// Reusable validation function
function validateField(input, errorSpan, validatorFn, errorMessage) {
    const value = input.value.trim();

    if (value === "") {
        input.classList.remove("valid");
        input.classList.add("invalid");
        errorSpan.textContent = errorMessage;
        return false;
    }

    if (validatorFn(value)) {
        input.classList.remove("invalid");
        input.classList.add("valid");
        errorSpan.textContent = "";
        return true;
    } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
        errorSpan.textContent = errorMessage;
        return false;
    }
}

// INPUT FIELDS
const fullname = document.getElementById("fullname");
const email = document.getElementById("email");
const username = document.getElementById("username");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirm_password");

// ERROR SPANS
const fullnameError = document.getElementById("fullnameError");
const emailError = document.getElementById("emailError");
const usernameError = document.getElementById("usernameError");
const passwordError = document.getElementById("passwordError");
const confirmPasswordError = document.getElementById("confirmPasswordError");

// PASSWORD TOGGLE
document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", () => {
        const input = icon.previousElementSibling;
        input.type = input.type === "password" ? "text" : "password";
        icon.classList.toggle("show");
    });
});

// Field validators
fullname.addEventListener("input", () => {
    fullname.classList.add("typing");
    validateField(fullname, fullnameError, v => v.length >= 3, "Full name is required.");
});

email.addEventListener("input", () => {
    email.classList.add("typing");
    validateField(email, emailError, v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), "Enter a valid email.");
});

username.addEventListener("input", () => {
    username.classList.add("typing");
    validateField(username, usernameError, v => /^[a-zA-Z0-9_]{4,20}$/.test(v), "Invalid username.");
});

password.addEventListener("input", () => {
    password.classList.add("typing");
    validateField(password, passwordError, v => v.length >= 8, "Password must be 8+ characters.");
});

confirmPassword.addEventListener("input", () => {
    confirmPassword.classList.add("typing");
    validateField(confirmPassword, confirmPasswordError, v => v === password.value, "Passwords do not match.");
});

// FINAL FORM CHECK BEFORE SUBMIT
document.getElementById("registerForm").addEventListener("submit", function (e) {

    let valid = true;

    if (!validateField(fullname, fullnameError, v => v.length >= 3, "Full name is required.")) valid = false;
    if (!validateField(email, emailError, v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), "Enter a valid email.")) valid = false;
    if (!validateField(username, usernameError, v => /^[a-zA-Z0-9_]{4,20}$/.test(v), "Invalid username.")) valid = false;
    if (!validateField(password, passwordError, v => v.length >= 8, "Password must be 8+ characters.")) valid = false;
    if (!validateField(confirmPassword, confirmPasswordError, v => v === password.value, "Passwords do not match.")) valid = false;

    if (!valid) {
        e.preventDefault(); // STOP FORM SUBMIT
    }
});


