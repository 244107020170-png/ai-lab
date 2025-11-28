<?php
// variables passed from controller: $rows, $total, $page, $limit, $q, $status
// but to support direct include, define fallbacks:
$q = $_GET['q'] ?? ($q ?? '');
$status = $_GET['status'] ?? ($status ?? '');
$page = intval($_GET['page'] ?? ($page ?? 1));
$limit = 10;
$total = $total ?? 0;
$rows = $rows ?? [];
$pages = ceil($total / $limit);
function labelFor($dbStatus) {
    $map = ['approved'=>'Published','pending'=>'Progressing','rejected'=>'Cancelled'];
    return $map[$dbStatus] ?? ucfirst($dbStatus);
}
function colorDot($dbStatus) {
    $map = ['approved'=>'green','pending'=>'yellow','rejected'=>'red'];
    return $map[$dbStatus] ?? 'gray';
}
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
    <form id="searchForm" method="get" action="index.php">
      <input type="hidden" name="action" value="projects">
      <input type="text" name="q" placeholder="Search title..." value="<?php echo htmlspecialchars($q); ?>">
      <select name="status">
        <option value="">All status</option>
        <option value="approved" <?php if($status==='approved') echo 'selected'; ?>>Published</option>
        <option value="pending" <?php if($status==='pending') echo 'selected'; ?>>Progressing</option>
        <option value="rejected" <?php if($status==='rejected') echo 'selected'; ?>>Cancelled</option>
      </select>
      <button type="submit">Search</button>
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
        <div class="table-row">
          <div class="col id">PJ<?php echo str_pad($r['id'], 3, '0', STR_PAD_LEFT); ?></div>
          <div class="col title"><?php echo htmlspecialchars($r['title']); ?></div>
          <div class="col status">
            <span class="dot <?php echo colorDot($r['status']); ?>"></span>
            <span class="label"><?php echo labelFor($r['status']); ?></span>
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
        <a class="page <?php if($p==$page) echo 'active'; ?>" href="index.php?action=projects&q=<?php echo urlencode($q); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
      <?php endfor; endif; ?>
    </div>
  </div>
</main>

<!-- toast -->
<div id="toast" class="toast"></div>

<script src="views/js/projects.js"></script>
</body>
</html>
