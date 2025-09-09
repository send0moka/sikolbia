<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola Komoditi</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data komoditi pangan
                </p>
            </div>
            <flux:button wire:click="openCreateModal" variant="primary">
                Tambah Komoditi
            </flux:button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search & Per Page -->
    <div class="mb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
            <flux:input 
                wire:model.live="search" 
                placeholder="Cari berdasarkan kode kelompok, kode komoditi, atau nama..."
                class="w-full sm:max-w-sm"
            />
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
        </div>
    </div>

    <!-- Komoditi Table -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700" id="komoditi-table-wrapper">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400" id="komoditi-table">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Kode Kelompok</th>
                        <th scope="col" class="px-6 py-3">Kode Komoditi</th>
                        <th scope="col" class="px-6 py-3">Nama Komoditi</th>
                        <th scope="col" class="px-6 py-3">Satuan Dasar</th>
                        <th scope="col" class="px-6 py-3">Kalori/100g</th>
                        <th scope="col" class="px-6 py-3">Protein/100g</th>
                        <th scope="col" class="px-6 py-3">Lemak/100g</th>
                        <th scope="col" class="px-6 py-3">Karbohidrat/100g</th>
                        <th scope="col" class="px-6 py-3">Serat/100g</th>
                        <th scope="col" class="px-6 py-3">Vitamin C/100g</th>
                        <th scope="col" class="px-6 py-3">Zat Besi/100g</th>
                        <th scope="col" class="px-6 py-3">Kalsium/100g</th>
                        <th scope="col" class="px-6 py-3">Musim Panen</th>
                        <th scope="col" class="px-6 py-3">Asal Produksi</th>
                        <th scope="col" class="px-6 py-3">Shelf Life (hari)</th>
                        <th scope="col" class="px-6 py-3">Harga Rata/kg</th>
                        <th scope="col" class="px-6 py-3">Dibuat</th>
                        <th scope="col" class="px-6 py-3 no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($komoditis as $komoditi)
                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">{{ $komoditi->kode_kelompok }}</td>
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">{{ $komoditi->kode_komoditi }}</td>
                        <td class="px-6 py-4">{{ $komoditi->nama }}</td>
                        <td class="px-6 py-4">{{ $komoditi->satuan_dasar }}</td>
                        <td class="px-6 py-4">{{ $komoditi->kalori_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->protein_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->lemak_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->karbohidrat_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->serat_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->vitamin_c_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->zat_besi_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->kalsium_per_100g }}</td>
                        <td class="px-6 py-4">{{ $komoditi->musim_panen }}</td>
                        <td class="px-6 py-4">{{ $komoditi->asal_produksi }}</td>
                        <td class="px-6 py-4">{{ $komoditi->shelf_life_hari }}</td>
                        <td class="px-6 py-4">{{ $komoditi->harga_rata_per_kg }}</td>
                        <td class="px-6 py-4">{{ $komoditi->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 no-print">
                            <div class="flex space-x-2">
                                <flux:button wire:click="openEditModal({{ $komoditi->id }})" variant="ghost" size="sm">Edit</flux:button>
                                <flux:button wire:click="openDeleteModal({{ $komoditi->id }})" variant="danger" size="sm">Hapus</flux:button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="18" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">Tidak ada komoditi ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="text-xs text-neutral-600 dark:text-neutral-400 md:mr-auto">
                Menampilkan
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $komoditis->firstItem() }}</span>
                -
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $komoditis->lastItem() }}</span>
                dari
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $komoditis->total() }}</span>
                komoditi
            </div>
            {{ $komoditis->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Create Komoditi Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Tambah Komoditi</h3>
                <form wire:submit="createKomoditi">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Kode Kelompok</label>
                            <select wire:model="kode_kelompok" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->kode }}">{{ $kelompok->kode }} - {{ $kelompok->nama }}</option>
                                @endforeach
                            </select>
                            @error('kode_kelompok') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <flux:input wire:model="kode_komoditi" label="Kode Komoditi" placeholder="Masukkan kode komoditi" required />
                        @error('kode_komoditi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="nama" label="Nama Komoditi" placeholder="Masukkan nama komoditi" required />
                        @error('nama') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="satuan_dasar" label="Satuan Dasar" placeholder="Contoh: kg, gram, liter" required />
                        @error('satuan_dasar') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="kalori_per_100g" label="Kalori per 100g" type="number" step="0.01" placeholder="Kalori per 100g" />
                        @error('kalori_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="protein_per_100g" label="Protein per 100g" type="number" step="0.01" placeholder="Protein per 100g" />
                        @error('protein_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="lemak_per_100g" label="Lemak per 100g" type="number" step="0.01" placeholder="Lemak per 100g" />
                        @error('lemak_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="karbohidrat_per_100g" label="Karbohidrat per 100g" type="number" step="0.01" placeholder="Karbohidrat per 100g" />
                        @error('karbohidrat_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="serat_per_100g" label="Serat per 100g" type="number" step="0.01" placeholder="Serat per 100g" />
                        @error('serat_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="vitamin_c_per_100g" label="Vitamin C per 100g" type="number" step="0.01" placeholder="Vitamin C per 100g" />
                        @error('vitamin_c_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="zat_besi_per_100g" label="Zat Besi per 100g" type="number" step="0.01" placeholder="Zat Besi per 100g" />
                        @error('zat_besi_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="kalsium_per_100g" label="Kalsium per 100g" type="number" step="0.01" placeholder="Kalsium per 100g" />
                        @error('kalsium_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="musim_panen" label="Musim Panen" placeholder="Contoh: Jan-Mar, Apr-Jun" />
                        @error('musim_panen') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="asal_produksi" label="Asal Produksi" placeholder="Contoh: lokal, impor" />
                        @error('asal_produksi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="shelf_life_hari" label="Shelf Life (hari)" type="number" placeholder="Shelf life dalam hari" />
                        @error('shelf_life_hari') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="harga_rata_per_kg" label="Harga Rata-rata per Kg" type="number" step="0.01" placeholder="Harga rata-rata per kg" />
                        @error('harga_rata_per_kg') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
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

    <!-- Edit Komoditi Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Edit Komoditi</h3>
                <form wire:submit="updateKomoditi">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Kode Kelompok</label>
                            <select wire:model="kode_kelompok" class="w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 focus:ring-accent focus:border-accent" required>
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->kode }}">{{ $kelompok->kode }} - {{ $kelompok->nama }}</option>
                                @endforeach
                            </select>
                            @error('kode_kelompok') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <flux:input wire:model="kode_komoditi" label="Kode Komoditi" placeholder="Masukkan kode komoditi" required />
                        @error('kode_komoditi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="nama" label="Nama Komoditi" placeholder="Masukkan nama komoditi" required />
                        @error('nama') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="satuan_dasar" label="Satuan Dasar" placeholder="Contoh: kg, gram, liter" required />
                        @error('satuan_dasar') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="kalori_per_100g" label="Kalori per 100g" type="number" step="0.01" placeholder="Kalori per 100g" />
                        @error('kalori_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="protein_per_100g" label="Protein per 100g" type="number" step="0.01" placeholder="Protein per 100g" />
                        @error('protein_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="lemak_per_100g" label="Lemak per 100g" type="number" step="0.01" placeholder="Lemak per 100g" />
                        @error('lemak_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="karbohidrat_per_100g" label="Karbohidrat per 100g" type="number" step="0.01" placeholder="Karbohidrat per 100g" />
                        @error('karbohidrat_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="serat_per_100g" label="Serat per 100g" type="number" step="0.01" placeholder="Serat per 100g" />
                        @error('serat_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="vitamin_c_per_100g" label="Vitamin C per 100g" type="number" step="0.01" placeholder="Vitamin C per 100g" />
                        @error('vitamin_c_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="zat_besi_per_100g" label="Zat Besi per 100g" type="number" step="0.01" placeholder="Zat Besi per 100g" />
                        @error('zat_besi_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="kalsium_per_100g" label="Kalsium per 100g" type="number" step="0.01" placeholder="Kalsium per 100g" />
                        @error('kalsium_per_100g') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="musim_panen" label="Musim Panen" placeholder="Contoh: Jan-Mar, Apr-Jun" />
                        @error('musim_panen') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="asal_produksi" label="Asal Produksi" placeholder="Contoh: lokal, impor" />
                        @error('asal_produksi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="shelf_life_hari" label="Shelf Life (hari)" type="number" placeholder="Shelf life dalam hari" />
                        @error('shelf_life_hari') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="harga_rata_per_kg" label="Harga Rata-rata per Kg" type="number" step="0.01" placeholder="Harga rata-rata per kg" />
                        @error('harga_rata_per_kg') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
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
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mt-2">Hapus Komoditi</h3>
                <div class="mt-2">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus komoditi <strong>{{ $deletingKomoditi->nama ?? '' }}</strong>?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <flux:button type="button" wire:click="closeDeleteModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button wire:click="deleteKomoditi" variant="danger">
                        Hapus
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
