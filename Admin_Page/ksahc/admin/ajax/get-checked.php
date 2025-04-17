<?php
session_start();
require_once '../../config/connection.php';

$checkstatus = $_POST['checkStatus'];
$receiptNumbers = json_decode($_POST['receiptNumber']);
$date = date("Y-m-d H:i:s");

foreach ($receiptNumbers as $receiptNumber) {
    $receiptNumber = mysqli_real_escape_string($conn, $receiptNumber);

    $updateSql = "UPDATE receipt SET receipt_remit_status = '$checkstatus', receipt_remitted_on = '$date' WHERE receipt_number = '$receiptNumber'";

    mysqli_query($conn, $updateSql);
}
mysqli_close($conn);
?>