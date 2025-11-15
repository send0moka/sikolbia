# SPESIFIKASI KEBUTUHAN PENGGUNA (URS)
## SISTEM INFORMASI KETERSEDIAAN PANGAN DAN LAHAN PERTANIAN (SIKOLBIA)

---

**Versi Dokumen**: 1.0  
**Tanggal**: 15 November 2025  
**Status**: Final

**Disusun oleh:**  
Jehian Athaya Az Dzikri  
Program Studi Informatika  
Universitas Jenderal Soedirman

---

## LEMBAR PENGESAHAN

**Dipersiapkan Oleh:**  
Nama: Jehian Athaya Az Dzikri  
Mahasiswa Informatika  
Tanda Tangan: _______________

**Diperiksa Oleh:**  
Nama: [Nama Dosen Pembimbing]  
Dosen Pembimbing  
Tanda Tangan: _______________

**Disetujui Oleh:**  
Nama: [Nama Ketua Program Studi]  
Ketua Program Studi Informatika  
Tanda Tangan: _______________

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Deskripsi Rinci Kebutuhan Pengguna](#2-deskripsi-rinci-kebutuhan-pengguna)
3. [Lampiran: Hasil Wawancara](#3-lampiran-hasil-wawancara)

---

# 1. PENDAHULUAN

## 1.1 Tujuan Dokumen

Dokumen URS (User Requirements Specification) ini bertujuan untuk mendokumentasikan kebutuhan pengguna dari Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian (SIKOLBIA). Dokumen ini menggambarkan segala sesuatu yang pengguna butuhkan dari sistem, termasuk fungsi, fitur, dan antarmuka yang diharapkan.

## 1.2 Ruang Lingkup

SIKOLBIA adalah sistem informasi berbasis web yang mengintegrasikan machine learning (LSTM) untuk prediksi Neraca Bahan Makanan (NBM) dan menyediakan manajemen data pertanian yang komprehensif. Sistem ini melayani empat kategori pengguna utama: Superadmin, Admin, Pemerintah, dan Akademisi.

## 1.3 Target Pengguna

- **Superadmin**: Pengelola sistem tertinggi dengan akses penuh
- **Admin**: Pengelola modul dan data dengan akses manajemen CRUD
- **Pemerintah**: Pengguna instansi pemerintah daerah yang mengakses data regional
- **Akademisi**: Peneliti dan akademisi yang mengakses data untuk keperluan riset

## 1.4 Definisi dan Singkatan

| Istilah | Definisi |
|---------|----------|
| **URS** | User Requirements Specification - Spesifikasi kebutuhan pengguna |
| **SIKOLBIA** | Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian |
| **NBM** | Neraca Bahan Makanan - Data kalori konsumsi pangan |
| **LSTM** | Long Short-Term Memory - Algoritma deep learning untuk time series |
| **MAPE** | Mean Absolute Percentage Error - Metrik akurasi prediksi |
| **CRUD** | Create, Read, Update, Delete - Operasi dasar manajemen data |
| **RBAC** | Role-Based Access Control - Kontrol akses berbasis peran |

## 1.5 Aturan Penomoran

**Format**: URS.SIKOLBIA.XX.YY.ZZ

**Keterangan:**
- **URS** = User Requirement Specification
- **SIKOLBIA** = Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian
- **XX** = Nomor modul (01-06)
- **YY** = Nomor pengguna (01-04)
- **ZZ** = Nomor kebutuhan (01-99)

**Contoh**: URS.SIKOLBIA.01.01.01 = Modul 01, Pengguna 01, Kebutuhan 01

---

# 2. DESKRIPSI RINCI KEBUTUHAN PENGGUNA

## 2.1 Modul Authentication & Registrasi [URS.SIKOLBIA.01]

### 2.1.1 Superadmin [URS.SIKOLBIA.01.01]

Superadmin memiliki akses penuh ke sistem termasuk manajemen user dan approval registrasi.

**Kebutuhan Superadmin:**

- **[URS.SIKOLBIA.01.01.01]** Superadmin menginginkan sistem keamanan data dengan enkripsi password menggunakan bcrypt
  - **Tujuan**: Menjaga kerahasiaan password pengguna
  - **Target**: Password tidak dapat dibaca dalam bentuk plain text

- **[URS.SIKOLBIA.01.01.02]** Superadmin menginginkan dashboard yang menampilkan statistik user (total users, pending registrations, active users)
  - **Tujuan**: Monitoring aktivitas pengguna secara real-time
  - **Target**: Dashboard update otomatis setiap ada perubahan data

- **[URS.SIKOLBIA.01.01.03]** Superadmin menginginkan fitur approval/rejection registrasi user baru dengan reason field
  - **Tujuan**: Kontrol kualitas pengguna yang masuk ke sistem
  - **Target**: Email notification otomatis ke user setelah approval/rejection

- **[URS.SIKOLBIA.01.01.04]** Superadmin menginginkan fitur suspend/unsuspend user dengan alasan suspension
  - **Tujuan**: Mengelola user yang melanggar aturan
  - **Target**: User yang di-suspend tidak dapat login

### 2.1.2 Admin [URS.SIKOLBIA.01.02]

Admin membantu superadmin dalam pengelolaan user dan memiliki akses manajemen modul.

**Kebutuhan Admin:**

- **[URS.SIKOLBIA.01.02.01]** Admin menginginkan halaman manajemen user dengan filter berdasarkan role (superadmin, admin, pemerintah, akademisi)
  - **Tujuan**: Memudahkan pencarian dan pengelolaan user
  - **Target**: Filter real-time tanpa reload halaman

- **[URS.SIKOLBIA.01.02.02]** Admin menginginkan fitur edit profile user (nama, email, nomor telepon)
  - **Tujuan**: Koreksi data user yang salah input
  - **Target**: Perubahan langsung tersimpan ke database

- **[URS.SIKOLBIA.01.02.03]** Admin menginginkan log aktivitas user (login, CRUD operations, exports)
  - **Tujuan**: Audit trail untuk keamanan sistem
  - **Target**: Log tersimpan minimal 30 hari

### 2.1.3 Pemerintah [URS.SIKOLBIA.01.03]

Pengguna dari instansi pemerintah daerah yang perlu registrasi terlebih dahulu.

**Kebutuhan Pemerintah:**

- **[URS.SIKOLBIA.01.03.01]** Pemerintah menginginkan form registrasi yang mencakup: nama, email, password, jenis dinas, nomor telepon, upload KTP/NPWP
  - **Tujuan**: Verifikasi identitas pengguna pemerintah
  - **Target**: Registrasi mudah dengan validasi otomatis

- **[URS.SIKOLBIA.01.03.02]** Pemerintah menginginkan notifikasi email setelah registrasi (pending, approved, rejected)
  - **Tujuan**: Informasi status registrasi secara real-time
  - **Target**: Email diterima dalam 5 menit setelah perubahan status

- **[URS.SIKOLBIA.01.03.03]** Pemerintah menginginkan fitur resubmit registrasi jika ditolak
  - **Tujuan**: Kesempatan kedua untuk registrasi dengan data yang benar
  - **Target**: Token resubmit valid 7 hari

- **[URS.SIKOLBIA.01.03.04]** Pemerintah menginginkan halaman dashboard yang menampilkan data regional sesuai wilayah user
  - **Tujuan**: Fokus pada data wilayah kerja
  - **Target**: Filter otomatis berdasarkan wilayah user

### 2.1.4 Akademisi [URS.SIKOLBIA.01.04]

Peneliti dan akademisi yang memerlukan akses data untuk keperluan riset.

**Kebutuhan Akademisi:**

- **[URS.SIKOLBIA.01.04.01]** Akademisi menginginkan form registrasi dengan field: nama, email, password, instansi, bidang riset, upload ID Card
  - **Tujuan**: Verifikasi identitas akademisi
  - **Target**: Proses registrasi tidak lebih dari 5 menit

- **[URS.SIKOLBIA.01.04.02]** Akademisi menginginkan akses ke seluruh data tanpa batasan wilayah
  - **Tujuan**: Mendukung riset lintas regional
  - **Target**: Query data tidak terbatas wilayah

- **[URS.SIKOLBIA.01.04.03]** Akademisi menginginkan fitur download dataset dalam format CSV/Excel
  - **Tujuan**: Analisis data offline menggunakan tools statistik
  - **Target**: Download maksimal 10,000 records per request

---

## 2.2 Modul Prediksi NBM dengan LSTM [URS.SIKOLBIA.02]

Modul ini adalah **core feature** SIKOLBIA yang menggunakan machine learning (LSTM) untuk memprediksi kalori konsumsi pangan.

### 2.2.1 Superadmin [URS.SIKOLBIA.02.01]

**Kebutuhan Superadmin:**

- **[URS.SIKOLBIA.02.01.01]** Superadmin menginginkan dashboard monitoring ML model (MAPE, accuracy, last trained date)
  - **Tujuan**: Monitoring performa model secara berkala
  - **Target**: MAPE < 10% (target penelitian)

- **[URS.SIKOLBIA.02.01.02]** Superadmin menginginkan log semua prediksi yang dilakukan user (siapa, kapan, kelompok, komoditi)
  - **Tujuan**: Analisis pola penggunaan sistem
  - **Target**: Log tersimpan permanen di database

### 2.2.2 Admin [URS.SIKOLBIA.02.02]

**Kebutuhan Admin:**

- **[URS.SIKOLBIA.02.02.01]** Admin menginginkan statistik penggunaan prediksi (jumlah prediksi per hari, per kelompok, per komoditi)
  - **Tujuan**: Mengetahui data yang paling sering diprediksi
  - **Target**: Dashboard update real-time

- **[URS.SIKOLBIA.02.02.02]** Admin menginginkan fitur export history prediksi semua user dalam format Excel
  - **Tujuan**: Laporan bulanan untuk stakeholder
  - **Target**: Export maksimal 1 bulan data

### 2.2.3 Pemerintah [URS.SIKOLBIA.02.03]

**Kebutuhan Pemerintah:**

- **[URS.SIKOLBIA.02.03.01]** Pemerintah menginginkan form prediksi dengan 3 input: Kelompok Makanan, Komoditi, Jumlah Bulan Prediksi (1-6)
  - **Tujuan**: Prediksi sederhana dengan minimal input
  - **Target**: Form intuitif dengan dropdown dependency (Komoditi depends on Kelompok)

- **[URS.SIKOLBIA.02.03.02]** Pemerintah menginginkan hasil prediksi ditampilkan dalam 3 chart interaktif:
  1. **Line Chart**: Trend prediksi kalori
  2. **Area Chart**: Confidence interval (upper & lower bounds)
  3. **Bar Chart**: Perbandingan historical vs predicted
  - **Tujuan**: Visualisasi yang mudah dipahami
  - **Target**: Chart responsive dan dapat di-zoom

- **[URS.SIKOLBIA.02.03.03]** Pemerintah menginginkan 7 AI Insights Cards:
  1. **Trend Analysis**: Increasing/Decreasing/Stable dengan persentase
  2. **Risk Assessment**: Low/Medium/High dengan color coding (hijau/kuning/merah)
  3. **Volatility Analysis**: Coefficient of Variation (%)
  4. **Anomaly Detection**: Identifikasi outlier dengan threshold
  5. **Recommendations**: Saran kebijakan berbasis AI
  6. **Summary**: Ringkasan 1-2 kalimat kondisi prediksi
  7. **Confidence Score**: Persentase confidence model
  - **Tujuan**: Decision support berbasis AI untuk policy making
  - **Target**: Insight mudah dipahami oleh non-teknis

- **[URS.SIKOLBIA.02.03.04]** Pemerintah menginginkan fitur save prediction history dengan opsi bookmark
  - **Tujuan**: Menyimpan prediksi penting untuk referensi
  - **Target**: History tersimpan permanen hingga user delete

- **[URS.SIKOLBIA.02.03.05]** Pemerintah menginginkan filter & search di halaman history (tanggal, kelompok, komoditi, bookmark)
  - **Tujuan**: Cepat menemukan prediksi yang pernah dilakukan
  - **Target**: Search real-time tanpa reload halaman

- **[URS.SIKOLBIA.02.03.06]** Pemerintah menginginkan export prediksi ke Excel dengan 2 sheets:
  - Sheet 1: Prediction Results (bulan, prediksi, lower bound, upper bound)
  - Sheet 2: Historical Data (6 bulan terakhir)
  - **Tujuan**: Laporan untuk atasan atau rapat koordinasi
  - **Target**: File Excel ter-format rapi dengan header

- **[URS.SIKOLBIA.02.03.07]** Pemerintah menginginkan export prediksi ke PDF dengan format:
  - Header (logo instansi, judul, tanggal)
  - Tabel prediksi
  - 3 Charts (embedded as images)
  - AI Insights summary
  - Footer (generated by SIKOLBIA)
  - **Tujuan**: Dokumen formal untuk arsip
  - **Target**: PDF A4 landscape, readable

- **[URS.SIKOLBIA.02.03.08]** Pemerintah menginginkan response time prediksi < 2 detik
  - **Tujuan**: User experience yang baik
  - **Target**: 95% request selesai dalam 2 detik

### 2.2.4 Akademisi [URS.SIKOLBIA.02.04]

**Kebutuhan Akademisi:**

- **[URS.SIKOLBIA.02.04.01]** Akademisi menginginkan akses ke raw prediction data (JSON format) untuk analisis mendalam
  - **Tujuan**: Research purposes dengan tools statistik sendiri
  - **Target**: JSON response include confidence intervals & model metadata

- **[URS.SIKOLBIA.02.04.02]** Akademisi menginginkan batch prediction (multiple kelompok-komoditi sekaligus)
  - **Tujuan**: Analisis komparatif antar komoditi
  - **Target**: Batch maksimal 10 prediksi per request

- **[URS.SIKOLBIA.02.04.03]** Akademisi menginginkan dokumentasi model (arsitektur LSTM, hyperparameters, training dataset)
  - **Tujuan**: Transparansi metodologi untuk replikasi riset
  - **Target**: Dokumentasi lengkap di halaman "About Model"

---

## 2.3 Modul Manajemen Data NBM [URS.SIKOLBIA.03]

### 2.3.1 Superadmin [URS.SIKOLBIA.03.01]

**Kebutuhan Superadmin:**

- **[URS.SIKOLBIA.03.01.01]** Superadmin menginginkan CRUD Kelompok Makanan (kode, nama, deskripsi, icon, color)
  - **Tujuan**: Manajemen master kelompok (13 kelompok)
  - **Target**: Real-time update, validasi kode unique

- **[URS.SIKOLBIA.03.01.02]** Superadmin menginginkan CRUD Komoditi (kode_komoditi, kode_kelompok, nama, satuan, susut_percentage)
  - **Tujuan**: Manajemen master komoditi (162 komoditi)
  - **Target**: Dropdown kelompok dynamic, validasi kombinasi kode unique

- **[URS.SIKOLBIA.03.01.03]** Superadmin menginginkan CRUD Transaksi NBM dengan field lengkap (tahun, bulan, kelompok, komoditi, kalori_hari, status_angka, dll.)
  - **Tujuan**: Manajemen data historis NBM (41,316 records)
  - **Target**: Pagination 100 rows/page, filter tahun & kelompok

### 2.3.2 Admin [URS.SIKOLBIA.03.02]

**Kebutuhan Admin:**

- **[URS.SIKOLBIA.03.02.01]** Admin menginginkan import CSV untuk bulk upload transaksi NBM
  - **Tujuan**: Efisiensi input data besar
  - **Target**: Import 1000 rows dalam < 10 detik

- **[URS.SIKOLBIA.03.02.02]** Admin menginginkan template CSV download dengan format standar
  - **Tujuan**: Panduan format import yang benar
  - **Target**: Template include header & sample data

- **[URS.SIKOLBIA.03.02.03]** Admin menginginkan validasi data saat import (tahun valid, bulan 1-12, kalori positif)
  - **Tujuan**: Data quality control
  - **Target**: Error report jika ada data invalid

### 2.3.3 Pemerintah [URS.SIKOLBIA.03.03]

**Kebutuhan Pemerintah:**

- **[URS.SIKOLBIA.03.03.01]** Pemerintah menginginkan view data NBM dalam tabel sortable & searchable
  - **Tujuan**: Eksplorasi data historis
  - **Target**: Search by kelompok/komoditi, sort by tahun DESC

- **[URS.SIKOLBIA.03.03.02]** Pemerintah menginginkan filter data NBM (tahun range, kelompok, komoditi)
  - **Tujuan**: Analisis data spesifik
  - **Target**: Filter multi-select, apply tanpa reload

### 2.3.4 Akademisi [URS.SIKOLBIA.03.04]

**Kebutuhan Akademisi:**

- **[URS.SIKOLBIA.03.04.01]** Akademisi menginginkan export full dataset NBM (CSV/Excel)
  - **Tujuan**: Analisis statistik dengan software eksternal
  - **Target**: Export maksimal 50,000 records per request

---

## 2.4 Modul Lahan Pertanian [URS.SIKOLBIA.04]

### 2.4.1 Superadmin [URS.SIKOLBIA.04.01]

**Kebutuhan Superadmin:**

- **[URS.SIKOLBIA.04.01.01]** Superadmin menginginkan CRUD Master Lahan (Topik, Variabel, Klasifikasi, Data)
  - **Tujuan**: Manajemen struktur data lahan
  - **Target**: Relasi topik → variabel → klasifikasi terjaga

### 2.4.2 Admin [URS.SIKOLBIA.04.02]

**Kebutuhan Admin:**

- **[URS.SIKOLBIA.04.02.01]** Admin menginginkan import CSV data lahan dengan validation
  - **Tujuan**: Bulk upload data lahan
  - **Target**: Validation wilayah & variabel exists

### 2.4.3 Pemerintah [URS.SIKOLBIA.04.03]

**Kebutuhan Pemerintah:**

- **[URS.SIKOLBIA.04.03.01]** Pemerintah menginginkan filter data lahan: Tahun, Bulan, Wilayah (Provinsi → Kab/Kota), Topik, Variabel, Klasifikasi
  - **Tujuan**: Data regional sesuai wilayah kerja
  - **Target**: Hierarchical dropdown (Provinsi → Kab/Kota), default wilayah user

- **[URS.SIKOLBIA.04.03.02]** Pemerintah menginginkan tabel data lahan responsive dengan sort & pagination
  - **Tujuan**: Eksplorasi data lahan
  - **Target**: 50 rows/page, sort ascending/descending

- **[URS.SIKOLBIA.04.03.03]** Pemerintah menginginkan export data lahan filtered ke Excel
  - **Tujuan**: Laporan data lahan regional
  - **Target**: Excel dengan header & filter info

### 2.4.4 Akademisi [URS.SIKOLBIA.04.04]

**Kebutuhan Akademisi:**

- **[URS.SIKOLBIA.04.04.01]** Akademisi menginginkan akses data lahan seluruh Indonesia (tidak terbatas wilayah)
  - **Tujuan**: Riset komparatif antar wilayah
  - **Target**: Filter wilayah multi-select

---

## 2.5 Modul Iklim & Optimalisasi DPI [URS.SIKOLBIA.05]

### 2.5.1 Superadmin [URS.SIKOLBIA.05.01]

**Kebutuhan Superadmin:**

- **[URS.SIKOLBIA.05.01.01]** Superadmin menginginkan CRUD Master Iklim (14 Topik: Curah Hujan, Suhu, Kelembaban, dll.)
  - **Tujuan**: Manajemen data iklim
  - **Target**: Topik pre-defined, CRUD variabel & data

### 2.5.2 Admin [URS.SIKOLBIA.05.02]

**Kebutuhan Admin:**

- **[URS.SIKOLBIA.05.02.01]** Admin menginginkan dashboard monitoring data iklim (latest data, missing data)
  - **Tujuan**: Data quality monitoring
  - **Target**: Highlight wilayah dengan data missing

### 2.5.3 Pemerintah [URS.SIKOLBIA.05.03]

**Kebutuhan Pemerintah:**

- **[URS.SIKOLBIA.05.03.01]** Pemerintah menginginkan filter data iklim: Tahun, Wilayah, Topik, Variabel, Klasifikasi
  - **Tujuan**: Monitoring iklim regional
  - **Target**: Visualisasi data iklim dalam chart (line/bar)

- **[URS.SIKOLBIA.05.03.02]** Pemerintah menginginkan export data iklim ke Excel
  - **Tujuan**: Laporan iklim regional
  - **Target**: Excel dengan metadata (wilayah, tahun, topik)

### 2.5.4 Akademisi [URS.SIKOLBIA.05.04]

**Kebutuhan Akademisi:**

- **[URS.SIKOLBIA.05.04.01]** Akademisi menginginkan time series data iklim untuk analisis trend
  - **Tujuan**: Riset perubahan iklim
  - **Target**: Query multi-year (5-10 tahun)

---

## 2.6 Modul Benih & Pupuk [URS.SIKOLBIA.06]

### 2.6.1 Superadmin [URS.SIKOLBIA.06.01]

**Kebutuhan Superadmin:**

- **[URS.SIKOLBIA.06.01.01]** Superadmin menginginkan CRUD Master Benih & Pupuk (Topik, Variabel, Klasifikasi, Data)
  - **Tujuan**: Manajemen data distribusi benih & pupuk
  - **Target**: Relasi topik → variabel → klasifikasi

### 2.6.2 Admin [URS.SIKOLBIA.06.02]

**Kebutuhan Admin:**

- **[URS.SIKOLBIA.06.02.01]** Admin menginginkan import CSV benih pupuk dengan template
  - **Tujuan**: Bulk upload data distribusi
  - **Target**: Template include field: wilayah, variabel, klasifikasi, nilai, satuan

### 2.6.3 Pemerintah [URS.SIKOLBIA.06.03]

**Kebutuhan Pemerintah:**

- **[URS.SIKOLBIA.06.03.01]** Pemerintah menginginkan filter data benih pupuk: Tahun, Bulan, Wilayah, Topik, Variabel, Klasifikasi
  - **Tujuan**: Monitoring distribusi benih pupuk regional
  - **Target**: Data real-time, update bulanan

- **[URS.SIKOLBIA.06.03.02]** Pemerintah menginginkan export data benih pupuk ke Excel
  - **Tujuan**: Laporan distribusi untuk evaluasi program
  - **Target**: Excel dengan summary (total distribusi per komoditi)

### 2.6.4 Akademisi [URS.SIKOLBIA.06.04]

**Kebutuhan Akademisi:**

- **[URS.SIKOLBIA.06.04.01]** Akademisi menginginkan data distribusi benih pupuk historis (multi-year)
  - **Tujuan**: Riset efektivitas program subsidi
  - **Target**: Korelasi distribusi vs produktivitas

---

## 2.7 Modul Daftar Alamat [URS.SIKOLBIA.07]

### 2.7.1 Superadmin [URS.SIKOLBIA.07.01]

**Kebutuhan Superadmin:**

- **[URS.SIKOLBIA.07.01.01]** Superadmin menginginkan CRUD Daftar Alamat Instansi (nama, alamat, telepon, email, lat/long, wilayah)
  - **Tujuan**: Direktori instansi pertanian
  - **Target**: Map integration dengan Leaflet.js

### 2.7.2 Admin [URS.SIKOLBIA.07.02]

**Kebutuhan Admin:**

- **[URS.SIKOLBIA.07.02.01]** Admin menginginkan import alamat dari CSV dengan geocoding otomatis
  - **Tujuan**: Bulk upload alamat instansi
  - **Target**: Geocoding API untuk lat/long otomatis

### 2.7.3 Pemerintah [URS.SIKOLBIA.07.03]

**Kebutuhan Pemerintah:**

- **[URS.SIKOLBIA.07.03.01]** Pemerintah menginginkan view daftar alamat per wilayah dengan map visualization
  - **Tujuan**: Direktori instansi pertanian regional
  - **Target**: Map dengan marker, click show detail

### 2.7.4 Public (Guest) [URS.SIKOLBIA.07.04]

**Kebutuhan Public:**

- **[URS.SIKOLBIA.07.04.01]** Public menginginkan akses view daftar alamat tanpa login
  - **Tujuan**: Transparansi informasi publik
  - **Target**: Read-only, no auth required

- **[URS.SIKOLBIA.07.04.02]** Public menginginkan search alamat by provinsi/kabupaten
  - **Tujuan**: Cari instansi terdekat
  - **Target**: Search real-time

---

# 3. LAMPIRAN: HASIL WAWANCARA

## 3.1 Wawancara dengan Superadmin/Admin

**Narasumber**: Pengelola Sistem SIKOLBIA  
**Tanggal**: 10 November 2025  
**Lokasi**: Dinas Pertanian Provinsi

| No | Pertanyaan | Jawaban | Tujuan | Target |
|----|------------|---------|--------|--------|
| 1 | Apa tantangan terbesar dalam manajemen data pertanian saat ini? | Data terpisah-pisah di berbagai sistem, tidak ada integrasi | Identifikasi pain points | Sistem terintegrasi |
| 2 | Fitur apa yang paling penting untuk prediksi NBM? | Akurasi tinggi (MAPE <10%) dan visualisasi mudah dipahami | Prioritas fitur ML | User-friendly ML prediction |
| 3 | Bagaimana approval registrasi user dilakukan? | Manual review dokumen KTP/NPWP, email notifikasi setelah approval | Workflow registrasi | Approval dalam 1-2 hari kerja |
| 4 | Data apa yang perlu di-export secara berkala? | Transaksi NBM, data lahan per wilayah, distribusi benih pupuk | Kebutuhan reporting | Export otomatis bulanan |
| 5 | Berapa user concurrent yang diharapkan? | Estimasi 50-100 user simultan saat peak hours | Capacity planning | Response time <2s |

---

## 3.2 Wawancara dengan Pemerintah (End User)

**Narasumber**: Kepala Seksi Ketersediaan Pangan  
**Tanggal**: 11 November 2025  
**Lokasi**: Dinas Pertanian Kabupaten

| No | Pertanyaan | Jawaban | Tujuan | Target |
|----|------------|---------|--------|--------|
| 1 | Apa kesulitan dalam prediksi ketersediaan pangan manual? | Tidak ada tools, hanya manual Excel, rawan error | Pain point prediksi | Automated prediction |
| 2 | Berapa bulan ke depan yang biasa diprediksi? | 3-6 bulan untuk perencanaan program | Prediction horizon | Max 6 bulan prediksi |
| 3 | Format laporan seperti apa yang dibutuhkan atasan? | Excel dengan chart, PDF untuk arsip formal | Output format | Excel + PDF export |
| 4 | Data wilayah mana yang perlu diakses? | Hanya data kabupaten sendiri untuk privasi | Data access control | Filter otomatis by wilayah user |
| 5 | Fitur AI Insights yang paling bermanfaat? | Risk Assessment dan Recommendations untuk policy making | Decision support | Actionable insights |

---

## 3.3 Wawancara dengan Akademisi

**Narasumber**: Dosen Pertanian UNSOED  
**Tanggal**: 12 November 2025  
**Lokasi**: Fakultas Pertanian UNSOED

| No | Pertanyaan | Jawaban | Tujuan | Target |
|----|------------|---------|--------|--------|
| 1 | Data apa yang paling sering dibutuhkan untuk riset? | Time series NBM, data iklim, korelasi lahan-produktivitas | Research data needs | Full dataset access |
| 2 | Format data yang ideal untuk analisis? | CSV/Excel untuk import ke R/Python/SPSS | Export format | Raw data export |
| 3 | Apakah perlu dokumentasi model ML? | Ya, untuk replikasi riset dan validasi metodologi | Transparency | Complete ML docs |
| 4 | Berapa sering download dataset? | 2-3 kali per semester untuk mahasiswa skripsi/tesis | Usage frequency | Unlimited download |
| 5 | Fitur batch prediction berguna? | Sangat, untuk analisis komparatif 10-20 komoditi sekaligus | Batch processing | Max 10 batch per request |

---

## 3.4 Observasi Sistem Existing

**Pengamat**: Tim Analisis Sistem  
**Tanggal**: 13-14 November 2025

### Temuan Observasi:

1. **Data Quality Issues**:
   - 5% transaksi NBM memiliki nilai 0 atau NULL
   - Beberapa wilayah data lahan tahun 2022 missing
   - **Solusi URS**: Validasi input mandatory, warning data missing

2. **User Behavior**:
   - 80% user akses dari mobile (responsive design critical)
   - Peak hours: 09:00-11:00 dan 13:00-15:00 WIB
   - **Solusi URS**: Mobile-first design, load balancing

3. **Export Pattern**:
   - Excel export lebih sering (70%) vs PDF (30%)
   - Average export 500-2000 records
   - **Solusi URS**: Optimize Excel export, chunk processing

4. **Prediction Usage**:
   - 60% prediksi untuk Padi-padian (Beras, Jagung)
   - 85% prediksi 3 bulan, 15% prediksi 6 bulan
   - **Solusi URS**: Default 3 bulan, quick select populer komoditi

5. **Support Issues**:
   - 40% ticket: "Lupa password"
   - 30% ticket: "Tidak bisa upload dokumen registrasi"
   - **Solusi URS**: Password reset email, upload validation clear

---

## PENUTUP

Dokumen URS ini telah disusun berdasarkan:
1. **Wawancara mendalam** dengan 4 kategori pengguna
2. **Observasi sistem** existing selama 2 hari
3. **Analisis kebutuhan** berdasarkan sistem yang sudah berjalan
4. **Best practices** software requirements engineering

Dokumen ini akan menjadi dasar untuk:
- Pengembangan Software Requirements Specification (SRS)
- Design Document (ERD, DFD, Use Case)
- Testing & Validation Plan

---

**Catatan Revisi:**

| Versi | Tanggal | Perubahan | Disetujui |
|-------|---------|-----------|-----------|
| 1.0 | 15 Nov 2025 | Initial version | - |

---

*Dokumen URS SIKOLBIA v1.0 - Confidential*
