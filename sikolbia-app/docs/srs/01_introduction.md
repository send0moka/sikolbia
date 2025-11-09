# Software Requirements Specification (SRS)
## Sistem Basis Data Konsumsi Pangan dengan Prediksi NBM

**Version:** 1.0  
**Date:** September 20, 2025  
**Prepared by:** System Analyst Team  
**Organization:** Basis Data Konsumsi Pangan Project  

---

## 1. INTRODUCTION

### 1.1 Purpose
Dokumen Software Requirements Specification (SRS) ini menjelaskan secara detail kebutuhan fungsional dan non-fungsional untuk Sistem Basis Data Konsumsi Pangan dengan fitur prediksi Nilai Belanja Makanan (NBM) menggunakan machine learning. Sistem ini dikembangkan untuk mendukung analisis dan prediksi konsumsi pangan di Indonesia.

### 1.2 Scope
Sistem ini adalah aplikasi web berbasis Laravel dengan teknologi Livewire untuk frontend yang responsif dan FastAPI untuk layanan machine learning. Sistem mencakup:

- **Manajemen Data Konsumsi Pangan**: CRUD lengkap untuk kelompok, komoditi, dan transaksi NBM
- **Dashboard Interaktif**: Visualisasi data dengan grafik dan statistik real-time
- **Export Data**: Kemampuan export ke format Excel untuk berbagai entitas
- **Sistem Role & Permission**: Kontrol akses berbasis peran pengguna
- **Prediksi NBM**: Model machine learning dengan akurasi tinggi (MAPE 8.88%)
- **API ML**: FastAPI server untuk prediksi real-time dan batch processing
- **Multi-Domain Coverage**: Data pertanian (Benih & Pupuk, Iklim OPT-DPI, Lahan)
- **Geospatial Features**: Integrasi dengan data wilayah dan pemetaan

### 1.3 Definitions, Acronyms, and Abbreviations

#### Definitions
- **NBM (Nilai Belanja Makanan)**: Nilai belanja makanan per kapita yang digunakan sebagai indikator konsumsi pangan
- **SUSENAS**: Survei Sosial Ekonomi Nasional oleh BPS
- **BPS**: Badan Pusat Statistik Indonesia
- **Komoditi**: Jenis barang atau produk pangan tertentu
- **Kelompok**: Kategori atau klasifikasi komoditi pangan

#### Acronyms
- **SRS**: Software Requirements Specification
- **CRUD**: Create, Read, Update, Delete
- **API**: Application Programming Interface
- **ML**: Machine Learning
- **MAPE**: Mean Absolute Percentage Error
- **LSTM**: Long Short-Term Memory (Neural Network)
- **UI/UX**: User Interface/User Experience
- **RBAC**: Role-Based Access Control

#### Abbreviations
- **Laravel**: PHP Web Framework
- **Livewire**: Full-stack framework for Laravel
- **FastAPI**: Modern Python web framework
- **MySQL**: Relational Database Management System
- **Docker**: Containerization platform
- **Redis**: In-memory data structure store

### 1.4 References
1. Laravel 12.x Documentation - https://laravel.com/docs
2. Livewire 3.x Documentation - https://livewire.laravel.com
3. FastAPI Documentation - https://fastapi.tiangolo.com
4. BPS Indonesia Data Standards
5. IEEE Std 830-1998: Recommended Practice for Software Requirements Specifications

### 1.5 Overview
Dokumen SRS ini terdiri dari beberapa bagian utama:

1. **Introduction** - Tujuan, ruang lingkup, dan definisi
2. **Overall Description** - Perspektif produk, fungsi, dan karakteristik pengguna
3. **System Features** - Detail fitur dan requirement fungsional
4. **External Interface Requirements** - Antarmuka pengguna, hardware, software, dan komunikasi
5. **Other Nonfunctional Requirements** - Performance, security, dan reliability
6. **Database Design** - Struktur database dan relasi
7. **API Specification** - Dokumentasi endpoint dan protokol
8. **Deployment Architecture** - Arsitektur sistem dan deployment

---

## 2. OVERALL DESCRIPTION

### 2.1 Product Perspective
Sistem Basis Data Konsumsi Pangan adalah sistem informasi terintegrasi yang menggabungkan:

#### 2.1.1 System Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   ML Service   │
│   (Livewire)    │◄──►│   (Laravel)     │◄──►│   (FastAPI)     │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Web Browser   │    │     MySQL       │    │   ML Models     │
│   (Chrome/FF)   │    │   Database      │    │   (Scikit-learn)│
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

#### 2.1.2 System Context
- **Internal System**: Database konsumsi pangan internal organisasi
- **External Data Sources**: Data BPS, data cuaca, data ekonomi
- **User Access**: Web browser untuk akses multi-platform
- **Export Integration**: Excel, CSV, PDF untuk reporting

### 2.2 Product Functions
Sistem menyediakan fungsi-fungsi utama:

#### 2.2.1 Data Management Functions
- Manajemen Kelompok Pangan (Food Groups)
- Manajemen Komoditi Pangan (Food Commodities)
- Manajemen Transaksi NBM (Food Transaction Data)
- Manajemen Data SUSENAS
- Manajemen Data Pertanian (Benih & Pupuk, Iklim, Lahan)
- Manajemen Master Data (Wilayah, Bulan, Klasifikasi)

#### 2.2.2 Analytics & Reporting Functions
- Dashboard Konsumsi Pangan
- Laporan NBM (Neraca Bahan Makanan)
- Laporan SUSENAS
- Export Data ke Multiple Format
- Visualisasi Grafik dan Chart
- Analisis Tren Konsumsi

#### 2.2.3 Machine Learning Functions
- Prediksi NBM menggunakan LSTM Model
- Batch Prediction untuk dataset besar
- Model Performance Monitoring
- Confidence Interval Calculation
- Real-time Prediction API

#### 2.2.4 User Management Functions
- Role-based Access Control (RBAC)
- User Authentication & Authorization
- Permission Management
- Profile Management
- Session Management

### 2.3 User Classes and Characteristics

#### 2.3.1 Super Administrator
- **Characteristics**: Technical expert dengan full system access
- **Responsibilities**: 
  - Manajemen pengguna dan role
  - Konfigurasi sistem
  - Backup dan maintenance
  - Monitoring performance
- **Technical Expertise**: Advanced
- **Frequency of Use**: Daily

#### 2.3.2 Administrator
- **Characteristics**: Data analyst dengan domain knowledge
- **Responsibilities**:
  - Input dan validasi data
  - Generate reports
  - Monitor data quality
  - Manage data relationships
- **Technical Expertise**: Intermediate
- **Frequency of Use**: Daily

#### 2.3.3 Public User
- **Characteristics**: General public atau researcher
- **Responsibilities**:
  - View public reports
  - Download public datasets
  - Access dashboard
- **Technical Expertise**: Basic
- **Frequency of Use**: Occasional

### 2.4 Operating Environment

#### 2.4.1 Software Environment
- **Server OS**: Linux Ubuntu 20.04+ / CentOS 8+ / Windows Server 2019+
- **Web Server**: Nginx 1.18+ atau Apache 2.4+
- **Database**: MySQL 8.0+ atau MariaDB 10.5+
- **PHP**: Version 8.2+
- **Python**: Version 3.9+ untuk ML services
- **Node.js**: Version 18+ untuk asset compilation

#### 2.4.2 Hardware Environment
- **Minimum Server Requirements**:
  - CPU: 4 cores @ 2.4GHz
  - RAM: 8GB
  - Storage: 500GB SSD
  - Network: 100Mbps
- **Recommended Server Requirements**:
  - CPU: 8 cores @ 3.0GHz
  - RAM: 16GB
  - Storage: 1TB NVMe SSD
  - Network: 1Gbps

#### 2.4.3 Client Environment
- **Supported Browsers**:
  - Google Chrome 90+
  - Mozilla Firefox 88+
  - Microsoft Edge 90+
  - Safari 14+ (macOS)
- **Screen Resolution**: Minimum 1024x768, Optimized for 1920x1080
- **Internet Connection**: Minimum 1Mbps untuk optimal experience

### 2.5 Design and Implementation Constraints

#### 2.5.1 Regulatory Constraints
- Compliance dengan standar data BPS Indonesia
- Kepatuhan terhadap regulasi perlindungan data
- Standar keamanan informasi nasional

#### 2.5.2 Hardware Limitations
- Database size maksimum: 100GB untuk optimal performance
- Concurrent users: Maximum 500 simultaneous users
- File upload limit: 50MB per file

#### 2.5.3 Technology Constraints
- Laravel Framework untuk consistency dengan existing ecosystem
- MySQL untuk database compatibility
- Docker untuk containerization dan deployment
- Web-based application untuk cross-platform accessibility

#### 2.5.4 Performance Constraints
- Response time maksimum: 3 detik untuk page load
- API response time: < 500ms untuk standard endpoints
- ML prediction response: < 2 detik untuk single prediction
- Database query time: < 1 detik untuk standard operations

### 2.6 User Documentation
- **User Manual**: Comprehensive guide untuk setiap user role
- **API Documentation**: Detailed API reference dengan examples
- **Installation Guide**: Step-by-step deployment instructions
- **Training Materials**: Video tutorials dan hands-on workshops
- **FAQ**: Common questions dan troubleshooting guide

### 2.7 Assumptions and Dependencies

#### 2.7.1 Assumptions
- Pengguna memiliki basic computer literacy
- Internet connection tersedia untuk akses sistem
- Data input akan mengikuti format yang telah ditentukan
- Pengguna akan menggunakan supported browsers
- Server infrastructure akan properly maintained

#### 2.7.2 Dependencies
- **External APIs**: Data sources dari BPS dan institusi terkait
- **Third-party Libraries**: Laravel, Livewire, FastAPI frameworks
- **Infrastructure**: Cloud hosting atau on-premise servers
- **Data Sources**: Availability dan quality dari data konsumsi pangan
- **Support Team**: Technical support untuk maintenance dan updates