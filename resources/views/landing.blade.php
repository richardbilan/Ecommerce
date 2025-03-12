<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brew Vroom</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="container">
        <!-- LEFT COLUMN -->
        <div class="left">
            <!-- NAVIGATION BAR -->
            <nav class="navbar">
                <div class="logo">
                    <img src="/images/cart.png" alt="Brew Vroom Logo">
                </div>
                <ul>
                    <li><a href="/">HOME</a></li>
                    <li><a href="#">MENU</a></li>
                    <li><a href="#">CONTACT</a></li>
                    <li><a href="/login">SERVICES PROVIDED</a></li>
                </ul>
            </nav>

            <!-- TEXT & BUTTONS CONTAINER -->
            <div class="text-container">
                <h1 class="slide-text"> SIP, <span class="highlight">BREW</span>, SAVOR & <br>REPEAT </h1>
                <p>Satisfy all your coffee and non-coffee cravings with us 🍫
                <br> Brewing at the same spot til supplies last, see you!</p>

                <!-- BUTTONS (Inside Text Container) -->
                <div class="btn-group">
                    <button id="order-btn">Login</button>
                    <button id="okay-btn">Register</button>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="right">
            <div class="image-container">
                <img src="/images/cup.png" class="coffee-cup" alt="Coffee Cup">
            </div>

            <div class="right-text">BREW VROOM CAFE</div>
            <div class="right-text outlined">BREW VROOM CAFE</div>
        </div>
    </div>

    <img src="/images/beans.png" class="bottom-left-image" alt="Decorative Image">

    <script>
        $(document).ready(function() {
            $(".slide-text").css({position: "relative"}).animate({left: "-50px"}, 1500);

            // When "Login" is clicked, redirect to login page
            $("#order-btn").click(function() {
                $(".right").animate({left: "-100%"}, 1000, function() {
                    window.location.href = "/login"; // Redirect to Login Page
                });
            });

            // When "Register" is clicked, redirect to register page
            $("#okay-btn").click(function() {
                $(".right").animate({left: "-100%"}, 1000, function() {
                    window.location.href = "/register"; // Redirect to Register Page
                });
            });
        });
    </script>

</body>
</html>
