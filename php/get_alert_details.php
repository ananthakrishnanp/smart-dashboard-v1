<?php


session_start();

require_once 'db_connection.php';


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'factory_manager') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}


$managerId = $_SESSION['user_id'];


if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Alert ID is required']);
    exit();
}

$alertId = intval($_GET['id']); 

try {
    
    $stmt = $conn->prepare("
        SELECT tn.note_id, tn.task_sub, tn.task_body, DATE_FORMAT(j.start_time, '%Y-%m-%d %H:%i') as timestamp
        FROM task_notes tn
        INNER JOIN jobs j ON tn.job_id = j.job_id
        WHERE tn.note_id = ? AND tn.manager_id = ?
    ");

    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    
    $stmt->bind_param('is', $alertId, $managerId);

    
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

   
    $result = $stmt->get_result();
    $alert = $result->fetch_assoc();

    if ($alert) {
        
        echo json_encode($alert);
    } else {
        
        http_response_code(404);
        echo json_encode(['error' => 'Alert not found']);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
   
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch alert details', 'details' => $e->getMessage()]);
}
?>
