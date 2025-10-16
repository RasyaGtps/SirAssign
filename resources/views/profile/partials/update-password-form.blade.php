<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="grid grid-cols-1 gap-6">
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-key mr-2" style="color: #570C49;"></i>Password Saat Ini
            </label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200" 
                   autocomplete="current-password" required>
            @error('current_password', 'updatePassword')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2" style="color: #570C49;"></i>Password Baru
            </label>
            <input id="update_password_password" name="password" type="password" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200" 
                   autocomplete="new-password" required>
            @error('password', 'updatePassword')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2" style="color: #570C49;"></i>Konfirmasi Password Baru
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200" 
                   autocomplete="new-password" required>
            @error('password_confirmation', 'updatePassword')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mr-2 mt-1"></i>
            <div class="text-blue-800 text-sm">
                <h4 class="font-medium mb-1">Tips Password yang Kuat:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Minimal 8 karakter</li>
                    <li>Kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                    <li>Hindari informasi pribadi yang mudah ditebak</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between pt-4">
        <button type="submit" 
                class="px-6 py-3 text-white font-medium rounded-xl transition duration-200 hover:shadow-lg flex items-center"
                style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
            <i class="fas fa-shield-alt mr-2"></i>Update Password
        </button>
    </div>
</form>
