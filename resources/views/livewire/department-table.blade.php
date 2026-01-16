<div class="mt-8">
                    @if ($departments->isNotEmpty())
                        {{-- GRID VIEW --}}
                        <div x-show="viewMode === 'grid'" x-transition:enter.duration.500ms>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($departments as $department)
                                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex flex-col items-start mb-6">
                                        <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
                                                <rect x="6" y="10" width="4" height="6"></rect>
                                                <rect x="14" y="10" width="4" height="6"></rect>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-2xl font-bold text-gray-900 leading-tight">{{ $department->name_km }}</h4>
                                            <p class="text-base text-gray-500 mt-1">{{ $department->name_en }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2 mb-6">
                                        <p class="text-gray-700 font-medium"><span class="font-bold text-gray-800">{{ __('មហាវិទ្យាល័យ') }}</span>: <span class="text-gray-600">{{ $department->faculty->name_km ?? 'N/A' }}</span></p>
                                        <p class="text-gray-700 font-medium"><span class="font-bold text-gray-800">{{ __('ប្រធានដេប៉ាតឺម៉ង់') }}</span>: <span class="text-gray-600">{{ $department->head->name ?? 'N/A' }}</span></p>
                                    </div>
                                    <div class="flex justify-end space-x-3 mt-auto">
                                        <a href="{{ route('admin.edit-department', $department->id) }}" class="p-3 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('កែប្រែ') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="openDeleteModal('{{ $department->id }}')" class="p-3 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out" title="{{ __('លុប') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="mt-8">
                                {{ $departments->links('pagination::tailwind') }}
                            </div>
                        </div>

                        {{-- TABLE VIEW --}}
                        <div x-show="viewMode === 'table'" x-transition:enter.duration.500ms style="display: none;">
                            <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('លេខរៀង') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះដេប៉ាតឺម៉ង់') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះមហាវិទ្យាល័យ') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ប្រធាន') }}</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($departments as $department)
                                            <tr class="hover:bg-gray-50 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $loop->iteration + (($departments->currentPage() - 1) * $departments->perPage()) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">{{ $department->name_km }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">{{ $department->faculty->name_km ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $department->head->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                    <div class="flex justify-end space-x-3">
                                                        <a href="{{ route('admin.edit-department', $department->id) }}" class="p-2 bg-gray-100 rounded-full text-green-600 hover:bg-gray-200 transition duration-150 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                            </svg>
                                                        </a>
                                                        <button type="button" onclick="openDeleteModal('{{ $department->id }}')" class="p-2 bg-gray-100 rounded-full text-red-600 hover:bg-gray-200 transition duration-150 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-8">
                                {{ $departments->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @else
                        <div class="bg-white p-12 rounded-3xl text-center text-gray-400 shadow-xl border border-gray-100">
                            <p class="font-semibold text-lg">{{ __('មិនទាន់មានដេប៉ាតឺម៉ង់ណាមួយនៅឡើយទេ។') }}</p>
                        </div>
                    @endif
                </div>