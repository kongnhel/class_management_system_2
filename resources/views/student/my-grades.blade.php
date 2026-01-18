<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 sm:px-0">
            <div>
                <h2 class="font-black text-2xl md:text-3xl text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-200">
                        <i class="fas fa-graduation-cap"></i>
                    </span>
                    {{ __('ពិន្ទុរបស់ខ្ញុំ') }}
                </h2>
                <p class="text-[10px] text-slate-400 mt-2 font-black uppercase tracking-[0.2em] leading-none">Academic Performance Tracking</p>
            </div>
            <div class="hidden sm:flex flex-col items-end">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('ឆមាសទី ១') }}</span>
                <span class="text-sm font-bold text-indigo-600">ឆ្នាំសិក្សា ២០២៤-២០២៥</span>
            </div>
        </div>
    </x-slot>

    @php
        $colorPalette = [
            'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-100',    'accent' => 'bg-blue-500'],
            'indigo'  => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-600',  'border' => 'border-indigo-100',  'accent' => 'bg-indigo-500'],
            'purple'  => ['bg' => 'bg-purple-50',  'text' => 'text-purple-600',  'border' => 'border-purple-100',  'accent' => 'bg-purple-500'],
            'rose'    => ['bg' => 'bg-rose-50',    'text' => 'text-rose-600',    'border' => 'border-rose-100',    'accent' => 'bg-rose-500'],
            'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'accent' => 'bg-emerald-500'],
            'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'border' => 'border-amber-100',   'accent' => 'bg-amber-500'],
            'cyan'    => ['bg' => 'bg-cyan-50',    'text' => 'text-cyan-600',    'border' => 'border-cyan-100',    'accent' => 'bg-cyan-500'],
        ];
        $colorKeys = array_keys($colorPalette);
    @endphp

    <div class="py-8 bg-[#f8fafc] min-h-screen font-['Battambang']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Summary Dashboard --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-[1.02]">
                    <div class="bg-amber-100 w-14 h-14 rounded-2xl flex items-center justify-center text-amber-600 shadow-inner shrink-0">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('ចំណាត់ថ្នាក់រួម') }}</p>
                        <h4 class="text-2xl font-black text-slate-800 italic">#{{ $overallRank ?? 'N/A' }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-[1.02]">
                    <div class="bg-emerald-100 w-14 h-14 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner shrink-0">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('មធ្យមភាគ') }}</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ number_format($averageScore ?? 0, 2) }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-[1.02]">
                    <div class="bg-indigo-100 w-14 h-14 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner shrink-0">
                        <i class="fas fa-plus-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('ពិន្ទុសរុប') }}</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ number_format($totalFinalScore ?? 0, 1) }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-[1.02]">
                    <div class="bg-rose-100 w-14 h-14 rounded-2xl flex items-center justify-center text-rose-600 shadow-inner shrink-0">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('និទ្ទេសរួម') }}</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ $overallGrade ?? 'A' }}</h4>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-[3rem] shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-white">
                    <h3 class="text-xl font-black text-slate-800 uppercase italic tracking-tighter">{{ __('តារាងលទ្ធផលសិក្សា') }}</h3>
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-center">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-50">
                                <th class="px-8 py-5 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('មុខវិជ្ជា') }}</th>
                                <th class="px-4 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('ចំណាត់ថ្នាក់') }}</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('ពិន្ទុបំបែក (ពិន្ទុ/ពេញ | និទ្ទេស)') }}</th>
                                <th class="px-10 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('ពិន្ទុសរុប') }}</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('និទ្ទេសរួម') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($grades as $grade)
                                @php
                                    $courseName = $grade->course_name_en;
                                    $colorIndex = abs(crc32($courseName)) % count($colorKeys);
                                    $ui = $colorPalette[$colorKeys[$colorIndex]];
                                    $percentage = min(100, ($grade->total_score / 100) * 100);
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-all border-l-4 {{ $grade->is_failed ? 'border-rose-500 bg-rose-50/30' : str_replace('bg-', 'border-', $ui['accent']) }}">
                                    <td class="px-8 py-6 text-left">
                                        <div class="flex items-center gap-4">
                                            <div class="{{ $grade->is_failed ? 'bg-rose-100 text-rose-600 border-rose-200' : $ui['bg'].' '.$ui['text'].' '.$ui['border'] }} w-12 h-12 rounded-2xl flex items-center justify-center font-black border shadow-sm text-lg">
                                                {{ substr($courseName, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black {{ $grade->is_failed ? 'text-rose-700' : 'text-slate-800' }}">{{ $courseName }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $grade->course_name_km }}</div>
                                                @if($grade->is_failed)
                                                    <span class="text-[9px] font-black bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full mt-1 inline-block uppercase">ប្រឡងសង</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-6">
                                        <span class="text-base font-black {{ $grade->is_failed ? 'text-rose-400' : 'text-slate-700' }} italic">#{{ $grade->course_rank }}</span>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex flex-wrap justify-center gap-2">
                                            @foreach($grade->assessments as $asmt)
                                                @php
                                                    $isPartFailed = false;
                                                    if($asmt->display_type === 'final' && $asmt->score_obtained < 24) $isPartFailed = true;
                                                    if($asmt->display_type === 'midterm' && $asmt->score_obtained < 9) $isPartFailed = true;
                                                    if($asmt->display_type === 'assignment' && $asmt->score_obtained < 9) $isPartFailed = true;
                                                @endphp
                                                <div class="flex flex-col items-center px-2 py-1 rounded-lg border {{ $isPartFailed ? 'bg-rose-50 border-rose-200 text-rose-600 font-black' : 'bg-white border-slate-100 text-slate-700' }} border shadow-sm min-w-[90px]">
                                                    <span class="text-[7px] font-black uppercase">{{ $asmt->display_type === 'quiz' ? 'Extra Quiz' : $asmt->display_type }}</span>
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="text-[11px] font-black">{{ number_format($asmt->score_obtained, 1) }}</span>
                                                        <span class="text-[8px] opacity-40">/{{ (int)$asmt->max_score }}</span>
                                                        <span class="text-[10px] font-black ml-1 text-indigo-500">/ {{ $asmt->grade ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="flex flex-col items-center px-2 py-1 rounded-lg border {{ $grade->attendance_score < 9 ? 'bg-rose-50 border-rose-200 text-rose-600' : 'bg-indigo-50 border-indigo-100 text-indigo-600' }} shadow-sm min-w-[80px]">
                                                <span class="text-[7px] font-black uppercase">វត្តមាន</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-[11px] font-black">{{ number_format($grade->attendance_score, 1) }}</span>
                                                    <span class="text-[10px] font-black ml-1">/ {{ $grade->attendance_score >= 9 ? 'P' : ' F' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex flex-col items-center">
                                            <span class="text-xl font-black {{ $grade->is_failed ? 'text-rose-600' : ($grade->total_score > 100 ? 'text-indigo-600' : 'text-slate-800') }}">{{ number_format($grade->total_score, 1) }} <span class="text-[10px] text-slate-300">/ 100</span></span>
                                            <div class="w-24 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                                <div class="h-full {{ $grade->is_failed ? 'bg-rose-500' : ($grade->total_score > 100 ? 'bg-indigo-500' : 'bg-amber-500') }} rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl {{ $grade->is_failed ? 'bg-rose-600' : 'bg-slate-900' }} text-white text-sm font-black shadow-lg">
                                            {{ $grade->grade }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-24 text-center text-slate-300 font-black uppercase text-xs">{{ __('មិនទាន់មានទិន្នន័យពិន្ទុ') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="md:hidden p-6 space-y-4 bg-slate-50/50">
                    @foreach ($grades as $grade)
                        @php
                            $courseName = $grade->course_name_en;
                            $percentage = min(100, ($grade->total_score / 100) * 100);
                        @endphp
                        <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 mb-4 overflow-hidden relative">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $grade->is_failed ? 'bg-rose-500' : 'bg-indigo-500' }} border-l-4"></div>
                            <div class="flex justify-between items-start mb-4 pl-2">
                                <div class="flex items-center gap-3">
                                    <div class="min-w-0">
                                        <h4 class="text-[13px] font-black text-slate-800 leading-tight truncate">{{ $courseName }}</h4>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $grade->course_name_km }}</p>
                                            <span class="text-[9px] font-black text-indigo-600 bg-indigo-50 px-1.5 rounded">#{{ $grade->course_rank }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-900 text-white text-[11px] font-black px-2.5 py-1 rounded-lg shrink-0">{{ $grade->grade }}</div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4 pl-2">
                                @foreach($grade->assessments as $asmt)
                                    <div class="bg-slate-50 rounded-xl p-2 border border-slate-100 flex flex-col items-center">
                                        <span class="text-[7px] font-black text-slate-400 uppercase mb-0.5">{{ $asmt->display_type }}</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-[11px] font-black text-slate-700">{{ number_format($asmt->score_obtained, 1) }}</span>
                                            <span class="text-[8px] opacity-40">/{{ (int)$asmt->max_score }}</span>
                                            <span class="text-[9px] font-black text-indigo-500">[{{ $asmt->grade }}]</span>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="bg-indigo-50 rounded-xl p-2 border border-indigo-100 flex flex-col items-center text-indigo-600">
                                    <span class="text-[7px] font-black uppercase">វត្តមាន</span>
                                    <span class="text-[11px] font-black">{{ number_format($grade->attendance_score, 1) }} [{{ $grade->attendance_score >= 9 ? 'P' : 'F' }}]</span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-50 pl-2">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total: {{ number_format($grade->total_score, 1) }} / 100</span>
                                    @if($grade->is_failed) <span class="text-[9px] font-black text-rose-500">ប្រឡងសង</span> @endif
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $grade->is_failed ? 'bg-rose-500' : ($grade->total_score > 100 ? 'bg-indigo-500' : 'bg-amber-500') }} rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="mt-2 text-[8px] font-bold text-slate-400 uppercase">
                                    <i class="fas fa-info-circle mr-1"></i> អវត្តមាន: {{ $grade->absent_count ?? 0 }} | ច្បាប់: {{ $grade->permission_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($grades->hasPages())
                    <div class="px-10 py-6 bg-slate-50/30 border-t border-slate-50">
                        {{ $grades->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>