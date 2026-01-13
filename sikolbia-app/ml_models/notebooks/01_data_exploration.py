"""
NBM Data Exploration & Feature Engineering
==========================================
Exploratory Data Analysis untuk prediksi konsumsi kalori harian
"""

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
import warnings
warnings.filterwarnings('ignore')

# Setup plotting style
plt.style.use('seaborn-v0_8-darkgrid')
sns.set_palette("husl")

print("=" * 60)
print("NBM DATA EXPLORATION - THESIS ML MODEL")
print("=" * 60)
print(f"Analysis Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
print()

# ============================================================================
# 1. DATA LOADING FROM DATABASE
# ============================================================================
print("Step 1: Loading data from MySQL...")

import mysql.connector
import os

# Database connection
conn = mysql.connector.connect(
    host=os.getenv('DB_HOST', 'mysql'),
    port=int(os.getenv('DB_PORT', 3306)),
    user=os.getenv('DB_USERNAME', 'sikolbia_user'),
    password=os.getenv('DB_PASSWORD', 'sikolbia_pass'),
    database=os.getenv('DB_DATABASE', 'sikolbia_db')
)

# Query with all features
query = """
SELECT 
    t.kode_kelompok,
    t.kode_komoditi,
    k.nama as nama_komoditi,
    t.tahun,
    t.bulan,
    t.bahan_makanan,
    t.keluaran as produksi,
    t.impor,
    t.ekspor,
    t.perubahan_stok,
    t.harga_konsumen,
    t.harga_produsen,
    t.populasi_indonesia,
    t.curah_hujan_mm,
    t.suhu_rata_celsius,
    t.luas_panen_ha,
    t.produktivitas_ton_ha,
    k.kalori_per_100g,
    k.protein_per_100g,
    k.lemak_per_100g
FROM transaksi_nbms t
INNER JOIN komoditi k 
    ON t.kode_kelompok = k.kode_kelompok 
    AND t.kode_komoditi = k.kode_komoditi
WHERE t.bahan_makanan > 0
ORDER BY t.tahun, t.bulan, k.nama
"""

df = pd.read_sql(query, conn)
conn.close()

# Filter out invalid dates (tahun=0 or bulan=0)
df = df[(df['tahun'] > 0) & (df['bulan'] > 0) & (df['bulan'] <= 12)]

print(f"✓ Loaded {len(df):,} records")
print(f"✓ Date range: {df['tahun'].min()}-{df['tahun'].max()}")
print(f"✓ Komoditi: {df['nama_komoditi'].nunique()} unique items")
print()

# ============================================================================
# 2. DATA QUALITY CHECK
# ============================================================================
print("Step 2: Data Quality Assessment")
print("-" * 60)

# Missing values
missing = df.isnull().sum()
missing_pct = (missing / len(df) * 100).round(2)
missing_df = pd.DataFrame({
    'Column': missing.index,
    'Missing': missing.values,
    'Percentage': missing_pct.values
}).query('Missing > 0').sort_values('Missing', ascending=False)

if len(missing_df) > 0:
    print("⚠ Missing Values:")
    print(missing_df.to_string(index=False))
else:
    print("✓ No missing values")

print()

# Data types and memory
print("Data Types:")
print(df.dtypes)
print(f"\nMemory Usage: {df.memory_usage(deep=True).sum() / 1024**2:.2f} MB")
print()

# ============================================================================
# 3. CREATE TARGET VARIABLE: KALORI PER CAPITA PER DAY
# ============================================================================
print("Step 3: Creating Target Variable - Kalori/Capita/Day")
print("-" * 60)

# Formula: (bahan_makanan_ribu_ton × 1,000 × 1,000,000 kg) / (populasi × 365)
# Then: gram_per_day / 100 × kalori_per_100g

df['gram_per_capita_per_day'] = (
    (df['bahan_makanan'] * 1000 * 1000000) / 
    (df['populasi_indonesia'] * 365)
).round(2)

df['kalori_per_capita_per_day'] = (
    (df['gram_per_capita_per_day'] / 100) * 
    df['kalori_per_100g']
).round(2)

df['protein_per_capita_per_day'] = (
    (df['gram_per_capita_per_day'] / 100) * 
    df['protein_per_100g']
).round(2)

print(f"✓ Target variable created")
print(f"  Mean kalori/capita/day: {df['kalori_per_capita_per_day'].mean():.2f}")
print(f"  Median: {df['kalori_per_capita_per_day'].median():.2f}")
print(f"  Std: {df['kalori_per_capita_per_day'].std():.2f}")
print(f"  Min: {df['kalori_per_capita_per_day'].min():.2f}")
print(f"  Max: {df['kalori_per_capita_per_day'].max():.2f}")
print()

# ============================================================================
# 4. TIME SERIES FEATURES
# ============================================================================
print("Step 4: Engineering Time Series Features")
print("-" * 60)

# Create datetime
df['date'] = pd.to_datetime(df['tahun'].astype(str) + '-' + df['bulan'].astype(str).str.zfill(2) + '-01')
df['year_month'] = df['date'].dt.to_period('M')

# Seasonal features
df['quarter'] = df['bulan'].apply(lambda x: (x-1)//3 + 1)
df['semester'] = df['bulan'].apply(lambda x: 1 if x <= 6 else 2)
df['is_harvest_season'] = df['bulan'].isin([3, 4, 5, 9, 10, 11]).astype(int)  # Musim panen
df['is_rainy_season'] = df['bulan'].isin([11, 12, 1, 2, 3]).astype(int)  # Musim hujan

# Cyclical encoding for month (untuk capture seasonality)
df['month_sin'] = np.sin(2 * np.pi * df['bulan'] / 12)
df['month_cos'] = np.cos(2 * np.pi * df['bulan'] / 12)

print("✓ Created time features:")
print("  - date, year_month, quarter, semester")
print("  - is_harvest_season, is_rainy_season")
print("  - month_sin, month_cos (cyclical encoding)")
print()

# ============================================================================
# 5. LAG FEATURES (untuk prediksi time series)
# ============================================================================
print("Step 5: Creating Lag Features for Time Series Prediction")
print("-" * 60)

# Sort by komoditi and date
df = df.sort_values(['kode_kelompok', 'kode_komoditi', 'tahun', 'bulan'])

# Create lag features per komoditi
for lag in [1, 3, 6, 12]:
    df[f'kalori_lag_{lag}'] = df.groupby(['kode_kelompok', 'kode_komoditi'])['kalori_per_capita_per_day'].shift(lag)
    df[f'bahan_makanan_lag_{lag}'] = df.groupby(['kode_kelompok', 'kode_komoditi'])['bahan_makanan'].shift(lag)

# Rolling statistics (moving average)
for window in [3, 6, 12]:
    df[f'kalori_ma_{window}'] = df.groupby(['kode_kelompok', 'kode_komoditi'])['kalori_per_capita_per_day'].transform(
        lambda x: x.rolling(window, min_periods=1).mean()
    )

# Growth rate (YoY)
df['kalori_growth_yoy'] = df.groupby(['kode_kelompok', 'kode_komoditi'])['kalori_per_capita_per_day'].pct_change(12) * 100

print("✓ Created lag features:")
print("  - kalori_lag_1, kalori_lag_3, kalori_lag_6, kalori_lag_12")
print("  - bahan_makanan_lag_1, lag_3, lag_6, lag_12")
print("  - kalori_ma_3, ma_6, ma_12 (moving averages)")
print("  - kalori_growth_yoy (year-over-year growth)")
print()

# ============================================================================
# 6. ECONOMIC INDICATORS
# ============================================================================
print("Step 6: Economic & Production Indicators")
print("-" * 60)

# Price ratio
df['price_margin'] = ((df['harga_konsumen'] - df['harga_produsen']) / df['harga_produsen'] * 100).round(2)

# Production efficiency
df['production_per_capita'] = (df['produksi'] * 1000000 / df['populasi_indonesia']).round(4)

# Import dependency ratio
df['import_ratio'] = (df['impor'] / (df['produksi'] + df['impor'] + 0.01) * 100).round(2)

# Export intensity
df['export_ratio'] = (df['ekspor'] / (df['produksi'] + 0.01) * 100).round(2)

print("✓ Created economic features:")
print("  - price_margin (consumer vs producer price)")
print("  - production_per_capita")
print("  - import_ratio, export_ratio")
print()

# ============================================================================
# 7. CRISIS/EVENT INDICATORS
# ============================================================================
print("Step 7: Historical Crisis Indicators")
print("-" * 60)

# Major economic/climate events
df['is_crisis_1998'] = ((df['tahun'] == 1998) | (df['tahun'] == 1999)).astype(int)
df['is_crisis_2008'] = ((df['tahun'] == 2008) | (df['tahun'] == 2009)).astype(int)
df['is_el_nino_2015'] = ((df['tahun'] == 2015) | (df['tahun'] == 2016)).astype(int)
df['is_pandemic'] = ((df['tahun'] >= 2020) & (df['tahun'] <= 2022)).astype(int)

print("✓ Created crisis indicators:")
print("  - is_crisis_1998 (Krisis Moneter)")
print("  - is_crisis_2008 (Global Financial Crisis)")
print("  - is_el_nino_2015 (Kekeringan)")
print("  - is_pandemic (COVID-19 2020-2022)")
print()

# ============================================================================
# 8. SUMMARY STATISTICS
# ============================================================================
print("Step 8: Summary Statistics")
print("=" * 60)

numeric_cols = df.select_dtypes(include=[np.number]).columns
summary = df[numeric_cols].describe()

print("\nKey Statistics:")
print(f"Total Records: {len(df):,}")
print(f"Date Range: {df['tahun'].min()}-{df['tahun'].max()}")
print(f"Time Points: {len(df['year_month'].unique())} months")
print(f"Komoditi: {df['nama_komoditi'].nunique()} items")
print(f"Average Kalori/Capita/Day: {df['kalori_per_capita_per_day'].mean():.2f} kcal")
print()

# Top consuming commodities
print("Top 10 Commodities by Calorie Contribution:")
top_komoditi = df.groupby('nama_komoditi')['kalori_per_capita_per_day'].mean().sort_values(ascending=False).head(10)
for i, (nama, kalori) in enumerate(top_komoditi.items(), 1):
    print(f"  {i:2d}. {nama:30s}: {kalori:6.2f} kcal/capita/day")
print()

# ============================================================================
# 9. EXPORT PROCESSED DATA
# ============================================================================
print("Step 9: Exporting Processed Data")
print("-" * 60)

# Export full dataset
output_path = 'ml_models/data/nbm_processed.csv'
df.to_csv(output_path, index=False)
print(f"✓ Exported to: {output_path}")

# Export train/val/test splits
train_df = df[df['tahun'] <= 2020]
val_df = df[(df['tahun'] > 2020) & (df['tahun'] <= 2022)]
test_df = df[df['tahun'] > 2022]

train_df.to_csv('ml_models/data/nbm_train.csv', index=False)
val_df.to_csv('ml_models/data/nbm_val.csv', index=False)
test_df.to_csv('ml_models/data/nbm_test.csv', index=False)

print(f"  - Train: {len(train_df):,} records (1993-2020)")
print(f"  - Val:   {len(val_df):,} records (2021-2022)")
print(f"  - Test:  {len(test_df):,} records (2023-2024)")
print()

# ============================================================================
# 10. FEATURE IMPORTANCE PREVIEW
# ============================================================================
print("Step 10: Feature List for Model Training")
print("=" * 60)

feature_cols = [
    # Time features
    'bulan', 'quarter', 'semester', 'is_harvest_season', 'is_rainy_season',
    'month_sin', 'month_cos',
    
    # Lag features
    'kalori_lag_1', 'kalori_lag_3', 'kalori_lag_6', 'kalori_lag_12',
    'bahan_makanan_lag_1', 'bahan_makanan_lag_3', 'bahan_makanan_lag_6', 'bahan_makanan_lag_12',
    
    # Rolling features
    'kalori_ma_3', 'kalori_ma_6', 'kalori_ma_12',
    'kalori_growth_yoy',
    
    # Economic features
    'harga_konsumen', 'harga_produsen', 'price_margin',
    'produksi', 'impor', 'ekspor', 'import_ratio', 'export_ratio',
    'production_per_capita',
    
    # Climate features
    'curah_hujan_mm', 'suhu_rata_celsius',
    
    # Production features
    'luas_panen_ha', 'produktivitas_ton_ha',
    
    # Nutrition features
    'kalori_per_100g', 'protein_per_100g',
    
    # Population
    'populasi_indonesia',
    
    # Crisis indicators
    'is_crisis_1998', 'is_crisis_2008', 'is_el_nino_2015', 'is_pandemic'
]

print(f"Total Features: {len(feature_cols)}")
print("\nFeature Categories:")
print(f"  - Time/Seasonal: 7")
print(f"  - Lag/Rolling: 16")
print(f"  - Economic: 9")
print(f"  - Climate: 2")
print(f"  - Production: 2")
print(f"  - Nutrition: 2")
print(f"  - Population: 1")
print(f"  - Crisis: 4")
print()

print("=" * 60)
print("✅ DATA EXPLORATION COMPLETE!")
print("=" * 60)
print("\nNext Steps:")
print("  1. Run model training: python 02_model_training.py")
print("  2. Evaluate models: python 03_model_evaluation.py")
print("  3. Generate predictions: python 04_predictions.py")
print()
print(f"Ready for ML Model Development! 🚀")
