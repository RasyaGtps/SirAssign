@extends('layouts.app')

@section('content')
<div class="min-h-screen p-6" style="background-color: #FFE693;">
    <!-- Header Section -->
    <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-1 md:space-x-3">
                    <li class="flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center text-gray-700 transition-colors duration-200" style="color: #570C49;"
                            onmouseover="this.style.color='#570C49'" onmouseout="this.style.color='#4B5563'">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('materi.index') }}"
                            class="ml-1 text-gray-700 transition-colors duration-200 md:ml-2" style="color: #570C49;"
                            onmouseover="this.style.color='#570C49'" onmouseout="this.style.color='#4B5563'">Materi</a>
                    </li>
                    <li aria-current="page" class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-600 md:ml-2 font-medium">Edit Materi</span>
                    </li>
                </ol>
            </nav>

            <!-- Main Header -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6" style="border: 1px solid #570C49;">
                <div class="flex items-center space-x-3">
                    <div class="p-3 rounded-lg shadow-lg" style="background-color: #570C49;">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Materi</h1>
                        <p class="text-gray-600">Perbarui informasi materi pembelajaran</p>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4" style="background-color: #570C49;">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Form Edit Materi
                    </h2>
                </div>

                <form action="{{ route('materi.update', $materi) }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
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
                                    style="--tw-ring-color: #570C49;" onfocus="this.style.borderColor='#570C49';"
                                    onblur="this.style.borderColor='#D1D5DB';">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mapels as $mapel)
                                        <option value="{{ $mapel->id }}"
                                            {{ old('mapel_id', $materi->mapel_id) == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('mapel_id')
                                <p class="text-red-500 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Judul Materi -->
                        <div class="space-y-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700">
                                Judul Materi
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $materi->title) }}" placeholder="Masukkan judul materi" required
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 transition-all duration-200 bg-white
                               @error('title') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-gray-300 @enderror"
                style="--tw-ring-color: #570C49;" onfocus="this.style.borderColor='#570C49';"
                onblur="this.style.borderColor='#D1D5DB';">
                            @error('title')
                                <p class="text-red-500 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div class="space-y-2">
                        <label for="file" class="block text-sm font-semibold text-gray-700">
                            File Materi
                            <span class="text-gray-500 text-xs">(Opsional - biarkan kosong jika tidak ingin mengubah
                                file)</span>
                        </label>

                        <!-- Current File Info -->
                        @if ($materi->file_path)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 rounded-lg" style="background-color: #570C49;">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">File Saat Ini:</p>
                                        <p class="text-gray-600">{{ basename($materi->file_path) }}</p>
                                        <a href="{{ route('materi.download', $materi->id) }}"
                                            class="text-sm font-medium inline-flex items-center mt-1 transition-colors duration-200"
                                            style="color: #570C49;" onmouseover="this.style.color='#4A0B3E'"
                                            onmouseout="this.style.color='#570C49'">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                                </path>
                                            </svg>
                                            Download File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- File Upload Input -->
                        <div class="relative">
                            <style>
                                input[type="file"]::file-selector-button {
                                    background-color: #570C49 !important;
                                    color: white !important;
                                    border: none !important;
                                    padding: 8px 16px !important;
                                    border-radius: 8px !important;
                                    font-weight: 500 !important;
                                    font-size: 14px !important;
                                    margin-right: 16px !important;
                                    cursor: pointer !important;
                                    transition: background-color 0.2s !important;
                                }
                                input[type="file"]::file-selector-button:hover {
                                    background-color: #4A0B3E !important;
                                }
                                input[type="file"]::-webkit-file-upload-button {
                                    background-color: #570C49 !important;
                                    color: white !important;
                                    border: none !important;
                                    padding: 8px 16px !important;
                                    border-radius: 8px !important;
                                    font-weight: 500 !important;
                                    font-size: 14px !important;
                                    margin-right: 16px !important;
                                    cursor: pointer !important;
                                    transition: background-color 0.2s !important;
                                }
                                input[type="file"]::-webkit-file-upload-button:hover {
                                    background-color: #4A0B3E !important;
                                }
                            </style>
                            <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.txt,.xlsx,.odf"
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 transition-all duration-200 bg-white cursor-pointer"
                                style="border-color: #570C49; --tw-ring-color: #570C49;"
                                onfocus="this.style.borderColor='#570C49';" onblur="this.style.borderColor='#D1D5DB';"
                                onchange="showFilePreview(this)">
                        </div>
                        @error('file')
                            <p class="text-red-500 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
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

                    <!-- Upload Guidelines -->
                    <div class="bg-gradient-to-r border rounded-xl p-6"
                        style="background: linear-gradient(135deg, #570C49 0%, #4A0B3E 100%); border-color: #570C49;">
                        <div class="flex items-start space-x-3">
                            <div class="bg-white bg-opacity-20 p-2 rounded-lg flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Petunjuk Upload File</h3>
                                <ul class="space-y-1 text-white text-opacity-90">
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Format yang didukung: PDF, DOC, DOCX, TXT, XLSX, ODF
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Ukuran maksimal file: 10MB
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Biarkan kosong jika tidak ingin mengubah file
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between gap-4 pt-6">
                        <a href="{{ route('materi.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-8 py-3 text-white rounded-lg focus:ring-2 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg"
                            style="background-color: #570C49; --tw-ring-color: #570C49;"
                            onmouseover="this.style.backgroundColor='#4A0B3E'"
                            onmouseout="this.style.backgroundColor='#570C49'">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Update Materi
                        </button>
                    </div>
                </form>
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
            'odf': 'fas fa-file'
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