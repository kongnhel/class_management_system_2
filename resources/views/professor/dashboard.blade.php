<x-app-layout>
    {{-- Custom Font & Global Style --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap');
        .font-khmer { font-family: 'Kantumruy Pro', 'Battambang', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        .animate-pulse-slow { animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 font-khmer">
            <div>
                <h2 class="font-black text-3xl text-slate-800 leading-tight">
                    {{ __('ផ្ទាំងគ្រប់គ្រងរបស់លោកគ្រូ/អ្នកគ្រូ') }}
                </h2>
                <p class="mt-1 text-slate-500 italic">{{ __('គ្រប់គ្រងវត្តមាន និងសកម្មភាពបង្រៀនដោយភាពវៃឆ្លាត') }}</p>
            </div>

            {{-- 🛡️ Google Link Status Card --}}
            <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                    <i class="fa-brands fa-google text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm leading-none mb-1">{{ __('សុវត្ថិភាពគណនី') }}</h3>
                    @if(!auth()->user()->google_id)
                        <button onclick="linkWithGoogle()" id="btn-link-google" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 text-[11px] font-black transition-all group">
                            <i class="fa-solid fa-link group-hover:rotate-45 transition-transform"></i> {{ __('ភ្ជាប់ជាមួយ Google ឥឡូវនេះ') }}
                        </button>
                    @else
                        <span class="text-emerald-500 font-bold flex items-center gap-1.5 text-[11px]">
                            <i class="fa-solid fa-circle-check"></i> {{ __('បានភ្ជាប់រួចរាល់') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-[#f8fafc] min-h-screen font-khmer pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Alerts --}}
            @if (session('success'))
                <div class="bg-emerald-500 text-white p-4 rounded-2xl mb-8 shadow-lg shadow-emerald-200 flex items-center animate-in fade-in slide-in-from-top-4 duration-500" role="alert">
                    <div class="bg-white/20 p-2 rounded-xl mr-4">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Welcome Section --}}
            <div class="mb-10">
                <div class="bg-white overflow-hidden shadow-sm rounded-[2.5rem] p-6 md:p-10 border border-slate-100 flex flex-col lg:flex-row items-center justify-between gap-8 relative">
                    <div class="absolute top-0 right-0 opacity-5 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                        <i class="fas fa-graduation-cap text-[200px]"></i>
                    </div>
                    
                    <div class="relative z-10 text-center lg:text-left">
                        <h3 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight mb-3">
                            {{ __('សួស្តី,') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">{{ Auth::user()->name }}</span>! 👋
                        </h3>
                        <p class="text-slate-500 text-lg">{{ __('រីករាយថ្ងៃបង្រៀន! នេះគឺជាកាលវិភាគសម្រាប់') }} <b class="text-slate-800 underline decoration-indigo-200 decoration-4 underline-offset-4">{{ __('ថ្ងៃនេះ') }}</b></p>
                    </div>

                    <div class="flex flex-wrap justify-center gap-4 relative z-10 font-bold">
                        @if(!auth()->user()->telegram_chat_id)
                            <button type="button" onclick="document.getElementById('telegramEntryModal').classList.remove('hidden')" 
                                class="flex items-center gap-2 bg-[#0088cc] hover:bg-[#0077b5] text-white px-6 py-4 rounded-2xl shadow-lg shadow-blue-100 transition-all hover:-translate-y-1 active:scale-95 text-sm">
                                <i class="fab fa-telegram-plane text-xl"></i>
                                <span>{{ __('ភ្ជាប់ Telegram') }}</span>
                            </button>
                        @else
                            <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-600 border border-emerald-100 px-6 py-4 rounded-2xl shadow-sm">
                                <i class="fas fa-check-circle text-xl"></i>
                                <span>{{ __('Telegram រួចរាល់') }}</span>
                            </div>
                        @endif
                        
                        <a href="{{ route('qr.scanner') }}" class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-6 py-4 rounded-2xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 text-sm">
                            <i class="fa-solid fa-qrcode text-xl text-indigo-400"></i>
                            <span>{{ __('ស្កែន QR ចូល PC') }}</span>
                        </a>
                        
                        <div class="inline-flex items-center gap-2 bg-white text-slate-700 border border-slate-200 px-6 py-4 rounded-2xl shadow-sm">
                            <i class="fas fa-calendar-alt text-indigo-500"></i>
                            <span>{{ now()->translatedFormat('d M, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @php
                    $stats = [
                        ['title' => 'ថ្នាក់បង្រៀនថ្ងៃនេះ', 'value' => $todaySchedules->pluck('course_offering_id')->unique()->count(), 'icon' => 'fa-chalkboard-teacher', 'color' => 'from-blue-600 to-indigo-700', 'shadow' => 'shadow-blue-200'],
                        ['title' => 'សិស្សសរុប', 'value' => $totalStudents, 'icon' => 'fa-users', 'color' => 'from-emerald-500 to-teal-600', 'shadow' => 'shadow-emerald-200'],
                        ['title' => 'កិច្ចការដាក់ឱ្យសិស្ស', 'value' => $upcomingAssignments->count(), 'icon' => 'fa-file-signature', 'color' => 'from-amber-500 to-orange-600', 'shadow' => 'shadow-amber-200'],
                        ['title' => 'ការប្រឡង/ឃ្វីស', 'value' => $upcomingExams->count() + $upcomingQuizzes->count(), 'icon' => 'fa-award', 'color' => 'from-rose-500 to-pink-600', 'shadow' => 'shadow-rose-200'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="bg-gradient-to-br {{ $stat['color'] }} text-white p-6 rounded-[2.2rem] shadow-xl {{ $stat['shadow'] }} flex items-center justify-between transition-all duration-300 hover:-translate-y-2 hover:rotate-1 group overflow-hidden relative">
                    <div class="absolute -right-2 -bottom-2 opacity-20 transform scale-150 transition-transform group-hover:scale-[1.7] group-hover:rotate-12">
                        <i class="fas {{ $stat['icon'] }} text-6xl"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-white/80 font-medium mb-1 text-sm">{{ __($stat['title']) }}</p>
                        <p class="text-4xl font-black tracking-tight">{{ $stat['value'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md relative z-10">
                        <i class="fas {{ $stat['icon'] }} text-2xl"></i>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- Left Column: Teaching Schedule --}}
                <div class="lg:col-span-2 space-y-10">
                    <section>
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-2 bg-indigo-600 rounded-full shadow-lg shadow-indigo-200"></div>
                                <h4 class="text-2xl font-black text-slate-800 tracking-tight">{{ __('កាលវិភាគបង្រៀនថ្ងៃនេះ') }}</h4>
                            </div>
                            <span class="bg-indigo-50 text-indigo-600 px-5 py-2 rounded-2xl text-xs font-black border border-indigo-100 uppercase tracking-wider">
                                {{ $todaySchedules->pluck('course_offering_id')->unique()->count() }} {{ __('មុខវិជ្ជា') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @php $groupedSchedules = $todaySchedules->groupBy('course_offering_id'); @endphp

                            @forelse ($groupedSchedules as $offeringId => $schedules)
                                @php
                                    $firstSchedule = $schedules->first();
                                    $courseOffering = $firstSchedule->courseOffering;
                                    $startTime = \Carbon\Carbon::parse($schedules->min('start_time'));
                                    $endTime = \Carbon\Carbon::parse($schedules->max('end_time'));
                                    $isCompletedToday = $schedules->contains('is_completed_today', true);
                                @endphp

                                <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:border-indigo-100 transition-all group flex flex-col h-full relative overflow-hidden">
                                    {{-- Time & Status --}}
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="bg-slate-50 border border-slate-200 px-4 py-2 rounded-2xl flex items-center gap-2 shadow-sm group-hover:bg-white group-hover:border-indigo-200 transition-colors">
                                            <i class="fas fa-clock text-indigo-500 animate-pulse"></i>
                                            <span class="font-black text-slate-700 text-sm">
                                                {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                                            </span>
                                        </div>

                                        @if($isCompletedToday)
                                            <div class="bg-emerald-100 text-emerald-600 p-2.5 rounded-2xl shadow-sm" title="Completed Today">
                                                <i class="fas fa-check-double"></i>
                                            </div>
                                        @else
                                            <div class="relative flex items-center justify-center">
                                                <div class="absolute w-full h-full bg-blue-400 rounded-full animate-ping opacity-25"></div>
                                                <div class="bg-blue-600 text-white p-2.5 rounded-2xl shadow-lg relative z-10">
                                                    <i class="fas fa-radio"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Course Details --}}
                                    <div class="mb-8">
                                        <h3 class="font-black text-xl text-slate-800 line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors mb-4">
                                            {{ $courseOffering->course->name_km ?? ($courseOffering->course->name ?? $courseOffering->course->title_en) }}
                                        </h3>
                                        
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-3">
                                                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-3 py-1 rounded-lg border border-indigo-100 uppercase">
                                                    {{ $courseOffering->course->program->name_km ?? 'បច្ចេកវិទ្យាវិទ្យាសាស្ត្រ' }}
                                                </span>
                                                <span class="text-slate-300">|</span>
                                                <div class="flex items-center gap-1.5 text-sm text-slate-500 font-bold">
                                                    <i class="fas fa-layer-group text-slate-400"></i>
                                                    <span>{{ __('ជំនាន់') }}: <b class="text-slate-800">{{ $courseOffering->generation }}</b></span>
                                                </div>
                                            </div>

                                            <div class="bg-slate-50 rounded-3xl p-4 space-y-3 group-hover:bg-indigo-50/50 transition-colors border border-transparent group-hover:border-indigo-50">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('បន្ទប់សិក្សា & ម៉ោងសិក្សា:') }}</p>
                                                @foreach($schedules as $sched)
                                                    <div class="flex justify-between items-center bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm">
                                                        <span class="text-slate-600 font-bold text-xs">
                                                            {{ __('វេនទី') }} {{ $loop->iteration }}
                                                        </span>
                                                        <span class="text-indigo-700 font-black text-xs px-3 py-1 bg-indigo-50 rounded-lg">
                                                            <i class="fas fa-door-open mr-1.5"></i> {{ $sched->room->room_number ?? 'Online' }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Action Button --}}
                                    <button type="button"
                                        @if($isCompletedToday)
                                            onclick="openAttendanceListOnly({{ $courseOffering->id }})"
                                        @else
                                            onclick="verifyTeacherLocationBeforeScan({{ $courseOffering->id }}, {{ $firstSchedule->id }})"
                                        @endif
                                        id="btn-scan-{{ $courseOffering->id }}"
                                        class="w-full mt-auto py-4 rounded-2xl font-black transition-all flex items-center justify-center gap-3 text-sm uppercase tracking-wider
                                        {{ $isCompletedToday
                                            ? 'bg-white border-2 border-emerald-500 text-emerald-600 hover:bg-emerald-50 shadow-lg shadow-emerald-50'
                                            : 'bg-gradient-to-r from-indigo-600 to-blue-600 text-white shadow-xl shadow-indigo-100 hover:shadow-indigo-300 active:scale-[0.98]'
                                        }}">
                                        @if($isCompletedToday)
                                            <i class="fas fa-clipboard-list text-lg"></i> {{ __('ពិនិត្យវត្តមានឡើងវិញ') }}
                                        @else
                                            <i class="fas fa-qrcode text-lg"></i> {{ __('ចាប់ផ្ដើមស្រង់វត្តមាន (Scan)') }}
                                        @endif
                                    </button>
                                </div>
                            @empty
                                <div class="col-span-full bg-white border-2 border-dashed border-slate-200 rounded-[3rem] p-16 text-center shadow-inner">
                                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                                        <i class="fas fa-mug-hot text-4xl text-slate-300"></i>
                                    </div>
                                    <h5 class="text-slate-800 font-black text-xl mb-2">{{ __('សម្រាកឱ្យបានច្រើន លោកគ្រូ/អ្នកគ្រូ!') }}</h5>
                                    <p class="text-slate-400 font-medium max-w-sm mx-auto">{{ __('មិនមានម៉ោងបង្រៀនសម្រាប់ថ្ងៃនេះទេ។ សូមរៀបចំខ្លួនសម្រាប់ថ្ងៃស្អែក!') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>

                {{-- Right Column: Side Info --}}
                <div class="space-y-10">
                    
                    {{-- Announcements --}}
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <div class="bg-amber-50 p-2.5 rounded-xl">
                                    <i class="fas fa-bullhorn text-amber-500"></i>
                                </div>
                                {{ __('សេចក្តីប្រកាស') }}
                            </h4>
                        </div>

                        <div class="space-y-5">
                            @forelse ($announcements as $announcement)
                                <div class="p-5 bg-slate-50 rounded-[1.8rem] border border-slate-100 relative group transition-all hover:bg-white hover:shadow-lg hover:border-amber-100">
                                    <h5 class="font-black text-slate-800 text-sm leading-snug mb-2 line-clamp-1 group-hover:text-amber-600 transition-colors">
                                        {{ $announcement->title_km ?? ($announcement->title_en ?? 'គ្មានចំណងជើង') }}
                                    </h5>
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $announcement->content_km ?? ($announcement->content_en ?? 'គ្មានខ្លឹមសារ') }}</p>
                                    <div class="flex items-center justify-between border-t border-slate-200/60 pt-4">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter bg-white px-2 py-1 rounded-md shadow-sm border border-slate-100">
                                            <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}
                                        </span>
                                        <i class="fas fa-arrow-right text-[10px] text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                        <i class="fas fa-inbox text-2xl"></i>
                                    </div>
                                    <p class="text-sm text-slate-400 font-bold italic">{{ __('មិនមានសេចក្តីប្រកាសថ្មី') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Upcoming Tasks --}}
                    <div class="bg-slate-900 text-white p-8 rounded-[2.8rem] shadow-2xl relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl transition-transform group-hover:scale-150"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>

                        <h4 class="text-xl font-black mb-8 relative z-10 flex items-center gap-3">
                            <div class="bg-white/10 p-2.5 rounded-xl border border-white/5">
                                <i class="fas fa-tasks text-indigo-400"></i>
                            </div>
                            {{ __('កិច្ចការត្រូវធ្វើ') }}
                        </h4>

                        <div class="space-y-5 relative z-10">
                            @forelse ($upcomingAssignments as $assignment)
                                <a href="{{ route('professor.manage-grades', ['offering_id' => $assignment->course_offering_id]) }}" 
                                   class="block bg-white/5 backdrop-blur-md border border-white/10 p-5 rounded-[1.8rem] hover:bg-white/10 transition-all group/task">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="px-2.5 py-1 rounded-lg bg-indigo-500 text-white text-[9px] font-black uppercase tracking-widest shadow-lg shadow-indigo-900/50">
                                            Assignment
                                        </span>
                                        <p class="text-[10px] text-indigo-300 font-mono font-bold">
                                            <i class="far fa-calendar-alt mr-1"></i> Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M') }}
                                        </p>
                                    </div>
                                    <h5 class="font-bold text-sm text-white group-hover/task:text-indigo-300 transition-colors leading-relaxed">
                                        {{ $assignment->title_km ?? ($assignment->title_en ?? 'គ្មានចំណងជើង') }}
                                    </h5>
                                </a>
                            @empty
                                <div class="text-center py-10 bg-white/5 rounded-[2rem] border border-dashed border-white/10">
                                    <p class="text-sm text-slate-400 font-bold">{{ __('កិច្ចការទាំងអស់ត្រូវបានរួចរាល់!') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Telegram Modal --}}
    <div id="telegramEntryModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md hidden flex items-center justify-center z-[9999] p-4 animate-in fade-in duration-300">
        <div class="bg-white rounded-[3rem] p-8 md:p-10 w-full max-w-md shadow-2xl border border-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#0088cc] to-blue-400"></div>
            
            <div class="flex justify-between items-start mb-8">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-50 rounded-2xl text-[#0088cc] shadow-inner">
                        <i class="fab fa-telegram-plane text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">ភ្ជាប់ Telegram</h3>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">Bot Smart Notification</p>
                    </div>
                </div>
                <button onclick="document.getElementById('telegramEntryModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-slate-50 text-slate-400 transition-all border border-transparent hover:border-slate-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="space-y-4 mb-10">
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex gap-4">
                    <span class="flex-none w-8 h-8 bg-white shadow-sm text-[#0088cc] rounded-xl flex items-center justify-center text-xs font-black border border-slate-100">01</span>
                    <p class="text-xs text-slate-600 leading-relaxed font-bold">
                        ផ្ញើសារទៅកាន់ <a href="https://t.me/userinfobot" target="_blank" class="font-black underline text-blue-600 decoration-2 decoration-blue-200">@userinfobot</a> រួចចម្លងលេខ ID របស់អ្នក។
                    </p>
                </div>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex gap-4">
                    <span class="flex-none w-8 h-8 bg-white shadow-sm text-amber-500 rounded-xl flex items-center justify-center text-xs font-black border border-slate-100">02</span>
                    <p class="text-xs text-slate-600 leading-relaxed font-bold">
                        ចុច <a href="https://t.me/Nmu1_schedule_bot" target="_blank" class="font-black underline text-amber-600 decoration-2 decoration-amber-200">@Nmu1_schedule_bot</a> រួចចុច <span class="text-amber-600 italic">START</span>
                    </p>
                </div>
            </div>
            
            <form action="{{ route('professor.update_telegram') }}" method="POST">
                @csrf
                <div class="mb-8">
                    <label class="block text-sm font-black text-slate-700 mb-4 ml-1">បញ្ចូលលេខ Telegram ID</label>
                    <input type="number" name="telegram_chat_id" required 
                            placeholder="ឧទាហរណ៍៖ 584930211"
                            class="w-full px-6 py-5 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-slate-700 font-mono text-xl text-center tracking-widest shadow-inner">
                </div>
                <div class="flex gap-4 font-black">
                    <button type="button" onclick="document.getElementById('telegramEntryModal').classList.add('hidden')" 
                            class="flex-1 px-4 py-5 bg-slate-100 text-slate-500 rounded-2xl hover:bg-slate-200 transition-colors">
                        បោះបង់
                    </button>
                    <button type="submit" class="flex-[2] px-4 py-5 bg-[#0088cc] text-white rounded-2xl hover:bg-[#0077b5] shadow-xl shadow-blue-100 transition-all transform active:scale-[0.98]">
                        រក្សាទុកទិន្នន័យ
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Livewire Modal --}}
    @livewire('teacher.attendance-modal')

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Firebase Integration --}}
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.app_id') }}"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        window.linkWithGoogle = () => {
            const btn = document.getElementById('btn-link-google');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> កំពុងដំណើរការ...';
            btn.disabled = true;

            signInWithPopup(auth, provider)
                .then((result) => {
                    const user = result.user;
                    fetch('{{ route("user.link-google") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            uid: user.uid,
                            photoURL: user.photoURL
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'linked') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ជោគជ័យ',
                                text: 'គណនី Google ត្រូវបានភ្ជាប់!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => window.location.reload());
                        }
                    });
                })
                .catch((error) => {
                    console.error("Firebase Error:", error.code);
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    Swal.fire('បរាជ័យ', 'មិនអាចភ្ជាប់ Google បានទេ៖ ' + error.message, 'error');
                });
        };
    </script>
<script>
  async function openAttendanceList(courseOfferingId) {
    if (window.Livewire?.dispatch) {
      // Livewire v3
      Livewire.dispatch('openAttendanceModal', { courseOfferingId });
    } else if (window.livewire?.emit) {
      // Livewire v2
      window.livewire.emit('openAttendanceModal', courseOfferingId);
    } else {
      Swal.fire('កំហុស', 'Livewire មិនបាន Load ទេ!', 'error');
    }
  }

  function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
  }

  async function precheckAttendance(courseOfferingId, sessionId) {
    const csrf = getCsrfToken();

    const res = await fetch("{{ route('professor.attendance.precheck') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify({
        course_offering_id: courseOfferingId,
        session_id: sessionId
      })
    });

    const data = await res.json().catch(() => null);

    if (!res.ok) {
      const msg =
        data?.message ||
        (res.status === 419 ? 'CSRF token mismatch (419). សូម Refresh ទំព័រ។' : null) ||
        `Precheck error (${res.status}).`;

      throw new Error(msg);
    }

    return !!data?.checked_in;
  }

  async function verifyLocation(courseOfferingId, sessionId, lat, lng) {
    const csrf = getCsrfToken();

    const res = await fetch("{{ route('professor.verify-location') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify({
        course_offering_id: courseOfferingId,
        session_id: sessionId,
        lat: lat,
        lng: lng
      })
    });

    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); } catch (e) {}

    if (!res.ok) {
      const msg =
        data?.message ||
        data?.error ||
        (res.status === 419 ? 'CSRF token mismatch (419). សូម Refresh ទំព័រ។' : null) ||
        (res.status === 403 ? (data?.message || 'មិនអនុញ្ញាត (403)') : null) ||
        `Server error (${res.status}).`;

      throw new Error(msg);
    }

    return data;
  }

function getAccurateLocation(maxAttempts = 3) {
  return new Promise((resolve, reject) => {

    let attempts = 0;

    function tryGetLocation() {
      attempts++;

      navigator.geolocation.getCurrentPosition(
        (pos) => {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;
          const accuracy = pos.coords.accuracy;

          console.log("GPS Accuracy:", accuracy);

          // Accept only if accuracy < 60 meters
          if (accuracy <= 80) {
            resolve({ lat, lng, accuracy });
          } else if (attempts < maxAttempts) {
            // Try again
            setTimeout(tryGetLocation, 2000);
          } else {
            reject(new Error(`GPS មិនទាន់ត្រឹមត្រូវ។ Accuracy: ${Math.round(accuracy)}m`));
          }
        },
        () => reject(new Error('សូមបើក GPS និងអនុញ្ញាត (Allow Location)!')),
        {
          enableHighAccuracy: true,
          timeout: 15000,
          maximumAge: 0
        }
      );
    }

    tryGetLocation();
  });
}

  async function verifyTeacherLocationBeforeScan(courseOfferingId, sessionId) {
    console.log('courseOfferingId:', courseOfferingId);
    console.log('sessionId:', sessionId);

    if (!sessionId) {
      Swal.fire('កំហុស', 'Session ID មិនត្រឹមត្រូវ (sessionId is missing)', 'error');
      return;
    }

    // 1) Precheck first (NO GPS)
    Swal.fire({
      title: 'កំពុងពិនិត្យ...',
      text: 'កំពុងពិនិត្យថាលោកគ្រូបាន Check-in រួចហើយឬនៅ...',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => Swal.showLoading()
    });

    try {
      const checkedIn = await precheckAttendance(courseOfferingId, sessionId);

      if (checkedIn) {
        Swal.close();
        // ✅ Already checked in → open list directly, no GPS
        await openAttendanceList(courseOfferingId);
        return;
      }

      // 2) Not checked in yet → do GPS
      Swal.update({
        title: 'កំពុងផ្ទៀងផ្ទាត់ទីតាំង',
        text: 'សូមរង់ចាំបន្តិច ដើម្បីប្រាកដថាលោកគ្រូស្ថិតនៅសាលារៀន...'
      });

const { lat, lng, accuracy } = await getAccurateLocation();

      // Optional: reject very inaccurate readings
      if (accuracy && accuracy > 80) {
        Swal.close();
        Swal.fire(
          'GPS មិនទាន់ត្រឹមត្រូវ',
          `សូមរង់ចាំបន្តិច ហើយសាកល្បងម្ដងទៀត។ Accuracy: ${Math.round(accuracy)}m`,
          'warning'
        );
        return;
      }

      // 3) Verify on server
      const data = await verifyLocation(courseOfferingId, sessionId, lat, lng);

      Swal.close();

      if (data?.success) {
        // Disable button after success (optional)
        const btn = document.getElementById(`btn-scan-${courseOfferingId}`);
        if (btn) btn.disabled = true;

        await Swal.fire({
          icon: 'success',
          title: 'បានចុះវត្តមានដោយជោគជ័យ 🎉',
          html: data?.distance
            ? `ចម្ងាយពីសាលា: <b>${data.distance} ម៉ែត្រ</b>`
            : 'ទីតាំងត្រឹមត្រូវ។ អាចចាប់ផ្តើមស្រង់វត្តមានបាន។',
          confirmButtonColor: '#4f46e5',
          confirmButtonText: 'បន្ត'
        });

        await openAttendanceList(courseOfferingId);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'ទីតាំងមិនត្រឹមត្រូវ',
          text: data?.message || 'Unknown error response',
          confirmButtonColor: '#4f46e5'
        });
      }
    } catch (err) {
      Swal.close();
      console.error(err);
      Swal.fire('កំហុស', err.message || 'មានបញ្ហាក្នុងការទាក់ទងទៅ Server!', 'error');
    }
  }
</script>

<script>
  function openAttendanceListOnly(courseOfferingId) {
    if (window.Livewire?.dispatch) {
      Livewire.dispatch('openAttendanceModal', { courseOfferingId });
    } else if (window.livewire?.emit) {
      window.livewire.emit('openAttendanceModal', courseOfferingId);
    } else {
      Swal.fire('កំហុស', 'Livewire មិនបាន Load ទេ!', 'error');
    }
  }
</script>
</x-app-layout>