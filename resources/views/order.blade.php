@extends('layouts.sidebar')

@section('content')
<!-- Add CSRF Token meta tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Pusher -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/style_order.css') }}">

<style>
    /* Center the main order container vertically and horizontally - recommended best practice */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        width: 100%;
        background: #f9f9f9;
    }
    .order-container {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        width: 100%;
        max-width: 1100px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.04);
        background: #fff;
        border-radius: 18px;
        padding: 32px 24px;
    }
    .header, .search-filter-section, .table-container, .pagination {
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    .table-container table {
        margin: 0 auto;
    }
</style>

<div class="order-container">
    <!-- Header Section -->
    <div class="header">
                        <div>
            <h1>Orders</h1>
            <p class="text-gray-500">Last updated: {{ now()->format('M d, Y h:i A') }}</p>
                    </div>
                    
 

    <!-- Search and Filter Bar -->
    <div class="search-filter-section">
        <div class="search-container">
            <input type="text" placeholder="Search orders..." id="orderSearch" class="search-input">
            <i class="fas fa-search"></i>
                    </div>
        <div class="filters">
            <select class="filter-select" id="statusFilter">
                <option value="all">All Orders</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="delivering">Delivering</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select class="filter-select" id="timeFilter">
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
            <select class="filter-select" id="sortFilter">
                <option value="created_at,desc">Latest First</option>
                <option value="created_at,asc">Oldest First</option>
                <option value="total_amount,desc">Highest Amount</option>
                <option value="total_amount,asc">Lowest Amount</option>
            </select>
            </div>
        </div>

  
    <!-- Orders Table -->
    <div class="table-container">
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
                @forelse($orders as $order)
                <tr data-order-id="{{ $order->id }}">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="order-icon">
                                <i class="fas fa-shopping-bag text-primary-light"></i>
                            </div>
                        <div>
                                <div class="font-semibold">#{{ sprintf('%06d', $order->id) }}</div>
                                <div class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="customer-info">
                            <div class="font-medium">{{ $order->user_name }}</div>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $order->delivery_address }}
                            </div>
                    </div>
                    </td>
                    <td>
                        <div class="items-list">
                            @if($order->items)
                                @foreach($order->items as $item)
                                    <div class="item-entry">
                                        <span>{{ $item['name'] }}</span>
                                        <span class="quantity">x{{ $item['quantity'] }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="item-entry">
                                    <span>{{ $order->name }}</span>
                                    <span class="quantity">x{{ $order->quantity }}</span>
                                </div>
                            @endif
                    </div>
                    </td>
                    <td>
                        <div class="amount-info">
                            <div class="font-semibold">₱{{ number_format($order->total_amount, 2) }}</div>
                            <div class="text-sm text-gray-500">{{ $order->payment_method }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($order->status === 'pending')
                                <div class="primary-actions">
                                    <button class="btn btn-secondary view-btn" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>
                                    <button class="btn btn-primary accept-btn" type="button" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-check"></i>
                                        Accept Order
                                    </button>
                                </div>
                            @elseif($order->status === 'processing')
                                <div class="primary-actions">
                                    <button class="btn btn-secondary view-btn" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>
                                    <button class="btn btn-warning prepare-btn" type="button" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-utensils"></i>
                                        Prepare Order
                                    </button>
                                </div>
                            @elseif($order->status === 'preparing')
                                <div class="primary-actions">
                                    <button class="btn btn-secondary view-btn" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>
                                    <button class="btn btn-info delivery-btn" type="button" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-truck"></i>
                                        Out for Delivery
                                    </button>
                                </div>
                            @elseif($order->status === 'delivering')
                                <div class="primary-actions">
                                    <button class="btn btn-secondary view-btn" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>
                                    <button class="btn btn-success complete-btn" type="button" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-check-circle"></i>
                                        Complete Delivery
                                    </button>
                                </div>
                            @elseif($order->status === 'delivered')
                                <div class="primary-actions">
                                    <button class="btn btn-secondary view-btn" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">No orders found</p>
                    </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

  <!-- Pagination -->
<div class="pagination">
    <div class="pagination-info mb-3">
        Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() ?? 0 }} orders
                    </div>
    <div class="pagination-links">
        @if ($orders->hasPages())
            <div class="pagination-controls">
                <!-- Previous Button -->
                <a href="{{ $orders->previousPageUrl() }}"
                    class="pagination-btn prev-btn {{ $orders->onFirstPage() ? 'disabled' : '' }}"
                    {{ $orders->onFirstPage() ? 'disabled' : '' }}>
                    <i class="fas fa-angle-left"></i>
                </a>

                <!-- Page Numbers -->
                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    <a href="{{ $url }}" 
                        class="pagination-page {{ $page == $orders->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                <!-- Next Button -->
                <a href="{{ $orders->nextPageUrl() }}"
                    class="pagination-btn next-btn {{ !$orders->hasMorePages() ? 'disabled' : '' }}"
                    {{ !$orders->hasMorePages() ? 'disabled' : '' }}>
                    <i class="fas fa-angle-right"></i>
                </a>
            </div>
        @endif
    </div>
</div>


<!-- Toast Messages -->
<div id="toast-container"></div>
@endsection

@push('styles')
<style>
.order-container {
    padding: 1.5rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin: 1rem;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.header h1 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.search-filter-section {
    background: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}

.search-container {
    flex: 1;
    min-width: 200px;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 0.5rem 1rem 0.5rem 2.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
}

.filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-select {
    padding: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    min-width: 150px;
}

.table-container {
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f9fafb;
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
}

td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending { background: #FEF3C7; color: #92400E; }
.status-confirmed { background: #DEF7EC; color: #03543F; }
.status-delivering { background: #E1EFFE; color: #1E429F; }
.status-delivered { background: #D1FAE5; color: #065F46; }
.status-cancelled { background: #FEE2E2; color: #991B1B; }

.action-buttons {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.primary-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-icon {
    padding: 0.5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    color: #666;
    transition: color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 4px;
}

.btn-icon:hover {
    background-color: #f3f4f6;
    color: #333;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    min-width: 160px;
    z-index: 50;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    color: #374151;
    text-decoration: none;
    transition: background-color 0.2s;
}

.dropdown-menu a:hover {
    background-color: #f3f4f6;
}

.dropdown-menu a.text-red-600 {
    color: #dc2626;
}

.dropdown-menu a.text-red-600:hover {
    background-color: #fef2f2;
}
.pagination {
    background: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.pagination-info {
    text-align: center;
    color: #666;
    font-size: 0.9rem;
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.75rem;
}

/* Pagination Buttons: Prev & Next */
.pagination-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e5e7eb;
    background-color: white;
    color: #6F4E37;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 1rem;
}

.pagination-btn:hover:not(.disabled) {
    border-color: #6F4E37;
    background-color: #6F4E37;
    color: white;
    transform: translateY(-2px);
}

.pagination-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    border-color: #e5e7eb;
    color: #9ca3af;
}

/* Page Numbers */
.pagination-page {
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.75rem;
    border-radius: 8px;
    font-weight: 500;
    color: #4b5563;
    background-color: white;
    border: 2px solid #e5e7eb;
    transition: all 0.2s ease;
}

.pagination-page:hover:not(.active) {
    border-color: #6F4E37;
    color: #6F4E37;
    transform: translateY(-2px);
}

.pagination-page.active {
    background-color: #6F4E37;
    border-color: #6F4E37;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(111, 78, 55, 0.1), 0 2px 4px -1px rgba(111, 78, 55, 0.06);
}

/* Toast styling if you're using notifications */
#toast-container {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    z-index: 50;
}

.toast {
    background: white;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

@media (max-width: 768px) {
    .statistics-grid {
        grid-template-columns: 1fr;
    }
    
    .search-filter-section {
        flex-direction: column;
    }
    
    .filters {
        width: 100%;
    }
    
    .action-buttons {
        flex-wrap: wrap;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Accept Order
    $(document).on('click', '.accept-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const orderId = $(this).data('order-id');
        alert('Accept Order clicked for order: ' + orderId);
        if (!confirm('Accept this order?')) return;
        updateOrderStatus(orderId, 'processing');
    });
    // Prepare Order
    $(document).on('click', '.prepare-btn', function() {
        const orderId = $(this).data('order-id');
        if (!confirm('Mark as preparing?')) return;
        updateOrderStatus(orderId, 'preparing');
    });
    // Out for Delivery
    $(document).on('click', '.delivery-btn', function() {
        const orderId = $(this).data('order-id');
        if (!confirm('Mark as out for delivery?')) return;
        updateOrderStatus(orderId, 'delivering');
    });
    // Delivered
    $(document).on('click', '.complete-btn', function() {
        const orderId = $(this).data('order-id');
        if (!confirm('Mark as delivered?')) return;
        updateOrderStatus(orderId, 'delivered');
    });
    function updateOrderStatus(orderId, status) {
        $.ajax({
            url: `/orders/${orderId}/status`,
            method: 'POST',
            data: { status: status },
            success: function(res) {
                if (res.success) {
                    alert('Order status updated!');
                    location.reload();
                } else {
                    alert(res.message || 'Failed to update order.');
                }
            },
            error: function(xhr) {
                alert('Server error: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    }

    // Filter functionality
    $('#statusFilter, #sortFilter, #timeFilter').on('change', function() {
        const status = $('#statusFilter').val();
        const sort = $('#sortFilter').val();
        const time = $('#timeFilter').val();
        $.ajax({
            url: window.location.pathname,
            type: 'GET',
            data: {
                status: status,
                sort: sort,
                time_filter: time
            },
            success: function(data) {
                // Replace the order table with new data (requires partial rendering support)
                const newTable = $(data).find('.table-container').html();
                $('.table-container').html(newTable);
                // Optionally, update pagination and info
            },
            error: function(xhr) {
                alert('Failed to filter orders: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    });

    // Add search input handler for live search
    $('#orderSearch').on('input', function() {
        const query = $(this).val();
        const status = $('#statusFilter').val();
        const sort = $('#sortFilter').val();
        const time = $('#timeFilter').val();
        $.ajax({
            url: window.location.pathname,
            type: 'GET',
            data: {
                search: query,
                status: status,
                sort: sort,
                time_filter: time
            },
            success: function(res) {
                $('.table-container').html(res.table);
                $('.pagination').html(res.pagination);
            },
            error: function(xhr) {
                alert('Search failed: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    });

    // Get user location using browser geolocation and set to hidden input on order form
    $(document).ready(function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                // Use Nominatim to get address
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        const location = data.display_name;
                        // Set location hidden input
                        $("input[name='location']").val(location);
                        // Optionally display location to user
                        $("#detectedLocation").text(location);
                    })
                    .catch(() => {
                        $("#detectedLocation").text('Unable to fetch address');
                    });
            }, function() {
                $("#detectedLocation").text('Geolocation permission denied');
            });
        } else {
            $("#detectedLocation").text('Geolocation not supported');
        }
    });
});
</script>
@endpush
