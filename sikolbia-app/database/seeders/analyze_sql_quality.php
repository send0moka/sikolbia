<?php
/**
 * ANALYZE SQL DATA QUALITY
 * =========================
 * Scan all transaksi_nbms_*_app3.sql files to check:
 * 1. How many records per commodity
 * 2. Year coverage (1994-2024)
 * 3. Missing data patterns
 * 4. Which commodities have good quality data
 * 
 * Usage: php database/seeders/analyze_sql_quality.php
 */

$seedersPath = __DIR__;
$sqlFiles = glob($seedersPath . '/transaksi_nbms_*_app3.sql');

if (empty($sqlFiles)) {
    die("ERROR: No SQL files found in $seedersPath\n");
}

echo "================================================================================\n";
echo "SQL DATA QUALITY ANALYSIS\n";
echo "================================================================================\n";
echo "Found " . count($sqlFiles) . " SQL files to analyze\n\n";

$results = [];

foreach ($sqlFiles as $file) {
    $filename = basename($file);
    
    // Extract commodity name from filename
    // Pattern: transaksi_nbms_NAMAKOMODITI_app3.sql
    preg_match('/transaksi_nbms_(.+)_app3\.sql/', $filename, $matches);
    $commodityName = $matches[1] ?? 'unknown';
    
    echo "Analyzing: $commodityName...\n";
    
    // Read file and count lines
    $content = file_get_contents($file);
    if ($content === false) {
        echo "  ERROR: Cannot read file\n";
        continue;
    }
    
    // Split by lines
    $lines = explode("\n", $content);
    $dataLines = array_filter($lines, function($line) {
        $line = trim($line);
        return !empty($line) && $line !== '(' && $line !== ');';
    });
    
    $totalRecords = count($dataLines);
    
    // Parse first and last line to get year range
    $years = [];
    $hasNullValues = 0;
    $hasZeroConsumption = 0;
    $kelompokCode = null;
    $komoditiCode = null;
    
    foreach ($dataLines as $idx => $line) {
        $line = trim($line, "(),\n");
        $values = array_map('trim', explode(',', $line));
        
        // Get kode_kelompok and kode_komoditi (columns 0 and 1)
        if ($idx === 0) {
            $kelompokCode = trim($values[0] ?? '', "'");
            $komoditiCode = trim($values[1] ?? '', "'");
        }
        
        // Get year (column 2)
        if (isset($values[2]) && is_numeric($values[2])) {
            $years[] = (int)$values[2];
        }
        
        // Check for NULL values (column 23 = bahan_makanan index)
        $bahanMakanan = $values[23] ?? 'NULL';
        if ($bahanMakanan === 'NULL' || $bahanMakanan === '0' || $bahanMakanan === '0.0000') {
            $hasZeroConsumption++;
        }
        
        // Count NULL values across all columns
        $nullCount = count(array_filter($values, function($v) {
            return $v === 'NULL' || $v === '';
        }));
        if ($nullCount > 5) { // More than 5 NULLs = bad quality
            $hasNullValues++;
        }
    }
    
    $yearMin = !empty($years) ? min($years) : 0;
    $yearMax = !empty($years) ? max($years) : 0;
    $yearSpan = $yearMax - $yearMin + 1;
    
    // Calculate data quality score (adjusted for ANNUAL data that will be converted to monthly)
    // For annual data: 1 year record = 12 monthly records after conversion
    $estimatedMonthlyRecords = $totalRecords * 12; // After convertAnnualToMonthly()
    
    $completeness = $totalRecords > 0 ? (($totalRecords - $hasNullValues) / $totalRecords) * 100 : 0;
    
    // For annual data, zero consumption is EXPECTED (will be filled during monthly conversion)
    // So we focus on year coverage instead
    $yearCoverageScore = min(100, ($yearSpan / 30) * 100); // 30 years = 100%
    
    // Overall quality score: prioritize year coverage and record count
    $qualityScore = ($completeness * 0.3) + ($yearCoverageScore * 0.7);
    
    // Categorize quality based on year span and estimated monthly records
    if ($yearSpan >= 25 && $estimatedMonthlyRecords >= 300) {
        $category = 'EXCELLENT';
    } elseif ($yearSpan >= 15 && $estimatedMonthlyRecords >= 180) {
        $category = 'GOOD';
    } elseif ($yearSpan >= 8 && $estimatedMonthlyRecords >= 96) {
        $category = 'FAIR';
    } else {
        $category = 'POOR';
    }
    
    $results[] = [
        'file' => $filename,
        'commodity' => $commodityName,
        'kelompok' => $kelompokCode,
        'komoditi' => $komoditiCode,
        'records' => $totalRecords,
        'monthly_records' => $estimatedMonthlyRecords,
        'year_min' => $yearMin,
        'year_max' => $yearMax,
        'year_span' => $yearSpan,
        'null_rows' => $hasNullValues,
        'zero_consumption' => $hasZeroConsumption,
        'completeness' => $completeness,
        'year_coverage_score' => $yearCoverageScore,
        'quality_score' => $qualityScore,
        'category' => $category
    ];
    
    echo sprintf("  Annual: %d | Monthly (after convert): %d | Years: %d-%d (%d years) | Quality: %s (%.1f%%)\n",
        $totalRecords, $estimatedMonthlyRecords, $yearMin, $yearMax, $yearSpan, $category, $qualityScore);
}

// Sort by quality score descending
usort($results, function($a, $b) {
    if ($b['quality_score'] == $a['quality_score']) return 0;
    return ($b['quality_score'] > $a['quality_score']) ? 1 : -1;
});

// Summary report
echo "\n" . str_repeat("=", 80) . "\n";
echo "SUMMARY REPORT\n";
echo str_repeat("=", 80) . "\n";

// Count by category
$categoryCount = array_count_values(array_column($results, 'category'));
echo "\nData Quality Distribution:\n";
echo "  EXCELLENT: " . ($categoryCount['EXCELLENT'] ?? 0) . " commodities\n";
echo "  GOOD:      " . ($categoryCount['GOOD'] ?? 0) . " commodities\n";
echo "  FAIR:      " . ($categoryCount['FAIR'] ?? 0) . " commodities\n";
echo "  POOR:      " . ($categoryCount['POOR'] ?? 0) . " commodities\n";

// Total records (both annual and estimated monthly after conversion)
$totalAnnualRecords = array_sum(array_column($results, 'records'));
$totalMonthlyRecords = array_sum(array_column($results, 'monthly_records'));
echo "\nTotal Annual Records: " . number_format($totalAnnualRecords) . "\n";
echo "Estimated Monthly Records (after conversion): " . number_format($totalMonthlyRecords) . "\n";

// Recommended commodities for LSTM training
$recommended = array_filter($results, function($r) {
    return $r['category'] === 'EXCELLENT' || $r['category'] === 'GOOD';
});

echo "\n" . str_repeat("=", 80) . "\n";
echo "RECOMMENDED FOR LSTM TRAINING (EXCELLENT + GOOD)\n";
echo str_repeat("=", 80) . "\n";
echo count($recommended) . " commodities recommended\n\n";

echo sprintf("%-4s %-30s %-10s %-12s %-12s %-15s %-10s\n", 
    "#", "Commodity", "Code", "Annual", "Monthly", "Years", "Quality");
echo str_repeat("-", 90) . "\n";

foreach (array_slice($recommended, 0, 50) as $idx => $item) {
    $code = $item['kelompok'] . $item['komoditi'];
    $yearRange = "{$item['year_min']}-{$item['year_max']}";
    echo sprintf("%-4d %-30s %-10s %-12d %-12d %-15s %6.1f%%\n",
        $idx + 1,
        substr($item['commodity'], 0, 28),
        $code,
        $item['records'],
        $item['monthly_records'],
        $yearRange,
        $item['quality_score']
    );
}

// Estimate sequences with top 30 commodities (AFTER monthly conversion!)
$top30 = array_slice($recommended, 0, 30);
$estimatedMonthlyRecords = array_sum(array_column($top30, 'monthly_records'));
// Subtract 6 for sequence window requirement (need 6 consecutive months)
$estimatedSequences = max(0, $estimatedMonthlyRecords - (count($top30) * 6));

echo "\n" . str_repeat("=", 80) . "\n";
echo "ESTIMATED DATASET SIZE WITH TOP 30 COMMODITIES\n";
echo str_repeat("=", 80) . "\n";
echo "Annual records: " . array_sum(array_column($top30, 'records')) . "\n";
echo "Monthly records (after conversion): " . number_format($estimatedMonthlyRecords) . "\n";
echo "Estimated sequences: " . number_format($estimatedSequences) . " (after 6-month window)\n";
echo "Current sequences (8 commodities): 2,034\n";
if ($estimatedSequences > 0) {
    echo "Increase: " . number_format($estimatedSequences / 2034, 1) . "x more data!\n";
}

if ($estimatedSequences > 10000) {
    echo "\n✅✅ EXCELLENT for LSTM training (>10K sequences)\n";
    echo "Expected LSTM MAPE: 12-16%\n";
} elseif ($estimatedSequences > 8000) {
    echo "\n✅ GOOD for LSTM training (8-10K sequences)\n";
    echo "Expected LSTM MAPE: 14-18%\n";
} elseif ($estimatedSequences > 5000) {
    echo "\n⚠️  ACCEPTABLE for LSTM training (5-8K sequences)\n";
    echo "Expected LSTM MAPE: 18-22%\n";
} else {
    echo "\n❌ INSUFFICIENT for LSTM training (<5K sequences)\n";
    echo "Recommendation: Use HYBRID approach\n";
}

// Export recommended commodity list
$outputFile = $seedersPath . '/recommended_commodities.json';
$exportData = [
    'analysis_date' => date('Y-m-d H:i:s'),
    'total_commodities' => count($results),
    'recommended_count' => count($recommended),
    'estimated_sequences' => $estimatedSequences,
    'top_30' => array_map(function($item) {
        return [
            'commodity' => $item['commodity'],
            'code' => $item['kelompok'] . $item['komoditi'],
            'kelompok' => $item['kelompok'],
            'komoditi' => $item['komoditi'],
            'records' => $item['records'],
            'year_span' => $item['year_span'],
            'quality_score' => round($item['quality_score'], 2)
        ];
    }, $top30)
];

file_put_contents($outputFile, json_encode($exportData, JSON_PRETTY_PRINT));
echo "\n✅ Exported recommended commodities to: $outputFile\n";

// Generate PHP array for seeder
$phpArrayFile = $seedersPath . '/recommended_commodities.php';
$phpCode = "<?php\n";
$phpCode .= "/**\n";
$phpCode .= " * TOP 30 RECOMMENDED COMMODITIES FOR LSTM TRAINING\n";
$phpCode .= " * Generated by analyze_sql_quality.php on " . date('Y-m-d H:i:s') . "\n";
$phpCode .= " * \n";
$phpCode .= " * Estimated sequences: " . number_format($estimatedSequences) . "\n";
$phpCode .= " * Quality threshold: EXCELLENT or GOOD\n";
$phpCode .= " */\n\n";
$phpCode .= "return [\n";
foreach ($top30 as $item) {
    $phpCode .= "    '{$item['commodity']}' => ['{$item['kelompok']}', '{$item['komoditi']}', '{$item['file']}'],\n";
}
$phpCode .= "];\n";

file_put_contents($phpArrayFile, $phpCode);
echo "✅ Generated PHP array for seeder: $phpArrayFile\n";

echo "\n" . str_repeat("=", 80) . "\n";
echo "NEXT STEPS\n";
echo str_repeat("=", 80) . "\n";
echo "1. Review recommended_commodities.json\n";
echo "2. Update your data loading script to use top 30 commodities\n";
echo "3. Run fix_sql_data_quality.php to clean data issues\n";
echo "4. Retrain LSTM with expanded dataset\n";
echo str_repeat("=", 80) . "\n";
