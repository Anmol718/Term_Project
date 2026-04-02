const passwordInput  = document.getElementById('password');
const confirmInput   = document.getElementById('confirm_password');
const matchMsg       = document.getElementById('password-match-msg');
const signupForm     = document.getElementById('signupForm');

function checkPasswordMatch() {
    const pw  = passwordInput.value;
    const cpw = confirmInput.value;

    if (cpw === '') {
        matchMsg.textContent = '';
        matchMsg.className = 'form-text';
        return;
    }

    if (pw === cpw) {
        matchMsg.textContent = '✔ Passwords match!';
        matchMsg.className = 'form-text text-success';
        confirmInput.classList.remove('is-invalid');
        confirmInput.classList.add('is-valid');
    } else {
        matchMsg.textContent = '✘ Passwords do not match.';
        matchMsg.className = 'form-text text-danger';
        confirmInput.classList.remove('is-valid');
        confirmInput.classList.add('is-invalid');
    }
}

confirmInput.addEventListener('input', checkPasswordMatch);
passwordInput.addEventListener('input', checkPasswordMatch);

signupForm.addEventListener('submit', function(e) {
    if (passwordInput.value !== confirmInput.value) {
        e.preventDefault();
        matchMsg.textContent = '✘ Passwords do not match. Please fix before submitting.';
        matchMsg.className = 'form-text text-danger';
        confirmInput.focus();
    }
});
