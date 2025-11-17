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
        Schema::table('programs', function (Blueprint $table) {
            // បន្ថែម Column 'degree_level' ប្រភេទ string ដែលអាចទុកជា null
            // Add 'degree_level' column as a nullable string.
            // អ្នកអាចផ្លាស់ប្តូរ 'nullable()' ទៅជា 'default(\"បរិញ្ញាបត្រ\")' ប្រសិនបើអ្នកចង់បាន Default Value។
            $table->string('degree_level')->nullable()->after('name_en'); // ដាក់វានៅក្រោយ 'name_en'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // លុប Column 'degree_level' ប្រសិនបើ Migration ត្រូវបាន Rollback
            // Drops the 'degree_level' column if the migration is rolled back.
            $table->dropColumn('degree_level');
        });
    }
};
