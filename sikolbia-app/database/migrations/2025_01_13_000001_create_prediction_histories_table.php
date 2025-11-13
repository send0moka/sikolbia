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
        Schema::create('prediction_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('kode_kelompok', 10);
            $table->string('kode_komoditi', 10);
            $table->string('kelompok_name');
            $table->string('komoditi_name');
            $table->integer('bulan_prediksi');
            $table->json('prediction_data'); // Store full prediction result
            $table->json('historical_data'); // Store historical input data
            $table->json('confidence_intervals')->nullable();
            $table->string('model_version')->nullable();
            $table->text('notes')->nullable(); // User notes
            $table->boolean('is_bookmarked')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('kode_kelompok');
            $table->index('kode_komoditi');
            $table->index('is_bookmarked');
            $table->index('created_at');
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_histories');
    }
};
