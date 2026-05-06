<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(5);
        return view('admin.rooms', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number'     => 'required|unique:rooms,room_number',
            'room_type'       => 'required',
            'floor_number'    => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'capacity'        => 'required|integer|min:1',
            'status'          => 'required',
            'image'           => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('storage/rooms'), $filename);
            $imagePath = 'storage/rooms/' . $filename;
        }

        Room::create([
            'room_id'         => Str::uuid(),
            'room_number'     => $request->room_number,
            'room_type'       => $request->room_type,
            'floor_number'    => $request->floor_number,
            'price_per_night' => $request->price_per_night,
            'capacity'        => $request->capacity,
            'status'          => $request->status,
            'amenities'       => $request->amenities,
            'image'           => $imagePath,
        ]);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room added successfully.');
    }

    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        return view('admin.rooms-form', compact('room'));
    }

    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_number'     => 'required|unique:rooms,room_number,' . $id . ',room_id',
            'room_type'       => 'required',
            'floor_number'    => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'capacity'        => 'required|integer|min:1',
            'status'          => 'required',
            'image'           => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'room_number', 'room_type', 'floor_number',
            'price_per_night', 'capacity', 'status', 'amenities'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($room->image && file_exists(public_path($room->image))) {
                unlink(public_path($room->image));
            }
            
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('storage/rooms'), $filename);
            $data['image'] = 'storage/rooms/' . $filename;
        }

        $room->update($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(string $id)
    {
        Room::findOrFail($id)->delete();
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}