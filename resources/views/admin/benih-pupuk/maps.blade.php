@extends('layouts.daftar-alamat')

@section('header')
    <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
        {{ __('Peta Daftar Alamat') }}
    </h2>
@stop

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">Peta Persebaran Alamat</h3>
                <div class="flex space-x-3">
                    <div class="relative">
                        <select id="kategori-alamat" class="appearance-none bg-white dark:bg-zinc-800 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Semua Kategori</option>
                            <option value="rumah-tinggal">Rumah Tinggal</option>
                            <option value="kantor">Kantor</option>
                            <option value="toko">Toko</option>
                            <option value="sekolah">Sekolah</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-700 dark:text-neutral-300">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                    <button id="lokasi-saya" class="bg-white dark:bg-zinc-800 hover:bg-neutral-50 dark:hover:bg-zinc-700 text-neutral-700 dark:text-neutral-200 font-medium py-2 px-4 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm text-sm flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Lokasi Saya
                    </button>
                </div>
            </div>
            
            <!-- Map Container -->
            <div id="map" class="w-full h-[600px] rounded-lg border border-neutral-200 dark:border-neutral-700">
                <!-- Map will be initialized here with Leaflet.js -->
                <div class="flex items-center justify-center h-full bg-neutral-50 dark:bg-zinc-800">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">Memuat peta...</h3>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Sedang memuat data alamat dan peta.</p>
                    </div>
                </div>
            </div>

            <!-- Map Legend -->
            <div class="mt-6
                <h4 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-3">Keterangan:</h4>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <span class="w-4 h-4 rounded-full bg-blue-500 mr-2"></span>
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Rumah Tinggal</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-4 h-4 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Kantor</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-4 h-4 rounded-full bg-yellow-500 mr-2"></span>
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Toko</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-4 h-4 rounded-full bg-purple-500 mr-2"></span>
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Sekolah</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-4 h-4 rounded-full bg-neutral-500 mr-2"></span>
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Lainnya</span>
                    </div>
                </div>
            </div>
            
            <!-- Map Controls -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="filter-provinsi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Provinsi</label>
                    <select id="filter-provinsi" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua Provinsi</option>
                        <option value="jawa-barat">Jawa Barat</option>
                        <option value="jawa-tengah">Jawa Tengah</option>
                        <option value="jawa-timur">Jawa Timur</option>
                    </select>
                </div>
                <div>
                    <label for="filter-kota" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kota/Kabupaten</label>
                    <select id="filter-kota" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua Kota/Kabupaten</option>
                    </select>
                </div>
                <div>
                    <label for="filter-kecamatan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Kecamatan</label>
                    <select id="filter-kecamatan" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua Kecamatan</option>
                    </select>
                </div>
                    
                    <div class="w-1/3">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Jenis Data</label>
                        <select class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Semua Jenis</option>
                            <option value="iklim">Data Iklim</option>
                            <option value="opt">Data OPT</option>
                            <option value="dpi">Data DPI</option>
                        </select>
                    </div>
                    
                    <div class="w-1/3">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Tahun</label>
                        <select class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Pilih Tahun</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-neutral-700 mb-2">Legenda</h4>
                    <div class="flex gap-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                            <span class="text-sm text-neutral-600">Lahan Produktif</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                            <span class="text-sm text-neutral-600">Lahan Semi-Produktif</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                            <span class="text-sm text-neutral-600">Lahan Non-Produktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Map initialization and controls will be implemented here
</script>
@endpush
