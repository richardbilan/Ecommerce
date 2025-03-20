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
            <a href="{{ route('deliveryuser') }}" class="flex flex-col items-center p-2 hover:bg-[#745858] hover:text-white rounded group">
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
  <!-- Main Content -->
<main class="flex-1 p-6">
    <h1 class="text-2xl font-bold">Your Delivery, {{ Auth::user()->name }}</h1>

    <!-- Flex container for Map and Track Order -->
    <div class="mt-4 flex flex-col lg:flex-row gap-6">
      <!-- Map Section -->
      <div class="w-full lg:w-3/4">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.393857019527!2d106.6645315748183!3d10.77978385905508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ee0b13dd1f5%3A0xa7a2e93e93a1732!2zMTEzIMSQLiBUcuG7jW5nIEjGsG5nIFF1w70sIFBoxrDhu51uZyA1LCBRdeG6o25nIDMsIFRow6BuaCBwaOG7kSBIw6AgQ2jDrSBNaW5o!5e0!3m2!1sen!2s!4v1710916477821!5m2!1sen!2s"
          width="100%"
          height="500"
          style="border:0; border-radius: 0.5rem;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>

        <button class="mt-2 px-4 py-2 border rounded hover:bg-gray-200" onclick="window.open('https://maps.app.goo.gl/NxvjoxKkvBpwCngK6', '_blank')">
          View Location
        </button>

        <!-- Delivery Time, Location Details, and Receipt -->
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

      <!-- Track Order Section -->
      <div class="w-full lg:w-1/3 bg-[#D7B899] p-4 rounded-lg shadow-md h-fit">
        <h2 class="text-lg font-semibold">Track Order</h2>
        <div class="mt-2 space-y-4 border-l-2 border-gray-600 pl-4">
          <div class="p-3 bg-white border rounded-lg shadow-sm">
            <h3 class="font-bold">10:00 - 10:10</h3>
            <p class="text-sm">Thanks for your order. We have received it and are on it!</p>
          </div>
          <div class="p-3 bg-white border rounded-lg shadow-sm">
            <h3 class="font-bold">10:10 - 10:15</h3>
            <p class="text-sm">Preparing your order. We are on it!</p>
          </div>
          <div class="p-3 bg-white border rounded-lg shadow-sm">
            <h3 class="font-bold">10:15 - 10:20</h3>
            <p class="text-sm">Delivering your order. Let's go!</p>
          </div>
          <div class="p-3 bg-white border rounded-lg shadow-sm">
            <h3 class="font-bold">10:20 - 10:25</h3>
            <p class="text-sm">Delivered. Enjoy your coffee!</p>
          </div>
        </div>

        <button onclick="openModal()" class="mt-2 w-full py-2 border rounded hover:bg-gray-200">Leave a Review</button>
        <a href="home" id="billingBtnn" class="mt-4 w-full py-2 bg-[#6F4E37] text-white rounded hover:bg-[#553C26] flex justify-center">Order it again</a>
      </div>
    </div>
  </main>

  <!-- Review Modal -->
  <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
      <h2 class="text-lg font-semibold mb-4">Leave a Review</h2>
      <label class="block mb-2">Rating:</label>
      <div class="flex space-x-1 mb-4" id="starsContainer">
        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(1)">&#9733;</span>
        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(2)">&#9733;</span>
        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(3)">&#9733;</span>
        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(4)">&#9733;</span>
        <span class="cursor-pointer text-gray-400 text-2xl" onclick="setRating(5)">&#9733;</span>
      </div>

      <label class="block mb-2">Comment:</label>
      <textarea id="reviewComment" class="w-full p-2 border rounded" placeholder="Write your review here..."></textarea>

      <div class="mt-4 flex justify-end space-x-2">
        <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
        <button onclick="submitReview()" class="px-4 py-2 bg-[#6F4E37] text-white rounded">Submit</button>
      </div>
    </div>
  </div>



  <!-- Scripts -->
  <script>
    const billingBtn = document.getElementById('billingBtn');
    const billingBtnn = document.getElementById('billingBtnn');
    const closeSidebar = document.getElementById('closeSidebar');
    const rightSidebar = document.getElementById('rightSidebar');
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');
    const favoriteBtn = document.getElementById('favoriteBtn');
    const heartIcon = document.getElementById('heartIcon');
    const orderMode = document.getElementById('orderMode');
    const locationBtn = document.getElementById('locationBtn');
    const increaseBtn = document.getElementById('increaseBtn');
    const decreaseBtn = document.getElementById('decreaseBtn');
    const quantityDisplay = document.getElementById('quantity');
    const stars = document.querySelectorAll('#starsContainer span');

    let quantity = 1;
    let currentRating = 0;

    billingBtn.addEventListener('click', () => rightSidebar.classList.remove('translate-x-full'));
    billingBtnn.addEventListener('click', () => rightSidebar.classList.remove('translate-x-full'));
    closeSidebar.addEventListener('click', () => rightSidebar.classList.add('translate-x-full'));
    userMenuBtn.addEventListener('click', () => userMenu.classList.toggle('hidden'));

    favoriteBtn.addEventListener('click', () => {
      heartIcon.classList.toggle('text-red-500');
      heartIcon.classList.toggle('text-gray-500');
    });

    orderMode.addEventListener('change', () => {
      locationBtn.textContent = orderMode.value === "pickup" ? "Current Location" : "Enter Your Location";
    });

    increaseBtn.addEventListener('click', () => {
      quantity++;
      quantityDisplay.textContent = quantity;
    });

    decreaseBtn.addEventListener('click', () => {
      if (quantity > 1) {
        quantity--;
        quantityDisplay.textContent = quantity;
      }
    });

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
        alert(`thank you for your ${selectedRating} star rating and review! MWAAA!!`);
        closeModal();
    }
        function redirectToSidebar() {
        const sidebar = document.getElementById('sidebar'); // Assuming sidebar has this ID
        if (sidebar) {
            sidebar.scrollIntoView({ behavior: 'smooth' });
        }
    }
  </script>

</body>

</html>
