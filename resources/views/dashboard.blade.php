@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="mb-4">
                <i class="fas fa-graduation-cap text-6xl" style="color: #570C49;"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                👋 Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-gray-700 text-lg max-w-2xl mx-auto font-medium">
                Sistem Rekomendasi Assignment - Kelola pembelajaran dengan lebih mudah
            </p>
        </div>

        <!-- Stats Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Mata Pelajaran</p>
                            <p class="text-4xl font-bold">{{ $totalMapels }}</p>
                            <p class="text-purple-200 text-xs mt-1">Total mapel Anda</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-book text-3xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Materi</p>
                            <p class="text-4xl font-bold">{{ $totalMateri }}</p>
                            <p class="text-blue-200 text-xs mt-1">Dokumen pembelajaran</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-file-pdf text-3xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Tugas</p>
                            <p class="text-4xl font-bold">{{ $totalTugas }}</p>
                            <p class="text-green-200 text-xs mt-1">Assignment dibuat</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-tasks text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-orange-100 text-sm font-medium mb-2">Tingkat Kesulitan</p>
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-smile text-green-300"></i>
                                        <span class="text-xs">Mudah</span>
                                    </div>
                                    <span class="font-bold">{{ $difficultyStats['mudah'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-meh text-yellow-300"></i>
                                        <span class="text-xs">Normal</span>
                                    </div>
                                    <span class="font-bold">{{ $difficultyStats['normal'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-frown text-red-300"></i>
                                        <span class="text-xs">Susah</span>
                                    </div>
                                    <span class="font-bold">{{ $difficultyStats['susah'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-chart-simple text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-3 mr-3">
                        <i class="fas fa-rocket text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Aksi Cepat</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('materi.create') }}" 
                       class="group flex flex-col items-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition duration-300 border-2 border-blue-300 hover:border-blue-500 transform hover:-translate-y-1">
                        <div class="bg-blue-500 rounded-full w-20 h-20 flex items-center justify-center mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-cloud-upload-alt text-white text-3xl"></i>
                        </div>
                        <p class="font-bold text-blue-800 mb-1">Upload Materi</p>
                        <p class="text-sm text-blue-600 text-center">Tambah dokumen PDF</p>
                    </a>
                    
                    <a href="{{ route('tugas.create') }}" 
                       class="group flex flex-col items-center p-6 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition duration-300 border-2 border-green-300 hover:border-green-500 transform hover:-translate-y-1">
                        <div class="bg-green-500 rounded-full w-20 h-20 flex items-center justify-center mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-edit text-white text-3xl"></i>
                        </div>
                        <p class="font-bold text-green-800 mb-1">Buat Tugas</p>
                        <p class="text-sm text-green-600 text-center">Tambah assignment baru</p>
                    </a>
                    
                    <a href="{{ route('tugas.index') }}" 
                       class="group flex flex-col items-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 rounded-xl transition duration-300 border-2 border-orange-300 hover:border-orange-500 transform hover:-translate-y-1">
                        <div class="bg-orange-500 rounded-full w-20 h-20 flex items-center justify-center mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-list-check text-white text-3xl"></i>
                        </div>
                        <p class="font-bold text-orange-800 mb-1">Lihat Tugas</p>
                        <p class="text-sm text-orange-600 text-center">Kelola semua tugas</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Recent Materi -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                                    <i class="fas fa-file-pdf text-white text-xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-white">Materi Terbaru</h3>
                            </div>
                            <a href="{{ route('materi.index') }}" class="text-white hover:text-blue-100 text-sm font-medium flex items-center">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        @if($recentMateri->count() > 0)
                            <div class="space-y-2">
                                @foreach($recentMateri as $materi)
                                    <div class="group flex items-start p-3 bg-gradient-to-r from-blue-50 to-transparent hover:from-blue-100 rounded-lg transition duration-300 border-l-4 border-blue-400">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="bg-blue-100 rounded-lg p-2 group-hover:bg-blue-200 transition duration-300">
                                                <i class="fas fa-file-pdf text-blue-600 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="font-semibold text-gray-800 group-hover:text-blue-600 transition duration-300">{{ Str::limit($materi->title, 35) }}</p>
                                            <div class="flex items-center justify-between mt-1">
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-book text-xs mr-1"></i>
                                                    {{ $materi->mapel->nama_mapel ?? 'Tidak ada mapel' }}
                                                </p>
                                                <span class="text-xs text-gray-500">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ $materi->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500">Belum ada materi yang diupload</p>
                                <a href="{{ route('materi.create') }}" class="inline-block mt-3 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                                    Upload Materi Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Tugas -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                                    <i class="fas fa-tasks text-white text-xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-white">Tugas Terbaru</h3>
                            </div>
                            <a href="{{ route('tugas.index') }}" class="text-white hover:text-green-100 text-sm font-medium flex items-center">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        @if($recentTugas->count() > 0)
                            <div class="space-y-2">
                                @foreach($recentTugas as $tugas)
                                    <div class="group flex items-start p-3 bg-gradient-to-r from-green-50 to-transparent hover:from-green-100 rounded-lg transition duration-300 border-l-4 border-green-400">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="bg-green-100 rounded-lg p-2 group-hover:bg-green-200 transition duration-300">
                                                <i class="fas fa-clipboard-list text-green-600 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-start justify-between">
                                                <p class="font-semibold text-gray-800 group-hover:text-green-600 transition duration-300 flex-1">{{ Str::limit($tugas->judul, 30) }}</p>
                                                @if($tugas->tingkat_kesulitan)
                                                    <span class="ml-2 px-2 py-1 text-xs font-bold rounded-full
                                                        {{ $tugas->tingkat_kesulitan == 'mudah' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $tugas->tingkat_kesulitan == 'normal' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                        {{ $tugas->tingkat_kesulitan == 'susah' ? 'bg-red-100 text-red-700' : '' }}">
                                                        {{ ucfirst($tugas->tingkat_kesulitan) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between mt-1">
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-book text-xs mr-1"></i>
                                                    {{ $tugas->mapel->nama_mapel ?? 'Tidak ada mapel' }}
                                                </p>
                                                <span class="text-xs text-gray-500">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ $tugas->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            @if($tugas->similarity_score)
                                                <div class="mt-1">
                                                    <span class="text-xs text-gray-500">
                                                        <i class="fas fa-percentage mr-1"></i>
                                                        Kemiripan: {{ round($tugas->similarity_score * 100, 1) }}%
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500">Belum ada tugas yang dibuat</p>
                                <a href="{{ route('tugas.create') }}" class="inline-block mt-3 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300">
                                    Buat Tugas Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Your Mapels -->
        @if($mapels->count() > 0)
            <div class="max-w-7xl mx-auto mt-8">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-3 mr-3">
                            <i class="fas fa-book-open text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Mata Pelajaran Anda</h3>
                            <p class="text-sm text-gray-600">{{ $totalMapels }} mata pelajaran</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($mapels as $mapel)
                            <div class="group bg-gradient-to-br from-white to-purple-50 border-2 border-purple-200 rounded-xl p-5 hover:shadow-xl hover:border-purple-400 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 text-lg group-hover:text-purple-600 transition duration-300">{{ $mapel->nama_mapel }}</h4>
                                    </div>
                                    <div class="bg-purple-100 group-hover:bg-purple-200 rounded-full p-3 transition duration-300">
                                        <i class="fas fa-book text-purple-600 text-lg"></i>
                                    </div>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($mapel->deskripsi, 80) }}</p>
                                
                                <div class="flex items-center space-x-2 mb-4">
                                    <div class="flex-1 bg-blue-100 rounded-lg p-2 text-center">
                                        <p class="text-2xl font-bold text-blue-700">{{ $mapel->materi->count() }}</p>
                                        <p class="text-xs text-blue-600 font-medium">Materi</p>
                                    </div>
                                    <div class="flex-1 bg-green-100 rounded-lg p-2 text-center">
                                        <p class="text-2xl font-bold text-green-700">{{ $mapel->tugas->count() }}</p>
                                        <p class="text-xs text-green-600 font-medium">Tugas</p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('mapel.show', $mapel) }}" class="block text-center px-3 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition duration-300 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
