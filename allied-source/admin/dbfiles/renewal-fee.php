<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT fees_id_ksdc, receipt_temp_id FROM receipt_temp 
    ORDER BY receipt_temp_id DESC LIMIT 200");
if(mysqli_num_rows($res)>0){

    while($row = mysqli_fetch_assoc($res)){

        $fee = '0';
        if($row['fees_id_ksdc']==1){
            $fee = 1;
        } else if($row['fees_id_ksdc']==2){
            $fee = '';
        } else if($row['fees_id_ksdc']==3){
            $fee = 5;
        } else if($row['fees_id_ksdc']==4){
            $fee = 6;
        } else if($row['fees_id_ksdc']==5){
            $fee = 7;
        } else if($row['fees_id_ksdc']==6){
            $fee = 8;
        } else if($row['fees_id_ksdc']==7){
            $fee = 9;
        } else if($row['fees_id_ksdc']==8){
            $fee = 10;
        } else if($row['fees_id_ksdc']==9){
            $fee = 11;
        } else if($row['fees_id_ksdc']==10){
            $fee = 12;
        } else if($row['fees_id_ksdc']==11){
            $fee = 13;
        } else if($row['fees_id_ksdc']==12){
            $fee = 14;
        } else if($row['fees_id_ksdc']==25){
            $fee = 20;//reg P provi
        } else if($row['fees_id_ksdc']==27){
            $fee = '';
        } else if($row['fees_id_ksdc']==28){
            $fee = '';
        } else if($row['fees_id_ksdc']==35){
            $fee = 13;
        } else if($row['fees_id_ksdc']==36){
            $fee = 14;
        } else if($row['fees_id_ksdc']==37){
            $fee = 21;//reg N provi
        } else if($row['fees_id_ksdc']==48){
            $fee = 14;
        } else if($row['fees_id_ksdc']==49){
            $fee = 15;
        } else if($row['fees_id_ksdc']==50){
            $fee = '';
        } else if($row['fees_id_ksdc']==52){
            $fee = '';
        } else if($row['fees_id_ksdc']==65){
            $fee = 16;
        } else if($row['fees_id_ksdc']==66){
            $fee = 17;
        } else if($row['fees_id_ksdc']==68){
            $fee = '';
        } else if($row['fees_id_ksdc']==69){
            $fee = 18;
        }

        mysqli_query($conn, "UPDATE receipt_temp SET fees_id = '$fee' WHERE receipt_temp_id = '$row[receipt_temp_id]'");
        
    }
}
mysqli_close($conn);
?>