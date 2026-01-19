<x-guest-layout>
    <style>
        /* 🎨 Custom Styling */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        body {
            font-family: 'Roboto', sans-serif;
            /* Dark, subtle gradient background */
            background: linear-gradient(135deg, #1f2937 0%, #0d121c 100%); 
        }

        /* Override default Tailwind form styles for select to match input */
        #program_id, #generation {
            /* Ensuring select matches input styling perfectly */
            appearance: none; /* Remove default arrow/styling in some browsers */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239CA3AF' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem !important; /* Make space for the custom arrow */
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
                <h2 class="text-2xl font-bold text-white">{{ __('ចុះឈ្មោះគណនីថ្មី') }}</h2>
                <p class="text-sm text-gray-400 mt-2">{{ __('សូមបំពេញព័ត៌មានដើម្បីបញ្ចប់ការចុះឈ្មោះ។') }}</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="student_id_code" :value="__('លេខសម្គាល់និស្សិត')" class="text-gray-300"/>
                    <x-text-input id="student_id_code" class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200" type="text" name="student_id_code" value="{{ old('student_id_code') }}" required autofocus />
                    <x-input-error :messages="$errors->get('student_id_code')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('អ៊ីមែល')" class="text-gray-300"/>
                    <x-text-input id="email" class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200" type="email" name="email" value="{{ old('email') }}" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="name" :value="__('ឈ្មោះបង្ហាញ')" class="text-gray-300"/>
                    <x-text-input id="name" class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200" type="text" name="name" value="{{ old('name') }}" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="program_id" :value="__('កម្មវិធីសិក្សា')" class="text-gray-300"/>
                    <select id="program_id" name="program_id" class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200" required>
                        <option value="">{{ __('ជ្រើសរើសកម្មវិធីសិក្សា') }}</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->name_km }} ({{ $program->name_en }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('program_id')" class="mt-2" />
                </div>
                
                <div>
                    <x-input-label for="generation" :value="__('ជំនាន់')" class="text-gray-300"/>
                    <select id="generation" name="generation" class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200" required>
                        <option value="">{{ __('ជ្រើសរើសជំនាន់') }}</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation }}" {{ old('generation') == $generation ? 'selected' : '' }}>
                                {{ $generation }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('generation')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('ពាក្យសម្ងាត់')" class="text-gray-300" />
                    <div class="relative">
                        <x-text-input id="password"
                            class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200
                                focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 pr-10"
                            type="password" name="password" required autocomplete="new-password" />

                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div id="password-strength" class="text-sm mt-2 text-gray-400"></div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('បញ្ជាក់ពាក្យសម្ងាត់')" class="text-gray-300" />

                    <div class="relative">
                        <x-text-input id="password_confirmation"
                            class="block w-full px-4 py-3 rounded-lg border-gray-700 bg-gray-700 text-gray-200
                                focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 pr-10"
                            type="password" name="password_confirmation" required autocomplete="new-password" />

                        <button type="button" id="togglePasswordConfirm"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center pt-2">
                    <x-primary-button class="w-full py-3 text-lg font-bold bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 rounded-lg shadow-md transition duration-200">
                        {{ __('ចុះឈ្មោះ') }}
                    </x-primary-button>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-400">
                        {{ __('មានគណនីរួចហើយ?') }} 
                        <a href="{{ route('login') }}" class="underline text-indigo-400 hover:text-indigo-300 font-medium">{{ __('ចូលគណនី') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Toggle Password Visibility Logic ---
        function togglePassword(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            
            if (input && button) {
                button.addEventListener('click', function() {
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    const icon = this.querySelector('i');
                    // Toggle the eye icon between 'fa-eye' (show) and 'fa-eye-slash' (hide)
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        }

        // Apply toggle to both password fields
        togglePassword('password', 'togglePassword');
        togglePassword('password_confirmation', 'togglePasswordConfirm');

        // --- 2. Password Strength Checker Logic ---
        const passwordInput = document.getElementById('password');
        const strengthText = document.getElementById('password-strength');

        if (passwordInput && strengthText) {
            passwordInput.addEventListener('input', () => {
                const value = passwordInput.value;
                let strength = 0;
                // Criteria checks
                if (/[A-Z]/.test(value)) strength++;       // Uppercase
                if (/[a-z]/.test(value)) strength++;       // Lowercase
                if (/[0-9]/.test(value)) strength++;       // Numbers
                if (/[@$!%*?&]/.test(value)) strength++;   // Symbols
                if (value.length >= 8) strength++;         // Length

                const levels = ['ខ្សោយ', 'មធ្យម', 'ល្អ', 'ខ្លាំង', 'ខ្លាំងណាស់'];
                const colors = ['text-red-400', 'text-yellow-400', 'text-green-400', 'text-green-500', 'text-green-600'];
                
                // Reset classes before setting the new one
                strengthText.className = 'text-sm mt-2'; 
                
                if (value) {
                    const levelIndex = strength > 0 ? strength - 1 : 0;
                    strengthText.textContent = 'កម្លាំងពាក្យសម្ងាត់៖ ' + levels[levelIndex];
                    strengthText.classList.add(colors[levelIndex]);
                } else {
                    strengthText.textContent = '';
                }
            });
        }
    });
    </script>
</x-guest-layout>