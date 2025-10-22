@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="mb-4">
                <i class="fas fa-pen-fancy text-6xl" style="color: #570C49;"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                ✍️ Buat Tugas Baru
            </h1>
            <p class="text-gray-700 text-lg max-w-md mx-auto font-medium">
                Buat soal dengan analisis otomatis tingkat kesulitan
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
                                <i class="fas fa-edit text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Form Buat Tugas</h2>
                                <p class="text-purple-200 text-sm mt-1">Lengkapi informasi tugas yang akan dibuat</p>
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
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-6 mb-8 rounded-xl shadow-lg">
                            <div class="flex">
                                <div class="flex-shrink-0 bg-red-500 rounded-full p-2">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-red-800 mb-3">⚠️ Terdapat Kesalahan</h3>
                                    <ul class="list-disc list-inside text-red-700 space-y-2">
                                        @foreach($errors->all() as $error)
                                            <li class="text-sm">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                
                    <form action="{{ route('tugas.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                        <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
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
                                       value="{{ old('judul') }}"
                                       class="w-full px-5 py-4 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300 text-base @error('judul') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror"
                                       placeholder="Contoh: Soal Aljabar Dasar"
                                       required>
                            </div>
                        </div>

                        <!-- Soal/Pertanyaan -->
                        <div>
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
                                      placeholder="Masukkan soal atau pertanyaan tugas disini..." 
                                      required>{{ old('pertanyaan') }}</textarea>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-start">
                                <div class="bg-blue-500 rounded-xl p-3 mr-4">
                                    <i class="fas fa-robot text-white text-3xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-blue-900 mb-3 flex items-center">
                                        <i class="fas fa-sparkles mr-2 text-yellow-500"></i>
                                        Analisis Otomatis AI
                                    </h3>
                                    <p class="text-blue-800 mb-4 font-medium">Setelah tugas dibuat, sistem akan secara otomatis:</p>
                                    <ul class="space-y-3 text-blue-700">
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle mr-3 text-green-500 mt-1 flex-shrink-0"></i>
                                            <span>Menganalisis tingkat kesulitan soal berdasarkan materi yang telah diajarkan</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle mr-3 text-green-500 mt-1 flex-shrink-0"></i>
                                            <div>
                                                <span>Menentukan kategori kesulitan:</span>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-green-500 text-white rounded-full">
                                                        <i class="fas fa-smile mr-1"></i> Mudah ≥70%
                                                    </span>
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-yellow-500 text-white rounded-full">
                                                        <i class="fas fa-meh mr-1"></i> Normal 40-70%
                                                    </span>
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-red-500 text-white rounded-full">
                                                        <i class="fas fa-frown mr-1"></i> Susah <40%
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle mr-3 text-green-500 mt-1 flex-shrink-0"></i>
                                            <span>Mencari materi yang paling relevan dengan soal yang dibuat</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="pt-6 border-t-2 border-gray-100">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ url()->previous() }}" 
                                   class="flex-1 py-4 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition duration-300 text-center border-2 border-gray-300 hover:border-gray-400 shadow-md hover:shadow-lg">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                                <button type="submit" 
                                        class="flex-1 py-4 px-6 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300">
                                    <span class="flex items-center justify-center">
                                        <i class="fas fa-paper-plane mr-2 text-xl"></i>
                                        Buat Tugas
                                    </span>
                                </button>
                            </div>
                            
                            <!-- Help Text -->
                            <div class="mt-6 text-center bg-green-50 rounded-xl p-4 border-2 border-green-200">
                                <p class="text-sm text-green-700 font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pastikan pertanyaan sudah jelas agar analisis AI lebih akurat
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
    // Auto-resize textarea
    document.getElementById('pertanyaan').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endsection