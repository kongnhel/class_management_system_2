# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel-based class management system for educational institutions. It supports three main user roles:
- **Admin**: Manages users, faculties, departments, programs, courses, course offerings, rooms, and announcements
- **Professor**: Manages course offerings, grades, attendance, assignments, exams, and notifications
- **Student**: Views enrolled courses, grades, attendance, schedules, and receives notifications

## Common Development Commands

### Development Server
```bash
# Start development server with all services (server, queue, logs, vite)
composer run dev

# Or start individual services
php artisan serve
php artisan queue:listen --tries=1
php artisan pail --timeout=0
npm run dev
```

### Testing
```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/ExampleTest.php
```

### Database
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name
```

### Code Quality
```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Run static analysis
php artisan code:analyse
```

## Architecture

### Role-Based Access Control
The system uses a custom role-based middleware system defined in `routes/web.php`:
- `role:admin` - Admin routes
- `role:professor` - Professor routes
- `role:student` - Student routes

User roles are stored in the `users` table with a `role` column. The `User` model provides helper methods:
- `isAdmin()`, `isProfessor()`, `isStudent()` methods in `app/Models/User.php`

### Key Models and Relationships

**User Model** (`app/Models/User.php`):
- Central model with role-based relationships
- Relationships to `UserProfile`, `StudentProfile`, `ProfessorProfile`
- Links to `Department`, `Program`, `CourseOffering` (as lecturer)
- Attendance records, submissions, exam results, quiz responses

**CourseOffering Model** (`app/Models/CourseOffering.php`):
- Links `Course` to `Program` with specific offering details
- Relationships to `lecturer`, `students`, `schedules`, `assignments`, `exams`, `quizzes`
- Contains enrollment data through `studentCourseEnrollments`

**Attendance System**:
- Attendance records stored in `attendance_records` table
- Auto-calculation of attendance scores (15% of total grade)
- Formula: 2 absences = -1 point, 4 permissions = -1 point
- QR code-based attendance system with location verification

### Controller Organization

Controllers are organized by role in `app/Http/Controllers/`:
- `admin/` - Admin-specific controllers
- `professor/` - Professor-specific controllers
- `Student/` - Student-specific controllers
- `Auth/` - Authentication controllers including QR login

### View Structure

Views are organized by role in `resources/views/`:
- `admin/` - Admin dashboard and management pages
- `professor/` - Professor dashboard and course management
- `student/` - Student dashboard and personal views
- `auth/` - Authentication pages including QR login
- `layouts/` - Main layout templates
- `components/` - Reusable Blade components

### Key Features

**QR Code Login System**:
- Desktop displays QR code (`QrLoginController`)
- Mobile app scans and authorizes login
- Token-based authentication with Firebase integration
- Location verification for attendance

**Grading System**:
- Multiple assessment types (assignments, exams, quizzes)
- Grading categories with configurable weights
- Manual and automatic attendance score calculation
- Export functionality for grades (CSV, DOCX)

**Notification System**:
- Database-backed notifications
- Role-specific notification views
- Telegram integration for grade notifications

**Real-time Features**:
- Laravel Echo and Pusher for real-time updates
- Livewire components for interactive UI elements

### Export Functionality

Export classes in `app/Exports/`:
- `UsersExport` - User data export
- `CourseStudentsExport` - Course enrollment export
- `StudentsGradeExport` - Grade data export

### External Integrations

- **Firebase**: Push notifications and authentication
- **Pusher**: Real-time event broadcasting
- **Telegram**: Grade notifications via bot
- **Cloudinary**: Image storage
- **DomPDF**: PDF generation
- **PhpWord**: Word document generation
- **Excel**: Import/export functionality

## Development Notes

### Database
- Uses SQLite by default (`database/database.sqlite`)
- Migration files in `database/migrations/`
- Seeders in `database/seeders/`

### Frontend
- Uses Vite for asset compilation
- Tailwind CSS for styling
- Alpine.js for client-side interactivity
- Laravel Breeze for authentication scaffolding

### Deployment
- Configured for Vercel deployment (`vercel.json`)
- Docker support (`Dockerfile`)
- Nixpacks configuration (`nixpacks.toml`)

### Testing
- Uses Pest PHP testing framework
- Test files in `tests/Feature/` and `tests/Unit/`
- PHPUnit configuration in `phpunit.xml`

## Important Files

- `routes/web.php` - Main route definitions with role-based middleware
- `app/Models/User.php` - User model with role methods and relationships
- `app/Models/CourseOffering.php` - Course offering model with relationships
- `app/Http/Controllers/AttendanceController.php` - Attendance management
- `app/Http/Controllers/Auth/QrLoginController.php` - QR code login system
- `resources/views/layouts/app.blade.php` - Main application layout
- `resources/views/layouts/navigation.blade.php` - Navigation component