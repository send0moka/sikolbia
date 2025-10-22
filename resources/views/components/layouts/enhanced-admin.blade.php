<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'SIKOLBIA') : config('app.name', 'SIKOLBIA') . ' - Admin Panel' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxStyles
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .notification-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 z-50 bg-white dark:bg-gray-900 bg-opacity-75 items-center justify-center hidden">
        <div class="text-center">
            <div class="loading-spinner w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full mx-auto mb-4"></div>
            <p class="text-gray-600 dark:text-gray-300">Loading...</p>
        </div>
    </div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="sidebar-transition" 
             :class="sidebarOpen ? 'w-64' : 'w-16'" 
             class="bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3" :class="!sidebarOpen && 'justify-center'">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-brain text-white text-sm"></i>
                    </div>
                    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <h1 class="text-lg font-bold text-gray-900 dark:text-white">SIKOLBIA</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">AI Platform</p>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-bars text-gray-500 dark:text-gray-400"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-2 py-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :title="!sidebarOpen ? 'Dashboard' : ''">
                    <i class="fas fa-home flex-shrink-0 w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Dashboard</span>
                </a>

                <!-- AI Prediction Dashboard -->
                <a href="{{ route('admin.prediction-dashboard') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.prediction-dashboard') ? 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :title="!sidebarOpen ? 'AI Prediction' : ''">
                    <i class="fas fa-robot flex-shrink-0 w-5 h-5 {{ request()->routeIs('admin.prediction-dashboard') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">AI Prediction</span>
                    <span x-show="sidebarOpen" class="ml-auto">
                        <span class="notification-badge bg-green-400 text-white text-xs px-2 py-1 rounded-full">New</span>
                    </span>
                </a>

                @can('view users')
                <!-- User Management -->
                <a href="{{ route('admin.users') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users') ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :title="!sidebarOpen ? 'Users' : ''">
                    <i class="fas fa-users flex-shrink-0 w-5 h-5 {{ request()->routeIs('admin.users') ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Kelola User</span>
                </a>
                @endcan

                @can('view kelompok')
                <!-- Kelompok Management -->
                <a href="{{ route('admin.kelompok') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.kelompok') ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :title="!sidebarOpen ? 'Kelompok' : ''">
                    <i class="fas fa-tags flex-shrink-0 w-5 h-5 {{ request()->routeIs('admin.kelompok') ? 'text-yellow-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Kelompok</span>
                </a>
                @endcan

                @can('view komoditi')
                <!-- Komoditi Management -->
                <a href="{{ route('admin.komoditi') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.komoditi') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :title="!sidebarOpen ? 'Komoditi' : ''">
                    <i class="fas fa-seedling flex-shrink-0 w-5 h-5 {{ request()->routeIs('admin.komoditi') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Komoditi</span>
                </a>
                @endcan

                @can('view transaksi_nbm')
                <!-- Transaksi NBM -->
                <a href="{{ route('admin.transaksi-nbm') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.transaksi-nbm') ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :title="!sidebarOpen ? 'Transaksi NBM' : ''">
                    <i class="fas fa-exchange-alt flex-shrink-0 w-5 h-5 {{ request()->routeIs('admin.transaksi-nbm') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Transaksi NBM</span>
                </a>
                @endcan

                <!-- Divider -->
                <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

                <!-- Other Modules -->
                <div x-show="sidebarOpen" x-transition>
                    <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Modules</p>
                </div>
                
                <a href="/admin/lahan" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :title="!sidebarOpen ? 'Lahan' : ''">
                    <i class="fas fa-leaf flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Lahan</span>
                </a>

                <a href="{{ route('admin.benih-pupuk.dashboard') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :title="!sidebarOpen ? 'Benih Pupuk' : ''">
                    <i class="fas fa-spa flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Benih Pupuk</span>
                </a>

                <a href="{{ route('admin.iklim-opt-dpi.dashboard') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :title="!sidebarOpen ? 'Iklim OPT-DPI' : ''">
                    <i class="fas fa-cloud-sun flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Iklim OPT-DPI</span>
                </a>

                <a href="{{ route('admin.daftar-alamat.dashboard') }}" 
                   class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :title="!sidebarOpen ? 'Daftar Alamat' : ''">
                    <i class="fas fa-map-marker-alt flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Daftar Alamat</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div x-show="sidebarOpen" x-transition class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 dark:text-gray-300 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->roles->first()?->name ?? 'User' }}</p>
                    </div>
                </div>
                <div x-show="!sidebarOpen" class="text-center">
                    <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-user text-gray-600 dark:text-gray-300 text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Page Title -->
                        <div class="flex items-center">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-bars text-gray-500 dark:text-gray-400"></i>
                            </button>
                            <div class="ml-4 md:ml-0">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $title ?? 'Admin Panel' }}
                                </h1>
                                @if(isset($subtitle))
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Top Bar Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 relative">
                                    <i class="fas fa-bell text-gray-500 dark:text-gray-400"></i>
                                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>
                                <!-- Notification Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition 
                                     class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-inbox text-2xl mb-2"></i>
                                            <p>No new notifications</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Menu -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </button>
                                <!-- User Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition 
                                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-user-circle mr-3"></i>
                                            Profile
                                        </a>
                                        <a href="/" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-home mr-3"></i>
                                            Back to Site
                                        </a>
                                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fas fa-sign-out-alt mr-3"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Flash Messages -->
                        @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition 
                             class="mb-6 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-md flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                {{ session('success') }}
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition 
                             class="mb-6 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-md flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                {{ session('error') }}
                            </div>
                            <button @click="show = false" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endif

                        <!-- Page Content -->
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" 
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    @fluxScripts
    @stack('scripts')

    <script>
        // Global loading functions
        window.showLoading = function() {
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        };

        window.hideLoading = function() {
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        };

        // Auto-hide flash messages
        setTimeout(() => {
            const alerts = document.querySelectorAll('[x-data*="show: true"]');
            alerts.forEach(alert => {
                if (alert.__x && alert.__x.$data && alert.__x.$data.show) {
                    alert.__x.$data.show = false;
                }
            });
        }, 5000);
    </script>
</body>
</html>