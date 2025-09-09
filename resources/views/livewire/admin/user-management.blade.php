<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Kelola User</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Kelola akun pengguna dan role mereka
                </p>
            </div>
            @can('create users')
            <flux:button wire:click="openCreateModal" variant="primary">
                Tambah User
            </flux:button>
            @endcan
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
                placeholder="Cari berdasarkan nama atau email..."
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

    <!-- Users Table -->
    <div class="bg-white dark:!bg-neutral-800 overflow-hidden shadow-sm rounded-lg border border-neutral-200 dark:border-neutral-700" id="users-table-wrapper">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400" id="users-table">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Bergabung</th>
                        <th scope="col" class="px-6 py-3 no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr class="bg-white border-b dark:!bg-neutral-800 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:!bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-neutral-900 dark:text-white">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 no-print">
                            <div class="flex space-x-2">
                                @can('edit users')
                                <flux:button wire:click="openEditModal({{ $user->id }})" variant="ghost" size="sm">
                                    Edit
                                </flux:button>
                                @endcan
                                @can('delete users')
                                <flux:button wire:click="openDeleteModal({{ $user->id }})" variant="danger" size="sm">
                                    Hapus
                                </flux:button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                            Tidak ada user ditemukan
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
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $users->firstItem() }}</span>
                -
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $users->lastItem() }}</span>
                dari
                <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $users->total() }}</span>
                user
            </div>
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Create User Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Tambah User</h3>
                <form wire:submit="createUser">
                    <div class="space-y-4">
                        <flux:input wire:model="name" label="Nama" placeholder="Nama lengkap" required />
                        @error('name') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="email" label="Email" type="email" placeholder="email@example.com" required />
                        @error('email') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="password" label="Password" type="password" required />
                        @error('password') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:select wire:model="selectedRole" label="Role" placeholder="Pilih role">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </flux:select>
                        @error('selectedRole') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <flux:button wire:click="closeCreateModal" variant="ghost">
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

    <!-- Edit User Modal -->
    @if($showEditModal && $editingUser)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Edit User</h3>
                <form wire:submit="updateUser">
                    <div class="space-y-4">
                        <flux:input wire:model="name" label="Nama" placeholder="Nama lengkap" required />
                        @error('name') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="email" label="Email" type="email" placeholder="email@example.com" required />
                        @error('email') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:input wire:model="password" label="Password Baru (kosongkan jika tidak ingin mengubah)" type="password" />
                        @error('password') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror

                        <flux:select wire:model="selectedRole" label="Role" placeholder="Pilih role">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </flux:select>
                        @error('selectedRole') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <flux:button wire:click="closeEditModal" variant="ghost">
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

    <!-- Delete User Modal -->
    @if($showDeleteModal && $deletingUser)
    <div class="fixed inset-0 bg-neutral-900/70 dark:bg-neutral-950/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-neutral-200 dark:border-neutral-700 w-96 shadow-xl rounded-md bg-white dark:!bg-neutral-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Konfirmasi Hapus</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-6">
                    Apakah Anda yakin ingin menghapus user <strong>{{ $deletingUser->name }}</strong>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex justify-center space-x-3">
                    <flux:button wire:click="closeDeleteModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button wire:click="deleteUser" variant="danger">
                        Hapus
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Print JS dipindah ke resources/js/app.js --}}
