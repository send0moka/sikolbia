<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\BenihPupukSeeder;

class SeedBenihPupuk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-benih-pupuk {--fresh : Drop tables and recreate them before seeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with benih pupuk data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('Dropping and recreating benih pupuk tables...');
            
            // Drop tables in reverse order due to foreign key constraints
            $tables = [
                'benih_pupuk_data',
                'benih_pupuk_variabel',
                'benih_pupuk_klasifikasi',
                'benih_pupuk_topik',
            ];
            
            foreach ($tables as $table) {
                $this->call('migrate:rollback', [
                    '--path' => 'database/migrations',
                    '--step' => 1
                ]);
            }
            
            $this->info('Running migrations...');
            $this->call('migrate', [
                '--path' => 'database/migrations',
                '--force' => true
            ]);
        }
        
        $this->info('Seeding benih pupuk data...');
        
        $seeder = new BenihPupukSeeder();
        $seeder->command = $this; // Set command context for seeder
        $seeder->run();
        
        $this->info('Benih pupuk data seeded successfully!');
    }
}
