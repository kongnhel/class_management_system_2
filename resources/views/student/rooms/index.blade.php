<x-app-layout>
    <x-slot name="header">
        <div class="px-4 md:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold text-gray-900 leading-tight flex items-center">
                {{ __('ព័ត៌មានបន្ទប់') }} <i class="fas fa-university text-3xl text-green-600 ml-4"></i>
            </h2>
            <p class="mt-2 text-lg text-gray-500">{{ __('ស្វែងរកព័ត៌មានអំពីបន្ទប់សិក្សា') }}</p>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12 border border-gray-200">

                <div class="flex flex-col md:flex-row items-center justify-between mb-8 space-y-4 md:space-y-0">
                    <h3 class="text-3xl font-bold text-gray-800">{{ __('បញ្ជីបន្ទប់ទាំងអស់') }}</h3>
                    <div class="relative w-full md:w-1/3">
                        <input type="text" id="searchInput" placeholder="{{ __('ស្វែងរកបន្ទប់...') }}" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent shadow-sm transition duration-200 text-base placeholder-gray-400">
                        <svg class="w-6 h-6 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                @if ($rooms->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-gray-600 bg-gray-100 rounded-2xl shadow-inner">
                        <svg class="w-24 h-24 text-green-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3m2 2V3m-5 8h14m-7 8a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-2xl font-semibold text-gray-800">{{ __('មិនមានបន្ទប់ណាមួយត្រូវបានរកឃើញទេ') }}</p>
                        <p class="mt-2 text-base text-gray-500">{{ __('សូមព្យាយាមស្វែងរកនៅពេលក្រោយ ឬពិនិត្យមើលការបញ្ចូលរបស់អ្នក។') }}</p>
                    </div>
                @else
                    <div id="roomList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($rooms as $room)
                            <div class="room-card bg-white rounded-2xl shadow-lg border border-gray-200 p-6 flex flex-col transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                                <h4 class="text-2xl font-extrabold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-door-open text-green-600 mr-3 text-2xl"></i>
                                    <span class="room-number-text">{{ __('បន្ទប់') }} {{ $room->room_number }}</span>
                                </h4>
                                <div class="space-y-4 text-gray-700 text-sm">
                                    <p class="flex items-start">
                                        <i class="fas fa-users w-5 h-5 mt-1 mr-3 text-green-500 flex-shrink-0"></i>
                                        <span class="font-bold">{{ __('សមត្ថភាព') }}:</span><span class="flex-1 ml-2 text-gray-600">{{ $room->capacity }} {{ __('នាក់') }}</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-wifi w-5 h-5 mt-1 mr-3 text-green-500 flex-shrink-0"></i>
                                        <span class="font-bold">{{ __('ឈ្មោះ Wifi') }}:</span><span class="flex-1 ml-2 text-gray-600">{{ $room->wifi_name ?? '-' }}</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-lock w-5 h-5 mt-1 mr-3 text-green-500 flex-shrink-0"></i>
                                        <span class="font-bold">{{ __('ពាក្យសម្ងាត់ Wifi') }}:</span><span class="flex-1 ml-2 text-gray-600">{{ $room->wifi_password ?? '-' }}</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-map-marker-alt w-5 h-5 mt-1 mr-3 text-green-500 flex-shrink-0"></i>
                                        <span class="font-bold">{{ __('ទីតាំង') }}:</span><span class="flex-1 ml-2 text-gray-600">{{ $room->location_of_room ?? '-' }}</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-clipboard w-5 h-5 mt-1 mr-3 text-green-500 flex-shrink-0"></i>
                                        <span class="font-bold">{{ __('ប្រភេទ') }}:</span><span class="flex-1 ml-2 text-gray-600">{{ $room->type_of_room ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const roomList = document.getElementById('roomList');

        if (!roomList) return;

        const roomCards = Array.from(roomList.querySelectorAll('.room-card'));

        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase().trim();
            let found = false;

            roomCards.forEach(card => {
                const roomNumber = card.querySelector('.room-number-text').textContent.toLowerCase();
                const cardText = card.textContent.toLowerCase();

                if (roomNumber.includes(query) || cardText.includes(query)) {
                    card.style.display = 'flex';
                    found = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // You could add a "not found" message here if needed
            const noResultsMessage = document.getElementById('noResultsMessage');
            if (noResultsMessage) {
                noResultsMessage.remove();
            }

            if (!found && roomCards.length > 0) {
                const messageDiv = document.createElement('div');
                messageDiv.id = 'noResultsMessage';
                messageDiv.className = 'flex flex-col items-center justify-center py-20 text-gray-600 bg-gray-100 rounded-2xl shadow-inner';
                messageDiv.innerHTML = `
                    <svg class="w-24 h-24 text-green-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-2xl font-semibold text-gray-800">{{ __('មិនមានបន្ទប់ណាមួយត្រូវបានរកឃើញទេ') }}</p>
                    <p class="mt-2 text-base text-gray-500">{{ __('សូមព្យាយាមស្វែងរកពាក្យផ្សេងទៀត') }}</p>
                `;
                roomList.parentNode.appendChild(messageDiv);
            }
        });
    });
</script>