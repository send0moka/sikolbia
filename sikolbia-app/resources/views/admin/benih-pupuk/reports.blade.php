@extends('layouts.daftar-alamat')

@section('header')
    <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
        {{ __('Laporan Alamat') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filter Section -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="report-type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Jenis Laporan</label>
                    <select id="report-type" name="report-type" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                        <option value="monthly">Laporan Bulanan</option>
                        <option value="quarterly">Laporan Triwulan</option>
                        <option value="yearly">Laporan Tahunan</option>
                        <option value="custom">Laporan Kustom</option>
                    </select>
                </div>
                <div>
                    <label for="region" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Wilayah</label>
                    <select id="region" name="region" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                        <option value="">Semua Wilayah</option>
                        <!-- Will be populated dynamically -->
                    </select>
                </div>
                <div>
                    <label for="date-range" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Periode</label>
                    <input type="month" id="date-range" name="date-range" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-neutral-200">
                </div>
                <div class="flex items-end">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Generate Laporan
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Reports Section -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">Laporan Terbaru</h3>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Download Semua
                </button>
            </div>
            
            <!-- Reports Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-neutral-50 dark:bg-zinc-800 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Nama Laporan
                            </th>
                            <th class="px-6 py-3 bg-neutral-50 dark:bg-zinc-800 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Periode
                            </th>
                            <th class="px-6 py-3 bg-neutral-50 dark:bg-zinc-800 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Wilayah
                            </th>
                            <th class="px-6 py-3 bg-neutral-50 dark:bg-zinc-800 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Tanggal Dibuat
                            </th>
                            <th class="px-6 py-3 bg-neutral-50 dark:bg-zinc-800 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 bg-neutral-50 dark:bg-zinc-800 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-neutral-200 dark:divide-neutral-700">
                        <!-- Example row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                Laporan Bulanan Agustus 2025
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                Agustus 2025
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                Semua Wilayah
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                20 Agustus 2025
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                    Lihat
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Download
                                </button>
                            </td>
                        </tr>
                        <!-- More rows... -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <nav class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button class="relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-sm font-medium rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-zinc-800 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                            Sebelumnya
                        </button>
                        <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-sm font-medium rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-zinc-800 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                            Selanjutnya
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-neutral-700 dark:text-neutral-300">
                                Menampilkan
                                <span class="font-medium">1</span>
                                sampai
                                <span class="font-medium">10</span>
                                dari
                                <span class="font-medium">20</span>
                                hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-500 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    <span class="sr-only">Sebelumnya</span>
                                    <!-- Heroicon name: solid/chevron-left -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button class="relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    1
                                </button>
                                <button class="relative inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    2
                                </button>
                                <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 text-sm font-medium text-neutral-500 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-zinc-700">
                                    <span class="sr-only">Selanjutnya</span>
                                    <!-- Heroicon name: solid/chevron-right -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
