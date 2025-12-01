<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management</title>
    <link rel="stylesheet" href="views/css/home.css">
    <link rel="stylesheet" href="views/css/anims.css">
    <link rel="stylesheet" href="views/css/members.css">
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
    <!-- Header -->
    <div class="header-container">

        <!-- LEFT BOX -->
        <div class="leBox header-left">
            <a href="../"><img src="views/img/logo.png"></a>
            <div class="logo-text">Lab Admin Page</div>
        </div>

        <!-- RIGHT BOX (NAVBAR) -->
        <div class="leBox header-right">
            <a href="index.php?action=index" class="nav-item">Home</a>
            <div class="nav-item selected-navbar">Members</div>
            <a href="projects.html" class="nav-item">Projects</a>
            <a href="news.html" class="nav-item">News</a>
        </div>

    </div>
    <!-- /.Header -->
    
    <!-- Background: Image + Gradient -->
    <div class="bg-layer" aria-hidden="true">
        <div class="bg-image"></div>
        <div class="bg-gradient"></div>
    </div>

    <!-- Main Container -->
    <div class="main-container" style="margin-top: 50px;">
        <!--Brief Description-->
        <div class="desc-title">Members Management</div>
        <div class="desc-text">
            Manage members and their role, contact, name, etc. This will include the students who became a volunteer.
            You can edit the statuses in the more menu.
        </div>

        <!--/.Brief Description-->

        <!-- Search Filter -->
        <div class="search-filter">
            <div class="leBox search-box">
                <img src="views/img/maginifier-icon.png" alt="">
                <input type="text" placeholder="Search Members" class="search-text">
            </div>

            <div class="filter-container">
                <div class="leBox filter-box" onclick="toggleFilter()">
                    <span id="filter-label">Filter</span>
                    <img src="views/img/arrow-down.png" class="filter-arrow" alt="">
                </div>

                <div class="filter-dropdown" id="filter-dropdown">
                    <div onclick="setFilter('Name')">Name</div>
                    <div onclick="setFilter('ID')">ID</div>
                    <div onclick="setFilter('Position')">Position</div>
                    <div onclick="setFilter('Status')">Status</div>
                </div>
            </div>

            <!-- NEW SORT BOX -->
            <div class="sort-container">
                <div class="leBox sort-box" onclick="toggleSort()">
                    <span id="sort-label">Asc</span>
                    <img src="views/img/arrow-down.png" alt="">
                </div>

                <div class="sort-dropdown" id="sort-dropdown">
                    <div onclick="setSort('Asc')">Asc</div>
                    <div onclick="setSort('Desc')">Desc</div>
                </div>
            </div>
        </div>
        <!-- /.Search Filter -->

        <!-- Members Lists -->
        <div class="leBox member-container">
            <div class="member-inner">
                <div class="member-header">
                    <div class="member-title">Members List</div>
                    <div class="member-btn">
                        <a href="projects.html" style="display: block; color: white !important; text-decoration: none !important;">
                            Add Members
                        </a>
                    </div>
                </div>


                <table class="member-table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Expertise</th>
                        <th></th>
                    </tr>
                    <tr>
                        <?php foreach ($members as $m): ?>
                            <td><?= $m['id'] ?></td>
                            <td><?= $m['full_name'] ?></td>
                            <td><?= $m['role'] ?></td>
                            <td><?= $m['expertise'] ?></td>
                            <td>...</td>
                    </tr>
                <?php endforeach; ?>
                </table>



                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?action=members&page=<?= $page - 1 ?>" class="page-btn">&lt;</a>
                    <?php else: ?>
                        <a href="#" class="page-btn disabled">&lt;</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?action=members&page=<?= $i ?>" class="page-btn <?= ($i == $page) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?action=members&page=<?= $page + 1 ?>" class="page-btn">&gt;</a>
                    <?php else: ?>
                        <a href="#" class="page-btn disabled">&gt;</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <!-- /.Members Lists -->

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
        <!-- /.Footer Section -->
    </div>
    <!-- /.Main Container -->

    <script src="views/js/members.js"></script>
</body>

</html>