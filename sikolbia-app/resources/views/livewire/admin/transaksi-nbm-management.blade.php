<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola Transaksi NBM</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data transaksi neraca bahan makanan
                </p>
            </div>
            <div class="flex gap-2">
                <flux:button wire:click="downloadTemplate" variant="outline">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Download Template</span>
                    </div>
                </flux:button>
                <flux:button wire:click="openBulkImportModal" variant="outline">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span>Bulk Import</span>
                    </div>
                </flux:button>
                <flux:button wire:click="openCreateModal" variant="primary">
                    Tambah Transaksi NBM
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded dark:bg-yellow-900/30 dark:border-yellow-700 dark:text-yellow-300">
            {{ session('warning') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif
    <!-- Bulk Import Modal -->
    @if($showBulkImportModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Bulk Import Transaksi NBM</h3>
                <form wire:submit="bulkImport">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Upload File Excel/CSV
                            </label>
                            <input 
                                type="file" 
                                wire:model="importFile" 
                                accept=".xlsx,.xls,.csv"
                                class="block w-full text-sm text-neutral-900 dark:text-neutral-100 border border-neutral-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-neutral-50 dark:bg-neutral-700 focus:outline-none"
                            />
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                Format: XLSX, XLS, atau CSV. Gunakan template yang disediakan.
                            </p>
                            @error('importFile') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if($importFile)
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                <strong>File:</strong> {{ $importFile->getClientOriginalName() }}
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                Ukuran: {{ number_format($importFile->getSize() / 1024, 2) }} KB
                            </p>
                        </div>
                        @endif

                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <p class="text-xs text-yellow-800 dark:text-yellow-300">
                                <strong>Perhatian:</strong> Pastikan file sesuai template. Data yang sudah ada dengan identitas sama (kelompok, komoditi, tahun, bulan) akan dilewati.
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <flux:button type="button" wire:click="closeBulkImportModal" variant="ghost">
                            Batal
                        </flux:button>
                        <flux:button type="submit" variant="primary" :disabled="!$importFile">
                            Upload & Import
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Search & Filter & Per Page -->
    <div class="mb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 no-print">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
            <flux:input 
                wire:model.live="search" 
                placeholder="Cari berdasarkan kelompok, komoditi, tahun..."
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
            @can('export transaksi_nbm')
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
            <flux:button wire:click="print" variant="ghost" class="!px-4">
                Print
            </flux:button>
            <flux:button wire:click="printAll" variant="ghost" class="!px-4">
                Print All
            </flux:button>
            @endcan
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tahun</label>
                <select wire:model.live="filterTahun" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunOptions as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Kelompok</label>
                <select wire:model.live="filterKelompok" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Kelompok</option>
                    @if(isset($kelompokOptions) && is_array($kelompokOptions))
                        @foreach($kelompokOptions as $kelompok)
                            <option value="{{ $kelompok['kode'] }}">{{ $kelompok['nama'] }}</option>
                        @endforeach
                    @else
                        <option disabled>No kelompok data</option>
                    @endif
                </select>
            </div>
            <div wire:key="komoditi-filter-{{ $filterKelompok }}">
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Komoditi</label>
                <select wire:model.live="filterKomoditi" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Komoditi</option>
                    @foreach($this->komoditiOptions as $komoditi)
                        <option value="{{ $komoditi['kode'] }}">{{ $komoditi['nama'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Status Angka</label>
                <select wire:model.live="filterStatusAngka" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Status</option>
                    @foreach($statusAngkaOptions as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div id="transaksi-nbm-table-wrapper" class="bg-white dark:bg-neutral-800 shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                    <tr>
                        <x-sortable-header field="kode_kelompok" :sort-field="$sortField" :sort-direction="$sortDirection" title="Identifikasi" class="px-4 py-3 w-48" />
                        <x-sortable-header field="keluaran" :sort-field="$sortField" :sort-direction="$sortDirection" title="Produksi" class="px-4 py-3 w-40" />
                        <x-sortable-header field="bahan_makanan" :sort-field="$sortField" :sort-direction="$sortDirection" title="Konsumsi & Nutrisi" class="px-4 py-3 w-44" />
                        <x-sortable-header field="harga_konsumen" :sort-field="$sortField" :sort-direction="$sortDirection" title="Ekonomi" class="px-4 py-3 w-36" />
                        <x-sortable-header field="suhu_rata_celsius" :sort-field="$sortField" :sort-direction="$sortDirection" title="Lingkungan" class="px-4 py-3 w-32" />
                        <x-sortable-header field="validation_status" :sort-field="$sortField" :sort-direction="$sortDirection" title="Status" class="px-4 py-3 w-32" />
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider no-print w-32">Aksi</th>
                    </tr>
                </thead>
                                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($transaksiNbms as $index => $transaksi)
                        <tr wire:key="nbm-row-{{ $transaksi->id }}" class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                            <!-- Kolom Identifikasi -->
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-1">
                                    <div class="font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ $transaksi->kode_kelompok }}-{{ $transaksi->kode_komoditi }}
                                    </div>
                                    <div class="text-neutral-600 dark:text-neutral-400">
                                        {{ $transaksi->tahun }}-{{ sprintf('%02d', $transaksi->bulan) }}
                                    </div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                        {{ $transaksi->data_source }}
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Produksi -->
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-1">
                                    <div class="font-semibold text-green-600 dark:text-green-400">
                                        {{ number_format($transaksi->keluaran ?? 0, 1) }} ton
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">
                                        Produksi
                                    </div>
                                    <div class="text-xs text-neutral-700 dark:text-neutral-300">
                                        Impor: {{ number_format($transaksi->impor ?? 0, 0) }}
                                    </div>
                                    <div class="text-xs text-neutral-700 dark:text-neutral-300">
                                        Ekspor: {{ number_format($transaksi->ekspor ?? 0, 0) }}
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Konsumsi & Nutrisi -->
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-1">
                                    <div class="font-semibold text-orange-600 dark:text-orange-400">
                                        {{ number_format($transaksi->kalori_hari ?? 0, 0) }} kal/hari
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">
                                        Protein: {{ number_format($transaksi->protein_hari ?? 0, 1) }}g
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Ekonomi -->
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-1">
                                    <div class="font-semibold text-purple-600 dark:text-purple-400">
                                        Rp {{ number_format($transaksi->harga_konsumen ?? 0, 0) }}
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">
                                        Harga Konsumen
                                    </div>
                                    <div class="text-xs text-neutral-700 dark:text-neutral-300">
                                        Inflasi: {{ number_format(($transaksi->inflasi_komoditi ?? 0) * 100, 2) }}%
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Lingkungan -->
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-1">
                                    <div class="font-semibold text-blue-600 dark:text-blue-400">
                                        {{ number_format($transaksi->suhu_rata_celsius ?? 0, 1) }}°C
                                    </div>
                                    <div class="text-neutral-700 dark:text-neutral-300">
                                        {{ number_format($transaksi->curah_hujan_mm ?? 0, 0) }}mm
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">
                                        Curah Hujan
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Status -->
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-1">
                                    <div class="inline-flex items-center">
                                        @if($transaksi->validation_status === 'verified')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                                verified
                                            </span>
                                        @elseif($transaksi->validation_status === 'pending')
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full">
                                                pending
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-full">
                                                flagged
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">
                                        Score: {{ number_format($transaksi->confidence_score ?? 0, 2) }}
                                    </div>
                                    <div class="text-xs">
                                        @if($transaksi->outlier_flag)
                                            <span class="text-red-600 dark:text-red-400">⚠️ Outlier</span>
                                        @else
                                            <span class="text-green-600 dark:text-green-400">✅ Normal</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="px-4 py-4 text-sm text-neutral-900 dark:text-neutral-100 no-print">
                                <div class="flex flex-col sm:flex-row sm:space-x-1 space-y-1 sm:space-y-0">
                                    <!-- Tombol Detail -->
                                    <flux:button 
                                        wire:click="view({{ $transaksi->id }})" 
                                        variant="ghost" 
                                        size="sm"
                                        class="!px-2 !py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-xs"
                                    >
                                        👁️ Detail
                                    </flux:button>
                                    
                                    <!-- Tombol Edit -->
                                    <flux:button 
                                        wire:click="openEditModal({{ $transaksi->id }})" 
                                        variant="ghost" 
                                        size="sm"
                                        class="!px-2 !py-1 text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-200 text-xs"
                                    >
                                        ✏️ Edit
                                    </flux:button>
                                    
                                    <!-- Tombol Hapus -->
                                    <flux:button 
                                        wire:click="openDeleteModal({{ $transaksi->id }})" 
                                        variant="ghost" 
                                        size="sm"
                                        class="!px-2 !py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 text-xs"
                                    >
                                        🗑️ Hapus
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-neutral-500 dark:text-neutral-400">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data transaksi NBM</p>
                                    <p class="text-sm">Data akan muncul di sini setelah Anda menambahkan transaksi NBM</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksiNbms->hasPages())
            <div class="bg-white dark:bg-neutral-800 px-4 py-3 border-t border-neutral-200 dark:border-neutral-700 sm:px-6 no-print">
                {{ $transaksiNbms->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border border-neutral-200 dark:border-neutral-700 max-w-5xl shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-6">Tambah Transaksi NBM</h3>
                <form wire:submit.prevent="createTransaksi">
                    <div class="max-h-96 overflow-y-auto pr-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <!-- Informasi Dasar -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b mb-1">Informasi Dasar</h4>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kode Kelompok</label>
                                <div class="relative">
                                    <select wire:model.live="kode_kelompok" class="w-full pl-4 pr-10 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Kelompok</option>
                                        @foreach($kelompokOptions as $kelompok)
                                            <option value="{{ $kelompok['kode'] }}">{{ $kelompok['kode'] }} - {{ $kelompok['nama'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400">
                                        <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('kode_kelompok') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kode Komoditi</label>
                                <div class="relative">
                                    <select wire:model="kode_komoditi" class="w-full pl-4 pr-10 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Komoditi</option>
                                        @foreach($this->modalKomoditiOptions as $komoditi)
                                            <option value="{{ $komoditi['kode'] }}">{{ $komoditi['kode'] }} - {{ $komoditi['nama'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400">
                                        <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('kode_komoditi') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tahun</label>
                                <input type="number" wire:model="tahun" min="1900" max="2100" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('tahun') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status Angka</label>
                                <div class="relative">
                                    <select wire:model="status_angka" class="w-full pl-4 pr-10 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Status</option>
                                        <option value="tetap">Tetap</option>
                                        <option value="sementara">Sementara</option>
                                        <option value="sangat sementara">Sangat Sementara</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400">
                                        <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('status_angka') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Data Produksi & Perdagangan -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-4">Data Produksi & Perdagangan</h4>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Masukan</label>
                                <input type="number" step="0.0001" wire:model="masukan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('masukan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Keluaran</label>
                                <input type="number" step="0.0001" wire:model="keluaran" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('keluaran') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Impor</label>
                                <input type="number" step="0.0001" wire:model="impor" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('impor') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Ekspor</label>
                                <input type="number" step="0.0001" wire:model="ekspor" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('ekspor') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Perubahan Stok</label>
                                <input type="number" step="0.0001" wire:model="perubahan_stok" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('perubahan_stok') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Data Penggunaan -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-6">Data Penggunaan</h4>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Pakan</label>
                                <input type="number" step="0.0001" wire:model="pakan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('pakan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bibit</label>
                                <input type="number" step="0.0001" wire:model="bibit" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('bibit') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Makanan</label>
                                <input type="number" step="0.0001" wire:model="makanan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('makanan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bukan Makanan</label>
                                <input type="number" step="0.0001" wire:model="bukan_makanan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('bukan_makanan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tercecer</label>
                                <input type="number" step="0.0001" wire:model="tercecer" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('tercecer') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Penggunaan Lain</label>
                                <input type="number" step="0.0001" wire:model="penggunaan_lain" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('penggunaan_lain') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Data Konsumsi & Gizi -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-6">Data Konsumsi & Gizi</h4>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bahan Makanan</label>
                                <input type="number" step="0.0001" wire:model="bahan_makanan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('bahan_makanan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kg/Tahun</label>
                                <input type="number" step="0.0001" wire:model="kg_tahun" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('kg_tahun') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Gram/Hari</label>
                                <input type="number" step="0.0001" wire:model="gram_hari" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('gram_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kalori/Hari</label>
                                <input type="number" step="0.0001" wire:model="kalori_hari" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('kalori_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Protein/Hari</label>
                                <input type="number" step="0.0001" wire:model="protein_hari" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('protein_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Lemak/Hari</label>
                                <input type="number" step="0.000001" wire:model="lemak_hari" placeholder="0.000000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('lemak_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Field Lengkap Edit Transaksi NBM -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-6">Field Lengkap Edit Transaksi NBM</h4>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bulan</label>
                                <input type="number" wire:model="bulan" min="1" max="12" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('bulan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kuartal</label>
                                <input type="number" wire:model="kuartal" min="1" max="4" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('kuartal') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Periode Data</label>
                                <input type="text" wire:model="periode_data" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('periode_data') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Harga Produsen</label>
                                <input type="number" step="0.0001" wire:model="harga_produsen" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('harga_produsen') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Harga Konsumen</label>
                                <input type="number" step="0.0001" wire:model="harga_konsumen" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('harga_konsumen') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Inflasi Komoditi</label>
                                <input type="number" step="0.0001" wire:model="inflasi_komoditi" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('inflasi_komoditi') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Nilai Tukar USD</label>
                                <input type="number" step="0.0001" wire:model="nilai_tukar_usd" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('nilai_tukar_usd') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Populasi Indonesia</label>
                                <input type="number" step="0.01" wire:model="populasi_indonesia" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('populasi_indonesia') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">GDP Per Kapita</label>
                                <input type="number" step="0.01" wire:model="gdp_per_kapita" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('gdp_per_kapita') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tingkat Kemiskinan</label>
                                <input type="number" step="0.01" wire:model="tingkat_kemiskinan" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('tingkat_kemiskinan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Curah Hujan (mm)</label>
                                <input type="number" step="0.01" wire:model="curah_hujan_mm" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('curah_hujan_mm') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Suhu Rata-rata (C)</label>
                                <input type="number" step="0.01" wire:model="suhu_rata_celsius" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('suhu_rata_celsius') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Indeks El Nino</label>
                                <input type="number" step="0.001" wire:model="indeks_el_nino" placeholder="0.000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('indeks_el_nino') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Luas Panen (Ha)</label>
                                <input type="number" step="0.01" wire:model="luas_panen_ha" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('luas_panen_ha') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Produktivitas (Ton/Ha)</label>
                                <input type="number" step="0.0001" wire:model="produktivitas_ton_ha" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('produktivitas_ton_ha') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kebijakan Impor</label>
                                <input type="text" wire:model="kebijakan_impor" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('kebijakan_impor') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Subsidi Pemerintah</label>
                                <input type="number" step="0.01" wire:model="subsidi_pemerintah" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('subsidi_pemerintah') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Stok Bulog</label>
                                <input type="number" step="0.0001" wire:model="stok_bulog" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('stok_bulog') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Confidence Score</label>
                                <input type="number" step="0.01" wire:model="confidence_score" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('confidence_score') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Data Source</label>
                                <input type="text" wire:model="data_source" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('data_source') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Validation Status</label>
                                <input type="text" wire:model="validation_status" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('validation_status') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Outlier</label>
                                <select wire:model="outlier_flag" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                                @error('outlier_flag') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-8 pt-6">
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

    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border border-neutral-200 dark:border-neutral-700 max-w-5xl shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-6">Edit Transaksi NBM</h3>
                <form wire:submit.prevent="updateTransaksi">
                    <div class="max-h-96 overflow-y-auto pr-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <!-- Informasi Dasar -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2">Informasi Dasar</h4>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kode Kelompok</label>
                                <div class="relative">
                                    <select wire:model.live="kode_kelompok" class="w-full pl-4 pr-10 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Kelompok</option>
                                        @foreach($kelompokOptions as $kelompok)
                                            <option value="{{ $kelompok['kode'] }}">{{ $kelompok['kode'] }} - {{ $kelompok['nama'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400">
                                        <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('kode_kelompok') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kode Komoditi</label>
                                <div class="relative">
                                    <select wire:model="kode_komoditi" class="w-full pl-4 pr-10 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Komoditi</option>
                                        @foreach($this->modalKomoditiOptions as $komoditi)
                                            <option value="{{ $komoditi['kode'] }}">{{ $komoditi['kode'] }} - {{ $komoditi['nama'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400">
                                        <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('kode_komoditi') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tahun</label>
                                <input type="number" wire:model="tahun" min="1900" max="2100" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('tahun') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status Angka</label>
                                <div class="relative">
                                    <select wire:model="status_angka" class="w-full pl-4 pr-10 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Status</option>
                                        <option value="tetap">Tetap</option>
                                        <option value="sementara">Sementara</option>
                                        <option value="sangat sementara">Sangat Sementara</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-400">
                                        <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('status_angka') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Data Produksi & Perdagangan -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-4">Data Produksi & Perdagangan</h4>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Masukan</label>
                                <input type="number" step="0.0001" wire:model="masukan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('masukan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Keluaran</label>
                                <input type="number" step="0.0001" wire:model="keluaran" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('keluaran') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Impor</label>
                                <input type="number" step="0.0001" wire:model="impor" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('impor') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Ekspor</label>
                                <input type="number" step="0.0001" wire:model="ekspor" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('ekspor') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Perubahan Stok</label>
                                <input type="number" step="0.0001" wire:model="perubahan_stok" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('perubahan_stok') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Data Penggunaan -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-6">Data Penggunaan</h4>
                                                       </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Pakan</label>
                                <input type="number" step="0.0001" wire:model="pakan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('pakan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bibit</label>
                                <input type="number" step="0.0001" wire:model="bibit" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('bibit') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Makanan</label>
                                <input type="number" step="0.0001" wire:model="makanan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('makanan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bukan Makanan</label>
                                <input type="number" step="0.0001" wire:model="bukan_makanan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('bukan_makanan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tercecer</label>
                                <input type="number" step="0.0001" wire:model="tercecer" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('tercecer') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Penggunaan Lain</label>
                                <input type="number" step="0.0001" wire:model="penggunaan_lain" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('penggunaan_lain') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Data Konsumsi & Gizi -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-6">Data Konsumsi & Gizi</h4>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bahan Makanan</label>
                                <input type="number" step="0.0001" wire:model="bahan_makanan" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('bahan_makanan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kg/Tahun</label>
                                <input type="number" step="0.0001" wire:model="kg_tahun" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('kg_tahun') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Gram/Hari</label>
                                <input type="number" step="0.0001" wire:model="gram_hari" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('gram_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kalori/Hari</label>
                                <input type="number" step="0.0001" wire:model="kalori_hari" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('kalori_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Protein/Hari</label>
                                <input type="number" step="0.0001" wire:model="protein_hari" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('protein_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Lemak/Hari</label>
                                <input type="number" step="0.000001" wire:model="lemak_hari" placeholder="0.000000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('lemak_hari') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Field Tambahan: Pastikan semua field model tampil di form -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mb-2 mt-6">Field Lengkap Transaksi NBM</h4>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bulan</label>
                                <input type="number" wire:model="bulan" min="1" max="12" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('bulan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kuartal</label>
                                <input type="number" wire:model="kuartal" min="1" max="4" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('kuartal') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Periode Data</label>
                                <input type="text" wire:model="periode_data" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('periode_data') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Harga Produsen</label>
                                <input type="number" step="0.0001" wire:model="harga_produsen" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('harga_produsen') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Harga Konsumen</label>
                                <input type="number" step="0.0001" wire:model="harga_konsumen" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('harga_konsumen') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Inflasi Komoditi</label>
                                <input type="number" step="0.0001" wire:model="inflasi_komoditi" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('inflasi_komoditi') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Nilai Tukar USD</label>
                                <input type="number" step="0.0001" wire:model="nilai_tukar_usd" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('nilai_tukar_usd') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Populasi Indonesia</label>
                                <input type="number" step="0.01" wire:model="populasi_indonesia" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('populasi_indonesia') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">GDP Per Kapita</label>
                                <input type="number" step="0.01" wire:model="gdp_per_kapita" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('gdp_per_kapita') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tingkat Kemiskinan</label>
                                <input type="number" step="0.01" wire:model="tingkat_kemiskinan" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('tingkat_kemiskinan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Curah Hujan (mm)</label>
                                <input type="number" step="0.01" wire:model="curah_hujan_mm" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('curah_hujan_mm') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Suhu Rata-rata (C)</label>
                                <input type="number" step="0.01" wire:model="suhu_rata_celsius" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('suhu_rata_celsius') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Indeks El Nino</label>
                                <input type="number" step="0.001" wire:model="indeks_el_nino" placeholder="0.000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('indeks_el_nino') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Luas Panen (Ha)</label>
                                <input type="number" step="0.01" wire:model="luas_panen_ha" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('luas_panen_ha') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Produktivitas (Ton/Ha)</label>
                                <input type="number" step="0.0001" wire:model="produktivitas_ton_ha" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('produktivitas_ton_ha') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kebijakan Impor</label>
                                <input type="text" wire:model="kebijakan_impor" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('kebijakan_impor') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Subsidi Pemerintah</label>
                                <input type="number" step="0.01" wire:model="subsidi_pemerintah" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('subsidi_pemerintah') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Stok Bulog</label>
                                <input type="number" step="0.0001" wire:model="stok_bulog" placeholder="0.0000" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('stok_bulog') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Confidence Score</label>
                                <input type="number" step="0.01" wire:model="confidence_score" placeholder="0.00" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('confidence_score') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Data Source</label>
                                <input type="text" wire:model="data_source" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('data_source') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Validation Status</label>
                                <input type="text" wire:model="validation_status" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('validation_status') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Outlier</label>
                                <select wire:model="outlier_flag" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                                @error('outlier_flag') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-8 pt-6">
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

    <!-- Delete Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mt-2">Hapus Transaksi NBM</h3>
                <div class="mt-2">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus transaksi NBM ini?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <flux:button type="button" variant="ghost" wire:click="closeDeleteModal">
                        Batal
                    </flux:button>
                    <flux:button type="button" variant="danger" wire:click="deleteTransaksi">
                        Hapus
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- View Details Modal -->
    @if($showViewModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border border-neutral-200 dark:border-neutral-700 max-w-6xl shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Detail Transaksi NBM</h3>
                    <flux:button wire:click="closeViewModal" variant="ghost" size="sm" class="!px-2 !py-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </flux:button>
                </div>
                
                <div class="max-h-96 overflow-y-auto pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- 📋 Informasi Dasar -->
                        <div class="lg:col-span-3">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4">
                                📋 Informasi Dasar
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">ID Transaksi</label>
                                <div class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md text-sm font-mono text-blue-900 dark:text-blue-100">
                                    {{ $viewingTransaksi['id'] ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Kode Kelompok</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm font-mono text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingTransaksi['kode_kelompok'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Kode Komoditi</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm font-mono text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingTransaksi['kode_komoditi'] ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Periode</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ ($viewingTransaksi['tahun'] ?? '') . '-' . sprintf('%02d', $viewingTransaksi['bulan'] ?? 0) }} (Q{{ $viewingTransaksi['kuartal'] ?? '-' }})
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Periode Data</label>
                                <div class="px-3 py-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md text-sm text-orange-900 dark:text-orange-100">
                                    {{ ucfirst($viewingTransaksi['periode_data'] ?? '-') }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Status Angka</label>
                                <div class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md text-sm text-purple-900 dark:text-purple-100">
                                    {{ ucfirst($viewingTransaksi['status_angka'] ?? '-') }}
                                </div>
                            </div>
                        </div>

                        <!-- 🏭 Produksi & Distribusi -->
                        <div class="lg:col-span-3">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                🏭 Produksi & Distribusi
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Masukan</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingTransaksi['masukan']) ? number_format($viewingTransaksi['masukan'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Keluaran</label>
                                <div class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md text-sm font-bold text-blue-900 dark:text-blue-100">
                                    {{ isset($viewingTransaksi['keluaran']) ? number_format($viewingTransaksi['keluaran'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Impor</label>
                                <div class="px-3 py-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md text-sm font-bold text-orange-900 dark:text-orange-100">
                                    {{ isset($viewingTransaksi['impor']) ? number_format($viewingTransaksi['impor'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Ekspor</label>
                                <div class="px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md text-sm font-bold text-red-900 dark:text-red-100">
                                    {{ isset($viewingTransaksi['ekspor']) ? number_format($viewingTransaksi['ekspor'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Perubahan Stok</label>
                                <div class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md text-sm font-bold text-purple-900 dark:text-purple-100">
                                    {{ isset($viewingTransaksi['perubahan_stok']) ? number_format($viewingTransaksi['perubahan_stok'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Pakan</label>
                                <div class="px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md text-sm font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ isset($viewingTransaksi['pakan']) ? number_format($viewingTransaksi['pakan'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Bibit</label>
                                <div class="px-3 py-2 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 rounded-md text-sm font-bold text-teal-900 dark:text-teal-100">
                                    {{ isset($viewingTransaksi['bibit']) ? number_format($viewingTransaksi['bibit'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Makanan</label>
                                <div class="px-3 py-2 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-md text-sm font-bold text-indigo-900 dark:text-indigo-100">
                                    {{ isset($viewingTransaksi['makanan']) ? number_format($viewingTransaksi['makanan'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Bukan Makanan</label>
                                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 rounded-md text-sm font-bold text-gray-900 dark:text-gray-100">
                                    {{ isset($viewingTransaksi['bukan_makanan']) ? number_format($viewingTransaksi['bukan_makanan'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- 🍽️ Konsumsi & Nutrisi -->
                        <div class="lg:col-span-3">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                🍽️ Konsumsi & Nutrisi
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Bahan Makanan</label>
                                <div class="px-3 py-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md text-sm font-bold text-orange-900 dark:text-orange-100">
                                    {{ isset($viewingTransaksi['bahan_makanan']) ? number_format($viewingTransaksi['bahan_makanan'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Konsumsi Tahunan</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingTransaksi['kg_tahun']) ? number_format($viewingTransaksi['kg_tahun'], 2) . ' kg/tahun' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Tercecer</label>
                                <div class="px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md text-sm font-bold text-red-900 dark:text-red-100">
                                    {{ isset($viewingTransaksi['tercecer']) ? number_format($viewingTransaksi['tercecer'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Konsumsi Harian</label>
                                <div class="px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md text-sm font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ isset($viewingTransaksi['gram_hari']) ? number_format($viewingTransaksi['gram_hari'], 1) . ' g/hari' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Kalori</label>
                                <div class="px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md text-sm font-bold text-red-900 dark:text-red-100">
                                    {{ isset($viewingTransaksi['kalori_hari']) ? number_format($viewingTransaksi['kalori_hari'], 0) . ' kal/hari' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Penggunaan Lain</label>
                                <div class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md text-sm font-bold text-purple-900 dark:text-purple-100">
                                    {{ isset($viewingTransaksi['penggunaan_lain']) ? number_format($viewingTransaksi['penggunaan_lain'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Protein</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingTransaksi['protein_hari']) ? number_format($viewingTransaksi['protein_hari'], 1) . ' g/hari' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Lemak</label>
                                <div class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md text-sm font-bold text-blue-900 dark:text-blue-100">
                                    {{ isset($viewingTransaksi['lemak_hari']) ? number_format($viewingTransaksi['lemak_hari'], 2) . ' g/hari' : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- 💰 Ekonomi -->
                        <div class="lg:col-span-3">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                💰 Ekonomi
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Harga Produsen</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingTransaksi['harga_produsen']) ? 'Rp ' . number_format($viewingTransaksi['harga_produsen'], 0, ',', '.') : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Harga Konsumen</label>
                                <div class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md text-sm font-bold text-purple-900 dark:text-purple-100">
                                    {{ isset($viewingTransaksi['harga_konsumen']) ? 'Rp ' . number_format($viewingTransaksi['harga_konsumen'], 0, ',', '.') : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Inflasi Komoditi</label>
                                <div class="px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md text-sm font-bold text-red-900 dark:text-red-100">
                                    {{ isset($viewingTransaksi['inflasi_komoditi']) ? number_format($viewingTransaksi['inflasi_komoditi'] * 100, 2) . '%' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Nilai Tukar USD</label>
                                <div class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md text-sm font-bold text-blue-900 dark:text-blue-100">
                                    {{ isset($viewingTransaksi['nilai_tukar_usd']) ? 'Rp ' . number_format($viewingTransaksi['nilai_tukar_usd'], 0, ',', '.') . '/USD' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Populasi Indonesia</label>
                                <div class="px-3 py-2 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-md text-sm font-bold text-indigo-900 dark:text-indigo-100">
                                    {{ isset($viewingTransaksi['populasi_indonesia']) ? number_format($viewingTransaksi['populasi_indonesia'], 0, ',', '.') . ' jiwa' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">GDP per Kapita</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingTransaksi['gdp_per_kapita']) ? '$' . number_format($viewingTransaksi['gdp_per_kapita'], 0, ',', '.') : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- 🌍 Lingkungan & Pertanian -->
                        <div class="lg:col-span-3">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                🌍 Lingkungan & Pertanian
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Curah Hujan</label>
                                <div class="px-3 py-2 bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-200 dark:border-cyan-800 rounded-md text-sm font-bold text-cyan-900 dark:text-cyan-100">
                                    {{ isset($viewingTransaksi['curah_hujan_mm']) ? number_format($viewingTransaksi['curah_hujan_mm'], 0) . ' mm' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Suhu Rata-rata</label>
                                <div class="px-3 py-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md text-sm font-bold text-orange-900 dark:text-orange-100">
                                    {{ isset($viewingTransaksi['suhu_rata_celsius']) ? number_format($viewingTransaksi['suhu_rata_celsius'], 1) . '°C' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Indeks El Niño</label>
                                <div class="px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md text-sm font-bold text-red-900 dark:text-red-100">
                                    {{ isset($viewingTransaksi['indeks_el_nino']) ? number_format($viewingTransaksi['indeks_el_nino'], 2) : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Luas Panen</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingTransaksi['luas_panen_ha']) ? number_format($viewingTransaksi['luas_panen_ha'], 0) . ' ha' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Produktivitas</label>
                                <div class="px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md text-sm font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ isset($viewingTransaksi['produktivitas_ton_ha']) ? number_format($viewingTransaksi['produktivitas_ton_ha'], 2) . ' ton/ha' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Tingkat Kemiskinan</label>
                                <div class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md text-sm font-bold text-purple-900 dark:text-purple-100">
                                    {{ isset($viewingTransaksi['tingkat_kemiskinan']) ? number_format($viewingTransaksi['tingkat_kemiskinan'], 2) . '%' : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- 📊 Kebijakan & Validasi -->
                        <div class="lg:col-span-3">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                📊 Kebijakan & Validasi
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Kebijakan Impor</label>
                                <div class="px-3 py-2 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-md text-sm font-bold text-indigo-900 dark:text-indigo-100">
                                    @if(isset($viewingTransaksi['kebijakan_impor']))
                                        @if($viewingTransaksi['kebijakan_impor'] === 'bebas')
                                            🟢 {{ ucfirst($viewingTransaksi['kebijakan_impor']) }}
                                        @elseif($viewingTransaksi['kebijakan_impor'] === 'terbatas')
                                            🟡 {{ ucfirst($viewingTransaksi['kebijakan_impor']) }}
                                        @else
                                            🔴 {{ ucfirst($viewingTransaksi['kebijakan_impor']) }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Subsidi Pemerintah</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingTransaksi['subsidi_pemerintah']) ? 'Rp ' . number_format($viewingTransaksi['subsidi_pemerintah'], 0, ',', '.') . ' miliar' : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Stok Bulog</label>
                                <div class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md text-sm font-bold text-blue-900 dark:text-blue-100">
                                    {{ isset($viewingTransaksi['stok_bulog']) ? number_format($viewingTransaksi['stok_bulog'], 2) . ' ton' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Status Validasi</label>
                                <div class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-md text-sm font-bold text-purple-900 dark:text-purple-100">
                                    @if(isset($viewingTransaksi['validation_status']))
                                        @if($viewingTransaksi['validation_status'] === 'verified')
                                            ✅ Verified
                                        @elseif($viewingTransaksi['validation_status'] === 'pending')
                                            ⏳ Pending
                                        @else
                                            ⚠️ Flagged
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Confidence Score</label>
                                <div class="px-3 py-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md text-sm font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ isset($viewingTransaksi['confidence_score']) ? number_format($viewingTransaksi['confidence_score'], 2) . '/1.00' : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Data Source</label>
                                <div class="px-3 py-2 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 rounded-md text-sm font-bold text-teal-900 dark:text-teal-100">
                                    {{ $viewingTransaksi['data_source'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                    <flux:button type="button" wire:click="closeViewModal" variant="ghost">
                        Tutup
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
        window.addEventListener('print-transaksi-nbm', function () {
            const wrap = document.getElementById('transaksi-nbm-table-wrapper');
            if (!wrap) { 
                window.print(); 
                return; 
            }

            // Clone content & strip elements not for print
            const clone = wrap.cloneNode(true);
            clone.querySelectorAll('.no-print, nav').forEach(el => el.remove());

            const html = `<!DOCTYPE html><html><head><title>Kelola Transaksi NBM</title><meta charset='utf-8'>
                <style>
                    *{box-sizing:border-box;}
                    body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Arial,sans-serif;margin:0;padding:24px;color:#111827;}
                    .header{text-align:center;margin-bottom:30px;border-bottom:2px solid #059669;padding-bottom:20px;}
                    .logo{width:60px;height:60px;margin:0 auto 15px;}
                    .dept-name{font-size:16px;font-weight:600;color:#059669;margin-bottom:5px;}
                    .dept-info{font-size:12px;color:#374151;margin-bottom:3px;}
                    .report-title{font-size:18px;font-weight:700;color:#111827;margin-top:15px;}
                    table{width:100%;border-collapse:collapse;font-size:11px;margin-top:20px;}
                    th,td{border:1px solid #e5e7eb;padding:4px 6px;text-align:left;vertical-align:top;}
                    th{background:#f3f4f6;font-weight:600;font-size:10px;text-transform:uppercase;letter-spacing:.05em;}
                    .no-col{width:30px;text-align:center;}
                    .numeric{text-align:right;}
                    @media print { 
                        body{padding:8px;} 
                        .header{margin-bottom:20px;padding-bottom:15px;}
                        .logo{width:50px;height:50px;}
                        .dept-name{font-size:14px;}
                        .dept-info{font-size:10px;}
                        .report-title{font-size:16px;}
                        table{font-size:9px;}
                        th,td{padding:3px 4px;}
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
                    <div class="report-title">LAPORAN DATA TRANSAKSI NBM<br>(Neraca Bahan Makanan)</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th class="no-col">No</th>
                            <th>Identifikasi</th>
                            <th>Produksi</th>
                            <th>Konsumsi & Nutrisi</th>
                            <th>Ekonomi</th>
                            <th>Lingkungan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Array.from(clone.querySelectorAll('tbody tr')).map((row, index) => {
                            const cells = Array.from(row.children);
                            if (cells.length === 1) return row.outerHTML;
                            
                            const extractText = (cell) => cell.textContent.replace(/\s+/g, ' ').trim();
                            const cellsHtml = cells.slice(0, 6).map(cell => `<td>${extractText(cell)}</td>`).join('');
                            return `<tr><td class="no-col">${index + 1}</td>${cellsHtml}</tr>`;
                        }).join('')}
                    </tbody>
                </table>
            </body></html>`;

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
        });

        window.addEventListener('print-all-transaksi-nbm', function (event) {
            const allData = event.detail.data;
            printAllTransaksiNbmData(allData);
        });

        function printAllTransaksiNbmData(allData) {
            let html = `<!DOCTYPE html>
            <html>
            <head>
                <title>Semua Data Transaksi NBM</title>
                <meta charset='utf-8'>
                <style>
                    *{box-sizing:border-box;}
                    body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Arial,sans-serif;margin:0;padding:16px;color:#111827;font-size:10px;}
                    .header{text-align:center;margin-bottom:25px;border-bottom:2px solid #059669;padding-bottom:15px;}
                    .logo{width:50px;height:50px;margin:0 auto 12px;}
                    .dept-name{font-size:14px;font-weight:600;color:#059669;margin-bottom:4px;}
                    .dept-info{font-size:10px;color:#374151;margin-bottom:2px;}
                    .report-title{font-size:16px;font-weight:700;color:#111827;margin-top:12px;}
                    table{width:100%;border-collapse:collapse;font-size:9px;margin-top:15px;}
                    th,td{border:1px solid #e5e7eb;padding:3px 4px;text-align:left;vertical-align:top;}
                    th{background:#f3f4f6;font-weight:600;font-size:8px;text-transform:uppercase;letter-spacing:.02em;}
                    .no-col{width:25px;text-align:center;}
                    .numeric{text-align:right;}
                    @media print { 
                        body{padding:8px;font-size:8px;} 
                        .header{margin-bottom:15px;padding-bottom:10px;}
                        .logo{width:40px;height:40px;}
                        .dept-name{font-size:12px;}
                        .dept-info{font-size:8px;}
                        .report-title{font-size:14px;}
                        table{font-size:7px;}
                        th,td{padding:2px 3px;}
                        .no-col{width:20px;}
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
                    <div class="report-title">LAPORAN SEMUA DATA TRANSAKSI NBM<br>(Neraca Bahan Makanan)</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th class="no-col">No</th>
                            <th>Identifikasi</th>
                            <th>Produksi</th>
                            <th>Konsumsi & Nutrisi</th>
                            <th>Ekonomi</th>
                            <th>Lingkungan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    </tbody>`;

            if (allData && Array.isArray(allData)) {
                allData.forEach((transaksi, index) => {
                    const identifikasi = `${transaksi.kode_kelompok || ''}-${transaksi.kode_komoditi || ''} ${transaksi.tahun || ''}-${String(transaksi.bulan || '').padStart(2, '0')} ${transaksi.data_source || ''}`;
                    const produksi = `${Number(transaksi.keluaran || 0).toFixed(1)} ton Produksi Impor: ${Number(transaksi.impor || 0).toFixed(0)} Ekspor: ${Number(transaksi.ekspor || 0).toFixed(0)}`;
                    const konsumsi = `${Number(transaksi.gram_hari || 0).toFixed(1)} g/hari ${Number(transaksi.kalori_hari || 0).toFixed(0)} kal/hari Protein: ${Number(transaksi.protein_hari || 0).toFixed(1)}g`;
                    const ekonomi = `Rp ${Number(transaksi.harga_konsumen || 0).toLocaleString('id-ID')} Harga Konsumen Inflasi: ${(Number(transaksi.inflasi_komoditi || 0) * 100).toFixed(2)}%`;
                    const lingkungan = `${Number(transaksi.suhu_rata_celsius || 0).toFixed(1)}°C ${Number(transaksi.curah_hujan_mm || 0).toFixed(0)}mm Curah Hujan`;
                    const status = `${transaksi.validation_status || ''} Score: ${Number(transaksi.confidence_score || 0).toFixed(2)} ${transaksi.outlier_flag ? '⚠️ Outlier' : '✅ Normal'}`;
                    
                    html += `<tr>
                        <td class="no-col">${index + 1}</td>
                        <td>${identifikasi}</td>
                        <td>${produksi}</td>
                        <td>${konsumsi}</td>
                        <td>${ekonomi}</td>
                        <td>${lingkungan}</td>
                        <td>${status}</td>
                    </tr>`;
                });
            }

            html += `
                    </tbody>
                </table>
            </body>
            </html>`;

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
    @endscript
</div>
