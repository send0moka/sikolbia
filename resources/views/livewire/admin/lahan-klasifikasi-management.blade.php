<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola Klasifikasi Lahan</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola data klasifikasi lahan pertanian
                </p>
            </div>
            <flux:button wire:click="openCreateModal" variant="primary">
                Tambah Klasifikasi Lahan
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
                placeholder="Cari klasifikasi lahan..."
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
    </div>

    <!-- Klasifikasi Table -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('id')">
                            <div class="flex items-center">
                                ID
                                @if($sortField === 'id')
                                    <x-icon.chevron-up class="w-4 h-4 ml-1 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                @else
                                    <x-icon.chevron-up class="w-4 h-4 ml-1 text-transparent" />
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">Nama Klasifikasi</th>
                        <th scope="col" class="px-6 py-3">Dibuat</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($klasifikasis as $klasifikasi)
                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $klasifikasi->id }}
                        </td>
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $klasifikasi->nama }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $klasifikasi->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <flux:button wire:click="openEditModal({{ $klasifikasi->id }})" variant="ghost" size="sm">
                                    Edit
                                </flux:button>
                                <flux:button wire:click="openDeleteModal({{ $klasifikasi->id }})" variant="danger" size="sm">
                                    Hapus
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                            Tidak ada klasifikasi lahan ditemukan
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
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $klasifikasis->firstItem() }}</span>
                -
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $klasifikasis->lastItem() }}</span>
                dari
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $klasifikasis->total() }}</span>
                klasifikasi lahan
            </div>
            {{ $klasifikasis->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Create Klasifikasi Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Tambah Klasifikasi Lahan</h3>
                <form wire:submit="createKlasifikasi">
                    <div class="space-y-4">
                        <flux:input wire:model="nama" label="Nama Klasifikasi" placeholder="Masukkan nama klasifikasi lahan" required />
                        @error('nama') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
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

    <!-- Edit Klasifikasi Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Edit Klasifikasi Lahan</h3>
                <form wire:submit="updateKlasifikasi">
                    <div class="space-y-4">
                        <flux:input wire:model="nama" label="Nama Klasifikasi" placeholder="Masukkan nama klasifikasi lahan" required />
                        @error('nama') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
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
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mt-2">Hapus Klasifikasi Lahan</h3>
                <div class="mt-2">
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus klasifikasi lahan <strong>{{ $deletingKlasifikasi->nama ?? '' }}</strong>?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <flux:button type="button" wire:click="closeDeleteModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button wire:click="deleteKlasifikasi" variant="danger">
                        Hapus
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
