@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-3xl shadow-2xl p-8 mb-8 text-white">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-2xl p-4">
                            <i class="fas fa-clipboard-list text-4xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $tugas->judul }}</h1>
                            <p class="text-green-200 flex items-center">
                                <i class="fas fa-book mr-2"></i>
                                {{ $tugas->mapel->nama_mapel }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ url()->previous() }}" 
                       class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold rounded-xl transition duration-300 border-2 border-white border-opacity-30">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Tugas Info Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Tingkat Kesulitan -->
                @if($tugas->tingkat_kesulitan)
                    @if($tugas->tingkat_kesulitan == 'mudah')
                        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium mb-1">Tingkat Kesulitan</p>
                                    <p class="text-3xl font-bold">Mudah</p>
                                    <p class="text-green-200 text-xs mt-1">≥ 70% kemiripan</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                                    <i class="fas fa-smile text-4xl"></i>
                                </div>
                            </div>
                        </div>
                    @elseif($tugas->tingkat_kesulitan == 'normal')
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-medium mb-1">Tingkat Kesulitan</p>
                                    <p class="text-3xl font-bold">Normal</p>
                                    <p class="text-yellow-200 text-xs mt-1">40-70% kemiripan</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                                    <i class="fas fa-meh text-4xl"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-100 text-sm font-medium mb-1">Tingkat Kesulitan</p>
                                    <p class="text-3xl font-bold">Susah</p>
                                    <p class="text-red-200 text-xs mt-1">< 40% kemiripan</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                                    <i class="fas fa-frown text-4xl"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-gradient-to-br from-gray-500 to-gray-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-100 text-sm font-medium mb-1">Tingkat Kesulitan</p>
                                <p class="text-3xl font-bold">Belum Dianalisis</p>
                                <p class="text-gray-200 text-xs mt-1">Menunggu analisis AI</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                                <i class="fas fa-question text-4xl"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Similarity Score -->
                @if($tugas->similarity_score)
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium mb-1">Kemiripan Materi</p>
                                <p class="text-3xl font-bold">{{ round($tugas->similarity_score * 100, 1) }}%</p>
                                <p class="text-blue-200 text-xs mt-1">Dengan materi terkait</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                                <i class="fas fa-percentage text-4xl"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Created Date -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium mb-1">Tanggal Dibuat</p>
                            <p class="text-2xl font-bold">{{ $tugas->created_at->format('d M Y') }}</p>
                            <p class="text-indigo-200 text-xs mt-1">{{ $tugas->created_at->format('H:i') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-calendar text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Soal/Pertanyaan -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                        <i class="fas fa-question-circle text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Soal/Pertanyaan</h2>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 border-2 border-green-200">
                    <p class="text-gray-800 text-lg leading-relaxed whitespace-pre-wrap">{{ $tugas->pertanyaan }}</p>
                </div>
            </div>

            <!-- Ringkasan Analisis AI -->
            @if($tugas->ringkasan)
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                        <i class="fas fa-brain text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Ringkasan Analisis AI</h2>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-6 border-2 border-purple-200">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas fa-lightbulb text-purple-500 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 text-base leading-relaxed">{{ $tugas->ringkasan }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ url()->previous() }}" 
                   class="flex-1 py-4 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition duration-300 text-center border-2 border-gray-300 hover:border-gray-400 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('tugas.edit', $tugas) }}" 
                   class="flex-1 py-4 px-6 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 text-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Tugas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection