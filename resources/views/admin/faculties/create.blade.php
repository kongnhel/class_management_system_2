<x-app-layout>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <x-slot name="header">
        <div class="px-4 md:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold text-gray-900 leading-tight flex items-center">
                {{ __('បង្កើតមហាវិទ្យាល័យថ្មី') }} <i class="fas fa-university text-green-600 ml-4"></i>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">
                <h3 class="text-3xl font-extrabold text-gray-800 mb-8 pb-4 border-b-2 border-green-500">{{ __('ព័ត៌មានមហាវិទ្យាល័យ') }}</h3>

                <form method="POST" action="{{ route('admin.store-faculty') }}">
                    @csrf

                    <!-- Name Khmer -->
                    <div class="mb-6">
                        <label for="name_km" class="block font-semibold text-gray-700 mb-2">{{ __('ឈ្មោះមហាវិទ្យាល័យជាខ្មែរ') }}</label>
                        <input id="name_km" type="text" name="name_km" value="{{ old('name_km') }}" required autofocus
                            class="mt-1 block w-full px-4 py-3 border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 @error('name_km') border-red-500 ring-red-500 @enderror">
                        @error('name_km') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Name English -->
                    <div class="mb-6">
                        <label for="name_en" class="block font-semibold text-gray-700 mb-2">{{ __('ឈ្មោះមហាវិទ្យាល័យជាអង់គ្លេស') }}</label>
                        <input id="name_en" type="text" name="name_en" value="{{ old('name_en') }}" required
                            class="mt-1 block w-full px-4 py-3 border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 @error('name_en') border-red-500 ring-red-500 @enderror">
                        @error('name_en') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dean (Professor) -->
                    <div class="mb-8">
                        <label for="dean_user_id" class="block font-semibold text-gray-700 mb-2">{{ __('ប្រធានមហាវិទ្យាល័យ (Dean)') }}</label>
                        <select id="dean_user_id" name="dean_user_id"
                            class="mt-1 block w-full px-4 py-3 border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 @error('dean_user_id') border-red-500 ring-red-500 @enderror">
                            <option value="" class="text-gray-500">{{ __('ជ្រើសរើសប្រធានមហាវិទ្យាល័យ (ស្រេចចិត្ត)') }}</option>
                            @foreach ($professors as $professor)
                                <option value="{{ $professor->id }}" {{ old('dean_user_id') == $professor->id ? 'selected' : '' }}>
                                    {{ $professor->name }} ({{ $professor->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('dean_user_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('admin.manage-faculties') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-full font-semibold text-xs text-gray-700 uppercase tracking-widest hover:text-gray-900 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('ត្រលប់ក្រោយ') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-600 border border-transparent rounded-full font-semibold text-sm text-white uppercase tracking-widest hover:from-green-700 hover:to-green-700 active:from-green-800 active:to-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus-circle mr-2"></i> {{ __('បង្កើតមហាវិទ្យាល័យ') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>