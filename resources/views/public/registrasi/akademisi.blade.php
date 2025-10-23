<x-layouts.landing title="Registrasi Akses Akademisi - SIKOLBIA">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <div class="py-12 bg-white" x-data="registrasiForm()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-neutral-700 hover:text-blue-600">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-blue-600 font-medium">Registrasi Akademisi</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-purple-600 text-3xl"></i>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                        Registrasi Akses Akademisi
                    </h1>
                    <p class="text-xl text-neutral-600 max-w-2xl mx-auto">
                        Dapatkan akses penelitian ke data NBM lengkap untuk keperluan riset, 
                        publikasi ilmiah, dan pengembangan ilmu pengetahuan
                    </p>
                </div>
            </div>

        <!-- Benefits Section -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">
                <i class="fas fa-star text-yellow-600 mr-2"></i>
                Keuntungan Akses Akademisi (Level 3)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-database text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Dataset Penelitian</h3>
                    <p class="text-sm text-gray-600">Akses lengkap data NBM untuk riset dan analisis akademik</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Tools Analisis</h3>
                    <p class="text-sm text-gray-600">Akses ke API dan tools statistik untuk analisis mendalam</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-file-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Publikasi Support</h3>
                    <p class="text-sm text-gray-600">Dukungan untuk sitasi dan penggunaan dalam publikasi ilmiah</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-download text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Raw Data Access</h3>
                    <p class="text-sm text-gray-600">Download data mentah dalam berbagai format untuk keperluan riset</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Kolaborasi Riset</h3>
                    <p class="text-sm text-gray-600">Akses ke community akademisi dan peluang kolaborasi</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-code text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">API Research</h3>
                    <p class="text-sm text-gray-600">Akses khusus API untuk integrasi dengan sistem penelitian</p>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-user-graduate text-purple-600 mr-2"></i>
                Form Registrasi Akademisi
            </h2>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-green-800 font-medium">Registrasi Berhasil!</h3>
                            <p class="text-green-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('public.registrasi.proses') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="tipe_akses" value="akademisi">

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gelar Akademik</label>
                        <input type="text" name="gelar" 
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="S1/S2/S3, Prof., Dr., dll.">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Akademik *</label>
                        <input type="email" name="email" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="email@universitas.ac.id">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIDN/NIDK/NIM</label>
                        <input type="text" name="nidn" 
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Nomor identitas akademik">
                    </div>
                </div>

                <!-- Institution Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Institusi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Institusi *</label>
                            <input type="text" name="institusi" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Universitas/Institut/Politeknik">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fakultas/Sekolah *</label>
                            <input type="text" name="fakultas" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Nama fakultas/sekolah">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan/Program Studi *</label>
                            <input type="text" name="jurusan" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Nama jurusan/prodi">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status_akademik" required
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Pilih Status</option>
                                <option value="mahasiswa_s1">Mahasiswa S1</option>
                                <option value="mahasiswa_s2">Mahasiswa S2</option>
                                <option value="mahasiswa_s3">Mahasiswa S3</option>
                                <option value="dosen">Dosen</option>
                                <option value="peneliti">Peneliti</option>
                                <option value="asisten_peneliti">Asisten Peneliti</option>
                                <option value="postdoc">Post-Doctoral Researcher</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Research Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Penelitian</h3>
                    
                    <div class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Penelitian *</label>
                                <select name="bidang_penelitian" required
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Pilih Bidang</option>
                                    <option value="ketahanan_pangan">Ketahanan Pangan</option>
                                    <option value="nutrisi_gizi">Nutrisi dan Gizi</option>
                                    <option value="ekonomi_pertanian">Ekonomi Pertanian</option>
                                    <option value="kebijakan_pangan">Kebijakan Pangan</option>
                                    <option value="teknologi_pangan">Teknologi Pangan</option>
                                    <option value="statistik_demografi">Statistik Demografi</option>
                                    <option value="machine_learning">Machine Learning/AI</option>
                                    <option value="geografi_spasial">Geografi dan Analisis Spasial</option>
                                    <option value="sosiologi_pedesaan">Sosiologi Pedesaan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Penelitian *</label>
                                <select name="jenis_penelitian" required
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Pilih Jenis</option>
                                    <option value="skripsi">Skripsi (S1)</option>
                                    <option value="tesis">Tesis (S2)</option>
                                    <option value="disertasi">Disertasi (S3)</option>
                                    <option value="penelitian_dosen">Penelitian Dosen</option>
                                    <option value="hibah_penelitian">Hibah Penelitian</option>
                                    <option value="publikasi_jurnal">Publikasi Jurnal</option>
                                    <option value="book_chapter">Book Chapter</option>
                                    <option value="konferensi">Paper Konferensi</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Penelitian *</label>
                            <input type="text" name="judul_penelitian" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Judul lengkap penelitian/tugas akhir">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Abstrak/Deskripsi Penelitian *</label>
                            <textarea name="abstrak_penelitian" rows="4" required
                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Jelaskan tujuan penelitian, metodologi yang akan digunakan, dan kontribusi yang diharapkan..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pembimbing/Supervisor</label>
                                <input type="text" name="nama_pembimbing" 
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="Prof./Dr. [Nama Pembimbing]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Selesai</label>
                                <input type="date" name="target_selesai" 
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Requirements -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Kebutuhan Data</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data yang Dibutuhkan *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="data_dibutuhkan[]" value="konsumsi_kalori" class="mr-2">
                                    <span class="text-sm">Konsumsi Kalori per Komoditas</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data_dibutuhkan[]" value="data_regional" class="mr-2">
                                    <span class="text-sm">Data Regional/Provinsi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data_dibutuhkan[]" value="time_series" class="mr-2">
                                    <span class="text-sm">Time Series Historis</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data_dibutuhkan[]" value="prediksi_ai" class="mr-2">
                                    <span class="text-sm">Hasil Prediksi AI</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data_dibutuhkan[]" value="nutrisi_lengkap" class="mr-2">
                                    <span class="text-sm">Data Nutrisi Lengkap</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data_dibutuhkan[]" value="raw_data" class="mr-2">
                                    <span class="text-sm">Raw Dataset untuk ML</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rencana Publikasi</label>
                            <textarea name="rencana_publikasi" rows="2"
                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Jelaskan rencana publikasi hasil penelitian (jurnal target, konferensi, dll.)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Academic Verification -->
                <div class="bg-purple-50 border-l-4 border-purple-500 p-4">
                    <div class="flex">
                        <i class="fas fa-university text-purple-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-purple-800 font-medium">Verifikasi Akademik</h3>
                            <p class="text-purple-700 text-sm mt-1">
                                Setelah submit form ini, Anda akan dihubungi untuk verifikasi:
                            </p>
                            <ul class="text-purple-700 text-sm mt-2 space-y-1">
                                <li>• Konfirmasi dengan email institusi (.ac.id)</li>
                                <li>• Verifikasi status akademik melalui SISTER/Portal institusi</li>
                                <li>• Surat rekomendasi dari pembimbing/supervisor</li>
                                <li>• Proposal penelitian (untuk mahasiswa S2/S3)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Ajukan Akses Akademisi
                    </button>
                    <p class="text-center text-sm text-gray-500 mt-2">
                        Proses verifikasi akademik membutuhkan 2-5 hari kerja
                    </p>
                </div>
            </form>
        </div>

        <!-- Academic Ethics -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-8">
            <h2 class="text-lg font-bold text-yellow-900 mb-4">
                <i class="fas fa-scroll text-yellow-600 mr-2"></i>
                Etika Penggunaan Data Akademik
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-yellow-800">
                <div>
                    <h3 class="font-bold mb-2">Kewajiban Peneliti:</h3>
                    <ul class="space-y-1">
                        <li>• Mencantumkan sitasi yang benar</li>
                        <li>• Tidak membagikan data ke pihak ketiga</li>
                        <li>• Menggunakan data sesuai tujuan penelitian</li>
                        <li>• Melaporkan hasil penelitian ke Pusdatin</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-2">Hak Peneliti:</h3>
                    <ul class="space-y-1">
                        <li>• Akses data lengkap untuk riset</li>
                        <li>• Support teknis dari tim ahli</li>
                        <li>• Peluang kolaborasi riset</li>
                        <li>• Credit dalam publikasi resmi</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="text-center">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Tim Academic Support</h3>
            <p class="text-gray-600 mb-4">Kami siap membantu penelitian Anda dengan data berkualitas tinggi</p>
            <div class="space-x-4">
                <span class="inline-flex items-center text-sm text-gray-600">
                    <i class="fas fa-envelope text-purple-600 mr-2"></i>
                    research@pusdatin.id
                </span>
                <span class="inline-flex items-center text-sm text-gray-600">
                    <i class="fas fa-users text-green-600 mr-2"></i>
                    Academic Partnership Program
                </span>
            </div>
        </div>

        </div> <!-- End Content Container -->
    </div>

    <script>
        function registrasiForm() {
            return {
                init() {
                    // Form initialization if needed
                }
            }
        }
    </script>
</x-layouts.landing>