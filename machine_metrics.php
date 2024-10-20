
<div class="container">
    <h1>Factory Management Dashboard</h1>

    <div class="filters">
        <label for="machine-select">Select Machine:</label>
        <select id="machine-select">
            
        </select>

        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date" value="2024-04-01">

        <label for="end-date">End Date:</label>
        <input type="date" id="end-date" value="2024-04-01">
    </div>


    <div class="legend">
        <div class="legend-item">
            <span class="legend-color" style="background-color: rgba(255, 99, 132, 1);"></span> Temperature
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: rgba(54, 162, 235, 1);"></span> Pressure
        </div>
    </div>

    <div class="chart-container">
        <canvas id="vitals-chart"></canvas> 
    </div>

    <div id="status-message"></div>
</div>


<link rel="stylesheet" href="./css/style_charts.css">
<script src="./js/chart_script.js"></script>
