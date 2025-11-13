<x-layouts.pemerintah title="Panduan Penggunaan - Panel Pemerintah - {{ config('app.name') }}">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Panduan Penggunaan</h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-1">Petunjuk Menggunakan Panel Pemerintah SIKOLBIA</p>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Tentang Akses Pemerintah</h2>
            <div class="prose dark:prose-invert max-w-none">
                <p class="text-neutral-700 dark:text-neutral-300 mb-4">
                    Sebagai pengguna dengan akses <strong>Pemerintah</strong>, Anda memiliki hak untuk melihat dan mengekspor data dari sistem SIKOLBIA. Akses ini dirancang khusus untuk mendukung pengambilan keputusan dan perencanaan kebijakan ketahanan pangan.
                </p>
                
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">Hak Akses Anda:</h4>
                    <ul class="list-disc list-inside space-y-1 text-blue-800 dark:text-blue-300">
                        <li>Melihat data Konsumsi Pangan & NBM</li>
                        <li>Menggunakan fitur Prediksi NBM berbasis Machine Learning</li>
                        <li>Melihat data Lahan Pertanian</li>
                        <li>Melihat data Benih & Pupuk</li>
                        <li>Melihat data Iklim OptDPI</li>
                        <li>Export data dalam format Excel dan PDF</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Cara Menggunakan Sistem</h2>
            
            <div class="space-y-6">
                <!-- Step 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                        1
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">Akses Dashboard</h4>
                        <p class="text-neutral-700 dark:text-neutral-300 text-sm">
                            Setelah login, Anda akan diarahkan ke Dashboard Pemerintah yang menampilkan ringkasan akses quick links ke berbagai modul data.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                        2
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">Navigasi Menu</h4>
                        <p class="text-neutral-700 dark:text-neutral-300 text-sm mb-2">
                            Gunakan sidebar di sebelah kiri untuk mengakses berbagai modul:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-neutral-700 dark:text-neutral-300 ml-4">
                            <li><strong>Konsumsi Pangan:</strong> Laporan NBM dan Prediksi ML</li>
                            <li><strong>Pertanian:</strong> Data Lahan, Benih & Pupuk, Iklim</li>
                            <li><strong>Informasi:</strong> Panduan dan dokumentasi</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                        3
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">Filter Data</h4>
                        <p class="text-neutral-700 dark:text-neutral-300 text-sm">
                            Setiap halaman data dilengkapi dengan filter (kelompok, tahun, bulan) untuk mempermudah pencarian data yang spesifik sesuai kebutuhan Anda.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                        4
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">Export Data</h4>
                        <p class="text-neutral-700 dark:text-neutral-300 text-sm mb-2">
                            Setelah menemukan data yang dibutuhkan, Anda dapat mengekspornya dengan:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-neutral-700 dark:text-neutral-300 ml-4">
                            <li>Klik tombol "Export Excel" untuk mendapatkan file .xlsx</li>
                            <li>Klik tombol "Export PDF" untuk dokumen printable</li>
                            <li>Data akan diunduh sesuai filter yang aktif</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                        5
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-neutral-900 dark:text-white mb-2">Prediksi Machine Learning</h4>
                        <p class="text-neutral-700 dark:text-neutral-300 text-sm">
                            Fitur Prediksi NBM menggunakan model LSTM yang dilatih dengan data historis lengkap (1993-2024). Model menganalisis pola konsumsi dari 6 bulan terakhir sebagai input untuk menghasilkan proyeksi masa depan yang akurat, lengkap dengan confidence interval.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Keterbatasan Akses</h2>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <p class="text-sm text-yellow-800 dark:text-yellow-300 mb-2">
                    <strong>Mode Read-Only:</strong> Akses Pemerintah tidak dapat melakukan:
                </p>
                <ul class="list-disc list-inside space-y-1 text-sm text-yellow-800 dark:text-yellow-300">
                    <li>Menambah, mengubah, atau menghapus data</li>
                    <li>Mengakses panel administrasi</li>
                    <li>Mengelola user atau permission</li>
                    <li>Melatih ulang model Machine Learning</li>
                </ul>
                <p class="text-sm text-yellow-800 dark:text-yellow-300 mt-3">
                    Jika membutuhkan akses lebih lanjut, silakan hubungi administrator sistem.
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Bantuan & Dukungan</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Email Support</p>
                        <a href="mailto:support@sikolbia.com" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">support@sikolbia.com</a>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-neutral-900 dark:text-white">Telepon</p>
                        <a href="tel:+6281234567890" class="text-sm text-green-600 dark:text-green-400 hover:underline">+62 812-3456-7890</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.pemerintah>
