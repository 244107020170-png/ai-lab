const togglePass = document.getElementById("togglePass");
const passwordInput = document.getElementById("password");

// Show / hide password
togglePass.addEventListener("change", () => {
    passwordInput.type = togglePass.checked ? "text" : "password";
});

// Submit handler
document.getElementById("loginForm").addEventListener("submit", function (e) {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!username || !password) {
        e.preventDefault();
        showError("Please fill out both fields.");
        return;
    }

    // Add loading spinner (ONLY ONCE)
    const btn = document.querySelector(".login-btn");
    btn.classList.add("loading");
    btn.innerHTML = `<div class="spinner"></div>`;
});

// Glow on focus
document.querySelectorAll(".input-wrapper input").forEach(input => {
    input.addEventListener("focus", () => {
        input.parentElement.classList.add("active");
    });
    input.addEventListener("blur", () => {
        input.parentElement.classList.remove("active");
    });
});

// Error handler
function showError(msg) {
    let err = document.getElementById("loginError");

    if (!err) {
        err = document.createElement("div");
        err.id = "loginError";
        err.className = "error-text";
        document.querySelector(".login-form").prepend(err);
    }

    err.innerText = msg;
    err.style.display = "block";

    setTimeout(() => {
        err.style.opacity = "0";
    }, 2500);

    setTimeout(() => {
        err.style.display = "none";
        err.style.opacity = "1";
    }, 3000);
}
