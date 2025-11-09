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
            $table->string('resubmit_token', 64)->nullable()->after('catatan_admin');
            $table->timestamp('resubmit_token_expires_at')->nullable()->after('resubmit_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrasi_akses', function (Blueprint $table) {
            $table->dropColumn(['resubmit_token', 'resubmit_token_expires_at']);
        });
    }
};
