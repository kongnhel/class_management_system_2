<x-app-layout>
    <x-slot name="header">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 no-print px-2">
            <div>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    {{ __('កាលវិភាគសិក្សា') }}
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">{{ __('ពិនិត្យ និងគ្រប់គ្រងម៉ោងសិក្សារបស់អ្នក') }}</p>
            </div>
            
            <button onclick="window.print()" 
                class="inline-flex items-center px-8 py-3 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-print mr-2 text-lg"></i>
                <span>{{ __('បោះពុម្ពកាលវិភាគ') }}</span>
            </button>
        </div>

        {{-- Day Filter Tabs --}}
        <div class="w-full mt-8 overflow-x-auto no-print scrollbar-hide">
            <div class="inline-flex p-1.5 bg-gray-100 rounded-2xl space-x-1 border border-gray-200/50">
                <button data-day-en="all" class="day-filter-btn px-8 py-2.5 rounded-xl text-sm font-black transition-all duration-300 bg-white text-green-700 shadow-md">ទាំងអស់</button>
                @foreach(['Monday' => 'ច័ន្ទ', 'Tuesday' => 'អង្គារ', 'Wednesday' => 'ពុធ', 'Thursday' => 'ព្រហស្បតិ៍', 'Friday' => 'សុក្រ', 'Saturday' => 'សៅរ៍', 'Sunday' => 'អាទិត្យ'] as $en => $kh)
                    <button data-day-en="{{ $en }}" class="day-filter-btn px-8 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:text-green-600 transition-all underline-offset-4">{{ $kh }}</button>
                @endforeach
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#fcfdfe] font-['Battambang'] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="no-print">
                @php
                    function getDayColor($day) {
                        return [
                            'Monday' => 'bg-blue-600', 'Tuesday' => 'bg-green-600',
                            'Wednesday' => 'bg-amber-500', 'Thursday' => 'bg-purple-600',
                            'Friday' => 'bg-pink-600', 'Saturday' => 'bg-orange-600',
                            'Sunday' => 'bg-red-600',
                        ][$day] ?? 'bg-gray-600';
                    }
                @endphp

                {{-- Desktop View --}}
                <div id="screen-timetable" class="hidden md:block bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 overflow-hidden border border-gray-100">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">មុខវិជ្ជា</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">សាស្រ្តាចារ្យ</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">ថ្ងៃសិក្សា</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">ម៉ោង</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">បន្ទប់</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($schedules as $schedule)
                            <tr class="schedule-row group hover:bg-green-50/30 transition-all duration-300" data-day="{{ $schedule->day_of_week }}">
                                <td class="px-8 py-6">
                                    <div class="font-black text-gray-900 group-hover:text-green-700 transition-colors">{{ $schedule->courseOffering->course->title_en ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase mt-1">{{ $schedule->courseOffering->course->code ?? '' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs border border-gray-200">
                                            {{ substr($schedule->courseOffering->lecturer->name ?? 'P', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-gray-700">{{ $schedule->courseOffering->lecturer->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ getDayColor($schedule->day_of_week) }}"></span>
                                        <span class="text-sm font-black text-gray-800">{{ $schedule->day_of_week }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-sm tabular-nums font-bold text-gray-600">
                                    <i class="far fa-clock mr-2 text-green-500 opacity-50"></i>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-gray-900 text-white px-4 py-1.5 rounded-xl text-xs font-black shadow-sm">
                                        {{ $schedule->room->room_number ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile View --}}
                <div id="mobile-timetable" class="md:hidden space-y-6 px-2">
                    @foreach ($schedules as $schedule)
                        <div class="schedule-row bg-white rounded-3xl p-6 shadow-xl shadow-gray-100/50 border border-gray-100 relative overflow-hidden" data-day="{{ $schedule->day_of_week }}">
                            <div class="absolute top-0 right-0 w-32 h-32 {{ getDayColor($schedule->day_of_week) }} opacity-[0.03] -mr-16 -mt-16 rounded-full"></div>
                            
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-[10px] font-black {{ str_replace('bg-', 'text-', getDayColor($schedule->day_of_week)) }} uppercase tracking-widest">{{ $schedule->day_of_week }}</span>
                                    <h4 class="text-xl font-black text-gray-900 mt-1">{{ $schedule->courseOffering->course->title_en ?? 'N/A' }}</h4>
                                </div>
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-lg text-xs font-black">{{ $schedule->room->room_number ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-50">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ __('សាស្រ្តាចារ្យ') }}</p>
                                    <p class="text-sm font-bold text-gray-700">{{ $schedule->courseOffering->lecturer->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ __('ម៉ោងសិក្សា') }}</p>
                                    <p class="text-sm font-bold text-green-700">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
        {{-- ORIGINAL PRINT VIEW (Unchanged as requested) --}}

            <div id="print-timetable" class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h1 class="text-xl font-bold">កាលវិភាគសិក្សារបស់ខ្ញុំ</h1>
                        <p class="text-sm">ជំនាន់៖ {{ $user->generation }}</p>
                        @if($studentProgram)
                        <p class="text-sm">កម្មវិធីសិក្សា៖ {{ $studentProgram->name_km ?? $studentProgram->name_en }}</p>
                        @endif
                    </div>
                    <img src="{{ asset('assets/image/nmu_Logo.png') }}" alt="Logo" class="w-16 h-16">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th class="time-col">ម៉ោង</th>
                            <th>ច័ន្ទ</th><th>អង្គារ</th><th>ពុធ</th><th>ព្រហស្បតិ៍</th><th>សុក្រ</th><th>សៅរ៍</th><th>អាទិត្យ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grouped = $schedules->groupBy(function($s) {
                                return \Carbon\Carbon::parse($s->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($s->end_time)->format('H:i');
                            });
                            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                        @endphp
                        @foreach($grouped as $slot => $schedulesPerSlot)
                            <tr>
                                <td class="time-col">{{ $slot }}</td>
                                @foreach($days as $day)
                                    <td>
                                        @foreach($schedulesPerSlot->where('day_of_week', $day) as $sch)
                                            <div><strong>{{ $sch->courseOffering->course->title_km ?? '' }}</strong></div>
                                            <div>{{ $sch->courseOffering->lecturer->name ?? '' }}</div>
                                            <div>បន្ទប់ {{ $sch->room->room_number ?? '' }}</div>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top:30px; text-align:right; font-size:12px;">

                    <p>Printed on: {{ now()->format('d/m/Y H:i') }}</p>

                    <p>Signature: ______________________</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.day-filter-btn');
            const scheduleRows = document.querySelectorAll('.schedule-row');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const selectedDay = button.getAttribute('data-day-en');
                    
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-white', 'text-green-700', 'shadow-md');
                        btn.classList.add('text-gray-500');
                    });
                    
                    button.classList.add('bg-white', 'text-green-700', 'shadow-md');
                    button.classList.remove('text-gray-500');

                    scheduleRows.forEach(row => {
                        const rowDay = row.getAttribute('data-day');
                        if (selectedDay === 'all' || rowDay === selectedDay) {
                            row.style.display = '';
                            row.classList.add('animate-fade-in');
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        #print-timetable { display: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }

        @media print {
            .no-print, header, nav, .day-filter-btn { display: none !important; }
            #print-timetable { display: block !important; }
            body { font-family: 'Battambang', sans-serif; font-size: 11px; }
            @page { size: A4 landscape; margin: 10mm; }
            table { border-collapse: collapse; width: 100%; table-layout: fixed; }
            th, td { border: 1px solid #000; text-align: center; padding: 4px; word-wrap: break-word; font-size: 10px; }
            th { background: #f0f0f0; }
            .time-col { font-weight: bold; width: 70px; }
        }
    </style>
</x-app-layout>