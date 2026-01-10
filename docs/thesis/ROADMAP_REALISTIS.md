# ROADMAP REALISTIS: Persiapan Bimbingan & Seminar Hasil
**Tanggal Dibuat:** 17 Desember 2025  
**Target:** Seminar Hasil - Pembuktian Penelitian

---

## ⚠️ GAP ANALYSIS: Apa yang Belum Ada vs Yang Ditulis di Thesis

### ❌ **BELUM ADA (Harus Dikerjakan)**

1. **UAT (User Acceptance Testing)**
   - ❌ Belum dilakukan testing dengan user Pusdatin
   - ❌ SUS Score 72.5 di thesis adalah ASUMSI
   - ❌ Satisfaction metrics (Tabel 21) adalah FIKTIF
   - ✅ **Action:** Buat mock UAT atau akui di bimbingan "UAT planned, belum execute"

2. **Data NBM Incomplete**
   - ❌ Banyak komoditas masih NULL
   - ✅ Ada: Gabah, Gula Pasir, Cabai
   - ❌ Komoditas lain perlu diisi atau dokumentasikan sebagai "data limitation"
   - ✅ **Action:** Audit data, buat tabel coverage komoditas

3. **Angka Performance Belum Terverifikasi**
   - ❌ MAPE 6.3% belum dicek dari training log asli
   - ❌ Baseline 12.4% belum dibuktikan
   - ❌ Response time 150-300ms belum ditest load
   - ✅ **Action:** Run training ulang, save metrics ke CSV/JSON, buat plot

4. **Visualisasi Chart Belum Ada**
   - ❌ Training loss curve belum di-plot
   - ❌ Actual vs Predicted scatter belum ada
   - ❌ Residual plot belum ada
   - ❌ Metrics comparison bar chart belum ada
   - ✅ **Action:** Generate semua plot pakai Python (lihat `run_training_with_metrics.py`)

5. **Listing Code Belum Dicek Keberadaannya**
   - Listing 1-7 di thesis, apakah benar ada di codebase?
   - ✅ **Action:** Audit satu-per-satu, verify path file

6. **Diagram UML Belum Lengkap**
   - ✅ Ada: Flowchart, ERD
   - ❌ Belum: Activity Diagram, Sequence Diagram
   - ✅ **Action:** Buat Activity + Sequence minimal 2-3 use case utama

7. **Demo Google Colab Belum Ada**
   - ❌ Belum ada notebook showcase prediksi
   - ✅ **Action:** Buat `demo_prediksi_nbm.ipynb` di Google Colab dengan sample data

8. **Dokumentasi Belum Proper Format**
   - ✅ Ada: User Manual, SRS, URS dalam Markdown
   - ❌ Belum: Export ke MS Word format
   - ✅ **Action:** Convert Markdown → DOCX pakai Pandoc

---

## 🎯 TIER 1: CRITICAL (Wajib Selesai Sebelum Bimbingan)

### 1. **Audit & Dokumentasi Data NBM** ⏱️ 2 jam
**Goal:** Dokumentasi realistis coverage data komoditas

```bash
# Step 1: Start database
cd /d/sikolbia/sikolbia-app
docker-compose up -d mysql

# Step 2: Check data
php artisan tinker
```

**Tinker commands:**
```php
// Total records
$total = DB::table('konsumsi_pangan')->count();

// Records per komoditas
$per_komoditas = DB::table('konsumsi_pangan')
    ->join('komoditas', 'konsumsi_pangan.komoditi_id', '=', 'komoditas.id')
    ->select('komoditas.nama_komoditas', 
             DB::raw('COUNT(*) as total'), 
             DB::raw('SUM(CASE WHEN kalori_per_kapita IS NULL THEN 1 ELSE 0 END) as null_count'))
    ->groupBy('komoditas.nama_komoditas')
    ->get();

// Export ke CSV
file_put_contents('d:/sikolbia/docs/thesis/data_audit_nbm.csv', 
    "Komoditas,Total Records,NULL Count,Coverage %\n");
```

**Output:** `docs/thesis/data_audit_nbm.csv` + tabel di Word

---

### 2. **Training Model + Generate Metrics Real** ⏱️ 4-6 jam
**Goal:** Angka MAPE, MAE, RMSE, R² yang REAL dari training

```bash
cd /d/sikolbia/sikolbia-ml

# Install dependencies
pip install matplotlib seaborn pandas numpy scikit-learn tensorflow

# Run training dengan metrics logging
python run_training_with_metrics.py
```

**Output yang dihasilkan:**
- `training_results_YYYYMMDD/metrics.json` (MAPE, MAE, RMSE, R²)
- `training_results_YYYYMMDD/plots/training_history.png` (Loss curve)
- `training_results_YYYYMMDD/plots/lstm_predictions.png` (Actual vs Predicted)
- `training_results_YYYYMMDD/plots/metrics_comparison.png` (LSTM vs Baseline vs Ensemble)

**Masukkan ke Thesis:**
- Update angka di BAB IV section 4.3, 4.6
- Insert gambar plot ke laporan (Gambar 21-24)

---

### 3. **Verifikasi Listing Code 1-7** ⏱️ 1 jam
**Goal:** Pastikan semua code snippet di thesis ada di codebase

**Listing Code di Thesis:**
1. Listing 1: Data preprocessing
2. Listing 2: LSTM model architecture
3. Listing 3: Training loop
4. Listing 4: Ensemble prediction
5. Listing 5: API endpoint FastAPI
6. Listing 6: Laravel controller call ML API
7. Listing 7: Adaptive weighting

**Action:**
- Buka setiap file, copy real code (max 40 lines)
- Replace listing di thesis dengan code asli
- Tambahkan comment Indonesian biar jelas

---

### 4. **Buat Google Colab Demo** ⏱️ 3 jam
**Goal:** Notebook interaktif untuk demo prediksi

**Struktur Notebook:**
```python
# Cell 1: Import & Setup
# Cell 2: Load Sample Data NBM (3-5 komoditas yang complete)
# Cell 3: Data Preprocessing (scaling, sequence creation)
# Cell 4: Load Trained Model (dari .keras file)
# Cell 5: Predict Future 6 Months
# Cell 6: Visualisasi Hasil (line chart, confidence interval)
# Cell 7: Metrics Evaluation (MAPE calculation)
```

**File:** `demo_prediksi_nbm.ipynb` di Google Drive/GitHub  
**Share Link:** Berikan ke dosen saat bimbingan

---

### 5. **Revisi Bahasa Thesis - Indonesia Natural** ⏱️ 3-4 jam
**Goal:** Ganti Inggris dipaksakan → Bahasa Indonesia yang alami

**Contoh Revisi:**
- ❌ "System successfully handles 50 concurrent users"
- ✅ "Sistem mampu menangani 50 pengguna bersamaan"

- ❌ "Multimodal fusion approaches dapat combining heterogeneous data sources"
- ✅ "Pendekatan fusi multi-modal dapat menggabungkan berbagai sumber data heterogen"

**Target:** BAB IV & V (prioritas karena hasil & pembahasan)

---

## 🟡 TIER 2: IMPORTANT (Nice to Have, Bisa Dijelaskan Verbal)

### 6. **Activity + Sequence Diagram** ⏱️ 2 jam
**Tool:** draw.io / Lucidchart / PlantUML

**Activity Diagram - Use Case:**
1. User Input Data NBM (pilih komoditas, periode)
2. Sistem Preprocessing Data
3. Sistem Call ML API FastAPI
4. ML Model Generate Prediksi
5. Sistem Tampilkan Hasil + Export Excel

**Sequence Diagram - Interaksi:**
```
User → Laravel → FastAPI → LSTM Model
  |       |         |           |
  |    Request  →   |           |
  |       |      Predict  →     |
  |       |         |     Result|
  |       |    ← Response        |
  |   ← Display                  |
```

---

### 7. **Export Dokumentasi Markdown → DOCX** ⏱️ 30 menit
```bash
# Install Pandoc (jika belum)
choco install pandoc  # Windows

# Convert
cd /d/sikolbia/docs
pandoc user_manual.md -o UserManual_SIKOLBIA.docx
pandoc SRS.md -o SRS_SIKOLBIA.docx
```

---

## 🔵 TIER 3: OPTIONAL (Kalau Ada Waktu)

### 8. **UAT Mock/Simulation**
Kalau tidak sempat UAT real dengan Pusdatin:
- **Option A:** Akui di bimbingan: "UAT planned tapi belum sempat execute karena koordinasi dengan stakeholder"
- **Option B:** Buat "Expert Review" dengan dosen pembimbing sebagai expert (minta feedback sistem)
- **Option C:** Self-testing dengan checklist SUS (isi sendiri sebagai developer perspective)

---

## 📅 TIMELINE EKSEKUSI

**Asumsi: 3 hari kerja (72 jam) sebelum bimbingan**

### Hari 1 (8 jam):
- [x] Audit data NBM (2 jam)
- [x] Setup training environment (1 jam)
- [ ] Run training model + generate metrics (4-6 jam) → **PRIORITAS TERTINGGI**
- [ ] Verifikasi listing code (1 jam)

### Hari 2 (8 jam):
- [ ] Buat Google Colab demo (3 jam)
- [ ] Generate visualisasi chart (2 jam)
- [ ] Revisi bahasa thesis BAB IV (3 jam)

### Hari 3 (8 jam):
- [ ] Revisi bahasa thesis BAB V (2 jam)
- [ ] Buat Activity + Sequence Diagram (2 jam)
- [ ] Export dokumentasi ke DOCX (30 menit)
- [ ] Print diagram + laporan (1 jam)
- [ ] Rehearsal presentasi + prepare demo (2.5 jam)

---

## ✅ CHECKLIST FINAL SEBELUM BIMBINGAN

### Dokumen:
- [ ] Laporan BAB IV-V (print + PDF) dengan angka REAL
- [ ] Data audit NBM (`data_audit_nbm.csv` + tabel)
- [ ] Metrics training real (`metrics.json` + tabel)
- [ ] Plot visualisasi (4-6 gambar PNG 300dpi)
- [ ] Diagram UML (Activity + Sequence) print A4
- [ ] Google Colab link (share mode "Anyone with link can view")

### Teknis:
- [ ] Docker containers tested running
- [ ] Model `.keras` file ada dan loadable
- [ ] FastAPI `/predict` endpoint tested dengan Postman
- [ ] Laravel frontend bisa hit ML API
- [ ] Sample data ready untuk live demo

### Pemahaman:
- [ ] Bisa jelasin LSTM gates tanpa lihat notes
- [ ] Bisa gambar arsitektur microservices dari memory
- [ ] Paham angka MAPE real (bukan asumsi)
- [ ] Siap jawab: "Data komoditas mana yang complete?"
- [ ] Siap jawab: "UAT kapan dan dengan siapa?" (atau akui belum)

---

## 🎯 STRATEGI BIMBINGAN

### Transparansi > Fabrication
**DO:**
- ✅ Akui apa yang belum sempat dikerjakan
- ✅ "UAT belum execute Pak, masih koordinasi dengan Pusdatin"
- ✅ "Data komoditas masih partial, fokus 3 komoditas utama dulu"
- ✅ "Angka di draft awal masih estimasi, ini hasil training real-nya"

**DON'T:**
- ❌ Ngarang angka atau hasil yang tidak ada buktinya
- ❌ Klaim sudah UAT kalau belum
- ❌ Bilang "semua komoditas complete" kalau banyak NULL

### Fokus Strength
**Highlight:**
1. Sistem REAL dan JALAN (demo langsung)
2. Arsitektur sound (microservices, Docker, scalable)
3. Model trained dan bisa predict (show Colab demo)
4. Metodologi RnD + CRISP-DM clear dan well-documented

**Minimize:**
1. UAT belum → "Rencana week depan Pak dengan Bu X di Pusdatin"
2. Data partial → "Ini limitation yang acknowledge di BAB V"
3. Metrics belum final → "Ini hasil preliminary, masih fine-tuning"

---

## 📞 BANTUAN JIKA STUCK

**Masalah Training Lama (>6 jam):**
- Reduce dataset: ambil 3 komoditas saja (gabah, gula, cabai)
- Reduce epochs: 50 → 20 epochs
- Use smaller model: LSTM 32-32 instead of 32-64-32

**Masalah Data NULL Banyak:**
- Dokumentasikan sebagai "Data Limitation" di BAB V
- Fokus analisis pada komoditas yang complete
- Future work: "Perlu data cleaning dan enrichment"

**Masalah Demo Error:**
- Buat video backup (OBS Studio record screen)
- Siapkan screenshot step-by-step
- Punya Postman collection untuk demo API

---

## 🚀 NEXT STEPS SEKARANG

**Immediate Action (Mulai Sekarang):**

```bash
# 1. Start training (prioritas #1)
cd /d/sikolbia/sikolbia-ml
python run_training_with_metrics.py

# 2. Parallel: Audit data (buka tab terminal baru)
cd /d/sikolbia/sikolbia-app
docker-compose up -d mysql
php artisan tinker
# Run audit commands dari atas

# 3. Parallel: Mulai revisi bahasa thesis (manual di editor)
# Buka laporan_tugas_akhir.md
# Ctrl+F cari pattern Inggris dipaksakan, replace dengan Indonesia
```

**Komunikasi dengan Dosen:**
- Email/WA dosen: "Pak, mau bimbingan [tanggal]. Ada beberapa yang masih progress (UAT, data completion). Fokus bimbingan ke arsitektur sistem dan hasil training model. Mohon arahan."

---

## 📊 EXPECTED OUTCOMES

Setelah complete roadmap ini:

✅ **Laporan:** Angka dan code real, bahasa Indonesia natural  
✅ **Demo:** Google Colab + live system siap  
✅ **Visualisasi:** 4-6 chart berkualitas publication-ready  
✅ **Pemahaman:** Bisa jelasin sistem end-to-end tanpa baca notes  
✅ **Honesty:** Transparansi limitation, bukan ngarang hasil

**Result:** Bimbingan smooth, dosen appreciate honesty + solid technical work, approved untuk Seminar Hasil! 🎓

---

**Dibuat oleh:** GitHub Copilot  
**Untuk:** Persiapan Realistis Seminar Hasil SIKOLBIA  
**Prinsip:** Reality > Perfection | Honesty > Fabrication
