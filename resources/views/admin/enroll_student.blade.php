<x-app-layout>
    <div class="py-16 bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-xl mx-auto px-6 lg:px-8 w-full">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden p-8 lg:p-12 border border-gray-200">

                <div class="text-center mb-10">
                    <h3 class="text-4xl font-extrabold text-gray-800 leading-tight">{{ __('ចុះឈ្មោះសិស្សចូលវគ្គសិក្សា') }}</h3>
                    <p class="mt-2 text-lg text-gray-500">{{ __('បំពេញព័ត៌មានខាងក្រោមដើម្បីចុះឈ្មោះសិស្ស') }}</p>
                </div>

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
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl relative mb-8 shadow-md" role="alert">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.perform_enrollment') }}" method="POST" class="space-y-8">
                    @csrf
                    <div>
                        <label for="student_user_id" class="block text-base font-semibold text-gray-700 mb-2">
                            {{ __('ជ្រើសរើសសិស្ស') }}
                        </label>
                        <select id="student_user_id" name="student_user_id" required
                                class="mt-1 block w-full p-4 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-800 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200">
                            <option value="" class="text-gray-400">-- {{ __('ជ្រើសរើសសិស្ស') }} --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" class="text-gray-800" {{ old('student_user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="course_offering_id" class="block text-base font-semibold text-gray-700 mb-2">
                            {{ __('ជ្រើសរើសវគ្គសិក្សា') }}
                        </label>
                        <select id="course_offering_id" name="course_offering_id" required
                                class="mt-1 block w-full p-4 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-800 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200">
                            <option value="" class="text-gray-400">-- {{ __('ជ្រើសរើសវគ្គសិក្សា') }} --</option>
                            @foreach($courseOfferings as $offering)
                                <option value="{{ $offering->id }}" class="text-gray-800" {{ old('course_offering_id') == $offering->id ? 'selected' : '' }}>
                                    {{ $offering->course->title_km ?? $offering->course->title_en }} ({{ $offering->academic_year }} - {{ $offering->semester }}) - {{ $offering->lecturer->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 transform hover:-translate-y-0.5">
                            {{ __('ចុះឈ្មោះ') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>