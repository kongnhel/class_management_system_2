<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceQrToken;
use App\Models\AttendanceRecord; // Model របស់បង
use App\Models\StudentCourseEnrollment; // Model របស់បង
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{

    public function processScan(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        $user = Auth::user();

        // ១. ស្វែងរក Token
        $qrData = AttendanceQrToken::where('token_code', $request->token)->first();

        // ២. ឆែកសុពលភាព (ត្រូវមាន និងមិនទាន់ផុតកំណត់)
        if (!$qrData) {
            return response()->json(['success' => false, 'message' => 'QR Code មិនត្រឹមត្រូវ!']);
        }
        if (now()->greaterThan($qrData->expires_at)) {
            return response()->json(['success' => false, 'message' => 'QR Code ផុតកំណត់ហើយ!']);
        }

        // ៣. ឆែកមើលថាសិស្សមានឈ្មោះក្នុងថ្នាក់នេះអត់?
        $isEnrolled = StudentCourseEnrollment::where('student_user_id', $user->id)
                        ->where('course_offering_id', $qrData->course_offering_id)
                        ->exists();

        if (!$isEnrolled) {
            return response()->json(['success' => false, 'message' => 'បងគ្មានឈ្មោះក្នុងថ្នាក់នេះទេ!']);
        }

        // ៤. ឆែកមើលក្រែងលោស្កែនរួចហើយ (ការពារស្កែនស្ទួន)
        $alreadyChecked = AttendanceRecord::where('student_user_id', $user->id)
                            ->where('course_offering_id', $qrData->course_offering_id)
                            ->where('date', now()->toDateString())
                            ->exists();

        if ($alreadyChecked) {
            return response()->json(['success' => false, 'message' => 'បងបានស្កែនរួចរាល់ហើយ!']);
        }

        // ៥. កត់ត្រាវត្តមាន
        AttendanceRecord::create([
            'student_user_id' => $user->id,
            'user_id'         => $user->id,
            'course_offering_id' => $qrData->course_offering_id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'remarks' => 'QR Scan',
        ]);

        return response()->json(['success' => true, 'message' => 'វត្តមានត្រូវបានកត់ត្រា!']);
    }

    public function closeAttendance($courseOfferingId)
{
    $today = now()->toDateString();

    // ១. ទាញយកសិស្សទាំងអស់ដែលរៀនថ្នាក់នេះ (Enrolled Students)
    $enrolledStudents = \App\Models\StudentCourseEnrollment::where('course_offering_id', $courseOfferingId)
                        ->pluck('student_user_id'); // យកតែ ID សិស្ស

    // ២. ទាញយកសិស្សដែលបានស្កែនរួចនៅថ្ងៃនេះ
    $presentStudents = \App\Models\AttendanceRecord::where('course_offering_id', $courseOfferingId)
                        ->where('date', $today)
                        ->pluck('student_user_id');

    // ៣. រកសិស្សដែលបាត់មុខ (Enrolled - Present = Absent)
    $absentStudents = $enrolledStudents->diff($presentStudents);

    // ៤. បញ្ចូលទិន្នន័យ 'absent' ឱ្យពួកគាត់
    foreach ($absentStudents as $studentId) {
        \App\Models\AttendanceRecord::create([
            'student_user_id' => $studentId,
            'course_offering_id' => $courseOfferingId,
            'date' => $today,
            'status' => 'absent',
            'remarks' => 'Auto-generated (No Scan)',
        ]);
    }

    return back()->with('success', 'បញ្ជីវត្តមានត្រូវបានបិទ! អ្នកមិនបានស្កែនត្រូវបានដាក់ថា អវត្តមាន។');
}
}