<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight tracking-wide">
            {{ __('កែប្រែប្រវត្តិរូបសាស្រ្តាចារ្យ') }} {{-- Edit Professor Profile in Khmer --}}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-10">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 sm:p-12 border border-gray-100">
                <h3 class="text-4xl font-extrabold text-green-700 mb-8 text-center">{{ __('កែប្រែព័ត៌មាន Profile របស់អ្នក') }}</h3>

              {{-- Success/Error Messages (Existing) --}}
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('ជោគជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('បរាជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('professor.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        {{-- Profile Picture --}}
                        <div class="col-span-1 md:col-span-2 flex flex-col items-center justify-center space-y-4">
                            <label for="profile_picture" class="block text-xl font-semibold text-gray-700">{{ __('រូបភាព Profile') }}</label>
                            <div class="relative w-36 h-36 rounded-full overflow-hidden border-4 border-green-400 shadow-xl group cursor-pointer" id="profile-picture-container">
                                @if ($userProfile->profile_picture_url)
                                    <img src="{{ asset('storage/' . $userProfile->profile_picture_url)       }}" alt="{{ $user->name }}" class="object-cover w-full h-full transition-all duration-300" id="profile-picture-preview">
                                @else
                                    <div id="profile-picture-placeholder" class="w-full h-full bg-green-100 flex items-center justify-center text-green-600 text-6xl font-extrabold tracking-tight">
                                        {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="text-white text-center font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm mt-1">
                                            {{ __('ផ្លាស់ប្តូររូប') }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*" />
                        </div>

                        {{-- Full Name (Khmer) --}}
                        <div>
                            <label for="full_name_km" class="block text-lg font-medium text-gray-700">{{ __('ឈ្មោះពេញ (ខ្មែរ)') }}<span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="full_name_km" id="full_name_km" value="{{ old('full_name_km', $userProfile->full_name_km) }}" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-lg p-3" placeholder="{{ __('ឧទាហរណ៍៖ ឈ្មោះពេញរបស់អ្នក') }}">
                            </div>
                        </div>

                        {{-- Full Name (English) --}}
                        <div>
                            <label for="full_name_en" class="block text-lg font-medium text-gray-700">{{ __('ឈ្មោះពេញ (អង់គ្លេស)') }}</label>
                            <div class="mt-2">
                                <input type="text" name="full_name_en" id="full_name_en" value="{{ old('full_name_en', $userProfile->full_name_en) }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-lg p-3" placeholder="{{ __('e.g., Your Full Name') }}">
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label for="gender" class="block text-lg font-medium text-gray-700">{{ __('ភេទ') }}<span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="gender" name="gender" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-lg p-3">
                                    <option value="" disabled selected>{{ __('ជ្រើសរើសភេទ') }}</option>
                                    <option value="male" @if(old('gender', $userProfile->gender) == 'male') selected @endif>{{ __('ប្រុស') }}</option>
                                    <option value="female" @if(old('gender', $userProfile->gender) == 'female') selected @endif>{{ __('ស្រី') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div>
                            <label for="date_of_birth" class="block text-lg font-medium text-gray-700">{{ __('ថ្ងៃខែឆ្នាំកំណើត') }}</label>
                            <div class="mt-2">
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $userProfile->date_of_birth ? $userProfile->date_of_birth->format('Y-m-d') : '') }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-lg p-3">
                            </div>
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <label for="phone_number" class="block text-lg font-medium text-gray-700">{{ __('លេខទូរស័ព្ទ') }}</label>
                            <div class="mt-2">
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $userProfile->phone_number) }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-lg p-3" placeholder="{{ __('e.g., 012345678') }}">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div>
                            <label for="address" class="block text-lg font-medium text-gray-700">{{ __('អាសយដ្ឋាន') }}</label>
                            <div class="mt-2">
                                <input type="text" name="address" id="address" value="{{ old('address', $userProfile->address) }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-lg p-3" placeholder="{{ __('ឧទាហរណ៍៖ ភ្នំពេញ') }}">
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="md:col-span-2 flex justify-center mt-6 space-x-4">
                            <button type="submit" class="inline-flex items-center px-8 py-4 border border-transparent text-xl font-medium rounded-full shadow-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h-1v5.586l-1.293-1.293z" />
                                    <path d="M11 18a7 7 0 10-2 0h2zm0 2a9 9 0 110-18 9 9 0 010 18z" />
                                </svg>
                                {{ __('រក្សាទុក') }}
                            </button>
                            <a href="{{ route('professor.profile.show') }}" class="inline-flex items-center px-8 py-4 border border-gray-300 text-xl font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-105 shadow-lg">
                                {{ __('បោះបង់') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    document.getElementById('profile-picture-container').addEventListener('click', function() {
        document.getElementById('profile_picture').click();
    });

    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('profile-picture-container');
        let previewElement = document.getElementById('profile-picture-preview');
        let placeholder = document.getElementById('profile-picture-placeholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewElement) {
                    previewElement.src = e.target.result;
                } else if (placeholder) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.id = 'profile-picture-preview';
                    img.alt = 'Profile Picture Preview';
                    img.className = 'object-cover w-full h-full transition-all duration-300';
                    placeholder.replaceWith(img);
                } else {
                    // Fallback in case both are missing
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.id = 'profile-picture-preview';
                    img.alt = 'Profile Picture Preview';
                    img.className = 'object-cover w-full h-full transition-all duration-300';
                    previewContainer.prepend(img);
                }
                // Update the data attribute to allow for clearing the input
                if (previewElement) {
                    previewElement.dataset.currentSrc = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        } else {
            // Revert to original if file input is cleared
            if (previewElement) {
                const originalSrc = previewElement.dataset.originalSrc;
                if (originalSrc) {
                    previewElement.src = originalSrc;
                } else {
                    // If no original image, replace with placeholder
                    const newPlaceholder = document.createElement('div');
                    newPlaceholder.id = 'profile-picture-placeholder';
                    newPlaceholder.className = 'w-full h-full bg-green-100 flex items-center justify-center text-green-600 text-6xl font-extrabold tracking-tight';
                    newPlaceholder.innerText = '{{ Str::upper(Str::substr($user->name, 0, 1)) }}';
                    previewElement.replaceWith(newPlaceholder);
                }
            }
        }
    });

    // Store the original image URL on page load
    document.addEventListener('DOMContentLoaded', () => {
        const previewElement = document.getElementById('profile-picture-preview');
        if (previewElement) {
            previewElement.dataset.originalSrc = previewElement.src;
        }
    });
</script>
</x-app-layout>
