<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-gray-50 to-green-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                <div class="flex flex-col md:flex-row justify-between items-center mb-10 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('មុខវិជ្ជាខ្ញុំបង្រៀន') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីវគ្គសិក្សាទាំងអស់ដែលអ្នកកំពុងបង្រៀន') }}</p>
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
                @if (session('info'))
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="block font-medium">{{ session('info') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586l-1.293-1.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="block font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($courseOfferings->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-gray-600 bg-white rounded-2xl shadow-inner border-2 border-dashed border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-green-400 mb-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                        </svg>
                        <p class="text-3xl font-bold mb-3 text-gray-800">{{ __('អ្នកមិនទាន់ត្រូវបានចាត់តាំងឱ្យបង្រៀនមុខវិជ្ជាណាមួយនៅឡើយទេ។') }}</p>
                        <p class="text-lg text-gray-500 text-center max-w-xl">
                            {{ __('សូមទាក់ទងការិយាល័យរដ្ឋបាល ប្រសិនបើអ្នកគិតថានេះជាកំហុស។') }}
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        @foreach ($courseOfferings as $offering)
                            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 transform transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl flex flex-col justify-between">
                                <div class="relative z-10">
                                    <h4 class="text-3xl font-extrabold text-gray-900 flex items-center mb-4 pr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                                        </svg>
                                        {{ $offering->course->title_km ?? 'N/A' }}
                                    </h4>
                                    <p class="text-md text-gray-500 mb-5">{{ $offering->course->title_en ?? 'N/A' }}</p>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                                        <div class="flex items-center space-x-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h.01M8 11h.01M12 11h.01M16 11h.01M9 15h.01M13 15h.01M17 15h.01M11 19h.01M15 19h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p><span class="font-semibold">{{ __('ឆ្នាំសិក្សា:') }}</span> {{ $offering->academic_year }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 119.21 3.21a1 1 0 001.071.185l.178.093c.328.175.666.33 1.01.48L12 15z" />
                                            </svg>
                                            <p><span class="font-semibold">{{ __('ឆមាស:') }}</span> {{ $offering->semester }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-8 pt-6 border-t border-gray-200 relative z-10">
                                    <button x-data="{}"
                                            x-on:click="$dispatch('open-course-management-modal', { courseOfferingId: {{ $offering->id }} })"
                                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-600 to-green-600 border border-transparent text-base font-bold rounded-2xl text-white hover:from-green-700 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 ease-in-out shadow-lg hover:shadow-xl w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.608 3.292 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ __('គ្រប់គ្រងវគ្គសិក្សា') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12 flex justify-center">
                        {{ $courseOfferings->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL REFINEMENT START --}}
    <div x-data="{ open: false, courseOfferingId: null }"
        x-on:open-course-management-modal.window="open = true; courseOfferingId = $event.detail.courseOfferingId"
        x-show="open"
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-headline"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" 
                 x-on:click="open = false" 
                 aria-hidden="true">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Content --}}
            <div x-show="open" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <div class="bg-gray-50 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-green-100 sm:mx-0 sm:h-12 sm:w-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.608 3.292 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900" id="modal-headline">
                                {{ __('គ្រប់គ្រងវគ្គសិក្សា') }}
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">
                                    {{ __('សូមជ្រើសរើសជម្រើសគ្រប់គ្រងសម្រាប់វគ្គសិក្សានេះ:') }}
                                </p>
                                <div class="flex flex-col space-y-3 mt-6">
                                    <a :href="courseOfferingId ? '{{ route('professor.students.in-course-offering', ['offering_id' => ':id_placeholder']) }}'.replace(':id_placeholder', courseOfferingId) : '#'" class="px-5 py-4 text-md font-semibold text-gray-800 hover:bg-green-50 hover:text-green-700 rounded-xl block transition-colors duration-200 flex items-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ __('មើលនិស្សិត') }}
                                    </a>
                                
                                    <a :href="courseOfferingId ? '{{ route('professor.manage-attendance', ['offering_id' => ':id_placeholder']) }}'.replace(':id_placeholder', courseOfferingId) : '#'" class="px-5 py-4 text-md font-semibold text-gray-800 hover:bg-green-50 hover:text-green-700 rounded-xl block transition-colors duration-200 flex items-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-2-4L9 7m3-4v4m4 8h.01M17 12a2 2 0 11-4 0 2 2 0 014 0zM12 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        {{ __('គ្រប់គ្រងវត្តមាន') }}
                                    </a>
                                    <a :href="courseOfferingId ? '{{ route('professor.manage-assignments', ['offering_id' => ':id_placeholder']) }}'.replace(':id_placeholder', courseOfferingId) : '#'" class="px-5 py-4 text-md font-semibold text-gray-800 hover:bg-green-50 hover:text-green-700 rounded-xl block transition-colors duration-200 flex items-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h2.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H19a2 2 0 012 2v4a2 2 0 01-2 2h-4.414a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293H5a2 2 0 01-2-2z" />
                                        </svg>
                                        {{ __('គ្រប់គ្រងកិច្ចការស្រាវជ្រាវ') }}
                                    </a>
                                    <a :href="courseOfferingId ? '{{ route('professor.manage-exams', ['offering_id' => ':id_placeholder']) }}'.replace(':id_placeholder', courseOfferingId) : '#'" class="px-5 py-4 text-md font-semibold text-gray-800 hover:bg-green-50 hover:text-green-700 rounded-xl block transition-colors duration-200 flex items-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                                        </svg>
                                        {{ __('គ្រប់គ្រងការប្រលង') }}
                                    </a>
                                    <a :href="courseOfferingId ? '{{ route('professor.manage-quizzes', ['offering_id' => ':id_placeholder']) }}'.replace(':id_placeholder', courseOfferingId) : '#'" class="px-5 py-4 text-md font-semibold text-gray-800 hover:bg-green-50 hover:text-green-700 rounded-xl block transition-colors duration-200 flex items-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9.228A2.5 2.5 0 006 11.5v3.5a2.5 2.5 0 002.228 2.228L12 18.25m-3.772-8.772a2.5 2.5 0 014.544 0L15 11.5v3.5a2.5 2.5 0 01-2.228 2.228L12 18.25" />
                                        </svg>
                                        {{ __('គ្រប់គ្រង Quiz') }}
                                    </a>
                                    <a :href="courseOfferingId ? '{{ route('professor.manage-grades', ['offering_id' => ':id_placeholder']) }}'.replace(':id_placeholder', courseOfferingId) : '#'" class="px-5 py-4 text-md font-semibold text-gray-800 hover:bg-green-50 hover:text-green-700 rounded-xl block transition-colors duration-200 flex items-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5a2 2 0 012 2v2a1 1 0 002 0V5a4 4 0 00-4-4H7a4 4 0 00-4 4v14a4 4 0 004 4h10a4 4 0 004-4v-7a1 1 0 00-2 0v7z" />
                                        </svg>
                                        {{ __('គ្រប់គ្រងពិន្ទុ') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-3xl">
                    {{-- Simplified x-on:click --}}
                    <button x-on:click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('បិទ') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL REFINEMENT END --}}
</x-app-layout>