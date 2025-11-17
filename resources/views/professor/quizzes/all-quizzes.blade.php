<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight">
            {{ __('គ្រប់គ្រង Quiz ទាំងអស់') }}
        </h2>
        <p class="mt-1 text-lg text-gray-500">{{ __('បញ្ជីកម្រងសំណួរទាំងអស់ពីគ្រប់មុខវិជ្ជាដែលអ្នកបង្រៀន') }}</p>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                <div class="overflow-x-auto bg-gray-50 rounded-2xl shadow-xl">
                    <table class="min-w-full leading-normal">
                        <thead class="bg-gradient-to-r from-teal-600 to-cyan-700">
                            <tr>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider rounded-tl-2xl">{{ __('ចំណងជើង Quiz') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('មុខវិជ្ជា') }}</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ថ្ងៃបញ្ចប់') }}</th>
                                <th class="py-4 px-6 text-center text-sm font-bold text-white uppercase tracking-wider rounded-tr-2xl">{{ __('សកម្មភាព') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($quizzes as $quiz)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-4 px-6 text-gray-800 font-medium">{{ $quiz->title_km ?? $quiz->title_en }}</td>
                                    <td class="py-4 px-6 text-gray-600">{{ $quiz->courseOffering->course->title_km ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-600">{{ \Carbon\Carbon::parse($quiz->end_date)->format('Y-m-d H:i') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('professor.manage-quizzes', ['offering_id' => $quiz->course_offering_id]) }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors duration-200 hover:bg-indigo-100 rounded-full px-4 py-2">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            {{ __('ទៅកាន់ការគ្រប់គ្រង') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 px-6 text-center text-gray-500">
                                        {{ __('មិនទាន់មាន Quiz ណាមួយត្រូវបានបង្កើតនៅឡើយទេ។') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                 @if ($quizzes->lastPage() > 1)
                    <div class="mt-6">
                        {{ $quizzes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
