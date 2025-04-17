<?php
require_once '../../config/connection.php';

if (isset($_GET['financial_year'])) {

    $financial_year = $_GET['financial_year'];
    $startYear = explode('-', $financial_year)[0];
    $endYear = explode('-', $financial_year)[1];

    $startDate = "$startYear-04-01"; 
    $endDate = "$endYear-03-31"; 

    $whereClause = "AND c.cashbook_date BETWEEN '$startDate' AND '$endDate'";

    $resM = mysqli_query($conn, "
        SELECT MAX(cn.cashbook_number) AS cashbook_number
        FROM cashbook_number_master cn 
        JOIN cashbook c ON cn.cashbook_number = c.cashbook_number 
        WHERE c.cashbook_type = 'Payment' 
        $whereClause
    ");

    if (mysqli_num_rows($resM) > 0) {
        $resM = mysqli_fetch_assoc($resM);
        echo $resM['cashbook_number'];
    } else {
        echo "No cashbook numbers found.";
    }
}
?>
