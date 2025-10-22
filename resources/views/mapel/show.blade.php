@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-3xl shadow-2xl p-8 mb-8 text-white">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-2xl p-4">
                            <i class="fas fa-book text-4xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $mapel->nama_mapel }}</h1>
                            <p class="text-purple-200">Detail Mata Pelajaran</p>
                        </div>
                    </div>
                    <a href="{{ route('mapel.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold rounded-xl transition duration-300 border-2 border-white border-opacity-30">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Info Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Materi -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Materi</p>
                            <p class="text-4xl font-bold">{{ $mapel->materi->count() }}</p>
                            <p class="text-blue-200 text-xs mt-1">Dokumen pembelajaran</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-file-pdf text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Tugas -->
                <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Tugas</p>
                            <p class="text-4xl font-bold">{{ $mapel->tugas->count() }}</p>
                            <p class="text-green-200 text-xs mt-1">Assignment dibuat</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Dokumen -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Total Dokumen</p>
                            <p class="text-4xl font-bold">{{ $mapel->materi->count() + $mapel->tugas->count() }}</p>
                            <p class="text-purple-200 text-xs mt-1">Semua konten</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-folder-open text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center mb-4">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                        <i class="fas fa-align-left text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Deskripsi Mata Pelajaran</h2>
                </div>
                <p class="text-gray-700 text-lg leading-relaxed mb-6">
                    {{ $mapel->deskripsi ?? 'Belum ada deskripsi untuk mata pelajaran ini.' }}
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border-2 border-blue-200">
                        <p class="text-sm font-medium text-blue-600 mb-1"><i class="fas fa-calendar-plus mr-2"></i>Dibuat pada</p>
                        <p class="text-gray-900 font-bold text-lg">{{ $mapel->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border-2 border-purple-200">
                        <p class="text-sm font-medium text-purple-600 mb-1"><i class="fas fa-sync mr-2"></i>Terakhir diupdate</p>
                        <p class="text-gray-900 font-bold text-lg">{{ $mapel->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                        <i class="fas fa-rocket text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Aksi Cepat</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="{{ route('materi.by-mapel', $mapel) }}" 
                       class="group flex items-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-700 font-bold rounded-2xl transition duration-300 border-2 border-blue-300 hover:border-blue-500 transform hover:-translate-y-1 hover:shadow-xl">
                        <div class="bg-blue-500 rounded-full w-16 h-16 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-file-pdf text-white text-2xl"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xl font-bold mb-1">Lihat Semua Materi</p>
                            <p class="text-sm text-blue-600">{{ $mapel->materi->count() }} dokumen tersedia</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('tugas.by-mapel', $mapel) }}" 
                       class="group flex items-center p-6 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-700 font-bold rounded-2xl transition duration-300 border-2 border-green-300 hover:border-green-500 transform hover:-translate-y-1 hover:shadow-xl">
                        <div class="bg-green-500 rounded-full w-16 h-16 flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-clipboard-list text-white text-2xl"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xl font-bold mb-1">Lihat Semua Tugas</p>
                            <p class="text-sm text-green-600">{{ $mapel->tugas->count() }} assignment tersedia</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Preview Materi & Tugas Terbaru -->
            @if($mapel->materi->count() > 0 || $mapel->tugas->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                            <i class="fas fa-clock-rotate-left text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Dokumen Terbaru</h2>
                    </div>
                    
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
                <div class="bg-white rounded-3xl shadow-xl p-16 text-center">
                    <div class="mb-6">
                        <i class="fas fa-folder-open text-8xl text-gray-300"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Belum Ada Dokumen</h3>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        Belum ada materi atau tugas untuk mata pelajaran ini. Mulai tambahkan konten pembelajaran.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('materi.create') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-cloud-upload-alt text-xl mr-3"></i>
                            <span>Tambah Materi</span>
                        </a>
                        <a href="{{ route('tugas.create') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold rounded-xl transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-pen-fancy text-xl mr-3"></i>
                            <span>Buat Tugas</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection