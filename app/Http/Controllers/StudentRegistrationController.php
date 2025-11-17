<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Program;
use App\Models\Course;
use App\Models\StudentProgramEnrollment; // Model នេះត្រូវបានប្រើសម្រាប់ចុះឈ្មោះ Program
use App\Models\StudentCourseEnrollment; // � ឥឡូវនេះយើងនឹងប្រើ Model នេះសម្រាប់ការចុះឈ្មោះមុខវិជ្ជា
use App\Models\CourseOffering; // 💡 បានបន្ថែម CourseOffering Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB; // 💡 បានបន្ថែមសម្រាប់ Database Transactions

class StudentRegistrationController extends Controller
{
    /**
     * Show the registration form
     */
    public function create()
    {
        // Fetch all programs to pass to the registration view
        $programs = Program::all();
        $generations = User::select('generation')->distinct()->pluck('generation')->filter()->all();
        return view('auth.register', compact('programs','generations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id_code' => [
                'required',
                'string',
                'max:255',
                Rule::exists('users', 'student_id_code')->where(function ($query) {
                    return $query->where('role', 'student')
                                 ->whereNull('email')
                                 ->whereNull('password');
                                 
                }),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'password' => ['required', 'confirmed', 'min:8'],
            'generation' => 'required|string|max:255',
        ], [
            'student_id_code.exists' => 'លេខសម្គាល់និស្សិតនេះមិនមាន ឬត្រូវបានប្រើប្រាស់រួចហើយ។',
            'email.unique' => 'អ៊ីម៉ែលនេះត្រូវបានប្រើប្រាស់រួចហើយ។ សូមប្រើអ៊ីម៉ែលផ្សេងទៀត។',
            'program_id.required' => 'សូមជ្រើសរើសកម្មវិធីសិក្សា។',
            'program_id.exists' => 'កម្មវិធីសិក្សាដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
            'generation.required' => 'សូមបញ្ចូលជំនាន់។',
            
        ]);

        // 💡 រុំ logic ទាំងមូលនៅក្នុង database transaction ដើម្បីធានាសុចរិតភាពទិន្នន័យ
        DB::transaction(function () use ($request) {
            // 🟢 Find the existing user
            $user = User::where('student_id_code', $request->student_id_code)
                        ->where('role', 'student')
                        ->whereNull('email')
                        ->whereNull('password')
                        ->firstOrFail();

            // 🟢 Update user info
            $user->forceFill([
                'name' => $request->name,
                'email' => $request->email,
                'generation' => $request->generation,
                'password' => Hash::make($request->password),
                'email_verified_at' => null,
            ])->save();

            // 🟢 Save enrollment into student_program_enrollments table
            StudentProgramEnrollment::firstOrCreate([
                'student_user_id' => $user->id,
                'program_id' => $request->program_id,
            ], [
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            // 🟢 Auto-enroll student in all relevant course offerings of the program
            // 💡 ទាញយក CourseOfferings ទាំងអស់ដែលជាផ្នែកមួយនៃ Program ដែលបានជ្រើសរើស
            //    ហើយដែលមិនទាន់ផុតកំណត់។
            $programCourseOfferings = CourseOffering::where('generation', $request->generation)
                                                    ->whereHas('course', function ($query) use ($request) {
                                                    $query->where('program_id', $request->program_id);
                                                })
                                                ->where('end_date', '>=', now()) // តែ CourseOffering ដែលមិនទាន់ចប់
                                                ->get();

            foreach ($programCourseOfferings as $courseOffering) {
                StudentCourseEnrollment::firstOrCreate([
                    'student_user_id' => $user->id,
                    'course_offering_id' => $courseOffering->id,
                ], [
                    'enrollment_date' => now(),
                    'status' => 'enrolled',
                ]);
            }

            // Fire event and login
            event(new Registered($user));
            Auth::login($user);
        });

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'ការចុះឈ្មោះរបស់អ្នកបានជោគជ័យ ហើយបានរក្សាទុកការចូលរៀនក្នុងកម្មវិធីសិក្សា និងមុខវិជ្ជាទាំងអស់!');
    }
}
