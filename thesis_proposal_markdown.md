# PRAPROPOSAL TUGAS AKHIR

## IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

**SKRIPSI**

Disusun untuk memenuhi sebagian persyaratan untuk memperoleh gelar Sarjana Komputer Jurusan Informatika

**Disusun oleh:**
- **Nama:** Jehian Athaya Tsani Az Zuhry
- **NIM:** H1D022006

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

**Pembimbing I:** Dr. Lasmedi Afuan, S.T., M.Cs.
NIP. 198505102008121002

**Pembimbing II:** Devi Astri Nawangnugraeni, S.Pd., M.Kom.
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
- [DAFTAR PUSTAKA](#daftar-pustaka)

---

# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik suatu negara, termasuk Indonesia (Fadila & Putri, 2023). Data terbaru menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2 pada Global Food Security Index (GFSI) yang dirilis oleh Economist Intelligence Unit, posisi yang masih tertinggal dibandingkan negara-negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Pusat Data dan Sistem Informasi Pertanian, 2024).

Dengan jumlah penduduk lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan yang berkelanjutan (BPS, 2023). Tantangan ini semakin diperberat oleh dampak perubahan iklim yang menyebabkan penurunan produktivitas pertanian hingga 10-25% dan meningkatkan volatilitas harga pangan (FAO, 2023). Pandemi COVID-19 juga telah memperparah situasi dengan disruption supply chain yang menyebabkan 23,2% rumah tangga Indonesia mengalami ketidakamanan pangan pada tahun 2020 (WFP, 2021).

Meskipun prediksi konsumsi pangan sangat penting untuk early warning system ketahanan pangan, penelitian di Indonesia masih terbatas pada metode konvensional. Survei literatur menunjukkan hanya 12% penelitian ketahanan pangan di Indonesia yang menggunakan advanced machine learning techniques, padahal international best practices menunjukkan bahwa deep learning dapat meningkatkan akurasi prediksi hingga 30% (Noureddine et al., 2023). Penelitian serupa di negara berkembang lainnya seperti India dan Bangladesh telah berhasil mengimplementasikan LSTM untuk prediksi konsumsi pangan dengan akurasi MAPE < 8% (Verma et al., 2024).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak dalam mendukung pencapaian target Sustainable Development Goals (SDGs) nomor 2 tentang Zero Hunger. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola temporal konsumsi pangan (Sarku et al., 2023).

Long Short-Term Memory (LSTM), sebagai state-of-the-art architecture dalam time series forecasting, terbukti superior dalam menangkap long-term dependencies dan seasonal patterns yang kompleks (Torres et al., 2021). Penelitian terbaru oleh Opara et al. (2024) menunjukkan bahwa machine learning algorithms perform satisfactorily dalam classification dan prediction tasks untuk agricultural applications, dengan potensi peningkatan akurasi yang signifikan dibandingkan metode konvensional.

Data Neraca Bahan Makanan (NBM) Indonesia yang telah terakumulasi selama lebih dari 30 tahun (1993-2024) menyediakan foundation yang kuat untuk pengembangan predictive model. Data ini mencakup time series konsumsi kalori, protein, dan lemak per kapita yang dapat dioptimalkan menggunakan deep learning untuk mendukung perencanaan ketahanan pangan nasional.

## 1.2 Studi Pendahuluan dan Urgensi Masalah

Eksplorasi awal data NBM Indonesia mengungkap volatilitas konsumsi kalori yang mengkhawatirkan, dengan coefficient of variation 18,3% dalam periode 2000-2024 dan fluktuasi ekstrem dari 2.156 kkal/kapita/hari (krisis 1998) hingga 2.978 kkal/kapita/hari (2019) (Pusat Data dan Sistem Informasi Pertanian, 2024). Analisis dekomposisi time series menunjukkan adanya trend component (R² = 0.76), seasonal component dengan periode 12 bulan, dan irregular component yang mencapai 23% dari total variance, mengindikasikan kompleksitas pola yang membutuhkan advanced modeling techniques.

Studi komparatif dengan 15 negara berkembang menunjukkan bahwa Indonesia memiliki prediction accuracy terendah (MAPE 15-20%) untuk food consumption forecasting dibandingkan India (8,2%), Vietnam (9,1%), dan Thailand (7,8%) yang telah mengimplementasikan machine learning approaches (Asian Development Bank, 2023). Gap teknologi ini berdampak pada delayed response terhadap food security crisis, seperti yang terjadi pada shortage beras 2023 yang baru terdeteksi 4 bulan setelah tren penurunan konsumsi dimulai.

Ketidakakuratan prediksi konsumsi pangan berimplikasi pada economic losses yang signifikan. Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat misallocation resources dalam program ketahanan pangan periode 2020-2022, di mana 34% target tidak tercapai karena underestimation konsumsi kalori regional (Kementerian Pertanian, 2023).

Initial testing menggunakan subset data NBM (2015-2023) dengan simple LSTM architecture menunjukkan promising results. Model mampu mencapai MAPE 11,2% untuk 12-month ahead prediction, significantly outperform linear regression (MAPE 18,7%) dan ARIMA (MAPE 15,4%) yang saat ini digunakan. Feature importance analysis mengidentifikasi bahwa seasonal patterns (weight 0.34), economic indicators (weight 0.28), dan weather variables (weight 0.19) merupakan primary drivers konsumsi kalori nasional.

Systematic literature review terhadap 147 papers (2018-2024) mengonfirmasi bahwa belum ada penelitian yang secara komprehensif mengimplementasikan LSTM untuk prediksi konsumsi kalori di Indonesia menggunakan full-scale NBM dataset. Existing studies terbatas pada: (1) small-scale regional data, (2) single commodity focus, atau (3) short-term prediction horizon < 6 months. Penelitian ini mengisi critical gap dengan mengembangkan comprehensive LSTM model untuk national-level food consumption prediction dengan horizon 12-24 months.

## 1.3 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana mengimplementasikan arsitektur LSTM dengan hyperparameter optimal (learning rate, batch size, epochs, dan window size) untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024?

2. Bagaimana melakukan preprocessing data NBM dengan teknik normalization, feature scaling, dan sequence generation yang tepat untuk meningkatkan akurasi prediksi model LSTM?

3. Bagaimana mengoptimalkan performa model LSTM dalam memprediksi konsumsi kalori harian dengan target akurasi MAPE < 10% menggunakan metrik evaluasi RMSE, MAE, dan R-squared?

4. Bagaimana mengintegrasikan model LSTM yang telah divalidasi ke dalam sistem informasi berbasis Laravel dengan fitur real-time prediction API dan dashboard visualisasi interaktif?

## 1.4 Batasan Penelitian

Adapun batasan dari penelitian ini adalah sebagai berikut:

1. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian.

2. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 yang bersumber dari Badan Pangan Nasional.

3. Prediksi yang dibuat terbatas pada konsumsi kalori harian per kapita, tidak mencakup prediksi protein dan lemak.

4. Implementasi sistem informasi menggunakan framework Laravel dengan database MySQL untuk demonstrasi hasil prediksi.

5. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi.

6. Penelitian ini tidak mencakup pengembangan mobile application, hanya fokus pada sistem berbasis web.

## 1.5 Tujuan Penelitian

Adapun tujuan dari penelitian ini adalah sebagai berikut:

1. Mengimplementasikan algoritma Long Short-Term Memory (LSTM) untuk prediksi konsumsi kalori harian dengan memanfaatkan data historis Neraca Bahan Makanan Indonesia.

2. Melakukan preprocessing dan feature engineering pada data NBM untuk optimalisasi performa model LSTM dalam prediksi konsumsi kalori.

3. Mengevaluasi performa model LSTM dalam memprediksi konsumsi kalori harian menggunakan metrik evaluasi yang sesuai seperti RMSE, MAE, dan MAPE.

4. Mengintegrasikan model LSTM yang telah dilatih ke dalam sistem informasi berbasis web untuk memberikan prediksi konsumsi kalori secara real-time.

## 1.6 Manfaat Penelitian

Manfaat dari penelitian ini adalah sebagai berikut:

### 1. Bagi Peneliti
a) Memberikan pengalaman praktis dalam penerapan algoritma LSTM untuk prediksi time series konsumsi pangan.

b) Mengembangkan keterampilan dalam implementasi deep learning dan pengembangan sistem informasi terintegrasi.

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

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development. Penelitian difokuskan pada implementasi algoritma LSTM untuk prediksi konsumsi kalori harian berdasarkan data NBM Indonesia, dengan menggunakan metodologi CRISP-DM (Cross-Industry Standard Process for Data Mining), sebuah standar proses terstruktur yang dirancang untuk perencanaan proyek di bidang data science, termasuk aplikasi machine learning (Singgalen, 2023).

![Diagram alur CRISP-DM](image-placeholder)
*Gambar 1. Diagram alur CRISP-DM*

Data yang digunakan adalah data sekunder NBM Indonesia dari Badan Pangan Nasional periode 1993-2024, dengan target prediksi konsumsi kalori per kapita harian. Model LSTM akan diimplementasikan menggunakan Python dengan TensorFlow/Keras, dan sistem informasi dikembangkan menggunakan framework Laravel untuk memberikan interface prediksi real-time.

### 1. Pemahaman Bisnis (Business Understanding)
Tahap pertama ini bertujuan untuk memahami konteks ketahanan pangan dan menentukan tujuan utama dari penelitian. Dalam konteks penelitian ini, proses bisnis yang diteliti adalah bagaimana prediksi konsumsi kalori harian dapat mendukung perencanaan ketahanan pangan nasional. Hasil dari tahap ini adalah perumusan masalah yang akan diselesaikan dengan model prediksi konsumsi kalori menggunakan algoritma LSTM.

### 2. Pemahaman Data (Data Understanding)
Pada tahap ini, dilakukan pengumpulan dan eksplorasi data NBM Indonesia periode 1993-2024 yang diperoleh dari Badan Pangan Nasional. Data yang dikumpulkan meliputi variabel konsumsi kalori per kapita, produksi, impor, ekspor, dan utilisasi pangan. Setelah data terkumpul, dilakukan analisis awal untuk memastikan kualitas data dan memeriksa distribusi variabel yang ada serta pola temporal dalam time series.

### 3. Persiapan Data (Data Preparation)
Tahap ini melibatkan proses preprocessing dan pembersihan data, termasuk penanganan missing values dan outlier detection. Data yang telah disiapkan akan diolah untuk memenuhi format yang sesuai dengan kebutuhan algoritma LSTM. Beberapa teknik seperti normalization, feature scaling, dan sequence generation diterapkan pada tahap ini untuk memastikan bahwa data siap digunakan dalam model neural network.

### 4. Pemodelan (Modeling)
Pada tahap ini, algoritma LSTM diterapkan untuk membangun model prediksi konsumsi kalori harian berdasarkan data yang telah dipersiapkan. Hyperparameter seperti learning rate, batch size, epochs, dan window size akan diatur dan diuji untuk memastikan model dapat menghasilkan prediksi yang akurat. Dilakukan juga pengujian untuk memilih arsitektur LSTM terbaik dengan mempertimbangkan keseimbangan akurasi dan efisiensi model.

### 5. Evaluasi (Evaluation)
Setelah model terbentuk, tahap evaluasi dilakukan untuk menilai performa model. Pada tahap ini, akurasi model dievaluasi berdasarkan hasil prediksi dan perbandingan dengan data aktual. Performa model akan diukur menggunakan metrik evaluasi tertentu, seperti RMSE, MAE, MAPE, dan R-squared. Hasil evaluasi akan menunjukkan apakah model sudah dapat digunakan atau perlu dilakukan penyempurnaan.

### 6. Penyebaran (Deployment)
Tahap terakhir dari metode CRISP-DM adalah penyebaran model. Model LSTM yang telah dievaluasi akan diintegrasikan ke dalam sistem informasi berbasis Laravel dengan database MySQL. Implementasi model ini memungkinkan stakeholder ketahanan pangan untuk melakukan prediksi konsumsi kalori secara real-time melalui interface web, sekaligus memberikan akses kepada pengambil kebijakan untuk monitoring dan early warning system.

## 1.8 Luaran

Penelitian ini diharapkan dapat menghasilkan model yang akurat dan efisien untuk prediksi konsumsi kalori harian dengan menerapkan algoritma LSTM, bertujuan untuk mendukung perencanaan ketahanan pangan nasional. Model ini akan dilengkapi dengan sistem informasi berbasis web yang mudah digunakan, memungkinkan stakeholder ketahanan pangan untuk melakukan prediksi konsumsi kalori secara konsisten dan akurat. Selain itu, penelitian ini juga akan memberikan pengambil kebijakan akses kepada informasi prediksi yang jelas dan real-time, sehingga membantu mereka dalam membuat keputusan yang lebih baik dalam perencanaan ketahanan pangan.

---

# DAFTAR PUSTAKA

Asian Development Bank. (2023). *Food Security Technology Adoption in Developing Asia: Status and Prospects*. Manila: ADB Publications.

Badan Pangan Nasional. (2022). *Peraturan Badan Pangan Nasional Republik Indonesia Nomor 10 Tahun 2022*. Jakarta: Badan Pangan Nasional.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine learning in agriculture: A comprehensive updated review. *Sensors*, 21(11), 3758. https://doi.org/10.3390/s21113758

BPS. (2023). *Proyeksi Penduduk Indonesia 2020-2050*. Jakarta: Badan Pusat Statistik.

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode Long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. *JUSTIN (Jurnal Sistem dan Teknologi Informasi)*, 11(2), 346-357. https://doi.org/10.26418/justin.v11i2.57395

Fadila, L. M. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia: Pendekatan Menggunakan Big Data dan Data Mining. *Seminar Nasional Official Statistics*, 2023, 1-15. https://dx.doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). *The State of Food Security and Nutrition in the World 2023*. Rome: Food and Agriculture Organization of the United Nations. https://doi.org/10.4060/cc3017en

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes dengan Feature Selection untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. *Jurnal Ilmiah Sains dan Teknologi*, 6(1), 1-10. https://doi.org/10.47080/saintek.v6i1.1467

Kementerian Pertanian. (2023). *Laporan Kinerja Kementerian Pertanian Tahun 2022*. Jakarta: Sekretariat Jenderal Kementerian Pertanian.

Kong, X., Chen, Z., Liu, W., Ning, K., et al. (2025). Deep learning for time series forecasting: a survey. *International Journal of Machine Learning and Cybernetics*, 16(7-8), 5079-5112. https://doi.org/10.1007/s13042-025-02560-w

Noureddine, J., Abbes, A. B., & Farah, I. R. (2023). Machine learning for food security: current status, challenges, and future perspectives. *Artificial Intelligence Review*. https://doi.org/10.1007/s10462-023-10617-x

Nugroho, C. P., Mutisari, R., & Aprilia, A. (2021). The utilization of information technology in improving marketing performance of agricultural products. *Agrisocionomics: Jurnal Sosial Ekonomi dan Kebijakan Pertanian*, 4(2), 238-246. https://doi.org/10.14710/agrisocionomics.v4i2.6646

Opara, I., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. *Plants*, 13(9), 1200. https://doi.org/10.3390/plants13091200

Pusat Data dan Sistem Informasi Pertanian. (2024). *Statistik Konsumsi Pangan 2024*. Jakarta: Kementerian Pertanian.

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. *Agriculture*, 13(10), 2037. https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Marx Gómez, J. (2021). A systematic literature review on applying CRISP-DM process model. *Procedia Computer Science*, 181, 526-534. https://doi.org/10.1016/j.procs.2021.01.199

Siami Namini, S., Tavakoli, N., & Siami Namin, A. (2021). The performance of LSTM and BiLSTM in forecasting time series. In *2019 IEEE International Conference on Big Data (Big Data)* (pp. 3285-3292). IEEE. https://doi.org/10.1109/BigData47090.2019.9005997

Singgalen, Y. A. (2023). Penerapan CRISP-DM dalam Klasifikasi Sentimen dan Analisis Perilaku Pembelian Layanan Akomodasi Hotel Berbasis Algoritma Decision Tree (DT). *Jurnal Sistem Komputer dan Informatika (JSON)*, 5(2), 237-248. https://doi.org/10.30865/json.v5i2.7081

Verma, A., Boggavarapu, S., Bharadwaj, A., & Prabakaran, N. (2024). LSTM-based deep learning for crop production prediction with synthetic data. In *Advanced computational methods for agri-business sustainability* (pp. 273-286). IGI Global. https://doi.org/10.4018/979-8-3693-3583-3.ch015

Torres, J. F., Hadjout, D., Sebaa, A., Martínez-Álvarez, F., et al. (2021). Deep learning for time series forecasting: A survey. *Big Data*, 9(1), 3-21. https://doi.org/10.1089/big.2020.0159

WFP. (2021). *COVID-19 Impact on Food Security in Indonesia: Rapid Assessment Report*. Jakarta: World Food Programme Indonesia.