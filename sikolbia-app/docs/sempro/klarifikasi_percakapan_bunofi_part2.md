# KLARIFIKASI PERCAKAPAN DENGAN BU NOFI - PART 2
**Tanggal:** 20 Oktober 2025  
**Pembimbing:** Ir. Nofiyati, S.Kom., M.Kom., IPM.  
**Mahasiswa:** Jehian Athaya Tsani Az Zuhry (H1D022006)

---

## 📝 KONTEKS PERCAKAPAN PART 2

Lanjutan feedback Bu Nofi tentang **struktur BAB 3** dan **metodologi penelitian** yang perlu diperbaiki.

---

## 📋 FEEDBACK BU NOFI - BAB 3

### **1. Judul BAB 3**
❌ **Yang Sekarang:** "METODOLOGI"  
✅ **Yang Benar:** "METODE PENELITIAN"

### **2. Struktur BAB 3 yang Diperlukan:**

#### **3.1 Data dan Alat Penelitian**
**Komponen yang harus ada:**
- **Data Primer:** Apa, dari mana, bagaimana diperoleh
- **Data Sekunder:** Sumber dan jenis data pendukung
- **Wawancara:** Jika ada, tuliskan detail
- **Alat/Tools:** Software, hardware yang digunakan

#### **3.2 Metode Penelitian**
**Komponen yang harus ada:**
- **Tahapan yang mengakomodir RnD dan CRISP-DM**
- **Diagram RnD dibuat sendiri** (jangan ambil dari Google)
- **Integrasi jelas antara RnD dan CRISP-DM**

---

## 📊 SOLUSI REVISI BAB 3

### **3.1 Data dan Alat Penelitian**

#### **a. Data Primer**
- **Sumber:** Database Transaksi NBM SIKOLBIA
- **Periode:** 1993-2024 (31 tahun)
- **Jumlah:** 41,316 records
- **Variabel Utama:** 
  - kode_kelompok, kode_komoditi
  - tahun, bulan
  - bahan_makanan, masukan, keluaran
  - kalori_hari (target prediksi)
- **Cara Perolehan:** Query database sistem

**Sampel Data (5 baris):**
```
| kode_kelompok | kode_komoditi | tahun | bulan | kalori_hari |
|---------------|---------------|-------|--------|-------------|
| 01            | 0101         | 1993  | 1      | 850.5       |
| 01            | 0102         | 1993  | 1      | 120.3       |
| 02            | 0201         | 1993  | 1      | 185.7       |
| 03            | 0301         | 1993  | 1      | 95.2        |
| 04            | 0401         | 1993  | 1      | 78.9        |
```

#### **b. Data Sekunder**
- **Publikasi NBM Badan Pangan Nasional**
- **Dokumentasi API NBM Kementerian Pertanian**
- **Literature review jurnal forecasting LSTM**
- **Best practices CRISP-DM methodology**

#### **c. Wawancara**
- **Narasumber:** Stakeholder Badan Pangan Nasional (jika diperlukan)
- **Tujuan:** Validasi kebutuhan sistem dan interpretasi hasil
- **Metode:** Structured interview

#### **d. Alat Penelitian**

**Software:**
- **Development:** Laravel 11, FastAPI, Docker
- **Machine Learning:** Python (TensorFlow/Keras), Scikit-learn
- **Database:** MySQL 8.0
- **Analysis:** Jupyter Notebook, Pandas, NumPy

**Hardware:**
- **Development Machine:** PC dengan minimum 8GB RAM
- **GPU:** NVIDIA untuk training LSTM (jika tersedia)
- **Storage:** Minimum 100GB untuk dataset dan model

---

### **3.2 Metode Penelitian**

#### **Integrasi RnD dan CRISP-DM**

**Tahapan Penelitian:**

```
📋 RESEARCH & DEVELOPMENT (RnD) FRAMEWORK
├── 1. Research & Collection Preliminary → CRISP-DM: Business Understanding
├── 2. Research Planning → CRISP-DM: Data Understanding  
├── 3. Early Product Development → CRISP-DM: Data Preparation
├── 4. Expert Validation → CRISP-DM: Modeling (Initial)
├── 5. Product Revision → CRISP-DM: Modeling (Refined)
├── 6. Early Test → CRISP-DM: Evaluation
├── 7. Product Revision → CRISP-DM: Deployment (Prep)
├── 8. Field Test → CRISP-DM: Deployment (Test)
├── 9. Final Product Revision → CRISP-DM: Deployment (Final)
└── 10. Dissemination → Documentation & Knowledge Transfer
```

#### **Diagram RnD Custom (Dibuat Sendiri):**

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   RESEARCH      │    │   DEVELOPMENT   │    │   VALIDATION    │
│   PHASE         │    │   PHASE         │    │   PHASE         │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ 1. Literature   │    │ 3. System       │    │ 6. Model        │
│    Review       │───▶│    Design       │───▶│    Testing      │
│                 │    │                 │    │                 │
│ 2. Data         │    │ 4. LSTM         │    │ 7. Performance  │
│    Collection   │    │    Implementation│    │    Evaluation   │
└─────────────────┘    │                 │    │                 │
                       │ 5. Web System   │    │ 8. User         │
                       │    Development  │    │    Validation   │
                       └─────────────────┘    └─────────────────┘
                                │                       │
                                ▼                       ▼
                       ┌─────────────────┐    ┌─────────────────┐
                       │   DEPLOYMENT    │    │   DOCUMENTATION │
                       │   PHASE         │    │   PHASE         │
                       ├─────────────────┤    ├─────────────────┤
                       │ 9. System       │    │ 10. Research    │
                       │    Integration  │    │     Report      │
                       │                 │    │                 │
                       │    Final        │    │     Knowledge   │
                       │    Testing      │    │     Transfer    │
                       └─────────────────┘    └─────────────────┘
```

#### **Detail Tahapan:**

**PHASE 1: RESEARCH (Bulan 1)**
- **RnD Step 1-2** ↔ **CRISP-DM: Business & Data Understanding**
- Literature review, data collection, problem definition

**PHASE 2: DEVELOPMENT (Bulan 2)**  
- **RnD Step 3-5** ↔ **CRISP-DM: Data Preparation & Modeling**
- System design, LSTM implementation, web development

**PHASE 3: VALIDATION (Bulan 3)**
- **RnD Step 6-8** ↔ **CRISP-DM: Evaluation & Deployment**
- Testing, validation, performance assessment

**PHASE 4: FINALIZATION**
- **RnD Step 9-10** ↔ **Documentation & Knowledge Transfer**
- Final integration, documentation, dissemination

---

## 📋 **REVISI PROPOSAL YANG DIPERLUKAN**

### **1. Ubah Judul BAB 3**
```markdown
## BAB III. METODE PENELITIAN
```

### **2. Restructure BAB 3**
```markdown
### 3.1 Data dan Alat Penelitian
a. Data Primer
b. Data Sekunder  
c. Wawancara (jika ada)
d. Alat Penelitian

### 3.2 Metode Penelitian
a. Kerangka Penelitian RnD
b. Integrasi dengan CRISP-DM
c. Tahapan Penelitian Detail
d. Timeline Pelaksanaan
```

### **3. Tambah Informasi Dataset di Batasan Masalah (BAB 1)**
```markdown
b. Data yang digunakan adalah data NBM Indonesia periode 1993-2024 
   dengan total 41,316 records yang bersumber dari Badan Pangan 
   Nasional, Badan Pusat Statistika, dan Pusat Data dan Sistem 
   Informasi Kementerian Pertanian.
```

### **4. Persingkat Latar Belakang (BAB 1)**
- Dari 3 halaman → 1.5 halaman
- Fokus pada core problem dan solution
- Tambah dataset info dan 5 sampel data

---

## ✅ **ACTION ITEMS IMMEDIATE**

- [ ] **Revisi Judul BAB 3:** "METODOLOGI" → "METODE PENELITIAN"
- [ ] **Restructure BAB 3:** 3.1 Data & Alat, 3.2 Metode Penelitian
- [ ] **Buat Diagram RnD Custom:** Jangan pakai dari Google
- [ ] **Detail Data Primer:** 41,316 records + 5 sampel data
- [ ] **Update Batasan Masalah:** Tambah info dataset
- [ ] **Persingkat Latar Belakang:** 3 halaman → 1.5 halaman

---

## 🎯 **KESIMPULAN**

Bu Nofi memberikan feedback **struktural yang sangat penting** untuk:
1. **Terminology yang benar** (Metode Penelitian vs Metodologi)
2. **Struktur akademik yang proper** (Data & Alat terpisah dari Metode)
3. **Originalitas diagram** (buat sendiri, jangan ambil dari Google)
4. **Kelengkapan informasi data** (primer, sekunder, alat penelitian)

**Next Step:** Implement semua feedback Bu Nofi untuk memperbaiki struktur dan konten BAB 3 sesuai standar penulisan akademik yang benar.