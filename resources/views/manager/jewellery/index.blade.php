<!DOCTYPE html>
<html lang="en" data-theme="black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management | SJM</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Manrope:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { gold: '#D4AF37', dark: '#050505', panel: '#121212' }, fontFamily: { serif: ['Cinzel', 'serif'], sans: ['Manrope', 'sans-serif'] } }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans min-h-screen">

    <div class="navbar fixed top-0 z-50 px-8 py-4 bg-black/90 backdrop-blur-md border-b border-white/10">
        <div class="flex-1">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-serif font-bold text-white hover:text-gold transition-colors">SJM<span class="text-gold">.</span></a>
            <span class="ml-4 text-xs font-mono bg-gold text-black px-2 py-1 rounded">INVENTORY</span>
        </div>
        <div class="flex-none">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-ghost">DASHBOARD</a>
        </div>
    </div>

    <div class="pt-32 pb-20 px-4 max-w-7xl mx-auto">
        
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-serif text-white mb-2">Jewellery Catalog</h1>
                <p class="text-gray-400 text-sm">Manage stock, pricing, and materials.</p>
            </div>
            <button onclick="document.getElementById('add_modal').showModal()" class="btn bg-gold text-black border-none hover:bg-white font-bold px-6">
                <i class="fa-solid fa-plus mr-2"></i> ADD ITEM
            </button>
        </div>

        @if(session('success'))
            <div class="alert bg-green-900/20 border border-green-500 text-green-400 mb-6 rounded-none">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-panel border border-white/10 rounded-xl">
            <table class="table w-full">
                <thead class="bg-black/50 text-gray-400 uppercase text-xs tracking-widest">
                    <tr>
                        <th>Preview</th>
                        <th>Details</th>
                        <th>Material</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td>
                            <div class="w-16 h-16 rounded bg-black border border-white/10 overflow-hidden">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-600"><i class="fa-solid fa-gem"></i></div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="font-bold text-white">{{ $item->name }}</div>
                            <div class="text-xs text-gold uppercase tracking-wider">{{ $item->type }}</div>
                        </td>
                        <td class="text-gray-400 text-sm">{{ $item->material }}</td>
                        <td class="font-mono text-white">${{ number_format($item->price, 2) }}</td>
                        <td>
                            <span class="badge {{ $item->stock_quantity > 0 ? 'badge-success' : 'badge-error' }} badge-outline text-xs">
                                {{ $item->stock_quantity }} Qty
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <button 
                                    onclick="openEditModal('{{ $item->id }}', '{{ addslashes($item->name) }}', '{{ $item->type }}', '{{ $item->material }}', '{{ $item->price }}', '{{ $item->stock_quantity }}')" 
                                    class="btn btn-xs btn-square bg-white/10 text-white hover:bg-gold hover:text-black border-none">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button onclick="openDeleteModal('{{ $item->id }}')" class="btn btn-xs btn-square bg-white/10 text-red-500 hover:bg-red-600 hover:text-white border-none">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-8 text-gray-500">
                            No items found. Click "Add Item" to create inventory.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="add_modal" class="modal backdrop-blur-sm">
        <div class="modal-box bg-panel border border-white/10 rounded-xl p-8 max-w-lg">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-gray-400">✕</button></form>
            <h3 class="font-serif text-2xl text-white mb-6">Add New Item</h3>
            <form action="{{ route('jewellery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="form-control"><label class="label text-xs text-gold">NAME</label><input type="text" name="name" required class="input input-bordered bg-black border-gray-700 text-white focus:border-gold w-full" /></div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control"><label class="label text-xs text-gold">TYPE</label><select name="type" class="select select-bordered bg-black border-gray-700 text-white focus:border-gold w-full"><option>Ring</option><option>Necklace</option><option>Earrings</option><option>Bracelet</option></select></div>
                    <div class="form-control"><label class="label text-xs text-gold">MATERIAL</label><select name="material" class="select select-bordered bg-black border-gray-700 text-white focus:border-gold w-full"><option>Gold</option><option>Silver</option><option>Platinum</option><option>Diamond</option></select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control"><label class="label text-xs text-gold">PRICE ($)</label><input type="number" step="0.01" name="price" required class="input input-bordered bg-black border-gray-700 text-white focus:border-gold w-full" /></div>
                    <div class="form-control"><label class="label text-xs text-gold">STOCK</label><input type="number" name="stock" required class="input input-bordered bg-black border-gray-700 text-white focus:border-gold w-full" /></div>
                </div>
                <div class="form-control"><label class="label text-xs text-gold">IMAGE</label><input type="file" name="image" class="file-input file-input-bordered bg-black border-gray-700 text-white w-full" /></div>
                <button type="submit" class="btn bg-gold text-black hover:bg-white w-full mt-4 font-bold">ADD TO INVENTORY</button>
            </form>
        </div>
    </dialog>

    <dialog id="edit_modal" class="modal backdrop-blur-sm">
        <div class="modal-box bg-panel border border-white/10 rounded-xl p-8 max-w-lg">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-gray-400">✕</button></form>
            <h3 class="font-serif text-2xl text-white mb-6">Edit Item</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-control"><label class="label text-xs text-gold">NAME</label><input type="text" id="edit_name" name="name" class="input input-bordered bg-black border-gray-700 text-white focus:border-gold w-full" /></div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control"><label class="label text-xs text-gold">TYPE</label><select id="edit_type" name="type" class="select select-bordered bg-black border-gray-700 text-white focus:border-gold w-full"><option>Ring</option><option>Necklace</option><option>Earrings</option><option>Bracelet</option></select></div>
                    <div class="form-control"><label class="label text-xs text-gold">MATERIAL</label><select id="edit_material" name="material" class="select select-bordered bg-black border-gray-700 text-white focus:border-gold w-full"><option>Gold</option><option>Silver</option><option>Platinum</option><option>Diamond</option></select></div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control"><label class="label text-xs text-gold">PRICE</label><input type="number" step="0.01" id="edit_price" name="price" class="input input-bordered bg-black border-gray-700 text-white focus:border-gold w-full" /></div>
                    <div class="form-control"><label class="label text-xs text-gold">STOCK</label><input type="number" id="edit_stock" name="stock" class="input input-bordered bg-black border-gray-700 text-white focus:border-gold w-full" /></div>
                </div>
                
                <div class="form-control"><label class="label text-xs text-gold">UPDATE IMAGE</label><input type="file" name="image" class="file-input file-input-bordered bg-black border-gray-700 text-white w-full" /></div>
                
                <button type="submit" class="btn bg-gold text-black hover:bg-white w-full mt-4 font-bold">SAVE CHANGES</button>
            </form>
        </div>
    </dialog>

    <dialog id="delete_modal" class="modal backdrop-blur-sm">
        <div class="modal-box bg-panel border border-red-900/50 rounded-xl p-8 max-w-sm text-center">
            <h3 class="font-serif text-xl text-white mb-2">Confirm Deletion</h3>
            <p class="text-gray-400 text-sm mb-6">Are you sure? This cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="flex gap-2 justify-center">
                    <button type="button" onclick="document.getElementById('delete_modal').close()" class="btn btn-ghost text-gray-400">Cancel</button>
                    <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white border-none">Yes, Delete</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openEditModal(id, name, type, material, price, stock) {
            // Populate Form
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_material').value = material;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_stock').value = stock;
            
            // Set Dynamic Action URL
            document.getElementById('editForm').action = "/inventory/update/" + id;
            
            // Show Modal
            document.getElementById('edit_modal').showModal();
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = "/inventory/delete/" + id;
            document.getElementById('delete_modal').showModal();
        }
    </script>
</body>
</html>