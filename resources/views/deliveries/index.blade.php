@extends('layouts.app')

@section('title', 'Deliveries - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 8px;">
                <i class="fa-solid fa-box-open" style="margin-right: 12px;"></i>Deliveries
            </h1>
            <p style="color: #888; font-size: 14px;">Track incoming jewellery deliveries and manage inventory</p>
        </div>
        <a href="{{ route('deliveries.create') }}" class="btn-gold">
            <i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Record New Delivery
        </a>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('deliveries.index') }}" style="display: grid; grid-template-columns: 1fr 250px 100px 100px; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Search</label>
                <input type="text" name="search" class="input-field" placeholder="Search by item name or supplier" value="{{ request('search') }}">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Supplier</label>
                <select name="supplier_id" class="input-field">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-gold" style="padding: 12px 20px;">
                <i class="fa-solid fa-filter" style="margin-right: 8px;"></i>Filter
            </button>

            @if(request('search') || request('supplier_id'))
                <a href="{{ route('deliveries.index') }}" class="btn-outline-gold" style="padding: 12px 20px; text-align: center;">
                    <i class="fa-solid fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="card">
        @if($deliveries->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Item Name</th>
                        <th>Supplier</th>
                        <th style="width: 100px;">Quantity</th>
                        <th style="width: 120px;">Delivery Date</th>
                        <th style="width: 100px; text-align: center;">Invoice</th>
                        <th style="width: 200px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                        <tr>
                            <td style="color: #d4af37; font-weight: 600;">#{{ $delivery->id }}</td>
                            <td style="font-weight: 600;">{{ $delivery->item_name }}</td>
                            <td>{{ $delivery->supplier->name }}</td>
                            <td>
                                <span style="background: rgba(212, 175, 55, 0.2); color: #d4af37; padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                                    {{ $delivery->quantity }}
                                </span>
                            </td>
                            <td>{{ $delivery->delivery_date->format('M d, Y') }}</td>
                            <td style="text-align: center;">
                                @if($delivery->invoice_image)
                                    <a href="{{ Storage::url($delivery->invoice_image) }}" target="_blank" class="btn-outline-gold" style="padding: 4px 12px; font-size: 12px; display: inline-block;">
                                        <i class="fa-solid fa-file-invoice"></i> View
                                    </a>
                                @else
                                    <span style="color: #666;">N/A</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('deliveries.edit', $delivery) }}" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; display: inline-block; margin-right: 8px;">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </a>
                                <button onclick="confirmDelete({{ $delivery->id }})" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; background: transparent; border-color: #dc3545; color: #dc3545;">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                                <form id="delete-form-{{ $delivery->id }}" action="{{ route('deliveries.destroy', $delivery) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $deliveries->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fa-solid fa-box-open" style="font-size: 64px; color: #444; margin-bottom: 20px;"></i>
                <p style="color: #888; font-size: 18px;">No deliveries found.</p>
                <a href="{{ route('deliveries.create') }}" class="btn-gold" style="display: inline-block; margin-top: 20px;">
                    <i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Record Your First Delivery
                </a>
            </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this delivery? Stock will be adjusted accordingly. This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
