# MODUL APLIKASI (USER MANUAL)
## SISTEM INFORMASI BELAJAR KUY
### Bimbingan Belajar

---

**Disusun untuk memenuhi tugas Mata Kuliah Analisis dan Desain Sistem (ADS)**  
**Yang dibimbing oleh dosen Teguh Cahyono, S.T., M.Kom**

**Disusun oleh:**
- Maulana Hafez Ahyatara Tempariyawan (H1D021004)
- Ayu Anjar Paramestuti (H1D021007)
- Usriyatul Khamimah (H1D021015)
- Ahita Bisma Adlula (H1D021030)
- Pilar Filino Hadi (H1D021084)

**KEMENTERIAN RISET DAN TEKNOLOGI**  
**UNIVERSITAS NEGERI JENDERAL SOEDIRMAN**  
**FAKULTAS TEKNIK**  
**JURUSAN INFORMATIKA**

JALAN MAYJEND SOENGKONO KM 5, BLATER, KALIMANAH – PURBALINGGA

---

**Direktorat TIK / UNSOED**  
**Nomor Dokumen:** UM – SIBeKu  
**Halaman:** 1 / 40  
**Revisi Tgl:** 24 Desember 2022

---

## DAFTAR ISI

- [BAB I PENDAHULUAN](#bab-i-pendahuluan)
  - [1.1. Pendahuluan](#11-pendahuluan)
  - [1.2. Tujuan Penulisan Aplikasi](#12-tujuan-penulisan-aplikasi)
  - [1.3. Deskripsi Umum Sistem](#13-deskripsi-umum-sistem)
  - [1.4. Deskripsi Dokumen](#14-deskripsi-dokumen)
  - [1.5. Definisi, Istilah, dan Singkatan](#15-definisi-istilah-dan-singkatan)
- [BAB II SUMBER DAYA YANG DIBUTUHKAN](#bab-ii-sumber-daya-yang-dibutuhkan)
  - [2.1. Perangkat Lunak](#21-perangkat-lunak)
  - [2.2. Perangkat Keras](#22-perangkat-keras)
  - [2.3. Sumber Daya yang Dibutuhkan](#23-sumber-daya-yang-dibutuhkan)
  - [2.4. Pengenalan dan Pelatihan](#24-pengenalan-dan-pelatihan)
- [BAB III CARA PENGGUNAAN](#bab-iii-cara-penggunaan)
  - [3.1. Struktur Menu](#31-struktur-menu)
  - [3.2. Cara Penggunaan](#32-cara-penggunaan)

---

## DAFTAR GAMBAR

1. Landing Page
2. Sign In
3. Sign Up
4. Landing Page setelah melakukan Sign In atau Sign Up
5. Bank Materi berdasarkan Mapel
6. Bank Materi berdasarkan Pencarian
7. Bank Soal berdasarkan Mapel
8. Bank Soal berdasarkan Pencarian
9. Jadwal
10. Feedback
11. Memilih Tipe Feedback
12. Mengisi Konten Feedback
13. Tombol My Account
14. Account Profile
15. Edit Account Profile
16. Sign Out
17. Landing Page menu Dashboard
18. Dashboard untuk Pemilik/Pengelola
19. Dashboard untuk Tentor
20. Dashboard Menu User
21. Add User
22. Edit User
23. Dashboard Menu Tentor
24. Add Tentor
25. Edit Tentor
26. Dashboard Menu Mapel
27. Add Mapel
28. Edit Mapel
29. Dashboard Menu Feedback
30. Dashboard Menu Materi
31. Add Materi
32. Edit Materi
33. Dashboard Menu Soal
34. Add Soal
35. Edit Soal
36. Dashboard Menu Jadwal
37. Add Jadwal
38. Edit Jadwal

---

## BAB I PENDAHULUAN

### 1.1. Pendahuluan

Dokumen ini berisi Dokumen Aplikasi atau User Manual untuk SIBeKu (Sistem Informasi Belajar Kuy).

### 1.2. Tujuan Penulisan Aplikasi

Dokumen Penggunaan Aplikasi dibuatnya Sistem Informasi Bimbingan Belajar, Belajar Kuy ini dibuat untuk tujuan berikut:

a. Menggambarkan dan menjelaskan penggunaan aplikasi Belajar Kuy untuk administrator dan pengguna.

b. Sebagai panduan instalasi, konfigurasi dan penggunaan aplikasi Belajar Kuy

c. Pihak-pihak yang berkepentingan dan berhak menggunakan dokumen ini adalah, sebagai berikut:
   1. **Murid**: Murid menggunakan dokumen ini untuk mengetahui cara-cara penggunaan aplikasi Belajar Kuy.
   2. **Tentor**: Tentor menggunakan dokumen ini sebagai panduan penggunaan aplikasi.
   3. **Pengelola**: Pengelola menggunakan dokumen ini sebagai panduan penggunaan aplikasi dan sebagai panduan untuk mereka bagaimana menggunakan dan melakukan pemeliharaan untuk aplikasi Belajar Kuy.
   4. **Pemilik**: Pemilik menggunakan dokumen ini sebagai panduan penggunaan aplikasi dan sebagai panduan untuk mereka bagaimana menggunakan dan melakukan pemeliharaan untuk aplikasi Belajar Kuy.

### 1.3. Deskripsi Umum Sistem

#### 1.3.1. Deskripsi Umum Aplikasi

Perangkat Lunak Sistem Informasi Bimbingan Belajar, Belajar Kuy merupakan sistem yang dibuat untuk memudahkan pemilik dan pengelola dalam pengolahan data dan pembuatan laporan, serta tentor dan murid dalam mengakses materi, jadwal dan soal. Kegiatan yang dapat ditangani oleh perangkat lunak ini meliputi registrasi, login, kelas dan materi, jadwal, bank soal serta feedback.

#### 1.3.2. Deskripsi Umum Kebutuhan Aplikasi yang akan Diimplementasikan

Deskripsi umum kebutuhan aplikasi yang akan diimplementasikan meliputi semua informasi yang bersifat teknis yang menjadi acuan dalam pengembangan aplikasi.

### 1.4. Deskripsi Dokumen

Dokumen URS ini dibagi menjadi tiga bagian utama. Bagian utama berisi penjelasan tentang dokumen URS yang mencakup tujuan pembuatan dokumen ini, lingkup masalah yang diselesaikan oleh perangkat lunak yang dikembangkan, definisi, referensi dan deskripsi umum, dan pengenalan pengguna. Bagian kedua berisi deskripsi kebutuhan user. Bagian ketiga berisi draf wawancara yang dilakukan oleh system analyst dengan narasumber.

### 1.5. Definisi, Istilah, dan Singkatan

| Istilah dan Singkatan | Definisi |
|----------------------|----------|
| **URS** | URS atau User Requirement Specification merupakan sebuah dokumen yang menggambarkan segala sesuatu yang pengguna (user) butuhkan dari sebuah sistem. |
| **SKPL** | SKPL atau Spesifikasi Kebutuhan Perangkat lunak adalah spesifikasi dari perangkat lunak yang akan dikembangkan. |
| **DFD** | DFD atau Data Flow Diagram adalah diagram yang menggambarkan proses aliran data dari sebuah sistem informasi yang dibangun. |
| **RPL** | Perubahan perangkat lunak guna mengembangkan, memelihara, dan membangun kembali dengan menggunakan prinsip rekayasa untuk menghasilkan perangkat lunak yang dapat bekerja lebih efisien dan efektif untuk pengguna. |
| **HyperText Markup Language (HTML)** | HTML adalah bahasa markup standar yang digunakan untuk membuat halaman website dan aplikasi web. |
| **Cascading Style Sheets (CSS)** | CSS merupakan bahasa yang digunakan untuk menentukan tampilan dan format sebuah halaman website. |
| **Hypertext Preprocessor (PHP)** | PHP merupakan bahasa penulisan skrip open-source yang banyak digunakan dalam pemrograman atau pengembangan website (web development). |

---

## BAB II SUMBER DAYA YANG DIBUTUHKAN

### 2.1. Perangkat Lunak

Perangkat lunak yang digunakan dalam pengujian adalah:
- Windows 10 sebagai Operating System
- MySQL sebagai database
- PHP, HTML, CSS dan JavaScript sebagai bahasa pemrograman
- Browser sebagai tempat jalannya aplikasi

### 2.2. Perangkat Keras

1. Perangkat keras yang dilibatkan dalam pengujian aplikasi ini adalah:

**Tabel 2. Spesifikasi ASUS Vivobook TP410UR**

| Spesifikasi | Detail |
|------------|--------|
| Processor | Intel(R) Core(TM) i5-7200U CPU @ 2.50GHz (4 CPUs), ~2.7GHz |
| RAM | 8 GB |
| Resolusi Display | Full HD (1920 x 1080) |
| HDMI | 1 |
| Ukuran Layar | 14.0" |
| Tipe memory | SSD |
| Sistem Operasi | Microsoft® Windows 10 |
| Kapasitas Penyimpanan | 256 GB |
| Tipe Layar | IPS |
| Baterai | 42 Wh |

2. Mouse sebagai peralatan antarmuka.
3. Monitor sebagai peralatan antarmuka.
4. Keyboard sebagai peralatan antarmuka.

### 2.3. Sumber Daya yang Dibutuhkan

Sumber daya manusia yang akan menggunakan aplikasi ini terutama dari sisi Tentor dan Murid adalah sebagai berikut:
- a. Memiliki pemahaman tentang antarmuka komputer
- b. Memiliki pemahaman proses bimbingan belajar online

### 2.4. Pengenalan dan Pelatihan

Sumber daya manusia yang terlibat dalam operasional penggunaan aplikasi ini sehari-hari terlebih dahulu diberikan pengenalan dan pelatihan yang cukup untuk menggunakan aplikasi Belajar Kuy ini.

---

## BAB III CARA PENGGUNAAN

### 3.1. Struktur Menu

Adapun struktur menu pada aplikasi Belajar Kuy adalah sebagai berikut:

1. **Menu Sign In**: Merupakan halaman yang digunakan untuk masuk ke dalam sistem jika telah memiliki akun pada aplikasi ini.

2. **Menu Sign Up**: Merupakan halaman yang digunakan untuk masuk ke dalam sistem bagi yang belum memiliki akun pada aplikasi ini.

3. **Menu Profile Account**: Merupakan halaman yang berisi profil akun.

4. **Menu Bank Materi**: Merupakan halaman yang berisi materi-materi yang ada pada bimbel BelajarKuy dalam bentuk PDF.

5. **Menu Bank Soal**: Merupakan halaman yang berisi soal-soal yang ada pada bimbel BelajarKuy dalam bentuk PDF.

6. **Menu Jadwal**: Merupakan halaman yang berisi jadwal pelaksanaan bimbel BelajarKuy

7. **Menu Feedback**: Merupakan halaman yang berisi kumpulan feedback dari pengguna aplikasi dan halaman untuk memberikan feedback ke website maupun tentor

8. **Menu Dashboard**: Merupakan menu yang digunakan untuk mengelola menu yang terdapat pada aplikasi ini yang hanya dapat dikelola oleh Pemilik, Pengelola dan Tentor. Akan tetapi, untuk Tentor hanya dapat mengelola pada menu Bank Materi dan Bank Soal. Menu-menu tersebut adalah sebagai berikut:
   - a. **Menu User**: Merupakan menu yang digunakan untuk mengelola data user
   - b. **Menu Tentor**: Merupakan menu yang digunakan untuk mengelola data Tentor
   - c. **Menu Mapel**: Merupakan menu yang digunakan untuk mengelola data Mapel
   - d. **Menu Feedback**: Merupakan menu yang digunakan untuk mengelola data Feedback
   - e. **Menu Materi**: Merupakan menu yang digunakan untuk mengelola data Materi
   - f. **Menu Soal**: Merupakan menu yang digunakan untuk mengelola data Soal
   - g. **Menu Jadwal**: Merupakan menu yang digunakan untuk mengelola data Jadwal

### 3.2. Cara Penggunaan

#### 3.2.1. Membuka Situs

Untuk memulai akses aplikasi BelajarKuy ini, pertama-tama masukan file pada GitHub berikut https://github.com/maulanahafez/belajarkuy ke folder XAMPP/htdocs. Setelah itu, aktifkan Apache dan MySQL serta akses melalui link berikut http://localhost/belajarkuy/. Kemudian, tekan enter pada keyboard atau klik Go pada browser. Setelah itu, akan muncul tampilan halaman depan atau landing page website Belajar Kuy seperti pada Gambar 1.

![Gambar 1. Landing Page]

#### 3.2.2. Melakukan Sign In atau Sign Up

Pada Gambar 1 terdapat tombol Sign In, tekan tombol tersebut untuk masuk ke menu Sign In seperti pada Gambar 2. Untuk user yang sudah memiliki akun dapat langsung menginputkan username dan password untuk bisa mengakses menu lainnya.

![Gambar 2. Sign In]

Untuk user yang belum memiliki akun, dapat menekan Sign Up yang terdapat pada Gambar 2 yang kemudian akan masuk ke menu Sign Up seperti pada Gambar 3. User dapat membuat akun baru dengan menginputkan nama, email, nomor telepon, username, password dan konfirmasi password terlebih dahulu agar bisa mengakses menu yang lainnya.

![Gambar 3. Sign Up]

Setelah melakukan Sign In atau Sign Up, user akan diarahkan ke halaman landing page seperti pada Gambar 4.

![Gambar 4. Landing Page setelah melakukan Sign In atau Sign Up]

#### 3.2.3. Mengakses Bank Materi

Untuk masuk ke dalam menu bank materi user dapat klik bank materi yang terdapat pada navigation bar. Kemudian, setelah masuk pada menu bank materi, user dapat mengakses materi berdasarkan mata pelajaran yang diinginkan seperti pada Gambar 5 ataupun mengakses melalui pencarian seperti pada Gambar 6. Pada menu ini, user dapat melihat materi secara online dengan klik "view online" atau download materi dengan klik "download" pada materi yang ingin dilihat atau didownload.

![Gambar 5. Bank Materi berdasarkan Mapel]

![Gambar 6. Bank Materi berdasarkan Pencarian]

#### 3.2.4. Mengakses Bank Soal

Untuk masuk ke dalam menu bank soal user dapat klik bank soal yang terdapat pada navigation bar. Kemudian, setelah masuk pada menu bank materi, user dapat mengakses materi berdasarkan mata pelajaran yang diinginkan seperti pada Gambar 7 ataupun mengakses melalui pencarian seperti pada Gambar 8. Pada menu ini, user dapat melihat materi secara online dengan klik "view online" atau download materi dengan klik "download" pada materi yang ingin dilihat atau didownload.

![Gambar 7. Bank Soal berdasarkan Mapel]

![Gambar 8. Bank Soal berdasarkan Pencarian]

#### 3.2.5. Mengakses Jadwal

Untuk mengakses ke dalam menu bank materi user dapat klik jadwal yang terdapat pada navigation bar. Setelah itu, akan muncul tampilan seperti pada Gambar 9. Pada menu jadwal ini, user dapat melihat langsung jadwal dan mendownload jadwal dengan klik "download jadwal".

![Gambar 9. Jadwal]

#### 3.2.6. Mengakses dan Memberikan Feedback

Untuk mengakses menu feedback, klik feedback pada navigation bar. Kemudian, akan muncul tampilan feedback yang menampilkan review-review user untuk tentor atau website Belajar Kuy seperti pada Gambar 10.

![Gambar 10. Feedback]

Untuk memberikan feedback user dapat menekan tombol review & rating, kemudian memilih akan mereview kepada tentor atau website seperti pada Gambar 11. Setelah itu user dapat memberikan rating dan reviewnya seperti pada Gambar 12 dan klik submit untuk mengirim feedback-nya.

![Gambar 11. Memilih Tipe Feedback]

![Gambar 12. Mengisi Konten Feedback]

#### 3.2.7. Mengakses Profile Account

Pada Profile Account ini user dapat melihat data yang dimasukkan saat Sign Up dan digunakan untuk mengedit data akun. Untuk mengakses menu Profile Account klik tombol "Account" yang terdapat pada pojok kanan atas yang kemudian menampilkan opsi My Account dan Sign Out seperti yang tertera pada Gambar 13. Selanjutnya pilih opsi My Account akan muncul tampilan seperti pada Gambar 14. Setelah itu klik pada tombol "Edit Account" untuk masuk ke menu edit profile. Pada edit profile user dapat langsung mengedit data yang user inginkan dan klik save changes setelah selesai mengedit data yang diinginkan untuk diperbarui.

![Gambar 13. Tombol My Account]

![Gambar 14. Account Profile]

![Gambar 15. Edit Account Profile]

#### 3.2.8. Melakukan Sign Out

Untuk mengakses menu Sign Out klik tombol "Account" yang terdapat pada pojok kanan atas yang kemudian menampilkan opsi My Account dan Sign Out seperti yang tertera pada Gambar 16 dan pilih opsi Sign Out.

![Gambar 16. Sign Out]

#### 3.2.9. Mengakses Dashboard

Untuk mendapatkan menu dashboard seperti pada Gambar 17, pengguna website harus melakukan Sign In menggunakan akun bertipe Pemilik/Pengelola/Tentor.

![Gambar 17. Landing Page menu Dashboard]

Setelah Sign In sebagai Pemilik/Pengelola/Tentor, tekan menu dashboard dan akan menampilkan menu-menu seperti pada Gambar 18 untuk Pemilik/Pengelola, sedangkan Gambar 19 untuk Tentor.

![Gambar 18. Dashboard untuk Pemilik/Pengelola]

![Gambar 19. Dashboard untuk Tentor]

##### 1. User

Tekan menu User pada Side Navigation Bar maka akan menampilkan seperti pada Gambar 20. Pengguna dapat melakukan tambah User dengan tekan Add User, mengedit User dengan tekan Edit, dan menghapus User dengan tekan Delete.

![Gambar 20. Dashboard Menu User]

Ketika pengguna menekan Add User maka pengguna dapat menambah User dengan mengisi formulir seperti pada Gambar 21. Sedangkan, ketika pengguna menekan Edit, maka pengguna dapat mengedit User berdasarkan ID User dengan mengubah formulir seperti pada Gambar 22.

![Gambar 21. Add User]

![Gambar 22. Edit User]

##### 2. Tentor

Tekan menu Tentor pada Side Navigation Bar maka akan menampilkan seperti pada Gambar 23. Pengguna dapat melakukan tambah Tentor dengan tekan Add Tentor, mengedit Tentor dengan tekan Edit, dan menghapus Tentor dengan tekan Delete.

![Gambar 23. Dashboard Menu Tentor]

Ketika pengguna menekan Add Tentor maka pengguna dapat menambah Tentor dengan mengisi formulir seperti pada Gambar 24. Sedangkan, ketika pengguna menekan Edit, maka pengguna dapat mengedit Tentor berdasarkan ID Tentor dengan mengubah formulir seperti pada Gambar 25.

![Gambar 24. Add Tentor]

![Gambar 25. Edit Tentor]

##### 3. Mapel

Tekan menu Tentor pada Side Navigation Bar maka akan menampilkan seperti pada Gambar 26. Pengguna dapat melakukan tambah Mapel dengan tekan Add Mapel, mengedit Tentor dengan tekan Edit, dan menghapus Mapel dengan tekan Delete.

![Gambar 26. Dashboard Menu Mapel]

Ketika pengguna menekan Add Mapel maka pengguna dapat menambah Mapel dengan mengisi formulir seperti pada Gambar 27. Sedangkan, ketika pengguna menekan Edit, maka pengguna dapat mengedit Mapel berdasarkan ID Mapel dengan mengubah formulir seperti pada Gambar 28.

![Gambar 27. Add Mapel]

![Gambar 28. Edit Mapel]

##### 4. Feedback

Tekan menu Feedback pada Side Navigation Bar maka akan menampilkan seperti pada Gambar 29. Pengguna dapat melakukan Delete Feedback dengan tekan Delete Feedback.

![Gambar 29. Dashboard Menu Feedback]

##### 5. Materi

Tekan menu Materi pada Side Navigation Bar maka akan menampilkan seperti pada Gambar 30. Pengguna dapat melakukan tambah Materi dengan tekan Add Materi, mengedit Tentor dengan tekan Edit, dan menghapus Materi dengan tekan Delete.

![Gambar 30. Dashboard Menu Materi]

Ketika pengguna menekan Add Materi maka pengguna dapat menambah Materi dengan mengisi formulir seperti pada Gambar 31. Sedangkan, ketika pengguna menekan Edit, maka pengguna dapat mengedit Materi berdasarkan ID Materi dengan mengubah formulir seperti pada Gambar 32.

![Gambar 31. Add Materi]

![Gambar 32. Edit Materi]

##### 6. Soal

Tekan menu Soal pada Side Navigation Bar maka akan menampilkan seperti pada Gambar 33. Pengguna dapat melakukan tambah soal dengan tekan Add Soal, mengedit Tentor dengan tekan Edit, dan menghapus soal dengan tekan Delete.

![Gambar 33. Dashboard Menu Soal]

Ketika pengguna menekan Add Soal maka pengguna dapat menambah soal dengan mengisi formulir seperti pada Gambar 34. Sedangkan, ketika pengguna menekan Edit, maka pengguna dapat mengedit Soal berdasarkan ID Mapel dengan mengubah formulir seperti pada Gambar 35.

![Gambar 34. Add Soal]

![Gambar 35. Edit Soal]

##### 7. Jadwal

Tekan menu Jadwal pada Side Navigation Bar maka akan menampilkan seperti pada Gambar 36. Pengguna dapat melakukan tambah soal dengan tekan Add Soal, mengedit Tentor dengan tekan Edit, dan menghapus soal dengan tekan Delete.

![Gambar 36. Dashboard Menu Jadwal]

Ketika pengguna menekan Add Jadwal maka pengguna dapat menambah jadwal dengan mengisi formulir seperti pada Gambar 37. Sedangkan, ketika pengguna menekan Edit, maka pengguna dapat mengedit jadwal berdasarkan ID Jadwal dengan mengubah formulir seperti pada Gambar 38.

![Gambar 37. Add Jadwal]

![Gambar 38. Edit Jadwal]

---

**Dokumen ini dan informasi yang dimilikinya adalah milik Jurusan Informatika - Unsoed dan bersifat rahasia.**  
**Dilarang untuk mereproduksi dokumen ini tanpa diketahui oleh Jurusan Informatika Unsoed**