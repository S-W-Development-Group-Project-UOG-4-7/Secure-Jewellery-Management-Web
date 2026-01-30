<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Secure Jewellery Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a;
            color: #ffffff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
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

        #bg-video-1 { opacity: 0.25; z-index: 1; }
        #bg-video-2 { opacity: 0.20; z-index: 2; }
        #bg-video-3 { opacity: 0.15; z-index: 3; }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.8) 0%, rgba(0, 0, 0, 0.9) 100%);
            z-index: 4;
        }

        .content-wrapper {
            position: relative;
            z-index: 5;
            min-height: 100vh;
        }

        .gold-accent {
            color: #d4af37;
        }

        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            color: #1a1a1a;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
        }

        .btn-outline-gold {
            background: transparent;
            color: #d4af37;
            border: 2px solid #d4af37;
            padding: 10px 28px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background: #d4af37;
            color: #1a1a1a;
        }

        .card {
            background: rgba(42, 42, 42, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .input-field {
            background: rgba(26, 26, 26, 0.8);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 6px;
            padding: 12px 18px;
            color: #ffffff;
            width: 100%;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
        }

        .navbar {
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: #d4af37;
            text-decoration: none;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar-menu a {
            color: #ffffff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .navbar-menu a:hover {
            background: rgba(212, 175, 55, 0.1);
            color: #d4af37;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border-color: #28a745;
            color: #9fff9f;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #ff9f9f;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background: rgba(212, 175, 55, 0.2);
            color: #d4af37;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #d4af37;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }

        .table tr:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background: rgba(40, 167, 69, 0.3);
            color: #9fff9f;
        }

        .badge-inactive {
            background: rgba(220, 53, 69, 0.3);
            color: #ff9f9f;
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
        @auth
        <nav class="navbar">
            <a href="{{ route('dashboard') }}" class="navbar-brand">SJM</a>
            <ul class="navbar-menu">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('customers.index') }}">Customers</a></li>
                <li><a href="{{ route('jewellery.index') }}">Jewellery</a></li>
                <li><a href="{{ route('lockers.index') }}">Lockers</a></li>
                <li><a href="{{ route('locker-verification.index') }}">Verification</a></li>
                <li><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                <li><a href="{{ route('deliveries.index') }}">Deliveries</a></li>
                <li><a href="{{ route('stock.index') }}">Stock</a></li>
                @if(Auth::user()->role === 'admin')
                <li><a href="{{ route('admin.users.index') }}">Users</a></li>
                @endif
                <li><a href="{{ route('profile.show') }}">Profile</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-outline-gold" style="padding: 6px 20px;">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
        @endauth

        <main style="padding: 40px 30px;">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
