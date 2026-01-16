<div class="p-6"> {{-- Root Element តែមួយគត់សម្រាប់ Livewire --}}
    
    <div class="mt-8">
        @if ($faculties->isEmpty())
            {{-- បង្ហាញនៅពេលមិនទាន់មានទិន្នន័យ --}}
            <div class="bg-white p-12 rounded-3xl text-center text-gray-400 shadow-xl border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <p class="font-semibold text-lg text-gray-600">{{ __('មិនទាន់មានមហាវិទ្យាល័យណាមួយនៅឡើយទេ។') }}</p>
                <p class="mt-2 text-sm">{{ __('ចាប់ផ្តើមដោយបន្ថែមមហាវិទ្យាល័យដំបូងរបស់អ្នកដើម្បីគ្រប់គ្រងដេប៉ាតឺម៉ង់ និងកម្មវិធីសិក្សា។') }}</p>
            </div>
        @else

            {{-- CARD/GRID VIEW --}}
            <div x-show="viewMode === 'grid'" x-transition:enter.duration.500ms>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($faculties as $faculty)
                        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex flex-col items-start mb-6">
                                <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-2xl font-bold text-gray-900 leading-tight">{{ $faculty->name_km }}</h4>
                                    <p class="text-base text-gray-500 mt-1">{{ $faculty->name_en }}</p>
                                </div>
                            </div>
                            <div class="space-y-2 mb-6">
                                <p class="text-gray-700 font-medium">
                                    <span class="font-bold text-gray-800">{{ __('ប្រធានមហាវិទ្យាល័យ') }}</span>: 
                                    <span class="text-gray-600">{{ $faculty->dean->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                            <div class="flex justify-end space-x-3 mt-auto">
                                <a href="{{ route('admin.edit-faculty', $faculty->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-green-200 transition duration-150" title="{{ __('កែប្រែ') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ $faculty->id }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-red-200 transition duration-150" title="{{ __('លុប') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TABLE VIEW --}}
            <div x-show="viewMode === 'table'" x-transition:enter.duration.500ms style="display: none;">
                <div class="overflow-x-auto shadow-xl rounded-2xl border border-gray-100 bg-white">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('លេខរៀង') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះមហាវិទ្យាល័យ') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">{{ __('ប្រធាន') }}</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($faculties as $faculty)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $loop->iteration + (($faculties->currentPage() - 1) * $faculties->perPage()) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-base font-semibold text-gray-800">{{ $faculty->name_km }}</div>
                                        <div class="text-xs text-gray-400 sm:hidden">{{ $faculty->name_en }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                        {{ $faculty->dean->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.edit-faculty', $faculty->id) }}" class="p-2 bg-gray-100 rounded-full text-green-600 hover:bg-green-200">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                            </a>
                                            <button type="button" onclick="openDeleteModal('{{ $faculty->id }}')" class="p-2 bg-gray-100 rounded-full text-red-600 hover:bg-red-200">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Pagination --}}
            <div class="mt-8">
                {{ $faculties->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    {{-- DELETE MODAL (ស្ថិតនៅក្នុង Root Element) --}}
    <div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" x-data x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="closeDeleteModal()"></div>
            <div class="relative bg-white rounded-3xl overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border border-red-100">
                <div class="p-8 text-center">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('បញ្ជាក់ការលុប') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('តើអ្នកពិតជាចង់លុបមហាវិទ្យាល័យនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានឡើយ។') }}</p>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <form id="delete-form" method="POST"> 
                        @csrf @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-bold rounded-full hover:bg-red-700 transition duration-150 w-full sm:w-auto">{{ __('លុប') }}</button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-full hover:bg-gray-100 transition duration-150 w-full sm:w-auto">{{ __('បោះបង់') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT សម្រាប់បញ្ជា Modal --}}
    <script>
        function openDeleteModal(facultyId) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            // កំណត់ URL សម្រាប់ Delete តាម ID
            form.action = `/admin/faculties/${facultyId}`; 
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
        }
    </script>
</div>