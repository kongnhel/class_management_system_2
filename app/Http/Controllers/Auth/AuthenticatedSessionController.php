<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // បង្កើត Token សម្ងាត់សម្រាប់ QR Code
        $token = (string) Str::uuid();
        
        // រក្សាទុកក្នុង Cache ២ នាទី
        Cache::put('login_token_' . $token, true, now()->addMinutes(2));

        // បង្កើតរូបភាព QR Code (ជា SVG)
        $qrCode = QrCode::size(200)
            ->color(16, 185, 129) // ពណ៌ Emerald Green ដូច Logo បង
            ->margin(1)
            ->generate($token);

        return view('auth.login', [
            'qrCode' => $qrCode,
            'token' => $token
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }

    public function store(LoginRequest $request): RedirectResponse
{
    try {
        $request->authenticate();
    } catch (ValidationException $e) {
        return back()
            ->withErrors($e->errors())
            ->withInput($request->only('email'));
    }

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
