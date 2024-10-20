<?php
include 'db_connection.php'; 


$sql = "SELECT job_id, machine_id, status, start_time, description FROM jobs ORDER BY start_time DESC";
$result = $conn->query($sql);

$jobs = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = array(
            'id' => '#J' . $row['job_id'],
            'machine' => 'M' . $row['machine_id'],
            'time' => date('H:i A', strtotime($row['start_time'])),
            'status' => ucfirst($row['status']),
            'description' => $row['description'] ? $row['description'] : 'No description provided'
        );
    }
} else {
    $jobs = array('message' => 'No active jobs found');
}

header('Content-Type: application/json');
echo json_encode($jobs);
$conn->close();