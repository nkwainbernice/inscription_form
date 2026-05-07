
// =====================
// CHECK FOR URL ERRORS
// =====================
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    
    if (error) {
        const messageBox = document.getElementById('messageBox');
        messageBox.textContent = '❌ ' + decodeURIComponent(error);
        messageBox.style.color = 'red';
        messageBox.style.backgroundColor = '#ffe6e6';
        messageBox.style.display = 'block';
        
        // Clear the URL to remove the error parameter
        window.history.replaceState({}, document.title, './signup.html');
    }
});

// =====================
// ELEMENTS
// =====================
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirm_password");

const submitBtn = document.getElementById("submitBtn");
const messageBox = document.getElementById("messageBox");
const matchMessage = document.getElementById("matchMessage");

const lengthEl = document.getElementById("length");
const upperEl = document.getElementById("uppercase");
const lowerEl = document.getElementById("lowercase");
const specialEl = document.getElementById("special");

const form = document.querySelector("form");



// =====================
// UI HELPERS
// =====================
function showMessage(text, type) {
    messageBox.textContent = text;

    if (type === "error") messageBox.style.color = "red";
    else if (type === "success") messageBox.style.color = "green";
    else messageBox.textContent = "";
}

function updateRule(el, condition, text) {
    el.textContent = (condition ? "✔️ " : "❌ ") + text;
    el.style.color = condition ? "green" : "red";
}


// =====================
// PASSWORD VALIDATION
// =====================
function validatePassword(value) {

    const hasLength = value.length >= 8 && value.length <= 12;
    const hasUpper = /[A-Z]/.test(value);
    const hasLower = /[a-z]/.test(value);
    const hasSpecial = /[\W]/.test(value);

    const isValid = hasLength && hasUpper && hasLower && hasSpecial;

    updateRule(lengthEl, hasLength, "8–12 characters");
    updateRule(upperEl, hasUpper, "At least one uppercase letter");
    updateRule(lowerEl, hasLower, "At least one lowercase letter");
    updateRule(specialEl, hasSpecial, "At least one special character");

    if (!value) {
        showMessage("", "neutral");
        return false;
    }

    showMessage(
        isValid ? "Strong password ✔" : "Password does not meet requirements!",
        isValid ? "success" : "error"
    );

    return isValid;
}


// =====================
// CONFIRM PASSWORD MATCH
// =====================
function checkPasswordMatch() {

    const pass = password.value;
    const confirm = confirmPassword.value;

    if (!confirm) {
        matchMessage.textContent = "";
        return false;
    }

    const match = pass === confirm;

    matchMessage.textContent = match
        ? "✔ Passwords match"
        : "❌ Passwords do not match";

    matchMessage.style.color = match ? "green" : "red";

    return match;
}


// =====================
// GLOBAL VALIDATION CONTROLLER
// =====================
function validateForm() {

    const passwordValid = validatePassword(password.value);
    const matchValid = checkPasswordMatch();

    const isValid = passwordValid && matchValid;

    submitBtn.disabled = !isValid;
    submitBtn.style.backgroundColor = isValid ? "green" : "gray";
    submitBtn.style.cursor = isValid ? "pointer" : "not-allowed";

    return isValid;
}


// =====================
// EVENTS
// =====================
password.addEventListener("input", validateForm);
confirmPassword.addEventListener("input", validateForm);


// =====================
// SUBMIT CONTROL (IMPORTANT)
// =====================
form.addEventListener("submit", function (e) {

    if (!validateForm()) {
        e.preventDefault();
        showMessage("Please fix errors before submitting!", "error");
    }
});


// =====================
// RESET / CANCEL
// =====================
form.addEventListener("reset", function () {

    setTimeout(() => {

        messageBox.textContent = "";
        matchMessage.textContent = "";

        updateRule(lengthEl, false, "8–12 characters");
        updateRule(upperEl, false, "At least one uppercase letter");
        updateRule(lowerEl, false, "At least one lowercase letter");
        updateRule(specialEl, false, "At least one special character");

        submitBtn.disabled = true;
        submitBtn.style.backgroundColor = "gray";
        submitBtn.style.cursor = "not-allowed";

    }, 0);
});