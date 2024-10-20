<?php
include 'db_connection.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'sl_no';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '2024-04-01';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$offset = ($page - 1) * 10; 

// Sanitize inputs
$allowed_sort_fields = ['sl_no', 'machine_id', 'log_date', 'power_consumption'];
if (!in_array($sort_by, $allowed_sort_fields)) {
    $sort_by = 'sl_no';
}

$order = ($order === 'desc') ? 'DESC' : 'ASC';


$query = "SELECT logs.sl_no, logs.machine_id, machines.machine_name, logs.log_date, logs.log_time, logs.power_consumption, logs.operational_status
          FROM logs
          INNER JOIN machines ON logs.machine_id = machines.machine_id
          WHERE logs.log_date BETWEEN ? AND ?";

$params = [$start_date, $end_date];
$param_types = 'ss';

if (!empty($filter_status)) {
    $query .= " AND logs.operational_status = ?";
    $params[] = $filter_status;
    $param_types .= 's';
}

$query .= " ORDER BY $sort_by $order LIMIT 11 OFFSET ?";
$params[] = $offset;
$param_types .= 'i';

$stmt = $conn->prepare($query);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}


$has_more_logs = count($logs) > 10;
if ($has_more_logs) {
    array_pop($logs); 
}

echo json_encode(['logs' => $logs, 'has_more_logs' => $has_more_logs]);

$stmt->close();
$conn->close();
?>
