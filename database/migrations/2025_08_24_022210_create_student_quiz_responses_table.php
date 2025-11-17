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
        Schema::create('student_quiz_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade'); // បន្ត cascade
            $table->foreignId('student_user_id')->constrained('users')->onDelete('cascade'); // បន្ត cascade
            // កែប្រែ onDelete ពី 'cascade' ទៅ 'set null' សម្រាប់ selected_option_id ដើម្បីជៀសវាង multiple cascade paths
            $table->foreignId('selected_option_id')->nullable()->constrained('quiz_options')->onDelete('set null');
            $table->text('short_answer_text')->nullable(); // ចម្លើយសម្រាប់ Short Answer
            $table->dateTime('submitted_at'); // កាលបរិច្ឆេទដាក់ចម្លើយ
            $table->boolean('is_correct')->nullable(); // តើចម្លើយត្រឹមត្រូវឬអត់ (សម្រាប់ Short Answer ត្រូវវាយតម្លៃដោយដៃ)
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_quiz_responses');
    }
};
