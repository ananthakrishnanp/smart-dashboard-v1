<?php

session_start();
include 'db_connection.php'; 

$job_id = isset($_POST['job_id']) ? $_POST['job_id'] : null;

if ($job_id !== null) {
    try {
        
        $sql = "DELETE FROM jobs WHERE job_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }

        $bind = $stmt->bind_param('i', $job_id);
        if ($bind === false) {
            throw new Exception('Bind failed: ' . $stmt->error);
        }

        $exec = $stmt->execute();
        if ($exec) {
            
            $_SESSION['success'] = "Job $job_id deleted successfully!";
        } else {
       
            $_SESSION['error'] = "Error deleting job: " . $stmt->error;
        }

        $stmt->close();

       
        header('Location: ../jobs.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error deleting job: " . $e->getMessage();
        header('Location: ../jobs.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid job ID';
    header('Location: ../jobs.php');
    exit();
}
?>
