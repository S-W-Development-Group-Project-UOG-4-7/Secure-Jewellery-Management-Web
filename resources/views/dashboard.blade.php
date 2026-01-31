@extends('layouts.app')

@section('title', 'Dashboard - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 10px;">Dashboard</h1>
    <p style="color: #aaa; margin-bottom: 40px;">Welcome back, {{ Auth::user()->name }}</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 50px;">
        <div class="card" style="padding: 25px;">
            <h3 style="color: #d4af37; margin-bottom: 10px; font-size: 16px;">Total Customers</h3>
            <p style="font-size: 36px; font-weight: 700; margin: 10px 0;">{{ $stats['total_customers'] }}</p>
        </div>

        <div class="card" style="padding: 25px;">
            <h3 style="color: #d4af37; margin-bottom: 10px; font-size: 16px;">Active Customers</h3>
            <p style="font-size: 36px; font-weight: 700; margin: 10px 0; color: #9fff9f;">{{ $stats['active_customers'] }}</p>
        </div>

        <div class="card" style="padding: 25px;">
            <h3 style="color: #d4af37; margin-bottom: 10px; font-size: 16px;">Inactive Customers</h3>
            <p style="font-size: 36px; font-weight: 700; margin: 10px 0; color: #ff9f9f;">{{ $stats['inactive_customers'] }}</p>
        </div>
    </div>

    <div class="card">
        <h2 class="gold-accent" style="margin-bottom: 20px; font-size: 24px;">Recent Customers</h2>

        @if($stats['recent_customers']->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['recent_customers'] as $customer)
                        <tr>
                            <td>#{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>
                                <span class="badge badge-{{ $customer->status }}">{{ ucfirst($customer->status) }}</span>
                            </td>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #888; text-align: center; padding: 40px;">No customers yet.</p>
        @endif

        <div style="margin-top: 25px; text-align: center;">
            <a href="{{ route('customers.index') }}" class="btn-outline-gold">View All Customers</a>
        </div>
    </div>
</div>
@endsection
