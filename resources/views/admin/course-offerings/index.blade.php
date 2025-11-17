<x-app-layout>
<div class="py-12 bg-gray-100 min-h-screen">
   <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div x-data="{ viewMode: 'grid' }" 
                    class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-gray-200">
                <div>
                    <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                        {{ __('គ្រប់គ្រងការផ្តល់ជូនមុខវិជ្ជា') }}
                    </h2>
                    <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះការផ្តល់ជូនមុខវិជ្ជាទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-4"> 

                               {{-- VIEW TOGGLE BUTTONS --}}
                        <div class="inline-flex rounded-full shadow-inner bg-gray-100 p-1">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់ប័ណ្ណ') }}">
                                {{-- Grid Icon --}}
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់តារាង') }}">
                                {{-- List Icon --}}
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>


               

                             <a href="{{ route('admin.create-course-offering') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">{{ __('បន្ថែមការផ្តល់ជូនមុខវិជ្ជាថ្មី') }}</span>
                            <span class="sm:hidden">{{ __('បន្ថែម') }}</span>
                        </a>
                </div>
            </div>

            

<div class="my-8 bg-gray-50 p-6 rounded-2xl border border-gray-200">
    <form action="{{ route('admin.manage-course-offerings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        
        {{-- Search Input (Stays the same) --}}
        {{-- <div class="md:col-span-2">
            <label for="search" class="block text-sm font-medium text-gray-700">{{ __('ស្វែងរកតាមឈ្មោះមុខវិជ្ជា ឬសាស្ត្រាចារ្យ') }}</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="បញ្ចូលពាក្យគន្លឹះ...">
        </div> --}}
        {{-- 2. Lecturer Filter (Select Option) --}}
        <div class="lg:col-span-1">
            <label for="lecturer_id" class="block text-sm font-medium text-gray-700">{{ __('ត្រងតាមសាស្ត្រាចារ្យ') }}</label>
            <select name="lecturer_id" id="lecturer_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">{{ __('សាស្ត្រាចារ្យទាំងអស់') }}</option>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- Program Filter (Stays the same) --}}
        <div>
            <label for="program_id" class="block text-sm font-medium text-gray-700">{{ __('ត្រងតាមកម្មវិធីសិក្សា') }}</label>
            <select name="program_id" id="program_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">{{ __('កម្មវិធីសិក្សាទាំងអស់') }}</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name_km }}</option>
                @endforeach
            </select>
        </div>


        {{-- Submit and Clear Buttons --}}
        <div class="md:col-span-1 flex items-center space-x-2">
            <button type="submit" class="w-full px-4 py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-sm">{{ __('ស្វែងរក') }}</button>
            <a href="{{ route('admin.manage-course-offerings') }}" class="w-full text-center px-4 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition duration-300">{{ __('សម្អាត') }}</a>
        </div>
        
    </form>
</div>

                      {{-- Success/Error Messages (Existing) --}}
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('ជោគជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('បរាជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

             <div x-show="viewMode === 'grid'" x-transition:enter.duration.500ms>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($courseOfferings as $offering)
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 relative">
                        @php
                            $today = now();
                            if ($today->lt($offering->start_date)) {
                                $status = 'Upcoming';
                                $statusColor = 'bg-yellow-100 text-yellow-800';
                            } elseif ($today->gt($offering->end_date)) {
                                $status = 'Finished';
                                $statusColor = 'bg-gray-100 text-gray-800';
                            } else {
                                $status = 'Active';
                                $statusColor = 'bg-green-100 text-green-800';
                            }
                        @endphp
                        <div class="absolute top-6 right-6 px-3 py-1 text-xs font-bold rounded-full {{ $statusColor }}">
                            {{ $status }}
                        </div>

                        <div class="flex flex-col items-start mb-4">
                            <h4 class="text-2xl font-bold text-gray-900 leading-tight">{{ $offering->course->title_km ?? 'N/A' }}</h4>
                            <p class="text-base text-gray-500 mt-1">{{ $offering->lecturer->name ?? 'N/A' }}</p>
                            <p class="text-sm text-green-600 font-semibold mt-1">{{ $offering->program->name_km ?? 'N/A' }}</p>
                        </div>

                        <div class="space-y-3 mb-6 text-sm text-gray-700">
                            <p><span class="font-bold text-gray-800">{{ __('ឆ្នាំសិក្សា') }}:</span> <span class="text-gray-600">{{ $offering->academic_year }} ({{ __('ឆមាស') }} {{ $offering->semester }})</span></p>
                            
                            <div>
                                <p class="font-bold text-gray-800 mb-1">{{ __('កាលវិភាគ') }}:</p>
                                <div class="pl-4 space-y-1">
                                    @forelse ($offering->schedules as $schedule)
                                        <p class="text-gray-600">{{ $schedule->day_of_week }}: {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }} ({{ __('បន្ទប់') }}: {{ $schedule->room->room_number ?? 'N/A' }})</p>
                                    @empty
                                        <p class="text-gray-400 italic">{{ __('មិនមានកាលវិភាគ') }}</p>
                                    @endforelse
                                </div>
                            </div>

                            <p class="pt-2">
                                <p class="text-sm text-gray-600 font-semibold mt-1">សម្រាប់ជំនាន់ទី {{ $offering->generation ?? 'N/A' }}</p>

                                <span class="font-bold text-gray-800">{{ __('សិស្សចុះឈ្មោះ') }}:</span> 
                                <span class="font-bold text-xl text-green-600">{{ $offering->student_course_enrollments_count }}</span>
                                <span class="text-gray-500"> / {{ $offering->capacity }}</span>
                            </p>
                        </div>

                        <div class="flex justify-end space-x-3 mt-auto">
                            <a href="{{ route('admin.edit-course-offering', $offering->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition" title="{{ __('កែប្រែ') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                            </a>
                            {{-- Change the button type to prevent form submission and add onclick event --}}
                            <button type="button" onclick="openDeleteModal({{ $offering->id }})" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition" title="{{ __('លុប') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-12 rounded-3xl text-center text-gray-400 shadow-xl border border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h.01M8 11h.01M12 11h.01M16 11h.01M9 15h.01M13 15h.01M17 15h.01M11 19h.01M15 19h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-semibold text-lg">{{ __('មិនមានការផ្តល់ជូនមុខវិជ្ជាដែលត្រូវនឹងការស្វែងរករបស់អ្នកទេ') }}</p>
                        <p class="mt-2 text-sm">{{ __('សូមព្យាយាមម្តងទៀតដោយប្រើពាក្យគន្លឹះផ្សេង ឬសម្អាតតម្រង។') }}</p>
                    </div>
                @endforelse
            </div>
             </div>
            <div>
               {{-- TABLE VIEW --}}
                        <div x-show="viewMode === 'table'" x-transition:enter.duration.500ms style="display: none;">
                            <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider rounded-tl-xl">
                                                {{ __('លេខរៀង') }}
                                            </th>
                                             <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                                                {{ __('មុខវិជ្ជា') }}
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                {{ __('កម្នវិធីសិក្សា') }}
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                                                {{ __('សាស្រ្តាចារ្យ') }}
                                            </th>
                                              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                                                {{ __('ជំនាន់') }}
                                            </th>
                                                 <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                                                {{ __('ឆ្នាំសិក្សា') }}
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider rounded-tr-xl">
                                                {{ __('សកម្មភាព') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php $i = 1; @endphp
                                        @foreach ($courseOfferings as $offering)
                                            <tr class="hover:bg-gray-50 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-1/12">
                                                    {{ $i++ + (($courseOfferings->currentPage() - 1) * $courseOfferings->perPage()) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                                   {{ $offering->course->title_km ?? 'N/A' }}

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                                    {{ $offering->program->name_km ?? 'N/A' }}
                                                    
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                                {{ $offering->generation ?? 'N/A' }}
                                                    
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500  lg:table-cell">
                                                    {{ $offering->lecturer->name ?? 'N/A' }}
                                                </td>
                                                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500  lg:table-cell">
                                                    {{ $offering->academic_year }} ({{ __('ឆមាស') }} {{ $offering->semester }})
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                 <div class="flex justify-end space-x-3 mt-auto">
                                                        <a href="{{ route('admin.edit-course-offering', $offering->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                            </svg>
                                                        </a>
                                                        <button type="button" onclick="openDeleteModal({{ $offering->id }})" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-8 pt-8 pb-4 sm:p-8 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856a2 2 0 001.914-2.938L13.129 3.329a2 2 0 00-3.464 0L3.024 16.062A2 2 0 004.938 18z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">
                            {{ __('លុបការផ្តល់ជូនមុខវិជ្ជា') }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ __('តើអ្នកប្រាកដទេថាចង់លុបការផ្តល់ជូនមុខវិជ្ជានេះ? ការលុបនេះមិនអាចយកមកវិញបានទេ។') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-8 py-6 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-4 sm:space-x-reverse">
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-6 py-3 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                        {{ __('លុប') }}
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                    {{ __('បោះបង់') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const deleteModal = document.getElementById('delete-modal');
    const deleteForm = document.getElementById('delete-form');

    function openDeleteModal(offeringId) {
        // Use a placeholder in the route helper and then replace it with the actual ID in JS
        const routeUrl = '{{ route('admin.course-offerings.destroy', ':offeringId') }}';
        deleteForm.action = routeUrl.replace(':offeringId', offeringId);
        // Show the modal
        deleteModal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        // Hide the modal
        deleteModal.classList.add('hidden');
    }
</script>

</x-app-layout>