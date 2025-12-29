<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SJM | Secure Jewellery Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .video-layer {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translateX(-50%) translateY(-50%);
            object-fit: cover;
        }

        #bg-video-1 { opacity: 0.15; z-index: 1; }
        #bg-video-2 { opacity: 0.12; z-index: 2; }
        #bg-video-3 { opacity: 0.10; z-index: 3; }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10, 10, 10, 0.95) 0%, rgba(0, 0, 0, 0.98) 100%);
            z-index: 4;
        }

        .content-wrapper {
            position: relative;
            z-index: 5;
        }

        .navbar {
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }

        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            color: #0a0a0a;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.3);
            border: none;
            cursor: pointer;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(212, 175, 55, 0.5);
        }

        .btn-outline-gold {
            border: 2px solid #d4af37;
            color: #d4af37;
            transition: all 0.3s ease;
            background: transparent;
            cursor: pointer;
        }

        .btn-outline-gold:hover {
            background: #d4af37;
            color: #0a0a0a;
        }

        .glow-card {
            background: rgba(20, 20, 20, 0.8);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            transition: all 0.4s ease;
        }

        .glow-card:hover {
            border-color: rgba(212, 175, 55, 0.6);
            box-shadow: 0 8px 40px rgba(212, 175, 55, 0.3);
            transform: translateY(-8px);
        }

        .hero-text {
            animation: fadeUp 1s ease-out;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: rgba(20, 20, 20, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            animation: slideUp 0.4s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .mobile-menu {
            display: none;
            background: rgba(10, 10, 10, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(212, 175, 55, 0.2);
        }

        .mobile-menu.active {
            display: block;
        }

        .input-field {
            background: rgba(10, 10, 10, 0.9);
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: #ffffff;
            transition: all 0.3s ease;
            padding: 12px 18px;
            border-radius: 6px;
            width: 100%;
        }

        .input-field:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }
    </style>
</head>
<body>
    <div class="video-background">
        <video id="bg-video-1" class="video-layer" autoplay muted loop playsinline>
            <source src="https://cdn.pixabay.com/video/2021/03/27/69098-531024197_large.mp4" type="video/mp4">
        </video>
        <video id="bg-video-2" class="video-layer" autoplay muted loop playsinline>
            <source src="https://cdn.pixabay.com/video/2020/01/08/31055-383967820_large.mp4" type="video/mp4">
        </video>
        <video id="bg-video-3" class="video-layer" autoplay muted loop playsinline>
            <source src="https://cdn.pixabay.com/video/2022/06/23/121493-724736834_large.mp4" type="video/mp4">
        </video>
    </div>
    <div class="overlay"></div>

    <div class="content-wrapper">
        <nav class="navbar">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-bold gold-gradient-text">SJM</div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#home" class="text-gray-300 hover:text-[#d4af37] transition">Home</a>
                        <a href="#features" class="text-gray-300 hover:text-[#d4af37] transition">Features</a>
                        <a href="#security" class="text-gray-300 hover:text-[#d4af37] transition">Security</a>
                        <button onclick="openLoginModal()" class="btn-outline-gold px-6 py-2 rounded-lg">Login</button>
                        <button onclick="openRegisterModal()" class="btn-gold px-6 py-2 rounded-lg">Register</button>
                    </div>

                    <button onclick="toggleMobileMenu()" class="md:hidden text-[#d4af37]">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>

                <div id="mobileMenu" class="mobile-menu mt-4">
                    <div class="flex flex-col space-y-4 py-4">
                        <a href="#home" class="text-gray-300 hover:text-[#d4af37] transition">Home</a>
                        <a href="#features" class="text-gray-300 hover:text-[#d4af37] transition">Features</a>
                        <a href="#security" class="text-gray-300 hover:text-[#d4af37] transition">Security</a>
                        <button onclick="openLoginModal()" class="btn-outline-gold px-6 py-2 rounded-lg text-left">Login</button>
                        <button onclick="openRegisterModal()" class="btn-gold px-6 py-2 rounded-lg text-center">Register</button>
                    </div>
                </div>
            </div>
        </nav>

        <section id="home" class="min-h-screen flex items-center justify-center pt-20 px-6">
            <div class="text-center hero-text max-w-5xl">
                <div class="inline-block px-4 py-2 rounded-full border border-[#d4af37] mb-6">
                    <span class="text-[#d4af37] text-sm font-semibold">SYSTEM V2.5 ONLINE</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    <span class="gold-gradient-text">Secure Assets.</span><br>
                    <span class="text-white">Design Futures.</span>
                </h1>
                <p class="text-gray-300 text-lg md:text-xl mb-10 max-w-3xl mx-auto leading-relaxed">
                    A military-grade ecosystem for jewellery management. Verify locker integrity, track live inventory, and create bespoke pieces with our AI Design Engine.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="openLoginModal()" class="btn-gold px-8 py-4 rounded-lg text-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Enter Portal
                    </button>
                    <button onclick="openRegisterModal()" class="btn-outline-gold px-8 py-4 rounded-lg text-lg">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </button>
                    <button onclick="alert('Demo request sent! Our team will contact you shortly.')" class="btn-outline-gold px-8 py-4 rounded-lg text-lg">
                        <i class="fas fa-play mr-2"></i>Request Demo
                    </button>
                </div>
            </div>
        </section>

        <section id="features" class="py-20 px-6">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4">
                        <span class="gold-gradient-text">Powerful Features</span>
                    </h2>
                    <p class="text-gray-400 text-lg">Enterprise-grade tools for modern jewellery management</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="glow-card p-8 rounded-2xl">
                        <div class="text-[#d4af37] text-4xl mb-4">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Smart Logistics</h3>
                        <p class="text-gray-400">Supplier reliability scoring based on delivery speed and accuracy. Digital invoice processing and dispute resolution.</p>
                    </div>

                    <div class="glow-card p-8 rounded-2xl">
                        <div class="text-[#d4af37] text-4xl mb-4">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Inventory AI</h3>
                        <p class="text-gray-400">Granular tracking of carat weight, metal purity, and depreciation. Automated low-stock alerts and valuation reports.</p>
                    </div>

                    <div class="glow-card p-8 rounded-2xl">
                        <div class="text-[#d4af37] text-4xl mb-4">
                            <i class="fa-solid fa-vault"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Locker Verification</h3>
                        <p class="text-gray-400">Visual proof-of-storage protocols. Require before/after photographic evidence for every secure vault interaction.</p>
                    </div>

                    <div class="glow-card p-8 rounded-2xl">
                        <div class="text-[#d4af37] text-4xl mb-4">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">AI Custom Studio</h3>
                        <p class="text-gray-400">Empower clients to generate unique jewellery concepts instantly. AI engine visualizes custom cuts, settings, and metals in real-time.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="security" class="py-20 px-6">
            <div class="container mx-auto max-w-4xl text-center">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    <span class="gold-gradient-text">Military-Grade Security</span>
                </h2>
                <p class="text-gray-300 text-lg mb-10 leading-relaxed">
                    Your assets are protected with bank-level encryption, biometric authentication, and real-time threat monitoring. Every transaction is logged and verified across multiple secure nodes. 2-factor OTP authentication ensures maximum security.
                </p>
                <button onclick="openRegisterModal()" class="btn-gold px-8 py-4 rounded-lg text-lg inline-block">
                    <i class="fas fa-shield-halved mr-2"></i>Experience Security
                </button>
            </div>
        </section>

        <footer class="py-10 px-6 border-t border-[#d4af37]/20">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center">
                    <p class="text-gray-400 mb-2">
                        Developed by <span class="text-[#d4af37] font-semibold">K. M. Nethmi Sanjalee</span>
                    </p>
                    <p class="text-gray-500 text-sm">
                        Authentication System + Customer Management Module
                    </p>
                    <p class="text-gray-600 text-xs mt-4">
                        &copy; 2024 SJM - Secure Jewellery Management. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold gold-gradient-text">Welcome Back</h3>
                <button onclick="closeLoginModal()" class="text-gray-400 hover:text-white text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 mb-2">Email Address</label>
                    <input type="email" name="email" class="input-field" placeholder="Enter your email" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Enter your password" required>
                </div>
                <div class="mb-6">
                    <label class="flex items-center text-gray-400">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span>Remember Me</span>
                    </label>
                </div>
                <button type="submit" class="btn-gold w-full py-3 rounded-lg text-lg">
                    Continue to OTP
                </button>
            </form>
            <p class="text-center text-gray-500 text-sm mt-6">
                Don't have an account? <button onclick="closeLoginModal(); openRegisterModal();" class="text-[#d4af37] hover:underline">Register here</button>
            </p>
        </div>
    </div>

    <div id="registerModal" class="modal">
        <div class="modal-content rounded-2xl p-8 max-w-lg w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold gold-gradient-text">Create Account</h3>
                <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-white text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('register') }}" method="POST" id="registerFormModal">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 mb-2">Full Name</label>
                    <input type="text" name="name" class="input-field" placeholder="Enter your full name" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-400 mb-2">Email</label>
                        <input type="email" name="email" class="input-field" placeholder="Enter your email" required>
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-2">Phone</label>
                        <input type="text" name="phone" class="input-field" placeholder="Enter your phone" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-2">Account Type</label>
                    <select name="role" class="input-field">
                        <option value="customer">Customer</option>
                        <option value="supplier">Supplier</option>
                        <option value="delivery">Delivery Personnel</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" id="regPassword" class="input-field" placeholder="Min. 8 characters" required minlength="8">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-400 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="regPasswordConfirm" class="input-field" placeholder="Confirm your password" required minlength="8">
                    <small id="regPasswordMatch" class="block mt-2"></small>
                </div>
                <button type="submit" class="btn-gold w-full py-3 rounded-lg text-lg">
                    Register & Verify OTP
                </button>
            </form>
            <p class="text-center text-gray-500 text-sm mt-6">
                Already have an account? <button onclick="closeRegisterModal(); openLoginModal();" class="text-[#d4af37] hover:underline">Login here</button>
            </p>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
        }

        function openLoginModal() {
            document.getElementById('loginModal').classList.add('active');
            document.getElementById('mobileMenu').classList.remove('active');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
        }

        function openRegisterModal() {
            document.getElementById('registerModal').classList.add('active');
            document.getElementById('mobileMenu').classList.remove('active');
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.remove('active');
        }

        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });

        document.getElementById('registerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRegisterModal();
            }
        });

        const regPassword = document.getElementById('regPassword');
        const regPasswordConfirm = document.getElementById('regPasswordConfirm');
        const regPasswordMatch = document.getElementById('regPasswordMatch');

        function checkPasswordMatch() {
            if (regPasswordConfirm.value === '') {
                regPasswordMatch.textContent = '';
                return;
            }

            if (regPassword.value === regPasswordConfirm.value) {
                regPasswordMatch.textContent = 'Passwords match';
                regPasswordMatch.style.color = '#9fff9f';
            } else {
                regPasswordMatch.textContent = 'Passwords do not match';
                regPasswordMatch.style.color = '#ff9f9f';
            }
        }

        if (regPassword && regPasswordConfirm) {
            regPassword.addEventListener('input', checkPasswordMatch);
            regPasswordConfirm.addEventListener('input', checkPasswordMatch);
        }

        document.getElementById('registerFormModal').addEventListener('submit', function(e) {
            if (regPassword.value !== regPasswordConfirm.value) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html>
