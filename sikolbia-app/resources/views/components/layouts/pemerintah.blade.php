@props(['title' => 'Panel Pemerintah - ' . config('app.name')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head', ['title' => $title])
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('pemerintah.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-pemerintah-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Dashboard')" class="grid">
                    <flux:navlist.item icon="home" :href="route('pemerintah.dashboard')" :current="request()->routeIs('pemerintah.dashboard')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('pemerintah.dashboard') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Dashboard') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Konsumsi Pangan')" class="grid">
                    <flux:navlist.item icon="chart-bar" :href="route('pemerintah.laporan-nbm')" :current="request()->routeIs('pemerintah.laporan-nbm')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('pemerintah.laporan-nbm') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Laporan NBM') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cpu-chip" :href="route('pemerintah.prediksi-nbm')" :current="request()->routeIs('pemerintah.prediksi-nbm')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('pemerintah.prediksi-nbm') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Prediksi NBM') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Pertanian')" class="grid">
                    <flux:navlist.item icon="map" :href="route('pemerintah.lahan')" :current="request()->routeIs('pemerintah.lahan')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('pemerintah.lahan') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Data Lahan') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cube" :href="route('pemerintah.benih-pupuk')" :current="request()->routeIs('pemerintah.benih-pupuk')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('pemerintah.benih-pupuk') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Benih & Pupuk') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cloud" :href="route('pemerintah.iklim')" :current="request()->routeIs('pemerintah.iklim')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('pemerintah.iklim') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Iklim OptDPI') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Informasi')" class="grid">
                    <flux:navlist.item icon="information-circle" :href="route('pemerintah.panduan')" :current="request()->routeIs('pemerintah.panduan')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('pemerintah.panduan') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Panduan Penggunaan') }}</span>
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
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-blue-500 text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs text-blue-600 dark:text-blue-400">Pemerintah</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('pemerintah.profile')" icon="user" wire:navigate>{{ __('Profile') }}</flux:menu.item>
                        <flux:menu.item :href="route('pemerintah.settings')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
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
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-blue-500 text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs text-blue-600 dark:text-blue-400">Pemerintah</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('pemerintah.profile')" icon="user" wire:navigate>{{ __('Profile') }}</flux:menu.item>
                        <flux:menu.item :href="route('pemerintah.settings')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
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
