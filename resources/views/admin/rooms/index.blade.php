<x-app-layout>
    <div class="py-10 bg-[#f8fafc] min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div x-data="{ viewMode: 'grid' }" class="space-y-8">
                
                {{-- Header Section --}}
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

                {{-- Main Content --}}
                <div id="rooms-container">
                    @livewire('room-table') {{-- ប្រើ Livewire ដូច Faculty ដើម្បីឱ្យ Reactive --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Modern Delete Modal --}}
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