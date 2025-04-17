<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT TransactionDate, DD_ChequeDate, receipt_id, receipt_date FROM receipt 
    ORDER BY receipt_id DESC LIMIT 110");

if(mysqli_num_rows($res)>0){

    while($row = mysqli_fetch_assoc($res)){

        if(!empty($row['DD_ChequeDate'])){
            
            $dd_date = $row['DD_ChequeDate'];
        } else if(!empty($row['TransactionDate'])){

            $dd_date = $row['TransactionDate'];
        } else {

            $dd_date = $row['receipt_date'];
        }

        mysqli_query($conn, "UPDATE receipt SET dd_date = '$dd_date' 
            WHERE receipt_id = '$row[receipt_id]'");
    }
}
mysqli_close($conn);
?>