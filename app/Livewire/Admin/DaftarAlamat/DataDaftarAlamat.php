<?php

namespace App\Livewire\Admin\DaftarAlamat;

use App\Models\DaftarAlamat;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DataDaftarAlamat extends Component
{
    use WithPagination, WithFileUploads;


    #[Url]
    public $search = '';

    #[Url]
    public $statusFilter = '';

    #[Url]
    public $wilayahFilter = '';

    #[Url]
    public $sortBy = 'id';

    #[Url]
    public $sortDirection = 'asc';

    #[Url]
    public $provinsiFilter = '';

    #[Url]
    public $kabupatenKotaFilter = '';

    public $perPage = 10;
    public $showModal = false;
    public $modalMode = 'create';
    public $selectedId = null;

    // Form properties
    public $provinsi = '';
    public $kabupaten_kota = '';
    public $nama_dinas = '';
    public $alamat = '';
    public $telp = '';
    public $email = '';
    public $website = '';
    public $status = 'Aktif';
    public $latitude = '';
    public $longitude = '';
    public $gambar = null;
    public $oldLatitude = '';
    public $oldLongitude = '';
    public $existingGambar = null;

    // Validation error properties
    public $provinsiValidationError = null;
    public $kabupatenValidationError = null;

    protected $rules = [
        'provinsi' => 'required|string|max:255',
        'kabupaten_kota' => 'required|string|max:255',
        'nama_dinas' => 'required|string|max:255',
        'alamat' => 'required|string',
        'telp' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'website' => 'nullable|url|max:255',
        'status' => 'required|in:Aktif,Tidak Aktif,Draft,Arsip,Pending',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'gambar' => 'nullable|file|image|max:2048',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingWilayahFilter()
    {
        $this->resetPage();
    }

    public function updatingProvinsiFilter()
    {
        $this->resetPage();
    }

    public function updatingKabupatenKotaFilter()
    {
        $this->resetPage();
    }

    public function updatingProvinsi()
    {
        // Don't reset kabupaten_kota if we're in edit mode - let updatedProvinsi handle it more intelligently
        if ($this->modalMode !== 'edit') {
            $this->kabupaten_kota = ''; // Reset kabupaten when province changes (only in create mode)
        }
        $this->provinsiValidationError = null; // Clear validation error
        $this->kabupatenValidationError = null; // Clear kabupaten validation error
    }

    public function updatingKabupatenKota()
    {
        $this->kabupatenValidationError = null; // Clear validation error when typing
    }

    public function updatedGambar()
    {
        // Preserve coordinates before image upload processing
        $this->preserveCoordinates();
        
        // Handle image preview when file is uploaded
        $this->dispatch('imageUploaded');
        
        // Restore coordinates after processing
        $this->restoreCoordinates();
    }

    public function updateCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    protected function preserveCoordinates()
    {
        // Store current coordinates before any update
        $this->oldLatitude = $this->latitude;
        $this->oldLongitude = $this->longitude;
    }

    protected function restoreCoordinates()
    {
        // Restore coordinates if they were reset
        if (empty($this->latitude) && !empty($this->oldLatitude)) {
            $this->latitude = $this->oldLatitude;
        }
        if (empty($this->longitude) && !empty($this->oldLongitude)) {
            $this->longitude = $this->oldLongitude;
        }
    }

    public function validateProvinsi()
    {
        $validProvinsi = [
            'Provinsi Aceh', 'Provinsi Sumatera Utara', 'Provinsi Sumatera Barat', 'Provinsi Riau', 'Provinsi Kepulauan Riau', 'Provinsi Jambi',
            'Provinsi Sumatera Selatan', 'Provinsi Bengkulu', 'Provinsi Lampung', 'Provinsi Kepulauan Bangka Belitung', 'Provinsi Daerah Khusus Jakarta',
            'Provinsi Jawa Barat', 'Provinsi Jawa Tengah', 'Provinsi Daerah Istimewa Yogyakarta', 'Provinsi Jawa Timur', 'Provinsi Banten', 'Provinsi Bali',
            'Provinsi Nusa Tenggara Barat', 'Provinsi Nusa Tenggara Timur', 'Provinsi Kalimantan Barat', 'Provinsi Kalimantan Tengah',
            'Provinsi Kalimantan Selatan', 'Provinsi Kalimantan Timur', 'Provinsi Kalimantan Utara', 'Provinsi Sulawesi Utara',
            'Provinsi Sulawesi Tengah', 'Provinsi Sulawesi Selatan', 'Provinsi Sulawesi Tenggara', 'Provinsi Gorontalo', 'Provinsi Sulawesi Barat',
            'Provinsi Maluku', 'Provinsi Maluku Utara', 'Provinsi Papua Barat', 'Provinsi Papua Barat Daya', 'Provinsi Papua Selatan',
            'Provinsi Papua Tengah', 'Provinsi Papua', 'Provinsi Papua Pegunungan'
        ];

        if (!empty($this->provinsi) && !in_array($this->provinsi, $validProvinsi)) {
            $this->provinsiValidationError = 'Provinsi tidak valid. Silakan pilih dari daftar yang tersedia.';
            $this->kabupaten_kota = ''; // Reset kabupaten if province is invalid
        } else {
            $this->provinsiValidationError = null;
        }
    }

    public function updatedProvinsi()
    {
        // Don't reset kabupaten_kota if we're in edit mode and the kabupaten is valid for the selected province
        $shouldResetKabupaten = true;
        
        if ($this->modalMode === 'edit' && !empty($this->kabupaten_kota) && !empty($this->provinsi)) {
            $validKabupaten = $this->getKabupatenByProvinsi($this->provinsi);
            // Keep the kabupaten if it's valid for the selected province
            if (in_array($this->kabupaten_kota, $validKabupaten)) {
                $shouldResetKabupaten = false;
            }
        }
        
        // Reset kabupaten/kota when province changes (except in edit mode with valid kabupaten)
        if ($shouldResetKabupaten) {
            $this->kabupaten_kota = '';
        }
        
        $this->kabupatenValidationError = null;
        $this->provinsiValidationError = null;
        
        // Validate the new province selection
        $this->validateProvinsi();
    }

    public function validateKabupatenKota()
    {
        if (!empty($this->kabupaten_kota) && !empty($this->provinsi)) {
            $validKabupaten = $this->getKabupatenByProvinsi($this->provinsi);
            
            if (!in_array($this->kabupaten_kota, $validKabupaten)) {
                $this->kabupatenValidationError = 'Kabupaten/Kota tidak valid untuk provinsi ' . $this->provinsi . '. Silakan pilih dari daftar yang tersedia.';
            } else {
                $this->kabupatenValidationError = null;
            }
        } else if (!empty($this->kabupaten_kota) && empty($this->provinsi)) {
            $this->kabupatenValidationError = 'Silakan pilih provinsi terlebih dahulu.';
        } else {
            $this->kabupatenValidationError = null;
        }
    }

    public function getKabupatenByProvinsi($provinsi)
    {
        $kabupatenData = [
            'Provinsi Aceh' => [
                'Kabupaten Aceh Barat', 'Kabupaten Aceh Barat Daya', 'Kabupaten Aceh Besar', 'Kabupaten Aceh Jaya',
                'Kabupaten Aceh Selatan', 'Kabupaten Aceh Singkil', 'Kabupaten Aceh Tamiang', 'Kabupaten Aceh Tengah',
                'Kabupaten Aceh Tenggara', 'Kabupaten Aceh Timur', 'Kabupaten Aceh Utara', 'Kabupaten Bener Meriah',
                'Kabupaten Bireuen', 'Kabupaten Gayo Lues', 'Kabupaten Nagan Raya', 'Kabupaten Pidie',
                'Kabupaten Pidie Jaya', 'Kabupaten Simeulue', 'Kota Banda Aceh', 'Kota Langsa',
                'Kota Lhokseumawe', 'Kota Sabang', 'Kota Subulussalam'
            ],
            'Provinsi Sumatera Utara' => [
                'Kabupaten Asahan', 'Kabupaten Batubara', 'Kabupaten Dairi', 'Kabupaten Deli Serdang',
                'Kabupaten Humbang Hasundutan', 'Kabupaten Karo', 'Kabupaten Labuhanbatu', 'Kabupaten Labuhanbatu Selatan',
                'Kabupaten Labuhanbatu Utara', 'Kabupaten Langkat', 'Kabupaten Mandailing Natal', 'Kabupaten Nias',
                'Kabupaten Nias Barat', 'Kabupaten Nias Selatan', 'Kabupaten Nias Utara', 'Kabupaten Padang Lawas',
                'Kabupaten Padang Lawas Utara', 'Kabupaten Pakpak Bharat', 'Kabupaten Samosir', 'Kabupaten Serdang Bedagai',
                'Kabupaten Simalungun', 'Kabupaten Tapanuli Selatan', 'Kabupaten Tapanuli Tengah', 'Kabupaten Tapanuli Utara',
                'Kabupaten Toba Samosir', 'Kota Binjai', 'Kota Gunungsitoli', 'Kota Medan', 'Kota Padangsidimpuan',
                'Kota Pematangsiantar', 'Kota Sibolga', 'Kota Tanjungbalai', 'Kota Tebing Tinggi'
            ],
            'Provinsi Sumatera Barat' => [
                'Kabupaten Agam', 'Kabupaten Dharmasraya', 'Kabupaten Kepulauan Mentawai', 'Kabupaten Lima Puluh Kota',
                'Kabupaten Padang Pariaman', 'Kabupaten Pasaman', 'Kabupaten Pasaman Barat', 'Kabupaten Pesisir Selatan',
                'Kabupaten Sijunjung', 'Kabupaten Solok', 'Kabupaten Solok Selatan', 'Kabupaten Tanah Datar',
                'Kota Bukittinggi', 'Kota Padang', 'Kota Padangpanjang', 'Kota Pariaman', 'Kota Payakumbuh',
                'Kota Sawahlunto', 'Kota Solok'
            ],
            'Provinsi Riau' => [
                'Kabupaten Bengkalis', 'Kabupaten Indragiri Hilir', 'Kabupaten Indragiri Hulu', 'Kabupaten Kampar',
                'Kabupaten Kepulauan Meranti', 'Kabupaten Kuantan Singingi', 'Kabupaten Pelalawan', 'Kabupaten Rokan Hilir',
                'Kabupaten Rokan Hulu', 'Kabupaten Siak', 'Kota Dumai', 'Kota Pekanbaru'
            ],
            'Provinsi Kepulauan Riau' => [
                'Kabupaten Bintan', 'Kabupaten Karimun', 'Kabupaten Kepulauan Anambas', 'Kabupaten Lingga',
                'Kabupaten Natuna', 'Kota Batam', 'Kota Tanjungpinang'
            ],
            'Provinsi Jambi' => [
                'Kabupaten Batanghari', 'Kabupaten Bungo', 'Kabupaten Kerinci', 'Kabupaten Merangin',
                'Kabupaten Muaro Jambi', 'Kabupaten Sarolangun', 'Kabupaten Tanjung Jabung Barat', 'Kabupaten Tanjung Jabung Timur',
                'Kabupaten Tebo', 'Kota Jambi', 'Kota Sungai Penuh'
            ],
            'Provinsi Sumatera Selatan' => [
                'Kabupaten Banyuasin', 'Kabupaten Empat Lawang', 'Kabupaten Lahat', 'Kabupaten Muara Enim',
                'Kabupaten Musi Banyuasin', 'Kabupaten Musi Rawas', 'Kabupaten Musi Rawas Utara', 'Kabupaten Ogan Ilir',
                'Kabupaten Ogan Komering Ilir', 'Kabupaten Ogan Komering Ulu', 'Kabupaten Ogan Komering Ulu Selatan',
                'Kabupaten Ogan Komering Ulu Timur', 'Kabupaten Penukal Abab Lematang Ilir', 'Kota Lubuklinggau',
                'Kota Pagar Alam', 'Kota Palembang', 'Kota Prabumulih'
            ],
            'Provinsi Bengkulu' => [
                'Kabupaten Bengkulu Selatan', 'Kabupaten Bengkulu Tengah', 'Kabupaten Bengkulu Utara', 'Kabupaten Kaur',
                'Kabupaten Kepahiang', 'Kabupaten Lebong', 'Kabupaten Mukomuko', 'Kabupaten Rejang Lebong',
                'Kabupaten Seluma', 'Kota Bengkulu'
            ],
            'Provinsi Lampung' => [
                'Kabupaten Lampung Barat', 'Kabupaten Lampung Selatan', 'Kabupaten Lampung Tengah', 'Kabupaten Lampung Timur',
                'Kabupaten Lampung Utara', 'Kabupaten Mesuji', 'Kabupaten Pesawaran', 'Kabupaten Pesisir Barat',
                'Kabupaten Pringsewu', 'Kabupaten Tanggamus', 'Kabupaten Tulang Bawang', 'Kabupaten Tulang Bawang Barat',
                'Kabupaten Way Kanan', 'Kota Bandar Lampung', 'Kota Metro'
            ],
            'Provinsi Kepulauan Bangka Belitung' => [
                'Kabupaten Bangka', 'Kabupaten Bangka Barat', 'Kabupaten Bangka Selatan', 'Kabupaten Bangka Tengah',
                'Kabupaten Belitung', 'Kabupaten Belitung Timur', 'Kota Pangkalpinang'
            ],
            'Provinsi Daerah Khusus Jakarta' => [
                'Kabupaten Kepulauan Seribu', 'Kota Jakarta Barat', 'Kota Jakarta Pusat', 'Kota Jakarta Selatan',
                'Kota Jakarta Timur', 'Kota Jakarta Utara'
            ],
            'Provinsi Jawa Barat' => [
                'Kabupaten Bandung', 'Kabupaten Bandung Barat', 'Kabupaten Bekasi', 'Kabupaten Bogor',
                'Kabupaten Ciamis', 'Kabupaten Cianjur', 'Kabupaten Cirebon', 'Kabupaten Garut',
                'Kabupaten Indramayu', 'Kabupaten Karawang', 'Kabupaten Kuningan', 'Kabupaten Majalengka',
                'Kabupaten Pangandaran', 'Kabupaten Purwakarta', 'Kabupaten Subang', 'Kabupaten Sukabumi',
                'Kabupaten Sumedang', 'Kabupaten Tasikmalaya', 'Kota Bandung', 'Kota Banjar',
                'Kota Bekasi', 'Kota Bogor', 'Kota Cimahi', 'Kota Cirebon', 'Kota Depok',
                'Kota Sukabumi', 'Kota Tasikmalaya'
            ],
            'Provinsi Jawa Tengah' => [
                'Kabupaten Banjarnegara', 'Kabupaten Banyumas', 'Kabupaten Batang', 'Kabupaten Blora',
                'Kabupaten Boyolali', 'Kabupaten Brebes', 'Kabupaten Cilacap', 'Kabupaten Demak',
                'Kabupaten Grobogan', 'Kabupaten Jepara', 'Kabupaten Karanganyar', 'Kabupaten Kebumen',
                'Kabupaten Kendal', 'Kabupaten Klaten', 'Kabupaten Kudus', 'Kabupaten Magelang',
                'Kabupaten Pati', 'Kabupaten Pekalongan', 'Kabupaten Pemalang', 'Kabupaten Purbalingga',
                'Kabupaten Purworejo', 'Kabupaten Rembang', 'Kabupaten Semarang', 'Kabupaten Sragen',
                'Kabupaten Sukoharjo', 'Kabupaten Tegal', 'Kabupaten Temanggung', 'Kabupaten Wonogiri',
                'Kabupaten Wonosobo', 'Kota Magelang', 'Kota Pekalongan', 'Kota Salatiga',
                'Kota Semarang', 'Kota Surakarta', 'Kota Tegal'
            ],
            'Provinsi Daerah Istimewa Yogyakarta' => [
                'Kabupaten Bantul', 'Kabupaten Gunungkidul', 'Kabupaten Kulon Progo', 'Kabupaten Sleman', 'Kota Yogyakarta'
            ],
            'Provinsi Jawa Timur' => [
                'Kabupaten Bangkalan', 'Kabupaten Banyuwangi', 'Kabupaten Blitar', 'Kabupaten Bojonegoro',
                'Kabupaten Bondowoso', 'Kabupaten Gresik', 'Kabupaten Jember', 'Kabupaten Jombang',
                'Kabupaten Kediri', 'Kabupaten Lamongan', 'Kabupaten Lumajang', 'Kabupaten Madiun',
                'Kabupaten Magetan', 'Kabupaten Malang', 'Kabupaten Mojokerto', 'Kabupaten Nganjuk',
                'Kabupaten Ngawi', 'Kabupaten Pacitan', 'Kabupaten Pamekasan', 'Kabupaten Pasuruan',
                'Kabupaten Ponorogo', 'Kabupaten Probolinggo', 'Kabupaten Sampang', 'Kabupaten Sidoarjo',
                'Kabupaten Situbondo', 'Kabupaten Sumenep', 'Kabupaten Trenggalek', 'Kabupaten Tuban',
                'Kabupaten Tulungagung', 'Kota Batu', 'Kota Blitar', 'Kota Kediri', 'Kota Madiun',
                'Kota Malang', 'Kota Mojokerto', 'Kota Pasuruan', 'Kota Probolinggo', 'Kota Surabaya'
            ],
            'Provinsi Banten' => [
                'Kabupaten Lebak', 'Kabupaten Pandeglang', 'Kabupaten Serang', 'Kabupaten Tangerang',
                'Kota Cilegon', 'Kota Serang', 'Kota Tangerang', 'Kota Tangerang Selatan'
            ],
            'Provinsi Bali' => [
                'Kabupaten Badung', 'Kabupaten Bangli', 'Kabupaten Buleleng', 'Kabupaten Gianyar',
                'Kabupaten Jembrana', 'Kabupaten Karangasem', 'Kabupaten Klungkung', 'Kabupaten Tabanan', 'Kota Denpasar'
            ],
            'Provinsi Nusa Tenggara Barat' => [
                'Kabupaten Bima', 'Kabupaten Dompu', 'Kabupaten Lombok Barat', 'Kabupaten Lombok Tengah',
                'Kabupaten Lombok Timur', 'Kabupaten Lombok Utara', 'Kabupaten Sumbawa', 'Kabupaten Sumbawa Barat',
                'Kota Bima', 'Kota Mataram'
            ],
            'Provinsi Nusa Tenggara Timur' => [
                'Kabupaten Alor', 'Kabupaten Belu', 'Kabupaten Ende', 'Kabupaten Flores Timur',
                'Kabupaten Kupang', 'Kabupaten Lembata', 'Kabupaten Manggarai', 'Kabupaten Manggarai Barat',
                'Kabupaten Manggarai Timur', 'Kabupaten Nagekeo', 'Kabupaten Ngada', 'Kabupaten Rote Ndao',
                'Kabupaten Sabu Raijua', 'Kabupaten Sikka', 'Kabupaten Sumba Barat', 'Kabupaten Sumba Barat Daya',
                'Kabupaten Sumba Tengah', 'Kabupaten Sumba Timur', 'Kabupaten Timor Tengah Selatan',
                'Kabupaten Timor Tengah Utara', 'Kota Kupang'
            ],
            'Provinsi Kalimantan Barat' => [
                'Kabupaten Bengkayang', 'Kabupaten Kapuas Hulu', 'Kabupaten Kayong Utara', 'Kabupaten Ketapang',
                'Kabupaten Kubu Raya', 'Kabupaten Landak', 'Kabupaten Melawi', 'Kabupaten Pontianak',
                'Kabupaten Sambas', 'Kabupaten Sanggau', 'Kabupaten Sekadau', 'Kabupaten Sintang',
                'Kota Pontianak', 'Kota Singkawang'
            ],
            'Provinsi Kalimantan Tengah' => [
                'Kabupaten Barito Selatan', 'Kabupaten Barito Timur', 'Kabupaten Barito Utara', 'Kabupaten Gunung Mas',
                'Kabupaten Kapuas', 'Kabupaten Katingan', 'Kabupaten Kotawaringin Barat', 'Kabupaten Kotawaringin Timur',
                'Kabupaten Lamandau', 'Kabupaten Murung Raya', 'Kabupaten Pulang Pisau', 'Kabupaten Sukamara',
                'Kabupaten Seruyan', 'Kota Palangka Raya'
            ],
            'Provinsi Kalimantan Selatan' => [
                'Kabupaten Balangan', 'Kabupaten Banjar', 'Kabupaten Barito Kuala', 'Kabupaten Hulu Sungai Selatan',
                'Kabupaten Hulu Sungai Tengah', 'Kabupaten Hulu Sungai Utara', 'Kabupaten Kotabaru', 'Kabupaten Tabalong',
                'Kabupaten Tanah Bumbu', 'Kabupaten Tanah Laut', 'Kabupaten Tapin', 'Kota Banjarbaru', 'Kota Banjarmasin'
            ],
            'Provinsi Kalimantan Timur' => [
                'Kabupaten Berau', 'Kabupaten Kutai Barat', 'Kabupaten Kutai Kartanegara', 'Kabupaten Kutai Timur',
                'Kabupaten Mahakam Ulu', 'Kabupaten Paser', 'Kabupaten Penajam Paser Utara', 'Kota Balikpapan',
                'Kota Bontang', 'Kota Samarinda'
            ],
            'Provinsi Kalimantan Utara' => [
                'Kabupaten Bulungan', 'Kabupaten Malinau', 'Kabupaten Nunukan', 'Kabupaten Tana Tidung', 'Kota Tarakan'
            ],
            'Provinsi Sulawesi Utara' => [
                'Kabupaten Bolaang Mongondow', 'Kabupaten Bolaang Mongondow Selatan', 'Kabupaten Bolaang Mongondow Timur',
                'Kabupaten Bolaang Mongondow Utara', 'Kabupaten Kepulauan Sangihe', 'Kabupaten Kepulauan Siau Tagulandang Biaro',
                'Kabupaten Kepulauan Talaud', 'Kabupaten Minahasa', 'Kabupaten Minahasa Selatan', 'Kabupaten Minahasa Tenggara',
                'Kabupaten Minahasa Utara', 'Kota Bitung', 'Kota Kotamobagu', 'Kota Manado', 'Kota Tomohon'
            ],
            'Provinsi Sulawesi Tengah' => [
                'Kabupaten Banggai', 'Kabupaten Banggai Kepulauan', 'Kabupaten Banggai Laut', 'Kabupaten Buol',
                'Kabupaten Donggala', 'Kabupaten Morowali', 'Kabupaten Morowali Utara', 'Kabupaten Parigi Moutong',
                'Kabupaten Poso', 'Kabupaten Sigi', 'Kabupaten Tojo Una-Una', 'Kabupaten Toli-Toli', 'Kota Palu'
            ],
            'Provinsi Sulawesi Selatan' => [
                'Kabupaten Bantaeng', 'Kabupaten Barru', 'Kabupaten Bone', 'Kabupaten Bulukumba', 'Kabupaten Enrekang',
                'Kabupaten Gowa', 'Kabupaten Jeneponto', 'Kabupaten Kepulauan Selayar', 'Kabupaten Luwu',
                'Kabupaten Luwu Timur', 'Kabupaten Luwu Utara', 'Kabupaten Maros', 'Kabupaten Pangkajene dan Kepulauan',
                'Kabupaten Pinrang', 'Kabupaten Sidenreng Rappang', 'Kabupaten Sinjai', 'Kabupaten Soppeng',
                'Kabupaten Takalar', 'Kabupaten Tana Toraja', 'Kabupaten Toraja Utara', 'Kabupaten Wajo',
                'Kota Makassar', 'Kota Palopo', 'Kota Parepare'
            ],
            'Provinsi Sulawesi Tenggara' => [
                'Kabupaten Bombana', 'Kabupaten Buton', 'Kabupaten Buton Selatan', 'Kabupaten Buton Tengah',
                'Kabupaten Buton Utara', 'Kabupaten Kolaka', 'Kabupaten Kolaka Timur', 'Kabupaten Kolaka Utara',
                'Kabupaten Konawe', 'Kabupaten Konawe Kepulauan', 'Kabupaten Konawe Selatan', 'Kabupaten Konawe Utara',
                'Kabupaten Muna', 'Kabupaten Muna Barat', 'Kabupaten Wakatobi', 'Kota Bau-Bau', 'Kota Kendari'
            ],
            'Provinsi Gorontalo' => [
                'Kabupaten Boalemo', 'Kabupaten Bone Bolango', 'Kabupaten Gorontalo', 'Kabupaten Gorontalo Utara',
                'Kabupaten Pohuwato', 'Kota Gorontalo'
            ],
            'Provinsi Sulawesi Barat' => [
                'Kabupaten Majene', 'Kabupaten Mamasa', 'Kabupaten Mamuju', 'Kabupaten Mamuju Tengah',
                'Kabupaten Mamuju Utara', 'Kabupaten Polewali Mandar'
            ],
            'Provinsi Maluku' => [
                'Kabupaten Buru', 'Kabupaten Buru Selatan', 'Kabupaten Kepulauan Aru', 'Kabupaten Maluku Barat Daya',
                'Kabupaten Maluku Tengah', 'Kabupaten Maluku Tenggara', 'Kabupaten Maluku Tenggara Barat',
                'Kabupaten Seram Bagian Barat', 'Kabupaten Seram Bagian Timur', 'Kota Ambon', 'Kota Tual'
            ],
            'Provinsi Maluku Utara' => [
                'Kabupaten Halmahera Barat', 'Kabupaten Halmahera Selatan', 'Kabupaten Halmahera Tengah',
                'Kabupaten Halmahera Timur', 'Kabupaten Halmahera Utara', 'Kabupaten Kepulauan Sula',
                'Kabupaten Pulau Morotai', 'Kabupaten Pulau Taliabu', 'Kota Ternate', 'Kota Tidore Kepulauan'
            ],
            'Provinsi Papua Barat' => [
                'Kabupaten Fakfak', 'Kabupaten Kaimana', 'Kabupaten Manokwari', 'Kabupaten Manokwari Selatan',
                'Kabupaten Pegunungan Arfak', 'Kabupaten Raja Ampat', 'Kabupaten Sorong', 'Kabupaten Sorong Selatan',
                'Kabupaten Tambrauw', 'Kabupaten Teluk Bintuni', 'Kabupaten Teluk Wondama', 'Kota Sorong'
            ],
            'Provinsi Papua Barat Daya' => [
                'Kabupaten Maybrat', 'Kabupaten Raja Ampat', 'Kabupaten Sorong', 'Kabupaten Sorong Selatan',
                'Kabupaten Tambrauw'
            ],
            'Provinsi Papua Selatan' => [
                'Kabupaten Asmat', 'Kabupaten Boven Digoel', 'Kabupaten Mappi', 'Kabupaten Merauke'
            ],
            'Provinsi Papua Tengah' => [
                'Kabupaten Deiyai', 'Kabupaten Dogiyai', 'Kabupaten Intan Jaya', 'Kabupaten Mimika',
                'Kabupaten Nabire', 'Kabupaten Paniai', 'Kabupaten Puncak'
            ],
            'Provinsi Papua' => [
                'Kabupaten Biak Numfor', 'Kabupaten Jayapura', 'Kabupaten Keerom', 'Kabupaten Kepulauan Yapen',
                'Kabupaten Mamberamo Raya', 'Kabupaten Sarmi', 'Kabupaten Supiori', 'Kabupaten Waropen', 'Kota Jayapura'
            ],
            'Provinsi Papua Pegunungan' => [
                'Kabupaten Jayawijaya', 'Kabupaten Lanny Jaya', 'Kabupaten Mamberamo Tengah', 'Kabupaten Nduga',
                'Kabupaten Pegunungan Bintang', 'Kabupaten Tolikara', 'Kabupaten Yahukimo', 'Kabupaten Yalimo'
            ]
        ];

        // Jika provinsi tidak ada dalam data lengkap, return array kosong
        if (!isset($kabupatenData[$provinsi])) {
            return [];
        }

        return $kabupatenData[$provinsi];
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->showModal = true;
        $this->dispatch('initializeMap');
    }

    public function test()
    {
        // Simple test method
        session()->flash('message', 'Test berhasil!');
    }

    public function edit($id)
    {
        $alamat = DaftarAlamat::findOrFail($id);
        $this->selectedId = $id;
        $this->modalMode = 'edit';
        
        // Fill all data from the model
        $this->fill($alamat->toArray());
        $this->existingGambar = $alamat->gambar;
        
        // Ensure coordinates are preserved and logged
        $this->oldLatitude = $this->latitude;
        $this->oldLongitude = $this->longitude;
        
        // Log coordinates for debugging
        Log::info('Edit coordinates loaded', [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'nama_dinas' => $this->nama_dinas
        ]);
        
        $this->showModal = true;
        $this->dispatch('initializeMap');
    }

    public function save()
    {
        try {
            // Validate provinsi and kabupaten before saving
            $this->validateProvinsi();
            $this->validateKabupatenKota();
            
            // Check if there are validation errors
            if ($this->provinsiValidationError || $this->kabupatenValidationError) {
                session()->flash('error', 'Harap perbaiki kesalahan validasi sebelum menyimpan.');
                return;
            }

            $this->validate();

            $data = [
                'provinsi' => $this->provinsi,
                'kabupaten_kota' => $this->kabupaten_kota,
                'nama_dinas' => $this->nama_dinas,
                'alamat' => $this->alamat,
                'telp' => $this->telp,
                'email' => $this->email,
                'website' => $this->website,
                'status' => $this->status,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ];

            // Handle image upload
            if ($this->gambar) {
                try {
                    // Ensure storage directory exists
                    if (!Storage::disk('public')->exists('daftar-alamat')) {
                        Storage::disk('public')->makeDirectory('daftar-alamat');
                    }

                    // Delete old image if editing
                    if ($this->modalMode === 'edit' && $this->existingGambar) {
                        Storage::disk('public')->delete($this->existingGambar);
                    }
                    
                    // Store new image
                    $fileName = time() . '_' . $this->gambar->getClientOriginalName();
                    $path = $this->gambar->storeAs('daftar-alamat', $fileName, 'public');
                    $data['gambar'] = $path;
                } catch (\Exception $uploadError) {
                    session()->flash('error', 'Gagal mengupload gambar: ' . $uploadError->getMessage());
                    return;
                }
            } elseif ($this->modalMode === 'edit') {
                // Keep existing image if no new image uploaded
                $data['gambar'] = $this->existingGambar;
            }

            if ($this->modalMode === 'create') {
                DaftarAlamat::create($data);
                session()->flash('message', 'Data alamat berhasil ditambahkan.');
            } else {
                DaftarAlamat::findOrFail($this->selectedId)->update($data);
                session()->flash('message', 'Data alamat berhasil diperbarui.');
            }

            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Livewire handle validation errors normally
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving daftar alamat: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::check() ? Auth::id() : null,
                'data' => $data ?? []
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $alamat = DaftarAlamat::findOrFail($id);
        
        // Delete associated image
        if ($alamat->gambar) {
            Storage::disk('public')->delete($alamat->gambar);
        }
        
        $alamat->delete();
        session()->flash('message', 'Data alamat berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->selectedId = null;
        $this->provinsiValidationError = false;
        $this->kabupatenValidationError = false;
    }

    public function deleteImage()
    {
        if ($this->existingGambar) {
            Storage::disk('public')->delete($this->existingGambar);
            
            if ($this->selectedId) {
                DaftarAlamat::findOrFail($this->selectedId)->update(['gambar' => null]);
            }
            
            $this->existingGambar = null;
            session()->flash('message', 'Gambar berhasil dihapus.');
        }
    }

    public function resetForm()
    {
        $this->reset([
            'provinsi', 'kabupaten_kota', 'nama_dinas', 'alamat', 'telp', 
            'email', 'website', 'status', 
            'latitude', 'longitude', 'gambar', 'existingGambar',
            'provinsiValidationError', 'kabupatenValidationError'
        ]);
        $this->status = 'Aktif';
        $this->latitude = -2.5489;  // Indonesia center
        $this->longitude = 118.0149;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'wilayahFilter', 'provinsiFilter', 'kabupatenKotaFilter']);
        $this->resetPage();
    }

    public function getKabupatenOptionsProperty()
    {
        if (!$this->provinsi) {
            return [];
        }
        
        return $this->getKabupatenByProvinsi($this->provinsi);
    }

    public function render()
    {
        $query = DaftarAlamat::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('provinsi', 'like', '%' . $this->search . '%')
                  ->orWhere('kabupaten_kota', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_dinas', 'like', '%' . $this->search . '%')
                  ->orWhere('alamat', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->provinsiFilter) {
            $query->where('provinsi', $this->provinsiFilter);
        }
        if ($this->kabupatenKotaFilter) {
            $query->where('kabupaten_kota', $this->kabupatenKotaFilter);
        }

        if ($this->wilayahFilter) {
            $query->where(function ($q) {
                $q->where('provinsi', 'like', '%' . $this->wilayahFilter . '%')
                  ->orWhere('kabupaten_kota', 'like', '%' . $this->wilayahFilter . '%');
            });
        }

        $alamats = $query->orderBy($this->sortBy, $this->sortDirection)
                        ->paginate($this->perPage);

        $statusOptions = DaftarAlamat::getStatusOptions();

        $provinsiOptions = DaftarAlamat::distinct('provinsi')->orderBy('provinsi')->pluck('provinsi')->filter()->unique()->sort()->values()->toArray();
        $kabupatenKotaOptions = DaftarAlamat::distinct('kabupaten_kota')->orderBy('kabupaten_kota')->pluck('kabupaten_kota')->filter()->unique()->sort()->values()->toArray();

        return view('livewire.admin.daftar-alamat.data-daftar-alamat', compact(
            'alamats', 'statusOptions', 'provinsiOptions', 'kabupatenKotaOptions'
        ));
    }
}
