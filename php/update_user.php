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

    if (!empty($password)) {
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, first_name = ?, last_name = ?, role = ? WHERE UID = ?");
        $stmt->bind_param("ssssss", $username, $hashed_password, $first_name, $last_name, $role, $UID);
    } else {
       
        $stmt = $conn->prepare("UPDATE users SET username = ?, first_name = ?, last_name = ?, role = ? WHERE UID = ?");
        $stmt->bind_param("sssss", $username, $first_name, $last_name, $role, $UID);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = 'User updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update user. Error: ' . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    header('Location: ../roles.php');
    exit();
}
?>
