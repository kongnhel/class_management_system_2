<?php

namespace App\Http\Controllers\professor;
use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class QuizController extends Controller
{
     public function index($offering_id)
    {
        $courseOffering = CourseOffering::with('course')->findOrFail($offering_id);
        $quizzes = Quiz::where('course_offering_id', $offering_id)
                         ->with('quizQuestions')
                         ->orderBy('start_date', 'asc')
                         ->paginate(10);

        return view('professor.quizzes.index', compact('courseOffering', 'quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create($offering_id)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        return view('professor.quizzes.create', compact('courseOffering'));
    }
    /**
     * Store a newly created quiz in storage.
     */
    public function store(Request $request, $offering_id)
    {
        $validatedData = $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_score' => 'required|numeric|min:0',
        ]);

        $courseOffering = CourseOffering::where('id', $offering_id)
                                         ->where('lecturer_user_id', Auth::id())
                                         ->firstOrFail();
        
        $quizData = $validatedData;
        $quizData['course_offering_id'] = $courseOffering->id;
        $quizData['total_points'] = $validatedData['max_score'];

        $quiz = Quiz::create($quizData);

        return redirect()->route('professor.quizzes.index', ['quiz' => $quiz->id])
                         ->with('success', 'Quiz ត្រូវបានបង្កើតដោយជោគជ័យ! សូមបន្ថែមសំណួរឥឡូវនេះ។');
    }
    
    /**
     * Show the form for editing a quiz.

     * Update the specified quiz in storage.
     */
    public function edit($offering_id, Quiz $quiz)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        return view('professor.quizzes.edit', compact('quiz', 'courseOffering'));
    }
    
    public function update(Request $request, $offering_id, Quiz $quiz)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        $validatedData = $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_score' => 'required|numeric|min:0',
        ]);
        
        $quiz->update($validatedData);

        return redirect()->route('professor.manage-quizzes', ['offering_id' => $offering_id])
                         ->with('success', 'Quiz ត្រូវបានកែសម្រួលដោយជោគជ័យ!');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy($offering_id, Quiz $quiz)
    {
        $courseOffering = CourseOffering::findOrFail($offering_id);
        $quiz->delete();

        return redirect()->route('professor.manage-quizzes', ['offering_id' => $offering_id])
                         ->with('success', 'Quiz ត្រូវបានលុបដោយជោគជ័យ!');
    }
   
}
