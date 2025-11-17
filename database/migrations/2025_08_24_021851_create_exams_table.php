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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_offering_id')->constrained('course_offerings')->onDelete('cascade');
            $table->string('title_km');
            $table->string('title_en');
            $table->dateTime('exam_date');
            $table->integer('duration_minutes');
            $table->integer('max_score');
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
