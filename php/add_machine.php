<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $machine_name = $_POST['machine_name'];
    $query = "INSERT INTO machines (machine_name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $machine_name);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Machine added successfully';
    } else {
        $_SESSION['error'] = 'Failed to add machine';
    }

    header('Location: ../machines.php');
}
?>
