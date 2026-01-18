<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-4 md:px-6 lg:px-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 leading-tight flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-chart-bar text-green-600 text-xl"></i>
                    </div>
                    {{ __('ផ្ទាំងគ្រប់គ្រងអ្នកគ្រប់គ្រង') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 ml-12">{{ __('ទិដ្ឋភាពទូទៅនៃប្រព័ន្ធ និងស្ថិតិ') }}</p>
            </div>
            {{-- Optional: Date or Breadcrumb could go here --}}
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Key Metrics Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- Total Users --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('អ្នកប្រើប្រាស់សរុប') }}</p>
                            <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalUsers }}</h3>
                        </div>
                        <div class="p-3 bg-green-50 rounded-xl text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-users-cog text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: 70%"></div>
                    </div>
                </div>

                {{-- Total Students --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('និស្សិតសរុប') }}</p>
                            <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalStudents }}</h3>
                        </div>
                        <div class="p-3 bg-teal-50 rounded-xl text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-teal-500 h-1.5 rounded-full" style="width: 85%"></div>
                    </div>
                </div>

                {{-- Total Professors --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('សាស្ត្រាចារ្យសរុប') }}</p>
                            <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalProfessors }}</h3>
                        </div>
                        <div class="p-3 bg-orange-50 rounded-xl text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: 45%"></div>
                    </div>
                </div>

                {{-- Total Faculties --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('មហាវិទ្យាល័យសរុប') }}</p>
                            <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalFaculties }}</h3>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>

            {{-- Content Split --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                {{-- Quick Actions (Takes up 2/3 on large screens) --}}
                <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-bolt text-teal-500"></i>
                            {{ __('សកម្មភាពរហ័ស') }}
                        </h4>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <a href="{{ route('admin.create-user') }}" class="group flex items-center p-4 bg-gray-50 rounded-xl border border-transparent hover:border-blue-200 hover:bg-blue-50 transition-all duration-200">
                            <div class="h-12 w-12 rounded-lg bg-white flex items-center justify-center shadow-sm text-blue-600 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-plus text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="font-bold text-gray-800 group-hover:text-blue-700">{{ __('បន្ថែមអ្នកប្រើប្រាស់') }}</h5>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('បង្កើតគណនីថ្មី') }}</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-blue-400"></i>
                        </a>

                        <a href="{{ route('admin.create-faculty') }}" class="group flex items-center p-4 bg-gray-50 rounded-xl border border-transparent hover:border-green-200 hover:bg-green-50 transition-all duration-200">
                            <div class="h-12 w-12 rounded-lg bg-white flex items-center justify-center shadow-sm text-green-600 group-hover:scale-110 transition-transform">
                                <i class="fas fa-university text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="font-bold text-gray-800 group-hover:text-green-700">{{ __('បន្ថែមមហាវិទ្យាល័យ') }}</h5>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('បង្កើតស្ថាប័នថ្មី') }}</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-green-400"></i>
                        </a>

                        <a href="{{ route('admin.manage-users') }}" class="group flex items-center p-4 bg-gray-50 rounded-xl border border-transparent hover:border-purple-200 hover:bg-purple-50 transition-all duration-200">
                            <div class="h-12 w-12 rounded-lg bg-white flex items-center justify-center shadow-sm text-purple-600 group-hover:scale-110 transition-transform">
                                <i class="fas fa-users-cog text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="font-bold text-gray-800 group-hover:text-purple-700">{{ __('គ្រប់គ្រងអ្នកប្រើប្រាស់') }}</h5>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('កែប្រែ ឬលុបគណនី') }}</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-purple-400"></i>
                        </a>

                        <a href="{{ route('admin.manage-faculties') }}" class="group flex items-center p-4 bg-gray-50 rounded-xl border border-transparent hover:border-red-200 hover:bg-red-50 transition-all duration-200">
                            <div class="h-12 w-12 rounded-lg bg-white flex items-center justify-center shadow-sm text-red-600 group-hover:scale-110 transition-transform">
                                <i class="fas fa-building text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="font-bold text-gray-800 group-hover:text-red-700">{{ __('គ្រប់គ្រងមហាវិទ្យាល័យ') }}</h5>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('ទិន្នន័យស្ថាប័ន') }}</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-red-400"></i>
                        </a>

                    </div>
                </div>

                {{-- System Info (Takes up 1/3) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-50">
                        <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            {{ __('ព័ត៌មានប្រព័ន្ធ') }}
                        </h4>
                    </div>
                    <div class="p-6 flex-1">
                        <div class="space-y-5">
                            {{-- Item 1 --}}
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-sitemap text-xs"></i>
                                    </div>
                                    <span class="text-gray-600 font-medium">{{ __('ដេប៉ាតឺម៉ង់សរុប') }}</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $totalDepartments }}</span>
                            </div>
                            <hr class="border-gray-50">

                            {{-- Item 2 --}}
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                                        <i class="fas fa-cubes text-xs"></i>
                                    </div>
                                    <span class="text-gray-600 font-medium">{{ __('កម្មវិធីសិក្សាសរុប') }}</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $totalPrograms }}</span>
                            </div>
                            <hr class="border-gray-50">

                            {{-- Item 3 --}}
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                                        <i class="fas fa-book text-xs"></i>
                                    </div>
                                    <span class="text-gray-600 font-medium">{{ __('មុខវិជ្ជាសរុប') }}</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900 group-hover:text-amber-600 transition-colors">{{ $totalCourses }}</span>
                            </div>
                            <hr class="border-gray-50">

                            {{-- Item 4 --}}
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-layer-group text-xs"></i>
                                    </div>
                                    <span class="text-gray-600 font-medium">{{ __('វគ្គសិក្សាសរុប') }}</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $totalCourseOfferings }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>