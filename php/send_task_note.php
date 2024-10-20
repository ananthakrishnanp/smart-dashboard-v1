<?php




header('Content-Type: application/json');


session_start();


include 'db_connection.php';


function send_response($success, $error = '') {
    echo json_encode(['success' => $success, 'error' => $error]);
    exit();
}


$task_sub = isset($_POST['task_sub']) ? trim($_POST['task_sub']) : '';
$task_body = isset($_POST['task_body']) ? trim($_POST['task_body']) : '';
$manager_id = isset($_POST['manager_id']) ? trim($_POST['manager_id']) : '';
// Sanitize the job_id in PHP
$job_id = isset($_POST['job_id']) ? intval(preg_replace("/\D/", "", $_POST['job_id'])) : '';
// Validate input
if (empty($task_sub) || empty($task_body) || empty($manager_id) || empty($job_id)) {
    send_response(false, 'All fields are required.');
}

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    send_response(false, 'User not authenticated.');
}

$user_id = $_SESSION['user_id'];


$job_id = intval($job_id); 


$stmt = $conn->prepare("INSERT INTO task_notes (job_id, manager_id, user_id, task_sub, task_body) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    send_response(false, 'Prepare failed: ' . $conn->error);
}



$stmt->bind_param("issss", $job_id, $manager_id, $user_id, $task_sub, $task_body);


if ($stmt->execute()) {
    send_response(true);
} else {
    send_response(false, 'Failed to send task note: ' . $stmt->error);
}


$stmt->close();
$conn->close();
?>
