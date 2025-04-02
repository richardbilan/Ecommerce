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
                        <a href="{{ route('home') }}" class="flex flex-col items-center p-3 hover:bg-[#745858] hover:text-white rounded group">
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

    <!-- Personal Information Section -->
    <section id="personal-info" class="settings-tab w-full max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-[#4E3629] mb-6">Personal Information</h2>
        <div class="flex flex-col md:flex-row gap-8">

            <!-- Profile Picture -->
            <form action="{{ route('update.profile.image') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                @csrf
                <div class="relative w-40 h-40">
                    <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('images/profile-placeholder.png') }}"
                         class="w-full h-full rounded-full border-4 border-[#8C5A3C] shadow-md" id="profilePreview">

                    <!-- Upload Button -->
                    <label for="profile-pic" class="absolute bottom-2 right-2 bg-[#8C5A3C] p-2 rounded-full cursor-pointer shadow">
                        <img src="{{ asset('images/cameraicon.png') }}" class="w-5 h-5">
                    </label>

                    <!-- Hidden File Input -->
                    <input type="file" id="profile-pic" name="profile_image" class="hidden" accept="image/*" onchange="previewAndSubmit(this)">
                </div>
            </form>

            <!-- User Information Form -->
            <form action="{{ route('update.profile') }}" method="POST" class="w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <!-- First Name (Non-editable) -->
                <div>
                    <label for="first-name" class="input-label block mb-1">First Name</label>
                    <p class="block w-full p-2 border-2 border-[#6A4028] rounded bg-gray-100 text-[#4E3629] cursor-default select-none">
                        {{ Auth::user()->name }}
                    </p>
                </div>

                <!-- Last Name -->
                <div>
                    <label class="input-label block">Last Name</label>
                    <input type="text" name="last_name" class="input-box w-full"
                        value="{{ auth()->user()->last_name }}" placeholder="Enter your last name"
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                </div>

                <!-- Birthdate -->
                <div>
                    <label class="input-label block">Birthdate</label>
                    <input type="date" name="birthdate" class="input-box w-full"
                        value="{{ auth()->user()->birthdate }}"
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                </div>

                <!-- Email (Non-editable) -->
                <div>
                    <label class="input-label block">Email</label>
                    <input type="email" class="input-box w-full" value="{{ auth()->user()->email }}" disabled
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="input-label block">Phone Number</label>
                    <input type="tel" name="phone_number" class="input-box w-full"
                        value="{{ auth()->user()->phone_number }}" placeholder="Enter your phone number"
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                </div>

                <!-- Gender -->
                <div>
                    <label class="input-label block">Gender</label>
                    <select name="gender" class="input-box w-full"
                        style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
                        <option disabled>Select your gender</option>
                        <option value="Male" {{ auth()->user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ auth()->user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ auth()->user()->gender == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Save Changes Button -->
                <div class="col-span-1 md:col-span-2 flex justify-end">
                    <button type="submit" class="bg-[#8C5A3C] text-white p-3 rounded mt-4 w-full md:w-auto">
                        Save Changes
                    </button>
                </div>
            </form>

        </div>




<form action="{{ route('update.password') }}" method="POST">
    @csrf
    <div>
        <label class="input-label block mt-3">Current Password</label>
        <input type="password" name="current_password" class="input-box w-full" placeholder="Enter current password"
            style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
    </div>
    <div>
        <label class="input-label block mt-6">New Password</label>
        <input type="password" name="new_password" class="input-box w-full" placeholder="Enter new password"
            style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
    </div>
    <div>
        <label class="input-label block">Confirm New Password</label>
        <input type="password" name="new_password_confirmation" class="input-box w-full" placeholder="Confirm new password"
            style="color: #4E3629; padding: 10px; border-radius: 5px; border: 2px solid #6A4028;">
    </div>
    <button type="submit" class="bg-[#8C5A3C] text-white px-6 py-2 rounded-lg mt-4">Save Password</button>
</form>
</section>

</main>

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


    function previewAndSubmit(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
        input.form.submit();
    }
    </script>

</body>
</html>
