<x-app-layout>
    {{-- Meta for CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <h3 class="font-bold text-gray-800">សុវត្ថិភាពគណនី</h3>
        <p class="text-sm text-gray-500 mb-4">ភ្ជាប់ជាមួយ Google ដើម្បីចូលប្រើប្រាស់បានលឿនជាងមុន។</p>
        
        @if(!auth()->user()->google_id)
            <button onclick="handleLinkGoogle()" id="btn-link" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100">
                <i class="fa-brands fa-google"></i> ភ្ជាប់ជាមួយ Google ឥឡូវនេះ
            </button>
        @else
            <span class="text-emerald-500 font-bold flex items-center gap-2">
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

                    <a href="{{ route('qr.scanner') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-emerald-100 transition-all text-sm">
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
                                                {{ $schedule->courseOffering->course->name_km ?? $schedule->courseOffering->course->title_en }}
                                            </h5>
                                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-xs text-gray-500">
                                                <span><i class="fas fa-door-open text-gray-300"></i> {{ $schedule->room->room_number ?? 'N/A' }}</span>
                                                <span><i class="fas fa-user-tie text-gray-300"></i> {{ $schedule->courseOffering->lecturer->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-200 group-hover:text-purple-300 transition-all sm:block hidden transform group-hover:translate-x-1"></i>
                                </div>
                            @empty
                                <div class="bg-gray-50 rounded-[2.5rem] p-10 text-center border-2 border-dashed border-gray-200 text-gray-400 italic">{{ __('មិនមានកាលវិភាគសម្រាប់ថ្ងៃនេះទេ') }}</div>
                            @endforelse
                        </div>
                    </section>

                    <section>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                            <h4 class="text-2xl font-black text-gray-800">{{ __('មុខវិជ្ជាដែលកំពុងសិក្សា') }}</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($enrolledCourses as $course)
                                @php $myEnrollment = $course->studentCourseEnrollments->first(); $isLeader = $myEnrollment ? $myEnrollment->is_class_leader : false; @endphp
                                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm relative group overflow-hidden">
                                    <div class="absolute top-6 right-6">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $course->today_status == 'present' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                            {{ $course->today_status == 'present' ? 'វត្តមាន' : 'មិនទាន់កត់ត្រា' }}
                                        </span>
                                    </div>
                                    <h3 class="font-black text-gray-800 text-lg mb-4">{{ $course->course->title_en ?? $course->course->name }}</h3>
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400"><i class="fas fa-user-tie"></i></div>
                                        <p class="text-sm font-bold text-gray-700">{{ $course->lecturer->name }}</p>
                                    </div>
                                    <div class="space-y-2">
                                        <a href="{{ route('student.scan') }}" class="w-full block py-3 text-center rounded-xl font-bold bg-blue-600 text-white shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">ស្កែនវត្តមាន</a>
                                        @if($isLeader)
                                            <a href="{{ route('student.leader.attendance', $course->id) }}" class="w-full block py-3 text-center rounded-xl font-bold bg-slate-800 text-white text-xs hover:bg-slate-700 transition-all tracking-wide">គ្រប់គ្រងវត្តមាន (ប្រធានថ្នាក់)</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
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
                    <p>១. ផ្ញើសារទៅ <a href="https://t.me/userinfobot" target="_blank" class="text-blue-600 font-bold">@userinfobot</a> ដើម្បីយក ID</p>
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

    {{-- 🚀 Firebase SDK for Link Account --}}
<script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyC5QgFzC-Kuudj7mWxLPf58xmoe_feXF3o",
            authDomain: "classmanagementsystem-cd57f.firebaseapp.com",
            projectId: "classmanagementsystem-cd57f",
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        window.handleLinkGoogle = () => {
            signInWithPopup(auth, provider).then((result) => {
                fetch('{{ route("user.link-google") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ uid: result.user.uid })
                }).then(() => location.reload()); // Refresh ដើម្បីប្តូរ Status
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
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: itemId })
            }).then(() => location.reload());
        }
    </script>
</x-app-layout>