<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Room;
use App\Models\Program;    
use App\Models\Department;
use App\Models\User;
use App\Models\StudentProfile;

use Illuminate\Validate\Rule;
use Illuminate\Facades\DB;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $room = Room::all();
        $courses = Course::with('department', 'program')->paginate(10);
        return view('admin.courses.index', compact('courses','room'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $departments = Department::all();
        $programs = Program::all();
        $generations = User::select('generation')->distinct()->pluck('generation')->filter()->all();
        return view('admin.courses.create', compact('departments', 'programs', 'generations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_km' => 'nullable|string',
            'description_en' => 'nullable|string',
            'credits' => 'required|numeric|min:0.5',
            'department_id' => 'required|exists:departments,id',
            'program_id' => 'required|exists:programs,id', // Changed from nullable to required
            'generation' => 'nullable|string|max:255',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.manage-courses')->with('success', 'មុខវិជ្ជាត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $departments = Department::all();
        $programs = Program::all();
        $generations = StudentProfile::select('generation')->distinct()->pluck('generation')->filter()->all();
        return view('admin.courses.edit', compact('course', 'departments', 'programs','generations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            // 'code' => ['required', 'string', 'max:20', Rule::unique('courses')->ignore($course->id)],
            'title_km' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_km' => 'nullable|string',
            'description_en' => 'nullable|string',
            'credits' => 'required|numeric|min:0.5',
            'department_id' => 'required|exists:departments,id',
            'program_id' => 'required|exists:programs,id', // Changed from nullable to required
             'generation' => 'nullable|string|max:255',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.manage-courses')->with('success', 'មុខវិជ្ជាត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete(); // Cascade delete will handle related course offerings, etc.
        return redirect()->route('admin.manage-courses')->with('success', 'មុខវិជ្ជាត្រូវបានលុបដោយជោគជ័យ');
    }
}
