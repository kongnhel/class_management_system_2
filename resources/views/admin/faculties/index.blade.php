<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- កែសម្រួល៖ ប្រើ $persist ដើម្បីរក្សា Mode និង x-init សម្រាប់ដោះស្រាយបញ្ហា Tab --}}
            <div x-data="{ 
                    viewMode: $persist('grid').as('faculty_view_mode'),
                    showDeleteModal: false,
                    deletingFacultyId: null,
                    deletingFacultyName: ''
                }" 
                class="bg-white overflow-hidden shadow-2xl sm:rounded-[3rem] p-8 lg:p-12 border border-gray-200 transition-all duration-300">

                <div class="flex flex-col md:flex-row items-center justify-between mb-10 pb-6 border-b border-gray-100">
                    <div>
                        <h2 class="font-black text-4xl text-gray-900 tracking-tight leading-none flex items-center gap-4">
                            <span class="p-3 bg-green-100 text-green-600 rounded-2xl shadow-sm">
                                <i class="fas fa-university"></i>
                            </span>
                            {{ __('គ្រប់គ្រងមហាវិទ្យាល័យ') }}
                        </h2>
                        <p class="mt-3 text-lg text-gray-400 font-medium tracking-wide italic">{{ __('បញ្ជីឈ្មោះមហាវិទ្យាល័យទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
                    </div>
                    
                    <div class="mt-6 md:mt-0 flex items-center gap-5">
                        {{-- VIEW TOGGLE BUTTONS --}}
                        <div class="inline-flex rounded-2xl shadow-inner bg-gray-50 p-1.5 border border-gray-100">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow-md text-green-600' : 'text-gray-400 hover:text-green-500'" 
                                    class="p-2.5 rounded-xl transition-all duration-300" 
                                    title="{{ __('ទម្រង់ប័ណ្ណ') }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow-md text-green-600' : 'text-gray-400 hover:text-green-500'" 
                                    class="p-2.5 rounded-xl transition-all duration-300" 
                                    title="{{ __('ទម្រង់តារាង') }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                        
                        {{-- ADD NEW BUTTON --}}
                        <a href="{{ route('admin.create-faculty') }}" class="px-8 py-3.5 bg-gradient-to-br from-green-500 to-emerald-600 text-white font-black rounded-2xl shadow-xl shadow-green-100 hover:shadow-green-200 transition-all active:scale-95 flex items-center gap-3">
                            <i class="fas fa-plus-circle text-lg text-green-100"></i>
                            <span class="uppercase tracking-widest text-xs">{{ __('បន្ថែមថ្មី') }}</span>
                        </a>
                    </div>
                </div>

                {{-- Messages --}}
                @if (session('success'))
                    <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                        <i class="fas fa-check-circle text-2xl"></i>
                        <p class="font-black uppercase text-xs tracking-widest">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- កន្លែងបង្ហាញ Livewire ដោយផ្អែកលើ viewMode --}}
                <div class="mt-4" x-transition:enter="transition ease-out duration-300">
                    @livewire('faculty-table', ['viewMode' => 'grid']) {{-- បញ្ជូន Mode ទៅកាន់ Component --}}
                </div>

                {{-- DELETE MODAL (កូដថ្មីដែលដំណើរការ) --}}
                <div x-show="showDeleteModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                    <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showDeleteModal" @click="showDeleteModal = false" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                        <div x-show="showDeleteModal" x-transition class="inline-block align-middle bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                            <div class="bg-white px-8 pt-10 pb-6">
                                <div class="sm:flex sm:items-start flex-col items-center text-center">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 mb-6 border border-rose-100">
                                        <i class="fas fa-exclamation-triangle text-3xl text-rose-600"></i>
                                    </div>
                                    <h3 class="text-2xl leading-6 font-black text-gray-900 uppercase tracking-tight mb-4">
                                        {{ __('បញ្ជាក់ការលុប') }}
                                    </h3>
                                    <p class="text-sm text-gray-400 font-medium leading-relaxed">
                                        {{ __('តើអ្នកពិតជាចង់លុបមហាវិទ្យាល័យ') }} <span class="font-black text-rose-600" x-text="deletingFacultyName"></span> {{ __('នេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយបានឡើយ។') }}
                                    </p>
                                </div>
                            </div>
                            <div class="bg-gray-50/50 px-8 py-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto inline-flex justify-center rounded-2xl border-2 border-gray-200 px-6 py-3 bg-white text-sm font-black text-gray-500 hover:bg-gray-100 transition-all uppercase tracking-widest">{{ __('បោះបង់') }}</button>
                                <form :action="'/admin/faculties/' + deletingFacultyId" method="POST" class="w-full sm:w-auto">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent px-6 py-3 bg-rose-600 text-sm font-black text-white hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all uppercase tracking-widest">{{ __('យល់ព្រមលុប') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

{{-- វិធីសាស្ត្រថ្មីសម្រាប់ហៅ Delete Modal ពី Livewire --}}
<script>
    window.addEventListener('open-delete-modal', event => {
        const data = event.detail[0];
        // បញ្ជូនទិន្នន័យទៅកាន់ AlpineJS Scope
        const alpineScope = document.querySelector('[x-data]').__x.$data;
        alpineScope.deletingFacultyId = data.id;
        alpineScope.deletingFacultyName = data.name;
        alpineScope.showDeleteModal = true;
    });

    // នៅក្នុងផ្នែក <script> នៃ navigation.blade.php

database.ref('faculties_sync').on('value', (snapshot) => {
    const data = snapshot.val();
    
    if (data && data.updated_at > window.sharedPageLoadTime) {
        // ១. បង្ហាញការជូនដំណឹង (Notification)
        window.dispatchEvent(new CustomEvent('firebase-message', {
            detail: { message: data.message }
        }));

        // ២. ប្រសិនបើមាន Logo ថ្មី ឱ្យដូររូបភាពនៅលើទំព័រភ្លាមៗ (Realtime Image Update)
        if (data.logo_url) {
            const logoElements = document.querySelectorAll('.faculty-logo-dynamic');
            logoElements.forEach(img => {
                img.src = data.logo_url; // ដូររូបភាពភ្លាមៗ
            });
        }
        
        // ៣. បាញ់ Event ទៅឱ្យ Livewire ដើម្បី Refresh ទិន្នន័យក្នុងតារាង
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('refreshComponent');
        }
    }
});
</script>