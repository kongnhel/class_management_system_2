<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900 leading-tight">
                {{ __('ព័ត៌មានបន្ទប់') }} 📋
            </h2>
            <a href="{{ route('admin.rooms.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition duration-300">
                &larr; {{ __('ត្រឡប់ក្រោយ') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3">
                    
                    <div class="p-8 bg-gray-100 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('WiFi QR Code') }}</h3>
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
                            @if($room->wifi_qr_code)
                                <img src="{{ asset('storage/' . $room->wifi_qr_code) }}" alt="WiFi QR Code" class="w-48 h-48 object-contain">
                            @else
                                <div class="w-48 h-48 flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                    <span class="text-xs">{{ __('មិនមានរូបភាព') }}</span>
                                </div>
                            @endif
                        </div>
                        <p class="mt-4 text-sm text-gray-500 text-center">
                            {{ __('ស្កេនដើម្បីភ្ជាប់បណ្តាញ WiFi ក្នុងបន្ទប់នេះ') }}
                        </p>
                    </div>

                    <div class="p-8 md:col-span-2">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-2xl font-extrabold text-gray-900">{{ __('បន្ទប់លេខ: ') }} {{ $room->room_number }}</h3>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider">
                                    {{ $room->type_of_room ?? 'ទូទៅ' }}
                                </span>
                            </div>
                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                {{ __('កែប្រែ') }}
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                            <div class="border-l-4 border-green-500 pl-4">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('សមត្ថភាពផ្ទុក') }}</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $room->capacity }} {{ __('នាក់') }}</p>
                            </div>

                            <div class="border-l-4 border-blue-500 pl-4">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('ទីតាំង') }}</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $room->location_of_room ?? 'មិនបានកំណត់' }}</p>
                            </div>

                            <div class="border-l-4 border-purple-500 pl-4">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('ឈ្មោះ WiFi') }}</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $room->wifi_name ?? 'N/A' }}</p>
                            </div>

                            <div class="border-l-4 border-yellow-500 pl-4">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('ពាក្យសម្ងាត់ WiFi') }}</p>
                                <p class="text-lg font-semibold text-gray-800 font-mono">{{ $room->wifi_password ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center text-sm text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('បង្កើតឡើងនៅកាលបរិច្ឆេទ:') }} {{ $room->created_at->format('d-M-Y') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>