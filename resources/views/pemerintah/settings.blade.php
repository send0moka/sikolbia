<x-layouts.pemerintah title="Settings - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Settings</h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-1">Pengaturan Akun dan Keamanan</p>
        </div>

        <!-- Change Password -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Ubah Password</h2>
            
            <form method="POST" class="space-y-4">
                @csrf
                <div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Password Lama</label>
                        <input type="password" name="current_password" placeholder="Masukkan password lama" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                <div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Password Baru</label>
                        <input type="password" name="new_password" placeholder="Masukkan password baru" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Minimal 8 karakter, kombinasi huruf dan angka</p>
                    </div>
                </div>

                <div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">Ubah Password</button>
                    <button type="button" class="px-4 py-2 bg-transparent hover:bg-neutral-100 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-lg font-medium transition-colors">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Notifikasi</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Email Notifikasi</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Terima notifikasi via email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-neutral-200 peer-focus:ring-2 peer-focus:ring-blue-500 dark:bg-neutral-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Update Data Baru</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Notifikasi saat ada data baru</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-neutral-200 peer-focus:ring-2 peer-focus:ring-blue-500 dark:bg-neutral-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Newsletter</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Terima newsletter bulanan</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-neutral-200 peer-focus:ring-2 peer-focus:ring-blue-500 dark:bg-neutral-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Display Settings -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Tampilan</h2>
            
            <div class="space-y-4">
                <div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Theme</label>
                        <select class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="system">System Default</option>
                            <option value="light">Light Mode</option>
                            <option value="dark">Dark Mode</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Bahasa</label>
                        <select class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="id">Indonesia</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Items per Page</label>
                        <select class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Jumlah data yang ditampilkan per halaman</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Privacy & Security -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Privasi & Keamanan</h2>
            
            <div class="space-y-4">
                <div class="p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Akun Terverifikasi</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Email Anda telah diverifikasi</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Password Terakhir Diubah</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ Auth::user()->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <strong>Mode Read-Only:</strong> Sebagai pengguna Pemerintah, Anda tidak dapat mengubah data sistem. Untuk akses penuh, hubungi administrator.
                    </p>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-4">Zona Bahaya</h2>
            
            <div class="space-y-4">
                <div class="p-4 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Hapus Akun</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Hapus akun Anda secara permanen</p>
                        </div>
                        <button disabled class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium opacity-50 cursor-not-allowed">
                            Hapus Akun
                        </button>
                    </div>
                    <div class="mt-3 bg-red-50 dark:bg-red-900/20 p-3 rounded">
                        <p class="text-xs text-red-800 dark:text-red-300">
                            Hubungi administrator untuk menghapus akun. Proses ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.pemerintah>
