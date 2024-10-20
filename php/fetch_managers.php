<?php
include 'db_connection.php';


$sql = "SELECT UID, first_name, last_name FROM users WHERE role = 'factory_manager'";
$result = $conn->query($sql);

$managers = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $managers[] = array(
            'UID' => $row['UID'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']
        );
    }
}


header('Content-Type: application/json');
echo json_encode($managers);


$conn->close();
?>
