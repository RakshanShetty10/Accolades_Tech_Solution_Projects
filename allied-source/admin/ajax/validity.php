<?php
require_once '../../config/connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? (int)$_GET['type'] : 0;
$status = isset($_GET['status']) ? $_GET['status'] : 'Choose';

$validity_date = "";
$valid = "";

if ($id > 0) {

    $resReceipt = mysqli_query($conn, "SELECT receipt_validity FROM
        receipt WHERE (receipt_for_id = 1 OR receipt_for_id = 2 
        OR receipt_for_id = 4) AND practitioner_id = '$id'AND receipt_status = 'Active'
        ORDER BY receipt_id DESC");
    if(mysqli_num_rows($resReceipt)>0){
        $resReceipt = mysqli_fetch_assoc($resReceipt);

        if(!empty($resReceipt['receipt_validity'])){
            $account_validity = $resReceipt['receipt_validity'];
            $validity_date = $resReceipt['receipt_validity'];
        }
    }
}

if($type == 1 && !empty($validity_date)){
    $valid = ' | Valid Upto: ' . date_format(date_create($validity_date),'d/m/Y');
}

if($type == 1 && $status == 'Eligible' && !empty($validity_date)){

    $validity_date_ac = date_format(date_create($validity_date), 'Y-m-d');
    $currunt_date = date('Y-m-d');
    $currunt_date = strtotime($currunt_date);
    $validity_date_ac = strtotime($validity_date_ac);

    if ($currunt_date > $validity_date_ac){
        $status = 'Not Renewed';
    }
}

echo json_encode(['validity' => $valid, 'vote' => $status]);

mysqli_close($conn);
?>