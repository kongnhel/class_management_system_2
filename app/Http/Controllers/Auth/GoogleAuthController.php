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
        // ទទួលបានទិន្នន័យពី Firebase/Google
        $googleEmail = $request->email;
        $googleId = $request->uid;
        $name = $request->name;

        // ស្វែងរកសិស្សតាមអ៊ីម៉ែល ឬ Google ID
        $user = User::where('email', $googleEmail)->first();

        if (!$user) {
            // បើអត់ទាន់មានគណនីទេ => ចុះឈ្មោះឱ្យអូតូ (Auto-Register)
            $user = User::create([
                'name' => $name,
                'email' => $googleEmail,
                'google_id' => $googleId,
                'password' => Hash::make(Str::random(24)), // បង្កើតលេខសម្ងាត់ចៃដន្យ
                'email_verified_at' => now(), // ចាត់ទុកថាបញ្ជាក់អ៊ីម៉ែលរួច
            ]);
        } else {
            // បើមានគណនីហើយ => គ្រាន់តែ Update Google ID បើមិនទាន់មាន
            if (!$user->google_id) {
                $user->update(['google_id' => $googleId]);
            }
        }

        // ធ្វើការ Login ចូលទៅក្នុង Laravel Session
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