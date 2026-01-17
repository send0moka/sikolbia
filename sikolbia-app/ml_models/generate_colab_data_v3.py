"""
Generate NBM data for Google Colab training
Pulls from database after new monthly distribution strategy (month 7 = remainder)
Output: nbm_processed_fixed_v3.csv
"""

import pandas as pd
import numpy as np
import mysql.connector
from datetime import datetime
import os
from pathlib import Path

print("=" * 80)
print("GENERATE NBM DATA FOR GOOGLE COLAB (v3)")
print("Strategy: Monthly distribution with month 7 (Juli) as remainder")
print("=" * 80)

# Read Laravel .env file for database credentials
env_path = Path(__file__).parent.parent / '.env'
db_config = {
    'host': 'localhost',
    'port': 3306,
    'user': 'sikolbia_user',
    'password': 'sikolbia_pass',
    'database': 'sikolbia_db'
}

if env_path.exists():
    print(f"\nReading database config from: {env_path}")
    with open(env_path, 'r', encoding='utf-8') as f:
        for line in f:
            line = line.strip()
            if line.startswith('DB_HOST='):
                db_config['host'] = line.split('=', 1)[1].strip()
            elif line.startswith('DB_PORT='):
                db_config['port'] = int(line.split('=', 1)[1].strip())
            elif line.startswith('DB_USERNAME='):
                db_config['user'] = line.split('=', 1)[1].strip()
            elif line.startswith('DB_PASSWORD='):
                db_config['password'] = line.split('=', 1)[1].strip()
            elif line.startswith('DB_DATABASE='):
                db_config['database'] = line.split('=', 1)[1].strip()
    print(f"  ✓ Database: {db_config['database']}")
    print(f"  ✓ User: {db_config['user']}")
    print(f"  ✓ Host: {db_config['host']}")

# Database connection
print("\nConnecting to database...")
try:
    # Try Docker host first (if running in container or DB_HOST=mysql)
    if db_config['host'] == 'mysql':
        conn = mysql.connector.connect(**db_config)
        print("✓ Connected to MySQL (Docker)")
    else:
        # Try localhost/127.0.0.1 for local development
        conn = mysql.connector.connect(**db_config)
        print("✓ Connected to MySQL (Local)")
except Exception as e:
    print(f"✗ Connection failed with {db_config['host']}: {e}")
    
    # Fallback: try alternative hosts
    fallback_hosts = ['127.0.0.1', 'localhost', 'mysql']
    for fallback_host in fallback_hosts:
        if fallback_host == db_config['host']:
            continue
        print(f"\nTrying fallback host: {fallback_host}...")
        try:
            db_config['host'] = fallback_host
            conn = mysql.connector.connect(**db_config)
            print(f"✓ Connected to MySQL ({fallback_host})")
            break
        except Exception as e2:
            print(f"✗ Failed with {fallback_host}: {e2}")
    else:
        print("\n❌ All connection attempts failed!")
        print("\nPlease check:")
        print("  1. MySQL service is running")
        print("  2. Database credentials in .env are correct")
        print("  3. Database 'sikolbia_db' exists")
        exit(1)

# Pull monthly data (bulan BETWEEN 1 AND 12)
query = """
SELECT 
    t.kode_kelompok,
    t.kode_komoditi,
    k.nama as nama_komoditi,
    t.tahun,
    t.bulan,
    t.kuartal,
    t.periode_data,
    t.status_angka,
    t.masukan,
    t.keluaran,
    t.impor,
    t.ekspor,
    t.perubahan_stok,
    t.pakan,
    t.bibit,
    t.makanan,
    t.bukan_makanan,
    t.tercecer,
    t.penggunaan_lain,
    t.bahan_makanan,
    t.harga_produsen,
    t.harga_konsumen,
    t.inflasi_komoditi,
    t.nilai_tukar_usd,
    t.populasi_indonesia,
    t.gdp_per_kapita,
    t.tingkat_kemiskinan,
    t.curah_hujan_mm,
    t.suhu_rata_celsius,
    t.indeks_el_nino,
    t.luas_panen_ha,
    t.produktivitas_ton_ha,
    t.kebijakan_impor,
    t.subsidi_pemerintah,
    t.stok_bulog,
    t.confidence_score,
    t.validation_status,
    t.data_source,
    t.outlier_flag,
    k.kalori_per_100g,
    k.protein_per_100g,
    k.lemak_per_100g,
    k.karbohidrat_per_100g
FROM transaksi_nbms t
LEFT JOIN komoditi k ON t.kode_kelompok = k.kode_kelompok 
    AND t.kode_komoditi = k.kode_komoditi
WHERE t.bulan BETWEEN 1 AND 12
    AND t.periode_data = 'bulanan'
    AND t.tahun >= 1994
ORDER BY t.tahun, t.bulan, t.kode_kelompok, t.kode_komoditi
"""

print("\nFetching monthly data from database...")
df = pd.read_sql(query, conn)
conn.close()

print(f"✓ Fetched {len(df):,} monthly records")
print(f"  Date range: {df['tahun'].min()}-{df['tahun'].max()}")
print(f"  Months: {sorted(df['bulan'].unique())}")
print(f"  Komoditi: {df['kode_komoditi'].nunique()}")

# Calculate kalori per capita per day
print("\nCalculating kalori per capita per day...")

# Formula: (bahan_makanan tons * kalori_per_100g) / populasi / 365 days * 10^8
# bahan_makanan is in ribu ton (thousands of tons)
# Convert: ribu ton → kg → grams → per capita → per day
df['kalori_per_capita_per_day'] = (
    df['bahan_makanan'] * 1000 * 1000 * 1000 *  # ribu ton → grams
    df['kalori_per_100g'] / 100 /                 # per 100g → per gram
    df['populasi_indonesia'] / 365                # per capita per day
)

# Handle missing/invalid values
df['kalori_per_capita_per_day'] = df['kalori_per_capita_per_day'].fillna(0)
df['kalori_per_capita_per_day'] = df['kalori_per_capita_per_day'].replace([np.inf, -np.inf], 0)

print(f"✓ Calculated kalori_per_capita_per_day")
print(f"  Range: {df['kalori_per_capita_per_day'].min():.2f} - {df['kalori_per_capita_per_day'].max():.2f}")
print(f"  Mean: {df['kalori_per_capita_per_day'].mean():.2f} kkal/kapita/hari")

# Verify month 7 distribution strategy
print("\n" + "=" * 80)
print("VERIFICATION: Month 7 (Juli) Distribution Strategy")
print("=" * 80)

sample_komoditi = df[
    (df['kode_komoditi'] == '0101') & 
    (df['tahun'] == 2024)
][['bulan', 'keluaran', 'bahan_makanan']].sort_values('bulan')

if len(sample_komoditi) > 0:
    print("\nGabah (0101) - 2024:")
    print(sample_komoditi.to_string(index=False))
    print(f"\nTotal keluaran: {sample_komoditi['keluaran'].sum():.4f}")
    print(f"Total bahan_makanan: {sample_komoditi['bahan_makanan'].sum():.4f}")
    
    # Check if month 7 is different from others (not exactly 1/12 of total)
    avg_keluaran = sample_komoditi['keluaran'].mean()
    july_keluaran = sample_komoditi[sample_komoditi['bulan'] == 7]['keluaran'].values
    if len(july_keluaran) > 0:
        july_val = july_keluaran[0]
        if abs(july_val - avg_keluaran) > avg_keluaran * 0.05:  # More than 5% different
            print(f"\n✓ Month 7 distribution strategy confirmed!")
            print(f"  July value: {july_val:.4f}")
            print(f"  Average: {avg_keluaran:.4f}")
            print(f"  Difference: {abs(july_val - avg_keluaran):.4f} ({abs(july_val - avg_keluaran)/avg_keluaran*100:.1f}%)")
        else:
            print(f"\n⚠ WARNING: Month 7 looks like equal distribution!")
            print(f"  July value: {july_val:.4f}")
            print(f"  Average: {avg_keluaran:.4f}")

# Sort data properly for lag features
print("\nSorting data for time series features...")
df = df.sort_values(['kode_kelompok', 'kode_komoditi', 'tahun', 'bulan']).reset_index(drop=True)
print("✓ Sorted by commodity and date")

# Create lag features
print("\nCreating lag features...")
lag_configs = {
    'kalori_lag_1': ('kalori_per_capita_per_day', 1),
    'kalori_lag_3': ('kalori_per_capita_per_day', 3),
    'kalori_lag_6': ('kalori_per_capita_per_day', 6),
    'kalori_lag_12': ('kalori_per_capita_per_day', 12),
    'bahan_makanan_lag_1': ('bahan_makanan', 1),
    'bahan_makanan_lag_3': ('bahan_makanan', 3),
}

for col_name, (source_col, lag) in lag_configs.items():
    df[col_name] = df.groupby(['kode_kelompok', 'kode_komoditi'])[source_col].shift(lag)
    print(f"  ✓ {col_name} (lag={lag})")

# Create moving averages
print("\nCreating moving average features...")
ma_configs = {
    'kalori_ma_3': 3,
    'kalori_ma_6': 6,
    'kalori_ma_12': 12,
}

for col_name, window in ma_configs.items():
    df[col_name] = df.groupby(['kode_kelompok', 'kode_komoditi'])['kalori_per_capita_per_day'].transform(
        lambda x: x.rolling(window=window, min_periods=1).mean()
    )
    print(f"  ✓ {col_name} (window={window})")

# Create YoY growth
print("\nCreating year-over-year growth...")
df['kalori_growth_yoy'] = df.groupby(['kode_kelompok', 'kode_komoditi'])['kalori_per_capita_per_day'].pct_change(12) * 100
print("  ✓ kalori_growth_yoy")

# Create seasonal indicators (cyclical encoding)
print("\nCreating seasonal indicators...")
df['bulan_sin'] = np.sin(2 * np.pi * df['bulan'] / 12)
df['bulan_cos'] = np.cos(2 * np.pi * df['bulan'] / 12)
df['kuartal_sin'] = np.sin(2 * np.pi * df['kuartal'] / 4)
df['kuartal_cos'] = np.cos(2 * np.pi * df['kuartal'] / 4)
print("  ✓ Cyclical month and quarter encoding")

# Verify sample
print("\n" + "=" * 80)
print("SAMPLE DATA - Beras (first 15 months)")
print("=" * 80)
beras = df[df['nama_komoditi'] == 'Beras'].head(15)
print(beras[['tahun', 'bulan', 'bahan_makanan', 'kalori_per_capita_per_day', 'kalori_lag_1', 'kalori_ma_3']].to_string(index=False))

# Data quality checks
print("\n" + "=" * 80)
print("DATA QUALITY CHECKS")
print("=" * 80)

print(f"\nMissing values per column:")
missing = df.isnull().sum()
missing = missing[missing > 0]
if len(missing) > 0:
    for col, count in missing.items():
        print(f"  {col}: {count:,} ({count/len(df)*100:.1f}%)")
else:
    print("  ✓ No missing values")

print(f"\nInfinite values:")
inf_cols = []
for col in df.select_dtypes(include=[np.number]).columns:
    inf_count = np.isinf(df[col]).sum()
    if inf_count > 0:
        inf_cols.append((col, inf_count))
        
if len(inf_cols) > 0:
    for col, count in inf_cols:
        print(f"  {col}: {count:,}")
else:
    print("  ✓ No infinite values")

print(f"\nZero kalori records:")
zero_kalori = (df['kalori_per_capita_per_day'] == 0).sum()
print(f"  {zero_kalori:,} records ({zero_kalori/len(df)*100:.1f}%)")

# Summary statistics
print("\n" + "=" * 80)
print("SUMMARY STATISTICS")
print("=" * 80)
print(f"\nTotal records: {len(df):,}")
print(f"Date range: {df['tahun'].min()}-{df['bulan'].min():02d} to {df['tahun'].max()}-{df['bulan'].max():02d}")
print(f"Unique komoditi: {df['kode_komoditi'].nunique()}")
print(f"Total columns: {len(df.columns)}")

print(f"\nKalori per capita per day:")
print(f"  Min: {df['kalori_per_capita_per_day'].min():.2f}")
print(f"  Max: {df['kalori_per_capita_per_day'].max():.2f}")
print(f"  Mean: {df['kalori_per_capita_per_day'].mean():.2f}")
print(f"  Median: {df['kalori_per_capita_per_day'].median():.2f}")
print(f"  Std: {df['kalori_per_capita_per_day'].std():.2f}")

# Save to CSV
output_path = "data/nbm_processed_fixed_v3.csv"
print(f"\n" + "=" * 80)
print(f"Saving to: {output_path}")
df.to_csv(output_path, index=False)
print(f"✓ Saved {len(df):,} rows × {len(df.columns)} columns")
print("=" * 80)

print("\n🔥 READY FOR GOOGLE COLAB!")
print(f"   Upload file: {output_path}")
print(f"   Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
print("\n✅ Data generated with new monthly distribution strategy (month 7 = remainder)")
