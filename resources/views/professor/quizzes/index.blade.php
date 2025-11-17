<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                    {{ __('គ្រប់គ្រងកម្រងសំណួរ (Quiz)') }}
                </h2>
                <p class="mt-1 text-lg text-gray-500">{{ $courseOffering->course->title_km ?? $courseOffering->course->title_en ?? 'N/A' }} ({{ $courseOffering->academic_year }} - {{ $courseOffering->semester }})</p>
            </div>
                  <a href="{{ route('professor.my-course-offerings', ['offering_id' => $courseOffering->id]) }}"
        class="inline-flex items-center px-6 py-3 
                bg-gradient-to-r from-indigo-500 via-indigo-600 to-indigo-700 
                hover:from-indigo-600 hover:via-indigo-700 hover:to-indigo-800 
                text-white text-sm font-semibold rounded-lg shadow-md 
                hover:shadow-lg transform hover:scale-105 
                transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('ត្រឡប់ទៅបញ្ជីមុខវិជ្ជា') }}
        </a>
        </div>
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
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-2xl font-bold text-gray-700 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M12 18h.01"></path></svg>
                        {{ __('បញ្ជីកម្រងសំណួរ') }}
                    </h4>
                    <a href="{{ route('professor.quizzes.create', ['offering_id' => $courseOffering->id]) }}" class="w-full md:w-auto px-6 py-3 text-white font-extrabold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:scale-[1.01] bg-gradient-to-r from-indigo-600 to-purple-700 hover:from-indigo-700 hover:to-purple-800">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span>{{ __('បង្កើត Quiz ថ្មី') }}</span>
                        </span>
                    </a>
                </div>

                <div class="overflow-x-auto bg-gray-50 rounded-2xl shadow-xl mb-6">
                    <table class="min-w-full leading-normal">
                        <thead class="bg-gradient-to-r from-teal-600 to-cyan-700">
                            <tr>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider rounded-tl-2xl">{{ __('ចំណងជើង') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ថ្ងៃចាប់ផ្តើម') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ថ្ងៃបញ្ចប់') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ពិន្ទុ') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ស្ថានភាព') }}</th>
                                <th class="py-4 px-6 text-center text-sm font-bold text-white uppercase tracking-wider rounded-tr-2xl">{{ __('សកម្មភាព') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($quizzes as $quiz)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-4 px-6 text-gray-800 font-medium">
                                        <a href="{{ route('professor.quizzes.questions.index', $quiz->id) }}" class="hover:underline text-indigo-600">
                                            {{ $quiz->title_km ?? $quiz->title_en ?? 'N/A' }}
                                        </a>
                                        {{-- <span class="text-xs text-gray-500">({{ $quiz->quizQuestions->count() }} សំណួរ)</span> --}}
                                    </td>
                                    <td class="py-4 px-6 text-gray-600">{{ \Carbon\Carbon::parse($quiz->start_date)->format('Y-m-d H:i') }}</td>
                                    <td class="py-4 px-6 text-gray-600">{{ \Carbon\Carbon::parse($quiz->end_date)->format('Y-m-d H:i') }}</td>
                                    <td class="py-4 px-6 text-gray-600">{{ $quiz->max_score ?? $quiz->total_points }}</td>
                                    <td class="py-4 px-6 text-gray-600">
                                        @php
                                            $now = now();
                                            $startDate = \Carbon\Carbon::parse($quiz->start_date);
                                            $endDate = \Carbon\Carbon::parse($quiz->end_date);
                                            if ($now->isAfter($endDate)) {
                                                echo '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 shadow-sm">' . __('បានបញ្ចប់') . '</span>';
                                            } elseif ($now->between($startDate, $endDate)) {
                                                echo '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 shadow-sm">' . __('កំពុងដំណើរការ') . '</span>';
                                            } else {
                                                echo '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 shadow-sm">' . __('មិនទាន់ដល់ពេល') . '</span>';
                                            }
                                        @endphp
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('professor.quizzes.edit', ['offering_id' => $courseOffering->id, 'quiz' => $quiz->id]) }}" class="inline-flex items-center text-sm font-semibold text-purple-600 hover:text-purple-800 transition-colors duration-200 hover:bg-purple-100 rounded-full px-3 py-1">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                                {{ __('កែសម្រួល') }}
                                            </a>
                                            <form action="{{ route('professor.quizzes.destroy', ['offering_id' => $courseOffering->id, 'quiz' => $quiz->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('តើអ្នកពិតជាចង់លុប Quiz នេះមែនទេ? សំណួរទាំងអស់ដែលជាប់ទាក់ទងក៏នឹងត្រូវលុបដែរ។') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center text-sm font-semibold text-red-600 hover:text-red-800 transition-colors duration-200 hover:bg-red-100 rounded-full px-3 py-1">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                    {{ __('លុប') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 px-6 text-center text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                            <p class="text-xl font-semibold mb-1">{{ __('មិនទាន់មាន Quiz ណាមួយសម្រាប់វគ្គសិក្សានេះនៅឡើយទេ។') }}</p>
                                            <p class="text-sm text-gray-400">{{ __('សូមចុចប៊ូតុងខាងលើដើម្បីបង្កើត Quiz ដំបូងរបស់អ្នក។') }}</p>
                                        </div>
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
