//   projects.js — FINAL CLEAN

document.addEventListener("DOMContentLoaded", () => {

//       DELETE PROJECT (AJAX)
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const id = this.dataset.id;
            if (!id) return;

            if (!confirm("Are you sure you want to delete this project?")) return;

            fetch("index.php?action=projects&op=delete", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + id
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast("Successfully Deleted!");

                    const row = document.querySelector(`#row-${id}`);
                    if (row) row.remove();
                } else {
                    alert("Delete failed.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Delete failed (network error)");
            });
        });
    });


//       DOT MENU (…) TOGGLE
    document.querySelectorAll(".dots-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();

            // hide semua menu dulu
            document.querySelectorAll(".dots-menu").forEach(m => m.style.display = "none");

            const id = this.dataset.id;
            const menu = document.querySelector(`.dots-menu[data-id="${id}"]`);
            if (menu) menu.style.display = "block";
        });
    });

    // Klik luar -> hide dots menu
    document.addEventListener("click", () => {
        document.querySelectorAll(".dots-menu").forEach(m => m.style.display = "none");
    });

    // Jangan close dots menu saat klik menu-nya
    document.querySelectorAll(".action-btn").forEach(btn => {
        btn.addEventListener("click", e => {
            e.stopPropagation();
        });
    });


//       TOAST
    window.showToast = function (msg) {
        const t = document.getElementById("toast");
        if (!t) return;

        t.textContent = msg;
        t.classList.add("show");

        setTimeout(() => {
            t.classList.remove("show");
        }, 2500);
    };


//       REALTIME SEARCH (AUTO-SUGGEST)
    const searchInput = document.getElementById("search-input");
    const suggestBox = document.getElementById("suggest-box");
    let timer = null;

    if (searchInput) {
        searchInput.addEventListener("keyup", () => {
            clearTimeout(timer);

            const q = searchInput.value.trim();
            if (q.length < 1) {
                suggestBox.style.display = "none";
                return;
            }

            timer = setTimeout(() => {
                fetch(`index.php?action=projects&op=index&q=${q}`)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, "text/html");
                        const rows = doc.querySelectorAll("tbody tr");

                        suggestBox.innerHTML = "";
                        rows.forEach(r => {
                            const title = r.querySelector("td:nth-child(2)").innerText;
                            suggestBox.innerHTML += `<div class="suggest-item">${title}</div>`;
                        });

                        suggestBox.style.display = "block";
                    });
            }, 300);
        });
    }

});
