@extends('layouts.sidebar')

@section('content')

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/style_inventory.css') }}">

    <!-- Top Bar -->
    <div class="top-bar">
   
        <div class="shop-status">
    <h1> INVENTORY MANAGEMENT </h1>
            <label for="toggleShop">Shop Status</label>
        <input type="checkbox" id="toggleShop" class="toggle" {{ $isShopOpen ? 'checked' : '' }}>
        <span class="status-label" style="color: {{ $isShopOpen ? '#10b981' : '#ef4444' }}">
            {{ $isShopOpen ? 'OPEN' : 'CLOSED' }}
        </span>
        </div>

<div class="search-container">
        <input type="text" id="search" placeholder="Search products...">
    <i class="fas fa-search"></i>
</div>
    </div>

<!-- Success Message -->
@if(session('success'))
    <div class="notification bg-green-500">
        {{ session('success') }}
    </div>
@endif

<!-- Inventory List -->
<div class="inventory-list">
    <h2>
        Inventory
        <i class="fas fa-plus add-icon" id="openModalBtn" title="Add Product"></i>
    </h2>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Tag</th>
                <th>Price (Hot)</th>
                <th>Price (Iced)</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="inventoryTable">
            @foreach($products as $product)
            <tr data-id="{{ $product->id }}">
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="product-thumbnail">
                    @else
                        <span class="no-image">No Image</span>
                    @endif
                </td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->tag }}</td>
                <td>
                    @if(!is_null($product->price_hot))
                        ₱ {{ number_format($product->price_hot, 2) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(!is_null($product->price_iced))
                        ₱ {{ number_format($product->price_iced, 2) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $product->availability }}</td>
                <td>
                    <button class="edit-btn" data-id="{{ $product->id }}" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="delete-btn" data-id="{{ $product->id }}" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach

            @if($products->isEmpty())
            <tr>
                <td colspan="6" class="text-center py-4 text-gray-500">No products found.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAddModalBtn">&times;</span>
        <h3 class="text-xl font-semibold mb-4">Add Product</h3>

        <form id="addProductForm">
            @csrf
            <input type="hidden" name="product_id" id="addProductId">
            <div class="mb-4">
                <label for="addProductName">Product Name</label>
                <input type="text" id="addProductName" name="product_name" required>
            </div>

            <div class="mb-4">
                <label for="addProductCategory">Category</label>
                <select id="addProductCategory" name="category" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="Coffee Series">Coffee Series</option>
                    <option value="Non-Coffee Series">Non-Coffee Series</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="addPriceHot">Price (Hot)</label>
                <input type="number" id="addPriceHot" name="price_hot" step="0.01">
            </div>

            <div class="mb-4">
                <label for="addPriceIced">Price (Iced)</label>
                <input type="number" id="addPriceIced" name="price_iced" step="0.01">
            </div>

            <div class="mb-4">
                <label for="addProductAvailability">Availability</label>
                <select id="addProductAvailability" name="availability" required>
                    <option value="" disabled selected>Select Availability</option>
                    <option value="In Stock">In Stock</option>
                    <option value="Out of Stock">Out of Stock</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="addProductImage">Product Image</label>
                <input type="file" id="addProductImage" name="image" accept="image/*">
            </div>

            <div class="mb-4">
                <label for="addProductTag">Tag</label>
                <input type="text" id="addProductTag" name="tag" placeholder="e.g., New, Popular, etc.">
            </div>

            <button type="submit" class="btn-add">Add Product</button>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditModalBtn">&times;</span>
        <h3 class="text-xl font-semibold mb-4">Edit Product</h3>

        <form id="editProductForm">
            @csrf
            <input type="hidden" id="editProductId" name="id">

            <div class="mb-4">
            <label for="editProductName">Product Name</label>
                <input type="text" id="editProductName" name="product_name" required>
            </div>

            <div class="mb-4">
            <label for="editProductCategory">Category</label>
            <select id="editProductCategory" name="category" required>
                    <option value="" disabled>Select Category</option>
                <option value="Coffee Series">Coffee Series</option>
                <option value="Non Coffee Series">Non Coffee Series</option>
            </select>
            </div>

            <div class="mb-4">
                <label for="editPriceHot">Price (Hot)</label>
                <input type="number" id="editPriceHot" name="price_hot" step="0.01">
            </div>

            <div class="mb-4">
                <label for="editPriceIced">Price (Iced)</label>
                <input type="number" id="editPriceIced" name="price_iced" step="0.01">
            </div>

            <div class="mb-4">
            <label for="editProductAvailability">Availability</label>
            <select id="editProductAvailability" name="availability" required>
                    <option value="" disabled>Select Availability</option>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>
            </div>

            <div class="mb-4">
                <label for="editProductImage">Product Image</label>
                <input type="file" id="editProductImage" name="image" accept="image/*">
                <div id="currentImage"></div>
            </div>

            <div class="mb-4">
                <label for="editProductTag">Tag</label>
                <input type="text" id="editProductTag" name="tag" placeholder="e.g., New, Popular, etc.">
            </div>

            <button type="submit" class="btn-save">Save Changes</button>
        </form>
    </div>
</div>

<!-- CSRF Token Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shop Status Toggle
    const toggleShop = document.getElementById('toggleShop');
    const statusLabel = document.querySelector('.status-label');

    toggleShop.addEventListener('change', async function() {
        try {
            const response = await fetch('/api/shop/toggle-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                statusLabel.textContent = data.is_open ? 'OPEN' : 'CLOSED';
                statusLabel.style.color = data.is_open ? '#10b981' : '#ef4444';
                toggleShop.checked = data.is_open;
                
                showNotification(data.message, data.is_open ? 'success' : 'error');
            } else {
                throw new Error(data.message || 'Failed to update shop status');
            }
        } catch (error) {
            console.error('Error:', error);
            toggleShop.checked = !toggleShop.checked;
            showNotification('Failed to update shop status', 'error');
        }
    });

    // Modal Elements
    const editProductModal = document.getElementById('editProductModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const addProductModal = document.getElementById('addProductModal');
    const closeAddModalBtn = document.getElementById('closeAddModalBtn');
    const openModalBtn = document.getElementById('openModalBtn');

    // Open Add Product Modal
    openModalBtn.addEventListener('click', () => {
        addProductModal.style.display = 'block';
        document.getElementById('addProductForm').reset();
    });

    // Close Add Product Modal
    closeAddModalBtn.addEventListener('click', () => {
        addProductModal.style.display = 'none';
    });

    // Open Edit Product Modal
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const row = e.target.closest('tr');
            const productId = row.dataset.id;
            const productName = row.children[1].textContent.trim();
            const category = row.children[2].textContent.trim();
            const priceHot = row.children[3].textContent.includes('N/A') ? '' : 
                           row.children[3].textContent.replace('₱', '').trim();
            const priceIced = row.children[4].textContent.includes('N/A') ? '' : 
                            row.children[4].textContent.replace('₱', '').trim();
            const availability = row.children[5].textContent.trim();

            document.getElementById('editProductId').value = productId;
            document.getElementById('editProductName').value = productName;
            document.getElementById('editProductCategory').value = category;
            document.getElementById('editPriceHot').value = priceHot;
            document.getElementById('editPriceIced').value = priceIced;
            document.getElementById('editProductAvailability').value = availability;

            editProductModal.style.display = 'block';
        });
    });

    // Close Edit Product Modal
    closeEditModalBtn.addEventListener('click', () => {
        editProductModal.style.display = 'none';
    });

    // Close Modals on Outside Click
    window.addEventListener('click', (e) => {
        if (e.target === editProductModal) {
            editProductModal.style.display = 'none';
        }
        if (e.target === addProductModal) {
            addProductModal.style.display = 'none';
        }
    });

    // Helper function to show notifications
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification bg-${type === 'success' ? 'green' : 'red'}-500`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    // Submit Edit Form
    document.getElementById('editProductForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const productId = document.getElementById('editProductId').value;
        const formData = new FormData(e.target);
        formData.append('_method', 'PUT'); // For Laravel method spoofing

        try {
            const response = await fetch(`/inventory/products/${productId}`, {
            method: 'POST',
            headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showNotification('Product updated successfully', 'success');

                // Update table row
                const row = document.querySelector(`tr[data-id="${productId}"]`);
                row.children[1].textContent = formData.get('product_name');
                row.children[2].textContent = formData.get('category');
                row.children[3].textContent = formData.get('price_hot') ? 
                    `₱ ${parseFloat(formData.get('price_hot')).toFixed(2)}` : 'N/A';
                row.children[4].textContent = formData.get('price_iced') ? 
                    `₱ ${parseFloat(formData.get('price_iced')).toFixed(2)}` : 'N/A';
                row.children[5].textContent = formData.get('availability');

                editProductModal.style.display = 'none';
            } else {
                throw new Error(data.message || 'Failed to update product');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Failed to update product', 'error');
        }
    });

    // Delete Product
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            const row = e.target.closest('tr');
            const productId = row.dataset.id;

            if (confirm('Are you sure you want to delete this product?')) {
                try {
                    const response = await fetch(`/inventory/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showNotification('Product deleted successfully', 'success');
                        row.remove();
                    } else {
                        throw new Error(data.message || 'Failed to delete product');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Failed to delete product', 'error');
                }
            }
        });
    });

    // Submit Add Form
    document.getElementById('addProductForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Generate a product ID
        const timestamp = new Date().getTime();
        const randomNum = Math.floor(Math.random() * 1000);
        const productId = `PROD-${timestamp}-${randomNum}`;
        document.getElementById('addProductId').value = productId;

        const formData = new FormData(e.target);

        try {
            const response = await fetch('/inventory/products', {
            method: 'POST',
            headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showNotification('Product added successfully', 'success');
                addProductModal.style.display = 'none';
                e.target.reset();
                window.location.reload(); // Reload to show new product
            } else {
                throw new Error(data.message || 'Failed to add product');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message || 'Failed to add product', 'error');
        }
});

// Search Products
    const searchInput = document.getElementById('search');
    const inventoryTable = document.getElementById('inventoryTable');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = inventoryTable.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            if (!row.dataset.id) return; // Skip if not a product row

            const productName = row.children[1].textContent.toLowerCase();
            const category = row.children[2].textContent.toLowerCase();
            
            if (productName.includes(searchTerm) || category.includes(searchTerm)) {
                row.style.display = '';
                } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
