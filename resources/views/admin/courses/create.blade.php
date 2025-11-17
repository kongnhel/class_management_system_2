<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900 leading-tight">{{ __('បង្កើតមុខវិជ្ជាថ្មី') }}</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-3xl p-8 lg:p-12">
                <h3 class="text-2xl font-extrabold text-gray-800 mb-6 border-b border-gray-200 pb-3">{{ __('បញ្ចូលព័ត៌មានមុខវិជ្ជា') }}</h3>

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

                <form action="{{ route('admin.store-course') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700">{{ __('នាយកដ្ឋាន:') }}</label>
                            <select name="department_id" id="department_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-150 ease-in-out" required>
                                <option value="">{{ __('ជ្រើសរើសនាយកដ្ឋាន') }}</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name_km }} ({{ $department->name_en }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program_id" class="block text-sm font-medium text-gray-700">{{ __('កម្មវិធីសិក្សា:') }}</label>
                            <select name="program_id" id="program_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-150 ease-in-out" required>
                                <option value="">{{ __('ជ្រើសរើសកម្មវិធីសិក្សា') }}</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->name_km }} ({{ $program->name_en }})
                                    </option>
                                @endforeach
                            </select>
                            @error('program_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <div>
                            <label for="code" class="block text-sm font-medium text-gray-700">{{ __('លេខកូដមុខវិជ្ជា:') }}</label>
                            <input type="text" name="code" id="code" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('code') }}" placeholder="ឧទាហរណ៍: CSC101" required>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}
<!-- 💡 បន្ថែម field ជំនាន់ -->
                        <div>
                            <label for="generation" class="block text-sm font-medium text-gray-700">{{ __('ជំនាន់:') }}</label>
                            <select name="generation" id="generation" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">{{ __('ជ្រើសរើសជំនាន់') }}</option>
                                @foreach ($generations as $generation)
                                    <option value="{{ $generation }}" {{ old('generation') == $generation ? 'selected' : '' }}>
                                        {{ $generation }}
                                    </option>
                                @endforeach
                            </select>
                            @error('generation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="credits" class="block text-sm font-medium text-gray-700">{{ __('Credits:') }}</label>
                            <input type="number" name="credits" id="credits" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('credits') }}" min="0.5" step="0.1" required>
                            @error('credits')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="title_km" class="block text-sm font-medium text-gray-700">{{ __('ចំណងជើង (ខ្មែរ):') }}</label>
                            <input type="text" name="title_km" id="title_km" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('title_km') }}" required>
                            @error('title_km')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="title_en" class="block text-sm font-medium text-gray-700">{{ __('ចំណងជើង (អង់គ្លេស):') }}</label>
                            <input type="text" name="title_en" id="title_en" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" value="{{ old('title_en') }}" required>
                            @error('title_en')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description_km" class="block text-sm font-medium text-gray-700">{{ __('ការពិពណ៌នា (ខ្មែរ):') }}</label>
                            <textarea name="description_km" id="description_km" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('description_km') }}</textarea>
                            @error('description_km')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description_en" class="block text-sm font-medium text-gray-700">{{ __('ការពិពណ៌នា (អង់គ្លេស):') }}</label>
                            <textarea name="description_en" id="description_en" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('description_en') }}</textarea>
                            @error('description_en')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 space-x-4">
                        <a href="{{ route('admin.manage-courses') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-full font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-100 transition duration-150 ease-in-out">
                            {{ __('បោះបង់') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-green-600 text-white font-bold rounded-full shadow-lg hover:bg-green-700 transition duration-300 transform hover:scale-105">
                            {{ __('បង្កើតមុខវិជ្ជា') }} ✅
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>