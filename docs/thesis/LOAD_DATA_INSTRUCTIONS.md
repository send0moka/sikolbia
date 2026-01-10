# Instruksi Load Data Real NBM untuk Google Colab

Ada 3 cara untuk load data real NBM ke notebook:

---

## 🔧 Opsi 1: Export dari Database (RECOMMENDED)

### Langkah:

1. **Export data dari MySQL menggunakan query:**

```sql
SELECT 
    tn.tahun,
    tn.bulan,
    k.nama AS kelompok,
    km.nama AS komoditi,
    tn.bahan_makanan,
    tn.populasi_indonesia,
    -- Formula: bahan_makanan dalam TON → konversi ke gram → kalori per kapita per hari
    -- 1 ton = 1,000,000 gram, kalori_per_100g perlu dibagi 100
    ROUND(
        (tn.bahan_makanan * 10000 * km.kalori_per_100g) / (tn.populasi_indonesia * 365),
        2
    ) AS kalori_hari
FROM transaksi_nbms tn
JOIN kelompok k ON tn.kode_kelompok = k.kode
JOIN komoditi km ON tn.kode_komoditi = km.kode_komoditi
WHERE tn.validation_status = 'verified'
    AND km.nama = 'Beras'  -- Filter untuk 1 komoditi dulu
GROUP BY tn.tahun, tn.bulan, k.nama, km.nama, tn.bahan_makanan, tn.populasi_indonesia, km.kalori_per_100g
ORDER BY tn.tahun, tn.bulan;
```

**Catatan penting:**
- `bahan_makanan` dalam **ton**, jadi dikali 1,000,000 untuk konversi ke gram
- `kalori_per_100g` artinya per 100 gram, jadi dibagi 100
- Disederhanakan: `* 1000000 / 100 = * 10000`
- `GROUP BY` untuk menghilangkan duplicate rows

2. **Export ke CSV:**
```bash
mysql -u root -p sikolbia < query.sql > nbm_data.csv
```

Atau via phpMyAdmin: Export → Format CSV → OK

3. **Upload ke Google Colab:**

```python
from google.colab import files
uploaded = files.upload()  # Pilih file nbm_data.csv

df = pd.read_csv('nbm_data.csv')
df['tanggal'] = pd.to_datetime(df[['tahun', 'bulan']].assign(day=1))
```

---

## 🔧 Opsi 2: Connect Langsung ke Database

**WARNING**: Hanya jika database accessible dari internet atau running locally

```python
import mysql.connector
import pandas as pd

# Connect ke database
conn = mysql.connector.connect(
    host='localhost',  # atau IP server
    user='root',
    password='your_password',
    database='sikolbia'
)

# Query data
query = """
    SELECT 
        tn.tahun,
        tn.bulan,
        k.nama AS kelompok,
        km.nama AS komoditi,
        tn.bahan_makanan,
        tn.populasi_indonesia,
        ROUND(
            (tn.bahan_makanan * 10000 * km.kalori_per_100g) / (tn.populasi_indonesia * 365),
            2
        ) AS kalori_hari
    FROM transaksi_nbms tn
    JOIN kelompok k ON tn.kode_kelompok = k.kode
    JOIN komoditi km ON tn.kode_komoditi = km.kode_komoditi
    WHERE tn.validation_status = 'verified'
        AND km.nama = 'Beras'
    GROUP BY tn.tahun, tn.bulan, k.nama, km.nama, tn.bahan_makanan, tn.populasi_indonesia, km.kalori_per_100g
    ORDER BY tn.tahun, tn.bulan
"""

df = pd.read_sql(query, conn)
df['tanggal'] = pd.to_datetime(df[['tahun', 'bulan']].assign(day=1))
conn.close()
```

---

## 🔧 Opsi 3: Generate Sample Data (DEMO MODE)

Jika tidak ada akses ke database, gunakan synthetic data yang sudah ada di notebook.

**Catatan**: Data synthetic ini hanya untuk demo/presentasi, bukan hasil training real!

---

## 📊 Struktur Data yang Diharapkan

Setelah load, DataFrame harus punya kolom minimal:

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `tahun` | int | Tahun (1993-2024) |
| `bulan` | int | Bulan (1-12) |
| `tanggal` | datetime | Timestamp untuk plotting |
| `kelompok` | str | Kelompok komoditas (e.g., "Padi-padian") |
| `komoditi` | str | Nama komoditi (e.g., "Beras") |
| `kalori_hari` | float | Konsumsi kalori per kapita per hari |

### Contoh:
```
   tahun  bulan   tanggal      kelompok komoditi  kalori_hari
0   1993      1  1993-01-01  Padi-padian    Beras      2034.56
1   1993      2  1993-02-01  Padi-padian    Beras      2041.23
...
```

---

## 🎯 Quick Start untuk Pembimbing

Kalau mau cepat dan tidak ribet:

1. Export data Beras saja (1 komoditi) ke CSV
2. Upload CSV ke Colab
3. Replace bagian "Load Data" di notebook dengan:

```python
from google.colab import files
uploaded = files.upload()

df = pd.read_csv(list(uploaded.keys())[0])
df['tanggal'] = pd.to_datetime(df[['tahun', 'bulan']].assign(day=1))

print(f"✓ Data loaded: {len(df)} records")
print(f"  Periode: {df['tahun'].min()}-{df['tahun'].max()}")
df.head()
```

**Done!** Notebook akan langsung jalan dengan data real.

---

## ❓ Troubleshooting

### Error: "No module named 'mysql.connector'"
```bash
!pip install mysql-connector-python
```

### Error: Database connection refused
- Pastikan MySQL service running
- Cek host/port benar
- Atau pakai Opsi 1 (export CSV)

### Data terlalu besar untuk Colab
Filter dulu untuk 1 komoditi:
```sql
WHERE km.nama = 'Beras'  -- Hanya ambil 1 komoditi
```

---

**Rekomendasi**: Untuk demo pembimbing, pakai **Opsi 1 (CSV export)** karena paling simple dan reliable!
