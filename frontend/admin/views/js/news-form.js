/* ============================================================
   NEWS FORM — IMAGE PREVIEW + UNSAVED CHANGES + CLEAN SUBMIT
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("newsForm");
    const saveBtn = document.getElementById("saveBtn");

    const thumbInput = document.getElementById("thumbInput");
    const detailInput = document.getElementById("detailInput");

    const thumbPreview = document.getElementById("thumbPreview");
    const detailPreview = document.getElementById("detailPreview");

    const thumbFilename = document.getElementById("thumbFilename");
    const detailFilename = document.getElementById("detailFilename");

    const unsavedBanner = document.getElementById("unsavedBanner");
    const saveBannerBtn = document.getElementById("saveBannerBtn");
    const discardBtn = document.getElementById("discardBtn");

    let isDirty = false;

    /* ============================================================
       MARK FORM AS DIRTY (Show Unsaved Banner)
       ============================================================ */
    function setDirty() {
        if (!isDirty) {
            isDirty = true;
            if (unsavedBanner) {
                unsavedBanner.style.display = "flex";
            }
        }
    }

    // Trigger dirty state on any form change
    document
        .querySelectorAll("#newsForm input, #newsForm textarea, #newsForm select")
        .forEach(el => {
            el.addEventListener("input", setDirty);
            el.addEventListener("change", setDirty);
        });

    /* ============================================================
       IMAGE PREVIEW HANDLER
       ============================================================ */
    function previewImage(input, imgEl, filenameEl) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            filenameEl.textContent = file.name;

            const reader = new FileReader();
            reader.onload = e => {
                imgEl.src = e.target.result;
            };
            reader.readAsDataURL(file);

            setDirty();
        }
    }

    if (thumbInput) {
        thumbInput.addEventListener("change", () => {
            previewImage(thumbInput, thumbPreview, thumbFilename);
        });
    }

    if (detailInput) {
        detailInput.addEventListener("change", () => {
            previewImage(detailInput, detailPreview, detailFilename);
        });
    }

    /* ============================================================
       REMOVE "LEAVE SITE" WARNING COMPLETELY
       ============================================================ */
    window.onbeforeunload = null;


    /* ============================================================
       SAVE ACTION (Banner Save)
       ============================================================ */
    if (saveBannerBtn) {
        saveBannerBtn.addEventListener("click", () => {
            isDirty = false; // prevent popup
            unsavedBanner.style.display = "none";
            form.submit();
        });
    }

    /* ============================================================
       SAVE BUTTON (BOTTOM)
       ============================================================ */
    if (saveBtn) {
        saveBtn.addEventListener("click", () => {
            isDirty = false; // prevent popup
            if (unsavedBanner) unsavedBanner.style.display = "none";
        });
    }

    /* ============================================================
       DISCARD BUTTON — GO BACK TO LIST
       ============================================================ */
    if (discardBtn) {
        discardBtn.addEventListener("click", () => {
            isDirty = false;
            if (unsavedBanner) unsavedBanner.style.display = "none";
            window.location.href = "index.php?action=news";
        });
    }

});
