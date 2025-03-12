import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.querySelector('.navbar-toggler');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });
});
