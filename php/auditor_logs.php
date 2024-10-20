<div class="container">
        <h1>Factory Status</h1>

        <!-- sorting, filtering, and download -->
        <div class="action-buttons">
            <button class="action-button" id="open-options-btn">Sort & Filter ▼</button>
            <form action="../php/download.php" method="post">
                <button type="submit" class="action-button">↓ Download</button>
            </form>
        </div>

        <!-- Date Range Selection with Go Button -->
        <div class="date-selection">
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" name="start-date">
        
            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" name="end-date">

            <!-- Go Button -->
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
                <button class="filter-option" data-filter="active">Active</button>
                <button class="filter-option" data-filter="maintenance">Maintenance</button>
                <button class="filter-option" data-filter="idle">Idle</button>
            </div>

            <!-- Reset Filters and Done Button -->
            <div class="action-buttons">
                <button class="action-button" id="reset-btn">Reset Filters</button>
                <button class="action-button" id="done-btn">Done</button>
            </div>
        </div>
    </div>

    
    <script src="../js/auditor_logs.js" defer></script>
<link rel="stylesheet" href="../css/auditor_logs.css">