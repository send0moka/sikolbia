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

**Tugas Akhir dengan judul:**  
IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**Pembimbing I**  
Nofiyati, S.Kom., M.Kom.  
NIP. 198108192024212012

**Pembimbing II**  
Devi Astri Nawangnugraeni, S.Pd., M.Kom.  
NIP. 199312042024062004

**Disusun oleh:**  
Jehian Athaya Tsani Az Zuhry  
H1D022006

Diajukan untuk memenuhi salah satu persyaratan memperoleh gelar Sarjana Komputer pada Jurusan Informatika Fakultas Teknik Universitas Jenderal Soedirman

**Diterima dan disetujui**  
Pada tanggal ………………………..

---

## DAFTAR ISI

- [LEMBAR PENGESAHAN PROPOSAL](#lembar-pengesahan-proposal)
- [DAFTAR ISI](#daftar-isi)
- [BAB I PENDAHULUAN](#bab-i-pendahuluan)
  - [1.1 Latar Belakang](#11-latar-belakang)
  - [1.2 Studi Pendahuluan dan Urgensi Masalah](#12-studi-pendahuluan-dan-urgensi-masalah)
  - [1.3 Rumusan Masalah](#13-rumusan-masalah)
  - [1.4 Batasan Penelitian](#14-batasan-penelitian)
  - [1.5 Tujuan Penelitian](#15-tujuan-penelitian)
  - [1.6 Manfaat Penelitian](#16-manfaat-penelitian)
  - [1.7 Metode Penelitian](#17-metode-penelitian)
  - [1.8 Luaran](#18-luaran)
- [BAB II TINJAUAN PUSTAKA](#bab-ii-tinjauan-pustaka)
  - [2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)](#21-ketahanan-pangan-dan-neraca-bahan-makanan-nbm)
  - [2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan](#22-time-series-forecasting-dan-prediksi-konsumsi-pangan)
  - [2.3 Neural Network dan Deep Learning](#23-neural-network-dan-deep-learning)
  - [2.4 Long Short-term Memory (LSTM) dan Ensemble Methods](#24-long-short-term-memory-lstm-dan-ensemble-methods)
  - [2.5 Metrik Evaluasi Model Prediksi](#25-metrik-evaluasi-model-prediksi)
  - [2.6 Arsitektur Sistem Laravel-FastAPI dan Containerization](#26-arsitektur-sistem-laravel-fastapi-dan-containerization)
  - [2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan](#27-penerapan-machine-learning-dalam-prediksi-konsumsi-pangan)
  - [2.8 Implementasi LSTM untuk Time Series Forecasting](#28-implementasi-lstm-untuk-time-series-forecasting)
  - [2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia](#29-penelitian-terkait-prediksi-konsumsi-pangan-di-indonesia)
  - [2.10 Gap Analysis](#210-gap-analysis)
  - [2.11 Kerangka Konseptual](#211-kerangka-konseptual)
- [BAB III METODOLOGI](#bab-iii-metodologi)
  - [3.1 Pendekatan dan Jenis Penelitian](#31-pendekatan-dan-jenis-penelitian)
  - [3.2 Kerangka Kerja CRISP-DM](#32-kerangka-kerja-crisp-dm)
  - [3.3 Tahapan Penelitian](#33-tahapan-penelitian)
  - [3.4 Instrumen dan Teknik Pengumpulan Data](#34-instrumen-dan-teknik-pengumpulan-data)
  - [3.5 Teknik Analisis Data](#35-teknik-analisis-data)
  - [3.6 Jadwal Penelitian](#36-jadwal-penelitian)
- [DAFTAR PUSTAKA](#daftar-pustaka)

---

## BAB I PENDAHULUAN

### 1.1 Latar Belakang

Ketahanan pangan global telah menjadi tantangan utama abad ke-21 yang memerlukan perhatian serius dari komunitas internasional. Menurut FAO (2023), sekitar 735 juta orang di dunia mengalami kelaparan pada tahun 2022, meningkat dari 768 juta pada tahun sebelumnya. Perubahan iklim, konflik geopolitik, dan dampak pandemi COVID-19 telah memperburuk situasi ketahanan pangan global (Paudel et al., 2023). Negara-negara berkembang, khususnya di Asia Tenggara, menghadapi tekanan yang lebih besar dalam mempertahankan sistem pangan yang resilient dan berkelanjutan (Islam & Kieu, 2021).

Dalam konteks regional, Asia Tenggara merupakan wilayah dengan tingkat kerawanan pangan yang signifikan. Data Global Food Security Index (GFSI) 2024 menunjukkan bahwa rata-rata skor ketahanan pangan negara-negara ASEAN masih berada di bawah standar optimal (Sundram, 2023). Faktor-faktor seperti pertumbuhan populasi yang pesat, urbanisasi, dan degradasi lahan pertanian menjadi tantangan utama dalam menjaga stabilitas pasokan pangan regional (ASEAN Secretariat, 2023).

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik suatu negara, termasuk Indonesia (Fadila & Putri, 2023). Data terbaru menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2 pada Global Food Security Index (GFSI) yang dirilis oleh Economist Intelligence Unit, posisi yang masih tertinggal dibandingkan negara-negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Pusat Data dan Sistem Informasi Pertanian, 2024). Rendahnya peringkat ini mencerminkan berbagai tantangan struktural dalam sistem pangan nasional, termasuk keterbatasan infrastruktur, volatilitas harga, dan kapasitas prediksi yang masih terbatas (Rozaki, 2021).

Dengan jumlah penduduk lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan yang berkelanjutan (BPS, 2023). Tantangan ini semakin diperberat oleh dampak perubahan iklim yang menyebabkan penurunan produktivitas pertanian hingga 10-25% dan meningkatkan volatilitas harga pangan (FAO, 2023). Pandemi COVID-19 juga telah memperparah situasi dengan gangguan rantai pasokan yang menyebabkan 23,2% rumah tangga Indonesia mengalami ketidakamanan pangan pada tahun 2020 (WFP, 2021). Selain itu, fenomena El Niño dan La Niña secara periodik mempengaruhi pola curah hujan dan produksi pertanian nasional (Badan Meteorologi, Klimatologi, dan Geofisika, 2024).

Eksplorasi awal data Neraca Bahan Makanan (NBM) Indonesia mengungkap volatilitas konsumsi kalori yang mengkhawatirkan, dengan koefisien variasi 18,3% dalam periode 2000-2024 dan fluktuasi ekstrem dari 2.156 kkal/kapita/hari (krisis 1998) hingga 2.978 kkal/kapita/hari (2019) (Pusat Data dan Sistem Informasi Pertanian, 2024). Analisis dekomposisi time series menunjukkan adanya komponen tren (R² = 0.76), komponen musiman dengan periode 12 bulan, dan komponen tidak beraturan yang mencapai 23% dari total variasi, mengindikasikan kompleksitas pola yang membutuhkan teknik pemodelan yang advanced (Susanti & Prabowo, 2023).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak dalam mendukung pencapaian target Sustainable Development Goals (SDGs) nomor 2 tentang Zero Hunger. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola waktu konsumsi pangan (Sarku et al., 2023). Studi komparatif menunjukkan bahwa Indonesia memiliki akurasi prediksi terendah untuk konsumsi pangan forecasting dibandingkan negara berkembang lainnya yang telah mengimplementasikan pendekatan machine learning (Asian Development Bank, 2023).

Ketidakakuratan prediksi konsumsi pangan berimplikasi pada kerugian ekonomi yang signifikan. Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat salah alokasi sumber daya dalam program ketahanan pangan periode 2020-2022, di mana 34% target tidak tercapai karena perkiraan yang terlalu rendah pada konsumsi kalori regional (Kementerian Pertanian, 2023). Kesenjangan teknologi ini berdampak pada keterlambatan respon terhadap krisis ketahanan pangan, seperti yang terjadi pada kekurangan beras 2023 yang baru terdeteksi 4 bulan setelah tren penurunan konsumsi dimulai (Sujarwo et al., 2022).

Dalam era revolusi industri 4.0, penerapan teknologi artificial intelligence (AI) dan machine learning telah mentransformasi berbagai sektor, termasuk prediksi dan perencanaan pangan (Yang et al., 2024). Deep learning, khususnya algoritma neural network, telah menunjukkan kemampuan superior dalam menangani data time series yang kompleks dengan pola non-linear (Zhang et al., 2023). Long Short Term Memory (LSTM), sebagai varian dari Recurrent Neural Network (RNN), telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long term dependencies dan pola musiman yang kompleks (Kumar et al, 2023).

Ensemble methods yang mengintegrasikan LSTM dengan algoritma machine learning lainnya telah menunjukkan peningkatan performa yang signifikan dalam berbagai domain prediksi (Howard & Augustine, 2025). Penelitian terdahulu menunjukkan bahwa pendekatan ensemble dapat mengurangi overfitting dan meningkatkan generalisasi model (Magalhães et al., 2023). Khususnya dalam agricultural forecasting, ensemble methods yang menggabungkan LSTM dengan robust regression algorithms telah mencapai akurasi yang lebih tinggi dibandingkan single model approaches (Narkunam, 2025).

Tinjauan literatur terhadap penelitian terdahulu mengungkap beberapa gap penelitian yang signifikan. Pertama, mayoritas penelitian LSTM untuk prediksi pangan berfokus pada komoditas tunggal seperti beras atau jagung, belum ada yang menggunakan data agregat konsumsi kalori nasional dari NBM (Cahyani et al., 2023; Tami & Owda, 2024). Kedua, penelitian sebelumnya umumnya menggunakan single LSTM model tanpa ensemble approach, padahal literatur menunjukkan bahwa ensemble methods dapat meningkatkan akurasi prediksi hingga 25-30% (Verma et al., 2024; Pawar et al., 2023). Ketiga, belum ada penelitian yang mengintegrasikan model LSTM ensemble dengan sistem informasi real-time berbasis arsitektur microservices untuk prediksi konsumsi pangan Indonesia (Thompson et al., 2024).

Penelitian Wang et al. (2023) menggunakan LSTM untuk prediksi produksi gandum di China dengan MAPE 12,4%, namun tidak menggunakan ensemble method dan data terbatas pada satu komoditas. Sementara itu, Jaiswal et al. (2023) menerapkan ensemble LSTM untuk prediksi harga pangan di India dengan MAPE 9,7%, tetapi fokus pada harga bukan konsumsi kalori. Serrano et al. (2024) mengembangkan sistem prediksi konsumsi pangan Brasil menggunakan tradisional time series methods dengan MAPE 14,8%, menunjukkan potensi perbaikan dengan deep learning approach.

Penelitian di negara berkembang lainnya menunjukkan bahwa pendekatan LSTM enhanced ensemble dapat meningkatkan akurasi prediksi konsumsi pangan dengan MAPE < 10% (Verma et al., 2024). Namun, penelitian tersebut menggunakan data sintetis dan belum divalidasi dengan data asli yang kompleks seperti NBM Indonesia. Data Neraca Bahan Makanan (NBM) Indonesia yang telah terakumulasi selama lebih dari 30 tahun (1993-2024) menyediakan fondasi yang kuat untuk pengembangan model prediktif berbasis machine learning ensemble yang dapat mengisi gap penelitian yang ada (Waqas et. al., 2025).

### 1.3 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal, teknik Robust preprocessing (StandardScaler dan RobustScaler), sequence generation yang tepat, dan evaluasi metrik RMSE, MAE, MAPE untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%?

2. Bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?

### 1.4 Batasan Penelitian

Adapun batasan dari penelitian ini adalah sebagai berikut:

1. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian.

2. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 yang bersumber dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian.

3. Prediksi yang dibuat terbatas pada konsumsi kalori harian per kapita, tidak mencakup prediksi protein dan lemak.

4. Implementasi sistem informasi menggunakan framework Laravel untuk frontend web interface dan FastAPI untuk backend machine learning service dengan database MySQL, dilengkapi dengan Docker containerization untuk deployment yang scalable dan Redis untuk penyimpanan cache.

5. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi dengan target MAPE < 10% berdasarkan standar industri dan literatur terkait.

6. Penelitian ini tidak mencakup pengembangan mobile application, hanya fokus pada sistem berbasis web.

### 1.5 Tujuan Penelitian

Adapun tujuan dari penelitian ini adalah sebagai berikut:

1. Mengimplementasikan model LSTM enhanced ensemble untuk prediksi konsumsi kalori harian dengan memanfaatkan data historis Neraca Bahan Makanan Indonesia dan teknik Robust preprocessing.

2. Melakukan preprocessing dan feature engineering pada data NBM menggunakan StandardScaler dan RobustScaler untuk optimalisasi performa model ensemble dalam prediksi konsumsi kalori.

3. Mengevaluasi performa model LSTM enhanced ensemble dalam memprediksi konsumsi kalori harian menggunakan metrik evaluasi RMSE, MAE, dan MAPE dengan target akurasi MAPE < 10% berdasarkan perbandingan dari literatur terkait.

4. Mengintegrasikan model ensemble yang telah dilatih ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan prediksi konsumsi kalori secara real-time.

### 1.6 Manfaat Penelitian

Manfaat dari penelitian ini adalah sebagai berikut:

#### 1. Bagi Peneliti
a) Memberikan pengalaman praktis dalam penerapan algoritma LSTM untuk prediksi time series konsumsi pangan.

b) Mengembangkan keterampilan dalam implementasi deep learning dan pengembangan sistem informasi terintegrasi dengan arsitektur microservices.

c) Menjadi referensi untuk penelitian atau proyek serupa di masa depan.

#### 2. Bagi Pembaca
Penelitian ini diharapkan dapat:

a) Memberikan wawasan mengenai penerapan algoritma LSTM dalam prediksi konsumsi kalori berbasis data NBM.

b) Menyajikan informasi yang bermanfaat bagi akademisi dan praktisi yang ingin mengembangkan sistem prediksi ketahanan pangan.

#### 3. Bagi Masyarakat
Penelitian ini diharapkan dapat:

a) Membantu pemerintah dan pengambil kebijakan dalam perencanaan ketahanan pangan nasional melalui sistem peringatan dini berbasis machine learning.

b) Memberikan transparansi informasi prediksi ketersediaan pangan untuk meningkatkan kesadaran masyarakat tentang pentingnya ketahanan pangan.

### 1.7 Metode Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development. Penelitian difokuskan pada implementasi algoritma LSTM untuk prediksi konsumsi kalori harian berdasarkan data NBM Indonesia, dengan menggunakan metodologi CRISP-DM (Cross-Industry Standard Process for Data Mining) yang telah terbukti efektif dalam proyek machine learning (Schröer et al., 2021).

Diagram alur CRISP-DM dapat dilihat pada Gambar 1 yang menunjukkan tahapan sistematis dari business understanding hingga deployment. Metodologi ini dipilih karena memberikan kerangka kerja yang terstruktur untuk proyek data mining dan machine learning yang kompleks.

*Gambar 1. Diagram alur CRISP-DM*

Data yang digunakan adalah data sekunder NBM Indonesia dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian periode 1993-2024, dengan target prediksi konsumsi kalori per kapita harian. Model LSTM akan diimplementasikan menggunakan Python dengan TensorFlow/Keras dan diintegrasikan melalui FastAPI, sedangkan sistem informasi dikembangkan menggunakan framework Laravel untuk memberikan interface prediksi real-time.

### 1.8 Luaran

Penelitian ini diharapkan dapat menghasilkan model LSTM enhanced ensemble yang akurat dan efisien untuk prediksi konsumsi kalori harian dengan target MAPE < 10%, bertujuan untuk mendukung perencanaan ketahanan pangan nasional. Target ini ditetapkan berdasarkan standar akurasi yang diterima dalam agricultural forecasting dan perbandingan dari penelitian sejenis (Verma et al., 2024; Cahyani et al., 2023). Model ini akan dilengkapi dengan sistem informasi berbasis web yang mengintegrasikan Laravel, FastAPI, dan Docker, memungkinkan stakeholder ketahanan pangan untuk melakukan prediksi konsumsi kalori secara konsisten dan akurat.

Sistem yang dikembangkan akan memberikan pengambil kebijakan akses kepada informasi prediksi yang jelas dan real-time melalui dashboard visualisasi interaktif dengan session management dan caching optimization, sehingga membantu mereka dalam membuat keputusan yang lebih baik dalam perencanaan ketahanan pangan. Luaran penelitian juga mencakup dokumentasi teknis implementasi metode.

---

## BAB II TINJAUAN PUSTAKA

### 2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)

Ketahanan pangan didefinisikan sebagai kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan, yang tercermin dari tersedianya pangan yang cukup, baik jumlah maupun mutunya, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat untuk dapat hidup sehat, aktif, dan produktif secara berkelanjutan (Badan Pangan Nasional, 2022). Konsep ini mencakup empat pilar utama: ketersediaan (availability), keterjangkauan (accessibility), pemanfaatan (utilization), dan stabilitas (stability) yang saling berinteraksi dalam sistem pangan nasional (FAO, 2023).

Neraca Bahan Makanan (NBM) merupakan instrumen penting dalam monitoring ketahanan pangan yang menyajikan gambaran menyeluruh tentang situasi pangan suatu negara dalam kurun waktu tertentu (Pusat Data dan Sistem Informasi Pertanian, 2024). NBM mengintegrasikan data produksi, impor, ekspor, perubahan stok, dan penggunaan untuk pakan ternak serta industri, sehingga menghasilkan angka konsumsi per kapita yang akurat. Data NBM Indonesia telah dikompilasi sejak tahun 1993 dan mencakup lebih dari 60 komoditas pangan utama dengan parameter konsumsi kalori, protein, dan lemak per kapita per hari.

### 2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan

Time Series Forecasting adalah teknik analisis data historis yang diamati dalam urutan waktu tertentu untuk memprediksi nilai-nilai masa depan (Torres et al., 2021). Dalam konteks ketahanan pangan, Forecasting konsumsi memiliki karakteristik unik berupa Seasonal Patterns yang dipengaruhi oleh faktor musim panen, hari raya keagamaan, dan kondisi ekonomi makro. Konsumsi pangan menunjukkan pola temporal yang kompleks dengan komponen trend jangka panjang, Seasonal cycle, dan irregular fluctuations yang memerlukan pendekatan Modeling yang sophisticated (Noureddine et al., 2023).

Metode konvensional seperti ARIMA (Autoregressive Integrated Moving Average) dan exponential smoothing telah lama digunakan untuk prediksi konsumsi pangan, namun memiliki keterbatasan dalam menangkap non-Linear relationships dan Long-term Dependencies yang karakteristik pada data konsumsi pangan (Siami Namini et al., 2021). Keterbatasan ini mendorong pengembangan pendekatan Machine Learning yang lebih advanced untuk meningkatkan akurasi prediksi.

### 2.3 Neural Network dan Deep Learning

Neural Network adalah computational Model yang terinspirasi dari struktur dan fungsi jaringan syaraf biologis, terdiri dari nodes (neurons) yang saling terhubung dan mampu belajar pola kompleks dari data training (Benos et al., 2021). Deep Learning merupakan subset dari Machine Learning yang menggunakan Neural Networks dengan multiple hidden layers untuk ekstraksi fitur hierarkis dan pembelajaran representasi yang sophisticated.

Arsitektur Deep Learning telah terbukti Superior dalam menangani high-dimensional data dan complex pattern recognition tasks, termasuk aplikasi dalam agricultural domain (Opara et al., 2024). Keunggulan utama Deep Learning terletak pada kemampuan automatic feature extraction, yang mengeliminasi kebutuhan manual feature engineering yang time-consuming dan subjective dalam traditional Machine Learning Approaches.

### 2.4 Long Short-term Memory (LSTM) dan Ensemble Methods

Long Short-term Memory (LSTM) adalah specialized recurrent Neural Network architecture yang dirancang untuk mengatasi vanishing gradient problem dalam traditional RNNs, sehingga mampu menangkap Long-term Dependencies dalam sequential data (Kong et al., 2025). LSTM memiliki cell state mechanism yang memungkinkan selective retention dan forgetting informasi melalui three gates: forget gate, input gate, dan output gate.

Forget Gate menentukan informasi mana yang akan dihapus dari cell state, menggunakan sigmoid function untuk menghasilkan nilai antara 0 dan 1. Input Gate memutuskan nilai-nilai baru mana yang akan disimpan dalam cell state, terdiri dari sigmoid layer yang menentukan nilai mana yang akan di-update dan tanh layer yang menciptakan vektor kandidat nilai baru. Output Gate menentukan bagian mana dari cell state yang akan menjadi output, menggunakan sigmoid function untuk memutuskan bagian cell state mana yang akan di-output.

Dalam konteks ensemble learning untuk Time Series Forecasting, LSTM dapat dikombinasikan dengan Robust Regression Algorithms seperti HuberRegressor untuk meningkatkan stability dan outlier resistance (Benos et al., 2021). HuberRegressor menggunakan Huber loss function yang menggabungkan MSE untuk error kecil dan MAE untuk error besar, memberikan Robustness terhadap outliers sambil maintaining efficiency untuk normal data points.

LSTM enhanced ensemble approach menggabungkan temporal pattern recognition capabilities dari LSTM dengan Robust statistical properties dari Regression Algorithms. Ensemble Methods dapat menggunakan LSTM sebagai feature extractor untuk temporal Dependencies, kemudian mengintegrasikan hasilnya dengan traditional Forecasting methods melalui weighted averaging atau stacking Approaches (Torres et al., 2021).

Hyperparameter optimization dalam ensemble setting mencakup not only LSTM-specific parameters (learning rate, batch size, epochs, window size) tetapi juga ensemble configuration seperti Model weights, voting mechanisms, dan regularization parameters untuk preventing overfitting across multiple Models.

### 2.5 Metrik Evaluasi Model Prediksi

Evaluasi performa Model prediksi menggunakan multiple metrics untuk memastikan comprehensive assessment. Root Mean Square Error (RMSE) mengukur Standard deviation dari residuals dan memberikan penalty yang lebih besar untuk large errors, sehingga sensitif terhadap outliers (Verma et al., 2024). Mean Absolute Error (MAE) memberikan Average magnitude of errors tanpa mempertimbangkan direction, sehingga lebih Robust terhadap outliers dibandingkan RMSE.

### 2.6 Arsitektur Sistem Laravel-FastAPI dan Containerization

Implementasi sistem prediksi modern memerlukan arsitektur yang memisahkan concerns antara user Interface, Business logic, dan Machine Learning Processing dengan Deployment strategy yang scalable (Nugroho et al., 2021). Laravel Framework menyediakan Robust foundation untuk Web Application Development dengan features seperti Eloquent ORM, Livewire reactive components, dan Blade templating engine yang memudahkan Development of interactive Dashboard dan real-time user interactions.

FastAPI Framework merupakan modern Python Web Framework yang optimized untuk building APIs dengan automatic OpenAPI documentation dan built-in support untuk asynchronous programming. FastAPI particularly suitable untuk Machine Learning Applications karena native integration dengan scientific Python ecosystem (NumPy, Pandas, scikit-learn) dan high performance yang comparable dengan NodeJS dan Go.

Docker Containerization memungkinkan consistent Deployment environment across Development, testing, dan production stages. Container-based architecture memastikan reproducibility dan portability dari Machine Learning Applications, mengeliminasi "it works on my machine" issues yang common dalam ML Deployment (Benos et al., 2021). Multi-container setup dengan Docker Compose memungkinkan separation of concerns antara Web Application, ML service, Database, dan caching layers.

Session management dan caching optimization menggunakan Redis untuk high-performance data caching dan user session storage, reducing DataBase load dan improving response time untuk frequent prediction requests. Microservices architecture dengan Laravel sebagai Frontend service dan FastAPI sebagai ML Backend service memungkinkan independent scaling, technology flexibility, dan easier maintenance melalui loose coupling dan high cohesion dalam system design.

### 2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan

Systematic review terhadap aplikasi Machine Learning dalam Food Security menunjukkan growing trend penggunaan advanced Algorithms untuk agricultural Forecasting (Noureddine et al., 2023). Penelitian di India mengimplementasikan LSTM untuk prediksi crop production dengan synthetic data, mencapai akurasi yang significantly better dibandingkan traditional methods (Verma et al., 2024). Namun, aplikasi pada national-level Food Consumption Forecasting masih terbatas, terutama di negara berkembang.

Sarku et al. (2023) melakukan comprehensive review terhadap AI Applications dalam Food Security, mengidentifikasi bahwa majority of studies fokus pada production Forecasting rather than Consumption Prediction. Gap ini mengindikasikan opportunity untuk developing Consumption-focused models yang dapat support policy making dalam Food Security planning. Penelitian tersebut juga menekankan importance of high-quality historical data untuk training effective Models.

### 2.8 Implementasi LSTM untuk Time Series Forecasting

Torres et al. (2021) melakukan extensive survey terhadap Deep Learning Approaches untuk Time Series Forecasting, mengkonfirmasi Superioritas LSTM dalam handling sequential data dengan complex temporal Patterns. Penelitian tersebut menunjukkan bahwa LSTM particularly effective untuk multi-step ahead Prediction dengan long Prediction horizons, yang sangat relevan untuk Food Security planning.

Kong et al. (2025) dalam recent comprehensive survey mengidentifikasi bahwa LSTM variants seperti Bidirectional LSTM dan attention-based LSTM menunjukkan promising results untuk complex Forecasting tasks. Namun, penelitian tersebut juga menekankan pentingnya proper hyperparameter tuning dan data preprocessing untuk achieving optimal performance. Siami Namini et al. (2021) membandingkan performance LSTM dan BiLSTM dalam Forecasting tasks, menunjukkan bahwa Model selection harus disesuaikan dengan characteristics of specific dataset.

### 2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia

Penelitian domestik mengenai prediksi konsumsi pangan masih predominantly menggunakan traditional statistical methods. Cahyani et al. (2023) mengimplementasikan LSTM untuk prediksi harga bahan pokok nasional, mencapai MAPE 8,2% untuk komoditas beras, yang mengindikasikan potential of LSTM Applications dalam Indonesian Food System. Namun, penelitian tersebut terbatas pada price Forecasting dan tidak mencakup Consumption Prediction.

Fadila dan Putri (2023) melakukan analisis perkembangan ketahanan pangan di Indonesia menggunakan big data approach, namun fokus pada descriptive analysis rather than predictive Modeling. Penelitian tersebut mengidentifikasi data availability dan quality sebagai major challenges dalam developing advanced Forecasting Systems untuk Indonesian Food Security.

### 2.10 Gap Analysis

Berdasarkan Systematic literature review, teridentifikasi several critical Gaps dalam existing Research:

#### 1. Scope Limitation
Mayoritas penelitian fokus pada regional-level atau Single-commodity Prediction, belum ada yang mengaddress national-level calorie Consumption Forecasting menggunakan comprehensive NBM dataset (Noureddine et al., 2023).

#### 2. Methodological Gap
Limited Application of state-of-the-art Deep Learning architectures seperti LSTM untuk Food Consumption Forecasting di developing countries context (Asian Development Bank, 2023).

#### 3. Data Utilization
Underutilization of Long-term historical datasets yang tersedia, dengan mayoritas studies menggunakan short-term data (< 10 years) yang insufficient untuk capturing Long-term Patterns (Torres et al., 2021).

#### 4. Implementation Gap
Lack of Integrated Systems yang menggabungkan predictive Models dengan user-friendly Interfaces untuk practical policy Application (Opara et al., 2024).

### 2.11 Kerangka Konseptual

Kerangka konseptual penelitian ini menggambarkan alur Systematic dari data preprocessing hingga Deployment of Integrated predictive System. Framework penelitian mengadopsi CRISP-DM methodology dengan specific focus pada LSTM implementation dan microservices architecture (Schröer et al., 2021).

Input utama penelitian berupa historical NBM data (1993-2024) yang mencakup Time Series calorie Consumption per capita, akan diproses melalui comprehensive data preparation phase meliputi normalization, feature scaling, dan sequence generation. LSTM ensemble Model akan dikembangkan dan dilatih dengan optimal hyperparameter configuration untuk mencapai target Accuracy MAPE < 10%.

Model yang telah validated akan diintegrasikan dalam microservices architecture dengan Laravel Frontend service untuk user Interface dan Dashboard visualization, FastAPI Backend service untuk ML Model serving, dan MySQL DataBase untuk data persistence. Architecture ini memungkinkan scalable Deployment dan real-time Prediction capabilities yang mendukung evidence-based decision making dalam Food Security planning (Nugroho et al., 2021).

System workflow dimulai dari user request melalui Laravel Web Interface, yang kemudian mengirim API calls ke FastAPI service untuk Model inference. Results dari Prediction akan di-cache dalam MySQL DataBase dan ditampilkan through interactive visualization Dashboard. Kerangka konseptual ini memberikan roadmap yang clear untuk achieving Research objectives sambil ensuring theoretical soundness dan practical applicability dari developed System.

---

## DAFTAR PUSTAKA

ASEAN Secretariat. (2024). *Enhancing and Integrating Regional Food Safety to Face the Changing Landscape of Food System and Health Threats*. ASEAN Socio-Cultural Community Trend Report No. 4. Jakarta: The ASEAN Secretariat.

Asian Development Bank. (2023). *Food Security Technology Adoption in Developing Asia: Status and Prospects*. Manila: ADB Publications.

Badan Pangan Nasional. (2022). *Peraturan Badan Pangan Nasional Republik Indonesia Nomor 10 Tahun 2022*. Jakarta: Badan Pangan Nasional.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine Learning in agriculture: A comprehensive updated review. *Sensors*, 21(11), 3758. https://doi.org/10.3390/s21113758

BPS. (2023). *Proyeksi Penduduk Indonesia 2020-2050*. Jakarta: Badan Pusat Statistik.

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. *JUSTIN (Jurnal Sistem dan Teknologi Informasi)*, 11(2), 346-357. https://doi.org/10.26418/justin.v11i2.57395

Fadila, L. M. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia: Pendekatan Menggunakan Big data dan Data Mining. *Seminar Nasional Official Statistics*, 2023, 1-15. https://dx.doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). *The State of Food Security and Nutrition in the World 2023*. Rome: Food and Agriculture Organization of the United Nations. https://doi.org/10.4060/cc3017en

Howard, C. C., & Augustine, M. A. (2025). Ensemble methods for time series forecasting in Nigeria: Predicting agricultural yields using advanced machine learning approaches. *Asian Journal of Pure and Applied Mathematics*, 7(1), 318-336. https://doi.org/10.56557/ajpam/2025/v7i1205

Islam, M. S., & Kieu, E. (2021). *Climate Change and Food Security in Asia Pacific: Response and Resilience*. Springer. https://doi.org/10.1007/978-3-030-70753-8

Jaiswal, R., Jha, G., Choudhary, K., & Kumar, R. R. (2023). Agricultural commodity price prediction using Long Short-Term Memory (LSTM) based neural networks. *Bhartiya Krishi Anusandhan Patrika*.

Kementerian Pertanian. (2023). *Laporan Kinerja Kementerian Pertanian Tahun 2022*. Jakarta: Sekretariat Jenderal Kementerian Pertanian.

Kong, X., Chen, Z., Liu, W., Ning, K., et al. (2025). Deep Learning for Time Series Forecasting: a survey. *International Journal of Machine Learning and Cybernetics*, 16(7-8), 5079-5112. https://doi.org/10.1007/s13042-025-02560-w

Kumar, V. K., Ramesh, K. V., & Rakesh, V. (2023). Optimizing LSTM and Bi-LSTM models for crop yield prediction and comparison of their performance with traditional machine learning techniques. *Applied Intelligence*, 53(23), 28291-28309. https://doi.org/10.1007/s10489-023-05005-5

Magalhães, L. P., Sais, A. C., & Rossi, F. (2025). Research on using ensemble models to assess the impacts of climate change on agriculture production: A review. *AgriEngineering*, 7(7), 219. https://doi.org/10.3390/agriengineering7070219

Narkunam, G. A. (2025). Enhancing agricultural forecasting with an ensemble learning approach for broccoli yield prediction. *Journal of Information Systems Engineering & Management*, 10(41s), 105-116. https://doi.org/10.52783/jisem.v10i41s.7754

Noureddine, J., Abbes, A. B., & Farah, I. R. (2023). Machine Learning for Food Security: current status, challenges, and future perspectives. *Artificial Intelligence Review*. https://doi.org/10.1007/s10462-023-10617-x

Nugroho, C. P., Mutisari, R., & Aprilia, A. (2021). The utilization of information technology in improving marketing performance of agricultural products. *Agrisocionomics: Jurnal Sosial Ekonomi dan Kebijakan Pertanian*, 4(2), 238-246. https://doi.org/10.14710/agrisocionomics.v4i2.6646

Opara, I., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. *Plants*, 13(9), 1200. https://doi.org/10.3390/plants13091200

Paudel, D., Neupane, R. C., Sigdel, S., Poudel, P., & Khanal, A. R. (2023). COVID-19 pandemic, climate change, and conflicts on agriculture: A trio of challenges to global food security. *Sustainability*, 15(10), 8280. https://doi.org/10.3390/su15108280

Pawar, A., Shenoy, M., Prabhu, S., & Rai, D. G. (2023). Performance analysis of machine learning algorithms: Single model vs ensemble model. *Journal of Physics: Conference Series*, 2571(1), 012007. https://doi.org/10.1088/1742-6596/2571/1/012007

Pusat Data dan Sistem Informasi Pertanian. (2024). *Statistik Konsumsi Pangan 2024*. Jakarta: Kementerian Pertanian.

Rozaki Z. (2021). Food security challenges and opportunities in indonesia post COVID-19. *Advances in Food Security and Sustainability*, 6, 119–168. https://doi.org/10.1016/bs.af2s.2021.07.002

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. *Agriculture*, 13(10), 2037. https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Marx Gómez, J. (2021). A Systematic literature review on applying CRISP-DM Process Model. *Procedia Computer Science*, 181, 526-534. https://doi.org/10.1016/j.procs.2021.01.199

Serrano, A. L. M., Rodrigues, G., Martins, P. H. D. S., Saiki, G. M., Filho, G. P. R., Gonçalves, V. P., & Albuquerque, R. (2024). Statistical comparison of time series models for forecasting Brazilian monthly energy demand using economic, industrial, and climatic exogenous variables. *Applied Sciences*, 14(13), 5846. https://doi.org/10.3390/app14135846

Siami Namini, S., Tavakoli, N., & Siami Namin, A. (2021). The performance of LSTM and BiLSTM in Forecasting Time Series. In *2019 IEEE International Conference on Big data (Big data)* (pp. 3285-3292). IEEE. https://doi.org/10.1109/BigData47090.2019.9005997

Singgalen, Y. A. (2023). Penerapan CRISP-DM dalam Klasifikasi Sentimen dan Analisis Perilaku Pembelian Layanan Akomodasi Hotel Berbasis Algoritma Decision Tree (DT). *Jurnal Sistem Komputer dan Informatika (JSON)*, 5(2), 237-248. https://doi.org/10.30865/json.v5i2.7081

Sujarwo, Putra, A. N., Setyawan, R. A., Teixeira, H. M., & Khumairoh, U. (2022). Forecasting rice status for a food crisis early warning system based on satellite imagery and cellular automata in Malang, Indonesia. *Sustainability*, 14(15), 1-14.

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes dengan Feature Selection untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. *Jurnal Ilmiah Sains dan Teknologi*, 6(1), 1-10. https://doi.org/10.47080/saintek.v6i1.1467

Sundram, P. (2023). Food security in ASEAN: progress, challenges and future. *Frontiers in Sustainable Food Systems*, 7. https://doi.org/10.3389/fsufs.2023.1260619

Verma, A., Boggavarapu, S., Bharadwaj, A., & Prabakaran, N. (2024). LSTM-based Deep Learning for crop production Prediction with synthetic data. In *Advanced computational methods for agri-Business sustainability* (pp. 273-286). IGI Global. https://doi.org/10.4018/979-8-3693-3583-3.ch015

Tami, M., & Owda, A. Y. (2024). Efficient commodity price forecasting using long short-term memory model. *IAES International Journal of Artificial Intelligence (IJ-AI)*, 13(1), 994-1004. http://doi.org/10.11591/ijai.v13.i1.pp994-1004

Torres, J. F., Hadjout, D., Sebaa, A., Martínez-Álvarez, F., et al. (2021). Deep Learning for Time Series Forecasting: A survey. *Big data*, 9(1), 3-21. https://doi.org/10.1089/big.2020.0159

Wang, X., Li, Y., & Zhang, Z. (2023). LSTM-based wheat production forecasting in China: A regional analysis approach. *Computers and Electronics in Agriculture*, 209, 107856. https://doi.org/10.1016/j.compag.2023.107856

Waqas, M., Naseem, A., Wannasingha, U. H., Hlaing, P. T., Dechpichai, P., & Wangwongchai, A. (2025). Applications of machine learning and deep learning in agriculture: A comprehensive review. *Green Technologies and Sustainability*. https://doi.org/10.1016/j.grets.2025.100199

WFP. (2021). *COVID-19 Impact on Food Security in Indonesia: RAPId Assessment Report*. Jakarta: World Food Programme Indonesia.

Yang, H., Jiao, W., Zouyi, L., Diao, H., & Xia, S. (2025). Artificial intelligence in the food industry: innovations and applications. *Discover Artificial Intelligence*, 5(1). https://doi.org/10.1007/s44163-025-00296-8

Zhang, L., Wang, R., Li, Z., Li, J., Ge, Y., Wa, S., Huang, S., & Lv, C. (2023). Time-Series Neural Network: A High-Accuracy Time-Series Forecasting Method Based on Kernel Filter and Time Attention. *Information*, 14(9), 500. https://doi.org/10.3390/info14090500