<?php

namespace App\Http\Controllers\professor;
use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Assignment;
use App\Models\Grade; // New Model Import
use App\Models\Course; // New Model Import
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth; // New Import
use Illuminate\Pagination\LengthAwarePaginator; // New Import

class GradeController extends Controller
{
    
    public function professorCourses()
    {
        $user = Auth::user();

        $courseOfferings = CourseOffering::where('lecturer_id', $user->id)
            ->with('course')
            ->orderBy('academic_year', 'desc')
            ->get();

        // Assuming you have a view like 'professor.courses-list' to show this data
        return view('professor.courses-list', compact('courseOfferings'));
    }
    /**
     * Show the form for managing grades for a specific course offering.
     */
    public function manageGrades($courseOfferingId)
    {
        // Assume CourseOffering is the main entity linking all components
        $courseOffering = CourseOffering::with('course', 'lecturer')
            ->findOrFail($courseOfferingId);

        // 1. Get all relevant assignments for this course offering
        $assignments = Assignment::whereHas('component.course', function ($query) use ($courseOffering) {
            $query->where('id', $courseOffering->course_id);
        })
        ->with('component')
        ->get();

        // 2. Get all enrolled students
        // And preload their existing grades for the current assignments
        $studentIds = $courseOffering->studentCourseEnrollments->pluck('student_id');
        
        $students = User::whereIn('id', $studentIds)
            ->with(['grades' => function ($query) use ($assignments) {
                // Filter grades only for the assignments relevant to this page
                $query->whereIn('assignment_id', $assignments->pluck('id'));
            }])
            ->get();


        return view('manage-grades', [
            'courseOffering' => $courseOffering,
            'students' => $students,
            'assignments' => $assignments,
        ]);
    }
    
    /**
     * Store or update grades submitted from the form.
     */
    public function storeOrUpdate(Request $request, $courseOfferingId)
    {
        // 1. Initial Validation: Check if grades array exists and basic format
        $request->validate([
            'grades' => 'required|array',
            'grades.*.*' => 'nullable|numeric|min:0', // grades[student_id][assignment_id]
        ]);

        $gradesData = $request->input('grades');
        // Ensure $gradesData is not empty and has the structure grades[student_id][assignment_id]
        if (empty($gradesData) || !is_array(current($gradesData))) {
             return redirect()->back()->with('error', 'ទម្រង់ទិន្នន័យមិនត្រឹមត្រូវ។');
        }
        
        $assignmentIds = array_keys(current($gradesData)); // Get all submitted assignment IDs

        // Fetch max points for all relevant assignments in one query
        $assignmentMaxPoints = Assignment::whereIn('id', $assignmentIds)
            ->pluck('max_points', 'id'); // Map of [assignment_id => max_points]
            
        $errors = [];

        // 2. Cross-check Validation: Ensure score doesn't exceed max_points
        foreach ($gradesData as $studentId => $assignmentGrades) {
            foreach ($assignmentGrades as $assignmentId => $scoreReceived) {
                // Skip if score is empty or not numeric (already validated, but check again for safety)
                if (!is_numeric($scoreReceived) || $scoreReceived === null) {
                    continue;
                }
                
                $maxPoints = $assignmentMaxPoints->get($assignmentId);

                if ($maxPoints !== null && $scoreReceived > $maxPoints) {
                    // Record validation error
                    // To avoid N+1 queries here, we might need to fetch student and assignment names beforehand
                    $assignmentName = Assignment::find($assignmentId)->assignment_name ?? "Assignment ID: $assignmentId";
                    $studentName = User::find($studentId)->full_name_km ?? "Student ID: $studentId";

                    $errors["grades.$studentId.$assignmentId"] = 
                        "ពិន្ទុដែលបញ្ចូល (" . $scoreReceived . ") សម្រាប់ " . $studentName . " លើកិច្ចការ '" . $assignmentName . "' គឺលើសពីពិន្ទុអតិបរមា (" . $maxPoints . ")។";
                }
            }
        }
        
        // Throw ValidationException if errors were found
        if (!empty($errors)) {
            // Flash the errors and redirect back, similar to default Laravel validation behavior
            throw ValidationException::withMessages($errors);
        }


        // 3. Process grades and perform mass upsert (Update or Insert)
        $upsertData = [];
        $timestamp = now();

        foreach ($gradesData as $studentId => $assignmentGrades) {
            foreach ($assignmentGrades as $assignmentId => $scoreReceived) {
                // Skip if score is empty
                if (!is_numeric($scoreReceived) || $scoreReceived === null) {
                    continue;
                }
                
                // Add to the upsert array
                $upsertData[] = [
                    'student_id' => $studentId,
                    'assignment_id' => $assignmentId,
                    'score_received' => $scoreReceived,
                    'created_at' => $timestamp, 
                    'updated_at' => $timestamp,
                ];
            }
        }

        if (!empty($upsertData)) {
            // Using DB::table()->upsert for efficient bulk updates/inserts
            DB::table('grades')->upsert(
                $upsertData,
                ['student_id', 'assignment_id'], // Unique by student and assignment
                ['score_received', 'updated_at'] // Fields to update if unique key exists
            );
        }

        return redirect()->back()->with('success', 'ពិន្ទុត្រូវបានរក្សាទុក/កែសម្រួលដោយជោគជ័យ។');
    }


    /**
     * Show a list of all grades (individual assessment scores) given by the authenticated professor.
     * This replaces the logic found in the original ProfessorController::allGrades().
     */
    public function allGrades(Request $request)
    {
        // 1. Get the current user (Professor)
        $user = Auth::user();

        // 2. Identify all Course IDs taught by this professor (based on instructor_id in courses table)
        $taughtCourseIds = Course::where('instructor_id', $user->id)->pluck('id');
        
        if ($taughtCourseIds->isEmpty()) {
            // If the professor teaches no courses, return an empty paginated result
            $grades = new LengthAwarePaginator([], 0, 10, LengthAwarePaginator::resolveCurrentPage('gradesPage'), [
                'path' => $request->url(),
                'pageName' => 'gradesPage',
            ]);
            return view('professor.all-grades', compact('grades'));
        }

        // 3. Find all Assignments related to these courses (via GradeComponent)
        $assignmentIds = Assignment::whereHas('component', function ($query) use ($taughtCourseIds) {
            $query->whereIn('course_id', $taughtCourseIds);
        })->pluck('id');
        
        if ($assignmentIds->isEmpty()) {
             // If there are courses but no assignments set up yet
            $grades = new LengthAwarePaginator([], 0, 10, LengthAwarePaginator::resolveCurrentPage('gradesPage'), [
                'path' => $request->url(),
                'pageName' => 'gradesPage',
            ]);
            return view('professor.all-grades', compact('grades'));
        }

        // 4. Fetch all Grades for these Assignments, with necessary relationships
        $allGradesQuery = Grade::whereIn('assignment_id', $assignmentIds)
            ->with([
                'assignment.component.course', // Course, Component, and Assignment details
                'student' // Student details
            ])
            ->orderByDesc('updated_at'); // Sort by most recently updated grade

        $perPage = 10;
        
        // Paginate the results
        $grades = $allGradesQuery->paginate($perPage, ['*'], 'gradesPage');

        // Map the grades collection to transform it into the required display structure
        $grades->getCollection()->transform(function ($grade) {
            $assignment = $grade->assignment;
            $component = $assignment->component;
            $course = $component->course;
            $student = $grade->student;
            
            // Assessment Type is Component Name + Assignment Name
            $assessmentType = ($component->component_name ?? 'N/A') . ': ' . ($assignment->assignment_name ?? 'N/A');
            
            return (object)[
                'id' => $grade->id,
                'type' => $component->component_name, // E.g., Homework, Midterm, Final Exam
                'course_title_km' => $course->course_name_km ?? $course->course_name ?? 'N/A', // Adjust field names as per your Course model
                'course_title_en' => $course->course_name_en ?? $course->course_name ?? 'N/A',
                'assessment_type' => $assessmentType,
                'student_name' => $student->full_name_km ?? $student->full_name_en ?? $student->name, // Adjust field names as per your User model
                'score' => $grade->score_received,
                'max_score' => $assignment->max_points,
                'date' => $grade->updated_at,
            ];
        });

        return view('professor.all-grades', compact('grades'));
    }
}
