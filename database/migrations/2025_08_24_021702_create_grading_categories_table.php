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
        Schema::create('grading_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('name_km'); // ឧ. កិច្ចការ, Quiz, ប្រឡង
            $table->string('name_en');
            $table->integer('weight_percentage'); // E.g., 20, 30, 50
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_categories');
    }
};
