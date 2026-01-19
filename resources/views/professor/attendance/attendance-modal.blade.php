<div>
    @if($isOpen)
        <style>
            .scan-line {
                width: 100%; height: 4px; background: #3b82f6; box-shadow: 0 0 10px #3b82f6;
                position: absolute; animation: scan 2.5s linear infinite; border-radius: 50%;
            }
            @keyframes scan {
                0%, 100% { top: 0%; opacity: 0; } 10%, 90% { opacity: 1; } 100% { top: 100%; opacity: 0; }
            }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none;  scrollbar-width: none; }
        </style>

        {{-- 1. ផ្ទៃខាងក្រោយ (Main Backdrop) --}}
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/80 backdrop-blur-md transition-opacity duration-300">
            
            {{-- 2. ប្រអប់ Modal ធំ (Main Container) --}}
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-6xl mx-4 flex flex-col md:flex-row h-[85vh] overflow-hidden relative border border-white/20"
                 wire:poll.3s> 

                {{-- ប៊ូតុងបិទ (Close X) --}}
                <button wire:click="close" class="absolute top-6 right-6 z-20 p-2 rounded-full bg-white/10 hover:bg-white/20 text-gray-500 hover:text-red-500 backdrop-blur-sm transition-all shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                {{-- === ផ្នែកខាងឆ្វេង: QR Presenter === --}}
                <div class="w-full md:w-5/12 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-10 flex flex-col items-center justify-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

                    <div class="relative z-10 text-center mb-8">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/10 backdrop-blur-md mb-4">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            <span class="text-xs font-bold tracking-wider uppercase text-green-300">{{ __('Live Attendance') }}</span>
                        </div>
                        <h2 class="text-4xl font-black tracking-tight mb-2">{{ __('ស្កែនវត្តមាន') }}</h2>
                        <p class="text-slate-400 font-medium text-lg">{{ __('ID មុខវិជ្ជា:') }} <span class="text-white font-mono bg-slate-700 px-2 py-0.5 rounded ml-1">#{{ $courseId }}</span></p>
                    </div>

                    <div class="relative z-10 group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-[2rem] blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative p-6 bg-white rounded-[1.8rem] shadow-2xl">
                            <div class="relative overflow-hidden rounded-xl">
                                {!! $qrCodeImage !!}
                                <div class="scan-line"></div>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 mt-8 flex items-center gap-3 bg-red-500/10 border border-red-500/20 px-5 py-3 rounded-2xl">
                        <div class="bg-red-500/20 p-2 rounded-full text-red-400 animate-pulse">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-white text-sm font-bold">{{ __('សុពលភាព QR') }}</p>
                            <p class="text-red-300 text-xs">{{ __('ផ្លាស់ប្តូររៀងរាល់ 15 វិនាទីម្តង') }}</p>
                        </div>
                    </div>
                </div>

                {{-- === ផ្នែកខាងស្តាំ: Student List === --}}
                <div class="w-full md:w-7/12 bg-white flex flex-col relative z-0">
                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white/80 backdrop-blur-sm sticky top-0 z-10">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ __('បញ្ជីឈ្មោះសិស្ស') }}</h3>
                            <p class="text-sm text-gray-400">{{ __('កំពុងរង់ចាំការស្កែនពីសិស្ស...') }}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-4xl font-black text-blue-600 leading-none">
                                {{ isset($attendances) ? str_pad(count($attendances), 2, '0', STR_PAD_LEFT) : '00' }}
                            </span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Total Scanned') }}</span>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-4 no-scrollbar bg-gray-50/50">
                        @if(isset($attendances) && count($attendances) > 0)
                            @foreach($attendances as $index => $record)
                                <div class="group flex items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 animate-fade-in-up" style="animation-delay: {{ $index * 50 }}ms;">
                                    <div class="relative">
                                        <img src="{{ $record->student->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.$record->student->name.'&background=random' }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-md group-hover:scale-105 transition-transform">
                                        <div class="absolute -bottom-1 -right-1 bg-green-500 border-2 border-white w-4 h-4 rounded-full"></div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $record->student->name }}</h4>
                                            <span class="text-xs font-mono font-medium text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">{{ $record->created_at->format('h:i:s A') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                            {{ __('បានកត់ត្រាវត្តមានជោគជ័យ') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-center opacity-60">
                                <div class="bg-gray-100 p-6 rounded-full mb-4 animate-bounce">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-600">{{ __('មិនទាន់មានសិស្សស្កែន') }}</h4>
                                <p class="text-gray-400 max-w-xs mx-auto mt-2">{{ __('សូមឱ្យសិស្សបើក App ហើយស្កែន QR Code ដែលបង្ហាញនៅខាងឆ្វេង។') }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer / Action Bar --}}
                    <div class="p-6 border-t border-gray-100 bg-white flex justify-end gap-4 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] relative z-20">
                        <button wire:click="close" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-all">
                            {{ __('បិទផ្ទាំង') }}
                        </button>

                        {{-- 👉 ប៊ូតុងនេះគ្រាន់តែបើកផ្ទាំង Confirm (អត់ប្រើ wire:confirm ទៀតទេ) --}}
                        <button wire:click="$set('showConfirmation', true)"
                                class="group relative px-8 py-3 rounded-xl font-bold text-white bg-red-600 overflow-hidden shadow-lg shadow-red-200 transition-all hover:scale-105 active:scale-95">
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer"></div>
                            <span class="flex items-center gap-2 relative z-10">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                {{ __('បញ្ចប់ និងរក្សាទុក') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔥 3. CUSTOM CONFIRMATION MODAL (ផ្ទាំងបញ្ជាក់ថ្មី) 🔥 --}}
        @if($showConfirmation)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-all animate-fade-in">
            <div class="bg-white p-8 rounded-[2rem] shadow-2xl max-w-md w-full text-center relative transform scale-100 animate-bounce-in">
                
                {{-- Warning Icon --}}
                <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6 text-red-600 shadow-sm">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-gray-800 mb-2">{{ __('តើអ្នកប្រាកដទេ?') }}</h3>
                <p class="text-gray-500 mb-8 leading-relaxed">
                    {{ __('ការចុច "បញ្ចប់" នឹងបិទបញ្ជីវត្តមានភ្លាមៗ។ សិស្សណាដែលមិនទាន់បានស្កែន នឹងត្រូវកំណត់ថា') }} 
                    <span class="font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded">{{ __('"អវត្តមាន"') }}</span> 
                    {{ __('ដោយស្វ័យប្រវត្តិ។') }}
                </p>

                <div class="flex gap-3">
                    {{-- Cancel Button --}}
                    <button wire:click="$set('showConfirmation', false)" 
                            class="flex-1 py-3.5 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">
                        {{ __('បោះបង់') }}
                    </button>

                    {{-- Confirm Button --}}
                    <button wire:click="closeAttendance" 
                            class="flex-1 py-3.5 rounded-2xl font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all transform hover:-translate-y-1">
                        {{ __('យល់ព្រម') }}
                    </button>
                </div>

            </div>
        </div>
        @endif

    @endif
</div>