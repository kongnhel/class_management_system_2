<x-app-layout>
    <div class="bg-[#f8fafc] min-h-screen font-['Battambang'] antialiased">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Header Section --}}
            <div class="mb-10 flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="text-center lg:text-left">
                    <h3 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight">
                        ជំរាបសួរ និស្សិត <span class="text-indigo-600">{{ $user->name }}</span>! 👋
                    </h3>
                    <p class="text-gray-500 font-medium mt-1">{{ __('សូមពិនិត្យមើលបច្ចុប្បន្នភាពនៃការសិក្សារបស់អ្នកនៅថ្ងៃនេះ') }}</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    @if(!auth()->user()->telegram_chat_id)
                        <button type="button" onclick="document.getElementById('telegramEntryModal').classList.remove('hidden')" 
                            class="w-full sm:w-auto flex items-center justify-center gap-2 bg-[#0088cc] hover:bg-[#0077b5] text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:scale-105 active:scale-95 text-sm">
                            <i class="fab fa-telegram-plane text-lg"></i>
                            <span>ភ្ជាប់ជាមួយ Telegram</span>
                        </button>
                    @else
                        <div class="w-full sm:w-auto bg-green-50 text-green-600 border border-green-100 px-5 py-3 rounded-2xl font-bold flex items-center justify-center gap-2 text-sm shadow-sm">
                            <i class="fas fa-check-circle text-lg"></i>
                            <span>បានភ្ជាប់ Telegram រួចរាល់</span>
                        </div>
                    @endif

                    <div class="w-full sm:w-auto bg-white text-gray-700 border border-gray-100 px-5 py-3 rounded-2xl font-bold shadow-sm flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-calendar-day text-indigo-500"></i>
                        {{ now()->format('d M, Y') }}
                    </div>
                </div>
            </div>

            {{-- ១. ផ្នែកស្ថិតិសង្ខេប (Quick Stats Cards) --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-12">
                @php
                    $stats = [
                        ['label' => 'កម្រងសំណួរ', 'count' => $upcomingQuizzes->count(), 'icon' => 'fa-stopwatch', 'color' => 'indigo'],
                        ['label' => 'កិច្ចការស្រាវជ្រាវ', 'count' => $upcomingAssignments->count(), 'icon' => 'fa-file-signature', 'color' => 'emerald'],
                        ['label' => 'ការប្រឡង', 'count' => $upcomingExams->count(), 'icon' => 'fa-graduation-cap', 'color' => 'rose'],
                        ['label' => 'ម៉ោងសិក្សាថ្ងៃនេះ', 'count' => $upcomingSchedules->count(), 'icon' => 'fa-book-open', 'color' => 'purple'],
                        ['label' => 'មុខវិជ្ជាសរុប', 'count' => $enrolledCourses->count(), 'icon' => 'fa-layer-group', 'color' => 'blue'],
                    ];
                @endphp

                @foreach($stats as $stat)
                    <div class="bg-white p-5 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                        <div class="w-10 h-10 bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform relative z-10">
                            <i class="fas {{ $stat['icon'] }}"></i>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1 relative z-10">{{ __($stat['label']) }}</p>
                        <h2 class="text-2xl font-black text-gray-900 relative z-10">{{ $stat['count'] }}</h2>
                        <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity">
                             <i class="fas {{ $stat['icon'] }} text-7xl"></i>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- ២. កាលវិភាគសិក្សាលម្អិត & មុខវិជ្ជា (Left Side) --}}
                <div class="lg:col-span-2 space-y-12">
                    
                    {{-- កាលវិភាគថ្ងៃនេះ --}}
                    <section>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1.5 bg-purple-600 rounded-full"></div>
                            <h4 class="text-2xl font-black text-gray-800">{{ __('កាលវិភាគថ្ងៃនេះ') }} <span class="text-gray-400 font-medium text-lg">({{ __($todayName) }})</span></h4>
                        </div>
                        
                        <div class="grid gap-4">
                            @forelse($upcomingSchedules as $schedule)
                                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between group hover:border-purple-200 transition-all gap-4">
                                    <div class="flex items-center gap-5">
                                        <div class="text-center bg-purple-50 px-4 py-2 rounded-2xl border border-purple-100 min-w-[80px]">
                                            <p class="text-sm font-black text-purple-600">{{ $schedule->start_time->format('H:i') }}</p>
                                            <p class="text-[10px] font-bold text-purple-400">ដល់ {{ $schedule->end_time->format('H:i') }}</p>
                                        </div>
                                        <div>
                                            <h5 class="font-black text-gray-800 group-hover:text-purple-600 transition-colors">
                                                {{ $schedule->courseOffering->course->name_km }}
                                            </h5>
                                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                                    <i class="fas fa-door-open text-gray-300"></i> {{ $schedule->room->room_number ?? 'N/A' }}
                                                </span>
                                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                                    <i class="fas fa-user-tie text-gray-300"></i> {{ $schedule->courseOffering->lecturer->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden sm:block">
                                        <i class="fas fa-chevron-right text-gray-200 group-hover:text-purple-300 transition-all transform group-hover:translate-x-1"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-50 rounded-[2.5rem] p-10 text-center border-2 border-dashed border-gray-200">
                                    <p class="text-gray-400 font-bold italic">{{ __('មិនមានកាលវិភាគសម្រាប់ថ្ងៃនេះទេ') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    {{-- កម្មវិធីសិក្សារបស់ខ្ញុំ --}}
                    <section>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1.5 bg-emerald-600 rounded-full"></div>
                            <h4 class="text-2xl font-black text-gray-800">{{ __('កម្មវិធីសិក្សា និងការណែនាំ') }}</h4>
                        </div>

                        @if ($studentProgram)
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-[2.5rem] p-8 text-white mb-8 shadow-xl shadow-emerald-100 flex flex-col md:flex-row justify-between items-center gap-6">
                                <div>
                                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-[0.2em] mb-2">{{ __('កម្មវិធីសិក្សាបច្ចុប្បន្ន') }}</p>
                                    <h5 class="text-2xl font-black">{{ $studentProgram->name_km }}</h5>
                                </div>
                                <div class="bg-white/20 px-6 py-3 rounded-2xl backdrop-blur-md border border-white/30 text-center">
                                    <p class="text-xs opacity-90">ជំនាន់</p>
                                    <p class="text-xl font-black">{{ $user->generation }}</p>
                                </div>
                            </div>

                            @if ($availableCoursesInProgram->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach ($availableCoursesInProgram as $courseOffering)
                                        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                                            <div class="mb-6">
                                                <h6 class="font-black text-gray-800 mb-2 text-lg leading-tight">{{ $courseOffering->course->title_km ?? $courseOffering->course->title_en }}</h6>
                                                <div class="flex items-center gap-2">
                                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded-md">{{ $courseOffering->course->code }}</span>
                                                    <span class="text-xs text-gray-400">|</span>
                                                    <span class="text-xs text-gray-500 italic">{{ $courseOffering->lecturer->name }}</span>
                                                </div>
                                            </div>
                                            <form action="{{ route('student.enroll_self') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="course_offering_id" value="{{ $courseOffering->id }}">
                                                <button class="w-full bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 group">
                                                    <i class="fas fa-plus-circle transition-transform group-hover:rotate-90"></i> {{ __('ចុះឈ្មោះចូលរៀន') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="bg-white p-12 rounded-[2.5rem] border border-dashed border-gray-300 text-center">
                                <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-graduation-cap text-3xl"></i>
                                </div>
                                <p class="text-gray-500 font-bold">{{ __('មិនទាន់មានកម្មវិធីសិក្សា? សូមទាក់ទងរដ្ឋបាល។') }}</p>
                            </div>
                        @endif
                    </section>

                    {{-- មុខវិជ្ជាដែលបានចុះឈ្មោះ --}}
                    <section>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                            <h4 class="text-2xl font-black text-gray-800">{{ __('មុខវិជ្ជាដែលកំពុងសិក្សា') }}</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($enrolledCourses as $course)
                                @php
                                    $enrollment = $course->studentCourseEnrollments->first();
                                    $isLeader = $enrollment ? $enrollment->is_class_leader : false;
                                @endphp
                                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:border-blue-200 transition-all relative group">
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="max-w-[80%]">
                                            <h3 class="font-black text-gray-800 leading-tight text-lg">
                                                {{ $enrollment->courseOffering->course->title_en }}
                                            </h3>
                                            <p class="text-[10px] text-blue-500 uppercase font-black tracking-widest mt-1">{{ $enrollment->courseOffering->academic_year }} • ឆមាស {{ $enrollment->courseOffering->semester }}</p>
                                        </div>
                                        @if($isLeader)
                                            <div class="bg-amber-100 text-amber-600 p-2 rounded-xl" title="Class Leader">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center gap-3 mb-8">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ __('សាស្ត្រាចារ្យ') }}</p>
                                            <p class="text-sm font-bold text-gray-700">{{ $enrollment->courseOffering->lecturer->name }}</p>
                                        </div>
                                    </div>

                                    @if($isLeader)
                                        <a href="{{ route('student.leader.attendance', $course->id) }}" 
                                           class="inline-flex items-center justify-center gap-2 w-full bg-slate-900 text-white px-4 py-3 rounded-xl text-xs font-bold hover:bg-blue-600 transition-all shadow-lg shadow-slate-100">
                                            <i class="fas fa-clipboard-check"></i> {{ __('គ្រប់គ្រងវត្តមាន') }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                {{-- ៣. សេចក្តីប្រកាស & ការជូនដំណឹង (Right Side) --}}
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm sticky top-8">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="h-6 w-1 bg-blue-600 rounded-full"></div>
                                <h4 class="text-xl font-black text-gray-800">{{ __('ព័ត៌មានថ្មីៗ') }}</h4>
                            </div>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-1 rounded-lg">LIVE</span>
                        </div>

                        <div class="space-y-4">
                            @forelse ($combinedFeed as $item)
                                <div id="{{ $item->type }}-{{ $item->id }}" 
                                     class="p-4 rounded-2xl border transition-all {{ $item->is_read ? 'bg-gray-50 border-gray-100 opacity-60' : 'bg-white border-blue-50 shadow-sm' }}">
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center {{ $item->type === 'announcement' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                            <i class="fas {{ $item->type === 'announcement' ? 'fa-thumbtack' : 'fa-bell' }} text-sm"></i>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <div class="flex justify-between items-start mb-1">
                                                <h5 class="text-xs font-black text-gray-800 truncate">{{ $item->title }}</h5>
                                            </div>
                                            <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed mb-2">{{ $item->content }}</p>
                                            
                                            <div class="flex items-center justify-between">
                                                <span class="text-[9px] text-gray-400 font-bold"><i class="far fa-clock mr-1"></i> {{ $item->created_at->diffForHumans() }}</span>
                                                @if(!$item->is_read)
                                                    <button onclick="markAsRead('{{ $item->type }}', '{{ $item->id }}')" 
                                                            class="text-[10px] font-black text-blue-600 uppercase hover:underline">
                                                        {{ __('អានរួច') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                    <i class="fas fa-inbox text-gray-200 text-3xl mb-3"></i>
                                    <p class="text-gray-400 font-bold text-xs">{{ __('មិនទាន់មានព័ត៌មាន') }}</p>
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
                        <p class="text-[10px] text-slate-400 font-bold">តម្លើងការជូនដំណឹងពិន្ទុ</p>
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
                            ចុចលើ <a href="https://t.me/kong_grade_bot" target="_blank" class="font-bold underline text-amber-600">@kong_grade_bot</a> រួចចុច <span class="bg-amber-100 px-2 py-0.5 rounded italic">START</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('student.update_telegram') }}" method="POST">
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

<script>
    function markAsRead(itemType, itemId) {
        const url = (itemType === 'notification')
            ? `{{ route('student.notifications.read', ['id' => 'TEMP_ID']) }}`.replace('TEMP_ID', itemId)
            : `{{ route('student.announcements.read', ['id' => 'TEMP_ID']) }}`.replace('TEMP_ID', itemId);

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: itemId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const itemElement = document.getElementById(`${itemType}-${itemId}`);
                if (itemElement) {
                    const actionArea = itemElement.querySelector('button');
                    if(actionArea) {
                        const newSpan = document.createElement('span');
                        newSpan.className = "text-[10px] text-green-600 font-black py-1 px-2 rounded-lg bg-green-50 border border-green-100";
                        newSpan.innerHTML = `{{ __('បានអាន') }}`;
                        actionArea.replaceWith(newSpan);
                    }
                    itemElement.classList.add('opacity-60');
                }
            } else {
                alert('មានបញ្ហាក្នុងការសម្គាល់ជាបានអានហើយ។');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('មានបញ្ហាក្នុងការភ្ជាប់ទៅម៉ាស៊ីនបម្រើ។');
        });
    }

    function openTelegramModal() {
        document.getElementById('telegramEntryModal').classList.remove('hidden');
    }
    
    function closeTelegramModal() {
        document.getElementById('telegramEntryModal').classList.add('hidden');
    }
</script>
</x-app-layout>