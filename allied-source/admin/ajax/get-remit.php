<?php

    require_once '../../config/connection.php';

    date_default_timezone_set('Asia/Kolkata');
    $date = date("Y-m-d H:i:s");

	$id = $_POST['id'];
	$rem = $_POST['rem'];

    // $id = "202400004";
	// $rem = "Yes";

    mysqli_query($conn, "UPDATE receipt SET receipt_remit_status = '$rem', receipt_remitted_on = '$date' 
        WHERE receipt_number = '$id'");

    echo json_encode(true);

	mysqli_close($conn);

?>