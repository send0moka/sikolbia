# SPESIFIKASI KEBUTUHAN PERANGKAT LUNAK
## SISTEM INFORMASI BELAJAR KUY
### (BIMBINGAN BELAJAR)

---

**Disusun untuk memenuhi tugas Mata Kuliah Analisis dan Desain Sistem (ADS)**  
**Dibimbing oleh:** Teguh Cahyono, S.T., M.Kom

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

| Direktorat TIK / UNSOED | Nomor Dokumen | Halaman |
|--------------------------|---------------|---------|
| SRS– SIBeKu | 1 / 69 | SKPL.I |
| **Revisi** | **Tgl: 24 Desember 2022** | |

---

## LEMBAR PENGESAHAN

### Module Registrasi dan Login
- **Dipersiapkan Oleh:** Usriyatul Khamimah
- **Diperiksa Oleh:** -
- **Disetujui Oleh:** -

### Module Kelas dan Materi
- **Dipersiapkan Oleh:** Pilar Filino Hadi
- **Diperiksa Oleh:** -
- **Disetujui Oleh:** -

### Module Jadwal
- **Dipersiapkan Oleh:** Maulana Hafez Ahyatara T.
- **Diperiksa Oleh:** -
- **Disetujui Oleh:** -

### Module Bank Soal
- **Dipersiapkan Oleh:** Ayu Anjar Paramestuti
- **Diperiksa Oleh:** -
- **Disetujui Oleh:** -

### Module Feedback
- **Dipersiapkan Oleh:** Ahita Bisma Adlula
- **Diperiksa Oleh:** -
- **Disetujui Oleh:** -

---

## DAFTAR PERUBAHAN

| Revisi | Deskripsi |
|--------|-----------|
| A | |
| B | |
| C | |
| D | |
| E | |
| F | |
| G | |

### INDEX

| Index | Tgl | Ditulis Oleh | Diperiksa Oleh | Disetujui Oleh |
|-------|-----|--------------|----------------|----------------|
| A | | | | |
| B | | | | |
| C | | | | |
| D | | | | |
| E | | | | |
| F | | | | |

---

## DAFTAR ISI

1. [LEMBAR PENGESAHAN](#lembar-pengesahan)
2. [DAFTAR PERUBAHAN](#daftar-perubahan)
3. [DAFTAR HALAMAN PERUBAHAN](#daftar-halaman-perubahan)
4. [DAFTAR ISI](#daftar-isi)
5. [DAFTAR GAMBAR](#daftar-gambar)
6. [DAFTAR TABEL](#daftar-tabel)
7. [BAB I PENDAHULUAN](#bab-i-pendahuluan)
   - 1.1. Pendahuluan
   - 1.2. Tujuan Penulisan Dokumen
   - 1.3. Lingkup Masalah
   - 1.4. Target Audience
   - 1.5. Definisi, Istilah, dan Singkatan
   - 1.6. Referensi
   - 1.7. Deskripsi Umum Dokumen
8. [BAB II DESKRIPSI UMUM PERANGKAT LUNAK](#bab-ii-deskripsi-umum-perangkat-lunak)
   - 2.1. Aturan Penomoran
   - 2.2. Deskripsi Umum Sistem
   - 2.3. Fungsi Produk
   - 2.4. Karakteristik dan Klasifikasi Pengguna
   - 2.5. Batasan-Batasan
   - 2.6. Lingkup Operasi
   - 2.7. Deskripsi Rincian Kebutuhan Sistem
   - 2.8. Kebutuhan Antarmuka Eksternal
9. [BAB III DESKRIPSI RINCI KEBUTUHAN](#bab-iii-deskripsi-rinci-kebutuhan)
   - 3.1. Kebutuhan Fungsional
   - 3.2. Data Requirement
   - 3.3. Non-Functional Requirement
   - 3.4. Batasan Perancangan
   - 3.5. Kerunutan (Traceability)
   - 3.6. Ringkasan Kebutuhan
10. [Lampiran 1](#lampiran-1)

---

## DAFTAR GAMBAR

- Gambar 1. Landing Page sebelum melakukan Sign In
- Gambar 2. Halaman Sign Up
- Gambar 3. Halaman Sign In
- Gambar 4. Landing Page setelah melakukan Sign In
- Gambar 5. Halaman Bank Materi
- Gambar 6. Halaman Bank Soal
- Gambar 7. Halaman Jadwal
- Gambar 8. Halaman Feedback
- Gambar 9. Halaman Account Profile
- Gambar 10. Halaman Dashboard untuk Pemilik dan Pengelola
- Gambar 11. Halaman Data User
- Gambar 12. Halaman Data Tentor
- Gambar 13. Halaman Data Mapel (Mata Pelajaran)
- Gambar 14. Halaman Data Materi
- Gambar 15. Halaman Data Soal
- Gambar 16. Halaman Data Jadwal
- Gambar 17. Halaman Data Feedback
- Gambar 18. Dashboard untuk Tentor
- Gambar 19. DFD Level 0
- Gambar 20. DFD Level 1
- Gambar 21. DFD Level 2 Subsistem Registrasi dan Login
- Gambar 22. DFD Level 2 Subsistem Kelas dan Materi
- Gambar 23. DFD Level 2 Subsistem Jadwal
- Gambar 24. DFD Level 2 Subsistem Bank Soal
- Gambar 25. DFD Level 2 Subsistem Feedback
- Gambar 26. Entity Relationship Diagram

---

## DAFTAR TABEL

- Tabel 1. Definisi, Istilah dan Singkatan
- Tabel 2. Aturan Penomoran
- Tabel 3. Fungsi Produk
- Tabel 4. Karakteristik dan Klasifikasi Pengguna
- Tabel 5. Kebutuhan Fungsional
- Tabel 6. Non-Functional Requirement
- Tabel 7. Ringkasan Kebutuhan Functional Requirement Summary
- Tabel 8. Non-Functional Requirement Summary

---

## BAB I PENDAHULUAN

### 1.1. Pendahuluan

Dokumen ini berisi Spesifikasi Kebutuhan Perangkat Lunak (SKPL) atau Software Requirement Specification (SRS) untuk Sistem Informasi Bimbingan Belajar Belajar Kuy. Untuk penamaan dokumen ini selanjutnya, akan digunakan istilah SRS.

### 1.2. Tujuan Penulisan Dokumen

Dokumen SKPL ini berisi spesifikasi kebutuhan perangkat lunak untuk mendefinisikan dan menjabarkan bimbingan belajar berbasis website. Tujuan dari penulisan dokumen ini adalah untuk:
- Dijadikan acuan
- Memberikan penjelasan mengenai spesifikasi perangkat lunak baik berupa gambaran umum maupun penjelasan detail dan menyeluruh bagi pengembang yang akan melakukan pengembangan perangkat lunak pada tahap selanjutnya
- Memenuhi tugas mata kuliah Analisis Desain Sistem (ADS)

### 1.3. Lingkup Masalah

Teknologi informasi telah membuka mata dunia akan sebuah kebutuhan baru, interaksi baru dan sebuah jaringan informasi yang tanpa batas. Perkembangan teknologi yang disebut internet, telah mengubah pola interaksi masyarakatnya, khususnya pada bidang hiburan. Internet telah memberikan kontribusi yang demikian besar bagi masyarakat, perusahaan, industri dan pemerintahan. Hadirnya internet telah menunjang efektivitas dan efisiensi sarana komunikasi, publikasi, serta sarana dan prasarana untuk mendapatkan berbagai informasi yang dibutuhkan. Website merupakan sarana hubungan antar entitas yaitu penyedia informasi dan penerima informasi agar dapat berkomunikasi terkait informasi secara efektif dan efisien.

### 1.4. Target Audience

Target audience dari dokumen ini adalah:
1. Pemilik Bimbingan Belajar
2. Pengelola Bimbingan Belajar
3. Tentor Bimbingan Belajar
4. Murid Bimbingan Belajar

### 1.5. Definisi, Istilah, dan Singkatan

| Istilah dan Singkatan | Definisi |
|------------------------|----------|
| **SRS** | Software Requirements Specification bisa juga diartikan sebagai Spesifikasi Kebutuhan Perangkat Lunak (SKPL) |
| **SKPL** | SKPL atau Spesifikasi Kebutuhan Perangkat lunak adalah spesifikasi dari perangkat lunak yang akan dikembangkan |
| **DFD** | DFD atau Data Flow Diagram adalah diagram yang menggambarkan proses aliran data dari sebuah sistem informasi yang dibangun |
| **RPL** | Perubahan perangkat lunak guna mengembangkan, memelihara, dan membangun kembali dengan menggunakan prinsip rekayasa untuk menghasilkan perangkat lunak yang dapat bekerja lebih efisien dan efektif untuk pengguna |
| **HTML** | HyperText Markup Language adalah bahasa markup standar yang digunakan untuk membuat halaman website dan aplikasi web |
| **CSS** | Cascading Style Sheets merupakan bahasa yang digunakan untuk menentukan tampilan dan format sebuah halaman website |
| **PHP** | Hypertext Preprocessor merupakan bahasa penulisan skrip open-source yang banyak digunakan dalam pemrograman atau pengembangan website (Web Development) |

### 1.6. Referensi

Referensi yang digunakan pada perangkat lunak ini adalah:
- Roger S Pressman, Ph.D. Rekayasa Perangkat Lunak. 2002
- Andri Kristanto. Rekayasa Perangkat Lunak (Konsep Dasar). 2004
- IEEE Std 830-1993, IEEE Recommended Practice for Software Requirement Specifications
- Bayu Hendradjaya. Panduan Penulisan Spesifikasi Kebutuhan Perangkat lunak (SKPL). Jurusan Teknik Informatika ITB
- STAF IF. GL01, Spesifikasi Kebutuhan Perangkat Lunak. Jurusan Teknik Informatika ITB
- Arry Ekananta, ST. Spesifikasi Kebutuhan Perangkat Lunak AKSES. Departemen Ilmu Komputer IPB

### 1.7. Deskripsi Umum Dokumen

Dokumen SKPL ini dibagi menjadi tiga bagian utama:
1. **Bagian Pertama** berisi penjelasan tentang dokumen SKPL yang mencakup tujuan pembuatan dokumen ini, lingkup masalah yang diselesaikan oleh perangkat lunak yang dikembangkan, definisi, referensi dan deskripsi umum, serta pengenalan pengguna
2. **Bagian Kedua** berisi deskripsi umum perangkat lunak
3. **Bagian Ketiga** berisi deskripsi rinci kebutuhan

---

## BAB II DESKRIPSI UMUM PERANGKAT LUNAK

### 2.1. Aturan Penomoran

| Format | Keterangan |
|--------|------------|
| **SRS.BB.SIBeku.XX.YY.ZZ** | |
| SRS | System Requirement Specification |
| BB | Bimbingan Belajar |
| SIBeku | Sistem Informasi Belajar Kuy |
| XX | Nomor subsistem |
| YY | Nomor pengguna |
| ZZ | Nomor kebutuhan sistem |

### 2.2. Deskripsi Umum Sistem

Perangkat Lunak Sistem Informasi Bimbingan Belajar, Belajar Kuy merupakan sistem yang dibuat untuk memudahkan pemilik dan pengelola dalam pengolahan data dan pembuatan laporan, serta Tentor dan Murid dalam mengakses materi, jadwal dan soal. Kegiatan yang dapat ditangani oleh perangkat lunak ini meliputi registrasi, login, kelas dan materi, jadwal, bank soal serta feedback.

### 2.3. Fungsi Produk

Kegunaan dari perangkat lunak ini adalah dapat membantu pengguna dalam melaksanakan kegiatan operasionalnya. Kegiatan tersebut meliputi pendataan kumpulan materi, jadwal dan kumpulan soal.

| No | ID Fungsi | Nama Fungsi |
|----|-----------|-------------|
| 1 | SRS-BB-SIBeKu-KF1 | Registrasi dan Login |
| 2 | SRS-BB-SIBeKu-KF2 | Pengelolaan Kelas dan Materi |
| 3 | SRS-BB-SIBeKu-KF3 | Pengelolaan Jadwal |
| 4 | SRS-BB-SIBeKu-KF4 | Pengelolaan Bank Soal |
| 5 | SRS-BB-SIBeKu-KF5 | Pengelolaan Feedback |

#### Rincian Fungsi:

#### 1. Fungsi Registrasi dan Login (SRS-BB-SIBeKu-KF1)
- **SRS-BB-SIBeKu-KF1-01**: Fungsi Tambah User - Sistem menyediakan formulir untuk menambahkan user baru ke database
- **SRS-BB-SIBeKu-KF1-02**: Fungsi Login User - Sistem menyediakan formulir yang digunakan oleh para user yang telah terdaftar di database untuk login ke dalam aplikasi
- **SRS-BB-SIBeKu-KF1-03**: Fungsi Edit User - Sistem menyediakan form yang digunakan oleh user untuk mengedit data user dari database
- **SRS-BB-SIBeKu-KF1-04**: Fungsi Hapus User - Sistem menyediakan menu untuk menghapus user dari database

#### 2. Fungsi Pengelolaan Kelas dan Materi (SRS-BB-SIBeKu-KF2)
- **SRS-BB-SIBeKu-KF2-01**: Fungsi Tambah Materi - Sistem menyediakan formulir untuk menambah materi ke database
- **SRS-BB-SIBeKu-KF2-02**: Fungsi Edit Materi - Sistem menyediakan menu untuk mengedit materi dari database
- **SRS-BB-SIBeKu-KF2-03**: Fungsi Hapus Materi - Sistem menyediakan menu untuk menghapus materi dari database
- **SRS-BB-SIBeKu-KF2-04**: Fungsi Pencarian - Sistem menyediakan fitur pencarian untuk mencari materi berdasarkan nama materi
- **SRS-BB-SIBeKu-KF2-05**: Fungsi Download - Sistem menyediakan fitur download untuk mengunduh materi yang tersedia

#### 3. Fungsi Pengelolaan Jadwal (SRS-BB-SIBeKu-KF3)
- **SRS-BB-SIBeKu-KF3-01**: Fungsi Tambah Jadwal - Sistem menyediakan formulir untuk menambah jadwal ke database
- **SRS-BB-SIBeKu-KF3-02**: Fungsi Edit Jadwal - Sistem menyediakan menu untuk mengedit jadwal dari database
- **SRS-BB-SIBeKu-KF3-03**: Fungsi Hapus Jadwal - Sistem menyediakan menu untuk menghapus jadwal dari database
- **SRS-BB-SIBeKu-KF3-04**: Fungsi Download - Sistem menyediakan fitur download untuk mengunduh jadwal yang tersedia

#### 4. Fungsi Pengelolaan Bank Soal (SRS-BB-SIBeKu-KF4)
- **SRS-BB-SIBeKu-KF4-01**: Fungsi Tambah Bank Soal - Sistem menyediakan formulir untuk menambah soal ke database
- **SRS-BB-SIBeKu-KF4-02**: Fungsi Edit Bank Soal - Sistem menyediakan menu untuk mengedit soal dari database
- **SRS-BB-SIBeKu-KF4-03**: Fungsi Hapus Soal - Sistem menyediakan menu untuk menghapus soal dari database
- **SRS-BB-SIBeKu-KF4-04**: Fungsi Pencarian - Sistem menyediakan fitur pencarian untuk mencari soal-soal berdasarkan nama soal
- **SRS-BB-SIBeKu-KF4-05**: Fungsi Download - Sistem menyediakan fitur download untuk mengunduh soal yang tersedia

#### 5. Fungsi Pengelolaan Feedback (SRS-BB-SIBeKu-KF5)
- **SRS-BB-SIBeKu-KF5-01**: Fungsi Rating - Sistem menyediakan media untuk memberi rating ke tentor, pengelola, atau sistem
- **SRS-BB-SIBeKu-KF5-02**: Fungsi Tambah Pesan - Sistem menyediakan formulir untuk memberikan feedback ke tentor, pengelola, atau sistem

### 2.4. Karakteristik dan Klasifikasi Pengguna

Pengguna perangkat lunak ini adalah semua orang yang ingin mengikuti bimbingan belajar secara daring melalui website. Adapun rincian penggunanya adalah tentor, murid, pengelola dan pemilik. Masing-masing pengguna yang berinteraksi dengan system dihubungkan dengan hak akses dan level autentifikasi sesuai dengan kebutuhan dan aturan yang terdapat pada bimbingan belajar.

| No | Kategori Pengguna | Keterangan Hak Akses | Nomor Hak Akses |
|----|-------------------|----------------------|-----------------|
| 1 | Pemilik, Pengelola, Tentor, Murid | 1. Murid melakukan registrasi akun<br>2. Pemilik, Pengelola, Tentor dan Murid melakukan login akun<br>3. Pemilik dan Pengelola melakukan pengelolaan data akun | SRS-BB-BK-0001 |
| 2 | Pemilik, Pengelola, Tentor, Murid | 1. Pemilik, Pengelola dan Tentor melakukan pengelolaan kelas dan materi<br>2. Murid mengakses dan men-download materi | SRS-BB-BK-0002 |
| 3 | Pemilik, Pengelola, Tentor, Murid | 1. Pemilik dan Pengelola melakukan pengelolaan jadwal bimbingan belajar<br>2. Tentor dan Murid mengakses jadwal bimbingan belajar | SRS-BB-BK-0003 |
| 4 | Pemilik, Pengelola, Tentor, Murid | 1. Pemilik, Pengelola dan Tentor melakukan pengelolaan bank soal<br>2. Murid mengakses dan men-download soal | SRS-BB-BK-0004 |
| 5 | Pemilik, Pengelola, Tentor, Murid | 1. Pemilik, Pengelola, Tentor, dan Murid memberikan feedback ke sistem<br>2. Murid memberikan feedback ke tentor<br>3. Pemilik dan Pengelola mengelola feedback | SRS-BB-BK-0005 |

### 2.5. Batasan-Batasan

Batasan-batasan yang digunakan pada pengembangan perangkat lunak ini adalah:
1. Pengembangan perangkat lunak tidak akan mengubah apapun file-file ataupun database yang ada saat ini tanpa adanya izin dari pemilik dan pengelola
2. Pengembangan perangkat lunak secara otomatis akan merekap pengelolaan data-data yang ada di bimbingan belajar meliputi data kelas dan materi, data jadwal, data soal dan laporan feedback
3. Waktu pengembangan perangkat lunak yang singkat membuat adanya kemungkinan tidak semua fungsi yang ada dapat dilaksanakan

### 2.6. Lingkup Operasi

Perangkat lunak yang dibutuhkan oleh SIBeKu adalah, sebagai berikut:
1. **Sistem Operasi**: Microsoft® Windows
2. **Bahasa Pemrograman**: HTML, CSS, Javascript, PHP
3. **Web Browser**: Google Chrome
4. **Server**: Apache
5. **DBMS**: MySQL

### 2.7. Deskripsi Rincian Kebutuhan Sistem

#### 2.7.1. Modul Registrasi dan Login [SRS.BB.SIBeKu.01]

Modul Registrasi dan Login adalah modul yang berisikan sub sistem registrasi dan login. User yang belum terdaftar dapat melakukan registrasi terlebih dahulu. User yang sudah terdaftar pada SIBeKu dapat login untuk melihat subsistem lainnya.

##### 1. Pemilik [SRS.BB.SIBeKu.01.01]
Pemilik pada modul Registrasi dan Login memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data registrasi atau login. Pemilik tidak perlu melakukan registrasi, akan tetapi pemilik dapat login sebagai pemilik agar dapat menggunakan seluruh subsistem.

Kebutuhan Pemilik:
- **[SRS.BB.SIBeKu.01.01.01]**: Sistem menyediakan kerahasiaan data dengan enkripsi password
- **[SRS.BB.SIBeKu.01.01.02]**: Sistem menyediakan inputan saat registrasi adalah nama, email, nomor telepon, username, dan password
  ```sql
  INSERT INTO user(username, password, nama_user, email_user, no_telp_user, tipe_user) 
  VALUES ("", "", "", "", "", "");
  ```

##### 2. Pengelola [SRS.BB.SIBeKu.01.02]
Pengelola pada modul Registrasi dan Login memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data registrasi atau login. Pengelola tidak perlu melakukan registrasi, akan tetapi pengelola dapat login sebagai admin agar dapat menggunakan seluruh subsistem.

Kebutuhan Pengelola:
- **[SRS.BB.SIBeKu.01.02.01]**: Sistem menjamin data terjaga kerahasiaannya
- **[SRS.BB.SIBeKu.01.02.02]**: Sistem menyediakan data yang diinputkan yaitu nama, email, nomor telepon, username, dan password
  ```sql
  INSERT INTO user(username, password, nama_user, email_user, no_telp_user, tipe_user) 
  VALUES ("", "", "", "", "", "");
  ```
- **[SRS.BB.SIBeKu.01.02.03]**: Sistem menyediakan fitur edit data akun untuk user
  ```sql
  UPDATE user SET username = "", password = "", nama_user = "", email_user = "", no_telp_user = "" 
  WHERE username = "";
  ```

##### 3. Tentor [SRS.BB.SIBeKu.01.03]
Tentor pada modul Registrasi dan Login memiliki akses untuk modul ini, seperti mengedit data. Tentor tidak perlu melakukan registrasi, akan tetapi dapat login sebagai tentor agar dapat menggunakan seluruh subsistem.

Kebutuhan Tentor:
- **[SRS.BB.SIBeKu.01.03.01]**: Sistem menyediakan fitur pemberitahuan kesalahan dan sukses saat input email/password
- **[SRS.BB.SIBeKu.01.03.02]**: Sistem menyediakan inputan data yang diinputkan saat login adalah nama, email, nomor telepon, username, dan password
- **[SRS.BB.SIBeKu.01.03.03]**: Sistem menjamin kerahasiaan password dengan enkripsi

##### 4. Murid [SRS.BB.SIBeKu.01.04]
Murid pada modul Registrasi dan Login memiliki akses untuk modul ini, seperti mengedit data. Murid perlu melakukan registrasi apabila belum mempunyai akun dan dapat login agar dapat menggunakan seluruh subsistem.

Kebutuhan Murid:
- **[SRS.BB.SIBeKu.01.04.01]**: Sistem menyediakan fitur pemberitahuan kesalahan dan sukses saat input email/password
- **[SRS.BB.SIBeKu.01.04.02]**: Sistem menyediakan inputan data yang diinput saat login adalah nama, email, nomor telepon, username, dan password
- **[SRS.BB.SIBeKu.01.04.03]**: Sistem menjamin kerahasiaan data

#### 2.7.2. Modul Kelas dan Materi [SRS.BB.SIBeKu.02]

Modul Kelas dan Materi adalah modul yang berisikan sub sistem kelas dan materi untuk digunakan user tentor dan murid.

##### 1. Pemilik [SRS.BB.SIBeKu.02.01]
Pemilik pada modul kelas dan materi memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul kelas dan materi, Pemilik harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.02.01.01]**: Sistem menampilkan halaman kelas dan materi yang didalamnya berisikan nama materi, kelas, nama mapel, deskripsi dan file materi
  ```sql
  SELECT nama_materi, kelas, mapel, deskripsi, file_materi FROM materi;
  ```
- **[SRS.BB.SIBeKu.02.01.02]**: Sistem menyediakan fitur pencarian
  ```sql
  SELECT nama_materi, kelas, mapel, deskripsi, file_materi FROM materi 
  WHERE nama_materi LIKE '%...%';
  ```
- **[SRS.BB.SIBeKu.02.01.03]**: Sistem menyediakan fitur download materi
  ```html
  <a href="" download="">
  ```

##### 2. Pengelola [SRS.BB.SIBeKu.02.02]
Pengelola pada modul kelas dan materi memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul kelas dan materi, Pengelola harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.02.02.01]**: Sistem menampilkan halaman kelas dan materi yang terdapat pemilihan nama materi, kelas, nama mapel, deskripsi dan file materi
- **[SRS.BB.SIBeKu.02.02.02]**: Sistem memberikan hak akses untuk pemilik, pengelola, dan tentor untuk mengelola halaman kelas dan materi seperti:
  - Edit: `UPDATE soal SET mapel = "", kelas = "", nama_materi = "", file_materi = "" WHERE id_materi = "";`
  - Tambah: `INSERT INTO materi(kelas, mapel, nama_materi, deskripsi_materi, file_materi) VALUES ("", "", "", "", "");`
  - Hapus: `DELETE FROM materi WHERE id_materi = "";`
- **[SRS.BB.SIBeKu.02.02.03]**: Sistem menyediakan fitur download materi
- **[SRS.BB.SIBeKu.02.02.04]**: Sistem menyediakan fitur pencarian materi

##### 3. Tentor [SRS.BB.SIBeKu.02.03]
Tentor pada modul kelas dan materi memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.02.03.01]**: Sistem memberikan hak akses untuk melihat, mengupload dan mengedit materi
- **[SRS.BB.SIBeKu.02.03.02]**: Sistem menampilkan bentuk materi berupa pdf
  ```html
  <input type="file" accept=".pdf">
  ```
- **[SRS.BB.SIBeKu.02.03.03]**: Sistem menyediakan fitur pencarian
  ```sql
  SELECT nama_materi, kelas, mapel, deskripsi, file_materi FROM materi 
  WHERE nama_materi LIKE '%...%';
  ```

##### 4. Murid [SRS.BB.SIBeKu.02.04]
Murid pada modul kelas dan materi dapat mengakses modul kelas dan materi dengan melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.02.04.01]**: Sistem menyediakan fitur pencarian
  ```sql
  SELECT nama_materi, kelas, mapel, deskripsi, file_materi FROM materi 
  WHERE nama_materi LIKE '%...%';
  ```
- **[SRS.BB.SIBeKu.02.04.02]**: Sistem menyediakan fitur download materi
  ```html
  <a href="" download="">
  ```

#### 2.7.3. Modul Jadwal [SRS.BB.SIBeKu.03]

Modul Jadwal adalah modul yang berisikan subsistem jadwal. Dimana pada subsistem ini menampilkan jadwal yang berisi informasi terkait pelaksanaan bimbingan belajar.

##### 1. Pemilik [SRS.BB.SIBeKu.03.01]
Pemilik pada modul jadwal memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data jadwal. Untuk mengakses modul jadwal, Pemilik harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.03.01.01]**: Sistem menampilkan informasi berupa mata pelajaran, kelas, tentor, dan waktu pelaksanaan (hari dan waktu) berdasarkan tahun ajaran berbentuk tabel
  ```sql
  SELECT * FROM jadwal WHERE tahun_ajaran = "";
  ```
  ```html
  <table>, <th>, <tr>, <td>
  ```
- **[SRS.BB.SIBeKu.03.01.02]**: Sistem memberikan akses kepada semua user untuk mengakses halaman jadwal
- **[SRS.BB.SIBeKu.03.01.03]**: Sistem menyediakan fitur download jadwal
  ```sql
  SELECT file_jadwal FROM jadwal;
  ```
  ```html
  <a href="" download="">
  ```

##### 2. Pengelola [SRS.BB.SIBeKu.03.02]
Pengelola pada modul jadwal memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data jadwal. Untuk mengakses modul jadwal, Pengelola harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.03.02.01]**: Sistem menampilkan jadwal berbentuk tabel
  ```html
  <table>, <th>, <tr>, <td>
  ```
- **[SRS.BB.SIBeKu.03.02.02]**: Sistem menampilkan informasi berupa mata pelajaran, kelas, tentor, dan waktu pelaksanaan (hari dan waktu)
  ```sql
  SELECT * FROM jadwal WHERE tahun_ajaran = "...";
  ```
- **[SRS.BB.SIBeKu.03.02.03]**: Sistem menyediakan fitur download jadwal
  ```sql
  SELECT file_jadwal FROM jadwal;
  ```

##### 3. Tentor [SRS.BB.SIBeKu.03.03]
Tentor pada modul jadwal memiliki akses untuk melihat jadwal pelaksanaan bimbingan belajar. Untuk mengakses modul jadwal, Tentor harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.03.03.01]**: Sistem menampilkan jadwal berbentuk tabel
- **[SRS.BB.SIBeKu.03.03.02]**: Sistem menampilkan informasi berupa mata pelajaran, kelas, tentor, dan waktu pelaksanaan (hari dan waktu)
- **[SRS.BB.SIBeKu.03.03.03]**: Sistem menyediakan fitur download jadwal

##### 4. Murid [SRS.BB.SIBeKu.03.04]
Murid pada modul jadwal memiliki akses untuk melihat jadwal pelaksanaan bimbingan belajar. Untuk mengakses modul jadwal, Murid harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.03.04.01]**: Sistem menampilkan jadwal berbentuk tabel
- **[SRS.BB.SIBeKu.03.04.02]**: Sistem menyediakan fitur download jadwal

#### 2.7.4. Modul Bank Soal [SRS.BB.SIBeKu.04]

Modul bank soal adalah modul yang berisikan subsistem bank soal. Pada subsistem bank soal, pemilik dapat mengelola dan menampilkan terkait soal-soal yang sesuai dengan materi.

##### 1. Pemilik [SRS.BB.SIBeKu.04.01]
Pemilik pada modul Bank Soal memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul Bank Soal, Pemilik harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.04.01.01]**: Sistem menampilkan Bank Soal yang didalamnya terdapat kelas, nama mata pelajaran, judul soal, dan file soal
  ```sql
  SELECT nama_soal, kelas, mapel, file_soal FROM soal;
  ```
- **[SRS.BB.SIBeKu.04.01.02]**: Sistem menyediakan fitur download
  ```html
  <a href="" download="">
  ```
- **[SRS.BB.SIBeKu.04.01.03]**: Sistem memberikan akses untuk semua user dalam mengakses halaman Bank Soal

##### 2. Pengelola [SRS.BB.SIBeKu.04.02]
Pengelola pada modul Bank Soal memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul Bank Soal, Pengelola harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.04.02.01]**: Sistem menampilkan nama mata pelajaran yang didalamnya terdapat soal
  ```sql
  SELECT mapel, kelas, nama_soal, file_soal FROM soal;
  ```
- **[SRS.BB.SIBeKu.04.02.02]**: Sistem menyediakan fitur pencarian dan download soal
  ```html
  <a href="" download="">
  ```

##### 3. Tentor [SRS.BB.SIBeKu.04.03]
Tentor pada modul Bank Soal memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul Bank Soal, Tentor harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.04.03.01]**: Sistem menyediakan fitur edit soal
  ```sql
  UPDATE soal SET mapel = "", kelas = "", nama_soal = "", file_soal = "" 
  WHERE id_soal = "";
  ```
- **[SRS.BB.SIBeKu.04.03.02]**: Sistem menerima upload soal dalam bentuk pdf
  ```html
  <input type="file" accept=".pdf">
  ```
- **[SRS.BB.SIBeKu.04.03.03]**: Sistem menyediakan fitur pencarian soal
  ```sql
  SELECT mapel, kelas, nama_soal, file_soal FROM soal 
  WHERE nama_soal LIKE '%...%';
  ```

##### 4. Murid [SRS.BB.SIBeKu.04.04]
Murid pada modul Bank Soal dapat mengakses modul Bank Soal dengan melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.04.04.01]**: Sistem memberikan tampilan halaman yang terdapat nama mata pelajaran, dan judul materi
  ```sql
  SELECT mapel, kelas, nama_soal, file_soal FROM soal;
  ```
- **[SRS.BB.SIBeKu.04.04.02]**: Sistem menyediakan fitur download soal
  ```html
  <a href="" download="">
  ```
- **[SRS.BB.SIBeKu.04.04.03]**: Sistem menyediakan fitur pencarian soal
  ```sql
  SELECT mapel, kelas, nama_soal, file_soal FROM soal 
  WHERE nama_soal LIKE '%...%';
  ```

#### 2.7.5. Modul Feedback [SRS.BB.SIBeKu.05]

Modul feedback adalah modul yang berisikan sub sistem feedback. Pada subsistem ini user tentor dapat menambahkan feedback terhadap website sedangkan untuk user murid dapat menambahkan feedback kepada tentor maupun website.

##### 1. Pemilik [SRS.BB.SIBeKu.05.01]
Pemilik pada modul feedback memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data feedback. Untuk mengakses modul, pemilik harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.05.01.01]**: Sistem menyediakan fitur rating
  ```sql
  INSERT INTO feedback (username, user_review, date, type_review, rating) 
  VALUES ("", "", "", "", "");
  ```
- **[SRS.BB.SIBeKu.05.01.02]**: Sistem menyediakan fitur pesan
  ```sql
  INSERT INTO feedback (username, user_review, date, type_review, rating) 
  VALUES ("", "", "", "", "");
  ```
- **[SRS.BB.SIBeKu.05.01.03]**: Sistem menyediakan feedback untuk tentor dan murid
  ```sql
  SELECT username, tipe_pengguna 
  WHERE tipe_pengguna = "tentor" OR tipe_pengguna = "murid";
  ```

##### 2. Pengelola [SRS.BB.SIBeKu.05.02]
Pengelola pada modul feedback memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data feedback. Untuk mengakses modul, pengelola harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.05.02.01]**: Sistem menyediakan fitur rating
  ```sql
  INSERT INTO feedback (username, user_review, date, type_review, rating) 
  VALUES ("", "", "", "", "");
  ```
- **[SRS.BB.SIBeKu.05.02.02]**: Sistem menyediakan feedback untuk tentor dan murid
  ```sql
  SELECT username, tipe_pengguna 
  WHERE tipe_pengguna = "tentor" OR tipe_pengguna = "murid";
  ```

##### 3. Tentor [SRS.BB.SIBeKu.05.03]
Tentor pada modul feedback memiliki akses untuk melihat modul. Untuk mengakses modul, tentor harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.05.03.01]**: Sistem menyediakan fitur rating
- **[SRS.BB.SIBeKu.05.03.02]**: Sistem menyediakan fitur pesan
- **[SRS.BB.SIBeKu.05.03.03]**: Sistem menyediakan feedback untuk tentor dan murid

##### 4. Murid [SRS.BB.SIBeKu.05.04]
Murid pada modul feedback memiliki akses untuk melihat modul. Untuk mengakses modul, murid harus melakukan login terlebih dahulu.

Kebutuhan sistem:
- **[SRS.BB.SIBeKu.05.04.01]**: Sistem menyediakan fitur rating
- **[SRS.BB.SIBeKu.05.04.02]**: Sistem menyediakan fitur pesan
- **[SRS.BB.SIBeKu.05.04.03]**: Sistem menyediakan feedback untuk tentor dan murid

### 2.8. Kebutuhan Antarmuka Eksternal

Kebutuhan Antarmuka eksternal perangkat lunak Sistem Informasi Bimbingan Belajar mencakup kebutuhan antarmuka pengguna, antarmuka perangkat keras dan antarmuka perangkat lunak.

#### 2.8.1. Antarmuka Pengguna

Sistem Informasi Bimbingan Belajar merupakan perangkat lunak berbasis web yang dilengkapi berbagai fitur dengan fungsi tertentu. Interaksi antara pengguna dan perangkat lunak dilakukan dengan menggunakan keyboard dan mouse. Ada beberapa fungsi yang hanya bisa dilakukan dengan mouse dan ada juga yang bisa dilakukan baik dengan keyboard dan mouse (misalnya pengaksesan bank materi dan bank soal).

##### 1. Interface Semua Pengguna (Pemilik, Pengelola, Tentor, Murid)

**Gambar 1. Landing Page sebelum melakukan Sign In**

Pada saat awal mengakses website maka aplikasi akan menampilkan halaman dimana pengguna harus melakukan Sign In atau Sign Up jika belum memiliki akun untuk mengakses isi dari aplikasi.

**Gambar 2. Halaman Sign Up**

Pada halaman register terdapat data yang harus diisi diantaranya nama, email, nomor telepon, username dan password. Kemudian, ketika semua data telah terisi maka tekan tombol Sign Up agar data tersebut masuk ke dalam database dan dapat digunakan untuk melakukan Sign In.

**Gambar 3. Halaman Sign In**

Pada halaman login terdapat kolom username dan password yang harus diisi dan merupakan data yang sebelumnya diisi pada menu registrasi. Kemudian jika sudah dimasukkan dapat menekan tombol Sign In sehingga akan masuk ke halaman masing-masing yang sesuai dengan tipe pengguna.

**Gambar 4. Landing Page setelah melakukan Sign In**

Setelah berhasil melakukan Sign In maka aplikasi akan menampilkan menu-menu yang ada serta pengguna dapat mengaksesnya.

**Gambar 5. Halaman Bank Materi**

Pada halaman materi, aplikasi akan menampilkan materi-materi yang berasal dari database berisikan informasi kelas, mapel, nama materi, deskripsi materi, serta tombol untuk melihat materi secara online dan tombol download materi. Pada halaman ini dilengkapi dengan pemilihan mapel dan textbox pencarian agar memudahkan pengguna dalam mencari materi yang dibutuhkan.

**Gambar 6. Halaman Bank Soal**

Pada halaman bank soal, aplikasi akan menampilkan soal-soal yang berasal dari database berisikan informasi kelas, mapel, nama soal, serta tombol untuk melihat secara online dan tombol untuk download soal. Pada halaman ini dilengkapi dengan pemilihan mapel dan textbox pencarian agar memudahkan pengguna dalam mencari soal-soal yang dibutuhkan.

**Gambar 7. Halaman Jadwal**

Pada halaman jadwal, aplikasi akan menampilkan jadwal pelaksanaan bimbingan belajar yang berisi informasi hari, waktu, dan kelas. Di halaman ini juga dilengkapi dengan tombol download jadwal untuk mencetak jadwal.

**Gambar 8. Halaman Feedback**

Pada halaman feedback, aplikasi akan menampilkan feedback untuk tentor maupun website yang berasal dari pengguna dan berisikan informasi tipe feedback, rating, tanggal submit feedback, konten feedback, dan username. Pengguna aplikasi ini dapat memberikan feedback untuk tentor maupun website dengan cara menekan tombol Review & Rating lalu memilih tipe feedback dan meng-input-kan rating dan konten feedback.

**Gambar 9. Halaman Account Profile**

Halaman Account Profile merupakan halaman yang menampilkan data dari pengguna yang berasal dari database pada saat pengguna tersebut melakukan registrasi. Selain itu, pengguna juga dapat melakukan edit akun jika diperlukan.

##### 2. Interface Pemilik dan Pengelola

**Gambar 10. Halaman Dashboard untuk Pemilik dan Pengelola**

Ketika pengguna melakukan Sign In menggunakan akun Pemilik/Pengelola maka akan ada menu tambahan yaitu dashboard yang akan menampilkan data-data dari aplikasi ini. Pemilik/Pengelola dapat mengelola data-data tersebut meliputi tambah, edit, dan hapus.

**Gambar 11. Halaman Data User**

**Gambar 12. Halaman Data Tentor**

Pada menu user dan tentor pada dashboard menampilkan tabel berupa data user pada aplikasi ini yang meliputi username, nama, password, email, nomor telepon, dan tipe pengguna. Yang membedakan adalah pada menu tentor hanya menampilkan user yang bertipe tentor. Pada halaman ini juga terdapat aksi untuk menambah, mengedit, menghapus, dan mencetak tabel.

**Gambar 13. Halaman Data Mapel (Mata Pelajaran)**

Menu mapel pada dashboard akan menampilkan tabel mapel yang tersedia pada SIBeKu. Pada halaman ini juga tersedia aksi untuk menambah, mengedit, menghapus dan mencetak tabel.

**Gambar 14. Halaman Data Materi**

**Gambar 15. Halaman Data Soal**

Pada menu materi dan soal pada dashboard menampilkan tabel berupa data materi dan soal yang akan ditampilkan pada halaman utama bank materi dan bank soal. Data yang disimpan untuk materi adalah mapel, kelas, nama materi, deskripsi materi dan file materi, sedangkan data yang disimpan untuk soal adalah mapel, kelas, nama soal dan file materi. Pada halaman ini juga terdapat aksi untuk menambah, mengedit, menghapus, dan mencetak tabel baik data materi maupun soal.

**Gambar 16. Halaman Data Jadwal**

Pada menu jadwal dashboard menampilkan tabel yang berisi tahun ajaran dan file jadwal pelaksanaan bimbingan belajar. Pada menu ini terdapat aksi untuk menambah, mengedit, menghapus, dan mencetak jadwal.

**Gambar 17. Halaman Data Feedback**

Pada menu feedback dashboard ini akan menampilkan tabel berupa data feedback yang berasal dari pengguna website. Data yang ditampilkan meliputi username, tanggal, tipe review (website/tentor), konten review dan rating. Pada halaman ini juga terdapat aksi untuk menambah dan menghapus data feedback.

##### 3. Interface Tentor

**Gambar 18. Dashboard untuk Tentor**

Pengguna bertipe tentor juga dapat mengakses dashboard melainkan Tentor hanya dapat mengakses menu materi dan soal. Pada menu materi dan soal, Tentor mendapatkan akses untuk menambah, mengedit, mengubah dan mencetak baik materi maupun soal.

#### 2.8.2. Antarmuka Perangkat Keras

Sistem ini berjalan di perangkat keras komputer yang terhubung jaringan internet dan berkomunikasi dengan protokol https. Dimana file website ditempatkan pada web hosting yang dioperasikan oleh Pengelola.

Kebutuhan minimum perangkat keras yang dapat digunakan pada website Belajar Kuy ini antara lain:
- Komputer, notebook atau smartphone yang terhubung melalui koneksi internet
- Monitor untuk menampilkan sistem secara visual
- Keyboard dan mouse untuk interaksi antara pengguna dan sistem
- Printer untuk mencetak data dan laporan

#### 2.8.3. Antarmuka Perangkat Lunak

Sistem Informasi Bimbingan Belajar Belajar Kuy adalah sebuah website yang akan dibangun menggunakan Bahasa PHP, JavaScript, HTML, dan CSS yang akan berjalan menggunakan sistem operasi Windows dan Linux serta dapat diakses menggunakan website pada perangkat komputer maupun mobile.

#### 2.8.4. Antarmuka Komunikasi

Komunikasi yang digunakan adalah metode client-server pada jaringan internet atau intranet dan menggunakan protocol TCP/IP dengan menggunakan perintah input, update dan delete dari user.

---

## BAB III DESKRIPSI RINCI KEBUTUHAN

### 3.1. Kebutuhan Fungsional

#### 3.1.1. Kebutuhan Fungsional

| No | ID Fungsi | Nama Fungsi |
|----|-----------|-------------|
| 1 | SRS-BB-SIBeKu-KF1 | **Registrasi:** Tentor dan Murid melakukan registrasi akun<br>**Login:** User/pengguna melakukan login akun |
| 2 | SRS-BB-SIBeKu-KF2 | **Kelas & Materi:** Pemilik, Pengelola dan Tentor dapat melakukan pengelolaan data materi. Pengelolaan yang dilakukan seperti menambahkan, memperbarui dan menghapus. Murid dapat melihat dan men-download materi |
| 3 | SRS-BB-SIBeKu-KF3 | **Jadwal:** Pemilik dan Pengelola dapat melakukan pengelolaan data jadwal. Pengelolaan yang dilakukan seperti menambahkan, memperbarui dan menghapus. Tentor dan Murid dapat melihat dan men-download jadwal |
| 4 | SRS-BB-SIBeKu-KF4 | **Bank Soal:** Pemilik, Pengelola dan Tentor dapat melakukan pengelolaan data bank soal. Pengelolaan yang dilakukan seperti menambahkan, memperbarui dan menghapus. Murid dapat melihat dan men-download soal |
| 5 | SRS-BB-SIBeKu-KF5 | **Feedback:** Pemilik dan Pengelola dapat melakukan pengelolaan data feedback. Pengelolaan yang dilakukan seperti menambahkan dan memperbarui. Tentor dan Murid dapat memberikan feedback untuk website dan feedback untuk tentor |

#### 3.1.2. Diagram Konteks

Data Flow Diagram menggambarkan sistem sebagai jaringan kerja antar fungsi yang berhubungan satu dengan yang lain dengan aliran dan penyimpangan data. Berikut ini merupakan penjelasan lebih rinci dari Diagram Konteks yang memuat proses-proses inti yang ada di dalam sistem.

##### 1. DFD Level 0

**Gambar 19. DFD Level 0**

##### 2. DFD Level 1

Berikut ini merupakan penjelasan lebih rinci dari Diagram Konteks yang dituangkan sebagai Data Flow Diagram Level 1 Bimbingan Belajar Belajar Kuy yang memuat proses-proses inti yang ada di dalam sistem.

**Gambar 20. DFD Level 1**

##### 3. DFD Level 2 Sub Sistem Registrasi dan Login

**Gambar 21. DFD Level 2 Subsistem Registrasi dan Login**

Keterangan DFD Level 2 Subsistem Registrasi dan Login:
- **Proses Input Form Registrasi** adalah proses pemasukan data tentor dan murid yang akan mendaftarkan akun ke system
- **Proses Konfirmasi** adalah proses hasil pengolahan dari input formulir pendaftaran dan konfirmasi kepada tentor dan murid bahwa datanya telah disimpan
- **Proses Input Form Login** adalah proses dimana user memasukan data akun dan password agar dapat masuk ke dalam system
- **Proses Konfirmasi User** adalah proses hasil pengolahan dari pemasukan form login berupa akun dan password oleh user dan konfirmasi keberhasilan login akun

##### 4. DFD Level 2 Subsistem Kelas dan Materi

**Gambar 22. DFD Level 2 Subsistem Kelas dan Materi**

Keterangan DFD Level 2 Subsistem Kelas dan Materi:
- **Proses tampilan materi** adalah proses yang menampilkan halaman kelas dan materi kepada user yang mengakses
- **Proses edit materi** adalah proses mengubah data materi yang dilakukan oleh pengelola dan tentor, yang akan ditampilkan pada sistem
- **Proses hapus materi** adalah proses menghapus materi dari database yang bisa dilakukan oleh pengelola dan tentor
- **Proses tambah materi** adalah proses menambah materi ke database yang dilakukan oleh pengelola dan tentor, yang akan ditampilkan pada sistem
- **Proses fitur pencarian materi** adalah proses yang dapat dilakukan oleh user untuk mencari materi yang terdaftar di database

##### 5. DFD Level 2 Subsistem Jadwal

**Gambar 23. DFD Level 2 Subsistem Jadwal**

Keterangan DFD Level 2 Subsistem Jadwal:
- **Proses tampilan jadwal** adalah proses yang menampilkan jadwal dalam bentuk tabel
- **Proses penginputan dan pengolahan data jadwal** dilakukan oleh pemilik dan pengelola
- **Proses fitur cetak jadwal** adalah fitur dimana pengguna dapat mendownload/mencetak jadwal dari database

##### 6. DFD Level 2 Subsistem Bank Soal

**Gambar 24. DFD Level 2 Subsistem Bank Soal**

Keterangan DFD Level 2 Subsistem Bank Soal:
- **Proses fitur edit** adalah proses memperbarui data bank soal oleh pemilik atau pengelola yang akan diolah dan ditampilkan pada sistem
- **Proses fitur download** adalah proses mengunduh soal oleh tentor dan murid yang akan diolah dan ditampilkan pada sistem
- **Proses tampilan bank soal** adalah proses menampilkan data menu bank soal yang berisi mata pelajaran dan judul materi oleh user, kemudian diolah dan ditampilkan pada sistem
- **Proses fitur pencarian** adalah proses menemukan data yang dicari oleh user, kemudian diolah dan ditampilkan pada sistem

##### 7. DFD Level 2 Subsistem Feedback

**Gambar 25. DFD Level 2 Subsistem Feedback**

Keterangan DFD Level 2 Subsistem Feedback:
- **Proses Tampilan Feedback** adalah proses menampilkan menu feedback kepada user
- **Proses Rating** adalah proses tentor dan murid untuk menambahkan data rating ke database
- **Proses Pesan** adalah proses tentor dan murid untuk menambahkan data pesan ke database

### 3.2. Data Requirement

#### 3.2.1. Data Aplikasi Statis

Data aplikasi yang bersifat statis adalah, sebagai berikut:
1. Pemilik adalah data yang mewakili informasi identitas pemilik
2. Pengelola adalah data yang mewakili informasi identitas pengelola/admin

#### 3.2.2. Data Aplikasi Dinamis

Data aplikasi yang bersifat dinamis adalah, sebagai berikut:
1. Data User meliputi nama, email, nomor telepon, username, password
2. Data Materi meliputi id_soal, kelas, id_mapel, nama_materi, deskripsi_materi, file_materi
3. Data Jadwal meliputi id_jadwal, tahun_ajaran, file_jadwal
4. Data Soal meliputi id_soal, kelas, id_mapel, nama_soal, file_soal
5. Data Feedback meliputi username, user_review, date, type_review, rating

#### 3.2.3. ERD (Entity Relationship Diagram)

Diagram Hubungan Entitas atau entity relationship diagram merupakan model data berupa notasi grafis dalam pemodelan data konseptual yang menggambarkan hubungan antara penyimpan. Model data sendiri merupakan sekumpulan cara, peralatan untuk mendeskripsikan data-data yang hubungannya satu sama lain, semantiknya, serta batasan konsistensi. Model data terdiri dari model hubungan entitas dan model relasional.

**Gambar 26. Entity Relationship Diagram**

### 3.3. Non-Functional Requirement

| SRS-BB-KnF-ID | Parameter | Requirement |
|---------------|-----------|-------------|
| [SRS-BB-SIBEKu-KnF1] | Availability | Tidak pernah gagal untuk menampilkan data materi, soal, jadwal, dan feedback |
| [SRS-BB-SIBEKu-KnF2] | Ergonomy | Mudah digunakan |
| [SRS-BB-SIBEKu-KnF3] | Memory | Penyimpanan database MySQL |
| [SRS-BB-SIBEKu-KnF4] | Reliability | Tidak pernah gagal |
| [SRS-BB-SIBEKu-KnF5] | Portability | Mudah diadopsi pada lingkungan sistem operasi Microsoft® Windows atau Linux |
| [SRS-BB-SIBEKu-KnF6] | Security | Hanya dapat digunakan dengan akun yang telah terdaftar |
| [SRS-BB-SIBEKu-KnF7] | Respon time | Internet Connection secara realtime untuk melihat informasi data terupdate |
| [SRS-BB-SIBEKu-KnF8] | Communication | Aplikasi antarmuka menggunakan bahasa Indonesia |

### 3.4. Batasan Perancangan

Batasan Perancangan pada Sistem Informasi BelajarKuy adalah, sebagai berikut:
1. Sistem Informasi BelajarKuy hanya dapat diakses dengan internet pada web browser yang telah support HTML5, CSS3 dan Javascript ES2022
2. Pengelolaan data User, Mapel, Materi, Soal, Jadwal, dan Feedback hanya dapat dilakukan oleh Pemilik dan Pengelola. Sedangkan pengelolaan data Materi dan Soal dapat dilakukan oleh Tentor
3. Murid dapat mengakses, melihat, dan mengunduh materi, soal, maupun jadwal
4. Pengisian feedback dapat dilakukan oleh semua User/Pengguna

### 3.5. Kerunutan (Traceability)

Tabel kerunutan atau traceability terlampir. (Lihat Lampiran 1)

### 3.6. Ringkasan Kebutuhan

#### 3.6.1. Functional Requirement Summary

| SRS-BB-KF-ID | Nama Fungsi |
|--------------|-------------|
| [SRS-BB-SIBeKu-KF1-01] | Tambah User |
| [SRS-BB-SIBeKu-KF1-02] | Login User |
| [SRS-BB-SIBeKu-KF1-03] | Edit User |
| [SRS-BB-SIBeKu-KF1-04] | Hapus User |
| [SRS-BB-SIBeKu-KF2-01] | Tambah Materi |
| [SRS-BB-SIBeKu-KF2-02] | Edit Materi |
| [SRS-BB-SIBeKu-KF2-03] | Hapus Materi |
| [SRS-BB-SIBeKu-KF2-04] | Pencarian |
| [SRS-BB-SIBeKu-KF2-05] | Download |
| [SRS-BB-SIBeKu-KF3-01] | Tambah Jadwal |
| [SRS-BB-SIBeKu-KF3-02] | Edit Jadwal |
| [SRS-BB-SIBeKu-KF3-03] | Hapus Jadwal |
| [SRS-BB-SIBeKu-KF3-04] | Download |
| [SRS-BB-SIBeKu-KF4-01] | Tambah Bank Soal |
| [SRS-BB-SIBeKu-KF4-02] | Edit Bank Soal |
| [SRS-BB-SIBeKu-KF4-03] | Hapus Soal |
| [SRS-BB-SIBeKu-KF4-04] | Pencarian |
| [SRS-BB-SIBeKu-KF4-05] | Download |
| [SRS-BB-SIBeKu-KF5-01] | Rating |
| [SRS-BB-SIBeKu-KF5-02] | Tambah Pesan |

#### 3.6.2. Non-Functional Requirement Summary

| SRS-BB-KnF-ID | Deskripsi |
|---------------|-----------|
| [SRS-BB-SIBEKu-KnF1] | Tidak pernah gagal untuk menampilkan data materi, soal, jadwal, dan feedback |
| [SRS-BB-SIBEKu-KnF2] | Mudah digunakan |
| [SRS-BB-SIBEKu-KnF3] | Penyimpanan database MySQL |
| [SRS-BB-SIBEKu-KnF4] | Tidak pernah gagal |
| [SRS-BB-SIBEKu-KnF5] | Mudah diadopsi pada lingkungan sistem operasi Microsoft® Windows atau Linux |
| [SRS-BB-SIBEKu-KnF6] | Hanya dapat digunakan dengan akun yang telah terdaftar |
| [SRS-BB-SIBEKu-KnF7] | Internet Connection secara realtime untuk melihat informasi data terupdate |
| [SRS-BB-SIBEKu-KnF8] | Aplikasi antarmuka menggunakan bahasa Indonesia |

---

## LAMPIRAN 1

### Tabel Traceability (Kerunutan)

| URS | SRS |
|-----|-----|
| **Modul Registrasi dan Login** | |
| Pemilik menginginkan kerahasiaan data.<br>[URS.BB.SIBeKu.01.01.01] | Sistem menyediakan kerahasiaan data dengan enkripsi password<br>[SRS.BB.SIBeKu.01.01] |
| Pengelola menginginkan data terjaga kerahasiaannya<br>[URS.BB.SIBeKu.01.02.01] | |
| Tentor menginginkan adanya kerahasiaan password dengan enkripsi<br>[URS.BB.SIBeKu.02.03.03] | |
| Tentor menginginkan kerahasiaan data<br>[URS.BB.SIBeKu.01.04.03] | |
| Pemilik menginginkan data yang diinputkan saat registrasi adalah email, password dan nomor telepon.<br>[URS.BB.SIBeKu.01.01.02] | Sistem menyediakan inputan saat registrasi adalah nama, email, nomor telepon, username, dan password (insert into user(username, password, nama_user, email_user, no_telp_user, tipe_user) values ("", "", "", "", "", "");)<br>[SRS.BB.SIBeKu.01.02] |
| Pengelola menginginkan data yang diinputkan yaitu nama, username dan password<br>[URS.BB.SIBeKu.01.02.02] | |
| Tentor menginginkan data yang diinputkan saat login adalah email dan password<br>[URS.BB.SIBeKu.02.03.02] | |
| Tentor menginginkan data yang diinput saat login adalah email dan password<br>[URS.BB.SIBeKu.01.04.02] | |
| Pengelola menginginkan adanya fitur edit data akun<br>[URS.BB.SIBeKu.01.02.02] | Sistem menyediakan fitur edit data akun untuk user (update user set username = "", password = "", nama_user = "", email_user = "", no_telp_user = "" where username = "")<br>[SRS.BB.SIBeKu.01.03] |
| Tentor menginginkan fitur pemberitahuan kesalahan dan sukses saat input email/password<br>[URS.BB.SIBeKu.01.03.01] | Sistem menyediakan fitur pemberitahuan kesalahan dan sukses saat input email/password<br>[SRS.BB.SIBeKu.01.04] |
| Tentor menginginkan fitur pemberitahuan kesalahan dan sukses saat input email/password<br>[URS.BB.SIBeKu.01.04.01] | |
| **Modul Kelas dan Materi** | |
| Pemilik menginginkan tampilan yang berisikan daftar kelas, jurusan, dan materi<br>[URS.BB.SIBeKu.02.01.01] | Sistem menampilkan halaman kelas dan materi yang didalamnya berisikan nama materi, kelas, nama mapel, deskripsi dan file materi (select nama_materi, kelas, mapel, deskripsi, file_materi from materi;)<br>[SRS.BB.SIBeKu.02.01] |
| Pengelola menginginkan halaman yang menampilkan pemilihan kelas, jurusan, mata pelajaran dan materi<br>[URS.BB.SIBeKu.02.02.01] | |
| Pengelola menginginkan pemilik, pengelola, dan tentor untuk memiliki hak akses untuk mengelola subsistem kelas dan materi<br>[URS.BB.SIBeKu.02.02.02] | Sistem memberikan hak akses untuk pemilik, pengelola, dan tentor untuk mengelola halaman kelas dan materi seperti edit (UPDATE soal set mapel = "", kelas = "", nama_materi = "", file_materi = "" where id_materi = "";), menambahkan materi (INSERT into materi(kelas, mapel, nama_materi, deskripsi_materi, file_materi) values ("", "", "", "", "");), dan menghapus materi (DELETE from materi where id_materi = "";)<br>[SRS.BB.SIBeKu.02.02] |
| Tentor menginginkan hak akses untuk melihat, mengupload dan mengedit materi<br>[URS.BB.SIBeKu.02.03.01] | |
| Pemilik menginginkan adanya fitur mengunduh materi dan pencarian materi<br>[URS.BB.SIBeKu.02.01.02] | Sistem menyediakan fitur pencarian (select nama_materi, kelas, mapel, deskripsi, file_materi from materi where nama_materi like %...%;) dan fitur download materi (<a href = "" download = "">)<br>[SRS.BB.SIBeKu.02.03] |
| Pengelola menginginkan adanya fitur mengunduh materi dan pencarian materi<br>[URS.BB.SIBeKu.02.02.03] | |
| Tentor memerlukan fitur pencarian di halaman kelas dan materi<br>[URS.BB.SIBeKu.02.03.03] | |
| Murid menginginkan adanya fitur pencarian kelas<br>[URS.BB.SIBeKu.02.04.01] | |
| Murid membutuhkan adanya fitur mengunduh materi<br>[URS.BB.SIBeKu.02.04.02] | |
| Tentor menginginkan bentuk materi yang ditampilkan berupa pdf<br>[URS.BB.SIBeKu.02.03.02] | Sistem menampilkan bentuk materi berupa pdf (<input type = "file" accept = ".pdf">)<br>[SRS.BB.SIBeKu.02.04] |
| **Modul Jadwal** | |
| Pemilik menginginkan jadwal berisi mata pelajaran dan waktu pelaksanaan<br>[URS.BB.SIBeKu.03.01.01] | Sistem menampilkan informasi berupa mata pelajaran, kelas, tentor, dan waktu pelaksanaan (hari dan waktu) berdasarkan tahun ajaran (select * from jadwal where tahun_ajaran = "";) berbentuk tabel (<table>, <th>, <tr>, <td>)<br>[SRS.BB.SIBeKu.03.01] |
| Pengelola menginginkan pada jadwal terdapat kelas, mata pelajaran, tentor dan waktu pelaksanaan<br>[URS.BB.SIBeKu.03.02.02] | |
| Tentor menginginkan pada jadwal terdapat informasi mata pelajaran, hari/tanggal, waktu, dan nama tentor<br>[URS.BB.SIBeKu.03.03.02] | |
| Pemilik menginginkan subsistem jadwal dapat diakses oleh semua user<br>[URS.BB.SIBeKu.03.01.02] | Sistem memberikan akses kepada semua user untuk mengakses halaman jadwal<br>[SRS.BB.SIBeKu.03.02] |
| Pemilik perlu fitur cetak jadwal<br>[URS.BB.SIBeKu.03.01.03] | Sistem menyediakan fitur download jadwal (select file_jadwal from jadwal; <a href = "" download = "">)<br>[SRS.BB.SIBeKu.03.03] |
| Pengelola perlu fitur cetak jadwal<br>[URS.BB.SIBeKu.03.02.03] | |
| Tentor menginginkan fitur cetak jadwal<br>[URS.BB.SIBeKu.03.03.03] | |
| Murid memerlukan adanya fitur cetak jadwal<br>[URS.BB.SIBeKu.03.04.02] | |
| Pengelola menginginkan jadwal berbentuk tabel<br>[URS.BB.SIBeKu.03.02.01] | Sistem menampilkan jadwal berbentuk tabel (<table>, <th>, <tr>, <td>)<br>[SRS.BB.SIBeKu.03.04] |
| Tentor menginginkan jadwal berbentuk tabel<br>[URS.BB.SIBeKu.03.03.01] | |
| Murid menginginkan jadwal berbentuk tabel<br>[URS.BB.SIBeKu.03.04.01] | |
| **Modul Bank Soal** | |
| Pemilik menginginkan data pada subsistem Bank Soal terdiri dari nama mata pelajaran dan judul materi<br>[URS.BB.SIBeKu.04.01.01] | Sistem menampilkan Bank Soal yang didalamnya terdapat kelas, nama mata pelajaran, judul soal, dan file soal (select nama_soal, kelas, mapel, file_soal from soal;)<br>[SRS.BB.SIBeKu.04.01] |
| Pengelola menginginkan tampilan berupa nama mata pelajaran yang didalamnya terdapat soal<br>[URS.BB.SIBeKu.04.02.01] | |
| Murid menginginkan tampilan yang terdapat nama mata pelajaran dan judul materi<br>[URS.BB.SIBeKu.04.04.01] | |
| Pemilik menginginkan fitur download soal<br>[URS.BB.SIBeKu.04.01.02] | Sistem menyediakan fitur download (<a href = "" download = "">) dan fitur pencarian soal (select mapel, kelas, nama_soal, file_soal from soal where nama_soal like %...%;)<br>[SRS.BB.SIBeKu.04.02] |
| Pengelola menginginkan fitur pencarian dan download soal<br>[URS.BB.SIBeKu.04.02.02] | |
| Tentor menginginkan fitur pencarian soal<br>[URS.BB.SIBeKu.04.03.03] | |
| Murid menginginkan fitur download soal<br>[URS.BB.SIBeKu.04.04.02] | |
| Murid menginginkan fitur pencarian soal<br>[URS.BB.SIBeKu.04.04.03] | |
| Pemilik menginginkan semua user dapat mengakses subsistem Bank Soal<br>[URS.BB.SIBeKu.04.01.03] | Sistem memberikan akses untuk semua user dalam mengakses halaman Bank Soal<br>[SRS.BB.SIBeKu.04.03] |
| Tentor menginginkan fitur edit soal<br>[URS.BB.SIBeKu.04.03.01] | Sistem menyediakan fitur edit soal (update soal set mapel = "", kelas = "", nama_soal = "", file_soal = "" where id_soal = "";)<br>[SRS.BB.SIBeKu.04.04] |
| Tentor menginginkan soal yang di upload dalam bentuk pdf<br>[URS.BB.SIBeKu.04.03.02] | Sistem menerima upload soal dalam bentuk pdf (<input type = "file" accept = ".pdf">)<br>[SRS.BB.SIBeKu.04.05] |
| **Modul Feedback** | |
| Pemilik menginginkan adanya fitur rating sebagai parameter penilaian<br>[URS.BB.SIBeKu.05.01.01] | Sistem menyediakan fitur rating (insert into feedback (username, user_review, date, type_review, rating) values ("", "", "", "", ""))<br>[SRS.BB.SIBeKu.05.01] |
| Pengelola menginginkan adanya fitur rating<br>[URS.BB.SIBeKu.05.02.01] | |
| Tentor menginginkan fitur rating<br>[URS.BB.SIBeKu.05.03.02] | |
| Murid menginginkan fitur rating<br>[URS.BB.SIBeKu.05.04.02] | |
| Pemilik menginginkan adanya fitur pesan<br>[URS.BB.SIBeKu.05.01.02] | Sistem menyediakan fitur pesan (insert into feedback (username, user_review, date, type_review, rating) values ("", "", "", "", ""))<br>[SRS.BB.SIBeKu.05.02] |
| Pengelola menginginkan adanya feedback untuk tentor<br>[URS.BB.SIBeKu.05.02.02] | |
| Tentor menginginkan fitur pesan<br>[URS.BB.SIBeKu.05.03.01] | |
| Murid menginginkan fitur pesan<br>[URS.BB.SIBeKu.05.04.01] | |
| Pemilik menginginkan adanya feedback untuk tentor<br>[URS.BB.SIBeKu.05.01.03] | Sistem menyediakan feedback untuk tentor dan murid (select username, tipe_pengguna where tipe_pengguna = "tentor" or tipe_pengguna = "murid")<br>[SRS.BB.SIBeKu.05.03] |
| Pengelola menginginkan adanya feedback untuk tentor<br>[URS.BB.SIBeKu.05.02.02] | |
| Tentor menginginkan feedback untuk dirinya<br>[URS.BB.SIBeKu.05.03.03] | |
| Murid menginginkan adanya feedback untuk tentor<br>[URS.BB.SIBeKu.05.04.03] | |

---

## PENUTUP

Dokumen Spesifikasi Kebutuhan Perangkat Lunak (SKPL) Sistem Informasi Belajar Kuy ini telah disusun untuk memberikan gambaran lengkap mengenai kebutuhan fungsional dan non-fungsional sistem. Dokumen ini menjadi panduan utama dalam proses pengembangan perangkat lunak dan dapat digunakan sebagai acuan untuk memastikan bahwa sistem yang dikembangkan sesuai dengan kebutuhan pengguna.

---

**Catatan:**
- Dokumen ini bersifat dinamis dan dapat diperbarui sesuai dengan kebutuhan pengembangan
- Semua perubahan pada dokumen harus melalui proses review dan approval yang sesuai
- Gambar-gambar diagram dan antarmuka yang direferensikan dalam dokumen ini tersedia dalam file terpisah

---

*Dokumen ini dan informasi yang dimilikinya adalah milik Jurusan Informatika - Unsoed dan bersifat rahasia. Dilarang untuk mereproduksi dokumen ini tanpa diketahui oleh Jurusan Informatika Unsoed.*