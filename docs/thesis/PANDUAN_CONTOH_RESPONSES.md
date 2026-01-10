# Panduan Contoh Responses UAT SIKOLBIA

## 📋 Overview
File ini menjelaskan contoh responses UAT dari 10 responden untuk sistem SIKOLBIA. Data ini dapat digunakan sebagai:
- **Template** untuk memahami format responses yang diharapkan
- **Data dummy** untuk testing perhitungan sebelum UAT real
- **Referensi** saat memasukkan data real dari Google Form ke Excel

## 👥 Daftar Responden (10 orang)

### Admin (2 orang)
1. **HARI PERMANA** - Kelompok Pengembangan Sistem Informasi (5-10 tahun)
   - Perspektif: Sangat positif, fokus pada kualitas teknis dan maintainability
   - Pola jawaban: Mayoritas 5 (SS), beberapa 4 (S) pada aspek performance

2. **ARIF NOFYANSYAH** - Kelompok Pengembangan Sistem Informasi (5-10 tahun)
   - Perspektif: Positif dengan perhatian detail pada robustness
   - Pola jawaban: Mix 4-5, lebih kritis pada aspek error handling

### Pemerintah (5 orang)
3. **DIAN PRASETYORINI** - Kelompok Data Komoditas (5-10 tahun) ⭐ DOMAIN EXPERT
   - Perspektif: Sangat positif, expert validasi akurasi model dan relevansi data NBM
   - Pola jawaban: Mayoritas 5, sangat paham konteks business logic
   - **KEY INSIGHT**: Respon beliau sangat penting untuk validasi domain NBM

4. **DEFRINAL SYARIEF** - Bagian Umum (3-5 tahun)
   - Perspektif: Pengguna praktis, coba akses via tablet untuk mobility
   - Pola jawaban: Mayoritas 4-5, tapi skor TS (2) pada C9 responsive tablet
   - **KEY CRITIQUE**: "Tampilan di tablet kurang optimal, tabel data terpotong"

5. **DHANANG SUSATYO** - Bagian Umum (3-5 tahun)
   - Perspektif: Balanced, sedikit lebih konservatif, koneksi internet lambat
   - Pola jawaban: Mix 2-5, skor TS (2) pada B1 loading time, CS (3) pada B4
   - **KEY CRITIQUE**: "Loading data NBM kadang >5 detik, terlalu lama untuk koneksi kantor"

6. **DIDIK PRATAMA SAPUTRA** - Bagian Umum (3-5 tahun)
   - Perspektif: Praktis dengan latar IT, perhatian pada user-friendliness
   - Pola jawaban: Mix 2-5, skor CS (3) pada B2 consistency, TS (2) pada C7 pesan error
   - **KEY CRITIQUE**: "Pesan error kadang masih teknis (SQL/Laravel error), kurang user-friendly"

7. **EDY PURNOMO** - Bagian Umum (5pernah pakai sistem lain, punya benchmark
   - Pola jawaban: Mayoritas 4-5, tapi TS (2) pada B2 consistency performance
   - **KEY CRITIQUE**: "Performance naik-turun, kadang cepat kadang lambat (mungkin tergantung server load)"mlined
   - Pola jawaban: Mayoritas 4-5, balanced across all variables

### Akademisi (3 orang)
8. **Fawwaz Afkar Muzakky** - Unsoed Informatika, Magang Pusdatin (1-3 tahun)
   - Perspektif: Technical student, paham architecture dan code quality
   - Pola jawaban: Mayoritas 5, fokus pada best practices implementation

9. **Raynard Prathama** - IBII Informatika, Matesting edge cases
   - Pola jawaban: Mix 1-5, sangat kritis pada error handling
   - **KEY CRITIQUE**: STS (1) pada A3 - "Sempat crash saat upload CSV dengan format invalid, error tidak ter-handle dengan baik"terns
   - Pola jawaban: Mix 4-5, balanced technical dan user perspective

10. **Pasa Kholilah** - Unsoed Teknologi Pangan (1-3 tahun)
    - Perspektif: Domain knowledge NBM/nutrition, validasi konteks food security
    - Pola jawaban: Mayoritas 4-5, sangat appreciate akurasi prediksi kalori
    - **KEY INSIGHT**: Validasi dari perspektif ilmu pangan dan gizi

## 📊 Distribusi Skor Contoh

### Statistik per Variabel
Berdasarkan contoh responses (UPDATED - with realistic low scores):

**A. Fungsionalitas (A1-A6)**
- Mean: 4.52 (90.4%)
- Pattern: Tinggi dengan 1 kritik serius (A3: Error Handling dari Raynard)
- Range: 1-5 (ada 1 skor STS, lebih realistis)
- **Concern**: A3 dapat skor 1 → error handling perlu improvement

**B. Kinerja (B1-B4)**
- Mean: 3.95 (79%)
- Pattern: Paling kritis, ada 2 skor TS (2), 2 skor CS (3)
- Range: 2-5 (Dhanang: loading lambat, Edy: consistency issue)
- **Insight**: Performance adalah area improvement utama

**C. UI/UX (C1-C9)**
- Mean: 4.40 (88%)
- Pattern: Masih tinggi tapi ada kritik pada responsive & error feedback
- Range: 2-5 (Defrinal: tablet UI, Didik: pesan error kurang jelas)
- **Concern**: C7 & C9 perlu diperbaiki untuk user experience lebih baik

**D. Efisiensi (D1-D6)**
- Mean: 4.68 (93.6%)
- Pattern: Paling stabil, workflow efisien tanpa kritik serius
- Range: 4-5 consistently

**Overall Average: 4.39 (87.8%)**

### Interpretasi Kategori
- **87.8%** → Masuk kategori **"Sangat Baik"** (81%-100%)
- Variabel A, C, D > 80% → **"Sangat Baik"**
- Variabel B (79%) → **"Baik"** (61%-80%) → **masih layak** tapi perlu improvement
- Overall > 80% → **"Layak Digunakan"** dengan catatan perbaikan performance

### Areas for Improvement (Realistis!)
1. **A3 - Error Handling** (1 skor STS): Crash saat upload data invalid
2. **B1 - Loading Time** (2 skor TS): Loading >5 detik untuk data besar
3. **B2 - Consistency Performance** (2 skor TS): Performance fluktuatif
4. **C7 - Pesan Error** (2 skor TS): Error message masih teknis
5. **C9 - Responsive Tablet** (2 skor TS): UI terpotong di tablet

## 🎯 Pola Jawaban Realistis ✅
1. **Tidak semua perfect (5)**: Ada variasi 1-5 untuk realisme tinggi
2. **Konsisten dengan role**: 
   - Admin fokus stability (4-5, tidak ada kritik serius)
   - Pemerintah paling kritis (mix 2-5) karena daily users
   - Akademisi: mahasiswa testing edge cases (Raynard kasih skor 1)
3. **Skor rendah ada reasoning KONKRET**: 
   - A3 Raynard (1): Crash saat upload invalid CSV → legitimate bug
   - B1 Dhanang (2): Loading >5 detik → real performance issue
   - B2 Edy & Didik (2-3): Consistency naik-turun → infrastructure concern
   - C7 Didik (2): Error message teknis (SQL/Laravel) → UX issue
   - C9 Defrinal (2): Tablet UI terpotong → responsive design gap
4. **Distribusi statistik normal**: 
   - 1 skor STS (1) = 0.4% dari 250 responses
   - 4 skor TS (2) = 1.6%
   - 2 skor CS (3) = 0.8%
   - Sisanya 4-5 = 97.2%
   - **Healthy distribution** untuk sistem yang baik tapi tidak sempurna

### Red Flags yang Berhasil Dihindari
❌ **Semua responden kasih 5 semua** → tidak kredibel (FIXED!)
❌ **Tidak ada skor 1-2** → terlalu sempurna (FIXED!)
❌ **Pattern identical** → terlihat fake/coordinated (FIXED!)
❌ **Skor rendah tanpa reasoning** → arbitrary
✅ **Mix 1-5 dengan dominasi 4-5 + kritik konkret** → realistic & defensible!

## 📝 Cara Menggunakan Contoh Ini

### Opsi 1: Testing Perhitungan
1. Import `contoh_responses_uat.csv` ke Excel/Google Sheets
2. Hitung Skor Total per pertanyaan: `COUNTIF()` untuk setiap Likert value × weight
3. Verifikasi rumus: `Σ(n × weight)` dari Tabel 24-27 di thesis
4. Cek apakah hasil ~87.8% (updated dengan skor realistis)

### Opsi 2: Template Input Manual
1. Buka Google Form responses setelah UAT real
2. Export ke Google Sheets
3. Gunakan struktur columns dari `contoh_responses_uat.csv`
4. Copy-paste values, lalu kalkulasi dengan rumus yang sama

### Opsi 3: Demo untuk Pembimbing
1. Show contoh responses ini sebagai "expected output" dari Google Form
2. **Highlight**: Distribusi realistis dengan 1 skor STS, 4 skor TS (tidak sempurna!)
3. Jelaskan reasoning untuk setiap kritik (crash, loading lambat, UI tablet, dll)
4. Demonstrasi cara menghitung dari raw responses → Tabel 24-27 → Tabel 29-32
5. Tunjukkan "Areas for Improvement" sebagai bagian dari honest evaluation
2. Jelaskan distribusi skor dan reasoning
3. Demonstrasi cara menghitung dari raw responses → Tabel 24-27 → Tabel 29-32

## 🔢 Formula Perhitungan (Quick Reference)

Untuk setiap pertanyaan (misal A1):
```
Skor Total A1 = (n_STS × 1) + (n_TS × 2) + (n_CS × 3) + (n_S × 4) + (n_SS × 5)
```

Contoh perhitungan A1 dari file CSV:
- STS (1): 0 orang
- TS (2): 0 orang  
- CS (3): 0 orang
- S (4): 2 orang (Defrinal, Didik)
- SS (5): 8 orang

```
Skor Total A1 = (0×1) + (0×2) + (0×3) + (2×4) + (8×5) = 0 + 0 + 0 + 8 + 40 = 48
Mean A1 = 48 / 10 = 4.80
Persentase A1 = (4.80 / 5) × 100% = 96%
```

## ⚠️ Catatan Penting

### Sebelum UAT Real
- **JANGAN gunakan data ini sebagai data real** di thesis final!
- Data ini hanya untuk testing dan pemahaman
- UAT real harus dilakukan dengan responden sungguhan

### Saat UAT Real
- Responden harus **benar-benar menggunakan sistem** sebelum mengisi form
- Brief demo 15-30 menit: login, navigasi, prediksi NBM, export, visualisasi
- Jawaban harus **jujur dan independen** (tidak boleh diskusi/coordinated)
- Catat feedback kualitatif di Bagian E untuk improvement insights

### Setelah UAT Real
- Bandingkan distribusi skor real vs contoh ini
- Jika ada perbedaan signifikan (>10%), analisa penyebabnya
- Update Tabel 24-27, 29-32, 33 dengan data real
- Jika overall < 80%, mungkin perlu system improvements atau re-UAT

## 📌 Checklist Validasi

Sebelum menggunakan data UAT (real atau contoh) di thesis:

- [ ] Semua 10 responden terisi lengkap (tidak ada missing data)
- [ ] Role distribution correct: 2 Admin, 5 Pemerintah, 3 Akademisi
- [ ] Pengalaman range realistic: mayoritas 3-10 tahun
- [ ] Tidak ada skor ekstrem (semua 1 atau semua 5)
- [ ] Overall average > 80% (layak digunakan)
- [ ] Setiap variabel > 61% (minimal kategori "Baik")
- [ ] Ada variasi skor untuk kredibilitas
- [ ] Data source jelas: Google Form export atau manual entry

---

**Pertanyaan?** Cek kembali:
- `google_form_uat_questions.md` → daftar 25 pertanyaan lengkap
- `laporan_tugas_akhir.md` Section 4.8 → metodologi dan tabel UAT
- `responden.csv` → daftar 10 responden yang akan isi form

**Ready untuk UAT real!** 🚀
