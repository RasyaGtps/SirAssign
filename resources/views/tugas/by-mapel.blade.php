@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-6 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold mb-4" style="color: #570C49;">
                <i class="fas fa-tasks mr-3"></i>Tugas - {{ $mapel->nama_mapel }}
            </h1>
            <p class="text-gray-700 text-lg mb-6">Semua tugas dan latihan untuk mata pelajaran {{ $mapel->nama_mapel }}</p>
            
            <div class="flex justify-center space-x-4">
                <a href="{{ route('mapel.show', $mapel) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transform transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Detail Mapel
                </a>
                <a href="{{ route('tugas.create') }}" 
                   class="inline-flex items-center px-8 py-3 text-white font-bold rounded-xl shadow-lg hover:scale-105 transform transition duration-300"
                   style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Tugas Baru
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-t-4" style="border-color: #570C49;">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Tugas untuk {{ $mapel->nama_mapel }}</h3>
                    <p class="text-3xl font-bold mt-2" style="color: #570C49;">{{ $tugas->count() }}</p>
                </div>
                <div class="text-6xl" style="color: #570C49;">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>

        <!-- Tugas Grid -->
        @if($tugas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tugas as $item)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                        <!-- Card Header -->
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 line-clamp-2 mb-2">{{ $item->judul }}</h3>
                                    <div class="flex items-center space-x-2 mb-3">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full text-white shadow-sm"
                                              style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                                            TUGAS
                                        </span>
                                        @if($item->tingkat_kesulitan)
                                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                                @if($item->tingkat_kesulitan == 'mudah') bg-green-100 text-green-800
                                                @elseif($item->tingkat_kesulitan == 'normal') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($item->tingkat_kesulitan) }}
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500">
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl ml-4" style="color: #570C49;">
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t">
                            <div class="flex justify-between items-center space-x-2">
                                <!-- View Button -->
                                <a href="{{ route('tugas.show', $item->id) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('tugas.edit', $item->id) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 text-white text-sm font-medium rounded-lg transition duration-300"
                                   style="background-color: #570C49;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('tugas.destroy', $item->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="text-8xl mb-6" style="color: #570C49;">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Belum Ada Tugas</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Belum ada tugas untuk mata pelajaran {{ $mapel->nama_mapel }}. 
                    Mulai dengan menambahkan tugas dan latihan pertama.
                </p>
                <a href="{{ route('tugas.create') }}" 
                   class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transform transition duration-300"
                   style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Upload Tugas Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection