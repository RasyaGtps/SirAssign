@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-3xl shadow-2xl p-8 mb-8 text-white">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-28 h-28 rounded-full flex items-center justify-center bg-white bg-opacity-20 text-white text-4xl font-bold border-4 border-white border-opacity-30">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <h1 class="text-4xl font-bold mb-2">
                            👤 {{ Auth::user()->name }}
                        </h1>
                        <p class="text-purple-200 text-lg mb-3">{{ Auth::user()->email }}</p>
                        <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                            <span class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-full text-sm font-bold border-2 border-white border-opacity-30">
                                <i class="fas fa-user-tie mr-1"></i>{{ ucfirst(Auth::user()->role ?? 'guru') }}
                            </span>
                            @if(Auth::user()->nip)
                                <span class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-full text-sm font-bold border-2 border-white border-opacity-30">
                                    <i class="fas fa-id-badge mr-1"></i>NIP: {{ Auth::user()->nip }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Mata Pelajaran</p>
                            <p class="text-4xl font-bold">{{ Auth::user()->mapels ? Auth::user()->mapels->count() : 0 }}</p>
                            <p class="text-purple-200 text-xs mt-1">Mapel yang diajar</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-book text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Materi</p>
                            <p class="text-4xl font-bold">
                                {{ Auth::user()->mapels ? Auth::user()->mapels->sum(function($mapel) { return $mapel->materi ? $mapel->materi->count() : 0; }) : 0 }}
                            </p>
                            <p class="text-blue-200 text-xs mt-1">Dokumen pembelajaran</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-file-pdf text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Tugas</p>
                            <p class="text-4xl font-bold">
                                {{ Auth::user()->mapels ? Auth::user()->mapels->sum(function($mapel) { return $mapel->tugas ? $mapel->tugas->count() : 0; }) : 0 }}
                            </p>
                            <p class="text-green-200 text-xs mt-1">Assignment dibuat</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                        <i class="fas fa-user-edit text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
                </div>

                @if(session('status') === 'profile-updated')
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-5 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-full p-2">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-bold text-green-800">✅ Berhasil!</p>
                                <p class="text-green-700">Profil berhasil diperbarui!</p>
                            </div>
                        </div>
                    </div>
                @endif

                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Change Password Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                        <i class="fas fa-lock text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Ubah Password</h2>
                </div>

                @if(session('status') === 'password-updated')
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-5 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-full p-2">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-bold text-green-800">✅ Berhasil!</p>
                                <p class="text-green-700">Password berhasil diperbarui!</p>
                            </div>
                        </div>
                    </div>
                @endif

                @include('profile.partials.update-password-form')
            </div>

            <!-- Mata Pelajaran yang Diajar -->
            @if(Auth::user()->mapels && Auth::user()->mapels->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                            <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Mata Pelajaran yang Diajar</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach(Auth::user()->mapels as $mapel)
                            <div class="group bg-gradient-to-br from-purple-50 to-white p-5 rounded-xl border-2 border-purple-200 hover:border-purple-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-purple-900 text-lg mb-2">{{ $mapel->nama_mapel }}</h3>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                                {{ $mapel->materi ? $mapel->materi->count() : 0 }} materi
                                            </span>
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                                {{ $mapel->tugas ? $mapel->tugas->count() : 0 }} tugas
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('mapel.show', $mapel) }}" 
                                       class="ml-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg w-10 h-10 flex items-center justify-center transition duration-300">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl shadow-xl p-16 text-center">
                    <div class="mb-6">
                        <i class="fas fa-chalkboard-teacher text-8xl text-gray-300"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Belum Ada Mata Pelajaran</h3>
                    <p class="text-gray-600 text-lg">Anda belum ditugaskan untuk mengajar mata pelajaran apapun. Silakan hubungi administrator sekolah.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    input:focus, textarea:focus {
        ring-color: #570C49 !important;
        border-color: #570C49 !important;
    }
</style>
@endpush
