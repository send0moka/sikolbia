# User's Guide
## Sistem Informasi Konsumsi Lahan Bibit Iklim dan Alamat - Modul Konsumsi Pangan

### Informasi Dokumen
- **System Name**: SIKOLBIA - Modul Konsumsi Pangan (NBM) v2.0
- **Document Type**: User's Guide
- **Version**: 2.0
- **Date**: Oktober 2025
- **Target Audience**: Semua level pengguna modul konsumsi pangan

---

## 1. Pengenalan Sistem

### 1.1 Tentang Modul Konsumsi Pangan SIKOLBIA
Modul Konsumsi Pangan dari Sistem Informasi Konsumsi Lahan Bibit Iklim dan Alamat (SIKOLBIA) adalah platform web yang menyediakan:
- **Data NBM (Neraca Bahan Makanan)** nasional dari 1993-2024
- **120 komoditas** dalam 11 kelompok komoditas
- **Prediksi konsumsi kalori** menggunakan Artificial Intelligence
- **Analisis SHAP** untuk interpretabilitas model AI
- **Tools penelitian** khusus konsumsi pangan untuk akademisi dan peneliti

### 1.2 Level Pengguna

#### 🔵 **Level 1: Administrator**
- **Siapa**: Pegawai Bagian Pengembangan Sistem Informasi Pusdatin Kementan
- **Akses**: Full system access
- **Fungsi Utama**: Mengelola sistem, user, dan data

#### 🟢 **Level 2: Government Users**
- **Siapa**: Pemerintah/Pengambil Kebijakan (BPN, Kementan, Bappenas, dll)
- **Akses**: Advanced analytics dan comprehensive data
- **Fungsi Utama**: Policy analysis dan strategic planning

#### 🟡 **Level 3: Academic Users**
- **Siapa**: Akademisi dan Peneliti
- **Akses**: Historical data dan research tools
- **Fungsi Utama**: Penelitian dan analisis akademik

#### 🟠 **Level 4: Public Users**
- **Siapa**: Masyarakat umum
- **Akses**: Informasi publik dan basic statistics
- **Fungsi Utama**: Mengakses informasi pangan publik

---

## 2. Akses Sistem

### 2.1 URL Akses
- **Website Utama**: `http://localhost:8000/ketersediaan/`
- **Dashboard Pemerintah**: `http://localhost:8000/ketersediaan/pemerintah/`
- **Dashboard Akademisi**: `http://localhost:8000/ketersediaan/akademisi/`
- **Admin Panel**: `http://localhost:8000/admin/konsumsi-pangan/`
- **API Documentation**: `http://localhost:8000/dokumentasi`

**Catatan**: Sistem diakses via web browser menggunakan nginx localhost. Tidak ada mobile apps - akses hanya melalui web interface.

### 2.2 Cara Registrasi

#### 📋 **Untuk Pengguna Pemerintah**

**Langkah 1: Persiapan Dokumen**
- Surat pengantar resmi dari instansi
- Identitas diri (KTP/ID Card)
- Email instansi (domain resmi pemerintah)
- **Template surat tersedia**: Klik tombol "Unduh Template" yang akan redirect ke Google Drive

**Langkah 2: Proses Registrasi**
1. Kunjungi `http://localhost:8000/ketersediaan/pemerintah/pendaftaran/`
2. Isi formulir dengan data lengkap:
   - Nama lengkap
   - Email instansi
   - Nama instansi
   - Jabatan/posisi
   - Nomor telepon
3. Upload dokumen yang diperlukan
4. Submit formulir

**Langkah 3: Menunggu Approval**
- Admin akan memverifikasi dokumen (1-3 hari kerja)
- Notifikasi approval/rejection via email ke jehianathayata@gmail.com
- Account activation setelah approval

#### 📚 **Untuk Pengguna Akademisi**

**Langkah 1: Persiapan Dokumen**
- Surat keterangan dari institusi akademik
- Proposal penelitian NBM (jika ada)
- Identitas diri (KTP/Student ID)
- **Template surat tersedia**: Klik tombol "Unduh Template" yang akan redirect ke Google Drive

**Langkah 2: Proses Registrasi**
1. Kunjungi `http://localhost:8000/ketersediaan/akademisi/pendaftaran/`
2. Isi formulir registrasi:
   - Nama lengkap
   - Email akademik (.ac.id atau .edu)
   - Nama institusi
   - Program studi/departemen
   - Topik penelitian NBM
3. Upload dokumen pendukung
4. Submit aplikasi

**Langkah 3: Approval Process**
- Review dokumen dan proposal (2-5 hari kerja)
- Possible interview untuk research yang sensitif terkait NBM
- Account activation dengan limited access

---

## 3. Panduan Penggunaan per Level User

### 3.1 🔵 Admin User Guide

#### Dashboard Admin
**URL**: `http://localhost:8000/admin/konsumsi-pangan/`

**Fitur Utama:**
1. **System Overview NBM**
   - User statistics modul konsumsi pangan
   - System performance metrics
   - Data quality indicators NBM
   - ML model status untuk prediksi konsumsi

2. **User Management**
   - Approve/reject registrations untuk akses NBM
   - Manage user permissions (Admin/Pemerintah/Akademisi/Publik)
   - View user activity logs
   - Disable/enable accounts

3. **Data Management NBM**
   - Input/upload data NBM (1993-2024)
   - Validate data quality 120 komoditas
   - Manage data sources konsumsi pangan
   - Export/backup data NBM

4. **ML Model Management NBM**
   - Monitor model performance prediksi konsumsi
   - Retrain models dengan data terbaru
   - Update SHAP analysis untuk interpretabilitas
   - Configure prediction parameters kalori

#### Tugas Harian Admin:
- [ ] Check pending user registrations
- [ ] Review data validation alerts
- [ ] Monitor system performance
- [ ] Respond to user support tickets

### 3.2 🟢 Government User Guide

#### Dashboard Pemerintah
**URL**: `http://localhost:8000/ketersediaan/pemerintah/`

**Fitur Utama:**
1. **Executive Summary**
   - Key performance indicators (KPI)
   - Critical alerts dan warnings
   - Policy impact analysis
   - Regional comparisons

2. **AI Predictions**
   - NBM forecasts (1-12 bulan)
   - Confidence intervals
   - SHAP explanations
   - Scenario analysis

3. **Advanced Analytics**
   - Trend analysis
   - Correlation studies
   - Regional breakdowns
   - Seasonal patterns

4. **Data Export**
   - Full dataset exports
   - Custom report generation
   - Scheduled reports
   - API access

#### Workflow Government User:

**Untuk Policy Analysis:**
1. **Access Predictions**
   ```
   Government Dashboard → Predictions → Select Commodity → View Forecast
   ```

2. **Understand AI Reasoning**
   ```
   Prediction Results → SHAP Analysis → View Feature Importance → Read Insights
   ```

3. **Export Data for Further Analysis**
   ```
   Analytics → Export → Select Format → Download
   ```

4. **Generate Policy Reports**
   ```
   Reports → Generate Custom Report → Select Parameters → Export PDF
   ```

### 3.3 🟡 Academic User Guide

#### Dashboard Akademisi
**URL**: `http://localhost:8000/ketersediaan/akademisi/`

**Fitur Utama:**
1. **Historical Data Access**
   - Time series data (5+ years)
   - Statistical summaries
   - Data quality metrics
   - Methodology documentation

2. **Analysis Tools**
   - Basic statistical analysis
   - Correlation analysis
   - Trend visualization
   - Export capabilities

3. **Research Collaboration**
   - Share analysis results
   - Citation management
   - Research project tracking
   - Publication support

#### Research Workflow:

**Untuk Data Analysis:**
1. **Access Historical Data**
   ```
   Research Dashboard → Historical Data → Select Time Period → Apply Filters
   ```

2. **Perform Analysis**
   ```
   Analysis Tools → Select Method → Configure Parameters → Run Analysis
   ```

3. **Export Results**
   ```
   Results → Export → Select Format (CSV/Excel) → Download
   ```

4. **Cite Data Properly**
   ```
   Citation → Generate Citation → Copy APA/IEEE Format
   ```

### 3.4 🟠 Public User Guide

#### Akses Publik
**URL**: `http://localhost:8000/ketersediaan/` (Tidak perlu registrasi)

**Fitur Tersedia:**
1. **Public Statistics**
   - Basic commodity statistics
   - Price trends
   - Production data
   - Consumption patterns

2. **Interactive Charts**
   - Regional maps
   - Time series charts
   - Commodity comparisons
   - Seasonal patterns

3. **Public Reports**
   - Monthly statistics reports
   - Annual summaries
   - Policy briefs
   - Methodology explanations

#### Public User Workflow:

**Untuk Mengakses Informasi:**
1. **Browse Statistics**
   ```
   Homepage → Statistik → Select Category → View Charts
   ```

2. **Download Reports**
   ```
   Laporan → Browse Available Reports → Download PDF
   ```

3. **Learn About Methodology**
   ```
   Metodologi → Read AI Explanation → View Technical Documentation
   ```

---

## 4. Fitur-Fitur Utama

### 4.1 🤖 AI Prediction System

#### Cara Menggunakan Prediksi AI:

**Step 1: Access Prediction Interface**
- Pengguna Pemerintah: `http://localhost:8000/ketersediaan/pemerintah/prediksi/`
- Pengguna Akademisi: `http://localhost:8000/ketersediaan/akademisi/prediksi/` (terbatas)

**Step 2: Input Parameters**
```
Select Commodity → Choose Time Period → Set Parameters → Run Prediction
```

**Step 3: Interpret Results**
- **Prediction Value**: Nilai prediksi NBM
- **Confidence Interval**: Range kepercayaan prediksi
- **SHAP Analysis**: Penjelasan faktor-faktor yang berpengaruh

#### Understanding SHAP Analysis:

**Positive Factors** (🔵 Blue bars):
- Faktor yang meningkatkan prediksi
- Semakin panjang bar = semakin besar pengaruh

**Negative Factors** (🔴 Red bars):
- Faktor yang menurunkan prediksi
- Berguna untuk identifikasi risiko

**Feature Importance**:
- Ranking faktor dari yang paling berpengaruh
- Insights untuk policy making

### 4.2 📊 Interactive Dashboard

#### Dashboard Components:

**1. KPI Cards**
- Metric utama dalam format card
- Real-time updates
- Color-coded alerts

**2. Time Series Charts**
- Interactive line charts
- Zoom dan pan capabilities
- Multiple commodity comparison

**3. Geographic Maps**
- Regional data visualization
- Choropleth maps
- Drill-down capabilities

**4. Statistical Tables**
- Sortable dan filterable
- Export functionality
- Pagination untuk dataset besar

### 4.3 📈 Data Export System

#### Export Options by User Level:

**Government Users:**
- **Full CSV Export**: Complete dataset
- **Excel Reports**: Formatted analysis reports
- **PDF Summaries**: Executive summaries
- **API Access**: Programmatic data access

**Academic Users:**
- **Research CSV**: Cleaned dataset untuk research
- **Statistical Summary**: Descriptive statistics
- **Citation Format**: Proper academic citation
- **Limited API**: Basic API access

**Public Users:**
- **Summary PDF**: Public summary reports
- **Basic CSV**: Aggregated public data
- **Infographic**: Visual summaries

#### Export Process:
```
Select Data → Choose Format → Configure Options → Generate Export → Download
```

---

## 5. Troubleshooting dan FAQ

### 5.1 🔧 Common Issues

#### **Issue: Login Problems**
**Symptoms**: Cannot login, "Invalid credentials" error

**Solutions:**
1. **Check email/password**: Ensure correct credentials
2. **Check account status**: Account might be pending approval
3. **Reset password**: Use forgot password feature
4. **Contact admin**: For account activation issues

**Prevention**: Keep login credentials secure, check email for approval notifications

#### **Issue: Slow Loading Pages**
**Symptoms**: Pages take long time to load

**Solutions:**
1. **Check internet connection**: Ensure stable connection
2. **Clear browser cache**: Refresh browser data
3. **Try different browser**: Switch to Chrome/Firefox
4. **Check system status**: Visit status page

#### **Issue: Export Fails**
**Symptoms**: Export process fails atau file corrupted

**Solutions:**
1. **Reduce data size**: Select smaller date range
2. **Try different format**: Switch from Excel to CSV
3. **Check file permissions**: Ensure download folder is writable
4. **Contact support**: For persistent issues

#### **Issue: Prediction Errors**
**Symptoms**: AI prediction fails atau unrealistic results

**Solutions:**
1. **Check input parameters**: Ensure valid inputs
2. **Try again later**: Model might be updating
3. **Contact admin**: Report persistent prediction errors
4. **Use historical data**: Reference past predictions

### 5.2 ❓ Frequently Asked Questions

#### **Q: Bagaimana cara menginterpretasi confidence interval?**
**A:** Confidence interval menunjukkan range dimana nilai sebenarnya kemungkinan besar berada. Interval yang sempit = prediksi lebih akurat, interval yang lebar = lebih banyak uncertainty.

#### **Q: Seberapa sering data NBM diupdate?**
**A:** 
- Data NBM: Bulanan (sesuai siklus NBM nasional)
- Data konsumsi kalori: Bulanan  
- Prediksi AI: Weekly retraining dengan data terbaru
- Statistik publik: Bulanan

#### **Q: Bisakah saya menggunakan data NBM untuk publikasi?**
**A:** 
- **Pengguna Pemerintah**: Ya, dengan proper citation
- **Pengguna Akademisi**: Ya, dengan approval untuk data sensitif NBM
- **Pengguna Publik**: Ya, untuk data NBM publik saja

#### **Q: Bagaimana cara request akses ke data yang lebih sensitif?**
**A:** Contact admin dengan justifikasi penggunaan, akan direview case-by-case.

#### **Q: Apakah ada API untuk akses otomatis?**
**A:** Ya, tersedia API dengan rate limiting sesuai level user. Lihat dokumentasi API untuk detail.

---

## 6. API Usage Guide

### 6.1 📡 API Authentication

#### Getting API Token:
```bash
# Login untuk mendapatkan token
curl -X POST http://localhost:8000/api/v1/auth/masuk \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.gov.id",
    "password": "password123"
  }'

# Response
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

#### Using Token:
```bash
curl -X GET https://sikolbia.pusdatin.kementan.go.id/api/v1/data/nbm \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 6.2 🔍 Common API Endpoints

#### **Public Endpoints NBM (No auth required):**
```bash
# Get public NBM statistics
GET http://localhost:8000/api/v1/publik/statistik/ringkasan

# Get commodity data
GET http://localhost:8000/api/v1/publik/komoditas/{komoditas}

# Get latest NBM reports
GET http://localhost:8000/api/v1/publik/laporan/terbaru
```

#### **Authenticated Endpoints NBM:**
```bash
# Get NBM predictions
GET http://localhost:8000/api/v1/prediksi?komoditas=beras&bulan=6

# Run custom NBM prediction
POST http://localhost:8000/api/v1/ml/prediksi
{
  "data_points": [{
    "tahun": 2024,
    "bulan": 12,
    "kelompok": 0,
    "komoditi": 1,
    "kalori_hari": 25.5
  }]
}

# Export NBM data
GET http://localhost:8000/api/v1/data/ekspor?format=csv&tanggal_mulai=2023-01-01
```

### 6.3 📊 Rate Limiting

| Level Pengguna | Requests/Minute | Requests/Hour | Burst Limit |
|------------|-----------------|---------------|-------------|
| Admin      | 500            | 25,000        | 1000        |
| Pemerintah | 250            | 10,000        | 500         |
| Akademisi  | 100            | 4,000         | 200         |
| Publik     | 30             | 1,000         | 60          |

**Catatan**: Rate limiting disesuaikan dengan kapasitas server lokal

---

## 7. Best Practices

### 7.1 💡 General Best Practices

#### **For All Users:**
1. **Keep credentials secure**: Don't share login information
2. **Regular backups**: Save important analysis results
3. **Cite properly**: Give credit when using data
4. **Report issues**: Help improve system by reporting bugs
5. **Follow usage guidelines**: Respect rate limits and data policies

#### **For Data Analysis:**
1. **Validate results**: Cross-check with multiple sources
2. **Understand limitations**: Know confidence intervals dan uncertainty
3. **Document methodology**: Keep track of analysis steps
4. **Version control**: Keep track of data versions used
5. **Peer review**: Have colleagues review important analysis

### 7.2 📋 Government User Best Practices

#### **Policy Analysis:**
1. **Use multiple scenarios**: Don't rely on single prediction
2. **Consider confidence intervals**: Factor uncertainty into decisions
3. **Monitor trends**: Look at historical patterns
4. **Regional analysis**: Consider geographic variations
5. **Stakeholder input**: Combine AI insights with expert knowledge

#### **Report Generation:**
1. **Clear methodology**: Explain data sources dan assumptions
2. **Visual presentation**: Use charts untuk better communication
3. **Executive summaries**: Provide clear conclusions
4. **Action items**: Include specific recommendations
5. **Regular updates**: Keep reports current

### 7.3 🎓 Academic Best Practices

#### **Research Methodology:**
1. **Literature review**: Compare with existing research
2. **Methodology documentation**: Document all steps clearly
3. **Statistical validation**: Use appropriate statistical methods
4. **Peer collaboration**: Work dengan other researchers
5. **Publication ethics**: Follow academic integrity guidelines

#### **Data Handling:**
1. **Data provenance**: Document data sources
2. **Quality assessment**: Evaluate data quality
3. **Preprocessing documentation**: Record all transformations
4. **Replication package**: Enable research replication
5. **Version control**: Track dataset versions

---

## 8. Support dan Kontak

### 8.1 📞 Technical Support

#### **Contact Information:**
- **Email Support**: jehianathayata@gmail.com
- **Phone**: +62-851-5543-3460 (Senin-Jumat, 08:00-17:00 WIB)
- **Emergency Hotline**: Tidak tersedia

#### **Support Levels:**
- **Level 1**: General usage questions, account issues
- **Level 2**: Technical problems, data quality issues  
- **Level 3**: System bugs, security concerns
- **Level 4**: Critical system failures

#### **Response Times:**
- **Critical Issues**: 2 hours
- **High Priority**: 4 hours
- **Medium Priority**: 1 business day
- **Low Priority**: 3 business days

### 8.2 📧 Contact by User Level

#### **Admin Support:**
- **Direct Line**: Internal Pusdatin extension
- **Escalation**: CTO/Technical Manager
- **24/7 Support**: Available for critical issues

#### **Government User Support:**
- **Dedicated Support**: Assigned support representative
- **Priority Queue**: Faster response times
- **Training Sessions**: Regular training updates

#### **Academic Support:**
- **Academic Liaison**: Specialized academic support
- **Research Collaboration**: Connect dengan other researchers
- **Methodology Support**: Help dengan research design

#### **Public Support:**
- **Self-Service Portal**: FAQ dan documentation
- **Community Forum**: User-to-user support
- **Email Support**: Basic support via email

### 8.3 📚 Additional Resources

#### **Documentation:**
- **Technical Documentation**: `/dokumentasi/technical`
- **API Documentation**: `/dokumentasi/api`
- **Video Tutorials**: `/tutorial`
- **Best Practices Guide**: `/dokumentasi/best-practices`

#### **Training Materials:**
- **User Onboarding**: New user orientation
- **Advanced Features**: Power user training
- **API Workshop**: Developer training
- **Policy Analysis**: Government user training

#### **Community:**
- **User Forum**: Community discussions
- **Newsletter**: Monthly updates
- **Webinars**: Regular training sessions
- **Conference Presentations**: Annual user conference

---

## 9. Changelog dan Updates

### 9.1 🔄 Version History

#### **Version 2.0 (Oktober 2025)**
- Fokus pada modul konsumsi pangan NBM
- Multi-level user access (4 level)
- AI predictions untuk konsumsi kalori dengan SHAP
- Local deployment dengan Docker
- Indonesian language interface

#### **Version 1.5 (Juni 2024)**
- Added basic NBM prediction capabilities
- Improved dashboard interface
- Enhanced data export features

#### **Version 1.0 (Januari 2024)**
- Initial release modul konsumsi pangan
- Basic NBM data management
- Simple reporting features

### 9.2 📅 Planned Updates

**Catatan**: Tidak ada rencana update mobile apps atau multi-language dalam roadmap saat ini. Fokus pada stabilitas dan optimasi modul konsumsi pangan.

---

## 10. Appendices

### 10.1 📊 Data Dictionary

#### **NBM Data Fields:**
- **tahun**: Year (1993-2024)
- **bulan**: Month (1-12)
- **kelompok**: Commodity group (11 kelompok)
- **komoditi**: Specific commodity code (120 komoditas)
- **kalori_hari**: Calories per day (fokus utama, decimal)

**Catatan**: Protein dan lemak tidak disertakan dalam modul ini, hanya fokus pada kalori

#### **User Fields:**
- **user_level**: admin, pemerintah, akademisi, publik
- **approval_status**: pending, approved, rejected
- **institution**: Government agency atau academic institution
- **access_module**: konsumsi_pangan (khusus modul ini)

### 10.2 🎯 Success Metrics

#### **System Performance (Nginx Localhost):**
- Page load time < 3 seconds (nginx localhost)
- 95% uptime (local deployment)
- API response time < 2 seconds
- Web browser access only (tidak ada mobile apps)

#### **User Adoption Target:**
- 20+ pengguna pemerintah
- 100+ pengguna akademisi  
- 1,000+ monthly public visitors (web browser)

#### **Data Quality NBM:**
- <1% NBM data validation errors
- 90%+ user satisfaction
- 80%+ prediction accuracy untuk konsumsi kalori

---

**Document Information:**
- **Last Updated**: Oktober 2025
- **Version**: 2.0
- **Review Cycle**: Quarterly
- **Feedback**: jehianathayata@gmail.com
- **Fokus**: Modul Konsumsi Pangan (NBM) SIKOLBIA
- **Deployment**: Nginx localhost production (localhost:8000)
- **Access Method**: Web browser only (TIDAK ADA MOBILE APPS)