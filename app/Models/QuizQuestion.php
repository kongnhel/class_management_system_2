<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
     use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text_km',
        'question_text_en',
        'question_type',
        'points',
        'order_number',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the quiz that the question belongs to.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the options for this quiz question.
     */
    public function options()
    {
        return $this->hasMany(QuizOption::class);
    }

    /**
     * Get the student responses for this quiz question.
     */
    public function studentQuizResponses()
    {
        return $this->hasMany(StudentQuizResponse::class);
    }
}
