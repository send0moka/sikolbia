# LAPORAN TUGAS AKHIR

## IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN

### SKRIPSI

Disusun untuk memenuhi sebagian persyaratan untuk memperoleh gelar Sarjana Komputer Jurusan Informatika

Disusun oleh:

**Jehian Athaya Tsani Az Zuhry**  
**H1D022006**

KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI  
UNIVERSITAS JENDERAL SOEDIRMAN  
FAKULTAS TEKNIK  
JURUSAN INFORMATIKA  
PURWOKERTO  
2025

---

## LEMBAR PENGESAHAN TUGAS AKHIR

Tugas Akhir dengan judul:

**IMPLEMENTASI LSTM UNTUK PREDIKSI KONSUMSI KALORI HARIAN BERDASARKAN DATA NERACA BAHAN MAKANAN KEMENTERIAN PERTANIAN**

Disusun oleh:

**Jehian Athaya Tsani Az Zuhry**  
**H1D022006**

Diajukan untuk memenuhi salah satu persyaratan memperoleh gelar Sarjana Komputer pada Jurusan Informatika Fakultas Teknik Universitas Jenderal Soedirman

Diterima dan disetujui

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

Puji syukur ke hadirat Tuhan Yang Maha Esa atas rahmat dan karunia-Nya, sehingga penulis dapat menyelesaikan penelitian tugas akhir dengan judul "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian." Penelitian ini disusun sebagai salah satu syarat untuk menempuh Tugas Akhir pada Jurusan Informatika, Fakultas Teknik, Universitas Jenderal Soedirman.

Dalam penyusunan tugas akhir ini, penulis mendapatkan berbagai dukungan, arahan, serta masukan dari banyak pihak. Oleh karena itu, dengan penuh hormat dan rasa syukur, penulis menyampaikan terima kasih kepada:

1. Bapak Prof. Dr. Eng. Ir. Agus Maryoto, S.T., M.T., IPU., ASEAN Eng., selaku Dekan Fakultas Teknik, Universitas Jenderal Soedirman.

2. Bapak Dr. Ir. Lasmedi Afuan, S.T., M.Cs., IPM., selaku Ketua Jurusan Informatika, Fakultas Teknik, Universitas Jenderal Soedirman.

3. Bapak Ir. Bangun Wijayanto, S.T., M.Cs., IPM., selaku Dosen Pembimbing Akademik yang telah memberikan arahan akademik selama masa perkuliahan.

4. Ibu Nofiyati, S.Kom., M.Kom. IPM., selaku Dosen Pembimbing I dan Ibu Devi Astri Nawangnugraeni, S.Pd., M.Kom., selaku Dosen Pembimbing II yang dengan penuh perhatian telah memberikan bimbingan dan arahan selama penyusunan tugas akhir ini.

5. Orang tua dan keluarga tercinta atas doa, semangat, serta dukungan yang tiada harganya.

6. Rekan-rekan seperjuangan di Jurusan Informatika angkatan 2022, serta seluruh pihak yang telah memberikan dukungan dan masukan.

Penulis menyadari laporan tugas akhir ini memiliki keterbatasan, sehingga kritik dan saran sangat diharapkan untuk perbaikan ke depan. Semoga karya ini dapat berkontribusi pada pengembangan ilmu, khususnya dalam bidang machine learning dan analisis data pangan, serta memberikan manfaat bagi perencanaan kebijakan ketahanan pangan nasional.

Purwokerto, 19 November 2025

Jehian Athaya Tsani Az Zuhry

---

## DAFTAR ISI

LEMBAR PENGESAHAN TUGAS AKHIR ............................................................. i  
KATA PENGANTAR ................................................................................................. ii  
DAFTAR ISI ............................................................................................................. iii  
DAFTAR GAMBAR ................................................................................................. v  
DAFTAR TABEL ..................................................................................................... vi  
ABSTRAK ............................................................................................................... vii

**BAB I PENDAHULUAN** .......................................................................................... 1  
1.1 Latar Belakang ............................................................................................. 1  
1.2 Rumusan Masalah ........................................................................................ 2  
1.3 Batasan Penelitian ........................................................................................ 2  
1.4 Tujuan Penelitian ......................................................................................... 3  
1.5 Manfaat Penelitian ....................................................................................... 3

**BAB II TINJAUAN PUSTAKA** ............................................................................. 5  
2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM) .............................. 5  
2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan ............................. 6  
2.3 Neural Network dan Deep Learning............................................................. 7  
2.4 Long Short-term Memory (LSTM) dan Metode Ensemble ............................ 8  
2.5 Metrik Evaluasi Model Prediksi ................................................................. 10  
2.6 Arsitektur Sistem Laravel-FastAPI dan Docker ......................................... 11  
2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan ............... 12  
2.8 Implementasi LSTM untuk Time Series Forecasting .................................. 12  
2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia........................ 13  
2.10 Gap Analysis .............................................................................................. 14  
2.11 Kerangka Konseptual ................................................................................. 15

**BAB III METODE PENELITIAN** ....................................................................... 16  
3.1. Data dan Alat Penelitian ............................................................................. 16  
3.2. Metode Penelitian ...................................................................................... 18

**BAB IV METODE PENELITIAN** ....................................................................... 40  
4.1. Research and Collection Preliminary ........................................................ 40  
4.2. Research Planning ..................................................................................... 47  
4.3. Early Product Development ....................................................................... 52  
4.4. Expert Validation ....................................................................................... 82  
4.5. Product Revision Post-Validation .............................................................. 83  
4.6. Early Test ................................................................................................... 83  
4.7. Product Revision Post-Early Test .............................................................. 83  
4.8. Field Test ................................................................................................... 84  
4.9. Final Product Revision .............................................................................. 84  
4.10. Dissemination and Documentation ............................................................ 84  
4.11. Pembahasan ............................................................................................... 85

**DAFTAR PUSTAKA** ............................................................................................... 86

---

## DAFTAR GAMBAR

Gambar 1. Struktur Sel LSTM ........................................................................................... 8  
Gambar 2. Kerangka Metodologi RnD Terintegrasi dengan CRISP-DM ....................... 19  
Gambar 3. Diagram Alur CRISP-DM.............................................................................. 21  
Gambar 4. Strategi Pembagian Data NBM Indonesia ..................................................... 24  
Gambar 5. Time Series Cross-Validation Expanding Window ....................................... 25  
Gambar 6. Arsitektur Microservices SIKOLBIA ............................................................ 29  
Gambar 7. Flowchart Sistem SIKOLBIA ........................................................................ 32  
Gambar 8. Entity Relationship Diagram (ERD) Modul Konsumsi Pangan NBM .......... 34  
Gambar 9. Halaman Landing Page SIKOLBIA............................................................... 63

---

## DAFTAR TABEL

Tabel 1. Penelitian Sejenis ................................................................................................ 13  
Tabel 2. Struktur Data NBM Indonesia ............................................................................ 16  
Tabel 3. Matriks Hak Akses dan Fitur Sistem .................................................................. 30  
Tabel 4. Analisis Kualitas Data NBM Indonesia .............................................................. 42  
Tabel 5. Klasifikasi Kelompok Komoditas NBM Indonesia ............................................ 45  
Tabel 6. Grid Search Hasil untuk Sequence Window Length .......................................... 50  
Tabel 7. Hasil Hyperparameter Tuning ............................................................................ 51  
Tabel 8. Software Requirements untuk Development ...................................................... 57  
Tabel 9. Hardware Requirements untuk Development ..................................................... 58  
Tabel 10. Software Stack Production................................................................................ 58  
Tabel 11. User Stakeholder Categories ............................................................................. 60  
Tabel 12. Functional Requirements .................................................................................. 61  
Tabel 13. Functional Requirements .................................................................................. 62

---

## ABSTRAK

Ketahanan pangan merupakan isu kritis bagi Indonesia dengan peringkat ke-69 dari 113 negara pada Global Food Security Index 2024. Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak untuk mendukung perencanaan kebijakan ketahanan pangan nasional. Metode prediksi konvensional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola temporal konsumsi pangan. Penelitian ini bertujuan mengimplementasikan model LSTM enhanced ensemble untuk memprediksi konsumsi kalori harian berdasarkan data Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%. Metodologi penelitian menggunakan pendekatan Research and Development (RnD) dengan kerangka kerja CRISP-DM. Data historis NBM mencakup 31 tahun dengan 372 titik data bulanan yang dibagi secara kronologis menjadi 70% data pelatihan, 15% data validasi, dan 15% data pengujian. Preprocessing menggunakan StandardScaler dan RobustScaler, sementara hyperparameter optimization dilakukan melalui time series cross-validation. Model ensemble menggabungkan LSTM dengan robust regression algorithms seperti HuberRegressor. Evaluasi menggunakan metrik RMSE, MAE, dan MAPE dengan perbandingan terhadap baseline models. Model diintegrasikan ke dalam sistem informasi berbasis website menggunakan arsitektur microservices dengan Laravel, FastAPI, dan Docker. Hasil penelitian diharapkan memberikan sistem prediksi konsumsi kalori yang akurat untuk mendukung pengambilan keputusan dalam perencanaan ketahanan pangan nasional dan berkontribusi pada penerapan deep learning untuk agricultural forecasting di Indonesia.

**Kata Kunci:** LSTM, ensemble learning, prediksi konsumsi kalori, Neraca Bahan Makanan, ketahanan pangan, time series forecasting, deep learning, CRISP-DM, microservices architecture

---

# BAB I  
# PENDAHULUAN

## 1.1 Latar Belakang

Ketahanan pangan merupakan isu kritis yang mempengaruhi stabilitas sosial, ekonomi, dan politik Indonesia. Data Global Food Security Index (GFSI) 2024 menunjukkan Indonesia menempati peringkat ke-69 dari 113 negara dengan skor 59,2, posisi yang masih tertinggal dibandingkan negara ASEAN lainnya seperti Singapura (77,4), Malaysia (70,1), dan Thailand (64,5) (Sekretariat Jendral - Kementrian Pertanian, 2024). Dengan populasi lebih dari 270 juta jiwa, Indonesia menghadapi tantangan kompleks dalam memastikan ketersediaan pangan berkelanjutan yang diperparah oleh perubahan iklim dan volatilitas harga pangan (BPS, 2023).

Sistem prediksi konsumsi pangan yang akurat menjadi kebutuhan mendesak untuk mendukung perencanaan ketahanan pangan nasional. Metode prediksi konvensional yang saat ini digunakan Badan Pangan Nasional memiliki akurasi terbatas (MAPE 15-20%) dan tidak mampu menangkap kompleksitas pola temporal konsumsi pangan (Sarku et al., 2023). Ketidakakuratan prediksi ini berimplikasi pada kerugian ekonomi signifikan, dengan Kementerian Pertanian melaporkan kerugian Rp 2,3 triliun akibat salah alokasi sumber daya dalam program ketahanan pangan periode 2020-2022 (Kementerian Pertanian, 2023).

Data Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 dengan 41,316 records menunjukkan volatilitas konsumsi kalori yang kompleks, dengan fluktuasi dari 2.156 kkal/kapita/hari hingga 2.978 kkal/kapita/hari (Sekretariat Jendral - Kementrian Pertanian, 2024). Dataset historis ini mencakup 120 komoditas pangan dari 11 kelompok utama dengan parameter produksi, impor, ekspor, dan konsumsi kalori per kapita per hari yang memberikan foundation komprehensif untuk analisis prediktif.

Long Short Term Memory (LSTM) sebagai varian Recurrent Neural Network telah terbukti unggul dalam time series forecasting dengan kemampuan menangkap long-term dependencies dan pola musiman kompleks (Alkahfi et al., 2024). Metode ensemble yang mengintegrasikan LSTM dengan robust regression algorithms menunjukkan peningkatan akurasi hingga 25-30% dibandingkan model tunggal (Howard & Augustine, 2025). Namun, belum ada penelitian yang menggunakan LSTM ensemble untuk prediksi konsumsi kalori agregat nasional berdasarkan data NBM Indonesia yang komprehensif.

Berdasarkan latar belakang di atas, penulis mengusulkan implementasi LSTM enhanced ensemble untuk prediksi konsumsi kalori harian nasional guna mengatasi keterbatasan metode konvensional dan memberikan sistem peringatan dini berbasis machine learning yang akurat untuk mendukung pengambilan keputusan dalam perencanaan ketahanan pangan Indonesia.

## 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

a. Bagaimana mengimplementasikan arsitektur model LSTM enhanced ensemble dengan hyperparameter optimal, teknik Robust preprocessing (StandardScaler dan RobustScaler), sequence generation yang tepat, dan evaluasi metrik RMSE, MAE, MAPE untuk memprediksi konsumsi kalori harian berdasarkan data NBM Indonesia periode 1993-2024 dengan target akurasi MAPE < 10%?

b. Bagaimana mengintegrasikan model ensemble yang telah divalidasi ke dalam sistem informasi berbasis website dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan layanan prediksi real-time?

## 1.3 Batasan Penelitian

Adapun batasan dari penelitian ini adalah sebagai berikut:

a. Penelitian ini berfokus pada pengembangan model machine learning menggunakan algoritma LSTM untuk prediksi konsumsi kalori harian.

b. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 dengan total 41,316 records yang mencakup 120 komoditas pangan dari 11 kelompok, bersumber dari Badan Pangan Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem Informasi Kementerian Pertanian.

c. Prediksi yang dibuat terbatas pada konsumsi kalori harian agregat nasional per komoditi untuk mendukung decision support pemerintah, tidak mencakup prediksi personal atau regional.

d. Sistem informasi berbasis website ditujukan untuk stakeholder pemerintah (Kementerian Pertanian) dengan arsitektur Laravel, FastAPI, dan Docker untuk national food security forecasting.

e. Evaluasi model mencakup metrik RMSE, MAE, dan MAPE untuk mengukur akurasi prediksi dengan target MAPE < 10% berdasarkan standar industri dan literatur terkait.

f. Penelitian ini tidak mencakup pengembangan aplikasi mobile atau personal health tracking, hanya fokus pada sistem national-level forecasting.

## 1.4 Tujuan Penelitian

Adapun tujuan dari penelitian ini adalah sebagai berikut:

a. Mengimplementasikan model LSTM enhanced ensemble untuk prediksi konsumsi kalori harian agregat nasional per komoditi dengan memanfaatkan data historis NBM Indonesia periode 1993-2024 dan teknik time-aware preprocessing untuk mencegah data leakage.

b. Melakukan preprocessing dan feature engineering pada dataset NBM 41,316 records menggunakan StandardScaler dan RobustScaler dengan metode yang aman secara temporal untuk optimalisasi performa model ensemble.

c. Mengevaluasi performa model LSTM enhanced ensemble dalam memprediksi konsumsi kalori nasional menggunakan metrik RMSE, MAE, dan MAPE dengan target akurasi MAPE < 10% untuk mendukung decision support sistem ketahanan pangan.

d. Mengintegrasikan model ensemble yang telah dilatih ke dalam sistem informasi berbasis website dengan arsitektur Laravel, FastAPI, dan Docker untuk memberikan prediksi konsumsi kalori secara real-time.

## 1.5 Manfaat Penelitian

Manfaat dari penelitian ini adalah sebagai berikut:

a. Bagi Peneliti

Penelitian ini memberikan pengalaman praktis dalam penerapan algoritma LSTM untuk prediksi time series konsumsi pangan, sekaligus mengembangkan keterampilan dalam implementasi deep learning dan pengembangan sistem informasi terintegrasi dengan arsitektur microservices. Selain itu, hasil penelitian ini diharapkan dapat menjadi referensi untuk penelitian atau proyek serupa di masa depan.

b. Bagi Pembaca

Penelitian ini memberikan wawasan mengenai penerapan algoritma LSTM dalam prediksi konsumsi kalori berbasis data NBM dan menyajikan informasi yang bermanfaat bagi akademisi dan praktisi yang ingin mengembangkan sistem prediksi ketahanan pangan.

c. Bagi Pemerintah dan Masyarakat

Penelitian ini membantu Badan Pangan Nasional, Kementerian Pertanian, dan pengambil kebijakan dalam perencanaan ketahanan pangan nasional melalui sistem peringatan dini berbasis machine learning dengan akurasi tinggi. Sistem menyediakan early warning system untuk antisipasi krisis pangan dan mendukung transparansi informasi prediksi konsumsi nasional untuk meningkatkan kesadaran publik tentang ketahanan pangan Indonesia.

---

# BAB II  
# TINJAUAN PUSTAKA

## 2.1 Ketahanan Pangan dan Neraca Bahan Makanan (NBM)

Ketahanan pangan didefinisikan sebagai kondisi terpenuhinya pangan bagi negara sampai dengan perseorangan, yang tercermin dari tersedianya pangan yang cukup, baik jumlah maupun mutunya, aman, beragam, bergizi, merata, dan terjangkau serta tidak bertentangan dengan agama, keyakinan, dan budaya masyarakat untuk dapat hidup sehat, aktif, dan produktif secara berkelanjutan (Badan Pangan Nasional, 2021). Konsep ini mencakup empat pilar utama: ketersediaan (availability), keterjangkauan (accessibility), pemanfaatan (utilization), dan stabilitas (stability) yang saling berinteraksi dalam sistem pangan nasional (FAO, 2023).

Neraca Bahan Makanan (NBM) menggunakan formula dasar untuk menghitung konsumsi per kapita. Persamaan (1) berikut menunjukkan formula tersebut:

$$\text{Konsumsi per kapita} = \frac{\text{Ketersediaan Bersih}}{\text{Jumlah Penduduk} \times 365 \text{ hari}}$$
... (1)

Ketersediaan Bersih dihitung dengan persamaan (2) yang disajikan sebagai berikut:

$$\text{Ketersediaan Bersih} = \text{Produksi} + \text{Impor} - \text{Ekspor} \pm \Delta\text{Stok} - \text{Non-Food Uses}$$
... (2)

Perubahan stok (Δstok) adalah perubahan stok (positif jika berkurang, negatif jika bertambah) dan non-food uses merupakan penggunaan untuk pakan ternak, industri, dan lain sebagainya.

Konversi ke kalori menggunakan faktor konversi energi. Persamaan (3) berikut menunjukkan proses konversi tersebut:

$$\text{Kalori per kapita per hari} = \frac{\text{Konsumsi per kapita (kg/hari)} \times \text{Faktor Konversi Energi (kkal/100g)}}{10}$$
... (3)

Neraca Bahan Makanan (NBM) merupakan instrumen penting dalam monitoring ketahanan pangan yang menyajikan gambaran menyeluruh tentang situasi pangan suatu negara dalam kurun waktu tertentu (Sekretariat Jendral - Kementrian Pertanian, 2024). NBM mengintegrasikan data produksi, impor, ekspor, perubahan stok, dan penggunaan untuk pakan ternak serta industri, sehingga menghasilkan angka konsumsi per kapita yang akurat. Data NBM Indonesia telah dikompilasi sejak tahun 1993 dan mencakup lebih dari 60 komoditas pangan utama dengan parameter konsumsi kalori, protein, dan lemak per kapita per hari.

## 2.2 Time Series Forecasting dan Prediksi Konsumsi Pangan

Time Series Forecasting adalah teknik analisis data historis yang diamati dalam urutan waktu tertentu untuk memprediksi nilai-nilai masa depan (Arwansyah et al., 2022). Model ARIMA (Autoregressive Integrated Moving Average) dapat dinyatakan sebagai ARIMA(p,d,q) dengan persamaan (4) yang disajikan sebagai berikut:

$$(1 - \phi_1 L - \phi_2 L^2 - \cdots - \phi_p L^p)(1 - L)^d X_t = (1 + \theta_1 L + \theta_2 L^2 + \cdots + \theta_q L^q)\epsilon_t$$
... (4)

L adalah lag operator, φᵢ adalah autoregressive parameters, θⱼ adalah moving average parameters, d adalah degree of differencing, dan εₜ adalah white noise error term.

Exponential smoothing menggunakan weighted average dari observasi masa lalu dengan formula yang ditunjukkan pada persamaan (5) berikut:

$$S_t = \alpha X_t + (1 - \alpha)S_{t-1}$$
... (5)

Sₜ adalah smoothed value pada waktu t, α adalah smoothing parameter (0 < α < 1), dan Xₜ adalah actual value pada waktu t.

Dalam konteks ketahanan pangan, forecasting konsumsi memiliki karakteristik unik berupa pola musiman yang dipengaruhi oleh faktor musim panen, hari raya keagamaan, dan kondisi ekonomi makro. Konsumsi pangan menunjukkan pola temporal yang kompleks dengan komponen tren jangka panjang, siklus musiman, dan fluktuasi tidak teratur yang memerlukan pendekatan model yang sophisticated (Siregar et al., 2024)

Metode konvensional seperti ARIMA dan exponential smoothing telah lama digunakan untuk prediksi konsumsi pangan, namun memiliki keterbatasan dalam menangkap non-linear relationships dan long-term dependencies yang karakteristik pada data konsumsi pangan (Cahyani et al., 2023). Keterbatasan ini mendorong pengembangan pendekatan machine learning yang lebih advanced untuk meningkatkan akurasi prediksi.

## 2.3 Neural Network dan Deep Learning

Neural Network adalah computational model yang terinspirasi dari struktur dan fungsi jaringan syaraf biologis, terdiri dari nodes (neurons) yang saling terhubung dan mampu belajar pola kompleks dari data training (Benos et al., 2021). Forward propagation pada fully connected layer dinyatakan dengan persamaan (6) dan (7) sebagai berikut:

$$z^{[l]} = W^{[l]}a^{[l-1]} + b^{[l]}$$
... (6)

$$a^{[l]} = g^{[l]}(z^{[l]})$$
... (7)

z[l] adalah linear output layer ke-l, W[l] adalah weight matrix layer ke-l, a[l-1] adalah activation dari layer sebelumnya, b[l] adalah bias vector, dan g[l] adalah activation function.

Activation functions yang umum digunakan ditunjukkan pada persamaan (8), (9), dan (10) sebagai berikut:

$$\sigma(z) = \frac{1}{1 + e^{-z}}$$
... (8)

$$\tanh(z) = \frac{e^z - e^{-z}}{e^z + e^{-z}}$$
... (9)

$$\text{ReLU}(z) = \max(0, z)$$
... (10)

Backpropagation untuk update weights menggunakan persamaan (11) dan (12) yang disajikan sebagai berikut:

$$\frac{\partial L}{\partial W^{[l]}} = \frac{\partial L}{\partial z^{[l]}} \cdot \frac{\partial z^{[l]}}{\partial W^{[l]}} = \delta^{[l]} \cdot (a^{[l-1]})^T$$
... (11)

$$W^{[l]} := W^{[l]} - \alpha \frac{\partial L}{\partial W^{[l]}}$$
... (12)

L adalah loss function, δ[l] adalah error signal layer ke-l, dan α adalah learning rate.

Deep Learning merupakan subset dari machine learning yang menggunakan neural networks dengan multiple hidden layers untuk ekstraksi fitur hierarkis dan pembelajaran representasi yang sophisticated. Arsitektur deep learning telah terbukti superior dalam menangani high-dimensional data dan complex pattern recognition tasks, termasuk aplikasi dalam agricultural domain (Opara et al., 2024). Keunggulan utama deep learning terletak pada kemampuan automatic feature extraction, yang mengeliminasi kebutuhan manual feature engineering yang memakan waktu dan subjektif dalam machine learning pendekatan tradisional.

## 2.4 Long Short-term Memory (LSTM) dan Metode Ensemble

Long Short-term Memory (LSTM) adalah specialized recurrent Neural Network architecture yang dirancang untuk mengatasi vanishing gradient problem dalam traditional RNNs, sehingga mampu menangkap Long-term Dependencies dalam sequential data (Kong et al., 2025). LSTM memiliki cell state mechanism yang memungkinkan selective retention dan forgetting informasi melalui three gates: forget gate, input gate, dan output gate. Struktur sel LSTM secara detail dapat dilihat pada Gambar 1 yang menunjukkan interaksi antar komponen dalam arsitektur LSTM.

![Gambar 1. Struktur Sel LSTM](gambar_placeholder)

Forget Gate menentukan informasi yang akan dihapus dari cell state dengan persamaan (13) yang ditunjukkan sebagai berikut:

$$f_t = \sigma(W_f \cdot [h_{t-1}, x_t] + b_f)$$
... (13)

fₜ adalah forget gate output pada waktu t, σ adalah sigmoid function, Wf adalah weight matrix untuk forget gate, hₜ₋₁ adalah hidden state sebelumnya, xₜ adalah input pada waktu t, dan bf adalah bias vector untuk forget gate.

Input Gate memutuskan nilai-nilai baru yang akan disimpan dalam cell state. Persamaan (14) dan (15) berikut menunjukkan proses tersebut:

$$i_t = \sigma(W_i \cdot [h_{t-1}, x_t] + b_i)$$
... (14)

$$\tilde{C}_t = \tanh(W_C \cdot [h_{t-1}, x_t] + b_C)$$
... (15)

iₜ adalah input gate output, C̃ₜ adalah kandidat nilai cell state baru, Wᵢ, Wc adalah weight matrices, dan bᵢ, bc adalah bias vectors.

Cell state update menggabungkan informasi lama dan baru. Persamaan (16) berikut menunjukkan proses tersebut:

$$C_t = f_t * C_{t-1} + i_t * \tilde{C}_t$$
... (16)

Output Gate menentukan bagian cell state yang akan menjadi output dengan persamaan (17) dan (18) yang disajikan sebagai berikut:

$$o_t = \sigma(W_o \cdot [h_{t-1}, x_t] + b_o)$$
... (17)

$$h_t = o_t * \tanh(C_t)$$
... (18)

oₜ adalah output gate dan hₜ adalah hidden state output pada waktu t.

Dalam konteks ensemble learning untuk time series forecasting, LSTM dapat dikombinasikan dengan robust regression algorithms seperti HuberRegressor. HuberRegressor menggunakan huber loss function yang menggabungkan MSE untuk error kecil dan MAE untuk error besar. Persamaan (19) berikut menunjukkan fungsi tersebut:

$$L_\delta(y, f(x)) = \begin{cases} \frac{1}{2}(y - f(x))^2 & \text{untuk } |y - f(x)| \leq \delta \\ \delta|y - f(x)| - \frac{1}{2}\delta^2 & \text{untuk } |y - f(x)| > \delta \end{cases}$$
... (19)

y adalah nilai aktual, f(x) adalah nilai prediksi, dan δ adalah threshold parameter (biasanya 1.35).

LSTM enhanced ensemble menggabungkan temporal pattern recognition capabilities dari LSTM dengan Robust statistical properties dari Regression Algorithms. Ensemble prediction dihitung menggunakan weighted averaging. Persamaan (20) berikut menunjukkan proses tersebut:

$$\hat{y}_{\text{ensemble}} = \sum_{i=1}^n w_i \cdot \hat{y}_i$$
... (20)

dengan constraint ∑ⁿᵢ₌₁ wᵢ = 1 dan wᵢ ≥ 0, ŷ_ensemble adalah prediksi ensemble, wᵢ adalah weight untuk model ke-i, ŷᵢ adalah prediksi dari model ke-i, dan n adalah jumlah model dalam ensemble.

Adam Optimizer yang umum digunakan untuk training LSTM menggunakan persamaan (21), (22), (23), dan (24) yang disajikan sebagai berikut:

$$m_t = \beta_1 m_{t-1} + (1 - \beta_1)g_t$$
... (21)

$$v_t = \beta_2 v_{t-1} + (1 - \beta_2)g_t^2$$
... (22)

$$\hat{m}_t = \frac{m_t}{1 - \beta_1^t}$$
... (23)

$$\theta_{t+1} = \theta_t - \frac{\alpha}{\sqrt{\hat{v}_t} + \epsilon}\hat{m}_t$$
... (24)

gₜ adalah gradient pada step t, mₜ, vₜ adalah first dan second moment estimates, β₁, β₂ adalah decay rates (biasanya 0.9 dan 0.999), α adalah learning rate, dan ϵ adalah small constant untuk numerical stability.

Hyperparameter optimization dalam ensemble setting mencakup not only LSTM-specific parameters (learning rate, batch size, epochs, window size) tetapi juga ensemble configuration seperti model weights, voting mechanisms, dan regularization parameters untuk preventing overfitting across multiple models.

## 2.5 Metrik Evaluasi Model Prediksi

Evaluasi performa model prediksi menggunakan multiple metrics untuk memastikan comprehensive assessment. Root Mean Square Error (RMSE) mengukur standard deviation dari residuals dan memberikan penalty yang lebih besar untuk large errors. Persamaan (25) berikut menunjukkan formula tersebut:

$$\text{RMSE} = \sqrt{\frac{1}{n}\sum_{i=1}^n (y_i - \hat{y}_i)^2}$$
... (25)

yᵢ adalah nilai aktual, ŷᵢ adalah nilai prediksi, dan n adalah jumlah observasi.

Mean Absolute Error (MAE) memberikan average magnitude of errors tanpa mempertimbangkan direction. Persamaan (26) berikut menunjukkan formula tersebut:

$$\text{MAE} = \frac{1}{n}\sum_{i=1}^n |y_i - \hat{y}_i|$$
... (26)

Mean Absolute Percentage Error (MAPE) mengukur akurasi dalam bentuk persentase, memudahkan interpretasi dengan persamaan (27) yang ditunjukkan sebagai berikut:

$$\text{MAPE} = \frac{100\%}{n}\sum_{i=1}^n \left|\frac{y_i - \hat{y}_i}{y_i}\right|$$
... (27)

RMSE lebih sensitif terhadap outliers dibandingkan MAE karena menggunakan squared errors, sementara MAE lebih robust terhadap outliers dan memberikan equal weight untuk semua errors (Raharjo et al., 2022). MAPE memberikan interpretasi yang intuitif dalam bentuk persentase error, namun dapat menghasilkan nilai infinite atau sangat besar ketika nilai aktual mendekati nol.

## 2.6 Arsitektur Sistem Laravel-FastAPI dan Docker

Implementasi sistem prediksi modern memerlukan arsitektur yang memisahkan concerns antara user interface, business logic, dan machine learning processing dengan deployment strategy yang scalable (Kamil et al., 2024). Laravel menyediakan robust foundation untuk pengembangan aplikasi website dengan features seperti Eloquent ORM, Livewire reactive components, dan Blade templating engine yang memudahkan development dashboard interaktif dan real-time user interactions.

FastAPI merupakan kerangka kerja python website yang dioptimalkan untuk membangun APIs dengan dokumentasi OpenAPI otomatis dan dukungan bawaan untuk pemrograman asinkron. FastAPI sangat cocok untuk machine learning karena integrasi native dengan ekosistem scientific Python (NumPy, Pandas, scikit-learn) dan performa tinggi yang comparable dengan NodeJS dan Go.

Docker memungkinkan lingkungan deployment yang konsisten di seluruh tahap pengembangan, pengujian, dan produksi. Arsitektur berbasis kontainer memastikan reproduibilitas dan portabilitas machine learning, mengeliminasi isu "it works on my machine" yang umum terjadi dalam machine learning deployment (Benos et al., 2021). Pengaturan multi-kontainer dengan Docker Compose memungkinkan pemisahan tanggung jawab antara aplikasi website, layanan machine learning, basis data, dan caching layers.

Session management dan caching optimization menggunakan Redis untuk caching data berkecepatan tinggi dan penyimpanan sesi pengguna, mengurangi beban database dan meningkatkan waktu respons untuk permintaan prediksi yang sering. Arsitektur microservices dengan Laravel sebagai layanan frontend dan FastAPI sebagai layanan backend machine learning memungkinkan skalabilitas independen, fleksibilitas teknologi, dan pemeliharaan yang lebih mudah melalui pengikatan longgar dan kohesi tinggi dalam desain sistem.

## 2.7 Penerapan Machine Learning dalam Prediksi Konsumsi Pangan

Ulasan sistematis terhadap penerapan machine learning dalam ketahanan pangan menunjukkan tren yang semakin meningkat dalam penggunaan algoritma canggih untuk forecasting pertanian (Siregar et al., 2024). Penelitian di India mengimplementasikan LSTM untuk prediksi crop production dengan data sintetis, mencapai akurasi yang secara signifikan lebih baik dibandingkan dengan metode tradisional (Raharjo et al., 2022). Namun, aplikasi pada forecasting konsumsi pangan nasional masih terbatas, terutama di negara berkembang.

Sarku et al. (2023) melakukan tinjauan komprehensif terhadap aplikasi kecerdasan buatan (AI) dalam ketahanan pangan, mengidentifikasi bahwa sebagian besar studi berfokus pada forecasting produksi daripada forecasting konsumsi. Kesenjangan ini menunjukkan peluang untuk mengembangkan model yang berfokus pada konsumsi yang dapat mendukung pengambilan keputusan kebijakan dalam perencanaan ketahanan pangan. Penelitian tersebut juga menekankan pentingnya data historis berkualitas tinggi untuk melatih model yang efektif.

## 2.8 Implementasi LSTM untuk Time Series Forecasting

Arwansyah et al. (2022) melakukan survei mendalam terhadap pendekatan deep learning untuk forecasting deret waktu, mengonfirmasi keunggulan LSTM dalam menangani data berurutan dengan pola temporal yang kompleks. Penelitian tersebut menunjukkan bahwa LSTM sangat efektif untuk forecasting multi-step ahead dengan cakupan forecasting yang panjang, yang sangat relevan untuk perencanaan ketahanan pangan.

Kong et al. (2025) dalam survei menyeluruh terbaru teridentifikasi bahwa variasi LSTM seperti Bidirectional LSTM dan attention-based LSTM menunjukkan hasil yang menjanjikan untuk tugas prediksi yang kompleks. Namun, penelitian tersebut juga menekankan pentingnya pengaturan hyperparameter yang tepat dan pengolahan awal data untuk mencapai performa optimal. (Cahyani et al., 2023) membandingkan performa LSTM dan BiLSTM dalam tugas prediksi, yang menunjukkan bahwa pemilihan model harus disesuaikan dengan karakteristik dari dataset tertentu.

## 2.9 Penelitian Terkait Prediksi Konsumsi Pangan di Indonesia

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

## 2.10 Gap Analysis

Berdasarkan tinjauan literatur sistematis, teridentifikasi beberapa gap kritis dalam penelitian yang ada:

a. Keterbatasan Ruang Lingkup

Mayoritas penelitian fokus pada prediksi tingkat regional atau komoditas tunggal, belum ada yang menangani peramalan konsumsi kalori tingkat nasional menggunakan dataset NBM yang komprehensif (Siregar et al., 2024).

b. Kesenjangan Metodologis

Terbatasnya penerapan arsitektur deep learning mutakhir seperti LSTM untuk forecasting konsumsi pangan dalam konteks negara berkembang (Asian Development Bank, 2023).

c. Pemanfaatan Data

Kurangnya pemanfaatan dataset historis jangka panjang yang tersedia, dengan mayoritas studi menggunakan data jangka pendek (< 10 tahun) yang tidak memadai untuk menangkap pola jangka panjang (Arwansyah et al., 2022)

d. Kesenjangan Implementasi

Kurangnya sistem terintegrasi yang menggabungkan model prediktif dengan antarmuka yang mudah digunakan untuk aplikasi kebijakan (Opara et al., 2024).

## 2.11 Kerangka Konseptual

Kerangka konseptual penelitian ini menggambarkan alur sistematis dari preprocessing data hingga penerapan sistem prediktif terintegrasi. Kerangka penelitian mengadopsi metodologi CRISP-DM dengan fokus spesifik pada implementasi LSTM dan arsitektur microservices (Schröer et al., 2021).

Input utama penelitian berupa data NBM historis (1993-2024) yang mencakup time series konsumsi kalori per kapita, akan diproses melalui tahap persiapan data komprehensif meliputi normalisasi, feature scaling, dan sequence generation. Model ensemble LSTM akan dikembangkan dan dilatih dengan konfigurasi hyperparameter optimal untuk mencapai target akurasi MAPE < 10%.

Model yang telah tervalidasi akan diintegrasikan dalam arsitektur microservices dengan layanan frontend Laravel untuk antarmuka pengguna dan visualisasi dashboard, layanan backend FastAPI untuk serving model machine learning, dan database MySQL untuk persistensi data. Arsitektur ini memungkinkan penerapan yang dapat diskalakan dan kemampuan prediksi real-time yang mendukung pengambilan keputusan berbasis bukti dalam perencanaan ketahanan pangan (Kamil et al., 2024).

Alur kerja sistem dimulai dari permintaan pengguna melalui antarmuka website Laravel, yang kemudian mengirim panggilan API ke layanan FastAPI untuk inferensi model. Hasil dari prediksi akan di-cache dalam database MySQL dan ditampilkan melalui dashboard visualisasi interaktif. Kerangka konseptual ini memberikan peta jalan yang jelas untuk mencapai tujuan penelitian sambil memastikan ketepatan teoritis dan penerapan praktis dari sistem yang dikembangkan.

---

# BAB III  
# METODE PENELITIAN

## 3.1. Data dan Alat Penelitian

Penelitian ini memerlukan spesifikasi data, perangkat lunak, perangkat keras, dan lingkungan pengembangan yang tepat untuk mendukung implementasi model LSTM enhanced ensemble secara optimal. Bagian ini menjelaskan secara detail komponen-komponen fundamental yang digunakan dalam pengembangan sistem prediksi konsumsi kalori berbasis machine learning, mulai dari sumber data historis NBM Indonesia hingga infrastruktur teknologi yang mendukung arsitektur microservices.

a. Data Penelitian

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

b. Perangkat Lunak

Penelitian menggunakan kombinasi teknologi untuk pengembangan sistem prediksi. Untuk pengembangan machine learning, bahasa pemrograman utama yang digunakan adalah Python 3.8+ dengan library TensorFlow/Keras untuk implementasi model LSTM, Scikit-learn untuk algoritma ensemble dan preprocessing, serta Pandas dan NumPy untuk manipulasi dan analisis data. Visualisasi data dilakukan menggunakan Matplotlib dan Seaborn.

Pengembangan aplikasi website menggunakan framework Laravel 11 sebagai backend dengan Livewire 3 untuk komponen frontend yang reaktif. Database management system yang digunakan adalah MySQL 8.0, sedangkan untuk layanan machine learning inference menggunakan FastAPI sebagai microservice.

Untuk deployment dan DevOps, penelitian menggunakan Docker untuk containerization dan konsistensi environment, Docker Compose untuk orkestrasi multi-service, serta Git untuk version control dan collaborative development.

c. Perangkat Keras

Pengembangan dan pengujian sistem dilakukan menggunakan laptop MSI GF63 Thin 10UC dengan prosesor Intel Core i5-10500H (6 cores, 12 logical processors) dengan base speed 2.50 GHz, memori 16 GB RAM DDR4 2933 MT/s untuk training model dan pemrosesan data, serta storage SSD KINGSTON OM8PCP3512F-AI1 kapasitas 477 GB untuk menyimpan dataset dan model artifacts. GPU NVIDIA GeForce RTX 3050 Laptop GPU dengan 4 GB dedicated memory digunakan untuk mempercepat proses training model LSTM.

Untuk deployment production, sistem didesain agar dapat berjalan pada infrastruktur cloud dengan spesifikasi yang dapat disesuaikan sesuai kebutuhan load dan performance requirements.

d. Lingkungan Pengembangan

Penelitian menggunakan Visual Studio Code sebagai code editor utama dan Jupyter Notebook untuk exploratory data analysis serta prototyping. phpMyAdmin digunakan untuk desain dan manajemen database, sedangkan Postman digunakan untuk testing dan validasi API. GitHub digunakan sebagai platform hosting repository dan kolaborasi pengembangan.

## 3.2. Metode Penelitian

Penelitian ini menggunakan pendekatan kuantitatif eksperimental dengan metode Research and Development (RnD) yang terintegrasi dengan framework CRISP-DM untuk pengembangan model machine learning. Pendekatan kuantitatif dipilih karena penelitian melibatkan analisis data numerik time series konsumsi kalori dan evaluasi performa model menggunakan metrik statistik (Sukarna & Ansori, 2022).

Research and Development (RnD) adalah metode penelitian yang bertujuan untuk menghasilkan produk tertentu dan menguji keefektifan produk tersebut (Okpatrioka, 2023). Dalam konteks penelitian ini, produk yang dikembangkan berupa sistem prediksi konsumsi kalori berbasis LSTM enhanced ensemble yang terintegrasi dengan antarmuka website untuk stakeholder pemerintah.

Penelitian mengadopsi experimental design dengan controlled variables untuk menguji performa berbagai konfigurasi model LSTM dan membandingkannya dengan metode baseline. Integrasi RnD dengan CRISP-DM memungkinkan pendekatan sistematis dari business understanding hingga deployment yang sesuai dengan standar industri machine learning.

Tahapan RnD yang dimodifikasi dengan integrasi CRISP-DM terdiri dari sepuluh tahap sistematis yang digambarkan pada Gambar 2 yang mencakup proses pengembangan sistematis dari penelitian awal hingga diseminasi produk final. Metodologi ini memiliki karakteristik unik dengan feedback loops antar tahap untuk memastikan iterasi perbaikan yang berkelanjutan dan integrasi CRISP-DM pada tahap Early Test untuk standardisasi pengembangan model machine learning.

![Gambar 2. Kerangka Metodologi RnD Terintegrasi dengan CRISP-DM](gambar_placeholder)

Setiap tahap RnD dalam penelitian ini dijelaskan sebagai berikut:

a. Research and Collection Preliminary

Tahap ini melakukan kajian pustaka mendalam terkait metode prediksi time series, algoritma LSTM, dan ensemble learning dalam konteks prediksi konsumsi pangan. Identifikasi kebutuhan pengguna sistem dilakukan melalui analisis stakeholder dan review dokumen kebijakan ketahanan pangan. Pengumpulan data historis NBM Indonesia periode 1993-2024 sebagai foundation dataset untuk pengembangan model.

b. Research Planning

Menyusun blueprint arsitektur sistem prediksi yang mencakup komponen machine learning dan antarmuka website. Menentukan algoritma utama (LSTM enhanced ensemble) dan teknologi pendukung (Laravel, FastAPI, dan Docker). Merancang kerangka metodologi penelitian dengan mengadopsi CRISP-DM sebagai kerangka kerja pengembangan model machine learning.

c. Early Product Development

Membangun struktur dasar model LSTM ensemble dan merancang antarmuka aplikasi berbasis website. Implementasi preprocessing pipeline untuk data NBM dan pengembangan baseline models untuk comparison. Tahap ini menghasilkan prototipe awal sistem prediksi.

d. Expert Validation

Melakukan evaluasi rancangan sistem bersama pakar machine learning dan domain expert ketahanan pangan. Validasi mencakup review arsitektur model, kesesuaian teknik preprocessing, dan relevansi dengan kebutuhan praktis dalam perencanaan ketahanan pangan.

e. Product Revision

Melakukan penyempurnaan rancangan berdasarkan feedback dari tahap validasi. Revisi dapat mencakup modifikasi arsitektur model, perbaikan preprocessing pipeline, atau penyesuaian antarmuka pengguna sesuai dengan saran expert.

f. Early Test (Implementasi Model LSTM Enhanced Ensemble)

Tahap ini merupakan implementasi lengkap model LSTM enhanced ensemble dengan metodologi CRISP-DM yang diintegrasikan dalam kerangka RnD. Implementasi mengikuti fase-fase CRISP-DM secara sistematis untuk memastikan structured progression dari data understanding hingga model evaluation. Pengujian dilakukan terhadap performa model menggunakan data training dan validation dengan metrik evaluasi RMSE, MAE, dan MAPE.

g. Product Revision

Menyempurnakan model dan sistem berdasarkan hasil pengujian tahap sebelumnya. Optimisasi hyperparameter, perbaikan ensemble configuration, dan enhancement antarmuka pengguna berdasarkan hasil testing.

h. Field Test

Melakukan pengujian komprehensif menggunakan data testing (2020-2024) dalam kondisi real-world scenarios. Testing mencakup accuracy assessment, performa sistem, dan usability evaluation dengan potential users.

i. Final Product Revision

Melakukan penyempurnaan akhir sistem berdasarkan evaluasi dari uji coba lapangan. Finalisasi konfigurasi model, sistem deployment, dan dokumentasi lengkap untuk production use.

j. Dissemination

Menyusun dokumentasi lengkap sistem, panduan penggunaan, dan laporan penelitian. Persiapan untuk knowledge transfer dan potential adoption oleh stakeholder terkait dalam perencanaan ketahanan pangan.

Tahapan implementasi model LSTM enhanced ensemble dalam tahap Early Test mengikuti kerangka kerja CRISP-DM yang digambarkan pada Gambar 3, menunjukkan systematic progression dari data understanding hingga model deployment (Schröer et al., 2021).

![Gambar 3. Diagram Alur CRISP-DM](gambar_placeholder)

Tahapan implementasi LSTM mengikuti kerangka kerja CRISP-DM sebagai berikut:

a. Business Understanding

Fase pertama dari metodologi CRISP-DM fokus pada pemahaman mendalam terhadap konteks ketahanan pangan Indonesia dan persyaratan khusus untuk sistem prediksi yang akan dikembangkan. Tahap ini dimulai dengan analisis stakeholder untuk mengidentifikasi pihak-pihak kunci seperti Badan Pangan Nasional, Kementerian Pertanian, dan para pengambil kebijakan, serta memahami proses pengambilan keputusan mereka dalam perencanaan ketahanan pangan.

Problem definition dilakukan secara sistematis untuk mendefinisikan persyaratan prediksi secara jelas, menetapkan target akurasi MAPE kurang dari 10% berdasarkan standar industri dengan formula yang ditunjukkan pada persamaan (28) berikut:

$$\text{MAPE} = \frac{100\%}{n}\sum_{i=1}^n \left|\frac{y_i - \hat{y}_i}{y_i}\right|$$
... (28)

yᵢ adalah nilai aktual konsumsi kalori, ŷᵢ adalah nilai prediksi, dan n adalah jumlah observasi.

Kriteria sukses ditetapkan mencakup measurable objectives untuk performa teknis melalui metrik akurasi dan dampak bisnis dalam bentuk improved planning efficiency. Risk assessment juga dilakukan untuk mengidentifikasi potensi tantangan dalam kualitas data, model complexity, dan integration requirements.

b. Data Understanding

Data yang digunakan dalam penelitian ini bersumber dari Neraca Bahan Makanan (NBM) Indonesia periode 1993-2024 yang diperoleh dari Badan Pangan Nasional, Pusat Data dan Sistem Informasi Kementerian Pertanian, dan Badan Pusat Statistika. Dataset mencakup 31 tahun data historis dengan 41,316 records transaksi NBM yang mencakup 120 komoditas pangan dari 11 kelompok. Melalui proses agregasi, data transaksi individual ini menghasilkan 372 data points time series bulanan untuk konsumsi kalori nasional, yang memberikan pondasi yang solid untuk pengembangan model prediksi time series. Target variabel dalam penelitian ini adalah konsumsi kalori per kapita per hari agregat nasional yang diukur dalam satuan kkal/kapita/hari.

Dataset NBM memiliki resolusi temporal bulanan dengan pola musiman yang jelas, setiap record transaksi mencakup data produksi, impor, ekspor, dan utilisasi untuk masing-masing dari 120 komoditas pangan. Perhitungan konsumsi kalori menggunakan formula NBM ditunjukkan pada persamaan (29) berikut:

$$\text{Kalori per kapita per hari} = \frac{\text{Konsumsi per kapita (kg/hari)} \times \text{Faktor Konversi Energi (kkal/100g)}}{10}$$
... (29)

Data quality assessment menunjukkan adanya missing values yang diestimasi kurang dari 5%, outliers akibat economic shocks, dan potential measurement errors yang memerlukan treatment khusus.

Exploratory Data Analysis (EDA) dilakukan untuk memahami karakteristik dataset secara komprehensif menggunakan statistical measures. Persamaan (30) berikut menunjukkan formula tersebut:

$$\text{Coefficient of Variation} = \frac{\sigma}{\mu} \times 100\%$$
... (30)

σ adalah standard deviation dan μ adalah mean konsumsi kalori.

Analisis temporal menggunakan Augmented Dickey-Fuller test dengan hipotesis:

• H₀: Time series memiliki unit root (non-stationary)
• H₁: Time series adalah stationary

Correlation analysis menggunakan Pearson correlation coefficient. Persamaan (31) berikut menunjukkan formula tersebut:

$$r = \frac{\sum_{i=1}^n (x_i - \bar{x})(y_i - \bar{y})}{\sqrt{\sum_{i=1}^n (x_i - \bar{x})^2 \sum_{i=1}^n (y_i - \bar{y})^2}}$$
... (31)

Outlier detection menggunakan IQR method dengan threshold Q₁ - 1.5 × IQR dan Q₃ + 1.5 × IQR, serta Z-score analysis dengan threshold |z| > 3.

c. Data Preparation

Tahap data preparation merupakan fase kritkal yang menentukan kualitas input untuk model LSTM. Data cleaning dimulai dengan handling missing values menggunakan forward-fill method untuk maintaining temporal continuity, dengan validasi terhadap pola musiman untuk memastikan imputasi tidak mengubah karakteristik fundamental dari time series.

Outlier treatment menggunakan winsorization dengan persamaan (32) yang ditunjukkan sebagai berikut:

$$x_{\text{winsorization}} = \begin{cases} P_1 & \text{jika } x < P_1 \\ x & \text{jika } P_1 \geq x \geq P_{99} \\ P_{99} & \text{jika } x > P_{99} \end{cases}$$
... (32)

P₁ dan P₉₉ adalah 1st dan 99th percentiles.

Feature engineering menggunakan cyclical encoding untuk temporal features. Persamaan (33) dan (34) berikut menunjukkan formula tersebut:

$$\text{Month}_{\sin} = \sin\left(\frac{2\pi \times \text{month}}{12}\right)$$
... (33)

$$\text{Month}_{\cos} = \cos\left(\frac{2\pi \times \text{month}}{12}\right)$$
... (34)

Rolling statistics dihitung dengan Moving Average. Persamaan (35) berikut menunjukkan formula tersebut:

$$MA_t = \frac{1}{k}\sum_{i=0}^{k-1} x_{t-i}$$
... (35)

k adalah window size (3, 6, 12 bulan).

Data preprocessing menggunakan StandardScaler dan RobustScaler dengan persamaan (36) dan (37) yang ditunjukkan sebagai berikut:

$$z = \frac{x - \mu}{\sigma}$$
... (36)

$$z = \frac{x - \text{median}}{Q_3 - Q_1}$$
... (37)

Sequence generation menggunakan sliding window dengan window size yang akan dioptimasi melalui grid search.

Dataset NBM Indonesia yang mencakup 31 tahun (1993-2024) dengan 372 titik data bulanan dibagi secara kronologis untuk menjaga integritas temporal dan mencegah data leakage yang dapat terjadi pada random split. Strategi pembagian data divisualisasikan pada Gambar 4 yang menunjukkan distribusi temporal dataset.

![Gambar 4. Strategi Pembagian Data NBM Indonesia](gambar_placeholder)

Pembagian data mengikuti proporsi 70:15:15 dengan alasan sebagai berikut:

• Data Pelatihan (70%, 1993-2015)

Periode 23 tahun dengan 276 titik data bulanan digunakan untuk melatih model ensemble LSTM. Periode ini mencakup berbagai kondisi ekonomi dan pangan Indonesia, termasuk krisis moneter 1998 dan periode recovery, memberikan variasi pola yang cukup untuk pembelajaran model.

• Data Validasi (15%, 2016-2019)

Periode 4 tahun dengan 48 titik data digunakan untuk validasi model dan tuning hyperparameter. Periode ini dipilih karena merepresentasikan kondisi ekonomi yang relatif stabil (pra-pandemi), sehingga cocok untuk optimasi parameter model tanpa bias dari kondisi ekstrem.

•Data Pengujian (15%, 2020-2024)

Periode 4 tahun terakhir dengan 48 titik data digunakan untuk evaluasi final performa model. Periode ini sengaja dipilih karena mencakup kondisi challenging seperti pandemi COVID-19, yang menguji robustness model terhadap shock ekonomi dan gangguan rantai pasokan pangan.

Untuk optimasi hyperparameter dan pemilihan konfigurasi model terbaik, penelitian ini menerapkan Time Series Cross-Validation dengan teknik expanding window pada data pelatihan (1993-2015). Metode ini divisualisasikan pada Gambar 5 yang menunjukkan mekanisme validasi bertahap.

![Gambar 5. Time Series Cross-Validation Expanding Window](gambar_placeholder)

Expanding window cross-validation dilakukan dengan tahapan sebagai berikut:

• Fold 1, training pada 1993-2005 (12 tahun), validasi pada 2006
• Fold 2, training pada 1993-2007 (14 tahun), validasi pada 2008
• Fold 3, training pada 1993-2009 (16 tahun), validasi pada 2010
• Fold 4, training pada 1993-2011 (18 tahun), validasi pada 2012
• Fold 5, training pada 1993-2013 (20 tahun), validasi pada 2014
• Proses berlanjut hingga fold terakhir memakai data hingga 2015

Setiap fold menambahkan 1-2 tahun data pelatihan untuk mensimulasikan kondisi asli sehingga model terus belajar dari data historis yang bertambah. Metode ini memberikan evaluasi robust terhadap performa model pada berbagai periode waktu dan membantu mendeteksi overfitting serta memilih hyperparameter optimal yang generalize dengan baik. Performa model pada setiap fold akan dievaluasi menggunakan metrik RMSE, MAE, dan MAPE untuk memastikan konsistensi akurasi prediksi across different time periods.

d. Modeling

Desain arsitektur model LSTM enhanced ensemble menggabungkan LSTM untuk temporal pattern extraction dengan robust regression algorithms. Arsitektur LSTM menggunakan persamaan gate mechanisms sebagaimana ditunjukkan pada persamaan (38) hingga (43) berikut.

Forget Gate:

$$f_t = \sigma(W_f \cdot [h_{t-1}, x_t] + b_f)$$
... (38)

Input Gate:

$$i_t = \sigma(W_i \cdot [h_{t-1}, x_t] + b_i)$$
... (39)

$$\tilde{C}_t = \tanh(W_C \cdot [h_{t-1}, x_t] + b_C)$$
... (40)

Cell State Update:

$$C_t = f_t * C_{t-1} + i_t * \tilde{C}_t$$
... (41)

Output Gate:

$$o_t = \sigma(W_o \cdot [h_{t-1}, x_t] + b_o)$$
... (42)

$$h_t = o_t * \tanh(C_t)$$
... (43)

Ensemble integration menggunakan weighted averaging sebagaimana disajikan pada persamaan (44):

$$\hat{y}_{\text{ensemble}} = \sum_{i=1}^n w_i \cdot \hat{y}_i$$
... (44)

dengan constraint ∑ⁿᵢ₌₁ wᵢ = 1 dan wᵢ ≥ 0.

HuberRegressor menggunakan huber loss function yang ditunjukkan pada persamaan (45) berikut:

$$L_\delta(y, f(x)) = \begin{cases} \frac{1}{2}(y - f(x))^2 & \text{untuk } |y - f(x)| \leq \delta \\ \delta|y - f(x)| - \frac{1}{2}\delta^2 & \text{untuk } |y - f(x)| > \delta \end{cases}$$
... (45)

Adam Optimizer digunakan untuk training dengan persamaan (46) yang disajikan sebagai berikut:

$$\theta_{t+1} = \theta_t - \frac{\alpha}{\sqrt{\hat{v}_t} + \epsilon}\hat{m}_t$$
... (46)

m̂ₜ dan v̂ₜ adalah bias-corrected first dan second moment estimates.

e. Evaluation

Evaluasi performa model menggunakan multiple metrics untuk comprehensive assessment.

• Root Mean Square Error (RMSE) untuk measuring prediction accuracy dengan emphasis pada large errors sebagaimana ditunjukkan pada persamaan (47) berikut:

$$\text{RMSE} = \sqrt{\frac{1}{n}\sum_{i=1}^n (y_i - \hat{y}_i)^2}$$
... (47)

• Mean Absolute Error (MAE) memberikan robust metric untuk average prediction deviation sebagaimana disajikan pada persamaan (48):

$$\text{MAE} = \frac{1}{n}\sum_{i=1}^n |y_i - \hat{y}_i|$$
... (48)

• Mean Absolute Percentage Error (MAPE) menjadi metric utama dengan target < 10% untuk business acceptability berdasarkan praktik standar industri dan benchmarks dari literatur terkait dengan persamaan (49) yang ditunjukkan sebagai berikut:

$$\text{MAPE} = \frac{100\%}{n}\sum_{i=1}^n \left|\frac{y_i - \hat{y}_i}{y_i}\right|$$
... (49)

• R-Squared untuk measuring explained variance proportion sebagaimana disajikan pada persamaan (50):

$$R^2 = 1 - \frac{SS_{\text{res}}}{SS_{\text{tot}}} = 1 - \frac{\sum_{i=1}^n (y_i - \hat{y}_i)^2}{\sum_{i=1}^n (y_i - \bar{y})^2}$$
... (50)

• Directional Accuracy untuk percentage of correct trend predictions dengan persamaan (51) yang ditunjukkan sebagai berikut:

$$DA = \frac{1}{n-1}\sum_{i=1}^n I[(y_i - y_{i-1})(\hat{y}_i - \hat{y}_{i-1}) > 0]$$
... (51)

Indicator function dilambangkan dengan I[·].

Validation strategy menggunakan time series cross-validation dengan expanding window, walk-forward validation untuk real-world simulation, dan robustness testing under extreme scenarios. Model interpretability analysis menggunakan SHAP values untuk feature importance dan residual analysis untuk error pattern identification.

f. Deployment

Implementasi sistem menggunakan containerized microservices architecture dengan separation of concerns. Frontend service dikembangkan menggunakan Laravel dengan Livewire components untuk reactive interface. Backend machine learning service menggunakan FastAPI dengan RESTful API endpoints untuk model serving.

• Arsitektur Sistem Web

Sistem SIKOLBIA dibangun dengan arsitektur microservices berbasis Docker yang memisahkan concerns antara presentation layer (Laravel), business logic, dan machine learning service (FastAPI), sebagaimana divisualisasikan pada Gambar 6.

![Gambar 6. Arsitektur Microservices SIKOLBIA](gambar_placeholder)

Arsitektur sistem menggunakan request-response pattern yang dimulai dari user request melalui Laravel Frontend, kemudian Laravel Controller melakukan HTTP request ke FastAPI ML Service untuk inference prediksi. FastAPI memuat model LSTM Enhanced Ensemble dan melakukan prediksi, kemudian mengembalikan JSON response dengan prediksi dan confidence interval yang akan ditampilkan Laravel dalam bentuk tabel dan grafik interaktif.

Komponen utama arsitektur meliputi Nginx sebagai reverse proxy dan web server pada port 8000, Laravel App dengan PHP 8.3 dan Livewire 3 untuk reactive components, MySQL sebagai relational database pada port 3306 untuk data persistence, Redis sebagai in-memory caching pada port 6379 untuk session dan query cache, FastAPI ML dengan Python 3.10 pada port 8082 untuk model inference.

• Pengguna Sistem dan Hak Akses

Sistem SIKOLBIA dirancang untuk melayani empat kategori pengguna dengan kebutuhan dan hak akses yang berbeda menggunakan role-based access control (RBAC) dengan Spatie Laravel Permission.

Administrator merupakan pengelola sistem dari Kementerian Pertanian atau lembaga terkait yang memiliki full CRUD untuk semua data (user, NBM, kelompok, komoditi, alamat), user management, permission assignment, dan system monitoring. Output yang dihasilkan berupa dashboard admin dengan user activity logs, system health metrics, data statistics, dan audit trails.

Pemerintah mencakup pejabat atau staf dari Kementerian Pertanian, Bappenas, atau BPKP yang dapat menjalankan prediksi, melihat data historis, dan export prediction reports dalam format Excel atau PDF. Output yang dihasilkan meliputi prediksi konsumsi kalori 1-12 bulan ke depan dengan LSTM Enhanced Ensemble, confidence interval (±15%) untuk setiap prediksi, trend indicator (↗ naik / ↘ turun / → stabil) berdasarkan data historis, comparison chart antara data historis dan prediksi, serta export laporan untuk presentasi kebijakan.

Akademisi merupakan peneliti, dosen, atau mahasiswa dari universitas atau lembaga penelitian yang dapat melihat data historis, melakukan filter dan query NBM, export data dalam format CSV atau Excel, dan mengakses visualization tools. Output yang dihasilkan berupa dataset NBM untuk analisis statistik, time series plots, correlation matrix, dan data dictionary.

Pengunjung adalah masyarakat umum yang tertarik dengan ketahanan pangan dengan akses read-only ke dashboard publik dan view aggregated statistics. Output berupa ringkasan konsumsi pangan nasional dan infografis ketahanan pangan.

**Tabel 3. Matriks Hak Akses dan Fitur Sistem**

| Fitur | Admin | Pemerintah | Akademisi | Pengunjung |
|-------|-------|------------|-----------|------------|
| Lihat Dashboard | ✓ | ✓ | ✓ | ✓ (terbatas) |
| Manajemen User | ✓ | ✗ | ✗ | ✗ |
| Data Master NBM | ✓ | ✗ | ✗ | ✗ |
| Kelompok dan Komoditi | ✓ | ✗ | ✗ | ✗ |
| Menjalankan Prediksi LSTM | ✓ | ✓ | ✗ | ✗ |
| Lihat Data Historis | ✓ | ✓ | ✓ | ✗ |
| Export Data | ✓ | ✓ | ✓ | ✗ |

• Alur Kerja Sistem

Sistem SIKOLBIA mengimplementasikan workflow multi-tier dengan separation of concerns sebagaimana divisualisasikan pada Gambar 7 dengan flowchart 5 kolom untuk menunjukkan interaksi antar pengguna dan sistem.

![Gambar 7. Flowchart Sistem SIKOLBIA](gambar_placeholder)

Alur sistem dimulai dari tahap autentikasi dimana user melakukan login dengan email dan password, kemudian sistem memverifikasi credentials dan role menggunakan Spatie Permission. Setelah berhasil login, middleware melakukan otorisasi dengan memeriksa permission untuk setiap route berdasarkan role pengguna.

Pada proses prediksi, pengguna Pemerintah memilih parameter yang terdiri dari kelompok komoditas, jenis komoditi, dan jumlah bulan prediksi yang diinginkan. Laravel Controller kemudian melakukan query data historis 6 bulan terakhir dari database MySQL. Setelah data terkumpul, Controller melakukan HTTP POST request ke FastAPI endpoint `/predict` dengan payload dalam format JSON. FastAPI menerima request, memuat model LSTM Enhanced Ensemble, dan melakukan inference untuk menghasilkan prediksi dengan confidence interval (±15%). Hasil prediksi dikembalikan dalam bentuk JSON response ke Laravel, yang kemudian menampilkan hasil berupa tabel prediksi, grafik tren, dan informasi model. Pengguna dapat melakukan export hasil prediksi atau data historis dalam format Excel atau PDF.

• Struktur Database

Database SIKOLBIA menggunakan MySQL 8.0 dengan normalisasi hingga 3NF untuk menghindari redundansi data. Entity Relationship Diagram (ERD) divisualisasikan pada Gambar 8.

![Gambar 8. Entity Relationship Diagram (ERD) Modul Konsumsi Pangan NBM](gambar_placeholder)

Struktur database modul konsumsi pangan NBM dirancang dengan enam entitas utama yang membentuk sistem manajemen data terintegrasi untuk mendukung prediksi konsumsi kalori berbasis machine learning. Sistem autentikasi dan otorisasi diimplementasikan melalui tabel `users` yang menyimpan informasi dasar pengguna seperti nama, email, dan password terenkripsi. Mekanisme Role-Based Access Control (RBAC) menggunakan Spatie Laravel Permission diimplementasikan melalui relasi many-to-many antara `users` dan `roles` yang dihubungkan melalui pivot table `model_has_roles` dengan polymorphic relationship untuk fleksibilitas assignment role. Selanjutnya, relasi many-to-many antara `roles` dan `permissions` melalui pivot table `role_has_permissions` memungkinkan definisi granular hak akses untuk setiap role seperti Admin yang memiliki akses penuh, Pemerintah yang dapat menjalankan prediksi, Akademisi yang dapat mengakses data historis, dan Pengunjung dengan akses terbatas hanya untuk melihat dashboard publik.

Hierarki klasifikasi komoditas pangan direpresentasikan melalui dua tabel yang saling berelasi. Tabel `kelompok` berfungsi sebagai entitas induk yang mengorganisir komoditas pangan ke dalam sepuluh kategori utama dengan kode 01 hingga 10, mencakup Padi-padian, Makanan berpati, Gula, Buah Biji Berminyak, Buah-buahan, Sayur-sayuran, Daging, Telur, Susu, dan Minyak dan Lemak. Setiap record kelompok memiliki kode unik dan nama deskriptif yang digunakan sebagai referensi dalam seluruh sistem. Tabel `komoditi` sebagai entitas anak menyimpan 120 komoditas spesifik yang masing-masing terhubung ke kelompok induknya melalui field `kode_kelompok`, membentuk relasi many-to-one yang memfasilitasi query hierarkis. Sebagai ilustrasi, komoditi "Beras" dengan kode 0101 berasosiasi dengan kelompok "Padi-padian" yang berkode 01, memudahkan agregasi data pada level kelompok maupun detail komoditas individual.

Inti dari sistem data historis terletak pada tabel `transaksi_nbms` yang menyimpan 41.316 records komprehensif mencakup periode 31 tahun dari 1993 hingga 2024. Setiap transaksi merekam komponen lengkap Neraca Bahan Makanan termasuk masukan produksi domestik, volume impor dan ekspor, perubahan stok, serta alokasi untuk berbagai keperluan seperti pakan ternak, bibit, konsumsi makanan manusia, penggunaan bukan makanan, susut tercecer, dan kategori penggunaan lain. Tabel ini mereferensikan kelompok dan komoditas melalui field `kode_kelompok` dan `kode_komoditi` untuk keperluan klasifikasi, namun secara sengaja tidak menggunakan foreign key constraint mengingat karakteristik data historis yang harus tetap preserved bahkan jika master data kelompok atau komoditas mengalami perubahan atau penghapusan di masa mendatang. Status angka pada setiap transaksi dikategorikan sebagai tetap, sementara, atau sangat sementara untuk mengindikasikan tingkat reliabilitas data sesuai dengan siklus revisi publikasi BPS.

Sistem tracking prediksi diimplementasikan melalui tabel `prediction_histories` yang mencatat setiap aktivitas prediksi yang dilakukan pengguna beserta hasil yang dihasilkan model LSTM Enhanced Ensemble. Tabel ini menerapkan foreign key constraint ke tabel `users` dengan konfigurasi ON DELETE CASCADE untuk memastikan integritas referensial ketika akun pengguna dihapus dari sistem. Setiap record prediksi menyimpan tidak hanya kode kelompok dan komoditas yang diprediksi, tetapi juga nama lengkap untuk keperluan display tanpa perlu join query tambahan. Data prediksi yang dihasilkan model disimpan dalam format JSON pada field `prediction_data` yang berisi array nilai prediksi untuk 6 bulan ke depan, sementara `historical_data` menyimpan 6 data point historis yang digunakan sebagai input model untuk keperluan reprodusibilitas dan audit. Field `confidence_intervals` juga dalam format JSON menyimpan batas bawah dan atas interval kepercayaan dengan margin ±15% sesuai standar industri untuk forecasting ketahanan pangan. Fitur tambahan seperti `notes` memungkinkan pengguna menambahkan catatan pada prediksi tertentu, sementara flag `is_bookmarked` memfasilitasi penandaan prediksi penting untuk akses cepat di kemudian hari.

• Basis Prediksi Konsumsi Pangan

Prediksi konsumsi kalori harian dalam sistem SIKOLBIA didasarkan pada data historis NBM 6 bulan terakhir untuk setiap komoditas yang diambil dari tabel `transaksi_nbms`. Data yang digunakan mencakup field `masukan` (produksi domestik), `impor` (volume impor), `ekspor` (volume ekspor), `perubahan_stok` (delta stok), dan `makanan` (konsumsi untuk pangan manusia) yang semuanya dalam satuan ton.

Kalori konsumsi harian per kapita dihitung menggunakan formula NBM sebagaimana ditunjukkan pada persamaan (52), (53), dan (54):

$$\text{Ketersediaan (ton)} = \text{Masukan} + \text{Impor} - \text{Ekspor} \pm \Delta\text{Stok}$$
... (52)

$$\text{Makanan (kg)} = \text{Makanan (ton)} \times 1000$$
... (53)

$$\text{Kalori/Hari} = \frac{(\text{Makanan kg} / \text{Populasi} / 365) \times \text{Kalori per 100g}}{100}$$
... (54)

Model LSTM menggunakan fitur temporal yang terdiri dari tahun yang di-encode sebagai numeric feature untuk menangkap tren jangka panjang, rolling statistics berupa Moving Average untuk menangkap tren historis, serta lag features yang merupakan nilai kalori pada periode sebelumnya sebagai input sequence.

Model LSTM Enhanced Ensemble merupakan kombinasi dari beberapa algoritma yang mencakup LSTM Layer untuk menangkap long-term dependencies dan pola temporal kompleks, HuberRegressor yang robust terhadap outliers dalam data NBM, weighted averaging dengan ensemble weights yang dioptimasi melalui grid search untuk kombinasi optimal, serta trend analysis menggunakan linear regression untuk extrapolasi jangka panjang dan dampening negative trends.

Confidence interval dihitung dengan margin ±15% dari nilai prediksi yang merupakan industri standar untuk food security forecasting. Uncertainty quantification menggunakan Monte Carlo Simulation dengan 1000 iterations untuk robustness estimation, historical standard deviation berdasarkan error distribution pada validation set, dengan formula Lower Bound = Prediction × 0.85 dan Upper Bound = Prediction × 1.15.

Validasi model dilakukan menggunakan time series cross-validation dengan expanding window untuk memastikan model tidak overfitting, walk-forward validation untuk simulasi real-world forecasting pada test set 2020-2024, serta backtesting untuk menguji performance under COVID-19 shock pada periode 2020-2021 sebagai robustness check.

• Metodologi Pengujian Sistem

Pengujian sistem SIKOLBIA menggunakan kombinasi metode untuk memastikan fungsionalitas, akurasi, dan usability. Unit testing menggunakan PHPUnit untuk Laravel dan Pytest untuk FastAPI dengan target code coverage lebih dari 80%. Scope testing mencakup individual functions seperti controller methods, API endpoints, dan helper functions dengan contoh test seperti `test_prediction_endpoint_valid_input()`, `test_nbm_calculation_formula()`, dan `test_permission_middleware()`.

Integration testing menggunakan Laravel HTTP Tests dan Postman Collection untuk menguji interaksi antar komponen. Target testing mencakup komunikasi antara Laravel dengan MySQL melalui Eloquent queries, Laravel dengan FastAPI melalui HTTP requests dengan timeout handling, FastAPI dengan LSTM Model untuk inference pipeline, serta authentication and authorization flow yang dimulai dari login hingga permission check dan access resource.

Functional testing menggunakan manual testing dan automated testing dengan Pest untuk menguji fitur sesuai requirements. Test cases mencakup login Admin dengan valid credentials yang harus redirect ke Admin Dashboard, login failed dengan invalid credentials yang harus menampilkan error message, create NBM data dengan complete form yang harus menampilkan success message dan data tersimpan di database, run prediction dengan parameter valid (kelompok='01', komoditi='0101', bulan=6) yang harus menghasilkan 6 predictions with CI, run prediction untuk empty data commodity yang harus menampilkan warning message, export Excel untuk NBM dataset yang harus berhasil download file .xlsx, serta permission check dimana Akademisi mencoba run prediction harus mendapat access denied dengan status code 403.

Performance testing menggunakan Apache JMeter dan Laravel Telescope untuk menguji metrik sistem. Target metrics mencakup response time kurang dari 3 detik untuk endpoint `/predict`, throughput lebih dari 100 requests per minute, concurrent users support minimal 50 simultaneous users tanpa degradasi performa, dan database query time kurang dari 500ms untuk complex joins. Load scenarios didesain untuk normal load dengan 10 users, peak load dengan 50 users, dan stress test dengan 100+ users.

Accuracy testing atau model evaluation menggunakan metrik target berupa MAPE kurang dari 10% yang merupakan acceptable business error untuk food security planning, RMSE dengan target minimize prediction error kurang dari 100 kkal/hari, MAE kurang dari 50 kalori/hari sebagai average absolute deviation, R² lebih dari 0.85 untuk explained variance proportion, serta directional accuracy lebih dari 80% untuk correct trend prediction. Test data menggunakan NBM periode 2020-2024 yang merupakan 48 bulan unseen data dengan validasi menggunakan time series cross-validation dengan expanding window.

Usability testing menggunakan metode User Acceptance Testing (UAT) dengan actual stakeholders yang terdiri dari 5 users Pemerintah, 3 users Akademisi, dan 2 administrators. Tasks yang dilakukan mencakup complete prediction workflow mulai dari pilih parameter hingga run prediksi, view results, dan export, rate ease of use menggunakan Likert scale 1-5, serta identify confusing UI elements dan pain points. Target metrics meliputi task completion rate lebih dari 90%, average satisfaction score lebih dari 4.0/5.0, time to complete task kurang dari 5 menit, dan learning curve kurang dari 30 menit untuk first-time users.

Security testing menggunakan OWASP ZAP dan manual penetration testing untuk menguji keamanan sistem. Target testing mencakup SQL injection protection menggunakan prepared statements dan ORM, XSS prevention menggunakan blade escaping, CSRF token validation dengan Laravel built-in, authentication bypass attempts, authorization checks untuk privilege escalation testing, serta API rate limiting untuk prevent brute force. Standar yang digunakan adalah OWASP Top 10 compliance.

• Kriteria Keberhasilan Sistem

Sistem SIKOLBIA dinyatakan berhasil jika memenuhi kriteria fungsional yang mencakup semua fitur sesuai requirements berjalan tanpa critical error, role-based access control berfungsi dengan benar untuk permission enforcement, prediksi menghasilkan output valid tanpa null atau NaN dengan range realistis, serta export data berhasil dalam format yang diminta yaitu Excel, PDF, dan CSV.

Kriteria non-fungsional meliputi response time rata-rata kurang dari 3 detik untuk prediction endpoint, system uptime lebih dari 99% untuk high availability, support minimum 50 concurrent users without performance degradation, serta mobile responsive untuk viewport kurang dari 768px menggunakan Tailwind CSS.

Kriteria akurasi model mencakup MAPE kurang dari 10% pada test set yang merupakan standar industri untuk food forecasting, directional accuracy lebih dari 80% untuk correct trend prediction, serta confidence interval coverage lebih dari 90% dimana actual values berada within CI bounds.

Kriteria usability meliputi user satisfaction score minimal 4.0 dari 5.0 berdasarkan UAT, task completion rate minimal 90%, serta learning curve kurang dari 30 menit dimana first-time users dapat menjalankan prediksi dengan mudah.

Kriteria security mencakup no critical vulnerabilities dengan OWASP Top 10 compliant, all inputs validated dan sanitized untuk prevent injection attacks, serta authentication dan authorization robust tanpa bypass exploits.

Optimasi performa menggunakan strategi caching (Redis untuk session dan query cache), database indexing (pada kolom `tahun`, `bulan`, `kode_kelompok`, `kode_komoditi`), dan API rate limiting (throttle middleware untuk prevent abuse). Security implementation meliputi authentication (Laravel Sanctum/Breeze), input validation (Form Request validation), dan secure communication protocols (HTTPS untuk production).

Monitoring dan logging menggunakan structured logging (Laravel Log channels) untuk system observability dan performance tracking (Laravel Telescope untuk development).

---

# BAB IV  
# HASIL DAN PEMBAHASAN

## 4.1. Research and Collection Preliminary

Tahap pertama dalam metodologi Research and Development (RnD) yang terintegrasi dengan CRISP-DM berfokus pada pengumpulan dan pemahaman data historis Neraca Bahan Makanan Indonesia. Proses ini sangat krusial karena kualitas model prediksi sangat bergantung pada representasi data yang akurat dan konsisten selama periode pelatihan yang panjang.

a. Dataset Neraca Bahan Makanan Indonesia

Dataset yang digunakan dalam penelitian ini merupakan kompilasi lengkap data Neraca Bahan Makanan (NBM) Indonesia yang mencakup periode tiga dekade dari tahun 1993 hingga 2024. Data dikumpulkan dari Pusat Data dan Sistem Informasi Pertanian, Kementerian Pertanian Republik Indonesia, serta publikasi tahunan Badan Pusat Statistik (BPS) mengenai konsumsi pangan nasional. Dataset ini tersedia dalam dua representasi utama yang saling melengkapi.

Representasi pertama berbentuk transaksi granular yang mencatat setiap komponen neraca bahan makanan secara rinci. Total 41.316 record transaksi mencakup aktivitas produksi domestik, impor komoditas pangan, ekspor, perubahan stok, serta alokasi untuk konsumsi makanan dan bukan makanan. Setiap record dilengkapi dengan metadata temporal (`tahun`, `bulan`), identifikasi komoditas (`kode_kelompok`, `kode_komoditi`), dan nilai numerik untuk setiap komponen neraca dalam satuan ton serta konversi kalori per kapita per hari.

Representasi kedua merupakan agregasi time-series bulanan yang menghasilkan 372 titik data observasi untuk total konsumsi kalori per kapita per hari pada tingkat nasional. Time-series ini dihitung dengan mengintegrasikan seluruh komoditas dalam kelompok pangan sesuai dengan formula konversi standar yang telah ditetapkan oleh FAO (Food and Agriculture Organization). Agregasi ini penting karena menjadi target variabel utama yang akan diprediksi oleh model LSTM enhanced ensemble.

b. Analisis Kualitas Data

Sebelum memasuki tahap preprocessing, dilakukan analisis menyeluruh terhadap kualitas dataset untuk mengidentifikasi potensi masalah yang dapat mempengaruhi performa model. Analisis ini mencakup pemeriksaan missing values, deteksi outlier, validasi konsistensi temporal, dan evaluasi distribusi statistik setiap variabel.

Hasil analisis menunjukkan bahwa missing values ditemukan pada sekitar 4,8% dari total observasi, terutama pada kolom `makanan` (ton) dan `produksi`. Missing values ini tersebar tidak merata, dengan konsentrasi lebih tinggi pada periode awal dataset (1993-1997) dan pada komoditas tertentu yang sistem pencatatannya baru distandarisasi pada periode kemudian. Sebagai contoh, data impor untuk beberapa komoditas buah-buahan tropis pada periode 1993-1995 tidak tercatat karena sistem klasifikasi HS Code untuk produk pertanian masih dalam tahap harmonisasi.

Outlier ekstrem teridentifikasi pada beberapa periode spesifik yang berkorelasi dengan kejadian ekonomi dan sosial signifikan. Krisis moneter 1998 menyebabkan lonjakan drastis pada nilai impor beberapa komoditas strategis akibat depresiasi rupiah, sementara nilai produksi domestik menurun tajam untuk komoditas yang memerlukan input impor. Periode pandemi COVID-19 (2020-2021) menunjukkan pola yang berbeda dengan fluktuasi tinggi pada konsumsi komoditas tertentu sementara komoditas lain mengalami penurunan konsumsi yang tajam.

Distribusi statistik menunjukkan bahwa sebagian besar variabel numerik (produksi, impor, ekspor) memiliki skewness positif yang signifikan, dengan nilai median jauh lebih rendah dari mean. Hal ini mengindikasikan keberadaan outlier pada ekor kanan distribusi yang perlu ditangani secara hati-hati untuk menghindari bias pada proses normalisasi. Hasil analisis kualitas data secara kuantitatif dapat dilihat pada Tabel 4 berikut.

**Tabel 4. Analisis Kualitas Data NBM Indonesia**

| Aspek Kualitas | Nilai | Metode Penanganan | Justifikasi |
|----------------|-------|-------------------|-------------|
| Total Records (Granular) | 41.316 | - | Mencakup semua komoditas & komponen neraca |
| Total Time Points (Agregat) | 372 bulan | - | Time-series kontinyu 1993-2024 |
| Missing Values | 4,8% | Forward-fill + Median fallback | Preservasi kontinuitas temporal |
| Missing Range Threshold | >6 bulan | Median regional | Hindari propagasi error panjang |
| Outlier Detection | Persentil 1-99 | Winsorization | Kurangi pengaruh shock ekonomi ekstrem |
| Skewness (Produksi) | +2.47 | RobustScaler | Tahan terhadap outlier |
| Skewness (Impor/Ekspor) | +3.12 | RobustScaler | Distribusi sangat skewed |
| Consistency Check | 100% | Manual verification | Validasi komponen neraca |
| Temporal Gap | 0 bulan |- | Tidak ada missing months |

c. Preprocessing dan Transformasi Data

Berdasarkan hasil analisis kualitas data, dirancang pipeline preprocessing yang komprehensif untuk memastikan dataset siap digunakan dalam pelatihan model deep learning. Pipeline ini diimplementasikan sebagai fungsi modular dalam ml_models/data_loader.py yang dapat dijalankan secara konsisten baik pada tahap training maupun inference.

Strategi imputasi yang dipilih adalah forward-fill temporal, dimana nilai yang hilang pada timestep t diisi dengan nilai observasi terakhir yang valid (t-1, t-2, dst). Pendekatan ini dipilih karena data konsumsi pangan cenderung memiliki autokorelasi yang kuat, dimana nilai bulan ini sangat dipengaruhi oleh nilai bulan sebelumnya. Namun, untuk menghindari propagasi error yang berlebihan ketika rentang missing values terlalu panjang (lebih dari 6 bulan konsekutif), digunakan fallback ke median regional komoditas yang sama. Median dipilih sebagai ukuran sentral yang robust terhadap outlier.

Implementasi fungsi imputasi dalam data_loader.py dapat dilihat pada Listing Code 1.

```python
def impute_missing_values(df: pd.DataFrame, max_forward_fill: int = 6) -> pd.DataFrame:
    """
    Imputasi missing values dengan strategi temporal forward-fill
    dan fallback median untuk rentang panjang.
    
    Args:
        df: DataFrame dengan kolom temporal (tahun, bulan, komoditi)
        max_forward_fill: Maksimal bulan forward-fill sebelum fallback
    
    Returns:
        DataFrame dengan missing values sudah diimputasi
    """
    df_sorted = df.sort_values(['kode_komoditi', 'tahun', 'bulan'])
    
    # Forward-fill per komoditi dengan limit
    df_filled = df_sorted.groupby('kode_komoditi').apply(
        lambda group: group.fillna(method='ffill', limit=max_forward_fill)
    )
    
    # Fallback: isi sisa dengan median per komoditi
    numeric_cols = ['produksi', 'impor', 'ekspor', 'makanan', 'kalori_hari']
    for col in numeric_cols:
        median_values = df_filled.groupby('kode_komoditi')[col].transform('median')
        df_filled[col].fillna(median_values, inplace=True)
    
    return df_filled
```

**Listing Code 1. Fungsi Imputasi Missing Values dalam data_loader.py**

Untuk mengurangi pengaruh outlier ekstrem yang dapat mendistorsi proses learning, diterapkan winsorization pada persentil 1 dan 99 untuk setiap variabel numerik. Winsorization dipilih dibanding penghapusan karena tetap mempertahankan ukuran dataset dan informasi temporal, hanya mengklip nilai ekstrem ke threshold yang ditentukan. Threshold 1-99 persentil dipilih berdasarkan exploratory data analysis yang menunjukkan bahwa outlier di luar rentang ini sebagian besar merupakan anomali pencatatan atau kejadian ekstrem yang tidak representatif untuk pola umum. Implementasi winsorization dapat dilihat pada Listing Code 2.

```python
from scipy.stats.mstats import winsorize

def apply_winsorization(df: pd.DataFrame, limits: tuple = (0.01, 0.01)) -> pd.DataFrame:
    """
    Terapkan winsorization pada kolom numerik untuk handle outlier.
    
    Args:
        df: DataFrame input
        limits: Tuple (lower, upper) persentil untuk klipping
    
    Returns:
        DataFrame dengan nilai ter-winsorize
    """
    numeric_cols = df.select_dtypes(include=[np.number]).columns
    df_winsorized = df.copy()
    
    for col in numeric_cols:
        if col not in ['tahun', 'bulan']:  # Preserve temporal columns
            df_winsorized[col] = winsorize(df[col], limits=limits)
    
    return df_winsorized
```

**Listing Code 2. Fungsi Winsorization untuk Handling Outlier**

Normalisasi diperlukan untuk memastikan semua fitur numerik berada pada skala yang sebanding, sehingga gradient descent dalam training neural network dapat konvergen dengan optimal. Berdasarkan karakteristik distribusi yang telah dianalisis, digunakan dua jenis scaler:

• StandardScaler untuk fitur dengan distribusi mendekati normal (produksi domestik agregat, kalori_hari agregat), yang melakukan transformasi z-score: (x - μ) / σ

• RobustScaler untuk fitur dengan distribusi skewed dan outlier (impor, ekspor per komoditi), yang menggunakan median dan IQR sebagai ukuran sentral dan dispersi: (x - median) / IQR

Implementasi normalisasi dilakukan per-komoditi untuk mempertahankan karakteristik skala masing-masing produk (Listing Code 3).

```python
from sklearn.preprocessing import StandardScaler, RobustScaler

def normalize_features(df: pd.DataFrame, scaler_type: str = 'robust') -> pd.DataFrame:
    """
    Normalisasi fitur numerik dengan scaler yang sesuai.
    
    Args:
        df: DataFrame input
        scaler_type: 'standard' atau 'robust'
    
    Returns:
        DataFrame normalized, scaler objects
    """
    scalers = {}
    df_normalized = df.copy()
    
    for komoditi in df['kode_komoditi'].unique():
        mask = df['kode_komoditi'] == komoditi
        subset = df[mask]
        
        if scaler_type == 'robust':
            scaler = RobustScaler()
        else:
            scaler = StandardScaler()
        
        numeric_cols = ['produksi', 'impor', 'ekspor', 'kalori_hari']
        df_normalized.loc[mask, numeric_cols] = scaler.fit_transform(
            subset[numeric_cols]
        )
        
        scalers[komoditi] = scaler
    
    return df_normalized, scalers
```

**Listing Code 3. Fungsi Normalisasi Fitur Per-Komoditi**

Hasil akhir dari tahap preprocessing ini adalah dataset yang bersih, konsisten, dan siap digunakan untuk feature engineering serta training model. Validasi kualitas dilakukan dengan memverifikasi bahwa tidak ada missing values tersisa, distribusi ter-normalized memiliki mean mendekati 0 dan standar deviasi mendekati 1 (untuk StandardScaler) atau median mendekati 0 (untuk RobustScaler), dan kontinuitas temporal tetap terjaga tanpa gap.

Dataset NBM Indonesia menggunakan klasifikasi standar kelompok komoditas berdasarkan Kementerian Pertanian. Sistem SIKOLBIA mengimplementasikan 11 kelompok utama yang disimpan dalam tabel kelompok database. Klasifikasi lengkap kelompok komoditas beserta contoh komoditas di dalamnya dapat dilihat pada Tabel 5 berikut.

**Tabel 5. Klasifikasi Kelompok Komoditas NBM Indonesia**

| Kode | Nama Kelompok | Deskripsi | Contoh Komoditas |
|------|---------------|-----------|------------------|
| 01 | Padi-padian | Serealia/biji-bijian sebagai sumber karbohidrat utama | Gabah, Beras, Jagung, Jagung Basah, Gandum, Tepung Gandum |
| 02 | Makanan berpati | Umbi-umbian dan sumber karbohidrat non-serealia | Ubi Jalar, Ubi Kayu, Gaplek, Tapioka, Tepung Sagu |
| 03 | Gula | Produk pemanis alami dan olahan | Gula Pasir, Gula Merah |
| 04 | Buah Biji Berminyak | Kacang-kacangan dan biji berminyak | Kacang Tanah (Berkulit/Lepas Kulit), Kedelai, Kacang Hijau, Kacang Merah, Kacang Panjang, Kelapa Daging, Biji Bunga Matahari, Kopra |
| 05 | Buah-buahan | Buah segar lokal dan impor | Alpokat, Jeruk, Duku, Durian, Jambu Biji, Mangga, Nanas, Pepaya, Pisang, Rambutan, Salak, Sawo, Leci, Matoa, Kiwi, Stroberi, Kurma, Tin, Buah Naga, Kesemek, Kelengkeng, Jambu Air, Semangka, Melon, Apel Malang, Anggur, Manggis, Belimbing, Sirsak, Sukun, Nangka, Kedondong, Markisa, Cempedak, Jambu Bol, Srikaya, Apel Batu |
| 06 | Sayur-sayuran | Sayuran segar dan bumbu dapur | Kangkung, Bayam, Kubis, Wortel, Buncis, Tomat, Daun Singkong, Terong, Pare, Labu, Kemangi, Seledri, Selada, Sawi, Jamur, Kentang, Bawang Merah, Bawang Putih, Jahe, Kunyit, Lengkuas, Kencur, Cabai, Daun Bawang, Lobak, Labu Siam, Mentimun |
| 07 | Daging | Protein hewani dari ternak dan unggas | Daging Sapi, Daging Ayam Buras, Daging Kambing, Daging Kerbau, Daging Domba, Daging Kuda, Daging Bebek, Daging Angsa, Daging Ayam Ras, Daging Babi, Jeroan |
| 08 | Telur | Produk telur dari berbagai unggas | Telur Ayam, Telur Bebek, Telur Puyuh |
| 09 | Susu | Produk susu dan olahannya | Susu Segar, Susu UHT, Susu Rendah Lemak, Susu Kemasan, Susu Pasteurisasi |
| 10 | Ikan | Ikan dan biota perairan | Ikan Lele, Ikan Kakap, Ikan Bandeng, Ikan Tuna, Udang, Cumi-cumi, Ikan Nila, Ikan Kerapu, Ikan Tongkol |
| 11 | Minyak dan Lemak | Sumber lemak nabati dan hewani | Minyak Kacang Tanah, Lemak Sapi, Minyak Goreng Kelapa, Lemak Babi, Minyak Goreng Sawit |

Setiap komoditas individual memiliki kode unik format XXYY dimana XX adalah kode_kelompok (01-11) dan YY adalah kode_komoditi dalam kelompok tersebut (01-99). Sebagai contoh:

• 0101 = Gabah (kelompok 01: Padi-padian, komoditi 01)
• 0102 = Beras (kelompok 01: Padi-padian, komoditi 02)
• 0201 = Ubi Jalar (kelompok 02: Makanan berpati, komoditi 01)

Total terdapat 372 time-series bulanan (1993-2024) untuk agregasi nasional, dengan 41.316 records transaksi granular yang mencakup seluruh komoditas across 11 kelompok ini.

## 4.2. Research Planning

Tahap Research Planning dalam metodologi RnD-CRISP-DM berfokus pada perencanaan eksperimen yang sistematis untuk mencapai tujuan penelitian. Pada tahap ini, dilakukan analisis mendalam terhadap karakteristik masalah forecasting time-series NBM, pemilihan arsitektur model yang sesuai, serta perancangan strategi eksperimen yang komprehensif untuk memvalidasi hipotesis penelitian.

a. Definisi Masalah dan Target Performa

Masalah utama yang dihadapi adalah ketidakpastian proyeksi konsumsi pangan nasional dalam jangka menengah (3-6 bulan), yang menyebabkan kesulitan dalam perencanaan kebijakan ketahanan pangan. Pendekatan manual yang sudah ada dengan tren linear dan pertimbangan ahli menghasilkan error rata-rata sekitar 15-20% (berdasarkan evaluasi internal Kementan 2019-2022), yang dinilai terlalu tinggi untuk mendukung decision-making yang akurat.

Penelitian ini menetapkan lima kriteria target performa sebagai berikut:

• MAPE (Mean Absolute Percentage Error) kurang dari 10% untuk prediksi 6 bulan ke depan
• MAE (Mean Absolute Error) maksimal 50 kkal/kapita/hari sebagai batas toleransi deviasi absolut
• Coverage confidence interval minimal 90% dalam band ±15% dari nilai aktual
• Latency inferensi maksimal 3 detik per request pada spesifikasi minimal 8 vCPU dan 16GB RAM
• Performa stabil pada periode volatilitas tinggi dengan penurunan akurasi maksimal 5 poin MAPE

Target MAPE < 10% dipilih berdasarkan benchmarking terhadap penelitian terkait di domain forecasting konsumsi pangan. Penelitian oleh Zhang et al. (2023) pada food consumption forecasting di China mencapai MAPE 12-14% dengan metode ARIMA, sementara Wang & Li (2021) melaporkan MAPE 9-11% menggunakan hybrid LSTM-ARIMA. Dengan memanfaatkan arsitektur ensemble dan dataset yang lebih lengkap, target < 10% dianggap ambisius namun achievable.

b. Pemilihan Arsitektur Model

Berdasarkan literature review pada BAB II, LSTM (Long Short-Term Memory) dipilih sebagai arsitektur base model karena kemampuannya dalam menangkap long-term dependencies pada data sequential. Keunggulan LSTM dibanding RNN vanilla adalah penanganan vanishing gradient problem melalui mekanisme gate (forget, input, output) yang mengontrol aliran informasi dalam cell state.

Namun, LSTM memiliki kelemahan dalam menangani sudden shifts atau structural breaks yang sering terjadi pada data ekonomi (seperti krisis 1998 atau pandemi 2020). Untuk mengatasi ini, dirancang arsitektur ensemble yang mengkombinasikan kekuatan LSTM dalam menangkap pola temporal kompleks dengan robustness metode tradisional terhadap outlier.

Arsitektur ensemble menggunakan dua komponen utama:

• LSTM Neural Network untuk menangkap pola temporal non-linear, pola musiman, dan efek lag yang kompleks dalam data time-series konsumsi pangan

• HuberRegressor sebagai model linear yang tahan terhadap outlier menggunakan fungsi loss Huber menggabungkan squared error untuk residual kecil dan absolute error untuk residual besar sehingga memberikan prediksi yang lebih stabil ketika terjadi anomali atau shock ekonomi

Kombinasi ini didasarkan pada prinsip diversity dalam ensemble learning, dimana model dengan karakteristik berbeda (deep learning vs traditional ML, non-linear vs linear) cenderung menghasilkan error yang complementary. Weighted averaging dari prediksi kedua model diharapkan menghasilkan prediksi yang lebih stabil dan akurat dibanding masing-masing model secara individual.

c. Feature Engineering dan Sequence Design

Proses rekayasa fitur dilakukan untuk mengekstrak informasi temporal dan pola statistik yang relevan dari data time-series mentah, dengan empat jenis fitur utama yang dikembangkan:

Lag Features menggunakan nilai konsumsi pada timestep t-1, t-2, dan t-3 sebagai historical values. Fitur ini dipilih karena data konsumsi pangan Indonesia menunjukkan autokorelasi yang kuat antar periode, dimana pola konsumsi bulan sebelumnya memberikan informasi prediktif yang signifikan untuk bulan berjalan.

Rolling Statistics dihitung dalam bentuk rolling mean dan rolling standard deviation dengan window 3 bulan. Fitur ini berfungsi untuk menangkap tren jangka pendek sekaligus mengukur volatilitas konsumsi, dengan efek smoothing yang mengurangi noise pada data historis.

Cyclical Encoding menggunakan transformasi trigonometri sin(2π × bulan/12) dan cos(2π × bulan/12) untuk merepresentasikan pola musiman. Pendekatan ini dipilih untuk mempertahankan sifat kontinuitas temporal, menghindari diskontinuitas artifisial antara bulan Desember dan Januari yang terjadi jika bulan direpresentasikan sebagai nilai ordinal 1-12.

Growth Rate dihitung dengan formula (kaloriₜ - kaloriₜ₋₁)/kaloriₜ₋₁ untuk menangkap momentum perubahan konsumsi. Fitur ini sensitif terhadap akselerasi atau deselerasi tren, memberikan sinyal early warning terhadap perubahan pola konsumsi yang signifikan.

Panjang sequence window merupakan hyperparameter krusial yang menentukan jumlah timestep historis yang digunakan model sebagai input untuk memprediksi periode berikutnya. Eksperimen grid search dilakukan dengan menguji tiga kandidat window length (3, 6, dan 12 bulan) menggunakan data validasi untuk mengevaluasi trade-off antara kompleksitas model dan akurasi prediksi. Hasil eksperimen grid search untuk pemilihan panjang sequence window dapat dilihat pada Tabel 6 berikut.

**Tabel 6. Grid Search Hasil untuk Sequence Window Length**

| Window Length | Training Time (min) | Validation MAPE (%) | Validation MAE | Memory (MB) | Pilihan |
|---------------|---------------------|---------------------|----------------|-------------|---------|
| 3 bulan | 18 | 11.2 | 55.3 | 187 | ✗ |
| 6 bulan | 32 | 9.8 | 45.1 | 245 | ✓ |
| 12 bulan | 67 | 10.4 | 47.8 | 421 | ✗ |

Hasil menunjukkan bahwa window 6 bulan memberikan trade-off terbaik antara akurasi dan efisiensi. Window 3 bulan terlalu pendek untuk menangkap seasonal patterns tahunan, sementara window 12 bulan mengalami overfitting dan memerlukan waktu training yang signifikan lebih lama tanpa improvement akurasi yang sebanding.

d. Hyperparameter Tuning Strategy

Untuk menemukan konfigurasi optimal, dirancang strategi hyperparameter tuning bertahap:

• Stage 1 Coarse Grid Search

Tahap eksplorasi luas untuk mengidentifikasi neighborhood parameter yang menjanjikan. Pada tahap ini dilakukan pencarian kombinatorial terhadap empat hyperparameter utama: jumlah unit LSTM pada layer pertama diuji dengan nilai 64, 128, dan 256 unit; layer kedua dengan 32, 64, dan 128 unit; ukuran batch divariasikan antara 16, 32, dan 64 sampel; serta dropout rate dieksperimen pada level 0.1, 0.2, dan 0.3 untuk regularisasi. Total 81 kombinasi konfigurasi dievaluasi menggunakan validation set dengan metrik MAPE sebagai kriteria pemilihan.

• Stage 2 Fine-tuning

Dilakukan setelah mengidentifikasi konfigurasi terbaik dari tahap pertama, dengan fokus pada penyempurnaan parameter yang lebih sensitif terhadap performa model. Learning rate dioptimasi melalui pencarian pada rentang 1×10⁻⁴, 5×10⁻⁴, dan 1×10⁻³ untuk mengontrol kecepatan konvergensi. Parameter Huber delta yang menentukan transisi antara squared loss dan absolute loss diuji pada nilai 1.0, 1.35, 1.5, dan 2.0 berdasarkan analisis distribusi residual error. Bobot ensemble antara LSTM dan HuberRegressor dioptimasi menggunakan algoritma Nelder-Mead dengan constraint bahwa jumlah bobot sama dengan 1 dan semua bobot non-negatif. Hasil lengkap dari proses tuning dapat dilihat pada Tabel 7 berikut.

**Tabel 7. Hasil Hyperparameter Tuning**

| Config | LSTM Units | Batch | Dropout | LR | Huber δ | Val MAPE (%) | Val MAE | Epoch Converged |
|--------|------------|-------|---------|----|---------|--------------| --------|-----------------|
| A | 128 – 64 | 32 | 0.2 | 1e-3 | 1.35 | 9.6 | 44.2 | 87 |
| B | 128 – 64 | 32 | 0.3 | 1e-3 | 1.35 | 9.8 | 45.1 | 93 |
| C | 256 –128 | 32 | 0.2 | 5e-4 | 1.0 | 10.1 | 46.7 | 124 |
| D | 128 – 64 | 16 | 0.2 | 1e-3 | 1.5 | 10.3 | 47.2 | 91 |
| E | 64 – 32 | 32 | 0.2 | 1e-3 | 1.35 | 10.9 | 49.8 | 76 |

Konfigurasi A terpilih sebagai model final karena mencapai validation MAPE terendah (9.6%) dengan jumlah parameter yang moderate (tidak overparameterized seperti Config C). Dropout 0.2 memberikan regularisasi yang cukup tanpa underfitting, dan learning rate 1e-3 memungkinkan konvergensi dalam waktu reasonable.

Huber delta 1.35 dipilih berdasarkan karakteristik residual error, nilai ini memberikan transisi smooth antara squared loss (untuk error kecil) dan absolute loss (untuk outlier), sesuai dengan distribusi error yang memiliki some extreme values pada periode shock ekonomi.

e. Cross-Validation Strategy

Mengingat sifat temporal data yang memiliki autokorelasi dan tren, standard k-fold cross-validation tidak appropriate karena akan mengakibatkan data leakage (training pada data masa depan, testing pada data masa lalu). Oleh karena itu, digunakan Expanding Window Time Series Cross-Validation dengan 5 folds:

• Fold 1: Train 1993-2010, Validate 2011-2012
• Fold 2: Train 1993-2012, Validate 2013-2014
• Fold 3: Train 1993-2014, Validate 2015-2016
• Fold 4: Train 1993-2016, Validate 2017-2018
• Fold 5: Train 1993-2018, Validate 2019

Strategi expanding window memastikan bahwa model selalu trained pada data historis dan validated pada data masa depan, merefleksikan skenario real-world deployment. Fold terakhir (2019) dipilih sebagai validation set untuk hyperparameter selection, sementara periode 2020-2024 digunakan sebagai held-out test set untuk evaluasi final.

Keuntungan expanding window dibanding sliding window adalah ukuran training set yang terus bertambah, memungkinkan model untuk learn dari lebih banyak historical patterns pada fold-fold akhir. Hal ini penting untuk menangkap long-term structural changes dalam pola konsumsi pangan Indonesia (seperti pergeseran dari pangan pokok serealia ke diversifikasi protein hewani).

## 4.3. Early Product Development

Tahap Early Product Development menandai transisi dari perencanaan eksperimental ke implementasi sistem yang dapat dioperasikan. Pada fase ini, arsitektur model yang telah dirancang diterjemahkan menjadi kode production-ready, infrastruktur microservices dibangun untuk deployment, dan pipeline ML end-to-end dikonstruksi untuk memastikan reproducibility dan maintainability.

a. Arsitektur Sistem dan Infrastruktur

Sistem SIKOLBIA dirancang dengan arsitektur microservices yang memisahkan komponen aplikasi web dan ML serving menjadi service independen. Pemisahan ini memberikan beberapa keuntungan strategis dalam pengembangan dan operasional sistem.

Dari sisi skalabilitas, ML service dapat dikembangkan secara independen berdasarkan beban inferensi tanpa mempengaruhi traffic aplikasi web. Hal ini memungkinkan resource allocation yang lebih efisien ketika terjadi lonjakan permintaan prediksi. Fleksibilitas teknologi memberikan kebebasan dalam pemilihan stack teknologi, dimana komponen web menggunakan PHP/Laravel sementara ML service memanfaatkan Python ecosystem yang kaya akan library machine learning. Aspek maintainability terjaga karena pembaruan model dapat dilakukan tanpa perlu restart aplikasi web, meminimalkan downtime sistem. Terakhir, fault isolation memastikan bahwa kegagalan pada satu service tidak menyebabkan total system failure, meningkatkan reliability keseluruhan sistem.

Arsitektur terdiri dari tiga komponen utama yang saling berkomunikasi:

• Laravel Web Application (Port 8000)

Berperan sebagai presentation layer yang menangani autentikasi dan otorisasi pengguna melalui session management, menyediakan antarmuka untuk input parameter prediksi dan visualisasi hasil dalam bentuk tabel serta grafik interaktif, mengelola transaksi database untuk menyimpan riwayat prediksi dan data pengguna, mengimplementasikan business logic untuk export laporan dalam format Excel, PDF, dan CSV, serta berfungsi sebagai API gateway yang mengatur komunikasi dengan ML service.

• FastAPI ML Service (Port 8082)

Core machine learning engine yang memuat model trained (LSTM + HuberRegressor) ke memory saat startup untuk mempercepat inference, menyediakan RESTful API endpoints untuk menerima prediction requests dalam format JSON, melakukan preprocessing input data sesuai dengan pipeline yang digunakan saat training untuk memastikan konsistensi transformasi, menjalankan inference dengan model ensemble dan menghitung confidence intervals menggunakan margin ±15%, kemudian mengembalikan hasil prediksi beserta metadata seperti model version dan feature importance.

• Redis Cache Layer (Port 6379)

Berfungsi sebagai high-performance caching system yang menyimpan hasil prediksi untuk request dengan input identik dengan Time-To-Live (TTL) 6 jam untuk menghindari komputasi redundan, mengurangi beban computational pada ML service untuk repeated queries yang sering terjadi pada analisis eksplorasi, serta menyimpan session data dan rate limiting counters untuk mengontrol beban sistem.

Komunikasi antar service menggunakan HTTP REST API dengan JSON payload, dilindungi oleh internal network dalam Docker Compose environment untuk security. Laravel melakukan HTTP POST request ke FastAPI ML Service dengan timeout 5 detik dan retry mechanism untuk handling transient failures.

b. Implementasi Pipeline ML

Pipeline Machine Learning diimplementasikan sebagai modular components yang dapat di-reuse baik pada tahap training maupun inference. Kode diorganisir dalam direktori ml_models/ (Listing Code 4).

```
ml_models/
├── data_loader.py
├── production_model.py
├── train_model.py
├── evaluate.py
└── models/
    └── nbm_production/
        ├── lstm_model.h5
        ├── huber_model.pkl
        ├── scalers.pkl
        └── metadata.json
```

**Listing Code 4. Struktur Direktori ml_models/**

Komponen data_loader.py bertanggung jawab untuk mengumpulkan, membersihkan, dan mentransformasi raw data menjadi format yang siap dikonsumsi oleh model. Implementasi mencakup semua preprocessing steps (Listing Code 5).

```python
import pandas as pd
import numpy as np
from typing import Tuple, Dict
from sklearn.preprocessing import StandardScaler, RobustScaler
import pickle

class NBMDataLoader:
    """
    Data loader untuk Neraca Bahan Makanan Indonesia.
    Handles preprocessing, feature engineering, dan sequence generation.
    """
    
    def __init__(self, window_size: int = 6):
        self.window_size = window_size
        self.scalers = {}
        self.feature_cols = ['kalori_hari', 'lag_1', 'lag_2', 'lag_3',
                             'rolling_mean_3', 'rolling_std_3',
                             'month_sin', 'month_cos']
    
    def load_and_preprocess(self, file_path: str,
                            start_year: int = None,
                            end_year: int = None) -> pd.DataFrame:
        """Load raw CSV dan apply preprocessing pipeline."""
        # Load data
        df = pd.read_csv(file_path)
        df['date'] = pd.to_datetime(df[['tahun', 'bulan']].assign(day=1))
        df = df.sort_values('date')
        
        # Filter tahun jika specified
        if start_year:
            df = df[df['tahun'] >= start_year]
        if end_year:
            df = df[df['tahun'] <= end_year]
        
        # Preprocessing pipeline
        df = self._handle_missing_values(df)
        df = self._remove_outliers(df)
        df = self._engineer_features(df)
        df = self._normalize_features(df)
        
        return df
    
    def _handle_missing_values(self, df: pd.DataFrame) -> pd.DataFrame:
        """Imputasi missing values dengan forward-fill + median."""
        df_filled = df.copy()
        
        # Forward fill dengan limit 6 bulan
        for col in ['kalori_hari', 'produksi', 'impor']:
            df_filled[col] = df_filled[col].fillna(method='ffill', limit=6)
        
        # Fallback ke median untuk gap panjang
        df_filled = df_filled.fillna(df_filled.median())
        
        return df_filled
    
    def _remove_outliers(self, df: pd.DataFrame) -> pd.DataFrame:
        """Winsorization pada persentil 1-99."""
        from scipy.stats.mstats import winsorize
        
        df_clean = df.copy()
        numeric_cols = ['kalori_hari', 'produksi', 'impor', 'ekspor']
        
        for col in numeric_cols:
            df_clean[col] = winsorize(df[col], limits=[0.01, 0.01])
        
        return df_clean
    
    def _engineer_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """Generate lag features, rolling stats, cyclical encoding."""
        df_features = df.copy()
        
        # Lag features (t-1, t-2, t-3)
        for lag in [1, 2, 3]:
            df_features[f'lag_{lag}'] = df_features['kalori_hari'].shift(lag)
        
        # Rolling statistics (3-month window)
        df_features['rolling_mean_3'] = df_features['kalori_hari'].rolling(
            window=3, min_periods=1
        ).mean()
        df_features['rolling_std_3'] = df_features['kalori_hari'].rolling(
            window=3, min_periods=1
        ).std().fillna(0)
        
        # Cyclical month encoding untuk seasonality
        df_features['month_sin'] = np.sin(2 * np.pi * df_features['bulan'] / 12)
        df_features['month_cos'] = np.cos(2 * np.pi * df_features['bulan'] / 12)
        
        # Drop rows dengan NaN dari lag features
        df_features = df_features.dropna()
        
        return df_features
    
    def _normalize_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """Normalisasi dengan RobustScaler (robust terhadap outlier)."""
        from sklearn.preprocessing import RobustScaler
        
        df_normalized = df.copy()
        scaler = RobustScaler()
        
        # Normalisasi semua feature columns
        df_normalized[self.feature_cols] = scaler.fit_transform(
            df[self.feature_cols]
        )
        
        # Simpan scaler untuk inference
        self.scalers['features'] = scaler
        
        return df_normalized
    
    def create_sequences(self, df: pd.DataFrame) -> Tuple[np.ndarray, np.ndarray]:
        """
        Generate sequences untuk LSTM training/inference.
        
        Returns:
            X: Array shape (n_samples, window_size, n_features)
            y: Array shape (n_samples,) - target values
        """
        features = df[self.feature_cols].values
        target = df['kalori_hari'].values
        
        X, y = [], []
        
        for i in range(len(features) - self.window_size):
            X.append(features[i:i + self.window_size])
            y.append(target[i + self.window_size])
        
        return np.array(X), np.array(y)
    
    def save_scalers(self, path: str):
        """Simpan scaler objects untuk production inference."""
        with open(path, 'wb') as f:
            pickle.dump(self.scalers, f)
    
    def load_scalers(self, path: str):
        """Load scaler objects untuk production inference."""
        with open(path, 'rb') as f:
            self.scalers = pickle.load(f)
```

Listing Code 5. Class NBMDataLoader untuk Preprocessing Pipeline

Implementasi ini memastikan bahwa model dapat di-save dan di-load dengan mudah untuk production deployment, dengan semua komponen (LSTM, Huber, weights) ter-persist secara konsisten.

c. Software dan Hardware Requirements

Sebelum deployment sistem SIKOLBIA, perlu dipahami requirement komprehensif baik dari sisi software maupun hardware untuk memastikan sistem dapat berjalan optimal. Requirements ini dibagi menjadi beberapa kategori berdasarkan environment dan role.

• Development Environment Requirements

Untuk development dan training model, diperlukan software dan hardware dengan spesifikasi yang memadai. Kebutuhan software development dapat dilihat pada Tabel 8 berikut.

Tabel 8. Software Requirements untuk Development

| Komponen Software | Versi Minimum | Versi Recommended | Justifikasi |
|-------------------|---------------|-------------------|-------------|
| Operating System | Ubuntu Linux 20.04 LTS | 22.04 LTS | Stabilitas, package availability |
| Python | Python 3.9 | 3.10.12 | TensorFlow 2.15 compatibility |
| Deep Learning Framework | TensorFlow 2.12 | 2.15.0 | LSTM training, GPU support |
| ML Library | scikit-learn 1.2 | 1.3.0 | HuberRegressor, preprocessing |
| Data Processing | Pandas 1.5 | 2.0.3 | DataFrame operations |
| | NumPy 1.23 | 1.24.3 | Numerical computing |
| Visualization | Matplotlib 3.6 | 3.7.1 | Training curves, analysis plots |
| | Seaborn 0.12 | 0.12.2 | Statistical visualizations |
| Explainability | SHAP 0.41 | 0.42.1 | Feature importance analysis |
| CUDA Support | CUDA Toolkit 11.2 | 11.8 | GPU acceleration |
| | cuDNN 8.1 | 8.6 | Deep learning GPU optimization |
| Version Control | Git 2.30 | 2.40+ | Code versioning |

Spesifikasi hardware untuk environment development dan training model dapat dilihat pada Tabel 9 berikut.

Tabel 9. Hardware Requirements untuk Development

| Komponen | Minimum Spec | Recommended Spec | Production Spec | Keterangan |
|----------|--------------|------------------|-----------------|------------|
| CPU | 4 cores, 2.0 GHz | 8 cores, 3.0 GHz | 12 cores, 2.4+ GHz | Training time critical |
| RAM | 16 GB | 32 GB | 32-64 GB | Large dataset loading |
| GPU | - | NVIDIA RTX 3060 (12GB) | NVIDIA RTX A2000 (6GB) | 3-4x speedup vs CPU |
| Storage | 100 GB SSD | 500 GB NVMe SSD | 1 TB NVMe SSD | Fast I/O untuk data loading |
| Network | 10 Mbps | 100 Mbps | 1 Gbps | Model download, deployment |

• Production Environment Requirements

Untuk deployment production menggunakan Docker Compose, digunakan software stack yang dijelaskan pada Tabel 10 berikut.

Tabel 10. Software Stack Production

| Service | Technology | Versi | Port | Resource Allocation |
|---------|------------|-------|------|---------------------|
| Web Application | PHP Laravel | 11.x | 8000 | 2 CPU, 4 GB RAM |
| ML API | Python FastAPI | 0.104+ | 8082 | 4 CPU, 8 GB RAM |
| Database | MySQL | 8.0 | 3306 | 2 CPU, 4 GB RAM |
| Cache | Redis | 7.0 | 6379 | 1 CPU, 2 GB RAM |
| Reverse Proxy | Nginx | 1.24 | 80, 443 | 1 CPU, 1 GB RAM |
| Container Runtime | Docker | 24.0+ | - | - |
| | Docker Compose | 2.20+ | - | - |

Berdasarkan hasil load testing dan capacity planning, deployment production sistem SIKOLBIA membutuhkan server dengan minimal 10 CPU cores, meskipun disarankan menggunakan 12-16 cores untuk menghandle concurrent users dengan lebih optimal. Kebutuhan memori minimal adalah 20 GB RAM, namun alokasi 32 GB sangat direkomendasikan untuk keperluan caching dan menangani peak load pada jam-jam sibuk. Dari sisi storage, diperlukan minimal 100 GB ruang penyimpanan SSD untuk menampung database, application logs, dan backup files. Koneksi network minimal 100 Mbps symmetric sudah cukup untuk operasional normal, tetapi untuk mendukung lebih dari 100 concurrent users, koneksi 1 Gbps sangat disarankan agar response time tetap optimal.

• Network Requirements

Infrastruktur jaringan untuk deployment production memerlukan konfigurasi firewall yang memperbolehkan akses inbound pada port 80 untuk HTTP dan port 443 untuk HTTPS, sementara akses langsung ke port database (3306), Redis (6379), dan ML API (8082) harus diblokir dari internet publik untuk keamanan. Sistem membutuhkan minimal satu domain atau subdomain yang valid seperti datanonkom.pertanian.go.id/sikolbia, dilengkapi dengan SSL certificate yang dapat diperoleh secara gratis atau menggunakan commercial certificate untuk production environment. Untuk keperluan backup dan disaster recovery, diperlukan network storage atau cloud storage dengan kapasitas minimal 500 GB yang terhubung ke sistem untuk menampung automated backups secara berkala.

• Security Requirements

Dari aspek keamanan sistem, SIKOLBIA dirancang dengan dukungan untuk OAuth 2.0. Enkripsi data diterapkan pada dua level: TLS 1.3 untuk melindungi data yang sedang ditransmisikan (data in transit) dan AES-256 untuk data yang tersimpan di database (data at rest). Sistem dilengkapi dengan centralized logging menggunakan ELK stack atau teknologi sejenis untuk keperluan audit trail dan forensik keamanan. Vulnerability scanning dilakukan secara berkala menggunakan tools seperti OWASP ZAP atau Nessus untuk mengidentifikasi dan memperbaiki celah keamanan potensial. Mekanisme backup otomatis berjalan setiap hari dengan retention policy 30 hari untuk memastikan data dapat dipulihkan jika terjadi insiden.

Seluruh requirements yang telah disebutkan di atas telah melalui proses validasi melalui deployment testing dan dapat dijadikan acuan bagi institusi lain yang ingin mengadopsi sistem SIKOLBIA untuk kebutuhan forecasting konsumsi pangan mereka.

d. User Requirements Analysis

Analisis user requirements dilakukan melalui series of stakeholder interviews, focus group discussions, dan task analysis untuk memahami kebutuhan actual end users. User requirements dibagi menjadi functional requirements (apa yang sistem harus lakukan) dan non-functional requirements (bagaimana sistem harus berperforma).

• Stakeholder Identification

Penelitian mengidentifikasi 4 kategori user utama dengan needs yang berbeda, yang dirincikan pada Tabel 11 berikut.

Tabel 11. User Stakeholder Categories

| Kategori | Jumlah Pengguna Terkait | Jabatan | Tujuan Utama | Frekuensi Penggunaan |
|----------|-------------------------|---------|--------------|----------------------|
| Pemerintah (Pembuat Kebijakan) | 15 | Direktur, Kepala Bidang | Perencanaan strategi, alokasi anggaran | Bulanan |
| Pemerintah (Analisis) | 25 | Staf Data/IT | Perencanaan operasional, pelaporan | Mingguan |
| Akademisi | 10 | Mahasiswa, Dosen | Riset publikasi, pengajaran | Berbasis proyek |
| Pengguna Umum | 50+ | Masyarakat Umum | Akses informasi, advokasi | Ad-hoc |

• Functional Requirements dari User Perspective

Berdasarkan hasil analisis kebutuhan stakeholder, functional requirements sebagaimana tertera pada Tabel 12 berikut.

Tabel 12. Functional Requirements

| ID | Requirement | Kategori Pengguna | Kriteria Penerimaan | Status |
|----|-------------|-------------------|---------------------|--------|
| FR-01 | Prediksi konsumsi kalori 3-6 bulan kedepan | Semua | MAPE < 10%, response < 3s | Sudah diimplentasi |
| FR-02 | Lihat confidence interval untuk risk assessment | Pemerintah | CI coverage ≥ 90%, visual representation | Sudah diimplementasi (92% coverage) |
| FR-03 | Export predictions ke Excel dengan charts | Pemerintah (Analisis) | Include historical comparison, formatted | Sudah diimplementasi (2 sheet template) |
| FR-04 | Export predictions ke PDF untuk presentations | Pemerintah (Pembuat Kebijakan) | Professional layout, logo, auto charts | Sudah diimplementasi |
| FR-05 | Export raw data ke CSV untuk reanalysis | Akademisi | Include all metadata, UTF-8 encoding | Sudah diimplementasi |
| FR-06 | Generate AI insights untuk interpretation | Pemerintah (Pembuat Kebijakan) | Rekomendasi aksi | Sudah diimplementasi |
| FR-07 | Compare predictions dengan actual historical data | Pemerintah (Analisis), Akademisi | Visualisasi perbandingan, error metrics | Sudah diimplementasi |
| FR-08 | Batch prediction untuk beberapa komoditi | Pemerintah (Analisis) | Support ≥20 komoditi, indikator progres | Sudah diimplementasi |
| FR-09 | Filter predictions dengan kelompok komoditi | Semua | Select dropdown | Sudah diimplementasi |
| FR-10 | Lihat tren data historis (5+ tahun) | Semua | Chart interaktif | Sudah diimplementasi |
| FR-11 | User authentication dan role based access | Semua | Secure login, different permissions | Sudah diimplementasi |
| FR-12 | Save favorite predictions untuk quick access | Pemerintah (Analisis) | Bookmark functionality, quick load | Dalam proses |

• Non-Functional Requirements

Selain functional requirements, sistem SIKOLBIA harus memenuhi berbagai non-functional requirements yang mencakup aspek performa, keamanan, keandalan, dan maintainability sebagaimana dijabarkan pada Tabel 13 berikut.

Tabel 13. Functional Requirements

| Kategori | Requirement | Metrik Target | Metrik Tercapai | Status |
|----------|-------------|---------------|-----------------|--------|
| Performance | Prediction response time (single) | < 3s | 0.82s (avg), 1.4s (p95) | Terpenuhi |
| | Prediction response time (batch 20) | < 10s | 5.1s | Terpenuhi |
| | Page load time | < 3s | 1.4s | Terpenuhi |
| | Concurrent users support | ≥ 50 | 120 tested | Terpenuhi |
| | Database query time | < 200ms | 45ms (avg) | Terpenuhi |
| Reliability | System uptime | ≥ 99% | 99.2% (3-month test) | Terpenuhi |
| | Error rate | < 0.1% | 0.03% | Terpenuhi |
| Usability | Task completion rate | ≥ 85% | 92% | Terpenuhi |
| | Time to complete core task | < 5 min | 3.8 min (avg) | Terpenuhi |
| Security | Authentication method | Multi-factor support | Password + session | Direncanakan |
| | Critical vulnerabilities | 0 | 0 (OWASP ZAP) | Terpenuhi |
| | Session timeout | 30 menit idle | 30 menit | Terpenuhi |
| Scalability | Horizontal scaling support | Docker/K8s ready | Docker ready | Terpenuhi |
| Accessibility | WCAG compliance | Level AA | Partial (contrast, alt text) | Dalam proses |
| Maintainability | Code documentation coverage | ≥ 80% | 85% estimated | Terpenuhi |
| | API documentation | Complete (Swagger) | Available | Terpenuhi |
| | User manual | Complete | 45 pages PDF | Terpenuhi |

e. Prosedur Penggunaan Sistem

Section ini menjelaskan prosedur untuk menggunakan sistem SIKOLBIA, mulai dari akses pertama kali hingga advanced features. Prosedur disusun berdasarkan typical user journeys yang diobservasi selama UAT. Setiap langkah prosedur dilengkapi dengan screenshot antarmuka sistem untuk memudahkan pemahaman alur penggunaan.

• Prosedur Registrasi dan Login

Pengguna dapat mengakses sistem SIKOLBIA dengan membuka web browser dan mengetikkan URL https://datanonkom.pertanian.go.id/sikolbia pada address bar. Setelah URL diakses, halaman landing page akan muncul menampilkan informasi singkat tentang sistem serta menu navigasi utama di bagian atas, sebagaimana ditunjukkan pada Gambar 9 berikut.

Gambar 9. Halaman Landing Page SIKOLBIA

Untuk pengguna baru, proses registrasi disesuaikan dengan jenis pengguna. Pengguna umum dapat langsung mengakses sistem tanpa perlu registrasi. Pemerintah perlu melakukan registrasi melalui halaman registrasi pemerintah, sedangkan akademisi perlu mendaftar melalui halaman registrasi akademisi. Untuk admin, akun sudah dibuatkan sebelumnya sehingga dapat langsung login. Cara login dapat dilakukan dengan mengklik menu "Manajemen Data" di navbar, kemudian akan muncul form login sebagaimana ditampilkan pada Gambar 10.

Gambar 10. Halaman Login Page SIKOLBIA

Pengguna dari instansi pemerintah yang ingin mendapatkan akses ke sistem SIKOLBIA harus melengkapi formulir registrasi khusus pemerintah yang dapat diakses melalui halaman /registrasi/pemerintah. Tampilan halaman registrasi pemerintah dapat dilihat pada Gambar 11. Proses registrasi dimulai dengan mengisi informasi pribadi yang mencakup nama lengkap, email, dan nomor telepon yang semuanya merupakan field wajib. Selanjutnya, pengguna perlu melengkapi informasi instansi yang meliputi nama instansi, jenis dinas yang dapat dipilih dari dropdown menu seperti Dinas Pertanian, Dinas Pangan, Dinas Ketahanan Pangan, BPS, atau Lainnya, serta jabatan pengguna di instansi tersebut.

Gambar 11. Halaman Registrasi Pemerintah

Bagian selanjutnya dari formulir registrasi pemerintah adalah pemilihan tujuan penggunaan data. Pengguna dapat memilih satu atau lebih tujuan dari pilihan yang tersedia yaitu Perencanaan Kebijakan, Monitoring dan Evaluasi, Penelitian, atau Analisis Data. Setelah memilih tujuan penggunaan data, pengguna diminta untuk menjelaskan secara detail deskripsi kebutuhan penggunaan data dalam sistem SIKOLBIA pada kolom yang telah disediakan.

Tahap penting dalam registrasi pemerintah adalah upload dokumen pendukung. Sistem menyediakan template surat permohonan resmi yang dapat diunduh oleh pengguna dengan mengklik tombol "Buka Template Surat". Dokumen yang wajib diunggah meliputi Surat Permohonan Akses Data dalam format PDF dengan ukuran maksimal 5MB, serta Kartu Pegawai atau ID Instansi dalam format PDF, JPG, atau PNG dengan ukuran maksimal 2MB. Terdapat juga opsi untuk mengunggah Surat Keterangan Atasan dalam format PDF dengan ukuran maksimal 3MB yang bersifat opsional. Pengguna dapat mengunggah dokumen dengan cara mengklik area upload atau melakukan drag and drop file ke area yang telah disediakan.

Sistem menampilkan persyaratan dokumen yang harus dipenuhi di bagian bawah formulir, yaitu dokumen harus jelas dan terbaca, surat permohonan harus bermaterai dan ditandatangani oleh pejabat berwenang, ID instansi yang diunggah harus masih berlaku, format file harus sesuai ketentuan, dan ukuran file tidak boleh melebihi batas maksimal yang telah ditentukan. Setelah semua informasi dan dokumen dilengkapi, pengguna dapat mengklik tombol "Kirim Registrasi" untuk mengirimkan permohonan. Sistem akan menampilkan informasi bahwa tim SIKOLBIA akan meninjau aplikasi dalam waktu 1 hingga 2 hari kerja, dan pengguna akan menerima email konfirmasi setelah proses verifikasi selesai. Jika pengguna ternyata dari institusi akademik atau mahasiswa, tersedia link di bagian bawah halaman untuk beralih ke halaman registrasi akademisi.

Untuk kalangan akademisi termasuk mahasiswa, dosen, dan peneliti, registrasi dilakukan melalui halaman khusus di /registrasi/akademisi yang tampilannya dapat dilihat pada Gambar 12. Formulir registrasi akademisi dimulai dengan pengisian informasi pribadi yang mencakup nama lengkap, email yang disarankan menggunakan email institusi dengan domain .ac.id untuk mempercepat proses verifikasi, dan nomor telepon. Ketiga field ini merupakan field wajib yang harus diisi oleh pengguna.

Gambar 12. Halaman Registrasi Akademisi

Setelah informasi pribadi, pengguna perlu melengkapi informasi akademik yang terdiri dari nama institusi atau universitas, jenjang pendidikan yang dapat dipilih dari dropdown menu meliputi S1 Sarjana, S2 Magister, S3 Doktor, Dosen, atau Peneliti, serta program studi atau bidang penelitian yang sedang dijalani. Bagian tujuan penggunaan data memungkinkan pengguna untuk memilih satu atau lebih tujuan dari pilihan yang tersedia seperti Penelitian Skripsi, Penelitian Tesis, Penelitian Disertasi, Penelitian Akademik, Analisis Data, atau Pembelajaran dan Edukasi.

Pengguna akademisi juga diminta untuk menjelaskan secara detail deskripsi kebutuhan atau topik penelitian yang akan dilakukan menggunakan sistem SIKOLBIA pada kolom yang disediakan. Proses registrasi akademisi lebih sederhana dibandingkan registrasi pemerintah karena tidak memerlukan upload dokumen pendukung. Setelah mengisi seluruh formulir, pengguna dapat mengklik tombol "Kirim Registrasi" dan sistem akan menampilkan informasi bahwa tim akan melakukan verifikasi dengan mengirimkan email konfirmasi dalam waktu 1 hingga 2 hari kerja. Terdapat catatan khusus untuk mahasiswa agar menggunakan email institusi dengan domain .ac.id untuk mempercepat proses verifikasi. Jika pengguna bukan dari institusi akademik, tersedia link untuk beralih ke halaman registrasi pemerintah.

Setelah registrasi disetujui dan pengguna menerima email konfirmasi berisi kredensial akses, pengguna dapat melakukan login ke sistem SIKOLBIA. Proses login dimulai dengan membuka halaman utama SIKOLBIA kemudian mengklik menu "Manajemen Data" pada navbar di bagian atas halaman. Sistem akan menampilkan form login yang meminta pengguna memasukkan kredensial berupa email dan password yang telah diberikan melalui email konfirmasi. Setelah memasukkan kredensial dengan benar, pengguna dapat mengklik tombol "Login" untuk masuk ke sistem.

Setelah login berhasil, pengguna akan diarahkan ke dashboard sesuai dengan role dan hak akses yang telah ditetapkan. Pengguna pemerintah dan akademisi akan memiliki akses ke fitur-fitur sesuai dengan kebutuhan yang telah didaftarkan saat registrasi, sementara admin memiliki akses penuh untuk mengelola seluruh sistem termasuk verifikasi registrasi pengguna baru, manajemen data, dan pengaturan sistem secara keseluruhan. Pengguna disarankan untuk menyimpan kredensial login dengan aman dan tidak membagikannya kepada pihak yang tidak berwenang demi menjaga keamanan data dan sistem SIKOLBIA.

• Prosedur Navigasi Dashboard

Sistem SIKOLBIA menyediakan dashboard yang berbeda untuk setiap jenis pengguna sesuai dengan hak akses dan kebutuhan masing-masing.

Setelah login sebagai admin, pengguna akan diarahkan ke halaman pemilihan panel admin di URL /admin yang menampilkan judul "Pilih Panel Admin" sebagaimana ditunjukkan pada Gambar 13. Halaman ini menampilkan lima panel utama yang dapat diakses, yaitu Konsumsi Pangan (/admin/konsumsi-pangan), Lahan (/admin/lahan), Iklim OPT-DPI (/admin/iklim-opt-dpi), Daftar Alamat (/admin/daftar-alamat), dan Benih Pupuk (/admin/benih-pupuk).

Gambar 13. Halaman Admin Panel

Di bagian bawah halaman terdapat informasi pengguna yang sedang login. Tombol "Kembali" di pojok kiri atas untuk kembali ke halaman sebelumnya, sementara tombol "Keluar" di pojok kanan atas untuk logout. Setelah memilih salah satu panel, admin akan masuk ke dashboard panel yang memiliki menu Dashboard untuk melihat ringkasan data dan menu CRUD untuk mengelola data. Sidebar navigasi menampilkan logo SIKOLBIA di bagian atas dengan menu-menu yang disusun secara hierarkis.

Pengguna pemerintah setelah login akan diarahkan ke dashboard pemerintah di URL /pemerintah sebagaimana ditunjukkan pada Gambar 14. Halaman dashboard menampilkan banner sambutan dengan informasi "Role: Pemerintah, Akses: Read-Only". Dashboard menyediakan empat kartu akses cepat yaitu Konsumsi Pangan - NBM, Data Lahan - Pertanian, Benih & Pupuk - Sarana, dan Iklim OptDPI - Cuaca.

Gambar 14. Halaman Pemerintah Panel

Di bawah kartu akses terdapat panel "Informasi Akses" yang menjelaskan bahwa pengguna memiliki akses read-only untuk melihat data, dapat melakukan export data dalam format Excel/CSV, dan menggunakan fitur prediksi NBM. Untuk akses edit atau kelola data, pengguna perlu menghubungi administrator. Bagian bawah dashboard menampilkan "Akses Cepat" dan "Informasi Akun" yang berisi nama, email, role, dan status akun.

Sidebar navigasi menampilkan logo SIKOLBIA dan label "Panel Pemerintah". Menu navigasi terdiri dari Dashboard, Konsumsi Pangan (Laporan NBM, Prediksi NBM), Pertanian (Data Lahan, Benih & Pupuk, Iklim OptDPI), dan Informasi (Panduan Penggunaan).

Pengguna akademisi setelah login akan diarahkan ke dashboard akademisi di URL /akademisi sebagaimana ditunjukkan pada Gambar 15. Halaman dashboard menampilkan banner sambutan dengan informasi "Role: Akademisi, Akses: Read-Only & Export". Dashboard menyediakan empat kartu fitur utama yaitu Data NBM - Riset, Publikasi - Export, Prediksi ML - Analisis, dan Dataset - Penelitian.

Gambar 15. Halaman Akademisi Panel

Panel "Informasi Akses Akademisi" menjelaskan bahwa pengguna dapat melihat seluruh data, melakukan export data, menggunakan fitur prediksi NBM berbasis Machine Learning, dan mengakses statistik serta visualisasi data. Data yang dieksport dapat digunakan untuk penelitian akademik, publikasi ilmiah, Skripsi, Tesis, Disertasi, dan analisis statistik. Pengguna diminta mencantumkan sumber data "SIKOLBIA - Sistem Kolaborasi Ketahanan Pangan Nasional Indonesia" dalam publikasi.

Bagian tengah dashboard menampilkan "Akses Data & Riset" yang berisi empat kartu dataset: Dataset NBM, Data Lahan Pertanian, Saprodi (Benih & Pupuk), dan Iklim OptDPI. Bagian kanan menampilkan "Informasi Akun" dan "Petunjuk Sitasi Data".

Sidebar navigasi menampilkan logo SIKOLBIA dan label "Panel Akademisi". Menu navigasi terdiri dari Platform (Dashboard), Data Historis NBM (Lihat Data NBM, Export Data), Tools & Visualisasi (Grafik & Statistik, Filter & Query), dan Dokumentasi (Konsep & Metode NBM, Panduan Sitasi, Data Dictionary).

Pengguna umum dapat mengakses dashboard publik melalui URL /ketersediaan/dashboard-publik sebagaimana ditunjukkan pada Gambar 16. Dashboard dapat diakses melalui navbar dengan mengklik menu "Ketersediaan" kemudian memilih "Dashboard NBM Publik". Breadcrumb navigation menampilkan urutan "Home, Ketersediaan, Dashboard NBM Publik".

Gambar 16. Halaman Dashboard NBM Publik

Halaman dashboard menampilkan judul "Dashboard Neraca Bahan Makanan (NBM) Indonesia" dengan subjudul "Informasi publik konsumsi pangan nasional - Data terbaru dari 120 komoditas dalam 11 kelompok". Di bawahnya terdapat empat kartu statistik ringkasan: Tahun Data Terbaru (2024), Komoditas Pangan (120), Kelompok Komoditas (11), dan Rata-rata Kalori/Hari (106).

Section berikutnya menampilkan dua visualisasi data. Visualisasi pertama adalah diagram donut berjudul "Konsumsi Kalori per Kelompok Komoditas" yang menampilkan proporsi konsumsi kalori dari berbagai kelompok seperti Ikan, Padi - Padian, Makanan berpati, Telur, Buah/Biji Berminyak, Daging, dan Sayur-sayuran. Visualisasi kedua adalah line chart berjudul "Tren Konsumsi Kalori (5 Tahun Terakhir)" yang menampilkan perkembangan konsumsi kalori dari tahun 2020 hingga 2024.

Section selanjutnya menampilkan tabel "Top 10 Komoditas Konsumsi Kalori Tertinggi" dengan kolom Ranking, Komoditas, Kelompok, Kalori/Hari, dan Persentase. Data menunjukkan Ikan Bandeng menempati peringkat pertama (3127.5 kkal/hari, 59%), diikuti Beras (673.2 kkal/hari, 13%), dan Ikan Tuna (549.7 kkal/hari, 10%).

Di bawah tabel terdapat dua panel informasi. Panel "Tentang Data NBM" menjelaskan definisi NBM dengan link "Pelajari Metodologi". Panel "Prediksi AI" menjelaskan penggunaan teknologi AI dengan SHAP analysis dan menampilkan badge "Prediksi akurasi lebih dari 80%" dan "Interpretabilitas SHAP".

Bagian bawah dashboard menampilkan call-to-action banner "Butuh Akses Lebih Lengkap?" dengan penjelasan benefit registrasi. Banner dilengkapi dua tombol yaitu "Akses Pemerintah" dan "Akses Akademisi" yang mengarahkan ke halaman registrasi sesuai kategori pengguna.

• Prosedur Generate Prediksi

Fitur prediksi NBM dalam sistem SIKOLBIA dapat diakses melalui URL /pemerintah/prediksi-nbm dan hanya tersedia untuk pengguna dengan role admin atau pemerintah yang telah login. Pengguna akademisi dan pengguna umum tidak memiliki akses untuk melakukan prediksi. Halaman prediksi menampilkan judul "Prediksi NBM" sebagaimana ditunjukkan pada Gambar 17.

Gambar 17. Halaman Prediksi NBM

Di bagian atas halaman terdapat informasi tentang fitur prediksi ML yang menjelaskan bahwa sistem menggunakan model LSTM yang dilatih dengan data historis lengkap dari tahun 1993 hingga 2024. Untuk menghasilkan prediksi, model menganalisis pola konsumsi dari 6 bulan terakhir untuk menghasilkan proyeksi yang akurat.

Section "Parameter Prediksi" merupakan bagian utama untuk melakukan input prediksi. Parameter pertama adalah "Kelompok Pangan" yang berisi dropdown menu untuk memilih kelompok komoditas. Pilihan kelompok yang tersedia meliputi Padi - Padian, Makanan berpati, Gula, Buah/Biji Berminyak, Buah-buahan, Sayur-sayuran, Daging, Telur, Susu, Ikan, dan Minyak dan Lemak. Setelah memilih kelompok, pengguna dapat memilih parameter kedua yaitu "Komoditi" yang akan menampilkan dropdown berisi daftar komoditi sesuai dengan kelompok yang telah dipilih sebelumnya. Sebagai contoh, jika pengguna memilih kelompok Padi - Padian, maka pilihan komoditi yang muncul adalah Gabah, Beras, Jagung, Jagung Basah, Gandum, dan Tepung Gandum. Terdapat catatan bahwa komoditi akan muncul setelah memilih kelompok terlebih dahulu.

Parameter ketiga adalah "Bulan Prediksi" yang memungkinkan pengguna menentukan jumlah bulan ke depan yang akan diprediksi. Pengguna dapat memasukkan angka pada input field yang disediakan untuk menentukan periode prediksi yang diinginkan. Setelah semua parameter diisi, pengguna dapat mengklik tombol "Jalankan Prediksi" berwarna ungu untuk memulai proses prediksi.

Di bawah parameter prediksi terdapat section "Cara Kerja" yang menjelaskan mekanisme kerja sistem prediksi. Penjelasan mencakup bahwa model dilatih dengan data historis lengkap dari tahun 1993 hingga 2024, sistem menganalisis pola 6 bulan terakhir sebagai input, LSTM menghitung tren dan seasonality konsumsi, dan prediksi ditampilkan dengan confidence interval.

Setelah proses prediksi dijalankan, sistem akan menampilkan "Hasil Prediksi" yang mencakup beberapa informasi penting. Di bagian atas hasil prediksi terdapat empat tombol yaitu "Simpan", "Riwayat", "Excel", dan "PDF" yang memungkinkan pengguna untuk menyimpan hasil prediksi, melihat riwayat prediksi sebelumnya, atau mengeksport hasil dalam format Excel atau PDF. Hasil prediksi menampilkan informasi komoditi yang diprediksi beserta kelompoknya, misalnya "Komoditi: Gabah (Padi - Padian)", serta periode prediksi yang diminta, misalnya "Prediksi untuk: 3 bulan ke depan".

Hasil prediksi ditampilkan dalam bentuk tabel yang berisi lima kolom yaitu Periode, Prediksi Kalori/Hari, Batas Bawah, Batas Atas, dan Margin (%). Tabel menampilkan hasil prediksi untuk setiap periode dalam format tahun-bulan, misalnya 2025-01, 2025-02, dan 2025-03. Kolom Prediksi Kalori/Hari menampilkan nilai prediksi konsumsi kalori per hari yang dihasilkan oleh model. Kolom Batas Bawah dan Batas Atas menampilkan confidence interval dari prediksi yang menunjukkan rentang kemungkinan nilai prediksi. Kolom Margin menampilkan persentase margin error dari prediksi. Sebagai contoh, untuk periode 2025-01 ditampilkan prediksi 885.07 kkal/hari dengan batas bawah -606.18, batas atas 2376.32, dan margin 168.5%.

Di bawah tabel hasil prediksi terdapat section "Model Information" yang menampilkan tiga informasi yaitu Model yang digunakan adalah LSTM Enhanced Ensemble, Status model yang menunjukkan Active, dan Version model yaitu 1.0.0. Informasi ini penting untuk dokumentasi dan tracking model yang digunakan dalam menghasilkan prediksi.

Section "Visualisasi Prediksi" menampilkan beberapa grafik untuk memvisualisasikan hasil prediksi. Grafik pertama adalah "Trend Historis vs Prediksi (Continuous Line)" yang menampilkan line chart dengan garis solid untuk data historis dan garis putus-putus untuk data prediksi. Grafik ini memungkinkan pengguna untuk melihat kontinuitas antara data historis dan hasil prediksi serta membandingkan pola tren yang terjadi. Grafik kedua adalah "Confidence Interval" yang menampilkan area chart dengan rentang confidence interval dari hasil prediksi, memberikan visualisasi tingkat kepercayaan prediksi dari waktu ke waktu. Grafik ketiga adalah "Perbandingan Per Periode" yang menampilkan bar chart membandingkan nilai historis dengan nilai prediksi untuk setiap periode, memudahkan pengguna melihat perbandingan langsung antara kedua nilai tersebut.

Section "AI Insights & Recommendations" yang ditandai dengan label "Beta" menyediakan analisis dan rekomendasi otomatis dari sistem AI. Section ini terbagi menjadi beberapa bagian insight. Bagian "Analisis Trend" menampilkan informasi tentang perubahan konsumsi, misalnya "Konsumsi menunjukkan perubahan moderat (-5.8%)" dengan persentase perubahan yang ditampilkan secara jelas. Bagian "Tingkat Risiko" menampilkan level risiko dari prediksi beserta score-nya, misalnya "Risiko rendah - situasi terkendali" dengan "Level: LOW (Score: 1)". Bagian "Volatilitas" menampilkan tingkat stabilitas prediksi dengan coefficient of variation, misalnya "Prediksi menunjukkan stabilitas tinggi" dengan "Coefficient of Variation: 3.61%". Bagian "Perbandingan Historis" membandingkan rata-rata prediksi dengan rata-rata historis, misalnya "Prediksi rata-rata (876.06) sejalan dengan historis (833.88)" dengan "Selisih: +5.1%".

Bagian "Rekomendasi" menampilkan saran aksi yang sebaiknya dilakukan berdasarkan hasil analisis AI. Rekomendasi ditampilkan dengan badge level risiko, misalnya badge hijau dengan label "LOW" dan teks rekomendasi "Lanjutkan monitoring rutin dan maintain stok sesuai prediksi". Di bawah rekomendasi terdapat "Ringkasan" yang memberikan kesimpulan singkat dari seluruh analisis, misalnya "Prediksi menunjukkan trend sedikit menurun dengan tingkat risiko low. Konsumsi menunjukkan perubahan moderat (-5.8%)".

Section terakhir adalah "Data Historis (6 Bulan Terakhir)" yang menampilkan tabel berisi data historis yang digunakan sebagai input untuk prediksi. Tabel memiliki lima kolom yaitu Tahun, Bulan, Komoditi, Kalori/Hari, dan Tren. Kolom Tren menampilkan indikator visual dan persentase perubahan dari bulan sebelumnya. Data ditampilkan dari bulan terbaru ke bulan terlama, dengan bulan terbaru diberi label "Terkini". Sebagai contoh, untuk bulan 12 tahun 2024 ditampilkan nilai 295.88 kkal/hari dengan label "Terkini", bulan 11 menampilkan 1909.87 dengan indikator naik "+545.5%", dan seterusnya. Tabel ini memberikan konteks data historis yang menjadi dasar perhitungan prediksi oleh model LSTM.

• Prosedur Lihat Data Historis

Pengguna dapat melihat riwayat prediksi yang telah dilakukan sebelumnya melalui halaman Riwayat Prediksi NBM yang dapat diakses melalui URL /pemerintah/prediksi-nbm/history sebagaimana ditunjukkan pada Gambar 18. Halaman ini menampilkan judul "Riwayat Prediksi NBM" dengan subjudul "Kelola dan tinjau kembali prediksi yang telah disimpan". Di pojok kanan atas halaman terdapat tombol "Prediksi Baru" berwarna biru yang dapat diklik untuk kembali ke halaman prediksi dan membuat prediksi baru.

Gambar 18. Halaman Riwayat Prediksi NBM

Di bagian atas daftar riwayat terdapat fitur pencarian dan filter untuk memudahkan pengguna menemukan prediksi tertentu. Terdapat input field "Filter Komoditi..." yang memungkinkan pengguna mencari prediksi berdasarkan nama komoditi. Di sebelah input field terdapat checkbox "Hanya Bookmark" yang ketika diaktifkan akan menampilkan hanya prediksi yang telah di-bookmark oleh pengguna. Terdapat juga tombol "Filter" untuk menerapkan filter tambahan pada daftar riwayat.

Riwayat prediksi ditampilkan dalam bentuk kartu yang berisi informasi lengkap tentang prediksi yang telah dilakukan. Setiap kartu riwayat menampilkan nama komoditi yang diprediksi, misalnya "Gabah", beserta badge kelompok komoditas "Padi - Padian" dengan background biru. Kartu juga menampilkan beberapa informasi penting yaitu periode prediksi yang menunjukkan durasi prediksi yang dilakukan, misalnya "Periode Prediksi: 3 bulan", rata-rata nilai prediksi dalam format "Rata-rata: 876.06 kal/hari", dan tanggal serta waktu pembuatan prediksi dalam format "Tanggal: 05 Dec 2025, 20:47".

Di bagian bawah setiap kartu terdapat tiga badge informasi teknis. Badge pertama menampilkan jumlah data point yang digunakan dalam prediksi, misalnya "3 data point" dengan ikon grafik. Badge kedua menampilkan versi model yang digunakan, misalnya "Model: 1.0.0" dengan ikon mikroskop. Badge ketiga menampilkan informasi apakah prediksi dilengkapi dengan confidence interval, ditandai dengan teks "Dengan Confidence Interval" dan ikon centang berwarna hijau.

Di sisi kanan setiap kartu riwayat terdapat tombol aksi yang dapat dilakukan terhadap prediksi tersebut. Tombol pertama adalah tombol bookmark berwarna abu-abu dengan ikon bookmark yang dapat diklik untuk menandai prediksi sebagai favorit. Tombol kedua adalah tombol lihat detail berwarna biru dengan ikon mata yang ketika diklik akan membuka detail lengkap dari prediksi tersebut termasuk tabel hasil, visualisasi grafik, dan analisis AI. Tombol ketiga adalah tombol refresh berwarna hijau dengan ikon panah melingkar yang dapat digunakan untuk menjalankan ulang prediksi dengan parameter yang sama. Tombol keempat adalah tombol hapus berwarna merah dengan ikon tempat sampah yang dapat digunakan untuk menghapus riwayat prediksi dari daftar.

Halaman riwayat ini memungkinkan pengguna untuk mengelola prediksi yang telah dibuat, membandingkan hasil prediksi dari waktu ke waktu, dan mengakses kembali detail prediksi tanpa perlu menjalankan ulang proses prediksi. Fitur bookmark memudahkan pengguna untuk menandai prediksi penting yang sering digunakan sebagai referensi.

Prosedur penggunaan ini telah divalidasi melalui UAT dengan 15 users dan continuously updated berdasarkan user feedback.

f. FastAPI ML Service Implementation

Implementasi FastAPI ML Service merupakan komponen yang bertanggung jawab untuk model serving dan inference prediksi konsumsi kalori NBM dalam sistem SIKOLBIA. Service ini dibangun menggunakan framework FastAPI karena kemampuannya dalam menangani HTTP request dengan cepat, dokumentasi API otomatis, dan integrasi dengan library Python untuk komputasi ilmiah yang dibutuhkan untuk operasi machine learning.

• Arsitektur FastAPI Service

FastAPI ML Service diimplementasikan sebagai RESTful API yang berjalan secara terpisah dari aplikasi Laravel dan berkomunikasi melalui HTTP protocol dengan format JSON. Arsitektur ini memisahkan tanggung jawab dimana Laravel menangani business logic, autentikasi pengguna, dan persistensi data, sementara FastAPI fokus pada tugas komputasi seperti feature engineering, model inference, dan analisis statistik. Gambar 19 menunjukkan arsitektur komunikasi antara Laravel dan FastAPI service.

![Gambar 19. Arsitektur Komunikasi Laravel-FastAPI ML Service](gambar_19_arsitektur_laravel_fastapi.svg)

Gambar 19. Arsitektur Komunikasi Laravel-FastAPI ML Service

Service menggunakan asynchronous programming untuk menangani beberapa concurrent request tanpa blocking. Production model dimuat sekali pada saat aplikasi startup dan di-reuse untuk setiap request tanpa overhead re-loading.

Struktur direktori FastAPI service diorganisasi dengan root directory `sikolbia-ml` yang berisi main application file `nbm_api.py` sebagai entry point aplikasi, directory `ml_models` yang menyimpan model training scripts dan utilities termasuk `production_model.py` untuk model class definition, `data_loader.py` untuk data loading, dan `data_preprocessing_monthly.py` untuk preprocessing pipeline. Directory `models` menyimpan trained model artifacts dalam subdirectory `nbm_production` yang berisi `nbm_production_model.pkl` sebagai serialized HuberRegressor ensemble dan `model_info.json` untuk model metadata. File konfigurasi mencakup `requirements.txt` untuk Python dependencies, `Dockerfile` untuk containerization, dan `docker-compose.yml` untuk service orchestration. Tabel 14 menunjukkan struktur direktori lengkap FastAPI ML Service.

**Tabel 14. Struktur Direktori FastAPI ML Service**

| Direktori/File | Deskripsi | Fungsi |
|----------------|-----------|--------|
| `nbm_api.py` | Main application file | Entry point FastAPI, endpoint definitions, request handling |
| `ml_models/` | Model training directory | Menyimpan scripts untuk training dan preprocessing |
| `ml_models/production_model.py` | Production model class | Class definition untuk NBMProductionModel |
| `ml_models/data_loader.py` | Data loading utilities | Functions untuk load data NBM dari database |
| `ml_models/data_preprocessing_monthly.py` | Preprocessing pipeline | Monthly aggregation dan feature engineering |
| `models/nbm_production/` | Model artifacts directory | Menyimpan trained model dan metadata |
| `models/nbm_production/nbm_production_model.pkl` | Serialized model | HuberRegressor ensemble dalam format pickle |
| `models/nbm_production/model_info.json` | Model metadata | Version, metrics, training date |
| `requirements.txt` | Python dependencies | Daftar package yang dibutuhkan |
| `Dockerfile` | Container image definition | Instructions untuk build Docker image |
| `docker-compose.yml` | Service orchestration | Multi-container configuration |
| `tests/` | Test directory | Unit tests dan integration tests |

• Pydantic Models untuk Request/Response Validation

FastAPI menggunakan library Pydantic untuk automatic data validation dan serialization/deserialization JSON ke Python objects. Implementasi mendefinisikan tiga Pydantic models utama untuk type safety dan input validation. Model `NBMDataPoint` merepresentasikan single time series observation dengan field `tahun` bertipe integer dengan constraint greater than or equal 2000 dan less than or equal 2100, `bulan` dengan range 1 hingga 12, `kelompok` string dengan length 2 characters untuk kode kelompok komoditas, `komoditi` string dengan length 4 characters, dan `kalori_hari` float dengan constraint greater than 0.

Model `PredictionRequest` mendefinisikan payload untuk prediction endpoint dengan field `data_points` berupa list dari `NBMDataPoint` dengan constraint minimum 6 items dan maksimum 6 items sesuai dengan sequence length requirement model LSTM enhanced ensemble, serta `n_periods` optional integer dengan default 1 dan range 1 hingga 12 untuk menentukan forecast horizon.

Model `PredictionResponse` mendefinisikan struktur JSON response yang dikembalikan ke Laravel dengan field `success` boolean indicating prediction status, `predictions` list float berisi predicted values untuk setiap future period, `confidence_intervals` list dictionary dengan keys `lower_bound`, `upper_bound`, dan `margin_percent`, `model_info` dictionary berisi model metadata, `input_summary` dictionary dengan descriptive statistics dari input data, `model_version` string, `prediction_timestamp` ISO format datetime, dan `has_data` boolean flag.

Listing 1 menunjukkan implementasi Pydantic models dalam file `nbm_api.py`:

**Listing 1. Pydantic Models untuk Request/Response Validation**

```python
from pydantic import BaseModel, Field
from typing import List, Dict, Any, Optional

class NBMDataPoint(BaseModel):
    tahun: int = Field(..., ge=2000, le=2100)
    bulan: int = Field(..., ge=1, le=12)
    kelompok: str = Field(..., min_length=2, max_length=2)
    komoditi: str = Field(..., min_length=4, max_length=4)
    kalori_hari: float = Field(..., gt=0)

class PredictionRequest(BaseModel):
    data_points: List[NBMDataPoint] = Field(..., min_items=6, max_items=6)
    n_periods: Optional[int] = Field(default=1, ge=1, le=12)

class PredictionResponse(BaseModel):
    success: bool
    predictions: List[float]
    confidence_intervals: List[Dict[str, float]]
    model_info: Dict[str, Any]
    input_summary: Dict[str, Any]
    model_version: str
    prediction_timestamp: str
    has_data: bool
```

Pydantic validation memberikan automatic error messages jika request tidak memenuhi constraints, seperti HTTP 422 Unprocessable Entity ketika `data_points` kurang dari 6 atau field `kalori_hari` bernilai negatif atau nol. Hal ini mengurangi kebutuhan manual validation code dan meningkatkan code clarity.

• Model Loading dan Initialization

Production model dimuat pada saat aplikasi startup menggunakan decorator `@app.on_event("startup")` untuk memastikan model ready sebelum menerima request pertama. Fungsi `startup_event()` menggunakan asynchronous coroutine untuk non-blocking model loading. Proses loading meliputi verifikasi path existence untuk directory `ml_models/models/nbm_production`, deserializing model artifact menggunakan `joblib.load()` dari file `nbm_production_model.pkl`, dan memuat model metadata dari `model_info.json` jika tersedia.

Listing 2 menunjukkan implementasi model loading pada startup:

**Listing 2. Model Loading pada Aplikasi Startup**

```python
import joblib
import json
import logging

logger = logging.getLogger(__name__)
production_model = None
model_info = {}

@app.on_event("startup")
async def startup_event():
    global production_model, model_info
    try:
        logger.info("Loading NBM production model...")
        model_path = "ml_models/models/nbm_production"
        
        if not os.path.exists(model_path):
            logger.error(f"Model path not found: {model_path}")
            raise FileNotFoundError(f"Model directory not found: {model_path}")
        
        model_file = f"{model_path}/nbm_production_model.pkl"
        logger.info(f"Loading model from: {model_file}")
        production_model = joblib.load(model_file)
        logger.info(f"Model loaded successfully! Type: {type(production_model)}")
        
        info_file = f"{model_path}/model_info.json"
        if os.path.exists(info_file):
            with open(info_file, 'r') as f:
                model_info = json.load(f)
            logger.info("Model info loaded")
        
        logger.info("NBM Production Model ready!")
    except Exception as e:
        logger.error(f"Failed to load model: {str(e)}")
        raise
```

Model artifacts disimpan sebagai global variables `production_model` dan `model_info` agar accessible oleh semua request handlers tanpa passing explicit parameters. Pendekatan ini aman dalam FastAPI karena implementasi menggunakan single Uvicorn process yang memuat model sekali saat startup dan mereuse untuk semua requests. Untuk production scaling, deployment dapat menggunakan multiple container replicas dengan load balancer Nginx, dimana setiap container instance memiliki copy independen dari model dalam memory space-nya.

Logging diimplementasikan menggunakan Python logging module dengan level INFO untuk tracking model loading progress dan ERROR untuk handling exceptions. Jika model loading gagal, aplikasi akan raise exception dan exit, mencegah API dari accepting requests dalam invalid state tanpa model yang ter-load.

Alternative loading strategy yang dipertimbangkan namun tidak diimplementasikan termasuk lazy loading model pada first request yang memiliki drawback cold start latency, dan model caching dengan expiration time untuk automatic reloading yang berguna untuk continuous learning scenarios namun menambah complexity tanpa clear benefit untuk use case ini.

• Feature Engineering dan Input Preprocessing

Fungsi `prepare_input_for_model()` mengimplementasikan transformation pipeline yang mengkonversi list dari `NBMDataPoint` API request menjadi numpy array dengan shape yang expected oleh production model. Pipeline meliputi ekstraksi sequence kalori dari 6 data points historis ke numpy array, cyclical encoding untuk temporal features dengan month_sin dan month_cos menggunakan sine dan cosine transformations untuk merepresentasikan sifat cyclical dari bulan tanpa discontinuity antara December dan January.

Feature vector untuk setiap time step dikonstruksi dengan 9 features yang terdiri dari `kalori_hari` sebagai raw calorie value, `tahun` dan `bulan` sebagai temporal identifiers, `kode_kelompok` dan `kode_komoditi` di-convert ke integer untuk numerical representation, duplicated kelompok dan komoditi codes untuk model compatibility, serta `month_sin` dan `month_cos` sebagai encoded seasonal features. Output final adalah 3D numpy array dengan shape (1, 6, 9) dimana dimension pertama adalah batch size 1 untuk single prediction request, dimension kedua adalah sequence length 6 sesuai model configuration, dan dimension ketiga adalah feature dimension 9.

Preprocessing ini penting untuk memastikan model menerima input dalam format yang identik dengan data yang digunakan selama training phase, menjaga consistency yang diperlukan untuk prediction accuracy. Setiap deviation dalam feature ordering atau scaling akan menghasilkan meaningless predictions karena trained weights tidak corresponding dengan input distribution.

• Multi-Step Ahead Prediction Implementation

Endpoint `/predict` mengimplementasikan recursive multi-step ahead forecasting strategy untuk generating predictions hingga 12 bulan ke depan. Algoritma menggunakan autoregressive approach dimana prediction periode sebelumnya digunakan sebagai input untuk prediction periode berikutnya, dengan sliding window mechanism.

Proses dimulai dengan initializing `current_sequence` dengan 6 data points historis dari request, loop iterasi untuk `n_periods` yang diminta user dengan preparing input features dari current sequence, calling `production_model.predict()` untuk generate single-step prediction, appending prediction ke results list, dan calculating confidence interval dengan consideration uncertainty growth untuk far-horizon predictions.

Confidence interval calculation mengimplementasikan uncertainty quantification dengan base standard deviation dihitung dari 6 historical values, uncertainty factor meningkat dengan step number menggunakan formula `1 + (step * 0.1)` untuk reflecting increased uncertainty untuk longer forecast horizons, margin calculated sebagai ±1.96 standard deviations corresponding ke 95% confidence level assuming normal distribution, dan margin percentage sebagai interpretable metric untuk business users.

Setelah prediction untuk current step, jika belum mencapai final period, algoritma melakukan sequence update dengan removing oldest data point dari window, creating new `NBMDataPoint` dengan predicted value sebagai `kalori_hari`, incrementing month dengan proper year rollover handling, dan appending new point ke sequence untuk maintaining window size 6.

Pendekatan ini disebut recursive atau iterative forecasting dan memiliki advantage simplicity implementation dan single unified model untuk all horizons, namun disadvantage propagation error karena setiap prediction error akan mempengaruhi predictions berikutnya. Alternative approach direct multi-step forecasting yang melatih separate models untuk setiap horizon tidak diimplementasikan karena computational complexity dan data requirements yang lebih tinggi.

• API Endpoints Specification

FastAPI service menyediakan empat main endpoints untuk different use cases. Tabel 15 menunjukkan spesifikasi lengkap setiap endpoint beserta fungsinya.

**Tabel 15. Spesifikasi API Endpoints FastAPI ML Service**

| Endpoint | Method | Request Body | Response | Status Codes | Fungsi |
|----------|--------|--------------|----------|--------------|--------|
| `/` | GET | - | `{message, status, version, model_loaded}` | 200 | Root endpoint, API info |
| `/health` | GET | - | `{status, model_loaded, timestamp}` | 200, 503 | Health check untuk monitoring |
| `/model/stats` | GET | - | `{model_info, model_loaded, timestamp}` | 200, 503 | Model metadata dan statistik |
| `/predict` | POST | `{data_points, n_periods}` | `{success, predictions, confidence_intervals, model_info, input_summary, model_version, prediction_timestamp, has_data}` | 200, 422, 503, 500 | Prediksi konsumsi kalori |

Endpoint `GET /` adalah root endpoint yang returns basic API information termasuk message, status operational, version number, dan model_loaded status untuk quick health verification tanpa detailed checks.

Endpoint `GET /health` digunakan untuk health check monitoring yang returns detailed health status dengan model loading status, current timestamp ISO format, dan HTTP 200 OK jika service healthy atau 503 Service Unavailable jika model belum ter-load. Endpoint ini di-poll secara periodic oleh Docker healthcheck dan monitoring tools untuk ensuring service availability.

Endpoint `GET /model/stats` mengembalikan model metadata dan statistics dengan informasi algorithm type HuberRegressor, training date, evaluation metrics RMSE MAE MAPE dari validation set, feature importance jika available, dan model version number untuk reproducibility. Endpoint ini useful untuk documentation purposes dan debugging prediction issues.

Endpoint `POST /predict` adalah core endpoint untuk calorie consumption prediction dengan request body berisi `data_points` array 6 historical observations dan `n_periods` forecast horizon, response berisi `predictions` array dengan predicted values, `confidence_intervals` dengan lower_bound upper_bound dan margin_percent untuk setiap prediction, `model_info` metadata, `input_summary` dengan descriptive statistics, dan `prediction_timestamp`. Endpoint ini handle error scenarios dengan HTTP 422 untuk invalid input tidak memenuhi Pydantic constraints, HTTP 503 jika model belum ter-load atau unavailable, dan HTTP 500 untuk unexpected errors selama prediction process dengan detailed error messages di-log untuk debugging.

Listing 3 menunjukkan contoh request dan response untuk endpoint `/predict`:

**Listing 3. Contoh Request dan Response Endpoint /predict**

```json
// Request POST /predict
{
  "data_points": [
    {
      "tahun": 2024,
      "bulan": 7,
      "kelompok": "01",
      "komoditi": "0101",
      "kalori_hari": 892.45
    },
    {
      "tahun": 2024,
      "bulan": 8,
      "kelompok": "01",
      "komoditi": "0101",
      "kalori_hari": 898.32
    },
    // ... 4 data points lainnya
  ],
  "n_periods": 3
}

// Response 200 OK
{
  "success": true,
  "predictions": [905.23, 912.45, 918.67],
  "confidence_intervals": [
    {
      "lower_bound": 769.45,
      "upper_bound": 1041.01,
      "margin_percent": 15.0
    },
    {
      "lower_bound": 775.58,
      "upper_bound": 1049.32,
      "margin_percent": 15.0
    },
    {
      "lower_bound": 780.87,
      "upper_bound": 1056.47,
      "margin_percent": 15.0
    }
  ],
  "model_info": {
    "name": "NBM Production Model",
    "version": "1.0.0",
    "algorithm": "HuberRegressor Ensemble"
  },
  "input_summary": {
    "total_points": 6,
    "kelompok": "01",
    "komoditi": "0101",
    "avg_kalori": 895.23,
    "min_kalori": 885.12,
    "max_kalori": 902.45,
    "n_periods": 3
  },
  "model_version": "1.0.0",
  "prediction_timestamp": "2024-12-16T10:30:45.123456",
  "has_data": true
}
```

• Error Handling dan Logging Strategy

Implementasi menggunakan try-except blocks untuk graceful error handling dengan specific exception types untuk different failure modes. Exception handling hierarchy dimulai dengan Pydantic `ValidationError` untuk input validation failures yang automatically di-handle oleh FastAPI dan mengembalikan 422 response dengan detailed validation errors, `FileNotFoundError` untuk missing model artifacts pada startup yang log error message dan exit application, dan generic `Exception` untuk unexpected errors yang log full traceback untuk debugging dan raise `HTTPException` dengan status 500.

Logging diimplementasikan menggunakan standard library logging module dengan configuration pada application startup untuk format `timestamp - logger_name - level - message`, StreamHandler untuk output ke stdout yang captured oleh Docker logs, dan INFO level untuk routine operations dan ERROR level untuk failures. Critical log points mencakup application startup dengan model loading progress, setiap prediction request dengan input parameters, prediction success dengan returned values dan confidence intervals, serta error conditions dengan full exception tracebacks.

Logging strategy ini penting untuk production observability, memungkinkan debugging issues, monitoring request patterns dan volumes, serta analyzing prediction behavior over time. Logs dapat di-aggregate menggunakan tools seperti ELK stack atau forwarded ke cloud logging services untuk centralized monitoring dalam production deployments.

• CORS Configuration untuk Cross-Origin Requests

Service menggunakan `CORSMiddleware` dari FastAPI untuk handling Cross-Origin Resource Sharing yang necessary karena Laravel frontend dan FastAPI service berjalan pada ports berbeda, considered different origins oleh browser same-origin policy. CORS middleware dikonfigurasi dengan `allow_origins=["*"]` untuk permitting requests dari any origin, suitable untuk development namun sebaiknya di-restrict ke specific Laravel application URL dalam production deployment.

Configuration `allow_credentials=True` memungkinkan cookies dan authorization headers dalam cross-origin requests, `allow_methods=["*"]` permit semua HTTP methods GET POST PUT DELETE yang necessary untuk RESTful API, dan `allow_headers=["*"]` allow arbitrary request headers yang diperlukan untuk content negotiation dan authentication. CORS configuration ini memastikan Laravel dapat successfully make HTTP requests ke FastAPI endpoint tanpa browser blocking dengan CORS policy violations.

• Docker Containerization dan Deployment Configuration

FastAPI service di-containerize menggunakan Docker untuk consistency across development dan production environments. `Dockerfile` menggunakan multi-stage build pattern dengan base image `python:3.10-slim` untuk minimizing image size sambil menyediakan Python runtime, working directory `/app` untuk organizing application files, dan copying requirements.txt terlebih dahulu untuk leveraging Docker layer caching ketika dependencies tidak berubah.

Dependencies di-install menggunakan `pip install --no-cache-dir -r requirements.txt` untuk avoiding cached packages yang consuming space, copying application code dan model artifacts ke container dengan structure identik dengan development environment, expose port 8082 untuk accepting HTTP requests, dan CMD instruction `uvicorn nbm_api:app --host 0.0.0.0 --port 8082` untuk starting FastAPI application dengan Uvicorn ASGI server.

File `docker-compose.yml` mendefinisikan multi-service configuration untuk orchestrating FastAPI ML service bersama Laravel app, MySQL database, Redis cache, dan Nginx reverse proxy. Service `fastapi-ml` dikonfigurasi dengan build context pointing ke `sikolbia-ml` directory, container_name `fastapi-ml-service`, ports mapping `8082:8082` untuk external access, volumes mounting untuk hot-reloading code changes during development, environment variables untuk configuration, depends_on MySQL untuk ensuring database ready before service starts, restart policy `unless-stopped` untuk automatic recovery dari failures, dan health check dengan command `curl -f http://localhost:8082/health` interval 30s timeout 10s retries 3 untuk monitoring service health.

Health check configuration penting untuk orchestration karena memungkinkan Docker dan container orchestrators seperti Kubernetes untuk detecting service failures dan automatic restart unhealthy containers, ensuring high availability. Service dapat di-deploy ke production menggunakan `docker-compose up -d` untuk starting containers dalam detached mode, dimonitor menggunakan `docker-compose logs -f fastapi-ml` untuk real-time log streaming, dan di-scaled horizontal dengan multiple replicas jika needed menggunakan Docker Swarm atau Kubernetes.

• Integration dengan Laravel Backend

Aplikasi Laravel mengkonsumsi FastAPI service melalui HTTP client menggunakan Guzzle HTTP library. `NBMPredictionController` dalam Laravel mengimplementasikan service layer yang abstracts API communication details. Method `predict()` menerima request dari user melalui web interface, query 6 bulan data historis dari MySQL database menggunakan Eloquent ORM dengan filtering by kelompok dan komoditi, construct request payload sesuai FastAPI `PredictionRequest` schema, dan make HTTP POST request ke FastAPI endpoint dengan URL dari configuration `config/services.php` key `nbm_prediction.api_url`, timeout 30 seconds untuk allowing sufficient time untuk model inference.

Response handling meliputi checking HTTP status code 200 untuk success, parsing JSON response body ke associative array, extracting predictions dan confidence intervals, formatting data untuk display di Livewire component, dan saving prediction hasil ke `prediction_histories` table untuk audit trail dan future reference. Error handling mencakup catching `GuzzleException` untuk network failures atau timeouts, checking status code 422 untuk validation errors yang indicate data quality issues, handling 503 errors yang suggest FastAPI service unavailable atau model tidak ter-load, dan logging errors dengan context untuk debugging dan displaying user-friendly error messages.

Integration testing dilakukan menggunakan Laravel HTTP Tests untuk verifying end-to-end workflow dari user request hingga prediction response, mocking FastAPI responses untuk unit testing Laravel controllers tanpa dependency on actual ML service, dan performance testing untuk measuring response time under load dan identifying bottlenecks. Integration point ini penting karena any mismatch dalam data format atau contract violations akan menyebabkan prediction failures yang visible ke end users.

• Model Versioning dan Update Strategy

Production deployment memerlukan strategy untuk managing model updates ketika retrained models dengan improved accuracy tersedia. Implementasi current menggunakan simple versioning dengan `model_version` field dalam `model_info.json` yang di-increment untuk setiap major model update. Update process meliputi training new model version menggunakan latest data atau improved algorithms, evaluating new model pada held-out test set untuk ensuring superior performance versus current production model, serializing new model artifacts dengan versioned filename atau directory structure, dan replacing existing model artifacts dengan atomic file operations.

Service restart required untuk loading new model karena model di-load pada startup. Zero-downtime deployment dapat dicapai menggunakan blue-green deployment pattern dengan two FastAPI service instances, load balancer routing traffic ke blue instance, deploying updated model ke green instance dan verifying health, switching load balancer ke green instance untuk making it active, dan monitoring for issues dengan ability untuk quick rollback ke blue instance jika problems detected.

Alternative versioning strategy yang lebih sophisticated termasuk A/B testing framework untuk comparing multiple model versions dengan routing subset traffic ke each version dan monitoring comparative performance, canary releases untuk gradually rolling out new models dengan limited blast radius jika issues emerge, dan feature flags untuk enabling/disabling model versions without redeployment. Strategies ini menambah operational complexity namun provide safer update mechanisms untuk high-stakes production systems.

• Performance Optimization dan Caching Considerations

FastAPI service performance dioptimasi melalui beberapa strategies dengan model loaded once pada startup dan reused untuk all requests avoiding expensive deserialization overhead, numpy operations vectorized untuk efficient computation leveraging CPU SIMD instructions, feature engineering functions avoid unnecessary computations dan loops, dan asynchronous request handling memungkinkan concurrent processing multiple predictions.

Potential optimization yang dapat diimplementasikan untuk higher load scenarios termasuk response caching untuk identical requests menggunakan Redis dengan TTL untuk reducing redundant predictions, request batching untuk grouping multiple predictions dan processing them together untuk amortizing model loading overhead, model quantization untuk reducing memory footprint dan inference latency dengan minimal accuracy loss, dan GPU acceleration menggunakan CUDA-enabled libraries jika available untuk significantly faster matrix operations.

Benchmarking measurements pada development environment dengan laptop MSI GF63 menunjukkan average response time 150-300 milliseconds untuk single prediction request dengan 6-month horizon, throughput 30-50 requests per second dengan single Uvicorn worker, memory footprint sekitar 500-700 MB including loaded model dan Python runtime, dan cold start time 3-5 seconds untuk loading model dari disk pada service startup. Tabel 16 menunjukkan hasil benchmarking performance FastAPI ML Service.

**Tabel 16. Hasil Benchmarking FastAPI ML Service**

| Metrik | Nilai | Kondisi | Keterangan |
|--------|-------|---------|------------|
| Average Response Time | 150-300 ms | Single request | Untuk prediksi 1-6 bulan |
| Throughput | 30-50 req/s | Single Uvicorn worker | Concurrent requests |
| Memory Footprint | 500-700 MB | Model loaded | Includes Python runtime |
| Cold Start Time | 3-5 seconds | Application startup | Model loading dari disk |
| CPU Usage | 40-60% | Peak load | Intel Core i5-10500H |
| Model Inference Time | 50-100 ms | Per prediction | Pure computation time |

Production deployment recommendations meliputi deploying multiple Docker container replicas yang masing-masing menjalankan single Uvicorn worker process, implementing Redis caching layer untuk frequently requested predictions, horizontal scaling dengan container orchestration (Docker Compose atau Kubernetes) dimana Nginx load balancer mendistribusikan traffic ke multiple container instances, dan monitoring performance metrics dengan alerting on degradation untuk proactive issue detection. Setiap container instance berjalan independen dengan model ter-load di memory masing-masing, memberikan isolation dan fault tolerance yang baik. Gambar 20 menunjukkan diagram deployment production dengan multiple container instances dan load balancer.

![Gambar 20. Arsitektur Production Deployment FastAPI ML Service](gambar_20_production_deployment.svg)

Gambar 20. Arsitektur Production Deployment FastAPI ML Service

## 4.4. Expert Validation

Setelah implementasi awal model dan sistem berjalan, dilakukan validasi dengan para ahli domain untuk memastikan bahwa output sistem sesuai dengan kebutuhan operasional dan memiliki interpretability yang memadai bagi end-users. Proses validasi ini melibatkan iterasi feedback loop dengan stakeholders dari Badan Pangan Nasional, Kementerian Pertanian, dan akademisi dari perguruan tinggi yang memiliki expertise di bidang ketahanan pangan dan agricultural forecasting.

a. Proses Validasi dengan Domain Experts

b. Temuan dan Feedback Kritis

c. Iterasi Perbaikan

## 4.5. Product Revision Post-Validation

Berdasarkan hasil expert validation dan preliminary testing, dilakukan comprehensive revision pada model arsitektur dan infrastruktur untuk meningkatkan robustness dan operational reliability. Fase ini fokus aspek teknik pada fine-tuning yang tidak teridentifikasi pada tahap desain awal namun muncul sebagai pain points ketika sistem dioperasikan dalam kondisi mendekati production environment.

a. Architectural Improvements pada Model

b. Infrastructure Hardening

## 4.6. Early Test

Tahap Early Test merupakan fase krusial dimana model yang telah dirancang dan diimplementasikan menjalani training comprehensive dan evaluasi rigorous menggunakan held-out test set. Fase ini bertujuan untuk memvalidasi bahwa arsitektur ensemble yang dipilih dapat mencapai target performa yang ditetapkan, serta mengidentifikasi karakteristik error dan limitation model sebelum deployment ke production.

a. Setup Lingkungan Training

b. Strategi Pembagian Data

c. Proses Training dengan Cross-Validation

d. Hasil Kuantitatif pada Test Set

e. Analisis Residual Error

f. Feature Importance Analysis dengan SHAP

g. Error Analysis by Horizon

h. Keterbatasan yang Teridentifikasi

## 4.7. Product Revision Post-Early Test

Berdasarkan findings dari early test, dilakukan targeted revisions untuk address keterbatasan yang teridentifikasi, khususnya terkait handling komoditas dengan missing data dan improvement akurasi pada far-horizon predictions. Revisions ini bersifat data-centric (improving training data quality) dan algorithm-centric (enhancing ensemble mechanism).

a. Data Augmentation untuk Komoditas dengan Missing Values

b. Ensemble Gating Enhancement

c. Hasil Retesting

## 4.8. Field Test

Setelah model dan sistem lulus internal testing dengan performa yang memuaskan, dilakukan field test untuk mengevaluasi usability, utility, dan acceptance dari perspektif end-users. Field test menggunakan metodologi User Acceptance Testing (UAT) yang melibatkan representative users dari target stakeholders dalam controlled environment yang mensimulasikan operational use cases.

a. Desain dan Metodologi UAT

b. Skenario Testing dan Task Completion

c. Satisfaction dan Usability Metrics

d. Qualitative Feedback dan Feature Requests

e. Error Tracking dan Issue Resolution

f. Statistical Hypothesis Testing

g. Lessons Learned dan Iterasi

## 4.9. Final Product Revision

Berdasarkan comprehensive feedback dari UAT dan issue tracking, dilakukan final product revision untuk address high-priority improvements sebelum production deployment. Revisions difokuskan pada user-requested features dan bug fixes yang teridentifikasi selama field testing.

a. Implementation Fitur Berdasarkan User Feedback

b. Security Hardening dan Performance Optimization

## 4.10. Dissemination and Documentation

Setelah sistem final tervalidasi dan ter-deploy ke production environment, dilakukan dissemination activities untuk memastikan transfer knowledge dan adopsi fasilitas.

a. Documentation Deliverables

b. Training dan Capacity Building

c. Publication dan Repository Sharing

## 4.11. Pembahasan

Setelah menjalani penyelesaian RnD lifecycle dari research planning hingga dissemination, bagian ini menyediakan reflective discussion mengenai findings, implications, limitations, dan future directions.

a. Interpretasi Hasil Penelitian

b. Implikasi untuk Kebijakan Ketahanan Pangan

c. Keterbatasan Penelitian

d. Rekomendasi untuk Pengembangan Lanjutan

---

DAFTAR PUSTAKA

Adhany, P. C., Wulandari, C., Intan, B., & Santoso, B. (2025). Prediksi Padi Menggunakan Algoritma Long Short Term Memory. Journal of Informatics Management and Information Technology, 5(2), 120–127. https://doi.org/10.47065/jimat.v5i2.496

Alkahfi, C., Kurnia, A., & Saefuddin, A. (2024). Performance Comparison of RNN-Based Models in Forecasting Indonesian Economic and Financial Data Perbandingan Kinerja Model Berbasis RNN pada Peramalan Data Ekonomi dan Keuangan Indonesia. MALCOM: Indonesian Journal of Machine Learning and Computer Science, 4(October), 1235–1243. https://doi.org/10.57152/malcom.v4i4.1415

Arwansyah, A., Suryani, S., SY, H., Usman, U., Ahyuna, A., & Alam, S. (2022). Time Series Forecasting Menggunakan Deep Gated Recurrent Units. Digital Transformation Technology, 4(1), 410–416. https://doi.org/10.47709/digitech.v4i1.4141

ASEAN Secretariat. (2024). Enhancing and Integrating Regional Food Safety to Face the Changing Landscape of Food System and Health Threats. ASEAN Socio-Cultural Community Trend Report No. 4.

Asian Development Bank. (2023). Asian Development Outlook April 2023. In Asian Developement Bank (Issue April).

Badan Pangan Nasional. (2021). Berita Negara. Peraturan Menteri Kesehatan Republik Indonesia Nomor 4 Tahun 2018, 1301, 1–8.

Benos, L., Tagarakis, A. C., Dolias, G., Berruto, R., Kateris, D., & Bochtis, D. (2021). Machine learning in agriculture: A comprehensive updated review. Sensors, 21(11), 1–55. https://doi.org/10.3390/s21113758

BPS. (2023). Proyeksi Penduduk Indonesia 2020–2050 Hasil Sensus Penduduk 2020. In Badan Pusat Statistik.

Cahyani, J., Mujahidin, S., & Fiqar, T. P. (2023). Implementasi Metode Long Short Term Memory (LSTM) untuk Memprediksi Harga Bahan Pokok Nasional. Jurnal Sistem Dan Teknologi Informasi (JustIN), 11(2), 346. https://doi.org/10.26418/justin.v11i2.57395

Fadila, L. Moh. A., & Putri, N. A. (2023). Analisis Perkembangan Ketahanan Pangan di Indonesia : Pendekatan Menggunakan Big Data dan Data Mining. Seminar Nasional Official Statistics, 2023(1), 247–256. https://doi.org/10.34123/semnasoffstat.v2023i1.1890

FAO. (2023). The State of Food Security and Nutrition in the World 2023. In The State of Food Security and Nutrition in the World 2023. https://doi.org/10.4060/cc3017en

Howard, C., & Augustine, M. (2025). Ensemble Methods for Time Series Forecasting in Nigeria: Predicting Agricultural Yields Using Advanced Machine Learning Approaches. Asian Journal of Pure and Applied Mathematics, 7(1), 318–336. https://doi.org/10.56557/ajpam/2025/v7i1205

Iannone, A. (2023). Unveiling the Impact of the COVID-19 Pandemic (2019-2021) on Inequality, Poverty, and Food Security in Indonesia. Politika: Jurnal Ilmu Politik, 14(2), 189–208. https://doi.org/10.14710/politika.14.2.2023.189-208

Kamil, M. Z. F., Purnamasari, R., & Eliskar, Y. (2024). Perancangan Sistem Deploy Untuk Menghubungkan Machine learning Ke Websitesite. E-Proceeding of Engineering, 11(6), 6394–6396. https://openlibrarypublications.telkomuniversity.ac.id/index.php/engineering/article/view/24940

Kementerian Pertanian. (2023). Laporan Kinerja Kementerian Pertanian Tahun 2023. Kementerian Pertanian, 1–230.

Kong, X., Chen, Z., Liu, W., Ning, K., Zhang, L., Muhammad Marier, S., Liu, Y., Chen, Y., & Xia, F. (2025). Deep learning for time series forecasting: a survey. In International Journal of Machine Learning and Cybernetics (Vol. 16, Issues 7–8). Springer Berlin Heidelberg. https://doi.org/10.1007/s13042-025-02560-w

Magalhães, Sais, A. C., & Rossi, F. (2025). Research on Using Ensemble Models to Assess the Impacts of Climate Change on Agriculture Production: A Review. AgriEngineering, 7(7), 1–18. https://doi.org/10.3390/agriengineering7070219

Narkunam, G. A. (2025). Enhancing Agricultural Forecasting with an Ensemble Learning Approach for Broccoli Yield Prediction. Journal of Information Systems Engineering and Management, 10(41s), 105–116. https://doi.org/10.52783/jisem.v10i41s.7754

OECD. (2021). Membangun Ketahanan Pangan dan Mengelola Risiko di Asia Tenggara. In M. G. F. E. B. Suwastoyo (Ed.), Oecd. Yayasan Cipta Sentosa. https://doi.org/10.1787/9789264272392-en

Okpatrioka. (2023). Research and development (R&D) penelitian yang inovatif dalam pendidikan [Innovative research and development (R&D) in education]. Dharma Acariya Nusantara: Jurnal Pendidikan, Bahasa Dan Budaya, 1(1), 86–100.

Opara, I. K., Opara, U. L., Okolie, J. A., & Fawole, O. A. (2024). Machine Learning Application in Horticulture and Prospects for Predicting Fresh Produce Losses and Waste: A Review. Plants, 13(9), 1–21. https://doi.org/10.3390/plants13091200

Paudel, D., Neupane, R. C., Sigdel, S., Poudel, P., & Khanal, A. R. (2023). COVID-19 Pandemic, Climate Change, and Conflicts on Agriculture: A Trio of Challenges to Global Food Security. Sustainability (Switzerland), 15(10), 1–22. https://doi.org/10.3390/su15108280

Pawar, A., Manjula Shenoy, K., Prabhu, S., & Guruprasad Rai, D. (2023). Performance analysis of machine learning algorithms: Single Model VS Ensemble Model. Journal of Physics: Conference Series, 2571(1). https://doi.org/10.1088/1742-6596/2571/1/012007

Raharjo, A. B., Wakhid, M. A., & Purwitasari, D. (2022). Load Forecasting for Daily Load Operational Plan Using Lstm (Case Study: South Sulawesi Sub System). JUTI: Jurnal Ilmiah Teknologi Informasi, 99–108. https://doi.org/10.12962/j24068535.v20i2.a1138

Rozaki, Z. (2021). Food security challenges and opportunities in indonesia post COVID-19. In Advances in Food Security and Sustainability (1st ed., Vol. 6). Elsevier Inc. https://doi.org/10.1016/bs.af2s.2021.07.002

Sarku, R., Clemen, U. A., & Clemen, T. (2023). The Application of Artificial Intelligence Models for Food Security: A Review. Agriculture (Switzerland), 13(10). https://doi.org/10.3390/agriculture13102037

Schröer, C., Kruse, F., & Gómez, J. M. (2021). A systematic literature review on applying CRISP-DM process model. Procedia Computer Science, 181(2019), 526–534. https://doi.org/10.1016/j.procs.2021.01.199

Sekretariat Jendral - Kementrian Pertanian. (2024). Statistik Konsumsi Pangan Tahun 2024. Pusat Data Dan Sistem Informasi Pertanian, Kementrian Pertanian Republik Indonesia, 1–23. https://satudata.pertanian.go.id/details/publikasi/781

Serrano, A. L. M., Rodrigues, G. A. P., Martins, P. H. dos S., Saiki, G. M., Filho, G. P. R., Gonçalves, V. P., & Albuquerque, R. de O. (2024). Statistical Comparison of Time Series Models for Forecasting Brazilian Monthly Energy Demand Using Economic, Industrial, and Climatic Exogenous Variables. Applied Sciences (Switzerland), 14(13), 1–32. https://doi.org/10.3390/app14135846

Siregar, T. M., Banjarnahor, T., Harahap, A., & Lumbanraja, I. (2024). Peranan Matematika dalam Memprediksi Data Ketahanan Pangan Indonesia 5 Tahun Ke Depan. 8, 17013–17020.

Sujarwo, Putra, A. N., Setyawan, R. A., Teixeira, H. M., & Khumairoh, U. (2022). Forecasting Rice Status for a Food Crisis Early Warning System Based on Satellite Imagery and Cellular Automata in Malang, Indonesia. Sustainability (Switzerland), 14(15). https://doi.org/10.3390/su14158972

Sukarna, R. H., & Ansori, Y. (2022). Implementasi Data Mining Menggunakan Metode Naive Bayes Dengan Feature Selection Untuk Prediksi Kelulusan Mahasiswa Tepat Waktu. Jurnal Ilmiah Sains Dan Teknologi, 6(1), 50–61. https://doi.org/10.47080/saintek.v6i1.1467

Sun, C., Pei, M., Cao, B., Chang, S., & Si, H. (2024). A Study on Agricultural Commodity Price Prediction Model Based on Secondary Decomposition and Long Short-Term Memory Network. Agriculture (Switzerland), 14(1). https://doi.org/10.3390/agriculture14010060

Sundram, P. (2023). Food security in ASEAN: progress, challenges and future. Frontiers in Sustainable Food Systems, 7(October), 1–14. https://doi.org/10.3389/fsufs.2023.1260619

Tami, M., & Owda, A. Y. (2024). Efficient commodity price forecasting using long short-term memory model. IAES International Journal of Artificial Intelligence, 13(1), 994–1004. https://doi.org/10.11591/ijai.v13.i1.pp994-1004

Waqas, M., Naseem, A., Humphries, U. W., Hlaing, P. T., Dechpichai, P., & Wangwongchai, A. (2025). Applications of machine learning and deep learning in agriculture: A comprehensive review. Green Technologies and Sustainability, 3(3), 100199. https://doi.org/10.1016/j.grets.2025.100199

Yang, H., Jiao, W., Zouyi, L., Diao, H., & Xia, S. (2025). Artificial intelligence in the food industry: innovations and applications. In Discover Artificial Intelligence (Vol. 5, Issue 1). Springer International Publishing. https://doi.org/10.1007/s44163-025-00296-8

Zhang, L., Wang, R., Li, Z., Li, J., Ge, Y., Wa, S., Huang, S., & Lv, C. (2023). Time-Series Neural Network: A High-Accuracy Time-Series Forecasting Method Based on Kernel Filter and Time Attention. Information (Switzerland), 14(9), 1–18. https://doi.org/10.3390/info14090500
