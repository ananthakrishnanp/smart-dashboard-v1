<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "smart_factory";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


 
$machines = [
    '01' => 'CNC Machine',
    '02' => '3D Printer',
    '03' => 'Industrial Robot',
    '04' => 'Automated Guided Vehicle (AGV)',
    '05' => 'Smart Conveyor System',
    '06' => 'IoT Sensor Hub',
    '07' => 'Predictive Maintenance System',
    '08' => 'Automated Assembly Line',
    '09' => 'Quality Control Scanner',
    '10' => 'Energy Management System'
];


foreach ($machines as $machine_id => $machine_name) {
    $checkMachineQuery = $conn->prepare("SELECT COUNT(*) FROM machines WHERE machine_id = ?");
    $checkMachineQuery->bind_param('s', $machine_id);
    $checkMachineQuery->execute();
    $checkMachineQuery->bind_result($machine_count);
    $checkMachineQuery->fetch();
    $checkMachineQuery->close();

    
    if ($machine_count == 0) {
        $insertMachineQuery = $conn->prepare("INSERT INTO machines (machine_id, machine_name) VALUES (?, ?)");
        $insertMachineQuery->bind_param('ss', $machine_id, $machine_name);
        if ($insertMachineQuery->execute() === false) {
            echo "Error inserting machine data: " . $conn->error . "<br>";
        }
        $insertMachineQuery->close();
    } else {
        echo "Machine with ID $machine_id already exists. Skipping insert.<br>";
    }
}




$csvFile = "../assets/factory_logs.csv";


if (file_exists($csvFile)) {
    echo "File exists: " . $csvFile . "<br>";

    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        
        fgetcsv($handle);

        
        $insertLogQuery = $conn->prepare(
            "INSERT INTO logs (machine_id, log_date, log_time, temperature, pressure, vibration, humidity, power_consumption, operational_status, error_code, production_count, maintenance_log, speed)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

       
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            
            $original_timestamp = $row[0];
            $datetime = DateTime::createFromFormat('d/m/Y H:i', $original_timestamp);
            if ($datetime) {
                $log_date = $datetime->format('Y-m-d');  
                $log_time = $datetime->format('H:i:s');  
            } else {
                $log_date = null;
                $log_time = null;
            }

            $machine_name = $row[1];
            $temperature = !empty($row[2]) ? (float)$row[2] : null;
            $pressure = !empty($row[3]) ? (float)$row[3] : null;
            $vibration = !empty($row[4]) ? (float)$row[4] : null;
            $humidity = !empty($row[5]) ? (float)$row[5] : null;
            $power_consumption = !empty($row[6]) ? (float)$row[6] : null;
            $operational_status = !empty($row[7]) ? $row[7] : null;
            $error_code = !empty($row[8]) ? $row[8] : null;
            $production_count = !empty($row[9]) ? (int)$row[9] : null;
            $maintenance_log = !empty($row[10]) ? $row[10] : null;
            $speed = !empty($row[11]) ? (float)$row[11] : null;

            
            $machine_id = array_search($machine_name, $machines);

            if ($machine_id === false) {
                echo "Error: Machine name '" . $machine_name . "' not found in the machines array.<br>";
                continue; 
            }

            // Log data insertion
            $insertLogQuery->bind_param(
                'issddddssissd', 
                $machine_id, $log_date, $log_time, $temperature, $pressure, $vibration, $humidity, 
                $power_consumption, $operational_status, $error_code, $production_count, 
                $maintenance_log, $speed
            );
            
            if ($insertLogQuery->execute() === false) {
                echo "Error inserting log data: " . $conn->error . "<br>";
            }
        }

        
        fclose($handle);
        $insertLogQuery->close();
        $conn->close();

        echo "CSV data has been successfully imported into the logs table!";
    } else {
        echo "Error: Unable to open the CSV file.";
    }
} else {
    echo "Error: CSV file not found at " . $csvFile;
}
?>
