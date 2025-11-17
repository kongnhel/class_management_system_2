<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the assignments for a specific course offering.
     */
    public function index(CourseOffering $courseOffering)
    {
        // Eager load only the 'course' relationship for the courseOffering
        // to prevent "Attempt to read property 'name' on string" error in the view.
        // Removed 'semester' as the user does not have a Semester table.
        $courseOffering->load('course');

        // Fetch assignments related to this specific course offering
        $assignments = $courseOffering->assignments()->latest()->get();

        return view('assignments.index', compact('courseOffering', 'assignments'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(CourseOffering $courseOffering)
    {
        // Eager load the 'course' relationship for the courseOffering
        $courseOffering->load('course');

        return view('assignments.create', compact('courseOffering'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request, CourseOffering $courseOffering)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $assignment = new Assignment($validatedData);
        $assignment->course_offering_id = $courseOffering->id;
        $assignment->save();

        return redirect()->route('professor.assignments.index', $courseOffering->id)
                         ->with('success', 'កិច្ចការផ្ទះត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * Display the specified assignment.
     */
    public function show(CourseOffering $courseOffering, Assignment $assignment)
    {
        // Optionally load relationships for assignment if needed
        return view('assignments.show', compact('courseOffering', 'assignment'));
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(CourseOffering $courseOffering, Assignment $assignment)
    {
        $courseOffering->load('course'); // Eager load course for title in edit view
        return view('assignments.edit', compact('courseOffering', 'assignment'));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, CourseOffering $courseOffering, Assignment $assignment)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $assignment->update($validatedData);

        return redirect()->route('professor.assignments.index', $courseOffering->id)
                         ->with('success', 'កិច្ចការផ្ទះត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(CourseOffering $courseOffering, Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('professor.assignments.index', $courseOffering->id)
                         ->with('success', 'កិច្ចការផ្ទះត្រូវបានលុបដោយជោគជ័យ។');
    }
}
