<x-app-layout>
    <x-slot name="header">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-2xl sm:text-4xl text-gray-900 leading-tight">
                        {{ __('កាលវិភាគរបស់ខ្ញុំ') }}
                    </h2>
                    <p class="text-sm sm:text-base text-gray-500 mt-1">
                        {{ __('កាលវិភាគបង្រៀនប្រចាំឆមាសរបស់អ្នក') }}
                    </p>
                </div>
                
                <div class="no-print">
                    <button onclick="window.print()" 
                        class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 text-white font-bold text-sm sm:text-base rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                        </svg>
                        <span>{{ __('បោះពុម្ព') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50 min-h-screen print:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl p-5 sm:p-8 lg:p-12 border border-gray-100">

                <h3 class="text-xl sm:text-2xl font-bold text-emerald-700 mb-6 sm:mb-8 pb-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                    {{ __('កាលវិភាគបង្រៀន') }}
                </h3>

                @if ($courseOfferings->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 sm:py-20 text-gray-600 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200 px-4 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 sm:h-20 sm:w-20 text-indigo-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xl sm:text-2xl font-bold mb-2 text-gray-800">
                            {{ __('មិនទាន់មានកាលវិភាគបង្រៀនទេ។') }}
                        </p>
                        <p class="text-sm sm:text-base text-gray-500 max-w-md">
                            {{ __('សូមទាក់ទងការិយាល័យរដ្ឋបាល ប្រសិនបើអ្នកគិតថានេះជាកំហុស។') }}
                        </p>
                    </div>
                @else
                    <div class="space-y-6 sm:space-y-10">
                        @foreach ($courseOfferings as $offering)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-8 transition-all duration-300 hover:shadow-md">
                                <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6 mb-6">
                                    <div class="flex-shrink-0 w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg sm:text-2xl font-bold text-gray-900 leading-tight">{{ $offering->course->title_en }}</h4>
                                        <p class="text-sm sm:text-md text-emerald-600 font-medium mt-0.5">{{ $offering->course->title_km }}</p>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 text-[12px] sm:text-sm text-gray-500 font-bold uppercase tracking-wider">
                                            <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>{{ __('ឆ្នាំសិក្សា') }}: {{ $offering->academic_year }}</span>
                                            <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>{{ __('ឆមាស') }}: {{ $offering->semester }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if ($offering->schedules->isEmpty())
                                    <p class="text-gray-400 italic text-center py-4 bg-gray-50 rounded-xl text-sm">
                                        {{ __('មិនមានកាលវិភាគត្រូវបានកំណត់ទេ។') }}
                                    </p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-6 border-t pt-6 border-gray-100">
                                        @foreach ($offering->schedules as $schedule)
                                            <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-100 transition-all hover:bg-white hover:border-emerald-200 hover:shadow-lg group">
                                                <div class="flex items-center mb-4 text-emerald-600">
                                                    <div class="p-2 bg-emerald-100 rounded-lg mr-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    <p class="font-black text-lg">{{ __($schedule->day_of_week) }}</p>
                                                </div>
                                                <div class="space-y-2.5">
                                                    <p class="text-gray-600 text-sm flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="font-bold mr-1">{{ __('ម៉ោង') }}:</span> 
                                                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                                    </p>
                                                    <p class="text-gray-600 text-sm flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        <span class="font-bold mr-1">{{ __('បន្ទប់') }}:</span> 
                                                        <span class="text-gray-900 uppercase">{{ $schedule->room->room_number ?? '-' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Print-Friendly Schedule Table --}}
<div class="hidden print:block mt-10">
    <h2 class="text-2xl font-bold text-center mb-6">
        {{ __('កាលវិភាគបង្រៀន') }}
    </h2>

    <table class="w-full border-collapse border border-gray-400 text-sm">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('មុខវិជ្ជា') }}</th>
                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('ឆមាស') }}</th>
                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('ឆ្នាំសិក្សា') }}</th>
                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('ថ្ងៃ') }}</th>
                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('ម៉ោង') }}</th>
                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('បន្ទប់') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courseOfferings as $offering)
                @foreach ($offering->schedules as $schedule)
                    <tr>
                        <td class="border border-gray-400 px-3 py-2">
                            {{ $offering->course->title_ ?? $offering->course->title_en }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2">{{ $offering->semester }}</td>
                        <td class="border border-gray-400 px-3 py-2">{{ $offering->academic_year }}</td>
                        <td class="border border-gray-400 px-3 py-2">{{ __($schedule->day_of_week) }}</td>
                        <td class="border border-gray-400 px-3 py-2">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2">{{ $schedule->room->room_number ?? '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
