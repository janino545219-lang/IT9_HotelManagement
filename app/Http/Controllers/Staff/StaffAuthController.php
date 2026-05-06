<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        // Clear staff session
        session()->forget([
            'staff_logged_in',
            'staff_user_id',
            'staff_id',
            'staff_name',
            'staff_role',
            'staff_email',
        ]);

        // Also logout Laravel Auth
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}