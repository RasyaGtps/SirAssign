@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bleeding-bg">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-white shadow-lg border-4 glow-border" style="border-color: #570C49;">
                <i class="fas fa-graduation-cap text-4xl" style="color: #570C49;"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white drop-shadow-lg">
                Login Sistem Manajemen Tugas
            </h2>
            <p class="mt-2 text-center text-sm" style="color: #FFE693;">
                Silakan masuk dengan akun guru Anda
            </p>
        </div>
        
        <form class="mt-8 space-y-6 bg-white rounded-2xl p-8 shadow-xl border-2 glow-border" style="border-color: #FFE693;" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2" style="color: #570C49;"></i>
                        Email Address
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           value="{{ old('email') }}"
                           class="appearance-none relative block w-full px-4 py-3 border-2 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition duration-300 @error('email') border-red-400 bg-red-50 @enderror"
                           style="border-color: #FFE693; focus:ring-color: #570C49;"
                           placeholder="guru@sekolah.id">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2" style="color: #570C49;"></i>
                        Password
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required 
                           class="appearance-none relative block w-full px-4 py-3 border-2 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition duration-300 @error('password') border-red-400 bg-red-50 @enderror"
                           style="border-color: #FFE693; focus:ring-color: #570C49;"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 rounded border-2" 
                           style="color: #570C49; border-color: #FFE693;">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border-2 border-transparent text-sm font-bold rounded-xl text-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-offset-2 transform transition duration-300 shadow-lg hover:shadow-xl"
                        style="background: linear-gradient(135deg, #570C49 0%, #8B1538 50%, #B91C5C 100%); border-color: #FFE693; focus:ring-color: #570C49;">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt group-hover:text-white transition duration-300" style="color: #FFE693;"></i>
                    </span>
                    Masuk ke Sistem
                </button>
            </div>

            @if($errors->any())
                <div class="border-l-4 p-4 rounded-r-lg" style="background-color: #FEF2F2; border-color: #F87171;">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                Login gagal. Periksa kembali email dan password Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </form>

        <!-- Demo Account Info -->
        <div class="text-center bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4 border-2" style="border-color: #FFE693;">
            <p class="text-white text-sm font-medium mb-2">
                <i class="fas fa-info-circle mr-2" style="color: #FFE693;"></i>
                Akun Demo
            </p>
            <p class="text-xs" style="color: #FFE693;">
                Email: ahmad@sekolah.id | Password: password123
            </p>
        </div>

        <div class="text-center">
            <p class="text-sm" style="color: #FFE693;">
                Sistem Manajemen Tugas Sekolah &copy; {{ date('Y') }}
            </p>
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
    
    input:focus {
        ring-color: #570C49 !important;
        border-color: #570C49 !important;
    }
    
    /* Custom bleeding gradient animation */
    .bleeding-bg {
        background: linear-gradient(135deg, #570C49 0%, #8B1538 25%, #B91C5C 50%, #F59E0B 75%, #FFE693 100%);
        background-size: 400% 400%;
        animation: gradient-shift 8s ease infinite;
    }
    
    @keyframes gradient-shift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    
    /* Glowing border effect */
    .glow-border {
        box-shadow: 0 0 20px rgba(255, 230, 147, 0.5);
    }
    
    .glow-border:hover {
        box-shadow: 0 0 30px rgba(255, 230, 147, 0.8);
    }
</style>
@endpush