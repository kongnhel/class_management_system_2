<x-app-layout> <div class="py-12 bg-gray-50 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-end items-center space-x-4 no-print print:hidden">
            
            {{-- Print Button --}}
            <button onclick="window.print()"
                class="group px-4 py-2 md:px-6 md:py-3 
                        bg-gradient-to-r from-green-500 to-green-600 
                        text-white font-semibold text-base md:text-lg 
                        rounded-lg shadow-xl 
                        hover:from-green-600 hover:to-green-700 
                        transform hover:scale-105 transition-all duration-300 ease-in-out
                        flex items-center space-x-2 focus:outline-none focus:ring-4 focus:ring-green-300">
                
                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" class="h-5 w-5 md:h-6 md:w-6 
                            group-hover:rotate-12 transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                </svg>
                
                <span>{{ __('បោះពុម្ព') }}</span>
            </button>
            
            {{-- Back Button --}}
            <a href="{{ route('professor.my-course-offerings', ['offering_id' => $courseOffering->id]) }}"
                class="group inline-flex items-center 
                        px-3 py-2 md:px-6 md:py-3 
                        border border-green-500 
                        bg-white text-green-700 text-sm font-semibold rounded-lg shadow-md
                        hover:bg-green-50 hover:text-green-800
                        transform hover:scale-105 transition-all duration-300 ease-in-out 
                        focus:outline-none focus:ring-4 focus:ring-green-300">

                <svg class="w-4 h-4 md:w-5 md:h-5 md:mr-2 
                            group-hover:-translate-x-1 transition duration-300" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                
                <span class="hidden md:inline">
                    {{ __('ត្រឡប់ទៅបញ្ជីមុខវិជ្ជា') }}
                </span>
                
            </a>
        </div>
        
    </div>
             {{-- Success/Error Messages (Existing) --}}
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('ជោគជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mx-6 mt-6 mb-0" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-semibold">{{ __('បរាជ័យ!') }}</p>
                            <p class="ml-auto">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">
            <h3 class="text-4xl font-extrabold text-green-700 mb-8 text-center">
                {{ __('បញ្ជីនិស្សិតដែលបានចុះឈ្មោះ') }}
            </h3>

            @if ($paginatedStudents->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-gray-600 bg-white rounded-2xl shadow-inner border-2 border-dashed border-gray-300">
                    <svg class="w-24 h-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <p class="text-3xl font-bold mb-3 text-gray-800">{{ __('មិនទាន់មាននិស្សិតចុះឈ្មោះនៅឡើយទេ។') }}</p>
                    <p class="text-lg text-gray-500 text-center max-w-xl">
                        {{ __('នៅពេលនិស្សិតចុះឈ្មោះ ពួកគេនឹងបង្ហាញនៅទីនេះ។') }}
                    </p>
                </div>
            @else
                
                {{-- NEW RESPONSIVE LIST/TABLE VIEW (FOR WEB) --}}
                <div class="overflow-x-auto print:hidden">
                    <table class="min-w-full divide-y divide-gray-200 shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                        
                        {{-- Table Header --}}
                        <thead class="bg-green-700/90">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">{{ __('ឈ្មោះនិស្សិត') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider hidden sm:table-cell">{{ __('លេខសម្គាល់និស្សិត') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider hidden md:table-cell">{{ __('អ៊ីមែល') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider hidden lg:table-cell">{{ __('លេខទូរស័ព្ទ') }}</th>
                                <th class="px-6 py-4 text-center text-sm font-bold text-white uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                            </tr>
                        </thead>
                        
                        {{-- Table Body --}}
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($paginatedStudents as $student)
                                @php
                                    // Profile picture logic for the list view
                                    $profilePictureUrl = $student->studentProfile && $student->studentProfile->profile_picture_url
                                                        ? asset('storage/' . $student->studentProfile->profile_picture_url)
                                                        : null;
                                @endphp
                                <tr class="hover:bg-green-50 transition duration-150 ease-in-out">
                                    
                                    {{-- Student Name and Profile --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            {{-- Profile Image/Fallback Initial --}}
                                            <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center text-lg font-bold bg-gradient-to-br from-green-500 to-green-700 text-white mr-4 flex-shrink-0 shadow-sm">
                                                @if($profilePictureUrl)
                                                    <img src="{{ $profilePictureUrl }}" alt="{{ $student->name }} Profile" class="w-full h-full object-cover">
                                                @else
                                                    {{ Str::substr($student->studentProfile->full_name_km ?? $student->name, 0, 1) }}
                                                @endif
                                            </div>
                                            {{-- Name and Email (for mobile) --}}
                                            <div>
                                                <div class="text-base font-bold text-gray-900">{{ $student->studentProfile->full_name_km ?? $student->name }}</div>
                                                <div class="text-xs text-gray-500 md:hidden">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Student ID --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden sm:table-cell">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $student->student_id_code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    
                                    {{-- Email --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden md:table-cell">
                                        {{ $student->email }}
                                    </td>
                                    
                                    {{-- Phone Number --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden lg:table-cell">
                                        {{ $student->studentProfile->phone_number ?? 'N/A' }}
                                    </td>
                                    
                                    {{-- Action Button --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('professor.students.show', ['courseOffering' => $courseOffering->id, 'student' => $student->id]) }}" 
                                            class="inline-flex items-center justify-center px-4 py-2 
                                                    bg-green-600 border border-transparent rounded-lg 
                                                    font-medium text-white text-sm tracking-wider 
                                                    hover:bg-green-700 transition ease-in-out duration-150 shadow-md transform hover:scale-105">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            {{ __('មើល') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                {{-- Print-Friendly Table (Kept as is for reporting) --}}
                <div class="hidden print:block mt-10 print:mt-0">
                    {{-- Title for Print --}}
                    <h2 class="text-2xl font-bold text-center mb-6">
                        {{ __('បញ្ជីនិស្សិតដែលបានចុះឈ្មោះ') }}
                    </h2>
                    <table class="w-full border-collapse border border-gray-400 text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('ឈ្មោះពេញ') }}</th>
                                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('អ៊ីមែល') }}</th>
                                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('លេខសម្គាល់និស្សិត') }}</th>
                                <th class="border border-gray-400 px-3 py-2 text-left">{{ __('លេខទូរស័ព្ទ') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paginatedStudents as $student)
                                <tr>
                                    <td class="border border-gray-400 px-3 py-2">{{ $student->studentProfile->full_name_km ?? $student->name }}</td>
                                    <td class="border border-gray-400 px-3 py-2">{{ $student->email }}</td>
                                    <td class="border border-gray-400 px-3 py-2">{{ $student->student_id_code ?? 'N/A' }}</td>
                                    <td class="border border-gray-400 px-3 py-2">{{ $student->studentProfile->phone_number ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-12 flex justify-center print:hidden">
                    {{ $paginatedStudents->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>


</x-app-layout>