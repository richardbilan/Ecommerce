<table>
    <thead>
        <tr>
            <th>Order Details</th>
            <th>Customer Info</th>
            <th>Items</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td>
                    <span class="font-semibold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span><br>
                    {{ $order->created_at->format('M d, Y h:i A') }}
                </td>
                <td>
                    <div class="font-medium">{{ $order->user_name }}</div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $order->delivery_address }}
                    </div>
                </td>
                <td>
                    @if(is_array($order->items))
                        @foreach($order->items as $item)
                            {{ $item['name'] }} x{{ $item['quantity'] }}<br>
                        @endforeach
                    @else
                        {{ $order->items }}
                    @endif
                </td>
                <td>
                    ₱{{ number_format($order->total_amount, 2) }}<br>
                    <span class="text-xs text-gray-500">{{ $order->payment_method }}</span>
                </td>
                <td>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    @include('partials.order-actions', ['order' => $order])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
