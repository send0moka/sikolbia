<x-layouts.landing>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">
                    {{ isset($isResubmit) && $isResubmit ? 'Lengkapi Dokumen Registrasi' : 'Registrasi Akses Pemerintah' }}
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400">
                    {{ isset($isResubmit) && $isResubmit ? 'Silakan lengkapi atau perbarui dokumen yang diperlukan' : 'Lengkapi formulir di bawah untuk mendapatkan akses ke sistem SIKOLBIA' }}
                </p>
                @if(isset($isResubmit) && $isResubmit && isset($registrasi) && $registrasi->catatan_admin)
                <div class="mt-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                    <p class="text-sm text-orange-800 dark:text-orange-300 font-medium">Catatan Admin:</p>
                    <p class="text-sm text-orange-700 dark:text-orange-400 mt-1">{{ $registrasi->catatan_admin }}</p>
                </div>
                @endif
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
                    <form action="{{ isset($isResubmit) && $isResubmit ? route('public.registrasi.resubmit.process', $token) : route('public.registrasi.proses') }}" method="POST" enctype="multipart/form-data">
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
                                        value="{{ old('nama_lengkap', isset($registrasi) ? $registrasi->nama_lengkap : '') }}"
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
                                        value="{{ old('email', isset($registrasi) ? $registrasi->email : '') }}"
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
                                        value="{{ old('telepon', isset($registrasi) ? $registrasi->telepon : '') }}"
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
                                        value="{{ old('instansi', isset($registrasi) ? $registrasi->instansi : '') }}"
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
                                        <option value="Dinas Pertanian" {{ old('jenis_dinas', isset($registrasi) ? $registrasi->jenis_dinas : '') == 'Dinas Pertanian' ? 'selected' : '' }}>Dinas Pertanian</option>
                                        <option value="Dinas Pangan" {{ old('jenis_dinas', isset($registrasi) ? $registrasi->jenis_dinas : '') == 'Dinas Pangan' ? 'selected' : '' }}>Dinas Pangan</option>
                                        <option value="Dinas Ketahanan Pangan" {{ old('jenis_dinas', isset($registrasi) ? $registrasi->jenis_dinas : '') == 'Dinas Ketahanan Pangan' ? 'selected' : '' }}>Dinas Ketahanan Pangan</option>
                                        <option value="BPS" {{ old('jenis_dinas', isset($registrasi) ? $registrasi->jenis_dinas : '') == 'BPS' ? 'selected' : '' }}>BPS</option>
                                        <option value="Lainnya" {{ old('jenis_dinas', isset($registrasi) ? $registrasi->jenis_dinas : '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                        value="{{ old('jabatan', isset($registrasi) ? $registrasi->jabatan : '') }}"
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

                            @php
                                $tujuanPenggunaan = old('tujuan_penggunaan', isset($registrasi) && $registrasi->tujuan_penggunaan ? $registrasi->tujuan_penggunaan : []);
                            @endphp

                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Perencanaan Kebijakan" 
                                        {{ in_array('Perencanaan Kebijakan', $tujuanPenggunaan) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Perencanaan Kebijakan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Monitoring dan Evaluasi" 
                                        {{ in_array('Monitoring dan Evaluasi', $tujuanPenggunaan) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Monitoring dan Evaluasi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Penelitian" 
                                        {{ in_array('Penelitian', $tujuanPenggunaan) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-700">
                                    <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Penelitian</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan_penggunaan[]" value="Analisis Data" 
                                        {{ in_array('Analisis Data', $tujuanPenggunaan) ? 'checked' : '' }}
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
                                placeholder="Jelaskan kebutuhan data Anda...">{{ old('deskripsi_kebutuhan', isset($registrasi) ? $registrasi->deskripsi_kebutuhan : '') }}</textarea>
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
                                    
                                    @if(isset($registrasi) && $registrasi->surat_permohonan)
                                    <!-- Existing File Display -->
                                    <div id="existing_surat_permohonan" class="mb-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm text-green-800 dark:text-green-300 font-medium">File sudah ada: {{ basename($registrasi->surat_permohonan) }}</span>
                                            </div>
                                            <button type="button" onclick="clearExistingFile('surat_permohonan')" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 font-medium">
                                                Ganti File
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div id="upload_surat_permohonan" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 dark:border-neutral-600 border-dashed rounded-lg hover:border-blue-400 transition @error('surat_permohonan') border-red-500 @enderror" 
                                         style="{{ isset($registrasi) && $registrasi->surat_permohonan ? 'display: none;' : '' }}">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex justify-center items-center text-sm text-neutral-600 dark:text-neutral-400">
                                                <label for="surat_permohonan" class="relative cursor-pointer bg-white dark:bg-neutral-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload file</span>
                                                    <input id="surat_permohonan" name="surat_permohonan" type="file" accept=".pdf" class="sr-only" {{ isset($registrasi) && $registrasi->surat_permohonan ? '' : 'required' }}>
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
                                    
                                    @if(isset($registrasi) && $registrasi->id_instansi)
                                    <!-- Existing File Display -->
                                    <div id="existing_id_instansi" class="mb-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm text-green-800 dark:text-green-300 font-medium">File sudah ada: {{ basename($registrasi->id_instansi) }}</span>
                                            </div>
                                            <button type="button" onclick="clearExistingFile('id_instansi')" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 font-medium">
                                                Ganti File
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div id="upload_id_instansi" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 dark:border-neutral-600 border-dashed rounded-lg hover:border-blue-400 transition @error('id_instansi') border-red-500 @enderror"
                                         style="{{ isset($registrasi) && $registrasi->id_instansi ? 'display: none;' : '' }}">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex justify-center items-center text-sm text-neutral-600 dark:text-neutral-400">
                                                <label for="id_instansi" class="relative cursor-pointer bg-white dark:bg-neutral-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload file</span>
                                                    <input id="id_instansi" name="id_instansi" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only" {{ isset($registrasi) && $registrasi->id_instansi ? '' : 'required' }}>
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
                                    
                                    @if(isset($registrasi) && $registrasi->surat_atasan)
                                    <!-- Existing File Display -->
                                    <div id="existing_surat_atasan" class="mb-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm text-green-800 dark:text-green-300 font-medium">File sudah ada: {{ basename($registrasi->surat_atasan) }}</span>
                                            </div>
                                            <button type="button" onclick="clearExistingFile('surat_atasan')" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 font-medium">
                                                Ganti File
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div id="upload_surat_atasan" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 dark:border-neutral-600 border-dashed rounded-lg hover:border-blue-400 transition @error('surat_atasan') border-red-500 @enderror"
                                         style="{{ isset($registrasi) && $registrasi->surat_atasan ? 'display: none;' : '' }}">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex justify-center items-center text-sm text-neutral-600 dark:text-neutral-400">
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
                                {{ isset($isResubmit) && $isResubmit ? 'Kirim Ulang Dokumen' : 'Kirim Registrasi' }}
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
        // Function to clear existing file and show upload zone
        function clearExistingFile(inputId) {
            const existingDiv = document.getElementById('existing_' + inputId);
            const uploadDiv = document.getElementById('upload_' + inputId);
            const input = document.getElementById(inputId);
            
            if (existingDiv) existingDiv.style.display = 'none';
            if (uploadDiv) uploadDiv.style.display = 'flex';
            if (input) input.required = true; // Make file required when replacing
        }
        
        // Simple file upload functionality without DOM manipulation issues
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = ['surat_permohonan', 'id_instansi', 'surat_atasan'];
            
            fileInputs.forEach(function(inputId) {
                const input = document.getElementById(inputId);
                if (!input) return;
                
                const dropZone = input.closest('.border-dashed');
                // File input change handler - safe approach that preserves input and updates text
                input.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        
                        // Validate file
                        if (validateFile(inputId, file)) {
                            // Show success state
                            dropZone.classList.add('border-green-400', 'bg-green-50');
                            dropZone.classList.remove('border-neutral-300', 'border-red-500');
                            
                            // Update the text content WITHOUT destroying the input element
                            const textContainer = dropZone.querySelector('.flex');
                            if (textContainer) {
                                // Find the paragraph text element (not the label with input)
                                const dragDropText = textContainer.querySelector('p');
                                if (dragDropText && !dragDropText.dataset.originalContent) {
                                    dragDropText.dataset.originalContent = dragDropText.textContent;
                                }
                                
                                // Update only the drag & drop text, not the entire container
                                if (dragDropText) {
                                    dragDropText.textContent = `✓ ${file.name}`;
                                    dragDropText.classList.add('text-green-600', 'dark:text-green-400', 'font-medium');
                                    dragDropText.classList.remove('pl-1');
                                }
                            }
                            
                            // Update the file size text
                            const sizeText = dropZone.querySelector('.text-xs.text-neutral-500');
                            if (sizeText) {
                                if (!sizeText.dataset.originalContent) {
                                    sizeText.dataset.originalContent = sizeText.textContent;
                                }
                                sizeText.textContent = `${formatFileSize(file.size)} - File berhasil dipilih`;
                                sizeText.classList.add('text-green-600', 'dark:text-green-400');
                                sizeText.classList.remove('text-neutral-500');
                            }
                        } else {
                            // Clear invalid file and reset to original state
                            input.value = '';
                            dropZone.classList.add('border-red-500');
                            dropZone.classList.remove('border-green-400', 'bg-green-50', 'border-neutral-300');
                            
                            // Reset text to original
                            resetTextToOriginal(dropZone);
                        }
                    } else {
                        // No file selected, reset to original state
                        resetTextToOriginal(dropZone);
                        dropZone.classList.remove('border-green-400', 'bg-green-50', 'border-red-500');
                        dropZone.classList.add('border-neutral-300');
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
            
            function resetTextToOriginal(dropZone) {
                const textContainer = dropZone.querySelector('.flex');
                if (textContainer) {
                    const dragDropText = textContainer.querySelector('p');
                    if (dragDropText && dragDropText.dataset.originalContent) {
                        dragDropText.textContent = dragDropText.dataset.originalContent;
                        dragDropText.classList.remove('text-green-600', 'dark:text-green-400', 'font-medium');
                        dragDropText.classList.add('pl-1');
                    }
                }
                
                const sizeText = dropZone.querySelector('.text-xs');
                if (sizeText && sizeText.dataset.originalContent) {
                    sizeText.textContent = sizeText.dataset.originalContent;
                    sizeText.classList.remove('text-green-600', 'dark:text-green-400');
                    sizeText.classList.add('text-neutral-500');
                }
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
                
                // Reset text to original content
                resetTextToOriginal(dropZone);
            }
        }
    </script>
</x-layouts.landing>
