document.addEventListener('DOMContentLoaded', function() {
    const usernameField = document.getElementById('username');
    const passwordField = document.getElementById('password');
    const usernameError = document.getElementById('username-error');
    const passwordError = document.getElementById('password-error');
    const submitButton = document.querySelector('.btn-submit');

    function validateUsername() {
        const usernameValue = usernameField.value.trim(); 

        if (usernameValue === '') {
            usernameError.textContent = 'Este campo no puede estar vacío.';
            usernameError.style.color = 'red';
            usernameField.style.border = '2px solid red';
        } else {
            usernameError.textContent = ''; 
            usernameField.style.border = '';
        }

        toggleSubmitButton();
    }

    function validatePassword() {
        const passwordValue = passwordField.value.trim();

        if (passwordValue === '') {
            passwordError.textContent = 'Este campo no puede estar vacío.';
            passwordError.style.color = 'red';
            passwordField.style.border = '2px solid red';
        } else {
            passwordError.textContent = '';
            passwordField.style.border = '';
        }

        toggleSubmitButton();
    }

    function toggleSubmitButton() {
        const usernameValue = usernameField.value.trim();
        const passwordValue = passwordField.value.trim();

        if (usernameValue !== '' && passwordValue !== '') {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    usernameField.addEventListener('blur', validateUsername);
    passwordField.addEventListener('blur', validatePassword);

    usernameField.addEventListener('input', validateUsername);
    passwordField.addEventListener('input', validatePassword);
});
