#!/bin/bash
# Backup script untuk data Beras sebelum update dengan APP3

echo "=== BACKUP DATA BERAS SEBELUM UPDATE APP3 ==="
echo ""

BACKUP_DIR="storage/backups/nbm"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="${BACKUP_DIR}/beras_backup_${TIMESTAMP}.json"

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

# Backup menggunakan artisan tinker
php artisan tinker <<EOF
\$data = DB::table('transaksi_nbms')
    ->where('kode_kelompok', '01')
    ->where('kode_komoditi', '0101')
    ->get();

\$count = \$data->count();
echo "Found \$count Beras records\n";

file_put_contents('${BACKUP_FILE}', \$data->toJson(JSON_PRETTY_PRINT));
echo "Backup saved to: ${BACKUP_FILE}\n";
EOF

# Also create SQL dump if mysqldump available
if command -v mysqldump &> /dev/null; then
    echo ""
    echo "Creating SQL dump..."
    mysqldump -u sikolbia_user -p sikolbia_db transaksi_nbms \
        --where="kode_kelompok='01' AND kode_komoditi='0101'" \
        > "${BACKUP_DIR}/beras_backup_${TIMESTAMP}.sql"
    echo "SQL dump saved to: ${BACKUP_DIR}/beras_backup_${TIMESTAMP}.sql"
fi

echo ""
echo "=== BACKUP COMPLETED ==="
echo "Files created:"
ls -lh "${BACKUP_DIR}/beras_backup_${TIMESTAMP}"*
