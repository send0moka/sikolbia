# KLARIFIKASI PERCAKAPAN DENGAN BU DEVI
**Tanggal:** 19 Oktober 2025  
**Pembimbing:** Devi Astri Nawangnugraeni, S.Pd., M.Kom.  
**Mahasiswa:** Jehian Athaya Tsani Az Zuhry (H1D022006)

---

## 📝 KONTEKS PERCAKAPAN

Setelah presentasi sempro, Bu Devi memberikan masukan terkait BAB 3 metodologi dan implementasi sistem website. Terdapat kesenjangan antara jawaban yang saya berikan dengan scope sebenarnya dari proposal penelitian.

---

## ❌ JAWABAN YANG TIDAK SESUAI DENGAN PROPOSAL

### Pertanyaan Bu Devi vs Jawaban Saya

| **Pertanyaan Bu Devi** | **Jawaban Saya** | **Status** |
|------------------------|------------------|------------|
| "Sistem itu masih terlalu sedikit dibahas, website dijelaskan dan ditampilkan hasilnya itu apa?" | Akan ada opsi user input menu makanan atau komoditi pangan | ❌ **TIDAK SESUAI** |
| "Yang diprediksi itu apanya?" | Prediksi kalori harian personal | ❌ **TIDAK SESUAI** |
| "Untuk perkeluarga atau pribadi?" | Pribadi | ❌ **TIDAK SESUAI** |
| "Goals setelah diprediksi gimana?" | User bisa monitor apakah kalori sesuai standar nasional; pemerintah bisa ambil keputusan | ⚠️ **SEBAGIAN BENAR** |
| "Ada hasil yang ditampilkan di website?" | Ada lewat dashboard | ✅ **BENAR** |
| "Record data prediksi disimpan di database?" | Iya | ⚠️ **PERLU KLARIFIKASI** |
| "Perlu authentication?" | Iya, pakai Google login, ada rate limiting per user | ❌ **TIDAK SESUAI** |

---

## ✅ KLARIFIKASI BERDASARKAN PROPOSAL SEBENARNYA

### 🎯 **Goals Sebenarnya Penelitian**

Berdasarkan proposal yang telah disusun:

**Judul:** "Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian"

**Tujuan Utama:**
1. **Mengimplementasikan model LSTM enhanced ensemble** untuk prediksi konsumsi kalori harian **NASIONAL**
2. **Mengintegrasikan model ke sistem web** dengan arsitektur Laravel + FastAPI + Docker
3. **Memberikan sistem peringatan dini** untuk **ketahanan pangan nasional**

### 🎯 **Target User Sebenarnya**

**Primary Users:**
- **Pemerintah & Pengambil Kebijakan**
  - Badan Pangan Nasional
  - Kementerian Pertanian
  - Bappenas

**Secondary Users:**
- **Akademisi & Peneliti**
- **Masyarakat** (akses informasi public)

### 📊 **Yang Diprediksi Sebenarnya**

**BUKAN:** Kalori personal individual  
**TETAPI:** **Konsumsi kalori agregat NASIONAL** per komoditi per kelompok per hari

**Berdasarkan Database Structure:**
Data input dari tabel `transaksi_nbms` memiliki granularitas **per komoditi** dengan kolom:
- `kode_kelompok` + `kode_komoditi` (spesifik komoditi)
- `tahun` + `bulan` (temporal)
- `kalori_hari` (target prediksi per komoditi)

**Contoh Output yang Benar:**
```json
{
  "prediction_date": "2025-01",
  "predictions": [
    {
      "kelompok": "01 - Padi-padian", 
      "komoditi": "0101 - Beras",
      "predicted_kalori_hari": 850.5,
      "confidence_interval": {"lower": 820.2, "upper": 880.8}
    },
    {
      "kelompok": "02 - Umbi-umbian",
      "komoditi": "0201 - Ubi Kayu", 
      "predicted_kalori_hari": 185.3,
      "confidence_interval": {"lower": 175.1, "upper": 195.5}
    }
  ],
  "total_national_calories": 2750.8,
  "aggregation_method": "SUM(predicted_kalori_hari) by komoditi"
}
```

**Proses Agregasi:**
1. **Input:** Data historis per komoditi dari `transaksi_nbms`
2. **Processing:** LSTM prediksi kalori_hari per komoditi 
3. **Output:** Prediksi per komoditi + agregasi nasional
4. **Dashboard:** Breakdown detail + summary nasional

### 🖥️ **Sistem Website Sebenarnya**

**1. Government Dashboard:**
- View data NBM historis (1993-2024)
- Run LSTM prediction untuk forecast nasional
- Early warning system untuk food security
- Export reports untuk policy making

**2. Research Dashboard:**
- Access dataset NBM untuk research
- Model performance metrics (MAPE, RMSE, MAE)
- Compare dengan baseline models
- Download results untuk publikasi

**3. Public Dashboard:**
- View tren konsumsi kalori nasional
- Educational content tentang ketahanan pangan
- Public awareness information

### 🔐 **Authentication Sebenarnya**

**BUKAN:** Google OAuth untuk user personal  
**TETAPI:** 
- **Admin login** untuk government officials
- **Researcher login** untuk akademisi
- **Public access** tanpa login untuk dashboard umum

---

## 🔄 **REVISI YANG DIPERLUKAN UNTUK BAB 3**

### Perlu Ditambahkan di Tahapan RnD:

#### **c. Early Product Development**
Membangun struktur dasar model LSTM ensemble dan merancang **antarmuka aplikasi berbasis web untuk stakeholder pemerintah dan peneliti**. Implementasi preprocessing pipeline untuk data NBM dan pengembangan baseline models untuk comparison. **Sistem web dirancang untuk menyajikan prediksi konsumsi kalori nasional melalui dashboard analytics yang mendukung pengambilan keputusan kebijakan ketahanan pangan.**

#### **f. Early Test**
Implementasi lengkap model LSTM enhanced ensemble dengan metodologi CRISP-DM. **Pengujian dilakukan terhadap performa model menggunakan data training dan validation dengan metrik evaluasi RMSE, MAE, dan MAPE. Sistem web diuji untuk memastikan dapat menyajikan prediksi konsumsi kalori nasional secara real-time kepada stakeholder pemerintah melalui dashboard yang user-friendly.**

#### **h. Field Test**
Melakukan pengujian komprehensif menggunakan data testing (2020-2024) dalam kondisi real-world scenarios. **Testing mencakup accuracy assessment model, performa sistem web dalam melayani permintaan prediksi nasional, dan usability evaluation dengan potential users dari kalangan pemerintah dan peneliti.**

---

## 📋 **KESIMPULAN KLARIFIKASI**

1. **Scope Penelitian:** National-level food security forecasting, bukan personal calorie tracking
2. **Target Output:** Prediksi konsumsi kalori agregat nasional untuk decision support
3. **Primary Users:** Government officials dan researchers, bukan individual consumers
4. **Sistem Website:** Dashboard analytics untuk stakeholder, bukan consumer app
5. **Authentication:** Role-based untuk government/research access, bukan mass user registration

---

## 🎯 **ACTION ITEMS**

- [ ] **Revisi BAB 3** dengan focus pada implementasi sistem untuk stakeholder pemerintah
- [ ] **Perjelas user flow** untuk government officials dan researchers
- [ ] **Detail implementasi dashboard** analytics untuk decision support
- [ ] **Klarifikasi data flow** dari NBM historical ke prediction output
- [ ] **Spesifikasi technical requirements** untuk sistem real-time forecasting

---

**Catatan:** Percakapan ini menjadi pembelajaran penting bahwa jawaban harus selalu konsisten dengan scope dan tujuan penelitian yang telah ditetapkan dalam proposal.