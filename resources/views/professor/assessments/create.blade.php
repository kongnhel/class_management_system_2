<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('បង្កើតការវាយតម្លៃថ្មី') }}
        </h2>
        <p class="text-gray-600 mt-1">{{ __('សម្រាប់មុខវិជ្ជា:') }} <span class="font-bold">{{ $courseOffering->course->title_km }}</span></p>
    </x-slot>
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
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form action="{{ route('professor.assessments.store', ['offering_id' => $courseOffering->id]) }}" method="POST">
                        @csrf

                        <!-- Assessment Type -->
                        <div class="mb-4">
                            <label for="assessment_type" class="block text-sm font-medium text-gray-700">ប្រភេទការវាយតម្លៃ <span class="text-red-500">*</span></label>
                            <select id="assessment_type" name="assessment_type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="assignment" {{ old('assessment_type') == 'assignment' ? 'selected' : '' }}>កិច្ចការ (Assignment)</option>
                                <option value="exam" {{ old('assessment_type') == 'exam' ? 'selected' : '' }}>ការប្រឡង (Exam)</option>
                            </select>
                             @error('assessment_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Title KM -->
                        <div class="mb-4">
                            <label for="title_km" class="block text-sm font-medium text-gray-700">ចំណងជើង (ខ្មែរ) <span class="text-red-500">*</span></label>
                            <input type="text" name="title_km" id="title_km" value="{{ old('title_km') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('title_km')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                         {{-- title-en --}}

                        <!-- Title EN (Added to fix SQL error) -->
                        <div class="mb-4">
                            <label for="title_en" class="block text-sm font-medium text-gray-700">ចំណងជើង (អង់គ្លេស)</label>
                            <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('title_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>


                        <!-- Max Score and Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="max_score" class="block text-sm font-medium text-gray-700">ពិន្ទុអតិបរមា <span class="text-red-500">*</span></label>
                                <input type="number" name="max_score" id="max_score" value="{{ old('max_score', 100) }}" required min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('max_score')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="assessment_date" class="block text-sm font-medium text-gray-700">កាលបរិច្ឆេទ <span class="text-red-500">*</span></label>
                                <input type="date" name="assessment_date" id="assessment_date" value="{{ old('assessment_date', date('Y-m-d')) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('assessment_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Grading Category -->
                        <div class="mb-6">
                            <label for="grading_category_id" class="block text-sm font-medium text-gray-700">ប្រភេទពិន្ទុ <span class="text-red-500">*</span></label>
                            <select id="grading_category_id" name="grading_category_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @forelse($gradingCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('grading_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_km }} ({{ $category->weight_percentage }}%)
                                    </option>
                                @empty
                                    <option value="" disabled>មិនមានប្រភេទពិន្ទុ</option>
                                @endforelse
                            </select>
                            @error('grading_category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('professor.manage-grades', ['offering_id' => $courseOffering->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded-lg mr-2">
                                បោះបង់
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                                រក្សាទុកការវាយតម្លៃ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

