<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Events\QrLoginSuccessful;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class QrLoginController extends Controller
{
    /**
     * ១. បង្ហាញទំព័រដែលមាន QR Code (លើ Desktop)
     * ចំណាំ៖ ប្រសិនបើបងប្រើ AuthenticatedSessionController រួចហើយ បងអាចមិនបាច់ប្រើ Method នេះក៏បាន
     */
    public function showQrForm()
    {
        $token = (string) Str::uuid();
        Cache::put('login_token_' . $token, true, now()->addMinutes(2));

        $qrCode = QrCode::size(250)
            ->color(16, 185, 129)
            ->margin(1)
            ->generate($token);

        return view('auth.login', compact('qrCode', 'token'));
    }

    /**
     * ២. ទទួលការ Scan ពីទូរស័ព្ទ (HTTPS Web Scan)
     */
public function handleScan(Request $request)
{
    $request->validate([
        'token' => 'required|string',
    ]);

    $token = $request->token;
    $user = Auth::user(); // អ្នកដែលកំពុងកាន់ទូរស័ព្ទស្កែន

    // ពិនិត្យមើលថា Token នេះមានក្នុង Cache និងមិនទាន់ផុតកំណត់
    if (Cache::has('login_token_' . $token)) {
        
        // រក្សាទុក User ID ភ្ជាប់ជាមួយ Token ក្នុង Cache រយៈពេល ១ នាទី
        Cache::put('authorized_user_' . $token, $user->id, now()->addMinute());
        
        // ប្រើ dispatchSync ដើម្បីបង្ខំឱ្យវាផ្ញើសញ្ញាទៅកាន់ Pusher ភ្លាមៗ (កុំប្រើជាន់គ្នា)
        \App\Events\QrLoginSuccessful::dispatchSync($token, $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'បញ្ជាក់អត្តសញ្ញាណជោគជ័យ! Computer កំពុងចូលប្រព័ន្ធ...'
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'QR Code មិនត្រឹមត្រូវ ឬបានផុតកំណត់។'
    ], 400);
}

    /**
     * ៣. មុខងារបញ្ចប់ការ Login លើ Computer ក្រោយទទួលសញ្ញាបាន
     */
    public function finalizeLogin($token)
    {
        // ទាញយក ID ចេញពី Cache រួចលុបវាចោលភ្លាម (Pull) ដើម្បីសុវត្ថិភាព
        $userId = Cache::pull('authorized_user_' . $token);

        if ($userId) {
            Auth::loginUsingId($userId);
            
            // Redirect ទៅកាន់ Dashboard តាមស្តង់ដារ Laravel 12
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return redirect()->route('login')->with('error', 'ការចូលប្រើប្រាស់ផុតកំណត់។');
    }
    
}