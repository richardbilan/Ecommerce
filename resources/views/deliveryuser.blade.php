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
                    <a href="{{ route('user_desktop') }}" class="flex flex-col items-center p-3 hover:bg-[#745858] hover:text-white rounded group">
                        <img src="{{ asset('images/home.png') }}" alt="Home Icon" class="w-9 h-9 mb-1 group-hover:hidden">
                        <img src="{{ asset('images/home_white.png') }}" alt="Home Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
                        <span>Home</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="#" id="billingBtn" class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded group">
                        <img src="{{ asset('images/billing.png') }}" alt="Billing Icon" class="w-9 h-9 mb-1 group-hover:hidden">
                        <img src="{{ asset('images/billing_white.png') }}" alt="Billing Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
                        <span>Billings</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('deliveryuser') }}" class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded group">
                        <img src="{{ asset('images/delivery.png') }}" alt="Delivery Icon" class="w-9 h-9 mb-1 group-hover:hidden">
                        <img src="{{ asset('images/delivery_white.png') }}" alt="Delivery Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
                        <span>Delivery</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="relative">
        <button id="userMenuBtn" class="flex flex-col items-center p-2 w-full ml-0.5 hover:bg-[#745858] hover:text-white rounded group">
            <img src="{{ asset('images/user.png') }}" alt="User Icon" class="w-9 h-9 mb-1 group-hover:hidden">
            <img src="{{ asset('images/user_white.png') }}" alt="User Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
            <span>User</span>
        </button>
        <div id="userMenu" class="absolute hidden bg-[#EADBC8] text-black left-0 top-[-7.6rem] mt-2 rounded shadow-md w-15 text-center text-xs">
            <a href="{{ route('account_settings') }}" class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Account Settings</a>
            <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Logout</a>
        </div>
    </div>
</aside>

<!-- Main Content -->
    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold">Your Delivery, User!</h1>

        <!-- Location and Details -->
        <div class="mt-4 flex gap-6">
            <div class="w-3/4">
            <img src="{{ asset('images/valid-image.jpg') }}" alt="Map" class="w-[1500px] h-[500px] rounded-lg object-cover">
                <button class="mt-2 px-4 py-2 border rounded hover:bg-gray-200">View Location</button>

                <div class="mt-4 p-4 border rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold">Estimated Delivery Time</h2>
                    <p>10 - 15 minutes</p>
                </div>

                <div class="mt-4">
                    <h2 class="text-lg font-semibold flex items-center">📍 Details of Location</h2>
                    <p>Purok-1 Tagas, Daraga</p>
                </div>

                <div class="mt-4">
                    <h2 class="text-lg font-semibold">Your Receipt</h2>
                    <p>Order# 12345</p>
                </div>
            </div>

            <!-- Tracking Order Section -->
            <div class="w-1/3 bg-[#D7B899] p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold">Track Order</h2>
                <div class="mt-2 space-y-4 border-l-2 border-gray-600 pl-4">
                    <div class="p-3 bg-white border rounded-lg shadow-sm">
                        <h3 class="font-bold">10:00 - 10:10</h3>
                        <p class="text-sm">Thanks for your order<br>We have received your order and are on it</p>
                    </div>
                    <div class="p-3 bg-white border rounded-lg shadow-sm">
                        <h3 class="font-bold">10:10 - 10:15</h3>
                        <p class="text-sm">Preparing your order<br>We are on it</p>
                    </div>
                    <div class="p-3 bg-white border rounded-lg shadow-sm">
                        <h3 class="font-bold">10:15 - 10:20</h3>
                        <p class="text-sm">Delivering your order<br>Let's go, on the way</p>
                    </div>
                    <div class="p-3 bg-white border rounded-lg shadow-sm">
                        <h3 class="font-bold">10:20 - 10:25</h3>
                        <p class="text-sm">Delivered<br>Enjoy your coffee</p>
                    </div>
                </div>

                <!-- Leave a Review Button -->
                <button onclick="openModal()" class="mt-2 w-full py-2 border rounded hover:bg-gray-200">Leave a Review</button>

                <!-- Order Again Button -->
                <a href="#" id="billingBtnn" class="mt-4 w-full py-2 bg-[#6F4E37] text-white rounded hover:bg-[#553C26] flex justify-center">
    Order it again
</a>

                <!-- Review Modal -->
                <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                        <h2 class="text-lg font-semibold mb-4">Leave a Review</h2>

                    <!-- Rating Input -->
                    <label class="block mb-2">Rating:</label>
                    <div class="flex space-x-1 mb-4">
                        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(1)">&#9733;</span>
                        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(2)">&#9733;</span>
                        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(3)">&#9733;</span>
                        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(4)">&#9733;</span>
                        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(5)">&#9733;</span>
                    </div>

                    <!-- Comment Input -->
                    <label class="block mb-2">Comment:</label>
                    <textarea id="reviewComment" class="w-full p-2 border rounded" placeholder="Write your review here..."></textarea>

                    <!-- Buttons -->
                    <div class="mt-4 flex justify-end space-x-2">
                        <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                        <button onclick="submitReview()" class="px-4 py-2 bg-[#6F4E37] text-white rounded">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar (Billing) -->
    <aside id="rightSidebar" class="fixed right-0 top-0 w-80 bg-[#EADBC8] h-full shadow-lg transform translate-x-full transition-transform duration-300 p-4">
    <button id="closeSidebar" class="mb-4" onclick="toggleSidebar(false)">
        <img src="{{ asset('images/close.png') }}" alt="Close Icon" class="w-6 h-6">
    </button>
    <h2 class="text-xl font-bold mb-4">Billings</h2>
    <div id="orderList" class="space-y-4"></div>

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

document.getElementById("billingBtnn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevents page reload
    document.getElementById("rightSidebar").classList.remove("translate-x-full");
});

document.getElementById("closeSidebar").addEventListener("click", function() {
    document.getElementById("rightSidebar").classList.add("translate-x-full");
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

// MODAL FOR LEAVE A REVIEW
let selectedRating = 0;

    function openModal() {
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    function setRating(rating) {
        selectedRating = rating;
        const stars = document.querySelectorAll('#reviewModal span');
        stars.forEach((star, index) => {
            star.style.color = index < rating ? '#FFD700' : '#ccc';
        });
    }

    function submitReview() {
        const comment = document.getElementById('reviewComment').value;
        if (selectedRating === 0) {
            alert('Please select a rating');
            return;
        }
        if (!comment.trim()) {
            alert('Please write a comment');
            return;
        }
        alert(`Review Submitted!\nRating: ${selectedRating}\nComment: ${comment}`);
        closeModal();
    }
        function redirectToSidebar() {
        const sidebar = document.getElementById('sidebar'); // Assuming sidebar has this ID
        if (sidebar) {
            sidebar.scrollIntoView({ behavior: 'smooth' });
        }
    }
</script>

    </script>
</body>
</html>
