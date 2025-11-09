@extends('layouts.daftar-alamat')

@section('header')
    <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
        {{ __('Pengaturan Alamat') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Pengaturan Umum -->
        <div class="p-4 sm:p-8 bg-white dark:bg-zinc-900 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
                    {{ __('Pengaturan Umum') }}
                </h3>

                <form class="mt-6 space-y-6">
                    <div>
                        <label for="default-region" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Wilayah Default
                        </label>
                        <select id="default-region" name="default_region" class="mt-1 block w-full rounded-md border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-800 dark:text-neutral-200">
                            <option value="">Pilih Wilayah Default</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>

                    <div>
                        <label for="map-center" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Koordinat Pusat Peta
                        </label>
                        <div class="mt-1 grid grid-cols-2 gap-4">
                            <input type="text" id="latitude" name="latitude" placeholder="Latitude" 
                                class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-800 dark:text-neutral-200">
                            <input type="text" id="longitude" name="longitude" placeholder="Longitude"
                                class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-800 dark:text-neutral-200">
                        </div>
                    </div>

                    <div>
                        <label for="default-zoom" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Level Zoom Default Peta
                        </label>
                        <input type="number" id="default-zoom" name="default_zoom" min="1" max="20" 
                            class="mt-1 block w-full rounded-md border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-800 dark:text-neutral-200">
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Simpan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pengaturan Notifikasi -->
        <div class="p-4 sm:p-8 bg-white dark:bg-zinc-900 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
                    {{ __('Pengaturan Notifikasi') }}
                </h3>

                <form class="mt-6 space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="email-notifications" name="email_notifications" type="checkbox" 
                                    class="h-4 w-4 rounded border-neutral-300 dark:border-neutral-600 text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-800">
                            </div>
                            <div class="ml-3">
                                <label for="email-notifications" class="font-medium text-neutral-700 dark:text-neutral-300">
                                    Notifikasi Email
                                </label>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                    Terima notifikasi email untuk perubahan status lahan
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="report-notifications" name="report_notifications" type="checkbox" 
                                    class="h-4 w-4 rounded border-neutral-300 dark:border-neutral-600 text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-800">
                            </div>
                            <div class="ml-3">
                                <label for="report-notifications" class="font-medium text-neutral-700 dark:text-neutral-300">
                                    Notifikasi Laporan
                                </label>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                    Terima notifikasi saat laporan baru tersedia
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Simpan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pengaturan Data -->
        <div class="p-4 sm:p-8 bg-white dark:bg-zinc-900 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
                    {{ __('Pengaturan Data') }}
                </h3>

                <form class="mt-6 space-y-6">
                    <div>
                        <label for="data-retention" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Periode Retensi Data
                        </label>
                        <select id="data-retention" name="data_retention" class="mt-1 block w-full rounded-md border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-800 dark:text-neutral-200">
                            <option value="1">1 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">1 Tahun</option>
                            <option value="0">Selamanya</option>
                        </select>
                    </div>

                    <div>
                        <label for="auto-backup" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Jadwal Backup Otomatis
                        </label>
                        <select id="auto-backup" name="auto_backup" class="mt-1 block w-full rounded-md border-neutral-300 dark:border-neutral-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-800 dark:text-neutral-200">
                            <option value="daily">Setiap Hari</option>
                            <option value="weekly">Setiap Minggu</option>
                            <option value="monthly">Setiap Bulan</option>
                            <option value="never">Tidak Pernah</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Simpan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
