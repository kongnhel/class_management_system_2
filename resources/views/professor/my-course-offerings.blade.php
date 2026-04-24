<x-app-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700;900&family=DM+Sans:wght@400;500;600;700&display=swap');

    .page-wrap { font-family: 'DM Sans', sans-serif; }
    .khmer { font-family: 'Hanuman', serif; }

    .card-course {
        background: #fff;
        border: 1px solid #e8f5e9;
        border-radius: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .card-course:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(34,197,94,0.12);
        border-color: #86efac;
    }

    .badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 999px;
        border: 1px solid #bbf7d0;
    }

    .btn-manage {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13px 20px;
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(22,163,74,0.25);
        letter-spacing: 0.02em;
    }
    .btn-manage:hover {
        background: linear-gradient(135deg, #15803d 0%, #166534 100%);
        box-shadow: 0 6px 20px rgba(22,163,74,0.35);
        transform: translateY(-1px);
    }

    .modal-overlay {
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(6px);
    }

    .modal-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,0.18);
        max-width: 420px;
        width: 100%;
    }

    .modal-menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 14px;
        color: #374151;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
        border: 1px solid transparent;
    }
    .modal-menu-item:hover {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }
    .modal-menu-item svg {
        flex-shrink: 0;
        color: #9ca3af;
        transition: color 0.15s;
    }
    .modal-menu-item:hover svg { color: #16a34a; }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #f0fdf4;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #16a34a;
        transition: background 0.2s, color 0.2s;
    }
    .card-course:hover .icon-circle {
        background: #16a34a;
        color: #fff;
    }

    .empty-state {
        background: #f9fafb;
        border: 2px dashed #d1d5db;
        border-radius: 20px;
        padding: 64px 32px;
        text-align: center;
    }

    .flash-success {
        display: flex; align-items: center; gap: 12px;
        background: #f0fdf4; border-left: 4px solid #22c55e;
        color: #166534; padding: 14px 18px;
        border-radius: 12px; font-weight: 500; font-size: 0.9rem;
    }
    .flash-error {
        display: flex; align-items: center; gap: 12px;
        background: #fef2f2; border-left: 4px solid #ef4444;
        color: #991b1b; padding: 14px 18px;
        border-radius: 12px; font-weight: 500; font-size: 0.9rem;
    }

    [x-cloak] { display: none !important; }
</style>

<div class="page-wrap" style="min-height:100vh; background: linear-gradient(160deg, #f0fdf4 0%, #f8fafc 60%); padding: 40px 0 60px;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">

        {{-- ===== Header ===== --}}
        <div style="margin-bottom: 32px;">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom: 6px;">
                <div style="width:5px; height:36px; background: linear-gradient(180deg,#16a34a,#4ade80); border-radius:4px;"></div>
                <h1 class="khmer" style="font-size:2rem; font-weight:900; color:#111827; margin:0; line-height:1.2;">
                    {{ __('មុខវិជ្ជាខ្ញុំបង្រៀន') }}
                </h1>
            </div>
            <p class="khmer" style="color:#6b7280; font-size:0.95rem; margin: 0 0 0 19px; padding-left:14px;">
                {{ __('បញ្ជីវគ្គសិក្សាទាំងអស់ដែលអ្នកកំពុងបង្រៀន') }}
            </p>
        </div>

        {{-- ===== Flash Messages ===== --}}
        @if (session('success'))
            <div class="flash-success" style="margin-bottom: 20px;">
                <svg style="width:20px;height:20px;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="khmer">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flash-error" style="margin-bottom: 20px;">
                <svg style="width:20px;height:20px;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="khmer">{{ session('error') }}</span>
            </div>
        @endif

        {{-- ===== Content ===== --}}
        @if ($courseOfferings->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:56px;height:56px;color:#d1d5db;margin:0 auto 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5a4 4 0 00-4 4v8a4 4 0 004 4c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5a4 4 0 014 4v8a4 4 0 01-4 4c-1.492 0-2.832-.462-4-1.253"/>
                    <line stroke-linecap="round" x1="12" y1="6.253" x2="12" y2="19.253"/>
                </svg>
                <p class="khmer" style="font-size:1.2rem;font-weight:700;color:#374151;margin:0 0 6px;">{{ __('មិនទាន់មានមុខវិជ្ជាត្រូវបានចាត់តាំង') }}</p>
                <p class="khmer" style="font-size:0.9rem;color:#9ca3af;margin:0;">{{ __('សូមទាក់ទងរដ្ឋបាល ប្រសិនបើមានចម្ងល់។') }}</p>
            </div>

        @else
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                @foreach ($courseOfferings as $offering)
                    <div class="card-course" style="padding: 24px; display:flex; flex-direction:column; gap: 20px;">

                        {{-- Top row: icon + badges --}}
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                            <div class="icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5a4 4 0 00-4 4v8a4 4 0 004 4c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5a4 4 0 014 4v8a4 4 0 01-4 4c-1.492 0-2.832-.462-4-1.253"/>
                                    <line stroke-linecap="round" x1="12" y1="6.253" x2="12" y2="19.253"/>
                                </svg>
                            </div>
                            <div style="display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-end;">
                                <span class="badge-tag khmer">
                                    <svg style="width:11px;height:11px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                                    {{ $offering->academic_year }}
                                </span>
                                <span class="badge-tag khmer">
                                    <svg style="width:11px;height:11px;" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm1 11H9V9h2v4zm0-6H9V5h2v2z"/></svg>
                                    {{ __('ឆមាស') }} {{ $offering->semester }}
                                </span>
                            </div>
                        </div>

                        {{-- Course Title --}}
                        <div style="flex:1;">
                            <h3 class="khmer" style="font-size:1.15rem; font-weight:700; color:#111827; margin:0 0 4px; line-height:1.4;">
                                {{ $offering->course->title_km ?? 'N/A' }}
                            </h3>
                            <p style="font-size:0.82rem; color:#9ca3af; font-style:italic; margin:0;">
                                {{ $offering->course->title_en ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Action Button --}}
                        <button
                            x-data="{}"
                            x-on:click="$dispatch('open-course-management-modal', { courseOfferingId: {{ $offering->id }} })"
                            class="btn-manage khmer">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:17px;height:17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.608 3.292 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ __('គ្រប់គ្រងវគ្គសិក្សា') }}
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div style="margin-top: 36px;">
                {{ $courseOfferings->links('pagination::tailwind') }}
            </div>
        @endif

    </div>
</div>


{{-- ===== MODAL ===== --}}
<div x-data="{ open: false, courseOfferingId: null }"
     x-on:open-course-management-modal.window="open = true; courseOfferingId = $event.detail.courseOfferingId"
     x-show="open"
     x-cloak
     style="position:fixed; inset:0; z-index:50; display:flex; align-items:center; justify-content:center; padding:16px;">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="modal-overlay"
         style="position:fixed; inset:0;"
         @click="open = false"></div>

    {{-- Modal Box --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-250" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="modal-card"
         style="position:relative;">

        {{-- Modal Header --}}
        <div style="padding: 24px 24px 20px; border-bottom: 1px solid #f3f4f6;">
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:40px;height:40px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.462 9.492 5 8 5a4 4 0 00-4 4v8a4 4 0 004 4c1.492 0 2.832-.462 4-1.253m0-13C13.168 5.462 14.508 5 16 5a4 4 0 014 4v8a4 4 0 01-4 4c-1.492 0-2.832-.462-4-1.253"/>
                            <line stroke-linecap="round" x1="12" y1="6.253" x2="12" y2="19.253"/>
                        </svg>
                    </div>
                    <h3 class="khmer" style="font-size:1.1rem;font-weight:800;color:#111827;margin:0;">
                        {{ __('គ្រប់គ្រងវគ្គសិក្សា') }}
                    </h3>
                </div>
                <button @click="open = false"
                        style="width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#6b7280;transition:background 0.15s;"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Modal Menu --}}
        <div style="padding: 16px 20px;">
            @php
                $menuItems = [
                    [
                        'route' => 'professor.students.in-course-offering',
                        'label' => 'មើលនិស្សិត',
                        'desc'  => 'View enrolled students',
                        'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'color' => '#2563eb',
                        'bg'    => '#eff6ff',
                    ],
                    [
                        'route' => 'professor.manage-grades',
                        'label' => 'គ្រប់គ្រងពិន្ទុ',
                        'desc'  => 'Manage student grades',
                        'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'color' => '#d97706',
                        'bg'    => '#fffbeb',
                    ],
                ];
            @endphp

            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach ($menuItems as $item)
                    <a :href="courseOfferingId ? '{{ route($item['route'], ['offering_id' => ':id']) }}'.replace(':id', courseOfferingId) : '#'"
                       class="modal-menu-item">
                        <div style="width:40px;height:40px;border-radius:11px;background:{{ $item['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:19px;height:19px;color:{{ $item['color'] }};" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="khmer" style="font-size:0.95rem;font-weight:700;color:#111827;line-height:1.3;">{{ __($item['label']) }}</div>
                            <div style="font-size:0.78rem;color:#9ca3af;">{{ $item['desc'] }}</div>
                        </div>
                        <svg style="width:15px;height:15px;color:#d1d5db;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="padding: 14px 20px 20px;">
            <button @click="open = false"
                    class="khmer"
                    style="width:100%;padding:11px;border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;color:#6b7280;font-weight:600;font-size:0.9rem;cursor:pointer;transition:background 0.15s;"
                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                {{ __('បិទ') }}
            </button>
        </div>
    </div>
</div>

</x-app-layout>