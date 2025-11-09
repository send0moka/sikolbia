PPT-SEMRPO_H1D022006
slide 1 cover:
judul Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Neraca Bahan Makanan Kementerian Pertanian; identitas Jehian Athaya Tsani Az Zuhry H1D022006 - Informatika; dosen pembimbing 1 Ir. Nofiyati, S.Kom., M.Kom. IPM.; dosen pembimbing 2 Devi Astri Nawangnugraeni, S.Pd., M.Kom.; acara Seminar Proposal Tugas Akhir Kamis, 16 Oktober 2025
slide 2 latar belakang:
ada 4 cards: card 1 img orang kelaparan, highlight 735 juta, desc orang mengalami kelaparan di dunia (2022); card 2 img poster bar chart index ketahanan pangan se asean (2022), highlight 69/113, desc peringkat indonesia dalam global food security index; card 3 img komoditi pangan, highlight 18,3%, desc koefisien variasi konsumsi kalori (2000-2024); card 4 img beras bulog, highlight Rp 2,3 T, desc kerugian akibat salah alokasi (2020-2022); setelah cards ada text tantangan: metode konvensional memiliki MAPE 15-20%, perlu teknologi prediksi yang lebih akurat.
slide 3 rumusan masalah:
pertanyaan penelitian, 1. Bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%? 2. Bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?
slide 4 tujuan penelitian:
1. Mengimplementasikan model LSTM enhanced ensemble untuk prediksi konsumsi kalori harian dengan data historis NBM Indonesia 2. Melakukan preprocessing dan feature engineering menggunakan StandardScaler dan RobustScaler 3. Mengevaluasi performa model menggunakan metrik RMSE, MAE, dan MAPE dengan target MAPE < 10% 4. Mengintegrasikan model ke dalam sistem informasi berbasis web untuk prediksi real-time
slide 5 batasan penelitian:
1. Fokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian
2. Data NBM Indonesia periode 1993-2024 dari Badan Pangan Nasional, BPS, dan Kementerian Pertanian
3. Sistem informasi menggunakan Laravel (frontend), FastAPI (backend ML), MySQL, Docker, dan Redis
4. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE dengan target MAPE < 10%
5. Penelitian tidak mencakup pengembangan aplikasi mobile, hanya berbasis web
slide 6 manfaat penelitian:
Bagi Peneliti, Pengalaman praktis penerapan LSTM, deep learning, dan sistem microservices. 
Menjadi referensi untuk penelitian serupa di masa depan dalam domain agricultural forecasting.
Bagi Pembaca, Wawasan mengenai penerapan LSTM dalam prediksi konsumsi kalori berbasis NBM. 
Informasi bermanfaat bagi akademisi dan praktisi yang mengembangkan sistem prediksi ketahanan pangan.
Bagi Masyarakat, Sistem peringatan dini berbasis machine learning untuk perencanaan ketahanan pangan. 
Transparansi informasi prediksi untuk meningkatkan kesadaran tentang ketahanan pangan nasional.
slide 7 tinjauan pustaka | Ketahanan Pangan dan Neraca Bahan Makanan (NBM):
Ketahanan pangan adalah kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan, yang tercermin dari tersedianya pangan yang cukup, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat untuk dapat hidup sehat, aktif, dan produktif secara berkelanjutan. Pilar Utama Ketahanan Pangan itu ketersediaan, keterjangkauan, pemanfaatan, stabilitas. persamaan dasar nbm itu Konsumsi per kapita = Ketersediaan Bersih / (Jumlah Penduduk × 365 hari), Ketersediaan Bersih = Produksi + Impor - Ekspor ± ΔStok - Non-Food Uses,  Kalori per kapita per hari = (Konsumsi per kapita × Faktor Konversi Energi) / 10.
slide 8 tinjauan pustaka | LSTM dan Metode Ensemble:
Long Short-term Memory (LSTM) adalah specialized recurrent Neural Network architecture yang dirancang untuk mengatasi vanishing gradient problem dalam traditional RNNs, sehingga mampu menangkap Long-term Dependencies dalam sequential data. Komponen ada forget gate input gate cell state output gate. Keunggulan LSTM  ✅  Mampu menangkap pola temporal kompleks
 ✅  Menangkap long-term dependencies dalam data time series
 ✅  Mengatasi vanishing gradient problem pada RNN tradisional. Metode Ensemble & Optimizer, LSTM enhanced ensemble menggabungkan temporal pattern recognition capabilities dari LSTM dengan Robust statistical properties dari Regression Algorithms.  ✅  Mengurangi overfitting
 ✅  Meningkatkan generalisasi model.25-30% Peningkatan akurasi prediksi dengan metode ensemble. Adam Optimizer untuk training LSTM dengan adaptive learning rate
slide 9 tinjauan pustaka | Gap Analysis:
Keterbatasan Ruang Lingkup, Mayoritas penelitian fokus pada prediksi tingkat regional atau komoditas tunggal, belum ada yang menangani peramalan konsumsi kalori tingkat nasional menggunakan dataset NBM yang komprehensif. Pemanfaatan Data, Kurangnya pemanfaatan dataset historis jangka panjang yang tersedia, dengan mayoritas studi menggunakan data jangka pendek (< 10 tahun) yang tidak memadai untuk menangkap pola jangka panjang. Kesenjangan Metodologis, Terbatasnya penerapan arsitektur deep learning mutakhir seperti LSTM untuk forecasting konsumsi pangan dalam konteks negara berkembang. Kesenjangan Implementasi, Kurangnya sistem terintegrasi yang menggabungkan model prediktif dengan antarmuka yang mudah digunakan untuk aplikasi kebijakan. Kontribusi Penelitian, Mengimplementasikan LSTM enhanced ensemble untuk prediksi konsumsi kalori nasional menggunakan data NBM jangka panjang (1993-2024) dan mengintegrasikannya dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker.
slide 10 metodologi | Pendekatan dan Jenis Penelitian:
Pendekatan Kuantitatif Eksperimental, Menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development (RnD). Dipilih karena melibatkan analisis data numerik time series dan evaluasi performa model menggunakan metrik statistik.
Research and Development (RnD), Metode penelitian yang bertujuan untuk menghasilkan produk dan menguji keefektifannya. Produk yang dikembangkan: sistem prediksi konsumsi kalori berbasis LSTM terintegrasi dengan antarmuka web. Tahapan Research and Development (RnD) ada 10 tahapan yaitu research and collection preliminary, research planning, early product development, expert validation, product revision, early test, product revision, field test, final product revision, desimination.
slide 11 metodologi | Implementasi Model LSTM Enhanced Ensemble:
Implementasi model LSTM enhanced ensemble dilakukan menggunakan metodologi CRISP-DM sebagai kerangka kerja pengembangan untuk memastikan structured progression dari data understanding hingga model deployment. Fase CRISP-DM itu pemahaman bisnis, pemahaman data jika tidak maka kembali ke step tadi jika benar lanjut, persiapan data, pemodelan jika tidak maka kembali ke step tadi jika benar lanjut, evaluasi jika tidak maka kembali ke step pemahaman bisnis jika benar lanjut, penyebaran.  ✅  Analisis stakeholder, definisi masalah, target MAPE < 10%
 ✅  NBM 1993-2024, 372 data points bulanan
 ✅  Data cleaning, StandardScaler, RobustScaler
 ✅  LSTM enhanced ensemble, hyperparameter optimization
 ✅  RMSE, MAE, MAPE metrics
 ✅  Integrasi ke sistem web Laravel-FastAPI. Strategi Pembagian Data, data pelatihan (70%) 1993-2015 23 tahun = 276 titik data, validasi (15%) 2016-2019 4 tahun = 48 data, pengujian (15%) 2020-2024 4 tahun = 48 data. Time Series Cross-Validation dengan expanding window untuk validasi model. Konfigurasi Model, Hyperparameter Optimization
Grid search untuk learning rate, batch size, epochs, window size; Ensemble Configuration
LSTM + HuberRegressor dengan weighted averaging.
slide 12 metodologi | Jadwal Penelitian:
Research and Collection Preliminary november
Research Planning november
Early Product Development november-desember
Expert Validation desember
Product Revision desember
Early Test (Implementasi dan Uji Coba Model) desember-januari
Field Test januari
Final Product Revision januari
Dissemination januari
Penyusunan Laporan november-januari.
slide 13 terima kasih:
Terbuka untuk pertanyaan dan diskusi lebih lanjut