<x-app-layout>
    <x-slot name="header">
        {{-- HEADER (រក្សាទុកផ្នែកនេះពីការកែសម្រួលមុន) --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-4 sm:px-0">
            <h2 class="font-extrabold text-3xl md:text-4xl text-gray-900 leading-tight tracking-wide mb-4 md:mb-0">
                {{ __('តារាងពិន្ទុ') }}
            </h2>
            
            <div class="order-3 md:order-2 mt-2 md:mt-0 flex flex-col md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-6 text-sm md:text-base">
                <p class="text-gray-600 font-medium">{{ __('មុខវិជ្ជា:') }}
                    <span class="font-extrabold text-indigo-700 block md:inline">{{ $courseOffering->course->title_km }}</span>
                </p>
                <p class="text-gray-500 font-normal">{{ $courseOffering->academic_year }} / {{ $courseOffering->semester }}</p>
            </div>
            
            <a href="{{ route('professor.my-course-offerings', ['offering_id' => $courseOffering->id]) }}"
                class="order-2 md:order-3 inline-flex items-center px-4 py-2 text-sm
                       bg-gradient-to-r from-indigo-500 via-indigo-600 to-indigo-700
                       hover:from-indigo-600 hover:via-indigo-700 hover:to-indigo-800
                       text-white font-semibold rounded-lg shadow-md
                       hover:shadow-lg transform hover:scale-105
                       transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <svg class="w-4 h-4 mr-1 md:mr-2" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">{{ __('ត្រឡប់ទៅបញ្ជីមុខវិជ្ជា') }}</span>
                <span class="sm:hidden">{{ __('ត្រឡប់') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-full lg:max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Action Buttons & Messages (រក្សាទុកផ្នែកនេះ) --}}
            <div class="mb-6 flex justify-between items-center no-print">
                <a href="{{ route('professor.assessments.create', ['offering_id' => $courseOffering->id]) }}"
                    class="inline-flex items-center px-4 py-2 text-sm
                           bg-gradient-to-r from-green-500 via-green-600 to-green-700
                           hover:from-green-600 hover:via-green-700 hover:to-green-800
                           text-white font-semibold rounded-lg shadow-md
                           hover:shadow-lg transform hover:scale-105
                           transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-green-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('បន្ថែមពិន្ទុថ្មី') }}
                </a>

                <button onclick="window.print()"
                    class="px-4 py-2 md:px-6 md:py-3 bg-indigo-600 text-white font-bold text-sm md:text-lg rounded-xl shadow-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center space-x-2 ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden sm:inline">{{ __('បោះពុម្ព') }}</span>
                    <span class="sm:hidden">{{ __('បោះពុម្ព') }}</span>
                </button>
            </div>
            
            {{-- Success and Error messages --}}
            {{-- ... (រក្សាទុកសារជោគជ័យ និងបញ្ហា) --}}
          {{-- Success/Error Messages (Existing) --}}
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('ជោគជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('បរាជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

            {{-- Card List Layout (សម្រាប់ Mobile) និង Table (សម្រាប់ Desktop) --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-200 p-4 md:p-6">
                <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-6">
                    {{ __('បញ្ជីពិន្ទុ') }}
                </h3>

                {{-- ** DESKTOP VIEW: Traditional Table (រក្សាទុកសម្រាប់ Desktop) ** --}}
                <div class="hidden md:block">
                    {{-- ខ្ញុំបានប្រើកូដតារាងពីមុន ដែលបានកែសម្រួលរួចហើយសម្រាប់ Desktop view --}}
                    <div class="overflow-x-auto relative shadow-sm rounded-lg border border-gray-100">
                        <table class="w-full text-sm text-gray-700 border-collapse table-auto">
                            <thead class="bg-gray-100 border-b-2 border-gray-200">
                                <tr class="bg-white/50">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-64">
                                        {{ __('ឈ្មោះនិស្សិត') }}
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">
                                        {{ __('វត្តមាន') }}
                                        <span class="block text-gray-400 text-xs font-normal mt-1 no-print">(10 {{ __('ពិន្ទុ') }})</span>
                                    </th>
                                    @forelse($assessments as $assessment)
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider min-w-[120px]">
                                            <div class="flex flex-col items-center">
                                                <a href="{{ route('professor.grades.edit', ['assessment_id' => $assessment->id, 'type' => $assessment instanceof \App\Models\Assignment ? 'assignment' : 'exam']) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200 font-bold"
                                                   title="ចុចដើម្បីបញ្ចូលពិន្ទុ">
                                                    {{ $assessment->title_km }}
                                                    <span class="block text-gray-500 text-xs font-normal capitalize">({{ $assessment->assessment_type ?? '❌ NO TYPE FOUND' }})</span>
                                                </a>
                                                <span class="block text-gray-400 text-xs font-normal mt-1 no-print">
                                                    ({{ $assessment->max_score }} {{ __('ពិន្ទុ') }})
                                                </span>
                                                {{-- <form method="POST" action="{{ route('professor.assessments.destroy', $assessment->id) }}" onsubmit="return confirm('{{ __('តើអ្នកពិតជាចង់លុបការវាយតម្លៃ') }} «{{ $assessment->title_km }}» {{ __('នេះមែនទេ? ពិន្ទុទាំងអស់ដែលពាក់ព័ន្ធនឹងត្រូវលុប!') }}');" class="mt-2 no-print">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-200 text-xs font-semibold flex items-center space-x-1" title="{{ __('លុបការវាយតម្លៃ') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        <span>{{ __('លុប') }}</span>
                                                    </button>
                                                </form> --}}
                                            </div>
                                        </th>
                                    @empty
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            {{ __('មិនទាន់មានការវាយតម្លៃ') }}
                                        </th>
                                    @endforelse
                                    @if ($assessments->isNotEmpty())
                                        <th class="px-6 py-4 text-center text-xs font-extrabold text-indigo-700 uppercase tracking-wider w-20">
                                            {{ __('ពិន្ទុសរុប') }}
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($students as $student)
                                    @php $totalScore = $student->attendance_score; @endphp
                                    <tr class="hover:bg-indigo-50/50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $student->profile->full_name_km ?? $student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ round($student->attendance_score, 2) }}</td>
                                        @if($assessments->isNotEmpty())
                                            @foreach ($assessments as $assessment)
                                                <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                                    @php
                                                        $score = $gradebook[$student->id][$assessment->id] ?? '-';
                                                        if (is_numeric($score)) { $totalScore += $score; }
                                                    @endphp
                                                    <span class="{{ is_numeric($score) && $score < ($assessment->max_score / 2) ? 'text-red-600 font-bold' : '' }}">
                                                        {{ $score }}
                                                    </span>
                                                </td>
                                            @endforeach
                                            <td class="px-6 py-4 text-sm font-extrabold text-indigo-700 text-center bg-indigo-50/50">
                                                {{ round($totalScore, 2) }}
                                            </td>
                                        @else
                                            <td class="px-6 py-4 text-sm text-gray-500 text-center">-</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ max(($assessments->count() > 0 ? $assessments->count() : 1) + 2, 3) }}"
                                            class="px-6 py-12 text-center text-gray-400 font-medium text-lg">
                                            {{ __('មិនទាន់មាននិស្សិតចុះឈ្មោះក្នុងមុខវិជ្ជានេះទេ។') }} 😢
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- ** MOBILE VIEW: Card List ** --}}
                <div class="md:hidden">
                    @forelse ($students as $student)
                        @php
                            $totalScore = $student->attendance_score;
                        @endphp
                        
                        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-4 mb-4">
                            
                            {{-- Student Header & Total Score --}}
                            <div class="flex justify-between items-center border-b pb-3 mb-3">
                                <h4 class="text-lg font-extrabold text-indigo-700">
                                    {{ $student->profile->full_name_km ?? $student->name }}
                                </h4>
                                <div class="text-sm font-bold text-gray-800 bg-indigo-100 px-3 py-1 rounded-full">
                                    {{ __('សរុប:') }} 
                                    <span class="text-indigo-800 ml-1">
                                        {{-- Calculate Total Score for Card Display --}}
                                        @php
                                            if($assessments->isNotEmpty()){
                                                foreach ($assessments as $assessment) {
                                                    $score = $gradebook[$student->id][$assessment->id] ?? '-';
                                                    if (is_numeric($score)) { $totalScore += $score; }
                                                }
                                            }
                                            echo round($totalScore, 2);
                                        @endphp
                                    </span>
                                </div>
                            </div>

                            {{-- Attendance Score --}}
                            <div class="flex justify-between text-sm py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600">{{ __('ពិន្ទុវត្តមាន') }} (10 {{ __('ពិន្ទុ') }}):</span>
                                <span class="font-semibold text-gray-800">{{ round($student->attendance_score, 2) }}</span>
                            </div>

                            {{-- Assessment Scores List --}}
                            <div class="mt-3 space-y-2">
                                @forelse ($assessments as $assessment)
                                    @php
                                        $score = $gradebook[$student->id][$assessment->id] ?? '-';
                                        $isLowScore = is_numeric($score) && $score < ($assessment->max_score / 2);
                                    @endphp
                                    <div class="flex justify-between text-sm">
                                        <span class="font-medium text-gray-600 mr-2">
                                            <a href="{{ route('professor.grades.edit', ['assessment_id' => $assessment->id, 'type' => $assessment instanceof \App\Models\Assignment ? 'assignment' : 'exam']) }}" 
                                               class="text-blue-600 hover:underline" title="ចុចដើម្បីបញ្ចូល/កែពិន្ទុ">
                                                {{ $assessment->title_km }}
                                            </a>
                                            <span class="text-xs text-gray-400"> ({{ $assessment->max_score }} {{ __('ពិន្ទុ') }})</span>
                                        </span>
                                        <span class="font-bold {{ $isLowScore ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $score }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-400 text-sm py-4">{{ __('មិនទាន់មានការវាយតម្លៃដែលបានកំណត់ទេ។') }}</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400 font-medium text-lg">
                            {{ __('មិនទាន់មាននិស្សិតចុះឈ្មោះក្នុងមុខវិជ្ជានេះទេ។') }} 😢
                        </div>
                    @endforelse
                </div>
                
            </div>
            
        </div>
    </div>
</x-app-layout>

{{-- Print Styles (រក្សាទុកដើម្បីបោះពុម្ពចេញជាទម្រង់ Table) --}}
<style>
@media print {
    /* ត្រូវប្រាកដថា Card View ត្រូវបានលាក់នៅពេលបោះពុម្ព */
    .md\:hidden { display: none !important; }
    .hidden.md\:block { display: block !important; } 

    .no-print, header, nav { display: none !important; }

    body {
        font-family: 'Battambang','Khmer OS',Arial,sans-serif !important;
        background: #fff !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact;
        box-shadow: none !important;
        margin: 0;
        padding: 0;
    }

    .py-12, .px-6, .sm\:px-6, .lg\:px-8 { padding: 0 !important; }
    .bg-white, .shadow-lg, .sm\:rounded-2xl, .bg-gray-100, .bg-white\/50, .border, .divide-y {
        background: none !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    table {
        border-collapse: collapse !important;
        width: 100% !important;
        font-size: 14px;
    }
    th, td {
        border: 1px solid #000 !important;
        padding: 8px !important;
        color: #000 !important;
        vertical-align: middle !important;
    }

    thead, tr, th, td { page-break-inside: avoid; }
    a { color: #000 !important; text-decoration: none !important; }
    a:after { content: none !important; }
    .overflow-x-auto { overflow: visible !important; }
}
</style>