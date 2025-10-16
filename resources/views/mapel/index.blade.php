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
                    Mata Pelajaran Saya
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Daftar mata pelajaran yang Anda ajar dan dokumentasi tugas yang tersedia
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
                                <p class="text-sm font-medium text-gray-500">Total Mata Pelajaran</p>
                                <p class="text-2xl font-bold" style="color: #570C49;">{{ $mapels->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-folder-open text-3xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Materi</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ $mapels->sum(function ($mapel) {return $mapel->materi->count();}) }}
                                </p>
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
                                <p class="text-2xl font-bold text-green-600">
                                    {{ $mapels->sum(function ($mapel) {return $mapel->tugas->count();}) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="max-w-6xl mx-auto mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-center">
                        <h2 class="text-xl font-bold text-gray-800">Daftar Mata Pelajaran Yang Anda Ajar</h2>
                        <p class="text-gray-600">Lihat dan kelola mata pelajaran yang ditugaskan kepada Anda</p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="max-w-6xl mx-auto mb-8">
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r-xl">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
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
            <div class="max-w-6xl mx-auto">
                @forelse($mapels as $mapel)
                    <div
                        class="bg-white rounded-3xl shadow-xl mb-6 overflow-hidden hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">

                        <!-- Card Header -->
                        <div class="px-8 py-6" style="background: linear-gradient(135deg, #570C49 0%, #6B1B47 100%);">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-book text-4xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white">{{ $mapel->nama_mapel }}</h3>
                                        <p class="text-purple-100 text-sm mt-1">Mata Pelajaran</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="px-4 py-2 bg-white bg-opacity-20 text-white font-bold rounded-full text-sm">
                                        {{ $mapel->materi->count() + $mapel->tugas->count() }} Total Item
                                    </span>
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
                    <div class="bg-white rounded-3xl shadow-xl p-12 text-center">
                        <div class="mb-6">
                            <i class="fas fa-graduation-cap text-8xl" style="color: #570C49;"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Belum Ada Mata Pelajaran</h3>
                        <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                            Anda belum ditugaskan untuk mengajar mata pelajaran apapun. Silakan hubungi administrator sekolah.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
