<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT FeeItemId, RenewalID, FeeAmount, temp_id FROM tbl_renewal_items ORDER BY temp_id ASC LIMIT 100000 OFFSET 500000");
if(mysqli_num_rows($res)>0){

    while($row = mysqli_fetch_assoc($res)){

        $fee = '0';
        if($row['FeeItemId']==1){
            $fee = 1;
        } else if($row['FeeItemId']==2){
            $fee = '';
        } else if($row['FeeItemId']==3){
            $fee = 5;
        } else if($row['FeeItemId']==4){
            $fee = 6;
        } else if($row['FeeItemId']==5){
            $fee = 7;
        } else if($row['FeeItemId']==6){
            $fee = 8;
        } else if($row['FeeItemId']==7){
            $fee = 9;
        } else if($row['FeeItemId']==8){
            $fee = 10;
        } else if($row['FeeItemId']==9){
            $fee = 11;
        } else if($row['FeeItemId']==10){
            $fee = 12;
        } else if($row['FeeItemId']==11){
            $fee = 13;
        } else if($row['FeeItemId']==12){
            $fee = 14;
        } else if($row['FeeItemId']==25){
            $fee = 20;//reg P provi
        } else if($row['FeeItemId']==27){
            $fee = '';
        } else if($row['FeeItemId']==28){
            $fee = '';
        } else if($row['FeeItemId']==35){
            $fee = 13;
        } else if($row['FeeItemId']==36){
            $fee = 14;
        } else if($row['FeeItemId']==37){
            $fee = 21;//reg N provi
        } else if($row['FeeItemId']==48){
            $fee = 14;
        } else if($row['FeeItemId']==49){
            $fee = 15;
        } else if($row['FeeItemId']==50){
            $fee = '';
        } else if($row['FeeItemId']==52){
            $fee = '';
        } else if($row['FeeItemId']==65){
            $fee = 16;
        } else if($row['FeeItemId']==66){
            $fee = 17;
        } else if($row['FeeItemId']==68){
            $fee = '';
        } else if($row['FeeItemId']==69){
            $fee = 18;
        }

        // echo $row['RenewalID'];
        // echo "<br>";

        $number = '0';
        $resNu = mysqli_query($conn, "SELECT receipt_number FROM receipt WHERE RenewalID = '$row[RenewalID]'");
        if(mysqli_num_rows($resNu)>0){
            $resNu = mysqli_fetch_assoc($resNu);
            $number = $resNu['receipt_number'];
            
            $sql = "INSERT INTO receipt_temp (fees_id, total_amount, receipt_number) VALUES 
                ('$fee', '$row[FeeAmount]', '$number')";

            if(mysqli_query($conn, $sql)){
                
                // echo $row['RenewalID'];
                // echo "<br>";
                // echo $row['RenewalID'];
            } else{
                
                echo $row['temp_id'];
                echo "<br>";
            }
        }
    }
}

?>