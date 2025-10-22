<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get mapels for current user (guru)
        $mapels = $user->mapels()->with(['materi', 'tugas'])->get();
        
        // Calculate stats
        $totalMapels = $mapels->count();
        $totalMateri = $mapels->sum(function($mapel) {
            return $mapel->materi->count();
        });
        $totalTugas = $mapels->sum(function($mapel) {
            return $mapel->tugas->count();
        });

        // Recent activities
        $recentMateri = Materi::whereIn('mapel_id', $mapels->pluck('id'))
                              ->with('mapel')
                              ->latest()
                              ->take(5)
                              ->get();
                              
        $recentTugas = Tugas::whereIn('mapel_id', $mapels->pluck('id'))
                            ->with('mapel')
                            ->latest()
                            ->take(5)
                            ->get();

        // Get all tugas for difficulty stats
        $allTugas = Tugas::whereIn('mapel_id', $mapels->pluck('id'))->get();
        $difficultyStats = [
            'mudah' => $allTugas->where('tingkat_kesulitan', 'mudah')->count(),
            'normal' => $allTugas->where('tingkat_kesulitan', 'normal')->count(),
            'susah' => $allTugas->where('tingkat_kesulitan', 'susah')->count(),
        ];

        return view('dashboard', compact(
            'mapels', 
            'totalMapels', 
            'totalMateri', 
            'totalTugas',
            'recentMateri',
            'recentTugas',
            'difficultyStats'
        ));
    }
}
