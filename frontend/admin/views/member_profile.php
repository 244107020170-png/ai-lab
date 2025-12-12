<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Member Profile | AILab</title>

<style>
  :root {
    --glass-bg: rgba(0,0,0,0.34);
    --glass-outline: rgba(255,255,255,0.11);
    --accentA: #03D3F1;
    --accentB: #027A8B;
    --muted: rgba(255,255,255,0.65);
    --maxWidth: 1200px;
}
html {
    background: #0b0f15 !important;
}

body {
    background: transparent !important;
}

html, body {
    margin: 0;
    padding: 0;
    font-family: 'SF Pro Rounded', sans-serif;
    color: white;
    overflow-x: hidden;
}

/* BACKGROUND LAYER */
.bg-layer {
    position: fixed;
    inset: 0;
    z-index: -1;
    pointer-events: none;
}
.bg-image {
    position: absolute;
    inset: 0;
    background-image: url("views/img/background3.jpg");
    background-size: cover;
    background-position: center;
    opacity: 0.25;
}
.bg-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        rgba(15, 32, 39, 0.65),
        rgba(32, 58, 67, 0.65),
        rgba(44, 83, 100, 0.65)
    );
    z-index: -1;
}

/* HEADER */
.header-container {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);

    display: flex;
    justify-content: space-between;
    align-items: center;

    width: 90%;
    max-width: 1440px;

    z-index: 999;
}
.header-container .leBox {
    padding: 12px 22px !important;
    backdrop-filter: blur(6px);
}


/* Left header */
.header-left {
    border-radius: 90px !important;
    padding: 12px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-left img {
    width: 120px;
    transition: 0.25s ease;
}

.header-left .logo-text {
    font-size: 22px;
    color: rgba(255,255,255,0.7);
    font-family: 'SF Pro Rounded';
}

/* Right header (Navbar) */
.right-navbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.header-right {
    border-radius: 90px !important;
    padding: 12px 22px;
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Navbar Items */
.nav-item {
    display: inline-block; /* so padding applies */
    padding: 8px 18px;
    border-radius: 30px;
    cursor: pointer;
    color: #8EF1FF !important;
    font-size: 18px;
    font-family: 'SF Pro Rounded';
    transition: 0.25s ease;
    text-decoration: none !important;
}
.nav-item {
    transition: color .25s ease, transform .2s ease;
}

.nav-item:hover {
    transform: translateY(-2px);
    color: white !important;
}

/* Selected Navbar */
.selected-navbar {
    background: linear-gradient(246deg, #03D3F1 0%, #027A8B 100%) !important;
    color: white !important;
    padding: 8px 18px;
    border-radius: 30px;
    font-weight: 600;
    box-shadow: 0px 4px 30px rgba(0,0,0,0.3);
}
  /* main container */
.main-container {
    width: 100%;
    max-width: var(--maxWidth);
    margin: 140px auto 80px auto;
    padding: 0 20px;
    display: flex;
    flex-direction: column;
    gap: 26px;
}
/* leBox mengikuti dashboard */
.leBox {
    background: var(--glass-bg);
    border-radius: 11px;
    outline: 1px solid var(--glass-outline);
    backdrop-filter: blur(6px);
    padding: 28px 32px !important;
    margin-bottom: 26px; 
}
.leBox.big { padding: 26px 32px; }
.leBox.mid { padding: 20px 26px; }
.leBox.small { padding: 14px 20px; }
.leBox .hint {
    margin-top: 0 !important;       /* reset margin bawaan */
    padding-top: 6px !important;    /* jarak yang rapi */
    display: block;
}
.leBox {
    opacity: 0;
    transform: translateY(8px);
    animation: fadeUp 0.5s ease forwards;
}

@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

  /* page header */
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 11px;
}

.page-title {
    font-size: 26px;
    font-weight: 700;
}

.page-sub {
    font-size: 14px;
    color: var(--muted);
}


  /* layout columns */
.profile-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 24px;
}

@media (max-width: 1000px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 14px;
}

.row {
    display: flex;
    gap: 18px;
    margin-bottom: 14px;
}

.col {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

label {
    font-size: 14px;
    font-weight: 500;
    color: rgba(255,255,255,0.75);
}
  /* left panel fields */
  input, select, textarea {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.09);
    padding: 14px 16px;
    border-radius: 10px;
    color: white;
    outline: none;
    font-size: 15px;
}

input::placeholder,
textarea::placeholder {
    color: rgba(255,255,255,0.45);
}

textarea {
    min-height: 80px;
    resize: vertical;
}

input, select, textarea {
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}

input:focus, select:focus, textarea:focus {
    border-color: rgba(3, 211, 241, 0.6);
    box-shadow: 0 0 8px rgba(3, 211, 241, 0.25);
}

  /* research tags */
  .tags-input {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 8px;
    border-radius: 8px;
    background: rgba(255,255,255,0.02);
}

.tag {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,0.06);
    font-size: 13px;
}

.tag button {
    background: transparent;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
}
.tag {
    animation: popIn .25s ease forwards;
}

@keyframes popIn {
    0% { transform: scale(0.7); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

.btn {
    padding: 10px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
    border: none;
    background: linear-gradient(90deg, var(--accentA), var(--accentB));
    color: #012;
}

.btn.ghost {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.12);
    color: white;
}

.btn.small {
    padding: 6px 10px;
    font-size: 14px;
}

.btn.warn {
    background: #ffb86b;
    color: #081219;
}
.btn {
    transition: transform .2s ease, box-shadow .3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 12px rgba(3, 211, 241, 0.4);
}

.btn:active {
    transform: translateY(0);
}
  /* right sidebar / avatar */
  .sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.avatar-wrap {
    display: flex;
    justify-content: center;
}

.avatar {
    width: 120px;
    height: 120px;
    border-radius: 999px;
    overflow: hidden;
    border: 6px solid rgba(255,255,255,0.9);
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.avatar-controls {
    display: flex;
    gap: 14px;
    justify-content: center;
    margin-top: 14px;
    margin-bottom: 12px;

}
.avatar-controls .btn {
    border: 1px solid rgba(255,255,255,0.25);
}
.avatar {
    transition: transform .3s ease, box-shadow .3s ease;
}

.avatar:hover {
    transform: scale(1.03);
    box-shadow: 0 0 18px rgba(255,255,255,0.25);
}

.admin-footer {
    width: 100%;
    padding: 20px 0;
    margin-top: 40px;
    text-align: center;
    color: rgba(255,255,255,0.7);
    background: rgba(0,0,0,0.15);
    border-top: 1px solid rgba(255,255,255,0.1);
}

#text-footer {
    font-size: 16px;
    color: white;
}
.fade-in {
    opacity: 0;
    transform: translateY(10px);
    animation: fadeIn .6s forwards;
}

  /* responsive tweaks */
  @media (max-width:600px){
    .page-title{font-size:20px}
    .avatar{width:96px;height:96px}
  }

  /* small helpers */
  .muted {
    color:var(--muted)
  }
  .actions-row {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px !important;
}

  .error {
    color:#ff9999;
    font-size:13px
  }
  .notice {
    font-size:13px;
    color:rgba(255,255,255,0.7);
    margin-top:6px
  }
  .field-inline {
    display:flex;
    gap:8px;
    align-items:center
  }
  .divider {
    height:1px;
    background:rgba(255,255,255,0.03);
    margin:20px 0;
    border-radius:2px
  }
  .hint {
    font-size: 12px;
    color: rgba(255,255,255,0.55);
    margin-top: -4px;
}
@keyframes fadeIn {
    to {
        opacity: 1;
        transform: none;
    }
}
select option {
    background: rgba(0,0,0,0.85);
    color: #fff;
}

select option:hover,
select option:checked {
    background: rgba(255,255,255,0.1);
}

</style>

</head>
<body>
  <div class="header-container">

        <!-- LEFT (LOGO) -->
        <div class="leBox header-left">
            <a href="index.php?action=member_dashboard">
                <img src="views/img/logo.png" alt="Logo">
            </a>
        </div>

        <!-- RIGHT NAV -->
        <div class="right-navbar-container">
            <div class="leBox header-right">
                <a href="index.php?action=member_dashboard" class="nav-item">Dashboard</a>
                <a href="index.php?action=member_profile" class="nav-item selected-navbar">My Profile</a>
                <a href="index.php?action=member_research" class="nav-item">My Research</a>
            </div>

            <div class="leBox header-right">
                <a href="index.php?action=logout" class="nav-item">Logout</a>
            </div>
        </div>
    </div>

     <!-- BACKGROUND LAYER -->
    <div class="bg-layer" aria-hidden="true">
        <div class="bg-image"></div>
        <div class="bg-gradient"></div>
    </div>

  <!-- main -->
  <!-- main -->
<main class="main-container fade-in">

    <div class="leBox big page-header">
        <div>
            <div class="page-title">Edit Profile</div>
            <div class="page-sub">Update your official AI Lab profile information.</div>
        </div>
        <div class="muted">Profile Completion: <strong id="profileCompletion">--%</strong></div>
    </div>

    <div class="profile-grid">

        <!-- LEFT: FORM -->
        <section class="form-section">

            <!-- BASIC INFO -->
           <div class="leBox">
    <div class="section-title">Basic Information</div>

    <div class="row">
        <div class="col">
            <label>Full name</label>
            <input id="fullName" type="text" placeholder="e.g. Nasywa Qonita RH" />
        </div>

        <div class="col">
            <label>Email</label>
            <input id="email" type="email" disabled />
            <div class="hint">Email cannot be changed.</div>
        </div>
    </div>

    <div class="row">
    <div class="col">
        <label>Expertise (tags)</label>
        <div id="expertiseBox" class="tags-input" tabindex="0">
            <input id="expertiseInput" type="text" placeholder="Type expertise & press Enter"
                style="border:none;background:transparent;color:#fff;outline:none;min-width:140px">
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <label>Description</label>
        <textarea id="description" rows="4" placeholder="Short description about yourself"></textarea>
    </div>
</div>

            <!-- SOCIAL LINKS -->
            <div class="leBox">
                <div class="section-title">Social & Academic Links</div>

                <div class="row">
                    <div class="col">
                        <label>Google Scholar</label>
                        <input id="linkScholar" type="url" placeholder="https://scholar.google.com/..." />
                    </div>

                    <div class="col">
                        <label>ORCID</label>
                        <input id="linkOrcid" type="url" placeholder="https://orcid.org/..." />
                    </div>
                </div>

                <div class="row" style="margin-top:10px">
                    <div class="col">
                        <label>ResearchGate</label>
                        <input id="linkRG" type="url" placeholder="https://www.researchgate.net/..." />
                    </div>

                    <div class="col">
                        <label>LinkedIn</label>
                        <input id="linkLinkedIn" type="url" placeholder="https://www.linkedin.com/..." />
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button id="cancelBtn" class="btn ghost">Cancel</button>
                <button id="resetBtn" class="btn ghost">Reset</button>
                <button id="saveBtn" class="btn">Save Changes</button>
            </div>

        </section>

        <!-- RIGHT: AVATAR -->
        <aside class="sidebar">

            <div class="leBox">
                <div class="section-title">Profile Photo</div>

                <div class="avatar-wrap">
                    <div class="avatar" id="avatarContainer">
                        <img id="avatarImg" src="views/img/memberavatar.png" alt="avatar" />
                    </div>
                </div>

                <div class="avatar-controls">
                    <label class="btn ghost small" style="cursor:pointer">
                        <input id="avatarFile" type="file" accept="image/*" style="display:none" />
                        Upload
                    </label>
                    <button id="removeAvatarBtn" class="btn ghost small">Remove</button>
                </div>

                <div class="hint">Allowed: JPG/PNG up to 2MB.</div>
            </div>

            <div class="leBox">
                <div class="section-title">Profile Summary</div>
                <div class="notice">Make sure your links & expertise are complete.</div>
                <div style="height:12px"></div>
                <div class="muted">Saved at: <span id="savedAt">—</span></div>
            </div>

        </aside>

    </div>
</main>

    <footer class="admin-footer">
        <div id="text-footer">© 2025 AI Lab Polinema</div>
    </footer>

<script>
  let expertiseTags = [];
/* ---------- QUERY SHORTCUTS ---------- */
const qs = id => document.getElementById(id);

/* ---------- FIELDS ---------- */
const fields = {
    fullName: qs("fullName"),
    email: qs("email"),
    description: qs("description"),

    linkScholar: qs("linkScholar"),
    linkOrcid: qs("linkOrcid"),
    linkRG: qs("linkRG"),
    linkLinkedIn: qs("linkLinkedIn"),

    avatarFile: qs("avatarFile"),
    avatarImg: qs("avatarImg"),

    savedAt: qs("savedAt"),
    profileCompletion: qs("profileCompletion")
};

/* ---------- COMPLETION ---------- */
function updateCompletion() {
    let score = 0;
    const total = 5;

    if (fields.fullName.value.trim()) score++;
    if (!fields.avatarImg.src.includes("memberavatar.png")) score++;
    if (expertiseTags.length > 0) score++;
    if (fields.description.value.trim()) score++;
    if (fields.linkScholar.value.trim()) score++;

    fields.profileCompletion.textContent =
        Math.round((score / total) * 100) + "%";
}

/* ---------- AVATAR UPLOAD ---------- */
fields.avatarFile.addEventListener("change", e => {
    const f = e.target.files[0];
    if (!f) return;

    const r = new FileReader();
    r.onload = () => {
        fields.avatarImg.src = r.result;
        updateCompletion();
    };
    r.readAsDataURL(f);
});

qs("removeAvatarBtn").addEventListener("click", () => {
    if (confirm("Remove photo?")) {
        fields.avatarImg.src = "views/img/memberavatar.png";
        updateCompletion();
    }
});

/* ---------- SAVE BUTTON ---------- */
qs("saveBtn").addEventListener("click", () => {
    const fd = new FormData();

    fd.append("fullName", fields.fullName.value);
    fd.append("expertise", expertiseTags.join(","));
    fd.append("description", fields.description.value);

    fd.append("linkScholar", fields.linkScholar.value);
    fd.append("linkOrcid", fields.linkOrcid.value);
    fd.append("linkRG", fields.linkRG.value);
    fd.append("linkLinkedIn", fields.linkLinkedIn.value);

    if (fields.avatarFile.files[0]) {
        fd.append("avatarFile", fields.avatarFile.files[0]);
    }

    fetch("index.php?action=member_profile_update", {
        method: "POST",
        body: fd
    }).then(() => {
        alert("Profile updated");
        location.reload();
    });
});
function renderExpertiseTags(tags) {
    expertiseTags = tags.slice();
    const box = qs("expertiseBox");

    // remove old tags
    box.querySelectorAll(".tag").forEach(t => t.remove());

    tags.forEach((tag, i) => {
        const span = document.createElement("span");
        span.className = "tag";
        span.innerHTML = `${tag} <button data-i="${i}">×</button>`;
        box.insertBefore(span, qs("expertiseInput"));

        span.querySelector("button").addEventListener("click", () => {
            expertiseTags.splice(i, 1);
            renderExpertiseTags(expertiseTags);
            updateCompletion();
        });
    });

    updateCompletion();
}
qs("expertiseInput").addEventListener("keyup", e => {
    if (e.key === "Enter" || e.key === ",") {
        const val = qs("expertiseInput").value.replace(",", "").trim();
        if (val) {
            expertiseTags.push(val);
            renderExpertiseTags(expertiseTags);
        }
        qs("expertiseInput").value = "";
    }
});

/* ---------- INIT: LOAD PROFILE ---------- */
async function init() {
    const res = await fetch("index.php?action=member_profile_api");
    const data = await res.json();

    fields.fullName.value = data.full_name ?? "";
    fields.email.value = data.email ?? "";
    if (data.expertise) {
    renderExpertiseTags(data.expertise.split(",").map(t => t.trim()));
}
    fields.description.value = data.description ?? "";

    fields.linkScholar.value = data.scholar ?? "";
    fields.linkOrcid.value = data.orcid ?? "";
    fields.linkRG.value = data.researchgate ?? "";
    fields.linkLinkedIn.value = data.linkedin ?? "";

    if (data.photo) {
        fields.avatarImg.src = "uploads/members/" + data.photo;
    }

    updateCompletion();
}

init();

</script>

</body>
</html>
