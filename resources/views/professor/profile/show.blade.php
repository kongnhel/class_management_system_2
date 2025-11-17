<x-app-layout> 
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl p-6 md:p-10 border border-gray-200 transition-all duration-300 hover:shadow-3xl">

                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-10 pb-6 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-3xl md:text-4xl text-gray-900 leading-tight">
                            {{ __('ប្រវត្តិរូបសាស្រ្តាចារ្យ') }}
                        </h2>
                        <p class="mt-2 text-base md:text-lg text-gray-500">
                            {{ __('មើល និងកែប្រែព័ត៌មាន Profile របស់អ្នក') }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('professor.profile.edit') }}" 
                           class="inline-flex items-center px-5 py-3 md:px-6 md:py-3 border border-transparent text-sm md:text-base font-medium rounded-full shadow-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5 -ml-1 mr-2" 
                                 viewBox="0 0 20 20" 
                                 fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd" 
                                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" 
                                      clip-rule="evenodd" />
                            </svg>
                            {{ __('កែប្រែប្រវត្តិរូប') }}
                        </a>
                    </div>
                </div>

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-6 w-6 text-green-500" 
                             viewBox="0 0 20 20" 
                             fill="currentColor">
                            <path fill-rule="evenodd" 
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" 
                                  clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm md:text-base font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Profile Section --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

                    {{-- Profile Picture --}}
                    <div class="md:col-span-1 bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex flex-col items-center">
                        <div class="w-32 h-32 md:w-36 md:h-36 rounded-full overflow-hidden border-4 border-green-400 shadow-md transition-transform duration-300 hover:scale-105">
                            @if ($userProfile->profile_picture_url)
                                <img src="{{ asset('storage/' . $userProfile->profile_picture_url) }}" 
                                     alt="{{ $user->name }}" 
                                     class="object-cover w-full h-full">
                            @else
                                <div class="w-full h-full bg-green-100 flex items-center justify-center text-green-600 text-4xl md:text-6xl font-extrabold tracking-tight">
                                    {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-6 text-2xl md:text-3xl font-extrabold text-gray-900 text-center">{{ $user->name }}</h3>
                        <p class="mt-1 text-base md:text-lg text-gray-500 font-medium">
                            {{ $user->role === 'professor' ? __('សាស្រ្តាចារ្យ') : Str::ucfirst($user->role) }}
                        </p>
                    </div>

                    {{-- Profile Info --}}
                    <div class="md:col-span-2 bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            {{-- Full Name (Khmer) --}}
                            <div class="flex items-center gap-4">
                                <div class="bg-green-50 text-green-500 p-3 rounded-xl flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ឈ្មោះពេញ (ខ្មែរ)') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $userProfile->full_name_km ?? 'N/A' }}</p>
                                </div>
                            </div>

                            {{-- Full Name (English) --}}
                            <div class="flex items-center gap-4">
                                <div class="bg-green-50 text-green-500 p-3 rounded-xl flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ឈ្មោះពេញ (អង់គ្លេស)') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $userProfile->full_name_en ?? 'N/A' }}</p>
                                </div>
                            </div>

                            {{-- Gender --}}
                            <div class="flex items-center gap-4">
                                <div class="bg-green-50 text-green-500 p-3 rounded-xl flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M16 12L12 8L8 12"></path><line x1="12" y1="16" x2="12" y2="8"></line></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ភេទ') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $userProfile->gender ?? 'N/A' }}</p>
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="flex items-center gap-4">
                                <div class="bg-green-50 text-green-500 p-3 rounded-xl flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ថ្ងៃខែឆ្នាំកំណើត') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $userProfile->date_of_birth ? $userProfile->date_of_birth->format('d/m/Y') : 'N/A' }}</p>
                                </div>
                            </div>

                            {{-- Phone Number --}}
                            <div class="flex items-center gap-4">
                                <div class="bg-green-50 text-green-500 p-3 rounded-xl flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 3.08 2h3a2 2 0 0 1 2 1.74 15.61 15.61 0 0 0 .93 4.63 1 1 0 0 1-.33 1.05l-2.43 2.43a15.75 15.75 0 0 0 6 6l2.43-2.43a1 1 0 0 1 1.05-.33 15.61 15.61 0 0 0 4.63.93A2 2 0 0 1 22 16.92z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">{{ __('លេខទូរស័ព្ទ') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $userProfile->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="flex items-center gap-4">
                                <div class="bg-green-50 text-green-500 p-3 rounded-xl flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">{{ __('អាសយដ្ឋាន') }}</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $userProfile->address ?? 'N/A' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
