<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SJM | Authenticate</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@300&display=swap" rel="stylesheet">
    
    <style>
        :root { --gold: #d4af37; --bg: #050505; --panel: #121212; --border: #333; }
        body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { width: 100%; max-width: 400px; padding: 40px; background: rgba(18,18,18,0.95); border: 1px solid var(--border); border-radius: 12px; }
        .bg-video { position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.3; z-index: -1; }
        h2 { font-family: 'Playfair Display'; color: var(--gold); text-align: center; margin-bottom: 30px; }
        label { display: block; color: #888; font-size: 0.7rem; margin-bottom: 8px; letter-spacing: 1px; }
        input { width: 100%; padding: 12px; background: #000; border: 1px solid #333; color: #fff; border-radius: 5px; outline: none; margin-bottom: 20px; }
        input:focus { border-color: var(--gold); }
        .btn { width: 100%; padding: 12px; background: var(--gold); border: none; font-weight: bold; cursor: pointer; border-radius: 5px; }
        .hidden { display: none; }
        #msg { text-align: center; margin-top: 15px; font-size: 0.8rem; height: 20px; }
    </style>
</head>
<body>
    <video autoplay muted loop playsinline class="bg-video"><source src="{{ asset('assets/v1.mp4') }}" type="video/mp4"></video>

    <div class="login-box">
        <h2>SJM Portal</h2>
        
        <form id="step1">
            <label>ACCESS ID</label>
            <input type="text" id="username" required>
            <label>PASSPHRASE</label>
            <input type="password" id="password" required>
            <button type="submit" class="btn" id="btn1">VERIFY CREDENTIALS</button>
        </form>

        <form id="step2" class="hidden">
            <p style="text-align: center; color: #aaa; margin-bottom: 20px;">Code sent to email.</p>
            <label>SECURITY CODE</label>
            <input type="text" id="otp" style="text-align: center; letter-spacing: 5px; font-size: 1.2rem;" maxlength="6" required>
            <button type="submit" class="btn" id="btn2">AUTHENTICATE</button>
        </form>

        <div id="msg"></div>
    </div>

    <script>
        let currentUsername = '';
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const msg = document.getElementById('msg');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        step1.addEventListener('submit', async (e) => {
            e.preventDefault();
            document.getElementById('btn1').innerText = 'SENDING...';
            
            const res = await fetch("{{ route('login') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ 
                    username: document.getElementById('username').value, 
                    password: document.getElementById('password').value 
                })
            });
            const data = await res.json();

            if (data.status === 'otp_required') {
                currentUsername = data.username;
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                msg.style.color = '#4caf50';
                msg.innerText = 'Code Sent!';
            } else {
                msg.style.color = '#ff5252';
                msg.innerText = data.message;
                document.getElementById('btn1').innerText = 'VERIFY CREDENTIALS';
            }
        });

        step2.addEventListener('submit', async (e) => {
            e.preventDefault();
            document.getElementById('btn2').innerText = 'CHECKING...';

            const res = await fetch("{{ route('otp.verify') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ username: currentUsername, otp: document.getElementById('otp').value })
            });
            const data = await res.json();

            if (data.status === 'success') {
                msg.style.color = '#4caf50';
                msg.innerText = 'SUCCESS';
                window.location.href = data.redirect;
            } else {
                msg.style.color = '#ff5252';
                msg.innerText = data.message;
                document.getElementById('btn2').innerText = 'AUTHENTICATE';
            }
        });
    </script>
</body>
</html>