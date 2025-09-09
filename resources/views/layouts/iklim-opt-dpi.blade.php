<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="/" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <!-- Main Menu -->
                <flux:navlist.group :heading="__('Menu Utama')" class="grid">
                    <flux:navlist.item icon="home" :href="route('admin.iklim-opt-dpi.dashboard')" :current="request()->routeIs('admin.iklim-opt-dpi.dashboard')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.dashboard') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Dashboard') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Data Iklim & OPT-DPI -->
                <flux:navlist.group :heading="__('Data Iklim & OPT-DPI')" class="grid">
                    <flux:navlist.item icon="map" :href="route('admin.iklim-opt-dpi.maps')" :current="request()->routeIs('admin.iklim-opt-dpi.maps')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.maps') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Peta Iklim') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cloud" :href="route('admin.iklim-opt-dpi.monitoring')" :current="request()->routeIs('admin.iklim-opt-dpi.monitoring')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.monitoring') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Monitoring Iklim') }}</span>
                    </flux:navlist.item>

                    <flux:navlist.item icon="chart-bar-square" :href="route('admin.iklim-opt-dpi.forecasting')" :current="request()->routeIs('admin.iklim-opt-dpi.forecasting')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.forecasting') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Prediksi Cuaca') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Kelola Data -->
                <flux:navlist.group :heading="__('Kelola Data')" class="grid">
                    <flux:navlist.item icon="database" :href="route('admin.iklim-opt-dpi.kelola')" :current="request()->routeIs('admin.iklim-opt-dpi.kelola')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.kelola') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Data Iklim Opt DPI') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="tag" :href="route('admin.iklim-opt-dpi.topik')" :current="request()->routeIs('admin.iklim-opt-dpi.topik')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.topik') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Topik') }}</span>
                    </flux:navlist.item>

                    <flux:navlist.item icon="variable" :href="route('admin.iklim-opt-dpi.variabel')" :current="request()->routeIs('admin.iklim-opt-dpi.variabel')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.variabel') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Variabel') }}</span>
                    </flux:navlist.item>

                    <flux:navlist.item icon="squares-2x2" :href="route('admin.iklim-opt-dpi.klasifikasi')" :current="request()->routeIs('admin.iklim-opt-dpi.klasifikasi')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.klasifikasi') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Klasifikasi') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Laporan -->
                <flux:navlist.group :heading="__('Laporan')" class="grid">
                    <flux:navlist.item icon="document-chart-bar" :href="route('admin.iklim-opt-dpi.reports')" :current="request()->routeIs('admin.iklim-opt-dpi.reports')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.iklim-opt-dpi.reports') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Laporan Iklim') }}</span>
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

        
        <flux:main>
            @hasSection('header')
                @yield('header')
            @endif
            
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </flux:main>

        @stack('scripts')
        @fluxScripts
    </body>
</html>
