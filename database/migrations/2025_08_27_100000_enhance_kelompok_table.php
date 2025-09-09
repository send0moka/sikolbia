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
        Schema::table('kelompok', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nama');
            $table->enum('prioritas_nasional', ['tinggi', 'sedang', 'rendah'])->default('sedang')->after('deskripsi');
            $table->decimal('target_konsumsi_harian', 8, 2)->nullable()->comment('gram per hari per kapita')->after('prioritas_nasional');
            $table->boolean('status_aktif')->default(true)->after('target_konsumsi_harian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'prioritas_nasional', 'target_konsumsi_harian', 'status_aktif']);
        });
    }
};
