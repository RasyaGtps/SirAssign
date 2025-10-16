@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold" 
                             style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-3xl font-bold mb-2" style="color: #570C49;">
                            {{ Auth::user()->name }}
                        </h1>
                        <p class="text-gray-600 text-lg">{{ Auth::user()->email }}</p>
                        @if(Auth::user()->bio)
                            <p class="text-gray-700 mt-2 text-sm leading-relaxed">{{ Auth::user()->bio }}</p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-3 justify-center sm:justify-start">
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                <i class="fas fa-user-tie mr-1"></i>{{ ucfirst(Auth::user()->role ?? 'guru') }}
                            </span>
                            @if(Auth::user()->nip)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    <i class="fas fa-id-badge mr-1"></i>NIP: {{ Auth::user()->nip }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4" style="border-left-color: #570C49;">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-book text-3xl" style="color: #570C49;"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Mata Pelajaran</p>
                            <p class="text-2xl font-bold" style="color: #570C49;">{{ Auth::user()->mapels ? Auth::user()->mapels->count() : 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-3xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Materi</p>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ Auth::user()->mapels ? Auth::user()->mapels->sum(function($mapel) { return $mapel->materi ? $mapel->materi->count() : 0; }) : 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tasks text-3xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Tugas</p>
                            <p class="text-2xl font-bold text-green-600">
                                {{ Auth::user()->mapels ? Auth::user()->mapels->sum(function($mapel) { return $mapel->tugas ? $mapel->tugas->count() : 0; }) : 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6" style="color: #570C49;">
                    <i class="fas fa-user-edit mr-3"></i>Edit Profil
                </h2>

                @if(session('status') === 'profile-updated')
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <p class="text-green-700 font-medium">Profil berhasil diperbarui!</p>
                        </div>
                    </div>
                @endif

                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Change Password Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6" style="color: #570C49;">
                    <i class="fas fa-lock mr-3"></i>Ubah Password
                </h2>

                @if(session('status') === 'password-updated')
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <p class="text-green-700 font-medium">Password berhasil diperbarui!</p>
                        </div>
                    </div>
                @endif

                @include('profile.partials.update-password-form')
            </div>

            <!-- Mata Pelajaran yang Diajar -->
            @if(Auth::user()->mapels && Auth::user()->mapels->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6" style="color: #570C49;">
                        <i class="fas fa-chalkboard-teacher mr-3"></i>Mata Pelajaran yang Diajar
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach(Auth::user()->mapels as $mapel)
                            <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-4 rounded-xl border-2 border-purple-200 hover:border-purple-300 transition duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-purple-900">{{ $mapel->nama_mapel }}</h3>
                                        <p class="text-sm text-purple-600">
                                            {{ $mapel->materi ? $mapel->materi->count() : 0 }} materi • {{ $mapel->tugas ? $mapel->tugas->count() : 0 }} tugas
                                        </p>
                                    </div>
                                    <a href="{{ route('mapel.show', $mapel) }}" 
                                       class="text-purple-600 hover:text-purple-800 transition duration-300">
                                        <i class="fas fa-external-link-alt text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-chalkboard-teacher text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Mata Pelajaran</h3>
                    <p class="text-gray-500">Anda belum ditugaskan untuk mengajar mata pelajaran apapun. Silakan hubungi administrator sekolah.</p>
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
