<?php
require 'db_connection.php'; // Include your database connection

// Query to fetch machine data
$query = "SELECT machine_id, machine_name FROM machines";
$result = $conn->query($query);


$machines = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $machines[] = [
            'machine_id' => $row['machine_id'],
            'machine_name' => $row['machine_name']
        ];
    }
}


echo json_encode($machines);

$conn->close();
?>
