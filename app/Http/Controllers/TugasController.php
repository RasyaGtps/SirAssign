<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Mapel;
use App\Services\TugasAnalysisService;
use App\Services\TextProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TugasController extends Controller
{
    private TugasAnalysisService $tugasAnalysisService;
    private TextProcessingService $textProcessingService;

    public function __construct(
        TugasAnalysisService $tugasAnalysisService,
        TextProcessingService $textProcessingService
    ) {
        $this->tugasAnalysisService = $tugasAnalysisService;
        $this->textProcessingService = $textProcessingService;
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
        // Validation rules berbeda tergantung mode
        $rules = [
            'mapel_id' => 'required|exists:mapels,id',
            'judul' => 'required|string|max:255',
            'input_mode' => 'required|in:text,file'
        ];

        // Tambah rules sesuai mode
        if ($request->input_mode === 'text') {
            $rules['pertanyaan'] = 'required|string|min:10';
        } else {
            $rules['file'] = 'required|file|mimes:pdf,doc,docx,txt|max:10240';
        }

        $request->validate($rules);

        // Verify user has access to this mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($request->mapel_id, $userMapelIds)) {
            return back()->withErrors(['mapel_id' => 'Anda tidak memiliki akses ke mapel ini.']);
        }

        // Prepare data
        $data = [
            'mapel_id' => $request->mapel_id,
            'judul' => $request->judul,
            'input_mode' => $request->input_mode,
            'tingkat_kesulitan' => 'normal' // Default
        ];

        // Handle berdasarkan mode
        if ($request->input_mode === 'text') {
            // Mode ketik manual
            $data['pertanyaan'] = $request->pertanyaan;
        } else {
            // Mode upload file
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('tugas', $fileName, 'public');

            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientOriginalExtension();

            // Extract text dari file untuk pertanyaan
            try {
                $fullPath = storage_path('app/public/' . $filePath);
                $mimeType = $file->getMimeType();
                
                Log::info("=== START FILE EXTRACTION ===", [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'full_path' => $fullPath,
                    'file_exists' => file_exists($fullPath)
                ]);
                
                $extractedText = $this->textProcessingService->extractTextFromFile($fullPath, $mimeType);
                
                Log::info("=== EXTRACTED TEXT RESULT ===", [
                    'is_empty' => empty(trim($extractedText)),
                    'length' => strlen($extractedText),
                    'trimmed_length' => strlen(trim($extractedText)),
                    'word_count' => str_word_count($extractedText),
                    'first_100_chars' => substr($extractedText, 0, 100),
                    'last_100_chars' => substr($extractedText, -100),
                    'full_preview' => substr($extractedText, 0, 500)
                ]);
                
                if (empty(trim($extractedText))) {
                    // Delete file jika gagal extract
                    Storage::disk('public')->delete($filePath);
                    Log::error("EMPTY TEXT EXTRACTED - Deleting file");
                    return back()->withErrors(['file' => 'Gagal mengekstrak teks dari file. Pastikan file tidak kosong.'])->withInput();
                }
                
                $data['pertanyaan'] = $extractedText;
                
                Log::info("=== TEXT ASSIGNED TO PERTANYAAN ===", [
                    'pertanyaan_length' => strlen($data['pertanyaan']),
                    'pertanyaan_preview' => substr($data['pertanyaan'], 0, 200)
                ]);
                
            } catch (\Exception $e) {
                // Delete file jika gagal
                Storage::disk('public')->delete($filePath);
                Log::error("=== EXCEPTION IN FILE EXTRACTION ===", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withErrors(['file' => 'Gagal memproses file: ' . $e->getMessage()])->withInput();
            }
        }

        // Create tugas
        $tugas = Tugas::create($data);

        // Analyze difficulty automatically
        try {
            $analysis = $this->tugasAnalysisService->analyzeDifficulty($tugas);
            
            // Check if question should be rejected
            if (isset($analysis['status']) && $analysis['status'] === 'rejected') {
                // Delete uploaded file if exists
                if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
                    Storage::disk('public')->delete($tugas->file_path);
                }
                $tugas->delete();
                
                return redirect()->back()
                    ->withErrors(['pertanyaan' => $analysis['message']])
                    ->withInput();
            }
            
            // Refresh tugas from database to get updated similarity_score
            $tugas->refresh();
            
            // Build message with adjusted similarity score
            $similarityPercentage = round($tugas->similarity_score * 100, 1);
            
            // Check if score was adjusted (if explanation mentions irrelevance)
            $explanationLower = strtolower($analysis['explanation']);
            $irrelevanceKeywords = [
                'tidak relevan',
                'kurang relevan',
                'tidak ada hubungan',
                'tidak ada informasi',
                'tidak mencukupi',
                'tidak sesuai',
                'tidak berkaitan',
                'tidak tersedia',
                'di luar materi',
                'pengetahuan tambahan'
            ];
            
            $isIrrelevant = false;
            foreach ($irrelevanceKeywords as $keyword) {
                if (str_contains($explanationLower, $keyword)) {
                    $isIrrelevant = true;
                    break;
                }
            }
            
            // Build contextual message from AI explanation
            $aiExplanation = $analysis['explanation'];
            
            // Clean up technical terms from AI explanation (just in case)
            $cleanExplanation = str_replace('similarity score', 'kemiripan', $aiExplanation);
            $cleanExplanation = str_replace('Similarity score', 'Kemiripan', $cleanExplanation);
            
            // Insert adjusted similarity percentage into explanation
            // Pattern: "Soal tentang X pada mata pelajaran Y." → add percentage after "Y"
            $mapelName = $tugas->mapel->nama_mapel;
            $cleanExplanation = str_replace(
                "pada mata pelajaran {$mapelName}.",
                "pada mata pelajaran {$mapelName} memiliki kemiripan {$similarityPercentage}% dengan materi yang tersedia.",
                $cleanExplanation
            );
            
            // Save ringkasan to database (with adjusted percentage)
            $tugas->ringkasan = $cleanExplanation;
            $tugas->save();
            
            // Build notification message
            $message = "Tugas berhasil dibuat! {$cleanExplanation}";
            
            // Simplify if message is too long
            if (strlen($message) > 380) {
                $message = "Tugas berhasil dibuat! " . substr($cleanExplanation, 0, 280) . "...";
            }
        } catch (\Exception $e) {
            $message = 'Tugas berhasil dibuat, tetapi analisis kesulitan gagal. Tingkat kesulitan diset ke normal.';
            Log::error('Analysis failed: ' . $e->getMessage());
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
        // Validation rules berbeda tergantung mode
        $rules = [
            'mapel_id' => 'required|exists:mapels,id',
            'judul' => 'required|string|max:255',
            'input_mode' => 'required|in:text,file'
        ];

        // Tambah rules sesuai mode
        if ($request->input_mode === 'text') {
            $rules['pertanyaan'] = 'required|string|min:10';
        } else {
            // File optional saat edit (kalau ga upload berarti pakai file lama)
            $rules['file'] = 'nullable|file|mimes:pdf,doc,docx,txt|max:10240';
        }

        $request->validate($rules);

        // Verify user has access to both old and new mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($tugas->mapel_id, $userMapelIds) || !in_array($request->mapel_id, $userMapelIds)) {
            return back()->withErrors(['mapel_id' => 'Anda tidak memiliki akses ke mapel ini.']);
        }

        // Prepare update data
        $data = [
            'mapel_id' => $request->mapel_id,
            'judul' => $request->judul,
            'input_mode' => $request->input_mode,
        ];

        // Handle berdasarkan mode
        if ($request->input_mode === 'text') {
            // Mode text - update pertanyaan, hapus file jika ada
            $data['pertanyaan'] = $request->pertanyaan;
            
            // Hapus file lama jika ada
            if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
                Storage::disk('public')->delete($tugas->file_path);
            }
            $data['file_path'] = null;
            $data['file_type'] = null;
            
        } else {
            // Mode file
            if ($request->hasFile('file')) {
                // Ada file baru - hapus file lama
                if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
                    Storage::disk('public')->delete($tugas->file_path);
                }

                // Upload file baru
                $file = $request->file('file');
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('tugas', $fileName, 'public');

                $data['file_path'] = $filePath;
                $data['file_type'] = $file->getClientOriginalExtension();

                // Extract text dari file
                try {
                    $fullPath = storage_path('app/public/' . $filePath);
                    $mimeType = $file->getMimeType();
                    $extractedText = $this->textProcessingService->extractTextFromFile($fullPath, $mimeType);
                    
                    if (empty(trim($extractedText))) {
                        Storage::disk('public')->delete($filePath);
                        return back()->withErrors(['file' => 'Gagal mengekstrak teks dari file. Pastikan file tidak kosong.'])->withInput();
                    }
                    
                    $data['pertanyaan'] = $extractedText;
                } catch (\Exception $e) {
                    Storage::disk('public')->delete($filePath);
                    Log::error("Failed to extract text: " . $e->getMessage());
                    return back()->withErrors(['file' => 'Gagal memproses file: ' . $e->getMessage()])->withInput();
                }
            }
            // Kalau ga ada file baru, pertanyaan tetap pakai yang lama (dari file lama)
        }

        // Update tugas
        $tugas->update($data);

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
            
            // Refresh tugas from database to get updated similarity_score
            $tugas->refresh();
            
            // Build message with adjusted similarity score if irrelevant
            $explanationLower = strtolower($analysis['explanation']);
            $irrelevanceKeywords = [
                'tidak relevan',
                'kurang relevan',
                'tidak ada hubungan',
                'tidak ada informasi',
                'tidak mencukupi',
                'tidak sesuai',
                'tidak berkaitan',
                'tidak tersedia',
                'di luar materi',
                'pengetahuan tambahan'
            ];
            
            $isIrrelevant = false;
            foreach ($irrelevanceKeywords as $keyword) {
                if (str_contains($explanationLower, $keyword)) {
                    $isIrrelevant = true;
                    break;
                }
            }
            
            $similarityPercentage = round($tugas->similarity_score * 100, 1);
            
            // Build contextual message from AI explanation
            $aiExplanation = $analysis['explanation'];
            
            // Clean up technical terms from AI explanation (just in case)
            $cleanExplanation = str_replace('similarity score', 'kemiripan', $aiExplanation);
            $cleanExplanation = str_replace('Similarity score', 'Kemiripan', $cleanExplanation);
            
            // Insert adjusted similarity percentage into explanation
            // Pattern: "Soal tentang X pada mata pelajaran Y." → add percentage after "Y"
            $mapelName = $tugas->mapel->nama_mapel;
            $cleanExplanation = str_replace(
                "pada mata pelajaran {$mapelName}.",
                "pada mata pelajaran {$mapelName} memiliki kemiripan {$similarityPercentage}% dengan materi yang tersedia.",
                $cleanExplanation
            );
            
            // Save ringkasan to database (with adjusted percentage)
            $tugas->ringkasan = $cleanExplanation;
            $tugas->save();
            
            // Add contextual message
            if (strlen($cleanExplanation) > 280) {
                $messages[] = substr($cleanExplanation, 0, 250) . "...";
            } else {
                $messages[] = $cleanExplanation;
            }
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
                }, array_slice($matchingMaterials, 0, 3));
                
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

        // Delete file if exists
        if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
            Storage::disk('public')->delete($tugas->file_path);
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