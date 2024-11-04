// Script to show/hide login and register modals
function closeModal(modal) {
    modal.style.display = "none";
}

window.onclick = function(event) {
    // Get both modals
    let registerModal = document.getElementById('registerForm');
   
    // Check if the click target is outside the registerModal only
    if (event.target == registerModal) {
        closeModal(registerModal);
    }
};
