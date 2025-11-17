<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id_code', // Added for students
        'department_id',   // Added for professors
        'program_id',      // Added for students
        'generation',  
            // Added for students

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime', 
        ];
    }
    
    
    // Added for role check in authorization
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get the user profile associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    
    // In App\Models\User.php

// public function professorProfile()
// {
//     return $this->hasOne(ProfessorProfile::class);
// }

    /**
     * Get the department that the professor user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the program that the student user belongs to.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the faculties where this user is the dean.
     */
    public function facultiesAsDean()
    {
        return $this->hasMany(Faculty::class, 'dean_user_id');
    }

    /**
     * Get the departments where this user is the head.
     */
    public function departmentsAsHead()
    {
        return $this->hasMany(Department::class, 'head_user_id');
    }

    /**
     * Get the course offerings where this user is the lecturer.
     */
    public function courseOfferingsAsLecturer()
    {
        return $this->hasMany(CourseOffering::class, 'lecturer_user_id');
    }

    /**
     * Get the program enrollments for this student user.
     */
    public function studentProgramEnrollments()
    {
        return $this->hasMany(StudentProgramEnrollment::class, 'student_user_id');
    }

    /**
     * Get the course enrollments for this student user.
     */
    public function studentCourseEnrollments()
    {
        return $this->hasMany(StudentCourseEnrollment::class, 'student_user_id');
    }

    /**
     * Get the attendance records for this student user.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_user_id');
    }

    /**
     * Get the assignment submissions for this student user.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_user_id');
    }

    /**
     * Get the exam results for this student user.
     */
    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'student_user_id');
    }

    /**
     * Get the quiz responses for this student user.
     */
    public function quizResponses()
    {
        return $this->hasMany(StudentQuizResponse::class, 'student_user_id');
    }

    /**
     * Get the announcements posted by this user.
     */
    public function announcementsPosted()
    {
        return $this->hasMany(Announcement::class, 'poster_user_id');
    }

    /**
     * Get the notifications for this user (if using custom notification table or morph relation).
     * If using default Laravel notifications, the Notifiable trait already handles this.
     */
    public function sentNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

       public function studentEnrollments()
    {
        return $this->hasMany(StudentCourseEnrollment::class, 'student_user_id');
    }
        public function programs()
    {
        return $this->hasManyThrough(
            Program::class, // The final model we want to access
            CourseOffering::class, // The intermediate model
            'program_id', // Foreign key on the intermediate table...
            'id', // Foreign key on the User table...
            'id', // Local key on the User table...
            'course_offering_id', // Local key on the intermediate table...
        );
    }
    public function userProfile()
{
    return $this->hasOne(UserProfile::class);
}

    public function studentProfile()
{
    return $this->hasOne(StudentProfile::class);
}

    /**
     * Get the course offerings taught by the user (if they are a professor).
     */
    public function taughtCourseOfferings()
    {
        return $this->hasMany(CourseOffering::class, 'lecturer_user_id');
    }

    // You may also have other relationships here, like department() or program()
    // You can also add the studentCourseEnrollments relationship here for consistency
    

}
