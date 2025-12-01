<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="views/css/home.css">
    <link rel="stylesheet" href="views/css/anims.css">
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
            background-image: url("views/img/background3.jpg");
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
            align-items: center;
            justify-content: center;
            color: #fff;
            padding: 2rem;
        }
    </style>

</head>

<body>

<div class="header-container">

        <!-- LEFT BOX -->
        <div class="leBox header-left">
            <a href="../"><img src="views/img/logo.png"></a>
            <div class="logo-text">Lab Admin Page</div>
        </div>

        <!-- RIGHT BOX (NAVBAR) -->
        <div class="leBox header-right">
            <div class="nav-item selected-navbar">Home</div>
            <a href="index.php?action=members" class="nav-item">Members</a>
            <a href="index.php?action=projects" class="nav-item">Projects</a>
            <a href="index.php?action=news" class="nav-item">News</a>
        </div>
    </div>

    <!-- Background: Image + Gradient -->
    <div class="bg-layer" aria-hidden="true">
        <div class="bg-image"></div>
        <div class="bg-gradient"></div>
    </div>

    <!-- Main Figma Canvas Container -->
    <div class="main-container" style="margin-top: 50px;">

        <!--Brief Description-->
        <div class="desc-title">
            Admin Dashboard</div>
        <div class="desc-text">
            The home page serves as a quick view of the performance graph for the Projects that are published by the Lab
            and
            Volunteers that has joined the lab. Each subpage serves to manage the data that will be displayed in the
            main
            website. Call this the backend site for the main one.</div>

        <!--/.Brief Description-->

        <!-- Three Statistic Boxes -->
        <div class="stats-row ">

            <div class="leBox stat-box">
                <div class="stat-inner">
                    <div class="stat-text">
                        <div class="stat-title">Total Members</div>
                        <div class="stat-value"><?php echo $totalmembers ?></div>
                        <div class="stat-desc">+<?php echo $members2025 ?> this year</div>
                    </div>
                    <div class="stat-icon">
                        <img src="views/img/person-logo.png" alt="">
                    </div>
                </div>
            </div>

            <div class="leBox stat-box">
                <div class="stat-inner">
                    <div class="stat-text">
                        <div class="stat-title">Projects Published</div>
                        <div class="stat-value"><?php echo $projectspublished ?></div>
                        <div class="stat-desc"><?php echo $projectsinprogress ?> in progress.</div>
                    </div>
                    <div class="stat-icon">
                        <img src="views/img/projects-logo.png" alt="">
                    </div>
                </div>
            </div>

            <div class="leBox stat-box">
                <div class="stat-inner">
                    <div class="stat-text">
                        <div class="stat-title">Activity’s done this month</div>
                        <div class="stat-value"><?php echo $newscount ?></div>
                        <div class="stat-desc"><?php echo $newscountpending ?> Pending.</div>
                    </div>
                    <div class="stat-icon">
                        <img src="views/img/clipboard-pencil-logo.png" alt="">
                    </div>
                </div>
            </div>

        </div>

        <!-- /.Three Statistic Boxes -->

        

        <!-- Graph Section Title + Description -->
        <div class="graphs-row">

            <!-- Graph Box 1 -->
            <div class="leBox graph-box">
                <div class="graph-inner">
                    <div class="graph-header">
                        <div class="graph-title">Projects Graph</div>
                        <div class="graph-btn">
                            <a href="projects.html" style="display: block; color: white !important; text-decoration: none !important;">
                                Add Project
                            </a>
                        </div>
                    </div>

                    <div class="graph-wrap">
                        <div class="graph-area">
                            <div class="graph-outline"></div>
                            <div class="graph-gradient"></div>
                            <img src="views/img/graph-lines.png">
                        </div>

                        <div class="graph-labels">
                            <div>Jan</div>
                            <div>Feb</div>
                            <div>Mar</div>
                            <div>Apr</div>
                            <div>May</div>
                            <div>Jun</div>
                            <div>Jul</div>
                            <div>Aug</div>
                            <div>Sep</div>
                            <div>Oct</div>
                            <div>Nov</div>
                            <div>Dec</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graph Box 2 -->
            <div class="leBox graph-box">
                <div class="graph-inner">
                    <div class="graph-header">
                        <div class="graph-title">Volunteers Joined Graph</div>
                        <div class="graph-btn small">
                            <a href="members.html" style="display: block; color: white !important; text-decoration: none !important;">
                                Manage Volunteers
                            </a>    
                        </div>
                    </div>

                    <div class="graph-wrap">
                        <div class="graph-area">
                            <div class="graph-outline"></div>
                            <div class="graph-gradient"></div>
                            <img src="views/img/graph-lines.png">
                        </div>

                        <div class="graph-labels wide">
                            <div>2017</div>
                            <div>2018</div>
                            <div>2019</div>
                            <div>2020</div>
                            <div>2021</div>
                            <div>2022</div>
                            <div>2023</div>
                            <div>2024</div>
                            <div>2025</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.Graphs Container -->

        <!-- Footer Section -->
        <footer class="footer">
            <div>
                <span style="color: white; font-size: 24px;"">©</span>
                        <span style=" color: white; font-size: 24px;"">2025 AI Lab Polinema</span>
            </div>

            <div>
                <span style="color: white; font-size: 24px;">Contact: </span>
                <span style="color: white; font-size: 24px; text-decoration: underline;">
                    <a style="color: white;" href="mailto:ailab@polinema.ac.id" class="underline text-white">
                        ailab@polinema.ac.id
                    </a>
                </span>
            </div>
        </footer>
    </div>
    <!-- /.Main Canvas -->

</body>

</html>