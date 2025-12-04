<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="stylesheet" href="views/css/login.css">
</head>
<body>

    <div class="login-bg"></div>

    <div class="login-card">
        <img src="views/img/logo.png" alt="Logo" class="login-logo">

        <h1 class="login-title">Login</h1>
        <p class="login-subtitle">Please enter the credentials</p>

        <form id="loginForm" class="login-form" method="POST" action="../index.php?action=login_process">
            
            <div class="input-wrapper">
                <input type="text" name="username" id="username" placeholder="Username" required>
            </div>

            <div class="input-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>

            <div class="show-pass">
                <input type="checkbox" id="togglePass">
                <label for="togglePass">Show Password</label>
            </div>

            <button type="submit" class="login-btn">Login</button>

        </form>
    </div>

    <script src="views/js/login.js"></script>
</body>
</html>
