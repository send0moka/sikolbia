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
        Schema::create('registrasi_akses', function (Blueprint $table) {
            $table->id();
            
            // Data pribadi
            $table->string('nama_lengkap');
            $table->string('nip_nik')->nullable();
            $table->string('email');
            $table->string('telepon');
            
            // Tipe registrasi
            $table->enum('tipe_akses', ['pemerintah', 'akademisi']);
            
            // Data instansi/institusi
            $table->string('instansi')->nullable(); // Untuk pemerintah
            $table->string('institusi')->nullable(); // Untuk akademisi
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('provinsi')->nullable();
            
            // Data khusus akademisi
            $table->string('gelar_akademik')->nullable();
            $table->string('bidang_keahlian')->nullable();
            $table->string('jenis_penelitian')->nullable();
            
            // Tujuan penggunaan
            $table->json('tujuan_penggunaan')->nullable(); // Array checkbox values
            $table->text('deskripsi_kebutuhan')->nullable();
            
            // Status proses
            $table->enum('status', ['pending', 'review', 'approved', 'rejected', 'need_documents'])->default('pending');
            $table->text('catatan_admin')->nullable(); // Catatan dari admin
            $table->timestamp('tanggal_review')->nullable();
            $table->timestamp('tanggal_approval')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Admin yang review
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('tipe_akses');
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi_akses');
    }
};
