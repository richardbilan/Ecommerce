@extends('layouts.sidebar')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Location Management</h1>
            <div class="flex items-center space-x-3">
                <span class="text-gray-700">Shop Status:</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="shopStatusToggle" class="sr-only peer" {{ $currentLocation && $currentLocation->is_open ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900" id="shopStatusText">
                        {{ $currentLocation && $currentLocation->is_open ? 'OPEN' : 'CLOSED' }}
                    </span>
                </label>
            </div>
        </div>

        <!-- Current Location Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Current Location</h2>
            <div id="map" class="w-full h-[400px] mb-6 rounded-lg"></div>
            
            <div id="currentLocationInfo" class="space-y-3">
                <p class="flex items-center text-gray-700">
                    <span class="font-semibold w-32">Address:</span>
                    <span id="currentAddress" class="flex-1">Loading...</span>
                </p>
                <p class="flex items-center text-gray-700">
                    <span class="font-semibold w-32">Coordinates:</span>
                    <span id="currentCoordinates" class="flex-1">Loading...</span>
                </p>
                <p class="flex items-center text-gray-700">
                    <span class="font-semibold w-32">Hours:</span>
                    <span id="currentHours" class="flex-1">Loading...</span>
                </p>
                <p class="flex items-center text-gray-700">
                    <span class="font-semibold w-32">Description:</span>
                    <span id="currentDescription" class="flex-1">Loading...</span>
                </p>
            </div>
        </div>

        <!-- Update Location Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Location</h2>
            <form id="updateLocationForm" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" id="address" name="address" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#6F4E37] focus:ring-[#6F4E37]" 
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" id="description" name="description" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#6F4E37] focus:ring-[#6F4E37]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opening Time</label>
                        <input type="time" id="opening_time" name="opening_time" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#6F4E37] focus:ring-[#6F4E37]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Closing Time</label>
                        <input type="time" id="closing_time" name="closing_time" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#6F4E37] focus:ring-[#6F4E37]">
                    </div>
                </div>

                <div class="flex items-center space-x-4 mt-6">
                    <button type="button" id="getCurrentPosition" 
                            class="bg-[#6F4E37] text-white px-6 py-2 rounded-md hover:bg-[#5C4130] transition-colors duration-200">
                        Get Current Position
                    </button>
                    <button type="submit" 
                            class="bg-[#6F4E37] text-white px-6 py-2 rounded-md hover:bg-[#5C4130] transition-colors duration-200">
                        Update Location
                    </button>
                </div>

                <!-- Hidden inputs for coordinates -->
                <input type="hidden" id="latitude" name="latitude" required>
                <input type="hidden" id="longitude" name="longitude" required>
            </form>
        </div>

        <!-- Location History Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Location History</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="locationHistory">
                        <!-- Location history will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Add Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Add Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map;
let marker;
let currentLocationIcon;
let defaultLocation = [14.5995, 120.9842]; // Manila coordinates

document.addEventListener('DOMContentLoaded', function() {
    initMap();
    loadCurrentLocation();
    loadLocationHistory();

    // Set up form submission
    document.getElementById('updateLocationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateLocation();
    });

    // Set up get current position button with permission handling
    document.getElementById('getCurrentPosition').addEventListener('click', requestLocation);

    // Shop Status Toggle Handler
    const toggleButton = document.getElementById('shopStatusToggle');
    const statusText = document.getElementById('shopStatusText');

    if (toggleButton && statusText) {
        // Function to update status without page reload
        async function updateShopStatus(isOpen) {
            try {
                const response = await fetch('/api/cafe/toggle-status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ is_open: isOpen })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (data.success) {
                    statusText.textContent = isOpen ? 'OPEN' : 'CLOSED';
                    showNotification(`Shop is now ${isOpen ? 'open' : 'closed'}`, 'success');
                    
                    // Dispatch shop status change event
                    window.dispatchEvent(new CustomEvent('shop-status-changed', {
                        detail: { isOpen: isOpen }
                    }));
                    
                    // Store the status in localStorage
                    localStorage.setItem('shopStatus', isOpen ? 'open' : 'closed');
                    
                    // Update toggle button state
                    toggleButton.checked = isOpen;
                } else {
                    throw new Error(data.message || 'Failed to update shop status');
                }
            } catch (error) {
                console.error('Error:', error);
                // Revert the toggle state
                toggleButton.checked = !isOpen;
                statusText.textContent = !isOpen ? 'OPEN' : 'CLOSED';
                showNotification(error.message || 'Error updating shop status', 'error');
            }
        }

        toggleButton.addEventListener('change', function() {
            const isOpen = this.checked;
            
            // Show loading state
            statusText.textContent = 'Updating...';
            toggleButton.disabled = true;
            
            updateShopStatus(isOpen).finally(() => {
                toggleButton.disabled = false;
            });
        });

        // Check current status periodically
        function checkCurrentStatus() {
            fetch('/api/cafe/current-location')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.location) {
                        const isOpen = data.location.is_open;
                        toggleButton.checked = isOpen;
                        statusText.textContent = isOpen ? 'OPEN' : 'CLOSED';
                    }
                })
                .catch(error => console.error('Error checking status:', error));
        }

        // Check status every 30 seconds
        setInterval(checkCurrentStatus, 30000);

        // Initial status check
        checkCurrentStatus();
    }
});

function initMap() {
    // Initialize the map
    map = L.map('map').setView(defaultLocation, 15);

    // Add the OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Create custom icon for the marker
    currentLocationIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        shadowSize: [41, 41]
    });

    // Add a draggable marker
    marker = L.marker(defaultLocation, {
        draggable: true,
        icon: currentLocationIcon
    }).addTo(map);

    // Update coordinates when marker is dragged
    marker.on('dragend', function() {
        const position = marker.getLatLng();
        updateCoordinatesAndAddress(position.lat, position.lng);
    });

    // Add click listener to map
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinatesAndAddress(e.latlng.lat, e.latlng.lng);
    });
}

async function requestLocation() {
    try {
        if (!navigator.geolocation) {
            showNotification('Geolocation is not supported by your browser', 'error');
            return;
        }

        const permission = await checkLocationPermission();
        if (permission === 'granted') {
            getCurrentPosition();
        } else if (permission === 'prompt') {
            showNotification('Please allow location access when prompted', 'info');
            getCurrentPosition();
        } else {
            showNotification('Location permission denied. Please enable location access in your browser settings.', 'error');
        }
    } catch (error) {
        showNotification('Error accessing location: ' + error.message, 'error');
    }
}

async function checkLocationPermission() {
    try {
        if (navigator.permissions && navigator.permissions.query) {
            const result = await navigator.permissions.query({ name: 'geolocation' });
            return result.state;
        }
        return 'prompt';
    } catch (error) {
        console.error('Error checking location permission:', error);
        return 'prompt';
    }
}

function getCurrentPosition() {
    const options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    };

    showNotification('Getting your current location...', 'info');
    
    navigator.geolocation.getCurrentPosition(
        position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            updateCoordinatesAndAddress(lat, lng);
            
            // Update marker and center map
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 17);

            showNotification('Location successfully updated!', 'success');
        },
        error => {
            let errorMessage = 'Error getting location: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Location permission denied.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Location information unavailable.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Location request timed out.';
                    break;
                default:
                    errorMessage += error.message;
            }
            showNotification(errorMessage, 'error');
        },
        options
    );
}

function updateCoordinatesAndAddress(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    
    // Update coordinates display
    document.getElementById('currentCoordinates').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    // Use OpenStreetMap Nominatim for reverse geocoding
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            const address = data.display_name;
            document.getElementById('address').value = address;
            document.getElementById('currentAddress').textContent = address;
        })
        .catch(error => {
            console.error('Error getting address:', error);
            showNotification('Error getting address from coordinates', 'error');
        });
}

function showNotification(message, type = 'info') {
    if (type === 'error') {
        alert('❌ ' + message);
    } else if (type === 'success') {
        alert('✅ ' + message);
    } else if (type === 'warning') {
        alert('⚠️ ' + message);
    } else {
        alert('ℹ️ ' + message);
    }
}

function loadCurrentLocation() {
    fetch('/api/cafe/current-location')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.location) {
                const location = data.location;
                
                // Update form fields
                document.getElementById('address').value = location.address;
                document.getElementById('description').value = location.description || '';
                document.getElementById('opening_time').value = location.opening_time || '';
                document.getElementById('closing_time').value = location.closing_time || '';
                document.getElementById('latitude').value = location.latitude;
                document.getElementById('longitude').value = location.longitude;
                
                // Update current location info
                document.getElementById('currentAddress').textContent = location.address;
                document.getElementById('currentCoordinates').textContent = 
                    `${location.latitude}, ${location.longitude}`;
                document.getElementById('currentHours').textContent = 
                    location.opening_time && location.closing_time ? 
                    `${location.opening_time} - ${location.closing_time}` : 'Not set';
                document.getElementById('currentDescription').textContent = 
                    location.description || 'No description';
                
                // Update map
                const latLng = [parseFloat(location.latitude), parseFloat(location.longitude)];
                marker.setLatLng(latLng);
                map.setView(latLng, 15);
            }
        })
        .catch(error => console.error('Error loading current location:', error));
}

function loadLocationHistory() {
    fetch('/api/cafe/location-history')
        .then(response => response.json())
        .then(data => {
            const historyContainer = document.getElementById('locationHistory');
            historyContainer.innerHTML = '';
            
            data.locations.forEach(location => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${new Date(location.created_at).toLocaleDateString()}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${location.address}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${location.opening_time ? location.opening_time + ' - ' + location.closing_time : 'Not set'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <button onclick="setAsCurrentLocation(${location.id})" 
                                class="text-[#6F4E37] hover:text-[#5C4130]">
                            Set as Current
                        </button>
                    </td>
                `;
                historyContainer.appendChild(row);
            });
        })
        .catch(error => console.error('Error loading location history:', error));
}

function updateLocation() {
    const formData = new FormData(document.getElementById('updateLocationForm'));
    
    fetch('/api/cafe/update-location', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData)),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Location updated successfully', 'success');
            loadCurrentLocation();
            loadLocationHistory();
        } else {
            showNotification(data.message || 'Error updating location', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating location', 'error');
    });
}

function setAsCurrentLocation(locationId) {
    fetch(`/api/cafe/set-current-location/${locationId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Current location updated successfully', 'success');
            loadCurrentLocation();
            loadLocationHistory();
        } else {
            showNotification(data.message || 'Error updating current location', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating current location', 'error');
    });
}

document.getElementById('shopStatusToggle').addEventListener('change', async function(e) {
    try {
        const response = await fetch('/api/cafe/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            const statusText = document.getElementById('shopStatusText');
            statusText.textContent = data.is_open ? 'OPEN' : 'CLOSED';
            
            // Show notification
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg transition-opacity duration-500';
            notification.textContent = `Shop is now ${data.is_open ? 'open' : 'closed'}`;
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            }, 3000);

            // Dispatch shop status change event
            window.dispatchEvent(new CustomEvent('shop-status-changed', {
                detail: { isOpen: data.is_open }
            }));
        } else {
            throw new Error(data.message || 'Failed to update shop status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update shop status. Please try again.');
        e.target.checked = !e.target.checked; // Revert the toggle
    }
});
</script>
@endpush 