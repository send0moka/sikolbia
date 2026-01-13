# Peristiwa Kritis yang Berdampak pada NBM Indonesia (1993-2025)

## Overview
Script enhanced generator (`generate_nbm_enhanced.php`) sudah memperhitungkan 5 peristiwa kritis yang berdampak signifikan terhadap ketersediaan dan harga pangan di Indonesia.

---

## 1. Krisis Moneter 1998 🔴
**Periode**: 1997-1999 (puncak 1998)

### Dampak:
- **Inflasi ekstrem**: 77.6% (tertinggi dalam sejarah modern Indonesia)
- **Nilai Rupiah**: Jatuh dari Rp 2,400/USD → Rp 16,000/USD
- **Harga pangan**: Melonjak 2-3x lipat, terutama komoditi impor
- **Daya beli**: Turun drastis, kemiskinan naik dari 11% → 24%
- **Produktivitas**: Turun 15% karena petani tidak mampu beli pupuk/bibit

### Komoditi Terpengaruh:
- **Sangat tinggi**: Gandum (+35%), Susu (+35%), Buah impor (+35%)
- **Tinggi**: Beras, Gula, Minyak goreng
- **Moderate**: Sayur lokal, Telur

### Implementasi di Generator:
```php
// Inflasi 1998: 1.15 (spike ekstrem)
// Harga komoditi impor: +35%
// Produktivitas tanaman: -15%
```

---

## 2. Krisis Global 2008 🟠
**Periode**: 2008-2009 (food crisis)

### Dampak:
- **Harga komoditas dunia**: Naik 60-80% (beras, gandum, minyak)
- **Inflasi pangan Indonesia**: 16.4% (2008)
- **Intervensi pemerintah**: Raskin diperluas, subsidi BBM naik
- **Stok beras**: Impor darurat 1 juta ton
- **Harga minyak dunia**: USD 140/barrel → harga pangan ikut naik

### Komoditi Terpengaruh:
- **Sangat tinggi**: Beras (+25%), Minyak goreng (+30%), Kedelai (+25%)
- **Tinggi**: Daging (+20%), Telur (+18%)
- **Moderate**: Sayur, Buah lokal

### Implementasi di Generator:
```php
// Inflasi 2008: 2.28 (lebih tinggi dari tren)
// Harga beras, minyak, daging: +25%
```

---

## 3. Kekeringan 2015 (El Nino Ekstrem) 🔥
**Periode**: 2015 (puncak kekeringan Agustus-Oktober)

### Dampak:
- **Curah hujan**: Turun 45% dari normal (kekeringan terparah 50 tahun)
- **Gagal panen**: 1.6 juta hektar sawah puso (tidak panen)
- **Produksi padi**: Turun 4.8 juta ton
- **Kebakaran hutan**: 2.6 juta hektar (terburuk dalam sejarah)
- **Impor beras**: Melonjak menjadi 861 ribu ton
- **Produktivitas**: Turun 30% untuk tanaman pangan

### Komoditi Terpengaruh:
- **Sangat tinggi**: Beras (+20%), Jagung (+22%), Kedelai (+18%)
- **Tinggi**: Umbi-umbian (+15%), Sayuran (+12%)
- **Moderate**: Daging (harga pakan naik)

### Implementasi di Generator:
```php
// Curah hujan 2015: 1,375 mm (55% dari normal)
// Harga padi-padian, umbi, sayur: +20%
// Produktivitas tanaman pangan: -30%
```

---

## 4. Pandemi COVID-19 (2020-2022) 🦠
**Periode**: Maret 2020 - Desember 2022

### Dampak:
- **Supply chain disruption**: Gangguan distribusi antar daerah
- **PSBB/Lockdown**: Pasar tradisional tutup, logistik terhambat
- **Perubahan konsumsi**: Shift ke makanan kemasan, online grocery
- **Harga volatil**: Sayur & buah naik-turun ekstrem (pasokan tidak stabil)
- **Tenaga kerja**: Petani kesulitan harvest (lockdown), produktivitas turun 8%
- **Export restrictions**: Beberapa negara batasi ekspor pangan

### Komoditi Terpengaruh:
- **Sangat tinggi**: Sayuran (+15%), Buah-buahan (+15%), Daging ayam (+12%)
- **Tinggi**: Telur, Bawang merah/putih
- **Moderate**: Beras (subsidi pemerintah menjaga harga)

### Implementasi di Generator:
```php
// Inflasi 2020-2022: Lebih tinggi dari tren normal
// Harga sayur, buah, daging: +15% (supply chain)
// Produktivitas 2020: -8% (lockdown impact)
```

---

## 5. Program Makan Bergizi Gratis (MBG) 2025 🍽️
**Periode**: Januari 2025 - sekarang

### Dampak:
- **Target**: 15 juta anak sekolah + ibu hamil
- **Demand increase**: Protein (daging, telur, susu), Sayuran
- **Harga proyeksi**: Naik 10-15% untuk komoditi target program
- **Produktivitas sayur**: Naik 8% (intensifikasi untuk penuhi demand)
- **Supply**: Pemerintah kontrak langsung dengan petani/peternak

### Komoditi Terpengaruh:
- **Sangat tinggi**: Telur ayam (+12%), Daging ayam (+10%), Sayuran (+10%)
- **Tinggi**: Susu (+10%), Ikan (+8%)
- **Moderate**: Buah-buahan

### Implementasi di Generator:
```php
// Inflasi 2025: 4.68 (demand increase)
// Harga protein & sayur: +10%
// Produktivitas sayur: +8% (intensifikasi)
```

---

## Cara Kerja Enhanced Generator

### 1. Data Kontekstual Dasar
Generator menghitung data normal berdasarkan:
- Populasi Indonesia per tahun (BPS)
- Inflasi kumulatif (basis tahun 2000)
- Suhu rata-rata (tren +0.03°C/tahun)
- Curah hujan (2500mm ± variasi El Nino/La Nina)

### 2. Anomaly Adjustment
Untuk tahun-tahun peristiwa kritis, generator menerapkan **multiplier adjustment**:
```php
Harga Final = Harga Base × Inflasi × Adjustment Factor
Produktivitas Final = Produktivitas Base × Trend × Adjustment Factor
```

### 3. Komoditi-Specific Impact
Setiap peristiwa berdampak berbeda per kelompok komoditi:
- **Krisis 1998**: Komoditi impor (kelompok 01, 03, 05, 09)
- **Krisis 2008**: Komoditi pokok (kelompok 01, 07, 10)
- **Kekeringan 2015**: Tanaman pangan (kelompok 01, 02, 06)
- **Pandemi 2020-2022**: Produk segar (kelompok 05, 06, 07)
- **MBG 2025**: Protein & sayur (kelompok 06, 07, 08, 09)

---

## Validasi Data Enhanced

### Cara Verifikasi:
1. Generate enhanced data: `php generate_nbm_enhanced.php <csv> <kelompok> <komoditi> <nama>`
2. Cek SQL output untuk tahun kritis (1998, 2008, 2015, 2020-2022, 2025)
3. Bandingkan dengan tahun normal:
   - Harga konsumen harus lebih tinggi pada tahun krisis
   - Produktivitas harus turun pada 1998, 2015, 2020
   - Curah hujan 2015 harus jauh lebih rendah

### Expected Patterns:
```
Beras 2014: Rp 10,530/kg (normal)
Beras 2015: Rp 12,636/kg (+20% kekeringan) ✓
Beras 2016: Rp 12,180/kg (recovery)

Produktivitas Padi 2014: 5.46 ton/ha (normal)
Produktivitas Padi 2015: 3.82 ton/ha (-30% gagal panen) ✓
Produktivitas Padi 2016: 5.54 ton/ha (recovery)
```

---

## Sumber Referensi
1. BPS - Statistik Indonesia (inflasi, populasi, produksi)
2. BMKG - Data Iklim Historis (suhu, curah hujan)
3. Kementerian Pertanian - Laporan Tahunan NBM
4. Bank Indonesia - Inflasi Historis 1993-2024
5. Media & Literatur Akademik - Analisis dampak krisis

---

## Kesimpulan

Enhanced generator ini memastikan data NBM **realistis dan kontekstual**, mencerminkan kondisi riil Indonesia di setiap periode. Data tidak hanya akurat secara statistik, tapi juga **bermakna secara historis dan ekonomis** untuk keperluan analisis skripsi.

✅ **Semua 5 peristiwa kritis sudah diimplementasikan dengan adjustment factor yang tepat.**
