<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Guest;

class GuestAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.guest-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/guest/dashboard');
        }

        return back()->withInput()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.guest-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'nullable|string|max:20',
            'password'   => 'required|confirmed|min:8',
        ]);

        try {
            // 1. Create user account
            $user = User::create([
                'email'         => $request->email,
                'password_hash' => Hash::make($request->password),
                'role'          => 'guest',
            ]);

            // 2. Create guest profile linked to user
            Guest::create([
                'user_id'    => $user->user_id,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
            ]);

            return redirect('/login')->with('status', 'Account created! Please sign in.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function dashboard()
    {
        return view('guest.dashboard');
    }

    public function profile()
    {
        $user = Auth::user();
        $guest = Guest::where('user_id', $user->user_id)->first();
        return view('guest.profile', compact('user', 'guest'));
    }

    public function invoices()
    {
        $user = Auth::user();
        $guest = Guest::where('user_id', $user->user_id)->first();
        $invoices = $guest ? $guest->invoices()->latest()->get() : collect();
        return view('guest.invoices', compact('invoices', 'guest'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}