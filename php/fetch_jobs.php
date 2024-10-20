<?php
include 'db_connection.php';

header('Content-Type: application/json');

try {
    // Query to fetch all job ids and descriptions
    $sql = "SELECT job_id, description FROM jobs";
    $result = $conn->query($sql);

    $jobs = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobs[] = array(
                "job_id" => $row["job_id"],
                "description" => $row["description"]
            );
        }
    }

    echo json_encode($jobs);
} catch (Exception $e) {
    echo json_encode(array("error" => "Failed to fetch jobs: " . $e->getMessage()));
}
?>
