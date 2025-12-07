<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="stylesheet" href="css/login.css">

    <style>
        :root {
            --img-opacity: 0.25;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        /* Background Layers */
        .bg-layer {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -2;
        }

        .bg-image {
            position: absolute;
            inset: 0;
            background-image: url("img/background3.jpg");
            background-size: cover;
            background-position: center;
            opacity: var(--img-opacity);
            transform: translateZ(0);
        }

        .bg-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(#0F2027, #203A43, #2C5364);
            z-index: -1;
            mix-blend-mode: normal;
        }

        main {
            position: relative;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            color: #fff;
            padding: 2rem;
        }
    </style>
</head>
<body>

    <!-- Background Image + Gradient -->
    <div class="bg-layer" aria-hidden="true">
        <div class="bg-image"></div>
        <div class="bg-gradient"></div>
    </div>
    <!-- /.Background -->

    <div class="login-card">
        <img src="img/logo.png" alt="Logo" class="login-logo">

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
