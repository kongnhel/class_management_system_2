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
use App\Models\StudentProgramEnrollment;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Notification as NotificationFacade; 
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Storage;
use App\Exports\GradebookExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Configuration\Configuration;

class ProfessorNotificationController extends Controller
{
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
}
