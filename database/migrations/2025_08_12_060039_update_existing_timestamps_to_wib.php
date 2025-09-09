<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing timestamps in users table to WIB (+7 hours)
        // This migration assumes that existing timestamps are stored in UTC
        // and converts them to WIB (Asia/Jakarta timezone)
        
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite syntax using datetime function
            DB::statement("
                UPDATE users 
                SET 
                    created_at = datetime(created_at, '+7 hours'),
                    updated_at = datetime(updated_at, '+7 hours')
                WHERE created_at IS NOT NULL
            ");
            
            // Update email_verified_at if it exists and is not null
            DB::statement("
                UPDATE users 
                SET email_verified_at = datetime(email_verified_at, '+7 hours')
                WHERE email_verified_at IS NOT NULL
            ");
        } else {
            // MySQL/MariaDB syntax using DATE_ADD
            DB::statement("
                UPDATE users 
                SET 
                    created_at = DATE_ADD(created_at, INTERVAL 7 HOUR),
                    updated_at = DATE_ADD(updated_at, INTERVAL 7 HOUR)
                WHERE created_at IS NOT NULL
            ");
            
            // Update email_verified_at if it exists and is not null
            DB::statement("
                UPDATE users 
                SET email_verified_at = DATE_ADD(email_verified_at, INTERVAL 7 HOUR)
                WHERE email_verified_at IS NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert timestamps back to UTC by subtracting 7 hours
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite syntax using datetime function
            DB::statement("
                UPDATE users 
                SET 
                    created_at = datetime(created_at, '-7 hours'),
                    updated_at = datetime(updated_at, '-7 hours')
                WHERE created_at IS NOT NULL
            ");
            
            DB::statement("
                UPDATE users 
                SET email_verified_at = datetime(email_verified_at, '-7 hours')
                WHERE email_verified_at IS NOT NULL
            ");
        } else {
            // MySQL/MariaDB syntax using DATE_SUB
            DB::statement("
                UPDATE users 
                SET 
                    created_at = DATE_SUB(created_at, INTERVAL 7 HOUR),
                    updated_at = DATE_SUB(updated_at, INTERVAL 7 HOUR)
                WHERE created_at IS NOT NULL
            ");
            
            DB::statement("
                UPDATE users 
                SET email_verified_at = DATE_SUB(email_verified_at, INTERVAL 7 HOUR)
                WHERE email_verified_at IS NOT NULL
            ");
        }
    }
};
