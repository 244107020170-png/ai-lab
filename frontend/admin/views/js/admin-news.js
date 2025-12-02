/* ======================================================
   NEWS LIST — SEARCH + SORT + FILTER + DELETE MODAL
====================================================== */

const searchInput = document.getElementById("searchInput");
const rows = Array.from(document.querySelectorAll(".project-row"));
const pagination = document.querySelector(".pagination");
const categoryFilter = document.getElementById("categoryFilter");
const sortBtn = document.getElementById("sortBtn");
const tableBody = document.getElementById("tableBody");

const modal = document.getElementById("deleteModal");
const confirmDelete = document.getElementById("confirmDelete");
const cancelDelete = document.getElementById("cancelDelete");

let deleteID = null;

/* ======================================================
   HELPER — REFRESH TABLE VIEW
====================================================== */
function applyFilters() {
  const q = searchInput.value.toLowerCase().trim();
  const selectedCat = categoryFilter.value.toLowerCase();
  let visible = 0;

  rows.forEach(row => {
    const title = row.children[1].innerText.toLowerCase();
    const category = row.children[2].innerText.toLowerCase();

    let match = true;

    if (!title.includes(q)) match = false;
    if (selectedCat && selectedCat !== category) match = false;

    row.style.display = match ? "grid" : "none";
    if (match) visible++;
  });

  pagination.style.display = q !== "" || selectedCat !== "" ? "none" : "flex";
}

/* ======================================================
   SEARCH
====================================================== */
searchInput?.addEventListener("input", applyFilters);

/* ======================================================
   FILTER CATEGORY
====================================================== */
categoryFilter?.addEventListener("change", applyFilters);

/* ======================================================
   SORT SYSTEM
====================================================== */
sortBtn?.addEventListener("click", () => {
  const choice = prompt(
    "Choose sort:\n1. Title A-Z\n2. Category A-Z\n3. Newest First\n4. Oldest First\n5. Status main→none"
  );

  if (!choice) return;

  let sortFn = null;

  switch (choice) {
    case "1": // title A-Z
      sortFn = (a, b) =>
        a.children[1].innerText.localeCompare(b.children[1].innerText);
      break;

    case "2": // category A-Z
      sortFn = (a, b) =>
        a.children[2].innerText.localeCompare(b.children[2].innerText);
      break;

    case "3": // newest (ID numeric only!)
      sortFn = (a, b) =>
        Number(b.children[0].innerText) - Number(a.children[0].innerText);
      break;

    case "4": // oldest
      sortFn = (a, b) =>
        Number(a.children[0].innerText) - Number(b.children[0].innerText);
      break;

    case "5": // main → none
      sortFn = (a, b) => {
        const va = a.children[3].innerText === "main" ? 0 : 1;
        const vb = b.children[3].innerText === "main" ? 0 : 1;
        return va - vb;
      };
      break;

    default:
      return;
  }

  rows.sort(sortFn);
  rows.forEach(r => tableBody.appendChild(r));
});

/* ======================================================
   DELETE — WITH MODAL
====================================================== */
document.querySelectorAll(".delete-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    deleteID = btn.dataset.id;
    modal.style.display = "flex";
  });
});

cancelDelete?.addEventListener("click", () => {
  modal.style.display = "none";
});

confirmDelete?.addEventListener("click", () => {
  fetch("index.php?action=news_delete", {
    method: "POST",
    body: new URLSearchParams({ id: deleteID })
  })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        alert(res.msg || "Delete failed");
      }
    });

  modal.style.display = "none";
});
