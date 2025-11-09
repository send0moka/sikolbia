<x-layouts.landing>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">
                    📄 Upload Dokumen Tambahan
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400">
                    Lengkapi dokumen yang diperlukan untuk proses verifikasi
                </p>
            </div>

            <!-- Info Registrasi -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <h3 class="font-medium text-blue-900 dark:text-blue-300 mb-2">Informasi Registrasi Anda</h3>
                <div class="space-y-1 text-sm">
                    <p class="text-blue-800 dark:text-blue-400"><strong>Nama:</strong> {{ $registrasi->nama_lengkap }}</p>
                    <p class="text-blue-800 dark:text-blue-400"><strong>Email:</strong> {{ $registrasi->email }}</p>
                    <p class="text-blue-800 dark:text-blue-400"><strong>Tipe:</strong> {{ ucfirst($registrasi->tipe_akses) }}</p>
                </div>
            </div>

            @if($registrasi->catatan_admin)
            <!-- Admin Request -->
            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 mb-6">
                <h3 class="font-medium text-orange-900 dark:text-orange-300 mb-2">📋 Dokumen yang Diperlukan</h3>
                <p class="text-orange-800 dark:text-orange-400 whitespace-pre-line">{{ $registrasi->catatan_admin }}</p>
            </div>
            @endif

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

            <!-- Upload Form Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-8">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-6">Upload Dokumen</h2>
                    
                    <form action="{{ url('/registrasi/upload-dokumen/' . $registrasi->id . '?token=' . request()->token) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- File Upload -->
                            <div>
                                <label for="dokumen" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Pilih Dokumen <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="dokumen" id="dokumen" 
                                    class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-neutral-700 dark:text-white @error('dokumen') border-red-500 @enderror" 
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                    required>
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    Format: PDF, JPG, PNG, DOC, DOCX (Max: 10MB)
                                </p>
                                @error('dokumen')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Keterangan (Opsional)
                                </label>
                                <textarea name="keterangan" id="keterangan" rows="3" 
                                    class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-neutral-700 dark:text-white @error('keterangan') border-red-500 @enderror" 
                                    placeholder="Tambahkan keterangan tentang dokumen yang diupload...">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ url('/') }}" class="text-sm text-orange-600 hover:text-orange-800 dark:text-orange-400">
                                    &larr; Kembali ke Beranda
                                </a>
                                <button type="submit" 
                                    class="px-6 py-3 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                                    📤 Upload Dokumen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Uploaded Documents -->
            @if($registrasi->dokumen_tambahan)
            @php
                $dokumen = json_decode($registrasi->dokumen_tambahan, true);
            @endphp
            @if($dokumen && count($dokumen) > 0)
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-8">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Dokumen yang Sudah Diupload</h2>
                    
                    <div class="space-y-3">
                        @foreach($dokumen as $doc)
                        <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-700/50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-neutral-900 dark:text-white">{{ $doc['original_name'] }}</p>
                                    @if(isset($doc['keterangan']) && $doc['keterangan'])
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $doc['keterangan'] }}</p>
                                    @endif
                                    <p class="text-xs text-neutral-500 dark:text-neutral-500">
                                        Diupload: {{ \Carbon\Carbon::parse($doc['uploaded_at'])->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full">
                                ✓ Uploaded
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- Info Box -->
            <div class="mt-6 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-neutral-800 dark:text-neutral-300">Informasi</h3>
                        <div class="mt-1 text-sm text-neutral-700 dark:text-neutral-400 space-y-1">
                            <p>
                                Setelah mengupload dokumen, admin akan mereview dan mengirimkan konfirmasi via email.
                            </p>
                            <p class="mt-2">
                                Anda dapat mengupload lebih dari satu dokumen jika diperlukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.landing>
