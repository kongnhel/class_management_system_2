<x-app-layout>
    @php
        // ទាញយក URL រូបភាពពី ImgBB ដោយផ្ទាល់ចេញពី userProfile
        $profileUrl = $user->userProfile?->profile_picture_url;
    @endphp

    <div class="py-12 bg-[#f8fafc] min-h-screen font-['Battambang']">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Form Card --}}
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[3rem] overflow-hidden border border-slate-100">
                
                {{-- Header Section --}}
                <div class="relative h-32 bg-gradient-to-r from-indigo-600 to-blue-500">
                    <div class="absolute -bottom-16 left-0 right-0 flex justify-center">
                        <div class="relative group">
                            {{-- Profile Picture Container --}}
                            <div id="profile-picture-container" class="w-32 h-32 md:w-36 md:h-36 rounded-[2.5rem] bg-white p-1.5 shadow-2xl cursor-pointer overflow-hidden transition-transform active:scale-95">
                                <div class="w-full h-full rounded-[2rem] overflow-hidden bg-slate-100 flex items-center justify-center">
                                    {{-- បង្ហាញរូបភាពពី ImgBB ដោយផ្ទាល់ (លុប asset('storage/') ចេញ) --}}
                                    @if ($profileUrl)
                                        <img src="{{ $profileUrl }}" alt="{{ $user->name }}" class="object-cover w-full h-full" id="profile-picture-preview">
                                    @else
                                        <div id="profile-picture-placeholder" class="text-indigo-500 text-4xl font-black">
                                            {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                {{-- Overlay icon --}}
                                <div class="absolute inset-1.5 bg-black/40 rounded-[2rem] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-camera text-white text-xl"></i>
                                </div>
                            </div>
                            {{-- Badge --}}
                            <div class="absolute bottom-1 right-1 bg-emerald-500 text-white w-8 h-8 rounded-full border-4 border-white flex items-center justify-center shadow-lg">
                                <i class="fas fa-plus text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-20 pb-12 px-8 md:px-16">
                    <div class="text-center mb-10">
                        <h2 class="text-2xl font-black text-slate-800">{{ __('កែប្រែប្រវត្តិរូប') }}</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">{{ __('រក្សាទុកព័ត៌មានផ្ទាល់ខ្លួនរបស់អ្នកឱ្យទាន់សម័យ') }}</p>
                    </div>

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

                    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Full Name (Khmer) --}}
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('ឈ្មោះពេញ (ខ្មែរ)') }} <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <input type="text" name="full_name_km" id="full_name_km" value="{{ old('full_name_km', $user->userProfile->full_name_km ?? '') }}" required 
                                           class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700" 
                                           placeholder="បញ្ជាក់ឈ្មោះជាភាសាខ្មែរ">
                                </div>
                                <x-input-error :messages="$errors->get('full_name_km')" class="mt-2" />
                            </div>

                            {{-- Full Name (English) --}}
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('ឈ្មោះពេញ (អង់គ្លេស)') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                    <input type="text" name="full_name_en" id="full_name_en" value="{{ old('full_name_en', $user->userProfile->full_name_en ?? '') }}" 
                                           class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700" 
                                           placeholder="Full Name in English">
                                </div>
                            </div>

                            {{-- Gender --}}
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('ភេទ') }} <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none">
                                        <i class="fas fa-venus-mars"></i>
                                    </span>
                                    <select id="gender" name="gender" required 
                                            class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                        <option value="" disabled selected>{{ __('ជ្រើសរើសភេទ') }}</option>
                                        <option value="male" @if(old('gender', $user->userProfile->gender ?? '') == 'male') selected @endif>{{ __('ប្រុស') }}</option>
                                        <option value="female" @if(old('gender', $user->userProfile->gender ?? '') == 'female') selected @endif>{{ __('ស្រី') }}</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('ថ្ងៃខែឆ្នាំកំណើត') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', isset($user->userProfile->date_of_birth) ? \Carbon\Carbon::parse($user->userProfile->date_of_birth)->format('Y-m-d') : '') }}" 
                                           class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                                </div>
                            </div>

                            {{-- Phone Number --}}
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('លេខទូរស័ព្ទ') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="fas fa-phone-alt"></i>
                                    </span>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->userProfile->phone_number ?? '') }}" 
                                           class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700" 
                                           placeholder="012 345 678">
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('អាសយដ្ឋាន') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input type="text" name="address" id="address" value="{{ old('address', $user->userProfile->address ?? '') }}" 
                                           class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all font-bold text-slate-700" 
                                           placeholder="រាជធានីភ្នំពេញ, កម្ពុជា">
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row items-center gap-4 pt-10">
                            <button type="submit" 
                                    class="w-full sm:flex-[2] py-4 bg-indigo-600 text-white rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i>
                                {{ __('រក្សាទុកការកែប្រែ') }}
                            </button>
                            
                            <a href="{{ route('student.profile.show') }}" 
                               class="w-full sm:flex-1 py-4 bg-white border border-slate-200 text-slate-500 rounded-2xl font-black text-center hover:bg-slate-50 transition-all">
                                {{ __('បោះបង់') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Trigger file input នៅពេលចុចលើរង្វង់រូបភាព
        document.getElementById('profile-picture-container').addEventListener('click', function() {
            document.getElementById('profile_picture').click();
        });

        // បង្ហាញរូបភាព Preview ភ្លាមៗ
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            let preview = document.getElementById('profile-picture-preview');
            let placeholder = document.getElementById('profile-picture-placeholder');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                    } else if (placeholder) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.id = 'profile-picture-preview';
                        img.className = 'object-cover w-full h-full';
                        placeholder.replaceWith(img);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>