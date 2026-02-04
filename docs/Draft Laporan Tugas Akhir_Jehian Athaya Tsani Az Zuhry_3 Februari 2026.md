# KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI

# UNIVERSITAS JENDERAL SOEDIRMAN

# FAKULTAS TEKNIK

# JURUSAN INFORMATIKA

# PURWOKERTO

# 2026

# LAPORAN TUGAS AKHIR

## IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

### SKRIPSI

Diajukan Sebagai Pedoman Pelaksanaan Penelitian Tugas Akhir
pada Jurusan Informatika Fakultas Teknik Universitas Jenderal Soedirman

Disusun Oleh:

**JEHIAN ATHAYA TSANI AZ ZUHRY**

**H1D022006**

---

# KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI

# UNIVERSITAS JENDERAL SOEDIRMAN

# FAKULTAS TEKNIK

# JURUSAN INFORMATIKA

# PURWOKERTO

# 2026

# LAPORAN TUGAS AKHIR

## IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

### SKRIPSI

Diajukan Sebagai Pedoman Pelaksanaan Penelitian Tugas Akhir
pada Jurusan Informatika Fakultas Teknik Universitas Jenderal Soedirman

Disusun Oleh:

**JEHIAN ATHAYA TSANI AZ ZUHRY**

**H1D022006**

---

# LEMBAR PENGESAHAN

## LAPORAN TUGAS AKHIR

### IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

Disusun Oleh:

**JEHIAN ATHAYA TSANI AZ ZUHRY**

**H1D022006**

Diajukan untuk memenuhi salah satu persyaratan memperoleh gelar
Sarjana Komputer pada Program Studi Informatika Fakultas Teknik
Universitas Jenderal Soedirman

Diterima dan disetujui

Pada tanggal: ……………………….

**Dosen Pembimbing I**

Ir. Nofiyati, S.Kom., M.Kom.,IPM
NIP.198112192024212012

**Dosen Pembimbing II**

Devi Astri Nawangnugraeni, S.Pd., M.Kom.
NIP. 199312042024062004

Mengetahui,

**Dekan Fakultas Teknik**

Prof. Dr. Eng.Ir. Agus Maryoto S.T., M.T.,IPU. ASEAN. Eng
NIP. 197109202006041001

---

# LEMBAR PERNYATAAN KEASLIAN SKRIPSI

Saya yang bertanda tangan dibawah ini:

NIM : H1D022006
Nama : Jehian Athaya Tsani Az Zuhry
Program Studi : Informatika

Dengan ini saya menyatakan bahwa skripsi yang saya buat dengan judul:

**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

merupakan hasil karya sendiri dan tidak mengandung karya yang pernah diajukan oleh pihak lain untuk memperoleh gelar akademik di perguruan tinggi mana pun. Sepanjang pengetahuan saya, tidak terdapat pendapat atau karya orang lain yang telah dipublikasikan atau dituliskan sebelumnya, kecuali yang secara jelas dicantumkan sebagai rujukan dan dinyatakan dalam daftar pustaka.

Demikian pernyataan ini saya buat dengan sebenar-benarnya tanpa ada paksaan dari pihak manapun juga. Apabila dikemudian hari terbukti bahwa saya memberikan pernyataan palsu, maka saya bersedia menerima sanksi yang telah ditetapkan.

Purwokerto, 27 Januari 2026

Jehian Athaya Tsani Az Zuhry

---

# KATA PENGANTAR

Segala puji dan syukur penulis panjatkan ke hadirat Allah SWT atas limpahan rahmat, taufik, serta karunia-Nya, sehingga penulis dapat menyelesaikan laporan penelitian dengan judul "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian." ini dengan baik dan tepat waktu.

Laporan penelitian ini disusun sebagai salah satu persyaratan untuk mencapai gelar Sarjana pada Program Studi Informatika, Fakultas Teknik, Universitas Jenderal Soedirman. Dalam proses penyusunan laporan ini, penulis banyak menerima bantuan, bimbingan, dan dukungan dari berbagai pihak, baik secara langsung maupun tidak langsung. Oleh karena itu, pada kesempatan ini penulis ingin menyampaikan ucapan terima kasih dan penghargaan setinggi-tingginya kepada:

1. Bapak Prof. Dr. Eng. Ir. Agus Maryoto, S.T., M.T., IPU., ASEAN.Eng., selaku Dekan Fakultas Teknik, Universitas Jenderal Soedirman.
2. Bapak Dr. Ir. Lasmedi Afuan, S.T., M.Cs., IPM., selaku Ketua Jurusan Informatika, Fakultas Teknik, Universitas Jenderal Soedirman.
3. Bapak Ir. Bangun Wijayanto, S.T., M.Cs., IPM., selaku Dosen Pembimbing Akademik yang telah memberikan arahan akademik selama masa perkuliahan.
4. Ibu Nofiyati, S.Kom., M.Kom. IPM., selaku Dosen Pembimbing I dan Ibu Devi Astri Nawangnugraeni, S.Pd., M.Kom., selaku Dosen Pembimbing II yang dengan penuh perhatian telah memberikan bimbingan dan arahan selama penyusunan tugas akhir ini.
5. Orang tua dan keluarga tercinta atas doa, semangat, serta dukungan yang tiada harganya.
6. Rekan-rekan seperjuangan di Jurusan Informatika angkatan 2022, serta seluruh pihak yang telah memberikan dukungan dan masukan.
7. Seluruh pihak yang tidak dapat disebutkan satu per satu yang telah memberikan bantuan, dorongan, dan masukan berharga sehingga laporan ini dapat terselesaikan dengan baik.

Penulis menyadari bahwa laporan ini masih jauh dari sempurna dan memerlukan banyak perbaikan. Oleh sebab itu, penulis sangat mengharapkan kritik, saran, dan masukan yang membangun dari berbagai pihak demi penyempurnaan laporan ini serta peningkatan kualitas penelitian yang akan dilakukan di masa mendatang.

Purwokerto, 27 Januari 2026

Jehian Athaya Tsani Az Zuhry

---

# PERSEMBAHAN

Segala puji dan syukur dipanjatkan ke hadirat Allah SWT atas limpahan rahmat, hidayah, dan karunia-Nya yang senantiasa menyertai setiap langkah dalam penyelesaian tugas akhir ini. Shalawat serta salam semoga selalu tercurah kepada junjungan kita Nabi Muhammad SAW, yang telah membawa umat manusia dari masa kegelapan menuju cahaya ilmu dan kebenaran.

Dengan penuh rasa hormat dan terima kasih, tugas akhir ini dipersembahkan kepada kedua orang tua tercinta, Bapak Fahrudin Juhri dan Ibu Khotimah Rahayuningsih, yang senantiasa memberikan doa, kasih sayang, dan dukungan tanpa henti. Terima kasih juga disampaikan kepada orang tercinta, Pasa Kholilah, yang selalu memberikan semangat dan motivasi.

Ucapan terima kasih turut disampaikan kepada sahabat-sahabat yang telah memberikan bantuan, dukungan, serta doa sehingga tugas akhir ini dapat diselesaikan dengan baik.

---

# MOTTO

"Maka sesungguhnya bersama kesulitan ada kemudahan. Sesungguhnya bersama kesulitan ada kemudahan."

Al-Qur'an, Surah Al-Insyirah ayat 5-6

"Setiap kesulitan membawa hikmah, dan kesabaran adalah kunci untuk melewatinya."

---

# DAFTAR ISI

- LEMBAR PENGESAHAN.........................................................................................................i
- LEMBAR PERNYATAAN KEASLIAN SKRIPSI....................................................................ii
- KATA PENGANTAR ................................................................................................................iii
- PERSEMBAHAN ...................................................................................................................... v
- MOTTO.....................................................................................................................................vi
- DAFTAR ISI.............................................................................................................................vii
- DAFTAR GAMBAR.................................................................................................................. x
- DAFTAR TABEL......................................................................................................................xi
- DAFTAR PERSAMAAN.........................................................................................................xii
- DAFTAR LISTING PROGRAM.............................................................................................xiii
- ABSTRAK...............................................................................................................................xiv
- ABSTRACT ............................................................................................................................... xv
- BAB I PENDAHULUAN .......................................................................................................... 1
  - 1.1 Latar Belakang............................................................................................................1
  - 1.2 Rumusan Masalah.......................................................................................................2
  - 1.3 Batasan Masalah .........................................................................................................3
  - 1.4 Tujuan Penelitian........................................................................................................3
  - 1.5 Tujuan Penelitian........................................................................................................4
- BAB II TINJAUAN PUSTAKA ................................................................................................ 6
  - 2.1 Ketahanan Pangan ......................................................................................................6
  - 2.2 Time Series Forecasting .............................................................................................8
  - 2.3 Long Short Term Memory...........................................................................................9
  - 2.4 XGBoost ................................................................................................................... 11
  - 2.5 HuberRegressor ........................................................................................................13
  - 2.6 Ensemble Learning...................................................................................................15
  - 2.7 Feature Engineering.................................................................................................18
  - 2.8 Data Preprocessing...................................................................................................21
  - 2.9 Metrik Evaluasi.........................................................................................................22
  - 2.10 Penelitian Sejenis......................................................................................................24
- BAB III METODE PENELITIAN ........................................................................................... 26
  - 3.1 Waktu dan Tempat Penelitian ...................................................................................26
  - 3.2 Data dan Alat Penelitian ...........................................................................................26
    - 3.2.1 Data Penelitian......................................................................................................26
    - 3.2.2 Alat Penelitian ......................................................................................................28
  - 3.3 Tahapan Penelitian....................................................................................................31
- BAB IV HASIL DAN PEMBAHASAN.................................................................................. 38
  - 4.1 Pemahaman Bisnis....................................................................................................38
  - 4.2 Pemahaman Data ......................................................................................................38
  - 4.3 Persiapan Data ..........................................................................................................40
    - 4.3.1 Data Cleaning dan Preprocessing ........................................................................40
    - 4.3.2 Perhitungan Kalori per Kapita..............................................................................41
    - 4.3.3 Penanganan Outlier ..............................................................................................42
  - 4.4 Pemodelan ................................................................................................................44
    - 4.4.1 Feature Engineering.............................................................................................44
    - 4.4.2 Pembagian Dataset ...............................................................................................46
    - 4.4.3 Normalisasi Data ..................................................................................................47
    - 4.4.4 Pengembangan Model LSTM...............................................................................48
    - 4.4.5 Pengembangan Model XGBoost ..........................................................................50
    - 4.4.6 Pengembangan Model HuberRegressor ...............................................................51
    - 4.4.7 Optimisasi Ensemble dengan Differential Evolution............................................52
    - 4.4.8 Strategi Conditional Ensemble .............................................................................53
    - 4.4.9 Perbandingan Performa Model.............................................................................54
  - 4.5 Evaluasi ....................................................................................................................54
    - 4.5.1 Visualisasi Performa Model..................................................................................54
    - 4.5.2 Analisis Performa per Komoditas.........................................................................56
    - 4.5.3 Ringkasan Evaluasi Model ...................................................................................58
  - 4.6 Penyebaran................................................................................................................58
    - 4.6.1 Arsitektur Sistem Prediksi....................................................................................58
    - 4.6.2 Mekanisme Prediksi Multi-Periode ......................................................................59
    - 4.6.3 Hasil Prediksi Komoditas Terpilih........................................................................60
    - 4.6.4 Prediksi Agregat Seluruh Komoditas ...................................................................61
    - 4.6.5 Implementasi pada Web........................................................................................62
- BAB V KESIMPULAN DAN SARAN ................................................................................... 63
  - 5.1 Kesimpulan...............................................................................................................63
  - 5.2 Saran .........................................................................................................................64
- DAFTAR PUSTAKA ............................................................................................................... 65

---

# DAFTAR GAMBAR

- Gambar 1. Arsitektur LSTM Cell dengan Mekanisme Gating................................................. 11
- Gambar 2. Dokumentasi Diskusi dengan Stakeholder Pusdatin Kementerian Pertanian.........26
- Gambar 3. Alur CRISP-DM .....................................................................................................31
- Gambar 4. Visualisasi Performa Model....................................................................................55

---

# DAFTAR TABEL

- Tabel 1. Penelitian Sejenis........................................................................................................24
- Tabel 2. Karakteristik Dataset Neraca Bahan Makanan Indonesia...........................................27
- Tabel 3. Distribusi Parameter Data Berdasarkan Kategori.......................................................27
- Tabel 4. Dimensi Dataset Awal.................................................................................................38
- Tabel 5. Distribusi Missing Values Dataset Transaksi ..............................................................39
- Tabel 6. Fitur Terpilih Setelah Filter dan Merge.......................................................................40
- Tabel 7. Hasil Perhitungan Kalori per Kapita per Hari.............................................................41
- Tabel 8. Perbandingan Threshold IQR terhadap Eliminasi Data ..............................................42
- Tabel 8. Distribusi Subset Dataset ............................................................................................47
- Tabel 10. Metrik Training Model LSTM..................................................................................49
- Tabel 11. Konfigurasi Hyperparameter XGBoost.....................................................................50
- Tabel 12. Perbandingan Performa Model pada Subset Testing.................................................54
- Tabel 13. 10 Komoditas dengan Performa Terbaik (MAPE Terendah)....................................56
- Tabel 14. 10 Komoditas dengan Performa Terburuk (MAPE Tertinggi)..................................57
- Tabel 15. Hasil Prediksi Konsumsi Beras Januari-Maret 2025 ................................................60
- Tabel 16. Hasil Prediksi Konsumsi Jagung Januari-Maret 2025 ..............................................61
- Tabel 17. Prediksi Agregat Konsumsi Januari-Maret 2025 ......................................................61

---

# DAFTAR PERSAMAAN

- Persamaan (1) Kalori per Kapita per Hari..................................................................................7
- Persamaan (2) .............................................................................................................................9
- Persamaan (3) ...........................................................................................................................10
- Persamaan (4) ...........................................................................................................................10
- Persamaan (5) ...........................................................................................................................10
- Persamaan (6) ...........................................................................................................................10
- Persamaan (7) ...........................................................................................................................10
- Persamaan (8) ...........................................................................................................................12
- Persamaan (9) ...........................................................................................................................12
- Persamaan (10) .........................................................................................................................14
- Persamaan (11) .........................................................................................................................16
- Persamaan (12) .........................................................................................................................16
- Persamaan (13) .........................................................................................................................16
- Persamaan (14) .........................................................................................................................17
- Persamaan (15) .........................................................................................................................18
- Persamaan (16) .........................................................................................................................19
- Persamaan (17) .........................................................................................................................19
- Persamaan (18) .........................................................................................................................20
- Persamaan (19) .........................................................................................................................20
- Persamaan (20) .........................................................................................................................21
- Persamaan (21) .........................................................................................................................21
- Persamaan (22) .........................................................................................................................22
- Persamaan (23) .........................................................................................................................22
- Persamaan (24) .........................................................................................................................23
- Persamaan (25) .........................................................................................................................23
- Persamaan (26) .........................................................................................................................24

---

# DAFTAR LISTING PROGRAM

- Listing Program 1. Implementasi Imputasi dan Eliminasi Outlier...........................................43
- Listing Program 2. Implementasi Lag Features........................................................................45
- Listing Program 3. Implementasi Rolling Window Features...................................................45
- Listing Program 4. Arsitektur Model LSTM............................................................................49
- Listing Program 5. Implementasi Model XGBoost..................................................................51
- Listing Program 6. Optimisasi Ensemble dengan Differential Evolution ................................53
- Listing Program 7. Inisialisasi Kelas KaloriPredictor..............................................................59

---

# IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**JEHIAN ATHAYA TSANI AZ ZUHRY**

**H1D022006**

## ABSTRAK

Ketahanan pangan merupakan isu kritis bagi Indonesia yang menempati peringkat ke-69 dari 113 negara pada Global Food Security Index 2024. Metode prediksi konvensional seperti regresi linear dan penghalusan eksponensial tunggal memiliki akurasi terbatas dengan MAPE 15-20% serta tidak mampu menangkap pola temporal konsumsi pangan yang dipengaruhi oleh faktor musiman, krisis ekonomi, dan perubahan iklim. Penelitian ini bertujuan mengembangkan model machine learning untuk memprediksi konsumsi kalori per kapita berdasarkan data Neraca Bahan Makanan (NBM) Indonesia dengan target akurasi MAPE di bawah 10%. Metode penelitian menggunakan kerangka kerja Cross-Industry Standard Process for Data Mining (CRISP-DM) dengan data NBM periode 1993-2024 sebanyak 47.087 samples yang mencakup 112 komoditas dan diolah menjadi 39 fitur prediktif melalui feature engineering komprehensif (lag features, moving averages, cyclical encoding, economic ratios, crisis indicators). Data dibagi secara kronologis menjadi training set (33.007 samples, 1993-2016), validation set (7.155 samples, 2016-2020), dan test set (6.925 samples, 2020-2024). Empat model dikembangkan yaitu LSTM, HuberRegressor, XGBoost, dan LSTM Enhanced Ensemble dengan strategi conditional (threshold 5000) menggunakan weighted ensemble (LSTM 90%, XGBoost 5%, HuberRegressor 5%) yang dioptimasi dengan Differential Evolution. Pengujian menggunakan metrik MAE, RMSE, MAPE, dan R² menunjukkan LSTM Enhanced Ensemble mencapai performa terbaik dengan MAE 867.04 kalori/hari, RMSE 1788.78 kalori/hari, MAPE 3.73%, dan R² 0.9901, melampaui target penelitian (<10% MAPE) dengan margin 62.7% dan menjelaskan 99.01% variasi konsumsi kalori. Model diintegrasikan ke dalam sistem informasi berbasis web SIKOLBIA menggunakan arsitektur microservices dengan Laravel 12 untuk antarmuka pengguna, FastAPI untuk ML service, dan Docker untuk containerization dengan response time <5 detik dan availability 99.5%. Penelitian ini memberikan kontribusi di bidang ilmu komputer berupa conditional ensemble architecture dengan threshold adaptif untuk time series heterogen, feature engineering framework 39 fitur prediktif, hyperparameter optimization menggunakan Differential Evolution, MLOps implementation untuk production-grade machine learning system, dan end-to-end web-based prediction system yang dapat diadaptasi untuk time series forecasting di berbagai domain.

**Kata Kunci:** Ensemble Learning, Ketahanan Pangan, LSTM, Machine Learning, Neraca Bahan Makanan, Prediksi Konsumsi Kalori, Time Series Forecasting

---

# IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**JEHIAN ATHAYA TSANI AZ ZUHRY**

**H1D022006**

## ABSTRACT

Food security is a critical issue for Indonesia, which ranks 69th out of 113 countries in the Global Food Security Index 2024. Conventional prediction methods such as linear regression and single exponential smoothing have limited accuracy with MAPE of 15-20% and are unable to capture temporal patterns of food consumption influenced by seasonal factors, economic crises, and climate change. This research aims to develop a machine learning model to predict calorie consumption per capita based on Indonesia's Food Balance Sheet (FBS) data with a target accuracy of MAPE below 10%. The research methodology employs the Cross-Industry Standard Process for Data Mining (CRISP-DM) framework with FBS data from 1994-2024 comprising 48,696 rows covering 112 commodities processed into 39 predictive features. Data was chronologically divided into training set (1994-2016), validation set (2017-2020), and test set (2021-2024). Four models were developed: XGBoost, LSTM, HuberRegressor, and LSTM Enhanced Ensemble with optimal weights of 30:40:30. Evaluation using RMSE, MAE, MAPE, and R² metrics shows that LSTM Enhanced Ensemble achieved MAPE of 9.32% with R² of 0.7938, surpassing the research target and explaining 79.38% of calorie consumption variation. The model was integrated into a web-based information system using microservices architecture with Laravel 11 for user interface, FastAPI for model service, and Docker for containerization with response time below 5 seconds. This research contributes an ensemble architecture combining sequential modeling with robust regression for sparse and high-volatility time series data, feature engineering techniques with cyclical encoding and crisis indicators, and microservices implementation as best practice for deploying machine learning models in production environments.

**Keywords:** Calorie Consumption Prediction, Food Balance Sheet, Food Security, LSTM, Machine Learning, Time Series Forecasting

---

# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Ketahanan pangan merupakan isu strategis yang mempengaruhi stabilitas sosial, ekonomi, dan politik Indonesia [1]. Data Global Food Security Index (GFSI) 2024 menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2, posisi yang masih tertinggal dibandingkan negara ASEAN lainnya seperti Singapura, Malaysia, dan Thailand [2]. Dengan populasi lebih dari 280 juta jiwa dan proyeksi mencapai 285 juta pada tahun 2025, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan yang berkelanjutan [3]. Tantangan ini diperparah oleh volatilitas harga pangan global, perubahan iklim, serta keterbatasan lahan produktif yang menyebabkan fluktuasi konsumsi kalori berkisar dari 2.156 hingga 2.978 kkal/kapita/hari [4], [5].

Metode prediksi konvensional memiliki keterbatasan signifikan dalam menangkap kompleksitas pola temporal konsumsi pangan [6]. Penelitian menggunakan Linear Regression untuk prediksi pemesanan obat rumah sakit menghasilkan MAPE 12,4% [7], sementara penelitian sejenis pada penjualan mobil mencapai MAPE 12,47% dengan kelemahan tidak menangkap faktor musiman [8]. Metode Multi-Layer Perceptron Neural Network bahkan menghasilkan MAPE 22,3-25,4% pada data penjualan obat [9], sementara Single Exponential Smoothing mencapai MAPE 29% pada data dengan volatilitas tinggi [10]. Studi peramalan produksi padi dan konsumsi beras menggunakan ARIMA menghasilkan MAPE 11,22% dan 10,17% [11], sementara metode Double Exponential Smoothing untuk data indeks harga pangan mencapai MAPE 2,00-2,25% [12]. Ketidakakuratan prediksi berdampak langsung pada inefisiensi perencanaan, sebagaimana tercermin dari penurunan alokasi APBN untuk ketahanan pangan dari Rp 99 triliun (2021) menjadi Rp 92,2 triliun (2022), sementara kebutuhan subsidi pupuk meningkat menjadi Rp 25,3 triliun akibat volatilitas harga global [13], [14].

Long Short-Term Memory (LSTM) sebagai arsitektur machine learning telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long-term dependencies melalui mekanisme gating yang mengatur aliran informasi [15]. Namun, model LSTM individual rentan terhadap overfitting pada nilai ekstrem [16]. Pendekatan ensemble learning yang mengintegrasikan LSTM dengan algoritma robust seperti XGBoost dan HuberRegressor dapat meningkatkan akurasi prediksi hingga 25-30% dibandingkan model tunggal [17]. Dataset Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 dengan 48.696 records mencakup 112 komoditas dari 10 kelompok pangan, menyediakan foundation yang komprehensif untuk pengembangan model prediktif [18]. Implementasi sistem prediksi berbasis web dengan arsitektur microservices telah terbukti efektif dalam deployment model machine learning untuk aplikasi real-time [19], [20].

Berdasarkan kondisi tersebut, penelitian ini mengimplementasikan model LSTM Enhanced Ensemble yang menggabungkan XGBoost, LSTM, dan HuberRegressor untuk prediksi konsumsi kalori harian per kapita dengan target akurasi MAPE < 10%. Model diintegrasikan ke dalam sistem informasi berbasis web menggunakan arsitektur microservices (Laravel, FastAPI, Docker) untuk memberikan layanan prediksi yang mendukung pengambilan keputusan dalam perencanaan ketahanan pangan Indonesia.

## 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana mengimplementasikan model LSTM Enhanced Ensemble dengan optimasi hyperparameter, feature engineering (lag features, rolling statistics, cyclical encoding), dan strategi conditional ensemble untuk memprediksi konsumsi kalori per kapita per hari berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%?
2. Apakah hasil prediksi yang dihasilkan oleh sistem web sesuai dengan performa model LSTM Enhanced Ensemble yang telah dikembangkan dan dievaluasi pada subset testing?

## 1.3 Batasan Masalah

Berikut adalah batasan masalah yang ditetapkan agar penelitian ini tetap terarah dan terfokus:

1. Penelitian ini berfokus pada prediksi konsumsi kalori per kapita per hari agregat nasional per komoditas menggunakan data NBM Indonesia periode 1993-2024 dengan 48.696 records mencakup 112 komoditas dari 10 kelompok pangan, tidak mencakup prediksi regional atau individual.
2. Model yang dikembangkan terdiri dari tiga algoritma (XGBoost, LSTM, HuberRegressor) yang dikombinasikan menggunakan weighted averaging dengan optimasi Differential Evolution, dengan evaluasi menggunakan metrik RMSE, MAE, MAPE, dan R².
3. Implementasi sistem menggunakan arsitektur microservices dengan Laravel 11 untuk web interface, FastAPI untuk model serving, dan Docker untuk containerization, ditujukan untuk stakeholder pemerintah (Kementerian Pertanian) sebagai sistem pendukung keputusan ketahanan pangan.

## 1.4 Tujuan Penelitian

Fokus utama penelitian ini dapat diuraikan sebagai berikut:

1. Mengimplementasikan dan mengevaluasi model LSTM Enhanced Ensemble untuk prediksi konsumsi kalori per kapita per hari dengan target akurasi MAPE < 10% yang diukur melalui metrik RMSE, MAE, MAPE, dan R² pada data testing periode 2021-2024.
2. Menganalisis performa model pada berbagai kelompok komoditas dan periode volatilitas untuk mengidentifikasi kekuatan dan kelemahan model dalam menangkap pola temporal konsumsi pangan yang kompleks.
3. Mengintegrasikan model terbaik ke dalam sistem informasi berbasis web dengan arsitektur microservices untuk memberikan layanan prediksi yang mendukung pengambilan keputusan dalam perencanaan ketahanan pangan nasional.

## 1.5 Manfaat Penelitian

Berikut adalah manfaat yang diharapkan dari penelitian ini bagi peneliti, pembaca, dan pemerintah serta masyarakat:

### 1. Bagi Peneliti

Memberikan pengalaman praktis dalam implementasi ensemble deep learning untuk time series forecasting dan kontribusi ilmiah berupa arsitektur model yang menggabungkan sequence modeling dengan robust regression untuk data konsumsi pangan dengan karakteristik sparse dan volatilitas tinggi, serta teknik feature engineering komprehensif yang dapat menjadi referensi untuk penelitian sejenis.

### 2. Bagi Pembaca

Penelitian ini memberikan wawasan mengenai penerapan algoritma LSTM dalam prediksi konsumsi kalori berbasis data NBM dan menyajikan informasi yang bermanfaat bagi akademisi dan praktisi yang ingin mengembangkan sistem prediksi ketahanan pangan.

### 3. Bagi Pemerintah

Menyediakan sistem prediksi konsumsi kalori dengan akurasi tinggi (MAPE < 10%) yang mendukung perencanaan alokasi anggaran ketahanan pangan lebih efisien, sistem early warning untuk antisipasi krisis pangan berdasarkan proyeksi multi-periode, dan dashboard monitoring yang memfasilitasi pengambilan keputusan berbasis data untuk kebijakan distribusi dan subsidi pangan.

---

# BAB II TINJAUAN PUSTAKA

## 2.1 Ketahanan Pangan

Ketahanan pangan merupakan kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan yang tercermin dari tersedianya pangan yang cukup, baik jumlah maupun mutunya, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat untuk dapat hidup sehat, aktif, dan produktif secara berkelanjutan (Undang-Undang Republik Indonesia Nomor 18 Tahun 2012). Ketahanan pangan memiliki empat pilar utama yaitu ketersediaan pangan (availability), akses pangan (accessibility), pemanfaatan pangan (utilization), dan stabilitas pangan (stability) [21].

Ketersediaan pangan berkaitan dengan suplai pangan yang cukup untuk memenuhi kebutuhan seluruh penduduk, baik yang berasal dari produksi dalam negeri maupun impor. Akses pangan mengacu pada kemampuan rumah tangga untuk memperoleh pangan yang cukup melalui kombinasi produksi sendiri, pembelian, barter, hadiah, pinjaman, dan bantuan pangan. Pemanfaatan pangan berkaitan dengan penggunaan pangan untuk memenuhi kebutuhan gizi dan kesehatan yang optimal. Stabilitas pangan menekankan pada ketersediaan dan akses pangan yang stabil sepanjang waktu tanpa fluktuasi musiman atau tahunan yang ekstrem [22].

Indonesia sebagai negara dengan populasi besar menghadapi tantangan kompleks dalam menjaga ketahanan pangan nasional. Global Food Security Index 2023 menempatkan Indonesia pada peringkat 63 dari 113 negara dengan skor 59,2 dari 100, mengindikasikan masih terdapat ruang signifikan untuk perbaikan sistem ketahanan pangan. Tantangan utama meliputi pertumbuhan populasi yang terus meningkat, perubahan iklim yang mempengaruhi produksi pertanian, keterbatasan lahan produktif, serta volatilitas harga pangan global [23].

Neraca Bahan Makanan (NBM) merupakan instrumen penting dalam monitoring ketahanan pangan nasional. NBM menyajikan gambaran menyeluruh tentang situasi penyediaan dan penggunaan bahan makanan di suatu negara atau wilayah dalam periode tertentu, umumnya satu tahun. Data NBM mencakup produksi, impor, ekspor, perubahan stok, dan berbagai penggunaan komoditas pangan seperti untuk konsumsi manusia, pakan ternak, bibit, industri, dan keperluan lainnya [24].

Dalam analisis ketahanan pangan, data NBM perlu dikonversi menjadi indikator konsumsi kalori per kapita per hari untuk mengukur tingkat kecukupan energi masyarakat. Perhitungan ini mempertimbangkan volume konsumsi bahan makanan, kandungan kalori per satuan berat komoditas, jumlah populasi, dan durasi periode pengamatan. Persamaan (1) menunjukkan formula standar perhitungan kalori per kapita per hari yang digunakan dalam analisis NBM.

**Persamaan (1):**

```
Kalori per Kapita per Hari = (bahan_makanan × 10⁹ × kalori_per_100g) / (populasi × hari × 100)
```

dimana bahan_makanan merupakan konsumsi dalam satuan ribu ton, kalori_per_100g adalah kandungan energi komoditas dalam kalori per 100 gram, populasi adalah jumlah penduduk dalam jiwa, dan hari adalah jumlah hari dalam periode pengamatan.

Formula ini memungkinkan standardisasi pengukuran konsumsi pangan antar periode waktu dan antar komoditas dengan karakteristik nutrisi berbeda. Konversi ini menjadi dasar dalam penetapan target kecukupan gizi nasional, evaluasi ketersediaan pangan, serta proyeksi kebutuhan pangan masa depan yang diperlukan dalam perencanaan ketahanan pangan berkelanjutan.

## 2.2 Time Series Forecasting

Time series forecasting merupakan teknik prediksi nilai masa depan berdasarkan pola historis data yang diobservasi secara berurutan dalam interval waktu tertentu. Data time series memiliki karakteristik khusus berupa ketergantungan temporal dimana observasi saat ini dipengaruhi oleh observasi sebelumnya, pola tren yang menunjukkan pergerakan jangka panjang naik atau turun, pola musiman (seasonal) yang berulang dalam periode tetap, dan pola siklikal yang berfluktuasi dalam periode tidak tetap [25].

Model time series tradisional seperti ARIMA (Autoregressive Integrated Moving Average) telah lama digunakan untuk prediksi data temporal. Model ARIMA menggabungkan tiga komponen: autoregressive (AR) yang memodelkan hubungan linear antara observasi dengan nilai lag-nya, integrated (I) yang menangani non-stasioneritas melalui differencing, dan moving average (MA) yang memodelkan hubungan antara observasi dengan error lag-nya. Namun, ARIMA memiliki keterbatasan dalam menangkap pola non-linear kompleks dan memerlukan asumsi stasioneritas yang sering dilanggar pada data konsumsi pangan [26].

Model machine learning menawarkan pendekatan alternatif yang lebih fleksibel untuk time series forecasting. Algoritma seperti Random Forest, Gradient Boosting, dan Support Vector Regression dapat menangkap hubungan non-linear tanpa asumsi distribusi data yang ketat. Namun, model-model ini tidak secara eksplisit memodelkan struktur temporal dan memerlukan feature engineering ekstensif untuk mengekstrak informasi temporal seperti lag features, rolling statistics, dan seasonal indicators [27].

Deep learning khususnya Recurrent Neural Network (RNN) dan variannya telah menunjukkan keunggulan signifikan dalam time series forecasting. RNN dirancang untuk memproses data sekuensial dengan mempertahankan hidden state yang merangkum informasi dari timestep sebelumnya. Namun, RNN standar mengalami masalah vanishing gradient yang menghambat pembelajaran dependencies jangka panjang. LSTM (Long Short-Term Memory) mengatasi keterbatasan ini melalui mekanisme gating yang mengatur aliran informasi.

## 2.3 Long Short Term Memory

LSTM merupakan arsitektur neural network khusus yang dirancang untuk menangkap dependencies temporal jangka panjang dalam data sekuensial. LSTM diperkenalkan oleh Hochreiter dan Schmidhuber pada tahun 1997 sebagai solusi terhadap masalah vanishing gradient yang dialami RNN standar saat mempelajari pola temporal yang memiliki jarak waktu panjang [28].

Arsitektur LSTM menggunakan struktur cell state dan tiga gate untuk mengontrol aliran informasi: forget gate, input gate, dan output gate. Cell state berfungsi sebagai memori jangka panjang yang dapat mempertahankan informasi melalui banyak timesteps, sedangkan gates mengatur informasi mana yang ditambahkan, dihapus, atau dikeluarkan dari cell state [29].

Forget gate menentukan informasi mana dari cell state sebelumnya yang akan dihapus. Gate ini menggunakan fungsi sigmoid yang menghasilkan nilai antara 0 (hapus sepenuhnya) dan 1 (pertahankan sepenuhnya). Persamaan (2) menunjukkan operasi forget gate.

**Persamaan (2):**

```
f_t = σ(W_f · [h_{t-1}, x_t] + b_f)
```

dimana f*t adalah output forget gate pada timestep t, σ adalah fungsi sigmoid, W_f adalah matriks bobot, h*{t-1} adalah hidden state sebelumnya, x_t adalah input saat ini, dan b_f adalah bias.

Input gate menentukan informasi baru mana yang akan ditambahkan ke cell state. Gate ini terdiri dari dua bagian: lapisan sigmoid yang menentukan nilai mana yang akan diupdate, dan lapisan tanh yang menciptakan vektor kandidat nilai baru. Persamaan (3) dan (4) menunjukkan operasi input gate.

**Persamaan (3):**

```
i_t = σ(W_i · [h_{t-1}, x_t] + b_i)
```

**Persamaan (4):**

```
C̃_t = tanh(W_c · [h_{t-1}, x_t] + b_C)
```

dimana i_t adalah output input gate, W_i dan W_c adalah matriks bobot, b_i dan b_C adalah bias, serta C̃_t adalah kandidat cell state baru.

Update cell state menggabungkan informasi dari forget gate dan input gate. Cell state lama dikalikan dengan output forget gate untuk menghapus informasi yang tidak relevan, kemudian ditambahkan dengan kandidat cell state baru yang dikalikan dengan output input gate. Persamaan (5) menunjukkan update cell state.

**Persamaan (5):**

```
C_t = f_t · C_{t-1} + i_t · C̃_t
```

dimana C*t adalah cell state saat ini dan C*{t-1} adalah cell state sebelumnya.

Output gate menentukan bagian mana dari cell state yang akan menjadi output. Gate ini menggunakan fungsi sigmoid untuk memutuskan bagian mana dari cell state yang akan dikeluarkan, kemudian cell state diproses melalui tanh dan dikalikan dengan output sigmoid. Persamaan (6) dan (7) menunjukkan operasi output gate.

**Persamaan (6):**

```
o_t = σ(W_o · [h_{t-1}, x_t] + b_o)
```

**Persamaan (7):**

```
h_t = o_t · tanh(C_t)
```

dimana o_t adalah output output gate, W_o adalah matriks bobot, b_o adalah bias, dan h_t adalah hidden state saat ini yang juga merupakan output LSTM.

LSTM telah berhasil diterapkan pada berbagai domain time series forecasting termasuk prediksi harga saham, peramalan permintaan energi, prediksi cuaca, dan analisis data sensor. Keunggulan LSTM dalam menangkap pola temporal jangka panjang membuatnya sesuai untuk data dengan dependencies kompleks yang tidak dapat ditangkap oleh model tradisional [30].

Dalam konteks prediksi konsumsi pangan, LSTM dapat mempelajari pola musiman seperti peningkatan konsumsi beras saat Ramadan, tren jangka panjang perubahan preferensi konsumsi, serta dampak peristiwa eksternal seperti krisis ekonomi atau pandemi terhadap pola konsumsi. Kemampuan LSTM untuk mempertahankan informasi jangka panjang melalui cell state memungkinkan model memahami bahwa kondisi ekonomi beberapa bulan lalu dapat mempengaruhi keputusan konsumsi saat ini. Ilustrasi lengkap arsitektur LSTM cell dengan mekanisme gating ditunjukkan pada Gambar 1.

![Gambar 1. Arsitektur LSTM Cell dengan Mekanisme Gating]

## 2.4 XGBoost

XGBoost (Extreme Gradient Boosting) merupakan implementasi efisien dari algoritma gradient boosting yang dikembangkan oleh Chen dan Guestrin pada tahun 2016. XGBoost membangun ensemble dari decision trees secara sekuensial dimana setiap tree baru berfokus memperbaiki kesalahan tree sebelumnya melalui optimasi fungsi objektif dengan regularisasi [31].

Gradient boosting merupakan teknik ensemble learning yang membangun model prediktif kuat dengan menggabungkan banyak model lemah (weak learners) secara bertahap. Setiap model baru dilatih untuk memprediksi residual atau error dari model sebelumnya, sehingga secara progresif mengurangi kesalahan prediksi [32].

Fungsi objektif XGBoost terdiri dari dua komponen: loss function yang mengukur seberapa baik model memprediksi data training, dan regularization term yang mengontrol kompleksitas model untuk mencegah overfitting. Persamaan (8) menunjukkan fungsi objektif XGBoost.

**Persamaan (8):**

```
ℒ(φ) = Σ_{i=1}^n l(y_i, ŷ_i) + Σ_{k=1}^K Ω(f_k)
```

dimana l adalah loss function yang dapat berupa MSE untuk regresi atau log loss untuk klasifikasi, y_i adalah nilai aktual, ŷ_i adalah nilai prediksi, K adalah jumlah trees, dan Ω adalah regularization term.

Regularization term mengontrol kompleksitas setiap tree melalui penalti pada jumlah leaf nodes dan magnitude bobot leaf. Persamaan (9) menunjukkan formula regularisasi.

**Persamaan (9):**

```
Ω(f) = γT + (1/2)λ Σ_{j=1}^T w_j²
```

dimana γ adalah penalti untuk jumlah leaf nodes T, λ adalah koefisien regularisasi L2, dan w_j² adalah bobot pada leaf j.

XGBoost memiliki berbagai hyperparameter yang mempengaruhi performa dan kompleksitas model. Parameter n_estimators menentukan jumlah trees dalam ensemble, dimana nilai lebih besar dapat meningkatkan akurasi namun juga meningkatkan risiko overfitting dan waktu komputasi. Parameter max_depth membatasi kedalaman maksimal setiap tree, mengontrol kompleksitas model dan mencegah overfitting.

Parameter learning_rate atau eta mengontrol kontribusi setiap tree terhadap prediksi final. Nilai kecil membuat pembelajaran lebih konservatif dan biasanya memerlukan lebih banyak trees, namun menghasilkan model yang lebih robust. Parameter subsample menentukan fraksi sampel yang digunakan untuk melatih setiap tree, dimana nilai di bawah 1.0 menciptakan stochastic gradient boosting yang dapat meningkatkan generalisasi.

Parameter colsample_bytree menentukan fraksi fitur yang digunakan untuk membangun setiap tree. Pengambilan sampel fitur secara acak meningkatkan diversitas antar trees dan mengurangi korelasi, menghasilkan ensemble yang lebih kuat. Parameter min_child_weight menentukan jumlah minimal instance weight yang diperlukan dalam sebuah child node, berfungsi sebagai regularisasi untuk mencegah overfitting pada data dengan noise tinggi.

XGBoost telah terbukti efektif pada berbagai kompetisi machine learning dan aplikasi praktis, sering kali mencapai performa terbaik pada data tabular. Dalam konteks regresi, XGBoost menggunakan MSE atau MAE sebagai loss function dan membangun trees untuk meminimalkan error prediksi.

Keunggulan XGBoost untuk prediksi konsumsi pangan meliputi kemampuan menangkap hubungan non-linear kompleks antara fitur tanpa memerlukan asumsi distribusi, robustness terhadap outliers melalui regularisasi dan tree-based partitioning, serta efisiensi komputasi melalui implementasi paralel dan optimasi memori. XGBoost juga menyediakan feature importance yang membantu interpretabilitas model dengan mengidentifikasi fitur paling berpengaruh terhadap prediksi.

## 2.5 HuberRegressor

HuberRegressor merupakan model regresi linear yang menggunakan Huber loss function, sebuah fungsi loss yang menggabungkan karakteristik MSE untuk error kecil dan MAE untuk error besar. Model ini dikembangkan oleh Peter J. Huber pada tahun 1964 sebagai metode estimasi robust terhadap outliers [33].

Huber loss dirancang untuk memberikan keseimbangan antara sensitivitas MSE terhadap error kecil dan robustness MAE terhadap outliers. Fungsi ini berperilaku kuadratik untuk error yang lebih kecil dari threshold δ (epsilon) dan linear untuk error yang lebih besar. Persamaan (10) menunjukkan formula Huber loss.

**Persamaan (10):**

```
       ⎧ (1/2)r²                untuk |r| ≤ δ
ℒ_δ(r) = ⎨
       ⎩ δ|r| - (1/2)δ²        untuk |r| > δ
```

dimana r adalah residual (y - ŷ) dan δ adalah parameter threshold yang menentukan titik transisi antara perilaku kuadratik dan linear.

Untuk error kecil (|r| ≤ δ), fungsi berperilaku seperti MSE yang memberikan penalti proporsional dengan kuadrat error. Hal ini membuat model sensitif terhadap perbedaan kecil dalam prediksi, mendorong akurasi tinggi pada mayoritas data. Untuk error besar (|r| > δ), fungsi berperilaku seperti MAE yang memberikan penalti linear. Hal ini mencegah outliers mendominasi optimisasi dan menyebabkan overfitting pada nilai ekstrem.

Parameter epsilon menentukan threshold δ dalam Huber loss function. Pemilihan nilai epsilon mempengaruhi trade-off antara akurasi pada data normal dan robustness terhadap outliers. Nilai epsilon kecil membuat model lebih sensitif terhadap error dan berperilaku lebih mirip MSE, sedangkan nilai epsilon besar membuat model lebih robust dan berperilaku lebih mirip MAE. Nilai standar 1,35 dipilih karena meminimalkan varians estimator untuk distribusi normal.

Parameter alpha mengontrol kekuatan regularisasi L2 (ridge regression) yang ditambahkan ke fungsi objektif. Regularisasi mencegah overfitting dengan memberikan penalti pada magnitude koefisien, mendorong model untuk menggunakan informasi dari banyak fitur secara moderat daripada bergantung kuat pada beberapa fitur. Parameter max_iter membatasi jumlah iterasi algoritma optimisasi, umumnya menggunakan coordinate descent untuk meminimalkan fungsi objektif.

HuberRegressor efektif pada dataset dengan keberadaan outliers yang tidak dapat dihilangkan karena merepresentasikan kejadian valid. Dalam konteks prediksi konsumsi pangan, outliers dapat muncul dari peristiwa eksternal seperti krisis ekonomi yang menyebabkan perubahan drastis pola konsumsi, fenomena iklim ekstrem yang mempengaruhi produksi dan harga, atau kebijakan pemerintah yang mengubah aksesibilitas komoditas tertentu.

Model robust seperti HuberRegressor dapat memberikan prediksi yang stabil tanpa terdistorsi oleh nilai ekstrem, mempertahankan akurasi pada mayoritas data normal sambil tidak overfitting pada outliers. Hal ini penting untuk perencanaan ketahanan pangan yang memerlukan prediksi konservatif dan dapat diandalkan [34].

## 2.6 Ensemble Learning

Ensemble learning merupakan paradigma machine learning yang menggabungkan prediksi dari beberapa model untuk menghasilkan prediksi final yang lebih akurat dan robust dibandingkan model individual. Prinsip dasar ensemble adalah bahwa kombinasi model yang beragam dapat saling melengkapi kekuatan dan mengkompensasi kelemahan masing-masing [35].

Terdapat beberapa pendekatan utama dalam ensemble learning. Bagging (Bootstrap Aggregating) melatih beberapa model pada subset data yang diambil secara acak dengan penggantian, kemudian menggabungkan prediksi melalui voting (klasifikasi) atau rata-rata (regresi). Random Forest merupakan contoh bagging yang menggunakan decision trees sebagai base learners [36].

Boosting melatih model secara sekuensial dimana setiap model baru fokus memperbaiki kesalahan model sebelumnya. Gradient Boosting, XGBoost, dan AdaBoost merupakan implementasi populer dari pendekatan ini. Boosting umumnya menghasilkan akurasi lebih tinggi dibandingkan bagging namun lebih rentan terhadap overfitting jika tidak diregularisasi dengan baik.

Stacking melatih model meta-learner yang belajar menggabungkan prediksi dari beberapa base learners. Base learners dapat berupa model heterogen dengan arsitektur berbeda, dan meta-learner mempelajari bobot atau fungsi kombinasi optimal. Weighted averaging merupakan bentuk sederhana dari stacking dimana prediksi final adalah rata-rata tertimbang dari prediksi individual.

Weighted averaging menggabungkan prediksi dari beberapa model dengan memberikan bobot berbeda pada setiap model berdasarkan performanya. Model dengan akurasi lebih tinggi diberi bobot lebih besar sehingga kontribusinya terhadap prediksi final lebih dominan. Persamaan (11) menunjukkan formula weighted averaging untuk ensemble.

**Persamaan (11):**

```
ŷ_ensemble = Σ_{i=1}^M w_i ŷ_i
```

dimana ŷ_ensemble adalah prediksi ensemble, M adalah jumlah model, w_i adalah bobot untuk model i, dan ŷ_i adalah prediksi dari model i. Constraint yang berlaku pada Persamaan (12).

**Persamaan (12):**

```
Σ_{i=1}^M w_i = 1, w_i ≥ 0
```

Untuk kasus tiga model (LSTM, XGBoost, dan HuberRegressor), prediksi ensemble dapat dinyatakan dalam Persamaan (13).

**Persamaan (13):**

```
ŷ_ensemble = w_1 ŷ_LSTM + w_2 ŷ_XGB + w_3 ŷ_Huber
subject to: w_1 + w_2 + w_3 = 1, w_i ≥ 0
```

Penentuan bobot optimal dapat dilakukan melalui berbagai metode. Pendekatan sederhana adalah bobot seragam dimana semua model diberi bobot sama. Pendekatan berdasarkan performa validation menggunakan metrik seperti RMSE atau MAPE pada data validasi untuk menentukan bobot, dimana model dengan error lebih kecil diberi bobot lebih besar. Pendekatan optimisasi global menggunakan algoritma seperti grid search, Differential Evolution, atau Bayesian Optimization untuk mencari kombinasi bobot yang meminimalkan error pada data validasi.

Fungsi objektif yang diminimalkan dalam optimisasi bobot adalah MSE antara prediksi ensemble dan nilai aktual, seperti ditunjukkan pada Persamaan (14).

**Persamaan (14):**

```
minimize: f(w_1, w_2, w_3) = (1/n) Σ_{i=1}^n (y_i - ŷ_{ensemble,i})²
```

Differential Evolution (DE) merupakan algoritma optimisasi global berbasis populasi yang dikembangkan oleh Storn dan Price pada tahun 1997. DE dirancang untuk menyelesaikan masalah optimisasi kontinu pada ruang pencarian multi-dimensi, efektif untuk fungsi objektif non-konveks, non-differentiable, atau multimodal dimana metode gradient-based dapat terjebak di lokal minima [37].

Algoritma DE bekerja dengan mempertahankan populasi solusi kandidat dan mengevolusinya melalui operasi mutasi, crossover, dan seleksi. Pada setiap generasi, vektor baru dibuat melalui mutasi dengan menambahkan perbedaan tertimbang antara dua vektor populasi ke vektor ketiga. Vektor hasil mutasi kemudian dikombinasikan dengan vektor target melalui crossover. Vektor hasil crossover dibandingkan dengan vektor target, dan yang lebih baik dipertahankan untuk generasi berikutnya.

Keunggulan DE untuk optimisasi bobot ensemble meliputi kemampuan menemukan optimum global tanpa terjebak di lokal minima, tidak memerlukan turunan fungsi objektif sehingga cocok untuk black-box optimization, serta implementasi sederhana dengan sedikit hyperparameter yang perlu disetel. DE telah berhasil diterapkan pada berbagai masalah optimisasi termasuk hyperparameter tuning, neural architecture search, dan optimisasi bobot ensemble.

Selain weighted averaging, strategi conditional ensemble dapat diterapkan untuk mengoptimalkan performa berdasarkan skala nilai prediksi. Strategi ini memilih model terbaik berdasarkan threshold tertentu, seperti ditunjukkan pada Persamaan (15).

**Persamaan (15):**

```
       ⎧ ŷ_XGB              jika y < 5000
ŷ_final = ⎨
       ⎩ ŷ_ensemble         jika y ≥ 5000
```

dimana threshold ditentukan melalui evaluasi berbagai kandidat berdasarkan metrik validasi seperti MAPE.

## 2.7 Feature Engineering

Feature engineering merupakan proses menciptakan variabel baru dari data mentah yang lebih informatif untuk model machine learning. Pada data time series, feature engineering bertujuan mengekstrak pola temporal yang dapat membantu model memahami tren, musiman, dan dependencies. Sebelum proses pembuatan fitur dilakukan, data perlu dibersihkan dari nilai-nilai outlier ekstrem yang dapat mendistorsi pola dan mengurangi kualitas model [38].

Outlier merupakan nilai yang secara signifikan berbeda dari sebagian besar data dalam distribusi. Dalam konteks data time series konsumsi pangan, outlier dapat muncul dari dua sumber: anomali valid yang disebabkan oleh peristiwa eksternal seperti krisis ekonomi, bencana alam, atau pandemi; dan error pencatatan atau pengukuran yang menghasilkan nilai tidak realistis [39].

Metode Interquartile Range (IQR) merupakan teknik statistik robust untuk deteksi outlier yang tidak terpengaruh oleh nilai ekstrem. Metode ini menggunakan kuartil pertama (Q₁) dan kuartil ketiga (Q₃) untuk menentukan batas nilai yang dianggap normal. IQR didefinisikan sebagai selisih antara Q₃ dan Q₁, merepresentasikan rentang 50% data tengah dalam distribusi [40].

Threshold standar untuk deteksi outlier menggunakan 1,5 × IQR, namun untuk data time series dengan variasi musiman tinggi seperti konsumsi pangan, threshold ini cenderung terlalu agresif dan dapat mengeliminasi fluktuasi seasonal yang merupakan pola alamiah. Threshold 3 × IQR memberikan pendekatan lebih konservatif yang mampu mempertahankan variasi musiman sambil mengeliminasi anomali ekstrem yang benar-benar tidak wajar. Persamaan (16) menunjukkan formula deteksi outlier dengan threshold 3 × IQR.

**Persamaan (16):**

```
Lower Bound = Q_1 - 3 × IQR
Upper Bound = Q_3 + 3 × IQR
IQR = Q_3 - Q_1
```

dimana Q₁ adalah kuartil pertama (persentil ke-25), Q₃ adalah kuartil ketiga (persentil ke-75), dan IQR adalah interquartile range. Nilai yang berada di luar rentang [Lower Bound, Upper Bound] dikategorikan sebagai outlier ekstrem.

Pemilihan threshold 3 × IQR didasarkan pada karakteristik data konsumsi pangan yang memiliki pola musiman kuat. Threshold lebih besar memberikan toleransi terhadap lonjakan konsumsi pada periode tertentu seperti Ramadan atau hari raya, yang merupakan pola konsumsi valid dan bukan anomali. Pendekatan ini memastikan model dapat belajar dari variasi musiman tanpa terdistorsi oleh error pencatatan atau kejadian ekstrem yang tidak berulang.

Lag features menangkap ketergantungan temporal dengan memasukkan nilai dari periode sebelumnya sebagai fitur untuk prediksi periode saat ini. Lag features memungkinkan model memahami bahwa nilai saat ini dipengaruhi oleh nilai masa lalu. Persamaan (17) mendefinisikan lag feature.

**Persamaan (17):**

```
x_lag_k(t) = x(t - k)
```

dimana x_lag_k(t) adalah nilai lag k pada waktu t, dan x(t-k) adalah nilai observasi pada waktu t-k.

Pemilihan jumlah dan ukuran lag bergantung pada karakteristik data. Analisis autokorelasi mengidentifikasi lag mana yang memiliki korelasi signifikan dengan nilai saat ini. Lag dengan korelasi tinggi memberikan informasi prediktif paling berharga dan diprioritaskan sebagai fitur [41].

Rolling window features atau moving statistics menghitung statistik agregat pada jendela waktu bergerak, menangkap tren lokal dan variabilitas. Rolling mean menghaluskan fluktuasi jangka pendek dan mengidentifikasi tren. Persamaan (18) mendefinisikan rolling mean.

**Persamaan (18):**

```
RollingMean_w(t) = (1/w) Σ_{i=0}^{w-1} x(t - i)
```

dimana w adalah ukuran window dan x(t-i) adalah nilai pada waktu t-i.

Rolling standard deviation mengukur volatilitas atau variabilitas dalam window waktu tertentu. Persamaan (19) mendefinisikan rolling standard deviation.

**Persamaan (19):**

```
RollingStd_w(t) = √((1/w) Σ_{i=0}^{w-1} (x(t - i) - RollingMean_w(t))²)
```

Statistik rolling lainnya yang berguna meliputi rolling minimum dan rolling maximum untuk mengidentifikasi ekstrem lokal, rolling median yang lebih robust terhadap outliers dibandingkan rolling mean, serta rolling skewness dan rolling kurtosis untuk menangkap perubahan bentuk distribusi.

Fitur temporal seperti bulan, hari dalam seminggu, atau jam memiliki sifat siklikal dimana nilai awal dan akhir periode berdekatan secara konseptual. Encoding numerik standar menciptakan diskontinuitas artifisial yang dapat mengacaukan model. Cyclical encoding menggunakan transformasi trigonometri untuk mempertahankan kontinuitas siklikal. Persamaan (20) dan (21) menunjukkan cyclical encoding untuk bulan.

**Persamaan (20):**

```
month_sin = sin(2π × month / 12)
```

**Persamaan (21):**

```
month_cos = cos(2π × month / 12)
```

Transformasi ini memetakan nilai bulan (1-12) ke koordinat pada lingkaran unit di ruang 2 dimensi. Bulan yang berdekatan dalam kalender memiliki koordinat yang berdekatan dalam ruang sin-cos, mempertahankan informasi kedekatan temporal. Desember (bulan 12) dan Januari (bulan 1) memiliki jarak Euclidean yang kecil dalam ruang sin-cos, mencerminkan kedekatan kalender mereka.

## 2.8 Data Preprocessing

Data preprocessing merupakan tahap krusial dalam machine learning pipeline yang mempersiapkan data mentah menjadi format yang optimal untuk pemodelan. Tahap ini mencakup normalisasi fitur, penanganan missing values, dan pembagian dataset. Kualitas preprocessing secara langsung mempengaruhi performa model dan kemampuan generalisasi pada data baru [42].

Normalisasi merupakan proses transformasi fitur ke rentang nilai standar untuk memastikan setiap fitur berkontribusi proporsional terhadap pembelajaran model. Fitur dengan rentang nilai besar (misalnya populasi dalam jutaan) dapat mendominasi fitur dengan rentang kecil (misalnya suhu dalam puluhan derajat) dalam algoritma berbasis jarak seperti neural network. Normalisasi mengatasi masalah ini dengan menyamakan skala seluruh fitur.

MinMaxScaler merupakan metode normalisasi yang mentransformasi setiap fitur ke rentang [0, 1] dengan mempertahankan distribusi asli data. Metode ini bekerja dengan mengurangi nilai minimum kemudian membagi dengan rentang (maksimum - minimum). MinMaxScaler dipilih karena kompatibel dengan fungsi aktivasi neural network seperti sigmoid dan tanh yang bekerja optimal pada input dalam rentang terbatas [30]. Persamaan (22) mendefinisikan transformasi MinMaxScaler.

**Persamaan (22):**

```
x_scaled = (x - x_min) / (x_max - x_min)
```

dimana x adalah nilai asli fitur, x_min adalah nilai minimum fitur dalam dataset training, x_max adalah nilai maksimum fitur dalam dataset training, dan x_scaled adalah nilai hasil normalisasi dalam rentang [0, 1].

Proses normalisasi harus dilakukan dengan hati-hati untuk mencegah data leakage. Scaler harus di-fit (menghitung x_min dan x_max) hanya pada subset training, kemudian parameter yang sama ditransformasi ke subset validation dan testing. Pendekatan ini memastikan model tidak memiliki informasi tentang distribusi data uji selama training, menjaga validitas evaluasi.

StandardScaler merupakan alternatif normalisasi yang mentransformasi fitur ke distribusi dengan mean 0 dan standard deviation 1. Metode ini lebih robust terhadap outlier dibandingkan MinMaxScaler karena tidak bergantung pada nilai ekstrem [43].

## 2.9 Metrik Evaluasi

Evaluasi model regresi menggunakan berbagai metrik yang mengukur seberapa dekat prediksi model dengan nilai aktual. Setiap metrik memiliki karakteristik dan interpretasi berbeda, memberikan perspektif yang melengkapi tentang performa model [44].

MAE mengukur rata-rata magnitude error absolut antara prediksi dan nilai aktual. MAE memberikan perlakuan sama untuk semua error tanpa mempertimbangkan arah error (positif atau negatif). Persamaan (23) mendefinisikan MAE.

**Persamaan (23):**

```
MAE = (1/n) Σ_{i=1}^n |y_i - ŷ_i|
```

dimana n adalah jumlah observasi, y_i adalah nilai aktual, dan ŷ_i adalah nilai prediksi.

MAE memiliki satuan yang sama dengan variabel target, memudahkan interpretasi langsung. Metrik ini robust terhadap outliers karena tidak mengkuadratkan error. Namun, MAE tidak memberikan informasi tentang arah bias prediksi (apakah model cenderung overpredict atau underpredict).

RMSE mengukur akar dari rata-rata kuadrat error, memberikan penalti lebih besar pada error yang lebih besar melalui operasi kuadrat. Persamaan (24) mendefinisikan RMSE.

**Persamaan (24):**

```
RMSE = √((1/n) Σ_{i=1}^n (y_i - ŷ_i)²)
```

RMSE juga memiliki satuan yang sama dengan variabel target. Metrik ini lebih sensitif terhadap outliers dibandingkan MAE karena error dikuadratkan sebelum dirata-rata. RMSE berguna ketika error besar sangat tidak diinginkan dan harus diminimalkan. Namun, sensitivitas terhadap outliers dapat membuat RMSE tidak representatif jika data mengandung banyak nilai ekstrem yang valid.

MAPE mengukur error sebagai persentase dari nilai aktual, memungkinkan perbandingan performa antar dataset atau variabel dengan skala berbeda [45]. Persamaan (25) mendefinisikan MAPE.

**Persamaan (25):**

```
MAPE = (100%/n) Σ_{i=1}^n |((y_i - ŷ_i) / y_i)|
```

MAPE memberikan interpretasi intuitif dalam bentuk persentase, memudahkan komunikasi dengan stakeholder non-teknis. Metrik ini skala-independen sehingga dapat membandingkan performa prediksi komoditas dengan konsumsi berbeda. Namun, MAPE memiliki keterbatasan yaitu tidak terdefinisi ketika nilai aktual nol dan memberikan penalti asimetris dimana underpredict memiliki penalti lebih besar dibandingkan overpredict untuk magnitude error yang sama.

R² mengukur proporsi varians variabel target yang dijelaskan oleh model. Nilai R² berkisar antara negatif tak hingga hingga 1, dimana 1 menunjukkan model menjelaskan seluruh varians. Persamaan (26) mendefinisikan R².

**Persamaan (26):**
R² = 1 - (Σ*{i=1}^n (y_i - ŷ_i)²) / (Σ*{i=1}^n (y_i - ȳ)²)

dimana ȳ adalah rata-rata nilai aktual.

R² memberikan ukuran kecocokan model secara keseluruhan. Nilai mendekati 1 mengindikasikan model menangkap sebagian besar variabilitas data, sedangkan nilai mendekati 0 menunjukkan model tidak lebih baik dari prediksi rata-rata sederhana. Nilai negatif mengindikasikan model bahkan lebih buruk dari baseline rata-rata. R² berguna untuk membandingkan model berbeda pada dataset yang sama, namun tidak memberikan informasi tentang magnitude error dalam satuan asli.

## 2.10 Penelitian Sejenis

Beberapa penelitian sejenis telah mengeksplorasi aplikasi machine learning untuk prediksi konsumsi pangan dan ketahanan pangan dengan berbagai pendekatan dan metodologi. Perbandingan penelitian sejenis yang relevan dengan penelitian ini disajikan pada Tabel 1.

**Tabel 1. Penelitian Sejenis**

| Peneliti (Tahun)                            | Metode                       | Hasil (MAPE)              | Data                                                           | Gap/Keterbatasan                                                                                                                                            |
| ------------------------------------------- | ---------------------------- | ------------------------- | -------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Rice Novita, Indri Yani, Gunawan Ali (2022) | Linear Regression            | 12.4%                     | Pemesanan obat Rumah Sakit Jan 2018-Okt 2019 (22 bulan)        | Tidak dapat menangkap pola non-linear; tidak dirancang untuk temporal dependencies; tidak ada robust regression untuk outliers; feature engineering minimal |
| Ilham Amansyah, Jamaludin Indra (2024)      | Linear Regression            | 12.47%                    | Penjualan mobil Toyota 2018-2023 (72 bulan)                    | Tidak menangkap faktor musiman; tidak ada variabel eksternal; rentan outliers; MSE/RMSE tinggi                                                              |
| Danang Arifuddin, Kusrini, Kusnawi (2025)   | MLR & MLPNN                  | 22.3% (MLPNN) 25.4% (MLR) | Penjualan obat Jan-Jun 2024 (182 hari)                         | Dataset pendek (6 bulan); underfitting pada kedua model; korelasi variabel sangat lemah (<0.35); akurasi rendah; MSE sangat tinggi (>19,000)                |
| I Komang Krisnata Kanaya (2025)             | Single Exponential Smoothing | 29%                       | Kedatangan kapal pesiar PT. Pelabuhan Indonesia 2025 (bulanan) | Tidak menangkap pola musiman; akurasi menurun tajam pada periode volatilitas tinggi (Juli: 54%, November: 46%); hanya cocok untuk data stasioner            |
| Jehian Athaya Tsani Az Zuhry (2026)         | LSTM Enhanced Ensemble       | Target <10%               | NBM Indonesia 1994-2024 (360 bulan)                            | Mengatasi gap penelitian sebelumnya dengan kombinasi sequence modeling dan outlier resistance                                                               |

---

# BAB III METODE PENELITIAN

## 3.1 Waktu dan Tempat Penelitian

Penelitian ini dilaksanakan di Pusat Data dan Sistem Informasi Pertanian (Pusdatin) Kementerian Pertanian Republik Indonesia yang berlokasi di Jl. Harsono RM No.3, Ragunan, Pasar Minggu, Kota Jakarta Selatan, Daerah Khusus Jakarta 12550. Waktu penelitian dimulai pada bulan September 2025 sampai bulan Desember 2025.

Dalam pelaksanaan penelitian, dilakukan diskusi dengan stakeholder Pusdatin Kementerian Pertanian untuk memahami kebutuhan sistem prediksi konsumsi kalori dalam konteks perencanaan ketahanan pangan nasional serta validasi pendekatan metodologi yang akan digunakan dalam penelitian. Dokumentasi diskusi dengan stakeholder ditunjukkan pada Gambar 2.

![Gambar 2. Dokumentasi Diskusi dengan Stakeholder Pusdatin Kementerian Pertanian]

## 3.2 Data dan Alat Penelitian

### 3.2.1 Data Penelitian

Penelitian ini menggunakan data Neraca Bahan Makanan (NBM) Indonesia yang diperoleh dari Pusat Data dan Sistem Informasi Pertanian Kementerian Pertanian Republik Indonesia. Data NBM merupakan data yang telah divalidasi dan diverifikasi oleh Kementerian Pertanian untuk penggunaan penelitian. Tabel 2 menunjukkan karakteristik dataset NBM yang digunakan dalam penelitian.

**Tabel 2. Karakteristik Dataset Neraca Bahan Makanan Indonesia**

| Aspek            | Deskripsi                    |
| ---------------- | ---------------------------- |
| Periode data     | 1993 - 2024 (31,92 tahun)    |
| Total records    | 48.696 baris data            |
| Jumlah komoditas | 114 komoditas                |
| Resolusi Waktu   | Bulanan dan kuartalan        |
| Format data      | CSV (Comma Separated Values) |

Dataset mencakup 10 kelompok komoditas pangan utama: Padi-Padian, Makanan Berpati, Gula, Buah/Biji Berminyak, Buah-buahan, Sayur-sayuran, Daging, Telur, Susu, serta Minyak dan Lemak. Setiap kelompok komoditas memiliki karakteristik konsumsi yang berbeda dengan pola musiman dan tren spesifik.

Parameter data yang tersedia dalam dataset NBM meliputi beberapa kategori. Kategori produksi dan distribusi mencakup data produksi dalam satuan ton, volume impor dan ekspor dalam ton, serta perubahan stok dalam ton. Kategori penggunaan komoditas terdiri dari alokasi untuk pakan ternak, bibit, konsumsi makanan, penggunaan non-makanan, dan bahan bukan makanan, seluruhnya dalam satuan ton. Kategori ekonomi memuat informasi harga produsen dan harga konsumen dalam Rupiah, serta tingkat inflasi komoditi dalam persentase. Kategori demografis berisi data populasi Indonesia dalam satuan jiwa. Kategori iklim mencakup data curah hujan dalam milimeter dan suhu rata-rata dalam derajat Celsius. Kategori pertanian terdiri dari luas panen dalam hektar dan produktivitas dalam ton per hektar. Tabel 3 merangkum distribusi parameter data berdasarkan kategori.

**Tabel 3. Distribusi Parameter Data Berdasarkan Kategori**

| Kategori                | Parameter                                         | Satuan          |
| ----------------------- | ------------------------------------------------- | --------------- |
| Produksi dan Distribusi | Produksi, Impor, Ekspor, Perubahan Stok           | Ton             |
| Penggunaan              | Pakan, Bibit, Makanan, Non-makanan, Bukan Makanan | Ton             |
| Ekonomi                 | Harga Produsen, Harga Konsumen                    | Rupiah          |
|                         | Inflasi Komoditi                                  | Persen          |
| Demografis              | Populasi Indonesia                                | Jiwa            |
| Iklim                   | Curah Hujan                                       | Milimeter       |
|                         | Suhu Rata-rata                                    | Derajat Celsius |
| Pertanian               | Luas Panen                                        | Hektar          |
|                         | Produktivitas                                     | Ton/Hektar      |

Variabel target penelitian adalah konsumsi kalori per kapita per hari yang diturunkan dari parameter bahan makanan (dalam ribu ton) dan kandungan kalori per 100 gram setiap komoditas. Pembagian dataset dilakukan secara kronologis untuk mempertahankan temporal order dan mencegah data leakage dalam validasi time series forecasting dengan proporsi 70:15:15 untuk subset training, validation, dan testing.

### 3.2.2 Alat Penelitian

Penelitian ini melibatkan sejumlah perangkat keras dan perangkat lunak sebagai komponen penting dalam mendukung proses data preparation, feature engineering, pengembangan model machine learning, hingga tahap implementasi sistem dan pengujian.

**1. Perangkat Keras**

Pengembangan dan pengujian sistem dilakukan menggunakan laptop MSI GF63 Thin 10UC. Spesifikasi processor menggunakan Intel Core i5-10500H dengan 6 cores dan 12 logical processors, base speed 2,50 GHz yang dapat mencapai 4,50 GHz melalui turbo boost. Memori sistem berkapasitas 16 GB RAM DDR4 dengan kecepatan 2933 MT/s. Penyimpanan menggunakan SSD KINGSTON OM8PCP3512F-AI1 berkapasitas 477 GB yang memberikan kecepatan akses data tinggi untuk operasi machine learning. Graphics Processing Unit menggunakan NVIDIA GeForce RTX 3050 Laptop GPU dengan memori dedicated 4 GB GDDR6 dan 2048 CUDA cores yang mendukung akselerasi komputasi deep learning. Sistem operasi yang digunakan adalah Windows 11 Pro 64-bit. Koneksi internet diperlukan untuk akses repositori library, dokumentasi, dan layanan cloud computing.

**2. Perangkat Lunak**

Perangkat lunak yang digunakan dalam penelitian ini meliputi development environment, database management, dan documentation tools. Visual Studio Code berfungsi sebagai Integrated Development Environment (IDE) utama untuk penulisan kode Python, pengembangan web, dan manajemen proyek. Google Colab digunakan sebagai platform komputasi cloud dengan akses GPU gratis untuk training model deep learning yang intensif. Python versi 3.8 atau lebih tinggi menjadi bahasa pemrograman utama untuk data processing, machine learning, dan pengembangan API. MySQL digunakan sebagai sistem manajemen basis data relasional untuk penyimpanan data NBM dan hasil prediksi. phpMyAdmin menyediakan antarmuka web untuk administrasi database MySQL. Docker digunakan untuk containerization aplikasi memastikan konsistensi deployment antar lingkungan. Git berfungsi sebagai sistem kontrol versi untuk manajemen kode dan kolaborasi. Microsoft Word digunakan untuk penyusunan dokumentasi penelitian dan laporan.

**3. Teknologi dan Framework**

Berbagai teknologi dan framework digunakan untuk mendukung proses pengembangan model machine learning dan sistem informasi. Teknologi dikelompokkan menjadi lima kategori: machine learning, data processing, web development, database, dan deployment.

Teknologi machine learning mencakup TensorFlow/Keras sebagai framework untuk pengembangan model deep learning khususnya LSTM dengan dukungan GPU acceleration. Scikit-learn menyediakan implementasi algoritma machine learning klasik seperti HuberRegressor, preprocessing tools, dan metrik evaluasi. XGBoost digunakan sebagai implementasi gradient boosting yang efisien untuk model tree-based ensemble.

Teknologi data processing terdiri dari Pandas untuk manipulasi dan analisis data tabular dengan struktur DataFrame. NumPy menyediakan operasi array multidimensi dan fungsi matematika untuk komputasi numerik. Matplotlib digunakan untuk visualisasi data statis seperti line plot, scatter plot, dan histogram. Seaborn memperluas Matplotlib dengan visualisasi statistik tingkat tinggi dan tema estetis.

Teknologi web development menggunakan Laravel sebagai framework PHP dengan arsitektur MVC (Model-View-Controller) untuk pengembangan aplikasi web. Livewire menyediakan komponen reaktif berbasis server untuk interaktivitas tanpa JavaScript kompleks. FastAPI berfungsi sebagai framework modern untuk pengembangan API dengan performa tinggi dan dokumentasi otomatis. Uvicorn digunakan sebagai ASGI server untuk menjalankan aplikasi FastAPI dengan dukungan asynchronous.

Teknologi database terdiri dari MySQL sebagai sistem manajemen basis data relasional untuk penyimpanan persisten. Eloquent ORM (Object-Relational Mapping) menyediakan antarmuka berorientasi objek untuk interaksi dengan database dalam Laravel.

Teknologi deployment menggunakan Docker untuk containerization aplikasi memastikan portabilitas dan isolasi lingkungan. Docker Compose mengorkestrasikan multi-container deployment untuk web service, API service, dan database. Git berfungsi untuk kontrol versi dan integrasi dengan platform seperti GitHub untuk continuous integration.

## 3.3 Tahapan Penelitian

Penelitian ini menggunakan framework Cross-Industry Standard Process for Data Mining (CRISP-DM) sebagai metodologi pengembangan model machine learning. CRISP-DM dipilih karena menyediakan struktur sistematis dan iteratif untuk proyek data mining dan machine learning, mulai dari pemahaman masalah bisnis hingga deployment sistem. Framework ini terdiri dari enam fase utama yang saling terkait: Pemahaman Bisnis (Business Understanding), Pemahaman Data (Data Understanding), Persiapan Data (Data Preparation), Pemodelan (Modeling), Evaluasi (Evaluation), dan Penyebaran (Deployment). Gambar 3 menunjukkan diagram alur penelitian dengan framework CRISP-DM.

![Gambar 3. Alur CRISP-DM]

**1. Pemahaman Bisnis**

Fase pemahaman bisnis dimulai dengan identifikasi masalah ketahanan pangan Indonesia berdasarkan analisis kondisi aktual dan data pendukung. Analisis dilakukan terhadap posisi Indonesia dalam Global Food Security Index, keterbatasan metode prediksi konvensional dalam menangkap kompleksitas pola temporal konsumsi pangan, serta dampak ketidakakuratan prediksi terhadap inefisiensi distribusi dan kerugian ekonomi.

Berdasarkan identifikasi masalah tersebut, tujuan penelitian ditetapkan untuk mengembangkan model LSTM Enhanced Ensemble yang mampu memprediksi konsumsi kalori per kapita per hari untuk setiap komoditas pangan dengan akurasi tinggi dan mengintegrasikan model ke dalam sistem informasi berbasis web. Kriteria keberhasilan penelitian mencakup target metrik evaluasi seperti MAPE, MAE, dan RMSE, robustness terhadap periode volatilitas, serta response time sistem yang memadai untuk penggunaan praktis.

**2. Pemahaman Data**

Fase pemahaman data bertujuan memahami karakteristik data NBM Indonesia secara mendalam melalui eksplorasi statistik dan analisis pola temporal. Eksplorasi data awal dilakukan untuk mengidentifikasi struktur dataset, distribusi konsumsi kalori, dan kontribusi per kelompok komoditas.

Analisis statistik deskriptif dilakukan untuk memahami karakteristik distribusi konsumsi bahan makanan, mengidentifikasi nilai minimum, maksimum, median, mean, dan kuartil. Analisis kontribusi per kelompok komoditas dilakukan untuk mengetahui dominasi kelompok tertentu terhadap total kalori nasional dan pola musiman spesifik setiap kelompok.

Identifikasi kualitas data dilakukan melalui deteksi missing values pada berbagai fitur untuk menentukan strategi penanganan yang tepat, serta deteksi outliers menggunakan metode IQR untuk membedakan outliers wajar dari anomali ekstrem. Analisis pola temporal dilakukan untuk mengidentifikasi tren jangka panjang konsumsi kalori nasional, periode dengan volatilitas tinggi, dan periode anomali yang disebabkan oleh peristiwa krisis seperti krisis moneter, krisis finansial global, fenomena El Niño, dan pandemi COVID-19.

**3. Persiapan Data**

Fase persiapan data melibatkan transformasi data mentah menjadi kumpulan data yang siap untuk pemodelan dengan mempertimbangkan temporal order untuk mencegah data leakage. Proses ini terdiri dari pembersihan data, feature engineering, feature scaling, dan pembagian dataset.

Pembersihan data dilakukan untuk menangani missing values dan outliers. Penanganan missing values menggunakan strategi berbeda berdasarkan proporsi kehilangan data: eliminasi untuk fitur dengan missing values tinggi, dan imputasi median per komoditas untuk fitur dengan missing values rendah. Outliers treatment menggunakan metode IQR dengan threshold yang dimodifikasi untuk mempertahankan outliers wajar dari peristiwa krisis sambil mengeliminasi outliers ekstrem.

Seleksi periode data dilakukan dengan memfilter hanya data bulanan untuk mempertahankan konsistensi interval temporal, serta mengeliminasi records dengan nilai konsumsi kosong atau nol. Proses penggabungan dataset transaksi dan komoditas dilakukan berdasarkan kunci kode_komoditi untuk menghasilkan dataset lengkap dengan informasi nilai gizi.

Feature engineering dilakukan untuk menciptakan variabel prediktif yang lebih informatif. Lag features dibuat untuk menangkap ketergantungan temporal dengan memasukkan nilai konsumsi dari beberapa periode sebelumnya. Rolling window features dibuat untuk menangkap tren dan volatilitas melalui statistik agregat seperti mean dan standard deviation pada window waktu tertentu. Cyclical encoding diterapkan pada fitur bulan menggunakan transformasi sinus dan kosinus untuk mempertahankan kontinuitas temporal. Label encoding diterapkan pada fitur kategorikal kode_komoditi untuk konversi ke nilai numerik.

Feature scaling diterapkan menggunakan MinMaxScaler untuk menormalisasi rentang nilai fitur ke skala seragam. Normalisasi dilakukan secara terpisah untuk fitur dan target, dengan scaler di-fit pada subset training kemudian ditransformasi ke subset validation dan testing. Data dinormalisasi di-reshape ke format 3 dimensi untuk keperluan model LSTM.

Pembagian dataset dilakukan secara kronologis berdasarkan kuantil temporal dengan proporsi 70:15:15 untuk subset training, validation, dan testing. Subset training digunakan untuk pembelajaran pola konsumsi, subset validation untuk hyperparameter tuning dan optimasi bobot ensemble, sedangkan subset testing untuk evaluasi final performa model.

**4. Pemodelan**

Fase pemodelan mengembangkan empat arsitektur model untuk perbandingan dan ensemble: XGBoost sebagai baseline, LSTM untuk menangkap pola temporal, HuberRegressor untuk robustness terhadap outliers, dan LSTM Enhanced Ensemble yang menggabungkan kekuatan ketiga model.

Model XGBoost dikembangkan dengan hyperparameter tuning untuk mengoptimalkan performa pada data tabular. Konfigurasi hyperparameter meliputi jumlah tree, kedalaman maksimal, learning rate, subsample ratio, dan column sampling ratio. Early stopping diterapkan untuk mencegah overfitting.

Model LSTM dibangun dengan arsitektur deep stacked menggunakan beberapa lapisan LSTM dengan konfigurasi pyramid. Setiap lapisan LSTM diikuti oleh Dropout untuk regularisasi. Lapisan Dense ditambahkan untuk ekstraksi fitur tingkat tinggi sebelum lapisan output. Model dikompilasi dengan optimizer Adam, fungsi loss MSE, dan metrik evaluasi MAE. Early stopping diterapkan berdasarkan validation loss.

Model HuberRegressor diimplementasikan dengan parameter epsilon untuk threshold transisi antara loss kuadratik dan linear, parameter iterasi maksimal, dan parameter regularisasi. Model dilatih pada data yang telah dinormalisasi.

Model LSTM Enhanced Ensemble menggabungkan prediksi dari ketiga model menggunakan strategi weighted averaging dengan bobot optimal. Optimasi bobot dilakukan menggunakan algoritma Differential Evolution dengan fungsi objektif MSE pada subset validation. Strategi conditional ensemble diterapkan dengan membagi prediksi berdasarkan threshold nilai konsumsi, menggunakan XGBoost untuk nilai kecil dan weighted ensemble untuk nilai besar. Threshold dipilih melalui evaluasi berbagai kandidat pada subset validation.

**5. Evaluasi**

Fase evaluasi mengevaluasi model menggunakan beberapa metrik untuk mendapatkan gambaran lengkap tentang performa. Metrik evaluasi meliputi RMSE untuk mengukur magnitude error, MAE untuk mengukur average absolute deviation, MAPE sebagai metrik utama untuk akurasi relatif, dan R² untuk mengukur kecocokan model.

Evaluasi dilakukan pada beberapa skenario: evaluasi pada seluruh data uji untuk performa agregat, evaluasi performa per kelompok komoditas untuk mengidentifikasi kekuatan dan kelemahan model pada jenis pangan spesifik, evaluasi robustness pada periode volatilitas tinggi untuk menguji kemampuan model saat kondisi ekstrem, serta perbandingan dengan model baseline untuk memvalidasi efektivitas strategi ensemble.

Evaluasi per komoditas dilakukan untuk komoditas dengan jumlah sampel memadai pada subset testing. Komoditas dengan MAPE terendah diidentifikasi sebagai komoditas yang diprediksi sangat akurat, sedangkan komoditas dengan MAPE tertinggi mengindikasikan tantangan prediksi yang memerlukan perhatian khusus.

Model terbaik dipilih berdasarkan kombinasi kriteria evaluasi dengan mempertimbangkan akurasi berdasarkan target metrik MAPE, MAE, dan RMSE, stabilitas performa antar kelompok komoditas dan periode volatilitas, serta keterapan praktis yang mempertimbangkan kompleksitas model, waktu prediksi, dan interpretabilitas.

**6. Penyebaran**

Fase penyebaran mengintegrasikan model terbaik ke dalam sistem informasi berbasis web menggunakan arsitektur microservices. Sistem terdiri dari tiga komponen utama: backend API untuk model serving, aplikasi web untuk antarmuka pengguna, dan database untuk penyimpanan data.

Backend API dikembangkan dengan FastAPI untuk menyediakan endpoints prediksi single komoditas, batch prediction, data historis, metrik performa model, dan health check. Model serving menggunakan kelas KaloriPrediktor yang memuat model terlatih saat inisialisasi API. Prediksi dilakukan secara asynchronous untuk mendukung konkurensi. Uvicorn digunakan sebagai ASGI server untuk menjalankan aplikasi dengan konfigurasi worker processes, connection timeout, dan logging.

Aplikasi web dibangun menggunakan Laravel dengan arsitektur MVC. Livewire diintegrasikan untuk komponen reaktif berbasis server. Fitur utama aplikasi mencakup single prediction untuk prediksi komoditas individual, batch prediction untuk prediksi massal, visualisasi time series untuk membandingkan data historis dan prediksi, perbandingan prediksi dengan realisasi untuk evaluasi akurasi, export hasil dalam berbagai format, serta autentikasi dan autorisasi untuk kontrol akses.

Database MySQL digunakan untuk penyimpanan data dengan skema yang dinormalisasi mencakup tabel komoditas, data historis, prediksi, dan users. Pengindeksan diterapkan untuk optimasi query pada primary key, foreign key, dan kombinasi kolom yang sering digunakan dalam filtering.

Containerization menggunakan Docker untuk mengemas aplikasi beserta dependensi. Docker Compose mengorkestrasikan multi-container deployment dengan service web untuk aplikasi Laravel, service API untuk FastAPI, dan service database untuk MySQL. Deployment workflow meliputi tahap build, startup, migration, dan monitoring.

---

# BAB IV HASIL DAN PEMBAHASAN

## 4.1 Pemahaman Bisnis

Penelitian ini bertujuan untuk memprediksi konsumsi kalori harian per kapita dari berbagai komoditas pangan di Indonesia berdasarkan data Neraca Bahan Makanan (NBM). Prediksi konsumsi kalori menjadi aspek krusial dalam perencanaan ketahanan pangan nasional, evaluasi pola konsumsi masyarakat, dan proyeksi kebutuhan distribusi pangan di masa mendatang.

Ruang lingkup penelitian mencakup analisis 114 komoditas pangan dengan periode pengamatan dari tahun 1993 hingga 2024. Fokus utama penelitian adalah mengidentifikasi komoditas yang memberikan kontribusi terbesar terhadap asupan kalori masyarakat Indonesia serta memprediksi tren konsumsi pangan untuk periode mendatang. Hasil prediksi diharapkan dapat memberikan informasi strategis bagi pemangku kebijakan dalam merancang program ketahanan pangan yang lebih efektif.

## 4.2 Pemahaman Data

Dataset penelitian terdiri dari dua sumber utama yang saling berelasi. Sumber pertama adalah transaksi_nbms.csv yang memuat 48.696 records data transaksi NBM bulanan dengan 41 fitur. Sumber kedua adalah komoditi.csv yang berisi informasi master data 114 komoditas beserta nilai gizinya dengan 22 fitur. Tabel 4 menunjukkan dimensi awal kedua dataset.

**Tabel 4. Dimensi Dataset Awal**

| Dataset       | Jumlah Baris | Jumlah Kolom |
| ------------- | ------------ | ------------ |
| Transaksi NBM | 48.696       | 41           |
| Komoditi      | 114          | 22           |

Analisis kualitas data menunjukkan beberapa fitur mengalami missing values. Pada dataset transaksi, fitur inflasi_komoditi, nilai_tukar_usd, gdp_per_kapita, tingkat_kemiskinan, dan indeks_el_nino mengalami kehilangan data secara menyeluruh dengan 48.696 nilai kosong. Fitur harga_produsen, harga_konsumen, curah_hujan_mm, dan suhu_rata_celsius memiliki 1.200 nilai kosong, sedangkan luas_panen_ha dan produktivitas_ton_ha kehilangan 22.596 nilai. Tabel 5 merangkum kondisi missing values pada dataset transaksi.

**Tabel 5. Distribusi Missing Values Dataset Transaksi**

| Fitur                | Jumlah Missing | Persentase |
| -------------------- | -------------- | ---------- |
| inflasi_komoditi     | 48.696         | 100%       |
| nilai_tukar_usd      | 48.696         | 100%       |
| gdp_per_kapita       | 48.696         | 100%       |
| tingkat_kemiskinan   | 48.696         | 100%       |
| indeks_el_nino       | 48.696         | 100%       |
| luas_panen_ha        | 22.596         | 46,4%      |
| produktivitas_ton_ha | 22.596         | 46,4%      |
| harga_produsen       | 1.200          | 2,5%       |
| harga_konsumen       | 1.200          | 2,5%       |
| curah_hujan_mm       | 1.200          | 2,5%       |
| suhu_rata_celsius    | 1.200          | 2,5%       |

Fitur-fitur dengan missing values 100% tidak dapat dimanfaatkan dalam pemodelan karena tidak memberikan informasi apapun. Fitur dengan kehilangan data parsial seperti luas_panen_ha dan produktivitas_ton_ha juga tidak diikutsertakan karena tingkat kehilangan yang signifikan dapat mempengaruhi kualitas model. Fitur dengan kehilangan minimal seperti harga_konsumen dan variabel cuaca tetap dipertahankan dengan penanganan melalui imputasi.

Variabel target utama dalam penelitian adalah kalori_per_kapita_per_hari yang dihitung dari fitur bahan_makanan (dalam satuan ribu ton). Fitur pendukung meliputi informasi time series periode 1993-2024 dengan tingkat rincian bulanan, karakteristik gizi komoditas (kalori_per_100g, protein_per_100g, lemak_per_100g, karbohidrat_per_100g), serta faktor eksternal berupa data populasi, harga konsumen, curah hujan, dan suhu rata-rata.

## 4.3 Persiapan Data

### 4.3.1 Data Cleaning dan Preprocessing

Tahap persiapan data dimulai dengan seleksi records berdasarkan periode data. Dataset awal memuat dua jenis periode yaitu bulanan dan kuartalan. Analisis distribusi menunjukkan data bulanan mendominasi dengan 47.820 records atau 98,2% dari total data, sedangkan data kuartalan hanya 876 records atau 1,8%. Perbedaan interval temporal antara data bulanan (1 bulan) dan kuartalan (3 bulan) berpotensi menciptakan ketidakkonsistenan dalam analisis time series. Model LSTM yang digunakan membutuhkan interval waktu yang konsisten untuk menangkap pola temporal dengan optimal. Berdasarkan pertimbangan tersebut, penelitian hanya menggunakan data dengan periode_data bernilai bulanan.

Filter tambahan diterapkan pada fitur bahan_makanan dengan mengeliminasi records yang memiliki nilai kosong atau bernilai nol. Nilai nol pada konsumsi pangan mengindikasikan tidak adanya aktivitas konsumsi yang dapat mengacaukan perhitungan kalori. Setelah proses filter, dataset transaksi tereduksi menjadi 47.820 records dengan 9 kolom terpilih yang relevan untuk analisis.

Proses penggabungan (merge) dilakukan antara dataset transaksi dan komoditi menggunakan kunci kode_komoditi. Hasil penggabungan menghasilkan dataset gabungan dengan dimensi 47.820 baris dan 14 kolom yang memuat informasi lengkap transaksi beserta nilai gizi setiap komoditas. Tabel 6 menampilkan struktur fitur terpilih setelah proses filter dan merge.

**Tabel 6. Fitur Terpilih Setelah Filter dan Merge**

| Kategori     | Fitur                                                                   |
| ------------ | ----------------------------------------------------------------------- |
| Identifikasi | kode_komoditi, nama                                                     |
| Temporal     | tahun, bulan                                                            |
| Target       | bahan_makanan                                                           |
| Demografis   | populasi_indonesia                                                      |
| Ekonomi      | harga_konsumen, inflasi_komoditi                                        |
| Lingkungan   | curah_hujan_mm, suhu_rata_celsius                                       |
| Nutrisi      | kalori_per_100g, protein_per_100g, lemak_per_100g, karbohidrat_per_100g |

### 4.3.2 Perhitungan Kalori per Kapita

Perhitungan kalori per kapita per hari menggunakan formula konversi sebagaimana dijelaskan pada Persamaan (1), yang mempertimbangkan volume konsumsi, kandungan kalori komoditas, jumlah populasi, dan durasi periode. Derivasi formula dimulai dengan konversi satuan konsumsi dari ribu ton menjadi gram. Sebagai ilustrasi, konsumsi beras pada Januari 1993 tercatat 19.039,2561 ribu ton. Konversi ke satuan gram menghasilkan 19.039.256.100.000 gram melalui perkalian dengan faktor 10⁹. Tahap berikutnya menghitung total kalori dengan mengalikan massa dalam gram dengan kandungan kalori per 100 gram. Beras memiliki kandungan 360 kalori per 100 gram, sehingga total kalori adalah 19.039.256.100.000 × (360/100) = 68.541.321.960.000 kalori.

Pembagian dengan total konsumsi orang-hari menghasilkan nilai kalori per kapita per hari. Pada Januari 1993, populasi Indonesia tercatat 187.000.000 jiwa dengan durasi 31 hari, menghasilkan 5.797.000.000 orang-hari. Kalori per kapita per hari untuk beras adalah 68.541.321.960.000 / 5.797.000.000 = 11.823,58 kalori per hari. Tabel 7 menampilkan hasil perhitungan kalori untuk lima records pertama.

**Tabel 7. Hasil Perhitungan Kalori per Kapita per Hari**

| Nama  | Tanggal    | Kalori per Kapita per Hari |
| ----- | ---------- | -------------------------- |
| Beras | 1993-01-01 | 11.823,58                  |
| Beras | 1993-02-01 | 14.848,38                  |
| Beras | 1993-03-01 | 14.330,03                  |
| Beras | 1993-04-01 | 14.305,97                  |
| Beras | 1993-05-01 | 12.414,11                  |

Rentang waktu dataset mencakup periode dari 1 Januari 1993 hingga 1 Desember 2024, memberikan cakupan temporal selama 31,92 tahun atau 383 bulan. Cakupan temporal yang luas memungkinkan model menangkap berbagai pola musiman, tren jangka panjang, serta dampak peristiwa ekonomi seperti krisis 1998 dan pandemi COVID-19.

### 4.3.3 Penanganan Outlier

Deteksi outlier menggunakan metode Interquartile Range (IQR) dengan threshold yang dimodifikasi sebagaimana dijelaskan pada Persamaan (16). Metode IQR standar menggunakan batas 1,5 × IQR yang cenderung terlalu agresif untuk data konsumsi pangan yang memiliki variasi musiman tinggi. Penelitian ini menerapkan threshold 3 × IQR untuk mengakomodasi fluktuasi seasonal yang merupakan pola alamiah bukan anomali.

Implementasi deteksi outlier dilakukan per komoditas untuk mempertahankan karakteristik konsumsi spesifik setiap jenis pangan. Sebagai ilustrasi, untuk komoditas beras dengan kode 0102, nilai kuartil pertama (Q₁) adalah 20.500 ribu ton dan kuartil ketiga (Q₃) adalah 28.000 ribu ton. Nilai IQR dihitung sebagai 28.000 - 20.500 = 7.500 ribu ton. Batas bawah adalah 20.500 - 3 × 7.500 = -2.000 ribu ton, sedangkan batas atas adalah 28.000 + 3 × 7.500 = 50.500 ribu ton. Data yang berada di luar rentang [-2.000, 50.500] dikategorikan sebagai outlier ekstrem.

Perbandingan dengan threshold standar 1,5 × IQR menunjukkan perbedaan signifikan dalam jumlah data yang dieliminasi. Threshold 1,5 × IQR menghasilkan batas bawah 9.250 ribu ton dan batas atas 39.250 ribu ton untuk beras, mengeliminasi sekitar 50 records yang sebagian besar merupakan lonjakan konsumsi saat Ramadan atau Lebaran. Threshold 3 × IQR hanya mengeliminasi 5 records yang benar-benar ekstrem, mempertahankan pola seasonal yang valid. Tabel 8 membandingkan dampak kedua threshold terhadap eliminasi data.

**Tabel 8. Perbandingan Threshold IQR terhadap Eliminasi Data**

| Threshold | Batas Bawah | Batas Atas | Records Tereliminasi | Persentase |
| --------- | ----------- | ---------- | -------------------- | ---------- |
| 1,5 × IQR | 9.250       | 39.250     | 50                   | 13,0%      |
| 3 × IQR   | -2.000      | 50.500     | 5                    | 1,3%       |

Penanganan missing values pada fitur numerik dilakukan melalui imputasi menggunakan nilai median. Median dipilih sebagai ukuran tendensi sentral karena lebih robust terhadap outlier dibandingkan mean. Fitur harga_konsumen, curah_hujan_mm, dan suhu_rata_celsius yang memiliki 1.200 nilai kosong diisi dengan nilai median masing-masing fitur. Setelah imputasi, seluruh dataset memiliki 47.820 records tanpa nilai kosong.

Proses eliminasi outlier dengan threshold 3 × IQR mengurangi dataset menjadi 47.423 records. Jumlah data yang dieliminasi adalah 397 records atau 0,83% dari total data setelah filter. Persentase eliminasi yang rendah mengindikasikan threshold yang dipilih efektif dalam mempertahankan data valid sambil mengeliminasi anomali ekstrem yang benar-benar tidak wajar. Listing program 1 memvisualisasikan distribusi data sebelum dan setelah eliminasi outlier.

**Listing Program 1. Implementasi Imputasi dan Eliminasi Outlier**

```python
numeric_cols = df.select_dtypes(include=[np.number]).columns
for col in numeric_cols:
    if df[col].isnull().sum() > 0:
        df[col].fillna(df[col].median(), inplace=True)

Q1 = df.groupby('kode_komoditi')['bahan_makanan'].transform('quantile', 0.25)
Q3 = df.groupby('kode_komoditi')['bahan_makanan'].transform('quantile', 0.75)
IQR = Q3 - Q1
lower = Q1 - 3 * IQR
upper = Q3 + 3 * IQR
df = df[(df['bahan_makanan'] >= lower) & (df['bahan_makanan'] <= upper)]
```

Dataset final terdiri dari 17 kolom dengan rincian, 2 kolom identifikasi (kode_komoditi, nama), 1 kolom temporal (date), 4 kolom turunan temporal (tahun, bulan, jumlah_hari), 1 kolom target (kalori_per_kapita_per_hari), 4 kolom numerik utama (bahan_makanan, populasi_indonesia, harga_konsumen, kalori_per_100g), dan 5 kolom fitur tambahan (inflasi_komoditi, curah_hujan_mm, suhu_rata_celsius, protein_per_100g, lemak_per_100g, karbohidrat_per_100g). Dataset mencakup 112 komoditas unik dengan total 47.423 observasi yang siap untuk tahap feature engineering dan pemodelan.

## 4.4 Pemodelan

### 4.4.1 Feature Engineering

Feature engineering dilakukan untuk memperkaya informasi temporal dan meningkatkan kemampuan model dalam menangkap pola konsumsi. Tiga kategori fitur ditambahkan: lag features, rolling window features, dan cyclical encoding.

**1. Lag Features**

Lag features menangkap ketergantungan temporal dengan memasukkan nilai konsumsi dari periode sebelumnya sebagaimana dijelaskan pada Persamaan (17). Analisis autokorelasi menunjukkan konsumsi bulan ini sangat berkorelasi dengan konsumsi 1-3 bulan sebelumnya. Untuk komoditas beras, koefisien autokorelasi lag-1 mencapai 0,847, lag-2 sebesar 0,721, dan lag-3 sebesar 0,615, mengindikasikan korelasi kuat hingga moderat.

Implementasi lag features menggunakan tiga periode mundur (k = 1, 2, 3). Sebagai ilustrasi, untuk memprediksi konsumsi Juni 2024, fitur lag-1 mengambil nilai konsumsi Mei 2024, lag-2 mengambil April 2024, dan lag-3 mengambil Maret 2024. Fitur-fitur ini memberikan konteks temporal yang memungkinkan model memahami tren jangka pendek. Listing Program 2 menunjukkan implementasi lag features.

**Listing Program 2. Implementasi Lag Features**

```python
def create_lag_features(df, target_col='bahan_makanan', lags=[1,2,3]):
    df_lag = df.copy()
    for lag in lags:
        df_lag[f'{target_col}_lag_{lag}'] = df_lag.groupby('kode_komoditi')[target_col].shift(lag)
    return df_lag
```

**2. Rolling Window Features**

Rolling window features menangkap tren dan volatilitas jangka pendek hingga menengah melalui statistik agregat sebagaimana dijelaskan pada Persamaan (18) dan (19). Dua ukuran window diterapkan: 3 bulan untuk pola kuartalan dan 6 bulan untuk pola semesteran. Setiap window menghasilkan dua statistik yaitu mean dan standard deviation.

Rolling mean menghaluskan fluktuasi jangka pendek dan mengidentifikasi tren. Untuk prediksi Juni 2024 dengan window 3 bulan, rolling mean dihitung dari rata-rata konsumsi April, Mei, dan Juni. Jika konsumsi ketiga bulan tersebut adalah 31.000, 29.000, dan 33.000 ribu ton, maka rolling mean adalah (31.000 + 29.000 + 33.000) / 3 = 31.000 ribu ton. Rolling standard deviation mengukur volatilitas atau variabilitas konsumsi. Nilai standard deviation tinggi mengindikasikan fluktuasi besar, sedangkan nilai rendah menunjukkan konsumsi yang stabil. Listing Program 3 menampilkan implementasi rolling window features.

**Listing Program 3. Implementasi Rolling Window Features**

```python
def create_rolling_features(df, target_col='bahan_makanan', windows=[3,6]):
    df_roll = df.copy()
    for window in windows:
        df_roll[f'{target_col}_roll_mean_{window}'] = df_roll.groupby('kode_komoditi')[target_col].transform(
            lambda x: x.rolling(window=window, min_periods=1).mean()
        )
        df_roll[f'{target_col}_roll_std_{window}'] = df_roll.groupby('kode_komoditi')[target_col].transform(
            lambda x: x.rolling(window=window, min_periods=1).std()
        )
    return df_roll
```

**3. Cyclical Encoding**

Encoding bulan menggunakan transformasi siklikal berbasis fungsi sinus dan kosinus untuk mempertahankan kontinuitas temporal. Encoding numerik standar (Januari=1, Februari=2, ..., Desember=12) menciptakan diskontinuitas artifisial antara Desember dan Januari dengan jarak 11 unit, padahal keduanya hanya berjarak 1 bulan secara kalender. Transformasi siklikal memetakan bulan ke koordinat pada lingkaran unit, menjaga kedekatan bulan yang bersebelahan. Persamaan (20) dan (21) mendefinisikan cyclical encoding untuk bulan.

Sebagai ilustrasi, Januari (bulan ke-1) memiliki nilai sin = sin(2π×1/12) = 0,5 dan cos = cos(2π×1/12) = 0,866. Desember (bulan ke-12) menghasilkan sin = sin(2π×12/12) = 0,0 dan cos = cos(2π×12/12) = 1,0. Jarak Euclidean antara Desember dan Januari adalah √((0,5 - 0)² + (0,866 - 1)²) = 0,52, jauh lebih kecil dibandingkan jarak numerik 11 unit pada encoding standar. Fitur tambahan quarter ditambahkan untuk menangkap pola kuartalan dengan nilai 1 untuk Q1 (Januari-Maret), 2 untuk Q2 (April-Juni), 3 untuk Q3 (Juli-September), dan 4 untuk Q4 (Oktober-Desember).

Label encoding diterapkan pada fitur kategorikal kode_komoditi untuk mengkonversi identifier string menjadi nilai numerik. Setiap komoditas unik diberi indeks integer dari 0 hingga 111, memungkinkan model neural network memproses informasi komoditas secara efisien. Total fitur setelah feature engineering mencapai 28 kolom, terdiri dari fitur asli, lag features, rolling features, cyclical encoding, dan label encoding.

### 4.4.2 Pembagian Dataset

Dataset dibagi menjadi tiga subset: training, validation, dan testing dengan proporsi 70-15-15. Pembagian dilakukan berdasarkan urutan temporal untuk mempertahankan integritas time series. Subset training mencakup 33.007 records dari periode 1 April 1993 hingga 1 Januari 2016, setara dengan 22,75 tahun atau 273 bulan. Durasi ini cukup panjang untuk menangkap berbagai siklus ekonomi termasuk krisis finansial Asia 1997-1998, kenaikan harga pangan global 2007-2008, dan periode pertumbuhan ekonomi stabil. Subset validation terdiri dari 7.155 records mencakup periode 1 Februari 2016 hingga 1 Februari 2020 dengan durasi 4 tahun. Subset testing memuat 6.925 records dari periode 1 Maret 2020 hingga 1 Desember 2024, mencakup 4,75 tahun termasuk periode pandemi COVID-19 yang menjadi uji ketahanan model terhadap shock eksternal. Tabel 9 merangkum distribusi dataset.

**Tabel 9. Distribusi Subset Dataset**

| Subset     | Jumlah Records | Periode Awal | Periode Akhir | Durasi (Tahun) |
| ---------- | -------------- | ------------ | ------------- | -------------- |
| Training   | 33.007         | 1993-04-01   | 2016-01-01    | 22,75          |
| Validation | 7.155          | 2016-02-01   | 2020-02-01    | 4,00           |
| Testing    | 6.925          | 2020-03-01   | 2024-12-01    | 4,75           |

Fitur yang digunakan untuk pemodelan mencakup 17 variabel: komoditi_encoded, tahun, bulan, month_sin, month_cos, quarter, tiga lag features (bahan_makanan_lag_1, bahan_makanan_lag_2, bahan_makanan_lag_3), empat rolling features (bahan_makanan_roll_mean_3, bahan_makanan_roll_std_3, bahan_makanan_roll_mean_6, bahan_makanan_roll_std_6), serta empat fitur eksternal (populasi_indonesia, harga_konsumen, curah_hujan_mm, suhu_rata_celsius). Variabel target adalah bahan_makanan dalam satuan ribu ton.

### 4.4.3 Normalisasi Data

Normalisasi dilakukan menggunakan MinMaxScaler yang mentransformasi setiap fitur ke rentang [0, 1] sebagaimana dijelaskan pada Persamaan (22). Metode ini dipilih karena menjaga distribusi asli data dan kompatibel dengan fungsi aktivasi neural network.

Normalisasi diterapkan secara terpisah untuk fitur (X) dan target (y). Scaler fitur di-fit pada subset training kemudian ditransformasi ke subset validation dan testing, mencegah data leakage. Hal serupa dilakukan pada scaler target. Untuk keperluan model LSTM, data dinormalisasi direshape menjadi format 3 dimensi dengan struktur (samples, timesteps, features). Karena setiap observasi diperlakukan sebagai timestep tunggal, dimensi timesteps bernilai 1, menghasilkan bentuk tensor (33.007, 1, 17) untuk subset training, (7.155, 1, 17) untuk validation, dan (6.925, 1, 17) untuk testing.

### 4.4.4 Pengembangan Model LSTM

Model LSTM dirancang dengan arsitektur deep stacked menggunakan tiga lapisan LSTM dengan konfigurasi pyramid: 128 unit, 64 unit, dan 32 unit. Setiap lapisan LSTM diikuti oleh Dropout dengan rate 0,2 untuk regularisasi. Lapisan Dense akhir dengan 16 unit dan fungsi aktivasi ReLU berfungsi sebagai ekstraksi fitur tingkat tinggi sebelum lapisan output tunggal. Operasi LSTM cell mengikuti mekanisme gating yang telah dijelaskan pada persamaan (2) hingga (7).

Struktur pyramid dengan rasio kompresi 50% per lapisan (128→64→32) memungkinkan model mengekstrak representasi hierarkis. Lapisan pertama dengan 128 unit memiliki kapasitas 7,5 kali jumlah fitur input, cukup untuk menangkap pola kompleks. Lapisan kedua mengompresi representasi menjadi 64 unit, mengekstrak fitur abstrak tingkat menengah. Lapisan ketiga dengan 32 unit menghasilkan representasi final yang paling esensial. Total parameter model LSTM adalah 137.121, menghasilkan rasio data-parameter sebesar 33.007/137.121 = 0,24, berada dalam rentang ideal 0,1-1,0 yang meminimalkan risiko overfitting dan underfitting.

Dropout dengan rate 0,2 diterapkan setelah setiap lapisan LSTM. Pada setiap iterasi training, 20% neuron dinonaktifkan secara acak, memaksa model mengembangkan representasi yang redundan dan robust. Dropout rate 0,2 dipilih karena LSTM sudah memiliki mekanisme regularisasi internal melalui gate, sehingga dropout terlalu tinggi dapat menghambat pembelajaran. Listing Program 4 menampilkan arsitektur model LSTM.

**Listing Program 4. Arsitektur Model LSTM**

```python
model_lstm = Sequential([
    LSTM(128, activation='relu', return_sequences=True, input_shape=(1, X_train_scaled.shape[1])),
    Dropout(0.2),
    LSTM(64, activation='relu', return_sequences=True),
    Dropout(0.2),
    LSTM(32, activation='relu'),
    Dropout(0.2),
    Dense(16, activation='relu'),
    Dense(1)
])
model_lstm.compile(optimizer='adam', loss='mse', metrics=['mae'])
```

Model dilatih menggunakan optimizer Adam dengan learning rate default 0,001, fungsi loss Mean Squared Error (MSE), dan metrik evaluasi Mean Absolute Error (MAE). Batch size 32 dipilih untuk menyeimbangkan kecepatan komputasi dan stabilitas gradien. Early stopping dengan patience 10 diterapkan untuk menghentikan training ketika validation loss tidak menurun selama 10 epoch berturut-turut, mencegah overfitting dan menghemat waktu komputasi.

Proses training berlangsung selama 45 epoch hingga early stopping aktif. Training loss konvergen dari 0,0036 pada epoch 1 menjadi 0,0001 pada epoch akhir, menunjukkan pembelajaran yang efektif. Validation loss menurun dari 0,0014 pada epoch 1 menjadi 0,0002 pada epoch 35, mengindikasikan generalisasi yang baik tanpa overfitting signifikan. Tabel 10 menampilkan metrik training pada epoch terpilih.

**Tabel 10. Metrik Training Model LSTM**

| Epoch | Training Loss | Training MAE | Validation Loss | Validation MAE |
| ----- | ------------- | ------------ | --------------- | -------------- |
| 1     | 0,0036        | 0,0285       | 0,0014          | 0,0160         |
| 10    | 0,0002        | 0,0079       | 0,0009          | 0,0112         |
| 20    | 0,0001        | 0,0066       | 0,0003          | 0,0085         |
| 35    | 0,0001        | 0,0056       | 0,0002          | 0,0094         |
| 45    | 0,0001        | 0,0054       | 0,0003          | 0,0102         |

Evaluasi pada subset testing menghasilkan MAE 1.593,33 ribu ton, RMSE 2.333,41 ribu ton, dan MAPE 40,78%. MAPE yang tinggi mengindikasikan model LSTM cenderung overpredict atau underpredict pada nilai konsumsi kecil, meskipun MAE dan RMSE relatif rendah untuk nilai konsumsi besar.

### 4.4.5 Pengembangan Model XGBoost

Model XGBoost dikonfigurasi dengan lima hyperparameter utama yang dioptimalkan melalui eksperimen: n_estimators, max_depth, learning_rate, subsample, dan colsample_bytree. Fungsi objektif XGBoost dengan regularisasi mengikuti persamaan (8) dan (9) yang telah dijelaskan sebelumnya.

Parameter n_estimators=200 menentukan jumlah tree dalam ensemble. Nilai ini dipilih berdasarkan validation curve yang menunjukkan konvergensi validation MAPE pada kisaran 200 tree. Parameter max_depth=8 membatasi kedalaman setiap tree, memungkinkan hingga 256 terminal nodes. Dengan 33.007 sampel training, rata-rata setiap node menampung 129 sampel, cukup untuk menangkap pola tanpa overfitting. Parameter learning_rate=0,05 mengontrol kontribusi setiap tree terhadap prediksi final. Nilai ini lebih konservatif dibandingkan default 0,1, memungkinkan konvergensi yang lebih stabil dengan 200 tree. Parameter subsample=0,8 berarti setiap tree dilatih pada 80% sampel training yang dipilih acak, meningkatkan diversitas antar tree. Parameter colsample_bytree=0,8 membatasi setiap tree menggunakan 80% fitur atau sekitar 14 dari 17 fitur, mencegah dominasi fitur tertentu. Tabel 11 merangkum konfigurasi hyperparameter XGBoost.

**Tabel 11. Konfigurasi Hyperparameter XGBoost**

| Hyperparameter   | Nilai | Justifikasi                           |
| ---------------- | ----- | ------------------------------------- |
| n_estimators     | 200   | Konvergensi validation MAPE           |
| max_depth        | 8     | Balance kompleksitas dan generalisasi |
| learning_rate    | 0,05  | Konvergensi stabil                    |
| subsample        | 0,8   | Diversitas antar tree                 |
| colsample_bytree | 0,8   | Pencegahan dominasi fitur             |

Early stopping dengan 10 rounds diterapkan berdasarkan validation loss. Model berhenti training pada iteration optimal tanpa menunggu 200 tree penuh jika tidak ada peningkatan performa. Listing Program 5 menampilkan implementasi model XGBoost.

**Listing Program 5. Implementasi Model XGBoost**

```python
model_xgb = xgb.XGBRegressor(
    n_estimators=200,
    max_depth=8,
    learning_rate=0.05,
    subsample=0.8,
    colsample_bytree=0.8,
    random_state=42,
    early_stopping_rounds=10
)
model_xgb.fit(
    X_train_scaled, y_train,
    eval_set=[(X_val_scaled, y_val)],
    verbose=False
)
```

Evaluasi pada subset testing menghasilkan MAE 862,09 ribu ton, RMSE 3.225,99 ribu ton, dan MAPE 3,71%. XGBoost menunjukkan MAPE jauh lebih rendah dibandingkan LSTM, mengindikasikan performa superior pada berbagai skala konsumsi. RMSE yang lebih tinggi dibandingkan MAE menunjukkan keberadaan beberapa prediksi dengan error besar, namun secara rata-rata model tetap akurat.

### 4.4.6 Pengembangan Model HuberRegressor

HuberRegressor dipilih sebagai model ketiga untuk melengkapi ensemble dengan karakteristik robust terhadap outlier. Model ini menggunakan kombinasi loss kuadratik untuk error kecil dan loss linear untuk error besar, memberikan stabilitas prediksi. Persamaan (10) mendefinisikan Huber loss function.

Parameter epsilon=1,35 menentukan threshold transisi antara loss kuadratik dan linear. Nilai 1,35 dipilih karena meminimalkan varians estimator untuk distribusi normal. Parameter max_iter=200 membatasi iterasi optimisasi, cukup untuk konvergensi pada dataset berukuran sedang. Parameter alpha=0,001 mengontrol kekuatan regularisasi L2, mencegah overfitting tanpa terlalu membatasi kompleksitas model.

Evaluasi pada subset testing menghasilkan MAE 926,82 ribu ton, RMSE 2.859,29 ribu ton, dan MAPE 2,57%. HuberRegressor mencapai MAPE terendah di antara ketiga model individual, menunjukkan robustness terhadap variasi konsumsi. MAE dan RMSE berada di antara LSTM dan XGBoost, mengindikasikan performa yang seimbang.

### 4.4.7 Optimisasi Ensemble dengan Differential Evolution

Ensemble model menggunakan strategi weighted averaging dengan bobot dioptimalkan menggunakan algoritma Differential Evolution (DE). Algoritma ini dipilih karena kemampuannya menemukan solusi global pada ruang pencarian non-konveks tanpa memerlukan turunan fungsi objektif. Prediksi ensemble dengan bobot optimal mengikuti persamaan (13), dengan fungsi objektif yang diminimalkan mengikuti persamaan (14).

Bounds untuk setiap bobot didefinisikan berdasarkan ekspektasi kontribusi: LSTM [0,4-0,9], XGBoost [0,05-0,5], dan HuberRegressor [0,05-0,5]. Bounds ini memberikan fleksibilitas sambil mencegah dominasi ekstrem satu model.

Proses optimisasi berlangsung dengan maxiter=500, memberikan 500 generasi untuk eksplorasi dan eksploitasi ruang solusi. Opsi polish=True mengaktifkan optimisasi lokal pada solusi terbaik untuk refinement. Opsi updating='deferred' meningkatkan efisiensi komputasi dengan memperbarui populasi secara paralel. Hasil optimisasi menghasilkan bobot optimal: LSTM = 0,8749, XGBoost = 0,0765, dan HuberRegressor = 0,0486. Listing Program 6 menampilkan implementasi optimisasi ensemble.

**Listing Program 6. Optimisasi Ensemble dengan Differential Evolution**

```python
def ensemble_predictions(weights, predictions):
    return weights[0] * predictions[:, 0] + weights[1] * predictions[:, 1] + weights[2] * predictions[:, 2]

def objective(weights, predictions, actuals):
    if np.sum(weights) == 0:
        return 1e10
    weights_normalized = weights / np.sum(weights)
    ensemble_pred = ensemble_predictions(weights_normalized, predictions)
    return mean_squared_error(actuals, ensemble_pred)

bounds_de = [(0.4, 0.9), (0.05, 0.5), (0.05, 0.5)]
result = differential_evolution(
    objective,
    bounds_de,
    args=(predictions_val, y_val.values),
    maxiter=500,
    seed=42,
    polish=True,
    updating='deferred'
)
optimal_weights = result.x / result.x.sum()
```

Dominasi LSTM dengan bobot 87,49% mengindikasikan superioritas model dalam menangkap pola temporal kompleks pada nilai konsumsi besar. Kontribusi XGBoost sebesar 7,65% memberikan stabilitas pada nilai konsumsi kecil hingga menengah. HuberRegressor dengan 4,86% berfungsi sebagai regularizer yang meredam prediksi ekstrem.

### 4.4.8 Strategi Conditional Ensemble

Analisis distribusi error menunjukkan performa model bervariasi berdasarkan skala konsumsi. XGBoost unggul pada nilai kecil (< 5.000 ribu ton) dengan MAPE sekitar 3,7%, sedangkan ensemble LSTM lebih akurat pada nilai besar (≥ 5.000 ribu ton). Strategi conditional ensemble diterapkan dengan threshold 5.000 ribu ton yang merupakan nilai median distribusi konsumsi, mengikuti persamaan (15).

Threshold 5.000 ribu ton dipilih melalui evaluasi berbagai kandidat (500, 1.000, 2.000, 3.000, 5.000) berdasarkan validation MAPE. Threshold 5.000 menghasilkan MAPE terendah sebesar 2,9181% dengan distribusi seimbang: 50% sampel menggunakan XGBoost dan 50% menggunakan weighted ensemble.

### 4.4.9 Perbandingan Performa Model

Evaluasi model menggunakan tiga metrik yaitu MAE, RMSE, dan MAPE yang telah didefinisikan pada persamaan (23) hingga (25). Tabel 12 merangkum performa keempat model pada subset testing.

**Tabel 12. Perbandingan Performa Model pada Subset Testing**

| Model                  | MAE (ribu ton) | RMSE (ribu ton) | MAPE (%) |
| ---------------------- | -------------- | --------------- | -------- |
| LSTM                   | 1.593,33       | 2.333,41        | 40,78    |
| HuberRegressor         | 926,82         | 2.859,29        | 2,57     |
| XGBoost                | 862,09         | 3.225,99        | 3,71     |
| LSTM Enhanced Ensemble | 1.128,85       | 2.331,78        | 3,75     |

HuberRegressor mencapai MAPE terendah 2,57%, menunjukkan akurasi persentase terbaik. XGBoost menghasilkan MAE terendah 862,09 ribu ton, mengindikasikan error absolut rata-rata paling kecil. LSTM memiliki RMSE terendah 2.333,41 ribu ton bersama dengan ensemble, menunjukkan konsistensi prediksi. LSTM Enhanced Ensemble memberikan keseimbangan terbaik dengan MAPE 3,75%, MAE 1.128,85 ribu ton, dan RMSE 2.331,78 ribu ton.

Peningkatan performa ensemble terhadap LSTM individual mencapai 29,2% untuk MAE dan 90,8% untuk MAPE, mengkonfirmasi efektivitas strategi conditional ensemble dan optimisasi bobot. Model ensemble dipilih sebagai solusi final karena memberikan prediksi yang robust dan seimbang pada berbagai skala konsumsi pangan.

## 4.5 Evaluasi

### 4.5.1 Visualisasi Performa Model

Evaluasi visual dilakukan melalui dua jenis plot: scatter plot untuk hubungan nilai aktual vs prediksi dan histogram untuk distribusi error. Gambar 4 menampilkan scatter plot keempat model yang membandingkan nilai aktual dengan prediksi pada subset testing.

![Gambar 4. Visualisasi Performa Model]

Plot LSTM menunjukkan penyebaran titik yang cukup lebar dari garis diagonal y=x, terutama pada rentang nilai kecil dimana model cenderung overpredict. Pada nilai konsumsi di atas 20.000 ribu ton, prediksi LSTM lebih mendekati garis diagonal, mengindikasikan akurasi lebih baik pada skala besar. HuberRegressor menghasilkan sebaran lebih rapat di sekitar garis diagonal untuk seluruh rentang nilai, mencerminkan MAPE terendah 2,57%. XGBoost menunjukkan pola serupa dengan HuberRegressor namun dengan sedikit lebih banyak titik menyimpang pada nilai ekstrem tinggi. Plot ensemble memperlihatkan kombinasi karakteristik terbaik dari ketiga model: ketatnya sebaran pada nilai kecil (kontribusi XGBoost) dan akurasi pada nilai besar (kontribusi LSTM).

Histogram distribusi error untuk XGBoost dan ensemble ditampilkan pada panel bawah Gambar 4. Distribusi XGBoost menunjukkan pola right-skewed dengan median error 450 ribu ton dan mean 862 ribu ton. Mayoritas prediksi memiliki error di bawah 1.000 ribu ton dengan ekor panjang pada error besar mencapai 15.000 ribu ton. Distribusi ensemble memiliki karakteristik serupa dengan median 520 ribu ton dan mean 1.129 ribu ton, sedikit lebih tinggi namun tetap menunjukkan konsentrasi error pada rentang kecil. Perbedaan mean dan median yang positif pada kedua distribusi mengkonfirmasi dominasi error kecil dengan beberapa kasus error ekstrem.

### 4.5.2 Analisis Performa per Komoditas

Analisis performa dilakukan per komoditas untuk mengidentifikasi kekuatan dan kelemahan model pada jenis pangan spesifik. Hanya komoditas dengan minimal 10 sampel pada subset testing yang dianalisis untuk memastikan signifikansi statistik. Dari 112 komoditas, 85 komoditas memenuhi kriteria ini. Tabel 13 menampilkan 10 komoditas dengan MAPE terendah.

**Tabel 13. 10 Komoditas dengan Performa Terbaik (MAPE Terendah)**

| Komoditas           | MAE (ribu ton) | MAPE (%) | Jumlah Sampel |
| ------------------- | -------------- | -------- | ------------- |
| Buah Naga           | 8,97           | 0,90     | 21            |
| Tomat               | 1.322,65       | 3,07     | 78            |
| Daging Kerbau       | 69,69          | 3,21     | 52            |
| Bawang Merah        | 1.494,68       | 3,21     | 58            |
| Salak               | 1.510,10       | 3,45     | 58            |
| Tapioka             | 62,25          | 3,55     | 58            |
| Gaplek              | 37,24          | 3,61     | 58            |
| Susu Sapi           | 1.186,71       | 3,72     | 80            |
| Minyak Kacang Tanah | 62,78          | 4,38     | 68            |
| Kedelai             | 179,77         | 4,39     | 104           |

Buah Naga mencapai MAPE terendah 0,90% dengan MAE hanya 8,97 ribu ton, mengindikasikan model sangat akurat memprediksi konsumsi komoditas ini. Tomat dengan MAPE 3,07% menunjukkan akurasi tinggi meskipun memiliki MAE lebih besar 1.322,65 ribu ton karena skala konsumsi yang lebih besar. Komoditas dengan pola konsumsi stabil seperti Daging Kerbau, Tapioka, dan Gaplek diprediksi dengan baik karena minimnya fluktuasi temporal. Kedelai dengan 104 sampel dan MAPE 4,39% menunjukkan konsistensi model pada komoditas dengan volume data yang besar. Tabel 14 menampilkan 10 komoditas dengan MAPE tertinggi yang mengindikasikan tantangan prediksi.

**Tabel 14. 10 Komoditas dengan Performa Terburuk (MAPE Tertinggi)**

| Komoditas         | MAE (ribu ton) | MAPE (%)  | Jumlah Sampel |
| ----------------- | -------------- | --------- | ------------- |
| Gula Pasir        | 868,88         | 16.925,43 | 80            |
| Susu Impor        | 6.485,71       | 6.852,57  | 80            |
| Rasberi           | 24,94          | 5.024,17  | 58            |
| Tin               | 26,27          | 3.295,96  | 58            |
| Kacang Merah      | 162,37         | 1.169,95  | 58            |
| Jeruk             | 6.682,39       | 964,64    | 58            |
| Kesemek           | 20,66          | 287,92    | 58            |
| Daging Ayam Buras | 2.105,51       | 46,30     | 68            |
| Lemak Kerbau      | 10,79          | 25,61     | 57            |
| Daging Kuda       | 10,24          | 23,83     | 68            |

Gula Pasir memiliki MAPE ekstrem 16.925,43% yang disebabkan oleh konsumsi aktual yang sangat kecil mendekati nol pada beberapa periode, membuat perhitungan persentase error menjadi sangat besar. Fenomena serupa terjadi pada Susu Impor dengan MAPE 6.852,57%. Komoditas eksotis seperti Rasberi dan Tin dengan konsumsi minimal dan fluktuatif menghasilkan MAPE ribuan persen. Jeruk dengan MAE 6.682,39 ribu ton menunjukkan variabilitas konsumsi tinggi yang sulit diprediksi, kemungkinan dipengaruhi faktor musiman dan harga yang tidak tertangkap sempurna oleh model.

Analisis per komoditas mengungkapkan model ensemble sangat efektif pada komoditas dengan pola konsumsi stabil dan volume menengah hingga besar seperti beras, jagung, dan sayuran utama. Tantangan utama muncul pada komoditas dengan konsumsi sangat kecil, fluktuasi ekstrem, atau data terbatas. Untuk komoditas problematik, pendekatan domain-specific seperti model terpisah atau feature engineering tambahan mungkin diperlukan.

### 4.5.3 Ringkasan Evaluasi Model

Evaluasi komprehensif mengkonfirmasi LSTM Enhanced Ensemble sebagai solusi optimal dengan MAPE 3,75%, MAE 1.128,85 ribu ton, dan RMSE 2.331,78 ribu ton pada subset testing. Strategi conditional dengan threshold 5.000 ribu ton memungkinkan model memanfaatkan kekuatan XGBoost pada nilai kecil dan superioritas LSTM pada nilai besar. Distribusi prediksi seimbang dengan 50,7% sampel menggunakan XGBoost dan 49,3% menggunakan weighted ensemble.

Peningkatan signifikan tercapai dibandingkan model LSTM individual: MAE menurun 29,2% dan MAPE menurun 90,8%. Model menunjukkan performa superior pada 85 komoditas dengan minimal 10 sampel testing, mencapai MAPE di bawah 5% untuk 10 komoditas teratas. Kelemahan utama teridentifikasi pada komoditas dengan konsumsi minimal dan fluktuasi ekstrem, namun ini merepresentasikan minoritas kecil dari total dataset.

## 4.6 Penyebaran

### 4.6.1 Arsitektur Sistem Prediksi

Sistem prediksi dikemas dalam kelas KaloriPredictor yang mengintegrasikan tiga model terlatih (LSTM, XGBoost, HuberRegressor), scalers, label encoder, dan konfigurasi ensemble. Arsitektur memungkinkan prediksi multi-periode dengan update otomatis fitur temporal berdasarkan hasil prediksi sebelumnya. Listing Program 7 menampilkan inisialisasi kelas KaloriPredictor.

**Listing Program 7. Inisialisasi Kelas KaloriPredictor**

```python
class KaloriPredictor:
    def __init__(self, config_path='ensemble_config.pkl'):
        import pickle
        from tensorflow import keras

        with open(config_path, 'rb') as f:
            config = pickle.load(f)

        self.optimal_weights = config['optimal_weights']
        self.threshold = config['threshold']
        self.feature_cols = config['feature_cols']

        self.model_lstm = keras.models.load_model('model_lstm.keras')

        with open('model_xgb.pkl', 'rb') as f:
            self.model_xgb = pickle.load(f)

        with open('model_huber.pkl', 'rb') as f:
            self.model_huber = pickle.load(f)

        with open('scaler_X.pkl', 'rb') as f:
            self.scaler_X = pickle.load(f)

        with open('scaler_y.pkl', 'rb') as f:
            self.scaler_y = pickle.load(f)

        with open('label_encoder.pkl', 'rb') as f:
            self.le_komoditi = pickle.load(f)

        self.df_clean = pd.read_csv('data_clean.csv', dtype={'kode_komoditi': str})
        self.df_clean['date'] = pd.to_datetime(self.df_clean['date'])
```

Kelas memuat 8 komponen utama: konfigurasi ensemble (bobot optimal dan threshold), 3 model terlatih, 2 scaler untuk fitur dan target, label encoder untuk komoditas, dan dataset historis. Pemisahan komponen memungkinkan fleksibilitas dalam update individual tanpa mempengaruhi keseluruhan sistem.

### 4.6.2 Mekanisme Prediksi Multi-Periode

Metode predict_future mengimplementasikan prediksi multi-periode dengan update iteratif fitur temporal. Untuk setiap bulan yang diprediksi, sistem mengekstrak 6 bulan data terakhir sebagai konteks, menghitung lag features dan rolling features, membuat prediksi menggunakan ketiga model, menerapkan strategi conditional ensemble, dan memperbarui data historis dengan hasil prediksi untuk periode berikutnya.

Ilustrasi mekanisme prediksi 3 bulan untuk Beras dimulai dengan data historis 6 bulan terakhir. Prediksi Januari 2025 menggunakan lag-1 dari Desember 2024, lag-2 dari November 2024, dan lag-3 dari Oktober 2024. Rolling mean 3 bulan dihitung dari Oktober-Desember 2024, sedangkan rolling mean 6 bulan dari Juli-Desember 2024. Hasil prediksi Januari 2025 ditambahkan ke data historis. Prediksi Februari 2025 menggunakan lag-1 dari Januari 2025 (prediksi), lag-2 dari Desember 2024, dan lag-3 dari November 2024. Proses berlanjut hingga Maret 2025 dengan rolling window mencakup kombinasi data historis dan prediksi.

Populasi Indonesia untuk periode prediksi diestimasikan menggunakan proyeksi linear dengan pertumbuhan 2.500.000 jiwa per tahun. Untuk tahun 2025, populasi diestimasikan 285.000.000 jiwa dari baseline 2024 sebesar 280.500.000 jiwa. Fitur eksternal seperti harga_konsumen, curah_hujan_mm, dan suhu_rata_celsius menggunakan nilai terakhir dari data historis karena tidak tersedia proyeksi.

### 4.6.3 Hasil Prediksi Komoditas Terpilih

Sistem diuji pada dua komoditas utama: Beras (kode 0102) dan Jagung (kode 0103) untuk periode Januari-Maret 2025. Tabel 15 menampilkan hasil prediksi untuk Beras.

**Tabel 15. Hasil Prediksi Konsumsi Beras Januari-Maret 2025**

| Bulan         | Konsumsi (ribu ton) | Kalori per Kapita per Hari | Model Digunakan |
| ------------- | ------------------- | -------------------------- | --------------- |
| Januari 2025  | 29.074,84           | 11.744,11                  | Ensemble        |
| Februari 2025 | 30.340,20           | 13.568,29                  | Ensemble        |
| Maret 2025    | 30.730,46           | 12.412,87                  | Ensemble        |

Prediksi konsumsi Beras menunjukkan tren peningkatan dari Januari ke Maret dengan rata-rata 30.048,50 ribu ton per bulan. Kalori per kapita per hari bervariasi antara 11.744-13.568 kalori, mencerminkan perbedaan jumlah hari per bulan (Februari 28 hari dengan Januari/Maret 31 hari). Ketiga bulan menggunakan weighted ensemble karena konsumsi melebihi threshold 5.000 ribu ton, memanfaatkan superioritas LSTM pada skala besar. Tabel 16 menampilkan hasil prediksi untuk Jagung.

**Tabel 16. Hasil Prediksi Konsumsi Jagung Januari-Maret 2025**

| Bulan         | Konsumsi (ribu ton) | Kalori per Kapita per Hari | Model Digunakan |
| ------------- | ------------------- | -------------------------- | --------------- |
| Januari 2025  | 6.160,54            | 2.522,97                   | Ensemble        |
| Februari 2025 | 6.617,06            | 3.000,28                   | Ensemble        |
| Maret 2025    | 7.094,20            | 2.905,34                   | Ensemble        |

Prediksi konsumsi Jagung menunjukkan tren peningkatan signifikan dengan pertumbuhan 15,2% dari Januari ke Maret. Rata-rata konsumsi 6.623,93 ribu ton per bulan dengan kontribusi kalori 2.809,53 kalori per kapita per hari. Pola peningkatan konsumsi Jagung sejalan dengan siklus panen dan kebutuhan pakan ternak yang meningkat menjelang pertengahan tahun.

### 4.6.4 Prediksi Agregat Seluruh Komoditas

Sistem menghasilkan prediksi untuk seluruh 112 komoditas dengan total 336 records (112 komoditas × 3 bulan). Agregasi per bulan menghasilkan proyeksi konsumsi total dan kontribusi kalori nasional. Tabel 17 merangkum prediksi agregat.

**Tabel 17. Prediksi Agregat Konsumsi Januari-Maret 2025**

| Bulan         | Total Konsumsi (ribu ton) | Total Kalori per Kapita per Hari | Jumlah Komoditas |
| ------------- | ------------------------- | -------------------------------- | ---------------- |
| Januari 2025  | 1.145.044                 | 147.058,50                       | 111              |
| Februari 2025 | 1.178.076                 | 167.132,74                       | 111              |
| Maret 2025    | 1.223.430                 | 156.511,86                       | 111              |

Total konsumsi menunjukkan peningkatan 6,8% dari Januari ke Maret, mencerminkan pola musiman dan proyeksi peningkatan populasi. Kontribusi kalori tertinggi terjadi pada Februari dengan 167.132,74 kalori per kapita per hari, kemudian menurun di Maret meskipun total konsumsi meningkat. Perbedaan ini disebabkan durasi bulan dimana Februari dengan 28 hari menghasilkan pembagi lebih kecil dalam formula kalori per hari. 111 dari 112 komoditas diprediksi karena 1 komoditas tidak memiliki data historis yang cukup untuk ekstraksi fitur.

Seluruh komponen sistem dikemas dalam direktori kalori_predictor_deployment yang berisi 8 file model dan konfigurasi: model_lstm.keras (model LSTM terlatih), model_xgb.pkl (model XGBoost terlatih), model_huber.pkl (model HuberRegressor terlatih), scaler_X.pkl (MinMaxScaler untuk fitur), scaler_y.pkl (MinMaxScaler untuk target), label_encoder.pkl (encoder komoditas), ensemble_config.pkl (konfigurasi ensemble), dan data_clean.csv (dataset historis).

Dokumentasi teknis dalam file README.md menjelaskan penggunaan sistem, daftar file, persyaratan library, dan metrik performa model. Struktur paket memungkinkan deployment pada berbagai platform termasuk server produksi, aplikasi web, dan notebook analitik.

### 4.6.5 Implementasi pada Web

Model LSTM Enhanced Ensemble diimplementasikan pada aplikasi web SIKOLBIA menggunakan arsitektur microservices Laravel 12 + FastAPI dengan Docker containerization. Sistem terintegrasi dengan database MySQL berisi 48.695 records transaksi NBM periode 1993-2024 sebagai data training dan inference, seperti ditampilkan pada Gambar 5.

![Gambar 5. Database transaksi NBM dengan 48.695 records historis periode 1993-2024 untuk 112 komoditas pangan](gambar-5-phpmyadmin-transaksi-nbm.png)

Proses pelatihan model menggunakan Google Colab dengan GPU acceleration. Hasil evaluasi pada Gambar 6 menunjukkan scatter plot Actual vs Predicted untuk keempat komponen model (LSTM, HuberRegressor, XGBoost, dan LSTM Enhanced Ensemble), membuktikan bahwa ensemble memberikan prediksi paling mendekati garis diagonal ideal dibandingkan model individual.

![Gambar 6. Evaluasi model menunjukkan ensemble memberikan prediksi paling akurat dibandingkan model individual (LSTM, Huber, XGBoost)](gambar-6-google-colab-training.png)

Antarmuka web terdiri dari homepage SIKOLBIA (Gambar 7) yang menyediakan akses publik dan admin untuk masuk ke sistem.

![Gambar 7. Homepage SIKOLBIA menyediakan akses publik dan panel administrasi](gambar-7-homepage.png)

Setelah login, panel seleksi modul (Gambar 8) menampilkan lima modul sistem dengan fokus pada modul Konsumsi Pangan untuk implementasi prediksi NBM.

![Gambar 8. Panel admin dengan lima modul, termasuk Konsumsi Pangan untuk prediksi NBM](gambar-8-panel-admin.png)

Dashboard admin (Gambar 9) menampilkan monitoring real-time data NBM dengan statistik sistem dan tabel data terbaru.

![Gambar 9. Dashboard admin untuk monitoring data transaksi NBM secara real-time](gambar-9-admin-dashboard.png)

Sistem manajemen data (Gambar 10) menyediakan fitur CRUD lengkap dengan filter pencarian, bulk import menggunakan template CSV, dan status verifikasi data.

![Gambar 10. Interface CRUD dengan fitur filter pencarian, bulk import, dan manajemen data lengkap](gambar-10-crud-transaksi-nbm.png)

Antarmuka prediksi (Gambar 11) memungkinkan pemilihan komoditi, jumlah bulan prediksi, dan periode data historis. Header menampilkan badge versi model aktif (v1.0.0) dan tombol update model.

![Gambar 11. Form prediksi dengan parameter komoditi, horizon forecasting, dan badge versi model aktif](gambar-11-form-prediksi.png)

Hasil prediksi (Gambar 12) ditampilkan dengan empat komponen: (1) metrik performa R²=0.9901 dan MAPE=3.73%, (2) visualisasi line chart dengan confidence interval, (3) tabel prediksi detail 3 bulan ke depan, dan (4) analisis AI-generated dengan interpretasi dan rekomendasi actionable.

![Gambar 12. Hasil prediksi menampilkan metrik R²=0.9901 dan MAPE=3.73%, visualisasi dengan confidence interval, tabel detail, dan analisis AI-generated](gambar-12-hasil-prediksi.png)

Sistem dilengkapi fitur model retraining dan version management. Modal training (Gambar 13) memungkinkan admin memilih release stage (Beta/Alpha/Production) dan memberikan deskripsi versi.

![Gambar 13. Modal training memungkinkan admin memilih release stage dan memberikan deskripsi versi model baru](gambar-13-update-model.png)

Progress training (Gambar 14) ditampilkan real-time dengan estimasi waktu menggunakan Laravel Queue background job yang mengeksekusi training script dengan GPU.

![Gambar 14. Progress training ditampilkan real-time dengan estimasi waktu menggunakan Laravel Queue background job](gambar-14-training-progress.png)

Version history (Gambar 15) menampilkan semua versi model dengan metrik lengkap (MAE: 86704, RMSE: 1788.78, MAPE: 3.73%, R²: 0.9901), memungkinkan aktivasi, promosi, atau rollback versi sesuai kebutuhan.

![Gambar 15. Version history menampilkan metrik lengkap setiap versi model dengan opsi aktivasi dan rollback](gambar-15-version-history.png)

Implementasi web berhasil mengintegrasikan model prediksi ke sistem produksi dengan response time <5 detik dan availability 99.5%. Arsitektur microservices memungkinkan continuous model improvement melalui automated retraining dan version management, memastikan model tetap akurat seiring bertambahnya data baru.

---

Penelitian berhasil membuktikan efektivitas model LSTM Enhanced Ensemble untuk prediksi konsumsi kalori komoditas pangan Indonesia. Dataset terdiri dari 47.087 samples (training: 33.007 samples periode 1993-2016, validation: 7.155 samples periode 2016-2020, test: 6.925 samples periode 2020-2024) untuk 112 komoditas yang ditransformasi menjadi 39 fitur prediktif melalui feature engineering komprehensif (lag features, moving averages, cyclical encoding, economic ratios, crisis indicators). Model ensemble menggunakan conditional strategy dengan threshold 5000: nilai kecil (<5000) menggunakan XGBoost, nilai besar (≥5000) menggunakan weighted ensemble dengan bobot LSTM 90%, XGBoost 5%, dan HuberRegressor 5% yang dioptimasi menggunakan Differential Evolution. Hasil evaluasi pada test set menunjukkan performa superior dengan MAE 867.04 kalori/hari, RMSE 1788.78 kalori/hari, MAPE 3.73%, dan R²=0.9901 yang menjelaskan 99.01% variasi konsumsi kalori, melampaui target penelitian (<10% MAPE) dengan margin 62.7%. Strategi conditional ensemble terbukti efektif dengan 50.7% test samples menggunakan XGBoost dan 49.3% menggunakan weighted ensemble. Tabel 18 menunjukkan komparasi performa keempat model pada test set, memvalidasi keunggulan LSTM Enhanced Ensemble. Model menghasilkan prediksi untuk 112 komoditas periode Januari-Maret 2025 dengan top 20 komoditas berkontribusi signifikan terhadap konsumsi kalori nasional (Tabel 19). Implementasi web menggunakan arsitektur microservices Laravel + FastAPI dengan Docker berhasil mengintegrasikan model ke sistem produksi dengan response time <5 detik, availability 99.5%, dan fitur continuous learning melalui automated model retraining yang memastikan akurasi prediksi tetap optimal.

**Tabel 18. Komparasi Performa Model pada Test Set (2020-2024)**

| Model                  | MAE (kalori/hari) | RMSE (kalori/hari) | MAPE (%) | R²     | Keterangan                            |
| ---------------------- | ----------------- | ------------------ | -------- | ------ | ------------------------------------- |
| LSTM                   | 1.403,60          | 1.800,34           | 55,46    | 0,9900 | Overfit pada pola temporal kompleks   |
| HuberRegressor         | 926,82            | 2.859,29           | 2,57     | 0,9748 | Robust terhadap outlier               |
| XGBoost                | 862,09            | 3.225,99           | 3,71     | 0,9679 | Terbaik untuk nilai kecil             |
| LSTM Enhanced Ensemble | 867,04            | 1.788,78           | 3,73     | 0,9901 | **Best Overall** - Balance MAE & RMSE |

Tabel 18 menunjukkan komparasi performa empat model pada 6.925 samples test set periode 2020-2024. LSTM Enhanced Ensemble mencapai performa terbaik dengan R² tertinggi (0.9901) dan RMSE terendah (1.788,78 kalori/hari), meskipun MAPE sedikit lebih tinggi dari XGBoost (3.73% vs 3.71%). LSTM individual menunjukkan MAPE sangat tinggi (55.46%) yang mengindikasikan overfitting pada pola temporal kompleks. HuberRegressor menghasilkan MAPE terendah (2.57%) namun RMSE tertinggi (2.859,29), menunjukkan trade-off antara robustness terhadap outlier dan akurasi prediksi absolut. Strategi conditional ensemble berhasil mengkombinasikan kelebihan XGBoost untuk nilai kecil dengan weighted ensemble untuk nilai besar, menghasilkan balance optimal antara MAE, RMSE, dan R² yang menjadikannya pilihan terbaik untuk deployment produksi.

**Tabel 19. Top 20 Prediksi Konsumsi Kalori Komoditas Januari-Maret 2025**

| Rank | Nama Komoditas | Rata-rata Konsumsi (ribu ton/bulan) | Rata-rata Kalori (kalori/kapita/hari) |
| ---- | -------------- | ----------------------------------- | ------------------------------------- |
| 1    | Beras          | 31.292,13 13.098,14                 |
| 2    | Mangga         | 65.411,90 4.559,59                  |
| 3    | Susu Impor     | 59.815,12 4.516,83                  |
| 4    | Durian         | 28.562,20 4.878,93                  |
| 5    | Salak          | 33.439,78 4.545,69                  |
| 6    | Kentang        | 41.605,92 3.721,86                  |
| 7    | Jeruk          | 61.809,29 3.375,05                  |
| 8    | Jeroan         | 24.564,41 3.427,00                  |
| 9    | Rambutan       | 27.766,97 3.130,04                  |
| 10   | Nangka         | 28.186,66 3.111,57                  |
| 11   | Susu Sapi      | 28.907,49 2.049,10                  |
| 12   | Cabai          | 43.596,65 2.026,44                  |
| 13   | Daging Sapi    | 23.397,56 6.792,31                  |
| 14   | Bawang Merah   | 29.121,75 1.353,55                  |
| 15   | Kubis          | 26.369,46 766,08                    |
| 16   | Daun Bawang    | 21.470,47 748,61                    |
| 17   | Semangka       | 20.557,28 716,70                    |
| 18   | Tomat          | 33.323,74                           | 697,01                                |
| 19   | Sawi           | 25.784,13                           | 659,18                                |
| 20   | Timun          | 22.008,15                           | 383,62                                |

Tabel 19 menampilkan 20 komoditas dengan kontribusi konsumsi kalori tertinggi hasil prediksi sistem untuk periode Januari-Maret 2025. Beras mendominasi dengan rata-rata 31.292,13 ribu ton/bulan dan 13.098,14 kalori/kapita/hari, diikuti Mangga dengan 4.559,59 kalori/hari dan Susu Impor dengan 4.516,83 kalori/hari. Seluruh 20 komoditas teratas menggunakan weighted ensemble karena nilai konsumsi >5000 threshold, memvalidasi strategi conditional ensemble yang dipilih. Pola temporal menunjukkan tren peningkatan konsumsi dari Januari ke Maret untuk mayoritas komoditas, dengan spike di Februari untuk beberapa komoditas yang dipengaruhi perbedaan jumlah hari (28 hari) dalam perhitungan kalori per hari.

Dari perspektif ilmu komputer dan informatika, penelitian ini memberikan kontribusi signifikan dalam beberapa aspek: (1) **Ensemble Learning Architecture** - memperkenalkan strategi conditional ensemble dengan threshold adaptif yang mengkombinasikan deep learning (LSTM) dan machine learning klasik (XGBoost, HuberRegressor) untuk time series forecasting pada data dengan skala heterogen, (2) **Feature Engineering Framework** - mengembangkan pipeline komprehensif 39 fitur prediktif yang mengintegrasikan temporal patterns (lag, moving average), cyclical encoding, economic indicators, dan crisis detection untuk memperkaya representasi data time series, (3) **Hyperparameter Optimization** - mengaplikasikan Differential Evolution algorithm untuk menemukan bobot ensemble optimal (LSTM 90%, XGBoost 5%, Huber 5%) yang mencapai R² 0.9901 pada test set, (4) **MLOps Implementation** - mengimplementasikan production-grade machine learning system dengan arsitektur microservices (Laravel + FastAPI), Docker containerization, automated model retraining, version management, dan continuous learning yang memastikan model sustainability, dan (5) **Web-based Prediction System** - membangun end-to-end deployment pipeline dari data preprocessing, model training, hingga RESTful API dengan response time <5 detik dan availability 99.5%, membuktikan kelayakan deep learning ensemble untuk aplikasi real-time forecasting pada domain ketahanan pangan. Metodologi dan arsitektur sistem yang dikembangkan dapat diadaptasi untuk time series prediction problems lainnya di berbagai domain seperti finance, healthcare, energy, dan climate forecasting.

---

# BAB V KESIMPULAN DAN SARAN

## 5.1 Kesimpulan

Berdasarkan hasil penelitian dengan judul "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian" dapat ditarik kesimpulan sebagai berikut:

1. Model LSTM Enhanced Ensemble dengan strategi conditional (threshold 5000) menggunakan weighted ensemble (LSTM 90%, XGBoost 5%, HuberRegressor 5%) berhasil memprediksi konsumsi kalori per kapita per hari menggunakan 39 fitur hasil feature engineering dari 47.087 samples periode 1993-2024 untuk 112 komoditas pangan, menghasilkan MAE 867.04 kalori/hari, RMSE 1788.78 kalori/hari, MAPE 3.73%, dan R²=0.9901 pada test set periode 2020-2024, melampaui target penelitian (<10% MAPE) dengan margin 62.7% dan menjelaskan 99.01% variasi konsumsi kalori.

2. Strategi conditional ensemble terbukti efektif dengan 50.7% test samples menggunakan XGBoost untuk nilai kecil (<5000) dan 49.3% menggunakan weighted ensemble untuk nilai besar (≥5000), menghasilkan prediksi untuk 112 komoditas periode Januari-Maret 2025 dengan top 20 komoditas yang didominasi Beras (13.098 kalori/hari), Mangga (4.560 kalori/hari), dan Susu Impor (4.517 kalori/hari).

3. Implementasi web menggunakan arsitektur microservices Laravel 12 + FastAPI dengan Docker containerization berhasil mengintegrasikan model ke sistem produksi SIKOLBIA dengan response time <5 detik, availability 99.5%, dan fitur continuous learning melalui automated model retraining dan version management yang memastikan akurasi prediksi tetap optimal seiring bertambahnya data baru.

4. Penelitian memberikan kontribusi di bidang ilmu komputer dan informatika melalui pengembangan conditional ensemble architecture dengan threshold adaptif, feature engineering framework 39 fitur prediktif, hyperparameter optimization menggunakan Differential Evolution, MLOps implementation untuk production-grade machine learning system, dan end-to-end web-based prediction system dengan response time <5 detik, yang dapat diadaptasi untuk time series forecasting di berbagai domain.

## 5.2 Saran

Berdasarkan hasil penelitian dengan judul "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian" dapat diberikan saran sebagai berikut:

1. Perluasan cakupan prediksi ke level regional (provinsi/kabupaten) dengan integrasi data iklim lokal, produktivitas pertanian, dan demografi wilayah untuk mendukung kebijakan distribusi pangan yang lebih targeted, mengingat model saat ini mencapai MAPE 3.73% dan R² 0.9901 pada level nasional agregat.

2. Implementasi MLOps pipeline untuk automated model retraining yang dapat mendeteksi data drift dan penurunan performa (threshold MAPE >5% atau R² <0.95), kemudian trigger retraining secara periodik (3-6 bulan) atau event-driven saat terjadi perubahan pola konsumsi signifikan, memanfaatkan infrastruktur Docker dan Laravel Queue yang sudah tersedia.

3. Pengembangan early warning system dengan threshold-based alerts untuk deteksi penurunan konsumsi >15% dalam 3 bulan berturut-turut pada komoditas strategis (top 20 dengan kontribusi kalori tertinggi), dilengkapi dashboard forecasting 12 bulan ke depan dengan confidence interval 95% dan risk assessment multi-horizon untuk mendukung perencanaan ketahanan pangan nasional.

---

# DAFTAR PUSTAKA

[1] W. Y. Alam, O. M. Ramadhona, and J. A. Dewani, "Ekonomi Politik Ketahanan Pangan: Studi Kebijakan Impor Beras," Innov. J. Soc. Sci. Res. Vol., vol. 5, no. 3, pp. 8367–8376, 2025.

[2] Tono, M. Ariani, and A. Suryana, "Kinerja Ketahanan Pangan Indonesia: Pembelajaran dari Penilaian dengan Kriteria Global dan Nasional," Anal. Kebijak. Pertan., vol. 21, no. 1, pp. 1–20, 2023, doi: 10.21082/akp.v21i1.1-20.

[3] D. Sukmawati, R. Rivaldi, and D. Mahmiludin, "Analisis Ketahanan Pangan Indonesia: Tantangan dan Strategi Berkelanjutan dalam Era Transformasi Sosial-Ekonomi," J. Innov. Res. Agric., vol. 04, no. 1, pp. 23–29, 2025.

[4] J. Mariyanto, "KRISIS GLOBAL DAN IMPLIKASINYA BAGI PERTANIAN INDONESIA: PERUBAHAN IKLIM, KONFLIK GEOPOLITIK, DAN SPEKULASI PASAR," J. Perenc. Pembang. Pertan., vol. 2, no. 1, pp. 22–43, 2025.

[5] B. P. Nasional and B. P. Statistik, "Neraca Bahan Makanan Indonesia 2022-2024," 2024.

[6] D. N. FAIZI, "Prediksi Harga Beras Di Jawa Timur Menggunakan Model Ensemble GRU – SVR Dengan Implementasi GUI," 2026.

[7] R. Novita, I. Yani, and G. Ali, "Sistem Prediksi untuk Penentuan Jumlah Pemesanan Obat Menggunakan Regresi Linier," MALCOM Indones. J. Mach. Learn. Comput. Sci., vol. 2, no. 1, pp. 62–70, 2022, doi: 10.57152/malcom.v2i1.198.

[8] I. Amansyah, J. Indra, E. Nurlaelasari, and A. R. Juwita, "Prediksi Penjualan Kendaraan Menggunakan Regresi Linear: Studi Kasus pada Industri Otomotif di Indonesia," Innov. J. Soc. Sci. Res., vol. 4, no. 4, pp. 1199–1216, 2024.

[9] D. Arifuddin, K. Kusrini, and K. Kusnawi, "Perbandingan Performansi Algoritma Multiple Linear Regression dan Multi Layer Perceptron Neural Network dalam Memprediksi Penjualan Obat," MALCOM Indones. J. Mach. Learn. Comput. Sci., vol. 5, no. 2, pp. 722–737, 2025, doi: 10.57152/malcom.v5i2.1952.

[10] I. K. K. Kanaya, B. E. I. Sitanggang, and N. L. P. L. S. Setiawati, "PERAMALAN KEDATANGAN KAPAL PESIAR MENGGUNAKAN METODE SINGLE EXPONENTIAL SMOOTHING PADA PT . PELABUHAN INDONESIA PERAMALAN KEDATANGAN KAPAL PESIAR MENGGUNAKAN METODE SINGLE EXPONENTIAL SMOOTHING PADA PT . PELABUHAN INDONESIA," J. MEDIA Akad., vol. 3, no. 11, 2025.

[11] A. Zamahzari and Puryantoro, "Forecasting Produksi Padi dan Konsumsi Beras di Provinsi Jawa Timur," CEMARA, vol. 20, no. 1, pp. 27–38, 2023.

[12] E. Agustina, S. Supartiningsih, and B. R. A. Febrilia, "Peramalan Indeks Harga Konsumen Kelompok Makanan di Kota Mataram Menggunakan Metode ARIMA dan Double Exponential Smoothing," Agroteksos, vol. 30, no. 2, pp. 1–11, 2024.

[13] Tempo.co, "Sri Mulyani: Rp 99 Triliun Disiapkan untuk Ketahanan Pangan 2021," Tempo.co, 2020.

[14] R. A. Sirait, R. Paramita, R. T. Kusumawardhani, and T. Riyono, "Evaluasi Subsidi Pupuk dan Rencana Bantuan Langsung," 2024.

[15] H. Rusanto and S. Soekirno, "Performance Comparison of 1D-CNN and LSTM Deep Learning Models for Time Series-Based Electric Power Prediction," ELKOMIKA, vol. 13, no. 1, pp. 44–56, 2025.

[16] H. T. A. Simanjuntak, A. Lumbanraja, G. Samosir, and Regita, "Prediksi Single-Step dan Multi-Step Data Cuaca Menggunakan Model Long Short-Term Memory dan Sarima," J. Teknol. Inf. dan Ilmu Komput., vol. 12, no. 2, pp. 399–410, 2025, doi: 10.25126/jtiik.2025129444.

[17] B. Wang, A. Bin, and M. Zain, "A Hybrid XGBoost-LSTM Framework for Supply Chain Demand Forecasting : Empirical Evidence from Retail Multi-Store Data," J. Comput. Appl. Stat. Consult., vol. 10, no. 4, pp. 4056–4073, 2025.

[18] A. Wiejaya and I. Fenriana, "Prediksi Harga Saham Top 10 NASDAQ dengan Time Series Prophet," bit-Tech, vol. 7, no. 2, pp. 252–262, 2024, doi: 10.32877/bt.v7i2.1736.

[19] W. Wu, M. Zhang, X. Min, X. Zhang, L. Zhuang, and J. Li, "Automated Model Management for Microservices: A CI/CD Approach," Comput. Fraud Secur., pp. 34–43, 2024, doi: 10.52710/cfs.87.

[20] M. Z. F. Kamil, R. Purnamasari, and Y. Eliskar, "Perancangan Sistem Deploy Untuk Menghubungkan Machine learning Ke Website," e-Proceeding Eng., vol. 11, no. 6, pp. 6394–6396, 2024.

[21] D. Y. Suhaedah, U. Syamsudin, and T. M. Mazya, "Strategi dan Kebijakan Ketahanan Pangan di Kabupaten Tangerang," J. Multiling., vol. 3, no. 4, pp. 110–125, 2023.

[22] Y. V. Lestari, T. P. E. Sanubari, and F. A. Wijaya, "Akses Pangan Rumah Tangga Petani pada Kelompok Tani Qaryah Thayyibah di Kota Salatiga," Amerta Nutr., vol. 6, no. 1, p. 72, 2022, doi: 10.20473/amnt.v6i1.2022.72-81.

[23] A. Valentina, M. I. Fianty, T. R. Mashur, and K. A. Laurenzia, "Dashboard Visualization of Food Security in Indonesia: Addressing Challenges of Food Distribution and Access," J. Ilm. Inform. Glob., vol. 15, no. 2, pp. 67–74, 2024, doi: 10.36982/jiig.v15i2.4168.

[24] A. M. Muhammad, D. M. Napitupulu, S. Suandi, and T. S. Putra, "Keterkaitan Lahan Pangan dengan Neraca Bahan Makanan dan Pola Pangan Harapan Kota Jambi," J. Ilm. Univ. Batanghari Jambi, vol. 22, no. 2, p. 858, 2022, doi: 10.33087/jiubj.v22i2.1987.

[25] B. Khaw, R. Irwanto, R. Yunis, and E. Elly, "Analisis Time Series dan Perancangan Dashboard untuk Memprediksi Penjualan dengan Metode Prophet dan SARIMAX," J. Sifo Mikroskil, vol. 26, no. 2, 2025, doi: 10.55601/jsm.v26i2.1797.

[26] J. Vernando, F. Insani, Okfalisa, and F. Kurnia, "Application of ARIMA and ARIMAX Method to Predict the Number of Visitors to Hotel XYZ Pekanbaru," J. Inovtek Polbeng - Seri Inform., vol. 10, no. 2, pp. 847–856, 2025.

[27] Risanti, W. Indrasari, and H. Suhendar, "Analisis Model Prediksi Cuaca Menggunakan Support Vector Machine, Gradient Boosting, Random Forest, Dan Decision Tree," Pros. Semin. Nas. Fis., vol. XII, pp. 119–128, 2024, doi: 10.21009/03.1201.fa18.

[28] Y. Ashari and A. Suhendar, "Implementasi Algoritma Long Short-Term Memory (Lstm) Untuk Memprediksi Harga Beras Di Jawa Tengah Berdasarkan Cuaca," Djtechno J. Teknol. Inf., vol. 5, no. 3, pp. 624–636, 2024, doi: 10.46576/djtechno.v5i3.5136.

[29] R. Fajar and M. Fachrie, "Implementasi Algoritma Long Short-Term Memory (LSTM) pada Sistem Prediksi Hasil Panen Sawit," J. Inform. Teknol. dan Sains, vol. 6, no. 5, pp. 937–944, 2024.

[30] E. Pramudya, D. Retnoningsih, and D. Ruswanti, "Implementasi Metode LSTM untuk Prediksi Harga Saham PT Indofood CBP Sukses Makmur TBK," J. Nas. Teknol. Inf. dan Apl., vol. 3, no. 4, pp. 933–940, 2025.

[31] N. Fadhil, "Perbandingan Akurasi Algoritma Xgboost Dan Svr Dalam Prediksi Harga Cryptocurrency," J. Ilmu Komput. dan Sist. Inf., vol. 13, no. 1, 2025, doi: 10.24912/jiksi.v13i1.32865.

[32] E. Fammaldo, M. Lestari, and C. Hermawan, "Gradient Boosting Trees untuk Pemodelan dan Prediksi Biaya Kerugian Asuransi Mobil," J. Algoritm. Log. dan Komputasi, vol. 7, no. 01, pp. 634–642, 2024.

[33] S. Davies Raihannabil, "Perbandingan Regresi Robust dengan M, S, dan MM-Estimator untuk Menganalisis Faktor-Faktor yang Memengaruhi Indeks Pemberdayaan Gender di Nusa Tenggara Barat Tahun 2023," J. EKSPONENSIAL, vol. 16, no. 1, pp. 10–22, 2025, doi: 10.30872/eksponensial.v16i1.1389.

[34] A. A. Dewayanti and H. Utami, "Estimasi Robust pada Model Regresi untuk Menangani Outlier dan Heteroskedastisitas," J. Mat. Thales, vol. 3, no. 1, pp. 1–12, 2021.

[35] C. Swastikawati and E. Utami, "Prediksi Kelulusan Calon Mahasiswa dengan Stacking Ensemble Learning," Sistemasi, vol. 14, no. 6, p. 2707, 2025, doi: 10.32520/stmsi.v14i6.5535.

[36] M. Ibnu Choldun Rachmatullah, "Ensemble Learning untuk Klasifikasi: Tinjauan Komprehensif Metode, Aplikasi, dan Perkembangan Terkini," Improv. (Universitas Logistik dan Bisnis Internasional), vol. 17, no. 1, pp. 29–31, 2025.

[37] D. Romeo, "Implementasi Algoritma Differential Evolution untuk Pencarian Solusi Optimal Global Beberapa Fungsi Differentiable dan Non-Differentiable," 2023.

[38] M. D. Salman, N. R. Pratama, and M. N. F. A, "Comparison of K-Means and K-Medoids Clustering Algorithm Performance in Grouping Schools in Riau Province," MALCOM Indones. J. Mach. Learn. Comput. Sci., vol. 5, no. July, pp. 797–806, 2025.

[39] L. Budiarti, Tarno, and B. Warsito, "Analisis Intervensi dan Deteksi Outlier pada Data Wisatawan Domestik (Studi Kasus di Daerah Istimewa Yogyakarta)," J. Gaussian, vol. 2, no. 1, pp. 39–48, 2013, [Online]. Available: https://ejournal3.undip.ac.id/index.php/gaussian/article/view/2742

[40] I. Fauzi, L. Muflikhah, and P. P. Adikara, "Deteksi Mutasi Epidermal Growth Factor Receptor pada Kanker Paru Menggunakan Extreme Gradient Boosting," J. Pengemb. Teknol. Inf. dan Ilmu Komput., vol. 9, no. 10, pp. 2548–964, 2025, [Online]. Available: http://j-ptiik.ub.ac.id

[41] H. Supriyanto, "Perbandingan Metode Supervised Learning untuk Peramalan Time Series pada Kunjungan Pasien Rawat Jalan," J. Simantec, vol. 10, no. 2, pp. 67–76, 2022, doi: 10.21107/simantec.v10i2.14010.

[42] R. Agung and P. Paramitha, "Perbandingan Performa Algoritma Gaussian Naive Bayes dan Decision Tree Classifier dalam Klasifikasi Prompt AI-Generated Image," J. Comput. Eng. Syst. Sci., vol. 10, no. 1, pp. 299–311, 2025.

[43] W. Nugraha, R. Sabaruddin, and S. Murni, "Teknik Scaling Menggunakan Robust Scaler Untuk Mengatasi Outlier Data Pada Model Prediksi Serangan Jantung," Techno.Com, vol. 23, no. 2, pp. 319–327, 2024, doi: 10.62411/tc.v23i2.10463.

[44] V. Yoga Pudya Ardhana, S. Lonang, D. Tejo Kumoro, and M. Dermawan Mulyodiputro, "Benchmarking Model Machine Learning untuk Prediksi Data Berdasarkan Akurasi dan Error," SainsTech Innov. J., vol. 2, no. 8, pp. 568–577, 2025.

[45] B. Siregar, F. A. Pangruruk, and S. P. Barus, "Perbandingan Model Runtun Waktu dan Prediksi Jumlah Kasus COVID-19 di Indonesia," Syntax Idea, vol. 4, no. 8, 2022.
