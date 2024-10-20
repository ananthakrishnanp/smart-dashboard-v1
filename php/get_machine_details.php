<?php
include 'db_connection.php';

if (isset($_GET['machine_id'])) {
    $machine_id = $_GET['machine_id'];

   
    $stmt = $conn->prepare("SELECT * FROM machines WHERE machine_id = ?");
    $stmt->bind_param("i", $machine_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $machine = $result->fetch_assoc();

        if ($machine) {
            echo json_encode(['success' => true, 'machine' => $machine]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Machine not found.']);
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

