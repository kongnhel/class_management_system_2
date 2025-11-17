<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">{{ __('បង្កើតការផ្តល់ជូនមុខវិជ្ជាថ្មី') }}</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-3xl p-6 sm:p-8 lg:p-12">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <strong class="font-bold">{{ __('មានបញ្ហា!') }}</strong>
                                <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
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

                <form method="POST" action="{{ route('admin.store-course-offering') }}">
                    @csrf
                    <div class="space-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-10">
                            <div>
                                <label for="program_id" class="block text-sm font-medium text-gray-700">{{ __('កម្មវិធីសិក្សា') }}<span class="text-red-500">*</span></label>
                                <select id="program_id" name="program_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                                    <option value="">{{ __('ជ្រើសរើសកម្មវិធីសិក្សា') }}</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name_km ?? $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="generation" class="block text-sm font-medium text-gray-700">{{ __('ជំនាន់') }}<span class="text-red-500">*</span></label>
                                <select id="generation" name="generation" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                                    <option value="">{{ __('ជ្រើសរើសជំនាន់') }}</option>
                                    @foreach ($generations as $generation)
                                        <option value="{{ $generation }}">{{ $generation }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="course_id" class="block text-sm font-medium text-gray-700">{{ __('មុខវិជ្ជា') }}<span class="text-red-500">*</span></label>
                                <select id="course_id" name="course_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required disabled>
                                    <option value="">{{ __('សូមជ្រើសរើសកម្មវិធីសិក្សានិងជំនាន់សិន') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">
                            <div class="bg-gray-50 p-6 rounded-2xl shadow-inner">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-200 pb-2">{{ __('ព័ត៌មានការផ្តល់ជូនមុខវិជ្ជា') }}</h3>
                                <div class="space-y-6">
                                    <div>
                                        <label for="lecturer_user_id" class="block text-sm font-medium text-gray-700">{{ __('សាស្រ្តាចារ្យ') }}</label>
                                        <select id="lecturer_user_id" name="lecturer_user_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                            <option value="">{{ __('ជ្រើសរើសសាស្រ្តាចារ្យ') }}</option>
                                            @foreach ($professors as $professor)
                                                <option value="{{ $professor->id }}" {{ old('lecturer_user_id') == $professor->id ? 'selected' : '' }}>{{ $professor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('lecturer_user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="academic_year" class="block text-sm font-medium text-gray-700">{{ __('ឆ្នាំសិក្សា') }}</label>
                                            <input type="text" name="academic_year" id="academic_year" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('academic_year') }}" placeholder="ឧទាហរណ៍: 2024-2025" required>
                                            @error('academic_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="semester" class="block text-sm font-medium text-gray-700">{{ __('ឆមាស') }}</label>
                                            <input type="text" name="semester" id="semester" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('semester') }}" placeholder="ឧទាហរណ៍: 1" required>
                                            @error('semester') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="capacity" class="block text-sm font-medium text-gray-700">{{ __('ចំនួនអតិបរមា') }}</label>
                                            <input type="number" name="capacity" id="capacity" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('capacity') }}" required>
                                            @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="flex items-end pb-1">
                                            <label for="is_open_for_self_enrollment" class="inline-flex items-center">
                                                <input type="checkbox" name="is_open_for_self_enrollment" id="is_open_for_self_enrollment" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" {{ old('is_open_for_self_enrollment') ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">{{ __('អនុញ្ញាតឱ្យចុះឈ្មោះដោយខ្លួនឯង') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('កាលបរិច្ឆេទចាប់ផ្តើម') }}</label>
                                            <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('start_date') }}" required>
                                            @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('កាលបរិច្ឆេទបញ្ចប់') }}</label>
                                            <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('end_date') }}" required>
                                            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-2xl shadow-inner border border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-200 pb-2">{{ __('កាលវិភាគ') }}</h3>
                                <div id="schedules-container" class="space-y-6">
                                    {{-- Schedule items will be inserted here by JavaScript --}}
                                </div>
                                <button type="button" id="add-schedule" class="mt-6 px-6 py-2 bg-green-600 text-white font-semibold rounded-full hover:bg-green-700 transition duration-300 shadow-md">
                                    {{ __('បន្ថែមទៀត') }} ➕
                                </button>
                                @error('schedules') <p class="text-red-500 text-xs mt-2">{{ __('សូមបន្ថែមយ៉ាងហោចណាស់កាលវិភាគមួយ។') }}</p> @enderror
                            </div>
                        </div>
                     <div class="mt-12 flex justify-between items-center">
                        <a href="{{ route('admin.manage-course-offerings') }}" class="px-6 py-3 text-gray-600 font-semibold rounded-full hover:bg-gray-200 transition duration-300 transform hover:scale-105">{{ __('បោះបង់') }}</a>
                        
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                                     {{ __('បង្កើតការផ្តល់ជូនមុខវិជ្ជា') }} ✅
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                         
                    </div>
                      
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Schedule Management Logic ---
            const container = document.getElementById('schedules-container');
            const addButton = document.getElementById('add-schedule');
            const rooms = {!! json_encode($rooms) !!}; // 💡 ឥឡូវនេះទិន្នន័យបន្ទប់ត្រូវបានបញ្ជូនត្រឹមត្រូវ
            let scheduleIndex = 0;

            function addScheduleRow(initialData = {}) {
                const scheduleDiv = document.createElement('div');
                scheduleDiv.className = 'schedule-item grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-x-4 gap-y-2 items-end pb-4 border-b border-gray-100 last:border-b-0';
                
                const roomOptions = rooms.map(room => `<option value="${room.id}" ${initialData.room_id == room.id ? 'selected' : ''}>${room.room_number}</option>`).join('');

                scheduleDiv.innerHTML = `
                    <div class="col-span-2 sm:col-span-4 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">{{ __('ថ្ងៃ') }}</label>
                        <select name="schedules[${scheduleIndex}][day_of_week]" class="w-full mt-1 px-4 py-2 border rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500" required>
                            <option value="">{{ __('ជ្រើសរើសថ្ងៃ') }}</option>
                            <option value="Monday" ${initialData.day_of_week === 'Monday' ? 'selected' : ''}>{{ __('ថ្ងៃច័ន្ទ') }}</option>
                            <option value="Tuesday" ${initialData.day_of_week === 'Tuesday' ? 'selected' : ''}>{{ __('ថ្ងៃអង្គារ') }}</option>
                            <option value="Wednesday" ${initialData.day_of_week === 'Wednesday' ? 'selected' : ''}>{{ __('ថ្ងៃពុធ') }}</option>
                            <option value="Thursday" ${initialData.day_of_week === 'Thursday' ? 'selected' : ''}>{{ __('ថ្ងៃព្រហស្បតិ៍') }}</option>
                            <option value="Friday" ${initialData.day_of_week === 'Friday' ? 'selected' : ''}>{{ __('ថ្ងៃសុក្រ') }}</option>
                            <option value="Saturday" ${initialData.day_of_week === 'Saturday' ? 'selected' : ''}>{{ __('ថ្ងៃសៅរ៍') }}</option>
                            <option value="Sunday" ${initialData.day_of_week === 'Sunday' ? 'selected' : ''}>{{ __('ថ្ងៃអាទិត្យ') }}</option>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">{{ __('បន្ទប់') }}</label>
                        <select name="schedules[${scheduleIndex}][room_id]" class="w-full mt-1 px-4 py-2 border rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500" required>
                            <option value="">{{ __('ជ្រើសរើសបន្ទប់') }}</option>
                            ${roomOptions}
                        </select>
                    </div>
                    <div class="col-span-1 sm:col-span-1 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">{{ __('ម៉ោងចាប់ផ្តើម') }}</label>
                        <input type="time" name="schedules[${scheduleIndex}][start_time]" class="w-full mt-1 px-4 py-2 border rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500" value="${initialData.start_time || ''}" required>
                    </div>
                    <div class="col-span-1 sm:col-span-1 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">{{ __('ម៉ោងបញ្ចប់') }}</label>
                        <input type="time" name="schedules[${scheduleIndex}][end_time]" class="w-full mt-1 px-4 py-2 border rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500" value="${initialData.end_time || ''}" required>
                    </div>
                    <div class="col-span-2 sm:col-span-4 lg:col-span-1 flex justify-end">
                        <button type="button" class="remove-schedule text-gray-400 hover:text-red-500 transition-colors duration-200 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                container.appendChild(scheduleDiv);
                scheduleDiv.querySelector('.remove-schedule').addEventListener('click', function() { scheduleDiv.remove(); });
                scheduleIndex++;
            }
            addButton.addEventListener('click', () => addScheduleRow());
            const oldSchedules = {!! json_encode(old('schedules', [])) !!};
            if (oldSchedules.length > 0) {
                oldSchedules.forEach(schedule => addScheduleRow(schedule));
            } else {
                addScheduleRow();
            }

            // --- NEW: Dependent Dropdown Logic (Fixed for Generation) ---
            const programSelect = document.getElementById('program_id');
            const generationSelect = document.getElementById('generation');
            const courseSelect = document.getElementById('course_id');

            function fetchAndPopulateCourses() {
                const programId = programSelect.value;
                const generation = generationSelect.value;
                
                courseSelect.innerHTML = '<option value="">{{ __("កំពុងផ្ទុក...") }}</option>';
                courseSelect.disabled = true;

                if (!programId || !generation) {
                    courseSelect.innerHTML = '<option value="">{{ __("សូមជ្រើសរើសកម្មវិធីសិក្សានិងជំនាន់សិន") }}</option>';
                    return;
                }

                fetch(`/admin/get-courses-by-program-and-generation?program_id=${programId}&generation=${generation}`)
                    .then(response => response.json())
                    .then(courses => {
                        courseSelect.innerHTML = '<option value="">{{ __("ជ្រើសរើសមុខវិជ្ជា") }}</option>';
                        if (courses.length > 0) {
                            courses.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = `${course.title_km}`;
                                courseSelect.appendChild(option);
                            });
                        } else {
                            courseSelect.innerHTML = '<option value="">{{ __("មិនមានមុខវិជ្ជាសម្រាប់កម្មវិធីសិក្សានិងជំនាន់នេះទេ") }}</option>';
                        }
                        courseSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching courses:', error);
                        courseSelect.innerHTML = '<option value="">{{ __("មានបញ្ហាក្នុងការផ្ទុកមុខវិជ្ជា") }}</option>';
                    });
            }

            // Listen for changes on both program and generation selects
            programSelect.addEventListener('change', fetchAndPopulateCourses);
            generationSelect.addEventListener('change', fetchAndPopulateCourses);
            
            fetchAndPopulateCourses();
        });
    </script>
</x-app-layout>