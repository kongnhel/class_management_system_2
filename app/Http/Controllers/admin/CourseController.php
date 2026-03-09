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
//     public function index()
//     {
//         // $room = Room::all();
//         // $courses = Course::with('department', 'program')->paginate(10);
//         $room = Room::all();

//         // ទាញយកទិន្នន័យមុខវិជ្ជាទាំងអស់ជាមួយ Department និង Program
// $coursesData = Course::with(['department', 'programs'])
//         ->orderBy('department_id')
//         ->get();

//         // ធ្វើការបែងចែកជាក្រុមធំតាម Department និងក្រុមតូចតាម Program
// $coursesGrouped = $coursesData->groupBy([
//         function ($item) {
//             // បែងចែកតាមឈ្មោះកម្មវិធីសិក្សា (Program)
//             return $item->program->name_km ?? 'មិនទាន់មានកម្មវិធីសិក្សា';
//         },
//         function ($item) {
//             // បែងចែកតាមជំនាន់ (Generation)
//             // ចំណាំ៖ បើ Column generation នៅក្នុង Table courses ផ្ទាល់ បងប្រើ $item->generation
//             return $item->generation ? 'ជំនាន់ទី ' . $item->generation : 'មិនទាន់កំណត់ជំនាន់';
//         },
//     ]);

//         return view('admin.courses.index', compact('coursesGrouped','coursesData', 'room'));
//     }
public function index()
{
    $room = Room::all();

    // Eager load 'programs' (plural)
    $coursesData = Course::with(['department', 'programs'])
        ->orderBy('department_id')
        ->get();

    // Re-structure data: if a course has multiple programs, it should appear in each group
    $flattenedCourses = $coursesData->flatMap(function ($course) {
        if ($course->programs->isEmpty()) {
            return [$course]; // Keep courses with no program for the 'N/A' group
        }
        
        return $course->programs->map(function ($program) use ($course) {
            $clone = clone $course;
            $clone->assigned_program_name = $program->name_km; // Temporary property for grouping
            return $clone;
        });
    });

    $coursesGrouped = $flattenedCourses->groupBy([
        function ($item) {
            return $item->assigned_program_name ?? 'មិនទាន់មានកម្មវិធីសិក្សា';
        },
        function ($item) {
            return $item->generation ? 'ជំនាន់ទី ' . $item->generation : 'មិនទាន់កំណត់ជំនាន់';
        },
    ]);

    return view('admin.courses.index', compact('coursesGrouped', 'room'));
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
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title_km' => 'required|string|max:255',
    //         'title_en' => 'required|string|max:255',
    //         'description_km' => 'nullable|string',
    //         'description_en' => 'nullable|string',
    //         'credits' => 'required|numeric|min:0.5',
    //         'department_id' => 'required|exists:departments,id',
            
    //         'program_id' => 'required|exists:programs,id', // Changed from nullable to required
    //         'generation' => 'nullable|string|max:255',
    //     ]);

    //     Course::create($request->all());

    //     return redirect()->route('admin.manage-courses')->with('success', 'មុខវិជ្ជាត្រូវបានបង្កើតដោយជោគជ័យ!');
    // }

    public function store(Request $request)
{
    // 1. Validation
    $request->validate([
        'title_km'       => 'required|string|max:255',
        'title_en'       => 'required|string|max:255',
        'description_km' => 'nullable|string',
        'description_en' => 'nullable|string',
        'credits'        => 'required|numeric|min:0.5',
        'department_id'  => 'required|exists:departments,id',
        'program_id'     => 'required|array|min:1', // Validate as an array
        'program_id.*'   => 'required|exists:programs,id',
        'generation'     => 'nullable|string|max:255',
    ]);

    // 2. Create the Course (excluding the program_id array)
    $course = Course::create($request->except('program_id'));

    // 3. Link multiple programs using the pivot table
    // This uses the relationship you defined in your Course.php model
    $course->programs()->sync($request->program_id);

    return redirect()->route('admin.manage-courses')
                     ->with('success', 'មុខវិជ្ជាត្រូវបានបង្កើតដោយជោគជ័យ!');
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
/**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $departments = Department::all();
        $programs = Program::all();
        
        // Fetch unique generations from StudentProfile
        $generations = StudentProfile::select('generation')
            ->distinct()
            ->pluck('generation')
            ->filter()
            ->all();

        // Get the IDs of programs currently linked to this course for the multi-select/checkboxes
        $selectedPrograms = $course->programs->pluck('id')->toArray();

        return view('admin.courses.edit', compact('course', 'departments', 'programs', 'generations', 'selectedPrograms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_km' => 'nullable|string',
            'description_en' => 'nullable|string',
            'credits' => 'required|numeric|min:0.5',
            'department_id' => 'required|exists:departments,id',
            'program_ids' => 'required|array', // Changed to array for many-to-many
            'program_ids.*' => 'exists:programs,id',
            'generation' => 'nullable|string|max:255',
        ]);

        // 1. Update the main course attributes
        $course->update($request->only([
            'title_km', 
            'title_en', 
            'description_km', 
            'description_en', 
            'credits', 
            'department_id', 
            'generation'
        ]));

        // 2. Sync the many-to-many relationship with programs
        // This will automatically add/remove entries in the 'course_program' table
        $course->programs()->sync($request->program_ids);

        return redirect()->route('admin.manage-courses')
            ->with('success', 'មុខវិជ្ជាត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ');
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
