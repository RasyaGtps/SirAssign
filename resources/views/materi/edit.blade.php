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
                ✏️ Edit Materi
            </h1>
            <p class="text-gray-700 text-lg max-w-md mx-auto font-medium">
                Perbarui informasi materi pembelajaran
            </p>
        </div>

        <!-- Form Card -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Card Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-purple-600 to-purple-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <i class="fas fa-file-edit text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Form Edit Materi</h2>
                                <p class="text-purple-200 text-sm mt-1">Perbarui informasi materi</p>
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

                    <form action="{{ route('materi.update', $materi) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

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
                                        <option value="{{ $mapel->id }}" {{ old('mapel_id', $materi->mapel_id) == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Judul Materi -->
                            <div>
                                <label for="title" class="block text-base font-bold text-gray-800 mb-3">
                                    <span class="flex items-center">
                                        <i class="fas fa-heading text-xl mr-2" style="color: #570C49;"></i>
                                        Judul Materi
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $materi->title) }}"
                                       class="w-full px-5 py-4 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300 text-base @error('title') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror"
                                       placeholder="Contoh: Materi Pengenalan Aljabar"
                                       required>
                            </div>
                        </div>

                        <!-- Current File Info -->
                        @if($materi->file_path)
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-blue-500 rounded-xl p-3">
                                        <i class="fas fa-file-pdf text-white text-3xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-600 mb-1">📄 File Saat Ini:</p>
                                        <p class="font-bold text-gray-800 text-lg">{{ basename($materi->file_path) }}</p>
                                        <a href="{{ route('materi.download', $materi->id) }}"
                                            class="inline-flex items-center mt-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>
                                            Download File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- File Upload Section -->
                        <div>
                            <label class="block text-base font-bold text-gray-800 mb-3">
                                <span class="flex items-center">
                                    <i class="fas fa-paperclip text-xl mr-2" style="color: #570C49;"></i>
                                    File Materi Baru
                                    <span class="text-gray-500 text-sm ml-2 font-normal">(Opsional - biarkan kosong jika tidak ingin mengubah file)</span>
                                </span>
                            </label>
                            
                            <input type="file" 
                                   id="file" 
                                   name="file" 
                                   class="w-full px-5 py-4 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300 text-base file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-700 file:font-medium hover:file:bg-purple-100 @error('file') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror"
                                   accept=".odf,.docx,.txt,.pdf,.xlsx"
                                   onchange="showFilePreview(this)">
                                   
                            <!-- File Info -->
                            <div class="mt-3 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                                <p class="text-sm text-purple-800 font-medium mb-2">
                                    <i class="fas fa-clipboard-list mr-1"></i> Format yang didukung:
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">ODF</span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">DOCX</span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">TXT</span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">PDF</span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">XLSX</span>
                                </div>
                                <p class="text-xs text-purple-600 mt-2">Maksimal ukuran file: 10MB</p>
                            </div>
                        </div>

                    <!-- File Preview Section -->
                    <div id="filePreview" class="hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i id="fileIcon" class="fas fa-file text-4xl" style="color: #570C49;"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-1">File Terpilih</h4>
                                        <p id="fileName" class="text-gray-700 font-medium">File name will appear here</p>
                                        <p id="fileInfo" class="text-sm text-gray-600">File info</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-sm font-bold rounded-full border border-green-300">
                                        ✓ Siap Upload
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Text Preview -->
                            <div id="textPreview" class="hidden mt-4">
                                <div class="bg-white border-2 border-green-100 rounded-xl p-4">
                                    <h5 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-eye mr-2"></i>
                                        Preview Konten:
                                    </h5>
                                    <div class="max-h-32 overflow-y-auto bg-gray-50 rounded-lg p-3">
                                        <pre id="textContent" class="text-xs text-gray-700 whitespace-pre-wrap font-mono"></pre>
                                    </div>
                                </div>
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
                                        class="flex-1 py-4 px-6 bg-gradient-to-r from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300">
                                    <span class="flex items-center justify-center">
                                        <i class="fas fa-save mr-2 text-xl"></i>
                                        Update Materi
                                    </span>
                                </button>
                            </div>
                            
                            <!-- Help Text -->
                            <div class="mt-6 text-center bg-purple-50 rounded-xl p-4 border-2 border-purple-200">
                                <p class="text-sm text-purple-700 font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Jika tidak ingin mengubah file, biarkan input file kosong
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function showFilePreview(input) {
    const preview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileInfo = document.getElementById('fileInfo');
    const fileIcon = document.getElementById('fileIcon');
    const textPreview = document.getElementById('textPreview');
    const textContent = document.getElementById('textContent');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        // Update file info
        fileName.textContent = file.name;
        fileInfo.textContent = `Format: ${fileExtension.toUpperCase()} • Ukuran: ${fileSize} MB`;
        
        // Update icon based on file type
        const icons = {
            'pdf': 'fas fa-file-pdf',
            'docx': 'fas fa-file-word', 
            'doc': 'fas fa-file-word',
            'txt': 'fas fa-file-alt',
            'xlsx': 'fas fa-file-excel',
            'odf': 'fas fa-file-pdf'
        };
        
        fileIcon.className = `text-4xl ${icons[fileExtension] || 'fas fa-file'}`;
        fileIcon.style.color = '#570C49';
        
        // Show text preview for TXT files
        if (fileExtension === 'txt' && file.size < 1024 * 1024) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const content = e.target.result;
                const previewText = content.length > 300 ? content.substring(0, 300) + '...' : content;
                textContent.textContent = previewText;
                textPreview.classList.remove('hidden');
            };
            reader.readAsText(file);
        } else {
            textPreview.classList.add('hidden');
        }
        
        // Show preview
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection