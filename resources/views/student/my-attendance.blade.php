<x-app-layout>
    {{-- Background ពណ៌ស្រទន់ បែប Modern --}}
    <div class="py-12 bg-[#fcfdfe] min-h-screen transition-colors duration-300 font-['Battambang']">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                // បង្កើត Palette ពណ៌សម្រាប់មុខវិជ្ជា
                $colorPalette = [
                    'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-100'],
                    'indigo'  => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-600',  'border' => 'border-indigo-100'],
                    'purple'  => ['bg' => 'bg-purple-50',  'text' => 'text-purple-600',  'border' => 'border-purple-100'],
                    'rose'    => ['bg' => 'bg-rose-50',    'text' => 'text-rose-600',    'border' => 'border-rose-100'],
                    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100'],
                    'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'border' => 'border-amber-100'],
                ];
                $colorKeys = array_keys($colorPalette);
            @endphp

            {{-- Main Card --}}
            <div class="bg-white shadow-xl shadow-gray-100/50 border border-gray-100 rounded-[32px] overflow-hidden border-t-8 border-green-600">
                
                {{-- Header Section --}}
                <div class="p-8 lg:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-50">
                    <div class="flex items-center gap-5">
                        <div class="p-4 bg-green-50 rounded-2xl shadow-sm">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                                {{ __('កំណត់ត្រាវត្តមានសិក្សា') }}
                            </h2>
                            <p class="text-sm text-gray-400 mt-1 font-medium">
                                {{ __('និស្សិត៖') }} <span class="text-green-600 font-black">{{ Auth::user()->name }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <button onclick="window.print()" class="bg-green-600 text-white px-8 py-3 rounded-2xl hover:bg-green-700 hover:-translate-y-1 transition-all duration-300 flex items-center shadow-lg shadow-green-100 text-sm font-black no-print">
                        <i class="fas fa-print mr-2 text-lg"></i>
                        {{ __('បោះពុម្ពរបាយការណ៍') }}
                    </button>
                </div>

                <div class="p-8 lg:p-10">
                    {{-- 1. DESKTOP VERSION --}}
                    <div class="hidden md:block overflow-hidden rounded-[24px] border border-gray-100 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('មុខវិជ្ជា') }}</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('កាលបរិច្ឆេទ') }}</th>
                                    <th class="px-8 py-5 text-center text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('ស្ថានភាព') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse ($attendances as $attendance)
                                    @php
                                        $courseName = $attendance->courseOffering->course->title_en ?? 'Unknown';
                                        $colorIndex = abs(crc32($courseName)) % count($colorKeys);
                                        $ui = $colorPalette[$colorKeys[$colorIndex]];

                                        $status_km = match(strtolower($attendance->status)) {
                                            'present', 'មាន' => 'មកសិក្សា',
                                            'absent', 'អវត្តមាន' => 'អវត្តមាន',
                                            'permission', 'ច្បាប់' => 'ច្បាប់',
                                            'late', 'យឺត' => 'យឺតយ៉ាវ',
                                            default => $attendance->status
                                        };

                                        $statusClass = match(strtolower($attendance->status)) {
                                            'present', 'មាន' => 'bg-green-50 text-green-700 border-green-100',
                                            'absent', 'អវត្តមាន' => 'bg-rose-50 text-rose-700 border-rose-100',
                                            'permission', 'ច្បាប់' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            default => 'bg-gray-50 text-gray-600 border-gray-100'
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="{{ $ui['bg'] }} {{ $ui['text'] }} w-9 h-9 rounded-lg flex items-center justify-center font-black border {{ $ui['border'] }} text-xs">
                                                    {{ substr($courseName, 0, 1) }}
                                                </div>
                                                <div class="text-sm font-black text-gray-800 group-hover:{{ $ui['text'] }} transition-colors">
                                                    {{ $courseName }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-500 font-bold tabular-nums">
                                            {{ $attendance->date ? $attendance->date->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-center">
                                            <span class="px-4 py-1.5 rounded-xl border text-[10px] font-black uppercase tracking-wider {{ $statusClass }}">
                                                {{ $status_km }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-20 text-center">
                                            <i class="fas fa-clipboard-list text-5xl text-gray-100 mb-4 block"></i>
                                            <span class="text-gray-400 font-medium italic">{{ __('មិនទាន់មានកំណត់ត្រាវត្តមាននៅឡើយទេ។') }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 2. MOBILE VERSION --}}
                    <div class="md:hidden space-y-5">
                        @foreach ($attendances as $attendance)
                            @php
                                $courseName = $attendance->courseOffering->course->title_en ?? 'Unknown';
                                $colorIndex = abs(crc32($courseName)) % count($colorKeys);
                                $ui = $colorPalette[$colorKeys[$colorIndex]];
                                
                                $status_km = match(strtolower($attendance->status)) {
                                    'present', 'មាន' => 'មកសិក្សា',
                                    'absent', 'អវត្តមាន' => 'អវត្តមាន',
                                    'permission', 'ច្បាប់' => 'ច្បាប់',
                                    'late', 'យឺត' => 'យឺតយ៉ាវ',
                                    default => $attendance->status
                                };

                                $statusClass = match(strtolower($attendance->status)) {
                                    'present', 'មាន' => 'bg-green-50 text-green-700 border-green-100',
                                    'absent', 'អវត្តមាន' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'permission', 'ច្បាប់' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    default => 'bg-gray-50 text-gray-600 border-gray-100'
                                };
                            @endphp
                            <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-xl shadow-gray-100/50 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1.5 h-full {{ str_replace('text-', 'bg-', explode(' ', $statusClass)[1] ?? 'bg-gray-300') }}"></div>
                                
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3 w-2/3">
                                        <div class="{{ $ui['bg'] }} {{ $ui['text'] }} w-8 h-8 rounded-lg flex items-center justify-center font-black border {{ $ui['border'] }} text-xs flex-shrink-0">
                                            {{ substr($courseName, 0, 1) }}
                                        </div>
                                        <h4 class="text-gray-900 font-black leading-tight text-sm">
                                            {{ $courseName }}
                                        </h4>
                                    </div>
                                    <span class="px-3 py-1 rounded-lg border text-[9px] font-black uppercase tracking-wider {{ $statusClass }}">
                                        {{ $status_km }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-xs pt-4 border-t border-gray-50">
                                    <span class="text-gray-400 font-bold uppercase tracking-tighter">{{ __('កាលបរិច្ឆេទ') }}</span>
                                    <span class="font-black text-gray-700">
                                        {{ $attendance->date ? $attendance->date->format('d-M-Y') : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Section --}}
                    @if ($attendances->hasPages())
                        <div class="mt-10 flex justify-center md:justify-end">
                            <div class="p-2 bg-gray-50 rounded-2xl border border-gray-100">
                                {{ $attendances->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .shadow-xl, .shadow-sm { box-shadow: none !important; }
            .rounded-\[32px\] { border-radius: 0 !important; }
            .border-t-8 { border-top-width: 2px !important; }
        }
    </style>
</x-app-layout>