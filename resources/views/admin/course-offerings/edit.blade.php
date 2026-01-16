<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">
            {{ __('កែសម្រួលការផ្តល់ជូនមុខវិជ្ជា') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 font-['Battambang']">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-3xl p-6 sm:p-8 lg:p-12">
                
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                        <strong class="font-bold">{{ __('មានបញ្ហា!') }}</strong>
                        <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.course-offerings.update', $courseOffering->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-10">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">
                            <div class="bg-gray-50 p-6 rounded-2xl shadow-inner">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">{{ __('ព័ត៌មានកែប្រែ') }}</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <div>
                                            <label for="program_id" class="block text-sm font-medium text-gray-700">កម្មវិធីសិក្សា <span class="text-red-500">*</span></label>
                                            <select id="program_id" name="program_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                                <option value="">ជ្រើសរើសកម្មវិធីសិក្សា</option>
                                                @foreach($programs as $program)
                                                    <option value="{{ $program->id }}" {{ $courseOffering->program_id == $program->id ? 'selected' : '' }}>{{ $program->name_km }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="generation" class="block text-sm font-medium text-gray-700">{{ __('ជំនាន់') }}<span class="text-red-500">*</span></label>
                                            <select id="generation" name="generation" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                                <option value="">{{ __('ជ្រើសរើសជំនាន់') }}</option>
                                                @foreach ($generations as $generation)
                                                    <option value="{{ $generation }}" {{ $courseOffering->generation == $generation ? 'selected' : '' }}>{{ $generation }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="course_id" class="block text-sm font-medium text-gray-700">មុខវិជ្ជា <span class="text-red-500">*</span></label>
                                            <select id="course_id" name="course_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                                <option value="">{{ __('សូមជ្រើសរើសកម្មវិធីសិក្សានិងជំនាន់សិន') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="lecturer_user_id" class="block text-sm font-medium text-gray-700">សាស្រ្តាចារ្យ <span class="text-red-500">*</span></label>
                                        <select id="lecturer_user_id" name="lecturer_user_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                            @foreach($lecturers as $lecturer)
                                                <option value="{{ $lecturer->id }}" {{ $courseOffering->lecturer_user_id == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="academic_year" class="block text-sm font-medium text-gray-700">ឆ្នាំសិក្សា</label>
                                            <input type="text" id="academic_year" name="academic_year" value="{{ $courseOffering->academic_year }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                                        </div>
                                        <div>
                                            <label for="semester" class="block text-sm font-medium text-gray-700">ឆមាស</label>
                                            <select id="semester" name="semester" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                                                <option value="ឆមាសទី១" {{ $courseOffering->semester == 'ឆមាសទី១' ? 'selected' : '' }}>ឆមាសទី១</option>
                                                <option value="ឆមាសទី២" {{ $courseOffering->semester == 'ឆមាសទី២' ? 'selected' : '' }}>ឆមាសទី២</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="capacity" class="block text-sm font-medium text-gray-700">ចំនួនអតិបរមានិស្សិត</label>
                                            <input type="number" id="capacity" name="capacity" value="{{ $courseOffering->capacity }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="start_date" class="block text-sm font-medium text-gray-700">កាលបរិច្ឆេទចាប់ផ្តើម</label>
                                            <input type="date" id="start_date" name="start_date" value="{{ \Carbon\Carbon::parse($courseOffering->start_date)->format('Y-m-d') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                                        </div>
                                        <div>
                                            <label for="end_date" class="block text-sm font-medium text-gray-700">កាលបរិច្ឆេទបញ្ចប់</label>
                                            <input type="date" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($courseOffering->end_date)->format('Y-m-d') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-2xl shadow-inner border border-gray-200">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-bold text-gray-800">{{ __('កាលវិភាគសិក្សា') }}</h3>
                                    <button type="button" id="add-schedule-btn" class="group flex items-center space-x-2 text-green-600 font-bold hover:text-green-700 transition duration-200">
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-green-50 group-hover:bg-green-100">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        </span>
                                        <span>{{ __('បន្ថែមម៉ោងសិក្សាថ្មី') }}</span>
                                    </button>
                                </div>
                                
                                <div id="schedules-container" class="space-y-4">
                                    @foreach ($courseOffering->schedules as $index => $schedule)
                                        <div class="schedule-item group relative bg-gray-50 p-5 rounded-2xl border border-gray-100 shadow-sm transition duration-200 mb-4">
                                            <div class="flex items-center mb-3 text-sm font-bold text-green-600 session-label">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                                                Session {{ $index + 1 }}
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div class="col-span-2 md:col-span-1">
                                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ថ្ងៃសិក្សា</label>
                                                    <select name="schedules[{{ $index }}][day_of_week]" class="w-full rounded-xl border-gray-200 text-sm focus:ring-green-500" required>
                                                        @php
                                                            $khmerDays = ['Monday' => 'ច័ន្ទ', 'Tuesday' => 'អង្គារ', 'Wednesday' => 'ពុធ', 'Thursday' => 'ព្រហស្បតិ៍', 'Friday' => 'សុក្រ', 'Saturday' => 'សៅរ៍', 'Sunday' => 'អាទិត្យ'];
                                                        @endphp
                                                        @foreach ($khmerDays as $en => $kh)
                                                            <option value="{{ $en }}" {{ $schedule->day_of_week == $en ? 'selected' : '' }}>{{ $kh }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-span-2 md:col-span-1">
                                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">បន្ទប់</label>
                                                    <select name="schedules[{{ $index }}][room_id]" class="w-full rounded-xl border-gray-200 text-sm focus:ring-green-500" required>
                                                        @foreach($rooms as $room)
                                                            <option value="{{ $room->id }}" {{ $schedule->room_id == $room->id ? 'selected' : '' }}>{{ $room->room_number }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-span-1">
                                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ចាប់ផ្តើម</label>
                                                    <input type="time" name="schedules[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" class="w-full rounded-xl border-gray-200 text-sm" required>
                                                </div>
                                                <div class="col-span-1">
                                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">បញ្ចប់</label>
                                                    <input type="time" name="schedules[{{ $index }}][end_time]" value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" class="w-full rounded-xl border-gray-200 text-sm">
                                                </div>
                                            </div>
                                            <button type="button" class="remove-schedule absolute -top-2 -right-2 bg-white text-gray-300 hover:text-red-500 rounded-full border border-gray-100 shadow-sm p-1 transition-colors duration-200 opacity-0 group-hover:opacity-100" onclick="removeRow(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-green-600 text-white font-bold rounded-full shadow-lg hover:bg-green-700 transition transform hover:scale-105">
                                {{ __('រក្សាទុកការកែសម្រួល') }} ✅
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rooms = {!! json_encode($rooms->map(fn($r) => ['id' => $r->id, 'room_number' => $r->room_number])) !!};
        const khmerDays = {'Monday': 'ច័ន្ទ', 'Tuesday': 'អង្គារ', 'Wednesday': 'ពុធ', 'Thursday': 'ព្រហស្បតិ៍', 'Friday': 'សុក្រ', 'Saturday': 'សៅរ៍', 'Sunday': 'អាទិត្យ'};
        
        const programSelect = document.getElementById('program_id');
        const generationSelect = document.getElementById('generation');
        const courseSelect = document.getElementById('course_id');
        const schedulesContainer = document.getElementById('schedules-container');
        const addBtn = document.getElementById('add-schedule-btn');

        // Logic សម្រាប់ Load Course
        function updateCourses(programId, generation, defaultCourseId = null) {
            if (!programId || !generation) return;
            fetch(`/admin/get-courses-by-program-and-generation?program_id=${programId}&generation=${generation}`)
                .then(res => res.json())
                .then(courses => {
                    courseSelect.innerHTML = '<option value="">{{ __("ជ្រើសរើសមុខវិជ្ជា") }}</option>';
                    courses.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = c.title_km;
                        if (c.id == defaultCourseId) opt.selected = true;
                        courseSelect.appendChild(opt);
                    });
                });
        }

        programSelect.addEventListener('change', () => updateCourses(programSelect.value, generationSelect.value));
        generationSelect.addEventListener('change', () => updateCourses(programSelect.value, generationSelect.value));
        
        // Load ដំបូងសម្រាប់ទំព័រ Edit
        updateCourses(programSelect.value, generationSelect.value, {{ $courseOffering->course_id }});

        // មុខងារបន្ថែម Row ថ្មី
        addBtn.addEventListener('click', function() {
            const index = Date.now();
            const sessionCount = document.querySelectorAll('.schedule-item').length + 1;
            
            const row = document.createElement('div');
            row.className = 'schedule-item group relative bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-green-200 transition duration-200 mb-4 animate-fadeIn';
            
            let roomOptions = rooms.map(r => `<option value="${r.id}">${r.room_number}</option>`).join('');
            let dayOptions = Object.keys(khmerDays).map(k => `<option value="${k}">${khmerDays[k]}</option>`).join('');

            row.innerHTML = `
                <div class="flex items-center mb-3 text-sm font-bold text-green-600 session-label">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                    Session ${sessionCount}
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ថ្ងៃសិក្សា</label>
                        <select name="schedules[${index}][day_of_week]" class="w-full rounded-xl border-gray-200 text-sm focus:ring-green-500" required>${dayOptions}</select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">បន្ទប់</label>
                        <select name="schedules[${index}][room_id]" class="w-full rounded-xl border-gray-200 text-sm focus:ring-green-500" required>${roomOptions}</select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ចាប់ផ្តើម</label>
                        <input type="time" name="schedules[${index}][start_time]" class="w-full rounded-xl border-gray-200 text-sm" required>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">បញ្ចប់</label>
                        <input type="time" name="schedules[${index}][end_time]" class="w-full rounded-xl border-gray-200 text-sm">
                    </div>
                </div>
                <button type="button" class="remove-schedule absolute -top-2 -right-2 bg-white text-gray-300 hover:text-red-500 rounded-full border border-gray-100 shadow-sm p-1 transition-colors duration-200 opacity-0 group-hover:opacity-100" onclick="removeRow(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            `;
            schedulesContainer.appendChild(row);
        });
    });

    // មុខងារលុប និង Update លេខ Session
    function removeRow(btn) {
        const row = btn.closest('.schedule-item');
        row.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            row.remove();
            updateSessionLabels();
        }, 200);
    }

    function updateSessionLabels() {
        document.querySelectorAll('.session-label').forEach((label, i) => {
            label.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                Session ${i + 1}
            `;
        });
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
</style>
</x-app-layout>