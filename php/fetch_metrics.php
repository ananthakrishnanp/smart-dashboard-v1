<?php

header('Content-Type: application/json');


include('db_connection.php');


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$query = "SELECT log_time, operational_status, error_code FROM logs"; // Replace 'log_time' with the actual column name if different
$result = mysqli_query($conn, $query);


if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$onlineData = [];
$errorData = [];
$offlineData = [];


while ($row = mysqli_fetch_assoc($result)) {
    
    $time = date('H:i', strtotime($row['log_time'])); 

    // Machine Status
    if ($row['operational_status'] == 'active' && empty($row['error_code'])) {
        if (!isset($onlineData[$time])) $onlineData[$time] = 0;
        $onlineData[$time]++;
    } elseif (!empty($row['error_code'])) {
        if (!isset($errorData[$time])) $errorData[$time] = 0;
        $errorData[$time]++;
    } else {
        if (!isset($offlineData[$time])) $offlineData[$time] = 0;
        $offlineData[$time]++;
    }
}


foreach (range(0, 23) as $hour) {
    $timeStr = str_pad($hour, 2, '0', STR_PAD_LEFT) . ":00";

    // Fill missing hours with 0 
    $onlineData[$timeStr] = $onlineData[$timeStr] ?? 0;
    $errorData[$timeStr] = $errorData[$timeStr] ?? 0;
    $offlineData[$timeStr] = $offlineData[$timeStr] ?? 0;
}


echo json_encode([
    'online' => $onlineData,
    'error' => $errorData,
    'offline' => $offlineData
]);
?>