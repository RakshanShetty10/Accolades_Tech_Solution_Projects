<?php
require_once '../config/connection.php';

date_default_timezone_set('Asia/Kolkata');
$date = $_POST['date'];

$data = '<table class="table table-sm"><tr><th>Slot</th><th>Status</th></tr>';

$timestamp = strtotime($date);
$selectedDay = date('l', $timestamp);
$query = "SELECT slot_timing, no_of_people FROM appointment_master WHERE weekday = '$selectedDay'";


$res = mysqli_query($conn, $query);
if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $data .= "<tr>";
        $resC = mysqli_query($conn, "SELECT appoint_id FROM appointment 
                WHERE appoint_slot_time = '$row[slot_timing]' AND appoint_date = '$date'");

        $count = mysqli_num_rows($resC);
        if ($count >= $row['no_of_people']) {
            $data .= "<td>" . $row['slot_timing'] . "</td><td>Booked</td>";
        } else {
            $data .= "<td>" . $row['slot_timing'] . "</td><td>Available</td>";
        }
        $data .= "</tr>";
    }
}

$data .= "</table>";

echo $data;

$conn->close();
