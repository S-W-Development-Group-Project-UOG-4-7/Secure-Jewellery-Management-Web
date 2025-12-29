@extends('layouts.app')

@section('title', 'User Management - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px;">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="btn-gold">Add New User</a>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: grid; grid-template-columns: 1fr 200px 200px 100px; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Search</label>
                <input type="text" name="search" class="input-field" placeholder="Search by name or email" value="{{ request('search') }}">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Role</label>
                <select name="role" class="input-field">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="supplier" {{ request('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                    <option value="delivery" {{ request('role') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Status</label>
                <select name="status" class="input-field">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn-gold">Filter</button>
        </form>
    </div>

    <div class="card">
        @if($users->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td style="font-weight: 600;">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="badge" style="background: rgba(212, 175, 55, 0.3); color: #d4af37;">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->is_active ? 'active' : 'inactive' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; display: inline-block; margin-right: 8px;">Edit</a>
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; margin-right: 8px;">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                @if($user->id !== Auth::id())
                                <button onclick="confirmDelete({{ $user->id }})" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px; background: transparent; border-color: #dc3545; color: #dc3545;">Delete</button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @else
            <p style="color: #888; text-align: center; padding: 40px;">No users found.</p>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
