// Handle navigation menu click events
document.querySelectorAll('.nav-items li').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.nav-items li').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});
