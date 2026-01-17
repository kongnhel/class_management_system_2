<x-app-layout>
    <div class="py-10 bg-[#f8fafc] min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div x-data="{ viewMode: 'grid' }" class="space-y-8">
                
                {{-- Header Section (Original) --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                            <span class="p-3 bg-green-100 rounded-2xl text-2xl">🏫</span>
                            {{ __('គ្រប់គ្រងបន្ទប់') }}
                        </h2>
                        <p class="text-gray-500 mt-1 ml-1">{{ __('គ្រប់គ្រង និងតាមដានបញ្ជីបន្ទប់ក្នុងប្រព័ន្ធរបស់អ្នក') }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- View Switcher --}}
                        <div class="flex bg-gray-100 p-1.5 rounded-2xl">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow-sm text-green-600' : 'text-gray-500 hover:text-green-600'" 
                                    class="p-2.5 rounded-xl transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow-sm text-green-600' : 'text-gray-500 hover:text-green-600'" 
                                    class="p-2.5 rounded-xl transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            </button>
                        </div>

                        <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl shadow-lg shadow-green-200 transition-all duration-300 transform hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            <span>{{ __('បង្កើតបន្ទប់ថ្មី') }}</span>
                        </a>
                    </div>
                </div>

                {{-- Main Content Section --}}
                <div id="rooms-container">
                    
                    {{-- 1. Grid View --}}
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($rooms as $room)
                            <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="p-4 bg-green-50 rounded-2xl">
                                        {{-- ✅ បង្ហាញរូបភាពពី ImgBB URL --}}
                                        @if($room->wifi_qr_code)
                                            <img src="{{ $room->wifi_qr_code }}" alt="QR" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                        @else
                                            <span class="text-3xl">🚪</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        {{-- ប៊ូតុង Edit --}}
                                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        {{-- ប៊ូតុង Delete --}}
                                        <button onclick="openDeleteModal({{ $room->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <h4 class="text-xl font-black text-gray-900 mb-1">បន្ទប់លេខ {{ $room->room_number }}</h4>
                                <p class="text-gray-500 text-sm mb-4">{{ $room->location_of_room ?? 'មិនទាន់មានទីតាំង' }}</p>
                                
                                <div class="flex items-center gap-4 py-4 border-t border-gray-50">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('សមត្ថភាព') }}</span>
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg">{{ $room->capacity }} នាក់</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- 2. Table View --}}
                    <div x-show="viewMode === 'table'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('បន្ទប់') }}</th>
                                    <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('ទីតាំង') }}</th>
                                    <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">{{ __('សមត្ថភាព') }}</th>
                                    <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">{{ __('សកម្មភាព') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($rooms as $room)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            @if($room->wifi_qr_code)
                                                <img src="{{ $room->wifi_qr_code }}" class="w-10 h-10 rounded-lg object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">🚪</div>
                                            @endif
                                            <span class="font-bold text-gray-900">{{ $room->room_number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-gray-600">{{ $room->location_of_room }}</td>
                                    <td class="px-6 py-5 text-center text-gray-600">{{ $room->capacity }} នាក់</td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-sm">{{ __('កែប្រែ') }}</a>
                                            <button onclick="openDeleteModal({{ $room->id }})" class="text-red-600 hover:text-red-800 font-bold text-sm">{{ __('លុប') }}</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $rooms->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Modern Delete Modal (Original) --}}
<div id="delete-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl transform transition-all sm:max-w-lg w-full overflow-hidden p-8 border border-gray-100">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-6 text-red-500">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">{{ __('បញ្ជាក់ការលុប') }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ __('តើអ្នកប្រាកដថាចង់លុបទិន្នន័យបន្ទប់នេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយបានឡើយ។') }}</p>
            </div>
            <div class="mt-10 flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl transition-all order-2 sm:order-1">
                    {{ __('បោះបង់') }}
                </button>
                <form id="delete-form" method="POST" class="flex-1 order-1 sm:order-2"> 
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-6 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-lg shadow-red-200 transition-all transform hover:scale-[1.02]">
                        {{ __('លុបចេញ') }}
                    </button>
                </form>
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