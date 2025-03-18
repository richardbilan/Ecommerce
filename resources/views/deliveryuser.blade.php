<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Laravel Frontend</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex bg-gray-100">

    <!-- Left Sidebar -->
    <aside class="w-30 bg-[#EADBC8] h-screen text-black flex flex-col justify-between p-4 sticky top-0">
        <!-- Logo -->
        <div>
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-[20px]">
            </div>

            <!-- Navigation Menu -->
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('home') }}"
                            class="flex flex-col items-center p-3 hover:bg-[#745858] hover:text-white rounded group">
                            <img src="{{ asset('images/home.png') }}" alt="Home Icon" class="w-9 h-9 mb-1 group-hover:hidden">
                            <img src="{{ asset('images/home_white.png') }}" alt="Home Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" id="billingBtn"
                            class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded group">
                            <img src="{{ asset('images/billing.png') }}" alt="Billing Icon" class="w-9 h-9 mb-1 group-hover:hidden">
                            <img src="{{ asset('images/billing_white.png') }}" alt="Billing Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
                            <span>Billings</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('deliveryuser') }}"
                            class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded group">
                            <img src="{{ asset('images/delivery.png') }}" alt="Delivery Icon" class="w-9 h-9 mb-1 group-hover:hidden">
                            <img src="{{ asset('images/delivery_white.png') }}" alt="Delivery Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
                            <span>Delivery</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- User Menu -->
        <div class="relative">
            <button id="userMenuBtn"
                class="flex flex-col items-center p-2 w-full hover:bg-[#745858] hover:text-white rounded group">
                <img src="{{ asset('images/user.png') }}" alt="User Icon" class="w-9 h-9 mb-1 group-hover:hidden">
                <img src="{{ asset('images/user_white.png') }}" alt="User Icon Hover" class="w-9 h-9 mb-1 hidden group-hover:block">
                <span>User</span>
            </button>
            <div id="userMenu" class="absolute hidden bg-[#EADBC8] text-black left-0 top-[-7.6rem] mt-2 rounded shadow-md w-32 text-center text-xs">
                <a href="{{ route('account_settings') }}" class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Account Settings</a>
                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-[#745858] hover:text-white rounded">Logout</a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">
        <h1 class="text-2xl font-bold">Your Delivery, User!</h1>

        <div class="mt-4 flex flex-wrap gap-6">
            <!-- Left Section -->
            <div class="flex-1 min-w-[60%]">
                <img src="{{ asset('images/valid-image.jpg') }}" alt="Map" class="w-full h-[500px] rounded-lg object-cover">
                <button class="mt-2 px-4 py-2 border rounded hover:bg-gray-200">View Location</button>

                <!-- Delivery Info -->
                <div class="mt-4 p-4 border rounded-lg shadow-sm bg-white">
                    <h2 class="text-lg font-semibold">Estimated Delivery Time</h2>
                    <p>10 - 15 minutes</p>
                </div>

                <div class="mt-4 p-4 border rounded-lg shadow-sm bg-white">
                    <h2 class="text-lg font-semibold flex items-center">📍 Details of Location</h2>
                    <p>Purok-1 Tagas, Daraga</p>
                </div>

                <div class="mt-4 p-4 border rounded-lg shadow-sm bg-white">
                    <h2 class="text-lg font-semibold">Your Receipt</h2>
                    <p>Order# 12345</p>
                </div>
            </div>

            <!-- Right Section -->
            <div class="w-1/3 min-w-[300px] bg-[#D7B899] p-4 rounded-lg shadow-md">
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

                <!-- Buttons -->
                <button onclick="openModal()" class="mt-4 w-full py-2 border rounded hover:bg-gray-200">Leave a Review</button>
                <a href="#" id="billingBtnn" class="mt-4 w-full py-2 bg-[#6F4E37] text-white rounded hover:bg-[#553C26] text-center block">Order it again</a>
            </div>
        </div>
    </main>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Leave a Review</h2>

            <!-- Rating -->
            <label class="block mb-2">Rating:</label>
            <div id="ratingStars" class="flex space-x-1 mb-4">
                <span data-rating="1" class="cursor-pointer text-gray-400 text-2xl">&#9733;</span>
                <span data-rating="2" class="cursor-pointer text-gray-400 text-2xl">&#9733;</span>
                <span data-rating="3" class="cursor-pointer text-gray-400 text-2xl">&#9733;</span>
                <span data-rating="4" class="cursor-pointer text-gray-400 text-2xl">&#9733;</span>
                <span data-rating="5" class="cursor-pointer text-gray-400 text-2xl">&#9733;</span>
            </div>

            <!-- Comment -->
            <label class="block mb-2">Comment:</label>
            <textarea id="reviewComment" class="w-full p-2 border rounded" placeholder="Write your review here..."></textarea>

            <!-- Buttons -->
            <div class="mt-4 flex justify-end space-x-2">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button onclick="submitReview()" class="px-4 py-2 bg-[#6F4E37] text-white rounded">Submit</button>
            </div>
        </div>
    </div>

    <!-- Right Sidebar (Billing) -->
    <aside id="rightSidebar" class="fixed right-0 top-0 w-80 bg-[#EADBC8] h-full shadow-lg transform translate-x-full transition-transform duration-300 p-4">
        <button id="closeSidebar" class="mb-4">
            <img src="{{ asset('images/close.png') }}" alt="Close Icon" class="w-6 h-6">
        </button>
        <h2 class="text-xl font-bold mb-4">Billings</h2>

        <div id="cartItems" class="space-y-4">
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
            </div>
        </div>
    </aside>

    <!-- Scripts -->
    <script>
        // Sidebar Toggle
        document.getElementById('billingBtn').addEventListener('click', () => document.getElementById('rightSidebar').classList.remove('translate-x-full'));
        document.getElementById('billingBtnn').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('rightSidebar').classList.remove('translate-x-full');
        });
        document.getElementById('closeSidebar').addEventListener('click', () => document.getElementById('rightSidebar').classList.add('translate-x-full'));

        // User Menu Toggle
        document.getElementById('userMenuBtn').addEventListener('click', () => document.getElementById('userMenu').classList.toggle('hidden'));

        // Review Modal
        let selectedRating = 0;

        function openModal() {
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            resetReviewForm();
        }

        function setRating(rating) {
            selectedRating = rating;
            const stars = document.querySelectorAll('#ratingStars span');
            stars.forEach((star, index) => {
                star.classList.toggle('text-yellow-400', index < rating);
                star.classList.toggle('text-gray-400', index >= rating);
            });
        }

        function submitReview() {
            const comment = document.getElementById('reviewComment').value.trim();

            if (!selectedRating) {
                alert('Please select a rating.');
                return;
            }

            if (!comment) {
                alert('Please enter your comment.');
                return;
            }

            alert(`Thank you for your review!\nRating: ${selectedRating} star(s)\nComment: "${comment}"`);
            closeModal();
        }

        function resetReviewForm() {
            selectedRating = 0;
            document.getElementById('reviewComment').value = '';
            document.querySelectorAll('#ratingStars span').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-400');
            });
        }

        document.querySelectorAll('#ratingStars span').forEach(star => {
            star.addEventListener('click', function () {
                setRating(parseInt(this.getAttribute('data-rating')));
            });
        });
    </script>
</body>

</html>
