<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;

use App\Models\StudentCourseEnrollment;
use App\Models\AttendanceRecord;
use App\Models\Assignment;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\Schedule;
use App\Models\Submission;
use App\Models\ExamResult;
use App\Models\Program;
use App\Models\Course;
use App\Models\StudentQuizResponse;
use App\Models\CourseOffering;
use App\Models\UserProfile;
use App\Models\StudentProgramEnrollment; // ត្រូវប្រាកដថាបាន import StudentProgramEnrollment model
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\DB; 

class StudentGradeController extends Controller
{
    public function myGrades(Request $request)
{
    $user = Auth::user();
    
    // ១. ទាញយកពិន្ទុទាំងអស់ពី ExamResult
    $allExamResults = \App\Models\ExamResult::where('student_user_id', $user->id)
        ->get()
        ->map(function ($result) {
            $assessment = match($result->assessment_type) {
                'assignment' => \App\Models\Assignment::with('courseOffering.course')->find($result->assessment_id),
                'quiz'       => \App\Models\Quiz::with('courseOffering.course')->find($result->assessment_id),
                default      => \App\Models\Exam::with('courseOffering.course')->find($result->assessment_id),
            };

            if (!$assessment) return null;

            $result->course_id = $assessment->course_offering_id;
            $result->course_name_en = $assessment->courseOffering->course->title_en;
            $result->course_name_km = $assessment->courseOffering->course->title_km;
            $result->max_score = (float) $assessment->max_score;

            $result->grade = $this->calculateGrade($result->score_obtained, $result->max_score);


            // កំណត់ប្រភេទសម្រាប់ការឆែកលក្ខខណ្ឌ (Midterm=15, Final=50)
            if ($result->assessment_type === 'exam') {
                $result->display_type = ($result->max_score == 15) ? 'midterm' : 'final';
            } else {
                $result->display_type = $result->assessment_type;
            }

            return $result;
        })->filter();

    // ២. គ្រុបពិន្ទុតាមមុខវិជ្ជា និងអនុវត្តលក្ខខណ្ឌ
    $courseGrades = $allExamResults->groupBy('course_id')->map(function ($items, $courseId) use ($user) {
        $attendanceScore = $user->getAttendanceScoreByCourse($courseId);
        
        // រាប់ចំនួនវត្តមាន (ដើម្បីបាត់ Error ក្នុង Blade)
        $absCount = \App\Models\AttendanceRecord::where('student_user_id', $user->id)
            ->where('course_offering_id', $courseId)
            ->where('status', 'absent')->count();
        $perCount = \App\Models\AttendanceRecord::where('student_user_id', $user->id)
            ->where('course_offering_id', $courseId)
            ->where('status', 'permission')->count();

        $finalExamScore = $items->where('display_type', 'final')->sum('score_obtained');
        $midtermScore   = $items->where('display_type', 'midterm')->sum('score_obtained');
        $assignmentScore = $items->where('display_type', 'assignment')->sum('score_obtained');
        $extraQuizScore  = $items->where('display_type', 'quiz')->sum('score_obtained');
        
        $totalObtained = $items->sum('score_obtained') + $attendanceScore;

        // លក្ខខណ្ឌកំណត់ "ប្រឡងសង"
        $isFailed = ($finalExamScore < 24 || $midtermScore < 9 || $assignmentScore < 9 || $attendanceScore < 9);

        // --- គណនា Course Rank ---
        $enrollments = \App\Models\StudentCourseEnrollment::where('course_offering_id', $courseId)->get();
        $rankings = $enrollments->map(function ($enrol) use ($courseId) {
            $student = \App\Models\User::find($enrol->student_user_id);
            $att = $student ? $student->getAttendanceScoreByCourse($courseId) : 0;
            $allPoints = \App\Models\ExamResult::where('student_user_id', $enrol->student_user_id)
                ->whereIn('assessment_id', function($q) use ($courseId) {
                    $q->select('id')->from('assignments')->where('course_offering_id', $courseId)
                      ->union(\DB::table('quizzes')->select('id')->where('course_offering_id', $courseId))
                      ->union(\DB::table('exams')->select('id')->where('course_offering_id', $courseId));
                })->sum('score_obtained');
            return ['id' => $enrol->student_user_id, 'total' => (float)$att + (float)$allPoints];
        })->sortByDesc('total')->values();

        $rankIndex = $rankings->search(fn($r) => $r['id'] == $user->id);

        return (object)[
            'course_rank'      => ($rankIndex !== false) ? $rankIndex + 1 : 'N/A',
            'course_name_en'   => $items->first()->course_name_en,
            'course_name_km'   => $items->first()->course_name_km,
            'attendance_score' => $attendanceScore,
            'absent_count'     => $absCount,      // បញ្ចូល Property នេះដើម្បីបាត់ Error
            'permission_count' => $perCount,      // បញ្ចូល Property នេះដើម្បីបាត់ Error
            'total_score'      => $totalObtained,
            'grade'            => $isFailed ? 'F' : $this->calculateGrade($totalObtained, 100),
            'is_failed'        => $isFailed,
            'assessments'      => $items
        ];
    })->values();

    // ៣. គណនា Overall Rank (ចំណាត់ថ្នាក់រួម)
    $overallRank = 'N/A';
    if ($courseGrades->isNotEmpty()) {
        $firstOfferingId = $courseGrades->first()->course_id ?? \App\Models\StudentCourseEnrollment::where('student_user_id', $user->id)->first()->course_offering_id;
        $enrollments = \App\Models\StudentCourseEnrollment::where('course_offering_id', $firstOfferingId)->get();
        $overallRankings = $enrollments->map(function ($enrol) {
            $sid = $enrol->student_user_id;
            $studentModel = \App\Models\User::find($sid);
            $totalPoints = \App\Models\ExamResult::where('student_user_id', $sid)->sum('score_obtained');
            $totalAtt = 0;
            foreach(\App\Models\StudentCourseEnrollment::where('student_user_id', $sid)->pluck('course_offering_id') as $cId) {
                $totalAtt += $studentModel ? $studentModel->getAttendanceScoreByCourse($cId) : 0;
            }
            return ['id' => $sid, 'total' => (float)$totalPoints + (float)$totalAtt];
        })->sortByDesc('total')->values();
        $overallRank = $overallRankings->search(fn($r) => $r['id'] == $user->id) + 1;
    }

    $averageScore = $courseGrades->avg('total_score') ?? 0;
    $totalFinalScore = $courseGrades->sum('total_score');
    $overallGrade = $this->calculateGrade($averageScore, 100);

    $grades = new \Illuminate\Pagination\LengthAwarePaginator(
        $courseGrades->slice(($request->page ?? 1 - 1) * 10, 10)->values(),
        $courseGrades->count(), 10, $request->page ?? 1, ['path' => $request->url()]
    );

    return view('student.my-grades', compact('user', 'grades', 'averageScore', 'totalFinalScore', 'overallRank', 'overallGrade'));
}



private function calculateGrade($score, $maxScore)
{
    if ($maxScore <= 0) return 'F';
    $percentage = ($score / $maxScore) * 100;

    if ($percentage >= 90) return 'A';
    if ($percentage >= 80) return 'B';
    if ($percentage >= 70) return 'C';
    if ($percentage >= 60) return 'D';
    if ($percentage >= 50) return 'E';
    return 'F';
}
    /**
     * បង្ហាញកាលវិភាគរបស់សិស្ស។
     * Display the student's schedule.
     */
public function mySchedule()
{
    $user = Auth::user();

    // ទាញយកព័ត៌មាន Program របស់និស្សិត
    $studentProgramEnrollment = StudentProgramEnrollment::where('student_user_id', $user->id)
        ->where('status', 'active')
        ->with('program')
        ->first();
    $studentProgram = $studentProgramEnrollment ? $studentProgramEnrollment->program : null;

    // ទាញយកព័ត៌មានកាលវិភាគរបស់និស្សិត
    $schedules = Schedule::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
        $query->where('student_user_id', $user->id);
    })
        ->with(['courseOffering.course', 'courseOffering.lecturer.userProfile', 'room'])
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();

    return view('student.my-schedule', compact('user', 'schedules', 'studentProgram'));
}
    /**
     * បង្ហាញបញ្ជីមុខវិជ្ជាដែលបានចុះឈ្មោះសម្រាប់សិស្សជាក់លាក់។
     * Display the list of enrolled courses for a specific student.
     *
     * @param  string  $studentId
     * @return \Illuminate\View\View
     */
    public function enrolledCourses($studentId)
    {
        // ស្វែងរកសិស្សតាម ID
        // Find the student by ID
        $student = User::with('studentEnrollments.courseOffering.course') // Eager load enrollments and course offerings
                            ->where('id', $studentId)
                            ->whereHas('studentEnrollments', function ($query) {
                                $query->where('status', 'enrolled'); // តែមុខវិជ្ជាដែលបានចុះឈ្មោះប៉ុណ្ណោះ
                            })
                            ->firstOrFail(); // បង្ហាញ 404 ប្រសិនបើសិស្សមិនត្រូវបានរកឃើញ ឬគ្មានមុខវិជ្ជាដែលបានចុះឈ្មោះ

        // ការពិនិត្យការអនុញ្ញាត: មានតែសិស្សខ្លួនឯង ឬអ្នកគ្រប់គ្រងប៉ុណ្ណោះដែលអាចមើលការចុះឈ្មោះរបស់ពួកគេបាន
        // Authorization check: Only the student themselves or an admin can view their enrollments
        if (Auth::id() !== $student->id && !(Auth::user() && Auth::user()->isAdmin())) { // Use isAdmin() method
            abort(403, 'Unauthorized action.');
        }

        // ទាញយកការចុះឈ្មោះសម្រាប់សិស្សនេះជាមួយនឹងស្ថានភាព 'enrolled'
        // Retrieve enrollments for this student with 'enrolled' status
        // We have eager loaded them, so they are available in $student->studentEnrollments
        $enrollments = $student->studentEnrollments;

        // បញ្ជូនទិន្នន័យទៅ View
        // Pass data to the View
        return view('student.enrolled_courses', compact('student', 'enrollments'));
    }

// myenroll
    /**
     * បង្ហាញកិច្ចការរបស់សិស្ស។
     * សន្មតថាមានតារាង 'assignments' និង 'assignment_submissions'។
     * Display the student's assignments.
     * Assumes an 'assignments' table and 'assignment_submissions' table.
     */
    public function myAssignments()
    {
        $user = Auth::user();
        // ទាញយកកិច្ចការសម្រាប់មុខវិជ្ជាដែលសិស្សបានចុះឈ្មោះ
        // Fetch assignments for the student's enrolled courses
        $assignments = Assignment::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                                           $query->where('student_user_id', $user->id);
                                       })
                                       ->with(['courseOffering.course', 'submissions' => function($query) use ($user) {
                                           $query->where('student_user_id', $user->id);
                                       }])
                                       ->orderBy('due_date', 'asc')
                                       ->paginate(10);

        $assignments->each(function ($assignment) use ($user) {
            $submission = $assignment->submissions->first();
            $assignment->isSubmitted = (bool) $submission;
            $assignment->grade = $submission ? $submission->grade_received : null;
        });

        return view('student.my-assignments', compact('user', 'assignments'));
    }

    /**
     * បង្ហាញការប្រឡងរបស់សិស្ស។
     * សន្មតថាមានតារាង 'exams' និង 'exam_results'។
     * Display the student's exams.
     * Assumes an 'exams' table and 'exam_results' table.
     */
    public function myExams()
    {
        $user = Auth::user();
        $exams = Exam::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                                 $query->where('student_user_id', $user->id);
                             })
                             ->with(['courseOffering.course', 'examResults' => function($query) use ($user) {
                                 $query->where('student_user_id', $user->id);
                             }])
                             ->orderBy('exam_date', 'asc')
                             ->paginate(10);

        $exams->each(function ($exam) use ($user) {
            $result = $exam->examResults->first();
            $exam->grade = $result ? $result->score_obtained : null;
        });

        return view('student.my-exams', compact('user', 'exams'));
    }
// studentProgram
    /**
     * បង្ហាញ Quiz របស់សិស្ស។
     * Display the student's quizzes.
     */
    public function myQuizzes()
    {
        $user = Auth::user();
        $quizzes = Quiz::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                                 $query->where('student_user_id', $user->id);
                             })
                             ->with(['courseOffering.course', 'quizQuestions.quizOptions', 'quizQuestions.studentQuizResponses' => function($query) use ($user) {
                                 $query->where('student_user_id', $user->id);
                             }])
                             ->orderBy('end_date', 'asc')
                             ->paginate(10);

        $quizzes->each(function ($quiz) use ($user) {
            $correctAnswers = 0;
            $totalQuestions = $quiz->quizQuestions->count();
            $totalPossibleScore = $quiz->total_points ?? ($totalQuestions > 0 ? $totalQuestions * 10 : 0);

            foreach ($quiz->quizQuestions as $question) {
                $studentResponse = $question->studentQuizResponses->first(function ($response) use ($user) {
                    return $response->student_user_id === $user->id;
                });
                if ($studentResponse && $studentResponse->is_correct) {
                    $correctAnswers++;
                }
            }
            $quiz->studentScore = $correctAnswers;
            $quiz->totalQuestions = $totalQuestions;
            $quiz->totalPossibleScore = $totalPossibleScore;
            $quiz->grade = ($totalQuestions > 0 && $totalPossibleScore > 0) ? round(($correctAnswers / $totalQuestions) * $totalPossibleScore, 2) : 0;
        });

        return view('student.my-quizzes', compact('user', 'quizzes'));
    }

    /**
     * បង្ហាញកំណត់ត្រាចូលរួមរបស់សិស្ស។
     * Display the student's attendance records.
     */

// image
    /**
     * បង្ហាញមុខវិជ្ជាដែលមានសម្រាប់សិស្សចុះឈ្មោះ។
     * Display the available courses for student enrollment.
     */
    public function availablePrograms()
    {
        $user = Auth::user();

        // ស្វែងរក Program IDs ដែលសិស្សបានចុះឈ្មោះរួចហើយ
        // Find Program IDs the student is already enrolled in
        $enrolledProgramIds = StudentProgramEnrollment::where('student_user_id', $user->id)
                                                      ->where('status', 'active')
                                                      ->pluck('program_id');

        // ទាញយក Programs ដែលសិស្សមិនទាន់បានចុះឈ្មោះ
        // Fetch Programs that the student is NOT already enrolled in
        $availablePrograms = Program::whereNotIn('id', $enrolledProgramIds)
                                    ->with('faculty', 'department') // ផ្ទុកទំនាក់ទំនងដែលត្រូវការ
                                    ->paginate(10);

        return view('student.available-programs', compact('user', 'availablePrograms'));
    }

public function enrollSelf(Request $request)
{
    $request->validate([
        'course_offering_id' => 'required|exists:course_offerings,id',
    ]);

    $user = Auth::user();
    $courseOfferingId = $request->input('course_offering_id');

    // ១. ពិនិត្យមើលថាធ្លាប់ចុះឈ្មោះរួចហើយឬនៅ
    $existingEnrollment = StudentCourseEnrollment::where('student_user_id', $user->id)
        ->where('course_offering_id', $courseOfferingId)
        ->first();

    if ($existingEnrollment) {
        Session::flash('info', 'អ្នកបានចុះឈ្មោះក្នុងវគ្គសិក្សានេះរួចហើយ។');
        return redirect()->back();
    }

    try {
        // ២. បង្កើត Record ថ្មី (បញ្ជូនទាំង student_user_id និង student_id)
        StudentCourseEnrollment::create([
            'student_user_id'    => $user->id,
            'student_id'         => $user->id, // 💡 បន្ថែមនេះដើម្បីដោះស្រាយបញ្ហា SQL Error
            'course_offering_id' => $courseOfferingId,
            'enrollment_date'    => now(),
            'status'             => 'enrolled',
        ]);

        Session::flash('success', 'ការចុះឈ្មោះដោយជោគជ័យ!');
    } catch (\Exception $e) {
        // បើមាន Error វានឹងបង្ហាញប្រាប់ថា Error អ្វី
        Session::flash('error', 'មានបញ្ហាក្នុងការចុះឈ្មោះ៖ ' . $e->getMessage());
    }

    // ៣. Redirect ទៅកាន់ Dashboard (ប្រើ student.dashboard តាម Route name របស់អ្នក)
    return redirect()->route('student.dashboard');
}
    /**
     * គ្រប់គ្រងការចុះឈ្មោះកម្មវិធីសិក្សារបស់សិស្ស។
     * Handles the student's program enrollment request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enrollProgram(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $user = Auth::user();
        $programId = $request->input('program_id');

        // ពិនិត្យមើលថាតើសិស្សបានចុះឈ្មោះក្នុង Program នេះរួចហើយឬនៅ
        // Check if student is already enrolled in this Program
        $existingProgramEnrollment = StudentProgramEnrollment::where('student_user_id', $user->id)
                                                              ->where('program_id', $programId)
                                                              ->first();

        if ($existingProgramEnrollment) {
            Session::flash('info', 'អ្នកបានចុះឈ្មោះក្នុងកម្មវិធីសិក្សានេះរួចហើយ។');
            return redirect()->back();
        }

        DB::transaction(function () use ($user, $programId) {
            // បង្កើតការចុះឈ្មោះ Program
            // Create the Program enrollment
            StudentProgramEnrollment::create([
                'student_user_id' => $user->id,
                'program_id' => $programId,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            // ចុះឈ្មោះដោយស្វ័យប្រវត្តិក្នុង Course Offerings ទាំងអស់នៃ Program នេះ
            // Auto-enroll in all relevant Course Offerings of this Program
            $programCourseOfferings = CourseOffering::whereHas('course', function ($query) use ($programId) {
                                                    $query->where('program_id', $programId);
                                                })
                                                ->where('end_date', '>=', now())
                                                ->get();

            foreach ($programCourseOfferings as $courseOffering) {
                StudentCourseEnrollment::firstOrCreate([
                    'student_user_id' => $user->id,
                    'course_offering_id' => $courseOffering->id,
                ], [
                    'enrollment_date' => now(),
                    'status' => 'enrolled',
                ]);
            }
        });

        Session::flash('success', 'ការចុះឈ្មោះកម្មវិធីសិក្សា និងមុខវិជ្ជាបានជោគជ័យ!');
        return redirect()->route('student.available_programs'); // បញ្ជូនត្រឡប់ទៅទំព័រកម្មវិធីសិក្សាដែលមាន
    }
public function myEnrolledCourses()
{
    $user = Auth::user();

    // ១. ស្វែងរក Program ដែលសិស្សបានចុះឈ្មោះ (Active)
    $studentProgramEnrollment = \App\Models\StudentProgramEnrollment::where('student_user_id', $user->id)
        ->where('status', 'active')
        ->with('program')
        ->first();

    $studentProgram = $studentProgramEnrollment ? $studentProgramEnrollment->program : null;

    // ២. ទាញយកមុខវិជ្ជាដែលបានចុះឈ្មោះ
    $enrollments = \App\Models\StudentCourseEnrollment::where('student_user_id', $user->id)
        ->with([
            'courseOffering.course', 
            'courseOffering.lecturer.userProfile', // សម្រាប់រូប Profile គ្រូ
            
            // ✅ បន្ថែមថ្មី៖ Load កាលវិភាគ និងបន្ទប់ មកជាមួយ ដើម្បីបង្ហាញក្នុង Card
            'courseOffering.schedules.room'        
        ])
        // រៀបតាមលំដាប់ចុះឈ្មោះចុងក្រោយនៅខាងលើ
        ->orderBy('created_at', 'desc') 
        ->paginate(10);

    return view('student.my-enrolled-courses', compact('user', 'enrollments', 'studentProgram'));
}

}
