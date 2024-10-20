<?php
session_start();

function restrict_access($allowed_roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        
        header('Location: ./php/forbidden.php');
        exit();
    }
}
?>
