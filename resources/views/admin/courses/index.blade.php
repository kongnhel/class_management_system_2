<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen font-inter antialiased">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ viewMode: 'grid' }"
             class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                <div class="flex flex-col md:flex-row items-center justify-between mb-10 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('គ្រប់គ្រងមុខវិជ្ជា') }}
                        </h2>
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះមុខវិជ្ជាទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
                    </div>

                    <div class="mt-4 md:mt-0 flex items-center space-x-4">  
                        {{-- VIEW TOGGLE BUTTONS --}}
                        <div class="inline-flex rounded-full shadow-inner bg-gray-100 p-1">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់ប័ណ្ណ') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់តារាង') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        <a href="{{ route('admin.create-course') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">{{ __('បន្ថែមមុខវិជ្ជាថ្មី') }}</span>
                            <span class="sm:hidden">{{ __('បន្ថែម') }}</span>
                        </a>
                    </div>
                </div>
                
                {{-- Modern Floating Toast (Keep your original code) --}}
                @if (session('success') || session('error'))
                <div x-data="{ show: false, progress: 100, startTimer() { this.show = true; let interval = setInterval(() => { this.progress -= 1; if (this.progress <= 0) { this.show = false; clearInterval(interval); } }, 50); } }" x-init="startTimer()" x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="translate-y-12 opacity-0 sm:translate-y-0 sm:translate-x-12" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed top-6 right-6 z-[9999] w-full max-w-sm">
                    <div class="relative overflow-hidden bg-white/80 backdrop-blur-xl border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.1)] rounded-2xl p-4 ring-1 ring-black/5">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                @if(session('success'))
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10 text-green-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 pt-0.5">
                                <p class="text-sm font-bold text-gray-900 leading-tight">{{ session('success') ? __('ជោគជ័យ!') : __('បរាជ័យ!') }}</p>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">{{ session('success') ?? session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="absolute bottom-0 left-0 h-1 bg-gray-100 w-full">
                            <div class="h-full transition-all duration-75 ease-linear {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}" :style="`width: ${progress}%` text-green-600"></div>
                        </div>
                    </div>
                </div>
                @endif

<div class="mt-8 space-y-16">
    @forelse ($coursesGrouped as $programName => $generations)
        {{-- ១. ក្រុមធំតាមកម្មវិធីសិក្សា (Program) --}}
        <div class="program-group bg-gray-50/50 p-8 rounded-[3rem] border border-gray-100 shadow-sm">
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ $programName }}</h3>
            </div>

            @foreach ($generations as $generationName => $courseList)
                {{-- ២. ក្រុមតូចតាមជំនាន់ (Generation) --}}
                <div class="mb-12 last:mb-0">
                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-[0.3em] mb-6 flex items-center bg-emerald-50 w-fit px-4 py-2 rounded-full border border-emerald-100">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                        {{ $generationName }}
                    </h4>

                    {{-- GRID VIEW --}}
                    <div x-show="viewMode === 'grid'" x-transition:enter.duration.500ms>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($courseList as $course)
                                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 group">
                                    <div class="flex flex-col items-start mb-6">
                                        <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V6.5A2.5 2.5 0 0 0 17.5 4h-11A2.5 2.5 0 0 0 4 6.5v13z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-black text-gray-900 leading-tight group-hover:text-blue-600 transition-colors">{{ $course->title_km }}</h4>
                                            <p class="text-sm text-gray-400 mt-1 font-medium tracking-wide uppercase">{{ $course->title_en }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="bg-gray-50 p-3 rounded-2xl text-center">
                                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">ក្រេឌីត</p>
                                            <p class="text-lg font-black text-gray-800">{{ $course->credits }}</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-2xl text-center">
                                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">ដេប៉ាតឺម៉ង់</p>
                                            <p class="text-[11px] font-black text-gray-800 truncate px-2">{{ $course->department->name_km ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-3 mt-auto pt-6 border-t border-gray-50">
                                        <a href="{{ route('admin.edit-course', $course->id) }}" class="p-4 bg-blue-50 rounded-2xl text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300" title="កែប្រែ">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <button onclick="openDeleteModal('{{ route('admin.delete-course', $course->id) }}')" class="p-4 bg-red-50 rounded-2xl text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300" title="លុប">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- TABLE VIEW (រក្សាតាមទម្រង់ថ្មី) --}}
                    <div x-show="viewMode === 'table'" x-transition:enter.duration.500ms style="display: none;">
                        <div class="overflow-hidden shadow-2xl rounded-[2rem] border border-gray-100 bg-white">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('ឈ្មោះមុខវិជ្ជា') }}</th>
                                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('ក្រេឌីត') }}</th>
                                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('សកម្មភាព') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach ($courseList as $course)
                                        <tr class="hover:bg-blue-50/30 transition-colors duration-300">
                                            <td class="px-8 py-6">
                                                <div class="text-base font-bold text-gray-800">{{ $course->title_km }}</div>
                                                <div class="text-[10px] text-gray-400 uppercase font-medium mt-1">{{ $course->title_en }}</div>
                                            </td>
                                            <td class="px-8 py-6">
                                                <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-black text-gray-600">{{ $course->credits }}</span>
                                            </td>
                                            <td class="px-8 py-6 text-right">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('admin.edit-course', $course->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                                    </a>
                                                    <button type="button" onclick="openDeleteModal('{{ route('admin.delete-course', $course->id) }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="bg-white p-20 rounded-[3rem] text-center shadow-xl border border-gray-100 reveal">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5c-5.072 0-8 3.844-8 7s2.928 7 8 7c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5c5.072 0 8 3.844 8 7s-2.928 7-8 7c-1.492 0-2.832-.462-4-1.253" />
                </svg>
            </div>
            <p class="font-black text-xl text-gray-400 tracking-tight">{{ __('មិនមានមុខវិជ្ជាណាមួយនៅឡើយទេ។') }}</p>
        </div>
    @endforelse
</div>
            </div>
        </div>
    </div>

        {{-- DELETE MODAL (ដូចដើម) --}}
    <div id="delete-modal" class="relative z-50 hidden no-print" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl sm:w-full sm:max-w-lg border border-gray-100">
                    <div class="bg-white p-8">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-6 mx-auto"><i class="fas fa-trash-alt fa-2x"></i></div>
                        <h3 class="text-xl font-black text-center text-slate-900">លុបការផ្តល់ជូនមុខវិជ្ជា?</h3>
                        <p class="text-sm text-gray-500 mt-4 text-center leading-relaxed">តើអ្នកប្រាកដទេថាចង់លុបទិន្នន័យនេះ? ប្រតិបត្តិការនេះមិនអាចត្រឡប់ថយក្រោយវិញបានឡើយ。</p>
                    </div>
                    <div class="bg-slate-50 px-8 py-5 flex justify-center gap-3">
                        <button onclick="closeDeleteModal()" class="bg-white border border-slate-200 px-6 py-2.5 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition-colors">បោះបង់</button>
                        <form id="delete-form" method="POST" action=""> @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-8 py-2.5 rounded-2xl text-sm font-black shadow-lg shadow-red-500/30 hover:bg-red-500 transition-all">យល់ព្រមលុប</button>
                        </form>
                    </div>
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
        }
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }
    </script>
</x-app-layout>