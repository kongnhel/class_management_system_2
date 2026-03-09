<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Course;
use App\Models\Program;
use App\Models\User;
use App\Models\Room;

use App\Exports\CourseStudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CourseOfferingController extends Controller
{
    const LECTURER_FK_COLUMN = 'lecturer_user_id';

public function index(Request $request)
{
    // ១. ចាប់ផ្ដើម Query ជាមួយ Relationship
    $query = CourseOffering::query()
        ->with(['course', 'targetPrograms', 'lecturer', 'schedules.room']) 
        ->withCount('studentCourseEnrollments');

    // ២. Filter ស្វែងរកតាមអត្ថបទ (Search)
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

    // ៣. Filter តាមសាស្ត្រាចារ្យ
    if ($request->filled('lecturer_id')) {
        $query->where('lecturer_user_id', $request->input('lecturer_id'));
    }

    // ៤. Filter តាមកម្មវិធីសិក្សា (Pivot Table)
    if ($request->filled('program_id')) {
        $query->whereHas('targetPrograms', function($q) use ($request) {
            $q->where('program_id', $request->input('program_id'));
        });
    }

    // ៥. Filter តាមជំនាន់ (Generation) ក្នុង Pivot Table
    if ($request->filled('generation')) {
        $query->whereHas('targetPrograms', function($q) use ($request) {
            // ប្រើឈ្មោះតារាងឱ្យចំដើម្បីកុំឱ្យ Error
            $q->where('course_offering_program.generation', '=', $request->input('generation'));
        });
    }

    // ៦. Filter តាមឆមាស
    if ($request->filled('semester')) {
        $query->where('semester', '=', $request->input('semester'));
    }
 // 🔥🔥🔥 2. បន្ថែម Logic សម្រាប់ SHIFT នៅត្រង់នេះ 🔥🔥🔥
    if ($request->filled('shift')) {
        $shift = $request->shift;
        // ប្រើ whereHas ដើម្បីឆែកចូលទៅក្នុង table 'schedules'
        $query->whereHas('schedules', function ($q) use ($shift) {
            if ($shift === 'weekend') {
                // បើ user រើស Weekend, យកតែ record ណាដែលមានថ្ងៃ សៅរ៍ ឬ អាទិត្យ
                $q->whereIn('day_of_week', ['Saturday', 'Sunday']);
            } elseif ($shift === 'weekday') {
                // បើ user រើស Weekday, យកតែ record ណាដែលមានថ្ងៃ ចន្ទ-សុក្រ
                $q->whereIn('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
            }
        });
    }
    $courseOfferings = $query->orderBy('academic_year', 'desc')
    ->orderBy('semester', 'desc')
    ->paginate(50)
    ->appends($request->query());

// Add the "is_active" status to each item
$courseOfferings->getCollection()->transform(function ($offering) {
    // Logic: Active if current date is between start and end date
    $offering->is_active = now()->between($offering->start_date, $offering->end_date);
    return $offering;
});

// $courseOfferings = $query->orderBy('academic_year', 'desc')
//                          ->orderBy('semester', 'desc')
//                          ->paginate(50)
//                          ->appends($request->query());
    // ៨. រៀបចំទិន្នន័យសម្រាប់ Dropdowns
    $programs = Program::orderBy('name_km')->get();
    
    $academicYears = CourseOffering::select('academic_year')
        ->distinct()
        ->orderBy('academic_year', 'desc')
        ->pluck('academic_year');

    $assignedLecturerIds = CourseOffering::distinct()->pluck('lecturer_user_id')->filter()->unique();
    $lecturers = User::whereIn('id', $assignedLecturerIds)
        ->where('role', 'professor')
        ->orderBy('name')
        ->get(['id', 'name']);

    // ៩. បញ្ជូនទិន្នន័យទៅកាន់ View
    return view('admin.course-offerings.index', compact(
        'courseOfferings', 
        'programs', 
        'academicYears', 
        'lecturers'
    ));
}

public function create()
    {
        // $courses = Course::all();
        $courses = Course::with('programs')->get();
        $professors = User::where('role', 'professor')->get();
        $programs = Program::all();
        $rooms = Room::all();
        // $generations អាចនឹងមិនត្រូវការនៅទីនេះទេ ព្រោះយើងនឹងបញ្ចូលតាម Program នីមួយៗ
        
        return view('admin.course-offerings.create', compact('courses', 'professors', 'programs', 'rooms'));
    }

public function store(Request $request)
{
    // 1. Define Validation Rules
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'course_id' => 'required|exists:courses,id',
        'lecturer_user_id' => 'required|exists:users,id',
        'academic_year' => 'required|string|max:255',
        'semester' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'is_open_for_self_enrollment' => 'boolean',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        
        'target_programs' => 'required|array|min:1',
        'target_programs.*.program_id' => 'required|exists:programs,id|distinct', // បន្ថែម distinct
        'target_programs.*.generation' => 'required|string|max:255',

        'schedules' => 'required|array|min:1',
        'schedules.*.day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'schedules.*.room_id' => 'required|exists:rooms,id',
        'schedules.*.start_time' => 'required|date_format:H:i',
        'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
    ], [
        'target_programs.required' => 'សូមជ្រើសរើសជំនាញ និងជំនាន់យ៉ាងហោចណាស់មួយ។',
    ]);

    // 2. Conflict Checks
    $validator->after(function ($validator) use ($request) {
        $schedules = $request->input('schedules', []);
        $lecturerId = $request->input('lecturer_user_id');
        $academicYear = $request->input('academic_year');
        $semester = $request->input('semester');

        if (!is_array($schedules)) return;

        foreach ($schedules as $index => $current) {
            $day = $current['day_of_week'] ?? null;
            $start = $current['start_time'] ?? null;
            $end = $current['end_time'] ?? null;
            $roomId = $current['room_id'] ?? null;

            if (!$day || !$start || !$end) continue;

            // --- CHECK 0: Internal Conflict (Check ជាន់គ្នាឯងក្នុង Request) ---
            foreach ($schedules as $innerIndex => $compare) {
                if ($index === $innerIndex) continue;
                if ($day === ($compare['day_of_week'] ?? '') && 
                    $start < ($compare['end_time'] ?? '') && 
                    $end > ($compare['start_time'] ?? '')) {
                    $validator->errors()->add("schedules.$index", "ម៉ោងដែលអ្នកបញ្ចូលមកមានការជាន់គ្នាឯងក្នុងបញ្ជីខាងលើ។");
                }
            }

            // Standard Overlap Query Logic (កែសម្រួលឱ្យមិនទើសនាទីគ្នា)
            $overlapQuery = function ($q) use ($start, $end) {
                $q->where(function ($query) use ($start, $end) {
                    $query->where('start_time', '<', $end)
                          ->where('end_time', '>', $start);
                });
            };

            // --- CHECK A: Room Conflict ---
            $roomConflict = \App\Models\Schedule::where('day_of_week', $day)
                ->where('room_id', $roomId)
                // ក្នុង store() មិនចាំបាច់ប្រើ where('id', '!=', ...) ទេ ព្រោះជាការបង្កើតថ្មី
                ->whereHas('courseOffering', function ($q) use ($academicYear, $semester) {
                    $q->where('academic_year', $academicYear)
                      ->where('semester', $semester);
                })
                ->where($overlapQuery)
                ->exists();

            if ($roomConflict) {
                $validator->errors()->add("schedules.$index.room_id", "បន្ទប់នេះជាប់រវល់ហើយ នៅថ្ងៃ $day ចន្លោះម៉ោង $start - $end");
            }

            // --- CHECK B: Lecturer Conflict ---
            $lecturerConflict = \App\Models\Schedule::where('day_of_week', $day)
                ->whereHas('courseOffering', function ($q) use ($lecturerId, $academicYear, $semester) {
                    $q->where('lecturer_user_id', $lecturerId)
                      ->where('academic_year', $academicYear)
                      ->where('semester', $semester);
                })
                ->where($overlapQuery)
                ->exists();

            if ($lecturerConflict) {
                $validator->errors()->add("lecturer_user_id", "សាស្ត្រាចារ្យនេះជាប់បង្រៀនថ្នាក់ផ្សេងហើយ នៅថ្ងៃ {$day} ម៉ោង {$start} - {$end}។");
            }
        }
    });

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $validated = $validator->validated();

    try {
        \Illuminate\Support\Facades\DB::beginTransaction();

        // 3. Create Course Offering
        $courseOffering = CourseOffering::create([
            'course_id' => $validated['course_id'],
            'lecturer_user_id' => $validated['lecturer_user_id'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'capacity' => $validated['capacity'],
            'is_open_for_self_enrollment' => $request->boolean('is_open_for_self_enrollment'),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // 4. Save Programs & AUTO ENROLL STUDENTS
        foreach ($validated['target_programs'] as $prog) {
            $courseOffering->targetPrograms()->attach($prog['program_id'], [
                'generation' => $prog['generation']
            ]);

            $students = User::where('role', 'student')
                ->where('program_id', $prog['program_id'])
                ->where('generation', $prog['generation'])
                ->get();

            foreach ($students as $student) {
                \App\Models\StudentCourseEnrollment::firstOrCreate([
                    'student_user_id' => $student->id,
                    'course_offering_id' => $courseOffering->id,
                ], [
                    'student_id' => $student->id,
                    'enrollment_date' => now(),
                    'status' => 'enrolled',
                ]);
            }
        }

        // 5. Create Schedules
        foreach ($validated['schedules'] as $scheduleData) {
            $courseOffering->schedules()->create($scheduleData);
        }

        \Illuminate\Support\Facades\DB::commit();

        return redirect()->route('admin.manage-course-offerings')
            ->with('success', 'ការផ្តល់ជូនមុខវិជ្ជាត្រូវបានបង្កើតដោយជោគជ័យ និងបានបញ្ចូលឈ្មោះសិស្សរួចរាល់!');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        \Illuminate\Support\Facades\Log::error('Error creating course offering: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'មានបញ្ហាក្នុងការបង្កើត៖ ' . $e->getMessage())
            ->withInput();
    }
}

    public function edit(CourseOffering $courseOffering)
    {
        // Load relationship
        $courseOffering->load('targetPrograms', 'schedules');
        $courses = Course::all();

        $programs = Program::all();
        $lecturers = User::where('role', 'professor')->get();
        $rooms = Room::all();
        $selectedCourse = Course::find($courseOffering->course_id);

        return view('admin.course-offerings.edit', compact(
            'courseOffering',
            'programs',
            'lecturers',
            'rooms',
            'selectedCourse',
            'courses',
        ));
    }

public function update(Request $request, CourseOffering $courseOffering)
{
    // 1. Define Validation Rules
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'course_id' => 'required|exists:courses,id',
        'lecturer_user_id' => 'required|exists:users,id',
        'academic_year' => 'required|string|max:255',
        'semester' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'is_open_for_self_enrollment' => 'boolean',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',

        // បន្ថែម distinct ដើម្បីកុំឱ្យបញ្ចូល Program ជាន់គ្នា
        'target_programs' => 'required|array|min:1',
        'target_programs.*.program_id' => 'required|exists:programs,id|distinct',
        'target_programs.*.generation' => 'required|string|max:255',

        'schedules' => 'required|array|min:1',
        'schedules.*.day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'schedules.*.room_id' => 'required|exists:rooms,id',
        'schedules.*.start_time' => 'required|date_format:H:i',
        'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
    ]);

    $validator->after(function ($validator) use ($request, $courseOffering) {
        $schedules = $request->input('schedules', []);
        $lecturerId = $request->input('lecturer_user_id');
        $academicYear = $request->input('academic_year');
        $semester = $request->input('semester');

        if (!is_array($schedules)) return;

        foreach ($schedules as $index => $current) {
            $day = $current['day_of_week'] ?? null;
            $start = $current['start_time'] ?? null;
            $end = $current['end_time'] ?? null;
            $roomId = $current['room_id'] ?? null;

            if (!$day || !$start || !$end) continue;

            // --- CHECK 0: Internal Conflict (Check ជាន់គ្នាឯងក្នុង Request) ---
            foreach ($schedules as $innerIndex => $compare) {
                if ($index === $innerIndex) continue;
                if ($day === ($compare['day_of_week'] ?? '') && 
                    $start < ($compare['end_time'] ?? '') && 
                    $end > ($compare['start_time'] ?? '')) {
                    $validator->errors()->add("schedules.$index", "ម៉ោងដែលអ្នកបញ្ចូលមកមានការជាន់គ្នាឯងក្នុងបញ្ជីខាងលើ។");
                }
            }

            // Standard Overlap Query Logic
            $overlapQuery = function ($q) use ($start, $end) {
                $q->where(function ($query) use ($start, $end) {
                    $query->where('start_time', '<', $end)
                          ->where('end_time', '>', $start);
                });
            };

            // --- CHECK A: Room Conflict ---
            $roomConflict = \App\Models\Schedule::where('day_of_week', $day)
                ->where('room_id', $roomId)
                ->where('course_offering_id', '!=', $courseOffering->id)
                ->whereHas('courseOffering', function ($q) use ($academicYear, $semester) {
                    $q->where('academic_year', $academicYear)
                      ->where('semester', $semester);
                })
                ->where($overlapQuery)
                ->exists();

            if ($roomConflict) {
                $validator->errors()->add("schedules.$index.room_id", "បន្ទប់នេះជាប់រវល់ហើយ នៅថ្ងៃ $day ចន្លោះម៉ោង $start - $end");
            }

            // --- CHECK B: Lecturer Conflict ---
            $lecturerConflict = \App\Models\Schedule::where('day_of_week', $day)
                ->whereHas('courseOffering', function ($q) use ($lecturerId, $academicYear, $semester, $courseOffering) {
                    $q->where('lecturer_user_id', $lecturerId)
                      ->where('academic_year', $academicYear)
                      ->where('semester', $semester)
                      ->where('id', '!=', $courseOffering->id);
                })
                ->where($overlapQuery)
                ->exists();

            if ($lecturerConflict) {
                $validator->errors()->add("lecturer_user_id", "សាស្ត្រាចារ្យនេះជាប់បង្រៀនថ្នាក់ផ្សេងហើយ នៅថ្ងៃ {$day} ម៉ោង {$start} - {$end}។");
            }
        }
    });

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $validated = $validator->validated();

    try {
        \Illuminate\Support\Facades\DB::beginTransaction();

        // 3. Update Main Table
        $courseOffering->update([
            'course_id' => $validated['course_id'],
            'lecturer_user_id' => $validated['lecturer_user_id'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'capacity' => $validated['capacity'],
            'is_open_for_self_enrollment' => $request->boolean('is_open_for_self_enrollment'),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // 4. Sync Programs
        $syncData = [];
        foreach ($validated['target_programs'] as $prog) {
            $syncData[$prog['program_id']] = ['generation' => $prog['generation']];
        }
        $courseOffering->targetPrograms()->sync($syncData);

        // 5. Update Schedules (Delete & Re-create)
        $courseOffering->schedules()->delete();
        foreach ($validated['schedules'] as $scheduleData) {
            $courseOffering->schedules()->create($scheduleData);
        }

        \Illuminate\Support\Facades\DB::commit();

        return redirect()->route('admin.manage-course-offerings')
            ->with('success', 'ការផ្តល់ជូនមុខវិជ្ជាត្រូវបានកែប្រែដោយជោគជ័យ!');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        \Illuminate\Support\Facades\Log::error('Error updating course offering: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'មានបញ្ហា៖ ' . $e->getMessage())
            ->withInput();
    }
}
    public function destroy(CourseOffering $courseOffering)
    {
        try {
            DB::beginTransaction();
            // Detach programs first (optional due to cascade, but good practice)
            $courseOffering->targetPrograms()->detach();
            $courseOffering->schedules()->delete();
            $courseOffering->studentCourseEnrollments()->delete();
            $courseOffering->delete();
            DB::commit();

            Session::flash('success', 'ការផ្តល់ជូនមុខវិជ្ជាត្រូវបានលុបដោយជោគជ័យ។');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'មានបញ្ហាក្នុងការលុប៖ ' . $e->getMessage());
        }
        return redirect()->route('admin.manage-course-offerings');
    }

    public function show(CourseOffering $courseOffering)
    {
        $courseOffering->load([
            'course', 
            'targetPrograms', // Load Programs
            'lecturer.profile', 
            'schedules.room', 
            'studentCourseEnrollments.student.profile'
        ]);

        return view('admin.course-offerings.show', compact('courseOffering'));
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

public function exportStudents($offering_id)
{
    // ឈ្មោះ File ដែលនឹងធ្លាក់មក៖ students_list_course_123.xlsx
    return Excel::download(new CourseStudentsExport($offering_id), 'students_list_course_' . $offering_id . '.xlsx');
}
}
