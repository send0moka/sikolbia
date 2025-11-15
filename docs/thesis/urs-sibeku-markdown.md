# SPESIFIKASI KEBUTUHAN PENGGUNA
## (BIMBINGAN BELAJAR)
## SISTEM INFORMASI BELAJAR KUY

Disusun untuk memenuhi tugas Mata Kuliah Analisis dan Desain Sistem (ADS)  
Yang dibimbing oleh dosen Teguh Cahyono, S.T., M.Kom

**Disusun oleh:**
- Maulana Hafez Ahyatara Tempariyawan (H1D021004)
- Ayu Anjar Paramestuti (H1D021007)
- Usriyatul Khamimah (H1D021015)
- Ahita Bisma Adlula (H1D021030)
- Pilar Filino Hadi (H1D021084)

---

**KEMENTERIAN RISET DAN TEKNOLOGI**  
**UNIVERSITAS NEGERI JENDERAL SOEDIRMAN**  
**FAKULTAS TEKNIK**  
**JURUSAN INFORMATIKA**  
JALAN MAYJEND SOENGKONO KM 5, BLATER, KALIMANAH – PURBALINGGA

**Direktorat TIK / UNSOED**  
**Nomor Dokumen:** URS – SIBeKu  
**Halaman:** 1 / 37  
**Revisi Tgl:** 24 Desember 2022

---

## LEMBAR PENGESAHAN

### Module Registrasi dan Login
**Dipersiapkan Oleh:**  
Nama: Usriyatul Khamimah  
Tanda Tangan: _______________

**Diperiksa Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

**Disetujui Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

### Module Kelas dan Materi
**Dipersiapkan Oleh:**  
Nama: Pilar Filino Hadi  
Tanda Tangan: _______________

**Diperiksa Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

**Disetujui Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

### Module Jadwal
**Dipersiapkan Oleh:**  
Nama: Maulana Hafez Ahyatara T.  
Tanda Tangan: _______________

**Diperiksa Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

**Disetujui Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

### Module Bank Soal
**Dipersiapkan Oleh:**  
Nama: Ayu Anjar Paramestuti  
Tanda Tangan: _______________

**Diperiksa Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

**Disetujui Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

### Module Feedback
**Dipersiapkan Oleh:**  
Nama: Ahita Bisma Adlula  
Tanda Tangan: _______________

**Diperiksa Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

**Disetujui Oleh:**  
Nama: _______________  
Tanda Tangan: _______________

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

| INDEX | A | B | C | D | E | F |
|-------|---|---|---|---|---|---|
| Tgl | | | | | | |
| Ditulis Oleh | | | | | | |
| Diperiksa Oleh | | | | | | |
| Disetujui Oleh | | | | | | |

---

## DAFTAR HALAMAN PERUBAHAN

| Halaman | Revisi | Halaman | Revisi |
|---------|--------|---------|--------|
| | | | |

---

## DAFTAR ISI

- [LEMBAR PENGESAHAN](#lembar-pengesahan)
- [DAFTAR PERUBAHAN](#daftar-perubahan)
- [DAFTAR HALAMAN PERUBAHAN](#daftar-halaman-perubahan)
- [DAFTAR ISI](#daftar-isi)
- [DAFTAR TABEL](#daftar-tabel)
- [BAB I PENDAHULUAN](#bab-i-pendahuluan)
  - 1.1. Pendahuluan
  - 1.2. Tujuan Penulisan Dokumen
  - 1.3. Lingkup Masalah
  - 1.4. Target Audience
  - 1.5. Definisi, Istilah, dan Singkatan
  - 1.6. Referensi
  - 1.7. Deskripsi Umum Dokumen
- [BAB II DESKRIPSI RINCI KEBUTUHAN PENGGUNA](#bab-ii-deskripsi-rinci-kebutuhan-pengguna)
  - 2.1. Aturan Penomoran
  - 2.2. Deskripsi Rincian Kebutuhan Pengguna
    - 2.2.1. Modul Registrasi dan Login
    - 2.2.2. Modul Kelas dan Materi
    - 2.2.3. Modul Jadwal
    - 2.2.4. Modul Bank Soal
    - 2.2.5. Modul Feedback
- [Lampiran](#lampiran)

---

## DAFTAR TABEL

- Tabel 1: Definisi, Istilah dan Singkatan
- Tabel 2: Aturan Penomoran

---

# BAB I PENDAHULUAN

## 1.1. Pendahuluan

Dokumen ini berisi Spesifikasi Kebutuhan Pengguna (SKP) atau User Requirement Specification (URS) untuk SIBeku (Sistem Informasi Belajar Kuy). Untuk penamaan dokumen ini selanjutnya, akan digunakan istilah URS.

## 1.2. Tujuan Penulisan Dokumen

Tujuan dari penulisan dokumen ini adalah untuk memberi gambaran mengenai spesifikasi kebutuhan pengguna atau URS dari sebuah sistem informasi bimbingan belajar bernama Belajar Kuy berbasis web. Dokumen ini akan menjelaskan mengenai kemampuan aplikasi sesuai dengan kebutuhan masing-masing pengguna serta menjelaskan tujuan dan fitur dari sistem, interface dari sistem, apa yang sistem lakukan, batasan dimana sistem beroperasi dan bagaimana sistem berinteraksi.

## 1.3. Lingkup Masalah

Media pembelajaran adalah suatu alat atau benda yang dapat digunakan untuk perantara menyalurkan pelajaran atau materi agar peserta didik mudah untuk memahami materi yang disampaikan oleh guru atau tentor. Dengan berkembangnya teknologi informasi yang semakin pesat dan mudahnya dalam mendapatkan informasi menciptakan kemudahan dalam kegiatan belajar-mengajar. 

Sistem Informasi Bimbingan Belajar "Belajar Kuy" merupakan sebuah media pembelajaran berupa perangkat lunak berbasis web yang digunakan dalam kegiatan bimbingan belajar. Perangkat lunak ini dapat membantu tentor dan murid dalam mengakses materi dan soal-soal dimanapun serta memudahkan murid dalam mengikuti bimbingan belajar. Dengan adanya "Belajar Kuy" diharapkan kegiatan bimbingan belajar dapat meningkatkan kualitas pendidikan yang optimal.

## 1.4. Target Audience

Target audience dari dokumen ini adalah:
- a. Pemilik Bimbingan Belajar
- b. Pengelola Bimbingan Belajar
- c. Tentor Bimbingan Belajar
- d. Murid Bimbingan Belajar

## 1.5. Definisi, Istilah, dan Singkatan

### Tabel 1: Definisi, Istilah dan Singkatan

| Istilah dan Singkatan | Definisi |
|------------------------|----------|
| URS | URS atau User Requirement Specification merupakan sebuah dokumen yang menggambarkan segala sesuatu yang pengguna (user) butuhkan dari sebuah sistem. |
| SKPL | SKPL atau Spesifikasi Kebutuhan Perangkat lunak adalah spesifikasi dari perangkat lunak yang akan dikembangkan. |
| DFD | DFD atau Data Flow Diagram adalah diagram yang menggambarkan proses aliran data dari sebuah sistem informasi yang dibangun. |
| RPL | Perubahan perangkat lunak guna mengembangkan, memelihara, dan membangun kembali dengan menggunakan prinsip rekayasa untuk menghasilkan perangkat lunak yang dapat bekerja lebih efisien dan efektif untuk pengguna. |
| HyperText Markup Language (HTML) | HTML adalah bahasa markup standar yang digunakan untuk membuat halaman website dan aplikasi web. |
| Cascading Style Sheets (CSS) | CSS merupakan bahasa yang digunakan untuk menentukan tampilan dan format sebuah halaman website. |
| Hypertext Preprocessor (PHP) | PHP merupakan bahasa penulisan skrip open-source yang banyak digunakan dalam pemrograman atau pengembangan website (Web Development). |

## 1.6. Referensi

Referensi yang digunakan pada perangkat lunak ini adalah:
1. Roger S Pressman, Ph.D. Rekayasa Perangkat Lunak. 2002
2. Andri Kristanto. Rekayasa Perangkat Lunak (Konsep Dasar). 2004
3. IEEE Std 830-1993, IEEE Recommended Practice for Software Requirement Specifications
4. Bayu Hendradjaya. Panduan Penulisan Spesifikasi Kebutuhan Perangkat lunak (SKPL). Jurusan Teknik Informatika ITB.
5. STAF IF. GL01, Spesifikasi Kebutuhan Perangkat Lunak. Jurusan Teknik Informatika ITB.
6. Arry Ekananta, ST. Spesifikasi Kebutuhan Perangkat Lunak AKSES. Departemen Ilmu Komputer IPB.

## 1.7. Deskripsi Umum Dokumen

Dokumen URS ini dibagi menjadi tiga bagian utama. Bagian utama berisi penjelasan tentang dokumen URS yang mencakup tujuan pembuatan dokumen ini, lingkup masalah yang diselesaikan oleh perangkat lunak yang dikembangkan, definisi, referensi dan deskripsi umum, dan pengenalan pengguna. Bagian kedua berisi deskripsi kebutuhan user. Bagian ketiga berisi draf wawancara yang dilakukan oleh sistem analis dengan narasumber.

---

# BAB II DESKRIPSI RINCI KEBUTUHAN PENGGUNA

## 2.1. Aturan Penomoran

Terdapat beberapa hal/bagian dalam dokumen ini yang perlu diberi nomor. Maksud penomoran ini untuk mempermudah audience dalam proses pengidentifikasian. Adapun aturan penomorannya sebagaimana tabel berikut:

### Tabel 2: Aturan Penomoran

**URS.BB.SIBeku.XX.YY.ZZ**

**Keterangan:**
- **URS** = User Requirement Specification
- **BB** = Bimbingan Belajar
- **SIBeKu** = Sistem Informasi Belajar Kuy
- **XX** = Nomor subsistem
- **YY** = Nomor pengguna
- **ZZ** = Nomor kebutuhan pengguna

## 2.2. Deskripsi Rincian Kebutuhan Pengguna

### 2.2.1. Modul Registrasi dan Login [URS.BB.SIBeKu.01]

Modul Registrasi dan Login adalah modul yang berisikan sub sistem registrasi dan login. User yang belum terdaftar dapat melakukan registrasi terlebih dahulu. User yang sudah terdaftar pada SIBeKu dapat login untuk melihat subsistem lainnya.

#### 1. Pemilik [URS.BB.SIBeKu.01.01]

Pemilik pada modul Registrasi dan Login memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data registrasi atau login. Pemilik tidak perlu melakukan registrasi, akan tetapi pemilik dapat login sebagai pemilik agar dapat menggunakan seluruh subsistem. Berikut kebutuhan Pemilik dalam modul ini:

- a. Pemilik menginginkan kerahasiaan data. **[URS.BB.SIBeKu.01.01.01]**
- b. Pemilik menginginkan data yang diinputkan saat registrasi adalah email, password dan nomor telepon. **[URS.BB.SIBeKu.01.01.02]**

#### 2. Pengelola [URS.BB.SIBeKu.01.02]

Pengelola pada modul Registrasi dan Login memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data registrasi atau login. Pengelola tidak perlu melakukan registrasi, akan tetapi pengelola dapat login sebagai admin agar dapat menggunakan seluruh subsistem. Berikut kebutuhan Pengelola dalam modul ini:

- a. Pengelola menginginkan data terjaga kerahasiaannya **[URS.BB.SIBeKu.01.02.01]**
- b. Pengelola menginginkan data yang diinputkan yaitu nama, username dan password **[URS.BB.SIBeKu.01.02.02]**
- c. Pengelola menginginkan adanya fitur edit data akun **[URS.BB.SIBeKu.01.02.03]**

#### 3. Tentor [URS.BB.SIBeKu.01.03]

Tentor pada modul Registrasi dan Login memiliki akses untuk modul ini, Tentor perlu melakukan registrasi apabila belum mempunyai akun dan dapat login agar dapat menggunakan seluruh subsistem. Berikut kebutuhan Tentor dalam modul ini:

- a. Tentor menginginkan fitur pemberitahuan kesalahan dan sukses saat input email/password **[URS.BB.SIBeKu.01.03.01]**
- b. Tentor menginginkan data yang diinputkan saat login adalah email dan password **[URS.BB.SIBeKu.01.03.02]**
- c. Tentor menginginkan adanya kerahasiaan password dengan enkripsi **[URS.BB.SIBeKu.01.03.03]**

#### 4. Murid [URS.BB.SIBeKu.01.04]

Murid pada modul Registrasi dan Login memiliki akses untuk modul ini, seperti menambahkan, mengedit dan menghapus data registrasi atau login. Tentor perlu melakukan registrasi apabila belum mempunyai akun dan dapat login agar dapat menggunakan seluruh subsist. Berikut kebutuhan Murid dalam modul ini:

- a. Tentor menginginkan fitur pemberitahuan kesalahan dan sukses saat input email/password **[URS.BB.SIBeKu.01.04.01]**
- b. Tentor menginginkan data yang diinput saat login adalah email dan password **[URS.BB.SIBeKu.01.04.02]**
- c. Tentor menginginkan kerahasiaan data **[URS.BB.SIBeKu.01.04.03]**

### 2.2.2. Modul Kelas dan Materi [URS.BB.SIBeKu.02]

Modul Kelas dan Materi adalah modul yang berisikan sub sistem kelas dan materi untuk digunakan user tentor dan murid.

#### 1. Pemilik [URS.BB.SIBeKu.02.01]

Pemilik pada kelas dan materi memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus. Berikut kebutuhan Pemilik dalam modul ini:

- a. Pemilik menginginkan tampilan yang berisikan daftar kelas, jurusan, dan materi **[URS.BB.SIBeKu.02.01.01]**
- b. Pemilik menginginkan adanya fitur mengunduh materi dan pencarian materi **[URS.BB.SIBeKu.02.01.02]**

#### 2. Pengelola [URS.BB.SIBeKu.02.02]

Pengelola pada modul kelas dan materi memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit, dan menghapus. Untuk mengakses modul kelas dan materi, pengelola diharuskan untuk login terlebih dahulu. Berikut kebutuhan Pengelola dalam modul ini:

- a. Pengelola menginginkan halaman yang menampilkan pemilihan kelas, jurusan, mata pelajaran dan materi **[URS.BB.SIBeKu.02.02.01]**
- b. Pengelola menginginkan pemilik, pengelola, dan tentor untuk memiliki hak akses untuk mengelola subsistem kelas dan materi **[URS.BB.SIBeKu.02.02.02]**
- c. Pengelola menginginkan adanya fitur mengunduh materi dan pencarian materi **[URS.BB.SIBeKu.02.02.03]**

#### 3. Tentor [URS.BB.SIBeKu.02.03]

Tentor pada modul kelas dan materi memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit, dan menghapus. Untuk mengakses modul kelas dan materi, tentor diharuskan untuk login terlebih dahulu. Berikut kebutuhan Tentor dalam modul ini:

- a. Tentor menginginkan hak akses untuk melihat, mengupload dan mengedit materi **[URS.BB.SIBeKu.02.03.01]**
- b. Tentor menginginkan bentuk materi yang ditampilkan berupa pdf **[URS.BB.SIBeKu.02.03.02]**
- c. Tentor memerlukan fitur pencarian di halaman kelas dan materi **[URS.BB.SIBeKu.02.03.03]**

#### 4. Murid [URS.BB.SIBeKu.02.04]

Murid pada modul kelas dan materi memiliki akses untuk melihat materi. Untuk dapat melihat materi, murid diharuskan untuk login terlebih dahulu. Berikut kebutuhan Murid dalam modul ini:

- a. Murid menginginkan adanya fitur pencarian kelas **[URS.BB.SIBeKu.02.04.01]**
- b. Murid membutuhkan adanya fitur mengunduh materi **[URS.BB.SIBeKu.02.04.02]**

### 2.2.3. Modul Jadwal [URS.BB.SIBeKu.03]

Modul Jadwal adalah modul yang berisikan subsistem jadwal. Dimana pada subsistem ini menampilkan jadwal yang berisi informasi terkait pelaksanaan bimbingan belajar.

#### 1. Pemilik [URS.BB.SIBeKu.03.01]

Pemilik pada modul jadwal memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data jadwal. Untuk mengakses modul jadwal, Pemilik harus melakukan login terlebih dahulu. Berikut kebutuhan Pemilik dalam modul ini:

- a. Pemilik menginginkan jadwal berisi mata pelajaran dan waktu pelaksanaan **[URS.BB.SIBeKu.03.01.01]**
- b. Pemilik menginginkan subsistem jadwal dapat diakses oleh semua user **[URS.BB.SIBeKu.03.01.02]**
- c. Pemilik perlu fitur cetak jadwal **[URS.BB.SIBeKu.03.01.03]**

#### 2. Pengelola [URS.BB.SIBeKu.03.02]

Pengelola pada modul jadwal memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data jadwal. Untuk mengakses modul jadwal, Pengelola harus melakukan login terlebih dahulu. Berikut kebutuhan Pengelola dalam modul ini:

- a. Pengelola menginginkan jadwal berbentuk tabel **[URS.BB.SIBeKu.03.02.01]**
- b. Pengelola menginginkan pada jadwal terdapat kelas, mata pelajaran, tentor dan waktu pelaksanaan **[URS.BB.SIBeKu.03.02.02]**
- c. Pengelola perlu fitur cetak jadwal **[URS.BB.SIBeKu.03.02.03]**

#### 3. Tentor [URS.BB.SIBeKu.03.03]

Tentor pada modul jadwal memiliki akses untuk melihat jadwal pelaksanaan bimbingan belajar. Untuk mengakses modul jadwal, Tentor harus melakukan login terlebih dahulu. Berikut kebutuhan Tentor dalam modul ini:

- a. Tentor menginginkan jadwal berbentuk tabel **[URS.BB.SIBeKu.03.03.01]**
- b. Tentor menginginkan pada jadwal terdapat informasi mata pelajaran, hari/tanggal, waktu, dan nama tentor **[URS.BB.SIBeKu.03.03.02]**
- c. Tentor menginginkan fitur cetak jadwal **[URS.BB.SIBeKu.03.03.03]**

#### 4. Murid [URS.BB.SIBeKu.03.04]

Murid pada modul jadwal memiliki akses untuk melihat jadwal pelaksanaan bimbingan belajar. Untuk mengakses modul jadwal, Murid harus melakukan login terlebih dahulu. Berikut kebutuhan Murid dalam modul ini:

- a. Murid menginginkan jadwal berbentuk tabel **[URS.BB.SIBeKu.03.04.01]**
- b. Murid memerlukan adanya fitur cetak jadwal **[URS.BB.SIBeKu.03.04.02]**

### 2.2.4. Modul Bank Soal [URS.BB.SIBeKu.04]

Modul bank soal adalah modul yang berisikan subsistem bank soal. Pada subsistem bank soal, pemilik dapat mengelola dan menampilkan terkait soal-soal yang sesuai dengan materi.

#### 1. Pemilik [URS.BB.SIBeKu.04.01]

Pemilik pada modul Bank Soal memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul Bank Soal, Pemilik harus melakukan login terlebih dahulu. Berikut kebutuhan Pemilik dalam modul ini:

- a. Pemilik menginginkan data pada subsistem Bank Soal terdiri dari nama mata pelajaran dan judul materi **[URS.BB.SIBeKu.04.01.01]**
- b. Pemilik menginginkan fitur download soal **[URS.BB.SIBeKu.04.01.02]**
- c. Pemilik menginginkan semua user dapat mengakses subsistem Bank Soal **[URS.BB.SIBeKu.04.01.03]**

#### 2. Pengelola [URS.BB.SIBeKu.04.02]

Pengelola pada modul Bank Soal memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul Bank Soal, Pengelola harus melakukan login terlebih dahulu. Berikut kebutuhan Pengelola dalam modul ini:

- a. Pengelola menginginkan tampilan berupa nama mata pelajaran yang didalamnya terdapat soal **[URS.BB.SIBeKu.04.02.01]**
- b. Pengelola menginginkan fitur pencarian dan download soal **[URS.BB.SIBeKu.04.02.02]**

#### 3. Tentor [URS.BB.SIBeKu.04.03]

Tentor pada modul Bank Soal memiliki akses untuk mengelola modul, seperti menambahkan, mengedit dan menghapus. Untuk mengakses modul Bank Soal, Tentor harus melakukan login terlebih dahulu. Berikut kebutuhan Tentor dalam modul ini:

- a. Tentor menginginkan fitur edit soal **[URS.BB.SIBeKu.04.03.01]**
- b. Tentor menginginkan soal yang di upload dalam bentuk pdf **[URS.BB.SIBeKu.04.03.02]**
- c. Tentor menginginkan fitur pencarian soal **[URS.BB.SIBeKu.04.03.03]**

#### 4. Murid [URS.BB.SIBeKu.04.04]

Murid pada modul Bank Soal dapat mengakses modul Bank Soal dengan melakukan login terlebih dahulu. Berikut kebutuhan Murid dalam modul ini:

- a. Murid menginginkan tampilan yang terdapat nama mata pelajaran dan judul materi **[URS.BB.SIBeKu.04.04.01]**
- b. Murid menginginkan fitur download soal **[URS.BB.SIBeKu.04.04.02]**
- c. Murid menginginkan fitur pencarian soal **[URS.BB.SIBeKu.04.04.03]**

### 2.2.5. Modul Feedback [URS.BB.SIBeKu.05]

Modul feedback adalah modul yang berisikan sub sistem feedback. Pada subsistem ini user tentor dapat menambahkan feedback terhadap website sedangkan untuk user murid dapat menambahkan feedback kepada tentor maupun website.

#### 1. Pemilik [URS.BB.SIBeKu.05.01]

Pemilik pada modul feedback memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data feedback. Untuk mengakses modul, pemilik harus melakukan login terlebih dahulu. Berikut kebutuhan Pemilik dalam modul ini:

- a. Pemilik menginginkan adanya fitur rating sebagai parameter penilaian **[URS.BB.SIBeKu.05.01.01]**
- b. Pemilik menginginkan adanya fitur pesan **[URS.BB.SIBeKu.05.01.02]**
- c. Pemilik menginginkan adanya feedback untuk tentor **[URS.BB.SIBeKu.05.01.03]**

#### 2. Pengelola [URS.BB.SIBeKu.05.02]

Pengelola pada modul feedback memiliki akses untuk mengelola modul ini, seperti menambahkan, mengedit dan menghapus data feedback. Untuk mengakses modul, pengelola harus melakukan login terlebih dahulu. Berikut kebutuhan Pengelola dalam modul ini:

- a. Pengelola menginginkan adanya fitur rating **[URS.BB.SIBeKu.05.02.01]**
- b. Pengelola menginginkan adanya feedback untuk tentor **[URS.BB.SIBeKu.05.02.02]**

#### 3. Tentor [URS.BB.SIBeKu.05.03]

Tentor pada modul feedback memiliki akses untuk melihat modul. Untuk mengakses modul, tentor harus melakukan login terlebih dahulu. Berikut kebutuhan Tentor dalam modul ini:

- a. Tentor menginginkan fitur pesan **[URS.BB.SIBeKu.05.03.01]**
- b. Tentor menginginkan fitur rating **[URS.BB.SIBeKu.05.03.02]**
- c. Tentor menginginkan feedback untuk dirinya **[URS.BB.SIBeKu.05.03.03]**

#### 4. Murid [URS.BB.SIBeKu.05.04]

Murid pada modul feedback memiliki akses untuk melihat modul. Untuk mengakses modul, murid harus melakukan login terlebih dahulu. Berikut kebutuhan Murid dalam modul ini:

- a. Murid menginginkan fitur pesan **[URS.BB.SIBeKu.05.04.01]**
- b. Murid menginginkan fitur rating **[URS.BB.SIBeKu.05.04.02]**
- c. Murid menginginkan adanya feedback untuk tentor **[URS.BB.SIBeKu.05.04.03]**

---

# Lampiran

## Subsistem Registrasi dan Login

**Analis:** Usriyatul Khamimah

### User: Pemilik
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apa pemilik menginginkan kerahasiaan data? | Ya, kerahasiaan data harus dijaga dengan enkripsi | Agar data user terjaga dengan baik | Data user tidak disalahgunakan |
| 2. | Fitur registrasi seperti apa yang pemilik inginkan? | Memasukkan nama, username, email, password, confirm password, dan kalau bisa nomor telepon | Untuk memudahkan user saat proses registrasi | Tidak ada hambatan dalam proses login |

**Mengetahui,**  
Pemilik Bimbingan Belajar  
Pilar Filino Hadi

---

### User: Pengelola
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apa yang anda inginkan saat proses login? | Data password terjaga kerahasiaannya dengan enkripsi | Memudahkan alur login dan terjaga kerahasiaan password | Tidak ada keraguan untuk login |
| 2. | Data apa saja yang dikirimkan ke sistem? | Nama, username dan password | Agar user tidak kebanyakan menginputkan data | Memudahkan dalam proses login |
| 3. | Apakah user memerlukan fitur edit data akun? | Iya, perlu | Agar memudahkan user membenarkan data jika ada salah input | Memudahkan user dalam membenarkan data |

**Mengetahui,**  
Pengelola Bimbingan Belajar  
Maulana Hafez Ahyatara Tempariyawan

---

### User: Tentor
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Fitur apa yang tentor inginkan? | Fitur pemberitahuan kesalahan dan sukses saat input email/password | Agar mengetahui input data yang salah | Memudahkan dalam melakukan login |
| 2. | Data apa saja yang diperlukan tentor dalam proses login? | Email dan password | Tentor mudah melakukan login | Login dapat dilakukan dengan mudah |
| 3. | Apa yang tentor inginkan saat proses login? | Kerahasiaan password dengan enkripsi | Agar user tidak kesulitan dalam proses login dan password terjaga | Alur login mudah dan kerahasiaan password |

**Mengetahui,**  
Tentor Bimbingan Belajar  
Ayu Anjar Paramestuti

---

### User: Murid
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Fitur apa yang diinginkan saat melakukan login? | Fitur pesan kesalahan dan sukses saat login | Agar mengetahui input data yang salah | Tidak menimbulkan kebingungan saat login |
| 2. | Data apa saja yang diperlukan saat login? | Email dan password | Agar memudahkan dalam proses login | Aktivitas login dilakukan dengan mudah |
| 3. | Data apa saja yang diperlukan pada saat proses registrasi? | Nama, username, email dan password | Agar user tidak terlalu banyak menginputkan data | Penginputan data saat registrasi tidak memberatkan user |
| 4. | Fitur apa saja yang diinginkan pada saat registrasi? | Fitur pesan berhasil atau gagal ketika registrasi | Agar user mengetahui registrasi sudah berhasil atau belum | Memudahkan dalam proses registrasi |

**Mengetahui,**  
Murid Bimbingan Belajar  
Ahita Bisma Adlula

---

## Subsistem Kelas dan Materi

**Analis:** Pilar Filino Hadi

### User: Pemilik
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Bagaimana tampilan subsistem yang diinginkan pemilik? | Tampilan yang berisikan daftar kelas, jurusan, dan materi | Agar tampilan mudah diakses sesuai dengan keinginan pemilik | Mempermudah pemilik untuk mengakses materi |
| 2. | Bagaimana cara kerja dari subsistem kelas dan materi yang diinginkan pemilik? | Dari pemilihan kelas lalu pemilihan jurusan (IPA/IPS) dan terakhir materi | Agar tidak membingungkan pengakses dalam subsistem ini | Tidak adanya malfungsi saat mengakses kelas dan materi |
| 3. | Fitur apa yang ingin ditambahkan oleh pemilik? | Fitur mengunduh materi dan pencarian materi | Agar dapat memenuhi kebutuhan fitur dalam mengelola materi | Melengkapi halaman kelas dan materi dengan fitur-fitur |

**Mengetahui,**  
Pemilik Bimbingan Belajar  
Maulana Hafez Ahyatara Tempariyawan

---

### User: Pengelola
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Bentuk tampilan yang bagaimana yang diinginkan oleh Pengelola? | Halaman yang menampilkan pemilihan kelas, jurusan, mata pelajaran, dan materi | Agar tampilan mudah diakses sesuai dengan keinginan pengelola | Agar memberikan kemudahan kepada pengelola dalam mengakses materi |
| 2. | Siapa saja yang memiliki hak akses untuk mengelola ke dalam subsistem kelas dan materi? | Pemilik, pengelola, dan tentor | Agar yang diberikan hak akses dapat mengelola dan melihat-lihat subsistem | Siapa saja yang mendapatkan akses diharapkan dapat mengelola subsistem |
| 3. | Apakah Pengelola memerlukan hak akses untuk mengupload dan mengedit materi? | Ya perlu hak akses untuk mengupload dan mengedit materi | Agar tidak terjadinya kesalahan upload materi yang dilakukan oleh tentor | Pengelola dapat melihat-lihat ada atau tidaknya kesalahan pada materi |
| 4. | Fitur apa yang ingin ditambahkan oleh pengelola? | Fitur mengunduh materi dan pencarian materi | Agar dapat memenuhi kebutuhan fitur dalam mengelola materi | Melengkapi halaman kelas dan materi dengan fitur-fitur |

**Mengetahui,**  
Pengelola Bimbingan Belajar  
Ayu Anjar Paramestuti

---

### User: Tentor
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apakah tentor diberikan hak akses untuk melihat? | Ya, saya menginginkan hak akses untuk melihat | Agar tentor dapat memantau materi-materi yang ada | Tentor dapat melihat materi yang sudah terupload |
| 2. | Bagaimana bentuk dari materi yang akan ditampilkan? | Bentuk dari materi dapat berupa file pdf | Agar tentor tidak kebingungan dalam mengupload materi | Menyesuaikan dengan bentuk materi yang lain |
| 3. | Apakah Tentor memerlukan hak akses untuk mengupload dan mengedit materi? | Ya, saya memerlukan hak akses untuk mengupload dan mengedit materi | Agar tentor dapat mengoreksi materi kalau ada suatu kesalahan | Tidak ada lagi yang salah upload |
| 4. | Fitur apa yang ingin ditambahkan oleh tentor? | Fitur Pencarian materi | Agar dapat memenuhi kebutuhan fitur dalam mengelola materi | Melengkapi halaman kelas dan materi dengan fitur-fitur |

**Mengetahui,**  
Tentor Bimbingan Belajar  
Ahita Bisma Adlula

---

### User: Murid
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Tampilan yang seperti apa yang diinginkan oleh murid? | Yang menarik dan tidak membingungkan | Agar murid dapat mengakses materi dengan mudah dan tidak membingungkan | Murid dapat mengakses materi dengan mudah tanpa adanya kesalahan |
| 2. | Apakah ada fitur-fitur yang ingin ditambahkan oleh murid? | Fitur pencarian materi | Agar mempermudah murid untuk mengakses kelas dan materi | Fitur-fitur yang diinginkan dapat mempermudah murid |
| 3. | Apakah Murid membutuhkan fitur download materi? | Ya perlu, agar bisa melihat materi tanpa adanya internet | agar murid dapat mengakses materi tanpa harus ke website | Murid dapat mengakses materi dimana saja |

**Mengetahui,**  
Murid Bimbingan Belajar  
Usriyatul Khamimah

---

## Subsistem Jadwal

**Analis:** Maulana Hafez Ahyatara Tempariyawan

### User: Pemilik
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apa saja data yang perlu ditampilkan pada saat mengunjungi halaman jadwal? | Informasi jadwal sesuai kelas yang berisi mata pelajaran dan waktu pelaksanaan | Mengetahui informasi yang perlu ditampilkan pada halaman jadwal | Informasi yang ditampilkan tidak membingungkan |
| 2. | Siapa saja yang dapat mengakses halaman jadwal, semua user atau tidak? | Semua user dapat mengakses halaman jadwal | Mengetahui siapa saja yang dapat mengakses halaman jadwal | Akses ke halaman jadwal lebih terarah |
| 3. | Apakah Pemilik memerlukan fitur cetak jadwal? | Ya perlu fitur cetak jadwal | Agar Pemilik dapat mengakses jadwal kapanpun | Pemilik dapat mencetak dan mengakses dimana mana saja |

**Mengetahui,**  
Pemilik Bimbingan Belajar  
Ayu Anjar Paramestuti

---

### User: Pengelola
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Bagaimana tampilan halaman jadwal yang Pengelola inginkan? | Tampilan yang memuat informasi yang diperlukan dan mudah dibaca | Agar Pengelola tidak kesulitan dalam membaca halaman jadwal | Mudah memahami isi dari halaman jadwal |
| 2. | Lebih baik jadwal berbentuk deskripsi atau berbentuk tabel? | Tabel | Memudahkan Pengelola dalam membaca halaman jadwal | Halaman jadwal sesuai dengan bentuk yang diinginkan |
| 3. | Data apa saja yang perlu ditampilkan pada halaman jadwal? | Kelas, mata pelajaran, tentor dan waktu | Mengetahui informasi yang perlu ditampilkan pada halaman jadwal | Informasi yang ditampilkan tidak membingungkan |
| 4. | Apakah Pengelola memerlukan fitur cetak jadwal? | Ya, saya perlu | Agar Pengelola dapat mengakses jadwal kapanpun | Pengelola dapat mencetak dan mengakses jadwal dimana saja |

**Mengetahui,**  
Pengelola Bimbingan Belajar  
Ahita Bisma Adlula

---

### User: Tentor
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Lebih baik jadwal berbentuk deskripsi atau berbentuk tabel? | Saya lebih baik dalam bentuk tabel | Memudahkan Tentor dalam membaca halaman jadwal | Halaman jadwal sesuai dengan bentuk yang diinginkan |
| 2. | Apa saja data yang perlu ditampilkan pada halaman jadwal | Nomor, nama mata pelajaran, hari/tanggal, waktu, dan nama tentor | Mengetahui informasi yang perlu ditampilkan pada halaman jadwal | Informasi yang caditampilkan tidak membingungkan |
| 3. | Apakah Tentor memerlukan fitur cetak jadwal? | Iya, perlu agar ketika tentor sedang tidak ada internet tetap bisa melihat jadwal | Agar Tentror dapat mengakses jadwal kapanpun | Tentor dapat mencetak dan mengakses jadwal dimana saja |

**Mengetahui,**  
Tentor Bimbingan Belajar  
Usriyatul Khamimah

---

### User: Murid
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Lebih baik jadwal berbentuk deskripsi atau berbentuk tabel? | Lebih baik berbentuk tabel | Memudahkan Murid dalam membaca halaman jadwal | Halaman jadwal sesuai dengan bentuk yang diinginkan |
| 2. | Apa saja data yang perlu ditampilkan pada halaman jadwal | Hari, waktu, nama mata pelajaran, dan tentor | Mengetahui informasi yang perlu ditampilkan pada halaman jadwal | Informasi yang ditampilkan tidak membingungkan |
| 3. | Apakah Murid memerlukan fitur cetak jadwal? | Ya, butuh untuk memudahkan siswa melihat jadwal tanpa harus ke halaman jadwal | Agar Murid dapat mengakses jadwal kapanpun | Murid dapat mencetak dan mengakses jadwal dimana saja |

**Mengetahui,**  
Murid Bimbingan Belajar  
Pilar Filino Hadi

---

## Subsistem Bank Soal

**Analis:** Ayu Anjar Paramestuti

### User: Pemilik
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apa saja data yang diperlukan pemilik pada subsistem Bank Soal? | Data yang diperlukan yaitu nama mata pelajaran dan judul materi | Mengetahui informasi yang diperlukan pada subsistem Bank Soal | Informasi pada subsistem mudah dipahami |
| 2. | Apakah pemilik membutuhkan fitur download soal? | Ya, pemilik membutuhkan fitur download soal | Agar pemilik dapat menggunakan soal kapanpun | Pemilik dapat mengakses soal secara offline |
| 3. | Siapa saja yang dapat mengakses subsistem Bank Soal, semua user atau tidak? | Semua user dapat mengakses subsistem Bank Soal | Mengetahui siapa saja yang dapat mengakses Bank Soal | Akses ke halaman bank soal lebih terarah |

**Mengetahui,**  
Pemilik Bimbingan Belajar  
Ahita Bisma Adlula

---

### User: Pengelola
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apa yang ingin ditampilkan dalam halaman bank soal? | Nama mata pelajaran yang di dalamnya terdapat soal | Memudahkan dalam mengakses bank soal | Tidak ada hambatan dalam mengakses bank soal |
| 2. | Apakah ada fitur-fitur tertentu yang dibutuhkan pada subsistem Bank Soal? | Fitur pencarian dan download soal dibutuhkan pada subsistem Bank Soal | Memenuhi kebutuhan fitur dalam mengelola Bank Soal | Memberi kenyamanan dalam mengelola Bank Soal |

**Mengetahui,**  
Pengelola Bimbingan Belajar  
Usriyatul Khamimah

---

### User: Tentor
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apakah tentor perlu fitur mengedit bank soal? | Ya, agar tentor dapat mengubah soal jika ada suatu kesalahan | Untuk membantu tentor ketika melakukan kesalahan input soal | Alur mudah dalam melakukan pengeditan data bank soal |
| 2. | Bagaimana proses input soal-soal yang tentor inginkan? | Soal-soal akan diupload dalam bentuk pdf | Memudahkan tentor dalam meng-upload soal | Tidak ada kendala dalam meng-upload soal |
| 3. | Apakah Anda memerlukan fitur pencarian pada halaman bank soal? | Ya, agar mempermudah tentor dalam mencari soal secara spesifik | Memudahkan dalam pencarian soal yang diinginkan | Tidak ada kendala saat mencari soal yang diinginkan |

**Mengetahui,**  
Tentor Bimbingan Belajar  
Pilar Filino Hadi

---

### User: Murid
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Tampilan Bank Soal seperti apa yang murid inginkan? | Tampilan yang didalamnya terdapat nama mata pelajaran dan dan judul materi | Agar mudah dalam mengakses Bank Soal | Tampilan Bank Soal sesuai dengan apa yang diinginkan |
| 2. | Apakah murid perlu akses untuk mendownload soal? | Perlu, agar saat tidak ada akses internet Saya tetap dapat mengerjakan soal latihan | Agar dapat melihat soal tanpa harus mengakses web | Agar dapat mengakses soal lebih leluasa |
| 3. | Apakah Anda memerlukan fitur pencarian pada halaman bank soal? | Perlu, agar Saya dapat mencari soal-soal latihan dengan cepat | Memudahkan dalam pencarian soal yang diinginkan | Tidak ada kendala saat mencari soal yang diinginkan |

**Mengetahui,**  
Murid Bimbingan Belajar  
Maulana Hafez Ahyatara Tempariyawan

---

## Subsistem Feedback

**Analis:** Ahita Bisma Adlula

### User: Pemilik
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Halaman Feedback seperti apa yang Anda inginkan? | Halaman yang menyediakan pilihan emoji atau angka dari 1-5 sebagai pilihan untuk user memberikan penilaian terhadap website | Agar pemilik mudah mengetahui penilaian user terhadap website | Halaman feedback sesuai dengan bentuk yang diinginkan |
| 2. | Apakah Anda membutuhkan kritik atau saran dari user? | Ya, saya membutuhkan satu kolom pesan agar user dapat mengirimkan tanggapannya | Pemilik dapat mengetahui saran dan kritik dari user | Bahan evaluasi untuk website |
| 3. | Apakah Anda menginginkan agar semua user mengirimkan feedback? | Tidak, user berhak untuk tidak mengirimkan feedback | Agar sistem terus mendapatkan ulasan dari pemakai website | Website melakukan maintenance setiap waktu |
| 4. | Apakah Anda memerlukan feedback untuk tentor? | Perlu | Untuk mengetahui masalah pada tentor | Bahan evaluasi untuk tentor |

**Mengetahui,**  
Pemilik Bimbingan Belajar  
Usriyatul Khamimah

---

### User: Pengelola
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Fitur apa saja yang Anda inginkan di halaman Feedback? | Adanya fitur rating dalam berbentuk nilai (1 s/d 5), dan komentar terhadap cara kerja bimbel ini | Memudahkan pengelola dalam membuka halaman Feedback | Agar pengelola mengetahui apa saja yang diinput user |
| 2. | Apakah Anda perlu feedback untuk tentor? | Ya, agar pengelola dapat melihat tentor yang kinerjanya baik dan yang tidak | Untuk mengetahui masalah pada tentor | Bahan evaluasi untuk tentor |

**Mengetahui,**  
Pengelola Bimbingan Belajar  
Pilar Filino Hadi

---

### User: Tentor
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apakah Anda ingin menyampaikan tanggapan terhadap website? | Ya, saya ingin menyampaikan tanggapan saya terhadap website | Mengetahui masalah atau tanggapan tentor ketika menggunakan website | Bahan evaluasi untuk website |
| 2. | Apakah Anda menginginkan tanggapan Anda dilihat oleh user lain? | Ya, saya ingin agar tanggapan saya bisa dilihat oleh user lain | Memberi rasa aman kepada user dalam menyampaikan tanggapannya | Data user terjaga kerahasiaannya |
| 3. | Apakah Anda memerlukan fitur rating pada halaman feedback? | Perlu, agar Saya dapat mengetahui dengan cepat rata-rata kinerja dari bimbel ini | Agar mudah dalam melakukan feedback | Mudah dalam membuat laporan data |
| 4. | Apakah Anda memerlukan feedback untuk tentor? | Perlu, agar saya dapat mengevaluasi keberhasilan tentoring saya | Untuk mengetahui masalah pada tentor | Bahan evaluasi untuk tentor |

**Mengetahui,**  
Tentor Bimbingan Belajar  
Maulana Hafez Ahyatara Tempariyawan

---

### User: Murid
**Waktu Pelaksanaan:** Selasa, 6 September 2022

| No | Daftar Pertanyaan | Jawaban | Tujuan | Target |
|----|-------------------|---------|--------|--------|
| 1. | Apakah Anda ingin menyampaikan tanggapan terhadap website? | Ya, saya ingin menyampaikan tanggapan saya terhadap website | Mengetahui masalah atau tanggapan murid ketika menggunakan website | Bahan evaluasi untuk website |
| 2. | Apakah Anda menginginkan tanggapan Anda dilihat oleh user lain? | Ya, saya ingin agar tanggapan saya bisa dilihat orang lain | Memberi rasa aman kepada user dalam menyampaikan tanggapannya | Data user terjaga kerahasiaannya |
| 3. | Apakah Anda memerlukan fitur rating pada halaman feedback? | Ya, saya memerlukan fitur rating untuk menyampaikan penilaian terhadap bimbel | Agar mudah dalam melakukan feedback | Mudah dalam membuat laporan data |
| 4. | Apakah Anda memerlukan feedback untuk tentor? | Ya, saya memerlukan untuk menilai dan mengevaluasi tentor | Agar mengetahui masalah murid terhadap tentor | Bahan evaluasi untuk tentor |

**Mengetahui,**  
Murid Bimbingan Belajar  
Ayu Anjar Paramestuti

---

**Dokumen ini dan informasi yang dimilikinya adalah milik Jurusan Informatika - Unsoed dan bersifat rahasia.**  
**Dilarang untuk mereproduksi dokumen ini tanpa diketahui oleh Jurusan Informatika Unsoed**