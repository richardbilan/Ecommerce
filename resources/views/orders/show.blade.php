@extends('layouts.sidebar')

@section('content')
<div class="order-container">
    <div class="header">
        <div class="flex justify-between items-center">
            <h1>Order Details #{{ sprintf('%06d', $order->id) }}</h1>
            <div class="flex gap-4">
                <button class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
                <button class="btn btn-secondary" onclick="window.location.href='/admin/orders/{{ $order->id }}/pdf'">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            </div>
        </div>
        <p class="text-gray-500">Created on {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>

    <div class="grid grid-cols-2 gap-6 mt-6">
        <!-- Customer Information -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-gray-500">Name</label>
                    <p class="font-medium">{{ $order->user_name }}</p>
                </div>
                <div>
                    <label class="text-gray-500">Delivery Location</label>
                    <p class="font-medium">{{ $order->location }}</p>
                </div>
                <div>
                    <label class="text-gray-500">Payment Method</label>
                    <p class="font-medium">{{ $order->payment_method }}</p>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Order Status</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-gray-500">Current Status</label>
                    <span class="status-badge status-{{ $order->status }} ml-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <label class="text-gray-500">Last Updated</label>
                    <p class="font-medium">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white p-6 rounded-lg shadow-sm col-span-2">
            <h2 class="text-lg font-semibold mb-4">Order Items</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Item</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($order->items)
                            @foreach($order->items as $item)
                            <tr>
                                <td class="py-2">{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td class="text-right">₱{{ number_format($item['price'], 2) }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="py-2">{{ $order->name }}</td>
                                <td class="text-center">{{ $order->quantity }}</td>
                                <td class="text-right">₱{{ number_format($order->price, 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="border-t">
                            <td colspan="2" class="py-2 font-semibold">Total Amount</td>
                            <td class="text-right font-semibold">₱{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-end gap-4">
        <button class="btn btn-secondary" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </button>
        @if($order->status === 'pending')
        <button class="btn btn-primary" onclick="confirmOrder('{{ $order->id }}')">
            <i class="fas fa-check"></i> Accept Order
        </button>
        @endif
    </div>
</div>

<!-- Toast Messages -->
<div id="toast-container"></div>
@endsection

@push('scripts')
<script>
function confirmOrder(orderId) {
    if (!confirm('Are you sure you want to accept this order?')) return;

    fetch(`/admin/orders/${orderId}/confirm`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Order accepted successfully!', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('Failed to accept order.', 'error');
        }
    })
    .catch(error => {
        showToast('An error occurred.', 'error');
        console.error('Error:', error);
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    const container = document.getElementById('toast-container');
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endpush 