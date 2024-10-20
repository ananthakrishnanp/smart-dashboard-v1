<?php
// get_alerts.php


session_start();

require_once 'db_connection.php'; 


if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}


$managerId = $_SESSION['user_id'];


try {
    
    $sql = "
    SELECT tn.note_id, tn.task_sub, DATE_FORMAT(j.start_time, '%Y-%m-%d %H:%i') as timestamp
    FROM task_notes tn
    INNER JOIN jobs j ON tn.job_id = j.job_id
    WHERE tn.manager_id = ?   -- Filter by the logged-in manager's ID
    ORDER BY j.start_time DESC
";

    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    
    $stmt->bind_param('s', $managerId);


    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $alerts = [];

      
        while ($row = $result->fetch_assoc()) {
            $alerts[] = $row;
        }

        echo json_encode($alerts);
    } else {
        
        throw new Exception("Failed to execute query: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch alerts', 'details' => $e->getMessage()]);
}
?>