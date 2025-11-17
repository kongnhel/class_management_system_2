<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <h2 class="font-semibold text-2xl md:text-3xl text-gray-900 leading-tight mb-4 md:mb-0">
            {{ __('កាលវិភាគរបស់ខ្ញុំ') }}
        </h2>
    </div>

    {{-- NEW WRAPPER FOR HORIZONTAL SCROLL (Filter Buttons) --}}
    <div class="w-full mt-4 md:mt-0 overflow-x-auto whitespace-nowrap pb-2">
        <div class="inline-flex space-x-2 sm:space-x-4">
            {{-- The buttons will now stay on one line and scroll horizontally on small screens --}}
            <button data-day-kh="ទាំងអស់" data-day-en="all"
                class="day-filter-btn px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 shadow-sm whitespace-nowrap">
                ទាំងអស់
            </button>
            <button data-day-kh="ច័ន្ទ" data-day-en="Monday"
                class="day-filter-btn px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-green-50 transition duration-300 shadow-sm whitespace-nowrap">
                ច័ន្ទ
            </button>
            <button data-day-kh="អង្គារ" data-day-en="Tuesday"
                class="day-filter-btn px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-green-50 transition duration-300 shadow-sm whitespace-nowrap">
                អង្គារ
            </button>
            <button data-day-kh="ពុធ" data-day-en="Wednesday"
                class="day-filter-btn px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-green-50 transition duration-300 shadow-sm whitespace-nowrap">
                ពុធ
            </button>
            <button data-day-kh="ព្រហស្បតិ៍" data-day-en="Thursday"
                class="day-filter-btn px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-green-50 transition duration-300 shadow-sm whitespace-nowrap">
                ព្រហស្បតិ៍
            </button>
            <button data-day-kh="សុក្រ" data-day-en="Friday"
                class="day-filter-btn px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-green-50 transition duration-300 shadow-sm whitespace-nowrap">
                សុក្រ
            </button>
            <button data-day-kh="សៅរ៍" data-day-en="Saturday"
                class="day-filter-btn px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-green-50 transition duration-300 shadow-sm whitespace-nowrap">
                សៅរ៍
            </button>
            <button data-day-kh="អាទិត្យ" data-day-en="Sunday"
                class="day-filter-btn px-4 py-2 bg-white text-gray-800 rounded-md hover:bg-green-50 transition duration-300 shadow-sm whitespace-nowrap">
                អាទិត្យ
            </button>
        </div>
    </div>
</x-slot>

    <div class="py-10 bg-gray-100 font-['Battambang']">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8">
<div class="flex items-center justify-between mb-6">
    <h3 class="text-3xl font-extrabold text-gray-800 mb-8">កាលវិភាគសិក្សា</h3>
                        <div class="mb-6 flex justify-end no-print">
                <button onclick="window.print()" 
                    class="px-6 py-3 bg-green-600 text-white font-bold text-lg rounded-xl shadow-lg hover:bg-green-700 transition-colors duration-200 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('បោះពុម្ព') }}</span>
                </button>
            </div>

</div>
            
                @php
                    function getDayColorClasses($day) {
                        return [
                            'Monday' => 'text-blue-600 bg-blue-50 border-blue-200',
                            'Tuesday' => 'text-green-600 bg-green-50 border-green-200',
                            'Wednesday' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
                            'Thursday' => 'text-purple-600 bg-purple-50 border-purple-200',
                            'Friday' => 'text-pink-600 bg-pink-50 border-pink-200',
                            'Saturday' => 'text-orange-600 bg-orange-50 border-orange-200',
                            'Sunday' => 'text-red-600 bg-red-50 border-red-200', // Weekend day
                        ][$day] ?? 'text-gray-600';
                    }
                @endphp

                {{-- ✅ Screen Version (Table - HIDDEN on mobile, SHOWN on desktop) --}}
                <div id="screen-timetable" class="hidden md:block overflow-x-auto rounded-xl shadow-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-500 to-green-600">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider rounded-tl-xl">
                                    មុខវិជ្ជា</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">
                                    សាស្រ្តាចារ្យ</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">ថ្ងៃ
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">ម៉ោងចាប់ផ្តើម
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">ម៉ោងបញ្ចប់
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider rounded-tr-xl">
                                    បន្ទប់</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                // Mapping ថ្ងៃទៅលេខសម្រាប់ order
                                $dayOrder = [
                                    'Monday' => 1,
                                    'Tuesday' => 2,
                                    'Wednesday' => 3,
                                    'Thursday' => 4,
                                    'Friday' => 5,
                                    'Saturday' => 6,
                                    'Sunday' => 7,
                                ];
                                // Sort by ថ្ងៃ -> ម៉ោងចាប់ផ្តើម
                                $schedules = $schedules->sortBy(function ($schedule) use ($dayOrder) {
                                    return sprintf(
                                        '%02d-%s',
                                        $dayOrder[$schedule->day_of_week] ?? 99,
                                        $schedule->start_time,
                                    );
                                });
                            @endphp

                            @forelse ($schedules as $schedule)
                                @php
                                    $dayColorClass = getDayColorClasses($schedule->day_of_week);
                                @endphp
                                <tr class="schedule-row hover:bg-gray-50 transition duration-200"
                                    data-day="{{ $schedule->day_of_week }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ $schedule->courseOffering->course->title_en?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $schedule->courseOffering->lecturer->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $dayColorClass }}">
                                        {{ $schedule->day_of_week }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $schedule->room->room_number ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="px-6 py-12 text-center text-gray-500 font-medium">
                                        មិនមានកាលវិភាគទេ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ✅ Mobile Version (Cards - SHOWN on mobile, HIDDEN on desktop) --}}
                <div id="mobile-timetable" class="block md:hidden space-y-4">
                    @forelse ($schedules as $schedule)
                        @php
                            // Utility map to get Khmer day name for the card
                            $khmerDay = [
                                'Monday' => 'ច័ន្ទ', 'Tuesday' => 'អង្គារ', 'Wednesday' => 'ពុធ',
                                'Thursday' => 'ព្រហស្បតិ៍', 'Friday' => 'សុក្រ', 'Saturday' => 'សៅរ៍',
                                'Sunday' => 'អាទិត្យ',
                            ][$schedule->day_of_week] ?? $schedule->day_of_week;
                            
                            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
                            $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');

                            // Get color classes for the card's day badge
                            $dayColorClass = getDayColorClasses($schedule->day_of_week);
                        @endphp

                        {{-- Start of Card --}}
                        <div class="schedule-row bg-white border border-gray-200 rounded-lg shadow-md p-4 space-y-2"
                            data-day="{{ $schedule->day_of_week }}">

                            <div class="flex justify-between items-start border-b pb-2">
                                <p class="text-lg font-extrabold text-green-700">
                                    {{ $schedule->courseOffering->course->title_en ?? 'N/A' }}
                                </p>
                                {{-- UPDATED CLASS HERE --}}
                                <span class="text-sm font-semibold {{ $dayColorClass }} px-3 py-1 rounded-full whitespace-nowrap border">
                                    {{ $khmerDay }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-sm">
                                {{-- Row 1: Lecturer --}}
                                <p class="font-medium text-gray-500">{{ __('សាស្រ្តាចារ្យ:') }}</p>
                                <p class="text-gray-800 font-semibold">{{ $schedule->courseOffering->lecturer->name ?? 'N/A' }}</p>

                                {{-- Row 2: Room --}}
                                <p class="font-medium text-gray-500">{{ __('បន្ទប់:') }}</p>
                                <p class="text-gray-800 font-semibold">{{ $schedule->room->room_number ?? 'N/A' }}</p>

                                {{-- Row 3: Time --}}
                                <p class="font-medium text-gray-500">{{ __('ម៉ោង:') }}</p>
                                <p class="text-gray-800 font-semibold">{{ $startTime }} - {{ $endTime }}</p>
                            </div>
                        </div>
                        {{-- End of Card --}}
                    @empty
                        <p class="text-center text-gray-500 py-8">{{ __('មិនមានកាលវិភាគទេ') }}</p>
                    @endforelse
                </div>

                {{-- ✅ Print Version (No changes needed here) --}}
                <div id="print-timetable" class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h1 class="text-xl font-bold">កាលវិភាគសិក្សារបស់ខ្ញុំ</h1>
                            {{-- <p class="text-sm">ឈ្មោះនិស្សិត៖ {{ $user->name_kh ?? $user->name_en }}</p> --}}
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
                                <th>ច័ន្ទ</th>
                                <th>អង្គារ</th>
                                <th>ពុធ</th>
                                <th>ព្រហស្បតិ៍</th>
                                <th>សុក្រ</th>
                                <th>សៅរ៍</th>
                                <th>អាទិត្យ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Group by time slot
                                $grouped = $schedules->groupBy(function($s) {
                                    return \Carbon\Carbon::parse($s->start_time)->format('H:i') . ' - ' .
                                                   \Carbon\Carbon::parse($s->end_time)->format('H:i');
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.day-filter-btn');
            // UPDATED: Select both the table rows AND the new mobile cards
            const scheduleRows = document.querySelectorAll('#screen-timetable .schedule-row, #mobile-timetable .schedule-row');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const selectedDay = button.getAttribute('data-day-en');

                    // Reset button styles
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-green-600', 'text-white');
                        btn.classList.add('bg-white', 'text-gray-800');
                    });

                    // Set active button style
                    button.classList.add('bg-green-600', 'text-white');
                    button.classList.remove('bg-white', 'text-gray-800');

                    // Filter rows (applies to both table rows and cards)
                    scheduleRows.forEach(row => {
                        const rowDay = row.getAttribute('data-day');
                        if (selectedDay === 'all' || rowDay === selectedDay) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>

<style>
    #print-timetable {
        display: none;
    }

@media print {
    /* Hide unwanted parts */
    header, nav, .day-filter-btn, #screen-timetable, #mobile-timetable, .alert, .header, .page-title .sidebar {
        display: none !important;
    }

    /* Keep only print timetable */
    #print-timetable {
        display: block !important;
    }

    body {
        font-family: 'Battambang', 'Khmer OS', Arial, sans-serif;
        font-size: 11px;
        color: #000;
    }

    @page {
        size: A4 landscape; /* អាចប្តូរ portrait ប្រសិនបើចង់ */
        margin: 10mm;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #000;
        text-align: center;
        padding: 4px;
        word-wrap: break-word;
        font-size: 11px;
    }

    th {
        background: #f0f0f0;
        font-size: 12px;
    }

    .time-col {
        font-weight: bold;
        width: 70px;
    }
}

</style>