<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        ស្រង់វត្តមាននិស្សិត
                    </h1>
                    <p class="text-slate-500 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.993 7.993 0 002 12a7.993 7.993 0 007 7.196V4.804z"></path>
                        </svg>
                        {{ $courseOffering->course->name_km ?? 'មុខវិជ្ជា' }}
                    </p>
                </div>
                
                <div class="bg-white p-2 rounded-xl shadow-sm border border-slate-200 inline-flex items-center">
                    <span class="px-3 text-sm font-semibold text-slate-600">ថ្ងៃទី:</span>
                    <input type="date" 
                           form="attendanceForm"
                           name="attendance_date" 
                           value="{{ $today }}" 
                           class="border-none focus:ring-0 text-slate-900 font-medium bg-transparent">
                </div>
            </div>

            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
                <form id="attendanceForm" action="{{ route('professor.attendance.store', $courseOffering->id) }}" method="POST">
                    @csrf
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">ឈ្មោះនិស្សិត</th>
                                    <th class="px-8 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">ស្ថានភាពវត្តមាន</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($students as $student)
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold group-hover:bg-green-100 group-hover:text-green-600 transition-colors">
                                                    {{ mb_substr($student->studentProfile->full_name_km ?? $student->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-slate-900">
                                                        {{ $student->studentProfile->full_name_km ?? $student->name }}
                                                    </div>
                                                    <div class="text-xs text-slate-400 font-mono uppercase">ID: {{ $student->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex justify-center items-center gap-2">
                                                <label class="relative flex flex-col items-center cursor-pointer group/radio">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" checked class="peer sr-only">
                                                    <span class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all hover:bg-slate-50">
                                                        មក
                                                    </span>
                                                </label>

                                                <label class="relative flex flex-col items-center cursor-pointer group/radio">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="permission" class="peer sr-only">
                                                    <span class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 transition-all hover:bg-slate-50">
                                                        ច្បាប់
                                                    </span>
                                                </label>

                                                <label class="relative flex flex-col items-center cursor-pointer group/radio">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="peer sr-only">
                                                    <span class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all hover:bg-slate-50">
                                                        អវត្តមាន
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-slate-50 px-8 py-6 border-t border-slate-200 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-slate-900 border border-transparent rounded-xl font-bold text-white uppercase tracking-widest hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all shadow-lg shadow-slate-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            រក្សាទុកវត្តមាន
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>