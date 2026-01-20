<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Course;
use App\Models\Program;    
use App\Models\Department;
use App\Models\User;
use App\Models\Room;
use App\Models\StudentProfile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CourseOfferingController extends Controller
{

public function show(CourseOffering $courseOffering)

{
    // Load all necessary relationships for the details view

    $courseOffering->load([
        'course', 
        'program', 
        'lecturer.profile', 
        'schedules.room', 
        'studentCourseEnrollments.student.profile'
    ]);

    return view('admin.course-offerings.show', compact('courseOffering'));
}
const LECTURER_FK_COLUMN = 'lecturer_user_id';

    /**
     * Display a listing of all course offerings with filters and search.
     */
public function index(Request $request) // Add Request $request
{
    $query = CourseOffering::query()
        ->with(['course', 'program', 'lecturer', 'schedules']) // Eager load everything needed
        ->withCount('studentCourseEnrollments'); // Efficiently count students

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->whereHas('course', function($q2) use ($search) {
                $q2->where('title_km', 'LIKE', "%{$search}%")
                   ->orWhere('title_en', 'LIKE', "%{$search}%");
            })->orWhereHas('lecturer', function($q3) use ($search) {
                $q3->where('name', 'LIKE', "%{$search}%");
            });
        });
    }
if ($request->filled('lecturer_id')) { 
        $query->where(self::LECTURER_FK_COLUMN, $request->input('lecturer_id'));
    }
    // Apply program filter
    if ($request->filled('program_id')) {
        $query->where('program_id', $request->input('program_id'));
    }

    // Apply academic year filter
    if ($request->filled('academic_year')) {
        $query->where('academic_year', $request->input('academic_year'));
    }
    
    // Paginate the results and append query strings for filter persistence
    $courseOfferings = $query->orderBy('academic_year', 'desc')
                             ->orderBy('semester', 'desc')
                             ->paginate(10)
                             ->appends($request->query());

    // Fetch data for filter dropdowns
    $programs = Program::orderBy('name_km')->get();
    $academicYears = CourseOffering::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');


    $assignedLecturerIds = CourseOffering::distinct()->pluck(self::LECTURER_FK_COLUMN)->filter()->unique();
    
    // Fetch user records using the IDs AND filter by role 'professor'
    $lecturers = User::whereIn('id', $assignedLecturerIds)
        ->where('role', 'professor') 
        ->orderBy('name')
        ->get(['id', 'name']);

    return view('admin.course-offerings.index', compact('courseOfferings', 'programs', 'academicYears', 'lecturers'));
}

    /**
     * Show the form for creating a new course offering.
     */
    public function create()
    {
        $courses = Course::all();
        $professors = User::where('role', 'professor')->get();
        $programs = Program::all(); // 💡 បានបន្ថែម: ទាញយក Programs
        $rooms = Room::all(); // Fetch all rooms
        $generations = Course::select('generation')->distinct()->pluck('generation');

        return view('admin.course-offerings.create', compact('courses', 'professors', 'programs', 'rooms', 'generations')); // 💡 បញ្ជូន Programs ទៅ view
    }

public function getCoursesByProgramAndGeneration(Request $request)
{
    $request->validate([
        'program_id' => 'required|exists:programs,id',
        'generation' => 'required|string',
    ]);

    $courses = Course::where('program_id', $request->program_id)
        ->where('generation', $request->generation)
        ->get();

    return response()->json($courses);
}

    /**
     * Store a newly created course offering in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'program_id' => 'required|exists:programs,id',
        'course_id' => 'required|exists:courses,id',
        'lecturer_user_id' => 'required|exists:users,id',
        'academic_year' => 'required|string|max:255',
        'semester' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'is_open_for_self_enrollment' => 'boolean',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'schedules' => 'required|array|min:1',
        'schedules.*.day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'schedules.*.room_id' => 'required|exists:rooms,id',
        'schedules.*.start_time' => 'required|date_format:H:i',
        'schedules.*.end_time' => 'nullable|date_format:H:i|after:schedules.*.start_time',
        'generation' => 'required|string|max:255', // 💡 NEW: Validation for the generation field
    ], [
        'program_id.required' => 'កម្មវិធីសិក្សាត្រូវតែជ្រើសរើស។',
        'course_id.required' => 'មុខវិជ្ជាត្រូវតែជ្រើសរើស។',
        'lecturer_user_id.required' => 'សាស្រ្តាចារ្យត្រូវតែជ្រើសរើស។',
        'academic_year.required' => 'ឆ្នាំសិក្សាត្រូវតែបញ្ចូល។',
        'semester.required' => 'ឆមាសត្រូវតែបញ្ចូល។',
        'capacity.required' => 'ចំនួនអតិបរមាត្រូវតែបញ្ចូល។',
        'capacity.min' => 'ចំនួនអតិបរមាត្រូវតែយ៉ាងហោចណាស់ 1។',
        'start_date.required' => 'កាលបរិច្ឆេទចាប់ផ្តើមត្រូវតែបញ្ចូល។',
        'end_date.required' => 'កាលបរិច្ឆេទបញ្ចប់ត្រូវតែបញ្ចូល។',
        'end_date.after_or_equal' => 'កាលបរិច្ឆេទបញ្ចប់ត្រូវតែក្រោយ ឬស្មើនឹងកាលបរិច្ឆេទចាប់ផ្តើម។',
        'schedules.required' => 'ត្រូវតែមានកាលវិភាគយ៉ាងហោចណាស់មួយ។',
        'schedules.*.day_of_week.required' => 'ថ្ងៃនៃសប្តាហ៍ត្រូវតែជ្រើសរើស។',
        'schedules.*.room_id.required' => 'បន្ទប់ត្រូវតែជ្រើសរើសសម្រាប់កាលវិភាគ។',
        'schedules.*.room_id.exists' => 'បន្ទប់ដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        'schedules.*.start_time.required' => 'ម៉ោងចាប់ផ្តើមត្រូវតែបញ្ចូល។',
        'schedules.*.end_time.after' => 'ម៉ោងបញ្ចប់ត្រូវតែក្រោយម៉ោងចាប់ផ្តើម។',
        'generation.required' => 'ជំនាន់ត្រូវតែជ្រើសរើស។', // 💡 NEW: Custom validation message
    ]);

    try {
        DB::beginTransaction();

        // Create the course offering
        $courseOffering = CourseOffering::create([
            'program_id' => $validated['program_id'],
            'course_id' => $validated['course_id'],
            'lecturer_user_id' => $validated['lecturer_user_id'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'capacity' => $validated['capacity'],
            'is_open_for_self_enrollment' => $request->has('is_open_for_self_enrollment'),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'generation' => $validated['generation'], // 💡 NEW: Save the generation
        ]);

        // Create schedules for the course offering
        foreach ($validated['schedules'] as $scheduleData) {
            $courseOffering->schedules()->create([
                'day_of_week' => $scheduleData['day_of_week'],
                'room_id' => $scheduleData['room_id'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
            ]);
        }

        DB::commit();

        Session::flash('success', 'ការផ្តល់ជូនមុខវិជ្ជាត្រូវបានបង្កើតដោយជោគជ័យ!');
        return redirect()->route('admin.manage-course-offerings');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating course offering: ' . $e->getMessage());
        Session::flash('error', 'មានបញ្ហាក្នុងការបង្កើតការផ្តល់ជូនមុខវិជ្ជា។ សូមព្យាយាមម្តងទៀត។');
        return redirect()->back()->withInput();
    }
}
    
public function edit(CourseOffering $courseOffering)
{
    $programs = Program::all();
    $lecturers = User::where('role', 'professor')->get();
    $rooms = Room::all();
    $generations = Course::select('generation')->distinct()->pluck('generation');
    
    // Pass the selected course to the view as well, for pre-selection
    $selectedCourse = Course::find($courseOffering->course_id);

    return view('admin.course-offerings.edit', compact(
        'courseOffering',
        'programs',
        'lecturers',
        'rooms',
        'generations',
        'selectedCourse'
    ));
}

public function update(Request $request, CourseOffering $courseOffering)
{
    $validated = $request->validate([
        'program_id' => 'required|exists:programs,id',
        'course_id' => 'required|exists:courses,id',
        'lecturer_user_id' => 'required|exists:users,id',
        'academic_year' => 'required|string|max:255',
        'semester' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'is_open_for_self_enrollment' => 'boolean',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'schedules' => 'required|array|min:1',
        'schedules.*.day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'schedules.*.room_id' => 'required|exists:rooms,id',
        'schedules.*.start_time' => 'required|date_format:H:i',
        'schedules.*.end_time' => 'nullable|date_format:H:i|after:schedules.*.start_time',
        'generation' => 'required|string|max:255', // 💡 NEW: Validation for the generation field
    ]);

    try {
        DB::beginTransaction();

        $courseOffering->update([
            'program_id' => $validated['program_id'],
            'course_id' => $validated['course_id'],
            'lecturer_user_id' => $validated['lecturer_user_id'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'capacity' => $validated['capacity'],
            'is_open_for_self_enrollment' => $request->has('is_open_for_self_enrollment'),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'generation' => $validated['generation'], // 💡 NEW: Update the generation
        ]);

        $courseOffering->schedules()->delete();

        foreach ($validated['schedules'] as $scheduleData) {
            $courseOffering->schedules()->create([
                'day_of_week' => $scheduleData['day_of_week'],
                'room_id' => $scheduleData['room_id'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
            ]);
        }

        DB::commit();

        Session::flash('success', 'ការផ្តល់ជូនមុខវិជ្ជាត្រូវបានកែប្រែដោយជោគជ័យ!');
        return redirect()->route('admin.manage-course-offerings');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating course offering: ' . $e->getMessage());
        Session::flash('error', 'មានបញ្ហាក្នុងការកែប្រែការផ្តល់ជូនមុខវិជ្ជា។ សូមព្យាយាមម្តងទៀត។');
        return redirect()->back()->withInput();
    }
}

    /**
     * Remove the specified course offering from storage.
     * លុបការផ្តល់ជូនមុខវិជ្ជាដែលបានបញ្ជាក់ពីកន្លែងផ្ទុក។
     */
   public function destroy(CourseOffering $courseOffering)
    {
        try {
            DB::beginTransaction();

            // Delete all related schedules first.
            $courseOffering->schedules()->delete();

            // Delete all related student enrollments.
            // Using the correct relationship name found in your Blade view.
            $courseOffering->studentCourseEnrollments()->delete();

            // Finally, delete the course offering itself.
            $courseOffering->delete();

            DB::commit();

            Session::flash('success', 'ការផ្តល់ជូនមុខវិជ្ជាត្រូវបានលុបដោយជោគជ័យ។');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'មានបញ្ហាក្នុងការលុបការផ្តល់ជូនមុខវិជ្ជា៖ ' . $e->getMessage());
        }

        return redirect()->route('admin.manage-course-offerings');
    }





    public function enrollStudentForm()
    {
        // Fetch all students (users with 'student' role)
        $students = User::where('role', 'student')->orderBy('name')->get();

        // Fetch all available course offerings
        $courseOfferings = CourseOffering::with('course', 'lecturer')
            ->where('end_date', '>=', now()) // Only show courses that haven't ended
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('admin.enroll_student', compact('students', 'courseOfferings'));
    }

    /**
     * Handle the admin's request to enroll a student in a course offering.
     *
     * @param  \Illuminate->Http->Request  $request
     * @return \Illuminate->Http->RedirectResponse
     */

 public function performEnrollment(Request $request)
    {
        $request->validate([
            'student_user_id' => 'required|exists:users,id',
            'course_offering_id' => 'required|exists:course_offerings,id',
        ]);

        $studentUserId = $request->input('student_user_id');
        $courseOfferingId = $request->input('course_offering_id');

        // Check if student is already enrolled in this course offering
        $existingEnrollment = StudentCourseEnrollment::where('student_user_id', $studentUserId)
            ->where('course_offering_id', $courseOfferingId)
            ->first();

        if ($existingEnrollment) {
            Session::flash('info', 'សិស្សរូបនេះបានចុះឈ្មោះក្នុងវគ្គសិក្សានេះរួចហើយ។');
            return redirect()->back();
        }

        try {
            StudentCourseEnrollment::create([
                'student_user_id' => $studentUserId,
                'course_offering_id' => $courseOfferingId,
                'enrollment_date' => now(),
                'status' => 'enrolled', // Default status for admin enrollment
            ]);
            Session::flash('success', 'ការចុះឈ្មោះសិស្សដោយជោគជ័យ!');
        } catch (\Exception $e) {
            Session::flash('error', 'មានបញ្ហាក្នុងការចុះឈ្មោះសិស្ស៖ ' . $e->getMessage());
        }
        return redirect()->back();
    }
    public function getCoursesByProgram(Program $program)
{
    // Eager load the program's courses and select only necessary fields
    $courses = $program->courses()->select('id', 'code', 'title_km')->get();
    
    return response()->json($courses);
}
}
