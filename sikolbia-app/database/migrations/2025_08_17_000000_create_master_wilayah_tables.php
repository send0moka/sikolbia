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
         //  create wilayah kategori table
         if (!Schema::hasTable('wilayah_kategori')) {
            Schema::create('wilayah_kategori', function (Blueprint $table) {
                $table->id();
                $table->string('deskripsi', 100)->nullable();
                
                $table->charset = 'latin1';
                $table->collation = 'latin1_swedish_ci';
                $table->engine = 'InnoDB';
            });
        }

        // Create wilayah table
        if (!Schema::hasTable('wilayah')) {
            Schema::create('wilayah', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_kategori')->nullable();
                $table->unsignedBigInteger('id_parent')->nullable();
                $table->string('kode', 10)->nullable();
                $table->string('nama', 255)->nullable();
                $table->integer('sorter')->nullable();
                
                $table->foreign('id_kategori')
                      ->references('id')
                      ->on('wilayah_kategori')
                      ->onDelete('no action')
                      ->onUpdate('no action');
                
                $table->index(['id_kategori']);
                $table->index(['id_parent']);
                
                $table->charset = 'latin1';
                $table->collation = 'latin1_swedish_ci';
                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('wilayah_kategori');
       Schema::dropIfExists('wilayah');
    }
};
