# USER MANUAL
## SISTEM INFORMASI KETERSEDIAAN PANGAN DAN LAHAN PERTANIAN (SIKOLBIA)

---

**Versi**: 1.0  
**Tanggal**: 15 November 2025

**Disusun oleh:**  
Jehian Athaya Az Dzikri  
Program Studi Informatika  
Universitas Jenderal Soedirman

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Spesifikasi Sistem](#2-spesifikasi-sistem)
3. [Panduan Penggunaan](#3-panduan-penggunaan)
4. [Troubleshooting](#4-troubleshooting)

---

# 1. PENDAHULUAN

## 1.1 Tujuan Dokumen

User Manual ini adalah panduan lengkap penggunaan SIKOLBIA untuk semua kategori pengguna: Superadmin, Admin, Pemerintah, dan Akademisi.

## 1.2 Deskripsi Sistem

SIKOLBIA adalah sistem informasi berbasis web yang mengintegrasikan:
- **Machine Learning (LSTM)** untuk prediksi Neraca Bahan Makanan
- **Manajemen Data Pertanian** (Lahan, Iklim, Benih & Pupuk)
- **Role-Based Access Control** dengan 4 kategori pengguna
- **Export Multi-Format** (Excel multi-sheet, PDF dengan chart)

## 1.3 Kategori Pengguna

| Role | Akses | Fitur Utama |
|------|-------|-------------|
| **Superadmin** | Full access | Approval registrasi, suspend user, manage all modules |
| **Admin** | Manajemen modul | CRUD master data, import CSV, monitoring system |
| **Pemerintah** | Data regional | Prediksi NBM, filter wilayah, export laporan |
| **Akademisi** | Data nasional | Akses dataset lengkap, batch prediction, download CSV |

---

# 2. SPESIFIKASI SISTEM

## 2.1 Perangkat Lunak

| Komponen | Spesifikasi |
|----------|-------------|
| **Backend** | Laravel 12.26.3, PHP 8.3.27 |
| **Frontend** | Livewire 3.6, Tailwind CSS, Flux UI |
| **Database** | MySQL 8.0 (Production), SQLite (Testing) |
| **ML Service** | FastAPI, TensorFlow 2.15, Python 3.10 |
| **Containerization** | Docker 24.x, Docker Compose 2.x |
| **Web Server** | Nginx 1.25 |
| **Cache** | Redis 7.x |
| **Browser** | Chrome 120+, Firefox 120+, Edge 120+ |

## 2.2 Perangkat Keras (Minimum)

**Client Side:**
- Processor: Intel Core i3 / AMD Ryzen 3
- RAM: 4 GB
- Storage: 500 MB free space
- Internet: 2 Mbps (stable)
- Display: 1366 x 768 resolution

**Server Side:**
- Processor: Intel Xeon / AMD EPYC (4 cores)
- RAM: 8 GB (16 GB recommended for ML)
- Storage: 50 GB SSD
- Network: 100 Mbps
- OS: Ubuntu 22.04 LTS / Windows Server 2022

## 2.3 Akses Aplikasi

SIKOLBIA dapat diakses melalui browser pada URL:
- **Production**: https://sikolbia.go.id
- **Development**: http://localhost:8000

---

# 3. PANDUAN PENGGUNAAN

## 3.1 REGISTRASI & LOGIN

### 3.1.1 Registrasi Pengguna Baru

**Untuk Pemerintah:**

1. Buka halaman utama SIKOLBIA
2. Klik tombol **"Daftar"** di pojok kanan atas
3. Pilih **"Pemerintah"**
4. Isi form registrasi:
   - **Nama Lengkap**: Nama sesuai KTP
   - **Email**: Email instansi (.go.id preferred)
   - **Password**: Min 8 karakter (huruf + angka + simbol)
   - **Jenis Dinas**: Pilih dari dropdown (Dinas Pertanian, Ketahanan Pangan, dll)
   - **Nomor Telepon**: Format 08xx-xxxx-xxxx
   - **Upload KTP/NPWP**: File JPG/PNG max 2MB
5. Centang **"Saya menyetujui syarat dan ketentuan"**
6. Klik **"Daftar"**
7. **Email Konfirmasi** akan dikirim ke inbox Anda:
   - Status **"Pending"**: Menunggu approval admin
   - Anda akan menerima email lagi setelah disetujui/ditolak

**Untuk Akademisi:**

Sama seperti Pemerintah, tetapi:
- Pilih **"Akademisi"** sebagai tipe user
- **Instansi**: Nama universitas/lembaga riset
- **Bidang Riset**: Pilih dari dropdown (Agronomi, Pangan, Ekonomi Pertanian, dll)
- **Upload ID Card**: Kartu mahasiswa/dosen

**Catatan:**
- Registrasi yang ditolak dapat **resubmit** dalam 7 hari via link di email
- Superadmin dan Admin dibuat langsung oleh sistem (tidak bisa daftar sendiri)

### 3.1.2 Login

1. Klik **"Masuk"** di halaman utama
2. Masukkan **Email** dan **Password**
3. Klik **"Masuk"**
4. Anda akan diarahkan ke **Dashboard** sesuai role:
   - Superadmin → Dashboard Manajemen User
   - Admin → Dashboard Monitoring Modul
   - Pemerintah/Akademisi → Halaman Prediksi NBM

**Lupa Password?**
1. Klik **"Lupa Password?"** di halaman login
2. Masukkan **Email** terdaftar
3. Cek email untuk **Reset Link** (valid 60 menit)
4. Klik link, masukkan **Password Baru**
5. Login dengan password baru

---

## 3.2 MODUL PREDIKSI NBM (Core Feature)

### 3.2.1 Melakukan Prediksi

**Pengguna**: Pemerintah, Akademisi, Admin

1. Dari dashboard, klik **"Prediksi NBM"** di sidebar
2. Isi form prediksi:
   - **Kelompok Makanan**: Pilih dari dropdown (13 kelompok)
     * Contoh: Padi-padian, Umbi-umbian, Ikan, Daging, dll
   - **Komoditi**: Pilih dari dropdown (otomatis filter by kelompok)
     * Contoh: Jika pilih "Padi-padian" → muncul Beras, Jagung, Gandum
   - **Jumlah Bulan Prediksi**: Slider 1-6 bulan (default: 3)
3. Klik **"Prediksi"**

**Loading State:**
- Muncul spinner "Memproses prediksi..."
- Backend mengambil 6 bulan data historis dari database
- ML API melakukan inference (± 1-2 detik)

**Hasil Prediksi:**

Setelah loading, muncul 3 section:

#### A. Visualisasi Chart (Interaktif)

**Chart 1: Line Chart - Trend Prediksi**
- X-axis: Bulan (Jan 2024, Feb 2024, ...)
- Y-axis: Kalori/hari (satuan)
- Garis Biru: Historical data (6 bulan terakhir)
- Garis Merah: Predicted data (N bulan ke depan)
- Hover untuk lihat nilai detail

**Chart 2: Area Chart - Confidence Interval**
- Area hijau transparan: Upper bound (prediksi tertinggi)
- Area merah transparan: Lower bound (prediksi terendah)
- Garis tengah: Prediksi rata-rata
- Hover untuk lihat range nilai

**Chart 3: Bar Chart - Perbandingan**
- Bar biru: 6 bulan historical
- Bar oranye: N bulan predicted
- Side-by-side comparison
- Klik bar untuk highlight

**Fitur Chart:**
- **Zoom**: Scroll mouse di atas chart
- **Pan**: Klik-drag untuk geser
- **Reset**: Double-click untuk reset zoom
- **Download**: Klik icon download (PNG format)

#### B. AI Insights (7 Cards)

**Card 1: Trend Analysis**
```
🔼 Increasing Trend (+12.5%)
Kalori konsumsi diprediksi naik 12.5% dalam 3 bulan ke depan.
```

**Card 2: Risk Assessment**
```
⚠️ Medium Risk
Fluktuasi moderat, perlu monitoring. Risiko kelangkaan: Sedang.
Color: Yellow/Kuning
```

**Card 3: Volatility**
```
📊 Volatility: 8.3%
Coefficient of Variation (CV) = 8.3%
Status: Stabil (CV < 10%)
```

**Card 4: Anomaly Detection**
```
⚠️ 1 Anomaly Detected
Bulan: Maret 2025 (920.5 kcal)
Outlier: >2 standard deviations dari mean
```

**Card 5: Recommendations**
```
💡 Policy Recommendations:
1. Tingkatkan stok buffer 15%
2. Monitor distribusi regional
3. Siapkan contingency plan
```

**Card 6: Summary**
```
📝 Summary:
Ketersediaan beras diprediksi meningkat stabil. Risiko sedang, perlu monitoring distribusi.
```

**Card 7: Confidence Score**
```
✅ Confidence: 91.3%
Model sangat yakin dengan prediksi ini.
MAPE: 8.7% (Excellent)
```

#### C. Data Table - Detail Prediksi

| Bulan | Prediksi (kcal) | Lower Bound | Upper Bound | Status |
|-------|-----------------|-------------|-------------|--------|
| Apr 2025 | 920.5 | 890.2 | 950.8 | 🔼 +2.3% |
| Mei 2025 | 935.2 | 900.1 | 970.3 | 🔼 +1.6% |
| Jun 2025 | 948.7 | 910.5 | 987.0 | 🔼 +1.4% |

**Kolom:**
- **Bulan**: Format MMM YYYY
- **Prediksi**: Nilai prediksi rata-rata
- **Lower/Upper**: Confidence interval 95%
- **Status**: Persentase perubahan dari bulan sebelumnya

### 3.2.2 Menyimpan Prediksi (Save History)

1. Setelah hasil prediksi muncul, klik **"Simpan Prediksi"**
2. Muncul modal konfirmasi:
   - **Nama Prediksi**: (Otomatis: "Prediksi Beras - 15 Nov 2025")
   - **Catatan** (Opsional): Tambahkan notes untuk referensi
   - **Bookmark**: Centang untuk tandai prediksi penting
3. Klik **"Simpan"**
4. Notifikasi sukses: "Prediksi berhasil disimpan!"

**Manfaat:**
- Bandingkan prediksi lama vs baru
- Track akurasi model dari waktu ke waktu
- Referensi untuk laporan bulanan

### 3.2.3 Melihat History Prediksi

1. Klik **"Riwayat Prediksi"** di menu sidebar
2. Tampilan tabel history:

| Tanggal | Kelompok | Komoditi | Bulan | Actions | Bookmark |
|---------|----------|----------|-------|---------|----------|
| 15 Nov 2025 14:30 | Padi-padian | Beras | 3 | 👁️ 📥 🗑️ | ⭐ |
| 10 Nov 2025 09:15 | Umbi-umbian | Ubi Kayu | 6 | 👁️ 📥 🗑️ | - |

**Filter & Search:**
- **Tanggal**: Date range picker (dari - sampai)
- **Kelompok**: Multi-select dropdown
- **Komoditi**: Multi-select dropdown
- **Bookmark**: Toggle "Tampilkan bookmark saja"
- **Search**: Ketik nama prediksi/catatan

**Actions:**
- **👁️ View**: Lihat detail prediksi lagi (charts, insights, data)
- **📥 Export**: Download Excel/PDF
- **🗑️ Delete**: Hapus prediksi (konfirmasi dulu)

### 3.2.4 Export Prediksi

**Format 1: Excel (Multi-Sheet)**

1. Dari history atau hasil prediksi, klik **"Export Excel"**
2. File download otomatis: `Prediksi_Beras_2025-11-15.xlsx`

**Isi File:**

**Sheet 1: Prediction Results**
| Bulan | Prediksi (kcal) | Lower Bound | Upper Bound | Change (%) |
|-------|-----------------|-------------|-------------|------------|
| Apr 2025 | 920.5 | 890.2 | 950.8 | +2.3% |

**Sheet 2: Historical Data (6 bulan)**
| Bulan | Kalori/hari | Status Angka | Keterangan |
|-------|-------------|--------------|------------|
| Okt 2024 | 895.3 | Angka Tetap | - |

**Sheet 3: AI Insights Summary**
| Kategori | Nilai | Deskripsi |
|----------|-------|-----------|
| Trend | Increasing | +12.5% dalam 3 bulan |
| Risk | Medium | Fluktuasi moderat |
| Volatility | 8.3% | Stabil |

**Format:**
- Header bold + background color
- Kolom auto-width
- Number formatting (1 decimal)

**Format 2: PDF (Formatted Report)**

1. Klik **"Export PDF"**
2. File download: `Laporan_Prediksi_Beras_2025-11-15.pdf`

**Isi File:**

```
┌────────────────────────────────────────────┐
│ [Logo SIKOLBIA]                            │
│                                             │
│   LAPORAN PREDIKSI NERACA BAHAN MAKANAN    │
│   Kelompok: Padi-padian                    │
│   Komoditi: Beras                          │
│   Periode: April - Juni 2025               │
│   Tanggal Prediksi: 15 November 2025       │
└────────────────────────────────────────────┘

1. HASIL PREDIKSI
[Tabel prediksi 3 bulan]

2. VISUALISASI
[3 Charts embedded as images - Line, Area, Bar]

3. ANALISIS AI
[7 Insights cards - Trend, Risk, Volatility, dll]

4. REKOMENDASI KEBIJAKAN
• Tingkatkan stok buffer 15%
• Monitor distribusi regional
• Siapkan contingency plan

5. INFORMASI MODEL
Model: LSTM (Long Short-Term Memory)
MAPE: 8.7%
Confidence: 91.3%
Training Data: 41,316 records (1993-2024)

────────────────────────────────────────────
Generated by SIKOLBIA | sikolbia.go.id
15 November 2025 14:30 WIB
```

**Layout:** A4 Landscape, margin 2cm

---

## 3.3 MANAJEMEN DATA NBM (Admin/Superadmin)

### 3.3.1 Master Kelompok Makanan

**Akses:** Superadmin only

1. Sidebar → **"NBM"** → **"Kelompok"**
2. Tampilan tabel kelompok (13 rows):

| Kode | Nama | Deskripsi | Icon | Color | Actions |
|------|------|-----------|------|-------|---------|
| 01 | Padi-padian | Beras, Jagung, Gandum | 🌾 | blue | ✏️ 🗑️ |
| 02 | Umbi-umbian | Ubi Kayu, Kentang, dll | 🥔 | orange | ✏️ 🗑️ |

**Tambah Kelompok:**
1. Klik **"+ Tambah Kelompok"**
2. Isi form:
   - **Kode**: 2 digit (01-99), unique
   - **Nama**: Nama kelompok
   - **Deskripsi**: Deskripsi singkat
   - **Icon Class**: Font Awesome (fa-wheat, fa-fish, dll)
   - **Color Class**: Tailwind (blue, green, red, dll)
3. Klik **"Simpan"**

**Edit/Delete:** Klik icon ✏️ atau 🗑️

### 3.3.2 Master Komoditi

**Akses:** Superadmin, Admin

1. Sidebar → **"NBM"** → **"Komoditi"**
2. Tampilan tabel komoditi (162 rows, pagination 50/page):

| Kode Kelompok | Kode Komoditi | Nama | Satuan | Susut (%) | Actions |
|---------------|---------------|------|--------|-----------|---------|
| 01 | 0101 | Beras | Kg | 5.0 | ✏️ 🗑️ |
| 01 | 0102 | Jagung | Kg | 8.0 | ✏️ 🗑️ |

**Filter:**
- **Kelompok**: Dropdown filter by kelompok
- **Search**: Cari by nama komoditi

**Tambah Komoditi:**
1. Klik **"+ Tambah Komoditi"**
2. Isi form:
   - **Kode Kelompok**: Dropdown (01-13)
   - **Kode Komoditi**: 4 digit (0101-0199), unique per kelompok
   - **Nama**: Nama komoditi
   - **Satuan**: Kg, Liter, Butir, dll
   - **Susut Percentage**: 0-100 (default: 0)
3. Klik **"Simpan"**

### 3.3.3 Transaksi NBM (Data Historis)

**Akses:** Superadmin, Admin

1. Sidebar → **"NBM"** → **"Transaksi"**
2. Tampilan tabel transaksi (41,316 rows total):

| Tahun | Bulan | Kelompok | Komoditi | Kalori/hari | Status | Actions |
|-------|-------|----------|----------|-------------|--------|---------|
| 2024 | 10 | Padi-padian | Beras | 895.3 | Tetap | ✏️ 🗑️ |

**Filter:**
- **Tahun**: Slider 1993-2024
- **Bulan**: Multi-select 1-12
- **Kelompok**: Dropdown
- **Komoditi**: Dropdown (dynamic by kelompok)

**Tambah Transaksi (Manual):**
1. Klik **"+ Tambah Transaksi"**
2. Isi form 30+ fields (tahun, bulan, kelompok, komoditi, kalori, dll)
3. Klik **"Simpan"**

**Import CSV (Bulk):**
1. Klik **"📥 Import CSV"**
2. Download template: **"Download Template CSV"**
3. Isi data di Excel sesuai format:
   ```
   tahun,bulan,kode_kelompok,kode_komoditi,kalori_hari,status_angka,...
   2024,11,01,0101,900.5,Angka Tetap,...
   ```
4. Upload file CSV (max 5MB, 1000 rows)
5. Klik **"Import"**
6. Validation:
   - ✅ Success: "1000 rows imported"
   - ❌ Error: "Row 15: Tahun invalid (harus 1993-2099)"

**Export Data:**
- Klik **"📤 Export Excel"** untuk download filtered data

---

## 3.4 MODUL LAHAN PERTANIAN

### 3.4.1 Filter Data Lahan

**Pengguna:** Pemerintah, Akademisi, Admin

1. Sidebar → **"Lahan Pertanian"**
2. Isi filter:

**Filter Tahun & Bulan:**
- **Tahun**: Dropdown (2015-2024)
- **Bulan**: Multi-select (Jan-Des)

**Filter Wilayah (Hierarchical):**
- **Provinsi**: Dropdown (34 provinsi)
- **Kabupaten/Kota**: Dropdown (otomatis filter by provinsi)
  * Contoh: Pilih "Jawa Tengah" → muncul Purbalingga, Banyumas, dll

**Filter Data:**
- **Topik**: Dropdown (Luas Panen, Produktivitas, Produksi, dll)
- **Variabel**: Dropdown (dynamic by topik)
  * Contoh: Topik "Luas Panen" → Variabel "Padi, Jagung, Kedelai"
- **Klasifikasi**: Dropdown (Sawah, Ladang, Kebun, dll)

3. Klik **"Filter"**

**Hasil:**
Tabel data lahan:

| Tahun | Bulan | Wilayah | Topik | Variabel | Klasifikasi | Nilai | Satuan |
|-------|-------|---------|-------|----------|-------------|-------|--------|
| 2024 | 10 | Purbalingga | Luas Panen | Padi | Sawah | 12,450 | Ha |

**Pagination:** 50 rows/page
**Sort:** Klik header kolom untuk sort ASC/DESC

### 3.4.2 Export Data Lahan

1. Setelah filter, klik **"📤 Export Excel"**
2. File download: `Data_Lahan_Purbalingga_2024.xlsx`

**Isi File:**

**Sheet 1: Filter Info**
```
Tahun: 2024
Bulan: Oktober
Wilayah: Kabupaten Purbalingga
Topik: Luas Panen
Variabel: Padi
Total Records: 250
```

**Sheet 2: Data**
[Tabel data sesuai filter]

---

## 3.5 MODUL IKLIM & OPTIMALISASI DPI

### 3.5.1 Filter Data Iklim

**Pengguna:** Pemerintah, Akademisi, Admin

1. Sidebar → **"Iklim & DPI"**
2. Isi filter:

**Filter Dasar:**
- **Tahun**: Range slider (2010-2024)
- **Wilayah**: Hierarchical (Provinsi → Kab/Kota)

**Filter Topik (14 Topik Tersedia):**
- Curah Hujan
- Suhu Udara
- Kelembaban
- Kecepatan Angin
- Tekanan Udara
- Penyinaran Matahari
- Penguapan
- Hari Hujan
- Dan 6 topik lainnya

**Filter Variabel & Klasifikasi:**
- Dynamic by topik yang dipilih

3. Klik **"Filter"**

**Visualisasi:**
Selain tabel, muncul **Line Chart** untuk time series:
- X-axis: Bulan (Jan-Des)
- Y-axis: Nilai (mm, °C, %, dll sesuai topik)
- Multiple lines jika multi-variabel

### 3.5.2 Export Data Iklim

1. Klik **"📤 Export Excel"**
2. File: `Data_Iklim_CurahHujan_2024.xlsx`

**Isi:** Sheet filter info + data + chart embedded

---

## 3.6 MODUL BENIH & PUPUK

### 3.6.1 Filter Data Benih Pupuk

**Pengguna:** Pemerintah, Akademisi, Admin

1. Sidebar → **"Benih & Pupuk"**
2. Filter sama seperti Lahan (Tahun, Bulan, Wilayah, Topik, Variabel, Klasifikasi)
3. Klik **"Filter"**

**Topik Tersedia:**
- Distribusi Benih Padi
- Distribusi Benih Jagung
- Distribusi Pupuk Urea
- Distribusi Pupuk NPK
- Subsidi Benih
- Subsidi Pupuk
- Dan lainnya

**Hasil:** Tabel distribusi dengan kolom:
- Tahun, Bulan, Wilayah
- Komoditi (Benih/Pupuk)
- Volume (Kg/Ton)
- Nilai (Rupiah)
- Penerima (Jumlah Petani)

### 3.6.2 Import CSV Benih Pupuk

**Pengguna:** Admin

1. Klik **"📥 Import CSV"**
2. Download template
3. Isi data:
   ```
   tahun,bulan,id_wilayah,id_variabel,id_klasifikasi,nilai,satuan
   2024,10,3301,15,2,12500,Kg
   ```
4. Upload & Import
5. Validation otomatis (wilayah exist, variabel valid, dll)

---

## 3.7 MODUL DAFTAR ALAMAT

### 3.7.1 Lihat Daftar Alamat (Public Access)

**Pengguna:** Semua (termasuk public tanpa login)

1. Sidebar → **"Daftar Alamat"**
2. Tampilan **Map + List**:

**Map (Leaflet.js):**
- Marker tiap instansi pertanian
- Klik marker → popup detail (nama, alamat, telepon)
- Zoom/Pan untuk navigasi

**List (Tabel):**
| Nama Instansi | Alamat | Telepon | Email | Wilayah | Actions |
|---------------|--------|---------|-------|---------|---------|
| Dinas Pertanian Kab. Purbalingga | Jl. Veteran No.1 | (0281) 123456 | dinas@purbalingga.go.id | Purbalingga | 👁️ 📍 |

**Filter:**
- **Provinsi**: Dropdown
- **Kabupaten/Kota**: Dropdown
- **Search**: Cari by nama instansi

**Actions:**
- **👁️ View**: Lihat detail lengkap
- **📍 Show on Map**: Fokus ke marker di map

### 3.7.2 Manajemen Alamat (Admin)

**Akses:** Admin, Superadmin

1. Dari halaman Daftar Alamat, klik **"⚙️ Kelola"**
2. CRUD Interface:

**Tambah Alamat:**
1. Klik **"+ Tambah Alamat"**
2. Isi form:
   - **Nama Instansi**
   - **Alamat Lengkap**
   - **Telepon** (format: (0xxx) xxxxxx)
   - **Email** (format email valid)
   - **Provinsi**: Dropdown
   - **Kabupaten/Kota**: Dropdown
   - **Latitude/Longitude**: Auto-geocoding atau manual input
3. Klik **"Simpan"**

**Import CSV:**
1. Klik **"📥 Import CSV"**
2. Template:
   ```
   nama,alamat,telepon,email,id_provinsi,id_kabkota,latitude,longitude
   Dinas Pertanian XYZ,Jl. ABC No.1,(0281) 123456,dinas@xyz.go.id,33,3301,-7.4321,109.2345
   ```
3. Upload → Geocoding otomatis jika lat/long kosong

---

## 3.8 APPROVAL REGISTRASI (Superadmin)

### 3.8.1 Lihat Pending Registrations

1. Login sebagai **Superadmin**
2. Dashboard → **"Registrasi Pending"** card (badge merah jumlah pending)
3. Klik card atau sidebar → **"Manajemen User"** → **"Pending"** tab

**Tampilan:**

| Nama | Email | Role | Jenis Dinas | Tanggal | Dokumen | Actions |
|------|-------|------|-------------|---------|---------|---------|
| John Doe | john@pertanian.go.id | Pemerintah | Dinas Pertanian | 10 Nov 2025 | 📄 KTP | ✅ ❌ |

### 3.8.2 Review Dokumen

1. Klik **📄 icon** untuk view dokumen KTP/NPWP/ID Card
2. Modal popup dengan image viewer
3. Zoom in/out untuk verifikasi

### 3.8.3 Approve Registrasi

1. Klik **✅ Approve**
2. Modal konfirmasi:
   ```
   Setujui registrasi user: John Doe?
   
   [ ] Kirim email notifikasi approval
   [ ] Aktifkan user langsung (bisa login)
   
   [Batal]  [Setujui]
   ```
3. Klik **"Setujui"**
4. Sistem otomatis:
   - Update status → Approved
   - Assign role (Pemerintah/Akademisi)
   - Kirim email approval
   - User bisa login

### 3.8.4 Reject Registrasi

1. Klik **❌ Reject**
2. Modal form:
   ```
   Alasan Penolakan (wajib):
   [Text area - min 20 karakter]
   
   Contoh:
   - Dokumen KTP tidak jelas
   - Email bukan instansi resmi
   - Data tidak sesuai
   
   [ ] Izinkan resubmit dalam 7 hari
   
   [Batal]  [Tolak Registrasi]
   ```
3. Klik **"Tolak Registrasi"**
4. Sistem:
   - Update status → Rejected
   - Kirim email rejection dengan alasan
   - Generate resubmit token (valid 7 hari)

---

## 3.9 MANAJEMEN USER (Superadmin)

### 3.9.1 Lihat Semua User

1. Sidebar → **"Manajemen User"**
2. Tab:
   - **All Users** (default)
   - **Pending** (registrasi belum disetujui)
   - **Active** (user aktif)
   - **Suspended** (user yang di-suspend)

**Tabel All Users:**

| ID | Nama | Email | Role | Status | Last Login | Actions |
|----|------|-------|------|--------|------------|---------|
| 1 | Admin | admin@sikolbia.id | superadmin | Active | 15 Nov 14:30 | ✏️ 🔒 🗑️ |
| 2 | John | john@pertanian.go.id | pemerintah | Active | 14 Nov 10:15 | ✏️ 🔒 🗑️ |

**Filter:**
- **Role**: Multi-select (superadmin, admin, pemerintah, akademisi)
- **Status**: Multi-select (active, suspended, pending)
- **Search**: By nama/email

### 3.9.2 Edit User

1. Klik **✏️ Edit**
2. Form edit:
   - **Nama**, **Email**, **Nomor Telepon**: Editable
   - **Role**: Dropdown (change role)
   - **Wilayah** (untuk Pemerintah): Update wilayah kerja
   - **Password**: Reset password (optional)
3. Klik **"Update"**

### 3.9.3 Suspend User

1. Klik **🔒 Suspend**
2. Modal form:
   ```
   Suspend user: John Doe?
   
   Alasan Suspension:
   [Text area]
   
   Durasi:
   ( ) 7 hari
   ( ) 30 hari
   ( ) 90 hari
   (*) Permanent (manual unsuspend)
   
   [Batal]  [Suspend]
   ```
3. Klik **"Suspend"**
4. User tidak bisa login, muncul pesan: "Akun Anda di-suspend. Hubungi admin."

**Unsuspend:**
1. Tab **"Suspended"**
2. Klik **🔓 Unsuspend** pada user
3. Konfirmasi → User bisa login lagi

### 3.9.4 Delete User

1. Klik **🗑️ Delete**
2. Konfirmasi:
   ```
   ⚠️ PERINGATAN
   
   Hapus user: John Doe?
   
   Data yang akan dihapus:
   - Account info
   - Prediction history (5 prediksi)
   - Activity logs
   
   Aksi ini TIDAK BISA di-undo!
   
   [Batal]  [Hapus Permanent]
   ```
3. Ketik "DELETE" untuk konfirmasi
4. Klik **"Hapus Permanent"**

---

## 3.10 MONITORING & LOGS (Admin)

### 3.10.1 Dashboard Monitoring

**Akses:** Admin, Superadmin

1. Sidebar → **"Dashboard"**
2. **Cards Statistics:**

```
┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│ 📊 Total Users   │ │ 🔮 Prediksi Hari │ │ 📥 Export Hari   │
│      245         │ │       18         │ │        7         │
│ +12 bulan ini    │ │ +3 vs kemarin    │ │ -2 vs kemarin    │
└──────────────────┘ └──────────────────┘ └──────────────────┘

┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│ ⚠️ Pending Reg   │ │ 🤖 ML API Status │ │ 🗄️ Database Size │
│       5          │ │   ✅ Healthy     │ │    2.3 GB        │
│ Perlu review     │ │ Response: 850ms  │ │ +50 MB/bulan     │
└──────────────────┘ └──────────────────┘ └──────────────────┘
```

3. **Charts:**

**Chart 1: User Growth (Last 6 Months)**
- Line chart jumlah user per bulan
- Breakdown by role (stacked)

**Chart 2: Prediction Usage (Last 30 Days)**
- Bar chart jumlah prediksi per hari
- Peak: weekdays vs weekends

**Chart 3: Most Predicted Commodities (Top 10)**
- Horizontal bar chart
- Beras, Jagung, Daging Ayam, dll

**Chart 4: System Performance (Last 24 Hours)**
- Line chart response time (ms)
- Target: <2000ms (garis merah)

### 3.10.2 Activity Logs

**Akses:** Superadmin

1. Sidebar → **"Activity Logs"**
2. Tabel logs:

| Timestamp | User | Action | Module | Details | IP Address |
|-----------|------|--------|--------|---------|------------|
| 15 Nov 14:30 | John Doe | Prediction | NBM | Prediksi Beras 3 bulan | 192.168.1.100 |
| 15 Nov 14:25 | Jane Smith | Export | Lahan | Export Excel Purbalingga | 192.168.1.101 |

**Filter:**
- **Date Range**: Last 7 days, 30 days, custom
- **User**: Dropdown select user
- **Action**: Login, Prediction, Export, CRUD
- **Module**: NBM, Lahan, Iklim, dll

**Export Logs:**
- Klik **"📤 Export CSV"** untuk audit report

---

# 4. TROUBLESHOOTING

## 4.1 Login & Registrasi

### Problem 1: "Email atau password salah"

**Solusi:**
1. Cek CAPS LOCK keyboard (password case-sensitive)
2. Coba **"Lupa Password?"** untuk reset
3. Pastikan email terdaftar (cek di email konfirmasi registrasi)
4. Jika masih gagal, hubungi admin

### Problem 2: "Akun Anda masih pending approval"

**Solusi:**
1. Tunggu email approval dari admin (max 2 hari kerja)
2. Cek folder **Spam/Junk** email
3. Jika >3 hari, email ke: support@sikolbia.go.id

### Problem 3: Upload dokumen gagal

**Penyebab:**
- File terlalu besar (>2MB)
- Format bukan JPG/PNG
- Nama file mengandung karakter khusus

**Solusi:**
1. Compress image dengan TinyPNG.com
2. Rename file: `KTP_NamaAnda.jpg` (no spaces, no special chars)
3. Coba upload lagi

## 4.2 Prediksi NBM

### Problem 1: "Insufficient historical data"

**Penyebab:** Komoditi pilihan tidak punya 6 bulan data historis

**Solusi:**
1. Pilih komoditi lain (populer: Beras, Jagung, Daging Ayam)
2. Atau hubungi admin untuk tambah data historis

### Problem 2: Prediksi loading lama (>10 detik)

**Penyebab:**
- ML API sedang down/restart
- Koneksi internet lambat
- Server overload

**Solusi:**
1. Refresh halaman (F5)
2. Coba lagi 5 menit kemudian
3. Jika masih gagal, hubungi admin (cek ML API status)

### Problem 3: Chart tidak muncul

**Penyebab:** Browser cache/JavaScript error

**Solusi:**
1. Hard refresh: **Ctrl+Shift+R** (Windows) / **Cmd+Shift+R** (Mac)
2. Clear browser cache:
   - Chrome: Settings → Privacy → Clear browsing data
3. Update browser ke versi terbaru
4. Coba browser lain (Chrome/Firefox/Edge)

## 4.3 Export File

### Problem 1: Download file tidak dimulai

**Solusi:**
1. Cek popup blocker browser (allow popups untuk sikolbia.go.id)
2. Coba klik **"Export"** lagi
3. Gunakan browser lain jika masih gagal

### Problem 2: Excel file corrupt/tidak bisa dibuka

**Solusi:**
1. Re-download file
2. Pastikan Excel/LibreOffice terinstall
3. Coba buka dengan Google Sheets (upload file)

### Problem 3: PDF chart tidak muncul

**Penyebab:** Server rendering timeout

**Solusi:**
1. Export Excel saja, lalu copy chart manual ke Word/PDF
2. Atau screenshot chart dari web, paste ke dokumen

## 4.4 Filter & Search

### Problem 1: Filter tidak menghasilkan data

**Penyebab:** Kombinasi filter terlalu spesifik, data tidak ada

**Solusi:**
1. **Reset Filter** → Coba lagi dengan filter lebih luas
2. Contoh: Ganti tahun 2020 → 2024 (lebih baru)
3. Atau hapus filter Klasifikasi (filter Wilayah + Topik saja)

### Problem 2: Search tidak menemukan data

**Solusi:**
1. Cek typo/ejaan (Beras bukan Breras)
2. Search partial: "Padi" akan match "Padi-padian", "Luas Panen Padi"
3. Case-insensitive: "BERAS" = "beras" = "Beras"

## 4.5 Performance

### Problem 1: Website lambat/loading lama

**Solusi Client-Side:**
1. Cek koneksi internet (min 2 Mbps)
2. Close tabs lain yang makan RAM
3. Restart browser
4. Clear cache & cookies

**Solusi Server-Side (Admin):**
1. Cek server load: `docker stats`
2. Restart services: `docker-compose restart`
3. Cek log errors: `docker-compose logs -f app`

### Problem 2: Mobile responsive issue

**Solusi:**
1. Rotate device (landscape mode untuk tabel lebar)
2. Zoom out/in (pinch gesture)
3. Update browser mobile ke versi terbaru
4. Gunakan desktop untuk fitur kompleks (export, dashboard)

## 4.6 Error Messages

### Error 419: CSRF Token Mismatch

**Solusi:**
1. Refresh halaman (F5)
2. Jika masih error, logout → login lagi
3. Clear browser cache

### Error 500: Internal Server Error

**Solusi:**
1. Coba lagi 5 menit kemudian (server restart)
2. Jika persist, screenshot error → email ke support

### Error 403: Forbidden

**Penyebab:** Akses fitur yang tidak sesuai role Anda

**Solusi:**
1. Pastikan login dengan role yang benar
2. Contoh: Dashboard hanya untuk Admin/Superadmin
3. Hubungi admin jika perlu upgrade role

## 4.7 Kontak Support

**Email:** support@sikolbia.go.id  
**WhatsApp:** +62 812-XXXX-XXXX  
**Jam Operasional:** Senin-Jumat, 08:00-16:00 WIB

**Saat kontak support, sertakan:**
1. **Screenshot error** (full page)
2. **Aksi yang dilakukan** (step-by-step)
3. **Browser & OS** (Chrome 120, Windows 11)
4. **Timestamp** (kapan error terjadi)

---

## LAMPIRAN A: Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| **Ctrl + K** | Focus search box |
| **Ctrl + /** | Toggle sidebar |
| **Ctrl + P** | Print current page |
| **Ctrl + S** | Save/Export (jika di form) |
| **Esc** | Close modal |
| **Alt + P** | Quick predict (dari dashboard) |
| **Alt + H** | Go to History |

---

## LAMPIRAN B: Browser Compatibility

| Browser | Minimum Version | Recommended |
|---------|----------------|-------------|
| Chrome | 100+ | 120+ |
| Firefox | 100+ | 120+ |
| Edge | 100+ | 120+ |
| Safari | 15+ | 17+ |
| Opera | 85+ | 105+ |

**Catatan:** Internet Explorer **tidak didukung**

---

## LAMPIRAN C: Data Format Reference

### CSV Import Format - Transaksi NBM

```csv
tahun,bulan,kode_kelompok,kode_komoditi,kalori_hari,status_angka,produksi_ton,impor_ton,ekspor_ton
2024,11,01,0101,900.5,Angka Tetap,5600000,125000,50000
```

**Field Rules:**
- `tahun`: 1993-2099
- `bulan`: 1-12
- `kode_kelompok`: 01-13 (harus exist di master)
- `kode_komoditi`: 4 digit (harus exist di master)
- `kalori_hari`: Decimal, >0
- `status_angka`: "Angka Tetap" / "Angka Sementara" / "Angka Sangat Sementara"

### API Response Format - Prediction

```json
{
  "success": true,
  "prediction": [920.5, 935.2, 948.7],
  "confidence_interval": {
    "lower": [890.2, 900.1, 910.5],
    "upper": [950.8, 970.3, 987.0]
  },
  "insights": {
    "trend": "increasing",
    "risk": "medium",
    "volatility": 8.3,
    "anomalies": [{"month": 2, "value": 920.5}]
  },
  "model_info": {
    "name": "LSTM NBM Production Model",
    "mape": 8.7,
    "confidence": 91.3,
    "last_trained": "2025-11-01"
  }
}
```

---

**END OF USER MANUAL**

*SIKOLBIA v1.0 - © 2025 Kementerian Pertanian RI*
