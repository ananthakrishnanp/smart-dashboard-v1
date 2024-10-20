<?php
session_start();

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "User not logged in.";
        header('Location: ../jobs.php');
        exit();
    }


    if (empty($_POST['job_id']) || empty($_POST['status']) || empty($_POST['description'])) {
        $_SESSION['error'] = "All form fields are required.";
        header('Location: ../jobs.php');
        exit();
    }

    
    $job_id = intval($_POST['job_id']); 
    $status = $_POST['status'];
    $description = $_POST['description'];
    $uid = $_SESSION['user_id']; 

 
    $stmt = $conn->prepare("UPDATE jobs SET status = ?, description = ? WHERE job_id = ? AND UID = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }


    if (!$stmt->bind_param("ssis", $status, $description, $job_id, $uid)) {
        die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    }

   
    $exec = $stmt->execute();
    if ($exec) {
        
        $stmt_get_machine = $conn->prepare("SELECT machine_id FROM jobs WHERE job_id = ? AND UID = ?");
        if (!$stmt_get_machine) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        if (!$stmt_get_machine->bind_param("is", $job_id, $uid)) {
            die("Binding parameters failed: (" . $stmt_get_machine->errno . ") " . $stmt_get_machine->error);
        }
        if (!$stmt_get_machine->execute()) {
            die("Execute failed: (" . $stmt_get_machine->errno . ") " . $stmt_get_machine->error);
        }
        $result = $stmt_get_machine->get_result();
        if ($result->num_rows === 0) {
            $_SESSION['error'] = "No matching job found.";
            header('Location: ../jobs.php');
            exit();
        }
        $row = $result->fetch_assoc();
        $machine_id = $row['machine_id'];
        $stmt_get_machine->close();

        
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
                die('Prepare failed for notification: ' . $conn->error);
            }

            
            $notification_bind = $notification_stmt->bind_param("is", $machine_id, $machine_status);
            if ($notification_bind === false) {
                die('Bind failed for notification: ' . $notification_stmt->error);
            }

            
            $notification_exec = $notification_stmt->execute();
            if ($notification_exec === false) {
                die('Notification insert failed: ' . $notification_stmt->error);
            }

            $notification_stmt->close(); 
        }

        $_SESSION['success'] = "Job updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update job. Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: ../jobs.php');
    exit();
} else {
    $_SESSION['error'] = "Invalid request method.";
    header('Location: ../jobs.php');
    exit();
}
?>
