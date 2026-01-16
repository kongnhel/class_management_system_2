<?php

namespace App\Http\Controllers\professor;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Program;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Assignment;
use App\Models\Notification;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\AttendanceRecord;
use App\Models\Submission;
use App\Models\ExamResult;
use App\Models\Announcement;
use App\Models\StudentQuizResponse;
use App\Models\GradingCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\StudentProfile;
use App\Models\StudentCourseEnrollment;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // បានបន្ថែម
use Illuminate\Support\Facades\Notification as NotificationFacade; // បានเอา comment ចេញ
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Storage;

use App\Exports\GradebookExport;
use Maatwebsite\Excel\Facades\Excel;


use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;

use Illuminate\Support\Facades\Http;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfessorController extends Controller
{







    /**
     * Display the professor dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $today = now()->format('l');



        $unreadNotificationsCount = $user->unreadNotifications()->count();

        // Get all course offerings taught by the professor
        $courseOfferings = CourseOffering::where('lecturer_user_id', $user->id)
                                         ->with('course')
                                         ->get();

        // Calculate total students across all their courses (unique students)
        $totalStudents = 0;
        $studentIds = [];
        foreach ($courseOfferings as $offering) {
            foreach ($offering->studentCourseEnrollments as $enrollment) {
                $studentIds[$enrollment->student_user_id] = true;
            }
        }
        $totalStudents = count($studentIds);

        // Upcoming assignments from the professor's course offerings
        $upcomingAssignments = Assignment::whereHas('courseOffering', function ($query) use ($user) {
                                            $query->where('lecturer_user_id', $user->id);
                                        })
                                        ->where('due_date', '>=', now() ->toDateString())
                                        ->orderBy('due_date')
                                        ->take(5)
                                        ->get();

        // Upcoming exams from the professor's course offerings
        $upcomingExams = Exam::whereHas('courseOffering', function ($query) use ($user) {
                                $query->where('lecturer_user_id', $user->id);
                            })
                            ->where('exam_date', '>=', now()->toDateString())
                            ->orderBy('exam_date')
                            ->take(5)
                            ->get();
        // $announcements = Announcement::where('poster_user_id', auth()->id())
        //                      ->orderBy('created_at', 'desc')
        //                      ->get();
        $announcements = Announcement::where('target_role', 'all')
                             ->orWhere('target_role', 'professor')
                             ->orderBy('created_at', 'desc')
                             ->get();

        $upcomingQuizzes = Quiz::whereHas('courseOffering', function ($query) use ($user) {
            // $query->where('lecturer_user_id', $user->id);
        })
        ->whereDate('quiz_date', '>=', now()->toDateString()) 
        ->orderBy('quiz_date', 'asc')
        ->take(5)
        ->get();
                             // ទាញយកកាលវិភាគបង្រៀនសម្រាប់ថ្ងៃនេះ
    $todaySchedules = Schedule::whereHas('courseOffering', function ($query) use ($user) {
            $query->where('lecturer_user_id', $user->id);
        })
        ->where('day_of_week', $today) // ត្រូវប្រាកដថា Column នេះរក្សាទុកឈ្មោះថ្ងៃ (Monday, Tuesday...)
        ->with(['courseOffering.course', 'room'])
        ->orderBy('start_time')
        ->get();



        return view('professor.dashboard', compact(
            'user',
            'courseOfferings',
            'totalStudents',
            'upcomingAssignments',
            'upcomingExams',
            'upcomingQuizzes',
            'announcements',
            'unreadNotificationsCount',
            'todaySchedules',
        ));
    }


public function markAsRead(Request $request, Announcement $announcement)
{
    // Check if the authenticated user is a professor
    if (Auth::user()->role === 'professor') {
        $announcement->is_read = true;
        $announcement->save();

        return response()->json(['message' => 'សេចក្តីប្រកាសត្រូវបានសម្គាល់ថាបានអានហើយ។']);
    }

    return response()->json(['message' => 'គ្មានការអនុញ្ញាត.'], 403);
}
// assessments
    /**
     * Display a list of course offerings taught by the authenticated professor.
     */
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

// public function manageGrades($offering_id)
// {
//     $courseOffering = CourseOffering::with([
//         'course',
//         'studentCourseEnrollments.student.studentProfile' 
//     ])->findOrFail($offering_id);

//     // ១. ទាញយក Assignments, Exams, Quizzes
//     $assignments = \App\Models\Assignment::where('course_offering_id', $offering_id)->get();
//     $exams = \App\Models\Exam::where('course_offering_id', $offering_id)->get();
//     $quizzes = \App\Models\Quiz::where('course_offering_id', $offering_id)->get();

//     $assessments = collect($assignments)->concat($exams)->concat($quizzes)->sortBy('created_at');

//     // ២. រៀបចំ Gradebook ដើម្បីយកមកគណនា Rank
//     $gradebook = [];
//     $students = $courseOffering->studentCourseEnrollments->map(function ($enrollment) use ($assessments, &$gradebook) {
//         $student = $enrollment->student;
//         $totalScore = $student->attendance_score ?? 0;

//         foreach ($assessments as $assessment) {
//             // កំណត់ប្រភេទឱ្យត្រូវតាម Class (assignment, quiz, exam)
//             $type = ($assessment instanceof \App\Models\Assignment) ? 'assignment' : 
//                    (($assessment instanceof \App\Models\Quiz) ? 'quiz' : 'exam');

//             // ទាញពិន្ទុពី ExamResult (ប្រើ assessment_id សម្រាប់គ្រប់ប្រភេទ)
//             $score = \App\Models\ExamResult::where('assessment_id', $assessment->id)
//                 ->where('student_user_id', $student->id)
//                 ->where('assessment_type', $type) // ឆែកតាមប្រភេទ 'assignment', 'exam', 'quiz'
//                 ->value('score_obtained');
            
//             // រក្សាទុកក្នុង Array សម្រាប់ផ្ញើទៅ Blade
//             $gradebook[$student->id][$type . '_' . $assessment->id] = $score;
            
//             // បូកបញ្ចូលក្នុងពិន្ទុសរុប
//             $totalScore += (is_numeric($score) ? $score : 0);
//         }

//         $student->temp_total = $totalScore;
//         return $student;
//     });

//     // ៣. តម្រៀប Ranking (ទុកដដែល)
//     $students = $students->sortByDesc('temp_total')->values();

//     // ៤. ផ្ដល់ Rank និង Grade (ទុកដដែល)
//     foreach ($students as $index => $student) {
//         $student->rank = $index + 1;
//         $ts = $student->temp_total;
//         if ($ts >= 85) $student->letterGrade = 'A';
//         elseif ($ts >= 80) $student->letterGrade = 'B+';
//         elseif ($ts >= 70) $student->letterGrade = 'B';
//         elseif ($ts >= 65) $student->letterGrade = 'C+';
//         elseif ($ts >= 50) $student->letterGrade = 'C';
//         else $student->letterGrade = 'F';
//     }

//     return view('professor.grades.index', compact('courseOffering', 'students', 'assessments', 'gradebook'));
// }


public function manageGrades($offering_id)
{
    $courseOffering = CourseOffering::with([
        'course',
        'studentCourseEnrollments.student.studentProfile' 
    ])->findOrFail($offering_id);

    // ១. ទាញយក Assignments, Exams, Quizzes
    $assignments = \App\Models\Assignment::where('course_offering_id', $offering_id)->get();
    $exams = \App\Models\Exam::where('course_offering_id', $offering_id)->get();
    $quizzes = \App\Models\Quiz::where('course_offering_id', $offering_id)->get();

    $assessments = collect($assignments)->concat($exams)->concat($quizzes)->sortBy('created_at');

    // ទាញយកពិន្ទុទាំងអស់មកទុកក្នុង Memory តែម្តង (ដើម្បីកុំឱ្យយឺត និងស្រួល Sort)
    $allResults = \App\Models\ExamResult::whereIn('student_user_id', $courseOffering->studentCourseEnrollments->pluck('student_user_id'))
        ->get();

    // ២. រៀបចំ Gradebook និងគណនាពិន្ទុ
    $gradebook = [];
    $students = $courseOffering->studentCourseEnrollments->map(function ($enrollment) use ($assessments, $allResults, &$gradebook, $offering_id) {
        $student = $enrollment->student;
        
        // ប្រសិនបើអ្នកមាន Function គណនាពិន្ទុវត្តមាន សូមហៅប្រើនៅទីនេះ
        $attendanceScore = (float)($student->getAttendanceScoreByCourse($offering_id) ?? 0);
        $totalScore = $attendanceScore;

        foreach ($assessments as $assessment) {
            $type = ($assessment instanceof \App\Models\Assignment) ? 'assignment' : 
                   (($assessment instanceof \App\Models\Quiz) ? 'quiz' : 'exam');

            // ស្វែងរកពិន្ទុក្នុង Collection (លឿនជាង Query ក្នុង Loop)
            $scoreRecord = $allResults->where('assessment_id', $assessment->id)
                                      ->where('student_user_id', $student->id)
                                      ->where('assessment_type', $type)
                                      ->first();
            
            $score = $scoreRecord ? (float)$scoreRecord->score_obtained : 0;
            $gradebook[$student->id][$type . '_' . $assessment->id] = $score;
            
            $totalScore += $score;
        }

        $student->temp_total = (float)$totalScore; // បង្ខំឱ្យទៅជាលេខទសភាគដើម្បី Sort ឱ្យត្រូវ
        return $student;
    });

    // ៣. តម្រៀប Ranking តាមពិន្ទុសរុបពីធំទៅតូច (សំខាន់បំផុត)
    // ប្រើ values() ដើម្បីឱ្យ index រត់ពី 0, 1, 2 ឡើងវិញ
    $students = $students->sortByDesc('temp_total')->values();

    // ៤. ផ្ដល់ Rank និង Grade បន្ទាប់ពី Sort រួចរាល់
    foreach ($students as $index => $student) {
        $student->rank = $index + 1; // ឥឡូវអ្នកពិន្ទុខ្ពស់ជាងគេនឹងនៅ index 0 ទទួលបាន Rank 1
        
        $ts = $student->temp_total;
        if ($ts >= 85) $student->letterGrade = 'A';
        elseif ($ts >= 80) $student->letterGrade = 'B+';
        elseif ($ts >= 70) $student->letterGrade = 'B';
        elseif ($ts >= 65) $student->letterGrade = 'C+';
        elseif ($ts >= 50) $student->letterGrade = 'C';
        else $student->letterGrade = 'F';
    }

    return view('professor.grades.index', compact('courseOffering', 'students', 'assessments', 'gradebook'));
}



// storeAssessment
// storeGradesForAssessment
    /**
     * Method សម្រាប់បង្ហាញទម្រង់បង្កើត Exam/Assignment
     */
    public function createAssessmentForm($offering_id)
    {
        $courseOffering = CourseOffering::with('course')->findOrFail($offering_id);
        $gradingCategories = GradingCategory::where('course_id', $courseOffering->course->id)->get();
        return view('professor.assessments.create', compact('courseOffering', 'gradingCategories'));
    }

    /**
     * Method សម្រាប់រក្សាទុក Exam/Assignment ថ្មី
     */
public function storeAssessment(Request $request, $offering_id)
{
    // ១. Validation
    $request->validate([
        'assessment_type' => 'required|in:assignment,exam,quiz',
        'title_en' => 'required|string|max:255',
        'title_km' => 'required|string|max:255',
        'max_score' => 'required|numeric|min:1',
        'assessment_date' => 'required|date',
        'grading_category_id' => 'nullable|exists:grading_categories,id',
    ]);

    $courseOffering = CourseOffering::findOrFail($offering_id);
    $type = $request->input('assessment_type');

    // ២. បន្ថែមការឆែក Limit (ជាពិសេសសម្រាប់ Exam)
    if ($type === 'exam') {
        // ប្រសិនបើចំណងជើងមានពាក្យ "Final" ឬ "Mid-term" យើងអាចឆែកការពារកុំឱ្យបង្កើតលើសពី ១
        $existingExam = Exam::where('course_offering_id', $offering_id)
            ->where(function($query) use ($request) {
                $query->where('title_en', 'LIKE', '%' . $request->title_en . '%')
                      ->orWhere('title_km', 'LIKE', '%' . $request->title_km . '%');
            })->first();

// ឆែកមើលក្នុង Controller
            if ($existingExam) {
                // ត្រូវប្រើ 'error' ជា Key ដើម្បីឱ្យស៊ីគ្នាជាមួយកូដ Blade ខាងលើ
                return back()->withInput()->with('error', 'វិញ្ញាសានេះមានរួចហើយ! អ្នកមិនអាចបង្កើតជាន់គ្នាបានទេ។');
            }
    }

    // ៣. បែងចែកការបង្កើតតាមប្រភេទ
    if ($type === 'quiz') {
        \App\Models\Quiz::create([
            'course_offering_id' => $courseOffering->id,
            'title_km' => $request->input('title_km'),
            'title_en' => $request->input('title_en'),
            'max_score' => $request->input('max_score'),
            'quiz_date' => $request->input('assessment_date'), 
            'grading_category_id' => $request->input('grading_category_id'),
        ]);
    } elseif ($type === 'assignment') {
        Assignment::create([
            'course_offering_id' => $courseOffering->id,
            'title_km'           => $request->title_km,
            'title_en'           => $request->title_en,
            'max_score'          => $request->max_score,
            'due_date'           => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } else { 
        Exam::create([
            'course_offering_id' => $courseOffering->id,
            'title_km'           => $request->title_km,
            'title_en'           => $request->title_en,
            'max_score'          => $request->max_score,
            'exam_date'          => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
            'duration_minutes'   => 120, 
        ]);
    }

    return redirect()->route('professor.manage-grades', ['offering_id' => $offering_id])
                     ->with('success', 'ការវាយតម្លៃត្រូវបានបង្កើតដោយជោគជ័យ!');
}
 
// storeGradesForAssessment


public function destroyAssessment(Request $request, $id)
{
    $type = $request->input('assessment_type'); 
    $assessment = null;

    if ($type === 'quiz') {
        $assessment = \App\Models\Quiz::find($id);
        if ($assessment) {
            // ប្រសិនបើបងបញ្ចូល Quiz ក្នុង exam_results ត្រូវលុបវាចេញសិន
            // សន្មតថា quiz ប្រើ id ភ្ជាប់ទៅ exam_id ក្នុងតារាង exam_results
       // កែពី exam_id ទៅ assessment_id (ប្រសិនបើក្នុង DB របស់បងប្រើឈ្មោះនេះ)
            \App\Models\ExamResult::where('assessment_id', $id)->delete();
        }
    } elseif ($type === 'assignment') {
        $assessment = \App\Models\Assignment::find($id);
        if ($assessment) {
            // លុបកិច្ចការដែលសិស្សបានផ្ញើ (Submissions)
            \App\Models\Submission::where('assignment_id', $id)->delete();
        }
    } elseif ($type === 'exam') {
        $assessment = \App\Models\Exam::find($id);
        if ($assessment) {
            // លុបពិន្ទុប្រឡងរបស់សិស្ស
            \App\Models\ExamResult::where('assessment_id', $id)->delete();
        }
    }

    if ($assessment) {
        $assessment->delete();
        return back()->with('success', 'លុបការវាយតម្លៃ និងពិន្ទុដែលពាក់ព័ន្ធបានជោគជ័យ!');
    }

    return back()->with('error', 'រកមិនឃើញទិន្នន័យដែលត្រូវលុប!');
}


public function showGradeEntryForm(Request $request, $assessment_id)
{
    $type = $request->query('type'); // 'assignment', 'exam', ឬ 'quiz'
    $search = $request->query('search'); 
    $assessment = null;

    // ១. ទាញយកទិន្នន័យតាមប្រភេទ Assessment 
    // យើងប្រើ eager load 'examResults' ទាំងអស់ ព្រោះទិន្នន័យពិន្ទុស្ថិតក្នុង Table តែមួយ
    if ($type === 'assignment') {
        $assessment = \App\Models\Assignment::with(['courseOffering.studentCourseEnrollments.student.studentProfile', 'examResults'])
                                ->findOrFail($assessment_id);
    } elseif ($type === 'exam') {
        $assessment = \App\Models\Exam::with(['courseOffering.studentCourseEnrollments.student.studentProfile', 'examResults'])
                          ->findOrFail($assessment_id);
    } elseif ($type === 'quiz') {
        $assessment = \App\Models\Quiz::with(['courseOffering.studentCourseEnrollments.student.studentProfile', 'examResults'])
                          ->findOrFail($assessment_id);
    } else {
        abort(404, 'ប្រភេទការវាយតម្លៃមិនត្រឹមត្រូវ');
    }

    // ២. ទាញបញ្ជីឈ្មោះសិស្សចេញពី Enrollment
    $students = $assessment->courseOffering->studentCourseEnrollments->map(function ($enrollment) {
        return $enrollment->student;
    })->filter();

    // Logic ស្វែងរក (រក្សាទុកនៅដដែល)
    if (!empty($search)) {
        $students = $students->filter(function ($student) use ($search) {
            $searchLower = mb_strtolower($search, 'UTF-8');
            $nameKm = mb_strtolower($student->studentProfile?->full_name_km ?? '', 'UTF-8');
            $nameEn = mb_strtolower($student->studentProfile?->full_name_en ?? '', 'UTF-8');
            $userName = mb_strtolower($student->name ?? '', 'UTF-8');
            $studentId = mb_strtolower($student->student_id_code ?? '', 'UTF-8');

            return str_contains($nameKm, $searchLower) || 
                   str_contains($nameEn, $searchLower) || 
                   str_contains($userName, $searchLower) || 
                   str_contains($studentId, $searchLower);
        });
    }

    $students = $students->sortBy('name');

    // ៣. ទាញពិន្ទុចេញពី examResults មកដាក់ក្នុង Array $scores
    $scores = [];
    
    // Logic ថ្មី៖ ទាញពិន្ទុពី examResults សម្រាប់គ្រប់ប្រភេទ
    // វានឹងដំណើរការទាំងការ Save ផ្ទាល់ដៃ និងការ Import តាម CSV
    foreach ($assessment->examResults as $result) {
        // ឆែកឱ្យច្បាស់ថា assessment_type ក្នុង Database ត្រូវជាមួយ type ដែលកំពុងមើល
        if ($result->assessment_type === $type) {
            $scores[$result->student_user_id] = [
                'score' => $result->score_obtained,
                'notes' => $result->notes,
            ];
        }
    }

    return view('professor.grades.edit', compact('assessment', 'students', 'scores', 'type', 'search'));
}
    /**
     * Method សម្រាប់រក្សាទុកពិន្ទុរបស់និស្សិត
     */
public function storeGradesForAssessment(Request $request, $assessment_id)
{
    $request->validate([
        'grades' => 'required|array',
        'assessment_type' => 'required|in:assignment,exam,quiz',
    ]);

    $type = $request->input('assessment_type');
    $offering_id = null;

    DB::beginTransaction();
    try {
        foreach ($request->input('grades') as $student_id => $gradeData) {
            // ប្រសិនបើមានបញ្ចូលពិន្ទុ (មិនមែន Null)
            if (!isset($gradeData['score']) || $gradeData['score'] === '') {
                continue;
            }

            if ($type === 'assignment') {
                $assessment = Assignment::findOrFail($assessment_id);
                $offering_id = $assessment->course_offering_id;
                
                ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $student_id,
                        'assessment_type' => 'assignment'
                    ],
                    [
                        'score_obtained' => $gradeData['score'], 
                        'notes' => $gradeData['notes'] ?? null, 
                        'recorded_at' => now()
                    ]
                );
            } elseif ($type === 'exam') {
                $assessment = Exam::findOrFail($assessment_id);
                $offering_id = $assessment->course_offering_id;
                
                ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $student_id,
                        'assessment_type' => 'exam'
                    ],
                    [
                        'score_obtained' => $gradeData['score'], 
                        'notes' => $gradeData['notes'] ?? null, 
                        'recorded_at' => now()
                    ]
                );
            } elseif ($type === 'quiz') {
                // ១. ទាញរកព័ត៌មាន Quiz ដើម្បីយក offering_id
                $assessment = Quiz::findOrFail($assessment_id);
                $offering_id = $assessment->course_offering_id;

                // ២. រក្សាទុកក្នុង ExamResult ដូច Exam ដែរ (តែប្តូរ type ជា quiz)
                // ប្រសិនបើប្អូនមានតារាង quiz_results ដាច់ដោយឡែក ត្រូវប្តូរ Model នៅទីនេះ
                ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $student_id,
                        'assessment_type' => 'quiz' // <--- បែងចែកឱ្យច្បាស់ក្នុង DB
                    ],
                    [
                        'score_obtained' => $gradeData['score'], 
                        'notes' => $gradeData['notes'] ?? null, 
                        'recorded_at' => now()
                    ]
                );
            }
        }
        
        DB::commit();
        // បើ $offering_id នៅ Null (ករណីអត់មាន Loop) ត្រូវការពារកុំឱ្យ Error
        $offering_id = $offering_id ?? $request->input('offering_id'); 
        if (!$offering_id) {
    // បើរក offering_id អត់ឃើញពីគ្រប់ច្រក ត្រូវទាញពី Assessment ផ្ទាល់
    $assessment = ($type === 'exam') ? Exam::find($assessment_id) : Assignment::find($assessment_id);
    $offering_id = $assessment->course_offering_id;
}
        return redirect()->route('professor.manage-grades', ['offering_id' => $offering_id])
                         ->with('success', 'រក្សាទុកពិន្ទុបានជោគជ័យ!');
                         
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
        return back()->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
    }
}
// professor.grades.edit
// storeGrades

// c
// reateAssessmentForm
// showGradeEntryForm
public function storeGrades(Request $request, $assessment_id)
{
    // ១. បន្ថែម Validation ដើម្បីសុវត្ថិភាពទិន្នន័យ
    $request->validate([
        'assessment_type' => 'required|in:assignment,exam,quiz',
        'grades' => 'required|array',
    ]);

    $type = $request->input('assessment_type');
    $grades = $request->input('grades');

    // ប្រើ Transaction ដើម្បីធានាថា បើ Error ម្នាក់ គឺមិនរក្សាទុកទាំងអស់ (ការពារទិន្នន័យច្របូកច្របល់)
    \DB::beginTransaction();
    try {
        foreach ($grades as $studentId => $data) {
            // ប្រសិនបើពិន្ទុទទេ (Empty String) យើងអាចរំលងបាន (Optional)
            if ($data['score'] === null || $data['score'] === '') continue;

            if ($type === 'assignment') {
                // រក្សាទុកក្នុងតារាង submissions
                \App\Models\Submission::updateOrCreate(
                    ['assignment_id' => $assessment_id, 'student_user_id' => $studentId],
                    [
                        'grade_received' => $data['score'], // ប្រើតាមឈ្មោះ Column ក្នុង DB ប្អូន
                        'feedback' => $data['notes']       // ប្រើតាមឈ្មោះ Column ក្នុង DB ប្អូន
                    ]
                );
            } else {
                // ប្រភេទ 'exam' ឬ 'quiz' រក្សាទុកក្នុងតារាង exam_results
                \App\Models\ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $studentId,
                        'assessment_type' => $type // ត្រូវដាក់ $type ដើម្បីបែងចែក exam និង quiz
                    ],
                    [
                        'score_obtained' => $data['score'], 
                        'notes' => $data['notes'],
                        'recorded_at' => now()
                    ]
                );
            }
        }
        \DB::commit();
        return back()->with('success', 'រក្សាទុកពិន្ទុបានជោគជ័យ');

    } catch (\Exception $e) {
        \DB::rollBack();
        dd($e->getMessage());
        return back()->with('error', 'មានបញ្ហា៖ ' . $e->getMessage());
    }
}
    /**
     * Manage attendance for a specific course offering.
     */
// destroyAssessment
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:255', 
        ]);

        // Create a new attendance record
        AttendanceRecord::create([
            'course_offering_id' => $validatedData['course_offering_id'],
            'student_user_id' => $validatedData['student_user_id'],
            'date' => $validatedData['date'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'] ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return back()->with('success', 'កំណត់ត្រាវត្តមានត្រូវបានរក្សាទុកដោយជោគជ័យ!');
    }
    public function manageAttendance($offering_id)
    {
        $courseOffering = CourseOffering::with(['course', 'studentCourseEnrollments.student.profile'])->findOrFail($offering_id);
        $attendanceRecords = AttendanceRecord::where('course_offering_id', $offering_id)
                                            ->with('student.profile')
                                            ->orderBy('date', 'desc')
                                            ->paginate(10);

        // Add a Khmer status for display
        $attendanceRecords->each(function ($record) {
            $record->status_km = match($record->status) {
                // 'present' => 'មានវត្តមាន',
                'absent' => 'អវត្តមាន',
                'late' => 'មកយឺត',
                'excused' => 'មានច្បាប់',
                default => 'មិនស្គាល់',
            };
        });

        return view('professor.manage-attendance', compact('courseOffering', 'attendanceRecords'));
    }

    /**
     * Manage assignments for a specific course offering.
     */

    // NEW METHODS FOR QUIZ QUESTIONS - START
    // --------------------------------------------------------------------------




    /**
     * Manage exams for a specific course offering.
     */
    public function manageExams($offering_id)
    {
        $courseOffering = CourseOffering::with('course')->findOrFail($offering_id);
        $exams = Exam::where('course_offering_id', $offering_id)
                     ->orderBy('exam_date', 'asc')
                     ->paginate(10);

        return view('professor.manage-exams', compact('courseOffering', 'exams'));
    }

    /**
     * Store a newly created exam for a specific course offering.
     */
    public function storeExam(Request $request, $offering_id)
    {
        $validatedData = $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_km' => 'nullable|string',
            'description_en' => 'nullable|string',
            'exam_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:10',
            'max_score' => 'required|numeric|min:0',
        ]);
        $courseOffering = CourseOffering::where('id', $offering_id)
                                         ->where('lecturer_user_id', Auth::id())
                                         ->firstOrFail();

        $exam = new Exam($validatedData);
        $exam->course_offering_id = $courseOffering->id;
        $exam->save();

        return redirect()->route('professor.manage-exams', ['offering_id' => $offering_id])
                         ->with('success', 'ការប្រលងត្រូវបានបន្ថែមដោយជោគជ័យ!');
    }

    /**
     * Display all grades managed by the professor across all their courses.
     */
    public function allGrades(Request $request)
    {
        $user = Auth::user();
        $allGrades = new Collection();

        $professorCourseOfferings = CourseOffering::where('lecturer_user_id', $user->id)->pluck('id');

        $assignmentGrades = Submission::whereHas('assignment', function($query) use ($professorCourseOfferings) {
                                            $query->whereIn('course_offering_id', $professorCourseOfferings);
                                        })
                                        ->whereNotNull('grade_received')
                                        ->with('assignment.courseOffering.course', 'student.profile')
                                        ->get()
                                        ->map(function ($submission) {
                                            $assignmentTitle = $submission->assignment->title_km ?? $submission->assignment->title_en ?? 'N/A';

                                            $courseTitle = $submission->assignment->courseOffering->course->title_km ?? 'N/A';
                                            $maxScore = $submission->assignment->max_score ?? 0;

                                            return (object)[
                                                'type' => 'assignment',
                                                'course_title_km' => $courseTitle,
                                                'course_title_en' => $submission->assignment->courseOffering->course->title_en ?? 'N/A',
                                                'assessment_type' => 'កិច្ចការស្រាវជ្រាវ: ' . $assignmentTitle,
                                                'student_name' => $submission->student->profile->full_name_km ?? $submission->student->name,
                                                'score' => $submission->grade_received,
                                                // 'score' => $result->score_obtained,
                                                'max_score' => $maxScore,
                                                'date' => $submission->updated_at,
                                            ];
                                        });
        $allGrades = $allGrades->concat($assignmentGrades);

        // 2. Fetch Exam Grades from their courses
        $examGrades = ExamResult::whereHas('exam', function($query) use ($professorCourseOfferings) {
                                            $query->whereIn('course_offering_id', $professorCourseOfferings);
                                        })
                                        ->whereNotNull('score_obtained')
                                        ->with('exam.courseOffering.course', 'student.profile')
                                        ->get()
                                        ->map(function ($result) {
                                            $examTitle = $result->exam->title_km ?? $result->exam->title_en ?? 'N/A';
                                            $courseTitle = $result->exam->courseOffering->course->title_km ?? 'N/A';
                                            $maxScore = $result->exam->max_score ?? 0;

                                            return (object)[
                                                'type' => 'exam',
                                                'course_title_km' => $courseTitle,
                                                'course_title_en' => $result->exam->courseOffering->course->title_en ?? 'N/A',
                                                'assessment_type' => 'ប្រឡង: ' . $examTitle,
                                                'student_name' => $result->student->profile->full_name_km ?? $result->student->name,
                                                'score' => $result->score_obtained,
                                                'max_score' => $maxScore,
                                                'date' => $result->updated_at,
                                            ];
                                        });
        $allGrades = $allGrades->concat($examGrades);

        // 3. Fetch Quiz Grades (Requires calculation per quiz for students in their courses)
        $quizzesTaught = Quiz::whereIn('course_offering_id', $professorCourseOfferings)
                                ->with(['courseOffering.course', 'quizQuestions.studentQuizResponses.student.profile'])
                                ->get();

        $quizGrades = new Collection();
        foreach ($quizzesTaught as $quiz) {
            $studentResponsesInQuiz = collect();
            foreach ($quiz->quizQuestions as $question) {
                $studentResponsesInQuiz = $studentResponsesInQuiz->concat($question->studentQuizResponses);
            }

            $studentResponsesInQuiz->groupBy('student_user_id')->each(function ($responses, $studentUserId) use ($quiz, &$quizGrades) {
                $student = User::find($studentUserId);
                if (!$student) return;

                $correctAnswers = 0;
                $totalQuestions = $quiz->quizQuestions->count();
                $totalPossibleScore = $quiz->max_score ?? ($totalQuestions > 0 ? $quiz->quizQuestions->sum('points') : 0);
                if ($totalPossibleScore === 0 && $totalQuestions > 0) {
                    $totalPossibleScore = $totalQuestions * 10; 
                }

                foreach ($responses as $response) {
                    if ($response->is_correct) {
                        $correctAnswers += $response->quizQuestion->points ?? 0;
                    }
                }

                $score = $correctAnswers;

                $quizGrades->push((object)[
                    'type' => 'quiz',
                    'course_title_km' => $quiz->courseOffering->course->title_km ?? 'N/A',
                    'course_title_en' => $quiz->courseOffering->course->title_en ?? 'N/A',
                    'assessment_type' => 'Quiz: ' . ($quiz->title_km ?? $quiz->title_en ?? 'N/A'),
                    'student_name' => $student->profile->full_name_km ?? $student->name,
                    'score' => round($score, 2),
                    'max_score' => $totalPossibleScore,
                    'date' => $responses->max('updated_at'), 
                ]);
            });
        }
        $allGrades = $allGrades->concat($quizGrades);


        $allGrades = $allGrades->sortByDesc('date');

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage('gradesPage');
        $currentItems = $allGrades->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
        $grades = new LengthAwarePaginator($currentItems, $allGrades->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'pageName' => 'gradesPage',
        ]);
    $studentAttendanceScores = collect($courseOffering->studentEnrollments)->mapWithKeys(function ($enrollment) {
    $attendanceRecords = \App\Models\AttendanceRecord::where('student_enrollment_id', $enrollment->id)->get();
    $totalAttended = $attendanceRecords->where('status', 'មានវត្តមាន')->count();
    $totalSessions = 15; 
    $score = ($totalAttended / $totalSessions) * 10;

    return [$enrollment->id => min($score, 10)]; 
    });
    $attendanceAssessment = (object) [
        'id' => 'attendance-grade',
        'title_km' => 'វត្តមាន',
        'title_en' => 'Attendance',
        'max_score' => 10,
        'assessment_type' => 'វត្តមាន',
        'course_offering_id' => $courseOffering->id,
        'is_attendance' => true,
    ];

    $assessments->prepend($attendanceAssessment);

        return view('professor.all-grades', compact('grades','courseOffering', 'students', 'assessments', 'gradebook'));
    }

    public function showGrades(CourseOffering $courseOffering)
    {

        $students = $courseOffering->students()->with([
            'userProfile',
            'submissions' => function ($query) use ($courseOffering) {
                $query->whereIn('assignment_id', $courseOffering->assignments->pluck('id'));
            },
            'examResults' => function ($query) use ($courseOffering) {
                $query->whereIn('exam_id', $courseOffering->exams->pluck('id'));
            },
            'studentQuizResponses.quizQuestion.quiz', // Added for Quiz scores
            'attendanceRecords' => function ($query) use ($courseOffering) {
                $query->where('course_offering_id', $courseOffering->id);
            }
        ])->get();

        $totalSessions = Schedule::where('course_offering_id', $courseOffering->id)->count();

        foreach ($students as $student) {
            $presentCount = $student->attendanceRecords->where('status', 'present')->count();
            $attendancePercentage = ($totalSessions > 0) ? ($presentCount / $totalSessions) * 100 : 0;
            $attendanceScore = ($attendancePercentage / 100) * 10;
            $student->attendance_score = round($attendanceScore, 2);
        }

        $assessments = $courseOffering->assessments()->get();

        return view('professor.grades.index', compact(
            'courseOffering',
            'students',
            'assessments'
        ));
    }

    
    public function allAssignments(Request $request)
    {
        $user = Auth::user();
        $assignments = Assignment::whereHas('courseOffering', function ($query) use ($user) {
                                            $query->where('lecturer_user_id', $user->id);
                                        })
                                        ->with('courseOffering.course')
                                        ->orderBy('due_date', 'desc')
                                        ->paginate(10, ['*'], 'assignmentsPage');

        return view('professor.all-assignments', compact('assignments'));
    }

    /**
     * Display all exams managed by the professor across all their courses.
     */
    public function allExams(Request $request)
    {
        $user = Auth::user();
        $exams = Exam::whereHas('courseOffering', function ($query) use ($user) {
                                $query->where('lecturer_user_id', $user->id);
                            })
                            ->with('courseOffering.course')
                            ->orderBy('exam_date', 'desc')
                            ->paginate(10, ['*'], 'examsPage');

        return view('professor.all-exams', compact('exams'));
    }

    /**
     * Display all attendance records managed by the professor across all their courses.
     */
    public function allAttendance(Request $request)
    {
        $user = Auth::user();
        $attendances = AttendanceRecord::whereHas('courseOffering', function ($query) use ($user) {
                                            $query->where('lecturer_user_id', $user->id);
                                        })
                                        ->with('courseOffering.course', 'student.profile')
                                        ->orderBy('date', 'desc')
                                        ->paginate(10, ['*'], 'attendancePage');

        $attendances->each(function ($record) {
            $record->status_km = match($record->status) {
                'present' => 'មានវត្តមាន',
                'absent' => 'អវត្តមាន',
                'late' => 'មកយឺត',
                'excused' => 'មានច្បាប់',
                default => 'មិនស្គាល់',
            };
        });

        return view('professor.manage-attendance', compact('attendances'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_user_id' => [
    'required',
    Rule::exists('student_course_enrollments', 'student_user_id')
        ->where('course_offering_id', $request->course_offering_id)
],
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'note' => 'nullable|string|max:255',
        ], [
            'student_user_id.required' => 'អត្តសញ្ញាណសិស្សតម្រូវឱ្យបញ្ចូល។',
            'student_user_id.exists' => 'អត្តសញ្ញាណសិស្សមិនមាននៅក្នុងប្រព័ន្ធទេ។',
            'course_offering_id.required' => 'អត្តសញ្ញាណវគ្គសិក្សាតម្រូវឱ្យបញ្ចូល។',
            'date.required' => 'កាលបរិច្ឆេទតម្រូវឱ្យបញ្ចូល។',
            'status.required' => 'ស្ថានភាពវត្តមានតម្រូវឱ្យបញ្ចូល។',
        ]);

        $attendance = AttendanceRecord::create([
            'course_offering_id' => $request->input('course_offering_id'),
            'student_user_id' => $request->input('student_user_id'), // Ensure this is mapped correctly
            'date' => $request->input('date'),
            'status' => $request->input('status'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('professor.manage-attendance', ['offering_id' => $request->input('course_offering_id')])
                 ->with('success', __('កំណត់ត្រាវត្តមានត្រូវបានបន្ថែមដោយជោគជ័យ។'));

    }

    /**
     * Update the specified attendance record in storage.
     */
    public function updateAttendance(Request $request, AttendanceRecord $attendance)
    {
        $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_user_id' => 'required|exists:users,id', // Changed from 'student_id' to 'student_user_id'
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'note' => 'nullable|string|max:255',
        ], [
            'student_user_id.required' => 'អត្តសញ្ញាណសិស្សតម្រូវឱ្យបញ្ចូល។',
            'student_user_id.exists' => 'អត្តសញ្ញាណសិស្សមិនមាននៅក្នុងប្រព័ន្ធទេ។',
            'course_offering_id.required' => 'អត្តសញ្ញាណវគ្គសិក្សាតម្រូវឱ្យបញ្ចូល។',
            'date.required' => 'កាលបរិច្ឆេទតម្រូវឱ្យបញ្ចូល។',
            'status.required' => 'ស្ថានភាពវត្តមានតម្រូវឱ្យបញ្ចូល។',
        ]);

        $attendance->update([
            'course_offering_id' => $request->input('course_offering_id'),
            'student_user_id' => $request->input('student_user_id'),
            'date' => $request->input('date'),
            'status' => $request->input('status'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('professor.manage-attendance', ['offering_id' => $attendance->course_offering_id])
                 ->with('success', __('កំណត់ត្រាវត្តមានត្រូវបានកែប្រែដោយជោគជ័យ។'));

    }

    /**
     * Remove the specified attendance record from storage.
     */
    public function destroyAttendance(AttendanceRecord $attendance)
    {
        $attendance->delete();

        return redirect()->route('professor.manage-attendance', ['offering_id' => $attendance->course_offering_id])
                 ->with('success', __('កំណត់ត្រាវត្តមានត្រូវបានលុបដោយជោគជ័យ។'));

    }


// professor.grades.store

    public function editAssignment($offering_id, Assignment $assignment)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        return view('professor.assignments.edit', compact('assignment', 'courseOffering'));
    }

/**
 * NEW: Update the specified assignment in storage.
 */
    public function updateAssignment(Request $request, $offering_id, Assignment $assignment)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);

        $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_km' => 'nullable|string',
            'description_en' => 'nullable|string',
            'due_date' => 'required|date',
            'max_score' => 'required|numeric|min:0',
        ]);

        $assignment->update($request->all());

        return redirect()->route('professor.manage-assignments', ['offering_id' => $offering_id])
                        ->with('success', 'កិច្ចការផ្ទះត្រូវបានកែសម្រួលដោយជោគជ័យ!');
    }

/**
 * NEW: Remove the specified assignment from storage.
 */
    public function destroyAssignment($offering_id, Assignment $assignment)
    {
        
        $assignment->submissions()->delete();

        $assignment->delete();

        return redirect()->route('professor.manage-assignments', ['offering_id' => $offering_id])
                        ->with('success', 'កិច្ចការផ្ទះត្រូវបានលុបដោយជោគជ័យ!');
    }

    public function editExam($offering_id, Exam $exam)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        return view('professor.exams.edit', compact('exam', 'courseOffering'));
    }

/**
 * Update the specified exam in storage.
 */
    public function updateExam(Request $request, $offering_id, Exam $exam)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);

        $validatedData = $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_km' => 'nullable|string',
            'description_en' => 'nullable|string',
            'exam_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:10',
            'max_score' => 'required|numeric|min:0',
        ]);

        $exam->update($validatedData);

        return redirect()->route('professor.manage-exams', ['offering_id' => $offering_id])
                        ->with('success', 'ការប្រលងត្រូវបានកែសម្រួលដោយជោគជ័យ!');
    }

/**
 * Remove the specified exam from storage.
 */
    public function destroyExam($offering_id, Exam $exam)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        $exam->delete();

        return redirect()->route('professor.manage-exams', ['offering_id' => $offering_id])
                        ->with('success', 'ការប្រលងត្រូវបានលុបដោយជោគជ័យ!');
    }


  /**
     * API to get course offerings with associated students for modals.
     */
 public function getStudentsInCourseOffering($offering_id)
{
    $user = Auth::user();

    // ១. បន្ថែម Relationship 'studentProgramEnrollments.program' ដើម្បីបង្ហាញព័ត៌មាន Program និង Generation
    $courseOffering = CourseOffering::where('id', $offering_id)
        ->where('lecturer_user_id', $user->id)
        ->with([
            'course', 
            'studentCourseEnrollments.student.studentProfile',
            'studentCourseEnrollments.student.studentProgramEnrollments.program' //
        ])
        ->firstOrFail();

    // ២. រៀបចំបញ្ជីឈ្មោះនិស្សិត និងគណនាស្ថិតិ
    $stats = [
        'total' => $courseOffering->studentCourseEnrollments->count(),
        'male' => 0,
        'female' => 0,
        'leaders' => 0,
    ];

    $students = $courseOffering->studentCourseEnrollments->map(function ($enrollment) use (&$stats) {
        $student = $enrollment->student;
        
        // ឆែកភេទ (Gender) ពី Profile
        $gender = strtoupper($student->studentProfile->gender ?? '');
        if (in_array($gender, ['M', 'MALE', 'ប្រុស'])) {
            $stats['male']++;
        } elseif (in_array($gender, ['F', 'FEMALE', 'ស្រី'])) {
            $stats['female']++;
        }

        // ឆែកប្រធានថ្នាក់
        if ($enrollment->is_class_leader) {
            $stats['leaders']++;
        }

        return $student; 
    });

    // ៣. រៀបចំ Pagination
    $perPage = 10;
    $currentPage = LengthAwarePaginator::resolveCurrentPage('studentsPage');
    $currentItems = $students->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
    
    $paginatedStudents = new LengthAwarePaginator($currentItems, $students->count(), $perPage, $currentPage, [
        'path' => request()->url(),
        'pageName' => 'studentsPage',
    ]);

    return view('professor.students.index', compact('courseOffering', 'paginatedStudents', 'stats'));
}
// getStudentsInCourseOffering
    /**
     * Display an 'all-in-one' view for professors,
     * combining various data points from all their courses.
     */
    public function allDataView(Request $request)
    {
        $user = Auth::user();

        $allCourseOfferings = $this->viewAllCourseOfferings()->getData()->courseOfferings;
        $allAssignments = $this->allAssignments($request)->getData()->assignments;
        $allExams = $this->allExams($request)->getData()->exams;
        $allQuizzes = $this->allQuizzes($request)->getData()->quizzes;
        $allAttendance = $this->allAttendance($request)->getData()->attendances;
        $allGrades = $this->allGrades($request)->getData()->grades; // This already handles custom pagination

        $allDepartments = $this->viewDepartments()->getData()->departments;
        $allPrograms = $this->viewPrograms()->getData()->programs;
        $allCourses = $this->viewCourses()->getData()->courses;

        return view('professor.all-data-view', compact(
            'allCourseOfferings',
            'allAssignments',
            'allExams',
            'allQuizzes',
            'allAttendance',
            'allGrades',
            'allDepartments',
            'allPrograms',
            'allCourses'
        ));
    }

    public function showStudentProfile(CourseOffering $courseOffering, User $student)
    {
        if (!$student->isStudent()) {
            abort(404); 
        }

        $student->loadMissing('studentProfile', 'program'); 

        return view('professor.students.show_profile', compact('courseOffering', 'student')); 
    }


    public function showStudentsInCourse(CourseOffering $courseOffering)
    {
        if ($courseOffering->lecturer_user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

       
        $studentIds = $courseOffering->studentCourseEnrollments()->pluck('student_user_id');
        $students = User::whereIn('id', $studentIds)
                        ->with('studentProfile') 
                        ->orderBy('name', 'asc')
                        ->paginate(10); 

        return view('professor.students.index', compact('courseOffering', 'students'));
    }

    public function mySchedule()
    {
        $user = Auth::user();
        if ($user->role !== 'professor') {
            return redirect()->route('dashboard')->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើមុខងារនេះទេ។');
        }

        $courseOfferings = CourseOffering::where('lecturer_user_id', $user->id)
            ->with(['course', 'schedules'])
            ->get();
        return view('professor.my-schedule', compact('user', 'courseOfferings'));
    }



    public function createNotificationForm()
    {
        $user = Auth::user();
        $courseOfferings = CourseOffering::where('lecturer_user_id', $user->id)->with('course')->get();

        $allStudentsByCourse = [];
        foreach ($courseOfferings as $offering) {
            $students = StudentCourseEnrollment::where('course_offering_id', $offering->id)
                ->with('student.studentProfile')
                ->get()
                ->map(function($enrollment) {
                    return [
                        'id' => $enrollment->student->id,
                        'name' => $enrollment->student->studentProfile->full_name_km ?? $enrollment->student->name,
                        'student_id_code' => $enrollment->student->student_id_code,
                    ];
                });
            $allStudentsByCourse[$offering->id] = $students;
        }

        return view('professor.notifications.create', compact('courseOfferings', 'allStudentsByCourse'));
    }
    public function getStudentsForCourseOffering(CourseOffering $courseOffering)
    {
        
        $students = StudentCourseEnrollment::where('course_offering_id', $courseOffering->id)
            ->with('student.studentProfile')
            ->get()
            ->map(function($enrollment) {
                return [
                    'id' => $enrollment->student->id,
                    'name' => $enrollment->student->studentProfile->full_name_km ?? $enrollment->student->name,
                ];
            });
        return response()->json($students);
    }

  public function notificationsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'recipient_ids' => 'required|array|min:1',
            'recipient_ids.*' => 'exists:users,id',
            'message' => 'required|string|max:2000',
        ], [
            'title.required' => 'សូមបញ្ចូលចំណងជើង។',
            'recipient_ids.required' => 'សូមជ្រើសរើសយ៉ាងហោចណាស់និស្សិតម្នាក់។',
            'message.required' => 'សារមិនអាចទទេបានទេ។',
        ]);

        $sender = Auth::user();
        $recipientIds = $request->input('recipient_ids');
        $recipients = User::whereIn('id', $recipientIds)->get();

        if ($recipients->isEmpty()) {
            return back()->with('error', 'No valid recipients found.');
        }

        $batchUuid = Str::uuid()->toString();

        foreach ($recipients as $recipient) {
            $notificationData = [
                'from_user_id'   => $sender->id,
                'from_user_name' => $sender->name,
                'title'          => $request->title,
                'message'        => $request->message,
                'batch_uuid'     => $batchUuid,
                'recipient_ids'  => $recipientIds,
            ];

            $recipient->notify(new GeneralNotification($notificationData));
        }

        Session::flash('success', 'ការជូនដំណឹងត្រូវបានផ្ញើដោយជោគជ័យ!');
        return redirect()->route('professor.notifications.index');
    }

    public function notificationsIndex()
    {
        $sentNotifications = Notification::where('data->from_user_id', Auth::id())
            ->select('notifications.*')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn($item) => $item->data['batch_uuid'] ?? $item->id)
            ->map(fn($group) => $group->first()); // lấy notification đầu tiênក្នុង batch

        return view('professor.notifications.index', [
            'sentNotifications' => $sentNotifications
        ]);
    }


    public function notificationsDestroy($notification_id)
    {
        $notification = DatabaseNotification::findOrFail($notification_id);

        if (($notification->data['from_user_id'] ?? null) != Auth::id()) {
            Session::flash('error', 'អ្នកមិនមានសិទ្ធិលុបការជូនដំណឹងនេះទេ។');
            return redirect()->route('professor.notifications.index');
        }

        $batchUuid = $notification->data['batch_uuid'] ?? null;

        DB::transaction(function () use ($batchUuid, $notification) {
            if ($batchUuid) {
                DatabaseNotification::where('data->batch_uuid', $batchUuid)->delete();
            } else {
                $notification->delete();
            }
        });

        Session::flash('success', 'ការជូនដំណឹងត្រូវបានលុបដោយជោគជ័យ!');
        return redirect()->route('professor.notifications.index');
    }
    public function manageAssignments($offering_id)
    {
        $courseOffering = CourseOffering::with('course')->findOrFail($offering_id);
        $assignments = Assignment::where('course_offering_id', $offering_id)
                                    ->orderBy('due_date', 'asc')
                                    ->paginate(10);
        return view('professor.manage-assignments', compact('courseOffering', 'assignments'));
    }

    public function createAssessment($offering_id)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        return view('professor.assignments.create', compact('courseOffering'));
    }


    public function storeAssignment(Request $request, $offering_id)
    {
        $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'due_date' => 'required|date',
            'max_score' => 'required|numeric|min:0',
            // 'grading_category_id' => 'required|exists:grading_categories,id',
        ]);

        $courseOffering = CourseOffering::where('id', $offering_id)
                                        ->where('lecturer_user_id', Auth::id())
                                        ->firstOrFail();

        $assignmentData = $request->all();
        $assignmentData['course_offering_id'] = $courseOffering->id;

        Assignment::create($assignmentData);

        return redirect()->route('professor.manage-assignments', ['offering_id' => $offering_id])
                        ->with('success', 'កិច្ចការផ្ទះត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function manageGradingCategories(Course $course)
    {
        $course->load('gradingCategories'); 

        return view('professor.grading-categories.index', compact('course'));
    }

    /**
     * Store a new grading category for a specific course.
     */
    public function storeGradingCategory(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name_km' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'weight_percentage' => 'required|numeric|min:1|max:100',
        ]);

        // Validate that the total weight does not exceed 100%
        $currentWeight = $course->gradingCategories()->sum('weight_percentage');
        if (($currentWeight + $validated['weight_percentage']) > 100) {
            return back()->with('error', 'ភាគរយសរុបនៃប្រភេទពិន្ទុមិនអាចលើសពី 100% បានទេ។');
        }

        $course->gradingCategories()->create($validated);

        return redirect()->back()->with('success', 'ប្រភេទពិន្ទុត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    /**
     * Delete a grading category.
     */
    public function destroyGradingCategory(GradingCategory $category)
    {
        // Security Check can be added here
        
        $category->delete();

        return redirect()->back()->with('success', 'ប្រភេទពិន្ទុត្រូវបានលុបដោយជោគជ័យ!');
    }




 public function showProfile()
    {
        $user = Auth::user();
        $userProfile = $user->userProfile;
        if (!$userProfile) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
        }

        return view('professor.profile.show', compact('user', 'userProfile'));
    }

    /**
     * Show the form for editing the professor's profile.
     */
    public function editProfile()
    {
        $user = Auth::user();
        $userProfile = $user->userProfile;
        if (!$userProfile) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
        }

        return view('professor.profile.edit', compact('user', 'userProfile'));
    }

    /**
     * Update the professor's profile in storage.
     */
// public function updateProfile(Request $request)
// {
//     $user = Auth::user();

//     $validator = Validator::make($request->all(), [
//         'full_name_km' => 'required|string|max:255',
//         'full_name_en' => 'nullable|string|max:255',
//         'gender' => 'required|in:male,female',
//         'date_of_birth' => 'nullable|date',
//         'phone_number' => 'nullable|string|max:20',
//         'telegram_user' => 'nullable|string|max:255', // បន្ថែមចំណុចនេះ
//         'address' => 'nullable|string|max:255',
//         'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//     ]);

//     if ($validator->fails()) {
//         return redirect()->back()->withErrors($validator)->withInput();
//     }

//     $userProfile = $user->userProfile()->firstOrNew(['user_id' => $user->id]);

//     // ការគ្រប់គ្រងរូបភាព Profile
//     if ($request->hasFile('profile_picture')) {
//         if ($userProfile->profile_picture_url) {
//             Storage::disk('public')->delete($userProfile->profile_picture_url);
//         }
//         $path = $request->file('profile_picture')->store('profile_pictures', 'public');
//         $userProfile->profile_picture_url = $path;
//     }

//     // រក្សាទុកទិន្នន័យទាំងអស់ រួមទាំង telegram_user ថ្មី
//     $userProfile->fill($validator->validated());
//     $userProfile->save();

//     Session::flash('success', 'ប្រវត្តិរូបរបស់អ្នកត្រូវបានកែប្រែដោយជោគជ័យ!');

//     return redirect()->route('professor.profile.show');
// }



public function updateProfile(Request $request)
{
    $user = Auth::user();

    $validator = Validator::make($request->all(), [
        'full_name_km' => 'required|string|max:255',
        'full_name_en' => 'nullable|string|max:255',
        'gender' => 'required|in:male,female',
        'date_of_birth' => 'nullable|date',
        'phone_number' => 'nullable|string|max:20',
        'telegram_user' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $userProfile = $user->userProfile()->firstOrNew(['user_id' => $user->id]);

    // ✅ Upload Profile Picture (Safe)
    if ($request->hasFile('profile_picture')) {

        // 🛑 Guard: Cloudinary មិន config
        if (!config('cloudinary.cloud_name')) {
            return back()->withErrors([
                'profile_picture' => 'Cloudinary មិនទាន់បានកំណត់ (ENV missing)'
            ]);
        }

        try {
            $result = Cloudinary::upload(
                $request->file('profile_picture')->getRealPath(),
                [
                    'folder' => 'profile_pictures',
                    'resource_type' => 'image',
                ]
            );

            $userProfile->profile_picture_url = $result->getSecurePath();

        } catch (\Exception $e) {
            Log::error('Cloudinary Upload Error: '.$e->getMessage());

            return back()->withErrors([
                'profile_picture' => 'Upload រូបភាពមិនបាន សូមព្យាយាមម្តងទៀត'
            ]);
        }
    }

    // រក្សាទុកព័ត៌មានផ្សេងៗ
    $userProfile->fill($validator->validated());
    $userProfile->save();

    return redirect()
        ->route('professor.profile.show')
        ->with('success', 'ប្រវត្តិរូបរបស់អ្នកត្រូវបានកែប្រែដោយជោគជ័យ!');
}


public function toggleClassLeader($offeringId, $studentUserId)
{
    // ១. ស្វែងរក record ក្នុង table student_course_enrollments
    $enrollment = DB::table('student_course_enrollments')
        ->where('course_offering_id', $offeringId)
        ->where('student_user_id', $studentUserId)
        ->first();

    if (!$enrollment) {
        return back()->with('error', 'រកមិនឃើញទិន្នន័យនិស្សិតក្នុង Database ទេ!');
    }

    // ២. ប្តូរតម្លៃ (Toggle) បើ 0 ទៅ 1, បើ 1 ទៅ 0
    $newStatus = $enrollment->is_class_leader ? 0 : 1;

    // ៣. Update ចូល Database ផ្ទាល់
    DB::table('student_course_enrollments')
        ->where('course_offering_id', $offeringId)
        ->where('student_user_id', $studentUserId)
        ->update(['is_class_leader' => $newStatus]);

    return back()->with('success', 'ស្ថានភាពប្រធានថ្នាក់ត្រូវបានផ្លាស់ប្តូរ!');
}







public function assignLeader($courseOfferingId, $studentId)
{
    // ស្វែងរកមុខវិជ្ជា
    $courseOffering = CourseOffering::findOrFail($courseOfferingId);

    // ដកតំណែងប្រធានថ្នាក់ចាស់ចេញសិន (ប្រសិនបើចង់ឱ្យមានប្រធានថ្នាក់តែម្នាក់)
    // ប្រសិនបើអ្នកចង់ឱ្យមានប្រធានថ្នាក់ច្រើននាក់ អ្នកអាចយកផ្នែកនេះចេញ
    DB::table('student_course_enrollments')
        ->where('course_offering_id', $courseOfferingId)
        ->update(['is_class_leader' => false]);

    // ឆែកមើលស្ថានភាពបច្ចុប្បន្នរបស់និស្សិត
    $enrollment = DB::table('student_course_enrollments')
        ->where('course_offering_id', $courseOfferingId)
        ->where('student_id', $studentId)
        ->first();

    // ប្តូរស្ថានភាព (Toggle)
    $newStatus = !($enrollment->is_class_leader ?? false);

    DB::table('student_course_enrollments')
        ->where('course_offering_id', $courseOfferingId)
        ->where('student_id', $studentId)
        ->update(['is_class_leader' => $newStatus]);

    $message = $newStatus ? 'បានតែងតាំងប្រធានថ្នាក់ជោគជ័យ!' : 'បានដកតំណែងប្រធានថ្នាក់ជោគជ័យ!';

    return redirect()->back()->with('success', $message);
}

public function attendanceIndex($courseOfferingId)
{
    $courseOffering = CourseOffering::with('students.studentProfile')->findOrFail($courseOfferingId);
    $students = $courseOffering->students; // យកបញ្ជីនិស្សិតក្នុងថ្នាក់នោះ
    $today = now()->format('Y-m-d');

    return view('professor.attendance.index', compact('courseOffering', 'students', 'today'));
}

public function attendanceStore(Request $request, $courseOfferingId)
{
    $request->validate([
        'attendance_date' => 'required|date',
        'attendance' => 'required|array',
    ]);

    foreach ($request->attendance as $studentId => $status) {
        DB::table('attendances')->updateOrInsert(
            [
                'course_offering_id' => $courseOfferingId,
                'user_id' => $studentId,
                'attendance_date' => $request->attendance_date,
            ],
            [
                'status' => $status,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    return redirect()->back()->with('success', 'បានរក្សាទុកវត្តមានដោយជោគជ័យ!');
}

// AttendanceRecord
// export

public function attendanceReport($courseOfferingId)
{
    $courseOffering = CourseOffering::findOrFail($courseOfferingId);

    $students = User::whereHas('enrolledCourses', function($query) use ($courseOfferingId) {
        $query->where('course_offering_id', $courseOfferingId);
    })
    ->withCount([
        // ប្រើឈ្មោះ 'attendances' ឱ្យដូចក្នុង User.php
        'attendances as present_count' => function ($query) use ($courseOfferingId) {
            $query->where('course_offering_id', $courseOfferingId)
                  ->where('status', 'present');
        },
        'attendances as absent_count' => function ($query) use ($courseOfferingId) {
            $query->where('course_offering_id', $courseOfferingId)
                  ->where('status', 'absent');
        },
        'attendances as permission_count' => function ($query) use ($courseOfferingId) {
            $query->where('course_offering_id', $courseOfferingId)
                  ->where('status', 'permission');
        },
        'attendances as late_count' => function ($query) use ($courseOfferingId) {
            $query->where('course_offering_id', $courseOfferingId)
                  ->where('status', 'late');
        }
    ])
    ->get();

    return view('professor.attendance.report', compact('courseOffering', 'students'));
}





// សម្រាប់បង្ហាញទំព័រ Edit
public function assessmentEdit($id, $type)
{
    if ($type === 'assignment') {
        $assessment = \App\Models\Assignment::findOrFail($id);
    } elseif ($type === 'quiz') {
        $assessment = \App\Models\Quiz::findOrFail($id);
    } elseif ($type === 'exam') {
        $assessment = \App\Models\Exam::findOrFail($id);
    } else {
        abort(404);
    }

    $courseOffering = \App\Models\CourseOffering::findOrFail(
        $assessment->course_offering_id
    );

    $gradingCategories = \App\Models\GradingCategory::where(
        'course_id', $courseOffering->course_id
    )->get();

    return view(
        'professor.assessments.edit',
        compact('assessment', 'type', 'courseOffering', 'gradingCategories')
    );
}

public function update(Request $request, $id, $type)
{
    $request->validate([
        'title_km' => 'required|string|max:255',
        'max_score' => 'required|numeric|min:1',
        'assessment_date' => 'required|date',
        'grading_category_id' => 'required'
    ]);

    if ($type === 'assignment') {
        $model = \App\Models\Assignment::findOrFail($id);
        $model->update([
            'title_km' => $request->title_km,
            'max_score' => $request->max_score,
            'due_date' => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } elseif ($type === 'quiz') {
        $model = \App\Models\Quiz::findOrFail($id);
        $model->update([
            'title_km' => $request->title_km,
            'max_score' => $request->max_score,
            'quiz_date' => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } elseif ($type === 'exam') {
        $model = \App\Models\Exam::findOrFail($id);
        $model->update([
            'title_km' => $request->title_km,
            'max_score' => $request->max_score,
            'exam_date' => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } else {
        abort(404);
    }

    return redirect()
        ->route('professor.manage-grades', [
            'offering_id' => $model->course_offering_id
        ])
        ->with('success', 'កែសម្រួលបានជោគជ័យ!');
}


public function showGradebook($offering_id)
    {
        // ១. ទាញយកព័ត៌មានមុខវិជ្ជា (Course Offering)
        $courseOffering = CourseOffering::with('course')->findOrFail($offering_id);

        // ២. ទាញបញ្ជីឈ្មោះសិស្ស ព្រមជាមួយ "វត្តមាន" ក្នុងមុខវិជ្ជានេះ
        $students = User::where('role', 'student')
            ->whereHas('courseOfferings', function($q) use ($offering_id) {
                $q->where('course_offering_id', $offering_id);
            })
            ->with(['attendanceRecords' => function($q) use ($offering_id) {
                $q->where('course_offering_id', $offering_id);
            }])
            ->get();

        // ៣. ទាញរាល់ការវាយតម្លៃទាំងអស់ (Assessments)
        $assignments = Assignment::where('course_offering_id', $offering_id)->get();
        $quizzes = Quiz::where('course_offering_id', $offering_id)->get();
        $exams = Exam::where('course_offering_id', $offering_id)->get();

        // បញ្ចូលគ្នាជា Collection តែមួយសម្រាប់បង្ហាញក្នុង Header តារាង
        $assessments = $assignments->concat($quizzes)->concat($exams);

        // ៤. រៀបចំទិន្នន័យពិន្ទុដាក់ក្នុង Array ដើម្បីងាយស្រួលទាញក្នុង Blade
        $gradebook = [];
        foreach ($students as $student) {
            foreach ($assignments as $a) {
                // ឧបមាថាអ្នកមាន Model AssignmentSubmission សម្រាប់រក្សាពិន្ទុ
                $student->attendance_score = $this->getAttendanceScore($student->id, $offering_id);
                $submission = $a->submissions()->where('user_id', $student->id)->first();
                $gradebook[$student->id]['assignment_' . $a->id] = $submission ? $submission->score : 0;
            }
            // ធ្វើដូចគ្នាសម្រាប់ Quiz និង Exam...
        }

        return view('professor.gradebook', compact('courseOffering', 'students', 'assessments', 'gradebook'));
    }
// totalAttendanceWeight
    public function getAttendanceScore($studentId, $courseOfferingId)
{
    // ១. រាប់ចំនួនអវត្តមានសរុប (Absents) របស់និស្សិតក្នុងមុខវិជ្ជានោះ
    $absentCount = \App\Models\Attendance::where('student_user_id', $studentId)
        ->where('course_offering_id', $courseOfferingId)
        ->where('status', 'absent') // យកតែអ្នកអវត្តមាន
        ->count();

    // ២. គណនាពិន្ទុ (ឈប់ ២ដង ដក ១ពិន្ទុ)
    $maxScore = 10;
    $deduction = floor($absentCount / 2); // ប្រើ floor ដើម្បីយកចំនួនគត់
    $finalScore = $maxScore - $deduction;

    // ការពារកុំឱ្យពិន្ទុធ្លាក់ក្រោម ០
    return $finalScore < 0 ? 0 : $finalScore;
}








public function exportStudentsDocx($offering_id)
{
    $user = Auth::user();

    $courseOffering = CourseOffering::where('id', $offering_id)
        ->where('lecturer_user_id', $user->id)
        ->with([
            'course', 
            'studentCourseEnrollments.student.studentProfile',
            'studentCourseEnrollments.student.studentProgramEnrollments.program'
        ])->firstOrFail();

    $students = $courseOffering->studentCourseEnrollments;

    // រៀបចំ HTML សម្រាប់ Word
    $html = view('professor.students.export_word', compact('courseOffering', 'students'))->render();

    $fileName = 'Student_List_' . time() . '.doc';

    return response($html)
        ->header('Content-Type', 'application/msword')
        ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
}

// ឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲ
public function exportGradebookDocx($offering_id)
{
    $courseOffering = \App\Models\CourseOffering::with([
        'course',
        'studentCourseEnrollments.student.studentProfile' 
    ])->findOrFail($offering_id);

    // ១. ទាញយក Assignments, Exams, Quizzes
    $assignments = \App\Models\Assignment::where('course_offering_id', $offering_id)->get();
    $exams = \App\Models\Exam::where('course_offering_id', $offering_id)->get();
    $quizzes = \App\Models\Quiz::where('course_offering_id', $offering_id)->get();

    $assessments = collect($assignments)->concat($exams)->concat($quizzes)->sortBy('created_at');

    // ទាញយកពិន្ទុទាំងអស់មកទុកក្នុង Memory តែម្តង (ដើម្បីល្បឿនលឿន)
    $studentIds = $courseOffering->studentCourseEnrollments->pluck('student_user_id');
    $allResults = \App\Models\ExamResult::whereIn('student_user_id', $studentIds)
        ->whereIn('assessment_id', $assessments->pluck('id'))
        ->get();

    // ២. រៀបចំ Gradebook និងគណនាពិន្ទុ
    $gradebook = [];
    $students = $courseOffering->studentCourseEnrollments->map(function ($enrollment) use ($assessments, $allResults, &$gradebook, $offering_id) {
        $student = $enrollment->student;
        
        // ប្រើ Method ដែលអ្នកមានស្រាប់សម្រាប់ពិន្ទុវត្តមាន
        $attendanceScore = $student->getAttendanceScoreByCourse($offering_id);
        $totalScore = $attendanceScore;

        foreach ($assessments as $assessment) {
            // កំណត់ប្រភេទឱ្យត្រូវតាម Database (assignment, quiz, exam)
            $type = ($assessment instanceof \App\Models\Assignment) ? 'assignment' : 
                   (($assessment instanceof \App\Models\Quiz) ? 'quiz' : 'exam');

            // ស្វែងរកពិន្ទុពី Collection ដែលយើងទាញទុកមុននេះ
            $score = $allResults->where('assessment_id', $assessment->id)
                                ->where('student_user_id', $student->id)
                                ->where('assessment_type', $type)
                                ->first()?->score_obtained ?? 0;
            
            // រក្សាទុកក្នុង Array សម្រាប់ផ្ញើទៅ Blade
            $gradebook[$student->id][$type . '_' . $assessment->id] = $score;
            
            // បូកបញ្ចូលក្នុងពិន្ទុសរុប
            $totalScore += (float)$score;
        }

        $student->temp_attendance = $attendanceScore;
        $student->temp_total = $totalScore;
        return $student;
    });

    // ៣. តម្រៀប Ranking តាមពិន្ទុសរុប
    $students = $students->sortByDesc('temp_total')->values();

    // ៤. ផ្ដល់ Rank និង Grade
    foreach ($students as $index => $student) {
        $student->rank = $index + 1;
        $ts = $student->temp_total;
        
        if ($ts >= 85) $student->letterGrade = 'A';
        elseif ($ts >= 80) $student->letterGrade = 'B+';
        elseif ($ts >= 70) $student->letterGrade = 'B';
        elseif ($ts >= 65) $student->letterGrade = 'C+';
        elseif ($ts >= 50) $student->letterGrade = 'C';
        else $student->letterGrade = 'F';
    }

    // ៥. បង្កើត HTML សម្រាប់ Word
    $html = view('professor.grades.export_word', compact('courseOffering', 'students', 'assessments', 'gradebook'))->render();

    // ប្តូរឈ្មោះ File និងការពារការខូចអក្សរខ្មែរ
    $fileName = 'Gradebook_' . str_replace([' ', '/', '\\'], '_', $courseOffering->course->title_km) . '.doc';

    return response($html)
        ->header('Content-Type', 'application/msword; charset=utf-8')
        ->header('Content-Disposition', "attachment; filename*=UTF-8''" . rawurlencode($fileName));
}
// ឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲឲ


public function notifyTelegram($chatId, $message) 
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        
        // ឆែកមើលថាតើមាន Token និង Chat ID ឬអត់មុននឹងផ្ញើ
        if (!$token || !$chatId) {
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text'    => $message,
                'parse_mode' => 'HTML'
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error("Telegram Notification Error: " . $e->getMessage());
            return false;
        }
    }


    public function publishGrades($offering_id)
{
    $courseOffering = CourseOffering::with('studentCourseEnrollments.student')->findOrFail($offering_id);
    $courseName = $courseOffering->course->title_km;

    foreach ($courseOffering->studentCourseEnrollments as $enrollment) {
        $student = $enrollment->student;

        // ឆែកថាតើសិស្សម្នាក់ហ្នឹងបានភ្ជាប់ Telegram (មាន chat_id) ឬនៅ
        if ($student->telegram_chat_id) {
            $msg = "<b>🔔 ដំណឹងលទ្ធផលសិក្សាថ្មី!</b>\n\n";
            $msg .= "មុខវិជ្ជា៖ <b>{$courseName}</b>\n";
            $msg .= "ស្ថានភាព៖ ពិន្ទុត្រូវបានគ្រូបោះពុម្ពផ្សាយហើយ។\n";
            $msg .= "🔗 ចូលមើលពិន្ទុ៖ <a href='".url('/student/my-grades')."'>ចុចទីនេះ</a>";

            $this->notifyTelegram($student->telegram_chat_id, $msg);
        }
    }

    return back()->with('success', 'បានផ្ញើដំណឹងទៅកាន់ Telegram របស់និស្សិតរួចរាល់!');
}

public function sendGradeTelegram($enrollment_id)
{
    // ទាញយក Enrollment ដោយភ្ជាប់ជាមួយ studentUser (Table users)
    $enrollment = \App\Models\StudentCourseEnrollment::with(['studentUser', 'courseOffering.course'])
        ->findOrFail($enrollment_id);
    
    $studentUser = $enrollment->studentUser;

    // ត្រួតពិនិត្យ Chat ID លើ studentUser មិនមែនលើ student ទេ
    if (!$studentUser || !$studentUser->telegram_chat_id) {
        return back()->with('error', 'និស្សិតនេះមិនទាន់បានភ្ជាប់ជាមួយ Telegram Bot នៅឡើយទេ!');
    }

    $token = env('TELEGRAM_BOT_TOKEN');
    
    $message = "<b>🔔 លទ្ធផលសិក្សា</b>\n\n";
    $message .= "និស្សិត៖ <b>{$studentUser->name}</b>\n";
    $message .= "មុខវិជ្ជា៖ <b>{$enrollment->courseOffering->course->title_km}</b>\n";
    $message .= "ស្ថានភាព៖ ពិន្ទុត្រូវបានផ្សាយហើយ។";

    // ហៅប្រើ function notifyTelegram ដែលអ្នកមានស្រាប់
    $this->notifyTelegram($studentUser->telegram_chat_id, $message);

    return back()->with('success', 'បានផ្ញើទៅ Telegram រួចរាល់!');
}
// professor.grades.store




public function sendAllTelegram(Request $request, $offering_id)
{
    $courseOffering = CourseOffering::with('course', 'studentCourseEnrollments.student.profile')->findOrFail($offering_id);
    
    $assessmentId = $request->input('assessment_id');
    $type = $request->input('assessment_type'); 

    // ១. ទាញយកព័ត៌មានវិញ្ញាសា
    $assessment = match($type) {
        'assignment' => \App\Models\Assignment::find($assessmentId),
        'quiz'       => \App\Models\Quiz::find($assessmentId),
        'exam'       => \App\Models\Exam::find($assessmentId),
        default      => null
    };

    if (!$assessment) {
        return back()->with('error', "រកមិនឃើញទិន្នន័យវិញ្ញាសាឡើយ។");
    }

    // ២. រៀបចំព័ត៌មានសាស្ត្រាចារ្យ (Contact Link)
    $professor = auth()->user();
    // សន្មតថាទំនាក់ទំនងគឺ professorProfile ឬ userProfile
    $profProfile = $professor->professorProfile ?: $professor->userProfile; 
    
    // បង្កើត Link ទៅកាន់ Telegram លោកគ្រូ (ប្រសិនបើគ្មាន វានឹងដាក់ Link ទៅកាន់ Bot)
    $professorContact = ($profProfile && $profProfile->telegram_user) 
        ? "https://t.me/" . str_replace('@', '', $profProfile->telegram_user) 
        : "https://t.me/kong_grade_bot";

    $typeName = match($type) {
        'assignment' => 'កិច្ចការ (Assignment)',
        'quiz'       => 'កម្រងសំណួរ (Quiz)',
        'exam'       => 'ការប្រឡង (Exam)',
        default      => 'វិញ្ញាសា'
    };

    $title = $assessment->title_km ?? $assessment->title_en;
    $sentCount = 0;

    foreach ($courseOffering->studentCourseEnrollments as $enrollment) {
        $student = $enrollment->student;
        
        if ($student && $student->telegram_chat_id) {
            
            // ៣. ទាញយកពិន្ទុពី Table ExamResult
            $result = \App\Models\ExamResult::where('assessment_id', $assessmentId)
                ->where('assessment_type', $type)
                ->where('student_user_id', $student->id)
                ->first();

            $score = $result ? number_format($result->score_obtained, 1) : '---';
            $maxScore = $assessment->max_score ?? 100;

            // ៤. រៀបចំ Template សារ Telegram
            $message = "<b>📢 ដំណឹងលទ្ធផលសិក្សា</b>\n\n";
            $message .= "សួស្តីនិស្សិត៖ <b>" . ($student->profile->full_name_km ?? $student->name) . "</b>\n";
            $message .= "មុខវិជ្ជា៖ <b>{$courseOffering->course->title_en}</b>\n";
            $message .= "ប្រភេទ៖ <b>{$typeName}</b>\n";
            $message .= "វិញ្ញាសា៖ <b>{$title}</b>\n";
            $message .= "--------------------------------\n";
            $message .= "🎯 ពិន្ទុទទួលបាន៖ <code>{$score} / {$maxScore}</code>\n";
            $message .= "--------------------------------\n\n";
            
            // បន្ថែម Link ទំនាក់ទំនងសាស្ត្រាចារ្យ
            $message .= "💬 បើមានចម្ងល់សូមទាក់ទងសាស្ត្រាចារ្យ៖\n";
            $message .= "👉 <a href='{$professorContact}'>ចុចទីនេះដើម្បីផ្ញើសារ</a>\n\n";
            
            $message .= "👉 សូមចូលពិនិត្យមើលពិន្ទុលម្អិតក្នុងប្រព័ន្ធ។";

            $this->notifyTelegram($student->telegram_chat_id, $message);
            $sentCount++;
        }
    }

    return back()->with('success', "បានផ្ញើដំណឹងពិន្ទុ {$title} ទៅកាន់និស្សិតចំនួន {$sentCount} នាក់ រួចរាល់។");
}   


public function updateTelegram(Request $request)
{
    $request->validate([
        'telegram_chat_id' => 'required|numeric',
    ]);

    $user = auth()->user();
    $user->telegram_chat_id = $request->telegram_chat_id;
    $user->save();

    return back()->with('success', 'អបអរសាទរ! គណនី Telegram របស់អ្នកត្រូវបានភ្ជាប់ហើយ។');
}

public function sendTelegramSchedule($chatId, $message)
{
    $botToken = "8326400735:AAEIrI4k9r8ryOJETTV0F9jmaRh-tLeHKe0"; // យកពី BotFather
    
    $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ]);

    return $response->successful();
}

public function notifyProfessorSchedule()
{
    $user = auth()->user();
    $chatId = $user->profile?->telegram_chat_id;

    if (!$chatId) return;

    // ទាញយកកាលវិភាគថ្ងៃនេះ (ឧទាហរណ៍)
    $schedules = Schedule::where('professor_id', $user->id)
                         ->whereDate('class_date', now())
                         ->get();

    if ($schedules->isEmpty()) {
        $message = "📅 ជម្រាបសួរលោកគ្រូ! ថ្ងៃនេះលោកគ្រូមិនមានកាលវិភាគបង្រៀនទេ។";
    } else {
        $message = "📅 <b>កាលវិភាគបង្រៀនថ្ងៃនេះ៖</b>\n\n";
        foreach ($schedules as $item) {
            $message .= "🔹 ម៉ោង: {$item->start_time} - {$item->end_time}\n";
            $message .= "🔹 មុខវិជ្ជា: {$item->subject_name}\n";
            $message .= "🔹 បន្ទប់: {$item->room}\n";
            $message .= "----------------------\n";
        }
    }

    $this->sendTelegramSchedule($chatId, $message);
}
// professor.assessments
 protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            // ១. ទាញយកសាស្ត្រាចារ្យទាំងឡាយណាដែលមាន Telegram Chat ID
            $users = User::whereNotNull('telegram_chat_id')->get();
            $botToken = env('TELEGRAM_BOT_TOKEN2'); // កុំភ្លេចដាក់ក្នុង .env

            foreach ($users as $user) {
                // ២. ទាញយកកាលវិភាគថ្ងៃនេះរបស់សាស្ត្រាចារ្យម្នាក់ៗ
                // លោកគ្រូត្រូវកែសម្រួល Logic ទាញកាលវិភាគតាម Database របស់លោកគ្រូ
                $todaySchedules = \App\Models\Schedule::where('professor_id', $user->id)
                    ->whereDate('date', now())
                    ->orderBy('start_time', 'asc')
                    ->get();

                if ($todaySchedules->isNotEmpty()) {
                    $message = "📅 <b>ជម្រាបសួរលោកគ្រូ " . ($user->profile->full_name_km ?? $user->name) . "</b>\n";
                    $message .= "នេះគឺជាកាលវិភាគបង្រៀនរបស់លោកគ្រូសម្រាប់ថ្ងៃនេះ៖\n\n";

                    foreach ($todaySchedules as $index => $item) {
                        $num = $index + 1;
                        $message .= "{$num}. <b>{$item->subject_name}</b>\n";
                        $message .= "   ⏰ ម៉ោង: {$item->start_time} - {$item->end_time}\n";
                        $message .= "   📍 បន្ទប់: {$item->room_name}\n";
                        $message .= "--------------------------\n";
                    }
                    
                    $message .= "\nសូមលោកគ្រូត្រៀមខ្លួនឱ្យបានរួចរាល់។ សូមអរគុណ!";

                    // ៣. ផ្ញើសារទៅកាន់ Telegram
                    Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $user->telegram_chat_id,
                        'text' => $message,
                        'parse_mode' => 'HTML',
                    ]);
                }
            }
        })->dailyAt('07:00');
    }
   
// --------------------------------------------------------------------------
}
// showProfile