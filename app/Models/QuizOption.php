<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'option_text_km',
        'option_text_en',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the quiz question that the option belongs to.
     */
    public function quizQuestion()
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    /**
     * Get the student quiz responses that selected this option.
     */
    public function studentQuizResponses()
    {
        return $this->hasMany(StudentQuizResponse::class, 'selected_option_id');
    }
}
