<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900 leading-tight">
            {{ __('ព័ត៌មានលម្អិតអ្នកប្រើប្រាស់') }}
        </h2>
        <a href="{{ route('admin.manage-users') }}" class="px-3 md:px-5 py-2 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition">
            
            <span class="md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0a9 9 0 01-18 0z" />
                </svg>
            </span>

            <span class="hidden md:inline-block">
                &larr; {{ __('ត្រឡប់ទៅបញ្ជីវិញ') }}
            </span>
        </a>
    </div>
</x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100">
                <div class="p-8 lg:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column: Profile Picture and Basic Info -->
                        <div class="md:col-span-1 text-center">
                            @php
                                $profile = $user->role === 'student' ? $user->studentProfile : $user->profile;
                            @endphp
                            @if ($profile && $profile->profile_picture_url)
                                <img src="{{ $profile->profile_picture_url }}" alt="{{ $user->name }}" class="w-40 h-40 rounded-full object-cover mx-auto border-4 border-indigo-400 shadow-lg">
                            @else
                                <div class="w-40 h-40 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-6xl font-bold mx-auto border-4 border-indigo-400 shadow-lg">
                                    {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <h3 class="text-3xl font-bold text-gray-900 mt-6">{{ $user->name }}</h3>
                            <p class="text-gray-500 text-lg">{{ $user->email ?? $user->student_id_code }}</p>
                            <span class="mt-4 inline-block bg-indigo-100 text-indigo-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">{{ ucfirst($user->role) }}</span>
                        </div>

                        <!-- Right Column: Detailed Information -->
                        <div class="md:col-span-2">
                            <h4 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">{{ __('ព័ត៌មាន Profile') }}</h4>
                            @if ($profile)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-base">
                                    <p><strong class="text-gray-600">{{ __('ឈ្មោះពេញ (ខ្មែរ)') }}:</strong> {{ $profile->full_name_km ?? 'N/A' }}</p>
                                    <p><strong class="text-gray-600">{{ __('ឈ្មោះពេញ (អង់គ្លេស)') }}:</strong> {{ $profile->full_name_en ?? 'N/A' }}</p>
                                    <p><strong class="text-gray-600">{{ __('ភេទ') }}:</strong> {{ ucfirst($profile->gender ?? 'N/A') }}</p>
                                    <p><strong class="text-gray-600">{{ __('ថ្ងៃខែឆ្នាំកំណើត') }}:</strong> {{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('d M Y') : 'N/A' }}</p>
                                    <p><strong class="text-gray-600">{{ __('លេខទូរស័ព្ទ') }}:</strong> {{ $profile->phone_number ?? 'N/A' }}</p>
                                    <p><strong class="text-gray-600">{{ __('អាសយដ្ឋាន') }}:</strong> {{ $profile->address ?? 'N/A' }}</p>
                                </div>
                            @else
                                <p class="text-gray-400 italic">{{ __('មិនមានព័ត៌មាន Profile ទេ។') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Role-Specific Information -->
                    <div class="mt-12 border-t pt-8">
                        @if ($user->role === 'professor')
                            <h4 class="text-2xl font-bold text-gray-800 mb-6">{{ __('មុខវិជ្ជាដែលកំពុងបង្រៀន') }}</h4>
                            <div class="space-y-4">
                                @forelse ($user->taughtCourseOfferings as $offering)
                                    <div class="bg-gray-50 p-4 rounded-lg border flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-lg text-blue-700">{{ $offering->course->title_km ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $offering->program->name_km ?? 'N/A' }} ({{ $offering->academic_year }})</p>
                                        </div>
                                        <a href="#" class="text-indigo-600 hover:underline font-semibold">មើលលម្អិត &rarr;</a>
                                        {{-- <a href="{{ route('admin.show-course-offering', $offering->id) }}" class="text-indigo-600 hover:underline font-semibold">មើលលម្អិត &rarr;</a> --}}
                                    </div>
                                @empty
                                    <p class="text-gray-400 italic">{{ __('សាស្រ្តាចារ្យនេះមិនទាន់ត្រូវបានចាត់តាំងឱ្យបង្រៀនមុខវិជ្ជាណាមួយនៅឡើយទេ។') }}</p>
                                @endforelse
                            </div>
                        @elseif ($user->role === 'student')
                             <h4 class="text-2xl font-bold text-gray-800 mb-6">{{ __('មុខវិជ្ជាដែលបានចុះឈ្មោះ') }}</h4>
                             <div class="space-y-4">
                                @forelse ($user->studentCourseEnrollments as $enrollment)
                                    <div class="bg-gray-50 p-4 rounded-lg border flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-lg text-blue-700">{{ $enrollment->courseOffering->course->title_km ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $enrollment->courseOffering->program->name_km ?? 'N/A' }} ({{ $enrollment->courseOffering->academic_year }})</p>
                                        </div>
                                        <a href="#" class="text-indigo-600 hover:underline font-semibold">មើលលម្អិត &rarr;</a>
                                        {{-- <a href="{{ route('admin.show-course-offering', $enrollment->courseOffering->id) }}" class="text-indigo-600 hover:underline font-semibold">មើលលម្អិត &rarr;</a> --}}
                                    </div>
                                @empty
                                    <p class="text-gray-400 italic">{{ __('និស្សិតនេះមិនទាន់បានចុះឈ្មោះក្នុងមុខវិជ្ជាណាមួយនៅឡើយទេ។') }}</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
