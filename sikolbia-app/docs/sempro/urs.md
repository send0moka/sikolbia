# User Requirements Specification (URS)
## Sistem Informasi Konsumsi Lahan Bibit Iklim dan Alamat - Modul Konsumsi Pangan

### Dokumen Informasi
- **Nama Sistem**: SIKOLBIA - Modul Konsumsi Pangan (Neraca Bahan Makanan)
- **Versi**: 2.0
- **Tanggal**: Oktober 2025
- **Status**: Draft untuk Redesign
- **Periode Pengembangan**: 3 bulan

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen
Dokumen ini menjelaskan kebutuhan pengguna (User Requirements) untuk pengembangan modul konsumsi pangan dari Sistem Informasi Konsumsi Lahan Bibit Iklim dan Alamat (SIKOLBIA), dengan fokus khusus pada Neraca Bahan Makanan (NBM), prediksi konsumsi, dan analisis kalori komoditas pangan.

### 1.2 Ruang Lingkup
Modul Konsumsi Pangan SIKOLBIA adalah sistem informasi berbasis web yang mengintegrasikan:
- Data NBM (Neraca Bahan Makanan) historis 1993-2024
- 120 komoditas pangan dalam 11 kelompok komoditas
- AI/Machine Learning untuk prediksi konsumsi dan kalori
- Analisis SHAP untuk interpretabilitas model
- Dashboard analytics dan monitoring khusus NBM
- Sistem manajemen pengguna bertingkat

### 1.3 Definisi dan Akronim
- **SIKOLBIA**: Sistem Informasi Konsumsi Lahan Bibit Iklim dan Alamat
- **NBM**: Neraca Bahan Makanan
- **Pusdatin**: Pusat Data dan Sistem Informasi Pertanian
- **Kementan**: Kementerian Pertanian
- **BPN**: Badan Pangan Nasional
- **Bappenas**: Badan Perencanaan Pembangunan Nasional
- **SHAP**: SHapley Additive exPlanations (untuk interpretabilitas model AI)

### 1.4 Batasan Proyek
- **Waktu Pengembangan**: 3 bulan
- **Budget**: Tidak ada anggaran khusus (menggunakan teknologi open source)
- **Deployment**: Local server (bukan cloud)
- **Domain**: Localhost (belum ada domain resmi)
- **Fokus Gizi**: Hanya kalori (protein dan lemak tidak disertakan)
- **Email Service**: Menggunakan layanan gratis
- **File Storage**: Local storage

---

## 2. Analisis Pengguna dan Stakeholder

### 2.1 Identifikasi Pengguna

#### 2.1.1 **Administrator (Level 1 - Highest)**
**Definisi**: Pegawai Bagian Pengembangan Sistem Informasi dari Pusdatin Kementan
- **Jumlah**: 3-5 orang
- **Akses**: Full system access untuk modul konsumsi pangan
- **Lokasi**: Kantor Pusdatin Kementan, Jakarta
- **Jadwal Kerja**: Senin-Jumat, 08:00-17:00 WIB
- **URL Akses**: `/admin/konsumsi-pangan/`

**Karakteristik Pengguna:**
- Pendidikan: S1 Informatika/Sistem Informasi
- Pengalaman IT: 3-10 tahun
- Pemahaman Database: Advanced
- Pemahaman AI/ML: Intermediate
- Pemahaman Domain NBM: Advanced

#### 2.1.2 **Government Users (Level 2 - High)**
**Definisi**: Pemerintah/Pengambil Kebijakan
- **Organisasi**: 
  - Badan Pangan Nasional (BPN)
  - Kementerian Pertanian (Kementan)
  - Bappenas
  - Kementerian Perdagangan
  - Kementerian Perindustrian
- **URL Akses**: `/ketersediaan/pemerintah/`
- **URL Registrasi**: `/ketersediaan/pemerintah/pendaftaran/`

**Sub-kategori:**
- **Direktur/Kepala Badan**: Strategic overview, policy decisions
- **Kepala Bidang**: Operational oversight, detailed analysis
- **Staf Analisis**: Daily monitoring, report generation

**Karakteristik Pengguna:**
- Pendidikan: S1-S3 (Ekonomi, Pertanian, Kebijakan Publik)
- Pengalaman Domain: 5-20 tahun
- Literasi IT: Basic to Intermediate
- Kebutuhan Akses: Data NBM lengkap, prediksi AI, analisis SHAP
- Frekuensi Penggunaan: Daily to Weekly

#### 2.1.3 **Academic/Research Users (Level 3 - Medium)**
**Definisi**: Akademisi dan Peneliti
- **Organisasi**:
  - Universitas (IPB, UGM, UNPAD, dll)
  - Lembaga Penelitian (LIPI, Balitbang Kementan)
  - Think Tank (INDEF, CSIS)
  - Mahasiswa S2/S3
- **URL Akses**: `/ketersediaan/akademisi/`
- **URL Registrasi**: `/ketersediaan/akademisi/pendaftaran/`

**Karakteristik Pengguna:**
- Pendidikan: S2-S3 (Pertanian, Ekonomi, Data Science)
- Pengalaman Riset: 2-15 tahun
- Literasi IT: Intermediate to Advanced
- Kebutuhan Akses: Data historis NBM (1993-2024), tools analisis statistik
- Frekuensi Penggunaan: Project-based (Weekly to Monthly)

#### 2.1.4 **Public Users (Level 4 - Basic)**
**Definisi**: Masyarakat Umum
- **Target**:
  - Petani dan peternak
  - Pelaku industri pangan
  - Wartawan dan media
  - Mahasiswa S1
  - Masyarakat yang tertarik isu pangan
- **URL Akses**: `/ketersediaan/` (homepage public)

**Karakteristik Pengguna:**
- Pendidikan: SMA-S1
- Literasi IT: Basic
- Kebutuhan Akses: Statistik NBM publik, informasi komoditas dasar
- Frekuensi Penggunaan: Occasional (Monthly or less)

### 2.2 Matriks Akses Pengguna - Modul Konsumsi Pangan

| Fitur/Modul NBM | Admin | Pemerintah | Akademisi | Publik |
|-------------|-------|------------|----------|--------|
| Konfigurasi Sistem | ✓ | ✗ | ✗ | ✗ |
| Manajemen Pengguna | ✓ | Terbatas | ✗ | ✗ |
| Data NBM Mentah | ✓ | ✓ | Terbatas | ✗ |
| Manajemen Model AI | ✓ | Lihat Saja | ✗ | ✗ |
| Prediksi NBM Advanced | ✓ | ✓ | ✓ | ✗ |
| Analisis SHAP | ✓ | ✓ | Terbatas | ✗ |
| Export Data Lengkap | ✓ | ✓ | Request | ✗ |
| Export Data Ringkasan | ✓ | ✓ | ✓ | ✓ |
| Dashboard Publik NBM | ✓ | ✓ | ✓ | ✓ |
| API NBM | ✓ | Terbatas | Terbatas | Publik Saja |
| Data Historis 1993-2024 | ✓ | ✓ | ✓ | Ringkasan |
| 120 Komoditas Detail | ✓ | ✓ | ✓ | Populer Saja |
| 11 Kelompok Komoditas | ✓ | ✓ | ✓ | ✓ |

---

## 3. Kebutuhan Fungsional

### 3.1 Sistem Autentikasi dan Autorisasi

#### 3.1.1 **FR-AUTH-001: Multi-Level Authentication**
**Deskripsi**: Sistem harus mendukung autentikasi bertingkat sesuai level pengguna
**Priority**: Critical
**User Stories**:
- Sebagai Admin, saya ingin login dengan akun khusus admin agar dapat mengakses semua fitur sistem
- Sebagai Government user, saya ingin proses registrasi khusus dengan verifikasi instansi
- Sebagai Academic user, saya ingin proses registrasi dengan verifikasi akademik
- Sebagai Public user, saya ingin mengakses informasi publik tanpa registrasi

#### 3.1.2 **FR-AUTH-002: Registration Workflow**
**Government Registration Process** (`/ketersediaan/pemerintah/pendaftaran/`):
1. User mengisi form registrasi dengan data instansi
2. Upload surat pengantar resmi dari instansi (tersedia template via Google Drive)
3. Admin verifikasi dokumen dan instansi
4. Approval/rejection dengan notifikasi email (jehianathayata@gmail.com)
5. Account activation setelah approval

**Academic Registration Process** (`/ketersediaan/akademisi/pendaftaran/`):
1. User mengisi form registrasi dengan data akademik
2. Upload surat keterangan dari institusi/proposal penelitian (template via Google Drive)
3. Admin verifikasi dokumen akademik
4. Approval/rejection dengan notifikasi email
5. Account activation dengan batasan akses

### 3.2 Dashboard dan Interface

#### 3.2.1 **FR-DASH-001: Halaman Publik NBM** (`/ketersediaan/`)
**Deskripsi**: Halaman publik khusus modul konsumsi pangan
**Components**:
- Overview statistik NBM nasional
- Interactive charts konsumsi kalori per komoditas
- Informasi 11 kelompok komoditas utama
- Tren konsumsi pangan bulanan
- Download area untuk laporan NBM publik

#### 3.2.2 **FR-DASH-002: Dashboard Pemerintah** (`/ketersediaan/pemerintah/`)
**Deskripsi**: Dashboard khusus untuk pengambil kebijakan pangan
**Components**:
- Executive summary NBM dengan KPI utama
- Prediksi AI konsumsi pangan dengan confidence intervals
- Analisis SHAP untuk interpretabilitas model
- Perbandingan regional konsumsi kalori
- Alert system untuk anomali konsumsi pangan
- Policy recommendation berdasarkan prediksi NBM

#### 3.2.3 **FR-DASH-003: Dashboard Akademisi** (`/ketersediaan/akademisi/`)
**Deskripsi**: Dashboard untuk penelitian NBM
**Components**:
- Visualisasi data historis NBM 1993-2024
- Statistical analysis tools untuk 120 komoditas
- Data export functionality (CSV/Excel)
- Research collaboration tools
- Citation system untuk penggunaan data NBM

### 3.3 AI dan Machine Learning

#### 3.3.1 **FR-ML-001: Sistem Prediksi NBM**
**Deskripsi**: Sistem prediksi konsumsi pangan dan kalori menggunakan AI
**Requirements**:
- Prediksi konsumsi kalori untuk 120 komoditas
- Multi-step prediction (1-12 bulan ke depan)
- Confidence intervals untuk setiap prediksi
- SHAP analysis untuk explainable AI
- Model training dengan data 1993-2024
- Real-time model monitoring

#### 3.3.2 **FR-ML-002: Interpretabilitas Model**
**Deskripsi**: Sistem untuk menjelaskan hasil prediksi AI NBM
**Components**:
- SHAP feature importance untuk faktor konsumsi
- Penjelasan prediksi dalam bahasa Indonesia
- Visual explanation dengan charts
- Analisis faktor yang mempengaruhi konsumsi kalori
- Scenario simulation untuk policy planning

### 3.4 Data Management

#### 3.4.1 **FR-DATA-001: Sistem Input Data NBM**
**Deskripsi**: Sistem input data NBM terpusat
**Requirements**:
- Bulk upload data NBM via Excel/CSV
- Validasi data untuk 120 komoditas dalam 11 kelompok
- Data validation konsumsi kalori
- Version control untuk dataset NBM
- Audit trail untuk semua perubahan data

#### 3.4.2 **FR-DATA-002: Sistem Export Data NBM**
**Deskripsi**: Sistem ekspor data NBM sesuai level akses
**Export Formats**:
- Excel untuk pengguna pemerintah (data lengkap)
- CSV/JSON untuk akademisi (data penelitian)  
- PDF report untuk publik (ringkasan)
- API response untuk developers (endpoint NBM)

### 3.5 Template dan Dokumentasi

#### 3.5.1 **FR-TEMPLATE-001: Template Surat Pengantar**
**Deskripsi**: Template surat resmi untuk registrasi
**Components**:
- Template surat pengantar pemerintah (Google Drive)
- Template surat keterangan akademik (Google Drive)
- Redirect button ke Google Drive dari halaman registrasi
- Format .docx yang dapat diedit

---

## 4. Kebutuhan Non-Fungsional

### 4.1 Performance Requirements

#### 4.1.1 **NFR-PERF-001: Response Time**
- **Public pages**: < 2 detik
- **Authenticated pages**: < 3 detik
- **AI predictions**: < 10 detik
- **Data export**: < 30 detik
- **Bulk operations**: < 5 menit

#### 4.1.2 **NFR-PERF-002: Skalabilitas**
- **Concurrent users**: 50 simultaneous users (local deployment)
- **Data volume**: Data NBM historis 1993-2024 (31 tahun)
- **Komoditas**: 120 komoditas dalam 11 kelompok
- **Growth rate**: Data bulanan baru setiap bulan
- **Peak usage**: 100% normal load (tidak ada cloud scaling)

### 4.2 Kebutuhan Keamanan

#### 4.2.1 **NFR-SEC-001: Proteksi Data**
- Enkripsi data sensitif NBM
- HTTPS untuk localhost (self-signed certificate)
- Rate limiting untuk API NBM
- SQL injection protection
- XSS protection
- Local file storage security

#### 4.2.2 **NFR-SEC-002: Kontrol Akses**
- Role-based access control (4 level: Admin, Pemerintah, Akademisi, Publik)
- Session management dengan timeout
- Failed login attempt protection
- Data access logging untuk NBM
- Regular security audit (manual)

### 4.3 Kebutuhan Usabilitas

#### 4.3.1 **NFR-USE-001: Pengalaman Pengguna**
- Web responsive design (untuk berbagai ukuran layar desktop/laptop)
- Antarmuka dalam Bahasa Indonesia
- Consistent UI/UX untuk modul konsumsi pangan
- Context-sensitive help system
- Dokumentasi lengkap dalam Bahasa Indonesia
- **Akses hanya via web browser** (tidak ada mobile apps)

---

## 5. Workflow dan Proses Bisnis

### 5.1 Proses Data Flow NBM

```
Data NBM → Validasi → Penyimpanan → Processing → Analisis AI → Visualisasi → Akses User
```

#### 5.1.1 **Workflow Pengumpulan Data NBM**
1. **Input Manual**: Admin input data NBM via web interface
2. **Bulk Upload**: Excel/CSV upload data konsumsi kalori dengan validasi
3. **Validasi Data**: Automated checks untuk 120 komoditas + manual review
4. **Data Publishing**: Data NBM yang approved tersedia untuk analisis
5. **Model Training**: Data historis 1993-2024 untuk training AI

#### 5.1.2 **Workflow Prediksi NBM**
1. **Data Preparation**: Preprocessing data NBM terbaru
2. **Model Inference**: AI prediction untuk konsumsi kalori
3. **SHAP Analysis**: Interpretabilitas faktor yang berpengaruh
4. **Result Validation**: Review hasil prediksi oleh admin
5. **Publication**: Hasil prediksi tersedia sesuai level akses user

#### 5.1.2 **AI Prediction Workflow**
1. **Data Preparation**: Historical data cleaning dan preprocessing
2. **Model Training**: Automated retraining based on new data
3. **Prediction Generation**: Batch predictions untuk semua komoditas
4. **Quality Assurance**: Model performance monitoring
5. **Result Publishing**: Predictions available via dashboard dan API

### 5.2 User Request Workflows

#### 5.2.1 **Government User Registration**
```
Registration Request → Document Verification → Institutional Verification → 
Admin Approval → Account Activation → Access Granted
```

#### 5.2.2 **Data Access Request**
```
User Request → Authorization Check → Data Preparation → 
Admin Approval (if required) → Data Delivery → Usage Tracking
```

---

## 6. Integrasi dan API

### 6.1 External Integrations

#### 6.1.1 **Data Sources**
- **BPS**: Statistik produksi dan konsumsi
- **Kementan**: Data pertanian dan peternakan
- **BPN**: Data kebijakan pangan
- **Bank Indonesia**: Data ekonomi dan harga
- **BMKG**: Data cuaca dan iklim

#### 6.1.2 **Output Integration**
- **Government Portals**: Data untuk portal pemerintah
- **Academic Platforms**: Data untuk penelitian
- **Public APIs**: Open data untuk developer
- **Web Access Only**: Semua akses melalui web browser (tidak ada mobile apps)

### 6.2 API Requirements

#### 6.2.1 **Public API**
- **Endpoint**: `/api/public/`
- **Data**: Basic statistics, published reports
- **Rate Limit**: 100 requests/hour/IP
- **Format**: JSON, no authentication required

#### 6.2.2 **Authenticated API**
- **Endpoint**: `/api/v1/`
- **Data**: Detailed data sesuai user level
- **Authentication**: Bearer token
- **Rate Limit**: Sesuai user level
- **Format**: JSON, XML, CSV

---

## 7. Acceptance Criteria

### 7.1 Functional Acceptance

#### 7.1.1 **User Management**
- [ ] Admin dapat membuat, mengedit, dan menghapus user
- [ ] Government user dapat mengakses data sesuai level autorisasi
- [ ] Academic user dapat mengakses data untuk penelitian
- [ ] Public user dapat mengakses informasi publik
- [ ] Registration workflow bekerja sesuai proses

#### 7.1.2 **AI Prediction**
- [ ] Model dapat memprediksi NBM dengan akurasi > 85%
- [ ] SHAP analysis tersedia untuk semua prediksi
- [ ] Confidence intervals akurat dan informatif
- [ ] Model monitoring mendeteksi drift performance
- [ ] Prediction explanation dalam bahasa Indonesia

### 7.2 Performance Acceptance

#### 7.2.1 **System Performance**
- [ ] Load time halaman publik < 2 detik
- [ ] AI prediction response < 10 detik
- [ ] System dapat handle 100 concurrent users
- [ ] Data export selesai < 30 detik
- [ ] 99.9% uptime availability

### 7.3 Security Acceptance

#### 7.3.1 **Security Compliance**
- [ ] Semua komunikasi menggunakan HTTPS
- [ ] Data sensitif terenkripsi
- [ ] Access control bekerja sesuai level user
- [ ] Audit log tersimpan untuk semua aktivitas
- [ ] Penetration testing passed

---

## 8. Constraints dan Asumsi

### 8.1 Kendala Teknis
- **Platform**: Web-based application (localhost)
- **Framework**: Laravel 12 + FastAPI
- **Database**: MySQL (via Docker)
- **Deployment**: Docker containerization (local)
- **Cloud**: Tidak menggunakan cloud (production local)
- **Domain**: Localhost (belum ada domain resmi)

### 8.2 Kendala Bisnis
- **Budget**: Tidak ada anggaran khusus
- **Timeline**: Development dalam 3 bulan
- **Technology**: Harus menggunakan teknologi gratis/open source
- **Email Service**: Menggunakan layanan email gratis
- **File Storage**: Local file storage
- **Language**: Bahasa Indonesia only

### 8.3 Asumsi
- Data NBM historis 1993-2024 tersedia dan akurat
- User akan mengikuti prosedur registrasi dengan template surat
- Local server infrastructure cukup untuk deployment
- Monitoring menggunakan tools gratis
- Email notifications cukup untuk user communication

---

## 9. Glossary

| Term | Definition |
|------|------------|
| **SIKOLBIA** | Sistem Informasi Konsumsi Lahan Bibit Iklim dan Alamat |
| **NBM** | Neraca Bahan Makanan - sistem accounting untuk konsumsi pangan |
| **SHAP** | SHapley Additive exPlanations - metode explainable AI |
| **Pusdatin** | Pusat Data dan Sistem Informasi Pertanian Kementan |
| **BPN** | Badan Pangan Nasional |
| **Komoditas** | Jenis bahan pangan (120 jenis dalam 11 kelompok) |
| **Kalori** | Satuan energi dari konsumsi pangan (fokus utama modul) |
| **Confidence Interval** | Range nilai prediksi dengan tingkat kepercayaan tertentu |

---

**Approval:**
- **Business Analyst**: _____________________
- **Technical Lead**: _____________________  
- **Stakeholder Representative**: _____________________
- **Date**: _____________________