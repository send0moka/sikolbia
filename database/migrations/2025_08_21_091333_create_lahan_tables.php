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
        // Create lahan_topik table
        if (!Schema::hasTable('lahan_topik')) {
            Schema::create('lahan_topik', function (Blueprint $table) {
                $table->id();
                $table->string('deskripsi', 255)->nullable();
            });
        }

        // Create lahan_variabel table
        if (!Schema::hasTable('lahan_variabel')) {
            Schema::create('lahan_variabel', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_topik')->nullable();
                $table->string('deskripsi', 255)->nullable();
                $table->string('satuan', 100)->nullable();
                $table->integer('sorter')->nullable();
                
                $table->foreign('id_topik')
                      ->references('id')
                      ->on('lahan_topik')
                      ->onDelete('no action')
                      ->onUpdate('cascade');
                
                $table->index(['id_topik']);
            });
        }

        // Create lahan_klasifikasi table
        if (!Schema::hasTable('lahan_klasifikasi')) {
            Schema::create('lahan_klasifikasi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_variabel')->nullable();
                $table->string('deskripsi', 255)->nullable();
                $table->integer('sorter')->nullable();
                
                $table->foreign('id_variabel')
                      ->references('id')
                      ->on('lahan_variabel')
                      ->onDelete('no action')
                      ->onUpdate('cascade');
                
                $table->index(['id_variabel']);
            });
        }

        // Create lahan_data table
        if (!Schema::hasTable('lahan_data')) {
            Schema::create('lahan_data', function (Blueprint $table) {
                // Primary key
                $table->id();
                
                // Data columns
                $table->year('tahun');
                $table->unsignedBigInteger('id_bulan');
                $table->unsignedBigInteger('id_wilayah');
                $table->unsignedBigInteger('id_variabel');
                $table->unsignedBigInteger('id_klasifikasi');
                $table->decimal('nilai', 20, 4)->nullable();
                $table->string('status', 5)->nullable();
                $table->timestamps();
                
                // Composite unique constraint to prevent duplicates
                $table->unique(
                    ['tahun', 'id_bulan', 'id_wilayah', 'id_variabel', 'id_klasifikasi'],
                    'lahan_data_unique'
                );
                
                // Add foreign key constraints
                $table->foreign('id_bulan')
                      ->references('id')
                      ->on('bulan')
                      ->onDelete('no action')
                      ->onUpdate('cascade');

                $table->foreign('id_wilayah')
                      ->references('id')
                      ->on('wilayah')
                      ->onDelete('no action')
                      ->onUpdate('cascade');

                $table->foreign('id_variabel')
                      ->references('id')
                      ->on('lahan_variabel')
                      ->onDelete('no action')
                      ->onUpdate('cascade');

                $table->foreign('id_klasifikasi')
                      ->references('id')
                      ->on('lahan_klasifikasi')
                      ->onDelete('no action')
                      ->onUpdate('cascade');

                // Indexes for better query performance
                $table->index(['id_variabel', 'id_klasifikasi']);
                $table->index(['tahun', 'id_bulan']);
                $table->index('id_wilayah');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to avoid foreign key constraint violations
        Schema::dropIfExists('lahan_data');
        Schema::dropIfExists('lahan_klasifikasi');
        Schema::dropIfExists('lahan_variabel');
        Schema::dropIfExists('lahan_topik');
    }
};
