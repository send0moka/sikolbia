# 4. EXTERNAL INTERFACE REQUIREMENTS

## 4.1 User Interfaces

### 4.1.1 General UI Requirements

#### REQ-UI-001: Responsive Design
**Description**: Aplikasi harus responsive dan dapat diakses dari berbagai devices  
**Requirements**:
- Mobile-first design approach
- Support untuk screen sizes: 320px - 1920px
- Touch-friendly interface untuk mobile devices
- Adaptive layout untuk tablet dan desktop
- Cross-browser compatibility

#### REQ-UI-002: Accessibility
**Description**: Interface harus accessible untuk users dengan disabilities  
**Standards**: WCAG 2.1 Level AA compliance  
**Features**:
- Screen reader compatibility
- Keyboard navigation support
- High contrast mode option
- Font size adjustment
- Alternative text untuk images

#### REQ-UI-003: User Experience
**Description**: Intuitive dan user-friendly interface design  
**Principles**:
- Consistent navigation structure
- Clear visual hierarchy
- Minimal cognitive load
- Fast loading times
- Error prevention dan recovery

### 4.1.2 Specific Interface Requirements

#### REQ-UI-004: Login Interface
**Layout Requirements**:
- Centered login form
- Organization branding/logo
- Secure HTTPS indication
- Remember me option
- Forgot password link
- Clear error messaging

**Validation**:
- Real-time field validation
- Password strength indicator
- CAPTCHA untuk security (after failed attempts)

#### REQ-UI-005: Dashboard Interface
**Super Admin Dashboard**:
- System overview metrics
- User activity monitoring
- System health indicators
- Quick action shortcuts
- Recent activity feed

**Admin Dashboard**:
- Data summary cards
- Trend visualization charts
- Quick data entry forms
- Export shortcuts
- Notification center

**Public Dashboard**:
- Public data visualizations
- Download links
- Search functionality
- Filter options
- Contact information

#### REQ-UI-006: Data Management Interfaces
**Data Tables**:
- Sortable columns
- Paginated results
- Search/filter toolbar
- Bulk action checkboxes
- Export buttons
- Row selection indicators

**Forms**:
- Clear field labels
- Input validation feedback
- Required field indicators
- Save/cancel buttons
- Auto-save functionality
- Progress indicators

#### REQ-UI-007: Prediction Interface
**Input Form**:
- Date range picker
- Dropdown selections (kelompok, komoditi)
- Numeric input fields dengan validation
- File upload area
- Preview data table
- Submit/clear buttons

**Results Display**:
- Prediction value dengan confidence interval
- Historical data comparison chart
- Model performance metrics
- Download prediction report
- Save prediction results

### 4.1.3 Navigation and Layout

#### REQ-UI-008: Navigation Structure
**Main Navigation**:
```
├── Dashboard
├── Ketersediaan Pangan
│   ├── Konsep & Metode
│   ├── Laporan NBM
│   ├── Dashboard Komoditas
│   └── Konsep Transaksi NBM
├── Konsumsi Pangan
│   ├── Konsep & Metode
│   ├── Laporan SUSENAS
│   ├── Per Kapita Seminggu
│   ├── Per Kapita Setahun
│   └── Konsep Transaksi SUSENAS
├── Admin Panel (Role-based)
│   ├── Data Management
│   │   ├── Kelompok
│   │   ├── Komoditi
│   │   ├── Transaksi NBM
│   │   └── User Management
│   ├── Pertanian
│   │   ├── Benih & Pupuk
│   │   ├── Iklim OPT-DPI
│   │   ├── Lahan
│   │   └── Daftar Alamat
│   └── Prediksi NBM
└── Settings
    ├── Profile
    ├── Password
    └── Appearance
```

#### REQ-UI-009: Breadcrumb Navigation
- Hierarchical page location indicators
- Clickable breadcrumb links
- Current page highlighting
- Mobile-friendly collapsed view

#### REQ-UI-010: Footer Information
- Copyright information
- Contact details
- System version
- Last updated timestamp
- Privacy policy link

## 4.2 Hardware Interfaces

### 4.2.1 Server Hardware Requirements

#### REQ-HW-001: Production Server Specifications
**Minimum Requirements**:
- CPU: Intel Xeon atau AMD EPYC, 4 cores @ 2.4GHz
- RAM: 8GB DDR4
- Storage: 500GB SSD, 10,000 IOPS
- Network: 1Gbps Ethernet
- GPU: Not required (CPU-based ML inference)

**Recommended Requirements**:
- CPU: Intel Xeon atau AMD EPYC, 8 cores @ 3.0GHz  
- RAM: 16GB DDR4
- Storage: 1TB NVMe SSD, 50,000 IOPS
- Network: 10Gbps Ethernet
- Backup: RAID 1 configuration

#### REQ-HW-002: Development Environment
**Minimum Specifications**:
- CPU: 4 cores @ 2.0GHz
- RAM: 8GB
- Storage: 256GB SSD
- Network: 100Mbps

#### REQ-HW-003: Load Balancer (if required)
- Support untuk HTTP/HTTPS load balancing
- SSL termination capability
- Health check monitoring
- Session affinity support

### 4.2.2 Client Hardware Requirements

#### REQ-HW-004: End User Devices
**Desktop/Laptop**:
- CPU: Dual-core @ 1.5GHz minimum
- RAM: 4GB minimum, 8GB recommended
- Storage: 1GB available space for cache
- Display: 1024x768 minimum, 1920x1080 recommended
- Network: Broadband internet connection

**Mobile Devices**:
- iOS 12+ atau Android 8.0+
- RAM: 2GB minimum
- Storage: 500MB available space
- Network: 3G/4G/5G atau WiFi connection

## 4.3 Software Interfaces

### 4.3.1 Operating System Interfaces

#### REQ-SW-001: Server Operating System
**Supported OS**:
- Ubuntu 20.04 LTS atau newer
- CentOS 8+ atau RHEL 8+
- Windows Server 2019/2022
- Docker containerized deployment (preferred)

**System Services**:
- Systemd untuk service management (Linux)
- Windows Service untuk background tasks (Windows)
- Cron jobs untuk scheduled tasks
- Log rotation dan management

#### REQ-SW-002: Web Server Integration
**Supported Web Servers**:
- **Nginx 1.18+** (recommended)
  - FastCGI interface dengan PHP-FPM
  - SSL/TLS termination
  - Static file serving
  - Reverse proxy untuk FastAPI

- **Apache HTTP Server 2.4+**
  - mod_php atau mod_fcgid
  - SSL module support
  - Rewrite module untuk clean URLs

### 4.3.2 Database Interfaces

#### REQ-SW-003: Primary Database
**MySQL 8.0+ / MariaDB 10.5+**:
- InnoDB storage engine
- UTF-8 character set support
- ACID compliance
- Foreign key constraints
- Stored procedures support
- Backup dan restore capabilities

**Connection Requirements**:
- Connection pooling untuk performance
- SSL encryption untuk data in transit
- Read/write splitting capability
- Replication support untuk high availability

#### REQ-SW-004: Cache Layer
**Redis 6.0+**:
- Session storage
- Query result caching
- Rate limiting data
- Real-time data temporary storage
- Pub/Sub messaging untuk notifications

### 4.3.3 External API Interfaces

#### REQ-SW-005: BPS Data Integration
**Interface Type**: REST API integration  
**Data Sources**:
- SUSENAS survey data
- Population statistics
- Economic indicators
- Regional administrative data

**Authentication**: API key-based authentication  
**Data Format**: JSON  
**Update Frequency**: Monthly untuk SUSENAS, quarterly untuk statistics  

#### REQ-SW-006: Weather Data Integration
**Interface Type**: HTTP/REST API  
**Data Sources**:
- BMKG (Indonesian Meteorological Agency)
- OpenWeatherMap API (backup)
- Historical weather data

**Data Elements**:
- Temperature (daily average)
- Rainfall (monthly totals)
- Humidity levels
- El Niño index data

#### REQ-SW-007: Economic Data Integration
**Data Sources**:
- Bank Indonesia exchange rates
- Ministry of Trade commodity prices
- Statistics Indonesia inflation data

**Update Mechanism**:
- Automated daily/weekly updates
- Manual override capability
- Data validation dan quality checks

### 4.3.4 Machine Learning Framework Interfaces

#### REQ-SW-008: Python ML Stack
**Required Libraries**:
- **Scikit-learn 1.3+**: Core machine learning algorithms
- **Pandas 2.0+**: Data manipulation dan analysis
- **NumPy 1.24+**: Numerical computing
- **Joblib 1.3+**: Model serialization
- **FastAPI 0.100+**: ML API server

**Model Interfaces**:
- Model loading/saving via Joblib
- Prediction input validation via Pydantic
- Performance monitoring integration
- Model versioning support

### 4.3.5 File System Interfaces

#### REQ-SW-009: File Storage Requirements
**Local Storage**:
- Upload directory dengan proper permissions
- Temporary file management
- Log file rotation
- Export file caching

**Cloud Storage (optional)**:
- AWS S3 compatibility
- File backup dan archival
- CDN integration untuk static assets

## 4.4 Communications Interfaces

### 4.4.1 Network Protocols

#### REQ-COMM-001: HTTP/HTTPS Protocol
**Web Application**:
- HTTP/1.1 dan HTTP/2 support
- HTTPS enforcement untuk production
- SSL/TLS 1.2+ encryption
- HSTS (HTTP Strict Transport Security)
- Content compression (gzip/brotli)

#### REQ-COMM-002: API Communication
**RESTful APIs**:
- JSON data format
- HTTP status codes untuk response handling
- Rate limiting implementation
- API versioning support
- CORS configuration untuk cross-origin requests

**FastAPI ML Service**:
- JSON request/response format
- OpenAPI (Swagger) documentation
- Async request handling
- WebSocket support untuk real-time predictions

### 4.4.2 Database Communication

#### REQ-COMM-003: Database Protocols
**MySQL Connection**:
- MySQL protocol over TCP/IP
- SSL encryption untuk production
- Connection pooling untuk efficiency
- Prepared statements untuk security

**Redis Communication**:
- Redis protocol over TCP
- Connection multiplexing
- Pub/Sub messaging support

### 4.4.3 Email Communication

#### REQ-COMM-004: Email Services
**SMTP Configuration**:
- Configurable SMTP server settings
- Authentication support (SMTP-AUTH)
- TLS/SSL encryption
- Template-based email generation

**Email Types**:
- User registration confirmation
- Password reset emails
- Scheduled report delivery
- System notification alerts
- Export completion notifications

### 4.4.4 Security Communication

#### REQ-COMM-005: Authentication Protocols
**Session Management**:
- Secure session cookies
- CSRF token protection
- Session timeout handling
- Concurrent session control

**API Authentication**:
- Bearer token authentication
- API key validation
- Rate limiting per user/IP
- Request signing untuk critical operations

### 4.4.5 Monitoring and Logging

#### REQ-COMM-006: Logging Protocols
**Application Logging**:
- Structured logging (JSON format)
- Log levels: DEBUG, INFO, WARNING, ERROR, CRITICAL
- Request/response logging
- Performance metrics logging

**System Monitoring**:
- Health check endpoints
- Metrics collection (Prometheus format)
- Error tracking dan alerting
- Performance monitoring integration

#### REQ-COMM-007: Notification Protocols
**Real-time Notifications**:
- WebSocket connections untuk live updates
- Server-sent events untuk dashboard updates
- Push notification support untuk mobile

**Batch Notifications**:
- Email batch processing
- SMS integration (optional)
- Slack/Discord webhook integration (optional)