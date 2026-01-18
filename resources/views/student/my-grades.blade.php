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
        
        // គណនាពិន្ទុសរុបពីគ្រប់មុខវិជ្ជា
        $totalObtainedScore = $grades->sum('score');
    @endphp

    <div class="py-8 bg-[#f8fafc] min-h-screen font-['Battambang']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Summary Dashboard - Updated to 4 Columns to include Total Score --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {{-- Rank Card --}}
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-[1.02]">
                    <div class="bg-amber-100 w-14 h-14 rounded-2xl flex items-center justify-center text-amber-600 shadow-inner shrink-0">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('ចំណាត់ថ្នាក់') }}</p>
                        <h4 class="text-2xl font-black text-slate-800 italic">#{{ $overallRank ?? '1' }}</h4>
                    </div>
                </div>

                {{-- Average Card --}}
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-[1.02]">
                    <div class="bg-emerald-100 w-14 h-14 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner shrink-0">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('មធ្យមភាគ') }}</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ number_format($averageScore ?? 0, 2) }}</h4>
                    </div>
                </div>

                {{-- Total Score Card - NEWLY ADDED --}}
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-[1.02]">
                    <div class="bg-indigo-100 w-14 h-14 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner shrink-0">
                        <i class="fas fa-plus-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('ពិន្ទុសរុប') }}</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ number_format($totalObtainedScore, 1) }}</h4>
                    </div>
                </div>

                {{-- Overall Grade Card --}}
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

            {{-- Main Grades Table Section --}}
            <div class="bg-white border border-slate-100 rounded-[3rem] shadow-xl shadow-slate-200/50 overflow-hidden">
                {{-- Table Header --}}
                <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-white">
                    <h3 class="text-xl font-black text-slate-800 uppercase italic tracking-tighter">{{ __('តារាងលទ្ធផលសិក្សា') }}</h3>
                    <div class="flex gap-2">
                        <button class="p-2.5 bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"><i class="fas fa-download"></i></button>
                        <button class="p-2.5 bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"><i class="fas fa-print"></i></button>
                    </div>
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-50">
                                <th class="px-10 py-5 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('មុខវិជ្ជា') }}</th>
                                <th class="px-6 py-5 text-center text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ប្រភេទ') }}</th>
                                <th class="px-6 py-5 text-center text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('និទ្ទេស') }}</th>
                                <th class="px-10 py-5 text-center text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ពិន្ទុទទួលបាន') }}</th>
                                <th class="px-10 py-5 text-center text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('កាលបរិច្ឆេទ') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($grades as $grade)
                                @php
                                    $courseName = $grade->course_title_en ?? 'Unknown';
                                    $colorIndex = abs(crc32($courseName)) % count($colorKeys);
                                    $ui = $colorPalette[$colorKeys[$colorIndex]];
                                    $maxScore = $grade->max_score ?? 100;
                                    $percentage = ($maxScore > 0) ? (($grade->score ?? 0) / $maxScore) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-all group">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="{{ $ui['bg'] }} {{ $ui['text'] }} w-12 h-12 rounded-2xl flex items-center justify-center font-black border {{ $ui['border'] }} shadow-sm text-lg">
                                                {{ substr($courseName, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-slate-800 group-hover:{{ $ui['text'] }} transition-colors">{{ $courseName }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">ID: #{{ $grade->id ?? '000' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span class="text-[10px] font-black uppercase px-3 py-1 rounded-full {{ $ui['bg'] }} {{ $ui['text'] }} border {{ $ui['border'] }}">
                                            {{ $grade->assessment_type ?? 'Exam' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-900 text-white text-xs font-black shadow-md">
                                            {{ $grade->grade ?? 'B' }}
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex flex-col items-center min-w-[120px]">
                                            <div class="flex items-baseline gap-1">
                                                <span class="text-lg font-black text-slate-800">{{ number_format($grade->score ?? 0, 1) }}</span>
                                                <span class="text-[10px] font-bold text-slate-300">/ {{ $maxScore }}</span>
                                            </div>
                                            <div class="w-full h-1.5 bg-slate-100 rounded-full mt-2 overflow-hidden max-w-[100px]">
                                                <div class="h-full {{ $ui['accent'] }} rounded-full shadow-sm transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-center">
                                        <span class="text-[11px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-lg">
                                            {{ \Carbon\Carbon::parse($grade->date)->format('d M, Y') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-24 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-slate-50 p-6 rounded-full mb-4">
                                                <i class="fas fa-inbox text-4xl text-slate-200"></i>
                                            </div>
                                            <span class="text-slate-300 font-black uppercase tracking-[0.2em] text-xs">{{ __('មិនទាន់មានទិន្នន័យពិន្ទុ') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="md:hidden p-6 space-y-4 bg-slate-50/50">
                    @foreach ($grades as $grade)
                        @php
                            $courseName = $grade->course_title_en ?? 'Unknown';
                            $colorIndex = abs(crc32($courseName)) % count($colorKeys);
                            $ui = $colorPalette[$colorKeys[$colorIndex]];
                        @endphp
                        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="{{ $ui['bg'] }} {{ $ui['text'] }} w-10 h-10 rounded-xl flex items-center justify-center font-black border {{ $ui['border'] }}">
                                        {{ substr($courseName, 0, 1) }}
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800">{{ $courseName }}</h4>
                                </div>
                                <div class="bg-slate-900 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-sm">
                                    {{ $grade->grade ?? 'A' }}
                                </div>
                            </div>
                            <div class="flex justify-between items-end border-t border-slate-50 pt-4 mt-2">
                                <span class="text-[9px] font-black {{ $ui['text'] }} uppercase tracking-widest bg-white px-2 py-1 rounded-md border {{ $ui['border'] }}">
                                    {{ $grade->assessment_type }}
                                </span>
                                <div class="text-right">
                                    <div class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Score</div>
                                    <div class="flex items-baseline gap-1 justify-end">
                                        <span class="text-xl font-black text-slate-800">{{ number_format($grade->score ?? 0, 1) }}</span>
                                        <span class="text-[10px] font-bold text-slate-300">/{{ $grade->max_score ?? 100 }}</span>
                                    </div>
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