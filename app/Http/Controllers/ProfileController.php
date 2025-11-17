<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse; // Added for update/destroy return types
use App\Models\UserProfile;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Added for email validation

class ProfileController extends Controller
{
    /**
     * Show the user's profile edit form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();

        // Fetch the profile picture URL if a UserProfile exists
        $userProfile = UserProfile::where('user_id', $user->id)->first();
        $profilePictureUrl = null;

        if ($userProfile && $userProfile->profile_picture_url) {
            // Generate the full URL for the profile picture
            $profilePictureUrl = Storage::disk('public')->url($userProfile->profile_picture_url);
        }

        return view('profile.edit', [
            'user' => $user,
            'profilePictureUrl' => $profilePictureUrl, // Pass the URL to the view
        ]);
    }

    /**
     * Update the user's profile picture.
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'profile_picture.required' => 'សូមជ្រើសរើសរូបភាពសម្រាប់ Profile Picture ។',
            'profile_picture.image' => 'File ដែលបាន upload ត្រូវតែជារូបភាព។',
            'profile_picture.mimes' => 'ទ្រង់ទ្រាយរូបភាពត្រូវជា JPEG, PNG, JPG, GIF, SVG ប៉ុណ្ណោះ។',
            'profile_picture.max' => 'ទំហំរូបភាពមិនត្រូវលើស 2MB ទេ។',
        ]);

        $user = Auth::user();

        // Get or create user profile
        $userProfile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            []
        );

        // Delete old profile picture if exists
        if ($userProfile->profile_picture_url && Storage::disk('public')->exists($userProfile->profile_picture_url)) {
            Storage::disk('public')->delete($userProfile->profile_picture_url);
        }

        // Store new file (relative path, e.g. "profile_pictures/xxxx.jpg")
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Save relative path in DB
        $userProfile->profile_picture_url = $path;
        $userProfile->save();

        Session::flash('success', 'Profile picture របស់អ្នកត្រូវបានអាប់ដេតដោយជោគជ័យ!');
        return redirect()->back();
    }

    // New method: Update the user's profile information
    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->fill($request->only('name', 'email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}