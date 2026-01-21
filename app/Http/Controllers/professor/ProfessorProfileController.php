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
    
    // ៣. បង្ហោះរូបភាពទៅកាន់ ImgBB
    if ($request->hasFile('profile_picture')) { 
        try {
            $image = $request->file('profile_picture');
            
            // ប្រើប្រាស់ Http Facade ដើម្បីបញ្ជូនរូបភាពទៅកាន់ ImgBB API
            $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'), // ប្រាកដថាអ្នកមាន key ក្នុង .env
                'image' => base64_encode(file_get_contents($image->getRealPath())),
            ]);

            if ($response->successful()) {
                // រក្សាទុក URL ពេញលេញដែលទទួលបានពី ImgBB
                $userProfile->profile_picture_url = $response->json()['data']['url'];
            } else {
                \Log::error('ImgBB Upload Error: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            \Log::error('Upload Error: ' . $e->getMessage());
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
