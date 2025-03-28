<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Laravel Frontend</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg--100">
<!-- Left Sidebar -->
<aside class="w-30 bg-[#EADBC8] h-screen text-black flex flex-col justify-between p-4 sticky top-0">
    <div>
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-[20px]">
        </div>
        <nav>
            <ul>
                <li class="mb-4">
                    <a href="#" class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded">
                        <img src="{{ asset('images/home.png') }}" alt="Home Icon" class="w-6 h-6 mb-1">
                        <span>Home</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="#" id="billingBtn" class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded">
                        <img src="{{ asset('images/billing.png') }}" alt="Billing Icon" class="w-6 h-6 mb-1">
                        <span>Billings</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="#" class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded">
                        <img src="{{ asset('images/delivery.png') }}" alt="Delivery Icon" class="w-6 h-6 mb-1">
                        <span>Delivery</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

        <div class="relative">
        <button id="userMenuBtn" class="flex flex-col items-center p-2 w-full ml-0.5 hover:bg-[#745858] hover:text-white rounded">
            <img src="{{ asset('images/user.png') }}" alt="User Icon" class="w-6 h-6 mb-1">
            <span>User</span>
        </button>
        <div id="userMenu" class="absolute hidden bg-[#EADBC8] text-black left-0 top-[-7.6rem] mt-2 rounded shadow-md w-15 text-center text-xs">
            <a href="#" class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Account Settings</a>
            <a href="#" class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Logout</a>
        </div>

        </div>
    </aside>

<!-- Main Content -->
    <main class="flex-1 p-6">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Welcome, User!</h1>
            <div class="relative">
            <input type="text" placeholder="Search Coffee..." class="p-2 border border-[#745858] rounded w-64 bg-[#EADBC8] text-black">
            <button class="absolute right-2 top-2">
                <img src="{{ asset('images/search.png') }}" alt="Search Icon" class="w-5 h-5">
            </button>
            </div>


        </header>

        <!-- Slideshow -->
        <div class="w-full h-64 overflow-hidden relative">
        <img src="{{ asset('images/image1.jpeg') }}" class="absolute inset-0 w-full h-full object-cover rounded-xl" alt="Slideshow 1">
        <img src="{{ asset('images/image2.jpeg') }}" class="absolute inset-0 w-full h-full object-cover hidden rounded-xl" alt="Slideshow 2">
        <img src="{{ asset('images/image1.jpeg') }}" class="absolute inset-0 w-full h-full object-cover hidden rounded-xl" alt="Slideshow 3">
        <img src="{{ asset('images/image1.jpeg') }}" class="absolute inset-0 w-full h-full object-cover hidden rounded-xl" alt="Slideshow 4">
        <img src="{{ asset('images/image2.jpeg') }}" class="absolute inset-0 w-full h-full object-cover hidden rounded-xl" alt="Slideshow 5">
        </div>

        <!-- Category Section -->
        <div class="mt-6 text-center">
            <h2 class="text-xl font-bold mb-4">Choose Your Category</h2>
        <div class="flex justify-center gap-4">
            <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="all">All</button>
            <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="coffee">Coffee</button>
            <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="non-coffee">Non-Coffee</button>
            <button class="category-btn bg-[#EADBC8] text-black px-4 py-2 rounded hover:bg-[#745858] hover:text-white" data-category="promo">Promo Deals</button>
            </div>
        </di>

        <!-- Product Grid -->
        <div id="productContainer" class="grid grid-cols-4 gap-4 mt-6">
            <!-- Images will be inserted here dynamically -->
        </div>
    </main>
    <!-- Right Sidebar (Billing) -->
    <aside id="rightSidebar" class="fixed right-0 top-0 w-80 bg-[#EADBC8] h-full shadow-lg transform translate-x-full transition-transform duration-300 p-4">
    <!-- Close Button -->
    <button id="closeSidebar" class="mb-4">
        <img src="{{ asset('images/close.png') }}" alt="Close Icon" class="w-6 h-6">
    </button>


    <!-- Billing Header -->
    <h2 class="text-xl font-bold mb-4">Billings</h2>

    <!-- Cart Items -->
    <div id="cartItems" class="space-y-4">
        <!-- Example Cart Item -->
        <div class="bg-white p-3 rounded shadow-md">
            <div class="flex justify-between items-center">
            <div>
                <h3 class="font-semibold">Americano <span class="text-sm text-gray-500">(Cold, Tall)</span></h3>
                <p class="text-sm">₱ 28</p>
            </div>
            <button>
                <img src="{{ asset('images/close.png') }}" alt="Remove Item" class="w-5 h-5">
            </button>
            </div>

            <div class="flex justify-between items-center mt-2">
    <!-- Heart Button -->
        <button id="favoriteBtn" class="text-gray-500 transition-colors duration-300">
            <svg id="heartIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21l-1.45-1.316C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.184L12 21z"/>
            </svg>
        </button>

        <!-- Quantity Buttons -->
        <div class="flex items-center space-x-2">
            <button id="decreaseBtn" class="bg-gray-300 px-2 py-1 rounded">-</button>
            <span id="quantity" class="w-6 text-center">1</span>
            <button id="increaseBtn" class="bg-gray-300 px-2 py-1 rounded">+</button>
        </div>
    </div>
    </div>
    </div>
        <!-- can add more items -->
    </div>

    <!-- Order Summary -->
    <div class="mt-2 border-t pt-2">
        <div class="flex justify-between">
            <span>Delivery Fee</span>
            <span>₱ 888</span>
        </div>
        <div class="flex justify-between">
            <span>Promo</span>
            <span>₱ 28</span>
        </div>
        <div class="flex justify-between font-bold border-t mt-2 pt-2">
            <span>Total</span>
            <span>₱ 28</span>
        </div>
    </div>

    <!-- Mode of Order -->
    <div class="mt-6">
        <label class="block font-bold">Mode of order</label>
        <select id="orderMode" class="w-full p-2 border rounded bg-white text-black text-center appearance-none">
            <option value="delivery">🚚 Delivery</option>
            <option value="pickup">🏠 Pick Up</option>
        </select>
        <button id="locationBtn" class="w-full mt-2 p-2 bg-white text-black border rounded">Enter Your Location</button>
    </div>

    <!-- Payment Method -->
    <div class="mt-6">
    <label class="block font-bold">Payment Method</label>
    <div class="flex justify-between mt-2">
        <button class="w-1/2 p-2 bg-white text-black border border-gray-500 rounded transition-colors duration-300 hover:bg-[#745858] hover:text-white">
            Gcash
        </button>
        <button class="w-1/2 p-2 bg-white text-black border border-gray-500 rounded ml-2 transition-colors duration-300 hover:bg-[#745858] hover:text-white">
            Cash
        </button>
    </div>
</div>

    <!-- Place Order Button -->
    <button class="w-full mt-7 p-2 bg-[#745858] text-white rounded">Place Order</button>
</aside>

    <script>
        document.getElementById('billingBtn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('rightSidebar').classList.remove('translate-x-full');
        });
        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('rightSidebar').classList.add('translate-x-full');
        });
        document.getElementById('userMenuBtn').addEventListener('click', function() {
            document.getElementById('userMenu').classList.toggle('hidden');
        });

        // Slideshow Functionality
        let slides = document.querySelectorAll('.w-full.h-64.overflow-hidden.relative img');
        let index = 0;
        setInterval(() => {
            slides[index].classList.add('hidden');
            index = (index + 1) % slides.length;
            slides[index].classList.remove('hidden');
        }, 2000);

         // Change button text based on mode of order
        document.getElementById("orderMode").addEventListener("change", function () {
        let locationBtn = document.getElementById("locationBtn");
        locationBtn.textContent = this.value === "pickup" ? "Current Location" : "Enter Your Location";
        });

        //heart button
        document.getElementById('favoriteBtn').addEventListener('click', function() {
        let heartIcon = document.getElementById('heartIcon');
        if (heartIcon.classList.contains('text-gray-500')) {
            heartIcon.classList.remove('text-gray-500');
            heartIcon.classList.add('text-red-500');
            heartIcon.setAttribute("fill", "red"); // Fill color red when favorited
        } else {
            heartIcon.classList.remove('text-red-500');
            heartIcon.classList.add('text-gray-500');
            heartIcon.setAttribute("fill", "none"); // Remove fill color when unfavorited
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const quantityDisplay = document.getElementById("quantity");
        const increaseBtn = document.getElementById("increaseBtn");
        const decreaseBtn = document.getElementById("decreaseBtn");

        let quantity = 1; // Default quantity

        // Increase Quantity
        increaseBtn.addEventListener("click", function () {
            quantity++;
            quantityDisplay.textContent = quantity;
        });

        // Decrease Quantity (Minimum 1)
        decreaseBtn.addEventListener("click", function () {
            if (quantity > 1) {
                quantity--;
                quantityDisplay.textContent = quantity;
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const productContainer = document.getElementById("productContainer");
    const categoryButtons = document.querySelectorAll(".category-btn");

    // Sample Image Data
    const products = {
        all: [
            ...Array(12).fill({ category: "coffee", img: "{{ asset('images/coffee1.png') }}" }),
            ...Array(12).fill({ category: "non-coffee", img: "{{ asset('images/noncoffee1.png') }}" })
        ],
        coffee: Array(12).fill({ category: "coffee", img: "{{ asset('images/coffee1.png') }}" }),
        "non-coffee": Array(12).fill({ category: "non-coffee", img: "{{ asset('images/noncoffee1.png') }}" }),
        promo: Array(8).fill({ category: "promo", img: "{{ asset('images/promo1.png') }}" })
    };

    // Function to display products
    function displayProducts(category) {
        productContainer.innerHTML = ""; // Clear previous products
        const selectedProducts = products[category] || [];
        selectedProducts.forEach((product) => {
            const productHTML = `
                <div class="bg-white p-3 rounded shadow-md">
                    <img src="${product.img}" class="w-full h-40 object-cover rounded" alt="Product">
                    <button class="w-full mt-2 p-2 bg-[#EADBC8] text-black rounded hover:bg-[#745858] hover:text-white">Add to Cart</button>
                </div>
            `;
            productContainer.innerHTML += productHTML;
        });
    }

    // Initial Load (Show All)
    displayProducts("all");

    // Add Event Listeners to Category Buttons
    categoryButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const category = this.getAttribute("data-category");
            displayProducts(category);
        });
    });
});

    </script>
</body>
</html>
