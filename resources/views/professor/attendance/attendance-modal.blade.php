<div>
    @if($isOpen)
        {{-- 1. ផ្ទៃខាងក្រោយខ្មៅ (Overlay) --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
            
            {{-- 2. ប្រអប់ Modal ធំ (Main Container) --}}
            {{-- ប្រើ wire:poll.3s ដើម្បី Update បញ្ជីឈ្មោះសិស្សរៀងរាល់ 3 វិនាទី --}}
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl mx-4 flex flex-col max-h-[90vh]"
                 wire:poll.3s> 

                {{-- 3. ប៊ូតុងបិទ (X) នៅជ្រុងលើ --}}
                <button wire:click="close" class="absolute top-4 right-4 z-10 text-white hover:text-red-400 transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                {{-- 4. ខ្លឹមសារខាងក្នុង (Scrollable Content) --}}
                <div class="flex flex-col md:flex-row h-full overflow-hidden">
                    
                    {{-- === ផ្នែកខាងឆ្វេង: QR Code === --}}
                    <div class="w-full md:w-5/12 bg-gray-50 p-8 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200 text-center relative">
                        <h2 class="text-3xl font-black text-gray-800 mb-2">{{ __('ស្កែនវត្តមាន') }}</h2>
                        <p class="text-gray-500 mb-6 font-medium">{{ __('ID មុខវិជ្ជា:') }} <span class="text-blue-600 font-bold">#{{ $courseId }}</span></p>

                        {{-- QR Code Box --}}
                        <div class="p-4 border-4 border-gray-900 rounded-3xl bg-white shadow-xl mb-6 transform hover:scale-105 transition-transform duration-300">
                            {!! $qrCodeImage !!}
                        </div>

                        {{-- Timer Warning --}}
                        <div class="flex items-center text-red-500 animate-pulse gap-2 bg-red-50 px-4 py-2 rounded-full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-xs font-bold">{{ __('QR ផ្លាស់ប្តូររៀងរាល់ 15 វិនាទី') }}</span>
                        </div>
                    </div>

                    {{-- === ផ្នែកខាងស្តាំ: បញ្ជីសិស្ស (Live List) === --}}
                    <div class="w-full md:w-7/12 p-8 flex flex-col bg-white">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <span>{{ __('សិស្សដែលបានស្កែន') }}</span>
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                            </h3>
                            <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-bold shadow-sm">
                                {{ isset($attendances) ? count($attendances) : 0 }} {{ __('នាក់') }}
                            </span>
                        </div>

                        {{-- List Container (Scrollable) --}}
                        <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                            @if(isset($attendances) && count($attendances) > 0)
                                @foreach($attendances as $record)
                                    <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-2xl border border-gray-100 hover:shadow-md transition-all animate-fade-in-up">
                                        {{-- Profile Pic --}}
                                        <img src="{{ $record->student->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.$record->student->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                        
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800">{{ $record->student->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $record->created_at->format('h:i:s A') }}</p>
                                        </div>
                                        
                                        <div class="text-green-500 bg-green-50 p-2 rounded-full">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center h-48 text-gray-400 border-2 border-dashed border-gray-200 rounded-2xl">
                                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <p class="text-sm font-medium">{{ __('កំពុងរង់ចាំសិស្សស្កែន...') }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- === Footer Buttons === --}}
                        <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end gap-3">
                            {{-- ប៊ូតុងបិទ (មិនទាន់ចប់) --}}
                            <button wire:click="close" 
                                    class="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-all text-sm">
                                {{ __('បិទ (មិនទាន់ចប់)') }}
                            </button>

                            {{-- ប៊ូតុងបញ្ចប់ (Finish & Save) --}}
                            <button wire:click="closeAttendance"
                                    wire:confirm="តើអ្នកច្បាស់ទេថាចង់បញ្ចប់? សិស្សដែលមិនទាន់ស្កែននឹងត្រូវដាក់ថា 'អវត្តមាន' ទាំងអស់។"
                                    class="px-6 py-2.5 rounded-xl font-bold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-lg shadow-red-200 transition-all flex items-center gap-2 transform active:scale-95">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('បញ្ចប់ និងរក្សាទុក') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>