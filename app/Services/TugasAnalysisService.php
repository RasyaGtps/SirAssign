<?php

namespace App\Services;

use App\Models\Tugas;
use App\Models\Materi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class TugasAnalysisService
{
    private DocumentIndexingService $documentIndexingService;
    private EmbeddingService $embeddingService;
    private PineconeService $pineconeService;

    public function __construct(
        DocumentIndexingService $documentIndexingService,
        EmbeddingService $embeddingService,
        PineconeService $pineconeService
    ) {
        $this->documentIndexingService = $documentIndexingService;
        $this->embeddingService = $embeddingService;
        $this->pineconeService = $pineconeService;
    }

    /**
     * Analyze tugas difficulty based on similarity with existing materi using AI
     *
     * @param Tugas $tugas
     * @return array
     */
    public function analyzeDifficulty(Tugas $tugas): array
    {
        try {
            Log::info("=== ANALYZE DIFFICULTY START ===", [
                'tugas_id' => $tugas->id,
                'tugas_judul' => $tugas->judul,
                'pertanyaan_length' => strlen($tugas->pertanyaan),
                'pertanyaan_preview' => substr($tugas->pertanyaan, 0, 300),
                'pertanyaan_full' => $tugas->pertanyaan
            ]);

            // Step 1: Generate embedding for the question
            $questionEmbedding = $this->embeddingService->generateEmbedding($tugas->pertanyaan);

            // Step 2: Search for similar content in materi (Top-5) - Remove filter to match test success
            $searchResults = $this->pineconeService->queryVector($questionEmbedding, 5);

            $matches = $searchResults['matches'] ?? [];
            $bestScore = 0;
            $matchedMaterials = [];
            $topContexts = [];

            Log::info("Pinecone search for difficulty analysis", [
                'tugas_id' => $tugas->id,
                'total_matches' => count($matches),
                'analysis_timestamp' => now()->toISOString()
            ]);

            foreach ($matches as $match) {
                $score = $match['score'] ?? 0;
                $metadata = $match['metadata'] ?? [];
                
                Log::info("Processing difficulty match", [
                    'id' => $match['id'],
                    'score' => $score,
                    'text_preview' => substr($metadata['text'] ?? '', 0, 100)
                ]);
                
                if ($score > $bestScore) {
                    $bestScore = $score;
                }

                if ($score > 0.3) { // Only include reasonably relevant matches
                    $contextText = $metadata['text'] ?? '';
                    $topContexts[] = $contextText;
                    
                    $matchedMaterials[] = [
                        'chunk_id' => $match['id'],
                        'score' => round($score, 4),
                        'text_preview' => substr($contextText, 0, 100) . '...',
                        'title' => $metadata['title'] ?? 'Unknown'
                    ];
                }
            }

            // Step 3: Use AI to determine difficulty based on question and context
            if (count($topContexts) > 0) {
                $aiAnalysis = $this->analyzeWithAI($tugas->pertanyaan, $topContexts, $tugas->mapel->nama_mapel, $bestScore);
            } else {
                Log::warning("No relevant contexts found for tugas: {$tugas->judul}");
                $aiAnalysis = [
                    'difficulty' => 'susah',
                    'explanation' => 'Tidak ditemukan materi relevan dalam database. Soal ini kemungkinan memerlukan pengetahuan tambahan.'
                ];
            }
            
            // Check if question is invalid (contains nonsense)
            if (isset($aiAnalysis['should_reject']) && $aiAnalysis['should_reject'] === true) {
                Log::warning("Invalid question detected, not updating database", [
                    'tugas_id' => $tugas->id,
                    'pertanyaan' => substr($tugas->pertanyaan, 0, 100)
                ]);
                
                // Set very low similarity score for nonsense questions
                $tugas->update([
                    'tingkat_kesulitan' => 'susah',
                    'similarity_score' => 0.05, // Very low score for nonsense
                    'matched_materials' => []
                ]);
                
                return [
                    'status' => 'rejected',
                    'message' => $aiAnalysis['explanation'],
                    'suggestion' => 'Silakan periksa kembali pertanyaan dan pastikan tidak mengandung teks acak atau tidak bermakna.'
                ];
            }
            
            $difficulty = $aiAnalysis['difficulty'];
            $aiExplanation = $aiAnalysis['explanation'];

            // Update tugas with analysis results
            $tugas->update([
                'tingkat_kesulitan' => $difficulty,
                'similarity_score' => round($bestScore, 4),
                'matched_materials' => array_slice($matchedMaterials, 0, 5) // Keep top 5 matches
            ]);

            Log::info("Tugas difficulty analysis completed", [
                'tugas_id' => $tugas->id,
                'tugas_judul' => $tugas->judul,
                'pertanyaan' => substr($tugas->pertanyaan, 0, 100) . '...',
                'difficulty' => $difficulty,
                'best_score' => $bestScore,
                'matches_count' => count($matchedMaterials),
                'ai_explanation' => $aiExplanation,
                'top_scores' => array_map(function($m) { return $m['score']; }, array_slice($matchedMaterials, 0, 3))
            ]);

            return [
                'success' => true,
                'difficulty' => $difficulty,
                'similarity_score' => round($bestScore, 4),
                'explanation' => $aiExplanation,
                'matched_materials_count' => count($matchedMaterials),
                'top_matches' => array_slice($matchedMaterials, 0, 3)
            ];

        } catch (Exception $e) {
            Log::error("Failed to analyze tugas difficulty: " . $e->getMessage());
            
            // Set default difficulty if analysis fails
            $tugas->update([
                'tingkat_kesulitan' => 'normal',
                'similarity_score' => null,
                'matched_materials' => null
            ]);

            throw $e;
        }
    }

    /**
     * Use Gemini AI to analyze difficulty based on question and retrieved context
     *
     * @param string $question
     * @param array $contexts
     * @param string $subject
     * @return array
     */
    private function analyzeWithAI(string $question, array $contexts, string $subject, float $similarityScore = 0): array
    {
        try {
            // Check for nonsense/invalid questions first
            if ($this->isNonsenseQuestion($question)) {
                Log::warning("Question detected as nonsense", ['question' => substr($question, 0, 100)]);
                return [
                    'difficulty' => 'susah',
                    'explanation' => 'Soal mengandung teks acak atau tidak dapat dipahami. Silakan periksa kembali pertanyaan Anda.',
                    'should_reject' => true
                ];
            }

            $similarityPercentage = $similarityScore * 100;
            
            if ($similarityPercentage >= 70) {
                Log::info("High similarity score detected, setting to easy", ['similarity' => $similarityPercentage]);
                return [
                    'difficulty' => 'mudah',
                    'explanation' => "Soal memiliki kemiripan tinggi (" . round($similarityPercentage, 1) . "%) dengan materi yang tersedia, jawaban dapat ditemukan langsung."
                ];
            } elseif ($similarityPercentage >= 40) {
                Log::info("Medium similarity score detected, setting to normal", ['similarity' => $similarityPercentage]);
                return [
                    'difficulty' => 'normal',
                    'explanation' => "Soal cukup relevan (" . round($similarityPercentage, 1) . "%) dengan materi, perlu pemahaman untuk menjawab."
                ];
            } else {
                Log::info("Low similarity score detected, setting to hard", ['similarity' => $similarityPercentage]);
                return [
                    'difficulty' => 'susah',
                    'explanation' => "Soal memiliki kemiripan rendah (" . round($similarityPercentage, 1) . "%) dengan materi yang tersedia, memerlukan pemahaman mendalam atau pengetahuan tambahan."
                ];
            }

        } catch (Exception $e) {
            Log::error("Analysis failed: " . $e->getMessage());
            
            return [
                'difficulty' => 'susah',
                'explanation' => 'Analisis gagal. Tingkat kesulitan diset ke susah sebagai default.'
            ];
        }
    }

    /**
     * Find matching materials for a tugas question using vector similarity
     *
     * @param Tugas $tugas
     * @return array
     */
    public function findMatchingMaterials(Tugas $tugas): array
    {
        try {
            Log::info("Finding matching materials for tugas: {$tugas->judul} (ID: {$tugas->id}) - UPDATE ANALYSIS");

            // Check for nonsense questions first
            if ($this->isNonsenseQuestion($tugas->pertanyaan)) {
                Log::info("Nonsense question detected, returning low similarity scores", [
                    'tugas_id' => $tugas->id,
                    'question' => substr($tugas->pertanyaan, 0, 100)
                ]);
                
                return [
                    'best_score' => 0.15, // Very low score for nonsense
                    'matched_materials' => [],
                    'total_matches' => 0
                ];
            }

            // Step 1: Generate embedding for the question
            $questionEmbedding = $this->embeddingService->generateEmbedding($tugas->pertanyaan);
            
            Log::info("Generated embedding for tugas question", [
                'tugas_id' => $tugas->id,
                'embedding_dimensions' => count($questionEmbedding),
                'first_5_values' => array_slice($questionEmbedding, 0, 5)
            ]);

            // Step 2: Search for similar content in materi (Top-10 for more options)
            $searchResults = $this->pineconeService->queryVector($questionEmbedding, 10);

            $matches = $searchResults['matches'] ?? [];
            $matchedMaterials = [];

            Log::info("Pinecone search completed", [
                'tugas_id' => $tugas->id,
                'total_matches' => count($matches),
                'query_timestamp' => now()->toISOString()
            ]);

            foreach ($matches as $match) {
                $score = $match['score'] ?? 0;
                $metadata = $match['metadata'] ?? [];
                
                Log::info("Processing match", [
                    'match_id' => $match['id'] ?? 'no_id',
                    'score' => $score,
                    'type' => $metadata['type'] ?? 'no_type',
                    'document_id' => $metadata['document_id'] ?? 'no_doc_id'
                ]);
                
                // Filter by type and mapel if available
                if (($metadata['type'] ?? '') === 'materi') {
                    $materiId = $metadata['document_id'] ?? null;
                    
                    if ($materiId) {
                        $materi = Materi::find($materiId);
                        if ($materi) {
                            $matchedMaterials[] = [
                                'id' => $materi->id,
                                'title' => $materi->title,
                                'mapel' => $materi->mapel->nama_mapel ?? 'Unknown',
                                'score' => round($score, 4),
                                'file_type' => $materi->file_type ?? 'unknown'
                            ];
                        }
                    }
                }
            }

            // Sort by score (highest first)
            usort($matchedMaterials, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            Log::info("Matching materials analysis completed", [
                'tugas_id' => $tugas->id,
                'total_found' => count($matchedMaterials),
                'top_3_scores' => array_map(function($m) { return $m['score']; }, array_slice($matchedMaterials, 0, 3)),
                'top_3_titles' => array_map(function($m) { return $m['title']; }, array_slice($matchedMaterials, 0, 3))
            ]);

            return $matchedMaterials;

        } catch (Exception $e) {
            Log::error("Failed to find matching materials for tugas {$tugas->id}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get difficulty statistics for a mapel
     *
     * @param int $mapelId
     * @return array
     */
    public function getDifficultyStats(int $mapelId): array
    {
        $stats = Tugas::where('mapel_id', $mapelId)
            ->selectRaw('tingkat_kesulitan, COUNT(*) as count')
            ->groupBy('tingkat_kesulitan')
            ->pluck('count', 'tingkat_kesulitan')
            ->toArray();

        $total = array_sum($stats);

        return [
            'total_tugas' => $total,
            'mudah' => $stats['mudah'] ?? 0,
            'normal' => $stats['normal'] ?? 0,
            'susah' => $stats['susah'] ?? 0,
            'mudah_percentage' => $total > 0 ? round(($stats['mudah'] ?? 0) / $total * 100, 1) : 0,
            'normal_percentage' => $total > 0 ? round(($stats['normal'] ?? 0) / $total * 100, 1) : 0,
            'susah_percentage' => $total > 0 ? round(($stats['susah'] ?? 0) / $total * 100, 1) : 0,
        ];
    }

    /**
     * Check if question contains nonsense or random characters
     *
     * @param string $question
     * @return bool
     */
    private function isNonsenseQuestion(string $question): bool
    {
        $cleanQuestion = trim($question);
        
        Log::info("=== CHECKING IF NONSENSE ===", [
            'original_length' => strlen($question),
            'clean_length' => strlen($cleanQuestion),
            'preview' => substr($cleanQuestion, 0, 200)
        ]);
        
        // Check for very short questions
        if (strlen($cleanQuestion) < 10) {
            Log::warning("NONSENSE: Too short", ['length' => strlen($cleanQuestion)]);
            return true;
        }

        // Check for specific patterns that indicate nonsense appended to legitimate questions
        $nonsensePatterns = [
            '/dwadadjkasdlkjawlkda/i',          // exact pattern from user's example
            '/dwadasdaw/i',                     // another pattern from user's example
            '/[kdjaskdjaskd]{6,}/i',            // specific nonsense patterns
            '/[aldjanwki]{6,}/i',
            // NOTE: Keyboard row patterns removed - too many false positives with Indonesian words
            // e.g. "bagaimana", "anaerobik", "global" incorrectly flagged
        ];

        foreach ($nonsensePatterns as $pattern) {
            if (preg_match($pattern, $cleanQuestion)) {
                Log::warning("NONSENSE: Pattern matched", [
                    'pattern' => $pattern, 
                    'question' => substr($cleanQuestion, 0, 100)
                ]);
                return true;
            }
        }
        
        Log::info("No nonsense patterns matched");

        // Check for words that are clearly random character sequences (10+ chars, no vowels or all consonants)
        $words = preg_split('/\s+/', $cleanQuestion);
        foreach ($words as $word) {
            $cleanWord = preg_replace('/[^a-zA-Z]/', '', $word);
            if (strlen($cleanWord) >= 8) {
                // Check if word has no vowels or is all consonants in a suspicious pattern
                $vowelCount = preg_match_all('/[aeiouAEIOU]/', $cleanWord);
                $consonantCount = preg_match_all('/[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]/', $cleanWord);
                
                // If word is long with very few vowels, likely nonsense
                if ($consonantCount > 6 && $vowelCount === 0) {
                    return true;
                }
                
                // Check for random character patterns in long words
                if (strlen($cleanWord) > 10 && $this->isRandomCharacterSequence($cleanWord)) {
                    return true;
                }
            }
        }

        // Check for excessive random characters (more than 30% non-letter characters excluding spaces and punctuation)
        $letters = preg_match_all('/[a-zA-Z]/', $cleanQuestion);
        $randomChars = preg_match_all('/[^a-zA-Z\s\?\!\.\,\;\:\(\)\[\]\/]/', $cleanQuestion);
        
        if ($letters > 0 && ($randomChars / $letters) > 0.3) {
            return true;
        }

        // Check for excessive repetitive patterns
        if (preg_match('/(.{2,})\1{3,}/', $cleanQuestion)) {
            return true;
        }

        // Split into words for further analysis
        $words = preg_split('/\s+/', $cleanQuestion);
        
        // Check if question has too many nonsense words
        $validWords = 0;
        $totalWords = count($words);
        
        if ($totalWords > 2) {
            foreach ($words as $word) {
                $cleanWord = preg_replace('/[^a-zA-Z]/', '', $word);
                // Valid words: have vowels, reasonable length, not all consonants
                // Increased max length to 25 for Indonesian compound words
                if (strlen($cleanWord) > 1 && strlen($cleanWord) < 25) {
                    $vowels = preg_match_all('/[aeiouAEIOU]/', $cleanWord);
                    $total = strlen($cleanWord);
                    
                    // Word is valid if it has some vowels and reasonable vowel ratio
                    if ($vowels > 0 && ($vowels / $total) > 0.05 && ($vowels / $total) < 0.9) {
                        $validWords++;
                    }
                }
            }
            
            // Log word analysis
            $ratio = $validWords / $totalWords;
            Log::info("=== WORD VALIDATION ANALYSIS ===", [
                'total_words' => $totalWords,
                'valid_words' => $validWords,
                'ratio' => round($ratio, 3),
                'threshold' => 0.5,
                'passes' => $ratio >= 0.5 || $totalWords < 10
            ]);
            
            // Relaxed threshold to 50% for file uploads with possible formatting
            // If less than 50% of words seem valid, likely contains nonsense
            if ($totalWords >= 10 && $ratio < 0.5) {
                Log::warning("NONSENSE: Low valid word ratio", [
                    'total' => $totalWords,
                    'valid' => $validWords,
                    'ratio' => round($ratio, 3),
                    'sample_words' => array_slice($words, 0, 10)
                ]);
                return true;
            }
        }

        Log::info("=== NONSENSE CHECK PASSED ===");
        return false;
    }

    /**
     * Check if a word appears to be a random character sequence
     *
     * @param string $word
     * @return bool
     */
    private function isRandomCharacterSequence(string $word): bool
    {
        $word = strtolower($word);
        $length = strlen($word);
        
        // More lenient for longer words
        if ($length < 12) {
            return false;
        }
        
        // Check for high character variety (possible random mashing)
        // Increased threshold to 0.85 to allow Indonesian words
        $uniqueChars = count(array_unique(str_split($word)));
        if ($uniqueChars / $length > 0.85) {
            return true;
        }
        
        // Check for suspicious consonant clusters (7+ consecutive)
        if (preg_match('/[bcdfghjklmnpqrstvwxyz]{7,}/', $word)) {
            return true;
        }
        
        // Check for patterns that look like keyboard mashing (6+ chars)
        $mashingPatterns = [
            '/[qwertyuiop]{6,}/',
            '/[asdfghjkl]{6,}/', 
            '/[zxcvbnm]{6,}/',
        ];
        
        foreach ($mashingPatterns as $pattern) {
            if (preg_match($pattern, $word)) {
                return true;
            }
        }
        
        return false;
    }
}