@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white py-20 px-8">
    <h1 class="text-4xl font-serif text-white mb-8">Pending Approvals <span class="text-yellow-500">.</span></h1>

    <div class="grid grid-cols-1 gap-6">
        @foreach($pendingOrders as $order)
        <div class="p-6 rounded-xl border border-gray-800 bg-gray-900 flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-48 h-48 bg-black rounded-lg overflow-hidden flex-shrink-0">
                @if($order->design_image_path)
                    <img src="{{ asset('storage/' . $order->design_image_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-full text-gray-600 text-xs">No Image</div>
                @endif
            </div>

            <div class="flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-yellow-500">Order #{{ $order->id }} - {{ $order->jewellery_type }}</h3>
                        <p class="text-sm text-gray-400">Client: {{ $order->user->username }}</p>
                    </div>
                    <div class="badge badge-warning">Pending</div>
                </div>

                <div class="bg-black p-4 rounded border border-gray-800 mb-4">
                    <p class="text-sm text-gray-300 italic">"{{ $order->description }}"</p>
                    <p class="text-xs text-yellow-500 mt-2 font-bold">Budget: ${{ number_format($order->budget, 2) }}</p>
                </div>

                <form action="{{ route('manager.approve', $order->id) }}" method="POST">
                    @csrf
                    <textarea name="manager_comments" class="textarea textarea-bordered w-full bg-black border-gray-700 text-white text-xs mb-3" placeholder="Manager comments..."></textarea>
                    <div class="flex gap-3">
                        <button type="submit" name="decision" value="approved" class="btn btn-sm bg-green-600 text-white border-none">Approve</button>
                        <button type="submit" name="decision" value="rejected" class="btn btn-sm bg-red-600 text-white border-none">Reject</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection