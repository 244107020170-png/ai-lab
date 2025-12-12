<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Research | AILab</title>
<style>
    * {
        box-sizing: border-box;
    }
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
    background: #0b0f15;
    color: white;
    overflow-x: hidden;
}

/* BACKGROUND LAYER */
.bg-layer {
    position: fixed;
    inset: 0;
    z-index: -2;
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
.leBox {
    background: var(--glass-bg);
    border-radius: 11px;
    outline: 1px solid var(--glass-outline);
    backdrop-filter: blur(6px);
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
.main-container {
    width: 100%;
    max-width: 1200px;
    margin: 160px auto 80px auto;
    padding: 0 20px;

    animation: fadeUp 0.45s ease forwards;
}

/* Soft glass wrapper for page box */
.research-box {
    background: rgba(0,0,0,0.34);
    outline: 1px solid rgba(255,255,255,0.11);
    border-radius: 12px;
    padding: 26px 32px;
    backdrop-filter: blur(6px);
    margin-bottom: 26px;
}

/* Title */
.page-title {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 6px;
}
.page-sub {
    font-size: 14px;
    color: rgba(255,255,255,0.65);
}

/* Tabs */
.tabs-container {
    margin-top: 12px;
    display: flex;
    gap: 14px;
    background: rgba(0,0,0,0.34);
    border-radius: 50px;
    padding: 10px 16px;
    outline: 1px solid rgba(255,255,255,0.11);
    backdrop-filter: blur(6px);
}

.tab-btn {
    border: none;
    background: transparent;
    color: rgba(255,255,255,0.65);
    padding: 10px 20px;
    border-radius: 999px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
}

.tab-btn:hover {
    color: white;
}

.tab-active {
    background: linear-gradient(246deg, #03D3F1, #027A8B);
    color: #012 !important;
    box-shadow: 0 0 16px rgba(3,211,241,0.4);
}

/* Tab Content */
.tab-content {
    background: rgba(0,0,0,0.34);
    border-radius: 12px;
    outline: 1px solid rgba(255,255,255,0.11);
    padding: 26px 32px;
    margin-top: 26px;
    display: none;
    backdrop-filter: blur(6px);
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.35s ease, transform 0.35s ease;

    animation: fadeUp 0.45s ease forwards;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px);}
    to   { opacity: 1; transform: translateY(0); }
}

/* Form fields */
.tab-content input,
.tab-content textarea {
    width: 100%;
    margin-top: 6px;
    margin-bottom: 16px;
    padding: 14px 16px;
    border-radius: 10px;
    font-size: 15px;

    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    color: white;

    transition: border-color .25s, box-shadow .25s;
}

.tab-content input:focus,
.tab-content textarea:focus {
    border-color: #03D3F1;
    box-shadow: 0 0 8px rgba(3,211,241,0.3);
    outline: none;
}

label {
    font-size: 14px;
    font-weight: 600;
}

/* Add button */
.btn {
    padding: 10px 18px;
    background: linear-gradient(90deg, #03D3F1, #027A8B);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
    color: #012;
    transition: .25s;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Research item list */
.research-item {
    margin-top: 16px;
    padding: 14px;
    border-radius: 12px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.06);
}

/* Footer spacing fix */
.admin-footer {
    width: 100%;
    padding: 20px 0;
    margin-top: 60px;
    text-align: center;
    color: rgba(255,255,255,0.7);
    background: rgba(0,0,0,0.15);
    border-top: 1px solid rgba(255,255,255,0.1);
}

#text-footer {
    font-size: 16px;
    color: white;
}
/* === ANIMATIONS AREA === */

/* Card pop animation */
.project-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 14px;
    animation: cardPop .35s ease-out forwards;
    opacity: 0;
    transform: translateY(10px) scale(0.97);
    transition: transform .25s ease, box-shadow .25s ease;
}

@keyframes cardPop {
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.project-card:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 0 14px rgba(3,211,241,0.25);
    border-color: rgba(255,255,255,0.12);
}


/* Tab switching */

.tab-content.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
}


/* Add button interaction */
.btn-add {
    transition: transform .15s ease, box-shadow .25s ease;
}

.btn-add:active {
    transform: scale(0.97);
    box-shadow: 0 0 10px rgba(3,211,241,0.3);
}


/* Input glow */
.main-container input:focus,
.main-container textarea:focus {
    border-color: var(--accentA);
    box-shadow: 0 0 10px rgba(3,211,241,0.18);
    transform: scale(1.01);
}

/* Title glow line */
.research-box h2 {
    position: relative;
    display: inline-block;
    margin-bottom: 24px;
}

.research-box h2::after {
    content: "";
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 70%;
    height: 2px;
    background: linear-gradient(90deg, var(--accentA), transparent);
    border-radius: 50px;
    opacity: 0.7;
}

</style>
</head>
<body>

    <!-- HEADER / NAVBAR MEMBER -->
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
                <a href="index.php?action=member_profile" class="nav-item">My Profile</a>
                <a href="index.php?action=member_research" class="nav-item selected-navbar">My Research</a>
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

    <!-- MAIN CONTENT -->
<div class="main-container">

    <div class="research-box">
        <div class="page-title">My Research</div>
        <div class="page-sub">Manage all your research, publications, and academic contributions.</div>
    </div>

    <!-- Tabs -->
    <div class="tabs-container">
        <button class="tab-btn tab-active" data-tab="tab-projects">Research Projects</button>
        <button class="tab-btn" data-tab="tab-publications">Publications</button>
        <button class="tab-btn" data-tab="tab-ppm">PPM</button>
        <button class="tab-btn" data-tab="tab-backgrounds">Backgrounds</button>
        <button class="tab-btn" data-tab="tab-ips">Intectual Property</button>
        <button class="tab-btn" data-tab="tab-activities">Activities</button>
    </div>

    <!-- TAB CONTENT: PROJECTS -->
    <div id="tab-projects" class="tab-content active">

        <h3>Research Projects</h3>

        <label>Project Title</label>
        <input id="projectTitle" type="text" placeholder="Enter project title">

        <label>Description</label>
        <textarea id="projectDesc" placeholder="Brief description"></textarea>

        <label>Year</label>
        <input id="projectYear" type="number" placeholder="2025">

        <button id="btnAddProject" class="btn">Add Project</button>

        <h4>Your Projects</h4>
        <div id="projectList" class="project-card">No New Projects Yet.</div>

    </div>

    <!-- TAB CONTENT: PUBLICATIONS -->
<div id="tab-publications" class="tab-content">

    <h3>Publications</h3>

    <label>Publication Title</label>
    <input id="pubTitle" type="text" placeholder="Enter publication title">

    <label>Publisher</label>
    <input id="pubPublisher" type="text" placeholder="e.g. IEEE, Elsevier">

    <label>Year</label>
    <input id="pubYear" type="number" placeholder="2025">

    <label>Link (optional)</label>
    <input id="pubLink" type="url" placeholder="https://...">

    <button id="btnAddPub" class="btn">Add Publication</button>

    <h4 style="margin-top: 30px;">Your Publications</h4>
    <div id="pubList" class="project-card">Loading...</div>

</div>


    <!-- TAB CONTENT: PPM -->
<div id="tab-ppm" class="tab-content">

    <h3>PPM</h3>

    <label>PPM Title</label>
    <input id="ppmTitle" type="text" placeholder="Enter PPM title">

    <label>Year</label>
    <input id="ppmYear" type="number" placeholder="2025">

    <label>Description</label>
    <textarea id="ppmDesc" placeholder="Write a short description"></textarea>

    <button id="btnAddPPM" class="btn">Add PPM Entry</button>

    <h4 style="margin-top: 30px;">Your PPM Entries</h4>
    <div id="ppmList" class="project-card">Loading...</div>

</div>


    <!-- TAB CONTENT: BACKGROUNDS -->
<div id="tab-backgrounds" class="tab-content">

    <h3>Academic Backgrounds</h3>

    <label>Institute</label>
    <input id="bgInstitute" type="text" placeholder="e.g. Politeknik Negeri Malang">

    <label>Academic Title</label>
    <input id="bgAcademicTitle" type="text" placeholder="e.g. S.Kom, M.T.">

    <label>Year</label>
    <input id="bgYear" type="number" placeholder="2023">

    <label>Degree</label>
    <input id="bgDegree" type="text" placeholder="e.g. Bachelor, Master, Doctorate">

    <button class="btn" id="addBackgroundBtn">Add Background</button>

    <h4 style="margin-top: 30px;">Your Backgrounds</h4>
    <div id="backgroundList">
        <div class="project-card">No backgrounds added.</div>
    </div>

</div>

<!-- TAB CONTENT: IPS -->
<div id="tab-ips" class="tab-content">

    <h3>Intellectual Property</h3>

    <label>IP Title</label>
    <input id="ipsTitle" type="text" placeholder="e.g. Smart Flood Detection System">

    <label>Year</label>
    <input id="ipsYear" type="number" placeholder="2022">

    <label>Registration Number</label>
    <input id="ipsReg" type="text" placeholder="e.g. IDP00012345">

    <button id="addIPSBtn" class="btn">Add IPS</button>

    <h4 style="margin-top: 30px;">Your Intellectual Properties</h4>
    <div id="ipsList" class="project-card">No intellectual property records yet.</div>

</div>

<!-- TAB CONTENT: Activities -->
<div id="tab-activities" class="tab-content">

    <h3>Activities</h3>

    <label>Activity Title</label>
    <input id="actTitle" type="text" placeholder="e.g. AI Workshop at ITS">

    <label>Year</label>
    <input id="actYear" type="number" placeholder="2022">

    <label>Location</label>
    <input id="actLocation" type="text" placeholder="e.g. Surabaya">

    <button id="addActBtn" class="btn">Add Activity</button>

    <h4 style="margin-top: 30px;">Your Activities</h4>
    <div id="actList" class="project-card">No activities yet.</div>

</div>

</div>

    
    <footer class="admin-footer">
        <div id="text-footer">© 2025 AI Lab Polinema</div>
    </footer>

<script>
/* ==========================================================
   HELPER: FETCH API WRAPPER
========================================================== */
async function api(action, method = "GET", body = null) {
    const opts = { method, credentials: "same-origin" };

    if (body && !(body instanceof FormData)) {
        opts.headers = { "Content-Type": "application/x-www-form-urlencoded" };
        opts.body = new URLSearchParams(body).toString();
    } else if (body instanceof FormData) {
        opts.body = body;
    }

    const res = await fetch(`index.php?action=${action}`, opts);
    if (!res.ok) throw new Error("HTTP Error " + res.status);
    return res.json();
}

/* ==========================================================
   TAB SWITCHING
========================================================== */
document.querySelectorAll(".tab-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("tab-active"));
        document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));

        btn.classList.add("tab-active");
        document.getElementById(btn.dataset.tab).classList.add("active");
    });
});

/* ==========================================================
   ESCAPE HTML
========================================================== */
function escapeHtml(s) {
    if (!s) return "";
    return String(s).replace(/[&<>"']/g, c => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    }[c]));
}

/* ==========================================================
   1. PROJECTS
========================================================== */
const projectList = document.getElementById("projectList");

async function loadProjects() {
    projectList.innerHTML = "Loading...";
    try {
        const { projects } = await api("member_research_get_projects");
        if (!projects.length) {
            projectList.innerHTML = `<div class="project-card">No projects yet.</div>`;
        } else {
            projectList.innerHTML = projects.map(p => `
                <div class="project-card">
                    <strong>${escapeHtml(p.title)}</strong> <small>(${p.year})</small>
                    <div>${escapeHtml(p.description || "")}</div>

                    <button class="btn btn-del" data-id="${p.id}">Delete</button>
                </div>
            `).join("");

            projectList.querySelectorAll(".btn-del").forEach(btn => {
                btn.onclick = async () => {
                    if (!confirm("Delete project?")) return;
                    await api("member_research_delete_project", "POST", { id: btn.dataset.id });
                    loadProjects();
                };
            });
        }
    } catch (err) {
        projectList.innerHTML = "Error loading projects.";
    }
}

document.getElementById("btnAddProject").onclick = async () => {
    const title = projectTitle.value.trim();
    const year = projectYear.value.trim();
    const description = projectDesc.value.trim();

    if (!title) return alert("Title required");

    await api("member_research_add_project", "POST", { title, year, description });

    projectTitle.value = "";
    projectYear.value = "";
    projectDesc.value = "";

    loadProjects();
};

/* ==========================================================
   2. PUBLICATIONS
========================================================== */
const pubList = document.getElementById("pubList");

async function loadPublications() {
    pubList.innerHTML = "Loading...";
    try {
        const { publications } = await api("member_research_get_publications");

        if (!publications.length) {
            pubList.innerHTML = `<div class="project-card">No publications yet.</div>`;
        } else {
            pubList.innerHTML = publications.map(p => `
                <div class="project-card">
                    <strong>${escapeHtml(p.title)}</strong> <small>(${p.year})</small>
                    <div>${escapeHtml(p.publisher || "")}</div>
                    ${p.link ? `<a href="${escapeHtml(p.link)}" target="_blank">Open Link</a>` : ""}

                    <button class="btn btn-del" data-id="${p.id}">Delete</button>
                </div>
            `).join("");

            pubList.querySelectorAll(".btn-del").forEach(btn => {
                btn.onclick = async () => {
                    if (!confirm("Delete publication?")) return;
                    await api("member_research_delete_publication", "POST", { id: btn.dataset.id });
                    loadPublications();
                };
            });
        }
    } catch (err) {
        pubList.innerHTML = "Error loading publications.";
    }
}

document.getElementById("btnAddPub").onclick = async () => {
    const title = pubTitle.value.trim();
    const publisher = pubPublisher.value.trim();
    const year = pubYear.value.trim();
    const link = pubLink.value.trim();

    if (!title) return alert("Title required");

    await api("member_research_add_publication", "POST", {
        title, publisher, year, link
    });

    pubTitle.value = "";
    pubPublisher.value = "";
    pubYear.value = "";
    pubLink.value = "";

    loadPublications();
};

/* ==========================================================
   3. PPM
========================================================== */
const ppmList = document.getElementById("ppmList");

async function loadPPM() {
    ppmList.innerHTML = "Loading...";
    try {
        const { ppm } = await api("member_research_get_ppm");

        if (!ppm.length) {
            ppmList.innerHTML = `<div class="project-card">No PPM entries yet.</div>`;
        } else {
            ppmList.innerHTML = ppm.map(p => `
                <div class="project-card">
                    <strong>${escapeHtml(p.title)}</strong> <small>(${p.year})</small>
                    <div>${escapeHtml(p.description || "")}</div>

                    <button class="btn btn-del" data-id="${p.id}">Delete</button>
                </div>
            `).join("");

            ppmList.querySelectorAll(".btn-del").forEach(btn => {
                btn.onclick = async () => {
                    if (!confirm("Delete PPM entry?")) return;
                    await api("member_research_delete_ppm", "POST", { id: btn.dataset.id });
                    loadPPM();
                };
            });
        }
    } catch (err) {
        ppmList.innerHTML = "Error loading PPM entries.";
    }
}

document.getElementById("btnAddPPM").onclick = async () => {
    const title = ppmTitle.value.trim();
    const year = ppmYear.value.trim();
    const description = ppmDesc.value.trim();

    if (!title) return alert("Title required");

    await api("member_research_add_ppm", "POST", { title, year, description });

    ppmTitle.value = "";
    ppmYear.value = "";
    ppmDesc.value = "";

    loadPPM();
};

/* ==========================================================
   4. BACKGROUNDS
========================================================== */
const bgList = document.getElementById("backgroundList");

async function loadBackgrounds() {
    bgList.innerHTML = "Loading...";
    try {
        const { backgrounds } = await api("member_research_get_backgrounds");

        if (!backgrounds.length) {
            bgList.innerHTML = `<div class="project-card">No backgrounds added.</div>`;
        } else {
            bgList.innerHTML = backgrounds.map(b => `
                <div class="project-card">
                    <strong>${escapeHtml(b.institute)}</strong> <small>(${b.year})</small>
                    <div>${escapeHtml(b.academic_title)} — ${escapeHtml(b.degree)}</div>

                    <button class="btn btn-del" data-id="${b.id}">Delete</button>
                </div>
            `).join("");

            bgList.querySelectorAll(".btn-del").forEach(btn => {
                btn.onclick = async () => {
                    if (!confirm("Delete background?")) return;
                    await api("member_research_delete_background", "POST", { id: btn.dataset.id });
                    loadBackgrounds();
                };
            });
        }
    } catch (err) {
        bgList.innerHTML = "Error loading backgrounds.";
    }
}

document.getElementById("addBackgroundBtn").onclick = async () => {
    const institute = bgInstitute.value.trim();
    const academic_title = bgAcademicTitle.value.trim();
    const year = bgYear.value.trim();
    const degree = bgDegree.value.trim();

    if (!institute) return alert("Institute required");

    await api("member_research_add_background", "POST", {
        institute, academic_title, year, degree
    });

    bgInstitute.value = "";
    bgAcademicTitle.value = "";
    bgYear.value = "";
    bgDegree.value = "";

    loadBackgrounds();
};

/* ==========================================================
   5. INTELLECTUAL PROPERTY
========================================================== */
const ipsListElement = document.getElementById("ipsList");

async function loadIPS() {
    ipsListElement.innerHTML = "Loading...";
    try {
        const { ips } = await api("member_research_get_ips");

        if (!ips.length) {
            ipsListElement.innerHTML = `<div class="project-card">No IP records yet.</div>`;
        } else {
            ipsListElement.innerHTML = ips.map(i => `
                <div class="project-card">
                    <strong>${escapeHtml(i.title)}</strong> <small>(${i.year})</small>
                    <div>Reg: ${escapeHtml(i.registration_number || "")}</div>

                    <button class="btn btn-del" data-id="${i.id}">Delete</button>
                </div>
            `).join("");

            ipsListElement.querySelectorAll(".btn-del").forEach(btn => {
                btn.onclick = async () => {
                    if (!confirm("Delete IP entry?")) return;
                    await api("member_research_delete_ips", "POST", { id: btn.dataset.id });
                    loadIPS();
                };
            });
        }
    } catch (err) {
        ipsListElement.innerHTML = "Error loading IPS entries.";
    }
}

document.getElementById("addIPSBtn").onclick = async () => {
    const title = ipsTitle.value.trim();
    const year = ipsYear.value.trim();
    const reg_number = ipsReg.value.trim();

    if (!title) return alert("Title required");

    await api("member_research_add_ips", "POST", { title, year, reg_number });

    ipsTitle.value = "";
    ipsYear.value = "";
    ipsReg.value = "";

    loadIPS();
};

/* ==========================================================
   6. ACTIVITIES
========================================================== */
const actList = document.getElementById("actList");

async function loadActivities() {
    actList.innerHTML = "Loading...";
    try {
        const { activities } = await api("member_research_get_activities");

        if (!activities.length) {
            actList.innerHTML = `<div class="project-card">No activities yet.</div>`;
        } else {
            actList.innerHTML = activities.map(a => `
                <div class="project-card">
                    <strong>${escapeHtml(a.title)}</strong> <small>(${a.year})</small>
                    <div>${escapeHtml(a.location)}</div>

                    <button class="btn btn-del" data-id="${a.id}">Delete</button>
                </div>
            `).join("");

            actList.querySelectorAll(".btn-del").forEach(btn => {
                btn.onclick = async () => {
                    if (!confirm("Delete activity?")) return;
                    await api("member_research_delete_activity", "POST", { id: btn.dataset.id });
                    loadActivities();
                };
            });
        }
    } catch (err) {
        actList.innerHTML = "Error loading activities.";
    }
}

document.getElementById("addActBtn").onclick = async () => {
    const title = actTitle.value.trim();
    const year = actYear.value.trim();
    const location = actLocation.value.trim();

    if (!title) return alert("Title required");

    await api("member_research_add_activity", "POST", {
        title, year, location
    });

    actTitle.value = "";
    actYear.value = "";
    actLocation.value = "";

    loadActivities();
};

/* ==========================================================
   INITIAL PAGE LOAD
========================================================== */
(async function init() {
    await loadProjects();
    await loadPublications();
    await loadPPM();
    await loadBackgrounds();
    await loadIPS();
    await loadActivities();
})();
</script>

</body>
</html>