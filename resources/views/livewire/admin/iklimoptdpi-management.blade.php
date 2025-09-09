<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola Data Iklim Opt DPI</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data iklim optimalisasi DPI
                </p>
            </div>
            <flux:button wire:click="openCreateModal" variant="primary">
                Tambah Data Iklim Opt DPI
            </flux:button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search & Filter & Per Page -->
    <div class="mb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
            <flux:input 
                wire:model.live="search" 
                placeholder="Cari berdasarkan wilayah, topik, variabel..."
                class="w-full sm:max-w-sm"
            />
            
            <!-- Filter Toggle Button -->
            <flux:button wire:click="toggleFilters" variant="ghost" class="!px-3 !py-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </flux:button>
            
            <!-- Reset Sort Button -->
            @if(!empty($sortField))
                <flux:button wire:click="resetSort" variant="ghost" class="!px-3 !py-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Sort
                </flux:button>
            @endif
            
            <div class="flex items-center space-x-2">
                <label class="text-sm text-neutral-600 dark:text-neutral-400">Tampil</label>
                <select wire:model.live="perPage" class="text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    @foreach($perPageOptions as $size)
                        <option value="{{ $size }}">{{ $size }}</option>
                    @endforeach
                </select>
                <span class="text-sm text-neutral-600 dark:text-neutral-400">/ halaman</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center space-x-2">
                <label for="exportFormat" class="text-sm text-neutral-600 dark:text-neutral-400">Export</label>
                <select id="exportFormat" wire:model="exportFormat" class="text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="xlsx">XLSX</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
            <flux:button wire:click="export" variant="ghost" class="!px-4">
                Download
            </flux:button>
            <button type="button" onclick="printIklimoptdpi()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Print
            </button>
        </div>
    </div>
    
    <!-- Filter Panel -->
    @if($showFilters)
    <div class="mb-4 p-4 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg border border-neutral-200 dark:border-neutral-700 no-print">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-neutral-900 dark:text-neutral-100">Filter Data</h3>
            <flux:button wire:click="clearFilters" variant="ghost" size="sm">
                Clear All
            </flux:button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tahun</label>
                <select wire:model.live="filterTahun" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunOptions as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Topik</label>
                <select wire:model.live="filterTopik" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Topik</option>
                    @foreach($topiks as $topik)
                        <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Variabel</label>
                <select wire:model.live="filterVariabel" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Variabel</option>
                    @foreach($variabels as $variabel)
                        <option value="{{ $variabel->id }}">{{ $variabel->nama }} ({{ $variabel->satuan }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Klasifikasi</label>
                <select wire:model.live="filterKlasifikasi" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Klasifikasi</option>
                    @foreach($klasifikasis as $klasifikasi)
                        <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                <select wire:model.live="filterStatus" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <!-- Iklimoptdpi Table -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700" id="iklimoptdpi-table-wrapper">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400" id="iklimoptdpi-table">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('id')">
                            <div class="flex items-center">
                                ID
                                @if($sortField === 'id')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('id_iklimoptdpi_topik')">
                            <div class="flex items-center">
                                Topik
                                @if($sortField === 'id_iklimoptdpi_topik')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('id_iklimoptdpi_variabel')">
                            <div class="flex items-center">
                                Variabel
                                @if($sortField === 'id_iklimoptdpi_variabel')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('id_iklimoptdpi_klasifikasi')">
                            <div class="flex items-center">
                                Klasifikasi
                                @if($sortField === 'id_iklimoptdpi_klasifikasi')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('wilayah')">
                            <div class="flex items-center">
                                Wilayah
                                @if($sortField === 'wilayah')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('tahun')">
                            <div class="flex items-center">
                                Tahun
                                @if($sortField === 'tahun')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('nilai')">
                            <div class="flex items-center">
                                Nilai
                                @if($sortField === 'nilai')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('status')">
                            <div class="flex items-center">
                                Status
                                @if($sortField === 'status')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($iklimoptdpis as $iklimoptdpi)
                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-500 dark:text-neutral-400">
                            #{{ $iklimoptdpi->id }}
                        </td>
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $iklimoptdpi->iklimoptdpiTopik->nama }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $iklimoptdpi->iklimoptdpiVariabel->nama }}
                            <span class="text-xs text-neutral-500 dark:text-neutral-400">({{ $iklimoptdpi->iklimoptdpiVariabel->satuan }})</span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $iklimoptdpi->iklimoptdpiKlasifikasi->nama }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $iklimoptdpi->wilayah }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $iklimoptdpi->tahun }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($iklimoptdpi->nilai, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($iklimoptdpi->status === 'Aktif') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                @elseif($iklimoptdpi->status === 'Tidak Aktif') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                @elseif($iklimoptdpi->status === 'Dalam Proses') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                @elseif($iklimoptdpi->status === 'Selesai') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                @else bg-neutral-100 text-neutral-800 dark:bg-neutral-900/30 dark:text-neutral-300
                                @endif">
                                {{ $iklimoptdpi->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 no-print">
                            <div class="flex space-x-2">
                                <flux:button wire:click="openEditModal({{ $iklimoptdpi->id }})" variant="ghost" size="sm">
                                    Edit
                                </flux:button>
                                <flux:button wire:click="openDeleteModal({{ $iklimoptdpi->id }})" variant="danger" size="sm">
                                    Hapus
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                            Tidak ada data iklimoptdpi ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="text-xs text-neutral-600 dark:text-neutral-400 md:mr-auto">
                Menampilkan
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $iklimoptdpis->firstItem() }}</span>
                -
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $iklimoptdpis->lastItem() }}</span>
                dari
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $iklimoptdpis->total() }}</span>
                data iklimoptdpi
            </div>
            {{ $iklimoptdpis->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Create Iklimoptdpi Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Tambah Data Iklim Opt DPI</h3>
                <form wire:submit="createIklimoptdpi">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Topik Iklim Opt DPI</label>
                            <select wire:model="id_iklimoptdpi_topik" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Topik</option>
                                @foreach($topiks as $topik)
                                    <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_iklimoptdpi_topik') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Variabel</label>
                            <select wire:model="id_iklimoptdpi_variabel" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Variabel</option>
                                @foreach($variabels as $variabel)
                                    <option value="{{ $variabel->id }}">{{ $variabel->nama }} ({{ $variabel->satuan }})</option>
                                @endforeach
                            </select>
                            @error('id_iklimoptdpi_variabel') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Klasifikasi</label>
                            <select wire:model="id_iklimoptdpi_klasifikasi" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Klasifikasi</option>
                                @foreach($klasifikasis as $klasifikasi)
                                    <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_iklimoptdpi_klasifikasi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <flux:input wire:model="wilayah" label="Wilayah" placeholder="Masukkan nama wilayah" required />
                        @error('wilayah') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="tahun" label="Tahun" type="number" placeholder="2024" min="2000" max="2030" required />
                        @error('tahun') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="nilai" label="Nilai" type="number" step="0.01" placeholder="0.00" required />
                        @error('nilai') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                            <select wire:model="status" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                                <option value="Dalam Proses">Dalam Proses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Tertunda">Tertunda</option>
                            </select>
                            @error('status') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <flux:button type="button" wire:click="closeCreateModal" variant="ghost">
                            Batal
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            Simpan
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Iklimoptdpi Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Edit Data Iklim Opt DPI</h3>
                <form wire:submit="updateIklimoptdpi">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Topik Iklim Opt DPI</label>
                            <select wire:model="id_iklimoptdpi_topik" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Topik</option>
                                @foreach($topiks as $topik)
                                    <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_iklimoptdpi_topik') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Variabel</label>
                            <select wire:model="id_iklimoptdpi_variabel" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Variabel</option>
                                @foreach($variabels as $variabel)
                                    <option value="{{ $variabel->id }}">{{ $variabel->nama }} ({{ $variabel->satuan }})</option>
                                @endforeach
                            </select>
                            @error('id_iklimoptdpi_variabel') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Klasifikasi</label>
                            <select wire:model="id_iklimoptdpi_klasifikasi" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Klasifikasi</option>
                                @foreach($klasifikasis as $klasifikasi)
                                    <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_iklimoptdpi_klasifikasi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <flux:input wire:model="wilayah" label="Wilayah" placeholder="Masukkan nama wilayah" required />
                        @error('wilayah') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="tahun" label="Tahun" type="number" placeholder="2024" min="2000" max="2030" required />
                        @error('tahun') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="nilai" label="Nilai" type="number" step="0.01" placeholder="0.00" required />
                        @error('nilai') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                            <select wire:model="status" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                                <option value="Dalam Proses">Dalam Proses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Tertunda">Tertunda</option>
                            </select>
                            @error('status') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <flux:button type="button" wire:click="closeEditModal" variant="ghost">
                            Batal
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            Update
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mt-2">Hapus Data Iklim Opt DPI</h3>
                <div class="mt-2">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus data iklim opt dpi <strong>{{ $deletingIklimoptdpi->iklimoptdpiTopik->nama ?? '' }}</strong> di wilayah <strong>{{ $deletingIklimoptdpi->wilayah ?? '' }}</strong>?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <flux:button type="button" wire:click="closeDeleteModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button wire:click="deleteIklimoptdpi" variant="danger">
                        Hapus
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
    function printIklimoptdpi() {
        const printContents = document.getElementById('iklimoptdpi-table').outerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <div style="padding: 20px;">
                <h2 style="text-align: center; margin-bottom: 20px;">Data Iklim Opt DPI</h2>
                ${printContents}
                <div style="text-align: right; margin-top: 30px; font-size: 12px;">
                    Dicetak pada: ${new Date().toLocaleString()}
                </div>
            </div>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
    }
    </script>
