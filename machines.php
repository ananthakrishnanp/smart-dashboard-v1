<?php
session_start();

include './php/access_control.php';
$current_page = basename($_SERVER['PHP_SELF']);

restrict_access(['operator','factory_manager']);


if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}


$firstName = $_SESSION['first_name']; 
$role = $_SESSION['role'];


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
    <title>Machines - Smart Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

    <style>
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); 
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%; 
            border-radius: 10px;
            text-align: center;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
        }

        
        .modal input,
        .modal select {
            display: block;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
        }

 
        .modal button:not(.delete-btn) {
            background-color: #4285f4;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal button:not(.delete-btn):hover {
            background-color: #357ae8;
        }

        .modal-active {
            display: block; 
        }

        .success-message,
        .error-message {
            display: none; 
            position: fixed;
            bottom: 16px;
            right: 16px;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
            font-size: 16px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .add-button {
            position: absolute;
            bottom: 16px;
            right: 16px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .edit-button {
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .add-button:hover {
            background-color: #005bb5;
        }

        .edit-button:hover {
            background-color: #005bb5;
        }

        
        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        
        .machine-list {
            margin-top: 20px;
        }

        .machine-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            
        }

        .machine-item:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }

        .machine-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            padding: 10px;
            
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    
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
        <!-- Top Bar -->
        <div class="top-bar">
        <div class="search-container">
    <input type="text" placeholder="Search" class="search-input" id="search-input">
    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
    <!-- Container for search results -->
    <div class="search-results" id="search-results"></div>
</div>
            <div class="date-time" id="current-date-time"></div>
            <div class="nav-bar-icons">
                <!-- Notifications Button -->
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

        <!-- Content Layout for Main and Notifications -->
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

                <!-- Machines Section -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Machines</h2>
                        <?php if ($role === 'factory_manager'): ?>
                            <div class="action-buttons">
                                <button class="edit-button" id="editMachineBtn">Edit Machine</button>
                            </div>
                        <?php endif; ?>
                        
                        
                    </div>
                    <div class="machine-header">
                        <div>MACHINE ID</div>
                        <div>MACHINE NAME</div>
                    </div>
                    <div id="machine-list" class="machine-list"></div>
                    
                    <?php if ($role === 'factory_manager'): ?>
                        <button class="add-button" id="addMachineButton">+</button>
                    <?php endif; ?>
                </div>

           
                <div id="addMachineModal" class="modal">
                    <div class="modal-content">
                    <span class="close-btn" id="closeAddMachineModal">&times;</span>
                        <h2>Add New Machine</h2>
                        <form id="add-machine-form" action="php/add_machine.php" method="POST">
                            <label for="machine_name">Machine Name</label>
                            <input type="text" id="machine_name" name="machine_name" required />
                            <button type="submit">Add Machine</button>
                        </form>
                    </div>
                </div>

               
                <div id="selectMachineModal" class="modal">
                    <div class="modal-content">
                    <span class="close-btn" id="closeSelectMachineModal">&times;</span>
                        <h2>Select Machine</h2>
                        <label for="machineSelect">Machine ID</label>
                        <select id="machineSelect"></select>
                        <button class="next-btn" id="nextBtn">NEXT</button>
                    </div>
                </div>

                
                <div id="updateMachineModal" class="modal">
                    <div class="modal-content">
                    <span class="close-btn" id="closeUpdateMachineModal">&times;</span>
                        <h2>Update Machine</h2>
                        <form id="update-machine-form" action="php/update_machine.php" method="POST">
                            <input type="hidden" id="update_machine_id" name="machine_id">
                            <label for="update_machine_name">Machine Name</label>
                            <input type="text" id="update_machine_name" name="machine_name" required />
                            <button type="submit" class="update-btn">UPDATE</button>
                        </form>
                        <form id="delete-machine-form" action="php/delete_machine.php" method="POST">
                            <input type="hidden" id="delete_machine_id" name="machine_id">
                            <button type="submit" class="delete-btn">DELETE MACHINE</button>
                        </form>
                    </div>
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


    <div class="success-message" id="success-message"></div>
    <div class="error-message" id="error-message"></div>

    <script>

    let selectedMachineId = null;

    document.addEventListener("DOMContentLoaded", function () {
        
        const addMachineModal = document.getElementById("addMachineModal");
        const selectMachineModal = document.getElementById("selectMachineModal");
        const updateMachineModal = document.getElementById("updateMachineModal");
        const addButton = document.getElementById("addMachineButton");
        const editButton = document.getElementById("editMachineBtn"); 
        const closeButtonAddMachine = document.getElementById("closeAddMachineModal");
        const closeButtonSelectMachine = document.getElementById("closeSelectMachineModal");
        const closeButtonUpdateMachine = document.getElementById("closeUpdateMachineModal");
        const machineSelect = document.getElementById("machineSelect"); 

      
        if (addButton) {
            addButton.onclick = function () {
                addMachineModal.style.display = "block";
            };
        }

        
        if (closeButtonAddMachine) {
            closeButtonAddMachine.onclick = function () {
                addMachineModal.style.display = "none";
            };
        }

        
        if (editButton) {
            editButton.onclick = function () {
                selectMachineModal.style.display = "block";
                populateMachineSelect(); 
            };
        }

   
        if (closeButtonSelectMachine) {
            closeButtonSelectMachine.onclick = function () {
                selectMachineModal.style.display = "none";
            };
        }

        
        if (closeButtonUpdateMachine) {
            closeButtonUpdateMachine.onclick = function () {
                updateMachineModal.style.display = "none";
            };
        }

        window.onclick = function (event) {
            if (event.target === addMachineModal) {
                addMachineModal.style.display = "none";
            }
            if (event.target === selectMachineModal) {
                selectMachineModal.style.display = "none";
            }
            if (event.target === updateMachineModal) {
                updateMachineModal.style.display = "none";
            }
        };

      
        function populateMachineList() {
            fetch('php/fetch_machines.php')
                .then(response => response.json())
                .then(data => {
                    const machineList = document.getElementById("machine-list");
                    machineList.innerHTML = '';

                    // Sort machines by machine_id
                    data.sort((a, b) => a.machine_id - b.machine_id);

                    data.forEach(machine => {
                        const div = document.createElement('div');
                        div.classList.add('machine-item');
                        div.innerHTML = `<div>${machine.machine_id}</div><div>${machine.machine_name}</div>`;
                        machineList.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error fetching machines:', error);
                });
        }

       
        function populateMachineSelect() {
            fetch('php/fetch_machines.php')
                .then(response => response.json())
                .then(data => {
                    machineSelect.innerHTML = ''; 

           
                    data.sort((a, b) => a.machine_id - b.machine_id);

                    data.forEach(machine => {
                        const option = document.createElement('option');
                        option.value = machine.machine_id;
                        option.textContent = `Machine ${machine.machine_id}: ${machine.machine_name}`;
                        machineSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching machines:', error);
                });
        }


        const nextBtn = document.getElementById('nextBtn');
        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                selectedMachineId = machineSelect.value;

                if (selectedMachineId) {
                    
                    openUpdatePopup(selectedMachineId);
                } else {
                    alert("Please select a machine!");
                }
            });
        }


        function openUpdatePopup(machineId) {
            fetch(`php/get_machine_details.php?machine_id=${machineId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const machine = data.machine;
                        document.getElementById('update_machine_id').value = machine.machine_id;
                        document.getElementById('update_machine_name').value = machine.machine_name;
                        document.getElementById('delete_machine_id').value = machine.machine_id;

                    
                        selectMachineModal.style.display = "none";
                        updateMachineModal.style.display = "block";
                    } else {
                        alert('Error fetching machine details: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error fetching machine details:', error);
                });
        }

        
        populateMachineList();


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


<link rel="stylesheet" href="./css/machines.css">
<script src="./js/script.js" defer></script>
</body>
</html>