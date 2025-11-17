<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    // public function manageFaculties().
    {
        $faculties = Faculty::with('dean')->paginate(10);
        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new faculty.
     */
    public function create()
    {
        $professors = User::where('role', 'professor')->get(); // Get all professors for assigning dean
        return view('admin.faculties.create', compact('professors'));
    }

    /**
     * Store a newly created faculty in storage.
     */
// ផ្នែកនៃ AdminController.php

public function store(Request $request)
// public function storeFaculty(Request $request)
{
    $request->validate([
        'name_km' => [
            'required',
            'string',
            'max:255',
            Rule::unique('faculties', 'name_km'),
        ],
        'name_en' => [
            'required',
            'string',
            'max:255',
            Rule::unique('faculties', 'name_en'),
        ],
        'dean_user_id' => 'nullable|exists:users,id',
    ]);

    Faculty::create($request->all());

    return redirect()->route('admin.manage-faculties')->with('success', 'មហាវិទ្យាល័យត្រូវបានបង្កើតដោយជោគជ័យ។');
}

    /**
     * Show the form for editing the specified faculty.
     */
    public function edit(Faculty $faculty)
    // public function editFaculty(Faculty $faculty)
    {
        $professors = User::where('role', 'professor')->get();
        return view('admin.faculties.edit', compact('faculty', 'professors'));
    }

    /**
     * Update the specified faculty in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'name_km' => ['required', 'string', 'max:255', Rule::unique('faculties')->ignore($faculty->id)],
            'name_en' => ['required', 'string', 'max:255', Rule::unique('faculties')->ignore($faculty->id)],
            'dean_user_id' => 'nullable|exists:users,id',
        ]);

        $faculty->update($request->all());

        return redirect()->route('admin.manage-faculties')->with('success', 'មហាវិទ្យាល័យត្រូវបានធ្វើបច្ចុប្បន្នដោយជោគជ័យ!');
    }
// $studen
    /**
     * Remove the specified faculty from storage.
     */
    // public function deleteFaculty(Faculty $faculty)
    // {
    //     $faculty->delete(); // Cascade delete will handle related departments, programs, etc.
    //     return redirect()->route('admin.manage-faculties')->with('success', 'Faculty deleted successfully.');
    // }
public function destroy(Faculty $faculty)
{
    try {
        DB::beginTransaction();

        // 1. Loop through each department of this faculty
        foreach ($faculty->departments as $department) {
            // 1.1 Delete courses for each program
            foreach ($department->programs as $program) {
                $program->courses()->delete();
            }
            // 1.2 Delete programs themselves
            $department->programs()->delete();
        }

        // 2. Delete all departments
        $faculty->departments()->delete();

        // 3. Delete the faculty
        $faculty->delete();

        DB::commit();

        return redirect()->route('admin.manage-faculties')
            ->with('success', 'មហាវិទ្យាល័យនិងទិន្នន័យដែលពាក់ព័ន្ធទាំងអស់ត្រូវបានលុបដោយជោគជ័យ។');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error deleting faculty: ' . $e->getMessage());
        return redirect()->route('admin.manage-faculties')
            ->with('error', 'មិនអាចលុបមហាវិទ្យាល័យបានទេ៖ មានបញ្ហាមួយបានកើតឡើង។');
    }
}

}
