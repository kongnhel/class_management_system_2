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
// public function dashboard()
// {
//     $user = Auth::user();
//     $studentId = $user->id;
//     $todayName = now()->format('l');
//     $todayDate = now()->toDateString();



//     // 1. ទាញយកមុខវិជ្ជាដែលនិស្សិតបានចុះឈ្មោះរួច (Enrolled Courses)
//     $enrolledCourses = CourseOffering::whereHas('students', function($query) use ($studentId) {
//         $query->where('student_user_id', $studentId);
//     })->with(['course', 'lecturer', 'studentCourseEnrollments' => function($query) use ($studentId) {
//         $query->where('student_user_id', $studentId);
//     }])
//     ->withCount('studentCourseEnrollments') 
//     ->get();

//     // 2. ទាញយកទិន្នន័យ Enrollment លម្អិត (សម្រាប់ Progress ឬ Status)
//     $enrollments = StudentCourseEnrollment::where('student_user_id', $user->id)
//                     ->with('courseOffering.course', 'courseOffering.lecturer')
//                     ->get();

//     // 3. ទាញយកកិច្ចការ ការប្រឡង និង QUIZ ដែលជិតមកដល់
//     $upcomingAssignments = Assignment::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
//             $query->where('student_user_id', $user->id);
//         })
//         ->whereDate('due_date', '>=', now()->toDateString()) 
//         ->orderBy('due_date', 'asc')
//         ->take(5) // បន្ថែម Take 5 ដូចមុន
//         ->get();

//     $upcomingExams = Exam::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
//             $query->where('student_user_id', $user->id);
//         })
//         ->whereDate('exam_date', '>=', now()->toDateString()) 
//         ->orderBy('exam_date', 'asc')
//         ->take(5)
//         ->get();

//     $upcomingQuizzes = \App\Models\Quiz::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
//             $query->where('student_user_id', $user->id);
//         })
//         ->whereDate('quiz_date', '>=', now()->toDateString()) 
//         ->orderBy('quiz_date', 'asc')
//         ->take(5)
//         ->get();

//     // Schedule ថ្ងៃនេះ (ទាញយក Room និង Lecturer មកជាមួយ)
//     $upcomingSchedules = Schedule::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($studentId) {
//             $query->where('student_user_id', $studentId);
//         })
//         ->with(['room', 'courseOffering.course', 'courseOffering.lecturer'])
//         ->where('day_of_week', $todayName)
//         ->orderBy('start_time', 'asc')
//         ->get();

//     // 4. ព័ត៌មានអំពីកម្មវិធីសិក្សា និង មុខវិជ្ជាដែលអាចចុះឈ្មោះបាន
//     $studentProgram = null;
//     $studentProgramEnrollment = \App\Models\StudentProgramEnrollment::where('student_user_id', $user->id)
//         ->where('status', 'active')
//         ->with('program')
//         ->first();

//     if ($studentProgramEnrollment) {
//         $studentProgram = $studentProgramEnrollment->program;
//     }

//     $availableCoursesInProgram = collect([]);
//     if ($studentProgram) {
//         $enrolledCourseOfferingIds = StudentCourseEnrollment::where('student_user_id', $user->id)
//             ->pluck('course_offering_id');

//         $studentGeneration = $user->generation;
        
//         $availableCoursesInProgram = CourseOffering::with(['course', 'lecturer'])
//             ->withCount('studentCourseEnrollments')
//             ->whereHas('course', function ($query) use ($studentProgram) {
//                 $query->where('program_id', $studentProgram->id);
//             })
//             ->whereNotIn('id', $enrolledCourseOfferingIds)
//             ->where('end_date', '>=', now())
//             ->where('generation', $studentGeneration)
//             ->get();
//     }

//     // 5. Statistics
//     $completedCoursesCount = StudentCourseEnrollment::where('student_user_id', $user->id)
//         ->where('status', 'completed')
//         ->count();

//     $totalCoursesInProgram = $studentProgram ? $studentProgram->courses->count() : 0;

//     // 6. Announcements & Notifications (Feed) - រួមបញ្ចូល Logic បកប្រែភាសា និង is_read
// $allAnnouncements = Announcement::where('target_role', 'all')
//     ->orWhere('target_role', 'student')
//     ->with(['poster', 'reads' => function($query) use ($user) {
//         $query->where('user_id', $user->id);
//     }])
//     ->orderBy('created_at', 'desc')
//     ->get()
//     ->map(function ($announcement) {
//         $announcement->type = 'announcement';
//         $announcement->title = $announcement->title_km ?? $announcement->title_en;
//         $announcement->content = $announcement->content_km ?? $announcement->content_en;
//         $announcement->is_read = $announcement->reads->isNotEmpty();
        
//         // ទាញយកឈ្មោះអ្នកបង្ហោះចេញពី Relationship 'poster'
//         $announcement->sender_name = $announcement->poster->name ?? __('រដ្ឋបាលសាលា');
//         return $announcement;
//     });

//     // $allNotifications = $user->notifications->map(function ($notification) {
//     //     $notification->type = 'notification';
//     //     $notification->title = $notification->data['title'] ?? 'ការជូនដំណឹងថ្មី';
//     //     $notification->content = $notification->data['message'] ?? 'អ្នកមានការជូនដំណឹងថ្មី។';
//     //     $notification->is_read = $notification->read_at !== null;
//     //     return $notification;
//     // });
//     $allNotifications = $user->notifications->map(function ($notification) {
//     $notification->type = 'notification';
    
//     // ទាញយកទិន្នន័យពី JSON field 'data'
//     $data = $notification->data; 
    
//     $notification->title = $data['title'] ?? 'ការជូនដំណឹងថ្មី';
//     $notification->content = $data['message'] ?? 'អ្នកមានការជូនដំណឹងថ្មី។';
    
//     // --- ចំណុចសំខាន់៖ បន្ថែមឈ្មោះគ្រូបង្រៀន ---
//     $notification->sender_name = $data['from_user_name'] ?? 'ប្រព័ន្ធ';
    
//     $notification->is_read = $notification->read_at !== null;
//     return $notification;
// });

//     $combinedFeed = $allAnnouncements->merge($allNotifications)->sortByDesc('created_at');

//     // 7. បញ្ជូនទិន្នន័យទៅ View
//     return view('student.dashboard', compact(
//         'user',
//         'enrolledCourses',
//         'enrollments',
//         'upcomingAssignments',
//         'upcomingExams',
//         'upcomingQuizzes',
//         'upcomingSchedules',
//         'studentProgram',
//         'availableCoursesInProgram',
//         'completedCoursesCount',
//         'totalCoursesInProgram',
//         'combinedFeed',
//         'todayName'
//     ));
// }

public function dashboard()
{
    $user = Auth::user();
    $studentId = $user->id;
    $todayName = now()->format('l');
    $todayDate = now()->toDateString();

    // --- 0. ស្ថិតិវត្តមាន (Attendance Stats) ---
    // (សម្រាប់បង្ហាញលើកាតខាងលើ៖ វត្តមាន, អវត្តមាន, ច្បាប់)
    $totalPresent = \App\Models\AttendanceRecord::where('student_user_id', $studentId)->where('status', 'present')->count();
    $totalAbsent = \App\Models\AttendanceRecord::where('student_user_id', $studentId)->where('status', 'absent')->count();
    $totalPermission = \App\Models\AttendanceRecord::where('student_user_id', $studentId)->where('status', 'permission')->count();
    $totalLate = \App\Models\AttendanceRecord::where('student_user_id', $studentId)->where('status', 'late')->count();


    // --- 1. ទាញយកមុខវិជ្ជា + ឆែកស្ថានភាពវត្តមានថ្ងៃនេះ (UPDATED) ---
    $enrolledCourses = CourseOffering::whereHas('students', function($query) use ($studentId) {
        $query->where('student_user_id', $studentId);
    })->with(['course', 'lecturer', 'studentCourseEnrollments' => function($query) use ($studentId) {
        $query->where('student_user_id', $studentId);
    }])
    ->withCount('studentCourseEnrollments') 
    ->get()
    // 👉 កែសម្រួល៖ ឆែកមើលថាថ្ងៃនេះបានស្កែនឬនៅ? ដើម្បីបង្ហាញ Badge
    ->map(function ($offering) use ($studentId, $todayDate) {
        $record = \App\Models\AttendanceRecord::where('student_user_id', $studentId)
                    ->where('course_offering_id', $offering->id)
                    ->where('date', $todayDate)
                    ->first();

        // ដាក់ status (present, absent, etc.) ចូលក្នុង object
        $offering->today_status = $record ? $record->status : null;
        return $offering;
    });


    // 2. ទាញយកទិន្នន័យ Enrollment លម្អិត (សម្រាប់ Progress ឬ Status)
    $enrollments = StudentCourseEnrollment::where('student_user_id', $user->id)
                    ->with('courseOffering.course', 'courseOffering.lecturer')
                    ->get();

    // 3. ទាញយកកិច្ចការ ការប្រឡង និង QUIZ ដែលជិតមកដល់
    $upcomingAssignments = Assignment::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
            $query->where('student_user_id', $user->id);
        })
        ->whereDate('due_date', '>=', $todayDate) 
        ->orderBy('due_date', 'asc')
        ->take(5)
        ->get();

    $upcomingExams = Exam::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
            $query->where('student_user_id', $user->id);
        })
        ->whereDate('exam_date', '>=', $todayDate) 
        ->orderBy('exam_date', 'asc')
        ->take(5)
        ->get();

    $upcomingQuizzes = \App\Models\Quiz::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($user) {
            $query->where('student_user_id', $user->id);
        })
        ->whereDate('quiz_date', '>=', $todayDate) 
        ->orderBy('quiz_date', 'asc')
        ->take(5)
        ->get();

    // Schedule ថ្ងៃនេះ (ទាញយក Room និង Lecturer មកជាមួយ)
    $upcomingSchedules = Schedule::whereHas('courseOffering.studentCourseEnrollments', function ($query) use ($studentId) {
            $query->where('student_user_id', $studentId);
        })
        ->with(['room', 'courseOffering.course', 'courseOffering.lecturer'])
        ->where('day_of_week', $todayName)
        ->orderBy('start_time', 'asc')
        ->get();

    // 4. ព័ត៌មានអំពីកម្មវិធីសិក្សា និង មុខវិជ្ជាដែលអាចចុះឈ្មោះបាន
    $studentProgram = null;
    $studentProgramEnrollment = \App\Models\StudentProgramEnrollment::where('student_user_id', $user->id)
        ->where('status', 'active')
        ->with('program')
        ->first();

    if ($studentProgramEnrollment) {
        $studentProgram = $studentProgramEnrollment->program;
    }

    $availableCoursesInProgram = collect([]);
    if ($studentProgram) {
        $enrolledCourseOfferingIds = StudentCourseEnrollment::where('student_user_id', $user->id)
            ->pluck('course_offering_id');

        $studentGeneration = $user->generation;
        
        $availableCoursesInProgram = CourseOffering::with(['course', 'lecturer'])
            ->withCount('studentCourseEnrollments')
            ->whereHas('course', function ($query) use ($studentProgram) {
                $query->where('program_id', $studentProgram->id);
            })
            ->whereNotIn('id', $enrolledCourseOfferingIds)
            ->where('end_date', '>=', now())
            ->where('generation', $studentGeneration)
            ->get();
    }

    // 5. Statistics
    $completedCoursesCount = StudentCourseEnrollment::where('student_user_id', $user->id)
        ->where('status', 'completed')
        ->count();

    $totalCoursesInProgram = $studentProgram ? $studentProgram->courses->count() : 0;

    // 6. Announcements & Notifications (Feed)
    $allAnnouncements = Announcement::where('target_role', 'all')
        ->orWhere('target_role', 'student')
        ->with(['poster', 'reads' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($announcement) {
            $announcement->type = 'announcement';
            $announcement->title = $announcement->title_km ?? $announcement->title_en;
            $announcement->content = $announcement->content_km ?? $announcement->content_en;
            $announcement->is_read = $announcement->reads->isNotEmpty();
            $announcement->sender_name = $announcement->poster->name ?? __('រដ្ឋបាលសាលា');
            return $announcement;
        });

    $allNotifications = $user->notifications->map(function ($notification) {
        $notification->type = 'notification';
        $data = $notification->data; 
        
        $notification->title = $data['title'] ?? 'ការជូនដំណឹងថ្មី';
        $notification->content = $data['message'] ?? 'អ្នកមានការជូនដំណឹងថ្មី។';
        $notification->sender_name = $data['from_user_name'] ?? 'ប្រព័ន្ធ';
        $notification->is_read = $notification->read_at !== null;
        return $notification;
    });

    $combinedFeed = $allAnnouncements->merge($allNotifications)->sortByDesc('created_at');

    // 7. បញ្ជូនទិន្នន័យទៅ View
    return view('student.dashboard', compact(
        'user',
        // Attendance Stats
        'totalPresent',
        'totalAbsent',
        'totalPermission',
        'totalLate',
        // Courses with Status
        'enrolledCourses',
        'enrollments',
        'upcomingAssignments',
        'upcomingExams',
        'upcomingQuizzes',
        'upcomingSchedules',
        'studentProgram',
        'availableCoursesInProgram',
        'completedCoursesCount',
        'totalCoursesInProgram',
        'combinedFeed',
        'todayName'
    ));
}
    /**
     * បង្ហាញផ្ទាំងគ្រប់គ្រងសម្រាប់សិស្ស។
     * Display the dashboard for the student.
     */

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

    // room
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
// public function myGrades(Request $request)
// {
//     $user = Auth::user();
    
//     // --- ១. ទាញយកពិន្ទុពីគ្រប់ប្រភេទ (Assignment, Exam, Quiz) ចេញពីតារាង ExamResult ---
//     // នេះជាកន្លែងដែលទិន្នន័យ Import ពី Excel របស់អ្នកស្ថិតនៅ
//     $examResults = \App\Models\ExamResult::where('student_user_id', $user->id)
//         ->get()
//         ->map(function ($result) {
//             $assessment = null;
//             $typeLabel = '';

//             // ឆែករក Assessment ឱ្យចំប្រភេទដើម្បីទាញយក Course Title និង Max Score
//             if ($result->assessment_type === 'assignment') {
//                 $assessment = \App\Models\Assignment::with('courseOffering.course')->find($result->assessment_id);
//                 $typeLabel = 'កិច្ចការ: ';
//             } elseif ($result->assessment_type === 'quiz') {
//                 $assessment = \App\Models\Quiz::with('courseOffering.course')->find($result->assessment_id);
//                 $typeLabel = 'កម្រងសំណួរ: ';
//             } else {
//                 $assessment = \App\Models\Exam::with('courseOffering.course')->find($result->assessment_id);
//                 $typeLabel = 'ការប្រឡង: ';
//             }

//             return (object)[
//                 'assessment_id'   => $result->assessment_id,
//                 'type_category'   => $result->assessment_type, // 'assignment', 'exam', 'quiz'
//                 'course_title_en' => $assessment->courseOffering->course->title_en ?? 'Unknown Course',
//                 'course_title_km' => $assessment->courseOffering->course->title_km ?? 'មិនស្គាល់មុខវិជ្ជា',
//                 'assessment_type' => $typeLabel . ($assessment->title_en ?? 'N/A'),
//                 'score'           => (float) $result->score_obtained,
//                 'max_score'       => (float) ($assessment->max_score ?? 0),
//                 'date'            => $result->updated_at,
//             ];
//         });

//     // --- ២. ទាញយកពិន្ទុពីតារាង Submission (ករណីគ្រូដាក់ពិន្ទុតាមប្រព័ន្ធផ្ទាល់ មិនមែន Import) ---
//     $submissionGrades = \App\Models\Submission::where('student_user_id', $user->id)
//         ->whereNotNull('grade_received')
//         ->with(['assignment.courseOffering.course'])
//         ->get()
//         ->map(function ($submission) {
//             return (object)[
//                 'assessment_id'   => $submission->assignment_id,
//                 'type_category'   => 'assignment',
//                 'course_title_en' => $submission->assignment->courseOffering->course->title_en ?? 'Unknown Course',
//                 'course_title_km' => $submission->assignment->courseOffering->course->title_km ?? 'មិនស្គាល់មុខវិជ្ជា',
//                 'assessment_type' => 'កិច្ចការ: ' . ($submission->assignment->title_en ?? 'N/A'),
//                 'score'           => (float) $submission->grade_received,
//                 'max_score'       => (float) ($submission->assignment->max_score ?? 0),
//                 'date'            => $submission->updated_at,
//             ];
//         });

//     // បញ្ចូលទិន្នន័យចូលគ្នា (ការពារកុំឱ្យមានទិន្នន័យជាន់គ្នា ប្រសិនបើមានទាំងក្នុង Submission និង ExamResult)
//     // ក្នុងករណីនេះ យើងយកទិន្នន័យពី ExamResult ជាអាទិភាព (ករណី Import Excel)
//     $allGrades = $examResults->concat($submissionGrades)->unique(function ($item) {
//         return $item->type_category . $item->assessment_id;
//     });

//     // --- ៣. គណនា Grade និង Rank ---
//     $gradedItems = $allGrades->map(function ($item) {
//         $item->grade = $this->calculateGrade($item->score, $item->max_score);
        
//         // គណនា Rank តាមប្រភេទ Assessment នីមួយៗ
//         if ($item->type_category == 'assignment' && !\App\Models\ExamResult::where('assessment_id', $item->assessment_id)->where('assessment_type', 'assignment')->exists()) {
//              $higherScores = \App\Models\ExamResult::where('assignment_id', $item->assessment_id)
//                 ->where('score_obtained', '>', $item->score)->count();
//         } else {
//             $higherScores = \App\Models\ExamResult::where('assessment_id', $item->assessment_id)
//                 ->where('assessment_type', $item->type_category)
//                 ->where('score_obtained', '>', $item->score)->count();
//         }
//         $item->rank = $higherScores + 1;
//         return $item;
//     })->sortByDesc('course_title_en');

//     // --- ៤. គណនា Overall Rank & Average Score ---
//     $averageScore = $allGrades->avg('score') ?? 0;
//     $averageMax   = $allGrades->avg('max_score') ?: 100;
//     $overallGrade = $this->calculateGrade($averageScore, $averageMax);

//     // Logic Ranking សាមញ្ញសម្រាប់ Overall (ផ្អែកលើមធ្យមភាគ)
//     $overallRank = 1; // អ្នកអាចបន្ថែម Logic Ranking កម្រិត Course ទីនេះ

//     // --- ៥. Pagination & UI Colors ---
//     $perPage = 10;
//     $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
//     $grades = new \Illuminate\Pagination\LengthAwarePaginator(
//         $gradedItems->slice(($currentPage - 1) * $perPage, $perPage)->values(),
//         $gradedItems->count(), $perPage, $currentPage, ['path' => $request->url()]
//     );

//     $colorPalette = [
//         ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'hover' => 'hover:bg-blue-50/50', 'accent' => 'bg-blue-500'],
//         ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'border-indigo-100', 'hover' => 'hover:bg-indigo-50/50', 'accent' => 'bg-indigo-500'],
//         ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100', 'hover' => 'hover:bg-purple-50/50', 'accent' => 'bg-purple-500'],
//         ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'hover' => 'hover:bg-rose-50/50', 'accent' => 'bg-rose-500'],
//     ];

//     $grades->getCollection()->transform(function ($grade, $key) use ($colorPalette) {
//         $colorIndex = $key % count($colorPalette);
//         $grade->ui = (object) $colorPalette[$colorIndex];
//         return $grade;
//     });
    
//     return view('student.my-grades', compact('user', 'grades', 'overallRank', 'averageScore', 'overallGrade'));
// }
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
public function myAttendance()
{
    $user = Auth::user();

    $attendances = AttendanceRecord::where('student_user_id', $user->id)
        ->with(['courseOffering.course'])
        // រាប់ចំនួនដងដែលអវត្តមានក្នុងមុខវិជ្ជានីមួយៗ
        ->withCount(['courseOffering as total_absent' => function ($query) use ($user) {
            $query->whereHas('attendanceRecords', function ($q) use ($user) {
                $q->where('student_user_id', $user->id)
                  ->whereIn('status', ['absent', 'អវត្តមាន']);
            });
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('student.my-attendance', compact('user', 'attendances'));
}
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

    // public function enrollSelf(Request $request)
    // {
    //     $request->validate([
    //         'course_offering_id' => 'required|exists:course_offerings,id',
    //     ]);

    //     $user = Auth::user();
    //     $courseOfferingId = $request->input('course_offering_id');

    //     // Check if the student is already enrolled in this course offering
    //     $existingEnrollment = StudentCourseEnrollment::where('student_user_id', $user->id)
    //         ->where('course_offering_id', $courseOfferingId)
    //         ->first();

    //     if ($existingEnrollment) {
    //         Session::flash('info', 'អ្នកបានចុះឈ្មោះក្នុងវគ្គសិក្សានេះរួចហើយ។');
    //         return redirect()->back();
    //     }

    //     try {
    //         // Create the new enrollment record
    //         StudentCourseEnrollment::create([
    //             'student_user_id' => $user->id,
    //             'course_offering_id' => $courseOfferingId,
    //             'enrollment_date' => now(),
    //             'status' => 'enrolled',
    //         ]);
    //         Session::flash('success', 'ការចុះឈ្មោះដោយជោគជ័យ!');
    //     } catch (\Exception $e) {
    //         Session::flash('error', 'មានបញ្ហាក្នុងការចុះឈ្មោះ៖ ' . $e->getMessage());
    //     }

    //     // return redirect()->route('student.my-enrolled-courses');
    //     return redirect()->route('student.dashboard');
    // }

    // enrolledCourses
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

    // ១. ស្វែងរក Program ដែលសិស្សបានចុះឈ្មោះ (រក្សានៅដដែល)
    $studentProgramEnrollment = StudentProgramEnrollment::where('student_user_id', $user->id)
        ->where('status', 'active')
        ->with('program')
        ->first();
        

    $studentProgram = $studentProgramEnrollment ? $studentProgramEnrollment->program : null;

    // ២. ទាញយកមុខវិជ្ជាដែលបានចុះឈ្មោះ (ត្រូវប្រាកដថាទាញយក is_class_leader ពីតារាង enrollment)
    $enrollments = StudentCourseEnrollment::where('student_user_id', $user->id)
        ->with([
            'courseOffering.course', 
            'courseOffering.lecturer',
            'courseOffering.lecturer.userProfile',
            'courseOffering.lecturer.studentProfile' // ករណីសាស្ត្រាចារ្យមាន Profile ក្នុងតារាង Student
        ])
        ->paginate(10);

    // បញ្ជូន variable $enrollments ទៅកាន់ view
    return view('student.my-enrolled-courses', compact('user', 'enrollments', 'studentProgram'));
}

    public function rooms()
{
    $rooms = Room::all();
    return view('student.rooms.index', compact('rooms'));
}


public function leaderAttendance($courseOfferingId)
{
    // ត្រូវប្រើ student_user_id ជានិច្ច
    $isLeader = DB::table('student_course_enrollments')
        ->where('course_offering_id', $courseOfferingId)
        ->where('student_user_id', auth()->id()) 
        ->where('is_class_leader', 1)
        ->exists();

    if (!$isLeader) {
        // បើចូលមកដល់នេះ ហើយលោត 403 មានន័យថា is_class_leader ក្នុង DB នៅតែជា 0
        abort(403, 'អ្នកមិនមែនជាប្រធានថ្នាក់សម្រាប់មុខវិជ្ជានេះទេ។');
    }

    // កូដសម្រាប់បង្ហាញទំព័រស្រង់វត្តមាន...

    $courseOffering = CourseOffering::with('students.studentProfile')->findOrFail($courseOfferingId);
    $students = $courseOffering->students;
    $today = now()->format('Y-m-d');

    return view('student.leader.attendance', compact('courseOffering', 'students', 'today'));
}

public function storeLeaderAttendance(Request $request, $courseOfferingId)
{
    // ១. ឆែកមើលម្ដងទៀតថាគាត់ជាប្រធានថ្នាក់ពិតមែនឬអត់ (ដើម្បីសុវត្ថិភាព)
    $isLeader = DB::table('student_course_enrollments')
        ->where('course_offering_id', $courseOfferingId)
        ->where('student_user_id', auth()->id())
        ->where('is_class_leader', 1)
        ->exists();

    if (!$isLeader) {
        abort(403);
    }

    // ២. ទទួលទិន្នន័យវត្តមានពី Form (ឧទាហរណ៍៖ $request->attendance)
    $attendances = $request->input('attendance'); 
    $date = now()->format('Y-m-d');

    foreach ($attendances as $studentUserId => $status) {
        DB::table('attendances')->updateOrInsert(
            [
                'course_offering_id' => $courseOfferingId,
                'student_user_id' => $studentUserId,
                'date' => $date
            ],
            [
                'status' => $status,
                'updated_at' => now()
            ]
        );
    }

    return redirect()->back()->with('success', 'រក្សាទុកវត្តមានបានជោគជ័យ!');
}
// myEnrolledCourses
public function leaderAttendanceReport($courseOfferingId)
{
    // ១. ទាញយកព័ត៌មាន Course
    $courseOffering = CourseOffering::with('course')->findOrFail($courseOfferingId);

    // ២. ឆែកសិទ្ធិ (រក្សាទុកកូដដដែលរបស់អ្នក)
    $isLeader = DB::table('student_course_enrollments')
        ->where('student_user_id', auth()->id())
        ->where('course_offering_id', $courseOfferingId)
        ->where('is_class_leader', 1)
        ->exists();

    if (!$isLeader) {
        abort(403, 'អ្នកមិនមានសិទ្ធិចូលមើលរបាយការណ៍នេះទេ។');
    }

    // ៣. ទាញយកបញ្ជីសិស្ស និង counts
    $students = User::whereHas('enrolledCourses', function($query) use ($courseOfferingId) {
            $query->where('course_offering_id', $courseOfferingId);
        })
        // បន្ថែម Eager Loading សម្រាប់ CourseOffering ដើម្បីបង្ហាញក្នុងតារាង
        ->with(['enrolledCourses' => function($q) use ($courseOfferingId) {
            $q->where('course_offering_id', $courseOfferingId)->with('course');
        }])
        ->withCount([
            'attendances as present_count' => function ($query) use ($courseOfferingId) {
                $query->where('course_offering_id', $courseOfferingId)->where('status', 'present');
            },
            'attendances as absent_count' => function ($query) use ($courseOfferingId) {
                $query->where('course_offering_id', $courseOfferingId)->where('status', 'absent');
            },
            'attendances as permission_count' => function ($query) use ($courseOfferingId) {
                $query->where('course_offering_id', $courseOfferingId)->where('status', 'permission');
            },
            'attendances as late_count' => function ($query) use ($courseOfferingId) {
                $query->where('course_offering_id', $courseOfferingId)->where('status', 'late');
            }
        ])
        ->get();

    return view('student.leader.report', compact('courseOffering', 'students'));
}

public function getAttendanceScore($studentId, $courseOfferingId)
{
    // ១. រាប់ចំនួនអវត្តមានសរុប (Absents) របស់និស្សិតក្នុងមុខវិជ្ជានោះ
    $absentCount = \App\Models\Attendance::where('student_user_id', $studentId)
        ->where('course_offering_id', $courseOfferingId)
        ->where('status', 'absent') // យកតែអ្នកអវត្តមាន
        ->count();

    // ២. គណនាពិន្ទុ (ឈប់ ២ដង ដក ១ពិន្ទុ)
    $maxScore = 15;
    $deduction = floor($absentCount / 2); // ប្រើ floor ដើម្បីយកចំនួនគត់
    $finalScore = $maxScore - $deduction;

    // ការពារកុំឱ្យពិន្ទុធ្លាក់ក្រោម ០
    return $finalScore < 0 ? 0 : $finalScore;
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




}

// enroll