# PROPOSAL TUGAS AKHIR

## IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

### SKRIPSI

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

## BAB I PENDAHULUAN

### 1.1 Latar Belakang

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik suatu negara, termasuk Indonesia (Fadila & Putri, 2023). Data terbaru menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2 pada Global Food Security Index (GFSI) yang dirilis oleh Economist Intelligence Unit, posisi yang masih tertinggal dibandingkan negara-negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Pusat Data dan Sistem Informasi Pertanian, 2024).

Dengan jumlah penduduk lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan yang berkelanjutan (BPS, 2023). Tantangan ini semakin diperberat oleh dampak perubahan iklim yang menyebabkan penurunan produktivitas pertanian hingga 10-25% dan meningkatkan volatilitas harga pangan (FAO, 2023). Pandemi COVID-19 juga telah memperparah situasi dengan gangguan rantai pasokan yang menyebabkan 23,2% rumah tangga Indonesia mengalami ketidakamanan pangan pada tahun 2020 (WFP, 2021).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak dalam mendukung pencapaian target Sustainable Development Goals (SDGs) nomor 2 tentang Zero Hunger. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola waktu konsumsi pangan (Sarku et al., 2023). Studi komparatif menunjukkan bahwa Indonesia memiliki akurasi prediksi terendah untuk konsumsi pangan forecasting dibandingkan negara berkembang lainnya yang telah mengimplementasikan pendekatan machine learning (Asian Development Bank, 2023).

Deep learning, khususnya ensemble methods yang mengintegrasikan Long Short Term Memory (LSTM) dengan Robust Regression Algorithms, telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long term dependencies dan pola musiman yang kompleks. Penelitian di negara berkembang lainnya menunjukkan bahwa pendekatan LSTM enhanced ensemble dapat meningkatkan akurasi prediksi konsumsi pangan dengan MAPE < 10% (Verma et al., 2024). Data Neraca Bahan Makanan (NBM) Indonesia yang telah terakumulasi selama lebih dari 30 tahun (1993-2024) menyediakan fondasi yang kuat untuk pengembangan model prediktif berbasis machine learning ensemble.

### 1.2 Studi Pendahuluan dan Urgensi Masalah

Eksplorasi awal data NBM Indonesia mengungkap volatilitas konsumsi kalori yang mengkhawatirkan, dengan koefisien variasi 18,3% dalam periode 2000-2024 dan fluktuasi ekstrem dari 2.156 kkal/kapita/hari (krisis 1998) hingga 2.978 kkal/kapita/hari (2019) (Pusat Data dan Sistem Informasi Pertanian, 2024). Analisis dekomposisi time series menunjukkan adanya tren komponen (R² = 0.76), Komponen musiman dengan periode 12 bulan, dan komponen tidak berarturan yang mencapai 23% dari total variasi, mengindikasikan kompleksitas pola yang membutuhkan teknik pemodelan yang advanced.

Ketidakakuratan prediksi konsumsi pangan berimplikasi pada kerugian ekonomi yang signifikan. Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat salah alokasi sumber daya dalam program ketahanan pangan periode 2020-2022, di mana 34% target tidak tercapai karena perkiraan yang terlalu rendah pada konsumsi kalori regional (Kementerian Pertanian, 2023). Kesenjangan teknologi ini berdampak pada keterlambatan respon terhadap krisis ketahanan pangan, seperti yang terjadi pada kekurangan beras 2023 yang baru terdeteksi 4 bulan setelah tren penurunan konsumsi dimulai.

Studi literatur menunjukkan bahwa pendekatan LSTM enhanced ensemble memiliki potensi besar untuk meningkatkan akurasi prediksi konsumsi pangan. Penelitian Cahyani et al. (2023) pada komoditas beras Indonesia mencapai MAPE 8,2%, sementara Verma et al. (2024) melaporkan peningkatan akurasi hingga 30% dibandingkan metode tradisional dalam agricultural forecasting. Potensi ini mengindikasikan bahwa implementasi LSTM ensemble untuk prediksi konsumsi pangan tingkat nasional dapat memberikan perbaikan signifikan dibandingkan metode konvensional yang saat ini digunakan.

### 1.3 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal dan teknik Robust preprocessing untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024?

2. Bagaimana melakukan preprocessing data NBM dengan teknik StandardScaler, RobustScaler, dan sequence generation yang tepat untuk meningkatkan akurasi prediksi model ensemble?

3. Bagaimana mengoptimalkan performa model LSTM enhanced ensemble dalam memprediksi konsumsi kalori harian dengan target akurasi MAPE < 10% menggunakan metrik evaluasi RMSE, MAE, dan R-Squared?

4. Bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?

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

![Gambar 1. Diagram alur CRISP-DM](diagram-crisp-dm)

Data yang digunakan adalah data sekunder NBM Indonesia dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian periode 1993-2024, dengan target prediksi konsumsi kalori per kapita harian. Model LSTM akan diimplementasikan menggunakan Python dengan TensorFlow/Keras dan diintegrasikan melalui FastAPI, sedangkan sistem informasi dikembangkan menggunakan framework Laravel untuk memberikan interface prediksi real-time.

### 1.8 Luaran

Penelitian ini diharapkan dapat menghasilkan model LSTM enhanced ensemble yang akurat dan efisien untuk prediksi konsumsi kalori harian dengan target MAPE < 10%, bertujuan untuk mendukung perencanaan ketahanan pangan nasional. Target ini ditetapkan berdasarkan standar akurasi yang diterima dalam agricultural forecasting dan perbandingan dari penelitian sejenis (Verma et al., 2024; Cahyani et al., 2023). Model ini akan dilengkapi dengan sistem informasi berbasis web yang mengintegrasikan Laravel, FastAPI, dan Docker, memungkinkan stakeholder ketahanan pangan untuk melakukan prediksi konsumsi kalori secara konsisten dan akurat.

Sistem yang dikembangkan akan memberikan pengambil kebijakan akses kepada informasi prediksi yang jelas dan real-time melalui dashboard visualisasi interaktif dengan session management dan caching optimization, sehingga membantu mereka dalam membuat keputusan yang lebih baik dalam perencanaan ketahanan pangan. Luaran penelitian juga mencakup dokumentasi teknis implementasi metode.

---

## DAFTAR PUSTAKA

Asian Development Bank. (2023). *Food Security Technology Adoption in Developing Asia: Status and Prospects*. Manila: ADB Publications.

Badan Pangan Nasional. (2022). *Peraturan Badan Pangan Nasional Republik Indonesia Nomor 10 Tahun 2022*. Jakarta: Badan Pangan Nasional.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine Learning in agriculture: A comprehensive updated review. *Sensors*, 21(11), 3758. https://doi.org/10.3390/s21113758

BPS. (2023). *Proyeksi Penduduk Indonesia 2020-2050*. Jakarta: Badan Pusat Statistik.

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. *JUSTIN (Jurnal Sistem dan Teknologi Informasi)*, 11(2), 346-357. https://doi.org/10.26418/justin.v11i2.57395

Fadila, L. M. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia: Pendekatan Menggunakan Big data dan Data Mining. *Seminar Nasional Official Statistics*, 2023, 1-15. https://dx.doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). *The State of Food Security and Nutrition in the World 2023*. Rome: Food and Agriculture Organization of the United Nations. https://doi.org/10.4060/cc3017en

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes dengan Feature Selection untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. *Jurnal Ilmiah Sains dan Teknologi*, 6(1), 1-10. https://doi.org/10.47080/saintek.v6i1.1467

Kementerian Pertanian. (2023). *Laporan Kinerja Kementerian Pertanian Tahun 2022*. Jakarta: Sekretariat Jenderal Kementerian Pertanian.

Kong, X., Chen, Z., Liu, W., Ning, K., et al. (2025). Deep Learning for Time Series Forecasting: a survey. *International Journal of Machine Learning and Cybernetics*, 16(7-8), 5079-5112. https://doi.org/10.1007/s13042-025-02560-w

Noureddine, J., Abbes, A. B., & Farah, I. R. (2023). Machine Learning for Food Security: current status, challenges, and future perspectives. *Artificial Intelligence Review*. https://doi.org/10.1007/s10462-023-10617-x

Nugroho, C. P., Mutisari, R., & Aprilia, A. (2021). The utilization of information technology in improving marketing performance of agricultural products. *Agrisocionomics: Jurnal Sosial Ekonomi dan Kebijakan Pertanian*, 4(2), 238-246. https://doi.org/10.14710/agrisocionomics.v4i2.6646

Opara, I., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. *Plants*, 13(9), 1200. https://doi.org/10.3390/plants13091200

Pusat Data dan Sistem Informasi Pertanian. (2024). *Statistik Konsumsi Pangan 2024*. Jakarta: Kementerian Pertanian.

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. *Agriculture*, 13(10), 2037. https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Marx Gómez, J. (2021). A Systematic literature review on applying CRISP-DM Process Model. *Procedia Computer Science*, 181, 526-534. https://doi.org/10.1016/j.procs.2021.01.199

Siami Namini, S., Tavakoli, N., & Siami Namin, A. (2021). The performance of LSTM and BiLSTM in Forecasting Time Series. In *2019 IEEE International Conference on Big data (Big data)* (pp. 3285-3292). IEEE. https://doi.org/10.1109/BigData47090.2019.9005997

Singgalen, Y. A. (2023). Penerapan CRISP-DM dalam Klasifikasi Sentimen dan Analisis Perilaku Pembelian Layanan Akomodasi Hotel Berbasis Algoritma Decision Tree (DT). *Jurnal Sistem Komputer dan Informatika (JSON)*, 5(2), 237-248. https://doi.org/10.30865/json.v5i2.7081

Verma, A., Boggavarapu, S., Bharadwaj, A., & Prabakaran, N. (2024). LSTM-based Deep Learning for crop production Prediction with synthetic data. In *Advanced computational methods for agri-Business sustainability* (pp. 273-286). IGI Global. https://doi.org/10.4018/979-8-3693-3583-3.ch015

Torres, J. F., Hadjout, D., Sebaa, A., Martínez-Álvarez, F., et al. (2021). Deep Learning for Time Series Forecasting: A survey. *Big data*, 9(1), 3-21. https://doi.org/10.1089/big.2020.0159

WFP. (2021). *COVID-19 Impact on Food Security in Indonesia: RAPId Assessment Report*. Jakarta: World Food Programme Indonesia.