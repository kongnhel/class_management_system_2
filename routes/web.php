<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\FacultyController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\ProgramController;
use App\Http\Controllers\admin\CourseController;
use App\Http\Controllers\admin\CourseOfferingController;
use App\Http\Controllers\admin\AnnouncementController;
use App\Http\Controllers\admin\RoomController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\ProfessorProfileController;
use App\Http\Controllers\professor\ProfessorController;
use App\Http\Controllers\professor\QuizController;
use App\Http\Controllers\professor\StudentQuizController;
use App\Http\Controllers\ProfileController;
use App\Models\StudentCourseEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\StudentNotificationController;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;
use App\Http\Controllers\TelegramController;

Route::get('/test-telegram', function() {
    Artisan::call('schedule:run');
    return "Telegram testing triggered!";
});

Route::get('/test-telegram', function () {
    $token = env('TELEGRAM_BOT_TOKEN');
    
    // ដាក់ Chat ID របស់អ្នក (អ្នកអាចរកបានតាមរយៈការ Chat ទៅកាន់ @userinfobot ក្នុង Telegram)
    // ឬប្រើ Chat ID របស់សិស្សដែលអ្នកបានរក្សាទុកក្នុង Database
    $chatId = "1581124755"; 

    $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
        'chat_id' => $chatId,
        'text'    => "<b>សួស្តី!</b> នេះគឺជាការតេស្តចេញពី Laravel ទៅកាន់ Telegram Bot។ 🚀",
        'parse_mode' => 'HTML'
    ]);

    return $response->json();
});

Route::get('/test-firebase', function () {
    try {
        // បញ្ជាក់ឱ្យច្បាស់ថាប្រើ project ឈ្មោះ 'app'
        $firebase = Firebase::project('app');
        $auth = $firebase->auth();
        
        $users = $auth->listUsers();
        return "ការតភ្ជាប់ជោគជ័យ!";
    } catch (\Exception $e) {
        return "ការតភ្ជាប់បរាជ័យ: " . $e->getMessage();
    }
});

Route::get('/save-data', function () {
    try {
        // បញ្ជាក់ Path ឱ្យចំ File .json តែម្តង
        $filePath = base_path('storage/app/firebase/classmanagementsystem.json');

        // ឆែកមើលថា តើ File ហ្នឹងមានពិតមែនឬអត់
        if (!file_exists($filePath)) {
            return "រកមិនឃើញឯកសារ JSON តាមផ្លូវនេះទេ: " . $filePath;
        }

        $factory = (new Factory)
            ->withServiceAccount($filePath)
            ->withDatabaseUri('https://classmanagementsystem-cd57f-default-rtdb.firebaseio.com/');

        $database = $factory->createDatabase();
        $database->getReference('test_connection')->set([
            'status' => 'success',
            'message' => 'តភ្ជាប់បានជោគជ័យហើយ!',
            'time' => now()->toDateTimeString()
        ]);

        return "អបអរសាទរ! ទិន្នន័យបានចូលទៅដល់ Firebase ហើយ។";
    } catch (\Exception $e) {
        return "នៅតែមានបញ្ហា: " . $e->getMessage();
    }
});
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isProfessor()) {
            return redirect()->route('professor.dashboard');
        }
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
        return redirect()->route('auth.login');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes (Shared for all authenticated users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->isProfessor()) {
            return redirect()->route('professor.dashboard');
        } else { // Default to student role
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update-picture');
});

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Protected by 'role:admin' middleware)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/get-courses-by-program-and-generation', [CourseOfferingController::class, 'getCoursesByProgramAndGeneration'])->name('get-courses-by-program-and-generation');
        Route::get('/users', [UserController::class, 'manageUsers'])->name('manage-users');
        Route::get('/users/create', [UserController::class, 'createUser'])->name('create-user');
        Route::post('/users', [UserController::class, 'storeUser'])->name('store-user');
        Route::get('/users/{user}/edit', [UserController::class, 'editUser'])->name('edit-user');
        Route::put('/users/{user}', [UserController::class, 'updateUser'])->name('update-user');
        Route::delete('/users/{user}', [UserController::class, 'deleteUser'])->name('delete-user');
        Route::get('/users/show/{user}', [UserController::class, 'showUser'])->name('show-user');

        Route::get('/faculties', [FacultyController::class, 'index'])->name('manage-faculties');
        Route::get('/faculties/create', [FacultyController::class, 'create'])->name('create-faculty');
        Route::post('/faculties', [FacultyController::class, 'store'])->name('store-faculty');
        Route::get('/faculties/{faculty}/edit', [FacultyController::class, 'edit'])->name('edit-faculty');
        Route::put('/faculties/{faculty}', [FacultyController::class, 'update'])->name('update-faculty');
        Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy'])->name('delete-faculty');
        Route::get('/faculties/{faculty}/delete', [FacultyController::class, 'deleteFaculty'])->name('delete-faculty-get');
        // Route::get('/get-departments-by-faculty/{faculty}', [FacultyController::class, 'getDepartmentsByFaculty'])->name('get-departments-by-faculty');
        // Route::get('/get-departments-by-faculty/{faculty}', [AdminController::class, 'getDepartmentsByFaculty'])->name('get-departments-by-faculty');

        Route::get('/departments', [DepartmentController::class, 'index'])->name('manage-departments');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('create-department');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('store-department');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('edit-department');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('update-department');
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('delete-department');
        Route::get('/get-departments-by-faculty/{faculty}', [DepartmentController::class, 'getDepartmentsByFaculty'])->name('get-departments-by-faculty');


        Route::get('/programs', [ProgramController::class, 'index'])->name('manage-programs');
        Route::get('/programs/create', [ProgramController::class, 'create'])->name('create-program');
        Route::post('/programs', [ProgramController::class, 'store'])->name('store-program');
        Route::get('/programs/{program}/edit', [ProgramController::class, 'edit'])->name('edit-program');
        Route::put('/programs/{program}', [ProgramController::class, 'update'])->name('update-program');
        Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])->name('delete-program');

        Route::get('/courses', [CourseController::class, 'index'])->name('manage-courses');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('create-course');
        Route::post('/courses', [CourseController::class, 'store'])->name('store-course');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('edit-course');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('update-course');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('delete-course');

        Route::resource('course-offerings', CourseOfferingController::class);
        Route::get('/course-offerings', [CourseOfferingController::class, 'index'])->name('manage-course-offerings');
        Route::get('/course-offerings/create', [CourseOfferingController::class, 'create'])->name('create-course-offering');
        Route::post('/course-offerings', [CourseOfferingController::class, 'store'])->name('store-course-offering');
        Route::get('/course-offerings/{courseOffering}/edit', [CourseOfferingController::class, 'edit'])->name('edit-course-offering');
        Route::put('/course-offerings/{courseOffering}', [CourseOfferingController::class, 'update'])->name('update-course-offering');
        Route::delete('/course-offerings/{courseOffering}', [CourseOfferingController::class, 'destroy'])->name('course-offerings.destroy');
        Route::get('/enroll-student', [CourseOfferingController::class, 'enrollStudentForm'])->name('enroll_student_form');
        Route::post('/perform-enrollment', [CourseOfferingController::class, 'performEnrollment'])->name('perform_enrollment');

        Route::resource('rooms', RoomController::class);
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index'); 
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create'); 
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store'); 
        Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show'); 
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit'); 
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update'); 
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy'); 

        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/announcements/store', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/{announcement}/update', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
        
        Route::get('/users/search', [UserController::class, 'searchUsers'])->name('users.search');
        Route::get('/get-courses-by-program/{program}', [AdminController::class, 'getCoursesByProgram'])->name('get-courses-by-program');
        Route::get('/course-offerings/{courseOffering}', [AdminController::class, 'showCourseOffering'])->name('show-course-offering');
    });

/*
|--------------------------------------------------------------------------
| Professor Routes (Protected by 'role:professor' middleware)
|--------------------------------------------------------------------------
*/
// Route::get('/export-grades/{offering_id}', [ProfessorController::class, 'exportGrades'])
//     ->name('professor.grades.export')
//     ->middleware('auth'); // បន្ថែមនេះដើម្បីសុវត្ថិភាពទិន្នន័យ


    Route::middleware(['auth', 'role:professor'])->prefix('professor')->name('professor.')->group(function () {



        Route::get('/dashboard', [ProfessorController::class, 'dashboard'])->name('dashboard');
        Route::get('/view-departments', [ProfessorController::class, 'viewDepartments'])->name('view-departments');
        Route::get('/view-programs', [ProfessorController::class, 'viewPrograms'])->name('view-programs');
        Route::get('/view-courses', [ProfessorController::class, 'viewCourses'])->name('view-courses');
        Route::get('/view-all-course-offerings', [ProfessorController::class, 'viewAllCourseOfferings'])->name('view-all-course-offerings');
        Route::get('/all-students', [ProfessorController::class, 'allStudents'])->name('all-students');
        Route::get('/my-course-offerings', [ProfessorController::class, 'myCourseOfferings'])->name('my-course-offerings');
        Route::get('/course-offering/{offering_id}/grades', [ProfessorController::class, 'manageGrades'])->name('manage-grades');
        Route::get('/course-offering/{offering_id}/attendance', [ProfessorController::class, 'manageAttendance'])->name('manage-attendance');
        Route::get('/course-offering/{offering_id}/assignments', [ProfessorController::class, 'manageAssignments'])->name('manage-assignments');
        Route::get('/course-offering/{offering_id}/exams', [ProfessorController::class, 'manageExams'])->name('manage-exams');
        Route::post('/course-offering/{offering_id}/exams', [ProfessorController::class, 'storeExam'])->name('store-exam');

        Route::get('/professor/all-grades', [GradeController::class, 'allGrades'])->name('grades.all');
        Route::get('/courses/{courseOffering}/grades/manage', [GradeController::class, 'manageGrades'])->name('grades.manage');
        Route::post('/courses/{courseOffering}/grades/store', [GradeController::class, 'storeOrUpdate'])->name('grades.storeOrUpdate');
        Route::get('/professor/courses', [GradeController::class, 'professorCourses'])->name('professor.courses');
        Route::get('/professor/grades/{courseOffering}', [GradeController::class, 'manageGrades'])->name('grades.manage');
        Route::post('/professor/grades/{courseOffering}/store', [GradeController::class, 'storeOrUpdateGrades'])->name('grades.storeOrUpdate');

        Route::get('/course-offerings/{offering_id}/assignments/{assignment}/edit', [ProfessorController::class, 'editAssignment'])->name('assignments.edit');
        Route::put('/course-offerings/{offering_id}/assignments/{assignment}', [ProfessorController::class, 'updateAssignment'])->name('assignments.update');
        Route::delete('/course-offerings/{offering_id}/assignments/{assignment}', [ProfessorController::class, 'destroyAssignment'])->name('assignments.destroy');
        Route::get('/course-offering/{offering_id}/exams/{exam}/edit', [ProfessorController::class, 'editExam'])->name('exams.edit');
        Route::put('/course-offering/{offering_id}/exams/{exam}', [ProfessorController::class, 'updateExam'])->name('exams.update');
        Route::delete('/course-offering/{offering_id}/exams/{exam}', [ProfessorController::class, 'destroyExam'])->name('exams.destroy');
        Route::get('/all-attendance', [ProfessorController::class, 'allAttendance'])->name('all-attendance');
        Route::post('/attendances', [ProfessorController::class, 'storeAttendance'])->name('attendances.store');
        Route::put('/attendances/{attendance}', [ProfessorController::class, 'updateAttendance'])->name('attendances.update');
        Route::delete('/attendances/{attendance}', [ProfessorController::class, 'destroyAttendance'])->name('attendances.destroy');
        Route::get('/professor/my-schedule', [ProfessorController::class, 'mySchedule'])->name('my-schedule');
        Route::get('/professor/course-offering/{offering_id}/exams', [ProfessorController::class, 'manageExams'])->name('manage-exams');
        Route::post('/professor/course-offering/{offering_id}/exams', [ProfessorController::class, 'storeExam'])->name('store-exam');
        Route::get('/api/course-offerings-with-students', [ProfessorController::class, 'getCourseOfferingsWithStudents']);
        Route::get('/all-data', [ProfessorController::class, 'allDataView'])->name('all-data-view');
        Route::get('/course-offering/{offering_id}/students', [ProfessorController::class, 'getStudentsInCourseOffering'])->name('students.in-course-offering');
        Route::get('/professor/course-offerings/{courseOffering}/students', [ProfessorController::class, 'showStudentsInCourse'])->name('professor.course-offerings.students.index');
        Route::get('/professor/course-offerings/{courseOffering}/students', [ProfessorController::class, 'showStudentsInCourse'])->name('course-offerings.students.index');
        Route::get('/professor/course-offerings/{courseOffering}/students/{student}', [ProfessorController::class, 'showStudentProfile'])->name('students.show');
        Route::get('/students/{student}', [ProfessorController::class, 'showStudentProfile'])->name('professor.students.show');
        Route::get('/professor/profile/create', [ProfessorProfileController::class, 'create'])->name('profile.create');
        // Route::post('/professor/profile', [ProfessorProfileController::class, 'store'])->name('profile.store');
        // Route::get('/professor/profile/edit', [ProfessorProfileController::class, 'edit'])->name('profile.edit');
        // Route::put('/professor/profile', [ProfessorProfileController::class, 'update'])->name('profile.update');
        Route::get('/notifications', [ProfessorController::class, 'notificationsIndex'])->name('notifications.index');
        Route::get('/course-offerings/{courseOffering}/students', [ProfessorController::class, 'getStudentsForCourseOffering'])->name('course_offerings.students');
        Route::get('/notifications/create', [ProfessorController::class, 'createNotificationForm'])->name('notifications.create');
        Route::post('/notifications/store', [ProfessorController::class, 'notificationsStore'])->name('notifications.store');
        Route::get('/notifications/{id}/edit', [ProfessorController::class, 'notificationsEdit'])->name('notifications.edit');
        Route::put('/notifications/{id}', [ProfessorController::class, 'notificationsUpdate'])->name('notifications.update');
        Route::delete('/notifications/{id}', [ProfessorController::class, 'notificationsDestroy'])->name('notifications.destroy');
        Route::get('/course/{course}/grading-categories', [ProfessorController::class, 'manageGradingCategories'])->name('grading-categories.index');
        Route::post('/course/{course}/grading-categories', [ProfessorController::class, 'storeGradingCategory'])->name('grading-categories.store');
        Route::delete('/grading-categories/{category}', [ProfessorController::class, 'destroyGradingCategory'])->name('grading-categories.destroy');
        Route::get('/course-offerings/{offering_id}/grades', [ProfessorController::class, 'manageGrades'])->name('manage-grades');
        Route::get('/course-offerings/{offering_id}/assessments/create', [ProfessorController::class, 'createAssessmentForm'])->name('assessments.create');
        Route::post('/course-offerings/{offering_id}/assessments', [ProfessorController::class, 'storeAssessment'])->name('assessments.store');
        Route::get('/assessments/{assessment_id}/grades/edit', [ProfessorController::class, 'showGradeEntryForm'])->name('grades.edit');
        // Route::delete('/assessments/{id}', [ProfessorController::class, 'destroyAssessment'])->name('assessments.destroy');
        Route::post('/assessments/{assessment_id}/grades', [ProfessorController::class, 'storeGradesForAssessment'])->name('grades.store');
        Route::get('/course-offering/{offering_id}/assignments', [ProfessorController::class, 'manageAssignments'])->name('manage-assignments');
        Route::post('/course-offering/{offering_id}/assignments', [ProfessorController::class, 'storeAssignment'])->name('assignments.store');

        Route::post('/announcements/{announcement}/mark-as-read', [ProfessorController::class, 'markAsRead'])->name('announcements.markAsRead');
        Route::get('/profile', [ProfessorController::class, 'showProfile'])->name('profile.show');
        Route::get('/profile/edit', [ProfessorController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [ProfessorController::class, 'updateProfile'])->name('profile.update');

        Route::get('/quizzes/{quiz}/questions', [ProfessorController::class, 'manageQuizQuestions'])->name('quizzes.questions.index');
        Route::post('/quizzes/{quiz}/questions', [ProfessorController::class, 'storeQuizQuestion'])->name('quizzes.questions.store');

        Route::controller(QuizController::class)->group(function () {
            // Quizzes (Index, Store, Update, Delete)
            Route::get('/my-course-offerings/{offering_id}/quizzes', 'index')->name('quizzes.index');
            Route::post('/my-course-offerings/{offering_id}/quizzes', 'store')->name('quizzes.store');
            
            Route::put('/my-course-offerings/{offering_id}/quizzes/{quiz}', 'update')->name('quizzes.update');
            Route::delete('/my-course-offerings/{offering_id}/quizzes/{quiz}', 'destroy')->name('quizzes.destroy');
            
            // New: Route to manage questions for a specific quiz
            Route::get('/my -course-offerings/{offering_id}/quizzes/{quiz}/questions', 'manageQuestions')->name('quizzes.manage-questions');

            Route::post('/telegram/webhook', [TelegramController::class, 'handle']);


    Route::post('/professor/send-grade-telegram/{enrollment_id}', [ProfessorController::class, 'sendGradeTelegram'])
    ->name('send_grade_telegram');
Route::post('/professor/course-offering/{id}/send-all-telegram', [ProfessorController::class, 'sendAllTelegram'])->name('send_all_telegram');


Route::post('/update-telegram', [ProfessorController::class, 'updateTelegram'])->name('update_telegram');

        });

Route::patch('/professor/course-offering/{offering_id}/student/{student_user_id}/toggle-leader', 
    [ProfessorController::class, 'toggleClassLeader']
)->name('toggleClassLeader');

        // ទំព័រសម្រាប់បង្ហាញបញ្ជីឈ្មោះស្រង់វត្តមាន
Route::get('/course-offerings/{courseOffering}/attendance', [ProfessorController::class, 'attendanceIndex'])->name('attendance.index');

// Route សម្រាប់ Save ទិន្នន័យវត្តមាន
Route::post('/course-offerings/{courseOffering}/attendance', [ProfessorController::class, 'attendanceStore'])->name('attendance.store');

Route::get('/course-offerings/{courseOffering}/attendance-report', [ProfessorController::class, 'attendanceReport'])
    ->name('attendance.report');
              // Route::get('/course-offering/{offering_id}/quizzes', [QuizController::class, 'index'])->name('manage-quizzes');
        // Route::get('/course-offering/{offering_id}/quizzes/create', [QuizController::class, 'create'])->name('create-quiz');
        // Route::post('/course-offering/{offering_id}/quizzes', [QuizController::class, 'stor e'])->name('store-quiz');
        // Route::get('/course-offering/{offering_id}/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('edit-quiz');
        // Route::put('/course-offering/{offering_id}/quizzes/{quiz}', [QuizController::class, 'update'])->name('update-quiz');
        // Route::delete('/course-offering/{offering_id}/quizzes/{quiz}', [QuizController::class, 'delete'])->name('delete-quiz');

Route::post('/professor/grades/store/{assessment_id}', [ProfessorController::class, 'updateGrades'])
     ->name('professor.grades.store');
Route::delete('/assessments/{id}', [ProfessorController::class, 'destroyAssessment'])->name('assessments.destroy');
        Route::get('/assessments/{id}/edit/{type}',
            [ProfessorController::class, 'assessmentEdit']
        )->name('assessments.edit');

        Route::put('/assessments/{id}/{type}',
            [ProfessorController::class, 'update']
        )->name('assessments.update');

// ដាក់ក្នុង Route Group របស់ Professor
// Route::get('assessment/{id}/export-csv', [GradeController::class, 'exportCSV'])->name('grades.export');
// Route::post('/assessment/{id}/import-csv', [GradeController::class, 'importCSV'])->name('grades.import');
// Ensure this group is inside your 'professor' prefix/name group
Route::prefix('grades')->name('grades.')->group(function () {
    
    // This will now be named: professor.grades.edit-attendance
    Route::get('/edit/{student_id}/{course_id}', [GradeController::class, 'editAttendance'])
        ->name('edit-attendance');

    Route::post('/attendance/update', [GradeController::class, 'updateAttendanceScore'])
        ->name('update-attendance');
});
Route::get('/course-offering/{course_offering}/grades', [GradeController::class, 'index'])
        ->name('course-offering.grades');

    // Route សម្រាប់កែវត្តមានដែលអ្នកមានស្រាប់
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/edit/{student_id}/{course_id}', [GradeController::class, 'editAttendance'])
            ->name('edit-attendance');
    });
        
    });
Route::get('/course-offerings/{offering_id}/export-docx', [ProfessorController::class, 'exportStudentsDocx'])
    ->name('professor.students.export-docx');
Route::get('/course-offerings/{offering_id}/export-gradebook', [ProfessorController::class, 'exportGradebookDocx'])
    ->name('professor.grades.export-docx');


    Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-grades', [StudentController::class, 'myGrades'])->name('my-grades');
        Route::get('/my-enrolled-courses', [StudentController::class, 'myEnrolledCourses'])->name('my-enrolled-courses');
        Route::get('/my-schedule', [StudentController::class, 'mySchedule'])->name('my-schedule');
        Route::get('/my-assignments', [StudentController::class, 'myAssignments'])->name('my-assignments');
        Route::get('/my-exams', [StudentController::class, 'myExams'])->name('my-exams');
        Route::get('/my-quizzes', [StudentController::class, 'myQuizzes'])->name('my-quizzes');
        Route::get('/quizzes/{quiz_id}', [StudentController::class, 'takeQuiz'])->name('take-quiz');
        Route::post('/quizzes/{quiz_id}/submit', [StudentController::class, 'submitQuiz'])->name('submit-quiz');
        Route::get('/{studentId}/enrolled-courses', [StudentController::class, 'enrolledCourses'])->name('enrolled_courses');
        Route::get('/available-programs', [StudentController::class, 'availablePrograms'])->name('available_programs');
        Route::get('/available-courses', [StudentController::class, 'availableCourses'])->name('available_courses');
        Route::post('/enroll-self', [StudentController::class, 'enrollSelf'])->name('enroll_self');
        Route::get('/available-courses', [StudentController::class, 'availableCourses'])->name('available_courses');
        Route::post('/enroll-self', [StudentController::class, 'enrollSelf'])->name('enroll_self');
        Route::get('/my-attendance', [StudentController::class, 'myAttendance'])->name('my-attendance');
        Route::get('profile', [StudentProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [StudentProfileController::class, 'update'])->name('profile.update');
        Route::get('/rooms', [StudentController::class, 'rooms']) ->name('rooms.index');
        Route::get('/my-timetable', [StudentController::class, 'myTimetable'])->name('my-timetable');
        Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{id}/read', [StudentController::class, 'markAsRead'])->name('notifications.read');
        Route::patch('/announcements/{id}/read', [StudentController::class, 'markAnnouncementAsRead'])->name('announcements.read');
        Route::patch('/notifications/read-all', [StudentController::class, 'markAllAsRead'])->name('notifications.readAll');

        Route::get('/class-leader/course/{courseOffering}/attendance', [StudentController::class, 'leaderAttendance'])
        ->name('leader.attendance');
    // បន្ថែម Route ថ្មីនេះសម្រាប់ Save ទិន្នន័យ (POST)
    Route::post('/class-leader/course/{courseOffering}/attendance', [StudentController::class, 'storeLeaderAttendance'])
        ->name('leader.attendance.store');
    // បង្ហាញរបាយការណ៍ (Report)
    // ត្រូវប្រាកដថា name() គឺ 'leader.report' (ព្រោះវាបូកជាមួយ prefix name 'student.')
    Route::get('/leader/attendance-report/{courseOffering}', [StudentController::class, 'leaderAttendanceReport'])
        ->name('leader.report');
Route::post('/student/update-telegram', [App\Http\Controllers\StudentController::class, 'updateTelegram'])
    ->name('update_telegram');
    
    });

    Route::get('/check-time', function() {
        dd(now()->toDateTimeString(), config('app.timezone'));
    });




// Route::get('assessment/{id}/export-excel', [GradeController::class, 'exportExcel'])->name('grades.export');
// Route::post('assessment/{id}/import-excel', [GradeController::class, 'importExcel'])->name('grades.import');

Route::get('assessment/{id}/export-csv', [GradeController::class, 'exportCSV'])->name('grades.export');
Route::post('/assessment/{id}/import-csv', [GradeController::class, 'importCSV'])->name('grades.import');



require __DIR__.'/auth.php';