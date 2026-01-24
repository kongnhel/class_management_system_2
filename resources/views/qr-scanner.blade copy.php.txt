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
fetch('/qr-authorize', { // ប្រើ URL ខ្លីបែបនេះ វានឹងរត់ទៅតាម Domain HTTPS អូតូ
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        // ត្រូវតែបន្ថែមបន្ទាត់នេះ ដើម្បីផ្ទៀងផ្ទាត់សុវត្ថិភាពលើ HTTPS
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ token: decodedText })
})
.then(response => {
    if (response.status === 401) {
        alert("Session ផុតកំណត់! សូម Login លើទូរស័ព្ទម្ដងទៀត។");
        window.location.reload();
    }
    return response.json();
})
.then(data => {
    if (data.status === 'success') {
        alert("បញ្ជាក់អត្តសញ្ញាណជោគជ័យ!");
        // បន្ថែមការ Redirect ទៅកាន់ Dashboard លើទូរស័ព្ទ
        window.location.href = "{{ route('dashboard') }}";
    }
});
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>