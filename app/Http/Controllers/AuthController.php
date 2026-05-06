<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Check BOTH Auth and session — if either is missing, show login
        if (Auth::check() && session('staff_logged_in') && Auth::user()->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::check() && Auth::user()->role === 'guest') {
            return redirect()->route('guest.dashboard');
        }

        // If Auth is set but session is missing (after restart), log out cleanly
        if (Auth::check() && Auth::user()->role === 'staff' && !session('staff_logged_in')) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return view('auth.login');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user ||
            !Hash::isHashed($user->password_hash) ||
            !Hash::check($request->password, $user->password_hash)
        ) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput($request->only('email'));
        }

        Auth::login($user, $request->boolean('remember'));

        // Staff
        if ($user->role === 'staff') {
            $staff = DB::table('staff')->where('user_id', $user->user_id)->first();
            session([
                'staff_logged_in' => true,
                'staff_user_id'   => $user->user_id,
                'staff_id'        => $staff->staff_id ?? null,
                'staff_name'      => ($staff->first_name ?? '') . ' ' . ($staff->last_name ?? ''),
                'staff_role'      => $staff->role ?? 'Staff',
                'staff_email'     => $user->email,
            ]);
            return redirect()->route('staff.dashboard');
        }

        // Admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Guest
        return redirect()->route('guest.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
