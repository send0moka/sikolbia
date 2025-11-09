<?php

namespace App\Console\Commands;

use App\Models\Kelompok;
use Illuminate\Console\Command;

class TestKelompok extends Command
{
    protected $signature = 'test:kelompok';
    protected $description = 'Test kelompok data';

    public function handle()
    {
        $this->info('Total kelompok: ' . Kelompok::count());
        $this->info('Data kelompok:');
        
        Kelompok::all()->each(function($kelompok) {
            $this->info("- {$kelompok->kode}: {$kelompok->nama}");
        });
        
        return 0;
    }
}
