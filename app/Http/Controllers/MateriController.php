<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Mapel;
use App\Services\DocumentIndexingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    private DocumentIndexingService $documentIndexingService;

    public function __construct(DocumentIndexingService $documentIndexingService)
    {
        $this->documentIndexingService = $documentIndexingService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya tampilkan materi dari mapel yang user bisa akses
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        $materi = Materi::with('mapel')
            ->whereIn('mapel_id', $userMapelIds)
            ->latest()
            ->get();
        return view('materi.index', compact('materi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya tampilkan mapel yang user bisa akses
        $mapels = Auth::user()->mapels;
        return view('materi.create', compact('mapels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verify user can only upload to their assigned mapels
        $allowedMapels = Auth::user()->mapels->pluck('id')->toArray();
        
        $request->validate([
            'mapel_id' => [
                'required',
                'exists:mapels,id',
                function ($attribute, $value, $fail) use ($allowedMapels) {
                    if (!in_array($value, $allowedMapels)) {
                        $fail('Anda tidak memiliki akses untuk mengupload materi ke mapel ini.');
                    }
                }
            ],
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:odf,docx,txt,pdf,xlsx|max:10240'
        ]);

        $file = $request->file('file');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('materi', $fileName, 'public');

        $materi = Materi::create([
            'mapel_id' => $request->mapel_id,
            'title' => $request->title,
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension()
        ]);

        // Auto-index to Pinecone (async/background job would be better for production)
        try {
            $this->documentIndexingService->indexMateri($materi);
            Log::info("Materi {$materi->id} successfully indexed to Pinecone");
        } catch (\Exception $e) {
            Log::error("Failed to index materi {$materi->id} to Pinecone: " . $e->getMessage());
            // Don't fail the upload if indexing fails
        }

        return redirect()->route('materi.index')->with('success', 'Materi berhasil diupload dan diindeks!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        return view('materi.show', compact('materi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materi $materi)
    {
        // Check if user has access to this materi's mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($materi->mapel_id, $userMapelIds)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit materi ini.');
        }

        // Only show mapels that user has access to
        $mapels = Auth::user()->mapels;
        return view('materi.edit', compact('materi', 'mapels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materi $materi)
    {
        // Check if user has access to this materi's mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($materi->mapel_id, $userMapelIds)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit materi ini.');
        }

        // Verify user can only update to their assigned mapels
        $request->validate([
            'mapel_id' => [
                'required',
                'exists:mapels,id',
                function ($attribute, $value, $fail) use ($userMapelIds) {
                    if (!in_array($value, $userMapelIds)) {
                        $fail('Anda tidak memiliki akses untuk memindahkan materi ke mapel ini.');
                    }
                }
            ],
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:odf,docx,txt,pdf,xlsx|max:10240'
        ]);

        $data = [
            'mapel_id' => $request->mapel_id,
            'title' => $request->title,
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            // Store new file
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('materi', $fileName, 'public');

            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientOriginalExtension();
        }

        $materi->update($data);

        // Re-index to Pinecone whenever materi is updated (title or file changed)
        try {
            $this->documentIndexingService->indexMateri($materi);
            Log::info("Materi {$materi->id} successfully re-indexed to Pinecone after update");
            $message = $request->hasFile('file') ? 
                'Materi berhasil diperbarui dan file baru telah diindeks!' : 
                'Materi berhasil diperbarui dan diindeks ulang!';
        } catch (\Exception $e) {
            Log::error("Failed to re-index materi {$materi->id} to Pinecone: " . $e->getMessage());
            $message = 'Materi berhasil diperbarui, tetapi terjadi error saat mengindeks ke Pinecone.';
        }

        return redirect()->route('materi.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materi $materi)
    {
        $materiId = $materi->id;
        
        // Delete file
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        // Remove from Pinecone
        try {
            $this->documentIndexingService->removeDocument('materi', $materiId);
            Log::info("Materi {$materiId} successfully removed from Pinecone");
        } catch (\Exception $e) {
            Log::error("Failed to remove materi {$materiId} from Pinecone: " . $e->getMessage());
        }

        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Download the specified resource.
     */
    public function download(Materi $materi)
    {
        // Check if user has access to this materi's mapel
        $userMapelIds = Auth::user()->mapels->pluck('id')->toArray();
        if (!in_array($materi->mapel_id, $userMapelIds)) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        if (!$materi->file_path || !Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $materi->file_path);
        return response()->download($filePath, $materi->title . '.' . $materi->file_type);
    }

    /**
     * Display materi by mapel.
     */
    public function byMapel(Mapel $mapel)
    {
        $materi = $mapel->materi()->latest()->get();
        return view('materi.by-mapel', compact('materi', 'mapel'));
    }
}