// admin-news.js (FINAL CLEAN VERSION)

(() => {
  // Elements
  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");
  const sortSelect = document.getElementById("sortSelect");
  const tableBody = document.getElementById("tableBody");
  const pagination = document.querySelector(".pagination");
  const deleteModal = document.getElementById("deleteModal");
  const confirmDelete = document.getElementById("confirmDelete");
  const cancelDelete = document.getElementById("cancelDelete");

  if (!tableBody) return;

  const getRows = () => Array.from(tableBody.querySelectorAll(".project-row"));

  function rowValues(row) {
    const cols = row.children;
    return {
      id: cols[0]?.innerText.trim() || "",
      title: cols[1]?.innerText.trim() || "",
      category: cols[2]?.innerText.trim() || "",
      status: cols[3]?.innerText.trim().toLowerCase() || "",
    };
  }

  function updatePaginationVisibility(active) {
    if (pagination) pagination.style.display = active ? "none" : "flex";
  }

  function applyFiltersAndSort() {
    const q = searchInput?.value.toLowerCase().trim() || "";
    const selectedCat = categoryFilter?.value || "";
    const rows = getRows();

    rows.forEach((row) => {
        const { title, id, category } = rowValues(row);
        let visible = true;

        if (q && !(title.toLowerCase().includes(q) || id.toLowerCase().includes(q)))
            visible = false;

        if (visible && selectedCat && category !== selectedCat)
            visible = false;

        row.style.display = visible ? "grid" : "none";
    });

    // Animasi soft 
    rows.forEach(row => {
        if (row.style.display !== "none") {
            row.classList.add("soft-appear");
        }
    });

    applySort();
    updatePaginationVisibility(q || selectedCat);
}

  function applySort() {
    if (!sortSelect) return;

    const mode = sortSelect.value;
    if (!mode) return;

    const rows = getRows().filter((r) => r.style.display !== "none");

    const compMap = {
      title_az: (a, b) => rowValues(a).title.localeCompare(rowValues(b).title),
      category_az: (a, b) =>
        rowValues(a).category.localeCompare(rowValues(b).category),
      newest: (a, b) => Number(rowValues(b).id) - Number(rowValues(a).id),
      oldest: (a, b) => Number(rowValues(a).id) - Number(rowValues(b).id),
      status_main_first: (a, b) => {
        const sa = rowValues(a).status === "main" ? 0 : 1;
        const sb = rowValues(b).status === "main" ? 0 : 1;
        return sa - sb;
      },
    };

    const comp = compMap[mode];
    if (!comp) return;

    const sorted = rows.sort(comp);
    sorted.forEach((r) => tableBody.appendChild(r));
  }

  function debounce(fn, ms = 200) {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), ms);
    };
  }

  function renderResults(results) {
    const container = document.querySelector(".news-list");
    container.innerHTML = "";

    results.forEach(item => {
        const row = document.createElement("div");
        row.classList.add("news-item", "soft-appear");
        row.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.category}</p>
            <p>${item.date}</p>
        `;
        container.appendChild(row);
    });
}

  if (searchInput)
    searchInput.addEventListener("input", debounce(applyFiltersAndSort, 140));

  if (categoryFilter)
    categoryFilter.addEventListener("change", applyFiltersAndSort);

  if (sortSelect)
    sortSelect.addEventListener("change", applyFiltersAndSort);

  // DELETE SYSTEM
  let deleteID = null;

  function attachDeleteButtons() {
    document.querySelectorAll(".delete-btn").forEach((btn) => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = "1";

      btn.addEventListener("click", () => {
        deleteID = btn.dataset.id;
        deleteModal.style.display = "flex";
      });
    });
  }

  async function doDelete(id) {
    try {
      const res = await fetch("index.php?action=news_delete", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ id }),
      });

      const json = await res.json();

      if (json.success) {
        const row = getRows().find((r) => r.children[0].innerText == id);
        if (row) row.remove();
        applyFiltersAndSort();
      } else {
        alert(json.msg || "Delete failed");
      }
    } catch (err) {
      console.error(err);
      alert("Network error");
    }
  }

  confirmDelete?.addEventListener("click", () => {
    if (deleteID) doDelete(deleteID);
    deleteModal.style.display = "none";
  });

  cancelDelete?.addEventListener("click", () => {
    deleteModal.style.display = "none";
    deleteID = null;
  });

  attachDeleteButtons();

  const obs = new MutationObserver(attachDeleteButtons);
  obs.observe(tableBody, { childList: true });

  applyFiltersAndSort();

  window.__adminNews = { applyFiltersAndSort, applySort };
})();