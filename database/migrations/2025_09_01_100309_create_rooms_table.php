<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * រត់ការ migration ។
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // ជួរឈរ Primary Key
            $table->string('room_number')->unique()->comment('លេខបន្ទប់ (ឧ. A101, B205)');
            $table->integer('capacity')->comment('សមត្ថភាពផ្ទុកអតិបរមានៃបន្ទប់');
            $table->string('wifi_name')->nullable()->comment('ឈ្មោះ Wifi');
            $table->string('wifi_password')->nullable()->comment('លេខសម្ងាត់ Wifi');
            $table->string('location_of_room')->nullable()->comment('ទីតាំងបន្ទប់');
            $table->string('type_of_room')->nullable()->comment('ប្រភេទបន្ទប់');
            $table->timestamps();
        });
    }

    /**
     * ត្រឡប់ក្រោយ migration ។
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
