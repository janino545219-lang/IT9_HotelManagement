<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffRoomController extends Controller
{
    public function index()
    {
        $rooms = DB::table('rooms')->orderBy('room_number')->get();
        return view('staff.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('staff.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number'     => 'required|unique:rooms,room_number',
            'room_type'       => 'required',
            'floor_number'    => 'required|integer',
            'price_per_night' => 'required|numeric',
            'capacity'        => 'required|integer',
            'status'          => 'required',
            'image'           => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('storage/rooms'), $filename);
            $imagePath = 'storage/rooms/' . $filename;
        }

        DB::table('rooms')->insert([
            'room_id'         => Str::uuid(),
            'room_number'     => $request->room_number,
            'room_type'       => $request->room_type,
            'floor_number'    => $request->floor_number,
            'price_per_night' => $request->price_per_night,
            'capacity'        => $request->capacity,
            'status'          => $request->status,
            'amenities'       => $request->amenities,
            'image'           => $imagePath,
            'is_active'       => 1,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect()->route('staff.rooms.index')->with('success', 'Room created successfully!');
    }
}