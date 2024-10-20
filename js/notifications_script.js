// Fetch notifications
fetch('../php/get_notifications.php')
    .then(response => response.json())
    .then(notifications => {
        const notificationList = document.getElementById('notification-list');

        
        notificationList.innerHTML = '';

       
        notifications.forEach(notification => {
            const li = document.createElement('li');
            li.innerHTML = `
                <svg class="alert-icon" width="24" height="24" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                    <title>Alert Icon</title>
                    <circle cx="20" cy="20" r="20" fill="#fef08a"/>
                    <path d="M20 10v12" stroke="#854d0e" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="20" cy="28" r="2" fill="#854d0e"/>
                </svg>
                <div class="notification-content">
                    <strong>${notification.item}</strong> ${notification.status}.
                    <span class="timestamp">${notification.time}</span>
                </div>
            `;
            notificationList.appendChild(li);
        });
    })
    .catch(error => console.error('Error fetching notifications:', error));
