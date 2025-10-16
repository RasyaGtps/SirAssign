<?php

namespace App\Http\Controllers;

use App\Services\PineconeService;
use App\Services\EmbeddingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class PineconeController extends Controller
{
    private PineconeService $pineconeService;
    private EmbeddingService $embeddingService;

    public function __construct(PineconeService $pineconeService, EmbeddingService $embeddingService)
    {
        $this->pineconeService = $pineconeService;
        $this->embeddingService = $embeddingService;
    }

    /**
     * Store document with embedding to Pinecone
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|string|max:255',
                'text' => 'required|string',
                'title' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:100',
                'mapel_id' => 'nullable|integer',
                'user_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Generate embedding for the document
            $embedding = $this->embeddingService->generateDocumentEmbedding($data['text']);

            // Prepare metadata
            $metadata = [
                'title' => $data['title'] ?? '',
                'text' => $data['text'],
                'type' => $data['type'] ?? 'document',
                'created_at' => now()->toISOString(),
            ];

            if (isset($data['mapel_id'])) {
                $metadata['mapel_id'] = $data['mapel_id'];
            }

            if (isset($data['user_id'])) {
                $metadata['user_id'] = $data['user_id'];
            }

            // Upsert to Pinecone
            $result = $this->pineconeService->upsertVector($data['id'], $embedding, $metadata);

            return response()->json([
                'success' => true,
                'message' => 'Document stored successfully',
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search documents using semantic search
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string',
                'top_k' => 'nullable|integer|min:1|max:20',
                'filter' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $request->input('query');
            $topK = $request->input('top_k', 5);
            $filter = $request->input('filter', []);

            // Generate embedding for the search query
            $queryEmbedding = $this->embeddingService->generateEmbedding($query);

            // Search in Pinecone
            $results = $this->pineconeService->queryVector($queryEmbedding, $topK, $filter);

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'query' => $query,
                'results' => $results
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document from Pinecone
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $id = $request->input('id');

            // Delete from Pinecone
            $result = $this->pineconeService->deleteVector($id);

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully',
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch store multiple documents
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function batchStore(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'documents' => 'required|array|min:1|max:100',
                'documents.*.id' => 'required|string|max:255',
                'documents.*.text' => 'required|string',
                'documents.*.title' => 'nullable|string|max:255',
                'documents.*.type' => 'nullable|string|max:100',
                'documents.*.mapel_id' => 'nullable|integer',
                'documents.*.user_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $documents = $request->input('documents');
            $vectors = [];

            // Generate embeddings for all documents
            foreach ($documents as $doc) {
                $embedding = $this->embeddingService->generateDocumentEmbedding($doc['text']);

                $metadata = [
                    'title' => $doc['title'] ?? '',
                    'text' => $doc['text'],
                    'type' => $doc['type'] ?? 'document',
                    'created_at' => now()->toISOString(),
                ];

                if (isset($doc['mapel_id'])) {
                    $metadata['mapel_id'] = $doc['mapel_id'];
                }

                if (isset($doc['user_id'])) {
                    $metadata['user_id'] = $doc['user_id'];
                }

                $vectors[] = [
                    'id' => $doc['id'],
                    'values' => $embedding,
                    'metadata' => $metadata
                ];
            }

            // Batch upsert to Pinecone
            $result = $this->pineconeService->batchUpsertVectors($vectors);

            return response()->json([
                'success' => true,
                'message' => 'Documents stored successfully',
                'count' => count($vectors),
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store documents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Pinecone index statistics
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->pineconeService->getIndexStats();

            return response()->json([
                'success' => true,
                'message' => 'Index stats retrieved successfully',
                'data' => $stats
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get index stats: ' . $e->getMessage()
            ], 500);
        }
    }
}