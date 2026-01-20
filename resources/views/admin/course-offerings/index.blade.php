<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ viewMode: 'grid' }" 
                 class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                {{-- Header & View Toggle --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('គ្រប់គ្រងការផ្តល់ជូនមុខវិជ្ជា') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះការផ្តល់ជូនមុខវិជ្ជាទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center space-x-4"> 
                        <div class="inline-flex rounded-full shadow-inner bg-gray-100 p-1">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់ប័ណ្ណ') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់តារាង') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        <a href="{{ route('admin.create-course-offering') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">{{ __('បន្ថែមថ្មី') }}</span>
                        </a>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="my-8 bg-gray-50 p-6 rounded-2xl border border-gray-200">
                    <form action="{{ route('admin.manage-course-offerings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="lg:col-span-1">
                            <label for="lecturer_id" class="block text-sm font-medium text-gray-700">{{ __('ត្រងតាមសាស្ត្រាចារ្យ') }}</label>
                            <select name="lecturer_id" id="lecturer_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">{{ __('សាស្ត្រាចារ្យទាំងអស់') }}</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="program_id" class="block text-sm font-medium text-gray-700">{{ __('ត្រងតាមកម្មវិធីសិក្សា') }}</label>
                            <select name="program_id" id="program_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">{{ __('កម្មវិធីសិក្សាទាំងអស់') }}</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name_km }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1 flex items-center space-x-2">
                            <button type="submit" class="w-full px-4 py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-sm">{{ __('ស្វែងរក') }}</button>
                            <a href="{{ route('admin.manage-course-offerings') }}" class="w-full text-center px-4 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition duration-300">{{ __('សម្អាត') }}</a>
                        </div>
                    </form>
                </div>

                {{-- Messages --}}
{{-- Modern Floating Toast --}}
@if (session('success') || session('error'))
<div 
    x-data="{ 
        show: false, 
        progress: 100,
        startTimer() {
            this.show = true;
            let interval = setInterval(() => {
                this.progress -= 1;
                if (this.progress <= 0) {
                    this.show = false;
                    clearInterval(interval);
                }
            }, 50); // 5 seconds total (50ms * 100)
        }
    }" 
    x-init="startTimer()"
    x-show="show" 
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="translate-y-12 opacity-0 sm:translate-y-0 sm:translate-x-12"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-6 right-6 z-[9999] w-full max-w-sm"
>
    <div class="relative overflow-hidden bg-white/80 backdrop-blur-xl border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.1)] rounded-2xl p-4 ring-1 ring-black/5">
        <div class="flex items-start gap-4">
            
            {{-- Modern Icon Logic --}}
            <div class="flex-shrink-0">
                @if(session('success'))
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Text Content --}}
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-bold text-gray-900 leading-tight">
                    {{ session('success') ? __('ជោគជ័យ!') : __('បរាជ័យ!') }}
                </p>
                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                    {{ session('success') ?? session('error') }}
                </p>
            </div>

            {{-- Manual Close --}}
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Progress Bar (The "Modern" Touch) --}}
        <div class="absolute bottom-0 left-0 h-1 bg-gray-100 w-full">
            <div 
                class="h-full transition-all duration-75 ease-linear {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}"
                :style="`width: ${progress}%`"
            ></div>
        </div>
    </div>
</div>
@endif

                {{-- GRID VIEW --}}
                <div x-show="viewMode === 'grid'" x-transition:enter.duration.500ms>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($courseOfferings as $offering)
                            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 relative">
                                
                                {{-- Status Badge --}}
                                @php
                                    $today = now();
                                    if ($today->lt($offering->start_date)) {
                                        $status = 'Upcoming'; $statusColor = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($today->gt($offering->end_date)) {
                                        $status = 'Finished'; $statusColor = 'bg-gray-100 text-gray-800';
                                    } else {
                                        $status = 'Active'; $statusColor = 'bg-green-100 text-green-800';
                                    }
                                @endphp
                                <div class="absolute top-6 right-6 px-3 py-1 text-xs font-bold rounded-full {{ $statusColor }}">
                                    {{ $status }}
                                </div>

                                {{-- Course Title --}}
                                <div class="flex flex-col items-start mb-4">
                                    <h4 class="text-2xl font-bold text-gray-900 leading-tight mb-1">{{ $offering->course->title_km ?? $offering->course->title }}</h4>
                                    <p class="text-base text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        {{ $offering->lecturer->name ?? 'N/A' }}
                                    </p>
                                </div>

                                {{-- Programs --}}
                                <div class="mb-4 space-y-2">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('កម្មវិធីសិក្សា និង ជំនាន់') }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($offering->targetPrograms as $program)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $program->name_km ?? $program->name }} 
                                                <span class="ml-1 text-blue-400">| Gen {{ $program->pivot->generation }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Info --}}
                                <div class="space-y-3 mb-6 text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <p class="flex justify-between">
                                        <span class="font-bold text-gray-600">{{ __('ឆ្នាំសិក្សា') }}:</span> 
                                        <span>{{ $offering->academic_year }} ({{ $offering->semester }})</span>
                                    </p>
                                    <p class="flex justify-between">
                                        <span class="font-bold text-gray-600">{{ __('សិស្ស') }}:</span>
                                        <span><span class="text-green-600 font-bold">{{ $offering->student_course_enrollments_count }}</span> / {{ $offering->capacity }}</span>
                                    </p>
                                    
                                    <div class="pt-2 border-t border-gray-200 mt-2">
                                        <p class="font-bold text-gray-600 mb-1">{{ __('កាលវិភាគ') }}:</p>
                                        <div class="space-y-1">
                                            @forelse ($offering->schedules as $schedule)
                                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                                    {{ $schedule->day_of_week }}: {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                    <span class="text-blue-500">({{ $schedule->room->room_number ?? 'N/A' }})</span>
                                                </p>
                                            @empty
                                                <p class="text-gray-400 italic text-xs">{{ __('មិនមានកាលវិភាគ') }}</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-3 mt-auto pt-4 border-t border-gray-100">
                                    <a href="{{ route('admin.edit-course-offering', $offering->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                    <button onclick="openDeleteModal({{ $offering->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white p-12 rounded-3xl text-center text-gray-400 shadow-xl border border-gray-100">
                                <p class="font-semibold text-lg">{{ __('មិនមានការផ្តល់ជូនមុខវិជ្ជាទេ') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- TABLE VIEW (UPDATED WITH ROOM) --}}
                <div x-show="viewMode === 'table'" x-transition:enter.duration.500ms style="display: none;">
                    <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ល.រ') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('មុខវិជ្ជា') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('កម្មវិធី & ជំនាន់') }}</th>
                                    {{-- 🔥 Added Column: Schedule & Room --}}
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('កាលវិភាគ & បន្ទប់') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សាស្រ្តាចារ្យ') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឆ្នាំសិក្សា') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $i = 1; @endphp
                                @foreach ($courseOfferings as $offering)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $i++ + (($courseOfferings->currentPage() - 1) * $courseOfferings->perPage()) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $offering->course->title_en ?? $offering->course->title }}</div>
                                            {{-- <div class="text-xs text-gray-500">{{ $offering->course->code }}</div> --}}
                                        </td>
                                        
                                        {{-- Programs --}}
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                @foreach($offering->targetPrograms as $program)
                                                    <span class="text-xs text-gray-700">
                                                        <span class="font-bold text-blue-600">{{ $program->name_km ?? $program->name }}</span> 
                                                        - Gen {{ $program->pivot->generation }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>

                                        {{-- 🔥 Schedule & Room Column --}}
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                @forelse($offering->schedules as $schedule)
                                                    <div class="text-xs text-gray-700">
                                                        <span class="font-bold">{{ $schedule->day_of_week }}:</span>
                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                        {{-- ✅ Corrected Access to Room --}}
                                                        <span class="text-green-600 font-bold ml-1">
                                                            (@if($schedule->room) {{ $schedule->room->room_number }} @else {{ 'N/A' }} @endif)
                                                        </span>
                                                    </div>
                                                @empty
                                                    <span class="text-xs text-gray-400 italic">{{ __('N/A') }}</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $offering->lecturer->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $offering->academic_year }}
                                            <span class="block text-xs text-gray-400">{{ $offering->semester }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.edit-course-offering', $offering->id) }}" class="text-blue-600 hover:text-blue-900">{{ __('កែប្រែ') }}</a>
                                            <button onclick="openDeleteModal({{ $offering->id }})" class="text-red-600 hover:text-red-900 ml-2">{{ __('លុប') }}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $courseOfferings->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-8 pt-8 pb-4 sm:p-8 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856a2 2 0 001.914-2.938L13.129 3.329a2 2 0 00-3.464 0L3.024 16.062A2 2 0 004.938 18z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">{{ __('លុបការផ្តល់ជូនមុខវិជ្ជា') }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">{{ __('តើអ្នកប្រាកដទេថាចង់លុបការផ្តល់ជូនមុខវិជ្ជានេះ? ការលុបនេះមិនអាចយកមកវិញបានទេ។') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-6 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-4 sm:space-x-reverse">
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-6 py-3 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">{{ __('លុប') }}</button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm">{{ __('បោះបង់') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');

        function openDeleteModal(offeringId) {
            const routeUrl = '{{ route('admin.course-offerings.destroy', ':offeringId') }}';
            deleteForm.action = routeUrl.replace(':offeringId', offeringId);
            deleteModal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }
    </script>
</x-app-layout>