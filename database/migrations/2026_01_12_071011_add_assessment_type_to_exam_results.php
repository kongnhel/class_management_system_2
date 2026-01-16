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
        // បន្ថែមតែ type មួយបានហើយ ទុក exam_id នៅដដែល
        if (!Schema::hasColumn('exam_results', 'assessment_type')) {
            $table->string('assessment_type')->after('exam_id')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            //
        });
    }
};
