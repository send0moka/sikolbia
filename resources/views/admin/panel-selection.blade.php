<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Basis Data Konsumsi Pangan') }} - Pilih Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @fluxStyles --}}
</head>
<body class="bg-gradient-to-br from-neutral-900 via-neutral-800 to-black dark:from-neutral-900 dark:via-neutral-800 dark:to-black min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-6xl">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="flex items-center justify-between mb-8">
                    <a href="/">
                        <flux:button variant="ghost" class="text-white hover:text-red-500 hover:bg-neutral-800 border border-neutral-600">
                            ← Kembali
                        </flux:button>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <flux:button type="submit" variant="ghost" class="text-white hover:text-red-500 hover:bg-neutral-800 border border-neutral-600">
                            Keluar
                        </flux:button>
                    </form>
                </div>
                <h1 class="text-white text-4xl font-light mb-4">Pilih Panel Admin</h1>
                <p class="text-neutral-400 text-lg">
            </div>

            <!-- Panel Grid -->
            <div class="container mx-auto flex gap-6 items-center justify-center flex-wrap">
                @php
                    $panelItems = [
                        [
                            'url' => '/admin/konsumsi-pangan',
                            'img' => 'konsumsi-pangan.jpg',
                            'emoji' => '🍽️',
                            'title' => 'Konsumsi Pangan',
                            'desc' => 'Data & Analisis',
                            'bg' => 'from-blue-600 to-blue-800',
                        ],
                        [
                            'url' => '/admin/lahan',
                            'img' => 'lahan.jpg',
                            'emoji' => '🌾',
                            'title' => 'Lahan',
                            'desc' => 'Data & Analisis',
                            'bg' => 'from-green-600 to-green-800',
                        ],
                        [
                            'url' => route('admin.iklim-opt-dpi.dashboard'),
                            'img' => 'iklim-opt-dpi.jpg',
                            'emoji' => '📈',
                            'title' => 'Iklim OPT-DPI',
                            'desc' => 'Monitoring dan Peramalan',
                            'bg' => 'from-purple-600 to-purple-800',
                        ],
                        [
                            'url' => route('admin.daftar-alamat.dashboard'),
                            'img' => 'daftar-alamat.jpg',
                            'emoji' => '🔍',
                            'title' => 'Daftar Alamat',
                            'desc' => 'Daftar Alamat',
                            'bg' => 'from-orange-600 to-orange-800',
                        ],
                        [
                            'url' => route('admin.benih-pupuk.dashboard'),
                            'img' => 'benih-pupuk.jpg',
                            'emoji' => '⚙️',
                            'title' => 'Benih Pupuk',
                            'desc' => 'Benih Pupuk',
                            'bg' => 'from-red-600 to-red-800',
                        ],
                    ];
                @endphp

                @foreach($panelItems as $item)
                <div class="group cursor-pointer w-40 h-40 flex-shrink-0" onclick="window.location.href='{{ $item['url'] }}'">
                    <div class="relative rounded-lg overflow-hidden transition-all w-full h-full">
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br {{ $item['bg'] }} p-6 text-white">
                            <div class="text-4xl mb-4">{{ $item['emoji'] }}</div>
                            <div class="text-center">
                                <h3 class="font-semibold text-lg mb-1">{{ $item['title'] }}</h3>
                                <p class="text-sm opacity-80">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-20 transition-all">
                            <img src="{{ asset($item['img']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <p class="text-center text-neutral-400 mt-3 group-hover:text-white transition-colors">{{ $item['title'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- User Info -->
            <div class="text-center mt-12">
                <div class="inline-flex items-center space-x-3 text-neutral-400">
                    <div class="w-8 h-8 bg-neutral-600 rounded-full flex items-center justify-center">
                        <span class="text-sm font-semibold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="text-left">
                        <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-neutral-400">
                            {{ auth()->user()->roles->first()?->name ?? 'No Role' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Netflix-like hover effects -->
    <style>
        @media (min-width: 768px) {
            .group:hover {
                transform: scale(1.1);
                transition: all 0.3s ease;
            }
            
            .group:hover::after {
                content: '';
                position: absolute;
                top: -10px;
                left: -10px;
                right: -10px;
                bottom: -10px;
                background: rgba(0, 0, 0, 0.3);
                border-radius: 12px;
                z-index: -1;
            }
        }
    </style>

    @fluxScripts
</body>
</html>