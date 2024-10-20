<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $machine_id = $_POST['machine_id'];
    
    $query = "DELETE FROM machines WHERE machine_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $machine_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Machine deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete machine';
    }

    header('Location: ../machines.php');
}
?>
