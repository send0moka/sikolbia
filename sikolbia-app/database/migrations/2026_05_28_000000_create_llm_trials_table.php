<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llm_trials', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 32);
            $table->string('purpose', 64)->nullable();
            $table->string('model', 128)->nullable();

            $table->boolean('success')->default(false);
            $table->unsignedInteger('latency_ms')->nullable();

            $table->unsignedInteger('messages_count')->nullable();
            $table->unsignedInteger('prompt_chars')->nullable();
            $table->unsignedInteger('response_chars')->nullable();

            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->unsignedInteger('total_tokens')->nullable();

            $table->decimal('cost_prompt_usd', 12, 6)->nullable();
            $table->decimal('cost_completion_usd', 12, 6)->nullable();
            $table->decimal('cost_total_usd', 12, 6)->nullable();

            $table->string('request_hash', 64)->nullable();
            $table->json('request_meta')->nullable();

            $table->string('error_type', 64)->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();

            $table->index(['provider', 'model']);
            $table->index(['purpose', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llm_trials');
    }
};
