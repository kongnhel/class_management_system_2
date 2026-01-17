<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Program;
use App\Models\Course;;
use App\Models\CourseOffering;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Storage; 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Intervention\Image\ImageManagerStatic as Image;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Fetch some statistics for the dashboard
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalProfessors = User::where('role', 'professor')->count();
        $totalFaculties = Faculty::count();
        $totalDepartments = Department::count();
        $totalPrograms = Program::count();
        $totalCourses = Course::count();
        $totalCourseOfferings = CourseOffering::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalProfessors',
            'totalFaculties',
            'totalDepartments',
            'totalPrograms',
            'totalCourses',
            'totalCourseOfferings'
        ));
    }

  
}

