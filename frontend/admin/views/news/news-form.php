<?php
// news-form.php
// Usage:
// - For ADD: set $news = null
// - For EDIT: set $news = associative array from DB (keys: id, title, category, excerpt, content, created_at, quote, image_thumb, image_detail, status)

// safe defaults
$news = $news ?? null;
$categories = $categories ?? []; // pass from controller: unique category list
$actionUrl = $news ? "index.php?action=news_update" : "index.php?action=news_store";
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $news ? 'Edit News' : 'Add News' ?> • Admin</title>

  <link rel="stylesheet" href="views/css/news-form.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- background + header: try include project's header if exists -->
<?php
$headerPath = __DIR__ . '/../shared/header.php';
if (file_exists($headerPath)) {
    include $headerPath;
} else {
    // fallback header (kept minimal but matches style)
    ?>
    <div class="bg-layer" aria-hidden="true">
      <div class="bg-image"></div>
      <div class="bg-gradient"></div>
    </div>

    <div class="admin-header glass-pill">
      <div class="admin-header-left">
        <img src="views/img/logowhite.png" alt="logo" class="admin-logo">
        <span class="admin-title">Lab Admin Page</span>
      </div>
      <nav class="admin-nav glass-pill">
        <a href="index.php?action=home" class="nav-item">Home</a>
        <a href="index.php?action=members" class="nav-item">Members</a>
        <a href="index.php?action=projects" class="nav-item">Projects</a>
        <a href="index.php?action=news" class="nav-item nav-active">News</a>
      </nav>
    </div>
    <?php
}
?>

<main class="main-container">
  <section class="admin-section-title">
    <h1><?= $news ? 'Edit News' : 'Add News' ?></h1>
    <p>Manage the news that will be shown in the website. Keep styling consistent with the admin panel.</p>
  </section>

  <!-- unsaved changes banner -->
  <div id="unsavedBanner" class="unsaved-banner" style="display:none">
    <div class="unsaved-left">
      <div class="dot"></div>
      <div class="unsaved-text">You have unsaved changes.</div>
    </div>
    <div class="unsaved-actions">
      <button id="discardBtn" type="button" class="btn-outline">Discard</button>
      <button id="saveBannerBtn" type="button" class="btn-solid">Save</button>
    </div>
  </div>

  <form id="newsForm" class="glass-panel news-form" action="<?= $actionUrl ?>" method="post" enctype="multipart/form-data" novalidate>
    <!-- If editing -->
    <?php if ($news): ?>
      <input type="hidden" name="id" value="<?= htmlspecialchars($news['id']) ?>">
    <?php endif; ?>

    <div class="form-grid">
      <!-- left column: images & brief -->
      <div class="col-left">
        <div class="image-block">
          <div class="image-preview">
            <img id="thumbPreview" src="<?= $news['image_thumb'] ?? 'views/img/placeholder-185x112.png' ?>" alt="Thumbnail preview">
          </div>
          <div class="image-meta">
            <label class="upload-btn">
              Upload Thumbnail
              <input id="thumbInput" name="image_thumb" type="file" accept="image/png,image/jpeg">
            </label>
            <div id="thumbFilename" class="filename"><?= $news['image_thumb'] ? basename($news['image_thumb']) : '' ?></div>
            <div class="hint">Please upload PNG/JPG. Max 5MB. Resized to 600px width.</div>
          </div>
        </div>

        <div class="image-block">
          <div class="image-preview">
            <img id="detailPreview" src="<?= $news['image_detail'] ?? 'views/img/placeholder-185x112.png' ?>" alt="Detail preview">
          </div>
          <div class="image-meta">
            <label class="upload-btn">
              Upload Detail Image
              <input id="detailInput" name="image_detail" type="file" accept="image/png,image/jpeg" <?= $news ? '' : 'required' ?>>
            </label>
            <div id="detailFilename" class="filename"><?= $news['image_detail'] ? basename($news['image_detail']) : '' ?></div>
            <div class="hint">Required. PNG/JPG. Max 5MB. Resized to 1200px width.</div>
          </div>
        </div>

      </div>

      <!-- right column: inputs -->
      <div class="col-right">
        <label class="field">
          <div class="label">News Title</div>
          <input type="text" name="title" value="<?= htmlspecialchars($news['title'] ?? '') ?>" required>
        </label>

        <label class="field">
          <div class="label">Category</div>
          <select name="category" required>
            <option value="">-- Select category --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($news['category']) && $news['category'] === $cat) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>

        <label class="field">
          <div class="label">Brief Description</div>
          <textarea name="excerpt" rows="5" required><?= htmlspecialchars($news['excerpt'] ?? '') ?></textarea>
        </label>

        <label class="field">
          <div class="label">Full Content</div>
          <textarea name="content" rows="8"><?= htmlspecialchars($news['content'] ?? '') ?></textarea>
        </label>

        <label class="two-inline">
          <div>
            <div class="label">Created At</div>
            <input type="date" name="created_at" value="<?= isset($news['created_at']) ? date('Y-m-d', strtotime($news['created_at'])) : date('Y-m-d') ?>">
          </div>

          <div>
            <div class="label">Status</div>
            <select name="status">
              <option value="none" <?= (isset($news['status']) && $news['status'] === 'none') ? 'selected' : '' ?>>none</option>
              <option value="main" <?= (isset($news['status']) && $news['status'] === 'main') ? 'selected' : '' ?>>main</option>
            </select>
          </div>
        </label>

        <label class="field">
          <div class="label">Short Quote</div>
          <textarea name="quote" rows="3"><?= htmlspecialchars($news['quote'] ?? '') ?></textarea>
        </label>

        <div class="form-actions">
          <a href="index.php?action=news" class="btn-outline">Discard</a>
          <button id="saveBtn" type="submit" class="btn-solid"><?= $news ? 'Update' : 'Save' ?></button>
        </div>
      </div>
    </div>
  </form>

  <div style="height:40px;"></div>
</main>

<?php
$footerPath = __DIR__ . '/../shared/footer.php';
if (file_exists($footerPath)) include $footerPath;
?>

<script src="views/js/news-form.js"></script>
</body>
</html>
