<?php

namespace Database\Seeders;

use App\Models\TransaksiNbm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TransaksiNbmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Importing TransaksiNbm data from SQL dump...\n";
        
        // Update nama file sesuai dengan file SQL yang ada
        $sqlFile = base_path('database/seeders/transaksi_nbms.sql');
        
        if (!file_exists($sqlFile)) {
            echo "Error: SQL dump file not found at: $sqlFile\n";
            echo "Please place your SQL dump file at: database/seeders/transaksi_nbms.sql\n";
            return;
        }
        
        try {
            // Read the SQL file
            $sql = file_get_contents($sqlFile);
            
            if ($sql === false) {
                echo "Error: Could not read SQL file\n";
                return;
            }
            
            echo "SQL file loaded successfully\n";
            echo "File size: " . number_format(strlen($sql)) . " bytes\n";
            
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Truncate the table first
            echo "Truncating transaksi_nbms table...\n";
            DB::table('transaksi_nbms')->truncate();
            
            // Split SQL into statements
            // Remove comments and split by semicolon
            $sql = preg_replace('/--.*$/m', '', $sql); // Remove single-line comments
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments
            
            // Extract only INSERT statements
            preg_match_all('/INSERT INTO `transaksi_nbms`.*?;/s', $sql, $matches);
            
            if (empty($matches[0])) {
                echo "Warning: No INSERT statements found in SQL file\n";
                return;
            }
            
            echo "Found " . count($matches[0]) . " INSERT statement(s)\n";
            
            $totalInserted = 0;
            
            foreach ($matches[0] as $index => $insertStatement) {
                echo "Executing INSERT statement " . ($index + 1) . "/" . count($matches[0]) . "...\n";
                
                try {
                    // Execute the INSERT statement
                    DB::unprepared($insertStatement);
                    
                    // Count records in this statement
                    preg_match_all('/\([^)]+\)(?=,|\s*;)/s', $insertStatement, $valueMatches);
                    $recordCount = count($valueMatches[0]);
                    $totalInserted += $recordCount;
                    
                    echo "  ✓ Inserted $recordCount records (Total: $totalInserted)\n";
                    
                } catch (QueryException $e) {
                    echo "  ✗ Error executing statement: " . $e->getMessage() . "\n";
                    
                    // Try to insert records one by one if batch insert fails
                    echo "  Attempting individual record insertion...\n";
                    $individualCount = $this->insertIndividually($insertStatement);
                    $totalInserted += $individualCount;
                }
            }
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            echo "\n=== POST-IMPORT DATA CLEANING ===\n";
            $this->cleanInvalidData();
            
            // Verify the import
            $finalCount = DB::table('transaksi_nbms')->count();
            
            echo "\n=== IMPORT SUMMARY ===\n";
            echo "Total records in database: $finalCount\n";
            echo "Expected records: $totalInserted\n";
            
            if ($finalCount > 0) {
                // Show sample data
                $sample = DB::table('transaksi_nbms')
                    ->where('tahun', '>', 1990)
                    ->where('periode_data', 'bulanan')
                    ->where('bulan', '>', 0)
                    ->orderBy('tahun')
                    ->orderBy('bulan')
                    ->first();
                
                if ($sample) {
                    echo "\nSample valid record:\n";
                    echo "  ID: {$sample->id}\n";
                    echo "  Komoditi: {$sample->kode_kelompok}-{$sample->kode_komoditi}\n";
                    echo "  Periode: {$sample->tahun}-" . str_pad($sample->bulan, 2, '0', STR_PAD_LEFT) . " ({$sample->periode_data})\n";
                    echo "  Bahan Makanan: {$sample->bahan_makanan}\n";
                }
                
                // Show date range
                $minYear = DB::table('transaksi_nbms')->min('tahun');
                $maxYear = DB::table('transaksi_nbms')->max('tahun');
                echo "\nData range: {$minYear} - {$maxYear}\n";
                
                // Show komoditi count
                $komoditiCount = DB::table('transaksi_nbms')
                    ->select('kode_kelompok', 'kode_komoditi')
                    ->distinct()
                    ->count();
                echo "Unique komoditi: {$komoditiCount}\n";
                
                // Show records by periode
                echo "\nRecords by periode:\n";
                $byPeriode = DB::table('transaksi_nbms')
                    ->select('periode_data', DB::raw('COUNT(*) as total'))
                    ->groupBy('periode_data')
                    ->orderBy('periode_data')
                    ->get();
                
                foreach ($byPeriode as $stat) {
                    echo "  {$stat->periode_data}: {$stat->total} records\n";
                }
                
                // Show records per year (top 10 years)
                echo "\nRecords per year (sample):\n";
                $yearStats = DB::table('transaksi_nbms')
                    ->select('tahun', DB::raw('COUNT(*) as total'))
                    ->groupBy('tahun')
                    ->orderBy('tahun', 'desc')
                    ->limit(10)
                    ->get();
                
                foreach ($yearStats as $stat) {
                    echo "  {$stat->tahun}: {$stat->total} records\n";
                }
            }
            
            echo "\n=== IMPORT COMPLETED ===\n";
            
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            echo "Fatal error: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString() . "\n";
        }
    }
    
    /**
     * Clean invalid data after import
     */
    private function cleanInvalidData()
    {
        echo "\n--- Cleaning invalid data ---\n";
        
        // 1. Delete records with invalid year (0 or < 1990)
        echo "1. Checking invalid years...\n";
        $invalidYearCount = DB::table('transaksi_nbms')
            ->where(function($query) {
                $query->where('tahun', 0)
                      ->orWhere('tahun', '<', 1990);
            })
            ->count();
        
        if ($invalidYearCount > 0) {
            echo "   Found $invalidYearCount records with invalid year\n";
            $deleted = DB::table('transaksi_nbms')
                ->where(function($query) {
                    $query->where('tahun', 0)
                          ->orWhere('tahun', '<', 1990);
                })
                ->delete();
            echo "   ✓ Deleted $deleted invalid year records\n";
        } else {
            echo "   ✓ No invalid year records found\n";
        }
        
        // 2. Fix monthly records with bulan = 0
        echo "\n2. Checking monthly records with bulan = 0...\n";
        $invalidMonthCount = DB::table('transaksi_nbms')
            ->where('bulan', 0)
            ->where('periode_data', 'bulanan')
            ->count();
        
        if ($invalidMonthCount > 0) {
            echo "   Found $invalidMonthCount monthly records with bulan = 0\n";
            $updated = DB::table('transaksi_nbms')
                ->where('bulan', 0)
                ->where('periode_data', 'bulanan')
                ->update([
                    'periode_data' => 'tahunan',
                    'kuartal' => null
                ]);
            echo "   ✓ Converted $updated records to tahunan\n";
        } else {
            echo "   ✓ No invalid monthly records found\n";
        }
        
        // 3. Fix quarterly records without kuartal value
        echo "\n3. Checking quarterly records without kuartal...\n";
        $invalidQuarterCount = DB::table('transaksi_nbms')
            ->where('periode_data', 'kuartalan')
            ->whereNull('kuartal')
            ->count();
        
        if ($invalidQuarterCount > 0) {
            echo "   Found $invalidQuarterCount quarterly records without kuartal\n";
            
            // Calculate kuartal from bulan if available
            $updated = DB::table('transaksi_nbms')
                ->where('periode_data', 'kuartalan')
                ->whereNull('kuartal')
                ->where('bulan', '>', 0)
                ->update([
                    'kuartal' => DB::raw('CEIL(bulan / 3)')
                ]);
            echo "   ✓ Fixed $updated quarterly records (calculated from bulan)\n";
            
            // Convert remaining to tahunan if still no kuartal
            $remaining = DB::table('transaksi_nbms')
                ->where('periode_data', 'kuartalan')
                ->whereNull('kuartal')
                ->update([
                    'periode_data' => 'tahunan',
                    'bulan' => null
                ]);
            
            if ($remaining > 0) {
                echo "   ✓ Converted $remaining remaining records to tahunan\n";
            }
        } else {
            echo "   ✓ No invalid quarterly records found\n";
        }
        
        // 4. Verify data source
        echo "\n4. Checking data sources...\n";
        $sources = DB::table('transaksi_nbms')
            ->select('data_source', DB::raw('COUNT(*) as total'))
            ->groupBy('data_source')
            ->get();
        
        foreach ($sources as $source) {
            echo "   {$source->data_source}: {$source->total} records\n";
        }
        
        echo "\n✓ Data cleaning completed\n";
    }
    
    /**
     * Insert records individually when batch insert fails
     */
    private function insertIndividually($insertStatement)
    {
        // Extract column names
        preg_match('/INSERT INTO `transaksi_nbms` \((.*?)\) VALUES/s', $insertStatement, $columnMatch);
        
        if (empty($columnMatch[1])) {
            echo "    Could not extract column names\n";
            return 0;
        }
        
        $columns = array_map('trim', explode(',', str_replace('`', '', $columnMatch[1])));
        
        // Extract all value sets
        preg_match_all('/\(([^)]+)\)(?=,|\s*;)/s', $insertStatement, $valueMatches);
        
        $inserted = 0;
        $skipped = 0;
        
        foreach ($valueMatches[1] as $valueSet) {
            try {
                // Parse values
                $values = $this->parseValues($valueSet);
                
                if (count($values) !== count($columns)) {
                    echo "    Skipping record: column count mismatch (" . count($values) . " vs " . count($columns) . ")\n";
                    $skipped++;
                    continue;
                }
                
                // Create associative array
                $data = array_combine($columns, $values);
                
                // Insert record
                DB::table('transaksi_nbms')->insert($data);
                $inserted++;
                
                if ($inserted % 100 == 0) {
                    echo "    Progress: $inserted inserted...\n";
                }
                
            } catch (\Exception $e) {
                $skipped++;
                if ($skipped <= 5) {
                    echo "    Skipped record: " . substr($e->getMessage(), 0, 100) . "\n";
                }
            }
        }
        
        echo "    Individual insertion: $inserted inserted, $skipped skipped\n";
        return $inserted;
    }
    
    /**
     * Parse SQL value set into array
     */
    private function parseValues($valueSet)
    {
        $values = [];
        $current = '';
        $inQuote = false;
        $quoteChar = null;
        
        for ($i = 0; $i < strlen($valueSet); $i++) {
            $char = $valueSet[$i];
            
            if (($char === "'" || $char === '"') && ($i === 0 || $valueSet[$i-1] !== '\\')) {
                if (!$inQuote) {
                    $inQuote = true;
                    $quoteChar = $char;
                } elseif ($char === $quoteChar) {
                    $inQuote = false;
                    $quoteChar = null;
                }
                continue;
            }
            
            if ($char === ',' && !$inQuote) {
                $values[] = $this->normalizeValue(trim($current));
                $current = '';
                continue;
            }
            
            $current .= $char;
        }
        
        // Add last value
        if ($current !== '') {
            $values[] = $this->normalizeValue(trim($current));
        }
        
        return $values;
    }
    
    /**
     * Normalize SQL value to PHP value
     */
    private function normalizeValue($value)
    {
        // NULL
        if (strtoupper($value) === 'NULL') {
            return null;
        }
        
        // Remove quotes
        if ((substr($value, 0, 1) === "'" && substr($value, -1) === "'") ||
            (substr($value, 0, 1) === '"' && substr($value, -1) === '"')) {
            return substr($value, 1, -1);
        }
        
        // Numeric
        if (is_numeric($value)) {
            if (strpos($value, '.') !== false) {
                return (float)$value;
            }
            return (int)$value;
        }
        
        return $value;
    }
}