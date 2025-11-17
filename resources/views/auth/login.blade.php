<x-guest-layout>
    <style>
        /* 🎨 Custom Styling */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        body {
            font-family: 'Roboto', sans-serif;
            /* Dark, subtle gradient background */
            background: linear-gradient(135deg, #1f2937 0%, #0d121c 100%); 
        }
    </style>
    
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="/">
                <img src="{{ asset('assets/image/nmu_Logo.png') }}" alt="NMU Logo" class="w-20 h-20 fill-current text-gray-500 rounded-full shadow-lg">
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-800 border border-gray-700 shadow-xl overflow-hidden sm:rounded-2xl">
            <x-auth-session-status class="mb-6 text-center text-green-400 font-medium" :status="session('status')" />

            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white">{{ __('ចូលគណនីរបស់អ្នក') }}</h2>
                <p class="text-sm text-gray-400 mt-2">{{ __('សូមបញ្ចូលព័ត៌មានលម្អិតរបស់អ្នកដើម្បីចូលគណនី។') }}</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-2">
                    <x-input-label for="email" :value="__('អ៊ីម៉ែល')" class="text-gray-300"/>
                    <x-text-input id="email" class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" :value="__('ពាក្យសម្ងាត់')" class="text-gray-300"/>
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-indigo-400 hover:text-indigo-300 font-medium" href="{{ route('password.request') }}">
                                {{ __('ភ្លេចពាក្យសម្ងាត់?') }}
                            </a>
                        @endif
                    </div>
                    
                    <div class="relative">
                        <x-text-input id="password" 
                            class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200 
                                focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 pr-10" 
                            type="password" name="password" required autocomplete="current-password" />
                        
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-start items-center mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900" name="remember">
                        <span class="ms-2 text-sm text-gray-400">{{ __('ចងចាំខ្ញុំ') }}</span>
                    </label>
                    </div>

                <div class="flex items-center justify-center mt-6">
                    <x-primary-button class="w-full py-3 text-lg font-bold bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 rounded-lg shadow-md transition duration-200">
                        {{ __('ចូលគណនី') }}
                    </x-primary-button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-400">
                        {{ __('មិនទាន់មានគណនីមែនទេ?') }} 
                        <a href="{{ route('register') }}" class="underline text-indigo-400 hover:text-indigo-300 font-medium">{{ __('ចុះឈ្មោះ') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');

            if (passwordInput && toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                    
                    // Toggle the icon between 'eye' and 'eye-slash'
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</x-guest-layout>