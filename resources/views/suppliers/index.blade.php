@extends('layouts.app')

@section('title', 'Suppliers - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 8px;">
                <i class="fa-solid fa-truck-fast" style="margin-right: 12px;"></i>Suppliers
            </h1>
            <p style="color: #888; font-size: 14px;">Manage your jewellery suppliers and track reliability</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn-gold">
            <i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Add New Supplier
        </a>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('suppliers.index') }}" style="display: flex; gap: 15px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Search</label>
                <input type="text" name="search" class="input-field" placeholder="Search by name, email, or phone" value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn-gold" style="padding: 12px 30px;">
                <i class="fa-solid fa-search" style="margin-right: 8px;"></i>Search
            </button>
            @if(request('search'))
                <a href="{{ route('suppliers.index') }}" class="btn-outline-gold" style="padding: 12px 30px;">
                    <i class="fa-solid fa-times" style="margin-right: 8px;"></i>Clear
                </a>
            @endif
        </form>
    </div>

    <div class="card">
        @if($suppliers->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th style="width: 150px;">Joined</th>
                        <th style="width: 200px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td style="color: #d4af37; font-weight: 600;">#{{ $supplier->id }}</td>
                            <td style="font-weight: 600;">{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact_email }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $supplier->address ?? 'N/A' }}
                            </td>
                            <td>{{ $supplier->created_at->format('M d, Y') }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; display: inline-block; margin-right: 8px;">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </a>
                                <button onclick="confirmDelete({{ $supplier->id }})" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; background: transparent; border-color: #dc3545; color: #dc3545;">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                                <form id="delete-form-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $suppliers->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fa-solid fa-truck-fast" style="font-size: 64px; color: #444; margin-bottom: 20px;"></i>
                <p style="color: #888; font-size: 18px;">No suppliers found.</p>
                <a href="{{ route('suppliers.create') }}" class="btn-gold" style="display: inline-block; margin-top: 20px;">
                    <i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Add Your First Supplier
                </a>
            </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this supplier? All associated deliveries will also be deleted. This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
