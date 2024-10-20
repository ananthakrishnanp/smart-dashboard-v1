<?php

include 'db_connection.php'; 


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT users.first_name, activity_notifications.status, activity_notifications.job_id, activity_notifications.machine_id, activity_notifications.timestamp 
        FROM activity_notifications 
        JOIN users ON activity_notifications.UID = users.UID 
        WHERE activity_notifications.type = 'activity' 
        ORDER BY activity_notifications.timestamp DESC";
        
$result = $conn->query($sql);


if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

$activities = array();

if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $activity = array(
            "user" => $row["first_name"],
            "action" => $row["status"],
            "item" => !empty($row["job_id"]) ? "Job ID: " . $row["job_id"] : "Machine ID: " . $row["machine_id"],
            "time" => date('Y-m-d H:i:s', strtotime($row["timestamp"]))
        );
        array_push($activities, $activity);
    }
} else {
    echo json_encode(array("message" => "No activities found"));
    exit; 
}

header('Content-Type: application/json');
echo json_encode($activities);

$conn->close();
?>
