<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->paginate(5);
        return view('admin.employees', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'department'  => 'required|string|max:255',
            'salary'      => 'required|numeric|min:0',
            'hire_date'   => 'required|date',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:8',
        ]);

        // Create user account first
        $user = User::create([
            'user_id'       => Str::uuid(),
            'role'          => 'employee',
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
        ]);

        Employee::create([
            'employee_id' => Str::uuid(),
            'user_id'     => $user->user_id,
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'position'    => $request->position,
            'department'  => $request->department,
            'salary'      => $request->salary,
            'hire_date'   => $request->hire_date,
            'is_active'   => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee added successfully.');
    }

    public function edit(string $id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.employees-form', compact('employee'));
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'position'   => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'salary'     => 'required|numeric|min:0',
            'hire_date'  => 'required|date',
        ]);

        $employee->update($request->only([
            'first_name', 'last_name', 'position',
            'department', 'salary', 'hire_date', 'is_active'
        ]));

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(string $id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted.');
    }
}