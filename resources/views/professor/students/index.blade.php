<x-app-layout>
    <div class="py-4 md:py-12 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-6 flex flex-col lg:flex-row lg:items-end justify-between gap-4 no-print">
                <div class="space-y-1 text-center md:text-left">
                    <nav class="flex items-center justify-center md:justify-start gap-1.5 text-[9px] md:text-xs font-bold uppercase tracking-widest text-slate-400">
                        <span class="cursor-default">សាស្ត្រាចារ្យ</span>
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                        <span class="text-blue-500">បញ្ជីឈ្មោះនិស្សិត</span>
                    </nav>
                    <h1 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tight">
                        {{ __('បញ្ជីឈ្មោះនិស្សិត') }}
                    </h1>
                    <div class="flex items-center justify-center md:justify-start mt-1">
                        <span class="px-2.5 py-0.5 bg-blue-50 text-blue-600 text-[9px] md:text-[10px] font-black uppercase rounded-md border border-blue-100 shadow-sm">
                            {{ $courseOffering->course->name_km }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-center gap-2 md:gap-3">
                    <a href="{{ route('professor.my-course-offerings', ['offering_id' => $courseOffering->id]) }}"
                        class="flex-1 md:flex-none h-10 px-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm hover:bg-slate-50 transition-all flex items-center justify-center group text-[11px] md:text-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('ត្រឡប់') }}
                    </a>

                    <button onclick="window.print()"
                        class="flex-1 md:flex-none h-10 px-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg shadow-slate-200 hover:bg-blue-600 transition-all flex items-center justify-center group text-[11px] md:text-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                        </svg>
                        <span>{{ __('បោះពុម្ព') }}</span>
                    </button>
                </div>
            </div>

            {{-- Alerts --}}
            @if (session('success') || session('error'))
                <div class="mb-5 animate-in fade-in slide-in-from-top-2 duration-300">
                    <div class="{{ session('success') ? 'bg-emerald-500 shadow-emerald-100' : 'bg-red-500 shadow-red-100' }} text-white p-3.5 rounded-xl shadow-xl font-bold text-[11px] md:text-sm flex items-center gap-2.5">
                        <i class="fas {{ session('success') ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                        {{ session('success') ?? session('error') }}
                    </div>
                </div>
            @endif

            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6 no-print">
                @php
                    $statItems = [
                        ['label' => 'និស្សិតសរុប', 'value' => $stats['total'] ?? 0, 'icon' => 'fa-users', 'bg' => 'bg-blue-50', 'text' => 'text-blue-500'],
                        ['label' => 'និស្សិតប្រុស', 'value' => $stats['male'] ?? 0, 'icon' => 'fa-mars', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-500'],
                        ['label' => 'និស្សិតស្រី', 'value' => $stats['female'] ?? 0, 'icon' => 'fa-venus', 'bg' => 'bg-rose-50', 'text' => 'text-rose-500'],
                        ['label' => 'ប្រធានថ្នាក់', 'value' => $stats['leaders'] ?? 0, 'icon' => 'fa-crown', 'bg' => 'bg-amber-50', 'text' => 'text-amber-500'],
                    ];
                @endphp

                @foreach($statItems as $item)
                <div class="bg-white p-3 md:p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-2.5 md:gap-4 hover:border-blue-200 transition-all">
                    <div class="w-8 h-8 md:w-12 md:h-12 {{ $item['bg'] }} {{ $item['text'] }} rounded-lg md:rounded-2xl flex items-center justify-center shadow-inner">
                        <i class="fas {{ $item['icon'] }} text-xs md:text-lg"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">{{ $item['label'] }}</p>
                        <h4 class="text-sm md:text-xl font-black text-slate-800">{{ $item['value'] }} នាក់</h4>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Table Section --}}
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl md:rounded-[2.5rem] overflow-hidden no-print">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-4 md:px-8 py-4 text-[9px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.1em] md:tracking-[0.2em] italic">{{ __('ព័ត៌មាននិស្សិត') }}</th>
                                <th class="px-4 py-4 text-[9px] md:text-[11px] font-black text-slate-400 uppercase tracking-wider hidden sm:table-cell italic text-center">{{ __('លេខសម្គាល់') }}</th>
                                <th class="px-4 py-4 text-[9px] md:text-[11px] font-black text-slate-400 uppercase tracking-wider hidden lg:table-cell italic">{{ __('ទំនាក់ទំនង') }}</th>
                                <th class="px-4 md:px-8 py-4 text-[9px] md:text-[11px] font-black text-slate-400 uppercase tracking-wider text-right italic">{{ __('សកម្មភាព') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($paginatedStudents as $student)
                                @php
                                    $profilePictureUrl = $student->studentProfile && $student->studentProfile->profile_picture_url ? asset('storage/' . $student->studentProfile->profile_picture_url) : null;
                                    $isLeader = DB::table('student_course_enrollments')->where('course_offering_id', $courseOffering->id)->where('student_user_id', $student->id)->where('is_class_leader', 1)->exists();
                                @endphp
                                <tr class="group hover:bg-blue-50/30 transition-all duration-200">
                                    <td class="px-4 md:px-8 py-3.5 md:py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="relative flex-shrink-0">
                                                <div class="w-9 h-9 md:w-12 md:h-12 rounded-lg md:rounded-2xl overflow-hidden flex items-center justify-center bg-white border-2 {{ $isLeader ? 'border-amber-400 ring-2 ring-amber-50' : 'border-slate-100' }}">
                                                    @if($profilePictureUrl)
                                                        <img src="{{ $profilePictureUrl }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-[10px] md:text-sm font-black text-blue-600 bg-blue-50 w-full h-full flex items-center justify-center">
                                                            {{ Str::substr($student->studentProfile->full_name_km ?? $student->name, 0, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($isLeader)
                                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-amber-400 text-white rounded-md flex items-center justify-center shadow-md border border-white">
                                                        <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1.01 0 00.951-.69l1.07-3.292z"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-[12px] md:text-[15px] font-black text-slate-800 group-hover:text-blue-600 transition-colors tracking-tight">
                                                    {{ $student->studentProfile->full_name_km ?? $student->name }}
                                                </div>
                                                <div class="flex items-center gap-1.5 mt-0.5 text-[8px] md:text-[9px] font-black uppercase tracking-tighter">
                                                    <span class="{{ $isLeader ? 'text-amber-600 bg-amber-50' : 'text-slate-400 bg-slate-50' }} px-1 py-0.5 rounded">
                                                        {{ $isLeader ? 'ប្រធានថ្នាក់' : 'និស្សិត' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 md:py-5 hidden sm:table-cell whitespace-nowrap text-center">
                                        <span class="font-mono text-[11px] md:text-[13px] font-bold text-slate-500 bg-slate-100/50 px-2 py-1 rounded-lg border border-slate-200/50 italic">
                                            {{ $student->student_id_code ?? 'ID-000' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 md:py-5 hidden lg:table-cell whitespace-nowrap">
                                        <div class="text-[12px] font-bold text-slate-600">{{ $student->email }}</div>
                                        <div class="text-[10px] font-medium text-slate-400 italic">{{ $student->studentProfile->phone_number ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 md:px-8 py-3.5 md:py-5 whitespace-nowrap">
                                        <div class="flex justify-end items-center gap-1.5 md:gap-2">
                                            <a href="{{ route('professor.students.show', ['courseOffering' => $courseOffering->id, 'student' => $student->id]) }}" 
                                               class="h-7 md:h-9 px-2.5 md:px-4 bg-blue-50 text-blue-600 text-[9px] md:text-[11px] font-black uppercase rounded-lg hover:bg-blue-600 hover:text-white transition-all flex items-center gap-1.5 border border-blue-100 shadow-sm">
                                                <i class="fas fa-eye"></i>
                                                <span class="hidden md:inline">{{ __('មើល') }}</span>
                                            </a>

                                            <form action="{{ route('professor.toggleClassLeader', [$courseOffering->id, $student->id]) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" 
                                                    class="h-7 md:h-9 px-2.5 md:px-4 text-[9px] md:text-[11px] font-black uppercase rounded-lg transition-all flex items-center gap-1.5 {{ $isLeader ? 'bg-amber-100 text-amber-600 border border-amber-200 shadow-amber-50' : 'bg-slate-50 text-slate-400 border border-slate-200 hover:border-blue-400 hover:text-blue-600' }} shadow-sm">
                                                    <i class="fas fa-star"></i>
                                                    <span class="hidden md:inline">{{ $isLeader ? __('ប្រធាន') : __('តែងតាំង') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 md:py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-12 h-12 md:w-16 md:h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-user-slash text-slate-200 text-xl md:text-2xl"></i>
                                            </div>
                                            <h3 class="text-sm md:text-lg font-black text-slate-800">{{ __('មិនទាន់មាននិស្សិត') }}</h3>
                                            <p class="text-slate-400 text-[10px] md:text-xs mt-1">{{ __('មិនទាន់មាននិស្សិតចុះឈ្មោះក្នុងមុខវិជ្ជានេះនៅឡើយទេ។') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination & Reports --}}
                <div class="px-4 md:px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <a href="{{ route('professor.attendance.report', $courseOffering->id) }}" 
                       class="w-full md:w-auto h-9 px-5 bg-white border border-slate-200 text-slate-700 text-[9px] md:text-[11px] font-black uppercase tracking-wider rounded-xl hover:bg-slate-50 transition-all flex items-center justify-center shadow-sm">
                        <i class="fas fa-file-chart-line mr-2 text-blue-500"></i>
                        {{ __('របាយការណ៍វត្តមាន') }}
                    </a>
                    
                    <div class="w-full md:w-auto flex justify-center scale-90 md:scale-100">
                        {{ $paginatedStudents->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Print Section and Styles remain unchanged to protect print quality --}}

         {{-- ================= PRINT SECTION (PROFESSIONAL KHMER STYLE) ================= --}}
<div class="hidden print:block font-serif text-black px-10 py-8 bg-white">
    
    {{-- សញ្ញាជាតិ និងចំណងជើង --}}
    <div class="flex flex-col items-center text-center mb-8">
        <div class="mb-2">
            <h2 class="text-[16px] font-bold mb-1" style="font-family: 'Khmer OS Muol Light', serif;">ព្រះរាជាណាចក្រកម្ពុជា</h2>
            <h2 class="text-[15px] font-bold" style="font-family: 'Khmer OS Muol Light', serif;">ជាតិ សាសនា ព្រះមហាក្សត្រ</h2>
            <div class="mt-1 flex justify-center">
                <span class="w-24 border-b border-black"></span>
            </div>
        </div>
        
        <div class="mt-6">
            <h1 class="text-xl font-bold uppercase tracking-widest" style="font-family: 'Khmer OS Muol Light', serif;">
                {{ __('បញ្ជីរាយនាមនិស្សិតសរុប') }}
            </h1>
        </div>
    </div>

    {{-- ព័ត៌មានវគ្គសិក្សា --}}
    <div class="mb-6 grid grid-cols-2 gap-y-2 text-[13px]">
        <div>
            <p><span class="font-bold">មុខវិជ្ជា៖</span> <span class="ml-1">{{ $courseOffering->course->title_en }}</span></p>
            <p><span class="font-bold">ជំនាន់៖</span> <span class="ml-1">{{ $courseOffering->generation ?? '...' }}</span></p>
        </div>
        <div class="text-right">
            <p><span class="font-bold">កាលបរិច្ឆេទបោះពុម្ព៖</span> <span class="ml-1">{{ now()->format('d/m/Y') }}</span></p>
            <p><span class="font-bold">សរុបនិស្សិត៖</span> <span class="ml-1">{{ count($paginatedStudents) }} នាក់</span></p>
        </div>
    </div>

    {{-- តារាងទិន្នន័យ --}}
    <table class="w-full border-collapse border border-black text-[12px]">
        <thead>
            <tr class="bg-gray-100 border border-black">
                <th class="border border-black px-2 py-3 w-[5%] text-center">ល.រ</th>
                <th class="border border-black px-2 py-3 w-[12%] text-center">អត្តលេខ</th>
                <th class="border border-black px-2 py-3 text-left w-[20%]">ឈ្មោះនិស្សិត</th>
                <th class="border border-black px-2 py-3 w-[8%] text-center">ភេទ</th>
                <th class="border border-black px-2 py-3 w-[12%] text-center">ថ្ងៃខែឆ្នាំកំណើត</th>
                <th class="border border-black px-2 py-3 text-left w-[28%]">ដេប៉ាតឺម៉ង់ / កម្មវិធីសិក្សា</th>
                <th class="border border-black px-2 py-3 w-[15%] text-center">លេខទូរស័ព្ទ</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($paginatedStudents as $index => $student)
                @php
                    $profile = $student->studentProfile;
                    $enrollment = $student->studentProgramEnrollments->first();
                    $genderKm = in_array(strtoupper($profile->gender ?? ''), ['M', 'MALE']) ? 'ប្រុស' : 'ស្រី';
                @endphp

                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-2 py-2 text-center">{{ $index + 1 }}</td>
                    <td class="border border-black px-2 py-2 text-center font-mono">{{ $student->student_id_code ?? '-' }}</td>
                    <td class="border border-black px-2 py-2 font-medium">
                        {{ $profile->full_name_km ?? $student->name }}
                    </td>
                    <td class="border border-black px-2 py-2 text-center">{{ $genderKm }}</td>
                    <td class="border border-black px-2 py-2 text-center font-mono">
                        {{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="border border-black px-2 py-2 leading-tight">
                        {{ $enrollment->program->name_km ?? 'មិនទាន់កំណត់' }}
                    </td>
                    <td class="border border-black px-2 py-2 text-center font-mono">
                        {{ $profile->phone_number ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ផ្នែកហត្ថលេខា --}}
    <div class="mt-12 flex justify-between">
        <div class="text-center w-1/3">
            <p class="text-[13px]">បានពិនិត្យដោយ</p>
            <p class="mt-16 font-bold underline">..........................................</p>
        </div>
        <div class="text-center w-1/3">
            <p class="text-[12px] italic">ធ្វើនៅ រាជធានីភ្នំពេញ, ថ្ងៃទី....... ខែ....... ឆ្នាំ២០...</p>
            <p class="text-[13px] font-bold mt-1">អ្នករៀបចំបញ្ជី</p>
            <p class="mt-16 font-bold">..........................................</p>
        </div>
    </div>
</div>

<style>
    @media print {
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            -webkit-print-color-adjust: exact;
        }
    }
</style>

    


</x-app-layout>