<?php

namespace App\Http\Controllers\professor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProfessorAttendanceController extends Controller
{
        /**
     * Display all grades managed by the professor across all their courses.
     */

    /**
     * Store a newly created attendance record in storage.
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_user_id' => [
    'required',
    Rule::exists('student_course_enrollments', 'student_user_id')
        ->where('course_offering_id', $request->course_offering_id)
],
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'note' => 'nullable|string|max:255',
        ], [
            'student_user_id.required' => 'អត្តសញ្ញាណសិស្សតម្រូវឱ្យបញ្ចូល។',
            'student_user_id.exists' => 'អត្តសញ្ញាណសិស្សមិនមាននៅក្នុងប្រព័ន្ធទេ។',
            'course_offering_id.required' => 'អត្តសញ្ញាណវគ្គសិក្សាតម្រូវឱ្យបញ្ចូល។',
            'date.required' => 'កាលបរិច្ឆេទតម្រូវឱ្យបញ្ចូល។',
            'status.required' => 'ស្ថានភាពវត្តមានតម្រូវឱ្យបញ្ចូល។',
        ]);

        $attendance = AttendanceRecord::create([
            'course_offering_id' => $request->input('course_offering_id'),
            'student_user_id' => $request->input('student_user_id'), // Ensure this is mapped correctly
            'date' => $request->input('date'),
            'status' => $request->input('status'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('professor.manage-attendance', ['offering_id' => $request->input('course_offering_id')])
                 ->with('success', __('កំណត់ត្រាវត្តមានត្រូវបានបន្ថែមដោយជោគជ័យ។'));

    }

    /**
     * Update the specified attendance record in storage.
     */
    public function updateAttendance(Request $request, AttendanceRecord $attendance)
    {
        $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_user_id' => 'required|exists:users,id', // Changed from 'student_id' to 'student_user_id'
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'note' => 'nullable|string|max:255',
        ], [
            'student_user_id.required' => 'អត្តសញ្ញាណសិស្សតម្រូវឱ្យបញ្ចូល។',
            'student_user_id.exists' => 'អត្តសញ្ញាណសិស្សមិនមាននៅក្នុងប្រព័ន្ធទេ។',
            'course_offering_id.required' => 'អត្តសញ្ញាណវគ្គសិក្សាតម្រូវឱ្យបញ្ចូល។',
            'date.required' => 'កាលបរិច្ឆេទតម្រូវឱ្យបញ្ចូល។',
            'status.required' => 'ស្ថានភាពវត្តមានតម្រូវឱ្យបញ្ចូល។',
        ]);

        $attendance->update([
            'course_offering_id' => $request->input('course_offering_id'),
            'student_user_id' => $request->input('student_user_id'),
            'date' => $request->input('date'),
            'status' => $request->input('status'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('professor.manage-attendance', ['offering_id' => $attendance->course_offering_id])
                 ->with('success', __('កំណត់ត្រាវត្តមានត្រូវបានកែប្រែដោយជោគជ័យ។'));

    }

    /**
     * Remove the specified attendance record from storage.
     */
    public function destroyAttendance(AttendanceRecord $attendance)
    {
        $attendance->delete();

        return redirect()->route('professor.manage-attendance', ['offering_id' => $attendance->course_offering_id])
                 ->with('success', __('កំណត់ត្រាវត្តមានត្រូវបានលុបដោយជោគជ័យ។'));

    }
}
