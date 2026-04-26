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
        }
        .scanner-line {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 2px;
            background: rgba(16, 185, 129, 0.8);
            box-shadow: 0 0 15px 5px rgba(16, 185, 129, 0.5);
            animation: scan 3s infinite linear;
            z-index: 10;
            pointer-events: none;
        }
        /* Customizing html5-qrcode UI */
        #reader { border: none !important; }
        #reader__dashboard_section_csr button {
            background-color: #10b981 !important;
            color: white !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            font-weight: 600 !important;
            border: none !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
    </style>

    <div class="min-h-[80vh] flex flex-col items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            {{-- Card Container --}}
            <div class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-2xl rounded-[2.5rem] p-8 text-center relative overflow-hidden">
                
                {{-- Header Section --}}
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 rounded-2xl mb-4 text-emerald-600 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">ស្កែន QR Code</h2>
                    <p class="text-gray-500 text-sm mt-1">Scan to authorize desktop login</p>
                </div>

                {{-- Scanner Window --}}
                <div class="scanner-container rounded-[2rem] border-4 border-emerald-500/10 bg-black relative shadow-2xl">
                    <div id="reader" class="w-full"></div>
                    <div class="scanner-line"></div> {{-- Animated Line --}}
                </div>

                {{-- Status Display --}}
                <div id="result" class="mt-6 min-h-[1.5rem] flex items-center justify-center gap-2">
                    <p class="text-sm font-bold text-emerald-600" id="status-text"></p>
                </div>
                
                <div class="mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">
                        <span class="text-emerald-600 font-bold uppercase mr-1">ចំណាំ:</span> 
                        សូមឆ្លុះកាមេរ៉ាទៅកាន់ QR Code ដែលបង្ហាញនៅលើអេក្រង់ Computer របស់អ្នកឱ្យចំផ្ទៃកណ្តាល
                    </p>
                </div>
            </div>

            {{-- Footer Support --}}
            <p class="text-center mt-8 text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} NMU Security Portal
            </p>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
   function onScanSuccess(decodedText, decodedResult) {
    console.log(`Code matched = ${decodedText}`);
    document.getElementById('status-text').innerText = "កំពុងផ្ទៀងផ្ទាត់...";

    fetch('/verify-qr-login', { // ផ្លូវ API របស់បង
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ qr_code: decodedText })
    })
    .then(response => {
        // បើ Response មិនមែន 200 OK ទេ កុំអាល Parse JSON
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text().then(text => text ? JSON.parse(text) : {}); 
        // ជួរខាងលើជួយការពារ បើ Server បោះមកទទេ ក៏មិន Error ដែរ
    })
    .then(data => {
        if (data.success) {
            document.getElementById('status-text').innerText = "ជោគជ័យ! កំពុងចូលប្រព័ន្ធ...";
            window.location.href = "/dashboard";
        } else {
            document.getElementById('status-text').innerText = data.message || "QR មិនត្រឹមត្រូវ";
        }
    })
    .catch(err => {
        console.error("Scan Error:", err);
        document.getElementById('status-text').innerText = "មានបញ្ហាបច្ចេកទេស!";
    });
}
        // ការកំណត់ Scanner ឱ្យដើររលូន
        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 15, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0 
        });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>