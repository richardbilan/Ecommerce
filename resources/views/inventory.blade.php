@extends('layouts.sidebar')

@section('content')

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/style_inventory.css') }}">

<h1>INVENTORY MANAGEMENT</h1>

<div class="inventory-container">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="shop-status">
            <label for="toggleShop">Shop Status</label>
            <input type="checkbox" id="toggleShop" class="toggle" checked>
            <span class="status-label">OPEN</span>
        </div>

      <!-- Include jQuery first! -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Search input -->
<div class="search-container">
    <input type="text" id="search" placeholder="Search product...">
    <i class="fas fa-search"></i>
</div>

<!-- Modal for search results -->
<div id="resultsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="product-results"></div>
    </div>
</div>
</div>
    </div>

   <!-- Add Product Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAddModalBtn">&times;</span>
        <h3>Add Product</h3>

        <form id="addProductForm">
            @csrf
            <label for="addProductName">Product Name</label>
            <input type="text" id="addProductName" name="product_name" placeholder="Product Name" required>

            <label for="addProductCategory">Category</label>
            <select id="addProductCategory" name="category" required>
                <option disabled selected>Select Category</option>
                <option value="Coffee Series">Coffee Series</option>
                <option value="Non-Coffee Series">Non-Coffee Series</option>
            </select>

            <label for="addPriceHot">Price (Hot)</label>
            <input type="number" id="addPriceHot" name="price_hot" step="0.01" placeholder="Price Hot">

            <label for="addPriceIced">Price (Iced)</label>
            <input type="number" id="addPriceIced" name="price_iced" step="0.01" placeholder="Price Iced">

            <label for="addProductAvailability">Availability</label>
            <select id="addProductAvailability" name="availability" required>
                <option disabled selected>Select Availability</option>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>

            <button type="submit" class="btn-add">Add Product</button>
        </form>
    </div>
</div>


    <!-- Inventory Table -->
<!-- Inventory Table -->
<div class="inventory-list">
    <h2>
        Inventory
        <i class="fas fa-plus add-icon" id="openModalBtn"></i>
    </h2>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price (Hot)</th>
                <th>Price (Iced)</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="inventoryTable">
            @foreach($products as $product)
            <tr data-id="{{ $product->id }}">
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category }}</td>
                <td>
                    @if(!is_null($product->price_hot))
                        P {{ number_format($product->price_hot, 2) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(!is_null($product->price_iced))
                        P {{ number_format($product->price_iced, 2) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $product->availability }}</td>
                <td>
                    <button class="edit-btn" data-id="{{ $product->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="delete-btn" data-id="{{ $product->id }}" title="Delete"><i class="fas fa-trash"></i></button>

                </td>
            </tr>
            @endforeach

            @if($products->isEmpty())
            <tr>
                <td colspan="6">No products found.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>


<!-- Edit Product Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditModalBtn">&times;</span>
        <h3>Edit Product</h3>

        <form id="editProductForm">
            @csrf
            <input type="hidden" id="editProductId" name="id">

            <label for="editProductName">Product Name</label>
            <input type="text" id="editProductName" name="product_name" placeholder="Product Name" required>

            <label for="editProductCategory">Category</label>
            <select id="editProductCategory" name="category" required>
                <option disabled selected>Select Category</option>
                <option value="Coffee Series">Coffee Series</option>
                <option value="Non Coffee Series">Non Coffee Series</option>
            </select>

            <label for="editProductPrice">Price</label>
            <input type="number" id="editProductPrice" name="price" step="0.01" placeholder="Price" required>

            <label for="editProductAvailability">Availability</label>
            <select id="editProductAvailability" name="availability" required>
                <option disabled selected>Select Availability</option>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>

            <button type="submit" class="btn-add">Update</button>
        </form>
    </div>
</div>

<!-- Custom JS -->
<script src="{{ asset('js/inventory.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const editProductModal = document.getElementById('editProductModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const addProductModal = document.getElementById('addProductModal');
    const closeAddModalBtn = document.getElementById('closeAddModalBtn');

    // Open Add Product Modal
    document.getElementById('openModalBtn').addEventListener('click', () => {
        addProductModal.style.display = 'block';
    });

    // Close Add Product Modal
    closeAddModalBtn.addEventListener('click', () => {
        addProductModal.style.display = 'none';
    });

    // Open Edit Product Modal
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            const productId = row.dataset.id;
            const productName = row.children[0].textContent.trim();
            const category = row.children[1].textContent.trim();
            const price = row.children[2].textContent.replace('P ', '').trim();
            const availability = row.children[3].textContent.trim();

            // Fill modal form with row data
            document.getElementById('editProductId').value = productId;
            document.getElementById('editProductName').value = productName;
            document.getElementById('editProductCategory').value = category;
            document.getElementById('editProductPrice').value = parseFloat(price);
            document.getElementById('editProductAvailability').value = availability;

            // Show modal
            editProductModal.style.display = 'block';
        });
    });

    // Close Edit Product Modal
    closeEditModalBtn.addEventListener('click', () => {
        editProductModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === editProductModal) {
            editProductModal.style.display = 'none';
        }
        if (e.target === addProductModal) {
            addProductModal.style.display = 'none';
        }
    });

    // Submit Edit Form
    document.getElementById('editProductForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const productId = document.getElementById('editProductId').value;
        const formData = new FormData(e.target);
        formData.append('_method', 'PUT');

        fetch(`/products/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                alert(data.message);

                // Update table row
                const row = document.querySelector(`tr[data-id="${productId}"]`);
                row.children[0].textContent = data.product.product_name;
                row.children[1].textContent = data.product.category;
                row.children[2].textContent = `P ${parseFloat(data.product.price).toFixed(2)}`;
                row.children[3].textContent = data.product.availability;

                editProductModal.style.display = 'none';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Something went wrong while updating.');
        });
    });

    // Delete Product
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            const productId = row.dataset.id;

            if (confirm('Are you sure you want to delete this product?')) {
                fetch(`/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        row.remove();
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Something went wrong while deleting.');
                });
            }
        });
    });

    // Submit Add Form
    document.getElementById('addProductForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);

        fetch('/products', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                addProductModal.style.display = 'none';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Something went wrong while adding the product.');
        });
    });
});

// Search Products
$(document).ready(function() {
    $('#search').on('keyup', function() {
        let query = $(this).val().trim();

        if (query === '') {
            $('.product-results').html('');
            $('#resultsModal').hide();
            return;
        }

        $.ajax({
            url: '{{ route('products.search') }}',
            type: 'GET',
            data: { query: query },
            success: function(products) {
                let resultHtml = '';

                if (products.length > 0) {
                    products.forEach(function(product) {
                        resultHtml += `
                            <div class="product-card">
                                <h3>${product.product_name}</h3>
                                <p>Category: ${product.category}</p>
                                <p>Price: ₱${product.price}</p>
                                <p>Availability: ${product.availability}</p>
                            </div>
                        `;
                    });
                } else {
                    resultHtml = '<p>No products found.</p>';
                }

                $('.product-results').html(resultHtml);
                $('#resultsModal').fadeIn(); // Show the modal smoothly
            },
            error: function() {
                alert('An error occurred while searching.');
            }
        });
    });

    // Close when 'X' is clicked
    $('.close').on('click', function() {
        $('#resultsModal').fadeOut();
    });

    // Close when clicking outside modal-content
    $(window).on('click', function(event) {
        if ($(event.target).is('#resultsModal')) {
            $('#resultsModal').fadeOut();
        }
    });
});



</script>

@endsection
