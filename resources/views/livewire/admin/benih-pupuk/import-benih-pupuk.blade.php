
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
                <form action="{{ route('admin.benih-pupuk.preview') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 sm:gap-3">
                    @csrf
                    <input type="file" name="importFile" accept=".csv,.xlsx,.xls"
                           class="text-xs sm:text-sm px-2 sm:px-3 py-1 sm:py-2 rounded border border-blue-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-1 sm:file:mr-2 file:py-1 file:px-1 sm:file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <button type="submit"
                            class="inline-flex items-center px-3 sm:px-4 py-1 sm:py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded transition-colors duration-200 shadow-sm text-sm">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="hidden sm:inline">Preview</span>
                        <span>Preview</span>
                    </button>
                </form>
                @error('importFile')
                    <span class="text-red-300 text-xs ml-2">{{ $message }}</span>
                @enderror
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

<!-- Preview Modal -->
@if(session('showPreviewModal'))
<div class="fixed inset-0 z-60 overflow-y-auto pointer-events-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 pointer-events-auto">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity pointer-events-none" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen pointer-events-none" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full pointer-events-auto max-h-screen overflow-y-auto">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Preview Data Import
                        </h3>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">
                                Total Baris: <strong>{{ Cache::get('totalRows') }}</strong> | Total Kolom: <strong>{{ Cache::get('totalColumns') }}</strong>
                            </p>
                            @if(Cache::get('warnings'))
                                <div class="mt-2 p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                                    <strong>Peringatan Kolom:</strong>
                                    <ul class="list-disc list-inside mt-1">
                                        @foreach(Cache::get('warnings') as $warning)
                                            <li>{{ $warning }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(Cache::get('rowWarnings'))
                                <div class="mt-2 p-3 text-left bg-red-100 border border-red-400 text-red-700 rounded">
                                    <strong>Peringatan Data:</strong>
                                    <ul class="list-disc list-inside mt-1">
                                        @foreach(Cache::get('rowWarnings') as $rowWarning)
                                            <li>Baris {{ $rowWarning['row'] }}: {{ implode(', ', $rowWarning['warnings']) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="overflow-x-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(Cache::get('previewData', []) as $rowIndex => $row)
                                        <tr class="{{ $rowIndex == 0 ? 'bg-gray-50 font-semibold' : '' }}">
                                            @foreach($row as $cell)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $cell }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form action="{{ route('admin.benih-pupuk.import') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" {{ (Cache::get('warnings') || Cache::get('rowWarnings')) ? 'disabled' : '' }} class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 {{ (Cache::get('warnings') || Cache::get('rowWarnings')) ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Konfirmasi Import
                    </button>
                </form>
                <a href="{{ route('admin.benih-pupuk.cancel-preview') }}" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Import Progress Modal -->
@if($showImportModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Proses Import
                        </h3>
                        <div class="mb-4">
                            <div class="bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $importProgress }}%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $importStatus }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button wire:click="$set('showImportModal', false)" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm" :disabled="$importProgress < 100">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif

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
