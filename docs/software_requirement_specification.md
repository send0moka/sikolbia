# Software Requirements Specification (SRS) - Main Index

**Sistem Basis Data Konsumsi Pangan dengan Prediksi NBM**

---

## 📋 Document Overview

Dokumentasi Software Requirements Specification (SRS) ini menjelaskan secara komprehensif kebutuhan fungsional dan non-fungsional untuk Sistem Basis Data Konsumsi Pangan dengan fitur prediksi Nilai Belanja Makanan (NBM) menggunakan machine learning.

**Version:** 1.0  
**Date:** September 20, 2025  
**Project:** Basis Data Konsumsi Pangan  
**Technology Stack:** Laravel 12 + Livewire 3 + FastAPI + MySQL + Docker  

---

## 📚 Table of Contents

### [1. Introduction & Overview](./srs/01_introduction.md)
- **1.1** Purpose dan Scope
- **1.2** Definitions, Acronyms, dan Abbreviations  
- **1.3** Product Perspective dan Functions
- **1.4** User Classes dan Characteristics
- **1.5** Operating Environment
- **1.6** Design Constraints dan Dependencies

**Key Topics:** Tujuan sistem, ruang lingkup aplikasi, arsitektur umum, karakteristik pengguna, environment requirements

---

### [2. System Features & Functional Requirements](./srs/02_system_features.md)
- **2.1** User Authentication & Authorization (RBAC)
- **2.2** Data Management (Kelompok, Komoditi, Transaksi NBM)
- **2.3** Machine Learning Prediction System  
- **2.4** Dashboard & Reporting System
- **2.5** Export & Import Functionality
- **2.6** Agricultural Data Management
- **2.7** Geographic Information System (GIS)

**Key Topics:** CRUD operations, ML predictions, role-based access control, reporting features, data visualization

---

### [3. External Interface Requirements](./srs/03_external_interfaces.md)
- **3.1** User Interface Requirements (Responsive, Accessibility)
- **3.2** Hardware Interface Requirements  
- **3.3** Software Interface Requirements (OS, Database, APIs)
- **3.4** Communication Interface Requirements (HTTP/HTTPS, Database protocols)

**Key Topics:** UI/UX requirements, hardware specifications, software dependencies, communication protocols

---

### [4. Non-Functional Requirements](./srs/04_nonfunctional_requirements.md)
- **4.1** Performance Requirements (Response times, Throughput)
- **4.2** Safety Requirements (Backup, Recovery, Fault tolerance)
- **4.3** Security Requirements (Authentication, Data protection, OWASP compliance)
- **4.4** Software Quality Attributes (Reliability, Scalability, Maintainability)
- **4.5** Business Rules (Data validation, Access rules, ML rules)

**Key Topics:** System performance metrics, security standards, reliability requirements, business logic constraints

---

### [5. Database Design](./srs/05_database_design.md)
- **5.1** Database Architecture Overview (MySQL, InnoDB, UTF-8)
- **5.2** Core Data Models (Users, Kelompok, Komoditi, Transaksi NBM)
- **5.3** Extended Data Models (SUSENAS, Geographic, Agricultural)
- **5.4** Database Relationships & ERD
- **5.5** Indexes & Performance Optimization
- **5.6** Data Migration & Seeding Strategies

**Key Topics:** Database schema design, entity relationships, performance optimization, data migration procedures

---

### [6. API Specification](./srs/06_api_specification.md)
- **6.1** API Architecture Overview (RESTful, JSON-first)
- **6.2** Laravel Web API Endpoints (Authentication, CRUD, Export)
- **6.3** FastAPI ML Service (Predictions, Model info, Health checks)
- **6.4** Error Handling & Status Codes
- **6.5** Rate Limiting & Security
- **6.6** API Documentation & Testing

**Key Topics:** REST API design, ML service endpoints, error handling, security measures, rate limiting

---

### [7. Deployment Architecture](./srs/07_deployment_architecture.md)
- **7.1** Deployment Overview (Multi-container Docker architecture)
- **7.2** Container Specifications (Laravel, FastAPI, MySQL, Nginx, Redis)
- **7.3** Docker Compose Configurations (Development, Staging, Production)
- **7.4** Environment-Specific Configurations
- **7.5** Deployment Strategies (Blue-Green, Rolling updates)
- **7.6** Monitoring & Observability (Prometheus, Grafana, ELK Stack)
- **7.7** Backup & Disaster Recovery

**Key Topics:** Docker containerization, deployment strategies, monitoring solutions, backup procedures

---

## 🎯 Quick Navigation

### For System Administrators
- [Deployment Architecture](./srs/07_deployment_architecture.md) - Container setup dan production deployment
- [Non-Functional Requirements](./srs/04_nonfunctional_requirements.md) - Performance dan security requirements
- [Database Design](./srs/05_database_design.md) - Database optimization dan backup strategies

### For Developers
- [System Features](./srs/02_system_features.md) - Functional requirements dan business logic
- [API Specification](./srs/06_api_specification.md) - REST API dan ML service documentation
- [Database Design](./srs/05_database_design.md) - Schema design dan relationships

### For Project Managers
- [Introduction](./srs/01_introduction.md) - Project overview dan scope
- [System Features](./srs/02_system_features.md) - Feature specifications dan priorities
- [Non-Functional Requirements](./srs/04_nonfunctional_requirements.md) - Quality attributes dan constraints

### For UI/UX Designers
- [External Interfaces](./srs/03_external_interfaces.md) - User interface requirements
- [System Features](./srs/02_system_features.md) - User interaction specifications

---

## 🏗️ System Architecture Summary

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                      │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │   Web Browser   │  │   Mobile Apps   │  │  API Clients│ │
│  │  (Responsive)   │  │   (Future)      │  │ (External)  │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                   APPLICATION LAYER                        │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │ Laravel 12 +    │  │   FastAPI ML    │  │    Nginx    │ │
│  │ Livewire 3      │◄─┤    Service      │  │ (Load Bal.) │ │
│  │ (Web App)       │  │  (Predictions)  │  │             │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                     DATA LAYER                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │   MySQL 8.0     │  │    Redis        │  │  File       │ │
│  │  (Primary DB)   │  │   (Cache)       │  │  Storage    │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔧 Technology Stack

### Backend Technologies
- **Framework:** Laravel 12.x dengan PHP 8.2+
- **Frontend:** Livewire 3.x untuk reactive components
- **ML Service:** FastAPI dengan Python 3.9+
- **Database:** MySQL 8.0+ dengan InnoDB storage engine
- **Cache:** Redis 7.x untuk session dan query caching

### DevOps & Deployment
- **Containerization:** Docker dengan multi-container architecture
- **Orchestration:** Docker Compose untuk service management
- **Web Server:** Nginx sebagai reverse proxy dan load balancer
- **Monitoring:** Prometheus + Grafana untuk metrics
- **Logging:** ELK Stack untuk centralized logging

### Security & Performance
- **Authentication:** Laravel Sanctum dengan role-based access control
- **Security:** OWASP compliance, HTTPS enforcement, input validation
- **Performance:** Database indexing, query optimization, caching strategies
- **Backup:** Automated database dan file backups dengan cloud storage

---

## 📊 Key Features Overview

### Core Functionalities
1. **Comprehensive Data Management** - CRUD operations untuk kelompok pangan, komoditi, dan transaksi NBM
2. **Advanced ML Predictions** - HuberRegressor ensemble model dengan 8.88% MAPE accuracy
3. **Interactive Dashboards** - Real-time data visualization dengan charts dan maps
4. **Flexible Export System** - Multiple format support (Excel, CSV, PDF) dengan custom configurations
5. **Role-Based Access Control** - Fine-grained permissions untuk different user types

### Extended Features
- **Multi-Domain Agriculture Data** - Benih & Pupuk, Iklim OPT-DPI, Lahan management
- **Geographic Information System** - Interactive maps dengan spatial data analysis
- **SUSENAS Integration** - BPS consumption survey data management
- **Automated Reporting** - Scheduled reports dengan email delivery
- **API Integration** - RESTful APIs untuk external system integration

---

## 📈 Performance Targets

| Metric | Target | Notes |
|--------|--------|-------|
| **Page Load Time** | ≤ 2 seconds | Standard pages, cached content |
| **API Response Time** | ≤ 500ms | CRUD operations average |
| **ML Prediction Time** | ≤ 2 seconds | Single prediction request |
| **Concurrent Users** | 500 users | Simultaneous active users |
| **System Uptime** | 99.5% | ≈ 3.6 hours downtime/month |
| **Database Query Time** | ≤ 100ms | Simple SELECT queries |

---

## 🔒 Security Compliance

- **OWASP Top 10** compliance untuk web application security
- **HTTPS enforcement** dengan TLS 1.2+ encryption
- **Input validation** dan SQL injection prevention
- **XSS protection** dengan content security policy
- **Rate limiting** untuk API endpoints
- **Data encryption** at rest dan in transit
- **Regular security updates** dan vulnerability scanning

---

## 📞 Document Maintenance

**Document Owner:** System Analysis Team  
**Last Updated:** September 20, 2025  
**Review Cycle:** Quarterly review untuk updates dan improvements  
**Approval:** Project Manager, Lead Developer, System Architect  

**Change Log:**
- v1.0 (Sep 20, 2025): Initial comprehensive SRS documentation
- Future versions will be documented here

---

**© 2025 Basis Data Konsumsi Pangan Project. All rights reserved.**