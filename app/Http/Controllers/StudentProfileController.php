<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http; // សម្រាប់ប្រើប្រាស់ ImgBB API
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Program;
use App\Models\UserProfile;

class StudentProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->isStudent()) {
            Session::flash('error', 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យចូលប្រើទំព័រនេះទេ។');
            return redirect()->route('dashboard');
        }

        // ប្រើ userProfile តែមួយគត់សម្រាប់គ្រប់ Role
        $userProfile = $user->userProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        return view('student.profile.show', compact('user', 'userProfile'));
    }

    public function edit()
    {
        $user = Auth::user();

        if (!$user->isStudent()) {
            Session::flash('error', 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យចូលប្រើទំព័រនេះទេ។');
            return redirect()->route('dashboard');
        }

        $userProfile = $user->userProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);
        
        $programs = Program::all(); 

        return view('student.profile.edit', compact('user', 'userProfile', 'programs'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user->isStudent()) {
            Session::flash('error', 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យអនុវត្តសកម្មភាពនេះទេ។');
            return redirect()->route('dashboard');
        }

        $userProfile = $user->userProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        $validatedData = $request->validate([
            'full_name_km' => ['nullable', 'string', 'max:255'],
            'full_name_en' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        // ===========================================
        // បង្ហោះរូបភាពទៅកាន់ ImgBB
        // ===========================================
        if ($request->hasFile('profile_picture')) {
            try {
                $image = $request->file('profile_picture');
                
                // បញ្ជូនរូបភាពទៅកាន់ ImgBB API
                $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
                    'key' => env('IMGBB_API_KEY'), 
                    'image' => base64_encode(file_get_contents($image->getRealPath())),
                ]);

                if ($response->successful()) {
                    // រក្សាទុក URL ពេញលេញពី Cloud
                    $userProfile->profile_picture_url = $response->json()['data']['url'];
                }
            } catch (\Exception $e) {
                \Log::error('ImgBB Upload Error: ' . $e->getMessage());
            }
        } elseif ($request->has('remove_profile_picture') && $request->input('remove_profile_picture') === '1') {
            // លុបរូបភាព (កំណត់ត្រឹមតែ URL ក្នុង DB ឱ្យទៅជា null)
            $userProfile->profile_picture_url = null;
        }

        // ===========================================
        // ធ្វើបច្ចុប្បន្នភាពព័ត៌មាន Profile
        // ===========================================
        $userProfile->fill($validatedData);
        $userProfile->save();

        Session::flash('success', 'ព័ត៌មាន Profile ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
        return redirect()->route('student.profile.show');
    }
}
