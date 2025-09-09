# Basis Data Konsumsi Pangan

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![Livewire](https://img.shields.io/badge/Livewire-3.x-green.svg)
![Python](https://img.shields.io/badge/Python-3.9+-blue.svg)
![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)
[![Docker Hub](https://img.shields.io/badge/Docker%20Hub-send0moka%2Fbasis--data--konsumsi--pangan-blue)](https://hub.docker.com/r/send0moka/basis-data-konsumsi-pangan)

Sistem informasi basis data konsumsi pangan dengan fitur prediksi Nilai Belanja Makanan (NBM) menggunakan machine learning. Aplikasi ini dibangun dengan Laravel dan Livewire untuk frontend yang modern dan responsif.

## 🚀 Quick Start dengan Docker (Recommended)

### Jalankan dengan Docker (1 menit setup):

```bash
# Clone repository
git clone https://github.com/send0moka/basis-data-konsumsi-pangan.git
cd basis-data-konsumsi-pangan

# Jalankan dengan Docker Compose
docker-compose up --build -d

# Akses aplikasi
# - Web App: http://localhost:8000
# - phpMyAdmin: http://localhost:8081
# - ML API: http://localhost:8082
```

**Login Credentials:**
- **Super Admin:** `superadmin@example.com` / `password`
- **Admin:** `admin@example.com` / `password`

### Atau gunakan Docker Hub Image:

```bash
# Pull dari Docker Hub
docker pull send0moka/basis-data-konsumsi-pangan:latest

# Jalankan container
docker run -d -p 8000:80 send0moka/basis-data-konsumsi-pangan:latest
```

## 🌟 Fitur Utama

- **Manajemen Data Konsumsi Pangan** CRUD lengkap untuk kelompok, komoditi, dan transaksi
- **Dashboard Interaktif** Visualisasi data dengan chart dan statistik
- **Export Data** Export ke Excel untuk berbagai entitas
- **Sistem Role & Permission** Kontrol akses berbasis peran
- **Prediksi NBM** Machine learning model dengan akurasi 8.88% MAPE
- **API ML** FastAPI server untuk prediksi real-time
- **Docker Ready** Deployment dengan Docker untuk production

## � Docker Setup Lengkap

### Persyaratan Docker:
- **Docker Desktop** atau **Docker Engine**
- **Docker Compose** v2.0+
- Minimum **4GB RAM** untuk semua services

### Services Yang Tersedia:

| Service | Port | Deskripsi |
|---------|------|-----------|
| **Laravel App** | `8000` | Aplikasi web utama |
| **Nginx** | `8000` | Web server reverse proxy |
| **MySQL** | `3306` | Database utama |
| **phpMyAdmin** | `8081` | Database management UI |
| **ML API** | `8082` | Machine Learning API server |

### Environment Variables:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=konsumsi_pangan
DB_USERNAME=root
DB_PASSWORD=rootsecret

# ML API
ML_API_URL=http://ml-api:8082

# App
APP_NAME="Basis Data Konsumsi Pangan"
APP_ENV=local
APP_KEY=base64:your_app_key_here
```

### Docker Commands:

```bash
# Build dan start semua services
docker-compose up --build -d

# Lihat status services
docker-compose ps

# Lihat logs
docker-compose logs -f app

# Stop semua services
docker-compose down

# Rebuild specific service
docker-compose up --build -d app

# Masuk ke container
docker-compose exec app bash

# Jalankan Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

### Troubleshooting Docker:

**1. Port sudah digunakan:**
```bash
# Cek port yang digunakan
netstat -tulpn | grep :8000

# Ubah port di docker-compose.yml
ports:
  - "8001:80"  # Ganti port host
```

**2. Memory limit Docker:**
```bash
# Increase Docker memory limit di Docker Desktop
# Settings > Resources > Memory > Set minimal 4GB
```

**3. Permission issues (Linux):**
```bash
# Add user ke docker group
sudo usermod -aG docker $USER
# Logout dan login ulang
```

## �📋 Prasyarat Sistem

### Software Yang Diperlukan (untuk development lokal):
- **PHP** 8.2 atau lebih tinggi
- **Composer** (untuk dependency PHP)
- **Node.js** & **npm** (untuk asset building)
- **MySQL** atau **MariaDB**
- **Git** (untuk cloning repository)
- **Python** 3.9+ (untuk ML model)

### Atau gunakan Docker (Recommended):
- **Docker Desktop** atau **Docker Engine**
- **Docker Compose** v2.0+

## 🚀 Instalasi

### **Opsi 1: Menggunakan Docker (Recommended)**

#### 1. Clone Repository

```bash
git clone https://github.com/send0moka/basis-data-konsumsi-pangan.git
cd basis-data-konsumsi-pangan
```

#### 2. Jalankan dengan Docker

```bash
# Build dan start semua services
docker-compose up --build -d

# Tunggu sampai semua container ready (2-3 menit pertama kali)
docker-compose ps

# Jalankan database migrations
docker-compose exec app php artisan migrate:fresh --seed

# Generate app key
docker-compose exec app php artisan key:generate
```

#### 3. Akses Aplikasi

- **Web App:** http://localhost:8000
- **phpMyAdmin:** http://localhost:8081
- **ML API:** http://localhost:8082

### **Opsi 2: Instalasi Manual (Development)**

#### 1. Clone Repository

```bash
git clone https://github.com/send0moka/basis-data-konsumsi-pangan.git
cd basis-data-konsumsi-pangan
```

#### 2. Setup Environment

Copy file environment dan sesuaikan konfigurasi:

```bash
cp .env.example .env
```

Edit file `.env` sesuai dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=konsumsi_pangan
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 3. Install Dependencies

**Install PHP Dependencies:**
```bash
composer install
```

**Install Node.js Dependencies:**
```bash
npm install
```

#### 4. Generate Application Key

```bash
php artisan key:generate
```

#### 5. Setup Database

Buat database MySQL terlebih dahulu:

```sql
CREATE DATABASE konsumsi_pangan;
```

**Pilihan A: Menggunakan Migration & Seed (Recommended)**

Jalankan migration dan seeder untuk membuat struktur database dan data sample:

```bash
php artisan migrate:fresh --seed
```

**Pilihan B: Import SQL File**

Jika Anda memilih menggunakan file SQL yang sudah tersedia:

```bash
# Import menggunakan MySQL command line
mysql -u root -p konsumsi_pangan < konsumsi_pangan.sql

# Atau menggunakan phpMyAdmin:
# 1. Buka phpMyAdmin
# 2. Pilih database 'konsumsi_pangan'
# 3. Klik tab 'Import'
# 4. Upload file 'konsumsi_pangan.sql'
# 5. Klik 'Go'
```

#### 6. Setup Machine Learning Environment

**Automated Setup (Recommended)**

Gunakan script setup otomatis:

**Windows:**
```bash
setup.bat
```

**Linux/macOS:**
```bash
chmod +x setup.sh
./setup.sh
```

**Manual Setup**

Jika Anda ingin setup manual:

```bash
# Install Python dependencies
cd ml_models
pip install -r requirements.txt

# Generate production model
python production_model.py

# Test model
python test_setup.py
```

#### 7. Build Assets

```bash
npm run build
```

Untuk development dengan hot reload:
```bash
npm run dev
```

#### 8. Set Permissions (Linux/macOS)

```bash
chmod -R 775 storage bootstrap/cache
```

## 🎯 Menjalankan Aplikasi

### **Dengan Docker (Recommended):**

```bash
# Start semua services
docker-compose up -d

# Cek status services
docker-compose ps

# Lihat logs
docker-compose logs -f
```

**Akses Aplikasi:**
- **Web App:** http://localhost:8000
- **phpMyAdmin:** http://localhost:8081
- **ML API:** http://localhost:8082

### **Development Manual:**

#### 1. Start Laravel Server

```bash
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

#### 2. Start ML API Server

Untuk menggunakan fitur prediksi NBM, jalankan API server:

**Windows:**
```bash
start_api.bat
```

**Linux/macOS:**
```bash
./start_api.sh
```

API akan berjalan di: `http://localhost:8081`

#### 3. Login ke Aplikasi

**Super Admin:**
- Email: `superadmin@example.com`
- Password: `password`

**Admin:**
- Email: `admin@example.com`
- Password: `password`

## 📁 Struktur Project

```
basis-data-konsumsi-pangan/
├── 🐳 Docker Files
│   ├── Dockerfile              # Laravel app container
│   ├── docker-compose.yml      # Multi-service setup
│   └── nginx.conf              # Nginx configuration
├── 📱 Laravel Application
│   ├── app/                    # Core aplikasi Laravel
│   │   ├── Http/Controllers/   # Controllers
│   │   ├── Livewire/          # Livewire components
│   │   ├── Models/            # Eloquent models
│   │   └── Services/          # Business logic services
│   ├── database/              # Database files
│   │   ├── migrations/        # Database migrations
│   │   └── seeders/          # Database seeders
│   ├── resources/
│   │   ├── views/           # Blade templates
│   │   ├── css/            # Stylesheets
│   │   └── js/             # JavaScript files
│   └── routes/              # Route definitions
├── 🤖 Machine Learning
│   ├── ml_models/             # ML models & scripts
│   │   ├── models/           # Trained models
│   │   ├── data/            # Training data
│   │   └── production_model.py # Production model
│   ├── nbm_api.py            # FastAPI server
│   └── requirements.txt      # Python dependencies
├── 📊 Database & Assets
│   ├── konsumsi_pangan.sql   # Database dump
│   ├── public/              # Public assets
│   └── storage/             # File storage
├── ⚙️ Configuration
│   ├── .env.example         # Environment template
│   ├── phpunit.xml          # Testing configuration
│   ├── composer.json        # PHP dependencies
│   └── package.json         # Node dependencies
└── 🚀 Scripts
    ├── setup.bat/.sh        # Setup scripts
    └── start_api.bat/.sh    # API server scripts
```

## 🧪 Testing

### **Testing dengan Docker:**

```bash
# Jalankan semua tests
docker-compose exec app php artisan test

# Jalankan specific test
docker-compose exec app php artisan test --filter=ExampleTest

# Jalankan dengan coverage
docker-compose exec app php artisan test --coverage

# Debug mode
docker-compose exec app php artisan test --verbose
```

### **Testing Manual (Development):**

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ExampleTest
```

### **Testing API ML:**

```bash
# Test health check
curl http://localhost:8082/health

# Test prediction endpoint
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{"kelompok_bps": 1, "komoditi_bps": 1, "tahun": 2024, "bulan": 1}'
```

## 📊 Fitur Machine Learning

### Model Specifications:
- **Algorithm**: HuberRegressor Ensemble
- **Accuracy**: 8.88% MAPE (Mean Absolute Percentage Error)
- **Input Features**: Kelompok BPS, Komoditi BPS, Tahun, Bulan
- **Output**: Prediksi Nilai Belanja Makanan (NBM)

### API Endpoints:

```
GET  /health              # Health check
POST /predict             # Single prediction
POST /predict/batch       # Batch prediction
GET  /model/info          # Model information
GET  /model/features      # Available features
POST /model/validate      # Validate input data
```

## � Deployment

### **Production dengan Docker**

#### 1. Setup Production Environment

```bash
# Clone repository
git clone https://github.com/send0moka/basis-data-konsumsi-pangan.git
cd basis-data-konsumsi-pangan

# Copy environment production
cp .env.example .env.production
```

Edit `.env.production` untuk production:

```env
APP_NAME="Basis Data Konsumsi Pangan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=konsumsi_pangan
DB_USERNAME=prod_user
DB_PASSWORD=secure_password

# SSL Configuration
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

#### 2. Deploy dengan Docker Compose

```bash
# Build untuk production
docker-compose -f docker-compose.yml up --build -d

# Atau gunakan image dari Docker Hub
docker run -d \
  --name basis-data-prod \
  -p 80:80 \
  -e APP_ENV=production \
  send0moka/basis-data-konsumsi-pangan:latest
```

#### 3. Setup SSL dengan Nginx Proxy

```yaml
# docker-compose.prod.yml
version: '3.8'
services:
  app:
    image: send0moka/basis-data-konsumsi-pangan:latest
    environment:
      - APP_ENV=production
    networks:
      - app-network

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/ssl/certs
    depends_on:
      - app
    networks:
      - app-network

networks:
  app-network:
    driver: bridge
```

### **Docker Hub Repository**

Image Docker tersedia di: [Docker Hub](https://hub.docker.com/r/send0moka/basis-data-konsumsi-pangan)

```bash
# Pull latest version
docker pull send0moka/basis-data-konsumsi-pangan:latest

# Pull specific version
docker pull send0moka/basis-data-konsumsi-pangan:v1.0.0
```

### **Environment Variables untuk Production**

```env
# Application
APP_NAME="Basis Data Konsumsi Pangan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=base64:your_generated_key

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=konsumsi_pangan
DB_USERNAME=prod_user
DB_PASSWORD=secure_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password

# ML API
ML_API_URL=http://ml-api:8082
```

## �🔧 Troubleshooting

## 🔧 Troubleshooting

### **Docker Issues:**

**1. Container tidak bisa start:**
```bash
# Lihat logs detail
docker-compose logs app

# Cek resource usage
docker stats

# Restart specific service
docker-compose restart app
```

**2. Port conflict:**
```bash
# Cek port yang digunakan
netstat -tulpn | grep :8000

# Ubah port mapping di docker-compose.yml
ports:
  - "8001:80"  # Ganti ke port lain
```

**3. Memory issues:**
```bash
# Increase Docker Desktop memory limit
# Docker Desktop > Settings > Resources > Memory > 4GB+
```

**4. Permission denied (Linux):**
```bash
# Add user to docker group
sudo usermod -aG docker $USER
# Logout dan login kembali
```

**5. Database connection failed:**
```bash
# Cek MySQL container status
docker-compose ps mysql

# Masuk ke MySQL container
docker-compose exec mysql mysql -u root -p

# Reset database
docker-compose exec app php artisan migrate:fresh --seed
```

### **Masalah Umum:**

**1. Error "production model not found":**
```bash
# Jalankan setup script
setup.bat  # Windows
./setup.sh # Linux/macOS
```

**2. Error koneksi database:**
- Pastikan MySQL/MariaDB berjalan
- Periksa konfigurasi `.env`
- Pastikan database sudah dibuat

**3. Error permission (Linux/macOS):**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

**4. Error Composer:**
```bash
composer clear-cache
composer install --no-cache
```

**5. Error NPM:**
```bash
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

**6. ML API tidak bisa diakses:**
- Pastikan Python dependencies terinstall
- Jalankan `start_api.bat` atau `start_api.sh`
- Periksa port 8081 tidak digunakan aplikasi lain

## 🤝 Contributing

### **Development dengan Docker:**

1. **Fork repository ini**
2. **Clone fork Anda:**
```bash
git clone https://github.com/yourusername/basis-data-konsumsi-pangan.git
cd basis-data-konsumsi-pangan
```

3. **Setup development environment:**
```bash
# Jalankan dengan Docker
docker-compose up --build -d

# Masuk ke container untuk development
docker-compose exec app bash

# Install dependencies jika ada perubahan
composer install
npm install
```

4. **Buat branch untuk fitur baru:**
```bash
git checkout -b feature/nama-fitur-anda
```

5. **Commit perubahan:**
```bash
git add .
git commit -m "Add: fitur baru yang awesome"
```

6. **Push dan buat Pull Request:**
```bash
git push origin feature/nama-fitur-anda
```

### **Docker Development Workflow:**

```bash
# Jalankan tests di container
docker-compose exec app php artisan test

# Build assets
docker-compose exec app npm run build

# Jalankan migrations
docker-compose exec app php artisan migrate

# Lihat logs real-time
docker-compose logs -f app
```

## � API Documentation

### **ML API Documentation:**

Dokumentasi lengkap API ML tersedia di:
- **Docker:** `http://localhost:8082/docs` (Swagger UI)
- **Manual:** `http://localhost:8081/docs` (Swagger UI)

### **Docker Hub:**

Image Docker tersedia di: [Docker Hub Repository](https://hub.docker.com/r/send0moka/basis-data-konsumsi-pangan)

```bash
# Pull latest image
docker pull send0moka/basis-data-konsumsi-pangan:latest

# Pull specific version
docker pull send0moka/basis-data-konsumsi-pangan:v1.0.0
```

### **API Endpoints:**

```
GET  /health              # Health check
POST /predict             # Single prediction
POST /predict/batch       # Batch prediction
GET  /model/info          # Model information
GET  /model/features      # Available features
POST /model/validate      # Validate input data
```

## �📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel** - The PHP framework for web artisans
- **Livewire** - Dynamic frontend for Laravel
- **Docker** - Containerization platform
- **Leaflet** - Interactive maps
- **Scikit-learn** - Machine learning library
- **FastAPI** - Modern Python web framework

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

1. **Cek bagian Troubleshooting** di atas
2. **Buat Issue** di [GitHub Issues](https://github.com/send0moka/basis-data-konsumsi-pangan/issues)
3. **Diskusi** di [GitHub Discussions](https://github.com/send0moka/basis-data-konsumsi-pangan/discussions)

---

**Happy Coding! 🚀**

*Built with ❤️ using Laravel, Livewire, and Docker*