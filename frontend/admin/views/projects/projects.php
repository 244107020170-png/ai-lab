<?php
// STATUS MAPPING (FINAL)
function statusUI($db)
{
  $map = [
    "published" => "Published",
    "progress"  => "Progressing",
    "cancelled" => "Cancelled"
  ];
  return $map[$db] ?? ucfirst($db);
}

function statusColor($db)
{
  $map = [
    "published" => "green",
    "progress"  => "yellow",
    "cancelled" => "red"
  ];
  return $map[$db] ?? "grey";
}


// variables passed from controller or fallback
$q = $_GET['q'] ?? ($q ?? '');
$status = $_GET['status'] ?? ($status ?? '');
$page = intval($_GET['page'] ?? ($page ?? 1));
$limit = 10;
$total = $total ?? 0;
$rows = $rows ?? [];
$pages = ($total > 0) ? ceil($total / $limit) : 1;
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Projects - Admin</title>
  <link rel="stylesheet" href="views/css/projects.css">
  <link rel="stylesheet" href="views/css/anims.css">
</head>

<body>

  <div class="bg-layer">
    <div class="bg-image"></div>
    <div class="bg-gradient"></div>
  </div>

  <!-- <div class="admin-header">

  <div class="header-left">
    <img src="views/img/logo.png" class="admin-logo">
  </div>

  <nav class="header-right nav">
    <a href="index.php">Home</a>
    <a href="index.php?action=members">Members</a>
    <a class="active" href="index.php?action=projects">Projects</a>
    <a href="index.php?action=news" class="nav-item">News</a>
  </nav>

</div> -->

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


  <main class="container layout">
    <h1>Project Management</h1>
    <p class="lead">Manage the projects that will be shown in the website. Freely edit on who participated in the project, what is the current status of the projects, and the link towards the document if any. If there isn’t, just upload the PDF file of the document.</p>

    <div class="controls">
      <form id="searchForm" method="get" action="index.php" class="controls-form">
        <input type="hidden" name="action" value="projects">

        <div class="search-container">
          <input type="text" id="search-input" name="q" placeholder="Search title..." value="<?php echo htmlspecialchars($q); ?>">
          <div id="suggest-box" class="suggest-box"></div>
        </div>

        <select name="status" id="statusSelect">
          <option value="">All status</option>
          <option value="published" <?= ($status == 'published' ? 'selected' : '') ?>>Published</option>
          <option value="progress" <?= ($status == 'progress'  ? 'selected' : '') ?>>Progressing</option>
          <option value="cancelled" <?= ($status == 'cancelled' ? 'selected' : '') ?>>Cancelled</option>
        </select>
      </form>
    </div>


    <div class="table-wrapper">
      <div class="table-card">

        <div class="table-header">
          <h2>Projects List</h2>
          <a href="index.php?action=projects&op=create" class="add-btn">+ Add Projects</a>
        </div>
        <!-- TABLE HEADER -->
        <div class="table-head">
          <div class="col id">ID</div>
          <div class="col title">Title</div>
          <div class="col status">Status</div>
          <div class="col actions">Actions</div>
        </div>

        <!-- TABLE BODY -->
        <div class="table-body">
          <?php if (empty($rows)): ?>
            <div class="no-data">No projects found.</div>
            <?php else: foreach ($rows as $r): ?>

              <div class="table-row" id="row-<?php echo $r['id']; ?>">
                <div class="col id">PJ<?php echo str_pad($r['id'], 3, '0', STR_PAD_LEFT); ?></div>
                <div class="col title"><?php echo htmlspecialchars($r['title']); ?></div>
                <div class="col status">
                  <span class="dot <?= statusColor($r['status']); ?>"></span>
                  <span class="label"><?= statusUI($r['status']); ?></span>
                </div>
                <div class="col actions">
                  <button class="dots-btn" data-id="<?= $r['id']; ?>">…</button>
                  <div class="dots-menu" data-id="<?= $r['id']; ?>">
                    <a href="index.php?action=projects&op=edit&id=<?= $r['id']; ?>">Edit</a>
                    <a href="#" class="delete-btn" data-id="<?= $r['id']; ?>">Delete</a>
                  </div>
                </div>
              </div>

          <?php endforeach;
          endif; ?>
        </div>

        <!-- pagination -->
        <div class="pagination">
          <?php if ($pages > 1): for ($p = 1; $p <= $pages; $p++): ?>
              <a class="page <?= ($p == $page ? 'active' : ''); ?>"
                href="index.php?action=projects&q=<?= urlencode($q); ?>&status=<?= urlencode($status); ?>&page=<?= $p; ?>">
                <?= $p; ?>
              </a>
          <?php endfor;
          endif; ?>
        </div>

      </div>
    </div>

  </main>

  <!-- toast -->
  <div id="toast" class="toast"></div>

  <!-- FOOTER -->
  <footer class="admin-footer">
    <div id="text-footer">© 2025 AI Lab Polinema</div>
  </footer>

  <script src="views/js/projects.js"></script>

</body>

</html>