<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola Kelompok</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data kelompok pangan
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
                    Tambah Kelompok
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

    <!-- Search & Per Page -->
    <div class="mb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
            <flux:input 
                wire:model.live="search" 
                placeholder="Cari berdasarkan kode atau nama..."
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

    <!-- Kelompok Table -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700" id="kelompok-table-wrapper">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400" id="kelompok-table">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Kode</th>
                        <th scope="col" class="px-6 py-3">Nama Kelompok</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3">AKE Ketersediaan</th>
                        <th scope="col" class="px-6 py-3">Skor PPH</th>
                        <th scope="col" class="px-6 py-3">Status Aktif</th>
                        <th scope="col" class="px-6 py-3">Dibuat</th>
                        <th scope="col" class="px-6 py-3 no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelompoks as $kelompok)
                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $kelompok->kode }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $kelompok->nama }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $kelompok->deskripsi }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $kelompok->ake_ketersediaan ? number_format($kelompok->ake_ketersediaan, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $kelompok->skor_pph ? number_format($kelompok->skor_pph, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs {{ $kelompok->status_aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $kelompok->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $kelompok->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 no-print">
                            <div class="flex space-x-2">
                                <flux:button wire:click="openEditModal({{ $kelompok->id }})" variant="ghost" size="sm">
                                    Edit
                                </flux:button>
                                <flux:button wire:click="openDeleteModal({{ $kelompok->id }})" variant="danger" size="sm">
                                    Hapus
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                            Tidak ada kelompok ditemukan
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
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $kelompoks->firstItem() }}</span>
                -
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $kelompoks->lastItem() }}</span>
                dari
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $kelompoks->total() }}</span>
                kelompok
            </div>
            {{ $kelompoks->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Create Kelompok Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Tambah Kelompok</h3>
                <form wire:submit="createKelompok">
                    <div class="space-y-4">
                        <flux:input wire:model="kode" label="Kode" placeholder="Masukkan kode kelompok" required />
                        @error('kode') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="nama" label="Nama Kelompok" placeholder="Masukkan nama kelompok" required />
                        @error('nama') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="deskripsi" label="Deskripsi" placeholder="Deskripsi kelompok (minimal 3 karakter)" required />
                        @error('deskripsi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="ake_ketersediaan" label="AKE Ketersediaan" type="number" step="0.01" placeholder="Angka Kecukupan Energi ketersediaan" />
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Angka Kecukupan Energi ketersediaan (opsional)</p>
                        @error('ake_ketersediaan') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="skor_pph" label="Skor PPH" type="number" step="0.01" placeholder="Skor Pola Pangan Harapan" />
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Skor Pola Pangan Harapan (opsional)</p>
                        @error('skor_pph') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="status_aktif" id="status_aktif" class="form-checkbox h-4 w-4 text-accent" />
                            <label for="status_aktif" class="text-sm">Aktif</label>
                        </div>
                        @error('status_aktif') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
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

    <!-- Edit Kelompok Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Edit Kelompok</h3>
                <form wire:submit="updateKelompok">
                    <div class="space-y-4">
                        <flux:input wire:model="kode" label="Kode" placeholder="Masukkan kode kelompok (kapital)" required />
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Hanya huruf kapital dan angka, maksimal 10 karakter</p>
                        @error('kode') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="nama" label="Nama Kelompok" placeholder="Masukkan nama kelompok" required />
                        @error('nama') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="deskripsi" label="Deskripsi" placeholder="Deskripsi kelompok (minimal 3 karakter)" required />
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Minimal 3 karakter, maksimal 255 karakter</p>
                        @error('deskripsi') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="ake_ketersediaan" label="AKE Ketersediaan" type="number" step="0.01" placeholder="Angka Kecukupan Energi ketersediaan" />
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Angka Kecukupan Energi ketersediaan (opsional)</p>
                        @error('ake_ketersediaan') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="skor_pph" label="Skor PPH" type="number" step="0.01" placeholder="Skor Pola Pangan Harapan" />
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Skor Pola Pangan Harapan (opsional)</p>
                        @error('skor_pph') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="status_aktif" id="status_aktif_edit" class="form-checkbox h-4 w-4 text-accent" />
                            <label for="status_aktif_edit" class="text-sm">Aktif</label>
                        </div>
                        @error('status_aktif') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
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
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mt-2">Hapus Kelompok</h3>
                <div class="mt-2">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus kelompok <strong>{{ $deletingKelompok->nama ?? '' }}</strong>?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <flux:button type="button" wire:click="closeDeleteModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button wire:click="deleteKelompok" variant="danger">
                        Hapus
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Import Modal -->
    @if($showBulkImportModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Bulk Import Kelompok</h3>
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
                                <strong>Perhatian:</strong> Pastikan file sesuai template. Data yang sudah ada dengan kode sama akan dilewati.
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

    @push('scripts')
    <script>
        document.addEventListener('livewire:loaded', () => {
            // Auto uppercase kode input
            Livewire.on('updatedKode', (value) => {
                const kodeInput = document.querySelector('input[wire\\:model="kode"]');
                if (kodeInput && value) {
                    kodeInput.value = value.toUpperCase();
                }
            });

            // Auto uppercase when typing in kode field
            document.addEventListener('input', function(e) {
                if (e.target.matches('input[wire\\:model="kode"]')) {
                    e.target.value = e.target.value.toUpperCase();
                }
            });

            // Remove symbols from kode input
            document.addEventListener('input', function(e) {
                if (e.target.matches('input[wire\\:model="kode"]')) {
                    e.target.value = e.target.value.replace(/[^A-Z0-9]/g, '');
                }
            });
        });
    </script>
    @endpush
