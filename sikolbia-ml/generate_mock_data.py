"""
Generate mock NBM data untuk testing training script
Output: CSV dengan pattern realistis konsumsi pangan Indonesia
"""

import pandas as pd
import numpy as np
from datetime import datetime

def generate_mock_nbm_data(start_year=2000, end_year=2024, output_path='data/nbm_training.csv'):
    """Generate mock time series data NBM"""
    
    # 10 Kelompok Komoditas
    kelompok = [
        ('01', 'Padi-padian'),
        ('02', 'Umbi-umbian'),
        ('03', 'Ikan/Daging/Telur/Susu'),
        ('04', 'Sayur dan Buah'),
        ('05', 'Kacang-kacangan'),
        ('06', 'Gula'),
        ('07', 'Sayur dan Buah'),
        ('08', 'Minyak dan Lemak'),
        ('09', 'Buah/Biji Berminyak'),
        ('10', 'Bumbu-bumbuan')
    ]
    
    # Sample komoditas per kelompok (1-3 komoditas)
    komoditas_map = {
        '01': [('0101', 'Beras'), ('0102', 'Jagung')],
        '02': [('0201', 'Ubi Kayu'), ('0202', 'Ubi Jalar')],
        '03': [('0301', 'Daging Sapi'), ('0302', 'Daging Ayam'), ('0303', 'Telur')],
        '04': [('0401', 'Bayam'), ('0402', 'Kangkung')],
        '05': [('0501', 'Kacang Tanah'), ('0502', 'Kacang Hijau')],
        '06': [('0601', 'Gula Pasir')],
        '07': [('0701', 'Pisang'), ('0702', 'Jeruk')],
        '08': [('0801', 'Minyak Kelapa'), ('0802', 'Minyak Goreng')],
        '09': [('0901', 'Kelapa')],
        '10': [('1001', 'Cabai'), ('1002', 'Bawang Merah')]
    }
    
    data = []
    
    for year in range(start_year, end_year + 1):
        for month in range(1, 13):
            for kode_kel, nama_kel in kelompok:
                for kode_kom, nama_kom in komoditas_map.get(kode_kel, []):
                    # Simulate seasonal pattern with noise
                    base_kalori = {
                        '01': 1500,  # Beras tinggi
                        '02': 300,
                        '03': 400,
                        '04': 200,
                        '05': 150,
                        '06': 250,
                        '07': 180,
                        '08': 350,
                        '09': 120,
                        '10': 50
                    }.get(kode_kel, 200)
                    
                    # Add trend (slight increase over years)
                    trend = (year - start_year) * 2
                    
                    # Add seasonal component
                    seasonal = 20 * np.sin(2 * np.pi * month / 12)
                    
                    # Add random noise
                    noise = np.random.normal(0, 30)
                    
                    kalori = max(10, base_kalori + trend + seasonal + noise)
                    protein = kalori * np.random.uniform(0.05, 0.15)
                    lemak = kalori * np.random.uniform(0.03, 0.10)
                    
                    data.append({
                        'tahun': year,
                        'bulan': month,
                        'kode_kelompok': kode_kel,
                        'nama_kelompok': nama_kel,
                        'kode_komoditi': kode_kom,
                        'nama_komoditas': nama_kom,
                        'kalori_per_kapita': round(kalori, 2),
                        'protein_per_kapita': round(protein, 2),
                        'lemak_per_kapita': round(lemak, 2)
                    })
    
    df = pd.DataFrame(data)
    df.to_csv(output_path, index=False)
    
    print(f"✅ Generated {len(df)} records")
    print(f"📊 Years: {start_year}-{end_year}")
    print(f"📦 Kelompok: {len(kelompok)}")
    print(f"💾 Saved to: {output_path}")
    print(f"\nFirst 5 rows:")
    print(df.head())
    
    return df

if __name__ == "__main__":
    import os
    os.makedirs('data', exist_ok=True)
    generate_mock_nbm_data()
