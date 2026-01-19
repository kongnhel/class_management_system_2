<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRecord;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ១. រាប់ចំនួនវត្តមាន
        $totalPresent = AttendanceRecord::where('student_user_id', $userId)->where('status', 'present')->count();
        
        // ២. រាប់ចំនួនអវត្តមាន
        $totalAbsent = AttendanceRecord::where('student_user_id', $userId)->where('status', 'absent')->count();
        
        // ៣. រាប់ចំនួនសុំច្បាប់
        $totalPermission = AttendanceRecord::where('student_user_id', $userId)->where('status', 'permission')->count();

        // ៤. ទាញយកប្រវត្តិ (Optional)
        $recentHistory = AttendanceRecord::where('student_user_id', $userId)->latest()->take(5)->get();

        // ⚠️ សំខាន់បំផុត៖ ត្រូវមាន compact() នេះទើប View ស្គាល់ variable
        return view('student.dashboard', compact('totalPresent', 'totalAbsent', 'totalPermission', 'recentHistory'));
    }
}