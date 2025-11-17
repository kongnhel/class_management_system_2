<x-app-layout>
    <x-slot name="header">
        <div class="px-4 md:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold text-gray-900 leading-tight flex items-center">
                {{ __('ផ្ទាំងគ្រប់គ្រងអ្នកគ្រប់គ្រង') }} <i class="fas fa-chart-bar text-green-600 ml-4"></i>
            </h2>
            <p class="mt-2 text-lg text-gray-500">{{ __('ទិដ្ឋភាពទូទៅនៃប្រព័ន្ធ') }}</p>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12 border border-gray-100">

                {{-- Key Metrics Section --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    {{-- Total Users Card --}}
                    <div class="p-8 rounded-2xl shadow-xl flex flex-col items-start justify-between transition-transform duration-300 hover:scale-[1.03] hover:shadow-2xl cursor-pointer bg-gradient-to-br from-green-600 to-green-800 text-white">
                        <div class="flex items-center space-x-4 mb-4">
                            <i class="fas fa-users-cog text-5xl opacity-80"></i>
                            <div>
                                <p class="text-sm font-medium opacity-90 tracking-wide">{{ __('អ្នកប្រើប្រាស់សរុប') }}</p>
                                <h2 class="text-4xl font-extrabold mt-1">{{ $totalUsers }}</h2>
                            </div>
                        </div>
                    </div>

                    {{-- Total Students Card --}}
                    <div class="p-8 rounded-2xl shadow-xl flex flex-col items-start justify-between transition-transform duration-300 hover:scale-[1.03] hover:shadow-2xl cursor-pointer bg-gradient-to-br from-teal-600 to-teal-800 text-white">
                        <div class="flex items-center space-x-4 mb-4">
                            <i class="fas fa-user-graduate text-5xl opacity-80"></i>
                            <div>
                                <p class="text-sm font-medium opacity-90 tracking-wide">{{ __('និស្សិតសរុប') }}</p>
                                <h2 class="text-4xl font-extrabold mt-1">{{ $totalStudents }}</h2>
                            </div>
                        </div>
                    </div>

                    {{-- Total Professors Card --}}
                    <div class="p-8 rounded-2xl shadow-xl flex flex-col items-start justify-between transition-transform duration-300 hover:scale-[1.03] hover:shadow-2xl cursor-pointer bg-gradient-to-br from-orange-600 to-orange-800 text-white">
                        <div class="flex items-center space-x-4 mb-4">
                            <i class="fas fa-chalkboard-teacher text-5xl opacity-80"></i>
                            <div>
                                <p class="text-sm font-medium opacity-90 tracking-wide">{{ __('សាស្ត្រាចារ្យសរុប') }}</p>
                                <h2 class="text-4xl font-extrabold mt-1">{{ $totalProfessors }}</h2>
                            </div>
                        </div>
                    </div>

                    {{-- Total Faculties Card --}}
                    <div class="p-8 rounded-2xl shadow-xl flex flex-col items-start justify-between transition-transform duration-300 hover:scale-[1.03] hover:shadow-2xl cursor-pointer bg-gradient-to-br from-purple-600 to-purple-800 text-white">
                        <div class="flex items-center space-x-4 mb-4">
                            <i class="fas fa-building text-5xl opacity-80"></i>
                            <div>
                                <p class="text-sm font-medium opacity-90 tracking-wide">{{ __('មហាវិទ្យាល័យសរុប') }}</p>
                                <h2 class="text-4xl font-extrabold mt-1">{{ $totalFaculties }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions & System Info Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    {{-- Quick Actions Card --}}
                    <div class="bg-gray-50 p-8 rounded-2xl shadow-inner border border-gray-100">
                        <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center border-b pb-4 border-gray-200">
                            <i class="fas fa-bolt text-3xl mr-4 text-teal-600"></i>
                            {{ __('សកម្មភាពរហ័ស') }}
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a href="{{ route('admin.create-user') }}" class="p-5 bg-white rounded-xl shadow-md border border-gray-100 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg flex items-center space-x-4 group">
                                <i class="fas fa-user-plus text-blue-600 text-2xl group-hover:text-blue-700 transition-colors"></i>
                                <span class="font-semibold text-gray-800 group-hover:text-gray-900">{{ __('បន្ថែមអ្នកប្រើប្រាស់ថ្មី') }}</span>
                            </a>
                            <a href="{{ route('admin.create-faculty') }}" class="p-5 bg-white rounded-xl shadow-md border border-gray-100 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg flex items-center space-x-4 group">
                                <i class="fas fa-university text-green-600 text-2xl group-hover:text-green-700 transition-colors"></i>
                                <span class="font-semibold text-gray-800 group-hover:text-gray-900">{{ __('បន្ថែមមហាវិទ្យាល័យថ្មី') }}</span>
                            </a>
                            <a href="{{ route('admin.manage-users') }}" class="p-5 bg-white rounded-xl shadow-md border border-gray-100 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg flex items-center space-x-4 group">
                                <i class="fas fa-users-cog text-purple-600 text-2xl group-hover:text-purple-700 transition-colors"></i>
                                <span class="font-semibold text-gray-800 group-hover:text-gray-900">{{ __('គ្រប់គ្រងអ្នកប្រើប្រាស់') }}</span>
                            </a>
                            <a href="{{ route('admin.manage-faculties') }}" class="p-5 bg-white rounded-xl shadow-md border border-gray-100 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg flex items-center space-x-4 group">
                                <i class="fas fa-building text-red-600 text-2xl group-hover:text-red-700 transition-colors"></i>
                                <span class="font-semibold text-gray-800 group-hover:text-gray-900">{{ __('គ្រប់គ្រងមហាវិទ្យាល័យ') }}</span>
                            </a>
                        </div>
                    </div>

                    {{-- System Info Card --}}
                    <div class="bg-gray-50 p-8 rounded-2xl shadow-inner border border-gray-100">
                        <h4 class="text-2xl font-bold text-gray-700 mb-6 flex items-center border-b pb-4 border-gray-200">
                            <i class="fas fa-info-circle text-3xl mr-4 text-green-600"></i>
                            {{ __('ព័ត៌មានប្រព័ន្ធ') }}
                        </h4>
                        <ul class="space-y-4 text-gray-700">
                            <li class="flex items-center justify-between space-x-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-sitemap text-green-500 text-lg flex-shrink-0"></i>
                                    <span class="font-semibold">{{ __('ដេប៉ាតឺម៉ង់សរុប:') }}</span>
                                </div>
                                <span class="font-bold text-lg text-gray-900">{{ $totalDepartments }}</span>
                            </li>
                            <li class="flex items-center justify-between space-x-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-cubes text-green-500 text-lg flex-shrink-0"></i>
                                    <span class="font-semibold">{{ __('កម្មវិធីសិក្សាសរុប:') }}</span>
                                    {{-- <span class="font-semibold">{{ __('កម្មវិធីសិក្សាសរុប:') }}</span> --}}
                                </div>
                                <span class="font-bold text-lg text-gray-900">{{ $totalPrograms }}</span>
                            </li>
                            <li class="flex items-center justify-between space-x-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-book text-green-500 text-lg flex-shrink-0"></i>
                                    <span class="font-semibold">{{ __('មុខវិជ្ជាសរុប:') }}</span>
                                </div>
                                <span class="font-bold text-lg text-gray-900">{{ $totalCourses }}</span>
                            </li>
                            <li class="flex items-center justify-between space-x-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-layer-group text-green-500 text-lg flex-shrink-0"></i>
                                    <span class="font-semibold">{{ __('វគ្គសិក្សាសរុប:') }}</span>
                                </div>
                                <span class="font-bold text-lg text-gray-900">{{ $totalCourseOfferings }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>