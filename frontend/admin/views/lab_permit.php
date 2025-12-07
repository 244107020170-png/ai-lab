<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Permit Management</title>

    <!-- CSS -->
    <link rel="stylesheet" href="views/css/lab_permit.css">
</head>

<body>

<!-- BACKGROUND -->
<div class="bg-layer">
    <div class="bg-image"></div>
    <div class="bg-gradient"></div>
</div>

<!-- HEADER -->
<div class="admin-header">
    <div class="header-left">
        <img src="views/img/logo.png" class="admin-logo">
        <span class="admin-title">Lab Admin Page</span>
    </div>

    <nav class="header-right nav">
        <a href="index.php">Home</a>
        <a href="index.php?action=members">Members</a>
        <a href="index.php?action=projects">Projects</a>
        <a href="index.php?action=news">News</a>
    </nav>
</div>


<!-- PAGE CONTENT -->
<main class="container lp-container">

    <h1 class="lp-title">Lab Permit Management</h1>
    <p class="lp-desc">Review, approve, or reject lab usage permit submissions.</p>

    <!-- FILTER BAR -->
    <div class="lp-controls">
        <input type="text" placeholder="Search name..." class="lp-search" id="searchName">

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
    <div class="footer-left">© 2025 AI Lab Polinema</div>
    <div class="footer-right">Contact: <span>ailab@polinema.ac.id</span></div>
</footer>

<script src="views/js/lab_permit.js"></script>

</body>
</html>
