<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realtime Metrics</title>
  <link rel="stylesheet" href="css/audit_chart.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">   
</head>
<body>
    <div class="realtime-metrics">
        <div class="metrics-header">
          <h3>Realtime Metrics</h3>
          <div class="legend">
            <div class="legend-item">
              <span class="legend-color online"></span> Online
            </div>
            <div class="legend-item">
              <span class="legend-color offline"></span> Offline
            </div>
            <div class="legend-item">
              <span class="legend-color error"></span> Error
            </div>
          </div>
        </div>
        <canvas id="metricsChart"></canvas>
      </div>
  <script src="js/audit_chart.js"></script>
</body>
</html>
