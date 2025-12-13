<!DOCTYPE html>
<html lang="en" data-theme="black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Approval Console | SJM</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Manrope:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
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
            <span class="ml-4 text-xs font-mono bg-gold text-black px-2 py-1 rounded">MANAGER ACCESS</span>
        </div>
        <div class="flex-none gap-4">
            <span class="text-xs text-gray-400 uppercase tracking-widest hidden md:inline">Logged in as {{ Auth::user()->username }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-ghost text-red-500 hover:bg-red-500/10">LOGOUT</button>
            </form>
        </div>
    </div>

    <div class="pt-32 pb-20 px-4 max-w-6xl mx-auto">
        
        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-4xl font-serif text-white mb-2">Order Approvals</h1>
                <p class="text-gray-400 text-sm">Review pending custom design requests.</p>
            </div>
            <div class="text-right">
                <span class="text-3xl font-mono text-gold">{{ $pendingOrders->count() }}</span>
                <span class="text-xs text-gray-500 uppercase block tracking-widest">Pending Tasks</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert bg-green-900/20 border border-green-500 text-green-400 mb-8 rounded-none">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if($pendingOrders->isEmpty())
            <div class="p-12 text-center border border-white/10 rounded-xl bg-panel">
                <i class="fa-solid fa-clipboard-check text-4xl text-gray-600 mb-4"></i>
                <p class="text-gray-400">All caught up! No pending orders.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8">
                @foreach($pendingOrders as $order)
                <div class="bg-panel border border-white/10 rounded-xl overflow-hidden flex flex-col lg:flex-row hover:border-gold/30 transition-colors">
                    
                    <div class="lg:w-1/3 h-64 lg:h-auto bg-black relative group">
                        @if($order->design_image_path)
                            <img src="{{ asset('storage/' . $order->design_image_path) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                            <a href="{{ asset('storage/' . $order->design_image_path) }}" target="_blank" class="absolute bottom-4 right-4 btn btn-xs btn-circle bg-gold text-black border-none"><i class="fa-solid fa-expand"></i></a>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-gray-600">
                                <i class="fa-regular fa-image text-3xl mb-2"></i>
                                <span class="text-xs">No Image Provided</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 badge badge-warning gap-2">
                            Order #{{ $order->id }}
                        </div>
                    </div>

                    <div class="flex-1 p-8 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-serif text-white">{{ $order->jewellery_type }} <span class="text-gold">//</span> {{ $order->metal_type }}</h3>
                                    <p class="text-sm text-gray-400 mt-1">Client: <span class="text-white font-bold">{{ $order->user->username }}</span> ({{ $order->user->email }})</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 uppercase tracking-widest">Est. Budget</p>
                                    <p class="text-xl font-mono text-gold">${{ number_format($order->budget, 2) }}</p>
                                </div>
                            </div>

                            <div class="bg-black/50 p-4 rounded border border-white/5 mb-6">
                                <p class="text-xs text-gray-500 uppercase tracking-widest mb-2">Client Requirements</p>
                                <p class="text-gray-300 text-sm leading-relaxed italic">"{{ $order->description }}"</p>
                            </div>
                        </div>

                        <form action="{{ route('manager.approve', $order->id) }}" method="POST" class="border-t border-white/10 pt-6">
                            @csrf
                            
                            <div class="form-control mb-4">
                                <input type="text" name="manager_comments" placeholder="Add notes for the customer (e.g. 'Approved, production starts Monday' or 'Please clarify gem cut')" class="input input-bordered bg-black border-gray-700 text-white focus:border-gold w-full text-sm" />
                            </div>

                            <div class="flex justify-end gap-3">
                                <button type="submit" name="decision" value="rejected" class="btn bg-transparent border-red-900 text-red-500 hover:bg-red-900 hover:border-red-900 hover:text-white px-6">
                                    <i class="fa-solid fa-xmark mr-2"></i> Reject
                                </button>
                                
                                <button type="submit" name="decision" value="approved" class="btn bg-gold text-black border-none hover:bg-white px-8 font-bold">
                                    <i class="fa-solid fa-check mr-2"></i> Approve Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>