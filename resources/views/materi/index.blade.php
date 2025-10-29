@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header Section -->
            <div class="text-center mb-10">
                <div class="mb-4">
                    <i class="fas fa-file-pdf text-6xl" style="color: #570C49;"></i>
                </div>
                <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                    📚 Manajemen Materi
                </h1>
                <p class="text-gray-700 text-lg max-w-2xl mx-auto font-medium">
                    Kelola semua materi pembelajaran dengan mudah dan aman
                </p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 max-w-4xl mx-auto">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-full w-10 h-10 flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-base font-bold text-green-800">✅ Berhasil!</p>
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 max-w-4xl mx-auto">
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-full w-10 h-10 flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-base font-bold text-red-800">⚠️ Error!</p>
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats & Action -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Stats Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Materi</p>
                            <p class="text-4xl font-bold">{{ $materi->count() }}</p>
                            <p class="text-blue-200 text-xs mt-1">Dokumen pembelajaran</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-file-pdf text-4xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Action -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between h-full">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-2">Tambahkan Materi Baru</p>
                            <p class="text-white text-base mb-3">Upload dokumen pembelajaran</p>
                        </div>
                        <a href="{{ route('materi.create') }}" 
                           class="bg-white text-purple-600 font-bold px-6 py-3 rounded-xl hover:bg-opacity-90 transition duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus mr-2"></i>
                            Upload
                        </a>
                    </div>
                </div>
            </div>

            <!-- Materi Grid -->
            @if($materi->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($materi as $item)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1 border-2 border-gray-100 hover:border-blue-300">
                            <!-- Card Header -->
                            <div class="p-6 pb-4 bg-gradient-to-br from-blue-50 to-white">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-800 line-clamp-2 mb-3">{{ $item->title }}</h3>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm">
                                                {{ strtoupper($item->file_type) }}
                                            </span>
                                            @if($item->mapel)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700">
                                                    {{ $item->mapel->nama_mapel }}
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="bg-blue-100 rounded-xl p-3">
                                            @switch($item->file_type)
                                                @case('pdf')
                                                    <i class="fas fa-file-pdf text-3xl text-red-500"></i>
                                                    @break
                                                @case('docx')
                                                    <i class="fas fa-file-word text-3xl text-blue-600"></i>
                                                    @break
                                                @case('txt')
                                                    <i class="fas fa-file-alt text-3xl text-gray-600"></i>
                                                    @break
                                                @case('xlsx')
                                                    <i class="fas fa-file-excel text-3xl text-green-600"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-file text-3xl text-gray-500"></i>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 py-4 bg-white border-t-2 border-gray-100">
                                <div class="flex justify-between items-center gap-2">
                                    <!-- Download Button -->
                                    <a href="{{ route('materi.download', $item->id) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-bold rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                        <i class="fas fa-download mr-2"></i>
                                        Download
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('materi.edit', $item->id) }}" 
                                       class="inline-flex items-center justify-center px-3 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('materi.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
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
                        <i class="fas fa-file-pdf text-8xl text-gray-300"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Belum Ada Materi</h3>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        Mulai upload materi pembelajaran pertama Anda untuk melihatnya di sini. 
                        Semua jenis file seperti PDF, DOCX, TXT, dan XLSX didukung.
                    </p>
                    <a href="{{ route('materi.create') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-cloud-upload-alt text-xl mr-3"></i>
                        <span>Upload Materi Pertama</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection