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

    <div class="main-container">

        <!--Brief Description-->
        <div class="desc-title">Members Management</div>
        <div class="desc-text">
            Manage members and their role, contact, name, etc. This will include the students who became a volunteer.
            You can edit the statuses in the more menu.
        </div>

        <!--/.Brief Description-->

        <!-- Personal Information Container -->
        <span style="font-size: 32px; font-weight: 600; margin-bottom: 20px; color: #F5F7FA; padding-top: 20px;">Personal Information</span>
        <div class="leBox form-container">

            <form action="index.php?action=save" method="POST">
                <input type="hidden" name="dosen_id" value="<?= isset($data['id']) ? $data['id'] : '' ?>">

                <div class="form-row">
                    <div class="form-group half">
                        <label>Full Name</label>
                        <input type="text" name="nama" class="glass-input"
                            value="<?= isset($data['full_name']) ? $data['full_name'] : '' ?>"
                            placeholder="e.g. Spinosaurus Aegyptiacus">
                    </div>
                    <div class="form-group half">
                        <label>Degree (Role)</label>
                        <input type="text" name="status" class="glass-input"
                            value="<?= isset($data['role']) ? $data['role'] : '' ?>"
                            placeholder="e.g. S.T., M.MT.">
                    </div>
                </div>

                <div class="form-group">
                    <label>Brief Description</label>
                    <textarea name="description" class="glass-input textarea"
                        placeholder="I am a student who wants to be..."><?= isset($data['description']) ? $data['description'] : '' ?></textarea>
                </div>

                <div class="section-header">External Links</div>

                <div class="form-group">
                    <label>Google Scholar</label>
                    <input type="text" name="scholar" class="glass-input"
                        value="<?= isset($data['scholar']) ? $data['scholar'] : '' ?>"
                        placeholder="https://scholar.google.com/...">
                </div>

                <div class="form-group">
                    <label>LinkedIn</label>
                    <input type="text" name="linkedin" class="glass-input"
                        value="<?= isset($data['linkedin']) ? $data['linkedin'] : '' ?>"
                        placeholder="https://linkedin.com/in/...">
                </div>

                <div class="form-group">
                    <label>ResearcherID (ResearchGate)</label>
                    <input type="text" name="researchgate" class="glass-input"
                        value="<?= isset($data['researchgate']) ? $data['researchgate'] : '' ?>"
                        placeholder="https://www.researchgate.net/profile/...">
                </div>

                <div class="form-group">
                    <label>Orcid</label>
                    <input type="text" name="orcid" class="glass-input"
                        value="<?= isset($data['orcid']) ? $data['orcid'] : '' ?>"
                        placeholder="https://orcid.org/...">
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>

            </form>
            <div style="margin-bottom: 20px;">
                <a href="index.php?action=members" style="color: rgba(255,255,255,0.6); text-decoration: none;">&larr; Back to Members</a>
            </div>
        </div>
    </div>

</body>

</html>