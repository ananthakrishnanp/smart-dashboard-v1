<?php
session_start();
include './php/access_control.php';
$current_page = basename($_SERVER['PHP_SELF']);

restrict_access(['admin']);


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
    <title>Roles - Smart Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    
    <link rel="stylesheet" href="./css/users.css">

    <style>
        
    </style>
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
            <?php if (in_array($_SESSION['role'], ['factory_manager','operator'])): ?>
       <!-- Notifications Button -->
       <a href="alerts.php" class="icon-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </a>

       <?php endif; ?>
                
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
               
                <!-- Users Section -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Users</h2>
                        <div class="action-buttons">
                            <button class="edit-button">Edit User</button>
                        </div>
                    </div>
                    <div class="user-header">
                        <div>UID</div>
                        <div>Username</div>
                        <div>First Name</div>
                        <div>Last Name</div>
                        <div>Role</div>
                    </div>
                    <div id="user-list" class="user-list"></div>
                    <button class="add-button">+</button>
                </div>

 
                <div id="addUserModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <h2>Add New User</h2>
                        <form id="add-user-form" action="php/add_user.php" method="POST">
                            <label for="UID">User ID</label>
                            <input type="text" id="UID" name="UID" required />

                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required />

                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required />

                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" required />

                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required />

                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="admin">Administrator</option>
                                <option value="operator">Production Operator</option>
                                <option value="factory_manager">Factory Manager</option>
                                <option value="auditor">Auditor</option>
                            </select>

                            <button type="submit">Add User</button>
                        </form>
                    </div>
                </div>

                <!-- Select User Modal -->
                <div id="selectUserModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <h2>Select User</h2>
                        <label for="userSelect">User ID</label>
                        <select id="userSelect"></select>
                        <button class="next-btn" id="nextBtn">NEXT</button>
                    </div>
                </div>

                <!-- Update User Modal -->
                <div id="updateUserModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <h2>Update User</h2>
                        <form id="update-user-form" action="php/update_user.php" method="POST">
                            <input type="hidden" id="update_UID" name="UID">

                            <label for="update_username">Username</label>
                            <input type="text" id="update_username" name="username" required />

                            <label for="update_password">Password (Leave blank to keep current password)</label>
                            <input type="password" id="update_password" name="password" />

                            <label for="update_first_name">First Name</label>
                            <input type="text" id="update_first_name" name="first_name" required />

                            <label for="update_last_name">Last Name</label>
                            <input type="text" id="update_last_name" name="last_name" required />

                            <label for="update_role">Role</label>
                            <select id="update_role" name="role" required>
                                <option value="admin">Administrator</option>
                                <option value="operator">Production Operator</option>
                                <option value="factory_manager">Factory Manager</option>
                                <option value="auditor">Auditor</option>
                            </select>

                            <button type="submit" class="update-btn">UPDATE</button>
                        </form>
                        <form id="delete-user-form" action="php/delete_user.php" method="POST">
                            <input type="hidden" id="delete_UID" name="UID">
                            <button type="submit" class="delete-btn">DELETE USER</button>
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
  
        let selectedUID = null;

        document.addEventListener("DOMContentLoaded", function () {
     
            const addUserModal = document.getElementById("addUserModal");
            const selectUserModal = document.getElementById("selectUserModal");
            const updateUserModal = document.getElementById("updateUserModal");
            const addButton = document.querySelector(".add-button");
            const editButton = document.querySelector(".edit-button");
            const closeButtons = document.querySelectorAll(".close-btn");
            const userSelect = document.getElementById("userSelect"); 

          
            addButton.onclick = function () {
                addUserModal.style.display = "block";
            };

            
            closeButtons.forEach(function (btn) {
                btn.onclick = function () {
                    addUserModal.style.display = "none";
                    selectUserModal.style.display = "none";
                    updateUserModal.style.display = "none";
                };
            });


            editButton.onclick = function () {
                selectUserModal.style.display = "block";
                populateUserSelect(); 
            };

    
            window.onclick = function (event) {
                if (event.target === addUserModal) {
                    addUserModal.style.display = "none";
                }
                if (event.target === selectUserModal) {
                    selectUserModal.style.display = "none";
                }
                if (event.target === updateUserModal) {
                    updateUserModal.style.display = "none";
                }
            };

        
            function populateUserList() {
                fetch('php/fetch_users.php')
                    .then(response => response.json())
                    .then(data => {
                        const userList = document.getElementById("user-list");
                        userList.innerHTML = ''; 

                       
                        data.sort((a, b) => a.UID.localeCompare(b.UID));

                        data.forEach(user => {
                            const div = document.createElement('div');
                            div.classList.add('user-item');
                            div.innerHTML = `<div>${user.UID}</div>
                                             <div>${user.username}</div>
                                             <div>${user.first_name}</div>
                                             <div>${user.last_name}</div>
                                             <div>${user.role}</div>`;
                            userList.appendChild(div);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching users:', error);
                    });
            }

            
            function populateUserSelect() {
                fetch('php/fetch_users.php')
                    .then(response => response.json())
                    .then(data => {
                        userSelect.innerHTML = ''; 

                        data.sort((a, b) => a.UID.localeCompare(b.UID));

                        data.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.UID;
                            option.textContent = `${user.UID}: ${user.first_name} ${user.last_name}`;
                            userSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching users:', error);
                    });
            }

       
            document.getElementById('nextBtn').addEventListener('click', function () {
                selectedUID = userSelect.value;

                if (selectedUID) {
                   
                    openUpdatePopup(selectedUID);
                } else {
                    alert("Please select a user!");
                }
            });

      
            function openUpdatePopup(UID) {
                fetch(`php/get_user_details.php?UID=${UID}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const user = data.user;
                            document.getElementById('update_UID').value = user.UID;
                            document.getElementById('update_username').value = user.username;
                            document.getElementById('update_first_name').value = user.first_name;
                            document.getElementById('update_last_name').value = user.last_name;
                            document.getElementById('update_role').value = user.role;
                            document.getElementById('delete_UID').value = user.UID;

                            
                            selectUserModal.style.display = "none";
                            updateUserModal.style.display = "block";
                        } else {
                            alert('Error fetching user details: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching user details:', error);
                    });
            }

      
            populateUserList();

           
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

    <script src="./js/script.js" defer></script>
</body>
</html>
