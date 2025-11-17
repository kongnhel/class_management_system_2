<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-4 md:px-6 lg:px-8">
            <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                {{ __('កាលវិភាគរបស់ខ្ញុំ') }}
            </h2>
            <p class="mt-2 text-base text-gray-500 md:mt-0">
                {{ __('កាលវិភាគបង្រៀនប្រចាំឆមាសរបស់អ្នក') }}
            </p>
                            <div class="mb-6 flex justify-end no-print">
                <button onclick="window.print()" 
                    class="px-6 py-3 bg-green-600 text-white font-bold text-lg rounded-xl shadow-lg hover:bg-green-700 transition-colors duration-200 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('បោះពុម្ព') }}</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen print:hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                <h3 class="text-3xl font-bold text-green-700 mb-8 pb-4 border-b border-gray-200">
                    {{ __('កាលវិភាគបង្រៀន') }}
                </h3>

                @if ($courseOfferings->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-gray-600 bg-white rounded-2xl shadow-inner border-2 border-dashed border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-3xl font-bold mb-3 text-gray-800">
                            {{ __('បច្ចុប្បន្ន អ្នកមិនទាន់មានកាលវិភាគបង្រៀនទេ។') }}
                        </p>
                        <p class="text-lg text-gray-500 text-center max-w-xl">
                            {{ __('សូមទាក់ទងការិយាល័យរដ្ឋបាល ប្រសិនបើអ្នកគិតថានេះជាកំហុស។') }}
                        </p>
                    </div>
                @else
                    <div class="space-y-10">
                        @foreach ($courseOfferings as $offering)
                            <div class="bg-gray-50 rounded-2xl shadow-lg border border-gray-100 p-8 transition-all duration-300 hover:shadow-xl">
                                <div class="flex items-start mb-6">
                                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-3xl mr-6 shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-bold text-gray-900">{{ $offering->course->title_km }}</h4>
                                        <p class="text-md text-gray-500 mt-1">{{ $offering->course->title_en }}</p>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                            <span><span class="font-semibold">{{ __('ឆ្នាំសិក្សា') }}:</span> {{ $offering->academic_year }}</span>
                                            <span>•</span>
                                            <span><span class="font-semibold">{{ __('ឆមាស') }}:</span> {{ $offering->semester }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if ($offering->schedules->isEmpty())
                                    <p class="text-gray-500 italic text-center py-4">
                                        {{ __('មិនមានកាលវិភាគត្រូវបានកំណត់ទេ។') }}
                                    </p>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 border-t pt-6 border-gray-200">
                                        @foreach ($offering->schedules as $schedule)
                                            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200 transition-all duration-200 hover:shadow-lg hover:border-green-300">
                                                <div class="flex items-center mb-2 text-green-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <p class="font-bold text-lg">{{ __($schedule->day_of_week) }}</p>
                                                </div>
                                                <p class="text-gray-700 text-sm mt-3 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-medium">{{ __('ម៉ោង') }}:</span> 
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </p>
                                                <p class="text-gray-700 text-sm mt-1 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="font-medium">{{ __('បន្ទប់') }}:</span> {{ $schedule->room->room_number ?? '-' }}
                                                </p>
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
                            {{ $offering->course->title_km ?? $offering->course->title_en }}
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
