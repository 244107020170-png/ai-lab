// JS Script for Dropdown
function toggleFilter() {
    document.getElementById("filter-dropdown").style.display =
        document.getElementById("filter-dropdown").style.display === "flex"
            ? "none"
            : "flex";
}

function setFilter(value) {
    document.getElementById("filter-label").innerText = value;
    document.getElementById("filter-dropdown").style.display = "none";
}

function toggleSort() {
    const dropdown = document.getElementById("sort-dropdown");
    dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
}

function setSort(value) {
    document.getElementById("sort-label").innerText = value;
    document.getElementById("sort-dropdown").style.display = "none";
}
