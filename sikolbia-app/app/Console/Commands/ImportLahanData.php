<?php

namespace App\Console\Commands;

use App\Models\LahanData;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLahanData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lahan:import {--file=ref_pertanianlahan.sql} {--batch-size=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import lahan data from SQL file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('⚠️  Direct SQL import has been replaced with realistic data generation');
        $this->info('🔄 For development, please use: php artisan db:seed --class=LahanDataRealisticSeeder');
        $this->info('📊 This provides realistic, varied data based on BPS references without large SQL files');
        $this->newLine();
        $this->info('💡 Benefits of the new approach:');
        $this->info('   • Realistic geographical variations');
        $this->info('   • Year-over-year trends (2018-2023)');
        $this->info('   • Randomized but plausible variations');
        $this->info('   • No dependency on large SQL files');
        $this->info('   • Annual data only (more efficient for web app)');
        $this->newLine();
        
        if ($this->confirm('Do you want to run LahanDataRealisticSeeder instead?', true)) {
            $this->call('db:seed', ['--class' => 'LahanDataRealisticSeeder']);
        }
        
        return 0;
    }
}
