<?php

namespace App\Http\Controllers\professor;
use App\Http\Controllers\Controller;
use App\Models\User;
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


class ProfessorController extends Controller
{
    /**
     * Display the professor dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

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
                                        ->where('due_date', '>=', now())
                                        ->orderBy('due_date')
                                        ->take(5)
                                        ->get();

        // Upcoming exams from the professor's course offerings
        $upcomingExams = Exam::whereHas('courseOffering', function ($query) use ($user) {
                                $query->where('lecturer_user_id', $user->id);
                            })
                            ->where('exam_date', '>=', now())
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



        return view('professor.dashboard', compact(
            'user',
            'courseOfferings',
            'totalStudents',
            'upcomingAssignments',
            'upcomingExams',
            'announcements',
            'unreadNotificationsCount',
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


    /*
    |--------------------------------------------------------------------------
    | START: កូដថ្មីសម្រាប់ការគ្រប់គ្រងពិន្ទុ
    |--------------------------------------------------------------------------
    */

    /**
     * Method សម្រាប់បង្ហាញតារាងពិន្ទុ (Gradebook)
     */
    public function manageGrades($offering_id)
    {
        $courseOffering = CourseOffering::with([
            'course',
            'studentCourseEnrollments.student.profile' 
        ])->findOrFail($offering_id);
        // $assignments = Assignment::where('course_offering_id', $offering_id)->with('submissions')->get();
        // $exams = Exam::where('course_offering_id', $offering_id)->with('examResults')->get();
        $assignments = Assignment::where('course_offering_id', $offering_id)
                ->with('submissions')
                ->get()
                ->map(function ($a) {
                    $a->assessment_type = 'assignment';
                    return $a;
                });

            $exams = Exam::where('course_offering_id', $offering_id)
                ->with('examResults')
                ->get()
                ->map(function ($e) {
                        if ($e->gradingCategory) {
                            $e->assessment_type = $e->gradingCategory->name_km; // Example: "ប្រឡងកណ្ដាលឆមាស"
                        } else {
                            $e->assessment_type = 'Exam'; // fallback
                        }
                        return $e;
                    });

        $assessments = collect($assignments)->concat($exams)->sortBy('created_at');
        $gradebook = [];
        $students = $courseOffering->studentCourseEnrollments->map(function ($enrollment) {
            return $enrollment->student;
        })->sortBy('name');

        foreach ($students as $student) {
            foreach ($assessments as $assessment) {
                $score = null;
                if ($assessment instanceof Assignment) {
                    $submission = $assessment->submissions->firstWhere('student_user_id', $student->id);
                    $score = $submission ? $submission->grade_received : null;
                } elseif ($assessment instanceof Exam) {
                    $result = $assessment->examResults->firstWhere('student_user_id', $student->id);
                    $score = $result ? $result->score_obtained : null;
                }
                $gradebook[$student->id][$assessment->id] = $score;
            }
        }

        return view('professor.grades.index', compact('courseOffering', 'students', 'assessments', 'gradebook'));
    }


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
        $validator = Validator::make($request->all(), [
            'assessment_type' => 'required|in:assignment,exam',
            'title_en' => 'required|string|max:255',
            'title_km' => 'required|string|max:255',
            'max_score' => 'required|numeric|min:1',
            'assessment_date' => 'required|date',
            'grading_category_id' => 'nullable|exists:grading_categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $courseOffering = CourseOffering::findOrFail($offering_id);

        if ($request->input('assessment_type') === 'assignment') {
            Assignment::create([
                'course_offering_id' => $courseOffering->id,
                'title_km' => $request->input('title_km'),
                'title_en' => $request->input('title_en'),
                'max_score' => $request->input('max_score'),
                'due_date' => $request->input('assessment_date'),
                'grading_category_id' => $request->input('grading_category_id'),
            ]);
        } elseif ($request->input('assessment_type') === 'exam') {
            Exam::create([
                'course_offering_id' => $courseOffering->id,
                'title_km' => $request->input('title_km'),
                'title_en' => $request->input('title_en'),
                'max_score' => $request->input('max_score'),
                'exam_date' => $request->input('assessment_date'),
                'duration_minutes' => 120, 
            ]);
        }

        Session::flash('success', 'ការវាយតម្លៃត្រូវបានបង្កើតដោយជោគជ័យ!');
        return redirect()->route('professor.manage-grades', ['offering_id' => $offering_id]);
    }
    public function destroyAssessment($id)
    {
        DB::beginTransaction();
        try {
            $deleted = false;
            $title = __('ការវាយតម្លៃ');

            $assessment = Assignment::find($id);
            if ($assessment) {
                $title = $assessment->title_km;
                // $assessment->grades()->delete();
                $assessment->submissions()->delete();  // For Assignment
                $assessment->examResults()->delete();  // For Exam

                $assessment->delete();
                $deleted = true;
            }

            if (!$deleted) {
                $assessment = Exam::find($id);
                if ($assessment) {
                    $title = $assessment->title_km;
                    $assessment->results()->delete();
                    $assessment->delete();
                    $deleted = true;
                }
            }

            if ($deleted) {
                DB::commit();
                Session::flash('success', __('ការវាយតម្លៃ') . ' «' . $title . '» ' . __('ត្រូវបានលុបដោយជោគជ័យ។'));
            } else {
                DB::rollBack();
                Session::flash('error', __('មិនអាចរកឃើញការវាយតម្លៃដើម្បីលុបបានទេ។'));
            }

        } catch (\Exception $e) {
        //     DB::rollBack();
        //     // Log the error for debugging
        //     \Log::error("Error deleting assessment ID $id: " . $e->getMessage());
        //     Session::flash('error', __('មានបញ្ហាក្នុងការលុបការវាយតម្លៃ។'));
        }

        return redirect()->back();
    }
// assessment_type
    /**
     * Method សម្រាប់បង្ហាញទម្រង់បញ្ចូលពិន្ទុ
     */
    public function showGradeEntryForm(Request $request, $assessment_id)
    {
        $type = $request->query('type');
        $assessment = null;

        if ($type === 'assignment') {
            $assessment = Assignment::with('courseOffering.studentCourseEnrollments.student.profile', 'submissions')->findOrFail($assessment_id);
        } elseif ($type === 'exam') {
            $assessment = Exam::with('courseOffering.studentCourseEnrollments.student.profile', 'examResults')->findOrFail($assessment_id);
        } else {
            abort(404, 'ប្រភេទការវាយតម្លៃមិនត្រឹមត្រូវ');
        }

        $students = $assessment->courseOffering->studentCourseEnrollments->map(function ($enrollment) {
            return $enrollment->student;
        })->sortBy('name');

        $scores = [];
        if ($type === 'assignment') {
            foreach ($assessment->submissions as $submission) {
                $scores[$submission->student_user_id] = [
                    'score' => $submission->grade_received,
                    'notes' => $submission->feedback,
                ];
            }
        } elseif ($type === 'exam') {
            foreach ($assessment->examResults as $result) {
                $scores[$result->student_user_id] = [
                    'score' => $result->score_obtained,
                    'notes' => $result->notes,
                ];
            }
        }

        return view('professor.grades.edit', compact('assessment', 'students', 'scores', 'type'));
    }


    /**
     * Method សម្រាប់រក្សាទុកពិន្ទុរបស់និស្សិត
     */
    public function storeGradesForAssessment(Request $request, $assessment_id)
    {
        $validator = Validator::make($request->all(), [
            'grades' => 'required|array',
            'grades.*.score' => 'nullable|numeric|min:0',
            'grades.*.notes' => 'nullable|string|max:1000',
            'assessment_type' => 'required|in:assignment,exam',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $type = $request->input('assessment_type');
        $offering_id = null;

        DB::beginTransaction();
        try {
            foreach ($request->input('grades') as $student_id => $gradeData) {
                if (!is_null($gradeData['score'])) {
                    if ($type === 'assignment') {
                        $assessment = Assignment::findOrFail($assessment_id);
                        $offering_id = $assessment->course_offering_id;
                        Submission::updateOrCreate(
                            [
                                'assignment_id' => $assessment_id,
                                'student_user_id' => $student_id,
                            ],
                            [
                                'grade_received' => $gradeData['score'],
                                'feedback' => $gradeData['notes'],
                                'submission_date' => now(), 
                            ]
                        );
                    } elseif ($type === 'exam') {
                        $assessment = Exam::findOrFail($assessment_id);
                        $offering_id = $assessment->course_offering_id;
                        ExamResult::updateOrCreate(
                            [
                                'exam_id' => $assessment_id,
                                'student_user_id' => $student_id,
                            ],
                            [
                                'score_obtained' => $gradeData['score'],
                                'notes' => $gradeData['notes'],
                                'recorded_at' => now(),
                            ]
                        );
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error storing grades: ' . $e->getMessage());
            Session::flash('error', 'មានបញ្ហាកើតឡើងនៅពេលរក្សាទុកពិន្ទុ។');
            return redirect()->back();
        }

        Session::flash('success', 'ពិន្ទុត្រូវបានរក្សាទុកដោយជោគជ័យ!');
        return redirect()->route('professor.manage-grades', ['offering_id' => $offering_id]);
    }

    /**
     * Manage attendance for a specific course offering.
     */

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

    public function allQuizzes(Request $request)
    {
        $user = Auth::user();
        $quizzes = Quiz::whereHas('courseOffering', function ($query) use ($user) {
                                $query->where('lecturer_user_id', $user->id);
                            })
                            ->with('courseOffering.course')
                            ->orderBy('end_date', 'desc')
                            ->paginate(10);

        return view('professor.all-quizzes', compact('quizzes'));
    }
    /**
     * Manage quizzes for a specific course offering.
     */
    public function manageQuizQuestions(Quiz $quiz)
    {
        if ($quiz->courseOffering->lecturer_user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $quiz->load('quizQuestions.options');

        return view('professor.quizzes.questions', compact('quiz'));
    }

    /**
     * Store a new question for a specific quiz.
     */
    public function storeQuizQuestion(Request $request, Quiz $quiz)
    {
        // Security check
        if ($quiz->courseOffering->lecturer_user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'question_text_km' => 'required|string',
            'question_text_en' => 'nullable|string',
            'question_type' => 'required|in:multiple_choice,short_answer', 
            'points' => 'required|numeric|min:0',
        ]);

        $quiz->quizQuestions()->create([
            'question_text_km' => $request->question_text_km,
            'question_text_en' => $request->question_text_en,
            'question_type' => $request->question_type,
            'points' => $request->points,
        ]);

        return redirect()->back()->with('success', 'សំណួរត្រូវបានបន្ថែមដោយជោគជ័យ!');
    }


    // --------------------------------------------------------------------------
    // NEW METHODS FOR QUIZ OPTIONS - START
    // --------------------------------------------------------------------------

    /**
     * Store a new option for a specific quiz question.
     */
    public function storeQuizOption(Request $request, QuizQuestion $question)
    {
        // Security check
        if ($question->quiz->courseOffering->lecturer_user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'option_text_km' => 'required|string|max:1000',
            'option_text_en' => 'nullable|string|max:1000',
            'is_correct' => 'sometimes|boolean',
        ]);

        if ($request->has('is_correct') && $request->is_correct) {
            $question->options()->update(['is_correct' => false]);
        }

        $question->options()->create([
            'option_text_km' => $validated['option_text_km'],
            'option_text_en' => $validated['option_text_en'],
            'is_correct' => $request->has('is_correct') ? $validated['is_correct'] : false,
        ]);

        return redirect()->back()->with('success', 'ជម្រើសត្រូវបានបន្ថែមដោយជោគជ័យ!');
    }

    /**
     * Delete an option from a quiz question.
     */
    public function destroyQuizOption(QuizOption $option)
    {
        // Security check
        if ($option->quizQuestion->quiz->courseOffering->lecturer_user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $option->delete();

        return redirect()->back()->with('success', 'ជម្រើសត្រូវបានលុបដោយជោគជ័យ!');
    }
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
    public function getCourseOfferingsWithStudents()
    {
        $user = Auth::user();
        $courseOfferings = CourseOffering::where('lecturer_user_id', $user->id)
                                         ->with('course')
                                         ->get();

        $students = User::where('role', 'student')->with('profile')->get();

        return response()->json([
            'courseOfferings' => $courseOfferings,
            'students' => $students,
        ]);
    }

      public function getStudentsInCourseOffering($offering_id)
    {
        $user = Auth::user();

        $courseOffering = CourseOffering::where('id', $offering_id)
                                         ->where('lecturer_user_id', $user->id)
                                         ->with(['course', 'studentCourseEnrollments.student.profile'])
                                         ->firstOrFail();
        $students = $courseOffering->studentCourseEnrollments->map(function ($enrollment) {
            return $enrollment->student; 
        });

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage('studentsPage');
        $currentItems = $students->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
        $paginatedStudents = new LengthAwarePaginator($currentItems, $students->count(), $perPage, $currentPage, [
            'path' => request()->url(),
            'pageName' => 'studentsPage',
        ]);

        return view('professor.students.index', compact('courseOffering', 'paginatedStudents'));
    }
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
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'full_name_km' => 'required|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userProfile = $user->userProfile()->firstOrNew(['user_id' => $user->id]);
        if ($request->hasFile('profile_picture')) {
            if ($userProfile->profile_picture_url) {
                Storage::disk('public')->delete(Str::after($userProfile->profile_picture_url, ''));
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $userProfile->profile_picture_url = '' . $path;
        }

        $userProfile->fill($validator->validated());

        $userProfile->save();

        Session::flash('success', 'ប្រវត្តិរូបរបស់អ្នកត្រូវបានកែប្រែដោយជោគជ័យ!'); // Success message in Khmer

        return redirect()->route('professor.profile.show');
    }
// --------------------------------------------------------------------------
    // GRADING CATEGORY MANAGEMENT - END
    // --------------------------------------------------------------------------
}
