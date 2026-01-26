<x-app-layout>
    <x-slot name="header">
        <div class="px-4 md:px-6 lg:px-8">
            <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                {{ __('ប្រវត្តិរូបនិស្សិត') }}
            </h2>
            <p class="mt-2 text-base text-gray-500">{{ __('ព័ត៌មានលម្អិត និងទំនាក់ទំនងរបស់និស្សិត') }}</p>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                <div class="flex flex-col md:flex-row items-center md:items-start mb-10 pb-10 border-b border-gray-200">
                    <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-10 relative group">
                        @if($student->studentProfile && $student->studentProfile->profile_picture_url)
                            <img src="{{$student->studentProfile->profile_picture_url}}" alt="{{ $student->name }}" class="w-36 h-36 rounded-full object-cover border-4 border-green-400 shadow-xl transition-transform duration-300 transform group-hover:scale-105">
                        @else
                            <div class="w-36 h-36 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-5xl font-extrabold border-4 border-green-400 shadow-xl">
                                {{ Str::upper(Str::substr($student->studentProfile->full_name_km ?? $student->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="text-center md:text-left">
                        <h3 class="text-5xl font-extrabold text-gray-900 leading-tight">{{ $student->studentProfile->full_name_km ?? $student->name }}</h3>
                        <p class="text-lg text-gray-600 mt-2">{{ $student->email }}</p>
                        <p class="text-lg text-gray-500 mt-3">{{ __('លេខកូដអត្តសញ្ញាណនិស្សិត') }}: <span class="font-bold text-gray-700">{{ $student->student_id_code ?? 'N/A' }}</span></p>
                    </div>
                </div>

                <div class="space-y-8">
                    <h4 class="text-3xl font-extrabold text-gray-800 mb-6 pb-2 border-b-2 border-green-300">{{ __('ព័ត៌មានលម្អិត') }}</h4>
                    @if ($student->studentProfile)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 text-gray-700">
                            {{-- Full Name (Khmer) --}}
                            <div class="flex items-start space-x-4 bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-id-card-alt text-green-500 mt-1 text-xl flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ឈ្មោះពេញ (ខ្មែរ)') }}</p>
                                    <p class="text-xl font-bold mt-1 text-gray-900">{{ $student->studentProfile->full_name_km ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Full Name (English) --}}
                            <div class="flex items-start space-x-4 bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-id-card text-green-500 mt-1 text-xl flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ឈ្មោះពេញ (អង់គ្លេស)') }}</p>
                                    <p class="text-xl font-bold mt-1 text-gray-900">{{ $student->studentProfile->full_name_en ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Gender --}}
                            <div class="flex items-start space-x-4 bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-venus-mars text-green-500 mt-1 text-xl flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ភេទ') }}</p>
                                    <p class="text-xl font-bold mt-1 text-gray-900">{{ $student->studentProfile->gender ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Date of Birth --}}
                            <div class="flex items-start space-x-4 bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-birthday-cake text-green-500 mt-1 text-xl flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('ថ្ងៃខែឆ្នាំកំណើត') }}</p>
                                    <p class="text-xl font-bold mt-1 text-gray-900">{{ $student->studentProfile->date_of_birth ? \Carbon\Carbon::parse($student->studentProfile->date_of_birth)->format('d-M-Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Phone Number --}}
                            <div class="flex items-start space-x-4 bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-phone-alt text-green-500 mt-1 text-xl flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('លេខទូរស័ព្ទ') }}</p>
                                    <p class="text-xl font-bold mt-1 text-gray-900">{{ $student->studentProfile->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Address --}}
                            <div class="flex items-start space-x-4 bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-map-marker-alt text-green-500 mt-1 text-xl flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('អាសយដ្ឋាន') }}</p>
                                    <p class="text-xl font-bold mt-1 text-gray-900">{{ $student->studentProfile->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- Academic Program --}}
                            <div class="flex items-start space-x-4 col-span-1 md:col-span-2 bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100">
                                <i class="fas fa-graduation-cap text-green-500 mt-1 text-xl flex-shrink-0"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-500">{{ __('កម្មវិធីសិក្សា') }}</p>
                                    <p class="text-xl font-bold mt-1 text-gray-900">{{ $student->program->name_km ?? $student->program->name_en ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-100 p-8 rounded-2xl text-center text-gray-500 border border-gray-200">
                            <p class="text-xl font-medium">{{ __('មិនទាន់មានព័ត៌មាន Profile ទេ។') }}</p>
                        </div>
                    @endif
                </div>

                {{-- <div class="flex justify-start mt-12 pt-8 border-t border-gray-200">
                    <a href="{{ route('professor.course-offerings.students.index', ['courseOffering' => $courseOffering->id]) }}" class="inline-flex items-center px-8 py-3 bg-gray-700 border border-transparent rounded-2xl font-bold text-base text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                        <i class="fas fa-arrow-left mr-3"></i> {{ __('ត្រឡប់ទៅបញ្ជីនិស្សិត') }}
                    </a>
                </div> --}}

            </div>
        </div>
    </div>
</x-app-layout>