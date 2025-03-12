@extends('layouts.sidebar')

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/style_order.css') }}">

@section('content')
<h1> ORDER MANAGEMENT</h1>

<div class="order-container">
    <!-- Top Bar: Search Bar + Shop Status -->
    <div class="top-bar">
        </div>
        <div class="search-container">
            <input type="text" id="search" placeholder="Search product...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <!-- order Table -->
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
        <h2>Order List</h2>
        <table>
        </table>
    </div>
</div>

<!-- Custom JavaScript -->
<script src="{{ asset('js/order.js') }}"></script>

@endsection
