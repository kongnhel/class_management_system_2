<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;

use App\Models\StudentCourseEnrollment;
use App\Models\AttendanceRecord;
use App\Models\Schedule;
use App\Models\ExamResult;
use App\Models\Program;
use App\Models\Course;
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


class StudentAttendanceController extends Controller
{
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
}
