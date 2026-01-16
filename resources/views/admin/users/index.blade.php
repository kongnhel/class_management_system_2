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

<div class="flex flex-col lg:flex-row justify-between items-center mb-10 gap-6">
    <div class="text-center lg:text-left">
        <h3 class="text-3xl font-bold text-gray-800 tracking-tight">
            {{ __('បញ្ជីអ្នកប្រើប្រាស់') }}
        </h3>
        <p class="text-gray-500 text-sm mt-1">{{ __('គ្រប់គ្រង និងតាមដានព័ត៌មានសមាជិកទាំងអស់') }}</p>
    </div>

    <div class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
        
        <form action="{{ route('admin.manage-users') }}" method="GET" class="w-full md:w-80">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-green-500 transition-colors"></i>
                </div>
                <input
                    id="search-input"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('ស្វែងរកឈ្មោះ ឬអ៊ីម៉ែល...') }}"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 focus:bg-white transition-all duration-200 outline-none"
                >
            </div>
        </form>
        
        <div class="hidden md:block h-8 w-px bg-gray-200"></div>

        <a href="{{ route('admin.create-user') }}"
           class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 bg-green-600 border border-transparent rounded-2xl font-bold text-sm text-white hover:bg-green-700 active:scale-95 focus:outline-none focus:ring-4 focus:ring-green-500/30 transition-all duration-200 shadow-lg shadow-green-200">
            <i class="fas fa-plus-circle mr-2 text-lg"></i> 
            {{ __('បន្ថែមសមាជិកថ្មី') }}
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
<div x-show="activeTab === 'admins'" class="space-y-3">
    @if ($admins->isEmpty())
        <div class="bg-gray-100 p-6 rounded-xl text-center text-gray-500 shadow-inner">
            <p class="text-base font-medium">{{ __('មិនទាន់មានអ្នកគ្រប់គ្រងណាមួយនៅឡើយទេ។') }}</p>
        </div>
    @else
        {{-- 1. DESKTOP VERSION --}}
        <div id="screen-admins" class="hidden md:block overflow-x-auto rounded-2xl shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('រូបភាព') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះអ្នកប្រើ') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('អ៊ីម៉ែល') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះពេញ') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @foreach ($admins as $admin)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3">
                                @if ($admin->profile && $admin->profile->profile_picture_url)
                                    <img src="{{ asset('storage/' . $admin->profile->profile_picture_url) }}" class="h-10 w-10 rounded-full object-cover border border-gray-100">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $admin->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $admin->email }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $admin->profile->full_name_km ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right font-bold space-x-3">
                                <a href="{{ route('admin.show-user', $admin->id) }}" class="text-green-600 hover:underline">{{ __('មើល') }}</a>
                                <a href="{{ route('admin.edit-user', $admin->id) }}" class="text-blue-600 hover:underline">{{ __('កែប្រែ') }}</a>
                                <button type="button" @click="confirmDelete('delete-admin-{{ $admin->id }}', '{{ __('អ្នកគ្រប់គ្រង') }}')" class="text-red-500 hover:underline">{{ __('លុប') }}</button>
                                <form id="delete-admin-{{ $admin->id }}" action="{{ route('admin.delete-user', $admin->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 2. MOBILE VERSION --}}
        <div id="mobile-admins" class="md:hidden space-y-3">
            @foreach ($admins as $admin)
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-50">
                        <div class="flex items-center space-x-3 min-w-0">
                            @if ($admin->profile && $admin->profile->profile_picture_url)
                                <img src="{{ asset('storage/' . $admin->profile->profile_picture_url) }}" class="h-12 w-12 rounded-full object-cover border border-gray-100">
                            @else
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-black text-xl shadow-md flex-shrink-0">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-base font-black text-gray-900 truncate tracking-tight uppercase">{{ $admin->name }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $admin->email }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="bg-red-50 text-red-700 text-[10px] font-bold px-2 py-1 rounded border border-red-100 uppercase tracking-widest">Admin</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                             <p class="text-xs text-gray-600 font-medium truncate">{{ $admin->profile->full_name_km ?? 'No Name' }}</p>
                        </div>
                        <div class="flex space-x-4 text-xs font-bold">
                            <a href="{{ route('admin.show-user', $admin->id) }}" class="text-green-600 flex items-center">
                                <i class="fas fa-eye mr-1 text-[10px]"></i> {{ __('មើល') }}
                            </a>
                            <a href="{{ route('admin.edit-user', $admin->id) }}" class="text-blue-600 flex items-center">
                                <i class="fas fa-edit mr-1 text-[10px]"></i> {{ __('កែ') }}
                            </a>
                            <button @click="confirmDelete('del-adm-mob-{{ $admin->id }}', 'Admin')" class="text-red-500 flex items-center">
                                <i class="fas fa-trash mr-1 text-[10px]"></i> {{ __('លុប') }}
                            </button>
                        </div>
                        <form id="del-adm-mob-{{ $admin->id }}" action="{{ route('admin.delete-user', $admin->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $admins->links('pagination::tailwind', ['pageName' => 'adminsPage']) }}
        </div>
    @endif
</div>

<div x-show="activeTab === 'professors'" class="space-y-3">
    @if ($professors->isEmpty())
        <div class="bg-gray-100 p-6 rounded-xl text-center text-gray-500 shadow-inner">
            <p class="text-base font-medium">{{ __('មិនទាន់មានលោកគ្រូអ្នកគ្រូណាមួយនៅឡើយទេ។') }}</p>
        </div>
    @else
        {{-- 1. DESKTOP VERSION --}}
        <div id="screen-professors" class="hidden md:block overflow-x-auto rounded-2xl shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('រូបភាព') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះអ្នកប្រើ') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('អ៊ីម៉ែល') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះពេញ') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ដេប៉ាតឺម៉ង់') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @foreach ($professors as $professor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3">
                                @if ($professor->profile && $professor->profile->profile_picture_url)
                                    <img src="{{ asset('storage/' . $professor->profile->profile_picture_url) }}" class="h-10 w-10 rounded-full object-cover border border-gray-100">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                        {{ strtoupper(substr($professor->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $professor->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $professor->email }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $professor->profile->full_name_km ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $professor->department->name_km ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right font-bold space-x-3">
                                <a href="{{ route('admin.show-user', $professor->id) }}" class="text-green-600 hover:underline">{{ __('មើល') }}</a>
                                <a href="{{ route('admin.edit-user', $professor->id) }}" class="text-blue-600 hover:underline">{{ __('កែប្រែ') }}</a>
                                <button type="button" @click="confirmDelete('delete-professor-{{ $professor->id }}', '{{ __('លោកគ្រូអ្នកគ្រូ') }}')" class="text-red-500 hover:underline">{{ __('លុប') }}</button>
                                <form id="delete-professor-{{ $professor->id }}" action="{{ route('admin.delete-user', $professor->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 2. MOBILE VERSION --}}
        <div id="mobile-professors" class="md:hidden space-y-3">
            @foreach ($professors as $professor)
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-50">
                        <div class="flex items-center space-x-3 min-w-0">
                            @if ($professor->profile && $professor->profile->profile_picture_url)
                                <img src="{{ asset('storage/' . $professor->profile->profile_picture_url) }}" class="h-12 w-12 rounded-full object-cover border border-gray-100">
                            @else
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-black text-xl shadow-md flex-shrink-0">
                                    {{ strtoupper(substr($professor->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-base font-black text-gray-900 truncate tracking-tight uppercase">{{ $professor->name }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $professor->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 pr-2">
                            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-tighter">{{ __('ដេប៉ាតឺម៉ង់') }}</p>
                            <p class="text-xs text-gray-700 font-bold truncate">{{ $professor->department->name_km ?? 'N/A' }}</p>
                        </div>
                        <div class="flex space-x-4 text-xs font-bold flex-shrink-0">
                            <a href="{{ route('admin.show-user', $professor->id) }}" class="text-green-600 flex items-center">
                                <i class="fas fa-eye mr-1 text-[10px]"></i> {{ __('មើល') }}
                            </a>
                            <a href="{{ route('admin.edit-user', $professor->id) }}" class="text-blue-600 flex items-center">
                                <i class="fas fa-edit mr-1 text-[10px]"></i> {{ __('កែ') }}
                            </a>
                            <button @click="confirmDelete('del-prof-mob-{{ $professor->id }}', 'Professor')" class="text-red-500 flex items-center">
                                <i class="fas fa-trash mr-1 text-[10px]"></i> {{ __('លុប') }}
                            </button>
                        </div>
                        <form id="del-prof-mob-{{ $professor->id }}" action="{{ route('admin.delete-user', $professor->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $professors->links('pagination::tailwind', ['pageName' => 'professorsPage']) }}
        </div>
    @endif
</div>

                        <!-- Students Tab Content -->
<div x-show="activeTab === 'students'" class="space-y-3">
    @if ($students->isEmpty())
        <div class="bg-gray-100 p-6 rounded-xl text-center text-gray-500 shadow-inner">
            <p class="text-base font-medium">{{ __('មិនទាន់មាននិស្សិតណាមួយនៅឡើយទេ។') }}</p>
        </div>
    @else
        {{-- 1. DESKTOP VERSION --}}
        <div id="screen-students" class="hidden md:block overflow-x-auto rounded-2xl shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('រូបភាព') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះអ្នកប្រើ') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('អ៊ីម៉ែល') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ឈ្មោះពេញ') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('កម្មវិធីសិក្សា') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('ជំនាន់') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('សកម្មភាព') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3">
                                @if ($student->studentProfile && $student->studentProfile->profile_picture_url)
                                    <img src="{{ asset('storage/' . $student->studentProfile->profile_picture_url) }}" class="h-10 w-10 rounded-full object-cover border border-gray-100">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $student->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $student->email }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $student->studentProfile->full_name_km ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $student->program->name_km ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $student->generation ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right font-bold space-x-3">
                                <a href="{{ route('admin.show-user', $student->id) }}" class="text-green-600 hover:underline">{{ __('មើល') }}</a>
                                <a href="{{ route('admin.edit-user', $student->id) }}" class="text-blue-600 hover:underline">{{ __('កែប្រែ') }}</a>
                                <button type="button" @click="confirmDelete('delete-student-{{ $student->id }}', '{{ __('និស្សិត') }}')" class="text-red-500 hover:underline">{{ __('លុប') }}</button>
                                <form id="delete-student-{{ $student->id }}" action="{{ route('admin.delete-user', $student->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 2. MOBILE VERSION (Semi-Table / Card Style) --}}
        <div id="mobile-students" class="md:hidden space-y-3">
            @foreach ($students as $student)
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-50">
                        <div class="flex items-center space-x-3 min-w-0">
                            {{-- Check for Profile Pic or use First Letter --}}
                            @if ($student->studentProfile && $student->studentProfile->profile_picture_url)
                                <img src="{{ asset('storage/' . $student->studentProfile->profile_picture_url) }}" class="h-12 w-12 rounded-full object-cover border border-gray-100">
                            @else
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white font-black text-xl shadow-md flex-shrink-0">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-base font-black text-gray-900 truncate tracking-tight uppercase">{{ $student->name }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $student->email }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="inline-block bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded-lg border border-green-100 uppercase">
                                G{{ $student->generation ?? '?' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] text-gray-500 font-medium italic">
                            {{ $student->program->name_km ?? 'N/A' }}
                        </div>
                        <div class="flex space-x-4 text-xs font-bold">
                            <a href="{{ route('admin.show-user', $student->id) }}" class="text-green-600 flex items-center tracking-wide">
                                <i class="fas fa-eye mr-1 text-[10px]"></i> {{ __('មើល') }}
                            </a>
                            <a href="{{ route('admin.edit-user', $student->id) }}" class="text-blue-600 flex items-center tracking-wide">
                                <i class="fas fa-edit mr-1 text-[10px]"></i> {{ __('កែ') }}
                            </a>
                            <button @click="confirmDelete('del-std-mob-{{ $student->id }}', 'Student')" class="text-red-500 flex items-center tracking-wide">
                                <i class="fas fa-trash mr-1 text-[10px]"></i> {{ __('លុប') }}
                            </button>
                        </div>
                        <form id="del-std-mob-{{ $student->id }}" action="{{ route('admin.delete-user', $student->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $students->links('pagination::tailwind', ['pageName' => 'studentsPage']) }}
        </div>
    @endif
</div>

                    <!-- Delete Confirmation Modal -->
<div x-show="showDeleteModal" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     x-cloak>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div x-show="showDeleteModal"
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" 
             @click="showDeleteModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showDeleteModal"
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full">
            
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.876c1.54 0 2.585-1.745 1.74-3.134L13.435 4.31a2 2 0 00-3.47 0L4.312 15.866C3.467 17.255 4.512 19 6.05 19z" />
                        </svg>
                    </div>
                    
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            {{ __('បញ្ជាក់ការលុប') }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 leading-relaxed">
                                {{ __('តើអ្នកពិតជាចង់លុប') }} <span class="font-bold text-red-600" x-text="deletingUserType"></span> {{ __('នេះមែនទេ? ទិន្នន័យនឹងត្រូវបាត់បង់ជារៀងរហូត។') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                <button type="button" 
                        @click="showDeleteModal = false"
                        class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2 bg-white text-sm font-bold text-gray-700 hover:bg-gray-100 focus:outline-none transition-all">
                    {{ __('បោះបង់') }}
                </button>
                <button type="button" 
                        @click="document.getElementById(deletingUserId).submit()"
                        class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2 bg-red-600 text-sm font-bold text-white hover:bg-red-700 focus:outline-none transition-all shadow-red-200 shadow-lg">
                    {{ __('លុបចេញ') }}
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