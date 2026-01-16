<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\CourseOffering;
use App\Models\StudentCourseEnrollment;
use App\Models\ExamResult;
use App\Models\Grade;
use App\Models\User;
use App\Exports\StudentsGradeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsGradeImport;
use Illuminate\Support\Facades\DB;
class GradeController extends Controller
{




// public function exportCSV(Request $request, $id)
// {
//     // ១. ទាញយកប្រភេទ Assessment ពី URL (ឧទាហរណ៍៖ ?type=exam)
//     $type = $request->query('type');

//     // ២. ទាញយកទិន្នន័យឱ្យចំ Table ការពារការជាន់ ID គ្នា
//     if ($type === 'assignment') {
//         $assessment = \App\Models\Assignment::with('courseOffering')->findOrFail($id);
//     } elseif ($type === 'quiz') {
//         $assessment = \App\Models\Quiz::with('courseOffering')->findOrFail($id);
//     } else {
//         $assessment = \App\Models\Exam::with('courseOffering')->findOrFail($id);
//     }

//     $courseOffering = $assessment->courseOffering;

//     // ៣. ទាញយកសិស្ស (Double Check Program & Generation)
//     $students = \App\Models\User::whereHas('studentCourseEnrollments', function($q) use ($courseOffering) {
//             $q->where('course_offering_id', $courseOffering->id)
//               ->where('status', 'enrolled');
//         })
//         ->where('program_id', $courseOffering->program_id)
//         ->where('generation', $courseOffering->generation)
//         ->with('userProfile')
//         ->get();

//     // ៤. រៀបចំ File CSV
//     $courseName = str_replace([' ', '/', '\\'], '_', $courseOffering->course->title_en ?? 'Subject');
//     $fileName = "Grades_{$courseName}_Gen{$courseOffering->generation}_{$type}_{$id}.csv";

//     $headers = [
//         "Content-type"        => "text/csv; charset=UTF-8",
//         "Content-Disposition" => "attachment; filename=$fileName",
//     ];

//     $callback = function() use ($students) {
//         $file = fopen('php://output', 'w');
//         fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // Support ខ្មែរ
//         fputcsv($file, ['ID', 'Student Code', 'Name', 'Score', 'Notes']);

//         foreach ($students as $student) {
//             fputcsv($file, [
//                 $student->id,
//                 $student->student_id_code,
//                 $student->userProfile?->full_name_km ?? $student->name,
//                 '', ''
//             ]);
//         }
//         fclose($file);
//     };

//     return response()->stream($callback, 200, $headers);
// }

public function exportCSV(Request $request, $id)
{
    // ១. ទាញយកប្រភេទ Assessment និងកំណត់ Type ឱ្យត្រូវតាម Model Name (ឧទាហរណ៍៖ Exam)
    $rawType = $request->query('type'); 
    $type = ucfirst(strtolower($rawType)); // បំប្លែង assignment -> Assignment

    if ($type === 'Assignment') {
        $assessment = \App\Models\Assignment::with('courseOffering')->findOrFail($id);
    } elseif ($type === 'Quiz') {
        $assessment = \App\Models\Quiz::with('courseOffering')->findOrFail($id);
    } else {
        $assessment = \App\Models\Exam::with('courseOffering')->findOrFail($id);
        $type = 'Exam'; 
    }

    $courseOffering = $assessment->courseOffering;

    // ២. ទាញយកសិស្ស (Filter តាម Program & Generation)
    $students = \App\Models\User::whereHas('studentCourseEnrollments', function($q) use ($courseOffering) {
            $q->where('course_offering_id', $courseOffering->id)
              ->where('status', 'enrolled');
        })
        ->where('program_id', $courseOffering->program_id)
        ->where('generation', $courseOffering->generation)
        ->with('userProfile')
        ->get();

    // ៣. ទាញយកពិន្ទុ (ប្រើ ucfirst($type) ដើម្បីឱ្យត្រូវនឹងអ្វីដែលបានរក្សាទុកក្នុង DB)
    $results = \App\Models\ExamResult::where('assessment_id', $id)
        ->where('assessment_type', $type) 
        ->get()
        ->keyBy('student_user_id');

    // ៤. រៀបចំ File CSV
    $courseName = str_replace([' ', '/', '\\'], '_', $courseOffering->course->title_en ?? 'Subject');
    $fileName = "Grades_{$courseName}_Gen{$courseOffering->generation}_{$type}_{$id}.csv";

    $headers = [
        "Content-type"        => "text/csv; charset=UTF-8",
        "Content-Disposition" => "attachment; filename=$fileName",
    ];

    $callback = function() use ($students, $results) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // Support អក្សរខ្មែរ
        
        fputcsv($file, ['ID', 'Student Code', 'Name', 'Score', 'Notes']);

        foreach ($students as $student) {
            // ទាញយក Record ពិន្ទុរបស់សិស្ស
            $scoreRecord = $results->get($student->id);
            
            // បង្ហាញពិន្ទុ បើគ្មានទេទុកទំនេរ
            $score = $scoreRecord ? $scoreRecord->score_obtained : '';
            $notes = $scoreRecord ? $scoreRecord->notes : '';

            fputcsv($file, [
                $student->id,
                $student->student_id_code,
                $student->userProfile?->full_name_km ?? $student->name,
                $score, 
                $notes 
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function importCSV(Request $request, $id)
{
    $request->validate([
        'excel_file' => 'required|mimes:csv,txt',
        'type' => 'required', // មកពី hidden input 'type'
        'offering_id' => 'required'
    ]);

    $type = $request->input('type'); // តម្លៃអាចជា: assignment, exam, quiz
    $offering_id = $request->input('offering_id');

    if (($handle = fopen($request->file('excel_file')->getRealPath(), "r")) !== FALSE) {
        // រំលង UTF-8 BOM ប្រសិនបើមាន
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($handle);
        
        fgetcsv($handle); // រំលង Header ជួរទី១

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (empty($data[0])) continue;

                $studentUserId = trim($data[0]); // ID និស្សិត (Index 0)
                $score         = trim($data[3]); // ពិន្ទុ (Index 3)
                $notes         = trim($data[4] ?? ''); // ចំណាំ (Index 4)

                if ($score !== '') {
                    // បញ្ចូលក្នុង ExamResult សម្រាប់គ្រប់ប្រភេទ (Assignment, Exam, Quiz)
                    \App\Models\ExamResult::updateOrCreate(
                        [
                            'assessment_id'   => $id, 
                            'student_user_id' => $studentUserId,
                            'assessment_type' => $type // រក្សាទុកពាក្យ 'assignment', 'exam', ឬ 'quiz'
                        ],
                        [
                            'score_obtained'  => $score,
                            'notes'           => $notes,
                            'recorded_at'     => now()
                        ]
                    );
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
        fclose($handle);
    }

    return redirect()->route('professor.manage-grades', ['offering_id' => $offering_id])
                     ->with('success', 'បញ្ចូលពិន្ទុ '. ucfirst($type) .' ជោគជ័យ!');
}

/**
 * បង្ហាញទំព័រកែសម្រួលពិន្ទុវត្តមាន
 */
public function editAttendance($student_id, $course_id)
{
    $student = User::findOrFail($student_id);
    $courseOffering = CourseOffering::findOrFail($course_id);
    
    // ទាញយកពិន្ទុដែលគណនាដោយ System
    $autoScore = $student->getAttendanceScoreByCourse($course_id); 
    
    // ទាញយក Enrollment ដើម្បីមើលពិន្ទុដែលធ្លាប់កែដោយដៃ
    $enrollment = StudentCourseEnrollment::where('student_user_id', $student_id)
                    ->where('course_offering_id', $course_id)
                    ->firstOrFail();

    return view('professor.grades.edit_attendance', compact('student', 'courseOffering', 'autoScore', 'enrollment'));
}

/**
 * រក្សាទុកពិន្ទុដែលគ្រូបញ្ចូលដោយដៃ
 */
public function updateAttendanceScore(Request $request)
{
    $request->validate([
        'student_id' => 'required',
        'course_id' => 'required',
        'score' => 'nullable|numeric|min:0|max:15',
    ]);

    // ១. រក្សាទុកពិន្ទុក្នុងតារាង student_course_enrollments
    $enrollment = \App\Models\StudentCourseEnrollment::where('student_user_id', $request->student_id)
                    ->where('course_offering_id', $request->course_id)
                    ->firstOrFail();
    
    $enrollment->attendance_score_manual = $request->score;
    $enrollment->save();

    // ២. ប្រសិនបើគ្រូបញ្ចូលពិន្ទុពេញ (១៥) ឱ្យ Update ក្នុង Table attendances ជា 'present' ទាំងអស់
    if ($request->score >= 15) {
        \App\Models\AttendanceRecord::where('student_user_id', $request->student_id)
            ->where('course_offering_id', $request->course_id)
            ->update(['status' => 'present']);
    }

    return redirect()->back()->with('success', 'បានធ្វើបច្ចុប្បន្នភាពពិន្ទុវត្តមានរួចរាល់');
}
}