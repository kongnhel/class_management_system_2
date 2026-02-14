<x-app-layout>
    {{-- នាំចូល Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Moul&display=swap" rel="stylesheet">

    <style>
        :root { 
            --font-header: 'Moul', serif; 
            --font-body: 'Battambang', system-ui, sans-serif; 
        }

        /* ----------------------------------------- */
        /* 🖨️ CSS សម្រាប់ PRINT ONLY (UPDATED SIZE) */
        /* ----------------------------------------- */
        #printable-schedule-container { display: none; } 

        @media print {
            @page { 
                size: A4 landscape; 
                margin: 5mm; /* Margin តូចបំផុត */
            }
            
            body { 
                background: white !important; 
                -webkit-print-color-adjust: exact; 
                margin: 0;
                padding: 0;
                font-family: 'Battambang', system-ui !important;
                /* 🔥 ZOOM: ដំឡើងមក 90% វិញឱ្យធំពេញភ្នែក */
                zoom: 90%; 
            }
            
            .no-print { display: none !important; } 
            
            #printable-schedule-container { 
                display: flex !important;
                flex-direction: column;
                width: 100% !important; 
                height: 98vh; /* ពេញកម្ពស់ */
                justify-content: space-between; 
            }

            /* --- Header Layout --- */
            .header-print-layout {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                align-items: start;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 15px;
            }
            
            .header-left { 
                text-align: center; 
                display: flex; flex-direction: column; align-items: center; justify-content: center;
            }
            .header-center { 
                text-align: center; 
                display: flex; flex-direction: column; align-items: center; justify-content: center;
            }
            .header-right { text-align: right; }

            /* Font Fixing */
            .font-moul { 
                font-family: 'Moul', serif !important; 
                font-weight: normal !important; 
            }

            .uni-logo-text h3 { 
                font-size: 11pt; /* ធំជាងមុន */
                color: #2a58ad; 
                margin: 3px 0; 
                line-height: 1.4; 
            }
            .uni-logo-text img { width: 85px; height: auto; margin-bottom: 5px; } /* Logo ធំជាងមុន */

            .kingdom-header h2 { 
                font-size: 12pt; /* ធំជាងមុន */
                margin: 3px 0; 
                color: black; 
                line-height: 1.4;
            }
            .kingdom-header img { width: 110px; height: auto; margin-top: 5px; }

            .schedule-title-block { text-align: center; margin-bottom: 20px; }
            .schedule-title-block h1 { 
                font-size: 13pt; /* ធំជាងមុន */
                margin: 5px 0; 
                color: black;
            }
            .schedule-title-block p { font-size: 10pt; font-weight: bold; margin: 0; }

            /* --- Table Styles --- */
            .table-wrapper { 
                flex-grow: 1; 
                display: flex;
                flex-direction: column;
                gap: 20px; 
            }

            .matrix-table { 
                width: 100%; 
                border-collapse: collapse; 
                border: 1.5pt solid black; 
            }
            
            .matrix-table th, .matrix-table td { 
                border: 1pt solid black; 
                padding: 8px; /* 🔥 PADDING ធំជាងមុន (ពី 4px ទៅ 8px) ឱ្យ Table ធំ */
                text-align: center; 
                vertical-align: middle; 
                font-size: 9.5pt; /* អក្សរធំជាងមុន */
                line-height: 1.4;
            }
            
            .matrix-table th { 
                font-size: 9.5pt; 
                background-color: #f1f5f9 !important; 
                height: 35px; 
                color: black;
            }
            
            /* Content inside cells */
            .cell-subject { font-weight: bold; display: block; font-size: 9.5pt; margin-bottom: 4px; }
            .cell-lecturer { display: block; font-size: 9pt; color: #000; }
            .cell-room { display: block; font-weight: bold; font-size: 9pt; color: #059669; }

            /* --- Footer Signatures --- */
            .f-sigs { 
                display: flex; 
                justify-content: space-between; 
                margin-top: 10px; 
                padding-bottom: 10px;
            }
            .sig-block { text-align: center; width: 35%; }
            
            .sig-title-top { font-size: 10pt; margin-bottom: 10px; }
            .sig-role { font-size: 10pt; margin: 0; }
            .sig-spacer { height: 80px; } /* កន្លែងហត្ថលេខាធំជាងមុន */
            .sig-name { font-size: 11pt; font-weight: bold; color: #2a58ad; }
            .sig-date { font-size: 9pt; margin-bottom: 5px; }
        }
    </style>

    {{-- UI ដើមរបស់ Admin (SCREEN VIEW - មិនកែប្រែ) --}}
    <div class="min-h-screen bg-gray-100 font-sans text-gray-900 no-print">
        <div class="bg-slate-900 text-white pb-32 pt-12 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider border border-emerald-500/30">
                                Academic Year {{ date('Y') }}
                            </span>
                        </div>
                        <h2 class="text-3xl font-extrabold tracking-tight text-white">{{ __('ការផ្តល់ជូនមុខវិជ្ជា') }}</h2>
                        <p class="text-slate-400 mt-2 max-w-2xl text-sm leading-relaxed">{{ __('គ្រប់គ្រង និងតាមដានការបែងចែកមុខវិជ្ជាទៅតាមជំនាញ សាស្ត្រាចារ្យ និងកាលវិភាគសិក្សា។') }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3" x-data="{ viewMode: '{{ request('view', 'grid') }}' }">
                        <div class="flex gap-2 mr-2">
                            <button onclick="exportToWord()" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2.5 rounded-lg font-bold shadow-lg transition-all flex items-center gap-2 text-sm">
                                <i class="fas fa-file-word"></i> Word
                            </button>
                            <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-lg font-bold shadow-lg transition-all flex items-center gap-2 text-sm">
                                <i class="fas fa-print"></i> បោះពុម្ព
                            </button>
                        </div>
                        <div class="bg-slate-800 p-1 rounded-lg border border-slate-700 flex">
                            <button @click="viewMode = 'grid'; $dispatch('view-changed', 'grid')" :class="viewMode === 'grid' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-slate-200'" class="p-2 rounded-md transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></button>
                            <button @click="viewMode = 'table'; $dispatch('view-changed', 'table')" :class="viewMode === 'table' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-slate-200'" class="p-2 rounded-md transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg></button>
                        </div>
                        <a href="{{ route('admin.create-course-offering') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg font-bold shadow-lg transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>{{ __('បន្ថែមថ្មី') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 pb-12 relative z-10">
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-5 mb-8">
            <form action="{{ route('admin.manage-course-offerings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                
                {{-- 1. Search Box --}}
                <div class="md:col-span-3">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('ស្វែងរកមុខវិជ្ជា/សាស្ត្រាចារ្យ') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរក..." class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white sm:text-sm py-2.5 shadow-sm">
                </div>
                
                {{-- 2. Program --}}
                <div class="md:col-span-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('កម្មវិធីសិក្សា') }}</label>
                    <select name="program_id" class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white sm:text-sm py-2.5">
                        <option value="">{{ __('បង្ហាញទាំងអស់') }}</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name_km }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Generation --}}
                <div class="md:col-span-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('ជំនាន់') }}</label>
                    <input type="text" name="generation" value="{{ request('generation') }}" placeholder="Ex: 17" class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white sm:text-sm py-2.5">
                </div>

                {{-- 🔥 4. SHIFT FILTER (ថ្មី) 🔥 --}}
                <div class="md:col-span-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('វេនសិក្សា (Shift)') }}</label>
                    <select name="shift" class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white sm:text-sm py-2.5">
                        <option value="">{{ __('ទាំងអស់') }}</option>
                        <option value="weekday" {{ request('shift') == 'weekday' ? 'selected' : '' }}>ចន្ទ-សុក្រ (Weekday)</option>
                        <option value="weekend" {{ request('shift') == 'weekend' ? 'selected' : '' }}>សៅរ៍-អាទិត្យ (Weekend)</option>
                    </select>
                </div>

                {{-- 5. Semester --}}
                <div class="md:col-span-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('ឆមាស') }}</label>
                    <select name="semester" class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white sm:text-sm py-2.5">
                        <option value="">{{ __('ទាំងអស់') }}</option>
                        <option value="ឆមាសទី១" {{ request('semester') == 'ឆមាសទី១' ? 'selected' : '' }}>{{ __('ឆមាសទី១') }}</option>
                        <option value="ឆមាសទី២" {{ request('semester') == 'ឆមាសទី២' ? 'selected' : '' }}>{{ __('ឆមាសទី២') }}</option>
                    </select>
                </div>

                {{-- 6. Lecturer --}}
                <div class="md:col-span-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('សាស្ត្រាចារ្យ') }}</label>
                    <select name="lecturer_id" class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white sm:text-sm py-2.5">
                        <option value="">{{ __('ទាំងអស់') }}</option>
                        @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-12 flex justify-end gap-2 mt-2">
                     {{-- Reset Button --}}
                     <a href="{{ route('admin.manage-course-offerings') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors font-bold text-sm">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </a>
                    {{-- Filter Button --}}
                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-6 py-2.5 rounded-lg transition-colors shadow-md text-sm">
                        <i class="fas fa-filter mr-1"></i> Filter Data
                    </button>
                </div>
            </form>
        </div>
            
            <div x-data="{ viewMode: '{{ request('view', 'grid') }}' }" @view-changed.window="viewMode = $event.detail">
                <div x-show="viewMode === 'grid'">
                    @if($courseOfferings->isEmpty())
                        <div class="bg-white p-20 text-center rounded-3xl shadow-sm border border-dashed border-gray-300">
                            <i class="fas fa-search fa-3x text-gray-200 mb-4"></i>
                            <p class="text-gray-500 font-medium italic">មិនមានទិន្នន័យសម្រាប់ការ Filter នេះទេ!</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($courseOfferings as $offering)
                                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-md mb-2 w-fit">Sem {{ $offering->semester }} / {{ $offering->academic_year }}</span>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($offering->targetPrograms as $p)
                                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[9px] font-bold border border-blue-100">{{ $p->name_km }} (G{{ $p->pivot->generation }})</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.edit-course-offering', $offering->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-colors"><i class="fas fa-edit text-xs"></i></a>
                                            <button onclick="openDeleteModal({{ $offering->id }})" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-colors"><i class="fas fa-trash text-xs"></i></button>
                                        </div>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-2 leading-tight">{{ $offering->course->title_km ?? $offering->course->title_en }}</h4>
                                    <div class="flex items-center gap-2 mb-5">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs"><i class="fas fa-user-tie"></i></div>
                                        <span class="text-sm font-semibold text-slate-600">{{ $offering->lecturer->name ?? 'Unassigned' }}</span>
                                    </div>
                                    <div class="space-y-2 border-t pt-5">
                                        @foreach($offering->schedules as $s)
                                            <div class="flex justify-between items-center text-[11px] bg-slate-50 p-2 rounded-xl">
                                                <span class="font-bold text-slate-800">{{ substr($s->day_of_week, 0, 3) }}</span>
                                                <span class="text-slate-500 font-medium">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</span>
                                                <span class="text-emerald-600 font-bold bg-white px-2 py-0.5 rounded-md border border-emerald-100">Rm: {{ $s->room->room_number ?? '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="mt-12 no-print">{{ $courseOfferings->links() }}</div>
            </div>
        </div>
    </div>

    {{-- 🔥🔥🔥 ផ្នែកសម្រាប់បោះពុម្ព (PRINTABLE AREA - UPDATED) 🔥🔥🔥 --}}
    <div id="printable-schedule-container">
        @php
            $allSchedules = collect();
            foreach($courseOfferings as $off) { foreach($off->schedules as $s) { $allSchedules->push($s); } }
            $weekdayMap = ['Monday' => 'ចន្ទ/Mon', 'Tuesday' => 'អង្គារ/Tue', 'Wednesday' => 'ពុធ/Wed', 'Thursday' => 'ព្រហស្បតិ៍/Thu', 'Friday' => 'សុក្រ/Fri'];
            $weekendMap = ['Saturday' => 'សៅរ៍/Sat', 'Sunday' => 'អាទិត្យ/Sun'];
            $weekdaySchedules = $allSchedules->filter(fn($s) => array_key_exists($s->day_of_week, $weekdayMap));
            $weekendSchedules = $allSchedules->filter(fn($s) => array_key_exists($s->day_of_week, $weekendMap));
            $weekdayRows = $weekdaySchedules->groupBy(fn($s) => \Carbon\Carbon::parse($s->start_time)->format('H:i') . '-' . \Carbon\Carbon::parse($s->end_time)->format('H:i'))->sortKeys();
            $weekendTimeSlots = $weekendSchedules->map(fn($s) => \Carbon\Carbon::parse($s->start_time)->format('H:i') . '-' . \Carbon\Carbon::parse($s->end_time)->format('H:i'))->unique()->sort();

            // 🔥 DYNAMIC PROGRAM NAME LOGIC
            $currentProgramName = "ជំនាញ គ្រប់គ្រងបច្ចេកវិទ្យាព័ត៌មានវិទ្យា"; 
            if(request('program_id')){
                $prog = $programs->firstWhere('id', request('program_id'));
                if($prog) $currentProgramName = $prog->name_km;
            } elseif($courseOfferings->isNotEmpty()) {
                $first = $courseOfferings->first();
                if($first->targetPrograms->isNotEmpty()){
                    $currentProgramName = $first->targetPrograms->first()->name_km;
                }
            }
            $generation = request('generation');
            $genText = $generation ? "(G$generation)" : "";
        @endphp

        {{-- 🔼 HEADER --}}
        <div>
            <div class="header-print-layout">
                <div class="header-left uni-logo-text">
                    <img src="{{ asset('assets/image/nmu_Logo.png') }}" alt="Logo">
                    <h3 class="font-moul">សាកលវិទ្យាល័យជាតិមានជ័យ</h3>
                    <h3 class="font-moul">ការិយាល័យសិក្សា</h3>
                </div>
                <div class="header-center kingdom-header">
                    <h2 class="font-moul">ព្រះរាជាណាចក្រកម្ពុជា</h2>
                    <h2 class="font-moul">ជាតិ សាសនា ព្រះមហាក្សត្រ</h2>
                    <img src="{{ asset('assets/image/2.png') }}" alt="Line">
                </div>
                <div class="header-right"></div> 
            </div>

            <div class="schedule-title-block">
                <h1 class="font-moul">តារាងវិភាគកម្មធម៌ឆមាសទី{{ request('semester') == 'ឆមាសទី២' ? '២' : '១' }} / Timetable Semester {{ request('semester') == 'ឆមាសទី២' ? '2' : '1' }}</h1>
                <p>ជំនាន់ទី{{ request('generation', '...') }} ថ្នាក់បរិញ្ញាបត្រវិទ្យាសាស្ត្រ និងបច្ចេកវិទ្យា ឆ្នាំសិក្សា {{ date('Y') }}-{{ date('Y')+1 }}</p>
                <p style="font-weight: normal; font-size: 10pt;">ចាប់ផ្តើមពីថ្ងៃចន្ទ ១២ កើត ខែអស្សុជ ឆ្នាំរោង ឆស័ក ព.ស ២៥៦៨ ត្រូវនឹងថ្ងៃទី១៤ ខែតុលា ឆ្នាំ២០២៤ ដល់សប្តាហ៍</p>
            </div>
        </div>

        {{-- ⏹️ CONTENT TABLES --}}
        <div class="table-wrapper">
            {{-- 📅 1. MONDAY - FRIDAY --}}
            @if($weekdayRows->isNotEmpty())
                <div style="text-align: left; font-weight: bold; font-family: 'Battambang'; text-decoration: underline; font-size: 10pt; margin-bottom: 5px;">ជំនាញ {{ $currentProgramName }} {{ $genText }} (Mon-Fri)</div>
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th class="font-moul" style="width: 12%;">ម៉ោងសិក្សា</th>
                            @foreach($weekdayMap as $label) <th class="font-moul">{{ $label }}</th> @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weekdayRows as $slot => $slots)
                        <tr>
                            <td style="font-weight: bold;">{{ $slot }}</td>
                            @foreach($weekdayMap as $dayKey => $label)
                                <td>
                                    @php $class = $slots->where('day_of_week', $dayKey)->first(); @endphp
                                    @if($class)
                                        <span class="cell-subject">{{ $class->courseOffering->course->title_km ?? 'N/A' }}</span>
                                        <span class="cell-lecturer">លោក {{ $class->courseOffering->lecturer->name ?? 'N/A' }}</span>
                                        <span class="cell-room">បន្ទប់ {{ $class->room->room_number ?? '-' }}</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- 📅 2. SATURDAY - SUNDAY --}}
            @if($weekendSchedules->isNotEmpty())
                <div style="text-align: left; font-weight: bold; font-family: 'Battambang'; text-decoration: underline; font-size: 10pt; margin-bottom: 5px;">ជំនាញ {{ $currentProgramName }} {{ $genText }} (Sat-Sun)</div>
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th class="font-moul" style="width: 12%;">ថ្ងៃសិក្សា</th>
                            @foreach($weekendTimeSlots as $timeSlot) <th class="font-moul">{{ $timeSlot }}</th> @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weekendMap as $dayKey => $dayLabel)
                        <tr>
                            <td class="font-moul" style="background-color: #f8fafc;">{{ $dayLabel }}</td>
                            @foreach($weekendTimeSlots as $time)
                                <td>
                                    @php 
                                        $class = $weekendSchedules->filter(function($s) use ($dayKey, $time) {
                                            $slot = \Carbon\Carbon::parse($s->start_time)->format('H:i') . '-' . \Carbon\Carbon::parse($s->end_time)->format('H:i');
                                            return $s->day_of_week === $dayKey && $slot === $time;
                                        })->first();
                                    @endphp
                                    @if($class)
                                        <span class="cell-subject">{{ $class->courseOffering->course->title_km ?? 'N/A' }}</span>
                                        <span class="cell-lecturer">លោក {{ $class->courseOffering->lecturer->name ?? 'N/A' }}</span>
                                        <span class="cell-room">បន្ទប់ {{ $class->room->room_number ?? '-' }}</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- 🔽 FOOTER SIGNATURES --}}
        <div class="f-sigs">
            <div class="sig-block" style="text-align: left; padding-left: 20px;">
                <div class="sig-title-top font-moul">បានឃើញ និងឯកភាព</div>
                <div class="sig-role font-moul">ជ. សាកលវិទ្យាធិការ</div>
                <div class="sig-role font-moul">សាកលវិទ្យាធិការរង</div>
                <div class="sig-spacer"></div>
                <div class="sig-name font-moul">ផុន សុខិន</div>
            </div>
            
            <div class="sig-block" style="text-align: right; padding-right: 20px;">
                <div class="sig-date">ថ្ងៃ............. ខែ............. ឆ្នាំ............. ព.ស ២៥៦៨</div>
                <div class="sig-date">បន្ទាយមានជ័យ ថ្ងៃទី............. ខែ............. ឆ្នាំ២០......</div>
                <div class="sig-title-top font-moul" style="margin-top: 5px;">ប្រធានការិយាល័យសិក្សា</div>
                <div class="sig-spacer"></div>
                <div class="sig-name font-moul">សឿន ~ មុំ</div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL (ដូចដើម) --}}
    <div id="delete-modal" class="relative z-50 hidden no-print" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl sm:w-full sm:max-w-lg border border-gray-100">
                    <div class="bg-white p-8">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-6 mx-auto"><i class="fas fa-trash-alt fa-2x"></i></div>
                        <h3 class="text-xl font-black text-center text-slate-900">លុបការផ្តល់ជូនមុខវិជ្ជា?</h3>
                        <p class="text-sm text-gray-500 mt-4 text-center leading-relaxed">តើអ្នកប្រាកដទេថាចង់លុបទិន្នន័យនេះ? ប្រតិបត្តិការនេះមិនអាចត្រឡប់ថយក្រោយវិញបានឡើយ。</p>
                    </div>
                    <div class="bg-slate-50 px-8 py-5 flex justify-center gap-3">
                        <button onclick="closeDeleteModal()" class="bg-white border border-slate-200 px-6 py-2.5 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition-colors">បោះបង់</button>
                        <form id="delete-form" method="POST" action=""> @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-8 py-2.5 rounded-2xl text-sm font-black shadow-lg shadow-red-500/30 hover:bg-red-500 transition-all">យល់ព្រមលុប</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id) {
            const form = document.getElementById('delete-form');
            form.action = '{{ route("admin.course-offerings.destroy", ":id") }}'.replace(':id', id);
            document.getElementById('delete-modal').classList.remove('hidden');
        }
        function closeDeleteModal() { document.getElementById('delete-modal').classList.add('hidden'); }

        function getBase64Image(img) {
            var canvas = document.createElement("canvas");
            canvas.width = img.naturalWidth; canvas.height = img.naturalHeight;
            var ctx = canvas.getContext("2d"); ctx.drawImage(img, 0, 0);
            return canvas.toDataURL("image/png");
        }

        function exportToWord() {
            const logo = document.querySelector('.uni-logo-text img');
            const line = document.querySelector('.kingdom-header img');
            let content = document.getElementById('printable-schedule-container').cloneNode(true);
            content.style.display = 'block';
            
            // Re-embed images for Word
            if(logo && logo.src) {
                const logoClone = content.querySelector('.uni-logo-text img');
                if(logoClone) logoClone.src = getBase64Image(logo);
            }
            if(line && line.src) {
                 const lineClone = content.querySelector('.kingdom-header img');
                 if(lineClone) lineClone.src = getBase64Image(line);
            }

            const htmlString = `
                <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
                <head><meta charset='utf-8'><style>
                    body { font-family: 'Battambang', Arial, sans-serif; }
                    .matrix-table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1pt solid black; padding: 5px; text-align: center; }
                    th { background-color: #f1f5f9; font-family: 'Moul', serif; font-size: 9pt; }
                    @page { size: A4 landscape; margin: 1cm; }
                </style></head>
                <body>${content.innerHTML}</body></html>`;

            const blob = new Blob(['\ufeff', htmlString], { type: 'application/msword' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url; link.download = 'NMU-Academic-Schedule.doc';
            document.body.appendChild(link); link.click(); document.body.removeChild(link);
        }
    </script>
</x-app-layout>