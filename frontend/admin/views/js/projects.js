document.addEventListener("DOMContentLoaded", () => {

  // auto apply filter when selecting status (inside DOM ready)
  document.addEventListener("change", function(e){
      if (e.target && e.target.id === "statusSelect") {
          const form = document.getElementById("searchForm");
          if (form) form.submit();
      }
  });

   //  DELETE PROJECT (AJAX)
  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();

      const id = btn.dataset.id;
      if (!confirm("Are you sure you want to delete this project?")) return;

      fetch("index.php?action=projects&op=delete", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + id
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById("row-" + id).remove();
          showToast("Project deleted successfully");
        } else {
          showToast("Delete failed");
        }
      })
      .catch(err => {
        console.error(err);
        showToast("Error deleting");
      });
    });
  });

function showToast(msg){
  const t = document.getElementById("toast");
  t.innerText = msg;
  t.classList.add("show");
  setTimeout(()=> t.classList.remove("show"), 2500);
}

    // DOT MENU (…) TOGGLE
  document.querySelectorAll(".dots-btn").forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      // hide all menus
      document.querySelectorAll(".dots-menu").forEach(m => m.style.display = "none");
      const id = this.dataset.id;
      const menu = document.querySelector(`.dots-menu[data-id="${id}"]`);
      if (menu) menu.style.display = "block";
    });
  });

  // close menus when clicking outside
  document.addEventListener("click", () => {
    document.querySelectorAll(".dots-menu").forEach(m => m.style.display = "none");
  });

  // prevent menu close when clicking inside menu (if you have .action-btn inside menu)
  document.querySelectorAll(".dots-menu").forEach(menu => {
    menu.addEventListener("click", (e) => e.stopPropagation());
  });


    // TOAST
  window.showToast = function (msg) {
    const t = document.getElementById("toast");
    if (!t) return;
    t.textContent = msg;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 2500);
  };


    // REALTIME SEARCH (AUTO-SUGGEST)
  const searchInput = document.getElementById("search-input");
  const suggestBox  = document.getElementById("suggest-box");
  let timer = null;

  if (searchInput && suggestBox) {
    // when user types
    searchInput.addEventListener("keyup", () => {
      clearTimeout(timer);
      const q = searchInput.value.trim();
      if (q.length < 1) {
        suggestBox.style.display = "none";
        return;
      }

      timer = setTimeout(() => {
        // request server-side rendered list filtered by q
        fetch("index.php?action=projects&op=index&q=" + encodeURIComponent(q))
          .then(res => res.text())
          .then(html => {
            // parse returned HTML and extract rows
            const doc = new DOMParser().parseFromString(html, "text/html");
            const rows = doc.querySelectorAll(".table-row");
            suggestBox.innerHTML = "";
            rows.forEach(r => {
              const titleEl = r.querySelector(".col.title");
              if (!titleEl) return;
              const title = titleEl.innerText.trim();
              const idAttr = r.id || "";
              // each suggestion clickable
              const item = document.createElement("div");
              item.className = "suggest-item";
              item.textContent = title;
              item.dataset.rowId = idAttr; // e.g. row-12
              // when click suggestion -> fill input and submit form
              item.addEventListener("click", () => {
                searchInput.value = title;
                suggestBox.style.display = "none";
                // submit the search form (which also includes status select)
                const form = document.getElementById("searchForm");
                if (form) form.submit();
              });
              suggestBox.appendChild(item);
            });

            if (suggestBox.children.length) {
              suggestBox.style.display = "block";
            } else {
              suggestBox.style.display = "none";
            }
          })
          .catch(err => {
            console.error(err);
            suggestBox.style.display = "none";
          });
      }, 250);
    });

    // hide suggestion on outside click
    document.addEventListener("click", (e) => {
      if (!suggestBox.contains(e.target) && e.target !== searchInput) {
        suggestBox.style.display = "none";
      }
    });
  }

}); // end DOMContentLoaded
