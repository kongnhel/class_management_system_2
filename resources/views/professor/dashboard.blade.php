<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight">
            {{ __('ផ្ទាំងគ្រប់គ្រងរបស់លោកគ្រូ/អ្នកគ្រូ') }}
        </h2>
        <p class="mt-1 text-lg text-gray-500">{{ __('ទិដ្ឋភាពទូទៅនៃព័ត៌មានសំខាន់ៗ') }}</p>
    </x-slot>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-full">
            <div class="bg-white overflow-hidden shadow-2xl p-8 lg:p-12 border border-gray-100 transition-transform duration-500 ease-in-out transform hover:scale-[1.005]">
                                <div class="mb-8">
                    @if(!auth()->user()->telegram_chat_id)
                        <button type="button" onclick="document.getElementById('telegramEntryModal').classList.remove('hidden')" 
                            class="flex items-center gap-3 bg-[#0088cc] hover:bg-[#0077b5] text-white px-6 py-3 rounded-2xl font-bold shadow-xl shadow-blue-100 transition-all transform hover:scale-105">
                            <i class="fab fa-telegram-plane text-xl"></i>
                            <span>{{ __('ភ្ជាប់ជាមួយ Telegram សម្រាប់ទទួលការជូនដំណឹងពីកាលវិភាគបង្រៀន') }}</span>
                        </button>
                    @else
                        <div class="inline-flex items-center gap-3 bg-green-50 text-green-600 border border-green-100 px-6 py-3 rounded-2xl font-bold shadow-sm">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span>{{ __('បានភ្ជាប់ Telegram Bot រួចរាល់') }}</span>
                        </div>
                    @endif
                    {{-- ផ្នែកបង្ហាញមុខវិជ្ជាដែលកំពុងបង្រៀន --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    @foreach($courseOfferings as $offering) 
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all flex flex-col justify-between h-full">
            
            {{-- ផ្នែកខាងលើ៖ ឈ្មោះ និង Status --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 line-clamp-1" title="{{ $offering->course->name }}">
                        {{ $offering->course->name ?? $offering->course->title_en ?? 'N/A' }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ __('ជំនាន់') }}: <span class="font-medium text-gray-700">{{ $offering->generation }}</span> 
                        <span class="mx-1">|</span> 
                        <span class="text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded text-[11px]">
                            {{ $offering->course->program->name_km ?? $offering->course->program->name_en ?? 'N/A' }}
                        </span>
                        {{-- {{ __('បន្ទប់') }}: <span class="font-medium text-gray-700">{{ $offering->room->room_number ?? $offering->room->room_number ?? 'N/A' }}</span> --}}
                    </p>
                </div>

                {{-- 🔥 LOGIC ប្តូរ STATUS BADGE 🔥 --}}
                @if($offering->is_completed_today)
                    {{-- 🟢 បើស្រង់រួច (Completed) --}}
                    <span class="shrink-0 inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 shadow-sm">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('រួចរាល់') }}
                    </span>
                @else
                    {{-- 🔵 បើមិនទាន់ស្រង់ (Active) --}}
                    <span class="shrink-0 inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        {{ __('Active') }}
                    </span>
                @endif
            </div>
            
            {{-- ប៊ូតុងបើក QR Code Modal --}}
            <button onclick="Livewire.dispatch('openAttendanceModal', { courseOfferingId: {{ $offering->id }} })"
                    class="w-full mt-auto py-3 rounded-xl font-medium transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform active:scale-95
                    {{ $offering->is_completed_today 
                        ? 'bg-white border-2 border-green-500 text-green-600 hover:bg-green-50' /* បើស្រង់រួច ចេញប៊ូតុងស */
                        : 'bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 shadow-blue-200' /* បើមិនទាន់ស្រង់ ចេញប៊ូតុងខៀវ */
                    }}">
                
                @if($offering->is_completed_today)
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ __('មើលវត្តមាន') }}
                @else
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    {{ __('ចាប់ផ្ដើមស្រង់') }}
                @endif
            </button>
        </div>
    @endforeach
</div>

{{-- ដាក់ Livewire Modal នៅខាងក្រោមគេបង្អស់ --}}
@livewire('teacher.attendance-modal')

                    <div id="telegramEntryModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-start justify-center z-[9999] p-4">
                        <div class="bg-white rounded-[32px] p-8 w-full max-w-md shadow-2xl border border-slate-100 mt-10">
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 bg-blue-100 rounded-2xl text-[#0088cc]">
                                        <i class="fab fa-telegram-plane text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800">{{ __('ភ្ជាប់តេឡេក្រាម') }}</h3>
                                </div>
                                <button onclick="document.getElementById('telegramEntryModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition">
                                    <i class="fas fa-times text-2xl"></i>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 text-xs">
                                    <p class="text-blue-700 leading-relaxed">
                                        <strong>១. {{ __('យកលេខសម្គាល់ (ID):') }}</strong> {{ __('ផ្ញើសារទៅកាន់') }} 
                                        <a href="https://t.me/userinfobot" target="_blank" class="font-bold underline text-blue-800">@userinfobot</a>
                                    </p>
                                </div>
                                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100 text-xs">
                                    <p class="text-amber-700 leading-relaxed">
                                        <strong>២. {{ __('បើកដំណើរការ Bot:') }}</strong> {{ __('ចុចលើ') }} 
                                        <a href="https://t.me/Nmu1_schedule_bot" target="_blank" class="font-bold underline text-amber-800">@Nmu1_schedule_bot</a> {{ __('រួចចុច "START"') }}
                                    </p>
                                </div>
                                
                                <form action="{{ route('professor.update_telegram') }}" method="POST">
                                    @csrf
                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('បញ្ចូលលេខ Telegram ID របស់អ្នក') }}</label>
                                        <input type="number" name="telegram_chat_id" required placeholder="584930211"
                                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-lg">
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="document.getElementById('telegramEntryModal').classList.add('hidden')" class="flex-1 px-4 py-4 bg-slate-100 rounded-2xl font-bold">{{ __('បោះបង់') }}</button>
                                        <button type="submit" class="flex-[2] px-4 py-4 bg-[#0088cc] text-white rounded-2xl font-bold hover:bg-[#0077b5]">{{ __('រក្សាទុកទិន្នន័យ') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('មុខវិជ្ជាខ្ញុំបង្រៀនសរុប') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $courseOfferings->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2h2V1a1 1 0 012 0v2h2a2 2 0 012 2v2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2H5a2 2 0 01-2-2V9a2 2 0 012-2h2V5zm3 2h2a1 1 0 100-2H7v2z" clip-rule="evenodd"></path></svg>
                    </div>

                    <div class="bg-gradient-to-br from-green-600 to-green-700 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('សិស្សសរុប (ក្នុងមុខវិជ្ជាខ្ញុំ)') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $totalStudents }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>

                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('កិច្ចការស្រាវជ្រាវដែលបានផ្តល់') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $upcomingAssignments->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-orange-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2a1 1 0 011-1h1.586l3 3H7a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path></svg>
                    </div>

                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('ឃ្វុីសដែលបានផ្តល់') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $upcomingQuizzes->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-orange-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2a1 1 0 011-1h1.586l3 3H7a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path></svg>
                    </div>

                    <div class="bg-gradient-to-br from-red-600 to-red-700 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('ការប្រលងដែលបានផ្តល់') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $upcomingExams->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-red-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>



                



                   <!-- Upcoming Lists Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <!-- Upcoming Assignments List -->
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-inner border border-gray-100">
                        <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                            {{ __('កិច្ចការស្រាវជ្រាវដែលបានផ្តល់') }}
                        </h4>
                        @forelse ($upcomingAssignments as $assignment)
                            <a href="{{ route('professor.manage-grades', ['offering_id' => $assignment->course_offering_id]) }}" class="block p-4 bg-white rounded-xl shadow-md border border-gray-100 mb-4 transition-all duration-300 transform hover:scale-[1.01] hover:shadow-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-blue-50 text-blue-600 rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2h2V1a1 1 0 012 0v2h2a2 2 0 012 2v2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2H5a2 2 0 01-2-2V9a2 2 0 012-2h2V5zm3 2h2a1 1 0 100-2H7v2z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="font-bold text-gray-800">{{ $assignment->title_km ?? $assignment->title_en }}</p>
                                        <p class="text-sm text-gray-500">{{ $assignment->courseOffering->course->title_km ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ __('ផុតកំណត់:') }} <span class="font-semibold">{{ \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d H:i A') }}</span>
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6 text-gray-500">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m4 4v10m0 0l-8-4m8 4l-8-4"></path></svg>
                                <p>{{ __('មិនមានកិច្ចការផ្ទះជិតដល់ថ្ងៃកំណត់នៅឡើយទេ។') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Upcoming Exams List -->
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-inner border border-gray-100">
                        <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 9a1 1 0 000 2h4.586l1.293 1.293a1 1 0 001.414 0l2-2a1 1 0 000-1.414l-2-2a1 1 0 00-1.414 0L9.586 9H5z" clip-rule="evenodd"></path></svg>
                            {{ __('ការប្រលងដែលបានផ្តល់') }}
                        </h4>
                        @forelse ($upcomingExams as $exam)
                            <a href="{{ route('professor.manage-grades', ['offering_id' => $exam->course_offering_id]) }}" class="block p-4 bg-white rounded-xl shadow-md border border-gray-100 mb-4 transition-all duration-300 transform hover:scale-[1.01] hover:shadow-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-red-50 text-red-600 rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 9a1 1 0 000 2h4.586l1.293 1.293a1 1 0 001.414 0l2-2a1 1 0 000-1.414l-2-2a1 1 0 00-1.414 0L9.586 9H5z"></path></svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="font-bold text-gray-800">{{ $exam->title_km ?? $exam->title_en }}</p>
                                        <p class="text-sm text-gray-500">{{ $exam->courseOffering->course->title_km ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ __('ថ្ងៃទី:') }} <span class="font-semibold">{{ \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d') }}</span> {{ __('ម៉ោង:') }} <span class="font-semibold">{{ \Carbon\Carbon::parse($exam->exam_date)->format('H:i A') }}</span>
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6 text-gray-500">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p>{{ __('មិនមានការប្រលងជិតដល់នៅឡើយទេ។') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>








                <div class="mb-10">
                    <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('កាលវិភាគបង្រៀនថ្ងៃនេះ') }} 
                        <span class="ml-3 text-sm font-normal text-gray-400 bg-gray-100 px-3 py-1 rounded-full">{{ now()->format('d-M-Y') }}</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($todaySchedules as $schedule)
                            <div class="bg-white p-6 rounded-2xl shadow-md border-l-4 border-indigo-500 hover:shadow-xl transition-all relative overflow-hidden group">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>
                                    <span class="text-gray-400 text-xs"><i class="fas fa-door-open mr-1"></i> {{ $schedule->room->room_number ?? 'Online' }}</span>
                                </div>
                                <h5 class="font-extrabold text-lg text-gray-800 mb-1">{{ $schedule->courseOffering->course->title_km ?? $schedule->courseOffering->course->title_en }}</h5>
                                <p class="text-xs text-gray-400">បន្ទប់រៀន: {{ $schedule->room->room_number ?? 'N/A' }}</p>
                            </div>
                        @empty
                            <div class="col-span-full bg-white p-10 rounded-2xl border border-dashed border-gray-300 text-center text-gray-500">
                                {{ __('មិនមានម៉ោងបង្រៀនសម្រាប់ថ្ងៃនេះទេ។') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                    <div class="lg:col-span-2 bg-gray-50 p-6 rounded-2xl shadow-inner border border-gray-100">
                        <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                            <i class="fas fa-bullhorn mr-3 text-yellow-600"></i> {{ __('សេចក្តីប្រកាស') }}
                        </h4>
                        @forelse ($announcements as $announcement)
                            <div id="announcement-{{ $announcement->id }}" class="p-4 bg-white rounded-xl shadow-md border mb-4 @if($announcement->is_read) opacity-60 @endif">
                                <p class="font-bold text-gray-800">{{ $announcement->title_km ?? $announcement->title_en }}</p>
                                <p class="text-sm text-gray-600 my-2">{{ $announcement->content_km ?? $announcement->content_en }}</p>
                                <div class="flex justify-between items-center mt-3 pt-3 border-t">
                                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}</span>
                                    @if(!$announcement->is_read)
                                        <button onclick="markAsRead({{ $announcement->id }})" class="text-xs text-blue-600 font-bold border border-blue-600 px-3 py-1 rounded-full hover:bg-blue-50">
                                            {{ __('សម្គាល់ថាបានអាន') }}
                                        </button>
                                    @else
                                        <span class="text-xs text-green-600 font-bold">{{ __('បានអានហើយ') }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">{{ __('មិនមានសេចក្តីប្រកាសថ្មីទេ។') }}</p>
                        @endforelse
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl shadow-inner border border-gray-100">
                        <h4 class="text-2xl font-bold text-gray-700 mb-6">{{ __('សកម្មភាពរហ័ស') }}</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <a href="{{ route('professor.my-course-offerings') }}" class="flex items-center p-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                                <i class="fas fa-book mr-3"></i> <span>{{ __('មើលមុខវិជ្ជាខ្ញុំ') }}</span>
                            </a>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<script>
    const readText = "{{ __('បានអានហើយ') }}";

    function markAsRead(id) {
        fetch(`/professor/announcements/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            // ប្រើ data.success ដើម្បីផ្ទៀងផ្ទាត់វិញទើបមានសុវត្ថិភាពជាង
            if (data.success) {
                const el = document.getElementById(`announcement-${id}`);
                if (el) {
                    el.classList.add('opacity-60'); // បន្ថយពណ៌អត្ថបទ
                    const btn = el.querySelector('button');
                    if(btn) {
                        btn.outerHTML = `<span class="text-xs text-green-600 font-bold">${readText}</span>`;
                    }
                }
            } else {
                alert(data.message); // បង្ហាញសារកំហុសបើមាន
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
</x-app-layout>