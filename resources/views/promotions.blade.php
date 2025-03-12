<!-- dashboard.blade.php -->
@extends('layouts.sidebar')
<link rel="stylesheet" href="{{ asset('css/style_promotions.css') }}">

@section('content')
    <h1>PROMOTIONS OR DISCOUNTS</h1>
    <div class="top-bar">
        <div class="search-container">
            <input type="text" id="search" placeholder="Search product...">
                <i class="fas fa-search"></i>
            </div>
        </div>
    <div class="inventory-container">
    <!-- Inventory Table -->
    <div class="inventory-list">
        <h2>Promo or Discounts <i class="fas fa-plus add-icon" onclick="openModal()"></i></h2>
        <table>
            <thead>
                <tr>
                    <th>Code Name</th>
                    <th>Discount</th>
                    <th>Expiration Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="promoTable">
                <tr>
                    <td>SUPEROHAH</td>
                    <td>10%</td>
                    <td>1/2/2025</td>
                    <td>Active</td>
                    <td>
                        <i class="fas fa-edit edit-icon"></i>
                        <i class="fas fa-trash delete-icon"></i>
                    </td>
                </tr>
                <!-- other rows -->
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Modal for Adding Product -->
<div id="addPromoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Add New Discounts/Promo</h3>
        <form id="addPromoForm">
            <input type="text" id="promoName" placeholder="Code Name" required>
            <input type="text" id="promoDiscount" placeholder="Discount" required>
            <input type="date" id="promoDate" placeholder="Expiration Date" required>
            <select id="Status">
                <option value="In Stock">Active</option>
                <option value="Out of Stock">Expired</option>
            </select>
            <button type="submit" class="btn-add">Add</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/inventory_promo.js') }}"></script>
@endsection


