<x-app-layout>
    <div class="min-h-screen bg-gray-100 font-sans text-gray-900">
        
        {{-- DARK HEADER SECTION (Overlapping Layout) --}}
        <div class="bg-slate-900 text-white pb-32 pt-12 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider border border-blue-500/30">
                                Academic Year {{ date('Y') }}
                            </span>
                        </div>
                        <h2 class="text-3xl font-extrabold tracking-tight text-white">
                            {{ __('ការផ្តល់ជូនមុខវិជ្ជា') }}
                        </h2>
                        <p class="text-slate-400 mt-2 max-w-2xl text-sm leading-relaxed">
                            {{ __('គ្រប់គ្រង និងតាមដានការបែងចែកមុខវិជ្ជាទៅតាមជំនាញ សាស្ត្រាចារ្យ និងកាលវិភាគសិក្សា។') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3" x-data="{ viewMode: '{{ request('view', 'grid') }}' }">
                        {{-- View Toggle (Dark Theme) --}}
                        <div class="bg-slate-800 p-1 rounded-lg border border-slate-700 flex">
                            <button @click="viewMode = 'grid'; $dispatch('view-changed', 'grid')" 
                                    :class="viewMode === 'grid' ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200'" 
                                    class="p-2 rounded-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            </button>
                            <button @click="viewMode = 'table'; $dispatch('view-changed', 'table')" 
                                    :class="viewMode === 'table' ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200'" 
                                    class="p-2 rounded-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            </button>
                        </div>

                        {{-- Primary Action --}}
                        <a href="{{ route('admin.create-course-offering') }}" 
                           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg font-bold shadow-lg shadow-blue-900/50 transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>{{ __('បន្ថែមថ្មី') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 pb-12 relative z-10">
            
            {{-- FLOATING FILTER CARD --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-5 mb-8">
                <form action="{{ route('admin.manage-course-offerings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                    <div class="md:col-span-4">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('ស្វែងរកមុខវិជ្ជា') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរក..." class="pl-10 block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 transition-all">
                        </div>
                    </div>
                    
                    <div class="md:col-span-3">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('កម្មវិធីសិក្សា') }}</label>
                        <select name="program_id" class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 transition-all">
                            <option value="">{{ __('បង្ហាញទាំងអស់') }}</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name_km }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 block">{{ __('សាស្ត្រាចារ្យ') }}</label>
                        <select name="lecturer_id" class="block w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 transition-all">
                            <option value="">{{ __('បង្ហាញទាំងអស់') }}</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-2.5 rounded-lg transition-colors shadow-md">
                            {{ __('ត្រង') }}
                        </button>
                        <a href="{{ route('admin.manage-course-offerings') }}" class="px-3 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-lg transition-colors" title="Reset">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    </div>
                </form>
            </div>

            {{-- NOTIFICATIONS --}}
            @if (session('success') || session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-8 bg-white border-l-4 {{ session('success') ? 'border-green-500' : 'border-red-500' }} rounded-r-xl shadow-md p-4 flex items-start justify-between animate-fade-in-down">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            @if(session('success')) 
                                <div class="bg-green-100 p-1.5 rounded-full"><svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                            @else 
                                <div class="bg-red-100 p-1.5 rounded-full"><svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">{{ session('success') ? 'Success' : 'Error' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ session('success') ?? session('error') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
            @endif

            {{-- MAIN CONTENT --}}
            <div x-data="{ viewMode: '{{ request('view', 'grid') }}' }" @view-changed.window="viewMode = $event.detail">
                
                {{-- GRID VIEW --}}
                <div x-show="viewMode === 'grid'">
                    @php
                        // Grouping Logic
                        $groupedOfferings = collect();
                        foreach ($courseOfferings as $offering) {
                            if ($offering->targetPrograms->isEmpty()) {
                                $key = 'Other Programs|N/A';
                                $lecturerName = $offering->lecturer->name ?? 'Unassigned';
                                if (!isset($groupedOfferings[$key])) $groupedOfferings[$key] = collect();
                                if (!isset($groupedOfferings[$key][$lecturerName])) $groupedOfferings[$key][$lecturerName] = collect();
                                $groupedOfferings[$key][$lecturerName]->push($offering);
                            } else {
                                foreach ($offering->targetPrograms as $program) {
                                    $key = ($program->name_km ?? $program->name) . '|' . $program->pivot->generation;
                                    $lecturerName = $offering->lecturer->name ?? 'Unassigned';
                                    if (!isset($groupedOfferings[$key])) $groupedOfferings[$key] = collect();
                                    if (!isset($groupedOfferings[$key][$lecturerName])) $groupedOfferings[$key][$lecturerName] = collect();
                                    $groupedOfferings[$key][$lecturerName]->push($offering);
                                }
                            }
                        }
                        $groupedOfferings = $groupedOfferings->sortKeys();
                    @endphp

                    <div class="space-y-12">
                        @forelse ($groupedOfferings as $programKey => $lecturers)
                            @php [$programName, $generation] = explode('|', $programKey); @endphp
                            
                            <div>
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $programName }}</h3>
                                    @if($generation != 'N/A')
                                        <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                            Generation {{ $generation }}
                                        </span>
                                    @endif
                                    <div class="h-px bg-gray-200 flex-1 ml-4"></div>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                                    @foreach ($lecturers as $lecturerName => $offerings)
                                        <div class="col-span-full">
                                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 pl-1">{{ $lecturerName }}</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                                @foreach ($offerings as $offering)
                                                    @php $isActive = now()->between($offering->start_date, $offering->end_date); @endphp
                                                    
                                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-0 hover:shadow-lg hover:border-blue-300 transition-all duration-300 flex flex-col group overflow-hidden">
                                                        
                                                        {{-- Card Header Status --}}
                                                        <div class="h-1.5 w-full {{ $isActive ? 'bg-green-500' : 'bg-gray-300' }}"></div>

                                                        <div class="p-5 flex-1">
                                                            <div class="flex justify-between items-start mb-3">
                                                                <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                                                    Yr {{ $offering->academic_year }} / Sem {{ $offering->semester }}
                                                                </span>
                                                                
                                                                {{-- Action Menu (visible on hover) --}}
                                                                <div class="flex gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                                                    <a href="{{ route('admin.edit-course-offering', $offering->id) }}" class="text-gray-400 hover:text-blue-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>
                                                                    <button onclick="openDeleteModal({{ $offering->id }})" class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                                </div>
                                                            </div>

                                                            <h4 class="text-lg font-bold text-gray-900 mb-4 leading-snug">
                                                                {{ $offering->course->title_km ?? $offering->course->title }}
                                                            </h4>

                                                            <div class="space-y-2.5">
                                                                @forelse ($offering->schedules->take(2) as $schedule)
                                                                    <div class="flex items-center text-sm">
                                                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-xs mr-3">
                                                                            {{ substr($schedule->day_of_week, 0, 3) }}
                                                                        </div>
                                                                        <div class="flex flex-col">
                                                                            <span class="text-gray-900 font-medium text-xs">
                                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                                            </span>
                                                                            <span class="text-gray-400 text-[10px]">Room {{ $schedule->room->room_number ?? 'N/A' }}</span>
                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <div class="flex items-center text-sm p-2 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                                                        <span class="text-gray-400 text-xs italic mx-auto">No schedule set</span>
                                                                    </div>
                                                                @endforelse
                                                            </div>
                                                        </div>

                                                        <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex items-center gap-2">
                                                            <div class="flex -space-x-1.5 overflow-hidden">
                                                                <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-slate-200"></div>
                                                                <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-slate-300"></div>
                                                            </div>
                                                            <span class="text-xs font-medium text-gray-500">
                                                                {{ $offering->student_course_enrollments_count }} Enrolled
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-200">
                                <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">មិនមានទិន្នន័យ</h3>
                                <p class="text-gray-500 mt-1">សូមព្យាយាមស្វែងរកពាក្យគន្លឹះផ្សេង ឬបន្ថែមការផ្តល់ជូនថ្មី។</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- TABLE VIEW --}}
                <div x-show="viewMode === 'table'" style="display: none;">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Target Groups</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Schedule</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lecturer</th>
                                        <th scope="col" class="relative px-6 py-4"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($courseOfferings as $offering)
                                        <tr class="hover:bg-blue-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $offering->course->title_km ?? $offering->course->title }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $offering->academic_year }} (Sem {{ $offering->semester }})</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col gap-1">
                                                    @foreach($offering->targetPrograms as $program)
                                                        <span class="inline-flex items-center text-xs text-gray-600">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-2"></span>
                                                            {{ $program->name_km ?? $program->name }} (Gen {{ $program->pivot->generation }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    @foreach($offering->schedules as $schedule)
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-mono font-bold text-xs bg-gray-100 px-1 rounded">{{ substr($schedule->day_of_week, 0, 3) }}</span>
                                                            <span class="text-xs">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                                {{ $offering->lecturer->name ?? 'Unassigned' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.edit-course-offering', $offering->id) }}" class="text-blue-600 hover:text-blue-900 mr-4 font-bold">Edit</a>
                                                <button onclick="openDeleteModal({{ $offering->id }})" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $courseOfferings->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div id="delete-modal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856a2 2 0 001.914-2.938L13.129 3.329a2 2 0 00-3.464 0L3.024 16.062A2 2 0 004.938 18z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">លុបការផ្តល់ជូនមុខវិជ្ជា</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">តើអ្នកប្រាកដទេថាចង់លុបទិន្នន័យនេះ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                            យល់ព្រមលុប
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        បោះបង់
                    </button>
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