<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registerds Page</title>
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
</head>
<body>
    <div class="container">
        <div class="logo">
            <p>suuu Logooooooooo</p>
        </div>
        <div class="form-container">
            <h2>Create an Account</h2>
            <form action="#" method="POST">
                <input type="text" placeholder="Name" required>
                <input type="email" placeholder="Email" required>
                <div class="password-group">
                    <input type="password" placeholder="Password" required>
                    <input type="password" placeholder="Confirm Password" required>
                </div>
                <div class="buttons">
                    <button class="register">Register</button>
                    <button class="signin">Sign In</button>
                </div>
            </form>
            <hr>
            <div class="social-login">
                <p>Facebook | Gmail | Google</p>
            </div>
        </div>
    </div>
</body>
</html>
