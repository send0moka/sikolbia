import pandas as pd
import numpy as np

# Load processed data
df = pd.read_csv('ml_models/data/nbm_processed.csv')

print("="*100)
print("ANALISIS KOMODITI DENGAN KALORI RENDAH")
print("="*100)

# 1. Filter data dengan kalori < 10 kkal/kapita/hari
low_kalori = df[df['kalori_per_capita_per_day'] < 10].copy()

if len(low_kalori) > 0:
    # Grup by komoditi
    summary = low_kalori.groupby('nama_komoditi').agg({
        'kalori_per_capita_per_day': ['count', 'mean', 'min', 'max'],
        'bahan_makanan': 'mean',
        'kalori_per_100g': 'first',
        'gram_per_capita_per_day': 'mean',
        'produksi': 'mean',
        'populasi_indonesia': 'first'
    }).round(2)
    
    summary.columns = ['Records', 'Avg_Kalori', 'Min_Kalori', 'Max_Kalori', 
                       'Avg_BahanMakanan_Ton', 'Kalori_per_100g', 
                       'Avg_Gram_perCapita', 'Avg_Produksi_Ton', 'Populasi']
    summary = summary.sort_values('Avg_Kalori')
    
    print(f"\nKomoditi dengan kalori < 10 kkal/kapita/hari:")
    print("-"*100)
    print(summary.to_string())
    print(f"\nTotal: {len(summary)} komoditi, {len(low_kalori):,} records dari {len(df):,} total records ({len(low_kalori)/len(df)*100:.2f}%)")
    
    # 2. Check apakah ada komoditi UTAMA (yang seharusnya punya konsumsi tinggi)
    print("\n" + "="*100)
    print("CEK KOMODITI UTAMA (Seharusnya Kalori Tinggi)")
    print("="*100)
    
    komoditi_utama = ['Beras', 'Jagung', 'Ubi Kayu', 'Ubi Jalar', 'Kentang', 
                      'Gula Pasir', 'Gula Mangkok', 'Minyak', 'Kelapa']
    
    problem_komoditi = []
    for komoditi in komoditi_utama:
        if komoditi in summary.index:
            problem_komoditi.append(komoditi)
            print(f"⚠️ {komoditi}: Avg kalori = {summary.loc[komoditi, 'Avg_Kalori']:.2f} kkal/kapita/hari")
    
    if not problem_komoditi:
        print("✓ OK: Semua komoditi utama punya kalori >= 10 kkal/kapita/hari")
    else:
        print(f"\n❌ PROBLEM: {len(problem_komoditi)} komoditi utama dengan kalori terlalu rendah!")
    
    # 3. Analisis apakah ini wajar (komoditi minor/bumbu/rempah)
    print("\n" + "="*100)
    print("KLASIFIKASI KOMODITI RENDAH")
    print("="*100)
    
    # Komoditi yang WAJAR punya kalori rendah (konsumsi sedikit)
    komoditi_minor = ['Cabe', 'Bawang', 'Tomat', 'Terong', 'Kangkung', 
                     'Bayam', 'Kacang Panjang', 'Labu', 'Ketimun',
                     'Sawi', 'Wortel', 'Jeruk', 'Mangga', 'Pepaya',
                     'Pisang', 'Nanas', 'Rambutan', 'Sawo', 'Alpokat']
    
    wajar = []
    tidak_wajar = []
    
    for komoditi in summary.index:
        # Check if komoditi minor (konsumsi sedikit = normal kalori rendah)
        is_minor = any(minor in komoditi for minor in komoditi_minor)
        
        if is_minor or summary.loc[komoditi, 'Kalori_per_100g'] < 100:  # Buah/sayur
            wajar.append(komoditi)
        else:
            tidak_wajar.append(komoditi)
    
    if wajar:
        print(f"\n✓ WAJAR ({len(wajar)} komoditi): Buah/sayur/bumbu dengan konsumsi kecil")
        for k in wajar[:10]:  # Show first 10
            print(f"   - {k}: {summary.loc[k, 'Avg_Kalori']:.2f} kkal (kalori_per_100g = {summary.loc[k, 'Kalori_per_100g']:.0f})")
        if len(wajar) > 10:
            print(f"   ... dan {len(wajar)-10} lainnya")
    
    if tidak_wajar:
        print(f"\n❌ TIDAK WAJAR ({len(tidak_wajar)} komoditi): Komoditi pokok/karbohidrat dengan kalori rendah")
        for k in tidak_wajar:
            print(f"   - {k}: {summary.loc[k, 'Avg_Kalori']:.2f} kkal (seharusnya tinggi, kalori_per_100g = {summary.loc[k, 'Kalori_per_100g']:.0f})")
            # Show sample records
            samples = df[df['nama_komoditi'] == k][['tahun', 'bulan', 'bahan_makanan', 'produksi', 'populasi_indonesia', 'gram_per_capita_per_day', 'kalori_per_capita_per_day']].head(3)
            print(samples.to_string(index=False))
            print()
    
    # 4. Statistik distribusi kalori
    print("\n" + "="*100)
    print("DISTRIBUSI KALORI (Semua Data)")
    print("="*100)
    
    kalori_bins = [0, 1, 5, 10, 50, 100, df['kalori_per_capita_per_day'].max()]
    kalori_labels = ['< 1', '1-5', '5-10', '10-50', '50-100', '> 100']
    df['kalori_bin'] = pd.cut(df['kalori_per_capita_per_day'], bins=kalori_bins, labels=kalori_labels)
    
    dist = df['kalori_bin'].value_counts().sort_index()
    print("\nDistribusi kalori per capita per day:")
    for bin_name, count in dist.items():
        pct = count / len(df) * 100
        print(f"  {bin_name:8s} kkal: {count:6,} records ({pct:5.2f}%)")
    
    print(f"\nTotal records dengan kalori < 10 kkal: {len(low_kalori):,} ({len(low_kalori)/len(df)*100:.2f}%)")
    
else:
    print("\n✓ Tidak ada data dengan kalori < 10 kkal/kapita/hari")

print("\n" + "="*100)
print("KESIMPULAN")
print("="*100)

# Kesimpulan dan rekomendasi
if len(low_kalori) > 0:
    pct_low = len(low_kalori) / len(df) * 100
    
    if pct_low < 20:
        print(f"✓ Data wajar: Hanya {pct_low:.1f}% records dengan kalori < 10 kkal")
        print("  → Ini normal untuk buah, sayur, dan bumbu-bumbu")
        print("  → Gunakan threshold 10 kkal untuk MAPE calculation ✓")
    elif pct_low < 40:
        print(f"⚠️ Data cukup banyak: {pct_low:.1f}% records dengan kalori < 10 kkal")
        print("  → Perlu review apakah ada komoditi utama yang salah")
        print("  → Jika hanya komoditi minor → OK gunakan threshold 10 kkal")
    else:
        print(f"❌ Data bermasalah: {pct_low:.1f}% records dengan kalori < 10 kkal")
        print("  → Kemungkinan ada error di perhitungan/seeder")
        print("  → Perlu perbaikan data sebelum training")
else:
    print("✓ Semua data punya kalori >= 10 kkal")
    print("  → Data bagus untuk modeling")

print("="*100)
