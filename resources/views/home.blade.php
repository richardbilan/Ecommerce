<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beyouu Brew Cafeph</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen overflow-hidden">


    <!-- Left Sidebar -->
    <aside class="w-32 bg-[#EADBC8] h-screen text-black flex flex-col justify-between p-4 sticky top-0">
        <div>
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-[20px]">
            </div>

            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('home') }}"
                            class="flex flex-col items-center p-3 hover:bg-[#745858] hover:text-white rounded group">
                            <img src="{{ asset('images/home.png') }}" alt="Home Icon"
                                class="w-9 h-9 mb-1 group-hover:hidden">
                            <img src="{{ asset('images/home_white.png') }}" alt="Home Icon Hover"
                                class="w-9 h-9 mb-1 hidden group-hover:block">
                            <span>Home</span>
                        </a>
                    </li>

                    <li class="mb-4">
                        <a href="#" id="billingBtn"
                            class="flex flex-col items-center p-3 hover:bg-[#745858] hover:text-white rounded group">
                            <img src="{{ asset('images/billing.png') }}" alt="Billing Icon"
                                class="w-9 h-9 mb-1 group-hover:hidden">
                            <img src="{{ asset('images/billing_white.png') }}" alt="Billing Icon Hover"
                                class="w-9 h-9 mb-1 hidden group-hover:block">
                            <span>Billings</span>
                        </a>
                    </li>

                    <li class="mb-4">
                        <a href="{{ route('deliveryuser') }}"
                            class="flex flex-col items-center p-3 hover:bg-[#745858] hover:text-white rounded group">
                            <img src="{{ asset('images/delivery.png') }}" alt="Delivery Icon"
                                class="w-9 h-9 mb-1 group-hover:hidden">
                            <img src="{{ asset('images/delivery_white.png') }}" alt="Delivery Icon Hover"
                                class="w-9 h-9 mb-1 hidden group-hover:block">
                            <span>Delivery</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- User Menu -->
        <div class="relative">
            <button id="userMenuBtn"
                class="flex flex-col items-center p-3 w-full hover:bg-[#745858] hover:text-white rounded group">
                <img src="{{ asset('images/user.png') }}" alt="User Icon"
                    class="w-9 h-9 mb-1 group-hover:hidden">
                <img src="{{ asset('images/user_white.png') }}" alt="User Icon Hover"
                    class="w-9 h-9 mb-1 hidden group-hover:block">
                <span>User</span>
            </button>

            <div id="userMenu"
                class="absolute hidden bg-[#EADBC8] text-black left-0 top-[-7.6rem] mt-2 rounded shadow-md w-32 text-center text-xs z-50">
                <a href="{{ route('account_settings') }}"
                    class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Account Settings</a>
                <a href="{{ route('logout') }}"
                    class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Logout</a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">

        <!-- Header -->
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h1>

            <div class="relative">
                <form role="search">
                    <div class="relative">
                        <input type="search" name="search" placeholder="Search Coffee..."
                            class="p-2 border border-[#745858] rounded w-64 bg-[#EADBC8] text-black"
                            oninput="searchProducts(this.value)">
                        <button type="submit" class="absolute right-3 top-2">
                            <img src="{{ asset('images/search.png') }}" alt="Search Icon"
                                class="w-5 h-5 group-hover:hidden">
                            <img src="{{ asset('images/search_black.png') }}" alt="Search Icon Hover"
                                class="w-5 h-5 hidden group-hover:block">
                        </button>
                    </div>
                </form>
            </div>
        </header>

        <!-- Slideshow -->
        <div class="w-full h-64 overflow-hidden relative rounded-xl mb-8">
            <img src="{{ asset('images/image1.jpeg') }}"
                class="absolute inset-0 w-full h-full object-cover rounded-xl" alt="Slideshow 1">
            <img src="{{ asset('images/image2.jpeg') }}"
                class="absolute inset-0 w-full h-full object-cover hidden rounded-xl" alt="Slideshow 2">
        </div>

      <!-- Category Section -->
      <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Choose Your Category</h2>
        <div class="flex flex-wrap gap-4">
            <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white"
            data-category="all">All</button>

        <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white"
            data-category="coffee series">Coffee Series</button>

        <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white"
            data-category="non-coffee series">Non-Coffee Series</button>

            <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white"
                data-category="best seller">Best Seller</button>
        </div>
    </div>




            <!-- Product List -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Choose Menu</h2>

        {{-- Debugging --}}


        @if ($products && count($products) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
            <div
            class="product-item flex bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
            data-category="{{ strtolower($product->category) }}">

                    <!-- Product Image -->
                    <img src="{{ asset('images/' . ($product->image ?? 'cup.png')) }}" alt="{{ $product->product_name }}"
                        class="w-full h-48 object-cover">

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-1">{{ $product->product_name }}</h3>
                        <p class="text-sm text-gray-600 mb-2 capitalize">{{ $product->category ?? 'N/A' }}</p>

                        <div class="mb-3 space-y-1">
                            <p class="text-sm"><strong>Hot:</strong>
                                {{ $product->price_hot ? '₱' . number_format($product->price_hot, 2) : 'N/A' }}</p>
                            <p class="text-sm"><strong>Cold:</strong>
                                {{ $product->price_cold ? '₱' . number_format($product->price_cold, 2) : 'N/A' }}</p>
                        </div>

                        <!-- Product Availability -->
                        <div class="mb-3">
                            @if ($product->availability)
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                    Available
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                    Out of Stock
                                </span>
                            @endif
                        </div>

                        <button
                        onclick="addToBillingCart(
                            '{{ $product->product_name }}',
                            '{{ $product->price_hot ?? 0 }}',
                            '{{ $product->price_cold ?? 0 }}'
                        )"
                        class="w-full py-2 px-4 bg-[#745858] text-white rounded hover:bg-[#5a4444] text-sm"
                        @if (!$product->availability) disabled class="cursor-not-allowed opacity-50" @endif
                    >
                        {{ $product->availability ? 'Order' : 'Unavailable' }}
                    </button>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-600">No products found.</p>
    @endif



   <!-- Right Sidebar (Billing) -->
<!-- Right Sidebar (Billing) -->
<aside id="rightSidebar" class="fixed right-0 top-0 w-80 bg-[#EADBC8] h-screen overflow-y-auto shadow-lg transform translate-x-full transition-transform duration-300 p-4 z-50">

    <!-- Close Button -->
    <button id="closeSidebar" class="mb-4">
        <img src="{{ asset('images/close.png') }}" alt="Close Icon" class="w-6 h-6">
    </button>

    <!-- Billing Header -->
    <h2 class="text-xl font-bold mb-4">Billings</h2>

    <!-- Cart Items (Scroll enabled) -->
    <div id="cartItems" class="space-y-4 overflow-y-auto" style="max-height: 400px;">
        <!-- Cart items will be dynamically added here -->
    </div>

    <!-- Order Summary -->
    <div class="mt-4 border-t pt-4">
        <div class="flex justify-between">
            <span>Delivery Fee</span>
            <span>₱ 50</span>
        </div>
        <div class="flex justify-between">
            <span>Promo</span>
            <span>₱ 0</span>
        </div>
        <div class="flex justify-between font-bold border-t mt-2 pt-2">
            <span>Total</span>
            <span id="totalAmount">₱ 0</span>
        </div>
    </div>

    <!-- Mode of Order -->
<div class="mt-6">
    <label class="block font-bold mb-2">Mode of Order</label>
    <select id="orderMode" class="w-full p-2 border rounded bg-white text-black text-center appearance-none">
        <option value="delivery">🚚 Delivery</option>
        <option value="pickup">🏠 Pick Up</option>
    </select>
    <button id="locationBtn" class="w-full mt-2 p-2 bg-white text-black border rounded">Enter Your Location</button>
</div>

<!-- Payment Method -->
<div class="mt-6">
    <label class="block font-bold mb-2">Payment Method</label>
    <div class="flex justify-between mt-2">
        <button class="payment-btn w-1/2 p-2 bg-white text-black border border-gray-500 rounded hover:bg-[#745858] hover:text-white" data-method="Gcash">Gcash</button>
        <button class="payment-btn w-1/2 p-2 bg-white text-black border border-gray-500 rounded hover:bg-[#745858] hover:text-white ml-2" data-method="Cash">Cash</button>
    </div>
</div>

<!-- Place Order Button -->
<button id="placeOrderBtn" class="w-full mt-7 p-2 bg-[#745858] text-white rounded hover:bg-[#553C26]">Place Order</button>



<script>
    // Open/Close Billing Sidebar
    document.getElementById('billingBtn').addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('rightSidebar').classList.remove('translate-x-full');
    });

    document.getElementById('closeSidebar').addEventListener('click', function () {
        document.getElementById('rightSidebar').classList.add('translate-x-full');
    });

    // Toggle User Menu
    document.getElementById('userMenuBtn').addEventListener('click', function () {
        document.getElementById('userMenu').classList.toggle('hidden');
    });

    // Slideshow
    let slides = document.querySelectorAll('.w-full.h-64.overflow-hidden.relative img');
    let index = 0;
    setInterval(() => {
        slides[index].classList.add('hidden');
        index = (index + 1) % slides.length;
        slides[index].classList.remove('hidden');
    }, 2000);

    // Mode of Order
    document.getElementById("orderMode").addEventListener("change", function () {
        let locationBtn = document.getElementById("locationBtn");
        locationBtn.textContent = this.value === "pickup" ? "Current Location" : "Enter Your Location";
    });

    // Favorite Button
    document.getElementById('favoriteBtn').addEventListener('click', function () {
        let heartIcon = document.getElementById('heartIcon');
        if (heartIcon.classList.contains('text-gray-500')) {
            heartIcon.classList.remove('text-gray-500');
            heartIcon.classList.add('text-red-500');
            heartIcon.setAttribute("fill", "red");
        } else {
            heartIcon.classList.remove('text-red-500');
            heartIcon.classList.add('text-gray-500');
            heartIcon.setAttribute("fill", "none");
        }
    });

    // Quantity Controls
    document.addEventListener("DOMContentLoaded", function () {
        const quantityDisplay = document.getElementById("quantity");
        const increaseBtn = document.getElementById("increaseBtn");
        const decreaseBtn = document.getElementById("decreaseBtn");

        let quantity = 1;

        if (increaseBtn) {
            increaseBtn.addEventListener("click", function () {
                quantity++;
                quantityDisplay.textContent = quantity;
            });
        }

        if (decreaseBtn) {
            decreaseBtn.addEventListener("click", function () {
                if (quantity > 1) {
                    quantity--;
                    quantityDisplay.textContent = quantity;
                }
            });
        }
    });

    // ✅ CATEGORY FILTER FUNCTION
    document.addEventListener("DOMContentLoaded", function () {
    const categoryButtons = document.querySelectorAll('.category-btn');
    const productItems = document.querySelectorAll('.product-item');

    if (!categoryButtons.length || !productItems.length) {
        console.warn("No categories or products found!");
        return;
    }

    categoryButtons.forEach(button => {
        button.addEventListener('click', function () {
            const selectedCategory = this.getAttribute('data-category').toLowerCase();

            productItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category').toLowerCase();

                if (selectedCategory === 'all' || itemCategory === selectedCategory) {
                    item.style.display = 'block'; // or 'flex' depending on your CSS layout
                } else {
                    item.style.display = 'none';
                }
            });

            // Highlight the active button
            categoryButtons.forEach(btn => btn.classList.remove('bg-[#745858]', 'text-white'));
            this.classList.add('bg-[#745858]', 'text-white');
        });
    });
});



    // ✅ BILLING CART FUNCTIONS
    function addToBillingCart(productName, priceHot, priceCold) {
        const selectedOption = priceHot > 0 ? "Hot" : "Cold";
        const selectedPrice = priceHot > 0 ? priceHot : priceCold;

        const cartItemHTML = `
            <div class="bg-white p-3 rounded shadow-md cart-item" data-price="${selectedPrice}">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold">${productName} <span class="text-sm text-gray-500">(${selectedOption})</span></h3>
                        <p class="text-sm">₱ <span class="item-price">${selectedPrice}</span></p>
                    </div>
                    <button onclick="removeCartItem(this)">
                        <img src="{{ asset('images/close.png') }}" alt="Remove Item" class="w-5 h-5">
                    </button>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <div class="flex items-center space-x-2">
                        <button class="bg-gray-300 px-2 py-1 rounded" onclick="changeQuantity(this, -1)">-</button>
                        <span class="quantity w-6 text-center">1</span>
                        <button class="bg-gray-300 px-2 py-1 rounded" onclick="changeQuantity(this, 1)">+</button>
                    </div>
                </div>
            </div>
        `;

        const cartContainer = document.getElementById("cartItems");
        cartContainer.insertAdjacentHTML('beforeend', cartItemHTML);

        document.getElementById("rightSidebar").classList.remove("translate-x-full");

        updateTotal();
    }

    function removeCartItem(button) {
        const item = button.closest('.cart-item');
        item.remove();
        updateTotal();
    }

    function changeQuantity(button, change) {
        const quantitySpan = button.parentElement.querySelector('.quantity');
        let quantity = parseInt(quantitySpan.textContent);

        quantity = Math.max(1, quantity + change);
        quantitySpan.textContent = quantity;

        updateTotal();
    }

    function updateTotal() {
        const cartItems = document.querySelectorAll('.cart-item');
        let total = 0;

        cartItems.forEach(item => {
            const price = parseFloat(item.getAttribute('data-price'));
            const quantity = parseInt(item.querySelector('.quantity').textContent);
            total += price * quantity;
        });

        document.getElementById('totalAmount').textContent = `₱ ${total.toFixed(2)}`;
    }

    // ✅ SEARCH FUNCTION FOR PRODUCTS
    function searchProducts(query) {
        query = query.trim().toLowerCase();

        const productItems = document.querySelectorAll('.product-item');

        let found = false;

        productItems.forEach(item => {
            const productName = item.querySelector('h3').innerText.toLowerCase();

            if (productName.includes(query)) {
                item.style.display = "flex";
                found = true;
            } else {
                item.style.display = "none";
            }
        });

        // If no products match
        const noResultMessage = document.getElementById('noResultMessage');
        if (!found) {
            if (!noResultMessage) {
                const message = document.createElement('p');
                message.id = 'noResultMessage';
                message.className = 'text-center text-gray-600 mt-4';
                message.innerText = 'No products found.';
                document.getElementById('productContainer').appendChild(message);
            }
        } else {
            if (noResultMessage) {
                noResultMessage.remove();
            }
        }
    }

</script>

</body>
</html>
