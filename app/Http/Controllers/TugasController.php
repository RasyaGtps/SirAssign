<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Mapel;
use App\Services\TugasAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TugasController extends Controller
{
    private TugasAnalysisService $tugasAnalysisService;

    public function __construct(TugasAnalysisService $tugasAnalysisService)
    {
        $this->tugasAnalysisService = $tugasAnalysisService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        $tugas = Tugas::with('mapel')
            ->whereIn('mapel_id', $userMapelIds)
            ->latest()
            ->get();
        
        return view('tugas.index', compact('tugas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get mapels that user has access to - same as MateriController
        $mapels = Auth::user()->mapels;
        
        return view('tugas.create', compact('mapels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'judul' => 'required|string|max:255',
            'pertanyaan' => 'required|string|min:10'
        ]);

        // Verify user has access to this mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($request->mapel_id, $userMapelIds)) {
            return back()->withErrors(['mapel_id' => 'Anda tidak memiliki akses ke mapel ini.']);
        }

        // Create tugas
        $tugas = Tugas::create([
            'mapel_id' => $request->mapel_id,
            'judul' => $request->judul,
            'pertanyaan' => $request->pertanyaan,
            'tingkat_kesulitan' => 'normal' // Default, will be updated by analysis
        ]);

        // Analyze difficulty automatically
        try {
            $analysis = $this->tugasAnalysisService->analyzeDifficulty($tugas);
            
            // Check if question should be rejected
            if (isset($analysis['status']) && $analysis['status'] === 'rejected') {
                return redirect()->back()
                    ->withErrors(['pertanyaan' => $analysis['message']])
                    ->withInput();
            }
            
            $message = 'Tugas berhasil dibuat! ' . $analysis['explanation'];
        } catch (\Exception $e) {
            $message = 'Tugas berhasil dibuat, tetapi analisis kesulitan gagal. Tingkat kesulitan diset ke normal.';
        }

        return redirect()->route('tugas.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tugas $tugas)
    {
        // Verify user has access to this tugas
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($tugas->mapel_id, $userMapelIds)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        return view('tugas.show', compact('tugas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tugas $tugas)
    {
        // Debug info
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        $hasAccess = in_array($tugas->mapel_id, $userMapelIds);
        
        // Log debug info
        Log::info('Edit access check', [
            'user_id' => Auth::id(),
            'user_mapels' => $userMapelIds, 
            'tugas_id' => $tugas->id,
            'tugas_mapel_id' => $tugas->mapel_id,
            'has_access' => $hasAccess
        ]);
        
        // If no access, show debug info in response
        if (!$hasAccess) {
            return response()->json([
                'error' => '403 Debug Info',
                'user_id' => Auth::id(),
                'user_mapels' => $userMapelIds,
                'tugas_id' => $tugas->id,
                'tugas_mapel_id' => $tugas->mapel_id,
                'has_access' => $hasAccess
            ], 403);
        }

        $mapels = Auth::user()->mapels;
        return view('tugas.edit', compact('tugas', 'mapels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tugas $tugas)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'judul' => 'required|string|max:255',
            'pertanyaan' => 'required|string|min:10'
        ]);

        // Verify user has access to both old and new mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($tugas->mapel_id, $userMapelIds) || !in_array($request->mapel_id, $userMapelIds)) {
            return back()->withErrors(['mapel_id' => 'Anda tidak memiliki akses ke mapel ini.']);
        }

        $tugas->update([
            'mapel_id' => $request->mapel_id,
            'judul' => $request->judul,
            'pertanyaan' => $request->pertanyaan,
        ]);

        $messages = [];

        // Always re-analyze difficulty when updating
        try {
            $analysis = $this->tugasAnalysisService->analyzeDifficulty($tugas);
            
            // Check if question should be rejected
            if (isset($analysis['status']) && $analysis['status'] === 'rejected') {
                return redirect()->back()
                    ->withErrors(['pertanyaan' => $analysis['message']])
                    ->withInput();
            }
            
            $messages[] = $analysis['explanation'];
        } catch (\Exception $e) {
            $messages[] = 'Analisis kesulitan gagal.';
            Log::error('Failed to analyze difficulty for tugas ' . $tugas->id . ': ' . $e->getMessage());
        }

        // Always find matching materials when updating
        try {
            $matchingMaterials = $this->tugasAnalysisService->findMatchingMaterials($tugas);
            
            if (!empty($matchingMaterials)) {
                $materialTitles = array_map(function($material) {
                    return $material['title'] . ' (skor: ' . number_format($material['score'], 3) . ')';
                }, array_slice($matchingMaterials, 0, 3)); // Top 3 materials with scores
                
                $messages[] = 'Ditemukan ' . count($matchingMaterials) . ' materi yang relevan: ' . implode(', ', $materialTitles);
            } else {
                $messages[] = 'Tidak ditemukan materi yang relevan dengan tugas ini.';
            }
        } catch (\Exception $e) {
            $messages[] = 'Pencarian materi relevan gagal.';
            Log::error('Failed to find matching materials for tugas ' . $tugas->id . ': ' . $e->getMessage());
        }

        $finalMessage = 'Tugas berhasil diperbarui!' . (!empty($messages) ? ' ' . implode(' ', $messages) : '');

        return redirect()->route('tugas.index')->with('success', $finalMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tugas $tugas)
    {
        // Verify user has access to this tugas
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($tugas->mapel_id, $userMapelIds)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $tugas->delete();

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Re-analyze difficulty for a tugas
     */
    public function reanalyze(Tugas $tugas)
    {
        // Verify user has access to this tugas
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($tugas->mapel_id, $userMapelIds)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        try {
            $analysis = $this->tugasAnalysisService->analyzeDifficulty($tugas);
            return redirect()->back()->with('success', 'Analisis berhasil diperbarui! ' . $analysis['explanation']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menganalisis tugas: ' . $e->getMessage());
        }
    }

    /**
     * Display tugas for a specific mapel
     */
    public function byMapel(Mapel $mapel)
    {
        // Check if user has access to this mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($mapel->id, $userMapelIds)) {
            abort(403, 'Anda tidak memiliki akses ke mata pelajaran ini.');
        }

        $tugas = $mapel->tugas()->latest()->get();
        
        return view('tugas.by-mapel', compact('tugas', 'mapel'));
    }

    /**
     * Get difficulty statistics for dashboard
     */
    public function getStats()
    {
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        $allStats = [];

        foreach (Auth::user()->mapels as $mapel) {
            $stats = $this->tugasAnalysisService->getDifficultyStats($mapel->id);
            $stats['mapel_name'] = $mapel->nama_mapel;
            $allStats[] = $stats;
        }

        return response()->json([
            'success' => true,
            'stats' => $allStats
        ]);
    }
}