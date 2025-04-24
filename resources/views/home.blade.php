<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Beyouu Brew Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen overflow-hidden">

    <!-- Add this right after the opening body tag -->
    <div id="shopClosedOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center {{ $isShopOpen ? 'hidden' : '' }}">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md mx-4">
            <h2 class="text-2xl font-bold text-red-600 mb-4">Shop is Currently Closed</h2>
            @if($shopStatus)
                <p class="text-gray-700 mb-2">Last opened: {{ $shopStatus->last_opened_at ? $shopStatus->last_opened_at->format('M d, Y h:i A') : 'Never' }}</p>
                <p class="text-gray-700 mb-2">Last closed: {{ $shopStatus->last_closed_at ? $shopStatus->last_closed_at->format('M d, Y h:i A') : 'Never' }}</p>
                @if($currentLocation)
                    <p class="text-gray-700 mb-4">Location: {{ $currentLocation->address }}</p>
                @endif
            @else
                <p class="text-gray-700 mb-4">Shop status not available.</p>
            @endif
            <button onclick="window.location.reload()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Refresh Page
            </button>
        </div>
    </div>

    <script>
        // Global variable to track shop status
        let isShopCurrentlyOpen = {{ $isShopOpen ? 'true' : 'false' }};

        // Function to check shop status
        async function checkShopStatus() {
            try {
                const response = await fetch('/api/cafe/current-location');
                const data = await response.json();
                
                if (data.success && data.location) {
                    const newStatus = Boolean(data.location.is_open);
                    
                    // Only update if status has changed
                    if (newStatus !== isShopCurrentlyOpen) {
                        console.log('Shop status changed:', newStatus ? 'open' : 'closed'); // Debug log
                        isShopCurrentlyOpen = newStatus;
                        updateUIForShopStatus(newStatus);
                    }
                }
            } catch (error) {
                console.error('Error checking shop status:', error);
            }
        }

        // Function to update UI based on shop status
        function updateUIForShopStatus(isOpen) {
            console.log('Updating UI for shop status:', isOpen ? 'open' : 'closed'); // Debug log
            
            const buttons = document.querySelectorAll('.option-btn, .add-to-cart-btn');
            const billingBtn = document.getElementById('billingBtn');
            const overlay = document.getElementById('shopClosedOverlay');
            const cartItems = document.getElementById('cartItems');
            
            // Update overlay
            if (overlay) {
                if (!isOpen) {
                    overlay.classList.remove('hidden');
                } else {
                    overlay.classList.add('hidden');
                }
            }

            // Update buttons
            buttons.forEach(button => {
                if (!isOpen) {
                    button.disabled = true;
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                    button.title = 'Shop is currently closed';
                } else {
                    // Only enable if not otherwise disabled
                    if (!button.hasAttribute('data-disabled')) {
                        button.disabled = false;
                        button.classList.remove('opacity-50', 'cursor-not-allowed');
                        button.removeAttribute('title');
                    }
                }
            });

            // Update billing button and cart
            if (billingBtn) {
                if (!isOpen) {
                    billingBtn.disabled = true;
                    billingBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    billingBtn.title = 'Shop is currently closed';
                    
                    // Clear cart if shop is closed
                    if (cartItems) {
                        cartItems.innerHTML = '<p class="text-center text-gray-500 py-4">Shop is currently closed</p>';
                    }
                } else {
                    billingBtn.disabled = false;
                    billingBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    billingBtn.removeAttribute('title');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initial UI update
            updateUIForShopStatus(isShopCurrentlyOpen);
            
            // Check status immediately
            checkShopStatus();
            
            // Set up periodic status check every 5 seconds
            setInterval(checkShopStatus, 5000);

            // Override the addToBillingCart function to check shop status in real-time
            const originalAddToBillingCart = window.addToBillingCart;
            window.addToBillingCart = async function(productId) {
                if (!isShopCurrentlyOpen) {
                    alert('Sorry, the shop is currently closed. Please try again during operating hours.');
                    return;
                }
                originalAddToBillingCart(productId);
            };
        });
    </script>

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
            @auth
            <h1 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}</span>!</h1>
        @endauth

        @guest
            <h1>Welcome, {{ Auth::user()->name }}</h1>
        @endguest
        <div class="flex justify-between items-center mb-6">
           <!-- Search Form -->
<form role="search" onsubmit="return false;">
    <div class="relative">
        <input
            type="search"
            name="search"
            placeholder="Search Coffee..."
            class="p-2 border border-[#745858] rounded w-64 bg-[#EADBC8] text-black"
            oninput="searchProducts(this.value)"
        >

        <button type="submit" class="absolute right-3 top-2">
            <img src="{{ asset('images/search.png') }}" alt="Search Icon" class="w-5 h-5 group-hover:hidden">
            <img src="{{ asset('images/search_black.png') }}" alt="Search Icon Hover" class="w-5 h-5 hidden group-hover:block">
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
<div class="mb-8">
    <h2 class="text-xl font-bold mb-4">Choose Your Category</h2>
    <div class="flex flex-wrap gap-4">
        <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="all">All</button>
        <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="coffee series">Coffee Series</button>
        <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="non-coffee series">Non-Coffee Series</button>
    </div>
</div>

@if (!$isShopOpen)
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mb-4">
                <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Shop is Currently Closed</h3>
            <p class="text-sm text-gray-500 mb-6">We're sorry, but the shop is currently closed. Please come back during our operating hours.</p>
            <div class="flex justify-center">
                <button onclick="window.location.reload()" class="bg-[#6F4E37] text-white px-4 py-2 rounded hover:bg-[#5C4130]">
                    Refresh Page
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Choose Menu</h2>

    <!-- Grid layout for the menu items -->
    <div id="productContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach ($products as $product)
        <!-- Product Card -->
        <div class="product-item bg-[#F5F5F5] p-6 rounded-xl shadow-md flex flex-col transition-transform duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md hover:bg-[#f0e6db]" 
             data-category="{{ strtolower(trim($product->category ?? 'uncategorized')) }}"
             data-product-id="{{ $product->id }}">
            <!-- Rest of the product card structure -->
            <div class="flex flex-row items-start gap-6">
                <!-- Product Image -->
                <div class="relative">
                    <img src="{{ asset($product->image ?? 'images/cup.png') }}"
                        alt="{{ $product->product_name }}"
                        class="w-[100px] h-[144px] rounded-[10px] object-cover"
                    />
                    <!-- Tags and favorite button -->
                </div>

                <!-- Product Info -->
                <div class="flex flex-col justify-between h-full flex-1">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-xl font-bold text-gray-800">{{ $product->product_name }}</h3>
                            <!-- Availability -->
                            @if ($product->availability === 'In Stock')
                                <p class="text-green-600 text-sm font-semibold">Available</p>
                            @else
                                <p class="text-red-600 text-sm font-semibold">Out of Stock</p>
                            @endif
                        </div>
                        <!-- Category and description -->
                        <p class="text-sm text-[#745858] font-medium mb-2">
                            {{ $product->category ?? 'Uncategorized' }}
                        </p>

                        <!-- Product Description -->
                        <p class="text-gray-600 text-sm">
                            {{ $product->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}
                        </p>
                    </div>

                    <!-- Pricing Buttons -->
                    <div class="text-sm text-gray-700 mb-4 flex gap-2">
                        <!-- Hot Button -->
                        <button onclick="selectOption(this, '{{ $product->id }}', 'hot', '{{ $product->price_hot ?? 0 }}')"
                            class="option-btn bg-white text-black border border-gray-500 rounded hover:bg-[#745858] hover:text-white transition-all duration-300 w-[92px] h-[26px] rounded-[20px] text-sm flex items-center justify-center"
                            type="button"
                            data-product-id="{{ $product->id }}"
                            data-option="hot"
                            data-price="{{ $product->price_hot ?? 0 }}"
                            @if (($product->price_hot ?? 0) <= 0) disabled @endif>
                            Hot: {{ ($product->price_hot ?? 0) > 0 ? '₱' . number_format($product->price_hot, 2) : 'N/A' }}
                        </button>

                        <!-- Iced Button -->
                        <button onclick="selectOption(this, '{{ $product->id }}', 'iced', '{{ $product->price_iced ?? 0 }}')"
                            class="option-btn bg-white text-black border border-gray-500 rounded hover:bg-[#745858] hover:text-white transition-all duration-300 w-[92px] h-[26px] rounded-[20px] text-sm flex items-center justify-center"
                            type="button"
                            data-product-id="{{ $product->id }}"
                            data-option="iced"
                            data-price="{{ $product->price_iced ?? 0 }}"
                            @if (($product->price_iced ?? 0) <= 0) disabled @endif>
                            Iced: {{ ($product->price_iced ?? 0) > 0 ? '₱' . number_format($product->price_iced, 2) : 'N/A' }}
                        </button>
                    </div>

                    <!-- Add to Cart Button -->
                    <button onclick="addToBillingCart('{{ $product->id }}')"
                        class="add-to-cart-btn bg-[#745858] text-white py-2 rounded-[20px] hover:bg-[#5a4444] transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed w-full"
                        data-product-id="{{ $product->id }}"
                        @if ($product->availability !== 'In Stock') disabled @endif>
                        {{ $product->availability === 'In Stock' ? 'Add to Cart' : 'Unavailable' }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Order Summary Modal -->
<div id="orderSummaryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999] hidden">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl">
        <form id="orderForm" action="{{ route('orders.store') }}" method="POST" onsubmit="return false;">
            @csrf
            <input type="hidden" name="items" id="itemsInput">
            <input type="hidden" name="total_amount" id="totalAmountInput">
            <input type="hidden" name="payment_method" id="paymentMethodInput">
            <input type="hidden" name="order_status" value="pending">
            <input type="hidden" name="payment_status" value="unpaid">
            <input type="hidden" name="location" id="locationInput">

            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-xl font-semibold">Order Summary</h2>
                <button type="button" onclick="closeOrderSummary()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <!-- Order Items Summary -->
                <div class="bg-gray-50 rounded-lg p-4 shadow mb-4">
                    <h3 class="font-semibold mb-3">Order Items</h3>
                    <div id="summaryItems" class="space-y-2">
                        <!-- Items will be populated dynamically -->
                    </div>
                    
                    <!-- Order Totals -->
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="summarySubtotal" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Delivery Fee:</span>
                            <span id="summaryDeliveryFee" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Discount:</span>
                            <span id="summaryDiscount" class="font-medium text-red-600"></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span id="summaryTotal"></span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="user_name" id="customer_name" value="{{ Auth::user()->name }}" readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="customer_email" value="{{ Auth::user()->email }}" readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone_number" id="customer_phone" value="{{ Auth::user()->phone_number }}" readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Delivery Address</label>
                        <input type="text" name="location" id="delivery_address" value="{{ Auth::user()->location }}" readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <input type="text" id="paymentMethodDisplay" readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t">
                <button type="button" onclick="handlePaymentProcess(event)" class="w-full bg-[#8C5A3C] text-white py-2 px-4 rounded-md hover:bg-[#6A4028] transition-colors">
                    Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>





</main>

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

    <div class="mt-4">
        <label for="promoCodeInput" class="block text-sm font-medium mb-1">Enter Promo Code</label>
        <div class="flex space-x-2">
            <input type="text" id="promoCodeInput" class="flex-1 p-2 border border-gray-300 rounded" placeholder="Enter code...">
            <button id="applyPromoBtn" class="bg-[#745858] text-white px-4 py-2 rounded hover:bg-[#5a4444] text-sm">Apply</button>
        </div>
        <p id="promoFeedback" class="text-xs mt-2 text-green-600 hidden">Promo Applied Successfully!</p>
        <p id="promoError" class="text-xs mt-2 text-red-600 hidden">Invalid or Expired Promo Code!</p>
    </div>

    <!-- Order Summary Section -->
    <div class="mt-4 border-t pt-4 space-y-2">
        <div class="flex justify-between">
            <span>Subtotal</span>
            <span id="subtotal">₱0.00</span>
        </div>

        <div class="flex justify-between">
            <span>Mode of Order:</span>
            <select id="orderMode" class="border px-2 py-1 rounded">
                <option value="pickup">🏠 Pick Up</option>
                <option value="delivery">🚚 Delivery</option>
            </select>
        </div>

        <div class="flex justify-between">
            <span>Delivery Fee</span>
            <span id="deliveryFee">₱0.00</span>
        </div>

        <div class="flex justify-between">
            <span class="text-red-600 font-bold">Promo Discount</span>
            <span id="promoDiscount" data-discount="0" class="text-red-600 font-bold">₱0.00</span>
        </div>

        <div class="flex justify-between font-bold text-lg">
            <span>Total Amount</span>
            <span id="totalAmount">₱0.00</span>
        </div>
    </div>

    <!-- Payment Method -->
    <div class="mt-6">
        <label class="block font-bold mb-2">Payment Method</label>
        <div class="flex justify-between mt-2">
            <button
                type="button"
                class="payment-btn w-1/2 p-2 bg-white text-black border border-gray-500 rounded hover:bg-[#745858] hover:text-white"
                onclick="selectPayment(this, 'gcash')">
                GCash
            </button>
            <button
                type="button"
                class="payment-btn w-1/2 p-2 bg-white text-black border border-gray-500 rounded hover:bg-[#745858] hover:text-white ml-2"
                onclick="selectPayment(this, 'cash')">
                Cash
            </button>
        </div>
        <input type="hidden" id="paymentMethodInput" name="payment_method" value="">
    </div>

    <!-- Place Order Button -->
    <button type="button" 
        id="placeOrderBtn" 
        onclick="showOrderSummary()" 
        class="mt-4 w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition-colors">
        Place Order
    </button>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        /** ✅ OPEN/CLOSE BILLING SIDEBAR **/
        const billingBtn = document.getElementById("billingBtn");
        const closeSidebar = document.getElementById("closeSidebar");
        const rightSidebar = document.getElementById("rightSidebar");

        if (billingBtn && closeSidebar && rightSidebar) {
            billingBtn.addEventListener("click", function (event) {
                event.preventDefault();
                rightSidebar.classList.remove("translate-x-full");
            });

            closeSidebar.addEventListener("click", function () {
                rightSidebar.classList.add("translate-x-full");
            });
        }

        /** ✅ TOGGLE USER MENU **/
        const userMenuBtn = document.getElementById("userMenuBtn");
        const userMenu = document.getElementById("userMenu");

        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener("click", function () {
                userMenu.classList.toggle("hidden");
            });
        }

        /** ✅ SLIDESHOW **/
        let slides = document.querySelectorAll(".w-full.h-64.overflow-hidden.relative img");
        let index = 0;

        if (slides.length > 0) {
            setInterval(() => {
                slides[index].classList.add("hidden");
                index = (index + 1) % slides.length;
                slides[index].classList.remove("hidden");
            }, 2000);
        }

        /** ✅ MODE OF ORDER **/
        const orderMode = document.getElementById("orderMode");
        const locationBtn = document.getElementById("locationBtn");

        if (orderMode && locationBtn) {
            orderMode.addEventListener("change", function () {
                locationBtn.textContent = this.value === "pickup" ? "Current Location" : "Enter Your Location";
            });
        }

        /** ✅ FAVORITE BUTTON **/
        const favoriteBtn = document.getElementById("favoriteBtn");
        const heartIcon = document.getElementById("heartIcon");

        if (favoriteBtn && heartIcon) {
            favoriteBtn.addEventListener("click", function () {
                heartIcon.classList.toggle("text-gray-500");
                heartIcon.classList.toggle("text-red-500");
                heartIcon.setAttribute("fill", heartIcon.classList.contains("text-red-500") ? "red" : "none");
            });
        }

        /** ✅ QUANTITY CONTROLS **/
        const quantityDisplay = document.getElementById("quantity");
        const increaseBtn = document.getElementById("increaseBtn");
        const decreaseBtn = document.getElementById("decreaseBtn");

        let quantity = 1;

        if (quantityDisplay && increaseBtn && decreaseBtn) {
            increaseBtn.addEventListener("click", function () {
                quantity++;
                quantityDisplay.textContent = quantity;
            });

            decreaseBtn.addEventListener("click", function () {
                if (quantity > 1) {
                    quantity--;
                    quantityDisplay.textContent = quantity;
                }
            });
        }

        /** ✅ PRODUCT CATEGORY FILTER **/
        const categoryButtons = document.querySelectorAll(".category-btn");
const productItems = document.querySelectorAll(".product-item");

categoryButtons.forEach((button) => {
    button.addEventListener("click", function () {
        const selectedCategory = this.getAttribute("data-category").trim().toLowerCase();

        productItems.forEach((item) => {
            const itemCategory = item.getAttribute("data-category").trim().toLowerCase();

            item.style.display = selectedCategory === "all" || itemCategory === selectedCategory ? "flex" : "none";
        });
    });
});

// Track selected options for each product
window.selectedOptions = {};

window.selectOption = function(button, productId, option, price) {
    console.log('Selecting option:', { productId, option, price });
    
    // Convert price to float
    price = parseFloat(price);
    
    if (isNaN(price) || price <= 0) {
        console.log('Invalid price, option not available');
        return;
    }

    // Remove highlight from all option buttons for this product
    const allButtons = document.querySelectorAll(`.option-btn[data-product-id="${productId}"]`);
    allButtons.forEach(btn => {
        btn.classList.remove('bg-[#745858]', 'text-white');
        btn.classList.add('bg-white', 'text-black');
    });

    // Highlight selected button
    button.classList.remove('bg-white', 'text-black');
    button.classList.add('bg-[#745858]', 'text-white');

    // Store the selected option
    window.selectedOptions[productId] = {
        option: option,
        price: price
    };

    console.log('Updated selectedOptions:', window.selectedOptions);
};

window.addToBillingCart = function(productId) {
    console.log('Adding to cart:', productId);
    console.log('Current selectedOptions:', window.selectedOptions);
    
    const selectedOption = window.selectedOptions[productId];
    if (!selectedOption) {
        alert('Please select Hot or Iced option first');
        return;
    }

    // Find the product card
    const productCard = document.querySelector(`.product-item[data-product-id="${productId}"]`);
    if (!productCard) {
        console.error('Product card not found:', productId);
        return;
    }

    // Get product details
    const productName = productCard.querySelector('h3').textContent.split('(')[0].trim();
    const image = productCard.querySelector('img').src;
    const price = parseFloat(selectedOption.price);
    const optionText = selectedOption.option.charAt(0).toUpperCase() + selectedOption.option.slice(1);

    console.log('Product details:', { productName, image, price, optionText });

    // Create cart item HTML
    const cartItemHTML = `
        <div class="bg-white p-3 rounded shadow-md cart-item mb-3" data-price="${price}">
            <div class="flex justify-between items-start gap-4">
                <div class="flex gap-3">
                    <img src="${image}" alt="${productName}" class="w-14 h-14 object-cover rounded" />
                    <div>
                        <h3 class="font-semibold">${productName}</h3>
                        <p class="text-sm text-gray-500">${optionText}</p>
                        <p class="text-sm">₱${price.toFixed(2)}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <button onclick="removeCartItem(this)" class="text-gray-500 hover:text-red-500">
                        <img src="{{ asset('images/close.png') }}" alt="Remove" class="w-4 h-4">
                    </button>
                    <div class="flex items-center gap-2 mt-2">
                        <button class="bg-gray-200 px-2 py-1 rounded text-sm" onclick="changeQuantity(this, -1)">-</button>
                        <span class="quantity w-8 text-center">1</span>
                        <button class="bg-gray-200 px-2 py-1 rounded text-sm" onclick="changeQuantity(this, 1)">+</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Add item to cart
    const cartItems = document.getElementById("cartItems");
    if (!cartItems) {
        console.error('Cart items container not found');
        return;
    }
    cartItems.insertAdjacentHTML("beforeend", cartItemHTML);
    
    // Show the billing sidebar
    const rightSidebar = document.getElementById("rightSidebar");
    if (!rightSidebar) {
        console.error('Right sidebar not found');
        return;
    }
    rightSidebar.classList.remove("translate-x-full");
    
    // Update totals
    updateTotal();

    // Reset the selection for this product
    delete window.selectedOptions[productId];
    const optionButtons = document.querySelectorAll(`.option-btn[data-product-id="${productId}"]`);
    optionButtons.forEach(btn => {
        btn.classList.remove('bg-[#745858]', 'text-white');
        btn.classList.add('bg-white', 'text-black');
    });
};

// Update the updateTotal function to handle all calculations
function updateTotal() {
    let subtotal = 0;
    const orderMode = document.getElementById("orderMode");
    const promoDiscountElement = document.getElementById("promoDiscount");
    const deliveryFeeElement = document.getElementById("deliveryFee");
    const subtotalElement = document.getElementById("subtotal");
    const totalAmountElement = document.getElementById("totalAmount");

    // Calculate subtotal from cart items
    document.querySelectorAll(".cart-item").forEach(item => {
        const price = parseFloat(item.getAttribute("data-price")) || 0;
        const quantity = parseInt(item.querySelector(".quantity").textContent) || 1;
        subtotal += price * quantity;
    });

    // Get delivery fee based on order mode
    const deliveryFee = orderMode.value === "delivery" ? 50 : 0;
    
    // Get promo discount
    const promoDiscount = parseFloat(promoDiscountElement.getAttribute("data-discount")) || 0;

    // Update all display elements
    subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
    deliveryFeeElement.textContent = `₱${deliveryFee.toFixed(2)}`;
    promoDiscountElement.textContent = `₱${promoDiscount.toFixed(2)}`;

    // Calculate and update total amount
    const totalAmount = subtotal + deliveryFee - promoDiscount;
    totalAmountElement.textContent = `₱${totalAmount.toFixed(2)}`;
}

        window.removeCartItem = function (button) {
            const cartItem = button.closest(".cart-item");
            if (cartItem) {
                cartItem.remove();
                updateTotal();
            }
        };

        window.changeQuantity = function (button, change) {
            const quantitySpan = button.parentElement.querySelector(".quantity");
            if (!quantitySpan) return;

            let quantity = parseInt(quantitySpan.textContent) || 1;
            quantity = Math.max(1, quantity + change);
            quantitySpan.textContent = quantity;

            updateTotal();
        };

        /** ✅ LIVE SEARCH FUNCTION **/
        window.searchProducts = function (query) {
            query = query.trim().toLowerCase();
            const productItems = document.querySelectorAll(".product-item");
            const productContainer = document.getElementById("productContainer");
            let noResultMessage = document.getElementById("noResultMessage");
            let found = false;

            productItems.forEach((item) => {
                const productName = item.querySelector("h3").innerText.toLowerCase();
                if (productName.includes(query)) {
                    item.style.display = "flex";
                    found = true;
                } else {
                    item.style.display = "none";
                }
            });

            // Handle No Results Message
            if (!found) {
                if (!noResultMessage) {
                    noResultMessage = document.createElement("p");
                    noResultMessage.id = "noResultMessage";
                    noResultMessage.className = "text-center text-gray-600 mt-4";
                    noResultMessage.innerText = "No products found.";
                    productContainer.appendChild(noResultMessage);
                }
            } else if (noResultMessage) {
                noResultMessage.remove();
            }
        };

        /** ✅ PLACE ORDER FUNCTION **/
        document.getElementById("placeOrderBtn").addEventListener("click", function(e) {
            e.preventDefault(); // Prevent form submission
            showOrderSummary(); // Show the summary modal directly
        });
    });


    //promotion/ Discount
    document.addEventListener("DOMContentLoaded", function () {
    const applyPromoBtn = document.getElementById("applyPromoBtn");
    const promoCodeInput = document.getElementById("promoCodeInput");
    const promoDiscountSpan = document.getElementById("promoDiscount");
    const promoFeedback = document.getElementById("promoFeedback");
    const promoError = document.getElementById("promoError");

    let promoDiscount = 0; // Store applied promo discount

    applyPromoBtn.addEventListener("click", function () {
        let promoCode = promoCodeInput.value.trim();

        if (promoCode === "") {
            promoError.textContent = "Please enter a promo code!";
            promoError.classList.remove("hidden");
            promoFeedback.classList.add("hidden");
            return;
        }

        // AJAX request to check promo code validity
        fetch(`/check-promo?code=${promoCode}`)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    promoDiscount = parseFloat(data.discount); // Store discount value

                    promoDiscountSpan.textContent = `₱ ${promoDiscount.toFixed(2)}`;
                    promoFeedback.classList.remove("hidden");
                    promoError.classList.add("hidden");
                } else {
                    promoDiscount = 0;
                    promoDiscountSpan.textContent = `₱ 0.00`;

                    promoFeedback.classList.add("hidden");
                    promoError.classList.remove("hidden");
                    promoError.textContent = "Invalid or Expired Promo Code!";
                }

                updateTotal(); // Ensure the total updates after promo application
            })
            .catch(error => console.error("Error fetching promo:", error));
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const orderMode = document.getElementById("orderMode");
    const subtotalElement = document.getElementById("subtotal");
    const promoDiscountElement = document.getElementById("promoDiscount");
    const deliveryFeeElement = document.getElementById("deliveryFee");
    const totalAmountElement = document.getElementById("totalAmount");

    window.updateTotal = function () {
        let subtotal = 0;

        // Calculate subtotal based on cart items
        document.querySelectorAll(".cart-item").forEach(item => {
            const price = parseFloat(item.getAttribute("data-price")) || 0;
            const quantity = parseInt(item.querySelector(".quantity").textContent) || 1;
            subtotal += price * quantity;
        });

        // Update Subtotal Display
        subtotalElement.textContent = `₱ ${subtotal.toFixed(2)}`;

        // Get Promo Discount Value (Ensure it's a valid number)
        let discount = parseFloat(promoDiscountElement.textContent.replace("₱", "").trim()) || 0;
        promoDiscountElement.textContent = `₱ ${discount.toFixed(2)}`;

        // Determine Delivery Fee
        let deliveryFee = orderMode.value === "delivery" ? 50 : 0;
        deliveryFeeElement.textContent = `₱ ${deliveryFee.toFixed(2)}`;

        // Calculate Final Total Amount
        let total = subtotal + deliveryFee - discount;
        totalAmountElement.textContent = `₱ ${total.toFixed(2)}`;
    };

    /** ✅ QUANTITY CONTROL **/
    window.changeQuantity = function (button, change) {
        const quantitySpan = button.parentElement.querySelector(".quantity");
        let quantity = parseInt(quantitySpan.textContent);
        quantitySpan.textContent = Math.max(1, quantity + change); // Prevent negative quantity
        updateTotal();
    };

    /** ✅ REMOVE ITEM FROM CART **/
    window.removeCartItem = function (button) {
        button.closest(".cart-item").remove();
        updateTotal();
    };

    /** ✅ LISTEN FOR ORDER MODE CHANGE (PICKUP OR DELIVERY) **/
    if (orderMode) {
        orderMode.addEventListener("change", updateTotal);
    }

    /** ✅ INITIAL TOTAL CALCULATION **/
    updateTotal();
});

//favorite
function toggleFavorite(productId, element) {
    fetch(`/favorite/${productId}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "added") {
            element.classList.add("text-red-500");
            element.classList.remove("text-gray-400");
        } else {
            element.classList.add("text-gray-400");
            element.classList.remove("text-red-500");
        }
    });
}

function selectPayment(button, method) {
    // Remove highlight from all buttons
    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.classList.remove('bg-[#745858]', 'text-white');
        btn.classList.add('bg-white', 'text-black');
    });

    // Add highlight to selected button
    button.classList.remove('bg-white', 'text-black');
    button.classList.add('bg-[#745858]', 'text-white');

    // Update the payment method input
    document.getElementById('paymentMethodInput').value = method;
    
    // Update the payment method display in the order summary
    const paymentMethodDisplay = document.getElementById('paymentMethodDisplay');
    if (paymentMethodDisplay) {
        paymentMethodDisplay.value = method === 'gcash' ? 'GCash' : 'Cash';
    }
}

function handlePaymentProcess(event) {
    if (event) event.preventDefault();
    
    const paymentMethod = document.getElementById('paymentMethodInput').value.toLowerCase();
    const name = document.getElementById('customer_name')?.value;
    const email = document.getElementById('customer_email')?.value;
    const phone = document.getElementById('customer_phone')?.value;
    const address = document.getElementById('delivery_address')?.value;
    const totalAmount = document.getElementById('totalAmountInput').value;
    const items = document.getElementById('itemsInput').value;

    if (!name || !email || !phone || !address) {
        alert('Please fill in all required fields');
        return;
    }

    if (!paymentMethod) {
        alert('Please select a payment method');
        return;
    }

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (!csrfToken) {
        alert('CSRF token not found. Please refresh the page.');
        return;
    }

    // Show loading state
    const confirmButton = document.querySelector('[onclick*="handlePaymentProcess"]');
    if (confirmButton) {
        confirmButton.disabled = true;
        confirmButton.textContent = 'Processing...';
    }
    
    if (paymentMethod === 'gcash') {
        // For GCash, proceed to PayMongo payment
        fetch("/pay-with-gcash", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({
                total_amount: parseFloat(totalAmount),
                name: name,
                email: email,
                phone: phone,
                delivery_address: address,
                items: items
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.checkout_url) {
                window.location.href = data.checkout_url;
            } else {
                throw new Error(data.message || 'Failed to initiate payment');
            }
        })
        .catch(error => {
            console.error("Error during GCash payment:", error);
            alert(error.message || 'Something went wrong. Please try again.');
            if (confirmButton) {
                confirmButton.disabled = false;
                confirmButton.textContent = 'Proceed to Payment';
            }
        });
    } else if (paymentMethod === 'cash') {
        // For cash payment, create order and proceed to delivery user page
        fetch("/orders", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({
                total_amount: parseFloat(totalAmount),
                user_name: name,
                email: email,
                phone: phone,
                delivery_address: address,
                items: JSON.parse(items),
                payment_method: 'cash',
                payment_status: 'unpaid',
                status: 'pending'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.order_id) {
                window.location.href = `/deliveryuser/${data.order_id}`;
            } else {
                throw new Error(data.message || 'Failed to create order');
            }
        })
        .catch(error => {
            console.error("Error during cash order:", error);
            alert('Something went wrong. Please try again.');
            if (confirmButton) {
                confirmButton.disabled = false;
                confirmButton.textContent = 'Proceed to Payment';
            }
        });
    }
}

function showOrderSummary() {
    const modal = document.getElementById('orderSummaryModal');
    const summaryItems = document.getElementById('summaryItems');
    const cartItems = document.querySelectorAll('.cart-item');
    
    if (!modal || !summaryItems || cartItems.length === 0) {
        alert('Please add items to your cart first.');
        return;
    }

    // Show the modal
    modal.classList.remove('hidden');
    
    // Clear previous items
    summaryItems.innerHTML = '';
    
    let subtotal = 0;
    const items = [];

    // Add each cart item to the summary
    cartItems.forEach(item => {
        const name = item.querySelector('h3').textContent.split('(')[0].trim();
        const option = item.querySelector('p.text-sm.text-gray-500')?.textContent || '';
        const price = parseFloat(item.getAttribute('data-price'));
        const quantity = parseInt(item.querySelector('.quantity').textContent);
        const itemTotal = price * quantity;
        subtotal += itemTotal;

        // Add to items array for form submission
        items.push({
            name: name,
            price: price,
            quantity: quantity,
            option: option
        });
        
        // Add item to summary display
        summaryItems.innerHTML += `
            <div class="flex justify-between items-center py-2 border-b">
                <div class="flex-1">
                    <div class="font-medium">${name}</div>
                    ${option ? `<div class="text-sm text-gray-500">${option}</div>` : ''}
                    <div class="text-sm text-gray-500">Quantity: ${quantity}</div>
                </div>
                <div class="text-right">
                    <div class="font-medium">₱${itemTotal.toFixed(2)}</div>
                    <div class="text-sm text-gray-500">₱${price.toFixed(2)} each</div>
                </div>
            </div>
        `;
    });

    // Get delivery fee and discount
    const deliveryFee = document.getElementById('orderMode').value === 'delivery' ? 50 : 0;
    const discount = parseFloat(document.getElementById('promoDiscount').getAttribute('data-discount')) || 0;
    
    // Calculate total
    const total = subtotal + deliveryFee - discount;

    // Update summary totals
    document.getElementById('summarySubtotal').textContent = `₱${subtotal.toFixed(2)}`;
    document.getElementById('summaryDeliveryFee').textContent = `₱${deliveryFee.toFixed(2)}`;
    document.getElementById('summaryDiscount').textContent = `₱${discount.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `₱${total.toFixed(2)}`;

    // Get selected payment method
    const selectedPaymentBtn = document.querySelector('.payment-btn.bg-\\[\\#745858\\]');
    const paymentMethod = selectedPaymentBtn ? selectedPaymentBtn.textContent.trim() : '';

    // Update payment method display
    const paymentMethodDisplay = document.getElementById('paymentMethodDisplay');
    if (paymentMethodDisplay) {
        paymentMethodDisplay.value = paymentMethod;
    }

    // Update hidden form inputs
    document.getElementById('itemsInput').value = JSON.stringify(items);
    document.getElementById('totalAmountInput').value = total.toFixed(2);
    document.getElementById('paymentMethodInput').value = paymentMethod.toLowerCase();
}

function closeOrderSummary() {
    const modal = document.getElementById('orderSummaryModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function getCurrentLocation() {
    if ("geolocation" in navigator) {
        const locationError = document.getElementById('locationError');
        locationError.classList.add('hidden');
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                // Use reverse geocoding to get address
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        const address = data.display_name;
                        document.getElementById('locationInput').value = address;
                    })
                    .catch(error => {
                        locationError.textContent = "Could not get address from coordinates";
                        locationError.classList.remove('hidden');
                    });
            },
            function(error) {
                const locationError = document.getElementById('locationError');
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        locationError.textContent = "Location permission denied. Please enter your location manually.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        locationError.textContent = "Location information unavailable. Please enter your location manually.";
                        break;
                    case error.TIMEOUT:
                        locationError.textContent = "Location request timed out. Please enter your location manually.";
                        break;
                    default:
                        locationError.textContent = "An unknown error occurred. Please enter your location manually.";
                }
                locationError.classList.remove('hidden');
            }
        );
    } else {
        const locationError = document.getElementById('locationError');
        locationError.textContent = "Geolocation is not supported by your browser. Please enter your location manually.";
        locationError.classList.remove('hidden');
    }
}

// Update the form submission to include location
document.getElementById('orderForm').addEventListener('submit', function(e) {
    const locationInput = document.getElementById('locationInput');
    if (!locationInput.value.trim()) {
        e.preventDefault();
        const locationError = document.getElementById('locationError');
        locationError.textContent = "Please enter your location";
        locationError.classList.remove('hidden');
    }
});

function updateDeliveryLocation() {
    const locationError = document.getElementById('deliveryLocationError');
    const locationInput = document.getElementById('deliveryLocation');
    
    locationError.classList.add('hidden');

    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Use reverse geocoding to get address
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        const address = data.display_name;
                        
                        // Update the location in the database
                        fetch('{{ route("update.location") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                location: address,
                                latitude: latitude,
                                longitude: longitude
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                locationInput.value = address;
                            } else {
                                throw new Error(data.message || 'Failed to update location');
                            }
                        })
                        .catch(error => {
                            locationError.textContent = error.message;
                            locationError.classList.remove('hidden');
                        });
                    })
                    .catch(error => {
                        locationError.textContent = "Could not get address from coordinates";
                        locationError.classList.remove('hidden');
                    });
            },
            function(error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        locationError.textContent = "Location permission denied. Please allow location access.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        locationError.textContent = "Location information unavailable.";
                        break;
                    case error.TIMEOUT:
                        locationError.textContent = "Location request timed out.";
                        break;
                    default:
                        locationError.textContent = "An unknown error occurred.";
                }
                locationError.classList.remove('hidden');
            }
        );
    } else {
        locationError.textContent = "Geolocation is not supported by your browser.";
        locationError.classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const isShopOpen = {{ $isShopOpen ? 'true' : 'false' }};
    
    // Function to update UI based on shop status
    function updateUIForShopStatus(isOpen) {
        const buttons = document.querySelectorAll('.option-btn, .add-to-cart-btn');
        const billingBtn = document.getElementById('billingBtn');
        
        buttons.forEach(button => {
            if (!isOpen) {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.title = 'Shop is currently closed';
            } else {
                // Only enable if not otherwise disabled
                if (!button.hasAttribute('data-disabled')) {
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                    button.removeAttribute('title');
                }
            }
        });

        // Update billing button
        if (billingBtn) {
            if (!isOpen) {
                billingBtn.disabled = true;
                billingBtn.classList.add('opacity-50', 'cursor-not-allowed');
                billingBtn.title = 'Shop is currently closed';
            } else {
                billingBtn.disabled = false;
                billingBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                billingBtn.removeAttribute('title');
            }
        }
    }

    // Initial UI update
    updateUIForShopStatus(isShopOpen);

    // Add event listener for shop status changes
    window.addEventListener('shop-status-changed', function(event) {
        updateUIForShopStatus(event.detail.isOpen);
    });

    // Override the addToBillingCart function to check shop status
    const originalAddToBillingCart = window.addToBillingCart;
    window.addToBillingCart = function(productId) {
        if (!isShopOpen) {
            alert('Sorry, the shop is currently closed. Please try again during operating hours.');
            return;
        }
        originalAddToBillingCart(productId);
    };
});

</script>


</body>
</html>
