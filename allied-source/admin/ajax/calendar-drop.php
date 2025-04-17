<?php
require_once '../../config/connection.php';

date_default_timezone_set('Asia/Kolkata');
//$date = "2024-11-01";
$date = $_POST['date'];

$data .= '<option value="">Choose</option>';
$selectedDay = date('l', $timestamp);
$res = mysqli_query($conn, "SELECT slot_timing, no_of_people FROM appointment_master WHERE weekday = '$selectedDay'");


if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $resC = mysqli_query($conn, "SELECT appoint_id FROM appointment 
                WHERE appoint_slot_time = '$row[slot_timing]' AND appoint_date = '$date'");

        $count = mysqli_num_rows($resC);
        if ($count >= $row['no_of_people']) {
            $data .= "<option value='" . $row['slot_timing'] . "' disabled>$row[slot_timing]</option>";
        } else {
            $data .= "<option value='" . $row['slot_timing'] . "'>$row[slot_timing]</option>";
        }

        // if(mysqli_num_rows($resC)>0){
        //     $data .= "<option value='".$row['slot_timing']."' disabled>$row[slot_timing]</option>";
        // } else{
        //     $data .= "<option value='".$row['slot_timing']."'>$row[slot_timing]</option>";
        // }
    }
}

echo $data;

$conn->close();
