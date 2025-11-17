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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poster_user_id')->constrained('users')->onDelete('cascade'); // Admin/Professor who posted
            $table->string('title_km');
            $table->string('title_en');
            $table->text('content_km');
            $table->text('content_en');
            $table->string('target_role')->nullable(); // E.g., 'admin', 'professor', 'student', 'all'
            $table->foreignId('course_offering_id')->nullable()->constrained('course_offerings')->onDelete('cascade'); // Nullable for general announcements
            $table->timestamps();
        });
    }

    /**
     * បញ្ច្រាស Migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
