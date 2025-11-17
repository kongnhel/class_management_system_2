<!--
    This is an updated version of a Laravel Blade template for a user management dashboard.
    It includes a modern confirmation modal for delete actions, replacing the native browser confirm() dialog.
    The styling is done with Tailwind CSS, and interactivity is handled by Alpine.js.
-->
<x-app-layout>
    <x-slot name="header">
        <div class="px-4 md:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold text-gray-900 leading-tight flex items-center">
                {{ __('គ្រប់គ្រងអ្នកប្រើប្រាស់') }} <i class="fas fa-users-cog text-green-600 ml-4"></i>
            </h2>
            <p class="mt-2 text-lg text-gray-500">{{ __('បញ្ជីឈ្មោះអ្នកប្រើប្រាស់ទាំងអស់នៅក្នុងប្រព័ន្ធ') }}</p>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-8 lg:p-12 border border-gray-100">

                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ __('បញ្ជីអ្នកប្រើប្រាស់') }}</h3>
                    <div class="flex items-center space-x-4 w-full md:w-auto">
                        <!-- Search Form -->
                        <form action="{{ route('admin.manage-users') }}" method="GET" class="flex-grow">
                            <label for="search-input" class="sr-only">{{ __('ស្វែងរកអ្នកប្រើប្រាស់...') }}</label>
                            <div class="relative">
                                <input
                                    id="search-input"
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="{{ __('ស្វែងរកអ្នកប្រើប្រាស់...') }}"
                                    class="w-full px-5 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-green-500 focus:outline-none text-sm transition duration-200 pl-10"
                                >
                                <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            </div>
                        </form>
                        
                        <!-- Add New User Button -->
                        <a href="{{ route('admin.create-user') }}"
                           class="flex-shrink-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-600 border border-transparent rounded-full font-bold text-base text-white hover:from-green-700 hover:to-green-700 active:from-green-800 active:to-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus mr-3 text-lg"></i> {{ __('បន្ថែមអ្នកប្រើប្រាស់ថ្មី') }}
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

                <!-- Alpine.js data for managing active tab and delete modal state -->
                <div x-data="{ 
                    activeTab: '{{ request()->query('tab', 'professors') }}', 
                    searchQuery: '{{ request('search') }}',
                    showDeleteModal: false,
                    deletingUserId: null,
                    deletingUserType: '',

                    confirmDelete(userId, userType) {
                        this.deletingUserId = userId;
                        this.deletingUserType = userType;
                        this.showDeleteModal = true;
                    }
                }" class="mt-8">
                    <div class="border-b-2 border-gray-200">
                        <nav class="-mb-0.5 flex space-x-6" aria-label="Tabs">
                            <a href="{{ route('admin.manage-users', ['tab' => 'admins', 'search' => request('search')]) }}" @click.prevent="activeTab = 'admins'"
                               class="whitespace-nowrap py-4 px-1 border-b-2 text-lg transition-colors duration-200"
                               :class="{ 'border-green-500 text-green-600 font-semibold': activeTab === 'admins', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'admins' }">
                                <i class="fas fa-user-shield mr-2"></i>{{ __('អ្នកគ្រប់គ្រង') }}
                            </a>
                            <a href="{{ route('admin.manage-users', ['tab' => 'professors', 'search' => request('search')]) }}" @click.prevent="activeTab = 'professors'"
                               class="whitespace-nowrap py-4 px-1 border-b-2 text-lg transition-colors duration-200"
                               :class="{ 'border-green-500 text-green-600 font-semibold': activeTab === 'professors', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'professors' }">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>{{ __('លោកគ្រូអ្នកគ្រូ') }}
                            </a>
                            <a href="{{ route('admin.manage-users', ['tab' => 'students', 'search' => request('search')]) }}" @click.prevent="activeTab = 'students'"
                               class="whitespace-nowrap py-4 px-1 border-b-2 text-lg transition-colors duration-200"
                               :class="{ 'border-green-500 text-green-600 font-semibold': activeTab === 'students', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'students' }">
                                <i class="fas fa-user-graduate mr-2"></i>{{ __('និស្សិត') }}
                            </a>
                        </nav>
                    </div>

                    <div class="mt-8">
                        <!-- Admins Tab Content -->
                        <div x-show="activeTab === 'admins'" class="space-y-6">
                            @if ($admins->isEmpty())
                                <div class="bg-gray-100 p-8 rounded-xl text-center text-gray-500 shadow-inner">
                                    <p class="text-xl font-medium">{{ __('មិនទាន់មានអ្នកគ្រប់គ្រងណាមួយនៅឡើយទេ។') }}</p>
                                </div>
                            @else
                            <div id="screen-users" class="hidden md:block overflow-x-auto rounded-2xl shadow-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('រូបភាព') }}</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ឈ្មោះអ្នកប្រើប្រាស់') }}</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('អ៊ីម៉ែល') }}</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ឈ្មោះពេញ') }}</th>
                                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($admins as $admin)
                                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if ($admin->profile && $admin->profile->profile_picture_url)
                                                            <img src="{{ asset('storage/' . $admin->profile->profile_picture_url) }}" alt="{{ $admin->name }}" class="h-10 w-10 rounded-full object-cover">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                                <i class="fas fa-user text-xl"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $admin->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $admin->email }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $admin->profile->full_name_km ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <!-- 💡 ADDED VIEW BUTTON -->
                                                        <a href="{{ route('admin.show-user', $admin->id) }}" class="text-green-600 hover:text-green-900 mr-4 transition-colors duration-200">{{ __('មើល') }}</a>
                                                        <a href="{{ route('admin.edit-user', $admin->id) }}" class="text-green-600 hover:text-green-900 mr-4 transition-colors duration-200">{{ __('កែប្រែ') }}</a>
                                                        <form id="delete-admin-{{ $admin->id }}" action="{{ route('admin.delete-user', $admin->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" @click="confirmDelete('delete-admin-{{ $admin->id }}', '{{ __('អ្នកគ្រប់គ្រង') }}')" class="text-red-600 hover:text-red-900 transition-colors duration-200">{{ __('លុប') }}</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- 2. MOBILE CARD VERSION (Stacked Cards - SHOWN on mobile) --}}
                                    <div id="mobile-admins" class="block md:hidden space-y-4">
                                        @foreach ($admins as $admin)
                                            <div class="user-card bg-white border border-gray-200 rounded-xl shadow-lg p-5 space-y-3">
                                                
                                                {{-- Header: Profile Picture & Username --}}
                                                <div class="flex items-center space-x-3 border-b pb-3">
                                                    {{-- Profile Picture --}}
                                                    @if ($admin->profile && $admin->profile->profile_picture_url)
                                                        <img src="{{ asset('storage/' . $admin->profile->profile_picture_url) }}" alt="{{ $admin->name }}" class="h-10 w-10 rounded-full object-cover flex-shrink-0">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                                                            <i class="fas fa-user text-xl"></i>
                                                        </div>
                                                    @endif
                                                    {{-- Username --}}
                                                    <p class="text-lg font-extrabold text-gray-900 leading-tight truncate">{{ $admin->name }}</p>
                                                </div>

                                                {{-- Details Grid --}}
                                                <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
                                                    
                                                    {{-- Full Name --}}
                                                    <p class="font-medium text-gray-500">{{ __('ឈ្មោះពេញ:') }}</p>
                                                    <p class="text-gray-800 font-semibold truncate text-right">{{ $admin->profile->full_name_km ?? 'N/A' }}</p>

                                                    {{-- Email --}}
                                                    <p class="font-medium text-gray-500">{{ __('អ៊ីម៉ែល:') }}</p>
                                                    <p class="text-gray-800 font-semibold truncate text-right">{{ $admin->email }}</p>
                                                    
                                                </div>
                                                
                                                {{-- Actions --}}
                                                <div class="flex justify-end space-x-4 pt-3 border-t mt-3">
                                                    <a href="{{ route('admin.show-user', $admin->id) }}"
                                                        class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                        {{ __('មើល') }}
                                                    </a>
                                                    <a href="{{ route('admin.edit-user', $admin->id) }}"
                                                        class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                        {{ __('កែប្រែ') }}
                                                    </a>
                                                    
                                                    <form id="delete-admin-{{ $admin->id }}-mobile" action="{{ route('admin.delete-user', $admin->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                @click="confirmDelete('delete-admin-{{ $admin->id }}', '{{ __('អ្នកគ្រប់គ្រង') }}')"
                                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                            {{ __('លុប') }}
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
                                <div class="mt-6">
                                    {{ $admins->links('pagination::tailwind', ['pageName' => 'adminsPage']) }}
                                </div>
                            @endif
                        </div>

                    <div x-show="activeTab === 'professors'" class="space-y-6">
                        @if ($professors->isEmpty())
                            <div class="bg-gray-100 p-8 rounded-xl text-center text-gray-500 shadow-inner">
                                <p class="text-xl font-medium">{{ __('មិនទាន់មានលោកគ្រូអ្នកគ្រូណាមួយនៅឡើយទេ។') }}</p>
                            </div>
                        @else
                            {{-- 1. DESKTOP/TABLET VERSION (Traditional Table - HIDDEN on mobile) --}}
                            <div id="screen-professors" class="hidden md:block overflow-x-auto rounded-2xl shadow-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('រូបភាព') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ឈ្មោះអ្នកប្រើប្រាស់') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('អ៊ីម៉ែល') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ឈ្មោះពេញ') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ដេប៉ាតឺម៉ង់') }}</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($professors as $professor)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($professor->profile && $professor->profile->profile_picture_url)
                                                        <img src="{{ asset('storage/' . $professor->profile->profile_picture_url) }}" alt="{{ $professor->name }}" class="h-10 w-10 rounded-full object-cover">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                            <i class="fas fa-user text-xl"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $professor->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $professor->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $professor->profile->full_name_km ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $professor->department->name_km ?? $professor->department->name_en ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('admin.show-user', $professor->id) }}" class="text-green-600 hover:text-green-900 mr-4 transition-colors duration-200">{{ __('មើល') }}</a>
                                                    <a href="{{ route('admin.edit-user', $professor->id) }}" class="text-green-600 hover:text-green-900 mr-4 transition-colors duration-200">{{ __('កែប្រែ') }}</a>
                                                    <form id="delete-professor-{{ $professor->id }}" action="{{ route('admin.delete-user', $professor->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" @click="confirmDelete('delete-professor-{{ $professor->id }}', '{{ __('លោកគ្រូអ្នកគ្រូ') }}')" class="text-red-600 hover:text-red-900 transition-colors duration-200">{{ __('លុប') }}</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- 2. MOBILE CARD VERSION (Stacked Cards - SHOWN on mobile) --}}
                            <div id="mobile-professors" class="block md:hidden space-y-4">
                                @foreach ($professors as $professor)
                                    <div class="user-card bg-white border border-gray-200 rounded-xl shadow-lg p-5 space-y-3">
                                        
                                        {{-- Header: Profile Picture & Username --}}
                                        <div class="flex items-center space-x-3 border-b pb-3">
                                            {{-- Profile Picture --}}
                                            @if ($professor->profile && $professor->profile->profile_picture_url)
                                                <img src="{{ asset('storage/' . $professor->profile->profile_picture_url) }}" alt="{{ $professor->name }}" class="h-10 w-10 rounded-full object-cover flex-shrink-0">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                                                    <i class="fas fa-user text-xl"></i>
                                                </div>
                                            @endif
                                            {{-- Username --}}
                                            <p class="text-lg font-extrabold text-gray-900 leading-tight truncate">{{ $professor->name }}</p>
                                        </div>

                                        {{-- Details Grid --}}
                                        <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
                                            
                                            {{-- Full Name --}}
                                            <p class="font-medium text-gray-500">{{ __('ឈ្មោះពេញ:') }}</p>
                                            <p class="text-gray-800 font-semibold truncate text-right">{{ $professor->profile->full_name_km ?? 'N/A' }}</p>

                                            {{-- Email --}}
                                            <p class="font-medium text-gray-500">{{ __('អ៊ីម៉ែល:') }}</p>
                                            <p class="text-gray-800 font-semibold truncate text-right">{{ $professor->email }}</p>

                                            {{-- Department --}}
                                            <p class="font-medium text-gray-500">{{ __('ដេប៉ាតឺម៉ង់:') }}</p>
                                            <p class="text-gray-800 font-semibold truncate text-right">{{ $professor->department->name_km ?? $professor->department->name_en ?? 'N/A' }}</p>
                                            
                                        </div>
                                        
                                        {{-- Actions --}}
                                        <div class="flex justify-end space-x-4 pt-3 border-t mt-3">
                                            <a href="{{ route('admin.show-user', $professor->id) }}"
                                                class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                {{ __('មើល') }}
                                            </a>
                                            <a href="{{ route('admin.edit-user', $professor->id) }}"
                                                class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                {{ __('កែប្រែ') }}
                                            </a>
                                            
                                            {{-- Reuse the same form ID as the desktop table to link to the Alpine modal --}}
                                            <form id="delete-professor-{{ $professor->id }}" action="{{ route('admin.delete-user', $professor->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        @click="confirmDelete('delete-professor-{{ $professor->id }}', '{{ __('លោកគ្រូអ្នកគ្រូ') }}')"
                                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                    {{ __('លុប') }}
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $professors->links('pagination::tailwind', ['pageName' => 'professorsPage']) }}
                            </div>
                        @endif
                    </div>

                        <!-- Students Tab Content -->
                    <div x-show="activeTab === 'students'" class="space-y-6">
                        @if ($students->isEmpty())
                            <div class="bg-gray-100 p-8 rounded-xl text-center text-gray-500 shadow-inner">
                                <p class="text-xl font-medium">{{ __('មិនទាន់មាននិស្សិតណាមួយនៅឡើយទេ។') }}</p>
                            </div>
                        @else
                            {{-- 1. DESKTOP/TABLET VERSION (Traditional Table - HIDDEN on mobile) --}}
                            <div id="screen-students" class="hidden md:block overflow-x-auto rounded-2xl shadow-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('រូបភាព') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ឈ្មោះអ្នកប្រើប្រាស់') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('អ៊ីម៉ែល') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ឈ្មោះពេញ') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('កម្មវិធីសិក្សា') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ជំនាន់') }}</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($students as $student)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($student->studentProfile && $student->studentProfile->profile_picture_url)
                                                        <img src="{{ asset('storage/' . $student->studentProfile->profile_picture_url) }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                            <i class="fas fa-user text-xl"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $student->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $student->studentProfile->full_name_km ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $student->program->name_km ?? $student->program->name_en ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $student->generation ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('admin.show-user', $student->id) }}" class="text-green-600 hover:text-green-900 mr-4 transition-colors duration-200">{{ __('មើល') }}</a>
                                                    <a href="{{ route('admin.edit-user', $student->id) }}" class="text-green-600 hover:text-green-900 mr-4 transition-colors duration-200">{{ __('កែប្រែ') }}</a>
                                                    <form id="delete-student-{{ $student->id }}" action="{{ route('admin.delete-user', $student->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" @click="confirmDelete('delete-student-{{ $student->id }}', '{{ __('និស្សិត') }}')" class="text-red-600 hover:text-red-900 transition-colors duration-200">{{ __('លុប') }}</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- 2. MOBILE CARD VERSION (Stacked Cards - SHOWN on mobile) --}}
                            <div id="mobile-students" class="block md:hidden space-y-4">
                                @foreach ($students as $student)
                                    <div class="user-card bg-white border border-gray-200 rounded-xl shadow-lg p-5 space-y-3">
                                        
                                        {{-- Header: Profile Picture & Username --}}
                                        <div class="flex items-center space-x-3 border-b pb-3">
                                            {{-- Profile Picture --}}
                                            @if ($student->studentProfile && $student->studentProfile->profile_picture_url)
                                                <img src="{{ asset('storage/' . $student->studentProfile->profile_picture_url) }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover flex-shrink-0">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                                                    <i class="fas fa-user text-xl"></i>
                                                </div>
                                            @endif
                                            {{-- Username --}}
                                            <p class="text-lg font-extrabold text-gray-900 leading-tight truncate">{{ $student->name }}</p>
                                        </div>

                                        {{-- Details Grid --}}
                                        <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
                                            
                                            {{-- Full Name --}}
                                            <p class="font-medium text-gray-500">{{ __('ឈ្មោះពេញ:') }}</p>
                                            <p class="text-gray-800 font-semibold truncate text-right">{{ $student->studentProfile->full_name_km ?? 'N/A' }}</p>

                                            {{-- Email --}}
                                            <p class="font-medium text-gray-500">{{ __('អ៊ីម៉ែល:') }}</p>
                                            <p class="text-gray-800 font-semibold truncate text-right">{{ $student->email }}</p>

                                            {{-- Program --}}
                                            <p class="font-medium text-gray-500">{{ __('កម្មវិធីសិក្សា:') }}</p>
                                            <p class="text-gray-800 font-semibold truncate text-right">{{ $student->program->name_km ?? $student->program->name_en ?? 'N/A' }}</p>
                                            
                                            {{-- Generation --}}
                                            <p class="font-medium text-gray-500">{{ __('ជំនាន់:') }}</p>
                                            <p class="text-gray-800 font-semibold truncate text-right">{{ $student->generation ?? 'N/A' }}</p>

                                        </div>
                                        
                                        {{-- Actions --}}
                                        <div class="flex justify-end space-x-4 pt-3 border-t mt-3">
                                            <a href="{{ route('admin.show-user', $student->id) }}"
                                                class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                {{ __('មើល') }}
                                            </a>
                                            <a href="{{ route('admin.edit-user', $student->id) }}"
                                                class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                {{ __('កែប្រែ') }}
                                            </a>
                                            
                                            {{-- Reuse the same form ID as the desktop table to link to the Alpine modal --}}
                                            <form id="delete-student-{{ $student->id }}" action="{{ route('admin.delete-user', $student->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        @click="confirmDelete('delete-student-{{ $student->id }}', '{{ __('និស្សិត') }}')"
                                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                    {{ __('លុប') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $students->links('pagination::tailwind', ['pageName' => 'studentsPage']) }}
                            </div>
                        @endif
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 ">
                            <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="inline-block align-bottom bg-white rounded-3xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.876c1.54 0 2.585-1.745 1.74-3.134L13.435 4.31a2 2 0 00-3.47 0L4.312 15.866C3.467 17.255 4.512 19 6.05 19z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            {{ __('បញ្ជាក់ការលុប') }}
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                {{ __('តើអ្នកពិតជាចង់លុប') }} <span x-text="deletingUserType"></span> {{ __('នេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់ថយក្រោយបានទេ។') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="button" @click="document.getElementById(deletingUserId).submit()"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        {{ __('លុប') }}
                                    </button>
                                    <button type="button" @click="showDeleteModal = false"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm">
                                        {{ __('បោះបង់') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>