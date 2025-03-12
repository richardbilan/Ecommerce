<!-- dashboard.blade.php -->
@extends('layouts.sidebar')
<link rel="stylesheet" href="{{ asset('css/style_delivery.css') }}">

@section('content')
    <h1>DELIVERY MANAGEMENT</h1>
    <div class="inventory-container">
    <div class="summary">
        <div class="card">All Deliveries <br> <span>P 1680</span></div>
        <div class="card">On Progress Deliveries <br> <span>30</span></div>
        <div class="card">Successful <br> <span>5</span></div>
        <div class="card">Canceled Orders <br> <span>20</span></div>
        <div class="card">Refund Request <br> <span>12</span></div>
    </div>
    <!-- Inventory Table -->
    <div class="inventory-list">
        <div class="top-bar">
        <h2>Delivery </h2>
            <div class="search-container">
                <input type="text" id="search" placeholder="Search product...">
                <i class="fas fa-search"></i>
            </div>
        </div>
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
        </table>
    </div>
</div>
    
@endsection

