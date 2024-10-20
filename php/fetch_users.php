<?php
include 'db_connection.php';


$query = "SELECT UID, username, first_name, last_name, `role` FROM users";
$result = $conn->query($query);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
echo json_encode($users);
?>
