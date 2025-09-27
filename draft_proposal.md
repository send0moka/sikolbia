# PROPOSAL TUGAS AKHIR
## IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**SKRIPSI**

Disusun untuk memenuhi sebagian persyaratan  
untuk memperoleh gelar Sarjana Komputer  
Jurusan Informatika

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

Tugas Akhir dengan judul:  
**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

**Pembimbing I**  
Nofiyati, S.Kom., M.Kom.  
NIP. 198108192024212012

**Pembimbing II**  
Devi Astri Nawangnugraeni, S.Pd., M.Kom.  
NIP. 199312042024062004

**Disusun oleh:**  
Jehian Athaya Tsani Az Zuhry  
H1D022006

Diajukan untuk memenuhi salah satu persyaratan memperoleh gelar  
Sarjana Komputer pada Jurusan Informatika  
Fakultas Teknik  
Universitas Jenderal Soedirman

Diterima dan disetujui  
Pada tanggal ………………………..

---

## KATA PENGANTAR

Puji syukur ke hadirat Tuhan Yang Maha Esa atas rahmat dan karunia-Nya, sehingga penulis dapat menyelesaikan proposal penelitian dengan judul "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian." Proposal ini disusun sebagai salah satu syarat untuk menempuh Tugas Akhir pada Jurusan Informatika, Fakultas Teknik, Universitas Jenderal Soedirman.

Dalam penyusunan proposal ini, penulis mendapatkan berbagai dukungan, arahan, serta masukan dari banyak pihak. Oleh karena itu, dengan penuh hormat dan rasa syukur, penulis menyampaikan terima kasih kepada:

1. Bapak Prof. Dr. Eng. Ir. Agus Maryoto, ST., M.T., IPU., selaku Dekan Fakultas Teknik, Universitas Jenderal Soedirman.
2. Bapak Dr. Ir. Lasmedi Afuan, S.T., M.Cs., IPM., selaku Ketua Jurusan Informatika, Fakultas Teknik, Universitas Jenderal Soedirman.
3. Ibu Nofiyati, S.Kom., M.Kom., selaku Dosen Pembimbing I dan Ibu Devi Astri Nawangnugraeni, S.Pd., M.Kom., selaku Dosen Pembimbing II yang dengan penuh perhatian telah memberikan bimbingan dan arahan selama penyusunan proposal ini.
4. Orang tua dan keluarga tercinta atas doa, semangat, serta dukungan yang tiada harganya.
5. Rekan-rekan seperjuangan di Jurusan Informatika angkatan 2022, serta seluruh pihak yang telah memberikan dukungan dan masukan.

Penulis menyadari proposal ini memiliki keterbatasan, sehingga kritik dan saran sangat diharapkan untuk perbaikan ke depan. Semoga karya ini dapat berkontribusi pada pengembangan ilmu, khususnya dalam bidang machine learning dan analisis data pangan, serta memberikan manfaat bagi perencanaan kebijakan ketahanan pangan nasional.

Purwokerto, 26 September 2025

Jehian Athaya Tsani Az Zuhry

---

## DAFTAR ISI

- [LEMBAR PENGESAHAN PROPOSAL](#lembar-pengesahan-proposal) ................................................... i
- [KATA PENGANTAR](#kata-pengantar) ................................................................................................ ii
- [DAFTAR ISI](#daftar-isi) ............................................................................................................. iii
- [DAFTAR GAMBAR](#daftar-gambar) ................................................................................................ iv
- [DAFTAR TABEL](#daftar-tabel) ...................................................................................................... v
- [BAB I PENDAHULUAN](#bab-i-pendahuluan) ......................................................................................... 1
  - [1.1 Latar Belakang](#11-latar-belakang) ............................................................................................ 1
  - [1.2 Rumusan Masalah](#12-rumusan-masalah) ........................................................................................ 4
  - [1.3 Batasan Penelitian](#13-batasan-penelitian) ....................................................................................... 4
  - [1.4 Tujuan Penelitian](#14-tujuan-penelitian) ......................................................................................... 5
  - [1.5 Manfaat Penelitian](#15-manfaat-penelitian) ....................................................................................... 5
  - [1.6 Metode Penelitian](#16-metode-penelitian) ......................................................................................... 6
  - [1.7 Luaran](#17-luaran) .......................................................................................................... 7
- [BAB II TINJAUAN PUSTAKA](#bab-ii-tinjauan-pustaka) .............................................................................. 8
  - [2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)](#21-ketahanan-pangan-dan-neraca-bahan-makanan-nbm) .............................. 8
  - [2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan](#22-time-series-forecasting-dan-prediksi-konsumsi-pangan) ............................. 9
  - [2.3 Neural Network dan Deep Learning](#23-neural-network-dan-deep-learning) ............................................................. 9
  - [2.4 Long Short-term Memory (LSTM) dan Metode Ensemble](#24-long-short-term-memory-lstm-dan-metode-ensemble) .......................... 10
  - [2.5 Metrik Evaluasi Model Prediksi](#25-metrik-evaluasi-model-prediksi) ................................................................. 12
  - [2.6 Arsitektur Sistem Laravel-FastAPI dan Docker](#26-arsitektur-sistem-laravel-fastapi-dan-docker) ......................................... 13
  - [2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan](#27-penerapan-machine-learning-dalam-prediksi-konsumsi-pangan) ............... 14
  - [2.8 Implementasi LSTM untuk Time Series Forecasting](#28-implementasi-lstm-untuk-time-series-forecasting) ................................. 15
  - [2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia](#29-penelitian-terkait-prediksi-konsumsi-pangan-di-indonesia) ........................ 15
  - [2.10 Gap Analysis](#210-gap-analysis) .............................................................................................. 16
  - [2.11 Kerangka Konseptual](#211-kerangka-konseptual) ................................................................................. 16
- [BAB III METODOLOGI](#bab-iii-metodologi) ......................................................................................... 18
  - [3.1 Pendekatan dan Jenis Penelitian](#31-pendekatan-dan-jenis-penelitian) ................................................................. 18
  - [3.2 Kerangka Kerja CRISP-DM](#32-kerangka-kerja-crisp-dm) ....................................................................... 18
  - [3.3 Tahapan Penelitian](#33-tahapan-penelitian) ..................................................................................... 19
  - [3.4 Jadwal Penelitian](#34-jadwal-penelitian) ........................................................................................ 26
- [DAFTAR PUSTAKA](#daftar-pustaka) ............................................................................................... 27

---

## DAFTAR GAMBAR

**Gambar 1.** Diagram Alur CRISP-DM ............................................................................................. 6

---

## DAFTAR TABEL

**Tabel 1.** Jadwal Penelitian ........................................................................................................ 26

---

# BAB I
# PENDAHULUAN

## 1.1 Latar Belakang

Ketahanan pangan global telah menjadi tantangan utama abad ke-21 yang memerlukan perhatian serius dari komunitas internasional. Menurut FAO (2023), sekitar 735 juta orang di dunia mengalami kelaparan pada tahun 2022, meningkat dari 768 juta pada tahun sebelumnya. Perubahan iklim, konflik geopolitik, dan dampak pandemi COVID-19 telah memperburuk situasi ketahanan pangan global (Paudel et al., 2023). Negara-negara berkembang, khususnya di Asia Tenggara, menghadapi tekanan yang lebih besar dalam mempertahankan sistem pangan yang resilient dan berkelanjutan (OECD, 2021).

Dalam konteks regional, Asia Tenggara merupakan wilayah dengan tingkat kerawanan pangan yang signifikan. Data Global Food Security Index (GFSI) 2024 menunjukkan bahwa rata-rata skor ketahanan pangan negara-negara ASEAN masih berada di bawah standar optimal (Sundram, 2023). Faktor-faktor seperti pertumbuhan populasi yang pesat, urbanisasi, dan degradasi lahan pertanian menjadi tantangan utama dalam menjaga stabilitas pasokan pangan regional (ASEAN Secretariat, 2024).

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik suatu negara, termasuk Indonesia (Fadila & Putri, 2023). Data terbaru menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2 pada Global Food Security Index (GFSI) yang dirilis oleh Economist Intelligence Unit, posisi yang masih tertinggal dibandingkan negara-negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Sekretariat Jendral - Kementrian Pertanian, 2024). Rendahnya peringkat ini mencerminkan berbagai tantangan struktural dalam sistem pangan nasional, termasuk keterbatasan infrastruktur, volatilitas harga, dan kapasitas prediksi yang masih terbatas (Rozaki, 2021).

Dengan jumlah penduduk lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan yang berkelanjutan (BPS, 2023). Tantangan ini semakin diperberat oleh dampak perubahan iklim yang menyebabkan penurunan produktivitas pertanian hingga 10-25% dan meningkatkan volatilitas harga pangan (FAO, 2023). Pandemi COVID-19 juga telah memperparah situasi dengan gangguan rantai pasokan yang menyebabkan 23,2% rumah tangga Indonesia mengalami ketidakamanan pangan pada tahun 2020 (Iannone, 2023). Selain itu, fenomena El Niño dan La Niña secara periodik mempengaruhi pola curah hujan dan produksi pertanian nasional (Badan Meteorologi, Klimatologi, dan Geofisika, 2024).

Eksplorasi awal data Neraca Bahan Makanan (NBM) Indonesia mengungkap volatilitas konsumsi kalori yang mengkhawatirkan, dengan koefisien variasi 18,3% dalam periode 2000-2024 dan fluktuasi ekstrem dari 2.156 kkal/kapita/hari (krisis 1998) hingga 2.978 kkal/kapita/hari (2019) (Sekretariat Jendral - Kementrian Pertanian, 2024). Analisis dekomposisi time series menunjukkan adanya komponen tren (R² = 0.76), komponen musiman dengan periode 12 bulan, dan komponen tidak beraturan yang mencapai 23% dari total variasi, mengindikasikan kompleksitas pola yang membutuhkan teknik pemodelan yang advanced (Susanti & Prabowo, 2023).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak dalam mendukung pencapaian target Sustainable Development Goals (SDGs) nomor 2 tentang Zero Hunger. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola waktu konsumsi pangan (Sarku et al., 2023). Studi komparatif menunjukkan bahwa Indonesia memiliki akurasi prediksi terendah untuk konsumsi pangan forecasting dibandingkan negara berkembang lainnya yang telah mengimplementasikan pendekatan machine learning (Asian Development Bank, 2023).

Ketidakakuratan prediksi konsumsi pangan berimplikasi pada kerugian ekonomi yang signifikan. Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat salah alokasi sumber daya dalam program ketahanan pangan periode 2020-2022, di mana 34% target tidak tercapai karena perkiraan yang terlalu rendah pada konsumsi kalori regional (Kementerian Pertanian, 2023). Kesenjangan teknologi ini berdampak pada keterlambatan respon terhadap krisis ketahanan pangan, seperti yang terjadi pada kekurangan beras 2023 yang baru terdeteksi 4 bulan setelah tren penurunan konsumsi dimulai (Sujarwo et al., 2022).

Dalam era revolusi industri 4.0, penerapan teknologi artificial intelligence (AI) dan machine learning telah mentransformasi berbagai sektor, termasuk prediksi dan perencanaan pangan (Yang et al., 2025). Deep learning, khususnya algoritma neural network, telah menunjukkan kemampuan superior dalam menangani data time series yang kompleks dengan pola non-linear (Zhang et al., 2023). Long Short Term Memory (LSTM), sebagai varian dari Recurrent Neural Network (RNN), telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long term dependencies dan pola musiman yang kompleks (Alkahfi et al., 2024).

Metode ensemble yang mengintegrasikan LSTM dengan algoritma machine learning lainnya telah menunjukkan peningkatan performa yang signifikan dalam berbagai domain prediksi (Howard & Augustine, 2025). Penelitian terdahulu menunjukkan bahwa pendekatan ensemble dapat mengurangi overfitting dan meningkatkan generalisasi model (Magalhães et al., 2025). Khususnya dalam agricultural forecasting, metode ensemble yang menggabungkan LSTM dengan robust regression algorithms telah mencapai akurasi yang lebih tinggi dibandingkan pendekatan model tunggal (Narkunam, 2025).

Tinjauan literatur terhadap penelitian terdahulu mengungkap beberapa gap penelitian yang signifikan. Pertama, mayoritas penelitian LSTM untuk prediksi pangan berfokus pada komoditas tunggal seperti beras atau jagung, belum ada yang menggunakan data agregat konsumsi kalori nasional dari NBM (Cahyani et al., 2023; Tami & Owda, 2024). Kedua, penelitian sebelumnya umumnya menggunakan model LSTM tunggal tanpa pendekatan ensemble, padahal literatur menunjukkan bahwa metode ensemble dapat meningkatkan akurasi prediksi hingga 25-30% (Pawar et al., 2023; Raharjo et al., 2022). Ketiga, belum ada penelitian yang mengintegrasikan model LSTM ensemble dengan sistem informasi real-time berbasis arsitektur microservices untuk prediksi konsumsi pangan Indonesia (Thompson et al., 2024).

Penelitian Adhany et al. (2025) menggunakan LSTM untuk prediksi produksi gandum di China dengan MAPE 12,4%, namun tidak menggunakan ensemble method dan data terbatas pada satu komoditas. Sementara itu, Sun et al. (2024) menerapkan ensemble LSTM untuk prediksi harga pangan di India dengan MAPE 9,7%, tetapi fokus pada harga bukan konsumsi kalori. Serrano et al. (2024) mengembangkan sistem prediksi konsumsi pangan Brasil menggunakan tradisional time series methods dengan MAPE 14,8%, menunjukkan potensi perbaikan dengan deep learning.

Penelitian di negara berkembang lainnya menunjukkan bahwa pendekatan LSTM enhanced ensemble dapat meningkatkan akurasi prediksi konsumsi pangan dengan MAPE < 10% (Raharjo et al., 2022). Namun, penelitian tersebut menggunakan data sintetis dan belum divalidasi dengan data asli yang kompleks seperti NBM Indonesia. Data Neraca Bahan Makanan (NBM) Indonesia yang telah terakumulasi selama lebih dari 30 tahun (1993-2024) menyediakan fondasi yang kuat untuk pengembangan model prediktif berbasis machine learning ensemble yang dapat mengisi gap penelitian yang ada (Waqas et al., 2025).

## 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

a. Bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal, teknik Robust preprocessing (StandardScaler dan RobustScaler), sequence generation yang tepat, dan evaluasi metrik RMSE, MAE, MAPE untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%?

b. Bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?

## 1.3 Batasan Penelitian

Adapun batasan dari penelitian ini adalah sebagai berikut:

a. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian.

b. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 yang bersumber dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian.

c. Prediksi yang dibuat terbatas pada konsumsi kalori harian per kapita, tidak mencakup prediksi protein dan lemak.

d. Implementasi sistem informasi menggunakan kerangka kerja Laravel untuk frontend web antarmuka dan FastAPI untuk backend machine learning service dengan database MySQL, dilengkapi dengan Docker containerization untuk deployment yang scalable dan Redis untuk penyimpanan cache.

e. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi dengan target MAPE < 10% berdasarkan standar industri dan literatur terkait.

f. Penelitian ini tidak mencakup pengembangan aplikasi mobile, hanya fokus pada sistem berbasis web.

## 1.4 Tujuan Penelitian

Adapun tujuan dari penelitian ini adalah sebagai berikut:

a. Mengimplementasikan model LSTM enhanced ensemble untuk prediksi konsumsi kalori harian dengan memanfaatkan data historis Neraca Bahan Makanan Indonesia dan teknik Robust preprocessing.

b. Melakukan preprocessing dan feature engineering pada data NBM menggunakan StandardScaler dan RobustScaler untuk optimalisasi performa model ensemble dalam prediksi konsumsi kalori.

c. Mengevaluasi performa model LSTM enhanced ensemble dalam memprediksi konsumsi kalori harian menggunakan metrik evaluasi RMSE, MAE, dan MAPE dengan target akurasi MAPE < 10% berdasarkan perbandingan dari literatur terkait.

d. Mengintegrasikan model ensemble yang telah dilatih ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan prediksi konsumsi kalori secara real-time.

## 1.5 Manfaat Penelitian

Manfaat dari penelitian ini adalah sebagai berikut:

### a. Bagi Peneliti
Penelitian ini memberikan pengalaman praktis dalam penerapan algoritma LSTM untuk prediksi time series konsumsi pangan, sekaligus mengembangkan keterampilan dalam implementasi deep learning dan pengembangan sistem informasi terintegrasi dengan arsitektur microservices. Selain itu, hasil penelitian ini diharapkan dapat menjadi referensi untuk penelitian atau proyek serupa di masa depan.

### b. Bagi Pembaca
Penelitian ini memberikan wawasan mengenai penerapan algoritma LSTM dalam prediksi konsumsi kalori berbasis data NBM dan menyajikan informasi yang bermanfaat bagi akademisi dan praktisi yang ingin mengembangkan sistem prediksi ketahanan pangan.

### c. Bagi Masyarakat
Penelitian ini membantu pemerintah dan pengambil kebijakan dalam perencanaan ketahanan pangan nasional melalui sistem peringatan dini berbasis machine learning, serta memberikan transparansi informasi prediksi ketersediaan pangan untuk meningkatkan kesadaran masyarakat tentang pentingnya ketahanan pangan.

## 1.6 Metode Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development. Penelitian difokuskan pada implementasi algoritma LSTM untuk prediksi konsumsi kalori harian berdasarkan data NBM Indonesia, dengan menggunakan metodologi CRISP-DM (Cross-Industry Standard Process for Data Mining) yang telah terbukti efektif dalam proyek machine learning (Schröer et al., 2021).

Diagram alur CRISP-DM dapat dilihat pada Gambar 1 yang menunjukkan tahapan sistematis dari business understanding hingga deployment. Metodologi ini dipilih karena memberikan kerangka kerja yang terstruktur untuk proyek data mining dan machine learning yang kompleks.

![Gambar 1. Diagram Alur CRISP-DM](gambar-1-crisp-dm.png)

Data yang digunakan adalah data sekunder NBM Indonesia dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian periode 1993-2024, dengan target prediksi konsumsi kalori per kapita harian. Model LSTM akan diimplementasikan menggunakan Python dengan TensorFlow/Keras dan diintegrasikan melalui FastAPI, sedangkan sistem informasi dikembangkan menggunakan kerangka kerja Laravel untuk memberikan antarmuka prediksi real-time.

## 1.7 Luaran

Penelitian ini diharapkan dapat menghasilkan model LSTM enhanced ensemble yang akurat dan efisien untuk prediksi konsumsi kalori harian dengan target MAPE < 10%, bertujuan untuk mendukung perencanaan ketahanan pangan nasional. Target ini ditetapkan berdasarkan standar akurasi yang diterima dalam agricultural forecasting dan perbandingan dari penelitian sejenis (Cahyani et al., 2023; Raharjo et al., 2022). Model ini akan dilengkapi dengan sistem informasi berbasis web yang mengintegrasikan Laravel, FastAPI, dan Docker, memungkinkan stakeholder ketahanan pangan untuk melakukan prediksi konsumsi kalori secara konsisten dan akurat.

Sistem yang dikembangkan akan memberikan pengambil kebijakan akses kepada informasi prediksi yang jelas dan real-time melalui dashboard visualisasi interaktif dengan session management dan caching optimization, sehingga membantu mereka dalam membuat keputusan yang lebih baik dalam perencanaan ketahanan pangan. Luaran penelitian juga mencakup dokumentasi teknis implementasi metode.

---

# BAB II
# TINJAUAN PUSTAKA

## 2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)

Ketahanan pangan didefinisikan sebagai kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan, yang tercermin dari tersedianya pangan yang cukup, baik jumlah maupun mutunya, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat untuk dapat hidup sehat, aktif, dan produktif secara berkelanjutan (Badan Pangan Nasional, 2021). Konsep ini mencakup empat pilar utama: ketersediaan (availability), keterjangkauan (accessibility), pemanfaatan (utilization), dan stabilitas (stability) yang saling berinteraksi dalam sistem pangan nasional (FAO, 2023).

Neraca Bahan Makanan (NBM) menggunakan formula dasar untuk menghitung konsumsi per kapita sebagai berikut:

```
Konsumsi per kapita = Ketersediaan Bersih / (Jumlah Penduduk × 365 hari)
```

Ketersediaan Bersih dihitung dengan persamaan:

```
Ketersediaan Bersih = Produksi + Impor - Ekspor ± ΔStok - Non-Food Uses
```

di mana Δstok adalah perubahan stok (positif jika berkurang, negatif jika bertambah) dan non-food uses merupakan penggunaan untuk pakan ternak, industri, dan lain sebagainya.

Konversi ke kalori menggunakan faktor konversi energi:

```
Kalori per kapita per hari = (Konsumsi per kapita (kg/hari) × Faktor Konversi Energi (kkal/100g)) / 10
```

Neraca Bahan Makanan (NBM) merupakan instrumen penting dalam monitoring ketahanan pangan yang menyajikan gambaran menyeluruh tentang situasi pangan suatu negara dalam kurun waktu tertentu (Sekretariat Jendral - Kementrian Pertanian, 2024). NBM mengintegrasikan data produksi, impor, ekspor, perubahan stok, dan penggunaan untuk pakan ternak serta industri, sehingga menghasilkan angka konsumsi per kapita yang akurat. Data NBM Indonesia telah dikompilasi sejak tahun 1993 dan mencakup lebih dari 60 komoditas pangan utama dengan parameter konsumsi kalori, protein, dan lemak per kapita per hari.

## 2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan

Time Series Forecasting adalah teknik analisis data historis yang diamati dalam urutan waktu tertentu untuk memprediksi nilai-nilai masa depan (Arwansyah et al., 2022). Model ARIMA (Autoregressive Integrated Moving Average) dapat dinyatakan sebagai ARIMA(p,d,q) dengan persamaan:

```
(1 - φ₁L - φ₂L² - ... - φₚLᵖ)(1 - L)ᵈXₜ = (1 + θ₁L + θ₂L² + ... + θₑLᵠ)εₜ
```

di mana L adalah lag operator, φᵢ adalah autoregressive parameters, θⱼ adalah moving average parameters, d adalah degree of differencing, dan εₜ adalah white noise error term.

Exponential smoothing menggunakan weighted average dari observasi masa lalu dengan formula:

```
Sₜ = αXₜ + (1 - α)Sₜ₋₁
```

di mana Sₜ adalah smoothed value pada waktu t, α adalah smoothing parameter (0 < α < 1), dan Xₜ adalah actual value pada waktu t.

Dalam konteks ketahanan pangan, forecasting konsumsi memiliki karakteristik unik berupa pola musiman yang dipengaruhi oleh faktor musim panen, hari raya keagamaan, dan kondisi ekonomi makro. Konsumsi pangan menunjukkan pola temporal yang kompleks dengan komponen tren jangka panjang, siklus musiman, dan fluktuasi tidak teratur yang memerlukan pendekatan model yang sophisticated (Siregar et al., 2024).

Metode konvensional seperti ARIMA dan exponential smoothing telah lama digunakan untuk prediksi konsumsi pangan, namun memiliki keterbatasan dalam menangkap non-linear relationships dan long-term dependencies yang karakteristik pada data konsumsi pangan (Cahyani et al., 2023). Keterbatasan ini mendorong pengembangan pendekatan machine learning yang lebih advanced untuk meningkatkan akurasi prediksi.

## 2.3 Neural Network dan Deep Learning

Neural Network adalah computational model yang terinspirasi dari struktur dan fungsi jaringan syaraf biologis, terdiri dari nodes (neurons) yang saling terhubung dan mampu belajar pola kompleks dari data training (Benos et al., 2021). Forward propagation pada fully connected layer dinyatakan dengan persamaan:

```
z[l] = W[l]a[l-1] + b[l]
a[l] = g[l](z[l])
```

di mana z[l] adalah linear output layer ke-l, W[l] adalah weight matrix layer ke-l, a[l-1] adalah activation dari layer sebelumnya, b[l] adalah bias vector, dan g[l] adalah activation function.

Activation functions yang umum digunakan meliputi:
- Sigmoid: σ(z) = 1/(1+e⁻ᶻ)
- Tanh: tanh(z) = (e²ᶻ - e⁻²ᶻ)/(e²ᶻ + e⁻²ᶻ)
- ReLU: ReLU(z) = max(0, z)

Backpropagation untuk update weights menggunakan persamaan:

```
∂L/∂W[l] = ∂L/∂z[l] · ∂z[l]/∂W[l] = δ[l] · (a[l-1])T
W[l] := W[l] - α ∂L/∂W[l]
```

di mana L adalah loss function, δ[l] adalah error signal layer ke-l, dan α adalah learning rate.

Deep Learning merupakan subset dari machine learning yang menggunakan neural networks dengan multiple hidden layers untuk ekstraksi fitur hierarkis dan pembelajaran representasi yang sophisticated. Arsitektur deep learning telah terbukti superior dalam menangani high-dimensional data dan complex pattern recognition tasks, termasuk aplikasi dalam agricultural domain (Opara et al., 2024). Keunggulan utama deep learning terletak pada kemampuan automatic feature extraction, yang mengeliminasi kebutuhan manual feature engineering yang memakan waktu dan subjektif dalam machine learning pendekatan tradisional.

## 2.4 Long Short-term Memory (LSTM) dan Metode Ensemble

Long Short-term Memory (LSTM) adalah specialized recurrent Neural Network architecture yang dirancang untuk mengatasi vanishing gradient problem dalam traditional RNNs, sehingga mampu menangkap Long-term Dependencies dalam sequential data (Kong et al., 2025). LSTM memiliki cell state mechanism yang memungkinkan selective retention dan forgetting informasi melalui three gates: forget gate, input gate, dan output gate.

**Forget Gate** menentukan informasi mana yang akan dihapus dari cell state dengan persamaan:

```
fₜ = σ(Wf · [hₜ₋₁, xₜ] + bf)
```

di mana fₜ adalah forget gate output pada waktu t, σ adalah sigmoid function, Wf adalah weight matrix untuk forget gate, hₜ₋₁ adalah hidden state sebelumnya, xₜ adalah input pada waktu t, dan bf adalah bias vector untuk forget gate.

**Input Gate** memutuskan nilai-nilai baru mana yang akan disimpan dalam cell state:

```
iₜ = σ(Wi · [hₜ₋₁, xₜ] + bi)
C̃ₜ = tanh(WC · [hₜ₋₁, xₜ] + bC)
```

di mana iₜ adalah input gate output, C̃ₜ adalah kandidat nilai cell state baru, Wi, WC adalah weight matrices, dan bi, bC adalah bias vectors.

**Cell state update** menggabungkan informasi lama dan baru:

```
Cₜ = fₜ * Cₜ₋₁ + iₜ * C̃ₜ
```

**Output Gate** menentukan bagian cell state yang akan menjadi output:

```
oₜ = σ(Wo · [hₜ₋₁, xₜ] + bo)
hₜ = oₜ * tanh(Cₜ)
```

di mana oₜ adalah output gate dan hₜ adalah hidden state output pada waktu t.

Dalam konteks ensemble learning untuk time series forecasting, LSTM dapat dikombinasikan dengan robust regression algorithms seperti HuberRegressor. HuberRegressor menggunakan huber loss function yang menggabungkan MSE untuk error kecil dan MAE untuk error besar:

```
Lδ(y, f(x)) = {
  1/2(y - f(x))²           untuk |y - f(x)| ≤ δ
  δ|y - f(x)| - 1/2δ²      untuk |y - f(x)| > δ
}
```

di mana y adalah nilai aktual, f(x) adalah nilai prediksi, dan δ adalah threshold parameter (biasanya 1.35).

LSTM enhanced ensemble menggabungkan temporal pattern recognition capabilities dari LSTM dengan Robust statistical properties dari Regression Algorithms. Ensemble prediction dihitung menggunakan weighted averaging:

```
ŷensemble = Σ(wi · ŷi) dari i=1 hingga n
```

dengan constraint Σwi = 1 dari i=1 hingga n dan wi ≥ 0, di mana ŷensemble adalah prediksi ensemble, wi adalah weight untuk model ke-i, ŷi adalah prediksi dari model ke-i, dan n adalah jumlah model dalam ensemble.

Adam Optimizer yang umum digunakan untuk training LSTM menggunakan persamaan:

```
mₜ = β₁mₜ₋₁ + (1 - β₁)gₜ
vₜ = β₂vₜ₋₁ + (1 - β₂)gₜ²
m̂ₜ = mₜ/(1 - β₁ᵗ)
v̂ₜ = vₜ/(1 - β₂ᵗ)
θₜ₊₁ = θₜ - α/(√v̂ₜ + ε) · m̂ₜ
```

di mana gₜ adalah gradient pada step t, mₜ, vₜ adalah first dan second moment estimates, β₁, β₂ adalah decay rates (biasanya 0.9 dan 0.999), α adalah learning rate, dan ε adalah small constant untuk numerical stability.

Hyperparameter optimization dalam ensemble setting mencakup not only LSTM-specific parameters (learning rate, batch size, epochs, window size) tetapi juga ensemble configuration seperti model weights, voting mechanisms, dan regularization parameters untuk preventing overfitting across multiple models.

## 2.5 Metrik Evaluasi Model Prediksi

Evaluasi performa model prediksi menggunakan multiple metrics untuk memastikan comprehensive assessment. **Root Mean Square Error (RMSE)** mengukur Standard deviation dari residuals dan memberikan penalty yang lebih besar untuk large errors:

```
RMSE = √(1/n Σ(yi - ŷi)²) dari i=1 hingga n
```

di mana yi adalah nilai aktual, ŷi adalah nilai prediksi, dan n adalah jumlah observasi.

**Mean Absolute Error (MAE)** memberikan average magnitude of errors tanpa mempertimbangkan direction:

```
MAE = 1/n Σ|yi - ŷi| dari i=1 hingga n
```

**Mean Absolute Percentage Error (MAPE)** mengukur akurasi dalam bentuk persentase, memudahkan interpretasi:

```
MAPE = 100%/n Σ|yi - ŷi|/yi dari i=1 hingga n
```

RMSE lebih sensitif terhadap outliers dibandingkan MAE karena menggunakan squared errors, sementara MAE lebih robust terhadap outliers dan memberikan equal weight untuk semua errors (Raharjo et al., 2022). MAPE memberikan interpretasi yang intuitif dalam bentuk persentase error, namun dapat menghasilkan nilai infinite atau sangat besar ketika nilai aktual mendekati nol.

## 2.6 Arsitektur Sistem Laravel-FastAPI dan Docker

Implementasi sistem prediksi modern memerlukan arsitektur yang memisahkan concerns antara user interface, business logic, dan machine learning processing dengan deployment strategy yang scalable (Kamil et al., 2024). Laravel menyediakan robust foundation untuk web application development dengan features seperti Eloquent ORM, Livewire reactive components, dan Blade templating engine yang memudahkan development of interactive dashboard dan real-time user interactions.

FastAPI merupakan modern kerangka kerja python web yang dioptimalkan untuk membangun APIs dengan dokumentasi OpenAPI otomatis dan dukungan bawaan untuk pemrograman asinkron. FastAPI sangat cocok untuk machine learning karena integrasi native dengan ekosistem scientific Python (NumPy, Pandas, scikit-learn) dan performa tinggi yang comparable dengan NodeJS dan Go.

Docker memungkinkan lingkungan deployment yang konsisten di seluruh tahap pengembangan, pengujian, dan produksi. Arsitektur berbasis kontainer memastikan reproduibilitas dan portabilitas dari machine learning, mengeliminasi isu "it works on my machine" yang umum terjadi dalam machine learning deployment (Benos et al., 2021). Pengaturan multi-kontainer dengan Docker Compose memungkinkan pemisahan tanggung jawab antara aplikasi web, layanan machine learning, basis data, dan caching layers.

Session management dan caching optimization menggunakan Redis untuk caching data berkecepatan tinggi dan penyimpanan sesi pengguna, mengurangi beban database dan meningkatkan waktu respons untuk permintaan prediksi yang sering. Arsitektur microservices dengan Laravel sebagai layanan frontend dan FastAPI sebagai layanan backend machine learning memungkinkan skalabilitas independen, fleksibilitas teknologi, dan pemeliharaan yang lebih mudah melalui pengikatan longgar dan kohesi tinggi dalam desain sistem.

## 2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan

Ulasan sistematis terhadap penerapan machine learning dalam ketahanan pangan menunjukkan tren yang semakin meningkat dalam penggunaan algoritma canggih untuk forecasting pertanian (Siregar et al., 2024). Penelitian di India mengimplementasikan LSTM untuk prediksi crop production dengan data sintetis, mencapai akurasi yang secara signifikan lebih baik dibandingkan dengan metode tradisional (Raharjo et al., 2022). Namun, aplikasi pada forecasting konsumsi pangan nasional masih terbatas, terutama di negara berkembang.

Sarku et al. (2023) melakukan tinjauan komprehensif terhadap aplikasi kecerdasan buatan (AI) dalam ketahanan pangan, mengidentifikasi bahwa sebagian besar studi berfokus pada forecasting produksi daripada forecasting konsumsi. Kesenjangan ini menunjukkan peluang untuk mengembangkan model yang berfokus pada konsumsi yang dapat mendukung pengambilan keputusan kebijakan dalam perencanaan ketahanan pangan. Penelitian tersebut juga menekankan pentingnya data historis berkualitas tinggi untuk melatih model yang efektif.

## 2.8 Implementasi LSTM untuk Time Series Forecasting

Arwansyah et al. (2022) melakukan survei mendalam terhadap pendekatan deep learning untuk forecasting deret waktu, mengonfirmasi keunggulan LSTM dalam menangani data berurutan dengan pola temporal yang kompleks. Penelitian tersebut menunjukkan bahwa LSTM sangat efektif untuk forecasting multi-step ahead dengan cakupan forecasting yang panjang, yang sangat relevan untuk perencanaan ketahanan pangan.

Kong et al. (2025) dalam survei menyeluruh terbaru teridentifikasi bahwa variasi LSTM seperti Bidirectional LSTM dan attention-based LSTM menunjukkan hasil yang menjanjikan untuk tugas prediksi yang kompleks. Namun, penelitian tersebut juga menekankan pentingnya pengaturan hyperparameter yang tepat dan pengolahan awal data untuk mencapai performa optimal. Cahyani et al. (2023) membandingkan performa LSTM dan BiLSTM dalam tugas prediksi, yang menunjukkan bahwa pemilihan model harus disesuaikan dengan karakteristik dari dataset tertentu.

## 2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia

Penelitian dalam negeri mengenai prediksi konsumsi pangan masih sebagian besar menggunakan metode statistik konvensional. Cahyani et al. (2023) menerapkan LSTM untuk prediksi harga bahan pokok nasional dan berhasil mencapai MAPE 8,2% untuk komoditas beras, yang menunjukkan potensi penerapan LSTM dalam sistem pangan Indonesia. Akan tetapi, penelitian tersebut hanya terfokus pada forecasting harga dan belum mencakup forecasting konsumsi.

Fadila & Putri (2023) melakukan analisis perkembangan ketahanan pangan di Indonesia menggunakan big data, namun fokus pada analisis deskriptif daripada pemodelan prediktif. Penelitian tersebut mengidentifikasi ketersediaan dan kualitas data menjadi tantangan besar dalam pengembangan sistem prediksi lanjutan untuk ketahanan pangan Indonesia.

## 2.10 Gap Analysis

Berdasarkan tinjauan literatur sistematis, teridentifikasi beberapa gap kritis dalam penelitian yang ada:

### a. Keterbatasan Ruang Lingkup
Mayoritas penelitian fokus pada prediksi tingkat regional atau komoditas tunggal, belum ada yang menangani peramalan konsumsi kalori tingkat nasional menggunakan dataset NBM yang komprehensif (Siregar et al., 2024).

### b. Kesenjangan Metodologis
Terbatasnya penerapan arsitektur deep learning mutakhir seperti LSTM untuk forecasting konsumsi pangan dalam konteks negara berkembang (Asian Development Bank, 2023).

### c. Pemanfaatan Data
Kurangnya pemanfaatan dataset historis jangka panjang yang tersedia, dengan mayoritas studi menggunakan data jangka pendek (< 10 tahun) yang tidak memadai untuk menangkap pola jangka panjang (Arwansyah et al., 2022).

### d. Kesenjangan Implementasi
Kurangnya sistem terintegrasi yang menggabungkan model prediktif dengan antarmuka yang mudah digunakan untuk aplikasi kebijakan (Opara et al., 2024).

## 2.11 Kerangka Konseptual

Kerangka konseptual penelitian ini menggambarkan alur sistematis dari preprocessing data hingga penerapan sistem prediktif terintegrasi. Kerangka penelitian mengadopsi metodologi CRISP-DM dengan fokus spesifik pada implementasi LSTM dan arsitektur microservices (Schröer et al., 2021).

Input utama penelitian berupa data NBM historis (1993-2024) yang mencakup time series konsumsi kalori per kapita, akan diproses melalui tahap persiapan data komprehensif meliputi normalisasi, feature scaling, dan sequence generation. Model ensemble LSTM akan dikembangkan dan dilatih dengan konfigurasi hyperparameter optimal untuk mencapai target akurasi MAPE < 10%.

Model yang telah tervalidasi akan diintegrasikan dalam arsitektur microservices dengan layanan frontend Laravel untuk antarmuka pengguna dan visualisasi dashboard, layanan backend FastAPI untuk serving model machine learning, dan database MySQL untuk persistensi data. Arsitektur ini memungkinkan penerapan yang dapat diskalakan dan kemampuan prediksi real-time yang mendukung pengambilan keputusan berbasis bukti dalam perencanaan ketahanan pangan (Kamil et al., 2024).

Alur kerja sistem dimulai dari permintaan pengguna melalui antarmuka web Laravel, yang kemudian mengirim panggilan API ke layanan FastAPI untuk inferensi model. Hasil dari prediksi akan di-cache dalam database MySQL dan ditampilkan melalui dashboard visualisasi interaktif. Kerangka konseptual ini memberikan peta jalan yang jelas untuk mencapai tujuan penelitian sambil memastikan ketepatan teoritis dan penerapan praktis dari sistem yang dikembangkan.

---

# BAB III
# METODOLOGI

## 3.1 Pendekatan dan Jenis Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development (RnD). Pendekatan kuantitatif dipilih karena penelitian melibatkan analisis data numerik time series konsumsi kalori dan evaluasi performa model menggunakan metrik statistik (Sukarna & Ansori, 2022). Metode RnD sesuai dengan tujuan penelitian yang mengembangkan produk berupa sistem prediksi terintegrasi dengan validasi empiris terhadap efektivitasnya.

Jenis penelitian ini termasuk applied research yang fokus pada penerapan praktis algoritma LSTM untuk menyelesaikan masalah nyata dalam ketahanan pangan Indonesia. Penelitian mengadopsi experimental design dengan controlled variables untuk menguji performa berbagai konfigurasi model LSTM dan membandingkannya dengan metode baseline yang saat ini digunakan (Torres et al., 2021). Pendekatan eksperimental memungkinkan isolasi variabel-variabel yang mempengaruhi akurasi prediksi sehingga dapat diidentifikasi konfigurasi optimal untuk implementasi.

## 3.2 Kerangka Kerja CRISP-DM

Penelitian ini mengadopsi metodologi CRISP-DM (Cross-Industry Standard Process for Data Mining) sebagai kerangka kerja utama pengembangan. CRISP-DM dipilih karena telah terbukti efektif dalam proyek machine learning dan memberikan structured yang memastikan systematic progression dari business understanding hingga successful deployment (Schröer et al., 2021).

Metodologi ini terdiri dari enam fase yang saling terkait dan bersifat iteratif, memungkinkan perbaikan berdasarkan hasil evaluasi pada setiap tahap. Sifat iteratif dari CRISP-DM sangat sesuai dengan karakteristik pengembangan model machine learning yang memerlukan eksperimen berulang untuk mencapai performa optimal (Singgalen, 2023). Kerangka kerja ini juga memastikan bahwa aspek bisnis dan teknis mendapat perhatian seimbang sepanjang proses pengembangan.

## 3.3 Tahapan Penelitian

### a. Business Understanding

Fase pertama dari metodologi CRISP-DM fokus pada pemahaman mendalam terhadap konteks ketahanan pangan Indonesia dan persyaratan khusus untuk sistem prediksi yang akan dikembangkan. Tahap ini dimulai dengan analisis stakeholder untuk mengidentifikasi pihak-pihak kunci seperti Badan Pangan Nasional, Kementerian Pertanian, dan para pengambil kebijakan, serta memahami proses pengambilan keputusan mereka dalam perencanaan ketahanan pangan.

Problem definition dilakukan secara sistematis untuk mendefinisikan persyaratan prediksi secara jelas, menetapkan target akurasi MAPE kurang dari 10% berdasarkan standar industri dengan formula:

```
MAPE = 100%/n Σ|yi - ŷi|/yi dari i=1 hingga n
```

di mana yi adalah nilai aktual konsumsi kalori, ŷi adalah nilai prediksi, dan n adalah jumlah observasi.

Kriteria sukses ditetapkan mencakup measurable objectives untuk performa teknis melalui metrik akurasi dan dampak bisnis dalam bentuk improved planning efficiency. Risk assessment juga dilakukan untuk mengidentifikasi potensi tantangan dalam kualitas data, model complexity, dan integration requirements.

Instrumen pengumpulan data pada tahap ini meliputi wawancara terstruktur dengan stakeholder, analisis dokumen kebijakan ketahanan pangan, dan review literatur terkait sistem prediksi konsumsi pangan. Teknik analisis menggunakan kerangka kerja analisis dan requirement engineering untuk memastikan pemahaman komprehensif.

Output dari fase ini berupa dokumen business requirements yang mendetailkan kebutuhan fungsional dan non-fungsional sistem prediksi. Dokumen ini menjadi acuan utama untuk seluruh tahap pengembangan selanjutnya dan memastikan keselarasan antara solusi teknikal dengan kebutuhan bisnis.

### b. Data Understanding

Data yang digunakan dalam penelitian ini bersumber dari Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 yang diperoleh dari Badan Pangan Nasional. Dataset mencakup 31 tahun data historis dengan 372 data points bulanan, memberikan foundation yang solid untuk pengembangan Model prediksi Time Series. Target variable dalam penelitian ini adalah konsumsi kalori per kapita per hari yang diukur dalam satuan kkal/kapita/hari.

Dataset NBM memiliki temporal resolution bulanan dengan pola musiman yang jelas, mencakup sekitar 60 komoditas pangan dengan data produksi, impor, ekspor, dan utilisasi. Perhitungan konsumsi kalori menggunakan formula NBM:

```
Kalori per kapita per hari = (Konsumsi per kapita (kg/hari) × Faktor Konversi Energi (kkal/100g)) / 10
```

Data quality assessment menunjukkan adanya missing values yang diestimasi kurang dari 5%, outliers akibat economic shocks, dan potential measurement errors yang memerlukan treatment khusus.

Instrumen pengumpulan data meliputi akses ke database resmi Badan Pangan Nasional, API dari Pusat Data dan Sistem Informasi Pertanian, serta data sekunder dari BPS dan Bank Indonesia untuk economic indicators. Teknik pengumpulan menggunakan automated data extraction dengan validation protocol dan manual verification untuk ensuring data integrity.

Exploratory Data Analysis (EDA) dilakukan untuk memahami karakteristik dataset secara komprehensif menggunakan statistical measures:

```
Coefficient of Variation = σ/μ × 100%
```

di mana σ adalah standard deviation dan μ adalah mean konsumsi kalori.

Temporal analysis menggunakan Augmented Dickey-Fuller test dengan hipotesis:
- H₀: Time series memiliki unit root (non-stationary)
- H₁: Time series adalah stationary

Correlation analysis menggunakan Pearson correlation coefficient:

```
r = Σ(xi - x̄)(yi - ȳ) / √[Σ(xi - x̄)² Σ(yi - ȳ)²]
```

Outlier detection menggunakan IQR method dengan threshold Q₁ - 1.5 × IQR dan Q₃ + 1.5 × IQR, serta Z-score analysis dengan threshold |z| > 3.

### c. Data Preparation

Tahap data preparation merupakan fase kritikal yang menentukan kualitas input untuk model LSTM. Data cleaning dimulai dengan handling missing values menggunakan forward-fill method untuk maintaining temporal continuity, dengan validasi terhadap pola musiman untuk memastikan imputasi tidak mengubah karakteristik fundamental dari time series.

Outlier treatment menggunakan winsorization dengan formula:

```
xwinsorization = {
  P₁      jika x < P₁
  x       jika P₁ ≥ x ≥ P₉₉
  P₉₉     jika x > P₉₉
}
```

di mana P₁ dan P₉₉ adalah 1st dan 99th percentiles.

Feature engineering menggunakan cyclical encoding untuk temporal features:

```
Month_sin = sin(2π × month / 12)
Month_cos = cos(2π × month / 12)
```

Rolling statistics dihitung dengan Moving Average:

```
MAₜ = 1/k Σ(xₜ₋ᵢ) dari i=0 hingga k-1
```

di mana k adalah window size (3, 6, 12 bulan).

Data preprocessing menggunakan StandardScaler dan RobustScaler:

**StandardScaler:**
```
z = (x - μ) / σ
```

**RobustScaler:**
```
z = (x - median) / (Q₃ - Q₁)
```

Instrumen untuk data preparation meliputi Python libraries (Pandas, NumPy, Scikit-learn), preprocessing pipelines otomatis, dan kerangka kerja validasi data. Teknik analisis menggunakan statistical testing untuk distribution normality, feature importance analysis, dan correlation assessment untuk feature selection.

Sequence generation menggunakan sliding window dengan window size yang akan dioptimasi melalui grid search. Train-validation-test split menggunakan chronological split: 70% training (1993-2015), 15% validation (2016-2019), 15% testing (2020-2024).

### d. Modeling

Desain arsitektur model LSTM enhanced ensemble menggabungkan LSTM untuk temporal pattern extraction dengan robust regression algorithms. Arsitektur LSTM menggunakan persamaan gate mechanisms:

**Forget Gate:**
```
fₜ = σ(Wf · [hₜ₋₁, xₜ] + bf)
```

**Input Gate:**
```
iₜ = σ(Wi · [hₜ₋₁, xₜ] + bi)
C̃ₜ = tanh(WC · [hₜ₋₁, xₜ] + bC)
```

**Cell State Update:**
```
Cₜ = fₜ * Cₜ₋₁ + iₜ * C̃ₜ
```

**Output Gate:**
```
oₜ = σ(Wo · [hₜ₋₁, xₜ] + bo)
hₜ = oₜ * tanh(Cₜ)
```

Ensemble integration menggunakan weighted averaging:

```
ŷensemble = Σ(wi · ŷi) dari i=1 hingga n
```

dengan constraint Σwi = 1 dari i=1 hingga n dan wi ≥ 0.

HuberRegressor menggunakan huber loss function:

```
Lδ(y, f(x)) = {
  1/2(y - f(x))²           untuk |y - f(x)| ≤ δ
  δ|y - f(x)| - 1/2δ²      untuk |y - f(x)| > δ
}
```

Instrumen model meliputi TensorFlow/Keras untuk LSTM implementation, Scikit-learn untuk metode ensemble, dan Optuna untuk hyperparameter optimization. Teknik analisis menggunakan time series cross-validation dengan expanding window, early stopping dengan patience mechanism, dan learning rate scheduling.

Adam Optimizer digunakan untuk training dengan persamaan:

```
θₜ₊₁ = θₜ - α/(√v̂ₜ + ε) · m̂ₜ
```

di mana m̂ₜ dan v̂ₜ adalah bias-corrected first dan second moment estimates.

Baseline model dikembangkan untuk comparison purposes, mencakup:
- ARIMA(p,d,q) dengan parameter optimal
- Linear Regression dengan polynomial features
- Random forest untuk temporal features
- Single LSTM architecture
- HuberRegressor individual

### e. Evaluation

Evaluasi performa model menggunakan multiple metrics untuk comprehensive assessment:

- **Root Mean Square Error (RMSE)** untuk measuring Prediction Accuracy dengan emphasis pada large errors:
  ```
  RMSE = √(1/n Σ(yi - ŷi)²) dari i=1 hingga n
  ```

- **Mean Absolute Error (MAE)** memberikan Robust metric untuk Average Prediction deviation:
  ```
  MAE = 1/n Σ|yi - ŷi| dari i=1 hingga n
  ```

- **Mean Absolute Percentage Error (MAPE)** menjadi metric utama dengan target < 10% untuk Business acceptability berdasarkan Standard industry practices dan benchmarks dari literatur terkait:
  ```
  MAPE = 100%/n Σ|yi - ŷi|/yi dari i=1 hingga n
  ```

- **R-Squared** untuk measuring explained variance proportion:
  ```
  R² = 1 - SSres/SStot = 1 - Σ(yi - ŷi)²/Σ(yi - ȳ)²
  ```

- **Directional Accuracy** untuk percentage of correct trend Predictions:
  ```
  DA = 1/(n-1) Σ I[·(yi - yi-1)(ŷi - ŷi-1) > 0] dari i=2 hingga n
  ```
  di mana I[·] adalah indicator function.

Instrumen evaluasi meliputi kerangka kerja statistical testing (SciPy), visualization tools (Matplotlib, Seaborn), dan model interpretability libraries (SHAP, LIME). Teknik analisis menggunakan paired t-test untuk statistical significance:

```
t = d̄/(sd/√n)
```

di mana d̄ adalah mean difference dan sd adalah standard deviation of differences.

Validation strategy menggunakan time series cross-validation dengan expanding window, walk-forward validation untuk real-world simulation, dan robustness testing under extreme scenarios. Model interpretability analysis menggunakan SHAP values untuk feature importance dan residual analysis untuk error pattern identification.

### f. Deployment

Implementasi sistem menggunakan containerized microservices architecture dengan separation of concerns. Frontend service dikembangkan menggunakan Laravel dengan Livewire components untuk reactive interface. Backend machine learning service menggunakan FastAPI dengan RESTful API endpoints untuk model serving.

Arsitektur sistem menggunakan request-response pattern:
- User request → Laravel Frontend
- API call → FastAPI ML Service
- Model inference → Prediction result
- Response → Frontend display

Instrumen deployment meliputi Docker containerization tools, CI/CD pipelines (GitHub Actions), dan kerangka kerja monitoring (Prometheus, Grafana). Teknik implementasi menggunakan automated deployment scripts, environment configuration management, dan security best practices.

Performance optimization menggunakan caching strategies, database indexing, dan API rate limiting. Security implementation meliputi authentication, input validation, dan secure communication protocols. Monitoring dan logging menggunakan structured logging untuk system observability dan performance tracking.

## 3.4 Jadwal Penelitian

Penelitian direncanakan berlangsung selama 3 bulan dengan distribusi waktu sebagai berikut.

| Tahap Penelitian | November 2025 | Desember 2025 | Januari 2026 |
|------------------|---------------|---------------|--------------|
| Pemahaman Bisnis & Pemahaman Data | ████████████ | | |
| Penyiapan Data & Feature Engineering | ████████ | ████████ | |
| Pengembangan Model LSTM | | ████████████ | |
| Integrasi Model Ensemble & Evaluasi | | ████████ | ████████ |
| Pengembangan Sistem (Laravel-FastAPI) | | | ████████████ |
| Pengujian, Dokumentasi & Laporan | | | ████████ |

---

# DAFTAR PUSTAKA

Adhany, P. C., Wulandari, C., Intan, B., & Santoso, B. (2025). Prediksi Padi Menggunakan Algoritma Long Short Term Memory. *Journal of Informatics Management and Information Technology*, 5(2), 120–127. https://doi.org/10.47709/digitech.v4i1.4141

ASEAN Secretariat. (2024). Enhancing and Integrating Regional Food Safety to Face the Changing Landscape of Food System and Health Threats. *ASEAN Socio-Cultural Community Trend Report No. 4*.

Asian Development Bank. (2023). Asian Development Outlook April 2023. In *Asian Development Bank* (Issue April).

Badan Pangan Nasional. (2021). Berita Negara. *Peraturan Menteri Kesehatan Republik Indonesia Nomor 4 Tahun 2018*, 1301, 1–8.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine learning in agriculture: A comprehensive updated review. *Sensors*, 21(11), 1–55. https://doi.org/10.3390/s21113758

BPS. (2023). Proyeksi Penduduk Indonesia 2020–2050 Hasil Sensus Penduduk 2020. In *Badan Pusat Statistik*.

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode Long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. *Jurnal Sistem Dan Teknologi Informasi (JustIN)*, 11(2), 346. https://doi.org/10.26418/justin.v11i2.57395

Fadila, L. Moh. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia : Pendekatan Menggunakan Big Data dan Data Mining. *Seminar Nasional Official Statistics*, 2023(1), 247–256. https://doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). The State of Food Security and Nutrition in the World 2023. In *The State of Food Security and Nutrition in the World 2023*. https://doi.org/10.4060/cc3017en

Howard, C., & Augustine, M. (2025). Ensemble Methods for Time Series Forecasting in Nigeria: Predicting Agricultural Yields Using Advanced Machine Learning Approaches. *Asian Journal of Pure and Applied Mathematics*, 7(1), 318–336. https://doi.org/10.56557/ajpam/2025/v7i1205

Iannone, A. (2023). Unveiling the Impact of the COVID-19 Pandemic (2019-2021) on Inequality, Poverty, and Food Security in Indonesia. *Politika: Jurnal Ilmu Politik*, 14(2), 189–208. https://doi.org/10.14710/politika.14.2.2023.189-208

Kamil, M. Z. F., Purnamasari, R., & Eliskar, Y. (2024). Perancangan Sistem Deploy Untuk Menghubungkan Machine learning Ke Website. *E-Proceeding of Engineering*, 11(6), 6394–6396. https://openlibrarypublications.telkomuniversity.ac.id/index.php/engineering/article/view/24940

Kementerian Pertanian. (2023). Laporan Kinerja Kementerian Pertanian Tahun 2023. *Kementerian Pertanian*, 1–230.

Kong, X., Chen, Z., Liu, W., Ning, K., Zhang, L., Muhammad Marier, S., Liu, Y., Chen, Y., & Xia, F. (2025). Deep learning for time series forecasting: a survey. In *International Journal of Machine Learning and Cybernetics* (Vol. 16, Issues 7–8). Springer Berlin Heidelberg. https://doi.org/10.1007/s13042-025-02560-w

Magalhães, Sais, A. C., & Rossi, F. (2025). Research on Using Ensemble Models to Assess the Impacts of Climate Change on Agriculture Production: A Review. *AgriEngineering*, 7(7), 1–18. https://doi.org/10.3390/agriengineering7070219

Narkunam, G. A. (2025). Enhancing Agricultural Forecasting with an Ensemble Learning Approach for Broccoli Yield Prediction. *Journal of Information Systems Engineering and Management*, 10(41s), 105–116. https://doi.org/10.52783/jisem.v10i41s.7754

OECD. (2021). Membangun Ketahanan Pangan dan Mengelola Risiko di Asia Tenggara. In M. G. F. E. B. Suwastoyo (Ed.), *OECD*. Yayasan Cipta Sentosa. https://doi.org/10.1787/9789264272392-en

Opara, I. K., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. *Plants*, 13(9), 1–21. https://doi.org/10.3390/plants13091200

Paudel, D., Neupane, R. C., Sigdel, S., Poudel, P., & Khanal, A. R. (2023). COVID-19 Pandemic, Climate Change, and Conflicts on Agriculture: A Trio of Challenges to Global Food Security. *Sustainability (Switzerland)*, 15(10), 1–22. https://doi.org/10.3390/su15108280

Pawar, A., Manjula Shenoy, K., Prabhu, S., & Guruprasad Rai, D. (2023). Performance analysis of machine learning algorithms: Single Model VS Ensemble Model. *Journal of Physics: Conference Series*, 2571(1). https://doi.org/10.1088/1742-6596/2571/1/012007

Raharjo, A. B., Wakhid, M. A., & Purwitasari, D. (2022). Load Forecasting for Daily Load Operational Plan Using Lstm (Case Study: South Sulawesi Sub System). *JUTI: Jurnal Ilmiah Teknologi Informasi*, 99–108. https://doi.org/10.12962/j24068535.v20i2.a1138

Rozaki, Z. (2021). Food security challenges and opportunities in indonesia post COVID-19. In *Advances in Food Security and Sustainability* (1st ed., Vol. 6). Elsevier Inc. https://doi.org/10.1016/bs.af2s.2021.07.002

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. *Agriculture (Switzerland)*, 13(10). https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Gómez, J. M. (2021). A systematic literature review on applying CRISP-DM process model. *Procedia Computer Science*, 181(2019), 526–534. https://doi.org/10.1016/j.procs.2021.01.199

Sekretariat Jendral - Kementrian Pertanian. (2024). Statistik Konsumsi Pangan Tahun 2024. *Pusat Data Dan Sistem Informasi Pertanian, Kementrian Pertanian Republik Indonesia*, 1–23. https://satudata.pertanian.go.id/details/publikasi/781

Serrano, A. L. M., Rodrigues, G. A. P., Martins, P. H. dos S., Saiki, G. M., Filho, G. P. R., Gonçalves, V. P., & Albuquerque, R. de O. (2024). Statistical Comparison of Time Series Models for Forecasting Brazilian Monthly Energy Demand Using Economic, Industrial, and Climatic Exogenous Variables. *Applied Sciences (Switzerland)*, 14(13), 1–32. https://doi.org/10.3390/app14135846

Singgalen, Y. A. (2023). Penerapan CRISP-DM dalam Klasifikasi Sentimen dan Analisis Perilaku Pembelian Layanan Akomodasi Hotel Berbasis Algoritma Decision Tree (DT). *Jurnal Sistem Komputer Dan Informatika (JSON)*, 5(2), 237. https://doi.org/10.30865/json.v5i2.7081

Siregar, T. M., Banjarnahor, T., Harahap, A., & Lumbanraja, I. (2024). Peranan Matematika dalam Memprediksi Data Ketahanan Pangan Indonesia 5 Tahun Ke Depan. *8*, 17013–17020.

Sujarwo, Putra, A. N., Setyawan, R. A., Teixeira, H. M., & Khumairoh, U. (2022). Forecasting Rice Status for a Food Crisis Early Warning System Based on Satellite Imagery and Cellular Automata in Malang, Indonesia. *Sustainability (Switzerland)*, 14(15). https://doi.org/10.3390/su14158972

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes Dengan Feature Selection Untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. *Jurnal Ilmiah Sains Dan Teknologi*, 6(1), 50–61. https://doi.org/10.47080/saintek.v6i1.1467

Sun, C., Pei, M., Cao, B., Chang, S., & Si, H. (2024). A Study on Agricultural Commodity Price Prediction Model Based on Secondary Decomposition and Long Short-Term Memory Network. *Agriculture (Switzerland)*, 14(1). https://doi.org/10.3390/agriculture14010060

Sundram, P. (2023). Food security in ASEAN: progress, challenges and future. *Frontiers in Sustainable Food Systems*, 7(October), 1–14. https://doi.org/10.3389/fsufs.2023.1260619

Tami, M., & Owda, A. Y. (2024). Efficient commodity price forecasting using long short-term memory model. *IAES International Journal of Artificial Intelligence*, 13(1), 994–1004. https://doi.org/10.11591/ijai.v13.i1.pp994-1004

Waqas, M., Naseem, A., Humphries, U. W., Hlaing, P. T., Dechpichai, P., & Wangwongchai, A. (2025). Applications of machine learning and deep learning in agriculture: A comprehensive review. *Green Technologies and Sustainability*, 3(3), 100199. https://doi.org/10.1016/j.grets.2025.100199

Yang, H., Jiao, W., Zouyi, L., Diao, H., & Xia, S. (2025). Artificial intelligence in the food industry: innovations and applications. In *Discover Artificial Intelligence* (Vol. 5, Issue 1). Springer International Publishing. https://doi.org/10.1007/s44163-025-00296-8

Zhang, L., Wang, R., Li, Z., Li, J., Ge, Y., Wa, S., Huang, S., & Lv, C. (2023). Time-Series Neural Network: A High-Accuracy Time-Series Forecasting Method Based on Kernel Filter and Time Attention. *Information (Switzerland)*, 14(9), 1–18. https://doi.org/10.3390/info14090500doi.org/10.47065/jimat.v5i2.496

Alkahfi, C., Kurnia, A., & Saefuddin, A. (2024). Performance Comparison of RNN-Based Models in Forecasting Indonesian Economic and Financial Data Perbandingan Kinerja Model Berbasis RNN pada Peramalan Data Ekonomi dan Keuangan Indonesia. *MALCOM: Indonesian Journal of Machine Learning and Computer Science*, 4(October), 1235–1243. https://doi.org/10.57152/malcom.v4i4.1415

Arwansyah, A., Suryani, S., SY, H., Usman, U., Ahyuna, A., & Alam, S. (2022). Time Series Forecasting Menggunakan Deep Gated Recurrent Units. *Digital Transformation Technology*, 4(1), 410–416. https://