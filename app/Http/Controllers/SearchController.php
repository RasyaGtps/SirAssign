<?php

namespace App\Http\Controllers;

use App\Services\DocumentIndexingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    private DocumentIndexingService $documentIndexingService;

    public function __construct(DocumentIndexingService $documentIndexingService)
    {
        $this->documentIndexingService = $documentIndexingService;
    }

    /**
     * Show search form
     */
    public function index()
    {
        return view('search.index');
    }

    /**
     * Perform search (API - simplified response)
     */
    public function searchApi(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3|max:255',
        ]);

        $query = $request->input('query');

        try {
            // Filter by user's mapels only
            $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
            $filter = [];
            
            if (!empty($userMapelIds)) {
                $filter['mapel_id'] = ['$in' => $userMapelIds];
            }

            $searchResults = $this->documentIndexingService->searchDocuments($query, $filter, 10);
            $documents = $searchResults['documents'] ?? [];
            
            // Enhanced response - include scores for testing
            $enhanced = [];
            foreach ($documents as $doc) {
                foreach ($doc['chunks'] as $chunk) {
                    $enhanced[] = [
                        'id' => $chunk['chunk_id'],
                        'score' => round($chunk['score'], 4),
                        'text' => $chunk['text']
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'query' => $query,
                'total_results' => count($enhanced),
                'results' => $enhanced
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Pencarian gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}