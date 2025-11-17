<x-app-layout>
    <div class="py-16 bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-xl mx-auto px-6 lg:px-8 w-full">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden p-8 lg:p-12 border border-gray-200">

                <div class="text-center mb-10">
                    <h3 class="text-4xl font-extrabold text-gray-800 leading-tight">{{ __('ចុះឈ្មោះសិស្សចូលវគ្គសិក្សា') }}</h3>
                    <p class="mt-2 text-lg text-gray-500">{{ __('បំពេញព័ត៌មានខាងក្រោមដើម្បីចុះឈ្មោះសិស្ស') }}</p>
                </div>

                @if (Session::has('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="block font-medium">{{ Session::get('success') }}</span>
                    </div>
                @endif
                @if (Session::has('info'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="block font-medium">{{ Session::get('info') }}</span>
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center space-x-3 shadow-md" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586l-1.293-1.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="block font-medium">{{ Session::get('error') }}</span>
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