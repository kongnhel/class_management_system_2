<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:wght@300;400;700&display=swap');

        :root {
            /* 🌿 Using the Emerald Green from your image */
            --primary-green: #10b981; 
            --primary-hover: #059669;
        }

        body {
            font-family: 'Inter', 'Kantumruy Pro', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: #020617;
        }

        /* 🏛️ Full Screen Background Logic */
        .full-screen-portal {
            width: 100vw;
            min-height: 100vh;
            position: relative;
            background-image: linear-gradient(rgba(2, 6, 23, 0.75), rgba(16, 24, 59, 0.85)), 
                              url('{{ asset('assets/image/download (5).jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Animated Grid Overlay */
        .full-screen-portal::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(16, 185, 129, 0.08) 1.5px, transparent 1.5px);
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
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Premium Large Glass Card */
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.9);
            width: 100%;
            max-width: 700px; /* Increased width for a more "Full" feel */
        }

        .input-focus-green:focus {
            border-color: var(--primary-green) !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
            background-color: rgba(255, 255, 255, 0.08);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2310b981' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.2em 1.2em;
        }
    </style>
    
    <div class="full-screen-portal">
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
                <div class="absolute inset-0 rounded-full bg-emerald-500 blur-3xl opacity-30"></div>
                <img src="{{ asset('assets/image/nmu_Logo.png') }}" alt="NMU Logo" 
                     class="relative w-28 h-28 rounded-full border-2 border-white/20 shadow-2xl transition-all duration-700 group-hover:scale-110">
            </div>
            <h1 class="text-white mt-6 font-bold text-xl tracking-[0.6em] uppercase opacity-80">NMU Portal</h1>
        </div>

        <div class="glass-card p-10 sm:p-14 overflow-hidden sm:rounded-[3rem] reveal" style="animation-delay: 0.1s">
            
            <div class="mb-12 text-center">
                <h2 class="text-4xl font-black text-white tracking-tight">{{ __('បង្កើតគណនីថ្មី') }}</h2>
                <div class="flex items-center justify-center gap-4 mt-5">
                    <div class="w-12 h-1.5 bg-emerald-500 rounded-full"></div>
                    <p class="text-gray-300 text-sm font-semibold uppercase tracking-widest">{{ __('Student Enrollment') }}</p>
                    <div class="w-12 h-1.5 bg-emerald-500 rounded-full"></div>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-7">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('លេខសម្គាល់និស្សិត') }}</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500"><i class="fa-solid fa-id-card"></i></span>
                            <input id="student_id_code" type="text" name="student_id_code" value="{{ old('student_id_code') }}" required autofocus
                                class="block w-full pl-12 pr-4 py-4 rounded-2xl border-white/10 bg-white/5 text-white placeholder-gray-600 transition-all input-focus-green outline-none"
                                placeholder="ID-0000X" />
                        </div>
                        <div class="mt-2">
                            <p class="text-[10px] text-emerald-400/80 italic font-medium">
                                * បញ្ចូលលេខសម្គាល់ដើម្បីទាញយកព័ត៌មានដែលរៀបចំដោយរដ្ឋបាល NMU អូតូ
                            </p>
                        </div>
                        <x-input-error :messages="$errors->get('student_id_code')" class="mt-2 text-xs" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('អ៊ីមែល') }}</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500"><i class="fa-solid fa-envelope"></i></span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                class="block w-full pl-12 pr-4 py-4 rounded-2xl border-white/10 bg-white/5 text-white placeholder-gray-600 transition-all input-focus-green outline-none"
                                placeholder="name@nmu.edu.kh" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('ឈ្មោះបង្ហាញ') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500"><i class="fa-solid fa-user"></i></span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                            class="block w-full pl-12 pr-4 py-4 rounded-2xl border-white/10 bg-white/5 text-white placeholder-gray-600 transition-all input-focus-green outline-none"
                            placeholder="Full Name" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('កម្មវិធីសិក្សា') }}</label>
                        <select id="program_id" name="program_id" class="block w-full px-5 py-4 rounded-2xl border-white/10 bg-white/5 text-white transition-all input-focus-green outline-none" required>
                            <option value="" class="bg-slate-900">{{ __('ជ្រើសរើសកម្មវិធី') }}</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }} class="bg-slate-900">
                                    {{ $program->name_km }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('ជំនាន់') }}</label>
                        <select id="generation" name="generation" class="block w-full px-5 py-4 rounded-2xl border-white/10 bg-white/5 text-white transition-all input-focus-green outline-none" required>
                            <option value="" class="bg-slate-900">{{ __('ជ្រើសរើសជំនាន់') }}</option>
                            @foreach($generations as $generation)
                                <option value="{{ $generation }}" {{ old('generation') == $generation ? 'selected' : '' }} class="bg-slate-900">
                                    {{ $generation }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('ពាក្យសម្ងាត់') }}</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                class="block w-full pl-5 pr-12 py-4 rounded-2xl border-white/10 bg-white/5 text-white transition-all input-focus-green outline-none"
                                placeholder="••••••••" />
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-emerald-400">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="mt-4 px-1">
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div id="strength-bar" class="h-full w-0 transition-all duration-700 ease-out"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('បញ្ជាក់ពាក្យសម្ងាត់') }}</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="block w-full pl-5 pr-12 py-4 rounded-2xl border-white/10 bg-white/5 text-white transition-all input-focus-green outline-none"
                                placeholder="••••••••" />
                            <button type="button" id="togglePasswordConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-emerald-400">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-8">
                    <button type="submit" class="group relative w-full overflow-hidden rounded-2xl bg-emerald-600 px-8 py-5 font-black text-white transition-all hover:bg-emerald-500 active:scale-[0.98] shadow-2xl">
                        <span class="relative z-10 flex items-center justify-center gap-4 uppercase tracking-[0.25em]">
                            {{ __('ចុះឈ្មោះឥឡូវនេះ') }}
                            <i class="fa-solid fa-chevron-right text-sm transition-transform group-hover:translate-x-2"></i>
                        </span>
                        <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-1000 group-hover:translate-x-full"></div>
                    </button>
                </div>
            </form>

            <div class="text-center mt-12">
                <p class="text-gray-400 font-medium tracking-wide">
                    {{ __('មានគណនីរួចហើយ?') }} 
                    <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-black ml-2 transition-all underline underline-offset-8 decoration-2">
                        {{ __('ចូលគណនី') }}
                    </a>
                </p>
            </div>
        </div>

        <footer class="mt-16 text-center reveal" style="animation-delay: 0.6s">
            <div class="flex items-center justify-center gap-8 mb-6 text-gray-500 text-xs font-bold uppercase tracking-widest">
                <a href="#" class="hover:text-emerald-400 transition-colors">Privacy</a>
                <span class="w-1.5 h-1.5 bg-emerald-900 rounded-full"></span>
                <a href="#" class="hover:text-emerald-400 transition-colors">Terms</a>
                <span class="w-1.5 h-1.5 bg-emerald-900 rounded-full"></span>
                <a href="#" class="hover:text-emerald-400 transition-colors">Support</a>
            </div>
            <p class="text-gray-600 text-[10px] uppercase tracking-[0.6em] font-black">
                &copy; {{ date('Y') }} National Management University
            </p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Password Fields
        function setupToggle(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            if (input && button) {
                button.addEventListener('click', function() {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    const icon = this.querySelector('i');
                    icon.className = isPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
                });
            }
        }
        setupToggle('password', 'togglePassword');
        setupToggle('password_confirmation', 'togglePasswordConfirm');

        // Password Strength Interaction
        const pswInput = document.getElementById('password');
        const sBar = document.getElementById('strength-bar');

        if (pswInput && sBar) {
            pswInput.addEventListener('input', () => {
                const val = pswInput.value;
                let strength = 0;
                if (val.length >= 8) strength++;
                if (/[A-Z]/.test(val)) strength++;
                if (/[0-9]/.test(val)) strength++;
                if (/[!@#$%^&*]/.test(val)) strength++;

                const colors = ['bg-transparent', 'bg-red-500', 'bg-orange-500', 'bg-yellow-400', 'bg-emerald-500'];
                sBar.className = `h-full transition-all duration-700 ease-out ${colors[strength]}`;
                sBar.style.width = (strength * 25) + '%';
            });
        }
    });


document.getElementById('student_id_code').addEventListener('blur', function() {
    let code = this.value;
    
    if (code.length >= 3) {
        // ១. បង្ហាញផ្ទាំង Loading ភ្លាមៗ
        Swal.fire({
            title: 'កំពុងស្វែងរកទិន្នន័យ...',
            html: 'សូមរង់ចាំមួយភ្លែត ពួកយើងកំពុងឆែកមើលបញ្ជីឈ្មោះរបស់រដ្ឋបាល NMU',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading(); // បង្ហាញ Spinner វិលៗ
            }
        });

        // ២. ហៅទៅកាន់ API ដើម្បីទាញទិន្នន័យ
        fetch(`/api/check-student/${code}`)
            .then(res => res.json())
            .then(data => {
                // បិទផ្ទាំង Loading វិញ
                Swal.close(); 

                if (data.success) {
                    // ៣. បង្ហាញផ្ទាំង Confirm ព័ត៌មានដែលរកឃើញ
                    Swal.fire({
                        title: 'រកឃើញអត្តសញ្ញាណរបស់អ្នក!',
                        html: `តើអ្នកពិតជាមានឈ្មោះ <b>${data.name}</b> ជំនាន់ <b>${data.generation}</b> មែនដែរឬទេ?<br><br>` +
                             `<span style="font-size: 0.8em; color: #10b981;">ព័ត៌មាននេះត្រូវបានផ្តល់ដោយរដ្ឋបាល NMU</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'បាទ/ចាស ត្រឹមត្រូវ',
                        cancelButtonText: 'មិនមែនទេ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // បំពេញទិន្នន័យអូតូ និងចាក់សោរ Input
                            document.getElementById('name').value = data.name;
                            document.getElementById('program_id').value = data.program_id;
                            document.getElementById('generation').value = data.generation;

                            document.getElementById('name').readOnly = true;
                            document.getElementById('program_id').style.pointerEvents = 'none';
                            document.getElementById('generation').style.pointerEvents = 'none';
                            
                            Swal.fire({
                                title: 'អរគុណ!',
                                text: 'សូមបន្តបង្កើតអ៊ីមែល និងពាក្យសម្ងាត់របស់អ្នក។',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            // បើមិនមែនទេ លុប ID ចេញ
                            document.getElementById('student_id_code').value = '';
                        }
                    });
                } else {
                    // បើរកមិនឃើញ
                    Swal.fire({
                        title: 'រកមិនឃើញ!',
                        text: 'លេខសម្គាល់និស្សិតនេះមិនទាន់មានក្នុងប្រព័ន្ធរដ្ឋបាលឡើយ។ សូមទាក់ទងមកកាន់ការិយាល័យសិក្សា!',
                        icon: 'error'
                    });
                    document.getElementById('student_id_code').value = '';
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire('Error!', 'មានបញ្ហាបច្ចេកទេសក្នុងការតភ្ជាប់ទៅកាន់ Server។', 'error');
                console.error('Fetch error:', error);
            });
    }
});
    </script>
</x-guest-layout>