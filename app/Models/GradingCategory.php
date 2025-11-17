<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingCategory extends Model
{
     use HasFactory;

    protected $fillable = [
        'course_id',
        'name_km',
        'name_en',
        'weight_percentage',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public const DEFAULT_COMPONENTS = [
            // Midterm Exam: 15%
            ['name_en' => 'Midterm Exam', 'name_km' => 'ការប្រឡងឆមាសកណ្តាល', 'weight_percentage' => 15],
            // Final Exam: 50%
            ['name_en' => 'Final Exam', 'name_km' => 'ការប្រឡងចុងឆមាស', 'weight_percentage' => 50],
            // Attendance: 15%
            ['name_en' => 'Attendance', 'name_km' => 'វត្តមាន', 'weight_percentage' => 15],
            // Assignment (Research Work): 20%
            ['name_en' => 'Assignment', 'name_km' => 'កិច្ចការស្រាវជ្រាវ', 'weight_percentage' => 20],
        ];
    /**
     * Get the course that the grading category belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the assignments that belong to this grading category.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
