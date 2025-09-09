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
        Schema::create('tb_komoditibps', function (Blueprint $table) {
            $table->string('kd_komoditibps')->primary(); // Primary key sebagai string
            $table->string('nm_komoditibps'); // Nama komoditi BPS
            $table->string('kd_kelompokbps'); // Foreign key ke tb_kelompokbps
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('kd_kelompokbps')->references('kd_kelompokbps')->on('tb_kelompokbps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_komoditibps');
    }
};
