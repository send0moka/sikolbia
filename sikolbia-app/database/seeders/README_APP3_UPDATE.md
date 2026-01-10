# Update Data NBM SIKOLBIA dari APP3 Pusdatin

## Overview
Script ini memperbarui data transaksi NBM (Neraca Bahan Makanan) di database SIKOLBIA menggunakan data resmi dari **APP3 (Aplikasi Neraca Bahan Makanan) - Pusdatin Kementerian Pertanian**.

### Data yang sudah diperbaiki
- ✅ **Beras (01-0101)**: 1993-2024 (34 records tahunan)

### Perbandingan Data SIKOLBIA vs APP3

| Tahun | Metrik | SIKOLBIA (lama) | APP3 (resmi) | Status |
|-------|--------|----------------|--------------|--------|
| 1993 | Keluaran (Produksi) | 2,429 | 28,750 ribu ton | ❌ Error magnitude 10x |
| 1993 | Tercecer (Waste) | 7,220 | 722 ribu ton | ❌ Error decimal placement |
| 1993 | Bahan Makanan | 281,750 | 28,175 ribu ton | ❌ Error decimal placement |
| 1993 | Kalori/Kapita/Hari | 14,860.4 | 1,481.00 | ❌ Error calculation |

## File Structure

```
database/
└── seeders/
    ├── generate_nbm_from_app3.php         # Generator script
    ├── transaksi_nbms_beras_app3.sql      # Generated SQL (408 records)
    ├── TransaksiNbmSeeder.php             # Main seeder (updated)
    └── README_APP3_UPDATE.md              # This file
```

## Cara Penggunaan

### 1. Generate SQL dari CSV APP3 baru (opsional)
Jika Anda punya raw data APP3 lainnya:

```bash
# Edit file CSV path di generate_nbm_from_app3.php line 8
# $inputCsv = 'C:\\Users\\jehia\\Downloads\\raw-beras-nbm-app3.csv';

php database/seeders/generate_nbm_from_app3.php
```

Output: `transaksi_nbms_beras_app3.sql` (34 records tahunan)

### 2. Jalankan Seeder (Update Database)

**PENTING**: Seeder ini akan:
- ❌ TIDAK truncate seluruh tabel
- ✅ Hanya menghapus data Beras (01-0101) lama
- ✅ Insert 34 records tahunan dari APP3 (1993-2024)

```bash
# Pastikan database sudah running (Docker atau local)
docker-compose up -d mysql

# Atau jika local MySQL
# mysql -u root -p

# Jalankan seeder
php artisan db:seed --class=TransaksiNbmSeeder
```

Expected output:
```
Updating TransaksiNbm data with APP3 Pusdatin official data...
Deleted old Beras records
Inserted 34 records so far...
Successfully updated 34 Beras NBM records from APP3 Pusdatin

Data source: APP3 (Aplikasi Neraca Bahan Makanan) - Pusdatin Kementerian Pertanian
Coverage: Beras (01-0101) from 1993-2024 with TAHUNAN granularity (ribu ton)
```

### 3. Verifikasi Data

```bash
php artisan tinker
```

```php
// Check total Beras records
DB::table('transaksi_nbms')
    ->where('kode_kelompok', '01')
    ->where('kode_komoditi', '0101')
    ->count();
// Expected: 34 (1 record per year 1993-2024, tahunan)

// Check 1993 data (should match APP3)
DB::table('transaksi_nbms')
    ->where('kode_kelompok', '01')
    ->where('kode_komoditi', '0101')
    ->where('tahun', 1993)
    ->first();
// Expected masukan: 44230.00 (44,230 ribu ton)
// Expected keluaran: 28750.00 (28,750 ribu ton)
// Expected tercecer: 722.00 (722 ribu ton)
// Expected bahan_makanan: 28175.00 (28,175 ribu ton)
// Expected bulan: 0 (tahunan)
// Expected kuartal: 0 (tahunan)
// Expected periode_data: 'tahunan'
// Expected data_source: 'APP3 Pusdatin'

// Check data source
DB::table('transaksi_nbms')
    ->where('data_source', 'APP3 Pusdatin')
    ->count();
// Expected: 34
```

## Struktur Data APP3

### Input CSV Format
```csv
Nama Kelompok,Nama komoditi,Tahun,Masukan,Keluaran,Impor,Ekpor,Perubahan stok,...
Padi - Padian,Beras,1993,44230,28750,24,351,-474,0,0,0,0,722,,28175,...
```

### Column Mapping

| CSV APP3 | Database SIKOLBIA | Keterangan |
|----------|-------------------|------------|
| Masukan | `masukan` | Gabah/padi input (ton) |
| Keluaran | `keluaran` | Produksi beras (ton) |
| Impor | `impor` | Import (ton) |
| Ekpor | `ekspor` | Export (ton) |
| Perubahan stok | `perubahan_stok` | Stock changes (ton) |
| Pakan | `pakan` | Animal feed (ton) |
| Bibit | `bibit` | Seeds (ton) |
| Makanan | `makanan` | Direct consumption (ton) |
| Bukan makanan | `bukan_makanan` | Non-food use (ton) |
| Tercecer | `tercecer` | Waste (ton) |
| Penggunaan lain | `penggunaan_lain` | Other use (ton) |
| Bahan makanan | `bahan_makanan` | Food material (ton) |
| Status angka | `status_angka` | 0=tetap, 1=sementara, 2=sangat sementara |
disimpan sebagai **TAHUNAN** (`bulan=0`, `kuartal=0`, `periode_data='tahunan'`)
- Satuan: **RIBU TON** (000 ton) sesuai header APP3
**Note**: 
- Section C (Kg tahun, Gram hari, Kalori hari) **TIDAK disimpan** di database
- Kalori/Kapita/Hari dihitung otomatis menggunakan formula NBM
- Data tahunan dibagi 12 untuk mendapatkan nilai bulanan

## Formula Kalori/Kapita/Hari

```
Kalori/Kapita/Hari = (Bahan_Makanan_kg / Populasi / 365) × Kalori_per_100g / 100
```

Contoh untuk Beras 1993:
```
Bahan Makanan = 28,175 ribu ton = 28,175,000 ton = 28,175,000,000 kg
Populasi 1993 = 187,000,000 jiwa

Kalori/Kapita/Hari = (28,175,000,000 kg / 187,000,000 / 365) × 360 / 100
                    = 411.49 kg/tahun × 360 / 100
                    = 1,481.00 kkal/kapita/hari ✓
```

## Backup Strategy

Sebelum run seeder di production:

```bash
# 1. Backup current Beras data
php artisan tinker
DB::table('transaksi_nbms')
    ->where('kode_kelompok', '01')
    ->where('kode_komoditi', '0101')
    ->get()
    ->toJson(JSON_PRETTY_PRINT)
    | file_put_contents('backup_beras_' . date('Y-m-d') . '.json', $_);

# 2. Or full table dump
mysqldump -u sikolbia_user -p sikolbia_db transaksi_nbms > backup_transaksi_nbms_$(date +%Y%m%d).sql
```

## Troubleshooting

### Error: "Could not open file transaksi_nbms_beras_app3.sql"
```bash
# Generate SQL file dulu
php database/seeders/generate_nbm_from_app3.php
```

### Error: "No such host is known (mysql)"
```bash
# Start database dulu
docker-compose up -d mysql

# Atau update .env untuk local MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
```

### Data tidak sesuai setelah seeding
```bash
# Check data_source field
php artisan tinker
DB::table('transaksi_nbms')
    ->where('kode_kelompok', '01')
    ->where('kode_komoditi', '0101')
    ->where('data_source', '!=', 'APP3 Pusdatin')
    ->count();
// Should be 0

# If not 0, run seeder again
php artisan db:seed --class=TransaksiNbmSeeder
```

## Roadmap

### Data yang sudah diperbaiki
- [x] Beras (01-0101): 1993-2024 (34 records tahunan)

### Data yang belum diperbaiki (masih dari SIKOLBIA lama)
- [ ] Jagung (01-0102)
- [ ] Kedelai (02-0201)
- [ ] Gula (03-0301)
- [ ] Minyak Goreng (04-0401)
- [ ] Daging Sapi (05-0501)
- [ ] Daging Ayam (05-0502)
- [ ] Telur (06-0601)
- [ ] Susu (07-0701)
- [ ] ... (dst, total 72 komoditi)

**Next steps**:
1. Dapatkan raw CSV APP3 untuk komoditi lainnya
2. Update `generate_nbm_from_app3.php` untuk handle multi-komoditi
3. Generate SQL baru
4. Run seeder

## References

- **Data Source**: [APP3 Pusdatin Kementerian Pertanian](https://pusdatin.pertanian.go.id/)
- **NBM Formula**: Pedoman Penyusunan Neraca Bahan Makanan, BPS 2020
- **Thesis**: [Laporan Tugas Akhir](../../docs/thesis/laporan_tugas_akhir.md)
- **Integration Docs**: [NBM API Integration](../../docs/ml-integration/)

## Contact

Untuk pertanyaan atau issue terkait data APP3:
- Create issue di repository
- Referensi official: Pusdatin Kementerian Pertanian
