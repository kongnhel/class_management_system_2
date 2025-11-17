<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Program; // Assuming StudentProfile can link to Program directly or through User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage; // For handling file uploads

class StudentProfileController extends Controller
{
    /**
     * បង្ហាញទម្រង់ Profile របស់និស្សិតដែលបានផ្ទៀងផ្ទាត់។
     * Display the authenticated student's profile.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        $user = Auth::user();

        // ត្រូវប្រាកដថាអ្នកប្រើប្រាស់គឺជាសិស្ស
        if (!$user->isStudent()) {
            Session::flash('error', 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យចូលប្រើទំព័រនេះទេ។');
            return redirect()->route('dashboard');
        }

        // ផ្ទុក StudentProfile
        // ប្រសិនបើ StudentProfile មិនទាន់មាន វានឹងបង្កើតថ្មីមួយដោយស្វ័យប្រវត្តិ
        $studentProfile = $user->studentProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        return view('student.profile.show', compact('user', 'studentProfile'));
    }

    /**
     * បង្ហាញទម្រង់សម្រាប់កែប្រែ Profile របស់និស្សិតដែលបានផ្ទៀងផ្ទាត់។
     * Display the form for editing the authenticated student's profile.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        $user = Auth::user();

        // ត្រូវប្រាកដថាអ្នកប្រើប្រាស់គឺជាសិស្ស
        if (!$user->isStudent()) {
            Session::flash('error', 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យចូលប្រើទំព័រនេះទេ។');
            return redirect()->route('dashboard');
        }

        // ផ្ទុក StudentProfile
        // ប្រសិនបើ StudentProfile មិនទាន់មាន វានឹងបង្កើតថ្មីមួយដោយស្វ័យប្រវត្តិ
        $studentProfile = $user->studentProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);
        
        // សម្រាប់ dropdown កម្មវិធីសិក្សា (ប្រសិនបើចាំបាច់នៅក្នុងទម្រង់កែប្រែ Profile របស់និស្សិត)
        $programs = Program::all(); 

        return view('student.profile.edit', compact('user', 'studentProfile', 'programs'));
    }

    /**
     * ធ្វើបច្ចុប្បន្នភាព Profile របស់និស្សិតដែលបានផ្ទៀងផ្ទាត់នៅក្នុងកន្លែងផ្ទុក។
     * Update the authenticated student's profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // ត្រូវប្រាកដថាអ្នកប្រើប្រាស់គឺជាសិស្ស
        if (!$user->isStudent()) {
            Session::flash('error', 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យអនុវត្តសកម្មភាពនេះទេ។');
            return redirect()->route('dashboard');
        }

        // ស្វែងរក ឬបង្កើត Profile របស់សិស្ស
        $studentProfile = $user->studentProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        // ===========================================
        // ការផ្ទៀងផ្ទាត់សម្រាប់ព័ត៌មាន Profile និស្សិត
        // ===========================================
        $validatedData = $request->validate([
            'full_name_km' => ['nullable', 'string', 'max:255'],
            'full_name_en' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        // ===========================================
        // ធ្វើបច្ចុប្បន្នភាពរូបភាព Profile
        // ===========================================
        if ($request->hasFile('profile_picture')) {
            // លុបរូបភាពចាស់ ប្រសិនបើមាន
            if ($studentProfile->profile_picture_url && Storage::disk('public')->exists($studentProfile->profile_picture_url)) {
                Storage::disk('public')->delete($studentProfile->profile_picture_url);
            }
            // រក្សាទុករូបភាពថ្មី
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $studentProfile->profile_picture_url = $path;
        } elseif ($request->has('remove_profile_picture') && $request->input('remove_profile_picture') === '1') {
            // លុបរូបភាព ប្រសិនបើ checkbox ត្រូវបានគូស
            if ($studentProfile->profile_picture_url && Storage::disk('public')->exists($studentProfile->profile_picture_url)) {
                Storage::disk('public')->delete($studentProfile->profile_picture_url);
            }
            $studentProfile->profile_picture_url = null;
        }

        // ===========================================
        // ធ្វើបច្ចុប្បន្នភាព Profile និស្សិត
        // ===========================================
        $studentProfile->full_name_km = $validatedData['full_name_km'] ?? $studentProfile->full_name_km;
        $studentProfile->full_name_en = $validatedData['full_name_en'] ?? $studentProfile->full_name_en;
        $studentProfile->date_of_birth = $validatedData['date_of_birth'] ?? $studentProfile->date_of_birth;
        $studentProfile->gender = $validatedData['gender'] ?? $studentProfile->gender;
        $studentProfile->phone_number = $validatedData['phone_number'] ?? $studentProfile->phone_number;
        $studentProfile->address = $validatedData['address'] ?? $studentProfile->address;
        $studentProfile->save();

        Session::flash('success', 'ព័ត៌មាន Profile ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
        return redirect()->route('student.profile.show');
    }
}
