<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Models\StudentCourseEnrollment;
use App\Models\AttendanceRecord;
use App\Models\Assignment;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\Schedule;
use App\Models\Submission;
use App\Models\ExamResult;
use App\Models\StudentQuizResponse;
use App\Models\CourseOffering;
use App\Models\Announcement; // Import Announcement model
use App\Models\Program;
use App\Models\Course;
use App\Models\UserProfile;
use App\Models\StudentProgramEnrollment; // ត្រូវប្រាកដថាបាន import StudentProgramEnrollment model
use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AnnouncementRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\DB; // Make sure DB facade is imported for transactions

class StudentController extends Controller
{

 public function dashboard()
    {
        $user = Auth::user();

        // ទាញយកទិន្នន័យជាក់លាក់របស់សិស្សសម្រាប់ផ្ទាំងគ្រប់គ្រង
        // Fetch student-specific data for the dashboard
        $enrollments = StudentCourseEnrollment::where('student_user_id', $user->id)
                                         ->with('courseOffering.course', 'courseOffering.lecturer')
                                         ->get();
        $upcomingAssignments = Assignment::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                                                    $query->where('student_user_id', $user->id);
                                                })
                                                ->where('due_date', '>=', now())
                                                ->orderBy('due_date')
                                                ->take(5)
                                                ->get();
        $upcomingExams = Exam::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                                            $query->where('student_user_id', $user->id);
                                        })
                                        ->where('exam_date', '>=', now())
                                        ->orderBy('exam_date')
                                        ->take(5)
                                        ->get();
        $upcomingSchedules = Schedule::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                                                    $query->where('student_user_id', $user->id);
                                                })
                                                ->where('start_time', '>=', now())
                                                ->orderBy('start_time')
                                                ->take(5)
                                                ->get();
$studentProgram = null;
        $studentProgramEnrollment = StudentProgramEnrollment::where('student_user_id', $user->id)
                                                           ->where('status', 'active')
                                                           ->with('program')
                                                           ->first();
        if ($studentProgramEnrollment) {
            $studentProgram = $studentProgramEnrollment->program;
        }

        $availableCoursesInProgram = collect([]);
        if ($studentProgram) {
            $enrolledCourseOfferingIds = StudentCourseEnrollment::where('student_user_id', $user->id)
                ->where('status', 'enrolled')
                ->pluck('course_offering_id');

            // 💡 ទាញយកជំនាន់របស់និស្សិត
            $studentGeneration = $user->generation;
            $availableCoursesInProgram = CourseOffering::with('course', 'lecturer')
                ->whereHas('course', function ($query) use ($studentProgram) {
                    $query->where('program_id', $studentProgram->id);
                })
                ->whereNotIn('id', $enrolledCourseOfferingIds)
                ->where('end_date', '>=', now())
                ->where('generation', $studentGeneration) // 💡 នេះគឺជាបន្ទាត់ដែលបានបន្ថែមដើម្បីត្រងតាមជំនាន់
                ->get();
        }
        
        $completedCoursesCount = StudentCourseEnrollment::where('student_user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        $totalCoursesInProgram = $studentProgram ? $studentProgram->courses->count() : 0;
        
        // ផ្លាស់ប្តូរដើម្បីទាញយកសេចក្តីប្រកាសទាំងអស់ និងសម្គាល់ថាតើវាត្រូវបានអានហើយឬនៅ
        $allAnnouncements = Announcement::where('target_role', 'all')
            ->orWhere('target_role', 'student')
            ->with(['reads' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($announcement) {
                $announcement->type = 'announcement';
                $announcement->title = $announcement->title_km ?? $announcement->title_en;
                $announcement->content = $announcement->content_km ?? $announcement->content_en;
                $announcement->is_read = $announcement->reads->isNotEmpty();
                return $announcement;
            });

        // ផ្លាស់ប្តូរដើម្បីទាញយកការជូនដំណឹងទាំងអស់ និងសម្គាល់ថាតើវាត្រូវបានអានហើយឬនៅ
        $allNotifications = $user->notifications->map(function ($notification) {
            $notification->type = 'notification';
            $notification->title = $notification->data['title'] ?? 'ការជូនដំណឹងថ្មី';
            $notification->content = $notification->data['message'] ?? 'អ្នកមានការជូនដំណឹងថ្មី។';
            $notification->is_read = $notification->read_at !== null;
            return $notification;
        });

        // បញ្ចូលគ្នានូវការជូនដំណឹង និងសេចក្តីប្រកាសទាំងអស់
        $combinedFeed = $allAnnouncements->merge($allNotifications)->sortByDesc('created_at');

        return view('student.dashboard', compact(
            'user',
            'enrollments',
            'upcomingAssignments',
            'upcomingExams',
            'upcomingSchedules',
            'studentProgram',
            'availableCoursesInProgram',
            'completedCoursesCount',
            'totalCoursesInProgram',
            'combinedFeed' // Pass the new combined feed
        ));
    }
    // ... (rest of the functions)

    /**
     * សម្គាល់ការជូនដំណឹងថាបានអានហើយ។
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();

        // រកមើលការជូនដំណឹងតាម ID
        $notification = $user->notifications()->find($id);

        if ($notification) {
            // សម្គាល់ថាបានអាន
            $notification->markAsRead();
            
            // ត្រឡប់ការឆ្លើយតបជា JSON
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.'
            ]);
        }
        
        // ត្រឡប់កំហុសប្រសិនបើមិនមានការជូនដំណឹង
        return response()->json([
            'success' => false,
            'message' => 'Notification not found.'
        ], 404);
    }

    /**
     * សម្គាល់សេចក្តីប្រកាសថាបានអានហើយ។
     * Mark an announcement as read.
     */
    public function markAnnouncementAsRead(Request $request, $id)
    {
        $user = Auth::user();
        
        // រកមើលសេចក្តីប្រកាសតាម ID
        $announcement = Announcement::find($id);

        if ($announcement) {
            // ពិនិត្យមើលថាតើអ្នកប្រើប្រាស់បានសម្គាល់វាថាបានអានហើយឬនៅ
            $readRecord = AnnouncementRead::where('announcement_id', $id)->where('user_id', $user->id)->first();
            
            if (!$readRecord) {
                // បង្កើតកំណត់ត្រាថ្មីប្រសិនបើមិនទាន់មាន
                AnnouncementRead::create([
                    'announcement_id' => $id,
                    'user_id' => $user->id,
                    'read_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Announcement marked as read.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Announcement already marked as read.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Announcement not found.'
        ], 404);
    }
public function notifications()
{
    $user = Auth::user();
    
    // Fetch all user notifications (both unread and read)
    $notifications = $user->notifications;

    // Fetch all relevant announcements for the student
    $courseOfferingIds = StudentCourseEnrollment::where('student_user_id', $user->id)->pluck('course_offering_id');
    $announcements = Announcement::where('target_role', 'all')
                                 ->orWhere('target_role', 'student')
                                 ->orWhereIn('course_offering_id', $courseOfferingIds)
                                 ->with('poster')
                                 ->get();

    // Combine notifications and announcements into a single collection
    $combinedFeed = collect();

    foreach ($notifications as $notification) {
        $combinedFeed->push((object) [
            'id' => $notification->id,
            'type' => 'notification',
            'title' => $notification->data['title'] ?? 'ការជូនដំណឹងថ្មី',
            'content' => $notification->data['message'] ?? '',
            'created_at' => $notification->created_at,
            'is_read' => $notification->read_at ? true : false,
        ]);
    }

    foreach ($announcements as $announcement) {
        $isRead = AnnouncementRead::where('announcement_id', $announcement->id)
                                  ->where('user_id', $user->id)
                                  ->exists();

        $combinedFeed->push((object) [
            'id' => $announcement->id,
            'type' => 'announcement',
            'title' => $announcement->title_km ?? $announcement->title_en,
            'content' => $announcement->content_km ?? $announcement->content_en,
            'created_at' => $announcement->created_at,
            'poster' => $announcement->poster,
            'is_read' => $isRead,
        ]);
    }

    // Sort the combined feed by creation date, with unread items at the top
    $combinedFeed = $combinedFeed->sortByDesc('created_at')->sortBy('is_read');

    // Manually paginate the combined feed
    $perPage = 10;
    $currentPage = request()->get('page', 1);
    $currentItems = $combinedFeed->slice(($currentPage - 1) * $perPage, $perPage)->all();

    $paginatedFeed = new LengthAwarePaginator(
        $currentItems,
        $combinedFeed->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url()]
    );

    return view('student.notifications.index', compact('paginatedFeed'));
}
    /**
     * បង្ហាញពិន្ទុរបស់សិស្ស។
     * រួមបញ្ចូលពិន្ទុពី AssignmentSubmissions, ExamResults និងពិន្ទុ Quiz ដែលបានគណនា។
     * Display the student's grades.
     * Combines grades from AssignmentSubmissions, ExamResults, and calculated Quiz scores.
     */
    public function myGrades(Request $request)
    {
        $user = Auth::user();
        $allGrades = new Collection();

        // 1. ទាញយកពិន្ទុកិច្ចការ
        // 1. Fetch Assignment Grades
        $assignmentGrades = Submission::where('student_user_id', $user->id)
                                             ->whereNotNull('grade_received')
                                             ->with('assignment.courseOffering.course')
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
                                                     'score' => $submission->grade_received,
                                                     'max_score' => $maxScore,
                                                     'date' => $submission->updated_at,
                                                 ];
                                             });
        $allGrades = $allGrades->concat($assignmentGrades);


        // 2. ទាញយកពិន្ទុប្រឡង
        // 2. Fetch Exam Grades
        $examGrades = ExamResult::where('student_user_id', $user->id)
                                ->whereNotNull('score_obtained')
                                ->with('exam.courseOffering.course')
                                ->get()
                                ->map(function ($result) {
                                    $examTitle = $result->exam->title_km ?? $result->exam->title_en ?? 'N/A';
                                    $courseTitle = $result->exam->courseOffering->course->title_km ?? 'N/A';
                                    $maxScore = $result->exam->max_score ?? 0;

                                    return (object)[
                                        'type' => 'exam',
                                        'course_title_km' => $courseTitle,
                                        'assessment_type' => 'ប្រឡង: ' . $examTitle,
                                        'score' => $result->score_obtained,
                                        'max_score' => $maxScore,
                                        'date' => $result->updated_at,
                                    ];
                                });
        $allGrades = $allGrades->concat($examGrades);

        // 3. ទាញយកពិន្ទុ Quiz (ទាមទារការគណនាសម្រាប់ Quiz នីមួយៗ)
        // 3. Fetch Quiz Grades (Requires calculation per quiz)
        $studentQuizzesWithResponses = Quiz::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                                                    $query->where('student_user_id', $user->id);
                                                })
                                                ->whereHas('quizQuestions.studentQuizResponses', function ($query) use ($user) {
                                                    $query->where('student_user_id', $user->id);
                                                })
                                                ->with(['courseOffering.course', 'quizQuestions.quizOptions', 'quizQuestions.studentQuizResponses' => function($query) use ($user) {
                                                    $query->where('student_user_id', $user->id);
                                                }])
                                                ->get();

        $quizGrades = $studentQuizzesWithResponses->map(function ($quiz) use ($user) {
            $correctAnswers = 0;
            $totalQuestions = 0;
            $totalPossibleScore = $quiz->total_points ?? 0;

            if ($quiz->quizQuestions->isNotEmpty()) {
                $totalQuestions = $quiz->quizQuestions->count();
                if ($totalPossibleScore === 0) {
                   $totalPossibleScore = $totalQuestions;
                }

                foreach ($quiz->quizQuestions as $question) {
                    $studentResponse = $question->studentQuizResponses->first(function ($response) use ($user) {
                        return $response->student_user_id === $user->id;
                    });
                    if ($studentResponse && $studentResponse->is_correct) {
                        $correctAnswers++;
                    }
                }
            }

            $score = ($totalQuestions > 0 && $totalPossibleScore > 0) ? ($correctAnswers / $totalQuestions) * $totalPossibleScore : 0;

            $quizTitle = $quiz->title_km ?? $quiz->title_en ?? 'N/A';
            $courseTitle = $quiz->courseOffering->course->title_km ?? 'N/A';

            return (object)[
                'type' => 'quiz',
                'course_title_km' => $courseTitle,
                'assessment_type' => 'Quiz: ' . $quizTitle,
                'score' => round($score, 2),
                'max_score' => $totalPossibleScore,
                'date' => $quiz->updated_at,
            ];
        })->filter();

        $allGrades = $allGrades->concat($quizGrades);

        $allGrades = $allGrades->sortByDesc('date');

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allGrades->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
        $grades = new LengthAwarePaginator($currentItems, $allGrades->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'pageName' => 'gradesPage',
        ]);

        return view('student.my-grades', compact('user', 'grades'));
    }


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
    public function myAttendance()
    {
        $user = Auth::user();
        $attendances = AttendanceRecord::where('student_user_id', $user->id)
                                             ->with('courseOffering.course')
                                             ->orderBy('date', 'desc')
                                             ->paginate(10);

        return view('student.my-attendance', compact('user', 'attendances'));
    }

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

        // Check if the student is already enrolled in this course offering
        $existingEnrollment = StudentCourseEnrollment::where('student_user_id', $user->id)
            ->where('course_offering_id', $courseOfferingId)
            ->first();

        if ($existingEnrollment) {
            Session::flash('info', 'អ្នកបានចុះឈ្មោះក្នុងវគ្គសិក្សានេះរួចហើយ។');
            return redirect()->back();
        }

        try {
            // Create the new enrollment record
            StudentCourseEnrollment::create([
                'student_user_id' => $user->id,
                'course_offering_id' => $courseOfferingId,
                'enrollment_date' => now(),
                'status' => 'enrolled',
            ]);
            Session::flash('success', 'ការចុះឈ្មោះដោយជោគជ័យ!');
        } catch (\Exception $e) {
            Session::flash('error', 'មានបញ្ហាក្នុងការចុះឈ្មោះ៖ ' . $e->getMessage());
        }

        // return redirect()->route('student.my-enrolled-courses');
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


    /**
     * បង្ហាញទំព័រសម្រាប់ធ្វើ Quiz ជាក់លាក់។
     * Display the page for taking a specific quiz.
     *
     * @param int $quiz_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function takeQuiz($quiz_id)
    {
        $user = Auth::user();

        $quiz = Quiz::where('id', $quiz_id)
                    ->whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                        $query->where('student_user_id', $user->id);
                    })
                    ->with(['quizQuestions.quizOptions'])
                    ->firstOrFail();

        // ពិនិត្យមើលថាតើ Quiz ត្រូវបានបើកហើយឬនៅ និងមិនទាន់ចប់
        // Check if the quiz is open and not ended
        if (now()->lt($quiz->start_date)) {
            Session::flash('info', 'Quiz នេះមិនទាន់បើកនៅឡើយទេ។');
            return redirect()->back();
        }
        if (now()->gt($quiz->end_date)) {
            Session::flash('info', 'Quiz នេះបានបិទហើយ។');
            return redirect()->back();
        }

        // ពិនិត្យមើលថាតើសិស្សបានឆ្លើយ Quiz នេះរួចហើយឬនៅ
        // Check if the student has already submitted this quiz
        $hasSubmitted = StudentQuizResponse::whereHas('quizQuestion', function ($query) use ($quiz_id) {
                                                $query->where('quiz_id', $quiz_id);
                                            })
                                            ->where('student_user_id', $user->id)
                                            ->exists();

        if ($hasSubmitted) {
            Session::flash('info', 'អ្នកបានឆ្លើយ Quiz នេះរួចហើយ។');
            return redirect()->route('student.my-quizzes'); // បញ្ជូនទៅទំព័រ Quiz របស់ខ្ញុំ
        }

        return view('student.take-quiz', compact('user', 'quiz'));
    }

    /**
     * គ្រប់គ្រងការដាក់ស្នើចម្លើយ Quiz របស់សិស្ស។
     * Handles the submission of student quiz answers.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $quiz_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitQuiz(Request $request, $quiz_id)
    {
        $user = Auth::user();

        $quiz = Quiz::where('id', $quiz_id)
                    ->whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
                        $query->where('student_user_id', $user->id);
                    })
                    ->with('quizQuestions.quizOptions')
                    ->firstOrFail();

        // ពិនិត្យមើលថាតើ Quiz ត្រូវបានបើកហើយឬនៅ និងមិនទាន់ចប់
        // Check if the quiz is open and not ended
        if (now()->lt($quiz->start_date) || now()->gt($quiz->end_date)) {
            Session::flash('error', 'Quiz នេះមិនអាចដាក់ស្នើបានទេ ដោយសារវាមិនមែនជារយៈពេលដែលបានកំណត់ទេ។');
            return redirect()->route('student.my-quizzes');
        }

        // ពិនិត្យមើលថាតើសិស្សបានដាក់ស្នើ Quiz នេះរួចហើយឬនៅ
        // Check if the student has already submitted this quiz
        $hasSubmitted = StudentQuizResponse::whereHas('quizQuestion', function ($query) use ($quiz_id) {
                                                $query->where('quiz_id', $quiz_id);
                                            })
                                            ->where('student_user_id', $user->id)
                                            ->exists();

        if ($hasSubmitted) {
            Session::flash('info', 'អ្នកបានដាក់ស្នើ Quiz នេះរួចហើយ។');
            return redirect()->route('student.my-quizzes');
        }

        $correctAnswersCount = 0;
        foreach ($quiz->quizQuestions as $question) {
            $submittedAnswerId = $request->input('question_' . $question->id);

            // ស្វែងរកជម្រើសត្រឹមត្រូវ
            // Find the correct option
            $correctOption = $question->quizOptions->first(fn($option) => $option->is_correct);

            $isCorrect = ($correctOption && $correctOption->id == $submittedAnswerId); // កែតម្រូវ spelling: $correctOpt=ion->id ទៅ $correctOption->id

            StudentQuizResponse::create([
                'student_user_id' => $user->id,
                'quiz_question_id' => $question->id,
                'quiz_option_id' => $submittedAnswerId, // អាច null ប្រសិនបើសិស្សមិនបានឆ្លើយ
                'is_correct' => $isCorrect,
            ]);

            if ($isCorrect) {
                $correctAnswersCount++;
            }
        }

        // Optionally, update a `quiz_attempts` table or `student_quiz_scores` to store the overall score for the quiz.
        // For simplicity, we are just storing individual responses here.
        // Example:
        // StudentQuizScore::updateOrCreate(
        //     ['student_user_id' => $user->id, 'quiz_id' => $quiz->id],
        //     ['score' => $correctAnswersCount, 'total_questions' => $quiz->quizQuestions->count()]
        // );

        Session::flash('success', 'Quiz របស់អ្នកត្រូវបានដាក់ស្នើដោយជោគជ័យ!');
        return redirect()->route('student.my-quizzes');
    }



    /**
     * Display a list of courses the student has enrolled in.
     * This method fetches the enrolled courses using pagination.
     */
     public function myEnrolledCourses()
    {
        $user = Auth::user();
        

        // ស្វែងរក Program ដែលសិស្សបានចុះឈ្មោះហើយ
        $studentProgram = null;
        $studentProgramEnrollment = StudentProgramEnrollment::where('student_user_id', $user->id)
            ->where('status', 'active')
            ->with('program')
            ->first();

        if ($studentProgramEnrollment) {
            $studentProgram = $studentProgramEnrollment->program;
        }

        // ទាញយកមុខវិជ្ជាដែលសិស្សបានចុះឈ្មោះជាមួយនឹង pagination
        $enrollments = StudentCourseEnrollment::where('student_user_id', $user->id)
            ->with('courseOffering.course', 'courseOffering.lecturer','courseOffering.lecturer.userProfile')
            ->paginate(10);

        return view('student.my-enrolled-courses', compact('user', 'enrollments', 'studentProgram'));
    }
    public function rooms()
{
    $rooms = Room::all();
    return view('student.rooms.index', compact('rooms'));
}




    
}
