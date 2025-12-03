<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>News Management • Admin</title>
  <link rel="stylesheet" href="views/css/admin-news.css">
</head>

<body>

<!-- BACKGROUND -->
<div class="bg-layer">
  <div class="bg-image"></div>
  <div class="bg-gradient"></div>
</div>

<!-- HEADER -->
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
  <h1>News Management</h1>
  <p>Manage the News and Research Highlight that will be shown in the website. You can set which three news that will be displayed in the main website.</p>
</section>

<!-- CONTROLS -->
<div class="top-controls">

  <!-- SEARCH -->
  <div class="search-box glass-box">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <!-- SORT -->
  <select id="sortSelect" class="glass-box"
    style="padding:10px 14px; border-radius:11px; background:rgba(255,255,255,0.05); color:white;">
      <option value="">Sort</option>
      <option value="title_az">Title A–Z</option>
      <option value="category_az">Category A–Z</option>
      <option value="newest">Newest First</option>
      <option value="oldest">Oldest First</option>
      <option value="status_main_first">Status main → none</option>
  </select>

  <!-- CATEGORY FILTER -->
  <select id="categoryFilter" class="glass-box"
    style="padding:10px 15px; border-radius:11px; background:rgba(255,255,255,0.1); color:white;">
      <option value="">All Categories</option>
      <option value="award">award</option>
      <option value="collaboration">collaboration</option>
      <option value="innovation">innovation</option>
      <option value="research">research</option>
  </select>

</div>

<!-- TABLE -->
<div class="table-wrapper glass-panel">

  <div class="table-header">
    <h2>News List</h2>
    <a href="index.php?action=news_create" class="add-btn" style="text-decoration:none;">+ Add News</a>
  </div>

  <div class="table-head-row">
    <div>ID</div>
    <div>Title</div>
    <div>Category</div>
    <div>Status</div>
    <div>Actions</div>
  </div>

  <div id="tableBody">
    <?php foreach ($news as $n): ?>
      <div class="project-row">

        <div><?= $n['id'] ?></div>

        <div><?= htmlspecialchars($n['title']) ?></div>

        <div><?= htmlspecialchars($n['category']) ?></div>

        <div style="display:flex; align-items:center; gap:6px;">
          <div class="status-dot <?= $n['status'] === 'main' ? 'status-main' : 'status-none' ?>"></div>
          <span><?= $n['status'] ?></span>
        </div>

        <div style="display:flex; gap:14px; justify-content:center;">
          <a href="index.php?action=news_edit&id=<?= $n['id'] ?>" style="color:#8EF1FF;">✏️</a>

          <button class="delete-btn"
                  data-id="<?= $n['id'] ?>"
                  style="background:none;border:none;color:#ff6b6b;cursor:pointer;">
            🗑️
          </button>
        </div>

      </div>
    <?php endforeach; ?>
  </div>

  <!-- PAGINATION -->
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a class="page-btn" href="index.php?action=news&page=<?= $page - 1 ?>">←</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="page-indicator <?= $i == $page ? 'active' : '' ?>"
         href="index.php?action=news&page=<?= $i ?>"></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
      <a class="page-btn" href="index.php?action=news&page=<?= $page + 1 ?>">→</a>
    <?php endif; ?>
  </div>

</div>

<!-- FOOTER -->
<footer class="admin-footer">
  <div class="footer-left">© 2025 AI Lab Polinema</div>
  <div class="footer-right">Contact: <span>ailab@polinema.ac.id</span></div>
</footer>

<script src="views/js/admin-news.js"></script>

<!-- DELETE MODAL -->
<div id="deleteModal" class="modal-overlay" style="display:none;">
  <div class="modal-window glass-panel">
      <h3 style="margin-bottom:10px;">Delete News?</h3>
      <p style="opacity:0.8; margin-bottom:20px;">This action cannot be undone.</p>

      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button id="cancelDelete" class="btn-outline">Cancel</button>
        <button id="confirmDelete" class="btn-solid" style="background:#ff4d4d;">Delete</button>
      </div>
  </div>
</div>

</body>
</html>