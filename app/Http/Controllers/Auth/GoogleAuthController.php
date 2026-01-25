<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    /**
     * សម្រាប់ Login តាមរយៈ Google (ទំព័រដើម)
     */
public function handleCallback(Request $request)
{
    $googleEmail = $request->email;
    $googleId = $request->uid;

    // ១. ស្វែងរក User ដោយប្រើ Google ID ជាអាទិភាព (ព្រោះបងបាន Link រួចហើយ)
    $user = User::where('google_id', $googleId)
                ->orWhere('email', $googleEmail)
                ->first();

    if (!$user) {
        // បើរកទាំងពីរហ្នឹងហើយ នៅតែមិនឃើញទៀត បានន័យថាគាត់មិនទាន់មាន Account មែន
        return response()->json([
            'status' => 'error',
            'message' => 'គណនី Google នេះមិនទាន់បានចុះឈ្មោះក្នុងប្រព័ន្ធ NMU ឡើយ។ សូមចុះឈ្មោះជាមុនសិន!'
        ], 403);
    }

    // ២. ប្រសិនបើរកឃើញ User តែគាត់មិនទាន់មាន google_id (ករណីគាត់ទើបតែ Register ថ្មី)
    if (!$user->google_id) {
        $user->update(['google_id' => $googleId]);
    }

    // ៣. ធ្វើការ Login ឱ្យគាត់
    Auth::login($user);

    return response()->json(['status' => 'success']);
}

    /**
     * សម្រាប់ភ្ជាប់គណនី (Link Google Account) ក្នុង Dashboard
     */
    public function linkAccount(Request $request)
    {
        // យក User ដែលកំពុង Login ស្រាប់តាមរយៈ Session
        $user = Auth::user(); 
\Log::info('Google UID Received: ' . $request->uid);
        if ($user) {
            // ធ្វើការបច្ចុប្បន្នភាព និងរក្សាទុកក្នុង MySQL
            $user->update([
                'google_id' => $request->uid,
                'avatar'    => $request->photoURL, // រក្សាទុករូបភាព Profile ពី Google
            ]);

            return response()->json([
                'status' => 'linked',
                'message' => 'គណនីត្រូវបានភ្ជាប់ដោយជោគជ័យ'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'មិនមានសិទ្ធិចូលប្រើប្រាស់'
        ], 401);
    }
}