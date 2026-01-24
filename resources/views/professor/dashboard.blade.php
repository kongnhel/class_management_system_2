<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight">
            {{ __('ផ្ទាំងគ្រប់គ្រងរបស់លោកគ្រូ/អ្នកគ្រូ') }}
        </h2>
        <p class="mt-1 text-lg text-gray-500">{{ __('ទិដ្ឋភាពទូទៅនៃព័ត៌មានសំខាន់ៗ') }}</p>
    </x-slot>

    <div class="bg-gray-50 min-h-screen font-['Battambang']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
                        {{-- Alerts --}}
         @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 md:p-5 rounded-xl mb-6 shadow-sm flex items-center animate-bounce" role="alert">
                        <i class="fas fa-check-circle mr-3 text-green-500 text-xl"></i>
                        <span class="font-bold text-sm md:text-lg">{{ session('success') }}</span>
                    </div>
                @endif
            {{-- Header Section (Telegram & Welcome) --}}
            <div class="mb-10">
                <div class="bg-white overflow-hidden shadow-xl rounded-[2rem] p-8 border border-gray-100 flex flex-col lg:flex-row items-center justify-between gap-6 transition-transform duration-500 hover:scale-[1.005]">
                    <div>
                        <h3 class="text-2xl font-black text-gray-800 leading-tight mb-2">
                            {{ __('សូមស្វាគមន៍ត្រឡប់មកវិញ,') }} <span class="text-indigo-600">{{ Auth::user()->name }}</span>! 👋
                        </h3>
                        <p class="text-gray-500">{{ __('នេះគឺជាកាលវិភាគបង្រៀនរបស់អ្នកសម្រាប់') }} <b>{{ __('ថ្ងៃនេះ') }}</b></p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        @if(!auth()->user()->telegram_chat_id)
                            <button type="button" onclick="document.getElementById('telegramEntryModal').classList.remove('hidden')" 
                                class="flex items-center justify-center gap-2 bg-[#0088cc] hover:bg-[#0077b5] text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:scale-105 active:scale-95 text-sm">
                                <i class="fab fa-telegram-plane text-xl"></i>
                                <span>{{ __('ភ្ជាប់ Telegram') }}</span>
                            </button>
                        @else
                            <div class="inline-flex items-center justify-center gap-2 bg-green-50 text-green-600 border border-green-100 px-6 py-3 rounded-2xl font-bold shadow-sm">
                                <i class="fas fa-check-circle text-xl"></i>
                                <span>{{ __('Telegram ភ្ជាប់រួចរាល់') }}</span>
                            </div>
                        @endif
                                            <a href="{{ route('qr.scanner') }}" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl font-bold text-sm">
    <i class="fa-solid fa-camera"></i>
    <span>ស្កែន QR ចូល Computer</span>
</a>
                        
                        <div class="inline-flex items-center justify-center gap-2 bg-gray-50 text-gray-700 border border-gray-200 px-6 py-3 rounded-2xl font-bold">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                            <span>{{ now()->format('d M, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {{-- Card 1: Today's Classes --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-6 rounded-[2rem] shadow-lg shadow-blue-200 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl group cursor-pointer">
                    <div>
                        {{-- Count Unique Offerings instead of total sessions --}}
                        <p class="text-sm font-medium text-blue-100 mb-1">{{ __('ថ្នាក់បង្រៀនថ្ងៃនេះ') }}</p>
                        <p class="text-4xl font-black">{{ $todaySchedules->pluck('course_offering_id')->unique()->count() }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                </div>

                {{-- Card 2: Total Students --}}
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white p-6 rounded-[2rem] shadow-lg shadow-emerald-200 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl group cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-emerald-100 mb-1">{{ __('សិស្សសរុប') }}</p>
                        <p class="text-4xl font-black">{{ $totalStudents }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>

                {{-- Card 3: Assignments --}}
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white p-6 rounded-[2rem] shadow-lg shadow-amber-200 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl group cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-amber-100 mb-1">{{ __('កិច្ចការដាក់ឱ្យសិស្ស') }}</p>
                        <p class="text-4xl font-black">{{ $upcomingAssignments->count() }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                </div>

                {{-- Card 4: Exams/Quizzes --}}
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 text-white p-6 rounded-[2rem] shadow-lg shadow-rose-200 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl group cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-rose-100 mb-1">{{ __('ការប្រឡង/ឃ្វុីស') }}</p>
                        <p class="text-4xl font-black">{{ $upcomingExams->count() + $upcomingQuizzes->count() }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left Column: Teaching Schedule & Courses --}}
                <div class="lg:col-span-2 space-y-10">
                    
                    {{-- 2. Today's Sessions List (GROUPED BY COURSE OFFERING) --}}
                    <section>
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-1.5 bg-indigo-600 rounded-full"></div>
                                <h4 class="text-2xl font-black text-gray-800">{{ __('កាលវិភាគបង្រៀនថ្ងៃនេះ') }}</h4>
                            </div>
                            <span class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-xs font-bold border border-indigo-100">
                                {{ $todaySchedules->pluck('course_offering_id')->unique()->count() }} {{ __('មុខវិជ្ជា') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                // Group schedules by course_offering_id to display as one card
                                $groupedSchedules = $todaySchedules->groupBy('course_offering_id');
                            @endphp

                            @forelse ($groupedSchedules as $offeringId => $schedules)
                                @php
                                    $firstSchedule = $schedules->first();
                                    $courseOffering = $firstSchedule->courseOffering;
                                    
                                    // Calculate overall start and end time
                                    $startTime = $schedules->min('start_time');
                                    $endTime = $schedules->max('end_time');
                                    
                                    // Check if ANY session in this group is completed (assuming if one is done, attendance is taken)
                                    // Or you can check if an AttendanceSession exists for today for this offering
                                    $isCompletedToday = $schedules->contains('is_completed_today', true);
                                @endphp

                                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl transition-all group flex flex-col justify-between h-full relative overflow-hidden">
                                    
                                    {{-- Time & Status --}}
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl flex items-center gap-2">
                                            <i class="fas fa-clock text-indigo-500"></i>
                                            <span class="font-bold text-gray-700 text-sm">
                                                {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                                            </span>
                                        </div>

                                        @if($isCompletedToday)
                                            <span class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 shadow-sm" title="Completed Today">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @else
                                            <span class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 shadow-sm animate-pulse" title="Active">
                                                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Course Details --}}
                                    <div class="mb-6">
                                        <h3 class="font-black text-lg text-gray-800 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors mb-2" 
                                            title="{{ $courseOffering->course->name }}">
                                            {{ $courseOffering->course->name ?? $courseOffering->course->title_en }}
                                        </h3>
                                        
                                        <div class="space-y-3">
                                            {{-- Room & Gen --}}
                                            <div class="flex items-center flex-wrap gap-2 text-sm text-gray-600">
                                                <div class="flex items-center gap-1">
                                                    <i class="fas fa-users text-gray-400 w-5 text-center"></i>
                                                    <span>{{ __('ជំនាន់') }}: <b class="text-gray-800">{{ $courseOffering->generation }}</b></span>
                                                </div>
                                                <span class="text-gray-300">|</span>
                                                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2 py-0.5 rounded">
                                                    {{ $courseOffering->course->program->name_km ?? 'Program' }}
                                                </span>
                                            </div>

                                            {{-- List Individual Sessions (Rooms) --}}
                                            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ __('កាលវិភាគលម្អិត:') }}</p>
                                                @foreach($schedules as $sched)
                                                    <div class="flex justify-between items-center text-xs">
                                                        <span class="text-gray-600 font-medium">
                                                            Session {{ $loop->iteration }} ({{ $sched->start_time->format('H:i') }}-{{ $sched->end_time->format('H:i') }})
                                                        </span>
                                                        <span class="text-gray-800 font-bold bg-white px-2 py-0.5 rounded border border-gray-100">
                                                            <i class="fas fa-door-open text-gray-400 mr-1"></i> {{ $sched->room->room_number ?? 'Online' }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Action Button (ONE BUTTON PER OFFERING) --}}
                                    {{-- We pass the courseOfferingId because attendance is usually tracked by offering for the day --}}
                                    {{-- Or pass the first schedule ID if your backend logic requires it --}}
                                    <button onclick="Livewire.dispatch('openAttendanceModal', { courseOfferingId: {{ $courseOffering->id }} })"
                                            class="w-full mt-auto py-3.5 rounded-2xl font-bold transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wide
                                            {{ $isCompletedToday 
                                                ? 'bg-white border-2 border-green-500 text-green-600 hover:bg-green-50' 
                                                : 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-200 hover:to-blue-800 active:scale-95' 
                                            }}">
                                        @if($isCompletedToday)
                                            <i class="fas fa-eye"></i> {{ __('មើលវត្តមាន') }}
                                        @else
                                            <i class="fas fa-qrcode"></i> {{ __('ចាប់ផ្ដើមស្រង់ (Scan)') }}
                                        @endif
                                    </button>
                                </div>
                            @empty
                                <div class="col-span-full bg-white border-2 border-dashed border-gray-200 rounded-[2rem] p-12 text-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-gray-300">
                                        <i class="fas fa-mug-hot text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold text-lg">{{ __('មិនមានម៉ោងបង្រៀនសម្រាប់ថ្ងៃនេះទេ។') }}</p>
                                    <p class="text-sm text-gray-400 mt-2">{{ __('លោកគ្រូអាចសម្រាក ឬរៀបចំមេរៀនសម្រាប់ថ្ងៃស្អែក!') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>

                {{-- Right Column: Announcements & Upcoming Tasks --}}
                <div class="space-y-8">
                    
                    {{-- Announcements --}}
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-black text-gray-800 flex items-center gap-2">
                                <i class="fas fa-bullhorn text-yellow-500 bg-yellow-50 p-2 rounded-xl"></i>
                                {{ __('សេចក្តីប្រកាស') }}
                            </h4>
                        </div>

                        <div class="space-y-4">
                            @forelse ($announcements as $announcement)
                                <div id="announcement-{{ $announcement->id }}" class="p-4 bg-gray-50 rounded-2xl border border-gray-100 relative group transition-all hover:bg-white hover:shadow-md">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2">
                                            {{ $announcement->title_km ?? $announcement->title_en }}
                                        </h5>
                                    </div>
                                    <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $announcement->content_km ?? $announcement->content_en }}</p>
                                    <div class="flex items-center justify-between border-t border-gray-200 pt-3">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <p class="text-sm">{{ __('មិនមានសេចក្តីប្រកាសថ្មីទេ។') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Upcoming Tasks --}}
                    <div class="bg-indigo-900 text-white p-6 rounded-[2.5rem] shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-500/30 rounded-full blur-xl -ml-5 -mb-5"></div>

                        <h4 class="text-xl font-black mb-6 relative z-10 flex items-center gap-2">
                            <i class="fas fa-tasks bg-white/20 p-2 rounded-xl"></i>
                            {{ __('កិច្ចការត្រូវធ្វើ') }}
                        </h4>

                        <div class="space-y-4 relative z-10">
                            @forelse ($upcomingAssignments as $assignment)
                                <a href="{{ route('professor.manage-grades', ['offering_id' => $assignment->course_offering_id]) }}" 
                                   class="block bg-white/10 backdrop-blur-md border border-white/10 p-4 rounded-2xl hover:bg-white/20 transition-all group">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 text-[10px] font-bold uppercase border border-amber-500/30">
                                            Homework
                                        </span>
                                        <p class="text-xs text-indigo-200 font-mono">
                                            Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M') }}
                                        </p>
                                    </div>
                                    <h5 class="font-bold text-sm text-white mb-1 group-hover:text-amber-300 transition-colors">
                                        {{ $assignment->title_km ?? $assignment->title_en }}
                                    </h5>
                                </a>
                            @empty
                                <div class="text-center py-6 text-indigo-300 bg-white/5 rounded-2xl border border-dashed border-white/10">
                                    <p class="text-sm">{{ __('មិនមានកិច្ចការជិតដល់ទេ។') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Telegram Modal --}}
    <div id="telegramEntryModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[9999] p-4">
        <div class="bg-white rounded-[2.5rem] p-8 w-full max-w-md shadow-2xl transform transition-all border border-slate-100">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 rounded-2xl text-[#0088cc]">
                        <i class="fab fa-telegram-plane text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800">ភ្ជាប់ Telegram</h3>
                        <p class="text-[10px] text-slate-400 font-bold">ទទួលការជូនដំណឹងកាលវិភាគ</p>
                    </div>
                </div>
                <button onclick="document.getElementById('telegramEntryModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-50 text-slate-400 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="space-y-4 mb-8">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 group hover:border-blue-200 transition-all">
                    <div class="flex gap-4">
                        <span class="flex-none w-8 h-8 bg-white shadow-sm text-blue-600 rounded-lg flex items-center justify-center text-xs font-black">០១</span>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            <span class="font-bold text-slate-800 block mb-1">យកលេខសម្គាល់ (Chat ID):</span>
                            ផ្ញើសារទៅកាន់ <a href="https://t.me/userinfobot" target="_blank" class="font-bold underline text-blue-600">@userinfobot</a> រួចចម្លងលេខ ID របស់អ្នក។
                        </p>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 group hover:border-amber-200 transition-all">
                    <div class="flex gap-4">
                        <span class="flex-none w-8 h-8 bg-white shadow-sm text-amber-500 rounded-lg flex items-center justify-center text-xs font-black">០២</span>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            <span class="font-bold text-slate-800 block mb-1">បើកដំណើរការ Bot:</span>
                            ចុចលើ <a href="https://t.me/Nmu1_schedule_bot" target="_blank" class="font-bold underline text-amber-600">@Nmu1_schedule_bot</a> រួចចុច <span class="bg-amber-100 px-2 py-0.5 rounded italic">START</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('professor.update_telegram') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-black text-slate-700 mb-3">បញ្ចូលលេខ Telegram ID របស់អ្នក</label>
                    <input type="number" name="telegram_chat_id" required 
                           placeholder="ឧទាហរណ៍៖ 584930211"
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition text-slate-700 font-mono text-lg">
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('telegramEntryModal').classList.add('hidden')" 
                            class="flex-1 px-4 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">
                        បោះបង់
                    </button>
                    <button type="submit" class="flex-[2] px-4 py-4 bg-[#0088cc] text-white rounded-2xl font-bold hover:bg-[#0077b5] shadow-lg shadow-blue-100 transition transform active:scale-95">
                        រក្សាទុកទិន្នន័យ
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Livewire Modal --}}
    @livewire('teacher.attendance-modal')
</x-app-layout>