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
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            // កែប្រែ onDelete ពី 'cascade' ទៅ 'no action' ដើម្បីជៀសវាង multiple cascade paths ជាមួយ student_quiz_responses
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('no action');
            $table->text('option_text_km');
            $table->text('option_text_en');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_options');
    }
};
