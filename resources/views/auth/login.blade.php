<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:wght@300;400;700&display=swap');
        :root { --primary-green: #10b981; --primary-hover: #059669; }
        body { font-family: 'Inter', 'Kantumruy Pro', sans-serif; margin: 0; background: #020617; }
        .portal-wrapper { width: 100vw; min-height: 100vh; background-image: linear-gradient(rgba(2, 6, 23, 0.75), rgba(2, 6, 23, 0.85)), url('{{ asset('assets/image/download (5).jpg') }}'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .glass-portal-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(25px); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 3rem; width: 100%; max-width: 460px; padding: 3rem; }
        .tab-btn { flex: 1; padding: 0.75rem; border-radius: 1rem; font-weight: bold; color: #6b7280; transition: all 0.3s; }
        .tab-btn.active { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.5); }
        .hidden { display: none; }
    </style>
    
    <div class="portal-wrapper">
        <div class="glass-portal-card">
            {{-- Tab Switcher --}}
            <div class="flex p-1 bg-white/5 rounded-2xl mb-10 border border-white/5">
                <button id="emailTabBtn" class="tab-btn active uppercase tracking-widest text-[11px]">
                    <i class="fa-solid fa-envelope"></i> Email
                </button>
                <button id="qrTabBtn" class="tab-btn uppercase tracking-widest text-[11px]">
                    <i class="fa-solid fa-qrcode"></i> QR Code
                </button>
            </div>

            {{-- ផ្នែក Login តាម Email --}}
            <div id="emailSection">
                <div class="mb-10">
                    <h2 class="text-3xl font-black text-white">{{ __('ចូលប្រើប្រាស់') }}</h2>
                    <p class="text-gray-400 text-[10px] uppercase opacity-60">Authentication via Credentials</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-500 uppercase mb-3 ml-1">អ៊ីម៉ែល</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500"><i class="fa-solid fa-envelope"></i></span>
                            <input id="email" type="email" name="email" required class="block w-full pl-12 pr-4 py-4 rounded-2xl bg-white/5 text-white border-white/10 outline-none focus:border-emerald-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-emerald-500 uppercase mb-3 ml-1">ពាក្យសម្ងាត់</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500"><i class="fa-solid fa-lock"></i></span>
                            
                            {{-- កំណត់ type="password" ជាដើម --}}
                            <input id="password" type="password" name="password" required class="block w-full pl-12 pr-12 py-4 rounded-2xl bg-white/5 text-white border-white/10 outline-none focus:border-emerald-500" />
                            
                            {{-- ប៊ូតុង Show/Hide ប្រើ ID សម្រាប់ JavaScript --}}
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-emerald-400">
                                <i id="eyeIcon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-emerald-600 py-5 font-black text-white hover:bg-emerald-500 transition-all uppercase tracking-widest">ចូលប្រើប្រព័ន្ធ</button>
                    <div style="opacity: 1; transform: none;"><div class="grid grid-cols-[1fr_max-content_1fr] items-center dark:text-gray-500 my-2 my-1!"><div class="h-px bg-[var(--border-heavy)]"></div><div class="mx-6 text-[13px] font-medium uppercase">OR</div><div class="h-px bg-[var(--border-heavy)]"></div></div></div>
                    <button type="button" onclick="loginWithGoogle()" class="w-full rounded-2xl bg-white py-4 font-bold text-gray-800 flex items-center justify-center gap-3 mt-4 transition-all">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5"> ចូលជាមួយ Google
                    </button>
                </form>
            </div>
                        <div class="text-center mt-12">
                <p class="text-sm text-gray-400 font-medium">
                    {{ __('មិនទាន់មានគណនីមែនទេ?') }} 
                    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-black ml-2 underline underline-offset-8 decoration-2">{{ __('ចុះឈ្មោះទីនេះ') }}</a>
                </p>
            </div>

            {{-- ផ្នែក Login តាម QR Code --}}
            <div id="qrSection" class="hidden text-center">
                <h2 class="text-2xl font-black text-white mb-2">{{ __('ចូលតាម QR Code') }}</h2>
                <div class="qr-container inline-block p-4 bg-white rounded-[2rem] border-4 border-emerald-500/30 mt-6">
                    {!! $qrCode ?? '<div class="w-48 h-48 flex items-center justify-center">QR Loading...</div>' !!}
                </div>
                <p class="text-emerald-400 text-[11px] font-black mt-8 uppercase tracking-widest" id="qr-status">រង់ចាំការស្កែន...</p>
            </div>
        </div>
    </div>

    <script>
        // ១. មុខងារ Show/Hide Password ប្រើ JavaScript
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleButton.addEventListener('click', function() {
            // ប្តូរប្រភេទ Input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // ប្តូររូប Icon
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        // ២. មុខងារប្តូរ Tab (Email / QR)
        const emailTabBtn = document.getElementById('emailTabBtn');
        const qrTabBtn = document.getElementById('qrTabBtn');
        const emailSection = document.getElementById('emailSection');
        const qrSection = document.getElementById('qrSection');

        emailTabBtn.addEventListener('click', () => {
            emailSection.classList.remove('hidden');
            qrSection.classList.add('hidden');
            emailTabBtn.classList.add('active');
            qrTabBtn.classList.remove('active');
        });

        qrTabBtn.addEventListener('click', () => {
            qrSection.classList.remove('hidden');
            emailSection.classList.add('hidden');
            qrTabBtn.classList.add('active');
            emailTabBtn.classList.remove('active');
        });
    </script>

    {{-- Firebase Login Script --}}
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";
        const firebaseConfig = {
            apiKey: "AIzaSyC5QgFzC-Kuudj7mWxLPf58xmoe_feXF3o",
            authDomain: "classmanagementsystem-cd57f.firebaseapp.com",
            projectId: "classmanagementsystem-cd57f",
        };
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        window.loginWithGoogle = () => {
            signInWithPopup(auth, provider).then((result) => {
                fetch('{{ route("auth.google.callback") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ uid: result.user.uid, email: result.user.email })
                }).then(res => res.json()).then(data => {
                    if(data.status === 'success') window.location.href = "/dashboard";
                    else alert(data.message);
                });
            });
        };
    </script>

    {{-- បន្ថែម Script នេះដើម្បីឱ្យវាស្ដាប់ការស្កែនពី Pusher --}}
@if(isset($token))
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    let currentToken = "{{ $token }}";

    // ការកំណត់ Pusher
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', { 
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        forceTLS: true
    });

    // បង្កើត Function សម្រាប់ប្តូរទំព័រពេលស្កែនជាប់
    function bindChannelEvents(channel) {
        channel.bind('login-success', function(data) {
            const statusEl = document.getElementById('qr-status');
            if (statusEl) {
                statusEl.innerText = "ជោគជ័យ! កំពុងចូលប្រព័ន្ធ...";
                statusEl.classList.add('text-emerald-400', 'animate-pulse');
            }
            // Redirect ទៅកាន់ finalize route ដើម្បីបញ្ចប់ការ Login
            window.location.href = "/qr-login/finalize/" + currentToken;
        });
    }

    // ចាប់ផ្តើមស្ដាប់ Channel
    let initialChannel = pusher.subscribe('login-channel-' + currentToken);
    bindChannelEvents(initialChannel);
</script>
@endif
</x-guest-layout>