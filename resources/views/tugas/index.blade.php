@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #FFE693;">
    <div class="container mx-auto px-6 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold mb-4" style="color: #570C49;">
                <i class="fas fa-tasks mr-3"></i>Manajemen Tugas
            </h1>
            <p class="text-gray-700 text-lg mb-6">Kelola semua tugas dengan analisis tingkat kesulitan otomatis</p>
            
            <div class="flex justify-center">
                <a href="{{ route('tugas.create') }}" 
                   class="inline-flex items-center px-8 py-4 text-white font-bold text-lg rounded-xl shadow-lg hover:scale-105 transform transition duration-300"
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

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-t-4" style="border-color: #570C49;">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Tugas Tersimpan</h3>
                    <p class="text-3xl font-bold mt-2" style="color: #570C49;">{{ $tugas->count() }}</p>
                </div>
                <div class="text-6xl" style="color: #570C49;">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>

        <!-- Tugas Grid -->
        @if($tugas->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($tugas as $item)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                        <!-- Card Header -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->judul }}</h3>
                                    <div class="flex items-center gap-2 mb-3">
                                        @if($item->mapel)
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-book mr-1"></i>
                                                {{ $item->mapel->nama_mapel }}
                                            </span>
                                        @endif
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Tingkat Kesulitan Badge -->
                                <div class="ml-4">
                                    @if($item->tingkat_kesulitan)
                                        @if($item->tingkat_kesulitan == 'mudah')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-smile mr-1"></i>
                                                Mudah
                                            </span>
                                        @elseif($item->tingkat_kesulitan == 'normal')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-meh mr-1"></i>
                                                Normal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-frown mr-1"></i>
                                                Susah
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-question mr-1"></i>
                                            Belum Dianalisis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Body - Soal/Pertanyaan -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Soal/Pertanyaan:</h4>
                                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                                    <p class="text-gray-800 text-sm leading-relaxed">
                                        {{ Str::limit($item->pertanyaan, 200) }}
                                    </p>
                                    @if(strlen($item->pertanyaan) > 200)
                                        <button onclick="toggleFullText({{ $item->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2">
                                            Lihat Selengkapnya...
                                        </button>
                                        <div id="fullText{{ $item->id }}" class="hidden mt-2">
                                            <p class="text-gray-800 text-sm leading-relaxed">
                                                {{ $item->pertanyaan }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Similarity Score & Matched Materials -->
                            @if($item->similarity_score)
                                <div class="border-t border-gray-100 pt-4">
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center">
                                            <span class="text-gray-600">Kemiripan dengan Materi:</span>
                                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                {{ number_format($item->similarity_score * 100, 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center">
                            <!-- Edit Button -->
                            <a href="{{ route('tugas.edit', $item->id) }}" 
                               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition duration-300 hover:scale-105"
                               style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('tugas.destroy', $item->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition duration-300">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus
                                </button>
                            </form>
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
                    Mulai buat tugas dan soal pertama Anda untuk melihatnya di sini. 
                    Sistem akan secara otomatis menganalisis tingkat kesulitan berdasarkan materi yang telah diajarkan.
                </p>
                <a href="{{ route('tugas.create') }}" 
                   class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transform transition duration-300"
                   style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Tugas Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleFullText(id) {
    const fullTextDiv = document.getElementById('fullText' + id);
    const button = event.target;
    
    if (fullTextDiv.classList.contains('hidden')) {
        fullTextDiv.classList.remove('hidden');
        button.textContent = 'Lihat Lebih Sedikit';
    } else {
        fullTextDiv.classList.add('hidden');
        button.textContent = 'Lihat Selengkapnya...';
    }
}
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush