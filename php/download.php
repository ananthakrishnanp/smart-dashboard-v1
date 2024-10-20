<?php

$file = '../assets/factory_logs.csv';

// Check if the file exists
if (file_exists($file)) {
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($file));
    header('Pragma: public');

    ob_clean();
    flush();


    readfile($file);
    exit;
} else {

    echo "File not found.";
}
?>
