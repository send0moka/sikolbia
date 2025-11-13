# SIKOLBIA Documentation Index

Dokumentasi lengkap untuk sistem SIKOLBIA (Sistem Informasi Komoditas Pangan dan Analisis Berbasis Intelegensi Artifisial).

## 📁 Struktur Dokumentasi

### 🚀 Setup & Deployment
**Folder**: [`setup/`](./setup/)

Panduan instalasi, konfigurasi, dan deployment sistem.

- [BACKUP_RESTORE_SETUP.md](./setup/BACKUP_RESTORE_SETUP.md) - Setup backup & restore database
- [CHANGELOG_BACKUP_RESTORE.md](./setup/CHANGELOG_BACKUP_RESTORE.md) - Changelog fitur backup
- [DOCKER_QUEUE_SETUP.md](./setup/DOCKER_QUEUE_SETUP.md) - Setup queue worker di Docker
- [EMAIL_QUICK_START.md](./setup/EMAIL_QUICK_START.md) - Quick start email configuration
- [GMAIL_SMTP_SETUP.md](./setup/GMAIL_SMTP_SETUP.md) - Setup Gmail SMTP
- [MYSQL_PATH_CONFIG.md](./setup/MYSQL_PATH_CONFIG.md) - Konfigurasi MySQL path
- [PRODUCTION_DEPLOYMENT.md](./setup/PRODUCTION_DEPLOYMENT.md) - Deployment ke production
- [QUEUE_WORKER_DOCKER.md](./setup/QUEUE_WORKER_DOCKER.md) - Worker queue di Docker

### ✨ Features
**Folder**: [`features/`](./features/)

Dokumentasi fitur-fitur utama sistem.

- [FASTAPI_INTEGRATION_SUCCESS.md](./features/FASTAPI_INTEGRATION_SUCCESS.md) - Integrasi FastAPI ML service
- [REGISTRASI_AKADEMISI_GUIDE.md](./features/REGISTRASI_AKADEMISI_GUIDE.md) - Panduan registrasi akademisi
- [REGISTRATION_WORKFLOW_COMPLETE.md](./features/REGISTRATION_WORKFLOW_COMPLETE.md) - Complete registration workflow
- [RESUBMIT_REGISTRATION_FEATURE.md](./features/RESUBMIT_REGISTRATION_FEATURE.md) - Fitur resubmit registration

### 🔧 Fixes & Troubleshooting
**Folder**: [`fixes/`](./fixes/)

Dokumentasi bug fixes dan solusi masalah.

- [FIX_419_ERROR.md](./fixes/FIX_419_ERROR.md) - Fix CSRF 419 error
- [FIX_AKADEMISI_REGISTRATION.md](./fixes/FIX_AKADEMISI_REGISTRATION.md) - Fix registrasi akademisi
- [FIX_DATABASE_CONFIG.md](./fixes/FIX_DATABASE_CONFIG.md) - Fix database configuration
- [FIX_EMAIL_REGISTRASI_PEMERINTAH.md](./fixes/FIX_EMAIL_REGISTRASI_PEMERINTAH.md) - Fix email registrasi
- [FIX_FLUX_COMPONENTS.md](./fixes/FIX_FLUX_COMPONENTS.md) - Fix Flux UI components
- [FIX_PEMERINTAH_REGISTRATION_FORM.md](./fixes/FIX_PEMERINTAH_REGISTRATION_FORM.md) - Fix form registrasi
- [FIX_ROUTE_ERROR.md](./fixes/FIX_ROUTE_ERROR.md) - Fix routing errors

### 📚 Reference Data
**Folder**: [`reference/`](./reference/)

Data referensi dan panduan teknis.

- [indonesia_provinces_regencies.md](./reference/indonesia_provinces_regencies.md) - Data provinsi & kabupaten
- [jenis_dinas_list.md](./reference/jenis_dinas_list.md) - Daftar jenis dinas
- [laravel_image_upload_guide.md](./reference/laravel_image_upload_guide.md) - Panduan upload gambar
- [nutrition.md](./reference/nutrition.md) - Data nutrisi pangan

### 🤖 Chatbot & LLM
**Folder**: [`chatbot/`](./chatbot/)

Dokumentasi sistem chatbot dan LLM orchestrator.

- [README.md](./chatbot/README.md) - Overview chatbot system
- [llm-comparison-plan.md](./chatbot/llm-comparison-plan.md) - LLM comparison analysis
- [rag-structured-retrieval.md](./chatbot/rag-structured-retrieval.md) - RAG implementation
- [research-plan.md](./chatbot/research-plan.md) - Research & development plan
- [improvement-roadmap.md](./chatbot/improvement-roadmap.md) - Improvement roadmap

### 📊 Machine Learning Evaluation
**Folder**: [`ml_evaluation/`](./ml_evaluation/)

Dokumentasi evaluasi model ML dan performa.

- [model_performance_report.md](./ml_evaluation/model_performance_report.md) - Laporan performa model
- [cross_validation_report.md](./ml_evaluation/cross_validation_report.md) - Hasil cross-validation
- [hyperparameter_optimization.md](./ml_evaluation/hyperparameter_optimization.md) - Optimisasi hyperparameter

### 📐 Architecture & Design
**Folder**: [`srs/`](./srs/)

Software Requirements Specification dan arsitektur sistem.

- [01_introduction.md](./srs/01_introduction.md) - Pengantar sistem
- [02_system_features.md](./srs/02_system_features.md) - Fitur sistem
- [03_external_interfaces.md](./srs/03_external_interfaces.md) - Interface eksternal
- [04_nonfunctional_requirements.md](./srs/04_nonfunctional_requirements.md) - Non-functional requirements
- [05_database_design.md](./srs/05_database_design.md) - Desain database
- [06_api_specification.md](./srs/06_api_specification.md) - Spesifikasi API
- [07_deployment_architecture.md](./srs/07_deployment_architecture.md) - Arsitektur deployment

### 🎓 Thesis & Seminar
**Folder**: [`sempro/`](./sempro/)

Dokumentasi seminar proposal dan tugas akhir.

- [complete_checklist.md](./sempro/complete_checklist.md) - Checklist kelengkapan
- [dataset_correction.md](./sempro/dataset_correction.md) - Koreksi dataset
- [laporan_tugas_akhir.md](./sempro/laporan_tugas_akhir.md) - Draft laporan TA
- [naskah_ppt.md](./sempro/naskah_ppt.md) - Naskah presentasi
- [post_sempro_roadmap.md](./sempro/post_sempro_roadmap.md) - Roadmap pasca sempro
- [revision_summary.md](./sempro/revision_summary.md) - Summary revisi
- [srs.md](./sempro/srs.md) - SRS document
- [tanya_jawab.md](./sempro/tanya_jawab.md) - Q&A sempro
- [users_guide.md](./sempro/users_guide.md) - User guide

### 🏛️ Architecture Decision Records
**Folder**: [`ADR/`](./ADR/)

Dokumentasi keputusan arsitektur penting.

- [001-llm-orchestrator.md](./ADR/001-llm-orchestrator.md) - LLM orchestrator architecture

### 📋 Other Documents

- [BRANCH_README.md](./BRANCH_README.md) - Branch strategy & workflow
- [ENHANCED_UI_UX_COMPLETED.md](./ENHANCED_UI_UX_COMPLETED.md) - UI/UX enhancement summary
- [EMAIL_NOTIFICATION_GUIDE.md](./EMAIL_NOTIFICATION_GUIDE.md) - Email notification guide
- [PREDICTION_DASHBOARD_COMPLETED.md](./PREDICTION_DASHBOARD_COMPLETED.md) - Dashboard completion notes
- [QUICK_REFERENCE_SPLIT.md](./QUICK_REFERENCE_SPLIT.md) - Quick reference split project
- [SHAP_IMPLEMENTATION_COMPLETED.md](./SHAP_IMPLEMENTATION_COMPLETED.md) - SHAP explainability implementation
- [SHAP_INSTALLATION.md](./SHAP_INSTALLATION.md) - SHAP installation guide
- [SPLIT_PROJECT_GUIDE.md](./SPLIT_PROJECT_GUIDE.md) - Split project guide (Laravel + FastAPI)
- [URL_ACCESS_GUIDE.md](./URL_ACCESS_GUIDE.md) - URL access reference

## 🚀 Quick Start

### For Developers
1. Read [SPLIT_PROJECT_GUIDE.md](./SPLIT_PROJECT_GUIDE.md) untuk struktur project
2. Follow [setup/DOCKER_QUEUE_SETUP.md](./setup/DOCKER_QUEUE_SETUP.md) untuk setup environment
3. Check [features/FASTAPI_INTEGRATION_SUCCESS.md](./features/FASTAPI_INTEGRATION_SUCCESS.md) untuk ML integration

### For Users
1. Read [sempro/users_guide.md](./sempro/users_guide.md) untuk panduan penggunaan
2. Check [URL_ACCESS_GUIDE.md](./URL_ACCESS_GUIDE.md) untuk akses sistem

### For Troubleshooting
1. Check [`fixes/`](./fixes/) folder untuk common issues
2. Lihat [QUICK_REFERENCE_SPLIT.md](./QUICK_REFERENCE_SPLIT.md) untuk referensi cepat

## 📊 Project Statistics

- **Total Documentation Files**: 60+ markdown files
- **Categories**: 12 kategori
- **Languages**: Indonesian & English
- **Last Updated**: November 13, 2025

## 🔗 External Documentation

- **Prediksi NBM Enhancement**: `d:/sikolbia/docs/prediksi-nbm/`
- **ML Integration**: `d:/sikolbia/docs/ml-integration/`
- **Thesis Documents**: `d:/sikolbia/docs/thesis/`
- **ML API Documentation**: `sikolbia-ml/README.md`

---

**Maintained by**: SIKOLBIA Development Team  
**Repository**: github.com/send0moka/sikolbia
