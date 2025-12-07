<?php
// $news = null (add) atau array (edit)
// $categories dari controller sudah benar
$actionUrl = $news ? "index.php?action=news_update" : "index.php?action=news_store";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $news ? 'Edit News' : 'Add News' ?> • Admin</title>
  <link rel="stylesheet" href="views/css/admin-news.css">
  <link rel="stylesheet" href="views/css/news-form.css">
</head>

<body>

<!-- BACKGROUND -->
<div class="bg-layer">
  <div class="bg-image"></div>
  <div class="bg-gradient"></div>
</div>

<!-- HEADER (same as news-list, no active navbar on form pages) -->
<header class="admin-header">
  <div class="leBox header-left">
    <a href="index.php?action=home">
      <img src="views/img/logo.png" class="admin-logo">
    </a>
    <span class="admin-title">Lab Admin Page</span>
  </div>

  <nav class="leBox header-right">
    <a href="index.php?action=home" class="nav-item">Home</a>
    <a href="index.php?action=members" class="nav-item">Members</a>
    <a href="index.php?action=projects" class="nav-item">Projects</a>
    <a href="index.php?action=news" class="nav-item selected-navbar">News</a>
  </nav>
</header>

<!-- TITLE -->
<section class="admin-section-title">
  <h1><?= $news ? 'Edit News' : 'Add News' ?></h1>
  <p>Manage the news that will be shown in the website. Freely edit on who participated in the project, what is the current status of the projects, and the link towards the document if any.</p>
</section>

<!-- UNSAVED BANNER -->
<div id="unsavedBanner" class="unsaved-banner" style="display:none;">
    <div class="unsaved-left">
        <div class="dot"></div>
        <span>You have unsaved changes.</span>
    </div>
    <div class="unsaved-actions">
        <button type="button" id="discardBtn" class="btn-outline">Discard</button>
        <button type="button" id="saveBannerBtn" class="btn-solid">Save</button>
    </div>
</div>

<!-- FORM -->
<form id="newsForm" class="glass-panel news-form"
      action="<?= $actionUrl ?>"
      method="post"
      enctype="multipart/form-data"
      novalidate>

  <?php if ($news): ?>
    <input type="hidden" name="id" value="<?= $news['id'] ?>">
  <?php endif; ?>

  <div class="form-grid">

    <!-- LEFT IMAGE COLUMN -->
    <div class="col-left">

      <!-- Thumbnail -->
      <div class="image-block">
        <div class="image-preview">
        <?php
                    // FIX: Database already has "img/name.png", so we just need "../" to go up one folder.
                    $pathPrefix = '../img/news/';
                    $defaultImg = '../img/news/default.png';

                    // Check if photo exists in DB
                    if (!empty($news['image_thumb'])) {
                        // Result: ../img/yan.png
                        $photoSrc = $pathPrefix . $news['image_thumb'] . '?' . time();
                    } else {
                        // Result: ../profile-photos/default-profile.png
                        $photoSrc = $defaultImg;
                    }
                    ?>
        
          <img id="thumbPreview"
               src="<?= $photoSrc ?>">
        </div>

        <div class="image-meta">
          <label class="upload-btn">
            Upload Thumbnail
            <input type="file" name="image_thumb" id="thumbInput"
                   accept="image/png,image/jpeg" <?= $news ? '' : 'required' ?>>
          </label>
          <div id="thumbFilename" class="filename">
            <?= $news['image_thumb'] ? basename($news['image_thumb']) : '' ?>
          </div>
          <div class="hint">PNG/JPG — max 5MB — resized to width 600px</div>
        </div>
      </div>

      <!-- Detail Image -->
      <div class="image-block">
        <div class="image-preview">
          <img id="detailPreview"
               src="<?= $news['image_detail'] ?? 'views/img/placeholder-185x112.png' ?>">
        </div>

        <div class="image-meta">
          <label class="upload-btn">
            Upload Detail Image
            <input type="file" name="image_detail" id="detailInput"
                   accept="image/png,image/jpeg" <?= $news ? '' : 'required' ?>>
          </label>
          <div id="detailFilename" class="filename">
            <?= $news['image_detail'] ? basename($news['image_detail']) : '' ?>
          </div>
          <div class="hint">Required — PNG/JPG — max 5MB — resized to width 1200px</div>
        </div>
      </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-right">

      <label class="field">
        <span class="label">News Title</span>
        <input type="text" name="title"
               value="<?= htmlspecialchars($news['title'] ?? '') ?>"
               required>
      </label>

      <label class="field">
        <span class="label">Category</span>
        <select name="category" required>
          <option value="">-- Select Category --</option>

          <?php
          // 4 kategori fixed yang kamu minta
          $fixedCats = ['award', 'collaboration', 'innovation', 'research'];
          ?>
          <?php foreach ($fixedCats as $cat): ?>
            <option value="<?= $cat ?>"
              <?= (isset($news['category']) && $news['category'] === $cat) ? 'selected' : '' ?>>
              <?= $cat ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label class="field">
        <span class="label">Brief Description</span>
        <textarea name="excerpt" rows="4" required><?= htmlspecialchars($news['excerpt'] ?? '') ?></textarea>
      </label>

      <label class="field">
        <span class="label">Full Content</span>
        <textarea name="content" rows="7"><?= htmlspecialchars($news['content'] ?? '') ?></textarea>
      </label>

      <div class="two-inline">

        <label class="field small">
          <span class="label">Created At</span>
          <input type="date" name="created_at"
            value="<?= isset($news['created_at']) ? date('Y-m-d', strtotime($news['created_at'])) : date('Y-m-d') ?>">
        </label>

        <label class="field small">
          <span class="label">Status</span>
          <select name="status">
            <option value="none"  <?= isset($news['status']) && $news['status']==='none' ? 'selected':''; ?>>none</option>
            <option value="main" <?= isset($news['status']) && $news['status']==='main' ? 'selected':''; ?>>main</option>
          </select>
        </label>

      </div>

      <label class="field">
        <span class="label">Short Quote</span>
        <textarea name="quote" rows="3"><?= htmlspecialchars($news['quote'] ?? '') ?></textarea>
      </label>

    </div>
  </div>

</form>

<!-- FOOTER -->
<footer class="admin-footer">
  <div class="footer-left">© 2025 AI Lab Polinema</div>
  <div class="footer-right">Contact: <span>ailab@polinema.ac.id</span></div>
</footer>

<script src="views/js/news-form.js"></script>
</body>
</html>
