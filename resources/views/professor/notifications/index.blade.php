<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight flex items-center gap-2">
            📢 {{ __('ការជូនដំណឹង') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen"
         x-data="{ showRecipientsModal: false, recipients: [], notificationTitle: '' }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl p-6 lg:p-8 border border-gray-100">

                {{-- ✅ Success Message --}}
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

                {{-- ✅ Empty State --}}
                @if ($sentNotifications->isEmpty())
                    <div class="text-center py-16 text-gray-500 bg-gray-50 rounded-2xl shadow-inner">
                        <i class="fas fa-bell-slash text-5xl text-gray-300"></i>
                        <p class="text-xl mt-4 font-semibold">{{ __('អ្នកមិនទាន់បានផ្ញើការជូនដំណឹងណាមួយនៅឡើយទេ។') }}</p>
                    </div>
                @else
                    {{-- ********************************************************** --}}
                    {{-- ** 1. DESKTOP VIEW: Traditional Table ** --}}
                    {{-- ********************************************************** --}}
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
                                        // ប្រមូលឈ្មោះអ្នកទទួលទាំងអស់ម្តង ដើម្បីកាត់បន្ថយការ Query ក្នុង Loop
                                        $allRecipients = \App\Models\User::whereIn(
                                            'id',
                                            $sentNotifications->flatMap(fn($n) => $n->data['recipient_ids'] ?? [])
                                        )->pluck('name', 'id');
                                    @endphp

                                    @foreach ($sentNotifications as $notification)
                                        <tr class="hover:bg-gray-50">
                                            {{-- Title --}}
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                                {{ \Illuminate\Support\Str::limit($notification->data['title'] ?? 'N/A', 40) }}
                                            </td>

                                            {{-- Message --}}
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 60) }}
                                            </td>

                                            {{-- Recipients --}}
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @php
                                                    $recipientIds = $notification->data['recipient_ids'] ?? [];
                                                    $recipientNames = collect($recipientIds)
                                                        ->map(fn($id) => $allRecipients[$id] ?? 'Unknown User')
                                                        ->all();
                                                @endphp

                                                <button 
                                                    @click="
                                                        recipients = {{ json_encode($recipientNames) }};
                                                        notificationTitle = '{{ addslashes($notification->data['title'] ?? '') }}';
                                                        showRecipientsModal = true;
                                                    "
                                                    class="text-green-600 hover:underline font-semibold">
                                                    {{ count($recipientIds) }} {{ __('និស្សិត') }}
                                                </button>
                                            </td>

                                            {{-- Sent Date --}}
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $notification->created_at->locale('km')->diffForHumans() }}
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                                <form action="{{ route('professor.notifications.destroy', $notification->id) }}"
                                                      method="POST"
                                                      class="inline-block"
                                                      onsubmit="return confirm('{{ __('តើអ្នកពិតជាចង់លុបការជូនដំណឹងនេះមែនទេ? សកម្មភាពនេះនឹងលុបការជូនដំណឹងចេញពីសិស្សទាំងអស់ដែលបានទទួល។') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold transition duration-200">
                                                        {{ __('លុប') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ********************************************************** --}}
                    {{-- ** 2. MOBILE VIEW: Card List ** --}}
                    {{-- ********************************************************** --}}
                    <div class="md:hidden space-y-4">
                        @foreach ($sentNotifications as $notification)
                            @php
                                $recipientIds = $notification->data['recipient_ids'] ?? [];
                                $recipientNames = collect($recipientIds)
                                    ->map(fn($id) => $allRecipients[$id] ?? 'Unknown User')
                                    ->all();
                            @endphp

                            <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-4 transition duration-300 hover:shadow-xl">
                                
                                {{-- Card Header: Title & Date --}}
                                <div class="border-b pb-3 mb-3">
                                    <h4 class="text-lg font-extrabold text-green-700 mb-1">
                                        {{ $notification->data['title'] ?? 'N/A' }}
                                    </h4>
                                    <p class="text-xs text-gray-500">{{ __('បានផ្ញើ:') }} {{ $notification->created_at->locale('km')->diffForHumans() }}</p>
                                </div>
                                
                                {{-- Card Body: Message & Recipients --}}
                                <div class="space-y-3 text-sm">
                                    {{-- Message --}}
                                    <div>
                                        <p class="font-semibold text-gray-700">{{ __('សារ:') }}</p>
                                        <p class="text-gray-600 mt-1 pl-2 border-l-2 border-green-200">
                                            {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 100) }}
                                        </p>
                                    </div>
                                    
                                    {{-- Recipients --}}
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <p class="font-semibold text-gray-700">{{ __('អ្នកទទួល') }}:</p>
                                        <button 
                                            @click="
                                                recipients = {{ json_encode($recipientNames) }};
                                                notificationTitle = '{{ addslashes($notification->data['title'] ?? '') }}';
                                                showRecipientsModal = true;
                                            "
                                            class="text-sm text-green-600 hover:underline font-bold">
                                            {{ count($recipientIds) }} {{ __('និស្សិត') }} <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Card Footer: Action --}}
                                <div class="mt-4 pt-4 border-t border-gray-100 text-right">
                                    <form action="{{ route('professor.notifications.destroy', $notification->id) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('{{ __('តើអ្នកពិតជាចង់លុបការជូនដំណឹងនេះមែនទេ? សកម្មភាពនេះនឹងលុបការជូនដំណឹងចេញពីសិស្សទាំងអស់ដែលបានទទួល។') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-600 font-bold rounded-lg hover:bg-red-200 transition duration-200">
                                            <i class="fas fa-trash-alt mr-1"></i> {{ __('លុបការជូនដំណឹង') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ✅ Recipients Modal (រក្សាទុក Modal សម្រាប់បង្ហាញអ្នកទទួល) --}}
        <div x-show="showRecipientsModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4">

                {{-- Backdrop --}}
                <div x-show="showRecipientsModal"
                     @click.away="showRecipientsModal = false"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                </div>

                {{-- Modal --}}
                <div x-show="showRecipientsModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6">

                    <h3 class="text-xl font-bold text-gray-900 border-b pb-3 mb-4 flex items-center justify-between">
                        <div>
                            <i class="fas fa-users mr-2 text-green-500"></i>
                            {{ __('អ្នកទទួលសម្រាប់:') }}
                        </div>
                        <span x-text="notificationTitle" class="text-green-600 text-base font-semibold"></span>
                    </h3>

                    <ul class="space-y-2 max-h-80 overflow-y-auto p-2 -m-2">
                        <template x-for="recipient in recipients" :key="recipient">
                            <li class="bg-green-50 p-3 rounded-md text-gray-800 flex items-center">
                                <i class="fas fa-user-circle mr-3 text-green-400"></i>
                                <span x-text="recipient"></span>
                            </li>
                        </template>
                    </ul>

                    <div class="mt-6 text-right">
                        <button @click="showRecipientsModal = false"
                                class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold transition duration-200">
                            {{ __('បិទ') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>