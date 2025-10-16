<nav style="background-color: #570C49;" class="border-b border-gray-100 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left: Sir Assign Logo -->
            <div class="flex items-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="text-white text-2xl font-bold hover:text-yellow-300 transition duration-300">
                    <i class="fas fa-graduation-cap mr-2"></i> Sir Assign
                </a>
            </div>

            @auth
            <!-- Center: Navigation Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('dashboard') }}" 
                   class="text-white hover:text-yellow-300 transition duration-300 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
                <a href="{{ route('mapel.index') }}" 
                   class="text-white hover:text-yellow-300 transition duration-300 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('mapel.*') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                    <i class="fas fa-book mr-1"></i> Mata Pelajaran
                </a>
                <a href="{{ route('materi.index') }}" 
                   class="text-white hover:text-yellow-300 transition duration-300 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('materi.*') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                    <i class="fas fa-file-alt mr-1"></i> Materi
                </a>
                <a href="{{ route('tugas.index') }}" 
                   class="text-white hover:text-yellow-300 transition duration-300 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tugas.*') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                    <i class="fas fa-tasks mr-1"></i> Tugas
                </a>
            </div>

            <!-- Right: User Menu & Actions -->
            <div class="flex items-center space-x-3">
                <!-- Add Actions Dropdown -->
                <div class="relative inline-block text-left">
                    <button type="button" 
                            class="bg-yellow-400 hover:bg-yellow-500 text-purple-900 font-bold py-2 px-4 rounded-lg transition duration-300 text-sm flex items-center"
                            onclick="toggleDropdown('add-dropdown')">
                        <i class="fas fa-plus mr-1"></i> Tambah
                        <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div id="add-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 border">
                        <a href="{{ route('materi.create') }}" 
                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                            <i class="fas fa-file-alt mr-2 text-blue-500"></i> Tambah Materi
                        </a>
                        <a href="{{ route('tugas.create') }}" 
                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                            <i class="fas fa-tasks mr-2 text-green-500"></i> Tambah Tugas
                        </a>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative inline-block text-left">
                    <button type="button" 
                            class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm flex items-center"
                            onclick="toggleDropdown('user-dropdown')">
                        <i class="fas fa-user mr-2"></i>
                        <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                        <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 border">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm text-gray-700 font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            @if(Auth::user()->nip)
                                <p class="text-xs text-gray-500">NIP: {{ Auth::user()->nip }}</p>
                            @endif
                        </div>
                        <a href="{{ route('profile.edit') }}" 
                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-edit mr-2 text-blue-500"></i> Edit Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth

            <!-- Mobile menu button (only show when authenticated) -->
            @auth
            <div class="md:hidden">
                <button type="button" 
                        class="text-white hover:text-yellow-300 focus:outline-none focus:text-yellow-300"
                        onclick="toggleMobileMenu()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            @endauth
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    @auth
    <div id="mobile-menu" class="md:hidden hidden bg-purple-800 bg-opacity-95">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="text-white hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('mapel.index') }}" 
               class="text-white hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('mapel.*') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                <i class="fas fa-book mr-2"></i> Mata Pelajaran
            </a>
            <a href="{{ route('materi.index') }}" 
               class="text-white hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('materi.*') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                <i class="fas fa-file-alt mr-2"></i> Materi
            </a>
            <a href="{{ route('tugas.index') }}" 
               class="text-white hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('tugas.*') ? 'text-yellow-300 bg-white bg-opacity-10' : '' }}">
                <i class="fas fa-tasks mr-2"></i> Tugas
            </a>
            
            <!-- Mobile User Info -->
            <div class="border-t border-purple-700 pt-4 mt-4">
                <div class="px-3 py-2">
                    <p class="text-yellow-300 font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-purple-200 text-sm">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" 
                   class="text-white hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-user-edit mr-2"></i> Edit Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left text-white hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth
</nav>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const allDropdowns = document.querySelectorAll('[id$="-dropdown"]');
        
        // Close all other dropdowns
        allDropdowns.forEach(d => {
            if (d.id !== dropdownId) {
                d.classList.add('hidden');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
    }

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('[id$="-dropdown"]');
        const buttons = document.querySelectorAll('button[onclick*="toggleDropdown"]');
        
        let clickedButton = false;
        buttons.forEach(button => {
            if (button.contains(event.target)) {
                clickedButton = true;
            }
        });
        
        if (!clickedButton) {
            dropdowns.forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
</script>
         