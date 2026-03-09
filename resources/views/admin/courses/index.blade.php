<x-app-layout>
    <div class="py-10 bg-[#f8fafc] min-h-screen font-inter antialiased">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Main Container --}}
            <div x-data="{ viewMode: 'table', search: '' }" 
                 class="bg-white shadow-sm sm:rounded-2xl border border-slate-200 overflow-hidden">
                
                {{-- Header Section --}}
                <div class="p-6 lg:p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                            {{ __('គ្រប់គ្រងមុខវិជ្ជា') }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('គ្រប់គ្រង និងពិនិត្យមើលបញ្ជីឈ្មោះមុខវិជ្ជាទាំងអស់') }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Search Input --}}
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="text" x-model="search" placeholder="{{ __('ស្វែងរក...') }}" class="pl-10 pr-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 w-full md:w-64 transition-all">
                        </div>

                        {{-- VIEW TOGGLE --}}
                        <div class="inline-flex bg-slate-100 p-1 rounded-xl">
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow-sm text-green-600' : 'text-slate-500 hover:text-slate-700'" 
                                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                {{ __('បញ្ជី') }}
                            </button>
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow-sm text-green-600' : 'text-slate-500 hover:text-slate-700'" 
                                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                {{ __('ប័ណ្ណ') }}
                            </button>
                        </div>

                        <a href="{{ route('admin.create-course') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-green-100 gap-2 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            {{ __('បន្ថែមមុខវិជ្ជា') }}
                        </a>
                    </div>
                </div>

                {{-- Toast Notification --}}
                @if (session('success') || session('error'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 5000)" 
                     x-show="show" 
                     x-transition:enter="transform ease-out duration-300 transition"
                     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed top-6 right-6 z-[9999] w-full max-w-sm">
                    <div class="bg-white border-l-4 {{ session('success') ? 'border-emerald-500' : 'border-red-500' }} shadow-2xl rounded-2xl overflow-hidden p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                @if(session('success'))
                                    <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-black text-slate-900">{{ session('success') ? __('ប្រតិបត្តិការជោគជ័យ') : __('មានបញ្ហាអ្វីមួយ') }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ session('success') ?? session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

<div class="p-6 lg:p-8">
    <div class="space-y-12">
        @forelse ($coursesGrouped as $programName => $generations)
            <div class="program-section">
                {{-- Program Title --}}
                <div class="flex items-center gap-4 mb-8">
                    <div class="flex flex-col">
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ $programName }}</h3>
                        <div class="h-1 w-12 bg-green-600 rounded-full mt-1"></div>
                    </div>
                </div>

                @foreach ($generations as $generationName => $courseList)
                    <div class="mb-12 last:mb-0">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="text-[11px] font-black text-green-700 bg-green-50 px-4 py-1.5 rounded-full uppercase tracking-widest border border-green-100 shadow-sm">
                                {{ $generationName }}
                            </span>
                            <div class="flex-1 h-px bg-gradient-to-r from-slate-200 to-transparent"></div>
                        </div>

                        {{-- GRID VIEW --}}
                        <div x-show="viewMode === 'grid'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($courseList as $course)
                                <div class="group bg-white border border-slate-200 rounded-2xl p-6 hover:border-green-400 hover:shadow-xl transition-all relative overflow-hidden">
                                    <div class="flex justify-between items-start mb-5">
                                        <div class="h-12 w-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-green-600 group-hover:text-white transition-all shadow-sm">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" /></svg>
                                        </div>
                                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                            <a href="{{ route('admin.edit-course', $course->id) }}" class="p-2 text-slate-400 hover:text-green-600 rounded-xl">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                                            </a>
                                            <button @click="openDeleteModal('{{ route('admin.delete-course', $course->id) }}')" class="p-2 text-slate-400 hover:text-red-500 rounded-xl">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <h4 class="text-lg font-extrabold text-slate-900 mb-1">{{ $course->title_km }}</h4>
                                    
                                    {{-- Show All assigned programs as tags inside the card --}}
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach($course->programs as $p)
                                            <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-bold uppercase">{{ $p->name_km }}</span>
                                        @endforeach
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest">{{ __('ក្រេឌីត') }}</span>
                                            <span class="text-sm font-black text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $course->credits }}</span>
                                        </div>
                                        <span class="text-[10px] bg-green-50 text-green-700 px-2.5 py-1 rounded-lg font-bold border border-green-100">
                                            {{ $course->department->name_km ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- TABLE VIEW --}}
                        <div x-show="viewMode === 'table'" x-transition class="overflow-x-auto border border-slate-200 rounded-2xl shadow-sm">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50/80">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase">{{ __('ព័ត៌មានមុខវិជ្ជា') }}</th>
                                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-500 uppercase">{{ __('កម្មវិធីសិក្សាទាំងអស់') }}</th>
                                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-500 uppercase">{{ __('ក្រេឌីត') }}</th>
                                        <th class="px-6 py-4 text-right text-[11px] font-black text-slate-500 uppercase">{{ __('សកម្មភាព') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @foreach ($courseList as $course)
                                        <tr class="hover:bg-green-50/30 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-slate-800">{{ $course->title_km }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $course->title_en }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($course->programs as $p)
                                                        <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-bold">{{ $p->name_km }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-white border border-slate-200 text-slate-700">
                                                    {{ $course->credits }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                                <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="{{ route('admin.edit-course', $course->id) }}" class="p-2 text-slate-400 hover:text-green-600 hover:bg-white rounded-xl shadow-sm transition-all">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                                                    </a>
                                                    <button @click="openDeleteModal('{{ route('admin.delete-course', $course->id) }}')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-white rounded-xl shadow-sm transition-all">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            {{-- Empty State --}}
            <div class="py-24 text-center">
                <h3 class="text-lg font-bold text-slate-900">{{ __('មិនទាន់មានទិន្នន័យ') }}</h3>
            </div>
        @endforelse
    </div>
</div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="delete-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-md" onclick="closeDeleteModal()"></div>

            <div class="relative inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-slate-200">
                <div class="bg-white px-8 pt-10 pb-8 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 text-red-500 mb-6">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-3">{{ __('តើអ្នកប្រាកដទេ?') }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ __('ទិន្នន័យនេះនឹងត្រូវលុបចេញពីប្រព័ន្ធរៀងរហូត។ អ្នកមិនអាចស្ដារវាឡើងវិញបានឡើយ។') }}</p>
                </div>
                <div class="bg-slate-50 px-8 py-6 flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-white border border-slate-200 text-sm font-black text-slate-600 rounded-2xl hover:bg-slate-100 transition-all">
                        {{ __('បោះបង់') }}
                    </button>
                    <form id="delete-form" method="POST" action="" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-sm font-black text-white rounded-2xl hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95">
                            {{ __('យល់ព្រមលុប') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');
        
        function openDeleteModal(deleteUrl) {
            deleteForm.action = deleteUrl;
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
</x-app-layout>