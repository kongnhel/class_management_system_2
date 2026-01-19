<?php
namespace App\Livewire\Teacher;

use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\AttendanceQrToken;
use Illuminate\Support\Str;

class AttendanceModal extends Component
{
    public $isOpen = false;
    public $courseId = null; // នេះគឺ course_offering_id
    public $qrCodeImage = null;

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

    public function render()
    {
        // ត្រូវនឹងឈ្មោះ Folder ដែលបងចង់បាន
        return view('professor.attendance.attendance-modal');
    }
}