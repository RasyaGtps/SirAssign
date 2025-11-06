@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="mb-4">
                <i class="fas fa-cloud-upload-alt text-6xl" style="color: #570C49;"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3" style="color: #570C49;">
                📤 Upload Materi Baru
            </h1>
            <p class="text-gray-700 text-lg max-w-md mx-auto font-medium">
                Tambahkan materi pembelajaran baru ke dalam sistem
            </p>
        </div>

        <!-- Main Form Card -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                
                <!-- Card Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-purple-600 to-purple-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <i class="fas fa-file-upload text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Form Upload Materi</h2>
                                <p class="text-purple-200 text-sm mt-1">Lengkapi informasi materi yang akan di-upload</p>
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

                    <form action="{{ route('materi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <!-- Form Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <!-- Left Column -->
                            <div class="space-y-6">
                                
                                <!-- Mapel Selection -->
                                <div class="form-group">
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
                                    @error('mapel_id')
                                        <p class="text-red-600 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <!-- Title Input -->
                                <div class="form-group">
                                    <label for="title" class="block text-base font-bold text-gray-800 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-edit text-xl mr-2" style="color: #570C49;"></i>
                                            Judul Materi
                                            <span class="text-red-500 ml-1">*</span>
                                        </span>
                                    </label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}"
                                           class="w-full px-5 py-4 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300 text-base @error('title') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror"
                                           placeholder="Contoh: Materi Pengenalan Aljabar"
                                           required>
                                    @error('title')
                                        <p class="text-red-600 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <!-- File Format Info -->
                                <div class="mt-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <p class="text-sm text-blue-800 font-medium mb-2">
                                        <i class="fas fa-clipboard-list mr-1"></i> Format yang didukung:
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                            <i class="fas fa-file mr-1"></i>ODF
                                        </span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                            <i class="fas fa-file-word mr-1"></i>DOCX
                                        </span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                            <i class="fas fa-file-alt mr-1"></i>TXT
                                        </span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                            <i class="fas fa-file-pdf mr-1"></i>PDF
                                        </span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                            <i class="fas fa-file-excel mr-1"></i>XLSX
                                        </span>
                                    </div>
                                    <p class="text-xs text-blue-600 mt-2">
                                        <i class="fas fa-database mr-1"></i>
                                        Maksimal ukuran file: 10MB
                                    </p>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                
                                <!-- File Upload with Drag & Drop -->
                                <div class="form-group">
                                    <label class="block text-base font-bold text-gray-800 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-paperclip text-xl mr-2" style="color: #570C49;"></i>
                                            File Materi
                                            <span class="text-red-500 ml-1">*</span>
                                        </span>
                                    </label>
                                    
                                    <!-- Drag & Drop Area -->
                                    <div id="drop-area" 
                                         class="relative border-4 border-dashed rounded-2xl transition-all duration-300 cursor-pointer bg-gradient-to-br from-purple-50 to-white hover:from-purple-100 hover:to-purple-50 @error('file') border-red-400 bg-red-50 @else border-purple-300 hover:border-purple-500 @enderror">
                                        
                                        <!-- Hidden File Input -->
                                        <input type="file" 
                                               id="file" 
                                               name="file" 
                                               class="hidden"
                                               accept=".odf,.docx,.txt,.pdf,.xlsx"
                                               required
                                               onchange="handleFileSelect(this.files)">
                                        
                                        <!-- Drop Area Content -->
                                        <div id="drop-content" class="p-8 text-center">
                                            <div class="mb-4">
                                                <i class="fas fa-upload text-6xl text-purple-400"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-800 mb-2">
                                                Drag & Drop File di Sini
                                            </h3>
                                            <p class="text-gray-600 mb-4">atau</p>
                                            <button type="button" 
                                                    onclick="document.getElementById('file').click()"
                                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                                                <i class="fas fa-folder-open mr-2"></i>
                                                Browse File
                                            </button>
                                            <p class="text-xs text-gray-500 mt-4">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Maksimal 10MB • PDF, DOCX, TXT, XLSX, ODF
                                            </p>
                                        </div>
                                        
                                        <div id="drag-overlay" class="hidden absolute inset-0 bg-purple-600 bg-opacity-90 rounded-2xl flex items-center justify-center">
                                            <div class="text-center">
                                                <i class="fas fa-download text-6xl text-white mb-4 animate-pulse"></i>
                                                <p class="text-2xl font-bold text-white">Lepaskan File di Sini!</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('file')
                                        <p class="text-red-600 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- File Preview Section -->
                        <div id="file-preview" class="hidden">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <i id="file-icon" class="fas fa-file text-4xl" style="color: #570C49;"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-bold text-gray-800 mb-1">File Terpilih</h4>
                                            <p id="file-name" class="text-gray-700 font-medium break-all"></p>
                                            <p id="file-info" class="text-sm text-gray-600"></p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-sm font-bold rounded-full border border-green-300">
                                            ✓ Siap Upload
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Text Preview -->
                                <div id="text-preview" class="hidden">
                                    <div class="bg-white border-2 border-green-100 rounded-xl p-4">
                                        <h5 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-eye mr-2"></i>
                                            Preview Konten:
                                        </h5>
                                        <div class="max-h-32 overflow-y-auto bg-gray-50 rounded-lg p-3">
                                            <pre id="text-content" class="text-xs text-gray-700 whitespace-pre-wrap font-mono"></pre>
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
                                        <i class="fas fa-cloud-upload-alt mr-2 text-xl"></i>
                                        Upload Materi
                                    </span>
                                </button>
                            </div>
                            
                            <!-- Help Text -->
                            <div class="mt-6 text-center bg-purple-50 rounded-xl p-4 border-2 border-purple-200">
                                <p class="text-sm text-purple-700 font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pastikan semua informasi sudah benar sebelum mengupload materi
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
// ===== DRAG & DROP FUNCTIONALITY =====

const dropArea = document.getElementById('drop-area');
const dropContent = document.getElementById('drop-content');
const dragOverlay = document.getElementById('drag-overlay');
const fileInput = document.getElementById('file');

// Drag counter to prevent flickering
let dragCounter = 0;

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Highlight drop area when dragging over it
dropArea.addEventListener('dragenter', function(e) {
    dragCounter++;
    highlight();
}, false);

dropArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
}, false);

dropArea.addEventListener('dragleave', function(e) {
    dragCounter--;
    if (dragCounter === 0) {
        unhighlight();
    }
}, false);

dropArea.addEventListener('drop', function(e) {
    dragCounter = 0;
    unhighlight();
    handleDrop(e);
}, false);

function highlight() {
    dragOverlay.classList.remove('hidden');
    dropArea.classList.add('border-purple-600', 'scale-105', 'shadow-2xl');
}

function unhighlight() {
    dragOverlay.classList.add('hidden');
    dropArea.classList.remove('border-purple-600', 'scale-105', 'shadow-2xl');
}

// Handle dropped files
function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const dt = e.dataTransfer;
    const files = dt.files;
    
    console.log('Files dropped:', files.length);
    
    if (files && files.length > 0) {
        // Create a new FileList-like object
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(files[0]);
        
        // Assign to file input
        fileInput.files = dataTransfer.files;
        
        console.log('File assigned to input:', fileInput.files.length);
        
        // Process the file
        handleFileSelect(files);
    }
}

// Handle file selection (both drag-drop and browse)
function handleFileSelect(files) {
    if (files && files.length > 0) {
        const file = files[0];
        
        // Validate file type
        const allowedTypes = ['.odf', '.docx', '.txt', '.pdf', '.xlsx'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            alert('⚠️ Format file tidak didukung!\n\nHanya menerima: PDF, DOCX, TXT, XLSX, ODF');
            fileInput.value = '';
            return;
        }
        
        // Validate file size (10MB max)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (file.size > maxSize) {
            alert('⚠️ Ukuran file terlalu besar!\n\nMaksimal: 10MB\nUkuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB');
            fileInput.value = '';
            return;
        }
        
        // Update drop area to show selected file
        updateDropAreaWithFile(file);
        
        // Show file preview
        showFilePreview(file);
    }
}

// Update drop area to show file is selected
function updateDropAreaWithFile(file) {
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    // Icons based on file type
    const icons = {
        'pdf': 'fa-file-pdf text-red-500',
        'docx': 'fa-file-word text-blue-500', 
        'txt': 'fa-file-alt text-gray-500',
        'xlsx': 'fa-file-excel text-green-500',
        'odf': 'fa-file-pdf text-orange-500'
    };
    
    const iconClass = icons[fileExtension] || 'fa-file text-gray-500';
    
    dropContent.innerHTML = `
        <div class="p-6">
            <div class="mb-4">
                <i class="fas ${iconClass} text-6xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                File Terpilih!
            </h3>
            <p class="text-gray-700 font-semibold mb-1 break-all max-w-full px-2">${file.name}</p>
            <p class="text-sm text-gray-600 mb-4">
                ${fileExtension.toUpperCase()} • ${fileSize} MB
            </p>
            <button type="button" 
                    onclick="resetFileInput()"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition duration-300">
                <i class="fas fa-times mr-2"></i>
                Ganti File
            </button>
        </div>
    `;
}

// Reset file input
function resetFileInput() {
    fileInput.value = '';
    
    // Restore original drop area content
    dropContent.innerHTML = `
        <div class="mb-4">
            <i class="fas fa-cloud-upload-alt text-6xl text-purple-400 animate-bounce"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">
            <i class="fas fa-hand-pointer mr-2"></i>
            Drag & Drop File di Sini
        </h3>
        <p class="text-gray-600 mb-4">atau</p>
        <button type="button" 
                onclick="document.getElementById('file').click()"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <i class="fas fa-folder-open mr-2"></i>
            Browse File
        </button>
        <p class="text-xs text-gray-500 mt-4">
            <i class="fas fa-info-circle mr-1"></i>
            Maksimal 10MB • PDF, DOCX, TXT, XLSX, ODF
        </p>
    `;
    
    // Hide preview
    const preview = document.getElementById('file-preview');
    preview.classList.add('hidden');
}

// ===== FILE PREVIEW FUNCTIONALITY =====

function showFilePreview(file) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileInfo = document.getElementById('file-info');
    const fileIcon = document.getElementById('file-icon');
    const textPreview = document.getElementById('text-preview');
    const textContent = document.getElementById('text-content');
    
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    // Update file info
    fileName.textContent = file.name;
    fileInfo.textContent = `Format: ${fileExtension.toUpperCase()} • Ukuran: ${fileSize} MB`;
    
    // Update icon based on file type
    const icons = {
        'pdf': 'fas fa-file-pdf',
        'docx': 'fas fa-file-word', 
        'txt': 'fas fa-file-alt',
        'xlsx': 'fas fa-file-excel',
        'odf': 'fas fa-file-pdf'
    };
    
    // Clear previous classes and set new icon
    fileIcon.className = `text-4xl ${icons[fileExtension] || 'fas fa-file'}`;
    fileIcon.style.color = '#570C49';
    
    // Show text preview for TXT files
    if (fileExtension === 'txt' && file.size < 1024 * 1024) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const content = e.target.result;
            const preview = content.length > 300 ? content.substring(0, 300) + '...' : content;
            textContent.textContent = preview;
            textPreview.classList.remove('hidden');
        };
        reader.readAsText(file);
    } else {
        textPreview.classList.add('hidden');
    }
    
    // Show preview with animation
    preview.classList.remove('hidden');
    setTimeout(() => {
        preview.style.opacity = '1';
    }, 100);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('file-preview');
    preview.style.transition = 'opacity 0.3s ease-in-out';
    preview.style.opacity = '0';
    
    // Add click handler to entire drop area
    dropArea.addEventListener('click', function(e) {
        // Don't trigger if clicking the browse button or change file button
        if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'I') {
            fileInput.click();
        }
    });
});
</script>
@endsection