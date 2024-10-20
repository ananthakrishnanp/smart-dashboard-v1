<?php

session_start();
include 'db_connection.php'; 




$job_id = isset($_POST['job_id']) ? $_POST['job_id'] : null;
$UID = isset($_POST['UID']) ? $_POST['UID'] : null; 
$status = isset($_POST['status']) ? trim($_POST['status']) : null;
$machine_id = isset($_POST['machine_id']) ? $_POST['machine_id'] : null;
$description = isset($_POST['description']) ? trim($_POST['description']) : null;


if ($job_id !== null && $UID !== null && $status !== '' && $machine_id !== null && $description !== '') {
    try {
        
        $sql = "UPDATE jobs SET UID = ?, status = ?, machine_id = ?, description = ? WHERE job_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }

        
        $bind = $stmt->bind_param('ssisi', $UID, $status, $machine_id, $description, $job_id);
        if ($bind === false) {
            throw new Exception('Bind failed: ' . $stmt->error);
        }

        
        $exec = $stmt->execute();
        if ($exec) {
            
            $machine_status = null;
            if (in_array($status, ['completed'])) {
                $machine_status = 'offline';
            } elseif (in_array($status, ['in progress', 'waiting'])) {
                $machine_status = 'online'; 
            } elseif (in_array($status, ['aborted'])) {
                $machine_status = 'error'; 
            }

            
            if ($machine_status !== null) {
                $notification_sql = "INSERT INTO activity_notifications (machine_id, type, status) 
                                    VALUES (?, 'notification', ?)";
                $notification_stmt = $conn->prepare($notification_sql);
                if ($notification_stmt === false) {
                    throw new Exception('Prepare failed for notification: ' . $conn->error);
                }

               
                $notification_bind = $notification_stmt->bind_param("is", $machine_id, $machine_status);
                if ($notification_bind === false) {
                    throw new Exception('Bind failed for notification: ' . $notification_stmt->error);
                }

                
                $notification_exec = $notification_stmt->execute();
                if ($notification_exec === false) {
                    throw new Exception('Notification insert failed: ' . $notification_stmt->error);
                }

                $notification_stmt->close(); 
            }

           
            $_SESSION['success'] = "Job $job_id updated successfully!";
        } else {
            
            $_SESSION['error'] = "Error updating job: " . $stmt->error;
        }

        $stmt->close(); 

        header('Location: ../jobs.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header('Location: ../jobs.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid input';
    header('Location: ../jobs.php');
    exit();
}
?>
