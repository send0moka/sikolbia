#!/bin/bash

# Backup dulu
BACKUP_DIR="backup_sql_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"
cp transaksi_nbms_*_app3.sql "$BACKUP_DIR/" 2>/dev/null || true
echo "Backup created in: $BACKUP_DIR"

# Process each SQL file
count=0
for file in transaksi_nbms_*_app3.sql; do
    if [ -f "$file" ]; then
        count=$((count + 1))
        # Deduplicate: sort | uniq
        sort "$file" | uniq > "${file}.tmp"
        
        # Count before/after
        original=$(wc -l < "$file")
        deduplicated=$(wc -l < "${file}.tmp")
        removed=$((original - deduplicated))
        
        if [ $removed -gt 0 ]; then
            echo "[$count] $file: Removed $removed duplicates ($original → $deduplicated)"
            mv "${file}.tmp" "$file"
        else
            echo "[$count] $file: No duplicates"
            rm "${file}.tmp"
        fi
    fi
done

echo "Deduplicate complete! Processed $count files"
