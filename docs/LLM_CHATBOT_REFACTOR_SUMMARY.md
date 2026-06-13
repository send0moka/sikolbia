# Ringkasan Pekerjaan Refactor Chatbot & LLM

**Tanggal**: 13 Juni 2026  
**Status**: Sebagian besar implementasi inti selesai, tinggal validasi lanjutan dan perapihan sisa baseline test.

## Latar Belakang

Refactor ini fokus pada chatbot publik di SIKOLBIA agar alur natural language tidak lagi terasa terlalu hardcoded, sekaligus menambahkan lapisan LLM yang lebih terukur untuk token, biaya, dan observability. Setelah itu, kebutuhan berikutnya adalah kontrol pemakaian untuk pengguna anonim agar pemakaian API tetap adil dan tidak disalahgunakan.

## Apa yang Sudah Dilakukan

### 1. Menyusun rencana refactor yang executable
- Dibuat file rencana refactor lengkap sebagai panduan kerja bertahap.
- Rencana ini memecah perubahan menjadi beberapa fase supaya implementasi bisa dikerjakan dan divalidasi secara aman.

### 2. Membangun lapisan LLM terpusat
- Ditambahkan `LLMService` sebagai wrapper utama untuk pemanggilan model.
- Service ini menangani routing provider, logging, dan konteks pemakaian LLM.
- Dibuat abstraction provider untuk mendukung OpenAI dan Gemini melalui interface yang sama.

### 3. Menambahkan kontrol token, biaya, dan trial logging
- Dibuat controller/budget guard untuk memantau pemakaian harian.
- Ditambahkan estimasi biaya berbasis token.
- Dibuat tabel database untuk menyimpan trial LLM agar analisis pemakaian dan budgeting bisa dilacak.

### 4. Menyambungkan service chatbot ke layer LLM
- `ChatNormalizationService` diarahkan ke `LLMService` untuk normalisasi input.
- `RAGSummarizer` juga diarahkan ke `LLMService` untuk ringkasan, dengan fallback offline jika diperlukan.
- Tujuannya adalah menjaga chatbot tetap berfungsi walau LLM tidak tersedia penuh, tetapi tetap memakai jalur utama LLM saat aktif.

### 5. Menyesuaikan environment testing
- Ditambahkan `.env.testing` agar testing tidak bergantung pada ekstensi Redis yang tidak tersedia di environment ini.
- Ini membuat `composer run pest` bisa lanjut, walau masih ada failure baseline yang berasal dari data test existing, bukan dari perubahan LLM.

### 6. Menambahkan kontrol pemakaian untuk pengguna anonim
- Diimplementasikan rate limit `20 request/menit` untuk chatbot publik.
- Mekanismenya memakai cookie anonim `chat_anon_id` sebagai identitas utama.
- Jika cookie diblokir atau tidak valid, sistem fallback ke IP.
- Endpoint chatbot sekarang memakai middleware khusus dan limiter bernama `chatbot-user`.

## Dampak Perubahan

- Pemakaian LLM lebih terkendali karena ada logging, budgeting, dan trial persistence.
- Integrasi normalizer dan summarizer menjadi lebih konsisten karena semua lewat satu lapisan layanan.
- Chatbot publik lebih adil karena satu user anonim tidak bisa mengirim permintaan berlebihan dalam waktu singkat.
- Sistem lebih siap untuk observability dan evaluasi biaya di tahap berikutnya.

## Hasil Validasi Yang Sudah Dilakukan

- Syntax check PHP pada file yang diubah sudah lolos.
- Route chatbot tetap terdaftar dengan benar setelah penambahan middleware.
- Ada satu baseline issue di test data SQLite pada modul NBM yang bukan akibat perubahan LLM/rate limit ini.

## Hal Yang Perlu Dilakukan Selanjutnya

### Prioritas 1: Jalankan validasi fungsional yang lebih nyata
- Uji chatbot publik dengan lebih dari 20 request dalam 1 menit untuk memastikan limit benar-benar aktif.
- Cek apakah cookie `chat_anon_id` terbentuk dan dipakai konsisten antar request.
- Verifikasi fallback IP bekerja saat cookie tidak tersedia.

### Prioritas 2: Rapikan baseline test yang masih gagal
- Perbaiki fixture atau migration test yang masih menyentuh kolom `transaksi_nbms.status_angka`.
- Setelah baseline test bersih, jalankan seluruh test suite lagi untuk memastikan tidak ada regresi dari refactor LLM.

### Prioritas 3: Tambahkan observability tambahan bila diperlukan
- Tambahkan metrik atau log yang lebih mudah dibaca untuk melihat berapa banyak request yang kena limit.
- Jika perlu, pisahkan metrik per mode chatbot: natural, structured, guided.

### Prioritas 4: Perkuat dokumentasi
- Dokumentasikan arsitektur baru LLM layer dan rate limiting anon cookie.
- Tambahkan penjelasan singkat di README atau docs internal agar alur ini mudah dipahami developer berikutnya.

### Prioritas 5: Evaluasi lanjutan UX chatbot
- Tinjau apakah pesan rate limit sudah cukup jelas untuk user.
- Jika perlu, tampilkan retry hint yang lebih ramah di frontend.
- Pertimbangkan apakah reset/summary endpoint juga perlu dibatasi lebih ketat atau cukup mengikuti limiter yang sama.

## Kesimpulan Singkat

Refactor inti untuk chatbot dan LLM sudah masuk ke jalur yang lebih terstruktur: ada layer service tunggal, provider abstraction, budgeting, logging, dan penyimpanan trial. Untuk kebutuhan public access, chatbot sekarang punya rate limit per user anonim melalui cookie dengan fallback IP, jadi pemakaian lebih aman dan lebih adil. Langkah berikutnya adalah membersihkan baseline test yang tersisa dan melakukan validasi fungsional yang lebih ketat.
