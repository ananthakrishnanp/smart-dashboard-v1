<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UID = $_POST['UID'];

    
    $stmt = $conn->prepare("DELETE FROM users WHERE UID = ?");
    $stmt->bind_param("s", $UID);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'User deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete user. Error: ' . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    header('Location: ../roles.php');
    exit();
}
?>
