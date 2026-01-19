<?php
namespace App\Livewire\Teacher;

use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\AttendanceQrToken;
use App\Models\StudentCourseEnrollment;
use App\Models\AttendanceRecord;
use Illuminate\Support\Str;

class AttendanceModal extends Component
{
    public $isOpen = false;
    public $courseId;
    public $qrCodeImage;
    public $showConfirmation = false;

    protected $listeners = ['openAttendanceModal' => 'open'];

    public function open($courseOfferingId)
    {
        $this->courseId = $courseOfferingId;
        $this->isOpen = true;
        $this->generateToken();
    }

    public function close()
    {
        $this->isOpen = false;
        $this->courseId = null;
    }

    public function generateToken()
    {
        if ($this->isOpen && $this->courseId) {
            // លុប Token ចាស់ៗចោល ដើម្បីកុំឱ្យ Database ពេញ
            AttendanceQrToken::where('course_offering_id', $this->courseId)->delete();

            $token = Str::random(40);
            
            AttendanceQrToken::create([
                'course_offering_id' => $this->courseId,
                'token_code' => $token,
                'expires_at' => now()->addSeconds(60), // R មានសុពលភាព ២០ វិនាទី
            ]);

            // បង្កើត QR
            $this->qrCodeImage = (string) QrCode::size(300)->generate($token);
        }
    }

    public function closeAttendance()
    {
        if (!$this->courseId) return;

        $today = now()->toDateString();

        // ១. ទាញយកសិស្សទាំងអស់ដែលរៀនថ្នាក់នេះ (Enrolled Students)
        $enrolledStudents = StudentCourseEnrollment::where('course_offering_id', $this->courseId)
                            ->pluck('student_user_id');

        $absentCount = 0;

        foreach ($enrolledStudents as $studentId) {
            // ២. ឆែកមើលថា តើសិស្សនេះមានទិន្នន័យថ្ងៃនេះឬនៅ? (Present, Absent, ឬ Permission)
            $hasRecord = AttendanceRecord::where('student_user_id', $studentId)
                            ->where('course_offering_id', $this->courseId)
                            ->where('date', $today)
                            ->exists();

            // ៣. បើអត់ទាន់មានអ្វីសោះ => មានន័យថាគាត់អវត្តមានហើយ
            if (!$hasRecord) {
                AttendanceRecord::create([
                    'student_user_id' => $studentId,
                    'user_id'         => $studentId,
                    'course_offering_id' => $this->courseId,
                    'date' => $today,
                    'status' => 'absent',       // ដាក់ថា អវត្តមាន
                    'remarks' => 'System Auto-Absent', // ចំណាំថាប្រព័ន្ធដាក់ឱ្យ
                ]);
                $absentCount++;
            }
        }

        // ៤. បិទ Modal ហើយជូនដំណឹង
        $this->showConfirmation = false;
        $this->isOpen = false;
        
        // បងអាចប្រើ Session flash ឬ Dispatch event ដើម្បីប្រាប់ថាជោគជ័យ
        session()->flash('success', "ការស្រង់វត្តមានត្រូវបានបញ្ចប់! សិស្ស $absentCount នាក់ត្រូវបានដាក់ថាអវត្តមាន។");
        
        // បើបងប្រើ SweetAlert ឬចង់ Reload
        return redirect()->route('professor.dashboard'); 
    }

public function render()
{
    $attendances = [];

    if ($this->isOpen && $this->courseId) {
        $attendances = AttendanceRecord::where('course_offering_id', $this->courseId)
                        ->where('date', now()->toDateString()) // ✅ កែមកប្រើ 'date' វិញ
                        ->with('student')
                        ->orderBy('created_at', 'desc')
                        ->get();
    }

    return view('professor.attendance.attendance-modal', [  
        'attendances' => $attendances
    ]);
}
}