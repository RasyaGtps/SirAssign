@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="mb-4">
                <i class="fas fa-edit text-6xl" style="color: #570C49;"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                ✏️ Edit Tugas
            </h1>
            <p class="text-gray-700 text-lg max-w-md mx-auto font-medium">
                Perbarui informasi tugas dan soal
            </p>
        </div>

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Card Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-purple-600 to-purple-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <i class="fas fa-pen-to-square text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Form Edit Tugas</h2>
                                <p class="text-purple-200 text-sm mt-1">Perbarui informasi tugas</p>
                            </div>
                        </div>
                        <a href="{{ url()->previous() }}" 
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold rounded-lg transition duration-300 border-2 border-white border-opacity-30">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="px-8 py-8">
                
                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 mb-8 rounded-xl shadow-md">
                            <div class="flex">
                                <div class="flex-shrink-0 bg-red-500 rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base font-bold text-red-800 mb-3">⚠️ Terdapat Kesalahan</h3>
                                    <ul class="list-disc list-inside text-red-700 space-y-2">
                                        @foreach($errors->all() as $error)
                                            <li class="text-sm">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Current Analysis (if exists) -->
                    @if($tugas->tingkat_kesulitan)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 mb-8">
                            <div class="flex items-start">
                                <div class="bg-blue-500 rounded-xl p-3 mr-4">
                                    <i class="fas fa-chart-line text-white text-3xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-blue-900 mb-3">📊 Analisis AI Saat Ini</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Difficulty Level -->
                                        <div class="bg-white rounded-xl p-4 border-2 border-blue-100">
                                            <label class="text-sm font-medium text-blue-600 block mb-2">Tingkat Kesulitan</label>
                                            @if($tugas->tingkat_kesulitan == 'mudah')
                                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-500 text-white font-bold">
                                                    <i class="fas fa-smile mr-2"></i>
                                                    Mudah
                                                </span>
                                            @elseif($tugas->tingkat_kesulitan == 'normal')
                                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-500 text-white font-bold">
                                                    <i class="fas fa-meh mr-2"></i>
                                                    Normal
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-500 text-white font-bold">
                                                    <i class="fas fa-frown mr-2"></i>
                                                    Susah
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Similarity Score (if exists) -->
                                        @if($tugas->similarity_score)
                                            <div class="bg-white rounded-xl p-4 border-2 border-blue-100">
                                                <label class="text-sm font-medium text-blue-600 block mb-2">Kemiripan dengan Materi</label>
                                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-blue-500 text-white font-bold">
                                                    <i class="fas fa-percentage mr-2"></i>
                                                    {{ round($tugas->similarity_score * 100, 1) }}%
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('tugas.update', $tugas) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <!-- Input Mode Selection -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl p-6">
                            <label class="block text-base font-bold text-gray-800 mb-4">
                                <span class="flex items-center">
                                    <i class="fas fa-toggle-on text-xl mr-2" style="color: #570C49;"></i>
                                    Pilih Mode Input
                                    <span class="text-red-500 ml-1">*</span>
                                </span>
                            </label>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="input_mode" value="text" class="peer hidden" {{ old('input_mode', $tugas->input_mode) == 'text' ? 'checked' : '' }}>
                                    <div class="border-3 border-gray-300 rounded-xl p-5 transition-all peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:shadow-lg hover:shadow-md">
                                        <div class="flex items-center justify-center mb-3">
                                            <i class="fas fa-keyboard text-4xl text-purple-600"></i>
                                        </div>
                                        <h3 class="font-bold text-center text-lg text-gray-800">Ketik Manual</h3>
                                        <p class="text-sm text-gray-600 text-center mt-2">Tulis soal langsung di form</p>
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="input_mode" value="file" class="peer hidden" {{ old('input_mode', $tugas->input_mode) == 'file' ? 'checked' : '' }}>
                                    <div class="border-3 border-gray-300 rounded-xl p-5 transition-all peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:shadow-lg hover:shadow-md">
                                        <div class="flex items-center justify-center mb-3">
                                            <i class="fas fa-file-upload text-4xl text-purple-600"></i>
                                        </div>
                                        <h3 class="font-bold text-center text-lg text-gray-800">Upload File</h3>
                                        <p class="text-sm text-gray-600 text-center mt-2">Upload PDF atau Word</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Input Fields Row -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Mata Pelajaran -->
                            <div>
                                <label for="mapel_id" class="block text-base font-bold text-gray-800 mb-3">
                                    <span class="flex items-center">
                                        <i class="fas fa-book text-xl mr-2" style="color: #570C49;"></i>
                                        Mata Pelajaran
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <select id="mapel_id" 
                                        name="mapel_id" 
                                        class="w-full px-5 py-4 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300 text-base @error('mapel_id') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror"
                                        required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}" {{ old('mapel_id', $tugas->mapel_id) == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Judul Tugas -->
                            <div>
                                <label for="judul" class="block text-base font-bold text-gray-800 mb-3">
                                    <span class="flex items-center">
                                        <i class="fas fa-heading text-xl mr-2" style="color: #570C49;"></i>
                                        Judul Tugas
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <input type="text" 
                                       id="judul" 
                                       name="judul" 
                                       value="{{ old('judul', $tugas->judul) }}"
                                       class="w-full px-5 py-4 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300 text-base @error('judul') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror"
                                       placeholder="Contoh: Soal Aljabar Dasar"
                                       required>
                            </div>
                        </div>

                        <!-- Soal/Pertanyaan - Mode Text -->
                        <div id="text-input-section">
                            <label for="pertanyaan" class="block text-base font-bold text-gray-800 mb-3">
                                <span class="flex items-center">
                                    <i class="fas fa-question-circle text-xl mr-2" style="color: #570C49;"></i>
                                    Soal/Pertanyaan
                                    <span class="text-red-500 ml-1">*</span>
                                </span>
                            </label>
                            <textarea id="pertanyaan" 
                                      name="pertanyaan" 
                                      rows="8"
                                      class="w-full px-5 py-4 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300 text-base @error('pertanyaan') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror" 
                                      placeholder="Masukkan soal atau pertanyaan tugas disini...">{{ old('pertanyaan', $tugas->pertanyaan) }}</textarea>
                            
                            <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <p class="text-sm text-yellow-800 font-medium flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Jika soal diubah, sistem akan menganalisis ulang tingkat kesulitan menggunakan AI.
                                </p>
                            </div>
                        </div>

                        <!-- File Upload - Mode File -->
                        <div id="file-upload-section" style="display: none;">
                            <!-- Current File Info (if exists) -->
                            @if($tugas->file_path)
                                <div class="mb-4 p-4 bg-blue-50 border-2 border-blue-200 rounded-xl">
                                    <p class="text-sm font-bold text-blue-800 mb-2">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        File Saat Ini:
                                    </p>
                                    <p class="text-sm text-blue-700">
                                        {{ basename($tugas->file_path) }} 
                                        <span class="text-xs">({{ strtoupper($tugas->file_type ?? 'unknown') }})</span>
                                    </p>
                                </div>
                            @endif

                            <label for="file" class="block text-base font-bold text-gray-800 mb-3">
                                <span class="flex items-center">
                                    <i class="fas fa-file-upload text-xl mr-2" style="color: #570C49;"></i>
                                    {{ $tugas->file_path ? 'Upload File Baru (Opsional)' : 'Upload File Soal' }}
                                    @if(!$tugas->file_path)
                                        <span class="text-red-500 ml-1">*</span>
                                    @endif
                                </span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-gray-50 hover:bg-gray-100 transition @error('file') border-red-400 bg-red-50 @enderror">
                                <div class="mb-4">
                                    <i class="fas fa-cloud-upload-alt text-6xl text-purple-600"></i>
                                </div>
                                <input type="file" 
                                       id="file" 
                                       name="file" 
                                       accept=".pdf,.doc,.docx,.txt"
                                       class="hidden">
                                <label for="file" class="cursor-pointer">
                                    <span class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition">
                                        <i class="fas fa-folder-open mr-2"></i>
                                        Pilih File Baru
                                    </span>
                                </label>
                                <p class="text-sm text-gray-600 mt-4">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: PDF, DOC, DOCX, TXT (Maks. 10MB)
                                </p>
                                <p id="file-name" class="text-sm font-bold text-purple-600 mt-2"></p>
                            </div>
                            
                            <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <p class="text-sm text-yellow-800 font-medium flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    {{ $tugas->file_path ? 'Upload file baru akan mengganti file lama dan menganalisis ulang.' : 'File akan diekstrak dan dianalisis tingkat kesulitannya.' }}
                                </p>
                            </div>
                        </div>

                        <!-- AI Re-analysis Info -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-start">
                                <div class="bg-green-500 rounded-xl p-3 mr-4">
                                    <i class="fas fa-robot text-white text-3xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-green-900 mb-3 flex items-center">
                                        <i class="fas fa-sparkles mr-2 text-yellow-500"></i>
                                        Re-analisis Otomatis AI
                                    </h3>
                                    <p class="text-green-800 mb-4 font-medium">Sistem akan otomatis melakukan analisis ulang untuk:</p>
                                    <ul class="space-y-3 text-green-700">
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle mr-3 text-green-500 mt-1 flex-shrink-0"></i>
                                            <span>Menganalisis dan memperbarui tingkat kesulitan</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle mr-3 text-green-500 mt-1 flex-shrink-0"></i>
                                            <span>Mencari materi yang paling relevan dengan soal</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle mr-3 text-green-500 mt-1 flex-shrink-0"></i>
                                            <span>Menghitung ulang skor kemiripan dengan materi</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="pt-6 border-t-2 border-gray-100">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ url()->previous() }}" 
                                   class="flex-1 py-4 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition duration-300 text-center border-2 border-gray-300 hover:border-gray-400 shadow-md hover:shadow-lg">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                                <button type="submit" 
                                        class="flex-1 py-4 px-6 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300">
                                    <span class="flex items-center justify-center">
                                        <i class="fas fa-save mr-2 text-xl"></i>
                                        Update Tugas
                                    </span>
                                </button>
                            </div>
                            
                            <!-- Help Text -->
                            <div class="mt-6 text-center bg-green-50 rounded-xl p-4 border-2 border-green-200">
                                <p class="text-sm text-green-700 font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Perubahan akan otomatis dianalisis oleh AI
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textMode = document.querySelector('input[name="input_mode"][value="text"]');
        const fileMode = document.querySelector('input[name="input_mode"][value="file"]');
        const textSection = document.getElementById('text-input-section');
        const fileSection = document.getElementById('file-upload-section');
        const pertanyaanInput = document.getElementById('pertanyaan');
        const fileInput = document.getElementById('file');
        const fileNameDisplay = document.getElementById('file-name');

        // Toggle sections based on mode
        function toggleSections() {
            if (textMode.checked) {
                textSection.style.display = 'block';
                fileSection.style.display = 'none';
                pertanyaanInput.required = true;
                fileInput.required = false;
            } else if (fileMode.checked) {
                textSection.style.display = 'none';
                fileSection.style.display = 'block';
                pertanyaanInput.required = false;
                // File not required on edit if already has file
                fileInput.required = {{ $tugas->file_path ? 'false' : 'true' }};
            }
        }

        // Listen to mode changes - gunakan click dan change
        textMode.addEventListener('click', toggleSections);
        fileMode.addEventListener('click', toggleSections);
        textMode.addEventListener('change', toggleSections);
        fileMode.addEventListener('change', toggleSections);

        // Auto-resize textarea
        function autoResizeTextarea(element) {
            element.style.height = 'auto';
            element.style.height = Math.min(element.scrollHeight, 400) + 'px';
        }
        
        // Set initial height
        autoResizeTextarea(pertanyaanInput);
        
        // Auto-resize on input
        pertanyaanInput.addEventListener('input', function() {
            autoResizeTextarea(this);
        });

        // Display file name when selected
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                fileNameDisplay.innerHTML = `<i class="fas fa-file-alt mr-2"></i>${fileName} (${fileSize} MB)`;
            } else {
                fileNameDisplay.textContent = '';
            }
        });

        // Initialize
        toggleSections();
        
        // Debug
        console.log('Edit toggle script loaded');
    });
</script>
@endsection
