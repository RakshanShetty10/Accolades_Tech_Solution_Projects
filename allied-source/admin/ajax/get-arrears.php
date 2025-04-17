<?php
require_once '../../config/connection.php';
date_default_timezone_set('Asia/Kolkata');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 77243;
$type = isset($_GET['type']) ? (int)$_GET['type'] : 1;

$response = "0";

$account_validity = "";

if ($id > 0 && $type == 1) {

    $resReceipt = mysqli_query($conn, "SELECT receipt_validity FROM
        receipt WHERE (receipt_for_id = 1 OR receipt_for_id = 2 
        OR receipt_for_id = 4) AND practitioner_id = '$id'
        ORDER BY receipt_id DESC");
    if(mysqli_num_rows($resReceipt)>0){
        $resReceipt = mysqli_fetch_assoc($resReceipt);

        if(!empty($resReceipt['receipt_validity'])){
            $account_validity = $resReceipt['receipt_validity'];
        }
    }

    if(!empty($account_validity)){

        $currunt_date = date('Y-m-d');
        $currunt_date = strtotime($currunt_date);

        $validity_date = strtotime($account_validity);
        $valid_year = date('Y', $validity_date);
        $validity_end_date = strtotime(($valid_year + 1) . "-03-31");

        $years_pending = date('Y', $currunt_date) - date('Y', $validity_end_date);
        $month = date('m', $currunt_date);
        if ($month < 4) {
            $years_pending -= 1;
        }
        if($years_pending > 0){

            $response = 200 * $years_pending;
        }
    }
}

echo $response;

mysqli_close($conn);
?>