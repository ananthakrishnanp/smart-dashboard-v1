document.addEventListener("DOMContentLoaded", function() {
    const jobList = document.getElementById('job-list');

    // Fetch active jobs from the server
    fetch('../php/get_active_jobs.php')
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