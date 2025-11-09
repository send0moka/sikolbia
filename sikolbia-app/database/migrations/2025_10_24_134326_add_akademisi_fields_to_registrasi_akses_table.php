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
            $table->string('jenjang_pendidikan')->nullable()->after('institusi');
            $table->string('program_studi')->nullable()->after('jenjang_pendidikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrasi_akses', function (Blueprint $table) {
            $table->dropColumn(['jenjang_pendidikan', 'program_studi']);
        });
    }
};
