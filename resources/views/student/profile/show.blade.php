<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row items-center justify-between mb-10 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('ប្រវត្តិរូបនិស្សិត') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('មើល និងកែប្រែព័ត៌មាន Profile របស់អ្នក') }}</p>
                    </div>
                </div>

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- User Profile Overview --}}
                <div class="flex flex-col md:flex-row items-center mb-10 pb-8 border-b border-gray-200">
                    <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8 relative group">
                        @if($studentProfile && $studentProfile->profile_picture_url)
                            <img src="{{ asset('storage/' . $studentProfile->profile_picture_url) }}" alt="{{ $user->name }}" class="w-36 h-36 rounded-full object-cover border-4 border-white shadow-lg transition-transform duration-300 transform group-hover:scale-105">
                        @else
                            <div class="w-36 h-36 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-6xl font-extrabold border-4 border-white shadow-lg">
                                {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="absolute bottom-2 right-2 p-2 bg-white rounded-full text-blue-600 border border-gray-200 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 5-4V5h1v10zm-3-9c.663 0 1.25.537 1.25 1.25S13.663 8.5 13 8.5 11.75 7.963 11.75 7.25 12.337 6 13 6z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-4xl font-extrabold text-gray-900 leading-tight">{{ $user->name }}</h3>
                        <p class="text-xl text-gray-600 mt-1">{{ $user->email }}</p>
                        <p class="text-lg text-gray-500 mt-2">{{ __('លេខកូដអត្តសញ្ញាណសិស្ស') }}: <span class="font-bold text-gray-700">{{ $user->student_id_code ?? 'N/A' }}</span></p>
                        <div class="mt-6">
                            <a href="{{ route('student.profile.edit') }}" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-full shadow-lg hover:from-blue-600 hover:to-indigo-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-7.586 7.586A.5.5 0 0111.5 14H8.25a.25.25 0 01-.25-.25v-3.25a.25.25 0 01.25-.25h3.25a.5.5 0 00.5-.5z" />
                                    <path d="M15 14a2 2 0 00-2-2H8.5a.5.5 0 01-.5-.5v-3a.5.5 0 01.5-.5H11a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1a.5.5 0 01-.5-.5z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ __('កែប្រែ Profile') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Profile Details Section --}}
                <div class="mt-8">
                    <h4 class="font-extrabold text-3xl text-gray-900 mb-6 border-b border-gray-200 pb-3">{{ __('ព័ត៌មានលម្អិត Profile') }}</h4>
                    @if ($studentProfile)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 text-gray-700">
                            {{-- Full Name (Khmer) --}}
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 20a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12z"></path>
                                        <path d="M12 7v10"></path>
                                        <path d="M16 11H8"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ឈ្មោះពេញ (ខ្មែរ)') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $studentProfile->full_name_km ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Full Name (English) --}}
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 20a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12z"></path>
                                        <path d="M12 7v10"></path>
                                        <path d="M16 11H8"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ឈ្មោះពេញ (អង់គ្លេស)') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $studentProfile->full_name_en ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Gender --}}
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10z"></path>
                                        <path d="M12 12v9"></path>
                                        <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10z"></path>
                                        <path d="M12 17v5"></path>
                                        <path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ភេទ') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $studentProfile->gender ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Date of Birth --}}
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ថ្ងៃខែឆ្នាំកំណើត') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $studentProfile->date_of_birth ? \Carbon\Carbon::parse($studentProfile->date_of_birth)->format('d-M-Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Phone Number --}}
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2 2h-1c-1.85 0-3.66-.45-5.3-1.28a19.49 19.49 0 0 1-8.58-8.58C4.45 6.66 4 4.85 4 3v-1a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2.18c-.35 1.05-.55 2.16-.58 3.3a2 2 0 0 1 2.22 2.22c1.13-.03 2.25-.23 3.3-.58a2 2 0 0 1 2.18 2.18z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('លេខទូរស័ព្ទ') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $studentProfile->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Address --}}
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('អាសយដ្ឋាន') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $studentProfile->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Program --}}
                            <div class="flex items-start space-x-4 col-span-1 md:col-span-2 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('កម្មវិធីសិក្សា') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $user->program->name_km ?? $user->program->name_en ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white p-12 rounded-3xl text-center text-gray-400 shadow-xl border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="font-semibold text-lg">{{ __('មិនទាន់មានព័ត៌មាន Profile ទេ។') }}</p>
                            <p class="mt-2 text-sm">{{ __('សូមកែប្រែ Profile របស់អ្នកដើម្បីបន្ថែមព័ត៌មានលម្អិត។') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>