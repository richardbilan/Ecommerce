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
        <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="best seller">Best Seller</button>
    </div>
</div>


<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Choose Menu</h2>

    <!-- Grid layout for the menu items -->
    <div id="productContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach ($products as $product)

        <!-- Product Card -->
        <div class="product-item bg-[#F5F5F5] p-6 rounded-xl shadow-md flex flex-col transition-transform duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md hover:bg-[#f0e6db]" data-category="{{ strtolower(trim($product->category ?? 'uncategorized')) }}">



            <!-- Horizontal Layout: Image Left, Details Right -->
            <div class="flex flex-row items-start gap-6">

                <!-- Product Image -->
                <div class="relative">
                    <img
                        src="{{ asset('images/' . ($product->image ?? 'cup.png')) }}"
                        alt="{{ $product->product_name }}"
                        class="w-[100px] h-[144px] rounded-[10px] object-cover"
                    />

                    <!-- Best Seller Badge -->
                    @if ($product->is_best_seller ?? false)
                        <span class="absolute bottom-[-10px] left-1/2 transform -translate-x-1/2 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-lg shadow-md">
                            Best Seller
                        </span>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="flex flex-col justify-between h-full flex-1">

                    <!-- Name, Availability & Category/Description -->
                    <div class="mb-4">

              <div class="flex items-center justify-between mb-1">
                            <!-- Product Name -->
                            <h3 class="text-xl font-bold text-gray-800">
                                {{ $product->product_name }}
                            </h3>

                            <!-- Availability -->
                            @if ($product->availability === 'In Stock')
                                <p class="text-green-600 text-sm font-semibold">Available</p>
                            @else
                                <p class="text-red-600 text-sm font-semibold">Out of Stock</p>
                            @endif
                        </div>

                        <!-- Product Category (Coffee/Non-Coffee Series) -->
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
  <button
    onclick="addToBillingCart(
      '{{ $product->product_name }}',
      'Hot',
      '{{ $product->price_hot ?? 0 }}'
    )"
    class="bg-[#745858] text-white hover:bg-[#5a4444] transition-all duration-300 w-[92px] h-[26px] rounded-[20px] text-sm flex items-center justify-center"
    type="button"
    @if (($product->price_hot ?? 0) <= 0) disabled @endif
  >
    Hot: {{ ($product->price_hot ?? 0) > 0 ? '₱' . number_format($product->price_hot, 2) : 'N/A' }}
  </button>

  <!-- Iced Button -->
  <button
    onclick="addToBillingCart(
      '{{ $product->product_name }}',
      'Iced',
      '{{ $product->price_iced ?? 0 }}'
    )"
    class="bg-[#745858] text-white hover:bg-[#5a4444] transition-all duration-300 w-[92px] h-[26px] rounded-[20px] text-sm flex items-center justify-center"
    type="button"
    @if (($product->price_iced ?? 0) <= 0) disabled @endif
  >
    Iced: {{ ($product->price_iced ?? 0) > 0 ? '₱' . number_format($product->price_iced, 2) : 'N/A' }}
  </button>
</div>

                    <!-- Add to Cart Button -->
                    <button
                        onclick="addToBillingCart(
                            '{{ $product->product_name }}',
                            '{{ $product->price_hot ?? 0 }}',
                            '{{ $product->price_iced ?? 0 }}'
                        )"
                        class="bg-[#745858] text-white py-2 rounded-[20px] hover:bg-[#5a4444] transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed w-full"
                        @if ($product->availability !== 'In Stock') disabled @endif
                    >
                        {{ $product->availability === 'In Stock' ? 'Add to Cart' : 'Unavailable' }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

                 <!-- Product Name and Availability Row -->



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

 <div class="mt-4">
    <label for="promoCodeInput" class="block text-sm font-medium mb-1">Enter Promo Code</label>
    <div class="flex space-x-2">
        <input type="text" id="promoCodeInput" class="flex-1 p-2 border border-gray-300 rounded" placeholder="Enter code...">
        <button id="applyPromoBtn" class="bg-[#745858] text-white px-4 py-2 rounded hover:bg-[#5a4444] text-sm">Apply</button>
    </div>
    <p id="promoFeedback" class="text-xs mt-2 text-green-600 hidden">Promo Applied Successfully!</p>
    <p id="promoError" class="text-xs mt-2 text-red-600 hidden">Invalid or Expired Promo Code!</p>
</div>

    <p id="promoFeedback" class="text-xs mt-2 text-green-600 hidden">Promo Applied Successfully!</p>
    <p id="promoError" class="text-xs mt-2 text-red-600 hidden">Invalid or Expired Promo Code!</p>
</div>

<!-- Order Summary Section -->
<div class="mt-4 border-t pt-4 space-y-2">
    <div class="flex justify-between">
        <span>Delivery Fee</span>
        <span id="deliveryFee">₱ 50.00</span>
    </div>

    <div class="flex justify-between">
        <span>Promo Discount</span>
        <span id="promoDiscount">₱ 0.00</span>
    </div>

    <div class="flex justify-between font-bold border-t pt-2">
        <span>Total</span>
        <span id="totalAmount">₱ 0.00</span>
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
<a href="{{ route('deliveryuser') }}" class="block w-full mt-7 p-2 text-center bg-[#745858] text-white rounded hover:bg-[#553C26]">
    Place Order
</a>





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

        /** ✅ ADD TO BILLING CART FUNCTION **/
        window.addToBillingCart = function (productName, priceHot, priceCold) {
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

            document.getElementById("cartItems").insertAdjacentHTML("beforeend", cartItemHTML);
            rightSidebar.classList.remove("translate-x-full");

            updateTotal();
        };

        window.removeCartItem = function (button) {
            button.closest(".cart-item").remove();
            updateTotal();
        };

        window.changeQuantity = function (button, change) {
            const quantitySpan = button.parentElement.querySelector(".quantity");
            let quantity = parseInt(quantitySpan.textContent);

            quantity = Math.max(1, quantity + change);
            quantitySpan.textContent = quantity;

            updateTotal();
        };

        window.updateTotal = function () {
            let total = 0;
            document.querySelectorAll(".cart-item").forEach((item) => {
                const price = parseFloat(item.getAttribute("data-price"));
                const quantity = parseInt(item.querySelector(".quantity").textContent);
                total += price * quantity;
            });

            document.getElementById("totalAmount").textContent = `₱ ${total.toFixed(2)}`;
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
        document.getElementById("placeOrderBtn")?.addEventListener("click", function () {
            localStorage.setItem("orderCart", JSON.stringify(cartItems));
            window.location.href = "delivery.html";
        });
    });
</script>


</body>
</html>
