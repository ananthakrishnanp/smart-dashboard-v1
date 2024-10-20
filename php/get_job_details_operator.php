<?php
session_start();

include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('error' => 'Unauthorized access.'));
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$job_id = $data['job_id'];
$uid = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT job_id, status, description FROM jobs WHERE job_id = ? AND UID = ?");
$stmt->bind_param("ii", $job_id, $uid);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();

    if ($job) {
        echo json_encode(array('success' => true, 'job' => $job));
    } else {
        echo json_encode(array('success' => false, 'error' => 'Job not found or you do not have permission to access it.'));
    }
} else {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Failed to fetch job details.'));
}

$stmt->close();
$conn->close();
?>
