<?php
if ($_SESSION['role'] != 'admin') {
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management</title>
    <link rel="stylesheet" href="views/css/home.css">
    <link rel="stylesheet" href="views/css/anims.css">
    <link rel="stylesheet" href="views/css/members.css">
    <link rel="stylesheet" href="views/css/form.css">
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
        </div>

        <!-- RIGHT BOX (NAVBAR) -->
        <div class="right-navbar-container">
            <div class="leBox header-right">
                <a href="index.php?action=index" class="nav-item">Home</a>
                <a href="index.php?action=members" class="nav-item selected-navbar">Members</a>
                <a href="index.php?action=projects" class="nav-item">Projects</a>
                <a href="index.php?action=news" class="nav-item">News</a>
            </div>

            <div class="leBox header-right">
                <a href="index.php?action=logout" class="nav-item">Logout</a>
            </div>
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
        <span style="font-size: 28px; font-weight: 600; margin-bottom: 20px; color: #f5f7fab0; padding-top: 20px;">Personal Information</span>
        <div class="leBox form-container">

            <!-- Back Button -->
            <div style="margin-bottom: 20px;">
                <a href="index.php?action=members" style="color: rgba(255,255,255,0.6); text-decoration: none;">&larr; Back to Members</a>
            </div>
            <!-- /.Back Button -->

            <!-- Form -->
            <form action="index.php?action=members_form_save" method="POST" id="memberForm" enctype="multipart/form-data">
                <!-- Profile Photo -->
                <div class="profile-container" style="margin-bottom: 20px;">

                    <?php
                    // FIX: Database already has "img/name.png", so we just need "../" to go up one folder.
                    $pathPrefix = '../img/profile-photos/';
                    $defaultImg = '../img/profile-photos/default-profile.png';

                    // Check if photo exists in DB
                    if (!empty($data['photo'])) {
                        // Result: ../img/yan.png
                        $photoSrc = $pathPrefix . $data['photo'] . '?' . time();
                    } else {
                        // Result: ../profile-photos/default-profile.png
                        $photoSrc = $defaultImg;
                    }
                    ?>

                    <img src="<?= $photoSrc ?>" id="preview-img" alt="profile-photo"
                        width="84px" height="112px"
                        style="border-radius: 11px; outline: 1px solid rgba(255, 255, 255, 0.11); object-fit: cover;">

                    <div class="photo-text">
                        <div>
                            <div style="color: #F5F7FA; margin-bottom: 5px;">Profile Photo</div>
                            <div style="color: #f5f7fa7c;">Please upload an image with the format of PNG, JPEG, and JPG</div>
                        </div>

                        <input type="file" name="photo" id="photo_input" accept="image/png, image/jpeg, image/jpg" style="display: none;">

                        <div style="display: flex; align-items: center; gap: 15px;">
                            <label for="photo_input" class="member-btn-action" style="margin-left: 0; color: #F5F7FA !important; padding: 6px 10px;">
                                Upload Photo
                            </label>

                            <span id="file-name" style="color: #f5f7fa7c; font-size: 13px;">No file chosen</span>
                        </div>
                    </div>
                </div>
                <!-- /.Profile Photo -->

                <input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : '' ?>">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="glass-input"
                        value="<?= isset($data['full_name']) ? $data['full_name'] : '' ?>"
                        placeholder="e.g. Spinosaurus Aegyptiacus">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <input type="text" name="role" class="glass-input"
                        value="<?= isset($data['role']) ? $data['role'] : '' ?>"
                        placeholder="e.g. Terrestial Carnivore">
                </div>

                <div class="form-group">
                    <label>Expertise</label>
                    <input type="text" name="expertise" class="glass-input"
                        value="<?= isset($data['expertise']) ? $data['expertise'] : '' ?>"
                        placeholder="e.g. Paleoanthropology">
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

                <select name="status" class="glass-input">
                    <option class="glass-input" value="Active" <?= isset($data['status']) && $data['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                    <option class="glass-input" value="On Leave" <?= isset($data['status']) && $data['status'] == 'On Leave' ? 'selected' : '' ?>>On Leave</option>
                    <option class="glass-input" value="Inactive" <?= isset($data['status']) && $data['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>


        </div>
        <!-- /.Personal Information Container -->

        <!-- Study Background Container -->
        <!-- Table -->
        <div class="leBox form-container" style="margin-top: 20px;">
            <div class="section-header">Educational Background</div>

            <table class="member-table">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 200px;">Institute Name</th>
                    <th style="width: 400px;">Academic Title</th>
                    <th style="width: 80px;">Year</th>
                    <th>Degree</th>
                    <th style="width: 50px;"></th>
                </tr>

                <?php
                // Ensure variable exists
                if (!isset($studyBackground)) $studyBackground = [];
                ?>

                <?php foreach ($studyBackground as $index => $s): ?>
                    <tr>
                        <td>
                            <?= is_numeric($s['id']) ? $s['id'] : '<span style="opacity:0.5; font-size:10px;">NEW</span>' ?>

                            <input type="hidden" name="backgrounds[<?= $index ?>][id]" value="<?= isset($s['id']) ? $s['id'] : 'new' ?>">
                        </td>
                        <td>
                            <input type="text" class="table-input"
                                name="backgrounds[<?= $index ?>][institute]"
                                value="<?= $s['institute'] ?>" placeholder="Institute">
                        </td>
                        <td>
                            <input type="text" class="table-input"
                                name="backgrounds[<?= $index ?>][academic_title]"
                                value="<?= $s['academic_title'] ?>" placeholder="Title">
                        </td>
                        <td>
                            <input type="number" class="table-input"
                                name="backgrounds[<?= $index ?>][year]"
                                value="<?= $s['year'] ?>" placeholder="Year">
                        </td>
                        <td>
                            <input type="text" class="table-input"
                                name="backgrounds[<?= $index ?>][degree]"
                                value="<?= $s['degree'] ?>" placeholder="Degree">
                        </td>
                        <td style="text-align: center;">
                            <?php if (is_numeric($s['id'])): ?>
                                <a href="index.php?action=members_delete_background&id=<?= $s['id'] ?>&member_id=<?= $data['id'] ?>"
                                    class="text-red"
                                    style="text-decoration:none; color: #ff6b6b; font-size: 12px;"
                                    onclick="return confirm('Are you sure you want to delete this field?')">
                                    &#10005;
                                </a>
                            <?php else: ?>
                                <a href="#" onclick="this.closest('tr').remove(); return false;" class="text-red" style="text-decoration:none; color: #ff6b6b; font-size: 12px;">&#10005;</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div style="padding: 15px; display: flex; justify-content: center;">
                <button type="submit" name="add_row" value="1" class="discard-btn" style="font-size: 12px;">
                    + Add Field
                </button>
            </div>
        </div>
        <!-- Table -->
        </form>
        <!-- /.Study Background Container -->


    </div>

    <!-- FOOTER -->
    <footer class="admin-footer">
        <div id="text-footer">© 2025 AI Lab Polinema</div>
    </footer>
    <!-- /.Footer Section -->

    <!-- Save Bar -->
    <div class="leBox save-bar">
        <div class="save-text">
            <span class="info-icon">!</span>
            You have unsaved changes.
        </div>
        <div class="save-actions">
            <a href="index.php?action=members" class="discard-btn">Discard</a>

            <button type="submit" form="memberForm" class="save-btn-floating">Save</button>
        </div>
    </div>
    <!-- /.Save Bar -->

    <script src="views/js/members.js"></script>
    <script src="views/js/forms.js"></script>
</body>

</html>