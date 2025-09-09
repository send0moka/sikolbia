<div>
    <!-- Leaflet.js for interactive maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        #location-map {
            height: 320px;
            width: 100%;
            border-radius: 8px;
            position: relative;
            z-index: 10;
            background-color: #f8f9fa;
        }
        
        /* Force proper dimensions */
        #location-map .leaflet-container {
            height: 320px !important;
            width: 100% !important;
        }

        /* Custom popup styling */
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .custom-popup .leaflet-popup-content {
            margin: 0;
            border-radius: 12px;
        }

        .custom-popup .leaflet-popup-tip {
            background-color: white;
        }

        /* Ensure map container is visible */
        .leaflet-container {
            background: #f8f9fa !important;
        }

        /* Ensure popup appears above map */
        .leaflet-popup-pane {
            z-index: 20;
        }

        /* Ensure all Leaflet controls are properly layered */
        .leaflet-control-container {
            z-index: 15;
        }

        .leaflet-tile-pane {
            z-index: 5;
        }

        .leaflet-overlay-pane {
            z-index: 10;
        }

        .leaflet-shadow-pane {
            z-index: 12;
        }

        .leaflet-marker-pane {
            z-index: 13;
        }
    </style>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Data Daftar Alamat</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data alamat dinas pertanian seluruh Indonesia
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button wire:click="create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Tambah Alamat
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div
        class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" placeholder="Cari alamat..."
                        class="w-full pl-10 pr-4 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent" />
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z">
                        </path>
                    </svg>
                </div>
            </div>
            <div>
                <select wire:model.live="statusFilter"
                    class="w-full p-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                    <select wire:model.live.url="provinsiFilter"
                        class="w-full p-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent mb-2" wire:key="provinsi-filter">
                        <option value="">Semua Provinsi</option>
                        @foreach ($provinsiOptions as $provinsi)
                            <option value="{{ $provinsi }}">{{ $provinsi }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live.url="kabupatenKotaFilter"
                        class="w-full p-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent" wire:key="kabupatenkota-filter">
                        <option value="">Semua Kabupaten/Kota</option>
                        @foreach ($kabupatenKotaOptions as $kabupatenKota)
                            <option value="{{ $kabupatenKota }}">{{ $kabupatenKota }}</option>
                        @endforeach
                    </select>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-center">
            <button wire:click="resetFilters"
                class="px-3 py-1.5 text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors">
                Reset Filter
            </button>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-neutral-600 dark:text-neutral-400">Per halaman:</span>
                <select wire:model.live="perPage"
                    class="text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div
        class="bg-white dark:!bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors"
                            wire:click="sortByField('id')">
                            <div class="flex items-center space-x-1">
                                <span>ID</span>
                                @if ($sortBy === 'id')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors"
                            wire:click="sortByField('provinsi')">
                            <div class="flex items-center space-x-1">
                                <span>Provinsi</span>
                                @if ($sortBy === 'provinsi')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors"
                            wire:click="sortByField('kabupaten_kota')">
                            <div class="flex items-center space-x-1">
                                <span>Kabupaten/Kota</span>
                                @if ($sortBy === 'kabupaten_kota')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            Gambar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors"
                            wire:click="sortByField('nama_dinas')">
                            <div class="flex items-center space-x-1">
                                <span>Nama Dinas</span>
                                @if ($sortBy === 'nama_dinas')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors"
                            wire:click="sortByField('email')">
                            <div class="flex items-center space-x-1">
                                <span>Kontak (email)</span>
                                @if ($sortBy === 'email')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors"
                            wire:click="sortByField('status')">
                            <div class="flex items-center space-x-1">
                                <span>Status</span>
                                @if ($sortBy === 'status')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors"
                            wire:click="sortByField('latitude')">
                            <div class="flex items-center space-x-1">
                                <span>Koordinat</span>
                                @if ($sortBy === 'latitude')
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:!bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($alamats as $alamat)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                                {{ $alamat->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                                {{ $alamat->provinsi }}
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">
                                <div class="max-w-xs truncate">{{ $alamat->kabupaten_kota }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($alamat->gambar)
                                    <img src="{{ $alamat->gambar_url }}" alt="Gambar {{ $alamat->nama_dinas }}"
                                        class="w-12 h-12 object-cover rounded-lg shadow-sm">
                                @else
                                    <div
                                        class="w-12 h-12 bg-neutral-100 dark:bg-neutral-700 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">
                                <div class="max-w-xs">
                                    <div class="font-medium truncate">{{ $alamat->nama_dinas }}</div>
                                    <div class="text-neutral-500 dark:text-neutral-400 truncate">
                                        {{ $alamat->alamat }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">
                                <div class="space-y-1">
                                    @if ($alamat->telp)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span class="truncate">{{ $alamat->telp }}</span>
                                        </div>
                                    @endif
                                    @if ($alamat->email)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span class="truncate">{{ $alamat->email }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $alamat->status_badge }}">
                                    {{ $alamat->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                @if ($alamat->has_coordinates)
                                    <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Ada
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-neutral-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tidak Ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="edit({{ $alamat->id }})"
                                        class="inline-flex items-center px-2 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $alamat->id }})"
                                        wire:confirm="Yakin ingin menghapus data ini?"
                                        class="inline-flex items-center px-2 py-1 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-4 text-neutral-300 dark:text-neutral-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data ditemukan</p>
                                    <p class="text-sm">Coba ubah filter atau tambah data baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($alamats->hasPages())
            <div class="px-6 py-3 border-t border-neutral-200 dark:border-neutral-700">
                {{ $alamats->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);"
                aria-hidden="true" wire:click="$set('showModal', false)"></div>

            <!-- Modal container -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="relative inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full z-10">
                    <form id="alamat-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="modal-mode" value="{{ $modalMode }}">
                        <input type="hidden" id="selected-id" value="{{ $selectedId }}">
                        <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-neutral-900 dark:text-white" id="modal-title">
                                    {{ $modalMode === 'create' ? 'Tambah Alamat Baru' : 'Edit Alamat' }}
                                </h3>
                                <button type="button" wire:click="$set('showModal', false)"
                                    class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Provinsi
                                            *</label>
                                        <div class="relative">
                                            <select wire:model.live="provinsi" wire:change="validateProvinsi"
                                                name="provinsi"
                                                class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent {{ $provinsiValidationError ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}">
                                                <option value="">Pilih Provinsi</option>
                                                <option value="Provinsi Aceh">Provinsi Aceh</option>
                                                <option value="Provinsi Sumatera Utara">Provinsi Sumatera Utara</option>
                                                <option value="Provinsi Sumatera Barat">Provinsi Sumatera Barat</option>
                                                <option value="Provinsi Riau">Provinsi Riau</option>
                                                <option value="Provinsi Kepulauan Riau">Provinsi Kepulauan Riau</option>
                                                <option value="Provinsi Jambi">Provinsi Jambi</option>
                                                <option value="Provinsi Sumatera Selatan">Provinsi Sumatera Selatan</option>
                                                <option value="Provinsi Bengkulu">Provinsi Bengkulu</option>
                                                <option value="Provinsi Lampung">Provinsi Lampung</option>
                                                <option value="Provinsi Kepulauan Bangka Belitung">Provinsi Kepulauan Bangka Belitung
                                                </option>
                                                <option value="Provinsi Daerah Khusus Jakarta">Provinsi Daerah Khusus Jakarta
                                                </option>
                                                <option value="Provinsi Jawa Barat">Provinsi Jawa Barat</option>
                                                <option value="Provinsi Jawa Tengah">Provinsi Jawa Tengah</option>
                                                <option value="Provinsi Daerah Istimewa Yogyakarta">Provinsi Daerah Istimewa
                                                    Yogyakarta</option>
                                                <option value="Provinsi Jawa Timur">Provinsi Jawa Timur</option>
                                                <option value="Provinsi Banten">Provinsi Banten</option>
                                                <option value="Provinsi Bali">Provinsi Bali</option>
                                                <option value="Provinsi Nusa Tenggara Barat">Provinsi Nusa Tenggara Barat</option>
                                                <option value="Provinsi Nusa Tenggara Timur">Provinsi Nusa Tenggara Timur</option>
                                                <option value="Provinsi Kalimantan Barat">Provinsi Kalimantan Barat</option>
                                                <option value="Provinsi Kalimantan Tengah">Provinsi Kalimantan Tengah</option>
                                                <option value="Provinsi Kalimantan Selatan">Provinsi Kalimantan Selatan</option>
                                                <option value="Provinsi Kalimantan Timur">Provinsi Kalimantan Timur</option>
                                                <option value="Provinsi Kalimantan Utara">Provinsi Kalimantan Utara</option>
                                                <option value="Provinsi Sulawesi Utara">Provinsi Sulawesi Utara</option>
                                                <option value="Provinsi Sulawesi Tengah">Provinsi Sulawesi Tengah</option>
                                                <option value="Provinsi Sulawesi Selatan">Provinsi Sulawesi Selatan</option>
                                                <option value="Provinsi Sulawesi Tenggara">Provinsi Sulawesi Tenggara</option>
                                                <option value="Provinsi Gorontalo">Provinsi Gorontalo</option>
                                                <option value="Provinsi Sulawesi Barat">Provinsi Sulawesi Barat</option>
                                                <option value="Provinsi Maluku">Provinsi Maluku</option>
                                                <option value="Provinsi Maluku Utara">Provinsi Maluku Utara</option>
                                                <option value="Provinsi Papua Barat">Provinsi Papua Barat</option>
                                                <option value="Provinsi Papua Barat Daya">Provinsi Papua Barat Daya</option>
                                                <option value="Provinsi Papua Selatan">Provinsi Papua Selatan</option>
                                                <option value="Provinsi Papua Tengah">Provinsi Papua Tengah</option>
                                                <option value="Provinsi Papua">Provinsi Papua</option>
                                                <option value="Provinsi Papua Pegunungan">Provinsi Papua Pegunungan</option>
                                            </select>
                                            @if ($provinsiValidationError)
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-red-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        @error('provinsi')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                        @if ($provinsiValidationError)
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $provinsiValidationError }}</span>
                                        @endif
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                            Kabupaten/Kota *
                                            <span wire:loading wire:target="provinsi"
                                                class="text-accent font-normal text-xs ml-1">(Memproses...)</span>
                                        </label>
                                        <div class="relative">
                                            <select wire:model.live="kabupaten_kota"
                                                wire:change="validateKabupatenKota" name="kabupaten_kota"
                                                class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent {{ !$provinsi ? 'opacity-50 cursor-not-allowed' : '' }} {{ $kabupatenValidationError ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                wire:target="provinsi" {{ !$provinsi ? 'disabled' : '' }}
                                                wire:loading.attr="disabled" wire:target="provinsi">

                                                <option value="" wire:loading.remove wire:target="provinsi">
                                                    {{ $provinsi ? 'Pilih Kabupaten/Kota' : 'Pilih provinsi terlebih dahulu' }}
                                                </option>
                                                <option value="" wire:loading wire:target="provinsi">Memuat
                                                    data kabupaten/kota...</option>

                                                @if ($provinsi)
                                                    @foreach ($this->kabupatenOptions as $kabupaten)
                                                        <option value="{{ $kabupaten }}">{{ $kabupaten }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <!-- Loading Spinner when province is changing -->
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                                wire:loading wire:target="provinsi">
                                                <svg class="animate-spin h-5 w-5 text-accent" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>

                                            <!-- Error icon when not loading -->
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                                wire:loading.remove wire:target="provinsi">
                                                @if ($kabupatenValidationError)
                                                    <svg class="h-5 w-5 text-red-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        @error('kabupaten_kota')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                        @if ($kabupatenValidationError)
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $kabupatenValidationError }}</span>
                                        @endif
                                    </div>

                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama
                                            Dinas *</label>
                                        <input wire:model="nama_dinas" name="nama_dinas"
                                            placeholder="Nama lengkap dinas"
                                            class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent" />
                                        @error('nama_dinas')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Alamat
                                            *</label>
                                        <textarea wire:model="alamat" name="alamat" placeholder="Alamat lengkap" rows="3"
                                            class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent"></textarea>
                                        @error('alamat')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Telepon</label>
                                        <input wire:model="telp" name="telp" placeholder="Nomor telepon"
                                            class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent" />
                                        @error('telp')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Email</label>
                                        <input wire:model="email" name="email" type="email"
                                            placeholder="Alamat email"
                                            class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent" />
                                        @error('email')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Website</label>
                                        <input wire:model="website" name="website" type="url"
                                            placeholder="URL website"
                                            class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent" />
                                        @error('website')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status
                                            *</label>
                                        <select wire:model="status" name="status"
                                            class="w-full px-3 py-2 text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                                            @foreach ($statusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <span
                                                class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Location Map Section -->
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Lokasi</label>

                                        <!-- Get Current Location Button -->
                                        <div class="mb-3">
                                            <button type="button" id="get-location-btn"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Ambil Lokasi Saya
                                            </button>
                                        </div>

                                        <!-- Map Container with Leaflet.js -->
                                        <div id="location-map"
                                            class="w-full h-80 border border-neutral-300 dark:border-neutral-600 rounded-lg mb-3 relative"
                                            style="min-height: 320px; height: 320px;" wire:ignore
                                            x-data="{
                                                map: null,
                                                marker: null,
                                                mapReady: false,
                                                initMap() {
                                                    console.log('initMap called');
                                                    // Wait longer for container to be fully rendered and stable
                                                    setTimeout(() => {
                                                        this.setupMap();
                                                    }, 300);
                                                },
                                                setupMap() {
                                                    try {
                                                        console.log('Initializing Leaflet map...');
                                            
                                                        if (typeof L === 'undefined') {
                                                            console.error('Leaflet.js is not loaded');
                                                            this.mapReady = true;
                                                            return;
                                                        }
                                            
                                                        // Force container to have proper dimensions
                                                        const container = document.getElementById('location-map');
                                                        if (container) {
                                                            container.style.width = '100%';
                                                            container.style.height = '320px';
                                                            container.style.minHeight = '320px';
                                                        }
                                            
                                                        this.map = L.map('location-map', {
                                                            preferCanvas: true,
                                                            fadeAnimation: false,
                                                            zoomAnimation: false,
                                                            closePopupOnClick: false,
                                                            boxZoom: false,
                                                            doubleClickZoom: false
                                                        }).setView([-2.5, 118], 5);
                                            
                                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                            attribution: '© OpenStreetMap contributors',
                                                            maxZoom: 19,
                                                            errorTileUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==',
                                                            timeout: 10000
                                                        }).addTo(this.map);
                                            
                                                        // Force map to resize multiple times to ensure proper loading
                                                        this.map.invalidateSize(true);
                                            
                                                        // Check if we have existing coordinates (for edit mode)
                                                        const latInput = document.getElementById('latitude-input');
                                                        const lngInput = document.getElementById('longitude-input');
                                                        const existingLat = latInput && latInput.value ? parseFloat(latInput.value) : null;
                                                        const existingLng = lngInput && lngInput.value ? parseFloat(lngInput.value) : null;
                                                        
                                                        console.log('Checking coordinates:', {
                                                            latInput: latInput?.value,
                                                            lngInput: lngInput?.value,
                                                            existingLat,
                                                            existingLng
                                                        });
                                                        
                                                        let initialLat, initialLng, initialZoom;
                                                        
                                                        if (existingLat && existingLng && existingLat !== 0 && existingLng !== 0) {
                                                            // Use existing coordinates for edit mode
                                                            console.log('Using existing coordinates:', existingLat, existingLng);
                                                            initialLat = existingLat;
                                                            initialLng = existingLng;
                                                            initialZoom = 15;
                                                        } else {
                                                            // Use default Indonesia center for create mode
                                                            console.log('Using default coordinates (create mode)');
                                                            initialLat = -2.5489;
                                                            initialLng = 118.0149;
                                                            initialZoom = 5;
                                                        }
                                                        
                                                        // Set view first, then add marker
                                                        this.map.setView([initialLat, initialLng], initialZoom);
                                                        this.setMarker(initialLat, initialLng);
                                                        
                                                        // Force multiple redraws to ensure tiles load
                                                        setTimeout(() => {
                                                            this.map.invalidateSize(true);
                                                            this.map.setView([initialLat, initialLng], initialZoom);
                                                        }, 100);
                                                        
                                                        setTimeout(() => {
                                                            this.map.invalidateSize(true);
                                                        }, 300);
                                                        
                                                        setTimeout(() => {
                                                            this.map.invalidateSize(true);
                                                        }, 500);
                                                        
                                                        this.mapReady = true;
                                                        console.log('Map initialized successfully');
                                            
                                                        // Add click handler with error protection
                                                        this.map.on('click', (e) => {
                                                            try {
                                                                console.log('Map clicked at:', e.latlng.lat, e.latlng.lng);
                                                                this.setMarker(e.latlng.lat, e.latlng.lng);
                                                            } catch (error) {
                                                                console.error('Error handling map click:', error);
                                                            }
                                                        });
                                                        // Add event listeners to ensure tiles load properly
                                                        this.map.on('load', () => {
                                                            console.log('Map load event fired');
                                                            this.map.invalidateSize(true);
                                                        });
                                                        
                                                        this.map.on('tileload', () => {
                                                            console.log('Tiles loading...');
                                                        });
                                                        
                                                        this.map.on('tilesload', () => {
                                                            console.log('All tiles loaded');
                                                        });
                                            
                                                        // Prevent DOM updates during map interaction
                                                        this.map.on('mousedown', () => {
                                                            document.body.style.pointerEvents = 'none';
                                                            setTimeout(() => {
                                                                document.body.style.pointerEvents = 'auto';
                                                            }, 100);
                                                        });
                                            
                                                    } catch (error) {
                                                        console.error('Error initializing map:', error);
                                                        this.mapReady = true;
                                                    }
                                                },
                                                setMarker(lat, lng) {
                                                    try {
                                                        if (!this.map) {
                                                            console.error('Map not initialized');
                                                            return;
                                                        }
                                            
                                                        if (this.marker) {
                                                            this.map.removeLayer(this.marker);
                                                        }
                                            
                                                        this.marker = L.marker([lat, lng], {
                                                            draggable: true
                                                        }).addTo(this.map);
                                            
                                                        this.marker.on('dragend', (e) => {
                                                            try {
                                                                const pos = e.target.getLatLng();
                                                                this.updateCoordinates(pos.lat, pos.lng);
                                                            } catch (error) {
                                                                console.error('Error handling marker drag:', error);
                                                            }
                                                        });
                                            
                                                        this.updateCoordinates(lat, lng);
                                                    } catch (error) {
                                                        console.error('Error setting marker:', error);
                                                    }
                                                },
                                                updateCoordinates(lat, lng) {
                                                    try {
                                                        const latEl = document.getElementById('current-latitude');
                                                        const lngEl = document.getElementById('current-longitude');
                                                        const latInput = document.getElementById('latitude-input');
                                                        const lngInput = document.getElementById('longitude-input');
                                            
                                                        if (latEl) latEl.textContent = lat.toFixed(6);
                                                        if (lngEl) lngEl.textContent = lng.toFixed(6);
                                                        if (latInput) latInput.value = lat.toFixed(6);
                                                        if (lngInput) lngInput.value = lng.toFixed(6);
                                            
                                                        // Update Livewire data using the new method
                                                        if (window.livewire) {
                                                            try {
                                                                const wireElement = document.querySelector('[wire\\:id]');
                                                                if (wireElement) {
                                                                    const wireId = wireElement.getAttribute('wire:id');
                                                                    const component = window.livewire.find(wireId);
                                                                    if (component) {
                                                                        component.call('updateCoordinates', lat.toFixed(6), lng.toFixed(6));
                                                                    }
                                                                }
                                                            } catch (error) {
                                                                console.log('Livewire update failed:', error);
                                                            }
                                                        }
                                                    } catch (error) {
                                                        console.error('Error updating coordinates:', error);
                                                    }
                                                },
                                            
                                                getCurrentLocation() {
                                                    if (!navigator.geolocation) {
                                                        alert('Geolocation tidak didukung oleh browser ini.');
                                                        return;
                                                    }
                                            
                                                    console.log('Getting current location...');
                                            
                                                    navigator.geolocation.getCurrentPosition(
                                                        (position) => {
                                                            const lat = position.coords.latitude;
                                                            const lng = position.coords.longitude;
                                            
                                                            console.log('Current location found:', lat, lng);
                                                            this.setMarker(lat, lng);
                                                            if (this.map) {
                                                                this.map.setView([lat, lng], 15);
                                                            }
                                                        },
                                                        (error) => {
                                                            console.error('Geolocation error:', error);
                                                            let message = 'Gagal mendapatkan lokasi. ';
                                                            switch (error.code) {
                                                                case error.PERMISSION_DENIED:
                                                                    message += 'Permisi akses lokasi ditolak.';
                                                                    break;
                                                                case error.POSITION_UNAVAILABLE:
                                                                    message += 'Informasi lokasi tidak tersedia.';
                                                                    break;
                                                                case error.TIMEOUT:
                                                                    message += 'Permintaan lokasi timeout.';
                                                                    break;
                                                                default:
                                                                    message += 'Terjadi kesalahan yang tidak diketahui.';
                                                                    break;
                                                            }
                                                            alert(message);
                                                        }, {
                                                            enableHighAccuracy: true,
                                                            timeout: 10000,
                                                            maximumAge: 60000
                                                        }
                                                    );
                                                }
                                            }" x-init="initMap()">
                                            <!-- Loading indicator -->
                                            <div x-show="!mapReady"
                                                class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg z-20">
                                                <div class="text-center">
                                                    <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-2"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    <p class="text-sm text-gray-600">Memuat peta...</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Coordinates Display -->
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div class="flex items-center">
                                                <span
                                                    class="font-medium text-neutral-700 dark:text-neutral-300 mr-2">Latitude:</span>
                                                <span id="current-latitude"
                                                    class="text-blue-600 dark:text-blue-400 font-mono">-</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span
                                                    class="font-medium text-neutral-700 dark:text-neutral-300 mr-2">Longitude:</span>
                                                <span id="current-longitude"
                                                    class="text-blue-600 dark:text-blue-400 font-mono">-</span>
                                            </div>
                                        </div>

                                        <!-- Hidden inputs for Livewire -->
                                        <input type="hidden" wire:model="latitude" name="latitude"
                                            id="latitude-input">
                                        <input type="hidden" wire:model="longitude" name="longitude"
                                            id="longitude-input">

                                        @error('latitude')
                                            <span class="text-red-500 dark:text-red-400 text-sm">Latitude
                                                diperlukan</span>
                                        @enderror
                                        @error('longitude')
                                            <span class="text-red-500 dark:text-red-400 text-sm">Longitude
                                                diperlukan</span>
                                        @enderror
                                    </div>

                                    <!-- Image Upload Section -->
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Gambar</label>

                                        <!-- File Input -->
                                        <div class="mt-1">
                                            <input wire:model="gambar" id="gambar-upload" type="file"
                                                accept="image/*"
                                                onchange="validateFileSize(this); previewImage(this);"
                                                class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300" />
                                            @error('gambar')
                                                <span
                                                    class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Image Preview -->
                                        <div id="image-preview" class="mt-4 hidden">
                                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                                Preview:</p>
                                            <div
                                                class="border-2 border-dashed border-neutral-300 dark:border-neutral-600 rounded-lg p-4">
                                                <img id="preview-img" src="" alt="Preview"
                                                    class="max-w-full h-auto max-h-48 mx-auto rounded-lg shadow-sm">
                                            </div>
                                        </div>

                                        <!-- Existing Image -->
                                        @if ($existingGambar)
                                            <div class="mt-4" id="existing-image">
                                                <p
                                                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                                    Gambar Saat Ini:</p>
                                                <div class="relative inline-block">
                                                    <img src="{{ asset('storage/' . $existingGambar) }}"
                                                        alt="Existing Image"
                                                        class="max-w-full h-auto max-h-48 rounded-lg shadow-sm">
                                                    <button wire:click="deleteImage" type="button"
                                                        wire:confirm="Apakah Anda yakin ingin menghapus gambar ini?"
                                                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                            Format yang didukung: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-neutral-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" onclick="submitForm()" id="submit-btn"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span
                                        id="submit-text">{{ $modalMode === 'create' ? 'Simpan' : 'Perbarui' }}</span>
                                    <span id="loading-text" class="hidden">
                                        <div class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            {{ $modalMode === 'create' ? 'Menyimpan...' : 'Memperbarui...' }}
                                        </div>
                                    </span>
                                </button>
                                <button type="button" wire:click="$set('showModal', false)"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-800 text-base font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const previewImg = document.getElementById('preview-img');
                const existingImage = document.getElementById('existing-image');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        preview.classList.remove('hidden');
                        if (existingImage) {
                            existingImage.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Handle get location button
            document.addEventListener('DOMContentLoaded', function() {
                const getLocationBtn = document.getElementById('get-location-btn');
                if (getLocationBtn) {
                    getLocationBtn.addEventListener('click', function() {
                        if (!navigator.geolocation) {
                            alert('Geolocation tidak didukung oleh browser ini.');
                            return;
                        }

                        console.log('Getting current location...');

                        getLocationBtn.disabled = true;
                        getLocationBtn.textContent = 'Mendapatkan lokasi...';

                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;

                                console.log('Current location found:', lat, lng);

                                // Update display elements and hidden inputs
                                const latDisplay = document.getElementById('current-latitude');
                                const lngDisplay = document.getElementById('current-longitude');
                                const latInput = document.getElementById('latitude-input');
                                const lngInput = document.getElementById('longitude-input');

                                if (latDisplay) latDisplay.textContent = lat.toFixed(6);
                                if (lngDisplay) lngDisplay.textContent = lng.toFixed(6);
                                if (latInput) latInput.value = lat.toFixed(6);
                                if (lngInput) lngInput.value = lng.toFixed(6);

                                // Update Livewire using the updateCoordinates method
                                if (window.livewire) {
                                    try {
                                        const wireElement = document.querySelector('[wire\\:id]');
                                        if (wireElement) {
                                            const wireId = wireElement.getAttribute('wire:id');
                                            const component = window.livewire.find(wireId);
                                            if (component) {
                                                component.call('updateCoordinates', lat.toFixed(6), lng
                                                    .toFixed(6));
                                            }
                                        }
                                    } catch (error) {
                                        console.log('Livewire update failed:', error);
                                    }
                                }

                                // Reset button
                                getLocationBtn.disabled = false;
                                getLocationBtn.innerHTML =
                                    '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Ambil Lokasi Saya';

                                alert('Lokasi berhasil didapatkan!');
                            },
                            (error) => {
                                console.error('Geolocation error:', error);
                                let message = 'Gagal mendapatkan lokasi. ';
                                switch (error.code) {
                                    case error.PERMISSION_DENIED:
                                        message += 'Permisi akses lokasi ditolak.';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        message += 'Informasi lokasi tidak tersedia.';
                                        break;
                                    case error.TIMEOUT:
                                        message += 'Permintaan lokasi timeout.';
                                        break;
                                    default:
                                        message += 'Terjadi kesalahan yang tidak diketahui.';
                                        break;
                                }
                                alert(message);

                                // Reset button
                                getLocationBtn.disabled = false;
                                getLocationBtn.innerHTML =
                                    '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Ambil Lokasi Saya';
                            }, {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 60000
                            }
                        );
                    });
                }
            });

            function submitForm() {
                const form = document.getElementById('alamat-form');
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const loadingText = document.getElementById('loading-text');

                // Show loading state
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingText.classList.remove('hidden');

                const formData = new FormData();
                const modalMode = document.getElementById('modal-mode').value;
                const selectedId = document.getElementById('selected-id').value;

                // Add all form fields manually
                formData.append('provinsi', document.querySelector('select[name="provinsi"]').value || '');
                formData.append('kabupaten_kota', document.querySelector('select[name="kabupaten_kota"]').value || '');
                formData.append('nama_dinas', document.querySelector('input[name="nama_dinas"]').value || '');
                formData.append('alamat', document.querySelector('textarea[name="alamat"]').value || '');
                formData.append('telp', document.querySelector('input[name="telp"]').value || '');
                formData.append('email', document.querySelector('input[name="email"]').value || '');
                formData.append('website', document.querySelector('input[name="website"]').value || '');
                formData.append('status', document.querySelector('select[name="status"]').value || '');

                // Get coordinates from Livewire component to ensure they're preserved
                if (window.livewire) {
                    try {
                        const wireElement = document.querySelector('[wire\\:id]');
                        if (wireElement) {
                            const wireId = wireElement.getAttribute('wire:id');
                            const component = window.livewire.find(wireId);
                            if (component) {
                                // Try to get data from component properties
                                latValue = component.get('latitude') || component.latitude || '';
                                lngValue = component.get('longitude') || component.longitude || '';
                                console.log('✅ Sending coordinates from Livewire (first function):', {
                                    latitude: latValue,
                                    longitude: lngValue
                                });
                            }
                        }
                    } catch (error) {
                        console.log('⚠️ Error getting coordinates from Livewire (first function):', error);
                    }
                }

                // Fallback to DOM if Livewire failed
                if (!latValue || !lngValue) {
                    latValue = document.querySelector('input[name="latitude"]').value || '';
                    lngValue = document.querySelector('input[name="longitude"]').value || '';
                    console.log('📍 Fallback: Sending coordinates from DOM (first function):', {
                        latitude: latValue,
                        longitude: lngValue
                    });
                }

                formData.append('latitude', latValue);
                formData.append('longitude', lngValue);

                // Handle file upload with base64 encoding to bypass PHP temp file issues
                const fileInput = document.getElementById('gambar-upload');

                if (fileInput && fileInput.files[0]) {
                    const file = fileInput.files[0];

                    if (file.type.startsWith('image/') && file.size <= 2048000) {
                        // Convert to base64 to bypass PHP temp file system
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            formData.append('gambar_base64', e.target.result);
                            formData.append('gambar_name', file.name);
                            formData.append('gambar_type', file.type);

                            // Submit form after file is read
                            submitFormData(formData);
                        };
                        reader.onerror = function(e) {
                            console.error('FileReader error:', e);
                            submitFormData(formData); // Submit without image
                        };
                        reader.readAsDataURL(file);
                        return; // Exit here, will continue in reader.onload
                    }
                }

                // Submit form without file
                submitFormData(formData);
            }

            function submitFormData(formData) {
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const loadingText = document.getElementById('loading-text');
                const modalMode = document.getElementById('modal-mode').value;
                const selectedId = document.getElementById('selected-id').value;

                // Add mode and ID to formData
                formData.append('mode', modalMode);
                if (selectedId) {
                    formData.append('id', selectedId);
                }

                fetch('{{ route('admin.daftar-alamat.save') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                console.error('Server returned non-JSON response:', text);
                                const jsonMatch = text.match(/\{.*\}$/);
                                if (jsonMatch) {
                                    try {
                                        return JSON.parse(jsonMatch[0]);
                                    } catch (e) {
                                        throw new Error('Server returned HTML instead of JSON. Check server logs.');
                                    }
                                }
                                throw new Error('Server returned HTML instead of JSON. Check server logs.');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Close modal and refresh
                            @this.set('showModal', false);
                            @this.$refresh();

                            // Show success message
                            const message = document.createElement('div');
                            message.className =
                                'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                            message.textContent = data.message;
                            document.body.appendChild(message);
                            setTimeout(() => message.remove(), 3000);
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Show error message
                        const message = document.createElement('div');
                        message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                        message.textContent = error.message || 'Terjadi kesalahan saat menyimpan data';
                        document.body.appendChild(message);
                        setTimeout(() => message.remove(), 5000);
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitText.classList.remove('hidden');
                        loadingText.classList.add('hidden');
                    });
            }
        </script>
    @endif
</div>

<script>
    function validateFileSize(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 1536000; // 1.5MB in bytes

            if (!file.type.startsWith('image/')) {
                // Show error message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent = 'File harus berupa gambar (JPG, PNG, GIF, dll.)';
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);

                // Clear the input
                input.value = '';
                return false;
            }

            if (file.size > maxSize) {
                // Show error message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent =
                    `Ukuran file terlalu besar (${(file.size/1024/1024).toFixed(2)}MB). Maksimal 1.5MB`;
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);

                // Clear the input
                input.value = '';
                return false;
            }

            return true;
        }
    }

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const existingImage = document.getElementById('existing-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
                if (existingImage) {
                    existingImage.style.display = 'none';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Listen for Livewire events
    document.addEventListener('livewire:loaded', () => {
        Livewire.on('imageUploaded', () => {
            // Handle image preview when Livewire updates the gambar property
            const fileInput = document.getElementById('gambar-upload');
            if (fileInput && fileInput.files && fileInput.files[0]) {
                previewImage(fileInput);
            }
        });

        // Listen for modal opened event to update coordinates display
        const modal = document.querySelector('[x-data*="showModal"]');
        if (modal) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        const isVisible = !modal.style.display || modal.style.display !== 'none';
                        if (isVisible) {
                            setTimeout(() => {
                                updateCoordinatesDisplay();
                                
                                // Force map redraw when modal becomes visible
                                const mapContainer = document.querySelector('[x-data*="initMap"]');
                                if (mapContainer && mapContainer.__x) {
                                    const alpineData = mapContainer.__x.$data;
                                    if (alpineData.map) {
                                        // Multiple invalidateSize calls to ensure proper rendering
                                        alpineData.map.invalidateSize(true);
                                        setTimeout(() => {
                                            alpineData.map.invalidateSize(true);
                                        }, 100);
                                        setTimeout(() => {
                                            alpineData.map.invalidateSize(true);
                                        }, 300);
                                    }
                                }
                            }, 100);
                        }
                    }
                });
            });
            observer.observe(modal, { attributes: true });
        }

        // Listen for Livewire wire:model updates
        Livewire.on('initializeMap', () => {
            setTimeout(() => {
                updateCoordinatesDisplay();
                // Also trigger map update if needed
                const mapContainer = document.querySelector('[x-data*="initMap"]');
                if (mapContainer && mapContainer.__x) {
                    const alpineData = mapContainer.__x.$data;
                    if (alpineData.map && alpineData.setMarker) {
                        // Force map to redraw when modal is opened
                        alpineData.map.invalidateSize(true);
                        
                        const latInput = document.getElementById('latitude-input');
                        const lngInput = document.getElementById('longitude-input');
                        if (latInput && lngInput && latInput.value && lngInput.value) {
                            const lat = parseFloat(latInput.value);
                            const lng = parseFloat(lngInput.value);
                            if (lat !== 0 && lng !== 0) {
                                // Set view first, then marker
                                alpineData.map.setView([lat, lng], 15);
                                setTimeout(() => {
                                    alpineData.setMarker(lat, lng);
                                    alpineData.map.invalidateSize(true);
                                }, 100);
                            }
                        }
                    }
                }
            }, 200);
        });
    });

    function updateCoordinatesDisplay() {
        const latInput = document.getElementById('latitude-input');
        const lngInput = document.getElementById('longitude-input');
        const latDisplay = document.getElementById('current-latitude');
        const lngDisplay = document.getElementById('current-longitude');

        console.log('updateCoordinatesDisplay called', {
            latInput: latInput?.value,
            lngInput: lngInput?.value,
            latDisplay: latDisplay?.textContent,
            lngDisplay: lngDisplay?.textContent
        });

        if (latInput && lngInput && latDisplay && lngDisplay) {
            const lat = latInput.value;
            const lng = lngInput.value;
            
            if (lat && lng && lat !== '0' && lng !== '0') {
                latDisplay.textContent = parseFloat(lat).toFixed(6);
                lngDisplay.textContent = parseFloat(lng).toFixed(6);
                console.log('Updated coordinates display:', lat, lng);
            } else {
                console.log('Coordinates are empty or zero:', lat, lng);
            }
        } else {
            console.log('Missing elements for coordinate display');
        }
    }

    function submitForm() {
        const form = document.getElementById('alamat-form');
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingText = document.getElementById('loading-text');

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');

        const formData = new FormData();
        const modalMode = document.getElementById('modal-mode').value;
        const selectedId = document.getElementById('selected-id').value;

        // Add all form fields manually - get coordinates from Livewire component
        formData.append('provinsi', document.querySelector('select[name="provinsi"]').value || '');
        formData.append('kabupaten_kota', document.querySelector('select[name="kabupaten_kota"]').value || '');
        formData.append('nama_dinas', document.querySelector('input[name="nama_dinas"]').value || '');
        formData.append('alamat', document.querySelector('textarea[name="alamat"]').value || '');
        formData.append('telp', document.querySelector('input[name="telp"]').value || '');
        formData.append('email', document.querySelector('input[name="email"]').value || '');
        formData.append('website', document.querySelector('input[name="website"]').value || '');
        formData.append('status', document.querySelector('select[name="status"]').value || '');

        // Get coordinates from Livewire component to ensure they're preserved
        let latValue = '';
        let lngValue = '';

        // Try multiple ways to get Livewire component
        if (window.livewire) {
            try {
                const wireElement = document.querySelector('[wire\\:id]');
                if (wireElement) {
                    const wireId = wireElement.getAttribute('wire:id');
                    const component = window.livewire.find(wireId);
                    if (component) {
                        // Try to get data from component properties
                        latValue = component.get('latitude') || component.latitude || '';
                        lngValue = component.get('longitude') || component.longitude || '';
                        console.log('✅ Sending coordinates from Livewire:', {
                            latitude: latValue,
                            longitude: lngValue
                        });
                    }
                }
            } catch (error) {
                console.log('⚠️ Error getting coordinates from Livewire:', error);
            }
        }

        // Fallback to DOM if Livewire failed
        if (!latValue || !lngValue) {
            latValue = document.querySelector('input[name="latitude"]').value || '';
            lngValue = document.querySelector('input[name="longitude"]').value || '';
            console.log('📍 Fallback: Sending coordinates from DOM:', {
                latitude: latValue,
                longitude: lngValue
            });
        }

        formData.append('latitude', latValue);
        formData.append('longitude', lngValue);

        // Handle file upload with base64 encoding to bypass PHP temp file issues
        const fileInput = document.getElementById('gambar-upload');

        if (fileInput && fileInput.files[0]) {
            const file = fileInput.files[0];

            // Check file type
            if (!file.type.startsWith('image/')) {
                // Reset button state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingText.classList.add('hidden');

                // Show error message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent = 'File harus berupa gambar (JPG, PNG, GIF, dll.)';
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);
                return;
            }

            // Check file size - reduced to 1.5MB to account for base64 encoding overhead
            if (file.size > 1536000) { // 1.5MB limit
                // Reset button state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingText.classList.add('hidden');

                // Show error message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent = 'Ukuran file terlalu besar. Maksimal 1.5MB';
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);
                return;
            }

            // Convert to base64 to bypass PHP temp file system
            const reader = new FileReader();
            reader.onload = function(e) {
                formData.append('gambar_base64', e.target.result);
                formData.append('gambar_name', file.name);
                formData.append('gambar_type', file.type);

                // Submit form after file is read
                submitFormData(formData);
            };
            reader.onerror = function(e) {
                console.error('FileReader error:', e);

                // Reset button state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingText.classList.add('hidden');

                // Show error message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent = 'Gagal membaca file gambar';
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);
                return;
            };
            reader.readAsDataURL(file);
            return; // Exit here, will continue in reader.onload
        }

        // Submit form without file
        submitFormData(formData);
    }

    function submitFormData(formData) {
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingText = document.getElementById('loading-text');
        const modalMode = document.getElementById('modal-mode').value;
        const selectedId = document.getElementById('selected-id').value;

        // Add mode and ID to formData
        formData.append('mode', modalMode);
        if (selectedId) {
            formData.append('id', selectedId);
        }

        fetch('/admin/daftar-alamat/save', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Handle specific HTTP error codes
                    if (response.status === 413) {
                        throw new Error(
                            'File terlalu besar untuk diupload. Silakan pilih file yang lebih kecil (maksimal 1.5MB).'
                        );
                    } else if (response.status === 422) {
                        throw new Error('Data tidak valid. Silakan periksa kembali form Anda.');
                    } else if (response.status === 500) {
                        throw new Error('Terjadi kesalahan server. Silakan coba lagi nanti.');
                    } else {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                }
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Server returned non-JSON response:', text);
                        const jsonMatch = text.match(/\{.*\}$/);
                        if (jsonMatch) {
                            try {
                                return JSON.parse(jsonMatch[0]);
                            } catch (e) {
                                throw new Error('Server returned HTML instead of JSON. Check server logs.');
                            }
                        }
                        throw new Error('Server returned HTML instead of JSON. Check server logs.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('✅ Save successful:', data.message);

                    // Close modal and refresh with delay to ensure data is saved
                    setTimeout(() => {
                        if (window.livewire) {
                            const wireElement = document.querySelector('[wire\\:id]');
                            if (wireElement) {
                                const wireId = wireElement.getAttribute('wire:id');
                                console.log('🔄 Refreshing Livewire component:', wireId);
                                window.livewire.find(wireId).set('showModal', false);
                                window.livewire.find(wireId).call('$refresh');
                            }
                        }
                    }, 500); // Small delay to ensure server processing is complete

                    // Show success message
                    const message = document.createElement('div');
                    message.className =
                        'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                    message.textContent = data.message;
                    document.body.appendChild(message);
                    setTimeout(() => message.remove(), 3000);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Show error message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent = error.message || 'Terjadi kesalahan saat menyimpan data';
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingText.classList.add('hidden');
            });
    }
</script>