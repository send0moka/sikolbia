@extends('layouts.daftar-alamat')

@section('header')
    <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
        {{ __('Daftar Alamat') }}
    </h2>
@stop

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Search and Filter Section -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">Daftar Alamat</h3>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="openAddModal()">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Alamat Baru
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="provinsi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Provinsi</label>
                    <select id="provinsi" name="provinsi" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Semua Provinsi</option>
                        <option value="jawa-barat">Jawa Barat</option>
                        <option value="jawa-tengah">Jawa Tengah</option>
                        <option value="jawa-timur">Jawa Timur</option>
                    </select>
                </div>
                
                <div>
                    <label for="kota" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kota/Kabupaten</label>
                    <select id="kota" name="kota" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Semua Kota/Kabupaten</option>
                    </select>
                </div>
                
                <div>
                    <label for="kecamatan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Semua Kecamatan</option>
                    </select>
                </div>
                
                <div>
                    <label for="pencarian" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Pencarian</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="pencarian" id="pencarian" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-800 rounded-l-md" placeholder="Cari alamat...">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-zinc-700 text-neutral-500 dark:text-neutral-300 text-sm rounded-r-md hover:bg-neutral-100 dark:hover:bg-zinc-600 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Address Table -->
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-zinc-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Alamat Lengkap</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Kecamatan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Kota/Kabupaten</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Provinsi</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-neutral-200 dark:divide-neutral-700">
                        <!-- Example row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">1</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-neutral-900 dark:text-white">Jl. Contoh No. 123</div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400">RT 01/RW 02</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Rumah Tinggal
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">Cibiru</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">Kota Bandung</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">Jawa Barat</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Hapus</a>
                            </td>
                        </tr>
                        <!-- More rows can be added dynamically -->
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="bg-white dark:bg-zinc-900 px-4 py-3 flex items-center justify-between border-t border-neutral-200 dark:border-neutral-700 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-neutral-300 text-sm font-medium rounded-md text-neutral-700 bg-white hover:bg-neutral-50 dark:bg-zinc-800 dark:border-neutral-600 dark:text-neutral-200">
                            Sebelumnya
                        </a>
                        <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-neutral-300 text-sm font-medium rounded-md text-neutral-700 bg-white hover:bg-neutral-50 dark:bg-zinc-800 dark:border-neutral-600 dark:text-neutral-200">
                            Selanjutnya
                        </a>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-neutral-700 dark:text-neutral-300">
                                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">10</span> dari <span class="font-medium">24</span> hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-neutral-300 bg-white text-sm font-medium text-neutral-500 hover:bg-neutral-50 dark:bg-zinc-800 dark:border-neutral-600 dark:text-neutral-400 dark:hover:bg-zinc-700">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-indigo-900 dark:border-indigo-700 dark:text-indigo-200">
                                    1
                                </a>
                                <a href="#" class="bg-white border-neutral-300 text-neutral-500 hover:bg-neutral-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-zinc-800 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-zinc-700">
                                    2
                                </a>
                                <a href="#" class="bg-white border-neutral-300 text-neutral-500 hover:bg-neutral-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-zinc-800 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-zinc-700">
                                    3
                                </a>
                                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-neutral-300 bg-white text-sm font-medium text-neutral-500 hover:bg-neutral-50 dark:bg-zinc-800 dark:border-neutral-600 dark:text-neutral-400 dark:hover:bg-zinc-700">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="hidden fixed z-10 inset-0 overflow-y-auto" id="add-address-modal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" data-modal-hide="add-address-modal"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-white mb-4" id="modal-title">Tambah Alamat Baru</h3>
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="alamat-lengkap" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Alamat Lengkap</label>
                        <div class="mt-1">
                            <textarea id="alamat-lengkap" name="alamat-lengkap" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-neutral-300 dark:border-neutral-600 dark:bg-zinc-700 dark:text-white rounded-md"></textarea>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="rt" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">RT</label>
                        <input type="text" name="rt" id="rt" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-neutral-300 dark:border-neutral-600 dark:bg-zinc-700 dark:text-white rounded-md">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="rw" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">RW</label>
                        <input type="text" name="rw" id="rw" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-neutral-300 dark:border-neutral-600 dark:bg-zinc-700 dark:text-white rounded-md">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="kode-pos" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kode Pos</label>
                        <input type="text" name="kode-pos" id="kode-pos" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border border-neutral-300 dark:border-neutral-600 dark:bg-zinc-700 dark:text-white rounded-md">
                    </div>

                    <div class="sm:col-span-3">
                        <label for="provinsi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Provinsi</label>
                        <select id="provinsi" name="provinsi" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-700 text-neutral-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Provinsi</option>
                            <option value="jawa-barat">Jawa Barat</option>
                            <option value="jawa-tengah">Jawa Tengah</option>
                            <option value="jawa-timur">Jawa Timur</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="kota" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kota/Kabupaten</label>
                        <select id="kota" name="kota" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-700 text-neutral-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="kecamatan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kecamatan</label>
                        <select id="kecamatan" name="kecamatan" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-700 text-neutral-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="kelurahan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kelurahan/Desa</label>
                        <select id="kelurahan" name="kelurahan" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-700 text-neutral-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Kelurahan/Desa</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="kategori" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Kategori Alamat</label>
                        <select id="kategori" name="kategori" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-700 text-neutral-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="rumah-tinggal">Rumah Tinggal</option>
                            <option value="kantor">Kantor</option>
                            <option value="toko">Toko</option>
                            <option value="sekolah">Sekolah</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status Kepemilikan</label>
                        <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-zinc-700 text-neutral-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="milik-sendiri">Milik Sendiri</option>
                            <option value="sewa">Sewa</option>
                            <option value="kontrak">Kontrak</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="bg-neutral-50 dark:bg-zinc-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-700 text-base font-medium text-neutral-700 dark:text-white hover:bg-neutral-50 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" data-modal-hide="add-address-modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inisialisasi modal
    document.addEventListener('DOMContentLoaded', function() {
        // Tombol Tambah Alamat Baru
        const addButton = document.querySelector('button[onclick="openAddModal()"]');
        const modal = document.getElementById('add-address-modal');
        
        // Fungsi untuk membuka modal
        window.openAddModal = function() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        // Fungsi untuk menutup modal
        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Event listener untuk tombol batal dan overlay
        modal.querySelectorAll('[data-modal-hide="add-address-modal"], .bg-opacity-75').forEach(element => {
            element.addEventListener('click', closeModal);
        });

        // Event listener untuk tombol escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    });
</script>
@endpush

@stop
