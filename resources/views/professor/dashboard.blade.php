<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight">
            {{ __('ផ្ទាំងគ្រប់គ្រងរបស់លោកគ្រូអ្នកគ្រូ') }}
        </h2>
        <p class="mt-1 text-lg text-gray-500">{{ __('ទិដ្ឋភាពទូទៅនៃព័ត៌មានសំខាន់ៗ') }}</p>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100 transition-transform duration-500 ease-in-out transform hover:scale-[1.005]">

                <!-- Statistic Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                    <!-- Total Subjects Card -->
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('មុខវិជ្ជាខ្ញុំបង្រៀនសរុប') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $courseOfferings->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2h2V1a1 1 0 012 0v2h2a2 2 0 012 2v2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2H5a2 2 0 01-2-2V9a2 2 0 012-2h2V5zm3 2h2a1 1 0 100-2H7v2z" clip-rule="evenodd"></path></svg>
                    </div>

                    <!-- Total Students Card -->
                    <div class="bg-gradient-to-br from-green-600 to-green-700 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('សិស្សសរុប (ក្នុងមុខវិជ្ជាខ្ញុំ)') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $totalStudents }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>

                    <!-- Upcoming Assignments Card -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('កិច្ចការស្រាវជ្រាវដែលបានផ្តល់') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $upcomingAssignments->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-orange-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2a1 1 0 011-1h1.586l3 3H7a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path></svg>
                    </div>

                    <!-- Upcoming Exams Card -->
                    <div class="bg-gradient-to-br from-red-600 to-red-700 text-white p-6 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group">
                        <div>
                            <p class="text-sm opacity-90">{{ __('ការប្រលងដែលបានផ្តល់') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $upcomingExams->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-red-300 transition-transform duration-300 group-hover:rotate-[15deg]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
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
                            <a href="{{ route('professor.manage-assignments', ['offering_id' => $assignment->course_offering_id]) }}" class="block p-4 bg-white rounded-xl shadow-md border border-gray-100 mb-4 transition-all duration-300 transform hover:scale-[1.01] hover:shadow-lg">
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
                            <a href="{{ route('professor.manage-exams', ['offering_id' => $exam->course_offering_id]) }}" class="block p-4 bg-white rounded-xl shadow-md border border-gray-100 mb-4 transition-all duration-300 transform hover:scale-[1.01] hover:shadow-lg">
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

                   <div class="lg:col-span-2">

    <div class="bg-gray-50 p-6 rounded-2xl shadow-inner border border-gray-100 mb-10">
        <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-3 text-yellow-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path></svg>
            {{ __('សេចក្តីប្រកាស') }}
        </h4>
        @forelse ($announcements as $announcement)
            <div id="announcement-{{ $announcement->id }}" 
                 class="block p-4 bg-white rounded-xl shadow-md border border-gray-100 mb-4 transition-all duration-300 
                        transform hover:scale-[1.01] hover:shadow-lg @if($announcement->is_read) opacity-60 @endif">
                
                {{-- 💥 ធាតុសំខាន់ៗត្រូវបានដាក់ជាជង់ (STACKED CONTENT) 💥 --}}
                <div class="flex items-start">
                    
                    {{-- Icon (ដាក់នៅខាងឆ្វេងជានិច្ច) --}}
                    <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 flex items-center justify-center 
                                bg-yellow-50 text-yellow-600 rounded-full mt-1">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 5a2 2 0 10-4 0 2 2 0 004 0zM7 10a2 2 0 10-4 0 2 2 0 004 0zM17 10a2 2 0 10-4 0 2 2 0 004 0zM12 10a2 2 0 10-4 0 2 2 0 004 0z"></path>
                        </svg>
                    </div>

                    {{-- Content Section (ចំណងជើង ខ្លឹមសារ ព័ត៌មានលម្អិត) --}}
                    <div class="ml-4 flex-1">
                        
                        {{-- Title --}}
                        <p class="font-extrabold text-lg text-gray-800 mb-1">
                            {{ $announcement->title_km ?? $announcement->title_en }}
                            @if(!$announcement->is_read)
                                <span class="ml-2 inline-block h-2 w-2 bg-red-500 rounded-full animate-pulse" title="{{ __('ថ្មី') }}"></span>
                            @endif
                        </p>
                        
                        {{-- Content --}}
                        <p class="text-sm text-gray-700 mt-1 mb-3 leading-relaxed">
                            {{ $announcement->content_km ?? $announcement->content_en }}
                        </p>
                        
                        {{-- Details (Date & Poster) --}}
                        <div class="text-xs text-gray-400 border-t pt-2">
                            <p>
                                {{ __('ប្រកាសដោយ:') }} <span class="font-semibold text-gray-600">{{ $announcement->poster->name }}</span>
                            </p>
                            <p class="mt-1">
                                {{ __('ថ្ងៃទី:') }} <span class="font-semibold text-gray-600">{{ \Carbon\Carbon::parse($announcement->created_at)->format('Y-m-d H:i A') }}</span>
                            </p>
                        </div>
                    </div>
                </div> {{-- បញ្ចប់ Flex សម្រាប់ Icon & Content --}}

                {{-- Action Button (ដាក់ដាច់ដោយឡែកនៅខាងក្រោម) --}}
                <div class="mt-4 pt-4 border-t border-gray-100 text-right">
                    @if(!$announcement->is_read)
                        <button onclick="markAsRead({{ $announcement->id }})" 
                                class="text-sm text-blue-600 hover:text-blue-800 font-semibold py-1.5 px-4 
                                       rounded-full border border-blue-600 hover:bg-blue-50 transition duration-300 flex-shrink-0">
                            <i class="fas fa-check-circle mr-1"></i> {{ __('សម្គាល់ថាបានអានហើយ') }}
                        </button>
                    @else
                        <span class="text-sm text-green-600 font-semibold py-1.5 px-4 rounded-full bg-green-50 border border-green-200 flex-shrink-0">
                            <i class="fas fa-eye mr-1"></i> {{ __('បានអានហើយ') }}
                        </span>
                    @endif
                </div>

            </div>
        @empty
            <div class="text-center py-6 text-gray-500">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <p>{{ __('មិនមានសេចក្តីប្រកាសថ្មីនៅឡើយទេ។') }}</p>
            </div>
        @endforelse
    </div>
</div>

                <!-- Quick Actions Section -->
                <div class="bg-gray-50 p-8 rounded-2xl shadow-inner border border-gray-100">
                    <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        {{ __('សកម្មភាពរហ័ស') }}
                    </h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                        <a href="{{ route('professor.my-course-offerings') }}" class="flex flex-col items-center justify-center p-6 bg-blue-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2a1 1 0 011-1h1.586l3 3H7a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path></svg>
                            <span class="text-center font-semibold">{{ __('មើលមុខវិជ្ជាខ្ញុំ') }}</span>
                        </a>
                        <a href="{{ route('professor.all-assignments') }}" class="flex flex-col items-center justify-center p-6 bg-green-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2h2V1a1 1 0 012 0v2h2a2 2 0 012 2v2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2H5a2 2 0 01-2-2V9a2 2 0 012-2h2V5zm3 2h2a1 1 0 100-2H7v2z" clip-rule="evenodd"></path></svg>
                            <span class="text-center font-semibold">{{ __('គ្រប់គ្រងកិច្ចការ') }}</span>
                        </a>
                        <a href="{{ route('professor.all-exams') }}" class="flex flex-col items-center justify-center p-6 bg-purple-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                            <span class="text-center font-semibold">{{ __('គ្រប់គ្រងការប្រលង') }}</span>
                        </a>
                        <a href="{{ route('professor.all-quizzes') }}" class="flex flex-col items-center justify-center p-6 bg-yellow-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 5H4v4h12V8zm-2 6H6v-2h8v2z" clip-rule="evenodd"></path></svg>
                            <span class="text-center font-semibold">{{ __('គ្រប់គ្រង Quiz') }}</span>
                        </a>
                        <a href="{{ route('professor.all-attendance') }}" class="flex flex-col items-center justify-center p-6 bg-red-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-center font-semibold">{{ __('គ្រប់គ្រងវត្តមាន') }}</span>
                        </a>
                        <a href="{{ route('professor.all-grades') }}" class="flex flex-col items-center justify-center p-6 bg-indigo-600 text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M11 6a3 3 0 11-6 0 3 3 0 016 0zM14 9a3 3 0 11-6 0 3 3 0 016 0zM17 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-center font-semibold">{{ __('គ្រប់គ្រងពិន្ទុ') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    const readText = "{{ __('បានអានហើយ') }}";

    function markAsRead(announcementId) {
        fetch(`/professor/announcements/${announcementId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Announcement marked as read.') {
                const announcementElement = document.getElementById(`announcement-${announcementId}`);
                if (announcementElement) {
                    const newSpan = document.createElement('span');
                    newSpan.className = "text-sm text-green-600 font-semibold py-1 px-3 rounded-full bg-green-50 border border-green-200";
                    newSpan.textContent = readText; // Use textContent for a safer approach

                    announcementElement.querySelector('button').replaceWith(newSpan);
                    announcementElement.classList.add('opacity-60');
                }
            } else {
                alert('មានបញ្ហាក្នុងការសម្គាល់សេចក្តីប្រកាសថាបានអានហើយ។');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('មានបញ្ហាក្នុងការភ្ជាប់ទៅ server។');
        });
    }
</script>