<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg--100 min-h-screen flex">

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
                        <a href="{{ route('delivery') }}" class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded group">
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

<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col items-center">

    <!-- Mini Header Navigation -->
    <header class="w-full max-w-15xl bg-[#8C5A3C] text-white flex justify-around py-5 shadow-md">
        <button onclick="showTab('personal-info')" class="header-btn hover:bg-[#EADBC8] hover:text-black rounded px-3 py-3 transition-all duration-200 hover:px-8 hover:py-3">
            Personal Info
        </button>
        <button onclick="showTab('transaction-history')" class="header-btn hover:bg-[#EADBC8] hover:text-black rounded px-3 py-3 transition-all duration-200 hover:px-8 hover:py-3">
            Transactions
        </button>
        <button onclick="showTab('chat-support')" class="header-btn hover:bg-[#EADBC8] hover:text-black rounded px-3 py-3 transition-all duration-200 hover:px-8 hover:py-3">
            Chat Support
        </button>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-7xl bg-white p-6 rounded-lg shadow-lg mt-6">
        
        <!-- Personal Information (Default Visible) -->
        <section id="personal-info" class="settings-tab w-full max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-[#4E3629] mb-6">Personal Information</h2>
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Profile Picture -->
                <div class="relative w-40 h-40 mx-auto md:mx-0">
                    <img src="{{ asset('images/profile-placeholder.png') }}" class="w-full h-full rounded-full border-4 border-[#8C5A3C] shadow-md">
                    <label for="profile-pic" class="absolute bottom-2 right-2 bg-[#8C5A3C] p-2 rounded-full cursor-pointer shadow">
                        <img src="{{ asset('images/edit-icon.png') }}" class="w-5 h-5">
                    </label>
                    <input type="file" id="profile-pic" class="hidden">
                </div>

                <!-- Information Fields -->
                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first-name" class="input-label block">First Name</label>
                            <input type="text" id="first-name" class="input-box w-full" placeholder="Enter your first name"
                                style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                        </div>
                        <div>
                            <label for="last-name" class="input-label block">Last Name</label>
                            <input type="text" id="last-name" class="input-box w-full" placeholder="Enter your last name"
                                style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                        </div>
                        <div>
                            <label class="input-label block">Birthdate</label>
                            <input type="date" class="input-box w-full" placeholder="Select your birthdate"
                                style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                        </div>
                        <div>
                            <label class="input-label block">Email</label>
                            <input type="email" class="input-box w-full" placeholder="Enter your email address"
                                style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                        </div>
                        <div>
                            <label class="input-label block">Phone Number</label>
                            <input type="tel" class="input-box w-full" placeholder="Enter your phone number"
                                style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                        </div>
                        <div>
                            <label class="input-label block">Gender</label>
                            <select class="input-box w-full"
                            style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                                <option disabled selected>Select your gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </div>

                <h2 class="text-2xl font-bold text-[#4E3629] mt-6">Settings and Privacy</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Change Password Section -->
                <div>
                    <label class="input-label block mt-3">Current Password</label>
                    <input type="password" id="current-password" class="input-box w-full" placeholder="Enter current password"
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                        <button id="forgot-password" class="bg-gray-500 text-white px-3 py-1 rounded-lg mt-2">Forgot Password?</button>
                </div>
                <div>
                    <label class="input-label block mt-6">New Password</label>
                    <input type="password" id="new-password" class="input-box w-full" placeholder="Enter new password"
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                </div>
                <div>
                    <label class="input-label block">Confirm New Password</label>
                    <input type="password" id="confirm-password" class="input-box w-full" placeholder="Confirm new password"
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                </div>
            <button id="save-password" class="bg-[#8C5A3C] text-white px-6 py-2 rounded-lg mt-4">Save Password</button>


        <!-- Change Password Modal -->
            <div id="password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <p class="text-lg font-bold">Changed Password Confirmed</p>
                    <button id="close-modal" class="bg-[#8C5A3C] text-white px-4 py-2 rounded-lg mt-4 mx-auto block">OK</button>
                </div>
            </div>

        <!-- Forgot Password Modals -->
            <div id="forgot-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <p class="text-lg font-bold">Enter your email to receive a verification code</p>
                    <input type="email" id="reset-email" class="input-box w-full my-2" placeholder="Enter your email" style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                    <button id="send-code" class="bg-[#8C5A3C] text-white px-4 py-2 rounded-lg">Send Code</button>
                </div>
            </div>

            <div id="input-code-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <p class="text-lg font-bold">Enter the verification code sent to your email</p>
                    <input type="text" id="verification-code" class="input-box w-full my-2" placeholder="Enter code" style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                    <button id="verify-code" class="bg-[#8C5A3C] text-white px-4 py-2 rounded-lg">Verify Code</button>
                </div>
            </div>

            <div id="new-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <p class="text-lg font-bold">Enter your new password</p>
                    <input type="password" id="reset-new-password" class="input-box w-full my-2" placeholder="New password" style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                    <input type="password" id="reset-confirm-password" class="input-box w-full my-2" placeholder="Confirm new password" style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                    <button id="save-new-password" class="bg-[#8C5A3C] text-white px-4 py-2 rounded-lg">Save New Password</button>
                </div>
            </div>

            <div id="password-saved-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <p class="text-lg font-bold">Password Changes Saved</p>
                    <button id="close-saved-modal" class="bg-[#8C5A3C] text-white px-4 py-2 rounded-lg mt-4 mx-auto block">OK</button>
                </div>
            </div>
            </div>
</div>
</div>
        </section>

        <!-- Transaction History (Hidden by Default) -->
        <section id="transaction-history" class="settings-tab hidden">
            <h2 class="text-3xl font-bold text-[#4E3629] mb-6">Transaction History</h2>
            <div class="bg-[#F5E8D7] p-6 rounded-lg shadow">
                <p class="text-lg font-semibold text-[#4E3629]">No transactions found.</p>
            </div>
        </section>

        <!-- Chat Support (Hidden by Default) -->
        <section id="chat-support" class="settings-tab hidden w-full max-w-7xl mx-auto mb-2">
            <h2 class="text-3xl font-bold text-[#4E3629] mb-6">Chat Support</h2>
            <p class="text-lg text-gray-700">Need help? Chat with our support team for assistance.</p>

            <!-- Chat Window -->
            <div class="bg-white border border-gray-300 rounded-lg shadow-md mt-6 p-4 h-96 flex flex-col">
                <!-- Messages Area -->
                <div id="chat-messages" class="flex-1 overflow-y-auto p-2 space-y-3">
                    <div class="bg-gray-200 p-3 rounded-lg w-fit max-w-xs">Hello! How can we help you?</div>
                </div>

                <!-- Input Field -->
                <div class="mt-4 flex items-center border-t pt-2">
                    <input type="text" id="chat-input" class="flex-1 border rounded-lg p-2 text-gray-800" placeholder="Type your message..."
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                    <button id="send-btn" class="ml-2 bg-[#8C5A3C] text-white px-4 py-2 rounded-lg">Send</button>
                </div>
            </div>
        </section>
    </main>
</div>



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

        function showTab(tabId) {
            document.querySelectorAll('.settings-tab').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
        }

        document.getElementById("send-btn").addEventListener("click", function() {
    let input = document.getElementById("chat-input");
    let message = input.value.trim();
    if (message) {
        let chatMessages = document.getElementById("chat-messages");

        // Create wrapper for user message (aligns message to the right)
        let userMessageWrapper = document.createElement("div");
        userMessageWrapper.className = "flex justify-end";

        let userMessage = document.createElement("div");
        userMessage.className = "bg-[#8C5A3C] text-white p-3 rounded-lg w-fit max-w-xs mt-2";
        userMessage.textContent = message;

        userMessageWrapper.appendChild(userMessage);
        chatMessages.appendChild(userMessageWrapper);

        // Auto-reply from system (aligned left)
        setTimeout(() => {
            let botMessageWrapper = document.createElement("div");
            botMessageWrapper.className = "flex justify-start";

            let botMessage = document.createElement("div");
            botMessage.className = "bg-gray-200 p-3 rounded-lg w-fit max-w-xs mt-2";
            botMessage.textContent = "Thanks for your message! Our team will get back to you shortly.";

            botMessageWrapper.appendChild(botMessage);
            chatMessages.appendChild(botMessageWrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 1000);

        // Clear input field
        input.value = "";
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

document.getElementById("save-password").addEventListener("click", function () {
        let newPassword = document.getElementById("new-password").value;
        let confirmPassword = document.getElementById("confirm-password").value;
        if (newPassword === confirmPassword) {
            document.getElementById("password-modal").classList.remove("hidden");
        } else {
            alert("Passwords do not match!");
        }
    });
    document.getElementById("close-modal").addEventListener("click", function () {
        document.getElementById("password-modal").classList.add("hidden");
    });

    document.getElementById("forgot-password").addEventListener("click", function () {
        document.getElementById("forgot-password-modal").classList.remove("hidden");
    });
    document.getElementById("send-code").addEventListener("click", function () {
        document.getElementById("forgot-password-modal").classList.add("hidden");
        document.getElementById("input-code-modal").classList.remove("hidden");
    });
    document.getElementById("verify-code").addEventListener("click", function () {
        document.getElementById("input-code-modal").classList.add("hidden");
        document.getElementById("new-password-modal").classList.remove("hidden");
    });
    document.getElementById("save-new-password").addEventListener("click", function () {
        let newPass = document.getElementById("reset-new-password").value;
        let confirmPass = document.getElementById("reset-confirm-password").value;
        if (newPass === confirmPass) {
            document.getElementById("new-password-modal").classList.add("hidden");
            document.getElementById("password-saved-modal").classList.remove("hidden");
        } else {
            alert("Passwords do not match!");
        }
    });
    document.getElementById("close-saved-modal").addEventListener("click", function () {
        document.getElementById("password-saved-modal").classList.add("hidden");
    });

    </script>

</body>
</html>
