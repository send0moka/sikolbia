import random
from datetime import datetime

TARGET = 15000
SQL_FILE = 'transaksi_nbms_insert_factory_style_noketerangan.sql'

kelompok_codes = ['01', '02', '03', '04', '05']
komoditi_codes = ['0101', '0102', '0103', '0201', '0202', '0301', '0401', '0403', '0501', '0502']
status_angka = ['tetap', 'sementara', 'sangat sementara']
periode_data = ['bulanan', 'kuartalan', 'tahunan']
kebijakan_impor = ['bebas', 'terbatas', 'dilarang']
validation_status = ['verified', 'pending', 'flagged']

columns = [
    'kode_kelompok', 'kode_komoditi', 'tahun', 'bulan', 'kuartal', 'periode_data',
    'status_angka', 'masukan', 'keluaran', 'impor', 'ekspor', 'perubahan_stok',
    'pakan', 'bibit', 'makanan', 'bukan_makanan', 'tercecer', 'penggunaan_lain',
    'bahan_makanan', 'kg_tahun', 'gram_hari', 'kalori_hari', 'protein_hari', 'lemak_hari',
    'harga_produsen', 'harga_konsumen', 'inflasi_komoditi', 'nilai_tukar_usd', 'populasi_indonesia',
    'gdp_per_kapita', 'tingkat_kemiskinan', 'curah_hujan_mm', 'suhu_rata_celsius', 'indeks_el_nino',
    'luas_panen_ha', 'produktivitas_ton_ha', 'kebijakan_impor', 'subsidi_pemerintah', 'stok_bulog',
    'confidence_score', 'data_source', 'validation_status', 'outlier_flag', 'created_at', 'updated_at'
]

with open(SQL_FILE, 'w', encoding='utf-8') as f:
    f.write('INSERT INTO transaksi_nbms (%s) VALUES\n' % ', '.join(columns))
    values = []
    now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    for i in range(TARGET):
        kode_komoditi = random.choice(komoditi_codes)
        kode_kelompok = kode_komoditi[:2]
        tahun = random.randint(1993, 2025)
        bulan = random.randint(1, 12)
        kuartal = (bulan - 1) // 3 + 1
        row = [
            f"'{kode_kelompok}'",
            f"'{kode_komoditi}'",
            str(tahun),
            str(bulan),
            str(kuartal),
            f"'{random.choice(periode_data)}'",
            f"'{random.choice(status_angka)}'",
            f"{round(random.uniform(0, 99999), 4)}",
            f"{round(random.uniform(0, 99999), 4)}",
            f"{round(random.uniform(0, 9999), 4)}",
            f"{round(random.uniform(0, 9999), 4)}",
            f"{round(random.uniform(-9999, 9999), 4)}",
            f"{round(random.uniform(0, 9999), 4)}",
            f"{round(random.uniform(0, 999), 4)}",
            f"{round(random.uniform(0, 99999), 4)}",
            f"{round(random.uniform(0, 9999), 4)}",
            f"{round(random.uniform(0, 9999), 4)}",
            f"{round(random.uniform(0, 9999), 4)}",
            f"{round(random.uniform(0, 99999), 4)}",
            f"{round(random.uniform(0, 999), 4)}",
            f"{round(random.uniform(0, 999), 4)}",
            f"{round(random.uniform(0, 9999), 4)}",
            f"{round(random.uniform(0, 99), 4)}",
            f"{round(random.uniform(0, 99), 6)}",
            f"{round(random.uniform(1000, 100000), 4)}",
            f"{round(random.uniform(1000, 100000), 4)}",
            f"{round(random.uniform(-100, 100), 4)}",
            f"{round(random.uniform(12000, 16000), 4)}",
            str(random.randint(250000000, 280000000)),
            f"{round(random.uniform(1000, 10000), 2)}",
            f"{round(random.uniform(5, 20), 2)}",
            f"{round(random.uniform(100, 4000), 2)}",
            f"{round(random.uniform(20, 35), 2)}",
            f"{round(random.uniform(-3, 3), 3)}",
            f"{round(random.uniform(100, 10000), 2)}",
            f"{round(random.uniform(10, 100), 4)}",
            f"'{random.choice(kebijakan_impor)}'",
            f"{round(random.uniform(0, 100000000), 2)}",
            f"{round(random.uniform(100, 10000), 4)}",
            f"{round(random.uniform(0.8, 1.0), 2)}",
            "'BPS'",
            f"'{random.choice(validation_status)}'",
            str(random.randint(0, 1)),
            f"'{now}'",
            f"'{now}'"
        ]
        values.append('(%s)' % ', '.join(row))
    f.write(',\n'.join(values) + ';\n')
print(f"SQL file '{SQL_FILE}' generated successfully with {TARGET} rows.")
