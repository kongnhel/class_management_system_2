<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight flex items-center gap-2">
            📢 {{ __('ការជូនដំណឹង') }}
        </h2>
    </x-slot>

    {{-- បន្ថែម state សម្រាប់ Delete Modal ក្នុង x-data --}}
    <div class="py-12 bg-gray-50 min-h-screen"
         x-data="{ 
            showRecipientsModal: false, 
            recipients: [], 
            notificationTitle: '',
            showDeleteModal: false,
            deleteRoute: '',
            itemTitle: ''
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-6 lg:p-8 border border-gray-100">

                {{-- ✅ Success/Error Messages --}}
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

                {{-- ✅ Header & Button --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-2xl font-extrabold text-gray-800">
                        {{ __('បញ្ជីការជូនដំណឹងដែលបានផ្ញើ') }}
                    </h3>
                    <a href="{{ route('professor.notifications.create') }}"
                       class="inline-flex items-center w-full justify-center md:w-auto px-6 py-3 bg-green-600 text-white font-bold rounded-full shadow-lg hover:bg-green-700 transition duration-300 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i> {{ __('បង្កើតការជូនដំណឹងថ្មី') }}
                    </a>
                </div>

                @if ($sentNotifications->isEmpty())
                    <div class="text-center py-16 text-gray-500 bg-gray-50 rounded-2xl shadow-inner">
                        <i class="fas fa-bell-slash text-5xl text-gray-300"></i>
                        <p class="text-xl mt-4 font-semibold">{{ __('អ្នកមិនទាន់បានផ្ញើការជូនដំណឹងណាមួយនៅឡើយទេ។') }}</p>
                    </div>
                @else
                    {{-- 1. DESKTOP VIEW --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/4">{{ __('ចំណងជើង') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/3">{{ __('សារ') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/6">{{ __('អ្នកទទួល') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/6">{{ __('បានផ្ញើនៅ') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/12">{{ __('សកម្មភាព') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @php
                                        $allRecipients = \App\Models\User::whereIn('id', $sentNotifications->flatMap(fn($n) => $n->data['recipient_ids'] ?? []))->pluck('name', 'id');
                                    @endphp

                                    @foreach ($sentNotifications as $notification)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                                {{ \Illuminate\Support\Str::limit($notification->data['title'] ?? 'N/A', 40) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 60) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @php
                                                    $recipientIds = $notification->data['recipient_ids'] ?? [];
                                                    $recipientNames = collect($recipientIds)->map(fn($id) => $allRecipients[$id] ?? 'Unknown User')->all();
                                                @endphp
                                                <button @click="recipients = {{ json_encode($recipientNames) }}; notificationTitle = '{{ addslashes($notification->data['title'] ?? '') }}'; showRecipientsModal = true;" class="text-green-600 hover:underline font-semibold">
                                                    {{ count($recipientIds) }} {{ __('និស្សិត') }}
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $notification->created_at->locale('km')->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                <button type="button" 
                                                    @click="showDeleteModal = true; deleteRoute = '{{ route('professor.notifications.destroy', $notification->id) }}'; itemTitle = '{{ addslashes($notification->data['title'] ?? '') }}'"
                                                    class="text-red-600 hover:text-red-900 font-semibold transition duration-200">
                                                    {{ __('លុប') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 2. MOBILE VIEW --}}
                    <div class="md:hidden space-y-4">
                        @foreach ($sentNotifications as $notification)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-4 transition duration-300 hover:shadow-xl">
                                <div class="border-b pb-3 mb-3">
                                    <h4 class="text-lg font-extrabold text-green-700 mb-1">{{ $notification->data['title'] ?? 'N/A' }}</h4>
                                    <p class="text-xs text-gray-500">{{ __('បានផ្ញើ:') }} {{ $notification->created_at->locale('km')->diffForHumans() }}</p>
                                </div>
                                <div class="space-y-3 text-sm">
                                    <p class="text-gray-600">{{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 100) }}</p>
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <p class="font-semibold text-gray-700">{{ __('អ្នកទទួល') }}:</p>
                                        <button @click="recipients = {{ json_encode($recipientNames) }}; notificationTitle = '{{ addslashes($notification->data['title'] ?? '') }}'; showRecipientsModal = true;" class="text-sm text-green-600 font-bold">
                                            {{ count($notification->data['recipient_ids'] ?? []) }} {{ __('និស្សិត') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 text-right">
                                    <button type="button" 
                                        @click="showDeleteModal = true; deleteRoute = '{{ route('professor.notifications.destroy', $notification->id) }}'; itemTitle = '{{ addslashes($notification->data['title'] ?? '') }}'"
                                        class="px-3 py-1 text-xs bg-red-100 text-red-600 font-bold rounded-lg">
                                        <i class="fas fa-trash-alt mr-1"></i> {{ __('លុបការជូនដំណឹង') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ✅ Modal បង្ហាញអ្នកទទួល (Existing) --}}
        <div x-show="showRecipientsModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click="showRecipientsModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-3 mb-4" x-text="'{{ __('អ្នកទទួលសម្រាប់:') }} ' + notificationTitle"></h3>
                    <ul class="space-y-2 max-h-80 overflow-y-auto p-2">
                        <template x-for="recipient in recipients" :key="recipient">
                            <li class="bg-green-50 p-3 rounded-md text-gray-800 flex items-center">
                                <i class="fas fa-user-circle mr-3 text-green-400"></i>
                                <span x-text="recipient"></span>
                            </li>
                        </template>
                    </ul>
                    <div class="mt-6 text-right">
                        <button @click="showRecipientsModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">{{ __('បិទ') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔴 NEW: Delete Confirmation Modal --}}
        <div x-show="showDeleteModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen p-4 text-center">
                {{-- Backdrop --}}
                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>

                {{-- Modal Content --}}
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full p-8">
                    
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('បញ្ជាក់ការលុប') }}</h3>
                    <p class="text-gray-600 mb-1" x-text="'{{ __('តើអ្នកពិតជាចង់លុបការជូនដំណឹង') }} &quot;' + itemTitle + '&quot; ' + '{{ __('មែនទេ?') }}'"></p>
                    <p class="text-sm text-red-500 font-medium bg-red-50 p-3 rounded-lg mt-4">
                        <i class="fas fa-info-circle mr-1"></i> {{ __('សកម្មភាពនេះនឹងលុបការជូនដំណឹងចេញពីសិស្សទាំងអស់ដែលបានទទួល ហើយមិនអាចត្រឡប់ក្រោយវិញបានទេ។') }}
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <button @click="showDeleteModal = false" type="button" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                            {{ __('បោះបង់') }}
                        </button>
                        
                        {{-- Form សម្រាប់លុប --}}
                        <form :action="deleteRoute" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition">
                                {{ __('លុបចោល') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>