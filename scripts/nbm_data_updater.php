#!/usr/bin/env php
<?php

/**
 * NBM Data Update Utility
 * Tool untuk import data NBM dari publikasi Pusdatin Kementan
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\TransaksiNbm;
use App\Models\Komoditi;
use App\Models\Kelompok;

class NBMDataUpdater
{
    private $year;
    private $dataSource;
    private $validationErrors = [];
    
    public function __construct($year = 2024, $dataSource = 'pusdatin')
    {
        $this->year = $year;
        $this->dataSource = $dataSource;
    }
    
    /**
     * Import data dari CSV file
     */
    public function importFromCSV($csvFile)
    {
        echo "📊 Starting NBM data import for year {$this->year}\n";
        echo "📁 Source file: {$csvFile}\n";
        echo "📈 Data source: {$this->dataSource}\n\n";
        
        if (!file_exists($csvFile)) {
            echo "❌ Error: File {$csvFile} not found!\n";
            return false;
        }
        
        $data = $this->parseCSV($csvFile);
        $this->validateData($data);
        
        if (!empty($this->validationErrors)) {
            echo "❌ Validation errors found:\n";
            foreach ($this->validationErrors as $error) {
                echo "   - {$error}\n";
            }
            return false;
        }
        
        return $this->insertData($data);
    }
    
    /**
     * Parse CSV file
     */
    private function parseCSV($csvFile)
    {
        $data = [];
        $header = null;
        
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (!$header) {
                    $header = $row;
                    continue;
                }
                
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        
        echo "📥 Parsed " . count($data) . " rows from CSV\n";
        return $data;
    }
    
    /**
     * Validate imported data
     */
    private function validateData($data)
    {
        echo "🔍 Validating data...\n";
        
        foreach ($data as $index => $row) {
            // Check required fields
            $required = ['kode_komoditi', 'bulan', 'bahan_makanan'];
            foreach ($required as $field) {
                if (empty($row[$field])) {
                    $this->validationErrors[] = "Row " . ($index + 1) . ": Missing {$field}";
                }
            }
            
            // Validate komoditi exists
            if (!empty($row['kode_komoditi'])) {
                $komoditi = Komoditi::where('kode_komoditi', $row['kode_komoditi'])->first();
                if (!$komoditi) {
                    $this->validationErrors[] = "Row " . ($index + 1) . ": Komoditi {$row['kode_komoditi']} not found";
                }
            }
            
            // Validate numeric values
            if (!empty($row['bahan_makanan']) && !is_numeric($row['bahan_makanan'])) {
                $this->validationErrors[] = "Row " . ($index + 1) . ": Invalid bahan_makanan value";
            }
            
            // Validate month
            if (!empty($row['bulan']) && ($row['bulan'] < 1 || $row['bulan'] > 12)) {
                $this->validationErrors[] = "Row " . ($index + 1) . ": Invalid month value";
            }
        }
        
        if (empty($this->validationErrors)) {
            echo "✅ Data validation passed\n";
        }
    }
    
    /**
     * Insert validated data
     */
    private function insertData($data)
    {
        echo "💾 Inserting data into database...\n";
        
        $insertCount = 0;
        $updateCount = 0;
        
        foreach ($data as $row) {
            // Get komoditi info
            $komoditi = Komoditi::where('kode_komoditi', $row['kode_komoditi'])->first();
            
            // Check if record exists
            $existing = TransaksiNbm::where([
                'kode_komoditi' => $row['kode_komoditi'],
                'tahun' => $this->year,
                'bulan' => $row['bulan']
            ])->first();
            
            $dataToSave = [
                'kode_kelompok' => $komoditi->kode_kelompok,
                'kode_komoditi' => $row['kode_komoditi'],
                'tahun' => $this->year,
                'bulan' => $row['bulan'],
                'bahan_makanan' => $row['bahan_makanan'],
                'data_source' => $this->dataSource,
                'validation_status' => 'verified',
                'confidence_score' => 0.95, // High confidence for official data
                'updated_at' => now()
            ];
            
            if ($existing) {
                $existing->update($dataToSave);
                $updateCount++;
            } else {
                $dataToSave['created_at'] = now();
                TransaksiNbm::create($dataToSave);
                $insertCount++;
            }
        }
        
        echo "✅ Data import completed!\n";
        echo "📈 Records inserted: {$insertCount}\n";
        echo "📝 Records updated: {$updateCount}\n";
        
        return true;
    }
    
    /**
     * Generate sample CSV template
     */
    public function generateTemplate($outputFile = null)
    {
        if (!$outputFile) {
            $outputFile = "nbm_import_template_{$this->year}.csv";
        }
        
        $headers = [
            'kode_komoditi',
            'bulan', 
            'bahan_makanan',
            'masukan',
            'keluaran',
            'keterangan'
        ];
        
        $sampleData = [
            ['0101', '1', '2456.78', '50000', '47544', 'Data Pusdatin Jan 2024'],
            ['0102', '1', '2234.56', '55000', '52765', 'Data Pusdatin Jan 2024'],
            ['0201', '1', '1876.34', '25000', '23124', 'Data Pusdatin Jan 2024']
        ];
        
        $fp = fopen($outputFile, 'w');
        fputcsv($fp, $headers);
        
        foreach ($sampleData as $row) {
            fputcsv($fp, $row);
        }
        
        fclose($fp);
        
        echo "📄 Template generated: {$outputFile}\n";
        echo "📝 Edit this file with actual Pusdatin data and run import\n";
    }
}

// CLI Usage
if ($argc > 1) {
    $updater = new NBMDataUpdater(2024, 'pusdatin_2024');
    
    switch ($argv[1]) {
        case 'template':
            $outputFile = $argv[2] ?? null;
            $updater->generateTemplate($outputFile);
            break;
            
        case 'import':
            if (empty($argv[2])) {
                echo "Usage: php nbm_data_updater.php import <csv_file>\n";
                exit(1);
            }
            $updater->importFromCSV($argv[2]);
            break;
            
        default:
            echo "Usage:\n";
            echo "  php nbm_data_updater.php template [output_file]\n";
            echo "  php nbm_data_updater.php import <csv_file>\n";
            break;
    }
} else {
    echo "NBM Data Updater - Pusdatin Integration Tool\n";
    echo "Usage:\n";
    echo "  php nbm_data_updater.php template [output_file]\n";
    echo "  php nbm_data_updater.php import <csv_file>\n";
}