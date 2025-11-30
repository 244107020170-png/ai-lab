<?php
// STATUS MAPPING (FINAL)
function statusUI($db) {
    $map = [
        "published" => "Published",
        "progress"  => "Progressing",
        "cancelled" => "Cancelled"
    ];
    return $map[$db] ?? ucfirst($db);
}

function statusColor($db) {
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
$pages = ($total>0) ? ceil($total / $limit) : 1;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Projects - Admin</title>
<link rel="stylesheet" href="views/css/projects.css">
</head>
<body>

<div class="admin-header">
  <div class="logo">Lab Admin Page</div>
  <nav class="nav">
    <a href="index.php">Home</a>
    <a href="index.php?action=members">Members</a>
    <a class="active" href="index.php?action=projects">Projects</a>
    <a href="index.php?action=news">News</a>
  </nav>
</div>

<main class="container">
  <h1>Project Management</h1>
  <p class="lead">Manage the projects that will be shown in the website.</p>

  <div class="controls">
    <!-- SEARCH + FILTER IN ONE FORM -->
    <form id="searchForm" method="get" action="index.php" style="display:flex;gap:12px;align-items:center;">
      <input type="hidden" name="action" value="projects">

      <div class="search-container" style="flex:1;max-width:560px;">
        <input type="text" id="search-input" name="q" placeholder="Search title..." value="<?php echo htmlspecialchars($q); ?>">
        <div id="suggest-box" class="suggest-box"></div>
      </div>

      <select name="status" id="statusSelect">
    <option value="">All status</option>
<option value="published" <?= ($status=='published' ? 'selected' : '') ?>>Published</option>
<option value="progress"  <?= ($status=='progress'  ? 'selected' : '') ?>>Progressing</option>
<option value="cancelled" <?= ($status=='cancelled' ? 'selected' : '') ?>>Cancelled</option>
      </select>
    </form>

    <div class="right-controls">
      <a class="btn add" href="index.php?action=projects&op=create">+ Add Projects</a>
    </div>
  </div>

  <div class="table-card">
    <div class="table-head">
      <div class="col id">ID</div>
      <div class="col title">Title</div>
      <div class="col status">Status</div>
      <div class="col actions">Actions</div>
    </div>

    <div class="table-body">
      <?php if(empty($rows)): ?>
        <div class="no-data">No projects found.</div>
      <?php else: foreach($rows as $r): ?>
        
        <div class="table-row" id="row-<?php echo $r['id']; ?>">
          <div class="col id">PJ<?php echo str_pad($r['id'], 3, '0', STR_PAD_LEFT); ?></div>
          <div class="col title"><?php echo htmlspecialchars($r['title']); ?></div>
          <div class="col status">
            <span class="dot <?php echo statusColor($r['status']); ?>"></span>
            <span class="label"><?php echo statusUI($r['status']); ?></span>
          </div>
          <div class="col actions">
            <button class="dots-btn" data-id="<?php echo $r['id']; ?>">…</button>
            <div class="dots-menu" data-id="<?php echo $r['id']; ?>">
              <a href="index.php?action=projects&op=edit&id=<?php echo $r['id']; ?>">Edit</a>
              <a href="#" class="delete-btn" data-id="<?php echo $r['id']; ?>">Delete</a>
            </div>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>

    <!-- pagination -->
    <div class="pagination">
      <?php if($pages > 1): for($p=1;$p<=$pages;$p++): ?>
        <a class="page <?php if($p==$page) echo 'active'; ?>"
           href="index.php?action=projects&q=<?php echo urlencode($q); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $p; ?>">
           <?php echo $p; ?>
        </a>
      <?php endfor; endif; ?>
    </div>

  </div>

</main>

<!-- toast -->
<div id="toast" class="toast"></div>

<script src="views/js/projects.js"></script>




</body>
</html>
