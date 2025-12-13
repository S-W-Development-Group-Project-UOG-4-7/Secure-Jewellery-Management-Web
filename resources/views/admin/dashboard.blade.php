<!DOCTYPE html>
<html lang="en" data-theme="black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SJM</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Manrope:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { gold: '#D4AF37', dark: '#050505', panel: '#121212' },
                    fontFamily: { serif: ['Cinzel', 'serif'], sans: ['Manrope', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans min-h-screen">

    <div class="navbar fixed top-0 z-50 px-8 py-4 bg-black/90 backdrop-blur-md border-b border-white/10">
        <div class="flex-1">
            <a href="/" class="text-2xl font-serif font-bold text-white hover:text-gold transition-colors">SJM<span class="text-gold">.</span></a>
            <span class="ml-4 text-xs font-mono bg-red-900 text-white px-2 py-1 rounded">ADMINISTRATOR</span>
        </div>
        <div class="flex-none gap-4">
            <span class="text-xs text-gray-400 uppercase tracking-widest hidden md:inline">Welcome, {{ Auth::user()->username }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-ghost text-red-500 hover:bg-red-500/10">LOGOUT</button>
            </form>
        </div>
    </div>

    <div class="pt-32 pb-20 px-4 max-w-6xl mx-auto">
        <h1 class="text-4xl font-serif text-white mb-2">Command Center</h1>
        <p class="text-gray-400 text-sm mb-12">System Overview & Management</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <a href="{{ route('manager.approvals') }}" class="group bg-panel border border-white/10 p-8 rounded-xl hover:border-gold/50 transition-all cursor-pointer relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fa-solid fa-pen-nib text-8xl text-white"></i>
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center text-gold">
                        <i class="fa-solid fa-list-check text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Custom Orders</h3>
                </div>
                <p class="text-gray-400 text-sm mb-6">Review, approve, or reject pending custom jewellery requests from clients.</p>
                <span class="text-gold text-xs font-bold uppercase tracking-widest group-hover:underline">Manage Approvals &rarr;</span>
            </a>

            <div class="bg-panel border border-white/5 p-8 rounded-xl opacity-50">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-gray-500">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-500">User Management</h3>
                </div>
                <p class="text-gray-600 text-sm">Module in development.</p>
            </div>

            <div class="bg-panel border border-white/5 p-8 rounded-xl opacity-50">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-gray-500">
                        <i class="fa-solid fa-chart-line text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-500">Analytics</h3>
                </div>
                <p class="text-gray-600 text-sm">Module in development.</p>
            </div>

        </div>
    </div>

</body>
</html>