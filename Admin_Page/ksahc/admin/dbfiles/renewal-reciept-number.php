<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT receipt_temp_id, RenewalID FROM receipt_temp
    WHERE receipt_number IS NULL ORDER BY receipt_temp_id DESC LIMIT 1000000 OFFSET 0");
if(mysqli_num_rows($res)>0){

    while($row = mysqli_fetch_assoc($res)){

        $number = '0';
        $resNu = mysqli_query($conn, "SELECT receipt_number FROM receipt WHERE RenewalID = '$row[RenewalID]'");
        if(mysqli_num_rows($resNu)>0){

            $resNu = mysqli_fetch_assoc($resNu);
            $number = $resNu['receipt_number'];
            
            mysqli_query($conn, "UPDATE receipt_temp SET receipt_number = '$number' WHERE receipt_temp_id = '$row[receipt_temp_id]'");
        }
    }
}
mysqli_close($conn);
?>