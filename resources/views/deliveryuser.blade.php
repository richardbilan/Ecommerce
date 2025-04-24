<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beyouu Brew Cafe</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Add Pusher.js -->
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <!-- Add Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- Add Leaflet Routing Machine CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
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
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="block w-full px-4 py-2 text-left hover:bg-[#745858] hover:text-white rounded">Logout</button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <!-- Main Content -->
<main class="flex-1 p-6">
    <h1 class="text-2xl font-bold">
        Your Delivery, {{ Auth::check() ? Auth::user()->name : 'Guest' }}
    </h1>

    <!-- Main Content Container -->
    <div class="container mx-auto px-4">
      <!-- Map and Active Orders Row -->
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Column - Map and Route -->
        <div class="flex-grow">
          <!-- Map -->
          <div class="w-full">
        <div id="map" class="h-[500px] w-full rounded-lg shadow-md"></div>
          </div>
        </div>

        <!-- Right Column - Active Orders -->
        <div class="lg:w-96 bg-[#D7B899] p-4 rounded-lg shadow-md h-fit">
          <h2 class="text-lg font-semibold mb-4">Active Orders</h2>

          <!-- Selected Order Tracking -->
          <div id="trackOrderContainer" class="mt-2 space-y-4 border-l-2 border-gray-600 pl-4 {{ $statistics['active_order'] || $orders->where('status', 'delivered')->first() ? '' : 'hidden' }}">
            <!-- Order Placed -->
            <div class="p-3 bg-white border rounded-lg shadow-sm">
              <div class="flex justify-between items-center">
                <h3 class="font-bold order-placed-time">
                    @if($statistics['active_order'])
                        {{ $statistics['active_order']->created_at->format('h:i A') }}
                    @else
                        {{ $orders->where('status', 'delivered')->first()?->created_at->format('h:i A') ?? '-' }}
                    @endif
                </h3>
                <span class="order-status-badge px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                    {{ $statistics['active_order'] ? ($statistics['active_order']->status == 'pending' ? 'Pending' : 'Completed') : 'Completed' }}
                </span>
              </div>
              <p class="text-sm">Order placed and waiting for confirmation</p>
            </div>

            <!-- Order Confirmed -->
            <div class="p-3 bg-white border rounded-lg shadow-sm">
              <div class="flex justify-between items-center">
                <h3 class="font-bold order-confirmed-time">
                    @if($statistics['active_order'])
                        @if($statistics['active_order']->status != 'pending')
                            {{ $statistics['active_order']->confirmed_at ? \Carbon\Carbon::parse($statistics['active_order']->confirmed_at)->format('h:i A') : '-' }}
                        @else
                            -
                        @endif
                    @else
                        {{ $orders->where('status', 'delivered')->first()?->confirmed_at ? \Carbon\Carbon::parse($orders->where('status', 'delivered')->first()->confirmed_at)->format('h:i A') : '-' }}
                    @endif
                </h3>
                <span class="confirmed-status-badge px-2 py-1 text-xs rounded-full {{ $statistics['active_order'] ? ($statistics['active_order']->status == 'pending' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800') : 'bg-green-100 text-green-800' }}">
                    {{ $statistics['active_order'] ? ($statistics['active_order']->status == 'pending' ? 'Waiting' : 'Completed') : 'Completed' }}
                </span>
              </div>
              <p class="text-sm">Order confirmed by Cafe</p>
            </div>

            <!-- Preparation -->
            <div class="p-3 bg-white border rounded-lg shadow-sm">
              <div class="flex justify-between items-center">
                <h3 class="font-bold preparation-time">
                    @if($statistics['active_order'])
                        @if(in_array($statistics['active_order']->status, ['delivering', 'delivered']))
                            {{ $statistics['active_order']->preparation_completed_at ? \Carbon\Carbon::parse($statistics['active_order']->preparation_completed_at)->format('h:i A') : '-' }}
                        @else
                            -
                        @endif
                    @else
                        {{ $orders->where('status', 'delivered')->first()?->preparation_completed_at ? \Carbon\Carbon::parse($orders->where('status', 'delivered')->first()->preparation_completed_at)->format('h:i A') : '-' }}
                    @endif
                </h3>
                <span class="preparation-status-badge px-2 py-1 text-xs rounded-full {{ $statistics['active_order'] ? (in_array($statistics['active_order']->status, ['delivering', 'delivered']) ? 'bg-green-100 text-green-800' : ($statistics['active_order']->status == 'confirmed' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) : 'bg-green-100 text-green-800' }}">
                    {{ $statistics['active_order'] ? (in_array($statistics['active_order']->status, ['delivering', 'delivered']) ? 'Completed' : ($statistics['active_order']->status == 'confirmed' ? 'In Progress' : 'Waiting')) : 'Completed' }}
                </span>
              </div>
              <p class="text-sm">Preparing your order</p>
            </div>

            <!-- Out for Delivery -->
            <div class="p-3 bg-white border rounded-lg shadow-sm">
              <div class="flex justify-between items-center">
                <h3 class="font-bold delivery-time">
                    @if($statistics['active_order'])
                        @if($statistics['active_order']->status == 'delivered')
                            {{ $statistics['active_order']->delivered_at ? \Carbon\Carbon::parse($statistics['active_order']->delivered_at)->format('h:i A') : '-' }}
                        @else
                            -
                        @endif
                    @else
                        {{ $orders->where('status', 'delivered')->first()?->delivered_at ? \Carbon\Carbon::parse($orders->where('status', 'delivered')->first()->delivered_at)->format('h:i A') : '-' }}
                    @endif
                </h3>
                <span class="delivery-status-badge px-2 py-1 text-xs rounded-full {{ $statistics['active_order'] ? ($statistics['active_order']->status == 'delivered' ? 'bg-green-100 text-green-800' : ($statistics['active_order']->status == 'delivering' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) : 'bg-green-100 text-green-800' }}">
                    {{ $statistics['active_order'] ? ($statistics['active_order']->status == 'delivered' ? 'Completed' : ($statistics['active_order']->status == 'delivering' ? 'In Progress' : 'Waiting')) : 'Completed' }}
                </span>
              </div>
              <p class="text-sm">Out for delivery</p>
            </div>
          </div>

          <!-- Overall Progress -->
          <div class="mt-4 p-3 bg-white rounded-lg">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Order Progress</span>
              <span class="text-sm text-gray-600 progress-percentage">
                @php
                    if ($statistics['active_order']) {
                        $progress = 0;
                        $status = $statistics['active_order']->status;

                        // Order placed - 25%
                        $progress = 25;
                        
                        // Order confirmed - 50%
                        if ($status !== 'pending') {
                            $progress = 50;
                        }
                        
                        // Preparation and delivery - 75%
                        if (in_array($status, ['preparing', 'delivering'])) {
                            $progress = 75;
                        }
                        
                        // Only completed delivery - 100%
                        if ($status === 'delivered') {
                            $progress = 100;
                        }
                        
                        echo $progress . '%';
                    } else {
                        echo '100%';
                    }
                @endphp
              </span>
            </div>
            <div class="mt-2 h-2 bg-gray-200 rounded-full">
              <div class="progress-bar h-full bg-[#6F4E37] rounded-full transition-all duration-500" style="width: {{ $statistics['active_order'] ? $progress : 100 }}%"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Delivery Route Info -->
      <div class="mt-6">
        <div class="p-3 bg-white rounded-lg">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Delivery Route</h2>
            <div class="flex items-center space-x-2">
              <span class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-1"></span>
                <span class="text-sm">Your Location</span>
              </span>
              <span class="flex items-center">
                <span class="w-3 h-3 bg-red-500 rounded-full mr-1"></span>
                <span class="text-sm">Shop Location</span>
              </span>
            </div>
        </div>

          <div class="space-y-2 mt-4">
            <div class="flex items-start space-x-2">
              <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5"></div>
              <div>
                <p class="font-medium">Your Location</p>
                <p class="text-sm text-gray-600" id="userAddress">Fetching your location...</p>
              </div>
            </div>
            <div class="flex items-start space-x-2">
              <div class="w-3 h-3 bg-red-500 rounded-full mt-1.5"></div>
              <div>
                <p class="font-medium">Shop Location</p>
                <p class="text-sm text-gray-600">Beyouu Brew Cafe, Purok-1 Tagas, Daraga</p>
              </div>
            </div>
        </div>

          <div class="mt-4 pt-4 border-t">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-600">Estimated Distance</p>
                <p class="font-medium" id="distance">Calculating...</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Estimated Time</p>
                <p class="font-medium" id="duration">Calculating...</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders List -->
      <div class="mt-6">
        <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Your Orders</h2>
            <div class="flex space-x-4 mb-6">
                <button id="activeOrdersBtn" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-medium">
                    Active Orders ({{ $orders->whereIn('status', ['pending', 'confirmed', 'delivering'])->count() }})
                </button>
                <button id="orderHistoryBtn" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-medium">
                    Order History ({{ $orders->where('status', 'delivered')->count() }})
                </button>
            </div>
          </div>

          <div class="space-y-4">
            @forelse($orders as $order)
                <div class="order-card bg-white rounded-lg shadow-md p-4 cursor-pointer hover:shadow-lg transition-shadow"
                     data-order-id="{{ $order->id }}"
                     data-status="{{ $order->status }}">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-lg">Order #{{ $order->id }}</h3>
                            <p class="text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">₱{{ number_format($order->total_amount, 2) }}</p>
                            <span class="px-2 py-1 rounded-full text-sm
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status == 'delivering') bg-purple-100 text-purple-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500">No orders found</p>
                </div>
            @endforelse
          </div>
          </div>
        </div>
      </div>
    <br>
  </main>

  <!-- Review Modal -->
  <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Leave a Review</h2>
        <button onclick="closeReviewModal()" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form id="reviewForm">
        <input type="hidden" id="currentOrderId" name="orderId">
      <label class="block mb-2">Rating:</label>
      <div class="flex space-x-1 mb-4" id="starsContainer">
          <i class="fas fa-star text-3xl cursor-pointer text-gray-300" data-rating="1"></i>
          <i class="fas fa-star text-3xl cursor-pointer text-gray-300" data-rating="2"></i>
          <i class="fas fa-star text-3xl cursor-pointer text-gray-300" data-rating="3"></i>
          <i class="fas fa-star text-3xl cursor-pointer text-gray-300" data-rating="4"></i>
          <i class="fas fa-star text-3xl cursor-pointer text-gray-300" data-rating="5"></i>
      </div>

      <label class="block mb-2">Comment:</label>
        <textarea id="reviewComment" name="comment" class="w-full p-2 border rounded" rows="4" placeholder="Write your review here..." required></textarea>

      <div class="mt-4 flex justify-end space-x-2">
          <button type="button" onclick="closeReviewModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
          <button type="submit" id="submitReviewBtn" class="px-4 py-2 bg-[#6F4E37] text-white rounded hover:bg-[#5a3d2c]">Submit</button>
      </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <!-- Add Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <!-- Add Leaflet Routing Machine JS -->
  <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');

        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener('click', function(event) {
                event.preventDefault();
                userMenu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuBtn.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }

        // Other existing event listeners...
    const billingBtn = document.getElementById('billingBtn');
    const billingBtnn = document.getElementById('billingBtnn');
    const closeSidebar = document.getElementById('closeSidebar');
    const rightSidebar = document.getElementById('rightSidebar');
    const favoriteBtn = document.getElementById('favoriteBtn');
    const heartIcon = document.getElementById('heartIcon');
    const orderMode = document.getElementById('orderMode');
    const locationBtn = document.getElementById('locationBtn');
    const increaseBtn = document.getElementById('increaseBtn');
    const decreaseBtn = document.getElementById('decreaseBtn');
    const quantityDisplay = document.getElementById('quantity');
    const stars = document.querySelectorAll('#starsContainer span');

    let quantity = 1;
        let selectedRating = 0;

        if (billingBtn && rightSidebar) {
    billingBtn.addEventListener('click', () => rightSidebar.classList.remove('translate-x-full'));
        }
        if (billingBtnn && rightSidebar) {
    billingBtnn.addEventListener('click', () => rightSidebar.classList.remove('translate-x-full'));
        }
        if (closeSidebar && rightSidebar) {
    closeSidebar.addEventListener('click', () => rightSidebar.classList.add('translate-x-full'));
        }

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

        function setRating(rating) {
            selectedRating = rating;
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#FFD700';
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-gray-400');
                } else {
                    star.style.color = '#ccc';
                    star.classList.add('text-gray-400');
                    star.classList.remove('text-yellow-400');
                }
            });
        }

        function openReviewModal(orderId) {
            const modal = document.getElementById('reviewModal');
            modal.classList.remove('hidden');
            document.getElementById('currentOrderId').value = orderId;
            
            // Reset form
            selectedRating = 0;
            resetStars();
            document.getElementById('reviewForm').reset();
            const submitBtn = document.getElementById('submitReviewBtn');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit';
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.classList.add('hidden');
            resetStars();
        }

        function resetStars() {
            const stars = document.querySelectorAll('#starsContainer i');
            stars.forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
        }

        function submitReview(event) {
            event.preventDefault();
            
            const orderId = document.getElementById('currentOrderId').value;
        const comment = document.getElementById('reviewComment').value;
            const submitBtn = document.getElementById('submitReviewBtn');

        if (selectedRating === 0) {
            alert('Please select a rating');
            return;
        }

        if (!comment.trim()) {
            alert('Please write a comment');
            return;
            }

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            fetch(`/orders/${orderId}/review`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    comment: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        title: 'Thank you!',
                        text: 'Your review has been submitted successfully.',
                        icon: 'success',
                        confirmButtonColor: '#6F4E37'
                    });
                    closeReviewModal();
                    
                    // Update the review button
                    const reviewButton = document.querySelector(`button[onclick="openReviewModal(${orderId})"]`);
                    if (reviewButton) {
                        reviewButton.textContent = 'Review Submitted';
                        reviewButton.disabled = true;
                        reviewButton.classList.remove('bg-blue-100', 'text-blue-800', 'hover:bg-blue-200');
                        reviewButton.classList.add('bg-green-100', 'text-green-800');
                    }
                } else {
                    throw new Error(data.message || 'Failed to submit review');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Failed to submit review. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#6F4E37'
                });
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
            });
        }

        // Initialize star rating when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.getElementById('starsContainer');
            if (starsContainer) {
                const stars = starsContainer.querySelectorAll('i');
                
                stars.forEach((star, index) => {
                    // Click handler
                    star.addEventListener('click', () => setRating(index + 1));
                    
                    // Hover effects
                    star.addEventListener('mouseenter', () => {
                        stars.forEach((s, i) => {
                            if (i <= index) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            }
                        });
                    });
                    
                    star.addEventListener('mouseleave', () => {
                        stars.forEach((s, i) => {
                            if (i < selectedRating) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    });
                });
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                const modal = document.getElementById('reviewModal');
                if (event.target === modal) {
                    closeReviewModal();
                }
            });
        });
    });

  </script>

  <!-- Add this at the bottom of your file, before the closing body tag -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps.api_key') }}&libraries=places"></script>
  <script>
    let map;
    let routingControl;
    
    // Shop coordinates (Beyouu Brew Cafe location)
    const shopLocation = [13.1568, 123.7035]; // Latitude, Longitude

    function initMap() {
        // Initialize the map
        map = L.map('map').setView(shopLocation, 14);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add shop marker
        const shopMarker = L.marker(shopLocation, {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            })
        }).addTo(map);
        shopMarker.bindPopup('Beyouu Brew Cafe').openPopup();

        // Get user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const userLocation = [position.coords.latitude, position.coords.longitude];

                    // Add user marker
                    const userMarker = L.marker(userLocation, {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34]
                        })
                    }).addTo(map);
                    userMarker.bindPopup('Your Location');

                    // Fit map to show both markers
                    const bounds = L.latLngBounds([userLocation, shopLocation]);
                    map.fitBounds(bounds, { padding: [50, 50] });

                    // Get address using Nominatim
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('userAddress').textContent = data.display_name;
                        })
                        .catch(() => {
                            document.getElementById('userAddress').textContent = 'Address lookup failed';
                        });

                    // Calculate and display route
                    calculateRoute(userLocation, shopLocation);
                },
                (error) => {
                    console.error('Error getting location:', error);
                    document.getElementById('userAddress').textContent = 'Unable to get your location';
                }
            );
        } else {
            document.getElementById('userAddress').textContent = 'Geolocation is not supported by your browser';
        }
    }

    // Initialize map when page loads
    window.onload = initMap;
  </script>

  <!-- Update the Pusher initialization script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Pusher with logging enabled for debugging
      Pusher.logToConsole = true;
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
          headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
          }
        }
      });

      // Subscribe to the user's private channel
      const channel = pusher.subscribe('user.{{ Auth::id() }}');

      // Debug connection status
      pusher.connection.bind('connected', () => {
        console.log('Successfully connected to Pusher');
      });

      pusher.connection.bind('error', error => {
        console.error('Failed to connect to Pusher:', error);
      });

      // Listen for order updates
      channel.bind('order.updated', function(data) {
        console.log('Received order update:', data);
        
        if (!data.order) {
          console.error('No order data received');
          return;
        }

        const order = data.order;
        
        // Update order card
        updateOrderCard(order);
        
        // Update tracking if this is the active order
        if (selectedOrderId === order.id) {
          updateOrderTracking(order);
        }

        // Show notification with status-specific messages
        let message = '';
        switch(order.status) {
                case 'confirmed':
            message = `Order #${order.id} has been confirmed by the admin`;
                    break;
                case 'preparing':
            message = `Order #${order.id} is now being prepared`;
                    break;
                case 'delivering':
            message = `Order #${order.id} is out for delivery`;
                    break;
                case 'delivered':
            message = `Order #${order.id} has been delivered`;
                    break;
          default:
            message = `Order #${order.id} status updated to ${order.status}`;
        }
        showNotification(message, 'success');
      });

      // Function to update order card
      function updateOrderCard(order) {
        const orderCard = document.querySelector(`[data-order-id="${order.id}"]`);
        if (!orderCard) {
          console.log(`Order card not found for order ${order.id}`);
          return;
        }

        // Update status badge
        const statusBadge = orderCard.querySelector('span[class*="rounded-full"]');
        if (statusBadge) {
          const statusColors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-blue-100 text-blue-800',
            'preparing': 'bg-blue-100 text-blue-800',
            'delivering': 'bg-purple-100 text-purple-800',
            'delivered': 'bg-green-100 text-green-800'
          };
          
          const baseClasses = 'px-3 py-1 rounded-full text-sm font-medium';
          statusBadge.className = `${baseClasses} ${statusColors[order.status] || ''}`;
          statusBadge.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);
        }

        // Update order visibility based on current filter
        const currentFilter = document.querySelector('.order-filter.active').dataset.filter;
        if (currentFilter === 'active') {
          orderCard.classList.toggle('hidden', order.status === 'delivered');
        } else if (currentFilter === 'completed') {
          orderCard.classList.toggle('hidden', order.status !== 'delivered');
        }
      }

      // Function to update order tracking
      function updateOrderTracking(order) {
        // Update timestamps
        if (document.querySelector('.order-placed-time')) {
          document.querySelector('.order-placed-time').textContent = formatTime(order.created_at);
        }
        if (document.querySelector('.order-confirmed-time')) {
          document.querySelector('.order-confirmed-time').textContent = order.confirmed_at ? formatTime(order.confirmed_at) : '-';
        }
        if (document.querySelector('.preparation-time')) {
          document.querySelector('.preparation-time').textContent = order.preparation_started_at ? formatTime(order.preparation_started_at) : '-';
        }
        if (document.querySelector('.delivery-time')) {
          document.querySelector('.delivery-time').textContent = order.delivery_started_at ? formatTime(order.delivery_started_at) : '-';
        }

        // Update status badges
        updateStatusBadges(order.status);

        // Calculate and update progress
        const progress = calculateProgress(order);
        if (document.querySelector('.progress-percentage')) {
          document.querySelector('.progress-percentage').textContent = `${progress}%`;
        }
        if (document.querySelector('.progress-bar')) {
          document.querySelector('.progress-bar').style.width = `${progress}%`;
        }
      }

      // Function to calculate progress
      function calculateProgress(order) {
        let progress = 0;
        
        // Order placed - 25%
        if (order.status) {
            progress = 25;
        }
        
        // Order confirmed - 50%
        if (order.status === 'confirmed' || order.status === 'preparing' || order.status === 'delivering' || order.status === 'delivered') {
            progress = 50;
        }
        
        // Preparing - 75%
        if (order.status === 'preparing' || order.status === 'delivering') {
            progress = 75;
        }
        
        // Delivered (completed) - 100%
        if (order.status === 'delivered') {
            progress = 100;
        }
        
        return progress;
      }

      // Function to format time
      function formatTime(timestamp) {
        if (!timestamp) return '-';
        const date = new Date(timestamp);
        // Ensure we're working with local time
        return date.toLocaleTimeString('en-US', {
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          hour12: true
        });
      }

      // Function to update status badges
      function updateStatusBadges(status) {
        const badges = {
          'order-status-badge': true, // Always completed as it's placed
          'confirmed-status-badge': status !== 'pending',
          'preparation-status-badge': ['preparing', 'delivering', 'delivered'].includes(status),
          'delivery-status-badge': ['delivering', 'delivered'].includes(status)
        };

        Object.entries(badges).forEach(([className, isCompleted]) => {
          const badge = document.querySelector(`.${className}`);
          if (badge) {
            const baseClasses = 'px-2 py-1 text-xs rounded-full';
            
            // Special handling for status indicators
            let statusText = 'Waiting';
            let statusClass = 'bg-gray-100 text-gray-800';
            
            if (isCompleted) {
                if (status === 'preparing' && className === 'preparation-status-badge') {
                    statusText = 'In Progress';
                    statusClass = 'bg-yellow-100 text-yellow-800';
                } else if (status === 'delivering' && className === 'delivery-status-badge') {
                    statusText = 'In Progress';
                    statusClass = 'bg-yellow-100 text-yellow-800';
                } else {
                    statusText = 'Completed';
                    statusClass = 'bg-green-100 text-green-800';
                }
            }
            
            badge.className = `${className} ${baseClasses} ${statusClass}`;
            badge.textContent = statusText;
          }
        });
      }

      // Function to show notification
      function showNotification(message, type = 'success') {
        const notificationsContainer = document.getElementById('notifications');
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // Add icon based on type
        const icon = document.createElement('span');
        icon.className = 'notification-icon';
        icon.innerHTML = type === 'success' ? '✓' : '!';
        
        const text = document.createElement('span');
        text.textContent = message;
        
        notification.appendChild(icon);
        notification.appendChild(text);
        
        notificationsContainer.appendChild(notification);
        
        // Remove notification after animation
        setTimeout(() => {
          notification.addEventListener('animationend', () => {
            notification.remove();
          });
        }, 3000);
      }
    });
  </script>

  <!-- Add toast container to layout -->
  <div id="toast-container" class="position-fixed bottom-0 right-0 p-3" style="z-index: 5000;">
  </div>

  <!-- Add necessary styles -->
  <style>
    .toast {
        min-width: 250px;
    }
    .toast-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .toast-error {
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .toast-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    .status-badge {
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .order-filter.active {
        background-color: #6F4E37;
        color: white;
    }
    #starsContainer span {
        transition: color 0.2s;
    }
    #starsContainer span:hover {
        transform: scale(1.2);
    }
  </style>

  <!-- Add this before the closing body tag -->
  <script>
    let selectedOrderId = null;
    const orders = @json($orders);

    function selectOrder(orderId) {
      selectedOrderId = orderId;
      const order = orders.find(o => o.id === orderId);

      // Show tracking container
      document.getElementById('trackOrderContainer').classList.remove('hidden');

      // Update order status and times
      updateOrderStatus(order);

      // Highlight selected order card
      document.querySelectorAll('.order-card').forEach(card => {
        card.classList.toggle('ring-2', card.dataset.orderId == orderId);
        card.classList.toggle('ring-[#6F4E37]', card.dataset.orderId == orderId);
      });

      // Update map route if the order has a delivery location
      if (order.location) {
        // Update map route logic here
      }
    }

    function updateOrderStatus(order) {
      // Update order placed time
      const orderPlacedTime = document.querySelector('.order-placed-time');
      if (orderPlacedTime) {
          orderPlacedTime.textContent = formatTime(order.created_at);
      }

      // Update confirmed time
      const confirmedTime = document.querySelector('.order-confirmed-time');
      if (confirmedTime && order.confirmed_at) {
          confirmedTime.textContent = formatTime(order.confirmed_at);
      }

      // Update preparation time
      const preparationTime = document.querySelector('.preparation-time');
      if (preparationTime && order.preparation_started_at) {
          preparationTime.textContent = formatTime(order.preparation_started_at);
      }

      // Update delivery time
      const deliveryTime = document.querySelector('.delivery-time');
      if (deliveryTime && order.delivery_started_at) {
          deliveryTime.textContent = formatTime(order.delivery_started_at);
      }

      // Update progress bar
      const progress = calculateProgress(order);
      const progressBar = document.querySelector('.progress-bar');
      const progressText = document.querySelector('.progress-percentage');
      
      if (progressBar) {
          progressBar.style.width = `${progress}%`;
      }
      if (progressText) {
          progressText.textContent = `${progress}%`;
      }

      // Update status badges
      updateStatusBadges(order.status);
    }

    // Select first order by default if exists
    if (orders.length > 0) {
      selectOrder(orders[0].id);
    }

    // Order filtering
    const filterButtons = document.querySelectorAll('.order-filter');
    const ordersContainer = document.getElementById('ordersContainer');
    const noOrdersMessage = document.getElementById('noOrdersMessage');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state of buttons
            filterButtons.forEach(btn => btn.classList.remove('active', 'bg-[#6F4E37]', 'text-white'));
            this.classList.add('active', 'bg-[#6F4E37]', 'text-white');

            const filterType = this.dataset.filter;
            const orders = document.querySelectorAll('.order-card');
            let visibleOrders = 0;

            orders.forEach(order => {
                const status = order.dataset.status;
                if (filterType === 'active') {
                    if (['pending', 'confirmed', 'delivering'].includes(status)) {
                        order.classList.remove('hidden');
                        visibleOrders++;
                    } else {
                        order.classList.add('hidden');
                    }
                } else if (filterType === 'completed') {
                    if (status === 'delivered') {
                        order.classList.remove('hidden');
                        visibleOrders++;
                    } else {
                        order.classList.add('hidden');
                    }
                }
            });

            // Show/hide no orders message
            if (visibleOrders === 0) {
                noOrdersMessage.textContent = `No ${filterType} orders found`;
                noOrdersMessage.classList.remove('hidden');
            } else {
                noOrdersMessage.classList.add('hidden');
            }
        });
    });

    // Initialize with active orders shown
    document.querySelector('.order-filter[data-filter="active"]').click();

    function orderAgain(orderId) {
        // Get the order details
        const order = @json($orders->keyBy('id')->toArray())[orderId];
        if (order) {
            // Store the order items in localStorage
            localStorage.setItem('lastOrder', JSON.stringify(order.items));
            // Redirect to home page
            window.location.href = "{{ route('home') }}";
        }
    }
  </script>

  <!-- Add SweetAlert2 for better alerts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Add these styles -->
  <style>
    .swal2-popup {
        font-family: 'Arial', sans-serif;
    }
    .swal2-title {
        color: #6F4E37;
    }
    .swal2-confirm {
        background-color: #6F4E37 !important;
    }
    .disabled-review-btn {
        opacity: 0.7;
        cursor: not-allowed;
    }
  </style>

  <!-- Add Font Awesome for better star icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Billing Modal -->
  <div id="billingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9999]" onclick="handleModalClick(event)">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-2xl rounded-lg shadow-xl">
      <!-- Header -->
      <div class="relative border-b">
        <h2 class="text-xl font-semibold text-gray-800 p-4">Billing Summary</h2>
        <button onclick="closeBillingModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 focus:outline-none p-1">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Content -->
      <div class="overflow-y-auto" style="max-height: calc(100vh - 200px);">
        <!-- Orders Summary -->
        <div class="p-4 space-y-4">
          @foreach($orders as $order)
          <div class="bg-gray-50 rounded-lg p-4 {{ $order->status === 'delivered' ? 'border-green-500' : 'border-blue-500' }} border-l-4">
            <div class="flex justify-between items-start mb-2">
              <div>
                <h3 class="font-semibold">Order #{{ sprintf('%06d', $order->id) }}</h3>
                <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</p>
              </div>
              <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                @elseif($order->status == 'delivering') bg-purple-100 text-purple-800
                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                @endif">
                {{ ucfirst($order->status) }}
              </span>
            </div>

            <div class="mt-2">
              <div class="text-sm text-gray-600">
                <p><span class="font-medium">Delivery Address:</span> {{ $order->location }}</p>
                <p><span class="font-medium">Payment Method:</span> {{ ucfirst($order->payment_method) }}</p>
              </div>

              <div class="mt-2">
                <p class="font-medium text-sm">Items:</p>
                <ul class="list-disc list-inside text-sm text-gray-600 ml-2">
                  @php
                    $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                  @endphp
                  @foreach($items as $item)
                    <li>
                      {{ is_array($item) ? $item['name'] : $item->name }}
                      x{{ is_array($item) ? $item['quantity'] : $item->quantity }}
                    </li>
                  @endforeach
                </ul>
              </div>

              <div class="mt-3 pt-3 border-t border-gray-200">
                <div class="flex justify-between items-center">
                  <span class="font-medium">Total Amount:</span>
                  <span class="font-semibold text-[#6F4E37]">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Summary Statistics -->
        <div class="p-4 bg-white border-t">
          <h3 class="font-semibold mb-3">Order Statistics</h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 p-3 rounded-lg">
              <p class="text-sm text-blue-600">Active Orders</p>
              <p class="text-2xl font-bold text-blue-700">{{ $statistics['active_orders'] }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-lg">
              <p class="text-sm text-green-600">Completed Orders</p>
              <p class="text-2xl font-bold text-green-700">{{ $statistics['completed_orders'] }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Add these functions to your existing script
    function openBillingModal() {
      document.getElementById('billingModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeBillingModal() {
      document.getElementById('billingModal').classList.add('hidden');
      document.body.style.overflow = ''; // Restore background scrolling
    }

    function handleModalClick(event) {
      if (event.target.id === 'billingModal') {
        closeBillingModal();
      }
    }

    // Update your existing DOMContentLoaded event listener
    document.addEventListener('DOMContentLoaded', function() {
      // ... existing code ...

      // Update billing button click handler
      const billingBtn = document.getElementById('billingBtn');
      if (billingBtn) {
        billingBtn.addEventListener('click', function(e) {
          e.preventDefault();
          openBillingModal();
        });
      }

      // Add escape key listener for modal
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          closeBillingModal();
        }
      });
    });
  </script>

  <style>
    /* Modal scrollbar styling */
    .overflow-y-auto {
      scrollbar-width: thin;
      scrollbar-color: #6F4E37 #f3f4f6;
    }

    .overflow-y-auto::-webkit-scrollbar {
      width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
      background: #f3f4f6;
      border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
      background-color: #6F4E37;
      border-radius: 3px;
    }
  </style>

  <!-- Add notification container -->
  <div id="notificationContainer" class="fixed inset-0 flex items-center justify-center pointer-events-none z-50">
    <div id="notifications" class="space-y-4"></div>
  </div>

  <!-- Update notification styles -->
  <style>
    @keyframes slideInDown {
      from {
        transform: translateY(-100%);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes fadeOut {
      from {
        opacity: 1;
      }
      to {
        opacity: 0;
      }
    }

    .notification {
      animation: slideInDown 0.5s ease-out, fadeOut 0.5s ease-in 2.5s;
      background-color: #6F4E37;
      color: white;
      padding: 1rem 2rem;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      pointer-events: auto;
      max-width: 90%;
      width: auto;
      text-align: center;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .notification.success {
      background-color: #059669;
    }

    .notification.error {
      background-color: #DC2626;
    }

    .notification-icon {
      font-size: 1.5rem;
    }
  </style>

</body>

</html>
