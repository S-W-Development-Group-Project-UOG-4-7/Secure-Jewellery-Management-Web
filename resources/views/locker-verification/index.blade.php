@extends('layouts.app')

@section('title', 'Locker Verification - Secure Jewellery Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px;">Locker Verification</h1>
        <a href="{{ route('locker-verification.before') }}" class="btn-gold">Start New Verification</a>
    </div>

    <div class="card">
        @if($verifications->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Locker</th>
                        <th>Items Count</th>
                        <th>Verified By</th>
                        <th>Status</th>
                        <th>Completed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($verifications as $verification)
                        <tr>
                            <td>#{{ $verification->id }}</td>
                            <td style="font-weight: 600; color: #d4af37;">{{ $verification->locker->locker_number }}</td>
                            <td>{{ $verification->jewelleryItems->count() }}</td>
                            <td>{{ $verification->verifiedBy->name }}</td>
                            <td>
                                @if($verification->status == 'pending')
                                    <span class="badge" style="background: rgba(255, 193, 7, 0.3); color: #ffc107;">Pending</span>
                                @elseif($verification->status == 'pass')
                                    <span class="badge badge-active">Pass</span>
                                @elseif($verification->status == 'fail')
                                    <span class="badge badge-inactive">Fail</span>
                                @else
                                    <span class="badge" style="background: rgba(255, 152, 0, 0.3); color: #ff9800;">Flagged</span>
                                @endif
                            </td>
                            <td>
                                @if($verification->completed_at)
                                    {{ $verification->completed_at->format('M d, Y H:i') }}
                                @else
                                    <span style="color: #888;">In Progress</span>
                                @endif
                            </td>
                            <td>
                                @if($verification->status == 'pending')
                                    <a href="{{ route('locker-verification.after', $verification) }}" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px;">Complete</a>
                                @else
                                    <a href="{{ route('locker-verification.results', $verification) }}" class="btn-outline-gold" style="padding: 6px 16px; font-size: 12px;">View Results</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $verifications->links() }}
            </div>
        @else
            <p style="color: #888; text-align: center; padding: 40px;">No verifications found. Start a new verification to track locker security.</p>
        @endif
    </div>
</div>
@endsection
