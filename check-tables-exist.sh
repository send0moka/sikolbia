#!/bin/bash

echo "🔍 Checking if all tables exist in database..."
echo ""

TABLES=(
    "konsumsi"
    "komoditi"
    "transaksi_nbms"
    "registrasi_akses"
    "users"
    "tb_kelompokbps"
    "tb_komoditibps"
    "transaksi_susenas"
)

echo "Checking tables in sikolbia_db database:"
echo ""

for table in "${TABLES[@]}"; do
    EXISTS=$(docker-compose exec -T app mysql -h mysql -u root -prootsecret sikolbia_db -e "SHOW TABLES LIKE '$table';" 2>/dev/null | grep -c "$table")
    
    if [ $EXISTS -eq 1 ]; then
        echo "✅ $table - EXISTS"
    else
        echo "❌ $table - NOT FOUND"
    fi
done

echo ""
echo "=== All tables in database ==="
docker-compose exec -T app mysql -h mysql -u root -prootsecret sikolbia_db -e "SHOW TABLES;" 2>/dev/null

echo ""
echo "✅ Done!"
