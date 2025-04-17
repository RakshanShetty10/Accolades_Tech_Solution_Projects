<?php
require_once '../../config/connection.php';

date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d H:i:s");

// Get the receipt numbers from the request
$receiptNumbers = $_POST['receipts'];

if (!empty($receiptNumbers)) {
    $receiptNumbersStr = "'" . implode("','", $receiptNumbers) . "'";

    $sql = "UPDATE receipt SET receipt_remit_status = 'Yes', receipt_remitted_on = '$date' 
            WHERE receipt_number IN ($receiptNumbersStr)";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

mysqli_close($conn);
?>
