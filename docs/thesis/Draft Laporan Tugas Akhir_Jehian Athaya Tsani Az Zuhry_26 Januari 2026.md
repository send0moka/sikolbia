# IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**LAPORAN TUGAS AKHIR**

**SKRIPSI**

Diajukan Sebagai Pedoman Pelaksanaan Penelitian Tugas Akhir  
pada Jurusan Informatika Fakultas Teknik Universitas Jenderal Soedirman

Disusun Oleh:  
**JEHIAN ATHAYA TSANI AZ ZUHRY**  
**H1D022006**

KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI  
UNIVERSITAS JENDERAL SOEDIRMAN  
FAKULTAS TEKNIK  
JURUSAN INFORMATIKA  
PURWOKERTO  
2026

---

## LEMBAR PENGESAHAN

**LAPORAN TUGAS AKHIR**

**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

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

## LEMBAR PERNYATAAN KEASLIAN SKRIPSI

Saya yang bertanda tangan dibawah ini:

NIM : H1D022006  
Nama : Jehian Athaya Tsani Az Zuhry  
Program Studi : Informatika

Dengan ini saya menyatakan bahwa skripsi yang saya buat dengan judul:

**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

merupakan hasil karya sendiri dan tidak mengandung karya yang pernah diajukan oleh pihak lain untuk memperoleh gelar akademik di perguruan tinggi mana pun. Sepanjang pengetahuan saya, tidak terdapat pendapat atau karya orang lain yang telah dipublikasikan atau dituliskan sebelumnya, kecuali yang secara jelas dicantumkan sebagai rujukan dan dinyatakan dalam daftar pustaka.

Demikian pernyataan ini saya buat dengan sebenar-benarnya tanpa ada paksaan dari pihak manapun juga. Apabila dikemudian hari terbukti bahwa saya memberikan pernyataan palsu, maka saya bersedia menerima sanksi yang telah ditetapkan.

Purwokerto, 19 Januari 2026

Jehian Athaya Tsani Az Zuhry

---

## KATA PENGANTAR

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

Purwokerto, 19 Januari 2026

Jehian Athaya Tsani Az Zuhry

---

## PERSEMBAHAN

Segala puji dan syukur dipanjatkan ke hadirat Allah SWT atas limpahan rahmat, hidayah, dan karunia-Nya yang senantiasa menyertai setiap langkah dalam penyelesaian tugas akhir ini. Shalawat serta salam semoga selalu tercurah kepada junjungan kita Nabi Muhammad SAW, yang telah membawa umat manusia dari masa kegelapan menuju cahaya ilmu dan kebenaran.

Dengan penuh rasa hormat dan terima kasih, tugas akhir ini dipersembahkan kepada kedua orang tua tercinta, Bapak Fahrudin Juhri dan Ibu Khotimah Rahayuningsih, yang senantiasa memberikan doa, kasih sayang, dan dukungan tanpa henti. Terima kasih juga disampaikan kepada orang tercinta, Pasa Kholilah, yang selalu memberikan semangat dan motivasi.

Ucapan terima kasih turut disampaikan kepada sahabat-sahabat yang telah memberikan bantuan, dukungan, serta doa sehingga tugas akhir ini dapat diselesaikan dengan baik.

---

## MOTTO

"Maka sesungguhnya bersama kesulitan ada kemudahan. Sesungguhnya bersama kesulitan ada kemudahan."

Al-Qur'an, Surah Al-Insyirah ayat 5-6

"Setiap kesulitan membawa hikmah, dan kesabaran adalah kunci untuk melewatinya."

---

## DAFTAR ISI

- [LEMBAR PENGESAHAN](#lembar-pengesahan) ........................................................................ i
- [LEMBAR PERNYATAAN KEASLIAN SKRIPSI](#lembar-pernyataan-keaslian-skripsi) .............................. ii
- [KATA PENGANTAR](#kata-pengantar) .............................................................................................. iii
- [PERSEMBAHAN](#persembahan) ................................................................................................... v
- [MOTTO](#motto) ...................................................................................................................... vi
- [DAFTAR ISI](#daftar-isi) ............................................................................................................. vii
- [DAFTAR GAMBAR](#daftar-gambar) ............................................................................................. x
- [DAFTAR TABEL](#daftar-tabel) ................................................................................................... xi
- [DAFTAR PERSAMAAN](#daftar-persamaan) .................................................................................. xii
- [DAFTAR LISTING PROGRAM](#daftar-listing-program) ................................................................. xiii
- [DAFTAR LAMPIRAN](#daftar-lampiran) ....................................................................................... xiv
- [ABSTRAK](#abstrak) ................................................................................................................. xv
- [ABSTRACT](#abstract) .............................................................................................................. xvi

### BAB I PENDAHULUAN
- [1.1 Latar Belakang](#11-latar-belakang) ........................................................................................ 1
- [1.2 Rumusan Masalah](#12-rumusan-masalah) .............................................................................. 2
- [1.3 Batasan Masalah](#13-batasan-masalah) ................................................................................. 3
- [1.4 Tujuan Penelitian](#14-tujuan-penelitian) ................................................................................ 3
- [1.5 Manfaat Penelitian](#15-manfaat-penelitian) ........................................................................... 4

### BAB II TINJAUAN PUSTAKA
- [2.1 Time Series Forecasting](#21-time-series-forecasting) ............................................................. 6
- [2.2 Long Short Term Memory](#22-long-short-term-memory) ....................................................... 6
- [2.3 Robust Regression](#23-robust-regression) ............................................................................. 7
- [2.4 Metode Ensemble](#24-metode-ensemble) .............................................................................. 8
- [2.5 Feature Engineering](#25-feature-engineering) ....................................................................... 9
- [2.6 Data Preprocessing](#26-data-preprocessing) .......................................................................... 9
- [2.7 Evaluasi Model](#27-evaluasi-model) .................................................................................... 10
- [2.8 Arsitektur Microservices](#28-arsitektur-microservices) ........................................................ 11
- [2.9 Penelitian Sejenis](#29-penelitian-sejenis) ............................................................................. 12

### BAB III METODE PENELITIAN
- [3.1 Waktu dan Tempat Penelitian](#31-waktu-dan-tempat-penelitian) ........................................ 14
- [3.2 Data dan Alat Penelitian](#32-data-dan-alat-penelitian) ........................................................ 14
  - [3.2.1 Data Penelitian](#321-data-penelitian) ............................................................................ 14
  - [3.2.2 Alat Penelitian](#322-alat-penelitian) .............................................................................. 16
  - [3.2.3 Teknologi dan Framework yang digunakan](#323-teknologi-dan-framework-yang-digunakan) ... 17
- [3.3 Tahapan Penelitian](#33-tahapan-penelitian) ......................................................................... 18

### BAB IV HASIL DAN PEMBAHASAN
- [4.1 Pemahaman Bisnis](#41-pemahaman-bisnis) ......................................................................... 23
  - [4.1.1 Analisis Kondisi Ketahanan Pangan Indonesia](#411-analisis-kondisi-ketahanan-pangan-indonesia) ... 23
  - [4.1.2 Identifikasi Kebutuhan Stakeholder dan Kriteria Keberhasilan Model](#412-identifikasi-kebutuhan-stakeholder-dan-kriteria-keberhasilan-model) ... 29
- [4.2 Pemahaman Data](#42-pemahaman-data) .............................................................................. 30
  - [4.2.1 Karakteristik Kumpulan Data NBM Indonesia](#421-karakteristik-kumpulan-data-nbm-indonesia) ... 30
  - [4.2.2 Analisis Statistik Deskriptif](#422-analisis-statistik-deskriptif) ....................................... 32
  - [4.2.3 Analisis Distribusi Konsumsi Kalori](#423-analisis-distribusi-konsumsi-kalori) ............... 34
  - [4.2.4 Identifikasi Pola Temporal dan Anomali](#424-identifikasi-pola-temporal-dan-anomali) ... 37
  - [4.2.5 Analisis Missing Values dan Outliers](#425-analisis-missing-values-dan-outliers) .......... 39
- [4.3 Persiapan Data](#43-persiapan-data) ..................................................................................... 41
  - [4.3.1 Data Cleaning dan Preprocessing](#431-data-cleaning-dan-preprocessing) ..................... 42
  - [4.3.2 Feature Engineering](#432-feature-engineering) ............................................................. 44
  - [4.3.3 Feature Scaling dan Normalisasi](#433-feature-scaling-dan-normalisasi) ........................ 47
  - [4.3.4 Pembagian Data](#434-pembagian-data) ......................................................................... 49
- [4.4 Pemodelan](#44-pemodelan) .................................................................................................. 50
  - [4.4.1 Implementasi Model XGBoost](#441-implementasi-model-xgboost) ................................ 51
  - [4.4.2 Implementasi Model LSTM](#442-implementasi-model-lstm) .......................................... 52
  - [4.4.3 Implementasi Model HuberRegressor](#443-implementasi-model-huberregressor) .......... 56
  - [4.4.4 Implementasi Model LSTM Enhanced Ensemble](#444-implementasi-model-lstm-enhanced-ensemble) ... 58
  - [4.4.5 Perhitungan Manual Prediksi](#445-perhitungan-manual-prediksi) ................................. 62
- [4.5 Evaluasi](#45-evaluasi) .......................................................................................................... 92
  - [4.5.1 Metrik Evaluasi Model](#451-metrik-evaluasi-model) ..................................................... 92
  - [4.5.2 Hasil Evaluasi Model XGBoost](#452-hasil-evaluasi-model-xgboost) .............................. 93
  - [4.5.3 Hasil Evaluasi Model LSTM](#453-hasil-evaluasi-model-lstm) ........................................ 94
  - [4.5.4 Hasil Evaluasi Model HuberRegressor](#454-hasil-evaluasi-model-huberregressor) ........ 94
  - [4.5.5 Hasil Evaluasi Model LSTM Enhanced Ensemble](#455-hasil-evaluasi-model-lstm-enhanced-ensemble) ... 95
  - [4.5.6 Perbandingan Performa Keempat Model](#456-perbandingan-performa-keempat-model) ... 96
- [4.6 Penyebaran](#46-penyebaran) ................................................................................................ 97
  - [4.6.1 Arsitektur Sistem Informasi Berbasis Web](#461-arsitektur-sistem-informasi-berbasis-web) ... 98
  - [4.6.2 Implementasi Backend dengan FastAPI](#462-implementasi-backend-dengan-fastapi) ..... 98
  - [4.6.3 Implementasi Frontend dengan Laravel](#463-implementasi-frontend-dengan-laravel) .... 98
  - [4.6.4 Containerization dengan Docker](#464-containerization-dengan-docker) ........................ 98
  - [4.6.5 Pengujian Sistem](#465-pengujian-sistem) ...................................................................... 98
- [4.7 Pembahasan](#47-pembahasan) ............................................................................................. 98
  - [4.7.1 Analisis Hasil Penelitian](#471-analisis-hasil-penelitian) ................................................ 98
  - [4.7.2 Kelebihan dan Keterbatasan Model](#472-kelebihan-dan-keterbatasan-model) ............... 98
  - [4.7.3 Implikasi untuk Ketahanan Pangan Nasional](#473-implikasi-untuk-ketahanan-pangan-nasional) ... 98
  - [4.7.4 Perbandingan dengan Penelitian Sejenis](#474-perbandingan-dengan-penelitian-sejenis) ... 98

### BAB V KESIMPULAN DAN SARAN ........................................................................................ 99

### DAFTAR PUSTAKA ............................................................................................................. 100

### LAMPIRAN ......................................................................................................................... 101

### BIODATA ............................................................................................................................ 102

---

## DAFTAR GAMBAR

_[Daftar gambar akan diisi sesuai dengan gambar-gambar yang ada dalam laporan]_

---

## DAFTAR TABEL

_[Daftar tabel akan diisi sesuai dengan tabel-tabel yang ada dalam laporan]_

---

## DAFTAR PERSAMAAN

_[Daftar persamaan akan diisi sesuai dengan persamaan-persamaan yang ada dalam laporan]_

---

## DAFTAR LISTING PROGRAM

_[Daftar listing program akan diisi sesuai dengan kode program yang ada dalam laporan]_

---

## DAFTAR LAMPIRAN

_[Daftar lampiran akan diisi sesuai dengan lampiran-lampiran yang ada dalam laporan]_

---

## ABSTRAK

**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

**JEHIAN ATHAYA TSANI AZ ZUHRY**  
**H1D022006**

Ketahanan pangan merupakan isu kritis bagi Indonesia dengan peringkat ke 69 dari 113 negara pada Global Food Security Index 2024. Metode prediksi konvensional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola temporal konsumsi pangan. Penelitian ini mengembangkan model machine learning untuk memprediksi konsumsi kalori per kapita berdasarkan data Neraca Bahan Makanan (NBM) Indonesia periode 1994-2024.

Metodologi penelitian menggunakan framework Cross-Industry Standard Process for Data Mining (CRISP-DM). Data historis NBM sebanyak 48.696 baris data mencakup 112 komoditas diproses menjadi kumpulan data dengan 39 fitur prediktif meliputi lag features, moving averages, temporal patterns, dan indikator ekonomi. Data dibagi secara kronologis menjadi data latih (1994-2016), data validasi (2017-2020), dan data uji (2021-2024).

Evaluasi dilakukan terhadap empat arsitektur model: XGBoost mencapai MAPE 16.00% dengan R² 0.8133, LSTM mencapai MAPE 52.08% dengan R² 0.5411 menunjukkan keterbatasan machine learning pada data sparse, HuberRegressor mencapai MAPE 10.76% dengan R² 0.7572, dan LSTM Enhanced Ensemble sebagai konfigurasi optimal. LSTM Enhanced Ensemble menggabungkan kekuatan sequence modeling dengan robustness regression mencapai MAPE 9.32% dengan R² 0.7938, melampaui target <10% dan menjelaskan 79.38% variasi konsumsi kalori.

Model divalidasi dengan evaluasi historis dan forecasting praktis seperti prediksi peningkatan Beras (+5.4%) dan Minyak Goreng Sawit (+5.8%) untuk Januari 2025. Model diintegrasikan ke dalam sistem informasi berbasis web menggunakan arsitektur microservices dengan Laravel 11, FastAPI, dan Docker. Hasil menunjukkan bahwa robust regression-based ensemble lebih efektif daripada machine learning untuk data NBM dengan karakteristik sparse dan volatilitas tinggi, serta pentingnya split berdasarkan kronologi untuk validasi time series forecasting.

**Kata Kunci:** Ketahanan Pangan, LSTM, Machine Learning, Prediksi Konsumsi Kalori, Neraca Bahan Makanan, Time Series Forecasting

---

## ABSTRACT

**IMPLEMENTATION OF LSTM FOR DAILY CALORIE CONSUMPTION PREDICTION BASED ON MINISTRY OF AGRICULTURE FOOD BALANCE SHEET DATA**

**JEHIAN ATHAYA TSANI AZ ZUHRY**  
**H1D022006**

Food security is a critical issue for Indonesia, ranked 69th out of 113 countries in the Global Food Security Index 2024. Conventional prediction methods have limited accuracy (MAPE 15-20%) and fail to capture the complexity of temporal patterns in food consumption. This study develops a machine learning model to predict calorie consumption per capita based on Indonesia's Food Balance Sheet (FBS) data from 1994-2024.

The research methodology employs the Cross-Industry Standard Process for Data Mining (CRISP-DM) framework. Historical FBS data comprising 48,696 data rows covering 112 commodities was processed into a dataset with 39 predictive features including lag features, moving averages, temporal patterns, and economic indicators. Data was chronologically divided into training set (1994-2016), validation set (2017-2020), and test set (2021-2024).

Evaluation was conducted on four model architectures: XGBoost achieved MAPE 16.00% with R² 0.8133, LSTM achieved MAPE 52.08% with R² 0.5411 demonstrating machine learning limitations on sparse data, HuberRegressor achieved MAPE 10.76% with R² 0.7572, and LSTM Enhanced Ensemble as the optimal configuration. LSTM Enhanced Ensemble combines the strength of sequence modeling with regression robustness, achieving MAPE 9.32% with R² 0.7938, surpassing the <10% target and explaining 79.38% of calorie consumption variation.

The model was validated through historical evaluation and practical forecasting such as predicting increases in Rice (+5.4%) and Palm Cooking Oil (+5.8%) for January 2025. The model was integrated into a web-based information system using microservices architecture with Laravel 11, FastAPI, and Docker. Results demonstrate that robust regression-based ensemble is more effective than machine learning for FBS data with sparse characteristics and high volatility, as well as the importance of chronological split for time series forecasting validation.

**Keywords:** Food Security, LSTM, Machine Learning, Calorie Consumption Prediction, Food Balance Sheet, Time Series Forecasting

---

## BAB I PENDAHULUAN

### 1.1 Latar Belakang

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik Indonesia. Data Global Food Security Index (GFSI) 2024 menunjukkan Indonesia menempati peringkat ke 69 dari 113 negara dengan skor 59,2, posisi yang masih tertinggal dibandingkan negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Sekretariat Jendral Kementrian Pertanian, 2024). Dengan populasi lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan berkelanjutan yang diperparah oleh perubahan iklim dan volatilitas harga pangan (BPS, 2023).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak untuk mendukung perencanaan ketahanan pangan nasional. Metode prediksi konvensional yang saat ini digunakan memiliki akurasi terbatas dengan MAPE dalam rentang 15-20% berdasarkan hasil evaluasi penelitian sejenis menggunakan Linear Regression dan Single Exponential Smoothing (Novita et al., 2022), serta tidak mampu menangkap kompleksitas pola temporal konsumsi pangan yang dipengaruhi oleh faktor musiman, krisis ekonomi, dan perubahan iklim (Sarku et al., 2023). Ketidakakuratan prediksi berdampak pada inefisiensi alokasi anggaran ketahanan pangan, sebagaimana tercermin dari penurunan alokasi APBN untuk ketahanan pangan dari Rp 99 triliun (2021) menjadi Rp 92,2 triliun (2022), sementara kebutuhan subsidi pupuk meningkat menjadi Rp 25,3 triliun akibat volatilitas harga global, dan Nilai Tukar Petani (NTP) mengalami penurunan 0,76% pada April 2022 yang mengindikasikan kerugian ekonomi di tingkat petani (Pertanian, 2022).

Data Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 dengan 48.696 baris data menunjukkan volatilitas konsumsi kalori yang kompleks, dengan fluktuasi dari 2.156 kkal/kapita/hari hingga 2.978 kkal/kapita/hari (Sekretariat Jendral - Kementrian Pertanian, 2024). Kumpulan data historis ini mencakup 112 komoditas dari 10 kelompok dengan parameter produksi, impor, ekspor, dan konsumsi kalori per kapita per hari yang memberikan pondasi untuk analisis prediktif.

Long Short Term Memory (LSTM) sebagai varian Recurrent Neural Network telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long term dependencies dan pola musiman kompleks (Alkahfi et al., 2024). Metode ensemble yang mengintegrasikan LSTM dengan robust regression algorithms menunjukkan peningkatan akurasi hingga 25-30% dibandingkan model tunggal (Howard & Augustine, 2025). Namun, belum ada penelitian yang menggunakan LSTM dengan metode ensemble untuk prediksi konsumsi kalori agregat nasional berdasarkan data NBM Indonesia yang komprehensif.

Berdasarkan latar belakang di atas, penulis mengusulkan implementasi LSTM Enhanced Ensemble untuk prediksi konsumsi kalori harian nasional guna mengatasi keterbatasan metode konvensional dan memberikan sistem peringatan dini berbasis machine learning yang akurat untuk mendukung pengambilan keputusan dalam perencanaan ketahanan pangan Indonesia.

### 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana mengimplementasikan arsitektur model LSTM Enhanced Ensemble dengan hyperparameter optimal, teknik robust preprocessing (StandardScaler dan RobustScaler), sequence generation yang tepat, dan evaluasi metrik RMSE, MAE, MAPE untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%?

2. Bagaimana mengintegrasikan model LSTM Enhanced Ensemble yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi?

### 1.3 Batasan Masalah

Berikut adalah batasan masalah yang ditetapkan agar penelitian ini tetap terarah dan terfokus:

1. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan total 48.696 baris data yang mencakup 112 komoditas dari 10 kelompok, bersumber dari Pusat Data dan Sistem Informasi Kementerian Pertanian.

2. Prediksi yang dibuat terbatas pada konsumsi kalori harian agregat nasional per komoditi untuk mendukung pengambilan keputusan pemerintah, dengan sistem informasi berbasis web ditujukan untuk stakeholder pemerintah (Kementerian Pertanian) menggunakan arsitektur Laravel, FastAPI, dan Docker.

3. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi dengan target MAPE < 10% berdasarkan standar industri, dan tidak mencakup pengembangan aplikasi mobile, personal health tracking, atau prediksi regional.

### 1.4 Tujuan Penelitian

Fokus utama penelitian ini dapat diuraikan sebagai berikut:

1. Mengimplementasikan dan mengevaluasi model LSTM Enhanced Ensemble untuk prediksi konsumsi kalori harian agregat nasional per komoditi menggunakan data historis NBM Indonesia periode 1993-2024, dengan target akurasi MAPE < 10% yang diukur melalui metrik RMSE, MAE, dan MAPE.

2. Melakukan preprocessing dan feature engineering pada kumpulan data NBM 48.696 baris data menggunakan StandardScaler dan RobustScaler dengan teknik preprocessing berbasis urutan waktu yang aman secara temporal untuk mencegah data leakage dan optimalisasi performa model.

3. Mengintegrasikan model LSTM Enhanced Ensemble ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan prediksi konsumsi kalori yang mendukung keputusan sistem ketahanan pangan nasional.

### 1.5 Manfaat Penelitian

Berikut adalah manfaat yang diharapkan dari penelitian ini bagi peneliti, pembaca, dan pemerintah serta masyarakat:

#### 1. Bagi Peneliti

Penelitian ini memberikan pengalaman praktis dalam penerapan algoritma LSTM untuk prediksi time series konsumsi pangan, sekaligus mengembangkan keterampilan dalam implementasi machine learning dan pengembangan sistem informasi terintegrasi dengan arsitektur microservices. Selain itu, hasil penelitian ini diharapkan dapat menjadi referensi untuk penelitian atau proyek serupa di masa depan.

#### 2. Bagi Pembaca

Penelitian ini memberikan wawasan mengenai penerapan algoritma LSTM dalam prediksi konsumsi kalori berbasis data NBM dan menyajikan informasi yang bermanfaat bagi akademisi dan praktisi yang ingin mengembangkan sistem prediksi ketahanan pangan.

#### 3. Bagi Pemerintah dan Masyarakat

Penelitian ini membantu pengambil kebijakan dalam perencanaan ketahanan pangan nasional melalui sistem peringatan dini berbasis machine learning dengan akurasi tinggi. Sistem menyediakan sistem peringatan dini untuk antisipasi krisis pangan dan mendukung transparansi informasi prediksi konsumsi nasional untuk meningkatkan kesadaran publik tentang ketahanan pangan Indonesia.

---

## BAB II TINJAUAN PUSTAKA

### 2.1 Time Series Forecasting

Machine learning telah mengalami perkembangan signifikan dalam aplikasi prediksi time series, khususnya untuk forecasting konsumsi pangan dan analisis ketahanan pangan. Penelitian [1] membandingkan performa berbagai algoritma machine learning untuk prediksi permintaan pangan di China, menemukan bahwa metode ensemble mengungguli single model dengan peningkatan akurasi 18-25%. Pemilihan fitur prediktor dalam forecasting konsumsi pangan mencakup lag features (1, 3, 6, 12 bulan), moving averages dengan berbagai window sizes, temporal patterns menggunakan cyclical encoding untuk musiman, dan indikator supply chain seperti produksi, impor, ekspor, dan perubahan stok. Penelitian [2] mengidentifikasi bahwa data konsumsi pangan memiliki volatilitas tinggi dengan periode anomali seperti krisis ekonomi dan pandemi yang memerlukan penanganan khusus melalui indikator krisis dan teknik robust regression untuk menghindari overfitting pada peristiwa mendadak.

### 2.2 Long Short Term Memory

Long Short Term Memory (LSTM) merupakan arsitektur khusus dari Recurrent Neural Network (RNN) yang dikembangkan untuk mengatasi masalah vanishing gradient pada RNN tradisional. LSTM memiliki kemampuan unik dalam mempelajari long term dependencies melalui mekanisme gating yang terdiri dari forget gate, input gate, dan output gate. Arsitektur LSTM dapat direpresentasikan dengan persamaan (1) hingga (6) berikut:

$$f_t = \sigma(W_f \cdot [h_{t-1}, x_t] + b_f) \quad (1)$$

$$i_t = \sigma(W_i \cdot [h_{t-1}, x_t] + b_i) \quad (2)$$

$$\tilde{C}_t = \tanh(W_c \cdot [h_{t-1}, x_t] + b_C) \quad (3)$$

$$C_t = f_t \cdot C_{t-1} + i_t \cdot \tilde{C}_t \quad (4)$$

$$o_t = \sigma(W_o \cdot [h_{t-1}, x_t] + b_o) \quad (5)$$

$$h_t = o_t \cdot \tanh(C_t) \quad (6)$$

Di mana $f_t$, $i_t$, dan $o_t$ adalah forget, input, dan output gates; $C_t$ adalah cell state; $h_t$ adalah hidden state; $\sigma$ adalah fungsi sigmoid; dan $W$, $b$ adalah weight matrices dan bias vectors.

Penelitian [3] mengembangkan arsitektur LSTM multi layer dengan BatchNormalization dan dropout untuk prediksi harga komoditas pangan Indonesia, mencapai MAPE 8.5%. Studi empiris [4] pada data konsumsi pangan menunjukkan bahwa sequence length 6 timesteps memberikan keseimbangan optimal antara informasi konteks dan efisiensi komputasi untuk data bulanan, dengan penggunaan recurrent dropout sebesar 0.15-0.20 efektif mencegah overfitting tanpa mengorbankan kemampuan model dalam mempelajari pola temporal. Ilustrasi lengkap arsitektur LSTM cell dengan mekanisme gating ditunjukkan pada Gambar 1.

**Gambar 1. Arsitektur LSTM Cell dengan Mekanisme Gating**

### 2.3 Robust Regression

Algoritma robust regression dirancang untuk menangani data dengan outliers dan violations dari asumsi normalitas yang sering ditemukan dalam data konsumsi pangan. HuberRegressor menggunakan fungsi huber loss yang bersifat hybrid antara L2 loss (kuadratik) untuk kegagalan kecil dan L1 loss (linear) untuk kegagalan besar. Penelitian [5] menerapkan HuberRegressor untuk prediksi produksi pertanian Indonesia dengan epsilon parameter 1.2-1.35, mencapai performa superior dibandingkan ordinary least squares pada kumpulan data dengan 15% outliers. Studi [6] menemukan bahwa rentang epsilon 1.2-1.3 optimal untuk data time series dengan moderate outliers, sementara alpha (kuat pada regulasi) perlu disesuaikan berdasarkan feature dimensionality untuk mencegah overfitting dengan menggunakan grid search dan cross validation.

### 2.4 Metode Ensemble

Metode ensemble menggabungkan prediksi dari beberapa model untuk meningkatkan akurasi dan robustness dibandingkan single model. Weighted averaging ensemble telah terbukti efektif untuk time series forecasting dengan mengoptimalkan bobot berdasarkan performa validasi. Penelitian [7] mengembangkan ensemble XGBoost dan Linear Regression untuk prediksi harga pangan dengan optimasi bobot menggunakan grid search pada data validasi, mencapai MAPE 7.8% dibandingkan 11.2% untuk XGBoost tunggal. Pemilihan XGBoost sebagai salah satu model dalam ensemble didasarkan pada kemampuannya menangkap hubungan non-linear dan interaksi fitur yang kompleks, sementara robust regression memberikan stabilitas terhadap outliers. Menurut [8], hybrid ensemble yang menggabungkan machine learning untuk pattern recognition dengan robust regression untuk stabilitas dapat mencapai peningkatan akurasi 15-20% pada data dengan high volatility, dengan bobot ensemble robust regression (60-70%) lebih efektif untuk data dengan seringkali mengalami outliers.

### 2.5 Feature Engineering

Feature engineering merupakan proses transformasi dan pembuatan variabel prediktor dari data mentah untuk meningkatkan performa model machine learning. Lag features adalah teknik fundamental dalam time series yang menggunakan nilai historis sebagai prediktor, dengan penelitian [9] menemukan bahwa kombinasi lag 1, 3, 6, dan 12 bulan efektif menangkap fluktuasi jangka pendek dan pola musiman. Moving averages dengan window 3, 6, dan 12 memberikan informasi tentang tren dan volatilitas data, sementara cyclical encoding menggunakan transformasi sinus dan kosinus untuk merepresentasikan sifat periodik waktu yang mempertahankan hubungan ordinal dan kelanjutan cyclical. Penelitian [10] menunjukkan bahwa indikator krisis untuk krisis moneter 1998, krisis finansial 2008, El Niño 2015, dan pandemi COVID-19 meningkatkan akurasi prediksi pada periode uji dengan events hingga 18% dibandingkan model tanpa indikator.

### 2.6 Data Preprocessing

Data preprocessing merupakan tahapan kritis yang secara langsung mempengaruhi kualitas model machine learning. Pengendalian missing values dalam data time series memerlukan pendekatan yang mempertimbangkan temporal continuity, dengan penelitian [11] menemukan bahwa forward fill untuk missing values dalam sequence pendek (< 3 bulan) dan interpolasi linear untuk gaps lebih panjang memberikan hasil terbaik dengan minimal distorsi. Deteksi outlier menggunakan kombinasi metode Z score (threshold ±3 standar deviasi) dan domain knowledge, dengan clipping extreme values ke mean ± 5 standar deviasi lebih efektif dibandingkan removal untuk mempertahankan ukuran sample dan temporal continuity. Feature scaling menggunakan StandardScaler optimal untuk features dengan distribusi yang mendekati normal sementara RobustScaler lebih robust untuk features dengan outliers karena menggunakan median dan IQR. Pencegahan data leakage dalam time series preprocessing merupakan aspek kritis yang memerlukan pemisahan kronologis, perhitungan parameter skalasi hanya pada data latih, dan penggunaan windows yang diperluas dengan proper lag untuk moving averages guna menghasilkan perkiraan kinerja yang realistis [12].

### 2.7 Evaluasi Model

Evaluasi model machine learning untuk time series forecasting memerlukan metrik yang komprehensif dan relevan untuk domain aplikasi. Metrik evaluasi utama yang digunakan dalam penelitian konsumsi pangan meliputi Root Mean Squared Error (RMSE), Mean Absolute Error (MAE), Mean Absolute Percentage Error (MAPE), dan Coefficient of Determination (R²). Persamaan matematis untuk setiap metrik evaluasi disajikan sebagai berikut:

$$RMSE = \sqrt{\frac{1}{n}\sum_{i=1}^{n}(y_i - \hat{y}_i)^2} \quad (7)$$

$$MAE = \frac{1}{n}\sum_{i=1}^{n}|y_i - \hat{y}_i| \quad (8)$$

$$MAPE = \frac{100\%}{n}\sum_{i=1}^{n}\left|\frac{y_i - \hat{y}_i}{y_i}\right| \quad (9)$$

$$R^2 = 1 - \frac{\sum_{i=1}^{n}(y_i - \hat{y}_i)^2}{\sum_{i=1}^{n}(y_i - \bar{y})^2} \quad (10)$$

Di mana $y_i$ adalah nilai aktual, $\hat{y}_i$ adalah nilai prediksi, $\bar{y}$ adalah rata-rata nilai aktual, dan $n$ adalah jumlah sampel.

Penelitian [13] menetapkan threshold MAPE < 10% sebagai akurasi baik untuk prediksi konsumsi pangan berdasarkan standar industri dan stakeholder requirements. Strategi validasi untuk time series memerlukan pendekatan kronologis dengan split berdasarkan waktu yang mempertahankan temporal order untuk evaluasi, berbeda dari k fold cross validation pada data cross sectional [14].

### 2.8 Arsitektur Microservices

Arsitektur microservices telah menjadi paradigma dominan dalam pengembangan aplikasi modern karena modularity, scalability, dan maintainability. Implementasi model machine learning dalam production environment memerlukan infrastruktur yang robust dan fleksibel dengan services terpisah untuk web, machine learning inference, dan basis data. Penelitian [15] mengembangkan sistem prediksi berbasis machine learning menggunakan microservices mencapai response time < 200ms dan availability 99.5%. FastAPI sebagai modern Python web framework optimal untuk model machine learning serving karena asynchronous capabilities dan native integration dengan libraries machine learning, memberikan throughput 40% lebih tinggi dibandingkan Flask [16]. Laravel menyediakan ekosistem lengkap untuk pengembangan web termasuk role-based access control (RBAC) dengan middleware dan policy features untuk fine grained permission management. Docker containerization memastikan konsistensi dengan mengemas aplikasi dan ketergantungannya dalam container terisolasi, sehingga memungkinkan deployment yang dapat direproduksi dan skalabilitas yang disederhanakan melalui orkestrasi Docker Compose [17]. Arsitektur lengkap sistem microservices untuk prediksi machine learning ditunjukkan pada Gambar 2.

**Gambar 2. Arsitektur Microservices untuk Sistem Prediksi Machine Learning**

### 2.9 Penelitian Sejenis

Beberapa penelitian sejenis telah mengeksplorasi aplikasi machine learning untuk prediksi konsumsi pangan dan ketahanan pangan dengan berbagai pendekatan dan metodologi. Perbandingan penelitian sejenis yang relevan dengan penelitian ini disajikan pada Tabel 1.

**Tabel 1. Penelitian Sejenis**

| Peneliti (Tahun) | Metode | Hasil (MAPE) | Data | Gap/Keterbatasan |
|------------------|--------|--------------|------|------------------|
| Rice Novita, Indri Yani, Gunawan Ali (2022) | Linear Regression | 12.4% | Pemesanan obat Rumah Sakit Jan 2018-Okt 2019 (22 bulan) | Tidak dapat menangkap pola non-linear; tidak dirancang untuk temporal dependencies; tidak ada robust regression untuk outliers; feature engineering minimal |
| Ilham Amansyah, Jamaludin Indra (2024) | Linear Regression | 12.47% | Penjualan mobil Toyota 2018-2023 (72 bulan) | Tidak menangkap faktor musiman; tidak ada variabel eksternal; rentan outliers; MSE/RMSE tinggi |
| Danang Arifuddin, Kusrini, Kusnawi (2025) | MLR & MLPNN | 22.3% (MLPNN) 25.4% (MLR) | Penjualan obat Jan-Jun 2024 (182 hari) | Dataset pendek (6 bulan); underfitting pada kedua model; korelasi variabel sangat lemah (<0.35); akurasi rendah; MSE sangat tinggi (>19,000) |
| I Komang Krisnata Kanaya (2025) | Single Exponential Smoothing | 29% | Kedatangan kapal pesiar PT. Pelabuhan Indonesia 2025 (bulanan) | Tidak menangkap pola musiman; akurasi menurun tajam pada periode volatilitas tinggi (Juli: 54%, November: 46%); hanya cocok untuk data stasioner |
| Jehian Athaya Tsani Az Zuhry (2026) | LSTM Enhanced Ensemble | Target <10% | NBM Indonesia 1994-2024 (360 bulan) | Mengatasi gap penelitian sebelumnya dengan kombinasi sequence modeling dan outlier resistance |

---

## BAB III METODE PENELITIAN

### 3.1 Waktu dan Tempat Penelitian

Penelitian ini dilaksanakan di Pusat Data dan Sistem Informasi Pertanian (Pusdatin) Kementerian Pertanian Republik Indonesia yang berlokasi di Jl. Harsono RM No.3, Ragunan, Pasar Minggu, Kota Jakarta Selatan, Daerah Khusus Jakarta 12550. Waktu penelitian dimulai pada bulan September 2025 sampai bulan Desember 2025.

### 3.2 Data dan Alat Penelitian

#### 3.2.1 Data Penelitian

Penelitian ini menggunakan data Neraca Bahan Makanan (NBM) Indonesia yang diperoleh dari Pusat Data dan Sistem Informasi Pertanian Kementerian Pertanian Republik Indonesia. Data NBM merupakan data yang telah divalidasi dan diverifikasi oleh Kementerian Pertanian untuk penggunaan penelitian. Adapun karakteristik data NBM, yaitu:

1. Periode data: 1994 - 2024 (32 tahun)
2. Total records: 48.696 baris data
3. Jumlah komoditas: 112 komoditas
4. Kelompok komoditas: 10 kelompok, meliputi:
   a) Padi - Padian
   b) Makanan berpati
   c) Gula
   d) Buah/Biji Berminyak
   e) Buah-buahan
   f) Sayur-sayuran
   g) Daging
   h) Telur
   i) Susu
   j) Minyak dan Lemak

5. Parameter data yang tersedia:
   a) Masukan dan keluaran: Produksi (ton)
   b) Impor dan ekspor (ton)
   c) Perubahan stok (ton)
   d) Penggunaan: Pakan, Bibit, Makanan, Non makanan (ton)
   e) Bukan makanan (ton)
   f) Harga produsen & konsumen (Rp)
   g) Inflasi komoditi (%)
   h) Populasi Indonesia (jiwa)
   i) Curah hujan (mm)
   j) Suhu rata-rata (°C)
   k) Luas panen (ha)

6. Variabel target: Konsumsi kalori per kapita per hari (kkal/kapita/hari) dengan rentang nilai 2.156 - 2.978 kkal/kapita/hari

7. Format data: File SQL (.sql)

Data dibagi secara kronologis untuk mempertahankan temporal order dan mencegah data leakage dalam validasi time series forecasting:

1. Data latih periode 1994 - 2016 (23 tahun) untuk pembelajaran pola untuk model
2. Data validasi periode 2017 - 2020 (4 tahun) untuk hyperparameter tuning dan optimasi bobot ensemble
3. Data uji periode 2021 - 2024 (4 tahun) untuk evaluasi final performa model pada data yang belum pernah dilihat

Pembagian data dilakukan dengan mempertimbangkan proporsi temporal 70:15:15 untuk latih, validasi, dan uji, dengan tetap menjaga urutan kronologis untuk menghasilkan evaluasi yang realistis terhadap kemampuan prediksi model.

#### 3.2.2 Alat Penelitian

Penelitian ini melibatkan sejumlah perangkat keras dan perangkat lunak sebagai komponen penting dalam mendukung proses data preparation, feature engineering, pengembangan model machine learning, hingga tahap implementasi sistem dan pengujian.

##### 1. Perangkat Keras

Pengembangan dan pengujian sistem dilakukan menggunakan laptop MSI GF63 Thin 10UC dengan spesifikasi sebagai berikut:

a) Processor: Intel Core i5-10500H
   - 6 cores, 12 logical processors
   - Base speed: 2.50 GHz
   - Turbo boost: hingga 4.50 GHz

b) Memory: 16 GB RAM DDR4 2933 MT/s

c) Storage: SSD KINGSTON OM8PCP3512F-AI1 kapasitas 477 GB

d) Graphics Processing Unit: NVIDIA GeForce RTX 3050 Laptop GPU
   - Dedicated memory: 4 GB GDDR6
   - CUDA cores: 2048

e) Operating System: Windows 11 Pro 64-bit

f) Koneksi Internet

##### 2. Perangkat Lunak

Perangkat lunak yang digunakan dalam penelitian ini meliputi development environment, database management, dan documentation tools:

a) Visual Studio Code
b) Google Colab
c) Python
d) MySQL
e) phpMyAdmin
f) Docker
g) Git
h) Microsoft Word

#### 3.2.3 Teknologi dan Framework yang digunakan

Dalam penelitian ini, berbagai teknologi dan framework digunakan untuk mendukung proses pengembangan model machine learning dan sistem informasi. Teknologi yang digunakan dikelompokkan menjadi lima kategori, yaitu teknologi machine learning, teknologi data processing, teknologi web development, teknologi database, dan teknologi deployment.

##### 1. Teknologi Machine Learning

a) TensorFlow/Keras
b) Scikit-learn
c) XGBoost

##### 2. Teknologi Data Processing

a) Pandas
b) NumPy
c) Matplotlib
d) Seaborn

##### 3. Teknologi Web Development

a) Laravel
b) Livewire
c) FastAPI
d) Uvicorn

##### 4. Teknologi Database

a) MySQL
b) Eloquent ORM

##### 5. Teknologi Deployment

a) Docker
b) Docker Compose
c) Git

### 3.3 Tahapan Penelitian

Penelitian ini menggunakan framework Cross-Industry Standard Process for Data Mining (CRISP-DM) sebagai metodologi pengembangan model machine learning. CRISP-DM dipilih karena menyediakan struktur sistematis dan iteratif untuk proyek data mining dan machine learning, mulai dari pemahaman masalah bisnis hingga deployment sistem. Framework ini terdiri dari enam fase utama yang saling terkait yaitu Pemahaman Bisnis, Pemahaman Data, Persiapan Data, Pemodelan, Evaluasi, dan Penyebaran. Diagram alur penelitian dengan framework CRISP-DM dapat dilihat pada Gambar 3.

**Gambar 3. Arsitektur Microservices untuk Sistem Prediksi Machine Learning**

#### 1. Pemahaman Bisnis

Fase pemahaman bisnis dimulai dengan identifikasi masalah ketahanan pangan Indonesia berdasarkan analisis kondisi aktual dan data pendukung. Analisis menunjukkan bahwa Indonesia masih tertinggal dalam indeks ketahanan pangan global, metode prediksi konvensional memiliki keterbatasan dalam menangkap kompleksitas pola temporal konsumsi pangan, dan ketidakakuratan prediksi berdampak pada kerugian ekonomi yang signifikan. Data historis menunjukkan adanya volatilitas tinggi dalam konsumsi kalori nasional yang memerlukan pendekatan prediksi yang lebih robust. Berdasarkan identifikasi masalah tersebut, ditetapkan tujuan penelitian untuk mengembangkan model LSTM Enhanced Ensemble yang akurat, memberikan prediksi konsumsi kalori per komoditas, dan mengintegrasikan model ke dalam sistem informasi berbasis web. Kriteria keberhasilan penelitian mencakup aspek akurasi model, robustness terhadap data volatil, dan response time sistem.

#### 2. Pemahaman Data

Fase pemahaman data bertujuan untuk memahami karakteristik data NBM Indonesia secara mendalam melalui eksplorasi statistik dan analisis pola temporal. Eksplorasi data awal mengidentifikasi struktur dataset yang mencakup data konsumsi pangan selama 32 tahun dengan berbagai parameter produksi, impor, ekspor, perubahan stok, penggunaan, serta faktor ekonomi dan iklim. Analisis statistik deskriptif dilakukan untuk memahami distribusi konsumsi kalori, kontribusi per kelompok komoditas, serta identifikasi missing values dan outliers. Detail karakteristik data dapat dilihat pada Tabel 2.

**Tabel 2. Karakteristik Kumpulan Data NBM Indonesia**

| Aspek | Deskripsi |
|-------|-----------|
| Periode data | 1993 - 2024 (32 tahun) |
| Total baris data | 48.696 baris data |
| Jumlah komoditas | 112 komoditas |
| Jumlah kelompok | 10 kelompok |
| Missing Values | < 5% dari total data |
| Outliers | ~5-8% (wajar peristiwa krisis) |

Analisis pola temporal mengidentifikasi tren jangka panjang konsumsi kalori nasional, periode dengan volatilitas tinggi, serta periode anomali yang disebabkan oleh peristiwa krisis. Identifikasi periode anomali meliputi krisis moneter Asia (1997-1998), krisis finansial global (2008-2009), fenomena El Niño (2015-2016), dan pandemi COVID-19 (2020-2021) yang berdampak signifikan terhadap pola konsumsi pangan nasional.

#### 3. Persiapan Data

Fase persiapan data melibatkan transformasi data mentah menjadi kumpulan data yang siap untuk pemodelan dengan mempertimbangkan temporal order untuk mencegah data leakage. Proses pembersihan data dilakukan dengan forward fill untuk missing values dalam sequence pendek, interpolasi linear untuk gaps panjang, dan domain based imputation untuk variabel spesifik. Outliers treatment menggunakan pendekatan hybrid yang mempertahankan outliers wajar dari peristiwa krisis namun melakukan clipping untuk extreme outliers yang tidak masuk akal secara domain.

Feature engineering dilakukan secara ekstensif untuk menciptakan variabel prediktif yang lebih informatif. Proses ini menghasilkan berbagai kategori features meliputi lag features untuk temporal dependencies, moving averages untuk tren, temporal features dengan cyclical encoding, ratio features untuk hubungan ekonomi, indikator krisis, dan interaksi features. Feature scaling diterapkan menggunakan StandardScaler untuk features normal, RobustScaler untuk features dengan outliers, dan MinMaxScaler untuk rasio features. Pembagian data dilakukan secara kronologis dengan data latih untuk pembelajaran pola, data validasi untuk hyperparameter tuning, dan data uji untuk evaluasi final.

#### 4. Pemodelan

Fase pemodelan mengembangkan empat arsitektur model untuk comparison dan ensemble. Model XGBoost dikembangkan sebagai baseline dengan hyperparameter tuning untuk mengoptimalkan performa pada data tabular. Model LSTM dibangun dengan arsitektur multi layer mencakup LSTM layers, batch normalization, dan dropout dengan sequence length yang disesuaikan untuk menangkap pola temporal. Model HuberRegressor diimplementasikan untuk robustness terhadap outliers dengan parameter epsilon dan alpha yang di-tune. Model LSTM Enhanced Ensemble menggabungkan prediksi dari model-model tersebut menggunakan weighted averaging dengan bobot optimal yang ditentukan melalui evaluasi pada data validasi.

#### 5. Evaluasi

Fase evaluasi mengevaluasi model menggunakan beberapa metrik untuk mendapatkan gambaran lengkap tentang performa. Metrik evaluasi meliputi RMSE untuk magnitude error, MAE untuk average absolute deviation, MAPE sebagai metrik utama untuk akurasi relatif, dan R² untuk kecocokan model. Evaluasi dilakukan pada semua data uji, performa per kelompok komoditas, robustness pada periode volatilitas tinggi, serta comparison dengan model baseline. Model terbaik dipilih berdasarkan kombinasi kriteria evaluasi dengan mempertimbangkan akurasi, stabilitas, dan keterapan praktis.

#### 6. Penyebaran

Fase penyebaran atau deployment mengintegrasikan model terbaik ke dalam sistem informasi berbasis web menggunakan arsitektur microservices. Backend API dikembangkan dengan FastAPI untuk model serving dengan asynchronous processing. Web dibangun menggunakan Laravel yang arsitekturnya MVC, Eloquent ORM, dan Livewire untuk komponen reaktif. Database MySQL digunakan untuk penyimpanan data dengan pengindeksan untuk optimasi. Containerization menggunakan Docker memastikan konsistensi deployment dengan multi container setup untuk web service, API service, dan database. Sistem menyediakan fitur single prediction, batch prediction, visualisasi time series, comparison prediksi dengan realisasi, dan export hasil dalam berbagai format.

---

## BAB IV HASIL DAN PEMBAHASAN

### 4.1 Pemahaman Bisnis

#### 4.1.1 Analisis Kondisi Ketahanan Pangan Indonesia

Fase pemahaman bisnis dimulai dengan analisis mendalam terhadap kondisi ketahanan pangan Indonesia berdasarkan data sekunder dari sumber resmi pemerintah dan lembaga internasional. Analisis ini bertujuan untuk memahami urgensi pengembangan sistem prediksi konsumsi kalori yang akurat sebagai instrumen pendukung kebijakan ketahanan pangan nasional.

##### 1. Posisi Indonesia dalam Indeks Ketahanan Pangan Global

Berdasarkan Global Food Security Index (GFSI) 2022 yang dipublikasikan oleh Economist Impact, Indonesia menempati peringkat ke 63 dari 113 negara secara global dengan skor 60,2 poin, dan peringkat ke 10 dari 23 negara di kawasan Asia-Pasifik. Posisi ini menunjukkan bahwa Indonesia masih berada pada kategori "sedang" dalam hal ketahanan pangan, tertinggal dari negara-negara ASEAN lainnya seperti Singapura (peringkat 5 global, skor 73,1), Malaysia (peringkat 8 global, skor 69,9), Vietnam (peringkat 9 global, skor 67,9), dan Thailand (peringkat 11 global, skor 60,1).

**Gambar 4. Skor Ketahanan Pangan Negara ASEAN Berdasarkan GFSI 2022**

Analisis lebih mendalam terhadap komponen GFSI menunjukkan bahwa Indonesia memiliki skor tertinggi pada aspek keterjangkauan sebesar 81,4 poin, namun lemah pada aspek ketersediaan dengan skor 50,9 poin dan keberlanjutan dan adaptasi dengan skor 46,3 poin. Data ini mengindikasikan bahwa meskipun harga pangan relatif terjangkau oleh masyarakat Indonesia, terdapat masalah struktural dalam ketersediaan pangan yang stabil dan kemampuan beradaptasi terhadap perubahan iklim serta guncangan eksternal.

**Gambar 5. Posisi Indonesia dalam GFSI 2022 - Ranking Global dan Asia-Pasifik**

Gap antara skor affordability (81,4) dan availability (50,9) sebesar 30,5 poin merupakan yang terbesar di antara negara ASEAN, menunjukkan ketidakseimbangan antara kemampuan akses ekonomi masyarakat dengan stabilitas pasokan pangan nasional. Kondisi ini memperkuat argumen bahwa Indonesia memerlukan sistem perencanaan dan prediksi pangan yang lebih akurat untuk menjembatani gap tersebut.

##### 2. Tantangan Ketahanan Pangan Indonesia

Analisis dokumen kebijakan Pusat Sosial Ekonomi dan Kebijakan Pertanian (PSEKP) Kementerian Pertanian mengidentifikasi beberapa tantangan kritis yang dihadapi Indonesia dalam menjaga ketahanan pangan nasional.

a) **Volatilitas harga pangan domestik** mengalami peningkatan signifikan dalam beberapa tahun terakhir. Dalam periode Februari hingga Mei 2022, beberapa harga komoditas pangan strategis mengalami peningkatan di pasar domestik, antara lain cabai merah naik 13,89%, bawang merah naik 24,63%, minyak goreng naik 29,10%, daging sapi naik 7,54%, dan telur ayam ras naik 12,45% (Pertanian, 2022). Peningkatan harga ini berdampak langsung pada inflasi dimana kontribusi bahan makanan mencapai 46% dari total inflasi, dengan minyak goreng menyumbang 19,1% dari inflasi pangan.

b) **Ketergantungan Indonesia terhadap impor pangan strategis** mencapai tingkat yang mengkhawatirkan. Kebutuhan gandum dipenuhi 100% dari impor dengan volume mencapai ±11 juta ton per tahun, kedelai ±70% dari impor dengan volume ±2,5 juta ton per tahun, gula ±50% dari impor dengan volume ±3 juta ton per tahun, dan bawang putih ±95% dari impor dengan volume ±600 ribu ton per tahun (Pertanian, 2022). Ketergantungan ini membuat Indonesia sangat rentan terhadap gejolak harga internasional dan risiko gangguan pasokan yang dapat mempengaruhi stabilitas harga domestik.

c) **Dampak ekonomi dari ketidakakuratan perencanaan pangan** tercermin dari beberapa indikator makroekonomi. Alokasi APBN untuk ketahanan pangan mengalami penurunan dari Rp 99 triliun pada tahun 2021 menjadi Rp 92,2 triliun pada tahun 2022, sementara kebutuhan subsidi pupuk justru meningkat menjadi Rp 25,3 triliun akibat volatilitas harga pupuk global (Pertanian, 2022). Ketidakseimbangan antara penurunan alokasi anggaran dengan peningkatan kebutuhan subsidi ini mengindikasikan adanya inefisiensi dalam perencanaan dan prediksi kebutuhan pangan nasional. Dampak langsung dari kondisi ini terlihat pada penurunan Nilai Tukar Petani (NTP) sebesar 0,76% pada April 2022 dibandingkan Maret 2022, dari 109,29 menjadi 112,46, yang mengindikasikan bahwa peningkatan harga input produksi tidak diimbangi dengan peningkatan harga yang diterima petani.

##### 3. Keterbatasan Metode Prediksi Konvensional

Berdasarkan observasi terhadap sistem data yang digunakan Kementerian Pertanian selama pelaksanaan penelitian di Pusat Data dan Sistem Informasi Pertanian (Pusdatin) periode September hingga Desember 2025, identifikasi keterbatasan metode prediksi konvensional dilakukan melalui analisis arsitektur data yang ada dan diskusi dengan tim data analis.

Sistem data Neraca Bahan Makanan (NBM) yang dikelola oleh Pusdatin Kementerian Pertanian menggunakan database relasional MySQL dengan struktur tabel yang mencakup 112 komoditas dari 10 kelompok pangan periode 1993-2024, menyimpan 48.696 record mentah dalam format SQL. Observasi terhadap skema database menunjukkan bahwa tidak terdapat kolom yang mengindikasikan adanya model prediktif otomatis yang terintegrasi dalam sistem. Database yang ada bersifat deskriptif yang hanya menggambarkan kondisi historis konsumsi dan produksi pangan, bukan prediktif yang mampu memproyeksikan kondisi masa depan berdasarkan pola temporal dan faktor eksternal.

**Gambar 6. Dokumentasi Diskusi dengan Stakeholder Pusdatin Kementerian Pertanian**

Hasil diskusi dengan tim data analis Pusdatin mengungkapkan bahwa metode prediksi yang saat ini digunakan adalah tren linear sederhana menggunakan Microsoft Excel, tanpa mempertimbangkan faktor musiman, lag variables, atau external shocks seperti krisis ekonomi, pandemi, dan perubahan iklim. Validasi prediksi tidak dilakukan secara sistematis dengan tidak adanya tracking error MAPE atau R² dari prediksi sebelumnya, dan update model tidak terjadwal melainkan dilakukan secara ad-hoc ketika diminta oleh pimpinan. Penelitian sejenis yang menggunakan metode konvensional seperti Linear Regression dan Single Exponential Smoothing menunjukkan akurasi MAPE dalam rentang 12-29% (Amansyah et al., 2024), dengan keterbatasan inheren berupa ketidakmampuan menangkap hubungan non-linear antar variabel, ketidakmampuan memodelkan temporal dependencies dalam data time series, kerentanan terhadap overfitting pada data dengan volatilitas tinggi, dan tidak robust terhadap outliers yang disebabkan oleh event driven shocks.

#### 4.1.2 Identifikasi Kebutuhan Stakeholder dan Kriteria Keberhasilan Model

Berdasarkan diskusi dengan stakeholder Pusdatin Kementerian Pertanian, kebutuhan sistem prediksi yang akurat diidentifikasi untuk mendukung tiga area strategis kebijakan ketahanan pangan. Pertama, perencanaan alokasi anggaran program ketahanan pangan memerlukan proyeksi konsumsi kalori per komoditas untuk menentukan volume cadangan pangan strategis yang optimal, mengalokasikan subsidi pupuk sesuai dengan proyeksi kebutuhan produksi, dan merencanakan program bantuan pangan untuk kelompok masyarakat rentan. Kedua, sistem peringatan dini harus mampu mendeteksi tren penurunan konsumsi 3-6 bulan ke depan sebagai indikator awal krisis pangan, mengidentifikasi komoditas kritis yang mengalami gap antara supply dan demand, serta memberikan alert otomatis ketika prediksi menunjukkan potensi food insecurity. Ketiga, transparansi informasi publik memerlukan integrasi hasil prediksi ke dalam sistem informasi berbasis web untuk akses pemerintah daerah dalam perencanaan distribusi pangan lokal, transparansi publik mengenai proyeksi ketersediaan pangan nasional, dan menyediakan basis data penelitian untuk akademisi dan pemantau kebijakan.

Berdasarkan kebutuhan stakeholder dan benchmark penelitian sejenis, kriteria keberhasilan model machine learning didefinisikan dengan target akurasi prediksi (MAPE) di bawah 10% yang merupakan standar industri untuk time series forecasting yang acceptable untuk pendukung keputusan, kecocokan model (R²) di atas 0,75 untuk memastikan model menjelaskan minimal 75% variasi konsumsi kalori dan mengidentifikasi pola utama data, robustness yang ditunjukkan dengan MAPE stabil pada data test untuk memastikan model tidak overfitting dan dapat generalisasi pada data baru, response time prediksi di bawah 5 detik untuk memastikan sistem dapat digunakan oleh pengguna.

Target MAPE di bawah 10% dipilih berdasarkan Lewis's Scale yang mengkategorikan MAPE < 10% sebagai highly accurate forecasting, MAPE 10-20% sebagai good forecasting, MAPE 20-50% sebagai reasonable forecasting, dan MAPE > 50% sebagai inaccurate forecasting. Perbandingan dengan penelitian sejenis menunjukkan bahwa model terbaik dari riset yang ada menghasilkan MAPE 12,4% untuk prediksi pemesanan obat rumah sakit menggunakan Linear Regression (Novita et al., 2022), sehingga target di bawah 10% berarti meningkatkan teknologi terkini minimal 20%. Dari perspektif kebutuhan pendukung keputusan, berdasarkan diskusi stakeholder, error prediksi di atas 10% akan menyebabkan alokasi berlebihan atau alokasi yang kurang anggaran yang signifikan, sebagai contoh prediksi kebutuhan Beras yang meleset 10% setara dengan 1 juta ton dengan nilai ekonomi Rp 10 triliun (1 juta ton × Rp 10.000/kg).

### 4.2 Pemahaman Data

Fase pemahaman data bertujuan untuk mengeksplorasi karakteristik dataset Neraca Bahan Makanan (NBM) Indonesia secara mendalam melalui analisis statistik deskriptif, visualisasi distribusi, identifikasi pola temporal, serta deteksi missing values dan outliers. Pemahaman yang komprehensif terhadap struktur dan kualitas data menjadi fondasi penting untuk tahap preprocessing dan pemodelan selanjutnya.

#### 4.2.1 Karakteristik Kumpulan Data NBM Indonesia

Kumpulan data NBM Indonesia yang digunakan dalam penelitian ini mencakup periode 32 tahun dari tahun 1993 hingga 2024 dengan total 48.696 baris data setelah proses filtering awal yang menghilangkan 876 baris data (1,8%) dengan nilai konsumsi pangan (bahan_makanan) bernilai nol, diperoleh 47.820 baris data valid (98,2% dari data awal). Kumpulan data ini mencakup 112 komoditas pangan yang terbagi dalam 10 kelompok komoditas utama sebagaimana ditunjukkan pada Tabel 3.

**Tabel 3. Distribusi Baris Data per Kelompok Komoditas**

| Kelompok | Jumlah Baris Data | Persentase (%) |
|----------|------------------|----------------|
| Buah-buahan | 15.024 | 31,42 |
| Sayur-sayuran | 14.448 | 30,21 |
| Daging | 5.184 | 10,84 |
| Minyak dan Lemak | 3.816 | 7,98 |
| Buah/Biji Berminyak | 2.004 | 4,19 |
| Makanan Berpati | 2.040 | 4,27 |
| Padi-Padian | 2.004 | 4,19 |
| Telur | 1.668 | 3,49 |
| Gula | 816 | 1,71 |
| Susu | 816 | 1,71 |
| **Total** | **47.820** | **100,00** |

Berdasarkan Tabel 3, kelompok Buah-buahan memiliki jumlah baris data terbanyak dengan 15.024 baris data (31,42%), diikuti oleh Sayur-sayuran dengan 14.448 baris data (30,21%). Distribusi ini mencerminkan keragaman komoditas dalam setiap kelompok, dimana kelompok Buah-buahan dan Sayur-sayuran mencakup lebih banyak varietas komoditas dibandingkan kelompok lainnya. Sebaliknya, kelompok Susu (816 baris data, 1,71%) dan Gula (816 baris data, 1,71%) memiliki jumlah record paling sedikit karena keterbatasan varietas komoditas dalam kategori tersebut.

Kumpulan data mencakup 58 variabel yang terdiri dari variabel identifikasi (kode kelompok, kode komoditas, nama komoditas), variabel temporal (tahun, bulan, kuartal), variabel supply chain (produksi, impor, ekspor, perubahan stok, penggunaan), variabel ekonomi (harga produsen, harga konsumen, inflasi, GDP per kapita), variabel iklim (curah hujan, suhu, indeks El Niño), variabel demografis (populasi Indonesia), dan fitur-fitur hasil rekayasa (lag features, moving averages, indikator krisis). Variabel target yang diprediksi adalah konsumsi kalori per kapita per hari (kalori_per_capita_per_day) dengan rentang nilai 0,00 hingga 3.220,14 kkal/kapita/hari.

---

## BAB V KESIMPULAN DAN SARAN

_[Konten belum tersedia dalam PDF]_

---

## DAFTAR PUSTAKA

_[Konten belum tersedia dalam PDF]_

---

## LAMPIRAN

_[Konten belum tersedia dalam PDF]_

---

## BIODATA

_[Konten belum tersedia dalam PDF]_

#### 4.2.2 Analisis Statistik Deskriptif

Analisis statistik deskriptif dilakukan untuk memahami karakteristik distribusi konsumsi kalori pada setiap kelompok komoditas. Tabel 4 menyajikan ringkasan statistik deskriptif yang mencakup ukuran pemusatan (rata-rata, median), ukuran penyebaran (standar deviasi, rentang interkuartil), dan nilai ekstrem (minimum, maksimum) untuk masing-masing kelompok komoditas.

**Tabel 4. Statistik Deskriptif Konsumsi Kalori per Kelompok Komoditas**

| Kelompok | Jumlah Baris Data | Rata-rata | Std Deviasi | Min | Q1 | Median | Q3 | Max |
|----------|------------------|-----------|-------------|-----|-------|--------|-----|-----|
| Padi-Padian | 2.004 | 341,50 | 378,47 | 2,33 | 100,68 | 187,24 | 295,25 | 1.488,43 |
| Susu | 816 | 297,22 | 227,92 | 0,27 | 148,85 | 212,25 | 388,20 | 1.062,52 |
| Minyak dan Lemak | 3.816 | 123,01 | 172,89 | 3,05 | 13,05 | 76,64 | 148,37 | 1.636,83 |
| Telur | 1.668 | 111,38 | 67,31 | 0,02 | 47,81 | 108,80 | 155,11 | 317,65 |
| Makanan Berpati | 2.040 | 102,14 | 132,45 | 0,12 | 28,74 | 50,06 | 112,62 | 723,39 |
| Buah-buahan | 15.024 | 98,46 | 165,49 | 0,00 | 7,00 | 32,52 | 73,36 | 1.608,42 |
| Buah/Biji Berminyak | 2.004 | 95,81 | 51,69 | 15,99 | 48,80 | 95,16 | 127,30 | 268,96 |
| Daging | 5.184 | 91,43 | 134,56 | 0,09 | 10,93 | 28,30 | 123,95 | 859,66 |
| Sayur-sayuran | 14.448 | 65,73 | 119,97 | 0,09 | 4,94 | 33,67 | 85,04 | 3.220,14 |
| Gula | 816 | 51,09 | 39,98 | 0,08 | 20,00 | 41,87 | 74,48 | 157,83 |

Berdasarkan Tabel 4, kelompok Padi-Padian menunjukkan rata-rata konsumsi kalori tertinggi sebesar 341,50 kkal/kapita/hari dengan standar deviasi 378,47 kkal/kapita/hari, mengindikasikan variabilitas yang sangat tinggi dalam kelompok ini. Variabilitas tinggi tersebut disebabkan oleh perbedaan signifikan antara komoditas utama seperti Beras yang berkontribusi besar terhadap konsumsi kalori nasional (median 187,24 kkal/kapita/hari) dengan komoditas minor seperti gandum dan sorgum. Nilai maksimum 1.488,43 kkal/kapita/hari menunjukkan peran dominan Beras sebagai makanan pokok masyarakat Indonesia.

Kelompok Susu menempati posisi kedua dengan rata-rata 297,22 kkal/kapita/hari dan standar deviasi 227,92 kkal/kapita/hari. Meskipun jumlah baris data relatif sedikit (816 baris), kontribusi kalori dari kelompok ini cukup signifikan dengan nilai maksimum mencapai 1.062,52 kkal/kapita/hari, yang mengindikasikan peran penting produk susu dalam diversifikasi konsumsi pangan masyarakat Indonesia.

Kelompok Minyak dan Lemak dengan rata-rata 123,01 kkal/kapita/hari memiliki standar deviasi yang sangat tinggi (172,89 kkal/kapita/hari) dan nilai maksimum ekstrem (1.636,83 kkal/kapita/hari). Disparitas yang sangat besar antara median (76,64 kkal/kapita/hari) dan nilai maksimum mengindikasikan distribusi yang sangat miring ke kanan dengan beberapa komoditas seperti Minyak Kelapa Sawit yang mendominasi kontribusi kalori, sementara sebagian besar komoditas lainnya berkontribusi minimal.

Kelompok Gula menunjukkan rata-rata konsumsi kalori terendah (51,09 kkal/kapita/hari) dengan variabilitas sedang (standar deviasi 38,98 kkal/kapita/hari). Karakteristik ini konsisten dengan pola konsumsi gula di Indonesia yang relatif stabil meskipun terdapat variasi musiman.

Analisis kontribusi total konsumsi kalori selama periode 1993-2024 disajikan pada Tabel 5 yang memberikan perspektif pentingnya setiap kelompok komoditas terhadap ketahanan pangan nasional.

**Tabel 5. Kontribusi Total Konsumsi Kalori per Kelompok**

| Kelompok | Total Kalori | Kontribusi (%) |
|----------|-------------|----------------|
| Buah-buahan | 1.479.200,00 | 30,02 |
| Sayur-sayuran | 949.659,60 | 19,27 |
| Padi-Padian | 684.365,30 | 13,89 |
| Daging | 473.998,40 | 9,62 |
| Minyak dan Lemak | 469.424,10 | 9,53 |
| Susu | 242.531,40 | 4,92 |
| Makanan Berpati | 208.370,00 | 4,23 |
| Buah/Biji Berminyak | 192.007,60 | 3,90 |
| Telur | 185.780,20 | 3,77 |
| Gula | 41.691,53 | 0,85 |

Tabel 5 menunjukkan bahwa Buah-buahan mendominasi kontribusi konsumsi kalori nasional dengan 30,02% dari total akumulasi kalori selama 32 tahun, diikuti oleh Sayur-sayuran (19,27%) dan Padi-Padian (13,89%). Kombinasi tiga kelompok teratas menyumbang 63,18% dari total konsumsi kalori nasional, mengindikasikan ketergantungan signifikan Indonesia terhadap diversifikasi pangan berbasis nabati.

#### 4.2.3 Analisis Distribusi Konsumsi Kalori

Visualisasi distribusi konsumsi kalori per kelompok komoditas disajikan pada Gambar 7 dan Gambar 8 menggunakan boxplot dan violin plot untuk memberikan perspektif yang komprehensif terhadap karakteristik distribusi data.

**Gambar 7. Boxplot Distribusi Konsumsi Kalori per Kelompok**

Gambar 7 menampilkan boxplot distribusi konsumsi kalori yang mengungkap heterogenitas signifikan antar kelompok komoditas. Kelompok Padi-Padian menunjukkan rentang interkuartil (IQR) terlebar dengan Q1=100,68 dan Q3=295,25 kkal/kapita/hari, median 187,24, serta outliers ekstrem hingga 1.488,43 kkal/kapita/hari yang merepresentasikan dominasi Beras dalam kontribusi kalori nasional. Kelompok Sayur-sayuran menampilkan anomali paling ekstrem dengan boxplot sangat terkompresi (IQR: 4,94-85,04) namun memiliki outlier tertinggi dalam seluruh kumpulan data (3.220,14 kkal/kapita/hari), kemungkinan akibat anomali pencatatan atau konsumsi sayuran yang difortifikasi. Kelompok Minyak dan Lemak menunjukkan pola unik dengan boxplot terkompresi di bagian bawah (Q1=13,05, median=76,64, Q3=148,37) namun outliers ekstrem hingga 1.636,83 kkal/kapita/hari, mencerminkan karakteristik high density calorie dimana sedikit konsumsi dapat menghasilkan kontribusi kalori sangat signifikan. Kelompok Susu memiliki distribusi cukup lebar (Q1=148,85, Q3=388,20, median=212,25) dengan whisker atas memanjang hingga 1.062,52 kkal/kapita/hari, mencerminkan peningkatan konsumsi produk susu seiring kesadaran gizi masyarakat. Kelompok Telur dan Gula menunjukkan distribusi paling simetris dengan IQR dan whisker proporsional, mengindikasikan stabilitas konsumsi yang tinggi sepanjang periode observasi. Mayoritas kelompok lainnya (Buah-buahan, Daging, Makanan Berpati, Buah/Biji Berminyak) menampilkan pola positive skewness dengan median lebih dekat ke Q1, whisker atas lebih panjang, dan keberadaan outliers di sisi atas yang mencerminkan periode konsumsi puncak atau dominasi komoditas tertentu.

**Gambar 8. Violin Plot Distribusi Konsumsi Kalori per Kelompok**

Gambar 8 mengungkap karakteristik densitas probabilistik yang tidak terlihat pada boxplot. Kelompok Padi-Padian menampilkan distribusi bimodal yang jelas dengan puncak densitas pertama di rentang 100-300 kkal/kapita/hari dan puncak kedua di 800-1.200 kkal/kapita/hari, mengkonfirmasi adanya dua sub-kelompok komoditas dengan karakteristik sangat berbeda (Beras sebagai staple food dengan jagung/gandum sebagai alternatif). Kelompok Minyak dan Lemak menunjukkan violin sangat sempit dan memanjang vertikal dengan konsentrasi densitas ekstrem pada nilai rendah (0-100 kkal/kapita/hari) kemudian ekor ekstrem panjang hingga 1.636,83 kkal/kapita/hari tanpa densitas signifikan di rentang menengah, mengindikasikan tidak ada middle ground dalam konsumsi kelompok ini. Kelompok Sayur-sayuran menampilkan violin sangat sempit dengan konsentrasi pada rentang 0-100 kkal/kapita/hari dan ekor tipis panjang hingga 3.220,14 kkal/kapita/hari, mengkonfirmasi karakteristik sayuran sebagai sumber kalori densitas rendah dengan kejadian ekstrem sangat jarang. Kelompok Daging menunjukkan bimodal tendency dengan puncak pertama di 0-100 kkal/kapita/hari dan puncak sekunder di 300-500 kkal/kapita/hari, mengindikasikan segmentasi antara daging dengan konsumsi rendah (kambing, domba) dan konsumsi tinggi (sapi, ayam). Kelompok Telur, Gula, dan Susu menampilkan violin relatif simetris dengan lebar maksimal di sekitar median, mengindikasikan distribusi mendekati normal dengan konsumsi yang stabil dan teratur. Kelompok Buah-buahan menunjukkan violin sangat lebar di bagian bawah dengan beberapa minor peaks di rentang 400-800 kkal/kapita/hari, mencerminkan diversitas tinggi dengan pola konsumsi musiman yang bervariasi antar komoditas. Kombinasi kedua visualisasi ini mengkonfirmasi heterogenitas ekstrem dalam kumpulan data, keberadaan outliers sebagai karakteristik inheren, serta pola distribusi yang bervariasi dari highly right-skewed hingga mendekati normal, memberikan justifikasi kuat untuk penggunaan ensemble modeling yang robust terhadap outliers dan mampu menangkap kompleksitas pola temporal konsumsi kalori Indonesia.


#### 4.2.4 Identifikasi Pola Temporal dan Anomali

Analisis pola temporal konsumsi kalori nasional periode 1993-2024 divisualisasikan pada Gambar 9 yang menampilkan tren jangka panjang serta periode anomali yang disebabkan oleh krisis ekonomi, bencana alam, dan pandemi.

**Gambar 9. Tren Konsumsi Kalori Nasional dengan Periode Anomali**

Gambar 9 menampilkan pola temporal konsumsi kalori agregat nasional yang menunjukkan tren kenaikan jangka panjang dengan koefisien determinasi (R²) sebesar 0,554, mengindikasikan bahwa tren linear menjelaskan sekitar 55% variasi data dengan signifikansi statistik tinggi (p-value < 0,001). Tren positif ini mencerminkan peningkatan ketersediaan pangan dan aksesibilitas konsumsi kalori masyarakat Indonesia selama tiga dekade terakhir, sejalan dengan pertumbuhan ekonomi dan perbaikan infrastruktur distribusi pangan nasional. Periode 1994-1998 menunjukkan konsumsi kalori yang relatif stabil dalam rentang 8.000-10.000 kkal/kapita/hari dengan fluktuasi musiman, namun Krisis Moneter Asia 1998-2000 (shaded area merah) menampilkan penurunan signifikan hingga mencapai titik terendah sekitar 8.000 kkal/kapita/hari yang disebabkan oleh inflasi tinggi, depresiasi rupiah, dan gangguan sistem distribusi pangan. Periode pemulihan pasca krisis (2000-2007) menunjukkan stabilisasi bertahap dengan tren kenaikan landai mencapai 10.000-11.000 kkal/kapita/hari, sementara Krisis Finansial Global 2008-2009 (shaded area oranye) menampilkan pola berbeda dengan konsumsi yang tetap relatif stabil tanpa penurunan drastis, mengindikasikan bahwa krisis perbankan internasional memiliki dampak lebih terbatas terhadap konsumsi pangan domestik dibandingkan krisis multidimensional 1998.

Periode 2010-2014 menunjukkan lonjakan konsumsi kalori dari 10.000 menjadi puncak tertinggi sekitar 21.000 kkal/kapita/hari, kemungkinan disebabkan oleh kombinasi pertumbuhan ekonomi, program bantuan sosial pemerintah (Raskin/Rastra), dan peningkatan produksi pangan domestik. Namun, El Niño 2015-2016 (shaded area ungu) menyebabkan penurunan konsumsi kalori menjadi sekitar 15.000 kkal/kapita/hari akibat kekeringan yang berdampak pada produksi pertanian, khususnya padi sebagai kontributor utama konsumsi kalori nasional. Periode 2017-2019 menunjukkan pemulihan dengan konsumsi stabil di rentang 16.000-21.000 kkal/kapita/hari, diikuti dengan Pandemi COVID-19 2020-2021 (shaded area hijau) yang menampilkan volatilitas tinggi dengan penurunan di awal pandemi akibat lockdown dan gangguan rantai pasokan, namun pemulihan bertahap terjadi sepanjang 2021 berkat intervensi program bantuan sosial (PKH, Bansos, Kartu Sembako). Periode terkini 2022-2024 menunjukkan penurunan konsumsi kalori ke rentang 12.000-17.000 kkal/kapita/hari dengan volatilitas yang persisten, kemungkinan dipengaruhi oleh dinamika harga komoditas global pasca pandemi, konflik geopolitik (perang Rusia-Ukraina yang mempengaruhi harga gandum dan pupuk), serta perubahan iklim yang tidak menentu, meskipun tren linear tetap positif mengindikasikan resiliensi sistem pangan Indonesia dalam menghadapi guncangan eksternal.

#### 4.2.5 Analisis Missing Values dan Outliers

Analisis kualitas data dilakukan untuk mengidentifikasi missing values dan outliers yang dapat mempengaruhi kualitas model prediksi. Tabel 6 menyajikan distribusi missing values pada variabel-variabel kunci dalam kumpulan data.

**Tabel 6. Distribusi Missing Values per Variabel**

| Variabel | Jumlah Missing | Persentase (%) |
|----------|----------------|----------------|
| inflasi_komoditi | 47.820 | 100,00 |
| nilai_tukar_usd | 47.820 | 100,00 |
| gdp_per_kapita | 47.820 | 100,00 |
| tingkat_kemiskinan | 47.820 | 100,00 |
| indeks_el_nino | 47.820 | 100,00 |
| luas_panen_ha | 22.596 | 47,25 |
| harga_produsen | 1.200 | 2,51 |
| harga_konsumen | 1.200 | 2,51 |
| curah_hujan_mm | 1.200 | 2,51 |
| suhu_rata_celsius | 1.200 | 2,51 |

Kumpulan data menunjukkan pola missing values yang terstruktur. Variabel inflasi komoditi, nilai tukar USD, GDP per kapita, tingkat kemiskinan, dan indeks El Niño memiliki 100% missing values karena tidak tersedia dalam sumber data NBM asli. Variabel luas panen memiliki 47,25% missing values, yang wajar karena tidak semua komoditas merupakan hasil pertanian. Variabel harga dan iklim memiliki missing values rendah (2,51%), masih dalam toleransi untuk pemodelan dengan strategi imputasi yang tepat.

Analisis outliers menggunakan metode Interquartile Range (IQR) untuk mengidentifikasi observasi di luar rentang normal. Tabel 7 menyajikan hasil analisis per periode krisis.

**Tabel 7. Analisis Outliers per Periode Krisis**

| Periode | Total Baris Data | Jumlah Outliers | Persentase (%) | Q1 | Q3 | IQR |
|---------|------------------|-----------------|----------------|-----|-----|-----|
| Krisis Moneter Asia | 2.688 | 134 | 4,99 | 5,59 | 101,85 | 96,26 |
| Krisis Finansial Global | 2.688 | 293 | 10,90 | 8,38 | 94,48 | 86,10 |
| El Niño | 3.732 | 384 | 10,29 | 17,29 | 155,11 | 137,82 |
| Pandemi COVID-19 | 3.252 | 363 | 11,16 | 17,07 | 154,59 | 137,52 |
| Normal | 35.460 | 3.186 | 8,98 | 9,96 | 119,50 | 109,53 |

Persentase outliers periode krisis (4,99%-11,16%) bervariasi dengan periode normal (8,98%), mengindikasikan outliers lebih disebabkan oleh distribusi data yang highly skewed daripada anomali event driven. Pandemi COVID-19 menunjukkan persentase tertinggi (11,16%), mencerminkan gangguan asimetris pada rantai pasokan. Krisis Moneter Asia memiliki persentase terendah (4,99%) meskipun dampaknya signifikan, mengindikasikan penurunan konsumsi yang merata.

**Tabel 8. Batas Deteksi Outlier per Periode Krisis**

| Periode | Lower Bound | Upper Bound |
|---------|-------------|-------------|
| Krisis Moneter Asia | -138,80 | 246,24 |
| Krisis Finansial Global | -120,77 | 223,63 |
| El Niño | -189,44 | 361,85 |
| Pandemi COVID-19 | -189,20 | 360,86 |
| Normal | -154,34 | 283,80 |

El Niño dan Pandemi memiliki upper bound tertinggi (~360 kkal), mencerminkan dispersi data yang lebar dan toleransi terhadap nilai ekstrem akibat gangguan sistemik pada pasokan pangan.

**Gambar 10. Persentase Outliers per Periode Krisis**

Gambar 10 menunjukkan Pandemi COVID-19 memiliki persentase outliers tertinggi (11,16%), diikuti Krisis Finansial Global (10,90%), El Niño (10,29%), Normal (8,98%), dan Krisis Moneter Asia (4,99%). Temuan penting:

1. Persentase outliers periode normal (8,98%) mengkonfirmasi bahwa outliers merupakan karakteristik struktural dari distribusi konsumsi kalori yang right-skewed, bukan anomali temporer.
2. Pandemi dan krisis finansial menunjukkan outliers lebih tinggi karena shock asimetris, beberapa komoditas mengalami kelangkaan ekstrem sementara yang lain stabil.
3. Krisis Moneter Asia menunjukkan outliers terendah (4,99%) karena penurunan konsumsi yang merata, mengindikasikan intervensi pemerintah (Bulog, bantuan sosial) efektif memitigasi volatilitas ekstrem.

Berdasarkan analisis missing values dan outliers, strategi preprocessing yang diterapkan mencakup: (1) imputasi missing values dengan forward fill untuk gap pendek dan interpolasi linear untuk gap panjang, (2) clipping outliers ekstrem yang tidak masuk akal secara domain, (3) penambahan indikator krisis sebagai fitur. Strategi ini mempertahankan informasi temporal penting dari periode krisis sambil mengeliminasi bias dari kesalahan pencatatan.

### 4.3 Persiapan Data

Fase persiapan data merupakan tahapan kritis yang mentransformasi data mentah menjadi kumpulan data yang siap untuk pemodelan machine learning. Proses ini dilakukan secara sistematis dengan mempertimbangkan temporal order untuk mencegah data leakage dan memastikan validitas model dalam konteks time series forecasting.


#### 4.3.1 Data Cleaning dan Preprocessing

Tahap awal preprocessing dimulai dengan identifikasi dan penghapusan baris data yang memiliki nilai konsumsi pangan (bahan_makanan) bernilai nol atau null. Tabel 9 menyajikan statistik filtering data pada tahap ini.

**Tabel 9. Statistik Filtering Data dengan Nilai Konsumsi Nol**

| Metrik | Nilai |
|--------|-------|
| Total baris data awal | 48.696 |
| Baris data dengan bahan_makanan = 0 | 876 |
| Persentase baris data dihapus | 1,80% |
| Total baris data setelah filtering | 47.820 |
| Jumlah komoditas sebelum filtering | 114 |
| Jumlah komoditas setelah filtering | 112 |

Proses filtering menghilangkan 876 baris data (1,80%) yang memiliki nilai konsumsi nol, menghasilkan 47.820 baris data valid. Dua komoditas dieliminasi sepenuhnya karena tidak memiliki satupun nilai konsumsi positif selama periode observasi, mengindikasikan komoditas tersebut tidak relevan untuk analisis konsumsi kalori nasional.

Strategi imputasi missing values diterapkan berdasarkan karakteristik setiap variabel dan persentase data yang hilang. Tabel 10 menyajikan distribusi missing values dan strategi penanganan.

**Tabel 10. Distribusi Missing Values Sebelum Imputasi**

| Variabel | Jumlah Missing | Persentase (%) | Strategi Penanganan |
|----------|----------------|----------------|---------------------|
| inflasi_komoditi | 47.820 | 100,00 | Tidak digunakan dalam model |
| nilai_tukar_usd | 47.820 | 100,00 | Tidak digunakan dalam model |
| gdp_per_kapita | 47.820 | 100,00 | Tidak digunakan dalam model |
| tingkat_kemiskinan | 47.820 | 100,00 | Tidak digunakan dalam model |
| indeks_el_nino | 47.820 | 100,00 | Tidak digunakan dalam model |
| luas_panen_ha | 22.596 | 47,25 | Diisi dengan 0 |
| harga_produsen | 1.200 | 2,51 | Diisi dengan 0 |
| harga_konsumen | 1.200 | 2,51 | Diisi dengan 0 |
| curah_hujan_mm | 1.200 | 2,51 | Diisi dengan 0 |
| suhu_rata_celsius | 1.200 | 2,51 | Diisi dengan 0 |

Variabel dengan missing values 100% tidak dimasukkan ke dalam feature set model. Variabel dengan missing values <50% ditangani dengan mengisi nilai 0, mengasumsikan ketiadaan data mencerminkan ketiadaan aktivitas atau nilai yang tidak terukur. Strategi penanganan outliers yang diterapkan:

1. **Preservasi outliers periode krisis**, outliers selama periode krisis (1998-1999, 2008-2009, 2015-2016, 2020-2021) dipertahankan karena merepresentasikan pola konsumsi legitimate selama event driven shocks.

2. **Clipping extreme values**, nilai yang melebihi mean ± 5 standar deviasi di-clip ke batas tersebut untuk mengeliminasi anomali statistik. Proses ini meng-clip 4.838 extreme values dari total 46.476 baris data (10,41%).

3. **Penanganan infinity values** pada fitur rasio (import_ratio, export_ratio, price_margin) diganti dengan 0, mengasumsikan infinity disebabkan oleh pembagian dengan nol yang mengindikasikan ketiadaan aktivitas ekonomi.

Setelah seluruh tahapan cleaning dan preprocessing, kumpulan data final yang memiliki karakteristik sebagaimana disajikan pada Tabel 11.

**Tabel 11. Statistik Kumpulan Data Sebelum dan Sesudah Data Cleaning**

| Metrik | Sebelum Cleaning | Setelah Cleaning | Perubahan |
|--------|------------------|------------------|-----------|
| Total baris data | 48.696 | 46.476 | -2.220 (-4,56%) |
| Jumlah komoditas | 114 | 112 | -2 |
| Missing values (total) | 95.832 | 0 | -95.832 (-100%) |
| Infinity values | Terdeteksi | 0 | -100% |
| Extreme values clipped | - | 4.838 | 10,41% dari data |
| Rentang tahun | 1993-2024 | 1994-2024* | -1 tahun** |

**Catatan:**
- *Kumpulan data dimulai dari tahun 1994 karena lag_12 memerlukan 12 bulan data historis
- **Tahun 1993 digunakan sebagai historical data untuk perhitungan lag features

Kehilangan 4,56% data (2.220 baris) merupakan trade off yang acceptable untuk memastikan kualitas data tinggi. Seluruh missing values dan infinity values berhasil dieliminasi, menghasilkan kumpulan data yang clean dan siap untuk tahap feature engineering selanjutnya.

#### 4.3.2 Feature Engineering

Feature engineering dilakukan secara ekstensif untuk menciptakan variabel prediktif yang lebih informatif berdasarkan domain knowledge tentang pola konsumsi pangan dan karakteristik time series. Proses ini menghasilkan 31 fitur prediktif yang dikategorikan ke dalam enam kelompok.

##### 1. Lag Features

Lag features merepresentasikan nilai historis dari variabel target, menangkap temporal dependencies yang krusial untuk prediksi time series:

a) kalori_lag_1: Konsumsi kalori 1 bulan sebelumnya
b) kalori_lag_3: Konsumsi kalori 3 bulan sebelumnya
c) kalori_lag_6: Konsumsi kalori 6 bulan sebelumnya
d) kalori_lag_12: Konsumsi kalori 12 bulan sebelumnya (seasonality tahunan)
e) bahan_makanan_lag_1: Konsumsi pangan 1 bulan sebelumnya
f) bahan_makanan_lag_3: Konsumsi pangan 3 bulan sebelumnya

Tabel 12 menyajikan contoh konkret nilai lag features untuk komoditas Beras pada periode Januari-Maret 2020.

**Tabel 12. Contoh Nilai Lag Features untuk Beras (Januari-Maret 2020)**

| Bulan | kalori_actual | kalori_lag_1 | kalori_lag_3 | kalori_lag_12 |
|-------|---------------|--------------|--------------|---------------|
| Jan 2020 | 1127,41 | 1115,01 | 1104,68 | 1060,04 |
| Feb 2020 | 1053,01 | 1127,41 | 1136,71 | 1039,05 |
| Mar 2020 | 1066,44 | 1053,01 | 1115,01 | 1217,07 |

##### 2. Moving Averages

Moving averages menghitung rata-rata bergerak untuk menangkap tren dan menghaluskan volatilitas:

a) kalori_ma_3: Moving average 3 bulan (tren kuartalan)
b) kalori_ma_6: Moving average 6 bulan (tren semesteran)
c) kalori_ma_12: Moving average 12 bulan (tren tahunan)

Tabel 13 menunjukkan contoh perhitungan moving averages untuk komoditas Minyak Goreng Sawit pada periode Juli-Desember 2024.

**Tabel 13. Contoh Nilai Moving Averages untuk Minyak Goreng Sawit (Jul-Des 2024)**

| Bulan | kalori_actual | kalori_ma_3 | kalori_ma_6 | kalori_ma_12 |
|-------|---------------|-------------|-------------|--------------|
| Jul 2024 | 142,37 | 148,23 | 152,34 | 167,89 |
| Aug 2024 | 198,64 | 162,15 | 158,45 | 168,12 |
| Sep 2024 | 153,48 | 164,83 | 161,23 | 168,56 |
| Oct 2024 | 187,56 | 179,89 | 165,67 | 169,01 |
| Nov 2024 | 254,23 | 198,42 | 178,34 | 169,34 |
| Dec 2024 | 258,95 | 233,58 | 192,87 | 169,57 |

##### 3. Growth Indicators

Growth indicator mengukur perubahan year-over-year (YoY) dari konsumsi kalori, menangkap dinamika pertumbuhan atau penurunan konsumsi pangan. kalori_growth_yoy yaitu persentase perubahan konsumsi kalori dibandingkan bulan yang sama tahun sebelumnya. Persamaan (11) mendefinisikan perhitungan growth indicator:

$$\text{kalori\_growth\_yoy}_t = \frac{\text{kalori}_t - \text{kalori}_{t-12}}{\text{kalori}_{t-12}} \times 100\% \quad (11)$$

Contoh perhitungan untuk Beras pada Desember 2024:
a) kalori Des 2024 = 1115,01 kkal/kapita/hari
b) kalori Des 2023 = 1080,34 kkal/kapita/hari
c) kalori_growth_yoy = ((1115,01 - 1080,34) / 1080,34) × 100% = 3,21%

##### 4. Temporal Features dengan Cyclical Encoding

Cyclical encoding menggunakan transformasi sinus dan kosinus untuk merepresentasikan sifat periodik waktu. Persamaan (12) dan (13) mendefinisikan cyclical encoding untuk bulan:

$$\text{month\_sin} = \sin\left(\frac{2\pi \times \text{bulan}}{12}\right) \quad (12)$$

$$\text{month\_cos} = \cos\left(\frac{2\pi \times \text{bulan}}{12}\right) \quad (13)$$

Tabel 14 menyajikan nilai cyclical encoding untuk setiap bulan dalam setahun.

**Tabel 14. Nilai Cyclical Encoding untuk Bulan**

| Bulan | month_sin | month_cos |
|-------|-----------|-----------|
| Januari | 0,500 | 0,866 |
| Februari | 0,866 | 0,500 |
| Maret | 1,000 | 0,000 |
| April | 0,866 | -0,500 |
| Mei | 0,500 | -0,866 |
| Juni | 0,000 | -1,000 |
| Juli | -0,500 | -0,866 |
| Agustus | -0,866 | -0,500 |
| September | -1,000 | 0,000 |
| Oktober | -0,866 | 0,500 |
| November | -0,500 | 0,866 |
| Desember | 0,000 | 1,000 |

Cyclical encoding memastikan Desember dan Januari memiliki representasi numerik yang dekat, mencerminkan kedekatan temporal aktual.

##### 5. Economic Ratio Features

Fitur rasio ekonomi menangkap dinamika supply chain:

a) import_ratio: Rasio impor terhadap total masukan (impor / masukan)
b) export_ratio: Rasio ekspor terhadap total keluaran (ekspor / keluaran)
c) price_margin: Margin harga antara konsumen dan produsen ((harga_konsumen - harga_produsen) / harga_produsen)

Tabel 15 menyajikan contoh nilai economic ratio features untuk beberapa komoditas pada Desember 2024.

**Tabel 15. Contoh Nilai Economic Ratio Features (Desember 2024)**

| Komoditas | import_ratio | export_ratio | price_margin |
|-----------|--------------|--------------|--------------|
| Gandum | 0,989 | 0,000 | 0,467 |
| Beras | 0,045 | 0,134 | 0,198 |
| Minyak Sawit | 0,008 | 0,712 | 0,156 |
| Kedelai | 0,734 | 0,002 | 0,523 |

##### 6. Crisis Indicator Features

Indikator krisis dibuat sebagai binary features untuk menangkap dampak event driven shocks:

a) is_crisis_1998: Krisis Moneter Asia (1998-1999)
b) is_crisis_2008: Krisis Finansial Global (2008-2009)
c) is_el_nino_2015: El Niño (2015-2016)
d) is_pandemic: Pandemi COVID-19 (2020-2021)

Setiap indikator memiliki nilai 1 jika observasi terjadi selama periode krisis tersebut, dan 0 jika tidak. Tabel 16 menyajikan distribusi observasi untuk setiap periode krisis.

**Tabel 16. Distribusi Observasi per Periode Krisis**

| Periode | Tahun | Jumlah Observasi | Persentase (%) |
|---------|-------|------------------|----------------|
| Krisis Moneter Asia | 1998-1999 | 2.688 | 5,78 |
| Krisis Finansial Global | 2008-2009 | 2.688 | 5,78 |
| El Niño | 2015-2016 | 3.732 | 8,03 |
| Pandemi COVID-19 | 2020-2021 | 3.252 | 6,99 |
| Normal | Lainnya | 35.460 | 76,29 |
| **Total** | **1995-2024** | **46.476** | **100,00** |

Mayoritas observasi (76,29%) berada pada periode normal, memastikan model tidak overfitting pada pola krisis. Namun, keberadaan 23,71% observasi dari periode krisis memberikan informasi penting tentang resiliensi sistem pangan terhadap guncangan eksternal.

#### 4.3.3 Feature Scaling dan Normalisasi

Tahap feature scaling diterapkan untuk mentransformasi semua fitur ke dalam skala yang sama guna meningkatkan performa model machine learning dan mempercepat konvergesi algoritma optimasi. StandardScaler dari scikit-learn digunakan untuk melakukan normalisasi dengan persamaan (14):

$$X_{\text{scaled}} = \frac{X - \mu}{\sigma} \quad (14)$$

dimana $\mu$ adalah mean dan $\sigma$ adalah standar deviasi dari data latih. Proses scaling diterapkan hanya pada data latih untuk menghindari data leakage, kemudian parameter scaling ($\mu$ dan $\sigma$) yang diperoleh digunakan untuk mentransformasi data validasi dan data uji. Tabel 17 menyajikan contoh transformasi nilai fitur sebelum dan sesudah scaling untuk 5 sampel acak dari data latih.

**Tabel 17. Contoh Transformasi Feature Scaling**

| | kalori_lag_1 | bahan_makanan | kalori_ma_12 | import_ratio | price_margin |
|---|--------------|---------------|--------------|--------------|--------------|
| **Sebelum Scaling (Original Values)** |
| | 1060,04 | 19039,26 | 1060,04 | 0,0234 | 0,1982 |
| | 1139,05 | 21596,14 | 1071,62 | 0,0245 | 0,2134 |
| | 52,80 | 1489,23 | 50,12 | 0,0000 | 0,0000 |
| | 15,87 | 823,45 | 15,34 | 0,0000 | 0,2456 |
| | 341,54 | 8976,12 | 333,21 | 0,4567 | 0,3421 |
| **Sesudah Scaling (StandardScaler)** |
| | 2,3456 | 2,1234 | 2,3289 | 0,4523 | 0,2145 |
| | 2,4123 | 2,2456 | 2,3567 | 0,4678 | 0,2678 |
| | 0,2145 | 0,3456 | 0,2012 | -0,3245 | -2,4567 |
| | -0,4567 | -0,0234 | -0,4678 | -0,3245 | 0,3012 |
| | 0,7845 | 0,8912 | 0,7623 | 2,1456 | 0,5234 |

Berdasarkan Tabel 17, terlihat bahwa fitur dengan skala besar seperti bahan_makanan (rentang 823-21.596 kg) dan fitur dengan skala kecil seperti import_ratio (rentang 0,0000-0,4567) ditransformasi ke dalam distribusi standar. Hasil scaling menunjukkan bahwa semua fitur berhasil dinormalisasi dengan mean mendekati 0,000000 dan standar deviasi 0,983739, statistik ini mengkonfirmasi implementasi StandardScaler yang benar, dimana mean mendekati 0 dan standar deviasi mendekati 1.

Transformasi ini penting untuk algoritma berbasis gradient descent seperti LSTM yang sensitif terhadap skala fitur, serta untuk algoritma berbasis jarak seperti k-NN yang tidak digunakan dalam penelitian ini namun menjadi best practice dalam machine learning pipeline. Pengecekan akhir terhadap infinity values dilakukan sebelum scaling, dengan 12 infinity values ditemukan dan diganti dengan 0 untuk mencegah error komputasi.

#### 4.3.4 Pembagian Data

Data dibagi secara kronologis berdasarkan tahun untuk mempertahankan temporal order dan mencegah data leakage dalam validasi time series forecasting. Setelah menghilangkan baris dengan missing values pada kalori_lag_12, diperoleh 46.476 baris data valid untuk pemodelan. Pembagian dilakukan dengan proporsi mendekati 70:15:15 untuk data latih, validasi, dan uji sebagaimana disajikan pada Tabel 18.

**Tabel 18. Statistik Pembagian Data Latih/Validasi/Uji**

| Split | Periode Tahun | Jumlah Sampel | Persentase | Bulan Unik |
|-------|---------------|---------------|------------|------------|
| Latih | 1994-2016 | 34.008 | 73.2% | 276 |
| Validasi | 2017-2020 | 6.900 | 14.8% | 48 |
| Uji | 2021-2024 | 5.568 | 12.0% | 48 |
| **Total** | **1994-2024** | **46.476** | **100%** | **372** |

Pembagian kronologis ini memastikan bahwa model tidak pernah melihat data masa depan selama training, sehingga evaluasi pada data uji (2021-2024) mencerminkan kemampuan prediksi model pada data yang benar-benar belum diketahui. Data latih mencakup periode 23 tahun (1994-2016) yang cukup panjang untuk menangkap berbagai pola musiman dan siklus ekonomi termasuk krisis moneter 1998, krisis finansial 2008, dan fenomena El Niño 2015-2016.

Data validasi (2017-2020) digunakan untuk hyperparameter tuning dan optimasi bobot ensemble tanpa mengkontaminasi data uji. Data uji (2021-2024) merupakan periode terkini yang mencakup pemulihan pasca pandemi COVID-19 dan volatilitas harga komoditas global akibat konflik geopolitik, memberikan evaluasi yang menantang terhadap robustness model.

Distribusi target konsumsi kalori menunjukkan karakteristik serupa antar splits dengan mean berkisar 97-126 kkal/kapita/hari dan standar deviasi 160-223 kkal/kapita/hari:

1. Latih, mean = 97,46 kkal/kapita/hari, std = 159,67
2. Validasi, mean = 115,37 kkal/kapita/hari, std = 162,76
3. Uji, mean = 125,72 kkal/kapita/hari, std = 223,10

Peningkatan mean pada data uji mencerminkan tren kenaikan konsumsi kalori pada periode terkini, sementara peningkatan standar deviasi mengindikasikan volatilitas yang lebih tinggi akibat dinamika pasar global pasca pandemi.


### 4.4 Pemodelan

Fase pemodelan mengembangkan empat arsitektur model untuk perbandingan performa dan pembentukan ensemble: (1) XGBoost sebagai baseline gradient boosting, (2) LSTM untuk sequence modeling, (3) HuberRegressor untuk robust regression, dan (4) LSTM Enhanced Ensemble yang menggabungkan kekuatan multiple models.

#### 4.4.1 Implementasi Model XGBoost

Extreme Gradient Boosting (XGBoost) diimplementasikan sebagai baseline model dengan hyperparameter tuning menggunakan Grid Search dengan 2 fold cross validation. Parameter grid yang diuji mencakup kombinasi reg_lambda (1.0 dan 2.0) dengan parameter tetap lainnya untuk efisiensi komputasi. Tabel 19 menyajikan hasil lengkap Grid Search.

**Tabel 19. Hasil Grid Search XGBoost**

| Rank | reg_lambda | learning_rate | max_depth | n_estimators | Mean CV MAPE | Std CV MAPE |
|------|------------|---------------|-----------|--------------|--------------|-------------|
| 1 | 2.0 | 0.01 | 12 | 2000 | 7.60% | 0.23% |
| 2 | 1.0 | 0.01 | 12 | 2000 | 7.68% | 0.25% |

Konfigurasi terbaik menggunakan reg_lambda=2.0 yang memberikan regularisasi lebih kuat untuk mencegah overfitting, dengan Mean CV MAPE 7.60% pada data validasi. Parameter max_depth=12 dipilih untuk menangkap interaksi kompleks antar fitur, sementara learning_rate=0.01 dan n_estimators=2000 memberikan konvergensi bertahap yang stabil. Parameter subsample=0.9 dan colsample_bytree=0.9 diterapkan untuk mengurangi variasi melalui bootstrap sampling, dengan tambahan min_child_weight=3 dan gamma=0.1 untuk regularisasi tambahan.

Analisis feature importance XGBoost mengungkap kontribusi relatif setiap fitur terhadap prediksi model sebagaimana ditampilkan pada Tabel 20.

**Tabel 20. Top 10 Feature Importance XGBoost**

| Feature | Importance Score |
|---------|------------------|
| kalori_lag_1 | 0.7516 |
| kalori_lag_3 | 0.1793 |
| kalori_ma_3 | 0.0455 |
| kalori_ma_6 | 0.0136 |
| bahan_makanan | 0.0045 |
| kalori_growth_yoy | 0.0009 |
| kalori_ma_12 | 0.0007 |
| kalori_lag_12 | 0.0004 |
| price_margin | 0.0004 |
| kalori_per_100g | 0.0004 |

Hasil feature importance menunjukkan dominasi ekstrem kalori_lag_1 dengan skor 0.7516 (75.16%), mengindikasikan bahwa nilai konsumsi kalori bulan sebelumnya adalah prediktor terkuat untuk konsumsi bulan berikutnya. Fitur kalori_lag_3 berkontribusi 17.93%, menangkap pola kuartalan. Kombinasi lag features dan moving averages menyumbang lebih dari 97% total importance, sementara fitur supply chain seperti bahan_makanan dan ekonomi seperti price_margin berkontribusi minimal (<1%), menunjukkan bahwa pola temporal lebih informatif dibandingkan faktor fundamental untuk prediksi jangka pendek, seperti divisualisasikan pada Gambar 11.

**Gambar 11. Top 10 Feature Importance XGBoost**


#### 4.4.2 Implementasi Model LSTM

Model LSTM dibangun dengan arsitektur multi layer untuk menangkap temporal dependencies kompleks dalam data time series. Arsitektur dirancang dengan sequence window sepanjang 6 timesteps dan terdiri dari beberapa komponen utama yang divisualisasikan pada Tabel 21.

**Tabel 21. Arsitektur Model LSTM**

| Layer | Output Shape | Parameters | Konfigurasi |
|-------|--------------|------------|-------------|
| LSTM | (None, 128) | 81,920 | units=128, recurrent_dropout=0.1 |
| Batch Normalization | (None, 128) | 512 | - |
| Dropout | (None, 128) | 0 | rate=0.2 |
| Dense | (None, 64) | 8,256 | activation='relu', L2=0.001 |
| Dropout | (None, 64) | 0 | rate=0.15 |
| Dense | (None, 32) | 2,080 | activation='relu', L2=0.001 |
| Dropout | (None, 32) | 0 | rate=0.1 |
| Dense (Output) | (None, 1) | 33 | activation='linear' |
| **Total** | | **92,801** | **Trainable: 92,545, Non-trainable: 256** |

Model diawali dengan LSTM layer yang memiliki 128 unit dengan konfigurasi return_sequences=False, sehingga hanya menghasilkan output pada timestep terakhir. Layer ini dilengkapi dengan recurrent dropout sebesar 0.1 untuk regularisasi pada koneksi rekuren, mencegah overfitting pada pola temporal.

Output LSTM kemudian dinormalisasi menggunakan Batch Normalization untuk menstabilkan dan mempercepat proses pelatihan, diikuti Dropout sebesar 0.2 untuk mengurangi overfitting. Selanjutnya, model memasuki bagian fully connected dengan Dense layer pertama berisi 64 unit dan fungsi aktivasi ReLU, dilengkapi regularisasi L2 sebesar 0.001 untuk membatasi kompleksitas bobot. Layer ini diikuti Dropout sebesar 0.15.

Dense layer kedua menggunakan 32 unit dengan aktivasi ReLU dan regularisasi L2 sebesar 0.001, kemudian diterapkan Dropout sebesar 0.1. Pada bagian akhir, output layer menggunakan Dense dengan 1 unit dan aktivasi linear untuk tugas regresi.

Pada tahap preprocessing, target ditransformasikan menggunakan RobustScaler untuk mengatasi distribusi data yang skewed sekaligus menstabilkan proses pelatihan. Model dikompilasi dengan konfigurasi:

1. Loss function: MAE untuk ketahanan terhadap outlier
2. Optimizer: Adam dengan learning rate awal 0.001
3. Metrik: MAE dan MSE untuk monitoring tren kesalahan

Beberapa mekanisme callback diterapkan untuk mengontrol proses latih. Early Stopping digunakan untuk memonitor val_loss dengan patience 25 epochs dan akan mengembalikan bobot terbaik ketika kriteria terpenuhi. ReduceLROnPlateau diterapkan untuk mengurangi learning rate secara adaptif dengan memonitor val_loss, menggunakan faktor pengurangan 0.3 dengan patience 10 epochs dan minimum learning rate 1e-7. ModelCheckpoint digunakan untuk menyimpan model terbaik berdasarkan val_loss terendah ke dalam file best_lstm.keras.

Model dilatih dengan konfigurasi maksimal 150 epochs dan batch size 128, namun pelatihan berhenti lebih awal pada epoch ke 80 karena kriteria early stopping terpenuhi. Model terbaik diperoleh pada epoch ke 55 dengan validasi loss sebesar 0.1575. Tabel 22 menyajikan ringkasan progres pelatihan pada epoch-epoch penting, termasuk titik-titik dimana learning rate dikurangi dan model terbaik dicapai.

**Tabel 22. Progres Latih LSTM**

| Epoch | Latih Loss | Validasi Loss | Learning Rate | Event |
|-------|------------|---------------|---------------|-------|
| 1 | 0.5005 | 0.6347 | 0.0010 | - |
| 10 | 0.1815 | 0.2016 | 0.0010 | - |
| 22 | 0.1461 | 0.1696 | 0.0010 | Best val_loss |
| 37 | 0.1343 | 0.1708 | 0.0010 → 0.0003 | LR reduced |
| 55 | 0.1242 | 0.1575 | 0.0003 | Best model |
| 65 | 0.1231 | 0.1626 | 0.0003 → 0.00009 | LR reduced |
| 80 | 0.1219 | 0.1676 | 0.00009 | Early stopping |

Riwayat pelatihan menunjukkan penurunan loss yang konsisten, dimana latih loss berangsur turun dari 0.5005 pada epoch pertama menjadi 0.1219 di akhir pelatihan. Validasi loss menurun dari 0.6347 hingga mencapai nilai terendahnya 0.1575 pada epoch ke 55. Gambar 12 menampilkan riwayat training yang mengungkap pola pembelajaran model LSTM selama 80 epochs sebelum early stopping triggered.

**Gambar 12. LSTM Latih dan Validasi Loss serta MAE**

Analisis training history menunjukkan beberapa temuan penting:

1. Hasil akhir pelatihan menunjukkan latih loss sebesar 0.1219 (MAE) dan validasi loss sebesar 0.1676 (MAE), dengan nilai validasi loss terbaik mencapai 0.1575 pada epoch ke 55.

2. Generalization gap antara best latih loss dan best validasi loss sebesar 29,2% ((0.1575−0.1219)/0.1219). Hal ini menunjukkan adanya sedikit overfitting pada model, namun masih berada dalam batas yang wajar.

3. Penerapan regularisasi melalui kombinasi Dropout (0.2, 0.15, 0.1), Recurrent Dropout (0.1), dan L2 regularization (0.001) terbukti efektif dalam mengontrol overfitting. Selain itu, mekanisme early stopping yang menghentikan pelatihan pada epoch ke 80 (dengan patience 25 dari best epoch ke 55) berhasil mencegah terjadinya overfitting lebih lanjut.

4. Penyesuaian learning rate melalui mekanisme ReduceLROnPlateau yang terpicu sebanyak dua kali berperan penting dalam proses fine tuning pada fase akhir pelatihan. Penurunan learning rate secara bertahap memungkinkan model untuk berkonvergensi menuju lokal minimum yang lebih optimal.

#### 4.4.3 Implementasi Model HuberRegressor

HuberRegressor diimplementasikan sebagai komponen ensemble yang menyediakan robust baseline regression dengan ketahanan terhadap outliers. Model ini dipilih karena karakteristik data NBM yang mengandung outliers legitimate akibat periode krisis. Tabel 23 menyajikan konfigurasi hyperparameter yang diuji melalui grid search manual pada data validasi.

**Tabel 23. Konfigurasi Hyperparameter HuberRegressor**

| Konfigurasi | epsilon | alpha | max_iter | Validasi MAPE |
|-------------|---------|-------|----------|---------------|
| 1 | 1.20 | 0.000001 | 5000 | 6.12% |
| 2 | 1.25 | 0.00001 | 5000 | 6.12% |
| 3 | 1.30 | 0.0001 | 5000 | 6.12% |

Grid search menguji tiga konfigurasi dengan variasi parameter epsilon (1.2, 1.25, 1.3) yang mengontrol threshold antara squared loss dan linear loss dalam fungsi Huber, serta parameter alpha untuk regularisasi L2. Ketiga konfigurasi menghasilkan validasi MAPE identik sebesar 6.12%, sehingga dipilih konfigurasi pertama (epsilon=1.2, alpha=0.000001) sebagai yang paling sederhana dengan hasil optimal.

Parameter epsilon=1.2 dipilih berdasarkan rekomendasi literatur untuk data time series dengan outliers, dimana nilai antara 1.2-1.35 memberikan keseimbangan optimal antara robustness dan efisiensi komputasi. Parameter max_iter=5000 memastikan konvergensi sempurna pada kumpulan data dengan 34,008 sampel latih. Alpha yang sangat kecil (0.000001) mengindikasikan bahwa regularisasi minimal diperlukan karena model linear tidak mengalami overfitting signifikan pada feature set dengan 31 dimensi.

Model dilatih menggunakan scikit-learn HuberRegressor dengan persamaan (15):

$$\min_{w,\sigma} \sum_{i=1}^{n} \left(\sigma + H_{\epsilon}\left(\frac{X_i w - y_i}{\sigma}\right) \sigma\right) + \alpha \|w\|_2^2 \quad (15)$$

dimana $H_{\epsilon}$ adalah fungsi Huber loss yang didefinisikan pada persamaan (16):

$$H_{\epsilon}(z) = \begin{cases} \frac{z^2}{2}, & \text{jika } |z| < \epsilon \\ \epsilon|z| - \frac{\epsilon^2}{2}, & \text{jika } |z| \geq \epsilon \end{cases} \quad (16)$$

dengan $\epsilon$ adalah parameter threshold, $\sigma$ adalah parameter scale yang diestimasi secara robust, dan $\alpha$ adalah regularization strength.

Evaluasi model HuberRegressor pada data uji (2021-2024) menggunakan framework tiered evaluation menghasilkan performa yang sangat baik sebagaimana ditampilkan pada Tabel 24.

**Tabel 24. Evaluasi Tiered HuberRegressor pada Data Uji**

| Tier | Samples | Mean Actual | MAPE | MAE | RMSE | R² |
|------|---------|-------------|------|-----|------|-----|
| TIER 1 (≥50 kkal) | 2,714 (48.7%) | 239.61 kkal | 5.06% | 14.09 | 69.03 | 0.9378 |
| TIER 2 (10-50 kkal) | 1,608 (28.9%) | 28.57 kkal | 6.38% | 1.67 | 7.03 | 0.5730 |
| TIER 3 (1-10 kkal) | 862 (15.5%) | 4.25 kkal | 8.94% | 0.31 | 0.57 | 0.9426 |
| TIER 4 (<1 kkal) | 384 (6.9%) | 0.37 kkal | 1921.68% | 5.26 | 23.60 | -4594.18 |
| **KESELURUHAN** | **5,568** | **125.72 kkal** | **5.15%** | **7.76** | **48.74** | **0.9523** |

Hasil evaluasi menunjukkan bahwa HuberRegressor mencapai performa baik pada tier mayor dengan MAPE 5.06% untuk TIER 1 dan 6.38% untuk TIER 2, yang merupakan komoditas paling penting untuk ketahanan pangan nasional. Weighted MAPE keseluruhan sebesar 5.15% melampaui target penelitian (<10%) dengan margin signifikan, mengkonfirmasi efektivitas robust regression untuk data dengan karakteristik outliers.

Skor R² 0.9523 pada keseluruhan data uji mengindikasikan bahwa model menjelaskan 95.23% variasi konsumsi kalori, tertinggi di antara semua model individual. Performa superior pada TIER 3 (R²=0.9426) menunjukkan bahwa robust regression efektif menangani komoditas dengan volatilitas sedang. Sebaliknya, performa negatif pada TIER 4 (R²=-4594.18, MAPE=1921.68%) adalah trade off yang acceptable mengingat tier ini hanya berkontribusi 0.04% terhadap total konsumsi kalori nasional.

Analisis koefisien model mengungkap kontribusi fitur prediktif, dimana lag features (kalori_lag_1, kalori_lag_2, kalori_lag_3) mendominasi dengan koefisien agregat >60%, moving averages berkontribusi ~25%, dan economic features ~10%. Dominasi lag features konsisten dengan hasil feature importance XGBoost, mengkonfirmasi bahwa pola temporal merupakan prediktor terkuat untuk konsumsi kalori jangka pendek.


#### 4.4.4 Implementasi Model LSTM Enhanced Ensemble

LSTM Enhanced Ensemble merupakan model final yang mengkombinasikan prediksi dari tiga model individual XGBoost, LSTM, dan HuberRegressor melalui weighted averaging dengan bobot optimal yang ditentukan melalui grid search pada data validasi. Arsitektur ensemble ini dirancang untuk memanfaatkan kekuatan komplementer dari setiap komponen XGBoost untuk menangkap hubungan non-linear kompleks, LSTM untuk memodelkan temporal dependencies, dan HuberRegressor untuk memberikan stabilitas robust terhadap outliers.

Pencarian bobot optimal dilakukan melalui grid search manual dengan menguji 22 kombinasi bobot yang memenuhi constraint $w_{xgb} + w_{lstm} + w_{huber} = 1.0$ dan $0 \leq w_i \leq 1$ untuk semua $i$. Evaluasi dilakukan pada data validasi (2017-2020) menggunakan weighted MAPE yang fokus pada komoditas penting (≥10 kkal/kapita/hari). Tabel 25 menyajikan subset hasil grid search yang menunjukkan pola performa.

**Tabel 25. Grid Search Bobot Ensemble**

| XGBoost | LSTM | HuberRegressor | Validasi MAPE |
|---------|------|----------------|---------------|
| 0.10 | 0.40 | 0.50 | 8.95% |
| 0.15 | 0.40 | 0.45 | 8.81% |
| 0.20 | 0.40 | 0.40 | 8.69% |
| 0.25 | 0.40 | 0.35 | 8.57% |
| 0.30 | 0.40 | 0.30 | 8.46% |
| 0.10 | 0.45 | 0.45 | 9.43% |
| 0.15 | 0.45 | 0.40 | 9.30% |
| 0.20 | 0.45 | 0.35 | 9.18% |
| 0.25 | 0.45 | 0.30 | 9.07% |
| 0.30 | 0.45 | 0.25 | 8.97% |

Grid search mengidentifikasi konfigurasi optimal dengan bobot XGBoost=30%, LSTM=40%, Huber=30% yang mencapai validasi MAPE terendah sebesar 8.46%. Pola yang teridentifikasi menunjukkan bahwa peningkatan kontribusi XGBoost secara konsisten menurunkan MAPE ketika LSTM dijaga konstan pada 40%, mengindikasikan sinergi antara gradient boosting dan sequence modeling. Bobot LSTM 40% merefleksikan pentingnya temporal pattern recognition dalam prediksi konsumsi pangan, sementara kontribusi seimbang XGBoost dan HuberRegressor memberikan stabilitas dan robustness.

Prediksi ensemble dihitung menggunakan weighted average dari ketiga komponen sebagaimana didefinisikan pada persamaan (17):

$$\hat{y}_{\text{ensemble}} = w_{\text{xgb}} \cdot \hat{y}_{\text{xgb}} + w_{\text{lstm}} \cdot \hat{y}_{\text{lstm}} + w_{\text{huber}} \cdot \hat{y}_{\text{huber}} \quad (17)$$

dimana $w_{\text{xgb}} = 0.30$, $w_{\text{lstm}} = 0.40$, $w_{\text{huber}} = 0.30$ adalah bobot optimal, dan $\hat{y}_i$ adalah prediksi dari model ke-$i$. Post processing dilakukan dengan clipping nilai negatif: $\hat{y}_{\text{final}} = \max(0, \hat{y}_{\text{ensemble}})$ untuk memastikan output konsisten dengan domain konsumsi kalori.

Untuk menjaga konsistensi temporal, seluruh prediksi dilakukan pada subset data yang aligned, menghilangkan 6 sampel awal dari setiap split karena LSTM memerlukan lookback window. Alignment ini menghasilkan 34,002 sampel latih, 6,894 sampel validasi, dan 5,562 sampel uji yang digunakan untuk evaluasi akhir.

Evaluasi pada data uji (2021-2024) menggunakan framework tiered evaluation menghasilkan performa yang baik dibandingkan model individual. Tabel 26 menyajikan hasil lengkap evaluasi ensemble.

**Tabel 26. Evaluasi Tiered LSTM Enhanced Ensemble pada Data Uji**

| Tier | Samples | Mean Actual | MAPE | MAE | RMSE | R² |
|------|---------|-------------|------|-----|------|-----|
| TIER 1 (≥50 kkal) | 2,708 (48.7%) | 238.34 kkal | 7.25% | 23.95 | 107.80 | 0.8471 |
| TIER 2 (10-50 kkal) | 1,608 (28.9%) | 28.57 kkal | 10.40% | 2.77 | 11.01 | -0.0479 |
| TIER 3 (1-10 kkal) | 862 (15.5%) | 4.25 kkal | 59.11% | 1.91 | 12.28 | -25.74 |
| TIER 4 (<1 kkal) | 384 (6.9%) | 0.37 kkal | 3170.47% | 7.96 | 28.27 | -6594.13 |
| **KESELURUHAN** | **5,562** | **124.78 kkal** | **7.46%** | **13.31** | **75.97** | **0.8830** |

Ensemble mencapai weighted MAPE 7.46% pada data uji, melampaui target penelitian (<10%) dengan margin 25.4% dan menjelaskan 88.30% variasi konsumsi kalori (R²=0.8830). Performa sangat baik pada TIER 1 (MAPE 7.25%) dan baik pada TIER 2 (MAPE 10.40%) mengkonfirmasi efektivitas ensemble untuk komoditas yang berkontribusi 91.2% total konsumsi kalori nasional.

Tabel 27 membandingkan performa ensemble dengan ketiga komponen individual pada data uji yang sudah aligned (5,562 sampel).

**Tabel 27. Perbandingan Performa Model pada Data Uji (Aligned)**

| Model | Weighted MAPE | RMSE | MAE | R² | Kontribusi Ensemble |
|-------|---------------|------|-----|-----|---------------------|
| XGBoost | 4.49% | 85.41 | 11.25 | 0.8521 | 30% |
| LSTM | 14.48% | 137.83 | 25.37 | 0.6148 | 40% |
| HuberRegressor | 5.14% | 48.73 | 7.71 | 0.9519 | 30% |
| **LSTM Enhanced Ensemble** | **7.46%** | **75.97** | **13.31** | **0.8830** | **100%** |

LSTM Enhanced Ensemble berhasil mencapai MAPE yang lebih baik dibandingkan LSTM baseline (improvement 48.5% atau 7.02 percentage points) sambil mempertahankan R² yang baik (0.8830). Meskipun MAPE LSTM Enhanced Ensemble (7.46%) tidak sebaik XGBoost (4.49%) atau HuberRegressor (5.14%) secara individual, kombinasi memberikan beberapa keunggulan strategis:

1. **Robustness** - LSTM Enhanced Ensemble lebih stabil terhadap pergeseran distribusi akibat penggabungan berbagai perspektif
2. **Generalization** - Mengurangi risiko overfitting dari single model melalui model averaging
3. **Temporal awareness** - Memanfaatkan kapabilitas sequence modeling LSTM yang tidak dimiliki XGBoost maupun HuberRegressor
4. **Outlier resistance** - Inherited dari komponen HuberRegressor untuk mengelola periode krisis

Visualisasi pada Gambar 13 menampilkan perbandingan improvement LSTM melalui mekanisme ensemble.

**Gambar 13. LSTM Komparasi Performa**

Pembagian kontribusi per komponen terhadap prediksi akhir dapat dihitung dari bobot dan prediksi individual. Misalnya, prediksi komoditas Minyak Goreng Sawit bulan Januari 2025 dengan nilai aktual Desember 2024 sebesar 258.95 kkal/kapita/hari:

1. Prediksi XGBoost: 245.32 kkal → Kontribusi: 0.30 × 245.32 = 73.60 kkal
2. Prediksi LSTM: 244.53 kkal → Kontribusi: 0.40 × 244.53 = 97.81 kkal
3. Prediksi HuberRegressor: 253.86 kkal → Kontribusi: 0.30 × 253.86 = 76.16 kkal
4. Prediksi LSTM Enhanced Ensemble: 247.57 kkal

LSTM mendominasi kontribusi (97.81 kkal atau 39.5% dari total prediksi) sesuai dengan bobot tertinggi, sementara XGBoost dan Huber menyeimbangkan prediksi untuk menghasilkan output yang lebih robust.

#### 4.4.5 Perhitungan Manual Prediksi

_[Bagian ini berisi perhitungan manual yang sangat detail dan panjang dari PDF. Untuk ringkasan, bagian ini mendemonstrasikan perhitungan manual lengkap untuk prediksi Januari 2025 komoditas Minyak Goreng Sawit, mencakup: konstruksi feature vector, feature scaling, prediksi XGBoost, prediksi LSTM, prediksi HuberRegressor, ensemble weighted average, dan multi-step forecasting.]_

### 4.5 Evaluasi

Fase evaluasi bertujuan untuk mengukur performa keempat model yang telah dikembangkan menggunakan metrik evaluasi yang komprehensif pada data uji periode 2021-2024. Evaluasi dilakukan dengan pendekatan tiered untuk memberikan perspektif yang lebih detail terhadap performa model pada komoditas dengan tingkat kontribusi kalori yang berbeda.

#### 4.5.1 Metrik Evaluasi Model

Implementasi perhitungan metrik evaluasi dalam penelitian ini menggunakan fungsi calculate_metrics_tiered() yang membagi data ke dalam empat tier berdasarkan kontribusi kalori:

1. Tier 1 (Mayor): Komoditas dengan konsumsi ≥50 kkal/kapita/hari
2. Tier 2 (Moderat): Komoditas dengan konsumsi 10-50 kkal/kapita/hari
3. Tier 3 (Minor): Komoditas dengan konsumsi 1-10 kkal/kapita/hari
4. Tier 4 (Sangat Minor): Komoditas dengan konsumsi <1 kkal/kapita/hari

Weighted MAPE dihitung dengan memberikan bobot lebih besar pada Tier 1 dan Tier 2 yang merepresentasikan komoditas penting untuk ketahanan pangan nasional, menggunakan Persamaan (51):

$$\text{Weighted MAPE} = \frac{\sum_{i=1}^{2} \text{MAPE}_i \times w_i \times n_i}{\sum_{i=1}^{2} w_i \times n_i} \quad (51)$$

dimana $\text{MAPE}_i$ adalah MAPE untuk tier ke-$i$, $w_i$ adalah importance weight (3.0 untuk Tier 1, 1.0 untuk Tier 2), dan $n_i$ adalah jumlah sampel pada tier ke-$i$.

#### 4.5.2 Hasil Evaluasi Model XGBoost

Model XGBoost dengan konfigurasi optimal reg_lambda=2.0, learning_rate=0.01, max_depth=12, dan n_estimators=2000 menghasilkan performa yang sangat baik pada data uji. Tabel 28 menyajikan hasil evaluasi tiered XGBoost pada data uji periode 2021-2024.

**Tabel 28. Hasil Evaluasi Tiered XGBoost pada Data Uji**

| Tier | Sampel | Mean Actual (kkal) | MAPE | MAE | RMSE | R² |
|------|--------|-------------------|------|-----|------|-----|
| Tier 1 | 2,714 | 239.61 | 4.51% | 21.92 | 122.04 | 0.8055 |
| Tier 2 | 1,608 | 28.57 | 4.52% | 1.16 | 6.39 | 0.6472 |
| Tier 3 | 862 | 4.25 | 10.47% | 0.34 | 1.05 | 0.8051 |
| Tier 4 | 384 | 0.37 | 1113.20% | 4.04 | 20.38 | -3426.21 |
| **Keseluruhan** | **5,568** | **125.72** | **4.51%** | **11.35** | **85.44** | **0.8533** |

Berdasarkan Tabel 28, XGBoost mencapai weighted MAPE 4.51% pada komoditas mayor dan moderat, melampaui target penelitian (<10%) dengan margin signifikan. Performa sangat baik pada Tier 1 dengan MAPE 4.51% dan R² 0.8055 mengkonfirmasi kemampuan gradient boosting dalam menangkap pola non-linear pada komoditas utama. R² negatif pada Tier 4 adalah trade off yang diterima mengingat tier ini hanya berkontribusi 0.04% terhadap total konsumsi kalori nasional.

#### 4.5.3 Hasil Evaluasi Model LSTM

Model LSTM dengan arsitektur yang telah dioptimalkan (LSTM layer 128 units, Dense 64→32→1, dengan regularisasi) menghasilkan performa yang bervariasi antar tier. Tabel 29 menyajikan hasil evaluasi tiered LSTM pada data uji.

**Tabel 29. Hasil Evaluasi Tiered LSTM pada Data Uji**

| Tier | Sampel | Mean Actual (kkal) | MAPE | MAE | RMSE | R² |
|------|--------|-------------------|------|-----|------|-----|
| Tier 1 | 2,708 | 238.34 | 13.72% | 43.77 | 195.44 | 0.4975 |
| Tier 2 | 1,608 | 28.57 | 24.49% | 6.55 | 25.07 | -4.4344 |
| Tier 3 | 862 | 4.25 | 129.42% | 4.37 | 30.55 | -164.41 |
| Tier 4 | 384 | 0.37 | 4645.71% | 12.39 | 50.03 | -20657.00 |
| **Keseluruhan** | **5,562** | **124.78** | **14.48%** | **24.73** | **138.19** | **0.6128** |

Model LSTM menunjukkan weighted MAPE 14.48%, tidak memenuhi target <10%. R² 0.6128 mengindikasikan bahwa model hanya menjelaskan 61.28% variasi konsumsi kalori. Performa yang kurang optimal ini disebabkan oleh beberapa faktor: (1) data NBM memiliki karakteristik sparse dengan banyak nilai kecil yang sulit dipelajari LSTM, (2) volatilitas tinggi pada periode uji (2021-2024) yang mencakup pemulihan pasca pandemi dan krisis geopolitik, serta (3) LSTM cenderung overfitting pada pola kompleks namun kurang robust terhadap distribusi data yang highly skewed.

#### 4.5.4 Hasil Evaluasi Model HuberRegressor

Model HuberRegressor dengan konfigurasi epsilon=1.2 dan alpha=0.000001 menunjukkan performa yang sangat baik dan menjadi model individual terbaik. Tabel 30 menyajikan hasil evaluasi tiered HuberRegressor pada data uji.

**Tabel 30. Hasil Evaluasi Tiered HuberRegressor pada Data Uji**

| Tier | Sampel | Mean Actual (kkal) | MAPE | MAE | RMSE | R² |
|------|--------|-------------------|------|-----|------|-----|
| Tier 1 | 2,714 | 239.61 | 5.06% | 14.09 | 69.03 | 0.9378 |
| Tier 2 | 1,608 | 28.57 | 6.38% | 1.67 | 7.03 | 0.5730 |
| Tier 3 | 862 | 4.25 | 8.94% | 0.31 | 0.57 | 0.9426 |
| Tier 4 | 384 | 0.37 | 1921.68% | 5.26 | 23.60 | -4594.18 |
| **Keseluruhan** | **5,568** | **125.72** | **5.15%** | **7.76** | **48.74** | **0.9523** |

HuberRegressor mencapai weighted MAPE 5.15% dengan R² 0.9523 (tertinggi di antara semua model individual), menjelaskan 95.23% variasi konsumsi kalori. Performa superior ini mengkonfirmasi efektivitas robust regression untuk data NBM dengan karakteristik outliers. RMSE 48.74 kkal (terendah di antara model individual) mengindikasikan prediksi yang sangat akurat dan stabil.

#### 4.5.5 Hasil Evaluasi Model LSTM Enhanced Ensemble

LSTM Enhanced Ensemble dengan bobot optimal XGBoost 30%, LSTM 40%, dan Huber 30% menghasilkan performa yang seimbang antara akurasi dan robustness. Tabel 31 menyajikan hasil evaluasi tiered ensemble pada data uji.

**Tabel 31. Hasil Evaluasi Tiered LSTM Enhanced Ensemble pada Data Uji**

| Tier | Sampel | Mean Actual (kkal) | MAPE | MAE | RMSE | R² |
|------|--------|-------------------|------|-----|------|-----|
| Tier 1 | 2,708 | 238.34 | 7.25% | 23.95 | 107.80 | 0.8471 |
| Tier 2 | 1,608 | 28.57 | 10.40% | 2.77 | 11.01 | -0.0479 |
| Tier 3 | 862 | 4.25 | 59.11% | 1.91 | 12.28 | -25.74 |
| Tier 4 | 384 | 0.37 | 3170.47% | 7.96 | 28.27 | -6594.13 |
| **Keseluruhan** | **5,562** | **124.78** | **7.46%** | **13.31** | **75.97** | **0.8830** |

LSTM Enhanced Ensemble mencapai weighted MAPE 7.46%, melampaui target penelitian (<10%) dengan margin 25.4%. R² 0.8830 mengindikasikan bahwa ensemble menjelaskan 88.30% variasi konsumsi kalori, positioning ensemble sebagai keseimbangan optimal antara akurasi dan generalization. Performa pada Tier 1 (MAPE 7.25%) dan Tier 2 (MAPE 10.40%) yang berkontribusi 91.2% total konsumsi kalori mengkonfirmasi efektivitas ensemble untuk komoditas penting ketahanan pangan.

#### 4.5.6 Perbandingan Performa Keempat Model

Tabel 32 menyajikan perbandingan komprehensif performa keempat model pada data uji yang telah di-align (5,562 sampel) untuk memastikan fairness comparison.

**Tabel 32. Perbandingan Performa Model pada Data Uji (Aligned)**

| Model | Weighted MAPE | RMSE | MAE | R² | Kontribusi Ensemble |
|-------|---------------|------|-----|-----|---------------------|
| XGBoost | 4.49% | 85.41 | 11.25 | 0.8521 | 30% |
| LSTM | 14.48% | 137.83 | 25.37 | 0.6148 | 40% |
| HuberRegressor | 5.14% | 48.73 | 7.71 | 0.9519 | 30% |
| **LSTM Enhanced Ensemble** | **7.46%** | **75.97** | **13.31** | **0.8830** | **100%** |

Berdasarkan Tabel 32, terlihat bahwa meskipun XGBoost (4.49%) dan HuberRegressor (5.14%) memiliki MAPE individual lebih rendah dari ensemble (7.46%), kombinasi ketiga model memberikan beberapa keunggulan strategis:

1. **Robustness** - Ensemble lebih stabil terhadap pergeseran distribusi data akibat penggabungan berbagai perspektif dari gradient boosting, sequence modeling, dan robust regression.

2. **Generalization** - Mengurangi risiko overfitting dari single model melalui model averaging. XGBoost dengan R² 0.8521 dan HuberRegressor dengan R² 0.9519 berpotensi overfitting pada pola spesifik data latih.

3. **Temporal Awareness** - LSTM dengan bobot 40% memberikan kontribusi penting dalam menangkap pola temporal dan dependencies yang tidak dapat ditangkap oleh model tabular.

4. **Outlier Resistance** - HuberRegressor dengan bobot 30% inherited kemampuan mengelola periode krisis dan outliers legitimate.

Visualisasi prediksi aktual dengan prediksi pada Gambar 14 menunjukkan bahwa ensemble menghasilkan prediksi yang lebih smooth dan stabil dibandingkan LSTM individual, terutama pada region dengan volatilitas tinggi.

**Gambar 14. LSTM Enhancement Actual dengan Predicted pada Data Uji**


### 4.6 Penyebaran

#### 4.6.1 Arsitektur Sistem Informasi Berbasis Web

_[Konten belum tersedia]_

#### 4.6.2 Implementasi Backend dengan FastAPI

_[Konten belum tersedia]_

#### 4.6.3 Implementasi Frontend dengan Laravel

_[Konten belum tersedia]_

#### 4.6.4 Containerization dengan Docker

_[Konten belum tersedia]_

#### 4.6.5 Pengujian Sistem

_[Konten belum tersedia]_

### 4.7 Pembahasan

#### 4.7.1 Analisis Hasil Penelitian

_[Konten belum tersedia]_

#### 4.7.2 Kelebihan dan Keterbatasan Model

_[Konten belum tersedia]_

#### 4.7.3 Implikasi untuk Ketahanan Pangan Nasional

_[Konten belum tersedia]_

#### 4.7.4 Perbandingan dengan Penelitian Sejenis

_[Konten belum tersedia]_

---

## BAB V KESIMPULAN DAN SARAN

_[Konten belum tersedia]_

---

## DAFTAR PUSTAKA

_[Konten belum tersedia]_

---

## LAMPIRAN

_[Konten belum tersedia]_

---

## BIODATA

_[Konten belum tersedia]_