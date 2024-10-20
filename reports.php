
<?php
session_start();
include './php/access_control.php';
$current_page = basename($_SERVER['PHP_SELF']);

restrict_access(['auditor']);


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
    <title>Reports - Smart Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

    <link rel="stylesheet" href="./css/reports.css">
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

                <!-- Reports Section -->
                <?php include 'realtime_metrics.php'; ?>
                <div class="card">
                    
    <div class="card-header">
        <h2 class="card-title">Factory Status</h2>
 
        <div class="card-action-buttons">
            <button class="action-button" id="open-options-btn">Sort & Filter ▼</button>
            <form action="php/download.php" method="post">
                <button type="submit" class="action-button">↓ Download</button>
            </form>
        </div>
    </div>

                    <!-- Date Range Selection with Go Button -->
                    <div class="date-selection">
                        <label for="start-date">Start Date:</label>
                        <input type="date" id="start-date" name="start-date">

                        <label for="end-date">End Date:</label>
                        <input type="date" id="end-date" name="end-date">

                        <!-- "Go" button for date range search -->
                        <button class="action-button" id="search-date-btn">Go</button>
                    </div>

                    <!-- Table to display logs -->
                    <div class="status-box">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL NO</th>
                                    <th>MACHINE ID</th>
                                    <th>MACHINE NAME</th>
                                    <th>LOG DATE & TIME</th>
                                    <th>POWER (KWH)</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="status-table-body">
                    
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination controls -->
                    <div class="pagination">
                        <button id="prevBtn" disabled>Previous</button>
                        <button id="nextBtn">Next</button>
                    </div>
                </div>
            </div>

            <!-- Right-side Notifications and Activities Pane -->
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

    <!-- Sort & Filter Modal -->
    <div id="optionsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Sort On</h2>

            <!-- Sort Direction -->
            <div class="sort-direction">
                <label>
                    <input type="radio" name="sortOrder" value="asc" checked>
                    Ascending
                </label>
                <label>
                    <input type="radio" name="sortOrder" value="desc">
                    Descending
                </label>
            </div>

            <!-- Sort Options -->
            <div class="sort-options">
                <button class="sort-option" data-sort="sl_no">SL NO</button>
                <button class="sort-option" data-sort="machine_id">MACHINE ID</button>
                <button class="sort-option" data-sort="log_date">LOG DATE</button>
                <button class="sort-option" data-sort="power_consumption">POWER (KWH)</button>
            </div>

            <!-- Filter Options -->
            <h2>Filter By Status</h2>
            <div class="filter-options">
                <button class="filter-option" data-filter="Active">Active</button>
                <button class="filter-option" data-filter="Maintenance">Maintenance</button>
                <button class="filter-option" data-filter="Idle">Idle</button>
            </div>

<div class="modal-action-buttons">
    <button class="action-button" id="reset-btn">Reset Filters</button>
    <button class="action-button" id="done-btn">Done</button>
</div>

        </div>
    </div>

   
    <div class="success-message" id="success-message"></div>
    <div class="error-message" id="error-message"></div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
    // Elements
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
   
    <script src="./js/reports.js" defer></script>
    
    <script src="./js/script.js" defer></script>
</body>
</html>
