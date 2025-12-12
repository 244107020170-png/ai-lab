<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>403 Forbidden</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.15);
        --accent: #03D3F1;
        --accent2: #027A8B;
    }

    * {
        margin: 0;
        padding: 0;
        font-family: 'SF Pro Rounded', sans-serif;
        box-sizing: border-box;
        color: white;
    }

    body {
        background: #0b0f15;
        overflow: hidden;
    }

    .bg-layer {
        position: fixed;
        inset: 0;
        z-index: -1;
    }
    .bg-image {
        background-image: url("img/background3.jpg");
        background-size: cover;
        background-position: center;
        width: 100%;
        height: 100%;
        opacity: 0.25;
    }
    .bg-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.6), rgba(0,0,0,0.85));
    }

    .container {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 20px;
        text-align: center;
        padding: 20px;
    }

    .card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        padding: 40px 55px;
        border-radius: 16px;
        backdrop-filter: blur(8px);
        max-width: 450px;
    }

    h1 {
        font-size: 55px;
        font-weight: 700;
        background: linear-gradient(90deg, var(--accent), var(--accent2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    p {
        font-size: 18px;
        opacity: 0.8;
        margin-bottom: 25px;
        line-height: 1.4;
    }

    .btn {
        display: inline-block;
        margin-top: 15px;
        padding: 12px 28px;
        font-size: 16px;
        font-weight: 600;
        background: linear-gradient(90deg, var(--accent), var(--accent2));
        color: #012;
        border-radius: 10px;
        cursor: pointer;
        text-decoration: none;
        transition: 0.25s ease;
        box-shadow: 0 0 18px rgba(3,211,241,0.35);
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 25px rgba(3,211,241,0.55);
    }

</style>
</head>
<body>

<div class="bg-layer">
    <div class="bg-image"></div>
    <div class="bg-gradient"></div>
</div>

<div class="container">
    <div class="card">
        <h1>403</h1>
        <p>You don't have permission to access this page.<br>
        Please return to the correct dashboard.</p>

        <?php
        // Smart Redirect
        if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin') {
            echo '<a href="index.php?action=home" class="btn">Back to Admin Dashboard</a>';
        } else {
            echo '<a href="index.php?action=member_dashboard" class="btn">Back to Member Dashboard</a>';
        }
        ?>
    </div>
</div>

</body>
</html>
