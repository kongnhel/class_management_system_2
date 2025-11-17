<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-4 sm:px-6 lg:px-8">
            <h2 class="font-bold text-4xl text-gray-900 leading-tight mb-2 md:mb-0">
                {{ __('ពិន្ទុរបស់ខ្ញុំ') }} <i class="fas fa-chart-line text-green-600 ml-2"></i>
            </h2>
            {{-- <a href="#" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg">
                <i class="fas fa-chart-pie mr-2"></i> {{ __('មើលទិដ្ឋភាពទូទៅ') }}
            </a> --}}
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">
                <h3 class="text-3xl font-extrabold text-gray-800 mb-8 pb-4 border-b-2 border-green-500">{{ __('កំណត់ត្រាពិន្ទុ') }}</h3>

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 mb-8 rounded-lg shadow-md font-medium" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                            </div>
                            <div class="ml-4 text-sm md:text-base">
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

               {{-- 1. DESKTOP/TABLET VERSION (Traditional Table - HIDDEN on mobile) --}}
                <div id="screen-grades" class="hidden md:block overflow-x-auto rounded-2xl shadow-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-600 to-purple-700 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider rounded-tl-2xl">{{ __('មុខវិជ្ជា') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">{{ __('ប្រភេទវាយតម្លៃ') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">{{ __('ពិន្ទុ') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">{{ __('ពិន្ទុអតិបរមា') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider rounded-tr-2xl">{{ __('កាលបរិច្ឆេទ') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($grades as $grade)
                                <tr class="hover:bg-gray-100 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-900">{{ $grade->course_title_km ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $grade->assessment_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold leading-5 border shadow-sm
                                            @if($grade->score >= 10)
                                                bg-green-100 text-green-800 border-green-300
                                            @elseif($grade->score >= 7)
                                                bg-yellow-100 text-yellow-800 border-yellow-300
                                            @else
                                                bg-red-100 text-red-800 border-red-300
                                            @endif">
                                            {{ $grade->score }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $grade->max_score }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($grade->date)->format('d-M-Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500 font-medium bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1a9 9 0 1118 0c0 4.97-4.03 9-9 9S0 18.97 0 14z"></path>
                                            </svg>
                                            <p class="text-lg font-semibold text-gray-800">{{ __('មិនទាន់មានកំណត់ត្រាពិន្ទុនៅឡើយទេ') }}</p>
                                            <p class="mt-1 text-sm text-gray-500">{{ __('ពិន្ទុរបស់អ្នកនឹងបង្ហាញនៅទីនេះនៅពេលដែលសាស្រ្តាចារ្យបានបញ្ចូល។') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
{{-- 2. MOBILE CARD VERSION (Stacked Cards - SHOWN on mobile) --}}
                <div id="mobile-grades" class="block md:hidden space-y-4">
                    @forelse ($grades as $grade)
                        @php
                            // Determine color classes based on score, mirroring table logic
                            $scoreBgClass = '';
                            $scoreTextClass = '';
                            if ($grade->score >= 10) {
                                $scoreBgClass = 'bg-green-100 border-green-300';
                                $scoreTextClass = 'text-green-800';
                            } elseif ($grade->score >= 7) {
                                $scoreBgClass = 'bg-yellow-100 border-yellow-300';
                                $scoreTextClass = 'text-yellow-800';
                            } else {
                                $scoreBgClass = 'bg-red-100 border-red-300';
                                $scoreTextClass = 'text-red-800';
                            }
                        @endphp
                        
                        <div class="grade-card bg-white border border-gray-200 rounded-xl shadow-lg p-5 space-y-3">
                            
                            {{-- Subject Name --}}
                            <div class="flex justify-between items-start border-b pb-3">
                                <p class="text-lg font-extrabold text-green-700 leading-tight">
                                    {{ $grade->course_title_km ?? 'N/A' }}
                                </p>
                            </div>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
                                
                                {{-- Assessment Type --}}
                                <p class="font-medium text-gray-500">{{ __('ប្រភេទវាយតម្លៃ:') }}</p>
                                <p class="text-blue-500 font-semibold text-right">{{ $grade->assessment_type }}</p>

                                {{-- Score --}}
                                <p class="font-medium text-gray-500">{{ __('ពិន្ទុ:') }}</p>
                                <p class="text-gray-800 font-semibold text-right">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold leading-5 border shadow-sm {{ $scoreBgClass }} {{ $scoreTextClass }}">
                                        {{ $grade->score }}
                                    </span>
                                </p>

                                {{-- Max Score --}}
                                <p class="font-medium text-gray-500">{{ __('ពិន្ទុអតិបរមា:') }}</p>
                                <p class="text-gray-800 font-semibold text-right">{{ $grade->max_score }}</p>
                                
                                {{-- Date --}}
                                <p class="font-medium text-gray-500">{{ __('កាលបរិច្ឆេទ:') }}</p>
                                <p class="text-gray-800 font-semibold text-right">{{ \Carbon\Carbon::parse($grade->date)->format('d-M-Y') }}</p>

                            </div>
                        </div>
                    @empty
                        {{-- Empty state for mobile --}}
                        <div class="py-16 text-center text-gray-500 font-medium bg-gray-50 rounded-2xl shadow-xl border border-gray-200">
                             <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1a9 9 0 1118 0c0 4.97-4.03 9-9 9S0 18.97 0 14z"></path>
                                </svg>
                                <p class="text-lg font-semibold text-gray-800">{{ __('មិនទាន់មានកំណត់ត្រាពិន្ទុនៅឡើយទេ') }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ __('ពិន្ទុរបស់អ្នកនឹងបង្ហាញនៅទីនេះនៅពេលដែលសាស្រ្តាចារ្យបានបញ្ចូល។') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                @if ($grades->hasPages())
                    <div class="mt-8">
                        {{ $grades->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>