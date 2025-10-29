@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-color: #FFE693;">
    <div class="max-w-5xl w-full">
        <!-- Main Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Left Side - Branding -->
            <div class="p-12 flex flex-col justify-center bg-gradient-to-br from-purple-900 to-purple-700" style="background: linear-gradient(135deg, #570C49 0%, #6D1050 100%);">
                <div class="text-white">
                    <!-- Logo/Icon -->
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white bg-opacity-10 backdrop-blur-sm mb-6">
                            <i class="fas fa-graduation-cap text-4xl" style="color: #FFE693;"></i>
                        </div>
                        <h1 class="text-4xl font-bold mb-3" style="color: #FFE693;">SirAssign</h1>
                        <p class="text-lg text-purple-100 font-medium">Sistem Manajemen Tugas Sekolah</p>
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-4 mt-12">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-check-circle text-xl" style="color: #FFE693;"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white mb-1">Analisis AI Otomatis</h3>
                                <p class="text-sm text-purple-200">Tingkat kesulitan soal dianalisis secara cerdas</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-check-circle text-xl" style="color: #FFE693;"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white mb-1">Manajemen Efisien</h3>
                                <p class="text-sm text-purple-200">Kelola materi dan tugas dalam satu platform</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-check-circle text-xl" style="color: #FFE693;"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white mb-1">Dashboard Intuitif</h3>
                                <p class="text-sm text-purple-200">Antarmuka modern dan mudah digunakan</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-16 pt-8 border-t border-white border-opacity-20">
                        <p class="text-sm text-purple-200">
                            <i class="far fa-copyright mr-1"></i>
                            {{ date('Y') }} SirAssign. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="p-12 flex flex-col justify-center">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold mb-2" style="color: #570C49;">
                        Selamat Datang
                    </h2>
                    <p class="text-gray-600">
                        Silakan masuk dengan akun guru Anda
                    </p>
                </div>
        
        <form class="space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-5">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold mb-2" style="color: #570C49;">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent transition-all @error('email') border-red-400 bg-red-50 @enderror"
                               style="focus:ring-color: #570C49;"
                               placeholder="guru@sekolah.id">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold mb-2" style="color: #570C49;">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required 
                               class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent transition-all @error('password') border-red-400 bg-red-50 @enderror"
                               style="focus:ring-color: #570C49;"
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 rounded border-gray-300 text-purple-900 focus:ring-purple-900">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent text-base font-semibold rounded-lg text-white focus:outline-none focus:ring-4 focus:ring-offset-2 transform transition-all duration-200 hover:shadow-lg"
                        style="background-color: #570C49; focus:ring-color: #570C49;">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk ke Sistem
                </button>
            </div>

            <!-- Error Message -->
            @if($errors->any())
                <div class="rounded-lg p-4 bg-red-50 border border-red-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800 font-medium">
                                Login gagal. Periksa kembali email dan password Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        margin: 0;
        padding: 0;
    }
    
    /* Professional Input Focus */
    input:focus {
        ring-color: #570C49 !important;
        border-color: #570C49 !important;
    }
    
    /* Smooth Button Hover */
    button[type="submit"]:hover {
        background-color: #6D1050 !important;
        transform: translateY(-1px);
    }
    
    button[type="submit"]:active {
        transform: translateY(0);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .grid-cols-2 {
            grid-template-columns: 1fr !important;
        }
        
        .p-12 {
            padding: 2rem !important;
        }
    }
</style>
@endpush