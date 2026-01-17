<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse; // Added for update/destroy return types
use App\Models\UserProfile;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Added for email validation
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    /**
     * Show the user's profile edit form.
     */
public function edit(Request $request): View
{
    $user = Auth::user();

    // ទាញយក Profile របស់ User មិនថាគេជា Admin, Professor ឬ Student ឡើយ
    $userProfile = UserProfile::where('user_id', $user->id)->first();
    
    // URL ពី ImgBB គឺជា Direct Link ស្រាប់
    $profilePictureUrl = $userProfile?->profile_picture_url;

    return view('profile.edit', [
        'user' => $user,
        'profilePictureUrl' => $profilePictureUrl, 
    ]);
}

/**
 * ធ្វើបច្ចុប្បន្នភាពរូបភាព Profile តាមរយៈ ImgBB
 */
public function updateProfilePicture(Request $request)
{
    $request->validate([
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ], [
        'profile_picture.required' => 'សូមជ្រើសរើសរូបភាពសម្រាប់ Profile Picture ។',
        'profile_picture.image' => 'File ដែលបាន upload ត្រូវតែជារូបភាព។',
        'profile_picture.max' => 'ទំហំរូបភាពមិនត្រូវលើស 2MB ទេ។',
    ]);

    $user = Auth::user();

    if ($request->hasFile('profile_picture')) {
        try {
            $image = $request->file('profile_picture');
            
            // បញ្ជូនរូបភាពទៅកាន់ ImgBB API
            $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'), 
                'image' => base64_encode(file_get_contents($image->getRealPath())),
            ]);

            if ($response->successful()) {
                $imageUrl = $response->json()['data']['url'];

                // រក្សាទុកក្នុង Table user_profiles តែមួយគត់
                UserProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    ['profile_picture_url' => $imageUrl]
                );
                
                Session::flash('success', 'រូបភាព Profile ត្រូវបានអាប់ដេតដោយជោគជ័យ!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['profile_picture' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()]);
        }
    }

    return redirect()->back();
}
    /**
     * Update the user's profile information.
     */


    // New method: Delete the user's account
    /**
     * Delete the user's account.
     */
  public function destroy(Request $request): RedirectResponse
{
    $request->validateWithBag('userDeletion', [
        'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();
    Auth::logout();

    // លុប Profile ពី Table user_profiles
    if ($user->userProfile) {
        $user->userProfile->delete();
    }

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}
}