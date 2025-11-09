-- ===================================================================
-- QUERY ANALISIS PERBEDAAN PERHITUNGAN OTOMATIS VS MANUAL
-- ===================================================================

-- 1. Data Gabah (0101) untuk tahun 2020 - Detail per bulan
SELECT 
    t.tahun,
    t.bulan,
    t.makanan as makanan_ribu_ton,
    t.populasi_indonesia,
    t.validation_status,
    t.outlier_flag,
    t.data_source
FROM transaksi_nbms t
WHERE t.kode_komoditi = '0101' 
    AND t.tahun = 2020
    AND t.validation_status = 'verified'
    AND t.outlier_flag = 0
ORDER BY t.bulan;

-- 2. Agregasi tahunan untuk gabah 2020
SELECT 
    t.tahun,
    COUNT(*) as jumlah_record,
    SUM(t.makanan) as total_makanan_ribu_ton,
    AVG(t.populasi_indonesia) as avg_populasi,
    MIN(t.populasi_indonesia) as min_populasi,
    MAX(t.populasi_indonesia) as max_populasi
FROM transaksi_nbms t
WHERE t.kode_komoditi = '0101' 
    AND t.tahun = 2020
    AND t.validation_status = 'verified'
    AND t.outlier_flag = 0;

-- 3. Data komoditi gabah untuk validasi nilai nutrisi
SELECT 
    k.kode_komoditi,
    k.nama,
    k.kalori_per_100g,
    k.protein_per_100g,
    k.lemak_per_100g
FROM komoditi k
WHERE k.kode_komoditi = '0101';

-- 4. Perhitungan manual step-by-step untuk 2020
SELECT 
    '2020' as tahun,
    SUM(t.makanan) as total_makanan_ribu_ton,
    SUM(t.makanan) * 1000 as total_makanan_ton,
    SUM(t.makanan) * 1000 * 1000 as total_makanan_kg,
    AVG(t.populasi_indonesia) as populasi,
    ROUND((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia), 4) as kg_per_tahun,
    ROUND(((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365, 4) as gram_per_hari,
    -- Kalori calculation
    ROUND((((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365 / 100) * k.kalori_per_100g, 4) as kalori_per_hari,
    -- Protein calculation  
    ROUND((((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365 / 100) * k.protein_per_100g, 4) as protein_per_hari,
    -- Lemak calculation
    ROUND((((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365 / 100) * k.lemak_per_100g, 6) as lemak_per_hari
FROM transaksi_nbms t
JOIN komoditi k ON t.kode_komoditi = k.kode_komoditi
WHERE t.kode_komoditi = '0101' 
    AND t.tahun = 2020
    AND t.validation_status = 'verified'
    AND t.outlier_flag = 0;

-- 5. Perbandingan dengan perhitungan manual yang Anda berikan
SELECT 
    'Manual Calculation (Your Data)' as source,
    186.2 as kg_per_tahun,
    510.4 as gram_per_hari,
    1837.44 as kalori_per_hari,
    38.28 as protein_per_hari,
    11.74 as lemak_per_hari,
    51182000 as makanan_ton_estimate,
    274814866 as populasi_estimate
UNION ALL
SELECT 
    'Database Calculation' as source,
    ROUND((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia), 4) as kg_per_tahun,
    ROUND(((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365, 4) as gram_per_hari,
    ROUND((((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365 / 100) * k.kalori_per_100g, 4) as kalori_per_hari,
    ROUND((((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365 / 100) * k.protein_per_100g, 4) as protein_per_hari,
    ROUND((((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365 / 100) * k.lemak_per_100g, 6) as lemak_per_hari,
    SUM(t.makanan) * 1000 as makanan_ton_database,
    AVG(t.populasi_indonesia) as populasi_database
FROM transaksi_nbms t
JOIN komoditi k ON t.kode_komoditi = k.kode_komoditi
WHERE t.kode_komoditi = '0101' 
    AND t.tahun = 2020
    AND t.validation_status = 'verified'
    AND t.outlier_flag = 0;

-- 6. Analisis seluruh tahun 2020-2024 untuk tren
SELECT 
    t.tahun,
    COUNT(*) as jumlah_record,
    SUM(t.makanan) as total_makanan_ribu_ton,
    AVG(t.populasi_indonesia) as avg_populasi,
    ROUND((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia), 4) as kg_per_tahun,
    ROUND(((SUM(t.makanan) * 1000 * 1000) / AVG(t.populasi_indonesia)) * 1000 / 365, 4) as gram_per_hari
FROM transaksi_nbms t
WHERE t.kode_komoditi = '0101' 
    AND t.tahun BETWEEN 2020 AND 2024
    AND t.validation_status = 'verified'
    AND t.outlier_flag = 0
GROUP BY t.tahun
ORDER BY t.tahun;

-- 7. Cek data outlier atau non-verified yang mungkin mempengaruhi perhitungan
SELECT 
    t.tahun,
    t.validation_status,
    t.outlier_flag,
    COUNT(*) as jumlah_record,
    SUM(t.makanan) as total_makanan_ribu_ton
FROM transaksi_nbms t
WHERE t.kode_komoditi = '0101' 
    AND t.tahun = 2020
GROUP BY t.tahun, t.validation_status, t.outlier_flag;

-- 8. Analisis data source untuk memahami variasi data
SELECT 
    t.data_source,
    COUNT(*) as jumlah_record,
    SUM(t.makanan) as total_makanan_ribu_ton,
    AVG(t.makanan) as avg_makanan_per_record
FROM transaksi_nbms t
WHERE t.kode_komoditi = '0101' 
    AND t.tahun = 2020
    AND t.validation_status = 'verified'
    AND t.outlier_flag = 0
GROUP BY t.data_source;