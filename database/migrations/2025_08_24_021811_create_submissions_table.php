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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('student_user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('submission_date');
            $table->string('file_path')->nullable(); // Path to the submitted file
            $table->integer('grade_received')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
