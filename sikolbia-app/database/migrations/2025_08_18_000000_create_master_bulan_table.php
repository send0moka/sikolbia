<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create master bulan table if it doesn't exist
        if (!Schema::hasTable('bulan')) {
            Schema::create('bulan', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 15);
                $table->string('nama_pendek', 3)->nullable();
                
                $table->charset = 'latin1';
                $table->collation = 'latin1_swedish_ci';
                $table->engine = 'InnoDB';
            });
            // Note: Run the MasterBulanSeeder to populate the data
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulan');
    }
};
