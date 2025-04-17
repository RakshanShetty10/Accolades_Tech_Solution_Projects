<?php
require_once '../../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $title = $data['title'];
    $start_date = $data['start'];
    $end_date = $data['end'];
    $all_day = $data['allDay'];

    $stmt = $conn->prepare("INSERT INTO events (title, start_date, end_date, all_day) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $start_date, $end_date, $all_day);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>