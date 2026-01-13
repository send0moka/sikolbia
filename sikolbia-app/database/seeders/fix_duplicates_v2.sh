#!/bin/bash

# Process each SQL file with awk untuk deduplikasi lebih akurat
count=0
total_removed=0

for file in transaksi_nbms_*_app3.sql; do
    if [ -f "$file" ]; then
        count=$((count + 1))
        
        # Get original count
        original=$(wc -l < "$file")
        
        # Deduplicate using awk - hanya keep first occurrence
        awk '!seen[$0]++' "$file" > "${file}.dedup"
        
        # Get deduplicated count
        deduplicated=$(wc -l < "${file}.dedup")
        removed=$((original - deduplicated))
        total_removed=$((total_removed + removed))
        
        if [ $removed -gt 0 ]; then
            echo "[$count] $file: Removed $removed duplicates ($original → $deduplicated)"
            mv "${file}.dedup" "$file"
        else
            rm "${file}.dedup"
        fi
    fi
done

echo ""
echo "=== SUMMARY ==="
echo "Total files processed: $count"
echo "Total duplicates removed: $total_removed"
