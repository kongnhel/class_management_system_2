<x-app-layout>
<x-slot name="header">
<div class="px-4 md:px-6 lg:px-8">
<h2 class="text-4xl font-extrabold text-gray-900 leading-tight">
{{ __('កែប្រែបន្ទប់') }} ✏️
</h2>
</div>
</x-slot>

<div class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
            <div class="p-8 sm:p-10">
                {{-- Form Title --}}
                <div class="mb-8">
                    <h3 class="text-2xl font-extrabold text-gray-800">{{ __('ព័ត៌មានលម្អិតបន្ទប់') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('បំពេញព័ត៌មានខាងក្រោមដើម្បីធ្វើបច្ចុប្បន្នភាពបន្ទប់') }}</p>
                </div>

                <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Room Number -->
                        <div>
                            <label for="room_number" class="block text-sm font-bold text-gray-700 mb-1">{{ __('លេខបន្ទប់') }}</label>
                            <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $room->room_number) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                            @error('room_number')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-bold text-gray-700 mb-1">{{ __('សមត្ថភាព') }}</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $room->capacity) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                            @error('capacity')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- WiFi Name -->
                        <div>
                            <label for="wifi_name" class="block text-sm font-bold text-gray-700 mb-1">{{ __('ឈ្មោះ Wifi') }}</label>
                            <input type="text" name="wifi_name" id="wifi_name" value="{{ old('wifi_name', $room->wifi_name) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                            @error('wifi_name')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- WiFi Password -->
                        <div>
                            <label for="wifi_password" class="block text-sm font-bold text-gray-700 mb-1">{{ __('ពាក្យសម្ងាត់ Wifi') }}</label>
                            <input type="text" name="wifi_password" id="wifi_password" value="{{ old('wifi_password', $room->wifi_password) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                            @error('wifi_password')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Location of Room -->
                        <div>
                            <label for="location_of_room" class="block text-sm font-bold text-gray-700 mb-1">{{ __('ទីតាំងបន្ទប់') }}</label>
                            <input type="text" name="location_of_room" id="location_of_room" value="{{ old('location_of_room', $room->location_of_room) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                            @error('location_of_room')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Type of Room -->
                        <div>
                            <label for="type_of_room" class="block text-sm font-bold text-gray-700 mb-1">{{ __('ប្រភេទបន្ទប់') }}</label>
                            <input type="text" name="type_of_room" id="type_of_room" value="{{ old('type_of_room', $room->type_of_room) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                            @error('type_of_room')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-full font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 transition ease-in-out duration-150">
                            {{ __('បោះបង់') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring-2 focus:ring-green-200 disabled:opacity-25 transition-all duration-300 transform hover:scale-105">
                            {{ __('កែប្រែ') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</x-app-layout>