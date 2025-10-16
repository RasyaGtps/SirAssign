@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #FFE693;">
    <div class="container mx-auto px-6 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold mb-4" style="color: #570C49;">
                <i class="fas fa-plus-circle mr-3"></i>Buat Tugas Baru
            </h1>
            <p class="text-gray-700 text-lg mb-6">Buat soal dengan analisis otomatis tingkat kesulitan</p>
            
            <!-- Breadcrumb -->
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('tugas.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-purple-600 md:ml-2">Tugas</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Buat Baru</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Card Header -->
                <div class="px-8 py-6 border-b border-gray-200" style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>
                        Form Buat Tugas
                    </h2>
                </div>

                <!-- Card Body -->
                <div class="p-8">
                    <form action="{{ route('tugas.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mata Pelajaran -->
                            <div>
                                <label for="mapel_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mata Pelajaran <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 {{ $errors->has('mapel_id') ? 'border-red-500' : 'border-gray-300' }}" 
                                        id="mapel_id" name="mapel_id" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mapel_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <!-- Judul Tugas -->
                            <div>
                                <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Judul Tugas <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 {{ $errors->has('judul') ? 'border-red-500' : 'border-gray-300' }}" 
                                       id="judul" name="judul" value="{{ old('judul') }}" 
                                       placeholder="Masukkan judul tugas" required>
                                @error('judul')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Soal/Pertanyaan -->
                        <div>
                            <label for="pertanyaan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Soal/Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 {{ $errors->has('pertanyaan') ? 'border-red-500' : 'border-gray-300' }}" 
                                      id="pertanyaan" name="pertanyaan" rows="8" 
                                      placeholder="Masukkan soal atau pertanyaan tugas disini..." required>{{ old('pertanyaan') }}</textarea>
                            @error('pertanyaan')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-600 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Sistem akan otomatis menganalisis tingkat kesulitan berdasarkan materi yang telah diajarkan.
                            </p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                            <div class="flex items-start">
                                <div class="text-3xl text-blue-500 mr-4">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Analisis Otomatis</h3>
                                    <p class="text-blue-800 mb-3">Setelah tugas dibuat, sistem akan secara otomatis:</p>
                                    <ul class="space-y-2 text-blue-700">
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                            Menganalisis tingkat kesulitan soal berdasarkan materi yang telah diajarkan
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                            Menentukan kategori: 
                                            <span class="inline-flex items-center px-2 py-1 mx-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Mudah</span>
                                            <span class="inline-flex items-center px-2 py-1 mx-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Normal</span>
                                            <span class="inline-flex items-center px-2 py-1 mx-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Susah</span>
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                            Mencari materi yang paling relevan dengan soal yang dibuat
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('tugas.index') }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transform transition duration-300"
                                    style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Tugas
                            </button>
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
    // Auto-resize textarea
    document.getElementById('pertanyaan').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endsection