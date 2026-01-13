import pandas as pd

df = pd.read_csv('ml_models/data/nbm_processed_fixed.csv')

print("="*60)
print("DATA VERIFICATION")
print("="*60)
print(f"\nTotal: {len(df):,} rows, {len(df.columns)} columns")

print(f"\n✓ Kalori statistics:")
print(f"  Mean:   {df['kalori_per_capita_per_day'].mean():.2f} kkal")
print(f"  Median: {df['kalori_per_capita_per_day'].median():.2f} kkal")
print(f"  Min:    {df['kalori_per_capita_per_day'].min():.2f} kkal")
print(f"  Max:    {df['kalori_per_capita_per_day'].max():.2f} kkal")

print(f"\n✓ Major commodities average:")
for k in ['Beras', 'Jagung', 'Gula Pasir', 'Kedelai', 'Ubi Kayu']:
    avg = df[df['nama_komoditi'] == k]['kalori_per_capita_per_day'].mean()
    print(f"  {k:15s}: {avg:8.2f} kkal/kapita/hari")

print(f"\n✓ Data ready for Google Colab!")
print("="*60)
