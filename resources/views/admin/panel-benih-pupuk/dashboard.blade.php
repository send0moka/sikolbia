@extends('layouts.benih-pupuk')

@section('header')
    <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
        {{ __('Dashboard Benih & Pupuk') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Dashboard Benih & Pupuk</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Selamat datang di sistem manajemen benih dan pupuk terpadu
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Benih -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 dark:bg-green-900 rounded-lg p-3 mr-4">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">Total Jenis Benih</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">1,234</p>
                    </div>
                </div>
            </div>

            <!-- Total Pupuk -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-3 mr-4">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">Total Jenis Pupuk</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">567</p>
                    </div>
                </div>
            </div>

            <!-- Stok Tersedia -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 dark:bg-yellow-900 rounded-lg p-3 mr-4">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">Stok Tersedia</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">12,345 kg</p>
                    </div>
                </div>
            </div>

            <!-- Distribusi Bulan Ini -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 dark:bg-purple-900 rounded-lg p-3 mr-4">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">Distribusi Bulan Ini</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-white">3,456 kg</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6 mb-8">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                <!-- Activity Item -->
                <div class="flex items-start">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2 mr-3">
                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Penambahan stok benih baru</p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">2 jam yang lalu</p>
                    </div>
                </div>
                <!-- More activity items... -->
            </div>
        </div>
    </div>
</div>
@endsection
