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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // Translatable
            $table->integer('default_days');
            $table->enum('applies_to', ['all', 'military', 'civilian', 'specific_rank', 'specific_job_title']);
            $table->string('specific_rank_or_title')->nullable();
            $table->boolean('is_permission')->default(false); // For 'إذن'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
