<?php
require_once '../../../config/connection.php';

$result = $conn->query("SELECT id, title, start_date, end_date, all_day FROM events");

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);

$conn->close();
?>