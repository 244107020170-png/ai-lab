<?php
$activity = $activity ?? null;
$mode = $activity ? 'edit' : 'create';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $mode==='edit' ? 'Edit Project' : 'Add Project'; ?></title>
  <link rel="stylesheet" href="views/css/projects.css">
</head>
<body>

<div class="bg-layer">
  <div class="bg-image"></div>
  <div class="bg-gradient"></div>
</div>

  <!-- Header -->
  <div class="header-container">

    <!-- LEFT BOX -->
    <div class="header-box header-left">
      <a href="../"><img src="views/img/logo.png"></a>
    </div>

    <!-- RIGHT BOX (NAVBAR) -->
    <div class="right-navbar-container">
      <div class="header-box header-right">
        <a href="index.php?action=index" class="nav-item">Home</a>

        <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="index.php?action=members" class="nav-item">Members</a>
        <?php endif; ?>

        <a href="index.php?action=projects" class="nav-item selected-navbar">Projects</a>

        <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="index.php?action=news" class="nav-item">News</a>
        <?php endif; ?>
      </div>

      <div class="header-box header-right">
        <a href="index.php?action=logout" class="nav-item">Logout</a>
      </div>
    </div>
  </div>

<main class="container form-container">
  <h1>Project Management</h1>
  <p class="lead">Create or edit project data. Upload thumbnail and banner if available.</p>

  <form id="projectForm" method="post" action="index.php?action=projects&op=<?php echo $mode==='edit' ? 'update' : 'store'; ?>" enctype="multipart/form-data">
    <?php if($mode==='edit'): ?>
      <input type="hidden" name="id" value="<?php echo intval($activity['id']); ?>">
    <?php endif; ?>

    <div class="card">
      <!-- <label>Project Thumbnail</label> -->
      <div class="thumb-row">
        <div class="thumb-preview" style="margin-top: 10px;">
          <?php if($activity && !empty($activity['thumbnail_image'])): ?>
            <img src="<?php echo "../img/activities/" . htmlspecialchars($activity['thumbnail_image']); ?>" alt="thumb" style="width: 287px; height: 112px;" />
          <?php else: ?>
            <img src="<?php echo "../img/activities/default.png"; ?>" alt="" style="width: 287px; height: 112px;" />
          <?php endif; ?>
        </div>
        <div class="thumb-upload">
          <input type="file" name="thumbnail_image" accept="image/*">
          <?php if($activity && !empty($activity['thumbnail_image'])): ?>
            <div class="small-note"><?php echo basename($activity['thumbnail_image']); ?></div>
          <?php endif; ?>
        </div>
      </div>

      <label>Full Name</label>
      <input type="text" name="title" value="<?php echo htmlspecialchars($activity['title'] ?? ''); ?>" required>

      <label>Brief Description</label>
      <textarea name="short_description"><?php echo htmlspecialchars($activity['short_description'] ?? ''); ?></textarea>

      <label>Full Description</label>
      <textarea name="full_description" rows="8"><?php echo htmlspecialchars($activity['full_description'] ?? ''); ?></textarea>

      <label>Created At</label>
      <input type="text" value="<?php echo htmlspecialchars($activity['created_at'] ?? ''); ?>" readonly>

      <label>Published At</label>
      <input type="date" name="published_at"
       value="<?php echo (!empty($activity['published_at'] ?? null))
            ? date('Y-m-d', strtotime($activity['published_at']))
            : ''; ?>">

      <label>Status</label>
      <select name="status" class="form-control">
    <option value="published"
        <?= (($activity['status'] ?? '') === 'published') ? 'selected' : '' ?>>
        Published
    </option>

    <option value="progress"
        <?= (($activity['status'] ?? '') === 'progress') ? 'selected' : '' ?>>
        Progressing
    </option>

    <option value="cancelled"
        <?= (($activity['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>
        Cancelled
    </option>
</select>


      <label>Project Document Link</label>
      <input type="url" name="document_link" value="<?php echo htmlspecialchars($activity['document_link'] ?? ''); ?>">

      <div class="form-actions">
        <a class="btn discard" href="index.php?action=projects">Discard</a>
        <button type="submit" class="btn save">Save</button>
      </div>
    </div>
  </form>
</main>

<div id="toast" class="toast"></div>

<!-- FOOTER -->
<footer class="admin-footer">
  <div id="text-footer">© 2025 AI Lab Polinema</div>
</footer>

<script src="views/js/projects.js"></script>
</body>
</html>
