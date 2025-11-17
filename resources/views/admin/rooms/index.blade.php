<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen font-sans">
        {{-- Use max-w-6xl from the Faculty example for consistent main container width --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            {{-- UPDATED: Keep x-data="{ viewMode: 'grid' }" --}}
            <div x-data="{ viewMode: 'grid' }" {{-- Alpine state for toggling view (viewMode: 'grid' is the default for consistency) --}}
                class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-200 transition-all duration-300 transform hover:shadow-3xl">

                {{-- Replicate the header/button row structure from the Faculty example --}}
                <div class="flex flex-col md:flex-row items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div>
                        {{-- Main Title --}}
                        <h2 class="font-extrabold text-4xl text-gray-900 leading-tight">
                            {{ __('គ្រប់គ្រងបន្ទប់') }} 🏫
                        </h2>
                        {{-- Sub-title/Description --}}
                        <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីបន្ទប់ទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p> 
                        {{-- Using 'បញ្ជីបន្ទប់ទាំងអស់នៅក្នុងប្រព័ន្ធ' (List of all rooms in the system) for clarity, similar to Faculty's 'បញ្ជីឈ្មោះមហាវិទ្យាល័យទាំងអស់នៅក្នុងប្រព័ន្ធ' --}}
                    </div>
                    
                    {{-- Action Buttons Container --}}
                    <div class="mt-4 md:mt-0 flex items-center space-x-4">
                        
                        {{-- VIEW TOGGLE BUTTONS (Already using viewMode) --}}
                        <div class="inline-flex rounded-full shadow-inner bg-gray-100 p-1">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់ប័ណ្ណ') }}">
                                {{-- Grid Icon --}}
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'table'" 
                                    :class="viewMode === 'table' ? 'bg-white shadow text-green-600' : 'text-gray-400 hover:text-green-600'" 
                                    class="p-2 rounded-full transition duration-200" 
                                    title="{{ __('ទម្រង់តារាង') }}">
                                {{-- List Icon --}}
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                        
                        {{-- ADD NEW BUTTON (Styled like Faculty, using correct Room route and text) --}}
                        <a href="{{ route('admin.rooms.create') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105 flex items-center space-x-2">
                            {{-- Icon for Add New --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">{{ __('បង្កើតបន្ទប់ថ្មី') }}</span> 
                            {{-- Using the full text for desktop --}}
                            <span class="sm:hidden">{{ __('បន្ថែម') }}</span> 
                            {{-- Using the concise text for mobile (from the Faculty example) --}}
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

                {{-- View Container --}}
                <div class="p-6 sm:p-8">

                    {{-- ------------------------------------------------------------------------------------------------------------------------- --}}
                    {{-- 1. CARD VIEW (UPDATED: Changed x-show="isCardView" to x-show="viewMode === 'grid'") --}}
                    {{-- ------------------------------------------------------------------------------------------------------------------------- --}}
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($rooms as $room)
                            <div class="bg-white/60 backdrop-blur-lg rounded-3xl shadow-lg border border-gray-200 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-2xl relative overflow-hidden">
                                {{-- Background decoration --}}
                                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-white opacity-50 -z-10 rounded-3xl"></div>
                                
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center font-bold text-xl shadow-inner">
                                            {{ substr($room->room_number, 0, 1) }}
                                        </div>
                                        <h4 class="ml-4 text-2xl font-bold text-gray-900">{{ __('លេខបន្ទប់') }} {{ $room->room_number }}</h4>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 text-sm text-gray-700">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ __('សមត្ថភាព') }}:</p>
                                            <p>{{ $room->capacity }}</p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ __('ប្រភេទ') }}:</p>
                                            <p>{{ $room->type_of_room ?? 'N/A' }}</p>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <p class="font-semibold text-gray-800">{{ __('ទីតាំង') }}:</p>
                                            <p>{{ $room->location_of_room ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ __('ឈ្មោះ Wifi') }}:</p>
                                            <p>{{ $room->wifi_name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ __('ពាក្យសម្ងាត់ Wifi') }}:</p>
                                            <p>{{ $room->wifi_password ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 px-6 py-4 flex flex-col sm:flex-row justify-between items-center bg-gray-50/70">
                                    <div class="text-xs text-gray-500 mb-2 sm:mb-0">
                                        <p>{{ __('កែប្រែ') }}: {{ $room->updated_at->format('d/m/Y') }}</p>
                                    </div>
                                    {{-- route('admin.rooms.edit', $room->id) --}}
                                   <div class="flex justify-end space-x-3 mt-auto">
                                        {{-- Edit button --}}
                                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                             <button type="button" onclick="openDeleteModal('{{$room->id }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-10 text-center text-lg font-medium text-gray-400">
                                <p>{{ __('មិនមានទិន្នន័យបន្ទប់ទេ') }} 😢</p>
                            </div>
                        @endforelse
                    </div>
{{-- {{ route('admin.rooms.destroy', $room->id) }} --}}
                    {{-- ------------------------------------------------------------------------------------------------------------------------- --}}
                    {{-- 2. TABLE VIEW (UPDATED: Changed x-show="!isCardView" to x-show="viewMode === 'table'") --}}
                    {{-- ------------------------------------------------------------------------------------------------------------------------- --}}
                    <div x-show="viewMode === 'table'" x-cloak class="overflow-x-auto rounded-xl shadow-lg border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- Table Header --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('លេខបន្ទប់') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សមត្ថភាព') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">{{ __('ប្រភេទ') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">{{ __('ទីតាំង') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">{{ __('Wifi Name') }}</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">{{ __('សកម្មភាព') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($rooms as $room)
                                    <tr class="hover:bg-green-50/50 transition duration-150">
                                        {{-- Room Number --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $room->room_number }}
                                        </td>
                                        {{-- Capacity --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $room->capacity }}
                                        </td>
                                        {{-- Type (Hidden on XS/SM) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden md:table-cell">
                                            {{ $room->type_of_room ?? 'N/A' }}
                                        </td>
                                        {{-- Location (Hidden on XS/SM/MD) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden lg:table-cell">
                                            {{ $room->location_of_room ?? 'N/A' }}
                                        </td>
                                        {{-- Wifi Name (Hidden on XS/SM/MD) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden lg:table-cell">
                                            {{ $room->wifi_name ?? 'N/A' }}
                                        </td>
                                        {{-- Actions --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                              
                                    {{-- route('admin.rooms.edit', $room->id) --}}
                                   <div class="flex justify-end space-x-3 mt-auto">
                                        {{-- Edit button --}}
                                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                             <button type="button" onclick="openDeleteModal('{{$room->id }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                    </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-lg font-medium text-gray-400">
                                            {{ __('មិនមានទិន្នន័យបន្ទប់ទេ') }} 😢
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
<div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-red-200">
            <div class="bg-white p-6 sm:p-8">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                            {{ __('បញ្ជាក់ការលុប') }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ __('តើអ្នកពិតជាចង់លុបដេប៉ាតឺម៉ង់នេះមែនទេ? វានឹងលុបកម្មវិធីសិក្សា និងមុខវិជ្ជាដែលពាក់ព័ន្ធទាំងអស់។') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-3xl">
                {{-- Form action is empty and will be set by JS --}}
                <form id="delete-form" action="" method="POST"> 
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-6 py-3 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                        {{ __('លុប') }}
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                    {{ __('បោះបង់') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const deleteModal = document.getElementById('delete-modal');
    // Get the form by its new ID
    const deleteForm = document.getElementById('delete-form');

    function openDeleteModal(roomId) {
        // Correctly set the action URL dynamically
        deleteForm.action = `/admin/rooms/${roomId}`;
        deleteModal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
    }
</script>