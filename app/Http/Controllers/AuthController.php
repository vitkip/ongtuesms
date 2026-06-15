<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SystemLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້.',
            'password.required' => 'ກະລຸນາປ້ອນລະຫັດຜ່ານ.',
        ]);

        // Find user first to check if active
        $user = User::where('username', $credentials['username'])->first();

        if ($user && !$user->is_active) {
            return back()->withErrors([
                'username' => 'ບັນຊີຂອງທ່ານຖືກລະງັບການນຳໃຊ້.',
            ])->onlyInput('username');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $authUser = Auth::user();
            $authUser->update([
                'last_login' => now()
            ]);

            // Log event
            SystemLog::create([
                'level' => 'info',
                'message' => "ຜູ້ໃຊ້ {$authUser->full_name} ເຂົ້າສູ່ລະບົບ",
                'user_id' => $authUser->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'context' => ['action' => 'login', 'username' => $authUser->username]
            ]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            SystemLog::create([
                'level' => 'info',
                'message' => "ຜູ້ໃຊ້ {$user->full_name} ອອກຈາກລະບົບ",
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'context' => ['action' => 'logout', 'username' => $user->username]
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
