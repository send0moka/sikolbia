<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akses Pemerintah - SIKOLBIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header Navigation -->
    <nav class="bg-green-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <i class="fas fa-seedling text-2xl"></i>
                        <div>
                            <h1 class="text-xl font-bold">SIKOLBIA</h1>
                            <p class="text-xs text-green-200">Registrasi Akses Pemerintah</p>
                        </div>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="hover:text-green-200">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                    <a href="{{ route('public.ketersediaan.dashboard') }}" class="hover:text-green-200">Dashboard Publik</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8" x-data="registrasiForm()">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-university text-blue-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Registrasi Akses Pemerintah
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Dapatkan akses lengkap ke data NBM, prediksi AI, dan tools analisis profesional 
                untuk instansi pemerintah (BPN, Kementan, Bappenas, dll.)
            </p>
        </div>

        <!-- Benefits Section -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">
                <i class="fas fa-star text-yellow-600 mr-2"></i>
                Keuntungan Akses Pemerintah (Level 2)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-database text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Data Historis Lengkap</h3>
                    <p class="text-sm text-gray-600">Akses ke semua data NBM 1993-2024 untuk seluruh komoditas dan wilayah</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-robot text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Prediksi AI Advanced</h3>
                    <p class="text-sm text-gray-600">Prediksi konsumsi dengan SHAP analysis dan confidence interval</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-map text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Analisis Regional</h3>
                    <p class="text-sm text-gray-600">Data per provinsi dengan visualisasi peta interaktif</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-download text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Export Unlimited</h3>
                    <p class="text-sm text-gray-600">Download data dalam format Excel, CSV, PDF tanpa batasan</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bell text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Alert System</h3>
                    <p class="text-sm text-gray-600">Notifikasi otomatis untuk perubahan signifikan konsumsi pangan</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-headset text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Support Priority</h3>
                    <p class="text-sm text-gray-600">Dukungan teknis prioritas dan konsultasi kebijakan</p>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                Form Registrasi
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
                <input type="hidden" name="tipe_akses" value="pemerintah">

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP/NIK *</label>
                        <input type="text" name="nip_nik" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nomor Induk Pegawai/Kependudukan">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Instansi *</label>
                        <input type="email" name="email" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="email@instansi.go.id">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                        <input type="tel" name="telepon" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="+62">
                    </div>
                </div>

                <!-- Institution Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Instansi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi *</label>
                            <select name="instansi" required
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Instansi</option>
                                <option value="BPN - Badan Pertanahan Nasional">BPN - Badan Pertanahan Nasional</option>
                                <option value="Kementerian Pertanian">Kementerian Pertanian</option>
                                <option value="Bappenas">Bappenas</option>
                                <option value="Kementerian Koordinator Perekonomian">Kemenko Perekonomian</option>
                                <option value="Kementerian Perdagangan">Kementerian Perdagangan</option>
                                <option value="BPS - Badan Pusat Statistik">BPS - Badan Pusat Statistik</option>
                                <option value="Kementerian Kelautan dan Perikanan">Kementerian Kelautan dan Perikanan</option>
                                <option value="BULOG">BULOG</option>
                                <option value="Pemerintah Provinsi">Pemerintah Provinsi</option>
                                <option value="Pemerintah Kabupaten/Kota">Pemerintah Kabupaten/Kota</option>
                                <option value="lainnya">Lainnya (sebutkan di catatan)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan *</label>
                            <input type="text" name="jabatan" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Jabatan dalam instansi">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja/Bagian *</label>
                            <input type="text" name="unit_kerja" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Divisi/Bagian/Unit kerja">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi/Wilayah Kerja *</label>
                            <select name="provinsi" required
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Provinsi</option>
                                <option value="Nasional">Nasional</option>
                                <option value="DKI Jakarta">DKI Jakarta</option>
                                <option value="Jawa Barat">Jawa Barat</option>
                                <option value="Jawa Tengah">Jawa Tengah</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                                <option value="Sumatera Utara">Sumatera Utara</option>
                                <option value="Sumatera Selatan">Sumatera Selatan</option>
                                <!-- Add other provinces as needed -->
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Usage Purpose -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Tujuan Penggunaan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Utama Akses Data *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan[]" value="perencanaan_kebijakan" class="mr-2">
                                    <span class="text-sm">Perencanaan Kebijakan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan[]" value="monitoring_ketahanan" class="mr-2">
                                    <span class="text-sm">Monitoring Ketahanan Pangan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan[]" value="analisis_regional" class="mr-2">
                                    <span class="text-sm">Analisis Regional</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan[]" value="laporan_rutin" class="mr-2">
                                    <span class="text-sm">Laporan Rutin</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan[]" value="prediksi_konsumsi" class="mr-2">
                                    <span class="text-sm">Prediksi Konsumsi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tujuan[]" value="evaluasi_program" class="mr-2">
                                    <span class="text-sm">Evaluasi Program</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kebutuhan Data</label>
                            <textarea name="deskripsi_kebutuhan" rows="3"
                                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Jelaskan secara spesifik kebutuhan data dan bagaimana akan digunakan..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Documents Upload Note -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-blue-800 font-medium">Dokumen yang Diperlukan</h3>
                            <p class="text-blue-700 text-sm mt-1">
                                Setelah submit form ini, Anda akan dihubungi untuk melengkapi dokumen:
                            </p>
                            <ul class="text-blue-700 text-sm mt-2 space-y-1">
                                <li>• Surat permohonan akses data (resmi dari instansi)</li>
                                <li>• Fotokopi kartu pegawai/ID instansi</li>
                                <li>• Surat keterangan dari atasan langsung</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan Permohonan Akses
                    </button>
                    <p class="text-center text-sm text-gray-500 mt-2">
                        Tim kami akan memproses permohonan dalam 1-3 hari kerja
                    </p>
                </div>
            </form>
        </div>

        <!-- Process Timeline -->
        <div class="bg-gray-50 rounded-lg p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">
                <i class="fas fa-clock text-green-600 mr-2"></i>
                Proses Persetujuan
            </h2>
            <div class="flex flex-wrap justify-center items-center space-x-4">
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mb-2">1</div>
                    <div class="text-sm font-medium">Submit Form</div>
                    <div class="text-xs text-gray-500">Hari ini</div>
                </div>

                <i class="fas fa-arrow-right text-gray-400 hidden md:block"></i>

                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mb-2">2</div>
                    <div class="text-sm font-medium">Verifikasi</div>
                    <div class="text-xs text-gray-500">1 hari kerja</div>
                </div>

                <i class="fas fa-arrow-right text-gray-400 hidden md:block"></i>

                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center mb-2">3</div>
                    <div class="text-sm font-medium">Kelengkapan</div>
                    <div class="text-xs text-gray-500">2 hari kerja</div>
                </div>

                <i class="fas fa-arrow-right text-gray-400 hidden md:block"></i>

                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mb-2">4</div>
                    <div class="text-sm font-medium">Aktivasi</div>
                    <div class="text-xs text-gray-500">Akses aktif</div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="text-center">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Butuh Bantuan?</h3>
            <p class="text-gray-600 mb-4">Tim support kami siap membantu proses registrasi Anda</p>
            <div class="space-x-4">
                <span class="inline-flex items-center text-sm text-gray-600">
                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                    support@pusdatin.id
                </span>
                <span class="inline-flex items-center text-sm text-gray-600">
                    <i class="fas fa-phone text-green-600 mr-2"></i>
                    +62 21-xxxx-xxxx
                </span>
            </div>
        </div>
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
</body>
</html>