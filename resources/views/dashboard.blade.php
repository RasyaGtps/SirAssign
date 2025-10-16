@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="mb-4">
                <i class="fas fa-tachometer-alt text-6xl" style="color: #570C49;"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                Dashboard Guru
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Selamat datang, {{ Auth::user()->name }}! Kelola mata pelajaran dan dokumentasi tugas Anda
            </p>
        </div>

        <!-- Stats Section -->
        <div class="max-w-6xl mx-auto mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4" style="border-left-color: #570C49;">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-book text-3xl" style="color: #570C49;"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Mata Pelajaran Anda</p>
                            <p class="text-2xl font-bold" style="color: #570C49;">{{ $totalMapels }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-3xl text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Materi</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalMateri }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tasks text-3xl text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Tugas</p>
                            <p class="text-2xl font-bold text-green-600">{{ $totalTugas }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-6xl mx-auto mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-bolt mr-2" style="color: #570C49;"></i>
                    Aksi Cepat
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('mapel.index') }}" 
                       class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition duration-300 border border-purple-200">
                        <i class="fas fa-book text-2xl text-purple-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-purple-800">Mata Pelajaran</p>
                            <p class="text-sm text-purple-600">Kelola mapel</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('materi.index') }}" 
                       class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition duration-300 border border-blue-200">
                        <i class="fas fa-file-alt text-2xl text-blue-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-blue-800">Materi</p>
                            <p class="text-sm text-blue-600">Upload materi</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('tugas.index') }}" 
                       class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition duration-300 border border-green-200">
                        <i class="fas fa-tasks text-2xl text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Tugas</p>
                            <p class="text-sm text-green-600">Kelola tugas</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition duration-300 border border-indigo-200">
                        <i class="fas fa-user-cog text-2xl text-indigo-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-indigo-800">Profile</p>
                            <p class="text-sm text-indigo-600">Edit profil</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Recent Materi -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-file-alt mr-2 text-blue-500"></i>
                            Materi Terbaru
                        </h3>
                        <a href="{{ route('materi.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($recentMateri->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentMateri as $materi)
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ Str::limit($materi->title, 30) }}</p>
                                            <p class="text-sm text-gray-600">{{ $materi->mapel->nama_mapel ?? 'Tidak ada mapel' }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $materi->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-6">Belum ada materi yang diupload</p>
                    @endif
                </div>

                <!-- Recent Tugas -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-tasks mr-2 text-green-500"></i>
                            Tugas Terbaru
                        </h3>
                        <a href="{{ route('tugas.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($recentTugas->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentTugas as $tugas)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-tasks text-green-500 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ Str::limit($tugas->title, 30) }}</p>
                                            <p class="text-sm text-gray-600">{{ $tugas->mapel->nama_mapel ?? 'Tidak ada mapel' }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $tugas->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-6">Belum ada tugas yang diupload</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Your Mapels -->
        @if($mapels->count() > 0)
            <div class="max-w-6xl mx-auto mt-8">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-book mr-2" style="color: #570C49;"></i>
                        Mata Pelajaran Anda
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($mapels as $mapel)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-medium text-gray-800">{{ $mapel->nama_mapel }}</h4>
                                    <i class="fas fa-book text-purple-500"></i>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($mapel->deskripsi, 60) }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-2">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            {{ $mapel->materi->count() }} Materi
                                        </span>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                            {{ $mapel->tugas->count() }} Tugas
                                        </span>
                                    </div>
                                    <a href="{{ route('mapel.show', $mapel) }}" class="text-purple-600 hover:text-purple-800 text-sm">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
