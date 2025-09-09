<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Basis Data Konsumsi Pangan') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxStyles
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-neutral-50 dark:bg-neutral-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                                Admin Panel
                            </h1>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <flux:link href="{{ route('dashboard') }}" 
                                class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-300 dark:hover:text-neutral-100 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Dashboard
                            </flux:link>
                            @can('view users')
                            <flux:link href="{{ route('admin.users') }}" 
                                class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-300 dark:hover:text-neutral-100 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Kelola User
                            </flux:link>
                            @endcan
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-neutral-700 dark:text-neutral-300">
                            {{ auth()->user()->name }}
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-2">
                                {{ auth()->user()->roles->first()?->name ?? 'No Role' }}
                            </span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:button type="submit" variant="ghost" size="sm">
                                Logout
                            </flux:button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @fluxScripts
</body>
</html>
