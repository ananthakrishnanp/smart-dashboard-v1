<?php

include 'db_connection.php'; 


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT activity_notifications.status, activity_notifications.job_id, activity_notifications.machine_id, activity_notifications.timestamp 
        FROM activity_notifications 
        WHERE activity_notifications.type = 'notification' 
        ORDER BY activity_notifications.timestamp DESC";

$result = $conn->query($sql);


if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

$notifications = array();


if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $notification = array(
            "status" => $row["status"],
            "item" => !empty($row["machine_id"]) ? "Machine ID: " . $row["machine_id"] : "Job ID: " . $row["job_id"],
            "time" => date('Y-m-d H:i:s', strtotime($row["timestamp"]))
        );
        array_push($notifications, $notification);
    }
} else {
    echo json_encode(array("message" => "No notifications found"));
    exit; 
}


header('Content-Type: application/json');
echo json_encode($notifications);

$conn->close();
?>
