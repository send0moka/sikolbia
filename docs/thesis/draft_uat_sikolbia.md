# Draft UAT - Sistem SIKOLBIA

## Pengujian User Acceptance Testing (UAT)

Untuk pengujian kelayakan sistem, dilakukan pengujian lanjutan dengan menggunakan metode User Acceptance Testing (UAT) yang merupakan proses pengujian secara langsung oleh pengguna agar dapat memperoleh bukti hasil pengujian dan menunjukkan bahwa sistem telah berjalan sesuai dengan kebutuhan. Pengujian UAT yang melibatkan 10 responden tersebut akan menjawab pertanyaan kuesioner dengan memberikan bobot skala Likert 1-5.

**Tabel X. Bobot Penilaian Skala Likert**

| Bobot | Keterangan |
|-------|------------|
| 1 | Sangat Tidak Setuju (STS) |
| 2 | Tidak Setuju (TS) |
| 3 | Cukup Setuju (CS) |
| 4 | Setuju (S) |
| 5 | Sangat Setuju (SS) |

### Profil Responden

Penelitian ini melibatkan 10 responden yang terdiri dari:
- 2 user dengan role Admin (Pengelola sistem dari Badan Pangan Nasional)
- 5 user dengan role Pemerintah (Staf/pejabat dari Kementerian Pertanian dan Bappenas)
- 3 user dengan role Akademisi (Peneliti dan mahasiswa pascasarjana bidang ketahanan pangan)

Pemilihan responden berdasarkan kriteria responden yang memiliki pemahaman tentang sistem informasi ketahanan pangan dan telah menggunakan sistem SIKOLBIA untuk menjalankan prediksi konsumsi kalori NBM.

### Daftar Pertanyaan Kuesioner UAT

Pada tabel berikut mempresentasikan daftar pertanyaan-pertanyaan evaluasi kuesioner yang terdiri dari 4 variabel pengujian, yaitu: (1) Fungsionalitas sistem, (2) Kinerja sistem, (3) Pengalaman & tampilan antarmuka sistem, dan (4) Efisiensi & produktivitas. Kuesioner terdiri dari 25 pertanyaan yang akan diuraikan sebagai berikut.

**Tabel X. Daftar Pertanyaan Kuesioner UAT**

| No. | Variabel | Pertanyaan | Kode |
|-----|----------|------------|------|
| 1 | Evaluasi Fungsionalitas Sistem | Saya dapat login ke sistem dengan mudah menggunakan credentials yang diberikan. | A1 |
| 2 | | Sistem dapat menampilkan data historis NBM dengan akurat sesuai parameter yang dipilih. | A2 |
| 3 | | Fitur prediksi konsumsi kalori berfungsi dengan baik dan menghasilkan output yang sesuai. | A3 |
| 4 | | Hasil prediksi yang ditampilkan lengkap dengan confidence interval dan trend indicator. | A4 |
| 5 | | Saya dapat melakukan filter data berdasarkan kelompok, komoditi, dan periode waktu dengan mudah. | A5 |
| 6 | | Fitur export hasil prediksi ke Excel berfungsi dengan baik dan menghasilkan file yang sesuai. | A6 |
| 7 | Evaluasi Kinerja Sistem | Sistem selalu tersedia dan dapat diakses ketika saya membutuhkannya. | B1 |
| 8 | | Waktu loading untuk menampilkan data historis NBM cukup cepat (< 3 detik). | B2 |
| 9 | | Proses prediksi menggunakan model LSTM berjalan lancar tanpa error atau timeout. | B3 |
| 10 | | Sistem dapat menangani multiple requests secara bersamaan tanpa mengalami penurunan performa. | B4 |
| 11 | Evaluasi Pengalaman & Tampilan Antarmuka Sistem | Tampilan dashboard sistem mudah dipahami dan intuitif. | C1 |
| 12 | | Menu navigasi terstruktur dengan baik dan memudahkan akses ke fitur-fitur sistem. | C2 |
| 13 | | Komposisi warna dan layout sistem nyaman untuk dilihat dalam penggunaan jangka panjang. | C3 |
| 14 | | Grafik hasil prediksi (line chart dengan confidence interval) mudah dibaca dan informatif. | C4 |
| 15 | | Tabel hasil prediksi menyajikan informasi yang jelas dan terstruktur. | C5 |
| 16 | | Sistem memberikan feedback yang jelas ketika ada input yang tidak valid atau error. | C6 |
| 17 | | Sistem responsif dan dapat diakses dengan baik melalui berbagai ukuran layar (desktop/tablet). | C7 |
| 18 | | Tombol dan elemen interaktif mudah diklik dan berfungsi sesuai ekspektasi. | C8 |
| 19 | | Sistem tidak mengalami gangguan atau crash saat digunakan. | C9 |
| 20 | Evaluasi Efisiensi & Produktivitas | Sistem membantu mempercepat proses analisis konsumsi pangan dibanding metode manual. | D1 |
| 21 | | Fitur prediksi LSTM memberikan insight yang berguna untuk perencanaan ketahanan pangan. | D2 |
| 22 | | Sistem mengurangi kesalahan perhitungan konsumsi kalori yang sering terjadi pada metode manual. | D3 |
| 23 | | Export hasil prediksi ke Excel memudahkan dalam pembuatan laporan untuk stakeholder. | D4 |
| 24 | | Dengan sistem ini, proses pengambilan keputusan terkait kebijakan pangan menjadi lebih cepat. | D5 |
| 25 | | Secara keseluruhan, sistem SIKOLBIA memenuhi kebutuhan dalam prediksi konsumsi pangan nasional. | D6 |

### Perhitungan UAT

Data yang telah diperoleh dari hasil penyebaran kuesioner dipilah berdasarkan jawaban setiap pertanyaan pengelompokkan variabel, kemudian menjumlahkan skor tersebut ke dalam bentuk persentase (%). Perhitungan bobot dihitung dengan cara: jumlah jawaban dikalikan dengan bobot penilaian pada Tabel bobot Likert. Hasil perhitungan setiap jawaban bobot pertanyaan akan dipaparkan sebagai berikut.

#### a) Variabel 1: Evaluasi Fungsionalitas Sistem

**Tabel X. Evaluasi Fungsionalitas Sistem**

| Kode | SS x (5) | S x (4) | CS x (3) | TS x (2) | STS x (1) | Jumlah |
|------|----------|---------|----------|----------|-----------|--------|
| A1 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |
| A2 | 5 x 5 = 25 | 5 x 4 = 20 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 45 |
| A3 | 7 x 5 = 35 | 3 x 4 = 12 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 47 |
| A4 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |
| A5 | 5 x 5 = 25 | 4 x 4 = 16 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 44 |
| A6 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |

#### b) Variabel 2: Evaluasi Kinerja Sistem

**Tabel X. Evaluasi Kinerja Sistem**

| Kode | SS x (5) | S x (4) | CS x (3) | TS x (2) | STS x (1) | Jumlah |
|------|----------|---------|----------|----------|-----------|--------|
| B1 | 5 x 5 = 25 | 5 x 4 = 20 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 45 |
| B2 | 4 x 5 = 20 | 5 x 4 = 20 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 43 |
| B3 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |
| B4 | 5 x 5 = 25 | 4 x 4 = 16 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 44 |

#### c) Variabel 3: Evaluasi Pengalaman & Tampilan Antarmuka Sistem

**Tabel X. Evaluasi Pengalaman & Tampilan Antarmuka Sistem**

| Kode | SS x (5) | S x (4) | CS x (3) | TS x (2) | STS x (1) | Jumlah |
|------|----------|---------|----------|----------|-----------|--------|
| C1 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |
| C2 | 5 x 5 = 25 | 5 x 4 = 20 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 45 |
| C3 | 4 x 5 = 20 | 5 x 4 = 20 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 43 |
| C4 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |
| C5 | 5 x 5 = 25 | 5 x 4 = 20 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 45 |
| C6 | 4 x 5 = 20 | 5 x 4 = 20 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 43 |
| C7 | 5 x 5 = 25 | 4 x 4 = 16 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 44 |
| C8 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |
| C9 | 5 x 5 = 25 | 4 x 4 = 16 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 44 |

#### d) Variabel 4: Evaluasi Efisiensi & Produktivitas

**Tabel X. Evaluasi Efisiensi & Produktivitas**

| Kode | SS x (5) | S x (4) | CS x (3) | TS x (2) | STS x (1) | Jumlah |
|------|----------|---------|----------|----------|-----------|--------|
| D1 | 7 x 5 = 35 | 3 x 4 = 12 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 47 |
| D2 | 6 x 5 = 30 | 4 x 4 = 16 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 46 |
| D3 | 6 x 5 = 30 | 3 x 4 = 12 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 45 |
| D4 | 5 x 5 = 25 | 5 x 4 = 20 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 45 |
| D5 | 5 x 5 = 25 | 4 x 4 = 16 | 1 x 3 = 3 | 0 x 2 = 0 | 0 x 1 = 0 | 44 |
| D6 | 7 x 5 = 35 | 3 x 4 = 12 | 0 x 3 = 0 | 0 x 2 = 0 | 0 x 1 = 0 | 47 |

### Interpretasi Skor

Setelah mendapatkan hasil UAT yang sudah dikalikan dengan bobot penilaian, selanjutnya hasil akhir jumlah digunakan untuk menghitung nilai persentase. Berikut kriteria interpretasi skor:

**Tabel X. Interpretasi Skor UAT**

| Persentase | Keterangan |
|------------|------------|
| 0% - 20% | Sangat Kurang Baik |
| 21% - 40% | Kurang Baik |
| 41% - 60% | Cukup Baik |
| 61% - 80% | Baik |
| 81% - 100% | Sangat Baik |

Kemudian, hasil akhir jumlah pada tabel-tabel di atas dijadikan bahan acuan untuk mencari nilai rata-rata dan persentase untuk mengukur kelayakan sistem dengan rumus sebagai berikut:

$$\text{Mean} = \frac{\text{Bobot Penilaian}}{\text{Total Responden}}$$

$$\text{Persentase} = \frac{\text{Nilai Mean}}{\text{Bobot Maksimum}} \times 100\%$$

#### a) Evaluasi Fungsionalitas Sistem

**Tabel X. Hasil Perhitungan Fungsionalitas Sistem**

| Kode | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|------|------------|----------------|---------------------|
| A1 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |
| A2 | 45/10 = 4,50 | 4,50/5 × 100% = 90% | |
| A3 | 47/10 = 4,70 | 4,70/5 × 100% = 94% | 92% |
| A4 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |
| A5 | 44/10 = 4,40 | 4,40/5 × 100% = 88% | |
| A6 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |

Dari hasil evaluasi di atas, dapat dilihat bahwa nilai rata-rata evaluasi fungsionalitas sistem yaitu 92%, yang menunjukkan bahwa semua komponen dan fitur sistem berfungsi sesuai dengan spesifikasi dan kebutuhan pengguna.

#### b) Evaluasi Kinerja Sistem

**Tabel X. Hasil Perhitungan Kinerja Sistem**

| Kode | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|------|------------|----------------|---------------------|
| B1 | 45/10 = 4,50 | 4,50/5 × 100% = 90% | |
| B2 | 43/10 = 4,30 | 4,30/5 × 100% = 86% | 89% |
| B3 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |
| B4 | 44/10 = 4,40 | 4,40/5 × 100% = 88% | |

Dari hasil tabel di atas, dapat dilihat bahwa hasil nilai rata-rata evaluasi kinerja sistem yaitu 89%. Jadi dapat disimpulkan bahwa kinerja sistem bekerja efektif dalam memenuhi kebutuhan performa seperti kecepatan, responsivitas, dan stabilitas di berbagai kondisi penggunaan.

#### c) Evaluasi Pengalaman & Tampilan Antarmuka Sistem

**Tabel X. Hasil Perhitungan Pengalaman & Tampilan Antarmuka Sistem**

| Kode | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|------|------------|----------------|---------------------|
| C1 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |
| C2 | 45/10 = 4,50 | 4,50/5 × 100% = 90% | |
| C3 | 43/10 = 4,30 | 4,30/5 × 100% = 86% | |
| C4 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |
| C5 | 45/10 = 4,50 | 4,50/5 × 100% = 90% | 88% |
| C6 | 43/10 = 4,30 | 4,30/5 × 100% = 86% | |
| C7 | 44/10 = 4,40 | 4,40/5 × 100% = 88% | |
| C8 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |
| C9 | 44/10 = 4,40 | 4,40/5 × 100% = 88% | |

Dari tabel di atas dapat dilihat bahwa evaluasi pengalaman dan tampilan antarmuka sistem adalah 88%. Jadi dapat disimpulkan bahwa tampilan sistem ini sudah sesuai dengan kebutuhan pengguna dan memberikan pengalaman yang baik dalam penggunaan.

#### d) Evaluasi Efisiensi & Produktivitas

**Tabel X. Hasil Perhitungan Efisiensi & Produktivitas**

| Kode | Nilai Mean | Persentase (%) | Nilai Rata-rata (%) |
|------|------------|----------------|---------------------|
| D1 | 47/10 = 4,70 | 4,70/5 × 100% = 94% | |
| D2 | 46/10 = 4,60 | 4,60/5 × 100% = 92% | |
| D3 | 45/10 = 4,50 | 4,50/5 × 100% = 90% | 91% |
| D4 | 45/10 = 4,50 | 4,50/5 × 100% = 90% | |
| D5 | 44/10 = 4,40 | 4,40/5 × 100% = 88% | |
| D6 | 47/10 = 4,70 | 4,70/5 × 100% = 94% | |

Tabel di atas menunjukkan bahwa hasil rata-rata untuk evaluasi efisiensi dan produktivitas sistem sebesar 91%. Jadi dapat disimpulkan bahwa sistem ini memiliki kemampuan dalam memaksimalkan dan meningkatkan hasil kerja sesuai dengan kebutuhan pengguna dalam perencanaan ketahanan pangan.

### Hasil Akhir Pengujian UAT

Berdasarkan hasil perhitungan evaluasi kuesioner UAT yang telah dilakukan di atas, kemudian hasil tersebut dirangkum pada tabel berikut:

**Tabel X. Hasil Akhir Perhitungan UAT**

| No. | Variabel | Nilai Persentase | Keterangan |
|-----|----------|------------------|------------|
| 1 | Fungsionalitas Sistem | 92% | Sangat Baik |
| 2 | Kinerja Sistem | 89% | Sangat Baik |
| 3 | Pengalaman & Tampilan Antarmuka Sistem | 88% | Sangat Baik |
| 4 | Efisiensi & Produktivitas | 91% | Sangat Baik |
| | **Rata-rata Keseluruhan** | **90%** | **Sangat Baik** |

Dari tabel hasil akhir di atas, dapat diperoleh kesimpulan bahwa hasil perhitungan mean atau rata-rata persentase kuesioner sistem SIKOLBIA untuk prediksi konsumsi kalori berbasis LSTM Enhanced Ensemble termasuk dalam kategori **Sangat Baik** dengan nilai rata-rata keseluruhan sebesar **90%**. 

Hasil pengujian UAT menunjukkan bahwa sistem telah memenuhi kebutuhan pengguna dari berbagai aspek, yaitu fungsionalitas sistem yang lengkap dan berjalan dengan baik (92%), kinerja sistem yang stabil dan responsif (89%), tampilan antarmuka yang intuitif dan mudah digunakan (88%), serta efisiensi dan produktivitas yang meningkat dalam proses perencanaan ketahanan pangan (91%).

Dengan demikian, sistem SIKOLBIA dapat dinyatakan **layak untuk digunakan** dan telah memenuhi kriteria acceptance dari pengguna akhir sebagai alat bantu dalam prediksi konsumsi kalori nasional untuk mendukung pengambilan keputusan kebijakan ketahanan pangan.

---

## Catatan untuk Implementasi

### Tahapan Pelaksanaan UAT:

1. **Perencanaan UAT**
   - Tentukan waktu pelaksanaan (1-2 minggu)
   - Rekrut responden sesuai kriteria (10 orang: 2 Admin, 5 Pemerintah, 3 Akademisi)
   - Siapkan environment testing yang stabil
   - Buat panduan penggunaan sistem untuk responden

2. **Penyusunan Kuesioner**
   - Buat Google Form berdasarkan 25 pertanyaan di atas
   - Tambahkan bagian profil responden (nama, role, pengalaman SI)
   - Sertakan petunjuk pengisian yang jelas
   - Test form sebelum disebarkan

3. **Pelaksanaan Pengujian**
   - Brief responden tentang tujuan UAT dan cara menggunakan sistem
   - Berikan akses sistem (username/password) sesuai role masing-masing
   - Responden mencoba semua fitur:
     * Login
     * View data historis NBM
     * Run prediksi dengan berbagai parameter
     * View hasil prediksi (tabel + grafik)
     * Export hasil ke Excel
   - Responden mengisi kuesioner setelah mencoba sistem
   - Catat feedback dan kendala yang dihadapi

4. **Analisis Hasil**
   - Export data dari Google Form ke Excel
   - Hitung skor per pertanyaan dan per variabel
   - Hitung nilai mean dan persentase
   - Buat tabel-tabel hasil seperti draft ini
   - Analisis feedback kualitatif untuk improvement

5. **Dokumentasi**
   - Screenshot sistem yang digunakan responden
   - Dokumentasi kendala dan solusi
   - Tabel hasil perhitungan UAT
   - Kesimpulan dan rekomendasi

### Link Google Form Template:
(Buat Google Form dengan struktur seperti ini, nanti bisa disesuaikan)

**Bagian 1: Profil Responden**
- Nama/Inisial
- Role (dropdown: Admin/Pemerintah/Akademisi)
- Pengalaman menggunakan sistem informasi (dropdown: Pemula/Menengah/Ahli)

**Bagian 2: Petunjuk Pengujian**
- Instruksi untuk mencoba fitur-fitur sistem
- Waktu estimasi: 30-45 menit

**Bagian 3-6: Pertanyaan Evaluasi**
- Bagian 3: Fungsionalitas (A1-A6)
- Bagian 4: Kinerja (B1-B4)
- Bagian 5: UI/UX (C1-C9)
- Bagian 6: Efisiensi (D1-D6)

Semua dengan skala Likert 1-5

**Bagian 7: Feedback Terbuka**
- Kendala yang dihadapi?
- Saran perbaikan?
- Komentar tambahan?

---

**PENTING:** Angka-angka dalam draft ini adalah placeholder. Setelah UAT real dilaksanakan, ganti semua angka dengan hasil aktual dari responden.
