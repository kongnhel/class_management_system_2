<?php

namespace App\Http\Controllers\professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\CourseOffering;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    /**
     * បង្ហាញបញ្ជី Quiz ទាំងអស់សម្រាប់ Course Offering ជាក់លាក់។
     *
     * @param  int  $offering_id
     */
    public function index($offering_id)
    {
        $user = Auth::user();
        
        // *** 1. ប្តូរឈ្មោះអថេរ $offering ទៅ $courseOffering ***
        $courseOffering = CourseOffering::findOrFail($offering_id);

        // 2. ត្រូវប្រាកដថាគ្រូបង្រៀនបច្ចុប្បន្នត្រូវបានចាត់តាំងទៅ Course Offering នេះ
        // if ($courseOffering->lecturer_id !== $user->id) {
        //      abort(403, 'អ្នកមិនមានសិទ្ធិចូលមើល Quiz សម្រាប់ Course Offering នេះទេ។');
        // }

        $quizzes = Quiz::where('course_offering_id', $offering_id)
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        // 3. ត្រឡប់ទៅ view សម្រាប់គ្រប់គ្រង Quiz ដោយប្រើ $courseOffering
        return view('professor.quiz.index', compact('courseOffering', 'quizzes'));
    }

    /**
     * រក្សាទុក Quiz ថ្មីមួយ។
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $offering_id
     */
    public function store(Request $request, $offering_id)
    {
        $offering = CourseOffering::findOrFail($offering_id);

        // // 1. ពិនិត្យការអនុញ្ញាត (Authorization Check)
        // if ($offering->lecturer_id !== auth()->id()) {
        //     return back()->with('error', 'អ្នកមិនមានសិទ្ធិបង្កើត Quiz ក្នុង Course នេះទេ។');
        // }

        // 2. ការត្រួតពិនិត្យទិន្នន័យ (Validation)
        $request->validate([
            'title_km' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_km' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            // ត្រូវបន្ថែម rules ទាំងពីរនេះ ព្រោះ Form ពីមុនមិនទាន់មាន input
            'max_attempts' => ['required', 'integer', 'min:1'], 
            'duration_minutes' => ['required', 'integer', 'min:1'],
            
            'max_score' => ['required', 'numeric', 'min:0'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
            'is_published' => ['boolean'],
        ]);

        // 3. បង្កើត Quiz
        Quiz::create([
            'course_offering_id' => $offering_id,
            'title_km' => $request->title_km,
            'title_en' => $request->title_en,
            'description_km' => $request->description_km,
            'description_en' => $request->description_en,
            'max_attempts' => $request->max_attempts, 
            'max_score' => $request->max_score,
            'duration_minutes' => $request->duration_minutes,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_published' => $request->boolean('is_published', false), // Set to false if not present
        ]);

        return redirect()->route('professor.quiz.index', $offering_id)
                         ->with('success', 'Quiz ថ្មីត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * បង្ហាញ Quiz ជាក់លាក់។
     *
     * @param  int  $offering_id
     * @param  \App\Models\Quiz  $quiz
     */
    public function show($offering_id, Quiz $quiz)
    {
        // ពិនិត្យការអនុញ្ញាត (Authorization check)
        // if ($quiz->course_offering->lecturer_id !== auth()->id() || $quiz->course_offering_id != $offering_id) {
        //     abort(403);
        // }
        
        return view('professor.quiz.show', compact('quiz'));
    }

    /**
     * កែប្រែ Quiz ជាក់លាក់។
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $offering_id
     * @param  \App\Models\Quiz  $quiz
     */
    public function update(Request $request, $offering_id, Quiz $quiz)
    {
        // // 1. ពិនិត្យការអនុញ្ញាត
        // if ($quiz->course_offering->lecturer_id !== auth()->id() || $quiz->course_offering_id != $offering_id) {
        //     return back()->with('error', 'អ្នកមិនមានសិទ្ធិកែប្រែ Quiz នេះទេ។');
        // }

        // 2. ការត្រួតពិនិត្យទិន្នន័យ (Validation)
        $request->validate([
            'title_km' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
          
            'max_attempts' => ['required', 'integer', 'min:1'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'max_score' => ['required', 'numeric', 'min:0'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
            'is_published' => ['boolean'],
        ]);

        // 3. កែប្រែ Quiz
        $quiz->update([
            'title_km' => $request->title_km,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'max_attempts' => $request->max_attempts,
            'max_score' => $request->max_score,
            'duration_minutes' => $request->duration_minutes,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_published' => $request->boolean('is_published', false),
        ]);

        return redirect()->route('professor.quiz.index', $offering_id)
                         ->with('success', 'Quiz ត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * លុប Quiz ។
     *
     * @param  int  $offering_id
     * @param  \App\Models\Quiz  $quiz
     */
    public function destroy($offering_id, Quiz $quiz)
    {
        // // 1. ពិនិត្យការអនុញ្ញាត
        // if ($quiz->course_offering->lecturer_id !== auth()->id() || $quiz->course_offering_id != $offering_id) {
        //     return back()->with('error', 'អ្នកមិនមានសិទ្ធិលុប Quiz នេះទេ។');
        // }

        // 2. លុប Quiz
        $quiz->delete();

        return redirect()->route('professor.quiz.index', $offering_id)
                         ->with('success', 'Quiz ត្រូវបានលុបដោយជោគជ័យ។');
    }
    
    /**
     * បង្ហាញទំព័រគ្រប់គ្រងសំណួររបស់ Quiz ។
     *
     * @param  int  $offering_id
     * @param  \App\Models\Quiz  $quiz
     */
    public function manageQuestions($offering_id, Quiz $quiz)
    {
        // // 1. ពិនិត្យការអនុញ្ញាត
        // if ($quiz->course_offering->lecturer_id !== auth()->id() || $quiz->course_offering_id != $offering_id) {
        //     abort(403, 'អ្នកមិនមានសិទ្ធិចូលមើលទំព័រគ្រប់គ្រងសំណួរនេះទេ។');
        // }
        
        // បង្ហាញទំព័រគ្រប់គ្រងសំណួរ (The view name might need adjustment based on your structure)
        return view('professor.quiz.manage-questions', compact('quiz', 'offering_id'));
    }
}