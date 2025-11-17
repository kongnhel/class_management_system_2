<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
        use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_user_id',
        'score_obtained',
        'recorded_at',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
// In Exam.php
public function grade()
{
    return $this->hasOne(ExamResult::class, 'exam_id')
                ->where('student_user_id', Auth::id());
}
    /**
     * Get the exam that the result belongs to.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the student (user) for the exam result.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }
}
