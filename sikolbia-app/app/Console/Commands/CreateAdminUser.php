<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user 
                            {--name= : The name of the user}
                            {--email= : The email of the user}
                            {--password= : The password of the user}
                            {--role=admin : The role to assign to the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user with specified role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('Enter user name');
        $email = $this->option('email') ?: $this->ask('Enter user email');
        $password = $this->option('password') ?: $this->secret('Enter user password');
        $role = $this->option('role');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // Assign role
        $user->assignRole($role);

        $this->info("User {$name} created successfully with role: {$role}");
        $this->info("Email: {$email}");
        
        return 0;
    }
}
