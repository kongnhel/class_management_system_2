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
    Schema::table('exam_results', function (Blueprint $table) {
        // បន្ថែម Column ដើម្បីបែងចែកប្រភេទ Assignment, Quiz ឬ Exam
        $table->string('assessment_type')->after('exam_id')->nullable();
        
        // កែ Column exam_id ឱ្យទៅជា assessment_id ដើម្បីកុំឱ្យច្រឡំ (Option)
        $table->renameColumn('exam_id', 'assessment_id'); 
    });
}

public function down(): void
{
    Schema::table('exam_results', function (Blueprint $table) {
        $table->renameColumn('assessment_id', 'exam_id');
        $table->dropColumn('assessment_type');
    });
}
};
