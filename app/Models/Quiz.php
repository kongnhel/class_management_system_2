<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_offering_id',
        'title_km',
        'title_en',
        'description',
        // 'description_en',
        'start_date',
        'end_date',
        'max_score',
        // 'duration_minutes',
        // 'total_points', // Assuming this column exists for total possible score
        // 'is_published',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        // 'is_published' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the course offering that the quiz belongs to.
     */
    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class);
    }

    /**
     * Get the quiz questions for the quiz.
     * This relationship is crucial for the StudentController to fetch quiz details.
     */
    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
