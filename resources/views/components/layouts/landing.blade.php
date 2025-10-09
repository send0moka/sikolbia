<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SIKOLBIA' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --color-primary: #2f8b3e;
            --color-secondary: #6a4c35;
            --color-accent: #efefa4;
            --color-danger: #e34e38;
            --color-purple: #782c7c;
        }
    </style>
</head>
<body class="font-['Figtree'] antialiased bg-neutral-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50 border-b border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo (allow shrink; hide long title below lg to avoid overflow) -->
                        <div class="flex items-center min-w-0">
                            <img src="/favicon.svg" alt="Logo" class="h-8 w-8 mr-3">
                            <!-- Always show full title; wrap and scale text so it fits on small screens -->
                            <a href="{{ route('home') }}" class="block text-xl max-[1024px]:text-lg max-[768px]:text-base max-[420px]:text-sm font-semibold text-[#6a4c35] hover:text-[#782c7c] transition-colors leading-tight max-w-[55vw] md:max-w-[40vw] lg:max-w-none">
                                SIKOLBIA
                            </a>
                        </div>

                        <!-- Navigation Links (show from md and up to avoid overflow on small widths/zoom) -->
                        <div class="hidden md:ml-8 md:flex md:space-x-8 md:items-center">
                            <!-- Home -->
                            <a href="{{ route('home') }}" 
                               class="border-transparent text-neutral-500 hover:border-[#2f8b3e] hover:text-[#2f8b3e] inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors h-16">
                                Home
                            </a>

                            <!-- Ketersediaan Dropdown -->
                            <div class="relative h-16 flex items-center" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="border-transparent text-neutral-500 hover:border-[#2f8b3e] hover:text-[#2f8b3e] inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors h-16">
                                    Ketersediaan
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-transition
                                     @click.outside="open = false"
                                     class="absolute left-0 top-full mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1">
                                        <a href="{{ route('ketersediaan.konsep-metode') }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Konsep dan Metode
                                        </a>
                                        <a href="{{ route('ketersediaan.laporan-nbm') }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Laporan Data NBM
                                        </a>
                                        <a href="{{ route('ketersediaan.dashboard-komoditas') }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Dashboard Komoditas
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Konsumsi Dropdown -->
                            <div class="relative h-16 flex items-center" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="border-transparent text-neutral-500 hover:border-[#2f8b3e] hover:text-[#2f8b3e] inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors h-16">
                                    Konsumsi
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-transition
                                     @click.outside="open = false"
                                     class="absolute left-0 top-full mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1">
                                        <a href="{{ route('konsumsi.konsep-metode') }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Konsep dan Metode
                                        </a>
                                        <a href="{{ route('konsumsi.laporan-susenas') }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Laporan Data Susenas
                                        </a>
                                        <a href="{{ route('konsumsi.per-kapita-seminggu') }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Konsumsi Per Kapita Seminggu
                                        </a>
                                        <a href="{{ route('konsumsi.per-kapita-setahun') }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Konsumsi Per Kapita Setahun
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Pertanian Dropdown -->
                            <div class="relative h-16 flex items-center" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="border-transparent text-neutral-500 hover:border-[#2f8b3e] hover:text-[#2f8b3e] inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors h-16">
                                    Pertanian
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-transition
                                     @click.outside="open = false"
                                     class="absolute left-0 top-full mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1">
                                        <a href="{{ route('pertanian.report', ['moduleType' => 'benih-pupuk']) }}" 
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Data Benih dan Pupuk
                                        </a>
                                        <a href="{{ route('pertanian.report', ['moduleType' => 'iklim-opt-dpi']) }}"
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Data Iklim, dan OPT DPI
                                        </a>
                                        <a href="{{ route('pertanian.report', ['moduleType' => 'lahan']) }}"
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Data Lahan
                                        </a>
                                        <a href="{{ route('pertanian.daftar-alamat') }}"
                                           class="block px-4 py-2 text-sm text-neutral-700 hover:bg-[#efefa4]/20 hover:text-[#6a4c35] font-medium transition-colors">
                                            Daftar Alamat
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Manajemen Data -->
                            <a href="{{ route('login') }}" 
                               class="border-transparent text-neutral-500 hover:border-[#2f8b3e] hover:text-[#2f8b3e] inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors h-16">
                                Manajemen Data
                            </a>
                        </div>
                    </div>

                    <!-- Mobile menu button (visible below md) -->
                    <div class="md:hidden flex items-center">
                        <button x-data x-on:click="$dispatch('toggle-mobile-menu')" 
                                class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-[#2f8b3e] hover:bg-[#efefa4]/20 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

          <!-- Mobile menu (visible below md) -->
            <div x-data="{ open: false }" 
                 x-on:toggle-mobile-menu.window="open = !open"
                 x-show="open" 
                 x-transition
              class="md:hidden bg-white border-t border-neutral-200">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-neutral-500 hover:text-[#2f8b3e] hover:bg-[#efefa4]/20 hover:border-[#2f8b3e] transition-colors">
                        Home
                    </a>
                    
                    <!-- Mobile Ketersediaan -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-neutral-500 hover:text-[#2f8b3e] hover:bg-[#efefa4]/20 flex justify-between items-center transition-colors">
                            Ketersediaan
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-6 space-y-1 bg-[#efefa4]/10">
                            <a href="{{ route('ketersediaan.konsep-metode') }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Konsep dan Metode
                            </a>
                            <a href="{{ route('ketersediaan.laporan-nbm') }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Laporan Data NBM
                            </a>
                            <a href="{{ route('ketersediaan.dashboard-komoditas') }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Dashboard Komoditas
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Konsumsi -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-neutral-500 hover:text-[#2f8b3e] hover:bg-[#efefa4]/20 flex justify-between items-center transition-colors">
                            Konsumsi
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-6 space-y-1 bg-[#efefa4]/10">
                            <a href="{{ route('konsumsi.konsep-metode') }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Konsep dan Metode
                            </a>
                            <a href="{{ route('konsumsi.laporan-susenas') }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Laporan Data Susenas
                            </a>
                            <a href="{{ route('konsumsi.per-kapita-seminggu') }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Per Kapita Seminggu
                            </a>
                            <a href="{{ route('konsumsi.per-kapita-setahun') }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Per Kapita Setahun
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Pertanian -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-neutral-500 hover:text-[#2f8b3e] hover:bg-[#efefa4]/20 flex justify-between items-center transition-colors">
                            Pertanian
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-6 space-y-1 bg-[#efefa4]/10">
                            <a href="{{ route('pertanian.report', ['moduleType' => 'benih-pupuk']) }}" 
                               class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Data Benih dan Pupuk
                            </a>
                            <a href="{{ route('pertanian.report', ['moduleType' => 'iklim-opt-dpi']) }}"
                                 class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Data Iklim, dan OPT DPI
                            </a>
                            <a href="{{ route('pertanian.report', ['moduleType' => 'lahan']) }}"
                                 class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Data Lahan
                            </a>
                            <a href="{{ route('pertanian.daftar-alamat') }}"
                                 class="block py-2 text-sm text-neutral-600 hover:text-[#6a4c35] font-medium transition-colors">
                                Daftar Alamat
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('login') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-neutral-500 hover:text-[#2f8b3e] hover:bg-[#efefa4]/20 hover:border-[#2f8b3e] transition-colors">
                        Manajemen Data
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gradient-to-r from-[#6a4c35] to-[#782c7c] text-white mt-auto">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Logo & Contact Info -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center mb-6">
                            <img src="/favicon.svg" alt="Logo" class="h-10 w-10 mr-3">
                            <div>
                                <h3 class="text-xl font-semibold text-[#efefa4]">SIKOLBIA</h3>
                                <p class="text-sm text-white/80">Sistem Informasi Konsumsi + Lahan + Iklim + Benih + Alamat</p>
                            </div>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 text-[#efefa4] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span class="text-white/90">Jl. Harsono RM. No. 3, Ragunan-Jakarta 12550, Indonesia</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-[#efefa4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-white/90">021-7806131, 021-7804116</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-[#efefa4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4L5.5 6M17 4l1.5 2M3 6h18l-2 13H5L3 6z"></path>
                                </svg>
                                <span class="text-white/90">Fax: 021-7806305</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-[#efefa4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:pusdatin@pertanian.go.id" class="text-[#efefa4] hover:text-white transition-colors">
                                    pusdatin@pertanian.go.id
                                </a>
                            </div>
                            <div class="bg-[#2f8b3e] bg-opacity-30 p-3 rounded-lg border border-[#2f8b3e]/30">
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 mr-2 text-[#efefa4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-[#efefa4]">PPID</span>
                                </div>
                                <p class="text-xs text-white/80">Telp: 0821-1089-7194</p>
                                <p class="text-xs text-white/70">(Jam 09.00 s.d 15.00 WIB setiap hari kerja)</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ketersediaan Section -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-[#efefa4] border-b border-[#efefa4]/30 pb-2">Ketersediaan</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="{{ route('ketersediaan.konsep-metode') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Konsep dan Metode
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ketersediaan.laporan-nbm') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Laporan Data NBM
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ketersediaan.dashboard-komoditas') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Dashboard Komoditas
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Konsumsi Section -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-[#efefa4] border-b border-[#efefa4]/30 pb-2">Konsumsi</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="{{ route('konsumsi.konsep-metode') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Konsep dan Metode
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konsumsi.laporan-susenas') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Laporan Data Susenas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konsumsi.per-kapita-seminggu') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Per Kapita Seminggu
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konsumsi.per-kapita-setahun') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Per Kapita Setahun
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Pertanian Section -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-[#efefa4] border-b border-[#efefa4]/30 pb-2">Pertanian</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="{{ route('pertanian.report', ['moduleType' => 'benih-pupuk']) }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Data Benih dan Pupuk
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pertanian.report', ['moduleType' => 'iklim-opt-dpi']) }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Data Iklim, dan OPT DPI
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pertanian.report', ['moduleType' => 'lahan']) }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Data Lahan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pertanian.daftar-alamat') }}" 
                                   class="text-white/80 hover:text-[#efefa4] transition-colors duration-200 flex items-center group">
                                    <svg class="w-3 h-3 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Daftar Alamat
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Footer Bottom -->
                <div class="mt-8 pt-8 border-t border-white/20">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-sm text-white/70 mb-4 md:mb-0">
                            © {{ date('Y') }} Pusat Data dan Sistem Informasi Pertanian. All rights reserved.
                        </p>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" 
                               class="bg-[#2f8b3e] hover:bg-[#2f8b3e]/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Manajemen Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
