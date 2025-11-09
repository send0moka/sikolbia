#!/bin/bash

echo "🧪 Testing mysqldump with correct credentials from docker-compose.yml..."
echo ""

echo "Using credentials:"
echo "  Host: mysql"
echo "  Port: 3306"
echo "  Database: sikolbia_db"
echo "  User: root"
echo "  Password: rootsecret"
echo ""

echo "Running mysqldump..."
docker-compose exec app mysqldump \
  --user=root \
  --password=rootsecret \
  --host=mysql \
  --port=3306 \
  --single-transaction \
  --routines \
  --triggers \
  --add-drop-table \
  sikolbia_db \
  konsumsi komoditi transaksi_nbms registrasi_akses users tb_kelompokbps tb_komoditibps transaksi_susenas \
  > /tmp/test_backup_correct.sql 2>&1

EXIT_CODE=$?
echo "Exit code: $EXIT_CODE"
echo ""

if [ $EXIT_CODE -eq 0 ]; then
    echo "✅ SUCCESS! Checking file..."
    ls -lh /tmp/test_backup_correct.sql
    echo ""
    echo "First 20 lines:"
    head -n 20 /tmp/test_backup_correct.sql
else
    echo "❌ FAILED! Error output:"
    cat /tmp/test_backup_correct.sql
fi

echo ""
echo "✅ Done!"
