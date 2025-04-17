<?php
require_once '../../config/connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$point = 0;

if ($id > 0) {

    $resPoints = mysqli_query($conn, "SELECT COALESCE(SUM(cde_points),0) AS points FROM 
        cde_points WHERE practitioner_id = '$row[practitioner_id]'");
    if(mysqli_num_rows($resPoints)>0){
        
        $resPoints = mysqli_fetch_assoc($resPoints);

        $point = $resPoints['points'];
    }
}

echo json_encode(['point' => $point]);

mysqli_close($conn);
?>