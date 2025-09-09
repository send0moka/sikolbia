<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @unless(request()->routeIs('admin.iklim-opt-dpi.*'))
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="/" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('dashboard') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Dashboard') }}</span>
                    </flux:navlist.item>
                    
                    @can('view users')
                    <flux:navlist.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.users') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Kelola User') }}</span>
                    </flux:navlist.item>
                    @endcan
                </flux:navlist.group>
                <flux:navlist.group :heading="__('Data NBM')" class="grid">
                    @can('view kelompok')
                    <flux:navlist.item icon="tag" :href="route('admin.kelompok')" :current="request()->routeIs('admin.kelompok')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.kelompok') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Kelola Kelompok') }}</span>
                    </flux:navlist.item>
                    @endcan
                    
                    @can('view komoditi')
                    <flux:navlist.item icon="cube" :href="route('admin.komoditi')" :current="request()->routeIs('admin.komoditi')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.komoditi') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Kelola Komoditi') }}</span>
                    </flux:navlist.item>
                    @endcan
                    
                    @can('view transaksi_nbm')
                    <flux:navlist.item icon="chart-bar" :href="route('admin.transaksi-nbm')" :current="request()->routeIs('admin.transaksi-nbm')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.transaksi-nbm') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Kelola Transaksi NBM') }}</span>
                    </flux:navlist.item>
                    @endcan
                </flux:navlist.group>

                <!-- Admin menu for data management -->
                @if(auth()->user() && auth()->user()->isAdmin())
                <flux:navlist.group :heading="__('Data Susenas')" class="grid">
                    <flux:navlist.item icon="rectangle-group" :href="route('admin.kelompok-bps')" :current="request()->routeIs('admin.kelompok-bps')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.kelompok-bps') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Kelompok BPS') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="cube" :href="route('admin.komoditi-bps')" :current="request()->routeIs('admin.komoditi-bps')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.komoditi-bps') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Komoditi BPS') }}</span>
                    </flux:navlist.item>

                    <flux:navlist.item icon="chart-bar" :href="route('admin.susenas')" :current="request()->routeIs('admin.susenas')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.susenas') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Data Susenas') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>
                @endif

                <flux:navlist.group :heading="__('Dokumentasi')" class="grid">
                    <flux:navlist.item icon="document-text" :href="route('admin.konsep-transaksi-nbm')" :current="request()->routeIs('admin.konsep-transaksi-nbm')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.konsep-transaksi-nbm') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Konsep Transaksi NBM') }}</span>
                    </flux:navlist.item>
                    
                    <flux:navlist.item icon="chart-pie" :href="route('admin.konsep-transaksi-susenas')" :current="request()->routeIs('admin.konsep-transaksi-susenas')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.konsep-transaksi-susenas') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Konsep Transaksi Susenas') }}</span>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Machine Learning')" class="grid">
                    <flux:navlist.item icon="cpu-chip" :href="route('admin.prediksi-nbm')" :current="request()->routeIs('admin.prediksi-nbm')" wire:navigate class="group active-icon">
                        <span class="nav-link-text transition-colors {{ request()->routeIs('admin.prediksi-nbm') ? 'text-neutral-900 dark:!text-white' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Prediksi Konsumsi NBM') }}</span>
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
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
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
        @endunless

        <!-- Mobile User Menu -->
        @unless(request()->routeIs('admin.iklim-opt-dpi.*'))
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
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
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
        @endunless

        {{ $slot }}

        @stack('scripts')
        @fluxScripts
    </body>
</html>