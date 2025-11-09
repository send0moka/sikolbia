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
        Schema::table('kelompok', function (Blueprint $table) {
            $table->string('icon_class', 50)->default('fas fa-cube')->after('status_aktif');
            $table->string('color_class', 20)->default('gray')->after('icon_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropColumn(['icon_class', 'color_class']);
        });
    }
};
