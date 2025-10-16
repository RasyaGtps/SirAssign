@extends('layouts.app')

@section('content')
<div class="min-h-screen p-6" style="background-color: #FFE693;">
    <!-- Header Section -->
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-1 md:space-x-3">
                <li class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 transition-colors duration-200" style="color: #570C49;" onmouseover="this.style.color='#570C49'" onmouseout="this.style.color='#4B5563'">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('tugas.index') }}" class="ml-1 text-gray-600 transition-colors duration-200 md:ml-2" style="color: #570C49;" onmouseover="this.style.color='#570C49'" onmouseout="this.style.color='#4B5563'">Tugas</a>
                </li>
                <li aria-current="page" class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2 font-medium">Edit Tugas</span>
                </li>
            </ol>
        </nav>

        <!-- Main Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-lg" style="background-color: #570C49;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Tugas</h1>
                    <p class="text-gray-600">Perbarui informasi tugas dan soal</p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4" style="background-color: #570C49;">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Form Edit Tugas
                </h2>
            </div>

            <form action="{{ route('tugas.update', $tugas) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Input Fields Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Mata Pelajaran -->
                    <div class="space-y-2">
                        <label for="mapel_id" class="block text-sm font-semibold text-gray-700">
                            Mata Pelajaran 
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="mapel_id" name="mapel_id" required
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 transition-all duration-200 bg-white
                                @error('mapel_id') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-gray-300 @enderror"
                                style="--tw-ring-color: #570C49;" onfocus="this.style.borderColor='#570C49';" onblur="this.style.borderColor='#D1D5DB';">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}" {{ (old('mapel_id', $tugas->mapel_id) == $mapel->id) ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('mapel_id')
                            <p class="text-red-500 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Judul Tugas -->
                    <div class="space-y-2">
                        <label for="judul" class="block text-sm font-semibold text-gray-700">
                            Judul Tugas 
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $tugas->judul) }}" 
                               placeholder="Masukkan judul tugas" required
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 transition-all duration-200
                               @error('judul') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-gray-300 @enderror"
                               style="--tw-ring-color: #570C49;" onfocus="this.style.borderColor='#570C49';" onblur="this.style.borderColor='#D1D5DB';">
                        @error('judul')
                            <p class="text-red-500 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Soal/Pertanyaan Section -->
                <div class="space-y-2">
                    <label for="pertanyaan" class="block text-sm font-semibold text-gray-700">
                        Soal/Pertanyaan 
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <textarea id="pertanyaan" name="pertanyaan" rows="8" required
                                  placeholder="Masukkan soal atau pertanyaan tugas disini..."
                                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 transition-all duration-200 resize-none
                                  @error('pertanyaan') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-gray-300 @enderror"
                                  style="--tw-ring-color: #570C49;" onfocus="this.style.borderColor='#570C49';" onblur="this.style.borderColor='#D1D5DB';">{{ old('pertanyaan', $tugas->pertanyaan) }}</textarea>
                        <div class="absolute bottom-3 right-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('pertanyaan')
                        <p class="text-red-500 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <div class="flex items-center text-sm text-gray-500 bg-yellow-50 px-3 py-2 rounded-lg border border-yellow-200">
                            <svg class="w-4 h-4 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Jika soal diubah, sistem akan menganalisis ulang tingkat kesulitan menggunakan AI.
                        </div>
                    @enderror
                </div>

                <!-- Current Analysis Section (if exists) -->
                @if($tugas->tingkat_kesulitan)
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Analisis AI Saat Ini
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Difficulty Level -->
                        <div class="bg-white rounded-lg p-4 border border-yellow-100">
                            <label class="text-sm font-medium text-gray-600 block mb-2">Tingkat Kesulitan</label>
                            <div class="flex items-center">
                                @if($tugas->tingkat_kesulitan == 'mudah')
                                    <div class="bg-green-100 text-green-800 px-3 py-2 rounded-full flex items-center font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Mudah
                                    </div>
                                @elseif($tugas->tingkat_kesulitan == 'normal')
                                    <div class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-full flex items-center font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                        </svg>
                                        Normal
                                    </div>
                                @else
                                    <div class="bg-red-100 text-red-800 px-3 py-2 rounded-full flex items-center font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Susah
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Similarity Score (if exists) -->
                        @if($tugas->similarity_score)
                        <div class="bg-white rounded-lg p-4 border border-yellow-100">
                            <label class="text-sm font-medium text-gray-600 block mb-2">Kemiripan dengan Materi</label>
                            <div class="flex items-center">
                                <div class="px-3 py-2 rounded-full flex items-center font-medium text-white" style="background-color: #570C49;">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ number_format($tugas->similarity_score * 100, 1) }}%
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- AI Re-analysis Info -->
                <div class="border rounded-xl p-6" style="background: linear-gradient(to right, #FDF7F0, #FEF7ED); border-color: #F59E0B;">
                    <div class="flex items-start space-x-3">
                        <div class="p-2 rounded-lg flex-shrink-0" style="background-color: #570C49;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Re-analisis Otomatis dengan AI</h3>
                            <p class="text-gray-600 mb-3">Jika Anda mengubah soal, sistem akan otomatis melakukan analisis ulang menggunakan AI untuk:</p>
                            <ul class="space-y-1 text-gray-600">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Menganalisis dan memperbarui tingkat kesulitan
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Mencari materi yang paling relevan dengan soal
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Menghitung ulang skor kemiripan dengan materi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 pt-6">
                    <a href="{{ route('tugas.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-3 text-white rounded-lg focus:ring-2 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg"
                            style="background-color: #570C49; --tw-ring-color: #570C49;" 
                            onmouseover="this.style.backgroundColor='#4A0B3E'" 
                            onmouseout="this.style.backgroundColor='#570C49'">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-resize textarea with smooth animation
    function autoResizeTextarea(element) {
        element.style.height = 'auto';
        element.style.height = Math.min(element.scrollHeight, 300) + 'px';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('pertanyaan');
        
        // Set initial height
        autoResizeTextarea(textarea);
        
        // Auto-resize on input
        textarea.addEventListener('input', function() {
            autoResizeTextarea(this);
        });
        
        // Add focus and blur effects
        textarea.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-500');
        });
        
        textarea.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-500');
        });
    });
</script>
@endsection