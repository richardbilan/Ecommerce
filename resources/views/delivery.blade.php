<!-- dashboard.blade.php -->
@extends('layouts.sidebar')
<link rel="stylesheet" href="{{ asset('css/style_delivery.css') }}">

@section('content')
    <h1>DELIVERY MANAGEMENT</h1>
    <div class="inventory-container">
    <div class="summary">
        <div class="card">Total Deliveries <br> <span>{{ $statistics['total_deliveries'] }}</span></div>
        <div class="card">Total Revenue <br> <span>₱{{ number_format($statistics['total_revenue'], 2) }}</span></div>
        <div class="card">On Progress <br> <span>{{ $statistics['on_progress'] }}</span></div>
        <div class="card">Successful <br> <span>{{ $statistics['successful'] }}</span></div>
        <div class="card">Canceled Orders <br> <span>{{ $statistics['canceled'] }}</span></div>
        <div class="card">Refund Request <br> <span>{{ $statistics['refund_request'] }}</span></div>
    </div>
    <!-- Inventory Table -->
    <div class="inventory-list">
        <div class="top-bar">
        <h2>Delivered Orders</h2>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search order...">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <table id="orderTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Date Delivered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveredOrders as $order)
                    <tr class="order-row">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user_name }}</td>
                        <td>{{ $order->location }}</td>
                        <td>
                            <span class="status-badge status-delivered">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->delivered_at ? $order->delivered_at->format('M d, Y h:i A') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr id="noOrdersRow">
                        <td colspan="5" class="text-center">No delivered orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const orderTable = document.getElementById('orderTable');
    const orderRows = document.getElementsByClassName('order-row');
    const noOrdersRow = document.getElementById('noOrdersRow');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let hasVisibleRows = false;

        Array.from(orderRows).forEach(row => {
            const orderId = row.cells[0].textContent.toLowerCase();
            const customerName = row.cells[1].textContent.toLowerCase();
            const address = row.cells[2].textContent.toLowerCase();
            const date = row.cells[4].textContent.toLowerCase();

            if (orderId.includes(searchTerm) || 
                customerName.includes(searchTerm) || 
                address.includes(searchTerm) || 
                date.includes(searchTerm)) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (noOrdersRow) {
            if (!hasVisibleRows && orderRows.length > 0) {
                if (!document.getElementById('noResultsRow')) {
                    const tbody = orderTable.querySelector('tbody');
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'noResultsRow';
                    noResultsRow.innerHTML = '<td colspan="5" class="text-center">No matching orders found</td>';
                    tbody.appendChild(noResultsRow);
                }
            } else {
                const noResultsRow = document.getElementById('noResultsRow');
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        }
    });
});
</script>
    
@endsection

