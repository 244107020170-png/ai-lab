// admin-news.js (dynamic version)

(() => {
  // Elements
  const searchInput = document.getElementById('searchInput');
  const categoryFilter = document.getElementById('categoryFilter');    // <select> with categories, or null
  const sortSelect = document.getElementById('sortSelect');            // <select> for sort (preferred)
  const tableBody = document.getElementById('tableBody');
  const pagination = document.querySelector('.pagination');
  const deleteModal = document.getElementById('deleteModal');
  const confirmDelete = document.getElementById('confirmDelete');
  const cancelDelete = document.getElementById('cancelDelete');

  if (!tableBody) return; // nothing to do

  // Build rows array from DOM (live DOM nodes)
  const getRows = () => Array.from(tableBody.querySelectorAll('.project-row'));

  // Utility: read row values
  function rowValues(row) {
    // Normalize columns: index 0=id, 1=title, 2=category, 3=statusWrapper (contains dot + text)
    const cols = row.children;
    const id = cols[0] ? cols[0].innerText.trim() : '';
    const title = cols[1] ? cols[1].innerText.trim() : '';
    const category = cols[2] ? cols[2].innerText.trim() : '';
    // status may contain dot + span, so take text from last element in col 3
    let status = '';
    if (cols[3]) {
      status = cols[3].innerText.trim();
    }
    return { id, title, category, status };
  }

  // Show/hide pagination when filters active
  function updatePaginationVisibility(activeFilter) {
    if (!pagination) return;
    pagination.style.display = activeFilter ? 'none' : 'flex';
  }

  // Apply search + category filter + reflow rows
  function applyFiltersAndSort() {
    const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const selectedCat = categoryFilter ? (categoryFilter.value || '') : '';
    const rows = getRows();

    // Filtering
    let visibleCount = 0;
    rows.forEach(row => {
      const { title, id, category } = rowValues(row);
      let ok = true;
      if (q) {
        ok = (title.toLowerCase().includes(q) || id.toString().toLowerCase().includes(q));
      }
      if (ok && selectedCat) {
        ok = category === selectedCat;
      }
      row.style.display = ok ? 'grid' : 'none';
      if (ok) visibleCount++;
    });

    // Sorting if requested
    applySort(); // sorts only visible rows by current sortSelect, if any

    updatePaginationVisibility(Boolean(q) || Boolean(selectedCat));
  }

  // Sorting logic — sorts DOM nodes (only visible rows are considered)
  function applySort() {
    const rows = getRows().filter(r => r.style.display !== 'none');
    let mode = null;

    if (sortSelect) mode = sortSelect.value;
    else {
      // fallback: check data-sort attribute on table or prompt not ideal
      mode = null;
    }

    if (!mode) {
      // nothing requested — keep original DOM order
      return;
    }

    // choose comparator
    const comp = {
      'title_az': (a,b) => rowValues(a).title.localeCompare(rowValues(b).title),
      'category_az': (a,b) => rowValues(a).category.localeCompare(rowValues(b).category),
      'newest': (a,b) => {
         // assume ID ascending = older; if created_at ordering not available, fallback to numeric id desc
         const ai = parseFloat(rowValues(a).id) || 0;
         const bi = parseFloat(rowValues(b).id) || 0;
         return bi - ai;
      },
      'oldest': (a,b) => {
         const ai = parseFloat(rowValues(a).id) || 0;
         const bi = parseFloat(rowValues(b).id) || 0;
         return ai - bi;
      },
      'status_main_first': (a,b) => {
         const sa = (rowValues(a).status.toLowerCase() === 'main') ? 0 : 1;
         const sb = (rowValues(b).status.toLowerCase() === 'main') ? 0 : 1;
         if (sa === sb) return rowValues(a).title.localeCompare(rowValues(b).title);
         return sa - sb;
      }
    }[mode];

    if (!comp) return;

    // sort and re-append into tableBody preserving other hidden rows position
    const visibleRows = rows.slice().sort(comp);
    // To preserve hidden rows relative positions, remove and reappend visible rows in order
    visibleRows.forEach(r => tableBody.appendChild(r));
  }

  // Debounce helper
  function debounce(fn, ms=200) {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), ms);
    };
  }

  // Wire events
  if (searchInput) searchInput.addEventListener('input', debounce(applyFiltersAndSort, 150));
  if (categoryFilter) categoryFilter.addEventListener('change', applyFiltersAndSort);

  if (sortSelect) {
    sortSelect.addEventListener('change', () => {
      applySort();
    });
}

  // DELETE modal handling
  let deleteID = null;
  function attachDeleteButtons() {
    const delBtns = Array.from(document.querySelectorAll('.delete-btn'));
    delBtns.forEach(btn => {
      // avoid attaching twice
      if (btn.dataset.attached) return;
      btn.dataset.attached = '1';
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        deleteID = btn.dataset.id;
        if (!deleteModal) {
          // fallback: confirm then call
          if (!confirm('Delete this news?')) return;
          doDelete(deleteID);
          return;
        }
        deleteModal.style.display = 'flex';
      });
    });
  }

  async function doDelete(id) {
    if (!id) { alert('Invalid id'); return; }
    try {
      const res = await fetch('index.php?action=news_delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id })
      });
      const json = await res.json();
      if (json.success) {
        // remove row from DOM
        const row = Array.from(document.querySelectorAll('.project-row'))
          .find(r => r.children[0] && String(r.children[0].innerText).trim() === String(id));
        if (row) row.remove();
        // re-evaluate filters/pagination
        applyFiltersAndSort();
      } else {
        alert(json.msg || 'Delete failed');
      }
    } catch (err) {
      console.error(err);
      alert('Delete failed (network)');
    }
  }

  if (cancelDelete) {
    cancelDelete.addEventListener('click', () => {
      if (deleteModal) deleteModal.style.display = 'none';
      deleteID = null;
    });
  }

  if (confirmDelete) {
    confirmDelete.addEventListener('click', () => {
      if (!deleteID) return;
      doDelete(deleteID);
      if (deleteModal) deleteModal.style.display = 'none';
    });
  }

  // initial run
  attachDeleteButtons();
  // in case rows update later, re-attach (use MutationObserver)
  const mo = new MutationObserver(() => {
    attachDeleteButtons();
  });
  mo.observe(tableBody, { childList: true, subtree: true });

  // initial filter/sort state
  applyFiltersAndSort();

  // expose for console debugging (optional)
  window.__adminNews = {
    applyFiltersAndSort,
    applySort,
    doDelete
  };
})();
