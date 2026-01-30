@extends('layouts.app')

@section('title', 'Jewellery Management - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px;">Jewellery Management</h1>
        <a href="{{ route('jewellery.create') }}" class="btn-gold">Add New Item</a>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('jewellery.index') }}" style="display: grid; grid-template-columns: 1fr 200px 200px 100px; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Search</label>
                <input type="text" name="search" class="input-field" placeholder="Search by name, type, or metal" value="{{ request('search') }}">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Type</label>
                <select name="type" class="input-field">
                    <option value="">All Types</option>
                    <option value="Ring" {{ request('type') == 'Ring' ? 'selected' : '' }}>Ring</option>
                    <option value="Necklace" {{ request('type') == 'Necklace' ? 'selected' : '' }}>Necklace</option>
                    <option value="Bracelet" {{ request('type') == 'Bracelet' ? 'selected' : '' }}>Bracelet</option>
                    <option value="Earrings" {{ request('type') == 'Earrings' ? 'selected' : '' }}>Earrings</option>
                    <option value="Pendant" {{ request('type') == 'Pendant' ? 'selected' : '' }}>Pendant</option>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Status</label>
                <select name="status" class="input-field">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="in_locker" {{ request('status') == 'in_locker' ? 'selected' : '' }}>In Locker</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>

            <button type="submit" class="btn-gold">Filter</button>
        </form>
    </div>

    <div class="card">
        @if($jewellery->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Metal</th>
                        <th>Weight (g)</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jewellery as $item)
                        <tr>
                            <td>#{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->metal }}</td>
                            <td>{{ number_format($item->weight, 2) }}</td>
                            <td>${{ number_format($item->value, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $item->status == 'available' ? 'active' : 'inactive' }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('jewellery.edit', $item) }}" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; display: inline-block; margin-right: 8px;">Edit</a>
                                <button onclick="confirmDelete({{ $item->id }})" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; background: transparent; border-color: #dc3545; color: #dc3545;">Delete</button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('jewellery.destroy', $item) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $jewellery->links() }}
            </div>
        @else
            <p style="color: #888; text-align: center; padding: 40px;">No jewellery items found.</p>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this jewellery item? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
