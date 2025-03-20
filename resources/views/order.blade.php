@extends('layouts.sidebar')

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/style_order.css') }}">

@section('content')
<h1>ORDER MANAGEMENT</h1>

<div class="order-container">
    <!-- Top Bar: Search Bar -->
    <div class="top-bar">
        <div class="search-container">
            <input type="text" id="search" placeholder="Search product...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <!-- Order Table -->
    <h2>Order List</h2>
    <div class="order-list">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic rows will go here -->
                <tr>
                    <td>#001</td>
                    <td>John Doe</td>
                    <td>123 Main Street</td>
                    <td>Delivered</td>
                    <td>2024-03-19</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Custom JavaScript -->
<script src="{{ asset('js/order.js') }}"></script>

@endsection
