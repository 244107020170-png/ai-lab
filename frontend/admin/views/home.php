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
            justify-content: center;
            align-items: flex-start;
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
    <div class="main-container">

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



        <!-- Recent Data -->
        <div class="graphs-row">

            <!-- Recent Members -->
            <div class="leBox member-container">
                <div class="member-inner">
                    <div class="member-header">
                        <div class="member-title">Recent Members</div>
                        <div class="member-btn">
                            <a href="index.php?action=members_form" style="display: block; color: white !important; text-decoration: none !important;">
                                Add Members
                            </a>
                        </div>
                    </div>


                    <table class="member-table">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                        <tr>
                            <?php foreach ($recentmembers as $m): ?>
                                <td><?= $m['id'] ?></td>
                                <td><?= $m['full_name'] ?></td>
                                <td>
                                    <?php
                                    $status = $m['status'];
                                    $dotClass = match ($status) {
                                        'Active'   => 'status-active',
                                        'On Leave' => 'status-warning',
                                        'Inactive' => 'status-inactive',
                                        default    => 'status-inactive'
                                    };
                                    ?>
                                    <span class="status-dot <?= $dotClass ?>"></span>
                                    <?= $status ?>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <!-- /.Recent Members -->

            <!-- Recent Projects -->
            <div class="leBox member-container">
                <div class="member-inner">
                    <div class="member-header">
                        <div class="member-title">Recent Projects</div>
                        <div class="member-btn">
                            <a href="index.php?action=projects&op=create" style="display: block; color: white !important; text-decoration: none !important;">
                                Add Projects
                            </a>
                        </div>
                    </div>


                    <table class="member-table">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Status</th>
                        </tr>
                        <tr>
                            <?php foreach ($recentprojects as $m): ?>
                                <td><?= $m['id'] ?></td>
                                <td><?= $m['title'] ?></td>
                                <td>
                                    <?php
                                    $status = strtolower($m['status']); // normalize database value

                                    $dotClass = match ($status) {
                                        'published'   => 'status-active',
                                        'progressing', 'progress' => 'status-warning',
                                        'cancelled'   => 'status-inactive',
                                        default       => 'status-inactive'
                                    };
                                    ?>
                                    <span class="status-dot <?= $dotClass ?>"></span>
                                    <?= statusUI($status) ?>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <!-- /.Recent Projects -->

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