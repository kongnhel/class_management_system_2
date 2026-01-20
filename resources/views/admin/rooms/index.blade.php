<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
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
            {{-- Header Section --}}
            <div x-data="{ viewMode: 'grid' }" class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 flex items-center gap-4">
                        <div class="h-14 w-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        {{ __('គ្រប់គ្រងបន្ទប់') }}
                    </h2>
                    <p class="text-gray-500 mt-3 ml-2 text-lg">{{ __('គ្រប់គ្រង និងតាមដានបញ្ជីបន្ទប់ក្នុងប្រព័ន្ធ') }}</p>
                </div>

                <div class="flex items-center gap-4">
                    {{-- View Switcher --}}
                    <div class="bg-white p-1.5 rounded-xl border border-gray-200 shadow-sm flex">
                        <button @click="viewMode = 'grid'" 
                                :class="viewMode === 'grid' ? 'bg-gray-100 text-gray-900 shadow-sm' : 'text-gray-400 hover:text-gray-600'" 
                                class="p-3 rounded-lg transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        </button>
                        <button @click="viewMode = 'table'" 
                                :class="viewMode === 'table' ? 'bg-gray-100 text-gray-900 shadow-sm' : 'text-gray-400 hover:text-gray-600'" 
                                class="p-3 rounded-lg transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                    </div>

                    <a href="{{ route('admin.rooms.create') }}" class="group inline-flex items-center gap-2.5 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 group-hover:text-green-300 transition-colors" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        <span class="text-base">{{ __('បង្កើតថ្មី') }}</span>
                    </a>
                </div>
            </div>

            <div x-data="{ viewMode: 'grid' }"> {{-- 1. BIG Grid View --}}
                <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($rooms as $room)
                        <div class="group bg-white rounded-[2rem] p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-2 relative">
                            
                            {{-- Action Menu (Top Right - Larger Touch Target) --}}
                            <div class="absolute top-6 right-6 flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="p-3 bg-white border border-gray-100 text-blue-600 rounded-xl shadow-md hover:bg-blue-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <button onclick="openDeleteModal({{ $room->id }})" class="p-3 bg-white border border-gray-100 text-red-600 rounded-xl shadow-md hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>

                            <div class="flex flex-col items-center text-center">
                                {{-- Large Image/Icon Container --}}
                                <div class="w-32 h-32 rounded-3xl bg-gray-50 flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-300 shadow-inner border border-gray-100">
                                    @if($room->wifi_qr_code)
                                        <img src="{{ $room->wifi_qr_code }}" alt="QR" class="w-full h-full object-cover rounded-3xl p-2">
                                    @else
                                        <span class="text-6xl">🚪</span>
                                    @endif
                                </div>
                                
                                {{-- Large Typography --}}
                                <h3 class="text-2xl font-black text-gray-900 mb-1 tracking-tight">{{ $room->room_number }}</h3>
                                <p class="text-base text-gray-500 mb-6 font-medium">{{ $room->location_of_room ?? '---' }}</p>
                                
                                {{-- Large Badge --}}
                                <div class="w-full pt-6 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('សមត្ថភាព') }}</span>
                                    <span class="px-4 py-1.5 bg-green-50 text-green-700 text-sm font-bold rounded-lg border border-green-100 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        {{ $room->capacity }} នាក់
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- 2. Table View (Same as before, just kept for context) --}}
                <div x-show="viewMode === 'table'" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-bold">
                                    <th class="px-8 py-5">{{ __('បន្ទប់') }}</th>
                                    <th class="px-8 py-5">{{ __('ទីតាំង') }}</th>
                                    <th class="px-8 py-5 text-center">{{ __('សមត្ថភាព') }}</th>
                                    <th class="px-8 py-5 text-right">{{ __('សកម្មភាព') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($rooms as $room)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-5">
                                            <div class="h-12 w-12 rounded-xl bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                                                @if($room->wifi_qr_code)
                                                    <img src="{{ $room->wifi_qr_code }}" class="h-full w-full object-cover">
                                                @else
                                                    <span class="text-xl">🚪</span>
                                                @endif
                                            </div>
                                            <div class="font-bold text-lg text-gray-900">{{ $room->room_number }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-gray-600 font-medium">{{ $room->location_of_room }}</td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-gray-100 text-gray-800">
                                            {{ $room->capacity }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ __('កែប្រែ') }}</a>
                                            <button onclick="openDeleteModal({{ $room->id }})" class="text-red-600 hover:text-red-800 font-semibold">{{ __('លុប') }}</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $rooms->links() }}
            </div>

        </div>
    </div>
</x-app-layout>

{{-- Delete Modal (Same logic) --}}
<div id="delete-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 p-8">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">{{ __('បញ្ជាក់ការលុប') }}</h3>
                    <p class="text-gray-500 mb-8">{{ __('តើអ្នកប្រាកដថាចង់លុបទិន្នន័យបន្ទប់នេះមែនទេ?') }}</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-xl bg-gray-100 px-5 py-3 text-sm font-bold text-gray-700 hover:bg-gray-200 transition-colors order-2 sm:order-1">
                        {{ __('បោះបង់') }}
                    </button>
                    <form id="delete-form" method="POST" class="flex-1 order-1 sm:order-2"> 
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-red-200 hover:bg-red-700 hover:-translate-y-0.5 transition-all">
                            {{ __('លុបចេញ') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(roomId) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        if(form && modal) {
            form.action = `/admin/rooms/${roomId}`; 
            modal.classList.remove('hidden');
        }
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>