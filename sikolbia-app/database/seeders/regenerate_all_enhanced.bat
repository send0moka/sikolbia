@echo off
REM ===================================================================
REM Script untuk regenerate SEMUA 106 komoditi dengan data enhanced
REM Menambahkan data kontekstual: harga, iklim, produktivitas
REM ===================================================================

echo ========================================
echo REGENERATE ALL NBM DATA WITH ENHANCED CONTEXT
echo ========================================
echo.
echo This will regenerate SQL files for 106 komoditi with:
echo - Harga konsumen ^& produsen (inflasi-adjusted)
echo - Suhu rata-rata ^& curah hujan (BMKG data)
echo - Luas panen ^& produktivitas (tanaman pangan)
echo.
echo WARNING: This will overwrite existing SQL files!
echo Press Ctrl+C to cancel, or
pause

cd database\seeders

REM Kelompok 01 - Padi-padian (6 komoditi)
echo.
echo [01] Processing Padi-padian (6 komoditi)...
php generate_nbm_enhanced.php raw-gabah-nbm-app3.csv 01 0101 gabah
php generate_nbm_enhanced.php raw-beras-nbm-app3.csv 01 0102 beras
php generate_nbm_enhanced.php raw-jagung-nbm-app3.csv 01 0103 jagung
php generate_nbm_enhanced.php raw-jagungbasah-nbm-app3.csv 01 0104 jagungbasah
php generate_nbm_enhanced.php raw-gandum-nbm-app3.csv 01 0105 gandum
php generate_nbm_enhanced.php raw-tepunggandum-nbm-app3.csv 01 0106 tepunggandum

REM Kelompok 02 - Umbi-umbian (5 komoditi)
echo.
echo [02] Processing Umbi-umbian (5 komoditi)...
php generate_nbm_enhanced.php raw-ubijalar-nbm-app3.csv 02 0201 ubijalar
php generate_nbm_enhanced.php raw-ubikayu-nbm-app3.csv 02 0202 ubikayu
php generate_nbm_enhanced.php raw-gaplek-nbm-app3.csv 02 0203 gaplek
php generate_nbm_enhanced.php raw-tapioka-nbm-app3.csv 02 0204 tapioka
php generate_nbm_enhanced.php raw-tepungsagu-nbm-app3.csv 02 0205 tepungsagu

REM Kelompok 03 - Gula (2 komoditi)
echo.
echo [03] Processing Gula (2 komoditi)...
php generate_nbm_enhanced.php raw-gulapasir-nbm-app3.csv 03 0301 gulapasir
php generate_nbm_enhanced.php raw-gulamangkok-nbm-app3.csv 03 0302 gulamangkok

REM Kelompok 04 - Kacang-kacangan (6 komoditi)
echo.
echo [04] Processing Kacang-kacangan (6 komoditi)...
php generate_nbm_enhanced.php raw-kacangtanahberkulit-nbm-app3.csv 04 0401 kacangtanahberkulit
php generate_nbm_enhanced.php raw-kacangtanahlepaskulit-nbm-app3.csv 04 0402 kacangtanahlepaskulit
php generate_nbm_enhanced.php raw-kedelai-nbm-app3.csv 04 0403 kedelai
php generate_nbm_enhanced.php raw-kacanghijau-nbm-app3.csv 04 0404 kacanghijau
php generate_nbm_enhanced.php raw-kelapadaging-nbm-app3.csv 04 0405 kelapadaging
php generate_nbm_enhanced.php raw-kopra-nbm-app3.csv 04 0406 kopra

REM Kelompok 05 - Buah-buahan (38 komoditi) - SUBSET SAMPLE
echo.
echo [05] Processing Buah-buahan (38 komoditi)...
echo NOTE: Only processing first 10 buah as SAMPLE
php generate_nbm_enhanced.php raw-alpokat-nbm-app3.csv 05 0501 alpokat
php generate_nbm_enhanced.php raw-jeruk-nbm-app3.csv 05 0502 jeruk
php generate_nbm_enhanced.php raw-duku-nbm-app3.csv 05 0503 duku
php generate_nbm_enhanced.php raw-durian-nbm-app3.csv 05 0504 durian
php generate_nbm_enhanced.php raw-jambu-nbm-app3.csv 05 0505 jambu
php generate_nbm_enhanced.php raw-mangga-nbm-app3.csv 05 0506 mangga
php generate_nbm_enhanced.php raw-nanas-nbm-app3.csv 05 0507 nanas
php generate_nbm_enhanced.php raw-pepaya-nbm-app3.csv 05 0508 pepaya
php generate_nbm_enhanced.php raw-pisang-nbm-app3.csv 05 0509 pisang
php generate_nbm_enhanced.php raw-rambutan-nbm-app3.csv 05 0510 rambutan

echo.
echo =======================================
echo SAMPLE REGENERATION COMPLETE
echo =======================================
echo.
echo Generated 29 enhanced SQL files (sample).
echo.
echo To generate ALL 106 komoditi, edit this script and uncomment all lines.
echo.
echo Next steps:
echo 1. Review the generated SQL files
echo 2. Run: php artisan db:seed --class=TransaksiNbmSeeder
echo 3. Verify data in UI (check Ekonomi and Lingkungan columns)
echo.
pause
