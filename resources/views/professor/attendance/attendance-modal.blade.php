<div>
    @if($isOpen)
        {{-- ផ្ទៃខាងក្រោយពណ៌ខ្មៅ --}}
        <div class="fixed inset-0 z-50 flex  justify-center bg-black/70 backdrop-blur-sm">
            
            {{-- ដាក់ wire:poll នៅទីនេះ ដើម្បីឱ្យវាដំណើរការតែពេល Modal បង្ហាញខ្លួន --}}
            <div class="bg-white p-8 rounded-3xl shadow-2xl max-w-md w-full relative transform transition-all scale-100"
                 wire:poll.15s="generateToken"> 
                
                {{-- ប៊ូតុងបិទ (Close Button) --}}
                <button wire:click="close" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ __('ស្កែនវត្តមាន') }}</h2>
                    <p class="text-gray-500 mb-6 font-medium">{{ __('សម្រាប់មុខវិជ្ជា ID:') }} <span class="text-blue-600">#{{ $courseId }}</span></p>

                    {{-- ប្រអប់ QR Code --}}
                    <div class="p-4 border-4 border-gray-900 rounded-2xl inline-block bg-white shadow-inner">
                        {!! $qrCodeImage !!}
                    </div>

                    {{-- សារព្រមាន --}}
                    <div class="mt-6 flex justify-center items-center text-red-500 animate-pulse gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-bold">{{ __('QR ផ្លាស់ប្តូររៀងរាល់ 15 វិនាទី') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>