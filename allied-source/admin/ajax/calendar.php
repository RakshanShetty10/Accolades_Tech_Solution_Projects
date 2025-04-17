<?php
    require_once '../../config/connection.php';

    date_default_timezone_set('Asia/Kolkata');

    $sql = "SELECT calender_date,calendar_description FROM calender WHERE calender_status = 'Active' AND calender_holiday = 'Yes'";
    $result = $conn->query($sql);
    
    $events = array();
    $desc = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if(!empty($row['calendar_description'])){
                $desc = $row['calendar_description'];
            }
            $events[] = array(
                'start' => $row['calender_date'],
                'backgroundColor' => '#ff000', 
                'borderColor' => '#ff000',
                'title' => $desc,
            );
        }
    }
    
    echo json_encode($events);
    
    $conn->close();
?>