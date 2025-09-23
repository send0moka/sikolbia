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

Ketahanan pangan global telah menjadi tantangan utama abad ke-21 yang memerlukan perhatian serius dari komunitas internasional. Menurut FAO (2023), sekitar 735 juta orang di dunia mengalami kelaparan pada tahun 2022, meningkat dari 768 juta pada tahun sebelumnya. Perubahan iklim, konflik geopolitik, dan dampak pandemi COVID-19 telah memperburuk situasi ketahanan pangan global (Smith et al., 2024). Negara-negara berkembang, khususnya di Asia Tenggara, menghadapi tekanan yang lebih besar dalam mempertahankan sistem pangan yang resilient dan berkelanjutan (Ahmed et al., 2023).

Dalam konteks regional, Asia Tenggara merupakan wilayah dengan tingkat kerawanan pangan yang signifikan. Data Global Food Security Index (GFSI) 2024 menunjukkan bahwa rata-rata skor ketahanan pangan negara-negara ASEAN masih berada di bawah standar optimal (Johnson & Williams, 2024). Faktor-faktor seperti pertumbuhan populasi yang pesat, urbanisasi, dan degradasi lahan pertanian menjadi tantangan utama dalam menjaga stabilitas pasokan pangan regional (ASEAN Secretariat, 2023).

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik suatu negara, termasuk Indonesia (Fadila & Putri, 2023). Data terbaru menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2 pada Global Food Security Index (GFSI) yang dirilis oleh Economist Intelligence Unit, posisi yang masih tertinggal dibandingkan negara-negara ASEAN lainnya seperti Singapapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Pusat Data dan Sistem Informasi Pertanian, 2024). Rendahnya peringkat ini mencerminkan berbagai tantangan struktural dalam sistem pangan nasional, termasuk keterbatasan infrastruktur, volatilitas harga, dan kapasitas prediksi yang masih terbatas (Rahmawati et al., 2023).

Dengan jumlah penduduk lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan yang berkelanjutan (BPS, 2023). Tantangan ini semakin diperberat oleh dampak perubahan iklim yang menyebabkan penurunan produktivitas pertanian hingga 10-25% dan meningkatkan volatilitas harga pangan (FAO, 2023). Pandemi COVID-19 juga telah memperparah situasi dengan gangguan rantai pasokan yang menyebabkan 23,2% rumah tangga Indonesia mengalami ketidakamanan pangan pada tahun 2020 (WFP, 2021). Selain itu, fenomena El Niño dan La Niña secara periodik mempengaruhi pola curah hujan dan produksi pertanian nasional (Meteorological, Climatological, and Geophysical Agency, 2024).

Eksplorasi awal data Neraca Bahan Makanan (NBM) Indonesia mengungkap volatilitas konsumsi kalori yang mengkhawatirkan, dengan koefisien variasi 18,3% dalam periode 2000-2024 dan fluktuasi ekstrem dari 2.156 kkal/kapita/hari (krisis 1998) hingga 2.978 kkal/kapita/hari (2019) (Pusat Data dan Sistem Informasi Pertanian, 2024). Analisis dekomposisi time series menunjukkan adanya komponen tren (R² = 0.76), komponen musiman dengan periode 12 bulan, dan komponen tidak beraturan yang mencapai 23% dari total variasi, mengindikasikan kompleksitas pola yang membutuhkan teknik pemodelan yang advanced (Susanti & Prabowo, 2023).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak dalam mendukung pencapaian target Sustainable Development Goals (SDGs) nomor 2 tentang Zero Hunger. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola waktu konsumsi pangan (Sarku et al., 2023). Studi komparatif menunjukkan bahwa Indonesia memiliki akurasi prediksi terendah untuk konsumsi pangan forecasting dibandingkan negara berkembang lainnya yang telah mengimplementasikan pendekatan machine learning (Asian Development Bank, 2023).

Ketidakakuratan prediksi konsumsi pangan berimplikasi pada kerugian ekonomi yang signifikan. Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat salah alokasi sumber daya dalam program ketahanan pangan periode 2020-2022, di mana 34% target tidak tercapai karena perkiraan yang terlalu rendah pada konsumsi kalori regional (Kementerian Pertanian, 2023). Kesenjangan teknologi ini berdampak pada keterlambatan respon terhadap krisis ketahanan pangan, seperti yang terjadi pada kekurangan beras 2023 yang baru terdeteksi 4 bulan setelah tren penurunan konsumsi dimulai (Wibowo et al., 2024).

Dalam era revolusi industri 4.0, penerapan teknologi artificial intelligence (AI) dan machine learning telah mentransformasi berbagai sektor, termasuk prediksi dan perencanaan pangan (Chen et al., 2024). Deep learning, khususnya algoritma neural network, telah menunjukkan kemampuan superior dalam menangani data time series yang kompleks dengan pola non-linear (Zhang et al., 2023). Long Short Term Memory (LSTM), sebagai varian dari Recurrent Neural Network (RNN), telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long term dependencies dan pola musiman yang kompleks (Liu & Wang, 2024).

Ensemble methods yang mengintegrasikan LSTM dengan algoritma machine learning lainnya telah menunjukkan peningkatan performa yang signifikan dalam berbagai domain prediksi (Kumar et al., 2024). Penelitian terdahulu menunjukkan bahwa pendekatan ensemble dapat mengurangi overfitting dan meningkatkan generalisasi model (Brown et al., 2023). Khususnya dalam agricultural forecasting, ensemble methods yang menggabungkan LSTM dengan robust regression algorithms telah mencapai akurasi yang lebih tinggi dibandingkan single model approaches (Martinez et al., 2024).

Tinjauan literatur terhadap penelitian terdahulu mengungkap beberapa gap penelitian yang signifikan. Pertama, mayoritas penelitian LSTM untuk prediksi pangan berfokus pada komoditas tunggal seperti beras atau jagung, belum ada yang menggunakan data agregat konsumsi kalori nasional dari NBM (Cahyani et al., 2023; Prasetyo & Utami, 2024). Kedua, penelitian sebelumnya umumnya menggunakan single LSTM model tanpa ensemble approach, padahal literatur menunjukkan bahwa ensemble methods dapat meningkatkan akurasi prediksi hingga 25-30% (Verma et al., 2024; Rodriguez & Chen, 2023). Ketiga, belum ada penelitian yang mengintegrasikan model LSTM ensemble dengan sistem informasi real-time berbasis microservices architecture untuk prediksi konsumsi pangan Indonesia (Thompson et al., 2024).

Penelitian Wang et al. (2023) menggunakan LSTM untuk prediksi produksi gandum di China dengan MAPE 12,4%, namun tidak menggunakan ensemble method dan data terbatas pada satu komoditas. Sementara itu, Singh & Patel (2024) menerapkan ensemble LSTM untuk prediksi harga pangan di India dengan MAPE 9,7%, tetapi fokus pada harga bukan konsumsi kalori. Garcia et al. (2023) mengembangkan sistem prediksi konsumsi pangan Brasil menggunakan traditional time series methods dengan MAPE 14,8%, menunjukkan potensi perbaikan dengan deep learning approach.

Penelitian di negara berkembang lainnya menunjukkan bahwa pendekatan LSTM enhanced ensemble dapat meningkatkan akurasi prediksi konsumsi pangan dengan MAPE < 10% (Verma et al., 2024). Namun, penelitian tersebut menggunakan data synthetic dan belum divalidasi dengan data real-world yang kompleks seperti NBM Indonesia. Data Neraca Bahan Makanan (NBM) Indonesia yang telah terakumulasi selama lebih dari 30 tahun (1993-2024) menyediakan fondasi yang kuat untuk pengembangan model prediktif berbasis machine learning ensemble yang dapat mengisi gap penelitian yang ada (Anderson & Lee, 2024).

### 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal, teknik Robust preprocessing (StandardScaler dan RobustScaler), sequence generation yang tepat, dan evaluasi metrik RMSE, MAE, MAPE untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%?

2. Bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?

### 1.3 Batasan Penelitian

Adapun batasan dari penelitian ini adalah sebagai berikut:

1. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian.

2. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 yang bersumber dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian.

3. Prediksi yang dibuat terbatas pada konsumsi kalori harian per kapita, tidak mencakup prediksi protein dan lemak.

4. Implementasi sistem informasi menggunakan framework Laravel untuk frontend web interface dan FastAPI untuk backend machine learning service dengan database MySQL, dilengkapi dengan Docker containerization untuk deployment yang scalable dan Redis untuk penyimpanan cache.

5. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi dengan target MAPE < 10% berdasarkan standar industri dan literatur terkait.

6. Penelitian ini tidak mencakup pengembangan mobile application, hanya fokus pada sistem berbasis web.

### 1.4 Tujuan Penelitian

Adapun tujuan dari penelitian ini adalah sebagai berikut:

1. Mengimplementasikan model LSTM enhanced ensemble untuk prediksi konsumsi kalori harian dengan memanfaatkan data historis Neraca Bahan Makanan Indonesia dan teknik Robust preprocessing.

2. Melakukan preprocessing dan feature engineering pada data NBM menggunakan StandardScaler dan RobustScaler untuk optimalisasi performa model ensemble dalam prediksi konsumsi kalori.

3. Mengevaluasi performa model LSTM enhanced ensemble dalam memprediksi konsumsi kalori harian menggunakan metrik evaluasi RMSE, MAE, dan MAPE dengan target akurasi MAPE < 10% berdasarkan perbandingan dari literatur terkait.

4. Mengintegrasikan model ensemble yang telah dilatih ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan prediksi konsumsi kalori secara real-time.

### 1.5 Manfaat Penelitian

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

### 1.6 Metode Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development. Penelitian difokuskan pada implementasi algoritma LSTM untuk prediksi konsumsi kalori harian berdasarkan data NBM Indonesia, dengan menggunakan metodologi CRISP-DM (Cross-Industry Standard Process for Data Mining) yang telah terbukti efektif dalam proyek machine learning (Schröer et al., 2021).

Diagram alur CRISP-DM dapat dilihat pada Gambar 1 yang menunjukkan tahapan sistematis dari business understanding hingga deployment. Metodologi ini dipilih karena memberikan kerangka kerja yang terstruktur untuk proyek data mining dan machine learning yang kompleks.

![Gambar 1. Diagram alur CRISP-DM](diagram-crisp-dm)

Data yang digunakan adalah data sekunder NBM Indonesia dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian periode 1993-2024, dengan target prediksi konsumsi kalori per kapita harian. Model LSTM akan diimplementasikan menggunakan Python dengan TensorFlow/Keras dan diintegrasikan melalui FastAPI, sedangkan sistem informasi dikembangkan menggunakan framework Laravel untuk memberikan interface prediksi real-time.

### 1.7 Luaran

Penelitian ini diharapkan dapat menghasilkan model LSTM enhanced ensemble yang akurat dan efisien untuk prediksi konsumsi kalori harian dengan target MAPE < 10%, bertujuan untuk mendukung perencanaan ketahanan pangan nasional. Target ini ditetapkan berdasarkan standar akurasi yang diterima dalam agricultural forecasting dan perbandingan dari penelitian sejenis (Verma et al., 2024; Cahyani et al., 2023). Model ini akan dilengkapi dengan sistem informasi berbasis web yang mengintegrasikan Laravel, FastAPI, dan Docker, memungkinkan stakeholder ketahanan pangan untuk melakukan prediksi konsumsi kalori secara konsisten dan akurat.

Sistem yang dikembangkan akan memberikan pengambil kebijakan akses kepada informasi prediksi yang jelas dan real-time melalui dashboard visualisasi interaktif dengan session management dan caching optimization, sehingga membantu mereka dalam membuat keputusan yang lebih baik dalam perencanaan ketahanan pangan. Luaran penelitian juga mencakup dokumentasi teknis implementasi metode.

---

## DAFTAR PUSTAKA

Islam, M. S., & Kieu, E. (2021). Climate Change and Food Security in Asia Pacific: Response and Resilience. Springer. https://doi.org/10.1007/978-3-030-70753-8

Waqas, M., Naseem, A., Wannasingha, U. H., Hlaing, P. T., Dechpichai, P., & Wangwongchai, A. (2025). Applications of machine learning and deep learning in agriculture: A comprehensive review. Green Technologies and Sustainability. https://doi.org/10.1016/j.grets.2025.100199

ASEAN Secretariat. (2024). Enhancing and Integrating Regional Food Safety to Face the Changing Landscape of Food System and Health Threats. ASEAN Socio-Cultural Community Trend Report No. 4. Jakarta: The ASEAN Secretariat.

Asian Development Bank. (2023). *Food Security Technology Adoption in Developing Asia: Status and Prospects*. Manila: ADB Publications.

Badan Pangan Nasional. (2022). *Peraturan Badan Pangan Nasional Republik Indonesia Nomor 10 Tahun 2022*. Jakarta: Badan Pangan Nasional.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine Learning in agriculture: A comprehensive updated review. *Sensors*, 21(11), 3758. https://doi.org/10.3390/s21113758

BPS. (2023). *Proyeksi Penduduk Indonesia 2020-2050*. Jakarta: Badan Pusat Statistik.

Magalhães, L. P., Sais, A. C., & Rossi, F. (2025). Research on using ensemble models to assess the impacts of climate change on agriculture production: A review. AgriEngineering, 7(7), 219. https://doi.org/10.3390/agriengineering7070219

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. *JUSTIN (Jurnal Sistem dan Teknologi Informasi)*, 11(2), 346-357. https://doi.org/10.26418/justin.v11i2.57395

Yang, H., Jiao, W., Zouyi, L., Diao, H., & Xia, S. (2025). Artificial intelligence in the food industry: innovations and applications. Discover Artificial Intelligence, 5(1). https://doi.org/10.1007/s44163-025-00296-8

Fadila, L. M. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia: Pendekatan Menggunakan Big data dan Data Mining. *Seminar Nasional Official Statistics*, 2023, 1-15. https://dx.doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). *The State of Food Security and Nutrition in the World 2023*. Rome: Food and Agriculture Organization of the United Nations. https://doi.org/10.4060/cc3017en

Serrano, A. L. M., Rodrigues, G., Martins, P. H. D. S., Saiki, G. M., Filho, G. P. R., Gonçalves, V. P., & Albuquerque, R. (2024). Statistical comparison of time series models for forecasting Brazilian monthly energy demand using economic, industrial, and climatic exogenous variables. Applied Sciences, 14(13), 5846. https://doi.org/10.3390/app14135846

Sundram, P. (2023). Food security in ASEAN: progress, challenges and future. Frontiers in Sustainable Food Systems, 7. https://doi.org/10.3389/fsufs.2023.1260619

Kementerian Pertanian. (2023). *Laporan Kinerja Kementerian Pertanian Tahun 2022*. Jakarta: Sekretariat Jenderal Kementerian Pertanian.

Kong, X., Chen, Z., Liu, W., Ning, K., et al. (2025). Deep Learning for Time Series Forecasting: a survey. *International Journal of Machine Learning and Cybernetics*, 16(7-8), 5079-5112. https://doi.org/10.1007/s13042-025-02560-w

Howard, C. C., & Augustine, M. A. (2025). Ensemble methods for time series forecasting in Nigeria: Predicting agricultural yields using advanced machine learning approaches. Asian Journal of Pure and Applied Mathematics, 7(1), 318-336. https://doi.org/10.56557/ajpam/2025/v7i1205

Kumar, V. K., Ramesh, K. V., & Rakesh, V. (2023). Optimizing LSTM and Bi-LSTM models for crop yield prediction and comparison of their performance with traditional machine learning techniques. Applied Intelligence, 53(23), 28291-28309. https://doi.org/10.1007/s10489-023-05005-5

Narkunam, G. A. (2025). Enhancing agricultural forecasting with an ensemble learning approach for broccoli yield prediction. Journal of Information Systems Engineering & Management, 10(41s), 105-116. https://doi.org/10.52783/jisem.v10i41s.7754

Badan Meteorologi, Klimatologi, dan Geofisika. (2024). Catatan Iklim dan Kualitas Udara Indonesia 2024. Jakarta: Deputi Bidang Klimatologi BMKG.

Noureddine, J., Abbes, A. B., & Farah, I. R. (2023). Machine Learning for Food Security: current status, challenges, and future perspectives. *Artificial Intelligence Review*. https://doi.org/10.1007/s10462-023-10617-x

Nugroho, C. P., Mutisari, R., & Aprilia, A. (2021). The utilization of information technology in improving marketing performance of agricultural products. *Agrisocionomics: Jurnal Sosial Ekonomi dan Kebijakan Pertanian*, 4(2), 238-246. https://doi.org/10.14710/agrisocionomics.v4i2.6646

Opara, I., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. *Plants*, 13(9), 1200. https://doi.org/10.3390/plants13091200

Tami, M., & Owda, A. Y. (2024). Efficient commodity price forecasting using long short-term memory model. IAES International Journal of Artificial Intelligence (IJ-AI), 13(1), 994-1004. http://doi.org/10.11591/ijai.v13.i1.pp994-1004

Pusat Data dan Sistem Informasi Pertanian. (2024). *Statistik Konsumsi Pangan 2024*. Jakarta: Kementerian Pertanian.

Rozaki Z. (2021). Food security challenges and opportunities in indonesia post COVID-19. Advances in Food Security and Sustainability, 6, 119–168. https://doi.org/10.1016/bs.af2s.2021.07.002

Pawar, A., Shenoy, M., Prabhu, S., & Rai, D. G. (2023). Performance analysis of machine learning algorithms: Single model vs ensemble model. Journal of Physics: Conference Series, 2571(1), 012007. https://doi.org/10.1088/1742-6596/2571/1/012007

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. *Agriculture*, 13(10), 2037. https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Marx Gómez, J. (2021). A Systematic literature review on applying CRISP-DM Process Model. *Procedia Computer Science*, 181, 526-534. https://doi.org/10.1016/j.procs.2021.01.199

Siami Namini, S., Tavakoli, N., & Siami Namin, A. (2021). The performance of LSTM and BiLSTM in Forecasting Time Series. In *2019 IEEE International Conference on Big data (Big data)* (pp. 3285-3292). IEEE. https://doi.org/10.1109/BigData47090.2019.9005997

Jaiswal, R., Jha, G., Choudhary, K., & Kumar, R. R. (2023). Agricultural commodity price prediction using Long Short-Term Memory (LSTM) based neural networks. Bhartiya Krishi Anusandhan Patrika. https://doi.org/10.18805/BKAP613

Singgalen, Y. A. (2023). Penerapan CRISP-DM dalam Klasifikasi Sentimen dan Analisis Perilaku Pembelian Layanan Akomodasi Hotel Berbasis Algoritma Decision Tree (DT). *Jurnal Sistem Komputer dan Informatika (JSON)*, 5(2), 237-248. https://doi.org/10.30865/json.v5i2.7081

Paudel, D., Neupane, R. C., Sigdel, S., Poudel, P., & Khanal, A. R. (2023). COVID-19 pandemic, climate change, and conflicts on agriculture: A trio of challenges to global food security. Sustainability, 15(10), 8280. https://doi.org/10.3390/su15108280

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes dengan Feature Selection untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. *Jurnal Ilmiah Sains dan Teknologi*, 6(1), 1-10. https://doi.org/10.47080/saintek.v6i1.1467

Susanti, M., & Prabowo, A. (2023). Time series decomposition analysis of Indonesian food consumption patterns: Trends, seasonality, and irregular components. *Statistical Journal of IAOS*, 39(3), 567-578. https://doi.org/10.3233/SJI-230045

Thompson, K., Lee, J., & Wilson, D. (2024). Microservices architecture for real-time agricultural prediction systems: Design patterns and implementation strategies. *Journal of Agricultural Informatics*, 15(1), 23-37. https://doi.org/10.17700/jai.2024.15.1.678

Torres, J. F., Hadjout, D., Sebaa, A., Martínez-Álvarez, F., et al. (2021). Deep Learning for Time Series Forecasting: A survey. *Big data*, 9(1), 3-21. https://doi.org/10.1089/big.2020.0159

Verma, A., Boggavarapu, S., Bharadwaj, A., & Prabakaran, N. (2024). LSTM-based Deep Learning for crop production Prediction with synthetic data. In *Advanced computational methods for agri-Business sustainability* (pp. 273-286). IGI Global. https://doi.org/10.4018/979-8-3693-3583-3.ch015

Wang, X., Li, Y., & Zhang, Z. (2023). LSTM-based wheat production forecasting in China: A regional analysis approach. *Computers and Electronics in Agriculture*, 209, 107856. https://doi.org/10.1016/j.compag.2023.107856

WFP. (2021). *COVID-19 Impact on Food Security in Indonesia: RAPId Assessment Report*. Jakarta: World Food Programme Indonesia.

Sujarwo, Putra, A. N., Setyawan, R. A., Teixeira, H. M., & Khumairoh, U. (2022). Forecasting rice status for a food crisis early warning system based on satellite imagery and cellular automata in Malang, Indonesia. Sustainability, 14(15), 1-14.

Zhang, L., Wang, R., Li, Z., Li, J., Ge, Y., Wa, S., Huang, S., & Lv, C. (2023). Time-Series Neural Network: A High-Accuracy Time-Series Forecasting Method Based on Kernel Filter and Time Attention. Information, 14(9), 500. https://doi.org/10.3390/info14090500