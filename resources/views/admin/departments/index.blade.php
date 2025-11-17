<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        {{-- <div class="max-w-6xl mx-auto sm:px-6 lg:px-8"> --}}
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div x-data="{ viewMode: 'grid' }" 
                    class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                <div class="flex flex-col md:flex-row items-center justify-between mb-10 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('គ្រប់គ្រងដេប៉ាតឺម៉ង់') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះដេប៉ាតឺម៉ង់ទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
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


                    {{-- <div class="mt-4 md:mt-0"> --}}
                            {{-- <a href="{{ route('admin.create-department') }}" class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ __('បន្ថែមដេប៉ាតឺម៉ង់ថ្មី') }}</span>
                            </a> --}}
                             {{-- ADD NEW BUTTON --}}
                        <a href="{{ route('admin.create-department') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">{{ __('បន្ថែមដេប៉ាតឺម៉ង់ថ្មី') }}</span>
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
                    @if ($departments->isNotEmpty())
                        <div x-show="viewMode === 'grid'" x-transition:enter.duration.500ms>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($departments as $department)

                                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex flex-col items-start mb-6">
                                        <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
                                                <rect x="6" y="10" width="4" height="6"></rect>
                                                <rect x="14" y="10" width="4" height="6"></rect>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-2xl font-bold text-gray-900 leading-tight">{{ $department->name_km }}</h4>
                                            <p class="text-base text-gray-500 mt-1">{{ $department->name_en }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2 mb-6">
                                        <p class="text-gray-700 font-medium"><span class="font-bold text-gray-800">{{ __('មហាវិទ្យាល័យ') }}</span>: <span class="text-gray-600">{{ $department->faculty->name_km ?? 'N/A' }}</span></p>
                                        <p class="text-gray-700 font-medium"><span class="font-bold text-gray-800">{{ __('ប្រធានដេប៉ាតឺម៉ង់') }}</span>: <span class="text-gray-600">{{ $department->head->name ?? 'N/A' }}</span></p>
                                    </div>
                                    <div class="flex justify-end space-x-3 mt-auto">
                                        <a href="{{ route('admin.edit-department', $department->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="openDeleteModal('{{ $department->id }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- <div class="mt-8">
                            {{ $departments->links('pagination::tailwind') }}
                        </div> --}}
                        </div>
                        
                        {{-- /////////////////////////////////////////////////////////// --}}
                        {{-- TABLE VIEW --}}
                        <div x-show="viewMode === 'table'" x-transition:enter.duration.500ms style="display: none;">
                            <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider rounded-tl-xl">
                                                {{ __('លេខរៀង') }}
                                            </th>
                                             <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                                                {{ __('ឈ្មោះដេប៉ាតឺម៉ង់') }}
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                {{ __('ឈ្មោះមហាវិទ្យាល័យ (ខ្មែរ)') }}
                                            </th>
                                       
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider  lg:table-cell">
                                                {{ __('ប្រធាន') }}
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider rounded-tr-xl">
                                                {{ __('សកម្មភាព') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php $i = 1; @endphp
                                        @foreach ($departments as $department)
                                            <tr class="hover:bg-gray-50 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-1/12">
                                                    {{ $i++ + (($departments->currentPage() - 1) * $departments->perPage()) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                                   {{ $department->name_km }}

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                                    {{ $department->faculty->name_km ?? 'N/A' }}

                                                </td>
                                            
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 lg:table-cell">
                                                    {{ $department->head->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                 <div class="flex justify-end space-x-3 mt-auto">
                                                        <a href="{{ route('admin.edit-department', $department->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                            </svg>
                                                        </a>
                                                        <button type="button" onclick="openDeleteModal('{{ $department->id }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
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
                    @else
                        <div class="bg-white p-12 rounded-3xl text-center text-gray-400 shadow-xl border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19v-1a2 2 0 012-2h12a2 2 0 012 2v1m-6-4a4 4 0 11-8 0 4 4 0 018 0zm-8 0a4 4 0 014-4h0m0 0a4 4 0 014 4h0" />
                            </svg>
                            <p class="font-semibold text-lg">{{ __('មិនទាន់មានដេប៉ាតឺម៉ង់ណាមួយនៅឡើយទេ។') }}</p>
                            <p class="mt-2 text-sm">{{ __('ចាប់ផ្តើមដោយបន្ថែមដេប៉ាតឺម៉ង់ដំបូងរបស់អ្នកដើម្បីគ្រប់គ្រងកម្មវិធីសិក្សា និងគ្រូ។') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-red-200">
            <div class="bg-white p-6 sm:p-8">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                            {{ __('បញ្ជាក់ការលុប') }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ __('តើអ្នកពិតជាចង់លុបដេប៉ាតឺម៉ង់នេះមែនទេ? វានឹងលុបកម្មវិធីសិក្សា និងមុខវិជ្ជាដែលពាក់ព័ន្ធទាំងអស់។') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-3xl">
                {{-- Form action is empty and will be set by JS --}}
                <form id="delete-form" action="" method="POST"> 
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
    // Get the form by its new ID
    const deleteForm = document.getElementById('delete-form');

    function openDeleteModal(departmentId) {
        // Correctly set the action URL dynamically
        deleteForm.action = `/admin/departments/${departmentId}`;
        deleteModal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
    }
</script>
