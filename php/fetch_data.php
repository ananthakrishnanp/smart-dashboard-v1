<?php
require 'db_connection.php';

$machine_id = $_POST['machine_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];


$query = "SELECT log_time AS time, temperature, pressure 
          FROM logs 
          WHERE machine_id = ? 
          AND log_date BETWEEN ? AND ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $machine_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($data);
?>

