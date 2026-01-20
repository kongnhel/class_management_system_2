<?php

namespace App\Http\Controllers\Student;
use App\Models\Room;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentRoomController extends Controller
{
    
    public function rooms()
{
    $rooms = Room::all();
    return view('student.rooms.index', compact('rooms'));
}
}
