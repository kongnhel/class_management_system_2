<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                    {{ __('បង្កើតកម្រងសំណួរថ្មី') }}
                </h2>
                <p class="mt-1 text-lg text-gray-500">{{ $courseOffering->course->title_km ?? $courseOffering->course->title_en ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('professor.manage-quizzes', ['offering_id' => $courseOffering->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('ត្រឡប់ទៅបញ្ជី Quiz') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('ព័ត៌មានលម្អិតអំពី Quiz') }}
                </h4>
                <div class="bg-gray-50 p-8 rounded-2xl shadow-inner border border-gray-100">
                    <form action="{{ route('professor.quizzes.store', ['offering_id' => $courseOffering->id]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <div>
                            <label for="title_km" class="block text-sm font-medium text-gray-700">{{ __('ចំណងជើង (ខ្មែរ)') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="title_km" name="title_km" value="{{ old('title_km') }}" required class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                        </div>
                        <div>
                            <label for="title_en" class="block text-sm font-medium text-gray-700">{{ __('ចំណងជើង (អង់គ្លេស)') }}</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                        </div>
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('បរិយាយ') }}</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">{{ old('description_km') }}</textarea>
                        </div>
                        {{-- <div class="md:col-span-2">
                            <label for="description_en" class="block text-sm font-medium text-gray-700">{{ __('បរិយាយ (អង់គ្លេស)') }}</label>
                            <textarea id="description_en" name="description_en" rows="3" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">{{ old('description_en') }}</textarea>
                        </div> --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('ថ្ងៃចាប់ផ្តើម') }} <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('ថ្ងៃបញ្ចប់') }} <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}" required class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                        </div>
                        {{-- <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700">{{ __('រយៈពេល (នាទី)') }} <span class="text-red-500">*</span></label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 10) }}" required min="1" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                        </div> --}}
                        <div>
                             <label for="max_score" class="block text-sm font-medium text-gray-700">{{ __('ពិន្ទុអតិបរមា') }} <span class="text-red-500">*</span></label>
                            <input type="number" id="max_score" name="max_score" value="{{ old('max_score', 10) }}" required min="0" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-4">
                            <button type="submit" class="w-full md:w-auto px-8 py-4 text-white font-extrabold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:scale-[1.01] bg-gradient-to-r from-indigo-600 to-purple-700 hover:from-indigo-700 hover:to-purple-800">
                                <span class="flex items-center justify-center space-x-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    <span>{{ __('បង្កើត Quiz') }}</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
