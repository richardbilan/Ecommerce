@extends('layouts.sidebar')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/style_promotions.css') }}">

<h1>PROMOTIONS OR DISCOUNTS</h1>
<div class="top-bar">
    <div class="search-container">
        <input type="text" id="search" placeholder="Search product...">
        <i class="fas fa-search"></i>
    </div>
</div>

<div class="inventory-container">
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
                @foreach($promotions as $promo)
                <tr id="promoRow_{{ $promo->id }}">
                    <td>{{ $promo->code_name }}</td>
                    <td>{{ $promo->discount }}%</td>
                    <td>{{ date('m/d/Y', strtotime($promo->expiration_date)) }}</td>
                    <td>{{ $promo->status }}</td>
                    <td>
                        <i class="fas fa-edit edit-icon" onclick="editPromo({{ $promo->id }}, '{{ $promo->code_name }}', {{ $promo->discount }}, '{{ $promo->expiration_date }}', '{{ $promo->status }}')"></i>
                        <i class="fas fa-trash delete-icon" onclick="deletePromo({{ $promo->id }})"></i>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="addPromoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle">Add New Discounts/Promo</h3>
        <form id="addPromoForm">
            <input type="hidden" id="promoId">
            <input type="text" id="promoName" placeholder="Code Name" required>
            <input type="number" id="promoDiscount" placeholder="Discount (%)" required>
            <input type="date" id="promoDate" required>
            <select id="promoStatus">
                <option value="Active">Active</option>
                <option value="Expired">Expired</option>
            </select>
            <button type="submit" class="btn-add">Save</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/inventory_promo.js') }}"></script>
@endsection
