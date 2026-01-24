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
    // ១. បង្ហាញទំព័រដែលមាន QR Code (លើ Desktop)
    public function showQrForm()
    {
        $token = (string) Str::uuid();
        // រក្សាទុក Token ក្នុង Cache រយៈពេល ២ នាទី (សម្រាប់សុវត្ថិភាព)
        Cache::put('login_token_' . $token, true, now()->addMinutes(2));

        $qrCode = QrCode::size(250)->generate($token);
        return view('auth.qr-login', compact('qrCode', 'token'));
    }

    // ២. API សម្រាប់ទូរស័ព្ទហៅមក (ក្រោយពេល Scan)
public function handleScan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = $request->token;
        $user = Auth::user(); // ទាញយក User ដែលកំពុងស្កែន

        // ១. ពិនិត្យមើលថា Token នេះមានក្នុង Cache និងមិនទាន់ផុតកំណត់មែនទេ?
        if (Cache::has('login_token_' . $token)) {
            
            // ២. រក្សាទុក User ID ភ្ជាប់ជាមួយ Token នេះក្នុង Cache រយៈពេល ១ នាទី
            Cache::put('authorized_user_' . $token, $user->id, now()->addMinute());
            
            // ៣. ផ្ញើសញ្ញា (Event) ទៅកាន់ Computer តាមរយៈ Pusher
            event(new QrLoginSuccessful($token, $user->id));

            return response()->json([
                'status' => 'success',
                'message' => 'ការបញ្ជាក់អត្តសញ្ញាណជោគជ័យ! Computer របស់អ្នកនឹង Login ក្នុងពេលបន្តិចទៀត។'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'QR Code មិនត្រឹមត្រូវ ឬបានផុតកំណត់ហើយ។'
        ], 400);
    }

    // ៣. មុខងារ Login លើ Computer ក្រោយទទួលសញ្ញាបាន
    public function finalizeLogin($token)
    {
        $userId = Cache::pull('authorized_user_' . $token);
        if ($userId) {
            Auth::loginUsingId($userId);
            return redirect()->intended('/dashboard');
        }
        return redirect()->route('login')->with('error', 'Login បរាជ័យ');
    }
}