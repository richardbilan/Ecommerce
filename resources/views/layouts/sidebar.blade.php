<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('../css/style_sidebar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
    <aside class="sidebar">
    <div class="logo">
        <img src="../css/images/Logo.jpg" alt="Logo">
    </div>
    <nav>
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="{{ route('inventory') }}"><i class="fas fa-box"></i><span>Inventory Management</span></a></li>
            <li><a href="{{ route('orders') }}"><i class="fas fa-clipboard-list"></i><span>Order Management</span></a></li>
            <li><a href="{{ route('delivery') }}"><i class="fas fa-truck"></i><span>Delivery Management</span></a></li>
            <li><a href="{{ route('promotions') }}"><i class="fas fa-tags"></i><span>Promotion & Discounts</span></a></li>
            <li><a href="{{ route('location') }}"><i class="fas fa-location-dot"></i><span> Location Management</span></a></li>
        </ul>
    </nav>
    <div class="logout">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i><span>Log out</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>


</aside>

        <main class="content">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
