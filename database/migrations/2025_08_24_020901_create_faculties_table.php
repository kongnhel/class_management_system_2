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
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name_km');
            $table->string('name_en');
            // Dean ត្រូវតែជា Professor, អាច set null ប្រសិនបើមិនទាន់មាន Dean
            $table->foreignId('dean_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
