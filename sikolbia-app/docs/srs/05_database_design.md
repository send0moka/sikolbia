# 6. DATABASE DESIGN

## 6.1 Database Architecture Overview

### 6.1.1 Database Management System
**Primary Database**: MySQL 8.0+ / MariaDB 10.5+  
**Storage Engine**: InnoDB untuk ACID compliance dan foreign key support  
**Character Set**: UTF-8 (utf8mb4) untuk full Unicode support  
**Collation**: utf8mb4_unicode_ci untuk case-insensitive sorting  

### 6.1.2 Database Design Principles
- **Normalization**: 3NF (Third Normal Form) untuk most tables
- **Referential Integrity**: Foreign key constraints enforcement
- **Data Consistency**: ACID transactions untuk critical operations
- **Performance**: Strategic denormalization untuk reporting tables
- **Scalability**: Partitioning considerations untuk large tables

## 6.2 Core Data Models

### 6.2.1 User Management Tables

#### users
**Purpose**: Core user authentication dan profile information
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);
```

#### roles
**Purpose**: Role definitions untuk RBAC system
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NOT NULL DEFAULT 'web',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_role_guard (name, guard_name)
);
```

#### permissions
**Purpose**: Permission definitions untuk fine-grained access control
```sql
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NOT NULL DEFAULT 'web',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_permission_guard (name, guard_name)
);
```

### 6.2.2 Food Data Core Tables

#### kelompok
**Purpose**: Food group master data (Primary classification)
```sql
CREATE TABLE kelompok (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) UNIQUE NOT NULL COMMENT 'Food group code (01-99)',
    nama VARCHAR(255) NOT NULL COMMENT 'Food group name',
    deskripsi TEXT NULL COMMENT 'Detailed description',
    ake_ketersediaan DECIMAL(8,2) NULL COMMENT 'Energy availability coefficient',
    skor_pph DECIMAL(8,2) NULL COMMENT 'PPH score',
    status_aktif BOOLEAN DEFAULT TRUE COMMENT 'Active status flag',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_kode (kode),
    INDEX idx_status_aktif (status_aktif),
    INDEX idx_nama (nama)
);
```

#### komoditi
**Purpose**: Food commodity master data (Secondary classification)
```sql
CREATE TABLE komoditi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_kelompok VARCHAR(10) NOT NULL COMMENT 'Reference to kelompok.kode',
    kode_komoditi VARCHAR(10) NOT NULL COMMENT 'Commodity code within group',
    nama VARCHAR(255) NOT NULL COMMENT 'Commodity name',
    deskripsi TEXT NULL COMMENT 'Detailed description',
    satuan VARCHAR(50) DEFAULT 'kg' COMMENT 'Unit of measurement',
    konversi_faktor DECIMAL(10,4) DEFAULT 1.0000 COMMENT 'Conversion factor',
    status_aktif BOOLEAN DEFAULT TRUE COMMENT 'Active status flag',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kode_kelompok) REFERENCES kelompok(kode) ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE KEY unique_komoditi_per_kelompok (kode_kelompok, kode_komoditi),
    INDEX idx_kode_kelompok (kode_kelompok),
    INDEX idx_kode_komoditi (kode_komoditi),
    INDEX idx_nama (nama),
    INDEX idx_status_aktif (status_aktif)
);
```

### 6.2.3 Transaction Data Tables

#### transaksi_nbms
**Purpose**: Main NBM (Neraca Bahan Makanan) transaction data
```sql
CREATE TABLE transaksi_nbms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_kelompok VARCHAR(10) NOT NULL,
    kode_komoditi VARCHAR(10) NOT NULL,
    tahun YEAR NOT NULL COMMENT 'Data year',
    bulan TINYINT NULL COMMENT 'Data month (1-12), NULL for yearly data',
    kuartal TINYINT NULL COMMENT 'Quarter (1-4), NULL for monthly/yearly',
    periode_data ENUM('bulanan', 'kuartalan', 'tahunan') DEFAULT 'tahunan',
    status_angka ENUM('tetap', 'sementara', 'sangat sementara') DEFAULT 'sementara',
    
    -- Supply side data (thousand tons)
    masukan DECIMAL(12,4) NULL COMMENT 'Total input supply',
    keluaran DECIMAL(12,4) NULL COMMENT 'Total output',
    impor DECIMAL(12,4) NULL COMMENT 'Import quantity',
    ekspor DECIMAL(12,4) NULL COMMENT 'Export quantity',
    perubahan_stok DECIMAL(12,4) NULL COMMENT 'Stock change',
    
    -- Demand side data (thousand tons)
    pakan DECIMAL(12,4) NULL COMMENT 'Animal feed usage',
    bibit DECIMAL(12,4) NULL COMMENT 'Seed usage',
    makanan DECIMAL(12,4) NULL COMMENT 'Food consumption',
    bukan_makanan DECIMAL(12,4) NULL COMMENT 'Non-food usage',
    tercecer DECIMAL(12,4) NULL COMMENT 'Waste/loss',
    penggunaan_lain DECIMAL(12,4) NULL COMMENT 'Other usage',
    
    -- Consumption metrics
    bahan_makanan DECIMAL(12,4) NULL COMMENT 'Food material (thousand tons)',
    kg_tahun DECIMAL(12,4) NULL COMMENT 'Kg per capita per year',
    gram_hari DECIMAL(12,4) NULL COMMENT 'Gram per capita per day',
    kalori_hari DECIMAL(12,4) NULL COMMENT 'Calories per capita per day',
    protein_hari DECIMAL(12,4) NULL COMMENT 'Protein per capita per day (grams)',
    lemak_hari DECIMAL(10,6) NULL COMMENT 'Fat per capita per day (grams)',
    
    -- Economic indicators
    harga_produsen DECIMAL(12,4) NULL COMMENT 'Producer price (Rupiah/kg)',
    harga_konsumen DECIMAL(12,4) NULL COMMENT 'Consumer price (Rupiah/kg)',
    inflasi_komoditi DECIMAL(8,4) NULL COMMENT 'Commodity inflation rate (%)',
    nilai_tukar_usd DECIMAL(8,4) NULL COMMENT 'USD exchange rate',
    
    -- Demographics and macro indicators
    populasi_indonesia BIGINT NULL COMMENT 'Indonesia population',
    gdp_per_kapita DECIMAL(12,2) NULL COMMENT 'GDP per capita (USD)',
    tingkat_kemiskinan DECIMAL(5,2) NULL COMMENT 'Poverty rate (%)',
    
    -- Environmental factors
    curah_hujan_mm DECIMAL(8,2) NULL COMMENT 'Rainfall (mm)',
    suhu_rata_celsius DECIMAL(5,2) NULL COMMENT 'Average temperature (Celsius)',
    indeks_el_nino DECIMAL(6,3) NULL COMMENT 'El Niño index',
    
    -- Agricultural factors
    luas_panen_ha DECIMAL(12,2) NULL COMMENT 'Harvest area (hectares)',
    produktivitas_ton_ha DECIMAL(8,4) NULL COMMENT 'Productivity (tons/hectare)',
    
    -- Policy factors
    kebijakan_impor BOOLEAN DEFAULT FALSE COMMENT 'Import policy active',
    subsidi_pemerintah DECIMAL(12,2) NULL COMMENT 'Government subsidy (billion Rupiah)',
    stok_bulog DECIMAL(12,4) NULL COMMENT 'Bulog stock (thousand tons)',
    
    -- Data quality indicators
    confidence_score DECIMAL(3,2) DEFAULT 0.80 COMMENT 'Data confidence score (0-1)',
    data_source VARCHAR(100) DEFAULT 'BPS' COMMENT 'Data source',
    validation_status ENUM('validated', 'pending', 'rejected') DEFAULT 'pending',
    outlier_flag BOOLEAN DEFAULT FALSE COMMENT 'Outlier detection flag',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (kode_kelompok) REFERENCES kelompok(kode) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (kode_komoditi) REFERENCES komoditi(kode_komoditi) ON UPDATE CASCADE ON DELETE RESTRICT,
    
    UNIQUE KEY unique_nbm_record (kode_kelompok, kode_komoditi, tahun, bulan, kuartal),
    INDEX idx_tahun_bulan (tahun, bulan),
    INDEX idx_kelompok_tahun (kode_kelompok, tahun),
    INDEX idx_komoditi_tahun (kode_komoditi, tahun),
    INDEX idx_kalori_hari (kalori_hari),
    INDEX idx_status_validation (validation_status),
    INDEX idx_created_at (created_at)
);
```

### 6.2.4 SUSENAS Data Tables

#### tb_kelompokbps
**Purpose**: BPS food group classification
```sql
CREATE TABLE tb_kelompokbps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kelompok_bps INT NOT NULL COMMENT 'BPS food group code',
    nama_kelompok VARCHAR(255) NOT NULL COMMENT 'BPS food group name',
    deskripsi TEXT NULL COMMENT 'Group description',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_kelompok_bps (kelompok_bps),
    INDEX idx_nama_kelompok (nama_kelompok)
);
```

#### tb_komoditibps
**Purpose**: BPS commodity classification
```sql
CREATE TABLE tb_komoditibps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kelompok_bps INT NOT NULL,
    komoditi_bps INT NOT NULL COMMENT 'BPS commodity code',
    nama_komoditi VARCHAR(255) NOT NULL COMMENT 'BPS commodity name',
    satuan VARCHAR(50) DEFAULT 'kg' COMMENT 'Unit of measurement',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kelompok_bps) REFERENCES tb_kelompokbps(kelompok_bps) ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE KEY unique_komoditi_bps (kelompok_bps, komoditi_bps),
    INDEX idx_kelompok_bps (kelompok_bps),
    INDEX idx_nama_komoditi (nama_komoditi)
);
```

#### transaksi_susenas
**Purpose**: SUSENAS consumption survey data
```sql
CREATE TABLE transaksi_susenas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kelompok_bps INT NOT NULL,
    komoditi_bps INT NOT NULL,
    tahun YEAR NOT NULL,
    kode_wilayah VARCHAR(10) NOT NULL COMMENT 'Regional code',
    nama_wilayah VARCHAR(255) NOT NULL COMMENT 'Regional name',
    konsumsi_per_kapita_seminggu DECIMAL(10,4) NULL COMMENT 'Consumption per capita per week',
    konsumsi_per_kapita_setahun DECIMAL(10,4) NULL COMMENT 'Consumption per capita per year',
    pengeluaran_per_kapita_seminggu DECIMAL(12,2) NULL COMMENT 'Expenditure per capita per week (Rupiah)',
    pengeluaran_per_kapita_setahun DECIMAL(12,2) NULL COMMENT 'Expenditure per capita per year (Rupiah)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kelompok_bps) REFERENCES tb_kelompokbps(kelompok_bps) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (komoditi_bps) REFERENCES tb_komoditibps(komoditi_bps) ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE KEY unique_susenas_record (kelompok_bps, komoditi_bps, tahun, kode_wilayah),
    INDEX idx_tahun_wilayah (tahun, kode_wilayah),
    INDEX idx_kelompok_tahun (kelompok_bps, tahun),
    INDEX idx_konsumsi_per_kapita (konsumsi_per_kapita_setahun)
);
```

## 6.3 Extended Data Models

### 6.3.1 Geographic and Administrative Data

#### master_wilayah
**Purpose**: Indonesia administrative boundaries
```sql
CREATE TABLE master_wilayah (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_wilayah VARCHAR(10) UNIQUE NOT NULL COMMENT 'Administrative code',
    nama_wilayah VARCHAR(255) NOT NULL COMMENT 'Administrative name',
    level_wilayah ENUM('provinsi', 'kabupaten', 'kecamatan', 'kelurahan') NOT NULL,
    parent_kode VARCHAR(10) NULL COMMENT 'Parent administrative code',
    latitude DECIMAL(10,8) NULL COMMENT 'Latitude coordinate',
    longitude DECIMAL(11,8) NULL COMMENT 'Longitude coordinate',
    luas_wilayah DECIMAL(12,4) NULL COMMENT 'Area (km²)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_kode) REFERENCES master_wilayah(kode_wilayah) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_level_wilayah (level_wilayah),
    INDEX idx_parent_kode (parent_kode),
    INDEX idx_nama_wilayah (nama_wilayah),
    INDEX idx_coordinates (latitude, longitude)
);
```

### 6.3.2 Agricultural Extension Data

#### benih_pupuk_data
**Purpose**: Seed dan fertilizer data storage
```sql
CREATE TABLE benih_pupuk_data (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topik_id BIGINT UNSIGNED NOT NULL,
    variabel_id BIGINT UNSIGNED NOT NULL,
    klasifikasi_id BIGINT UNSIGNED NOT NULL,
    wilayah_id BIGINT UNSIGNED NOT NULL,
    tahun YEAR NOT NULL,
    bulan_id BIGINT UNSIGNED NOT NULL,
    nilai DECIMAL(15,4) NOT NULL COMMENT 'Data value',
    satuan VARCHAR(50) NOT NULL COMMENT 'Unit of measurement',
    keterangan TEXT NULL COMMENT 'Additional notes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topik_id) REFERENCES benih_pupuk_topik(id) ON DELETE CASCADE,
    FOREIGN KEY (variabel_id) REFERENCES benih_pupuk_variabel(id) ON DELETE CASCADE,
    FOREIGN KEY (klasifikasi_id) REFERENCES benih_pupuk_klasifikasi(id) ON DELETE CASCADE,
    FOREIGN KEY (wilayah_id) REFERENCES benih_pupuk_wilayah(id) ON DELETE CASCADE,
    FOREIGN KEY (bulan_id) REFERENCES master_bulan(id) ON DELETE CASCADE,
    UNIQUE KEY unique_benih_pupuk_record (topik_id, variabel_id, klasifikasi_id, wilayah_id, tahun, bulan_id),
    INDEX idx_tahun_bulan (tahun, bulan_id),
    INDEX idx_wilayah_tahun (wilayah_id, tahun),
    INDEX idx_topik_variabel (topik_id, variabel_id)
);
```

#### iklimoptdpi_data
**Purpose**: Climate and pest/disease data
```sql
CREATE TABLE iklimoptdpi_data (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topik_id BIGINT UNSIGNED NOT NULL,
    variabel_id BIGINT UNSIGNED NOT NULL,
    klasifikasi_id BIGINT UNSIGNED NOT NULL,
    kode_wilayah VARCHAR(10) NOT NULL,
    tahun YEAR NOT NULL,
    nilai DECIMAL(15,4) NOT NULL,
    satuan VARCHAR(50) NOT NULL,
    metode_pengukuran VARCHAR(100) NULL COMMENT 'Measurement method',
    tingkat_kepercayaan DECIMAL(5,2) DEFAULT 95.00 COMMENT 'Confidence level (%)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topik_id) REFERENCES iklimoptdpi_topik(id) ON DELETE CASCADE,
    FOREIGN KEY (variabel_id) REFERENCES iklimoptdpi_variabel(id) ON DELETE CASCADE,
    FOREIGN KEY (klasifikasi_id) REFERENCES iklimoptdpi_klasifikasi(id) ON DELETE CASCADE,
    FOREIGN KEY (kode_wilayah) REFERENCES master_wilayah(kode_wilayah) ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE KEY unique_iklim_record (topik_id, variabel_id, klasifikasi_id, kode_wilayah, tahun),
    INDEX idx_wilayah_tahun (kode_wilayah, tahun),
    INDEX idx_topik_tahun (topik_id, tahun)
);
```

## 6.4 Database Relationships

### 6.4.1 Entity Relationship Diagram (ERD)

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     users       │    │     roles       │    │  permissions    │
│─────────────────│    │─────────────────│    │─────────────────│
│ id (PK)         │    │ id (PK)         │    │ id (PK)         │
│ name            │    │ name            │    │ name            │
│ email (UNIQUE)  │    │ guard_name      │    │ guard_name      │
│ password        │    │ created_at      │    │ created_at      │
│ created_at      │    │ updated_at      │    │ updated_at      │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         └───────────┬───────────┴───────────┬───────────┘
                     │                       │
             ┌───────▼────────┐    ┌────────▼─────────┐
             │ model_has_roles │    │model_has_permissions│
             │─────────────────│    │─────────────────│
             │ role_id (FK)    │    │ permission_id(FK)│
             │ model_type      │    │ model_type      │
             │ model_id        │    │ model_id        │
             └─────────────────┘    └─────────────────┘

┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│    kelompok     │───▶│    komoditi     │───▶│ transaksi_nbms  │
│─────────────────│ 1:N│─────────────────│ 1:N│─────────────────│
│ id (PK)         │    │ id (PK)         │    │ id (PK)         │
│ kode (UNIQUE)   │    │ kode_kelompok(FK)│    │ kode_kelompok(FK)│
│ nama            │    │ kode_komoditi   │    │ kode_komoditi(FK)│
│ status_aktif    │    │ nama            │    │ tahun           │
│ created_at      │    │ status_aktif    │    │ bulan           │
└─────────────────┘    │ created_at      │    │ kalori_hari     │
                       └─────────────────┘    │ ... (50+ fields)│
                                              │ created_at      │
                                              └─────────────────┘

┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ tb_kelompokbps  │───▶│ tb_komoditibps  │───▶│transaksi_susenas│
│─────────────────│ 1:N│─────────────────│ 1:N│─────────────────│
│ id (PK)         │    │ id (PK)         │    │ id (PK)         │
│ kelompok_bps    │    │ kelompok_bps(FK)│    │ kelompok_bps(FK)│
│ nama_kelompok   │    │ komoditi_bps    │    │ komoditi_bps(FK)│
│ created_at      │    │ nama_komoditi   │    │ tahun           │
└─────────────────┘    │ created_at      │    │ kode_wilayah    │
                       └─────────────────┘    │ konsumsi_per_kapita│
                                              │ created_at      │
                                              └─────────────────┘
```

### 6.4.2 Key Relationships

#### Primary Relationships
1. **kelompok ↔ komoditi**: One-to-Many (kelompok.kode → komoditi.kode_kelompok)
2. **komoditi ↔ transaksi_nbms**: One-to-Many (komoditi.kode_komoditi → transaksi_nbms.kode_komoditi)
3. **kelompok ↔ transaksi_nbms**: One-to-Many (kelompok.kode → transaksi_nbms.kode_kelompok)

#### SUSENAS Relationships
1. **tb_kelompokbps ↔ tb_komoditibps**: One-to-Many
2. **tb_komoditibps ↔ transaksi_susenas**: One-to-Many

#### Geographic Relationships
1. **master_wilayah ↔ transaksi_susenas**: One-to-Many (wilayah.kode_wilayah → susenas.kode_wilayah)
2. **master_wilayah ↔ iklimoptdpi_data**: One-to-Many (self-referencing hierarchy)

## 6.5 Database Indexes and Performance

### 6.5.1 Primary Indexes

#### Performance-Critical Indexes
```sql
-- NBM transaction queries (most frequent)
CREATE INDEX idx_nbm_year_month ON transaksi_nbms (tahun, bulan);
CREATE INDEX idx_nbm_kelompok_year ON transaksi_nbms (kode_kelompok, tahun);
CREATE INDEX idx_nbm_komoditi_year ON transaksi_nbms (kode_komoditi, tahun);
CREATE INDEX idx_nbm_kalori_range ON transaksi_nbms (kalori_hari) WHERE kalori_hari IS NOT NULL;

-- User and authentication queries
CREATE INDEX idx_users_email ON users (email);
CREATE INDEX idx_users_created ON users (created_at);

-- Geographic queries
CREATE INDEX idx_wilayah_level ON master_wilayah (level_wilayah);
CREATE INDEX idx_wilayah_parent ON master_wilayah (parent_kode);
CREATE INDEX idx_wilayah_coordinates ON master_wilayah (latitude, longitude);
```

### 6.5.2 Composite Indexes

#### Multi-Column Indexes untuk Complex Queries
```sql
-- Reporting queries
CREATE INDEX idx_nbm_reporting ON transaksi_nbms (tahun, kode_kelompok, validation_status);
CREATE INDEX idx_susenas_regional ON transaksi_susenas (tahun, kode_wilayah, kelompok_bps);

-- Data quality queries
CREATE INDEX idx_nbm_quality ON transaksi_nbms (validation_status, outlier_flag, confidence_score);

-- Agricultural data queries
CREATE INDEX idx_benih_pupuk_reporting ON benih_pupuk_data (tahun, topik_id, wilayah_id);
CREATE INDEX idx_iklim_temporal ON iklimoptdpi_data (tahun, topik_id, kode_wilayah);
```

### 6.5.3 Database Partitioning Strategy

#### Time-Based Partitioning untuk Large Tables
```sql
-- Partition transaksi_nbms by year untuk better performance
ALTER TABLE transaksi_nbms 
PARTITION BY RANGE (tahun) (
    PARTITION p2020 VALUES LESS THAN (2021),
    PARTITION p2021 VALUES LESS THAN (2022),
    PARTITION p2022 VALUES LESS THAN (2023),
    PARTITION p2023 VALUES LESS THAN (2024),
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p_future VALUES LESS THAN MAXVALUE
);
```

## 6.6 Data Migration and Seeding

### 6.6.1 Initial Data Requirements

#### Master Data Seeding
```sql
-- Default roles seeding
INSERT INTO roles (name, guard_name) VALUES
('Super Admin', 'web'),
('Admin', 'web'),
('Public', 'web');

-- Default permissions seeding
INSERT INTO permissions (name, guard_name) VALUES
('view users', 'web'),
('create users', 'web'),
('edit users', 'web'),
('delete users', 'web'),
('view kelompok', 'web'),
('create kelompok', 'web'),
('edit kelompok', 'web'),
('delete kelompok', 'web'),
('view komoditi', 'web'),
('create komoditi', 'web'),
('edit komoditi', 'web'),
('delete komoditi', 'web'),
('view transaksi_nbm', 'web'),
('create transaksi_nbm', 'web'),
('edit transaksi_nbm', 'web'),
('delete transaksi_nbm', 'web'),
('predict nbm', 'web'),
('export data', 'web');

-- Sample kelompok data
INSERT INTO kelompok (kode, nama, deskripsi, status_aktif) VALUES
('01', 'Padi-padian', 'Cereals dan grains', TRUE),
('02', 'Umbi-umbian', 'Tubers dan root vegetables', TRUE),
('03', 'Ikan/Udang/Cumi/Kerang', 'Fish dan seafood', TRUE),
('04', 'Daging', 'Meat products', TRUE),
('05', 'Telur dan Susu', 'Eggs dan dairy products', TRUE),
('06', 'Sayur-sayuran', 'Vegetables', TRUE),
('07', 'Kacang-kacangan', 'Legumes dan nuts', TRUE),
('08', 'Buah-buahan', 'Fruits', TRUE),
('09', 'Minyak dan Lemak', 'Oils dan fats', TRUE),
('10', 'Bahan Minuman', 'Beverage ingredients', TRUE),
('11', 'Bumbu-bumbuan', 'Spices dan seasonings', TRUE);
```

### 6.6.2 Data Migration Scripts

#### Historical Data Import Process
```sql
-- Create temporary staging table untuk data validation
CREATE TEMPORARY TABLE staging_nbm AS 
SELECT * FROM transaksi_nbms WHERE 1=0;

-- Add validation columns
ALTER TABLE staging_nbm 
ADD COLUMN validation_errors TEXT,
ADD COLUMN import_status ENUM('pending', 'validated', 'rejected') DEFAULT 'pending';

-- Import validation procedure
DELIMITER //
CREATE PROCEDURE ValidateNBMImport()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_id BIGINT;
    DECLARE v_errors TEXT DEFAULT '';
    
    DECLARE cur CURSOR FOR 
        SELECT id FROM staging_nbm WHERE import_status = 'pending';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    
    validation_loop: LOOP
        FETCH cur INTO v_id;
        IF done THEN
            LEAVE validation_loop;
        END IF;
        
        -- Validation logic here
        SET v_errors = '';
        
        -- Check year range
        IF (SELECT tahun FROM staging_nbm WHERE id = v_id) NOT BETWEEN 1990 AND 2030 THEN
            SET v_errors = CONCAT(v_errors, 'Invalid year; ');
        END IF;
        
        -- Check kalori_hari range
        IF (SELECT kalori_hari FROM staging_nbm WHERE id = v_id) NOT BETWEEN 0 AND 1000 THEN
            SET v_errors = CONCAT(v_errors, 'Invalid calorie value; ');
        END IF;
        
        -- Update validation results
        UPDATE staging_nbm 
        SET validation_errors = v_errors,
            import_status = CASE 
                WHEN v_errors = '' THEN 'validated' 
                ELSE 'rejected' 
            END
        WHERE id = v_id;
        
    END LOOP;
    
    CLOSE cur;
END //
DELIMITER ;
```