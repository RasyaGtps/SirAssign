<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PineconeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home route - redirect based on auth status
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('mapel', [MapelController::class, 'index'])->name('mapel.index');
    Route::get('mapel/{mapel}', [MapelController::class, 'show'])->name('mapel.show');
    
    // Materi routes
    Route::get('materi/{materi}/download', [MateriController::class, 'download'])->name('materi.download');
    Route::get('mapel/{mapel}/materi', [MateriController::class, 'byMapel'])->name('materi.by-mapel');
    Route::resource('materi', MateriController::class);
    
    // Debug route - hapus setelah selesai debug
    Route::get('/debug-user', function() {
        $user = Auth::user();
        $userMapels = $user->mapels->pluck('id')->toArray();
        $tugas3 = App\Models\Tugas::find(3);
        $tugas4 = App\Models\Tugas::find(4);
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_mapels' => $userMapels,
            'tugas3' => $tugas3 ? [
                'id' => $tugas3->id,
                'mapel_id' => $tugas3->mapel_id,
                'has_access' => in_array($tugas3->mapel_id, $userMapels)
            ] : null,
            'tugas4' => $tugas4 ? [
                'id' => $tugas4->id, 
                'mapel_id' => $tugas4->mapel_id,
                'has_access' => in_array($tugas4->mapel_id, $userMapels)
            ] : null
        ]);
    });
    
    // Tugas routes
    Route::get('mapel/{mapel}/tugas', [TugasController::class, 'byMapel'])->name('tugas.by-mapel');
    Route::resource('tugas', TugasController::class)->parameters(['tugas' => 'tugas']);
    
    // Alternative routes for easier navigation
    Route::get('/mata-pelajaran', [MapelController::class, 'index'])->name('mata-pelajaran.index');
    
    // Search routes
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search', [SearchController::class, 'search'])->name('search.perform');
    
    // Simple API search route
    Route::post('/api/search', [SearchController::class, 'searchApi'])->name('search.api');
    
    // Pinecone API routes
    Route::prefix('api/pinecone')->group(function () {
        Route::post('/store', [PineconeController::class, 'store'])->name('pinecone.store');
        Route::post('/search', [PineconeController::class, 'search'])->name('pinecone.search');
        Route::delete('/delete', [PineconeController::class, 'delete'])->name('pinecone.delete');
        Route::post('/batch-store', [PineconeController::class, 'batchStore'])->name('pinecone.batch-store');
        Route::get('/stats', [PineconeController::class, 'stats'])->name('pinecone.stats');
        Route::get('/reindex-all-materi', [PineconeController::class, 'reindexAllMateri'])->name('pinecone.reindex-all-materi');
    });
});

require __DIR__.'/auth.php';
