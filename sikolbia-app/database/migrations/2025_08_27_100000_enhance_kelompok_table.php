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
            $table->decimal('ake_ketersediaan', 8, 2)->nullable()->comment('Angka Kecukupan Energi ketersediaan')->after('deskripsi');
            $table->decimal('skor_pph', 8, 2)->nullable()->comment('Skor Pola Pangan Harapan')->after('ake_ketersediaan');
            $table->boolean('status_aktif')->default(true)->after('skor_pph');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'ake_ketersediaan', 'skor_pph', 'status_aktif']);
        });
    }
};
