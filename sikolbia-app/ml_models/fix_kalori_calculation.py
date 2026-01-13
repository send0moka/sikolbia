"""
Fix Kalori Calculation - Convert dari per tahun (365 hari) ke per bulan (30.4375 hari)
=======================================================================================

Problem: Data bahan_makanan sudah MONTHLY (dibagi 12 di seeder),
         tapi formula masih pakai 365 hari (yearly assumption)

Solution: Gunakan 30.4375 hari (365.25 / 12) untuk data bulanan
"""

import pandas as pd
import numpy as np

print("=" * 80)
print("FIX KALORI CALCULATION - MONTHLY DATA")
print("=" * 80)

# Load existing data
input_file = 'ml_models/data/nbm_processed.csv'
output_file = 'ml_models/data/nbm_processed_fixed.csv'

print(f"\n1. Loading data from: {input_file}")
df = pd.read_csv(input_file)
print(f"   ✓ Loaded {len(df):,} rows")

# Display current stats
print(f"\n2. Current kalori statistics (WRONG - using 365 days):")
print(f"   Mean: {df['kalori_per_capita_per_day'].mean():.2f} kkal")
print(f"   Median: {df['kalori_per_capita_per_day'].median():.2f} kkal")
print(f"   Min: {df['kalori_per_capita_per_day'].min():.2f} kkal")
print(f"   Max: {df['kalori_per_capita_per_day'].max():.2f} kkal")
print(f"   < 10 kkal: {(df['kalori_per_capita_per_day'] < 10).sum():,} records ({(df['kalori_per_capita_per_day'] < 10).sum() / len(df) * 100:.1f}%)")

# Fix calculation
print(f"\n3. Recalculating with MONTHLY assumption (30.4375 days)...")

DAYS_PER_MONTH_AVG = 365.25 / 12  # 30.4375
DAYS_PER_YEAR = 365

# Multiplier to fix: current uses 365, should use 30.4375
# New = Old × (365 / 30.4375)
CORRECTION_FACTOR = DAYS_PER_YEAR / DAYS_PER_MONTH_AVG  # ~12

print(f"   Correction factor: {CORRECTION_FACTOR:.4f}x")

# Recalculate gram_per_capita_per_day
df['gram_per_capita_per_day_old'] = df['gram_per_capita_per_day'].copy()
df['gram_per_capita_per_day'] = (
    (df['bahan_makanan'] * 1000 * 1000000) / 
    (df['populasi_indonesia'] * DAYS_PER_MONTH_AVG)
).round(2)

# Recalculate kalori_per_capita_per_day
df['kalori_per_capita_per_day_old'] = df['kalori_per_capita_per_day'].copy()
df['kalori_per_capita_per_day'] = (
    (df['gram_per_capita_per_day'] / 100) * 
    df['kalori_per_100g']
).round(2)

# Recalculate protein_per_capita_per_day
df['protein_per_capita_per_day_old'] = df['protein_per_capita_per_day'].copy()
df['protein_per_capita_per_day'] = (
    (df['gram_per_capita_per_day'] / 100) * 
    df['protein_per_100g']
).round(2)

print(f"   ✓ Recalculation complete")

# Display new stats
print(f"\n4. New kalori statistics (CORRECT - using 30.4375 days):")
print(f"   Mean: {df['kalori_per_capita_per_day'].mean():.2f} kkal")
print(f"   Median: {df['kalori_per_capita_per_day'].median():.2f} kkal")
print(f"   Min: {df['kalori_per_capita_per_day'].min():.2f} kkal")
print(f"   Max: {df['kalori_per_capita_per_day'].max():.2f} kkal")
print(f"   < 10 kkal: {(df['kalori_per_capita_per_day'] < 10).sum():,} records ({(df['kalori_per_capita_per_day'] < 10).sum() / len(df) * 100:.1f}%)")

# Comparison examples
print(f"\n5. Sample comparisons (Old vs New):")
samples = df[df['nama_komoditi'].isin(['Beras', 'Jagung', 'Gula Pasir', 'Kedelai', 'Ubi Kayu'])].groupby('nama_komoditi').first()
for komoditi in samples.index:
    old = samples.loc[komoditi, 'kalori_per_capita_per_day_old']
    new = samples.loc[komoditi, 'kalori_per_capita_per_day']
    print(f"   {komoditi:20s}: {old:8.2f} → {new:8.2f} kkal ({new/old:6.2f}x)")

# Check major commodities
print(f"\n6. Major commodities average kalori (should be reasonable now):")
major_komoditi = ['Beras', 'Jagung', 'Gula Pasir', 'Kedelai', 'Ubi Kayu', 'Kentang', 'Gula Mangkok']
for k in major_komoditi:
    if k in df['nama_komoditi'].values:
        avg_kalori = df[df['nama_komoditi'] == k]['kalori_per_capita_per_day'].mean()
        print(f"   {k:20s}: {avg_kalori:8.2f} kkal/kapita/hari")

# Save fixed data
print(f"\n7. Saving fixed data...")

# Drop old columns before saving
df_save = df.drop(columns=['gram_per_capita_per_day_old', 'kalori_per_capita_per_day_old', 'protein_per_capita_per_day_old'])

df_save.to_csv(output_file, index=False)
print(f"   ✓ Saved to: {output_file}")
print(f"   ✓ {len(df_save):,} rows, {len(df_save.columns)} columns")

# Also update original file
df_save.to_csv(input_file, index=False)
print(f"   ✓ Updated original: {input_file}")

print(f"\n{'='*80}")
print(f"✅ KALORI CALCULATION FIXED!")
print(f"{'='*80}")
print(f"\nSummary:")
print(f"  - Data bahan_makanan: MONTHLY (sudah dibagi 12 di seeder)")
print(f"  - Formula sebelumnya: gram = bahan_makanan / (populasi × 365) ❌")
print(f"  - Formula sekarang:   gram = bahan_makanan / (populasi × 30.4375) ✅")
print(f"  - Efek: Kalori naik ~12x (karena data sudah monthly, tidak perlu dibagi setahun)")
print(f"\n🎯 Sekarang retrain model dengan data yang benar!")
print(f"{'='*80}")
