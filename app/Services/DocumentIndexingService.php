<?php

namespace App\Services;

use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Exception;

class DocumentIndexingService
{
    private PineconeService $pineconeService;
    private EmbeddingService $embeddingService;
    private TextProcessingService $textProcessingService;

    public function __construct(
        PineconeService $pineconeService,
        EmbeddingService $embeddingService,
        TextProcessingService $textProcessingService
    ) {
        $this->pineconeService = $pineconeService;
        $this->embeddingService = $embeddingService;
        $this->textProcessingService = $textProcessingService;
    }

    /**
     * Index a materi document to Pinecone
     *
     * @param Materi $materi
     * @return array
     */
    public function indexMateri(Materi $materi): array
    {
        try {
            $results = [];
            
            // Get text content
            $text = $this->extractTextFromMateri($materi);
            
            if (empty(trim($text))) {
                throw new Exception('No text content found in materi');
            }

            // Create chunks
            $chunks = $this->textProcessingService->chunkText($text);
            
            if (empty($chunks)) {
                throw new Exception('No chunks created from materi text');
            }

            Log::info("Created " . count($chunks) . " chunks for materi {$materi->id}");

            // Create base metadata
            $baseMetadata = [
                'type' => 'materi',
                'materi_id' => $materi->id,
                'mapel_id' => $materi->mapel_id,
                'user_id' => Auth::id() ?? 0, // Use current user or 0 as fallback
                'title' => $materi->judul,
                'description' => $materi->deskripsi ?? '',
                'file_name' => $materi->file_path ? basename($materi->file_path) : '',
                'mapel_name' => $materi->mapel->nama_mapel ?? '',
            ];

            // Process each chunk
            foreach ($chunks as $chunkIndex => $chunk) {
                // Ensure chunk is an array with required structure
                if (!is_array($chunk)) {
                    Log::warning("Chunk at index {$chunkIndex} is not an array, skipping");
                    continue;
                }

                if (!isset($chunk['index']) || !isset($chunk['text'])) {
                    Log::warning("Chunk at index {$chunkIndex} missing required fields, skipping");
                    continue;
                }

                $chunkId = "materi_{$materi->id}_chunk_{$chunk['index']}";
                
                Log::info("Processing chunk {$chunkId}");

                // Generate embedding
                $embedding = $this->embeddingService->generateDocumentEmbedding($chunk['text']);
                
                // Simple metadata - only ID and text
                $metadata = [
                    'id' => $chunkId,
                    'text' => $chunk['text']
                ];
                
                // Upsert to Pinecone
                $result = $this->pineconeService->upsertVector($chunkId, $embedding, $metadata);
                $results[] = [
                    'chunk_id' => $chunkId,
                    'chunk_index' => $chunk['index'],
                    'result' => $result
                ];
            }

            Log::info("Successfully indexed materi {$materi->id} with " . count($chunks) . " chunks");
            
            return [
                'success' => true,
                'materi_id' => $materi->id,
                'chunks_count' => count($chunks),
                'results' => $results
            ];

        } catch (Exception $e) {
            Log::error("Failed to index materi {$materi->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Index a tugas document to Pinecone
     *
     * @param Tugas $tugas
     * @return array
     */
    public function indexTugas(Tugas $tugas): array
    {
        try {
            $results = [];
            
            // Get text content
            $text = $this->extractTextFromTugas($tugas);
            
            if (empty(trim($text))) {
                throw new Exception('No text content found in tugas');
            }

            // Create chunks
            $chunks = $this->textProcessingService->chunkText($text);
            
            if (empty($chunks)) {
                throw new Exception('No chunks created from tugas text');
            }

            // Create base metadata
            $baseMetadata = [
                'type' => 'tugas',
                'tugas_id' => $tugas->id,
                'mapel_id' => $tugas->mapel_id,
                'user_id' => $tugas->user_id,
                'title' => $tugas->judul,
                'description' => $tugas->deskripsi ?? '',
                'deadline' => $tugas->deadline?->toISOString(),
                'file_name' => $tugas->file_path ? basename($tugas->file_path) : '',
                'mapel_name' => $tugas->mapel->nama_mapel ?? '',
            ];

            // Process each chunk
            foreach ($chunks as $chunk) {
                $chunkId = "tugas_{$tugas->id}_chunk_{$chunk['index']}";
                
                // Generate embedding
                $embedding = $this->embeddingService->generateDocumentEmbedding($chunk['text']);
                
                // Simple metadata - only ID and text
                $metadata = [
                    'id' => $chunkId,
                    'text' => $chunk['text']
                ];
                
                // Upsert to Pinecone
                $result = $this->pineconeService->upsertVector($chunkId, $embedding, $metadata);
                $results[] = [
                    'chunk_id' => $chunkId,
                    'chunk_index' => $chunk['index'],
                    'result' => $result
                ];
            }

            Log::info("Successfully indexed tugas {$tugas->id} with " . count($chunks) . " chunks");
            
            return [
                'success' => true,
                'tugas_id' => $tugas->id,
                'chunks_count' => count($chunks),
                'results' => $results
            ];

        } catch (Exception $e) {
            Log::error("Failed to index tugas {$tugas->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove document from Pinecone index
     *
     * @param string $type ('materi' or 'tugas')
     * @param int $id
     * @return array
     */
    public function removeDocument(string $type, int $id): array
    {
        try {
            // Get all chunk IDs for this document
            $filter = [
                'type' => ['$eq' => $type],
                $type . '_id' => ['$eq' => $id]
            ];

            // Query to get all chunks for this document
            $dummyEmbedding = array_fill(0, 1024, 0.0); // Dummy vector for filter-only query
            $queryResult = $this->pineconeService->queryVector($dummyEmbedding, 100, $filter);

            $deletedChunks = [];
            
            if (isset($queryResult['matches'])) {
                foreach ($queryResult['matches'] as $match) {
                    $chunkId = $match['id'];
                    $this->pineconeService->deleteVector($chunkId);
                    $deletedChunks[] = $chunkId;
                }
            }

            Log::info("Successfully removed {$type} {$id} with " . count($deletedChunks) . " chunks");
            
            return [
                'success' => true,
                'type' => $type,
                'document_id' => $id,
                'deleted_chunks' => $deletedChunks,
                'chunks_count' => count($deletedChunks)
            ];

        } catch (Exception $e) {
            Log::error("Failed to remove {$type} {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Search documents by query
     *
     * @param string $query
     * @param array $filters
     * @param int $topK
     * @return array
     */
    public function searchDocuments(string $query, array $filters = [], int $topK = 10): array
    {
        try {
            // Generate embedding for search query
            $queryEmbedding = $this->embeddingService->generateEmbedding($query);
            
            // Search in Pinecone
            $results = $this->pineconeService->queryVector($queryEmbedding, $topK, $filters);
            
            // Group results by document
            $groupedResults = [];
            
            if (isset($results['matches'])) {
                foreach ($results['matches'] as $match) {
                    $metadata = $match['metadata'] ?? [];
                    $type = $metadata['type'] ?? 'unknown';
                    $documentId = $metadata[$type . '_id'] ?? 'unknown';
                    
                    $key = $type . '_' . $documentId;
                    
                    if (!isset($groupedResults[$key])) {
                        $groupedResults[$key] = [
                            'type' => $type,
                            'document_id' => $documentId,
                            'title' => $metadata['title'] ?? '',
                            'mapel_name' => $metadata['mapel_name'] ?? '',
                            'chunks' => [],
                            'max_score' => 0,
                        ];
                    }
                    
                    $groupedResults[$key]['chunks'][] = [
                        'chunk_id' => $match['id'],
                        'score' => $match['score'] ?? 0,
                        'text' => $metadata['text'] ?? '',
                        'chunk_index' => $metadata['chunk_index'] ?? 0,
                    ];
                    
                    $groupedResults[$key]['max_score'] = max(
                        $groupedResults[$key]['max_score'],
                        $match['score'] ?? 0
                    );
                }
            }
            
            // Sort by max score
            uasort($groupedResults, function($a, $b) {
                return $b['max_score'] <=> $a['max_score'];
            });
            
            return [
                'success' => true,
                'query' => $query,
                'total_chunks' => count($results['matches'] ?? []),
                'documents' => array_values($groupedResults)
            ];

        } catch (Exception $e) {
            Log::error("Failed to search documents: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract text from materi
     *
     * @param Materi $materi
     * @return string
     */
    private function extractTextFromMateri(Materi $materi): string
    {
        $text = '';
        
        // Extract text from file only (no title/description)
        if ($materi->file_path) {
            try {
                // Try multiple paths
                $possiblePaths = [
                    Storage::path($materi->file_path), // Standard storage path
                    public_path('storage/' . $materi->file_path), // Public storage path
                    public_path('storage/materi/' . basename($materi->file_path)), // Public materi folder
                    storage_path('app/public/' . $materi->file_path), // App public path
                ];
                
                $filePath = null;
                $mimeType = null;
                
                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $filePath = $path;
                        // Determine mime type
                        if (Storage::exists($materi->file_path)) {
                            $mimeType = Storage::mimeType($materi->file_path);
                        } else {
                            $mimeType = mime_content_type($path) ?: 'application/pdf';
                        }
                        break;
                    }
                }
                
                if ($filePath) {
                    Log::info("Found file at: {$filePath}");
                    $fileText = $this->textProcessingService->extractTextFromFile($filePath, $mimeType);
                    
                    if (!empty(trim($fileText))) {
                        $text .= $fileText;
                        Log::info("Extracted " . strlen($fileText) . " characters from file");
                    } else {
                        Log::warning("File text extraction returned empty result");
                    }
                } else {
                    Log::warning("File not found at any path for materi {$materi->id}, file_path: {$materi->file_path}");
                    // List tried paths for debugging
                    Log::warning("Tried paths: " . implode(', ', $possiblePaths));
                }
                
            } catch (Exception $e) {
                Log::error("Failed to extract text from materi file {$materi->file_path}: " . $e->getMessage());
            }
        } else {
            Log::warning("No file_path found for materi {$materi->id}");
        }
        
        return trim($text);
    }

    /**
     * Extract text from tugas
     *
     * @param Tugas $tugas
     * @return string
     */
    private function extractTextFromTugas(Tugas $tugas): string
    {
        $text = '';
        
        // Add title and description
        $text .= $tugas->judul . "\n\n";
        if ($tugas->deskripsi) {
            $text .= $tugas->deskripsi . "\n\n";
        }
        
        // Add deadline info
        if ($tugas->deadline) {
            $text .= "Deadline: " . $tugas->deadline->format('d/m/Y H:i') . "\n\n";
        }
        
        // Extract text from file if exists
        if ($tugas->file_path && Storage::exists($tugas->file_path)) {
            $filePath = Storage::path($tugas->file_path);
            $mimeType = Storage::mimeType($tugas->file_path);
            
            try {
                $fileText = $this->textProcessingService->extractTextFromFile($filePath, $mimeType);
                $text .= $fileText;
            } catch (Exception $e) {
                Log::warning("Failed to extract text from tugas file {$tugas->file_path}: " . $e->getMessage());
            }
        }
        
        return trim($text);
    }
}