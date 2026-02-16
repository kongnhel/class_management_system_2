<?php

namespace App\Http\Controllers\professor;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Program;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Assignment;
use App\Models\Notification;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\AttendanceRecord;
use App\Models\Submission;
use App\Models\ExamResult;
use App\Models\Announcement;
use App\Models\StudentQuizResponse;
use App\Models\GradingCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\StudentProfile;
use App\Models\StudentCourseEnrollment;
use App\Models\StudentProgramEnrollment;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Notification as NotificationFacade; 
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Storage;
use App\Exports\GradebookExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfessorGradeController extends Controller
{
    public function manageGrades($offering_id)
{
    $courseOffering = CourseOffering::with([
        'course',
        'studentCourseEnrollments.student.studentProfile' 
    ])->findOrFail($offering_id);

    // ១. ទាញយក Assignments, Exams, Quizzes
    $assignments = \App\Models\Assignment::where('course_offering_id', $offering_id)->get();
    $exams = \App\Models\Exam::where('course_offering_id', $offering_id)->get();
    $quizzes = \App\Models\Quiz::where('course_offering_id', $offering_id)->get();

    $assessments = collect($assignments)->concat($exams)->concat($quizzes)->sortBy('created_at');

    // ទាញយកពិន្ទុទាំងអស់មកទុកក្នុង Memory តែម្តង (ដើម្បីកុំឱ្យយឺត និងស្រួល Sort)
    $allResults = \App\Models\ExamResult::whereIn('student_user_id', $courseOffering->studentCourseEnrollments->pluck('student_user_id'))
        ->get();

    // ២. រៀបចំ Gradebook និងគណនាពិន្ទុ
    $gradebook = [];
    $students = $courseOffering->studentCourseEnrollments->map(function ($enrollment) use ($assessments, $allResults, &$gradebook, $offering_id) {
        $student = $enrollment->student;
        
        // ប្រសិនបើអ្នកមាន Function គណនាពិន្ទុវត្តមាន សូមហៅប្រើនៅទីនេះ
        $attendanceScore = (float)($student->getAttendanceScoreByCourse($offering_id) ?? 0);
        $totalScore = $attendanceScore;

        foreach ($assessments as $assessment) {
            $type = ($assessment instanceof \App\Models\Assignment) ? 'assignment' : 
                   (($assessment instanceof \App\Models\Quiz) ? 'quiz' : 'exam');

            // ស្វែងរកពិន្ទុក្នុង Collection (លឿនជាង Query ក្នុង Loop)
            $scoreRecord = $allResults->where('assessment_id', $assessment->id)
                                      ->where('student_user_id', $student->id)
                                      ->where('assessment_type', $type)
                                      ->first();
            
            $score = $scoreRecord ? (float)$scoreRecord->score_obtained : 0;
            $gradebook[$student->id][$type . '_' . $assessment->id] = $score;
            
            $totalScore += $score;
        }

        $student->temp_total = (float)$totalScore; // បង្ខំឱ្យទៅជាលេខទសភាគដើម្បី Sort ឱ្យត្រូវ
        return $student;
    });

    // ៣. តម្រៀប Ranking តាមពិន្ទុសរុបពីធំទៅតូច (សំខាន់បំផុត)
    // ប្រើ values() ដើម្បីឱ្យ index រត់ពី 0, 1, 2 ឡើងវិញ
    $students = $students->sortByDesc('temp_total')->values();

    // ៤. ផ្ដល់ Rank និង Grade បន្ទាប់ពី Sort រួចរាល់
    foreach ($students as $index => $student) {
        $student->rank = $index + 1; // ឥឡូវអ្នកពិន្ទុខ្ពស់ជាងគេនឹងនៅ index 0 ទទួលបាន Rank 1
        
        $ts = $student->temp_total;
        if ($ts >= 85) $student->letterGrade = 'A';
        elseif ($ts >= 80) $student->letterGrade = 'B+';
        elseif ($ts >= 70) $student->letterGrade = 'B';
        elseif ($ts >= 65) $student->letterGrade = 'C+';
        elseif ($ts >= 50) $student->letterGrade = 'C';
        else $student->letterGrade = 'F';
    }

    return view('professor.grades.index', compact('courseOffering', 'students', 'assessments', 'gradebook'));
}



// storeAssessment
// storeGradesForAssessment
    /**
     * Method សម្រាប់បង្ហាញទម្រង់បង្កើត Exam/Assignment
     */
    public function createAssessmentForm($offering_id)
    {
        $courseOffering = CourseOffering::with('course')->findOrFail($offering_id);
        $gradingCategories = GradingCategory::where('course_id', $courseOffering->course->id)->get();
        return view('professor.assessments.create', compact('courseOffering', 'gradingCategories'));
    }

    /**
     * Method សម្រាប់រក្សាទុក Exam/Assignment ថ្មី
     */
public function storeAssessment(Request $request, $offering_id)
{
    // ១. Validation
    $request->validate([
        'assessment_type' => 'required|in:assignment,exam,quiz',
        'title_en' => 'required|string|max:255',
        'title_km' => 'required|string|max:255',
        'max_score' => 'required|numeric|min:1',
        'assessment_date' => 'required|date',
        'grading_category_id' => 'nullable|exists:grading_categories,id',
    ]);

    $courseOffering = CourseOffering::findOrFail($offering_id);
    $type = $request->input('assessment_type');

    // ២. បន្ថែមការឆែក Limit (ជាពិសេសសម្រាប់ Exam)
    if ($type === 'exam') {
        // ប្រសិនបើចំណងជើងមានពាក្យ "Final" ឬ "Mid-term" យើងអាចឆែកការពារកុំឱ្យបង្កើតលើសពី ១
        $existingExam = Exam::where('course_offering_id', $offering_id)
            ->where(function($query) use ($request) {
                $query->where('title_en', 'LIKE', '%' . $request->title_en . '%')
                      ->orWhere('title_km', 'LIKE', '%' . $request->title_km . '%');
            })->first();

// ឆែកមើលក្នុង Controller
            if ($existingExam) {
                // ត្រូវប្រើ 'error' ជា Key ដើម្បីឱ្យស៊ីគ្នាជាមួយកូដ Blade ខាងលើ
                return back()->withInput()->with('error', 'វិញ្ញាសានេះមានរួចហើយ! អ្នកមិនអាចបង្កើតជាន់គ្នាបានទេ។');
            }
    }

    // ៣. បែងចែកការបង្កើតតាមប្រភេទ
    if ($type === 'quiz') {
        \App\Models\Quiz::create([
            'course_offering_id' => $courseOffering->id,
            'title_km' => $request->input('title_km'),
            'title_en' => $request->input('title_en'),
            'max_score' => $request->input('max_score'),
            'quiz_date' => $request->input('assessment_date'), 
            'grading_category_id' => $request->input('grading_category_id'),
        ]);
    } elseif ($type === 'assignment') {
        Assignment::create([
            'course_offering_id' => $courseOffering->id,
            'title_km'           => $request->title_km,
            'title_en'           => $request->title_en,
            'max_score'          => $request->max_score,
            'due_date'           => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } else { 
        Exam::create([
            'course_offering_id' => $courseOffering->id,
            'title_km'           => $request->title_km,
            'title_en'           => $request->title_en,
            'max_score'          => $request->max_score,
            'exam_date'          => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
            'duration_minutes'   => 120, 
        ]);
    }

    return redirect()->route('professor.manage-grades', ['offering_id' => $offering_id])
                     ->with('success', 'ការវាយតម្លៃត្រូវបានបង្កើតដោយជោគជ័យ!');
}
 
// storeGradesForAssessment


public function destroyAssessment(Request $request, $id)
{
    $type = $request->input('assessment_type'); 
    $assessment = null;

    if ($type === 'quiz') {
        $assessment = \App\Models\Quiz::find($id);
        if ($assessment) {
            // ប្រសិនបើបងបញ្ចូល Quiz ក្នុង exam_results ត្រូវលុបវាចេញសិន
            // សន្មតថា quiz ប្រើ id ភ្ជាប់ទៅ exam_id ក្នុងតារាង exam_results
       // កែពី exam_id ទៅ assessment_id (ប្រសិនបើក្នុង DB របស់បងប្រើឈ្មោះនេះ)
            \App\Models\ExamResult::where('assessment_id', $id)->delete();
        }
    } elseif ($type === 'assignment') {
        $assessment = \App\Models\Assignment::find($id);
        if ($assessment) {
            // លុបកិច្ចការដែលសិស្សបានផ្ញើ (Submissions)
            \App\Models\Submission::where('assignment_id', $id)->delete();
        }
    } elseif ($type === 'exam') {
        $assessment = \App\Models\Exam::find($id);
        if ($assessment) {
            // លុបពិន្ទុប្រឡងរបស់សិស្ស
            \App\Models\ExamResult::where('assessment_id', $id)->delete();
        }
    }

    if ($assessment) {
        $assessment->delete();
        return back()->with('success', 'លុបការវាយតម្លៃ និងពិន្ទុដែលពាក់ព័ន្ធបានជោគជ័យ!');
    }

    return back()->with('error', 'រកមិនឃើញទិន្នន័យដែលត្រូវលុប!');
}


public function showGradeEntryForm(Request $request, $assessment_id)
{
    $type = $request->query('type'); // 'assignment', 'exam', ឬ 'quiz'
    $search = $request->query('search'); 
    $assessment = null;

    // ១. ទាញយកទិន្នន័យតាមប្រភេទ Assessment 
    // យើងប្រើ eager load 'examResults' ទាំងអស់ ព្រោះទិន្នន័យពិន្ទុស្ថិតក្នុង Table តែមួយ
    if ($type === 'assignment') {
        $assessment = \App\Models\Assignment::with(['courseOffering.studentCourseEnrollments.student.studentProfile', 'examResults'])
                                ->findOrFail($assessment_id);
    } elseif ($type === 'exam') {
        $assessment = \App\Models\Exam::with(['courseOffering.studentCourseEnrollments.student.studentProfile', 'examResults'])
                          ->findOrFail($assessment_id);
    } elseif ($type === 'quiz') {
        $assessment = \App\Models\Quiz::with(['courseOffering.studentCourseEnrollments.student.studentProfile', 'examResults'])
                          ->findOrFail($assessment_id);
    } else {
        abort(404, 'ប្រភេទការវាយតម្លៃមិនត្រឹមត្រូវ');
    }

    // ២. ទាញបញ្ជីឈ្មោះសិស្សចេញពី Enrollment
    $students = $assessment->courseOffering->studentCourseEnrollments->map(function ($enrollment) {
        return $enrollment->student;
    })->filter();

    // Logic ស្វែងរក (រក្សាទុកនៅដដែល)
    if (!empty($search)) {
        $students = $students->filter(function ($student) use ($search) {
            $searchLower = mb_strtolower($search, 'UTF-8');
            $nameKm = mb_strtolower($student->studentProfile?->full_name_km ?? '', 'UTF-8');
            $nameEn = mb_strtolower($student->studentProfile?->full_name_en ?? '', 'UTF-8');
            $userName = mb_strtolower($student->name ?? '', 'UTF-8');
            $studentId = mb_strtolower($student->student_id_code ?? '', 'UTF-8');

            return str_contains($nameKm, $searchLower) || 
                   str_contains($nameEn, $searchLower) || 
                   str_contains($userName, $searchLower) || 
                   str_contains($studentId, $searchLower);
        });
    }

    $students = $students->sortBy('name');

    // ៣. ទាញពិន្ទុចេញពី examResults មកដាក់ក្នុង Array $scores
    $scores = [];
    
    // Logic ថ្មី៖ ទាញពិន្ទុពី examResults សម្រាប់គ្រប់ប្រភេទ
    // វានឹងដំណើរការទាំងការ Save ផ្ទាល់ដៃ និងការ Import តាម CSV
    foreach ($assessment->examResults as $result) {
        // ឆែកឱ្យច្បាស់ថា assessment_type ក្នុង Database ត្រូវជាមួយ type ដែលកំពុងមើល
        if ($result->assessment_type === $type) {
            $scores[$result->student_user_id] = [
                'score' => $result->score_obtained,
                'notes' => $result->notes,
            ];
        }
    }

    return view('professor.grades.edit', compact('assessment', 'students', 'scores', 'type', 'search'));
}
    /**
     * Method សម្រាប់រក្សាទុកពិន្ទុរបស់និស្សិត
     */
public function storeGradesForAssessment(Request $request, $assessment_id)
{
    $request->validate([
        'grades' => 'required|array',
        'assessment_type' => 'required|in:assignment,exam,quiz',
    ]);

    $type = $request->input('assessment_type');
    $offering_id = null;

    DB::beginTransaction();
    try {
        foreach ($request->input('grades') as $student_id => $gradeData) {
            // ប្រសិនបើមានបញ្ចូលពិន្ទុ (មិនមែន Null)
            if (!isset($gradeData['score']) || $gradeData['score'] === '') {
                continue;
            }

            if ($type === 'assignment') {
                $assessment = Assignment::findOrFail($assessment_id);
                $offering_id = $assessment->course_offering_id;
                
                ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $student_id,
                        'assessment_type' => 'assignment'
                    ],
                    [
                        'score_obtained' => $gradeData['score'], 
                        'notes' => $gradeData['notes'] ?? null, 
                        'recorded_at' => now()
                    ]
                );
            } elseif ($type === 'exam') {
                $assessment = Exam::findOrFail($assessment_id);
                $offering_id = $assessment->course_offering_id;
                
                ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $student_id,
                        'assessment_type' => 'exam'
                    ],
                    [
                        'score_obtained' => $gradeData['score'], 
                        'notes' => $gradeData['notes'] ?? null, 
                        'recorded_at' => now()
                    ]
                );
            } elseif ($type === 'quiz') {
                // ១. ទាញរកព័ត៌មាន Quiz ដើម្បីយក offering_id
                $assessment = Quiz::findOrFail($assessment_id);
                $offering_id = $assessment->course_offering_id;

                // ២. រក្សាទុកក្នុង ExamResult ដូច Exam ដែរ (តែប្តូរ type ជា quiz)
                // ប្រសិនបើប្អូនមានតារាង quiz_results ដាច់ដោយឡែក ត្រូវប្តូរ Model នៅទីនេះ
                ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $student_id,
                        'assessment_type' => 'quiz' // <--- បែងចែកឱ្យច្បាស់ក្នុង DB
                    ],
                    [
                        'score_obtained' => $gradeData['score'], 
                        'notes' => $gradeData['notes'] ?? null, 
                        'recorded_at' => now()
                    ]
                );
            }
        }
        
        DB::commit();
        // បើ $offering_id នៅ Null (ករណីអត់មាន Loop) ត្រូវការពារកុំឱ្យ Error
        $offering_id = $offering_id ?? $request->input('offering_id'); 
        if (!$offering_id) {
    // បើរក offering_id អត់ឃើញពីគ្រប់ច្រក ត្រូវទាញពី Assessment ផ្ទាល់
    $assessment = ($type === 'exam') ? Exam::find($assessment_id) : Assignment::find($assessment_id);
    $offering_id = $assessment->course_offering_id;
}
        return redirect()->route('professor.manage-grades', ['offering_id' => $offering_id])
                         ->with('success', 'រក្សាទុកពិន្ទុបានជោគជ័យ!');
                         
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
        return back()->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
    }
}
// professor.grades.edit
// storeGrades

// c
// reateAssessmentForm
// showGradeEntryForm
public function storeGrades(Request $request, $assessment_id)
{
    // ១. បន្ថែម Validation ដើម្បីសុវត្ថិភាពទិន្នន័យ
    $request->validate([
        'assessment_type' => 'required|in:assignment,exam,quiz',
        'grades' => 'required|array',
    ]);

    $type = $request->input('assessment_type');
    $grades = $request->input('grades');

    // ប្រើ Transaction ដើម្បីធានាថា បើ Error ម្នាក់ គឺមិនរក្សាទុកទាំងអស់ (ការពារទិន្នន័យច្របូកច្របល់)
    \DB::beginTransaction();
    try {
        foreach ($grades as $studentId => $data) {
            // ប្រសិនបើពិន្ទុទទេ (Empty String) យើងអាចរំលងបាន (Optional)
            if ($data['score'] === null || $data['score'] === '') continue;

            if ($type === 'assignment') {
                // រក្សាទុកក្នុងតារាង submissions
                \App\Models\Submission::updateOrCreate(
                    ['assignment_id' => $assessment_id, 'student_user_id' => $studentId],
                    [
                        'grade_received' => $data['score'], // ប្រើតាមឈ្មោះ Column ក្នុង DB ប្អូន
                        'feedback' => $data['notes']       // ប្រើតាមឈ្មោះ Column ក្នុង DB ប្អូន
                    ]
                );
            } else {
                // ប្រភេទ 'exam' ឬ 'quiz' រក្សាទុកក្នុងតារាង exam_results
                \App\Models\ExamResult::updateOrCreate(
                    [
                        'assessment_id' => $assessment_id, 
                        'student_user_id' => $studentId,
                        'assessment_type' => $type // ត្រូវដាក់ $type ដើម្បីបែងចែក exam និង quiz
                    ],
                    [
                        'score_obtained' => $data['score'], 
                        'notes' => $data['notes'],
                        'recorded_at' => now()
                    ]
                );
            }
        }
        \DB::commit();
        return back()->with('success', 'រក្សាទុកពិន្ទុបានជោគជ័យ');

    } catch (\Exception $e) {
        \DB::rollBack();
        dd($e->getMessage());
        return back()->with('error', 'មានបញ្ហា៖ ' . $e->getMessage());
    }
}
    /**
     * Manage attendance for a specific course offering.
     */
// destroyAssessment
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'course_offering_id' => 'required|exists:course_offerings,id',
            'student_user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:255', 
        ]);

        // Create a new attendance record
        AttendanceRecord::create([
            'course_offering_id' => $validatedData['course_offering_id'],
            'student_user_id' => $validatedData['student_user_id'],
            'date' => $validatedData['date'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'] ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return back()->with('success', 'កំណត់ត្រាវត្តមានត្រូវបានរក្សាទុកដោយជោគជ័យ!');
    }
    public function manageAttendance($offering_id)
    {
        $courseOffering = CourseOffering::with(['course', 'studentCourseEnrollments.student.profile'])->findOrFail($offering_id);
        $attendanceRecords = AttendanceRecord::where('course_offering_id', $offering_id)
                                            ->with('student.profile')
                                            ->orderBy('date', 'desc')
                                            ->paginate(10);

        // Add a Khmer status for display
        $attendanceRecords->each(function ($record) {
            $record->status_km = match($record->status) {
                // 'present' => 'មានវត្តមាន',
                'absent' => 'អវត្តមាន',
                'late' => 'មកយឺត',
                'excused' => 'មានច្បាប់',
                default => 'មិនស្គាល់',
            };
        });

        return view('professor.manage-attendance', compact('courseOffering', 'attendanceRecords'));
    }




public function exportCSV(Request $request, $id)
{
    // ១. ទាញយកប្រភេទ Assessment (រក្សាទុកដដែល)
    $rawType = $request->query('type'); 
    $type = ucfirst(strtolower($rawType)); 

    if ($type === 'Assignment') {
        $assessment = \App\Models\Assignment::with('courseOffering.targetPrograms')->findOrFail($id);
    } elseif ($type === 'Quiz') {
        $assessment = \App\Models\Quiz::with('courseOffering.targetPrograms')->findOrFail($id);
    } else {
        $assessment = \App\Models\Exam::with('courseOffering.targetPrograms')->findOrFail($id);
        $type = 'Exam'; 
    }

    $courseOffering = $assessment->courseOffering;

    // ២. 🔥 ជួសជុល៖ ទាញយកសិស្សដែលបាន Enroll ក្នុង Course Offering នេះផ្ទាល់តែម្តង
    // មិនបាច់ Filter តាម program_id លើ table course_offerings ទៀតទេ
    $students = \App\Models\User::whereHas('studentCourseEnrollments', function($q) use ($courseOffering) {
            $q->where('course_offering_id', $courseOffering->id)
              ->where('status', 'enrolled');
        })
        ->with('userProfile')
        ->get();

    // ៣. ទាញយកពិន្ទុ (រក្សាទុកដដែល)
    $results = \App\Models\ExamResult::where('assessment_id', $id)
        ->where('assessment_type', strtolower($type)) 
        ->get()
        ->keyBy('student_user_id');

    // ៤. រៀបចំ File CSV
    $courseName = str_replace([' ', '/', '\\'], '_', $courseOffering->course->title_en ?? 'Subject');
    
    // 🔥 ប្តូរឈ្មោះ File ឱ្យសមស្រប (ព្រោះឥឡូវមានច្រើន Gen)
    $fileName = "Grades_{$courseName}_{$type}_ID{$id}.csv";

    $headers = [
        "Content-type"        => "text/csv; charset=UTF-8",
        "Content-Disposition" => "attachment; filename=$fileName",
    ];

    $callback = function() use ($students, $results) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // Support ខ្មែរ
        
        fputcsv($file, ['ID', 'Student Code', 'Name', 'Score', 'Notes']);

        foreach ($students as $student) {
            $scoreRecord = $results->get($student->id);
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

public function assessmentEdit($id, $type)
{
    if ($type === 'assignment') {
        $assessment = \App\Models\Assignment::findOrFail($id);
    } elseif ($type === 'quiz') {
        $assessment = \App\Models\Quiz::findOrFail($id);
    } elseif ($type === 'exam') {
        $assessment = \App\Models\Exam::findOrFail($id);
    } else {
        abort(404);
    }

    $courseOffering = \App\Models\CourseOffering::findOrFail(
        $assessment->course_offering_id
    );

    $gradingCategories = \App\Models\GradingCategory::where(
        'course_id', $courseOffering->course_id
    )->get();

    return view(
        'professor.assessments.edit',
        compact('assessment', 'type', 'courseOffering', 'gradingCategories')
    );
}

public function update(Request $request, $id, $type)
{
    $request->validate([
        'title_km' => 'required|string|max:255',
        'max_score' => 'required|numeric|min:1',
        'assessment_date' => 'required|date',
        'grading_category_id' => 'required'
    ]);

    if ($type === 'assignment') {
        $model = \App\Models\Assignment::findOrFail($id);
        $model->update([
            'title_km' => $request->title_km,
            'max_score' => $request->max_score,
            'due_date' => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } elseif ($type === 'quiz') {
        $model = \App\Models\Quiz::findOrFail($id);
        $model->update([
            'title_km' => $request->title_km,
            'max_score' => $request->max_score,
            'quiz_date' => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } elseif ($type === 'exam') {
        $model = \App\Models\Exam::findOrFail($id);
        $model->update([
            'title_km' => $request->title_km,
            'max_score' => $request->max_score,
            'exam_date' => $request->assessment_date,
            'grading_category_id' => $request->grading_category_id,
        ]);
    } else {
        abort(404);
    }

    return redirect()
        ->route('professor.manage-grades', [
            'offering_id' => $model->course_offering_id
        ])
        ->with('success', 'កែសម្រួលបានជោគជ័យ!');
}
}
