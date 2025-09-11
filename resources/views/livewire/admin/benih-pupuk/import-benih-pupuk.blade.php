
<div>
<!-- Fixed Header with Title and Import Form -->
<header class="fixed top-0 z-30 bg-gradient-to-r from-neutral-600 to-neutral-700 dark:from-neutral-800 dark:to-neutral-900 text-white shadow-lg border-b border-zinc-200 dark:border-zinc-700 backdrop-blur-sm bg-opacity-95"
        style="left: calc(16rem + 1px); right: 0;">
    <div class="px-4 sm:px-6 py-3">
        <div class="flex items-center justify-between">
            <!-- Title Section -->
            <div class="flex-shrink-0">
                <h1 class="text-lg sm:text-xl font-bold">🌱 Import Data Benih & Pupuk</h1>
            </div>

            <!-- Import Form Section -->
            <div class="flex-shrink-0">
                <form action="{{ route('admin.benih-pupuk.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 sm:gap-3">
                    @csrf
                    <div class="flex items-center gap-2">
                        <input type="file" name="importFile" accept=".csv,.xlsx,.xls"
                               class="text-xs sm:text-sm px-2 sm:px-3 py-1 sm:py-2 rounded border border-blue-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-1 sm:file:mr-2 file:py-1 file:px-1 sm:file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        <button type="submit"
                                class="inline-flex items-center px-3 sm:px-4 py-1 sm:py-2 bg-blue-600 hover:bg-gray-50 text-white font-semibold rounded transition-colors duration-200 shadow-sm text-sm">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="hidden sm:inline">Import</span>
                            <span>Import</span>
                        </button>
                    </div>
                    @error('importFile')
                        <span class="text-red-300 text-xs ml-2">{{ $message }}</span>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Toast Notification Container - Moved outside main content -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2 pointer-events-none">
    @if (session()->has('message'))
        <div id="success-toast" class="flex items-center p-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-600 rounded-lg shadow-lg backdrop-blur-sm pointer-events-auto animate-in slide-in-from-right-2 fade-in duration-300">
            <svg class="w-5 h-5 mr-3 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-medium flex-1">{{ session('message') }}</p>
            <button onclick="closeToast('success-toast')" class="ml-4 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if (session()->has('error') || $errors->any())
        <div id="error-toast" class="flex items-center p-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-600 rounded-lg shadow-lg backdrop-blur-sm pointer-events-auto animate-in slide-in-from-right-2 fade-in duration-300">
            <svg class="w-5 h-5 mr-3 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-medium flex-1">
                @if(session()->has('error'))
                    {{ session('error') }}
                @elseif($errors->has('importFile'))
                    {{ $errors->first('importFile') }}
                @else
                    Terjadi kesalahan saat memproses data
                @endif
            </p>
            <button onclick="closeToast('error-toast')" class="ml-4 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif
</div>

<script>
    // Auto-hide toasts after 10 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successToast = document.getElementById('success-toast');
        const errorToast = document.getElementById('error-toast');

        if (successToast) {
            setTimeout(() => {
                closeToast('success-toast');
            }, 10000);
        }

        if (errorToast) {
            setTimeout(() => {
                closeToast('error-toast');
            }, 10000);
        }
    });

    function closeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    // Show toast if there are validation errors on page load
    @if($errors->any() && !$errors->has('importFile'))
        document.addEventListener('DOMContentLoaded', function() {
            // Force show error toast for general errors
            const errorToast = document.getElementById('error-toast');
            if (errorToast) {
                errorToast.style.display = 'flex';
            }
        });
    @endif
</script><!-- Main Content with Instructions -->
<div class="max-w-5xl mx-auto px-4 py-8 space-y-8 pt-20">
    <!-- Step 1: Import Guide -->
    <section class="mb-10">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow border border-blue-200 dark:border-blue-700 p-8">
            <h2 class="text-2xl font-bold text-blue-900 dark:text-blue-200 mb-4">📘 Panduan Import</h2>
            <ul class="list-disc pl-6 text-blue-800 dark:text-blue-200 space-y-2 text-base">
                <li><strong>Persiapkan File:</strong> Format CSV atau Excel (.xlsx/.xls)</li>
                <li><strong>Format Kolom:</strong> Kolom harus sesuai template</li>
                <li><strong>Validasi Data:</strong> Pastikan data sesuai referensi</li>
                <li><strong>Upload & Import:</strong> Pilih file dan klik import</li>
            </ul>
        </div>
    </section>

    <!-- Step 2: Format Kolom Wajib -->
    <section class="mb-10">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg shadow border border-yellow-200 dark:border-yellow-700 p-8">
            <h2 class="text-2xl font-bold text-yellow-900 dark:text-yellow-100 mb-4">🗂️ Format Kolom Wajib</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-yellow-800 dark:text-yellow-200">
                    <thead>
                        <tr class="border-b border-yellow-200 dark:border-yellow-700 bg-yellow-100 dark:bg-yellow-900/30">
                            <th class="text-left py-2 px-4 font-semibold text-yellow-900 dark:text-yellow-100">Kolom</th>
                            <th class="text-left py-2 px-4 font-semibold text-yellow-900 dark:text-yellow-100">Tipe Data</th>
                            <th class="text-left py-2 px-4 font-semibold text-yellow-900 dark:text-yellow-100">Keterangan</th>
                            <th class="text-left py-2 px-4 font-semibold text-yellow-900 dark:text-yellow-100">Contoh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-yellow-200 dark:divide-yellow-700">
                        <tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            <td class="py-2 px-4 font-mono text-yellow-900 dark:text-yellow-200">tahun</td>
                            <td class="py-2 px-4">Integer</td>
                            <td class="py-2 px-4">Tahun data (2000-2050)</td>
                            <td class="py-2 px-4">2024</td>
                        </tr>
                        <tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            <td class="py-2 px-4 font-mono text-yellow-900 dark:text-yellow-200">id_bulan</td>
                            <td class="py-2 px-4">Integer</td>
                            <td class="py-2 px-4">ID bulan (1-12)</td>
                            <td class="py-2 px-4">1</td>
                        </tr>
                        <tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            <td class="py-2 px-4 font-mono text-yellow-900 dark:text-yellow-200">id_wilayah</td>
                            <td class="py-2 px-4">Integer</td>
                            <td class="py-2 px-4">ID wilayah (provinsi/kabupaten)</td>
                            <td class="py-2 px-4">1</td>
                        </tr>
                        <tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            <td class="py-2 px-4 font-mono text-yellow-900 dark:text-yellow-200">id_variabel</td>
                            <td class="py-2 px-4">Integer</td>
                            <td class="py-2 px-4">ID variabel benih/pupuk</td>
                            <td class="py-2 px-4">1</td>
                        </tr>
                        <tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            <td class="py-2 px-4 font-mono text-yellow-900 dark:text-yellow-200">id_klasifikasi</td>
                            <td class="py-2 px-4">Integer</td>
                            <td class="py-2 px-4">ID klasifikasi variabel</td>
                            <td class="py-2 px-4">1</td>
                        </tr>
                        <tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            <td class="py-2 px-4 font-mono text-yellow-900 dark:text-yellow-200">nilai</td>
                            <td class="py-2 px-4">Decimal</td>
                            <td class="py-2 px-4">Nilai data (opsional)</td>
                            <td class="py-2 px-4">100.50</td>
                        </tr>
                        <tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            <td class="py-2 px-4 font-mono text-yellow-900 dark:text-yellow-200">status</td>
                            <td class="py-2 px-4">String</td>
                            <td class="py-2 px-4">Status data (A=Active, I=Inactive, D=Deleted)</td>
                            <td class="py-2 px-4">A</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-sm text-yellow-700 dark:text-yellow-800 bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded border border-yellow-200 dark:border-yellow-700">
                <p><strong>Catatan:</strong> Template Excel berisi multiple sheet dengan referensi data lengkap untuk setiap kolom ID.</p>
            </div>
        </div>
    </section>

    <!-- Step 3: Download Template -->
    <section class="mb-10">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-600 p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">📥 Download Template Import</h2>
            <p class="text-base text-gray-600 dark:text-gray-100 mb-4">Template Excel sudah berisi format dan referensi data yang diperlukan untuk import. Terdapat beberapa sheet referensi.</p>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-600">
                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-900 mb-2">Sheet yang tersedia:</h4>
                <ul class="text-sm text-gray-600 dark:text-gray-600 space-y-1">
                    <li>• <strong>Template Import:</strong> Format dasar untuk import data</li>
                    <li>• <strong>Referensi Bulan:</strong> Daftar bulan dengan ID</li>
                    <li>• <strong>Referensi Wilayah:</strong> Daftar provinsi dan kabupaten</li>
                    <li>• <strong>Referensi Variabel:</strong> Daftar variabel benih dan pupuk</li>
                    <li>• <strong>Referensi Klasifikasi:</strong> Daftar klasifikasi untuk setiap variabel</li>
                </ul>
            </div>
            <button wire:click="downloadTemplate"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Template Excel
            </button>
        </div>
    </section>

    <!-- Step 4: API Reference -->
    <section class="mb-10">
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg shadow border border-purple-200 dark:border-purple-700 p-8">
            <h2 class="text-2xl font-bold text-purple-700 dark:text-purple-200 mb-4">🔗 API Referensi Data</h2>
            <p class="text-base text-purple-800 dark:text-purple-200 mb-4">Gunakan endpoint API berikut untuk mengambil data referensi secara programmatic:</p>
            <div class="space-y-2 text-sm">
                <div class="bg-purple-100 dark:bg-purple-800/50 rounded p-3 border border-purple-200 dark:border-purple-700">
                    <code class="text-purple-700 dark:text-purple-700 font-mono">GET /api/benih-pupuk/topiks</code>
                    <span class="text-purple-700 dark:text-purple-700 ml-2">- Daftar topik benih pupuk</span>
                </div>
                <div class="bg-purple-100 dark:bg-purple-800/50 rounded p-3 border border-purple-200 dark:border-purple-700">
                    <code class="text-purple-700 dark:text-purple-700 font-mono">GET /api/benih-pupuk/variabels/{topik}</code>
                    <span class="text-purple-700 dark:text-purple-700 ml-2">- Variabel berdasarkan topik</span>
                </div>
                <div class="bg-purple-100 dark:bg-purple-800/50 rounded p-3 border border-purple-200 dark:border-purple-700">
                    <code class="text-purple-700 dark:text-purple-700 font-mono">POST /api/benih-pupuk/klasifikasis</code>
                    <span class="text-purple-700 dark:text-purple-700 ml-2">- Klasifikasi berdasarkan variabel</span>
                </div>
                <div class="bg-purple-100 dark:bg-purple-800/50 rounded p-3 border border-purple-200 dark:border-purple-700">
                    <code class="text-purple-700 dark:text-purple-700 font-mono">GET /api/benih-pupuk/wilayahs</code>
                    <span class="text-purple-700 dark:text-purple-700 ml-2">- Daftar wilayah lengkap</span>
                </div>
                <div class="bg-purple-100 dark:bg-purple-800/50 rounded p-3 border border-purple-200 dark:border-purple-700">
                    <code class="text-purple-700 dark:text-purple-700 font-mono">GET /api/benih-pupuk/bulans</code>
                    <span class="text-purple-700 dark:text-purple-700 ml-2">- Daftar bulan</span>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
