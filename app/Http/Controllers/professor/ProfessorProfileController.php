<?php

namespace App\Http\Controllers\professor;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Program;

class ProfessorProfileController extends Controller
{
 public function showProfile()
    {
        $user = Auth::user();
        $userProfile = $user->userProfile;
        if (!$userProfile) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
        }

        return view('professor.profile.show', compact('user', 'userProfile'));
    }

    /**
     * Show the form for editing the professor's profile.
     */
    public function editProfile()
    {
        $user = Auth::user();
        $userProfile = $user->userProfile;
        if (!$userProfile) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
        }

        return view('professor.profile.edit', compact('user', 'userProfile'));
    }




public function updateProfile(Request $request) {
    $user = Auth::user();
    
    // ១. ការត្រួតពិនិត្យទិន្នន័យ (Validation)
    $request->validate([
        'full_name_km' => 'required|string|max:255',
        'full_name_en' => 'nullable|string|max:255',
        'gender' => 'required|in:male,female',
        'date_of_birth' => 'nullable|date',
        'phone_number' => 'nullable|string|max:20',
        'telegram_user' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // ២. ទាញយក ឬបង្កើត Profile ថ្មី
    $userProfile = $user->userProfile()->firstOrNew(['user_id' => $user->id]);
    
// ៣. បង្ហោះរូបភាពទៅកាន់ ImageKit
if ($request->hasFile('profile_picture')) { 
    try {
        $image = $request->file('profile_picture');
        
        // បញ្ជូនរូបភាពទៅកាន់ ImageKit API
        // ចំណាំ៖ ImageKit តម្រូវឱ្យប្រើ Private Key ក្នុង Basic Auth (Username = Private Key, Password = ទទេ)
        $response = Http::withBasicAuth(env('IMAGEKIT_PRIVATE_KEY'), '')
            ->attach(
                'file', 
                file_get_contents($image->getRealPath()), 
                $image->getClientOriginalName()
            )
            ->post('https://upload.imagekit.io/api/v1/files/upload', [
                'fileName' => 'student_' . time() . '_' . auth()->id(), // ដាក់ឈ្មោះឱ្យប្លែកគ្នា
                'useUniqueFileName' => 'true',
                'folder' => '/student_profiles', // បែងចែក Folder ឱ្យមានរបៀប
            ]);

        if ($response->successful()) {
            // រក្សាទុក URL ពេញលេញដែលទទួលបានពី ImageKit
            $userProfile->profile_picture_url = $response->json()['url'];
        } else {
            Log::error('ImageKit Upload Error: ' . $response->body());
        }
        
    } catch (\Exception $e) {
        Log::error('Upload Error: ' . $e->getMessage());
    }
}
    // ៤. រក្សាទុកទិន្នន័យផ្សេងៗចូលក្នុង Database
    $userProfile->fill($request->except(['profile_picture']));
    $userProfile->save();
    
    return redirect()
        ->route('professor.profile.show')
        ->with('success', 'ប្រវត្តិរូបរបស់អ្នកត្រូវបានកែប្រែដោយជោគជ័យ!');
}
}
