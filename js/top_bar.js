// Update date and time every minute
function updateDateTime() {
    const now = new Date();
    const options = { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric', 
        hour: 'numeric', 
        minute: '2-digit', 
        hour12: true 
    };
    const formattedDateTime = now.toLocaleString('en-US', options);
    document.getElementById('current-date-time').textContent = formattedDateTime;
}

// Update date and time immediately and then every minute
updateDateTime();
setInterval(updateDateTime, 60000);

// Handle profile button click to toggle the dropdown menu
document.querySelector('.profile-btn').addEventListener('click', function() {
    const dropdownMenu = document.getElementById('dropdown-menu');
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

// Close dropdown if clicked outside
window.addEventListener('click', function(e) {
    const profileBtn = document.querySelector('.profile-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');
    
    if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.style.display = 'none';
    }
});

// Logout functionality
document.getElementById('logout-btn').addEventListener('click', function() {
    alert('You have been logged out.');
    // Add redirect logic here if needed, e.g., window.location.href = 'login.php';
});
