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
        Schema::table('registrasi_akses', function (Blueprint $table) {
            // Document upload fields
            $table->string('surat_permohonan')->nullable()->after('deskripsi_kebutuhan');
            $table->string('id_instansi')->nullable()->after('surat_permohonan');
            $table->string('surat_atasan')->nullable()->after('id_instansi');
            
            // Additional fields for akademisi documents
            $table->string('surat_keterangan_institusi')->nullable()->after('surat_atasan');
            $table->string('proposal_penelitian')->nullable()->after('surat_keterangan_institusi');
            
            // Document upload timestamps
            $table->timestamp('dokumen_uploaded_at')->nullable()->after('proposal_penelitian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrasi_akses', function (Blueprint $table) {
            $table->dropColumn([
                'surat_permohonan',
                'id_instansi', 
                'surat_atasan',
                'surat_keterangan_institusi',
                'proposal_penelitian',
                'dokumen_uploaded_at'
            ]);
        });
    }
};
