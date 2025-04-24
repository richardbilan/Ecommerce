<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
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
            <div class="grid md:grid-cols-3 gap-8 items-start">
                <!-- Profile Picture Section -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40">
                        <img src="{{ Auth::user()->profile_image ? asset('storage/profile_images/' . Auth::user()->profile_image) : asset('images/profile-placeholder.png') }}"
                             class="w-full h-full object-cover rounded-full border-4 border-[#8C5A3C] shadow-lg" id="profilePreview">

                        <form id="profileImageForm" action="{{ route('update.profile.image') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" 
                                   name="profile_image" 
                                   id="profile-pic" 
                                   class="hidden" 
                                   accept="image/*"
                                   onchange="handleProfileImageUpload(this)">
                            <label for="profile-pic" 
                                   class="absolute bottom-2 right-2 bg-[#8C5A3C] p-2 rounded-full cursor-pointer shadow-lg hover:bg-[#6A4028] transition">
                            <img src="{{ asset('images/cameraicon.png') }}" class="w-5 h-5">
                        </label>
                        </form>
                    </div>
                    <p id="uploadError" class="text-red-500 text-sm mt-2 hidden"></p>
                </div>


                <!-- User Information Form -->
                <div class="relative">
                    <!-- Edit Toggle Button -->
                    <button type="button" 
                            onclick="toggleEditMode()" 
                            class="absolute -top-2 -right-1 bg-[#8C5A3C] p-2 rounded-full cursor-pointer shadow-lg hover:bg-[#6A4028] transition z-10">
                        <img src="{{ asset('images/edit.jpg') }}" alt="Edit" class="w-3 h-3">
                    </button>

                    <form id="userInfoForm" action="{{ route('update.profile') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <!-- First Name (Non-editable) -->
                    <div>
                        <label class="block font-semibold">First Name</label>
                        <input type="text" value="{{ Auth::user()->name }}" disabled
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block font-semibold">Last Name</label>
                        <input type="text" name="last_name" value="{{ auth()->user()->last_name }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input" disabled>
                    </div>

                    <!-- Birthdate -->
                    <div>
                        <label class="block font-semibold">Birthdate</label>
                        <input type="date" name="birthdate" value="{{ auth()->user()->birthdate }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input" disabled>
                    </div>

                    <!-- Email (Non-editable) -->
                    <div>
                        <label class="block font-semibold">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block font-semibold">Phone Number</label>
                        <input type="tel" name="phone_number" value="{{ auth()->user()->phone_number }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input" disabled>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block font-semibold">Gender</label>
                            <select name="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input" disabled>
                            <option disabled>Select your gender</option>
                            <option value="Male" {{ auth()->user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ auth()->user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ auth()->user()->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                        <!-- Location Information -->
                        <div class="col-span-2 mt-4">
                            <label class="block font-semibold">Location</label>
                            <div class="flex gap-4 items-start">
                                <div class="flex-1">
                                    <input type="text" name="location" id="locationInput" value="{{ auth()->user()->location }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input" disabled>
                                </div>
                                <button type="button" onclick="getCurrentLocation()"
                                        class="bg-[#8C5A3C] text-white px-4 py-2 rounded-lg hover:bg-[#6A4028] transition location-btn" disabled>
                                    Get Current Location
                                </button>
                            </div>
                            <p id="locationError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                        <!-- Save Button (Hidden by default) -->
                        <div id="saveButtonContainer" class="col-span-2 flex justify-end hidden">
                        <button type="submit"
                                    class="bg-[#8C5A3C] text-white px-6 py-2 rounded-lg hover:bg-[#6A4028] transition">
                            Save Changes
                        </button>
                    </div>
                </form>
                </div>

            </section>

        </main>


        <div class="w-full max-w-7xl bg-white p-6 rounded-lg shadow-lg mt-6">
            <!-- Left: Spacer for Alignment -->
            <div></div>

            <!-- Right: Password Change Box -->
            <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-[#4E3629] mb-4">Change Password</h2>
                <form action="{{ route('update.password') }}" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" class="w-full mt-1 p-2 border rounded-md focus:ring-[#6A4028] focus:border-[#6A4028]"
                            placeholder="Enter current password">
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" class="w-full mt-1 p-2 border rounded-md focus:ring-[#6A4028] focus:border-[#6A4028]"
                            placeholder="Enter new password">
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full mt-1 p-2 border rounded-md focus:ring-[#6A4028] focus:border-[#6A4028]"
                            placeholder="Confirm new password">
                    </div>
                    <button type="submit" class="w-full bg-[#8C5A3C] text-white py-2 mt-4 rounded-lg hover:bg-[#6A4028]">
                        Save Password
                    </button>
                </form>
            </div>
        </div>


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
        const file = input.files[0];
        const errorElement = document.getElementById('uploadError');
        errorElement.classList.add('hidden');

        // Validate file type
        if (!file.type.startsWith('image/')) {
            errorElement.textContent = 'Please select an image file';
            errorElement.classList.remove('hidden');
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            errorElement.textContent = 'Image size should be less than 2MB';
            errorElement.classList.remove('hidden');
            return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            };
        reader.readAsDataURL(file);

        // Submit form
        document.getElementById('profileImageForm').submit();
    }

    function toggleEditMode() {
        const form = document.getElementById('userInfoForm');
        const inputs = form.querySelectorAll('.form-input');
        const saveButton = document.getElementById('saveButtonContainer');
        const locationBtn = document.querySelector('.location-btn');

        inputs.forEach(input => {
            input.disabled = !input.disabled;
            if (!input.disabled) {
                input.classList.add('bg-white');
                input.classList.remove('bg-gray-100');
            } else {
                input.classList.remove('bg-white');
                input.classList.add('bg-gray-100');
            }
        });

        locationBtn.disabled = !locationBtn.disabled;
        saveButton.classList.toggle('hidden');

        // If we're enabling editing, attach the form submit handler
        if (!inputs[0].disabled) {
            form.removeEventListener('submit', handleFormSubmit); // Remove any existing handler
            form.addEventListener('submit', handleFormSubmit); // Add the handler
        }
    }

    // Update the form submission handler
    function handleFormSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        // Debug logging
        console.log('Form Data being sent:');
        formData.forEach((value, key) => {
            console.log(`${key}: ${value}`);
        });

        // Convert FormData to an object for sending
        const formDataObj = {};
        formData.forEach((value, key) => {
            formDataObj[key] = value;
        });

        console.log('JSON Data being sent:', formDataObj);

        // Make the AJAX request
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formDataObj)
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update form fields with new data
                if (data.data) {
                    Object.keys(data.data).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.value = data.data[key];
                        }
                    });
                }
                
                showNotification('Profile updated successfully', 'success');
                toggleEditMode();
            } else {
                showNotification(data.message || 'Error updating profile', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating profile', 'error');
        });
    }

    // Make sure to initialize the form handler when the document loads
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('userInfoForm');
        if (form) {
            form.removeEventListener('submit', handleFormSubmit); // Remove any existing handler
            form.addEventListener('submit', handleFormSubmit); // Add the handler
        }
    });

    // Show success message if it exists in the session
    @if(session('success'))
        alert("{{ session('success') }}");
    @endif

    // Show error message if it exists in the session
    @if($errors->any())
        alert("{{ $errors->first() }}");
    @endif

    function getCurrentLocation() {
        const locationError = document.getElementById('locationError');
        const locationInput = document.getElementById('locationInput');
        const form = document.getElementById('userInfoForm');

        locationError.classList.add('hidden');
        console.log('Getting current location...'); // Debug log

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    
                    console.log('Got coordinates:', latitude, longitude); // Debug log
                    
                    // Use reverse geocoding to get address
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Got address data:', data); // Debug log
                            const address = data.display_name;
                            locationInput.value = address;
                            
                            // Create FormData and only send the location
                            const formData = new FormData();
                            formData.append('location', address);
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                            console.log('Sending location to server:', address); // Debug log

                            // Use jQuery AJAX which handles FormData better
                            $.ajax({
                                url: "{{ route('update.location') }}",
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    console.log('Server response:', response); // Debug log
                                    if (response.success) {
                                        showNotification('Location updated successfully', 'success');
                                    } else {
                                        showNotification(response.message || 'Error updating location', 'error');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error:', error);
                                    console.error('Response:', xhr.responseText);
                                    showNotification('Error updating location: ' + error, 'error');
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Error getting address:', error); // Debug log
                            locationError.textContent = "Could not get address from coordinates";
                            locationError.classList.remove('hidden');
                        });
                },
                function(error) {
                    console.error('Geolocation error:', error); // Debug log
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

    // Initialize Pusher
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    const channel = pusher.subscribe('user-updates-{{ Auth::id() }}');

    // Listen for profile updates
    channel.bind('profile-updated', function(data) {
        updateFormFields(data);
        showNotification('Profile updated successfully!');
    });

    // Listen for profile image updates
    channel.bind('profile-image-updated', function(data) {
        document.getElementById('profilePreview').src = data.image_url;
        showNotification('Profile image updated successfully!');
    });

    // Handle profile image upload
    function handleProfileImageUpload(input) {
        const file = input.files[0];
        if (!file) return;

        // Show loading state
        const preview = document.getElementById('profilePreview');
        preview.style.opacity = '0.5';

        const formData = new FormData();
        formData.append('profile_image', file);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route('update.profile.image') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                preview.style.opacity = '1';
                if (response.success) {
                    showNotification('Profile image updated successfully!');
                }
            },
            error: function(xhr) {
                preview.style.opacity = '1';
                showNotification('Error updating profile image', 'error');
            }
        });
    }

    function updateFormFields(data) {
        Object.keys(data).forEach(key => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = data[key];
            }
        });
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white z-50 transition-opacity duration-500`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }
    </script>

    <style>
    .form-input:disabled {
        background-color: #f3f4f6;
    }
    .form-input:not(:disabled) {
        background-color: white;
    }
    .notification {
        transition: opacity 0.5s ease-in-out;
    }
    </style>

</body>
</html>
