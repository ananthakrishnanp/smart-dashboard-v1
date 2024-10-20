// Handle navigation menu click events
document.querySelectorAll('.nav-items li').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.nav-items li').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});


//Top Bar//

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

//Dashboard
document.addEventListener("DOMContentLoaded", function () {
    const machineSelect = document.getElementById('machine-select');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const canvas = document.getElementById('vitals-chart');
    const ctx = canvas.getContext('2d');

    // Function to fetch machines from the database and populate the dropdown
    function fetchMachines() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'php/fetch_machines.php', true);

        xhr.onload = function () {
            if (this.status === 200) {
                const machines = JSON.parse(this.responseText);
                populateMachineDropdown(machines);
            } else {
                console.error('Error fetching machines.');
            }
        };
        xhr.send();
    }

    // Function to populate the machine dropdown
    function populateMachineDropdown(machines) {
        machineSelect.innerHTML = ''; // Clear the existing options
        machines.forEach(machine => {
            const option = document.createElement('option');
            option.value = machine.machine_id;
            option.textContent = machine.machine_name;
            machineSelect.appendChild(option);
        });

        // Fetch the initial data after the dropdown is populated
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
        fetchAverageValues(machineSelect.value, startDateInput.value, endDateInput.value);
    }

    const padding = 50; // Padding for the chart

    // Function to handle high-DPI screen rendering
    function resizeCanvasForHighDPI(canvas, ctx) {
        const pixelRatio = window.devicePixelRatio || 1;
        const width = canvas.offsetWidth;
        const height = canvas.offsetHeight;

        // Set the canvas resolution to match the pixel ratio
        canvas.width = width * pixelRatio;
        canvas.height = height * pixelRatio;

        // Scale the context to ensure sharp rendering
        ctx.scale(pixelRatio, pixelRatio);

        // Maintain the CSS size by setting back to the original width and height
        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;
    }

    function drawGrid(ctx, width, height, labelsX, labelsY) {
        ctx.strokeStyle = '#e0e0e0';
        ctx.lineWidth = 1;
        const chartWidth = width - padding * 2;
        const chartHeight = height - padding * 2;

        // Draw vertical grid lines and x-axis labels (time)
        const labelInterval = 8; // 4-hour intervals
        for (let i = 0; i < labelsX.length; i++) {
            if (i % labelInterval === 0) {  // Only draw every 8th label (4 hours)
                const x = padding + (chartWidth / (labelsX.length - 1)) * i;
                ctx.beginPath();
                ctx.moveTo(x, padding);
                ctx.lineTo(x, height - padding);
                ctx.stroke();

                // X-axis labels
                ctx.fillStyle = '#333';
                ctx.textAlign = 'center';
                ctx.fillText(labelsX[i], x, height - padding + 20);
            }
        }

        // Draw horizontal grid lines and y-axis labels (values)
        const maxY = Math.max(...labelsY);
        for (let i = 0; i <= 10; i++) {
            const y = padding + (chartHeight / 10) * i;
            const label = ((maxY / 10) * (10 - i)).toFixed(2);
            ctx.beginPath();
            ctx.moveTo(padding, y);
            ctx.lineTo(width - padding, y);
            ctx.stroke();

            // Y-axis labels
            ctx.fillStyle = '#333';
            ctx.textAlign = 'right';
            ctx.fillText(label, padding - 10, y + 3);
        }
    }

    function drawLineGraph(ctx, data, color, width, height, minVal, maxVal) {
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;
        ctx.beginPath();

        const chartWidth = width - padding * 2;
        const chartHeight = height - padding * 2;

        const scaleX = chartWidth / (data.length - 1);
        const scaleY = chartHeight / (maxVal - minVal);

        for (let i = 0; i < data.length; i++) {
            const x = padding + i * scaleX;
            const y = padding + chartHeight - (data[i] - minVal) * scaleY;

            if (i === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        }

        ctx.stroke();
    }

    function clearCanvas(ctx, width, height) {
        ctx.clearRect(0, 0, width, height);
    }

    function fetchAverageValues(machineId, startDate, endDate) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'php/fetch_average.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200) {
                const responseData = JSON.parse(this.responseText);
                updateAverageValues(responseData);
            } else {
                console.error("Error fetching average values.");
            }
        };
        xhr.send(`machine_id=${machineId}&start_date=${startDate}&end_date=${endDate}`);
    }
    
    function updateAverageValues(data) {
        console.log('Update Function Called', data);
    
        // Ensure data is not null before updating the DOM
        const avgTemperature = data.avg_temperature ? parseFloat(data.avg_temperature).toFixed(2) : '--';
        const avgPressure = data.avg_pressure ? parseFloat(data.avg_pressure).toFixed(2) : '--';
        const avgHumidity = data.avg_humidity ? parseFloat(data.avg_humidity).toFixed(2) : '--';
        const avgVibration = data.avg_vibration ? parseFloat(data.avg_vibration).toFixed(2) : '--';

    
        // Update the HTML elements with the average values
        document.getElementById('avg-temp').textContent = `${avgTemperature}°C`;
        document.getElementById('avg-pressure').textContent = `${avgPressure} Pa`;
        document.getElementById('avg-humidity').textContent = `${avgHumidity}%`;
        document.getElementById('avg-vibration').textContent = `${avgVibration} Hz`;
    }
    

    function fetchChartData(machineId, startDate, endDate) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'php/fetch_data.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200) {
                const responseData = JSON.parse(this.responseText);
                updateChart(responseData);
            } else {
                document.getElementById('status-message').textContent = "Error fetching data.";
            }
        };
        xhr.send(`machine_id=${machineId}&start_date=${startDate}&end_date=${endDate}`);
    }

    function updateChart(data) {
        const temperatureData = data.map(row => row.temperature);
        const pressureData = data.map(row => row.pressure);
        const labelsX = data.map(row => row.time);
        const labelsY = [...temperatureData, ...pressureData];

        const minTemperature = Math.min(...temperatureData);
        const maxTemperature = Math.max(...temperatureData);
        const minPressure = Math.min(...pressureData);
        const maxPressure = Math.max(...pressureData);

        const minVal = Math.min(minTemperature, minPressure);
        const maxVal = Math.max(maxTemperature, maxPressure);

        clearCanvas(ctx, canvas.width, canvas.height);
        drawGrid(ctx, canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio, labelsX, labelsY);

        drawLineGraph(ctx, temperatureData, 'rgba(255, 99, 132, 1)', canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio, minVal, maxVal);
        drawLineGraph(ctx, pressureData, 'rgba(54, 162, 235, 1)', canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio, minVal, maxVal);
    }

    // Resize canvas for high-DPI screens
    resizeCanvasForHighDPI(canvas, ctx);

    // Fetch and populate machines when page loads
    fetchMachines();
    fetchAverageValues(machineSelect.value, startDateInput.value, endDateInput.value);


    // Update chart when filters change
    machineSelect.addEventListener('change', () => {
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
        fetchAverageValues(machineSelect.value, startDateInput.value, endDateInput.value);
    });

    startDateInput.addEventListener('change', () => {
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
        fetchAverageValues(machineSelect.value, startDateInput.value, endDateInput.value);
    });

    endDateInput.addEventListener('change', () => {
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
        fetchAverageValues(machineSelect.value, startDateInput.value, endDateInput.value);
    });
});



//Notifications
// Fetch notifications from the PHP endpoint
fetch('php/get_notifications.php')
    .then(response => response.json())
    .then(notifications => {
        const notificationList = document.getElementById('notification-list');

        // Clear any existing list items
        notificationList.innerHTML = '';

        // Loop through the notifications and append them to the list
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

    //Activities
    // Fetch activities from the PHP endpoint
fetch('php/get_activities.php')
.then(response => response.json())
.then(activities => {
    const activityList = document.getElementById('activity-list');

    // Clear any existing list items
    activityList.innerHTML = '';

    // Loop through the activities and append them to the list
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

document.getElementById('logout-btn').addEventListener('click', function() {
    window.location.href = 'php/logout_logic.php'; // Ensure this points to the correct location
});

// Second Logout Button Logic (for the profile logout)
document.getElementById('profile-logout-btn').addEventListener('click', function() {
    window.location.href = 'php/logout_logic.php'; // Redirect to logout logic
});


//JOBS PAGE
document.addEventListener("DOMContentLoaded", function() {
    const jobList = document.getElementById('job-list');

    // Fetch active jobs from the server
    fetch('php/get_active_jobs.php')
        .then(response => response.json())
        .then(jobs => {
            if (jobs.message) {
                const noJobsMessage = document.createElement('p');
                noJobsMessage.textContent = jobs.message;
                jobList.appendChild(noJobsMessage);
            } else {
                jobs.forEach(job => {
                    const jobItem = document.createElement('div');
                    jobItem.className = 'job-item';

                    let statusColor = '';
                    switch (job.status.toLowerCase()) {
                        case 'started':
                            statusColor = 'background-color: #dff0ff; color: #004080;';
                            break;
                        case 'in progress':
                            statusColor = 'background-color: #e6f3ff; color: #0066cc;';
                            break;
                        case 'completed':
                            statusColor = 'background-color: #e6ffe6; color: #006600;';
                            break;
                        case 'waiting':
                            statusColor = 'background-color: #fff5e6; color: #cc6600;';
                            break;
                        case 'aborted':
                            statusColor = 'background-color: #ffe6e6; color: #cc0000;';
                            break;
                        default:
                            statusColor = 'background-color: #f0f0f0; color: #333;';
                            break;
                    }

                    const descriptionText = job.description ? job.description : 'No description provided';

                    jobItem.innerHTML = `
                        <div>${job.id}</div>
                        <div>${job.machine}</div>
                        <div>${job.time}</div>
                        <div><span class="status" style="${statusColor}">${job.status}</span></div>
                        <div>${descriptionText}</div>
                    `;
                    jobList.appendChild(jobItem);
                });
            }
        })
        .catch(error => console.error('Error fetching jobs:', error));
});

document.addEventListener("DOMContentLoaded", function() {
    const operatorJobList = document.getElementById('operator-job-list');

    // Fetch operator jobs from the server
    fetch('php/get_operator_jobs.php') // New endpoint to fetch jobs assigned to the operator
        .then(response => response.json())
        .then(jobs => {
            if (jobs.message) {
                const noJobsMessage = document.createElement('p');
                noJobsMessage.textContent = jobs.message;
                operatorJobList.appendChild(noJobsMessage);
            } else {
                jobs.forEach(job => {
                    const jobItem = document.createElement('div');
                    jobItem.className = 'job-item';
                    
                    // Generate the status color
                    let statusColor = '';
                    switch (job.status.toLowerCase()) {
                        case 'started':
                            statusColor = 'background-color: #dff0ff; color: #004080;';
                            break;
                        case 'in progress':
                            statusColor = 'background-color: #e6f3ff; color: #0066cc;';
                            break;
                        case 'completed':
                            statusColor = 'background-color: #e6ffe6; color: #006600;';
                            break;
                        case 'waiting':
                            statusColor = 'background-color: #fff5e6; color: #cc6600;';
                            break;
                        case 'aborted':
                            statusColor = 'background-color: #ffe6e6; color: #cc0000;';
                            break;
                        default:
                            statusColor = 'background-color: #f0f0f0; color: #333;';
                            break;
                    }

                    // Generate the HTML for each job
                    jobItem.innerHTML = `
                        <div>${job.id}</div>
                        <div>${job.machine}</div>
                        <div>${job.time}</div>
                        <div><span class="status" style="${statusColor}">${job.status}</span></div>
                        <div>${job.description ? job.description : 'No description provided'}</div>
                        <div>
                            <button class="send-note-btn" data-job-id="${job.id}">Send Note</button>
                        </div>
                    `;
                    operatorJobList.appendChild(jobItem);
                });
            }
        })
        .catch(error => console.error('Error fetching operator jobs:', error));
});



//Note Submission
document.addEventListener("DOMContentLoaded", function() {
    const taskNoteModal = document.getElementById('taskNoteModal');
    const sendNoteForm = document.getElementById('send-task-note-form');
    const closeTaskNoteModal = document.getElementById('closeTaskNoteModal');

    // Open modal when "Send Note" button is clicked
    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('send-note-btn')) {
            const jobId = event.target.dataset.jobId;
            document.getElementById('note_job_id').value = jobId;
            fetchManagers(); // Populate managers
            taskNoteModal.style.display = 'block';
            console.log('Task Note Modal opened for Job ID:', jobId); // Debugging log
        }
    });

    // Close the modal when "x" is clicked
    closeTaskNoteModal.onclick = function() {
        taskNoteModal.style.display = 'none';
    };

    // Close modal if clicking outside of modal content
    window.onclick = function(event) {
        if (event.target == taskNoteModal) {
            taskNoteModal.style.display = 'none';
        }
    };

    // Fetch managers and populate the select dropdown
    function fetchManagers() {
        fetch('php/fetch_managers.php')
            .then(response => response.json())
            .then(managers => {
                console.log('Managers fetched:', managers); // Debugging log
                const managerSelect = document.getElementById('managerSelect');
                if (!managerSelect) {
                    console.error('Manager select element not found');
                    return;
                }
                
                // Clear existing options
                managerSelect.innerHTML = '<option value="" disabled selected>Select Manager</option>';
                
                // Populate options
                managers.forEach(manager => {
                    console.log('Adding manager:', manager); // Debugging log
                    const option = document.createElement('option');
                    option.value = manager.UID; // Corrected property
                    option.textContent = `${manager.first_name} ${manager.last_name}`;
                    managerSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching managers:', error);
            });
    }

    // Handle form submission
    sendNoteForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        console.log('Submitting task note form.');

        const formData = new FormData(sendNoteForm);
        const taskSub = formData.get('task_sub');
        const taskBody = formData.get('task_body');
        const managerId = formData.get('manager_id');
        const jobId = formData.get('job_id');

        console.log('Form Data:', {
            task_sub: taskSub,
            task_body: taskBody,
            manager_id: managerId,
            job_id: jobId
        });

        if (!managerId) {
            alert('Please select a manager.');
            return;
        }

        fetch('./php/send_task_note.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Fetch response:', result); // Debugging log
            if (result.success) {
                // Show the success message using the custom function
            showMessage('Task note sent successfully!');
            taskNoteModal.style.display = 'none';  // Close the modal on success
            sendNoteForm.reset();  // Reset form fields
            } else {
                showMessage('Failed to send task note: ' + result.error, true);
            }
        })
        .catch(error => {
            console.error('Error sending task note:', error);
            alert('An error occurred while sending the task note.');
        });
    });
});


function showMessage(message, isError = false) {
    const messageDiv = isError ? document.getElementById('error-message') : document.getElementById('success-message');
    messageDiv.textContent = message;
    messageDiv.style.display = 'block';

    // Hide the message after 3 seconds
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 3000);
}





