<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="flex h-screen overflow-hidden">
            <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-64 flex-shrink-0">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="/" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <!-- Menu Utama -->
                <flux:navlist.group :heading="__('Menu Utama')" class="grid">
                    <flux:navlist.item icon="home" :href="route('admin.daftar-alamat.dashboard')" :current="request()->routeIs('admin.daftar-alamat.dashboard')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.daftar-alamat.dashboard') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Dashboard') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Data Alamat -->
                <flux:navlist.group :heading="__('Data Alamat')" class="grid">
                    <flux:navlist.item icon="map-pin" :href="route('admin.daftar-alamat.data')" :current="request()->routeIs('admin.daftar-alamat.data')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.daftar-alamat.data') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Daftar Alamat') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="map" :href="route('admin.daftar-alamat.maps')" :current="request()->routeIs('admin.daftar-alamat.maps')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.daftar-alamat.maps') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Peta Alamat') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Laporan -->
                <flux:navlist.group :heading="__('Laporan')" class="grid">
                    <flux:navlist.item icon="document-chart-bar" :href="route('admin.daftar-alamat.reports')" :current="request()->routeIs('admin.daftar-alamat.reports')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.daftar-alamat.reports') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Laporan Alamat') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Pengaturan -->
                <flux:navlist.group :heading="__('Pengaturan')" class="grid">
                    <flux:navlist.item icon="cog" :href="route('admin.daftar-alamat.settings')" :current="request()->routeIs('admin.daftar-alamat.settings')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.daftar-alamat.settings') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Pengaturan Alamat') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Page Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('header')
                    @yield('content')
                </div>
            </main>
        </div>
        </div>

        @stack('scripts')
        @fluxScripts
        @livewireScripts
    </body>
</html>
