<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        // ទាញយកការជូនដំណឹងទាំងអស់
        $notifications = $student->notifications()->latest()->paginate(10);

        return view('student.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $student = Auth::user();
        $notification = $student->notifications()->findOrFail($id);

        $notification->markAsRead();

        return back()->with('success', 'បានអានការជូនដំណឹង!');
    }

    public function markAllAsRead()
    {
        $student = Auth::user();
        $student->unreadNotifications->markAsRead();

        return back()->with('success', 'បានអានការជូនដំណឹងទាំងអស់!');
    }
    
}
