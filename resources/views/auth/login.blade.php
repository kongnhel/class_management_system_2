<x-guest-layout>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:wght@300;400;700&display=swap');

        :root {
            /* 🌿 Official University Emerald Green */
            --primary-green: #10b981; 
            --primary-hover: #059669;
            --glow-color: rgba(16, 185, 129, 0.4);
        }

        body {
            font-family: 'Inter', 'Kantumruy Pro', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: #020617;
        }

        /* 🏛️ Full-Screen Background with Dark Overlay */
        .portal-wrapper {
            width: 100vw;
            min-height: 100vh;
            position: relative;
            /* High-quality overlay for text legibility */
            background-image: linear-gradient(rgba(2, 6, 23, 0.75), rgba(2, 6, 23, 0.85)), 
                              url('{{ asset('assets/image/download (5).jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Ambient Animated Grid Overlay */
        .portal-wrapper::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(16, 185, 129, 0.1) 1.5px, transparent 1.5px);
            background-size: 45px 45px;
            pointer-events: none;
            z-index: 1;
        }

        /* Entrance Animation */
        .reveal {
            animation: revealUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            z-index: 10;
        }

        @keyframes revealUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Enhanced High-End Glassmorphism */
        .glass-portal-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(16, 185, 129, 0.2);
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.8);
        }

        /* Custom Input Focus Effect */
        .focus-green:focus {
            border-color: var(--primary-green) !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
            background-color: rgba(255, 255, 255, 0.08);
        }
    </style>
    
    <div class="portal-wrapper">
        {{-- Modern Floating Toast --}}
@if (session('success') || session('error'))
<div 
    x-data="{ 
        show: false, 
        progress: 100,
        startTimer() {
            this.show = true;
            let interval = setInterval(() => {
                this.progress -= 1;
                if (this.progress <= 0) {
                    this.show = false;
                    clearInterval(interval);
                }
            }, 50); // 5 seconds total (50ms * 100)
        }
    }" 
    x-init="startTimer()"
    x-show="show" 
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="translate-y-12 opacity-0 sm:translate-y-0 sm:translate-x-12"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-6 right-6 z-[9999] w-full max-w-sm"
>
    <div class="relative overflow-hidden bg-white/80 backdrop-blur-xl border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.1)] rounded-2xl p-4 ring-1 ring-black/5">
        <div class="flex items-start gap-4">
            
            {{-- Modern Icon Logic --}}
            <div class="flex-shrink-0">
                @if(session('success'))
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Text Content --}}
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-bold text-gray-900 leading-tight">
                    {{ session('success') ? __('ជោគជ័យ!') : __('បរាជ័យ!') }}
                </p>
                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                    {{ session('success') ?? session('error') }}
                </p>
            </div>

            {{-- Manual Close --}}
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Progress Bar (The "Modern" Touch) --}}
        <div class="absolute bottom-0 left-0 h-1 bg-gray-100 w-full">
            <div 
                class="h-full transition-all duration-75 ease-linear {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}"
                :style="`width: ${progress}%`"
            ></div>
        </div>
    </div>
</div>
@endif
        
        <div class="reveal mb-10 text-center">
            <div class="relative inline-block group">
                <div class="absolute inset-0 rounded-full bg-emerald-500 blur-3xl opacity-30 group-hover:opacity-50 transition duration-700"></div>
                <img src="{{ asset('assets/image/nmu_Logo.png') }}" alt="NMU Logo" 
                     class="relative w-28 h-28 rounded-full border-2 border-white/20 shadow-2xl transition-all duration-700 group-hover:scale-110">
            </div>
            <h1 class="text-white mt-6 font-bold text-xl tracking-[0.5em] uppercase opacity-80">NMU PORTAL</h1>
            <p class="text-emerald-400 text-[10px] font-bold tracking-[0.3em] uppercase mt-2 opacity-60">Management System</p>
        </div>

        <div class="w-full sm:max-w-[460px] glass-portal-card p-10 sm:p-12 overflow-hidden sm:rounded-[3rem] reveal" style="animation-delay: 0.1s">
            
            <x-auth-session-status class="mb-6 text-center text-emerald-400 font-medium bg-emerald-500/10 py-3 rounded-xl border border-emerald-500/20" :status="session('status')" />

            <div class="mb-10 text-center sm:text-left">
                <h2 class="text-3xl font-black text-white tracking-tight">{{ __('ចូលប្រើប្រាស់') }}</h2>
                <div class="flex items-center justify-center sm:justify-start gap-3 mt-4">
                    <div class="w-10 h-1.5 bg-emerald-500 rounded-full"></div>
                    <p class="text-gray-400 text-sm font-semibold tracking-wide">{{ __('NMU Class System') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-7">
                @csrf

                <div class="reveal" style="animation-delay: 0.2s">
                    <label class="block text-[11px] font-bold text-emerald-500 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('អាសយដ្ឋានអ៊ីម៉ែល') }}</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 group-focus-within:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="block w-full pl-12 pr-4 py-4 rounded-2xl border-white/10 bg-white/5 text-white placeholder-gray-600 transition-all duration-300 focus-green outline-none"
                            placeholder="student@gmail.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <div class="reveal" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-[11px] font-bold text-emerald-500 uppercase tracking-[0.2em] ml-1">{{ __('ពាក្យសម្ងាត់') }}</label>
                        @if (Route::has('password.request'))
                            <a class="text-[10px] text-gray-500 hover:text-emerald-400 transition-colors font-bold uppercase tracking-tighter" href="{{ route('password.request') }}">
                                {{ __('ភ្លេចលេខសម្ងាត់?') }}
                            </a>
                        @endif
                    </div>
                    
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 group-focus-within:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="block w-full pl-12 pr-12 py-4 rounded-2xl border-white/10 bg-white/5 text-white transition-all duration-300 focus-green outline-none"
                            placeholder="••••••••" />
                        
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <div class="space-y-7 pt-2">
                    <label class="flex items-center cursor-pointer group w-fit">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/10 bg-white/5 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-950 transition-all">
                        <span class="ms-3 text-xs text-gray-400 group-hover:text-gray-200 transition-colors tracking-wide">{{ __('ចងចាំខ្ញុំណាមួយ') }}</span>
                    </label>

                    <button type="submit" class="group relative w-full overflow-hidden rounded-2xl bg-emerald-600 px-8 py-5 font-black text-white transition-all hover:bg-emerald-500 active:scale-[0.98] shadow-2xl shadow-emerald-900/40 reveal" style="animation-delay: 0.4s">
                        <span class="relative z-10 flex items-center justify-center gap-4 uppercase tracking-[0.2em]">
                            {{ __('ចូលប្រើប្រព័ន្ធ') }}
                            <i class="fa-solid fa-arrow-right-to-bracket transition-transform group-hover:translate-x-1"></i>
                        </span>
                        <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-1000 group-hover:translate-x-full"></div>
                    </button>
                </div>
            </form>

            <div class="text-center mt-12 reveal" style="animation-delay: 0.5s">
                <p class="text-sm text-gray-400 font-medium">
                    {{ __('មិនទាន់មានគណនីមែនទេ?') }} 
                    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-black ml-2 transition-all underline underline-offset-8 decoration-2">
                        {{ __('ចុះឈ្មោះទីនេះ') }}
                    </a>
                </p>
            </div>
        </div>

        <footer class="mt-16 text-center reveal" style="animation-delay: 0.6s">
            <div class="flex items-center justify-center gap-8 mb-6 text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">
                <a href="#" class="hover:text-emerald-400 transition-colors">Privacy Policy</a>
                <span class="w-1.5 h-1.5 bg-emerald-900 rounded-full"></span>
                <a href="#" class="hover:text-emerald-400 transition-colors">Technical Support</a>
            </div>
            <p class="text-gray-600 text-[10px] uppercase tracking-[0.5em] font-black">
                &copy; {{ date('Y') }} National Management University
            </p>
        </footer>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');

            if (passwordInput && toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    
                    const icon = this.querySelector('i');
                    icon.className = isPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
                    
                    this.classList.add('scale-110');
                    setTimeout(() => this.classList.remove('scale-110'), 150);
                });
            }
        });
    </script>
</x-guest-layout>