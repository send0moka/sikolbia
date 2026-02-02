<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIKOLBIA') }} - Admin {{ isset($title) ? '- ' . $title : '' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm border-r border-gray-200">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200">
                <a href="{{ route('admin.prediction-dashboard') ?? '#' }}" class="text-xl font-bold text-blue-600">
                    <i class="fas fa-chart-line mr-2"></i>SIKOLBIA Admin
                </a>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="mt-6">
                <div class="px-3">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.prediction-dashboard') ?? '#' }}" 
                       class="group flex items-center px-3 py-2 mb-1 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-tachometer-alt text-gray-400 group-hover:text-blue-600 mr-3"></i>
                        Dashboard
                    </a>

                    <!-- Konsumsi Pangan Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Konsumsi Pangan
                        </h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('ketersediaan.laporan-nbm') ?? '#' }}" 
                               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-chart-bar text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Laporan NBM
                            </a>
                            <a href="{{ route('admin.prediksi-nbm') ?? '#' }}" 
                               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-brain text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Prediksi NBM
                            </a>
                            <a href="{{ route('admin.registrasi-akses') ?? '#' }}" 
                               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md 
                                      {{ request()->routeIs('admin.registrasi-akses') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                <i class="fas fa-user-check {{ request()->routeIs('admin.registrasi-akses') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} mr-3"></i>
                                Registrasi Akses
                            </a>
                        </div>
                    </div>

                    <!-- Other Modules -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Modules
                        </h3>
                        <div class="mt-2 space-y-1">
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-seedling text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Benih & Pupuk
                            </a>
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-map text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Lahan
                            </a>
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-cloud-sun text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Iklim OptDPI
                            </a>
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-address-book text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Daftar Alamat
                            </a>
                        </div>
                    </div>

                    <!-- System -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            System
                        </h3>
                        <div class="mt-2 space-y-1">
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-users text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Users
                            </a>
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-cog text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                Settings
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Page Title -->
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">
                                {{ $title ?? 'Admin Panel' }}
                            </h1>
                            @if(isset($subtitle))
                                <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
                            @endif
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center" x-data="{ userMenuOpen: false }">
                            @auth
                                <div class="relative">
                                    <button @click="userMenuOpen = !userMenuOpen" 
                                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <span class="ml-2 text-gray-700 font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
                                        <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                                    </button>

                                    <div x-show="userMenuOpen" 
                                         @click.away="userMenuOpen = false"
                                         x-transition
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user mr-2"></i>Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') ?? '#' }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts

    @stack('scripts')
</body>
</html>