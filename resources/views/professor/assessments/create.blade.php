<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-2">
            <div class="flex items-center gap-3">
                <a href="{{ route('professor.manage-grades', ['offering_id' => $courseOffering->id]) }}" 
                   class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:bg-indigo-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-lg sm:text-xl text-gray-800 leading-tight tracking-tight">
                        {{ __('បង្កើតការវាយតម្លៃថ្មី') }}
                    </h2>
                    <p class="text-[11px] sm:text-sm text-gray-500 mt-0.5">
                        {{ __('មុខវិជ្ជា:') }} <span class="font-bold text-indigo-600">{{ $courseOffering->course->title_km }}</span>
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Messages Section --}}
    <div class="max-w-4xl mx-auto px-4 mt-6">
        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm flex items-center gap-3" role="alert">
                <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-xl shadow-sm flex items-center gap-3" role="alert">
                <svg class="h-5 w-5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif
    </div>

    <div class="py-6 sm:py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl shadow-gray-200/50 rounded-2xl sm:rounded-3xl border border-gray-100">
                <div class="p-6 sm:p-10">
                    <form action="{{ route('professor.assessments.store', ['offering_id' => $courseOffering->id]) }}" method="POST">
                        @csrf

                        <div class="space-y-5 sm:space-y-6">
                            {{-- ប្រភេទការវាយតម្លៃ --}}
                            <div class="group">
                                <label for="assessment_type" class="flex items-center gap-2 text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    ប្រភេទការវាយតម្លៃ <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="assessment_type" name="assessment_type" required 
                                            class="block w-full pl-5 pr-10 py-3.5 bg-gray-50 border-gray-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 rounded-xl transition-all appearance-none font-bold text-gray-700">
                                        <option value="assignment" {{ old('assessment_type') == 'assignment' ? 'selected' : '' }}>📝 កិច្ចការ (Assignment)</option>
                                        <option value="exam" {{ old('assessment_type') == 'exam' ? 'selected' : '' }}>🎓 ការប្រឡង (Exam)</option>
                                        <option value="quiz" {{ old('assessment_type') == 'quiz' ? 'selected' : '' }}>⚡ កម្រងសំណួរ (Quiz)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('assessment_type')<p class="text-rose-500 text-xs mt-2 font-medium ml-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- ចំណងជើង --}}
                            <div class="grid grid-cols-1 gap-5">
                                <div class="group">
                                    <label for="title_km" class="flex items-center gap-2 text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        ចំណងជើង (ខ្មែរ) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="title_km" id="title_km" placeholder="ឧទាហរណ៍៖ កិច្ចការស្រាវជ្រាវ..." value="{{ old('title_km') }}" required 
                                           class="w-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 rounded-xl py-3.5 px-5 transition-all font-bold text-gray-700">
                                    @error('title_km')<p class="text-rose-500 text-xs mt-2 font-medium ml-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="group">
                                    <label for="title_en" class="flex items-center gap-2 text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                                        ចំណងជើង (អង់គ្លេស)
                                    </label>
                                    <input type="text" name="title_en" id="title_en" placeholder="Example: Research Assignment..." value="{{ old('title_en') }}" 
                                           class="w-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 rounded-xl py-3.5 px-5 transition-all font-bold text-gray-700">
                                </div>
                            </div>

                            {{-- ពិន្ទុ និង កាលបរិច្ឆេទ --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="group">
                                    <label for="max_score" class="flex items-center gap-2 text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                        ពិន្ទុអតិបរមា <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" name="max_score" id="max_score" value="{{ old('max_score', 100) }}" required min="1" 
                                           class="w-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 rounded-xl py-3.5 px-5 transition-all font-bold text-gray-700">
                                    @error('max_score')<p class="text-rose-500 text-xs mt-2 font-medium ml-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="group">
                                    <label for="assessment_date" class="flex items-center gap-2 text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        កាលបរិច្ឆេទ <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="assessment_date" id="assessment_date" value="{{ old('assessment_date', date('Y-m-d')) }}" required 
                                           class="w-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 rounded-xl py-3.5 px-5 transition-all font-bold text-gray-700">
                                    @error('assessment_date')<p class="text-rose-500 text-xs mt-2 font-medium ml-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- ប្រភេទពិន្ទុ --}}
                            <div class="group">
                                <label for="grading_category_id" class="flex items-center gap-2 text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    ប្រភេទពិន្ទុ <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="grading_category_id" name="grading_category_id" required 
                                            class="block w-full pl-5 pr-10 py-3.5 bg-gray-50 border-gray-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 rounded-xl appearance-none transition-all font-bold text-gray-700">
                                        @forelse($gradingCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('grading_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name_km }} ({{ $category->weight_percentage }}%)
                                            </option>
                                        @empty
                                            <option value="" disabled>មិនមានប្រភេទពិន្ទុ</option>
                                        @endforelse
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                            <a href="{{ route('professor.manage-grades', ['offering_id' => $courseOffering->id]) }}" 
                               class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 border border-gray-200 text-sm font-bold rounded-xl text-gray-500 bg-white hover:bg-gray-50 transition-all active:scale-95">
                                បោះបង់
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-10 py-3.5 border border-transparent text-sm font-bold rounded-xl shadow-xl shadow-indigo-100 text-white bg-indigo-600 hover:bg-indigo-700 transition-all transform active:scale-95">
                                រក្សាទុកការវាយតម្លៃ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>