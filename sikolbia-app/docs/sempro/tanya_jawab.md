## **Pertanyaan 1: Mengapa memilih LSTM dibandingkan metode deep learning lain seperti GRU atau Transformer?**

**Jawaban:**
Terima kasih atas pertanyaannya. LSTM dipilih karena beberapa alasan spesifik untuk kasus prediksi konsumsi kalori ini. Pertama, LSTM memiliki cell state mechanism yang memungkinkan model untuk menyimpan informasi jangka panjang, yang sangat penting untuk data NBM yang memiliki pola temporal kompleks selama 31 tahun. Kedua, berdasarkan tinjauan literatur, LSTM telah terbukti efektif untuk agricultural forecasting dengan data time series yang memiliki seasonal patterns seperti data konsumsi pangan kita. Ketiga, dibandingkan dengan GRU yang lebih sederhana, LSTM memberikan kontrol yang lebih granular melalui forget gate, input gate, dan output gate, sehingga lebih cocok untuk menangkap kompleksitas pola konsumsi pangan. Sedangkan Transformer, meskipun powerful, lebih cocok untuk sequence yang sangat panjang dan membutuhkan computational resources yang lebih besar, yang mungkin tidak efisien untuk dataset dengan 372 data points ini. Selain itu, penelitian Adhany et al. (2025) yang juga menggunakan LSTM untuk prediksi produksi padi di Indonesia berhasil mencapai MAPE 4.44%, menunjukkan efektivitas LSTM untuk konteks agricultural forecasting di Indonesia.

---

## **Pertanyaan 2: Bagaimana Anda menentukan target MAPE < 10%? Apakah ada standar industri atau referensi akademik untuk target tersebut?**

**Jawaban:**
Pertanyaan yang sangat baik dan kritis. Target MAPE kurang dari 10% ditentukan berdasarkan beberapa pertimbangan yang telah saya verifikasi secara langsung dari sumber-sumber penelitian. Izinkan saya menjelaskan dengan menunjukkan bukti dari penelitian aslinya.

Pertama, menurut Lewis (1982) yang menjadi standar referensi dalam forecasting dan dikutip di berbagai literatur termasuk Serrano et al. (2024) halaman 8, MAPE di bawah 10% dikategorikan sebagai "Highly Accurate Prediction", MAPE 10-20% sebagai "Good Prediction", 20-50% sebagai "Reasonable Prediction", dan di atas 50% sebagai "Inaccurate Prediction". Ini adalah standar yang diterima secara luas dalam komunitas ilmiah forecasting.

Kedua, saya telah melakukan verifikasi langsung terhadap penelitian-penelitian terkini yang menggunakan LSTM untuk agricultural forecasting, dan saya ingin meluruskan beberapa nilai yang tertulis di proposal awal saya:

**Sun et al. (2024)** - halaman 10-11 Table 4 - untuk prediksi harga komoditas pertanian di China menggunakan VMD-EEMD-LSTM mencapai MAPE **0.0195 atau 1.95%** untuk babi, **0.0269 atau 2.69%** untuk Chinese chives (Table 6 hal 15), **0.0185 atau 1.85%** untuk jamur shiitake (Table 7 hal 15), dan **0.0389 atau 3.89%** untuk kembang kol (Table 8 hal 16).

**Adhany et al. (2025)** - halaman 124-125 Tabel 2 - untuk prediksi produksi padi di Lubuklinggau, Indonesia menggunakan LSTM mencapai MAPE **4.44%**.

**Serrano et al. (2024)** - halaman 22-23 Table 5 - untuk prediksi energy demand di Brazil menggunakan SARIMA mencapai MAPE **1.38%**.

Semua penelitian ini mencapai MAPE jauh di bawah 10%, menunjukkan bahwa target tersebut adalah **achievable dan realistis** dengan metode deep learning yang tepat.

Ketiga, mengingat metode konvensional yang saat ini digunakan Badan Pangan Nasional memiliki MAPE 15-20%, penetapan target MAPE kurang dari 10% memberikan **improvement yang signifikan sekitar 33-50%**, yang sangat meaningful untuk aplikasi praktis dalam perencanaan ketahanan pangan nasional. Saya akan melakukan revisi pada nilai-nilai spesifik di dokumen proposal untuk memastikan akurasi referensi.

---

## **Pertanyaan 3: Apa yang dimaksud dengan "ensemble" dalam LSTM enhanced ensemble? Model apa saja yang akan digabungkan?**

**Jawaban:**
Terima kasih atas pertanyaannya. LSTM enhanced ensemble yang saya maksud adalah kombinasi antara LSTM sebagai model utama untuk ekstraksi pola temporal dengan robust regression algorithms, khususnya HuberRegressor. Jadi arsitekturnya seperti ini: LSTM akan berfungsi sebagai feature extractor yang menangkap temporal patterns dan long-term dependencies dari data time series konsumsi kalori. Output dari LSTM ini kemudian akan menjadi input features untuk HuberRegressor. 

HuberRegressor dipilih karena sifatnya yang robust terhadap outliers, yang penting mengingat data konsumsi pangan Indonesia memiliki volatilitas tinggi dengan koefisien variasi 18,3%. Prediksi final akan menggunakan weighted averaging dari kedua model ini. Bobot optimal untuk masing-masing model akan ditentukan melalui proses hyperparameter optimization pada tahap training menggunakan time series cross-validation dengan expanding window. 

Pendekatan ensemble ini terbukti dari literatur dapat meningkatkan akurasi prediksi hingga 25-30% dibandingkan single model approach. Sun et al. (2024) yang menggunakan metode ensemble dengan VMD-EEMD-LSTM untuk agricultural forecasting mencapai akurasi yang sangat tinggi, mendukung pendekatan ensemble yang akan saya gunakan.

---

## **Pertanyaan 4: Mengapa pembagian data 70:15:15 dan bukan menggunakan proporsi standar seperti 80:10:10?**

**Jawaban:**
Pertanyaan yang sangat relevan. Saya memilih proporsi 70:15:15 dengan beberapa pertimbangan khusus untuk time series forecasting. Pertama, untuk time series cross-validation dengan expanding window, saya memerlukan validation set yang cukup besar untuk melakukan multiple folds validation secara robust. Dengan 15% atau 48 titik data untuk validasi yang mencakup periode 2016-2019, saya dapat melakukan beberapa iterasi expanding window validation yang meaningful tanpa menghabiskan terlalu banyak data training.

Kedua, data testing 15% yang mencakup periode 2020-2024 sengaja dipilih karena periode ini mencakup kondisi challenging seperti pandemi COVID-19 dan disruptions terbaru dalam sistem pangan. Ini penting untuk menguji robustness model terhadap kondisi ekstrem yang tidak terduga. Periode ini juga relatif recent sehingga lebih relevan untuk validasi performa model dalam kondisi terkini.

Ketiga, dengan 70% data training atau 23 tahun data dari 1993-2015, model masih mendapatkan cukup variasi pola termasuk krisis ekonomi 1998 dan periode recovery, berbagai kondisi musim, serta perubahan struktural dalam konsumsi pangan Indonesia. Data ini cukup untuk menangkap pola jangka panjang dan seasonal patterns yang kompleks.

Proporsi 80:10:10 yang lebih umum digunakan akan memberikan data training lebih banyak, namun validation set yang terlalu kecil (sekitar 37 titik data) tidak cukup untuk melakukan time series cross-validation yang robust dengan expanding window. Proporsi 70:15:15 memberikan balance optimal antara data training yang sufficient dan validation/testing sets yang representative untuk evaluasi performa model secara comprehensive, khususnya untuk time series dengan karakteristik kompleks seperti data konsumsi kalori nasional.

---

## **Pertanyaan 5: Mengapa memilih Laravel dan FastAPI? Bukankah bisa menggunakan satu framework saja seperti Django atau Flask?**

**Jawaban:**
Pertanyaan yang sangat bagus tentang arsitektur sistem. Saya memilih kombinasi Laravel dan FastAPI karena prinsip separation of concerns dan optimization untuk use case yang berbeda, bukan sekadar preferensi teknologi.

Laravel dipilih untuk frontend karena beberapa alasan strategis: Pertama, Laravel memiliki ecosystem yang mature dengan Eloquent ORM yang powerful untuk database operations yang kompleks, terutama untuk mengelola data historis NBM yang besar. Kedua, Laravel menyediakan Livewire untuk reactive components yang memudahkan pembuatan dashboard interaktif real-time tanpa perlu heavy JavaScript framework. Ketiga, Blade templating engine sangat elegant untuk building user interface yang kompleks dengan maintainable code. Keempat, Laravel sangat baik untuk authentication, authorization, dan session management yang robust, yang penting untuk sistem yang akan digunakan oleh multiple stakeholders.

FastAPI dipilih untuk backend machine learning service karena keunggulan spesifik: Pertama, FastAPI dioptimalkan untuk serving machine learning models dengan native support untuk asynchronous operations yang crucial untuk handling multiple prediction requests simultaneously tanpa blocking. Kedua, FastAPI memiliki automatic API documentation dengan OpenAPI (Swagger) yang memudahkan development, testing, dan documentation untuk API endpoints. Ketiga, FastAPI terintegrasi seamlessly dengan Python scientific ecosystem seperti NumPy, Pandas, scikit-learn, dan TensorFlow/PyTorch yang saya gunakan untuk model LSTM. Keempat, FastAPI memiliki performa yang sangat tinggi, comparable dengan NodeJS dan Go, berkat penggunaan Starlette dan Pydantic.

Arsitektur microservices ini memberikan beberapa keuntungan praktis: Pertama, independent scaling - jika prediction service butuh more resources karena high demand, saya bisa scale up hanya FastAPI service tanpa affect Laravel application. Kedua, technology flexibility - frontend team bisa bekerja dengan Laravel (PHP) sementara data science team bekerja dengan FastAPI (Python) tanpa conflict. Ketiga, fault isolation - jika ML service mengalami issue, web interface masih bisa berfungsi untuk displaying historical data. Keempat, easier maintenance - updates pada model atau ML pipeline tidak require restart entire application.

Docker containerization memudahkan deployment konsisten across different environments, mengeliminasi "it works on my machine" problem. Dengan Docker Compose, saya bisa orchestrate multi-container setup dengan clear separation antara web service, ML service, database, dan caching layer.

Jadi meskipun bisa saja menggunakan satu framework seperti Django, separation ini memberikan optimization, flexibility, dan maintainability yang lebih baik untuk production deployment, terutama untuk sistem yang akan digunakan dalam jangka panjang untuk decision making di sektor ketahanan pangan.