// projects.js (plain JS)
document.addEventListener('DOMContentLoaded', function(){
  // handle delete clicks
  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function(e){
      e.preventDefault();
      const id = this.dataset.id;
      if (!confirm('Delete this project?')) return;

      fetch('index.php?action=projects&op=delete', {
        method: 'POST',
        headers: {'X-Requested-With':'XMLHttpRequest'},
        body: new URLSearchParams({id: id})
      })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          showToast(res.message || 'Successfully Deleted!');
          // remove row visually
          const menu = document.querySelector('.dots-menu[data-id="'+id+'"]');
          if (menu) {
            const row = menu.closest('.table-row');
            if (row) row.remove();
          }
        } else {
          alert(res.message || 'Delete failed');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Delete failed (network).');
      });
    });
  });

  // dots toggle (close others)
  document.querySelectorAll('.dots-btn').forEach(b => {
    b.addEventListener('click', function(e){
      e.stopPropagation();
      const id = this.dataset.id;
      // hide all
      document.querySelectorAll('.dots-menu').forEach(m => m.style.display = 'none');
      const menu = document.querySelector('.dots-menu[data-id="'+id+'"]');
      if (menu) menu.style.display = 'block';
    });
  });

  // close dots-menu when clicking outside
  document.addEventListener('click', function(){
    document.querySelectorAll('.dots-menu').forEach(m => m.style.display = 'none');
  });

  // toast helper
  window.showToast = function(msg) {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(()=> t.classList.remove('show'), 2500);
  };

  // prevent accidental unsaved changes (basic)
  const form = document.getElementById('projectForm');
  if (form) {
    let initial = new FormData(form);
    window.addEventListener('beforeunload', function(e){
      if (!form) return;
      const now = new FormData(form);
      // naive compare size
      if (initial.toString !== now.toString) {
        // show browser confirm
        e.preventDefault();
        e.returnValue = '';
      }
    });
  }
});
