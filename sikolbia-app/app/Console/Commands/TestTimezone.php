<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestTimezone extends Command
{
    protected $signature = 'test:timezone';
    protected $description = 'Test timezone configuration';

    public function handle()
    {
        $this->info('App timezone: ' . config('app.timezone'));
        $this->info('Current time: ' . now()->format('Y-m-d H:i:s T'));
        $this->info('Current time UTC: ' . now('UTC')->format('Y-m-d H:i:s T'));
        
        // Get latest users
        $users = User::latest()->take(3)->get();
        
        if ($users->count() > 0) {
            $this->info('Recent users:');
            foreach ($users as $user) {
                $this->info("- {$user->name}: {$user->created_at->format('Y-m-d H:i:s T')} (Raw: {$user->getAttributes()['created_at']})");
            }
        } else {
            $this->info('No users found');
        }
        
        return 0;
    }
}
