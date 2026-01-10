# Switch: Jurnal Sains dan Teknologi Informasi

**Volume. 3 Nomor. 1 Tahun 2025**  
e-ISSN : 3032-3320, dan p-ISSN : 3032-3339, Hal. 84-100  
DOI: https://doi.org/10.62951/switch.v3i1.330  
Available online at: https://journal.aptii.or.id/index.php/Switch

Received November 05, 2024; Revised November 20, 2024; Accepted Desember 10, 2024;  
Online Available Desember 11, 2024

---

# Penggunaan User Acceptance Testing (UAT) Pada Pengujian Sistem Informasi Pengelolaan Keuangan Dan Inventaris Barang

**Aliyah<sup>1*</sup>, Nahrun Hartono<sup>2</sup>, Asrul Azhari Muin<sup>3</sup>**

<sup>1,2,3</sup> Universitas Islam Negeri Alauddin Makassar, Indonesia  
Alamat : Jl. H.M. Yasin Limpo No. 36 Samata, Gowa, Sulawesi Selatan  
E-mail : 60900120034@uinalauddin.ac.id <sup>1</sup>, nahrunhartono@gmail.com <sup>2</sup>, asrul.muin@uin-alauddin.ac.id <sup>3</sup>

## Abstract

Software testing is a crucial stage in the development of information systems to ensure that applications can function according to user needs. System feasibility testing using UAT is intended to ensure that the system meets the functional requirements specified by the users. This study aims to apply UAT to a financial and inventory management information system used to manage various operational needs of the company, such as inventory recording, expense and income reporting, and employee wages. The UAT process is conducted by the system's end-users, consisting of 16 respondents, including employees and the owner of CV. Nitah Tirta Makassar. The testing methodology includes several stages: UAT planning, preparing test questions through a Google Forms (gforms) questionnaire, conducting the tests, and analyzing the test result calculations. The results of this study indicate that the financial and inventory management information system meets user needs, based on UAT questionnaire score calculations that achieved an average interpretation score above 87% with a "very good" rating. With the implementation of UAT, it can be concluded that the financial and inventory management information system meets the users' requirements.

**Keywords** : User Acceptance Testing (UAT) , Finance, Inventory

## Abstrak

Pengujian perangkat lunak merupakan tahap penting dalam pengembangan sistem informasi untuk memastikan bahwa aplikasi dapat berjalan sesuai dengan kebutuhan pengguna. Pengujian kelayakan sistem menggunakan UAT fungsinya untuk memastikan bahwa sistem telah memenuhi persyaratan fungsional yang ditetapkan oleh pengguna. Penelitian ini bertujuan untuk menerapkan UAT pada sistem informasi pengelolaan keuangan dan inventaris barang yang digunakan untuk mengelola berbagai kebutuhan operasional perusahaan seperti pencatatan persediaan barang, pelaporan pengeluaran & pendapatan, hingga upah karyawan. Proses pengujian UAT dilakukan oleh pengguna akhir sistem terdiri dari 16 responden, termasuk karyawan dan owner CV. Nitah Tirta Makassar. Metodologi pengujian melalui beberapa tahapan, yaitu perencanaan UAT, penyusunan pertanyaan pengujian menggunakan kuesioner Google Forms (gforms), pelaksanaan pengujian, serta analisis perhitungan hasil uji. Hasil dari penelitian ini menunjukkan bahwa sistem informasi pengelolaan keuangan dan inventaris barang telah memenuhi kebutuhan pengguna berdasarkan hasil pengujian perhitungan skor kuesioner UAT yang memperoleh hasil uji dengan rata-rata skor interpretasi diatas 87% dengan kriteria sangat baik. Dengan implementasi UAT, sistem informasi pengelolaan keuangan dan inventaris barang dapat disimpulkan telah memenuhi kebutuhan pengguna.

**Kata Kunci** : Pengujian Penerimaan Pengguna, Keuangan, Invetaris

---

## 1. LATAR BELAKANG

Menurut (Ridwan et al., 2021) dalam bukunya menjelaskan bahwa perubahan yang cepat dalam bidang teknologi dan pemrosesan informasi telah mengubah cara suatu organisasi/perusahaan dikelola, baik sekarang maupun masa yang akan datang. Sebagai akibatnya, sudah seharusnya pelaku usaha mampu mengambil tindakan terhadap perubahan tersebut. Tantangan yang paling penting adalah perlunya suatu perusahaan memiliki seorang yang ahli di bidang teknologi informasi. Seiring dengan perkembangan perusahaan yang semakin pesat, kebutuhan akan sistem informasi khususnya pengelolaan keuangan dan inventaris barang yang efektif dan efisien menjadi semakin penting. Sebuah sistem informasi yang efektif dapat memberikan organisasi dengan perencanaan yang lebih baik, pengambilan keputusan, dan hasil yang di inginkan (Wardhana, 2021).

Teknologi informasi memainkan peran penting yaitu salah satunya dalam hal pengelolaan data suatu perusahaan dengan menyediakan tools dan sistem untuk mengumpulkan, menyimpan, mengelola dan menganalisis data" (Sarwani et al., 2021). Sistem informasi merupakan prosedur pengumpulan data dimana data diolah menjadi sebuah informasi dan di distribusikan kepada penggunanya (Nugroho et al., 2021).

Kualitas sistem informasi memperlihatkan bahwa jika pengguna sistem informasi merasa bahwa menggunakan sistem tersebut mudah, pengguna tidak memerlukan effort banyak untuk menggunakannya, sehingga mereka akan lebih banyak waktu untuk mengerjakan hal lain yang kemungkinan akan meningkatkan kinerja mereka secara keseluruhan (Amiruddin, 2024). Oleh karena itu, diperlukan suatu metode pengujian kelayakan sistem untuk memastikan sistem mampu berjalan sesuai dengan ekspektasi pengguna.

Pengujian kelayakan sistem atau biasa disebut dengan Testing Alpha digunakan untuk mengetahui respon pengguna terhadap sistem yang dibangun. Pengujian ini merupakan proses yang dilakukan untuk menguji kualitas dan fungsionalitas perangkat lunak sebelum diluncurkan atau digunakan secara luas (Kerthyayana et al., 2023). Tujuan dari pengujian alpha adalah untuk menguji proyek (sistem) oleh sekelompok pengguna akhir untuk dapat memberi tahu pengembang jika ada bug yang tersisa setelah mereka menemukan dan melaporakan kesalahan apapun dalam aplikasi.

User Acceptance Testing (UAT) merupakan salah satu metode pengujian alpha yang dilakukan oleh end-user dimana user tersebut adalah staff/karyawan perusahaan yang langsung berinteraksi dengan sistem dan dilakukan verifikasi apakah fungsi yang ada telah berjalan sesuai dengan kebutuhan/fungsinya (Ariandi et al., 2021). Setelah dilakukan sistem testing, acceptance testing menyatakan bahwa sistem telah memenuhi persyaratan. Meskipun pengguna terlibat aktif dalam pengujian ini, pengembang yang relevan terus memberikan bantuan penuh. Setelah itu, kriteria persetujuan akan dibuat oleh tim pengujian dan pengguna (Mei, 2020).

Dalam operasional sehari-hari, CV. Nitah Tirta Makassar menghadapi beberapa tantangan yang signifikan dalam hal pengelolaan keuangan dan inventaris barang, berdasarkan sistem lama tersebut, terkadang masih sering terjadi kesalahan dalam hal perhitungan, kesulitan dalam pencatatan dan pembuatan laporan, belum lagi sulitnya dalam pencarian data yang diperlukan karena penumpukan berkas yang banyak. Selain itu, pembukuan manual seperti spreadsheet memiliki keterbatasan skala dan jumlah data yang dapat diolah sehingga menumpuknya file-file yang bisa saja hilang tanpa disadari. Oleh karena permasalahan tersebut, maka penelitian ini bertujuan untuk merancang dan mengembangkan sistem informasi pengelolaan keuangan dan inventaris barang sesuai dengan kebutuhan CV. Nitah Tirta Makassar.

Sistem informasi pengelolaan keuangan dan inventaris barang dirancang untuk membantu dalam mengelola data terkait keuangan dan persediaan barang. Sistem ini meliputi berbagai fungsi untuk memudahkan pencatatan, pemantauan, dan pelaporan aktifitas keuangan serta manajemen inventaris secara efisien dan akurat. Jika sistem ini tidak mampu menangani data keuangan dan inventaris dalam mengelola data dengan baik, hal ini dapat mengganggu alur kas perusahaan, manajemen stok, hingga produktivitas karyawan. keberhasilan sistem informasi ini bergantung pada aspek fungsionalitas, kinerja, tampilan, efisiensi, serta keamanan dan keandalan sistem. Pengujian UAT memiliki peran penting karena dengan ini dapat memeriksa kinerja serta fitur sistem dan memvalidasi kualitasnya (Iwan et al., 2023).

Penelitian ini dilakukan pada CV. Nitah Tirta Makassar dengan tujuan untuk menguji sistem pengelolaan keuangan dan inventaris barang menggunakan UAT. Sebanyak 17 responden sebagai pengguna akhir sistem, yang terdiri dari 14 user sebagai karyawan, 1 admin, dan 1 owner. Pengujian ini difokuskan pada lima variabel utama dalam mengevaluasi sistem diantaranya : (1). Fungsionalitas sistem, (2). Kinerja sistem, (3). Pengalaman & tampilan antarmuka sistem, 4). Efisiensi & produktivitas, dan (5). Keamanan & keandalan sistem (Fadilah et al., 2019). Dengan menguji beberapa aspek tersebut, hasil penelitian ini diharapkan bisa menjadi acuan bagi perusahaan lain yang ingin menerapkan pengujian sistem informasi yang serupa.

---

## 2. KAJIAN TEORITIS

### Sistem Informasi Keuangan (SIK)

Sistem Informasi Keuangan (SIK) adalah suatu sistem yang digunakan dalam suatu organisasi atau perusahaan untuk mengumpulkan, mengelola, menyimpan, menganalisis, dan menyajikan informasi keuangan. SIK termasuk dalam komponen penting dalam pengelolaan keuangan modern yang membantu organisasi mengambil keputusan yang lebih baik, meningkatkan efisiensi operasional, dan memenuhi kebutuhan pelaporan keuangan.

### Sistem Inventaris

Menurut Surya Ade Saputra, dkk., (2022) pada penelitiannya mendefinisikan bahwa "Sistem inventaris adalah pengelolaan data barang inventaris yang terdapat dalam suatu gudang. Sistem inventaris telah menjadi satu kegiatan penting bagi perusahaan untuk melaporkan jumlah data barang yang tersimpan dalam gudang".

### User Acceptance Testing (UAT)

Menurut I Gede Iwan Sudipa, dkk. (2023), User Acceptance Testing (UAT) merupakan salah satu tahap penting dalam proses pengujian perangkat lunak. UAT dilakukan untuk memastikan bahwa perangkat lunak yang dikembangkan memenuhi kebutuhan dan harapan pengguna yang sebenarnya sebelum diluncurkan secara resmi. Pengujian UAT difokuskan pada lima variabel utama dalam mengevaluasi sistem, diantaranya : (1). Fungsionalitas sistem, (2). Kinerja sistem, (3). Pengalaman & tampilan antarmuka sistem, 4). Efisiensi & produktivitas, dan (5). Keamanan & keandalan sistem.

---

## 3. METODE PENELITIAN

Penelitian ini akan digambarkan melalui diagram alur penelitian yang dirancang untuk pengujian sistem informasi pengelolaan keuangan dan inventaris barang pada CV. Nitah Tirta Makassar. Berikut penjelasan tentang diagram alur penelitian yang akan dilakukan :

![Gambar 1. Diagram Penelitian](image)

### Identifikasi Masalah

Penelitian ini dimulai dengan mengidentifikasi masalah yang menjadi landasan bagi pengujian sistem informasi. Adapun fokus penelitian ini adalah menggunakan User Acceptance Testing (UAT) pada pengujian sistem informasi pengelolaan keuangan dan inventaris barang yang bertujuan untuk menguji kelayakan/keberhasilan implementasi sistem apakah telah sesuai dengan kebutuhan pengguna.

### Analisis Kebutuhan

Analisis kebutuhan setelah identifikasi masalah dalam pengujian sistem informasi dengan menggunakan metode UAT mencakup beberapa aspek seperti fitur, performa sistem, dan fungsi utama yang ada dalam sistem. Aspek-aspek inilah yang akan diuji sebagaimana 4 variabel pengujian form kuesioner UAT.

### Studi Literatur

Pada tahapan ini dilakukan dengan meninjau berbagai referensi pustaka seperti jurnal, buku digital serta materi literasi lainnya untuk mengumpulkan informasi dan memperkuat tentang implementasi pengujian UAT pada suatu sistem informasi.

### Pengumpulan Data

Pengumpulan data ini bertujuan untuk memastikan bahwa sistem diuji secara menyeluruh dan masukan/feedback yang diperoleh dipergunakan untuk melakukan perbaikan maupun validasi sistem sebelum implementasi final.

#### 1. Wawancara

Proses wawancara dilakukan dengan pengguna untuk mengumpulkan data secara kualitatif yang lebih mendalam terkait kepuasan, kesan, dan saran perbaikan sistem.

#### 2. Kuesioner

Pengumpulan data dilakukan menggunakan kuesioner google forms yang disusun dengan menggunakan skala likert 1-5 untuk memperoleh data mengenai persepsi pengguna tentang fungsi dan kinerja sistem dengan mengacu pada 4 aspek evaluasi tersebut. Kuesioner akan diberikan kepada responden yang terlibat langsung dalam penggunaan sistem informasi keuangan dan inventaris barang ini.

### Analisis Data

Teknik analisis data yang digunakan adalah analisis deskriptif, di mana data dari kuesioner akan dihitung dan dirata-rata untuk setiap variabel pengujian. Hasil analisis ini kemudian disajikan dalam bentuk tabel untuk memudahkan dalam penarikan kesimpulan.

### Implementasi

Selanjutnya adalah tahap implementasi, dimana sistem dirancang menggunakan bahasa pemrograman Hypertext Preprocessor (PHP), peneliti menerjemahkan desain sistem yang telah disusun menjadi kode program sehingga menghasilkan sistem secara keseluruhan. Sistem informasi yang telah dirancang kemudian di uji menggunakan metode UAT untuk mengetahui penerimaan pengguna terhadap sistem informasi pengelolaan keuangan dan inventaris barang.

### Pengujian User Acceptance Testing (UAT)

User Acceptance Testing (UAT) atau uji penerimaan pengguna adalah proses pengujian oleh pengguna yang dimaksudkan untuk menghasilkan dokumen yang dijadikan bukti bahwa software yang dikembangkan telah dapat diterima oleh pengguna (Khusna et al., 2021).

Adapun responden dalam penelitian ini adalah karyawan dan owner CV. Nitah Tirta Makassar sebagai pengguna sistem informasi. Pemilihan responden berdasarkan kriteria responden yang telah menggunakan sistem, sebanyak 16 responden yang akan dilibatkan dalam penelitian untuk mendapatkan hasil maksimal, yaitu 14 user karyawan, 1 admin & 1 owner. Pengumpulan data melalui kuesioner ini bertujuan untuk menilai sejauh mana suatu sistem telah diterima dan sesuai dengan kebutuhan pengguna. Hal ini dilakukan untuk menentukan apakah sistem tersebut sudah memenuhi standar yang diharapkan atau masih memerlukan perbaikan agar layak digunakan. Terdapat 3 tahapan dalam pengujian UAT ini, yaitu:

1. Menyusun rencana pengujian UAT yang meliputi waktu pelaksanaan, tujuan pengujian, prosedur pengisian, serta aspek yang akan diuji. Kuesioner terdiri dari 25 pertanyaan, diantaranya: (1). Evaluasi fungsionalitas sistem terdiri dari 6 pertanyaan, (2). Kinerja sistem 4 pertanyaan, (3). Pengalaman & tampilan antarmuka sistem 9 pertanyaan, dan 4). Efisiensi & produktivitas 6 pertanyaan.
2. Tahapan kedua, yaitu pengguna akhir menjalankan sistem sesuai prosedur standar yang diikuti, kemudian mengisi kuesioner setelah uji coba sistem.
3. Tahap akhir, yaitu menghitung skor dari hasil kuesioner untuk tiap variabel dan menginterpretasikan skor akhir dalam bentuk persentase (%), sehingga dapat diketahui hasil pengujian.

---

## 3. HASIL DAN PEMBAHASAN

Pada bab ini akan dijelaskan hasil dan pembahasan dari penelitian yang telah dilakukan, yaitu pembahasan mengenai hasil implementari sistem dann pengujian kelayakan sistem informasi menggunakan metode UAT.

### Implementasi Sistem

Tahap implementasi inilah sistem yang telah dirancang dan dikembangkan diimplementasikan kedalam lingkungan perusahaan CV. Nitah Tirta Makassar. Tujuan dari tahap ini adalah untuk memastikan bahwa sistem informasi dapat berjalan sesuai dengan yang direncanakan dan memenuhi kebutuhan pengguna.

#### 1. Halaman Login

Tampilan menu login. User perlu memasukkan username dan password yang telah ditentukan untuk bisa mengakses ke dalam dashboard sistem.

![Gambar 2. Halaman Login](image)

#### 2. Halaman Master Data Barang

Halaman ini menampilkan master data barang, admin bisa menginput data-data barang yang dikehendaki, yang terdiri dari : kode barang, jenis barang, nama barang, dan harga satuan barang.

![Gambar 3. Halaman Master Data Barang](image)

#### 3. Halaman Master Data Vendor

Halaman ini menampilkan master data barang, admin bisa menginput data-data barang yang dikehendaki, yang terdiri dari : kode barang, jenis barang, nama barang, dan harga satuan barang.

![Gambar 4. Halaman Master Data Vendor](image)

#### 4. Halaman Master Data Hotel

Pada halaman ini menampilkan master data hotel/klien CV. Nitah Tirta Makassar yang berisi informasi : nama hotel, kontak, kota, dan alamat hotel.

![Gambar 5. Halaman Master Data Hotel](image)

#### 5. Halaman Menu Upah Karyawan

Pada halaman upah karyawan menampilkan data-data karyawan beserta jumlah upah yang diterima berdasarkan barang dan jumlah barang yang dikerjakan. Halaman ini berisi informasi : nama karyawan, tanggal diselesaikan, kode item, nama item, quantitas (qty), tarif per item, dan total upah.

![Gambar 6. Halaman Menu Upah Karyawan](image)

#### 6. Halaman Menu Laporan Penjualan

Pada halaman ini, terdapat laporan penjualan barang bulanan yang berisi informasi jenis barang, nama barang, volume, qty, harga pokok, harga jual, keuntungan per-barang, dan jumlah penjualan.

![Gambar 7. Halaman Menu Laporan Penjualan](image)

#### 7. Halaman Menu Persediaan Barang

Pada halaman ini terdapat daftar persediaan barang gudang yang terdiri dari kode barang, nama barang, satuan, harga per barang, total harga, stok awal barang, stok masuk, stok keluar, total masuk dan total keluar.

![Gambar 8. Halaman Menu Persediaan Barang](image)

#### 8. Halaman Menu Neraca Saldo

Pada halaman menu neraca saldo hanya boleh di akses oleh user owner dengan menginput tanggal pelaporan, total debit, total kredit dan nama akun.

![Gambar 9. Halaman Menu Neraca Saldo](image)

#### 9. Halaman Menu Pengeluaran

Halaman menu pengeluaran yang fungsinya untuk memantau arus kas pengeluaran CV. Nitah Tirta dalam 1 bulan dengan menginput tanggal, uraian pengeluaran, kategori dan jumlah biaya.

![Gambar 10. Halaman Menu Pengeluaran](image)

#### 10. Halaman Karyawan

Pada halaman karyawan ini, admin menginput nama karyawan, nomor whatsapp, username, dan password untuk kemudian karyawan gunakan untuk login ke dalam sistem.

![Gambar 11. Halaman Karyawan](image)

### Pengujian Kelayakan Sistem User Acceptance Testing (UAT)

Untuk Pengujian kelayakan sistem, dilakukan pengujian lanjutan dengan menggunakan metode UAT yang merupakan proses pengujian secara langsung oleh pengguna agar dapat memperoleh bukti hasil pengujian dan menunjukkan bahwa sistem telah berjalan sesuai dengan kebutuhan. Pengujian UAT yang melibatkan 16 resposden tersebut akan menjawab pertanyaan kuesioner pada table 2 dengan memberikan bobot skala likert 1-5.

**Tabel 1. Bobot Penilaian Skala Likert**

| Bobot | Keterangan |
|-------|------------|
| 1 | Tidak Setuju (TS) |
| 2 | Kurang Setuju (KS) |
| 3 | Cukup Setuju (CS) |
| 4 | Setuju (S) |
| 5 | Sangat Setuju (SS) |

Pada tabel dibawah mempresentasikan daftar pertanyaan-pertanyaan evaluasi kuesioner yang terdiri dari 5 variabel pengujian, yaitu : (1). Fungsionalitas sistem, (2). Kinerja sistem, (3). Pengalaman & tampilan antarmuka sistem, dan 4). Efisiensi & produktivitas. Pada penelitian ini pengujian dalam aspek keamanan dan keandalan sistem tidak dilakukan karena penelitian ini tidak menyediakan layanan bantuan melalui Help Desk ataupun Migration Support. Daftar pertanyaan kuesioner akan diuraikan pada halaman selanjutnya.

**Tabel 2. Daftar Pertanyaan Kuesioner**

| No. | Variabel | Pertanyaan (P) |
|-----|----------|----------------|
| 1 | Evaluasi fungsionalitas sistem | Saya dapat menambahkan dan memperbarui data tanpa masalah. | A1 |
| 2 | | Apakah laporan yang dihasilkan oleh sistem ini akurat dan sesuai dengan kebutuhan pengguna. | A2 |
| 3 | | Sistem ini dapat menghasilkan informasi yang lengkap dan dapat diandalkan. | A3 |
| 4 | | Saya dapat mencari dan menemukan data tertentu dalam sistem ini dengan mudah dan sesuai kebutuhan. | A4 |
| 5 | | Fitur pencarian atau filter pada sistem ini berfungsi dengan baik dan sangat membantu. | A5 |
| 6 | | Sistem ini dapat memproses masukan dengan benar. | A6 |
| 7 | Evaluasi kinerja sistem | Sistem ini selalu tersedia ketika saya membutuhkannya. | B1 |
| 8 | | Kecepatan respon sistem ini sangat baik.. | B2 |
| 9 | | Data yang saya masukkan ke dalam sistem selalu tersimpan dengan benar. | B3 |
| 10 | | Sistem ini menyediakan fitur yang cukup untuk memenuhi kebutuhan pekerjaan. | B4 |
| 11 | Evaluasi pengalaman & tampilan antarmuka sistem | Sistem ini tidak mengalami gangguan saat digunakan. | C1 |
| 12 | | Informasi yang disajikan dalam antarmuka sistem ini mudah dibaca dan dimengerti. | C2 |
| 13 | | Tampilan sistem ini telah memiliki komposisi warna yang sesuai. | C3 |
| 14 | | Informasi yang disajikan sesuai dengan apa yang diharapkan pengguna. | C4 |
| 15 | | Antarmuka pengguna (tampilan) dari sistem ini mudah dipahami. | C5 |
| 16 | | Saya merasa nyaman menggunakan sistem ini dalam aktivitas sehari-hari. | C6 |
| 17 | | Sistem ini membantu meningkatkan efisiensi kinerja saya. | C7 |
| 18 | | Sistem ini mudah di kendalikan (Navigasi) dan sangat responsif (klik, input data, dll.). | C8 |
| 19 | | Tata letak menu dan button pada sistem sudah sesuai. | C9 |
| 20 | Evaluasi Efisiensi & Produktivitas | Apakah sistem ini membantu mengurangi waktu yang dibutuhkan untuk menyelesaikan tugas. | D1 |
| 21 | | Saya merasa puas dengan fitur otomatisasi yang disediakan oleh sistem ini. | D2 |
| 22 | | Sistem ini membantu dalam mengurangi beban kerja manual. | D3 |
| 23 | | Penggunaan sistem ini mengurangi kesalahan atau yang sering terjadi dalam proses kerja. | D4 |
| 24 | | Sistem ini mempermudah dalam meningkatkan kolaborasi antar rekan kerja tim. | D5 |
| 25 | | Dengan sistem ini, proses pengambilan keputusan menjadi lebih cepat dan akurat. | D6 |

#### 1. Perhitungan UAT

Data yang telah diperoleh dari hasil penyebaran kuesioner dipilah berdasarkan jawaban setiap pertanyaan pengelompokkan variable, kemudian menjumlahkan skor tersebut kedalam bentuk persentase (%).

Dari data kuesioner yang didapat tersebut, kemudian dianalisis dengan menghitung rata-rata jawaban berdasarkan skor yang diperoleh dari setiap jawaban responden. Perhitungan bobot pada tabel V.13-V.16 dihitung dengan cara : jumlah jawaban dikalikan dengan bobot penilaian pada tabel V.1. Hasil perhitungan setiap jawaban bobot pertanyaan akan dipaparkan pada halaman selanjutnya.

**a) Variabel 1 :Evaluasi fungsionalitas sistem**

**Tabel 3. Evaluasi Fungsionalitas Sistem**

| P | SS x (5) | S x (4) | CS x (3) | KS x (2) | TS x (1) | Jumlah |
|---|----------|---------|----------|----------|----------|--------|
| A1 | 7 x 5 = 35 | 7 x 4 = 28 | 1 x 3 = 3 | 1 x 2 = 2 | 0 x 1 = 0 | 68 |
| A2 | 8 x 5 = 40 | 8 x 4 = 32 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 72 |
| A3 | 10 x 5 = 50 | 5 x 4 =20 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 73 |
| A4 | 9 x 5 = 45 | 6 x 4 = 24 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 72 |
| A5 | 8 x 5 = 40 | 8 x 4 = 32 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 72 |
| A6 | 8 x 5 = 40 | 7 x 4 = 28 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 71 |

**b) Variabel 2 : Evaluasi kinerja sistem**

**Tabel 4. Evaluasi Kinerja Sistem**

| P | SS x (5) | Sx(4) | CSx(3) | KSx(2) | TSx(1) | Jumlah |
|---|----------|-------|--------|--------|--------|--------|
| B1 | 7 x 5 = 35 | 8 x 4 = 32 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 70 |
| B2 | 7 x 5 = 35 | 9 x 4 = 36 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 71 |
| B3 | 6 x 5 = 30 | 10 x 4 = 40 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 70 |
| B4 | 6 x 5 =30 | 10 x 4 = 40 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 70 |

**c) Variabel 3 : Evaluasi pengalaman & tampilan antarmuka sistem**

**Tabel 5. Evaluasi Pengalaman & Tampilan Antarmuka Sistem**

| P | SSx(5) | Sx(4) | CSx(3) | KSx(2) | TSx(1) | Jumlah |
|---|--------|-------|--------|--------|--------|--------|
| C1 | 6 x 5 = 30 | 8 x 4 = 32 | 2 x 3 = 6 | 0 x 2 = 0 | 0 x 1 = 0 | 68 |
| C2 | 8 x 5 = 40 | 8 x 4 = 32 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 72 |
| C3 | 5 x 5 = 25 | 11 x 4 = 44 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 69 |
| C4 | 4 x 5 = 20 | 11 x 4 = 44 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 67 |
| C5 | 4 x 5 = 20 | 12 x 4 = 48 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 68 |
| C6 | 4 x 5 = 20 | 12 x 4 = 48 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 68 |
| C7 | 5 x 5 = 25 | 11 x 4 = 44 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 69 |
| C8 | 6 x 5 = 30 | 10 x 4 = 40 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 70 |
| C9 | 6 x 5 = 30 | 10 x 4 = 40 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 70 |

**d) Variabel 4 :Evaluasi Efisiensi & Produktivitas**

**Tabel 6. Evaluasi Efisiensi & Produktivitas**

| P | SSx(5) | Sx(4) | CSx(3) | KSx(2) | TSx(1) | Jumlah |
|---|--------|-------|--------|--------|--------|--------|
| D1 | 11 x 5 = 55 | 5 x 4 = 20 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 75 |
| D2 | 10 x 5 = 50 | 4 x 4 = 16 | 2 x 3 = 6 | 0 x 2 = 0 | 0 x 1 = 0 | 72 |
| D3 | 8 x 5 = 40 | 7 x 4 = 28 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 71 |
| D4 | 7 x 5 = 35 | 9 x 4 = 36 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 71 |
| D5 | 7 x 5 = 35 | 8 x 4 = 32 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 70 |
| D6 | 8 x 5 = 40 | 7 x 4 = 28 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 71 |

#### 2. Interpretasi Skor

Setelah mendapatkan hasil UAT yang sudah dikalikan dengan bobot penilaian, selanjutnya hasil akhir jumlah digunakan untuk menghitung nilai presentase. Berikut kriteria interpretasi skor :

**Tabel 7. Interpretasi Skor**

| Presentase | Keterangan |
|------------|------------|
| 0% - 20 % | Sangat kurang baik |
| 21% - 40% | Kurang baik |
| 41% - 60% | Cukup baik |
| 61% - 80% | Baik |
| 81% - 100% | Sangat baik |

Kemudian, hasil akhir jumlah pada tabel V.12 hingga V.16 diatas, dijadikan bahan acuan untuk mencari nilai rata-rata dan persentase untuk mengukur kelayakan sistem dengan rumus sebagai berikut:

$$mean = \frac{bobot\ penilaian}{total\ responden}$$

$$persentase = \frac{Nilai\ Mean}{bobot\ Maksimum} \times 100\%$$

**a) Evaluasi fungsionalitas system**

**Tabel 8. Fungsionalitas sistem**

| P | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|---|------------|----------------|---------------------|
| A1 | 68/16 = 4,25 | 4,25/5x100% = 85% | = 89% |
| A2 | 72/16 = 4,50 | 4,50/5x100% = 90% | |
| A3 | 73/16 = 4,56 | 4,56/5x100% = 91% | |
| A4 | 72/16 = 4,50 | 4,50/5x100% = 90% | |
| A5 | 72/16 = 4,50 | 4,50/5x100% = 90% | |
| A6 | 71/16 = 4,44 | 4,43/5x100% = 89% | |

Dari hasil evaluasi diatas, dapat dilihat bahwa nilai rata-rata evaluasi fungsionalitas sistem yaitu 89%, semua komponen dan fitur sistem berfungsi sesuai dengan spesifikasi dan kebutuhan pengguna.

**b) Evaluasi Kinerja Sistem**

**Tabel 9. Kinerja Sistem**

| P | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|---|------------|----------------|---------------------|
| B1 | 70/16 = 4,38 | 4,38/5x100% = 88% | = 88% |
| B2 | 71/16 = 4,44 | 4,44/5x100% = 89% | |
| B3 | 70/16 = 4,38 | 4,38/5x100% = 88% | |
| B4 | 70/16 = 4,38 | 4,38/5x100% =88% | |

Dari hasil tabel V. 18 diatas, dapat dilihat bahwa hasil nilai rata-rata evaluasi kinerja sistem yaitu 88%. Jadi dapat disimpulkan bahwa, kinerja sistem bekerja efektif dalam memenuhi kebutuhan performa seperti kecepatan, responsivitas, dan stabilitas di berbagai kondisi penggunaan.

**c) Evaluasi Pengalaman dan Tampilan Antarmuka Sistem**

**Tabel 3. 10. pengalaman dan tampilan antarmuka sistem**

| P | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|---|------------|----------------|---------------------|
| C1 | 68/16 = 4,25 | 4,25/5x100% =84% | = 86% |
| C2 | 72/16 = 4,50 | 4,50/5x100% =85% | |
| C3 | 69/16 = 4,31 | 4,31/5x100% =90% | |
| C4 | 67/16 = 4,19 | 4,19/5x100% =86% | |
| C5 | 68/16 = 4,25 | 4,25/5x100% =84% | |
| C6 | 68/16 = 4,25 | 4,25/5x100% =85% | |
| C7 | 68/16 = 4,25 | 4,25/5x100% =85% | |
| C8 | 69/16 = 4,31 | 4,31/5x100% =86% | |
| C9 | 70/16 = 4,38 | 4,38/5x100% =88% | |

Dari tabel diatas dapat dilihat bahwa evaluasi pengalaman dan tampilan antarmuka sistem adalah 86%. Jadi dapat disimpulkan bahwa, tampilan sistem ini sudah sesuai dengan kebutuhan pengguna.

**d) Evaluasi Efisiensi dan Produktifitas Sistem**

**Tabel 11. Efisiensi dan Produktivitas Sistem**

| P | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|---|------------|----------------|---------------------|
| D1 | 75/16 = 4,69 | 4,69/5x100% = 94% | = 90% |
| D2 | 72/16 = 4,50 | 4,50/5x100% = 90% | |
| D3 | 71/16 = 4,44 | 4,44/5x100% = 89% | |
| D4 | 71/16 = 4,44 | 4,44/5x100% = 89% | |
| D5 | 70/16 = 4,38 | 4,38/5x100% = 88% | |
| D6 | 71/16 = 4,44 | 4,44/5x100% = 89% | |

Tabel diatas menunjukkan bahwa hasil rata-rata untuk evaluasi efisiensi dan produktivitas sistem sebesar 90%. Jadi dapat disimpulkan bahwa, sistem ini memiliki kemampuan dalam memaksimalkan dan meningkatkan hasil kerja sesuai dengan kebutuhan pengguna.

Berdasarkan hasil perhitungan evaluasi kuesioner UAT yang telah dilakukan diatas, kemudian hasil tersebut dirangkum pada tabel dibawah ini :

**Tabel 3. 12. Hasil Akhir Perhitungan UAT**

| No. | Variabel | Nilai bobot % | Keterangan |
|-----|----------|---------------|------------|
| 1 | Fungsionalitas sistem | 89% | Sangat baik |
| 2 | Kinerja Sistem | 88% | Sangat baik |
| 3 | Pengalaman antarmuka sistem | 86% | Sangat baik |
| 4 | Efisiensi dan produktivitas sistem | 90% | Sangat baik |

Dari tabel V.22 nilai hasil rata-rata tiap variabel evaluasi diatas, dapat diperoleh kesimpulan bahwa hasil perhitungan mean atau rata-rata persentase kuesioner sistem informasi pengelolaan keuangan dan inventaris barang termasuk dalam kategori sangat baik.

---

## KESIMPULAN

Berdasarkan hasil analisis dan pembahasan, maka dapat diambil kesimpulan bahwa penerapan metode UAT pada pengujian kelayakan sistem informasi pengelolaan keuangan dan inventaris barang dapat dinyatakan telah layak untuk digunakan. Hasil pengujian menggunakan metode UAT didapatkan nilai rata-rata sebesar 87% sehingga diperoleh bahwa sistem ini berhasil diimplementasikan tanpa adanya kendala dalam hal fungsionalitas sistem, kinerja sistem, tampilan antarmuka sistem, efisiensi dan produktivitas sistem, serta kemanan dan keandalan sistem

---

## SARAN

Pengujian selanjutnya dapat dikembangkan lebih lanjut dengan menyertakan skenario/pertanyaan pengujian pada kondisi beban tinggi untuk memastikan kinerja sistem tetap stabil dalam situasi operasional yang lebih kompleks.

---

## UCAPAN TERIMA KASIH

Penulis mengucapkan terima kasih yang sebesar-besarnya kepada para responden yang telah meluangkan waktu untuk berpartisipasi dalam pengujian sistem informasi ini. Ucapan terima kasih juga penulis berikan kepada dosen pembimbing yang telah memberikan arahan, dukungan, dan bimbingan yang sangat berharga selama proses penelitian ini. Tak lupa juga penulis mengucapkan apresiasi kepada CV. Nitah Tirta Makassar yang telah memberikan izin dan kesempatan untuk melakukan penelitian. Dukungan dari semua pihak ini telah sangat membantu dalam mencapai hasil penelitian yang optimal, dan penulis berharap bisa memberikan manfaat bagi perkembangan sistem informasi yang lebih baik di masa mendatang.

---

## DAFTAR REFERENSI

Amiruddin, M. S. (2024). _Perancangan dan pengembangan sistem informasi berbasis Scrum_. Deepublish Digital.

Ariandi, N., Rahma, D. S., Dwi, P. H., & Surya, N. R. (2021). Rancang bangun aplikasi inventory berbasis web dengan menggunakan model MVC. Jakarta Global University.

Fadilah, N., Hernawati, & Kuswari. (2019). _Pengembangan sistem pengolahan hasil belajar siswa SMP berbasis kurikulum 2013: Studi kasus SMP Negeri 1 Prambanan_ [Universitas Negeri Yogyakarta]. http://eprints.uny.ac.id/id/eprint/45200

Iwan, S. I. G., Suci, A. M., Pomalingo, S., Ridwan, A. P. D., Anak, A. G. B. A., Ilham, R., I. N. A. A., Irmawati, I., & Yanuarsyah, I. (2023). _Buku ajar rekayasa perangkat lunak_. Sonpedia Publishing Indonesia.

Kerthyayana, M. I. B., Abdillah, R., Lefan, A. D., Zulfa, I., Yuliyanti, S., Widiyasono, N., Bahana Raymond, Sepriano, S., Efitra, E., & Juansa, A. (2023). _Pengembangan aplikasi perangkat lunak_. Sonpedia Publishing Indonesia. https://doi.org/9786238345083

Khusna, A. N., Delasano, K. P., & Saputra, D. C. E. (2021). Penerapan user-based collaborative filtering algorithm. _MATRIK: Jurnal Manajemen, Teknik Informatika dan Rekayasa Komputer_. https://api.semanticscholar.org/CorpusID:244344177

Mei, P. (2020). _Metodologi pengembangan sistem informasi_. LP2M IAIN Salatiga.

Nugroho, A., Sari, R. D., Dwi, H. P., & Surya, N. R. (2021). Rancang bangun aplikasi inventory berbasis web dengan menggunakan model MVC. Jakarta Global University.

Ridwan, M., Widiastiwi, Y., Zaidiah, A., Ho, R., Ika, P., Isnainiyah, N., Ardilla, Y., Kraugusteeliana, E., Krisnanik, R., Yuliana, I., Putu, S., Arta, S., Ningsih, I., Permana, S., Guntoro, A. R., & Putra, T. R. (2021). _Sistem informasi manajemen_. WIDINA Bhakti Persada Bandung. www.penerbitwidina.com

Sarwani, H., Hadi, S., Taswanda, T., & Ferhat, A. (2021). _Sistem informasi manajemen_. Unpam Press.

Wardhana, A. (2021). Sistem informasi dalam bisnis (pp. 133–149).