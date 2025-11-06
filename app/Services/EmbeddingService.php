<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EmbeddingService
{
    private string $geminiApiKey;
    private string $geminiApiUrl;

    public function __construct()
    {
        $this->geminiApiKey = 'AIzaSyDJKL5bc2HqJwwsef0HAeV8JMElQADxGhs';
        $this->geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/text-embedding-004:embedContent';
    }

    /**
     * Generate embedding for search queries using Google Gemini Embedding 004
     *
     * @param string $text
     * @return array
     */
    public function generateEmbedding(string $text): array
    {
        try {
            $response = Http::timeout(30)->post($this->geminiApiUrl . '?key=' . $this->geminiApiKey, [
                'model' => 'models/text-embedding-004',
                'content' => [
                    'parts' => [
                        [
                            'text' => $text
                        ]
                    ]
                ],
                'taskType' => 'RETRIEVAL_QUERY'
            ]);

            if ($response->failed()) {
                Log::error('Gemini embedding failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Gemini embedding failed: ' . $response->body());
            }

            $result = $response->json();
            
            if (!isset($result['embedding']['values'])) {
                throw new Exception('Invalid Gemini embedding response format');
            }

            $embedding = $result['embedding']['values'];
            
            // Gemini text-embedding-004 has 768 dimensions, but Pinecone expects 1024
            // Pad with zeros to reach 1024 dimensions
            while (count($embedding) < 1024) {
                $embedding[] = 0.0;
            }
            
            // Validate final dimensions
            if (count($embedding) !== 1024) {
                throw new Exception('Expected 1024 dimensions after padding, got ' . count($embedding));
            }

            return $embedding;

        } catch (Exception $e) {
            Log::error('Gemini embedding error: ' . $e->getMessage());
            
            // Fallback to dummy embedding
            Log::warning('Using dummy embedding as fallback');
            return $this->generateDummyEmbedding($text);
        }
    }

    /**
     * Generate embedding for document text using Google Gemini Embedding 004
     *
     * @param string $text
     * @return array
     */
    public function generateDocumentEmbedding(string $text): array
    {
        try {
            $response = Http::timeout(30)->post($this->geminiApiUrl . '?key=' . $this->geminiApiKey, [
                'model' => 'models/text-embedding-004',
                'content' => [
                    'parts' => [
                        [
                            'text' => $text
                        ]
                    ]
                ],
                'taskType' => 'RETRIEVAL_DOCUMENT'
            ]);

            if ($response->failed()) {
                Log::error('Gemini document embedding failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Gemini document embedding failed: ' . $response->body());
            }

            $result = $response->json();
            
            if (!isset($result['embedding']['values'])) {
                throw new Exception('Invalid Gemini embedding response format');
            }

            $embedding = $result['embedding']['values'];
            
            // Gemini text-embedding-004 has 768 dimensions, but Pinecone expects 1024
            // Pad with zeros to reach 1024 dimensions
            while (count($embedding) < 1024) {
                $embedding[] = 0.0;
            }
            
            // Validate final dimensions
            if (count($embedding) !== 1024) {
                throw new Exception('Expected 1024 dimensions after padding, got ' . count($embedding));
            }

            return $embedding;

        } catch (Exception $e) {
            Log::error('Gemini document embedding error: ' . $e->getMessage());
            
            // Fallback to dummy embedding
            Log::warning('Using dummy document embedding as fallback');
            return $this->generateDummyEmbedding("document: " . $text);
        }
    }

    /**
     * Generate embeddings for multiple texts using Gemini batch processing
     *
     * @param array $texts
     * @param string $taskType ('RETRIEVAL_QUERY' or 'RETRIEVAL_DOCUMENT')
     * @return array
     */
    public function generateBatchEmbeddings(array $texts, string $taskType = 'RETRIEVAL_QUERY'): array
    {
        try {
            $embeddings = [];
            
            // Gemini doesn't support batch embedding, so we process one by one
            foreach ($texts as $text) {
                $response = Http::timeout(30)->post($this->geminiApiUrl . '?key=' . $this->geminiApiKey, [
                    'model' => 'models/text-embedding-004',
                    'content' => [
                        'parts' => [
                            [
                                'text' => $text
                            ]
                        ]
                    ],
                    'taskType' => $taskType
                ]);

                if ($response->failed()) {
                    throw new Exception('Gemini batch embedding failed: ' . $response->body());
                }

                $result = $response->json();
                
                if (!isset($result['embedding']['values'])) {
                    throw new Exception('Invalid batch embedding response format');
                }

                $embeddings[] = $result['embedding']['values'];
                
                // Pad embedding to 1024 dimensions
                $lastEmbedding = &$embeddings[count($embeddings) - 1];
                while (count($lastEmbedding) < 1024) {
                    $lastEmbedding[] = 0.0;
                }
                
                // Add small delay to avoid rate limiting
                usleep(100000); // 0.1 second
            }

            return $embeddings;

        } catch (Exception $e) {
            Log::error('Gemini batch embedding error: ' . $e->getMessage());
            
            // Fallback to individual dummy embeddings
            return array_map(function($text) use ($taskType) {
                return $this->generateDummyEmbedding($taskType . ': ' . $text);
            }, $texts);
        }
    }

    /**
     * Generate text using Gemini AI (RAG Generation component)
     *
     * @param string $prompt
     * @return string
     */
    public function generateTextWithAI(string $prompt): string
    {
        try {
            $geminiGenerateUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent';
            
            $response = Http::timeout(30)->post($geminiGenerateUrl . '?key=' . $this->geminiApiKey, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini text generation failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Gemini generation failed: ' . $response->body());
            }

            $result = $response->json();
            
            Log::info('Gemini API response', ['result' => $result]);
            
            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                throw new Exception('Invalid Gemini generation response format');
            }

            $generatedText = $result['candidates'][0]['content']['parts'][0]['text'];
            
            Log::info('Gemini generated text', ['text' => substr($generatedText, 0, 200)]);

            return $generatedText;

        } catch (Exception $e) {
            Log::error('Gemini generation error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate deterministic dummy embedding (1024 dimensions) as fallback
     *
     * @param string $text
     * @return array
     */
    private function generateDummyEmbedding(string $text): array
    {
        // Generate deterministic dummy embedding based on text hash
        $hash = hash('sha256', $text);
        $embedding = [];
        
        // Create 1024-dimensional vector (match Pinecone index dimensions)
        for ($i = 0; $i < 1024; $i++) {
            $seed = hexdec(substr($hash, $i % 64, 2));
            $embedding[] = (float) (($seed / 255.0) * 2.0 - 1.0); // Normalize to [-1, 1]
        }
        
        // Normalize vector to unit length
        $magnitude = sqrt(array_sum(array_map(function($x) { return $x * $x; }, $embedding)));
        if ($magnitude > 0) {
            $embedding = array_map(function($x) use ($magnitude) { return $x / $magnitude; }, $embedding);
        }

        return $embedding;
    }
}