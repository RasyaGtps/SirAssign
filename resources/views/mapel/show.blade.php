@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2" style="color: #570C49;">
                            <i class="fas fa-book mr-3"></i>{{ $mapel->nama_mapel }}
                        </h1>
                        <p class="text-gray-600">Detail Mata Pelajaran</p>
                    </div>
                    <a href="{{ route('mapel.index') }}" 
                       class="inline-flex items-center px-6 py-3 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-300"
                       style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Info Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Materi -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-3xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Materi</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $mapel->materi->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Tugas -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tasks text-3xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Tugas</p>
                            <p class="text-2xl font-bold text-green-600">{{ $mapel->tugas->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Dokumen -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4" style="border-left-color: #570C49;">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-folder text-3xl" style="color: #570C49;"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Dokumen</p>
                            <p class="text-2xl font-bold" style="color: #570C49;">{{ $mapel->materi->count() + $mapel->tugas->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-4" style="color: #570C49;">
                    <i class="fas fa-align-left mr-3"></i>Deskripsi Mata Pelajaran
                </h2>
                <p class="text-gray-700 text-lg leading-relaxed">
                    {{ $mapel->deskripsi ?? 'Belum ada deskripsi untuk mata pelajaran ini.' }}
                </p>
                
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm font-medium text-gray-500">Dibuat pada</p>
                        <p class="text-gray-900 font-semibold">{{ $mapel->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm font-medium text-gray-500">Terakhir diupdate</p>
                        <p class="text-gray-900 font-semibold">{{ $mapel->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6" style="color: #570C49;">
                    <i class="fas fa-bolt mr-3"></i>Aksi Cepat
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('materi.by-mapel', $mapel) }}" 
                       class="flex items-center justify-center p-6 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold rounded-xl transition duration-300 border-2 border-blue-200 hover:border-blue-300">
                        <i class="fas fa-file-alt text-2xl mr-4"></i>
                        <div class="text-left">
                            <p class="text-lg">Lihat Semua Materi</p>
                            <p class="text-sm opacity-75">{{ $mapel->materi->count() }} file tersedia</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('tugas.by-mapel', $mapel) }}" 
                       class="flex items-center justify-center p-6 bg-green-50 hover:bg-green-100 text-green-700 font-bold rounded-xl transition duration-300 border-2 border-green-200 hover:border-green-300">
                        <i class="fas fa-tasks text-2xl mr-4"></i>
                        <div class="text-left">
                            <p class="text-lg">Lihat Semua Tugas</p>
                            <p class="text-sm opacity-75">{{ $mapel->tugas->count() }} file tersedia</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Preview Materi & Tugas Terbaru -->
            @if($mapel->materi->count() > 0 || $mapel->tugas->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6" style="color: #570C49;">
                        <i class="fas fa-file-text mr-3"></i>Dokumen Terbaru
                    </h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @if($mapel->materi->count() > 0)
                            <div>
                                <h3 class="text-lg font-bold text-blue-700 mb-4">
                                    <i class="fas fa-file-alt mr-2"></i>Materi Terbaru
                                </h3>
                                <div class="space-y-3">
                                    @foreach($mapel->materi->take(3) as $materi)
                                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-xl border border-blue-200 hover:bg-blue-100 transition duration-300">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-blue-900">{{ $materi->title }}</h4>
                                                <p class="text-sm text-blue-600">
                                                    <i class="fas fa-file mr-1"></i>{{ strtoupper($materi->file_type) }} • 
                                                    <i class="fas fa-calendar mr-1"></i>{{ $materi->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                            <a href="{{ route('materi.download', $materi) }}" 
                                               class="ml-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-300">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </a>
                                        </div>
                                    @endforeach
                                    @if($mapel->materi->count() > 3)
                                        <div class="text-center py-3">
                                            <p class="text-blue-600">
                                                <i class="fas fa-ellipsis-h mr-2"></i>
                                                Dan {{ $mapel->materi->count() - 3 }} materi lainnya...
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($mapel->tugas->count() > 0)
                            <div>
                                <h3 class="text-lg font-bold text-green-700 mb-4">
                                    <i class="fas fa-tasks mr-2"></i>Tugas Terbaru
                                </h3>
                                <div class="space-y-3">
                                    @foreach($mapel->tugas->take(3) as $tugas)
                                        <div class="flex justify-between items-center p-4 bg-green-50 rounded-xl border border-green-200 hover:bg-green-100 transition duration-300">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-green-900">{{ $tugas->judul }}</h4>
                                                <p class="text-sm text-green-600">
                                                    <i class="fas fa-tasks mr-1"></i>Tugas • 
                                                    <i class="fas fa-calendar mr-1"></i>{{ $tugas->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                            <a href="{{ route('tugas.show', $tugas) }}" 
                                               class="ml-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-300">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                        </div>
                                    @endforeach
                                    @if($mapel->tugas->count() > 3)
                                        <div class="text-center py-3">
                                            <p class="text-green-600">
                                                <i class="fas fa-ellipsis-h mr-2"></i>
                                                Dan {{ $mapel->tugas->count() - 3 }} tugas lainnya...
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-folder-open text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Dokumen</h3>
                    <p class="text-gray-500 mb-6">Belum ada materi atau tugas untuk mata pelajaran ini.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('materi.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Tambah Materi
                        </a>
                        <a href="{{ route('tugas.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Tambah Tugas
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection