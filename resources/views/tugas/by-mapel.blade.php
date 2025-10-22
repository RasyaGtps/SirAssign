@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-3xl shadow-2xl p-8 mb-8 text-white">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-2xl p-4">
                            <i class="fas fa-clipboard-list text-4xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Tugas - {{ $mapel->nama_mapel }}</h1>
                            <p class="text-purple-200">Semua assignment dan latihan</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('mapel.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold rounded-xl transition duration-300 border-2 border-white border-opacity-30">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <a href="{{ route('tugas.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white text-purple-600 font-bold rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Tugas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-5 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-full p-2">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-bold text-green-800">✅ Berhasil!</p>
                                <p class="text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-6 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Total Tugas</p>
                        <p class="text-4xl font-bold">{{ $tugas->count() }}</p>
                        <p class="text-green-200 text-xs mt-1">Assignment untuk {{ $mapel->nama_mapel }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-clipboard-list text-4xl"></i>
                    </div>
                </div>
            </div>

            <!-- Tugas Grid -->
            @if($tugas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tugas as $item)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1 border-2 border-gray-100 hover:border-green-300">
                            <!-- Card Header -->
                            <div class="p-6 pb-4 bg-gradient-to-br from-green-50 to-white">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-800 line-clamp-2 mb-3">{{ $item->judul }}</h3>
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($item->tingkat_kesulitan)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                                    @if($item->tingkat_kesulitan == 'mudah') bg-green-500 text-white
                                                    @elseif($item->tingkat_kesulitan == 'normal') bg-yellow-500 text-white
                                                    @else bg-red-500 text-white @endif">
                                                    {{ ucfirst($item->tingkat_kesulitan) }}
                                                </span>
                                            @endif
                                            @if($item->similarity_score)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">
                                                    {{ round($item->similarity_score * 100, 1) }}%
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="bg-green-100 rounded-xl p-3">
                                            <i class="fas fa-clipboard-list text-3xl text-green-600"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 py-4 bg-white border-t-2 border-gray-100">
                                <div class="flex justify-between items-center gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('tugas.show', $item->id) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-bold rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                        <i class="fas fa-eye mr-2"></i>
                                        Lihat
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('tugas.edit', $item->id) }}" 
                                       class="inline-flex items-center justify-center px-3 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('tugas.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center px-3 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl shadow-xl p-16 text-center">
                    <div class="mb-6">
                        <i class="fas fa-clipboard-list text-8xl text-gray-300"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Belum Ada Tugas</h3>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        Belum ada tugas untuk mata pelajaran {{ $mapel->nama_mapel }}. 
                        Mulai dengan menambahkan tugas dan latihan pertama.
                    </p>
                    <a href="{{ route('tugas.create') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold rounded-xl transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-pen-fancy text-xl mr-3"></i>
                        <span>Buat Tugas Pertama</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection