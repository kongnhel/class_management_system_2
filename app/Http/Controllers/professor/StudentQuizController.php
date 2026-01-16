<?php

namespace App\Http\Controllers\professor;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class StudentQuizController extends Controller
{

    public function index()
    {
        // ក្នុងកម្មវិធីពិត អ្នកគួរតែត្រងតាម course offering របស់សិស្ស
        $quizzes = Quiz::all(); 

        return view('student.quizzes.index', compact('quizzes'));
    }

    /**
     * បង្ហាញព័ត៌មានលម្អិតរបស់ Quiz និងប្រវត្តិប៉ុនប៉ង។
     */
    public function show(Quiz $quiz)
    {
        $userId = Auth::id(); // សន្មត់ថា user ត្រូវបាន authenticated

        // ទទួលបានប្រវត្តិប៉ុនប៉ងសម្រាប់ Quiz នេះ
        $attempts = $quiz->attempts()
                         ->where('user_id', $userId)
                         ->orderBy('attempt_number', 'desc')
                         ->get();

        $canAttempt = $attempts->count() < $quiz->max_attempts;
        
        // ពិនិត្យមើលពេលវេលាចាប់ផ្តើម/បញ្ចប់
        $isAvailable = true; 
        if ($quiz->start_time && $quiz->start_time->isFuture()) {
            $isAvailable = false;
        }
        if ($quiz->end_time && $quiz->end_time->isPast()) {
            $isAvailable = false;
        }

        return view('student.quizzes.show', compact('quiz', 'attempts', 'canAttempt', 'isAvailable'));
    }

    /**
     * ចាប់ផ្តើមការប៉ុនប៉ង Quiz ថ្មី។
     */
    public function startAttempt(Quiz $quiz)
    {
        $userId = Auth::id();

        // 1. ពិនិត្យមើលការអនុញ្ញាត
        $currentAttempts = $quiz->attempts()->where('user_id', $userId)->count();

        if ($currentAttempts >= $quiz->max_attempts) {
            return back()->with('error', 'អ្នកបានប្រើប្រាស់ចំនួនប៉ុនប៉ងអតិបរមា ('. $quiz->max_attempts .') ដែលបានកំណត់ហើយ។');
        }

        // 2. ពិនិត្យពេលវេលា
        if ($quiz->start_time && $quiz->start_time->isFuture()) {
            return back()->with('error', 'Quiz នេះមិនទាន់ដល់ពេលចាប់ផ្តើមនៅឡើយទេ។');
        }
        if ($quiz->end_time && $quiz->end_time->isPast()) {
            return back()->with('error', 'Quiz នេះបានផុតកំណត់ហើយ។');
        }

        // 3. បង្កើត QuizAttempt ថ្មី
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $userId,
            'attempt_number' => $currentAttempts + 1,
            'started_at' => Carbon::now(),
            'status' => 'started',
            'score' => null,
        ]);

        // 4. Redirect ទៅទំព័រសម្រាប់ធ្វើ Quiz (យើងនឹងបង្កើត route នេះនៅជំហានបន្ទាប់)
        return redirect()->route('student.quizzes.take', $attempt->id);
    }
    
    /**
     * បង្ហាញទម្រង់សម្រាប់ធ្វើ Quiz (យើងនឹងបង្កើតមុខងារនេះបន្ទាប់ពី route)
     */
    public function take(QuizAttempt $attempt)
    {
        // ពិនិត្យមើលថាការប៉ុនប៉ងនេះជាកម្មសិទ្ធិរបស់ user បច្ចុប្បន្ន
        if ($attempt->user_id !== Auth::id() || $attempt->status !== 'started') {
            return redirect()->route('student.quizzes.show', $attempt->quiz_id)
                             ->with('error', 'ការប៉ុនប៉ងមិនត្រឹមត្រូវ ឬត្រូវបានបញ្ចប់ហើយ។');
        }

        // ផ្ទុកសំណួរ និងជម្រើសរបស់វា
        $quiz = $attempt->quiz()->with('questions.options')->first();

        // គណនាពេលវេលាដែលនៅសល់
        $duration = $quiz->duration_minutes * 60; // វិនាទី
        $elapsed = Carbon::now()->diffInSeconds($attempt->started_at);
        $timeRemaining = max(0, $duration - $elapsed);
        
        // បង្ហាញទំព័រធ្វើ Quiz
        return view('student.quizzes.take', compact('attempt', 'quiz', 'timeRemaining'));
    }

    /**
     * ដាក់ស្នើចម្លើយរបស់ Quiz (យើងនឹងបង្កើតមុខងារនេះបន្ទាប់)
     */
    public function submit(Request $request, QuizAttempt $attempt)
    {
        // Logic សម្រាប់រក្សាទុកចម្លើយ និង Grading នឹងត្រូវបានបន្ថែមនៅពេលក្រោយ
        
        // ត្រូវប្រាកដថា Attempt មិនទាន់ត្រូវបានបញ្ចប់
        if ($attempt->status === 'finished') {
            return back()->with('error', 'ការប៉ុនប៉ងនេះត្រូវបានបញ្ចប់ហើយ។');
        }

        // ... ដំណាក់កាលទី 1: រក្សាទុកចម្លើយទៅក្នុង QuizAnswer Table ...
        // ... ដំណាក់កាលទី 2: គណនាពិន្ទុ (Grading) ...
        
        // បន្ទាប់ពី Grading
        $attempt->update([
            'status' => 'finished',
            'finished_at' => Carbon::now(),
            // 'score' => $finalCalculatedScore, // នឹងត្រូវបានកំណត់បន្ទាប់ពី grading
        ]);

        return redirect()->route('student.quizzes.show', $attempt->quiz_id)
                         ->with('success', 'អ្នកបានដាក់ស្នើ Quiz ដោយជោគជ័យ។');
    }
}