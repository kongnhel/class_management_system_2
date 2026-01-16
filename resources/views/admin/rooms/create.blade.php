<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900 leading-tight">
                {{ __('បង្កើតបន្ទប់ថ្មី') }} 
            </h2>
            <a href="{{ route('admin.rooms.index') }}" class="px-3 md:px-5 py-2 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition">
                <span class="md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0a9 9 0 01-18 0z" />
                    </svg>
                </span>
                <span class="hidden md:inline-block">
                    &larr; {{ __('ត្រឡប់ទៅបញ្ជីវិញ') }}
                </span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-3xl p-8 lg:p-12">
                <h3 class="text-2xl font-extrabold text-gray-800 mb-6 border-b border-gray-200 pb-3">{{ __('បំពេញព័ត៌មានបន្ទប់') }}</h3>

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <strong class="font-bold">{{ __('មានបញ្ហា!') }}</strong>
                                <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- UPDATED: Added enctype for file upload --}}
                <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- លេខបន្ទប់ --}}
                        <div>
                            <label for="room_number" class="block text-sm font-medium text-gray-700">{{ __('លេខបន្ទប់') }}</label>
                            <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="ឧទាហរណ៍: B-101">
                        </div>

                        {{-- សមត្ថភាព --}}
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700">{{ __('សមត្ថភាព') }}</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="ឧទាហរណ៍: 50">
                        </div>

                        {{-- ទីតាំងបន្ទប់ --}}
                        <div class="md:col-span-2">
                            <label for="location_of_room" class="block text-sm font-medium text-gray-700">{{ __('ទីតាំងបន្ទប់') }}</label>
                            <input type="text" name="location_of_room" id="location_of_room" value="{{ old('location_of_room') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="ឧទាហរណ៍: អគារ B ជាន់ទី១">
                        </div>

                        {{-- ប្រភេទបន្ទប់ --}}
                        <div class="md:col-span-2">
                            <label for="type_of_room" class="block text-sm font-medium text-gray-700">{{ __('ប្រភេទបន្ទប់') }}</label>
                            <input type="text" name="type_of_room" id="type_of_room" value="{{ old('type_of_room') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="ឧទាហរណ៍: បន្ទប់រៀនធម្មតា, ពិសោធន៍">
                        </div>

                        {{-- WiFi QR Code Upload --}}
                     <div class="md:col-span-2" x-data="{ imagePreview: null }">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('រូបភាព WiFi QR Code') }}</label>
    
    {{-- បន្ថែម cursor-pointer នៅខាងលើបង្អស់ --}}
    <div class="relative mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-green-400 transition-colors bg-white cursor-pointer"
         @click="$refs.fileInput.click()"> {{-- ចុចកន្លែងណាក៏បានក្នុងប្រអប់ វានឹងបើកឱ្យរើសរូបភាព --}}
        
        <div class="space-y-1 text-center">
            {{-- Preview Image --}}
            <template x-if="imagePreview">
                <div class="mb-4 flex justify-center">
                    <img :src="imagePreview" class="h-48 w-48 object-contain rounded-lg shadow-md border">
                </div>
            </template>
            
            {{-- Placeholder Icon --}}
            <template x-if="!imagePreview">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </template>

            <div class="flex text-sm text-gray-600 justify-center">
                <span class="relative rounded-md font-medium text-green-600 hover:text-green-500">
                    {{ __('បញ្ចូលរូបភាព') }}
                </span>
                <p class="pl-1">{{ __('ឬអូសទម្លាក់ទីនេះ') }}</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF រហូតដល់ 2MB</p>
        </div>

        {{-- Input File ពិតប្រាកដ (លាក់ទុក) --}}
        <input type="file" 
               name="wifi_qr_code" 
               x-ref="fileInput" 
               class="hidden" 
               accept="image/*" 
               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result; }; reader.readAsDataURL(file); }">
    </div>
</div>
                    </div>

                    <div class="flex items-center justify-end mt-8 space-x-4">
                        <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-full font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-100 transition duration-150 ease-in-out">
                            {{ __('បោះបង់') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-green-600 text-white font-bold rounded-full shadow-lg hover:bg-green-700 transition duration-300 transform hover:scale-105">
                            {{ __('បង្កើត') }} ✨
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>