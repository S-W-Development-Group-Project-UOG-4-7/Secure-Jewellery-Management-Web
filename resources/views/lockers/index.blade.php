@extends('layouts.app')

@section('title', 'Lockers - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px;">Locker Management</h1>
        <a href="{{ route('lockers.create') }}" class="btn-gold">Add New Locker</a>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('lockers.index') }}" style="display: grid; grid-template-columns: 1fr 200px 100px; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Search</label>
                <input type="text" name="search" class="input-field" placeholder="Search by locker number or location" value="{{ request('search') }}">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Status</label>
                <select name="status" class="input-field">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <button type="submit" class="btn-gold">Filter</button>
        </form>
    </div>

    <div class="card">
        @if($lockers->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Locker Number</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lockers as $locker)
                        <tr>
                            <td>#{{ $locker->id }}</td>
                            <td style="font-weight: 600; color: #d4af37;">{{ $locker->locker_number }}</td>
                            <td>{{ $locker->location }}</td>
                            <td>
                                <span class="badge badge-{{ $locker->status == 'available' ? 'active' : 'inactive' }}">
                                    {{ ucfirst($locker->status) }}
                                </span>
                            </td>
                            <td>{{ $locker->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('lockers.edit', $locker) }}" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; display: inline-block; margin-right: 8px;">Edit</a>
                                <button onclick="confirmDelete({{ $locker->id }})" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; background: transparent; border-color: #dc3545; color: #dc3545;">Delete</button>
                                <form id="delete-form-{{ $locker->id }}" action="{{ route('lockers.destroy', $locker) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $lockers->links() }}
            </div>
        @else
            <p style="color: #888; text-align: center; padding: 40px;">No lockers found.</p>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this locker? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
