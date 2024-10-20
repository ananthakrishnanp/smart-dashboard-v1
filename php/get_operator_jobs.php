<?php
session_start(); // Ensure the session is started
include 'db_connection.php'; // Include your existing database connection file

// Check if the user is logged in and ensure the 'user_id' is set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header('Location: index.php');
    exit();
}

// Fetch jobs for the current operator (logged-in user)
$operatorId = $_SESSION['user_id']; // Assuming 'user_id' is stored in session
$sql = "SELECT job_id, machine_id, status, start_time, description FROM jobs WHERE UID = ? ORDER BY start_time DESC"; // Adjust your field names and table structure as needed
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $operatorId);
$stmt->execute();
$result = $stmt->get_result();

$operatorJobs = array();

if ($result && $result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $operatorJobs[] = array(
            'id' => '#J' . $row['job_id'],
            'machine' => 'M' . $row['machine_id'],
            'time' => date('H:i A', strtotime($row['start_time'])),
            'status' => ucfirst($row['status']), // Capitalize the first letter of the status
            'description' => $row['description'] ? $row['description'] : 'No description' // Default text if description is null
        );
    }
} else {
    
    $operatorJobs = array('message' => 'No jobs assigned to you.');
}


header('Content-Type: application/json');
echo json_encode($operatorJobs);

$stmt->close();
$conn->close();
?>
