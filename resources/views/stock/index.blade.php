@extends('layouts.app')

@section('title', 'Stock Dashboard - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 8px;">
            <i class="fa-solid fa-boxes-stacked" style="margin-right: 12px;"></i>Stock Dashboard
        </h1>
        <p style="color: #888; font-size: 14px;">Real-time inventory tracking and management</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 40px;">
        <div class="card" style="padding: 25px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="background: rgba(212, 175, 55, 0.2); padding: 15px; border-radius: 12px;">
                    <i class="fa-solid fa-gem" style="font-size: 28px; color: #d4af37;"></i>
                </div>
                <div>
                    <h3 style="color: #888; margin-bottom: 5px; font-size: 14px;">Total Items</h3>
                    <p style="font-size: 32px; font-weight: 700; margin: 0; color: #d4af37;">{{ $totalItems }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 25px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="background: rgba(159, 255, 159, 0.2); padding: 15px; border-radius: 12px;">
                    <i class="fa-solid fa-boxes-stacked" style="font-size: 28px; color: #9fff9f;"></i>
                </div>
                <div>
                    <h3 style="color: #888; margin-bottom: 5px; font-size: 14px;">Total Quantity</h3>
                    <p style="font-size: 32px; font-weight: 700; margin: 0; color: #9fff9f;">{{ number_format($totalQuantity) }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 25px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="background: rgba(255, 159, 159, 0.2); padding: 15px; border-radius: 12px;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 28px; color: #ff9f9f;"></i>
                </div>
                <div>
                    <h3 style="color: #888; margin-bottom: 5px; font-size: 14px;">Low Stock Items</h3>
                    <p style="font-size: 32px; font-weight: 700; margin: 0; color: #ff9f9f;">{{ $lowStockCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('stock.index') }}" style="display: grid; grid-template-columns: 1fr 150px 150px 100px; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Search Items</label>
                <input type="text" name="search" class="input-field" placeholder="Search by item name" value="{{ request('search') }}">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Low Stock</label>
                <select name="low_stock" class="input-field">
                    <option value="">All Items</option>
                    <option value="1" {{ request('low_stock') == '1' ? 'selected' : '' }}>Low Stock Only</option>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Threshold</label>
                <input type="number" name="threshold" class="input-field" placeholder="10" value="{{ request('threshold', 10) }}" min="1">
            </div>

            <button type="submit" class="btn-gold" style="padding: 12px 20px;">
                <i class="fa-solid fa-filter" style="margin-right: 8px;"></i>Filter
            </button>
        </form>
    </div>

    <div class="card">
        @if($stocks->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Item Name</th>
                        <th style="width: 150px;">Current Stock</th>
                        <th style="width: 180px;">Last Updated</th>
                        <th style="width: 150px;">Updated By</th>
                        <th style="width: 200px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                        <tr id="stock-row-{{ $stock->id }}">
                            <td style="color: #d4af37; font-weight: 600;">#{{ $stock->id }}</td>
                            <td style="font-weight: 600;">{{ $stock->item_name }}</td>
                            <td>
                                <span class="badge {{ $stock->quantity <= 10 ? 'badge-inactive' : 'badge-active' }}" style="font-size: 16px; padding: 8px 16px;">
                                    {{ $stock->quantity }}
                                </span>
                            </td>
                            <td>{{ $stock->last_updated->format('M d, Y H:i') }}</td>
                            <td>{{ $stock->updatedByUser ? $stock->updatedByUser->name : 'System' }}</td>
                            <td style="text-align: center;">
                                <button onclick="openEditModal({{ $stock->id }}, '{{ $stock->item_name }}', {{ $stock->quantity }})" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px;">
                                    <i class="fa-solid fa-edit"></i> Update Qty
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $stocks->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fa-solid fa-boxes-stacked" style="font-size: 64px; color: #444; margin-bottom: 20px;"></i>
                <p style="color: #888; font-size: 18px;">No stock items found.</p>
                <a href="{{ route('deliveries.create') }}" class="btn-gold" style="display: inline-block; margin-top: 20px;">
                    <i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Add Items via Delivery
                </a>
            </div>
        @endif
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content rounded-2xl p-8 max-w-md w-full mx-4" style="background: rgba(20, 20, 20, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(212, 175, 55, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 class="gold-accent" style="font-size: 24px; font-weight: 700; margin: 0;">
                <i class="fa-solid fa-edit" style="margin-right: 10px;"></i>Update Stock Quantity
            </h3>
            <button onclick="closeEditModal()" style="background: none; border: none; color: #888; font-size: 24px; cursor: pointer;">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <form id="editStockForm" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Item Name</label>
                <input type="text" id="modal-item-name" class="input-field" readonly style="background: rgba(40, 40, 40, 0.8);">
            </div>

            <div style="margin-bottom: 25px;">
                <label for="modal-quantity" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">New Quantity</label>
                <input type="number" id="modal-quantity" name="quantity" class="input-field" placeholder="Enter new quantity" required min="0">
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">
                    <i class="fa-solid fa-save" style="margin-right: 8px;"></i>Update Stock
                </button>
                <button type="button" onclick="closeEditModal()" class="btn-outline-gold" style="flex: 1;">
                    <i class="fa-solid fa-times" style="margin-right: 8px;"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
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
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        animation: slideUp 0.4s ease;
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
</style>

<script>
    let currentStockId = null;

    function openEditModal(id, itemName, currentQty) {
        currentStockId = id;
        document.getElementById('modal-item-name').value = itemName;
        document.getElementById('modal-quantity').value = currentQty;
        document.getElementById('editStockForm').action = `/stock/${id}/update`;
        document.getElementById('editModal').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        currentStockId = null;
    }

    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });
</script>
@endsection
