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
        Schema::table('komoditi', function (Blueprint $table) {
            $table->string('satuan_dasar', 50)->default('kg')->after('nama');
            $table->decimal('kalori_per_100g', 8, 2)->nullable()->after('satuan_dasar');
            $table->decimal('protein_per_100g', 8, 2)->nullable()->after('kalori_per_100g');
            $table->decimal('lemak_per_100g', 8, 2)->nullable()->after('protein_per_100g');
            $table->decimal('karbohidrat_per_100g', 8, 2)->nullable()->after('lemak_per_100g');
            $table->decimal('serat_per_100g', 8, 2)->nullable()->after('karbohidrat_per_100g');
            $table->decimal('vitamin_c_per_100g', 8, 2)->nullable()->after('serat_per_100g');
            $table->decimal('zat_besi_per_100g', 8, 2)->nullable()->after('vitamin_c_per_100g');
            $table->decimal('kalsium_per_100g', 8, 2)->nullable()->after('zat_besi_per_100g');
            $table->enum('musim_panen', ['jan-mar', 'apr-jun', 'jul-sep', 'okt-des', 'sepanjang_tahun'])->nullable()->after('kalsium_per_100g');
            $table->enum('asal_produksi', ['lokal', 'impor', 'campuran'])->default('lokal')->after('musim_panen');
            $table->integer('shelf_life_hari')->nullable()->after('asal_produksi');
            $table->decimal('harga_rata_per_kg', 10, 2)->nullable()->after('shelf_life_hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komoditi', function (Blueprint $table) {
            $table->dropColumn([
                'satuan_dasar', 'kalori_per_100g', 'protein_per_100g', 'lemak_per_100g',
                'karbohidrat_per_100g', 'serat_per_100g', 'vitamin_c_per_100g',
                'zat_besi_per_100g', 'kalsium_per_100g', 'musim_panen',
                'asal_produksi', 'shelf_life_hari', 'harga_rata_per_kg'
            ]);
        });
    }
};
