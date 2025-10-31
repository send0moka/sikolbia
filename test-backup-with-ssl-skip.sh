#!/bin/bash

echo "🧪 Testing mysqldump with --skip-ssl flag..."
echo ""

echo "Running mysqldump with --skip-ssl..."
docker-compose exec app mysqldump \
  --user=root \
  --password=rootsecret \
  --host=mysql \
  --port=3306 \
  --skip-ssl \
  --single-transaction \
  --routines \
  --triggers \
  --add-drop-table \
  sikolbia_db \
  konsumsi komoditi transaksi_nbms registrasi_akses users tb_kelompokbps tb_komoditibps transaksi_susenas \
  > /tmp/test_backup_ssl_skip.sql 2>&1

EXIT_CODE=$?
echo "Exit code: $EXIT_CODE"
echo ""

if [ $EXIT_CODE -eq 0 ]; then
    echo "✅ SUCCESS! Backup created successfully!"
    ls -lh /tmp/test_backup_ssl_skip.sql
    echo ""
    echo "First 20 lines of backup:"
    head -n 20 /tmp/test_backup_ssl_skip.sql
    echo ""
    echo "🎉 Backup is working! Now try from the web interface."
else
    echo "❌ FAILED! Error output:"
    cat /tmp/test_backup_ssl_skip.sql
fi

echo ""
echo "✅ Done!"
