<?php
session_start();

include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('error' => 'Unauthorized access.'));
    exit();
}

$uid = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT job_id FROM jobs WHERE UID = ?");
$stmt->bind_param("i", $uid);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $jobs = array();

    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }

    echo json_encode($jobs);
} else {
    http_response_code(500);
    echo json_encode(array('error' => 'Failed to fetch jobs.'));
}

$stmt->close();
$conn->close();
?>
