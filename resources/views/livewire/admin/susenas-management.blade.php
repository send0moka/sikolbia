<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola Data Susenas</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data konsumsi pangan dari survei Susenas
                </p>
            </div>
            <flux:button wire:click="create" variant="primary">
                Tambah Data Susenas
            </flux:button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search & Filter & Per Page -->
    <div class="mb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 no-print">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
            <flux:input 
                wire:model.live="search" 
                placeholder="Cari data susenas..."
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
            <flux:button wire:click="print" variant="ghost" class="!px-4">
                Print
            </flux:button>
            <flux:button wire:click="printAll" variant="ghost" class="!px-4">
                Print All
            </flux:button>
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
                    @foreach($tahunOptions as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Kelompok BPS</label>
                <select wire:model.live="filterKelompokbps" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Kelompok BPS</option>
                    @foreach($kelompokbps as $kelompok)
                        <option value="{{ $kelompok->kd_kelompokbps }}">{{ $kelompok->nm_kelompokbps }}</option>
                    @endforeach
                </select>
            </div>
            <div wire:key="komoditi-filter-susenas-{{ $filterKelompokbps }}">
                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Komoditi BPS</label>
                <select wire:model.live="filterKomoditibps" wire:key="select-komoditi-{{ $filterKelompokbps }}" class="w-full text-sm rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent">
                    <option value="">Semua Komoditi BPS</option>
                    @foreach($komoditibps as $komoditi)
                        <option value="{{ $komoditi->kd_komoditibps }}">{{ $komoditi->nm_komoditibps }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div id="susenas-table-wrapper" class="bg-white dark:bg-neutral-800 shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            No
                        </th>
                        <x-sortable-header field="id" :sort-field="$sortField" :sort-direction="$sortDirection" title="ID" class="px-6 py-3" />
                        <x-sortable-header field="kd_kelompokbps" :sort-field="$sortField" :sort-direction="$sortDirection" title="Kelompok BPS" class="px-6 py-3" />
                        <x-sortable-header field="kd_komoditibps" :sort-field="$sortField" :sort-direction="$sortDirection" title="Komoditi BPS" class="px-6 py-3" />
                        <x-sortable-header field="tahun" :sort-field="$sortField" :sort-direction="$sortDirection" title="Tahun" class="px-6 py-3" />
                        <x-sortable-header field="Satuan" :sort-field="$sortField" :sort-direction="$sortDirection" title="Satuan" class="px-6 py-3" />
                        <x-sortable-header field="konsumsikuantity" :sort-field="$sortField" :sort-direction="$sortDirection" title="Konsumsi Kuantitas" class="px-6 py-3" />
                        <x-sortable-header field="konsumsinilai" :sort-field="$sortField" :sort-direction="$sortDirection" title="Konsumsi Nilai" class="px-6 py-3" />
                        <x-sortable-header field="konsumsigizi" :sort-field="$sortField" :sort-direction="$sortDirection" title="Konsumsi Gizi" class="px-6 py-3" />
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider no-print">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($susenas as $index => $item)
                        <tr wire:key="susenas-row-{{ $item->id }}" class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100 text-center">
                                {{ ($susenas->currentPage() - 1) * $susenas->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100 text-center">
                                {{ $item->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                                <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $item->kelompokbps->nm_kelompokbps ?? '-' }}
                                </div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $item->kd_kelompokbps }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                                <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $item->komoditibps->nm_komoditibps ?? '-' }}
                                </div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $item->kd_komoditibps }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->tahun }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->Satuan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                {{ number_format($item->konsumsikuantity, 2) ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->konsumsinilai ? number_format($item->konsumsinilai, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->konsumsigizi ? number_format($item->konsumsigizi, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium no-print">
                                <div class="flex items-center justify-end space-x-2">
                                    <flux:button wire:key="view-{{ $item->id }}" wire:click="view({{ $item->id }})" variant="ghost" size="sm">
                                        Lihat
                                    </flux:button>
                                    @can('edit susenas')
                                    <flux:button wire:key="edit-{{ $item->id }}" wire:click="edit({{ $item->id }})" variant="ghost" size="sm">
                                        Edit
                                    </flux:button>
                                    @endcan
                                    @can('delete susenas')
                                    <flux:button wire:key="delete-{{ $item->id }}" wire:click="confirmDelete({{ $item->id }})" variant="danger" size="sm">
                                        Hapus
                                    </flux:button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-neutral-500 dark:text-neutral-400">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-neutral-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                    <p class="text-neutral-500 dark:text-neutral-400 font-medium">Tidak ada data Susenas</p>
                                    <p class="text-neutral-400 dark:text-neutral-500 text-sm mt-1">Klik "Tambah Data Susenas" untuk menambah data baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($susenas->hasPages())
            <div class="bg-white dark:bg-neutral-800 px-4 py-3 border-t border-neutral-200 dark:border-neutral-700 sm:px-6 no-print">
                {{ $susenas->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border border-neutral-200 dark:border-neutral-700 max-w-4xl shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-6">Tambah Data Susenas</h3>
                <form wire:submit="save">
                    <div class="max-h-96 overflow-y-auto pr-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Informasi Dasar -->
                            <div class="md:col-span-2">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b">Informasi Dasar</h4>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tahun *</label>
                                <input type="number" wire:model="tahun" min="1900" max="2100" placeholder="Masukkan tahun" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('tahun') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kelompok BPS *</label>
                                <div class="relative">
                                    <select wire:model.live="kd_kelompokbps" class="w-full px-4 py-3 pr-10 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Kelompok BPS</option>
                                        @foreach($kelompokbps as $kelompok)
                                            <option value="{{ $kelompok->kd_kelompokbps }}">
                                                {{ $kelompok->kd_kelompokbps }} - {{ $kelompok->nm_kelompokbps }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('kd_kelompokbps') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Komoditi BPS *</label>
                                <div class="relative">
                                    <select wire:model="kd_komoditibps" class="w-full px-4 py-3 pr-10 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">{{ $kd_kelompokbps ? 'Pilih Komoditi BPS' : 'Pilih kelompok BPS terlebih dahulu' }}</option>
                                        @foreach($this->modalKomoditiOptions as $komoditi)
                                            <option value="{{ $komoditi->kd_komoditibps }}">
                                                {{ $komoditi->kd_komoditibps }} - {{ $komoditi->nm_komoditibps }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('kd_komoditibps') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Satuan</label>
                                <input type="text" wire:model="Satuan" placeholder="Masukkan satuan (kg, liter, dll.)" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('Satuan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Data Konsumsi -->
                            <div class="md:col-span-2">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mt-4">Data Konsumsi</h4>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Konsumsi Kuantitas *</label>
                                <input type="number" step="0.01" min="0" wire:model="konsumsikuantity" placeholder="Masukkan nilai konsumsi kuantitas" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('konsumsikuantity') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Konsumsi Nilai</label>
                                <input type="number" step="0.01" min="0" wire:model="konsumsinilai" placeholder="Masukkan nilai konsumsi dalam rupiah" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('konsumsinilai') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Konsumsi Gizi</label>
                                <input type="number" step="0.01" min="0" wire:model="konsumsigizi" placeholder="Masukkan nilai konsumsi gizi" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('konsumsigizi') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
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
        <div class="relative top-10 mx-auto p-6 border border-neutral-200 dark:border-neutral-700 max-w-4xl shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-6">Edit Data Susenas</h3>
                <form wire:submit="save">
                    <div class="max-h-96 overflow-y-auto pr-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Informasi Dasar -->
                            <div class="md:col-span-2">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b">Informasi Dasar</h4>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tahun *</label>
                                <input type="number" wire:model="tahun" min="1900" max="2100" placeholder="Masukkan tahun" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm" />
                                @error('tahun') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kelompok BPS *</label>
                                <div class="relative">
                                    <select wire:model.live="kd_kelompokbps" class="w-full px-4 py-3 pr-10 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">Pilih Kelompok BPS</option>
                                        @foreach($kelompokbps as $kelompok)
                                            <option value="{{ $kelompok->kd_kelompokbps }}">
                                                {{ $kelompok->kd_kelompokbps }} - {{ $kelompok->nm_kelompokbps }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('kd_kelompokbps') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Komoditi BPS *</label>
                                <div class="relative">
                                    <select wire:model="kd_komoditibps" class="w-full px-4 py-3 pr-10 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm appearance-none">
                                        <option value="">{{ $kd_kelompokbps ? 'Pilih Komoditi BPS' : 'Pilih kelompok BPS terlebih dahulu' }}</option>
                                        @foreach($this->modalKomoditiOptions as $komoditi)
                                            <option value="{{ $komoditi->kd_komoditibps }}">
                                                {{ $komoditi->kd_komoditibps }} - {{ $komoditi->nm_komoditibps }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('kd_komoditibps') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Satuan</label>
                                <input type="text" wire:model="Satuan" placeholder="Masukkan satuan (kg, liter, dll.)" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('Satuan') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Data Konsumsi -->
                            <div class="md:col-span-2">
                                <h4 class="font-medium text-neutral-900 dark:text-white border-b pb-2 mt-4">Data Konsumsi</h4>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Konsumsi Kuantitas *</label>
                                <input type="number" step="0.01" min="0" wire:model="konsumsikuantity" placeholder="Masukkan nilai konsumsi kuantitas" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('konsumsikuantity') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Konsumsi Nilai</label>
                                <input type="number" step="0.01" min="0" wire:model="konsumsinilai" placeholder="Masukkan nilai konsumsi dalam rupiah" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('konsumsinilai') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Konsumsi Gizi</label>
                                <input type="number" step="0.01" min="0" wire:model="konsumsigizi" placeholder="Masukkan nilai konsumsi gizi" class="w-full px-4 py-3 rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200 focus:ring-accent focus:border-accent text-sm placeholder-neutral-400" />
                                @error('konsumsigizi') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-8 pt-6">
                        <flux:button type="button" wire:click="closeEditModal" variant="ghost">
                            Batal
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            Perbarui
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
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mt-2">Hapus Data Susenas</h3>
                <div class="mt-2">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus data Susenas ini?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <flux:button type="button" wire:click="closeDeleteModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button wire:click="delete" variant="danger">
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
        <div class="relative top-10 mx-auto p-6 border border-neutral-200 dark:border-neutral-700 max-w-4xl shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Detail Data Susenas</h3>
                    <flux:button wire:click="closeViewModal" variant="ghost" size="sm" class="!px-2 !py-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </flux:button>
                </div>
                
                <div class="max-h-96 overflow-y-auto pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Informasi Identitas -->
                        <div class="md:col-span-2">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4">
                                <svg class="inline w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Informasi Identitas
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">ID Susenas</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['id'] ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Tahun</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['tahun'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Tanggal Dibuat</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ isset($viewingSusenas['created_at']) ? \Carbon\Carbon::parse($viewingSusenas['created_at'])->format('d F Y H:i') : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Terakhir Diperbarui</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ isset($viewingSusenas['updated_at']) ? \Carbon\Carbon::parse($viewingSusenas['updated_at'])->format('d F Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Kelompok & Komoditi -->
                        <div class="md:col-span-2">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                <svg class="inline w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Informasi Kelompok & Komoditi
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Kode Kelompok BPS</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm font-mono text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['kd_kelompokbps'] ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Nama Kelompok BPS</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['kelompokbps']['nm_kelompokbps'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Kode Komoditi BPS</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm font-mono text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['kd_komoditibps'] ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Nama Komoditi BPS</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['komoditibps']['nm_komoditibps'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Satuan</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['Satuan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Konsumsi -->
                        <div class="md:col-span-2">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                <svg class="inline w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Data Konsumsi
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Konsumsi Kuantitas</label>
                                <div class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md text-sm font-bold text-blue-900 dark:text-blue-100">
                                    {{ isset($viewingSusenas['konsumsikuantity']) ? number_format($viewingSusenas['konsumsikuantity'], 2) : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Konsumsi Nilai (Rupiah)</label>
                                <div class="px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-sm font-bold text-green-900 dark:text-green-100">
                                    {{ isset($viewingSusenas['konsumsinilai']) && $viewingSusenas['konsumsinilai'] ? 'Rp ' . number_format($viewingSusenas['konsumsinilai'], 2) : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Konsumsi Gizi</label>
                                <div class="px-3 py-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md text-sm font-bold text-orange-900 dark:text-orange-100">
                                    {{ isset($viewingSusenas['konsumsigizi']) && $viewingSusenas['konsumsigizi'] ? number_format($viewingSusenas['konsumsigizi'], 2) : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Information -->
                        @if(isset($viewingSusenas['status']) || isset($viewingSusenas['keterangan']))
                        <div class="md:col-span-2">
                            <h4 class="font-semibold text-neutral-900 dark:text-white border-b border-neutral-200 dark:border-neutral-600 pb-2 mb-4 mt-6">
                                <svg class="inline w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Tambahan
                            </h4>
                        </div>
                        
                        @if(isset($viewingSusenas['status']))
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Status</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ $viewingSusenas['status'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($viewingSusenas['keterangan']))
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">Keterangan</label>
                                <div class="px-3 py-2 bg-neutral-50 dark:bg-neutral-700/50 border border-neutral-200 dark:border-neutral-600 rounded-md text-sm text-neutral-900 dark:text-neutral-100 min-h-[60px] whitespace-pre-wrap">
                                    {{ $viewingSusenas['keterangan'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
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
</div>

@script
<script>
    window.addEventListener('print-susenas', function () {
        const wrap = document.getElementById('susenas-table-wrapper');
        if (!wrap) { 
            window.print(); 
            return; 
        }

        // Clone content & strip elements not for print
        const clone = wrap.cloneNode(true);
        clone.querySelectorAll('.no-print, nav').forEach(el => el.remove());

        const html = `<!DOCTYPE html><html><head><title>Data Susenas</title><meta charset='utf-8'>
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
                <div class="report-title">LAPORAN DATA SUSENAS<br>(Survei Sosial Ekonomi Nasional)</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        <th>ID</th>
                        <th>Kelompok BPS</th>
                        <th>Komoditi BPS</th>
                        <th>Tahun</th>
                        <th>Satuan</th>
                        <th>Konsumsi Kuantitas</th>
                        <th>Konsumsi Nilai</th>
                        <th>Konsumsi Gizi</th>
                    </tr>
                </thead>
                <tbody>
                    ${Array.from(clone.querySelectorAll('tbody tr')).map((row, index) => {
                        const cells = Array.from(row.children);
                        // Skip first cell (nomor) and find cells without no-print class
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
    });

    window.addEventListener('print-all-susenas', function (event) {
        const allData = event.detail.data;
        printAllSusenasData(allData);
    });

    function printAllSusenasData(allData) {
        let html = `<!DOCTYPE html>
        <html>
        <head>
            <title>Semua Data Susenas</title>
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
                <div class="report-title">LAPORAN SEMUA DATA SUSENAS<br>(Survei Sosial Ekonomi Nasional)</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        <th>ID</th>
                        <th>Kelompok BPS</th>
                        <th>Komoditi BPS</th>
                        <th>Tahun</th>
                        <th>Satuan</th>
                        <th>Konsumsi Kuantitas</th>
                        <th>Konsumsi Nilai</th>
                        <th>Konsumsi Gizi</th>
                    </tr>
                </thead>
                <tbody>`;

        if (allData && Array.isArray(allData)) {
            allData.forEach((item, index) => {
                html += `<tr>
                    <td class="no-col">${index + 1}</td>
                    <td class="numeric">${item.id}</td>
                    <td>${item.kelompokbps?.nm_kelompokbps || '-'}</td>
                    <td>${item.komoditibps?.nm_komoditibps || '-'}</td>
                    <td>${item.tahun}</td>
                    <td>${item.Satuan || '-'}</td>
                    <td class="numeric">${Number(item.konsumsikuantity || 0).toFixed(2)}</td>
                    <td class="numeric">${item.konsumsinilai ? Number(item.konsumsinilai).toFixed(2) : '-'}</td>
                    <td class="numeric">${item.konsumsigizi ? Number(item.konsumsigizi).toFixed(2) : '-'}</td>
                </tr>`;
            });
        }

        html += `
                </tbody>
            </table>
        </body>
        </html>`;

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
@endscript
