function resizeHomeLayout() {
    const width = window.innerWidth;

    // STAT BOXES AUTO STACK
    const statRow = document.querySelector(".stats-row");
    if (width < 900) {
        statRow.style.flexDirection = "column";
        statRow.style.alignItems = "center";
    } else {
        statRow.style.flexDirection = "row";
    }

    // MEMBER + PROJECT BOXES AUTO STACK
    const graphRows = document.querySelectorAll(".graphs-row");
    graphRows.forEach(row => {
        if (width < 1100) {
            row.style.flexDirection = "column";
            row.style.alignItems = "center";
        } else {
            row.style.flexDirection = "row";
        }
    });
}

// Trigger on load + resize
resizeHomeLayout();
window.addEventListener("resize", resizeHomeLayout);