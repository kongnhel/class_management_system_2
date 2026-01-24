<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6 text-center">
                <h2 class="text-xl font-black text-gray-800 mb-4">ស្កែន QR Code</h2>
                
                <div id="reader" class="rounded-2xl overflow-hidden border-4 border-emerald-500/20"></div>

                <div id="result" class="mt-4 text-sm font-medium text-emerald-600"></div>
                
                <p class="mt-6 text-xs text-gray-500 italic">
                    សូមឆ្លុះកាមេរ៉ាទៅកាន់ QR Code លើអេក្រង់ Computer របស់អ្នក
                </p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // decodedText គឺជា UUID ដែលបានមកពី QR
            document.getElementById('result').innerText = "កំពុងបញ្ជាក់អត្តសញ្ញាណ...";

            // បញ្ឈប់កាមេរ៉ាសិនក្រោយស្កែនជាប់
            html5QrcodeScanner.clear();

            // ផ្ញើទិន្នន័យទៅ API ក្នុង Laravel
            fetch('/api/qr/authorize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // បញ្ជាក់៖ ដោយសារយើង Login លើ Browser ស្រាប់ វានឹងប្រើ Session អូតូ
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ token: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert("Login ជោគជ័យ! Computer របស់អ្នកកំពុងបើក Dashboard");
                    window.location.href = "{{ route('dashboard') }}";
                } else {
                    alert("កំហុស៖ " + data.message);
                    location.reload(); // បើខុស ឱ្យវាស្កែនម្ដងទៀត
                }
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>