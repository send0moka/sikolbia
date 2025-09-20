# 3. SYSTEM FEATURES

## 3.1 User Authentication and Authorization

### 3.1.1 Description and Priority
**Priority**: High  
Sistem keamanan berbasis role yang mengontrol akses pengguna ke berbagai fitur aplikasi. Menggunakan Laravel's built-in authentication dengan Spatie Laravel Permission untuk role management.

### 3.1.2 Stimulus/Response Sequences
1. **Login Process**:
   - User mengakses halaman login
   - System menampilkan form authentication
   - User memasukkan credentials
   - System memvalidasi credentials
   - System redirect ke dashboard sesuai role

2. **Permission Check**:
   - User mengakses protected resource
   - System mengecek user permissions
   - System grant/deny access berdasarkan role

### 3.1.3 Functional Requirements

#### REQ-AUTH-001: User Login
**Description**: User harus dapat login dengan email dan password  
**Input**: Email dan password yang valid  
**Processing**: Validasi credentials terhadap database  
**Output**: Access token dan redirect ke dashboard  
**Priority**: High  

#### REQ-AUTH-002: Role-Based Access Control
**Description**: Sistem harus mengimplementasikan RBAC  
**Roles**:
- **Super Admin**: Full system access
  - User management (create, edit, delete users)
  - Role dan permission management
  - System configuration
  - All data access
- **Admin**: Data management access
  - View dan manage kelompok, komoditi, transaksi NBM
  - Generate reports dan exports
  - Access prediction features
  - Limited user management (view only)
- **Public**: Limited read-only access
  - View public dashboards
  - Access public reports
  - Download public datasets

#### REQ-AUTH-003: Session Management
**Description**: Sistem harus mengelola user sessions dengan aman  
**Features**:
- Session timeout after 2 hours of inactivity
- Secure session storage menggunakan Redis
- Remember me functionality
- Concurrent session control

#### REQ-AUTH-004: Password Security
**Description**: Implementasi security practices untuk password  
**Requirements**:
- Password hashing menggunakan bcrypt
- Minimum password length: 8 characters
- Password reset via email
- Account lockout after 5 failed attempts

## 3.2 Data Management - Kelompok Pangan

### 3.2.1 Description and Priority
**Priority**: High  
Manajemen master data kelompok pangan (food groups) yang menjadi basis klasifikasi komoditi.

### 3.2.2 Functional Requirements

#### REQ-KELOMPOK-001: CRUD Operations
**Description**: Admin dapat melakukan operasi CRUD untuk kelompok pangan  
**Operations**:
- **Create**: Tambah kelompok baru dengan kode dan nama
- **Read**: View daftar kelompok dengan pagination dan search
- **Update**: Edit informasi kelompok existing
- **Delete**: Hapus kelompok (dengan validasi dependency)

#### REQ-KELOMPOK-002: Data Validation
**Description**: Validasi data input untuk kelompok  
**Rules**:
- Kode kelompok: Required, unique, format numeric (01-99)
- Nama kelompok: Required, string, max 255 characters
- Deskripsi: Optional, text field
- Status aktif: Boolean field

#### REQ-KELOMPOK-003: Relationship Management
**Description**: Kelompok memiliki relasi one-to-many dengan komoditi  
**Constraints**:
- Kelompok tidak dapat dihapus jika memiliki komoditi aktif
- Cascade update untuk perubahan kode kelompok
- Integrity check sebelum delete operations

## 3.3 Data Management - Komoditi Pangan

### 3.3.1 Description and Priority
**Priority**: High  
Manajemen data komoditi pangan yang merupakan item-item spesifik dalam setiap kelompok.

### 3.3.2 Functional Requirements

#### REQ-KOMODITI-001: CRUD Operations
**Description**: Admin dapat mengelola data komoditi pangan  
**Features**:
- Tambah komoditi baru dengan assignment ke kelompok
- Edit informasi komoditi
- Delete komoditi dengan dependency check
- Bulk operations untuk efficiency

#### REQ-KOMODITI-002: Hierarchical Structure
**Description**: Komoditi mengikuti struktur hierarkis kelompok  
**Implementation**:
- Kode komoditi: Kombinasi kode_kelompok + kode_komoditi (contoh: 0101)
- Automatic code generation berdasarkan kelompok
- Parent-child relationship validation

#### REQ-KOMODITI-003: Search and Filter
**Description**: Kemampuan pencarian dan filter komoditi  
**Features**:
- Search by nama komoditi
- Filter by kelompok
- Filter by status aktif
- Advanced filter dengan multiple criteria

## 3.4 Data Management - Transaksi NBM

### 3.4.1 Description and Priority
**Priority**: Critical  
Core functionality untuk mengelola data transaksi Neraca Bahan Makanan (NBM).

### 3.4.2 Functional Requirements

#### REQ-NBM-001: Transaction CRUD
**Description**: Manajemen lengkap data transaksi NBM  
**Data Fields**:
- Identifiers: kode_kelompok, kode_komoditi, tahun, bulan
- Status: status_angka (tetap/sementara/sangat sementara)
- Supply Data: masukan, keluaran, impor, ekspor, perubahan_stok
- Usage Data: pakan, bibit, makanan, bukan_makanan, tercecer
- Consumption Metrics: kg_tahun, gram_hari, kalori_hari, protein_hari, lemak_hari
- Economic Data: harga_produsen, harga_konsumen, inflasi_komoditi
- External Factors: curah_hujan, suhu_rata, indeks_el_nino

#### REQ-NBM-002: Data Validation
**Description**: Comprehensive validation untuk data NBM  
**Validation Rules**:
- Required fields: kelompok, komoditi, tahun, kalori_hari
- Numeric validation dengan reasonable ranges
- Date validation untuk tahun dan bulan
- Cross-field validation (consistency checks)
- Duplicate prevention berdasarkan composite key

#### REQ-NBM-003: Import/Export Functionality
**Description**: Bulk data operations untuk efficiency  
**Import Features**:
- Excel file upload dengan template validation
- CSV import dengan delimiter detection
- Data mapping dan transformation
- Error reporting dengan line-by-line details
- Preview mode sebelum final import

**Export Features**:
- Excel export dengan formatting
- CSV export untuk data analysis
- PDF export untuk reporting
- Custom date range selection
- Filter-based export

#### REQ-NBM-004: Data Quality Management
**Description**: Ensuring data quality dan consistency  
**Features**:
- Outlier detection dan flagging
- Data completeness monitoring
- Consistency checks across related records
- Audit trail untuk data changes
- Data source tracking

## 3.5 Machine Learning Prediction System

### 3.5.1 Description and Priority
**Priority**: High  
Advanced machine learning system untuk prediksi konsumsi pangan dengan akurasi tinggi.

### 3.5.2 Functional Requirements

#### REQ-ML-001: NBM Prediction Engine
**Description**: Core prediction functionality menggunakan LSTM model  
**Specifications**:
- Model Type: HuberRegressor Ensemble dengan LSTM preprocessing
- Accuracy Target: MAPE < 10% (Current: 8.88%)
- Input Features: 6 months historical data (kelompok, komoditi, kalori_hari)
- Output: Predicted kalori_hari untuk bulan berikutnya
- Confidence Interval: 95% confidence bounds

#### REQ-ML-002: Prediction API
**Description**: RESTful API untuk prediction services  
**Endpoints**:
- `POST /predict`: Single prediction
- `POST /predict/batch`: Batch predictions
- `GET /model/info`: Model metadata
- `GET /model/stats`: Performance statistics
- `GET /health`: API health check

#### REQ-ML-003: Model Management
**Description**: Production model management dan monitoring  
**Features**:
- Model versioning dan rollback capability
- Performance monitoring dan alerting
- Automated model retraining scheduling
- A/B testing untuk model comparison
- Model explainability dan interpretability

#### REQ-ML-004: Data Preprocessing
**Description**: Automated data preparation untuk model input  
**Processing Steps**:
- Data cleaning dan outlier handling
- Feature engineering dan transformation
- Sequence preparation untuk LSTM
- Normalization dan scaling
- Missing value imputation

#### REQ-ML-005: Prediction Interface
**Description**: User-friendly interface untuk prediction tasks  
**Features**:
- Interactive form untuk manual input
- File upload untuk batch predictions
- Real-time prediction display
- Historical prediction tracking
- Prediction confidence visualization

## 3.6 Dashboard and Reporting

### 3.6.1 Description and Priority
**Priority**: High  
Comprehensive dashboard dan reporting system untuk data visualization dan analysis.

### 3.6.2 Functional Requirements

#### REQ-DASH-001: Main Dashboard
**Description**: Overview dashboard dengan key metrics  
**Components**:
- Summary statistics cards
- Trend charts untuk konsumsi patterns
- Geographic distribution maps
- Recent activity timeline
- Quick action buttons

#### REQ-DASH-002: Laporan NBM
**Description**: Detailed NBM reporting dengan filter options  
**Features**:
- Interactive data tables
- Dynamic filtering (tahun, kelompok, komoditi)
- Export capabilities
- Drill-down functionality
- Comparative analysis tools

#### REQ-DASH-003: Laporan SUSENAS
**Description**: SUSENAS data reporting dan analysis  
**Features**:
- Multi-dimensional data views
- Regional comparison charts
- Time series analysis
- Statistical summaries
- Custom report generation

#### REQ-DASH-004: Data Visualization
**Description**: Advanced charting dan visualization tools  
**Chart Types**:
- Line charts untuk trend analysis
- Bar charts untuk comparisons
- Pie charts untuk composition analysis
- Heat maps untuk correlation analysis
- Geographic maps untuk spatial data

## 3.7 Export and Reporting System

### 3.7.1 Description and Priority
**Priority**: Medium  
Flexible export system untuk various output formats dan use cases.

### 3.7.2 Functional Requirements

#### REQ-EXPORT-001: Multiple Format Support
**Description**: Export data dalam berbagai format  
**Supported Formats**:
- Excel (.xlsx) dengan formatting dan charts
- CSV untuk data analysis tools
- PDF untuk official reports
- JSON untuk API integration

#### REQ-EXPORT-002: Custom Export Configuration
**Description**: Configurable export parameters  
**Options**:
- Date range selection
- Field selection (custom columns)
- Filter application
- Sort order specification
- Template customization

#### REQ-EXPORT-003: Scheduled Reports
**Description**: Automated report generation dan delivery  
**Features**:
- Schedule configuration (daily, weekly, monthly)
- Email delivery dengan attachments
- FTP/SFTP upload options
- Report archive management
- Delivery confirmation tracking

## 3.8 Agricultural Data Management

### 3.8.1 Description and Priority
**Priority**: Medium  
Extended functionality untuk agricultural data domains.

### 3.8.2 Functional Requirements

#### REQ-AGRI-001: Benih & Pupuk Management
**Description**: Comprehensive seed dan fertilizer data management  
**Features**:
- Multi-dimensional data structure (topik, variabel, klasifikasi)
- Regional data dengan province/regency breakdown
- Time series data dengan monthly granularity
- Integration dengan existing NBM data

#### REQ-AGRI-002: Iklim OPT-DPI Management
**Description**: Climate dan pest/disease data management  
**Features**:
- Weather data integration
- Pest outbreak tracking
- Disease incidence monitoring
- Correlation analysis dengan NBM data

#### REQ-AGRI-003: Lahan Management
**Description**: Land use dan agricultural land data  
**Features**:
- Land classification dan categorization
- Usage pattern tracking
- Productivity analysis
- Geographic mapping integration

## 3.9 Geographic Information System

### 3.9.1 Description and Priority
**Priority**: Medium  
GIS capabilities untuk spatial data analysis dan visualization.

### 3.9.2 Functional Requirements

#### REQ-GIS-001: Map Integration
**Description**: Interactive map displays dengan data overlay  
**Features**:
- Base map dengan multiple providers
- Data layer overlays
- Interactive markers dan popups
- Zoom dan pan functionality
- Export map images

#### REQ-GIS-002: Spatial Analysis
**Description**: Geographic analysis tools  
**Features**:
- Regional aggregation
- Spatial correlation analysis
- Distance calculations
- Boundary management
- Coordinate system support

#### REQ-GIS-003: Location Services
**Description**: Location-based data services  
**Features**:
- Address geocoding
- Reverse geocoding
- Proximity searches
- Administrative boundary lookups
- GPS coordinate handling