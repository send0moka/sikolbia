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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['backup', 'restore'])->comment('Tipe operasi: backup atau restore');
            $table->string('filename')->comment('Nama file backup SQL');
            $table->string('filepath')->comment('Path lengkap file backup');
            $table->bigInteger('file_size')->nullable()->comment('Ukuran file dalam bytes');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('User yang melakukan operasi');
            $table->string('user_name')->comment('Nama user (untuk referensi)');
            $table->string('user_email')->comment('Email user (untuk referensi)');
            $table->enum('status', ['success', 'failed', 'in_progress'])->default('in_progress')->comment('Status operasi');
            $table->text('description')->nullable()->comment('Deskripsi atau catatan');
            $table->text('error_message')->nullable()->comment('Pesan error jika gagal');
            $table->json('tables_included')->nullable()->comment('Daftar tabel yang di-backup/restore');
            $table->integer('records_count')->nullable()->comment('Jumlah total records');
            $table->string('ip_address', 45)->nullable()->comment('IP address user');
            $table->text('user_agent')->nullable()->comment('Browser/user agent');
            $table->timestamp('started_at')->nullable()->comment('Waktu mulai operasi');
            $table->timestamp('completed_at')->nullable()->comment('Waktu selesai operasi');
            $table->integer('duration_seconds')->nullable()->comment('Durasi operasi dalam detik');
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index('type');
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
