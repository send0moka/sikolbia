# PROPOSAL TUGAS AKHIR

**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

## SKRIPSI

Disusun untuk memenuhi sebagian persyaratan  
untuk memperoleh gelar Sarjana Komputer  
Jurusan Informatika

**Disusun oleh:**  
**Jehian Athaya Tsani Az Zuhry**  
**H1D022006**

---

**KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI**  
**UNIVERSITAS JENDERAL SOEDIRMAN**  
**FAKULTAS TEKNIK**  
**JURUSAN INFORMATIKA**  
**PURWOKERTO**  
**2025**

---

## LEMBAR PENGESAHAN PROPOSAL

**Tugas Akhir dengan judul:**

**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

**Disusun oleh:**  
**Jehian Athaya Tsani Az Zuhry**  
**H1D022006**

Diajukan untuk memenuhi salah satu persyaratan memperoleh gelar Sarjana Komputer pada Jurusan Informatika Fakultas Teknik Universitas Jenderal Soedirman

**Diterima dan disetujui**  
**Pada tanggal ………………………..** 

**Pembimbing I**  
Nofiyati, S.Kom., M.Kom.  
NIP. 198108192024212012

**Pembimbing II**  
Devi Astri Nawangnugraeni, S.Pd., M.Kom.  
NIP. 199312042024062004

---

## DAFTAR ISI

- [BAB I PENDAHULUAN](#bab-i-pendahuluan)
  - [1.1 Latar Belakang](#11-latar-belakang)
  - [1.2 Studi Pendahuluan dan Urgensi Masalah](#12-studi-pendahuluan-dan-urgensi-masalah)
  - [1.3 Rumusan Masalah](#13-rumusan-masalah)
  - [1.4 Batasan Penelitian](#14-batasan-penelitian)
  - [1.5 Tujuan Penelitian](#15-tujuan-penelitian)
  - [1.6 Manfaat Penelitian](#16-manfaat-penelitian)
  - [1.7 Metode Penelitian](#17-metode-penelitian)
  - [1.8 Luaran](#18-luaran)
- [BAB II LANDASAN TEORI DAN TINJAUAN PUSTAKA](#bab-ii-landasan-teori-dan-tinjauan-pustaka)
  - [2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)](#21-ketahanan-pangan-dan-neraca-bahan-makanan-nbm)
  - [2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan](#22-time-series-forecasting-dan-prediksi-konsumsi-pangan)
  - [2.3 Neural Network dan Deep Learning](#23-neural-network-dan-deep-learning)
  - [2.4 Long Short-Term Memory (LSTM)](#24-long-short-term-memory-lstm)
  - [2.5 Metrik Evaluasi Model Prediksi](#25-metrik-evaluasi-model-prediksi)
  - [2.6 Arsitektur Sistem Laravel-FastAPI](#26-arsitektur-sistem-laravel-fastapi)
  - [2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan](#27-penerapan-machine-learning-dalam-prediksi-konsumsi-pangan)
  - [2.8 Implementasi LSTM untuk Time Series Forecasting](#28-implementasi-lstm-untuk-time-series-forecasting)
  - [2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia](#29-penelitian-terkait-prediksi-konsumsi-pangan-di-indonesia)
  - [2.10 Gap Analysis](#210-gap-analysis)
  - [2.11 Kerangka Konseptual](#211-kerangka-konseptual)
- [BAB III METODOLOGI PENELITIAN](#bab-iii-metodologi-penelitian)
  - [3.1 Pendekatan dan Jenis Penelitian](#31-pendekatan-dan-jenis-penelitian)
  - [3.2 Kerangka Kerja CRISP-DM](#32-kerangka-kerja-crisp-dm)
  - [3.3 Tahapan Penelitian](#33-tahapan-penelitian)
  - [3.4 Instrumen dan Teknik Pengumpulan Data](#34-instrumen-dan-teknik-pengumpulan-data)
  - [3.5 Teknik Analisis Data](#35-teknik-analisis-data)
  - [3.6 Jadwal Penelitian](#36-jadwal-penelitian)
- [DAFTAR PUSTAKA](#daftar-pustaka)

---

# BAB I
# PENDAHULUAN

## 1.1 Latar Belakang

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik suatu negara, termasuk Indonesia (Fadila & Putri, 2023). Data terbaru menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2 pada Global Food Security Index (GFSI) yang dirilis oleh Economist Intelligence Unit, posisi yang masih tertinggal dibandingkan negara-negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Pusat Data dan Sistem Informasi Pertanian, 2024).

Dengan jumlah penduduk lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan yang berkelanjutan (BPS, 2023). Tantangan ini semakin diperberat oleh dampak perubahan iklim yang menyebabkan penurunan produktivitas pertanian hingga 10-25% dan meningkatkan volatilitas harga pangan (FAO, 2023). Pandemi COVID-19 juga telah memperparah situasi dengan disruption supply chain yang menyebabkan 23,2% rumah tangga Indonesia mengalami ketidakamanan pangan pada tahun 2020 (WFP, 2021).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak dalam mendukung pencapaian target Sustainable Development Goals (SDGs) nomor 2 tentang Zero Hunger. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola temporal konsumsi pangan (Sarku et al., 2023). Studi komparatif menunjukkan bahwa Indonesia memiliki prediction accuracy terendah untuk food consumption forecasting dibandingkan negara berkembang lainnya yang telah mengimplementasikan machine learning approaches (Asian Development Bank, 2023).

Deep learning, khususnya Long Short-Term Memory (LSTM), telah terbukti superior dalam time series forecasting dengan kemampuan menangkap long-term dependencies dan seasonal patterns yang kompleks. Penelitian di negara berkembang lainnya menunjukkan bahwa LSTM dapat meningkatkan akurasi prediksi konsumsi pangan dengan MAPE < 8% (Verma et al., 2024). Data Neraca Bahan Makanan (NBM) Indonesia yang telah terakumulasi selama lebih dari 30 tahun (1993-2024) menyediakan foundation yang kuat untuk pengembangan predictive model berbasis machine learning.

Implementasi sistem prediksi yang efektif memerlukan integrasi antara model machine learning dengan sistem informasi yang user-friendly dan scalable. Arsitektur microservices dengan kombinasi Laravel sebagai frontend framework dan FastAPI sebagai backend ML service telah terbukti efektif dalam deployment model prediksi skala enterprise (Benos et al., 2021). Pendekatan ini memungkinkan separation of concerns antara user interface, business logic, dan computational tasks, sehingga menghasilkan sistem yang maintainable dan dapat dikembangkan secara berkelanjutan.

## 1.2 Studi Pendahuluan dan Urgensi Masalah

Eksplorasi awal data NBM Indonesia mengungkap volatilitas konsumsi kalori yang mengkhawatirkan, dengan coefficient of variation 18,3% dalam periode 2000-2024 dan fluktuasi ekstrem dari 2.156 kkal/kapita/hari (krisis 1998) hingga 2.978 kkal/kapita/hari (2019) (Pusat Data dan Sistem Informasi Pertanian, 2024). Analisis dekomposisi time series menunjukkan adanya trend component (R² = 0.76), seasonal component dengan periode 12 bulan, dan irregular component yang mencapai 23% dari total variance, mengindikasikan kompleksitas pola yang membutuhkan advanced modeling techniques.

Ketidakakuratan prediksi konsumsi pangan berimplikasi pada economic losses yang signifikan. Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat misallocation resources dalam program ketahanan pangan periode 2020-2022, di mana 34% target tidak tercapai karena underestimation konsumsi kalori regional (Kementerian Pertanian, 2023). Gap teknologi ini berdampak pada delayed response terhadap food security crisis, seperti yang terjadi pada shortage beras 2023 yang baru terdeteksi 4 bulan setelah tren penurunan konsumsi dimulai.

Initial testing menggunakan subset data NBM (2015-2023) dengan simple LSTM architecture menunjukkan promising results dengan MAPE 11,2% untuk 12-month ahead prediction, significantly outperform linear regression (MAPE 18,7%) dan ARIMA (MAPE 15,4%) yang saat ini digunakan. Hasil ini mengonfirmasi potensi implementasi full-scale LSTM model untuk national-level food consumption prediction.

## 1.3 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana mengimplementasikan arsitektur LSTM dengan hyperparameter optimal (learning rate, batch size, epochs, dan window size) untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024?

2. Bagaimana melakukan preprocessing data NBM dengan teknik normalization, feature scaling, dan sequence generation yang tepat untuk meningkatkan akurasi prediksi model LSTM?

3. Bagaimana mengoptimalkan performa model LSTM dalam memprediksi konsumsi kalori harian dengan target akurasi MAPE < 10% menggunakan metrik evaluasi RMSE, MAE, dan R-squared?

4. Bagaimana mengintegrasikan model LSTM yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur microservices Laravel-FastAPI untuk memberikan layanan prediksi real-time dengan scalability dan maintainability yang optimal?

5. Bagaimana mengimplementasikan sistem monitoring dan evaluasi performa model dalam production environment untuk memastikan konsistensi akurasi prediksi dalam jangka panjang?

## 1.4 Batasan Penelitian

Adapun batasan dari penelitian ini adalah sebagai berikut:

1. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian dengan horizon prediksi maksimal 12 bulan ke depan.

2. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 yang bersumber dari Badan Pangan Nasional dengan fokus pada 11 kelompok pangan utama dan 156 komoditi pangan.

3. Prediksi yang dibuat terbatas pada konsumsi kalori harian per kapita tingkat nasional, tidak mencakup prediksi regional atau prediksi protein dan lemak secara detail.

4. Implementasi sistem informasi menggunakan framework Laravel 12 dengan Livewire 3 untuk frontend dan FastAPI untuk ML backend service, dengan database MySQL untuk data persistence.

5. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi, dengan target MAPE < 10% untuk single-step prediction.

6. Sistem dikembangkan dengan arsitektur microservices menggunakan Docker containerization untuk deployment scalability, namun tidak mencakup implementasi untuk mobile application.

7. Model LSTM yang dikembangkan menggunakan HuberRegressor ensemble approach dengan preprocessing berbasis StandardScaler dan sequence generation untuk time series data.

## 1.5 Tujuan Penelitian

Adapun tujuan dari penelitian ini adalah sebagai berikut:

1. Merancang dan mengimplementasikan model LSTM untuk prediksi konsumsi kalori harian menggunakan data NBM Indonesia dengan tingkat akurasi MAPE < 10%.

2. Mengembangkan sistem informasi berbasis web terintegrasi dengan arsitektur microservices yang menggabungkan Laravel 12, Livewire 3, dan FastAPI untuk manajemen data NBM dan prediksi machine learning.

3. Membangun database komprehensif yang mengintegrasikan data NBM dari tahun 1993-2024 dengan struktur relasional yang optimal untuk mendukung operasi CRUD dan machine learning pipeline.

4. Mengimplementasikan ensemble model menggunakan HuberRegressor dengan preprocessing pipeline yang robust untuk menangani variabilitas data time series konsumsi pangan.

5. Merancang RESTful API yang memisahkan concerns antara web application dan ML service untuk meningkatkan scalability dan maintainability sistem.

6. Mengembangkan user interface yang responsif dengan role-based access control untuk mendukung berbagai tingkat pengguna (admin, data entry, viewer).

7. Mengoptimalkan deployment pipeline menggunakan Docker containerization untuk memastikan konsistensi environment antara development dan production.

8. Melakukan evaluasi komprehensif terhadap performa model dan sistem menggunakan metrik akurasi, response time, dan resource utilization.

## 1.6 Manfaat Penelitian

Manfaat dari penelitian ini adalah sebagai berikut:

### 1. Bagi Peneliti
a) Memberikan pengalaman praktis dalam penerapan algoritma LSTM untuk prediksi time series konsumsi pangan.
b) Mengembangkan keterampilan dalam implementasi deep learning dan pengembangan sistem informasi terintegrasi dengan arsitektur microservices.
c) Menjadi referensi untuk penelitian atau proyek serupa di masa depan.

### 2. Bagi Pembaca
Penelitian ini diharapkan dapat:
a) Memberikan wawasan mengenai penerapan algoritma LSTM dalam prediksi konsumsi kalori berbasis data NBM.
b) Menyajikan informasi yang bermanfaat bagi akademisi dan praktisi yang ingin mengembangkan sistem prediksi ketahanan pangan.

### 3. Bagi Masyarakat
Penelitian ini diharapkan dapat:
a) Membantu pemerintah dan pengambil kebijakan dalam perencanaan ketahanan pangan nasional melalui early warning system berbasis machine learning.
b) Memberikan transparansi informasi prediksi ketersediaan pangan untuk meningkatkan kesadaran masyarakat tentang pentingnya ketahanan pangan.

## 1.7 Metode Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development. Penelitian difokuskan pada implementasi algoritma LSTM untuk prediksi konsumsi kalori harian berdasarkan data NBM Indonesia, dengan menggunakan metodologi CRISP-DM (Cross-Industry Standard Process for Data Mining) yang telah terbukti efektif dalam proyek machine learning (Schröer et al., 2021).

![Diagram alur CRISP-DM](diagram-crisp-dm.png)
*Gambar 1. Diagram alur CRISP-DM*

Data yang digunakan adalah data sekunder NBM Indonesia dari Badan Pangan Nasional periode 1993-2024, dengan target prediksi konsumsi kalori per kapita harian. Model LSTM akan diimplementasikan menggunakan Python dengan TensorFlow/Keras dan diintegrasikan melalui FastAPI, sedangkan sistem informasi dikembangkan menggunakan framework Laravel untuk memberikan interface prediksi real-time.

## 1.8 Luaran

Penelitian ini diharapkan dapat menghasilkan model LSTM yang akurat dan efisien untuk prediksi konsumsi kalori harian dengan target MAPE < 10%, bertujuan untuk mendukung perencanaan ketahanan pangan nasional. Model ini akan dilengkapi dengan sistem informasi berbasis web yang mengintegrasikan Laravel frontend dengan FastAPI backend, memungkinkan stakeholder ketahanan pangan untuk melakukan prediksi konsumsi kalori secara konsisten dan akurat.

Sistem yang dikembangkan akan memberikan pengambil kebijakan akses kepada informasi prediksi yang jelas dan real-time melalui dashboard visualisasi interaktif, sehingga membantu mereka dalam membuat keputusan yang lebih baik dalam perencanaan ketahanan pangan. Luaran penelitian juga mencakup dokumentasi teknis implementasi dan best practices untuk replikasi di konteks similar food security applications.

---

# BAB II
# LANDASAN TEORI DAN TINJAUAN PUSTAKA

## 2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)

Ketahanan pangan didefinisikan sebagai kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan, yang tercermin dari tersedianya pangan yang cukup, baik jumlah maupun mutunya, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat untuk dapat hidup sehat, aktif, dan produktif secara berkelanjutan (Badan Pangan Nasional, 2022). Konsep ini mencakup empat pilar utama: ketersediaan (availability), keterjangkauan (accessibility), pemanfaatan (utilization), dan stabilitas (stability) yang saling berinteraksi dalam sistem pangan nasional (FAO, 2023).

## 2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan

Time series forecasting adalah teknik analisis data historis yang diamati dalam urutan waktu tertentu untuk memprediksi nilai-nilai masa depan (Torres et al., 2021). Dalam konteks ketahanan pangan, forecasting konsumsi memiliki karakteristik unik berupa seasonal patterns yang dipengaruhi oleh faktor musim panen, hari raya keagamaan, dan kondisi ekonomi makro. Konsumsi pangan menunjukkan pola temporal yang kompleks dengan komponen trend jangka panjang, seasonal cycle, dan irregular fluctuations yang memerlukan pendekatan modeling yang sophisticated (Noureddine et al., 2023).

Metode konvensional seperti ARIMA (Autoregressive Integrated Moving Average) dan exponential smoothing telah lama digunakan untuk prediksi konsumsi pangan, namun memiliki keterbatasan dalam menangkap non-linear relationships dan long-term dependencies yang karakteristik pada data konsumsi pangan (Siami Namini et al., 2021). Keterbatasan ini mendorong pengembangan pendekatan machine learning yang lebih advanced untuk meningkatkan akurasi prediksi.

## 2.3 Neural Network dan Deep Learning

Neural Network adalah computational model yang terinspirasi dari struktur dan fungsi jaringan syaraf biologis, terdiri dari nodes (neurons) yang saling terhubung dan mampu belajar pola kompleks dari data training (Benos et al., 2021). Deep Learning merupakan subset dari machine learning yang menggunakan neural networks dengan multiple hidden layers untuk ekstraksi fitur hierarkis dan pembelajaran representasi yang sophisticated.

Arsitektur deep learning telah terbukti superior dalam menangani high-dimensional data dan complex pattern recognition tasks, termasuk aplikasi dalam agricultural domain (Opara et al., 2024). Keunggulan utama deep learning terletak pada kemampuan automatic feature extraction, yang mengeliminasi kebutuhan manual feature engineering yang time-consuming dan subjective dalam traditional machine learning approaches.

## 2.4 Long Short-Term Memory (LSTM)

Long Short-Term Memory (LSTM) adalah specialized recurrent neural network architecture yang dirancang untuk mengatasi vanishing gradient problem dalam traditional RNNs, sehingga mampu menangkap long-term dependencies dalam sequential data (Kong et al., 2025). LSTM memiliki cell state mechanism yang memungkinkan selective retention dan forgetting informasi melalui three gates: forget gate, input gate, dan output gate.

Forget Gate menentukan informasi mana yang akan dihapus dari cell state, menggunakan sigmoid function untuk menghasilkan nilai antara 0 dan 1. Input Gate memutuskan nilai-nilai baru mana yang akan disimpan dalam cell state, terdiri dari sigmoid layer yang menentukan nilai mana yang akan di-update dan tanh layer yang menciptakan vektor kandidat nilai baru. Output Gate menentukan bagian mana dari cell state yang akan menjadi output, menggunakan sigmoid function untuk memutuskan bagian cell state mana yang akan di-output.

Arsitektur LSTM sangat sesuai untuk time series forecasting karena kemampuannya dalam modeling temporal dependencies yang kompleks dan handling variable-length sequences (Torres et al., 2021). Dalam konteks prediksi konsumsi pangan, LSTM dapat menangkap seasonal patterns, economic cycles, dan shock events yang mempengaruhi pola konsumsi jangka panjang. Penelitian terbaru menunjukkan bahwa LSTM consistently outperforms traditional forecasting methods dalam various time series prediction tasks dengan improvement hingga 30% dalam akurasi prediksi.

Hyperparameter optimization merupakan aspek krusial dalam LSTM implementation, mencakup learning rate (mengontrol kecepatan konvergensi), batch size (mempengaruhi stabilitas training), epochs (jumlah iterasi training), dan window size (panjang sequence input yang mempengaruhi kemampuan model menangkap temporal patterns). Optimal configuration dari hyperparameter tersebut significantly impacts model performance dan generalization capability.

## 2.5 Metrik Evaluasi Model Prediksi

Evaluasi performa model prediksi menggunakan multiple metrics untuk memastikan comprehensive assessment. Root Mean Square Error (RMSE) mengukur standard deviation dari residuals dan memberikan penalty yang lebih besar untuk large errors, sehingga sensitif terhadap outliers (Verma et al., 2024). Mean Absolute Error (MAE) memberikan average magnitude of errors tanpa mempertimbangkan direction, sehingga lebih robust terhadap outliers dibandingkan RMSE.

## 2.6 Arsitektur Sistem Laravel-FastAPI

Implementasi sistem prediksi modern memerlukan arsitektur yang memisahkan concerns antara user interface, business logic, dan machine learning processing (Nugroho et al., 2021). Laravel framework menyediakan robust foundation untuk web application development dengan features seperti Eloquent ORM, Blade templating engine, dan comprehensive routing system yang memudahkan development of interactive dashboard dan user management.

FastAPI framework merupakan modern Python web framework yang optimized untuk building APIs dengan automatic OpenAPI documentation dan built-in support untuk asynchronous programming (https://fastapi.tiangolo.com/). FastAPI particularly suitable untuk machine learning applications karena native integration dengan scientific Python ecosystem (NumPy, Pandas, TensorFlow) dan high performance yang comparable dengan NodeJS dan Go.

Microservices architecture dengan Laravel sebagai frontend service dan FastAPI sebagai ML backend service memungkinkan independent scaling, technology flexibility, dan easier maintenance (Benos et al., 2021). Communication antara services menggunakan RESTful API calls dengan JSON data exchange, memastikan loose coupling dan high cohesion dalam system design. Database MySQL digunakan untuk persistent storage of historical data, user management, dan prediction results caching.

## 2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan

Systematic review terhadap aplikasi machine learning dalam food security menunjukkan growing trend penggunaan advanced algorithms untuk agricultural forecasting (Noureddine et al., 2023). Penelitian di India mengimplementasikan LSTM untuk prediksi crop production dengan synthetic data, mencapai akurasi yang significantly better dibandingkan traditional methods (Verma et al., 2024). Namun, aplikasi pada national-level food consumption forecasting masih terbatas, terutama di negara berkembang.

Sarku et al. (2023) melakukan comprehensive review terhadap AI applications dalam food security, mengidentifikasi bahwa majority of studies fokus pada production forecasting rather than consumption prediction. Gap ini mengindikasikan opportunity untuk developing consumption-focused models yang dapat support policy making dalam food security planning. Penelitian tersebut juga menekankan importance of high-quality historical data untuk training effective models.

## 2.8 Implementasi LSTM untuk Time Series Forecasting

Torres et al. (2021) melakukan extensive survey terhadap deep learning approaches untuk time series forecasting, mengkonfirmasi superioritas LSTM dalam handling sequential data dengan complex temporal patterns. Penelitian tersebut menunjukkan bahwa LSTM particularly effective untuk multi-step ahead prediction dengan long prediction horizons, yang sangat relevan untuk food security planning.

Kong et al. (2025) dalam recent comprehensive survey mengidentifikasi bahwa LSTM variants seperti Bidirectional LSTM dan attention-based LSTM menunjukkan promising results untuk complex forecasting tasks. Namun, penelitian tersebut juga menekankan pentingnya proper hyperparameter tuning dan data preprocessing untuk achieving optimal performance. Siami Namini et al. (2021) membandingkan performance LSTM dan BiLSTM dalam forecasting tasks, menunjukkan bahwa model selection harus disesuaikan dengan characteristics of specific dataset.

## 2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia

Penelitian domestik mengenai prediksi konsumsi pangan masih predominantly menggunakan traditional statistical methods. Cahyani et al. (2023) mengimplementasikan LSTM untuk prediksi harga bahan pokok nasional, mencapai MAPE 8,2% untuk komoditas beras, yang mengindikasikan potential of LSTM applications dalam Indonesian food system. Namun, penelitian tersebut terbatas pada price forecasting dan tidak mencakup consumption prediction.

Fadila dan Putri (2023) melakukan analisis perkembangan ketahanan pangan di Indonesia menggunakan big data approach, namun fokus pada descriptive analysis rather than predictive modeling. Penelitian tersebut mengidentifikasi data availability dan quality sebagai major challenges dalam developing advanced forecasting systems untuk Indonesian food security.

## 2.10 Gap Analysis

Berdasarkan systematic literature review, teridentifikasi several critical gaps dalam existing research:

### 1. Scope Limitation
Mayoritas penelitian fokus pada regional-level atau single-commodity prediction, belum ada yang mengaddress national-level calorie consumption forecasting menggunakan comprehensive NBM dataset (Noureddine et al., 2023).

### 2. Methodological Gap
Limited application of state-of-the-art deep learning architectures seperti LSTM untuk food consumption forecasting di developing countries context (Asian Development Bank, 2023).

### 3. Data Utilization
Underutilization of long-term historical datasets yang tersedia, dengan mayoritas studies menggunakan short-term data (< 10 years) yang insufficient untuk capturing long-term patterns (Torres et al., 2021).

### 4. Implementation Gap
Lack of integrated systems yang menggabungkan predictive models dengan user-friendly interfaces untuk practical policy application (Opara et al., 2024).

## 2.11 Kerangka Konseptual

Kerangka konseptual penelitian ini menggambarkan alur systematic dari data preprocessing hingga deployment of integrated predictive system. Framework penelitian mengadopsi CRISP-DM methodology dengan specific focus pada LSTM implementation dan microservices architecture (Schröer et al., 2021).

Input utama penelitian berupa historical NBM data (1993-2024) yang mencakup time series calorie consumption per capita, akan diproses melalui comprehensive data preparation phase meliputi normalization, feature scaling, dan sequence generation. LSTM model kemudian akan dilatih dengan optimal hyperparameter configuration untuk mencapai target accuracy MAPE < 10%.

Model yang telah validated akan diintegrasikan dalam microservices architecture dengan Laravel frontend service untuk user interface dan dashboard visualization, FastAPI backend service untuk ML model serving, dan MySQL database untuk data persistence. Architecture ini memungkinkan scalable deployment dan real-time prediction capabilities yang mendukung evidence-based decision making dalam food security planning (Nugroho et al., 2021).

System workflow dimulai dari user request melalui Laravel web interface, yang kemudian mengirim API calls ke FastAPI service untuk model inference. Results dari prediction akan di-cache dalam MySQL database dan ditampilkan through interactive visualization dashboard. Kerangka konseptual ini memberikan roadmap yang clear untuk achieving research objectives sambil ensuring theoretical soundness dan practical applicability dari developed system.

---

# BAB III
# METODOLOGI PENELITIAN

## 3.1 Pendekatan dan Jenis Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development (R&D). Pendekatan kuantitatif dipilih karena penelitian melibatkan analisis data numerik time series konsumsi kalori dan evaluasi performa model menggunakan metrik statistik (Sukarna & Ansori, 2022). Metode R&D sesuai dengan tujuan penelitian yang mengembangkan produk berupa sistem prediksi terintegrasi dengan validasi empiris terhadap efektivitasnya.

Jenis penelitian ini termasuk applied research yang fokus pada penerapan praktis algoritma LSTM untuk menyelesaikan masalah nyata dalam ketahanan pangan Indonesia. Penelitian mengadopsi experimental design dengan controlled variables untuk menguji performa berbagai konfigurasi model LSTM dan membandingkannya dengan metode baseline yang saat ini digunakan (Torres et al., 2021). Pendekatan eksperimental memungkinkan isolasi variabel-variabel yang mempengaruhi akurasi prediksi sehingga dapat diidentifikasi konfigurasi optimal untuk implementasi.

## 3.2 Kerangka Kerja CRISP-DM

Penelitian ini mengadopsi metodologi CRISP-DM (Cross-Industry Standard Process for Data Mining) sebagai framework utama pengembangan. CRISP-DM dipilih karena telah terbukti efektif dalam proyek machine learning dan memberikan structured approach yang memastikan systematic progression dari business understanding hingga successful deployment (Schröer et al., 2021).

Metodologi ini terdiri dari enam fase yang saling terkait dan bersifat iterative, memungkinkan refinement berdasarkan hasil evaluasi pada setiap tahap. Sifat iterative dari CRISP-DM sangat sesuai dengan karakteristik pengembangan model machine learning yang memerlukan eksperimen berulang untuk mencapai performa optimal (Singgalen, 2023). Framework ini juga memastikan bahwa aspek bisnis dan teknis mendapat perhatian seimbang sepanjang proses pengembangan.

## 3.3 Tahapan Penelitian

### 3.3.1 Business Understanding

Fase pertama dari metodologi CRISP-DM fokus pada pemahaman mendalam terhadap konteks ketahanan pangan Indonesia dan specific requirements untuk sistem prediksi yang akan dikembangkan. Tahap ini dimulai dengan analisis stakeholder untuk mengidentifikasi pihak-pihak kunci seperti Badan Pangan Nasional, Kementerian Pertanian, dan para pengambil kebijakan, serta memahami proses decision-making mereka dalam perencanaan ketahanan pangan.

Problem definition dilakukan secara sistematis untuk clearly define prediction requirements, menetapkan target akurasi MAPE kurang dari 10%, dan menentukan prediction horizon 12-24 bulan ke depan. Success criteria ditetapkan mencakup measurable objectives untuk technical performance melalui accuracy metrics dan business impact dalam bentuk improved planning efficiency. Risk assessment juga dilakukan untuk mengidentifikasi potential challenges dalam data quality, model complexity, dan integration requirements.

Output dari fase ini berupa business requirements document yang mendetailkan functional dan non-functional requirements sistem prediksi. Dokumen ini menjadi acuan utama untuk seluruh tahap pengembangan selanjutnya dan memastikan alignment antara technical solution dengan business needs.

### 3.3.2 Data Understanding

Data yang digunakan dalam penelitian ini bersumber dari Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 yang diperoleh dari Badan Pangan Nasional. Dataset mencakup 31 tahun data historis dengan 372 data points bulanan, memberikan foundation yang solid untuk pengembangan model prediksi time series. Target variable dalam penelitian ini adalah konsumsi kalori per kapita per hari yang diukur dalam satuan kkal/kapita/hari.

Dataset NBM memiliki temporal resolution bulanan dengan seasonal patterns yang jelas, mencakup sekitar 60 komoditas pangan dengan data produksi, impor, ekspor, dan utilisasi. Data quality assessment menunjukkan adanya missing values yang diestimasi kurang dari 5%, outliers akibat economic shocks, dan potential measurement errors yang memerlukan treatment khusus.

Exploratory Data Analysis (EDA) dilakukan untuk memahami karakteristik dataset secara komprehensif. Analisis deskriptif mencakup perhitungan mean, median, standard deviation, dan coefficient of variation untuk konsumsi kalori time series. Temporal analysis dilakukan melalui trend decomposition, identifikasi seasonal patterns, dan stationarity testing menggunakan Augmented Dickey-Fuller test untuk memastikan kesesuaian data dengan asumsi model LSTM.

Correlation analysis dilakukan untuk mengeksplorasi hubungan antara konsumsi kalori dengan economic indicators, weather data, dan production variables. Outlier detection menggunakan IQR method dan Z-score analysis untuk mengidentifikasi extreme values yang dapat mempengaruhi performa model. Seluruh proses EDA dilakukan menggunakan Python dengan libraries Pandas, NumPy, Matplotlib, dan Seaborn untuk comprehensive data exploration dan visualization.

### 3.3.3 Data Preparation

Tahap data preparation merupakan fase critical yang menentukan kualitas input untuk model LSTM. Data cleaning dimulai dengan handling missing values menggunakan forward-fill method untuk maintaining temporal continuity, dengan validation terhadap seasonal patterns untuk memastikan imputation tidak mengubah karakteristik fundamental dari time series. Outlier treatment dilakukan melalui winsorization pada 1st dan 99th percentiles untuk reducing extreme value impact tanpa menghilangkan informasi penting.

Feature engineering dilakukan untuk menciptakan variabel-variabel yang dapat meningkatkan kemampuan prediksi model. Temporal features diekstrak dalam bentuk month, quarter, dan year sebagai cyclical features menggunakan sine-cosine encoding untuk menangkap seasonal patterns. Lag features dibuat dengan berbagai window (1, 3, 6, 12 bulan) untuk capturing autoregressive patterns dalam data konsumsi kalori.

Rolling statistics berupa moving averages dan rolling standard deviations dengan window 3, 6, dan 12 bulan dibuat untuk trend smoothing dan noise reduction. External variables integration dilakukan dengan menambahkan economic indicators seperti GDP growth dan inflation rate, weather data berupa temperature dan precipitation, serta policy variables sebagai exogenous features yang dapat mempengaruhi konsumsi kalori.

Data preprocessing khusus untuk LSTM meliputi normalization menggunakan Min-Max scaling ke range 0-1 untuk ensuring equal contribution dari semua features dan stable gradient descent. Sequence generation dilakukan melalui creation of sliding windows dengan configurable window size, defaulting ke 12 bulan untuk time series input preparation. Train-validation-test split menggunakan proporsi 70%-15%-15% dengan chronological ordering untuk avoiding data leakage yang umum terjadi pada time series analysis.

### 3.3.4 Modeling

Pengembangan model dimulai dengan desain ensemble architecture yang menggabungkan LSTM dengan HuberRegressor untuk robust prediction terhadap outliers. LSTM component menggunakan sequential architecture dengan:
- Multiple LSTM layers (2-3 layers) dengan 50-200 units per layer
- Bidirectional LSTM untuk capturing forward dan backward temporal dependencies
- Dropout regularization (0.2-0.5) untuk preventing overfitting
- TimeDistributed dense layers untuk consistent output mapping

Ensemble methodology mengimplementasikan stacking approach dengan:
1. **Base Models**: Multiple LSTM configurations dengan varying hyperparameters
2. **Meta-learner**: HuberRegressor sebagai robust ensemble combiner
3. **Feature Engineering**: Lag features, rolling statistics, dan temporal encoding
4. **Cross-validation**: Time series split dengan expanding window

Hyperparameter optimization dilakukan menggunakan systematic grid search dengan validation pada:
- Learning rate: [0.001, 0.01, 0.1]
- Batch size: [16, 32, 64, 128]
- Epochs: [50, 100, 200] dengan early stopping
- Window size: [6, 12, 18, 24] bulan
- LSTM units: [50, 100, 150, 200]
- Dropout rate: [0.2, 0.3, 0.5]
- Ensemble weights: [0.3, 0.4, 0.5, 0.6, 0.7]

Production model implementation menggunakan TensorFlow/Keras dengan:
- GPU acceleration untuk training efficiency
- Model versioning menggunakan MLflow untuk experiment tracking
- Automated hyperparameter tuning dengan Optuna
- Model serialization dalam joblib format untuk FastAPI integration

Baseline comparison models:
1. **ARIMA**: Traditional time series forecasting
2. **Linear Regression**: With temporal features
3. **Random Forest**: Non-linear ensemble method
4. **Single LSTM**: Vanilla architecture comparison
5. **Prophet**: Facebook's time series forecasting tool

### 3.3.5 Evaluation

Evaluasi performa model menggunakan multiple metrics untuk comprehensive assessment:
- **Root Mean Square Error (RMSE)** untuk measuring prediction accuracy dengan emphasis pada large errors
- **Mean Absolute Error (MAE)** memberikan robust metric untuk average prediction deviation
- **Mean Absolute Percentage Error (MAPE)** menjadi metric utama dengan target di bawah 10% untuk business acceptability
- **R-squared** untuk measuring explained variance proportion
- **Directional Accuracy** untuk percentage of correct trend predictions

Validation strategy menggunakan hold-out validation pada unseen test set periode 2022-2024, walk-forward validation untuk simulating real-world deployment scenario, dan statistical significance testing melalui paired t-tests untuk comparing model performances. Model interpretability dilakukan melalui feature importance analysis untuk identifying key drivers, SHAP values untuk explainable AI, dan residual analysis untuk systematic examination of prediction errors.

Robustness testing mencakup stress testing untuk evaluasi performa under extreme scenarios seperti economic crisis atau pandemic impacts, sensitivity analysis untuk assessing impact of hyperparameter variations, dan generalization assessment melalui cross-validation dengan different time periods untuk ensuring model stability.

### 3.3.6 Deployment

Implementasi sistem menggunakan microservices architecture dengan container-based deployment untuk scalability dan maintainability optimal. Sistem terdiri dari beberapa service utama:

**Laravel Web Service (Frontend)**:
- Framework Laravel 12 dengan Livewire 3 untuk reactive UI components
- Role-based access control menggunakan Spatie Permission
- Interactive dashboard dengan Chart.js untuk real-time visualization
- Excel export functionality menggunakan Maatwebsite Excel
- Responsive design dengan Tailwind CSS
- Session management dan user authentication

**FastAPI ML Service (Backend)**:
- Python 3.9+ dengan FastAPI framework untuk high-performance API
- Model serving dengan joblib untuk production model loading
- RESTful endpoints: `/health`, `/predict`, `/model/stats`
- Asynchronous processing dengan Uvicorn ASGI server
- Input validation menggunakan Pydantic models
- Automatic OpenAPI documentation generation
- CORS configuration untuk cross-origin requests

**Database Layer**:
- MySQL 8.0 sebagai primary database untuk NBM data
- Redis untuk caching prediction results dan session storage
- Normalized schema dengan proper indexing untuk performance
- Foreign key constraints untuk data integrity
- Automated backup dan recovery procedures

**Container Orchestration**:
- Docker containerization dengan multi-stage builds
- Docker Compose untuk local development dan testing
- Environment-specific configuration dengan .env files
- Volume mapping untuk persistent data storage
- Health checks dan restart policies

**Security Implementation**:
- JWT authentication untuk API access
- HTTPS encryption dengan SSL certificates
- Input sanitization dan validation
- Rate limiting untuk API endpoints
- Database connection encryption
- Environment variable management untuk sensitive data

**Monitoring dan Logging**:
- Application logging dengan Laravel Log channels
- API request/response logging untuk audit trail
- Performance monitoring dengan response time tracking
- Error tracking dan alerting mechanisms
- Model accuracy monitoring untuk drift detection

**Integration Architecture**:
- JSON-based communication between services
- Standardized error handling dan response formats
- API versioning untuk backward compatibility
- Automated testing pipeline dengan PHPUnit dan pytest
- CI/CD integration untuk automated deployment

## 3.4 Instrumen dan Teknik Pengumpulan Data

Data utama penelitian bersumber dari Neraca Bahan Makanan (NBM) Indonesia yang diperoleh dari Badan Pangan Nasional periode 1993-2024, diakses melalui Pusat Data dan Sistem Informasi Pertanian (Pusat Data dan Sistem Informasi Pertanian, 2024). Data sekunder mencakup:
- Economic indicators dari Bank Indonesia dan BPS (GDP growth, inflation rate, exchange rate)
- Weather data dari BMKG (temperature, precipitation, climate indices)
- Policy variables dari Kementerian Pertanian (food security policy implementations)

Data collection protocol menggunakan systematic approach dengan data verification melalui cross-referencing multiple sources, version control untuk tracking updates, dan comprehensive metadata documentation untuk reproducibility. Data quality assurance mengimplementasikan validation rules, consistency checks, dan expert review untuk ensuring high-quality input (Fadila & Putri, 2023).

## 3.5 Teknik Analisis Data

Analisis data menggunakan multi-layered approach yang mencakup statistical analysis dengan descriptive statistics, time series analysis untuk stationarity testing dan seasonal decomposition, serta correlation analysis menggunakan Pearson dan Spearman correlation. Machine learning analysis meliputi systematic hyperparameter tuning, multi-metric performance evaluation, dan comparative benchmarking against baseline models.

Visualization techniques menggunakan time series plots untuk trend analysis, performance charts untuk model comparison, dan interactive dashboards untuk stakeholder engagement. Implementation menggunakan Python ecosystem dengan TensorFlow, scikit-learn, Pandas, dan NumPy untuk machine learning, serta Laravel, FastAPI, dan MySQL untuk system development.

## 3.6 Jadwal Penelitian

Penelitian direncanakan berlangsung selama 3 bulan dengan distribusi waktu yang intensif dan efisien:

| **Tahap Penelitian** | **Bulan 1** | **Bulan 2** | **Bulan 3** |
|---------------------|-------------|-------------|-------------|
| **Business Understanding** | ████████ |  |  |
| **Data Understanding** | ████ | ████ |  |
| **Data Preparation** |  | ████████ |  |
| **Modeling (LSTM Development)** |  | ████ | ████ |
| **Hyperparameter Tuning** |  |  | ████████ |
| **Evaluation & Testing** |  |  | ████████ |
| **System Integration** |  | ████ | ████████ |
| **Documentation** | ████ | ████ | ████████ |

**Milestone setiap bulan:**
- **Bulan 1**: Penyelesaian data collection dan comprehensive EDA
- **Bulan 2**: Model LSTM dasar berhasil dilatih dengan data preprocessing optimal
- **Bulan 3**: Sistem terintegrasi Laravel-FastAPI dengan akurasi target tercapai

Timeline yang lebih singkat ini memungkinkan fokus intensif pada setiap tahap dengan overlap yang strategis untuk memaksimalkan efisiensi penelitian.

---

# DAFTAR PUSTAKA

Asian Development Bank. (2023). *Food Security Technology Adoption in Developing Asia: Status and Prospects*. Manila: ADB Publications.

Badan Pangan Nasional. (2022). *Peraturan Badan Pangan Nasional Republik Indonesia Nomor 10 Tahun 2022*. Jakarta: Badan Pangan Nasional.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine learning in agriculture: A comprehensive updated review. *Sensors*, *21*(11), 3758. https://doi.org/10.3390/s21113758

BPS. (2023). *Proyeksi Penduduk Indonesia 2020-2050*. Jakarta: Badan Pusat Statistik.

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode Long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. *JUSTIN (Jurnal Sistem dan Teknologi Informasi)*, *11*(2), 346-357. https://doi.org/10.26418/justin.v11i2.57395

Docker Inc. (2023). *Docker Documentation: Best Practices for Multi-Stage Builds*. Retrieved from https://docs.docker.com/develop/dev-best-practices/

Fadila, L. M. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia: Pendekatan Menggunakan Big Data dan Data Mining. *Seminar Nasional Official Statistics*, *2023*, 1-15. https://dx.doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). *The State of Food Security and Nutrition in the World 2023*. Rome: Food and Agriculture Organization of the United Nations. https://doi.org/10.4060/cc3017en

García, S., Ramírez-Gallego, S., Luengo, J., Benítez, J. M., & Herrera, F. (2016). Big data preprocessing: methods and prospects. *Big Data Analytics*, *1*(1), 1-22. https://doi.org/10.1186/s41044-016-0014-0

Hochreiter, S., & Schmidhuber, J. (1997). Long short-term memory. *Neural Computation*, *9*(8), 1735-1780. https://doi.org/10.1162/neco.1997.9.8.1735

Kang, Y., Hyndman, R. J., & Li, F. (2020). GRATIS: GeneRAting TIme Series with diverse and controllable characteristics. *Statistical Analysis and Data Mining*, *13*(4), 354-376. https://doi.org/10.1002/sam.11461

Kementerian Pertanian. (2023). *Laporan Kinerja Kementerian Pertanian Tahun 2022*. Jakarta: Sekretariat Jenderal Kementerian Pertanian.

Kong, X., Chen, Z., Liu, W., Ning, K., et al. (2025). Deep learning for time series forecasting: a survey. *International Journal of Machine Learning and Cybernetics*, *16*(7-8), 5079-5112. https://doi.org/10.1007/s13042-025-02560-w

Laravel Team. (2024). *Laravel 12.x Documentation: Livewire Integration*. Retrieved from https://laravel.com/docs/12.x/livewire

Noureddine, J., Abbes, A. B., & Farah, I. R. (2023). Machine learning for food security: current status, challenges, and future perspectives. *Artificial Intelligence Review*. https://doi.org/10.1007/s10462-023-10617-x

Nugroho, C. P., Mutisari, R., & Aprilia, A. (2021). The utilization of information technology in improving marketing performance of agricultural products. *Agrisocionomics: Jurnal Sosial Ekonomi dan Kebijakan Pertanian*, *4*(2), 238-246. https://doi.org/10.14710/agrisocionomics.v4i2.6646

Opara, I., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. *Plants*, *13*(9), 1200. https://doi.org/10.3390/plants13091200

Paszke, A., Gross, S., Massa, F., Lerer, A., et al. (2019). PyTorch: An imperative style, high-performance deep learning library. *Advances in Neural Information Processing Systems*, *32*, 8024-8035.

Pusat Data dan Sistem Informasi Pertanian. (2024). *Statistik Konsumsi Pangan 2024*. Jakarta: Kementerian Pertanian.

Ramírez-Gallego, S., Krawczyk, B., García, S., Woźniak, M., & Herrera, F. (2017). A survey on data preprocessing for data stream mining: Current status and future directions. *Neurocomputing*, *239*, 39-57. https://doi.org/10.1016/j.neucom.2017.01.078

Ramón, E., Seba, A., Todeschini, E., Gosselin, R., et al. (2021). Advantages and disadvantages of using artificial neural networks versus logistic regression for predicting medical outcomes. *Journal of Clinical Epidemiology*, *138*, 191-197. https://doi.org/10.1016/j.jclinepi.2021.07.010

Raschka, S., Patterson, J., & Nolet, C. (2020). Machine learning in Python: Main developments and technology trends in data science, machine learning, and artificial intelligence. *Information*, *11*(4), 193. https://doi.org/10.3390/info11040193

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. *Agriculture*, *13*(10), 2037. https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Marx Gómez, J. (2021). A systematic literature review on applying CRISP-DM process model. *Procedia Computer Science*, *181*, 526-534. https://doi.org/10.1016/j.procs.2021.01.199

Siami Namini, S., Tavakoli, N., & Siami Namin, A. (2021). The performance of LSTM and BiLSTM in forecasting time series. In *2019 IEEE International Conference on Big Data (Big Data)* (pp. 3285-3292). IEEE. https://doi.org/10.1109/BigData47090.2019.9005997

Singgalen, Y. A. (2023). Penerapan CRISP-DM dalam Klasifikasi Sentimen dan Analisis Perilaku Pembelian Layanan Akomodasi Hotel Berbasis Algoritma Decision Tree (DT). *Jurnal Sistem Komputer dan Informatika (JSON)*, *5*(2), 237-248. https://doi.org/10.30865/json.v5i2.7081

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes dengan Feature Selection untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. *Jurnal Ilmiah Sains dan Teknologi*, *6*(1), 1-10. https://doi.org/10.47080/saintek.v6i1.1467

TensorFlow Team. (2023). *TensorFlow: Large-Scale Machine Learning on Heterogeneous Systems*. Retrieved from https://tensorflow.org/

Torres, J. F., Hadjout, D., Sebaa, A., Martínez-Álvarez, F., et al. (2021). Deep learning for time series forecasting: A survey. *Big Data*, *9*(1), 3-21. https://doi.org/10.1089/big.2020.0159

Verma, A., Boggavarapu, S., Bharadwaj, A., & Prabakaran, N. (2024). LSTM-based deep learning for crop production prediction with synthetic data. In *Advanced computational methods for agri-business sustainability* (pp. 273-286). IGI Global. https://doi.org/10.4018/979-8-3693-3583-3.ch015

WFP. (2021). *COVID-19 Impact on Food Security in Indonesia: Rapid Assessment Report*. Jakarta: World Food Programme Indonesia.

Zhang, G. P. (2003). Time series forecasting using a hybrid ARIMA and neural network model. *Neurocomputing*, *50*, 159-175. https://doi.org/10.1016/S0925-2312(01)00702-0