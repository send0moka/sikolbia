# PROPOSAL TUGAS AKHIR

## IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**SKRIPSI**

Disusun untuk memenuhi sebagian persyaratan untuk memperoleh gelar Sarjana Komputer Jurusan Informatika

**Disusun oleh:**  
Jehian Athaya Tsani Az Zuhry  
H1D022006

**KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI**  
**UNIVERSITAS JENDERAL SOEDIRMAN**  
**FAKULTAS TEKNIK**  
**JURUSAN INFORMATIKA**  
**PURWOKERTO**  
**2025**

---

## LEMBAR PENGESAHAN PROPOSAL

**Tugas Akhir dengan judul:**

### IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**Disusun oleh:**  
Jehian Athaya Tsani Az Zuhry  
H1D022006

Diajukan untuk memenuhi salah satu persyaratan memperoleh gelar Sarjana Komputer pada Jurusan Informatika Fakultas Teknik Universitas Jenderal Soedirman

**Diterima dan disetujui**  
Pada tanggal ………………………..

**Pembimbing I**  
Ir. Nofiyati, S.Kom., M.Kom., IPM.  
NIP. 198108192024212012

**Pembimbing II**  
Devi Astri Nawangnugraeni, S.Pd., M.Kom.  
NIP. 199312042024062004

**Dekan Fakultas Teknik**  
**Universitas Jenderal Soedirman**  
Prof. Dr. Eng. Ir. Agus Maryoto, S.T., M.T., IPU., ASEAN Eng.  
NIP. 197109202006041001

---

## KATA PENGANTAR

Puji syukur ke hadirat Tuhan Yang Maha Esa atas rahmat dan karunia-Nya, sehingga penulis dapat menyelesaikan proposal penelitian dengan judul "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian." Proposal ini disusun sebagai salah satu syarat untuk menempuh Tugas Akhir pada Jurusan Informatika, Fakultas Teknik, Universitas Jenderal Soedirman.

Dalam penyusunan proposal ini, penulis mendapatkan berbagai dukungan, arahan, serta masukan dari banyak pihak. Oleh karena itu, dengan penuh hormat dan rasa syukur, penulis menyampaikan terima kasih kepada:

1. Bapak Prof. Dr. Eng. Ir. Agus Maryoto, S.T., M.T., IPU., ASEAN Eng., selaku Dekan Fakultas Teknik, Universitas Jenderal Soedirman.
2. Bapak Dr. Ir. Lasmedi Afuan, S.T., M.Cs., IPM., selaku Ketua Jurusan Informatika, Fakultas Teknik, Universitas Jenderal Soedirman.
3. Bapak Ir. Bangun Wijayanto, S.T., M.Cs., IPM., selaku Dosen Pembimbing Akademik yang telah memberikan arahan akademik selama masa perkuliahan.
4. Ibu Nofiyati, S.Kom., M.Kom. IPM., selaku Dosen Pembimbing I dan Ibu Devi Astri Nawangnugraeni, S.Pd., M.Kom., selaku Dosen Pembimbing II yang dengan penuh perhatian telah memberikan bimbingan dan arahan selama penyusunan proposal ini.
5. Orang tua dan keluarga tercinta atas doa, semangat, serta dukungan yang tiada harganya.
6. Rekan-rekan seperjuangan di Jurusan Informatika angkatan 2022, serta seluruh pihak yang telah memberikan dukungan dan masukan.

Penulis menyadari proposal ini memiliki keterbatasan, sehingga kritik dan saran sangat diharapkan untuk perbaikan ke depan. Semoga karya ini dapat berkontribusi pada pengembangan ilmu, khususnya dalam bidang machine learning dan analisis data pangan, serta memberikan manfaat bagi perencanaan kebijakan ketahanan pangan nasional.

Purwokerto, 13 Oktober 2025  
**Jehian Athaya Tsani Az Zuhry**

---

## DAFTAR ISI

- [LEMBAR PENGESAHAN PROPOSAL](#lembar-pengesahan-proposal)
- [KATA PENGANTAR](#kata-pengantar)
- [DAFTAR ISI](#daftar-isi)
- [DAFTAR GAMBAR](#daftar-gambar)
- [DAFTAR TABEL](#daftar-tabel)
- [ABSTRAK](#abstrak)
- [BAB I. PENDAHULUAN](#bab-i-pendahuluan)
  - [1.1 Latar Belakang](#11-latar-belakang)
  - [1.2 Rumusan Masalah](#12-rumusan-masalah)
  - [1.3 Batasan Penelitian](#13-batasan-penelitian)
  - [1.4 Tujuan Penelitian](#14-tujuan-penelitian)
  - [1.5 Manfaat Penelitian](#15-manfaat-penelitian)
- [BAB II. TINJAUAN PUSTAKA](#bab-ii-tinjauan-pustaka)
  - [2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)](#21-ketahanan-pangan-dan-neraca-bahan-makanan-nbm)
  - [2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan](#22-time-series-forecasting-dan-prediksi-konsumsi-pangan)
  - [2.3 Neural Network dan Deep Learning](#23-neural-network-dan-deep-learning)
  - [2.4 Long Short-term Memory (LSTM) dan Metode Ensemble](#24-long-short-term-memory-lstm-dan-metode-ensemble)
  - [2.5 Metrik Evaluasi Model Prediksi](#25-metrik-evaluasi-model-prediksi)
  - [2.6 Arsitektur Sistem Laravel-FastAPI dan Docker](#26-arsitektur-sistem-laravel-fastapi-dan-docker)
  - [2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan](#27-penerapan-machine-learning-dalam-prediksi-konsumsi-pangan)
  - [2.8 Implementasi LSTM untuk Time Series Forecasting](#28-implementasi-lstm-untuk-time-series-forecasting)
  - [2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia](#29-penelitian-terkait-prediksi-konsumsi-pangan-di-indonesia)
  - [2.10 Gap Analysis](#210-gap-analysis)
  - [2.11 Kerangka Konseptual](#211-kerangka-konseptual)
- [BAB III. METODOLOGI](#bab-iii-metodologi)
  - [3.1 Data dan Alat Penelitian](#31-data-dan-alat-penelitian)
  - [3.2 Metode Penelitian](#32-metode-penelitian)
  - [3.3 Jadwal Penelitian](#33-jadwal-penelitian)
- [DAFTAR PUSTAKA](#daftar-pustaka)

---

## DAFTAR GAMBAR

- Gambar 1. Struktur Sel LSTM
- Gambar 2. Tahapan Research and Development
- Gambar 3. Diagram Alur CRISP-DM
- Gambar 4. Strategi Pembagian Data NBM Indonesia
- Gambar 5. Time Series Cross-Validation Expanding Window

---

## DAFTAR TABEL

- Tabel 1. Penelitian Sejenis
- Tabel 2. Jadwal Penelitian

---

## ABSTRAK

Ketahanan pangan merupakan isu kritis bagi Indonesia dengan peringkat ke-69 dari 113 negara pada Global Food Security Index 2024. Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak untuk mendukung perencanaan kebijakan ketahanan pangan nasional. Metode prediksi konvensional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola temporal konsumsi pangan.

Penelitian ini bertujuan mengimplementasikan model LSTM enhanced ensemble untuk memprediksi konsumsi kalori harian berdasarkan data Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%. Metodologi penelitian menggunakan pendekatan Research and Development (RnD) dengan kerangka kerja CRISP-DM. Data historis NBM mencakup 31 tahun dengan 372 titik data bulanan yang dibagi secara kronologis menjadi 70% data pelatihan, 15% data validasi, dan 15% data pengujian. Preprocessing menggunakan StandardScaler dan RobustScaler, sementara hyperparameter optimization dilakukan melalui time series cross-validation. Model ensemble menggabungkan LSTM dengan robust regression algorithms seperti HuberRegressor. Evaluasi menggunakan metrik RMSE, MAE, dan MAPE dengan perbandingan terhadap baseline models.

Model diintegrasikan ke dalam sistem informasi berbasis website menggunakan arsitektur microservices dengan Laravel, FastAPI, dan Docker. Hasil penelitian diharapkan memberikan sistem prediksi konsumsi kalori yang akurat untuk mendukung pengambilan keputusan dalam perencanaan ketahanan pangan nasional dan berkontribusi pada penerapan deep learning untuk agricultural forecasting di Indonesia.

**Kata Kunci:** LSTM, ensemble learning, prediksi konsumsi kalori, Neraca Bahan Makanan, ketahanan pangan, time series forecasting, deep learning, CRISP-DM, microservices architecture

---

## BAB I PENDAHULUAN

### 1.1 Latar Belakang

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik Indonesia. Data Global Food Security Index (GFSI) 2024 menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2, posisi yang masih tertinggal dibandingkan negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Sekretariat Jendral - Kementrian Pertanian, 2024). Dengan populasi lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan berkelanjutan yang diperparah oleh perubahan iklim dan volatilitas harga pangan (BPS, 2023).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak untuk mendukung perencanaan ketahanan pangan nasional. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola temporal konsumsi pangan (Sarku et al., 2023). Ketidakakuratan prediksi ini berimplikasi pada kerugian ekonomi signifikan, dengan Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat salah alokasi sumber daya dalam program ketahanan pangan periode 2020-2022 (Kementerian Pertanian, 2023).

Data Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 dengan 41,316 records menunjukkan volatilitas konsumsi kalori yang kompleks, dengan fluktuasi dari 2.156 kkal/kapita/hari hingga 2.978 kkal/kapita/hari (Sekretariat Jendral - Kementrian Pertanian, 2024). Dataset historis ini mencakup 120 komoditas pangan dari 11 kelompok utama dengan parameter produksi, impor, ekspor, dan konsumsi kalori per kapita per hari yang memberikan foundation komprehensif untuk analisis prediktif.

Long Short Term Memory (LSTM) sebagai varian Recurrent Neural Network telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long-term dependencies dan pola musiman kompleks (Alkahfi et al., 2024). Metode ensemble yang mengintegrasikan LSTM dengan robust regression algorithms menunjukkan peningkatan akurasi hingga 25-30% dibandingkan model tunggal (Howard & Augustine, 2025). Namun, belum ada penelitian yang menggunakan LSTM ensemble untuk prediksi konsumsi kalori agregat nasional berdasarkan data NBM Indonesia yang komprehensif.

Berdasarkan latar belakang di atas, penulis mengusulkan implementasi LSTM enhanced ensemble untuk prediksi konsumsi kalori harian nasional guna mengatasi keterbatasan metode konvensional dan memberikan sistem peringatan dini berbasis machine learning yang akurat untuk mendukung pengambilan keputusan dalam perencanaan ketahanan pangan Indonesia.

### 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

a. Bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal, teknik Robust preprocessing (StandardScaler dan RobustScaler), sequence generation yang tepat, dan evaluasi metrik RMSE, MAE, MAPE untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%?

b. Bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis website dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?

### 1.3 Batasan Penelitian

Adapun batasan dari penelitian ini adalah sebagai berikut:

a. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian.

b. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 dengan total 41,316 records yang mencakup 120 komoditas pangan dari 11 kelompok, bersumber dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian.

c. Prediksi yang dibuat terbatas pada konsumsi kalori harian agregat nasional per komoditi untuk mendukung decision support pemerintah, tidak mencakup prediksi personal atau regional.

d. Sistem informasi berbasis website ditujukan untuk stakeholder pemerintah (Kementerian Pertanian) dengan arsitektur Laravel, FastAPI, dan Docker untuk national food security forecasting.

e. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi dengan target MAPE < 10% berdasarkan standar industri dan literatur terkait.

f. Penelitian ini tidak mencakup pengembangan aplikasi mobile atau personal health tracking, hanya fokus pada sistem national-level forecasting.

### 1.4 Tujuan Penelitian

Adapun tujuan dari penelitian ini adalah sebagai berikut:

a. Mengimplementasikan model LSTM enhanced ensemble untuk prediksi konsumsi kalori harian agregat nasional per komoditi dengan memanfaatkan data historis NBM Indonesia periode 1993-2024 dan teknik time-aware preprocessing untuk mencegah data leakage.

b. Melakukan preprocessing dan feature engineering pada dataset NBM 41,316 records menggunakan StandardScaler dan RobustScaler dengan metode yang aman secara temporal untuk optimalisasi performa model ensemble.

c. Mengevaluasi performa model LSTM enhanced ensemble dalam memprediksi konsumsi kalori nasional menggunakan metrik RMSE, MAE, dan MAPE dengan target akurasi MAPE < 10% untuk mendukung decision support sistem ketahanan pangan.

d. Mengintegrasikan model ensemble yang telah dilatih ke dalam sistem informasi berbasis website dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan prediksi konsumsi kalori secara real-time.

### 1.5 Manfaat Penelitian

Manfaat dari penelitian ini adalah sebagai berikut:

#### a. Bagi Peneliti

Penelitian ini memberikan pengalaman praktis dalam penerapan algoritma LSTM untuk prediksi time series konsumsi pangan, sekaligus mengembangkan keterampilan dalam implementasi deep learning dan pengembangan sistem informasi terintegrasi dengan arsitektur microservices. Selain itu, hasil penelitian ini diharapkan dapat menjadi referensi untuk penelitian atau proyek serupa di masa depan.

#### b. Bagi Pembaca

Penelitian ini memberikan wawasan mengenai penerapan algoritma LSTM dalam prediksi konsumsi kalori berbasis data NBM dan menyajikan informasi yang bermanfaat bagi akademisi dan praktisi yang ingin mengembangkan sistem prediksi ketahanan pangan.

#### c. Bagi Pemerintah dan Masyarakat

Penelitian ini membantu Badan Pangan Nasional, Kementerian Pertanian, dan pengambil kebijakan dalam perencanaan ketahanan pangan nasional melalui sistem peringatan dini berbasis machine learning dengan akurasi tinggi. Sistem menyediakan early warning system untuk antisipasi krisis pangan dan mendukung transparansi informasi prediksi konsumsi nasional untuk meningkatkan kesadaran publik tentang ketahanan pangan Indonesia.

---

## BAB II TINJAUAN PUSTAKA

### 2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)

Ketahanan pangan didefinisikan sebagai kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan, yang tercermin dari tersedianya pangan yang cukup, baik jumlah maupun mutunya, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat untuk dapat hidup sehat, aktif, dan produktif secara berkelanjutan (Badan Pangan Nasional, 2021). Konsep ini mencakup empat pilar utama: ketersediaan (availability), keterjangkauan (accessibility), pemanfaatan (utilization), dan stabilitas (stability) yang saling berinteraksi dalam sistem pangan nasional (FAO, 2023).

Neraca Bahan Makanan (NBM) menggunakan formula dasar untuk menghitung konsumsi per kapita. Persamaan (1) berikut menunjukkan formula tersebut:

```
Konsumsi per kapita = Ketersediaan Bersih / (Jumlah Penduduk × 365 hari)  ... (1)
```

Ketersediaan Bersih dihitung dengan persamaan (2) yang disajikan sebagai berikut:

```
Ketersediaan Bersih = Produksi + Impor − Ekspor ± ΔStok − Non-Food Uses  ... (2)
```

Perubahan stok (Δstok) adalah perubahan stok (positif jika berkurang, negatif jika bertambah) dan non-food uses merupakan penggunaan untuk pakan ternak, industri, dan lain sebagainya.

Konversi ke kalori menggunakan faktor konversi energi. Persamaan (3) berikut menunjukkan proses konversi tersebut:

```
Kalori per kapita per hari = [Konsumsi per kapita (kg/hari) × Faktor Konversi Energi (kkal/100g)] / 10  ... (3)
```

Neraca Bahan Makanan (NBM) merupakan instrumen penting dalam monitoring ketahanan pangan yang menyajikan gambaran menyeluruh tentang situasi pangan suatu negara dalam kurun waktu tertentu (Sekretariat Jendral - Kementrian Pertanian, 2024). NBM mengintegrasikan data produksi, impor, ekspor, perubahan stok, dan penggunaan untuk pakan ternak serta industri, sehingga menghasilkan angka konsumsi per kapita yang akurat. Data NBM Indonesia telah dikompilasi sejak tahun 1993 dan mencakup lebih dari 60 komoditas pangan utama dengan parameter konsumsi kalori, protein, dan lemak per kapita per hari.

### 2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan

Time Series Forecasting adalah teknik analisis data historis yang diamati dalam urutan waktu tertentu untuk memprediksi nilai-nilai masa depan (Arwansyah et al., 2022). Model ARIMA (Autoregressive Integrated Moving Average) dapat dinyatakan sebagai ARIMA(p,d,q) dengan persamaan (4) yang disajikan sebagai berikut:

```
(1 − φ₁L − φ₂L² − ⋯ − φₚLᵖ)(1 − L)ᵈXₜ = (1 + θ₁L + θ₂L² + ⋯ + θₑLᵠ)εₜ  ... (4)
```

L adalah lag operator, φᵢ adalah autoregressive parameters, θⱼ adalah moving average parameters, d adalah degree of differencing, dan εₜ adalah white noise error term.

Exponential smoothing menggunakan weighted average dari observasi masa lalu dengan formula yang ditunjukkan pada persamaan (5) berikut:

```
Sₜ = αΧₜ + (1 − α)Sₜ₋₁  ... (5)
```

Sₜ adalah smoothed value pada waktu t, α adalah smoothing parameter (0 < α < 1), dan Χₜ adalah actual value pada waktu t.

Dalam konteks ketahanan pangan, forecasting konsumsi memiliki karakteristik unik berupa pola musiman yang dipengaruhi oleh faktor musim panen, hari raya keagamaan, dan kondisi ekonomi makro. Konsumsi pangan menunjukkan pola temporal yang kompleks dengan komponen tren jangka panjang, siklus musiman, dan fluktuasi tidak teratur yang memerlukan pendekatan model yang sophisticated (Siregar et al., 2024).

Metode konvensional seperti ARIMA dan exponential smoothing telah lama digunakan untuk prediksi konsumsi pangan, namun memiliki keterbatasan dalam menangkap non-linear relationships dan long-term dependencies yang karakteristik pada data konsumsi pangan (Cahyani et al., 2023). Keterbatasan ini mendorong pengembangan pendekatan machine learning yang lebih advanced untuk meningkatkan akurasi prediksi.

### 2.3 Neural Network dan Deep Learning

Neural Network adalah computational model yang terinspirasi dari struktur dan fungsi jaringan syaraf biologis, terdiri dari nodes (neurons) yang saling terhubung dan mampu belajar pola kompleks dari data training (Benos et al., 2021). Forward propagation pada fully connected layer dinyatakan dengan persamaan (6) dan (7) sebagai berikut:

```
z[l] = W[l]a[l-1] + b[l]  ... (6)
a[l] = g[l](z[l])  ... (7)
```

z[l] adalah linear output layer ke-l, W[l] adalah weight matrix layer ke-l, a[l-1] adalah activation dari layer sebelumnya, b[l] adalah bias vector, dan g[l] adalah activation function.

Activation functions yang umum digunakan ditunjukkan pada persamaan (8), (9), dan (10) sebagai berikut:

```
σ(z) = 1 / (1 + e⁻ᶻ)  ... (8)
tanh(z) = (e² − e⁻²) / (e² + e⁻²)  ... (9)
ReLU(z) = max(0, z)  ... (10)
```

Backpropagation untuk update weights menggunakan persamaan (11) dan (12) yang disajikan sebagai berikut:

```
∂L/∂W[l] = (∂L/∂z[l]) · (∂z[l]/∂W[l]) = δ[l] · (α[l-1])ᵀ  ... (11)
W[l] ≔ W[l] − α(∂L/∂W[l])  ... (12)
```

L adalah loss function, δ[l] adalah error signal layer ke-l, dan α adalah learning rate.

Deep Learning merupakan subset dari machine learning yang menggunakan neural networks dengan multiple hidden layers untuk ekstraksi fitur hierarkis dan pembelajaran representasi yang sophisticated. Arsitektur deep learning telah terbukti superior dalam menangani high-dimensional data dan complex pattern recognition tasks, termasuk aplikasi dalam agricultural domain (Opara et al., 2024). Keunggulan utama deep learning terletak pada kemampuan automatic feature extraction, yang mengeliminasi kebutuhan manual feature engineering yang memakan waktu dan subjektif dalam machine learning pendekatan tradisional.

### 2.4 Long Short-term Memory (LSTM) dan Metode Ensemble

Long Short-term Memory (LSTM) adalah specialized recurrent Neural Network architecture yang dirancang untuk mengatasi vanishing gradient problem dalam traditional RNNs, sehingga mampu menangkap Long-term Dependencies dalam sequential data (Kong et al., 2025). LSTM memiliki cell state mechanism yang memungkinkan selective retention dan forgetting informasi melalui three gates: forget gate, input gate, dan output gate. Struktur sel LSTM secara detail dapat dilihat pada Gambar 1 yang menunjukkan interaksi antar komponen dalam arsitektur LSTM.

**Gambar 1. Struktur Sel LSTM**

Forget Gate menentukan informasi yang akan dihapus dari cell state dengan persamaan (13) yang ditunjukkan sebagai berikut:

```
fₜ = σ(Wf · [hₜ₋₁, xₜ] + bf)  ... (13)
```

fₜ adalah forget gate output pada waktu t, σ adalah sigmoid function, Wf adalah weight matrix untuk forget gate, hₜ₋₁ adalah hidden state sebelumnya, xₜ adalah input pada waktu t, dan bf adalah bias vector untuk forget gate.

Input Gate memutuskan nilai-nilai baru yang akan disimpan dalam cell state. Persamaan (14) dan (15) berikut menunjukkan proses tersebut:

```
iₜ = σ(Wᵢ · [hₜ₋₁, xₜ] + bᵢ)  ... (14)
C̅ₜ = tanh(Wc · [hₜ₋₁, xₜ] + bc)  ... (15)
```

iₜ adalah input gate output, C̅ₜ adalah kandidat nilai cell state baru, Wᵢ, Wc adalah weight matrices, dan bᵢ, bc adalah bias vectors.

Cell state update menggabungkan informasi lama dan baru. Persamaan (16) berikut menunjukkan proses tersebut:

```
Cₜ = fₜ * Cₜ₋₁ + iₜ * C̅ₜ  ... (16)
```

Output Gate menentukan bagian cell state yang akan menjadi output dengan persamaan (17) dan (18) yang disajikan sebagai berikut:

```
oₜ = σ(Wo · [hₜ₋₁, xₜ] + b₀)  ... (17)
hₜ = oₜ * tanh(Cₜ)  ... (18)
```

oₜ adalah output gate dan hₜ adalah hidden state output pada waktu t.

Dalam konteks ensemble learning untuk time series forecasting, LSTM dapat dikombinasikan dengan robust regression algorithms seperti HuberRegressor. HuberRegressor menggunakan huber loss function yang menggabungkan MSE untuk error kecil dan MAE untuk error besar. Persamaan (19) berikut menunjukkan fungsi tersebut:

```
           ⎧ ½(y − f(x))²                  untuk |y − f(x)| ≤ δ
Lδ(y,f(x)) = ⎨
           ⎩ δ|y − f(x)| − ½δ²            untuk |y − f(x)| > δ
... (19)
```

y adalah nilai aktual, f(x) adalah nilai prediksi, dan δ adalah threshold parameter (biasanya 1.35).

LSTM enhanced ensemble menggabungkan temporal pattern recognition capabilities dari LSTM dengan Robust statistical properties dari Regression Algorithms. Ensemble prediction dihitung menggunakan weighted averaging. Persamaan (20) berikut menunjukkan proses tersebut:

```
ŷensemble = Σ(i=1 to n) wᵢ · ŷᵢ  ... (20)
```

dengan constraint Σ(i=1 to n) wᵢ = 1 dan wᵢ ≥ 0, ŷensemble adalah prediksi ensemble, wᵢ adalah weight untuk model ke-i, ŷᵢ adalah prediksi dari model ke-i, dan n adalah jumlah model dalam ensemble.

Adam Optimizer yang umum digunakan untuk training LSTM menggunakan persamaan (21), (22), (23), dan (24) yang disajikan sebagai berikut:

```
mₜ = β₁mₜ₋₁ + (1 − β₁)gₜ  ... (21)
vₜ = β₂vₜ₋₁ + (1 − β₂)gₜ²  ... (22)
m̂ₜ = mₜ / (1 − β₁ᵗ)  ... (23)
θₜ₊₁ = θₜ − (α / √(v̂ₜ + ε)) m̂ₜ  ... (24)
```

gₜ adalah gradient pada step t, mₜ, vₜ adalah first dan second moment estimates, β₁, β₂ adalah decay rates (biasanya 0.9 dan 0.999), α adalah learning rate, dan ϵ adalah small constant untuk numerical stability.

Hyperparameter optimization dalam ensemble setting mencakup not only LSTM-specific parameters (learning rate, batch size, epochs, window size) tetapi juga ensemble configuration seperti model weights, voting mechanisms, dan regularization parameters untuk preventing overfitting across multiple models.

### 2.5 Metrik Evaluasi Model Prediksi

Evaluasi performa model prediksi menggunakan multiple metrics untuk memastikan comprehensive assessment. Root Mean Square Error (RMSE) mengukur standard deviation dari residuals dan memberikan penalty yang lebih besar untuk large errors. Persamaan (25) berikut menunjukkan formula tersebut:

```
RMSE = √(1/n ∑(yᵢ − ŷᵢ)²)  ... (25)
```

yᵢ adalah nilai aktual, ŷᵢ adalah nilai prediksi, dan n adalah jumlah observasi.

Mean Absolute Error (MAE) memberikan average magnitude of errors tanpa mempertimbangkan direction. Persamaan (26) berikut menunjukkan formula tersebut:

```
MAE = (1/n) ∑|yᵢ − ŷᵢ|  ... (26)
```

Mean Absolute Percentage Error (MAPE) mengukur akurasi dalam bentuk persentase, memudahkan interpretasi dengan persamaan (27) yang ditunjukkan sebagai berikut:

```
MAPE = (100%/n) ∑|(yᵢ − ŷᵢ)/yᵢ|  ... (27)
```

RMSE lebih sensitif terhadap outliers dibandingkan MAE karena menggunakan squared errors, sementara MAE lebih robust terhadap outliers dan memberikan equal weight untuk semua errors (Raharjo et al., 2022). MAPE memberikan interpretasi yang intuitif dalam bentuk persentase error, namun dapat menghasilkan nilai infinite atau sangat besar ketika nilai aktual mendekati nol.

### 2.6 Arsitektur Sistem Laravel-FastAPI dan Docker

Implementasi sistem prediksi modern memerlukan arsitektur yang memisahkan concerns antara user interface, business logic, dan machine learning processing dengan deployment strategy yang scalable (Kamil et al., 2024). Laravel menyediakan robust foundation untuk pengembangan aplikasi website dengan features seperti Eloquent ORM, Livewire reactive components, dan Blade templating engine yang memudahkan development of interactive dashboard dan real-time user interactions.

FastAPI merupakan modern kerangka kerja python website yang dioptimalkan untuk membangun APIs dengan dokumentasi OpenAPI otomatis dan dukungan bawaan untuk pemrograman asinkron. FastAPI sangat cocok untuk machine learning karena integrasi native dengan ekosistem scientific Python (NumPy, Pandas, scikit-learn) dan performa tinggi yang comparable dengan NodeJS dan Go.

Docker memungkinkan lingkungan deployment yang konsisten di seluruh tahap pengembangan, pengujian, dan produksi. Arsitektur berbasis kontainer memastikan reproduibilitas dan portabilitas dari machine learning, mengeliminasi isu "it works on my machine" yang umum terjadi dalam machine learning deployment (Benos et al., 2021). Pengaturan multi-kontainer dengan Docker Compose memungkinkan pemisahan tanggung jawab antara aplikasi website, layanan machine learning, basis data, dan caching layers.

Session management dan caching optimization menggunakan Redis untuk caching data berkecepatan tinggi dan penyimpanan sesi pengguna, mengurangi beban database dan meningkatkan waktu respons untuk permintaan prediksi yang sering. Arsitektur microservices dengan Laravel sebagai layanan frontend dan FastAPI sebagai layanan backend machine learning memungkinkan skalabilitas independen, fleksibilitas teknologi, dan pemeliharaan yang lebih mudah melalui pengikatan longgar dan kohesi tinggi dalam desain sistem.

### 2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan

Ulasan sistematis terhadap penerapan machine learning dalam ketahanan pangan menunjukkan tren yang semakin meningkat dalam penggunaan algoritma canggih untuk forecasting pertanian (Siregar et al., 2024). Penelitian di India mengimplementasikan LSTM untuk prediksi crop production dengan data sintetis, mencapai akurasi yang secara signifikan lebih baik dibandingkan dengan metode tradisional (Raharjo et al., 2022). Namun, aplikasi pada forecasting konsumsi pangan nasional masih terbatas, terutama di negara berkembang.

Sarku et al. (2023) melakukan tinjauan komprehensif terhadap aplikasi kecerdasan buatan (AI) dalam ketahanan pangan, mengidentifikasi bahwa sebagian besar studi berfokus pada forecasting produksi daripada forecasting konsumsi. Kesenjangan ini menunjukkan peluang untuk mengembangkan model yang berfokus pada konsumsi yang dapat mendukung pengambilan keputusan kebijakan dalam perencanaan ketahanan pangan. Penelitian tersebut juga menekankan pentingnya data historis berkualitas tinggi untuk melatih model yang efektif.

### 2.8 Implementasi LSTM untuk Time Series Forecasting

Arwansyah et al. (2022) melakukan survei mendalam terhadap pendekatan deep learning untuk forecasting deret waktu, mengonfirmasi keunggulan LSTM dalam menangani data berurutan dengan pola temporal yang kompleks. Penelitian tersebut menunjukkan bahwa LSTM sangat efektif untuk forecasting multi-step ahead dengan cakupan forecasting yang panjang, yang sangat relevan untuk perencanaan ketahanan pangan.

Kong et al. (2025) dalam survei menyeluruh terbaru teridentifikasi bahwa variasi LSTM seperti Bidirectional LSTM dan attention-based LSTM menunjukkan hasil yang menjanjikan untuk tugas prediksi yang kompleks. Namun, penelitian tersebut juga menekankan pentingnya pengaturan hyperparameter yang tepat dan pengolahan awal data untuk mencapai performa optimal. Cahyani et al. (2023) membandingkan performa LSTM dan BiLSTM dalam tugas prediksi, yang menunjukkan bahwa pemilihan model harus disesuaikan dengan karakteristik dari dataset tertentu.

### 2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia

Penelitian dalam negeri mengenai prediksi konsumsi pangan masih sebagian besar menggunakan metode statistik konvensional. Cahyani et al. (2023) menerapkan LSTM untuk prediksi harga bahan pokok nasional dan berhasil mencapai MAPE 8,2% untuk komoditas beras, yang menunjukkan potensi penerapan LSTM dalam sistem pangan Indonesia. Akan tetapi, penelitian tersebut hanya terfokus pada forecasting harga dan belum mencakup forecasting konsumsi.

Fadila & Putri (2023) melakukan analisis perkembangan ketahanan pangan di Indonesia menggunakan big data, namun fokus pada analisis deskriptif daripada pemodelan prediktif. Penelitian tersebut mengidentifikasi ketersediaan dan kualitas data menjadi tantangan besar dalam pengembangan sistem prediksi lanjutan untuk ketahanan pangan Indonesia.

Ringkasan penelitian sejenis yang terkait dengan prediksi konsumsi pangan dan penerapan LSTM dapat dilihat pada Tabel 1 yang menunjukkan perbandingan hasil penelitian terdahulu dengan pendekatan yang akan digunakan dalam penelitian ini.

**Tabel 1. Penelitian Sejenis**

| No | Peneliti (Tahun) | Judul | Hasil | Perbedaan |
|----|------------------|-------|-------|-----------|
| 1 | Cahyani et al. (2023) | Implementasi Metode Long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional | MAPE 8,2% untuk prediksi harga beras | Fokus pada prediksi harga, bukan konsumsi kalori; tidak menggunakan metode ensemble |
| 2 | Fadila & Putri (2023) | Analisis Perkembangan Ketahanan Pangan di Indonesia: Pendekatan Menggunakan Big Data dan Data Mining | Analisis deskriptif ketahanan pangan menggunakan big data | Tidak melakukan pemodelan prediktif; hanya analisis deskriptif |
| 3 | Serrano et al. (2024) | Statistical Comparison of Time Series Models for Brazilian Monthly Energy Demand | MAPE 14,8% menggunakan metode statistik tradisional | Menggunakan metode konvensional; tidak menerapkan deep learning |
| 4 | Sun et al. (2024) | Agricultural Commodity Price Prediction Model Based on Secondary Decomposition and LSTM | MAPE 9,7% untuk prediksi harga pangan di India | Fokus pada harga komoditas; tidak menggunakan metode ensemble untuk konsumsi |
| 5 | Adhany et al. (2025) | Prediksi Padi Menggunakan Algoritma Long Short Term Memory | MAPE 12,4% untuk prediksi produksi gandum di China | Fokus pada produksi komoditas tunggal; tidak menggunakan data NBM Indonesia |

### 2.10 Gap Analysis

Berdasarkan tinjauan literatur sistematis, teridentifikasi beberapa gap kritis dalam penelitian yang ada:

a. **Keterbatasan Ruang Lingkup**  
   Mayoritas penelitian fokus pada prediksi tingkat regional atau komoditas tunggal, belum ada yang menangani peramalan konsumsi kalori tingkat nasional menggunakan dataset NBM yang komprehensif (Siregar et al., 2024).

b. **Kesenjangan Metodologis**  
   Terbatasnya penerapan arsitektur deep learning mutakhir seperti LSTM untuk forecasting konsumsi pangan dalam konteks negara berkembang (Asian Development Bank, 2023).

c. **Pemanfaatan Data**  
   Kurangnya pemanfaatan dataset historis jangka panjang yang tersedia, dengan mayoritas studi menggunakan data jangka pendek (< 10 tahun) yang tidak memadai untuk menangkap pola jangka panjang (Arwansyah et al., 2022).

d. **Kesenjangan Implementasi**  
   Kurangnya sistem terintegrasi yang menggabungkan model prediktif dengan antarmuka yang mudah digunakan untuk aplikasi kebijakan (Opara et al., 2024).

### 2.11 Kerangka Konseptual

Kerangka konseptual penelitian ini menggambarkan alur sistematis dari preprocessing data hingga penerapan sistem prediktif terintegrasi. Kerangka penelitian mengadopsi metodologi CRISP-DM dengan fokus spesifik pada implementasi LSTM dan arsitektur microservices (Schröer et al., 2021).

Input utama penelitian berupa data NBM historis (1993-2024) yang mencakup time series konsumsi kalori per kapita, akan diproses melalui tahap persiapan data komprehensif meliputi normalisasi, feature scaling, dan sequence generation. Model ensemble LSTM akan dikembangkan dan dilatih dengan konfigurasi hyperparameter optimal untuk mencapai target akurasi MAPE < 10%.

Model yang telah tervalidasi akan diintegrasikan dalam arsitektur microservices dengan layanan frontend Laravel untuk antarmuka pengguna dan visualisasi dashboard, layanan backend FastAPI untuk serving model machine learning, dan database MySQL untuk persistensi data. Arsitektur ini memungkinkan penerapan yang dapat diskalakan dan kemampuan prediksi real-time yang mendukung pengambilan keputusan berbasis bukti dalam perencanaan ketahanan pangan (Kamil et al., 2024).

Alur kerja sistem dimulai dari permintaan pengguna melalui antarmuka website Laravel, yang kemudian mengirim panggilan API ke layanan FastAPI untuk inferensi model. Hasil dari prediksi akan di-cache dalam database MySQL dan ditampilkan melalui dashboard visualisasi interaktif. Kerangka konseptual ini memberikan peta jalan yang jelas untuk mencapai tujuan penelitian sambil memastikan ketepatan teoritis dan penerapan praktis dari sistem yang dikembangkan.

---

## BAB III METODOLOGI

### 3.1 Data dan Alat Penelitian

Penelitian ini memerlukan spesifikasi data, perangkat lunak, perangkat keras, dan lingkungan pengembangan yang tepat untuk mendukung implementasi model LSTM enhanced ensemble secara optimal. Bagian ini menjelaskan secara detail komponen-komponen fundamental yang digunakan dalam pengembangan sistem prediksi konsumsi kalori berbasis machine learning, mulai dari sumber data historis NBM Indonesia hingga infrastruktur teknologi yang mendukung arsitektur microservices.

#### a. Data Penelitian

Data yang digunakan dalam penelitian ini bersumber dari Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 yang diperoleh dari Badan Pangan Nasional, Pusat Data dan Sistem Informasi Kementerian Pertanian, dan Badan Pusat Statistika. Dataset mencakup 31 tahun data historis dengan 41,316 records transaksi NBM yang mencakup 120 komoditas pangan dari 11 kelompok utama.

Melalui proses agregasi temporal, data transaksi individual ini menghasilkan 372 titik data time series bulanan untuk konsumsi kalori nasional (31 tahun × 12 bulan), memberikan foundation yang solid untuk pengembangan model prediksi time series. Target variabel penelitian adalah konsumsi kalori per kapita per hari agregat nasional yang diukur dalam satuan kkal/kapita/hari.

Struktur data NBM yang digunakan dalam penelitian ini dapat dilihat pada Tabel 2 yang menunjukkan format record transaksi dengan field utama meliputi tahun, bulan, kode kelompok komoditas, kode komoditas spesifik, dan nilai kalori per hari untuk setiap komoditas pangan.

**Tabel 2. Struktur Data NBM Indonesia**

| Tahun | Bulan | Kelompok | Komoditi | Kalori/Hari |
|-------|-------|----------|----------|-------------|
| 1993 | 01 | 01 | 0101 | 892.45 |
| 1993 | 01 | 01 | 0102 | 45.12 |
| 1993 | 01 | 02 | 0201 | 234.78 |
| 1993 | 01 | 03 | 0301 | 123.56 |
| 1993 | 01 | 03 | 0302 | 123.56 |

#### b. Perangkat Lunak

Penelitian menggunakan kombinasi teknologi untuk pengembangan sistem prediksi. Untuk pengembangan machine learning, bahasa pemrograman utama yang digunakan adalah Python 3.8+ dengan library TensorFlow/Keras untuk implementasi model LSTM, Scikit-learn untuk algoritma ensemble dan preprocessing, serta Pandas dan NumPy untuk manipulasi dan analisis data. Visualisasi data dilakukan menggunakan Matplotlib dan Seaborn.

Pengembangan aplikasi website menggunakan framework Laravel 11 sebagai backend dengan Livewire 3 untuk komponen frontend yang reaktif. Database management system yang digunakan adalah MySQL 8.0, sedangkan untuk layanan machine learning inference menggunakan FastAPI sebagai microservice.

Untuk deployment dan DevOps, penelitian menggunakan Docker untuk containerization dan konsistensi environment, Docker Compose untuk orkestrasi multi-service, serta Git untuk version control dan collaborative development.

#### c. Perangkat Keras

Pengembangan dan pengujian sistem dilakukan menggunakan laptop MSI GF63 Thin 10UC dengan prosesor Intel Core i5-10500H (6 cores, 12 logical processors) dengan base speed 2.50 GHz, memori 16 GB RAM DDR4 2933 MT/s untuk training model dan pemrosesan data, serta storage SSD KINGSTON OM8PCP3512F-AI1 kapasitas 477 GB untuk menyimpan dataset dan model artifacts. GPU NVIDIA GeForce RTX 3050 Laptop GPU dengan 4 GB dedicated memory digunakan untuk mempercepat proses training model LSTM.

Untuk deployment production, sistem didesain agar dapat berjalan pada infrastruktur cloud dengan spesifikasi yang dapat disesuaikan sesuai kebutuhan load dan performance requirements.

#### d. Lingkungan Pengembangan

Penelitian menggunakan Visual Studio Code sebagai code editor utama dan Jupyter Notebook untuk exploratory data analysis serta prototyping. phpMyAdmin digunakan untuk desain dan manajemen database, sedangkan Postman digunakan untuk testing dan validasi API. GitHub digunakan sebagai platform hosting repository dan kolaborasi pengembangan.

### 3.2 Metode Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development (RnD) yang terintegrasi dengan framework CRISP-DM untuk pengembangan model machine learning. Pendekatan kuantitatif dipilih karena penelitian melibatkan analisis data numerik time series konsumsi kalori dan evaluasi performa model menggunakan metrik statistik (Sukarna & Ansori, 2022).

Research and Development (RnD) adalah metode penelitian yang bertujuan untuk menghasilkan produk tertentu dan menguji keefektifan produk tersebut (Okpatrioka, 2023). Dalam konteks penelitian ini, produk yang dikembangkan berupa sistem prediksi konsumsi kalori berbasis LSTM enhanced ensemble yang terintegrasi dengan antarmuka website untuk stakeholder pemerintah.

Penelitian mengadopsi experimental design dengan controlled variables untuk menguji performa berbagai konfigurasi model LSTM dan membandingkannya dengan metode baseline. Integrasi RnD dengan CRISP-DM memungkinkan pendekatan sistematis dari business understanding hingga deployment yang sesuai dengan standar industri machine learning.

Tahapan RnD yang dimodifikasi dengan integrasi CRISP-DM terdiri dari sepuluh tahap sistematis yang digambarkan pada Gambar 2 yang mencakup proses pengembangan sistematis dari penelitian awal hingga diseminasi produk final. Metodologi ini memiliki karakteristik unik dengan feedback loops antar tahap untuk memastikan iterasi perbaikan yang berkelanjutan dan integrasi CRISP-DM pada tahap Early Test untuk standardisasi pengembangan model machine learning.

**Gambar 2. Tahapan Research and Development**

Setiap tahap RnD dalam penelitian ini dijelaskan sebagai berikut:

a. **Research and Collection Preliminary**  
   Tahap ini melakukan kajian pustaka mendalam terkait metode prediksi time series, algoritma LSTM, dan ensemble learning dalam konteks prediksi konsumsi pangan. Identifikasi kebutuhan pengguna sistem dilakukan melalui analisis stakeholder dan review dokumen kebijakan ketahanan pangan. Pengumpulan data historis NBM Indonesia periode 1993-2024 sebagai foundation dataset untuk pengembangan model.

b. **Research Planning**  
   Menyusun blueprint arsitektur sistem prediksi yang mencakup komponen machine learning dan antarmuka website. Menentukan algoritma utama (LSTM enhanced ensemble) dan teknologi pendukung (Laravel, FastAPI, dan Docker). Merancang kerangka metodologi penelitian dengan mengadopsi CRISP-DM sebagai kerangka kerja pengembangan model machine learning.

c. **Early Product Development**  
   Membangun struktur dasar model LSTM ensemble dan merancang antarmuka aplikasi berbasis website. Implementasi preprocessing pipeline untuk data NBM dan pengembangan baseline models untuk comparison. Tahap ini menghasilkan prototipe awal sistem prediksi.

d. **Expert Validation**  
   Melakukan evaluasi rancangan sistem bersama pakar machine learning dan domain expert ketahanan pangan. Validasi mencakup review arsitektur model, kesesuaian teknik preprocessing, dan relevansi dengan kebutuhan praktis dalam perencanaan ketahanan pangan.

e. **Product Revision**  
   Melakukan penyempurnaan rancangan berdasarkan feedback dari tahap validasi. Revisi dapat mencakup modifikasi arsitektur model, perbaikan preprocessing pipeline, atau penyesuaian antarmuka pengguna sesuai dengan saran expert.

f. **Early Test (Implementasi Model LSTM Enhanced Ensemble)**  
   Tahap ini merupakan implementasi lengkap model LSTM enhanced ensemble dengan metodologi CRISP-DM yang diintegrasikan dalam kerangka RnD. Implementasi mengikuti fase-fase CRISP-DM secara sistematis untuk memastikan structured progression dari data understanding hingga model evaluation. Pengujian dilakukan terhadap performa model menggunakan data training dan validation dengan metrik evaluasi RMSE, MAE, dan MAPE.

g. **Product Revision**  
   Menyempurnakan model dan sistem berdasarkan hasil pengujian tahap sebelumnya. Optimisasi hyperparameter, perbaikan ensemble configuration, dan enhancement antarmuka pengguna berdasarkan hasil testing.

h. **Field Test**  
   Melakukan pengujian komprehensif menggunakan data testing (2020-2024) dalam kondisi real-world scenarios. Testing mencakup accuracy assessment, performa sistem, dan usability evaluation dengan potential users.

i. **Final Product Revision**  
   Melakukan penyempurnaan akhir sistem berdasarkan evaluasi dari uji coba lapangan. Finalisasi konfigurasi model, sistem deployment, dan dokumentasi lengkap untuk production use.

j. **Dissemination**  
   Menyusun dokumentasi lengkap sistem, panduan penggunaan, dan laporan penelitian. Persiapan untuk knowledge transfer dan potential adoption oleh stakeholder terkait dalam perencanaan ketahanan pangan.

Tahapan implementasi model LSTM enhanced ensemble dalam tahap Early Test mengikuti kerangka kerja CRISP-DM yang digambarkan pada Gambar 3, menunjukkan systematic progression dari data understanding hingga model deployment (Schröer et al., 2021).

**Gambar 3. Diagram Alur CRISP-DM**

Tahapan implementasi LSTM mengikuti kerangka kerja CRISP-DM sebagai berikut:

#### a. Business Understanding

Fase pertama dari metodologi CRISP-DM fokus pada pemahaman mendalam terhadap konteks ketahanan pangan Indonesia dan persyaratan khusus untuk sistem prediksi yang akan dikembangkan. Tahap ini dimulai dengan analisis stakeholder untuk mengidentifikasi pihak-pihak kunci seperti Badan Pangan Nasional, Kementerian Pertanian, dan para pengambil kebijakan, serta memahami proses pengambilan keputusan mereka dalam perencanaan ketahanan pangan.

Problem definition dilakukan secara sistematis untuk mendefinisikan persyaratan prediksi secara jelas, menetapkan target akurasi MAPE kurang dari 10% berdasarkan standar industri dengan formula yang ditunjukkan pada persamaan (28) berikut:

```
MAPE = (100%/n) ∑|(yᵢ − ŷᵢ)/yᵢ|  ... (28)
```

yᵢ adalah nilai aktual konsumsi kalori, ŷᵢ adalah nilai prediksi, dan n adalah jumlah observasi.

Kriteria sukses ditetapkan mencakup measurable objectives untuk performa teknis melalui metrik akurasi dan dampak bisnis dalam bentuk improved planning efficiency. Risk assessment juga dilakukan untuk mengidentifikasi potensi tantangan dalam kualitas data, model complexity, dan integration requirements.

#### b. Data Understanding

Data yang digunakan dalam penelitian ini bersumber dari Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 yang diperoleh dari Badan Pangan Nasional, Pusat Data dan Sistem Informasi Kementerian Pertanian, dan Badan Pusat Statistika. Dataset mencakup 31 tahun data historis dengan 41,316 records transaksi NBM yang mencakup 120 komoditas pangan dari 11 kelompok. Melalui proses agregasi, data transaksi individual ini menghasilkan 372 data points time series bulanan untuk konsumsi kalori nasional, yang memberikan pondasi yang solid untuk pengembangan model prediksi time series. Target variabel dalam penelitian ini adalah konsumsi kalori per kapita per hari agregat nasional yang diukur dalam satuan kkal/kapita/hari.

Dataset NBM memiliki resolusi temporal bulanan dengan pola musiman yang jelas, setiap record transaksi mencakup data produksi, impor, ekspor, dan utilisasi untuk masing-masing dari 120 komoditas pangan. Perhitungan konsumsi kalori menggunakan formula NBM ditunjukkan pada persamaan (29) berikut:

```
Kalori per kapita per hari = [Konsumsi per kapita (kg/hari) × Faktor Konversi Energi (kkal/100g)] / 10  ... (29)
```

Data quality assessment menunjukkan adanya missing values yang diestimasi kurang dari 5%, outliers akibat economic shocks, dan potential measurement errors yang memerlukan treatment khusus.

Exploratory Data Analysis (EDA) dilakukan untuk memahami karakteristik dataset secara komprehensif menggunakan statistical measures. Persamaan (30) berikut menunjukkan formula tersebut:

```
Coefficient of Variation = (σ/μ) × 100%  ... (30)
```

σ adalah standard deviation dan μ adalah mean konsumsi kalori.

Analisis temporal menggunakan Augmented Dickey-Fuller test dengan hipotesis:
- H₀: Time series memiliki unit root (non-stationary)
- H₁: Time series adalah stationary

Correlation analysis menggunakan Pearson correlation coefficient. Persamaan (31) berikut menunjukkan formula tersebut:

```
r = ∑(xᵢ − x̄)(yᵢ − ȳ) / √[∑(xᵢ − x̄)² ∑(yᵢ − ȳ)²]  ... (31)
```

Outlier detection menggunakan IQR method dengan threshold Q₁ − 1.5 × IQR dan Q₃ + 1.5 × IQR, serta Z-score analysis dengan threshold |z| > 3.

#### c. Data Preparation

Tahap data preparation merupakan fase kritikal yang menentukan kualitas input untuk model LSTM. Data cleaning dimulai dengan handling missing values menggunakan forward-fill method untuk maintaining temporal continuity, dengan validasi terhadap pola musiman untuk memastikan imputasi tidak mengubah karakteristik fundamental dari time series.

Outlier treatment menggunakan winsorization dengan persamaan (32) yang ditunjukkan sebagai berikut:

```
xwinsorization = { P₁      jika x < P₁
                   x       jika P₁ ≥ x ≥ P₉₉
                   P₉₉     jika x > P₉₉  ... (32)
```

P₁ dan P₉₉ adalah 1st dan 99th percentiles.

Feature engineering menggunakan cyclical encoding untuk temporal features. Persamaan (33) dan (34) berikut menunjukkan formula tersebut:

```
Monthₛᵢₙ = sin(2π × month / 12)  ... (33)
Monthcₒₛ = cos(2π × month / 12)  ... (34)
```

Rolling statistics dihitung dengan Moving Average. Persamaan (35) berikut menunjukkan formula tersebut:

```
MAₜ = (1/k) ∑xₜ₋ᵢ  ... (35)
```

k adalah window size (3, 6, 12 bulan).

Data preprocessing menggunakan StandardScaler dan RobustScaler dengan persamaan (36) dan (37) yang ditunjukkan sebagai berikut:

```
z = (x − μ) / σ  ... (36)
z = (x − median) / (Q₃ − Q₁)  ... (37)
```

Sequence generation menggunakan sliding window dengan window size yang akan dioptimasi melalui grid search.

Dataset NBM Indonesia yang mencakup 31 tahun (1993-2024) dengan 372 titik data bulanan dibagi secara kronologis untuk menjaga integritas temporal dan mencegah data leakage yang dapat terjadi pada random split. Strategi pembagian data divisualisasikan pada Gambar 4 yang menunjukkan distribusi temporal dataset.

**Gambar 4. Strategi Pembagian Data NBM Indonesia**

Pembagian data mengikuti proporsi 70:15:15 dengan alasan sebagai berikut:

- **Data Pelatihan (70%, 1993-2015)**  
  Periode 23 tahun dengan 276 titik data bulanan digunakan untuk melatih model ensemble LSTM. Periode ini mencakup berbagai kondisi ekonomi dan pangan Indonesia, termasuk krisis moneter 1998 dan periode recovery, memberikan variasi pola yang cukup untuk pembelajaran model.

- **Data Validasi (15%, 2016-2019)**  
  Periode 4 tahun dengan 48 titik data digunakan untuk validasi model dan tuning hyperparameter. Periode ini dipilih karena merepresentasikan kondisi ekonomi yang relatif stabil (pra-pandemi), sehingga cocok untuk optimasi parameter model tanpa bias dari kondisi ekstrem.

- **Data Pengujian (15%, 2020-2024)**  
  Periode 4 tahun terakhir dengan 48 titik data digunakan untuk evaluasi final performa model. Periode ini sengaja dipilih karena mencakup kondisi challenging seperti pandemi COVID-19, yang menguji robustness model terhadap shock ekonomi dan gangguan rantai pasokan pangan.

Untuk optimasi hyperparameter dan pemilihan konfigurasi model terbaik, penelitian ini menerapkan Time Series Cross-Validation dengan teknik expanding window pada data pelatihan (1993-2015). Metode ini divisualisasikan pada Gambar 5 yang menunjukkan mekanisme validasi bertahap.

**Gambar 5. Time Series Cross-Validation Expanding Window**

Expanding window cross-validation dilakukan dengan tahapan sebagai berikut:
- Fold 1: training pada 1993-2005 (12 tahun), validasi pada 2006
- Fold 2: training pada 1993-2007 (14 tahun), validasi pada 2008
- Fold 3: training pada 1993-2009 (16 tahun), validasi pada 2010
- Fold 4: training pada 1993-2011 (18 tahun), validasi pada 2012
- Fold 5: training pada 1993-2013 (20 tahun), validasi pada 2014
- Proses berlanjut hingga fold terakhir memakai data hingga 2015

Setiap fold menambahkan 1-2 tahun data pelatihan untuk mensimulasikan kondisi asli sehingga model terus belajar dari data historis yang bertambah. Metode ini memberikan evaluasi robust terhadap performa model pada berbagai periode waktu dan membantu mendeteksi overfitting serta memilih hyperparameter optimal yang generalize dengan baik. Performa model pada setiap fold akan dievaluasi menggunakan metrik RMSE, MAE, dan MAPE untuk memastikan konsistensi akurasi prediksi across different time periods.

#### d. Modeling

Desain arsitektur model LSTM enhanced ensemble menggabungkan LSTM untuk temporal pattern extraction dengan robust regression algorithms. Arsitektur LSTM menggunakan persamaan gate mechanisms sebagaimana ditunjukkan pada persamaan (38) hingga (43) berikut.

Forget Gate:
```
fₜ = σ(Wf · [hₜ₋₁, xₜ] + bf)  ... (38)
```

Input Gate:
```
iₜ = σ(Wᵢ · [hₜ₋₁, xₜ] + bᵢ)  ... (39)
C̃ₜ = tanh(Wc · [hₜ₋₁, xₜ] + bc)  ... (40)
```

Cell State Update:
```
Cₜ = fₜ * Cₜ₋₁ + iₜ * C̃ₜ  ... (41)
```

Output Gate:
```
oₜ = σ(Wₒ · [hₜ₋₁, xₜ] + b₀)  ... (42)
hₜ = oₜ * tanh(Cₜ)  ... (43)
```

Ensemble integration menggunakan weighted averaging sebagaimana disajikan pada persamaan (44):

```
ŷensemble = ∑wᵢ · ŷᵢ  ... (44)
```

dengan constraint ∑wᵢ = 1 dan wᵢ ≥ 0.

HuberRegressor menggunakan huber loss function yang ditunjukkan pada persamaan (45) berikut:

```
           ⎧ ½(y − f(x))²                  untuk |y − f(x)| ≤ δ
Lδ(y,f(x)) = ⎨
           ⎩ δ|y − f(x)| − ½δ²            untuk |y − f(x)| > δ
... (45)
```

Adam Optimizer digunakan untuk training dengan persamaan (46) yang disajikan sebagai berikut:

```
θₜ₊₁ = θₜ − (α / √(v̂ₜ + ε)) m̂ₜ  ... (46)
```

m̂ₜ dan v̂ₜ adalah bias-corrected first dan second moment estimates.

#### e. Evaluation

Evaluasi performa model menggunakan multiple metrics untuk comprehensive assessment.

- **Root Mean Square Error (RMSE)** untuk measuring prediction accuracy dengan emphasis pada large errors sebagaimana ditunjukkan pada persamaan (47) berikut:

```
RMSE = √[(1/n) ∑(yᵢ − ŷᵢ)²]  ... (47)
```

- **Mean Absolute Error (MAE)** memberikan robust metric untuk average prediction deviation sebagaimana disajikan pada persamaan (48):

```
MAE = (1/n) ∑|yᵢ − ŷᵢ|  ... (48)
```

- **Mean Absolute Percentage Error (MAPE)** menjadi metric utama dengan target < 10% untuk business acceptability berdasarkan praktik standar industri dan benchmarks dari literatur terkait dengan persamaan (49) yang ditunjukkan sebagai berikut:

```
MAPE = (100%/n) ∑|(yᵢ − ŷᵢ)/yᵢ|  ... (49)
```

- **R-Squared** untuk measuring explained variance proportion sebagaimana disajikan pada persamaan (50):

```
R² = 1 − (SSres/SStot) = 1 − [∑(yᵢ − ŷᵢ)² / ∑(yᵢ − ȳ)²]  ... (50)
```

- **Directional Accuracy** untuk percentage of correct trend predictions dengan persamaan (51) yang ditunjukkan sebagai berikut:

```
DA = [1/(n−1)] ∑I[(yᵢ − ŷᵢ₋₁)(yᵢ − ŷᵢ₋₁) > 0]  ... (51)
```

Indicator function dilambangkan dengan I[·].

Validation strategy menggunakan time series cross-validation dengan expanding window, walk-forward validation untuk real-world simulation, dan robustness testing under extreme scenarios. Model interpretability analysis menggunakan SHAP values untuk feature importance dan residual analysis untuk error pattern identification.

#### f. Deployment

Implementasi sistem menggunakan containerized microservices architecture dengan separation of concerns. Frontend service dikembangkan menggunakan Laravel dengan Livewire components untuk reactive interface. Backend machine learning service menggunakan FastAPI dengan RESTful API endpoints untuk model serving.

Arsitektur sistem menggunakan request-response pattern:
- User request → Laravel Frontend
- API call → FastAPI ML Service
- Model inference → Prediction result
- Response → Frontend display

Optimasi performa menggunakan strategi caching, database indexing, dan API rate limiting. Security implementation meliputi authentication, input validation, dan secure communication protocols. Monitoring dan logging menggunakan structured logging untuk system observability dan performance tracking.

### 3.3 Jadwal Penelitian

Penelitian direncanakan berlangsung selama 3 bulan dengan distribusi waktu yang sistematis untuk memastikan setiap tahap RnD dapat dilaksanakan secara optimal. Rincian jadwal pelaksanaan penelitian dapat dilihat pada Tabel 2 yang menunjukkan timeline dan distribusi kegiatan penelitian dari bulan November 2025 hingga Januari 2026.

**Tabel 2. Jadwal Penelitian**

| Tahap Penelitian | November 2025 | Desember 2025 | Januari 2026 |
|------------------|---------------|---------------|--------------|
| Research and Collection Preliminary | ████ | | |
| Research Planning | ████ | | |
| Early Product Development | | ████ | |
| Expert Validation | | ████ | |
| Product Revision | | ████ | |
| Early Test (Implementasi dan Uji Coba Model) | | ████ | ████ |
| Product Revision | | | ████ |
| Field Test | | | ████ |
| Final Product Revision | | | ████ |
| Dissemination | | | ████ |
| Penyusunan Laporan | ████ | ████ | ████ |

---

## DAFTAR PUSTAKA

Adhany, P. C., Wulandari, C., Intan, B., & Santoso, B. (2025). Prediksi Padi Menggunakan Algoritma Long Short Term Memory. *Journal of Informatics Management and Information Technology*, *5*(2), 120–127. https://doi.org/10.47065/jimat.v5i2.496

Alkahfi, C., Kurnia, A., & Saefuddin, A. (2024). Performance Comparison of RNN-Based Models in Forecasting Indonesian Economic and Financial Data Perbandingan Kinerja Model Berbasis RNN pada Peramalan Data Ekonomi dan Keuangan Indonesia. *MALCOM: Indonesian Journal of Machine Learning and Computer Science*, *4*(October), 1235–1243. https://doi.org/10.57152/malcom.v4i4.1415

Arwansyah, A., Suryani, S., SY, H., Usman, U., Ahyuna, A., & Alam, S. (2022). Time Series Forecasting Menggunakan Deep Gated Recurrent Units. *Digital Transformation Technology*, *4*(1), 410–416. https://doi.org/10.47709/digitech.v4i1.4141

ASEAN Secretariat. (2024). *Enhancing and Integrating Regional Food Safety to Face the Changing Landscape of Food System and Health Threats*. ASEAN Socio-Cultural Community Trend Report No. 4.

Asian Development Bank. (2023). *Asian Development Outlook April 2023*. Asian Development Bank.

Badan Pangan Nasional. (2021). Berita Negara. *Peraturan Menteri Kesehatan Republik Indonesia Nomor 4 Tahun 2018*, *1301*, 1–8.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine learning in agriculture: A comprehensive updated review. *Sensors*, *21*(11), 1–55. https://doi.org/10.3390/s21113758

BPS. (2023). *Proyeksi Penduduk Indonesia 2020–2050 Hasil Sensus Penduduk 2020*. Badan Pusat Statistik.

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode Long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. *Jurnal Sistem Dan Teknologi Informasi (JustIN)*, *11*(2), 346. https://doi.org/10.26418/justin.v11i2.57395

Fadila, L. Moh. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia: Pendekatan Menggunakan Big Data dan Data Mining. *Seminar Nasional Official Statistics*, *2023*(1), 247–256. https://doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). *The State of Food Security and Nutrition in the World 2023*. https://doi.org/10.4060/cc3017en

Howard, C., & Augustine, M. (2025). Ensemble Methods for Time Series Forecasting in Nigeria: Predicting Agricultural Yields Using Advanced Machine Learning Approaches. *Asian Journal of Pure and Applied Mathematics*, *7*(1), 318–336. https://doi.org/10.56557/ajpam/2025/v7i1205

Iannone, A. (2023). Unveiling the Impact of the COVID-19 Pandemic (2019-2021) on Inequality, Poverty, and Food Security in Indonesia. *Politika: Jurnal Ilmu Politik*, *14*(2), 189–208. https://doi.org/10.14710/politika.14.2.2023.189-208

Kamil, M. Z. F., Purnamasari, R., & Eliskar, Y. (2024). Perancangan Sistem Deploy Untuk Menghubungkan Machine learning Ke Website. *E-Proceeding of Engineering*, *11*(6), 6394–6396. https://openlibrarypublications.telkomuniversity.ac.id/index.php/engineering/article/view/24940

Kementerian Pertanian. (2023). *Laporan Kinerja Kementerian Pertanian Tahun 2023*. Kementerian Pertanian, 1–230.

Kong, X., Chen, Z., Liu, W., Ning, K., Zhang, L., Muhammad Marier, S., Liu, Y., Chen, Y., & Xia, F. (2025). Deep learning for time series forecasting: a survey. *International Journal of Machine Learning and Cybernetics*, *16*(7–8). https://doi.org/10.1007/s13042-025-02560-w

Magalhães, Sais, A. C., & Rossi, F. (2025). Research on Using Ensemble Models to Assess the Impacts of Climate Change on Agriculture Production: A Review. *AgriEngineering*, *7*(7), 1–18. https://doi.org/10.3390/agriengineering7070219

Narkunam, G. A. (2025). Enhancing Agricultural Forecasting with an Ensemble Learning Approach for Broccoli Yield Prediction. *Journal of Information Systems Engineering and Management*, *10*(41s), 105–116. https://doi.org/10.52783/jisem.v10i41s.7754

OECD. (2021). *Membangun Ketahanan Pangan dan Mengelola Risiko di Asia Tenggara*. (M. G. F. E. B. Suwastoyo, Ed.). Yayasan Cipta Sentosa. https://doi.org/10.1787/9789264272392-en

Okpatrioka. (2023). Research and development (R&D) penelitian yang inovatif dalam pendidikan [Innovative research and development (R&D) in education]. *Dharma Acariya Nusantara: Jurnal Pendidikan, Bahasa Dan Budaya*, *1*(1), 86–100.

Opara, I. K., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. *Plants*, *13*(9), 1–21. https://doi.org/10.3390/plants13091200

Paudel, D., Neupane, R. C., Sigdel, S., Poudel, P., & Khanal, A. R. (2023). COVID-19 Pandemic, Climate Change, and Conflicts on Agriculture: A Trio of Challenges to Global Food Security. *Sustainability (Switzerland)*, *15*(10), 1–22. https://doi.org/10.3390/su15108280

Pawar, A., Manjula Shenoy, K., Prabhu, S., & Guruprasad Rai, D. (2023). Performance analysis of machine learning algorithms: Single Model VS Ensemble Model. *Journal of Physics: Conference Series*, *2571*(1). https://doi.org/10.1088/1742-6596/2571/1/012007

Raharjo, A. B., Wakhid, M. A., & Purwitasari, D. (2022). Load Forecasting for Daily Load Operational Plan Using LSTM (Case Study: South Sulawesi Sub System). *JUTI: Jurnal Ilmiah Teknologi Informasi*, 99–108. https://doi.org/10.12962/j24068535.v20i2.a1138

Rozaki, Z. (2021). Food security challenges and opportunities in Indonesia post COVID-19. *Advances in Food Security and Sustainability* (1st ed., Vol. 6). Elsevier Inc. https://doi.org/10.1016/bs.af2s.2021.07.002

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. *Agriculture (Switzerland)*, *13*(10). https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Gómez, J. M. (2021). A systematic literature review on applying CRISP-DM process model. *Procedia Computer Science*, *181*(2019), 526–534. https://doi.org/10.1016/j.procs.2021.01.199

Sekretariat Jendral - Kementrian Pertanian. (2024). *Statistik Konsumsi Pangan Tahun 2024*. Pusat Data Dan Sistem Informasi Pertanian, Kementrian Pertanian Republik Indonesia, 1–23. https://satudata.pertanian.go.id/details/publikasi/781

Serrano, A. L. M., Rodrigues, G. A. P., Martins, P. H. dos S., Saiki, G. M., Filho, G. P. R., Gonçalves, V. P., & Albuquerque, R. de O. (2024). Statistical Comparison of Time Series Models for Forecasting Brazilian Monthly Energy Demand Using Economic, Industrial, and Climatic Exogenous Variables. *Applied Sciences (Switzerland)*, *14*(13), 1–32. https://doi.org/10.3390/app14135846

Siregar, T. M., Banjarnahor, T., Harahap, A., & Lumbanraja, I. (2024). Peranan Matematika dalam Memprediksi Data Ketahanan Pangan Indonesia 5 Tahun Ke Depan. *8*, 17013–17020.

Sujarwo, Putra, A. N., Setyawan, R. A., Teixeira, H. M., & Khumairoh, U. (2022). Forecasting Rice Status for a Food Crisis Early Warning System Based on Satellite Imagery and Cellular Automata in Malang, Indonesia. *Sustainability (Switzerland)*, *14*(15). https://doi.org/10.3390/su14158972

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes Dengan Feature Selection Untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. *Jurnal Ilmiah Sains Dan Teknologi*, *6*(1), 50–61. https://doi.org/10.47080/saintek.v6i1.1467

Sun, C., Pei, M., Cao, B., Chang, S., & Si, H. (2024). A Study on Agricultural Commodity Price Prediction Model Based on Secondary Decomposition and Long Short-Term Memory Network. *Agriculture (Switzerland)*, *14*(1). https://doi.org/10.3390/agriculture14010060

Sundram, P. (2023). Food security in ASEAN: progress, challenges and future. *Frontiers in Sustainable Food Systems*, *7*(October), 1–14. https://doi.org/10.3389/fsufs.2023.1260619

Tami, M., & Owda, A. Y. (2024). Efficient commodity price forecasting using long short-term memory model. *IAES International Journal of Artificial Intelligence*, *13*(1), 994–1004. https://doi.org/10.11591/ijai.v13.i1.pp994-1004

Waqas, M., Naseem, A., Humphries, U. W., Hlaing, P. T., Dechpichai, P., & Wangwongchai, A. (2025). Applications of machine learning and deep learning in agriculture: A comprehensive review. *Green Technologies and Sustainability*, *3*(3), 100199. https://doi.org/10.1016/j.grets.2025.100199

Yang, H., Jiao, W., Zouyi, L., Diao, H., & Xia, S. (2025). Artificial intelligence in the food industry: innovations and applications. *Discover Artificial Intelligence*, *5*(1). Springer International Publishing. https://doi.org/10.1007/s44163-025-00296-8

Zhang, L., Wang, R., Li, Z., Li, J., Ge, Y., Wa, S., Huang, S., & Lv, C. (2023). Time-Series Neural Network: A High-Accuracy Time-Series Forecasting Method Based on Kernel Filter and Time Attention. *Information (Switzerland)*, *14*(9), 1–18. https://doi.org/10.3390/info14090500