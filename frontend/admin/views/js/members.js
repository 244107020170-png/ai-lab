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

function updateUrlParam(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    url.searchParams.set('page', 1); // Reset to page 1 on sort change
    window.location.href = url.toString();
}

function applySort(column) {
    updateUrlParam('sort', column);
}

function applyOrder(order) {
    updateUrlParam('order', order);
}

const mSearch = document.getElementById("mSearch");
if (mSearch) {
    mSearch.addEventListener("keyup", function () {
        const value = this.value.toLowerCase();
        
        // Loop through all table rows
        document.querySelectorAll(".member-table tr").forEach((row, index) => {
            // Skip the Header Row (Index 0)
            if (index === 0) return; 

            // Check if the row text contains the search input
            if (row.innerText.toLowerCase().includes(value)) {
                row.style.display = ""; 
            } else {
                row.style.display = "none"; 
            }
        });
    });
}

function openVolunteerModal(id) {
    fetch(`index.php?action=volunteer_view&id=${id}`)
        .then(res => res.json())
        .then(data => {
            let areas = data.areas;
            try { areas = JSON.parse(areas); } catch(e) {}
            const table = document.getElementById("volunteerDetailTable");

            table.innerHTML = `
            <tr><th>Full Name</th><td>${data.full_name}</td></tr>
            <tr><th>Nickname</th><td>${data.nickname}</td></tr>
            <tr><th>Email</th><td>${data.email}</td></tr>
            <tr><th>Phone</th><td>${data.phone}</td></tr>
            <tr><th>Study Program</th><td>${data.study_program}</td></tr>
            <tr><th>Semester</th><td>${data.semester}</td></tr>
            <tr><th>Areas</th>
                <td>${
                    Array.isArray(areas)
                        ? areas.join(", ")
                        : (typeof areas === 'string' ? areas : '')
                }</td></tr>
            <tr><th>Skills</th><td>${data.skills}</td></tr>
            <tr><th>Motivation</th><td>${data.motivation}</td></tr>
            <tr><th>Availability</th><td>${data.availability}</td></tr>
        `;

            document.getElementById("volunteerModal").classList.remove("hidden");
        });
}

function closeVolunteerModal() {
    document.getElementById("volunteerModal").classList.add("hidden");
}
const vSearch = document.getElementById("vSearch");
if (vSearch) {
    vSearch.addEventListener("keyup", function () {
        const value = this.value.toLowerCase();
        document.querySelectorAll(".vol-row").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
}

function toggleVolunteerFilter() {
    document.getElementById("vFilterDropdown").classList.toggle("show");
}

function setVolunteerFilter(status) {
    document.getElementById("vFilterLabel").innerText = status;
    document.getElementById("vFilterDropdown").classList.remove("show");

    document.querySelectorAll(".vol-row").forEach(row => {
        let rowStatus = row.dataset.status;

        if (status === "All") {
            row.style.display = "";
        } else {
            row.style.display = (rowStatus === status) ? "" : "none";
        }
    });
}

