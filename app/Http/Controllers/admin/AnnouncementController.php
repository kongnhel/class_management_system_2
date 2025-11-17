<?php

namespace App\Http\Controllers\admin;
use App\Models\Announcement;
use App\Models\CourseOffering;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    
    public function index()
    {
        $announcements = Announcement::with('poster', 'courseOffering.course', 'courseOffering.program')->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     * កែសម្រួលដើម្បីរួមបញ្ចូល roles ទាំងអស់
     */
    public function create()
    {
        $courseOfferings = CourseOffering::with('course', 'program')->get();
        $role = ['all', 'student', 'professor', 'admin'];

        return view('admin.announcements.create', compact('courseOfferings', 'role'));
    }
    /**
     * Store a new announcement in the database.
     * កែសម្រួលការ Redirect បន្ទាប់ពីបានជោគជ័យ
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_km' => 'required|string',
            'content_en' => 'required|string',
            'target_role' => ['nullable', 'string', Rule::in(['all', 'student', 'professor', 'admin'])],
            'course_offering_id' => 'nullable|exists:course_offerings,id',
        ], [
            'title_km.required' => 'ចំណងជើងជាភាសាខ្មែរត្រូវតែបញ្ចូល។',
            'title_en.required' => 'ចំណងជើងជាភាសាអង់គ្លេសត្រូវតែបញ្ចូល។',
            'content_km.required' => 'ខ្លឹមសារជាភាសាខ្មែរត្រូវតែបញ្ចូល។',
            'content_en.required' => 'ខ្លឹមសារជាភាសាអង់គ្លេសត្រូវតែបញ្ចូល។',
            'course_offering_id.exists' => 'ការផ្តល់ជូនវគ្គសិក្សាមិនត្រឹមត្រូវទេ។',
        ]);
// code 
        try {
            Announcement::create([
                'poster_user_id' => Auth::id(),
                'title_km' => $request->input('title_km'),
                'title_en' => $request->input('title_en'),
                'content_km' => $request->input('content_km'),
                'content_en' => $request->input('content_en'),
                'target_role' => $request->input('target_role'),
                'course_offering_id' => $request->input('course_offering_id'),
            ]);

            Session::flash('success', 'សេចក្តីប្រកាសត្រូវបានបង្កើតដោយជោគជ័យ!');
        } catch (\Exception $e) {
            Session::flash('error', 'មានបញ្ហាក្នុងការបង្កើតសេចក្តីប្រកាស: ' . $e->getMessage());
        }

        return redirect()->route('admin.announcements.index');
    }
    
    /**
     * Show the form for editing the specified announcement.
     * កែសម្រួលដើម្បីរួមបញ្ចូល roles ទាំងអស់
     */
    public function edit(Announcement $announcement)
    {
        $courseOfferings = CourseOffering::with('course', 'program')->get();
        $role = ['all', 'student', 'professor','admin'];

        return view('admin.announcements.edit', compact('announcement', 'courseOfferings', 'role'));
    }
    
    /**
     * Update the specified announcement in storage.
     * កែសម្រួលការ Redirect បន្ទាប់ពីបានជោគជ័យ
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title_km' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_km' => 'required|string',
            'content_en' => 'required|string',
            'target_role' => ['nullable', 'string', Rule::in(['all', 'student', 'professor', 'admin'])],
            'course_offering_id' => 'nullable|exists:course_offerings,id',
        ]);

        try {
            $announcement->update($request->all());
            Session::flash('success', 'សេចក្តីប្រកាសត្រូវបានកែប្រែដោយជោគជ័យ!');
        } catch (\Exception $e) {
            Session::flash('error', 'មានបញ្ហាក្នុងការកែប្រែសេចក្តីប្រកាស: ' . $e->getMessage());
        }

        return redirect()->route('admin.announcements.index');
    }
    
    /**
     * Remove the specified announcement from storage.
     * កែសម្រួលដើម្បីដោះស្រាយបញ្ហា foreign key constraint
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();
            Session::flash('success', 'សេចក្តីប្រកាសត្រូវបានលុបដោយជោគជ័យ!');
        } catch (\Exception $e) {
            Session::flash('error', 'មានបញ្ហាក្នុងការលុបសេចក្តីប្រកាស: ' . $e->getMessage());
        }

        return redirect()->route('admin.announcements.index');
    }
}
