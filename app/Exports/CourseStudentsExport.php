<?php

namespace App\Exports;

use App\Models\StudentCourseEnrollment;
use Maatwebsite\Excel\Concerns\FromCollection;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CourseStudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $courseOfferingId;

    public function __construct($courseOfferingId)
    {
        $this->courseOfferingId = $courseOfferingId;
    }

    public function collection()
    {
        // ទាញយកសិស្សទាំងអស់ក្នុងថ្នាក់នេះ (មិនមែនយកតែមួយ Page ទេ គឺយកទាំងអស់)
        return StudentCourseEnrollment::with(['student.studentProfile', 'student.studentProgramEnrollments.program'])
            ->where('course_offering_id', $this->courseOfferingId)
            ->get();
    }

    // កំណត់ទិន្នន័យដែលត្រូវដាក់ក្នុង Excel តាមជួរនីមួយៗ
    public function map($enrollment): array
    {
        $student = $enrollment->student;
        $profile = $student->studentProfile;
        $program = $student->studentProgramEnrollments->first()?->program;

        return [
            $student->student_id_code ?? 'N/A', // អត្តលេខ
            $profile->full_name_km ?? $student->name, // ឈ្មោះ
            ($profile->gender == 'M' || $profile->gender == 'Male') ? 'ប្រុស' : 'ស្រី', // ភេទ
            $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('d/m/Y') : '-', // ថ្ងៃកំណើត
            $program->name_km ?? '-', // ជំនាញ/ដេប៉ាតឺម៉ង់
            $profile->phone_number ?? '-', // លេខទូរស័ព្ទ
            $student->email, // អ៊ីមែល
        ];
    }

    // ក្បាលតារាង (Header)
    public function headings(): array
    {
        return [
            'អត្តលេខ',
            'ឈ្មោះនិស្សិត',
            'ភេទ',
            'ថ្ងៃខែឆ្នាំកំណើត',
            'ជំនាញ/ដេប៉ាតឺម៉ង់',
            'លេខទូរស័ព្ទ',
            'អ៊ីមែល',
        ];
    }

    // ដាក់ Style ឱ្យក្បាលតារាងដិតស្អាត (Bold Header)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}