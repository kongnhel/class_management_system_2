<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                {{-- <div class="mb-8 pb-4 border-b border-gray-200">
                    <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                        {{ __('បង្កើតសេចក្តីប្រកាសថ្មី') }}
                    </h2>
                    <p class="mt-2 text-lg text-gray-500">{{ __('បំពេញព័ត៌មានលម្អិតខាងក្រោមដើម្បីបង្កើតសេចក្តីប្រកាស') }}</p>
                </div> --}}
                <x-slot name="header">
                    <div class="flex justify-between items-center">
                        <h2 class="text-3xl font-bold text-gray-900 leading-tight">
                            {{ __('បង្កើតសេចក្តីប្រកាសថ្មី') }} 
                        </h2>
                        <a href="{{ route('admin.announcements.index') }}" class="px-3 md:px-5 py-2 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition">
                            
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

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586l-1.293-1.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="block sm:inline font-medium">{{ session('error') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl relative mb-8 flex items-start space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <strong class="font-bold block">{{ __('មានបញ្ហា!') }}</strong>
                            <span class="block sm:inline mt-1">{{ __('សូមពិនិត្យមើលកំហុសឆ្គងខាងក្រោម។') }}</span>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.announcements.store') }}">
                    @csrf
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="title_km" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('ចំណងជើង (ខ្មែរ)') }}</label>
                                <input type="text" name="title_km" id="title_km" value="{{ old('title_km') }}" required class="form-input w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out placeholder-gray-400" placeholder="{{ __('បញ្ចូលចំណងជើងជាភាសាខ្មែរ') }}">
                            </div>
                            <div>
                                <label for="title_en" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('ចំណងជើង (អង់គ្លេស)') }}</label>
                                <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}"  class="form-input w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out placeholder-gray-400" placeholder="{{ __('បញ្ចូលចំណងជើងជាភាសាអង់គ្លេស') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="content_km" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('ខ្លឹមសារ (ខ្មែរ)') }}</label>
                                <textarea name="content_km" id="content_km" rows="6" required class="form-textarea w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out placeholder-gray-400" placeholder="{{ __('បញ្ចូលខ្លឹមសារជាភាសាខ្មែរ') }}">{{ old('content_km') }}</textarea>
                            </div>
                            <div>
                                <label for="content_en" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('ខ្លឹមសារ (អង់គ្លេស)') }}</label>
                                <textarea name="content_en" id="content_en" rows="6"  class="form-textarea w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out placeholder-gray-400" placeholder="{{ __('បញ្ចូលខ្លឹមសារជាភាសាអង់គ្លេស') }}">{{ old('content_en') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="target_role" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('កំណត់គោលដៅអ្នកប្រើប្រាស់') }}</label>
                                <select name="target_role" id="target_role" class="form-select w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out">
                                    <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>{{ __('ជ្រើសរើស') }}</option>
                                    @foreach ($role as $role)
                                        <option value="{{ $role }}" {{ old('target_role') == $role ? 'selected' : '' }}>
                                            {{ __($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <div>
                                <label for="course_offering_id" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('ការផ្តល់ជូនវគ្គសិក្សា (ស្រេចចិត្ត)') }}</label>
                                <select name="course_offering_id" id="course_offering_id" class="form-select w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out">
                                    <option value="">{{ __('សេចក្តីប្រកាសទូទៅ') }}</option>
                                    @foreach ($courseOfferings as $offering)
                                        <option value="{{ $offering->id }}" {{ old('course_offering_id') == $offering->id ? 'selected' : '' }}>
                                           ({{ $offering->program->name_km }}) {{ $offering->course->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                    </div>
                    
                    <div class="mt-12 flex justify-between items-center">
                        <a href="{{ route('admin.announcements.index') }}" class="px-6 py-3 text-gray-600 font-semibold rounded-full hover:bg-gray-200 transition duration-300 transform hover:scale-105">{{ __('ត្រឡប់ក្រោយ') }}</a>

                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-blue-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <span>{{ __('បង្កើតសេចក្តីប្រកាស') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>