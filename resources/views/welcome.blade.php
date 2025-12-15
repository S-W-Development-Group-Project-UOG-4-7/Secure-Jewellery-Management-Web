<!DOCTYPE html>
<html lang="en" data-theme="black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SJM | The Authority in Jewellery Security</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;800&family=Manrope:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { gold: '#D4AF37', dark: '#050505' },
                    fontFamily: { serif: ['Cinzel', 'serif'], sans: ['Manrope', 'sans-serif'] },
                    animation: { 'fade-in': 'fadeIn 1.5s ease-out' },
                    keyframes: { fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } } }
                }
            }
        }
    </script>

    <style>
        body { background-color: #000; overflow-x: hidden; cursor: none; }
        
        /* VIDEO ENGINE */
        .video-layer { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -50; background: #000; }
        .bg-video {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; opacity: 0; transition: opacity 1.5s;
            filter: brightness(0.4) contrast(1.1);
        }
        .bg-video.active { opacity: 1; }

        /* CUSTOM CURSOR */
        .cursor-dot { position: fixed; width: 8px; height: 8px; background: #D4AF37; border-radius: 50%; z-index: 9999; pointer-events: none; }
        .cursor-outline { position: fixed; width: 40px; height: 40px; border: 1px solid #D4AF37; border-radius: 50%; z-index: 9999; pointer-events: none; transition: 0.15s; }
    </style>
</head>
<body class="font-sans text-white antialiased">

    <div id="debug-box" class="fixed top-0 left-0 bg-red-600 text-white p-4 z-50 hidden font-mono text-xs">
        <p class="font-bold">VIDEO LOAD ERROR:</p>
        <p id="debug-url"></p>
        <p>Ensure files are in: public/assets/v1.mp4</p>
    </div>

    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <div class="video-layer">
        <video muted playsinline class="bg-video active" id="vid1">
            <source src="{{ asset('assets/v1.mp4') }}" type="video/mp4">
        </video>
        <video muted playsinline class="bg-video" id="vid2">
            <source src="{{ asset('assets/v2.mp4') }}" type="video/mp4">
        </video>
        <video muted playsinline class="bg-video" id="vid3">
            <source src="{{ asset('assets/v3.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-dark/80"></div>
    </div>

    <div class="navbar fixed top-0 z-50 px-6 py-4">
        <div class="navbar-start">
            <a class="text-2xl font-serif font-bold text-white hover:text-gold transition-colors">SJM<span class="text-gold">.</span></a>
        </div>
        <div class="navbar-end gap-3">
            <button onclick="login_modal.showModal()" class="btn btn-ghost hover:text-gold text-white font-bold">LOGIN</button>
            <button onclick="register_modal.showModal()" class="btn bg-white text-black hover:bg-gold border-none font-bold">REGISTER</button>
        </div>
    </div>

    <div class="hero min-h-screen relative z-10 flex items-center justify-center text-center">
        <div class="max-w-4xl px-4">
            <div class="inline-block px-3 py-1 border border-gold/30 rounded-full bg-black/40 backdrop-blur-sm mb-6 animate-fade-in">
                <span class="text-gold font-mono text-xs tracking-[0.2em] uppercase">System v2.5 Online</span>
            </div>
            <h1 class="mb-5 text-5xl md:text-7xl font-serif font-bold text-white leading-tight animate-fade-in">
                Secure Assets.<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-gold to-white">Design Futures.</span>
            </h1>
            <p class="mb-8 text-lg text-gray-300 font-light max-w-2xl mx-auto animate-fade-in">
                The definitive platform for high-value jewellery management.
            </p>
            <button onclick="login_modal.showModal()" class="btn bg-gold hover:bg-white text-black border-none px-8 rounded-none font-serif font-bold tracking-widest animate-fade-in">ENTER PORTAL</button>
        </div>
    </div>

    <dialog id="login_modal" class="modal backdrop-blur-sm">
        <div class="modal-box bg-neutral-900 border border-white/10 rounded-none p-10 max-w-md w-full shadow-2xl">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-white">✕</button></form>
            <h3 class="font-serif text-2xl font-bold text-gold mb-6 text-center">Secure Access</h3>
            
            <div id="step1">
                <input type="text" id="loginUser" placeholder="Username" class="input input-bordered w-full bg-black border-gray-700 mb-4 text-white rounded-none focus:border-gold" />
                <input type="password" id="loginPass" placeholder="Password" class="input input-bordered w-full bg-black border-gray-700 mb-6 text-white rounded-none focus:border-gold" />
                <button id="authBtn" class="btn w-full bg-gold hover:bg-white text-black border-none rounded-none font-bold">VERIFY</button>
            </div>

            <div id="step2" class="hidden text-center">
                <div class="alert bg-green-900/30 text-green-400 border border-green-500/50 mb-6 text-xs font-mono rounded-none" id="otpMsg"></div>
                <input type="text" id="otpInput" maxlength="6" placeholder="______" class="input input-lg w-full bg-black border-gray-700 text-center text-3xl tracking-[0.5em] text-white focus:border-gold rounded-none mb-6" />
                <button onclick="verifyOtp()" class="btn w-full bg-white hover:bg-gold text-black border-none rounded-none font-bold">LOGIN</button>
            </div>
            <p id="loginError" class="text-center text-red-500 text-xs font-mono mt-4 min-h-5"></p>
        </div>
    </dialog>

    <dialog id="register_modal" class="modal backdrop-blur-sm">
        <div class="modal-box bg-neutral-900 border border-white/10 rounded-none p-10 max-w-md w-full shadow-2xl">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-white">✕</button></form>
            <h3 class="font-serif text-2xl font-bold text-white mb-6 text-center">Join Network</h3>
            <input type="text" id="regUser" placeholder="Username" class="input input-bordered w-full bg-black border-gray-700 mb-4 text-white rounded-none" />
            <input type="email" id="regEmail" placeholder="Email" class="input input-bordered w-full bg-black border-gray-700 mb-4 text-white rounded-none" />
            <input type="password" id="regPass" placeholder="Password" class="input input-bordered w-full bg-black border-gray-700 mb-6 text-white rounded-none" />
            <button id="regBtn" class="btn w-full bg-white hover:bg-gold text-black border-none rounded-none font-bold">SUBMIT</button>
            <p id="regError" class="text-center text-red-500 text-xs font-mono mt-4 min-h-5"></p>
        </div>
    </dialog>

    <script>
        const API_BASE = '/api/auth';
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentUser = "";

        window.onload = () => {
            // 1. Video Engine
            const videos = document.querySelectorAll('.bg-video');
            if(videos.length > 0) {
                // Check for errors
                videos[0].onerror = () => {
                    document.getElementById('debug-box').classList.remove('hidden');
                    document.getElementById('debug-url').innerText = "Failed: " + videos[0].currentSrc;
                };

                // Play Loop
                let current = 0;
                videos[0].play().catch(e => console.log("Autoplay blocked"));
                
                setInterval(() => {
                    const next = (current + 1) % videos.length;
                    videos[next].play().then(() => {
                        videos[current].classList.remove('active');
                        videos[next].classList.add('active');
                        current = next;
                    });
                }, 7000);
            }

            // 2. Cursor
            const dot = document.querySelector(".cursor-dot");
            const outline = document.querySelector(".cursor-outline");
            window.addEventListener("mousemove", (e) => {
                dot.style.left = `${e.clientX}px`; dot.style.top = `${e.clientY}px`;
                outline.animate({ left: `${e.clientX}px`, top: `${e.clientY}px` }, { duration: 500, fill: "forwards" });
            });
        };

        // --- AUTH LOGIC ---
        document.getElementById('authBtn').addEventListener('click', async () => {
            const btn = document.getElementById('authBtn');
            const msg = document.getElementById('loginError');
            btn.innerHTML = '...'; btn.disabled = true; msg.innerText = "";

            try {
                const res = await fetch(`${API_BASE}/login`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                    body: JSON.stringify({ 
                        username: document.getElementById('loginUser').value, 
                        password: document.getElementById('loginPass').value 
                    })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    currentUser = document.getElementById('loginUser').value;
                    document.getElementById('step1').classList.add('hidden');
                    document.getElementById('step2').classList.remove('hidden');
                    document.getElementById('otpMsg').innerText = data.message;
                } else {
                    msg.innerText = data.message;
                    btn.innerHTML = 'VERIFY'; btn.disabled = false;
                }
            } catch(e) { 
                msg.innerText = "Connection Failed"; 
                btn.innerHTML = 'VERIFY'; 
                btn.disabled = false; 
            }
        });

        async function verifyOtp() {
            const otp = document.getElementById('otpInput').value;
            try {
                const res = await fetch(`${API_BASE}/otp`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                    body: JSON.stringify({ username: currentUser, otp: otp })
                });
                const data = await res.json();
                if (data.status === 'success') window.location.href = data.redirect;
                else document.getElementById('loginError').innerText = data.message;
            } catch(e) { document.getElementById('loginError').innerText = "Server Error"; }
        }

        document.getElementById('regBtn').addEventListener('click', async () => {
            const btn = document.getElementById('regBtn');
            const msg = document.getElementById('regError');
            btn.innerHTML = '...'; 

            try {
                const res = await fetch(`${API_BASE}/register`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                    body: JSON.stringify({ 
                        username: document.getElementById('regUser').value, 
                        email: document.getElementById('regEmail').value, 
                        password: document.getElementById('regPass').value 
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    msg.className = "text-green-400"; msg.innerText = "Success! Login now.";
                    setTimeout(() => { document.getElementById('register_modal').close(); document.getElementById('login_modal').showModal(); }, 1500);
                } else {
                    msg.className = "text-red-500"; msg.innerText = data.message;
                }
            } catch(e) { msg.innerText = "Error"; }
            finally { btn.innerHTML = 'SUBMIT'; }
        });
    </script>
</body>
</html>