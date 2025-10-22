@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="text-center mb-10">
                <div class="mb-4">
                    <i class="fas fa-book-open text-6xl" style="color: #570C49;"></i>
                </div>
                <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                    📚 Mata Pelajaran Saya
                </h1>
                <p class="text-gray-700 text-lg max-w-2xl mx-auto font-medium">
                    Daftar mata pelajaran yang Anda ajar dan dokumentasi pembelajaran
                </p>
            </div>

            <!-- Stats Section -->
            <div class="max-w-7xl mx-auto mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium mb-1">Mata Pelajaran</p>
                                <p class="text-4xl font-bold">{{ $mapels->count() }}</p>
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
                                <p class="text-4xl font-bold">
                                    {{ $mapels->sum(function ($mapel) {return $mapel->materi->count();}) }}
                                </p>
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
                                <p class="text-4xl font-bold">
                                    {{ $mapels->sum(function ($mapel) {return $mapel->tugas->count();}) }}
                                </p>
                                <p class="text-green-200 text-xs mt-1">Assignment dibuat</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-tasks text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Header -->
            <div class="max-w-7xl mx-auto mb-6">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-3 mr-3">
                        <i class="fas fa-list text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Mata Pelajaran</h2>
                        <p class="text-sm text-gray-600">{{ $mapels->count() }} mata pelajaran yang Anda ajar</p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto mb-6">
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

            <!-- Mapel Cards -->
            <div class="max-w-7xl mx-auto">
                @forelse($mapels as $mapel)
                    <div
                        class="bg-white rounded-3xl shadow-xl mb-6 overflow-hidden hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">

                        <!-- Card Header -->
                        <div class="px-8 py-6 bg-gradient-to-r from-purple-600 to-purple-800">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-2xl p-4">
                                        <i class="fas fa-book text-4xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white mb-1">{{ $mapel->nama_mapel }}</h3>
                                        <p class="text-purple-200 text-sm">Mata Pelajaran</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-500 bg-opacity-90 px-4 py-2 rounded-xl flex items-center space-x-2">
                                        <i class="fas fa-file-pdf text-white text-xl"></i>
                                        <span class="text-white font-bold text-lg">{{ $mapel->materi->count() }}</span>
                                        <span class="text-blue-100 text-sm">Materi</span>
                                    </div>
                                    <div class="bg-green-500 bg-opacity-90 px-4 py-2 rounded-xl flex items-center space-x-2">
                                        <i class="fas fa-clipboard-list text-white text-xl"></i>
                                        <span class="text-white font-bold text-lg">{{ $mapel->tugas->count() }}</span>
                                        <span class="text-green-100 text-sm">Tugas</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="px-8 py-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                                <!-- Description -->
                                <div class="lg:col-span-2">
                                    <h4 class="text-lg font-bold text-gray-800 mb-3"><i
                                            class="fas fa-align-left mr-2"></i>Deskripsi</h4>
                                    <p class="text-gray-600 text-base leading-relaxed">
                                        {{ $mapel->deskripsi ?? 'Belum ada deskripsi untuk mata pelajaran ini.' }}
                                    </p>

                                    <div class="mt-4">
                                        <h5 class="text-sm font-bold text-gray-700 mb-2"><i
                                                class="fas fa-chart-bar mr-2"></i>Statistik:</h5>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                                <i class="fas fa-file-alt mr-1"></i>{{ $mapel->materi->count() }} Materi
                                            </span>
                                            <span
                                                class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                                                <i class="fas fa-tasks mr-1"></i>{{ $mapel->tugas->count() }} Tugas
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="space-y-3">
                                    <h4 class="text-lg font-bold text-gray-800 mb-3"><i class="fas fa-bolt mr-2"></i>Aksi
                                    </h4>

                                    <a href="{{ route('mapel.show', $mapel) }}"
                                        class="w-full block px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-xl transition duration-300 text-center border border-blue-200">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Detail Mapel
                                        </span>
                                    </a>

                                    <a href="{{ route('materi.by-mapel', $mapel) }}"
                                        class="w-full block px-4 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded-xl transition duration-300 text-center border border-indigo-200">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                                </path>
                                            </svg>
                                            Lihat Materi ({{ $mapel->materi->count() }})
                                        </span>
                                    </a>

                                    <a href="{{ route('tugas.by-mapel', $mapel) }}"
                                        class="w-full block px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-xl transition duration-300 text-center border border-green-200">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                </path>
                                            </svg>
                                            Lihat Tugas ({{ $mapel->tugas->count() }})
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="bg-white rounded-3xl shadow-xl p-16 text-center">
                        <div class="mb-6">
                            <i class="fas fa-book-open text-8xl text-gray-300"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">Belum Ada Mata Pelajaran</h3>
                        <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">
                            Anda belum ditugaskan untuk mengajar mata pelajaran apapun. Silakan hubungi administrator sekolah.
                        </p>
                        <div class="inline-block px-6 py-3 bg-purple-100 text-purple-700 rounded-lg">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="font-medium">Hubungi Admin untuk Penugasan</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
