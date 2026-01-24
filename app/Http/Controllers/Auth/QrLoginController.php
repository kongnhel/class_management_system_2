<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Events\QrLoginSuccessful;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
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
    try {
        $token = $request->token;
        $user = Auth::user();

        if (Cache::has('login_token_' . $token)) {
            Cache::put('authorized_user_' . $token, $user->id, now()->addMinute());
            
            // ប្រើវិធីនេះដើម្បីធានាសុវត្ថិភាពកូដ
            // event(new QrLoginSuccessful($token, $user->id));
            broadcast(new QrLoginSuccessful($token, $user->id));

            return response()->json(['status' => 'success']);
        }
    } catch (\Exception $e) {
        // បើមាន Error វានឹងប្រាប់ក្នុង storage/logs/laravel.log
        Log::error("QR Scan Error: " . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Server Error'], 500);
    }
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