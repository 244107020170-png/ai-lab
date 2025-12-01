document.addEventListener("DOMContentLoaded", () => {

  /* ===========================
     AUTO FILTER STATUS
  ============================ */
  document.addEventListener("change", (e) => {
    if (e.target.id === "statusSelect") {
      const form = document.getElementById("searchForm");
      if (form) form.submit();
    }
  });


  /* ===========================
     GLOBAL CLICK HANDLER
     (dots menu + delete)
  ============================ */
  document.addEventListener("click", (e) => {

    /* --- OPEN DOTS MENU --- */
    const btn = e.target.closest(".dots-btn");
    if (btn) {
      const id = btn.dataset.id;
      const menu = document.querySelector(`.dots-menu[data-id="${id}"]`);

      // Close all other menus
      document.querySelectorAll(".dots-menu").forEach(m => m.classList.remove("show"));

      if (menu) menu.classList.add("show");

      e.stopPropagation();
      return;
    }

    /* --- CLICK DELETE BUTTON --- */
    if (e.target.classList.contains("delete-btn")) {
      e.preventDefault();

      const id = e.target.dataset.id;
      if (!confirm("Delete this project?")) return;

      fetch("index.php?action=projects&op=delete", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + id
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            const row = document.getElementById("row-" + id);
            if (row) row.remove();
          }
        });

      return;
    }

    /* --- CLICK OUTSIDE → CLOSE MENUS --- */
    if (!e.target.closest(".dots-menu")) {
      document.querySelectorAll(".dots-menu").forEach(m => m.classList.remove("show"));
    }
  });


  /* ===========================
     TOAST
  ============================ */
  window.showToast = function (msg) {
    const t = document.getElementById("toast");
    if (!t) return;
    t.textContent = msg;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 2500);
  };


  /* ===========================
     REALTIME SEARCH (SUGGEST)
  ============================ */
  const searchInput = document.getElementById("search-input");
  const suggestBox = document.getElementById("suggest-box");
  let timer = null;

  if (searchInput && suggestBox) {

    searchInput.addEventListener("keyup", () => {
      clearTimeout(timer);
      const q = searchInput.value.trim();

      if (q.length < 1) {
        suggestBox.style.display = "none";
        return;
      }

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
                searchInput.value = item.textContent;
                suggestBox.style.display = "none";

                const form = document.getElementById("searchForm");
                if (form) form.submit();
              });

              suggestBox.appendChild(item);
            });

            suggestBox.style.display =
              suggestBox.children.length ? "block" : "none";
          })
          .catch(() => (suggestBox.style.display = "none"));
      }, 250);
    });

    /* --- CLOSE SUGGESTBOX WHEN CLICK OUTSIDE --- */
    document.addEventListener("click", (e) => {
      // ignore clicks on dots button OR menu
      if (e.target.closest(".dots-btn") || e.target.closest(".dots-menu")) {
        return;
      }

      if (!suggestBox.contains(e.target) && e.target !== searchInput) {
        suggestBox.style.display = "none";
      }
    });
  }

}); // END DOMContentLoaded
