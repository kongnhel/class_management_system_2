<?php

namespace App\Http\Controllers;

use App\Models\ProfessorProfile;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Program;

class ProfessorProfileController extends Controller
{
    // updateProfilePicture
    /**
     * មុខងារជំនួយសម្រាប់ទាញយក Profile របស់សាស្ត្រាចារ្យដែលបានផ្ទៀងផ្ទាត់។
     *
     * @return \App\Models\ProfessorProfile
     * @throws \Exception
     */
    private function getProfessorProfile()
    {
        $user = Auth::user();

        // ត្រូវប្រាកដថាអ្នកប្រើប្រាស់គឺជាសាស្ត្រាចារ្យ
        if (!$user->isProfessor()) {
            Session::flash('error', 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យចូលប្រើទំព័រនេះទេ។');
            abort(403, 'Unauthorized access.'); // បញ្ចប់ដំណើរការដោយមានកំហុស 403
        }

        // ផ្ទុក professorProfile, firstOrCreate គឺប្រសើរណាស់
        return $user->professorProfile()->firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * បង្ហាញបញ្ជីប្រវត្តិរូបសាស្ត្រាចារ្យទាំងអស់។
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ប្រើ with() ដើម្បី eager load professorProfile និង department ដើម្បីកាត់បន្ថយ queries
        $professors = User::where('role', 'professor')
                            ->with('professorProfile.department')
                            ->paginate(10); // បញ្ចូល pagination សម្រាប់ដំណើរការកាន់តែប្រសើរ

        return view('professor.profile.index', compact('professors'));
    }

    /**
     * បង្ហាញទម្រង់ Profile របស់សាស្ត្រាចារ្យដែលបានផ្ទៀងផ្ទាត់។
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        try {
            $professorProfile = $this->getProfessorProfile();
            $user = Auth::user();
            return view('professor.profile.show', compact('user', 'professorProfile'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard');
        }
    }
    
    /**
     * បង្ហាញទម្រង់សម្រាប់កែប្រែ Profile របស់សាស្ត្រាចារ្យដែលបានផ្ទៀងផ្ទាត់។
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        try {
            $professorProfile = $this->getProfessorProfile();
            $user = Auth::user();
            
            // ផ្ទុកបញ្ជីនាយកដ្ឋានទាំងអស់សម្រាប់ dropdown
            $departments = Department::all();
            
            return view('professor.profile.edit', compact('user', 'professorProfile', 'departments'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard');
        }
    }

    /**
     * ធ្វើបច្ចុប្បន្នភាព Profile របស់សាស្ត្រាចារ្យដែលបានផ្ទៀងផ្ទាត់នៅក្នុងកន្លែងផ្ទុក។
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $professorProfile = $this->getProfessorProfile();
        } catch (\Exception $e) {
            return redirect()->route('dashboard');
        }

        // ===========================================
        // ការផ្ទៀងផ្ទាត់សម្រាប់ព័ត៌មាន Profile
        // ===========================================
        $validatedData = $request->validate([
            'full_name_km' => ['nullable', 'string', 'max:255'],
            'full_name_en' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'staff_id' => ['nullable', 'string', 'max:255', Rule::unique('professor_profiles')->ignore($professorProfile->id)],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position' => ['nullable', 'string', 'max:255'],
            'qualifications' => ['nullable', 'string'],
            'specializations' => ['nullable', 'string'],
        ]);

        // ===========================================
        // ធ្វើបច្ចុប្បន្នភាពរូបភាព Profile ដាច់ដោយឡែក
        // ===========================================
        if ($request->hasFile('profile_picture')) {
            // លុបរូបភាពចាស់ ប្រសិនបើមាន
            if ($professorProfile->profile_picture_url) {
                Storage::disk('public')->delete($professorProfile->profile_picture_url);
            }
            // រក្សាទុករូបភាពថ្មី
            $validatedData['profile_picture_url'] = $request->file('profile_picture')->store('profile_pictures', 'public');
            // ដក 'profile_picture' ចេញពី array ដែលបានផ្ទៀងផ្ទាត់
            unset($validatedData['profile_picture']);
        } elseif ($request->has('remove_profile_picture') && $request->input('remove_profile_picture') === '1') {
            // លុបរូបភាព ប្រសិនបើ checkbox ត្រូវបានគូស
            if ($professorProfile->profile_picture_url) {
                Storage::disk('public')->delete($professorProfile->profile_picture_url);
            }
            $validatedData['profile_picture_url'] = null;
        }

        // ធ្វើបច្ចុប្បន្នភាព Profile ដោយប្រើ Mass Assignment
        $professorProfile->update($validatedData);

        Session::flash('success', 'ព័ត៌មាន Profile ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
        return redirect()->route('professor.profile.show');
    }

    /**
     * លុបប្រវត្តិរូបសាស្ត្រាចារ្យចេញពីកន្លែងផ្ទុក។
     *
     * @param  \App\Models\ProfessorProfile  $professorProfile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProfessorProfile $professorProfile)
    {
        // លុបរូបភាព Profile ប្រសិនបើមាន
        if ($professorProfile->profile_picture_url) {
            Storage::disk('public')->delete($professorProfile->profile_picture_url);
        }

        // លុបអ្នកប្រើប្រាស់ដែលពាក់ព័ន្ធ
        if ($professorProfile->user) {
            $professorProfile->user->delete();
        }

        // លុប ProfessorProfile ខ្លួនឯង
        $professorProfile->delete();

        Session::flash('success', 'ប្រវត្តិរូបសាស្ត្រាចារ្យត្រូវបានលុបដោយជោគជ័យ!');
        return redirect()->route('professor.profiles.index');
    }
}
