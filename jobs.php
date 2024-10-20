<?php
session_start();

include './php/access_control.php';
$current_page = basename($_SERVER['PHP_SELF']);
restrict_access(['operator', 'factory_manager']);


if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}


$firstName = $_SESSION['first_name']; 
$role = $_SESSION['role']; 
$user_id = $_SESSION['user_id']; 


$roleMapping = [
    'admin' => 'Administrator',
    'operator' => 'Production Operator',
    'factory_manager' => 'Factory Manager',
    'auditor' => 'Auditor'
];

$formattedRole = isset($roleMapping[$role]) ? $roleMapping[$role] : ucfirst($role);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - Smart Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">


</head>
<body>
<div class="dashboard-container">
    <!-- Left Navigation -->
    <nav class="left-nav">
    <div class="logo-container">
        <h1>SMART <br>DASHBOARD</h1>
        <span class="version">V1</span>
    </div>
    <ul class="nav-items">
    <?php if (in_array($_SESSION['role'], ['admin', 'factory_manager','operator','auditor'])): ?>
        <li class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
            <a href="dashboard.php">
                <span class="icon">
                    <img src="./assets/overview.svg" alt="Overview" />
                </span>
                <span>Overview</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (in_array($_SESSION['role'], ['operator', 'factory_manager'])): ?>
        <li class="<?= ($current_page == 'jobs.php') ? 'active' : '' ?>">
            <a href="jobs.php">
                <span class="icon">
                    <img src="./assets/job.svg" alt="Jobs" />
                </span>
                <span>Jobs</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (in_array($_SESSION['role'], ['operator', 'factory_manager'])): ?>
        <li class="<?= ($current_page == 'machines.php') ? 'active' : '' ?>">
            <a href="machines.php">
                <span class="icon">
                    <img src="./assets/machines.svg" alt="Machines" />
                </span>
                <span>Machines</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (in_array($_SESSION['role'], ['admin'])): ?>
        <li class="<?= ($current_page == 'roles.php') ? 'active' : '' ?>">
            <a href="roles.php">
                <span class="icon">
                    <img src="./assets/roles.svg" alt="Roles" />
                </span>
                <span>Roles</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (in_array($_SESSION['role'], ['operator', 'factory_manager'])): ?>
        <li class="<?= ($current_page == 'alerts.php') ? 'active' : '' ?>">
            <a href="alerts.php">
                <span class="icon">
                    <img src="./assets/alerts.svg" alt="Alerts" />
                </span>
                <span>Alerts</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (in_array($_SESSION['role'], ['auditor'])): ?>
        <li class="<?= ($current_page == 'reports.php') ? 'active' : '' ?>">
            <a href="reports.php">
                <span class="icon">
                    <img src="./assets/reports.svg" alt="Reports" />
                </span>
                <span>Report</span>
            </a>
        </li>
    <?php endif; ?>
</ul>
</nav>

    <!-- Main Content -->
    <div class="main-content">
     
        <div class="top-bar">
        <div class="search-container">
    <input type="text" placeholder="Search" class="search-input" id="search-input">
    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
   
    <div class="search-results" id="search-results"></div>
</div>
            <div class="date-time" id="current-date-time"></div>
            <div class="nav-bar-icons">
               
                <a href="alerts.php" class="icon-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </a>

                <!-- Profile Button -->
                <div class="profile-menu">
                    <button class="icon-button profile-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </button>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <button id="logout-btn">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="logout-icon">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-layout">
            <div class="content-main">
            <?php if (in_array($_SESSION['role'], ['factory_manager','operator'])): ?>
        <!-- Action Buttons -->
        <div class="action-buttons">
                    <button class="action-btn" onclick="window.location.href='dashboard.php'">
                        <h2>FACTORY <br>MANAGEMENT</h2>
                        <img src="./assets/industry.svg" alt="Factory Icon" class="icon">
                    </button>

                    <button class="action-btn" onclick="window.location.href='machines.php'">
                        <h2>MACHINE <br>MANAGEMENT</h2>
                        <img src="./assets/robotic_arm.svg" alt="Machine Icon" class="icon">
                    </button>

                    <button class="action-btn" onclick="window.location.href='jobs.php'">
                        <h2>JOB <br>MANAGEMENT</h2>
                        <img src="./assets/job.svg" alt="Job Icon" class="icon">
                    </button>
                </div>

       <?php endif; ?>
                <?php if (in_array($_SESSION['role'], ['factory_manager'])): ?>
       
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Active Jobs</h2>
                        <div class="action-buttons">
                            <button class="edit-button" id="editJobBtn">Edit Job</button>
                        </div>
                    </div>
                    <div class="job-header">
                        <div>JOB ID</div>
                        <div>Machine ID</div>
                        <div>Start Time</div>
                        <div>Status</div>
                        <div>Description</div>
                    </div>
                    <div id="job-list"></div>
                    <button class="add-button" id="addJobButton">+</button>
                </div>

                <?php endif; ?>
                <?php if (in_array($_SESSION['role'], ['operator'])): ?>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Your Assigned Jobs</h2>
                        <div class="action-buttons">
                            <button class="edit-button" id="editJobBtn2">Edit Job</button>
                        </div>
                    </div>
                    <div class="job-header">
                        <div>JOB ID</div>
                        <div>Machine ID</div>
                        <div>Start Time</div>
                        <div>Status</div>
                        <div>Description</div>
                        <div>Action</div> 
                    </div>
                    <div id="operator-job-list"></div>
                </div>
                <?php endif; ?>

                
                

                   
<div class="modal" id="taskNoteModal">
    <div class="modal-content">
        <span class="close-btn" id="closeTaskNoteModal">&times;</span>
        <h2>Send Task Note</h2>
        <form id="send-task-note-form">

    <label for="taskSub">Task Subject</label>
    <input type="text" id="taskSub" name="task_sub" required>

    <label for="taskBody">Task Body</label>
    <textarea id="taskBody" name="task_body" required></textarea>

    <label for="managerSelect">Send to Manager</label>
    <select id="managerSelect" name="manager_id" required>
    <option value="" disabled selected>Select Manager</option>
    </select>

    <input type="hidden" id="note_job_id" name="job_id"> 

    <button type="submit">Send Task Note</button>
</form>

    </div>
</div>



                
                <div class="modal" id="selectJobPopup">
                    <div class="modal-content">
                        <span class="close-btn" id="closeSelectPopup">&times;</span>
                        <h2>Select Job</h2>
                        <label for="jobSelect">Job ID</label>
                        <select id="jobSelect"></select>
                        <button class="next-btn" id="nextBtn">NEXT</button>
                    </div>
                </div>

               
               
            <div class="modal" id="updateJobPopup">
            <div class="modal-content">
                <span class="close-btn" id="closeUpdatePopup">&times;</span>
                <h2>Update Job Attributes</h2>

           
                <form id="update-job-form" action="php/update_job.php" method="POST">
       
                    <input type="hidden" id="update_job_id" name="job_id">

                    <?php if ($_SESSION['role'] === 'factory_manager'): ?>
                <label for="edit_UID">Select User</label>
                <select id="edit_UID" name="UID" required>
                    <option value="" disabled selected>Loading users...</option>
                </select>
            <?php endif; ?>

                    <label for="startTimeInput">Start Time</label>
                    <input type="text" id="startTimeInput" name="start_time" readonly placeholder="Start Time" />

                    <label for="statusInput">Status</label>
                    <select id="statusInput" name="status">
                        <option value="in progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="waiting">Waiting</option>
                        <option value="aborted">Aborted</option>
                    </select>

                    <label for="edit_machine_id">Machine ID</label>
                    <select id="edit_machine_id" name="machine_id" required>
                        <option value="" disabled selected>Loading machines...</option>
                    </select>

               
                     <div class="desc-box">
                    <label for="edit_description">Job Description</label>
                    <textarea id="edit_description" name="description" required></textarea>
                    </div>
                    <button type="submit" class="update-btn">UPDATE</button>
                </form>


                <form id="delete-job-form" action="php/delete_job.php" method="POST">
                  
                    <input type="hidden" id="delete_job_id" name="job_id">
                    <button type="submit" class="delete-btn">DELETE JOB</button>
                </form>
            </div>
        </div>
</div>




      
                <div class="modal" id="selectJobPopupOperator">
                    <div class="modal-content">
                        <span class="close-btn" id="closeSelectPopupOperator">&times;</span>
                        <h2>Select Job</h2>
                        <label for="jobSelectOperator">Job ID</label>
                        <select id="jobSelectOperator"></select>
                        <button class="next-btn" id="nextBtnOperator">NEXT</button>
                    </div>
                </div>

                <div class="modal" id="updateJobPopupOperator">
                    <div class="modal-content">
                        <span class="close-btn" id="closeUpdatePopupOperator">&times;</span>
                        <h2>Update Job Attributes</h2>

                        
                        <form id="update-job-form-operator" action="php/update_job_operator.php" method="POST">
                           
                            <input type="hidden" id="update_job_id_operator" name="job_id">

   
                            <label for="statusInputOperator">Status</label>
                            <select id="statusInputOperator" name="status">
                                <option value="in progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="waiting">Waiting</option>
                                <option value="aborted">Aborted</option>
                            </select>

                          
                            <div class="desc-box">
                                <label for="edit_description_operator">Job Description</label>
                                <textarea id="edit_description_operator" name="description" required></textarea>
                            </div>
                            <button type="submit" class="update-btn">UPDATE</button>
                        </form>
                    </div>
                </div>



                
<div id="addJobModal" class="modal">
    <div class="modal-content">
    <span class="close-btn" id="closeAddJobModal">&times;</span>
        <h2>Add New Job</h2>
        <form id="add-job-form" action="php/add_job.php" method="POST">
            
            <label for="add_machine_id">Select Machine</label>
            <select id="add_machine_id" name="machine_id" required>
                <option value="" disabled selected>Loading machines...</option>
            </select>

            <label for="add_UID">Select User</label>
            <select id="add_UID" name="UID" required>
                <option value="" disabled selected>Loading users...</option>
            </select>

            <label for="status">Job Status</label>
            <select id="status" name="status" required>
                <option value="in progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="waiting">Waiting</option>
                <option value="aborted">Aborted</option>
            </select>

            <!-- New Description Field -->
            <label for="add_description">Job Description</label>
            <textarea id="add_description" name="description" required></textarea>

            <button type="submit">Add Job</button>
        </form>
    </div>
</div>


            
            <div class="side-pane">
                <div class="notifications-container">
                    <div class="notifications">
                        <h5>Notifications</h5>
                        <ul id="notification-list" class="list-unstyled"></ul>
                    </div>
                </div>
                <div class="activities-container">
                    <div class="activities">
                        <h5>Activities</h5>
                        <ul id="activity-list" class="list-unstyled"></ul>
                    </div>
                </div>
                <div class="user-profile">
                    <div class="profile-info">
                        <div class="user-details">
                            <h3><?php echo htmlspecialchars($firstName); ?></h3>
                            <p><?php echo htmlspecialchars($formattedRole); ?></p>
                            <p class="login-time">Login: <?php echo htmlspecialchars($_SESSION['login_time']); ?></p>
                        </div>
                        <div class="avatar">
                            <img src="./assets/user.svg" alt="User Avatar">
                        </div>
                    </div>
                    <button id="profile-logout-btn">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="logout-icon">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="success-message" id="success-message"></div>
<div class="error-message" id="error-message"></div>
<script>

let selectedJobId = null;

document.addEventListener("DOMContentLoaded", function () {
    
    const addJobModal = document.getElementById("addJobModal");
    const selectJobPopup = document.getElementById("selectJobPopup");
    const updateJobPopup = document.getElementById("updateJobPopup");
    const addButton = document.getElementById("addJobButton");
    const editButtonManager = document.getElementById("editJobBtn"); 
    const editButtonOperator = document.getElementById("editJobBtn2"); 
    const closeButtonAddJob = document.getElementById("closeAddJobModal");
    const closeButtonSelectJob = document.getElementById("closeSelectPopup");
    const closeButtonUpdateJob = document.getElementById("closeUpdatePopup");
    const jobSelect = document.getElementById("jobSelect"); 

   
    if (addButton) {
        addButton.onclick = function () {
            addJobModal.style.display = "block";
            fetchMachines('add_machine_id'); 
            fetchUsers('add_UID'); 
        };
    }

    
    if (closeButtonAddJob) {
        closeButtonAddJob.onclick = function () {
            addJobModal.style.display = "none";
        };
    }


    if (editButtonManager) {
        editButtonManager.onclick = function () {
            selectJobPopup.classList.add('modal-active'); 
            populateJobSelect(); 
        };
    }


    if (closeButtonSelectJob) {
        closeButtonSelectJob.onclick = function () {
            selectJobPopup.classList.remove('modal-active');
        };
    }

    
    const nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            selectedJobId = jobSelect.value;

            if (selectedJobId) {
                
                openUpdatePopup(selectedJobId);
            } else {
                alert("Please select a job!");
            }
        });
    }

  
    if (closeButtonUpdateJob) {
        closeButtonUpdateJob.onclick = function () {
            updateJobPopup.classList.remove('modal-active');
        };
    }


    if (editButtonOperator) {
        const selectJobPopupOperator = document.getElementById("selectJobPopupOperator");
        const closeButtonSelectJobOperator = document.getElementById("closeSelectPopupOperator");
        const jobSelectOperator = document.getElementById("jobSelectOperator");
        const nextBtnOperator = document.getElementById("nextBtnOperator");
        const updateJobPopupOperator = document.getElementById("updateJobPopupOperator");

        editButtonOperator.onclick = function () {
            selectJobPopupOperator.classList.add('modal-active');
            populateJobSelectOperator();
        };

        closeButtonSelectJobOperator.onclick = function () {
            selectJobPopupOperator.classList.remove('modal-active');
        };

       
        if (nextBtnOperator) {
            nextBtnOperator.onclick = function () {
                selectedJobId = jobSelectOperator.value;

                if (selectedJobId) {
          
                    openUpdatePopupOperator(selectedJobId);
                } else {
                    alert("Please select a job!");
                }
            };
        }

        
        const closeButtonUpdateJobOperator = document.getElementById("closeUpdatePopupOperator");
        if (closeButtonUpdateJobOperator) {
            closeButtonUpdateJobOperator.onclick = function () {
                updateJobPopupOperator.classList.remove('modal-active');
            };
        }
    }

    
    window.onclick = function (event) {
        if (event.target === addJobModal) {
            addJobModal.style.display = "none";
        }
        if (event.target === selectJobPopup) {
            selectJobPopup.classList.remove('modal-active');
        }
        if (event.target === updateJobPopup) {
            updateJobPopup.classList.remove('modal-active');
        }
        const selectJobPopupOperator = document.getElementById("selectJobPopupOperator");
        const updateJobPopupOperator = document.getElementById("updateJobPopupOperator");
        if (event.target === selectJobPopupOperator) {
            selectJobPopupOperator.classList.remove('modal-active');
        }
        if (event.target === updateJobPopupOperator) {
            updateJobPopupOperator.classList.remove('modal-active');
        }
    };


       
        function fetchMachines(selectElementId) {
            return fetch('php/fetch_machines.php', {
                method: 'GET',
                credentials: 'include'
            })
                .then(response => response.json())
                .then(data => {
                    const machineSelect = document.getElementById(selectElementId);
                    machineSelect.innerHTML = ''; 
                    data.forEach(machine => {
                        const option = document.createElement('option');
                        option.value = machine.machine_id;
                        option.textContent = `${machine.machine_name} (ID: ${machine.machine_id})`;
                        machineSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching machines:', error);
                });
        }


        function fetchUsers(selectElementId) {
            return fetch('php/fetch_users.php', {
                method: 'GET',
                credentials: 'include'
            })
                .then(response => response.json())
                .then(data => {
                    const userSelect = document.getElementById(selectElementId);
                    userSelect.innerHTML = ''; 
                    data.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.UID; 
                        option.textContent = `${user.first_name} ${user.last_name} (ID: ${user.UID})`;
                        userSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                });
        }


        async function populateJobSelect() {
            const jobs = await fetchJobs();
            jobSelect.innerHTML = ''; 

            jobs.forEach(job => {
                const option = document.createElement('option');
                option.value = job.job_id; 
                option.textContent = `Job ${job.job_id}`; 
                jobSelect.appendChild(option);
            });
        }

      
        async function fetchJobs() {
            try {
                const response = await fetch('php/fetch_jobs.php', {
                    method: 'GET',
                    credentials: 'include'
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                console.error('Error fetching jobs:', error);
                return [];
            }
        }

        
        async function openUpdatePopup(jobId) {
            const selectedJob = await fetchJobDetails(jobId);
            if (selectedJob) {
        
                if ('<?php echo $_SESSION['role']; ?>' === 'factory_manager') {
                    await fetchUsers('edit_UID');
                    document.getElementById('edit_UID').value = selectedJob.UID;
                }
                document.getElementById('startTimeInput').value = selectedJob.start_time;
                document.getElementById('statusInput').value = selectedJob.status;

                await fetchMachines('edit_machine_id');
                document.getElementById('edit_machine_id').value = selectedJob.machine_id;

                
                document.getElementById('update_job_id').value = jobId;
                document.getElementById('delete_job_id').value = jobId;

       
                document.getElementById('edit_description').value = selectedJob.description;

                
                selectJobPopup.classList.remove('modal-active');
                updateJobPopup.classList.add('modal-active');
            }
        }

        
        async function fetchJobDetails(job_id) {
            try {
                const response = await fetch('php/get_job_details.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({ job_id: job_id })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                if (data.success) {
                    return data.job;
                } else {
                    alert('Error fetching job details: ' + data.error);
                    return null;
                }
            } catch (error) {
                console.error('Error fetching job details:', error);
                return null;
            }
        }

 
        async function populateJobSelectOperator() {
            const jobs = await fetchJobsOperator();
            const jobSelectOperator = document.getElementById("jobSelectOperator");
            jobSelectOperator.innerHTML = ''; 

            jobs.forEach(job => {
                const option = document.createElement('option');
                option.value = job.job_id; 
                option.textContent = `Job ${job.job_id}`; 
                jobSelectOperator.appendChild(option);
            });
        }

        
        async function fetchJobsOperator() {
            try {
                const response = await fetch('php/fetch_jobs_operator.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include', 
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                console.error('Error fetching jobs:', error);
                return [];
            }
        }

        
        async function openUpdatePopupOperator(jobId) {
            const selectedJob = await fetchJobDetailsOperator(jobId); 
            if (selectedJob) {
              
                document.getElementById('statusInputOperator').value = selectedJob.status;
                document.getElementById('edit_description_operator').value = selectedJob.description;

              
                document.getElementById('update_job_id_operator').value = jobId;

                document.getElementById("selectJobPopupOperator").classList.remove('modal-active');
                const updateJobPopupOperator = document.getElementById("updateJobPopupOperator");
                updateJobPopupOperator.classList.add('modal-active');

                const closeButtonUpdateJobOperator = document.getElementById("closeUpdatePopupOperator");
                closeButtonUpdateJobOperator.onclick = function () {
                    updateJobPopupOperator.classList.remove('modal-active');
                };
            }
        }

        
        async function fetchJobDetailsOperator(job_id) {
            try {
                const response = await fetch('php/get_job_details_operator.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include', 
                    body: JSON.stringify({ job_id: job_id })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                if (data.success) {
                    return data.job;
                } else {
                    alert('Error fetching job details: ' + data.error);
                    return null;
                }
            } catch (error) {
                console.error('Error fetching job details:', error);
                return null;
            }
        }

        
        function showMessage(message, isError = false) {
            const messageDiv = isError ? document.getElementById('error-message') : document.getElementById('success-message');
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';

          
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        }

       
        <?php if (isset($_SESSION['success'])): ?>
            showMessage("<?php echo $_SESSION['success']; ?>");
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            showMessage("<?php echo $_SESSION['error']; ?>", true);
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
  
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');


    const userRole = <?php echo json_encode($_SESSION['role']); ?>;
    const pages = [
        <?php if (in_array($_SESSION['role'], ['admin', 'factory_manager', 'operator', 'auditor'])): ?>
        ,{ name: 'Overview', url: 'dashboard.php' },
        <?php endif; ?>
        <?php if (in_array($_SESSION['role'], ['operator', 'factory_manager'])): ?>
        ,{ name: 'Jobs', url: 'jobs.php' },
        { name: 'Machines', url: 'machines.php' },
        { name: 'Alerts', url: 'alerts.php' },
        <?php endif; ?>
        <?php if (in_array($_SESSION['role'], ['admin'])): ?>
        ,{ name: 'Roles', url: 'roles.php' },
        <?php endif; ?>
        <?php if (in_array($_SESSION['role'], ['auditor'])): ?>
        ,{ name: 'Reports', url: 'reports.php' },
        <?php endif; ?>
    ];

   
    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase().trim();

   
        searchResults.innerHTML = '';

        if (query === '') {
            searchResults.style.display = 'none';
            return;
        }

        
        const filteredPages = pages.filter(page => page.name.toLowerCase().includes(query));

        if (filteredPages.length > 0) {
            filteredPages.forEach((page, index) => {
                const item = document.createElement('div');
                item.classList.add('search-item');
                item.textContent = page.name;
                item.dataset.url = page.url;

               
                item.addEventListener('click', function () {
                    window.location.href = page.url;
                });

                searchResults.appendChild(item);
            });

            searchResults.style.display = 'block';
        } else {
            const item = document.createElement('div');
            item.classList.add('search-item');
            item.textContent = 'No results found';
            searchResults.appendChild(item);
            searchResults.style.display = 'block';
        }
    });

    
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

  
    let currentFocus = -1;
    searchInput.addEventListener('keydown', function (e) {
        const items = searchResults.getElementsByClassName('search-item');
        if (e.keyCode === 40) { 
            currentFocus++;
            addActive(items);
        } else if (e.keyCode === 38) { 
            currentFocus--;
            addActive(items);
        } else if (e.keyCode === 13) { 
            e.preventDefault();
            if (currentFocus > -1) {
                if (items[currentFocus]) {
                    items[currentFocus].click();
                }
            }
        }
    });

    function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;
        items[currentFocus].classList.add('active');
        items[currentFocus].scrollIntoView({ block: 'nearest' });
    }

    function removeActive(items) {
        for (let item of items) {
            item.classList.remove('active');
        }
    }
});

</script>


<link rel="stylesheet" href="./css/active_jobs.css">
<script src="./js/script.js" defer></script>
</body>
</html>