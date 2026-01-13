"""
Fix lag and moving average features in nbm_processed_fixed.csv
Root cause: Main kalori column was corrected (×12) but lag/MA features still use old values
"""

import pandas as pd
import numpy as np

print("=" * 80)
print("FIX LAG & MOVING AVERAGE FEATURES")
print("=" * 80)

# Load data
csv_path = "data/nbm_processed_fixed.csv"
print(f"\nLoading: {csv_path}")
df = pd.read_csv(csv_path)
print(f"✓ Loaded {len(df):,} rows")

# Check current values
print(f"\nBefore fix:")
print(f"  kalori_per_capita_per_day (main): {df['kalori_per_capita_per_day'].head(3).tolist()}")
print(f"  kalori_lag_1: {df['kalori_lag_1'].head(5).tolist()}")
print(f"  kalori_ma_3: {df['kalori_ma_3'].head(5).tolist()}")

# Sort by commodity and date to ensure proper lag calculation
df = df.sort_values(['kode_kelompok', 'kode_komoditi', 'tahun', 'bulan']).reset_index(drop=True)

# Recalculate lag features
print("\nRecalculating lag features...")
lag_cols = {
    'kalori_lag_1': 1,
    'kalori_lag_3': 3,
    'kalori_lag_6': 6,
    'kalori_lag_12': 12,
    'bahan_makanan_lag_1': 1,
    'bahan_makanan_lag_3': 3
}

for col, lag in lag_cols.items():
    if 'kalori' in col:
        source_col = 'kalori_per_capita_per_day'
    else:
        source_col = 'bahan_makanan'
    
    df[col] = df.groupby(['kode_kelompok', 'kode_komoditi'])[source_col].shift(lag)
    print(f"  ✓ {col}")

# Recalculate moving averages
print("\nRecalculating moving averages...")
ma_windows = {
    'kalori_ma_3': 3,
    'kalori_ma_6': 6,
    'kalori_ma_12': 12
}

for col, window in ma_windows.items():
    df[col] = df.groupby(['kode_kelompok', 'kode_komoditi'])['kalori_per_capita_per_day'].transform(
        lambda x: x.rolling(window=window, min_periods=1).mean()
    )
    print(f"  ✓ {col}")

# Recalculate YoY growth (should use current vs lag_12)
print("\nRecalculating YoY growth...")
df['kalori_growth_yoy'] = df.groupby(['kode_kelompok', 'kode_komoditi'])['kalori_per_capita_per_day'].pct_change(12) * 100
print(f"  ✓ kalori_growth_yoy")

# Check after fix
print(f"\nAfter fix:")
sample = df[df['nama_komoditi'] == 'Beras'].head(15)
print(f"\nBeras first 15 months:")
print(sample[['tahun', 'bulan', 'kalori_per_capita_per_day', 'kalori_lag_1', 'kalori_ma_3', 'kalori_ma_6']].to_string(index=False))

# Verify major commodities
print(f"\n" + "=" * 80)
print("VERIFICATION - Major Commodities")
print("=" * 80)
for komoditi in ['Beras', 'Jagung', 'Gula Pasir', 'Kedelai']:
    subset = df[df['nama_komoditi'] == komoditi]
    avg_main = subset['kalori_per_capita_per_day'].mean()
    avg_lag1 = subset['kalori_lag_1'].mean()
    avg_ma3 = subset['kalori_ma_3'].mean()
    print(f"\n{komoditi}:")
    print(f"  Main kalori: {avg_main:.2f} kkal/kapita/hari")
    print(f"  Lag-1 avg:   {avg_lag1:.2f} kkal/kapita/hari")
    print(f"  MA-3 avg:    {avg_ma3:.2f} kkal/kapita/hari")
    
    # Check if they're close (should be similar after lag adjustment)
    if abs(avg_main - avg_lag1) > avg_main * 0.1:  # More than 10% difference
        print(f"  ⚠ WARNING: Large gap between main and lag!")
    else:
        print(f"  ✓ Lag features look correct")

# Save fixed CSV
output_path = "data/nbm_processed_fixed_v2.csv"
df.to_csv(output_path, index=False)
print(f"\n" + "=" * 80)
print(f"✓ Fixed CSV saved: {output_path}")
print(f"  Size: {len(df):,} rows × {len(df.columns)} columns")
print("=" * 80)

print("\n🔥 Upload file ini ke Google Colab: nbm_processed_fixed_v2.csv")
