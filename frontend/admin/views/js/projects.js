// views/js/projects.js
document.addEventListener("DOMContentLoaded", () => {

  // auto apply filter when selecting status
  document.addEventListener("change", (e) => {
    if (e.target && e.target.id === "statusSelect") {
      const form = document.getElementById("searchForm");
      if (form) form.submit();
    }
  });

  // showToast helper
  function showToast(msg) {
    const t = document.getElementById("toast");
    if (!t) return;
    t.textContent = msg;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 2500);
  }
  window.showToast = showToast;


  // === GLOBAL EVENT LISTENER FOR DOTS + DELETE ===
  document.addEventListener("click", (e) => {

    // --- OPEN DOTS MENU ---
    const dotsBtn = e.target.closest(".dots-btn");
    if (dotsBtn) {
      e.stopPropagation();

      document.querySelectorAll(".dots-menu").forEach(m => m.classList.remove("show"));

      const id = dotsBtn.dataset.id;
      const menu = document.querySelector(`.dots-menu[data-id="${id}"]`);
      if (menu) menu.classList.add("show");

      return;
    }


    // --- DELETE BUTTON (FIXED) ---
    if (e.target.matches(".delete-btn")) {
      e.preventDefault();
      e.stopPropagation();

      const id = e.target.dataset.id;

      if (!confirm("Are you sure you want to delete this project?")) return;

      const body = new URLSearchParams();
      body.append("id", id);

      fetch("index.php?action=projects&op=delete", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body: body.toString(),
        credentials: "same-origin"
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const row = document.getElementById("row-" + id);
          if (row) {
            row.classList.add("row-fade-out");
            setTimeout(() => row.remove(), 350);
          }

          showToast("Deleted successfully");
        } else {
          showToast("Delete failed");
        }
      })
      .catch(err => {
        console.error("DELETE ERROR:", err);
        showToast("Error deleting");
      });

      return;
    }


    // --- CLOSE DOTS MENU WHEN CLICK OUTSIDE ---
    document.querySelectorAll(".dots-menu").forEach(m => m.classList.remove("show"));
  });



  // === REALTIME SEARCH ===
  const searchInput = document.getElementById("search-input");
  const suggestBox  = document.getElementById("suggest-box");
  let timer = null;

  if (searchInput && suggestBox) {
    searchInput.addEventListener("keyup", () => {
      clearTimeout(timer);
      const q = searchInput.value.trim();
      if (q.length < 1) { suggestBox.style.display = "none"; return; }

      timer = setTimeout(() => {
        fetch("index.php?action=projects&op=index&q=" + encodeURIComponent(q))
          .then(res => res.text())
          .then(html => {
            const doc = new DOMParser().parseFromString(html, "text/html");
            const rows = doc.querySelectorAll(".table-row");

            suggestBox.innerHTML = "";

            rows.forEach(r => {
              const titleEl = r.querySelector(".col.title");
              if (!titleEl) return;

              const item = document.createElement("div");
              item.className = "suggest-item";
              item.textContent = titleEl.innerText.trim();
              
              item.addEventListener("click", () => {
                searchInput.value = titleEl.innerText.trim();
                suggestBox.style.display = "none";
                const form = document.getElementById("searchForm");
                if (form) form.submit();
              });

              suggestBox.appendChild(item);
            });

            suggestBox.style.display = suggestBox.children.length ? "block" : "none";
          })
          .catch(err => {
            console.error(err);
            suggestBox.style.display = "none";
          });
      }, 250);
    });

    document.addEventListener("click", (e) => {
      if (!suggestBox.contains(e.target) && e.target !== searchInput) {
        suggestBox.style.display = "none";
      }
    });
  }

});
