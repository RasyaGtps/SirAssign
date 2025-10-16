<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PineconeService
{
    private string $apiKey;
    private string $host;

    public function __construct()
    {
        $this->apiKey = config('services.pinecone.api_key');
        $this->host = config('services.pinecone.host');

        if (!$this->apiKey || !$this->host) {
            throw new Exception('Pinecone API key or host not configured');
        }
    }

    /**
     * Upsert vector to Pinecone
     *
     * @param string $id
     * @param array $values - embedding vector (1024 dimensions for multilingual-e5-large)
     * @param array $metadata
     * @return array
     */
    public function upsertVector(string $id, array $values, array $metadata = []): array
    {
        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->host . '/vectors/upsert', [
                'vectors' => [
                    [
                        'id' => $id,
                        'values' => $values,
                        'metadata' => $metadata
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Pinecone upsert failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to upsert vector to Pinecone: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Pinecone upsert error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Query vectors from Pinecone
     *
     * @param array $values - query embedding vector
     * @param int $topK - number of results to return
     * @param array $filter - optional metadata filter
     * @return array
     */
    public function queryVector(array $values, int $topK = 5, array $filter = []): array
    {
        try {
            $payload = [
                'vector' => $values,
                'topK' => $topK,
                'includeValues' => false,
                'includeMetadata' => true
            ];

            if (!empty($filter)) {
                $payload['filter'] = $filter;
            }

            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->host . '/query', $payload);

            if ($response->failed()) {
                Log::error('Pinecone query failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to query vectors from Pinecone: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Pinecone query error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete vector from Pinecone
     *
     * @param string $id
     * @return array
     */
    public function deleteVector(string $id): array
    {
        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->host . '/vectors/delete', [
                'ids' => [$id]
            ]);

            if ($response->failed()) {
                Log::error('Pinecone delete failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to delete vector from Pinecone: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Pinecone delete error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Batch upsert multiple vectors
     *
     * @param array $vectors - array of ['id' => string, 'values' => array, 'metadata' => array]
     * @return array
     */
    public function batchUpsertVectors(array $vectors): array
    {
        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->host . '/vectors/upsert', [
                'vectors' => $vectors
            ]);

            if ($response->failed()) {
                Log::error('Pinecone batch upsert failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to batch upsert vectors to Pinecone: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Pinecone batch upsert error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get index stats
     *
     * @return array
     */
    public function getIndexStats(): array
    {
        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->host . '/describe_index_stats');

            if ($response->failed()) {
                Log::error('Pinecone index stats failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to get index stats from Pinecone: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Pinecone index stats error: ' . $e->getMessage());
            throw $e;
        }
    }
}