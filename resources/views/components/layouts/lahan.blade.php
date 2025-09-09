@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tX/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen bg-neutral-50 dark:bg-neutral-900">
        <!-- Sidebar -->
        <div class="flex-shrink-0 w-64 bg-white dark:bg-neutral-800 border-r border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 border-b border-neutral-200 dark:border-neutral-700">
                    <a href="/" class="flex items-center space-x-2" wire:navigate>
                        <x-app-logo />
                    </a>
                </div>

                <!-- Navigation -->
                <div class="flex-1 px-3 py-4 overflow-y-auto">
                    <flux:navlist variant="outline">
                        <!-- Main Menu -->
                        <flux:navlist.group :heading="__('Menu Utama')" class="grid">
                            <flux:navlist.item icon="home" :href="route('admin.lahan.dashboard')" :current="request()->routeIs('admin.lahan.dashboard')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.dashboard') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Dashboard') }}</span>
                            </flux:navlist.item>
                        </flux:navlist.group>

                        <!-- Data Lahan -->
                        <flux:navlist.group :heading="__('Data Lahan')" class="grid">
                            <flux:navlist.item icon="database" :href="route('admin.lahan.kelola')" :current="request()->routeIs('admin.lahan.kelola')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.kelola') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Kelola Data Lahan') }}</span>
                            </flux:navlist.item>

                            <flux:navlist.item icon="map" :href="route('admin.lahan.maps')" :current="request()->routeIs('admin.lahan.maps')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.maps') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Peta Lahan') }}</span>
                            </flux:navlist.item>
                            
                            <flux:navlist.item icon="squares-2x2" :href="route('admin.lahan.categories')" :current="request()->routeIs('admin.lahan.categories')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.categories') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Kategori Lahan') }}</span>
                            </flux:navlist.item>

                            <flux:navlist.item icon="rectangle-stack" :href="route('admin.lahan.inventory')" :current="request()->routeIs('admin.lahan.inventory')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.inventory') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Inventaris Lahan') }}</span>
                            </flux:navlist.item>
                        </flux:navlist.group>

                        <!-- Analisis -->
                        <flux:navlist.group :heading="__('Analisis')" class="grid">
                            <flux:navlist.item icon="chart-bar" :href="route('admin.lahan.statistics')" :current="request()->routeIs('admin.lahan.statistics')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.statistics') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Statistik') }}</span>
                            </flux:navlist.item>

                            <flux:navlist.item icon="document-chart-bar" :href="route('admin.lahan.reports')" :current="request()->routeIs('admin.lahan.reports')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.reports') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Laporan') }}</span>
                            </flux:navlist.item>

                            <flux:navlist.item icon="chart-pie" :href="route('admin.lahan.analysis')" :current="request()->routeIs('admin.lahan.analysis')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.analysis') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Analisis Lanjutan') }}</span>
                            </flux:navlist.item>
                        </flux:navlist.group>

                        <!-- Pengaturan -->
                        <flux:navlist.group :heading="__('Pengaturan')" class="grid">
                            <flux:navlist.item icon="cog-6-tooth" :href="route('admin.lahan.settings')" :current="request()->routeIs('admin.lahan.settings')" wire:navigate class="group active-icon">
                                <span class="nav-link-text transition-colors {{ request()->routeIs('admin.lahan.settings') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Pengaturan') }}</span>
                            </flux:navlist.item>
                        </flux:navlist.group>
                    </flux:navlist>
                </div>

                <!-- User Menu -->
                <div class="flex-shrink-0 p-4 border-t border-neutral-200 dark:border-neutral-700">
                    <flux:dropdown position="top" align="start">
                        <flux:button variant="ghost" class="w-full justify-start">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-neutral-200 dark:bg-neutral-600 rounded-full flex items-center justify-center">
                                    <flux:icon.user class="w-4 h-4" />
                                </div>
                                <div class="text-left">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>
                            </div>
                        </flux:button>

                        <flux:menu class="w-48">
                            <flux:menu.item icon="user" href="{{ route('settings.profile') }}" wire:navigate>
                                {{ __('Profile') }}
                            </flux:menu.item>
                            
                            <flux:menu.item icon="cog-6-tooth" href="{{ route('settings.appearance') }}" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>

                            <flux:menu.separator />

                            <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-neutral-50 dark:bg-neutral-900">
                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @fluxScripts
</body>
</html>
