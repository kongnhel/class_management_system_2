<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('ផ្ទាំងគ្រប់គ្រងនិស្សិត') }}
        </h2>
    </x-slot> --}}

    <div class="py-6 sm:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> {{-- Added px-4 for extra small screens --}}
            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl p-6 sm:p-8 lg:p-10 transform transition-all duration-300 hover:shadow-3xl border-2 border-gray-100">
                <h3 class="text-3xl sm:text-4xl font-extrabold text-green-700 mb-6 sm:mb-8 text-center animate-fade-in"> {{-- Reduced font for mobile --}}
                    ជំរាបសួរ និស្សិត {{ $user->name }}! 👋
                </h3>

                {{-- Quick Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-12"> {{-- Reduced gap for mobile --}}
                    {{-- Upcoming Assignments --}}
                    <a href="{{ route('student.my-grades') }}" class="group block">
                        <div class="bg-gradient-to-br from-green-500 to-green-700 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col items-start justify-between transform transition-transform duration-500 group-hover:scale-105 group-hover:shadow-2xl relative overflow-hidden h-full"> {{-- Reduced padding for mobile --}}
                            <div class="absolute top-0 right-0 -mr-4 -mt-4 text-white opacity-20 transition-all duration-500 group-hover:opacity-40">
                                <svg class="w-28 h-28 sm:w-32 sm:h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <p class="text-xs sm:text-sm font-light uppercase tracking-wide opacity-90 mb-1 sm:mb-2">{{ __('មានកិច្ចការ') }}</p>
                            <h2 class="text-4xl sm:text-5xl font-extrabold mt-0 mb-3 sm:mb-4 transition-all duration-500">{{ $upcomingAssignments->count() }}</h2> {{-- Reduced font for mobile --}}
                            @if($upcomingAssignments->isNotEmpty())
                                <ul class="mt-2 text-sm sm:text-base space-y-1 sm:space-y-2 opacity-95"> {{-- Reduced font and spacing for mobile --}}
                                    @foreach($upcomingAssignments->take(2) as $assignment)
                                        <li class="flex items-center">
                                            <span class="mr-2 text-white opacity-80">▪</span> {{ $assignment->title_km ?? $assignment->title_en }}
                                        </li>
                                    @endforeach
                                </ul>
                                @if($upcomingAssignments->count() > 2)
                                    <p class="text-xs sm:text-sm mt-2 sm:mt-3 opacity-80 font-light">{{ __('និងច្រើនទៀត...') }}</p>
                                @endif
                            @else
                                <p class="mt-2 text-sm sm:text-base opacity-90">{{ __('មិនមានកិច្ចការជិតមកដល់ទេ។') }}</p>
                            @endif
                        </div>
                    </a>

                    {{-- Upcoming Exams (Repeated logic for other 3 cards, just showing the first as example) --}}
                    {{-- Upcoming Exams --}}
                    <a href="{{ route('student.my-grades') }}" class="group block">
                        <div class="bg-gradient-to-br from-red-500 to-red-700 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col items-start justify-between transform transition-transform duration-500 group-hover:scale-105 group-hover:shadow-2xl relative overflow-hidden h-full">
                            <div class="absolute top-0 right-0 -mr-4 -mt-4 text-white opacity-20 transition-all duration-500 group-hover:opacity-40">
                                <svg class="w-28 h-28 sm:w-32 sm:h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <p class="text-xs sm:text-sm font-light uppercase tracking-wide opacity-90 mb-1 sm:mb-2">{{ __('ការប្រឡងជិតមកដល់') }}</p>
                            <h2 class="text-4xl sm:text-5xl font-extrabold mt-0 mb-3 sm:mb-4 transition-all duration-500">{{ $upcomingExams->count() }}</h2>
                            @if($upcomingExams->isNotEmpty())
                                <ul class="mt-2 text-sm sm:text-base space-y-1 sm:space-y-2 opacity-95">
                                    @foreach($upcomingExams->take(2) as $exam)
                                        <li class="flex items-center">
                                            <span class="mr-2 text-white opacity-80">▪</span> {{ $exam->title_km ?? $exam->title_en }}
                                        </li>
                                    @endforeach
                                </ul>
                                @if($upcomingExams->count() > 2)
                                    <p class="text-xs sm:text-sm mt-2 sm:mt-3 opacity-80 font-light">{{ __('និងច្រើនទៀត...') }}</p>
                                @endif
                            @else
                                <p class="mt-2 text-sm sm:text-base opacity-90">{{ __('មិនមានការប្រឡងជិតមកដល់ទេ។') }}</p>
                            @endif
                        </div>
                    </a>

                    {{-- Upcoming Schedules --}}
                    <a href="{{ route('student.my-schedule') }}" class="group block">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-700 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col items-start justify-between transform transition-transform duration-500 group-hover:scale-105 group-hover:shadow-2xl relative overflow-hidden h-full">
                            <div class="absolute top-0 right-0 -mr-4 -mt-4 text-white opacity-20 transition-all duration-500 group-hover:opacity-40">
                                <svg class="w-28 h-28 sm:w-32 sm:h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-xs sm:text-sm font-light uppercase tracking-wide opacity-90 mb-1 sm:mb-2">{{ __('កាលវិភាគជិតមកដល់') }}</p>
                            <h2 class="text-4xl sm:text-5xl font-extrabold mt-0 mb-3 sm:mb-4 transition-all duration-500">{{ $upcomingSchedules->count() }}</h2>
                            @if($upcomingSchedules->isNotEmpty())
                                <ul class="mt-2 text-sm sm:text-base space-y-1 sm:space-y-2 opacity-95">
                                    @foreach($upcomingSchedules->take(2) as $schedule)
                                        <li class="flex items-center">
                                            <span class="mr-2 text-white opacity-80">▪</span> {{ $schedule->courseOffering->course->title_km ?? $schedule->courseOffering->course->title_en }}
                                        </li>
                                    @endforeach
                                </ul>
                                @if($upcomingSchedules->count() > 2)
                                    <p class="text-xs sm:text-sm mt-2 sm:mt-3 opacity-80 font-light">{{ __('និងច្រើនទៀត...') }}</p>
                                @endif
                            @else
                                <p class="mt-2 text-sm sm:text-base opacity-90">{{ __('មិនមានកាលវិភាគជិតមកដល់ទេ។') }}</p>
                            @endif
                        </div>
                    </a>

                    {{-- Total Enrolled Courses --}}
                    <a href="{{ route('student.my-enrolled-courses') }}" class="group block">
                        <div class="bg-gradient-to-br from-teal-500 to-teal-700 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col items-start justify-between transform transition-transform duration-500 group-hover:scale-105 group-hover:shadow-2xl relative overflow-hidden h-full">
                            <div class="absolute top-0 right-0 -mr-4 -mt-4 text-white opacity-20 transition-all duration-500 group-hover:opacity-40">
                                <svg class="w-28 h-28 sm:w-32 sm:h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.206 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.794 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.794 5 16.5 5c1.706 0 3.332.477 4.5 1.253v13C19.832 18.477 18.206 18 16.5 18c-1.706 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <p class="text-xs sm:text-sm font-light uppercase tracking-wide opacity-90 mb-1 sm:mb-2">{{ __('មុខវិជ្ជាបានចុះឈ្មោះសរុប') }}</p>
                            <h2 class="text-4xl sm:text-5xl font-extrabold mt-0 mb-3 sm:mb-4 transition-all duration-500">{{ $enrollments->count() }}</h2>
                            <p class="mt-2 text-sm sm:text-base opacity-90">{{ __('មើលបញ្ជីមុខវិជ្ជាទាំងអស់ដែលអ្នកបានចុះឈ្មោះ។') }}</p>
                        </div>
                    </a>
                </div>

                {{-- Unified Announcements & Notifications Section --}}
                <h4 class="text-2xl sm:text-3xl font-bold text-green-700 mb-4 sm:mb-6 border-b-2 border-green-200 pb-2 sm:pb-3 mt-8 sm:mt-12"> {{-- Reduced font and spacing for mobile --}}
                    {{ __('សេចក្តីប្រកាស & ការជូនដំណឹង') }}
                </h4>
                <div class="space-y-4 sm:space-y-6"> {{-- Reduced vertical spacing for mobile --}}
                    @forelse ($combinedFeed as $item)
                        @php
                            $isAnnouncement = ($item->type === 'announcement');
                            $icon = $isAnnouncement ? '📌' : '🔔';
                            $bgColor = $isAnnouncement ? 'bg-green-50' : 'bg-yellow-50';
                            $borderColor = $isAnnouncement ? 'border-green-200' : 'border-yellow-200';
                            $sentBy = $isAnnouncement ? ($item->poster->name ?? 'N/A') : 'គ្រូបង្រៀន';
                            // ប្រើ is_read ដែលបានកំណត់នៅក្នុង Controller
                            $isRead = $item->is_read;
                        @endphp
                        {{-- Use flex-col on mobile for button placement, then revert to items-center on larger screens --}}
                        <div id="{{ $item->type }}-{{ $item->id }}" class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 p-4 rounded-xl shadow-lg border-2 {{ $bgColor }} {{ $borderColor }} transition-all duration-300 transform hover:scale-105">
                            <div class="flex-shrink-0 text-2xl sm:text-3xl"> {{-- Reduced icon size slightly for mobile --}}
                                {{ $icon }}
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-start justify-between">
                                    <h5 class="text-base sm:text-lg font-bold text-gray-800">{{ $item->title }}</h5> {{-- Reduced font for mobile --}}
                                    <span class="text-xs text-gray-500 ml-2">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $item->content }}</p> {{-- Added line-clamp-2 to make content concise on mobile --}}
                                <p class="text-xs text-gray-500 mt-2">ផ្ញើដោយ: {{ $sentBy }}</p>
                            </div>
                            <div class="flex-shrink-0 mt-2 sm:mt-0">
                                @if(!$isRead)
                                    <button onclick="markAsRead('{{ $item->type }}', '{{ $item->id }}')" class="w-full sm:w-auto bg-blue-500 text-white text-xs sm:text-sm font-semibold py-1 sm:py-2 px-3 sm:px-4 rounded-full shadow-md hover:bg-blue-600 transition duration-300 whitespace-nowrap"> {{-- Made button smaller on mobile and full width --}}
                                        {{ __('សម្គាល់ថាបានអាន') }}
                                    </button>
                                @else
                                    <span class="text-xs sm:text-sm text-green-600 font-semibold py-1 px-3 rounded-full bg-green-50 border border-green-200 whitespace-nowrap">
                                        {{ __('បានអានហើយ') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-6 sm:p-8 bg-gray-100 rounded-xl shadow-inner">
                            <p class="text-lg sm:text-xl font-semibold text-gray-600">{{ __('មិនមានសេចក្តីប្រកាស ឬការជូនដំណឹងថ្មីៗទេ។') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Student's Program and Recommended Courses --}}
                @if ($studentProgram)
                    <h4 class="text-2xl sm:text-3xl font-bold text-green-700 mb-4 sm:mb-6 border-b-2 border-green-200 pb-2 sm:pb-3 mt-8 sm:mt-12">
                        {{ __('កម្មវិធីសិក្សារបស់ខ្ញុំ:') }} {{ $studentProgram->name_km }}
                        ||ជំនាន់: <span class="font-bold text-green-800">{{ $user->generation }}</span>
                    </h4>
                    <div class="bg-gray-50 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 mb-8 sm:mb-12 border border-gray-200">
                        @if ($availableCoursesInProgram->isNotEmpty())
                            <h5 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 sm:mb-4">{{ __('មុខវិជ្ជាដែលបានណែនាំក្នុងកម្មវិធីសិក្សា') }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6"> {{-- Reduced gap for mobile --}}
                                @foreach ($availableCoursesInProgram as $courseOffering)
                                    <div class="bg-green-50 p-4 sm:p-6 rounded-xl shadow-md border border-green-100 flex flex-col justify-between transform transition-transform duration-200 hover:scale-102">
                                        <div>
                                            <h6 class="text-lg sm:text-xl font-bold text-green-800 mb-1 sm:mb-2">{{ $courseOffering->course->title_km ?? $courseOffering->course->title_en }}</h6>
                                            <p class="text-gray-700 text-xs sm:text-sm mb-0 sm:mb-1">{{ __('លេខកូដ:') }} {{ $courseOffering->course->code }}</p>
                                            <p class="text-gray-700 text-xs sm:text-sm mb-0 sm:mb-1">{{ __('សាស្ត្រាចារ្យ:') }} {{ $courseOffering->lecturer->name }}</p>
                                            <p class="text-gray-700 text-xs sm:text-sm">{{ __('ឆមាស:') }} {{ $courseOffering->semester }}, {{ __('ឆ្នាំសិក្សា:') }} {{ $courseOffering->academic_year }}</p>
                                        </div>
                                        <form action="{{ route('student.enroll_self') }}" method="POST" class="mt-3 sm:mt-4">
                                            @csrf
                                            <input type="hidden" name="course_offering_id" value="{{ $courseOffering->id }}">
                                            <x-primary-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 text-sm"> {{-- Reduced font size for button --}}
                                                {{ __('ចុះឈ្មោះ') }}
                                            </x-primary-button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-4 sm:py-6 text-gray-500 bg-gray-50 rounded-xl border border-gray-200">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mb-2 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="text-base sm:text-lg">{{ __('មិនមានមុខវិជ្ជាណាមួយដែលត្រូវណែនាំក្នុងកម្មវិធីសិក្សារបស់អ្នកនៅពេលនេះទេ។') }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <h4 class="text-2xl sm:text-3xl font-bold text-green-700 mb-4 sm:mb-6 border-b-2 border-green-200 pb-2 sm:pb-3 mt-8 sm:mt-12">{{ __('កម្មវិធីសិក្សារបស់ខ្ញុំ') }}</h4>
                    <div class="flex flex-col items-center justify-center py-6 sm:py-8 text-gray-600 bg-gray-50 rounded-xl shadow-sm border border-gray-200">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mb-2 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <p class="text-lg sm:text-xl font-semibold mb-1 sm:mb-2">{{ __('អ្នកមិនទាន់បានជ្រើសរើសកម្មវិធីសិក្សានៅឡើយទេ។') }}</p>
                        <p class="text-sm sm:text-base text-gray-500">{{ __('សូមទាក់ទងការិយាល័យរដ្ឋបាលដើម្បីចុះឈ្មោះក្នុងកម្មវិធីសិក្សា។') }}</p>
                    </div>
                @endif

                {{-- Enrolled Courses Section --}}
                <h4 class="text-2xl sm:text-3xl font-bold text-green-700 mb-4 sm:mb-6 border-b-2 border-green-200 pb-2 sm:pb-3 mt-8 sm:mt-12">{{ __('មុខវិជ្ជាដែលបានចុះឈ្មោះ') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6"> {{-- Reduced gap for mobile --}}
                    @forelse($enrollments as $enrollment)
                        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border border-gray-200 flex flex-col justify-between transform transition-transform duration-200 hover:scale-[1.02] hover:shadow-xl">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1">{{ __('លេខកូដមុខវិជ្ជា:') }}</p>
                                <h5 class="text-lg sm:text-xl font-extrabold text-green-800 mb-2 sm:mb-3">{{ $enrollment->courseOffering->course->code }}</h5>

                                <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1">{{ __('ចំណងជើងមុខវិជ្ជា:') }}</p>
                                <p class="text-base sm:text-lg font-bold text-gray-800 mb-2 sm:mb-3">{{ $enrollment->courseOffering->course->title_km ?? $enrollment->courseOffering->course->title_en }}</p>

                                <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1">{{ __('សាស្ត្រាចារ្យ:') }}</p>
                                <p class="text-sm sm:text-base text-gray-700 mb-2 sm:mb-3">{{ $enrollment->courseOffering->lecturer->name }}</p>

                                <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600 border-t border-gray-100 pt-2 sm:pt-3 mt-2 sm:mt-3">
                                    <span>{{ __('ឆ្នាំសិក្សា:') }} <span class="font-semibold text-gray-800">{{ $enrollment->courseOffering->academic_year }}</span></span>
                                    <span>{{ __('ឆមាស:') }} <span class="font-semibold text-gray-800">{{ $enrollment->courseOffering->semester }}</span></span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="lg:col-span-3 col-span-1 bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200 text-center flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mb-2 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-lg sm:text-xl font-semibold text-gray-600">{{ __('មិនទាន់បានចុះឈ្មោះមុខវិជ្ជាណាមួយទេ។') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
<script>
    function markAsRead(itemType, itemId) {
        const url = (itemType === 'notification')
            ? `{{ route('student.notifications.read', ['id' => 'TEMP_ID']) }}`.replace('TEMP_ID', itemId)
            : `{{ route('student.announcements.read', ['id' => 'TEMP_ID']) }}`.replace('TEMP_ID', itemId);

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: itemId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const itemElement = document.getElementById(`${itemType}-${itemId}`);
                if (itemElement) {
                    const buttonContainer = itemElement.querySelector('button').closest('div'); // Find the parent div of the button
                    const newSpan = document.createElement('span');
                    newSpan.className = "text-xs sm:text-sm text-green-600 font-semibold py-1 px-3 rounded-full bg-green-50 border border-green-200 whitespace-nowrap"; // Use text-xs for mobile
                    newSpan.innerHTML = `{{ __('បានអានហើយ') }}`;

                    // Replace the entire button container content to ensure proper centering/alignment if needed
                    buttonContainer.innerHTML = '';
                    buttonContainer.appendChild(newSpan);

                    itemElement.classList.add('opacity-60');
                }
            } else {
                alert('មានបញ្ហាក្នុងការសម្គាល់ជាបានអានហើយ។');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('មានបញ្ហាក្នុងការភ្ជាប់ទៅម៉ាស៊ីនបម្រើ។');
        });
    }
</script>
</x-app-layout>