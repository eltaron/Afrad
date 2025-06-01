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
        Schema::create('personnel_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnels')->cascadeOnDelete();
            $table->foreignId('violation_type_id')->constrained('violation_types')->cascadeOnDelete();
            $table->date('violation_date');
            $table->enum('penalty_type', ['confinement', 'detention', 'salary_deduction']);
            $table->integer('penalty_days')->nullable();
            $table->integer('leave_deduction_days')->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_violations');
    }
};
