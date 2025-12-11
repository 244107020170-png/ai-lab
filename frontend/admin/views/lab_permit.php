<?php if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Permit Management</title>

    <!-- CSS -->
    <link rel="stylesheet" href="views/css/lab_permit.css">
    <link rel="stylesheet" href="views/css/anims.css">
</head>

<body>

<!-- BACKGROUND -->
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
        <a href="index.php?action=members" class="nav-item">Members</a>
        <a href="index.php?action=projects" class="nav-item">Projects</a>
        <a href="index.php?action=news" class="nav-item">News</a>
      </div>

      <div class="header-box header-right">
        <a href="index.php?action=logout" class="nav-item">Logout</a>
      </div>
    </div>
  </div>
  <!-- /.Header -->


<!-- PAGE CONTENT -->
<main class="container lp-container">

    <h1 class="lp-title">Lab Permit Management</h1>
    <p class="lp-desc">Review, approve, or reject lab usage permit submissions.</p>

    <!-- FILTER BAR -->
    <div class="lp-controls">
<div class="lp-search-box">
    <input type="text" class="lp-search-input" placeholder="Search name..." />
</div>
        <select class="lp-select" id="filterStatus">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <!-- TABLE CARD -->
    <div class="lp-table-card">
        <table class="lp-table">
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 25%;">Name</th>
                <th style="width: 20%;">Study Program</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;">Submitted</th>
                <th style="width: 15%;">Action</th>
            </tr>

            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td>LP<?= str_pad($r['id'], 3, '0', STR_PAD_LEFT) ?></td>

                        <td><?= htmlspecialchars($r['full_name']) ?></td>

                        <td><?= htmlspecialchars($r['study_program']) ?></td>

                        <td>
                            <span class="lp-dot <?= $r['status'] ?>"></span>
                            <?= ucfirst($r['status']) ?>
                        </td>

                        <td><?= date("Y-m-d", strtotime($r['submitted_at'])) ?></td>

                        <td>
                            <button class="lp-view-btn" onclick="openPermitDetail(<?= $r['id'] ?>)">View</button>
                            <button class="lp-delete-btn" onclick="deletePermit(<?= $r['id'] ?>)">
                                🗑️
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="color:#9fb3b8; padding:20px; text-align:center;">
                        No permit requests found.
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</main>


<!-- MODAL -->
<div id="lp-modal" class="lp-modal">
    <div class="lp-modal-box">

        <h2>Permit Details</h2>

        <div id="detail-content">
            <!-- Filled dynamically using AJAX -->
        </div>

        <div class="lp-actions">
            <button class="lp-accept" onclick="approvePermit()">Accept</button>
            <button class="lp-reject" onclick="rejectPermit()">Reject</button>
            <button class="lp-close" onclick="closePermitDetail()">Close</button>
        </div>

    </div>
</div>


<!-- FOOTER -->
<footer class="admin-footer">
  <div id="text-footer">© 2025 AI Lab Polinema</div>
</footer>

<script src="views/js/lab_permit.js"></script>

</body>
</html>
