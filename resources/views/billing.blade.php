

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
                    <h3 class="font-semibold">Americano
                        <span class="text-sm text-gray-500">(Cold, Tall)</span>
                    </h3>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 21l-1.45-1.316C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81
                            4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55
                            11.184L12 21z" />
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
        <!-- End Example Cart Item -->

        <!-- You can loop more cart items here in Blade -->
        {{-- @foreach($cartItems as $item)
            // Repeat the cart item structure above with dynamic data.
        @endforeach --}}
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
