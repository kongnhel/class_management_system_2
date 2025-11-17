<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                {{ __('វត្តមានរបស់ខ្ញុំ') }}
            </h2>
            <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300">
                ទិដ្ឋភាពទូទៅ
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8">
                <h3 class="text-3xl font-extrabold text-gray-800 mb-8">កំណត់ត្រាវត្តមានសិក្សា</h3>

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm leading-5 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

              {{-- 1. DESKTOP/TABLET VERSION (Traditional Table - HIDDEN on mobile) --}}
<div id="screen-attendance" class="hidden md:block overflow-x-auto rounded-xl shadow-lg">
    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-500 to-green-600">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider rounded-tl-xl">មុខវិជ្ជា</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">កាលបរិច្ឆេទ</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">ស្ថានភាព</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider rounded-tr-xl">កំណត់សម្គាល់</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($attendances as $attendance)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $attendance->courseOffering->course->title_km ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $attendance->date->format('d-M-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if ($attendance->status_km == 'មាន')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold leading-5 bg-green-100 text-green-800">
                                                {{ $attendance->status_km }}
                                            </span>
                                        @elseif ($attendance->status_km == 'អវត្តមាន')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold leading-5 bg-red-100 text-red-800">
                                                {{ $attendance->status_km }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold leading-5 bg-yellow-100 text-yellow-800">
                                                {{ $attendance->status_km }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $attendance->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-medium">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            <p>មិនមានកំណត់ត្រាវត្តមានទេ។</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
{{-- 2. MOBILE CARD VERSION (Stacked Cards - SHOWN on mobile) --}}
                <div id="mobile-attendance" class="block md:hidden space-y-4">
                    @forelse ($attendances as $attendance)
                        @php
                            // Determine status classes
                            $statusClass = 'bg-yellow-100 text-yellow-800'; // Default
                            if ($attendance->status_km == 'មាន') {
                                $statusClass = 'bg-green-100 text-green-800';
                            } elseif ($attendance->status_km == 'អវត្តមាន') {
                                $statusClass = 'bg-red-100 text-red-800';
                            }
                        @endphp
                        
                        <div class="attendance-card bg-white border border-gray-200 rounded-xl shadow-lg p-5 space-y-3">
                            
                            {{-- Subject Name and Status --}}
                            <div class="flex justify-between items-start border-b pb-3">
                                <p class="text-lg font-extrabold text-green-700 leading-tight">
                                    {{ $attendance->courseOffering->course->title_km ?? 'N/A' }}
                                </p>
                                <span class="px-3 py-1 inline-flex text-xs font-bold leading-5 rounded-full whitespace-nowrap {{ $statusClass }}">
                                    {{ $attendance->status_km }}
                                </span>
                            </div>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
                                
                                {{-- Date --}}
                                <p class="font-medium text-gray-500">{{ __('កាលបរិច្ឆេទ:') }}</p>
                                <p class="text-gray-800 font-semibold text-right">{{ $attendance->date->format('d-M-Y') }}</p>

                                {{-- Note --}}
                                <p class="font-medium text-gray-500">{{ __('កំណត់សម្គាល់:') }}</p>
                                <p class="text-gray-800 font-semibold text-right">{{ $attendance->note ?? '-' }}</p>

                            </div>
                        </div>
                    @empty
                        {{-- Empty state for mobile --}}
                        <div class="py-12 text-center text-gray-500 font-medium bg-gray-50 rounded-xl shadow-lg border border-gray-200">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <p>មិនមានកំណត់ត្រាវត្តមានទេ។</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                @if ($attendances->hasPages())
                    <div class="mt-8 flex justify-end">
                        <nav role="navigation" aria-label="Pagination Navigation">
                            {{ $attendances->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>