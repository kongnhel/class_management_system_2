<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-gray-50 to-green-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-10 pb-6 border-b border-gray-100">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('មុខវិជ្ជាខ្ញុំបង្រៀន') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីវគ្គសិក្សាទាំងអស់ដែលអ្នកកំពុងបង្រៀន') }}</p>
                    </div>
                </div>
{{-- room --}}
                {{-- Flash Messages --}}
                <div class="space-y-4 mb-8">
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm flex items-center" role="alert">
                            <svg class="h-6 w-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm flex items-center" role="alert">
                            <svg class="h-6 w-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Course Content --}}
                @if ($courseOfferings->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-gray-600 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                        </svg>
                        <p class="text-2xl font-bold text-gray-800">{{ __('មិនទាន់មានមុខវិជ្ជាត្រូវបានចាត់តាំង') }}</p>
                        <p class="mt-2 text-gray-500">{{ __('សូមទាក់ទងរដ្ឋបាល ប្រសិនបើមានចម្ងល់។') }}</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($courseOfferings as $offering)
                            <div class="group bg-white p-8 rounded-3xl shadow-lg border border-gray-100 hover:border-green-200 transition-all duration-300 hover:shadow-2xl flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="p-3 bg-green-50 rounded-2xl text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <h4 class="text-2xl font-bold text-gray-900 mb-1 leading-snug">
                                        {{ $offering->course->title_km ?? 'N/A' }}
                                    </h4>
                                    <p class="text-gray-500 font-medium mb-6 italic">{{ $offering->course->title_en ?? 'N/A' }}</p>

                                    <div class="space-y-3">
                                        <div class="flex items-center text-gray-600 bg-gray-50 p-3 rounded-xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm"><strong>{{ __('ឆ្នាំសិក្សា:') }}</strong> {{ $offering->academic_year }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-600 bg-gray-50 p-3 rounded-xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            <span class="text-sm"><strong>{{ __('ឆមាស:') }}</strong> {{ $offering->semester }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-8">
                                    <button x-data="{}"
                                            x-on:click="$dispatch('open-course-management-modal', { courseOfferingId: {{ $offering->id }} })"
                                            class="flex items-center justify-center w-full px-6 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all duration-300 shadow-lg shadow-green-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.608 3.292 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ __('គ្រប់គ្រងវគ្គសិក្សា') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $courseOfferings->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL REFINEMENT --}}
    <div x-data="{ open: false, courseOfferingId: null }"
        x-on:open-course-management-modal.window="open = true; courseOfferingId = $event.detail.courseOfferingId"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            {{-- Backdrop --}}
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
                 @click="open = false"></div>

            {{-- Modal Content --}}
            <div x-show="open" 
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-[2rem] shadow-2xl max-w-md w-full overflow-hidden"
            >
                <div class="p-8">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="bg-green-100 p-3 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 leading-tight">
                            {{ __('គ្រប់គ្រងវគ្គសិក្សា') }}
                        </h3>
                    </div>

                    <div class="space-y-3">
                        {{-- Menu Items --}}
                        @php
                            $menuItems = [
                                ['route' => 'professor.students.in-course-offering', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'មើលនិស្សិត'],
                                ['route' => 'professor.manage-grades', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5a2 2 0 012 2v2a1 1 0 002 0V5a4 4 0 00-4-4H7a4 4 0 00-4 4v14a4 4 0 004 4h10a4 4 0 004-4v-7a1 1 0 00-2 0v7z', 'label' => 'គ្រប់គ្រងពិន្ទុ'],
                            ];
                        @endphp

                        @foreach ($menuItems as $item)
                            <a :href="courseOfferingId ? '{{ route($item['route'], ['offering_id' => ':id']) }}'.replace(':id', courseOfferingId) : '#'" 
                               class="flex items-center p-4 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-2xl transition-all duration-200 border border-gray-50 hover:border-green-100 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 text-gray-400 group-hover:text-green-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                                <span class="font-semibold">{{ __($item['label']) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-5 flex flex-col sm:flex-row-reverse">
                    <button @click="open = false" type="button" class="w-full py-3 px-6 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                        {{ __('បិទ') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>