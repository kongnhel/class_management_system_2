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
        Schema::table('courses', function (Blueprint $table) {
            // 💡 បន្ថែម field 'generation' ទៅក្នុងតារាង 'courses'
            $table->string('generation')->nullable()->after('program_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // 💡 លុប field 'generation' វិញ
            $table->dropColumn('generation');
        });
    }
};
