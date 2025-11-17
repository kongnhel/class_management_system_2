<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::with('department')->paginate(10);
        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.programs.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                $request->validate([
            'name_km' => 'required|string|max:255|unique:programs',
            'name_en' => 'required|string|max:255|unique:programs',
            'department_id' => 'required|exists:departments,id',
            'duration_years' => 'required|integer|min:1',
            'degree_level' => 'required|string|max:50',
        ]);

        Program::create($request->all());

        return redirect()->route('admin.manage-programs')->with('success', 'កម្មវិធីសសិក្សាបង្កើតដោយជោគជ័យ!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        $departments = Department::all();
        return view('admin.programs.edit', compact('program', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
         $request->validate([
            'name_km' => ['required', 'string', 'max:255', Rule::unique('programs')->ignore($program->id)],
            'name_en' => ['required', 'string', 'max:255', Rule::unique('programs')->ignore($program->id)],
            'department_id' => 'required|exists:departments,id',
            'duration_years' => 'required|integer|min:1',
            'degree_level' => 'required|string|max:50',
        ]);

        $program->update($request->all());

        return redirect()->route('admin.manage-programs')->with('success', 'កម្មវិធីសិក្សាត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
         try {
        // Check if there are any users associated with this program.
        // This prevents a foreign key constraint error.
        if ($program->users()->exists()) {
            return redirect()->route('admin.manage-programs')
                ->with('error', 'មិនអាចលុបកម្មវិធីសិក្សានេះបានទេ ព្រោះមានអ្នកប្រើប្រាស់ដែលពាក់ព័ន្ធ។ សូមផ្ទេរអ្នកប្រើប្រាស់ទាំងនោះទៅកម្មវិធីផ្សេងមុន។');
        }

        // If no users are associated, proceed to delete the program
        $program->delete();

        return redirect()->route('admin.manage-programs')
            ->with('success', 'កម្មវិធីសិក្សាត្រូវបានលុបដោយជោគជ័យ!');
    } catch (\Exception $e) {
        // Catch any unexpected database errors and provide a user-friendly message.
        return redirect()->route('admin.manage-programs')
            ->with('error', 'មានកំហុសកើតឡើងក្នុងការលុបកម្មវិធីសិក្សា។ សូមព្យាយាមម្តងទៀត។');
    }
    }
}
