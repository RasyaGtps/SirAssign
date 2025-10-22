@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header Section -->
            <div class="text-center mb-10">
                <div class="mb-4">
                    <i class="fas fa-clipboard-list text-6xl" style="color: #570C49;"></i>
                </div>
                <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                    📝 Manajemen Tugas
                </h1>
                <p class="text-gray-700 text-lg max-w-2xl mx-auto font-medium">
                    Kelola semua tugas dengan analisis tingkat kesulitan otomatis
                </p>
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

            @if(session('error'))
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-5 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-full p-2">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-bold text-red-800">⚠️ Error!</p>
                                <p class="text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats & Action -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Stats Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Tugas</p>
                            <p class="text-4xl font-bold">{{ $tugas->count() }}</p>
                            <p class="text-green-200 text-xs mt-1">Assignment dibuat</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-4xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Action -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between h-full">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-2">Tambahkan Tugas Baru</p>
                            <p class="text-white text-base mb-3">Buat assignment dengan AI</p>
                        </div>
                        <a href="{{ route('tugas.create') }}" 
                           class="bg-white text-purple-600 font-bold px-6 py-3 rounded-xl hover:bg-opacity-90 transition duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus mr-2"></i>
                            Buat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tugas Grid -->
            @if($tugas->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($tugas as $item)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1 border-2 border-gray-100 hover:border-green-300">
                            <!-- Card Header -->
                            <div class="p-6 bg-gradient-to-br from-green-50 to-white border-b-2 border-gray-100">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $item->judul }}</h3>
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($item->mapel)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700">
                                                    <i class="fas fa-book mr-1"></i>
                                                    {{ $item->mapel->nama_mapel }}
                                                </span>
                                            @endif
                                            @if($item->similarity_score)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">
                                                    <i class="fas fa-percentage mr-1"></i>
                                                    {{ round($item->similarity_score * 100, 1) }}%
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Tingkat Kesulitan Badge -->
                                    <div class="ml-4">
                                        @if($item->tingkat_kesulitan)
                                            @if($item->tingkat_kesulitan == 'mudah')
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-500 text-white shadow-md">
                                                    <i class="fas fa-smile mr-1"></i>
                                                    Mudah
                                                </span>
                                            @elseif($item->tingkat_kesulitan == 'normal')
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-500 text-white shadow-md">
                                                    <i class="fas fa-meh mr-1"></i>
                                                    Normal
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-500 text-white shadow-md">
                                                    <i class="fas fa-frown mr-1"></i>
                                                    Susah
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gray-400 text-white shadow-md">
                                                <i class="fas fa-question mr-1"></i>
                                                N/A
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body - Soal/Pertanyaan -->
                            <div class="p-6">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-question-circle mr-2 text-green-600"></i>
                                        Soal/Pertanyaan:
                                    </h4>
                                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border-2 border-green-200">
                                        <p class="text-gray-800 text-sm leading-relaxed">
                                            {{ Str::limit($item->pertanyaan, 200) }}
                                        </p>
                                        @if(strlen($item->pertanyaan) > 200)
                                            <button onclick="toggleFullText({{ $item->id }})" 
                                                    class="text-green-600 hover:text-green-800 text-sm font-bold mt-3">
                                                <i class="fas fa-angle-down mr-1"></i>
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
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 py-4 bg-white border-t-2 border-gray-100 flex justify-between items-center gap-2">
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
                        Mulai buat tugas dan soal pertama Anda untuk melihatnya di sini. 
                        Sistem akan secara otomatis menganalisis tingkat kesulitan berdasarkan materi yang telah diajarkan.
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

@section('scripts')
<script>
function toggleFullText(id) {
    const fullTextDiv = document.getElementById('fullText' + id);
    const button = event.target;
    
    if (fullTextDiv.classList.contains('hidden')) {
        fullTextDiv.classList.remove('hidden');
        button.innerHTML = '<i class="fas fa-angle-up mr-1"></i>Lihat Lebih Sedikit';
    } else {
        fullTextDiv.classList.add('hidden');
        button.innerHTML = '<i class="fas fa-angle-down mr-1"></i>Lihat Selengkapnya...';
    }
}
</script>
@endsection