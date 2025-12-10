document.addEventListener("DOMContentLoaded", () => {
  const togglePass = document.getElementById("togglePass");
  const passwordInput = document.getElementById("password");

  if (togglePass && passwordInput) {
    togglePass.addEventListener("change", function () {
      passwordInput.type = this.checked ? "text" : "password";
    });
  }
});
