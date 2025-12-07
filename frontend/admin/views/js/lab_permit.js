/* Handles modal, AJAX detail loading, approve & reject actions*/

// Modal elements
const modal = document.getElementById("lp-modal");
const detailBox = document.getElementById("detail-content");

// Toast helper
function showToast(msg, success = true) {
    const toast = document.createElement("div");
    toast.className = "toast-message";
    toast.innerText = msg;

    toast.style.position = "fixed";
    toast.style.left = "50%";
    toast.style.bottom = "20px";
    toast.style.transform = "translateX(-50%)";
    toast.style.padding = "12px 20px";
    toast.style.borderRadius = "10px";
    toast.style.color = "white";
    toast.style.fontSize = "15px";
    toast.style.zIndex = "9999";
    toast.style.background = success ? "#1c9c4d" : "#b42323";
    toast.style.opacity = "0";
    toast.style.transition = "0.35s ease";

    document.body.appendChild(toast);

    setTimeout(() => { toast.style.opacity = "1"; }, 50);
    setTimeout(() => { toast.style.opacity = "0"; }, 2500);
    setTimeout(() => { toast.remove(); }, 3000);
}

/* OPEN MODAL + LOAD PERMIT DETAILS */
function openPermitDetail(id) {
    modal.classList.add("show");

    // Load details from server (AJAX)
    fetch(`index.php?action=lab_permit&op=detail&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.error) {
                detailBox.innerHTML = "<p style='color:#f88'>Failed to load details.</p>";
                return;
            }

            // Populate modal content
            detailBox.innerHTML = `
                <div class="detail-line"><strong>Name:</strong> ${data.full_name}</div>
                <div class="detail-line"><strong>Program:</strong> ${data.study_program}</div>
                <div class="detail-line"><strong>Semester:</strong> ${data.semester}</div>
                <div class="detail-line"><strong>Email:</strong> ${data.email}</div>
                <div class="detail-line"><strong>Phone:</strong> ${data.phone}</div>
                <div class="detail-line"><strong>Reason:</strong><br> ${data.reason}</div>
                <div class="detail-line"><strong>Status:</strong> ${data.status}</div>
                <div class="detail-line"><strong>Submitted:</strong> ${data.submitted_at}</div>
            `;

            // Save selected ID for approve/reject
            modal.dataset.id = id;
        })
        .catch(() => {
            detailBox.innerHTML = "<p style='color:#f88'>Connection error.</p>";
        });
}

/* CLOSE MODAL */
function closePermitDetail() {
    modal.classList.remove("show");
    detailBox.innerHTML = "";
}


/* APPROVE PERMIT */
function approvePermit() {
    const id = modal.dataset.id;

    fetch("index.php?action=lab_permit&op=approve", {
        method: "POST",
        body: new URLSearchParams({ id })
    })
    .then(res => res.text())
    .then(() => {
        showToast("Permit approved", true);
        closePermitDetail();
        refreshRow(id, "accepted");
    })
    .catch(() => showToast("Failed to approve permit", false));
}


/* REJECT PERMIT (WITH PROMPT) */
function rejectPermit() {
    const id = modal.dataset.id;

    const reason = prompt("Enter rejection reason:");

    if (!reason) {
        showToast("Rejection cancelled", false);
        return;
    }

    fetch("index.php?action=lab_permit&op=reject", {
        method: "POST",
        body: new URLSearchParams({ id, reason })
    })
    .then(res => res.text())
    .then(() => {
        showToast("Permit rejected", true);
        closePermitDetail();
        refreshRow(id, "rejected");
    })
    .catch(() => showToast("Failed to reject permit", false));
}


/* UPDATE TABLE ROW WITHOUT RELOADING PAGE */
function refreshRow(id, newStatus) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;

    row.querySelector(".lp-dot").className = `lp-dot ${newStatus}`;
    row.querySelector(".status-label").innerText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

    // Optional: subtle animation
    row.style.background = "rgba(255,255,255,0.06)";
    setTimeout(() => { row.style.background = "transparent"; }, 600);
}
function deletePermit(id) {
    if (!confirm("Are you sure you want to delete this permit request?")) {
        return;
    }

    fetch("index.php?action=lab_permit&op=delete", {
        method: "POST",
        body: new URLSearchParams({ id })
    })
    .then(res => res.text())
    .then(resp => {
        showToast("Permit deleted", true);

        // Remove row without reload
        const row = document.querySelector(`button[onclick="openPermitDetail(${id})"]`).closest("tr");
        if (row) row.remove();
    })
    .catch(() => {
        showToast("Failed to delete permit", false);
    });
}

