<x-app-layout>
<x-slot name="header">
<div class="flex flex-col md:flex-row md:items-center md:justify-between">
<h2 class="font-bold text-3xl text-gray-800 leading-tight">
{{ __('បញ្ចូលពិន្ទុ') }}
</h2>
<div class="mt-4 md:mt-0 text-right">
<p class="text-gray-800">{{ __('ការវាយតម្លៃ:') }} <span class="font-bold">{{ $assessment->title_km }}</span></p>
<p class="text-gray-600">{{ __('មុខវិជ្ជា:') }} <span class="font-bold">{{ $assessment->courseOffering->course->title_km }}</span></p>
<p class="text-sm text-gray-500">ពិន្ទុអតិបរមា: {{ $assessment->max_score }}</p>
</div>
</div>
</x-slot>

<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-200">
            <form action="{{ route('professor.grades.store', ['assessment_id' => $assessment->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="assessment_type" value="{{ $type }}">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ឈ្មោះនិស្សិត</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">ពិន្ទុ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">កំណត់ចំណាំ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($students as $index => $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->profile->full_name_km ?? $student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->student_id_code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number"
                                                name="grades[{{ $student->id }}][score]"
                                                value="{{ old('grades.'.$student->id.'.score', $scores[$student->id]['score'] ?? '') }}"
                                                min="0"
                                                max="{{ $assessment->max_score }}"
                                                step="0.01"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                placeholder="0 - {{ $assessment->max_score }}">
                                            @error('grades.'.$student->id.'.score')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text"
                                                name="grades[{{ $student->id }}][notes]"
                                                value="{{ old('grades.'.$student->id.'.notes', $scores[$student->id]['notes'] ?? '') }}"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                placeholder="មតិយោបល់ (ស្រេចចិត្ត)">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            មិនទាន់មាននិស្សិតចុះឈ្មោះក្នុងមុខវិជ្ជានេះទេ។
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 text-right">
                    <a href="{{ route('professor.manage-grades', ['offering_id' => $assessment->course_offering_id]) }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded-lg mr-2">
                        ត្រឡប់ក្រោយ
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-save mr-2"></i> រក្សាទុកពិន្ទុ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-app-layout>