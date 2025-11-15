# SPESIFIKASI KEBUTUHAN PERANGKAT LUNAK (SRS)
## SISTEM INFORMASI KETERSEDIAAN PANGAN DAN LAHAN PERTANIAN (SIKOLBIA)

---

**Versi Dokumen**: 1.0  
**Tanggal**: 15 November 2025  
**Status**: Final

**Disusun oleh:**  
Jehian Athaya Az Dzikri  
Program Studi Informatika  
Universitas Jenderal Soedirman

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Deskripsi Umum](#2-deskripsi-umum)
3. [Kebutuhan Spesifik](#3-kebutuhan-spesifik)
4. [Kebutuhan Non-Fungsional](#4-kebutuhan-non-fungsional)
5. [Batasan dan Asumsi](#5-batasan-dan-asumsi)

---

## 1. PENDAHULUAN

### 1.1 Tujuan Dokumen

Dokumen ini menjelaskan spesifikasi kebutuhan perangkat lunak untuk Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian (SIKOLBIA), yang mengintegrasikan machine learning untuk prediksi Neraca Bahan Makanan (NBM) menggunakan LSTM.

### 1.2 Ruang Lingkup Sistem

SIKOLBIA adalah sistem berbasis web yang menyediakan:
- **Prediksi NBM dengan LSTM** (Machine Learning Time Series)
- **Manajemen Data Konsumsi Pangan** (Kelompok, Komoditi, Transaksi NBM)
- **Manajemen Data Lahan Pertanian**
- **Manajemen Data Iklim & Optimalisasi DPI**
- **Manajemen Data Benih & Pupuk**
- **Direktori Alamat Instansi Pertanian**

### 1.3 Definisi dan Singkatan

| Istilah | Definisi |
|---------|----------|
| **SRS** | Software Requirements Specification |
| **SIKOLBIA** | Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian |
| **NBM** | Neraca Bahan Makanan |
| **LSTM** | Long Short-Term Memory (Deep Learning Model) |
| **MAPE** | Mean Absolute Percentage Error |
| **RBAC** | Role-Based Access Control |
| **API** | Application Programming Interface |
| **CRUD** | Create, Read, Update, Delete |

### 1.4 Referensi

- IEEE Std 830-1998, IEEE Recommended Practice for SRS
- Laravel 12 Documentation (https://laravel.com/docs/12.x)
- TensorFlow/Keras Documentation untuk LSTM
- Spatie Laravel Permission Documentation

---

## 2. DESKRIPSI UMUM

### 2.1 Perspektif Produk

SIKOLBIA adalah sistem standalone berbasis web dengan arsitektur microservices:
- **Frontend**: Laravel 12 + Livewire 3 + Tailwind CSS
- **Backend**: Laravel 12 + MySQL 8.0
- **ML Service**: FastAPI + TensorFlow 2.15 (Python)
- **Deployment**: Docker Compose (7 containers)

### 2.2 Fungsi Produk

#### 2.2.1 Modul Konsumsi Pangan NBM (Primary Module)

**Fungsi Utama:**
1. **Prediksi NBM dengan LSTM**
   - Input: 6 bulan data historis (tahun, bulan, kelompok, komoditi, kalori)
   - Output: Prediksi kalori/hari untuk N bulan kedepan
   - Target Akurasi: MAPE < 10%

2. **AI Insights Generation**
   - Trend Analysis (increasing/decreasing/stable)
   - Risk Assessment (low/medium/high)
   - Volatility Analysis (coefficient of variation)
   - Anomaly Detection (outlier identification)
   - Policy Recommendations

3. **Visualisasi Interaktif**
   - Line Chart: Trend prediksi
   - Area Chart: Confidence interval (upper/lower bounds)
   - Comparison Chart: Historical vs Predicted

4. **History Management**
   - Simpan prediksi ke database
   - Bookmark prediksi penting
   - Filter berdasarkan tanggal/kelompok/komoditi
   - Delete history

5. **Export Functionality**
   - Excel: 2 sheets (Predictions + Historical Data)
   - PDF: Formatted report dengan charts

#### 2.2.2 Modul Lahan Pertanian

**Fungsi Utama:**
1. Manajemen Data Lahan (Topik, Variabel, Klasifikasi, Data)
2. Filter Multi-Dimensi (Tahun, Bulan, Wilayah, Topik, Variabel)
3. Export Excel per filter
4. Visualisasi data tabel dinamis

#### 2.2.3 Modul Iklim & Optimalisasi DPI

**Fungsi Utama:**
1. Manajemen Data Iklim (14 Topik: Curah Hujan, Suhu, dll.)
2. Filter berdasarkan Tahun, Wilayah, Topik, Variabel
3. Export Excel per filter
4. Dashboard monitoring iklim

#### 2.2.4 Modul Benih & Pupuk

**Fungsi Utama:**
1. Manajemen Data Benih & Pupuk
2. Import CSV untuk bulk upload
3. Filter Multi-Dimensi
4. Export dengan template Excel
5. Visualisasi distribusi per wilayah

#### 2.2.5 Modul Daftar Alamat

**Fungsi Utama:**
1. CRUD Alamat Instansi Pertanian
2. Master Data Provinsi (34) & Kabupaten/Kota (514)
3. Map Visualization (Leaflet.js)
4. Export daftar alamat per wilayah
5. Public access untuk ketersediaan alamat

### 2.3 Karakteristik Pengguna

| Role | Hak Akses | Jumlah Estimasi |
|------|-----------|-----------------|
| **Superadmin** | Full system access, user management, all CRUD | 1-2 users |
| **Admin** | Module management, CRUD operations, reports | 5-10 users |
| **Pemerintah** | View, filter, export data pertanian (regional) | 50-100 users |
| **Akademisi** | View, download untuk research purposes | 20-50 users |

### 2.4 Batasan Sistem

1. **Performance**: Response time < 2 detik untuk 90% request
2. **Data Volume**: Support hingga 100,000 transaksi NBM
3. **Concurrent Users**: Hingga 100 simultaneous users
4. **ML Model**: LSTM dengan MAPE < 10%
5. **Browser Support**: Chrome 90+, Firefox 88+, Edge 90+

### 2.5 Asumsi dan Ketergantungan

**Asumsi:**
- User memiliki koneksi internet minimal 2 Mbps
- Browser support HTML5, CSS3, JavaScript ES6+
- Data NBM historis tersedia minimal 2 tahun

**Ketergantungan:**
- Docker & Docker Compose untuk deployment
- MySQL 8.0 untuk database
- FastAPI ML service UP dan healthy
- Laravel Queue Worker untuk background jobs

---

## 3. KEBUTUHAN SPESIFIK

### 3.1 Kebutuhan Fungsional

#### 3.1.1 Modul Authentication & Authorization

**FR-AUTH-001: Registrasi User**
- **Deskripsi**: User dapat mendaftar akun baru (role: pemerintah atau akademisi)
- **Input**: Nama, Email, Password, Jenis Dinas (pemerintah), ID Card (akademisi)
- **Output**: Akun tersimpan dengan status "pending approval"
- **Validasi**: Email unique, password min 8 karakter
- **SQL**: 
  ```sql
  INSERT INTO users (name, email, password, created_at) 
  VALUES (?, ?, ?, NOW());
  
  INSERT INTO registrasi_akses (user_id, jenis_registrasi, status, dokumen_ktp) 
  VALUES (?, ?, 'pending', ?);
  ```

**FR-AUTH-002: Login User**
- **Deskripsi**: User login dengan email & password
- **Input**: Email, Password
- **Output**: Session created, redirect berdasarkan role
- **Validasi**: Credentials match, account not suspended
- **SQL**: 
  ```sql
  SELECT * FROM users WHERE email = ? AND is_suspended = 0;
  ```

**FR-AUTH-003: Approval Registration**
- **Deskripsi**: Admin approve/reject registrasi user
- **Input**: Registration ID, Decision (approve/reject), Reason (jika reject)
- **Output**: User notified via email, role assigned (jika approve)
- **SQL**: 
  ```sql
  UPDATE registrasi_akses 
  SET status = ?, alasan_penolakan = ?, approved_at = NOW() 
  WHERE id = ?;
  
  -- Jika approve, assign role
  INSERT INTO model_has_roles (role_id, model_type, model_id) 
  VALUES (?, 'App\\Models\\User', ?);
  ```

**FR-AUTH-004: Role-Based Access Control**
- **Deskripsi**: Sistem enforce permission berdasarkan role user
- **Roles**: superadmin, admin, pemerintah, akademisi
- **Middleware**: `role:admin|superadmin`, `permission:view users`
- **Implementation**: Spatie Laravel Permission

#### 3.1.2 Modul Prediksi NBM (Core Feature)

**FR-NBM-001: Predict Kalori (LSTM)**
- **Deskripsi**: User memilih kelompok, komoditi, dan jumlah bulan prediksi
- **Input**: 
  - `kelompok_id` (1-13)
  - `komoditi_id` (1-162)
  - `prediction_months` (1-6)
- **Process**:
  1. Ambil 6 bulan data historis terakhir dari `transaksi_nbms`
  2. POST ke FastAPI `/predict` endpoint
  3. ML model (LSTM) prediksi N bulan kedepan
  4. Generate confidence intervals (lower/upper bounds)
- **Output**: 
  ```json
  {
    "predictions": [920.5, 935.2, 948.7],
    "confidence_interval": {
      "lower": [910.2, 925.8, 938.4],
      "upper": [930.8, 944.6, 959.0]
    },
    "model_info": {...}
  }
  ```
- **SQL**: 
  ```sql
  SELECT tahun, bulan, kalori_hari 
  FROM transaksi_nbms 
  WHERE kode_kelompok = ? AND kode_komoditi = ?
  ORDER BY tahun DESC, bulan DESC 
  LIMIT 6;
  ```
- **Target Performance**: MAPE < 10%, Response time < 2s

**FR-NBM-002: Generate AI Insights**
- **Deskripsi**: Service generate 7 AI-powered insights dari prediksi
- **Input**: Predictions array, Historical data array
- **Output**: 7 Insight Cards:
  1. **Trend Analysis**: Increasing/Decreasing/Stable (threshold 10%)
  2. **Risk Assessment**: Low/Medium/High dengan color coding
  3. **Volatility**: Coefficient of Variation (CV = σ/μ × 100%)
  4. **Anomaly Detection**: Outliers (>2 standard deviations)
  5. **Recommendations**: Policy suggestions berbasis trend & risk
  6. **Summary**: 1-2 kalimat ringkasan kondisi
  7. **Confidence Score**: Persentase confidence model
- **Service Class**: `App\Services\PredictionInsightService`

**FR-NBM-003: Visualisasi Prediksi (3 Charts)**
- **Deskripsi**: Tampilkan 3 chart interaktif menggunakan Chart.js
- **Charts**:
  1. **Line Chart**: Trend prediksi vs historical
  2. **Area Chart**: Confidence interval (shaded area)
  3. **Bar Chart**: Comparison last 6 months vs next N months
- **Library**: Chart.js 4.x
- **Responsive**: Auto-resize on window change

**FR-NBM-004: Save Prediction History**
- **Deskripsi**: User dapat simpan hasil prediksi ke database
- **Input**: Prediction data, User action (save/bookmark)
- **Output**: Record tersimpan di `prediction_histories` table
- **SQL**: 
  ```sql
  INSERT INTO prediction_histories (
    user_id, kode_kelompok, kode_komoditi, 
    prediction_months, predictions, insights, 
    is_bookmarked, created_at
  ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW());
  ```

**FR-NBM-005: Filter & View History**
- **Deskripsi**: User dapat filter dan view history prediksi
- **Filters**: Date range, Kelompok, Komoditi, Bookmark status
- **Sort**: Date DESC (terbaru pertama)
- **Pagination**: 10 records per page
- **SQL**: 
  ```sql
  SELECT ph.*, k.nama as kelompok_nama, ko.nama as komoditi_nama
  FROM prediction_histories ph
  JOIN kelompok k ON ph.kode_kelompok = k.kode
  JOIN komoditi ko ON ph.kode_komoditi = ko.kode_komoditi
  WHERE ph.user_id = ? 
    AND ph.created_at BETWEEN ? AND ?
    AND (? IS NULL OR ph.kode_kelompok = ?)
  ORDER BY ph.created_at DESC;
  ```

**FR-NBM-006: Export Prediction (Excel & PDF)**
- **Deskripsi**: User export hasil prediksi ke Excel atau PDF
- **Excel Format**: 2 sheets
  - Sheet 1: Prediction Results (bulan, prediksi, lower, upper)
  - Sheet 2: Historical Data (6 bulan terakhir)
- **PDF Format**: Formatted report dengan:
  - Header (logo, title, date)
  - Prediction table
  - 3 Charts (embedded as images)
  - AI Insights summary
  - Footer (generated by SIKOLBIA)
- **Classes**: `App\Exports\PrediksiNbmExport`, `Maatwebsite\Excel`, `Barryvdh\DomPDF`

#### 3.1.3 Modul Manajemen Data NBM (Admin)

**FR-NBM-ADMIN-001: CRUD Kelompok**
- **Deskripsi**: Admin manage master kelompok makanan
- **Operations**: Create, Read, Update, Delete
- **Fields**: kode (PK), nama, deskripsi, icon_class, color_class
- **Validation**: kode unique (2 digit), nama required
- **SQL**: 
  ```sql
  -- Create
  INSERT INTO kelompok (kode, nama, deskripsi, icon_class, color_class) 
  VALUES (?, ?, ?, ?, ?);
  
  -- Update
  UPDATE kelompok SET nama = ?, deskripsi = ? WHERE kode = ?;
  
  -- Delete (check foreign key)
  DELETE FROM kelompok WHERE kode = ? AND NOT EXISTS (
    SELECT 1 FROM transaksi_nbms WHERE kode_kelompok = ?
  );
  ```

**FR-NBM-ADMIN-002: CRUD Komoditi**
- **Deskripsi**: Admin manage master komoditi per kelompok
- **Fields**: kode_komoditi (PK), kode_kelompok (FK), nama, satuan, susut_percentage
- **Relasi**: belongsTo Kelompok
- **Validation**: Kombinasi kode_kelompok + kode_komoditi unique

**FR-NBM-ADMIN-003: CRUD Transaksi NBM**
- **Deskripsi**: Admin manage transaksi NBM (41,316 records)
- **Fields**: tahun, bulan, kode_kelompok, kode_komoditi, kalori_hari, status_angka, dll.
- **Import**: Support CSV import untuk bulk upload
- **Template**: Download template CSV dengan format standar

#### 3.1.4 Modul Lahan Pertanian

**FR-LAHAN-001: Filter Data Lahan**
- **Deskripsi**: User filter data lahan multi-dimensi
- **Filters**:
  - Tahun (dropdown)
  - Bulan (dropdown)
  - Wilayah (hierarchical: Provinsi → Kabupaten/Kota)
  - Topik (dropdown)
  - Variabel (dependent on Topik)
  - Klasifikasi (optional)
- **Output**: Filtered data grid (sortable, searchable)
- **SQL**: 
  ```sql
  SELECT ld.*, lv.nama_variabel, lk.nama_klasifikasi, w.nama_wilayah
  FROM lahan_data ld
  JOIN lahan_variabel lv ON ld.id_variabel = lv.id
  LEFT JOIN lahan_klasifikasi lk ON ld.id_klasifikasi = lk.id
  JOIN wilayah w ON ld.id_wilayah = w.id
  WHERE ld.tahun = ? 
    AND ld.id_bulan = ?
    AND ld.id_wilayah = ?
    AND lv.id_topik = ?;
  ```

**FR-LAHAN-002: Export Data Lahan**
- **Deskripsi**: User export filtered data ke Excel
- **Workflow**:
  1. Livewire set session dengan filter data
  2. Redirect ke download route
  3. Controller baca session, generate Excel
  4. Stream download ke browser
- **Class**: `App\Exports\PemerintahLahanExport`

**FR-LAHAN-003: CRUD Master Lahan (Admin Only)**
- **Operations**: Manage Topik, Variabel, Klasifikasi, Data
- **Permission**: `role:admin|superadmin`

#### 3.1.5 Modul Iklim & Optimalisasi DPI

**FR-IKLIM-001: Filter Data Iklim**
- **Filters**: Tahun, Wilayah, Topik (14 topik), Variabel, Klasifikasi
- **Topik Examples**: Curah Hujan, Suhu Udara, Kelembaban, Kecepatan Angin
- **Similar to Lahan Module**

**FR-IKLIM-002: Export Data Iklim**
- **Class**: `App\Exports\PemerintahIklimExport`

#### 3.1.6 Modul Benih & Pupuk

**FR-BENIH-001: Import CSV**
- **Deskripsi**: Admin upload CSV untuk bulk insert data benih pupuk
- **Validation**: Header validation, data type check
- **Process**: Laravel Excel import dengan chunk (1000 rows)

**FR-BENIH-002: Filter & Export**
- **Similar to Lahan & Iklim modules**
- **Class**: `App\Exports\PemerintahBenihPupukExport`

#### 3.1.7 Modul Daftar Alamat

**FR-ALAMAT-001: CRUD Daftar Alamat**
- **Fields**: nama_instansi, alamat, telepon, email, latitude, longitude, id_wilayah
- **Map Integration**: Leaflet.js untuk pin location

**FR-ALAMAT-002: Public Access**
- **Route**: `/ketersediaan/daftar-alamat`
- **No Auth Required**: Public can view alamat instansi pertanian

### 3.2 Kebutuhan Antarmuka

#### 3.2.1 User Interface

**Dashboard (Role-Based)**:
- **Superadmin/Admin**: 
  - Statistik cards (Total Users, Total Transaksi NBM, Latest Predictions)
  - Recent Activities log
  - Quick Actions (Add User, Import Data, View Reports)
- **Pemerintah**: 
  - 3 Module cards (Lahan, Benih Pupuk, Iklim)
  - Regional statistics untuk wilayah user
- **Akademisi**: 
  - Research-focused view
  - Download center untuk datasets

**Prediksi NBM Page**:
- Section 1: Filter Form (Kelompok, Komoditi, Bulan)
- Section 2: 3 Charts (responsive grid)
- Section 3: 7 AI Insight Cards (2-column grid)
- Section 4: Action Buttons (Save, Bookmark, Export Excel, Export PDF)

**Responsive Design**:
- Desktop: 1920x1080 (optimal)
- Tablet: 768x1024 (responsive grid)
- Mobile: 375x667 (stacked layout)

#### 3.2.2 API Interface (ML Service)

**Endpoint**: `POST http://localhost:8082/predict`

**Request**:
```json
{
  "data": [
    {
      "tahun": 2024,
      "bulan": 1,
      "kelompok": "01",
      "komoditi": "0101",
      "kalori_hari": 920.5
    },
    // ... 5 more months (total 6)
  ],
  "prediction_steps": 3
}
```

**Response**:
```json
{
  "success": true,
  "predictions": [935.2, 948.7, 962.1],
  "confidence_interval": {
    "lower": [925.8, 938.4, 951.9],
    "upper": [944.6, 959.0, 972.3]
  },
  "model_info": {
    "name": "NBM LSTM Model",
    "version": "1.0.0",
    "mape": 8.7
  },
  "input_summary": {
    "sequence_length": 6,
    "prediction_months": 3
  }
}
```

**Error Response** (Status 400):
```json
{
  "success": false,
  "error": "Insufficient data. Required 6 months, received 3."
}
```

#### 3.2.3 Database Interface

**DBMS**: MySQL 8.0  
**Connection**: PDO via Laravel Eloquent ORM  
**Charset**: utf8mb4 (support emoji & special characters)  
**Collation**: utf8mb4_unicode_ci  
**Engine**: InnoDB (support transactions & foreign keys)

**Key Tables** (38 total):
1. `users` - User accounts
2. `roles` & `permissions` - Spatie RBAC
3. `kelompok` (13 rows) - Kelompok makanan
4. `komoditi` (162 rows) - Komoditi per kelompok
5. `transaksi_nbms` (41,316 rows) - NBM data 1993-2024
6. `prediction_histories` - Saved predictions
7. `lahan_*` (4 tables) - Lahan module
8. `benih_pupuk_*` (4 tables) - Benih pupuk module
9. `iklimoptdpi_*` (4 tables) - Iklim module
10. `daftar_alamat` - Alamat instansi
11. `wilayah`, `master_provinsi`, `master_kabkota` - Master wilayah

---

## 4. KEBUTUHAN NON-FUNGSIONAL

### 4.1 Performance Requirements

| Metric | Target | Measurement |
|--------|--------|-------------|
| **Page Load Time** | < 2 detik | Google Lighthouse |
| **ML API Response Time** | < 1 detik | cURL benchmark |
| **Chart Render Time** | < 500 ms | Browser DevTools |
| **Excel Export** | < 5 detik (10k rows) | Stopwatch |
| **Concurrent Users** | 100 simultaneous | Load testing (Apache Bench) |
| **Database Query** | < 100 ms (95% queries) | Laravel Debugbar |

### 4.2 Reliability & Availability

| Aspect | Requirement |
|--------|-------------|
| **Uptime** | 99.5% (43.8 hours downtime/year) |
| **MTBF** | > 720 hours (30 days) |
| **MTTR** | < 1 hour |
| **Backup Frequency** | Daily automated MySQL dump |
| **Recovery Point Objective (RPO)** | 24 hours |
| **Recovery Time Objective (RTO)** | 4 hours |

### 4.3 Security Requirements

**NFR-SEC-001: Authentication**
- Password hashing: bcrypt (Laravel default)
- Session management: Redis-backed
- CSRF protection: Laravel token validation
- XSS prevention: Blade template escaping

**NFR-SEC-002: Authorization**
- RBAC: Spatie Laravel Permission
- Middleware protection: `role:`, `permission:`
- API authentication: Sanctum token (untuk mobile expansion)

**NFR-SEC-003: Data Protection**
- HTTPS: SSL/TLS certificate required
- SQL Injection: Eloquent ORM prepared statements
- File upload: Whitelist extension (.pdf, .csv, .xlsx)
- Environment vars: `.env` file (not in Git)

**NFR-SEC-004: Audit Trail**
- Log user actions: Login, CRUD operations, exports
- Laravel Log: Daily rotation (30 days retention)
- Backup logs: `backup_logs` table

### 4.4 Usability Requirements

**NFR-USA-001: Learnability**
- New user dapat navigasi sistem dalam < 15 menit
- Tooltips pada form fields yang kompleks
- Help documentation: Panduan PDF

**NFR-USA-002: Error Handling**
- User-friendly error messages (Bahasa Indonesia)
- Validation errors: Red highlight + pesan di bawah field
- API errors: Toast notification (success/error/warning)

**NFR-USA-003: Accessibility**
- WCAG 2.1 Level A compliance
- Keyboard navigation support
- Alt text untuk images
- Color contrast ratio ≥ 4.5:1

### 4.5 Scalability Requirements

**NFR-SCA-001: Horizontal Scaling**
- Support load balancer (NGINX)
- Session storage: Redis (shareable across instances)
- File storage: Cloud-ready (S3 compatible)

**NFR-SCA-002: Database Scaling**
- Support master-slave replication
- Query optimization: Index pada foreign keys
- Pagination: Chunk large datasets (max 100 rows/page)

### 4.6 Maintainability Requirements

**NFR-MAI-001: Code Quality**
- PSR-12 coding standard (Laravel Pint)
- PHPDoc blocks untuk functions
- Git commit messages: Conventional Commits

**NFR-MAI-002: Testing**
- Unit tests: 80% coverage (PHPUnit/Pest)
- Integration tests: ML API endpoints (Pytest)
- Manual testing: Complete before deployment

**NFR-MAI-003: Documentation**
- Code comments: Complex logic explained
- API documentation: Swagger/OpenAPI (FastAPI)
- Deployment guide: README.md updated

### 4.7 Portability Requirements

**NFR-POR-001: Platform Independence**
- OS: Windows, Linux, macOS (via Docker)
- Browser: Chrome 90+, Firefox 88+, Edge 90+, Safari 14+
- Mobile: Responsive design (future native app)

**NFR-POR-002: Database**
- Primary: MySQL 8.0
- Testing: SQLite (in-memory)
- Migration-ready: PostgreSQL (future expansion)

### 4.8 ML Model Requirements

**NFR-ML-001: Accuracy**
- MAPE < 10% (primary metric)
- RMSE: Lower is better (track monthly)
- R² Score > 0.80 (goodness of fit)

**NFR-ML-002: Training**
- Retrain frequency: Quarterly (every 3 months)
- Training data: Minimum 2 years historical
- Validation: 80/20 train-test split

**NFR-ML-003: Inference**
- Response time: < 1 second (average)
- Batch support: Up to 10 predictions simultaneously
- Error handling: Graceful fallback jika model unavailable

---

## 5. BATASAN DAN ASUMSI

### 5.1 Batasan Sistem

1. **Data Quality**: Sistem assume data NBM historis akurat & lengkap
2. **Internet Dependency**: Sistem require koneksi internet untuk ML API calls
3. **Browser Limitation**: Tidak support Internet Explorer
4. **File Size**: Max upload 10 MB per file (CSV/PDF)
5. **Prediction Horizon**: Maximum 6 bulan kedepan
6. **Concurrency**: Optimal untuk 100 concurrent users

### 5.2 Asumsi Teknis

1. **Infrastructure**: Docker & Docker Compose tersedia
2. **Database**: MySQL 8.0 dengan InnoDB engine
3. **ML Service**: FastAPI running pada port 8082
4. **Queue Worker**: Laravel queue worker aktif untuk background jobs
5. **Cron Jobs**: System cron tersedia untuk scheduled tasks

### 5.3 Asumsi Bisnis

1. **User Training**: Admin mendapat training sebelum production
2. **Data Maintenance**: Admin update data NBM minimal bulanan
3. **Support**: Technical support tersedia untuk bug fixes
4. **Backup**: IT team responsible untuk backup & disaster recovery

---

## LAMPIRAN A: ENTITY RELATIONSHIP DIAGRAM (ERD)

### Core Tables Relationship

```
users (1) ----< (N) prediction_histories
users (N) ----< (N) roles (via model_has_roles)
roles (N) ----< (N) permissions (via role_has_permissions)

kelompok (1) ----< (N) komoditi
kelompok (1) ----< (N) transaksi_nbms
komoditi (1) ----< (N) transaksi_nbms

lahan_topik (1) ----< (N) lahan_variabel
lahan_variabel (1) ----< (N) lahan_data
lahan_klasifikasi (1) ----< (N) lahan_data
wilayah (1) ----< (N) lahan_data
bulan (1) ----< (N) lahan_data

(Similar structure for benih_pupuk_* and iklimoptdpi_* modules)

master_provinsi (1) ----< (N) master_kabkota
master_kabkota (1) ----< (N) daftar_alamat
```

---

## LAMPIRAN B: USE CASE DIAGRAM

### Primary Actors & Use Cases

**Actor: Superadmin/Admin**
- UC-01: Login ke sistem
- UC-02: Manage users (CRUD)
- UC-03: Approve/Reject registrasi
- UC-04: Manage kelompok & komoditi (CRUD)
- UC-05: Import transaksi NBM (CSV)
- UC-06: Manage lahan data (CRUD)
- UC-07: Manage iklim data (CRUD)
- UC-08: Manage benih pupuk data (CRUD)
- UC-09: View system logs
- UC-10: Generate reports

**Actor: Pemerintah**
- UC-11: Login ke sistem
- UC-12: Prediksi NBM dengan LSTM
- UC-13: View & export data lahan (regional)
- UC-14: View & export data iklim (regional)
- UC-15: View & export data benih pupuk (regional)
- UC-16: Save prediction history
- UC-17: Export Excel/PDF

**Actor: Akademisi**
- UC-18: Login ke sistem
- UC-19: View data konsumsi pangan
- UC-20: View data lahan
- UC-21: View data iklim
- UC-22: View data benih pupuk
- UC-23: Download datasets untuk research
- UC-24: View daftar alamat instansi

**Actor: Public (Guest)**
- UC-25: View ketersediaan data NBM (summary)
- UC-26: View daftar alamat instansi pertanian
- UC-27: Registrasi akun baru

**Actor: ML Service (System)**
- UC-28: Receive prediction request
- UC-29: Load LSTM model
- UC-30: Process time series data
- UC-31: Return predictions dengan confidence interval

---

## LAMPIRAN C: DATA DICTIONARY

### Table: users

| Column | Type | Constraint | Description |
|--------|------|------------|-------------|
| id | BIGINT | PK, AUTO_INCREMENT | User ID |
| name | VARCHAR(255) | NOT NULL | Nama lengkap user |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Email untuk login |
| password | VARCHAR(255) | NOT NULL | Bcrypt hashed password |
| is_suspended | BOOLEAN | DEFAULT 0 | Status suspension |
| suspended_at | TIMESTAMP | NULL | Tanggal suspend |
| suspension_reason | TEXT | NULL | Alasan suspend |
| created_at | TIMESTAMP | NULL | Tanggal registrasi |
| updated_at | TIMESTAMP | NULL | Tanggal update terakhir |

### Table: kelompok

| Column | Type | Constraint | Description |
|--------|------|------------|-------------|
| kode | CHAR(2) | PK | Kode kelompok (01-13) |
| nama | VARCHAR(255) | NOT NULL | Nama kelompok (e.g., "Padi-Padian") |
| deskripsi | TEXT | NULL | Deskripsi kelompok |
| icon_class | VARCHAR(100) | NULL | CSS class untuk icon |
| color_class | VARCHAR(100) | NULL | CSS class untuk color |

### Table: komoditi

| Column | Type | Constraint | Description |
|--------|------|------------|-------------|
| kode_kelompok | CHAR(2) | FK → kelompok | Kelompok parent |
| kode_komoditi | CHAR(4) | PK | Kode komoditi (0101-1309) |
| nama | VARCHAR(255) | NOT NULL | Nama komoditi (e.g., "Beras") |
| satuan | VARCHAR(50) | NULL | Satuan (Kg, Liter, dll.) |
| susut_percentage | DECIMAL(5,2) | NULL | Persentase susut |

### Table: transaksi_nbms

| Column | Type | Constraint | Description |
|--------|------|------------|-------------|
| id | BIGINT | PK, AUTO_INCREMENT | Transaction ID |
| tahun | INT | NOT NULL, INDEX | Tahun data (1993-2024) |
| bulan | INT | NOT NULL, INDEX | Bulan (1-12) |
| kode_kelompok | CHAR(2) | FK → kelompok | Kelompok makanan |
| kode_komoditi | CHAR(4) | FK → komoditi | Komoditi |
| kalori_hari | DECIMAL(10,2) | NOT NULL | Kalori per hari |
| status_angka | VARCHAR(50) | NULL | Status data |
| created_at | TIMESTAMP | NULL | Timestamp insert |

### Table: prediction_histories

| Column | Type | Constraint | Description |
|--------|------|------------|-------------|
| id | BIGINT | PK, AUTO_INCREMENT | History ID |
| user_id | BIGINT | FK → users | User yang predict |
| kode_kelompok | CHAR(2) | FK → kelompok | Kelompok diprediksi |
| kode_komoditi | CHAR(4) | FK → komoditi | Komoditi diprediksi |
| prediction_months | INT | NOT NULL | Jumlah bulan prediksi |
| predictions | JSON | NOT NULL | Array hasil prediksi |
| insights | JSON | NULL | AI insights data |
| is_bookmarked | BOOLEAN | DEFAULT 0 | Status bookmark |
| created_at | TIMESTAMP | NULL | Tanggal prediksi |

---

## LAMPIRAN D: STATE TRANSITION DIAGRAM

### Registration State Flow

```
[New User] 
    ↓ (submit registration)
[Pending Approval]
    ↓ (admin review)
    ├─→ [Approved] → [Active User] → Can Login
    └─→ [Rejected] → [Can Resubmit] → [Pending Approval]
    
[Active User]
    ↓ (admin action)
[Suspended] → Cannot Login
    ↓ (admin unsuspend)
[Active User]
```

### Prediction State Flow

```
[User Dashboard]
    ↓ (navigate to Prediksi NBM)
[Select Filters] (Kelompok, Komoditi, Bulan)
    ↓ (click "Prediksi")
[Loading] (send to ML API)
    ├─→ [Success] → Display 3 Charts + 7 AI Insights
    │       ↓
    │   [Actions Available]
    │       ├─→ [Save to History]
    │       ├─→ [Bookmark]
    │       ├─→ [Export Excel]
    │       └─→ [Export PDF]
    └─→ [Error] → Show error message + Retry option
```

---

## PERSETUJUAN DOKUMEN

**Disusun oleh:**  
Jehian Athaya Az Dzikri  
Mahasiswa Informatika  

**Tanggal**: 15 November 2025

**Disetujui oleh:**  
[Nama Dosen Pembimbing]  
Dosen Pembimbing  

**Tanggal**: ________________

---

**CATATAN**: Dokumen ini adalah living document dan dapat diupdate sesuai perkembangan sistem. Setiap perubahan harus melalui approval formal.

---

*Dokumen SRS SIKOLBIA v1.0 - Confidential*
