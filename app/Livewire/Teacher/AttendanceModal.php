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
            
            // ១. លុប Token ចាស់ៗចោល (ល្អហើយ)
            AttendanceQrToken::where('course_offering_id', $this->courseId)->delete();

            // ២. បង្កើត Token ថ្មី
            $token = Str::random(40);
            
            AttendanceQrToken::create([
                'course_offering_id' => $this->courseId,
                'token_code' => $token,
                'expires_at' => now()->addSeconds(10), // ✅ ១៥ វិនាទី
            ]);

            // ៣. បង្កើតរូបភាព QR
            $this->qrCodeImage = (string) QrCode::size(300)
                                    ->margin(2)
                                    ->generate($token);
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
        
        AttendanceQrToken::where('course_offering_id', $this->courseId)->delete();
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
        $courseName = '...'; // ឈ្មោះលំនាំដើម

    if ($this->courseId) {
        $courseOffering = \App\Models\CourseOffering::with('course')->find($this->courseId);
        // ត្រូវប្រាកដថា CourseOffering មាន relation ទៅ Course
        $courseName = $courseOffering ? ($courseOffering->course->title_en ?? 'N/A') : 'N/A';
        // បើចង់បានឈ្មោះភាសាខ្មែរ៖ $courseOffering->course->name_km ?? ...
    }
        // 🔥 ចំណុចសំខាន់នៅត្រង់នេះ! 🔥
        // យើងត្រូវឆែកមើលមុននឹងបង្កើតថ្មី
        if ($this->isOpen && $this->courseId) {
            
            $latestToken = AttendanceQrToken::where('course_offering_id', $this->courseId)
                            ->latest()
                            ->first();

            // លក្ខខណ្ឌ៖ បើ "អត់ទាន់មាន Token" ឬ "Token ចាស់ហួសម៉ោង" => ចាំបង្កើតថ្មី
            if (!$latestToken || now()->greaterThan($latestToken->expires_at)) {
                $this->generateToken(); 
            } 
            // បើមាន Token ហើយមិនទាន់ផុតកំណត់ តែរូបភាពបាត់ (Re-render) => បង្កើតរូបភាពឡើងវិញ
            elseif (!$this->qrCodeImage) {
                $this->qrCodeImage = (string) QrCode::size(300)
                                        ->margin(2)
                                        ->generate($latestToken->token_code);
            }
        }

        // ទាញយកបញ្ជីសិស្ស
        $attendances = [];
        if ($this->isOpen && $this->courseId) {
            $attendances = AttendanceRecord::where('course_offering_id', $this->courseId)
                            ->where('date', now()->toDateString())
                            ->with('student')
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('professor.attendance.attendance-modal', [
            'attendances' => $attendances,
            'courseName' => $courseName,
        ]);
    }
public function checkAndGenerateToken()
{
    // រកមើល Token ចុងក្រោយ
    $latestToken = AttendanceQrToken::where('course_offering_id', $this->courseId)
                    ->latest()
                    ->first();

    // លក្ខខណ្ឌ៖ បើអត់ទាន់មាន ឬ ផុតកំណត់ (ហួសម៉ោង) => បង្កើតថ្មី
    if (!$latestToken || now()->greaterThan($latestToken->expires_at)) {
        
        $newToken = \Illuminate\Support\Str::random(40);
        
        AttendanceQrToken::create([
            'course_offering_id' => $this->courseId,
            'token_code' => $newToken,
            'expires_at' => now()->addSeconds(15), // ✅ កំណត់អាយុ ១៥ វិនាទី
        ]);

        // បង្កើតរូបភាព QR ថ្មី
        $this->qrCodeImage = QrCode::size(300)->generate($newToken);
    
    } elseif (!$this->qrCodeImage) {
        // បើ Token នៅមានសុពលភាព តែរូបភាពបាត់ => បង្កើតរូបភាពឡើងវិញ
        $this->qrCodeImage = QrCode::size(300)->generate($latestToken->token_code);
    }
}
}