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
            'course_offering_id' => $qrData->course_offering_id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'notes' => 'QR Scan',
        ]);

        return response()->json(['success' => true, 'message' => 'វត្តមានត្រូវបានកត់ត្រា!']);
    }
}