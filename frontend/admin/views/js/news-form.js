// news-form.js
document.addEventListener('DOMContentLoaded', function(){
  const thumbInput = document.getElementById('thumbInput');
  const detailInput = document.getElementById('detailInput');
  const thumbPreview = document.getElementById('thumbPreview');
  const detailPreview = document.getElementById('detailPreview');
  const thumbFilename = document.getElementById('thumbFilename');
  const detailFilename = document.getElementById('detailFilename');
  const maxBytes = 5 * 1024 * 1024; // 5MB

  function handleFileInput(file, previewEl, nameEl) {
    if (!file) return;
    if (file.size > maxBytes) {
      alert('Image file size exceeds 5MB. Please upload a smaller image.');
      return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
      previewEl.src = e.target.result;
    }
    reader.readAsDataURL(file);
    nameEl.textContent = file.name;
  }

  if (thumbInput) {
    thumbInput.addEventListener('change', function(e){
      handleFileInput(this.files[0], thumbPreview, thumbFilename);
      markUnsaved(true);
    });
  }
  if (detailInput) {
    detailInput.addEventListener('change', function(e){
      handleFileInput(this.files[0], detailPreview, detailFilename);
      markUnsaved(true);
    });
  }

  const form = document.getElementById('newsForm');
  const unsavedBanner = document.getElementById('unsavedBanner');
  const discardBtn = document.getElementById('discardBtn');
  const saveBannerBtn = document.getElementById('saveBannerBtn');
  const saveBtn = document.getElementById('saveBtn');

  let dirty = false;
  function markUnsaved(v){
    dirty = !!v;
    unsavedBanner.style.display = dirty ? 'flex' : 'none';
  }

  // any input change marks unsaved
  form.querySelectorAll('input, textarea, select').forEach(el => {
    el.addEventListener('input', ()=> markUnsaved(true));
  });

  discardBtn && discardBtn.addEventListener('click', function(){
    if (!confirm('Discard changes?')) return;
    window.location.href = 'index.php?action=news';
  });

  saveBannerBtn && saveBannerBtn.addEventListener('click', function(){
    saveBtn.click();
  });

  window.addEventListener('beforeunload', function(e){
    if (dirty) {
      e.preventDefault();
      e.returnValue = '';
    }
  });

});
