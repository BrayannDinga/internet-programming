
const passwordInput = document.getElementById("password");
const submitButton = document.getElementById("submitBtn");

passwordInput.addEventListener("input", function() {
    if (passwordInput.value.length >= 6) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
});