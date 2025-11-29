document.addEventListener("DOMContentLoaded", () => {

  // auto apply filter when selecting status (inside DOM ready)
  document.addEventListener("change", function(e){
      if (e.target && e.target.id === "statusSelect") {
          const form = document.getElementById("searchForm");
          if (form) form.submit();
      }
  });

function showToast(msg){
  const t = document.getElementById("toast");
  t.innerText = msg;
  t.classList.add("show");
  setTimeout(()=> t.classList.remove("show"), 2500);
}

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
