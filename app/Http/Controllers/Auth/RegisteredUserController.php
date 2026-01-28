<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program; // ត្រូវប្រាកដថាបាន import Program Model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $programs = Program::all(); // ទាញយកកម្មវិធីសិក្សាទាំងអស់ដើម្បីបង្ហាញក្នុង dropdown
        return view('auth.register', compact('programs'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate->Validation->ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // ឆែកមើលថា តើ Student ID ហ្នឹងមានក្នុង DB មែនអត់
    $user = User::where('student_id_code', $request->student_id_code)->first();

    if (!$user) {
        return back()->with('error', 'ប្រតិបត្តិការមិនជោគជ័យ! ទិន្នន័យសិស្សមិនត្រឹមត្រូវតាមប្រព័ន្ធរដ្ឋបាល។');
    }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules->Password::defaults()],
            'student_id_code' => ['required', 'string', 'max:20', 'unique:users,student_id_code'], // បន្ថែមសម្រាប់និស្សិត
            'program_id' => ['required', 'exists:programs,id'], // បន្ថែមសម្រាប់និស្សិត
            'generation' => ['required', 'string', 'max:255'], // បន្ថែមសម្រាប់និស្សិត
        ]);

        // ស្វែងរក Program ដើម្បីទទួលបាន Department ID
        $program = Program::findOrFail($request->program_id);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // កំណត់ role ជា 'student' ដោយស្វ័យប្រវត្តិសម្រាប់ការចុះឈ្មោះនេះ
            'student_id_code' => $request->student_id_code, // បន្ថែម student_id_code
            'program_id' => $request->program_id, // បន្ថែម program_id
            'department_id' => $program->department_id, // កំណត់ department_id ពី Program
            'email_verified_at' => null, // និស្សិតដែលចុះឈ្មោះដោយខ្លួនឯងមិនទាន់បានផ្ទៀងផ្ទាត់ email
            'generation' => $request->generation, // បន្ថែម generation
        ]);

        $user->update([
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'email_verified_at' => now(), // អាចចាត់ទុកថាជោគជ័យភ្លាម
    ]);
        event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false)); // អ្នកអាចប្តូរទៅ route ផ្សេងទៀតសម្រាប់និស្សិត
        Auth::login($user);
    return redirect(route('dashboard'))->with('success', 'សូមស្វាគមន៍មកកាន់ NMU Portal!');
    }

    public function checkStudent($code): JsonResponse 
{
    // ស្វែងរកសិស្សដែល Admin បង្កើតទុក (តែមិនទាន់មាន Email)
    $student = User::where('student_id_code', $code)
                   ->where('role', 'student')
                   ->with('program')
                   ->first();

    if ($student) {
        return response()->json([
            'success'      => true,
            'name'         => $student->name, // បញ្ជូនឈ្មោះដែល Admin បានដាក់
            'program_id'   => $student->program_id,
            'program_name' => $student->program->name_km ?? '',
            'generation'   => $student->generation,
        ]);
    }

    return response()->json(['success' => false]);
}
}
