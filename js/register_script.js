document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registerForm");

  const usernamePattern = /^[a-zA-Z0-9_]{4,20}$/;
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  const fields = {
    fullname: { element: document.getElementById("fullname"), validator: val => val.trim() !== "", message: "Full name is required." },
    email: { element: document.getElementById("email"), validator: val => emailPattern.test(val), message: "Invalid email." },
    username: { element: document.getElementById("username"), validator: val => usernamePattern.test(val), message: "4-20 letters, numbers, or underscores." },
    password: { element: document.getElementById("password"), validator: val => val.length >= 8, message: "At least 8 characters." },
    confirm_password: { element: document.getElementById("confirm_password"), validator: val => val === document.getElementById("password").value, message: "Passwords do not match." }
  };

  Object.keys(fields).forEach(key => {
    const field = fields[key];
    const errorEl = document.getElementById(key + "Error");

    field.element.addEventListener("input", () => {
      field.element.classList.add("typing");

      let valid = field.validator(field.element.value);
      if (field.element.value === "") valid = false;

      field.element.classList.remove("valid", "invalid");

      if (field.element.value.length > 0) {
        if (valid) {
          field.element.classList.add("valid");
          errorEl.textContent = "";
        } else {
          field.element.classList.add("invalid");
          errorEl.textContent = field.message;
        }
      } else {
        errorEl.textContent = "";
      }

      // Special case: confirm password
      if (key === "password") {
        const confirm = fields.confirm_password.element;
        const confirmError = document.getElementById("confirmPasswordError");
        if (confirm.value.length > 0) {
          if (confirm.value !== field.element.value) {
            confirm.classList.add("invalid");
            confirm.classList.remove("valid");
            confirmError.textContent = fields.confirm_password.message;
          } else {
            confirm.classList.remove("invalid");
            confirm.classList.add("valid");
            confirmError.textContent = "";
          }
        }
      }
    });
  });

  // Toggle password visibility
  document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", () => {
      const input = icon.previousElementSibling;
      input.type = input.type === "password" ? "text" : "password";
    });
  });

  // Final check on submit
  form.addEventListener("submit", e => {
    let formValid = true;
    Object.keys(fields).forEach(key => {
      const field = fields[key];
      if (!field.validator(field.element.value)) {
        formValid = false;
        field.element.classList.add("invalid");
        document.getElementById(key + "Error").textContent = field.message;
      }
    });
    if (!formValid) e.preventDefault();
  });
});
