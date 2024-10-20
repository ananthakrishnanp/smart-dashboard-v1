<?php
session_start();


include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $machine_id = $_POST['machine_id'];
    $UID = $_POST['UID'];
    $status = $_POST['status'];
    $description = $_POST['description']; 

    if (!empty($machine_id) && !empty($UID) && !empty($status) && !empty($description)) {
        
        $stmt = $conn->prepare("INSERT INTO jobs (machine_id, UID, status, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $machine_id, $UID, $status, $description);

        
        if ($stmt->execute()) {
            
            $job_id = $stmt->insert_id;

            
            $activity_sql = "INSERT INTO activity_notifications (UID, job_id, type, status) VALUES (?, ?, 'activity', 'started')";
            $activity_stmt = $conn->prepare($activity_sql);
            $activity_stmt->bind_param("si", $UID, $job_id);
            $activity_stmt->execute();

           
            $_SESSION['success'] = "Job successfully added!";
            header('Location: ../jobs.php');
        } else {
            
            $_SESSION['error'] = "Failed to add job. Please try again.";
            header('Location: ../jobs.php');
        }

        $stmt->close();
    } else {
        
        $_SESSION['error'] = "All fields are required.";
        header('Location: ../jobs.php');
    }
} else {
    
    header('Location: ../jobs.php');
}

$conn->close();
?>
