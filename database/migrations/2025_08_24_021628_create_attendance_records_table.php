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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_offering_id')->constrained('course_offerings')->onDelete('cascade');
            $table->date('date');
            $table->string('status'); // present, absent, late
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
