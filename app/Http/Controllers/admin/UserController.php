<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentProfile;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{

   public function manageUsers(Request $request)
{
    $search = $request->input('search');

    // Fetch paginated Admins
    $admins = User::where('role', 'admin')
                  ->with('profile') // Eager load profile
                  ->when($search, function($query, $search) {
                      $query->where(function ($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhereHas('profile', function ($q2) use ($search) {
                                $q2->where('full_name_km', 'LIKE', "%{$search}%");
                            });
                      });
                  })
                  ->orderBy('name')
                  ->paginate(10, ['*'], 'adminsPage');

    $professors = User::where('role', 'professor')
                      ->with('profile', 'department')
                      ->when($search, function($query, $search) {
                          $query->where(function ($q) use ($search) {
                              $q->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%")
                                ->orWhereHas('profile', function ($q2) use ($search) {
                                    $q2->where('full_name_km', 'LIKE', "%{$search}%");
                                })
                                ->orWhereHas('department', function ($q3) use ($search) {
                                    $q3->where('name_km', 'LIKE', "%{$search}%")
                                       ->orWhere('name_en', 'LIKE', "%{$search}%");
                                });
                          });
                      })
                      ->orderBy('name')
                      ->paginate(10, ['*'], 'professorsPage');

    // Fetch paginated Students
    $students = User::where('role', 'student')
                    ->with('profile', 'program')
                    ->when($search, function($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                              ->orWhere('email', 'LIKE', "%{$search}%")
                              ->orWhereHas('profile', function ($q2) use ($search) {
                                  $q2->where('full_name_km', 'LIKE', "%{$search}%");
                              })
                              ->orWhereHas('program', function ($q3) use ($search) {
                                  $q3->where('name_km', 'LIKE', "%{$search}%")
                                     ->orWhere('name_en', 'LIKE', "%{$search}%");
                              });
                        });
                    })
                    ->orderBy('name')
                    ->paginate(10, ['*'], 'studentsPage');

    return view('admin.users.index', compact('admins', 'professors', 'students'));
}


public function searchUsers(Request $request)
{
    $search = $request->input('q');

    $users = User::with('profile')
        ->where('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhereHas('profile', function ($q) use ($search) {
            $q->where('full_name_km', 'LIKE', "%{$search}%");
        })
        ->limit(5) // show only top 5
        ->get();

    return response()->json($users);
}

    public function getDepartmentsByFaculty(Faculty $faculty)
    {
        // Get only the departments that belong to the specified faculty
        $departments = $faculty->departments()->select('id', 'name_km', 'name_en')->get();
        return response()->json($departments);
    }



    public function showUser(User $user)
{
    // Eager load all possible profiles and role-specific data
    $user->load(['profile', 'studentProfile']);

    if ($user->role === 'professor') {
        // Load the courses this professor teaches
        $user->load(['taughtCourseOfferings' => function ($query) {
            $query->with(['course', 'program'])->orderBy('academic_year', 'desc');
        }]);
    } elseif ($user->role === 'student') {
        // Load the courses this student is enrolled in
        $user->load(['studentCourseEnrollments' => function ($query) {
            $query->with(['courseOffering.course', 'courseOffering.program'])->orderBy('created_at', 'desc');
        }]);
    }

    return view('admin.users.show', compact('user'));
}
    /**
     * Show the form for creating a new user.
     */
      public function createUser()
    {
        $faculties = Faculty::all();
        $departments = Department::all();
        $programs = Program::all();
        $generations = User::where('role', 'student')->whereNotNull('generation')->distinct()->pluck('generation');
        
        return view('admin.users.create', compact('departments', 'programs', 'faculties', 'generations'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function storeUser(Request $request)
    {
        // --- Base validation rules ---
        $rules = [
            'name' => 'required|string|max:255',
            'role' => ['required', 'string', Rule::in(['admin', 'professor', 'student'])],
            'full_name_km' => 'nullable|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048', // Max 2MB
        ];

        // --- Role-specific rules ---
        if ($request->role === 'student') {
            $rules['student_id_code'] = ['required', 'string', 'max:255', Rule::unique('users', 'student_id_code')];
            $rules['program_id'] = 'required|exists:programs,id';
            $rules['generation'] = 'nullable|string|max:255';
        } elseif ($request->role === 'professor') {
            $rules['email'] = 'required|string|email|max:255|unique:users';
            $rules['password'] = [
    'required',
    'confirmed',
    Password::min(8)
        ->letters()        
        ->mixedCase()     
        ->numbers()        
        ->symbols()        // ត្រូវមានសញ្ញាពិសេស
        ->uncompromised()  // ត្រួតពិនិត្យថាមិនជារូបពាក្យសម្ងាត់ដែលរំលោភសុវត្ថិភាព (Pwned Password)
];
            $rules['department_id'] = 'required|exists:departments,id';
        } else { // Admin
            $rules['email'] = 'required|string|email|max:255|unique:users';
            $rules['password'] = [
    'required',
    'confirmed',
    Password::min(8)
        ->letters()      
        ->mixedCase()      // ត្រូវមានទាំងអក្សរធំ និងតូច
        ->numbers()        // ត្រូវមានលេខ
        ->symbols()        // ត្រូវមានសញ្ញាពិសេស
        ->uncompromised()  // ត្រួតពិនិត្យថាមិនជារូបពាក្យសម្ងាត់ដែលរំលោភសុវត្ថិភាព (Pwned Password)
];
        }

        $request->validate($rules);

        // --- Create the core User model ---
        $user = User::create([
            'name' => $request->name,
            'role' => $request->role,
            'student_id_code' => ($request->role === 'student') ? $request->student_id_code : null,
            'department_id' => ($request->role === 'professor') ? $request->department_id : null,
            'program_id' => ($request->role === 'student') ? $request->program_id : null,
            'email' => ($request->role !== 'student') ? $request->email : null,
            'password' => ($request->role !== 'student') ? Hash::make($request->password) : null,
            'generation' => ($request->role === 'student') ? $request->generation : null, // រក្សាទុក generation ទៅក្នុងតារាង users
        ]);

        // --- Conditional Profile Creation Logic ---
        $profileData = $request->only(['full_name_km', 'full_name_en', 'gender', 'date_of_birth', 'phone_number', 'address']);
        
        // Only create a profile if some data was actually entered
        if (count(array_filter($profileData)) > 0 || $request->hasFile('profile_picture')) {
            if ($request->role === 'student') {
                $profile = new StudentProfile($profileData);
                $profile->generation = $request->generation; 
            } else { // For 'admin' or 'professor'
                $profile = new UserProfile($profileData);
            }

            // Handle the profile picture upload
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $profile->profile_picture_url = $path;
            }

            // Save the profile to the correct relationship
            if ($request->role === 'student') {
                 $user->studentProfile()->save($profile);
            } else {
                 $user->profile()->save($profile);
            }
        }

        return redirect()->route('admin.manage-users')->with('success', 'អ្នកបានបង្កើតអ្នកប្រើប្រាស់ថ្មីដោយជោគជ័យ។');
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function editUser(User $user)
    {
        $user->load('profile', 'studentProfile', 'department.faculty', 'program'); 
        $faculties = Faculty::all();
        $departments = Department::all();
        $programs = Program::all();
        $generations = User::where('role', 'student')->whereNotNull('generation')->distinct()->pluck('generation');
        return view('admin.users.edit', compact('user', 'departments', 'programs', 'faculties', 'generations'));
    }

    /**
     * Update the specified user in storage.
     */
    public function updateUser(Request $request, User $user)
    {
        // --- Validation rules ---
        $rules = [
            'name' => 'required|string|max:255',
            'role' => ['required', 'string', Rule::in(['admin', 'professor', 'student'])],
            // Profile fields
            'full_name_km' => 'nullable|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
            'generation' => 'nullable|string|max:255',
        ];
        if ($request->role === 'student') {

            $rules['student_id_code'] = ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)];
            $rules['generation'] = ['required', 'string', 'max:255'];
            $rules['program_id'] = 'required|exists:programs,id';
        } else { // Admin & Professor
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
            $rules['password'] = 'nullable|string|min:8|confirmed';
            if ($request->role === 'professor') {
                $rules['department_id'] = 'required|exists:departments,id';
            }
        }
        $request->validate($rules);

        // --- Update User core data ---
        $user->fill($request->only(['name', 'role']));
        $user->student_id_code = ($request->role === 'student') ? $request->student_id_code : null;
        $user->department_id = ($request->role === 'professor') ? $request->department_id : null;
        $user->program_id = ($request->role === 'student') ? $request->program_id : null;
        
        if ($request->role !== 'student') {
            $user->email = $request->email;
            if ($request->filled('password')) $user->password = Hash::make($request->password);
        }
        $user->save();

        // --- Conditional Profile Handling ---
        $profileData = $request->only(['full_name_km', 'full_name_en', 'gender', 'date_of_birth', 'phone_number', 'address']);
        
        if ($request->role === 'student') {
            $profile = $user->studentProfile()->firstOrNew(['user_id' => $user->id]);
              $profile->generation = $request->generation; 

            if ($user->profile) { // Clean up staff profile if role was changed to student
                if($user->profile->profile_picture_url) Storage::disk('public')->delete($user->profile->profile_picture_url);
                $user->profile->delete();
            }
        } else { // Admin or Professor
            $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);
            if ($user->studentProfile) { // Clean up student profile if role was changed
                 if($user->studentProfile->profile_picture_url) Storage::disk('public')->delete($user->studentProfile->profile_picture_url);
                $user->studentProfile->delete();
            }
        }
        $profile->fill($profileData);

        // Handle Profile Picture
        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture_url && Storage::disk('public')->exists($profile->profile_picture_url)) {
                Storage::disk('public')->delete($profile->profile_picture_url);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profile->profile_picture_url = $path;
        } elseif ($request->has('remove_profile_picture')) {
            if ($profile->profile_picture_url && Storage::disk('public')->exists($profile->profile_picture_url)) {
                Storage::disk('public')->delete($profile->profile_picture_url);
            }
            $profile->profile_picture_url = null;
        }
        $profile->save();

        return redirect()->route('admin.manage-users')->with('success', 'អ្នកប្រើប្រាស់ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។');
    }

    /**
     * Remove the specified user from storage.
     */
    public function deleteUser(User $user)
    {
        $user->load('profile', 'studentProfile');
        // Delete staff profile and picture if it exists
        if ($user->profile) {
            if ($user->profile->profile_picture_url) {
                Storage::disk('public')->delete($user->profile->profile_picture_url);
            }
            $user->profile->delete();
        }
        // Delete student profile and picture if it exists
        if ($user->studentProfile) {
            if ($user->studentProfile->profile_picture_url) {
                Storage::disk('public')->delete($user->studentProfile->profile_picture_url);
            }
            $user->studentProfile->delete();
        }
        // Finally, delete the user
        $user->delete();
        return redirect()->route('admin.manage-users')->with('success', 'អ្នកប្រើប្រាស់ត្រូវបានលុបដោយជោគជ័យ។');
    }

    
}
