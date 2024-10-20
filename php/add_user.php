<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UID = $_POST['UID'];
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("INSERT INTO users (UID, username, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $UID, $username, $hashed_password, $first_name, $last_name, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'User added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add user. Error: ' . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    header('Location: ../roles.php');
    exit();
}
?>
