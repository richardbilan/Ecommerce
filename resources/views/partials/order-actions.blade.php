@php
// Example: You can add more action buttons as needed
@endphp
@if($order->status === 'pending')
    <button class="btn btn-primary accept-btn" type="button" data-order-id="{{ $order->id }}">
        <i class="fas fa-check"></i> Accept Order
    </button>
@endif
@if($order->status === 'processing')
    <button class="btn btn-warning prepare-btn" type="button" data-order-id="{{ $order->id }}">
        <i class="fas fa-utensils"></i> Prepare Order
    </button>
@endif
@if($order->status === 'preparing')
    <button class="btn btn-info deliver-btn" type="button" data-order-id="{{ $order->id }}">
        <i class="fas fa-truck"></i> Deliver Order
    </button>
@endif
@if($order->status === 'delivering')
    <button class="btn btn-success complete-btn" type="button" data-order-id="{{ $order->id }}">
        <i class="fas fa-check-double"></i> Complete
    </button>
@endif
<button class="btn btn-secondary view-btn" type="button" data-order-id="{{ $order->id }}">
    <i class="fas fa-eye"></i> View
</button>
