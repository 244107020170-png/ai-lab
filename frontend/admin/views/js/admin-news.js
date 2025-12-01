const projects = [
  { id: "PJ001", title: "Agriculture Database-Based System", status: "Main" },
  { id: "PJ002", title: "Automated Cyber Security Maturity Assessment (AMATI)", status: "Main" },
  { id: "PJ003", title: "Implementation of Smart Adaptive Learning System (SEALS)", status: "None" },
  { id: "PJ004", title: "Crowdfunding Activity with CrowdEquiChain", status: "Main" },
  { id: "PJ005", title: "The First Ever Dinosaur Revival", status: "None" },
  { id: "PJ006", title: "Insert Project Here cuz I Ran Out of Ideas", status: "None" },
  { id: "PJ007", title: "Polinema Siakad Remaster :3", status: "None" },
  { id: "PJ008", title: "Also the SLC", status: "None" },
  { id: "PJ009", title: "LMSSLC as well", status: "None" }
];

function renderTable(list) {
  const body = document.getElementById("tableBody");
  body.innerHTML = "";

  list.forEach(item => {
    const row = document.createElement("div");
    row.className = "project-row";

    const dotColor = item.status === "Main" ? "#27C840" : "#404040";

    row.innerHTML = `
      <div>${item.id}</div>
      <div>${item.title}</div>
      <div style="display:flex; align-items:center; gap:6px;">
        <div class="status-dot" style="background:${dotColor};"></div>
        <span>${item.status}</span>
      </div>
      <div style="text-align:center; opacity:0.5;">...</div>
    `;

    body.appendChild(row);
  });
}

renderTable(projects);

/* SEARCH */
document.getElementById("searchInput").addEventListener("input", e => {
  const val = e.target.value.toLowerCase();
  const filtered = projects.filter(p =>
    p.id.toLowerCase().includes(val) ||
    p.title.toLowerCase().includes(val)
  );
  renderTable(filtered);
});
