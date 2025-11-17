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
        Schema::create('student_program_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->date('enrollment_date');
            $table->date('graduation_date')->nullable();
            $table->string('status')->default('active'); // active, graduated, withdrawn
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_program_enrollments');
    }
};
