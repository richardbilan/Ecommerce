@extends('layouts.sidebar')

@section('content')
<div class="order-summary-container" style="max-width:600px;margin:40px auto;background:#fff;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.06);padding:36px 32px;">
    <h2 class="text-2xl font-bold mb-4 text-center">Order Summary</h2>
    <div class="mb-6 text-center">
        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold text-lg">Payment Successful!</span>
    </div>
    <div class="mb-4">
        <strong>Order ID:</strong> #{{ $order->id }}<br>
        <strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}<br>
        <strong>Status:</strong> <span class="font-semibold capitalize">{{ $order->status }}</span>
    </div>
    <div class="mb-4">
        <strong>Customer:</strong> {{ $order->user_name }}<br>
        <strong>Delivery Address:</strong> {{ $order->delivery_address }}
    </div>
    <div class="mb-4">
        <strong>Payment Method:</strong> GCash<br>
        <strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}
    </div>
    @if($order->items)
    <div class="mb-4">
        <strong>Items:</strong>
        <ul class="list-disc ml-6">
            @foreach($order->items as $item)
                <li>{{ $item['name'] ?? '' }} x{{ $item['quantity'] ?? 1 }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="text-center mt-8">
        <a href="{{ route('deliveryuser') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Go to My Orders</a>
    </div>
</div>
@endsection
