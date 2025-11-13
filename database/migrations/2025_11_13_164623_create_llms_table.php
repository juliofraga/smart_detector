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
        Schema::create('llms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('provider');
            $table->string('model_id');
            $table->string('api_base_url');
            $table->text('api_key');
            $table->integer('max_tokens')->nullable();
            $table->decimal('default_temperature', 3, 2)->default(1.00);
            $table->integer('context_length')->nullable();
            $table->decimal('pricing_prompt_token', 10, 6)->nullable();
            $table->decimal('pricing_completion_token', 10, 6)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true)->comment('0 - FALSE, 1 - TRUE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llms');
    }
};
