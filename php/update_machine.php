<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $machine_id = $_POST['machine_id'];
    $machine_name = $_POST['machine_name'];
    
    $query = "UPDATE machines SET machine_name = ? WHERE machine_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $machine_name, $machine_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Machine updated successfully';
    } else {
        $_SESSION['error'] = 'Failed to update machine';
    }

    header('Location: ../machines.php');
}
?>
