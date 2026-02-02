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
        Schema::create('model_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20)->unique(); // e.g., v1.0.0, v1.0.1
            $table->string('model_name', 100); // e.g., LSTM Enhanced Ensemble
            $table->string('model_type', 50)->default('nbm_prediction'); // Type of model
            $table->string('folder_path'); // Path to model folder e.g., ml_models/models/nbm_v1.0.0
            $table->text('description')->nullable();
            
            // Model metrics
            $table->decimal('mae', 10, 2)->nullable();
            $table->decimal('rmse', 10, 2)->nullable();
            $table->decimal('mape', 10, 4)->nullable();
            $table->decimal('r2_score', 10, 4)->nullable();
            
            // Training info
            $table->integer('training_data_count')->nullable(); // Number of records used for training
            $table->date('training_data_from')->nullable(); // Oldest date in training data
            $table->date('training_data_to')->nullable(); // Latest date in training data
            $table->integer('epochs')->nullable();
            $table->json('model_config')->nullable(); // Store model configuration (architecture, hyperparameters)
            
            // Status
            $table->enum('status', ['training', 'completed', 'active', 'archived', 'failed'])->default('completed');
            $table->boolean('is_active')->default(false); // Currently active model for predictions
            
            // Release info
            $table->enum('release_stage', ['alpha', 'beta', 'production'])->default('beta');
            $table->timestamp('released_at')->nullable();
            $table->foreignId('trained_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Metadata
            $table->text('notes')->nullable(); // Additional notes or changelog
            $table->json('artifacts')->nullable(); // List of model files (model_lstm.keras, scaler_X.pkl, etc.)
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('version');
            $table->index('is_active');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_versions');
    }
};
