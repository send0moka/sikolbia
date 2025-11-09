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
        // Create iklimoptdpi_topik table
        Schema::create('iklimoptdpi_topik', function (Blueprint $table) {
            $table->id();
            $table->string('deskripsi');
            $table->timestamps();
        });

        // Create iklimoptdpi_variabel table
        Schema::create('iklimoptdpi_variabel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_topik')->constrained('iklimoptdpi_topik')->onDelete('cascade');
            $table->string('deskripsi');
            $table->string('satuan');
            $table->integer('sorter')->nullable();
            $table->timestamps();
            $table->index(['id_topik']);
        });

        // Create iklimoptdpi_klasifikasi table
        Schema::create('iklimoptdpi_klasifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_variabel')->constrained('iklimoptdpi_variabel')->onDelete('cascade');
            $table->string('deskripsi');
            $table->integer('nilai_min')->nullable();
            $table->integer('nilai_max')->nullable();
            $table->integer('sorter')->nullable();
            $table->timestamps();
            $table->index(['id_variabel']);
        });

        // Create iklimoptdpi_data table
        Schema::create('iklimoptdpi_data', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Data columns
            $table->year('tahun');
            $table->unsignedBigInteger('id_bulan');
            $table->unsignedBigInteger('id_variabel');
            $table->unsignedBigInteger('id_klasifikasi');
            $table->unsignedBigInteger('id_wilayah');
            $table->double('nilai')->nullable();
            $table->string('status', 5)->nullable();
            $table->timestamps();
            $table->unique(['tahun', 'id_bulan', 'id_variabel', 'id_klasifikasi', 'id_wilayah'], 'unique_data_combination');
            
     
            // Foreign key constraints
            $table->foreign('id_bulan')
                  ->references('id')
                  ->on('bulan')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('id_variabel')
                  ->references('id')
                  ->on('iklimoptdpi_variabel')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('id_klasifikasi')
                  ->references('id')
                  ->on('iklimoptdpi_klasifikasi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('id_wilayah')
                  ->references('id')
                  ->on('wilayah')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iklimoptdpi_data');
        Schema::dropIfExists('iklimoptdpi_klasifikasi');
        Schema::dropIfExists('iklimoptdpi_variabel');
        Schema::dropIfExists('iklimoptdpi_topik');
    }
};
