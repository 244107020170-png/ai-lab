const togglePass = document.getElementById("togglePass");
const passwordInput = document.getElementById("password");

togglePass.addEventListener("change", () => {
    passwordInput.type = togglePass.checked ? "text" : "password";
});

document.getElementById("loginForm").addEventListener("submit", function (e) {
    // default submit ke PHP, tidak preventDefault
    console.log("Submitting login form...");
});
