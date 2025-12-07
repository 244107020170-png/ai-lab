<div class="lp-container">

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

  <!-- TABLE -->
  <div class="lp-table-card">
    <table class="lp-table">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Program</th>
        <th>Status</th>
        <th>Action</th>
      </tr>

      <?php foreach($rows as $r): ?>
      <tr>
        <td>LP<?= str_pad($r['id'],3,'0',STR_PAD_LEFT) ?></td>
        <td><?= htmlspecialchars($r['fullname']) ?></td>
        <td><?= htmlspecialchars($r['program']) ?></td>

        <td>
            <span class="lp-dot <?= $r['status'] ?>"></span>
            <?= ucfirst($r['status']) ?>
        </td>

        <td>
            <button class="lp-view-btn"
                    onclick="openPermitDetail(<?= $r['id'] ?>)">View</button>
        </td>
      </tr>
      <?php endforeach; ?>

    </table>
  </div>
</div>


<!-- MODAL -->
<div id="lp-modal" class="lp-modal">
  <div class="lp-modal-box">

    <h2>Permit Details</h2>

    <div id="detail-content">
        <!-- Filled by AJAX later -->
    </div>

    <div class="lp-actions">
        <button class="lp-accept" onclick="approvePermit()">Accept</button>
        <button class="lp-reject" onclick="rejectPermit()">Reject</button>
        <button class="lp-close" onclick="closePermitDetail()">Close</button>
    </div>

  </div>
</div>
