<x-app-layout>
    <style>
        /* Scanning Animation Line */
        @keyframes scan {
            0% { top: 0; }
            50% { top: 100%; }
            100% { top: 0; }
        }
        .scanner-container {
            position: relative;
            overflow: hidden;
            background-color: #000;
        }
        .scanner-line {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 3px;
            background: #10b981;
            box-shadow: 0 0 15px 5px rgba(16, 185, 129, 0.7);
            animation: scan 2.5s infinite ease-in-out;
            z-index: 10;
            pointer-events: none;
        }
        /* Hide html5-qrcode default success/error messages to use our own */
        #reader__status_span { display: none !important; }
        #reader img { display: block; margin: 0 auto; }
        #reader button {
            background-color: #10b981 !important;
            color: white !important;
            border-radius: 0.75rem !important;
            padding: 0.6rem 1.2rem !important;
            font-weight: 700 !important;
            border: none !important;
            cursor: pointer;
            transition: all 0.2s;
        }
        #reader button:hover { background-color: #059669 !important; }
    </style>

    <div class="min-h-[80vh] flex flex-col items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <div class="bg-white/90 backdrop-blur-xl border border-gray-100 shadow-2xl rounded-[2.5rem] p-8 text-center relative">
                
                {{-- Header --}}
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 rounded-2xl mb-4 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">ស្កែន QR Code</h2>
                    <p class="text-gray-500 text-sm mt-1 uppercase tracking-wider font-semibold">Security Authorization</p>
                </div>

                {{-- Scanner Window --}}
                <div class="scanner-container rounded-[1.5rem] border-4 border-gray-100 bg-black relative">
                    <div id="reader" class="w-full overflow-hidden"></div>
                    <div id="scan-line" class="scanner-line"></div>
                </div>

                {{-- Status Display --}}
                <div id="result" class="mt-6 min-h-[2rem]">
                    <p class="text-sm font-bold transition-all duration-300" id="status-text"></p>
                </div>
                
                <div class="mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[11px] text-gray-500 leading-relaxed">
                        <span class="text-emerald-600 font-black mr-1 uppercase">ចំណាំ:</span> 
                        សូមដាក់ QR Code ឱ្យចំកណ្តាលប្រអប់ស្កែន ដើម្បីបញ្ជាក់អត្តសញ្ញាណចូលប្រើប្រាស់ប្រព័ន្ធ។
                    </p>
                </div>
            </div>

            <p class="text-center mt-8 text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} NMU Security Portal
            </p>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const statusText = document.getElementById('status-text');
        const scanLine = document.getElementById('scan-line');

        function onScanSuccess(decodedText, decodedResult) {
            // ប្តូរ UI ពេលស្កែនជាប់
            statusText.innerText = "កំពុងបញ្ជាក់អត្តសញ្ញាណ...";
            statusText.style.color = "#059669";
            statusText.classList.add('animate-pulse');
            scanLine.style.display = "none"; // បញ្ឈប់ Line Animation

            // ផ្ញើទិន្នន័យទៅ Laravel
            fetch('/qr-authorize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // សំខាន់បំផុត! បង្ខំឱ្យ Laravel បោះ JSON មកវិញ
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ token: decodedText })
            })
            .then(async response => {
                // ឆែកមើលថាតើ Response ជា JSON មែនឬអត់
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;

                if (!response.ok) {
                    // បើ Server បោះ Error មក (ឧទាហរណ៍ 401, 419, 500)
                    throw new Error(data?.message || 'Server Error: ' + response.status);
                }
                return data;
            })
            .then(data => {
                if (data && data.status === 'success') {
                    statusText.innerText = "✓ បញ្ជាក់អត្តសញ្ញាណជោគជ័យ!";
                    statusText.style.color = "#10b981";
                    statusText.classList.remove('animate-pulse');
                    
                    // បញ្ឈប់ Scanner ទាំងស្រុង
                    html5QrcodeScanner.clear();

                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 800);
                } else {
                    throw new Error(data?.message || "ទិន្នន័យមិនត្រឹមត្រូវ!");
                }
            })
            .catch(error => {
                console.error('Scan Error:', error);
                statusText.innerText = "❌ " + error.message;
                statusText.style.color = "#e11d48";
                statusText.classList.remove('animate-pulse');
                
                // បើ Error អនុញ្ញាតឱ្យស្កែនម្តងទៀតក្រោយ 2 វិនាទី
                setTimeout(() => {
                    statusText.innerText = "";
                    scanLine.style.display = "block";
                }, 2000);
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 20, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true
        });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>