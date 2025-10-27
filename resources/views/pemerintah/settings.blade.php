<x-layouts.pemerintah title="Settings - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <flux:heading size="xl">Settings</flux:heading>
            <flux:subheading>Pengaturan Akun dan Keamanan</flux:subheading>
        </div>

        <!-- Change Password -->
        <flux:card class="mb-6">
            <flux:heading size="lg" class="mb-4">Ubah Password</flux:heading>
            
            <form method="POST" class="space-y-4">
                @csrf
                <div>
                    <flux:field>
                        <flux:label>Password Lama</flux:label>
                        <flux:input type="password" name="current_password" placeholder="Masukkan password lama" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Password Baru</flux:label>
                        <flux:input type="password" name="new_password" placeholder="Masukkan password baru" />
                        <flux:description>Minimal 8 karakter, kombinasi huruf dan angka</flux:description>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Konfirmasi Password Baru</flux:label>
                        <flux:input type="password" name="new_password_confirmation" placeholder="Ulangi password baru" />
                    </flux:field>
                </div>

                <div class="flex gap-3">
                    <flux:button type="submit" variant="primary">Ubah Password</flux:button>
                    <flux:button type="button" variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </flux:card>

        <!-- Notification Settings -->
        <flux:card class="mb-6">
            <flux:heading size="lg" class="mb-4">Notifikasi</flux:heading>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Email Notifikasi</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Terima notifikasi via email</p>
                    </div>
                    <flux:switch />
                </div>

                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Update Data Baru</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Notifikasi saat ada data baru</p>
                    </div>
                    <flux:switch />
                </div>

                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Newsletter</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Terima newsletter bulanan</p>
                    </div>
                    <flux:switch />
                </div>
            </div>
        </flux:card>

        <!-- Display Settings -->
        <flux:card class="mb-6">
            <flux:heading size="lg" class="mb-4">Tampilan</flux:heading>
            
            <div class="space-y-4">
                <div>
                    <flux:field>
                        <flux:label>Theme</flux:label>
                        <flux:select variant="filled">
                            <option value="system">System Default</option>
                            <option value="light">Light Mode</option>
                            <option value="dark">Dark Mode</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Bahasa</flux:label>
                        <flux:select variant="filled">
                            <option value="id">Indonesia</option>
                            <option value="en">English</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Items per Page</flux:label>
                        <flux:select variant="filled">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </flux:select>
                        <flux:description>Jumlah data yang ditampilkan per halaman</flux:description>
                    </flux:field>
                </div>
            </div>
        </flux:card>

        <!-- Privacy & Security -->
        <flux:card class="mb-6">
            <flux:heading size="lg" class="mb-4">Privasi & Keamanan</flux:heading>
            
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
        </flux:card>

        <!-- Danger Zone -->
        <flux:card>
            <flux:heading size="lg" class="mb-4" class="text-red-600 dark:text-red-400">Zona Bahaya</flux:heading>
            
            <div class="space-y-4">
                <div class="p-4 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white">Hapus Akun</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">Hapus akun Anda secara permanen</p>
                        </div>
                        <flux:button variant="danger" disabled>
                            Hapus Akun
                        </flux:button>
                    </div>
                    <div class="mt-3 bg-red-50 dark:bg-red-900/20 p-3 rounded">
                        <p class="text-xs text-red-800 dark:text-red-300">
                            Hubungi administrator untuk menghapus akun. Proses ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
            </div>
        </flux:card>
    </div>
</x-layouts.pemerintah>
