<!DOCTYPE html>
<html lang="en" data-theme="black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | SJM</title>
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
        </div>
        <div class="flex-none gap-4">
            <a href="{{ route('custom.create') }}" class="btn btn-sm bg-gold text-black border-none hover:bg-white font-bold">NEW ORDER</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-ghost text-red-500">LOGOUT</button>
            </form>
        </div>
    </div>

    <div class="pt-32 pb-20 px-4 max-w-5xl mx-auto">
        
        <h1 class="text-4xl font-serif text-white mb-8">Order History</h1>

        @if(session('success'))
            <div class="alert bg-green-900/20 border border-green-500 text-green-400 mb-8 rounded-none">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="p-12 text-center border border-white/10 rounded-xl bg-panel">
                <i class="fa-solid fa-box-open text-4xl text-gray-600 mb-4"></i>
                <p class="text-gray-400">You have no active orders.</p>
                <a href="{{ route('custom.create') }}" class="text-gold underline mt-2 inline-block">Start a Custom Design</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table w-full border border-white/10">
                    <thead class="bg-panel text-gray-400 uppercase text-xs tracking-widest">
                        <tr>
                            <th class="p-4">Order ID</th>
                            <th>Details</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-black/50">
                        @foreach($orders as $order)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="font-mono text-gold p-4">#{{ $order->id }}</td>
                            <td>
                                <div class="font-bold text-white">{{ $order->jewellery_type }}</div>
                                <div class="text-xs text-gray-500">{{ $order->metal_type }}</div>
                            </td>
                            <td class="text-sm text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge badge-warning badge-outline text-xs">Pending Review</span>
                                @elseif($order->status === 'approved')
                                    <span class="badge badge-success badge-outline text-xs">In Production</span>
                                @elseif($order->status === 'rejected')
                                    <span class="badge badge-error badge-outline text-xs">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('custom.show', $order->id) }}" class="btn btn-xs bg-white/10 text-white hover:bg-gold hover:text-black border-none">
                                    Track <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</body>
</html>