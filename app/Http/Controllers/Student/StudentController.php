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

// myEnrolledCourses

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

    $todayOfferingIds = \App\Models\Schedule::where('day_of_week', $todayName)
        ->pluck('course_offering_id');
 
    $enrolledCourses = CourseOffering::whereIn('id', $todayOfferingIds) // ✅ Filter យកតែមុខវិជ្ជាមានរៀនថ្ងៃនេះ
        ->whereHas('students', function($query) use ($studentId) {
            $query->where('student_user_id', $studentId);
        })
        ->with(['course', 'lecturer', 'studentCourseEnrollments' => function($query) use ($studentId) {
            $query->where('student_user_id', $studentId);
        }])
        ->get()
        ->map(function ($offering) use ($studentId, $todayDate) {
            $record = \App\Models\AttendanceRecord::where('student_user_id', $studentId)
                        ->where('course_offering_id', $offering->id)
                        ->where('date', $todayDate)
                        ->first();

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
        
        // ឆែកក្នុងតារាង Pivot (course_offering_program)
        ->whereHas('targetPrograms', function ($query) use ($user) {
            $query->where('program_id', $user->program_id)      // ត្រូវនឹងជំនាញសិស្ស
                  ->where('generation', $user->generation);     // ត្រូវនឹងជំនាន់សិស្ស
        })
        
        // លក្ខខណ្ឌបន្ថែម (មិនទាន់ផុតកំណត់, មិនទាន់ចុះឈ្មោះ)
        ->where('end_date', '>=', now())
        ->whereNotIn('id', $enrolledCourseOfferingIds) // $enrolledCourseOfferingIds បានពីកូដចាស់
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
        'totalPresent',
        'totalAbsent',
        'totalPermission',
        'totalLate',
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
