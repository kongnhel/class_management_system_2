<x-guest-layout>
    {{-- បន្ថែម CSRF Meta សម្រាប់ការពារបញ្ហាអត្តសញ្ញាណលើ HTTPS --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ហៅ Font Awesome សម្រាប់រូប Icon ភ្នែក និង Google --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:wght@300;400;700&display=swap');
        :root { --primary-green: #10b981; --primary-hover: #059669; --glow-color: rgba(16, 185, 129, 0.4); }
        body { font-family: 'Inter', 'Kantumruy Pro', sans-serif; margin: 0; padding: 0; overflow-x: hidden; background: #020617; }
        .portal-wrapper { width: 100vw; min-height: 100vh; position: relative; background-image: linear-gradient(rgba(2, 6, 23, 0.75), rgba(2, 6, 23, 0.85)), url('{{ asset('assets/image/download (5).jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .portal-wrapper::before { content: ""; position: absolute; inset: 0; background-image: radial-gradient(rgba(16, 185, 129, 0.1) 1.5px, transparent 1.5px); background-size: 45px 45px; pointer-events: none; z-index: 1; }
        .reveal { animation: revealUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; z-index: 10; }
        @keyframes revealUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .glass-portal-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(25px) saturate(180%); -webkit-backdrop-filter: blur(25px) saturate(180%); border: 1px solid rgba(16, 185, 129, 0.2); box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.8); }
        .focus-green:focus { border-color: var(--primary-green) !important; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2); background-color: rgba(255, 255, 255, 0.08); }
        .tab-btn { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .tab-btn.active { background: rgba(16, 185, 129, 0.15); color: #10b981; border-color: rgba(16, 185, 129, 0.5); }
    </style>
    
    {{-- បន្ថែម 'showPassword: false' ទៅក្នុង x-data --}}
    <div class="portal-wrapper" x-data="{ loginMode: 'email', showPassword: false }">
        
        {{-- Toast Notification --}}
        @if (session('success') || session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-5 right-5 z-[100] bg-white p-4 rounded-2xl shadow-2xl border-l-4 {{ session('success') ? 'border-green-500' : 'border-red-500' }}">
                <p class="text-sm font-bold text-gray-800">{{ session('success') ?? session('error') }}</p>
            </div>
        @endif
        
        <div class="reveal mb-10 text-center">
            <div class="relative inline-block group">
                <div class="absolute inset-0 rounded-full bg-emerald-500 blur-3xl opacity-30"></div>
                <img src="{{ asset('assets/image/nmu_Logo.png') }}" alt="NMU Logo" class="relative w-28 h-28 rounded-full border-2 border-white/20 shadow-2xl transition-all duration-700 group-hover:scale-110">
            </div>
            <h1 class="text-white mt-6 font-bold text-xl tracking-[0.5em] uppercase opacity-80">NMU PORTAL</h1>
        </div>

        <div class="w-full sm:max-w-[460px] glass-portal-card p-10 sm:p-12 overflow-hidden sm:rounded-[3rem] reveal" style="animation-delay: 0.1s">
            
            {{-- Tab Switcher --}}
            <div class="flex p-1 bg-white/5 rounded-2xl mb-10 border border-white/5">
                <button @click="loginMode = 'email'" :class="loginMode === 'email' ? 'active' : 'text-gray-500 hover:text-gray-300'" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-[11px] font-bold uppercase tracking-widest tab-btn border border-transparent">
                    <i class="fa-solid fa-envelope text-sm"></i> Email
                </button>
                <button @click="loginMode = 'qr'" :class="loginMode === 'qr' ? 'active' : 'text-gray-500 hover:text-gray-300'" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-[11px] font-bold uppercase tracking-widest tab-btn border border-transparent">
                    <i class="fa-solid fa-qrcode text-sm"></i> QR Code
                </button>
            </div>

            {{-- ផ្នែក Login តាម Email --}}
            <div x-show="loginMode === 'email'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4">
                <div class="mb-10 text-center sm:text-left">
                    <h2 class="text-3xl font-black text-white tracking-tight">{{ __('ចូលប្រើប្រាស់') }}</h2>
                    <p class="text-gray-400 text-sm mt-2 font-medium tracking-wide uppercase text-[10px] opacity-60">Authentication via Credentials</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-500 uppercase tracking-[0.2em] mb-3 ml-1">{{ __('អាសយដ្ឋានអ៊ីម៉ែល') }}</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 group-focus-within:text-emerald-400">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus class="block w-full pl-12 pr-4 py-4 rounded-2xl border-white/10 bg-white/5 text-white focus-green outline-none" placeholder="student@gmail.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-400" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-[11px] font-bold text-emerald-500 uppercase tracking-[0.2em] ml-1">{{ __('ពាក្យសម្ងាត់') }}</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] text-gray-500 hover:text-emerald-400 font-bold uppercase tracking-tighter" href="{{ route('password.request') }}">{{ __('ភ្លេចលេខសម្ងាត់?') }}</a>
                            @endif
                        </div>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 group-focus-within:text-emerald-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            
                            {{-- កែប្រែ type តាមរយៈ x-bind --}}
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required class="block w-full pl-12 pr-12 py-4 rounded-2xl border-white/10 bg-white/5 text-white focus-green outline-none" placeholder="••••••••" />
                            
                            {{-- ប៊ូតុង Show/Hide Password --}}
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-emerald-400 transition-colors">
                                <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-400" />
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-emerald-600 px-8 py-5 font-black text-white hover:bg-emerald-500 shadow-2xl shadow-emerald-900/40 uppercase tracking-[0.2em] transition-all">
                        {{ __('ចូលប្រើប្រព័ន្ធ') }}
                    </button>

                    {{-- ប៊ូតុង Login with Google (Firebase) --}}
                    <div class="relative flex items-center py-2">
                        <div class="flex-grow border-t border-white/10"></div>
                        <span class="flex-shrink mx-4 text-gray-500 text-[10px] font-bold uppercase tracking-widest">ឬ</span>
                        <div class="flex-grow border-t border-white/10"></div>
                    </div>

                    <button type="button" onclick="loginWithGoogle()" class="w-full rounded-2xl bg-white px-8 py-4 font-bold text-gray-800 hover:bg-gray-100 flex items-center justify-center gap-3 shadow-xl transition-all">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5">
                        {{ __('ចូលប្រើជាមួយ Google') }}
                    </button>
                </form>
            </div>

            {{-- ផ្នែក Login តាម QR Code --}}
            <div x-show="loginMode === 'qr'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" style="display: none;">
                <div class="text-center">
                    <h2 class="text-2xl font-black text-white mb-2">{{ __('ចូលតាម QR Code') }}</h2>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-8 opacity-60">Scan to Authenticate</p>
                    
                    <div class="qr-container inline-block p-4 bg-white rounded-[2rem] shadow-[0_0_50px_rgba(16,185,129,0.2)] border-4 border-emerald-500/30">
                        @if(isset($qrCode))
                            {!! $qrCode !!}
                        @else
                            <div class="w-48 h-48 flex items-center justify-center text-gray-300 italic text-xs">QR Loading...</div>
                        @endif
                    </div>

                    <div class="mt-8 space-y-4">
                        <div class="flex items-center justify-center gap-3">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                            <p class="text-emerald-400 text-[11px] font-black uppercase tracking-widest" id="qr-status">រង់ចាំការស្កែនពីទូរស័ព្ទ...</p>
                        </div>
                        <p class="text-gray-500 text-[10px] leading-relaxed px-6">សូមបើកកម្មវិធី NMU លើទូរស័ព្ទរបស់អ្នក រួចស្កែន QR នេះដើម្បីចូលប្រើប្រាស់ដោយមិនបាច់ប្រើលេខសម្ងាត់។</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <p class="text-sm text-gray-400 font-medium">
                    {{ __('មិនទាន់មានគណនីមែនទេ?') }} 
                    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-black ml-2 underline underline-offset-8 decoration-2">{{ __('ចុះឈ្មោះទីនេះ') }}</a>
                </p>
            </div>
        </div>

        <footer class="mt-16 text-center reveal" style="animation-delay: 0.6s">
             <p class="text-gray-600 text-[10px] uppercase tracking-[0.5em] font-black">&copy; {{ date('Y') }} National Management University</p>
        </footer>
    </div>

    {{-- Firebase SDK & Logic --}}
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

        // កូដ Config ពី Firebase Console របស់បង
        const firebaseConfig = {
            apiKey: "AIzaSyC5QgFzC-Kuudj7mWxLPf58xmoe_feXF3o",
            authDomain: "classmanagementsystem-cd57f.firebaseapp.com",
            projectId: "classmanagementsystem-cd57f",
            storageBucket: "classmanagementsystem-cd57f.firebasestorage.app",
            messagingSenderId: "171013327760",
            appId: "1:171013327760:web:d00df5782c6c78f4c64115"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        window.loginWithGoogle = () => {
            signInWithPopup(auth, provider)
                .then((result) => {
                    const user = result.user;
                    // ផ្ញើទិន្នន័យទៅ Laravel ដើម្បី Login/Register
                    fetch('{{ route("auth.google.callback") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            uid: user.uid,
                            email: user.email,
                            name: user.displayName
                        })
                    }).then(res => res.json())
.then(data => {
    if(data.status === 'success') {
        window.location.href = "/dashboard";
    } else {
        // បើគាត់មិនទាន់បាន Link គណនីទេ ឱ្យលោតប្រាប់គាត់
        alert("គណនី Google នេះមិនទាន់បានភ្ជាប់ជាមួយគណនីសិស្ស NMU ឡើយ។ សូម Login តាម Email រួចទៅភ្ជាប់ក្នុង Dashboard!");
    }
});
                })
                .catch((error) => console.error("Google Auth Error:", error));
        };
    </script>

    {{-- Script សម្រាប់ QR Real-time & Auto-Refresh --}}
    @if(isset($token))
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        let currentToken = "{{ $token }}";
        const refreshInterval = 120000; // ២ នាទី

        // ការកំណត់ Pusher
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', { 
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true,
            enabledTransports: ['ws', 'wss']
        });

        function bindChannelEvents(channel) {
            channel.bind('login-success', function(data) {
                const statusEl = document.getElementById('qr-status');
                if (statusEl) {
                    statusEl.innerText = "ជោគជ័យ! កំពុងចូលប្រព័ន្ធ...";
                    statusEl.classList.add('text-emerald-400', 'animate-pulse');
                }
                // Redirect ទៅកាន់ finalize route
                window.location.href = "/qr-login/finalize/" + currentToken;
            });
        }

        // ចាប់ផ្តើមស្ដាប់ Channel ដំបូង
        let initialChannel = pusher.subscribe('login-channel-' + currentToken);
        bindChannelEvents(initialChannel);

        // មុខងារ Auto-Refresh QR
        setInterval(() => {
            fetch("{{ route('qr.refresh') }}")
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.qr-container').innerHTML = data.qrCode;
                    pusher.unsubscribe('login-channel-' + currentToken);
                    currentToken = data.token;
                    let newChannel = pusher.subscribe('login-channel-' + currentToken);
                    bindChannelEvents(newChannel);
                    console.log("QR Refreshed");
                });
        }, refreshInterval);
    </script>
    @endif
</x-guest-layout>