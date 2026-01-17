<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

class RoomController extends Controller
{
    /**
     * បង្កើតរោងចក្រ Firebase (Private Helper)
     */
    private function getFirebaseDatabase()
    {
        $credentialPath = storage_path('app/firebase/classmanagementsystem.json');

        if (!is_file($credentialPath)) {
            throw new \Exception("រកមិនឃើញ File JSON របស់ Firebase ទេ។");
        }

        return (new Factory)
            ->withServiceAccount($credentialPath)
            ->withDatabaseUri('https://classmanagementsystem-cd57f-default-rtdb.firebaseio.com/')
            ->createDatabase();
    }

    /**
     * ផ្ញើសញ្ញាទៅ Firebase ជាមួយសារជាក់លាក់
     * ប្តូរទៅកាន់ Reference 'rooms_sync' ដើម្បីឱ្យត្រូវជាមួយ JS ក្នុង Navigation
     */
    private function syncWithFirebase($message = 'ទិន្នន័យបន្ទប់ត្រូវបានធ្វើបច្ចុប្បន្នភាព')
    {
        try {
            $this->getFirebaseDatabase()
                ->getReference('rooms_sync') // ប្រើ rooms_sync សម្រាប់ទំព័របន្ទប់
                ->set([
                    'updated_at' => now()->timestamp,
                    'message' => $message 
                ]);
        } catch (\Exception $e) {
            Log::error('Firebase Sync Error: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $rooms = Room::paginate(10); 
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }
    public function show(Room $room)
{
    return view('admin.rooms.show', compact('room'));
}

    public function store(Request $request)
    {
        $request->validate([
            'room_number'      => 'required|string|unique:rooms,room_number|max:255',
            'capacity'         => 'required|integer|min:1',
            'wifi_qr_code'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'location_of_room' => 'nullable|string|max:255',
            'type_of_room'     => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('wifi_qr_code')) {
            $imagePath = $request->file('wifi_qr_code')->store('rooms/qrcodes', 'public');
            $data['wifi_qr_code'] = $imagePath;
        }

        $room = Room::create($data);

        // --- បញ្ជូនទិន្នន័យ និងផ្ញើសារទៅ Firebase ---
        try {
            $this->getFirebaseDatabase()->getReference('rooms/' . $room->id)->set([
                'room_number' => $room->room_number,
                'capacity'    => $room->capacity,
                'updated_at'  => now()->toDateTimeString()
            ]);
            
            // ផ្ញើសារប្រាប់ថាបានបង្កើតបន្ទប់ថ្មី
            $this->syncWithFirebase("បន្ទប់លេខ " . $room->room_number . " ត្រូវបានបង្កើតថ្មី!"); 
        } catch (\Exception $e) {
            Log::error('Firebase Store Error: ' . $e->getMessage());
        }

        return redirect()->route('admin.rooms.index')->with('success', 'បន្ទប់ត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number'      => 'required|string|max:255|unique:rooms,room_number,' . $room->id,
            'capacity'         => 'required|integer|min:1',
            'wifi_qr_code'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'location_of_room' => 'nullable|string|max:255',
            'type_of_room'     => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('wifi_qr_code')) {
            if ($room->wifi_qr_code) {
                Storage::disk('public')->delete($room->wifi_qr_code);
            }
            $imagePath = $request->file('wifi_qr_code')->store('rooms/qrcodes', 'public');
            $data['wifi_qr_code'] = $imagePath;
        }

        $room->update($data);

        // --- Update ទៅ Firebase ---
        try {
            $this->getFirebaseDatabase()->getReference('rooms/' . $room->id)->update([
                'room_number' => $room->room_number,
                'capacity'    => $room->capacity,
                'updated_at'  => now()->toDateTimeString()
            ]);
            
            // ផ្ញើសារប្រាប់ថាបានកែប្រែ
            $this->syncWithFirebase("ទិន្នន័យបន្ទប់លេខ " . $room->room_number . " ត្រូវបានកែប្រែ!");
        } catch (\Exception $e) {
            Log::error('Firebase Update Error: ' . $e->getMessage());
        }

        return redirect()->route('admin.rooms.index')->with('success', 'បន្ទប់ត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function destroy(Room $room)
    {
        $roomNumber = $room->room_number;
        $roomId = $room->id;

        if ($room->wifi_qr_code) {
            Storage::disk('public')->delete($room->wifi_qr_code);
        }

        $room->delete();

        // --- លុបចេញពី Firebase ---
        try {
            $this->getFirebaseDatabase()->getReference('rooms/' . $roomId)->remove();
            
            // ផ្ញើសារប្រាប់ថាបានលុប
            $this->syncWithFirebase("បន្ទប់លេខ " . $roomNumber . " ត្រូវបានលុបចេញពីប្រព័ន្ធ!");
        } catch (\Exception $e) {
            Log::error('Firebase Delete Error: ' . $e->getMessage());
        }

        return redirect()->route('admin.rooms.index')->with('success', 'បន្ទប់ត្រូវបានលុបដោយជោគជ័យ!');
    }
}