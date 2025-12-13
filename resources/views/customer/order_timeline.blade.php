@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white py-20 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-10 border-b border-gray-800 pb-6">
            <h2 class="text-3xl font-serif text-white">Order #{{ $order->id }}</h2>
            <div class="badge {{ $order->status === 'approved' ? 'badge-success' : 'badge-warning' }} badge-lg text-white">
                {{ ucfirst($order->status) }}
            </div>
        </div>

        <ul class="timeline timeline-vertical">
            <li>
                <div class="timeline-start bg-gray-900 p-2 rounded border border-yellow-500 text-white">Order Submitted</div>
                <div class="timeline-middle"><i class="fa-solid fa-circle-check text-yellow-500"></i></div>
                <hr class="bg-yellow-500"/>
            </li>
            <li>
                <hr class="{{ $order->status !== 'pending' ? 'bg-yellow-500' : 'bg-gray-800' }}"/>
                <div class="timeline-middle"><i class="fa-solid {{ $order->status !== 'pending' ? 'fa-circle-check text-yellow-500' : 'fa-circle text-gray-600' }}"></i></div>
                <div class="timeline-end bg-gray-900 p-2 rounded border border-gray-700 text-gray-300">
                    Manager Review
                    @if($order->manager_comments) <br><span class="text-xs text-yellow-500 italic">"{{ $order->manager_comments }}"</span> @endif
                </div>
                <hr class="{{ $order->status === 'approved' ? 'bg-yellow-500' : 'bg-gray-800' }}"/>
            </li>
            <li>
                <hr class="{{ $order->status === 'approved' ? 'bg-yellow-500' : 'bg-gray-800' }}"/>
                <div class="timeline-start bg-gray-900 p-2 rounded border border-gray-700 text-gray-300">Production Phase</div>
                <div class="timeline-middle"><i class="fa-solid {{ $order->status === 'approved' ? 'fa-hammer text-yellow-500' : 'fa-circle text-gray-600' }}"></i></div>
            </li>
        </ul>
    </div>
</div>
@endsection