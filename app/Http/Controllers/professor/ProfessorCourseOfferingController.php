<?php

namespace App\Http\Controllers\professor;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Program;
use App\Models\CourseOffering;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProfessorCourseOfferingController extends Controller
{
        public function myCourseOfferings()
    {
        $user = Auth::user();
        $courseOfferings = CourseOffering::where('lecturer_user_id', $user->id)
                                         ->with('course.department', 'lecturer')
                                         ->paginate(10); // Paginate for the view

        return view('professor.my-course-offerings', compact('courseOfferings'));
    }

    /*
    |--------------------------------------------------------------------------
    | Professor Management Functionality (Placeholders - will be expanded)
    |--------------------------------------------------------------------------
    */
// grading_category_id
    /**
     * Display departments for professors.
     */
    public function viewDepartments()
    {
        $departments = Department::with('faculty', 'head')->paginate(10);
        return view('professor.departments.index', compact('departments'));
    }

    /**
     * Display programs for professors.
     */
    public function viewPrograms()
    {
        $programs = Program::with('department')->paginate(10);
        return view('professor.programs.index', compact('programs'));
    }

    /**
     * Display courses for professors.
     */
    public function viewCourses()
    {
        $courses = Course::with('department', 'program')->paginate(10);
        return view('professor.courses.index', compact('courses'));
    }

    /**
     * Display all course offerings (not just the ones taught by the professor).
     */
    public function viewAllCourseOfferings()
    {
        $courseOfferings = CourseOffering::with('course', 'lecturer')->paginate(10);
        return view('professor.all-course-offerings.index', compact('courseOfferings'));
    }
}
