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
    Schema::table('announcements', function (Blueprint $table) {
        // បន្ថែមកូឡោន is_read ជាប្រភេទ boolean និងកំណត់តម្លៃដើមជា false (0)
        $table->boolean('is_read')->default(false)->after('content_en'); 
    });
}

public function down(): void
{
    Schema::table('announcements', function (Blueprint $table) {
        // លុបកូឡោនវិញ ប្រសិនបើមានការ Rollback
        $table->dropColumn('is_read');
    });
}
};
