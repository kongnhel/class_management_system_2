<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight">
            {{ __('គ្រប់គ្រង Quizzes សម្រាប់មុខវិជ្ជា') }}
        </h2>
        <p class="mt-1 text-lg text-gray-500">{{ $courseOffering->course->title_km ?? $courseOffering->course->title_en ?? 'N/A' }} ({{ $courseOffering->academic_year }} - {{ $courseOffering->semester }})</p>
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
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100 transition-transform duration-500 ease-in-out">

                <div class="bg-blue-50 p-6 rounded-2xl shadow-md border-l-4 border-blue-500 mb-10">
                    <div class="flex items-center space-x-4">
                        <svg class="w-10 h-10 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="text-xl font-bold text-blue-800">{{ __('ព័ត៌មានវគ្គសិក្សា') }}</p>
                            <ul class="list-disc list-inside text-gray-700 mt-2 text-sm md:text-base">
                                <li>{{ __('លេខកូដមុខវិជ្ជា:') }} <span class="font-semibold text-gray-900">{{ $courseOffering->course->code ?? 'N/A' }}</span></li>
                                <li>{{ __('គ្រូបង្រៀន:') }} <span class="font-semibold text-gray-900">{{ $courseOffering->lecturer->name ?? 'N/A' }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('បង្កើត Quiz ថ្មី') }}
                </h4>
                <div class="bg-gray-50 p-8 rounded-2xl shadow-inner mb-10 border border-gray-100">
                    <form action="{{ route('professor.store-quiz', ['offering_id' => $courseOffering->id]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <div>
                            <label for="title_km" class="block text-sm font-medium text-gray-700">{{ __('ចំណងជើង (ខ្មែរ)') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="title_km" name="title_km" value="{{ old('title_km') }}" required class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="title_en" class="block text-sm font-medium text-gray-700">{{ __('ចំណងជើង (អង់គ្លេស)') }}</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                         <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('ថ្ងៃចាប់ផ្ដើម') }} <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                         <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('ថ្ងៃបញ្ចប់') }} <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}" required class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700">{{ __('រយៈពេល (នាទី)') }} <span class="text-red-500">*</span></label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required min="1" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="total_points" class="block text-sm font-medium text-gray-700">{{ __('ពិន្ទុសរុប') }} <span class="text-red-500">*</span></label>
                            <input type="number" id="total_points" name="total_points" value="{{ old('total_points', 100) }}" required min="0" class="mt-1 block w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_published" name="is_published" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_published" class="font-medium text-gray-700">{{ __('ផ្សព្វផ្សាយ Quiz នេះ?') }}</label>
                                    <p class="text-gray-500">{{ __('ប្រសិនបើធីក និស្សិតនឹងអាចមើលឃើញ និងធ្វើ Quiz នេះបាន។') }}</p>
                                </div>
                            </div>
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

                <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M12 18h.01"></path></svg>
                    {{ __('បញ្ជី Quizzes') }}
                </h4>
                <div class="overflow-x-auto bg-gray-50 rounded-2xl shadow-xl mb-6">
                    <table class="min-w-full leading-normal">
                        <thead class="bg-gradient-to-r from-teal-600 to-cyan-700">
                            <tr>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider rounded-tl-2xl">{{ __('ចំណងជើង') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ថ្ងៃចាប់ផ្ដើម') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ថ្ងៃបញ្ចប់') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ពិន្ទុសរុប') }}</th>
                                <th class="py-4 px-6 text-center text-sm font-bold text-white uppercase tracking-wider rounded-tr-2xl">{{ __('សកម្មភាព') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($quizzes as $quiz)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-4 px-6 text-gray-800 font-medium">{{ $quiz->title_km ?? $quiz->title_en ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-600">{{ \Carbon\Carbon::parse($quiz->start_date)->format('Y-m-d H:i') }}</td>
                                    <td class="py-4 px-6 text-gray-600">{{ \Carbon\Carbon::parse($quiz->end_date)->format('Y-m-d H:i') }}</td>
                                    <td class="py-4 px-6 text-gray-600">{{ $quiz->total_points }}</td>
                                    <td class="py-4 px-6 text-center">
                                        {{-- Actions like Edit, Delete, View Questions can be added here --}}
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">កែសម្រួល</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 px-6 text-center text-gray-500 bg-gray-50">
                                        <p class="text-xl font-semibold">{{ __('មិនទាន់មាន Quiz ណាមួយសម្រាប់វគ្គសិក្សានេះនៅឡើយទេ។') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($quizzes->lastPage() > 1)
                    <div class="mt-4">
                        {{ $quizzes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

