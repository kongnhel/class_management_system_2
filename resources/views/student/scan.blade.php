<x-app-layout>
    {{-- Custom CSS: លាក់ប៊ូតុង និងផ្ទាំងរញ៉េរញ៉ៃរបស់ Library ចោលទាំងអស់ --}}
    <style>
        /* លាក់ Border និង Background ដើម */
        #reader { border: none !important; padding: 0 !important; }
        
        /* លាក់ប៊ូតុង Stop/Start របស់ Library (យើងនឹងប្រើប៊ូតុងខ្លួនឯងបើចាំបាច់) */
        #reader__dashboard_section_csr span, 
        #reader__dashboard_section_swaplink,
        #reader__dashboard_section_csr input { display: none !important; }

        /* លាក់ Error Message ពណ៌ក្រហមរបស់ Library */
        #reader__header_message { display: none !important; }

        /* ធ្វើឱ្យវីដេអូពេញគែមស្អាត */
        video { object-fit: cover; border-radius: 1.5rem; width: 100% !important; height: 100% !important; }
    </style>

    <div class="min-h-screen bg-gray-50 flex flex-col items-center pt-6 px-4">
        
        {{-- Header: ប៊ូតុងត្រឡប់ក្រោយ --}}
        <div class="w-full max-w-md flex justify-between items-center mb-6">
            <a href="{{ route('student.dashboard') }}" class="p-3 rounded-full bg-white shadow-sm text-gray-500 hover:text-gray-900 transition-all active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800 tracking-wide">{{ __('QR Attendance') }}</h1>
            <div class="w-10"></div> {{-- Spacer --}}
        </div>

        {{-- Main Scanner Card --}}
        <div class="w-full max-w-md relative">
            <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden relative p-4">
                
                {{-- Decorative Text --}}
                <div class="text-center mb-4 mt-2">
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">{{ __('SCANNING...') }}</p>
                </div>

                {{-- Camera Viewfinder --}}
                <div class="relative rounded-2xl overflow-hidden bg-black aspect-square shadow-inner isolate">
                    
                    {{-- កន្លែងបង្ហាញកាមេរ៉ា --}}
                    <div id="reader" class="w-full h-full absolute inset-0"></div>

                    {{-- Custom Overlay: គែមទាំង ៤ (UI តែប៉ុណ្ណោះ) --}}
                    <div class="absolute inset-0 pointer-events-none p-10 flex flex-col justify-between z-10">
                        <div class="flex justify-between">
                            <div class="w-12 h-12 border-t-4 border-l-4 border-white/80 rounded-tl-3xl drop-shadow-md"></div>
                            <div class="w-12 h-12 border-t-4 border-r-4 border-white/80 rounded-tr-3xl drop-shadow-md"></div>
                        </div>
                        <div class="flex justify-between">
                            <div class="w-12 h-12 border-b-4 border-l-4 border-white/80 rounded-bl-3xl drop-shadow-md"></div>
                            <div class="w-12 h-12 border-b-4 border-r-4 border-white/80 rounded-br-3xl drop-shadow-md"></div>
                        </div>
                    </div>

                    {{-- Laser Animation --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-blue-500/80 shadow-[0_0_20px_rgba(59,130,246,1)] animate-scan pointer-events-none z-20"></div>
                </div>

                <div class="mt-6 text-center pb-2">
                    <p class="text-xs text-gray-300">{{ __('Powered by University System') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Success / Error Popup --}}
    <div id="result-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-md transition-opacity duration-300 px-4">
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center transform transition-all scale-100 shadow-2xl relative overflow-hidden">
            
            {{-- Background decorative circle --}}
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-gray-50 rounded-full blur-2xl z-0"></div>
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-gray-50 rounded-full blur-2xl z-0"></div>

            <div class="relative z-10">
                {{-- Icon --}}
                <div id="status-icon-container" class="mx-auto w-24 h-24 rounded-full flex items-center justify-center mb-6 shadow-sm">
                    {{-- Icons injected by JS --}}
                </div>
                
                <h3 id="modal-title" class="text-2xl font-black text-gray-900 mb-2 tracking-tight"></h3>
                <p id="modal-message" class="text-gray-500 mb-8 font-medium leading-relaxed"></p>
                
                <button onclick="resetScanner()" class="w-full py-4 rounded-2xl font-bold text-white transition-all transform active:scale-95 shadow-xl hover:shadow-2xl text-lg" id="modal-btn">
                    {{ __('Scan Again') }}
                </button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const overlay = document.getElementById('result-overlay');
        const modalTitle = document.getElementById('modal-title');
        const modalMsg = document.getElementById('modal-message');
        const iconContainer = document.getElementById('status-icon-container');
        const modalBtn = document.getElementById('modal-btn');
        
        let isProcessing = false;
        let html5QrcodeScanner = null;

        // --- 1. បន្ថែម Function សម្រាប់ចាប់ Error (ពេល Scan មិនចេញ) ---
        function onScanFailure(error) {
            // កុំបង្ហាញ Alert រំខានពេលកំពុង Scan កាមេរ៉ា
            // ប៉ុន្តែយើងអាចមើលក្នុង Console បានថាហេតុអ្វីវាអានរូបភាពមិនចេញ
            console.warn(`Code scan error = ${error}`);
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            // Pause Camera
            try {
                html5QrcodeScanner.clear(); // ប្រើ clear() ជំនួស pause() ដើម្បីបិទទាំងស្រុងពេលជោគជ័យ
            } catch (e) { console.log(e); }
            
            if (navigator.vibrate) navigator.vibrate(200);
            showModal('processing');

            fetch('{{ route("student.process-scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ token: decodedText })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || response.statusText);
                return data;
            })
            .then(data => {
                if(data.success) {
                    showModal('success', data.message);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showModal('error', error.message);
            });
        }

        function showModal(type, message = '') {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');

            if (type === 'processing') {
                iconContainer.className = "mx-auto w-24 h-24 rounded-full flex items-center justify-center mb-6 bg-blue-50 text-blue-600 animate-spin-slow";
                iconContainer.innerHTML = `<svg class="w-10 h-10" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
                modalTitle.innerText = "Processing...";
                modalMsg.innerText = "Verifying attendance...";
                modalBtn.classList.add('hidden');
            } 
            else if (type === 'success') {
                iconContainer.className = "mx-auto w-24 h-24 rounded-full flex items-center justify-center mb-6 bg-green-100 text-green-600";
                iconContainer.innerHTML = `<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>`;
                modalTitle.innerText = "Awesome!";
                modalMsg.innerText = message;
                modalBtn.className = "w-full py-4 rounded-2xl font-bold text-white bg-green-500 hover:bg-green-600 transition-all shadow-lg shadow-green-200 block";
                modalBtn.innerText = "Done";
                modalBtn.classList.remove('hidden');
            } 
            else if (type === 'error') {
                iconContainer.className = "mx-auto w-24 h-24 rounded-full flex items-center justify-center mb-6 bg-red-100 text-red-500";
                iconContainer.innerHTML = `<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>`;
                modalTitle.innerText = "Oops!";
                modalMsg.innerText = message;
                modalBtn.className = "w-full py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 transition-all shadow-lg shadow-red-200 block";
                modalBtn.innerText = "Try Again";
                modalBtn.classList.remove('hidden');
            }
        }

        function resetScanner() {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            isProcessing = false;
            // Reload page ដើម្បី reset scanner ទាំងស្រុង (វិធីសុវត្ថិភាពបំផុតសម្រាប់ library នេះ)
            window.location.reload();
        }

        // --- Initialize Scanner ---
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
                aspectRatio: 1.0,
                // បើក Experimental Features ដើម្បីឱ្យវាព្យាយាមចាប់ QR ពិបាកៗ
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                },
                rememberLastUsedCamera: true
            },
            false
        );
        
        // 👉 ដាក់ onScanFailure នៅទីនេះ
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        // CSS Animations
        const styleSheet = document.createElement("style");
        styleSheet.innerText = `
            @keyframes scan { 0% { top: 0; } 50% { top: 100%; } 100% { top: 0; } }
            .animate-scan { animation: scan 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite; }
            .animate-spin-slow { animation: spin 2s linear infinite; }
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        `;
        document.head.appendChild(styleSheet);
    </script>
</x-app-layout>