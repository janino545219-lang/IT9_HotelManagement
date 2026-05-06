<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::withCount('reservations')->latest()->paginate(5);
        return view('admin.guests', compact('guests'));
    }

    public function create()
    {
        return view('admin.guests-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:20',
        ]);

        // Guests are tied to users — for admin-created guests,
        // create a placeholder user or handle as needed
        // For now we update an existing guest's info
        Guest::create([
            'guest_id'   => \Str::uuid(),
            'user_id'    => \Str::uuid(), // placeholder
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
        ]);

        return redirect()->route('admin.guests.index')
            ->with('success', 'Guest added successfully.');
    }

    public function show(string $id)
    {
        $guest = Guest::with('reservations.room')->findOrFail($id);
        return view('admin.guests-show', compact('guest'));
    }

    public function edit(string $id)
    {
        $guest = Guest::findOrFail($id);
        return view('admin.guests-form', compact('guest'));
    }

    public function update(Request $request, string $id)
    {
        $guest = Guest::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:20',
        ]);

        $guest->update($request->only(['first_name', 'last_name', 'email', 'phone']));

        return redirect()->route('admin.guests.index')
            ->with('success', 'Guest updated successfully.');
    }

    public function destroy(string $id)
    {
        Guest::findOrFail($id)->delete();
        return redirect()->route('admin.guests.index')
            ->with('success', 'Guest deleted successfully.');
    }
}