# Update NBM Data Plan - Pusdatin Kementan Integration

## 📊 Current Data Status Analysis

### Data Coverage by Year:
- **2024**: 32 komoditi (INCOMPLETE - need update)
- **2023**: 87 komoditi (Good coverage)
- **2022**: 93 komoditi (Good coverage) 
- **2021**: 95 komoditi (Good coverage)
- **2020**: 96 komoditi (Good coverage)

### Missing Kelompok in 2024:
- Makanan berpati
- Gula
- Buah-buahan
- Sayuran
- Minyak dan Lemak
- Susu

## 🎯 Update Strategy

### Phase 1: Recent Data Update (2020-2024)
**Priority: HIGH - Thesis credibility**

#### Sources to Collect:
1. **Pusdatin Kementan 2024**
   - Outlook Komoditas Pertanian
   - Statistik Konsumsi Pangan
   - Neraca Bahan Makanan

2. **BPS Statistics 2024**
   - Konsumsi Kalori dan Protein Penduduk Indonesia
   - Survei Sosial Ekonomi Nasional (SUSENAS)

#### Target Komoditi untuk Update 2024:
- **Kelompok 02 (Makanan berpati)**: Ubi jalar, Ubi kayu, Sagu
- **Kelompok 03 (Gula)**: Gula pasir, Gula merah  
- **Kelompok 05 (Buah-buahan)**: Pisang, Jeruk, Mangga
- **Kelompok 06 (Sayuran)**: Kangkung, Bayam, Tomat
- **Kelompok 09 (Minyak dan Lemak)**: Minyak kelapa, Minyak sawit
- **Kelompok 10 (Susu)**: Susu sapi, Susu bubuk

### Phase 2: Data Validation & Quality Check
- Cross-reference dengan publikasi resmi
- Consistency check dengan trend historis
- Outlier detection dan cleaning

### Phase 3: ML Model Retraining
- Update model dengan data terbaru
- Validation dengan data 2024 yang updated
- Performance improvement assessment

## 📋 Implementation Steps

### Step 1: Create Data Collection Templates
- Excel/CSV templates sesuai struktur database
- Mapping komoditi codes dengan publikasi

### Step 2: Data Entry & Import Scripts
- Bulk import utilities
- Data validation scripts
- Backup procedures

### Step 3: Quality Assurance
- Statistical validation
- Trend analysis
- Expert review

## 🎯 Expected Outcomes

1. **Complete 2024 Data**: All 11 kelompok covered
2. **Improved ML Accuracy**: More recent training data
3. **Thesis Credibility**: Official government statistics
4. **Better Predictions**: Reflect actual consumption patterns

## 📅 Timeline

- **Week 1**: Data collection from Pusdatin sources
- **Week 2**: Data entry and validation
- **Week 3**: Database update and ML retraining
- **Week 4**: Testing and documentation

## 🔗 Data Sources Links

### Pusdatin Kementan:
- https://pusdatin.pertanian.go.id/
- Outlook Komoditas series
- Statistik Konsumsi Pangan series

### BPS:
- https://www.bps.go.id/
- SUSENAS consumption data
- Kalori dan Protein series