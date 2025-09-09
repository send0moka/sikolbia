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
        Schema::create('daftar_alamat', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->string('gambar')->nullable();
            $table->string('nama_dinas');
            $table->text('alamat');
            $table->string('telp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Draft', 'Arsip', 'Pending'])->default('Aktif');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_alamat');
    }
};
