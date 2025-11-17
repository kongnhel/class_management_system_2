<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ពិន្ទុទាំងអស់') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8">
                <h3 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                    <i class="fas fa-star mr-3 text-red-600"></i>{{ __('បញ្ជីពិន្ទុទាំងអស់ដែលខ្ញុំគ្រប់គ្រង') }}
                </h3>

                <div class="overflow-x-auto bg-gray-50 rounded-lg shadow-inner mb-6">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700 uppercase text-sm font-semibold">
                                <th class="py-3 px-4 text-left rounded-tl-lg">{{ __('ឈ្មោះសិស្ស') }}</th>
                                <th class="py-3 px-4 text-left">{{ __('មុខវិជ្ជា') }}</th>
                                <th class="py-3 px-4 text-left">{{ __('ប្រភេទវាយតម្លៃ') }}</th>
                                <th class="py-3 px-4 text-left">{{ __('ពិន្ទុ') }}</th>
                                <th class="py-3 px-4 text-left">{{ __('ពិន្ទុអតិបរមា') }}</th>
                                <th class="py-3 px-4 text-left rounded-tr-lg">{{ __('កាលបរិច្ឆេទ') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grades as $grade)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-4 text-gray-800">{{ $grade->student_name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $grade->course_title_km ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $grade->assessment_type ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-800">{{ $grade->score }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $grade->max_score }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ \Carbon\Carbon::parse($grade->date)->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                        {{ __('មិនទាន់មានពិន្ទុណាមួយត្រូវបានកត់ត្រាសម្រាប់មុខវិជ្ជារបស់អ្នកទេ។') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $grades->links('pagination::tailwind', ['pageName' => 'gradesPage']) }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
