# UML Diagrams untuk Thesis - Panduan Lengkap

## 📋 Daftar Diagram yang Dibuat

### 1. Activity Diagram - Alur Prediksi Konsumsi Kalori NBM
**File:** `activity_diagram.puml`  
**Output:** `activity_diagram.png`  
**Tujuan:** Menggambarkan workflow lengkap sistem prediksi dari perspektif user

**Komponen yang Dicakup:**
- ✅ Login dan autentikasi user
- ✅ Input parameter prediksi (kelompok, komoditi, jumlah bulan)
- ✅ Validasi input dan ketersediaan data
- ✅ Query data historis dari MySQL
- ✅ Komunikasi Laravel → FastAPI
- ✅ Preprocessing data (MinMaxScaler, sequences)
- ✅ LSTM inference + HuberRegressor
- ✅ Ensemble weighting (70/30)
- ✅ Confidence interval calculation
- ✅ Rendering hasil (tabel, chart, CI)
- ✅ Export ke Excel workflow
- ✅ Error handling di setiap decision point

**Referensi di Thesis:** Gambar 25 (BAB III - Metode Penelitian)

---

### 2. Sequence Diagram - Interaksi Komponen Sistem
**File:** `sequence_diagram.puml`  
**Output:** `sequence_diagram.png`  
**Tujuan:** Menunjukkan interaksi temporal message flow antar komponen

**Aktor dan Komponen:**
- 👤 User (Pemerintah)
- 🌐 Browser (Livewire)
- ⚙️ Laravel Controller
- 🗄️ MySQL Database
- 🤖 FastAPI ML Service
- 🧠 LSTM Model
- 📊 HuberRegressor
- 💾 Redis Cache

**Message Flow yang Digambarkan:**
1. User request → Browser → Laravel
2. Laravel → MySQL (query historical data)
3. Laravel → FastAPI (POST /predict)
4. FastAPI → LSTM + Huber (inference)
5. FastAPI → Laravel (JSON response)
6. Laravel → MySQL (save prediction_histories)
7. Laravel → Redis (cache result)
8. Laravel → Browser → User (display results)
9. Export workflow (session → redirect → generate Excel)

**Referensi di Thesis:** Gambar 26 (BAB III - Metode Penelitian)

---

## 🛠️ Cara Generate Diagram PNG

### Opsi 1: Menggunakan Script Otomatis

#### Windows:
```cmd
cd d:\sikolbia\docs\thesis
generate_diagrams.bat
```

#### Linux/Mac:
```bash
cd /d/sikolbia/docs/thesis
chmod +x generate_diagrams.sh
./generate_diagrams.sh
```

Script akan otomatis:
1. Download PlantUML JAR jika belum ada
2. Check Java installation
3. Generate PNG dari semua .puml files
4. Output: `activity_diagram.png` dan `sequence_diagram.png`

---

### Opsi 2: Manual dengan PlantUML

#### Install PlantUML:
```bash
# Download PlantUML JAR
curl -L -o plantuml.jar https://github.com/plantuml/plantuml/releases/download/v1.2024.7/plantuml-1.2024.7.jar

# Atau via package manager
# Ubuntu/Debian:
sudo apt-get install plantuml

# macOS:
brew install plantuml

# Windows:
# Download dari https://plantuml.com/download
```

#### Generate PNG:
```bash
cd docs/thesis/images

# Generate single diagram
java -jar ../../../plantuml.jar -tpng activity_diagram.puml

# Generate all .puml files
java -jar ../../../plantuml.jar -tpng *.puml

# Generate with specific output directory
java -jar ../../../plantuml.jar -o output/ -tpng *.puml
```

---

### Opsi 3: Online Editor (Tanpa Install)

#### PlantUML Online Editor:
1. Buka: https://www.plantuml.com/plantuml/
2. Copy-paste isi file `.puml`
3. Klik "Submit" untuk preview
4. Download PNG dari browser

#### Mermaid Live Editor:
1. Buka: https://mermaid.live/
2. Copy-paste Mermaid syntax dari `UML_DIAGRAMS.md`
3. Export as PNG/SVG

---

### Opsi 4: VS Code Extension

#### Install Extension:
- **PlantUML** by jebbs
- **Markdown Preview Mermaid Support**

#### Cara Pakai:
1. Buka file `.puml` di VS Code
2. Tekan `Alt+D` untuk preview
3. Right-click → Export → PNG

---

## 📐 Format Diagram yang Tersedia

### PlantUML (.puml)
- ✅ Format text-based yang mudah di-version control
- ✅ Syntax sederhana dan readable
- ✅ Bisa generate PNG, SVG, PDF
- ✅ Cocok untuk dokumentasi formal (thesis)
- 📁 Files: `activity_diagram.puml`, `sequence_diagram.puml`

### Mermaid (.md)
- ✅ Native Markdown support
- ✅ Auto-render di GitHub/GitLab
- ✅ Interactive di beberapa viewer
- ✅ Cocok untuk dokumentasi online
- 📁 File: `UML_DIAGRAMS.md`

---

## 🎨 Customization Diagram

### Mengubah Warna (PlantUML):
```plantuml
skinparam backgroundColor #FEFEFE
skinparam activityBackgroundColor #E3F2FD
skinparam activityBorderColor #1976D2
skinparam activityFontColor #000000
```

### Mengubah Ukuran Font:
```plantuml
skinparam defaultFontSize 12
skinparam activityFontSize 11
```

### Export dengan DPI Tinggi:
```bash
java -jar plantuml.jar -tpng -DPLANTUML_LIMIT_SIZE=8192 diagram.puml
```

---

## 📊 Integrasi dengan Thesis

### Referensi Gambar di LaTeX/Markdown:

```markdown
![Gambar 25. Activity Diagram](images/activity_diagram.png)

**Gambar 25.** Activity Diagram menunjukkan workflow lengkap...
```

### Path Relatif yang Digunakan:
- Thesis: `docs/thesis/laporan_tugas_akhir.md`
- Diagrams: `docs/thesis/images/activity_diagram.png`
- Relative path: `images/activity_diagram.png`

---

## ✅ Checklist Integrasi

- [x] Activity Diagram (.puml) created
- [x] Sequence Diagram (.puml) created
- [x] Mermaid version (UML_DIAGRAMS.md) created
- [x] Generate scripts (Windows + Linux) created
- [x] Referensi di thesis (Gambar 25-26) added
- [x] Caption dan penjelasan lengkap added
- [ ] PNG files generated (run script)
- [ ] Review diagram dengan pembimbing
- [ ] Verify rendering di PDF thesis

---

## 🐛 Troubleshooting

### Error: "Java not found"
**Solusi:**
```bash
# Install Java
# Ubuntu/Debian:
sudo apt install default-jre

# Windows:
# Download dari https://www.oracle.com/java/technologies/downloads/

# macOS:
brew install openjdk
```

### Error: "Cannot find plantuml.jar"
**Solusi:**
```bash
# Download manual
curl -L -o plantuml.jar https://github.com/plantuml/plantuml/releases/download/v1.2024.7/plantuml-1.2024.7.jar

# Atau gunakan online editor sebagai fallback
```

### Diagram PNG Terlalu Kecil
**Solusi:**
```bash
# Increase resolution
java -DPLANTUML_LIMIT_SIZE=16384 -jar plantuml.jar -tpng diagram.puml
```

### Font tidak Muncul di PNG
**Solusi:**
```bash
# Install font dependencies
# Ubuntu:
sudo apt install fonts-dejavu fonts-liberation

# Windows: Pastikan Segoe UI font installed
```

---

## 📚 Dokumentasi Tambahan

- **PlantUML Documentation:** https://plantuml.com/
- **PlantUML Activity Diagram Guide:** https://plantuml.com/activity-diagram-beta
- **PlantUML Sequence Diagram Guide:** https://plantuml.com/sequence-diagram
- **Mermaid Documentation:** https://mermaid.js.org/
- **UML Best Practices:** https://www.uml-diagrams.org/

---

## 📝 Catatan untuk Dosen Pembimbing

**Diagram yang Tersedia:**
1. **Gambar 25 - Activity Diagram**: Menunjukkan complete user journey dari login hingga export, dengan semua decision points dan error handling.
2. **Gambar 26 - Sequence Diagram**: Menggambarkan interaksi message flow antar 8 komponen sistem (User, Browser, Laravel, MySQL, FastAPI, LSTM, Huber, Redis).

**Keunggulan Diagram:**
- ✅ Mencakup semua alur utama dan alternatif
- ✅ Detail error handling di setiap validation point
- ✅ Menunjukkan integrasi Laravel-FastAPI secara eksplisit
- ✅ Include caching strategy dengan Redis
- ✅ Export workflow dijelaskan lengkap

**Format yang Disediakan:**
- PlantUML (.puml) → untuk generate PNG formal
- Mermaid (.md) → untuk preview online
- Generated PNG → siap insert ke thesis

---

## 🔄 Update Log

- **2025-12-18**: Initial creation - Activity & Sequence Diagrams
- **2025-12-18**: Added Mermaid version for online rendering
- **2025-12-18**: Created generation scripts (Windows + Linux)
- **2025-12-18**: Integrated into thesis as Gambar 25-26

---

**Status:** ✅ READY FOR THESIS INTEGRATION

Diagram sudah siap untuk di-generate dan diintegrasikan ke dalam thesis. Silakan run `generate_diagrams.bat` (Windows) atau `generate_diagrams.sh` (Linux) untuk generate PNG files.
