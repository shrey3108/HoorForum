// Script to show/hide login and register modals
function closeModal(modal) {
    modal.style.display = "none";
}

window.onclick = function(event) {
    let loginModal = document.getElementById('loginForm');
    let registerModal = document.getElementById('registerForm');
    if (event.target == loginModal) {
        closeModal(loginModal);
    }
    if (event.target == registerModal) {
        closeModal(registerModal);
    }
}
