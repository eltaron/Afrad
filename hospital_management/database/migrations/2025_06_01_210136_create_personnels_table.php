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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('military_id')->nullable()->unique();
            $table->string('national_id')->nullable()->unique();
            $table->string('phone_number')->nullable();
            $table->date('recruitment_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('job_title')->nullable(); // For civilians
            $table->string('rank')->nullable(); // For military
            $table->foreignId('hospital_force_id')->constrained('hospital_forces')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Link to users table, optional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
