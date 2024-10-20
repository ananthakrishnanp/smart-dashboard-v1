<?php
include 'db_connection.php';

if (isset($_GET['UID'])) {
    $UID = $_GET['UID'];

    
    $stmt = $conn->prepare("SELECT UID, username, first_name, last_name, role FROM users WHERE UID = ?");
    $stmt->bind_param("s", $UID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'error' => 'User not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error.']);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
