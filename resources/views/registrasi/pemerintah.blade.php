<x-layouts.landing>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">
                    Registrasi Akses Pemerintah
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400">
                    Lengkapi formulir di bawah untuk mendapatkan akses ke sistem SIKOLBIA
                </p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-600 dark:text-green-200" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-600 dark:text-red-200" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-8">
                    <form action="{{ route('public.registrasi.proses') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tipe_akses" value="pemerintah">

                        <!-- Informasi Pribadi -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Informasi Pribadi
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" 
                                        value="{{ old('nama_lengkap') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('nama_lengkap') border-red-500 @enderror" 
                                        required>
                                    @error('nama_lengkap')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" 
                                        value="{{ old('email') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('email') border-red-500 @enderror" 
                                        required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="telepon" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        No. Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="telepon" id="telepon" 
                                        value="{{ old('telepon') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('telepon') border-red-500 @enderror" 
                                        required>
                                    @error('telepon')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Instansi -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Informasi Instansi
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="instansi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Nama Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="instansi" id="instansi" 
                                        value="{{ old('instansi') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('instansi') border-red-500 @enderror" 
                                        required>
                                    @error('instansi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenis_dinas" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Jenis Dinas
                                    </label>
                                    <select name="jenis_dinas" id="jenis_dinas" 
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('jenis_dinas') border-red-500 @enderror">
                                        <option value="">Pilih Jenis Dinas</option>
                                        <option value="Dinas Pertanian" {{ old('jenis_dinas') == 'Dinas Pertanian' ? 'selected' : '' }}>Dinas Pertanian</option>
                                        <option value="Dinas Pangan" {{ old('jenis_dinas') == 'Dinas Pangan' ? 'selected' : '' }}>Dinas Pangan</option>
                                        <option value="Dinas Ketahanan Pangan" {{ old('jenis_dinas') == 'Dinas Ketahanan Pangan' ? 'selected' : '' }}>Dinas Ketahanan Pangan</option>
                                        <option value="BPS" {{ old('jenis_dinas') == 'BPS' ? 'selected' : '' }}>BPS</option>
                                        <option value="Lainnya" {{ old('jenis_dinas') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('jenis_dinas')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jabatan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        Jabatan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="jabatan" id="jabatan" 
                                        value="{{ old('jabatan') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('jabatan') border-red-500 @enderror" 
                                        required>
                                    @error('jabatan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tujuan Penggunaan -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Tujuan Penggunaan Data
                            </h2>

                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Perencanaan Kebijakan" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Perencanaan Kebijakan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Monitoring dan Evaluasi" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Monitoring dan Evaluasi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Penelitian" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Penelitian</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Analisis Data" 
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Analisis Data</span>
                                </label>
                            </div>
                        </div>

                        <!-- Deskripsi Kebutuhan -->
                        <div class="mb-8">
                            <label for="deskripsi_kebutuhan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                Deskripsi Kebutuhan
                            </label>
                            <textarea name="deskripsi_kebutuhan" id="deskripsi_kebutuhan" rows="4" 
                                class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white @error('deskripsi_kebutuhan') border-red-500 @enderror" 
                                placeholder="Jelaskan kebutuhan data Anda...">{{ old('deskripsi_kebutuhan') }}</textarea>
                            @error('deskripsi_kebutuhan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Dokumen -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4 pb-2 border-b border-neutral-200 dark:border-neutral-700">
                                Upload Dokumen Pendukung
                            </h2>

                            <!-- Template Download -->
                            <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Template Surat Permohonan</h3>
                                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                            Download template resmi surat permohonan akses data NBM dari instansi Anda.
                                        </p>
                                        <a href="{{ asset('templates/template-surat-permohonan-pemerintah.html') }}" target="_blank"
                                           class="inline-flex items-center mt-2 text-sm bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Buka Template Surat
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Surat Permohonan Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Surat Permohonan Akses Data <span class="text-red-500">*</span>
                                        <span class="text-neutral-500">(PDF, maksimal 5MB)</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 dark:border-neutral-600 border-dashed rounded-lg hover:border-blue-400 transition @error('surat_permohonan') border-red-500 @enderror">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-neutral-600 dark:text-neutral-400">
                                                <label for="surat_permohonan" class="relative cursor-pointer bg-white dark:bg-neutral-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload file</span>
                                                    <input id="surat_permohonan" name="surat_permohonan" type="file" accept=".pdf" class="sr-only" required>
                                                </label>
                                                <p class="pl-1">atau drag & drop</p>
                                            </div>
                                            <p class="text-xs text-neutral-500">PDF hingga 5MB</p>
                                        </div>
                                    </div>
                                    @error('surat_permohonan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- ID Instansi Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Kartu Pegawai/ID Instansi <span class="text-red-500">*</span>
                                        <span class="text-neutral-500">(PDF/JPG/PNG, maksimal 2MB)</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 dark:border-neutral-600 border-dashed rounded-lg hover:border-blue-400 transition @error('id_instansi') border-red-500 @enderror">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-neutral-600 dark:text-neutral-400">
                                                <label for="id_instansi" class="relative cursor-pointer bg-white dark:bg-neutral-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload file</span>
                                                    <input id="id_instansi" name="id_instansi" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only" required>
                                                </label>
                                                <p class="pl-1">atau drag & drop</p>
                                            </div>
                                            <p class="text-xs text-neutral-500">PDF, JPG, PNG hingga 2MB</p>
                                        </div>
                                    </div>
                                    @error('id_instansi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Surat Keterangan Atasan Upload (Optional) -->
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Surat Keterangan Atasan
                                        <span class="text-neutral-500">(PDF, maksimal 3MB, opsional)</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 dark:border-neutral-600 border-dashed rounded-lg hover:border-blue-400 transition @error('surat_atasan') border-red-500 @enderror">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-neutral-600 dark:text-neutral-400">
                                                <label for="surat_atasan" class="relative cursor-pointer bg-white dark:bg-neutral-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload file</span>
                                                    <input id="surat_atasan" name="surat_atasan" type="file" accept=".pdf" class="sr-only">
                                                </label>
                                                <p class="pl-1">atau drag & drop</p>
                                            </div>
                                            <p class="text-xs text-neutral-500">PDF hingga 3MB (opsional)</p>
                                        </div>
                                    </div>
                                    @error('surat_atasan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Upload Requirements -->
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Persyaratan Dokumen</h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-400">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Dokumen harus jelas dan terbaca</li>
                                                    <li>Surat permohonan bermaterai dan ditandatangani pejabat berwenang</li>
                                                    <li>ID instansi yang masih berlaku</li>
                                                    <li>Format file sesuai ketentuan (PDF/JPG untuk ID)</li>
                                                    <li>Ukuran file tidak melebihi batas maksimal</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <a href="{{ url('/') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                &larr; Kembali ke Beranda
                            </a>
                            <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Kirim Registrasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Informasi</h3>
                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                            Setelah Anda mengirim registrasi, tim kami akan meninjau aplikasi Anda. 
                            Anda akan menerima email konfirmasi dalam 1-2 hari kerja.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Switch to Akademisi -->
            <div class="mt-4 text-center">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    Dari institusi akademik atau mahasiswa? 
                    <a href="{{ route('public.registrasi.akademisi') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium">
                        Daftar sebagai Akademisi
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Simple file upload functionality without DOM manipulation issues
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = ['surat_permohonan', 'id_instansi', 'surat_atasan'];
            
            fileInputs.forEach(function(inputId) {
                const input = document.getElementById(inputId);
                if (!input) return;
                
                const dropZone = input.closest('.border-dashed');
                
                // File input change handler - safe approach that preserves input
                input.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        
                        // Validate file
                        if (validateFile(inputId, file)) {
                            // Show success state
                            dropZone.classList.add('border-green-400', 'bg-green-50');
                            dropZone.classList.remove('border-neutral-300', 'border-red-500');
                            
                            // Add success indicator without destroying the input
                            const existingSuccess = dropZone.querySelector('.file-success-indicator');
                            if (existingSuccess) {
                                existingSuccess.remove();
                            }
                            
                            const successDiv = document.createElement('div');
                            successDiv.className = 'file-success-indicator mt-2 text-sm text-green-600 font-medium text-center';
                            successDiv.innerHTML = `✓ ${file.name} (${formatFileSize(file.size)})`;
                            dropZone.appendChild(successDiv);
                        } else {
                            // Clear invalid file
                            input.value = '';
                            dropZone.classList.add('border-red-500');
                            dropZone.classList.remove('border-green-400', 'bg-green-50', 'border-neutral-300');
                            
                            // Remove success indicator if exists
                            const successDiv = dropZone.querySelector('.file-success-indicator');
                            if (successDiv) {
                                successDiv.remove();
                            }
                        }
                    }
                });
                
                // Drag and drop handlers
                dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropZone.classList.add('border-blue-400', 'bg-blue-50');
                });
                
                dropZone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
                });
                
                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
                    
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        input.files = files;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            });
            
            function validateFile(inputId, file) {
                const maxSizes = {
                    'surat_permohonan': 5 * 1024 * 1024, // 5MB
                    'id_instansi': 2 * 1024 * 1024,      // 2MB
                    'surat_atasan': 3 * 1024 * 1024      // 3MB
                };
                
                const allowedTypes = {
                    'surat_permohonan': ['application/pdf'],
                    'id_instansi': ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'],
                    'surat_atasan': ['application/pdf']
                };
                
                // Check file size
                if (file.size > maxSizes[inputId]) {
                    alert(`Ukuran file terlalu besar. Maksimal ${formatFileSize(maxSizes[inputId])}`);
                    return false;
                }
                
                // Check file type
                if (!allowedTypes[inputId].includes(file.type)) {
                    alert('Tipe file tidak diizinkan. Periksa format yang diperbolehkan.');
                    return false;
                }
                
                return true;
            }
            
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
        
        // Global function for clearing files (if needed)
        function clearFile(inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                input.value = '';
                const dropZone = input.closest('.border-dashed');
                dropZone.classList.remove('border-green-400', 'bg-green-50', 'border-red-500');
                dropZone.classList.add('border-neutral-300');
                
                // Remove success indicator if exists
                const successDiv = dropZone.querySelector('.file-success-indicator');
                if (successDiv) {
                    successDiv.remove();
                }
            }
        }
    </script>
</x-layouts.landing>
