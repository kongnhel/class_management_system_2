<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen font-inter antialiased">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ viewMode: 'grid' }"
             class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                <div class="flex flex-col md:flex-row items-center justify-between mb-10 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('គ្រប់គ្រងមុខវិជ្ជា') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះមុខវិជ្ជាទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
                    </div>

                <div class="mt-4 md:mt-0 flex items-center space-x-4">  
                
                               {{-- VIEW TOGGLE BUTTONS --}}
                        <div class="inline-flex rounded-full shadow-inner bg-gray-100 p-1">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់ប័ណ្ណ') }}">
                                {{-- Grid Icon --}}
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់តារាង') }}">
                                {{-- List Icon --}}
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                           <a href="{{ route('admin.create-course') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">{{ __('បន្ថែមមុខវិជ្ជាថ្មី') }}</span>
                            <span class="sm:hidden">{{ __('បន្ថែម') }}</span>
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

            <div class="mt-8">
                <div x-show="viewMode === 'grid'" x-transition:enter.duration.500ms>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($courses as $course)
                            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex flex-col items-start mb-6">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V6.5A2.5 2.5 0 0 0 17.5 4h-11A2.5 2.5 0 0 0 4 6.5v13z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-bold text-gray-900 leading-tight">{{ $course->title_km }} </h4>
                                        <p class="text-base text-gray-500 mt-1">{{ $course->title_en }}</p>
                                    </div>
                                    {{-- <span class="text-gray-500 text-base font-normal">({{ $course->code }})</span> --}}
                                </div>
                                <div class="space-y-2 mb-6 text-gray-700">
                                    <p class="font-medium"><span class="font-bold text-gray-800">{{ __('ក្រេឌីត') }}</span>: <span class="text-gray-600">{{ $course->credits }}</span></p>
                                    <p class="font-medium"><span class="font-bold text-gray-800">{{ __('ដេប៉ាតឺម៉ង់') }}</span>: <span class="text-gray-600">{{ $course->department->name_km ?? 'N/A' }}</span></p>
                                    <p class="font-medium"><span class="font-bold text-gray-800">{{ __('កម្មវិធីសិក្សា') }}</span>: <span class="text-gray-600">{{ $course->program->name_km ?? 'N/A' }}</span></p>
                                    <p class="font-medium"><span class="font-bold text-gray-800">{{ __('ជំនាន់') }}</span>: <span class="text-gray-600">ជំនាន់ទី{{ $course->generation ?? 'N/A' }}</span></p>
                                </div>
                                <div class="flex justify-end space-x-3 mt-auto">
                                    <a href="{{ route('admin.edit-course', $course->id) }}" class="p-3 bg-gray-100 rounded-full text-blue-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <button onclick="openDeleteModal('{{ route('admin.delete-course', $course->id) }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-12 rounded-3xl text-center text-gray-400 shadow-xl border border-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                                </svg>
                                <p class="font-semibold text-lg">{{ __('មិនមានមុខវិជ្ជាណាមួយនៅឡើយទេ។') }}</p>
                                <p class="mt-2 text-sm">{{ __('ចាប់ផ្តើមដោយបន្ថែមមុខវិជ្ជាដំបូងរបស់អ្នកដើម្បីគ្រប់គ្រងកម្មវិធីសិក្សា។') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                 {{-- TABLE VIEW --}}
    <div x-show="viewMode === 'table'" x-transition:enter.duration.500ms style="display: none;">
        <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider rounded-tl-xl">
                            {{ __('លេខរៀង') }}
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            {{ __('ឈ្មោះមុខវិជ្ជា') }}
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            {{ __('ក្រេឌីត') }}
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                            {{ __('ដេប៉ាតឺម៉ង់') }}
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                            {{ __('កម្មវិធីសិក្សា') }}
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider rounded-tr-xl">
                            {{ __('សកម្មភាព') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $i = 1; @endphp
                    @foreach ($courses as $course)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            {{-- លេខរៀង (Index) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-1/12">
                                {{ $i++ + (($courses->currentPage() - 1) * $courses->perPage()) }}
                            </td>
                            {{-- ឈ្មោះមុខវិជ្ជា (Course Name) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                {{ $course->title_en }}
                            </td>
                            {{-- ក្រេឌីត (Credits) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $course->credits }}
                            </td>
                            {{-- ដេប៉ាតឺម៉ង់ (Department) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500  lg:table-cell">
                                {{ $course->department->name_km ?? 'N/A'}}
                            </td>
                            {{-- កម្មវិធីសិក្សា (Program) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500  lg:table-cell">
                                {{ $course->program->name_km ?? 'N/A'}}
                            </td>
                            {{-- សកម្មភាព (Actions) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <div class="flex justify-end space-x-3 mt-auto">
                                    <a href="{{ route('admin.edit-course', $course->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <button type="button" onclick="openDeleteModal('{{ $course->id }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
                 
                </div>
            </div>
        </div>
    </div>
    
    <!-- Custom Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
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
                                {{ __('លុបមុខវិជ្ជា') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-base text-gray-500">
                                    {{ __('តើអ្នកពិតជាចង់លុបមុខវិជ្ជានេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។ វានឹងលុបទិន្នន័យពាក់ព័ន្ធទាំងអស់ផងដែរ។') }}
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
