<?php
require 'db_connection.php';

$machine_id = $_POST['machine_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];


$query = "SELECT 
            AVG(temperature) AS avg_temperature, 
            AVG(pressure) AS avg_pressure, 
            AVG(humidity) AS avg_humidity ,
            AVG(vibration) AS avg_vibration
          FROM logs 
          WHERE machine_id = ? 
          AND log_date BETWEEN ? AND ? 
          AND temperature IS NOT NULL
          AND pressure IS NOT NULL
          AND humidity IS NOT NULL
          AND vibration IS NOT NULL";
;

$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $machine_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$averages = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode($averages);
?>
