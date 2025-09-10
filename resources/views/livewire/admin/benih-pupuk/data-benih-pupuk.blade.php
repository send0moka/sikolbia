<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Data Benih Pupuk</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data benih pupuk
                </p>
            </div>
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Tambah Data Benih Pupuk
            </button>
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
            <div class="relative flex-1 sm:max-w-sm">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari berdasarkan wilayah, variabel, klasifikasi, tahun, atau nilai..."
                    class="w-full px-3 py-2 pr-10 border border-neutral-300 dark:border-neutral-600 rounded-md shadow-sm placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-neutral-800 dark:text-white text-sm"
                    wire:loading.attr="disabled"
                />
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="animate-spin h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                @if($search)
                    <button wire:click="clearSearch" wire:loading.attr="disabled" class="absolute inset-y-0 right-0 pr-3 flex items-center text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>
            
            <!-- Filter Toggle Button -->
            <button wire:click="toggleFilters" class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>
            
            <!-- Reset Sort Button -->
            @if(!empty($sortBy))
                <button wire:click="resetSort" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Sort
                </button>
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
            <button wire:click="export" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Download
            </button>
            <button type="button" onclick="printBenihPupuk()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Print
            </button>
            <button wire:click="printAll" title="Print semua data (max 5000 record)" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Print All
            </button>
        </div>
    </div>
    
    <!-- Filter Panel -->
    @if($showFilters)
    <div class="mb-4 p-4 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg border border-neutral-200 dark:border-neutral-700 no-print">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-neutral-900 dark:text-neutral-100">Filter Data</h3>
            <button wire:click="clearAllFilters" class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Clear All
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tahun</label>
                <select wire:model.live="tahunFilter" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunOptions as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Bulan</label>
                <select wire:model.live="bulanFilter" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Bulan</option>
                    @foreach($bulanOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                <select wire:model.live="statusFilter" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Wilayah</label>
                <select wire:model.live="wilayahFilter" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Variabel</label>
                <select wire:model.live="variabelFilter" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Variabel</option>
                    @foreach($variabelOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Klasifikasi</label>
                <select wire:model.live="klasifikasiFilter" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Klasifikasi</option>
                    @foreach($klasifikasiOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <!-- Benih Pupuk Table -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700" id="benih-pupuk-table-wrapper">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-700">
            <div class="flex justify-between items-center">
                <div class="text-sm text-neutral-700 dark:text-neutral-300">
                    Menampilkan {{ $data->count() }} dari {{ $data->total() }} data
                    @if($search)
                        untuk pencarian "{{ $search }}"
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-neutral-700 dark:text-neutral-300">Tampilkan:</label>
                    <select wire:model.live="perPage" class="text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400" id="benih-pupuk-table">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortByField('tahun')">
                            <div class="flex items-center">
                                Tahun
                                @if($sortBy === 'tahun')
                                    @if($sortDir === 'asc')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortByField('bulan')">
                            <div class="flex items-center">
                                Bulan
                                @if($sortBy === 'bulan')
                                    @if($sortDir === 'asc')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortByField('wilayah')">
                            <div class="flex items-center">
                                Wilayah
                                @if($sortBy === 'wilayah')
                                    @if($sortDir === 'asc')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortByField('variabel')">
                            <div class="flex items-center">
                                Variabel
                                @if($sortBy === 'variabel')
                                    @if($sortDir === 'asc')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortByField('klasifikasi')">
                            <div class="flex items-center">
                                Klasifikasi
                                @if($sortBy === 'klasifikasi')
                                    @if($sortDir === 'asc')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortByField('nilai')">
                            <div class="flex items-center">
                                Nilai
                                @if($sortBy === 'nilai')
                                    @if($sortDir === 'asc')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortByField('status')">
                            <div class="flex items-center">
                                Status
                                @if($sortBy === 'status')
                                    @if($sortDir === 'asc')
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
                    @forelse ($data as $item)
                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-500 dark:text-neutral-400">
                            {{ $loop->iteration + (($data->currentPage() - 1) * $data->perPage()) }}
                        </td>
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $item->tahun }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->bulan->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ Str::limit($item->wilayah->nama ?? '-', 20) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ Str::limit($item->variabel->deskripsi ?? '-', 25) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ Str::limit($item->klasifikasi->deskripsi ?? '-', 20) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->formatted_nilai }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($item->status === 'Aktif') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                @elseif($item->status === 'Tidak Aktif') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                @elseif($item->status === 'Draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                @elseif($item->status === 'Arsip') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                @else bg-neutral-100 text-neutral-800 dark:bg-neutral-900/30 dark:text-neutral-300
                                @endif">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 no-print">
                            <div class="flex space-x-2">
                                <button wire:click="edit({{ $item->id }})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $item->id }})" wire:confirm="Apakah Anda yakin ingin menghapus data ini?" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 dark:text-red-300 hover:text-red-900 dark:hover:text-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-neutral-500 dark:text-neutral-400">
                            @if($search)
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data ditemukan</p>
                                    <p class="text-sm">Coba ubah kata kunci pencarian atau filter</p>
                                    <button wire:click="clearSearch" class="mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                        Hapus Pencarian
                                    </button>
                                </div>
                            @else
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-5.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Belum ada data</p>
                                    <p class="text-sm">Mulai tambahkan data benih pupuk pertama</p>
                                </div>
                            @endif
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
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $data->firstItem() }}</span>
                -
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $data->lastItem() }}</span>
                dari
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $data->total() }}</span>
                data benih pupuk
            </div>
            {{ $data->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Create Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">{{ $editingId ? 'Edit Data Benih Pupuk' : 'Tambah Data Benih Pupuk' }}</h3>
                <form wire:submit="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tahun</label>
                            <input wire:model="tahun" type="number" min="2000" max="2050" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                            @error('tahun') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Bulan</label>
                            <select wire:model="id_bulan" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Bulan</option>
                                @foreach($bulanOptions as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('id_bulan') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Wilayah</label>
                            <select wire:model="id_wilayah" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Wilayah</option>
                                @foreach($wilayahOptions as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('id_wilayah') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Variabel</label>
                            <select wire:model="id_variabel" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Variabel</option>
                                @foreach($variabelOptions as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('id_variabel') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Klasifikasi</label>
                            <select wire:model="id_klasifikasi" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Klasifikasi</option>
                                @foreach($klasifikasiOptions as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('id_klasifikasi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nilai</label>
                            <input wire:model="nilai" type="number" step="0.01" placeholder="0.00" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                            @error('nilai') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                            <select wire:model="status" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Status</option>
                                @foreach($statusOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" class="inline-flex items-center px-3 py-2 border border-neutral-300 dark:border-neutral-600 shadow-sm text-sm leading-4 font-medium rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ $editingId ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function printBenihPupuk() {
    const wrap = document.getElementById('benih-pupuk-table-wrapper');
    if (!wrap) {
        window.print();
        return;
    }

    // Clone content & strip elements not for print
    const clone = wrap.cloneNode(true);
    clone.querySelectorAll('.no-print, nav').forEach(el => el.remove());

    const html = `<!DOCTYPE html><html><head><title>Data Benih Pupuk</title><meta charset='utf-8'>
        <style>
            *{box-sizing:border-box;}
            body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Arial,sans-serif;margin:0;padding:24px;color:#111827;}
            .header{text-align:center;margin-bottom:30px;border-bottom:2px solid #059669;padding-bottom:20px;}
            .logo{width:60px;height:60px;margin:0 auto 15px;}
            .dept-name{font-size:16px;font-weight:600;color:#059669;margin-bottom:5px;}
            .dept-info{font-size:12px;color:#374151;margin-bottom:3px;}
            .report-title{font-size:18px;font-weight:700;color:#111827;margin-top:15px;}
            table{width:100%;border-collapse:collapse;font-size:12px;margin-top:20px;}
            th,td{border:1px solid #e5e7eb;padding:6px 8px;text-align:left;vertical-align:top;}
            th{background:#f3f4f6;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.05em;}
            .no-col{width:40px;text-align:center;}
            .numeric{text-align:right;}
            @media print {
                body{padding:8px;}
                .header{margin-bottom:20px;padding-bottom:15px;}
                .logo{width:50px;height:50px;}
                .dept-name{font-size:14px;}
                .dept-info{font-size:10px;}
                .report-title{font-size:16px;}
                table{font-size:10px;}
                th,td{padding:4px 6px;}
            }
        </style>
    </head><body>
        <div class="header">
            <div class="logo">
                <img src="${window.location.origin}/LogoKementan.png" alt="Logo Kementerian Pertanian" style="width:60px;height:60px;object-fit:contain;" />
            </div>
            <div class="dept-name">KEMENTERIAN PERTANIAN</div>
            <div class="dept-info">REPUBLIK INDONESIA</div>
            <div class="dept-info">Pusat Data dan Sistem Informasi</div>
            <div class="dept-info">Jl. Harsono RM No.3, Ragunan, Pasar Minggu, Jakarta Selatan 12550</div>
            <div class="dept-info">Telp: (021) 7804030 | www.pertanian.go.id</div>
            <div class="report-title">LAPORAN DATA BENIH PUPUK<br>(Data Konsumsi Pangan)</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Wilayah</th>
                    <th>Variabel</th>
                    <th>Klasifikasi</th>
                    <th>Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                ${Array.from(clone.querySelectorAll('tbody tr')).map((row, index) => {
                    const cells = Array.from(row.children);
                    // Skip first cell (nomor), then find cells without no-print class
                    const dataCells = cells.slice(1).filter(cell => !cell.classList.contains('no-print'));
                    const cellsHtml = dataCells.map(cell => cell.outerHTML).join('');
                    return `<tr><td class="no-col">${index + 1}</td>${cellsHtml}</tr>`;
                }).join('')}
            </tbody>
        </table>
    </body></html>`;

    // Create hidden iframe
    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';
    document.body.appendChild(iframe);

    const doc = iframe.contentDocument || iframe.contentWindow.document;
    doc.open();
    doc.write(html);
    doc.close();

    iframe.onload = () => {
        try {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        } finally {
            // Remove iframe after slight delay to allow dialog
            setTimeout(() => iframe.remove(), 2000);
        }
    };
}

document.addEventListener('livewire:loaded', () => {
    console.log('Livewire loaded, registering print event listeners');

    Livewire.on('print-benih-pupuk', () => {
        console.log('Print current page event triggered');
        printBenihPupuk();
    });

    Livewire.on('print-all-benih-pupuk', (event) => {
        console.log('Print all event triggered with data:', event);
        printAllBenihPupukData(event.data);
    });
});

function printAllBenihPupukData(allData) {
    console.log('Print All Data received:', allData);

    // Convert Laravel collection to array if needed
    let dataArray = [];
    if (allData) {
        if (Array.isArray(allData)) {
            dataArray = allData;
        } else if (allData.data && Array.isArray(allData.data)) {
            // Laravel paginated response
            dataArray = allData.data;
        } else if (typeof allData === 'object') {
            // Laravel collection as object
            dataArray = Object.values(allData);
        } else {
            // Try to convert to array
            dataArray = Array.from(allData || []);
        }
    }

    console.log('Data array:', dataArray);

    if (!dataArray || dataArray.length === 0) {
        alert('Tidak ada data untuk dicetak');
        return;
    }

    // Check if data reached the limit
    if (dataArray.length >= 5000) {
        const proceed = confirm(`Data yang akan dicetak mencapai batas maksimum (5000 record).\n\nRekomendasi:\n• Gunakan filter untuk mengurangi jumlah data\n• Filter berdasarkan tahun, bulan, atau wilayah\n• Jika tetap perlu semua data, hubungi administrator untuk meningkatkan limit\n\nApakah Anda ingin melanjutkan mencetak ${dataArray.length} record?`);
        if (!proceed) {
            return;
        }
    }

    let html = `<!DOCTYPE html>
    <html>
    <head>
        <title>Semua Data Benih Pupuk</title>
        <meta charset='utf-8'>
        <style>
            *{box-sizing:border-box;}
            body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Arial,sans-serif;margin:0;padding:16px;color:#111827;font-size:12px;}
            .header{text-align:center;margin-bottom:25px;border-bottom:2px solid #059669;padding-bottom:15px;}
            .logo{width:50px;height:50px;margin:0 auto 12px;}
            .dept-name{font-size:14px;font-weight:600;color:#059669;margin-bottom:4px;}
            .dept-info{font-size:10px;color:#374151;margin-bottom:2px;}
            .report-title{font-size:16px;font-weight:700;color:#111827;margin-top:12px;}
            table{width:100%;border-collapse:collapse;font-size:11px;margin-top:15px;}
            th,td{border:1px solid #e5e7eb;padding:4px 6px;text-align:left;vertical-align:top;}
            th{background:#f3f4f6;font-weight:600;font-size:10px;text-transform:uppercase;letter-spacing:.02em;}
            .no-col{width:35px;text-align:center;}
            .numeric{text-align:right;}
            @media print {
                body{padding:8px;font-size:10px;}
                .header{margin-bottom:15px;padding-bottom:10px;}
                .logo{width:40px;height:40px;}
                .dept-name{font-size:12px;}
                .dept-info{font-size:8px;}
                .report-title{font-size:14px;}
                table{font-size:9px;}
                th,td{padding:3px 4px;}
                .no-col{width:30px;}
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="logo">
                <img src="${window.location.origin}/LogoKementan.png" alt="Logo Kementerian Pertanian" style="width:50px;height:50px;object-fit:contain;" />
            </div>
            <div class="dept-name">KEMENTERIAN PERTANIAN</div>
            <div class="dept-info">REPUBLIK INDONESIA</div>
            <div class="dept-info">Pusat Data dan Sistem Informasi</div>
            <div class="dept-info">Jl. Harsono RM No.3, Ragunan, Pasar Minggu, Jakarta Selatan 12550</div>
            <div class="dept-info">Telp: (021) 7804030 | www.pertanian.go.id</div>
            <div class="report-title">LAPORAN SEMUA DATA BENIH PUPUK<br>(Data Konsumsi Pangan)</div>
            <div style="font-size: 10px; color: #666; margin-top: 5px; text-align: center;">
                Total Records: ${dataArray.length} ${dataArray.length >= 5000 ? '(Limited - Use filters for more data)' : ''}
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Wilayah</th>
                    <th>Variabel</th>
                    <th>Klasifikasi</th>
                    <th>Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>`;

    dataArray.forEach((item, index) => {
        console.log('Processing item:', item);
        html += `<tr>
            <td class="no-col">${index + 1}</td>
            <td>${item.tahun || '-'}</td>
            <td>${item.bulan && typeof item.bulan === 'object' && item.bulan.nama ? item.bulan.nama : (item.bulan || '-')}</td>
            <td>${item.wilayah && typeof item.wilayah === 'object' && item.wilayah.nama ? item.wilayah.nama : (item.wilayah || '-')}</td>
            <td>${item.variabel && typeof item.variabel === 'object' && item.variabel.deskripsi ? item.variabel.deskripsi : (item.variabel || '-')}</td>
            <td>${item.klasifikasi && typeof item.klasifikasi === 'object' && item.klasifikasi.deskripsi ? item.klasifikasi.deskripsi : (item.klasifikasi || '-')}</td>
            <td class="numeric">${item.nilai ? Number(item.nilai).toFixed(2) : '-'}</td>
            <td>${!item.status || item.status === '' ? 'null' : item.status}</td>
        </tr>`;
    });

    html += `
            </tbody>
        </table>
    </body>
    </html>`;

    console.log('Generated HTML length:', html.length);

    // Create hidden iframe
    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';
    document.body.appendChild(iframe);

    const doc = iframe.contentDocument || iframe.contentWindow.document;
    doc.open();
    doc.write(html);
    doc.close();

    iframe.onload = () => {
        try {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        } finally {
            setTimeout(() => iframe.remove(), 2000);
        }
    };
}
</script>
