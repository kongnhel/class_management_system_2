<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight tracking-wide">
            {{ __('មុខវិជ្ជាដែលបានចុះឈ្មោះរបស់ខ្ញុំ') }}
        </h2>
        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះមុខវិជ្ជាដែលអ្នកបានចុះឈ្មោះ') }}</p>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">
                {{-- Program Details Section --}}
                @if ($studentProgram)
                    <div class="flex flex-col md:flex-row justify-between items-center mb-10 pb-5 border-b border-gray-200">
                        <h3 class="text-3xl font-extrabold text-green-700 mb-4 md:mb-0 flex items-center">
                            <i class="fas fa-graduation-cap text-3xl mr-3 text-green-600"></i>
                            {{ __('ជំនាញ') }}: {{ $studentProgram->name_km }}
                        </h3>
                    </div>
                @else
                    <div class="bg-gray-100 p-8 rounded-2xl text-center text-gray-500 mb-10 shadow-inner">
                        <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <p class="text-2xl font-bold text-gray-800 mb-2">{{ __('អ្នកមិនទាន់បានចុះឈ្មោះក្នុងកម្មវិធីសិក្សាណាមួយនៅឡើយទេ') }}</p>
                        <p class="text-base text-gray-500">{{ __('សូមទាក់ទងអ្នកគ្រប់គ្រងដើម្បីចុះឈ្មោះក្នុងជំនាញរបស់អ្នក។') }}</p>
                    </div>
                @endif

                {{-- Session Messages --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl relative mb-6 font-medium shadow-sm flex items-center space-x-3" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl relative mb-6 font-medium shadow-sm flex items-center space-x-3" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Courses List Section --}}
                <div class="mt-8">
                    @if ($enrollments->isEmpty())
                        <div class="bg-gray-100 p-8 rounded-2xl text-center text-gray-500 shadow-inner">
                            <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3h4v2m-2 2v10m0 0v1h10v-1m-10 0v-10h10v10M9 5h6"></path>
                            </svg>
                            <p class="text-2xl font-bold text-gray-800 mb-2">{{ __('អ្នកមិនទាន់បានចុះឈ្មោះក្នុងមុខវិជ្ជាណាមួយនៅឡើយទេ') }}</p>
                            <p class="text-base text-gray-500">{{ __('សូមចូលទៅកាន់ទំព័រ Courses ដើម្បីមើលមុខវិជ្ជាដែលមាន។') }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($enrollments as $enrollment)
                                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-shadow duration-300 transform hover:scale-105">
                                    <div class="flex items-start mb-6">
                                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-2xl mr-5 shadow-md">
    <i class="fas fa-book"></i>
</div>

                                        <div>
                                            <h4 class="text-2xl font-bold text-gray-900 leading-tight">{{ $enrollment->courseOffering->course->title_km ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-500 font-medium">{{ $enrollment->courseOffering->course->title_en ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="mb-6 space-y-4 text-sm text-gray-700">
                                        <p class="font-medium flex items-center text-gray-700">
                                            <i class="fas fa-chalkboard-teacher text-lg mr-3 text-green-500"></i>
                                            <span class="font-semibold">{{ __('សាស្រ្តាចារ្យ') }}:</span>
                                            <span class="text-gray-600 ml-2 flex items-center">
                                                {{-- Lecturer Profile Picture --}}
                                                @if ($enrollment->courseOffering->lecturer && $enrollment->courseOffering->lecturer->userProfile && $enrollment->courseOffering->lecturer->userProfile->profile_picture_url)
                                                    <img class="w-8 h-8 rounded-full object-cover mr-2 border border-gray-300" src="{{ asset('storage/' . $enrollment->courseOffering->lecturer->userProfile->profile_picture_url) }}" alt="{{ $enrollment->courseOffering->lecturer->name }}">
                                                @else
                                                    {{-- Default Avatar --}}
                                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-2">
                                                        <i class="fas fa-user-circle text-green-600"></i>
                                                    </div>
                                                @endif
                                                {{ $enrollment->courseOffering->lecturer->name ?? 'N/A' }}
                                            </span>
                                        </p>
                                        <p class="font-medium flex items-center">
                                            <i class="fas fa-calendar-alt text-lg mr-3 text-green-500"></i>
                                            <span class="font-semibold">{{ __('កាលបរិច្ឆេទ') }}:</span>
                                            <span class="text-gray-600 ml-2">{{ \Carbon\Carbon::parse($enrollment->courseOffering->start_date)->format('d-M-Y') }} - {{ \Carbon\Carbon::parse($enrollment->courseOffering->end_date)->format('d-M-Y') }}</span>
                                        </p>
                                        <p class="font-medium flex items-center">
                                            <i class="fas fa-check-circle text-lg mr-3 text-green-500"></i>
                                            <span class="font-semibold">{{ __('ស្ថានភាព') }}:</span>
                                            @if ($enrollment->status == 'enrolled')
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-4 py-1.5 rounded-full border border-green-300 ml-2">{{ __('បានចុះឈ្មោះ') }}</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-4 py-1.5 rounded-full border border-yellow-300 ml-2">{{ __('កំពុងពិចារណា') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <p class="text-base font-bold text-gray-800 mb-4">{{ __('កាលវិភាគ') }}</p>
                                        <div class="space-y-3">
                                            @forelse ($enrollment->courseOffering->schedules as $schedule)
                                                <div class="text-sm text-gray-600 flex items-center">
                                                    <i class="fas fa-clock text-base mr-3 text-green-500"></i>
                                                    <span class="font-medium">{{ __($schedule->day_of_week) }}:</span>
                                                    <span class="ml-1">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</span>
                                                </div>
                                                <div class="text-sm text-gray-600 flex items-center">
                                                    <i class="fas fa-map-marker-alt text-base mr-3 text-green-500"></i>
                                                    <span class="font-medium">{{ __('បន្ទប់') }}:</span>
                                                    <span class="ml-1">{{ $schedule->room->room_number }}</span>
                                                </div>
                                                @if (!$loop->last)
                                                    <div class="border-t border-gray-100 my-2"></div>
                                                @endif
                                            @empty
                                                <p class="italic text-gray-500 text-sm">{{ __('មិនមានកាលវិភាគសម្រាប់វគ្គសិក្សានេះទេ') }}</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-10 flex justify-center">
                            {{ $enrollments->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>