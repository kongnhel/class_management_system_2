<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoomController extends Controller
{
      public function index()
    {
        $rooms = Room::all(); // ទាញយកបន្ទប់ទាំងអស់ពី database
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * បង្ហាញទម្រង់សម្រាប់បង្កើតបន្ទប់ថ្មី។
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * រក្សាទុកបន្ទប់ថ្មីទៅក្នុង database។
     */
    public function store(Request $request)
    {
        // Validation (ផ្ទៀងផ្ទាត់ទិន្នន័យ)
        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number|max:255',
            'capacity' => 'required|integer|min:1',
            'wifi_name' => 'nullable|string|max:255',
            'wifi_password' => 'nullable|string|max:255',
            'location_of_room' => 'nullable|string|max:255',
            'type_of_room' => 'nullable|string|max:255',
        ]);

        // បង្កើតបន្ទប់ថ្មី
        Room::create($request->all());

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'បន្ទប់ត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    /**
     * បង្ហាញព័ត៌មានលម្អិតនៃបន្ទប់ជាក់លាក់មួយ។
     */
    public function show(Room $room)
    {
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * បង្ហាញទម្រង់កែប្រែបន្ទប់
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * កែប្រែបន្ទប់ក្នុង database
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number'      => 'required|string|max:255|unique:rooms,room_number,' . $room->id,
            'capacity'         => 'required|integer|min:1',
            'wifi_name'        => 'nullable|string|max:255',
            'wifi_password'    => 'nullable|string|max:255',
            'location_of_room' => 'nullable|string|max:255',
            'type_of_room'     => 'nullable|string|max:255',
        ]);

        $room->update($request->all());

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'បន្ទប់ត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    /**
     * លុបបន្ទប់ចេញពី database
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'បន្ទប់ត្រូវបានលុបដោយជោគជ័យ!');
    }



}
