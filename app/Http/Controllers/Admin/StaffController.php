<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::latest()->paginate(5);
        return view('admin.staff', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'shift'      => 'required|in:morning,afternoon,night',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8',
        ]);

        $user = User::create([
            'user_id'       => Str::uuid(),
            'role'          => 'staff',
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
        ]);

        Staff::create([
            'staff_id'     => Str::uuid(),
            'user_id'      => $user->user_id,
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'role'         => $request->role,
            'shift'        => $request->shift,
            'is_available' => $request->is_available ?? 1,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff added successfully.');
    }

    public function edit(string $id)
    {
        $staffMember = Staff::findOrFail($id);
        return view('admin.staff-form', compact('staffMember'));
    }

    public function update(Request $request, string $id)
    {
        $staffMember = Staff::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'shift'      => 'required|in:morning,afternoon,night',
        ]);

        $staffMember->update($request->only([
            'first_name', 'last_name', 'role', 'shift', 'is_available'
        ]));

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff updated successfully.');
    }

    public function destroy(string $id)
    {
        Staff::findOrFail($id)->delete();
        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff deleted.');
    }
}