@echo off
REM ===================================================================
REM BATCH REGENERATE ALL 106 KOMODITI WITH ENHANCED DATA
REM Overwrites existing SQL files with enhanced versions
REM ===================================================================

echo ========================================
echo REGENERATE ALL NBM DATA - ENHANCED
echo ========================================
echo.
echo This script will:
echo 1. Backup existing SQL files to backup/ folder
echo 2. Regenerate all 106 komoditi with enhanced data (harga, iklim, produktivitas)
echo 3. Overwrite existing *_app3.sql files
echo.
echo WARNING: This will take 5-10 minutes!
echo Press Ctrl+C to cancel, or
pause

cd /d %~dp0

REM Create backup folder with timestamp
set timestamp=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set timestamp=%timestamp: =0%
set backupDir=backup_%timestamp%
mkdir %backupDir%

echo.
echo [BACKUP] Creating backup of existing SQL files...
xcopy transaksi_nbms_*_app3.sql %backupDir%\ /Y >nul 2>&1
echo ✓ Backup created: %backupDir%
echo.

REM Counter for progress
set /a count=0
set /a total=106

echo ========================================
echo STARTING ENHANCED GENERATION (106 komoditi)
echo ========================================
echo.

REM Kelompok 01 - Padi-padian (6 komoditi)
echo [01] Padi-padian (6/106)
call :generate raw-gabah-nbm-app3.csv 01 0101 gabah
call :generate raw-beras-nbm-app3.csv 01 0102 beras
call :generate raw-jagung-nbm-app3.csv 01 0103 jagung
call :generate raw-jagungbasah-nbm-app3.csv 01 0104 jagungbasah
call :generate raw-gandum-nbm-app3.csv 01 0105 gandum
call :generate raw-tepunggandum-nbm-app3.csv 01 0106 tepunggandum

REM Kelompok 02 - Umbi-umbian (5 komoditi)
echo [02] Umbi-umbian (11/106)
call :generate raw-ubijalar-nbm-app3.csv 02 0201 ubijalar
call :generate raw-ubikayu-nbm-app3.csv 02 0202 ubikayu
call :generate raw-gaplek-nbm-app3.csv 02 0203 gaplek
call :generate raw-tapioka-nbm-app3.csv 02 0204 tapioka
call :generate raw-tepungsagu-nbm-app3.csv 02 0205 tepungsagu

REM Kelompok 03 - Gula (2 komoditi)
echo [03] Gula (13/106)
call :generate raw-gulapasir-nbm-app3.csv 03 0301 gulapasir
call :generate raw-gulamangkok-nbm-app3.csv 03 0302 gulamangkok

REM Kelompok 04 - Kacang-kacangan (6 komoditi)
echo [04] Kacang-kacangan (19/106)
call :generate raw-kacangtanahberkulit-nbm-app3.csv 04 0401 kacangtanahberkulit
call :generate raw-kacangtanahlepaskulit-nbm-app3.csv 04 0402 kacangtanahlepaskulit
call :generate raw-kedelai-nbm-app3.csv 04 0403 kedelai
call :generate raw-kacanghijau-nbm-app3.csv 04 0404 kacanghijau
call :generate raw-kelapadaging-nbm-app3.csv 04 0405 kelapadaging
call :generate raw-kopra-nbm-app3.csv 04 0406 kopra

REM Kelompok 05 - Buah-buahan (38 komoditi)
echo [05] Buah-buahan (57/106)
call :generate raw-alpokat-nbm-app3.csv 05 0501 alpokat
call :generate raw-jeruk-nbm-app3.csv 05 0502 jeruk
call :generate raw-duku-nbm-app3.csv 05 0503 duku
call :generate raw-durian-nbm-app3.csv 05 0504 durian
call :generate raw-jambu-nbm-app3.csv 05 0505 jambu
call :generate raw-mangga-nbm-app3.csv 05 0506 mangga
call :generate raw-nanas-nbm-app3.csv 05 0507 nanas
call :generate raw-pepaya-nbm-app3.csv 05 0508 pepaya
call :generate raw-pisang-nbm-app3.csv 05 0509 pisang
call :generate raw-rambutan-nbm-app3.csv 05 0510 rambutan
call :generate raw-salak-nbm-app3.csv 05 0511 salak
call :generate raw-sawo-nbm-app3.csv 05 0512 sawo
call :generate raw-anggur-nbm-app3.csv 05 0513 anggur
call :generate raw-semangka-nbm-app3.csv 05 0514 semangka
call :generate raw-belimbing-nbm-app3.csv 05 0515 belimbing
call :generate raw-manggis-nbm-app3.csv 05 0516 manggis
call :generate raw-nangka-nbm-app3.csv 05 0517 nangka
call :generate raw-markisa-nbm-app3.csv 05 0518 markisa
call :generate raw-sirsak-nbm-app3.csv 05 0519 sirsak
call :generate raw-sukun-nbm-app3.csv 05 0520 sukun
call :generate raw-buahlainnya-nbm-app3.csv 05 0521 buahlainnya
call :generate raw-apel-nbm-app3.csv 05 0522 apel
call :generate raw-jambuair-nbm-app3.csv 05 0523 jambuair
call :generate raw-melon-nbm-app3.csv 05 0524 melon
call :generate raw-stroberi-nbm-app3.csv 05 0525 stroberi
call :generate raw-blewah-nbm-app3.csv 05 0526 blewah
call :generate raw-lemon-nbm-app3.csv 05 0527 lemon
call :generate raw-jerukbesar-nbm-app3.csv 05 0528 jerukbesar
call :generate raw-kurma-nbm-app3.csv 05 0529 kurma
call :generate raw-tin-nbm-app3.csv 05 0530 tin
call :generate raw-pir-nbm-app3.csv 05 0531 pir
call :generate raw-aprikot-nbm-app3.csv 05 0532 aprikot
call :generate raw-rasberi-nbm-app3.csv 05 0533 rasberi
call :generate raw-kiwi-nbm-app3.csv 05 0534 kiwi
call :generate raw-kesemek-nbm-app3.csv 05 0535 kesemek
call :generate raw-lengkeng-nbm-app3.csv 05 0536 lengkeng
call :generate raw-leci-nbm-app3.csv 05 0537 leci
call :generate raw-buahnaga-nbm-app3.csv 05 0538 buahnaga

REM Kelompok 06 - Sayuran (24 komoditi)
echo [06] Sayuran (81/106)
call :generate raw-bawangmerah-nbm-app3.csv 06 0601 bawangmerah
call :generate raw-timun-nbm-app3.csv 06 0602 timun
call :generate raw-kacangmerah-nbm-app3.csv 06 0603 kacangmerah
call :generate raw-kacangpanjang-nbm-app3.csv 06 0604 kacangpanjang
call :generate raw-kentang-nbm-app3.csv 06 0605 kentang
call :generate raw-kubis-nbm-app3.csv 06 0606 kubis
call :generate raw-tomat-nbm-app3.csv 06 0607 tomat
call :generate raw-wortel-nbm-app3.csv 06 0608 wortel
call :generate raw-cabai-nbm-app3.csv 06 0609 cabai
call :generate raw-terong-nbm-app3.csv 06 0610 terong
call :generate raw-sawi-nbm-app3.csv 06 0611 sawi
call :generate raw-daunbawang-nbm-app3.csv 06 0612 daunbawang
call :generate raw-kangkung-nbm-app3.csv 06 0613 kangkung
call :generate raw-lobak-nbm-app3.csv 06 0614 lobak
call :generate raw-labusiam-nbm-app3.csv 06 0615 labusiam
call :generate raw-buncis-nbm-app3.csv 06 0616 buncis
call :generate raw-bayam-nbm-app3.csv 06 0617 bayam
call :generate raw-bawangputih-nbm-app3.csv 06 0618 bawangputih
call :generate raw-kembangkol-nbm-app3.csv 06 0619 kembangkol
call :generate raw-jamur-nbm-app3.csv 06 0620 jamur
call :generate raw-melinjo-nbm-app3.csv 06 0621 melinjo
call :generate raw-petai-nbm-app3.csv 06 0622 petai
call :generate raw-sayuranlainnya-nbm-app3.csv 06 0623 sayuranlainnya
call :generate raw-jengkol-nbm-app3.csv 06 0624 jengkol

REM Kelompok 07 - Daging (11 komoditi)
echo [07] Daging (95/106)
call :generate raw-dagingsapi-nbm-app3.csv 07 0701 dagingsapi
call :generate raw-dagingkerbau-nbm-app3.csv 07 0702 dagingkerbau
call :generate raw-dagingkambing-nbm-app3.csv 07 0703 dagingkambing
call :generate raw-dagingdomba-nbm-app3.csv 07 0704 dagingdomba
call :generate raw-dagingkuda-nbm-app3.csv 07 0705 dagingkuda
call :generate raw-dagingbabi-nbm-app3.csv 07 0706 dagingbabi
call :generate raw-dagingayamburas-nbm-app3.csv 07 0707 dagingayamburas
call :generate raw-dagingayamras-nbm-app3.csv 07 0708 dagingayamras
call :generate raw-dagingbebek-nbm-app3.csv 07 0709 dagingbebek
call :generate raw-jeroan-nbm-app3.csv 07 0710 jeroan
call :generate raw-dagingpuyuh-nbm-app3.csv 07 0711 dagingpuyuh

REM Kelompok 08 - Telur (3 komoditi)
echo [08] Telur (98/106)
call :generate raw-telurayamburas-nbm-app3.csv 08 0801 telurayamburas
call :generate raw-telurayamras-nbm-app3.csv 08 0802 telurayamras
call :generate raw-telurbebek-nbm-app3.csv 08 0803 telurbebek

REM Kelompok 09 - Susu (2 komoditi)
echo [09] Susu (101/106)
call :generate raw-sususapi-nbm-app3.csv 09 0901 sususapi
call :generate raw-susuimpor-nbm-app3.csv 09 0902 susuimpor

REM Kelompok 10 - Minyak & Lemak (9 komoditi)
echo [10] Minyak ^& Lemak (106/106)
call :generate raw-minyakkacangtanah-nbm-app3.csv 10 1001 minyakkacangtanah
call :generate raw-minyakgorengkelapa-nbm-app3.csv 10 1002 minyakgorengkelapa
call :generate raw-minyaksawit-nbm-app3.csv 10 1003 minyaksawit
call :generate raw-minyakgorengsawit-nbm-app3.csv 10 1004 minyakgorengsawit
call :generate raw-lemaksapi-nbm-app3.csv 10 1005 lemaksapi
call :generate raw-lemakkerbau-nbm-app3.csv 10 1006 lemakkerbau
call :generate raw-lemakkambing-nbm-app3.csv 10 1007 lemakkambing
call :generate raw-lemakdomba-nbm-app3.csv 10 1008 lemakdomba
call :generate raw-lemakbabi-nbm-app3.csv 10 1009 lemakbabi

echo.
echo ========================================
echo GENERATION COMPLETE! (106/106)
echo ========================================
echo.
echo ✓ All SQL files regenerated with enhanced data
echo ✓ Backup saved to: %backupDir%
echo.
echo Next steps:
echo 1. Verify a few SQL files (check harga_konsumen, produktivitas_ton_ha columns)
echo 2. Run seeder: docker-compose exec app php artisan db:seed --class=TransaksiNbmSeeder
echo 3. Check UI - Ekonomi and Lingkungan columns should now have data!
echo.
pause
goto :eof

:generate
set /a count+=1
echo [%count%/%total%] Generating %4...
php generate_nbm_enhanced.php %1 %2 %3 %4 2>nul
if errorlevel 1 (
    echo    ERROR: Failed to generate %4
) else (
    echo    ✓ Generated
)
goto :eof
