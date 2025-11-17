<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class DepartmentController extends Controller
{
     public function index()
    {
        // This is the crucial part that fetches departments and passes them to the view
        $departments = Department::with('faculty', 'head')->paginate(10); // Fetch departments with their associated faculty and head
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $faculties = Faculty::all(); // Fetch all faculties to assign to a department
        $professors = User::where('role', 'professor')->get(); // Fetch professors to assign as head
        return view('admin.departments.create', compact('faculties', 'professors'));
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_km' => 'required|string|max:255|unique:departments',
            'name_en' => 'required|string|max:255|unique:departments',
            'faculty_id' => 'required|exists:faculties,id',
            'head_user_id' => 'nullable|exists:users,id',
        ]);

        Department::create($request->all());

        return redirect()->route('admin.manage-departments')->with('success', 'ដេប៉ាតឺម៉ង់ត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        $faculties = Faculty::all();
        $professors = User::where('role', 'professor')->get();
        return view('admin.departments.edit', compact('department', 'faculties', 'professors'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name_km' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)],
            'name_en' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)],
            'faculty_id' => 'required|exists:faculties,id',
            'head_user_id' => 'nullable|exists:users,id',
        ]);

        $department->update($request->all());

        return redirect()->route('admin.manage-departments')->with('success', 'ដេប៉ាតឺម៉ង់ត្រូវបានធ្វើបច្ចុបន្បភាពដោយជោគជ័យ');
    }


    public function getDepartmentsByFaculty($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)
            ->select('id', 'name_km', 'name_en')
            ->get();

        return response()->json($departments);
    }


      public function destroy(Department $department)
    {
        try {
            DB::beginTransaction();

            // 1. Get all programs associated with this department.
            $programs = $department->programs()->get();

            // 2. Loop through each program and delete its children (e.g., courses) first.
            foreach ($programs as $program) {
                $program->courses()->delete();
            }

            // 3. Now that all programs' children are gone, delete the programs.
            $department->programs()->delete();

            // 4. Finally, delete the department itself.
            $department->delete();

            DB::commit();

            return redirect()->route('admin.manage-departments')->with('success', 'ដេប៉ាតឺម៉ង់និងទិន្នន័យពាក់ព័ន្ធទាំងអស់ត្រូវបានលុបដោយជោគជ័យ។');

        } catch (\Exception $e) {
            DB::rollBack();
            // Logging the error message for debugging purposes
            \Log::error('Error deleting department: ' . $e->getMessage());
            return redirect()->route('admin.manage-departments')->with('error', 'មិនអាចលុបដេប៉ាតឺម៉ង់បានទេ៖ មានបញ្ហាមួយបានកើតឡើង។');
        }
    }
}
