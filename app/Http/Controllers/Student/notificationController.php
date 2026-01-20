<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use App\Models\Notification;
use App\Models\Announcement;
use App\Http\Controllers\Controller;
use App\Models\AnnouncementRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\DB; 

class notificationController extends Controller
{
        public function index()
    {
        $student = Auth::user();

        // ទាញយកការជូនដំណឹងទាំងអស់
        $notifications = $student->notifications()->latest()->paginate(10);

        return view('student.notifications.index', compact('notifications'));
    }

    // public function markAsRead($id)
    // {
    //     $student = Auth::user();
    //     $notification = $student->notifications()->findOrFail($id);

    //     $notification->markAsRead();

    //     return back()->with('success', 'បានអានការជូនដំណឹង!');
    // }

    public function markAllAsRead()
    {
        $student = Auth::user();
        $student->unreadNotifications->markAsRead();

        return back()->with('success', 'បានអានការជូនដំណឹងទាំងអស់!');
    }
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
}
