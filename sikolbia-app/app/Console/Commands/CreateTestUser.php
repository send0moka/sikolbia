<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateTestUser extends Command
{
    protected $signature = 'test:create-user {name?}';
    protected $description = 'Create a test user to verify timezone';

    public function handle()
    {
        $name = $this->argument('name') ?: 'Test User ' . Str::random(5);
        $email = Str::slug($name) . '@test.com';
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password')
        ]);
        
        $this->info("User created successfully!");
        $this->info("Name: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Created at: {$user->created_at->format('Y-m-d H:i:s T')}");
        $this->info("Current time: " . now()->format('Y-m-d H:i:s T'));
        
        return 0;
    }
}
