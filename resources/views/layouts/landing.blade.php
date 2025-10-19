<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and primary nav -->
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                            SIKOLBIA
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-8 md:flex md:space-x-8">
                        <!-- Home -->
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-neutral-700 hover:text-blue-600' }}">
                            Home
                        </a>

                        <!-- Ketersediaan Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-1 pt-1 text-sm font-medium text-neutral-700 hover:text-blue-600 focus:outline-none">
                                Ketersediaan
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('ketersediaan.konsep-metode') }}" 
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Konsep dan Metode
                                    </a>
                                    <a href="{{ route('ketersediaan.laporan-nbm') }}" 
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Laporan Data NBM
                                    </a>
                                    <a href="{{ route('ketersediaan.konsep-transaksi-nbm') }}" 
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Konsep Transaksi NBM
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Konsumsi Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-1 pt-1 text-sm font-medium text-neutral-700 hover:text-blue-600 focus:outline-none">
                                Konsumsi
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('konsumsi.konsep-metode') }}" 
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Konsep dan Metode
                                    </a>
                                    <a href="{{ route('konsumsi.laporan-susenas') }}" 
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Laporan Data Susenas
                                    </a>
                                    <a href="{{ route('konsumsi.per-kapita-seminggu') }}" 
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Konsumsi Per Kapita Seminggu
                                    </a>
                                    <a href="{{ route('konsumsi.per-kapita-setahun') }}" 
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Konsumsi Per Kapita Setahun
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Pertanian Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="inline-flex items-center px-1 pt-1 text-sm font-medium text-neutral-700 hover:text-blue-600 focus:outline-none">
                                Data Non Komoditas Pertanian
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('pertanian.report', ['moduleType' => 'benih-pupuk']) }}"
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Laporan Benih & Pupuk
                                    </a>
                                    <a href="{{ route('pertanian.report', ['moduleType' => 'iklim-opt-dpi']) }}"
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Data Iklim, dan OPT DPI
                                    </a>
                                    <a href="{{ route('pertanian.daftar-alamat') }}"
                                       class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                        Daftar Alamat
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Manajemen Data -->
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium text-neutral-700 hover:text-blue-600">
                            Manajemen Data
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <!-- Home -->
                <a href="{{ route('home') }}" 
                   class="block px-3 py-2 text-base font-medium {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-neutral-700 hover:text-blue-600 hover:bg-neutral-50' }}">
                    Home
                </a>

                <!-- Ketersediaan -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 text-base font-medium text-neutral-700 hover:text-blue-600 hover:bg-neutral-50">
                        Ketersediaan
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-6">
                        <a href="{{ route('ketersediaan.konsep-metode') }}" 
                           class="block px-3 py-2 text-sm text-neutral-600 hover:text-blue-600 hover:bg-neutral-50">
                            Konsep dan Metode
                        </a>
                        <a href="{{ route('ketersediaan.laporan-nbm') }}" 
                           class="block px-3 py-2 text-sm text-neutral-600 hover:text-blue-600 hover:bg-neutral-50">
                            Laporan Data NBM
                        </a>
                        <a href="{{ route('ketersediaan.konsep-transaksi-nbm') }}" 
                           class="block px-3 py-2 text-sm text-neutral-600 hover:text-blue-600 hover:bg-neutral-50">
                            Konsep Transaksi NBM
                        </a>
                    </div>
                </div>

                <!-- Konsumsi -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 text-base font-medium text-neutral-700 hover:text-blue-600 hover:bg-neutral-50">
                        Konsumsi
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-6">
                        <a href="{{ route('konsumsi.konsep-metode') }}" 
                           class="block px-3 py-2 text-sm text-neutral-600 hover:text-blue-600 hover:bg-neutral-50">
                            Konsep dan Metode
                        </a>
                        <a href="{{ route('konsumsi.laporan-susenas') }}" 
                           class="block px-3 py-2 text-sm text-neutral-600 hover:text-blue-600 hover:bg-neutral-50">
                            Laporan Data Susenas
                        </a>
                        <a href="{{ route('konsumsi.per-kapita-seminggu') }}" 
                           class="block px-3 py-2 text-sm text-neutral-600 hover:text-blue-600 hover:bg-neutral-50">
                            Konsumsi Per Kapita Seminggu
                        </a>
                        <a href="{{ route('konsumsi.per-kapita-setahun') }}" 
                           class="block px-3 py-2 text-sm text-neutral-600 hover:text-blue-600 hover:bg-neutral-50">
                            Konsumsi Per Kapita Setahun
                        </a>
                    </div>
                </div>

                <!-- Manajemen Data -->
                <a href="{{ route('login') }}" 
                   class="block px-3 py-2 text-base font-medium text-neutral-700 hover:text-blue-600 hover:bg-neutral-50">
                    Manajemen Data
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-neutral-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">SIKOLBIA</h3>
                    <p class="text-neutral-300">
                        Sistem Informasi Konsumsi + Lahan + Iklim + Benih + Alamat untuk data terintegrasi di Indonesia
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Menu Utama</h3>
                    <ul class="space-y-2 text-neutral-300">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('ketersediaan.konsep-metode') }}" class="hover:text-white">Ketersediaan</a></li>
                        <li><a href="{{ route('konsumsi.konsep-metode') }}" class="hover:text-white">Konsumsi</a></li>
                        <li><a href="{{ route('pertanian.daftar-alamat') }}" class="hover:text-white">Daftar Alamat</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Manajemen Data</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <p class="text-neutral-300">
                        Email: info@konsumsi-pangan.id<br>
                        Telepon: (021) 123-4567
                    </p>
                </div>
            </div>
            <div class="border-t border-neutral-700 mt-8 pt-8 text-center text-neutral-300">
                <p>&copy; {{ date('Y') }} SIKOLBIA. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
