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

// public function handleScan(Request $request)
// {
//     try {
//         $token = $request->token;
//         $user = Auth::user();

//         if (Cache::has('login_token_' . $token)) {
//             Cache::put('authorized_user_' . $token, $user->id, now()->addMinute());
            
//             broadcast(new QrLoginSuccessful($token, $user->id));

//             return response()->json(['status' => 'success']);
//         }
//     } catch (\Exception $e) {
//         Log::error("QR Scan Error: " . $e->getMessage());
//         return response()->json(['status' => 'error', 'message' => 'Server Error'], 500);
//     }
// }
public function handleScan(Request $request)
{
    try {
        $user = Auth::user();

        // ✅ Fix #2 — ពិនិត្យ auth ជាមុន
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $token = $request->token;

        if (Cache::has('login_token_' . $token)) {
            Cache::put('authorized_user_' . $token, $user->id, now()->addMinute());
            broadcast(new QrLoginSuccessful($token, $user->id));
            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'QR Code នេះផុតកំណត់ ឬមិនត្រឹមត្រូវឡើយ!'
        ], 400);

    } catch (\Exception $e) {
        Log::error("QR Scan Error: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'មានបញ្ហាម៉ាស៊ីនបម្រើ (Server Error)'
        ], 500);
    }
}



    public function finalizeLogin($token)
    {
        $userId = Cache::pull('authorized_user_' . $token);

        if ($userId) {
            Auth::loginUsingId($userId);
            
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return redirect()->route('login')->with('error', 'ការចូលប្រើប្រាស់ផុតកំណត់។');
    }

public function refreshQr()
{
    $token = (string) Str::uuid();
    Cache::put('login_token_' . $token, true, now()->addMinutes(2));

    $qrCode = QrCode::size(200)
        ->color(16, 185, 129)
        ->margin(1)
        ->generate($token);

    // ✅ Fix #1 — cast ជា string ត្រឹមត្រូវ
    return response()->json([
        'qrCode' => (string) $qrCode,
        'token' => $token
    ]);
}
    
}