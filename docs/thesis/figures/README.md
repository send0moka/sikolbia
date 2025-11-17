# Figures - Laporan Tugas Akhir SIKOLBIA

Folder ini berisi 8 gambar dalam format SVG untuk mendukung dokumen laporan tugas akhir.

## Daftar Gambar

### 1. **gambar1_lstm_cell.svg** (7.2 KB)
**Judul:** Struktur Sel LSTM  
**Deskripsi:** Diagram lengkap arsitektur sel LSTM dengan 3 gates (forget, input, output), cell state flow, dan operasi matematika (multiplication, addition). Menunjukkan alur informasi dari hidden state sebelumnya (h_{t-1}) dan input (x_t) hingga menghasilkan hidden state baru (h_t).  
**Digunakan di:** BAB II (Section 2.4 LSTM dan Metode Ensemble)

### 2. **gambar2_rnd_crisp.svg** (9.6 KB)
**Judul:** Kerangka Metodologi RnD Terintegrasi dengan CRISP-DM  
**Deskripsi:** Diagram integrasi 10 tahap Research and Development (kiri) dengan 6 fase CRISP-DM (kanan). Menunjukkan feedback loops dan bagaimana Early Test (Stage 6) mengimplementasikan seluruh metodologi CRISP-DM.  
**Digunakan di:** BAB III (Section 3.2 Metode Penelitian)

### 3. **gambar3_crisp_dm_flow.svg** (8.4 KB)
**Judul:** Diagram Alur CRISP-DM  
**Deskripsi:** Flowchart siklus CRISP-DM dengan 6 fase (Business Understanding, Data Understanding, Data Preparation, Modeling, Evaluation, Deployment) mengelilingi repository data sentral. Panah solid menunjukkan alur utama, panah putus-putus menunjukkan iterasi/feedback.  
**Digunakan di:** BAB III (Section 3.2f - Early Test)

### 4. **gambar4_data_split.svg** (5.7 KB)
**Judul:** Strategi Pembagian Data NBM Indonesia  
**Deskripsi:** Timeline 1993-2024 yang menunjukkan pembagian kronologis dataset menjadi Training (70%, 1993-2015), Validation (15%, 2016-2019), dan Test (15%, 2020-2024). Setiap bagian mencakup karakteristik periode (krisis ekonomi, pandemi COVID-19) dan jumlah data points.  
**Digunakan di:** BAB III (Section 3.2c - Data Preparation)

### 5. **gambar5_cv_expanding.svg** (6.9 KB)
**Judul:** Time Series Cross-Validation Expanding Window  
**Deskripsi:** Ilustrasi 5+ folds dengan expanding window di mana training set bertambah progresif (1993-2005, 1993-2007, dst.) dan validation set berpindah 1 tahun tiap fold. Menunjukkan strategi validasi yang menjaga integritas temporal.  
**Digunakan di:** BAB III (Section 3.2c - Data Preparation)

### 6. **gambar6_arsitektur_microservices.svg** (11 KB)
**Judul:** Arsitektur Microservices SIKOLBIA  
**Deskripsi:** Diagram lengkap sistem dengan 4 layer: Users (Admin, Pemerintah, Akademisi, Pengunjung) → Nginx (reverse proxy, port 8000) → Application Layer (Laravel 11 + FastAPI ML Service) → Data Layer (MySQL, Redis, phpMyAdmin). Menunjukkan separation of concerns dan Docker containerization.  
**Digunakan di:** BAB III (Section 3.2f - Deployment)

### 7. **gambar7_flowchart_sistem.svg** (11 KB)
**Judul:** Flowchart Sistem SIKOLBIA  
**Deskripsi:** Flowchart komprehensif dari START hingga END, mencakup autentikasi, role checking (4 role), branching logic untuk setiap tipe pengguna, request prediksi ke FastAPI ML Service, dan display hasil dengan chart + confidence interval.  
**Digunakan di:** BAB III (Section 3.2f - Deployment)

### 8. **gambar8_erd_nbm.svg** (11 KB)
**Judul:** Entity Relationship Diagram (ERD) Modul Konsumsi Pangan NBM  
**Deskripsi:** ERD database dengan 6 tabel utama (USERS, ROLES, KELOMPOK_KOMODITAS, KOMODITAS, TRANSAKSI_NBM, PREDICTIONS) beserta primary keys, foreign keys, dan relasi Many-to-One / One-to-Many. Menunjukkan struktur 41,316 records NBM dan mekanisme penyimpanan prediksi.  
**Digunakan di:** BAB III (Section 3.2f - Deployment, setelah penjelasan monitoring & logging)

---

## Format & Spesifikasi

- **Format:** SVG (Scalable Vector Graphics)
- **Total Size:** ~84 KB (8 files)
- **Resolusi:** Vector-based (scalable tanpa loss quality)
- **Color Scheme:** 
  - Biru (#1976D2, #2196F3): System components, data flows
  - Hijau (#4CAF50, #388E3C): Training, success states
  - Oranye (#FF9800, #F57C00): Validation, user actions
  - Kuning (#F9A825): ML/CRISP-DM phases
  - Merah (#F44336, #C62828): Errors, endpoints
  - Ungu (#7B1FA2): Academic users, modeling phases

## Cara Menggunakan

### Di Markdown
```markdown
![Alt Text](figures/gambar1_lstm_cell.svg)
```

### Di LaTeX (untuk PDF export)
```latex
\includegraphics[width=\textwidth]{figures/gambar1_lstm_cell.svg}
```

### Di HTML
```html
<img src="figures/gambar1_lstm_cell.svg" alt="Struktur Sel LSTM" width="100%">
```

## Editing Gambar

SVG dapat diedit menggunakan:
- **Inkscape** (free, open-source)
- **Adobe Illustrator** (commercial)
- **Figma** (online, free tier available)
- **VS Code** dengan extension SVG Editor

Atau langsung edit XML di text editor untuk perubahan minor (warna, text, positioning).

---

## Lisensi

Gambar-gambar ini merupakan bagian dari Laporan Tugas Akhir SIKOLBIA oleh **Jehian Athaya Tsani Az Zuhry (H1D022006)**, Universitas Jenderal Soedirman, 2025.

Digunakan untuk keperluan akademik dan dokumentasi proyek SIKOLBIA.
