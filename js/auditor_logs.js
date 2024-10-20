document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById('status-table-body');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const openOptionsBtn = document.getElementById('open-options-btn');
    const doneBtn = document.getElementById('done-btn');
    const resetBtn = document.getElementById('reset-btn');
    const modal = document.getElementById("optionsModal");
    const span = document.getElementsByClassName("close")[0];
    
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const searchDateBtn = document.getElementById('search-date-btn');
    
    let currentPage = 1;
    let currentSort = 'sl_no';  
    let currentSortOrder = 'asc'; 
    let currentFilter = '';  
    
    const defaultStartDate = '2024-04-01';
    const defaultEndDate = new Date().toISOString().split('T')[0]; 

    startDateInput.value = defaultStartDate;
    endDateInput.value = defaultEndDate;

    function fetchLogs(page, sort = 'sl_no', order = 'asc', filter = '', startDate, endDate) {
        fetch(`../php/fetch_auditor_logs.php?page=${page}&sort_by=${sort}&order=${order}&filter_status=${filter}&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                const logs = data.logs;
                const hasMoreLogs = data.has_more_logs;
                tableBody.innerHTML = '';
                logs.forEach((log) => {
                    const row = document.createElement('tr');
                    let statusClass = '';
                    switch (log.operational_status.toLowerCase()) {
                        case 'active':
                            statusClass = 'status-active';
                            break;
                        case 'maintenance':
                            statusClass = 'status-maintenance';
                            break;
                        case 'idle':
                            statusClass = 'status-idle';
                            break;
                    }

                    row.innerHTML = `
                        <td>${log.sl_no}</td>
                        <td>${log.machine_id}</td>
                        <td>${log.machine_name}</td>
                        <td>${log.log_date} ${log.log_time}</td>
                        <td>${log.power_consumption} KWH</td>
                        <td><span class="status ${statusClass}">${log.operational_status}</span></td>
                    `;
                    tableBody.appendChild(row);
                });

                prevBtn.disabled = page === 1;
                nextBtn.disabled = !hasMoreLogs;
            })
            .catch(error => console.error('Error fetching logs:', error));
    }

    fetchLogs(currentPage, currentSort, currentSortOrder, currentFilter, defaultStartDate, defaultEndDate);

    prevBtn.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            fetchLogs(currentPage, currentSort, currentSortOrder, currentFilter, startDateInput.value, endDateInput.value);
        }
    });

    nextBtn.addEventListener('click', function () {
        currentPage++;
        fetchLogs(currentPage, currentSort, currentSortOrder, currentFilter, startDateInput.value, endDateInput.value);
    });

    document.querySelectorAll('.sort-option').forEach(button => {
        button.addEventListener('click', function () {
            document.querySelectorAll('.sort-option').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentSort = this.dataset.sort;
        });
    });

    document.querySelectorAll('input[name="sortOrder"]').forEach(radio => {
        radio.addEventListener('change', function () {
            currentSortOrder = this.value;
        });
    });

    document.querySelectorAll('.filter-option').forEach(button => {
        button.addEventListener('click', function () {
            document.querySelectorAll('.filter-option').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
        });
    });

    doneBtn.addEventListener('click', function () {
        fetchLogs(currentPage, currentSort, currentSortOrder, currentFilter, startDateInput.value, endDateInput.value);
        modal.style.display = "none";
    });

    resetBtn.addEventListener('click', function () {
        currentSort = 'sl_no';
        currentSortOrder = 'asc';
        currentFilter = '';
        fetchLogs(currentPage, currentSort, currentSortOrder, currentFilter, defaultStartDate, defaultEndDate);
        modal.style.display = "none";
    });

    openOptionsBtn.addEventListener('click', function() {
        modal.style.display = "block";
    });

    span.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Go Button
    searchDateBtn.addEventListener('click', function () {
        fetchLogs(currentPage, currentSort, currentSortOrder, currentFilter, startDateInput.value, endDateInput.value);
    });
});
