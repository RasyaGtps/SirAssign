<?php
namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Support\Facades\Auth;

class MapelController extends Controller
{
    public function index()
    {
        // Hanya tampilkan mapel yang diajar oleh guru yang sedang login
        $mapels = Auth::user()->mapels()->with(['materi', 'tugas'])->get();
        return view('mapel.index', compact('mapels'));
    }

    public function show(Mapel $mapel)
    {
        // Pastikan guru hanya bisa melihat mapel yang dia ajar
        if (!Auth::user()->mapels->contains($mapel)) {
            abort(403, 'Anda tidak memiliki akses ke mata pelajaran ini.');
        }
        
        return view('mapel.show', compact('mapel'));
    }
}
