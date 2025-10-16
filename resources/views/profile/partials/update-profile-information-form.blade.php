
<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-2" style="color: #570C49;"></i>Nama Lengkap
            </label>
            <input id="name" name="name" type="text" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200" 
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2" style="color: #570C49;"></i>Email
            </label>
            <input id="email" name="email" type="email" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200" 
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-id-badge mr-2" style="color: #570C49;"></i>NIP (Opsional)
            </label>
            <input id="nip" name="nip" type="text" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200" 
                   value="{{ old('nip', $user->nip ?? '') }}" autocomplete="nip">
            @error('nip')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user-circle mr-2" style="color: #570C49;"></i>Bio / Deskripsi (Opsional)
            </label>
            <textarea id="bio" name="bio" rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200" 
                      placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
            @error('bio')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                <p class="text-yellow-800 text-sm">
                    Email Anda belum diverifikasi.
                    <button form="send-verification" class="underline text-yellow-700 hover:text-yellow-900 ml-1">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                </p>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="mt-2 flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    <p class="text-green-700 text-sm">Link verifikasi baru telah dikirim ke email Anda.</p>
                </div>
            @endif
        </div>
    @endif

    <div class="flex items-center justify-between pt-4">
        <button type="submit" 
                class="px-6 py-3 text-white font-medium rounded-xl transition duration-200 hover:shadow-lg flex items-center"
                style="background: linear-gradient(135deg, #570C49 0%, #8B1538 100%);">
            <i class="fas fa-save mr-2"></i>Simpan Perubahan
        </button>
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

