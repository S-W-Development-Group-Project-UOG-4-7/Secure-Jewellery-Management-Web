@extends('layouts.app')

@section('title', 'Edit Delivery - Secure Jewellery Management')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 8px;">
            <i class="fa-solid fa-edit" style="margin-right: 12px;"></i>Edit Delivery
        </h1>
        <p style="color: #888; font-size: 14px;">Update delivery information and adjust stock</p>
    </div>

    <div class="card">
        <form action="{{ route('deliveries.update', $delivery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="supplier_id" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                    <i class="fa-solid fa-truck-fast" style="margin-right: 6px;"></i>Supplier
                </label>
                <select id="supplier_id" name="supplier_id" class="input-field" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $delivery->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }} - {{ $supplier->contact_email }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="item_name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                        <i class="fa-solid fa-gem" style="margin-right: 6px;"></i>Item Name
                    </label>
                    <input type="text" id="item_name" name="item_name" class="input-field" placeholder="Enter item name" required value="{{ old('item_name', $delivery->item_name) }}">
                    @error('item_name')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="quantity" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                        <i class="fa-solid fa-hashtag" style="margin-right: 6px;"></i>Quantity
                    </label>
                    <input type="number" id="quantity" name="quantity" class="input-field" placeholder="Enter quantity" required min="1" value="{{ old('quantity', $delivery->quantity) }}">
                    @error('quantity')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="delivery_date" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                        <i class="fa-solid fa-calendar" style="margin-right: 6px;"></i>Delivery Date
                    </label>
                    <input type="date" id="delivery_date" name="delivery_date" class="input-field" required value="{{ old('delivery_date', $delivery->delivery_date->format('Y-m-d')) }}">
                    @error('delivery_date')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="invoice_image" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                        <i class="fa-solid fa-file-invoice" style="margin-right: 6px;"></i>Invoice Image
                    </label>
                    <input type="file" id="invoice_image" name="invoice_image" class="input-field" accept="image/*,application/pdf" style="padding: 10px;">
                    <small style="color: #888; display: block; margin-top: 5px;">
                        @if($delivery->invoice_image)
                            Current: <a href="{{ Storage::url($delivery->invoice_image) }}" target="_blank" style="color: #d4af37; text-decoration: underline;">View Invoice</a>
                        @else
                            Max 5MB (JPG, PNG, PDF)
                        @endif
                    </small>
                    @error('invoice_image')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                <p style="color: #d4af37; font-size: 14px; margin: 0;">
                    <i class="fa-solid fa-info-circle" style="margin-right: 8px;"></i>
                    <strong>Note:</strong> Updating this delivery will automatically adjust the stock quantity based on the changes made.
                </p>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">
                    <i class="fa-solid fa-save" style="margin-right: 8px;"></i>Update Delivery & Adjust Stock
                </button>
                <a href="{{ route('deliveries.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">
                    <i class="fa-solid fa-times" style="margin-right: 8px;"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection
