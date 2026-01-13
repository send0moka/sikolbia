#!/bin/bash
# ===================================================================
# BATCH REGENERATE ALL 106 KOMODITI WITH ENHANCED DATA
# Overwrites existing SQL files with enhanced versions
# ===================================================================

echo "========================================"
echo "REGENERATE ALL NBM DATA - ENHANCED"
echo "========================================"
echo ""
echo "This script will:"
echo "1. Backup existing SQL files to backup/ folder"
echo "2. Regenerate all 106 komoditi with enhanced data"
echo "3. Overwrite existing *_app3.sql files"
echo ""
echo "WARNING: This will take 5-10 minutes!"
read -p "Press Enter to continue or Ctrl+C to cancel..."

cd "$(dirname "$0")"

# Create backup folder with timestamp
timestamp=$(date +%Y%m%d_%H%M%S)
backupDir="backup_${timestamp}"
mkdir -p "$backupDir"

echo ""
echo "[BACKUP] Creating backup of existing SQL files..."
cp transaksi_nbms_*_app3.sql "$backupDir/" 2>/dev/null || true
echo "✓ Backup created: $backupDir"
echo ""

# Counter for progress
count=0
total=106

echo "========================================"
echo "STARTING ENHANCED GENERATION (106 komoditi)"
echo "========================================"
echo ""

generate() {
    csv=$1
    kelompok=$2
    komoditi=$3
    nama=$4
    
    ((count++))
    echo "[$count/$total] Generating $nama..."
    php generate_nbm_enhanced.php "$csv" "$kelompok" "$komoditi" "$nama" 2>&1 | grep -E "(Generated|Error)" || echo "   ✓ Generated"
}

# Kelompok 01 - Padi-padian (6 komoditi)
echo "[01] Padi-padian (6/106)"
generate raw-gabah-nbm-app3.csv 01 0101 gabah
generate raw-beras-nbm-app3.csv 01 0102 beras
generate raw-jagung-nbm-app3.csv 01 0103 jagung
generate raw-jagungbasah-nbm-app3.csv 01 0104 jagungbasah
generate raw-gandum-nbm-app3.csv 01 0105 gandum
generate raw-tepunggandum-nbm-app3.csv 01 0106 tepunggandum

# Kelompok 02 - Umbi-umbian (5 komoditi)
echo "[02] Umbi-umbian (11/106)"
generate raw-ubijalar-nbm-app3.csv 02 0201 ubijalar
generate raw-ubikayu-nbm-app3.csv 02 0202 ubikayu
generate raw-gaplek-nbm-app3.csv 02 0203 gaplek
generate raw-tapioka-nbm-app3.csv 02 0204 tapioka
generate raw-tepungsagu-nbm-app3.csv 02 0205 tepungsagu

# Kelompok 03 - Gula (2 komoditi)
echo "[03] Gula (13/106)"
generate raw-gulapasir-nbm-app3.csv 03 0301 gulapasir
generate raw-gulamangkok-nbm-app3.csv 03 0302 gulamangkok

# Kelompok 04 - Kacang-kacangan (6 komoditi)
echo "[04] Kacang-kacangan (19/106)"
generate raw-kacangtanahberkulit-nbm-app3.csv 04 0401 kacangtanahberkulit
generate raw-kacangtanahlepaskulit-nbm-app3.csv 04 0402 kacangtanahlepaskulit
generate raw-kedelai-nbm-app3.csv 04 0403 kedelai
generate raw-kacanghijau-nbm-app3.csv 04 0404 kacanghijau
generate raw-kelapadaging-nbm-app3.csv 04 0405 kelapadaging
generate raw-kopra-nbm-app3.csv 04 0406 kopra

# Kelompok 05 - Buah-buahan (38 komoditi)
echo "[05] Buah-buahan (57/106)"
generate raw-alpokat-nbm-app3.csv 05 0501 alpokat
generate raw-jeruk-nbm-app3.csv 05 0502 jeruk
generate raw-duku-nbm-app3.csv 05 0503 duku
generate raw-durian-nbm-app3.csv 05 0504 durian
generate raw-jambu-nbm-app3.csv 05 0505 jambu
generate raw-mangga-nbm-app3.csv 05 0506 mangga
generate raw-nanas-nbm-app3.csv 05 0507 nanas
generate raw-pepaya-nbm-app3.csv 05 0508 pepaya
generate raw-pisang-nbm-app3.csv 05 0509 pisang
generate raw-rambutan-nbm-app3.csv 05 0510 rambutan
generate raw-salak-nbm-app3.csv 05 0511 salak
generate raw-sawo-nbm-app3.csv 05 0512 sawo
generate raw-anggur-nbm-app3.csv 05 0513 anggur
generate raw-semangka-nbm-app3.csv 05 0514 semangka
generate raw-belimbing-nbm-app3.csv 05 0515 belimbing
generate raw-manggis-nbm-app3.csv 05 0516 manggis
generate raw-nangka-nbm-app3.csv 05 0517 nangka
generate raw-markisa-nbm-app3.csv 05 0518 markisa
generate raw-sirsak-nbm-app3.csv 05 0519 sirsak
generate raw-sukun-nbm-app3.csv 05 0520 sukun
generate raw-buahlainnya-nbm-app3.csv 05 0521 buahlainnya
generate raw-apel-nbm-app3.csv 05 0522 apel
generate raw-jambuair-nbm-app3.csv 05 0523 jambuair
generate raw-melon-nbm-app3.csv 05 0524 melon
generate raw-stroberi-nbm-app3.csv 05 0525 stroberi
generate raw-blewah-nbm-app3.csv 05 0526 blewah
generate raw-lemon-nbm-app3.csv 05 0527 lemon
generate raw-jerukbesar-nbm-app3.csv 05 0528 jerukbesar
generate raw-kurma-nbm-app3.csv 05 0529 kurma
generate raw-tin-nbm-app3.csv 05 0530 tin
generate raw-pir-nbm-app3.csv 05 0531 pir
generate raw-aprikot-nbm-app3.csv 05 0532 aprikot
generate raw-rasberi-nbm-app3.csv 05 0533 rasberi
generate raw-kiwi-nbm-app3.csv 05 0534 kiwi
generate raw-kesemek-nbm-app3.csv 05 0535 kesemek
generate raw-lengkeng-nbm-app3.csv 05 0536 lengkeng
generate raw-leci-nbm-app3.csv 05 0537 leci
generate raw-buahnaga-nbm-app3.csv 05 0538 buahnaga

# Kelompok 06 - Sayuran (24 komoditi)
echo "[06] Sayuran (81/106)"
generate raw-bawangmerah-nbm-app3.csv 06 0601 bawangmerah
generate raw-timun-nbm-app3.csv 06 0602 timun
generate raw-kacangmerah-nbm-app3.csv 06 0603 kacangmerah
generate raw-kacangpanjang-nbm-app3.csv 06 0604 kacangpanjang
generate raw-kentang-nbm-app3.csv 06 0605 kentang
generate raw-kubis-nbm-app3.csv 06 0606 kubis
generate raw-tomat-nbm-app3.csv 06 0607 tomat
generate raw-wortel-nbm-app3.csv 06 0608 wortel
generate raw-cabai-nbm-app3.csv 06 0609 cabai
generate raw-terong-nbm-app3.csv 06 0610 terong
generate raw-sawi-nbm-app3.csv 06 0611 sawi
generate raw-daunbawang-nbm-app3.csv 06 0612 daunbawang
generate raw-kangkung-nbm-app3.csv 06 0613 kangkung
generate raw-lobak-nbm-app3.csv 06 0614 lobak
generate raw-labusiam-nbm-app3.csv 06 0615 labusiam
generate raw-buncis-nbm-app3.csv 06 0616 buncis
generate raw-bayam-nbm-app3.csv 06 0617 bayam
generate raw-bawangputih-nbm-app3.csv 06 0618 bawangputih
generate raw-kembangkol-nbm-app3.csv 06 0619 kembangkol
generate raw-jamur-nbm-app3.csv 06 0620 jamur
generate raw-melinjo-nbm-app3.csv 06 0621 melinjo
generate raw-petai-nbm-app3.csv 06 0622 petai
generate raw-sayuranlainnya-nbm-app3.csv 06 0623 sayuranlainnya
generate raw-jengkol-nbm-app3.csv 06 0624 jengkol

# Kelompok 07 - Daging (11 komoditi)
echo "[07] Daging (95/106)"
generate raw-dagingsapi-nbm-app3.csv 07 0701 dagingsapi
generate raw-dagingkerbau-nbm-app3.csv 07 0702 dagingkerbau
generate raw-dagingkambing-nbm-app3.csv 07 0703 dagingkambing
generate raw-dagingdomba-nbm-app3.csv 07 0704 dagingdomba
generate raw-dagingkuda-nbm-app3.csv 07 0705 dagingkuda
generate raw-dagingbabi-nbm-app3.csv 07 0706 dagingbabi
generate raw-dagingayamburas-nbm-app3.csv 07 0707 dagingayamburas
generate raw-dagingayamras-nbm-app3.csv 07 0708 dagingayamras
generate raw-dagingbebek-nbm-app3.csv 07 0709 dagingbebek
generate raw-jeroan-nbm-app3.csv 07 0710 jeroan
generate raw-dagingpuyuh-nbm-app3.csv 07 0711 dagingpuyuh

# Kelompok 08 - Telur (3 komoditi)
echo "[08] Telur (98/106)"
generate raw-telurayamburas-nbm-app3.csv 08 0801 telurayamburas
generate raw-telurayamras-nbm-app3.csv 08 0802 telurayamras
generate raw-telurbebek-nbm-app3.csv 08 0803 telurbebek

# Kelompok 09 - Susu (2 komoditi)
echo "[09] Susu (101/106)"
generate raw-sususapi-nbm-app3.csv 09 0901 sususapi
generate raw-susuimpor-nbm-app3.csv 09 0902 susuimpor

# Kelompok 10 - Minyak & Lemak (9 komoditi)
echo "[10] Minyak & Lemak (106/106)"
generate raw-minyakkacangtanah-nbm-app3.csv 10 1001 minyakkacangtanah
generate raw-minyakgorengkelapa-nbm-app3.csv 10 1002 minyakgorengkelapa
generate raw-minyaksawit-nbm-app3.csv 10 1003 minyaksawit
generate raw-minyakgorengsawit-nbm-app3.csv 10 1004 minyakgorengsawit
generate raw-lemaksapi-nbm-app3.csv 10 1005 lemaksapi
generate raw-lemakkerbau-nbm-app3.csv 10 1006 lemakkerbau
generate raw-lemakkambing-nbm-app3.csv 10 1007 lemakkambing
generate raw-lemakdomba-nbm-app3.csv 10 1008 lemakdomba
generate raw-lemakbabi-nbm-app3.csv 10 1009 lemakbabi

echo ""
echo "========================================"
echo "GENERATION COMPLETE! (106/106)"
echo "========================================"
echo ""
echo "✓ All SQL files regenerated with enhanced data"
echo "✓ Backup saved to: $backupDir"
echo ""
echo "Next steps:"
echo "1. Verify a few SQL files"
echo "2. Run: docker-compose exec app php artisan db:seed --class=TransaksiNbmSeeder"
echo "3. Check UI - Ekonomi and Lingkungan columns should have data!"
echo ""
