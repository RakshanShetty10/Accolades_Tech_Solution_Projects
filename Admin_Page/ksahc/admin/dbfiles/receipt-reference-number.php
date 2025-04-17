<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT TransactionNo, DD_ChequeNO, receipt_id FROM receipt 
    ORDER BY receipt_id DESC LIMIT 110");

if(mysqli_num_rows($res)>0){

    while($row = mysqli_fetch_assoc($res)){

        if(!empty($row['DD_ChequeNO'])){
            
            $reference = $row['DD_ChequeNO'];
        } else{

            $reference = $row['TransactionNo'];
        }

        mysqli_query($conn, "UPDATE receipt SET receipt_reference_number = '$reference' 
            WHERE receipt_id = '$row[receipt_id]'");
    }
}
mysqli_close($conn);
?>