document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const alertsList = document.getElementById('alerts-list');
    const notificationsList = document.getElementById('notifications-list');
    const activitiesList = document.getElementById('activities-list');

    // fetch task note alerts
    function fetchAlerts() {
        if (!alertsList) return; 
        fetch('php/get_alerts.php')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    populateAlerts(data);
                } else {
                    console.error('Invalid data format for alerts:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching alerts:', error);
            });
    }

    // Notification
    function fetchNotifications() {
        if (!notificationsList) return; 
        fetch('php/get_notifications.php')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    populateNotifications(data);
                } else {
                    console.error('Invalid data format for notifications:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
            });
    }

    // Activities
    function fetchActivities() {
        if (!activitiesList) return; 
        fetch('php/get_activities.php')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    populateActivities(data);
                } else {
                    console.error('Invalid data format for activities:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching activities:', error);
            });
    }

    // Alert
    function populateAlerts(alertsData) {
        if (!alertsList) return; 
        alertsList.innerHTML = '';
        alertsData.forEach(alert => {
            const listItem = document.createElement('li');
            listItem.classList.add('alert-item');
            listItem.dataset.id = alert.note_id;
            listItem.innerHTML = `
                <div class="alert-title">${alert.task_sub || 'No Subject'}</div>
                <div class="alert-timestamp">${alert.timestamp || 'No Timestamp'}</div>
            `;
            alertsList.appendChild(listItem);
        });
    }

    // Populate Notifications
    function populateNotifications(notificationsData) {
        if (!notificationsList) return; 
        notificationsList.innerHTML = '';
        notificationsData.forEach(notification => {
            const listItem = document.createElement('li');
            listItem.innerHTML = `
                <div class="notification-content">
                    <div class="notification-item">${notification.item || 'No Item'}</div>
                    <div class="notification-status">${notification.status || 'No Status'}</div>
                    <div class="timestamp">${notification.time || 'No Time'}</div>
                </div>
            `;
            notificationsList.appendChild(listItem);
        });
    }

    // Populate Activities
    function populateActivities(activitiesData) {
        if (!activitiesList) return; 
        activitiesList.innerHTML = '';
        activitiesData.forEach(activity => {
            const listItem = document.createElement('li');
            listItem.innerHTML = `
                <div class="activity-content">
                    <div class="activity-user">${activity.user || 'No User'}</div>
                    <div class="activity-action">${activity.action || 'No Action'}</div>
                    <div class="activity-item">${activity.item || 'No Item'}</div>
                    <div class="timestamp">${activity.time || 'No Time'}</div>
                </div>
            `;
            activitiesList.appendChild(listItem);
        });
    }

    // Alert Click
    if (alertsList) {
        fetchAlerts();
        alertsList.addEventListener('click', function (e) {
            const alertItem = e.target.closest('.alert-item');
            if (alertItem) {
                const alertId = alertItem.dataset.id;
                fetchAlertDetails(alertId);
            }
        });
    }

    // Fetch Alert open Modal
    function fetchAlertDetails(alertId) {
        fetch(`php/get_alert_details.php?id=${alertId}`)
            .then(response => response.json())
            .then(alertData => {
                showAlertModal(alertData);
            })
            .catch(error => {
                console.error('Error fetching alert details:', error);
            });
    }

    // Alert Modal function
    const alertModal = document.getElementById('alertModal');
    const alertModalTitle = document.getElementById('alert-modal-title');
    const alertModalContent = document.getElementById('alert-modal-content');
    const closeModal = document.getElementById('closeAlertModal');

    function showAlertModal(alertData) {
        if (!alertModal) return; // Exit if alertModal is not present
        alertModalTitle.textContent = alertData.task_sub || 'No Subject';
        alertModalContent.textContent = alertData.task_body || 'No Content';
        alertModal.style.display = 'block';
    }

    // Close Button
    if (closeModal) {
        closeModal.addEventListener('click', function () {
            if (alertModal) {
                alertModal.style.display = 'none';
            }
        });
    }

    // Close when clicking outside
    window.addEventListener('click', function (e) {
        if (alertModal && e.target == alertModal) {
            alertModal.style.display = 'none';
        }
    });

    // Initialise page calling functions
    if (notificationsList) {
        fetchNotifications();
    }
    if (activitiesList) {
        fetchActivities();
    }
});
