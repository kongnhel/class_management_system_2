<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceQrToken extends Model
{
    use HasFactory;

    // បន្ថែមចំណុចនេះដើម្បីអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យបាន
    protected $fillable = [
        'course_offering_id',
        'token_code',
        'expires_at',
    ];

    // Optional: កំណត់ឱ្យវាស្គាល់ថាជាប្រភេទកាលបរិច្ឆេទ
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}