<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-xl md:text-2xl text-slate-800 leading-tight tracking-tight">
                        {{ __('បញ្ចូលពិន្ទុ') }}
                    </h2>
                    <div class="flex items-center mt-1 text-slate-500 space-x-2">
                        <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $assessment->courseOffering->course->title_km }}</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-2 md:gap-3">
                    {{-- Excel Actions --}}
                    <a href="{{ route('grades.export', ['id' => $assessment->id]) }}"
                       class="flex-1 md:flex-none inline-flex justify-center items-center px-3 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-[11px] font-bold hover:bg-emerald-100 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('ទាញយក') }}
                    </a>

                    <form id="importForm" action="{{ route('grades.import', ['id' => $assessment->id]) }}" method="POST" enctype="multipart/form-data" class="flex-1 md:flex-none">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}"> 
                        <input type="hidden" name="offering_id" value="{{ $assessment->course_offering_id }}">
                        <input type="file" id="csvFileInput" name="excel_file" class="hidden" accept=".csv" onchange="document.getElementById('importForm').submit();">
                        
                        <button type="button" onclick="document.getElementById('csvFileInput').click();"
                                class="w-full inline-flex justify-center items-center px-3 py-2 bg-amber-50 text-amber-700 border border-amber-100 rounded-xl text-[11px] font-bold hover:bg-amber-100 transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            {{ __('បញ្ចូលតាម Excel') }}
                        </button>
                    </form>

                    {{-- Info Badge (Visible on Desktop, simplified on mobile) --}}
                    <div class="w-full lg:w-auto flex items-center gap-4 bg-slate-50 lg:bg-white p-2 md:p-2.5 rounded-2xl border border-slate-100 shadow-sm mt-2 lg:mt-0">
                        <div class="flex-1 lg:text-right lg:pr-4 lg:border-r border-slate-200">
                            <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest leading-none">{{ __('ការវាយតម្លៃ') }}</p>
                            <p class="text-xs md:text-sm font-bold text-slate-700 mt-1">{{ $assessment->title_km }}</p>
                        </div>
                        <div class="text-center px-4">
                            <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest leading-none">{{ __('អតិបរមា') }}</p>
                            <p class="text-xs md:text-sm font-black text-emerald-600 mt-1">{{ $assessment->max_score }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 md:py-8 bg-[#f8fafc] min-h-screen pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Search Area --}}
            <div class="mb-6">
                <form action="{{ url()->current() }}" method="GET" class="relative w-full md:w-96">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="ស្វែងរកឈ្មោះ ឬ អត្តលេខ..."
                        class="block w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm"
                    >
                </form>

                @if(session('success'))
                    <div class="mt-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 text-sm font-bold">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            {{-- Main Form --}}
<form id="grade-form" action="{{ route('professor.grades.store', ['assessment_id' => $assessment->id]) }}" method="POST">
    @csrf
    <input type="hidden" name="assessment_type" value="{{ $type }}">

    <div class="bg-white shadow-sm border border-slate-200 rounded-[2rem] overflow-hidden mb-12">
        <table class="w-full text-left border-collapse">
            {{-- Hide header on mobile --}}
            <thead class="hidden md:table-header-group">
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest w-16">#</th>
                    <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('ព័ត៌មាននិស្សិត') }}</th>
                    <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest w-48 text-center">{{ __('ពិន្ទុទទួលបាន') }}</th>
                    <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('កំណត់ចំណាំ') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($students as $index => $student)
                    {{-- Standard TR on desktop, but a "Flex Card" on mobile --}}
                    <tr class="flex flex-col md:table-row hover:bg-slate-50/50 transition-colors p-5 md:p-0">
                        
                        {{-- Row Index (Hidden on mobile cards) --}}
                        <td class="hidden md:table-cell px-6 py-4 text-xs font-bold text-slate-400">
                            {{ $index + 1 }}
                        </td>

                        {{-- Student Info --}}
                        <td class="px-6 py-2 md:py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-black text-xs">
                                    {{ mb_substr($student->studentProfile?->full_name_km ?? $student->name ?? '?', 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-slate-800">{{ $student->studentProfile?->full_name_km ?? $student->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">{{ $student->student_id_code }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Score Input --}}
                        <td class="px-6 py-2 md:py-4">
                            <label class="md:hidden text-[10px] font-black uppercase text-slate-400 mb-1 block">{{ __('ពិន្ទុទទួលបាន') }}</label>
                            <input type="number" 
                                name="grades[{{ $student->id }}][score]" 
                                value="{{ old('grades.'.$student->id.'.score', $scores[$student->id]['score'] ?? '') }}" 
                                min="0" max="{{ $assessment->max_score }}" step="0.01" 
                                class="block w-full text-center py-2 bg-slate-50 border-transparent rounded-xl text-sm font-black text-indigo-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/20" 
                                placeholder="0.00">
                        </td>

                        {{-- Notes Input --}}
                        <td class="px-6 py-2 md:py-4">
                            <label class="md:hidden text-[10px] font-black uppercase text-slate-400 mb-1 block">{{ __('មតិយោបល់') }}</label>
                            <input type="text" 
                                name="grades[{{ $student->id }}][notes]" 
                                value="{{ old('grades.'.$student->id.'.notes', $scores[$student->id]['notes'] ?? '') }}" 
                                class="block w-full px-4 py-2 bg-slate-50 border-transparent rounded-xl text-xs font-medium text-slate-600 focus:bg-white focus:ring-2 focus:ring-indigo-500/10" 
                                placeholder="...">
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-20 text-center text-slate-400 font-bold">{{ __('រកមិនឃើញនិស្សិត') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>
        </div>
    </div>

    {{-- Sticky Actions Bar --}}
    <div class="fixed bottom-0 inset-x-0 bg-white/90 backdrop-blur-xl border-t border-slate-200 py-4 z-40 shadow-[0_-10px_25px_rgba(0,0,0,0.05)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
            <a href="{{ route('professor.manage-grades', ['offering_id' => $assessment->course_offering_id]) }}"
               class="p-3 md:px-6 md:py-3 text-slate-500 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span class="hidden md:inline-flex items-center text-xs font-bold uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    {{ __('ត្រឡប់ក្រោយ') }}
                </span>
            </a>
            
            <div class="flex-1 md:flex-none flex items-center justify-end gap-4">
                <span class="hidden md:inline text-[11px] font-black text-slate-400 uppercase tracking-widest">
                    {{ count($students) }} {{ __('និស្សិតសរុប') }}
                </span>
                <button type="submit" form="grade-form" class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm shadow-xl shadow-indigo-200 transition-all active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    {{ __('រក្សាទុកទាំងអស់') }}
                </button>
            </div>
        </div>
    </div>

    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
</x-app-layout>