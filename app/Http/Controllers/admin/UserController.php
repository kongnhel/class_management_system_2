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
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\StudentProfile;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Http;    
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    // // Fetch paginated Students
    // $students = User::where('role', 'student')
    //                 ->with('profile', 'program')
    //                 ->when($search, function($query, $search) {
    //                     $query->where(function ($q) use ($search) {
    //                         $q->where('name', 'LIKE', "%{$search}%")
    //                           ->orWhere('email', 'LIKE', "%{$search}%")
    //                           ->orWhereHas('profile', function ($q2) use ($search) {
    //                               $q2->where('full_name_km', 'LIKE', "%{$search}%");
    //                           })
    //                           ->orWhereHas('program', function ($q3) use ($search) {
    //                               $q3->where('name_km', 'LIKE', "%{$search}%")
    //                                  ->orWhere('name_en', 'LIKE', "%{$search}%");
    //                           });
    //                     });
    //                 })
    //                 ->orderBy('name')
    //                 ->paginate(10, ['*'], 'studentsPage');
    // Fetch Students and group them by Generation and Program
$students = User::where('role', 'student')
    ->with(['profile', 'program'])
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
    ->orderBy('generation', 'desc') // បង្ហាញជំនាន់ចុងក្រោយមុនគេ
    ->orderBy('name', 'asc')        // រៀបតាមឈ្មោះក្នុងជំនាន់នីមួយៗ
    ->get(); // យើងប្រើ get() ដើម្បីអាចធ្វើការ Group ក្នុង Collection បាន
    // $categories = Category::paginate(10);

// ធ្វើការបែងចែកជា گروپ ធំ (Generation) និង گروپ តូច (Program)
$studentsGrouped = $students->groupBy([
    'generation', 
    function ($item) {
        return $item->program->name_km ?? 'មិនទាន់មានកម្មវិធីសិក្សា';
    }
]);

    return view('admin.users.index', compact('admins', 'professors', 'students','studentsGrouped'));
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
        $messages = [
            'profile_picture.max' => 'រូបភាពមិនអាចធំជាង ២MB ឡើយ!',
            'profile_picture.image' => 'ឯកសារត្រូវតែជាប្រភេទរូបភាព!',
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
            // if ($request->hasFile('profile_picture')) {
            //     $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            //     $profile->profile_picture_url = $path;
            // }
if ($request->hasFile('profile_picture')) {
    $image = $request->file('profile_picture');
    
    // ផ្ញើរូបភាពទៅ ImgBB
    $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
        'key' => env('IMGBB_API_KEY'),
        'image' => base64_encode(file_get_contents($image->getRealPath())),
    ]);

    if ($response->successful()) {
        // រក្សាទុក URL ពេញលេញពី ImgBB
        $profile->profile_picture_url = $response->json()['data']['url'];
    }
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
    // public function editUser(User $user)
    // {
    //     $user->load('profile', 'studentProfile', 'department.faculty', 'program'); 
    //     $faculties = Faculty::all();
    //     $departments = Department::all();
    //     $programs = Program::all();
    //     $generations = User::where('role', 'student')->whereNotNull('generation')->distinct()->pluck('generation');
    //     return view('admin.users.edit', compact('user', 'departments', 'programs', 'faculties', 'generations'));
    // }

    public function editUser(User $user)
    {
        $user->load('profile', 'studentProfile', 'department.faculty', 'program'); 
        $faculties = Faculty::all();
        $departments = Department::all();
        $programs = Program::all();
        $generations = User::where('role', 'student')->whereNotNull('generation')->distinct()->pluck('generation');
        
        return view('admin.users.edit', compact('user', 'departments', 'programs', 'faculties', 'generations'));
    }

    public function updateUser(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => ['required', 'string', Rule::in(['admin', 'professor', 'student'])],
            'full_name_km' => 'nullable|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            // 'profile_picture' => 'nullable|image|max:2048', 
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'generation' => 'nullable|string|max:255',
        ];
$messages = [
        'profile_picture.max' => 'រូបភាពមិនអាចធំជាង ២MB ឡើយ!',
        'profile_picture.image' => 'ឯកសារត្រូវតែជាប្រភេទរូបភាព!',
        'profile_picture.mimes' => 'រូបភាពត្រូវតែជាប្រភេទ: jpeg, png, jpg!',
    ];


        if ($request->role === 'student') {
            $rules['student_id_code'] = ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)];
            $rules['program_id'] = 'required|exists:programs,id';
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
            $rules['password'] = 'nullable|string|min:8|confirmed';
            if ($request->role === 'professor') {
                $rules['department_id'] = 'required|exists:departments,id';
            }
        }
        
        $request->validate($rules,$messages);

        // ១. Update User Core Data
        $user->name = $request->name;
        $user->role = $request->role;
        $user->student_id_code = ($request->role === 'student') ? $request->student_id_code : null;
        $user->department_id = ($request->role === 'professor') ? $request->department_id : null;
        $user->program_id = ($request->role === 'student') ? $request->program_id : null;
        $user->generation = ($request->role === 'student') ? $request->generation : null;
        
        if ($request->role !== 'student') {
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
        }
        $user->save();

        // ២. Profile Logic
        if ($request->role === 'student') {
            $profile = $user->studentProfile()->firstOrNew(['user_id' => $user->id]);
            if ($user->profile) $user->profile->delete();
        } else {
            $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);
            if ($user->studentProfile) $user->studentProfile->delete();
        }

        $profile->fill($request->only(['full_name_km', 'full_name_en', 'gender', 'date_of_birth', 'phone_number', 'address']));

        // ៣. Handle ImgBB Upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
                'image' => base64_encode(file_get_contents($file->getRealPath())),
            ]);

            if ($response->successful()) {
                // រក្សាទុក Full URL ពី ImgBB
                $profile->profile_picture_url = $response->json()['data']['url'];
            }
        }

        $profile->save();

        return redirect()->route('admin.manage-users')->with('success', 'ព័ត៌មានត្រូវបានធ្វើបច្ចុប្បន្នភាព។');
    }

    /**
     * Update the specified user in storage.
     */
// public function updateUser(Request $request, User $user)
// {
//     // --- ១. ការកំណត់លក្ខខណ្ឌ Validation ---
//     $rules = [
//         'name' => 'required|string|max:255',
//         'role' => ['required', 'string', Rule::in(['admin', 'professor', 'student'])],
//         'full_name_km' => 'nullable|string|max:255',
//         'full_name_en' => 'nullable|string|max:255',
//         'gender' => 'nullable|string|max:10',
//         'date_of_birth' => 'nullable|date',
//         'phone_number' => 'nullable|string|max:20',
//         'address' => 'nullable|string|max:255',
//         'profile_picture' => 'nullable|image|max:2048', // កម្រិត ២MB
//         'generation' => 'nullable|string|max:255',
//     ];

//     if ($request->role === 'student') {
//         $rules['student_id_code'] = ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)];
//         $rules['generation'] = ['required', 'string', 'max:255'];
//         $rules['program_id'] = 'required|exists:programs,id';
//     } else {
//         $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
//         $rules['password'] = 'nullable|string|min:8|confirmed';
//         if ($request->role === 'professor') {
//             $rules['department_id'] = 'required|exists:departments,id';
//         }
//     }
    
//     $request->validate($rules);

//     // --- ២. ធ្វើបច្ចុប្បន្នភាពទិន្នន័យគោលរបស់ User ---
//     $user->fill($request->only(['name', 'role']));
//     $user->student_id_code = ($request->role === 'student') ? $request->student_id_code : null;
//     $user->department_id = ($request->role === 'professor') ? $request->department_id : null;
//     $user->program_id = ($request->role === 'student') ? $request->program_id : null;
    
//     if ($request->role !== 'student') {
//         $user->email = $request->email;
//         if ($request->filled('password')) {
//             $user->password = Hash::make($request->password);
//         }
//     }
//     $user->save();

//     // --- ៣. ការគ្រប់គ្រង Profile តាមតួនាទី ---
//     $profileData = $request->only(['full_name_km', 'full_name_en', 'gender', 'date_of_birth', 'phone_number', 'address']);
    
//     if ($request->role === 'student') {
//         $profile = $user->studentProfile()->firstOrNew(['user_id' => $user->id]);
//         $profile->generation = $request->generation; 

//         if ($user->profile) {
//             // លុប Profile ចាស់ចោលប្រសិនបើប្តូរតួនាទីពីបុគ្គលិកមកជានិស្សិត
//             $user->profile->delete();
//         }
//     } else {
//         $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);
//         if ($user->studentProfile) {
//             // លុប Profile ចាស់ចោលប្រសិនបើប្តូរតួនាទីពីនិស្សិតមកជាបុគ្គលិក
//             $user->studentProfile->delete();
//         }
//     }
//     $profile->fill($profileData);

    
//     // --- ៤. ការ Upload រូបភាពទៅកាន់ ImgBB ---
//     if ($request->hasFile('profile_picture')) {
//         $file = $request->file('profile_picture');

//         // ប្រើប្រាស់ Http Facade ដើម្បីបញ្ជូនរូបភាពទៅ ImgBB API
//         $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
//             'key' => env('IMGBB_API_KEY'),
//             'image' => base64_encode(file_get_contents($file->getRealPath())),
//         ]);

//         if ($response->successful()) {
//             // រក្សាទុក URL ពេញលេញដែលទទួលបានពី ImgBB
//             $profile->profile_picture_url = $response->json()['data']['url'];
//         }
//     }

//     $profile->save();

//     return redirect()->route('admin.manage-users')->with('success', 'ព័ត៌មានអ្នកប្រើប្រាស់ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។');
// }

    /**
     * Remove the specified user from storage.
     */
//     public function deleteUser(User $user)
// {
//     $hasOfferings = \App\Models\CourseOffering::where('lecturer_user_id', $user->id)->exists();
//     $hasEnrollments = \App\Models\StudentCourseEnrollment::where('student_user_id', $user->id)->exists();
//     if ($hasOfferings || $hasEnrollments) {
//         return redirect()->route('admin.manage-users')
//             ->with('error', 'មិនអាចលុបអ្នកប្រើប្រាស់នេះបានទេ ពីព្រោះមានទំនាក់ទំនងជាមួយកំណត់ត្រាផ្សេងទៀត។( កំពុងមានថ្នាក់រៀនសម្រាប់បង្រៀន​ )');
//     }
//     // Load profile ទាំងពីរប្រភេទដើម្បីធានាថាមានទិន្នន័យសម្រាប់លុប
//     $user->load('profile', 'studentProfile');

//     // ១. លុប Staff Profile ប្រសិនបើមាន (Admin ឬ Professor)
//     if ($user->profile) {
//         // ចំណាំ៖ ImgBB មិនអនុញ្ញាតឱ្យលុបរូបភាពតាម API កម្រិតឥតគិតថ្លៃទេ 
//         // ដូច្នេះយើងគ្រាន់តែលុបទិន្នន័យ Profile ចេញពី Database ជាការស្រេច
//         $user->profile->delete();
//     }

//     // ២. លុប Student Profile ប្រសិនបើមាន
//     if ($user->studentProfile) {
//         $user->studentProfile->delete();
//     }

//     // ៣. ជាចុងក្រោយ លុបគណនីអ្នកប្រើប្រាស់ (User Account)
//     $user->delete();

//     return redirect()->route('admin.manage-users')
//         ->with('success', 'អ្នកប្រើប្រាស់ត្រូវបានលុបចេញពីប្រព័ន្ធដោយជោគជ័យ។');
// }

    
// }

public function deleteUser(User $user)
{
    try {
        \DB::transaction(function () use ($user) {
            // 1. Remove Course Offerings where this user is the lecturer
            // Note: You might want to reassign these instead of deleting them.
            \App\Models\CourseOffering::where('lecturer_user_id', $user->id)->delete();

            // 2. Remove Student Enrollments
            \App\Models\StudentCourseEnrollment::where('student_user_id', $user->id)->delete();

            // 3. Load and delete profiles
            $user->load(['profile', 'studentProfile']);

            if ($user->profile) {
                $user->profile->delete();
            }

            if ($user->studentProfile) {
                $user->studentProfile->delete();
            }

            // 4. Finally, delete the User account
            $user->delete();
        });

        return redirect()->route('admin.manage-users')
            ->with('success', 'អ្នកប្រើប្រាស់ និងទិន្នន័យពាក់ព័ន្ធត្រូវបានលុបដោយជោគជ័យ។');

    } catch (\Exception $e) {
        return redirect()->route('admin.manage-users')
            ->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
    }
}
}