fetch('../php/get_activities.php')
    .then(response => response.json())
    .then(activities => {
        const activityList = document.getElementById('activity-list');

        // Clearing list items
        activityList.innerHTML = '';

        // Looping through activities
        activities.forEach(activity => {
            const li = document.createElement('li');
            li.innerHTML = `
                <svg class="user-icon" width="24" height="24" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                    <title>User Profile Icon</title>
                    <circle cx="20" cy="20" r="20" fill="#E0E0E0"/>
                    <path d="M20 21C22.7614 21 25 18.7614 25 16C25 13.2386 22.7614 11 20 11C17.2386 11 15 13.2386 15 16C15 18.7614 17.2386 21 20 21ZM20 23C15.0294 23 11 25.6863 11 29V31H29V29C29 25.6863 24.9706 23 20 23Z" fill="#757575"/>
                </svg>
                <div class="activity-content">
                    <strong>${activity.user}</strong> ${activity.action} ${activity.item}.
                    <span class="timestamp">${activity.time}</span>
                </div>
            `;
            activityList.appendChild(li);
        });
    })
    .catch(error => console.error('Error fetching activities:', error));
    