<x-app-layout>
    {{-- Meta for CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="bg-[#f8fafc] min-h-screen font-['Battambang'] antialiased">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Header Section --}}
            <div class="mb-8 flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="text-center lg:text-left">
                    <h3 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight">
                        ជំរាបសួរ និស្សិត <span class="text-indigo-600">{{ auth()->user()->name }}</span>! 👋
                    </h3>
                    <p class="text-gray-500 font-medium mt-1 mb-6">{{ __('សូមពិនិត្យមើលបច្ចុប្បន្នភាពនៃការសិក្សារបស់អ្នកនៅថ្ងៃនេះ') }}</p>
                    
                    {{-- 🛡️ Google Link Status Card --}}
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 inline-block w-full sm:w-auto">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-blue-600">
                                <i class="fa-brands fa-google text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">សុវត្ថិភាពគណនី</h3>
                                <p class="text-[10px] text-gray-500 mb-2">ភ្ជាប់ជាមួយ Google ដើម្បីចូលបានលឿន</p>
                                
                                @if(!auth()->user()->google_id)
                                    <button onclick="linkWithGoogle()" id="btn-link-google" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 text-xs font-bold transition-all">
                                        <i class="fa-solid fa-link"></i> ភ្ជាប់ជាមួយ Google ឥឡូវនេះ
                                    </button>
                                @else
                                    <span class="text-emerald-500 font-bold flex items-center gap-2 text-xs">
                                        <i class="fa-solid fa-circle-check"></i> បានភ្ជាប់រួចរាល់
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    @if(!auth()->user()->telegram_chat_id)
                        <button type="button" onclick="document.getElementById('telegramEntryModal').classList.remove('hidden')" 
                            class="w-full sm:w-auto flex items-center justify-center gap-2 bg-[#0088cc] hover:bg-[#0077b5] text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:scale-105 text-sm">
                            <i class="fab fa-telegram-plane text-lg"></i>
                            <span>ភ្ជាប់ជាមួយ Telegram</span>
                        </button>
                    @else
                        <div class="w-full sm:w-auto bg-green-50 text-green-600 border border-green-100 px-5 py-3 rounded-2xl font-bold flex items-center justify-center gap-2 text-sm shadow-sm">
                            <i class="fas fa-check-circle text-lg"></i>
                            <span>បានភ្ជាប់ Telegram រួចរាល់</span>
                        </div>
                    @endif

                    <a href="{{ route('qr.scanner') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg text-sm transition-all">
                        <i class="fa-solid fa-camera"></i>
                        <span>ស្កែន QR ចូល PC</span>
                    </a>

                    <div class="w-full sm:w-auto bg-white text-gray-700 border border-gray-100 px-5 py-3 rounded-2xl font-bold shadow-sm flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-calendar-day text-indigo-500"></i>
                        {{ now()->format('d M, Y') }}
                    </div>
                </div>
            </div>

            {{-- ១. ស្ថិតិវត្តមាន --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-4 rounded-2xl border border-green-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl"><i class="fas fa-user-check"></i></div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">វត្តមាន</p>
                        <h4 class="text-2xl font-black text-gray-800">{{ $totalPresent ?? 0 }}</h4>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-red-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl"><i class="fas fa-user-times"></i></div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">អវត្តមាន</p>
                        <h4 class="text-2xl font-black text-gray-800">{{ $totalAbsent ?? 0 }}</h4>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="fas fa-file-contract"></i></div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">ច្បាប់</p>
                        <h4 class="text-2xl font-black text-gray-800">{{ $totalPermission ?? 0 }}</h4>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-yellow-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl"><i class="fas fa-clock"></i></div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">យឺត</p>
                        <h4 class="text-2xl font-black text-gray-800">{{ $totalLate ?? 0 }}</h4>
                    </div>
                </div>
            </div>

            {{-- ២. ស្ថិតិទូទៅ --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-12">
                @php
                    $stats = [
                        ['label' => 'កម្រងសំណួរ', 'count' => $upcomingQuizzes->count(), 'icon' => 'fa-stopwatch', 'color' => 'indigo'],
                        ['label' => 'កិច្ចការ', 'count' => $upcomingAssignments->count(), 'icon' => 'fa-file-signature', 'color' => 'emerald'],
                        ['label' => 'ការប្រឡង', 'count' => $upcomingExams->count(), 'icon' => 'fa-graduation-cap', 'color' => 'rose'],
                        ['label' => 'ម៉ោងសិក្សា', 'count' => $upcomingSchedules->count(), 'icon' => 'fa-book-open', 'color' => 'purple'],
                        ['label' => 'មុខវិជ្ជា', 'count' => $enrolledCourses->count(), 'icon' => 'fa-layer-group', 'color' => 'blue'],
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
                {{-- ផ្នែកខាងឆ្វេង --}}
                <div class="lg:col-span-2 space-y-12">
                    {{-- កាលវិភាគថ្ងៃនេះ --}}
                    <section>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1.5 bg-purple-600 rounded-full"></div>
                            <h4 class="text-2xl font-black text-gray-800">{{ __('កាលវិភាគថ្ងៃនេះ') }}</h4>
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
                                                {{ $schedule->courseOffering->course->name_km ?? $schedule->courseOffering->course->title_en }}
                                            </h5>
                                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-xs text-gray-500">
                                                <span><i class="fas fa-door-open text-gray-300"></i> {{ $schedule->room->room_number ?? 'N/A' }}</span>
                                                <span><i class="fas fa-user-tie text-gray-300"></i> {{ $schedule->courseOffering->lecturer->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-50 rounded-[2.5rem] p-10 text-center border-2 border-dashed border-gray-200 text-gray-400 italic">{{ __('មិនមានកាលវិភាគសម្រាប់ថ្ងៃនេះទេ') }}</div>
                            @endforelse
                        </div>
                    </section>
                                {{-- មុខវិជ្ជាដែលកំពុងសិក្សា (Enrolled Courses) --}}
                    <section>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                            <h4 class="text-2xl font-black text-gray-800">{{ __('មុខវិជ្ជាដែលកំពុងសិក្សា') }}</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($enrolledCourses as $course)
                                @php
                                    // រកមើលថាសិស្សជាប្រធានថ្នាក់ឬអត់
                                    // ដោយសារយើង load relation 'studentCourseEnrollments' ដែល filter តែសិស្សម្នាក់នេះ
                                    // ដូច្នេះយកធាតុទី 1 មកឆែក
                                    $myEnrollment = $course->studentCourseEnrollments->first(); 
                                    $isLeader = $myEnrollment ? $myEnrollment->is_class_leader : false;
                                @endphp

                                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:border-blue-200 transition-all relative group overflow-hidden">
                                    
                                    {{-- 🔥 STATUS BADGE (បង្ហាញនៅជ្រុង) 🔥 --}}
                                    <div class="absolute top-6 right-6 z-10">
                                        @if($course->today_status == 'present')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 flex items-center gap-1 shadow-sm">
                                                <i class="fas fa-check-circle"></i> {{ __('វត្តមាន') }}
                                            </span>
                                        @elseif($course->today_status == 'absent')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200 shadow-sm">
                                                {{ __('អវត្តមាន') }}
                                            </span>
                                        @elseif($course->today_status == 'late')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 shadow-sm">
                                                {{ __('យឺត') }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-400 border border-gray-100">
                                                {{ __('មិនទាន់កត់ត្រា') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col justify-between h-full">
                                        <div class="mb-6 max-w-[70%]">
                                            <h3 class="font-black text-gray-800 leading-tight text-lg mb-1">
                                                {{ $course->course->title_en ?? $course->course->name }}
                                            </h3>
                                            <p class="text-[10px] text-blue-500 uppercase font-black tracking-widest">
                                                {{ $course->academic_year }} • ឆមាស {{ $course->semester }}
                                            </p>
                                        </div>
                                        
                                        <div class="flex items-center gap-3 mb-6">
                                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 border border-gray-100">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ __('សាស្ត្រាចារ្យ') }}</p>
                                                <p class="text-sm font-bold text-gray-700">{{ $course->lecturer->name }}</p>
                                            </div>
                                        </div>

                                        {{-- ប៊ូតុងចូលស្កែន ឬ មើលវត្តមាន --}}
                                        <div class="flex flex-col gap-2">
                                            @if($course->today_status == 'present')
                                                <button disabled class="w-full py-3 rounded-xl font-bold bg-green-50 text-green-600 cursor-default flex items-center justify-center gap-2">
                                                    <i class="fas fa-check"></i> {{ __('បានស្កែនរួចរាល់') }}
                                                </button>
                                            @else
                                                <a href="{{ route('student.scan') }}" class="w-full py-3 rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all flex items-center justify-center gap-2">
                                                    <i class="fas fa-qrcode"></i> {{ __('ស្កែនវត្តមាន') }}
                                                </a>
                                            @endif

                                            @if($isLeader)
                                                <a href="{{ route('student.leader.attendance', $course->id) }}" 
                                                   class="w-full bg-slate-800 text-white px-4 py-3 rounded-xl text-xs font-bold hover:bg-slate-700 transition-all flex items-center justify-center gap-2">
                                                    <i class="fas fa-clipboard-check"></i> {{ __('គ្រប់គ្រងវត្តមាន (ប្រធានថ្នាក់)') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>


                                    {{-- កម្មវិធីសិក្សា (Curriculum) --}}
                    <section>
                         <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1.5 bg-emerald-600 rounded-full"></div>
                            <h4 class="text-2xl font-black text-gray-800">{{ __('កម្មវិធីសិក្សា') }}</h4>
                        </div>
                         @if ($studentProgram)
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-[2.5rem] p-8 text-white shadow-xl shadow-emerald-100 flex flex-col md:flex-row justify-between items-center gap-6">
                                <div>
                                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-[0.2em] mb-2">{{ __('កម្មវិធីសិក្សាបច្ចុប្បន្ន') }}</p>
                                    <h5 class="text-2xl font-black">{{ $studentProgram->name_km }}</h5>
                                </div>
                                <div class="bg-white/20 px-6 py-3 rounded-2xl backdrop-blur-md border border-white/30 text-center">
                                    <p class="text-xs opacity-90">ជំនាន់</p>
                                    <p class="text-xl font-black">{{ $user->generation }}</p>
                                </div>
                            </div>
                        @endif
                    </section>
                                            @if ($studentProgram)
    

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
                </div>

                {{-- ផ្នែកខាងស្តាំ --}}
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm sticky top-8">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="text-xl font-black text-gray-800">{{ __('ព័ត៌មានថ្មីៗ') }}</h4>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-1 rounded-lg">LIVE</span>
                        </div>
                        <div class="space-y-4">
                            @forelse ($combinedFeed as $item)
                                <div id="{{ $item->type }}-{{ $item->id }}" class="p-5 rounded-[2rem] border transition-all {{ $item->is_read ? 'opacity-60 bg-gray-50' : 'bg-white border-blue-100 shadow-md' }}">
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center {{ $item->type === 'announcement' ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-100 text-indigo-600' }}">
                                            <i class="fas {{ $item->type === 'announcement' ? 'fa-bullhorn' : 'fa-bell' }}"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <h5 class="text-sm font-black text-gray-800 truncate">{{ $item->title }}</h5>
                                            <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $item->content }}</p>
                                            @if(!$item->is_read)
                                                <button onclick="markAsRead('{{ $item->type }}', '{{ $item->id }}')" class="mt-2 text-[10px] font-black text-blue-600 uppercase">{{ __('អានរួច') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 text-xs italic">{{ __('មិនមានព័ត៌មានថ្មី') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Telegram Modal --}}
    <div id="telegramEntryModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[9999] p-4">
        <div class="bg-white rounded-[2.5rem] p-8 w-full max-w-md border border-slate-100 shadow-2xl">
            <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-3">
                <i class="fab fa-telegram-plane text-blue-500"></i> ភ្ជាប់ Telegram
            </h3>
            <form action="{{ route('student.update_telegram') }}" method="POST">
                @csrf
                <div class="mb-6 text-xs text-slate-500 leading-relaxed bg-slate-50 p-4 rounded-2xl">
                    <p>១. ផ្ញើសារទៅ <a href="https://t.me/userinfobot" target="_blank" class="text-blue-600 font-bold">@userinfobot</a></p>
                    <p class="mt-1">២. ចុច START លើ <a href="https://t.me/kong_grade_bot" target="_blank" class="text-blue-600 font-bold">@kong_grade_bot</a></p>
                </div>
                <input type="number" name="telegram_chat_id" required placeholder="បញ្ចូលលេខ Chat ID" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl mb-4 focus:ring-4 focus:ring-blue-500/10 outline-none">
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('telegramEntryModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-500">បោះបង់</button>
                    <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-bold">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>

{{-- 🚀 ផ្នែក Firebase SDK សម្រាប់ Dashboard --}}
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

    const firebaseConfig = {
        apiKey: "AIzaSyC5QgFzC-Kuudj7mWxLPf58xmoe_feXF3o",
        authDomain: "classmanagementsystem-cd57f.firebaseapp.com",
        projectId: "classmanagementsystem-cd57f",
        storageBucket: "classmanagementsystem-cd57f.firebasestorage.app",
        messagingSenderId: "171013327760",
        appId: "1:171013327760:web:d00df5782c6c78f4c64115"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const provider = new GoogleAuthProvider();

    // បង្កើត Function ឱ្យត្រូវនឹងឈ្មោះ onclick="linkWithGoogle()" ក្នុងប៊ូតុងបង
    window.linkWithGoogle = () => {
        signInWithPopup(auth, provider)
            .then((result) => {
                const user = result.user;
                
                // ផ្ញើទិន្នន័យទៅរក្សាទុកក្នុង Database របស់ Laravel
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
                        // បើរក្សាទុកក្នុង Database ជាប់ វានឹង Refresh ទំព័រ រួចបាត់ប៊ូតុងភ្ជាប់
                        window.location.reload();
                    }
                });
            })
            .catch((error) => {
                console.error("Firebase Error:", error.code);
                if(error.code === 'auth/unauthorized-domain') {
                    alert("Error: បងត្រូវបន្ថែម domain 127.0.0.1 ក្នុង Firebase Console ជាមុនសិន!");
                }
            });
    };
</script>

    {{-- Notification Script --}}
    <script>
        function markAsRead(itemType, itemId) {
            const url = itemType === 'notification' 
                ? `{{ route('student.notifications.read', ':id') }}`.replace(':id', itemId)
                : `{{ route('student.announcements.read', ':id') }}`.replace(':id', itemId);

            fetch(url, {
                method: 'PATCH',
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify({ id: itemId })
            }).then(() => location.reload());
        }
    </script>
</x-app-layout>