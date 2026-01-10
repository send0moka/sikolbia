# Cara Load Data Real ke Google Colab Notebook

## ⚠️ PENTING: Query SQL Yang Benar

Data CSV yang sudah di-export **nilai kalori_hari-nya salah**! 

### Formula Yang Salah (sudah di-export):
```sql
(tn.bahan_makanan / tn.populasi_indonesia / 365) * km.kalori_per_100g
```
**Hasil**: 0.00014... (SALAH! Terlalu kecil)

### Formula Yang Benar:
```sql
ROUND(
    (tn.bahan_makanan * 10000 * km.kalori_per_100g) / (tn.populasi_indonesia * 365),
    2
) AS kalori_hari
```
**Hasil**: 1485.37 kkal/hari (BENAR!)

**Penjelasan:**
- `bahan_makanan` dalam **TON** (28,175 ton)
- Perlu konversi ke **GRAM**: 28,175 ton × 1,000,000 = 28,175,000,000 gram
- Per kapita per hari: 28,175,000,000 / 187,000,000 / 365 = 412.5 gram/hari
- Kalori: (412.5 / 100) × 360 = **1485 kkal/hari** ✓

---

## 🔄 Export Ulang Data dengan Query Benar

Jalankan query ini di phpMyAdmin atau MySQL:

```sql
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
ORDER BY tn.tahun, tn.bulan;
```

Export ke CSV → `beras_nbm_corrected.csv`

---

## 📊 Struktur Data Yang Diharapkan

Setelah export dengan query benar:

```
tahun,bulan,kelompok,komoditi,bahan_makanan,populasi_indonesia,kalori_hari
1993,1,Padi - Padian,Beras,28175.0000,187000000,1485.37
1993,2,Padi - Padian,Beras,28175.0000,187000000,1485.37
...
```

**Rentang nilai kalori_hari yang wajar**: 1000-2500 kkal/hari

---

## 🐍 Code untuk Google Colab

### Load dari CSV Upload:

```python
from google.colab import files
import pandas as pd

# Upload file CSV yang sudah di-export ulang
uploaded = files.upload()

# Load data
df = pd.read_csv(list(uploaded.keys())[0])

# Convert ke datetime
df['tanggal'] = pd.to_datetime(df[['tahun', 'bulan']].assign(day=1))

# Handle missing months (interpolasi linear)
df = df.set_index('tanggal').resample('MS').asfreq()
df[['tahun', 'bulan']] = df.index.year, df.index.month
df['kalori_hari'] = df['kalori_hari'].interpolate(method='linear')
df['kelompok'] = df['kelompok'].ffill()
df['komoditi'] = df['komoditi'].ffill()
df = df.reset_index(drop=False)

print(f"✓ Data loaded: {len(df)} records")
print(f"  Periode: {df['tahun'].min()}-{df['tahun'].max()}")
print(f"  Kalori min: {df['kalori_hari'].min():.2f} kkal/hari")
print(f"  Kalori max: {df['kalori_hari'].max():.2f} kkal/hari")
print(f"  Kalori mean: {df['kalori_hari'].mean():.2f} kkal/hari")

df.head()
```

### Atau Load dari Google Drive:

```python
from google.colab import drive
import pandas as pd

# Mount Google Drive
drive.mount('/content/drive')

# Load file dari Drive
df = pd.read_csv('/content/drive/MyDrive/beras_nbm_corrected.csv')
df['tanggal'] = pd.to_datetime(df[['tahun', 'bulan']].assign(day=1))

# Handle missing months
df = df.set_index('tanggal').resample('MS').asfreq()
df[['tahun', 'bulan']] = df.index.year, df.index.month
df['kalori_hari'] = df['kalori_hari'].interpolate(method='linear')
df['kelompok'] = df['kelompok'].ffill()
df['komoditi'] = df['komoditi'].ffill()
df = df.reset_index(drop=False)

print(f"✓ Data loaded: {len(df)} records")
df.head()
```

---

## 🔍 Validasi Data

Setelah load, cek apakah data sudah benar:

```python
# Check range
assert df['kalori_hari'].min() > 500, "Kalori terlalu rendah! Query salah?"
assert df['kalori_hari'].max() < 3000, "Kalori terlalu tinggi! Ada outlier?"

# Check continuity (harus ada semua bulan)
expected_months = (df['tahun'].max() - df['tahun'].min()) * 12
actual_months = len(df)
print(f"Expected months: {expected_months}")
print(f"Actual months: {actual_months}")
print(f"Missing: {expected_months - actual_months} months")

# Plot untuk visual check
import matplotlib.pyplot as plt

plt.figure(figsize=(14, 5))
plt.plot(df['tanggal'], df['kalori_hari'], linewidth=1)
plt.title('Konsumsi Kalori Beras Per Kapita Per Hari (1993-2024)')
plt.xlabel('Tahun')
plt.ylabel('Kalori (kkal/hari)')
plt.grid(True, alpha=0.3)
plt.tight_layout()
plt.show()
```

Jika plot menunjukkan nilai 1000-2000 kkal/hari → ✓ Data BENAR!  
Jika plot menunjukkan nilai < 1 → ❌ Data SALAH, perlu export ulang!

---

## 💡 Quick Fix untuk CSV Lama

Jika tidak mau export ulang, bisa fix di Python:

```python
# Load CSV lama yang salah
df = pd.read_csv('komoditi.csv')

# Fix formula (karena kalori_per_100g = 360 untuk beras)
# Data lama: bahan_makanan / populasi / 365 * 360
# Seharusnya: bahan_makanan * 10000 * 360 / (populasi * 365)
# Ratio: 10000 (konversi ton→gram dan /100 untuk per 100g)

df['kalori_hari'] = df['kalori_hari'] * 10000

print("✓ Kalori fixed!")
print(f"  New range: {df['kalori_hari'].min():.2f} - {df['kalori_hari'].max():.2f}")
```

**Tapi lebih baik export ulang dengan query yang benar!**
