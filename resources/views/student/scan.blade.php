<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl p-6 text-center">
                
                <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ __('ស្កែនវត្តមាន') }}</h2>
                
                {{-- កន្លែងបង្ហាញកាមេរ៉ា --}}
                <div id="reader" class="w-full rounded-2xl overflow-hidden border-4 border-dashed border-gray-300"></div>

                {{-- កន្លែងបង្ហាញសារលទ្ធផល --}}
                <div id="result-message" class="mt-4 hidden p-4 rounded-xl font-bold text-lg shadow-sm transition-all"></div>

                <a href="{{ route('student.dashboard') }}" class="mt-6 inline-block text-gray-500 hover:text-gray-700 underline">
                    {{ __('ត្រឡប់ទៅផ្ទាំងដើម') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Script សម្រាប់ដំណើរការកាមេរ៉ា --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const resultMsg = document.getElementById('result-message');

        function onScanSuccess(decodedText, decodedResult) {
            // 1. បញ្ឈប់ការស្កែនជាបណ្ដោះអាសន្ន
            html5QrcodeScanner.clear();

            // 2. បង្ហាញសារ "កំពុងដំណើរការ..."
            resultMsg.className = "mt-4 p-4 rounded-xl font-bold text-lg bg-blue-50 text-blue-600 block animate-pulse";
            resultMsg.innerText = "កំពុងផ្ទៀងផ្ទាត់...";

            // 3. ផ្ញើទិន្នន័យទៅ Server តាមរយៈ API
            fetch('{{ route("student.process-scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ token: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // ជោគជ័យ
                    resultMsg.className = "mt-4 p-4 rounded-xl font-bold text-lg bg-green-50 text-green-600 block shadow-green-100";
                    resultMsg.innerText = "✅ " + data.message;
                    // Play success sound (Optional)
                } else {
                    // បរាជ័យ
                    resultMsg.className = "mt-4 p-4 rounded-xl font-bold text-lg bg-red-50 text-red-600 block shadow-red-100";
                    resultMsg.innerText = "❌ " + data.message;
                    
                    // បើកកាមេរ៉ាវិញដោយស្វ័យប្រវត្តិបន្ទាប់ពី ២ វិនាទី
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(error => {
                console.error(error);
                resultMsg.innerText = "មានបញ្ហាបច្ចេកទេស!";
            });
        }

        // កំណត់កាមេរ៉ា
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: {width: 250, height: 250} },
            false
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>