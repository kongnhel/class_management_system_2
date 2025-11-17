<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                <div class="flex flex-col md:flex-row items-center justify-between mb-8 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('បញ្ជីសេចក្តីប្រកាស') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('គ្រប់គ្រង និងកែសម្រួលសេចក្តីប្រកាសរបស់សាលា') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('admin.announcements.create') }}" class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ __('បង្កើតសេចក្តីប្រកាសថ្មី') }}</span>
                        </a>
                    </div>
                </div>

    
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
                
         <div class="relative shadow-xl sm:rounded-2xl transition-all duration-300 border border-gray-100">
    
    {{-- 1. DESKTOP/TABLET VERSION (Traditional Table - Hidden on mobile) --}}
    <div id="screen-announcements" class="hidden md:block overflow-x-auto rounded-2xl">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th scope="col" class="py-4 px-6 font-bold">{{ __('ចំណងជើង') }}</th>
                    <th scope="col" class="py-4 px-6 font-bold">{{ __('ខ្លឹមសារ') }}</th>
                    <th scope="col" class="py-4 px-6 font-bold">{{ __('អ្នកបង្ហោះ') }}</th>
                    <th scope="col" class="py-4 px-6 font-bold">{{ __('គោលដៅ') }}</th>
                    <th scope="col" class="py-4 px-6 font-bold">{{ __('កាលបរិច្ឆេទ') }}</th>
                    <th scope="col" class="py-4 px-6 text-right font-bold">{{ __('សកម្មភាព') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($announcements as $announcement)
                    <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                            {{ $announcement->title_km }}
                            <div class="text-xs text-gray-500 mt-1">{{ $announcement->title_en }}</div>
                        </td>
                        <td class="py-4 px-6 text-gray-700">
                            {{ Str::limit($announcement->content_km, 50) }}
                        </td>
                        <td class="py-4 px-6 text-gray-700">
                            {{ $announcement->poster->name }}
                        </td>
                        <td class="py-4 px-6 capitalize text-gray-700">
                            <div class="flex items-center space-x-2">
                                <span class="py-1 px-3 bg-gray-200 rounded-full text-xs font-semibold text-gray-800">
                                    {{ __($announcement->target_role) }}
                                </span>
                                @if ($announcement->course_offering_id)
                                    <span class="text-xs text-gray-500">
                                        {{ $announcement->courseOffering->course->name_en }} ({{ $announcement->courseOffering->program->name_en }})
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-gray-700">
                            {{ $announcement->created_at->format('d M, Y') }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end space-x-3 items-center">
                                <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="text-green-600 hover:text-green-800 transition duration-150 ease-in-out transform hover:scale-110" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('admin.announcements.destroy', $announcement->id) }}')" class="text-red-600 hover:text-red-800 transition duration-150 ease-in-out transform hover:scale-110" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white">
                        <td colspan="6" class="py-12 px-6 text-center text-gray-400 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('មិនទាន់មានសេចក្តីប្រកាសណាមួយនៅឡើយ។') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 2. MOBILE CARD VERSION (Stacked Cards - Shown on mobile) --}}
    <div id="mobile-announcements" class="block md:hidden space-y-4 p-4">
        @forelse ($announcements as $announcement)
            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-5 space-y-3">
                
                {{-- Title/Header --}}
                <div class="border-b pb-3">
                    <p class="text-base font-extrabold text-gray-900 leading-tight">{{ $announcement->title_km }}</p>
                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $announcement->title_en }}</p>
                </div>

                {{-- Details Grid --}}
                <div class="space-y-2 text-sm">
                    
                    {{-- Content Snippet --}}
                    <div class="border-b pb-2">
                        <p class="font-medium text-gray-500">{{ __('ខ្លឹមសារ:') }}</p>
                        <p class="text-gray-800 mt-1">{{ Str::limit($announcement->content_km, 80) }}</p>
                    </div>

                    {{-- Poster & Date --}}
                    <div class="grid grid-cols-2 gap-4">
                        <p class="font-medium text-gray-500">{{ __('អ្នកបង្ហោះ:') }}</p>
                        <p class="text-gray-800 font-semibold text-right truncate">{{ $announcement->poster->name }}</p>
                        
                        <p class="font-medium text-gray-500">{{ __('កាលបរិច្ឆេទ:') }}</p>
                        <p class="text-gray-800 font-semibold text-right">{{ $announcement->created_at->format('d M, Y') }}</p>
                    </div>

                    {{-- Target Role & Course --}}
                    <div class="pt-2 border-t">
                        <p class="font-medium text-gray-500 mb-1">{{ __('គោលដៅ:') }}</p>
                        <div class="flex flex-col space-y-1">
                            <span class="py-1 px-3 bg-green-100 rounded-full text-xs font-bold text-green-800 w-fit">
                                {{ __($announcement->target_role) }}
                            </span>
                            @if ($announcement->course_offering_id)
                                <span class="text-xs text-gray-600 mt-1">
                                    {{ $announcement->courseOffering->course->name_en }} ({{ $announcement->courseOffering->program->name_en }})
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Actions --}}
                <div class="flex justify-end space-x-4 pt-3 border-t mt-4">
                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="text-green-600 hover:text-green-800 transition duration-150 ease-in-out font-medium text-sm" title="Edit">
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ __('កែប្រែ') }}</span>
                        </div>
                    </a>
                    
                    <button type="button" onclick="openDeleteModal('{{ route('admin.announcements.destroy', $announcement->id) }}')" class="text-red-600 hover:text-red-800 transition duration-150 ease-in-out font-medium text-sm" title="Delete">
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ __('លុប') }}</span>
                        </div>
                    </button>
                </div>

            </div>
        @empty
            {{-- Empty State for Mobile --}}
            <div class="bg-gray-100 p-8 rounded-xl text-center text-gray-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xl font-medium">{{ __('មិនទាន់មានសេចក្តីប្រកាសណាមួយនៅឡើយ។') }}</p>
            </div>
        @endforelse
    </div>
</div>

                <div class="mt-8">
                    {{ $announcements->links() }}
                </div>

            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                                {{ __('លុបសេចក្តីប្រកាស') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-base text-gray-500">
                                    {{ __('តើអ្នកពិតជាចង់លុបសេចក្តីប្រកាសនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-3xl">
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-6 py-3 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                            {{ __('លុប') }}
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                        {{ __('បោះបង់') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');

        function openDeleteModal(deleteUrl) {
            deleteForm.action = deleteUrl;
            deleteModal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }
    </script>
</x-app-layout>