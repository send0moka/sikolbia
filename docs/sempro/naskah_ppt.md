# Naskah Presentasi Seminar Proposal

**[SLIDE 1 - COVER]**

Assalamu'alaikum warahmatullahi wabarakatuh. Selamat pagi Ibu dosen pembimbing dan teman-teman yang saya hormati.

Perkenalkan, nama saya Jehian Athaya Tsani Az Zuhry dengan NIM H1D022006 dari Program Studi Informatika. Pada kesempatan ini, saya akan mempresentasikan proposal tugas akhir saya yang berjudul "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian".

Penelitian ini dibimbing oleh Ibu Ir. Nofiyati, S.Kom., M.Kom. IPM. sebagai pembimbing pertama dan Ibu Devi Astri Nawangnugraeni, S.Pd., M.Kom. sebagai pembimbing kedua.

---

**[SLIDE 2 - LATAR BELAKANG]**

Baik, saya akan memulai dengan latar belakang penelitian ini.

Ketahanan pangan merupakan isu global yang sangat kritis saat ini. Berdasarkan data FAO tahun 2023, ada sekitar 735 juta orang di dunia yang mengalami kelaparan pada tahun 2022. Ini menunjukkan bahwa masalah ketahanan pangan masih menjadi tantangan besar bagi komunitas internasional.

Jika kita fokus ke Indonesia, situasinya juga cukup mengkhawatirkan. Indonesia menempati peringkat ke-69 dari 113 negara dalam Global Food Security Index tahun 2024. Ini menunjukkan bahwa kita masih tertinggal dibandingkan negara-negara ASEAN lainnya seperti Singapura, Malaysia, dan Thailand.

Yang lebih mengkhawatirkan lagi, data Neraca Bahan Makanan Indonesia menunjukkan volatilitas konsumsi kalori yang tinggi dengan koefisien variasi mencapai 18,3% dalam periode 2000-2024. Fluktuasi yang tinggi ini mencerminkan ketidakstabilan dalam sistem pangan nasional kita.

Dampak dari ketidakakuratan prediksi konsumsi pangan juga sangat signifikan. Kementerian Pertanian melaporkan kerugian mencapai Rp 2,3 triliun akibat salah alokasi sumber daya dalam program ketahanan pangan periode 2020-2022.

Tantangan utama yang kita hadapi adalah metode prediksi konvensional yang saat ini digunakan masih memiliki akurasi yang terbatas, dengan MAPE sekitar 15-20%. Oleh karena itu, kita memerlukan teknologi prediksi yang lebih akurat untuk mendukung perencanaan ketahanan pangan nasional.

---

**[SLIDE 3 - RUMUSAN MASALAH]**

Berdasarkan latar belakang tersebut, penelitian ini akan menjawab dua pertanyaan utama:

Pertama, bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal untuk memprediksi konsumsi kalori harian berdasarkan data Neraca Bahan Makanan Indonesia periode 1993-2024 dengan target akurasi MAPE kurang dari 10%?

Kedua, bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?

---

**[SLIDE 4 - TUJUAN PENELITIAN]**

Untuk menjawab rumusan masalah tersebut, penelitian ini memiliki empat tujuan utama:

Pertama, mengimplementasikan model LSTM enhanced ensemble untuk prediksi konsumsi kalori harian dengan memanfaatkan data historis Neraca Bahan Makanan Indonesia.

Kedua, melakukan preprocessing dan feature engineering pada data NBM menggunakan StandardScaler dan RobustScaler untuk optimalisasi performa model ensemble.

Ketiga, mengevaluasi performa model menggunakan metrik RMSE, MAE, dan MAPE dengan target akurasi MAPE kurang dari 10%.

Dan keempat, mengintegrasikan model yang telah dilatih ke dalam sistem informasi berbasis web untuk memberikan prediksi konsumsi kalori secara real-time.

---

**[SLIDE 5 - BATASAN PENELITIAN]**

Agar penelitian ini tetap fokus dan terarah, saya menetapkan beberapa batasan penelitian:

Pertama, penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian.

Kedua, data yang digunakan adalah data Neraca Bahan Makanan Indonesia periode 1993-2024 yang bersumber dari Badan Pangan Nasional, BPS, dan Kementerian Pertanian.

Ketiga, sistem informasi yang dikembangkan menggunakan Laravel untuk frontend, FastAPI untuk backend machine learning, MySQL sebagai database, serta Docker dan Redis untuk mendukung deployment yang scalable.

Keempat, evaluasi model akan menggunakan metrik RMSE, MAE, dan MAPE dengan target MAPE kurang dari 10%.

Dan terakhir, penelitian ini tidak mencakup pengembangan aplikasi mobile, hanya fokus pada sistem berbasis web.

---

**[SLIDE 6 - MANFAAT PENELITIAN]**

Penelitian ini diharapkan memberikan manfaat bagi berbagai pihak.

Bagi saya sebagai peneliti, penelitian ini memberikan pengalaman praktis dalam penerapan LSTM, deep learning, dan pengembangan sistem microservices. Selain itu, hasil penelitian ini diharapkan dapat menjadi referensi untuk penelitian serupa di masa depan, khususnya dalam domain agricultural forecasting.

Bagi pembaca, baik akademisi maupun praktisi, penelitian ini memberikan wawasan mengenai penerapan LSTM dalam prediksi konsumsi kalori berbasis data NBM dan informasi yang bermanfaat untuk mengembangkan sistem prediksi ketahanan pangan.

Bagi masyarakat luas, penelitian ini diharapkan dapat menghasilkan sistem peringatan dini berbasis machine learning untuk mendukung perencanaan ketahanan pangan nasional, serta memberikan transparansi informasi prediksi untuk meningkatkan kesadaran tentang pentingnya ketahanan pangan.

---

**[SLIDE 7 - TINJAUAN PUSTAKA: KETAHANAN PANGAN DAN NBM]**

Sekarang saya akan menjelaskan beberapa konsep penting yang menjadi landasan penelitian ini.

Ketahanan pangan didefinisikan sebagai kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan, yang tercermin dari tersedianya pangan yang cukup, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat.

Konsep ketahanan pangan ini berdiri pada empat pilar utama, yaitu ketersediaan, keterjangkauan, pemanfaatan, dan stabilitas.

Neraca Bahan Makanan atau NBM adalah instrumen penting dalam monitoring ketahanan pangan yang mengintegrasikan berbagai data seperti produksi, impor, ekspor, perubahan stok, dan penggunaan untuk pakan ternak.

Formula dasar NBM yang digunakan adalah: konsumsi per kapita dihitung dari ketersediaan bersih dibagi dengan jumlah penduduk dikali 365 hari. Ketersediaan bersih sendiri dihitung dari produksi ditambah impor dikurangi ekspor plus minus perubahan stok dan dikurangi penggunaan non-pangan. Kemudian untuk mendapatkan kalori per kapita per hari, konsumsi per kapita dikalikan dengan faktor konversi energi dan dibagi 10.

---

**[SLIDE 8 - TINJAUAN PUSTAKA: LSTM DAN METODE ENSEMBLE]**

Long Short-Term Memory atau LSTM adalah arsitektur neural network khusus yang dirancang untuk mengatasi masalah vanishing gradient pada RNN tradisional, sehingga mampu menangkap long-term dependencies dalam data sekuensial.

LSTM memiliki empat komponen utama: forget gate, input gate, cell state, dan output gate. Ketiga gate ini bekerja bersama untuk mengontrol aliran informasi dalam network.

Keunggulan utama LSTM adalah kemampuannya menangkap pola temporal yang kompleks, menangkap long-term dependencies dalam data time series, dan mengatasi vanishing gradient problem yang sering terjadi pada RNN tradisional.

Dalam penelitian ini, saya akan menggunakan metode ensemble yang menggabungkan LSTM dengan robust regression algorithms. Pendekatan ensemble ini terbukti dapat mengurangi overfitting dan meningkatkan generalisasi model. Berdasarkan literatur, metode ensemble dapat meningkatkan akurasi prediksi hingga 25-30%.

Untuk training, saya akan menggunakan Adam Optimizer yang memiliki adaptive learning rate, sehingga proses pembelajaran dapat lebih efisien.

---

**[SLIDE 9 - TINJAUAN PUSTAKA: GAP ANALYSIS]**

Berdasarkan tinjauan literatur yang telah saya lakukan, teridentifikasi beberapa gap penelitian yang signifikan:

Dari segi keterbatasan ruang lingkup, mayoritas penelitian yang ada fokus pada prediksi tingkat regional atau komoditas tunggal. Belum ada yang menangani peramalan konsumsi kalori tingkat nasional menggunakan dataset NBM yang komprehensif.

Dari segi pemanfaatan data, masih kurangnya pemanfaatan dataset historis jangka panjang. Mayoritas studi menggunakan data jangka pendek, kurang dari 10 tahun, yang tidak memadai untuk menangkap pola jangka panjang.

Dari segi metodologis, masih terbatasnya penerapan arsitektur deep learning mutakhir seperti LSTM untuk forecasting konsumsi pangan, khususnya dalam konteks negara berkembang.

Dan dari segi implementasi, masih kurangnya sistem terintegrasi yang menggabungkan model prediktif dengan antarmuka yang mudah digunakan untuk aplikasi kebijakan.

Oleh karena itu, kontribusi penelitian ini adalah mengimplementasikan LSTM enhanced ensemble untuk prediksi konsumsi kalori nasional menggunakan data NBM jangka panjang dari 1993 hingga 2024, dan mengintegrasikannya dalam sistem informasi berbasis web dengan arsitektur Laravel, FastAPI, dan Docker.

---

**[SLIDE 10 - METODOLOGI: PENDEKATAN DAN JENIS PENELITIAN]**

Sekarang saya akan menjelaskan metodologi penelitian yang akan digunakan.

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development atau RnD. Pendekatan ini dipilih karena penelitian ini melibatkan analisis data numerik time series dan evaluasi performa model menggunakan metrik statistik.

Metode Research and Development adalah metode penelitian yang bertujuan untuk menghasilkan produk dan menguji keefektifannya. Dalam konteks penelitian ini, produk yang akan dikembangkan adalah sistem prediksi konsumsi kalori berbasis LSTM yang terintegrasi dengan antarmuka web.

Tahapan RnD yang akan dilakukan mencakup 10 tahap, yaitu: research and collection preliminary, research planning, early product development, expert validation, product revision, early test, product revision lagi, field test, final product revision, dan dissemination.

---

**[SLIDE 11 - METODOLOGI: IMPLEMENTASI MODEL LSTM]**

Implementasi model LSTM enhanced ensemble akan dilakukan menggunakan metodologi CRISP-DM sebagai kerangka kerja pengembangan. CRISP-DM dipilih untuk memastikan structured progression dari data understanding hingga model deployment.

Ada enam fase dalam CRISP-DM yang akan saya lakukan. Pertama adalah pemahaman bisnis, di mana saya akan melakukan analisis stakeholder, definisi masalah, dan menetapkan target MAPE kurang dari 10%.

Kedua adalah pemahaman data, menggunakan data NBM dari 1993 hingga 2024 yang mencakup 372 data points bulanan.

Ketiga adalah persiapan data, meliputi data cleaning dan preprocessing menggunakan StandardScaler dan RobustScaler.

Keempat adalah pemodelan, di mana saya akan mengimplementasikan LSTM enhanced ensemble dengan hyperparameter optimization.

Kelima adalah evaluasi menggunakan metrik RMSE, MAE, dan MAPE.

Dan keenam adalah deployment, yaitu integrasi ke sistem web Laravel-FastAPI.

Untuk strategi pembagian data, saya akan membagi dataset secara kronologis menjadi 70% untuk data pelatihan yang mencakup periode 1993-2015 atau 276 titik data, 15% untuk data validasi periode 2016-2019 atau 48 titik data, dan 15% untuk data pengujian periode 2020-2024 atau 48 titik data.

Saya juga akan menggunakan Time Series Cross-Validation dengan expanding window untuk validasi model yang lebih robust.

Untuk konfigurasi model, saya akan melakukan hyperparameter optimization menggunakan grid search untuk learning rate, batch size, epochs, dan window size. Ensemble configuration akan menggabungkan LSTM dengan HuberRegressor menggunakan weighted averaging.

---

**[SLIDE 12 - METODOLOGI: JADWAL PENELITIAN]**

Penelitian ini direncanakan berlangsung selama 3 bulan dari November 2025 hingga Januari 2026.

Pada bulan November, saya akan melakukan research and collection preliminary, research planning, dan memulai early product development.

Pada bulan Desember, saya akan menyelesaikan early product development, melakukan expert validation, product revision, dan memulai early test yang merupakan tahap implementasi dan uji coba model.

Pada bulan Januari, saya akan menyelesaikan early test, melakukan field test, final product revision, dan dissemination.

Penyusunan laporan akan dilakukan secara paralel dari bulan November hingga Januari.

---

**[SLIDE 13 - PENUTUP]**

Demikian presentasi proposal tugas akhir saya. Saya menyadari masih banyak kekurangan dalam proposal ini, oleh karena itu saya sangat mengharapkan masukan, saran, dan arahan dari Ibu dosen pembimbing untuk perbaikan dan kelancaran penelitian ini ke depan.

Saya juga terbuka untuk pertanyaan dan diskusi dari teman-teman semua.

Terima kasih atas perhatiannya.

Wassalamu'alaikum warahmatullahi wabarakatuh.