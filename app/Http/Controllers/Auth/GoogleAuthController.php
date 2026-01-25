<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
public function handleCallback(Request $request)
{
    $googleEmail = $request->email;
    $googleId = $request->uid;

    // ១. ស្វែងរក User ក្នុងប្រព័ន្ធតាម Email
    $user = User::where('email', $googleEmail)->first();

    if (!$user) {
        // ប្រសិនបើរកមិនឃើញ Email ក្នុង Table users ទេ មានន័យថាគាត់មិនទាន់បាន Register តាម Student ID ឡើយ
        return response()->json([
            'status' => 'error',
            'message' => 'គណនី Google នេះមិនទាន់បានចុះឈ្មោះក្នុងប្រព័ន្ធ NMU ឡើយ។ សូមចុះឈ្មោះដោយប្រើលេខសម្គាល់និស្សិតជាមុនសិន!'
        ], 403);
    }

    // ២. បើមានគណនីហើយ ត្រូវ Update Google ID បើមិនទាន់មាន
    if (!$user->google_id) {
        $user->update(['google_id' => $googleId]);
    }

    // ៣. ធ្វើការ Login ឱ្យសិស្ស
    Auth::login($user);

    return response()->json(['status' => 'success']);
}

    public function linkAccount(Request $request)
{
    $user = Auth::user(); // យក User ដែលកំពុង Login ស្រាប់
    
    // Update ព័ត៌មាន Google ចូលទៅក្នុង Account គាត់
    $user->update([
        'google_id' => $request->uid,
        'avatar' => $request->photoURL, // យករូបភាព Profile មកជាមួយ
    ]);

    return response()->json(['status' => 'linked']);
}
}