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

                    {{-- UPDATED: Added enctype for file upload --}}
                    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="room_number" class="block text-sm font-bold text-gray-700 mb-1">{{ __('លេខបន្ទប់') }}</label>
                                <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $room->room_number) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                                @error('room_number')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="capacity" class="block text-sm font-bold text-gray-700 mb-1">{{ __('សមត្ថភាព') }}</label>
                                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $room->capacity) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                                @error('capacity')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="location_of_room" class="block text-sm font-bold text-gray-700 mb-1">{{ __('ទីតាំងបន្ទប់') }}</label>
                                <input type="text" name="location_of_room" id="location_of_room" value="{{ old('location_of_room', $room->location_of_room) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                                @error('location_of_room')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="type_of_room" class="block text-sm font-bold text-gray-700 mb-1">{{ __('ប្រភេទបន្ទប់') }}</label>
                                <input type="text" name="type_of_room" id="type_of_room" value="{{ old('type_of_room', $room->type_of_room) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300">
                                @error('type_of_room')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- UPDATED: WiFi QR Code Section --}}
                            <div class="md:col-span-2" x-data="{ imagePreview: null }">
                                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('រូបភាព WiFi QR Code') }}</label>
                                
                                <div class="flex flex-col md:flex-row items-center gap-6 p-6 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50">
                                    {{-- Current Image or New Preview --}}
                                    <div class="relative group w-40 h-40 flex-shrink-0">
                                        <template x-if="imagePreview">
                                            <img :src="imagePreview" class="w-full h-full object-contain rounded-lg border bg-white shadow-sm">
                                        </template>
                                        <template x-if="!imagePreview">
                                            @if($room->wifi_qr_code)
                                                <img src="{{ asset('storage/' . $room->wifi_qr_code) }}" class="w-full h-full object-contain rounded-lg border bg-white shadow-sm">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200 rounded-lg text-gray-400">
                                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </template>
                                    </div>

                                    {{-- Upload Input --}}
                                    <div class="flex-1 text-center md:text-left">
                                        <p class="text-sm text-gray-600 mb-3">{{ __('ជ្រើសរើសរូបភាពថ្មីដើម្បីជំនួសរូបភាពចាស់ (ទុកវាឱ្យនៅទំនេរប្រសិនបើមិនចង់ផ្លាស់ប្តូរ)') }}</p>
                                        <label for="wifi_qr_code" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            {{ __('ប្តូររូបភាព') }}
                                            <input id="wifi_qr_code" name="wifi_qr_code" type="file" class="sr-only" accept="image/*"
                                                @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result; }; reader.readAsDataURL(file); }">
                                        </label>
                                        @error('wifi_qr_code')
                                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-full font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 transition ease-in-out duration-150">
                                {{ __('បោះបង់') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring-2 focus:ring-green-200 transition-all duration-300 transform hover:scale-105">
                                {{ __('រក្សាទុកការផ្លាស់ប្តូរ') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>