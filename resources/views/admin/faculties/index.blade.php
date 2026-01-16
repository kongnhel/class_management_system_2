<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            {{-- ប្រើ AlpineJS គ្រប់គ្រង viewMode នៅកម្រិតខាងលើបង្អស់ --}}
            <div x-data="{ viewMode: 'grid' }" 
                class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                <div class="flex flex-col md:flex-row items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('គ្រប់គ្រងមហាវិទ្យាល័យ') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះមហាវិទ្យាល័យទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0 flex items-center space-x-4">
                        
                        {{-- VIEW TOGGLE BUTTONS --}}
                        <div class="inline-flex rounded-full shadow-inner bg-gray-100 p-1">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់ប័ណ្ណ') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់តារាង') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                        
                        {{-- ADD NEW BUTTON --}}
                        <a href="{{ route('admin.create-faculty') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">{{ __('បន្ថែមមហាវិទ្យាល័យថ្មី') }}</span>
                            <span class="sm:hidden">{{ __('បន្ថែម') }}</span>
                        </a>
                    </div>
                </div>

                {{-- បង្ហាញ Success/Error Messages --}}
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm animate-pulse">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            <p class="font-bold">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                {{-- ហៅ Livewire Component (ទិន្នន័យ Grid/Table នៅក្នុងនេះ) --}}
                <div class="mt-6">
                    @livewire('faculty-table')
                </div>

            </div>
        </div>
    </div>

    {{-- <div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" x-data x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="closeDeleteModal()"></div>
            <div class="relative bg-white rounded-3xl overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border border-red-100">
                <div class="p-8">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-center text-gray-900 mb-2">{{ __('បញ្ជាក់ការលុប') }}</h3>
                    <p class="text-sm text-gray-500 text-center">{{ __('តើអ្នកពិតជាចង់លុបមហាវិទ្យាល័យនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានឡើយ។') }}</p>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse space-x-reverse space-x-3">
                    <form id="delete-form" method="POST"> 
                        @csrf @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-bold rounded-full hover:bg-red-700 transition duration-150">{{ __('លុប') }}</button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-full hover:bg-gray-100 transition duration-150">{{ __('បោះបង់') }}</button>
                </div>
            </div>
        </div>
    </div> --}}
</x-app-layout>
<script>
    function openDeleteModal(facultyId) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/faculties/${facultyId}`;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
    
    // ឈប់ដាក់កូដ Firebase ឬ pageLoadTime នៅទីនេះទៀត ព្រោះវាមានក្នុង Navigation រួចហើយ
</script>