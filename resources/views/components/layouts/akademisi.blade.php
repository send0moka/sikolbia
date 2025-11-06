@props(['title' => 'Panel Akademisi - ' . config('app.name')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head', ['title' => $title])
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('akademisi.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-akademisi-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('akademisi.dashboard')" :current="request()->routeIs('akademisi.dashboard')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('akademisi.dashboard') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Dashboard') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Konsumsi Pangan')" class="grid">
                    <flux:navlist.item icon="chart-bar" :href="route('ketersediaan.laporan-nbm')" :current="request()->routeIs('ketersediaan.laporan-nbm')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('ketersediaan.laporan-nbm') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Laporan NBM') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cpu-chip" href="#" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">{{ __('Prediksi NBM') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Pertanian')" class="grid">
                    <flux:navlist.item icon="map" href="#" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">{{ __('Data Lahan') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cube" href="#" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">{{ __('Benih & Pupuk') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cloud" href="#" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">{{ __('Iklim OptDPI') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Referensi')" class="grid">
                    <flux:navlist.item icon="map-pin" href="#" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">{{ __('Data Alamat') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Dokumentasi')" class="grid">
                    <flux:navlist.item icon="book-open" :href="route('ketersediaan.konsep-metode')" :current="request()->routeIs('ketersediaan.konsep-metode')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('ketersediaan.konsep-metode') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Konsep & Metode') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="information-circle" href="#" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">{{ __('Panduan Sitasi') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
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
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-purple-500 text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs text-purple-600 dark:text-purple-400">Akademisi</span>
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
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-purple-500 text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs text-purple-600 dark:text-purple-400">Akademisi</span>
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

        <flux:main>
            {{ $slot }}
        </flux:main>

        @stack('scripts')
        @fluxScripts
    </body>
</html>
