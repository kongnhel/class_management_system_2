<x-app-layout>
    @php
        // ១. ទាញយកទិន្នន័យ User និង Profile
        $user = Auth::user()->loadMissing('userProfile');
        
        // ២. ទាញយក URL ពី ImgBB ដោយផ្ទាល់ (មិនប្រើ asset() ឡើយ ដើម្បីជៀសវាង Error 403)
        $profileUrl = $user->userProfile?->profile_picture_url;
    @endphp

    <div class="py-12 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 space-y-10">
                        {{-- Alerts --}}
         @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 md:p-5 rounded-xl mb-6 shadow-sm flex items-center animate-bounce" role="alert">
                        <i class="fas fa-check-circle mr-3 text-green-500 text-xl"></i>
                        <span class="font-bold text-sm md:text-lg">{{ session('success') }}</span>
                    </div>
                @endif

            {{-- ១. ផ្នែកប្តូររូបភាព Profile (PROFILE PICTURE CARD) --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-10">
                <header class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ __('រូបភាពប្រវត្តិរូប') }}
                    </h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        {{ __("ធ្វើបច្ចុប្បន្នភាពរូបភាពប្រវត្តិរូបគណនីរបស់អ្នកតាមរយៈ ImgBB Cloud។") }}
                    </p>
                </header>

                <form method="post" action="{{ route('profile.update-picture') }}" enctype="multipart/form-data" class="space-y-8 flex flex-col items-center">
                    @csrf

                    <div class="flex flex-col items-center space-y-6">
                        {{-- កន្លែងបង្ហាញរូបភាព និងជ្រើសរើសរូបភាព --}}
                        <div 
                            class="relative w-36 h-36 rounded-full overflow-hidden border-4 border-green-400 dark:border-green-600 shadow-xl group cursor-pointer" 
                            id="profile-picture-container"
                        >
                            @if ($profileUrl)
                                {{-- បង្ហាញរូបភាពពី ImgBB --}}
                                <img src="{{ $profileUrl }}" alt="{{ $user->name }}" class="object-cover w-full h-full transition-all duration-300" id="profile-picture-preview">
                            @else
                                {{-- បង្ហាញអក្សរកាត់ឈ្មោះក្នុងករណីមិនទាន់មានរូបភាព --}}
                                <div id="profile-picture-placeholder" class="w-full h-full bg-green-100 dark:bg-green-700 flex items-center justify-center text-green-600 dark:text-green-100 text-6xl font-extrabold tracking-tight">
                                    {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            {{-- Overlay ពេល Hover --}}
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span class="text-white text-center font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm mt-1">{{ __('ផ្លាស់ប្តូររូប') }}</span>
                                </span>
                            </div>
                        </div>

                        {{-- Hidden File Input --}}
                        <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*" />

                        <x-input-error class="text-center" :messages="$errors->get('profile_picture')" />

                        <x-primary-button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg mt-4">
                            {{ __('អាប់ដេតរូបភាព') }}
                        </x-primary-button>

                        @if (session('success'))
                            <p class="text-green-600 dark:text-green-400 font-medium mt-4">{{ session('success') }}</p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ២. ផ្នែកព័ត៌មានប្រវត្តិរូប (PROFILE INFO CARD) --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-10">
                <header class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('ព័ត៌មានប្រវត្តិរូប') }}</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        {{ __("ធ្វើបច្ចុប្បន្នភាពព័ត៌មានគណនី និងអាសយដ្ឋានអ៊ីមែលរបស់អ្នក។") }}
                    </p>
                </header>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('ឈ្មោះ')" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-600 focus:border-green-600 dark:bg-gray-900 dark:border-gray-700"
                                :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('អ៊ីមែល')" />
                            <x-text-input id="email" name="email" type="email"
                                class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-600 focus:border-green-600 dark:bg-gray-900 dark:border-gray-700"
                                :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-6 pt-6">
                        <x-primary-button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg">
                            {{ __('រក្សាទុក') }}
                        </x-primary-button>
                        @if (session('status') === 'profile-updated')
                            <p class="text-green-600 dark:text-green-400 font-medium">{{ __('បានរក្សាទុក។') }}</p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ៣. ផ្នែកប្តូរលេខសម្ងាត់ (PASSWORD UPDATE CARD) --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-10">
                <header class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('ផ្លាស់ប្តូរលេខសម្ងាត់') }}</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('សូមប្រើលេខសម្ងាត់វែង និងមានសុវត្ថិភាព។') }}</p>
                </header>

                <form method="post" action="{{ route('password.update') }}" class="space-y-8">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="update_password_current_password" :value="__('លេខសម្ងាត់បច្ចុប្បន្ន')" />
                            <x-text-input id="update_password_current_password" name="current_password" type="password"
                                class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-600 focus:border-green-600 dark:bg-gray-900 dark:border-gray-700"
                                autocomplete="current-password" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password" :value="__('លេខសម្ងាត់ថ្មី')" />
                            <x-text-input id="update_password_password" name="password" type="password"
                                class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-600 focus:border-green-600 dark:bg-gray-900 dark:border-gray-700"
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password_confirmation" :value="__('បញ្ជាក់លេខសម្ងាត់')" />
                            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                                class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-600 focus:border-green-600 dark:bg-gray-900 dark:border-gray-700"
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-center pt-6">
                        <x-primary-button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg">
                            {{ __('រក្សាទុក') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- ៤. ផ្នែកលុបគណនី (DELETE ACCOUNT CARD) --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-10 text-center">
                <h2 class="text-2xl font-bold text-red-600">{{ __('លុបគណនី') }}</h2>
                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    {{ __('ការលុបគណនីនឹងលុបទិន្នន័យទាំងអស់ជាអចិន្ត្រៃយ៍។ សូមប្រាកដមុនពេលអនុវត្ត។') }}
                </p>

                <x-danger-button 
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
                    class="mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg">
                    {{ __('លុបគណនី') }}
                </x-danger-button>
            </div>
        </div>
    </div>

    {{-- JavaScript សម្រាប់គ្រប់គ្រងការជ្រើសរើសរូបភាព --}}
    <script>
        // នៅពេលចុចលើរង្វង់រូបភាព ឱ្យវាហៅ Input File
        document.getElementById('profile-picture-container').addEventListener('click', function() {
            document.getElementById('profile_picture').click();
        });

        // បង្ហាញរូបភាពភ្លាមៗ (Preview) នៅពេលជ្រើសរើស File
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
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
                        img.className = 'object-cover w-full h-full transition-all duration-300';
                        placeholder.replaceWith(img);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>