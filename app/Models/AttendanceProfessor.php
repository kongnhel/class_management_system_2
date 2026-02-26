<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceProfessor extends Model
{
    protected $fillable = [
        'professor_id',
        'course_offering_id',
        'lat',
        'lng',
        'verified_at'
    ];
}