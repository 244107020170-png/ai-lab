<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard | AILab</title>
    <!-- <link rel="stylesheet" href="views/css/member_dashboard.css"> -->

    <!-- css -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'SF Pro Rounded', sans-serif;
            background: #0b0f15;
            color: white;
            margin: 0;
        }

        /* BACKGROUND LAYER */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
        }

        .bg-image {
            position: absolute;
            inset: 0;
            background-image: url("views/img/background3.jpg");
            background-size: cover;
            background-position: center;
            opacity: 0.25;
        }

        .bg-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(15, 32, 39, 0.65),
                    rgba(32, 58, 67, 0.65),
                    rgba(44, 83, 100, 0.65));
            z-index: -1;
        }



        /* HEADER */
        .header-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);

            display: flex;
            justify-content: space-between;
            align-items: center;

            width: 90%;
            max-width: 1440px;

            z-index: 999;
        }

        /* Left header */
        .header-left {
            border-radius: 90px !important;
            padding: 12px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-left img {
            width: 120px;
            transition: 0.25s ease;
        }

        .header-left .logo-text {
            font-size: 22px;
            color: rgba(255, 255, 255, 0.7);
            font-family: 'SF Pro Rounded';
        }

        /* Right header (Navbar) */
        .right-navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .header-right {
            border-radius: 90px !important;
            padding: 12px 22px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Navbar Items */
        .nav-item {
            display: inline-block;
            /* so padding applies */
            padding: 8px 18px;
            border-radius: 30px;
            cursor: pointer;
            color: #8EF1FF !important;
            font-size: 18px;
            font-family: 'SF Pro Rounded';
            transition: 0.25s ease;
            text-decoration: none !important;
        }

        .nav-item {
            transition: color .25s ease, transform .2s ease;
        }

        .nav-item:hover {
            transform: translateY(-2px);
            color: white !important;
        }

        /* Selected Navbar */
        .selected-navbar {
            background: linear-gradient(246deg, #03D3F1 0%, #027A8B 100%) !important;
            color: white !important;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0px 4px 30px rgba(0, 0, 0, 0.3);
        }

        /* ./Whole header */

        /* MAIN CONTAINER */
        .main-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            /* bukan 24px */
            display: flex;
            flex-direction: column;

            min-height: 100vh;
        }

        /* GLASS BOX */
        .leBox {
            background: rgba(0, 0, 0, 0.34);
            border-radius: 11px;
            outline: 1px solid rgba(255, 255, 255, 0.11);
            backdrop-filter: blur(6px);
        }

        /* WELCOME SECTION */
        .member-welcome {
            width: 100%;
            padding: 26px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;

            background: rgba(0, 0, 0, 0.34);
            outline: 1px solid rgba(255, 255, 255, 0.11);
            border-radius: 11px;
            backdrop-filter: blur(6px);

            animation: fadeIn 0.7s ease-out;
        }

        .welcome-title {
            font-size: 26px;
            font-weight: 700;
            color: white;
        }

        .welcome-title span {
            color: #8EF1FF;
        }

        .welcome-desc {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.65);
            margin-top: 4px;
        }

        .welcome-avatar img {
            width: 74px;
            height: 74px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.35);
        }

        /* STATS */
        .stats-row {
            width: 100%;
            max-width: 100%;

            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .stat-box {
            width: auto;
            padding: 18px 20px;

            display: flex;
            flex-direction: column;
            flex: 1;
            gap: 10px;

            background: rgba(0, 0, 0, 0.34);
            border-radius: 11px;
            outline: 1px solid rgba(255, 255, 255, 0.11);
            backdrop-filter: blur(6px);

            transition: 0.25s ease;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.4);
        }

        .stat-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-icon img {
            width: 28px;
            height: 28px;
            opacity: 0.9;
        }

        /* RECENT ACTIVITY */
        .member-container {
            padding: 26px 32px;
            width: 100%;
            max-width: 1200px;

            background: rgba(0, 0, 0, 0.34);
            border-radius: 11px;
            outline: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
            transition: 0.4s ease;
        }

        .member-title {
            font-size: 20px;
            font-weight: 700;
            color: white;
        }

        .member-table {
            width: 100%;
            margin-top: 16px;
            border-collapse: collapse;
            color: white;
            table-layout: fixed;
        }

        .member-table th {
            color: rgba(255, 255, 255, 0.75);
            text-align: left;
            padding-bottom: 10px;
            font-weight: 600;
        }

        .member-table td {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
        }

        .member-table tr:hover td {
            background: rgba(255, 255, 255, 0.05);
            transition: 0.25s ease;
        }

        @media (max-width: 900px) {
            .member-welcome {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
            }

            .stats-row {
                flex-direction: column;
                align-items: center;
            }

            .stat-box {
                width: 100%;
            }

            .member-container {
                padding: 18px 20px;
            }
        }

        /* animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-fade {
            opacity: 0;
            transform: translateY(20px);
            transition: 0.7s ease;
        }

        .page-fade.show {
            opacity: 1;
            transform: translateY(0);
        }

        .leBox,
        .stat-box,
        .member-container {
            opacity: 0;
            transform: translateY(20px);
        }

        .admin-footer {
            width: 100%;
            margin-top: 40px;
            padding: 20px 0;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            background: rgba(0, 0, 0, 0.15);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }


        #text-footer {
            color: white !important;
            font-size: 16px;
            text-align: center;
        }

        .dashboard-wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 140px auto 60px auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            gap: 25px;
            /* bikin jarak antar box mirip admin */
        }
    </style>

</head>

<body>

    <!-- HEADER / NAVBAR MEMBER -->
    <div class="header-container">

        <!-- LEFT (LOGO) -->
        <div class="leBox header-left">
            <a href="index.php?action=member_dashboard">
                <img src="views/img/logo.png" alt="Logo">
            </a>
        </div>

        <!-- RIGHT NAV -->
        <div class="right-navbar-container">
            <div class="leBox header-right">
                <a href="index.php?action=member_dashboard" class="nav-item selected-navbar">Dashboard</a>
                <a href="index.php?action=member_profile" class="nav-item">My Profile</a>
                <a href="index.php?action=member_research" class="nav-item">My Research</a>
            </div>

            <div class="leBox header-right">
                <a href="index.php?action=logout" class="nav-item">Logout</a>
            </div>
        </div>
    </div>

    <!-- BACKGROUND LAYER -->
    <div class="bg-layer" aria-hidden="true">
        <div class="bg-image"></div>
        <div class="bg-gradient"></div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="main-container page-fade">

        <!-- WRAPPER FOR ALL CONTENT -->
        <div class="dashboard-wrapper">

            <!-- WELCOME -->
            <div class="member-welcome leBox">
                <div class="welcome-left">
                    <h1 class="welcome-title">Welcome back, <span> <?php echo $_SESSION['username']; ?> </span> 👋</h1>
                    <p class="welcome-desc">Here is a quick summary of your activities inside the Lab.</p>
                </div>

                <div class="welcome-avatar">
                    <img id="userAvatar"
                         src=""
                         alt="User Avatar">
                </div>

            </div>

            <!-- STATS -->
            <div class="stats-row">

                <div class="leBox stat-box">
                    <div class="stat-inner">
                        <div class="stat-text">
                            <div class="stat-title">My Research Submitted</div>
                            <div class="stat-value">4</div>
                            <div class="stat-desc">1 awaiting approval</div>
                        </div>

                        <div class="stat-icon">
                            <img src="views/img/projects-logo.png" alt="">
                        </div>
                    </div>
                </div>

                <div class="leBox stat-box">
                    <div class="stat-inner">
                        <div class="stat-text">
                            <div class="stat-title">Profile Completion</div>
                            <div class="stat-value">92%</div>
                            <div class="stat-desc">2 fields missing</div>
                        </div>

                        <div class="stat-icon">
                            <img src="views/img/person-logo.png" alt="">
                        </div>
                    </div>
                </div>

            </div>

            <!-- RECENT ACTIVITY -->
            <div class="leBox member-container">
                <div class="member-inner">

                    <div class="member-header">
                        <div class="member-title">Recent Activity</div>
                    </div>

                    <table class="member-table">
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>

                        <tr>
                            <td>Research</td>
                            <td>Submitted “AI in Agriculture Study”</td>
                            <td>2025-12-08</td>
                        </tr>

                        <tr>
                            <td>Profile</td>
                            <td>Updated academic title</td>
                            <td>2025-12-04</td>
                        </tr>

                        <tr>
                            <td>Research</td>
                            <td>Added 3 new publications</td>
                            <td>2025-12-01</td>
                        </tr>
                    </table>

                </div>
            </div>

        </div> <!-- END dashboard-wrapper -->

    </div>
    <footer class="admin-footer">
        <div id="text-footer">© 2025 AI Lab Polinema</div>
    </footer>

    <!-- OPTIONAL JS (tidak wajib, tapi placeholder) -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // Fade on load
            const fadeEls = document.querySelectorAll(".page-fade");
            fadeEls.forEach(el => {
                setTimeout(() => el.classList.add("show"), 120);
            });

            // Scroll reveal
            const revealEls = document.querySelectorAll(".leBox, .member-container");

            function revealOnScroll() {
                revealEls.forEach(el => {
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight - 80) {
                        el.style.opacity = "1";
                        el.style.transform = "translateY(0)";
                        el.style.transition = "0.7s ease";
                    }
                });
            }

            window.addEventListener("scroll", revealOnScroll);
            revealOnScroll();

            // fade leBox on load
            document.querySelectorAll(".leBox").forEach((box, i) => {
                setTimeout(() => {
                    box.style.opacity = "1";
                    box.style.transform = "translateY(0)";
                    box.style.transition = "0.7s ease";
                }, 200 + i * 120);
            });

        });
        fetch("index.php?action=member_dashboard_api")
            .then(res => res.json())
            .then(data => {

                // NAMA
                document.querySelector(".welcome-title span").textContent = data.member.full_name;

                // AVATAR
                // AVATAR (PAKAI HASIL DARI BACKEND)
                const avatarEl = document.querySelector(".welcome-avatar img");
                avatarEl.src = data.member.photo_url;

                avatarEl.onerror = () => {
                    avatarEl.src = "views/img/memberavatar.png";
                };
                // RESEARCH STATS
                document.querySelectorAll(".stat-value")[0].textContent = data.research_total;

                // PROFILE COMPLETION
                document.querySelectorAll(".stat-value")[1].textContent = data.profile_completion + "%";

                // RECENT ACTIVITY
                const table = document.querySelector(".member-table");
                table.innerHTML = `
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        `;

                data.recent_activities.forEach(a => {
                    table.innerHTML += `
                <tr>
                    <td>Activity</td>
                    <td>${a.title} — ${a.location}</td>
                    <td>${a.year}</td>
                </tr>
            `;
                });
            })
            .catch(err => console.error("API ERROR:", err));
    </script>
</body>

</html>