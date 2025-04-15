<?php
require_once '../config/connection.php';

date_default_timezone_set('Asia/Kolkata');
$date = $_POST['date'];

// Initialize the dropdown options with a default placeholder
$data = '<option value="">Choose</option>';

$timestamp = strtotime($date);
$isSaturday = date('N', $timestamp) == 6;

// Determine the query based on whether it's a Saturday
$selectedDay = date('l', $timestamp);
$query = "SELECT slot_timing, no_of_people FROM appointment_master WHERE weekday = '$selectedDay'";

$res = mysqli_query($conn, $query);

if ($res && mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $slotTiming = $row['slot_timing'];
        $noOfPeople = $row['no_of_people'];

        // Check if the slot is already full for the selected date
        $resC = mysqli_query($conn, "SELECT appoint_id FROM appointment 
                WHERE appoint_slot_time = '$slotTiming' AND appoint_date = '$date'");

        $count = $resC ? mysqli_num_rows($resC) : 0;

        // Add the slot as enabled or disabled based on availability
        if ($count >= $noOfPeople) {
            $data .= "<option value='" . $slotTiming . "' disabled>$slotTiming (Full)</option>";
        } else {
            $data .= "<option value='" . $slotTiming . "'>$slotTiming</option>";
        }
    }
} else {
    // Handle the case where no slots are found
    $data .= '<option value="" disabled>No slots available</option>';
}

echo $data;

$conn->close();
