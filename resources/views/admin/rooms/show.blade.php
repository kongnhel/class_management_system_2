<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ព័ត៌មានបន្ទប់') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('ព័ត៌មានលម្អិត') }}</h3>
                        <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('ត្រឡប់ក្រោយ') }}
                        </a>
                    </div>

                    <div class="mt-4">
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700">{{ __('លេខបន្ទប់:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $room->room_number }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700">{{ __('សមត្ថភាព:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $room->capacity }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700">{{ __('ឈ្មោះ Wifi:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $room->wifi_name ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700">{{ __('ពាក្យសម្ងាត់ Wifi:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $room->wifi_password ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700">{{ __('ទីតាំងបន្ទប់:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $room->location_of_room ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700">{{ __('ប្រភេទបន្ទប់:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $room->type_of_room ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
